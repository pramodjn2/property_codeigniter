<?php

function value_check($val, $st1){
		if (in_array($val, $st1)) {
          return true;
		  }else{
			return false;  
			}
		}
		
		
function select($table, $where='')
{
	  $CI = & get_instance();
	  //$CI->load->database();
					
	  $sql = "select * from $table";
	  if(!empty($where))
	    $sql .= " $where";
	  $query = $CI->db->query($sql);
	  if($query->num_rows()>0){
		 $result = $query->result_array();
		 return $result;
	   }else{
		  return false; 
	  }
}



/*Load Langauage*/ 
function language_load()
{

   $CI = & get_instance();

   $language = $CI->session->userdata('language') ? $CI->session->userdata('language') : 'english';

   return $language;

} 

/*Resize Image*/
function resize_images($path,$rs_width,$rs_height,$destinationFolder='') {

 $folder_path = dirname($path);
 $thumb_folder = $destinationFolder;
 $percent = 0.5;
 
 if (!is_dir($thumb_folder)) {

    mkdir($thumb_folder, 0777, true);
 }
 
 $name = basename($path);

 $x = getimagesize($path);            

 $width  = $x['0'];

 $height = $x['1'];

 switch ($x['mime']){

              case "image/gif":

                 $img = imagecreatefromgif($path);

                 break;

              case "image/jpeg":

                 $img = imagecreatefromjpeg($path);

                 break;
			 case "image/jpg":

                 $img = imagecreatefromjpeg($path);

                 break;

              case "image/png":

                 $img = imagecreatefrompng($path);

                 break;

  }

    $img_base = imagecreatetruecolor($rs_width, $rs_height);

    $white = imagecolorallocate($img_base, 255, 255, 255);

    imagefill($img_base, 0, 0, $white);
    
	imagecopyresized($img_base, $img, 0, 0, 0, 0, $rs_width, $rs_height, $width, $height);

    imagecopyresampled($img_base, $img, 0, 0, 0, 0, $rs_width, $rs_height, $width, $height);

    $path_info = pathinfo($path);   

    $dest = $thumb_folder.$name;

           switch ($path_info['extension']) {

              case "gif":

                 imagegif($img_base, $dest);  

                 break;

              case "jpg":

                 return imagejpeg($img_base, $dest);  

                 break;
			  case "jpeg":

                 return imagejpeg($img_base, $dest);  

                 break;

              case "png":

                return imagepng($img_base, $dest);  

                 break;

           }
}
/*Get Property-Catagory-Name*/
function getPropertyCatagoryName($id){

   $CI = & get_instance();
   //$CI->load->database();
	if(!empty($id)){					
		  $sql = "select * from property_category where property_category_id='$id'";
		 
		  $query = $CI->db->query($sql);
		  if($query->num_rows()>0){
			 $result = $query->result_array();
			 return $result[0]["categoryName"];
		   }else{
			  return false; 
		  }
	}else{
	   return false; 
	}	  
}
/*Get Property-Type*/
function getPropertyTypeName($id){

   $CI = & get_instance();
   //$CI->load->database();
	if(!empty($id)){					
		  $sql = "select * from property_types where property_types_id='$id'";
		 
		  $query = $CI->db->query($sql);
		  if($query->num_rows()>0){
			 $result = $query->result_array();
			 return $result[0]["typeName"];
		   }else{
			  return false; 
		  }
	}else{
	   return false; 
	}	  
}

/*address to lat lang get*/

