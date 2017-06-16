<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Property_model extends CI_Model {
public function getFeaturedProperty(){
//$this->output->enable_profiler(TRUE);	
   $latitude =  $this->session->userdata('latitude');
   $longitude = $this->session->userdata('longitude');
   $country = $this->session->userdata('country_internet');
   
$this->db->select('p.property_id,p.property_closing_date,p.property_description,p.prices as property_price,p.address as property_address,ad.agency_id,ad.agency_logo,p.property_sqft,pimg.image_name,p.bedrooms,p.bathrooms,pts.typeName as property_types,u.profile_image as user_image,u.user_id,u.firstName,u.lastName,
(SELECT COUNT(pimgc.property_id) from property_image pimgc where pimgc.property_id = p.property_id  GROUP BY pimgc.property_id) as property_image_count, 
  (SELECT COUNT(upv.property_id) from user_property_views upv where upv.property_id = p.property_id  GROUP BY upv.property_id) as property_views_count, pc.categoryName as property_category_name,
  (SELECT COUNT(upf.property_id) from user_property_favorites upf where upf.property_id = p.property_id GROUP BY upf.property_id ) as property_favorites_count');
 
 if(!empty($latitude) && !empty($longitude)){
  $this->db->select('( 3959 * acos( cos( radians("'.$latitude.'") ) * cos( radians(p.latitude) ) * cos( radians(p.longitude) - radians("'.$longitude.'") ) + sin( radians("'.$latitude.'") ) * sin( radians(p.latitude)))) AS distance', false);
}
  
  
 $this->db->from('property p');
 $this->db->join('user u', 'u.user_id = p.user_id', 'LEFT');
 $this->db->join('user_associated_agency uaa', 'uaa.user_id = u.user_id', 'LEFT'); 
 $this->db->join('agency_detail ad', 'ad.agency_id = uaa.agency_id', 'LEFT');
 $this->db->join('property_image pimg', 'pimg.property_id = p.property_id', 'LEFT');
 $this->db->join('property_category pc', 'pc.property_category_id = p.property_category', 'LEFT');
 $this->db->join('property_types pts', 'pts.property_types_id = p.property_type', 'LEFT');
 $this->db->join('country c', 'c.countryid = p.country', 'LEFT');  // internet
 
 $this->db->join('property_nearby_address pna', 'pna.property_id = p.property_id', 'LEFT');
 $this->db->join('manage_nearby mn', 'mn.nearby_id = pna.property_key', 'LEFT');
 
 
 
 //$this->db->join('property_features pfs', 'pfs.property_id = p.property_id ', 'LEFT'); 
 //$this->db->join('manage_property_features mpf', 'mpf.manage_features_id = pfs.property_features_id ', 'LEFT'); 
 
 $this->db->join('property_auction pa', 'pa.property_id = p.property_id','LEFT');
 //$this->db->where('p.property_id != pa.property_id');
		
 
 $this->db->where('p.status', 'Active');
 $this->db->where('pc.status', 'Active');
 $this->db->where('pts.status', 'Active');
 //$this->db->where('mn.status', 'Active');
 
 if(trim($country) != ''){
   $this->db->where('c.internet', trim($country));
  }
 
 //$this->db->where('mpf.status', 'Active');
	
	/* if(!empty($latitude) && !empty($longitude)){
			$this->db->where('( 3959 * acos( cos( radians("'.$latitude.'") ) * cos( radians(p.latitude)) * cos( radians( p.longitude) - radians("'.$longitude.'"))+sin(radians("'.$latitude.'"))*sin(radians(p.latitude)))) < ', 1000); 
		}*/
		
	$date=date('m/d/Y');
    $this->db->where('p.property_closing_date >="'.$date.'"');	
	$this->db->group_by("p.property_id");
	$this->db->where("p.auction_status", "0");
	$this->db->group_by("p.user_id");
	//$this->db->order_by("p.property_id", "DESC");
   $this->db->order_by("p.property_id", "random"); 
	$this->db->limit('4');
	$query = $this->db->get();
	if($query->num_rows()>0){
	  $result =  $query->result_array();
	   $query->free_result();
	  return $result;
	}else{ 
	   return false;
	}
 }
/*This function by Aliasger Sabunwala on 2 Sep 2015*/
public function getAuctionProperty(){
   $latitude =  $this->session->userdata('latitude');
   $longitude = $this->session->userdata('longitude');
   $country = $this->session->userdata('country_internet');
   
$this->db->select('p.property_id,p.property_closing_date,p.property_description,p.prices as property_price,p.address as property_address,ad.agency_id,ad.agency_logo,
p.property_sqft,pimg.image_name,p.bedrooms,p.bathrooms,u.profile_image as user_image,u.user_id,
pts.typeName as property_types,
(SELECT COUNT(pimgc.property_id) from property_image pimgc where pimgc.property_id = p.property_id  GROUP BY pimgc.property_id) as property_image_count, 
  (SELECT COUNT(upv.property_id) from user_property_views upv where upv.property_id = p.property_id  GROUP BY upv.property_id) as property_views_count, pc.categoryName as property_category_name,
  (SELECT COUNT(upf.property_id) from user_property_favorites upf where upf.property_id = p.property_id GROUP BY upf.property_id ) as property_favorites_count,
pa.start_date as auction_start, pa.end_date as auction_end, (Select Max(price) from user_set_auction_price usap where usap.property_id = p.property_id) as auction_highestbid');
 
 if(!empty($latitude) && !empty($longitude)){
  $this->db->select('( 3959 * acos( cos( radians("'.$latitude.'") ) * cos( radians(p.latitude) ) * cos( radians(p.longitude) - radians("'.$longitude.'") ) + sin( radians("'.$latitude.'") ) * sin( radians(p.latitude)))) AS distance', false);
}
  
 
 $this->db->from('property p');
 $this->db->join('user u', 'u.user_id = p.user_id', 'LEFT');
 $this->db->join('user_associated_agency uaa', 'uaa.user_id = u.user_id', 'LEFT'); 
 $this->db->join('agency_detail ad', 'ad.agency_id = uaa.agency_id', 'LEFT');
 $this->db->join('property_image pimg', 'pimg.property_id = p.property_id', 'LEFT');
 $this->db->join('property_category pc', 'pc.property_category_id = p.property_category', 'LEFT');
 $this->db->join('property_types pts', 'pts.property_types_id = p.property_type', 'LEFT');
 
 $this->db->join('country c', 'c.countryid = p.country', 'LEFT');  // internet
 $this->db->join('property_nearby_address pna', 'pna.property_id = p.property_id', 'LEFT');
 $this->db->join('manage_nearby mn', 'mn.nearby_id = pna.property_key', 'LEFT');
 
 $currenttime = strtotime(date('M-d-Y H:i:s'));
 
 $this->db->join('property_auction pa', "pa.property_id = p.property_id");
 $this->db->where('pa.status', 'Active');
 $this->db->where('p.status', 'Active');
  $this->db->where('pc.status', 'Active');
 $this->db->where('pts.status', 'Active');
 //$this->db->where('mn.status', 'Active');
 $this->db->where("p.auction_status", "1");
 
 $session_user_id =  $this->session->userdata('user_id');
 if(empty($session_user_id)){
	$this->db->where('pa.publicStatus', 'On'); 
 }

  
   if(trim($country) != ''){
   $this->db->where('c.internet', trim($country));
  }

  
	$this->db->where('pa.start_date <= "'.$currenttime.'" and pa.end_date >= "'.$currenttime.'"');	
	$date=date('m/d/Y');
	
	
    
 
    //$this->db->where('p.property_closing_date >= "'.$date.'"');	
	//$this->db->where('p.property_closing_date >= "'.$date.'"');	
	
	$wh = "STR_TO_DATE(p.property_closing_date, '%m/%d/%Y') >= STR_TO_DATE('$date', '%m/%d/%Y')";
	$this->db->where($wh); 


	$this->db->group_by("p.property_id");
	$this->db->group_by("p.user_id");
	//$this->db->order_by("p.property_id", "DESC");
	$this->db->order_by("p.property_id", "random"); 
	$this->db->limit('4');
	$query = $this->db->get();
	if($query->num_rows()>0){
	  $result =  $query->result_array();
	   $query->free_result();
	  return $result;
	}else{ 
	   return false;
	}
 }
 public function getPropertyAbroad($country = NULL){
  // $this->output->enable_profiler(TRUE);
    $this->db->select('p.*,c.internet as country,cr.code as region,crc.city');
    $this->db->from('property_find_abroad p');
	$this->db->join('country c', 'c.countryid = p.abroad_country', 'LEFT');
	$this->db->join('country_regions cr', 'cr.regionid = p.abroad_state', 'LEFT'); //code
	$this->db->join('country_region_cities crc', 'crc.cityId = p.abroad_city', 'LEFT'); //city
	
    
	$country = $country ? $country :$this->session->userdata('country');
	if(!empty($country)){
	$this->db->where('c.country', $country); 
	}
	$this->db->where('p.status', 'Active'); 
	$this->db->order_by("p.id", "random"); 
	$this->db->limit('7');
	$query = $this->db->get();
	
   if($query->num_rows()>0){
	    $result = $query->result_array();
		 $query->free_result();
		return $result;
	}else{
		//return $this->getPropertyAbroad('United Kingdom');
		return false;			
	}
	
	
 }	
/* insert in datebase */
	public function data_insert($mytable,$data)
	{
		$query = $this->db->insert($mytable, $this->db->escape_str($data)); 
		$id = $this->db->insert_id();
		return $id;			
	}
/* update in datebase */
	public function data_update($mytable,$data,$where)
	{
		if(is_array($where)){
			foreach ($where as $key => $value){
			  $this->db->where($key, $value);
			}
		} 
		$this->db->update($mytable, $this->db->escape_str($data));
		return true;
	}
  
/* delete in datebase */
	public function data_delete($mytable,$attribute_name, $attribute_value)
	{
		$this->db->where($attribute_name, $attribute_value);
		$this->db->delete($mytable);
		return true;
	}
public function get_property_details($property_id){
  //  $this->output->enable_profiler(TRUE);
	$this->db->select('p.*, pauction.property_auction_id,pauction.start_date,pauction.end_date,pauction.auction_min_price,
(SELECT GROUP_CONCAT(features_id SEPARATOR ", ") FROM property_features WHERE find_in_set(property_id, p.property_id)) as features_id
', false);
	$this->db->from('property p');
	$this->db->join('property_auction pauction', 'pauction.property_id = p.property_id', 'LEFT');
	$this->db->where('p.property_id', $property_id); 
	$this->db->where('p.user_id', $this->session->userData('user_id'));
	$query = $this->db->get();
	if($query->num_rows()>0){
		$result =  $query->result_array();
		 $query->free_result();
		   return  $result;
	}else{ 
	   return false;
	} 	
}
/* select in datebase */
     public function select($table, $where ='', $oderby = '')
	 {	
		$sql = "SELECT * FROM ".$table;
		if(!empty($where)){
     	 $sql .= " ".$where;
		}
		if(!empty($oderby)){
		 $sql .= " ".$oderby;
		}
		$query = $this->db->query($sql);
		if($query->num_rows()>0)
		{
         $result = $query->result_array();
               $query->free_result();
			return $result;
		}else{
			return false;
		}
	}
	
/*country state city get*/ 
  function country_state_city($country,$state,$city){
	 $this->db->select('c.country,s.region,ct.city');
 $this->db->from('country c');
 $this->db->join('country_regions s', 'c.countryid = s.countryid', 'LEFT'); 
 $this->db->join('country_region_cities ct', 'c.countryid = ct.countryid', 'LEFT'); 
 $this->db->where('c.countryid',$country);
 $this->db->where('s.regionid',$state);
 $this->db->where('ct.cityId',$city);
 $query = $this->db->get();
 if($query->num_rows()>0){
      $data =  $query->result_array();
 $query->free_result();
 return $data[0];
    }else{
   return false;
 }
}
function selectJson($table, $where ='')
{
        $sql = "SELECT * FROM $table";
  if(!empty($where)){
     $sql .= " $where";
  }
  $query = $this->db->query($sql);
        if($query->num_rows()>0)
        {
      $models_list = array();
            $models_list = $query->result();
 $query->free_result();
			
         return  $json = json_encode($models_list);
         }
        else{
            return false;
        }
    }
public function getNewMessageUniqueID(){
	$this->db->select('max(message_unique_id) as maxid');
	$this->db->from('messages');
	$query = $this->db->get();
	if($query->num_rows()>0){
		$resarr = $query->result_array(); 
		$maxval = $resarr[0]['maxid'];
 $query->free_result();
		return $maxval + 1;
	} else {
		return 1;
	}
}
public function totalCounter($table,$order)
{		//$this->output->enable_profiler(TRUE);
		$this->db->select('p.* , pc.* , pt.*');
		$this->db->from('property p');
		$this->db->join('property_category pc', 'p.property_category = pc.property_category_id', 'LEFT');
		$this->db->join('property_types pt', 'p.property_type = pt.property_types_id', 'LEFT');
		$this->db->order_by('p.'.$order);
		
		if($this->session->userdata('field'))
		{
			$fieldname = $this->fieldNameList($this->session->userdata('field'));
			if(!($this->session->userdata('searchdata')))
			{
				$inputdata = $this->session->userdata('searchdata');
				$this->db->like($fieldname, $inputdata);
			}
			
		}
		$query = $this->db->get();	
		$num_rows = $query->num_rows();
		$query->free_result();	
		return $num_rows;
}
public function getCounter($table,$limit, $offset, $sort_by, $sort_order, $wherelike= NULL)
{
	    $this->db->select('p.* , pc.* , pt.*');
		$this->db->from('property p');
		$this->db->join('property_category pc', 'p.property_category = pc.property_category_id', 'LEFT');
		$this->db->join('property_types pt', 'p.property_type = pt.property_types_id', 'LEFT');
		if($this->session->userdata('field'))
		{
			$fieldname = $this->fieldNameList($this->session->userdata('field'));
			if($this->session->userdata('searchdata'))
			{
				$inputdata = $this->session->userdata('searchdata');
				$this->db->like($fieldname, $inputdata);
			}
		}
	$this->db->limit($limit, $offset);	
	$sort_by = $this->fieldNameList($sort_by);		
	$sort_by = ($sort_by)?$sort_by:'date_time';
	$this->db->order_by($sort_by, $sort_order);
	$query = $this->db->get();
	$result = $query->result_array();		
	 $query->free_result();
	return $result;	
}
	function fieldNameList($key=''){
		
		$fields = array('name' => 'pc.name',
						'type' => 'pt.type',
						'property_name' => 'p.property_name',
						'property_description' =>'p.property_description',
						'address' => 'p.address'
					);
		foreach($fields as $fkey => $name){			
			if($fkey == $key){
				return $fields[$fkey];	
				break;
			}	
		} // close foreach loop	
		
		return 'date_time';
	}
	
	
function getPropertyImage($property_id){
	if(empty($property_id)){return false;}
		
	$this->db->select('*');
	$this->db->from('property_image'); 
	$this->db->where('property_id',$property_id); 
	$query = $this->db->get();
	
	if($query->num_rows()>0){
	 $data =  $query->result_array();
	  $query->free_result();
	return $data;
	}else{
	return false;
	}	
}
		
function getPropertyVists($property_id){
	if(empty($property_id)){return false;}
	$this->db->select('*');
	$this->db->from('user_property_views'); 
	$this->db->where('property_id',$property_id); 
	$query = $this->db->get();
	
	if($query->num_rows()>0){
	 $data =  $query->num_rows();
	  $query->free_result();
	return $data;
	}else{
	 return false;
	}
}
	
function getPropertyFavourites($property_id){
	if(empty($property_id)){return false;}
	$this->db->select('*');
	$this->db->from('user_property_favorites'); 
	$this->db->where('property_id',$property_id); 
	$query = $this->db->get();
	if($query->num_rows()>0){
	$data =  $query->num_rows();
	 $query->free_result();
	return $data;
	}else{
	 return false;
	}
}
	
function getProperty(){
 $this->db->select('p.*,pimg.*,u.name as agent_name,u.email,up.company_logo as company_logo,up.company_phone_number as agent_phone_number,up.company_cell_number as agent_cell_number,up.company_address as agent_address,pc.categoryName as category_name,c.country as country_name,cr.region as state_name,crc.city as city_name,pt.typeName as property_type_name,ppm.name as property_price_modifier_name');
 $this->db->from('property p');
 $this->db->join('user u', 'p.user_id = u.user_id', 'LEFT'); 
 $this->db->join('user_profile up', 'u.user_id = up.user_id', 'LEFT'); 
 $this->db->join('property_category pc', 'p.property_category = pc.property_category_id', 'LEFT'); 
 $this->db->join('country c', 'p.country = c.countryid', 'LEFT');
 $this->db->join('country_regions cr', 'p.state = cr.regionid', 'LEFT');
 $this->db->join('country_region_cities crc', 'p.city = crc.cityId', 'LEFT');
 $this->db->join('property_types pt', 'p.property_type = pt.property_types_id', 'LEFT');
 $this->db->join('property_price_modifier ppm', 'p.price_modifier = ppm.property_price_modifier_id', 'LEFT');
 $this->db->join('property_image pimg', 'p.proprty_id = pimg.property_id', 'LEFT');
// $this->db->where('p.proprty_id',$property_id); 
  $query = $this->db->get();
  if($query->num_rows()>0){
   $manage = array('status' => '1','message'=>'successfully');
      $result =  $query->result_array();
   foreach($result as $val){
    $manage['property_result'][] =  array('id' =>$val['proprty_id'],
           'user_id' => $val['user_id'],
           'property_name' => $val['property_name'],
           'property_description' => $val['property_description'],
           'county' => $val['county'],
           'min_price' => $val['min_price'],
           'property_images' => $this->getPropertyImage($val['proprty_id']),
           'max_price' => $val['max_price'],
		   
		   'prices'=>$val['prices'],
		   
		   'bedrooms'=>$val['bedrooms'],
		   
		   'address'=>$val['address'], 
		   
		   'latitude'=>$val['latitude'], 
		   
		   'longitude'=>$val['longitude'], 
		   
		   'agent_id'=>$val['user_id'],
		   
		   'agent_name'=>$val['agent_name'], 
		   
		   'agent_address'=>$val['agent_address'],  
		   
		   'agent_phone_number'=>$val['agent_phone_number'],
		   
		   'company_logo'=>$val['company_logo'],
		   
		   'property_view'=>$this->getPropertyVists($val['proprty_id']),
		   
		   'property_save'=>$this->getPropertyFavourites($val['proprty_id']),
		   
		   'property_review'=>'',	
			
              );
              
  }
   //echo $manage = json_encode($manage);
    $query->free_result();
   return $manage;
   }else{
   return false;
   }
}
}