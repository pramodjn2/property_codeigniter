<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Myenquiry extends CI_Controller {   
	 
	var $userID;
	public function __construct() 
    { 
    	parent::__construct();
		$this->load->model(array('Contractor_model', 'Common'));
		$this->load->helper(array('url'));
		$this->load->library(array('session', 'form_validation','file_upload','fileupload','pagination','grocery_CRUD', 'ajax_grocery_crud'));
		$this->userID = checkUserLogin();
		checkUserAccessPermission();
		//$this->output->enable_profiler(TRUE);		
    }
    public function index()
    {	
		$user_id = $this->session->userdata('user_id');	
		$crud = new grocery_CRUD();
		
		//$crud->set_theme('bootstrap');
		//$crud->unset_jquery();
		
		$crud->unset_print();
		$crud->unset_export();
		$crud->unset_add();		
		$crud->unset_edit();	
		$crud->unset_bulk_publish();
		$crud->unset_bulk_unpublish();
		
		$crud->set_subject('Enquiry');	
		
		//$crud->set_model('custom_query_model');
		$crud->set_table('free_valuation');
		$crud->set_relation('country', 'country','country');
		$crud->set_relation('region', 'country_regions','region');
		$crud->set_relation('city', 'country_region_cities','city');
		//$crud->set_relation('property_id', 'property','property_name');
		
		$crud->where('free_valuation.sender_id', $user_id);
		$crud->order_by('free_valuation.id', 'desc');	    
      
	  	
		
		$crud->unset_fields('user_id','property_id','sender_id','receiver_id','email','uname','message_status');

	$crud->columns('property_link','default_text','date_time');
		
		$crud->callback_column('property_link', array($this,'_property_link'));
		
		//Add_action parameter 1.Label 2.icon(img) 3.url 4. button class 5. call back function
		
		
		$crud->add_action('Send', 'fa fa-envelope', '', 'btn btn-xs btn-default', array($this,'_custom_action'));
		
		//$crud->add_action('link', 'icon-home', '', 'btn btn-xs btn-default propertyEnq', array($this,'_custom_enquiry'));
				
		$crud->display_as('uname', 'Name');
		//$crud->display_as('property_id', 'Property Name');
		$crud->display_as('message_status', 'Status');
		$crud->display_as('default_text', 'Message');
		$crud->display_as('property_link', 'Property Name');
		
		$crud->set_bulk_action_url(base_url('myenquiry/bulk_action'));
		$crud->set_data_status_field_name('status');
  	    
		$output = $crud->render(); 
		$page_title = array('page_title'=>'Manage Send Enquiry');
		$outputData = array_merge((array)$output, $page_title);
		$this->_example_output($outputData);
			
	}
	
	function _property_link($primary_key , $row){ 
	  $id     = safe_b64encode($row->property_id);
	  $pid    = $row->property_id;
	  
	  $property=  $this->Common->property_info_get($pid);
	  
	      $property_name=$property[0]['property_name'];
	      $bedrooms=$property[0]['bedrooms'];
		  $property_type_name=$property[0]['typeName'];
		  $category_name=$property[0]['categoryName'];
	   
	    $seo_url_string = ucwords($bedrooms.'  Bed '. $property_type_name.'  For '. $category_name);
		  $property_seo_url = seo_friendly_urls($seo_url_string,'',$pid);
	  
	 // $getData = $query->result_();
	  
	  return '<a href="'.base_url('property/details/'.$property_seo_url).'" class="crud-action propertyEnq" title="'.$property_name.'" target="_blank" style="text-decoration:underline; color:blue !important;">'.$property_name.'</a>';
	}
	
	function _custom_action($primary_key , $row){   
		$email  = safe_b64encode($row->email);
		$id     = safe_b64encode($row->id);
		return base_url('mymessage/composeMail?e='.$email.'&i='.$id.'&t=freevaluation');
	}
	
	function _custom_enquiry($primary_key , $row){   
		$id     = safe_b64encode($row->property_id);
		return base_url('property/details/'.$id);
	}
	
	function _example_output($output = null){
       $this->load->view('my_account/setting',$output); 
    }   
	
	public function bulk_action(){
				
		$action = $this->input->post("action", TRUE);
		$table_name = $this->input->post("table_name", TRUE);
		$field_name = $this->input->post("field_name", TRUE);
		$primary_key = $this->input->post("primary_key", TRUE);
		
		$selection = rtrim($this->input->post("selection", TRUE), '|');
		$id_array = ($selection) ? explode("|", $selection) : '';
		
		if($id_array != '' && $table_name !='' && $primary_key !=''){
			switch($action){
				case 'delete':
					foreach($id_array as $item):
						if($item != ''):
							$this->db->delete($table_name, array($primary_key => $item));
						endif;
					endforeach;
					echo count($id_array).' Items deleted!';
				break;
				case 'publish':
					foreach($id_array as $item):
						if($item != '' && $field_name !=''):
							$this->db->update($table_name, array($field_name => 'Active'), array($primary_key => $item));
						endif;
					endforeach;
					echo count($id_array).' Items published!';
				break;
				case 'unpublish':
					foreach($id_array as $item):
						if($item != '' && $field_name !=''):
							$this->db->update($table_name, array($field_name => 'Inactive'), array($primary_key => $item));
						endif;
					endforeach;
					echo count($id_array).' Items unpublished!';
				break;	
			}
		}else{
		   echo 'Kindly Select Atleast One Item!';
		}
	}
	
    
}
