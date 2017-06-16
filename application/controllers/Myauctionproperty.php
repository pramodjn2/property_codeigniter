<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Myauctionproperty extends CI_Controller { 
	
	var $userID;
	var $message;
	public function __construct() {
		
		parent::__construct();
		$this->load->helper(array('common_admin'));
		$this->load->library(array('file_upload','fileupload','grocery_CRUD', 'ajax_grocery_crud'));
		$this->load->model(array('property_model'));			 		
		//check user login and get user_id from session.
		$this->userID = checkUserLogin();
		checkUserAccessPermission();
	}	
	
	public function index(){
		
		$crud = new grocery_CRUD();
		
		$crud->unset_print();
		$crud->unset_export();
		
		$crud->unset_bulk_publish();
		$crud->unset_bulk_unpublish();
		$crud->unset_bulk_delete();
		
		$crud->set_subject('Auction');
		
		$crud->set_table('property_auction');
		
		$crud->where('property_auction.user_id', $this->userID);

		$crud->callback_column('start_date',array($this,'_callback_start_date'));
		
		$crud->callback_column('end_date',array($this,'_callback_end_date'));
		
		$crud->set_relation('property_id', 'property', 'property_name');
		
		$crud->display_as('property_id','Property name');
		
		$crud->columns('property_id','start_date','end_date' , 'auction_min_price','status','action');
		
		
		
	    $crud->unset_fields('updatedDate');
		
		$crud->set_add_url_path(base_url('myproperties/add'));
		//$crud->set_edit_url_path(base_url('myproperties/edit'));
		
		$crud->set_read_url_path(base_url('property/auction_property/'));
		
		$crud->callback_column('action',array($this,'_custome_action_button'));
		
		$crud->unset_read();
		$crud->unset_delete();
		
		$crud->unset_edit();
		
				$stylesheet = array('assets/plugins/bootstrap-dialogbox/bootstrap-dialog.css');

		$scriptsrc = array('assets/plugins/bootstrap-dialogbox/bootstrap-dialog.js');

		$script = array("<script>$(document).ready(function(){

					   $('a.openPopupDialog').click(function(){

							var url = this.href;

							$.ajax({ 

								url: url, //'http://localhost/testing/ajax-dialog.php',								

								success: function(data){																	 

									  BootstrapDialog.show({

										size: BootstrapDialog.SIZE_LARGE,

										closable: false,

										title: 'Property overview stats',

										message: data,

										buttons: [{

													label: 'Close',

													action: function(dialogRef){

														dialogRef.close();

													}

												}]

									  });				

								}

							});

							return false; 

						}); 

					}); </script>");
		
		
		$output = $crud->render();
		
		
		$page_title = array('page_title' => 'Manage Auction Property', 'stylesheet'=>$stylesheet, 'scriptsrc'=>$scriptsrc, 'script'=>$script);
		$outputData = array_merge((array) $output , $page_title);
		$this->_example_output($outputData); 		
	}
	
	
	
	public function _example_output($output = null){
		$this->load->view('my_account/setting',$output);
	}
	
	public function _callback_start_date($value, $row){ 
	   $results=date('d/m/Y h:i A',$row->start_date);
       return $results;
    }
	
	public function _callback_end_date($value, $row){ 
	   $results=date('d/m/Y h:i A',$row->end_date);
       return $results;
    }
	
	public function view($id){
	echo $id;
	/* $this->load->model(Common);
	 $result=$this->Common->select('property_auction',"where property_auction_id='$id'");
	 
	 redirect('property/auction/'.$result[0]['property_id']);
	 */
	}
	
	function show_bid($primary_key , $row){
	

	
	    $mydata=select('property_auction',"where property_id='$primary_key'");
		
		
		if($mydata[0]['user_id']==$this->userID){
		

	    $data['page_title'] = 'Auction Property';
		
	   //$data['property_data']= $this->property_model->select('property',"where property_id='$proid'");
	   
	    $data['property_data']= getPropertyFullDetails($primary_key);
		
		$data['auction_data'] = getRecentAuction($primary_key);
									
		$this->load->view('my_account/recentbid',$data);
		
		}else{
		  redirect('myauctionproperty');
		}
		
    }
	
	public function _custome_action_button($primary_key, $row){
		//$row->user_id;
		
		
		 $this->load->model('Common');	
		 
	      $property=  $this->Common->property_info_get($row->property_id);

		  $bedrooms=$property[0]['bedrooms'];
		  $property_type_name=$property[0]['typeName'];
		  $category_name=$property[0]['categoryName'];
		  

		  $seo_url_string = ucwords($bedrooms.'  Bed '. $property_type_name.'  For '. $category_name);
		  $property_seo_url = seo_friendly_urls($seo_url_string,'',$row->property_id);
		  
		  
		$button = '<a href="'.base_url('myauctionproperty/viewStats/'.$row->property_id).'" class="btn btn-xs btn-default openPopupDialog crud-action" title="Stats"><i class="clip-stats"></i> Stats</a>&nbsp;';
		
		
		
		
		$button .= '<a  class="delete-row" title="Delete" href="'.base_url('myauctionproperty/delete/'.$row->property_auction_id.'/'.$row->property_id).'"><button type="button" class="btn btn-danger"><i class="fa fa-trash-o"></i></button></i></a>&nbsp;';
		
		
		
		
		$button .= '<a title="View" target="_blank" href="'.base_url('property/auction/'.$property_seo_url).'"><button class="btn btn-info" type="button">
	       			<i class="clip-expand"></i>
	  			</button></a>&nbsp;';
		
		$button .= '<a title="Show Bid" href="'.base_url('myauctionproperty/show_bid/'.$row->property_id).'"><button class="btn btn-info" type="button">
	       			<i class="fa fa-eye"></i>
	  			</button></a>&nbsp;';
				
				$button .= '<a title="Edit Auction" href="'.base_url('myproperties/edit/'.$row->property_id).'"><button class="btn btn-primary" type="button">
	       			<i class="fa fa-edit"></i>
	  			</button></a>';
				
				
				
					                    						
																
		
		return $button;
	}
	
	public function delete()
	{
		$property_auction_id = ($this->uri->segment(3))?$this->uri->segment(3):'';
		$property_id = ($this->uri->segment(4))?$this->uri->segment(4):'';
		
		if(!empty($property_auction_id))
		{
			$table = 'property_auction';
			
			$this->db->where('property_auction_id', $property_auction_id);
			$this->db->delete($table);
			
		
			
			
			$data = array('auction_status' => '0');

            $this->db->where('property_id', $property_id);
            $this->db->update('property', $data); 
			
			/**Property Delete*/
				    $table = 'property_image';
        $where = "where property_id = $property_id";
		$data = $this->property_model->select($table,$where);
		 $path = config_item('root_url').'applicationMediaFiles/propertiesImage/'; 
		if(!empty($data)){
		     foreach($data as $val){
				 
				 $image = $val['image_name'];
				 if (file_exists($path.$image)) { 
			    	 @unlink($path.$image);
				 }
				  if (file_exists($path.'thumb/'.$image)) { 
			    	@unlink($path.'thumb/'.$image);
				 }

				 if (file_exists($path.'350325/'.$image)) { 
			    	@unlink($path.'350325/'.$image);
				 }

				 if (file_exists($path.'350325/'.$image)) { 
			    	 @unlink($path.'350325/'.$image);
				 }

				 if (file_exists($path.'800600/'.$image)) { 
			    	 @unlink($path.'800600/'.$image);
				 }
				 
				 if (file_exists($path.'1300400/'.$image)) { 
			    	 @unlink($path.'1300400/'.$image);
				 }

				 
				 
				 
			 }	
		}


        $where = "where property_id = $primary_key";
		$data = $this->property_model->select('property',$where);
		if(!empty($data)){
		     foreach($data as $val){
				 $floor_plan = $val['floor_plan'];
				 $video_url = $val['video_url'];
		  $propertiesVedioUrl = config_item('root_url').'applicationMediaFiles/propertiesVedio/'; 
		  $floorPlanUrl = config_item('root_url').'applicationMediaFiles/floorPlanImage/';
                 if (file_exists($propertiesVedioUrl.$video_url)) { 
			    	@unlink($propertiesVedioUrl.$video_url);
				 }
				 if (file_exists($floorPlanUrl.$floor_plan)) {
			    	@unlink($floorPlanUrl.$floor_plan);
				 }
				   
				 
			 }
		}
		
		$this->property_model->data_delete('property','property_id',$primary_key);
		$this->property_model->data_delete('property_features','property_id',$primary_key);	 
		$this->property_model->data_delete('property_nearby_address','property_id',$primary_key);	
		$this->property_model->data_delete('property_image','property_id',$primary_key);	
			
			
			
			$delte_array = array('success'=>true,
			'success_message'=>'<p>Your data has been successfully 
			                       deleted from the database.</p>');
			$outPut = json_encode($delte_array);
			print($outPut);
			return $outPut;
			//redirect('mypropertyfavorites/');
		}
	}
	
	public function viewStats($propertyID = NULL){

		

		$data['results'] = 'Property Data';

		if(!empty($propertyID))

		$data['getResult'] = getPropertyData($propertyID);

		$this->load->view('my_account/propertyOverViewStats', $data);

	}
	
}
