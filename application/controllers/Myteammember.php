<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Myteammember extends CI_Controller { 
	
	var $userID;
	var $message;
	public function __construct() {		
		parent::__construct();
		$this->load->helper(array('common_admin'));
		$this->load->library(array('file_upload','fileupload','grocery_CRUD','ajax_grocery_crud'));
		$this->language =  language_load();
		//check user login and get user_id from session.
		$this->userID = checkUserLogin();
		checkUserAccessPermission();
	}	
	public function index(){
		$crud = new grocery_CRUD();		
		$crud->set_subject('Staff');
		
		$crud->unset_print();
		$crud->unset_export();
		$crud->set_subject('Team Member');		
		$crud->set_table('user');
		$crud->set_relation('country', 'country', 'country');
		$crud->set_relation('region', 'country_regions', 'region');
		$crud->set_relation('city', 'country_region_cities', 'city');
		$crud->where('created_by', $this->userID);
		$crud->set_relation('group_id', 'user_group','groupName');	
		$crud->set_relation('country', 'country','country');      
		
		$crud->set_add_url_path(base_url('myteammember/packagecheck'));
		
		$state = $crud->getState();
    	//$state_info = $crud->getStateInfo();
		if($state !='edit'){
			//$crud->set_relation_n_n('agency', 'user_associated_agency', 'agency_detail', 'user.user_id', 'agency_id', 'agency_name');
		}
		$crud->columns('firstName', 'lastName',  'address', 'email', 'phone_number','country','region','city');
		$crud->unset_fields('facebook_id', 'gmail_id', 'linkden_id', 'registerDate', 'lastLoginDate', 'updatedDate','user_professional_category','password','profile_image','postal_code','address','about_us','twitter_id','linkden_url','gmail_url','facebook_url','ip_address','salt','activation_code','username','forgotten_password_code','forgotten_password_time','remember_code','created_on','last_login','visit_count','comments','discussions','language','reported','report_reason','report_date','report_user_id','points','last_activity','active','id','verified_number','account_status');
		
		$crud->edit_fields('firstName', 'lastName','email',  'gender','phone_number','country','region','city'); 
        $crud->add_fields('firstName', 'lastName','email', 'gender','phone_number','created_by','country','region','city'); 
		
		$crud->required_fields('group_id','firstName', 'groupName', 'email');

		$crud->callback_after_insert(array($this, 'user_group_after_insert'));

		$crud->callback_after_insert(array($this , 'adminuser_sendemail'));
		
		$crud->callback_after_update(array($this, 'log_user_after_update'));
		
		$crud->callback_after_delete(array($this,'user_after_delete'));
	
		$crud->set_rules('email', 'Email','trim|required|valid_email|callback_email_duplicatecheck|max_length[50]');		
		$crud->field_type('created_by', 'hidden', $this->userID);
		
		$crud->callback_edit_field('region', array($this, 'empty_state_dropdown_select'));
		$crud->callback_edit_field('city', array($this, 'empty_city_dropdown_select'));

		$output = $crud->render();		
		$page_title = array('page_title'=>'My Team Member');
		$outputData = array_merge((array)$output, $page_title);
		$this->_result_data_output($outputData);		
	}
	
	function log_user_after_update($post_array,$primary_key)
    {
       userProfileUpdateMessage($primary_key); 
    }
	public function message($id){
	
	 
	  switch ($id){

              case "1":

                 $msg = 'Your Free Subscription Expire Please Purchase a <a href="#" title="click here">Subscription!</a>';

                 break;

              case "2":

                 $msg = 'Your Subscription Expire Please Purchase a new <a href="#" title="click here">Subscription!</a>';

                 break;
			  case "3":

                 $msg = 'You have Reached Limit for Add TeamMember.Please Purchase a new <a href="#" title="click here">Subscription!</a>';

                 break;

     }
	
	
	
	
	$this->messageci->set($msg, 'error');
	
	redirect('myteammember');
	
	}
	
	
	public function packagecheck(){
	
	  $userPackage=getUserPackageData($this->userID);
	
	  $teamCount=getTeamCountPackage($this->userID,$userPackage[0]['start_date']);
	
	  $team_package_count=$userPackage[0]['teamCount'];
	  $end_date=$userPackage[0]['end_date'];
	  $todayDate=date('Y-m-d');
	
	

	   $packagesetting=getUserSubcription($this->userID);
	
	    $subscription=$packagesetting['subscription_setting']['subscription'];
	    $month=$packagesetting['subscription_setting']['month'];
	
	   if(!empty($subscription)){
		  
		  if(($subscription=='no')&&($month=='exceed')){
		  
		     $this->message('1'); /*Expire Your Month(Free Users)*/
		 
		  }else{
		   
			   if(($subscription=='yes')&&($end_date<$todayDate)){
				$this->message('2'); /*Expire Your Package(For Renew Package )*/
			   }
			   else{
			    if(!empty($userPackage)){
					 if($teamCount>=$team_package_count){
					   $this->message('3'); /*Reached Maximum Limit*/
					 }else{
					   redirect('myteammember/index/add');
					 }
				 }else{
				    redirect('myteammember/index/add');
				 }
			   }
		  }
		   
		}else{

		 redirect('myteammember/index/add');
		
		}
	
	}
	
	
	
	
	public function adminuser_sendemail($post_array,$primary_key){
	  
	    
		$this->load->library('encrypt');
		$key = config_item('encryption_key');//'super-secret-key';
		
		$password=mt_rand();
		$activation_code = mt_rand();
		$encpassword = $this->encrypt->encode($password, $key);
		
		
		$group_id = $this->session->userdata('group_id');
		
	    $user_logs_insert = array("id"=>$primary_key,"active"=>1,"password"=>$encpassword,"status"=>'Inactive',"group_id" => $group_id, 'activation_code' => $activation_code);
		$this->db->where('user_id', $primary_key);
		$this->db->update('user',$user_logs_insert);
	    
		
		$setting = array('user_id' => $primary_key,
		                 'setting_currency' => 'GBP',
					     'setting_subscription' => '1,2,3,4,5,6,7,8');
		$this->db->insert('user_setting', $setting); 
		
	
		$subject=config_item('site_name').' Registration';
		
/*
		$msg_body='Dear '. $post_array['firstName'].' ' . $post_array['lastName'] . '<br/><br/>';
		$msg_body.='Thank you for registering with '.config_item('site_name');
		$msg_body.='<br/><br/>';
		
		$msg_body.='<b>User Details:</b>';
		$msg_body.='<br/>';
		$msg_body.='<b>Email : </b>'.$post_array['email'];
		$msg_body.='<br/>';
		$msg_body.='<b>Password : </b>'.$password;
		$msg_body.='<br/>';
		$msg_body.='Click <b><a href="' . base_url() . '">here</a></b> for Sign In to ' . config_item('site_name');
		$msg_body.='<br/><br/>';
		$this->checkMail($post_array['email'], $subject, $msg_body);
		
		*/
		$data['fullname'] = $post_array['firstName'].' ' .$post_array['lastName'];
		$data['firstname'] = $post_array['firstName'];
		$data['email'] = $post_array['email'];
		$data['password'] = $password;	
		$data['activation_code'] = $activation_code;	
		$message = $this->load->view('my_account/message/template/registration_message', $data, TRUE); 
		$toEmail = $post_array['email'];
		$fromEmail = array('email' => config_item('no_reply'),'name' => config_item('site_name'));
		$subject = ucwords(config_item('site_name').' Registration');
		$attachment = array();
		$result = sendUserEmailCI($toEmail, $fromEmail, $subject, $message, $attachment);
		
	
	}
	public function user_group_after_insert($post_array,$primary_key){
		$user_id = $this->session->userdata('user_id') ? $this->session->userdata('user_id') : 1;
		$group_id = $this->session->userdata('group_id');
	    $this->load->model('Common');
		$result = $this->Common->select('user_associated_agency',"where user_id='$user_id'");
		if(!empty($result)){
			$agency_id = $result[0]['agency_id'];
			$data = array('agency_id' => $agency_id, 'user_id' => $user_id);
            $this->db->insert('user_associated_agency', $data); 
		}
		
		
		
		
		
		
		$user_logs_insert = array("created_by" => $user_id,"group_id" => $group_id);
		$this->db->where('user_id', $primary_key);
		$this->db->update('user',$user_logs_insert);		
	    return true;
	}
	public function email_duplicatecheck($email) {
		$user_id = $this->uri->segment(4);
		if(empty($user_id) || !is_numeric($user_id)) {
			$user_id = -1;
		}
		$this->load->model('Common');
		$result = $this->Common->select('user',"where user_id != $user_id and email = '" . $email . "'");
		if (!empty($result) && count($result) > 0){
			$this->form_validation->set_message('email_duplicatecheck', 'The email already exists');	
			return false;
		}
		return true;
	}

	public function _custome_action_button($primary_key, $row){
		$row->user_id;
		$button = '<a href="'.base_url('myagencies/index/read/'.$row->agency_id).'" class="btn btn-default"><i class="fa fa-eye"></i></a>';
		if($this->userID == $row->user_id){
			
			$button .= '<a href="'.base_url('myagencies/index/edit/'.$row->agency_id).'" class="btn btn-default"><i class="fa fa-edit"></i></a>';
	    	$button .= '<a href="'.base_url('myagencies/index/delete/'.$row->agency_id).'" class="btn btn-default"><i class="fa fa-trash-o"></i></a>';
		
		}else{
			$button .= '<a href="'.base_url('myagencies/index/edit/'.$row->agency_id).'" class="btn btn-default" title="Leave this Agency"><i class="clip-exit"></i></a>';
	    	//$button .= '<a href="'.base_url('myagencies/delete/'.$row->agency_id).'" class="btn btn-default"><i class="fa fa-trash-o"></i></a>';
		}
		return $button;
	}


	public function _result_data_output($output = null){
		$this->load->view('my_account/setting',$output);
	}	
	

	public function checkMail($email='',$subject='',$msg_body=''){
		$emaildata['body'] = $msg_body; //$message;
		$attachment = array();
		$subj = $subject;
		$message = $this->load->view('my_account/message/template/simple_message', $emaildata, TRUE);
		$toEmail = $email;
		$fromEmail = array('email' => config_item('no_reply'),'name' => $this->session->userdata('fullName'));
		
		$result = sendUserEmailCIAdmin($toEmail, $fromEmail, $subj, $message, $attachment);
	}
	public function user_after_delete($primary_key)
    {
         $this->db->where('user_setting_id', $primary_key);
         $this->db->delete('user_setting');
		 
		 return;
    }
	
		// ------------ Call Back Function -------------------
	function empty_state_dropdown_select()
	{
		//CREATE THE EMPTY SELECT STRING
		$empty_select = '<select name="region" class="chosen-select" data-placeholder="Select State/Province" style="width: 300px; display: none;">';
		$empty_select_closed = '</select>';
		
		//GET THE ID OF THE LISTING USING URI
		$listingID = $this->uri->segment(4);   // record entry
		
		//LOAD GCRUD AND GET THE STATE
		$crud = new grocery_CRUD();
		$state = $crud->getState();
		
		//CHECK FOR A URI VALUE AND MAKE SURE ITS ON THE EDIT STATE
		if(isset($listingID) && $state == "edit") {

			//GET THE STORED STATE ID
			$this->db->select('country,region')
					 ->from('user')
					 ->where('user_id', $listingID);
					 
			$db = $this->db->get();
			$row = $db->row(0);
			
			$countryID = $row->country;
			$stateID = $row->region;
			
			//GET THE STATES PER COUNTRY ID
			$this->db->select('*')
					 ->from('country_regions')
					 ->where('countryid', $countryID);
			$db = $this->db->get();
			
			//APPEND THE OPTION FIELDS WITH VALUES FROM THE STATES PER THE COUNTRY ID
			foreach($db->result() as $row):
				if($row->regionid == $stateID) {
					$empty_select .= '<option value="'.$row->regionid.'" selected="selected">'.$row->region.'</option>';
				} else {
					$empty_select .= '<option value="'.$row->regionid.'">'.$row->region.'</option>';
				}
			endforeach;
			
			//RETURN SELECTION COMBO
			return $empty_select.$empty_select_closed;
		} else {
			//RETURN SELECTION COMBO
			return $empty_select.$empty_select_closed;	
		}
	}
	
	function empty_city_dropdown_select()
	{
		//CREATE THE EMPTY SELECT STRING
		$empty_select = '<select name="city" class="chosen-select" data-placeholder="Select City/Town" style="width: 300px; display: none;">';
		$empty_select_closed = '</select>';
		//GET THE ID OF THE LISTING USING URI
	    $listingID = $this->uri->segment(4);
		
		//LOAD GCRUD AND GET THE STATE
		$crud = new grocery_CRUD();
		$state = $crud->getState();
		
		//CHECK FOR A URI VALUE AND MAKE SURE ITS ON THE EDIT STATE
		if(isset($listingID) && $state == "edit") {
			//GET THE STORED STATE ID
			$this->db->select('region, city')
					 ->from('user')
					 ->where('user_id', $listingID);
			$db = $this->db->get();
			$row = $db->row(0);
			
			$stateID = $row->region;
			$cityID = $row->city;
			
			//GET THE CITIES PER STATE ID
			$this->db->select('*')
					 ->from('country_region_cities')
					 ->where('regionid', $stateID);
			$db = $this->db->get();
			
			//APPEND THE OPTION FIELDS WITH VALUES FROM THE STATES PER THE COUNTRY ID
			foreach($db->result() as $row):
				if($row->cityId == $cityID) {
					$empty_select .= '<option value="'.$row->cityId.'" selected="selected">'.$row->city.'</option>';
				} else {
					$empty_select .= '<option value="'.$row->cityId.'">'.$row->city.'</option>';
				}
			endforeach;
			
			//RETURN SELECTION COMBO
			return $empty_select.$empty_select_closed;
		} else {
			//RETURN SELECTION COMBO
			return $empty_select.$empty_select_closed;	
		}
	}

	
	// ------------ Call Back Function -------------------	
	
	//GET JSON OF STATES
	function get_states()
	{
		$countryID = $this->uri->segment(3);

		$this->db->select("*")
				 ->from('country_regions')
				 ->where('countryid', $countryID);
		$db = $this->db->get();

		$array = array();
		foreach($db->result() as $row):
			$array[] = array("value" => $row->regionid, "property" => $row->region);
		endforeach;
		echo json_encode($array); 
		exit;
	}
	
	//GET JSON OF CITIES
	function get_cities()
	{
		$stateID = $this->uri->segment(3);
		
		$this->db->select("*")
				 ->from('country_region_cities')
				 ->where('regionid', $stateID);
				 
		$db = $this->db->get();
		
		$array = array();
		foreach($db->result() as $row):
			$array[] = array("value" => $row->cityId, "property" => $row->city);
		endforeach;
		
		echo json_encode($array);
		exit;
	}	
}
