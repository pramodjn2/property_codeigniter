<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Property_listing_model extends CI_Model {
	
public function getPropertyListingCount($post= NULL){
	//$this->output->enable_profiler(TRUE);	
	if(empty($post)){
	$post =	$this->session->userdata('property_search');
	
	}
	
	$post_data = array('property_search' => $post);
	$this->session->set_userdata($post_data);
		
		
	$latitude =  $post['latitude'] ? $post['latitude'] : $this->session->userdata('latitude');
	$longitude = $post['longitude'] ? $post['longitude'] : $this->session->userdata('longitude');
	
$country = $post['country_code'] ? $post['country_code'] : $this->session->userdata('country_internet');
$regions = trim($post['regions']);
$city = trim($post['city']);
/*$postal_code = $post['postal_code'] ? $post['postal_code'] : $this->session->userdata('postal_code');
$longitude = $post['longitude'] ? $post['longitude'] : $this->session->userdata('longitude');
$longitude = $post['longitude'] ? $post['longitude'] : $this->session->userdata('longitude');
$longitude = $post['longitude'] ? $post['longitude'] : $this->session->userdata('longitude');
$near_by = $post['near_by'] ? $post['near_by'] : $this->session->userdata('near_by');*/
	
	$this->db->select('p.property_id,p.property_closing_date, p.property_name, p.property_description, p.prices as property_price, p.address as property_address, p.property_sqft, p.latitude, p.longitude, pc.categoryName as property_category_name, u.user_id as agent_id, u.firstName as agent_name,u.address as agent_company_address, u.phone_number as agent_company_phone_number, u.phone_number as agent_company_cell_number, ad.agency_logo as agency_company_logo, ad.agency_name as agency_company_name,pimg.image_name,(SELECT COUNT(upv.property_id) from user_property_views upv where upv.property_id = p.property_id  GROUP BY upv.property_id) as property_views_count,(SELECT COUNT(upf.property_id) from user_property_favorites upf where upf.property_id = p.property_id GROUP BY upf.property_id ) as property_favorites_count, pnba.property_key, mnb.nearby_name, mnb.nearby_logo', false);
	
	/*if(!empty($post) && isset($post) && $post != NULL){
		if(!empty($latitude) && !empty($longitude)){  
			$this->db->select('( 3959 * acos( cos( radians("'.$latitude.'") ) * cos( radians(p.latitude) ) * cos( radians(p.longitude) - radians("'.$longitude.'") ) + sin( radians("'.$latitude.'") ) * sin( radians(p.latitude)))) AS distance', false);
		} 
	}*/
	$this->db->from('property p');
	$this->db->join('property_category pc', 'pc.property_category_id = p.property_category', 'LEFT');
	$this->db->join('property_image pimg', 'pimg.property_id = p.property_id', 'LEFT');
	$this->db->join('user u', 'u.user_id = p.user_id', 'LEFT');
	$this->db->join('user_associated_agency uaa', 'uaa.user_id = u.user_id', 'LEFT'); 
    $this->db->join('agency_detail ad', 'ad.agency_id = uaa.agency_id', 'LEFT'); 
	$this->db->join('property_nearby_address pnba', 'pnba.property_id = p.property_id ', 'LEFT'); 
	$this->db->join('manage_nearby mnb', 'mnb.nearby_id = pnba.property_key', 'LEFT');
	
	 $this->db->join('property_types pts', 'pts.property_types_id = p.property_type', 'LEFT');
	 $this->db->join('manage_nearby mn', 'mn.nearby_id = pnba.property_key', 'LEFT');
	
	$this->db->join('country c', 'c.countryid = p.country', 'LEFT');  // internet
	$this->db->join('country_regions cr', 'cr.regionid = p.state', 'LEFT'); //code
	$this->db->join('country_region_cities crc', 'crc.cityId = p.city', 'LEFT'); //city
	
	$this->db->join('property_auction pa', 'pa.property_id = p.property_id','LEFT');
    //$this->db->where('p.property_id != pa.property_id');
	
	if(trim($country) != ''){
			$this->db->where('c.internet', trim($country));
		} 
	if(trim($regions) != ''){
		  // $this->db->where('cr.code', $regions);
		  // $this->db->or_where('cr.region', $regions);
		  
		  $where = "(cr.code='$regions' OR cr.region='$regions')";
		  $this->db->where($where);
		  
		   //$this->db->like('cr.code', $regions, 'start');
		} 
	if(trim($city) != ''){
		$this->db->like('crc.city', $city, 'start');
	} 
		
	
	
	if(!empty($post) && isset($post) && $post != NULL){
		
		
		/*foreach($post as $key => $val){
			if(!empty($val)){
				$this->session->set_userdata($key, $val);  
			}else{
				$this->session->unset_userdata($key, '');   
			}
		}*/
		if(!empty($post['min_price'])){
			$min_price = $post['min_price'];
			$this->db->where('p.prices >=', ''.$min_price.''); 
		}
		if(!empty($post['max_price'])){
			$max_price = $post['max_price'];
			$this->db->where('p.prices <=', ''.$max_price.''); 
		}
		if(!empty($post['proprty_category'])){
			$this->db->where('pc.categoryName', $post['proprty_category']); 
		}	 
		if(!empty($post['property_type'])){
			$this->db->where('p.property_type', $post['property_type']); 
		}
		if(!empty($post['bed_room'])){
			$this->db->where('p.bedrooms >=', $post['bed_room']); 
		}
		if(!empty($post['near_by'])){
			$this->db->where('pnba.property_key', $post['near_by']);
		}		
		
		
		if(!empty($post['postal_code'])){
			//$this->db->where('p.zipcode', $post['postal_code']);
		}		
		/*if(!empty($latitude) && !empty($longitude)){
			$this->db->where('( 3959 * acos( cos( radians("'.$latitude.'") ) * cos( radians(p.latitude)) * cos( radians( p.longitude) - radians("'.$longitude.'"))+sin(radians("'.$latitude.'"))*sin(radians(p.latitude)))) < ', 500); 
		}else if(!empty($post['location'])){
			$this->db->like('p.address', $post['location'], 'start');
		}*/		
		if(!empty($post['features_name'])){		
			$this->db->join('property_features pfs', 'pfs.property_id = p.property_id ', 'LEFT'); 
			$this->db->where_in('pfs.features_id', $post['features_name']);
		}
	}
	
    $this->db->where('p.status', 'Active');
	$this->db->where('p.property_availability', 'available');
	
	$this->db->where('pc.status', 'Active');
	$this->db->where('pts.status', 'Active');
	//$this->db->where('mn.status', 'Active');
 
	$date=date('m/d/Y');
	
	$wh = "STR_TO_DATE(p.property_closing_date, '%m/%d/%Y') >= STR_TO_DATE('$date', '%m/%d/%Y')";
	$this->db->where($wh); 
	
	
   // $this->db->where('p.property_closing_date >= "'.$date.'"');
	$this->db->where("p.auction_status", "0");
	$this->db->group_by("p.property_id");
	$this->db->order_by("p.property_id", "desc");
	$query = $this->db->get();
	//echo $str = $this->db->last_query();
	if($query->num_rows()>0){
		$result =  count($query->result_array());
		return  $result;
	}else{ 
	   return false;
	}
}
 
public function getPropertyListing($limit, $offset, $post= NULL, $type = 'json', $no_limit= NULL){
	//$this->output->enable_profiler(TRUE);
	
	if(empty($post)){
	$post =	$this->session->userdata('property_search');
	}
	$post_data = array('property_search' => $post);
		$this->session->set_userdata($post_data);
	
	$latitude =  $post['latitude'] ? $post['latitude'] : $this->session->userdata('latitude');
	$longitude = $post['longitude'] ? $post['longitude'] : $this->session->userdata('longitude');
	$country = $post['country_code'] ? $post['country_code'] : $this->session->userdata('country_internet');
	$regions = trim($post['regions']);
    $city = trim($post['city']);
	
	$this->db->select('pty.typeName,p.property_id,p.property_closing_date, p.property_name, p.property_description, p.prices as property_price, p.address as property_address, p.property_sqft, p.latitude, p.longitude, p.auction_status, pc.categoryName as property_category_name, p.bathrooms,p.bedrooms, u.user_id as agent_id,u.profile_image, u.firstName as agent_name,u.lastName, u.address as agent_company_address, u.phone_number as agent_company_phone_number, u.phone_number as agent_company_cell_number,ad.agency_id, ad.agency_logo as agency_company_logo, ad.agency_name as agency_company_name, pimg.image_name,(SELECT COUNT(upv.property_id) from user_property_views upv where upv.property_id = p.property_id  GROUP BY upv.property_id) as property_views_count,(SELECT COUNT(pimgcount.property_id) from property_image pimgcount where pimgcount.property_id = p.property_id  GROUP BY pimgcount.property_id) as property_image_count,
	(SELECT COUNT(upf.property_id) from user_property_favorites upf where upf.property_id = p.property_id GROUP BY upf.property_id ) as property_favorites_count, pnba.property_key, mnb.nearby_name, mnb.nearby_logo', false);
	/*if(!empty($post) && isset($post) && $post != NULL){
		if(!empty($latitude) && !empty($longitude)){ 
			$this->db->select('( 3959 * acos( cos( radians("'.$latitude.'") ) * cos( radians(p.latitude) ) * cos( radians(p.longitude) - radians("'.$longitude.'") ) + sin( radians("'.$latitude.'") ) * sin( radians(p.latitude)))) AS distance', false);
		}
	}*/
	$this->db->from('property p');
	$this->db->join('property_category pc', 'pc.property_category_id = p.property_category', 'LEFT');
	$this->db->join('property_types pty', 'pty.property_types_id = p.property_type', 'LEFT');
	$this->db->join('property_image pimg', 'pimg.property_id = p.property_id', 'LEFT');
	$this->db->join('user u', 'u.user_id = p.user_id', 'LEFT');
	$this->db->join('user_associated_agency uaa', 'uaa.user_id = u.user_id', 'LEFT'); 
    $this->db->join('agency_detail ad', 'ad.agency_id = uaa.agency_id', 'LEFT'); 
	$this->db->join('property_nearby_address pnba', 'pnba.property_id = p.property_id ', 'LEFT'); 
	$this->db->join('manage_nearby mnb', 'mnb.nearby_id = pnba.property_key', 'LEFT');
	
	$this->db->join('property_types pts', 'pts.property_types_id = p.property_type', 'LEFT');
	$this->db->join('manage_nearby mn', 'mn.nearby_id = pnba.property_key', 'LEFT'); 
	
	$this->db->join('country c', 'c.countryid = p.country', 'LEFT');  // internet
	$this->db->join('country_regions cr', 'cr.regionid = p.state', 'LEFT'); //code
	$this->db->join('country_region_cities crc', 'crc.cityId = p.city', 'LEFT'); //city
	
	$this->db->join('property_auction pa', 'pa.property_id = p.property_id','LEFT');
    //$this->db->where('p.property_id != pa.property_id');
	
	if(trim($country) != ''){
			$this->db->where('c.internet', trim($country));
		} 
	if(trim($regions) != ''){
		  // $this->db->where('cr.code', $regions);
		   
		   $where = "(cr.code='$regions' OR cr.region='$regions')";
		  $this->db->where($where);
		  
			//  $this->db->or_where('cr.code', $regions);
		   //$this->db->or_where('cr.region', $regions);
			//$this->db->like('cr.code', $regions, 'start');
		} 
	if(trim($city) != ''){
		$this->db->like('crc.city', $city, 'start');
		
	} 
		
	if(!empty($post) && isset($post) && $post != NULL){
		
		//$post_data = array('property_search' => $post);
		//$this->session->set_userdata($post_data);
		/*foreach($post as $key => $val){
			if(!empty($val)){
				$this->session->set_userdata($key, $val);  
			}else{
				$this->session->unset_userdata($key, '');   
			}
		}*/
		if(!empty($post['min_price'])){
			$this->db->where('p.prices >=', $post['min_price']); 
		}
		if(!empty($post['max_price'])){
			$this->db->where('p.prices <=', $post['max_price']); 
		}
		if(!empty($post['proprty_category'])){
			$this->db->where('pc.categoryName', $post['proprty_category']); 
		}	 
		if(!empty($post['property_type'])){
			$this->db->where('p.property_type', $post['property_type']); 
		}
		if(!empty($post['bed_room'])){
			$this->db->where('p.bedrooms >=', $post['bed_room']); 
		}
		if(!empty($post['near_by'])){
			$this->db->where('pnba.property_key', $post['near_by']);
		}		
		
		
		if(!empty($post['postal_code'])){
		//	$this->db->where('p.zipcode', $post['postal_code']);
		}		
		/*if(!empty($latitude) && !empty($longitude)){
			$this->db->where('( 3959 * acos( cos( radians("'.$latitude.'") ) * cos( radians(p.latitude)) * cos( radians( p.longitude) - radians("'.$longitude.'"))+sin(radians("'.$latitude.'"))*sin(radians(p.latitude)))) < ', 500); 
		}else if(!empty($post['location'])){
			$this->db->like('p.address', $post['location'], 'start');
		}*/		
		if(!empty($post['features_name'])){		
			$this->db->join('property_features pfs', 'pfs.property_id = p.property_id ', 'LEFT'); 
			$this->db->where_in('pfs.features_id', $post['features_name']);
		}
	}
		
	$this->db->where('p.status', 'Active');
	$this->db->where('p.property_availability', 'available');
	$this->db->where('pc.status', 'Active');
	$this->db->where('pts.status', 'Active');
	//$this->db->where('mn.status', 'Active');
	
	
	$date=date('m/d/Y');
	
	$wh = "STR_TO_DATE(p.property_closing_date, '%m/%d/%Y') >= STR_TO_DATE('$date', '%m/%d/%Y')";
	$this->db->where($wh);
	
	
	//$this->db->where('p.property_closing_date >= "'.$date.'"');
	$this->db->where("p.auction_status", "0");
	$this->db->group_by("p.property_id");
	$this->db->order_by("p.property_id", "desc");
	if(empty($no_limit)){
		$this->db->limit($limit, $offset);   
	}
	$query = $this->db->get();
	//echo $str = $this->db->last_query();
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
			$slug = seo_friendly_urls($val['agent_name'],$val['lastName'],$val['agent_id']);
			$property_image_name = getUserProfileImage($val['image_name'],$url.'applicationMediaFiles/propertiesImage/350325/');
			$agency_company_logo = getUserProfileImage($val['agency_company_logo'],$url.'applicationMediaFiles/companyImage/9999/');
			
			$profile_image = getUserProfileImage($val['profile_image'],$url.'applicationMediaFiles/usersImage/150150/');
			
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
			
			$manage[] = array("property_id" => $val['property_id'],
							  "property_id_encode" => $property_id ,
							  "property_seo_url" => $property_seo_url ,
							  "bathrooms" => $val['bathrooms'],
							  "bedrooms" => $val['bedrooms'],
							  "property_category_name" => $val['property_category_name'],
							  "typeName" => $val['typeName'],
							  "property_name" => $val['property_name'],
							  "property_price" => attachCurrencySymbol(convert_currency($val['property_price'])),
							  "agency_id" => $agency_id,
							  "agency_company_logo" => $agency_company_logo,
							  "property_address" => replace_strings(stripslashes(htmlentities($property_address))),
							  "property_sqft" => $val['property_sqft'],
							  "latitude" => $val['latitude'],
							  "longitude" => $val['longitude'],
							  "property_image_name" => $property_image_name,
							  "property_favorites_count" => $val['property_favorites_count'] ? $val['property_favorites_count'] : 0,
							  "property_image_count" => $val['property_image_count'] ? $val['property_image_count'] : 0,
							  "agent_name" => $val['agent_name'],
							  "agent_company_address" => $val['agent_company_address'],
							  "agent_company_phone_number" => $val['agent_company_phone_number'],
							  "favourites_property"=>$favourites_class,
							  "agent_id"=>$slug,
							  "auction_status"=>$val['auction_status'],
							  "profile_image"=>$profile_image
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
 
	
function recently_seen(){
	
	$client_ip = $_SERVER['REMOTE_ADDR'];
	$this->db->select("upv.property_id, upv.createdDate, p.min_price, p.prices, p.address, p.bedrooms, p.bathrooms,p.auction_status,p.property_sqft, pc.categoryName, pt.typeName, CONCAT(u.firstName,' ',u.lastName) as agentName, ug.groupName, (SELECT pimg.image_name FROM property_image pimg WHERE pimg.property_id = p.property_id GROUP BY pimg.property_id) as image_name,
	
	(SELECT COUNT(pimgcount.property_id) from property_image pimgcount where pimgcount.property_id = p.property_id  GROUP BY pimgcount.property_id) as property_image_count,
	
	(SELECT COUNT(upf.property_id) from user_property_favorites upf where upf.property_id = p.property_id GROUP BY upf.property_id ) as property_favorites_count
	
	", false);
	$this->db->from('user_property_views upv');
	$this->db->join('property p', 'p.property_id = upv.property_id', 'LEFT');
	$this->db->join('property_category pc', 'pc.property_category_id = p.property_category', 'LEFT');
	$this->db->join('property_types pt', 'pt.property_types_id = p.property_type', 'LEFT');	
	//$this->db->join('property_image pimg', 'pimg.property_id = p.property_id', 'LEFT');
    $this->db->join('user u', 'u.user_id = p.user_id', 'LEFT');
	$this->db->join('user_group ug', 'ug.group_id = u.group_id', 'LEFT');
	
	$this->db->where('upv.ipaddress', $client_ip);
	$this->db->where('date(upv.createdDate)', date('Y-m-d'));
	$this->db->order_by('upv.createdDate', 'DESC');	
	$this->db->group_by("p.property_id");
	$result = $this->db->get();
	$resultRecords = $result->num_rows();
	if($resultRecords > 0){
		$data = array();
		foreach($result->result_array() as $resultData){
		
		   $seo_url_string = ucwords($resultData['bedrooms'].'  Bed '. $resultData['typeName'].'  For '. $resultData['categoryName']);
		   $property_seo_url = seo_friendly_urls($seo_url_string,'',$resultData['property_id']);
		
		
			$image = array('image_name' => displayImage($resultData['image_name'], PROPERTY_IMAGE_THUMB),
							'createdDate' => get_time_ago($resultData['createdDate']),
							'min_price' => attachCurrencySymbol(convert_currency($resultData['prices'])),
							'address'=>replace_strings($resultData['address']),
							'bedrooms'=>$resultData['bedrooms']?$resultData['bedrooms']:0,
							'bathrooms'=>$resultData['bathrooms']?$resultData['bathrooms']:0,
							'property_sqft'=>$resultData['property_sqft']?$resultData['property_sqft']:0,
							'property_seo_url'=>$property_seo_url,
							'property_image_count'=>$resultData['property_image_count']?$resultData['property_image_count']:0,
							'property_favorites_count'=>$resultData['property_favorites_count']?$resultData['property_favorites_count']:0,
							'auction_status'=>$resultData['auction_status']
							
							);
			//$date = array('createdDate' => get_time_ago($resultData['createdDate']));
			
							
			$data[] = array_replace($resultData, $image);		
		}
	}
	
	$data = array('total' => $resultRecords, 'data' => $data);
	
	return $data;
  }	
  
public function getAuctionPropertyListingCount($post= NULL){
	//$this->output->enable_profiler(TRUE);	
	if(empty($post)){
	$post =	$this->session->userdata('property_search');
	
	}
	
	$post_data = array('property_search' => $post);
	$this->session->set_userdata($post_data);
	
	
	//$this->output->enable_profiler(TRUE);	
   $latitude =  $post['latitude'] ? $post['latitude'] : $this->session->userdata('latitude');
	$longitude = $post['longitude'] ? $post['longitude'] : $this->session->userdata('longitude');
	$country = $post['country_code'] ? $post['country_code'] : $this->session->userdata('country_internet');
	$regions = trim($post['regions']);
    $city = trim($post['city']);
	
	
  $this->db->select('p.property_id,p.property_closing_date, p.property_name, p.property_description, p.prices as property_price, p.address as property_address, p.property_sqft, p.latitude, p.longitude, pc.categoryName as property_category_name, u.user_id as agent_id, u.firstName as agent_name,u.lastName, 
  u.address as agent_company_address, u.phone_number as agent_company_phone_number, u.phone_number as agent_company_cell_number, ad.agency_logo as agency_company_logo, ad.agency_name as agency_company_name,pimg.image_name, 
  (SELECT COUNT(upv.property_id) from user_property_views upv where upv.property_id = p.property_id  GROUP BY upv.property_id) as property_views_count, 
  (SELECT COUNT(upf.property_id) from user_property_favorites upf where upf.property_id = p.property_id GROUP BY upf.property_id ) as property_favorites_count, pnba.property_key, mnb.nearby_name, mnb.nearby_logo,
  pa.start_date as auction_start, pa.end_date as auction_end, (Select Max(price) from user_set_auction_price usap where usap.property_id = p.property_id) as auction_highestbid', false);
if(!empty($post) && isset($post) && $post != NULL){
  if(!empty($post['search_type'])){  
   $this->db->select('( 3959 * acos( cos( radians("'.$latitude.'") ) * cos( radians(p.latitude) ) * cos( radians(p.longitude) - radians("'.$longitude.'") ) + sin( radians("'.$latitude.'") ) * sin( radians(p.latitude)))) AS distance', false);
  } 
}
	$this->db->from('property p');
	$this->db->join('property_category pc', 'pc.property_category_id = p.property_category', 'LEFT');
	$this->db->join('property_image pimg', 'pimg.property_id = p.property_id', 'LEFT');
	$this->db->join('user u', 'u.user_id = p.user_id', 'LEFT');
	
	$this->db->join('user_associated_agency uaa', 'uaa.user_id = u.user_id', 'LEFT'); 
    $this->db->join('agency_detail ad', 'ad.agency_id = uaa.agency_id', 'LEFT'); 
	$this->db->join('property_auction pa', "pa.property_id = p.property_id"); 
	//$this->db->join('user_parent_group upg', 'upg.user_id = u.user_id', 'LEFT'); 
	//$this->db->join('agency_detail ad', 'upg.user_parent_id = ad.user_id', 'LEFT');	 
	$this->db->join('property_nearby_address pnba', 'pnba.property_id = p.property_id ', 'LEFT'); 
	$this->db->join('manage_nearby mnb', 'mnb.nearby_id = pnba.property_key', 'LEFT'); 
	
	
	$this->db->join('country c', 'c.countryid = p.country', 'LEFT');  // internet
	$this->db->join('country_regions cr', 'cr.regionid = p.state', 'LEFT'); //code
	$this->db->join('country_region_cities crc', 'crc.cityId = p.city', 'LEFT'); //city
	
	
	if(trim($country) != ''){
			$this->db->where('c.internet', trim($country));
		} 
	if(trim($regions) != ''){
		  $where = "(cr.code='$regions' OR cr.region='$regions')";
		  $this->db->where($where);
		} 
	if(trim($city) != ''){
		$this->db->like('crc.city', $city, 'start');
		
	} 
	
	
	if(!empty($post) && isset($post) && $post != NULL){
	foreach($post as $key => $val){
	  if(!empty($val)){
		$this->session->set_userdata($key, $val);  
	  }else{
		$this->session->unset_userdata($key, '');   
	  }
	}
	 
	 
	
	
	
	 if(!empty($post['min_price'])){
        $this->db->where('p.prices >=', $post['min_price']); 
     }if(!empty($post['max_price'])){
        $this->db->where('p.prices <=', $post['max_price']); 
     }
	 if(!empty($post['proprty_category'])){
	    $this->db->where('pc.categoryName', $post['proprty_category']); 
     }	 
	 if(!empty($post['property_type'])){
	    $this->db->where('p.property_type', $post['property_type']); 
     }
if(!empty($post['bed_room'])){
	  $this->db->where('p.bedrooms >=', $post['bed_room']); 
     }
	 
	 
	 if(!empty($post['near_by'])){
        $this->db->where('pnba.property_key', $post['near_by']);
     } 
	 if(!empty($post['location'])){
       // $this->db->like('p.address', $post['location'], 'start');
     } 
	 if(!empty($post['search_type'])){
			$this->db->where('( 3959 * acos( cos( radians("'.$latitude.'") ) * cos( radians(p.latitude)) * cos( radians( p.longitude) - radians("'.$longitude.'"))+sin(radians("'.$latitude.'"))*sin(radians(p.latitude)))) < ', 200); 
		}
		
		if(!empty($post['features_name'])){		
         $this->db->join('property_features pfs', 'pfs.property_id = p.property_id ', 'LEFT'); 
		 $this->db->where_in('pfs.features_id', $post['features_name']);
        }	 
		
	}	
    $this->db->where('p.status', 'Active');
	
	$this->db->where('pa.status', 'Active');
	 
	$this->db->where("p.auction_status", "1");
	$currenttime = strtotime(date('M-d-Y H:i:s'));
	$this->db->where('pa.start_date <= "'.$currenttime.'" and pa.end_date >= "'.$currenttime.'"');
	$date=date('m/d/Y');
    //$this->db->where('p.property_closing_date >= "'.$date.'"');
	
	$wh = "STR_TO_DATE(p.property_closing_date, '%m/%d/%Y') >= STR_TO_DATE('$date', '%m/%d/%Y')";
	$this->db->where($wh); 


	
	$this->db->group_by("p.property_id");
	$this->db->order_by("p.property_id", "desc");
	$query = $this->db->get();
	//echo $str = $this->db->last_query();
	if($query->num_rows()>0){
		$result =  count($query->result_array());
		   return  $result;
	}else{ 
	   return false;
	}
	
 }
 
public function getAuctionPropertyListing($limit, $offset, $post= NULL,$type = 'json',$no_limit= NULL){

if(empty($post)){
	$post =	$this->session->userdata('property_search');
	
	}
	
	$post_data = array('property_search' => $post);
	$this->session->set_userdata($post_data);


	//$this->output->enable_profiler(TRUE); 
    
    $latitude =  $post['latitude'] ? $post['latitude'] : $this->session->userdata('latitude');
	$longitude = $post['longitude'] ? $post['longitude'] : $this->session->userdata('longitude');
	$country = $post['country_code'] ? $post['country_code'] : $this->session->userdata('country_internet');
	$regions = trim($post['regions']);
    $city = trim($post['city']);
	
	
	 $this->db->select('pty.typeName,p.property_id,p.property_closing_date, p.property_name, p.property_description, p.prices as property_price, p.address as property_address, p.property_sqft, p.latitude, p.longitude, pc.categoryName as property_category_name, p.bathrooms,p.bedrooms,p.auction_status, u.user_id as agent_id,u.profile_image, u.firstName as agent_name,u.lastName,u.address as agent_company_address, u.phone_number as agent_company_phone_number, u.phone_number as agent_company_cell_number,ad.agency_id, ad.agency_logo as agency_company_logo, ad.agency_name as agency_company_name, pimg.image_name,
  (SELECT COUNT(upv.property_id) from user_property_views upv where upv.property_id = p.property_id  GROUP BY upv.property_id) as property_views_count, 
 (SELECT COUNT(pimgcount.property_id) from property_image pimgcount where pimgcount.property_id = p.property_id  GROUP BY pimgcount.property_id) as property_image_count,
  (SELECT COUNT(upf.property_id) from user_property_favorites upf where upf.property_id = p.property_id GROUP BY upf.property_id ) as property_favorites_count, pnba.property_key, mnb.nearby_name, mnb.nearby_logo,
  pa.start_date as auction_start, pa.end_date as auction_end, (Select Max(price) from user_set_auction_price usap where usap.property_id = p.property_id) as auction_highestbid
  ', false);
	if(!empty($post) && isset($post) && $post != NULL){
	  if(!empty($post['search_type'])){  
	   $this->db->select('( 3959 * acos( cos( radians("'.$latitude.'") ) * cos( radians(p.latitude) ) * cos( radians(p.longitude) - radians("'.$longitude.'") ) + sin( radians("'.$latitude.'") ) * sin( radians(p.latitude)))) AS distance', false);
	  }
	}
	$this->db->from('property p');
	$this->db->join('property_category pc', 'pc.property_category_id = p.property_category', 'LEFT');
	
	$this->db->join('property_types pty', 'pty.property_types_id = p.property_type', 'LEFT');
	
	
	$this->db->join('property_image pimg', 'pimg.property_id = p.property_id', 'LEFT');
	$this->db->join('user u', 'u.user_id = p.user_id', 'LEFT');
	$this->db->join('user_associated_agency uaa', 'uaa.user_id = u.user_id', 'LEFT'); 
    $this->db->join('agency_detail ad', 'ad.agency_id = uaa.agency_id', 'LEFT'); 
	$this->db->join('property_nearby_address pnba', 'pnba.property_id = p.property_id ', 'LEFT'); 
	$this->db->join('manage_nearby mnb', 'mnb.nearby_id = pnba.property_key', 'LEFT');
	$this->db->join('property_auction pa', "pa.property_id = p.property_id"); 
	
	$this->db->join('country c', 'c.countryid = p.country', 'LEFT');  // internet
	$this->db->join('country_regions cr', 'cr.regionid = p.state', 'LEFT'); //code
	$this->db->join('country_region_cities crc', 'crc.cityId = p.city', 'LEFT'); //city
	
	if(trim($country) != ''){
			$this->db->where('c.internet', trim($country));
		} 
	if(trim($regions) != ''){
		  $where = "(cr.code='$regions' OR cr.region='$regions')";
		  $this->db->where($where);
		} 
	if(trim($city) != ''){
		$this->db->like('crc.city', $city, 'start');
		
	} 
	
	
	if(!empty($post) && isset($post) && $post != NULL){
	foreach($post as $key => $val){
	  if(!empty($val)){
		$this->session->set_userdata($key, $val);  
	  }else{
		$this->session->unset_userdata($key, '');   
	  }
	}
	
	
	
	 if(!empty($post['min_price'])){
        $this->db->where('p.prices >=', $post['min_price']); 
     }if(!empty($post['max_price'])){
        $this->db->where('p.prices <=', $post['max_price']); 
     }
	 if(!empty($post['proprty_category'])){
	    $this->db->where('pc.categoryName', $post['proprty_category']); 
     }	 
	 if(!empty($post['property_type'])){
	    $this->db->where('p.property_type', $post['property_type']); 
     }
     if(!empty($post['bed_room'])){
	    $this->db->where('p.bedrooms >=', $post['bed_room']); 
     }
	 
	 if(!empty($post['near_by'])){
        $this->db->where('pnba.property_key', $post['near_by']);
     } 
	 if(!empty($post['location'])){
       // $this->db->like('p.address', $post['location'], 'start');
     } 
        if(!empty($post['search_type'])){
			$this->db->where('( 3959 * acos( cos( radians("'.$latitude.'") ) * cos( radians(p.latitude)) * cos( radians( p.longitude) - radians("'.$longitude.'"))+sin(radians("'.$latitude.'"))*sin(radians(p.latitude)))) < ', 200); 
		}
		if(!empty($post['features_name'])){		
         $this->db->join('property_features pfs', 'pfs.property_id = p.property_id ', 'LEFT'); 
		 $this->db->where_in('pfs.features_id', $post['features_name']);
        }	 
	}
	
	 $this->db->where('p.status', 'Active');
	 $this->db->where('pa.status', 'Active');
	 $this->db->where("p.auction_status", "1");
	$currenttime = strtotime(date('M-d-Y H:i:s'));
	$this->db->where('pa.start_date <= "'.$currenttime.'" and pa.end_date >= "'.$currenttime.'"');
	$date=date('m/d/Y');
    //$this->db->where('p.property_closing_date >= "'.$date.'"');
	
	$wh = "STR_TO_DATE(p.property_closing_date, '%m/%d/%Y') >= STR_TO_DATE('$date', '%m/%d/%Y')";
	$this->db->where($wh); 
	
	
     $this->db->group_by("p.property_id");
	$this->db->order_by("p.property_id", "desc");
    
	if(empty($no_limit)){
	
	$this->db->limit($limit, $offset);   
	
	}
	$query = $this->db->get();
  //echo $str = $this->db->last_query();
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
			/*aup.company_address as ,aup.company_phone_number as ,aup.company_cell_number as agent_	company_cell_number,up.company_logo as agency_company_logo,up.company_name as agency_company_name, pv.property_views_count,upf.property_favorites_count*/
			
			        
			        $property_image_name = getUserProfileImage($val['image_name'],$url.'applicationMediaFiles/propertiesImage/350325/');
					
					$agency_company_logo = getUserProfileImage($val['agency_company_logo'],$url.'applicationMediaFiles/companyImage/9999/');
					
					$profile_image = getUserProfileImage($val['profile_image'],$url.'applicationMediaFiles/usersImage/150150/');
					
					
					if(config_item('URL_ENCODE')){
					$property_id = safe_b64encode($val['property_id']);	
					}else{
					$property_id = $val['property_id'];	
					}
					
					if(config_item('URL_ENCODE')){
					$agency_id = safe_b64encode($val['agency_id']);	
					}else{
					$agency_id = $val['agency_id'];	
					}
                    $property_address   = str_replace("\r\n", "", $val['property_address']);
					$property_address   = str_replace("\r", "", $property_address);
					$property_address   = str_replace("\n", "", $property_address);
					
					$property_auction_end = '';
					if (empty($val['auction_highestbid']) || $val['auction_highestbid'] <= 0){
						$property_auction_end = 'N/A';
					} else {
						$property_auction_end = attachCurrencySymbol(convert_currency($val['auction_highestbid']));
					}
					
					
					$slug = seo_friendly_urls($val['agent_name'],$val['lastName'],$val['agent_id']);
					
					$bedrooms = $val['bedrooms'];
			        $property_type_name = $val['typeName'];
			        $category_name = $val['property_category_name'];
			        $seo_url_string = ucwords($bedrooms.'  Bed '. $property_type_name.'  For '. $category_name);
			        $property_seo_url = seo_friendly_urls($seo_url_string,'',$val['property_id']);
					
					
					
					
                    $manage[] = array("property_id" => $val['property_id'],
					                  "property_id_encode" => $property_id ,
									  "property_seo_url" => $property_seo_url ,
									  "property_auction_end" => date('Y/m/d H:i:s', $val['auction_end']),
									  "property_auction_highestbid" => $property_auction_end,
									  "bathrooms" => $val['bathrooms'],
									  "bedrooms" => $val['bedrooms'],
									  "property_category_name" => $val['property_category_name'],
									  "typeName" => $val['typeName'],
			                          "property_name" => $val['property_name'],
							    	  "property_price" => attachCurrencySymbol(convert_currency($val['property_price'])),
									  "agency_id" => $agency_id,
									  "agency_company_logo" => $agency_company_logo,
									  
									  "property_address" => replace_strings(stripslashes(htmlentities($property_address))),
									  "property_sqft" => $val['property_sqft'],
									  "latitude" => $val['latitude'],
									  "longitude" => $val['longitude'],
                                      "property_image_name" => $property_image_name,
									  "property_favorites_count" => $val['property_favorites_count'] ? $val['property_favorites_count'] : 0,
									  "property_image_count" => $val['property_image_count'] ? $val['property_image_count'] : 0,
                                      "agent_name" => $val['agent_name'],
									  "agent_company_address" => $val['agent_company_address'],
									  "agent_company_phone_number" => $val['agent_company_phone_number'],
									  "favourites_property"=>$favourites_class,
									  "agent_id"=>$slug,
									  "auction_status"=>$val['auction_status'],
									  "profile_image"=>$profile_image
									  ); 
			//$manage[] = array("property_id" => $val["property_id"]);
									  
			}
			if($type == 'json'){
              
			 /* $manage = str_replace("\r",'', $manage);
			  $manage = str_replace("\n",'', $manage);
              $manage = str_replace("\r\n",'', $manage);*/
			return  json_encode($manage);
			}else{
			return  $manage;
			}
	   }else{ 
	   return false;
	   }
	
 }  
  
  
  function nearby_propertyLatLng($id)
	{
	 $this->db->select('pna.property_val,pna.nearby_address,pna.nearby_lat,pna.nearby_lang,mn.nearby_name,mn.nearby_logo',false);
	 $this->db->from('property_nearby_address as pna');
	 $this->db->join('manage_nearby mn','mn.nearby_id = pna.property_key','LEFT');
	 $this->db->where('pna.property_id',$id);
	 $this->db->where('mn.status','Active');
	 $result = $this->db->get()->result_array();
	 if(!empty($result))
	   {
		   
	    foreach($result as $val)
		{
		$manage[]=array(
				 'lat'=>$val['nearby_lat'],
		         'lng'=>$val['nearby_lang']);
		}
	    return  json_encode($manage);
	   }
	}
	
	
  
	function nearby_propertylisting($id)
	{
	 
	 $this->db->select('pna.property_val,pna.nearby_address,pna.nearby_lat,pna.nearby_lang,mn.nearby_name,mn.nearby_logo',false);
	 $this->db->from('property_nearby_address as pna');
	 $this->db->join('manage_nearby mn','mn.nearby_id = pna.property_key','LEFT');
	 $this->db->where('pna.property_id',$id);
	 $this->db->where('mn.status','Active');
	 $result = $this->db->get()->result_array();
	 if(!empty($result))
	   {
	    
	    foreach($result as $val)
		{
		 $manage[]= array("property_val" => $val['property_val'],
		  				   "nearby_address" => $val['nearby_address'],
						   "nearby_lat" => $val['nearby_lat'],
						   "nearby_lang" => $val['nearby_lang'],
						   "nearby_name" => $val['nearby_name'],
						   "type"=>"",
						   "bedrooms"=>"",
						   "bathrooms"=>"",
						   "num_floors"=>"",
						   "num_recepts"=>"",
						   "nearby_logo" => base_url('applicationMediaFiles/markers/'.$val['nearby_logo']));
						   
		
				
		 	
		}
      $sql = "SELECT p.property_category,p.address,p.latitude,p.longitude,p.property_name,p.bedrooms,p.bathrooms,p.num_floors,p.num_recepts,pty.typeName , pc.categoryName FROM property as p LEFT JOIN property_category as pc on pc.property_category_id = p.property_category    LEFT JOIN property_types as pty on pty.property_types_id = p.property_type  where p.property_id = $id";
	  
	
	
	
	
	  $query = $this->db->query($sql);
	  if($query->num_rows()>0){
           foreach($query->result_array() as $val){
		   	
			
			    $bedrooms = $val['bedrooms'];
			   $property_type_name = $val['typeName'];
			   $category_name = $val['categoryName'];
$propertyName = ucwords($bedrooms.'  Bedroom  '. $property_type_name.'  For '. $category_name);
				
				
				
			    $manage_property[]= array("property_val" => $val['property_category'],
		  				   "nearby_address" => $val['address'],
						   "nearby_lat" => $val['latitude'],
						   "nearby_lang" => $val['longitude'],
   					       "nearby_name" => $propertyName,
						   "bedrooms"=>$val['bedrooms'],
						   "bathrooms"=>$val['bathrooms'],
						   "num_floors"=>$val['num_floors'],
						   "num_recepts"=>$val['num_recepts'],
						   "type"=>"default",
						   "nearby_logo" => base_url('applicationMediaFiles/markers/map-pointer.png'));	
						   
			   
		  }
		  $manage = array_merge($manage, $manage_property);
        }
	    return  json_encode($manage);
	   }else{
		   
			 $sql = "SELECT p.property_category,p.address,p.latitude,p.longitude,p.property_name,p.bedrooms,p.bathrooms,p.num_floors,p.num_recepts,pty.typeName , pc.categoryName FROM property as p LEFT JOIN property_category as pc on pc.property_category_id = p.property_category    LEFT JOIN property_types as pty on pty.property_types_id = p.property_type  where p.property_id = $id";
	  $query = $this->db->query($sql);
	  if($query->num_rows()>0){
           foreach($query->result_array() as $val){
		  $bedrooms = $val['bedrooms'];
			   $property_type_name = $val['typeName'];
			   $category_name = $val['categoryName'];
$propertyName = ucwords($bedrooms.'  Bedroom  '. $property_type_name.'  For '. $category_name);
		   
			    $manage[]= array("property_val" => $val['property_category'],
		  				   "nearby_address" => $val['address'],
						   "nearby_lat" => $val['latitude'],
						   "nearby_lang" => $val['longitude'],
   					       "nearby_name" => $propertyName,
						   "bedrooms"=>$val['bedrooms'],
						   "bathrooms"=>$val['bathrooms'],
						   "num_floors"=>$val['num_floors'],
						   "num_recepts"=>$val['num_recepts'],
						   "type"=>"default",
						   "nearby_logo" => base_url('applicationMediaFiles/markers/map-pointer.png'));	
						   
			   
		  }
		  
        }
	    return  json_encode($manage);
		   }
	}
	function getProperuserDetail($created_by = '')
	{
			    $this->db->select('u.user_professional_title,u.country,u.profile_image,ad.agency_name,ad.agency_establish,ad.agency_phone_number,ad.agency_cell_number,ad.agency_fax,ad.agency_website,ad.blog_url,ad.agency_logo');
				$this->db->from('user u');
				$this->db->join('agency_detail ad','ad.user_id = u.user_id','LEFT');
				$this->db->where('u.user_id',$created_by);
				$result = $this->db->get()->result_array();
				if(!empty($result)){return $result;}else{return false;}
	}
  
}
