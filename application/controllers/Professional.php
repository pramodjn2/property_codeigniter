<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Professional extends CI_Controller { 
	var $userID;
	function __construct()
    {
		parent::__construct();	
		$this->load->model(array('Professional_model', 'Common'));	
		//$this->output->enable_profiler(TRUE); 		
    }
	public function index()
    {	
	    $data['page_title'] = 'Search Professional';
		$this->load->view('professional/search', $data);
	}
	
	public function listing()
	{
		
		$postData = $this->input->get();
		
		
		
		$countryCode = $postData['country_code']; 
     $Location = $postData['location'];  
	if(empty($countryCode) && !empty($Location)){
	  $QUERY_STRING = @$_SERVER['QUERY_STRING'];
	 
	   $NewcountryCode = get_country_code($Location);
	   if(!empty($NewcountryCode)){
		  $postData['country_code'] = $NewcountryCode; 
		 }
	}
	
		
		//echo '<pre/>';
		//print_r($postData);
		
		//print_r($this->session->all_userdata());
		
		//$sessionData = $this->session->userdata('searchProfessional');
		/*if(!isset($postData) || empty($postData)){
			$this->input->post($sessionData);
		}else{
			$this->session->set_userdata(array('searchProfessional' => $this->input->post()));
		}*/
		
		
	   $professional_search = $this->session->userdata('professional_search'); 	
	   if(!empty($professional_search)){
		   $country_code = $professional_search['country_code'];   
			$regions = $professional_search['regions'];   
			$city = $professional_search['city'];   
			$postal_code = $professional_search['postal_code'];   
			$location = $professional_search['location'];   
			$name = $professional_search['name'];   
			$pro_type = $professional_search['pro_type'];   
			$profession = $professional_search['profession'];   
			
	   }
	  
	$data['country_code'] = $postData['country_code'] ? $postData['country_code'] : $country_code;
	$data['regions'] = $postData['regions'] ? $postData['regions'] : $regions ;
	$data['city'] = $postData['city'] ? $postData['city'] : $city;
	$data['postal_code'] = $postData['postal_code'] ? $postData['postal_code'] : $postal_code;
	$data['location'] = $postData['location'] ? $postData['location'] : $location;
	$data['name'] = $postData['name'] ? $postData['name'] : $name;
	$data['pro_type'] = $postData['pro_type'] ? $postData['pro_type'] : $pro_type;
	$data['profession'] = $postData['profession'] ? $postData['profession'] : $profession;
	$data['profession_type'] = $postData['profession'] ? $postData['profession'] : 5; 
		
		$this->form_validation->set_data($postData);
		$searchFormValidation = $this->__setFormRules('searchProfessional');
		
		//if($searchFormValidation || (isset($sessionData) && !empty($sessionData))){
		if ($this->form_validation->run() == TRUE){
			$post = $this->input->get();
			//echo '---';
			// print_r($post);
			$this->load->library("pagination");		 
			$limit = 9;
			$dispaly = 1;
			$dispaly_from = $limit;
			$offset = ($this->uri->segment(3))? $this->uri->segment(3) : NULL;
			if(!empty($offset)){
				if($offset != 1){
				  $dispaly =  (($offset-1) * $limit);	
				}else{
					$dispaly = $limit;
				}
				
				$offset  = (($offset-1) * $limit); 
				$dispaly_from = $offset + $limit ;
				
			}
			 
			$data['agent_listing_count'] = $this->Professional_model->professionalListingCount($post);
				
			if(!empty($data['agent_listing_count'])){
				$data['results']=$this->Professional_model->professionalListing($limit, $offset, $post);
			if($dispaly_from > $data['agent_listing_count']){
				$dispaly_from = $data['agent_listing_count'];	
			 }
				$data['agent_results'] = array('message' => 'success',
												 'total_count' => $data['agent_listing_count'],
				  'num_display' => 'Displaying '.$dispaly.' to '.$dispaly_from.' of '. $data['agent_listing_count']);
			}else{
				$data['results']= '';
				$data['agent_results'] = array('message' => 'No agency found',
											 'total_count' => '',
											 'num_display' => '');
			}
			  
			 
			$config["base_url"] = base_url("professional/listing");
			$config["total_rows"] = $data['agent_listing_count'];
			$config["per_page"] = $limit;
			$config["uri_segment"] = 3;
			$config['num_links'] = 2; 
			$config['display_pages'] = TRUE;  
			$config = array_merge($config, $this->config->item('pagingConfig'));
			$this->pagination->initialize($config);
			$data["links"] = $this->pagination->create_links();		
			
			$data["post_val"] = $post;
			
		}else{
			//echo '--=========================';
			$data["links"] ='';
			$this->messageci->set(strip_tags(validation_errors()), 'error');
		}
		
		$data['page_title'] = 'Professional listing';
		$data['stylesheet'] = array('assets/css/pagination.css');
        $this->load->view('professional/listing', $data);
		
	}
	public function adddb(){
		$data = array(
			   	'user_id' => $_POST['user_id_log'] ,
   				'property_id' => $_POST['property_id'] ,
   				'comment' => $_POST['comment'] ,
     			'comment_parent_id' => $_POST['comment_parent_id'] ,
	 			'date_time' => $_POST['date_time']);
		$this->db->insert('comment', $data); 
		$this->details();
		if(config_item('URL_ENCODE')){
		   $user_id = safe_b64encode($_POST['user_id_log']);
		}else{
		   $user_id = $_POST['user_id_log'];	
		}
           redirect('professional/details/'.$user_id);
	}
	
	function groupName($group_id){		
		$group = array('5'=>'agent_detail', '6'=>'solicitor_detail', '7'=>'contractor_detail', '8'=>'user_detail');
		return array_key_exists($group_id, $group)? $group[$group_id]: 'agent_detail';
	}
	
	public function details()
	{
		$id = $this->uri->segment(3);
		if (!is_numeric($id)) {
			$id = safe_b64decode($id);
	    }
		
		$user_details = getUserInformation($id);
		
		if($user_details[0]['status']!='Active'){
			  $this->messageci->set(strip_tags('Unauthorised Access'), 'error');
		  redirect(base_url());
		
		}
		
		
		$group_id = $user_details[0]['group_id'];
		$group_folder = $this->groupName($group_id);
		
		
		$this->load->model('Professional_model');
	  
	  $data['results'] = $this->Professional_model->professionale_property($id); 
	  
	  $this->load->library("pagination");
	  $data['page_title'] = 'Agent Detail';
	
	
	  $data['stylesheet'] = array('assets/css/pagination.css');
									
    
		
		$data['agent_detail'] = $this->Professional_model->agentDetails($id);
		
			
			if(!empty($data['agent_detail'])){
				 $firstName = $data['agent_detail'][0]['firstName'];
				 $lastName = $data['agent_detail'][0]['lastName'];
				 $gender = $data['agent_detail'][0]['gender'];
				 $mr = 'Mr.';
				 if($gender == 'Female'){
					$mr = 'Ms.'; 
				 }
				$data['title'] = ucwords($mr.' '. $firstName.' '. $lastName);
				}
          
		
		
		
		$limit = 4;
		$dispaly = 1;
		$dispaly_from = $limit;
		$offset = ($this->uri->segment(5))? $this->uri->segment(5) : NULL;
		if(!empty($offset)){
			if($offset != 1){
			  $dispaly =  (($offset-1) * $limit);	
			}else{
				$dispaly = $limit;
			}
			
		 	$offset  = (($offset-1) * $limit); 
			$dispaly_from = $offset + $limit ;
			
		}
		$data['active_listing_count']=$this->Professional_model->ActivePropertyCount($id);
		
		
		
		if(!empty($data['active_listing_count'])){
		$data['active_results']=$this->Professional_model->ActiveProperty($limit, $offset,$id);		
		
	    if($dispaly_from > $data['active_listing_count']){
		   $dispaly_from = $data['active_listing_count'];	
		 }
		$data['active_property_results'] = array('message' => 'success',
		                                 'total_count' => $data['active_listing_count'],
	      'num_display' => 'Displaying '.$dispaly.' to '.$dispaly_from.' of '. $data['active_listing_count']);
		}else{
		$data['active_results']= '';
	    $data['active_property_results'] = array('message' => 'No Active Listing found',
		                                 'total_count' => '',
	                                     'num_display' => '');
		}
		
		$data['sale_listing_count']=$this->Professional_model->ActivePropertyCount($id,'Sale');
		if(!empty($data['sale_listing_count'])){
		$data['sale_results']=$this->Professional_model->ActiveProperty($limit, $offset,$id,'Sale');
		
		
		
	    if($dispaly_from > $data['sale_listing_count']){
		   $dispaly_from = $data['sale_listing_count'];	
		 }
		$data['sale_property_results'] = array('message' => 'success',
		                                 'total_count' => $data['sale_listing_count'],
	      'num_display' => 'Displaying '.$dispaly.' to '.$dispaly_from.' of '. $data['sale_listing_count']);
		}else{
		$data['sale_results']= '';
	    $data['sale_property_results'] = array('message' => 'No Active Listing found',
		                                 'total_count' => '',
	                                     'num_display' => '');
		}
		
		
		
		
		
		$data['rent_listing_count']=$this->Professional_model->ActivePropertyCount($id,'Rent');
		if(!empty($data['rent_listing_count'])){
		$data['rent_results']=$this->Professional_model->ActiveProperty($limit, $offset,$id,'Rent');
		
		
		
	    if($dispaly_from > $data['rent_listing_count']){
		   $dispaly_from = $data['rent_listing_count'];	
		 }
		$data['rent_property_results'] = array('message' => 'success',
		                                 'total_count' => $data['rent_listing_count'],
	      'num_display' => 'Displaying '.$dispaly.' to '.$dispaly_from.' of '. $data['rent_listing_count']);
		}else{
		$data['rent_results']= '';
	    $data['rent_property_results'] = array('message' => 'No Active Listing found',
		                                 'total_count' => '',
	                                     'num_display' => '');
		}
		
		
		
			$agent_id = $id;	
		
		
		
		
		$config["base_url"] = base_url() . "professional/details/".$agent_id;
		$config["total_rows"] = $data['active_listing_count'];
		$config["per_page"] = $limit;
		$config["uri_segment"] = 5;
		$config['num_links'] = 2; 
		$config['display_pages'] = TRUE;  
		$config = array_merge($config, $this->config->item('pagingConfig'));
		$this->pagination->initialize($config);
		$data["links"] = $this->pagination->create_links();
		
		
		
		
		
		
		$Climit = 5;
		$Cdispaly = 1;
		$Coffset = 0;
		$Cdispaly_from = $Climit;
		$Coffset = ($this->uri->segment(5))? $this->uri->segment(5) : NULL;
		if(!empty($Coffset)){
			if($Coffset != 1){
			  $Cdispaly =  (($Coffset-1) * $Climit);	
			}else{
				$Cdispaly = $Climit;
			}
			
		 	$Coffset  = (($Coffset-1) * $Climit); 
			$Cdispaly_from = $Coffset + $Climit ;
			
		}
		
		$data['active_comment_count']=$this->Professional_model->getCommentsCount($id);
		
		
		
		
		
		if(!empty($data['active_comment_count'])){
		$data['agent_comments']=$this->Professional_model->getComments($Climit, $Coffset,$id);		
		
	    if($Cdispaly_from > $data['active_comment_count']){
		   $Cdispaly_from = $data['active_comment_count'];	
		 }
		$data['agent_comments_results'] = array('message' => 'success',
		                                 'total_count' => $data['active_comment_count'],
	      'num_display' => 'Displaying '.$Cdispaly.' to '.$Cdispaly_from.' of '. $data['active_comment_count']);
		}else{
		$data['agent_comments']= '';
	    $data['agent_comments_results'] = array('message' => 'No Active Listing found',
		                                 'total_count' => '',
	                                     'num_display' => '');
		}
		
		
		$configC["base_url"] = base_url() . "professional/rating_detail/".$agent_id;
		$configC["total_rows"] = $data['active_comment_count'];
		$configC["per_page"] = $Climit;
		$configC["uri_segment"] = 5;
		$configC['num_links'] = 2; 
		$configC['display_pages'] = TRUE;  
		$configC = array_merge($configC, $this->config->item('pagingConfig'));
		$this->pagination->initialize($configC);
		$data["linksC"] = $this->pagination->create_links();
		
		
		
		
       //$data["agent_comments"]=$this->Professional_model->getComments($id);
		$this->load->view('professional/'.$group_folder,$data);
	}
	public function setdate(){
		$this->db->select('*');
		$this->db->from('comment_like');
		$this->db->where('comment_id',$_POST['postId']);
		$result = $this->db->get();
	 	$recordsData = $result->result();
		if(!empty($recordsData)){
			$data1 = array('likes' => $recordsData[0]->likes+1);
			$this->db->update('comment_like', $data1, array('comment_id' => $_POST['postId']));
	 	}else{
            $data = array(
                   'user_id' => $_POST['user_id'] ,
                   'comment_id' => $_POST['postId'] ,
                   'likes' => 1);
			$this->db->insert('comment_like', $data); 
	 	}
	 	$this->db->select('*');
		$this->db->from('comment_like');
		$this->db->where('comment_id',$_POST['postId']);
	 	$resualt = $this->db->get();
	 	$recordsDaaata = $resualt->result();
		echo $recordsDaaata[0]->likes.' person liked this';
	}
	public function unsetdate(){
		$this->db->select('*');
		$this->db->from('comment_like');
		$this->db->where('comment_id',$_POST['postId']);
	 	$result = $this->db->get();
	 	$recordsData = $result->result();
		if(!empty($recordsData)){
			$data1 = array( 'likes' => $recordsData[0]->likes-1);
			$this->db->update('comment_like', $data1, array('comment_id' => $_POST['postId']));
		}
	 	$this->db->select('*');
		$this->db->from('comment_like');
		$this->db->where('comment_id',$_POST['postId']);
	 	$resualt = $this->db->get();
	 	$recordsDaaata = $resualt->result();
		echo $recordsDaaata[0]->likes.' people Like this';
	}	
	public function write_review(){
	  $this->load->model('Common');
	 $id = ($this->uri->segment(3)) ? $this->uri->segment(3) : 84;
	 $data['target_id'] = $id;
	 
	 $data['stylesheet'] = array(//'assets/css/main.css',
	                             'assets/plugins/revolution_slider/rs-plugin/css/settings.css',
								 'assets/plugins/flex-slider/flexslider.css',
								 'assets/plugins/colorbox/example2/colorbox.css',
								 'assets/plugins/jQRangeSlider/css/classic-min.css',
								 'assets/plugins/select2/select2.css'
								 
									);
	 
	 
	 
     
	 
	 $this->load->model('Professional_model'); 
	 $where = " where user_id = '".$id."'";
	 $data['agent_detail'] = $this->Common->select('user', $where);	 
	 
	  	  if($_POST){
		    
		  $user_id = $this->session->userdata('user_id') ? $this->session->userdata('user_id') : '90';
		  $target_id = $this->input->post('target_id',TRUE);
		  $comment = $this->input->post('comment',TRUE);
		  $service_provided = $this->input->post('service_provided',TRUE);
		  $year_of_services = $this->input->post('year_of_services',TRUE);
		  $address = $this->input->post('address',TRUE);
		  $local_knowledge = $this->input->post('local_knowledge',TRUE);
		  $process_expertise = $this->input->post('process_expertise',TRUE);
		  $responsiveness = $this->input->post('responsiveness',TRUE);
		  $negotiation_skills = $this->input->post('negotiation_skills',TRUE);
		  
		  $total=($local_knowledge+$process_expertise+$responsiveness+$negotiation_skills)/4;
		  
		  $date_time=time();
	   	  
			$formValidation = $this->__setFormRules($this->input->post());
			if ($this->form_validation->run() == TRUE){
	  
	  $data=array('user_id'=>$user_id,
	              'target_id'=>$target_id,
	              'comment'=>$comment,
	              'service_provided'=>$service_provided,
				  'year_of_services'=>$year_of_services,
				  'address'=>$address,
				  'local_knowledge'=>$local_knowledge,
				  'process_expertise'=>$process_expertise,
				  'responsiveness'=>$responsiveness,
				  'negotiation_skills'=>$negotiation_skills,
				  'date_time'=>$date_time,
				  'total'=>$total
	  
	             );
				
	  
	          $lastinsertId= $this->Common->data_insert('comment',$data);	
			  
			  if(config_item('URL_ENCODE')){
					 $target_id = safe_b64encode($target_id);	
				}else{
					 $target_id = $target_id;	
				}
			  
			  
			   $reciever=getUserInformation($target_id);
			   
				
				
				
				$data['sender_fullname'] = $this->session->userdata('fullName');;
				$data['sender_firstname'] = $this->session->userdata('name');
				$data['sender_profile_image'] = $this->session->userdata('profile_image');
				
				
				$seousername = str_replace('&nbsp;', '-', $data['sender_fullname']);
				$data['senderseo'] = seo_friendly_urls($seousername,'',$this->session->userdata('user_id'));
		 

				$data['fullname'] = $reciever[0]['firstName'].' ' .$reciever[0]['lastName'];
				$data['firstname'] = $reciever[0]['firstName'];
				$data['profile_image'] = $reciever[0]['profile_image'];
				
				
				$seousername = str_replace('&nbsp;', '-', $data['fullname']);
				$data['recieverseo'] = seo_friendly_urls($seousername,'',$reciever[0]['user_id']);
				
				
				$message = $this->load->view('my_account/message/template/profile_comment', $data, TRUE); 
				$toEmail = $reciever[0]['email'];
				$fromEmail = array('email' => config_item('no_reply'),'name' => config_item('site_name'));
				$subject = ucwords(config_item('site_name').' - '.$data['sender_fullname'].' makes review on your profile');
				$attachment = array();
				$result = sendUserEmailCI($toEmail, $fromEmail, $subject, $message, $attachment);
				
				
				
				
				
				
			  
			  
			  
			  redirect('professional/details/'.$target_id);
	      }else{
		      $data['post_val'] = array($this->input->post());
			  $this->load->view('professional/write_review',$data);
		  }
	  
	  }
	 
	 
	 
	 
	 $this->load->view('professional/write_review', $data);
	
	}
	public function save_comment(){
	
	
	   $data['stylesheet'] = array('assets/css/main.css',
	                             'assets/plugins/revolution_slider/rs-plugin/css/settings.css',
								 'assets/plugins/flex-slider/flexslider.css',
								 'assets/plugins/colorbox/example2/colorbox.css',
								 'assets/plugins/jQRangeSlider/css/classic-min.css',
								 'assets/plugins/select2/select2.css'
								 
									);
	  $this->load->model('Common'); 
	
	  $user_id = $this->session->userdata('user_id') ? $this->session->userdata('user_id') : '90';
	  $target_id = $this->input->post('target_id',TRUE);
	  $comment = $this->input->post('comment',TRUE);
	  $service_provided = $this->input->post('service_provided',TRUE);
	  $year_of_services = $this->input->post('year_of_services',TRUE);
	  $address = $this->input->post('address',TRUE);
	  $local_knowledge = $this->input->post('local_knowledge',TRUE);
	  $process_expertise = $this->input->post('process_expertise',TRUE);
	  $responsiveness = $this->input->post('responsiveness',TRUE);
	  $negotiation_skills = $this->input->post('negotiation_skills',TRUE);
	  
	  $total=($local_knowledge+$process_expertise+$responsiveness+$negotiation_skills)/4;
	  
	  $date_time=time();
	  
	  
	  $this->load->model('Professional_model'); 
	  $data['agent_detail'] = $this->Professional_model->agentDetails($agent_id);
	  
	  if($_POST){
	   	  
			$formValidation = $this->__setFormRules($this->input->post());
			if ($this->form_validation->run() == TRUE){
	  
	  $data=array('user_id'=>$user_id,
	              'target_id'=>$target_id,
	              'comment'=>$comment,
	              'service_provided'=>$service_provided,
				  'year_of_services'=>$year_of_services,
				  'address'=>$address,
				  'local_knowledge'=>$local_knowledge,
				  'process_expertise'=>$process_expertise,
				  'responsiveness'=>$responsiveness,
				  'negotiation_skills'=>$negotiation_skills,
				  'date_time'=>$date_time,
				  'total'=>$total
	  
	             );
				
	  
	          $lastinsertId= $this->Common->data_insert('comment',$data);	
			  
			  if(config_item('URL_ENCODE')){
					 $target_id = safe_b64encode($target_id);	
				}else{
					 $target_id = $target_id;	
				}
			  
			  
			   $reciever=getUserInformation($target_id);
			   
			   $sender=getUserInformation($user_id);
			   
			   $senderName=$sender[0]['firstName'].' '.$sender[0]['lastName'];
			  
			   $subject=config_item('site_name').' Comment';
				
			   $msg_body='Dear User<br/>';
				
			   $msg_body.=ucwords($senderName).' Comment on your profile';
				
			   $msg_body.='<br/>';
				
				$detail_url='<a href="'.config_item('base_url').'professional/details/'.$target_id.'">View</a>';
				
				$msg_body.='To view this click here:'.$detail_url.'';
				$msg_body.='<br/>';
				
				$email=$reciever[0]['email'];
				
				
				$tempname = 'comment_message';
				$this->checkMail($email,$subject,$msg_body,$tempname);
			  
			  
			  
			  redirect('professional/details/'.$target_id);
	      }else{
		      $data['post_val'] = array($this->input->post());
			  $this->load->view('professional/write_review',$data);
		  }
	  
	  }
	    
	}
	public function agency()
	{	
	 
	 $agency_array=array('profession'=>'4');
	 $data["post_val"]=$agency_array;
	 $this->load->library("pagination");
	 $data['page_title'] = 'Professional listing';
	 $data['scriptsrc'] = array('assets/plugins/revolution_slider/rs-plugin/js/jquery.themepunch.plugins.min.js', 
									'assets/plugins/revolution_slider/rs-plugin/js/jquery.themepunch.revolution.min.js',
									'assets/plugins/flex-slider/jquery.flexslider.js',
									'assets/plugins/stellar.js/jquery.stellar.min.js',
									'assets/plugins/colorbox/jquery.colorbox-min.js',
									'assets/js/front-end-index.js',
									'assets/plugins/select2/select2.min.js',
									'assets/plugins/jQuery-Knob/js/jquery.knob.js');
		$data['stylesheet'] = array('assets/css/pagination.css'); 
		$limit = 9;
		$dispaly = 1;
		$dispaly_from = $limit;
		$offset = ($this->uri->segment(3))? $this->uri->segment(3) : NULL;
		if(!empty($offset)){
			if($offset != 1){
			  $dispaly =  (($offset-1) * $limit);	
			}else{
				$dispaly = $limit;
			}
			
		 	$offset  = (($offset-1) * $limit); 
			$dispaly_from = $offset + $limit ;
			
		}
		$data['agent_listing_count']=$this->Professional_model->professionalListingCount($agency_array);
			
		if(!empty($data['agent_listing_count'])){
		$data['results']=$this->Professional_model->professionalListing($limit, $offset,$agency_array);
	    if($dispaly_from > $data['agent_listing_count']){
		   $dispaly_from = $data['agent_listing_count'];	
		 }
		$data['agent_results'] = array('message' => 'success',
		                                 'total_count' => $data['agent_listing_count'],
	      'num_display' => 'Displaying '.$dispaly.' to '.$dispaly_from.' of '. $data['agent_listing_count']);
		}else{
		$data['results']= '';
	    $data['agent_results'] = array('message' => 'No agency found',
		                                 'total_count' => '',
	                                     'num_display' => '');
		}
	      
		 
		$config["base_url"] = base_url() . "professional/agency";
		$config["total_rows"] = $data['agent_listing_count'];
		$config["per_page"] = $limit;
		$config["uri_segment"] = 3;
		$config['num_links'] = 2; 
		$config['display_pages'] = TRUE;  
		$config = array_merge($config, $this->config->item('pagingConfig'));
		$this->pagination->initialize($config);
		$data["links"] = $this->pagination->create_links();
        $this->load->view('professional/listing', $data);
		
	}
	public function agent()
	{	
	 
	 $agent_array=array('profession'=>'5');
	 $data["post_val"]=$agent_array;
	 $this->load->library("pagination");
	 $data['page_title'] = 'Professional listing';
	 $data['scriptsrc'] = array('assets/plugins/revolution_slider/rs-plugin/js/jquery.themepunch.plugins.min.js', 
									'assets/plugins/revolution_slider/rs-plugin/js/jquery.themepunch.revolution.min.js',
									'assets/plugins/flex-slider/jquery.flexslider.js',
									'assets/plugins/stellar.js/jquery.stellar.min.js',
									'assets/plugins/colorbox/jquery.colorbox-min.js',
									'assets/js/front-end-index.js',
									'assets/plugins/select2/select2.min.js',
									'assets/plugins/jQuery-Knob/js/jquery.knob.js');
		$data['stylesheet'] = array('assets/css/pagination.css'); 
		$limit = 9;
		$dispaly = 1;
		$dispaly_from = $limit;
		$offset = ($this->uri->segment(3))? $this->uri->segment(3) : NULL;
		if(!empty($offset)){
			if($offset != 1){
			  $dispaly =  (($offset-1) * $limit);	
			}else{
				$dispaly = $limit;
			}
			
		 	$offset  = (($offset-1) * $limit); 
			$dispaly_from = $offset + $limit ;
			
		}
		$data['agent_listing_count']=$this->Professional_model->professionalListingCount($agent_array);
			
		if(!empty($data['agent_listing_count'])){
		$data['results']=$this->Professional_model->professionalListing($limit, $offset,$agent_array);
	    if($dispaly_from > $data['agent_listing_count']){
		   $dispaly_from = $data['agent_listing_count'];	
		 }
		$data['agent_results'] = array('message' => 'success',
		                                 'total_count' => $data['agent_listing_count'],
	      'num_display' => 'Displaying '.$dispaly.' to '.$dispaly_from.' of '. $data['agent_listing_count']);
		}else{
		$data['results']= '';
	    $data['agent_results'] = array('message' => 'No agency found',
		                                 'total_count' => '',
	                                     'num_display' => '');
		}
	      
		 
		$config["base_url"] = base_url() . "professional/agent";
		$config["total_rows"] = $data['agent_listing_count'];
		$config["per_page"] = $limit;
		$config["uri_segment"] = 3;
		$config['num_links'] = 2; 
		$config['display_pages'] = TRUE;  
		$config = array_merge($config, $this->config->item('pagingConfig'));
		$this->pagination->initialize($config);
		$data["links"] = $this->pagination->create_links();
        $this->load->view('professional/listing', $data);
		
	}
	public function solicitor()
	{	
	 
	 $solicitor_array=array('profession'=>'6');
	 $data["post_val"]=$solicitor_array;
	 $this->load->library("pagination");
	 $data['page_title'] = 'Professional listing';
	 $data['scriptsrc'] = array('assets/plugins/revolution_slider/rs-plugin/js/jquery.themepunch.plugins.min.js', 
									'assets/plugins/revolution_slider/rs-plugin/js/jquery.themepunch.revolution.min.js',
									'assets/plugins/flex-slider/jquery.flexslider.js',
									'assets/plugins/stellar.js/jquery.stellar.min.js',
									'assets/plugins/colorbox/jquery.colorbox-min.js',
									'assets/js/front-end-index.js',
									'assets/plugins/select2/select2.min.js',
									'assets/plugins/jQuery-Knob/js/jquery.knob.js');
		$data['stylesheet'] = array('assets/css/pagination.css'); 
		$limit = 9;
		$dispaly = 1;
		$dispaly_from = $limit;
		$offset = ($this->uri->segment(3))? $this->uri->segment(3) : NULL;
		if(!empty($offset)){
			if($offset != 1){
			  $dispaly =  (($offset-1) * $limit);	
			}else{
				$dispaly = $limit;
			}
			
		 	$offset  = (($offset-1) * $limit); 
			$dispaly_from = $offset + $limit ;
			
		}
		$data['agent_listing_count']=$this->Professional_model->professionalListingCount($solicitor_array);
			
		if(!empty($data['agent_listing_count'])){
		$data['results']=$this->Professional_model->professionalListing($limit, $offset,$solicitor_array);
	    if($dispaly_from > $data['agent_listing_count']){
		   $dispaly_from = $data['agent_listing_count'];	
		 }
		$data['agent_results'] = array('message' => 'success',
		                                 'total_count' => $data['agent_listing_count'],
	      'num_display' => 'Displaying '.$dispaly.' to '.$dispaly_from.' of '. $data['agent_listing_count']);
		}else{
		$data['results']= '';
	    $data['agent_results'] = array('message' => 'No agency found',
		                                 'total_count' => '',
	                                     'num_display' => '');
		}
	      
		 
		$config["base_url"] = base_url() . "professional/solicitor";
		$config["total_rows"] = $data['agent_listing_count'];
		$config["per_page"] = $limit;
		$config["uri_segment"] = 3;
		$config['num_links'] = 2; 
		$config['display_pages'] = TRUE;  
		$config = array_merge($config, $this->config->item('pagingConfig'));
		$this->pagination->initialize($config);
		$data["links"] = $this->pagination->create_links();
        $this->load->view('professional/listing', $data);
		
	}
	public function contractor()
	{	
	 
	 $contractor_array=array('profession'=>'7');
	 $data["post_val"]=$contractor_array;
	 $this->load->library("pagination");
	 $data['page_title'] = 'Professional listing';
	 $data['scriptsrc'] = array('assets/plugins/revolution_slider/rs-plugin/js/jquery.themepunch.plugins.min.js', 
									'assets/plugins/revolution_slider/rs-plugin/js/jquery.themepunch.revolution.min.js',
									'assets/plugins/flex-slider/jquery.flexslider.js',
									'assets/plugins/stellar.js/jquery.stellar.min.js',
									'assets/plugins/colorbox/jquery.colorbox-min.js',
									'assets/js/front-end-index.js',
									'assets/plugins/select2/select2.min.js',
									'assets/plugins/jQuery-Knob/js/jquery.knob.js');
		$data['stylesheet'] = array('assets/css/pagination.css'); 
		$limit = 9;
		$dispaly = 1;
		$dispaly_from = $limit;
		$offset = ($this->uri->segment(3))? $this->uri->segment(3) : NULL;
		if(!empty($offset)){
			if($offset != 1){
			  $dispaly =  (($offset-1) * $limit);	
			}else{
				$dispaly = $limit;
			}
			
		 	$offset  = (($offset-1) * $limit); 
			$dispaly_from = $offset + $limit ;
			
		}
		$data['agent_listing_count']=$this->Professional_model->professionalListingCount($contractor_array);
			
		if(!empty($data['agent_listing_count'])){
		$data['results']=$this->Professional_model->professionalListing($limit, $offset,$contractor_array);
	    if($dispaly_from > $data['agent_listing_count']){
		   $dispaly_from = $data['agent_listing_count'];	
		 }
		$data['agent_results'] = array('message' => 'success',
		                                 'total_count' => $data['agent_listing_count'],
	      'num_display' => 'Displaying '.$dispaly.' to '.$dispaly_from.' of '. $data['agent_listing_count']);
		}else{
		$data['results']= '';
	    $data['agent_results'] = array('message' => 'No agency found',
		                                 'total_count' => '',
	                                     'num_display' => '');
		}
	      
		 
		$config["base_url"] = base_url() . "professional/contractor";
		$config["total_rows"] = $data['agent_listing_count'];
		$config["per_page"] = $limit;
		$config["uri_segment"] = 3;
		$config['num_links'] = 2; 
		$config['display_pages'] = TRUE;  
		$config = array_merge($config, $this->config->item('pagingConfig'));
		$this->pagination->initialize($config);
		$data["links"] = $this->pagination->create_links();
        $this->load->view('professional/listing', $data);
		
	}
	
	private function __setFormRules($formType = ''){ 
		switch($formType){
			case 'searchProfessional':
				$this->form_validation->set_rules('location', 'Location', 'required');	
				//$this->form_validation->set_rules('name', 'Name', 'trim');	
				$this->form_validation->set_rules('profession', 'Profession', 'trim');	
				//$this->form_validation->set_rules('pro_type', 'Professional Type', 'trim');	
				//$this->form_validation->set_rules('specialities', 'Specialities', 'trim');	
			break;
			default:
				$this->form_validation->set_rules('comment', 'Comment', 'required');
				$this->form_validation->set_rules('service_provided', 'Service provided', 'required');
				$this->form_validation->set_rules('year_of_services', 'Year of service', 'required');	
				$this->form_validation->set_rules('address', 'Address', 'required');
			break;
		}
  		
  		return $this->form_validation->run();
    }
	
 public function checkMail($email='',$subject='',$msg_body=''){
  $emaildata['body'] = $msg_body; //$message;
  if(empty($tempname))
    $tempname = 'simple_message';
	
  $attachment = array();
  $subj = $subject;
  $message = $this->load->view('my_account/message/template/'.$tempname, $emaildata, TRUE);
  $toEmail = $email;
  $fromEmail = array('email' => config_item('no_reply'),'name' => config_item('site_name'));
  $result = sendUserEmailCI($toEmail, $fromEmail, $subj, $message, $attachment);
  }
  public function rating_detail(){
    
	$id = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
	  $this->load->model('Professional_model');
	  
	  
	  $this->load->library("pagination");
	  
	
	  //$data['agent_detail'] = $this->Professional_model->agentDetails($id);
		
		$limit = 5;
	
		$dispaly = 1;
		$dispaly_from = $limit;
		$offset = ($this->uri->segment(4))? $this->uri->segment(4) : 0;
		if(!empty($offset)){
			if($offset != 1){
			  $dispaly =  (($offset-1) * $limit);	
			}else{
				$dispaly = $limit;
			}
			
		 	$offset  = (($offset-1) * $limit); 
			$dispaly_from = $offset + $limit ;
			
		}
		
		$data['active_comment_count']=$this->Professional_model->getCommentsCount($id);
		$config["base_url"] = base_url() . "professional/rating_detail/".$id;
		$config["total_rows"] = $data['active_comment_count'];
		$config["per_page"] = $limit;
		$config["uri_segment"] = 4;
		$config['num_links'] = 2; 
		//$config['display_pages'] = TRUE;  
		$config = array_merge($config, $this->config->item('pagingConfig'));
		$this->pagination->initialize($config);
		$data["links"] = $this->pagination->create_links();
		
		
		
		$data["script"] ='<script type="text/javascript">
		
		$(document).ready(function(){
	   $(".local-knowledge").jRating({
			isDisabled:true,
			rateMax : 5,
		});
		$(".progress-expertise").jRating({
			isDisabled:true,
			rateMax : 5,
		});
		$(".responsiveness").jRating({
			isDisabled:true,
			rateMax : 5,
		});
		$(".negotiation-skills").jRating({
			isDisabled:true,
			rateMax : 5,
		});
		$(".defaultReview").jRating({
			isDisabled:true,
			rateMax : 5,
		});
	});
		
		
  $(function(){
 applyPagination();
    function applyPagination() {
  $("#ajax_paging a").click(function() {
        var url = $(this).attr("href");
		
		
      ajaxPaging(url); 
      return false;
      });
      
    }
 function ajaxPaging(url){
  var res = url.split("/");
  var ln = res.length -1;
  
  var agentId = res.length -2;
  console.log(res[agentId])
   if(res[agentId] == "rating_detail"){
	 var url = base_url+"professional/rating_detail/"+res[ln];  
	}else{
 var url = base_url+"professional/rating_detail/"+res[agentId]+"/"+res[ln];
	}
	
  
  
  $.ajax({
   type: "POST",  
   url: url
  }).done(function(data){
  var json_result = JSON.parse(data);
  var message=json_result.message;
  if(message=="success"){			
				
				var results = json_result.results;			 			
				var pagination_link = json_result.pagination_link; 	
				var script = json_result.script;	
				
				$("#ajax_paging").html("");			
				jQuery("#ajax_paging").html(pagination_link);
				$("#showList").html(" ");				
				jQuery("#showList").html(results);	
				$("#script_attach").html(" ");		
				jQuery("#script_attach").html(script);							
		
			}else{				
				$("#showList").html("");			
				$("#showList").html("<div class=\"alert alert-danger\" >No recored found</div>");					
			}	
  
  });
 }});</script>';
		
		
		
		
	  
	  
	  if(!empty($data['active_comment_count'])){
		$data['agent_comments']=$this->Professional_model->getComments($limit, $offset,$id);
		
		$list='';
		
		
      if(!empty($data['agent_comments'])){
		  $cycle = 0;
	  foreach($data['agent_comments'] as $comments){
		       $urls = USER_IMAGE_150150;
		  
				$user_img  = getUserProfileImage($comments["profile_image"],$urls);
     if($comments['comment_parent_id'] == 0){
					if($cycle > 0){
						//echo '</div></div>';
						 $list.='</div></div>';
					}
			$cycle++;	
			
        $list.='<div class="media row"> 
          <a class="pull-left" href="'.base_url('professional/details/'.$comments['user_id']).'"> 
            <img class="media-object circle-img" alt="" src="'.$urls.$user_img.'"> 
          </a>
          <div class="media-body"> <div class="media messages">
            <h5 class="media-heading margin_bottom">
			  <a href="'.base_url('professional/details/'.$comments['user_id']).'"> 
			    '.$comments["firstName"].'
			  </a>
              <span class="date">'.ago($comments["date_time"]).'</span>
			  
			   <span class="pull-right" style="text-align:right;"> 
			 <a onclick="report_probleam('.$comments["comment_id"].','.$comments["target_id"].');" href="javascript:void(0);">
			  <i title="Review Reply" class="fa fa-flag pull-right"></i></a></span>
          		
        		 
        		  
      	    </span> 
			 
            </h5>
            
            <strong class="col-sm-2 no-padding" style="padding-top:5px;">Local knowledge:</strong>
            <div id="local-knowledge" class="local-knowledge" data-average="'.$comments["local_knowledge"].'" data-id="4"></div>
            <div class="clear margin_bottom"></div>
            <strong class="col-sm-2 no-padding" style="padding-top:5px;">Process expertise:</strong>
            <div id="progress-expertise" class="progress-expertise" data-average="'.$comments["process_expertise"].'" data-id="3"></div>
            <div class="clear margin_bottom"></div>
            <strong class="col-sm-2 no-padding" style="padding-top:5px;">Responsiveness:</strong>
            <div id="responsiveness" class="responsiveness" data-average="'.$comments["responsiveness"].'" data-id="3"></div>
            <div class="clear margin_bottom"></div>
            <strong class="col-sm-2 no-padding" style="padding-top:5px;">Negotiation skills:</strong>
            <div id="negotiation-skills" class="negotiation-skills" data-average="'.$comments["negotiation_skills"].'" data-id="3"></div>
           <div class="clear margin_bottom"></div>
            <div class="AD_commenttext">
              <p>'.ucwords($comments["comment"]).'</p>
            </div>
              </div> <div class="clear margin_bottom"></div>  
            <div class="clear"></div>
            <hr>';
	 }else{
		  $list.='<div class="media"> 
          <a class="pull-left" href="'.base_url('professional/details/'.$comments['user_id']).'"> 
            <img class="media-object circle-img" alt="" src="'.$urls.$user_img.'"> 
          </a>
          <div class="media-body"><div class="media messages">
            <h5 class="media-heading margin_bottom">
			  <a href="'.base_url('professional/details/'.$comments['user_id']).'"> 
			    '.$comments["firstName"].'
			  </a>
              <span class="date">'.ago($comments["date_time"]).'</span>
			
            </h5>
            <div class="AD_commenttext">
              <p>'.ucwords($comments["comment"]).'</p>
            </div>
            <span class="pull-right" style="text-align:right;"> 
     </div> <div class="clear margin_bottom"></div>  
            <div class="clear"></div>
            <hr></div></div>';
		 
		 }
		
		
      }	
	  }
		
		
		
		
		
		
				
		
	    if($dispaly_from > $data['active_comment_count']){
		   $dispaly_from = $data['active_comment_count'];	
		 }
		$data['agent_comments_results'] = array(
		                                   "message" => "success",
                                            "results" => $list,
                                           "pagination_link" => $data['links'],
										   "script"=>$data['script']
										 );
		}else{
		$data['agent_comments']= '';
	    $data['agent_comments_results'] = array('message' => "error",
		                                 'results' => "",
	                                     'pagination_link' => "");
		}
		
		
		
		
		
		echo json_encode($data['agent_comments_results']);
		die;
		
  } 
}