function address_to_latlng($address,$where){

  

  $CI = & get_instance();

  $CI->load->model('Property_model');

  $address = urlencode($address);

  $request_url = "http://maps.googleapis.com/maps/api/geocode/xml?address=".$address."&sensor=true";

  $xml = simplexml_load_file($request_url);

  $status = $xml->status;

  if ($status=="OK") {

      $Lat = $xml->result->geometry->location->lat;

      $Lon = $xml->result->geometry->location->lng;

      

 $data = array('latitude' => $Lat,

                'longitude' => $Lon);

 $result = $CI->Property_model->data_update('property',$data, $where);

   return true;

  }

  return true;

}
// 29-June Sonali Add functions 
function getStates($id,$sel_id){
	/*
	   $id = $this->input->post('id',TRUE);
	   $sel_id = $this->input->post('sel_id',TRUE);*/
	   $CI = & get_instance();
 	   $CI->load->model('Property_model');

	   if(empty($id)){
		 return false;
	   }
	   $result= $CI->Property_model->select('country_regions',"where countryid='$id'");
	   
	   $dopt = '<option disabled="disabled">Select State</option>';
	   if(!empty($result)){
	   foreach($result as $states){
		  $sel = '';
		  if($sel_id == $states['regionid']){
			 $sel = 'selected="selected"'; 
		  } 
	     $dopt .= '<option '.$sel.'  value="'.$states['regionid'].'">'.$states['region'].'</option>';
	    }
	  }
	   echo $dopt;
	}
	
function getCities($id,$sel_id){
		$CI = & get_instance();
 	    $CI->load->model('Property_model');
	    if(empty($id)){
		 return false;
	   }
	   $result=  $CI->Property_model->select('country_region_cities',"where regionid='$id'");
	   
	   $dopt = '<option disabled="disabled">Select Cities</option>';
        if(!empty($result)){
	   foreach($result as $cities){
		   $sel = '';
		  if($sel_id == $cities['cityId']){
			 $sel = 'selected="selected"'; 
		  } 
	     $dopt .= '<option '.$sel.' value="'.$cities['cityId'].'">'.$cities['city'].'</option>';
	    }
	  }
	   echo $dopt;
	}	

function countProperty($where='')
{

   $CI = & get_instance();
   //$CI->load->database();
					
	  $sql = "select * from property";
	  if(!empty($where))
	    $sql .= " $where";
	  $query = $CI->db->query($sql);
	  if($query->num_rows()>0){
		 
		 return $query->num_rows();
	   }else{
		  return 0; 
	  }

}
function getUserEmailByIDs($toArray = array()){
	if(!empty($toArray)){
		$Ids = implode(',', $toArray);
		$useremail = array();
		$userdata = selectData('user',"where user_id IN (".$Ids.")");
		foreach($userdata as $user){
			$useremail[] = $user["email"];
		}
		return $useremail;
	}
	return false;
}

/* user details get */
function getUserDetails($id = ''){
        $CI = & get_instance();
		//$CI->load->database();
        if(empty($id)){
		   $id = $CI->session->userdata('user_id');
		}
		
		$CI->db->select('*');
		$CI->db->from('user');
		$CI->db->where('user_id',$id);
		
		$result = $CI->db->get();
	    $resultData=  $result->result_array();
	 
	    if($result->num_rows() == 1)
		{
		   return $resultData;
		}
		else{ return false;	}
        
}


function countagency()
{
 $CI = & get_instance();
 $CI->db->select('*');
 $CI->db->from('user');
 $CI->db->where('group_id','4');
 $query = $CI->db->get();
return $query->num_rows();
}

function countsolicitors()
{
 $CI = & get_instance();
 $CI->db->select('*');
 $CI->db->from('user');
 $CI->db->where('group_id','6');
 $query = $CI->db->get();
return $query->num_rows();
}

function countcontractor()
{
 $CI = & get_instance();
 $CI->db->select('*');
 $CI->db->from('user');
 $CI->db->where('group_id','7');
 $query = $CI->db->get();
return $query->num_rows();
}


