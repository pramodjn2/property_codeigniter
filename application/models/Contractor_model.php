<?php
class Contractor_model extends CI_Model {

function contractorListingCount($post= NULL){
	 
	 $this->db->select('*');
	 $this->db->from('user u');
	  $this->db->join('user_associated_agency uaa', 'uaa.user_id = u.user_id', 'LEFT'); 
     $this->db->join('agency_detail ad', 'ad.agency_id = uaa.agency_id', 'LEFT'); 
	 $this->db->where('u.group_id', 7);
	 $this->db->where('u.status', 'Active');
	 
	 foreach($post as $key => $val){
	  if(!empty($val)){
		$this->session->set_userdata($key, $val);  
	  }else{
		$this->session->unset_userdata($key, '');   
	  }
	 }
	
	 if(!empty($post['location'])){
        $this->db->like('u.address', $post['location'],'start'); 
	 }if(!empty($post['name'])){
        $this->db->like('u.firstName', $post['name'],'start'); 
     }
	   $this->db->order_by("u.user_id", "RANDOM");
	   $query = $this->db->get();
       if($query->num_rows()>0){
		$result =  count($query->result_array());
	      return  $result;
	   }else{ 
	     return false;
	   }
   }
   
    function contractorListing($limit, $offset, $post= NULL,$type = 'array'){
	 $this->db->select('u.user_id,u.firstName,u.profile_image,u.about_us,u.phone_number,u.address,ad.agency_id,ad.agency_logo,(SELECT COUNT(cmt.user_id) from comment cmt where cmt.user_id = u.user_id  GROUP BY cmt.user_id) as review_count');
	 $this->db->from('user u');
	 $this->db->join('user_associated_agency uaa', 'uaa.user_id = u.user_id', 'LEFT'); 
     $this->db->join('agency_detail ad', 'ad.agency_id = uaa.agency_id', 'LEFT'); 
	
	 $this->db->where('u.group_id', 7);
	 $this->db->where('u.status', 'Active');

    if(!empty($post)){
	 foreach($post as $key => $val){
	  if(!empty($val)){
		$this->session->set_userdata($key, $val);  
	  }else{
		$this->session->unset_userdata($key, '');   
	  }
	 }
	
	 if(!empty($post['location'])){
        $this->db->like('u.address', $post['location'],'start'); 
	 }if(!empty($post['name'])){
        $this->db->like('u.firstName', $post['name'],'start'); 
     }
	}
	   $this->db->order_by("u.user_id", "RANDOM");
	   $this->db->limit($limit, $offset);   
	   $query = $this->db->get(); 
       if($query->num_rows()>0){
		$result =  $query->result_array();
	      $url = config_item('base_url');
		   foreach($result as $val){
			
			$profile_image = getUserProfileImage($val['profile_image'],$url.'applicationMediaFiles/usersImage/150150/');
			$profile_image = $url.'applicationMediaFiles/usersImage/150150'.$profile_image;
			
			$agency_logo = getUserProfileImage($val['agency_logo'],$url.'applicationMediaFiles/companyImage/9934/');
			$agency_logo = $url.'applicationMediaFiles/companyImage/9934'.$agency_logo;
			
			    if(config_item('URL_ENCODE')){
				  $user_id = safe_b64encode($val['user_id']);
				  $agency_id = safe_b64encode($val['agency_id']);	
				}else{
				  $user_id = $val['user_id'];	
				  $agency_id = $val['agency_id'];	
				}
				$manage[] = array("user_id" => $user_id,
								   "firstName" => ucwords($val['firstName']),
								   "review_count" => $val['review_count'] ? $val['review_count'] : 0,
								   "profile_image" => $profile_image,
								   "about_us" => $val['about_us'],
								   "phone_number" => $val['phone_number'] ? $val['phone_number'] :  '**********',
								   "address" => $val['address'],
								   "agency_logo" => $agency_logo,
								   "agency_id" => $agency_id); 


					  
		   }
			   if($type == 'json'){
	         	 return  json_encode($manage);
				}else{
				return  $manage;
				}
	   }else{ 
	     return false;
	   }
   }
   
   
  public function contractorDetails($id = NULL){
    if(empty($id)){
       return false;
       }

  $this->db->select('u.*,ad.agency_id,ad.agency_email,ad.agency_name,ad.agency_about_us,ad.agency_logo,ad.agency_phone_number,ad.agency_cell_number,ad.agency_address,ad.agency_website');
  $this->db->from('user u');
  $this->db->join('user_associated_agency uaa', 'uaa.user_id = u.user_id', 'LEFT'); 
  $this->db->join('agency_detail ad', 'ad.agency_id = uaa.agency_id', 'LEFT'); 
  $this->db->where('u.status', 'Active');
  $this->db->where('u.user_id', $id);
  $query = $this->db->get();
       if($query->num_rows()>0){
  $result =  $query->result_array();
       return  $result;
    }else{ 
      return false;
    }
   }

   
}