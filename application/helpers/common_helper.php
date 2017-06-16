<?php
function get_type_of_expert($select=''){
	$CI = & get_instance();	
	$count = "SELECT * FROM manage_expert_search_from_permission";
	$query = $CI->db->query($count);
	if($query->num_rows() > 0){
		  $results =  $query->result_array();
		  $expert_group_id = $results[0]['expert_group_id'];
		  
		  $count = "SELECT * FROM user_group where group_id IN ($expert_group_id) ";
	      $result = $CI->db->query($count);
		  $result = $result->result_array();
		  	foreach($result as $value){
					$selected = ($select==$value['group_id'])?'SELECTED':'';
					$option .= '<option '.$selected.' value="'.$value['group_id'].'">'.$value['groupName'].'</option>'."\n";			

			}
			return $option;
	 	}else{
	    	return false; 
	 	}
		
	}
  

function login_attempt_count($email) {
	$CI = & get_instance();		
	try {
		$seconds = 30;
		$oldest = strtotime(date("Y-m-d H:i:s")." - ".$seconds." minutes");
		$oldest = date("Y-m-d H:i:s",$oldest);

		
		// First we delete old attempts from the table
		$sql = "DELETE FROM login_attempts WHERE attempt_time < '".$oldest."'";
		$CI->db->query($sql);
		
		
		$ip = $_SERVER['REMOTE_ADDR'];
		$time = date("Y-m-d H:i:s");
		// Next we insert this attempt into the table
	$insert = "INSERT INTO login_attempts (`ip`, `attempt_time`) VALUES ('".$ip."','".$time."')";
		$CI->db->query($insert);
		
		// Finally we count the number of recent attempts from this ip address	
		$count = "SELECT * FROM login_attempts where `ip` = '".$ip."'";
		$query = $CI->db->query($count);
		$attempts = $query->num_rows(); 
		if($attempts > 3){
			 $sql = "UPDATE user SET status = 'Deactivate' WHERE email = '".$email."'";
		     $CI->db->query($sql);
			 /* site block*/
			 $insert = "INSERT INTO ip_block (`ip`) VALUES ('".$ip."')";
		     $CI->db->query($insert);
			 
			    $userdata=selectData('user',"where email='$email'");
				
			    $username=ucwords($userdata[0]['firstName'].' '.$userdata[0]['lastName']);
                $seousername = str_replace('&nbsp;', '-', $username);
                $data['recieverseo'] = seo_friendly_urls($seousername,'',$userdata[0]['user_id']);
			 
			    $data['fullname'] = $username;
				$data['firstname'] = $userdata[0]['firstName'];
				$data['profile_image'] = $userdata[0]['profile_image'];
			 
			    $message = $CI->load->view('my_account/message/template/account_status_deactive_message', $data, TRUE); 
				$toEmail = $email;
				$fromEmail = array('email' => config_item('no_reply'),'name' => config_item('site_name'));
				$subject = ucwords(config_item('site_name').' - Your Account Blocked');
				$result = sendUserEmailCI($toEmail, $fromEmail, $subject, $message, $attachment);
				
				
				 $sql = "select * from website_setting";
	             $query = $CI->db->query($sql);
		
				 if($query->num_rows()>0){
					 $adminresult =  $query->result_array();
				 }
				
				if(!empty($adminresult)){
					    $data['fullname'] = 'Otriga Administrator';
					    $data['firstname'] = 'Otriga Administrator';
						$data['recieverseo']='';
					    $data['profile_image'] = '';
						$data['user']=$username;
						$data['userip']=$ip;
						
						
						
						$message = $CI->load->view('my_account/message/template/ipblock_admin_message', $data, TRUE); 
						$toEmail = $adminresult[0]['email'];
						$fromEmail = array('email' => config_item('no_reply'),'name' => config_item('site_name'));
						$subject = ucwords(config_item('site_name').' - Account Blocked');
						$result = sendUserEmailCI($toEmail, $fromEmail, $subject, $message, $attachment);
			   }
				
				
			 
			 
			 
		}
	    return $attempts;
	} catch (Exception $e) {
		return false;
	}
}

