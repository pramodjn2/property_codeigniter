<?php
class Agent_model extends CI_Model { 
 
 function agentListingCount($post= NULL){
	 
	 $this->db->select('*');
	 $this->db->from('user u');
	  $this->db->join('user_associated_agency uaa', 'uaa.user_id = u.user_id', 'LEFT'); 
     $this->db->join('agency_detail ad', 'ad.agency_id = uaa.agency_id', 'LEFT'); 
	 $this->db->where('u.group_id', 5);
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
   
  
   
	
   function agentListing($limit, $offset, $post= NULL,$type = 'array'){
	// $this->output->enable_profiler(TRUE);
	 $this->db->select('u.user_id,u.firstName,u.profile_image,u.about_us,u.phone_number,u.address,ad.agency_id,ad.agency_logo,(SELECT COUNT(cmt.user_id) from comment cmt where cmt.user_id = u.user_id  GROUP BY cmt.user_id) as review_count');
	 $this->db->from('user u');
	 $this->db->join('user_associated_agency uaa', 'uaa.user_id = u.user_id', 'LEFT'); 
     $this->db->join('agency_detail ad', 'ad.agency_id = uaa.agency_id', 'LEFT'); 
	
	 $this->db->where('u.group_id', 5);
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
								   "review_count" => $review_count ? $review_count : 0,
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
   
public function agentDetails($id = NULL){

    if(empty($id)){

       return false;

       }

  
  $this->db->select('u.*,ad.agency_id,ad.agency_email,ad.agency_name,ad.agency_about_us,ad.agency_logo,ad.agency_phone_number,ad.agency_cell_number,ad.agency_address,ad.agency_website,ad.agency_country,ad.agency_state,ad.agency_city,adu.agency_address as professional_address,adu.agency_phone_number as professional_phone_number,adu.agency_cell_number as professional_cell_number,adu.agency_name as professional_agency_name,adu.agency_establish as professional_member_since');

  $this->db->from('user u');
  $this->db->join('agency_detail adu','adu.user_id = u.user_id', 'LEFT');
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


function ActivePropertyCount(){
    $this->db->select('p.property_id, p.video_url,p.property_name, p.property_description, p.prices as property_price, p.address as property_address, p.property_sqft, p.latitude, p.longitude, pc.categoryName as property_category_name, u.user_id as agent_id, u.firstName as agent_name,u.	phone_number,u.address,ad.agency_logo, ad.agency_name,ad.agency_id,pimg.image_name,
  (SELECT COUNT(pimgc.property_id) from property_image pimgc where pimgc.property_id = p.property_id  GROUP BY pimgc.property_id) as property_image_count, 

  (SELECT COUNT(upv.property_id) from user_property_views upv where upv.property_id = p.property_id  GROUP BY upv.property_id) as property_views_count, 

 

  (SELECT COUNT(upf.property_id) from user_property_favorites upf where upf.property_id = p.property_id GROUP BY upf.property_id ) as property_favorites_count, pnba.property_key, mnb.nearby_name, mnb.nearby_logo', false);

  

	$this->db->from('property p');
	$this->db->join('property_category pc', 'p.property_category = pc.property_category_id', 'LEFT');
	$this->db->join('property_image pimg', 'p.property_id = pimg.property_id', 'LEFT');
	$this->db->join('user u', 'p.user_id = u.user_id', 'LEFT');

    $this->db->join('user_associated_agency uaa', 'uaa.user_id = u.user_id', 'LEFT'); 
    $this->db->join('agency_detail ad', 'ad.agency_id = uaa.agency_id', 'LEFT'); 
  	$this->db->join('property_nearby_address pnba', 'pnba.property_id = p.property_id ', 'LEFT'); 
    $this->db->join('manage_nearby mnb', 'mnb.nearby_id = pnba.property_key', 'LEFT'); 

	$this->db->where('p.status', 'Active');

	$this->db->group_by("p.property_id");

	$this->db->order_by("p.property_id", "desc");


	$query = $this->db->get();

	$total_records = $query->num_rows();	

	if($total_records > 0){
       return $query->num_rows();
	}else{ 

	   return false;

    }   
}



function ActiveProperty($limit, $offset){
  
	 $this->db->select('p.property_id, p.video_url,p.property_name, p.property_description, p.prices as property_price, p.address as property_address, p.property_sqft, p.latitude, p.longitude, pc.categoryName as property_category_name, u.user_id as agent_id, u.firstName as agent_name,
	 
	 ad.agency_logo, ad.agency_name,ad.agency_id,
	 
	  pimg.image_name,p.bedrooms, 

  (SELECT COUNT(pimgc.property_id) from property_image pimgc where pimgc.property_id = p.property_id  GROUP BY pimgc.property_id) as property_image_count, 

  (SELECT COUNT(upv.property_id) from user_property_views upv where upv.property_id = p.property_id  GROUP BY upv.property_id) as property_views_count, 

 

  (SELECT COUNT(upf.property_id) from user_property_favorites upf where upf.property_id = p.property_id GROUP BY upf.property_id ) as property_favorites_count, pnba.property_key, mnb.nearby_name, mnb.nearby_logo', false);

  

	$this->db->from('property p');
	$this->db->join('property_category pc', 'p.property_category = pc.property_category_id', 'LEFT');
	$this->db->join('property_image pimg', 'p.property_id = pimg.property_id', 'LEFT');
	$this->db->join('user u', 'p.user_id = u.user_id', 'LEFT');
   
    $this->db->join('user_associated_agency uaa', 'uaa.user_id = u.user_id', 'LEFT'); 
    $this->db->join('agency_detail ad', 'ad.agency_id = uaa.agency_id', 'LEFT'); 
	
	/*$this->db->join('user_profile aup', 'u.user_id = aup.user_id', 'LEFT'); 
	$this->db->join('user_parent_group upg', 'upg.user_id = u.user_id', 'LEFT'); 
	$this->db->join('user_profile up', 'upg.user_parent_id = up.user_id', 'LEFT');	 */

	$this->db->join('property_nearby_address pnba', 'pnba.property_id = p.property_id ', 'LEFT'); 

	$this->db->join('manage_nearby mnb', 'mnb.nearby_id = pnba.property_key', 'LEFT'); 

	$this->db->where('p.status', 'Active');

	$this->db->group_by("p.property_id");

	$this->db->order_by("p.property_id", "desc");

	$this->db->limit($limit, $offset);   

	$query = $this->db->get();

	$total_records = $query->num_rows();	

	if($total_records > 0){

		return $query->result_array();

	   }else{ 

	   return false;

	   }   
}

function getComments($agent_id){

   $this->db->select('u.profile_image,u.firstName,c.*,c.user_id as commented_user,
                      (SELECT COUNT(cl.comment_id) from comment_like cl where c.comment_id = cl.comment_id and likes="1"  GROUP BY cl.comment_id) as likes');
   $this->db->from('comment c');
   $this->db->join('user u', 'c.user_id = u.user_id', 'LEFT'); 
   $this->db->where('c.comment_parent_id=0');
   $this->db->where('c.target_id='. $agent_id);

   $query = $this->db->get();
   if($query->num_rows()>0){

    $result =  $query->result_array();

       return  $result;

    }else{ 

      return false;

    }
}

function getComment($agent_id){

   $this->db->select('u.profile_image,u.firstName,c.*,c.user_id as commented_user,
                      (SELECT COUNT(cl.comment_id) from comment_like cl where c.comment_id = cl.comment_id and likes="1"  GROUP BY cl.comment_id) as likes');
   $this->db->from('comment c');
   $this->db->join('user u', 'u.user_id = c.user_id', 'LEFT'); 
   $this->db->where('c.comment_parent_id=0');
   $this->db->where('c.comment_id='. $agent_id);

   $query = $this->db->get();
   if($query->num_rows()>0){

    $result =  $query->result_array();

       return  $result;

    }else{ 

      return false;

    }
}
	function getCommentsCount($agent_id)
{
	$this->db->select('u.profile_image,u.firstName,c.*,c.user_id as commented_user,
                      (SELECT COUNT(cl.comment_id) from comment_like cl where c.comment_id = cl.comment_id and likes="1"  GROUP BY cl.comment_id) as likes');
   $this->db->from('comment c');
   $this->db->join('user u', 'c.user_id = u.user_id', 'LEFT'); 
   $this->db->where('c.comment_parent_id=0');
   $this->db->where('c.target_id='. $agent_id);

   $query = $this->db->get();
   if(!empty($query))
   {
    $result = $query->num_rows();
	return $result;
   }else{return false;}
}


function getUserComments($agent_id,$limit = '' , $offset = '')
{
 $this->db->select('u.profile_image,u.firstName,c.*,c.user_id as commented_user,
                      (SELECT COUNT(cl.comment_id) from comment_like cl where c.comment_id = cl.comment_id and likes="1"  GROUP BY cl.comment_id) as likes');
   $this->db->from('comment c');
   $this->db->join('user u', 'c.user_id = u.user_id', 'LEFT'); 
   $this->db->where('c.comment_parent_id=0');
   $this->db->where('c.target_id='. $agent_id);
   $this->db->order_by("c.createdDate", "asc");
   $this->db->limit($limit, $offset); 	
   $query = $this->db->get();
   if($query->num_rows()>0){

    $result =  $query->result_array();

       return  $result;

    }else{ 

      return false;

    }
}

}
