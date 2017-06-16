<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Myproperties extends CI_Controller {

	var $userID;

	public function __construct()  

	{

		parent::__construct();

		$this->load->database();

		$this->load->library(array('grocery_CRUD', 'ajax_grocery_crud'));

		$this->load->model(array('common', 'property_model'));

		$this->load->helper('common_admin');		 		

		$this->language =  language_load();

		// check user login			 

		$this->userID = checkUserLogin();

		//checkUserAccessPermission(); 
		
		

	}

	

	public function index(){	

		$crud = new grocery_CRUD();

		$crud->unset_print(); 

		$crud->unset_export();
		
		$crud->unset_delete();
		
		$crud->unset_bulk_publish();
		$crud->unset_bulk_unpublish();

		$crud->set_subject('Property');

		

						

		$crud->set_table('property');

		$crud->where('property.user_id', $this->userID);
		
		$crud->where('property.auction_status', '0');

		$crud->set_relation('user_id', 'user', '{firstName} {lastName}');	

		$crud->set_relation('property_category', 'property_category', 'categoryName');	

		$crud->set_relation('property_type', 'property_types', 'typeName');

		

		$crud->columns('property_name', 'property_category', 'property_type','property_availability','status');		

		
		
		$crud->set_add_url_path(base_url('myproperties/packagecheck'));

		$crud->set_edit_url_path(base_url('myproperties/edit'));

		$crud->set_read_url_path(base_url('myproperties/view'));

		$crud->set_bulk_action_url(base_url('myproperties/bulk_action'));		
		$crud->set_data_status_field_name('status');		

		$crud->display_as('property_category', 'Listing For')->display_as('property_type', 'Type')->display_as('availability_status', 'Availability');

		

		$crud->add_action('Stats', 'clip-stats', base_url().'myproperties/viewStats/', 'btn btn-xs btn-default openPopupDialog');

		$crud->add_action('', 'fa fa-trash-o', base_url().'myproperties/log_user_before_delete/', 'btn btn-danger delete-row');	

		$output = $crud->render();

		

		$stylesheet = array('assets/plugins/bootstrap-dialogbox/bootstrap-dialog.css');

		$scriptsrc = array('assets/plugins/bootstrap-dialogbox/bootstrap-dialog.js');

		$script = array("<script>$(document).ready(function(){

					   $('a.openPopupDialog').click(function(){

							var url = this.href;

							$.ajax({ 

								url: url, //'http://localhost/testing/ajax-dialog.php',								

								success: function(data){																	 

									  BootstrapDialog.show({

										size: BootstrapDialog.SIZE_LARGE,

										closable: false,

										title: 'Property overview stats',

										message: data,

										buttons: [{

													label: 'Close',

													action: function(dialogRef){

														dialogRef.close();

													}

												}]

									  });				

								}

							});

							return false; 

						}); 

					}); </script>");		

		

		$page_title = array('page_title'=>'Manage Properties', 'stylesheet'=>$stylesheet, 'scriptsrc'=>$scriptsrc, 'script'=>$script);

		$outputData = array_merge((array)$output, $page_title);

		$this->_example_output($outputData);		

	}

	public function viewStats($propertyID = NULL){

		

		$data['results'] = 'Property Data';

		if(!empty($propertyID))

		$data['getResult'] = getPropertyData($propertyID);

		$this->load->view('my_account/propertyOverViewStats', $data);

	}

	public function message($id){
	
	 
	  switch ($id){

              case "1":

                 $msg = 'Your Free Subscription Expire Please Purchase a <a href="'.base_url('mydashboard/subscription').'" title="click here">Subscription!</a>';

                 break;

              case "2":

                 $msg = 'Your Subscription Expire Please Purchase a new <a href="'.base_url('mydashboard/subscription').'" title="click here">Subscription!</a>';

                 break;
			  case "3":

                 $msg = 'You have Reached Limit for Add Property.Please Purchase a new <a href="'.base_url('mydashboard/subscription').'" title="click here">Subscription!</a>';

                 break;

     }
	
	
	
	
	$this->messageci->set($msg, 'error');
	
	redirect('myproperties');
	
	}
	
	
	public function packagecheck(){
	
	  $userPackage=getUserPackageData($this->userID);
	  
	  $propertyCount=getPropertyCountPackage($this->userID,$userPackage[0]['start_date']);
	
	  $property_package_count=$userPackage[0]['propertyCount'];
	  $end_date=$userPackage[0]['end_date'];
	  $todayDate=date('Y-m-d');
	
	
	
	    $packagesetting=getUserSubcription($this->userID);
	
	    $subscription=$packagesetting['subscription_setting']['subscription'];
	    $month=$packagesetting['subscription_setting']['month'];
	     
		 
	
	   if(!empty($subscription)){
		  
		  if(($subscription=='no')&&($month=='exceed')){
		  
		     $this->message('1'); /*Expire Your Month(Free Users)*/
		 
		  }else{
		   
			   if(($subscription=='yes')&&($end_date<$todayDate)){
				$this->message('2'); /*Expire Your Package(For Renew Package )*/
			   }
			   else{
			     if(!empty($userPackage)){
					 if($propertyCount>=$property_package_count){
					   $this->message('3'); /*Reached Maximum Limit*/
					 }else{
					   redirect('myproperties/add');
					 }
				 }else{
				    redirect('myproperties/add');
				 } 
			   }
		  }
		   
		}else{

		 redirect('myproperties/add');
		
		}
	
	}

	public function add(){
	
	   

	   $this->lang->load('agent/property', $this->language);	   

	   $data['lang_data'] = $this->lang->language;	   

	   $data['near_json'] = $this->property_model->selectJson("manage_nearby","where status='Active'"); 

	   $data['page_title'] = 'Add Properties';

	   if($_POST){

			$formValidation = $this->__setFormRules($this->input->post());

			if ($this->form_validation->run() == TRUE){

			    $user_id = $this->session->userdata('user_id') ? $this->session->userdata('user_id') : '1';

			    //$property_name = $this->input->post('property_name',TRUE);

				$property_description = $this->input->post('property_description',TRUE);

				$property_category = $this->input->post('property_category',TRUE);

				$country = $this->input->post('country',TRUE);

				$city = $this->input->post('city',TRUE);

				$bedrooms = $this->input->post('bedrooms',TRUE);				

				$property_type = $this->input->post('property_type',TRUE);

				$disclaimer = $this->input->post('disclaimer',TRUE);

				$state = $this->input->post('state',TRUE);

				$address = $this->input->post('address',TRUE);

				$latitude = $this->input->post('latitude',TRUE);

				$longitude = $this->input->post('longitude',TRUE);

				$zipcode = $this->input->post('zipcode',TRUE);

				$closing_date=$this->input->post('closing_date',TRUE);

				$price = $this->input->post('price',TRUE);

				$bathrooms = $this->input->post('bathrooms',TRUE);

				$num_floors = $this->input->post('num_floors',TRUE);

				$num_recepts = $this->input->post('num_recepts',TRUE);

				$price_modifier = $this->input->post('price_modifier',TRUE);

				$county = $this->input->post('county',TRUE);
	            $add_auction = $this->input->post('add_auction',TRUE);	 
				//$offer = '1';

			//	$offer_text = $this->input->post('offer_text') ? $this->input->post('offer_text') : '';

				$property_vedio_url=$_FILES["property_vedio_url"]['name'];				

				if(!empty($property_vedio_url)){

					$upload_directory = config_item('root_url').'applicationMediaFiles/propertiesVedio/';

					$temp_name = $_FILES['property_vedio_url']['tmp_name'];

					$ext = @pathinfo($property_vedio_url, PATHINFO_EXTENSION);

					$file_name   = time().rand(1000,99999).'.'.$ext;

					$file_path = $upload_directory.$file_name; 

					@move_uploaded_file($temp_name, $file_path);

				}else{

				    $file_name='';

				}	

				

				

				 $floor_plan_name = '';

                $property_floor_plan=$_FILES["property_floor_plan"]['name'];

				if(!empty($property_floor_plan)){

					$upload_directory = config_item('root_url').'applicationMediaFiles/property/florplan/';

					$temp_name = $_FILES['property_floor_plan']['tmp_name'];

					$ext = @pathinfo($property_floor_plan, PATHINFO_EXTENSION);

					$floor_plan_name   = time().rand(1000,99999).'.'.$ext;

					$file_path = $upload_directory.$floor_plan_name; 

					@move_uploaded_file($temp_name, $file_path);

				}

				

							

				$data=array(

				             'user_id'=>$user_id,


							 'property_description'=>$property_description,

							 'property_category'=>$property_category,

							 'country'=>$country,

							 'city'=>$city,

							 'bedrooms'=>$bedrooms,

							 'property_type'=>$property_type,

							 'disclaimer'=>$disclaimer,

							 'state'=>$state,

							 'address'=>$address,

							 

							 'latitude'=>$latitude,

							 'longitude'=>$longitude,

							 'zipcode'=>$zipcode,

							 'video_url'=>$file_name,

							 'floor_plan'=>$floor_plan_name,

							// 'offer'=>$offer,

							// 'offer_text'=>$offer_text,

							 'property_closing_date'=>$closing_date,

							 'prices'=>$price,

							 'bathrooms'=>$bathrooms,

							 'num_floors'=>$num_floors,

							 'num_recepts'=>$num_recepts,

							 'price_modifier'=>$price_modifier,
							 'auction_status'=>$add_auction ? 1 : 0,
							 'county'=>$county

				           );	

						    				  

			   $lastinsertId= $this->property_model->data_insert('property',$data);	

			   
               $bedrooms=$bedrooms;
			   $property_type_name=selectData("property_types","where property_types_id='$property_type'");
			   $category_name=selectData("property_category","where property_category_id='$property_category'");
		  

		       $seo_url_string = ucwords($bedrooms.'  Bed '. $property_type_name[0]['typeName'].'  For '. $category_name[0]['categoryName']);
			   
			   $property_seo_url = seo_friendly_urls($seo_url_string,'','');
			   
			   $where = array('property_id' => $lastinsertId);
			   
			   $pnamedata=array('property_name'=>$property_seo_url);
			   
			   $this->property_model->data_update('property',$pnamedata,$where);
			   

			   $add_auction=$this->input->post('add_auction',TRUE); 

			   

			   

			   if(!empty($add_auction)){

			   

			    $start_data=$this->input->post('start_data',TRUE); 

				

				

				$auctionDate=explode('-',$start_data);

				

				

				$bid_price=$this->input->post('bid_price',TRUE); 

				

				$auction=array('property_id'=>$lastinsertId,'user_id'=>$user_id,'start_date'=>strtotime($auctionDate[0]),'end_date'=>strtotime($auctionDate[1]),'auction_min_price'=>$bid_price);

			    $this->property_model->data_insert('property_auction',$auction);		

				

			   

			   }

			   

			   

			   $features_name = $this->input->post('features_name',TRUE);

				if(!empty($features_name)){

					for($i=0; $i < count($features_name); $i++)

					 {

					  $features_name_data[] = array('features_id' => $features_name[$i],

					                         'property_id' => $lastinsertId);

					 }

				    $this->db->insert_batch('property_features', $features_name_data);

				  }



				$data['fullname'] = $this->session->userdata('fullName');
				$data['firstname'] = $this->session->userdata('name');
				$data['profile_image'] = $this->session->userdata('profile_image');
				
				$property_seo_url = seo_friendly_urls($seo_url_string,'',$lastinsertId);
				$data['property_seo_url']=$property_seo_url;	
					
                $seousername = str_replace('&nbsp;', '-', $data['fullname']);
                $data['recieverseo'] = seo_friendly_urls($seousername,'',$this->session->userdata('user_id'));
				

				
				$message = $this->load->view('my_account/message/template/addProperty_message', $data, TRUE); 
				$toEmail = $this->session->userdata('email');
				$fromEmail = array('email' => config_item('no_reply'),'name' => config_item('site_name'));
				$subject = ucwords(config_item('site_name').' - '.$data['fullname'].' Add property');
				$attachment = array();
				$mailresult = sendUserEmailCI($toEmail, $fromEmail, $subject, $message, $attachment);

			   

			   

			   

			  

			   

			   $nearby=$this->input->post('nearby',TRUE); 

			   for($j=1; $j<=$nearby; $j++){

					$property_key=$this->input->post('nearbykey_'.$j,TRUE);

					$property_val=$this->input->post('nearbyvalue_'.$j,TRUE);

					

					$nearby_address=$this->input->post('nearbyaddress_'.$j);

					

					$latlang=address_to_latlng_nearby($nearby_address);


					$mydata=array('property_id'=>$lastinsertId,'property_key'=>$property_key,'property_val'=>$property_val,'nearby_address'=>$nearby_address,'nearby_lat'=>$nearby_lat?$nearby_lat:'','nearby_lang'=>$nearby_lang?$nearby_lang:'');

					$this->property_model->data_insert('property_nearby_address',$mydata);

			   }

			   $count = count($_FILES['property_image']['name']);

				 //$upload_directory = config_item('site_url');				

			   $upload_directory = config_item('root_url').'applicationMediaFiles/propertiesImage/';

			   for($i=0; $i<$count; $i++){

				  $file_name =$_FILES['property_image']['name'][$i];				

				  if(!empty($file_name)){				

					$temp_name = $_FILES['property_image']['tmp_name'][$i];				

					// Check if image file is a actual image or fake image				

					$check = getimagesize($temp_name);				

					if($check !== false) {				

				   // Check file size  				

					 if($_FILES['property_image']['size'][$i] < 5242880) {	  //5mb  1027*10			

						$ext = @pathinfo($file_name, PATHINFO_EXTENSION);				

						$file_name   = time().rand(1000,99999).'.'.$ext;				

						$file_path = $upload_directory.$file_name; 				

						@move_uploaded_file($temp_name, $file_path);	

									

						//insert query  en_property_image

						$property_image_title = $_POST['property_image_title'][$i];	

						$property_image_caption = $_POST['property_image_caption'][$i];	

						$data=array('property_Id'=>$lastinsertId,'image_name'=>$file_name,

							'image_title' => $property_image_title,

							'image_caption' => $property_image_caption);

						

						$insertId= $this->property_model->data_insert('property_image',$data);					

						$filePath= $upload_directory.$file_name;					

						$img = resize_images($filePath,271,221, $upload_directory.'thumb/'); 

						$img = resize_images($filePath,450,300, $upload_directory.'350325/');

						$img = resize_images($filePath,1300,400, $upload_directory.'1300400/');

						$img = resize_images($filePath,800,600, $upload_directory.'800600/'); 					

					 }				

				   }				

				  }

                } 				

				$get_c_s_city = $this->property_model->country_state_city($country,$state,$city);

				if(!empty($get_c_s_city)){			

				   $country = $get_c_s_city['country'];			

				   $state = $get_c_s_city['region'];			

				   $city = $get_c_s_city['city']; 			

				   $address = $country.','.$state.','.$city.','.$address;			

				   $where = array('property_id'=>$lastinsertId);			

				   address_to_latlng($address,$where);			

				 }	 
				 
				if($add_auction!='on'){ 
				  redirect('myproperties/');
                }else{
				  redirect('myauctionproperty/');
				}
				die;

			}else{

			  $data['post_val'] = array($this->input->post());

			  $this->load->view('my_account/addProperty',$data);

			}

	   }else{

	     $this->load->view('my_account/addProperty', $data);

	   }

	}

	

	public function addtest()

	{

	  $this->load->view('my_account/addPropertyTest', $data);

	}

	public function edit($proid){

	   $this->lang->load('agent/property', $this->language);

	   $data['lang_data']=$this->lang->language;

	   $data['near_json'] = $this->property_model->selectJson("manage_nearby","where status='Active'"); 

	   $data['property_data']= $this->property_model->get_property_details($proid);

	   $data['page_title'] = 'Edit Properties';

	   

	   if($_POST){

	

			$id = $proid;

			$formValidation = $this->__setFormRules($this->input->post());

			if ($this->form_validation->run() == TRUE){

			    $user_id = $this->session->userdata('user_id') ? $this->session->userdata('user_id') : '1';

			    //$property_name = $this->input->post('property_name',TRUE);

				$property_description = $this->input->post('property_description',TRUE);

				$property_category = $this->input->post('property_category',TRUE);

				$country = $this->input->post('country',TRUE);

				$city = $this->input->post('city',TRUE);

				//$min_price = $this->input->post('min_price',TRUE);

				$bedrooms = $this->input->post('bedrooms',TRUE);				

				$property_type = $this->input->post('property_type',TRUE);

				$disclaimer = $this->input->post('disclaimer',TRUE);

				$state = $this->input->post('state',TRUE);

				$address = $this->input->post('address',TRUE);

				$latitude = $this->input->post('latitude',TRUE);

				$longitude = $this->input->post('longitude',TRUE);

				$zipcode = $this->input->post('zipcode',TRUE);

				//$max_price = $this->input->post('max_price',TRUE);

				$max_price = $this->input->post('max_price',TRUE);

				$closing_date1= $this->input->post('closing_date',TRUE);

				$price = $this->input->post('price',TRUE);

				$bathrooms = $this->input->post('bathrooms',TRUE);

				$num_floors = $this->input->post('num_floors',TRUE);

				$num_recepts = $this->input->post('num_recepts',TRUE);

				$price_modifier = $this->input->post('price_modifier',TRUE);

				$county = $this->input->post('county',TRUE);

				$closing_date = strtotime($closing_date1);
				
				$property_availability = $this->input->post('property_availability',TRUE);

				$add_auction = $this->input->post('add_auction',TRUE);	  

				//$furnishing_type = $this->input->post('furnishing_type',TRUE);

				

				

				//$offer = (!empty($this->input->post('offer_chk')))? 1:0;	

			//	$offer = $this->input->post('offer_chk')? 1 : 0;			

			//$offer_text = $this->input->post('offer_chk') ? $this->input->post('offer_text') : '';

                $floor_plan_name = '';

                $property_floor_plan=$_FILES["property_floor_plan"]['name'];

				if(!empty($property_floor_plan)){

					$upload_directory = config_item('root_url').'applicationMediaFiles/property/florplan/';

					$temp_name = $_FILES['property_floor_plan']['tmp_name'];

					$ext = @pathinfo($property_floor_plan, PATHINFO_EXTENSION);

					$floor_plan_name   = time().rand(1000,99999).'.'.$ext;

					$file_path = $upload_directory.$floor_plan_name; 

					@move_uploaded_file($temp_name, $file_path);

				}

				

				

				

				$property_vedio_url=$_FILES["property_vedio_url"]['name'];

                $file_name='';

				if(!empty($property_vedio_url)){

					$upload_directory = config_item('root_url').'applicationMediaFiles/propertiesVedio/';

					$temp_name = $_FILES['property_vedio_url']['tmp_name'];

					$ext = @pathinfo($property_vedio_url, PATHINFO_EXTENSION);

					$file_name   = time().rand(1000,99999).'.'.$ext;

					$file_path = $upload_directory.$file_name; 

					@move_uploaded_file($temp_name, $file_path);

				}

				

			   $bedrooms=$bedrooms;
			   $property_type_name=selectData("property_types","where property_types_id='$property_type'");
			   $category_name=selectData("property_category","where property_category_id='$property_category'");
		  

		       $seo_url_string = ucwords($bedrooms.'  Bed '. $property_type_name[0]['typeName'].'  For '. $category_name[0]['categoryName']);
			   
			   $property_seo_url = seo_friendly_urls($seo_url_string,'','');	

				

                

				$data=array(

				             'user_id'=>$user_id,

				             'property_name'=>$property_seo_url,

							 'property_description'=>$property_description,

							 'property_category'=>$property_category,

							 'country'=>$country,

							 'city'=>$city,

							 'bedrooms'=>$bedrooms,

							 'property_type'=>$property_type,

							 'disclaimer'=>$disclaimer,

							 'state'=>$state,

							 'address'=>$address,

							 'latitude'=>$latitude,

							 'longitude'=>$longitude,

							 'zipcode'=>$zipcode,

							 //'offer'=>$offer,

							// 'offer_text'=>$offer_text,

							 'property_closing_date'=>$closing_date1,

							 'prices'=>$price,

							'bathrooms'=>$bathrooms,

							'num_floors'=>$num_floors,

							'num_recepts'=>$num_recepts,

							'price_modifier'=>$price_modifier,

							// 'furnishing_type'=>$furnishing_type,

							'county'=>$county,
							'auction_status'=>$add_auction ? 1 : 0,
							
							'property_availability'=>$property_availability,
							'status'=>'Under_review' 
				           );

                if(!empty($file_name)){

				   $file_name_data = array('video_url' => $file_name);	

				   $data = array_merge($data, $file_name_data);

				}

				if(!empty($floor_plan_name)){

					$floor_plan_name_data = array('floor_plan' => $floor_plan_name);

					$data = array_merge($data, $floor_plan_name_data);

				}

				

			   $where = array('property_id' => $id);				  

			   $this->property_model->data_update('property',$data,$where);

			   $nearby=$this->input->post('nearby',TRUE); 

			   

			   

			   

			   

			   $table = 'property_nearby_address';

	           $this->property_model->data_delete($table,'property_id',$id);

			   

			  

			   for($j=1; $j<=$nearby; $j++){

					$property_key=$this->input->post('nearbykey_'.$j);

					$property_val=$this->input->post('nearbyvalue_'.$j);

					

					

					$nearby_address=$this->input->post('nearbyaddress_'.$j);

					

					$latlang=address_to_latlng_nearby($nearby_address);

					
					if(!empty($latlang)){
					  $nearby_lat=$latlang['latitude'];
					  $nearby_lang=$latlang['longitude'];
					}

					$mydata=array('property_id'=>$id,'property_key'=>$property_key,'property_val'=>$property_val,'nearby_address'=>$nearby_address,'nearby_lat'=>$nearby_lat?$nearby_lat:'','nearby_lang'=>$nearby_lang?$nearby_lang:'');

					

					if(!empty($property_key)&&($property_val)){

					$this->property_model->data_insert('property_nearby_address',$mydata);

					}

			   }

			   $add_auction=$this->input->post('add_auction',TRUE); 

			   

		

			   

			   if(!empty($add_auction)){

			    

				$this->property_model->data_delete('property_auction','property_id',$id);

				

			    $start_data=$this->input->post('start_data',TRUE); 

				

				

				$auctionDate=explode('-',$start_data);

				

				

				$bid_price=$this->input->post('bid_price',TRUE); 

				

				$auction=array('property_id'=>$id,'user_id'=>$user_id,'start_date'=>strtotime($auctionDate[0]),'end_date'=>strtotime($auctionDate[1]),'auction_min_price'=>$bid_price);

			    $this->property_model->data_insert('property_auction',$auction);		

				

			   

			   }

			   

			   

			   

                $features_name = $this->input->post('features_name',TRUE);

				if(!empty($features_name)){

				$this->property_model->data_delete('property_features','property_id',$id);

					for($i=0; $i < count($features_name); $i++)

					 {

					  $features_name_data[] = array('features_id' => $features_name[$i],

					                         'property_id' => $id);

					 }

				    $this->db->insert_batch('property_features', $features_name_data);

				  }

				 

				 

				

			    $count = count($_FILES['property_image']['name']);

				$upload_directory = config_item('root_url').'applicationMediaFiles/propertiesImage/';

				for($i=0; $i<$count; $i++){

				  $file_name =$_FILES['property_image']['name'][$i];				

				  if(!empty($file_name)){				

					$temp_name = $_FILES['property_image']['tmp_name'][$i];				

					// Check if image file is a actual image or fake image				

					$check = getimagesize($temp_name);				

					if($check !== false) {				

				   // Check file size  				
//echo '--'.$_FILES['property_image']['size'][$i];
					 if($_FILES['property_image']['size'][$i] < 5242880) {	 //5mb  1027*10			

						$ext = @pathinfo($file_name, PATHINFO_EXTENSION);				

						$file_name   = time().rand(1000,99999).'.'.$ext;				

						$file_path = $upload_directory.$file_name;				

						@move_uploaded_file($temp_name, $file_path);				

						//insert query  en_property_image

						$property_image_title = $_POST['property_image_title'][$i];	

						$property_image_caption = $_POST['property_image_caption'][$i];

						$data=array('property_id'=>$id,'image_name'=>$file_name,'image_title' => $property_image_title,'image_caption' => $property_image_caption);

						$insertId= $this->property_model->data_insert('property_image',$data);				

						$filePath= $upload_directory.$file_name;				

						$img = resize_images($filePath,271,221, $upload_directory.'thumb/'); 	

						$img = resize_images($filePath,450,300, $upload_directory.'350325/');

						$img = resize_images($filePath,1300,400, $upload_directory.'1300400/');

						$img = resize_images($filePath,800,600, $upload_directory.'800600/'); 				

					 }	//end inner if			

				   }//end outer if				

				  }//end outer most if

                } //end foreach	  

				if($add_auction!='on'){  
				  redirect('myproperties/');
                }else{
				  redirect('myauctionproperty/');
				}

			//end validation if

			}else{

			  $data['post_val'] = array($this->input->post());

			  $this->load->view('my_account/editProperty',$data);

			}

			//end $_POST if

	   }

	      $this->load->view('my_account/editProperty',$data);

	   	  

	}

	

	public function view($proid){
	  $this->load->model('Common');	
		 
	      $property=  $this->Common->property_info_get($proid);

		  $bedrooms=$property[0]['bedrooms'];
		  $property_type_name=$property[0]['typeName'];
		  $category_name=$property[0]['categoryName'];
		  $auction_status=$property[0]['auction_status'];
		  
		 
		  
		  if($auction_status=='1'){
			  $detailUrl='property/auction/';
			}else{
			  $detailUrl='property/details/';
			}
		  

		  $seo_url_string = ucwords($bedrooms.'  Bed '. $property_type_name.'  For '. $category_name);
		  $property_seo_url = seo_friendly_urls($seo_url_string,'',$proid);
		  
		  $propertyurl=config_item('base_url').$detailUrl.$property_seo_url;
		  
		  redirect($propertyurl);
         
	}

	public function deleteImages(){

		$id = $this->input->post('id',TRUE);

		$table = 'property_image';

		$where = 'where property_image_id = '.$id;

		$data = $this->property_model->select($table,$where);

		$image = $data[0]['image_name'];

		$path = config_item('base_url').'applicationMediaFiles/propertiesImage/';

		@unlink($path.$image);	

		$this->property_model->data_delete($table,'property_image_id',$id);	  

		return TRUE;

	}//end deleteImages function

	public function deleteNearby(){

		$id = $this->input->post('id',TRUE);

		$table = 'property_nearby_address';

		$this->property_model->data_delete($table,'property_nearby_address_id',$id);	  

		return TRUE;

	}

	  

	private function __setFormRules($post = ''){

	    

		$this->form_validation->set_rules('property_category', 'Property catagory', 'required');
		
		$this->form_validation->set_rules('price', 'Property price', 'required');
		
		$this->form_validation->set_rules('closing_date', 'Closing date', 'required');

		$this->form_validation->set_rules('country', 'Country', 'required');

		$this->form_validation->set_rules('city', 'Cities', 'required');

		$this->form_validation->set_rules('bedrooms', 'Bedrooms', 'required');

		$this->form_validation->set_rules('property_type', 'Type', 'required');

		$this->form_validation->set_rules('disclaimer', 'Disclaimer', 'required');

		$this->form_validation->set_rules('state', 'States', 'required');

		$this->form_validation->set_rules('address', 'Address', 'required');

		$this->form_validation->set_rules('zipcode', 'Zipcode', 'required');

  		return $this->form_validation->run();

    }

	

	public function _example_output($output = null){

		$this->load->view('my_account/setting',$output);

	}

	public function checkMail($email='',$subject='',$msg_body=''){

	  $emaildata['body'] = $msg_body; //$message;

	  $attachment = array();

	  $subj = $subject;

	  $message = $this->load->view('my_account/message/template/addProperty_message', $emaildata, TRUE);

	  $toEmail = $email;

	  $fromEmail = array('email' => config_item('no_reply'),'name' => config_item('site_name'));

	  $result = sendUserEmailCI($toEmail, $fromEmail, $subj, $message, $attachment);

	}

	public function editimagetitle(){

		$pkid = @$_REQUEST['id'];

		$imagetitle = @$_REQUEST['image_title'];

		if (empty($pkid)) die('error');

		$where = array('property_image_id' => $pkid);

		$data = array('image_title' => $imagetitle);

	    $this->property_model->data_update('property_image',$data,$where);

		die('success');

	}
	
	public function editimagecaption(){

		$pkid = @$_REQUEST['id'];

		$caption = @$_REQUEST['caption'];

		if (empty($pkid)) die('error');

		$where = array('property_image_id' => $pkid);

		$data = array('image_caption' => $caption);

	    $this->property_model->data_update('property_image',$data,$where);

		die('success');

	}
	
	public function log_user_before_delete($primary_key){
	
       
	    $table = 'property_image';
        $where = "where property_id = $primary_key";
		$data = $this->property_model->select($table,$where);
		 $path = config_item('root_url').'applicationMediaFiles/propertiesImage/'; 
		if(!empty($data)){
		     foreach($data as $val){
				 
				 $image = $val['image_name'];
				 if (file_exists($path.$image)) { 
			    	 @unlink($path.$image);
				 }
				  if (file_exists($path.'thumb/'.$image)) { 
			    	@unlink($path.'thumb/'.$image);
				 }

				 if (file_exists($path.'350325/'.$image)) { 
			    	@unlink($path.'350325/'.$image);
				 }

				 if (file_exists($path.'350325/'.$image)) { 
			    	 @unlink($path.'350325/'.$image);
				 }

				 if (file_exists($path.'800600/'.$image)) { 
			    	 @unlink($path.'800600/'.$image);
				 }
				 
				 if (file_exists($path.'1300400/'.$image)) { 
			    	 @unlink($path.'1300400/'.$image);
				 }

				 
				 
				 
			 }	
		}


        $where = "where property_id = $primary_key";
		$data = $this->property_model->select('property',$where);
		if(!empty($data)){
		     foreach($data as $val){
				 $floor_plan = $val['floor_plan'];
				 $video_url = $val['video_url'];
		  $propertiesVedioUrl = config_item('root_url').'applicationMediaFiles/propertiesVedio/'; 
		  $floorPlanUrl = config_item('root_url').'applicationMediaFiles/floorPlanImage/';
                 if (file_exists($propertiesVedioUrl.$video_url)) { 
			    	@unlink($propertiesVedioUrl.$video_url);
				 }
				 if (file_exists($floorPlanUrl.$floor_plan)) {
			    	@unlink($floorPlanUrl.$floor_plan);
				 }
				   
				 
			 }
		}
		
		$this->property_model->data_delete('property','property_id',$primary_key);
		$this->property_model->data_delete('property_features','property_id',$primary_key);	 
		$this->property_model->data_delete('property_nearby_address','property_id',$primary_key);	
		$this->property_model->data_delete('property_image','property_id',$primary_key);	  
		
	$delte_array = array('success'=>true,
			'success_message'=>'<p>Your data has been successfully 
			                       deleted from the database.</p>');
			$outPut = json_encode($delte_array);
		
		print($outPut);
			return $outPut;
		
    }
	
	public function bulk_action(){

				

		$action = $this->input->post("action", TRUE);

		$table_name = $this->input->post("table_name", TRUE);

		$field_name = $this->input->post("field_name", TRUE);

		$primary_key = $this->input->post("primary_key", TRUE);

		

		$selection = rtrim($this->input->post("selection", TRUE), '|');

		$id_array = ($selection) ? explode("|", $selection) : '';

		

		if($id_array != '' && $table_name !='' && $primary_key !=''){

			switch($action){

				case 'delete':

					foreach($id_array as $item):

						if($item != ''):

							$this->db->delete($table_name, array($primary_key => $item));

						endif;

					endforeach;

					echo count($id_array).' Items deleted!';

				break;

				case 'publish':

					foreach($id_array as $item):

						if($item != '' && $field_name !=''):

							$this->db->update($table_name, array($field_name => 'Active'), array($primary_key => $item));
							

						endif;

					endforeach;

					echo count($id_array).' Items published!';

				break;

				case 'unpublish':

					foreach($id_array as $item):

						if($item != '' && $field_name !=''):

							$this->db->update($table_name, array($field_name => 'Inactive'), array($primary_key => $item));
							

						endif;

					endforeach;

					echo count($id_array).' Items unpublished!';

				break;	

			}

		}else{

		   echo 'Kindly Select Atleast One Item!';

		}

	}  

}