<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mydashboard extends CI_Controller {  
    var $userID;
	var $userGROUP;
	var $message = array();
	
	public function __construct(){
		parent::__construct();
		//$this->load->database();
		$this->load->library(array('file_upload','onlineusers'));	
		$this->load->model(array('user_model','common','professional_model'));
		$this->load->helper('common_admin');
		
		//check user login and get user_id from session.
		$this->userID = checkUserLoginId();
		$this->userEmail = $this->session->userdata('email');
		$this->userGROUP = $this->session->userdata('group_id');
		//checkUserAccessPermission();	
		
    } 
	function __groupName(){		
		$group = array('5'=>'agent', '6'=>'solicitor', '7'=>'contractor', '8'=>'user');
		return array_key_exists($this->userGROUP, $group)?$group[$this->userGROUP]: 'user';
	}
	public function index(){
		checkUserLogin();
		$data['page_title'] = 'My dashboard';
		
        $data['property_vist_list']=$this->user_model->get_property_vist_list($this->userID);
		$data['recently_cont_cust']=$this->user_model->get_recently_contacted_customer($this->userEmail);
		$data['received_enquiry']=$this->user_model->get_received_enquiry_for_valuation($this->userID);
		$data['property_favourites_list']=$this->user_model->get_property_favourite_list($this->userID);
		$data['self_property_vist_list']=$this->user_model->self_user_property_visit_list($this->userID);
		$data['user_manage_advert']=$this->user_model->user_manage_advert($this->userID);
		$this->load->view('my_account/'.$this->__groupName().'/dashboard', $data);	
	}
	
	public function profile(){
		checkUserLogin();
		$data['result'] = $this->user_model->getMydashboardProfileData($this->userID);
		$data['profDetails'] = $this->professional_model->agentDetails($this->userID);
		$data['property_sale_result'] = $this->user_model->getUserProperty($this->userID,1,6);
		$data['property_rent_result'] = $this->user_model->getUserProperty($this->userID,2,6);
		
		$data['stylesheet'] = array('assets/plugins/bootstrap-fileupload/bootstrap-fileupload.min.css',
									'assets/plugins/bootstrap-social-buttons/social-buttons-3.css');
		$data['page_title'] = 'My Profile';
		
		$data['profile_user_id']=$this->userID;
		$this->load->view('my_account/'.$this->__groupName().'/profile', $data);
	}
	public function updateProfileImage(){
		if($_FILES['profile_image']['name']!=''){
			$fileBodyName = strtotime(date('Y-m-d h:i:s'));				
			$imageName = $this->user_model->uploadProfileImage($_FILES['profile_image'], USER_IMAGE_DIRPATH, $fileBodyName, '200');
			if($imageName != ''){
				$this->user_model->updateProfileImage($this->userID);
			}												
			$imageData = array('profile_image' => $imageName);
			$upload_directory = config_item('root_url').'applicationMediaFiles/usersImage/';
	        $filePath= $upload_directory.$imageName;
			$img = resize_images($filePath,271,221, $upload_directory.'thumb/'); 
            $img = resize_images($filePath,150,150, $upload_directory.'150150/');
			$result = $this->db->update('user', $imageData, array('user_id' => $this->userID));
			if($result){
				$this->messageci->set('Your profile picture has been changed!', 'success');
			}else{
				$this->messageci->set('Your profile picture did not changed!', 'error');
			}
		}else{			
			$this->messageci->set('Kindly select Valid Image!', 'error');
		}
		redirect('mydashboard/profile');		
	}
	
	public function editprofile(){
		
		$data['page_title'] = 'Edit Profile';
		$data['stylesheet'] = array('assets/plugins/bootstrap-fileupload/bootstrap-fileupload.min.css', 
									'assets/plugins/summernote/build/summernote.css');

		$userGroupType = 'user';
		switch($this->userGROUP){
			case '5': //agent type of user
				$userGroupType = 'agent';
				$this->__editProfessionalProfile($userGroupType);			
			break;
			case '6': //Solicitor type of user
				$userGroupType = 'solicitor';
				$this->__editProfessionalProfile($userGroupType);	
			break;
			case '7': //Contractor type of user
				$userGroupType = 'contractor';
				$this->__editProfessionalProfile($userGroupType);
			break;
			case '8': //Register type of user
				$userGroupType = 'user';
				$this->__editUserProfile($userGroupType);	
			break;
			default:
				$userGroupType = 'user';
				$this->__editUserProfile($userGroupType);	
			break;
		}
		
		$data['result'] = $this->user_model->getUserProfileData($this->userID);
		$this->load->view('my_account/'.$userGroupType.'/profileEdit', $data);
		
	}
	
	function __editUserProfile($userProfileType='user'){
				
		$checkFormValidation = $this->__setFormRules($userProfileType);
		if($checkFormValidation){
			$userData = $this->input->post();   
			
			if(empty($this->userEmail)){	
			   $email = $userData['email'];
			   $where = " where email = '$email'"; 
			   $result = $this->common->select('user',$where);
			   
			 
			   if($result){
				$this->messageci->set('Sorry, This e-mail address already exists', 'error');
					redirect('mydashboard/editprofile');
					exit;   
			  }
			}
			       
						
			if($_FILES['profile_image']['name']!=''){
				
				$attachments = $_FILES['profile_image']['name'];
				$FileType = pathinfo($attachments,PATHINFO_EXTENSION);
				
				
				if($_FILES["profile_image"]["size"] > 5242880) {   //5mb  1027*10
					$this->messageci->set('Sorry, your profile image is too large. File should be less than 5mb', 'error');
					redirect('mydashboard/editprofile');
					exit;
				}else if($FileType != "jpg" && $FileType != "png" && $FileType != "jpeg"
) {  
					$this->messageci->set('Sorry, profile image only JPG, JPEG, PNG files are allowed', 'error');
					redirect('mydashboard/editprofile');
					exit;
				}
				
				$fileBodyName = strtotime(date('Y-m-d h:i:s'));				
				$imageName = $this->user_model->uploadProfileImage($_FILES['profile_image'], USER_IMAGE_DIRPATH, $fileBodyName, '200');
                $upload_directory = config_item('root_url').'applicationMediaFiles/usersImage/';
	            $filePath= $upload_directory.$imageName;
				$img = resize_images($filePath,271,221, $upload_directory.'thumb/'); 
                $img = resize_images($filePath,150,150, $upload_directory.'150150/');	
				
				 
				$imageData = array('profile_image' => $imageName);
				$userData = array_replace($userData, $imageData);				
			}			
			
			
				
						  
			$user_data = array( 'gender' => $userData['gender'],
								'firstName' => $userData['firstName'],
								'lastName' => $userData['lastName'],
								'email'=>$userData['email'],								
								'about_us' => $userData['about_us'],
								'country' => $userData['country'],
								'region' => $userData['region'],
								'city' => $userData['city'],
								'postal_code' => $userData['postal_code'],
								'address' => $userData['address'],
								'linkden_url' => $userData['linkden_url'],
								'gmail_url' => $userData['gmail_url'],
								'facebook_url' => $userData['facebook_url'],
								'phone_number' => $userData['phone_number']);
								
			if(empty($this->userEmail)){
				 $status = array('status' => 'Inactive');
				 $user_data = $user_data + $status;
			}
					
			if(!empty($imageName)){
				$this->session->set_userdata(array('profile_image'=>$imageName));  
		       $user_data = $user_data + $imageData;
			}
			
			$result = $this->db->update('user', $user_data, array('user_id' => $this->userID));	
			//userProfileUpdateMessage($this->userID);
			
			if(empty($this->userEmail)){
			  $this->session->sess_destroy();
      	     // delete_cookie("userID");
			 useremailVerifyMessage($this->userID);
			  $this->messageci->set('Your a/c is Inactive please varify email after that login', 'success');
			  redirect(base_url('redirect/'));	
			  die;
			}else{
			userProfileUpdateMessage($this->userID);	
			}
					
			
			if($result){	
				$this->messageci->set('Your account has been updated successfully!', 'success');
			}else{
				$this->messageci->set('There is coming problem to update your profile!', 'error');
			}
		}		
	
	}
	function __editProfessionalProfile($userProfileType='user'){
		
		
				
		$checkFormValidation = $this->__setFormRules($userProfileType);
		if($checkFormValidation){	
			$userData = $this->input->post();          
						
			if($_FILES['profile_image']['name']!=''){
				
				$attachments = $_FILES['profile_image']['name'];
				$FileType = pathinfo($attachments,PATHINFO_EXTENSION);
				 
				if($_FILES["profile_image"]["size"] > 5242880) {   //5mb  1027*581024
					$this->messageci->set('Sorry, your profile image is too large. File should be less than 5mb', 'error');
					redirect('mydashboard/editprofile');
					exit;
				}else if($FileType != "jpg" && $FileType != "png" && $FileType != "jpeg"
) {  
					$this->messageci->set('Sorry, profile image only JPG, JPEG, PNG files are allowed', 'error');
					redirect('mydashboard/editprofile');
					exit;
				}
				
				$fileBodyName = strtotime(date('Y-m-d h:i:s'));				
				$imageName = $this->user_model->uploadProfileImage($_FILES['profile_image'], USER_IMAGE_DIRPATH, $fileBodyName, '200');
                $upload_directory = config_item('root_url').'applicationMediaFiles/usersImage/';
	            $filePath= $upload_directory.$imageName;
				$img = resize_images($filePath,271,221, $upload_directory.'thumb/'); 
                $img = resize_images($filePath,150,150, $upload_directory.'150150/');			   
				
				$imageData = array('profile_image' => $imageName);
				$userData = array_replace($userData, $imageData);
				
				$this->session->set_userdata(array('profile_image'=> $imageName));				
			}			
			
			if($_FILES['agency_logo']['name']!=''){
				
				
				$attachments = $_FILES['agency_logo']['name'];
				$FileType = pathinfo($attachments,PATHINFO_EXTENSION);
				 
				if($_FILES["agency_logo"]["size"] > 5242880) {   //5mb  1027*581024
					$this->messageci->set('Sorry, your agency logo is too large. File should be less than 5mb', 'error');
					redirect('mydashboard/editprofile');
					exit;
				}else if($FileType != "jpg" && $FileType != "png" && $FileType != "jpeg"
) {  
					$this->messageci->set('Sorry, agency logo only JPG, JPEG, PNG files are allowed', 'error');
					redirect('mydashboard/editprofile');
					exit;
				}
				
				
				$fileBodyName = strtotime(date('Y-m-d h:i:s'));				
				$company_imageName = $this->user_model->uploadProfileImage($_FILES['agency_logo'], COMPANY_IMAGE_DIRPATH, $fileBodyName, '200');
                $upload_directory = config_item('root_url').'applicationMediaFiles/companyImage/';
	            $filePath= $upload_directory.$company_imageName;
				$img = resize_images($filePath,271,221, $upload_directory.'34059/'); 
                $img = resize_images($filePath,150,150, $upload_directory.'9934/');
				$img = resize_images($filePath,150,150, $upload_directory.'9999/');	   
				
				$agency_logo = array('agency_logo' => $company_imageName);
				$userData = array_replace($userData, $agency_logo);
			}	
			/**Job Refrence**/
			
			$ref_name=$this->input->post('ref_name');
			$ref_address=$this->input->post('ref_address');
			$ref_contact_no=$this->input->post('ref_contact_no');
			
			$ref_namechk=array_filter($ref_name);
			$ref_address_chk=array_filter($ref_address);
			$ref_contact_no_chk=array_filter($ref_contact_no);
			
			
			 if(!empty($ref_namechk)){
				$this->db->where('user_id', $this->userID);
                $this->db->delete('job_refrence');
					for($i=0; $i < count($ref_namechk); $i++)
					 {
					  $ref_data[] = array('job_refrence_name' => $ref_name[$i],
										   'job_refrence_address' => $ref_address[$i],
										   'job_refrence_contact_no' => $ref_contact_no[$i],
										   'user_id' => $this->userID);
					 }
				    $this->db->insert_batch('job_refrence', $ref_data);
				  }				  
			$user_data = array( 'user_type' => $userData['agency_type'],
								'gender' => $userData['gender'],
								'firstName' => $userData['firstName'],
								'lastName' => $userData['lastName'],
								'country' => $userData['country'],
								'region' => $userData['region'],
								'city' => $userData['city'],
								'postal_code' => $userData['postal_code'],
								'address' => $userData['address'],
								'phone_number' => $userData['phone_number'],
								'linkden_url' => $userData['linkden_url'],
								'gmail_url' => $userData['gmail_url'],
								'facebook_url' => $userData['facebook_url'],
								'about_us' => $userData['about_us'],
								'user_professional_title' => $userData['user_professional_title'],
								'ip_address' => $_SERVER['REMOTE_ADDR'],
								'updatedDate' => time());	
					
			if(!empty($imageName)){	  
		       $user_data = $user_data + $imageData;
			}
			
			$result = $this->db->update('user', $user_data, array('user_id' => $this->userID));	
			
				 $this->session->set_userdata(array('name' => $userData['firstName'],
								'fullName'		=> $userData['firstName'].' '.$userData['lastName'],
								'user_type'		=> $userData['agency_type']));

			
			/*$groupID = $this->session->userdata('group_id');
			if($result && $userData['group_id'] != $groupID){
				$this->db->select('groupName');
				$this->db->where('group_id', $userData['group_id']);
				$getGroupName = $this->db->get('user_group')->result_array();			
				$this->session->set_userdata(array('groupName'=> $getGroupName[0]['groupName']));
				$this->session->set_userdata(array('group_id'=> $userData['group_id']));
			}*/
			
			$agency_data= array('agency_name' => $userData['agency_name'],
								'agency_phone_number' => $userData['agency_phone_number'],
								'agency_cell_number' => $userData['agency_cell_number'],
								'agency_fax' => $userData['agency_fax'],
								'agency_website' => $userData['agency_website'],
								'blog_url' => $userData['blog_url'],
								'agency_establish' => $userData['agency_establish']							
								);	
			
			if(!empty($agency_logo)){	  
		       $agency_data = $agency_data + $agency_logo;
			}		
			
			$this->db->where('user_id', $this->userID);
			$checkUserExistance = $this->db->get('agency_detail')->num_rows();
		
			if($checkUserExistance > 0){					
			  $this->db->update('agency_detail', $agency_data, array('user_id' => $this->userID));
			}else{
			  $mydata = array('user_id' => $this->userID);
			  $agency_data = $agency_data + $mydata;
			  $this->db->insert('agency_detail', $agency_data); 
			}
			
			/*if($userData['group_id'] == 8){
				if($checkUserExistance > 0){
					$this->db->delete('agency_detail', array('user_id' => $this->userID)); 
					}
				$this->db->update('user', array('user_professional_title' => ''), array('user_id' => $this->userID));	
			}*/
			 
			 $userSpecialties = $userData['specialities'];
			 if(!empty($userSpecialties) ){
				$this->db->delete('user_profession_specialties', array('user_id'=>$this->userID));	
				$mydata = array();			
				for($j=0; $j<count($userSpecialties); $j++){
				$mydata[] = array('user_id'=>$this->userID,'specialties_id'=>$userSpecialties[$j]);
				}
			    $this->db->insert_batch('user_profession_specialties', $mydata);
			 }
			
			userProfileUpdateMessage($this->userID);	   			
			if($result){	
				$this->messageci->set('Your account has been updated successfully!', 'success');
			}else{
				$this->messageci->set('There is coming problem to update your profile!', 'error');
			}
		}		
	
	}
		
	public function subscription(){
	
      	$group_id=$this->session->userdata('group_id');
				
		if(($group_id=='5')||($group_id=='6')){
				
		/*$usi_result = $this->common->select('user_subscribe_information', ' where user_id = ' . $this->userID);
		$usi_plandetails = array();
		if (!empty($usi_result) && count($usi_result) > 0){
			$usi_plandetails = $this->common->select('membership_plan', ' where plan_id = ' . $usi_result[0]['plan_id']);
		}
		$data = array();
		$data['usi_result'] = $usi_result;
		$data['usi_plandetails'] = $usi_plandetails;*/
		
		
		$data['userplanDetails'] = $this->common->select('membership_plan',' where  status="Active"');
		$this->load->view('select_plan', $data);
		}else{
		   $this->messageci->set("You are not allowed to access that location", 'error');
		    redirect('home');
		}
		
	}
    public function changePassword(){
		checkUserLogin();
		$passChangeFormValidation = $this->__setFormRules('changePassword');
		if($passChangeFormValidation){		    
			$oldpass = $this->input->post('oldpass',TRUE);
			$newpass = $this->input->post('newpass',TRUE);
			
			$newPass = array('password' => encrypt_data($newpass));
			
			$uCheck = $this->db->get_where('user', array('user_id' => $this->userID))->result_array();
			$userPass = decrypt_data($uCheck[0]['password']);
			
			if($oldpass == $userPass)
			{
			  $where = array('user_id'=> $this->userID);
			  $record = $this->db->update('user', $newPass, $where);
			  if(!empty($record)){
			 			$data['msg']['success'] = 'Your Password has been changed successfully!';
						
						$userName = ucfirst($uCheck[0]['firstName']).'&nbsp;'.ucfirst($uCheck[0]['lastName']);
						
						$log_url  = base_url();
						$email    = $uCheck[0]['email'];
						$subject  = 'Your '.config_item('site_name').' password has been changed';
						
						$msg_body = 'Dear '.$userName.'<br/><br/>';
						$msg_body.= 'You recently changed the password associated with your account <b>'.$uCheck[0]['email'].'</b>.<br />'; 
						$msg_body.= 'If you did not make this change and believe your 
									 '.config_item('site_name').' account has been compromised, please <a href="'.base_url('faq/contact').'">contact support</a>.';
						$msg_body.='<br/><br/>';
						$msg_body.='For login click here: <a href="'.$log_url.'">Link</a>';
						$this->checkMail($email,$subject,$msg_body);
					}else{
						$data['msg']['danger'] = 'Password changing faild, Please try again!';
						 }
			} 
			else{
				  $data['msg']['danger'] = 'Password does not match the old password!';
				}
	   }
		
		$data['page_title'] = 'Change Password';
		$this->load->view('my_account/changePassword', $data);
	}
	public function checkMail($email='',$subject='',$msg_body=''){
  		$emaildata['body'] = $msg_body; //$message;
  		$attachment = array();
  		$subj = $subject;
  		$message = $this->load->view('my_account/message/template/changePassword_message', $emaildata, TRUE);
  		$toEmail = $email;
  		$fromEmail = array('email' => config_item('no_reply'),'name' => config_item('site_name'));
  		$result = sendUserEmailCI($toEmail, $fromEmail, $subj, $message, $attachment);
		}
	public function setting(){
		checkUserLogin();
	 $this->load->model('Common');
	 $this->load->helper('url');
	 $userId=$this->userID;
	 $result=$this->Common->select('user_setting',"where user_id ='$userId'");
	
	 $data['setting_results']=$result;	 
		$passChangeFormValidation = $this->__setFormRules('settings');
		if($passChangeFormValidation){		    
			$setting_currency = $this->input->post('setting_currency',TRUE);
			//$setting_time_zone = $this->input->post('setting_time_zone',TRUE);
			//$setting_languae=$this->input->post('setting_languae',TRUE);
			
			$this->session->set_userdata('currency', $setting_currency);
			
			
			
			$resultCur=$this->Common->select('manage_currencies',"where status ='Active'");
			
			
		    $this->session->set_userdata('currency_symbol', $resultCur[0]['symbol']);
			
			
			$setting_display_name=$this->input->post('setting_display_name',TRUE);
			
			$data=array(
			            'user_id'=>$this->userID, 
			            'setting_currency'=>$setting_currency,
                        'setting_display_name'=>$setting_display_name
			            );
			
			if(!empty($result)){
			 $where = array('user_id'=> $this->userID);
			 $success=$this->db->update('user_setting', $data, $where);
			
			}else{
			  $success=$this->Common->data_insert('user_setting',$data);
			}
			$setting_subscription = $this->input->post('setting_subscription',TRUE);
			 if(!empty($setting_subscription)){
				    $values=implode(',',$setting_subscription);
					$where = array('user_id'=> $this->userID);
					$mydata = array('setting_subscription'=>$values);
					$success=$this->db->update('user_setting', $mydata, $where);			   
			 }
			if($result){	
				$this->messageci->set('Your setting has been updated successfully!', 'success');
			}else{
				$this->messageci->set('There is coming problem to update your setting!', 'error');
			}
			redirect('mydashboard/setting');
		}
		$data['page_title'] = 'Setting';
		$this->load->view('my_account/user_setting.php', $data);
	}
	public function signout(){
		$this->session->sess_destroy();
      	redirect('home','referece');
	}
	public function getStateList($country_id=NULL){
			$stateList = '<option value="">--Select Region--</option>';
		echo $stateList .= $this->common->getStateListBox($country_id);
	}
	public function getCityList($state_id=NULL){
			 $cityList = '<option value="">--Select City--</option>';
		echo $cityList .= $this->common->getCityListBox($state_id);
	}	
	public function getSpecialities($group_id = NULL){
		if(isset($group_id) && $group_id !=''){
			$table = 'user_group_specialities';
			$oData = array('value'=>'speciality_id', 'option'=>'specialityName');
			$select = array();
			$where = array('group_id' => $group_id);							
			echo $this->common->generateCheckBox($table, $oData, $select, $where, 'specialities');
		}else{
			echo 'Specialty not found';
		}		
	}
	function __setFormRules($setRulesFor = ''){
		switch($setRulesFor){
			case'agent':				
			case'solicitor':
				//$this->form_validation->set_rules('group_id', 'Profession', 'trim|required');
				$this->form_validation->set_rules('gender', 'Gender', 'trim|required');
				$this->form_validation->set_rules('firstName', 'First Name', 'trim|required');
				$this->form_validation->set_rules('lastName', 'Last Name', 'trim|required');
				//$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[user.email]');
				$this->form_validation->set_rules('country', 'Country', 'trim|required');
				$this->form_validation->set_rules('region', 'Region', 'trim|required');
				$this->form_validation->set_rules('city', 'City', 'trim|required');
				$this->form_validation->set_rules('postal_code', 'Postal Code', 'trim|required');
				$this->form_validation->set_rules('address', 'Address', 'trim|required');
				$this->form_validation->set_rules('phone_number', 'Phone number', 'trim|required');
				$this->form_validation->set_rules('about_us', 'About your-self', 'trim|required');
				
				$chk = $this->input->post();			
				 if((!empty($chk['agency_phone_number'])||($chk['agency_cell_number'])||($chk['agency_fax'])||($chk['agency_website'])||($chk['blog_url'])||($chk['agency_licenses_number'])||($chk['agency_establish']))&&(empty($chk['agency_name']))){
				  $this->form_validation->set_rules('user_professional_title', 'Profession Title', 'trim|required');
				  $this->form_validation->set_rules('agency_name', 'Company name', 'trim|required');
				 }
				
			break;
			case'contractor':
				//$this->form_validation->set_rules('group_id', 'Profession', 'trim|required');
				$this->form_validation->set_rules('gender', 'Gender', 'trim|required');
				$this->form_validation->set_rules('firstName', 'First Name', 'trim|required');
				$this->form_validation->set_rules('lastName', 'Last Name', 'trim|required');
				//$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[user.email]');
				$this->form_validation->set_rules('country', 'Country', 'trim|required');
				$this->form_validation->set_rules('region', 'Region', 'trim|required');
				$this->form_validation->set_rules('city', 'City', 'trim|required');
				$this->form_validation->set_rules('postal_code', 'Postal Code', 'trim|required');
				$this->form_validation->set_rules('address', 'Address', 'trim|required');
				$this->form_validation->set_rules('phone_number', 'Phone number', 'trim|required');
				$this->form_validation->set_rules('about_us', 'About your-self', 'trim|required');
				
			break;
			case'user':
				//$this->form_validation->set_rules('group_id', 'Profession', 'trim|required');
				$this->form_validation->set_rules('gender', 'Gender', 'trim|required');
				$this->form_validation->set_rules('firstName', 'First Name', 'trim|required');
				$this->form_validation->set_rules('lastName', 'Last Name', 'trim|required');
				//$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[user.email]');
				$this->form_validation->set_rules('country', 'Country', 'trim|required');
				$this->form_validation->set_rules('region', 'Region', 'trim|required');
				$this->form_validation->set_rules('city', 'City', 'trim|required');
				$this->form_validation->set_rules('postal_code', 'Postal Code', 'trim|required');
				$this->form_validation->set_rules('address', 'Address', 'trim|required');
				$this->form_validation->set_rules('phone_number', 'Phone number', 'trim|required');
				$this->form_validation->set_rules('about_us', 'About your-self', 'trim|required');
			break;		
			case'changePassword':
				$this->form_validation->set_rules('oldpass', 'Old Password', 'trim|required');
				$this->form_validation->set_rules('newpass', 'New Password','trim|required|min_length[6]|matches[newpassconf]');
				$this->form_validation->set_rules('newpassconf', 'New Password Confirm', 'trim|required');
			break;
			
			case'changePassword_verify':
				$this->form_validation->set_rules('newpass', 'New Password','trim|required|min_length[6]|matches[newpassconf]');
				$this->form_validation->set_rules('newpassconf', 'New Password Confirm', 'trim|required');
			break;
			case'settings':
			   $this->form_validation->set_rules('setting_currency', 'setting_currency', 'trim|required');
			 
			break;
			default:
				$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
				$this->form_validation->set_rules('password', 'Password', 'trim|required');
			break;
		}
				$this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button data-dismiss="alert" class="close">×</button><i class="fa fa-times-circle"></i> ', '</div>');
				
		return $this->form_validation->run();
	}
	
	function account_status()
	{
	 $uID            = $this->input->post('uID',TRUE);
	 $status         = $this->input->post('status',TRUE);
	 $account_status = $this->input->post('account_status',TRUE);
	 
	 /* 'account_status' => $account_status*/
	 $data = array('status' => $account_status);
	 $this->db->where('user_id', $uID);
	 $this->db->update('user', $data);
     return true;
	}
	public function deleteRefrence(){
	   $id=$this->input->post('id',TRUE); 
	   
	   if(!empty($id)){
	   
	    // $this->db->where('job_refrence_id', $id);
        // $this->db->delete('job_refrence');
	   
	   }
	   
	}
}
