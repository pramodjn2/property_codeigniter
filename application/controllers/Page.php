<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page extends CI_Controller { 

	var $userID;  

	function __construct()    

    {

		parent::__construct();		

		$this->load->helper(array('form', 'url','common','captcha'));

		$this->load->library(array('session', 'form_validation','file_upload'));

		$this->load->database();  

		  $this->load->model('Common');	

		//$this->output->enable_profiler(TRUE);	

    }





	public function index()

    {	

	    $urlID = $this->uri->segment(3);

		$this->load->model('Common');

	    $data['scriptsrc'] = array('assets/plugins/revolution_slider/rs-plugin/js/jquery.themepunch.plugins.min.js', 

									'assets/plugins/revolution_slider/rs-plugin/js/jquery.themepunch.revolution.min.js',

									'assets/plugins/flex-slider/jquery.flexslider.js',

									'assets/plugins/stellar.js/jquery.stellar.min.js',

									'assets/plugins/colorbox/jquery.colorbox-min.js',

									'assets/js/front-end-index.js',

									'assets/plugins/select2/select2.min.js',

									'assets/plugins/jQuery-Knob/js/jquery.knob.js',

									'assets/js/custom/email-subscribe.js');



		

		

		

		$where="where static_pages_id = ".$urlID;

		$data['result']=$this->Common->select('manage_static_pages',$where);

		

		$pagename = $data['result'][0]['page_name'];

		if($pagename == "career")

		{

		 redirect('page/career');

		}else{

		$this->load->view('page', $data);}

	}

	
	
	
	public function content()
    {	
	    //$this->output->enable_profiler(TRUE);	
		$urlID = $this->uri->segment(3);
        if (!is_numeric($urlID)) {
			$urlID = safe_b64decode($urlID);
	    }
		
		$this->load->model('Common');

	    $data['scriptsrc'] = array('assets/plugins/revolution_slider/rs-plugin/js/jquery.themepunch.plugins.min.js', 

									'assets/plugins/revolution_slider/rs-plugin/js/jquery.themepunch.revolution.min.js',

									'assets/plugins/flex-slider/jquery.flexslider.js',

									'assets/plugins/stellar.js/jquery.stellar.min.js',

									'assets/plugins/colorbox/jquery.colorbox-min.js',

									'assets/js/front-end-index.js',

									'assets/plugins/select2/select2.min.js',

									'assets/plugins/jQuery-Knob/js/jquery.knob.js',

									'assets/js/custom/email-subscribe.js');



		

		

		

		$where="where static_pages_id = ".$urlID;

		$data['result']=$this->Common->select('manage_static_pages',$where);

		

		$pagename = $data['result'][0]['page_name'];
		
		 $pagetitle  = ucwords(trim(str_replace('_', ' ', $data['result'][0]['page_name'])));
		
		$title = $data['result'][0]['meta_title'];
		$data['title'] = $title ? $title : $pagetitle;
		
		$data['keywords'] = $data['result'][0]['meta_keys'];
		$data['description'] = $data['result'][0]['meta_description'];
		if($pagename == "careers" || $urlID == 9 )

		{

		 redirect('page/career');

		}else{

		$this->load->view('page', $data);}

	}




	

	public function Career(){

	 $this->load->model(array('Blog_model', 'Common'));

	    $data['scriptsrc'] = array('assets/plugins/revolution_slider/rs-plugin/js/jquery.themepunch.plugins.min.js', 

									'assets/plugins/revolution_slider/rs-plugin/js/jquery.themepunch.revolution.min.js',

									'assets/plugins/flex-slider/jquery.flexslider.js',

									'assets/plugins/stellar.js/jquery.stellar.min.js',

									'assets/plugins/colorbox/jquery.colorbox-min.js',

									'assets/js/front-end-index.js',

									'assets/plugins/select2/select2.min.js',

									'assets/plugins/jQuery-Knob/js/jquery.knob.js',

									'assets/js/custom/email-subscribe.js');



		

		$where="where page_name='careers' || static_pages_id  = 9";

		$data['result']=$this->Common->select('manage_static_pages',$where);

		

	 

	  $data['categoryData'][0] = array('categoryName' => '', 'blog_cat_id'=>'');

	  $data['results'] = $this->Blog_model->blogCategoryList();

	  $data['admin_job_role_applications'] = $this->Common->select('admin_job_role','where vacancy_status = "open" AND status = "Active" ');

	 

	  $data['page_title'] = 'blog';

	  $data['h1'] = "Welcome to my Career!";

	  $this->load->view('news_career/career',$data);

	  

	

	}

	

	function open_positions($categoryid = 0){
	
	
	  if (empty($categoryid)){

		  Redirect('page/career');

	  } else {
		 if (!is_numeric($categoryid)) {
			$categoryid = safe_b64decode($categoryid);
		 }
		  $adminjobappresults = $this->Common->select("admin_job_role"," where role_id = '" . $categoryid . "'");

		  if (empty($adminjobappresults) || count($adminjobappresults) <= 0){

			  Redirect('page/career');

			  return;

		  }

	  }

	  $data['page_title'] = 'apply';

	  $data['admin_job_role_application'] = $categoryid;

	  $data['adminjobappresults'] = $this->Common->select("admin_job_role"," where role_id = '" . $categoryid . "'");

	  $this->load->view('news_career/open_positions',$data);

	}

	

	

	function captcha_check($str)

	{

	 $capWord = $this->session->userdata('capWord');

	 if($capWord != $str)

	 {

	  $this->form_validation->set_message('captcha_check', 'The %s not match. ');

	  return FALSE;

	 }

	 else{return TRUE;}

	}

	

	

	function __setFormRules($setRulesFor = '',$fileType = ''){

	    switch($setRulesFor){

			case'jobapply':

				$this->form_validation->set_rules('uname', 'Name', 'trim|required');

				$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');

				$this->form_validation->set_rules('cell_number', 'Cell number', 'trim|required|integer|min_length[10]');

				$this->form_validation->set_rules('captchaName', 'Captcha', 'trim|required|callback_captcha_check');

				if($fileType == 0)

     $this->form_validation->set_rules('uploadresume', 'Upload not Valid Format', 'required');

				break;

				

		}

		$this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button data-dismiss="alert" class="close">×</button><i class="fa fa-times-circle"></i> ', '</div>');

		return $this->form_validation->run();

	}

	

	

	function apply($applicationid){
		
		if (!is_numeric($applicationid)) {
		   $applicationid = safe_b64decode($applicationid);
		}
		$app_seo_url = seo_friendly_urls('Click To Apply','',$applicationid); 
	  $data['applicationid'] = $applicationid;

	  $data['page_title'] = 'apply';	 

	  if (empty($applicationid)){

		redirect('page/career');

	  } else {
		   
		  $adminjobappresults = $this->Common->select("admin_job_role"," where role_id = '" . $applicationid . "'");

		  if (empty($adminjobappresults) || count($adminjobappresults) <= 0){

			  redirect('page/career');

			  return;

		  }

		  $data['job_position'] = $adminjobappresults[0]['role_title'];

	  }

	  

	  

	  if ($_POST){

		  $typeFile    = $this->input->post('file_type',TRUE);

		  

		  $passChangeFormValidation = $this->__setFormRules('jobapply',$typeFile);

		  

		  if($passChangeFormValidation){	

		  	  $username     = $this->input->post('uname',TRUE);

			  $email        = $this->input->post('email',TRUE);

			  $phone_number = $this->input->post('phone_number',TRUE);

			  $cell_number  = $this->input->post('cell_number',TRUE);

			  $desc         = $this->input->post('desc_user',TRUE);

			  

			  $insertdata = array();

			  $insertdata['job_role_id'] = $applicationid;

			  $insertdata['name'] = $username;

			  $insertdata['email'] = $email;

			  $insertdata['telephone'] = $phone_number;

			  $insertdata['mobile'] = $cell_number;

			  $insertdata['description'] = $desc;

			  

			  $uploadresume=$_FILES["uploadresume"]['name'];

			  if(!empty($uploadresume)){

				 $upload_directory = config_item('root_url').'applicationMediaFiles/appliedjob/';

				 $temp_name = $_FILES['uploadresume']['tmp_name'];

				 $ext = @pathinfo($uploadresume, PATHINFO_EXTENSION);

				 $floor_plan_name = time().rand(1000,99999).'.'.$ext;

				 $file_path = $upload_directory.$floor_plan_name; 

				 @move_uploaded_file($temp_name, $file_path);

				 $insertdata['upload_resume'] = $floor_plan_name;

			  }

			  

	  $check_ExistID =  ' where job_role_id = '.$applicationid.' AND email = "'.$email.'"'; 

			  $resultAlready = $this->Common->select('user_job_role_apply',$check_ExistID);

			  

			  if(!empty($resultAlready))

			  {

			   $this->session->set_flashdata('emailAlready', 'Already Apply');

			   redirect('page/apply/'.$app_seo_url);

			  }

			  else{

			  

			  $result = $this->Common->data_insert('user_job_role_apply', $insertdata);

			  

			  $where_roleID =  ' where role_id = '.$applicationid.''; 

			  $result = $this->Common->select('admin_job_role',$where_roleID);

			  

			  $roleName = 'Some Post';

			  if(!empty($result)){

	   				$roleName = $result[0]['role_title'];

	  			}else{$roleName = "Some Post";}

			  


			  $log_url = base_url('admin/');

			  $msg_body='';

			  $msg_body.='Here are Person Detail<br/><br/>';

			  $msg_body.='<b>Name:</b>'.ucfirst($username);

			  $msg_body.='<br/><br/>';

			  $msg_body.='<b>Email:</b>'.ucfirst($email);

			  $msg_body.='<br/><br/>';

			  $msg_body.='<b>Mobile:</b>'.ucfirst($cell_number);

			  $msg_body.='<br/><br/>';

			  if(!empty($desc))

			  {

			  $msg_body.='<b>Descripition:</b>'.ucfirst($desc);

			  $msg_body.='<br/>';

			  }

			  $msg_body.='For login click here: <a href="'.$log_url.'">Link</a>';

			  

			  $hremail = config_item('hrinfo');

			  

			           $data['fullname'] = 'Admin';
					   $data['firstname'] = 'Admin';
					   $data['profile_image'] = '';
					 
		
					 
		 
					 
			  
			        $data['body']=$msg_body;
					$message = $this->load->view('my_account/message/template/simple_message', $data, TRUE); 
					$toEmail = $hremail;
					$fromEmail = array('email' => config_item('no_reply'),'name' => config_item('site_name'));
					$subject = ucwords(config_item('site_name').' Job Apply for '.$roleName.' Post');
					$attachment = array();
					$result = sendUserEmailCI($toEmail, $fromEmail, $subject, $message, $attachment,'','career_email');
			  
			       
					    $email = $this->input->post('email',TRUE);
						 
						
						
						
						
						$data=selectData('user',"where email='$email'");
						if(!empty($data)){
						 $data['profile_image'] = $data[0]['profile_image'];
						 
                         $seousername = str_replace('&nbsp;', '-', $this->input->post('uname',TRUE));
                         $data['recieverseo'] = seo_friendly_urls($seousername,'',$data[0]['user_id']);
						}else{
						 $data['profile_image'] ='';
						}
					 
		
					  $msg_body='Your job application has been submitted successfully, Otriga-team will contact you soon<br/><br/>';
		 
					 
			  
			        $data['body']=$msg_body;
					$username     = $this->input->post('uname',TRUE);
                    $data['fullname'] = $this->input->post('uname',TRUE);
					$data['firstname'] = $this->input->post('uname',TRUE);
					$message = $this->load->view('my_account/message/template/simple_message', $data, TRUE); 
					$toEmail = $this->input->post('email',TRUE);
					$fromEmail = array('email' => config_item('no_reply'),'name' => config_item('site_name'));
					$subject = ucwords(config_item('site_name').' Job Apply for '.$roleName.' Post');
					$attachment = array();
					$result = sendUserEmailCI($toEmail, $fromEmail, $subject, $message, $attachment,'','career_email');
			  
			  

			  $this->session->set_flashdata('success', 'Your job application has been submitted successfully, Otriga-team will contact you soon');

			  redirect('page/apply/'.$app_seo_url);

			  //$data['msg'] = array('success' => 'Application send successfully');

			  }

		  }

	  }

	 

	  $url = base_url('assets/captcha/');

	  $vals = array(

						'img_path'	=> './assets/captcha/',

						'img_url'	=> $url,

						'font_path' => base_url('system/fonts/texb.ttf'),

    					'img_width' => 150,

						'word_length'   => 4,

    					'img_height' => 40,

					 );

	  $cap = create_captcha($vals);

	  $data['capImage'] = $cap['image'];

	  $data['captchaWord'] = $cap['word'];

	  $set_session = array('capWord'=>$cap['word']);

	  $this->session->set_userdata($set_session);

	  $this->load->view('news_career/apply',$data);

	}

	

	public function checkMail($email='',$subject='',$msg_body=''){

	

	  	$emaildata['body'] = $msg_body; //$message;

	  	$attachment = array();

  		$subj = $subject;

  		$message = $this->load->view('my_account/message/template/simple_message', $emaildata, TRUE);

  		$toEmail = $email;

  		$fromEmail = array('email' => config_item('no_reply'),'name' => config_item('site_name'));

  		$result = sendUserEmailCI($toEmail, $fromEmail, $subj, $message, $attachment);

    }

	

	

}