/* user type check */
function user_group_check()
{
  $CI = & get_instance();
  //$CI->load->database();
  $CI->load->library('session');
  $groupType = $CI->session->userdata('group_id');
  if($groupType == '1' or $groupType == '2' or $groupType == '3')
  {
  
  }
  else
  {
   $CI->session->sess_destroy();
   redirect('login');
  }

}
function getFurnishingTypeList($num='')
{
	$numdata = array('1'=> 'Furnished','2'=> 'Part Furnished','3' => 'Unfurnished');
	$list = $sel = '';	
	$i=1;
	foreach($numdata as $key => $val)
	{	
		$sel = ($num == $key)?'checked="checked"':'';			
		$list .= '<input type="radio" '.$sel.' name="furnishing_type" value="'.$key.'" >&nbsp;'.$val.'&nbsp;';		
		$i++;	
	}	
	return $list;
}
function getPropertyFeatureList($num='')
{
	$numdata = array('1'=> 'Parking/Garage','2'=> 'Garden','3' => 'Fireplace','4' => 'Balcony/terrace','5'=>'Wood floors','6'=>'Poster/Security','7'=>'Rural/Seculed','8'=>'Bills Included');
	$list = $sel = '';	
	
	$propfe=explode(",",$num);
	
	foreach($numdata as $key => $val)
	{	
		if (in_array($key,$propfe)){
		  $list .= '<input type="checkbox" checked name="property_feature[]" value="'.$key.'" >&nbsp;'.$val.'&nbsp;';
		 }
		 else{
		  $list .= '<input type="checkbox"  name="property_feature[]" value="'.$key.'" >&nbsp;'.$val.'&nbsp;';
		 }
		
					
	}	
	return $list;
}	
/* user name */
function getUserName($id)
{
	$CI = & get_instance();
 	if(empty($id))
	{
		return false;
	}
	else
	{
	$CI->db->select("CONCAT(firstName,' ',lastName) as name", false);
	$CI->db->from('user');
	$CI->db->where('user_id',$id);
	$name = $CI->db->get()->row()->name;
	
	return $name;
	}
}
/**
 * Function:	sendEmailCI
 * params:	
 * 				$to			can be string, array or comma saparated value, 
 * 				$from 		array('name'=>'', email=>''), 
 * 				$subject 	string, 
 * 				$body 		string, 
 * 				$attachment array
 */
function sendEmailCI($to, $from, $subject = '', $body = '', $attachments = array()){
	$CI = & get_instance();
	$CI->load->library('email');
	if(is_array($from)){
		$CI->email->from($from['email'], $from['name']);
	}else{
		$CI->email->from($from);
	}
	$CI->email->to($to);
	//$CI->email->to('lokendra.joshi@techlect.com');
	$CI->email->subject($subject);
	$CI->email->message($body);
	$CI->email->set_mailtype('html');
	if(!empty($attachments))
	{
		foreach($attachments as $attachment){
			$CI->email->attach(base_url().$attachment);
		}
	}
	$result = $CI->email->send();
	
	return $result;
	
}

