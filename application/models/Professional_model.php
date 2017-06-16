<?php
class Professional_model extends CI_Model { 
 function professionalListingCount($post= NULL){ 
	   
	if(empty($post)){
	$post =	$this->session->userdata('professional_search');
	
	}
	
	    $this->db->select('u.user_id,u.firstName,u.lastName,u.profile_image,u.about_us,u.phone_number,u.country,u.address,ug.groupName,ug.description,ad.agency_id,ad.agency_logo,(SELECT COUNT(cmt.user_id) from comment cmt where cmt.user_id = u.user_id  GROUP BY cmt.user_id) as review_count');
	 $this->db->from('user u');
     $this->db->join('user_group ug','ug.group_id = u.group_id', 'LEFT');
	 $this->db->join('agency_detail ad', 'ad.user_id = u.user_id', 'LEFT');
	 
	 $this->db->join('country c', 'c.countryid = u.country', 'LEFT');  // internet
	$this->db->join('country_regions cr', 'cr.regionid = u.region', 'LEFT'); //code
	$this->db->join('country_region_cities crc', 'crc.cityId = u.city', 'LEFT'); //city 
	 
	 
	 
	  
	if(!empty($post) && isset($post) && $post != NULL){
		$post_data = array('professional_search' => $post);
		$this->session->set_userdata($post_data);
	}
	
	  if(!empty($post['pro_type']) && $post['pro_type']!='both'){
	    $this->db->where('u.user_type', trim($post['pro_type']));
	  }
	 if(!empty($post['profession'])){
	  $this->db->where('u.group_id', trim($post['profession']));
	 }else{
	  $this->db->where('u.group_id', 5); 
	 }
	 
	  if(trim($post['country_code'])!=''){
        $this->db->where('c.internet', trim($post['country_code']));
     } 
	  if(trim($post['regions'])!=''){
	    $this->db->where('cr.code', trim($post['regions']));
		//$this->db->like('cr.code', $post['regions'], 'start');
     } 
	 if(trim($post['city'])!=''){
       // $this->db->where('crc.city', $post['city']);
		$this->db->like('crc.city', trim($post['city']), 'start');
     } 
	 if(!empty($post['postal_code'])){
     //   $this->db->where('p.zipcode', $post['postal_code']);
     } 
	 
	 if(trim($post['specialitiesIds'])!=''){
		$this->db->join('user_profession_specialties ups', 'u.user_id = ups.user_id', 'LEFT'); 
		//$this->db->where_in('ups.specialties_id', $post['specialitiesIds']);
		$this->db->where_in('ups.specialties_id', explode(",",trim($post['specialitiesIds'])));
	 }
	 if(trim($post['location'])!=''){
        //$this->db->like('u.address', $post['location'],'start'); 
	 }
	 if(!empty($post['name'])){
        $this->db->like('u.firstName', trim($post['name']),'start'); 
     }
	 $this->db->where('u.status', 'Active');
	 $this->db->group_by("u.user_id");
     $this->db->order_by("u.user_id", "RANDOM");
	  
	   $query = $this->db->get(); 
       if($query->num_rows()>0){
		return count($query->result_array());
	   }else{
		   return false;
		   
		   }
		
   }
   function professionalListing($limit, $offset, $post= NULL,$type = 'array'){
	   
	// $this->output->enable_profiler(TRUE);
	
	if(empty($post)){
	$post =	$this->session->userdata('professional_search');
	}
	
	 $this->db->select('u.user_id,u.firstName,u.lastName,u.profile_image,u.about_us,u.phone_number,u.country,u.address,ug.groupName,ug.description,ad.agency_id,ad.agency_logo,(SELECT COUNT(cmt.user_id) from comment cmt where cmt.user_id = u.user_id  GROUP BY cmt.user_id) as review_count');
	 $this->db->from('user u');
     $this->db->join('user_group ug','ug.group_id = u.group_id', 'LEFT');
	 $this->db->join('agency_detail ad', 'ad.user_id = u.user_id', 'LEFT'); 
	 $this->db->join('country c', 'c.countryid = u.country', 'LEFT');  // internet
	$this->db->join('country_regions cr', 'cr.regionid = u.region', 'LEFT'); //code
	$this->db->join('country_region_cities crc', 'crc.cityId = u.city', 'LEFT'); //city 
	 
	 if(!empty($post) && isset($post) && $post != NULL){
		$post_data = array('professional_search' => $post);
		$this->session->set_userdata($post_data);
	}
	
	 if(!empty($post['pro_type']) && $post['pro_type']!='both'){
	    $this->db->where('u.user_type', trim($post['pro_type']));
	  }
	 if(!empty($post['profession'])){
	  $this->db->where('u.group_id', trim($post['profession']));
	 }else{
	  $this->db->where('u.group_id', 5); 
	 }
	 
	
	 if(trim($post['country_code'])!=''){
        $this->db->where('c.internet', trim($post['country_code']));
     } 
	  if(trim($post['regions'])!=''){
        $this->db->where('cr.code', trim($post['regions']));
		//$this->db->like('cr.code', $post['regions'], 'start');
     } 
	 if(trim($post['city'])!=''){
       // $this->db->where('crc.city', $post['city']);
		$this->db->like('crc.city', trim($post['city']), 'start');
     } 
	 if(!empty($post['postal_code'])){
     //   $this->db->where('p.zipcode', $post['postal_code']);
     } 
	 
	  if(!empty($post['specialitiesIds'])){
		/*$this->db->join('user_group_specialities ugs', 'u.group_id = ugs.group_id', 'LEFT'); 
		$this->db->where_in('ugs.speciality_id', $post['specialitiesIds']);		*/
		
		$this->db->join('user_profession_specialties ups', 'u.user_id = ups.user_id', 'LEFT'); 
		$this->db->where_in('ups.specialties_id', explode(",",trim($post['specialitiesIds'])));
     }
		
	
	 if(trim($post['location'])!=''){
        //$this->db->like('u.address', $post['location'],'start'); 
	 }
	 if(!empty($post['name'])){
        $this->db->like('u.firstName', trim($post['name']),'start'); 
     }
	 $this->db->where('u.status', 'Active');
   /* if(!empty($post)){
	 foreach($post as $key => $val){
	  if(!empty($val)){
		$this->session->set_userdata($key, $val);  
	  }else{
		$this->session->unset_userdata($key, '');   
	  }
	 }
	}*/
	   $this->db->group_by("u.user_id");
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
			    
				  $user_id = $val['user_id'];	
				  $agency_id = $val['agency_id'];	
				$manage[] = array("user_id" => $user_id,
								   "firstName" => ucwords($val['firstName']),
								   "lastName"=>ucfirst($val['lastName']),
	                               "groupName" => ucwords($val['groupName']),
			  					 "description" => ucwords($val['description']),
								   "profile_image" => $profile_image,
								   "about_us" => $val['about_us'],
								   "phone_number" => $val['phone_number'] ? $val['phone_number'] :  '**********',
								   "address" => $val['address'],
								   "agency_logo" => $agency_logo,
								   "agency_id" => $agency_id,
								   "country"=>$val['country']
								   ); 
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
  $this->db->select('u.*,ug.groupName,ug.description,ad.agency_id,ad.agency_email,adu.blog_url,ad.agency_name,ad.agency_about_us,
  ad.agency_logo,ad.agency_phone_number,ad.agency_cell_number,ad.agency_address,ad.agency_country,
  ad.agency_state,ad.agency_city,adu.agency_address as professional_address,adu.agency_phone_number as professional_phone_number,
  adu.agency_cell_number as professional_cell_number,adu.agency_name as professional_agency_name, adu.agency_fax as professional_agency_fax,
  adu.agency_website as professional_agency_website, adu.agency_establish as professional_member_since,
  
  (SELECT GROUP_CONCAT(specialties_id SEPARATOR ", ")
   FROM user_profession_specialties WHERE find_in_set(user_id, u.user_id)) as profession_specialties_id', false);
/*
  (SELECT GROUP_CONCAT(profession_specialties_id SEPARATOR ", ")
   FROM user_profession_specialties WHERE find_in_set(user_id, u.user_id)) as profession_specialties_id
*/
  $this->db->from('user u');
  $this->db->join('agency_detail adu','adu.user_id = u.user_id', 'LEFT');
  
  $this->db->join('user_group ug','ug.group_id = u.group_id', 'LEFT');
  
  $this->db->join('user_associated_agency uaa', 'uaa.user_id = u.user_id', 'LEFT'); 
  $this->db->join('agency_detail ad', 'ad.agency_id = uaa.agency_id', 'LEFT'); 
  $this->db->where('u.status', 'Active');
  $this->db->where('u.user_id', $id);
 $this->db->group_by("u.user_id");
  $query = $this->db->get();
       if($query->num_rows()>0){
  $result =  $query->result_array();
       return  $result;
    }else{ 
      return false;
    }
   }
function ActivePropertyCount($user_id,$property_catagory=''){
    $this->db->select('p.property_id, p.video_url,p.property_name, p.property_description, p.prices as property_price, p.address as property_address, p.property_sqft,p.bathrooms,p.bedrooms, p.latitude, p.longitude, pc.categoryName as property_category_name, u.user_id as agent_id, u.firstName as agent_name,u.	phone_number,u.address,ad.agency_logo, ad.agency_name,ad.agency_id,pimg.image_name,
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
    $this->db->where('p.user_id', $user_id); 
	
	if(!empty($property_catagory)){
	 
	 if($property_catagory=='Sale'){
	   $this->db->where('p.property_category', '1'); 
	 }elseif($property_catagory=='Rent'){
	   $this->db->where('p.property_category', '2'); 
	 }
	}
	
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
function ActiveProperty($limit, $offset,$user_id,$property_catagory=''){
  
	 $this->db->select('p.bathrooms,pty.typeName,p.property_id, p.video_url,p.property_name, p.property_description, p.prices as property_price, p.address as property_address, p.property_sqft, p.latitude, p.longitude, pc.categoryName as property_category_name, u.user_id as agent_id, u.firstName as agent_name,
	 
	 ad.agency_logo, ad.agency_name,ad.agency_id,
	 
	  pimg.image_name,p.bedrooms, 
  (SELECT COUNT(pimgc.property_id) from property_image pimgc where pimgc.property_id = p.property_id  GROUP BY pimgc.property_id) as property_image_count, 
  (SELECT COUNT(upv.property_id) from user_property_views upv where upv.property_id = p.property_id  GROUP BY upv.property_id) as property_views_count, 
 
  (SELECT COUNT(upf.property_id) from user_property_favorites upf where upf.property_id = p.property_id GROUP BY upf.property_id ) as property_favorites_count, pnba.property_key, mnb.nearby_name, mnb.nearby_logo', false);
  
	$this->db->from('property p');
	$this->db->join('property_types pty', 'pty.property_types_id = p.property_type', 'LEFT');
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
    $this->db->where('p.user_id', $user_id); 
	
	if(!empty($property_catagory)){
	 
	 if($property_catagory=='Sale'){
	   $this->db->where('p.property_category', '1'); 
	 }elseif($property_catagory=='Rent'){
	   $this->db->where('p.property_category', '2'); 
	 }
	}
	
	
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
/*function getComments($limit, $offset,$agent_id){
   $this->db->select('u.profile_image,c.*,c.user_id as commented_user,(SELECT COUNT(cl.comment_id) from comment_like cl where c.comment_id = cl.comment_id and likes=1  GROUP BY cl.comment_id) as likes, CONCAT(u.firstName, " ",u.lastName) as firstName', FALSE);
   $this->db->from('comment c');
   $this->db->join('user u', 'c.user_id = u.user_id', 'LEFT'); 
   $this->db->where('c.comment_parent_id=0');
   $this->db->where('c.target_id='. $agent_id);
   $this->db->limit($limit, $offset); 
   $this->db->order_by("c.createdDate","desc");
   $query = $this->db->get();
  if($query->num_rows()>0){
    $result =  $query->result_array();
       return  $result;
    }else{ 
      return false;
    }
}*/
function getCommentsCount($agent_id){
	
	if(!empty($agent_id)){
	$query = $this->db->query("select c.*,c.user_id as commented_user, (SELECT COUNT(cl.comment_id) from comment_like cl where c.comment_id = cl.comment_id and likes=1  GROUP BY cl.comment_id) as likes,CONCAT(u.firstName,' ', lastName) as firstName, u.profile_image from (
					  select c.*,
					  coalesce(nullif(c.comment_parent_id, 0), c.comment_id) as groupID,
					  case when c.comment_parent_id = 0 then 1 else 0 end as isparent,
					  case when p.comment_parent_id = 0 then c.comment_parent_id end as orderbyint
					  from comment c
					  left join comment p on p.comment_id = c.comment_parent_id
					) c left join user u on u.user_id = c.user_id WHERE c.target_id = '".$agent_id."' and c.status='Active' and u.status='Active' order by groupID, isparent desc, orderbyint");
					
					
					
					  
					if($query->num_rows()>0){
					$result =  $query->result_array();
					   return  count($result);
					}else{ 
					  return false;
					}
	
					
			
			
		}else{
			return false;
		}
	
	}
	
function getComments($limit, $offset,$agent_id){
	$offset = $offset ? $offset : 0;
	if(!empty($agent_id)){
	$query = $this->db->query("select c.*,c.user_id as commented_user, (SELECT COUNT(cl.comment_id) from comment_like cl where c.comment_id = cl.comment_id and likes=1  GROUP BY cl.comment_id) as likes,CONCAT(u.firstName,' ', lastName) as firstName, u.profile_image from (
					  select c.*,
					  coalesce(nullif(c.comment_parent_id, 0), c.comment_id) as groupID,
					  case when c.comment_parent_id = 0 then 1 else 0 end as isparent,
					  case when p.comment_parent_id = 0 then c.comment_parent_id end as orderbyint
					  from comment c
					  left join comment p on p.comment_id = c.comment_parent_id
					) c left join user u on u.user_id = c.user_id WHERE c.target_id = '".$agent_id."' and c.status='Active' and u.status='Active' order by groupID, isparent desc, orderbyint Limit ".$offset.",".$limit."");
					
					
					
					  
					if($query->num_rows()>0){
					$result =  $query->result_array();
					   return  $result;
					}else{ 
					  return false;
					}
	
					
			
			
		}else{
			return false;
		}
	
	}
/*function getCommentsCount($agent_id){
   $this->db->select('u.profile_image,c.*,c.user_id as commented_user,(SELECT COUNT(cl.comment_id) from comment_like cl where c.comment_id = cl.comment_id and likes=1  GROUP BY cl.comment_id) as likes, CONCAT(u.firstName, " ",u.lastName) as firstName', FALSE);
   $this->db->from('comment c');
   $this->db->join('user u', 'c.user_id = u.user_id', 'LEFT'); 
   $this->db->where('c.comment_parent_id=0');
   $this->db->where('c.target_id='. $agent_id);
   $this->db->order_by("c.createdDate","desc");
   $query = $this->db->get();
  if($query->num_rows()>0){
    $result =  count($query->result_array());
       return  $result;
    }else{ 
      return false;
    }
}
*/
function getComment($agent_id){
   $this->db->select('u.profile_image,u.firstName,c.*,c.user_id as commented_user,
                      (SELECT COUNT(cl.comment_id) from comment_like cl where c.comment_id = cl.comment_id and likes="1"  GROUP BY cl.comment_id) as likes');
   $this->db->from('comment c');
   $this->db->join('user u', 'c.target_id = u.user_id', 'LEFT'); 
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
function professionale_property($user_id){
	if(empty($user_id)){
	   return false;	
	 }
	$this->db->select('pty.typeName,p.property_id, p.property_name, p.property_description, p.prices as property_price, p.address as property_address, p.property_sqft, p.latitude, p.longitude, pc.categoryName as property_category_name, p.bathrooms,p.bedrooms, u.user_id as agent_id,u.profile_image as agent_logo, u.firstName as agent_name,u.lastName, u.address as agent_company_address, u.phone_number as agent_company_phone_number, u.phone_number as agent_company_cell_number,pimg.image_name,
  (SELECT COUNT(upv.property_id) from user_property_views upv where upv.property_id = p.property_id  GROUP BY upv.property_id) as property_views_count, 
 (SELECT COUNT(pimgcount.property_id) from property_image pimgcount where pimgcount.property_id = p.property_id  GROUP BY pimgcount.property_id) as property_image_count,
  (SELECT COUNT(upf.property_id) from user_property_favorites upf where upf.property_id = p.property_id GROUP BY upf.property_id ) as property_favorites_count, pnba.property_key, mnb.nearby_name, mnb.nearby_logo', false);
$this->db->from('property p');
$this->db->join('property_category pc', 'pc.property_category_id = p.property_category', 'LEFT');
$this->db->join('property_types pty', 'pty.property_types_id = p.property_type', 'LEFT');
$this->db->join('property_image pimg', 'pimg.property_id = p.property_id', 'LEFT');
$this->db->join('user u', 'u.user_id = p.user_id', 'LEFT');
$this->db->join('property_nearby_address pnba', 'pnba.property_id = p.property_id ', 'LEFT'); 
$this->db->join('manage_nearby mnb', 'mnb.nearby_id = pnba.property_key', 'LEFT'); 
	$this->db->where('p.user_id', $user_id); 
	$this->db->group_by("p.property_id");
	$this->db->order_by("p.property_id", "desc");
	 $this->db->where('p.status', 'Active');
	$this->db->limit($limit, $offset);   
	$query = $this->db->get();
	$total_records = $query->num_rows();	
	if($total_records > 0){
		$result =  $query->result_array();
		$url = config_item('base_url');
		foreach($result as $val){
          $session_user_id =  $this->session->userdata('user_id');
	      $favourites=checkPropertyFavourites($session_user_id,$val['property_id']);
		  if($favourites=='1'){
		    $favourites_class='';
		  }elseif($favourites=='0'){
		    $favourites_class='-o';
		  }
$property_image_name = getUserProfileImage($val['image_name'],$url.'applicationMediaFiles/propertiesImage/350325/');
$agency_company_logo = getUserProfileImage($val['agent_logo'],$url.'applicationMediaFiles/usersImage/150150/');
					
					
					$property_id = $val['property_id'];	
					
					
				
					$agency_id = $val['agency_id'];	
                     $property_address   = str_replace("\r\n", "", $val['property_address']);
					$property_address   = str_replace("\r", "", $property_address);
					$property_address   = str_replace("\n", "", $property_address);
			   $bedrooms = $val['bedrooms'];
			   $property_type_name = $val['typeName'];
			   $category_name = $val['property_category_name'];
			   $seo_url_string = ucwords($bedrooms.'  Bed '. $property_type_name.'  For '. $category_name);
			   $property_seo_url = seo_friendly_urls($seo_url_string,'',$val['property_id']);
					
 $user_seo_url = seo_friendly_urls($val['agent_name'],$val['lastName'],$val['agent_id']);
                    $manage[] = array("property_id" => $val['property_id'],
					                  "property_id_encode" => $property_id ,
                                       "property_seo_url" => $property_seo_url ,
									  "bathrooms" => $val['bathrooms'],
									  "bedrooms" => $val['bedrooms'],
									  "property_category_name" => $val['property_category_name'],
									  "typeName" => $val['typeName'],
			                          "property_name" => $val['property_name'],
							    	  "property_price" => attachCurrencySymbol(convert_currency($val['property_price'])),
									  "agency_id" => $user_seo_url,
									  "agency_company_logo" => $agency_company_logo,
									  "property_address" => stripslashes(htmlentities($property_address)),
									  "property_sqft" => $val['property_sqft'],
									  "latitude" => $val['latitude'],
									  "longitude" => $val['longitude'],
                                      "property_image_name" => $property_image_name,
									  "property_favorites_count" => $val['property_favorites_count'] ? $val['property_favorites_count'] : 0,
									  "property_image_count" => $val['property_image_count'] ? $val['property_image_count'] : 0,
                                      "agent_name" => $val['agent_name'],
									  "agent_company_address" => $val['agent_company_address'],
									  "agent_company_phone_number" => $val['agent_company_phone_number'],
									  "favourites_property"=>$favourites_class
									  ); 
			//$manage[] = array("property_id" => $val["property_id"]);
									  
			}
			
              $manage = str_replace("\r",'', $manage);
			  $manage = str_replace("\n",'', $manage);
              $manage = str_replace("\r\n",'', $manage);
			return  json_encode($manage); //JSON_UNESCAPED_SLASHES
			
	   }else{ 
	   return false;
	   }
	
	}
function professional_user_info($id){
 $this->db->select('p.property_id,u.user_id as userId,u.profile_image as user_image,u.address as user_address,
 u.firstName as user_fname,u.lastName as user_lname,u.user_type as user_type,ad.agency_address,ad.agency_name,ad.agency_logo', false);
 
 $this->db->from('property p');
 $this->db->join('user u', 'u.user_id = p.user_id', 'LEFT');
 $this->db->join('user_associated_agency uaa', 'uaa.user_id = u.user_id', 'LEFT'); 
// $this->db->join('agency_detail ad', 'ad.agency_id = uaa.agency_id', 'LEFT'); 
  $this->db->join('agency_detail ad', 'ad.user_id = u.user_id', 'LEFT');
 $this->db->where('u.status', 'Active');
 $this->db->where('p.property_id',$id);
 $query = $this->db->get();
	if($query->num_rows()>0){
	  $result =  $query->result_array();
	  foreach($result as $res){
		 if($res['user_type']=='individual'){
			
			$url=base_url('applicationMediaFiles/usersImage/150150/');
			$image_name = getUserProfileImage($res['user_image'],$url);
			
			
			
			$manage[] = array("image_name" => $url.$image_name,
							  "username"=>$res['user_fname'].'&nbsp;'.$res['user_lname'],
							  "address"=>$res['user_address'],
							  "user_id"=>$res['userId'],
							  "property_id"=>$res['property_id']
							  ); 
		 
		 }elseif($res['user_type']=='team'){
		 
		 
		   $url=base_url('applicationMediaFiles/companyImage/9999/');
		   $image_name = getUserProfileImage($res['agency_logo'],$url);
		 
		   $manage[] = array("image_name" => $url.$image_name,
							  "username"=>$res['agency_name'],
							  "address"=>$res['agency_address'],
							  "user_id"=>$res['userId'],
							  "property_id"=>$res['property_id']
							  ); 
		 
		 }
	  }
	}	
   return $manage;
		
 }
 function comment_report($id){
 $this->db->select("cr.*,cp.problem_desc,CONCAT(u.firstName, ' ',  u.lastName) as username", false);
 $this->db->from('comment_report cr');
 $this->db->join('comment_problem cp', 'cp.problem_id = cr.comment_probleam_id', 'LEFT');
 $this->db->join('user u', 'u.user_id = cr.user_id', 'LEFT');
 $this->db->where('cr.commnet_id',$id);
 $query = $this->db->get();
	if($query->num_rows()>0){
	  $result =  $query->result_array();
	  
	  return $result;
	  
	}  
	  
 }
}
