<?php

class User_model extends CI_Model {



    var $details;

	

	function getUserProfileData($userID=''){		

		$this->db->select("u.*, CONCAT(u.firstName, ' ',  u.lastName) as name, ad.*, ug.groupName, CONCAT(crc.city , ', ' , r.region , ', ' , c.country) as location,
		(SELECT GROUP_CONCAT(specialties_id SEPARATOR ', ') FROM user_profession_specialties WHERE find_in_set(user_id, u.user_id)) as specialties_ids", false);

		$this->db->from('user u');

		$this->db->join('user_group ug', 'ug.group_id = u.group_id', 'LEFT');

		$this->db->join('agency_detail ad','ad.user_id = u.user_id', 'LEFT');

		

		$this->db->join('country c', 'c.countryid = u.country', 'LEFT');

		$this->db->join('country_regions r', 'r.regionid = u.region', 'LEFT');

		$this->db->join('country_region_cities crc','crc.cityId = u.city', 'LEFT');

		

		$this->db->where('u.user_id',$userID);

		$this->responseData = $this->db->get()->result_array();

		return $this->responseData;



    }

	

	function getMydashboardProfileData($userID=''){		

		$this->db->select("u.*, CONCAT(u.firstName, ' ',  u.lastName) as name, adu.*, ug.groupName, CONCAT(crc.city , ', ' , r.region , ', ' , c.country) as location,ad.agency_name as associate_agency_name,ad.agency_address as associate_agency_address,ad.agency_email as associate_agency_email,ad.agency_phone_number as associate_agency_phone_number,ad.agency_cell_number as associate_agency_cell_number,ad.agency_website as associate_agency_website,	

		 (SELECT COUNT(c.target_id) from comment c where c.target_id = u.user_id  GROUP BY c.target_id) as user_review, 

		  (SELECT COUNT(p.user_id) from property p where p.user_id = u.user_id && p.status = 'Active' GROUP BY p.user_id) as total_property,

		  

		  (SELECT COUNT(p.user_id) from property p where p.user_id = u.user_id && p.property_category = 1 && p.status = 'Active' GROUP BY p.user_id) as property_sale,

		  (SELECT COUNT(p.user_id) from property p where p.user_id = u.user_id && p.property_category = 2 && p.status = 'Active' GROUP BY p.user_id) as property_rent,

		  

		  (SELECT COUNT(p.user_id) from property p where p.user_id = u.user_id && p.property_category = 3 GROUP BY p.property_id) as user_id", false);

		

		$this->db->from('user u');

		$this->db->join('agency_detail adu','adu.user_id = u.group_id', 'LEFT');

		$this->db->join('user_group ug', 'ug.group_id = u.group_id', 'LEFT');

		$this->db->join('user_associated_agency  uassa', 'uassa.user_id = u.user_id', 'LEFT');

		$this->db->join('agency_detail ad','ad.agency_id = uassa.agency_id', 'LEFT');

		$this->db->join('country c', 'c.countryid = u.country', 'LEFT');

		$this->db->join('country_regions r', 'r.regionid = u.region', 'LEFT');

		$this->db->join('country_region_cities crc','crc.cityId = u.city', 'LEFT');

		

		$this->db->where('u.user_id',$userID);

		$this->responseData = $this->db->get()->result_array();

		return $this->responseData;



    }

	

	

function getUserProperty($user_id, $property_category,$limit = NULL){



$this->db->select('p.*,u.firstName,u.user_id,u.profile_image,u.email,u.about_us,u.phone_number,pc.categoryName,pt.typeName as property_type_name,ppm.name as property_price_modifier_name,pimg.image_name,(SELECT COUNT(upvc.property_id) from user_property_views upvc where p.property_id = upvc.property_id) as total_visits', false);



 $this->db->from('property p');

 $this->db->join('property_image pimg', 'pimg.property_id = p.property_id', 'LEFT');

 $this->db->join('user u', 'u.user_id = p.user_id', 'LEFT'); 

 $this->db->join('property_category pc', 'pc.property_category_id = p.property_category', 'LEFT');

 $this->db->join('property_types pt', 'pt.property_types_id = p.property_type', 'LEFT');

 $this->db->join('property_price_modifier ppm', 'ppm.property_price_modifier_id = p.price_modifier', 'LEFT');

 $this->db->where('p.user_id',$user_id); 

 $this->db->where('p.status','Active'); 

 $this->db->where('p.property_category',$property_category);

  if(!empty($limit)){

	$this->db->limit($limit);  

   }

$this->db->group_by("p.property_id");
	
	
   $this->db->order_by("p.property_id", "desc");

 

 $query = $this->db->get();

  if($query->num_rows()>0){

      $data =  $query->result_array();

   return $data;

    }else{

   return false;

 }

}



	

	

	function getProfileData($userID=''){		

		$this->db->select("u.*, CONCAT(u.firstName, ' ',  u.lastName) as name, ad.*, ug.groupName, CONCAT(crc.city , ', ' , r.region , ', ' , c.country) as location", false);

		$this->db->from('user u');

		$this->db->join('user_group ug', 'ug.group_id = u.group_id', 'LEFT');

		$this->db->join('user_associated_agency  uassa', 'uassa.user_id = u.user_id', 'LEFT');

		$this->db->join('agency_detail ad','ad.agency_id = uassa.agency_id', 'LEFT');

		

		$this->db->join('country c', 'c.countryid = u.country', 'LEFT');

		$this->db->join('country_regions r', 'r.regionid = u.region', 'LEFT');

		$this->db->join('country_region_cities crc','crc.cityId = u.city', 'LEFT');

		

		$this->db->where('u.user_id',$userID);

		$this->responseData = $this->db->get()->result_array();

		return $this->responseData;



    }

	

	function getMessage($user_id=''){

		$this->db->select('m.*,u.email,us.profile_image senderimage,us.email senderemail');

		$this->db->from('messages m');

		$this->db->join('user u', 'u.user_id ='.$user_id);

		$this->db->join('user us', 'm.sender = us.user_id');

		$this->db->like('m.receiver',$user_id);

		$this->db->where('m.owner',$user_id);

		$this->db->where('m.trash',0);

		$data = $this->db->get()->result_array();

	}

	

	function uploadProfileImage($files, $dstPath, $fileBodyName, $size=200){

		$fileName = $fileBodyName.'.jpeg';

		$this->file_upload->upload($files);

		if ($this->file_upload->uploaded){

			

		   // Upload Original Image

		   $this->file_upload->file_new_name_body   = $fileBodyName;

		   $this->file_new_name_ext					= 'jpeg';

		   $this->file_upload->image_resize         = false;

		   $this->file_upload->process($dstPath);

		   if ($this->file_upload->processed){

			  $fileName = $this->file_upload->file_dst_name;			  

		   }

		   	   

		   // create Thumbnail Image with 200

		   $this->file_upload->file_new_name_body   = $fileBodyName;

		   $this->file_new_name_ext					= 'jpeg';

		   $this->file_upload->image_resize         = true;

		   $this->file_upload->image_x              = $size;

		   $this->file_upload->image_ratio_y        = true;

		   $this->file_upload->process($dstPath.DIRECTORY_SEPARATOR.'thumb');

		   if ($this->file_upload->processed){

			  $fileName = $this->file_upload->file_dst_name;			  

		   }

		   

		   // create Thumbnail Image with 100

		   $this->file_upload->file_new_name_body   = $fileBodyName;

		   $this->file_new_name_ext					= 'jpeg';

		   $this->file_upload->image_resize         = true;

		   $this->file_upload->image_x              = 100;

		   $this->file_upload->image_ratio_y        = true;

		   $this->file_upload->process($dstPath.DIRECTORY_SEPARATOR.'150150');

		   if ($this->file_upload->processed){

			  $fileName = $this->file_upload->file_dst_name;			  

		   }

		   		   

		   $this->file_upload->clean();

		   

		}

		return $fileName;				

	}

	

	function updateProfileImage($user_id){

		

		$this->db->select('profile_image');

		$this->db->from('user');		

		$this->db->where(array('user_id'=>$user_id));

		$query = $this->db->get();

		foreach ($query->result_array() as $resultData){

			

			$userMainImage = USER_IMAGE_DIRPATH.DIRECTORY_SEPARATOR.$resultData['profile_image'];

			$userThumbImage = USER_IMAGE_DIRPATH.DIRECTORY_SEPARATOR.'thumb'.DIRECTORY_SEPARATOR.$resultData['profile_image'];

			$userThumb150150 = USER_IMAGE_DIRPATH.DIRECTORY_SEPARATOR.'150150'.DIRECTORY_SEPARATOR.$resultData['profile_image'];

			

			if($resultData['profile_image'] !='' && is_file($userMainImage) && file_exists($userMainImage)){

				@unlink($userMainImage);

			}

			if($resultData['profile_image'] !='' && is_file($userThumbImage) && file_exists($userThumbImage)){

				@unlink($userThumbImage);

			}

			if($resultData['profile_image'] !='' && is_file($userThumb150150) && file_exists($userThumb150150)){

				@unlink($userThumb150150);

			}								  

			

		} // CLose foreach loop

		

		return true;

			

	}	

	

    function authenticateUser($user_id_or_email, $type = 'email', $password=''){

	    $this->db->select('u.*, g.groupName');
        $this->db->from('user u');
		$this->db->join('user_group g', 'g.group_id = u.group_id', 'LEFT');
		if($user_id_or_email !='' && $type=='email'){
        	$this->db->where('u.email', $user_id_or_email);		
		}else{
			$this->db->where('u.user_id', $user_id_or_email);
		}		
       // $this->db->where('u.status', 'Active');
        $result = $this->db->get();
		$records = $result->num_rows();
		$recordsData = $result->result();
		
        if($records > 0) {						
			if($user_id_or_email !='' && $type=='email' && $password !=''){						
				if(decrypt_data($recordsData[0]->password) == trim($password)){		
					
					if($recordsData[0]->status == 'Active'){
					   $this->details = $recordsData[0];				
					   $this->set_session($recordsData[0]);
					   //return true;
					   return 1;
					}else if($recordsData[0]->status == 'Inactive'){
						//$this->messageci->set('your a/c is Inactive please please varify email after that login.','error');
				        //return false;
						$msg = 'Your account is inactive. Please verify email after login.For Verification <a href="'.base_url('user/mailverify/'.$recordsData[0]->user_id).'" >Click here</a>';
						return $msg;	  
					}else if($recordsData[0]->status == 'Deactivate'){
						//$this->messageci->set('your a/c is deactivate please contact to admin <a href="'.base_url('faq/contact').'" >Cleck here</a>.','error');
						//return false;
						$msg = 'Your A/C has been deactivated, Kindly contact to wed-admin <a href="'.base_url('faq/contact').'" >Click here</a>';
						return $msg;	
					}
				}else{
					//return false;
					$msg = 'Incorrect Password';
					return $msg;
				}
			}else{				
			
				$this->details = $recordsData[0];				

				$this->set_session($recordsData[0]);

				//return true;
				return 1;				

			}

        }

        //return false;
		$msg = 'Incorrect Email-ID';
					return $msg;

    }



    function set_session($userData = '') {

		

		$this->details = (!isset($this->details) && empty($this->details))? $userData : $this->details;

			

        $this->session->set_userdata(array('user_id'		=> $this->details->user_id,

											'name'			=> $this->details->firstName,

											'fullName'		=> $this->details->firstName.' '.$this->details->lastName,

											'email'			=> $this->details->email, 

											'groupName'		=> $this->details->groupName,  

											'group_id'		=> $this->details->group_id,

											'user_type'		=> $this->details->user_type,

											'status'		=> $this->details->status,
											
											'created_by'		=> $this->details->created_by,
											
											'registerDate' =>$this->details->registerDate,

											'profile_image' => $this->details->profile_image));

											

		$this->updateUserLoginStatus($this->details->user_id);

		

	}

	

	function updateUserLoginStatus($user_id=''){

		$timestamp = strtotime(date('Y-m-d h:i:s'));

		$dataArray = array('lastLoginDate' => $timestamp);

		if($user_id !=''){	

		$this->db->update('user', $dataArray, array('user_id' => $user_id));

		}

	}

	

	function recent_mail($receiver_email = ''){

				

		$this->db->select("mrr.*, m.subject, m.message, IF(m.sender_type = 'admin', a.profile_image,u.profile_image) as profile_image,
IF(m.sender_type = 'admin', CONCAT(a.firstName, ' ', a.lastName),CONCAT(u.firstName, ' ', u.lastName) )as name", false);

		$this->db->from('message_receiver_relation mrr');

		$this->db->join('message m', 'm.message_id = mrr.message_id', 'LEFT');
		$this->db->join('administrator a', 'a.email = mrr.sender_email', 'LEFT');
		$this->db->join('user u', 'u.email = mrr.sender_email', 'LEFT');

		$this->db->where('mrr.receiver_email', $receiver_email);

		$this->db->where('mrr.read_status', 0);

		$this->db->order_by("m.message_id", "desc");

		$result = $this->db->get();

		$resultRecords = $result->num_rows();

		if($resultRecords > 0){

			$data = array();

			foreach($result->result_array() as $resultData){
				
				
				$check=is_null($resultData['message']) ? 1 : 0;
				if(!empty($resultData['message'])&&($check==0)){
					$msg=substr(strip_tags($resultData['message']),0,100).'..';
				}else{
					$msg='';
					}

				$image = array('profile_image' => displayImage($resultData['profile_image'], USER_IMAGE_THUMB),

								'send_date' => get_time_ago($resultData['send_date']),
                                 
								'subject'=> $resultData['subject']?$resultData['subject']:'',
								 
								'message' => $msg);		

								

				$data[] = array_replace($resultData, $image);		

			}

		}

		

		$data = array('total' => $resultRecords, 'data' => $data);
		return $data;

	}
	
	function get_advertise_link($group_id){
		/* $sql = "SELECT um.* FROM user_group_todo_menu_permission as up join user_group_todo_menu um on up.todo_menu_id = um.todo_menu_id where up.group_id = $group_id AND um.status = 'Active' ORDER BY `up`.`priority` DESC";*/
		 
		$this->db->select('um.*', false);
		$this->db->from('user_group_todo_menu_permission up');
		$this->db->join('user_group_todo_menu um', 'up.todo_menu_id = um.todo_menu_id');
		$this->db->where('up.group_id', $group_id);
		$this->db->where('um.status', 'Active');
		$this->db->order_by('up.priority', 'desc');
		$result = $this->db->get();
	    $resultRecords = $result->num_rows();
		 if($resultRecords > 0){
			 return $result->result_array();
		  }else{
			  return false;
		 }
		
		}
		
		/*
		 AND upvc.property_view_date >= curdate( ) - INTERVAL DAYOFWEEK( curdate( ) ) +6 DAY
			 AND upvc.property_view_date < curdate( ) - INTERVAL DAYOFWEEK( curdate( ) ) -1 DAY
			 */
		function get_property_vist_list($id){
			$this->db->select('p.property_id,p.property_name, p.address, p.zipcode,p.bedrooms,p.auction_status,pc.categoryName,pty.typeName, 
			
			(SELECT GROUP_CONCAT(user_id SEPARATOR ", ") FROM user_property_views WHERE find_in_set(property_id, p.property_id)) as user_id,
			
			(SELECT COUNT(upvc.property_id) from user_property_views upvc where p.property_id = upvc.property_id) as total_visits,
			
			(SELECT COUNT( upvc.property_id ) FROM user_property_views upvc WHERE p.property_id = upvc.property_id AND upvc.property_view_date >= curdate( ) - INTERVAL DAYOFWEEK( curdate( ) ) +6 DAY AND upvc.property_view_date < curdate( ) - INTERVAL DAYOFWEEK( curdate( ) ) -1 DAY ) as weekly_visits,
			 
			(SELECT COUNT( DISTINCT upvc.user_id ) FROM user_property_views upvc WHERE p.property_id = upvc.property_id) as unique_visits', false);
			$this->db->from('property p');
			$this->db->join('user u', 'u.user_id = p.user_id', 'LEFT');
		    $this->db->join('property_category pc', 'pc.property_category_id = p.property_category', 'LEFT');
	        $this->db->join('property_types pty', 'pty.property_types_id = p.property_type', 'LEFT');
			$this->db->where('p.user_id', $id);
			$this->db->where('p.status', 'Active');
			$this->db->order_by("p.property_id", "desc");
			$result = $this->db->get();
			$resultRecords = $result->num_rows();
			 if($resultRecords > 0){
				 return $result->result_array();
			  }else{
				  return false;
			 }
		
		}
		
		function get_recently_contacted_customer($userEmail)
		{
			//$userEmail = $this->session->userdata('email');
			//$this->db->select("SELECT mrr.*, CONCAT(u.firstName,' ',u.lastName) as sender_name, m.subject, m.message, m.attachment FROM message_receiver_relation mrr LEFT JOIN user u ON u.email = mrr.sender_email LEFT JOIN message m ON m.message_id = mrr.message_id WHERE mrr.receiver_email='".$userEmail."' AND mrr.trash=0 ORDER BY mrr.send_date DESC"); //Query text here"
			$this->db->select("mrr.*, m.subject, m.message, CONCAT(u.firstName,' ',u.lastName) as sender_name, m.message, m.attachment", false);
			$this->db->from('message_receiver_relation mrr');
			$this->db->join('message m', 'm.message_id = mrr.message_id', 'LEFT');
			$this->db->join('user u', 'u.email = mrr.sender_email', 'LEFT');
			$this->db->where('mrr.receiver_email', $userEmail);
			$this->db->where('mrr.trash', 0);
			$this->db->order_by('mrr.send_date','DESC');	
			$result = $this->db->get();
			$resultRecords = $result->num_rows();
			if($resultRecords > 0){
				 return $result->result_array();
			  }else{
				  return false;
			 }
		}
		function get_received_enquiry_for_valuation($user_id)
		{
			$this->db->select('*');
			$this->db->from('free_valuation');
			$this->db->where('receiver_id', $user_id);
			$this->db->order_by('date_time','DESC');
			$result = $this->db->get();
			$resultRecords = $result->num_rows();
			if($resultRecords > 0){
				 return $result->result_array();
			  }else{
				  return false;
			 }
		}
		function get_property_favourite_list($user_id){
		 
		 $sql="SELECT p.property_id, p.property_name,p.address,p.bedrooms,p.auction_status,t.typeName,c.categoryName 
											FROM property p
											left join property_types t on p.property_type = t.property_types_id
											left join property_category c on p.property_category = c.property_category_id
											where 
											(property_id
											IN (
											SELECT user_property_favorites.property_id
											FROM user_property_favorites
											WHERE user_property_favorites.user_id =".$user_id."))";
											
			$query = $this->db->query($sql);
		  if($query->num_rows()>0){
			 $result = $query->result_array();
			 return $result;
		   }else{
			  return false; 
		  }									
		}
		
		function self_user_property_visit_list($user_id){
		    $this->db->select('p.property_id,p.property_name, p.address, p.zipcode,p.bedrooms,p.auction_status,pc.categoryName,pty.typeName', false);
			$this->db->from('user_property_views upvc');
			$this->db->join('property p', 'p.property_id = upvc.property_id', 'LEFT');
		    $this->db->join('property_category pc', 'pc.property_category_id = p.property_category', 'LEFT');
	        $this->db->join('property_types pty', 'pty.property_types_id = p.property_type', 'LEFT');
			$this->db->where('upvc.user_id', $user_id);
			$result = $this->db->get();
			$resultRecords = $result->num_rows();
			 if($resultRecords > 0){
				 return $result->result_array();
			  }else{
				  return false;
			 }
		}
		
		function user_manage_advert($user_id){
		    $this->db->select('uma.*,map.page_name', false);
			$this->db->from('manage_advert uma');
			$this->db->join('manage_advertise_page map', 'map.page_id = uma.page_id', 'LEFT');
			$this->db->where('uma.user_id', $user_id);
			$result = $this->db->get();
			$resultRecords = $result->num_rows();
			 if($resultRecords > 0){
				 return $result->result_array();
			  }else{
				  return false;
			 }
		}


}

