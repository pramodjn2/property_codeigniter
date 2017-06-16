<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Property extends CI_Controller {  
    public function __construct(){  
		parent::__construct();
		$this->load->database();
		$this->load->model(array('Property_listing_model', 'Common'));
		user_country_get();
      // $this->output->enable_profiler(TRUE);
		//print_r($_SESSION);
    } 
	public function index(){
	  $this->load->view('listing');	
	}	
	public function listing(){
	 //$this->output->enable_profiler(TRUE);
	// print_r($_SESSION);
	
	 
	  $this->load->library("pagination");
	  
	 $post_data = $this->input->get();
	
	 
	 $countryCode = $post_data['country_code']; 
     $Location = $post_data['location'];  
	if(empty($countryCode) && !empty($Location)){
	  $QUERY_STRING = @$_SERVER['QUERY_STRING'];
	 
	   $NewcountryCode = get_country_code($Location);
	   if(!empty($NewcountryCode)){
		  $post_data['country_code'] = $NewcountryCode; 
		 }
	}


	 $proprty_category = $post_data['proprty_category'];
	 $proprty_category = $proprty_category ? $proprty_category : 'Sale';
	  $data['title'] = 'Property For '.$proprty_category;
	  $slider_min_price = '10000';
	  $slider_max_price = '6500000';
	  if($proprty_category == 'Rent'){
		   $slider_min_price = '100';
	       $slider_max_price = '39000'; 
	  }
	  
	  $data['country']     = $post_data['country_code'];
	  $data['regions']     = $post_data['regions'];
	  $data['city']        = $post_data['city'];
	  $data['postal_code'] = $post_data['postal_code'];
	  $data['min_price']   = $post_data['min_price'];
	  $data['max_price']   = $post_data['max_price'];
	    
	  $min_rang_price = $data['min_price'] ? $data['min_price'] : $slider_min_price;
	  $max_rang_price = $data['max_price'] ? $data['max_price'] : $slider_max_price;
	  $default_currency = $this->config->item('currency');
   	  $currency = $this->session->userdata('currency');
	  $currency_symbol = $currency ? $currency : $this->config->item('currency');
	  $currency_symbol = $this->session->userdata('currency_symbol');
	  $currency_symbol = $currency_symbol ? $currency_symbol : $this->config->item('currency_symbol');
	  $current_rate = $this->session->userdata('current_rate')?$this->session->userdata('current_rate'):1; 
	  
	
   
	if($currency == $default_currency){
	$current_rate = 1;
	}
	$data['currency_symbol'] =  $currency_symbol;
	$data['current_rate'] =  $current_rate;
	$data['min_rang_price'] =  $min_rang_price;
	$data['max_rang_price'] =  $max_rang_price;
	$data['range_slider_min_length'] =  $slider_min_price;
	$data['range_slider_max_length'] =  $slider_max_price;
		  
	  $data['proprty_category'] = $proprty_category;
	  $location11 = $this->session->userdata('property_search');
	  $data['property_type']   = $post_data['property_type'];
	  $data['near_by']   = $post_data['near_by'];
	  $data['bed_room']   = $post_data['bed_room'];
  $data['location']   = $post_data['location'] ? $post_data['location'] : $location11['location'];
	  $data['latitude']   = $post_data['latitude'];
	  $data['longitude']   = $post_data['longitude'];
	  
		$limit = 6;
		$dispaly = 1;
		$dispaly_from = $limit;
		$offset = ($this->uri->segment(3))? $this->uri->segment(3) : NULL;
		if(!empty($offset)){
			if($offset != 1){
			  $dispaly =  (($offset-1) * $limit);	
			}else{
				$dispaly = $limit;
			}
		 	$offset  = (($offset-1) * $limit); 
			$dispaly_from = $offset + $limit ;
		}
		$data['property_listing_count']=$this->Property_listing_model->getPropertyListingCount($post_data);
		if(!empty($data['property_listing_count'])){
			$data['results']=$this->Property_listing_model->getPropertyListing($limit, $offset,$post_data);
			// print_r($data['results']);
			if($dispaly_from > $data['property_listing_count']){
			   $dispaly_from = $data['property_listing_count'];	
			 }
			
			$data['property_results'] = array('message' => 'success', 'total_count' => $data['property_listing_count'],
			  'num_display' => 'Displaying '.$dispaly.' to '.$dispaly_from.' of '. $data['property_listing_count']);
		}else{
			$data['results']= '';
			$data['property_results'] = array('message' => 'No record found', 'total_count' => '', 'num_display' => '');
		}
		$config["base_url"] = base_url() . "ajax/listing";
		$config["total_rows"] = $data['property_listing_count'];
		$config["per_page"] = $limit;
		$config["uri_segment"] = 3;
		$config['num_links'] = 2; 
		$config['display_pages'] = TRUE;  
		$config = array_merge($config, $this->config->item('pagingConfig'));
		$this->pagination->initialize($config);
		$data["links"] = $this->pagination->create_links();
		$this->load->view('listing',$data);
	}
	
	public function auctionlisting(){
		//$this->output->enable_profiler(TRUE);
		$user_id = $this->session->userdata('user_id');
		if(empty($user_id)){
			  $this->messageci->set(strip_tags('Unauthorised Access'), 'error');
		  redirect(base_url());
		
		}
		
		//echo '<pre/>';
		//print_r($_SESSION);
	$this->load->library("pagination");
	
	$post_data = $this->input->get();
	 $data['min_price'] = $post_data['min_price'];
	 
	 
	  $data['max_price'] = $post_data['max_price'];
	  $proprty_category = $post_data['proprty_category'];
      $data['title'] = $proprty_category;
	  $slider_min_price = '10000';
	  $slider_max_price = '6500000';
	  if($proprty_category == 'Rent'){
		   $slider_min_price = '100';
	       $slider_max_price = '39000'; 
	  }
	  
	$min_rang_price = $data['min_price'] ? $data['min_price'] : $slider_min_price;
	$max_rang_price = $data['max_price'] ? $data['max_price'] : $slider_max_price;
	$default_currency = $this->config->item('currency');
	$currency = $this->session->userdata('currency');
	$currency_symbol = $this->session->userdata('currency_symbol');
	$current_rate = $this->session->userdata('current_rate'); 
	if($currency == $default_currency){
	$current_rate = 1;
	}
	$data['currency_symbol'] =  $currency_symbol;
	$data['current_rate'] =  $current_rate;
	$data['min_rang_price'] =  $min_rang_price;
	$data['max_rang_price'] =  $max_rang_price;	
	$data['range_slider_min_length'] =  $slider_min_price;;
	$data['range_slider_max_length'] =  $slider_max_price;
  $data['property_type'] = $post_data['property_type'];
	  $data['near_by'] = $post_data['near_by'];
	  $data['bed_room'] = $post_data['bed_room'];
	  $data['location'] = $post_data['location'] ? $post_data['location'] : $this->session->userdata('country');
		$limit = 6;
		$dispaly = 1;
		$dispaly_from = $limit;
		$offset = ($this->uri->segment(3))? $this->uri->segment(3) : NULL;
		if(!empty($offset)){
			if($offset != 1){
			  $dispaly =  (($offset-1) * $limit);	
			}else{
				$dispaly = $limit;
			}
		 	$offset  = (($offset-1) * $limit); 
			$dispaly_from = $offset + $limit ;
		}
		$data['property_listing_count']=$this->Property_listing_model->getAuctionPropertyListingCount($post_data);
		if(!empty($data['property_listing_count'])){
			$data['results']=$this->Property_listing_model->getAuctionPropertyListing($limit, $offset,$post_data);
			if($dispaly_from > $data['property_listing_count']){
			   $dispaly_from = $data['property_listing_count'];	
			 }
			$data['property_results'] = array('message' => 'success', 'total_count' => $data['property_listing_count'],
			  'num_display' => 'Displaying '.$dispaly.' to '.$dispaly_from.' of '. $data['property_listing_count']);
		}else{
			$data['results']= '';
			$data['property_results'] = array('message' => 'No record found', 'total_count' => '', 'num_display' => '');
		}
		$config["base_url"] = base_url() . "ajax/listing";
		$config["total_rows"] = $data['property_listing_count'];
		$config["per_page"] = $limit;
		$config["uri_segment"] = 3;
		$config['num_links'] = 2; 
		$config['display_pages'] = TRUE;  
		$config = array_merge($config, $this->config->item('pagingConfig'));
		$this->pagination->initialize($config);
		$data["links"] = $this->pagination->create_links();
		$this->load->view('auction_listing',$data);
	}
	
	public function map(){
	$post_data = $this->input->get(); 
	
	$countryCode = $post_data['country_code']; 
     $Location = $post_data['location'];  
	if(empty($countryCode) && !empty($Location)){
	  $QUERY_STRING = @$_SERVER['QUERY_STRING'];
	 
	   $NewcountryCode = get_country_code($Location);
	   if(!empty($NewcountryCode)){
		  $post_data['country_code'] = $NewcountryCode; 
		 }
	}


	  $data['min_price'] = $post_data['min_price'];
	  $data['max_price'] = $post_data['max_price'];
      $proprty_category = $post_data['proprty_category'];
      $data['title'] = $proprty_category;
	  
	    $country_internet = $this->session->userdata('country_internet');
		$country = $this->session->userdata('country');
		$country_code = $this->session->userdata('country_code');
		
	  	$country = $country_code ? $country_code : $country;
		$location = $this->session->userdata('location') ? $this->session->userdata('location') : $country;
		$data['location'] = $location;
		
	  
	  $slider_min_price = '10000';
	  $slider_max_price = '6500000';
	  if($proprty_category == 'Rent'){
		   $slider_min_price = '100';
	       $slider_max_price = '39000'; 
	  }
	  
	  $min_rang_price = $data['min_price'] ? $data['min_price'] : $slider_min_price;
	$max_rang_price = $data['max_price'] ? $data['max_price'] : $slider_max_price;
	
	$default_currency = $this->config->item('currency');
	$currency = $this->session->userdata('currency');
	$currency_symbol = $this->session->userdata('currency_symbol');
	$current_rate = $this->session->userdata('current_rate'); 
	if($currency == $default_currency){
	$current_rate = 1;
	}
	$data['currency_symbol'] =  $currency_symbol;
	$data['current_rate'] =  $current_rate;
	$data['min_rang_price'] =  $min_rang_price;
	$data['max_rang_price'] =  $max_rang_price;	  
	$data['range_slider_min_length'] =  $slider_min_price;
	$data['range_slider_max_length'] =  $slider_max_price;
	
	  $data['proprty_category'] = $post_data['proprty_category'] ? $post_data['proprty_category'] : 'Sale'; 
	  $data['property_type'] = $post_data['property_type'] ? $post_data['property_type'] : '';
	  $data['near_by'] = $post_data['near_by'];
	  $data['bed_room'] = $post_data['bed_room'];
	  $data['location'] = $post_data['location'] ? $post_data['location'] : $this->session->userdata('country');
		
		if($post_data){
			$postData = $post_data;
		}else{
			$postData = $_SESSION['property_search'];
			}
		
	$data['results']=$this->Property_listing_model->getPropertyListing('','',$postData,'json','All');
	
	
	
	
	
	if(!empty($data['results'])){
		
		$total_count =  count(json_decode($data['results']));
			$data['property_results'] = array("message" => "success", 
			"total_count" => $total_count,
			"results" => $data['results'],
			"num_display" => "Displaying  1 to ".$total_count." of ". $total_count);
		}else{
			$data['property_results'] = array("message" => "error",
                                    "results" => "",
                                    "total_count" => "",
                                    "num_display" => "No record found");
		}
		
		
	
	$this->load->view('map_view',$data);
	}	
	
	public function forsale(){
		
		$this->load->library("pagination");
		$post_data = $this->input->get(); 
		$country_internet = $this->session->userdata('country_internet');
		$country = $this->session->userdata('country');
		$country_code = $this->session->userdata('country_code');
		
		
		$country = $country_code ? $country_code : $country;
		$location = $this->session->userdata('location') ? $this->session->userdata('location') : $country;
		$data['location'] = $location;
		
       
		$data_search = array('proprty_category' => 'Sale','search_type' => 'default'); 
$data_search = '';
		$data['proprty_category'] = 'Sale';
		  $data['title'] = 'Property For Sale';
		$limit = 8;
		$dispaly = 1;
		$dispaly_from = $limit;
		$offset = ($this->uri->segment(3))? $this->uri->segment(3) : NULL;
		if(!empty($offset)){
			if($offset != 1){
			  $dispaly =  (($offset-1) * $limit);	
			}else{
				$dispaly = $limit;
			}
			
		 	$offset  = (($offset-1) * $limit); 
			$dispaly_from = $offset + $limit ;
			
		}
		
		
	$country_code = $country_code ? $country_code : $country_internet;	
	$data['country'] = $country_code;
	$sale_data = array('proprty_category' => 'Sale','min_price' => 10000, 'max_price' => 6500000,'country_code' => $country_code);
	$default_post_data = array('property_search' => $sale_data);
	$this->session->set_userdata($default_post_data);
		
		$data['property_listing_count']=$this->Property_listing_model->getPropertyListingCount($data_search);
		
		if(!empty($data['property_listing_count'])){
		$data['results']=$this->Property_listing_model->getPropertyListing($limit, $offset,$data_search);
	    if($dispaly_from > $data['property_listing_count']){
		   $dispaly_from = $data['property_listing_count'];	
		 }
		$data['property_results'] = array('message' => 'success',
		                                 'total_count' => $data['property_listing_count'],
	      'num_display' => 'Displaying '.$dispaly.' to '.$dispaly_from.' of '. $data['property_listing_count']);
		}else{
		$data['results']= '';
	    $data['property_results'] = array('message' => 'No record found',
		                                 'total_count' => '',
	                                     'num_display' => '');
		}
	    		
		$default_currency = $this->config->item('currency');
		$currency = $this->session->userdata('currency');
		$currency_symbol = $this->session->userdata('currency_symbol');
		$current_rate = $this->session->userdata('current_rate'); 
		if($currency == $default_currency){
		$current_rate = 1;
		}
		$data['currency_symbol'] =  $currency_symbol;
		$data['current_rate'] =  $current_rate;
		$data['min_rang_price'] =  '10000';
		$data['max_rang_price'] =  '6500000';
		$data['range_slider_min_length'] =  '10000';
		$data['range_slider_max_length'] =  '6500000';
		
		$config["base_url"] = base_url() . "ajax/listing";
		$config["total_rows"] = $data['property_listing_count'];
		$config["per_page"] = $limit;
		$config["uri_segment"] = 3;
		$config['num_links'] = 2; 
		$config['display_pages'] = TRUE;  
		$config = array_merge($config, $this->config->item('pagingConfig'));
		$this->pagination->initialize($config);
		$data["links"] = $this->pagination->create_links();
		$this->load->view('listing',$data);
	}
	
	public function forrent(){
		 $this->load->library("pagination");
		$post_data = $this->input->get(); 
		 
		$country_internet = $this->session->userdata('country_internet');
		$country = $this->session->userdata('country');
		$country_code = $this->session->userdata('country_code');
		
		$country = $country_code ? $country_code : $country;
		$location = $this->session->userdata('location') ? $this->session->userdata('location') : $country;
		$data['location'] = $location;
		
		
		 
	  $data['proprty_category'] = 'Rent';
	    $data['title'] = 'Property For Rent';
	  $data_search = array('proprty_category' => 'Rent','search_type' => 'default'); 
	  
	  
		
		$country_code = $country_code ? $country_code : $country_internet;
		$data['country'] = $country_code;
	 $sale_data = array('proprty_category' => 'Rent','min_price' => 100, 'max_price' => 39000,'country_code' => $country_code);
	  $default_post_data = array('property_search' => $sale_data);
	  $this->session->set_userdata($default_post_data);


$data_search = '';
		$limit = 6;
		$dispaly = 1;
		$dispaly_from = $limit;
		$offset = ($this->uri->segment(3))? $this->uri->segment(3) : NULL;
		if(!empty($offset)){
			if($offset != 1){
			  $dispaly =  (($offset-1) * $limit);	
			}else{
				$dispaly = $limit;
			}
			
		 	$offset  = (($offset-1) * $limit); 
			$dispaly_from = $offset + $limit ;
			
		}
		$data['property_listing_count']=$this->Property_listing_model->getPropertyListingCount($data_search);
		
		if(!empty($data['property_listing_count'])){
		$data['results']=$this->Property_listing_model->getPropertyListing($limit, $offset,$data_search);
	    if($dispaly_from > $data['property_listing_count']){
		   $dispaly_from = $data['property_listing_count'];	
		 }
		$data['property_results'] = array('message' => 'success',
		                                 'total_count' => $data['property_listing_count'],
	      'num_display' => 'Displaying '.$dispaly.' to '.$dispaly_from.' of '. $data['property_listing_count']);
		}else{
		$data['results']= '';
	    $data['property_results'] = array('message' => 'No record found',
		                                 'total_count' => '',
	                                     'num_display' => '');
		}
		
				
		$default_currency = $this->config->item('currency');
		$currency = $this->session->userdata('currency');
		$currency_symbol = $this->session->userdata('currency_symbol');
		$current_rate = $this->session->userdata('current_rate'); 
		if($currency == $default_currency){
		$current_rate = 1;
		}
		$data['currency_symbol'] =  $currency_symbol;
		$data['current_rate'] =  $current_rate;
		$data['min_rang_price'] =  '100';
		$data['max_rang_price'] =  '39000';
		$data['range_slider_min_length'] =  '100';
		$data['range_slider_max_length'] =  '39000';
	      
		$config["base_url"] = base_url() . "ajax/listing";
		$config["total_rows"] = $data['property_listing_count'];
		$config["per_page"] = $limit;
		$config["uri_segment"] = 3;
		$config['num_links'] = 2; 
		$config['display_pages'] = TRUE;  
		$config = array_merge($config, $this->config->item('pagingConfig'));
		$this->pagination->initialize($config);
		$data["links"] = $this->pagination->create_links();
		$data["page_type"]='Rent';
		$this->load->view('listing',$data);
	}
	
    function details(){
		
		 $id = $this->uri->segment(3);
        if (!is_numeric($id)) {
			$id = safe_b64decode($id);
	    }
		 $data['property_id'] = $id;
		 $where = " where property_id = '".$id."'";
		 
         $data['propertyData'] = getProperty($id, 'auction_status');
		 	if(!empty($data['propertyData'])){
				
				
			   $remote_addr = $_SERVER['REMOTE_ADDR'];
			   $user_id = $this->session->userdata('user_id');
			   $whereCon = "WHERE user_id = '".$user_id."' && property_id = '".$id."' && ipaddress = '".$remote_addr."'";
			   $data['pro_fav_status'] = $this->Common->select('user_property_favorites',$whereCon);
			   $data['pro_image'] = $this->Common->select('property_image', $where);
			   $bedrooms = $data['propertyData'][0]['bedrooms'];
			   $property_type_name = $data['propertyData'][0]['property_type_name'];
			   $category_name = $data['propertyData'][0]['category_name'];
			   $address = $data['propertyData'][0]['address'];
			   
			   $websetting = $this->session->userdata('websetting');
			   $site_name =  $websetting['site_name'];
			   
$data['title'] = ucwords($bedrooms.'  Bedroom  '. $property_type_name.'  For '. $category_name .' in '.$address.' - '.$zipcode.' - '.$site_name);
$data['propertyTitle'] = ucwords($bedrooms.'  Bedroom  '. $property_type_name.'  For '. $category_name);


				$created_by = $data['propertyData'][0]['created_by'];
			   
			   if(!empty($created_by))
			   {
                $data['professionData'] = $this->Property_listing_model->getProperuserDetail($created_by);
			   }   	
			
			
			}else{
				$this->messageci->set('Sorry, property Is not available at this time.', 'error');
				 redirect(base_url());
								
				}
		 
		
		 
		 $data['stylesheet'] = array('assets/plugins/flex-slider/flexslider.css', 'assets/plugins/colorbox/example2/colorbox.css');
 $data['nearby_property'] = $this->Property_listing_model->nearby_propertylisting($id);
 $data['nearby_propertyLatLng'] = $this->Property_listing_model->nearby_propertyLatLng($id);
 
		 $this->load->view('details',$data);
		
	}
	
	function auction(){
		
		$user_id = $this->session->userdata('user_id');
		/*if(empty($user_id)){
			  $this->messageci->set(strip_tags('Unauthorised Access'), 'error');
		  redirect(base_url());
		
		}*/
		$post_data = $this->input->get(); 
		$data['scriptsrc'] = array('assets/js/custom/email-subscribe.js',
		                            'assets/js/custom/property_details.js'   
								   );
		 $id = $this->uri->segment(3);
		 if (!is_numeric($id)) {
			$id = safe_b64decode($id);
	    }
		 $data['property_id'] = $id;
		 $where = " where property_id = '".$id."'";
		 $data['pro_image'] = $this->Common->select('property_image', $where);
         $data['propertyData'] = getAuctionDetail($id);
		
		 
		 if(!empty($data['propertyData'])){
			 
			   $bedrooms = $data['propertyData'][0]['bedrooms'];
			   $property_type_name = $data['propertyData'][0]['property_type_name'];
			   $category_name = $data['propertyData'][0]['category_name'];
$data['title'] = ucwords($bedrooms.'  Bedroom  '. $property_type_name.'  For '. $category_name);
				$created_by = $data['propertyData'][0]['created_by'];
			   
			  /* if(!empty($created_by))
			   {
                $data['professionData'] = $this->Property_listing_model->getProperuserDetail($created_by);
			   }   	
			*/
			
			}else{
			   $this->messageci->set(strip_tags('Unauthorised Access'), 'error');
		  redirect(base_url());
			}
			
		 $data['nearby_property'] = $this->Property_listing_model->nearby_propertylisting($id);
 $data['nearby_propertyLatLng'] = $this->Property_listing_model->nearby_propertyLatLng($id);
 
		 $default_currency = $this->config->item('currency');
		 $currency = $this->session->userdata('currency');
		 $currency_symbol = $currency ? $currency : $this->config->item('currency');
	     $currency_symbol = $this->session->userdata('currency_symbol');
	     $currency_symbol = $currency_symbol ? $currency_symbol : $this->config->item('currency_symbol');
		 $current_rate = $this->session->userdata('current_rate')?$this->session->userdata('current_rate'):1; 
			if($currency == $default_currency){
				$current_rate = 1;
				}
		$data['currency_symbol'] =  $currency_symbol;
		$data['current_rate'] =  $current_rate;
		
		 
		 $data['stylesheet'] = array('assets/plugins/flex-slider/flexslider.css', 'assets/plugins/colorbox/example2/colorbox.css');
		$data['auction_data']=selectData('property_auction',"where property_id='$id'");
        if(empty($data)){
		   $this->messageci->set(strip_tags('Unauthorised Access'), 'error');
		  redirect(base_url());
		}else{
         $this->load->view('auction',$data);
        } 
		
	}
	
	public function recent_auction($property_id){
	
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')     {
	 if(!empty($property_id)){
	   $auction_data= getRecentAuction($property_id);
	   $list='';
	   
	   $list.='<h3 class="center">Recent Bids</h3>
	           <table class="table table-bordered table-striped">
	          		<thead>
		           		<tr>
			            	<th>Amount</th>
			            	<th>Name</th>
			            	<th>Time</th>
		           		</tr>
    	      		</thead>
        	  <tbody>
              <ul class="team-list">';
	   
	   
	   foreach($auction_data as $aucdata){
	   
	   $urls = config_item('base_url').'applicationMediaFiles/usersImage/150150';
	
								 if(!empty($aucdata["profile_image"])){
								 
								 $aj_img   = getUserProfileImage($aucdata["profile_image"],$urls);
								 }else{
								 $aj_img='/default.png';
								 }
				
				if ($aucdata['hide_name'] == 1){
					$uname = '********';
				} else {
					$uname =  $aucdata['firstName'].'&nbsp;' . $aucdata['lastName'];					
				}
				$utime = ago(strtotime($aucdata['createdDate']));
				
				
				
	           $list.= '<tr>
			            <td> '.attachCurrencySymbol(convert_currency($aucdata['price'])).' </td>
			            <td><span class="badge">'.ucfirst($uname).'</span></td>
			            <td>'.$utime.'</td>
		        </tr>
				';			
				
				
				
				
	   }
	   $list.='</tbody></table><div class="clear"></div></ul>';
	   
	   echo $list;
	 }
	
	}else{
	 echo 'Unauthorized access';
	 }
	}
	
	public function myagentinfo($id)
	{
       // $id = safe_b64decode($id); 
		
		$this->db->select('*');
		$this->db->from('user u');
		$this->db->join('user_profile g', 'u.user_id = g.user_id', 'LEFT'); 
		$this->db->where('u.group_id',5);
		$this->db->where('u.user_id',$id);
		
		$result = $this->db->get();
		$data = $result->result();
		$data['page_title'] = 'Agent-Details';
		$this->load->model('findagent_model');
		$resultqw=$this->findagent_model->usercomment($id);
		//echo '<pre>';
		//print_r($resultqw); die();
		
		$this->load->view('findagent/agent_detail', array('detail' => $data,'resultqw' => $resultqw));
	}
	
	public function recently_seen(){
	 
	 if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')     {
		$result = $this->Property_listing_model->recently_seen();
		
		header('Content-Type: application/json');
	    echo json_encode($result);
	 }else{
	   echo 'Unauthorized access';
	 }	
		
	}
	
	public function save_result($id){
	//$this->output->enable_profiler(TRUE);
	$post_data = $this->input->post(); 
	$result=$this->Common->select('user_property_save_result',"where user_property_save_id='$id'"); 
	$save_result=$result[0]['save_result'];
	$result_type=$result[0]['result_type'];
	$save_result = unserialize($save_result);
	/*echo '<pre/>';
	print_r($save_result); */
	$_POST=$save_result;
	/*echo '<pre/>';
	print_r($_POST);  die;*/
	  //$this->output->enable_profiler(TRUE);
	  
	 if($result_type=='1'){
	 
	 
	
	    $this->load->library("pagination");
	
	 $data['min_price'] = $post_data['min_price'];
	 
	 
	  $data['max_price'] = $post_data['max_price'];
	  $proprty_category = $post_data['proprty_category'];
      $data['title'] = $proprty_category;
	  $slider_min_price = '10000';
	  $slider_max_price = '6500000';
	  if($proprty_category == 'Rent'){
		   $slider_min_price = '100';
	       $slider_max_price = '39000'; 
	  }
	  
	$min_rang_price = $data['min_price'] ? $data['min_price'] : $slider_min_price;
	$max_rang_price = $data['max_price'] ? $data['max_price'] : $slider_max_price;
	$default_currency = $this->config->item('currency');
	$currency = $this->session->userdata('currency');
	$currency_symbol = $this->session->userdata('currency_symbol');
	$current_rate = $this->session->userdata('current_rate'); 
	if($currency == $default_currency){
	$current_rate = 1;
	}
	$data['currency_symbol'] =  $currency_symbol;
	$data['current_rate'] =  $current_rate;
	$data['min_rang_price'] =  $min_rang_price;
	$data['max_rang_price'] =  $max_rang_price;	
	$data['range_slider_min_length'] =  $slider_min_price;;
	$data['range_slider_max_length'] =  $slider_max_price;
  $data['property_type'] = $post_data['property_type'];
	  $data['near_by'] = $post_data['near_by'];
	  $data['bed_room'] = $post_data['bed_room'];
	  $data['location'] = $post_data['location'] ? $post_data['location'] : $this->session->userdata('country');
		$limit = 6;
		$dispaly = 1;
		$dispaly_from = $limit;
		$offset = ($this->uri->segment(4))? $this->uri->segment(4) : NULL;
		if(!empty($offset)){
			if($offset != 1){
			  $dispaly =  (($offset-1) * $limit);	
			}else{
				$dispaly = $limit;
			}
		 	$offset  = (($offset-1) * $limit); 
			$dispaly_from = $offset + $limit ;
		}
		$data['property_listing_count']=$this->Property_listing_model->getAuctionPropertyListingCount($_POST);
		if(!empty($data['property_listing_count'])){
			$data['results']=$this->Property_listing_model->getAuctionPropertyListing(50, 0,$_POST);
			if($dispaly_from > $data['property_listing_count']){
			   $dispaly_from = $data['property_listing_count'];	
			 }
			$data['property_results'] = array('message' => 'success', 'total_count' => $data['property_listing_count'],
			  'num_display' => 'Displaying '.$dispaly.' to '.$dispaly_from.' of '. $data['property_listing_count']);
		}else{
			$data['results']= '';
			$data['property_results'] = array('message' => 'No record found', 'total_count' => '', 'num_display' => '');
		}
		$config["base_url"] = base_url() . "ajax/listing";
		$config["total_rows"] = $data['property_listing_count'];
		$config["per_page"] = $limit;
		$config["uri_segment"] = 3;
		$config['num_links'] = 2; 
		$config['display_pages'] = TRUE;  
		$config = array_merge($config, $this->config->item('pagingConfig'));
		$this->pagination->initialize($config);
		$data["links"] = $this->pagination->create_links();
		$this->load->view('auction_listing',$data);
	 
	 
	 
	 }else{ 
	  
	  $this->load->library("pagination");
	  $data['min_price'] = $post_data['min_price'];
	  $data['max_price'] = $post_data['max_price'];
	  $data['proprty_category'] = $post_data['proprty_category'];
	  $data['property_type'] = $post_data['property_type'];
	  $data['near_by'] = $post_data['near_by'];
	  $data['bed_room'] = $post_data['bed_room'];
	  $data['location'] = $post_data['location'] ? $post_data['location'] : $this->session->userdata('country');
	  
	  $data['latitude'] = $post_data['latitude'];
      $data['longitude'] = $post_data['longitude'];
	  
	   $slider_min_price = '10000';
       $slider_max_price = '6500000';
	  if($data['proprty_category'] == 'Rent'){
		   $slider_min_price = '100';
	       $slider_max_price = '39000'; 
	  }
	  
	  $data['min_price'] = $post_data['min_price'];
	  $data['max_price'] = $post_data['max_price'];	  
	  $min_rang_price = $data['min_price'] ? $data['min_price'] : $slider_min_price;
	  $max_rang_price = $data['max_price'] ? $data['max_price'] : $slider_max_price;
	  
	  $data['min_rang_price'] =  $min_rang_price;
	  $data['max_rang_price'] =  $max_rang_price;
      $data['range_slider_min_length'] =  $slider_min_price;
	  $data['range_slider_max_length'] =  $slider_max_price;
	
	  $default_currency = $this->config->item('currency');
   	  $currency = $this->session->userdata('currency');
	  $currency_symbol = $currency ? $currency : $this->config->item('currency');
	  $currency_symbol = $this->session->userdata('currency_symbol');
 $currency_symbol = $currency_symbol ? $currency_symbol : $this->config->item('currency_symbol');
 $current_rate = $this->session->userdata('current_rate')?$this->session->userdata('current_rate'):1;	if($currency == $default_currency){
	$current_rate = 1;
	}
	$data['currency_symbol'] =  $currency_symbol;
	$data['current_rate'] =  $current_rate;
	  
	  
		$limit = 6;
		$dispaly = 1;
		$dispaly_from = $limit;
		$offset = ($this->uri->segment(4))? $this->uri->segment(4) : NULL;
		if(!empty($offset)){
			if($offset != 1){
			  $dispaly =  (($offset-1) * $limit);	
			}else{
				$dispaly = $limit;
			}
			
		 	$offset  = (($offset-1) * $limit); 
			$dispaly_from = $offset + $limit ;
			
		}
		$data['property_listing_count']=$this->Property_listing_model->getPropertyListingCount($_POST);
		
		if(!empty($data['property_listing_count'])){
		$data['results']=$this->Property_listing_model->getPropertyListing($limit, $offset,$_POST);
	    if($dispaly_from > $data['property_listing_count']){
		   $dispaly_from = $data['property_listing_count'];	
		 }
		$data['property_results'] = array('message' => 'success',
		                                 'total_count' => $data['property_listing_count'],
	      'num_display' => 'Displaying '.$dispaly.' to '.$dispaly_from.' of '. $data['property_listing_count']);
		}else{
		$data['results']= '';
	    $data['property_results'] = array('message' => 'No record found',
		                                 'total_count' => '',
	                                     'num_display' => '');
		}
	      
		$config["base_url"] = base_url() . "ajax/listing";
		$config["total_rows"] = $data['property_listing_count'];
		$config["per_page"] = $limit;
		$config["uri_segment"] = 3;
		$config['num_links'] = 2; 
		$config['display_pages'] = TRUE;  
		$config = array_merge($config, $this->config->item('pagingConfig'));
		$this->pagination->initialize($config);
		$data["links"] = $this->pagination->create_links();
		$this->load->view('listing',$data);
		}
	}
	
	public function auction_property($id){
	 
	  $result=$this->Common->select('property_auction',"where property_auction_id='$id'");
	 
	 redirect('property/auction/'.$result[0]['property_id']);
	}
}