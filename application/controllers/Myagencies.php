<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Myagencies extends CI_Controller { 
	
	var $userID;
	var $message;
	public function __construct() {
		
		parent::__construct();
		$this->load->helper(array('common_admin'));
		$this->load->library(array('file_upload','fileupload','grocery_CRUD', 'ajax_grocery_crud'));			 		
		$this->language =  language_load();
		//check user login and get user_id from session.
		$this->userID = checkUserLogin();
		checkUserAccessPermission();
	}	
	
	public function index(){
		
		$crud = new grocery_CRUD();		
		$crud->set_subject('Agency');
		$crud->set_model('custom_query_model');
		$crud->set_table('agency_detail'); //Change to your table name
		
		$state = $crud->getState();
		if(($state=='edit')||($state=='add')){
		$crud->set_relation('agency_country', 'country','country');
		}
		
		$crud->set_field_upload('agency_logo','applicationMediaFiles/companyImage');
		
		$crud->basic_model->set_query_str("SELECT ad.*, 
		CONCAT(ad.agency_address,' - ',ad.agency_postal_code) as Address,
		CONCAT(ad.agency_phone_number,', ',ad.agency_cell_number) as contact_number, 
		CONCAT(u.firstName,' ',u.lastName) as admin, 
		CONCAT(crc.city, ', ', cr.region, ', ', c.country) as location FROM agency_detail ad 
			LEFT JOIN user u ON u.user_id=ad.user_id			 
			LEFT JOIN country c ON c.countryid = ad.agency_country 
			LEFT JOIN country_regions cr ON cr.regionid = ad.agency_state 
			LEFT JOIN country_region_cities crc ON crc.cityid = ad.agency_city			
			WHERE ad.user_id=".$this->userID." OR ad.agency_id in(SELECT CONCAT(uaa.agency_id,',') FROM user_associated_agency uaa WHERE uaa.user_id='".$this->userID."')"); //Query text here
		$crud->columns('admin', 'agency_name','Address', 'contact_number', 'agency_email', 'agency_website', 'actions');
		$crud->required_fields('agency_email', 'agency_name', 'status', 'agency_establish', 'agency_phone_number');
		$crud->unset_fields('user_id');
		$crud->display_as('agency_website', 'website');
		$crud->callback_column('actions',array($this,'_custome_action_button'));
		$crud->callback_after_insert(array($this, 'agency_after_insert'));
		$crud->callback_after_upload(array($this,'resize_callback_after_upload'));
		//$crud->unset_operations();
		//$crud->unset_add();
		$crud->unset_edit();
		$crud->unset_read();
		$crud->unset_delete();
		
		$output = $crud->render();		
		$page_title = array('page_title'=>'My Agency');
		$outputData = array_merge((array)$output, $page_title);
		$this->_result_data_output($outputData);		
	}
	
	public function _custome_action_button($primary_key, $row){
		$row->user_id;
		//$button = '<a href="'.base_url('myagencies/index/read/'.$row->agency_id).'" class="btn btn-default"><i class="fa fa-eye"></i></a>';
		$button='';
		if($this->userID == $row->user_id){
		$button .= '<a href="'.base_url('myagencies/edit/'.$row->agency_id).'" class="btn btn-default"><i class="fa fa-edit"></i></a>';
    	$button .= '<a href="'.base_url('myagencies/index/delete/'.$row->agency_id).'" class="btn btn-default"><i class="fa fa-trash-o"></i></a>';
		}else{
		$button .= '<a href="'.base_url('myagencies/edit/'.$row->agency_id).'" class="btn btn-default" title="Leave this Agency"><i class="clip-exit"></i></a>';
    	//$button .= '<a href="'.base_url('myagencies/delete/'.$row->agency_id).'" class="btn btn-default"><i class="fa fa-trash-o"></i></a>';
		}
		return $button;
	}
	
	
	public function _concate_column($value, $row){		
		echo '<pre>';
		print_r($row);
		echo '</pre>';
		return $row->agency_city.', '.$row->agency_state.', '.$row->agency_country;
		//$subject.substr(strip_tags($row->message), 0, 100);
	}
	
	public function _filter_html($value, $row){		
		$subject = '<strong>'.$row->subject.'</strong>'.'... ';
		//$message = substr(strip_tags($row->message), 0, 100);
		return $subject.substr(strip_tags($row->message), 0, 100);
	}
	
	public function _result_data_output($output = null){
		$this->load->view('my_account/setting',$output);
	}	
	
	function __setFormRules($setRulesFor = ''){
		switch($setRulesFor){
			case'compose':				
				$this->form_validation->set_rules('recipienties', 'Recipient', 'trim|required');
				$this->form_validation->set_rules('subject', 'Subject', 'trim|required');
				$this->form_validation->set_rules('message', 'Message', 'trim|required');				
			break;			
			case'sendMail':
				$this->form_validation->set_rules('tomsg', 'Send TO', 'trim|required');
				$this->form_validation->set_rules('frommsgid', 'Sender ID', 'trim|required');
				$this->form_validation->set_rules('msgsubj', 'Subject', 'trim|required');
				$this->form_validation->set_rules('msgbody', 'Message Body', 'trim|required');
			break;
			default:
				$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
				$this->form_validation->set_rules('password', 'Password', 'trim|required');
			break;
		}
		$this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button data-dismiss="alert" class="close">×</button><i class="fa fa-times-circle"></i> ', '</div>');
		
		return $this->form_validation->run();
	}
	
	public function agency_after_insert($post_array,$primary_key){
	
	  
	  $user_id = $this->session->userdata('user_id') ? $this->session->userdata('user_id') : 1;
	  
	  /*$this->load->model('property_model');
	  $data = array('group_id' => '4', 'created_by' => $user_id,'firstName'=>$post_array['agency_name']);
      $lastId=$this->property_model->data_insert('user', $data); */
	  
	    $agency_logs_insert = array("user_id" => $user_id,'agency_state'=>$post_array['region'],'agency_city'=>$post_array['city']);
		$this->db->where('agency_id', $primary_key);
		$this->db->update('agency_detail',$agency_logs_insert);
		
	  return true;
	
	}
	
	public function edit($id){
	  $this->load->model('Agency_model');
	  $data['page_title'] = 'Edit Agency';
	  $data['agency_detail'] = $this->Agency_model->agencyDetails($id);
	  
	  if($_POST){
	    $agency_name = $this->input->post('agency_name',TRUE);
		$agency_email = $this->input->post('agency_email',TRUE);
		$agency_phone_number = $this->input->post('agency_phone_number',TRUE);
		$agency_cell_number = $this->input->post('agency_cell_number',TRUE);
		$agency_team_size = $this->input->post('agency_team_size',TRUE);
		$agency_establish = $this->input->post('agency_establish',TRUE);
		$agency_website = $this->input->post('agency_website',TRUE);
		$agency_address = $this->input->post('agency_address',TRUE);
		$country = $this->input->post('country',TRUE);
		$state = $this->input->post('state',TRUE);
		$city = $this->input->post('city',TRUE);
		$agency_postal_code = $this->input->post('agency_postal_code',TRUE);
		$agency_about_us = $this->input->post('agency_about_us',TRUE);
        
		
		 $data=array(
				             'agency_name'=>$agency_name,
				             'agency_email'=>$agency_email,
							 'agency_phone_number'=>$agency_phone_number,
							 'agency_cell_number'=>$agency_cell_number,
							 'agency_team_size'=>$agency_team_size,
							 'agency_establish'=>$agency_establish,
							 'agency_website'=>$agency_website,
							 'agency_address'=>$agency_address,
							 'agency_country'=>$country,
							 'agency_state'=>$state,
							 'agency_city'=>$city,
							 'agency_postal_code'=>$agency_postal_code,
							 'agency_about_us'=>$agency_about_us,
				           );
		
		
		$agency_logo=$_FILES["agency_logo"]['name'];
				if(!empty($agency_logo)){
		            $upload_directory = config_item('site_url').'applicationMediaFiles/companyImage/';
					$temp_name = $_FILES['agency_logo']['tmp_name'];
					$check = getimagesize($temp_name);
					if($check !== false){	
					  if($_FILES['agency_logo']['size']< 900000) {		
					$ext = @pathinfo($agency_logo, PATHINFO_EXTENSION);
					$comp_name   = time().rand(1000,99999).'.'.$ext;
					$file_path = $upload_directory.$comp_name; 
					@move_uploaded_file($temp_name, $file_path);
					// Check file size  				
					$filePath= $upload_directory.$comp_name;					
			    	$img = resize_images($filePath,271,221, $upload_directory.'thumb/');
					$img = resize_images($filePath,99,99, $upload_directory.'9999/');
					$img = resize_images($filePath,340,59, $upload_directory.'34059/'); 
					$img = resize_images($filePath,99,34, $upload_directory.'9934/');				
					 }				
				   }// thumb
				   
				   	$agencyLogo=array('agency_logo'=>$comp_name);
					$data=array_merge($agencyLogo,$data);
		        }
		      
		       $this->db->where('agency_id', $id);
		       $this->db->update('agency_detail',$data);
		       redirect('myagencies');
	  }else{
	    $this->load->view('my_account/edit_agency',$data);
	  }
	  
	}
function resize_callback_after_upload($uploader_response,$field_info, $files_to_upload)
{     
	$upload_directory = config_item('root_url').'applicationMediaFiles/companyImage/';
	
	$filePath= $upload_directory.$uploader_response[0]->name;
	$img = resize_images($filePath,271,221, $upload_directory.'thumb/');
					$img = resize_images($filePath,99,99, $upload_directory.'9999/');
					$img = resize_images($filePath,340,59, $upload_directory.'34059/'); 
					$img = resize_images($filePath,99,34, $upload_directory.'9934/');	
    return true;
}
}
