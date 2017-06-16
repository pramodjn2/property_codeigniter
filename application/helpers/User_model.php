<?php

class User_model extends CI_Model {

    var $details;
	
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
        $result = $this->db->get();
		$records = $result->num_rows();
		$recordsData = $result->result();
        if($records > 0) {						
            // check password with stored password
			if($user_id_or_email !='' && $type=='email' && $password !=''){						
				if(decrypt_data($recordsData[0]->password) == trim($password)){		
					$this->details = $recordsData[0];				
					$this->set_session($recordsData[0]);
					return true;
				}else{
					return false;
				}
			}else{				
				$this->details = $recordsData[0];				
				$this->set_session($recordsData[0]);
				return true;				
			}
        }
        return false;
    }

    function set_session($userData = '') {
		
		$this->details = (!isset($this->details) && empty($this->details))? $userData : $this->details;
			
        $this->session->set_userdata(array('user_id'		=> $this->details->user_id,
											'name'			=> $this->details->firstName,
											'fullName'		=> $this->details->firstName.' '.$this->details->lastName,
											'email'			=> $this->details->email, 
											'groupName'		=> $this->details->groupName,  
											'group_id'		=> $this->details->group_id,
											'status'		=> $this->details->status,
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
	
}
