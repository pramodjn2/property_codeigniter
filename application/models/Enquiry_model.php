<?php
class Agency_model extends CI_Model {
   
 function agencyListingCount($post= NULL){
	 
	 $this->db->select('*');
	 $this->db->from('agency_detail ad');
	 $this->db->where('ad.status', 'Active');
	 
	 foreach($post as $key => $val){
	  if(!empty($val)){
		$this->session->set_userdata($key, $val);  
	  }else{
		$this->session->unset_userdata($key, '');   
	  }
	 }
	
	 if(!empty($post['location'])){
        $this->db->like('ad.agency_address', $post['location'],'start'); 
	 }if(!empty($post['name'])){
        $this->db->like('ad.agency_name', $post['name'],'start'); 
     }
	   $this->db->order_by("ad.agency_id", "RANDOM");
	   $query = $this->db->get();
       if($query->num_rows()>0){
		$result =  count($query->result_array());
	      return  $result;
	   }else{ 
	     return false;
	   }
   }
   
  
   
	
   function agencyListing($limit, $offset, $post= NULL,$type = 'array'){
	 
	 $this->db->select('*');
	// $this->db->select('*');
	 $this->db->from('agency_detail ad');
	 $this->db->where('ad.status', 'Active');
  	
    if(!empty($post)){
	 foreach($post as $key => $val){
	  if(!empty($val)){
		$this->session->set_userdata($key, $val);  
	  }else{
		$this->session->unset_userdata($key, '');   
	  }
	 }
	
	 if(!empty($post['location'])){
        $this->db->like('ad.agency_address', $post['location'],'start'); 
	 }if(!empty($post['name'])){
        $this->db->like('ad.agency_name', $post['name'],'start'); 
     }
	}
	   $this->db->order_by("ad.agency_id", "RANDOM");
	   $this->db->limit($limit, $offset);   
	   $query = $this->db->get();
       if($query->num_rows()>0){
		$result =  $query->result_array();
	      $url = config_item('base_url');
		   foreach($result as $val){
			 $agency_logo = getUserProfileImage($val['agency_logo'],$url.'applicationMediaFiles/companyImage/9999/');
			$agency_logo = $url.'applicationMediaFiles/companyImage/9999'.$agency_logo;
			 
			    if(config_item('URL_ENCODE')){
				  $user_id = safe_b64encode($val['user_id']);	
				}else{
				  $user_id = $val['user_id'];	
				}
				
				 if(config_item('URL_ENCODE')){
				  $agency_id = safe_b64encode($val['agency_id']);	
				}else{
				  $agency_id = $val['agency_id'];	
				}
				
				$phone = $val['agency_phone_number'] ? $val['agency_phone_number'] :  $val['agency_cell_number'];
				
				 $manage[] = array("agency_id" => $agency_id,
				                    "user_id" => $user_id,
								   "agency_name" => ucwords($val['agency_name']),
								   "agency_logo" => $agency_logo,
								   "agency_about_us" => $val['agency_about_us'],
								   "agency_phone_number" => $phone ? $phone : '**********',
								   "agency_address" => $val['agency_address']); 


					  
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
   
   public function agencyDetails($id = NULL){
     if(empty($id)){
       return false;
       }

  $this->db->select('*');
  $this->db->from('agency_detail ad');
  $this->db->where('ad.status', 'Active');
  $this->db->where('ad.agency_id', $id);
  $query = $this->db->get();
  if($query->num_rows()>0){
  $result =  $query->result_array();
       return  $result;
    }else{ 
      return false;
    }

   }

   

}