function getPropertyFullDetails($property_id){
    if(empty($property_id)){return false;}

 $CI = & get_instance();
// $CI->load->database();

 $CI->db->select('p.*,u.firstName,u.lastName,u.email,u.phone_number,ad.agency_name,ad.agency_phone_number,ad.agency_cell_number,ad.agency_email,ad.agency_address,pc.categoryName as category_name,c.country as country_name,cr.region as state_name,crc.city as city_name,pt.typeName as property_type_name,ppm.name as property_price_modifier_name');

 $CI->db->from('property p');
 $CI->db->join('user u', 'p.user_id = u.user_id', 'LEFT'); 
 $CI->db->join('user_associated_agency uaa', 'uaa.user_id = u.user_id', 'LEFT'); 
 $CI->db->join('agency_detail ad', 'ad.agency_id = uaa.agency_id', 'LEFT');
 $CI->db->join('property_category pc', 'p.property_category = pc.property_category_id', 'LEFT'); 
 $CI->db->join('country c', 'p.country = c.countryid', 'LEFT');
 $CI->db->join('country_regions cr', 'p.state = cr.regionid', 'LEFT');
 $CI->db->join('country_region_cities crc', 'p.city = crc.cityId', 'LEFT');
 $CI->db->join('property_types pt', 'p.property_type = pt.property_types_id', 'LEFT');
 $CI->db->join('property_price_modifier ppm', 'p.price_modifier = ppm.property_price_modifier_id', 'LEFT');
 $CI->db->join('property_image pimg', 'pimg.property_id = p.property_id', 'LEFT'); 
 
 $CI->db->where('p.property_id',$property_id); 
 $query = $CI->db->get();
  if($query->num_rows()>0){
      $data =  $query->result_array();
   return $data;
    }else{
   return false;
 }
}
function radio_box($table_name,$where = NULL,$column = '*',$selected = NULL,$filed_name){
 $CI = & get_instance();
 $CI->load->database();
 $sql = "select $column from $table_name";
  if(!empty($where))
     $sql .= " $where";
   $query = $CI->db->query($sql);
   if($query->num_rows()>0){
   $result = $query->result_array();
   $list = '';
   //$filed_name = $filed_name.'[]';

   $selected = explode(",",$selected);

   foreach($result as $val){
     
    if (in_array($val['id'],$selected)){
    $list .= '<input type="radio" checked name="'.$filed_name.'" value="'.$val['id'].'" >&nbsp;'.$val['name'].'&nbsp;';
    }else{
      $list .= '<input type="radio" name="'.$filed_name.'" value="'.$val['id'].'" >&nbsp;'.$val['name'].'&nbsp;';
    }
   }

   return $list;

    }else{

    return false; 

    }
}
function check_box($table_name,$where = NULL,$column = '*',$selected = NULL,$filed_name){
 $CI = & get_instance();
 //$CI->load->database();
 $sql = "select $column from $table_name";
  if(!empty($where))
     $sql .= " $where";
   $query = $CI->db->query($sql);
   if($query->num_rows()>0){
   $result = $query->result_array();
   $list = '';
   //$filed_name = $filed_name.'[]';

   $selected = explode(",",$selected);

   foreach($result as $val){
     
    if (in_array($val['id'],$selected)){
    $list .= '<input type="checkbox" checked name="'.$filed_name.'" value="'.$val['id'].'" >&nbsp;'.$val['name'].'&nbsp;';
    }else{
      $list .= '<input type="checkbox" name="'.$filed_name.'" value="'.$val['id'].'" >&nbsp;'.$val['name'].'&nbsp;';
    }
   }

   return $list;

    }else{

    return false; 

    }
}
function sendUserEmailCIAdmin($to, $from, $subject = '', $body = '', $attachments = array()){
	$CI = & get_instance();
	$CI->load->library('email');
	if(is_array($from)){
		$CI->email->from($from['email'], $from['name']);
	}else{
		$CI->email->from($from);
	}
	$CI->email->to($to);
	//$CI->email->to('lokendra.joshi@techlect.com');
	$CI->email->subject($subject);
	$CI->email->message($body);
	$CI->email->set_mailtype('html');
	if(!empty($attachments))
	{
		foreach($attachments as $attachment){
			$CI->email->attach(config_item('site_url').$attachment);
		}
	}
	return $CI->email->send();
	
}
function address_to_latlng_nearby($address){

  

  $CI = & get_instance();


  $address = urlencode($address);

  $request_url = "http://maps.googleapis.com/maps/api/geocode/xml?address=".$address."&sensor=true";

  $xml = simplexml_load_file($request_url);

  $status = $xml->status;

  if ($status=="OK") {

      $Lat = $xml->result->geometry->location->lat;

      $Lon = $xml->result->geometry->location->lng;

      

 $data = array('latitude' => $Lat,

                'longitude' => $Lon);
   return $data;

  }

  return true;

}
function getUserReplyReview($id){

 $CI = & get_instance();
 
 $CI->db->select('*,c.status as comment_status',false);
 $CI->db->from('comment c');
 $CI->db->join('user u', 'u.user_id = c.target_id', 'LEFT'); 
 $CI->db->where('c.comment_parent_id',$id); 
 $query = $CI->db->get();
  if($query->num_rows()>0){
      $data =  $query->result_array();
 
   return $data;
    }else{
   return false;
 }


}
function getUserSubcription($user_id){
  $CI = & get_instance();
  $my_date=$CI->session->userdata('registerDate');

   $CI->db->select('*',false);
   $CI->db->from('paypalPayment pp');
   $CI->db->join('user u', 'u.user_id = pp.user_id', 'LEFT'); 
   $CI->db->where('pp.user_id',$user_id); 
   $CI->db->where('pp.user_plan_status','Active');
   $CI->db->where('pp.subscribe_type','membership'); 
   $query = $CI->db->get();

  
  if($query->num_rows()>0){
      $data =  $query->result_array();
	  $msg = array("subscription_setting" => array(
		                                          "month"=>"",
		                                          "subscription"=>"yes"
												  ) 
		                ); 
    }else{
		// true if my_date is more than a month ago
		if(strtotime($my_date) < strtotime('1 month ago')){
		   $msg = array("subscription_setting" => array(
		                                          "month"=>"exceed",
		                                          "subscription"=>"no"
												  ) 
		                ); 
			  
		}else{
		    $msg = array("subscription_setting" => array(
		                                          "month"=>"notexceed",
		                                          "subscription"=>"no"
												  )
		                ); 
		}  
   }
   
   return $msg;
    
}
function getPropertyCountPackage($user_id,$start_date='2015-09-14'){
    
     $todayDate=date('Y-m-d');
 
	 $CI = & get_instance();
	  //$CI->load->database();
					
	  $sql = "select * from property where user_id='$user_id' and createdDate BETWEEN '$start_date' AND '$todayDate'";
	 
	  $query = $CI->db->query($sql);
	  if($query->num_rows()>0){
		 $result = $query->num_rows();
		 return $result;
	   }else{
		  return false; 
	  }
}
function getTeamCountPackage($user_id,$start_date='2015-09-14'){
    
     $todayDate=date('Y-m-d');
 
	 $CI = & get_instance();
	  //$CI->load->database();
					
	  $sql = "select * from user where created_by='$user_id' and registerDate BETWEEN '$start_date' AND '$todayDate'";
	  
	  $query = $CI->db->query($sql);
	  if($query->num_rows()>0){
		 $result = $query->num_rows();
		 return $result;
	   }else{
		  return false; 
	  }
}
function getUserPackageData($user_id){

   $CI = & get_instance();
   $CI->db->select('pp.start_date,pp.end_date,mp.propertyCount,mp.teamCount',false);
   $CI->db->from('paypalPayment pp');
   $CI->db->join('membership_plan mp', 'mp.plan_id = pp.mem_plan_id', 'LEFT');  
   $CI->db->where('pp.user_id',$user_id);
   
   $query = $CI->db->get();

   if($query->num_rows()>0){
	  $result =  $query->result_array();
	  return $result;
	  
   }else{
		  return false; 
   }	  

}
function mydashboardstatsData($user_id='',$type=''){
   $CI = & get_instance();
   
   switch($type){
     
	         case "property":

                 $sql = "select * from property where user_id='$user_id'";

                 break;

              case "enquiry":

                 $sql = "select * from free_valuation where receiver_id='$user_id'";

                 break; 
				 
			   case "teammember":

                 $sql = "select * from user where created_by='$user_id'";

                 break; 	 
				 
   
   }
   
   
	  $query = $CI->db->query($sql);
	  if($query->num_rows()>0){
		 
		 return $query->num_rows();
	   }else{
		  return 0; 
	  }
   

}
 function packagepagecheck($user_id){
	
	  $userPackage=getUserPackageData($user_id);
	
	  $propertyCount=getPropertyCountPackage($user_id,$userPackage[0]['start_date']);
	
	  $property_package_count=$userPackage[0]['propertyCount'];
	  $end_date=$userPackage[0]['end_date'];
	  $todayDate=date('Y-m-d');
	
	
	
	    $packagesetting=getUserSubcription($user_id);
	
	    $subscription=$packagesetting['subscription_setting']['subscription'];
	    $month=$packagesetting['subscription_setting']['month'];
	     
		 
	
	   if(!empty($subscription)){
		  
		  if(($subscription=='no')&&($month=='exceed')){
		     $msg = array('msg'=>'Your Free Subscription Expire Please Purchase a <a href="#" title="click here">Subscription!</a>','subscription'=>$subscription); /*Expire Your Month(Free Users)*/
		 
		  }else{
		   
			   if(($subscription=='yes')&&($end_date<$todayDate)){

				$msg = array('msg'=>'Your Subscription Expire Please Purchase a new <a href="#" title="click here">Subscription!</a>','subscription'=>$subscription);
				
				 /*Expire Your Package(For Renew Package )*/
			   }
			   else{
			     if(!empty($userPackage)){
					 if($propertyCount>=$property_package_count){
					 
					   $msg = array('msg'=>'You have Reached Limit for Add Property.Please Purchase a new <a href="#" title="click here">Subscription!</a>','subscription'=>$subscription);
					 
					   /*Reached Maximum Limit*/
					 }else{
					    $msg = array('msg'=>'','subscription'=>$subscription);
					 }
				 }else{
				     $msg = array('msg'=>'','subscription'=>$subscription);
				 }	 
			   }
		  }
		   
		}else{

		 $msg;
		
		}
		
		return $msg;
	
	}
	
	function userProfileUpdateMessage($user_id){
   
    $CI = & get_instance();
  
    $info =  select('user',"where user_id='$user_id'");

	  
	$lastName = $info[0]['lastName'];
	$firstName = $info[0]['firstName'];
	$profile_image = $info[0]['profile_image'];
	$email = $info[0]['email'];
    
	$data['admin_fullname'] = $CI->session->userdata('fullName');
    $data['admin_firstName']=$CI->session->userdata('name');   
	$data['admin_profile_image']=$CI->session->userdata('profile_image');  
	
	$username=ucwords($firstName.' '.$lastName);
    $seousername = str_replace('&nbsp;', '-', $username);
 
    $data['recieverseo'] = seo_friendly_urls($seousername,'',$post_array['user_id']);
	
	
    $seosendername = str_replace('&nbsp;', '-', ucwords($data['admin_fullname']));
 
    $data['senderseo'] = seo_friendly_urls($seosendername,'',$CI->session->userdata('user_id'));
	
	$sessionId=$CI->session->userdata('user_id');
	if($user_id==$sessionId){
	  $data['self']='self';
	  $subject = ucwords(config_item('site_name').' you update your profile');
	}else{
	  $subject = ucwords(config_item('site_name').' '.ucwords($data['admin_fullname']).' update your profile');
	}
   
	$data['fullname'] = $firstName.' ' .$lastName;
	$data['firstname'] = $firstName;
	$data['email'] = $email;
	$data['profile_image'] = $profile_image;
	$message = $CI->load->view('my_account/message/template/profile_update_message', $data, TRUE); 
	$toEmail = $email;
	$fromEmail = array('email' => config_item('no_reply'),'name' => config_item('site_name'));
	
	$attachment = array();
	$result = sendUserEmailCI($toEmail, $fromEmail, $subject, $message, $attachment);

}
function useremailVerifyMessage($user_id){
   
    $CI = & get_instance();
	$CI->output->enable_profiler(TRUE);
	$CI->load->library('encrypt');
	$key = config_item('encryption_key');
  
    $info =  select('user',"where user_id='$user_id'");

	  
	$lastName = $info[0]['lastName'];
	$firstName = $info[0]['firstName'];
	$profile_image = $info[0]['profile_image'];
	$email = $info[0]['email'];
	
	$password=mt_rand();
	$encpassword = $CI->encrypt->encode($password, $key);
	$activation_code = mt_rand();
	
	$data = array(
               'password' => $encpassword,
               'activation_code' => $activation_code
            );

    $CI->db->where('user_id', $user_id);
    $CI->db->update('user', $data); 
	
	
	
	$username=ucwords($firstName.' '.$lastName);
    $seousername = str_replace('&nbsp;', '-', $username);
 
    $data['recieverseo'] = seo_friendly_urls($seousername,'',$user_id);
	
   
	$data['fullname'] = $firstName.' ' .$lastName;
	$data['firstname'] = $firstName;
	$data['email'] = $email;
	$data['password'] = $password;
	$data['activation_code'] = $activation_code;
	$data['profile_image'] = $profile_image;
	$subject = config_item('site_name').' Registration';
	$message = $CI->load->view('my_account/message/template/registration_message', $data, TRUE);
	$toEmail = $email;
	$fromEmail = array('email' => config_item('no_reply'),'name' => config_item('site_name'));
	
	$attachment = array();
	$result = sendUserEmailCI($toEmail, $fromEmail, $subject, $message, $attachment);
	
	return true;

}
?>