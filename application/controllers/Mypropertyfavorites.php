<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mypropertyfavorites extends CI_Controller {  
	
	var $userID;
	var $message;
	public function __construct() { 
		
		parent::__construct();
		$this->load->helper(array('common_admin'));
		$this->load->model(array('Common', 'property_model'));
		$this->load->library(array('file_upload','fileupload','grocery_CRUD', 'ajax_grocery_crud'));			 		
		$this->language =  language_load();
		//check user login and get user_id from session.
		$this->userID = checkUserLogin();
		checkUserAccessPermission();
		//$this->output->enable_profiler(TRUE);
	}	
	
	public function index(){
		$crud = new grocery_CRUD();		
		$crud->set_subject('Property');
		$crud->set_model('custom_query_model');
		$crud->set_table('property'); //Change to your table name (user_id=".$this->userID.") AND		
		$crud->basic_model->set_query_str("SELECT p.property_id, p.property_name,p.address,p.bedrooms,t.typeName,c.categoryName 
											FROM property p
											left join property_types t on p.property_type = t.property_types_id
											left join property_category c on p.property_category = c.property_category_id
											where 
											(property_id
											IN (
											SELECT user_property_favorites.property_id
											FROM user_property_favorites
											WHERE user_property_favorites.user_id =".$this->userID."))"); //Query text here
		$crud->columns('property_name', 'address','categoryName', 'typeName','action');
		//void add_action( string $label,  string $image_url , string $link_url , string $css_class ,  mixed $url_callback)
		//$crud->add_action('Delete', '', 'mypropertyfavorites/delete','delete-icon');
		$crud->unset_fields('user_id');
		$crud->set_read_url_path(base_url('property/details'));
		$crud->set_edit_url_path(base_url('property/details'));
		$crud->display_as('property_name', 'Property Name')->display_as('address', 'Address')->display_as('categoryName','Category')->display_as('typeName','Type');
		
		$crud->callback_column('action', array($this,'_custom_action'));
		
		//Add_action parameter 1.Label 2.icon(img) 3.url 4. button class 5. call back function
		//$crud->add_action('Contact Property Owner', '', '', 'btn btn-xs btn-default', array($this,'_contact_owner'));
		//$crud->add_action('Get Free Valuation', '', '', 'btn btn-xs btn-default', array($this,'_freevaluation'));
		
		//$crud->callback_column('contact_property_owner', array($this,'_contact_owner'));
		//$crud->callback_column('get_free_valuation', array($this,'_freevaluation'));
		//Get Free Valuation
		
		$crud->display_as('contact_property_owner', 'Contact Property Owner');
		$crud->display_as('get_free_valuation', 'Get Free Valuation');
		$crud->unset_add();
		$crud->unset_read();
        $crud->unset_edit();
		$crud->unset_delete();
		$crud->unset_print();
		$crud->unset_export();
		$crud->unset_bulk_publish();
		$crud->unset_bulk_unpublish();
		$output = $crud->render();		
		$page_title = array('page_title'=>'My Favorites Properties');
		$outputData = array_merge((array)$output, $page_title);
		$this->_result_data_output($outputData);		
	}
	
	function _contact_owner($primary_key , $row){   
		$get_userID = $this->db->get_where('property', array('property_id' => $row->property_id))->result_array();
		$userID = $get_userID[0]['user_id'];
		$userDetail = getUserInformation($userID,'user');
	    $email =  safe_b64encode($userDetail[0]['email']);
		return base_url('mymessage/composeMail?e='.$email.'&i=1');
		
	}
	/*public function _contact_owner($primary_key , $row){
	
		$get_userID = $this->db->get_where('property', array('property_id' => $row->property_id))->result_array();
		$userID = $get_userID[0]['user_id'];
		
		$userDetail = getUserInformation($userID,'user');
			
        $email =  safe_b64encode($userDetail[0]['email']);
		
				
	  return '<a class="btn btn-xs btn-default" href="'.base_url('mymessage/composeMail?e='.$email.'&i=1').'" >Contact Property Owner</a>';
	  
	}*/
	public function _freevaluation($primary_key , $row){
		
	return base_url('freevaluation/index?e='.safe_b64encode($row->property_id));	
  /*return '<a class="btn btn-xs btn-default" href="'.base_url('freevaluation/index/'.safe_b64encode($row->property_id)).'" target="_blank">Get Free Valuation</a>';*/
	}
	
	function _custom_action($primary_key , $row)
	{
	
	$bedrooms=$row->bedrooms;
	$property_type_name=$row->typeName;
	$category_name=$row->categoryName;
	$property_id=$row->property_id;
	$userId=$this->userID;
	$mydata=selectData('user_property_favorites',"where property_id='$property_id' and user_id='$userId'");
	$propertyType=$mydata[0]['property_type'];
	
	if($propertyType=='1'){
	  $detailUrl='property/auction/';
	}else{
	  $detailUrl='property/details/';
	}
	
	
	
	 $seo_url_string = ucwords($bedrooms.'  Bed '. $property_type_name.'  For '. $category_name);
	 $property_seo_url = seo_friendly_urls($seo_url_string,'',$row->property_id);
	
	
	$get_userID = $this->db->get_where('property', array('property_id' => $row->property_id))->result_array();
		$userID = $get_userID[0]['user_id'];
		$userDetail = getUserInformation($userID,'user');
	    $email =  safe_b64encode($userDetail[0]['email']);
		
	 return '
	 			
	 <a class="delete-row" title="Remove from your Favorite List" href="'.base_url('mypropertyfavorites/delete/'.safe_b64encode($row->property_id)).'">
                <button class="btn btn-danger" type="button">
					<i class="fa fa-trash-o"></i>
			     </button>
             </a>
			 <a class="edit_button" target="_blank" title="View Property" href="'.base_url($detailUrl.$property_seo_url).'">
      			<button class="btn btn-info" type="button">
	       			<i class="clip-expand"></i>
	  			</button>
			 </a>
			 <a class="btn btn-xs btn-default" href="'.base_url('mymessage/composeMail?e='.$email.'&i=1').'">Contact</a>';
			 //<a class="btn btn-xs btn-default" href="'.base_url('freevaluation/index/'.safe_b64encode($row->property_id)).'" target="_blank">Free valuation</a>
			 
      
	}
	
	public function delete()
	{
		$property_id = ($this->uri->segment(3))?$this->uri->segment(3):'';
		if (!is_numeric($property_id)) {
			$property_id = safe_b64decode($property_id);
	    }
		
		
		
		if(!empty($property_id))
		{
			$table = 'user_property_favorites';
			
			$this->db->where('property_id', $property_id);
			$this->db->where('user_id', $this->userID);
			$this->db->delete($table);
			$delte_array = array('success'=>true,
			'success_message'=>'<p>Your data has been successfully 
			                       deleted from the database.</p>');
			$outPut = json_encode($delte_array);
			
					
		    $data['property'] =  $this->Common->property_info_get($property_id);
			$data['sender'] = getUserInformation($this->userID);
			
			
			
			
			$data['sender_fullname'] = $data['sender'][0]['firstName'].' ' .$data['sender'][0]['lastName'];
					$data['sender_firstname'] = $data['sender'][0]['firstName'];
					$data['sender_profile_image'] = $data['sender'][0]['profile_image'];
					
					
                    $seousername = str_replace('&nbsp;', '-', $data['sender_fullname']);
                    $data['senderseo'] = seo_friendly_urls($seousername,'',$this->userID);
             
  
                    $data['fullname'] = $data['property'][0]['firstName'].' ' .$data['property'][0]['lastName'];
					$data['firstname'] = $data['property'][0]['firstName'];
					$data['profile_image'] = $data['property'][0]['profile_image'];
					
					
                    $seousername = str_replace('&nbsp;', '-', $data['fullname']);
                    $data['recieverseo'] = seo_friendly_urls($seousername,'',$data['property'][0]['user_id']);
					
					
					$message = $this->load->view('my_account/message/template/unfavProperty_message', $data, TRUE); 
					$toEmail = $data['property'][0]['email'];
					$fromEmail = array('email' => config_item('no_reply'),'name' => config_item('site_name'));
					$subject = ucwords(config_item('site_name').' - '.$data['sender_fullname'].'   has remove this property from their favorite list');
					
					$attachment = array();
					$result = sendUserEmailCI($toEmail, $fromEmail, $subject, $message, $attachment);
			
			
			
			
  
  
			print($outPut);
			return $outPut;
			//redirect('mypropertyfavorites/');
		}
	}
	
	public function _filter_html($value, $row){		
		$subject = '<strong>'.$row->subject.'</strong>'.'... ';
		//$message = substr(strip_tags($row->message), 0, 100);
		return $subject.substr(strip_tags($row->message), 0, 100);
	}
	public function _result_data_output($output = null){
		$this->load->view('my_account/setting',$output);
	}	
}