function displayAdvertise($page_id, $position_id){
	$CI = & get_instance();	
	//$CI->output->enable_profiler(TRUE);	
	$CI->db->where(array('page_id'=>$page_id, 'position_id'=>$position_id,'status'=>'Active','unpublish_date >'=>date('Y-m-d')));
	$CI->db->order_by('advert_id', 'random');
    $CI->db->limit(1);
	$result = $CI->db->get('manage_advert');
	$content = '';
	if($result->num_rows() > 0){			
		foreach($result->result_array() as $data){
			 $advert_id = $data['advert_id'];
			$content .= '<a onclick="advert(\''.safe_b64encode($advert_id).'\');" href="javascript:void(0);">';
			$content .= '<div class="advert-container">';
			$mainFIle = base_url('applicationMediaFiles/advertImage/'.$data['advert_content']);				
			if(@getimagesize($mainFIle)){
				$content .= '<img src="'.$mainFIle.'" alt="">';
			}
			//$content .= '<div class="advert-container-overlay"></div>';
			$content .= '</div>';
			$content .= '</a>';
		  
		  $sql = "UPDATE manage_advert SET impresion = impresion + 1 WHERE advert_id = $advert_id";
		  $CI->db->query($sql);
		   
   
		}
	}
	return $content;	
}
function seo_friendly_urls($firstName,$lastName,$user_id='')
{ 
    $text = '';
    if(!empty($firstName)){
	  $text .= $firstName;
	}
	 if(!empty($firstName)){
	  $text .= ' '.$lastName;
	}
	 
  // replace non letter or digits by -
  $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
  // trim
  $text = trim($text, '-');
  // transliterate
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
  // lowercase
  $text = strtolower($text);
  // remove unwanted characters
  $text = preg_replace('~[^-\w]+~', '', $text);
  if (empty($text))
  {
    return FALSE;
  }
  
  if(!empty($user_id)){
  
  $encode_id = safe_b64encode($user_id);
  $encode = $encode_id.'/'.$text;
  
  }else{
   $encode = $text;
  }
  return $encode;
}
function currency_droupdown(){
	$CI = & get_instance();

	$websetting = $CI->session->userdata('websetting');
	$default_currency = $websetting['currency'] ? $websetting['currency'] : 'GBP';
	$default_symbol = $websetting['currency_symbol'] ? $websetting['currency_symbol'] : '&#163;';
	
	$currency = $CI->session->userdata('currency') ? $CI->session->userdata('currency') : $default_currency;
	$currency_symbol = $CI->session->userdata('currency_symbol') ? $CI->session->userdata('currency_symbol') :$default_symbol;
	$result = select_query('manage_currencies', " where status = 'Active'");
	$list = '';
	if(!empty($result)){
		$list .= '<select name="currency" onchange="this.form.submit();" class="tech" id="currency">';
		foreach($result as $val){
			$currency_key = $val['code'];
			$currency_title = $val['title'];
			$currency_symbol = $val['symbol'];
			$sel = '';
			if($currency == $currency_key){
				$sel = "selected='selected'";			  
			}
			$list .= "<option ".$sel."  value='".$currency_key.'_'.$currency_symbol."' data-image='".base_url("assets/plugins/jQuery-Dropdown/images/msdropdown/icons/blank.gif")."' data-imagecss='flag ".substr(strtolower($currency_key), 0, -1)."' >".$currency_symbol .'&nbsp;&nbsp;'. $currency_title."</option>";
		}
		$list .= "</select>"; 
		return $list;
	}
}
function setCurrency(){
	$CI = & get_instance();
	$websetting = $CI->session->userdata('websetting');
	$default_currency = $websetting['currency'] ? $websetting['currency'] : 'GBP';
	$currency_symbol = $websetting['currency_symbol'] ? $websetting['currency_symbol'] : '&#163;';
	
	$user_set_currency = $CI->session->userdata('currency') ? $CI->session->userdata('currency') : $default_currency;
	$currency_symbol = $CI->session->userdata('currency_symbol') ? $CI->session->userdata('currency_symbol') : $currency_symbol;
	
	if($default_currency != $user_set_currency){
		$url = "http://www.google.com/finance/converter?a=1&from=".$default_currency."&to=".$user_set_currency; 
		$request = curl_init();
		$timeOut = 0;
		curl_setopt ($request, CURLOPT_URL, $url);
		curl_setopt ($request, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($request, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
		curl_setopt ($request, CURLOPT_CONNECTTIMEOUT, $timeOut);
		$response = curl_exec($request); 
		curl_close($request);
		$regularExpression = '#\<span class=bld\>(.+?)\<\/span\>#s';
		preg_match($regularExpression, $response, $finalData);
		$ccode = strtolower($currency_code);
		$current_rate = preg_replace("/[^0-9.]/", "", $finalData[0]);
		$CI->session->set_userdata('currency', $user_set_currency);
		$CI->session->set_userdata('current_rate', $current_rate);
		$CI->session->set_userdata('currency_symbol', $currency_symbol);		
	}else{
		$CI->session->set_userdata('currency', $user_set_currency);
		$CI->session->set_userdata('currency_symbol', $currency_symbol);
	}
		
}
function convert_currency($amount = NULL){
	$CI = & get_instance();		
	$websetting = $CI->session->userdata('websetting');
	$default_currency = $websetting['currency'] ? $websetting['currency'] : 'GBP';
	$user_set_currency = $CI->session->userdata('currency') ? $CI->session->userdata('currency') : $default_currency;
	
	
	
	
	$current_rate = $CI->session->userdata('current_rate');
	$currency_symbol = $CI->session->userdata('currency_symbol');
	
	if(($default_currency != $user_set_currency) && !empty($amount) && !empty($current_rate)){
		$amount = str_replace(",", "", $amount);		
		$value = $amount*$current_rate;			
		//return $currency_symbol.'&nbsp;'.getnumformat($value);	
		return getnumformat($value);		
	}else{
		return getnumformat($amount);			
	}
}
function attachCurrencySymbol($amount){
	$CI = & get_instance();
	
	$websetting = $CI->session->userdata('websetting');
	$currency_symbol = $websetting['currency_symbol'] ? $websetting['currency_symbol'] : '&#163;';
	$currency_symbol = $CI->session->userdata('currency_symbol') ? $CI->session->userdata('currency_symbol') : $currency_symbol;
	$amount = $currency_symbol.'&nbsp;'.$amount;
	return $amount;
}
function set_property_features($table,$where='',$name){
	  $CI = & get_instance();
	 // $CI->load->database();
	  $sql = "select * from $table";
	  if(!empty($where))
	    $sql .= " $where";
	  $query = $CI->db->query($sql);
	  if($query->num_rows()>0){
		 $result = $query->result_array();
		
		 $list = "";
		 foreach($result as $val){
			 
			$list .= " <label class='label label-info'>".$val[$name]."</label>"; 
			}
			 //$list = "</div>";
			return $list;
	   }else{
		  return false; 
	  }
	
}
function select_query($table, $where=''){
	  $CI = & get_instance();
	 // $CI->load->database();	  
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
// 29-June Sonali Add functions 
function getStatesfront($id,$sel_id){
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
	
function getCitiesfront($id,$sel_id){
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
		
function user_country_get(){
	$CI = & get_instance();
	$ip = $_SERVER['REMOTE_ADDR'];
	
	
	$count = "SELECT * FROM ip_block where `ip` = '".$ip."' AND status = 'Active'";
	$query = $CI->db->query($count);
	$attempts = $query->num_rows(); 
	if(!empty($attempts)){
		redirect('blacklist');
		exit;
	
	}
		
		
	//$ip = '110.33.122.75';
	//if($ip != '182.70.247.82')
	//mail("pramod.jain@techlect.com","My subject",$ip);
	$country = $CI->session->userdata('country');
	
	if($country)
	{
		 return true;
	}else{
    
	$latitude = '';
	$longitude = '';
	$details = '';
	$loc = '';
	$country = 'GB';
	$city = '';
	//ini_set('max_execution_time', 30);
  // $ipDetails = @file_get_contents("http://ipinfo.io/{$ip}");
	
	/*$curl = curl_init();
     curl_setopt($curl, CURLOPT_URL, "http://ipinfo.io/$ip/json"); 
	 curl_setopt($curl, CURLOPT_CONNECTTIMEOUT ,0); 
     curl_setopt($curl, CURLOPT_TIMEOUT, 60); //timeout in seconds
	 curl_setopt($curl, CURLOPT_TIMEOUT_MS, 60);
     curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
	$ipDetails = curl_exec($curl);
    curl_close($curl);
	if(!empty($ipDetails)){
	$details = json_decode($ipDetails);
	$loc = $details->loc;
	}
	
	if(!empty($loc)){
		$loc = explode(",", $loc);
		$latitude = $loc[0];
		$longitude = $loc[1];   
$country = $details->country ? strtoupper($details->country) : 'GB';
$city = $details->city;
	}*/

   $query = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip));
   if($query && $query['status'] == 'success') {
  		$country = strtoupper($query['countryCode']);
		$city = $query['city'];
		$latitude = $query['lat'];
		$longitude = $query['lon']; 
   } 

	
	$country_code = $country;
	$CI->db->select('*');
	$CI->db->from('country');
	$CI->db->where('internet',$country);
	$result = $CI->db->get();
	
	$results = $result->result();
	if($result->num_rows()>0){
		$country = $results[0]->country;
		$country_code = $results[0]->internet;
	}else{
		$country = 'United Kingdom';  
	}
	$id_result = array('country'  => $country,
	                   'country_internet'  => $country_code,
					   'city'     => $city,
					   'latitude' => $latitude,
					   'longitude' => $longitude);
					  // print_r($id_result);
						
	$CI->session->set_userdata($id_result);
	}
}
/* user details get */
function getUserInformation($id = '',$type = 'user'){
        $CI = & get_instance();
		//$CI->load->database();
        if(empty($id)){
		   $id = $CI->session->userdata('user_id');
		}
		$user_id = 'user_id';
		if($type != 'user'){
			$user_id = 'agency_id';
			}
		
		$CI->db->select('*');
		$CI->db->from($type);
		$CI->db->where($user_id,$id);
		
		$result = $CI->db->get();
	    $resultData=  $result->result_array();
	 
	    if($result->num_rows() == 1)
		{
		   return $resultData;
		}
		else{ return false;	}
        
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
function sendUserEmailCI($to, $from, $subject = '', $body = '', $attachments = array(),$filePath='',$emailtype=''){
	$CI = & get_instance();
	$config = array();
	$config['useragent']	= "CodeIgniter";
	$config['mailpath'] 	= "/usr/bin/sendmail"; // or "/usr/sbin/sendmail"
	$config['protocol'] 	= "smtp";
	$config['smtp_host']	= "localhost";
	$config['smtp_port']	= "25";
	$config['mailtype']		= 'html';
	$config['charset'] 		= 'utf-8';
	$config['newline']  	= "\r\n";
	$config['wordwrap'] 	= TRUE;
	$CI->load->library('email');
	$CI->email->initialize($config);
	
	$websetting = $CI->session->userdata('websetting');
	if(!empty($websetting)){
		$site_email = $websetting['site_email']; 
		$carrier_email = $websetting['career_email']; 
		$support_email = $websetting['support_email']; 
		$site_name = $websetting['site_name']; 
		
	}
	
	if(empty($emailtype)){
	
	$CI->email->from($site_email, $site_name);
	
	}else{
	
		if($emailtype=='support_email'){
		    $CI->email->from($support_email, $site_name);
		}else{
		   	$CI->email->from($carrier_email, $site_name);
		}
	}
	
	/*if(is_array($from)){
		if($from['email'] == $site_email){
			$CI->email->from($site_email, $site_name);
		}else{
			$CI->email->from($from['email'], $site_name);
		}
	}else{
		if($from == $site_email){
			$CI->email->from($site_email, $site_name);
		}else{
			$CI->email->from($from, $site_name);
		}
	}*/
	
	
	$CI->email->to($to);
	$CI->email->subject($subject);
	$CI->email->message($body);
	if(!empty($attachments))
	{
		foreach($attachments as $attachment){
			$file_path = $filePath ? $filePath : config_item('root_url');
			$CI->email->attach($file_path.$attachment);
		}
	}
	
	$result = $CI->email->send();
	if($result){
	  return $result;
	}else{
	 return false; //echo $CI->email->print_debugger();
	}
	
}
function encrypt_data($data){
  $CI = & get_instance();
  $CI->load->library('encrypt');	 
  $key = $CI->config->item('encryption_key');
  return  $CI->encrypt->encode($data, $key);
}
	 
function decrypt_data($value){
	$CI = & get_instance();
	$CI->load->library('encrypt');	 
	$key = $CI->config->item('encryption_key');
	return  $CI->encrypt->decode($value, $key);
}
function dataLimit($data,$limit,$id = NULL){
 $total=strlen($data);
 $str=substr($data, 0, $limit);
 
 if($total>$limit){
  if($id != NULL){
	  $link = '<a class="btn btn-xs btn-main-color" href="'.base_url($id).'">Read more...</a>';
	  $str= $str.' '.$link; 
	 }else{
   $str=$str.'....';
	 }
 }
 return $str;
}
	
function getPropertyImage($property_id){
	if(empty($property_id)){return false;}
     $CI = & get_instance();
	// $CI->load->database();
$CI->db->select('*');
	$CI->db->from('property_image'); 
	$CI->db->where('property_id',$property_id); 
	$query = $CI->db->get();
	
	if($query->num_rows()>0){
	$data =  $query->result_array();
	return $data;
	}else{
	return false;
	}
}
function getUserProfileImage($image, $url){
	 $filename="$url/$image";
	if(!empty($image)){
		if(@getimagesize($filename)){
		  return '/'.$image ;
		}else{
		  return '/default.png';
     	}
	}else{
	   return '/default.png';
	}
}
function priceListRent($select_val = ''){
	$list = $sel = '';
	for($i=1; $i <= 60; $i++){				 
		if($i <= 10 && $i >= 0 ){
			$price += 100;
		}else if($i >= 10 && $i<= 26){
			$price += 250;
		}else if($i >=26){
			$price += 1000;
		}
	$sel = ($price == $select_val) ? 'selected="selected"' : '';
	$val = convert_currency($price);
	$valueWithSymbol =	attachCurrencySymbol($val);
	$list .= '<option value="'.$price.'" '.$sel.' > '.$valueWithSymbol.' </option>';
	}
	return $list;
}
function priceList($price=''){	
	$list = '';
	for($i=1; $i <= 60; $i++){				 
		if($i <= 25 && $i >= 0 ){
			$price += 10000;
		}else if($i >= 26 && $i<= 35){
			$price += 25000;
		}else if($i >=36 && $i<= 45){
			$price += 100000;
		}else if($i >=46 && $i<= 55){
			$price += 250000;
		}else{
			$price += 500000;
		}		
		$sel = ($price == $select_val) ? 'selected="selected"' : '';
		$val = convert_currency($price);
		$valueWithSymbol =	attachCurrencySymbol($val);
		$list .= '<option value="'.$price.'" '.$sel.' > '.$valueWithSymbol.' </option>';
	}	
	return $list;
}
function numberList($num=''){
	$numdata = array('1'=>'1+', '2'=>'2+', '3'=>'3+', '4'=>'4+', '5'=>'5+', '6'=>'6+', '7'=>'7+', '8'=>'8+','9'=>'9+','10'=>'10+','11'=>'11+','12'=>'12+');
	$list = $sel = '';
	foreach($numdata as $key => $val){
		$sel = ($num == $key)?'selected="selected"':'';
		$list .= '<option value="'.$key.'" '.$sel.' > '.$val.' </option>';			
	}	
	return $list;
}
/**Select Data */
function selectData($table, $where=''){
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
/* Agent info */
function most_active_agent_info($limit,$group_id)
{
$CI = & get_instance();
 $group_id =  $group_id ? $group_id : 5;
 $limit = $limit ? $limit : 5;
		
		 $CI->db->select('u.*,ug.groupName,ug.description,u.user_id as agent_id,ad.agency_id,ad.agency_logo,(SELECT COUNT(cmt.user_id) from comment cmt where cmt.user_id = u.user_id  GROUP BY cmt.user_id) as review_count');
		
		  $CI->db->from('user u');
         $CI->db->join('user_group ug','ug.group_id = u.group_id', 'LEFT');
		  $CI->db->join('user_associated_agency uaa', 'uaa.user_id = u.user_id', 'LEFT'); 
          $CI->db->join('agency_detail ad', 'ad.agency_id = uaa.agency_id', 'LEFT'); 
	 
		 //$CI->db->join('user_profile g', 'u.user_id = g.user_id', 'LEFT'); 
		 $CI->db->where('u.group_id',$group_id);
		 $CI->db->limit($limit);
		 $CI->db->order_by("u.user_id", "random"); 
		 $CI->db->group_by("u.user_id");
		$result =  $CI->db->get();
		 $results = $result->result();
		 return $results;
	
}
function getProperty($property_id, $where = NULL){
    if(empty($property_id)){return false;}
	
 $CI = & get_instance();
// $CI->output->enable_profiler(TRUE);
// $CI->load->database();
 
$CI->db->select('p.*,pauction.property_auction_id,pauction.start_date,pauction.end_date,pauction.auction_min_price,u.firstName as agent_name,u.lastName as agent_last_name,ug.groupName as grp_groupName,ug.description as grp_description,u.user_id,u.created_by,u.profile_image as agent_profile_image,u.email,u.about_us as agent_description,u.phone_number as agent_phone_number,u.profile_image as agent_company_logo,pc.categoryName as category_name,c.country as country_name,cr.region as state_name,crc.city as city_name,pt.typeName as property_type_name,ppm.name as property_price_modifier_name,ad.agency_id,ad.agency_name,ad.agency_about_us,ad.agency_logo,ad.agency_phone_number,ad.agency_cell_number,ad.agency_address,u.user_type,u.country,ad.agency_country,c.internet as property_country,crc.city as property_city,cuser.countryid as countryid_agency,cuser.country as countryname_agency,u.linkden_url,u.gmail_url,u.facebook_url',false);
 $CI->db->from('property p');
 $CI->db->join('user u', 'u.user_id = p.user_id', 'LEFT'); 
 $CI->db->join('user_group ug','ug.group_id = u.group_id', 'LEFT');
 $CI->db->join('user_associated_agency uaa', 'uaa.user_id = u.user_id', 'LEFT'); 
 //$CI->db->join('agency_detail ad', 'ad.agency_id = uaa.agency_id', 'LEFT'); 
 $CI->db->join('agency_detail ad', 'ad.user_id = u.user_id', 'LEFT');
 $CI->db->join('property_category pc', 'pc.property_category_id = p.property_category', 'LEFT');
 $CI->db->join('country c', 'c.countryid = p.country', 'LEFT');
 $CI->db->join('country cuser', 'cuser.countryid = u.country', 'LEFT');
 
 $CI->db->join('country_regions cr', 'cr.regionid = p.state', 'LEFT');
 $CI->db->join('country_region_cities crc', 'crc.cityId = p.city', 'LEFT');
 $CI->db->join('property_types pt', 'pt.property_types_id = p.property_type', 'LEFT');
 $CI->db->join('property_price_modifier ppm', 'ppm.property_price_modifier_id = p.price_modifier', 'LEFT');
 $CI->db->join('property_auction pauction', 'pauction.property_id = p.property_id', 'LEFT');
 $CI->db->where('p.property_id',$property_id); 
 if(!empty($where)){
   $CI->db->where('p.auction_status','0');	 
 }
 $CI->db->group_by("p.property_id");
 $CI->db->where('p.status', 'Active');
 $CI->db->where('p.property_availability', 'available');

	
 $query = $CI->db->get();
  if($query->num_rows()>0){
      $data =  $query->result_array();
 
   return $data;
    }else{
   return false;
 }
}
function getAuctionDetail($property_id, $where = NULL){
    if(empty($property_id)){return false;}
	
 $CI = & get_instance();
// $CI->output->enable_profiler(TRUE);
// $CI->load->database();
 
$CI->db->select('p.*,pauction.property_auction_id,pauction.start_date,pauction.end_date,pauction.auction_min_price,u.firstName as agent_name,u.lastName as agent_last_name,ug.groupName as grp_groupName,ug.description as grp_description,u.user_id,u.created_by,u.profile_image as agent_profile_image,u.email,u.about_us as agent_description,u.phone_number as agent_phone_number,u.profile_image as agent_company_logo,pc.categoryName as category_name,c.country as country_name,cr.region as state_name,crc.city as city_name,pt.typeName as property_type_name,ppm.name as property_price_modifier_name,ad.agency_id,ad.agency_name,ad.agency_about_us,ad.agency_logo,ad.agency_phone_number,ad.agency_cell_number,ad.agency_address,u.user_type,u.country,ad.agency_country,c.internet as property_country,crc.city as property_city,cuser.countryid as countryid_agency,cuser.country as countryname_agency,u.linkden_url,u.gmail_url,u.facebook_url',false);
 $CI->db->from('property p');
 $CI->db->join('user u', 'u.user_id = p.user_id', 'LEFT'); 
 $CI->db->join('user_group ug','ug.group_id = u.group_id', 'LEFT');
 $CI->db->join('user_associated_agency uaa', 'uaa.user_id = u.user_id', 'LEFT'); 
 //$CI->db->join('agency_detail ad', 'ad.agency_id = uaa.agency_id', 'LEFT'); 
 $CI->db->join('agency_detail ad', 'ad.user_id = u.user_id', 'LEFT');
 $CI->db->join('property_category pc', 'pc.property_category_id = p.property_category', 'LEFT');
 $CI->db->join('country c', 'c.countryid = p.country', 'LEFT');
 $CI->db->join('country cuser', 'cuser.countryid = u.country', 'LEFT');
 
 $CI->db->join('country_regions cr', 'cr.regionid = p.state', 'LEFT');
 $CI->db->join('country_region_cities crc', 'crc.cityId = p.city', 'LEFT');
 $CI->db->join('property_types pt', 'pt.property_types_id = p.property_type', 'LEFT');
 $CI->db->join('property_price_modifier ppm', 'ppm.property_price_modifier_id = p.price_modifier', 'LEFT');
 $CI->db->join('property_auction pauction', 'pauction.property_id = p.property_id', 'LEFT');
 $CI->db->where('p.property_id',$property_id); 
 $CI->db->where('p.auction_status','1');
 
 $session_user_id =  $CI->session->userdata('user_id');
 if(empty($session_user_id)){
	$CI->db->where('pauction.publicStatus', 'On'); 
 }
 
 $CI->db->where('pauction.status', 'Active');	
  	 
 $CI->db->group_by("p.property_id");
 $CI->db->where('p.status', 'Active');
 $CI->db->where('p.property_availability', 'available');

	
 $query = $CI->db->get();
  if($query->num_rows()>0){
      $data =  $query->result_array();
 
   return $data;
    }else{
   return false;
 }
}
function getCountryName($id){
	   $CI = & get_instance();
 	   $CI->load->model('Property_model');
	   if(empty($id)){
		 return false;
	   }
	   
	   $result= $CI->Property_model->select('country',"where countryid='$id'");
	   
	   return $result[0]['country'];
}
function getStatesName($id){
	   $CI = & get_instance();
 	   $CI->load->model('Property_model');
	   if(empty($id)){
		 return false;
	   }
	   
	   $result= $CI->Property_model->select('country_regions',"where regionid='$id'");
	   
	   return $result[0]['region'];
}
function getCitiesName($id){
		$CI = & get_instance();
 	    $CI->load->model('Property_model');
	    if(empty($id)){
		 return false;
	   }
	   $result=  $CI->Property_model->select('country_region_cities',"where cityid='$id'");
	   
	   return $result[0]['city'];   
}
function getAllagents($id='',$limit='8',$myid){
 $group_id=getUserGroup($id);
 $CI = & get_instance();
// $sql="SELECT * FROM user WHERE group_id ='$group_id' and user_id!='$id' ORDER BY IF( created_by =$id, -1, created_by ) ASC limit $limit";
  
 if(!empty($myid)){
   $sql="select * from user where created_by='$id' and user_id!='$myid' or user_id='$id' limit $limit";
 }else{ 
   $sql="select * from user where created_by='$id' and user_id!='$id' limit $limit";
 }

 
 $query = $CI->db->query($sql);
 
 
  if($query->num_rows()>0){
      $data =  $query->result_array();  
   return $data;
    }else{
   return false;
 }
}
function get_ageny_id ($uid){
$CI = & get_instance();
		 $CI->db->select('*');
		
		 $CI->db->from('user_parent_group');
		 
		 $CI->db->where('user_id',$uid);
		
		$result =  $CI->db->get();
		 $results = $result->result();
		 return $results[0]->user_parent_id;
}
function countProperty_by_uid($uid, $property_type)
{
 $CI = & get_instance();
 $CI->db->select('*');
 $CI->db->from('property');
 $CI->db->where('user_id',$uid);  
 
   if($property_type!=3){
  $CI->db->where('property_category', $property_type);
  }
    
 $CI->db->where('status','active');
 $query = $CI->db->get();
return $query->num_rows();
}
function get_agent_by_agencyid($uid,$limit){
$CI = & get_instance();
		 $CI->db->select('*');
		
		 $CI->db->from('user_parent_group u');
		   $CI->db->join('user g', 'u.user_id = g.user_id', 'LEFT'); 
		  $CI->db->join('user_profile gp', 'u.user_id = gp.user_id', 'LEFT'); 
		 $CI->db->where('u.user_parent_id',$uid);
		 $CI->db->limit($limit);
		$result =  $CI->db->get();
		 $results = $result->result();
		 //print_r($results);die();
		 return $results;
}
/* date time ago */
function ago($timestamp = ''){
 if(empty($timestamp)){
  return false;
 }
 // $difference = time() - strtotime($timestamp);
  $difference = time() - $timestamp;
  $periods = array('second', 'minute', 'hour', 'day', 'week', 'month', 'years', 'decade');
  $lengths = array('60', '60', '24', '7', '4.35', '12', '10');
  for($j = 0; $difference >= $lengths[$j]; $j++) $difference /= $lengths[$j];
  $difference = round($difference);
  if($difference != 1) $periods[$j] .= "s";
  return "$difference $periods[$j] ago";
}
function get_time_ago($datetime){
	
	$time = strtotime($datetime);
	$time = time() - $time; // to get the time since that moment	
	$tokens = array (
		31536000 => 'year',
		2592000 => 'month',
		604800 => 'week',
		86400 => 'day',
		3600 => 'hour',
		60 => 'minute',
		1 => 'second'
	);	
	foreach ($tokens as $unit => $text) {
		if ($time < $unit) continue;
		$numberOfUnits = floor($time / $unit);
		return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'').' ago';
	}
}
  function safe_b64encode($string) {
 
        $data = base64_encode($string);
        $data = str_replace(array('+','/','='),array('-','_',''),$data);
        return $data;
    }
 
  function safe_b64decode($string) {
        $data = str_replace(array('-','_'),array('+','/'),$string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }
	
	function getCityNamelike($nm){
		$CI = & get_instance();
 	  	$CI->db->select('cityId'); 
	  	$CI->db->like('city',$nm); 
	    $CI->db->from('country_region_cities'); 
		$result =  $CI->db->get();
		$results = $result->result_array();
		$manage=array();
		foreach($results as $key => $val)
		{	
		 $manage[]="'".$val["cityId"]."'";
		}	
		$test=implode(',',$manage);
		return $test;
	}
	function defaultCurrency(){
		$CI = & get_instance();
		$websetting = $CI->session->userdata('websetting');
	
		$currency = $CI->session->userdata('currency') ? $CI->session->userdata('currency') : $websetting['currency'];
		return $currency;
	}
	
	function defaultLanguage(){
		$CI = & get_instance();
		$currency = $CI->session->userdata('language') ? $CI->session->userdata('language') : $CI->config->item('language');
		return $currency;
	}
	
	function checkUserLoginId(){
		$CI = & get_instance();
		$user_id = $CI->session->userdata('user_id');
		$email = $CI->session->userdata('email');
		if($user_id ==''){
			redirect(base_url('user/login'), 'refresh');
		}else{
				return $user_id;
		}
	}
	
	function checkUserLogin(){
		$CI = & get_instance();
		$user_id = $CI->session->userdata('user_id');
		$email = $CI->session->userdata('email');
		if($user_id ==''){
			redirect(base_url('user/login'), 'refresh');
		}else{
			if(empty($email)){
				$CI->messageci->set('Please update us your email id.', 'error');
				redirect('mydashboard/editprofile');
			}else{
				return $user_id;
			}
		}
	}
	
	function checkUserAccessPermission($controllerName = '', $method = ''){
		$CI = & get_instance();
		$CI->load->library('permission');
		$controllerName = $CI->router->fetch_class();
		$methodName = $CI->router->fetch_method();
		// set groupID
        $groupID = ($CI->session->userdata('group_id')) ? $CI->session->userdata('group_id') : 0;
		// get permissions and show error if they don't have any permissions at all
		if($CI->permission->check_user_permissions($groupID, $controllerName)){
            return true;
        }else{
			$CI->messageci->set('You do not have permission to access this page!', 'error');
			redirect($_SERVER["HTTP_REFERER"]);
		}			
	}
	
	function get_user_permissions(){
		$CI = & get_instance();
		$CI->load->library('permission');		
		// set groupID
		/*echo '<pre/>';
		$st = $CI->session->userdata();
		print_r($st);*/
        $groupID = ($CI->session->userdata('group_id')) ? $CI->session->userdata('group_id') : 0;
		// get permissions and show error if they don't have any permissions at all
		$permissionNavigation = $CI->permission->get_user_permissions($groupID);
		
		
		return $permissionNavigation;
	}
	
	//this function check file existance and return default file if not exist with complete absolute path.
	// you can use this function everywhere 
	function displayImage($imageName='', $imagePath=''){
			
		if($imageName !='' && $imagePath !=''){
			$filePath = trim($imagePath).DIRECTORY_SEPARATOR.trim($imageName);
			if(@getimagesize($filePath)){
				return $filePath;
			}else{
				return trim($imagePath).DIRECTORY_SEPARATOR.'default.png';
			}
		}
		return trim($imagePath).DIRECTORY_SEPARATOR.'default.png';
	}
	
	// this function return latest picture
	function getCurrentProfilePic($user_id='', $path=''){
		$CI = & get_instance();
		//$CI->load->database();
		$CI->db->select('profile_image');
		$CI->db->from('user'); 
		$CI->db->where('user_id', $user_id); 
		$rslt = $CI->db->get()->result_array();	 
		//echo $rslt[0]['profile_image'];
		return displayImage($rslt[0]['profile_image'], $path);	
	}
	function getBlogCommentsReply($blog_id = NULL){
    $CI = & get_instance();
	//$CI->load->database();	
    if(empty($blog_id)){
       return false;
       }
  $CI->db->select('blgc.blog_comment_id,blgc.blog_parent_id,blgc.blog_id,blgc.blog_comment as comment_reply,blgc.createdDate as reply_date,blgc.blog_comment_id as blog_reply_id,u.user_id,u.firstName as reply_user_fname,u.lastName as reply_user_lname,u.profile_image as reply_user_image');
  $CI->db->from('blog_comment blgc');
  $CI->db->join('user u', 'blgc.user_id = u.user_id', 'LEFT');
  $CI->db->where('blgc.blog_parent_id', $blog_id);
  
  $query = $CI->db->get();
       if($query->num_rows()>0){
  $result =  $query->result_array();
       return  $result;
    }else{ 
      return false;
    }
   } 
function getagenttype($type=''){
	$agent_type = array('both'=>'Both','individual' => 'Individual','team'=>'Team');
	$list = $sel = '';	
	foreach($agent_type as $key => $val){	
		$sel = ($type == $key)?'selected="selected"':'';			
		$list .= '<option value="'.$key.'" '.$sel.' > '.$val.' </option>';			
	}	
	return $list;
}
 
function getUserReviews($target_id){
 $CI = & get_instance();
 if(empty($target_id)){
   return false;
 }
  
 $CI->db->select('*'); 
 $CI->db->from('comment');
 $CI->db->where('target_id', $target_id);
 
 $query = $CI->db->get();
    $review_total= 0;
	$count_total= 0;
	$rating_total= 0;
  
   if($query->num_rows()>0){
    $result =  $query->result_array();
		foreach($result as $val){
		  $review_total=$review_total+$val['total'];
		  $count_total++;
		 }
	}
     
	 $count_total = $count_total ? $count_total : 0; 
    
	if(!empty($review_total)){
	  $rating_total = round($review_total/$count_total,2);
	  $rating_total = $rating_total ? $rating_total : 0;
	}
	
	
	$rating_out_of = $rating_total.'/5 out of '. $count_total;
		
	
 $manage=array('review_total'=>$count_total,'rating_total'=>$rating_total,'rating_out_of' => $rating_out_of);
 return  $manage;
}
 
function nice_number($n) {
        $n = (0+str_replace(",", "", $n));
        if (!is_numeric($n)) return false;
        if ($n > 1000000000000) return round(($n/1000000000000), 2).' trillion';
       elseif ($n > 1000) return number_format($n, 0, '.', ',');
        return number_format($n);
}
function getnumformat($amount=''){
	$amount = number_format($amount);
	return $amount;
}
function getSaleminprice(){
    $list =$val='';	
	for($i=1000;$i<=10000;$i+=1000){
		$val=getnumformat($i);
		$list .= '<option value="'.$val.'"> '.$val.' </option>';			
	}
	return $list;	
}
function getSalemaxprice(){
 $list =$val='';	
	for($i=10000;$i<=1000000;$i+=1000){
		$val=getnumformat($i);				
		$list .= '<option value="'.$val.'"> '.$val.' </option>';			
	}	
	return $list;	
}
function checkPropertyFavourites($user_id,$property_id){
 $CI = & get_instance();
 //$CI->load->database();	
 $sql = "select * from user_property_favorites where user_id = '$user_id' && property_id = '$property_id'";
 $query = $CI->db->query($sql);
	  if($query->num_rows()>0){
		 return '1';
	   }else{
		 return '0'; 
	  }
}
function getprofessionlist($type=''){
	$agent_type = array('5'=>'Agent','4' => 'Agency','6'=>'Solicitor','7'=>'Contractor');
$list = $sel = '';	
	foreach($agent_type as $key => $val){
	
		$sel = ($type == $key)?'selected="selected"':'';			
		$list .= '<option value="'.$key.'" '.$sel.' > '.$val.' </option>';			
	}	
	return $list;
}
function formatPhoneNumber($phoneNumber) {
    $phoneNumber = preg_replace('/[^0-9]/','',$phoneNumber);
    if(strlen($phoneNumber) > 10) {
        $countryCode = substr($phoneNumber, 0, strlen($phoneNumber)-10);
        $areaCode = substr($phoneNumber, -10, 3);
        $nextThree = substr($phoneNumber, -7, 3);
        $lastFour = substr($phoneNumber, -4, 4);
        $phoneNumber = '+'.$countryCode.' ('.$areaCode.') '.$nextThree.'-'.$lastFour;
    }
    else if(strlen($phoneNumber) == 10) {
        $areaCode = substr($phoneNumber, 0, 3);
        $nextThree = substr($phoneNumber, 3, 3);
        $lastFour = substr($phoneNumber, 6, 4);
        $phoneNumber = '('.$areaCode.') '.$nextThree.'-'.$lastFour;
    }
    else if(strlen($phoneNumber) == 7) {
        $nextThree = substr($phoneNumber, 0, 3);
        $lastFour = substr($phoneNumber, 3, 4);
        $phoneNumber = $nextThree.'-'.$lastFour;
    }
    return $phoneNumber;
}
function getagencyDetail($id = NULL){
 $CI = & get_instance();
 //$CI->load->database();	
 if(empty($id)){
   return false;
 }
 
  $CI->db->select('*');
  $CI->db->from('user_associated_agency uaa');
  $CI->db->where('uaa.user_id', $id);
  $CI->db->join('agency_detail ad','uaa.agency_id = ad.agency_id');
  $query = $CI->db->get();
       if($query->num_rows()>0){
  $result =  $query->result_array();
       return  $result;
    }else{ 
      return false;
    }
   }
   
    function getRecentAuction($property_id){
   if(empty($property_id)){return false;}
 $CI = & get_instance();
 //$CI->load->database(); 
 $CI->db->select('usap.*,u.profile_image,u.firstName,u.lastName,u.email',false);
 $CI->db->from('user_set_auction_price usap');
 $CI->db->join('user u', 'u.user_id = usap.user_id', 'LEFT'); 
 $CI->db->limit('10');
 $CI->db->order_by("usap.uset_auction_price_id", "desc");
 
  $CI->db->where('usap.property_id', $property_id);
  $query = $CI->db->get();
  if($query->num_rows()>0){
  $result =  $query->result_array();
       return  $result;
    }else{ 
      return false;
    }
}
function address_to_latlng_detail($address,$where){
  
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
   return $data;
  }
  return true;
}
function replace_strings($data){
	if(!empty($data)){
	
	$data = str_replace('\\r', '', $data);
	$data = str_replace('\\n', '', $data);
	$data = str_replace('\\', '', $data);
	
	return $data;
	
	}else{
	return false;
	}
}
function getUserGroup($id){
$CI = & get_instance();
//$CI->load->database();
	  $sql = "select group_id from user where user_id='$id'";
	 
		$query = $CI->db->query($sql);
	  if($query->num_rows()>0){
		 $result = $query->result_array();
		 return $result[0]['group_id'];
	   }else{
		  return false;
	   }
}
// Propert features data searching
	function search_array($array, $key, $value) {
	    $return = array();   
		 foreach ($array as $k=>$subarray){  
		  	if (isset($subarray[$key]) && $subarray[$key] == $value) {
			  $return[$k] = $subarray;
			}
		 }
		 
		 if(empty($return))
			  return false;
		 else
			  return $return;
	 }
	 
// Get Propert parent feature name	 
	function getPropertFeatureName($id,$table)
	{
		$CI = & get_instance();
		$result = $CI->db->get_where($table,array('manage_features_id'=>$id))->row();
		if(!empty($result))
		{
			$resultData = $result->features_name;
			return $resultData;
		}
	else{return false;}
	} 	
	
	function getmarked_by_company($user_id){
		 $CI = & get_instance();
		 $CI->db->select('ad.*,u.user_id,u.firstName,u.lastName,u.user_type');
		 $CI->db->from('user u');
		 $CI->db->join('agency_detail ad','ad.user_id = u.user_id','LEFT');
		 $CI->db->where('u.user_id', $user_id); 
		 $query = $CI->db->get();
		 if($query->num_rows()>0){
		  $result =  $query->result_array();
		   $created_by = $result[0]['created_by'];
		   if($created_by > 0){
               $CI->db->select('ad.*,u.user_id,u.firstName,u.lastName,u.user_type');
		       $CI->db->from('user u');
		       $CI->db->join('agency_detail ad','ad.user_id = u.created_by','LEFT');
			   $CI->db->where('u.user_id', $user_id); 
			   $query = $CI->db->get();
		       $result =  $query->result_array();
			   foreach($result as $val){
				   print_r($val);
				   $user_id = $val['user_id'];
				   $firstName = $val['firstName'];
				   $lastName = $val['lastName'];
				   $seo_url = seo_friendly_urls($firstName,$lastName,$user_id);
                   
				   $user_type = $val['user_type'];
				   $marketed_by = 'Associated Agency Information';
				  /* if($user_type == 'team'){
					 $marketed_by = 'Agency Information';  
					   }*/
				   
				  $agency_logo = $val['agency_logo'];
$agency_logo=getUserProfileImage($agency_logo,base_url('applicationMediaFiles/companyImage/9999/'));
$agency_logo= base_url('applicationMediaFiles/companyImage/9999/'.$agency_logo);				  
				   $manage[] =  array('agency_name' => $val['agency_name'],
				                      'agency_email' => $val['agency_email'],
									  'agency_phone_number' => $val['agency_phone_number'],
									  'agency_cell_number' => $val['agency_cell_number'],
									  'agency_fax' => $val['agency_fax'],
									  'agency_website' => $val['agency_website'],
									  'agency_address' => $val['agency_address'],
									  'agency_establish' => $val['agency_establish'],
									  'agency_logo' => $agency_logo,
									  'marketed_by' => $marketed_by,
									  
									  'seo_url' => $seo_url);
				   }
				   return  $manage;
			}else{
			 foreach($result as $val){
				 
				 $user_id = $val['user_id'];
				   $firstName = $val['firstName'];
				   $lastName = $val['lastName'];
				   $seo_url = seo_friendly_urls($firstName,$lastName,$user_id);
                   
				   $user_type = $val['user_type'];
				   $marketed_by = 'Associated Agency Information';
				   if($user_type == 'team'){
					 $marketed_by = 'Agency Information';  
					   }
				   
				   
				   $agency_logo = $val['agency_logo'];
$agency_logo=getUserProfileImage($agency_logo,base_url('applicationMediaFiles/companyImage/9999/'));
$agency_logo= base_url('applicationMediaFiles/companyImage/9999/'.$agency_logo);
				   $manage[] =  array('agency_name' => $val['agency_name'],
				                      'agency_email' => $val['agency_email'],
									  'agency_phone_number' => $val['agency_phone_number'],
									  'agency_cell_number' => $val['agency_cell_number'],
									  'agency_fax' => $val['agency_fax'],
									  'agency_website' => $val['agency_website'],
									  'agency_address' => $val['agency_address'],
									  'agency_establish' => $val['agency_establish'],
									  'agency_logo' => $agency_logo,
									  'marketed_by' => $marketed_by,
									  'seo_url' => $seo_url);
				   }
				   return  $manage;
			}
	}else{ 
	   return false;
	}
  }
	function getPropertyfeaturesArray(){
	
	 $CI = & get_instance();
	 
	 $sql="select*from manage_property_features where status='Active'";
	 
	 
	 $query = $CI->db->query($sql);
	  if($query->num_rows()>0){
		 $result = $query->result_array();
		 
		  $manage=array();
		 foreach($result as $res){
		 
		   if($res['features_parent_id']==0){
		     $manage['parent'][]=array(
			                    'manage_features_id'=>$res['manage_features_id'],
								'features_name'=>$res['features_name'],
								'features_parent_id'=>$res['features_parent_id']    
			 
			                    );
								
						
		   }
		   
		 
		 }
		 
		  foreach($result as $res){
		 
		   if($res['features_parent_id']>0){
		     $manage['child'][]=array(
			                    'manage_features_id'=>$res['manage_features_id'],
								'features_name'=>$res['features_name'],
								'features_parent_id'=>$res['features_parent_id']   
			 
			                    );
								
						
		   }
		  
		 
		 }
          return $manage;		
		 
	   }
	 
	}
	
/*function getFooterLink()
{
 $CI = & get_instance();
 $CI->db->select('mspc.page_cat_id as cat_id ,mspc.category_title as cat_title,msp.page_name,msp.link_url,msp.link_type,msp.static_pages_id');
 $CI->db->from('manage_static_page_category mspc');
 $CI->db->join('manage_static_pages msp','msp.category_id = mspc.page_cat_id','LEFT');
 $CI->db->where('mspc.status','Active');
 $CI->db->where('msp.status','Active');
 $result = $CI->db->get()->result_array();
 if(!empty($result)){return $result;}else{return false;}
}
*/

function getFooterLink()
{
 $CI = & get_instance();
 $CI->db->select('mspc.page_cat_id as cat_id ,mspc.category_title as cat_title,msp.page_name,msp.link_url,msp.link_type,msp.static_pages_id');
 $CI->db->from('manage_static_page_category mspc');
 $CI->db->join('manage_static_pages msp','msp.category_id = mspc.page_cat_id','LEFT');
 $CI->db->where('mspc.status','Active');
 $CI->db->where('msp.status','Active');
 $CI->db->order_by("msp.page_order", "asc");
 $result = $CI->db->get()->result_array();
	if(!empty($result)){
	$manage = array();
	foreach($result as $val){
		$category_title = $val['cat_title'];
	    $manage[$category_title][] = array("cat_id" => $val['cat_id'],
											"cat_title" => $val['cat_title'],
											"page_name" => $val['page_name'],
											"link_url" => $val['link_url'],
											"link_type" => $val['link_type'],
											"static_pages_id" => $val['static_pages_id']);	
	}
	return $manage;
	}else{return false;
	}
}

function features_check_box($result,$selected = NULL,$filed_name){ 
	$selected = explode(",",$selected);
	if(!empty($result)){
		foreach($result as $val){	
			if(in_array($val['manage_features_id'],$selected)) $checked = 'checked="checked"'; else $checked = '';
			$list .= '<label class="checkbox-inline"><input type="checkbox" class="square-red" name="'.$filed_name.'" value="'.$val['manage_features_id'].'" '.$checked.'>'.$val['features_name'].'</label>';	
		}
	}
	return $list;
}

function filter_features_check_box($result,$selected = NULL,$filed_name,$row=12){ 
	$selected = explode(",",$selected);
	$list = '';
	if(!empty($result)){
		$list='<div class="row hiddenMoreOptions" style="display:none;"><div class="col-md-12">';		
		foreach($result as $val){
			if(in_array($val['manage_features_id'], $selected))$checked = 'checked'; else $checked = '';
			
			$list .= '<label class="checkbox-inline no-margin-right col-md-'.$row.' col-sm-'.$row.'"><input type="checkbox" class="red" name="'.$filed_name.'" value="'.$val['manage_features_id'].'" '.$checked.'>'.$val['features_name'].'</label>';
			
		}
		$list .= '</div></div>';	
	} 
	return $list;    
}
function getPhoneNumberFormat($id=''){
  if(!empty($id)){
	  $data=selectData('country',"where countryid='$id'");
	  
	  if(!empty($data[0]['calling_code'])){
		return '+'.$data[0]['calling_code'];
	  }else{
		return '+44';
	  }
  }else{
      return '+44';
  }
}
function getPropertyData($propertyID)
{
	$CI = & get_instance();	
	$CI->db->select('p.property_id,
	(SELECT COUNT(upvc.property_id) from user_property_views upvc where p.property_id = upvc.property_id) as total_visits,
	(SELECT COUNT(fv.property_id) from free_valuation fv where p.property_id = fv.property_id) as freevalution,
	(SELECT COUNT( DISTINCT upvc.user_id ) FROM user_property_views upvc WHERE p.property_id = upvc.property_id) as unique_visits,
	(SELECT COUNT(upf.property_id) from user_property_favorites upf where upf.property_id = p.property_id GROUP BY upf.property_id ) as property_favorites_count',false);
	$CI->db->from('property p');
	$CI->db->where('p.property_id',$propertyID);
	
	//return $CI->db->last_query(); 
	$result = $CI->db->get()->result_array();
	return $result;
	
}
function gethowitworksLink()
{
 $CI = & get_instance();
 $CI->db->select('mspc.page_cat_id as cat_id ,mspc.category_title as cat_title,msp.page_name,msp.link_url,msp.link_type,msp.static_pages_id');
 $CI->db->from('manage_static_page_category mspc');
 $CI->db->join('manage_static_pages msp','msp.category_id = mspc.page_cat_id','LEFT');
 $CI->db->where('mspc.status','Active');
 $CI->db->where('msp.status','Active');
 
 $CI->db->where('msp.static_pages_id','12');
 $result = $CI->db->get()->result_array();
 if(!empty($result)){return $result;}else{return false;}
}
function countsoldProperty_by_uid($uid)
{
 $CI = & get_instance();
 $CI->db->select('*');
 $CI->db->from('property');
 $CI->db->where('user_id',$uid);  
 $CI->db->where('property_availability', 'sold');
 $CI->db->where('status','active');
 $query = $CI->db->get();
return $query->num_rows();
}
function get_country_code($address){

  

  $address = urlencode($address);

  $request_url = "http://maps.googleapis.com/maps/api/geocode/xml?address=".$address."&sensor=true";

  $xml = simplexml_load_file($request_url);

  $status = $xml->status;

  if ($status=="OK") {

 $address_component = $xml->result->address_component;

  if(!empty($address_component)){

  foreach($address_component as $val){

  

  if($val->type[0] == 'country'){

   return $val->short_name;

   }

   }

  }

   }

  

  return false;

}

function getpackagePlanfeatures($plan_id)
	{
	
	  $CI = & get_instance();
	 	$CI->db->select('*');
		$CI->db->from('membership_plan_features mpf');
		$CI->db->join('membership_features mf', 'mf.features_id=mpf.features_id', 'LEFT');
		$CI->db->where('mpf.plan_id',$plan_id);
		$CI->db->order_by("mpf.priority", "ASC"); 
		$CI->db->where('mf.status', 'Active');
		$result = $CI->db->get()->result_array();
		
		return $result;
		
	}
	
function getdiscountprice($discount_type,$discount_price,$actual_price){

	if($discount_type=='Fixed'){
	  $newprice=$actual_price-$discount_price;
	}else{
	  $newprice=($actual_price*(100-$discount_price))/100;
	}
	
	return $newprice;
}	
function updateAbroadpropertystatus($id){
   $CI = & get_instance();
   
   $data = array('status' => 'Inactive');
   $CI->db->where('id', $id);
   $CI->db->update('property_find_abroad', $data); 
   return;	
}
?>