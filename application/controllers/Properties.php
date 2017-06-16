<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Properties extends CI_Controller { 

	var $userID;

	public function __construct(){

		parent::__construct();

		$this->load->database();
		$this->load->helper(array('url','common'));

		$this->load->library(array('session', 'form_validation','file_upload','grocery_CRUD','ajax_grocery_crud'));

		//user_group_check();

		// check user login			 

		 if(!$this->session->userdata('user_id')) {				

			redirect('login');

		 }

		 $this->userID = $this->session->userdata('user_id');

		

		$this->load->model(array('common_model', 'Property_model'));

		$this->language =  language_load(); 

		

	}

	private function __setFormRules($post = ''){	    


		$this->form_validation->set_rules('property_category', 'Property catagory', 'required');
		
		$this->form_validation->set_rules('price', 'Property price', 'required');
		
		$this->form_validation->set_rules('closing_date', 'Closing date', 'required');
		
		$this->form_validation->set_rules('property_assign', 'Property assign', 'required');

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

		$this->load->view('setting',$output);

	}

	

	public function index(){		

		$crud = new grocery_CRUD();
$crud->unset_jquery();
		$crud->set_table('property');	
		$crud->set_relation('user_id', 'user', 'firstName');
		$crud->set_relation('property_category', 'property_category', 'categoryName');	
		$crud->set_relation('property_type', 'property_types', 'typeName');
		$crud->where('property.auction_status', '0');

		$crud->unset_print();
		$crud->unset_export();
		$crud->unset_delete();

		/*$crud->set_relation('country', 'country', 'country');

		$crud->set_relation('state', 'country_regions', 'region');

		$crud->set_relation('city', 'country_region_cities', 'city');*/

		
		$crud->columns('address', 'user_id', 'property_category', 'property_type', 'property_availability','status');

		//'location','country','regionName','city',

		//$crud->callback_column('location', array($this,'_display_location'));

		$crud->set_subject('Property');
		$crud->set_add_url_path(base_url('properties/add'));
		$crud->set_edit_url_path(base_url('properties/edit'));
		$crud->set_read_url_path(base_url('properties/view'));
		$crud->set_bulk_action_url(base_url('properties/bulk_action'));		
		$crud->set_data_status_field_name('status');
		$crud->display_as('user_id', 'Upload By')->display_as('property_category', 'Category')->display_as('property_type', 'Type')->display_as('property_availability', 'Availability');
		$crud->unset_print();
		$crud->unset_export();
		
		
		$crud->add_action('Stats', 'clip-stats', base_url().'properties/viewStats/', 'btn btn-xs btn-default openPopupDialog');		
		
		$crud->add_action('', 'fa fa-trash-o', base_url().'properties/log_user_before_delete/', 'btn btn-danger delete-row');		
			
			
		
						
						
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
		

		$output = $crud->render();
		
		$page_title = array('page_title'=>'Manage Properties', 'stylesheet'=>$stylesheet, 'scriptsrc'=>$scriptsrc, 'script'=>$script);

		$outputData = array_merge((array)$output, $page_title);		
		
		$this->_example_output($outputData);

		

	}
	
	public function log_user_before_delete($primary_key){
	
       
	    $table = 'property_image';
        $where = "where property_id = $primary_key";
		$data = $this->Property_model->select($table,$where);
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
		$data = $this->Property_model->select('property',$where);
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
		
		$this->Property_model->data_delete('property','property_id',$primary_key);
		$this->Property_model->data_delete('property_features','property_id',$primary_key);	 
		$this->Property_model->data_delete('property_nearby_address','property_id',$primary_key);	
		$this->Property_model->data_delete('property_image','property_id',$primary_key);	  
		
	$delte_array = array('success'=>true,
			'success_message'=>'<p>Your data has been successfully 
			                       deleted from the database.</p>');
			$outPut = json_encode($delte_array);
		
		print($outPut);
			return $outPut;
		
    }

	public function _display_location($value, $row){		

		$location = $row->city.', '.$row->region.', '.$row->country;		

		return $location;

	}	

	public function add(){
	  	
	   $this->lang->load('agent/property', $this->language);	   

	   $data['lang_data'] = $this->lang->language;	   

	   $data['near_json'] = $this->Property_model->selectJson("manage_nearby","where status='Active'"); 

	   $data['page_title'] = 'Add Properties';

	   if($_POST){

			$formValidation = $this->__setFormRules($this->input->post());

			if ($this->form_validation->run() == TRUE){

			    $user_id = $this->input->post('property_assign',TRUE);
                 
				 
				 
				 
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

				//$offer_text = $this->input->post('offer_text') ? $this->input->post('offer_text') : '';

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

							

							 'property_closing_date'=>$closing_date,

							 'prices'=>$price,

							 'bathrooms'=>$bathrooms,

							 'num_floors'=>$num_floors,

							 'num_recepts'=>$num_recepts,

							 'price_modifier'=>$price_modifier,
							 'auction_status'=>$add_auction ? '1' : '0',

							 'county'=>$county

				           );	

						    				  

			   $lastinsertId= $this->Property_model->data_insert('property',$data);	

			   			   
			   

			   $bedrooms=$bedrooms;
			   $property_type_name=selectData("property_types","where property_types_id='$property_type'");
			   $category_name=selectData("property_category","where property_category_id='$property_category'");
		  

		       $seo_url_string = ucwords($bedrooms.'  Bed '. $property_type_name[0]['typeName'].'  For '. $category_name[0]['categoryName']);
			   
			   $property_seo_url = seo_friendly_urls($seo_url_string,'','');
			   
			   $where = array('property_id' => $lastinsertId);
			   
			   $pnamedata=array('property_name'=>$property_seo_url);
			   
			   $this->Property_model->data_update('property',$pnamedata,$where);
			   
			   
			   

			   $add_auction=$this->input->post('add_auction',TRUE); 

			   

			   

			   if(!empty($add_auction)){

			   

			    $start_data=$this->input->post('start_data',TRUE); 

				

				

				$auctionDate=explode('-',$start_data);

				

				

				$bid_price=$this->input->post('bid_price',TRUE); 

				

				$auction=array('property_id'=>$lastinsertId,'user_id'=>$user_id,'start_date'=>strtotime($auctionDate[0]),'end_date'=>strtotime($auctionDate[1]),'auction_min_price'=>$bid_price);

			    $this->Property_model->data_insert('property_auction',$auction);		

				

			   

			   }

			   $data['fullname'] = $this->session->userdata('firstName').' '.$this->session->userdata('lastName');
				$data['firstname'] = $this->session->userdata('firstName');
				$data['profile_image'] = $this->session->userdata('profile_image');
				
				$property_seo_url = seo_friendly_urls($seo_url_string,'',$lastinsertId);
				$data['property_seo_url']=$property_seo_url;	
					
                $seousername = str_replace('&nbsp;', '-', $data['fullname']);
                $data['recieverseo'] = seo_friendly_urls($seousername,'',$this->session->userdata('user_id'));
				

				
				$message = $this->load->view('message/template/addProperty_message', $data, TRUE); 
				$toEmail = $this->session->userdata('email');
				$fromEmail = array('email' => config_item('no_reply'),'name' => config_item('site_name'));
				$subject = ucwords(config_item('site_name').' - '.$data['fullname'].' Add property');
				$attachment = array();
				$mailresult = sendUserEmailCI($toEmail, $fromEmail, $subject, $message, $attachment);

			   

			   $features_name = $this->input->post('features_name',TRUE);

				if(!empty($features_name)){

					for($i=0; $i < count($features_name); $i++)

					 {

					  $features_name_data[] = array('features_id' => $features_name[$i],

					                         'property_id' => $lastinsertId);

					 }

				    $this->db->insert_batch('property_features', $features_name_data);

				  }


			   $nearby=$this->input->post('nearby',TRUE); 

			   for($j=1; $j<=$nearby; $j++){

					$property_key=$this->input->post('nearbykey_'.$j,TRUE);

					$property_val=$this->input->post('nearbyvalue_'.$j,TRUE);

					

					$nearby_address=$this->input->post('nearbyaddress_'.$j);

					

					$latlang=address_to_latlng_nearby($nearby_address);

					if(!empty($latlang)){
					  $nearby_lat=$latlang['latitude'];
					  $nearby_lang=$latlang['longitude'];
					}

					$mydata=array('property_id'=>$lastinsertId,'property_key'=>$property_key,'property_val'=>$property_val,'nearby_address'=>$nearby_address,'nearby_lat'=>$nearby_lat?$nearby_lat:'','nearby_lang'=>$nearby_lang?$nearby_lang:'');

					$this->Property_model->data_insert('property_nearby_address',$mydata);

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

					 if($_FILES['property_image']['size'][$i] < 5242880) {	 //5mb  1027*10				

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

						

						$insertId= $this->Property_model->data_insert('property_image',$data);					

						$filePath= $upload_directory.$file_name;					

						$img = resize_images($filePath,271,221, $upload_directory.'thumb/'); 

						$img = resize_images($filePath,450,300, $upload_directory.'350325/');

						$img = resize_images($filePath,1300,400, $upload_directory.'1300400/');

						$img = resize_images($filePath,800,600, $upload_directory.'800600/'); 					

					 }				

				   }				

				  }

                } 				

				$get_c_s_city = $this->Property_model->country_state_city($country,$state,$city);

				if(!empty($get_c_s_city)){			

				   $country = $get_c_s_city['country'];			

				   $state = $get_c_s_city['region'];			

				   $city = $get_c_s_city['city']; 			

				   $address = $country.','.$state.','.$city.','.$address;			

				   $where = array('property_id'=>$lastinsertId);			

				   address_to_latlng($address,$where);			

				 }	  

				if($add_auction!='on'){ 
				  redirect('properties/');
                }else{
				  redirect('auctionproperty/');
				}

				die;

			}else{

			  $data['post_val'] = array($this->input->post());

			  $this->load->view('property/addProperty',$data);

			}

	   }else{

	     $this->load->view('property/addProperty', $data);

	   }

	}	

	public function edit($proid){
	  

	   $this->lang->load('agent/property', $this->language);

	   $data['lang_data']=$this->lang->language;

	   $data['near_json'] = $this->Property_model->selectJson("manage_nearby","where status='Active'"); 

	   $data['property_data']= $this->Property_model->get_property_details($proid);

	   $data['page_title'] = 'Edit Properties';
      
	 

	   if($_POST){

		  

			$id = $proid;

			$formValidation = $this->__setFormRules($this->input->post());

			if ($this->form_validation->run() == TRUE){

			    $user_id = $this->input->post('property_assign',TRUE);
				
				$first_name = $data['property_data'][0]['firstName'];
				$last_name = $data['property_data'][0]['lastName'];
				$email = $data['property_data'][0]['email'];
				$profile_image = $data['property_data'][0]['profile_image'];
				

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
				
				$status=$this->input->post('status',TRUE);
				
				$property_availability = $this->input->post('property_availability',TRUE);
				 $add_auction = $this->input->post('add_auction',TRUE);	
				

				//$furnishing_type = $this->input->post('furnishing_type',TRUE);

				

				

				//$offer = (!empty($this->input->post('offer_chk')))? 1:0;	

				//$offer = $this->input->post('offer_chk')? 1 : 0;			

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

							 'property_closing_date'=>$closing_date1,

							 'prices'=>$price,

							'bathrooms'=>$bathrooms,

							'num_floors'=>$num_floors,

							'num_recepts'=>$num_recepts,

							'price_modifier'=>$price_modifier,
                             'auction_status'=>$add_auction ? '1' : '0',
							// 'furnishing_type'=>$furnishing_type,

							'county'=>$county,
							'status'=>$status,
							'property_availability'=>$property_availability

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
              
			   $this->Property_model->data_update('property',$data,$where);
			  
			   
			  
			  
			    $property_seo_url = seo_friendly_urls($seo_url_string,'',$id);
		  
		  $data['property_seo_url']=$property_seo_url;
		  
		  $data['admin_fullname'] = $this->session->userdata('firstName').' ' .$this->session->userdata('lastName');
          $data['admin_firstName']=$this->session->userdata('firstName');
	      $data['status']=$status;    
	      $data['admin_profile_image']=$this->session->userdata('profile_image');  
		  
		  $adusername=ucwords($this->session->userdata('firstName').' '.$this->session->userdata('lastName'));
          $seosendername = str_replace('&nbsp;', '-', $adusername);
          $data['senderseo'] = seo_friendly_urls($seosendername,'',$this->session->userdata('user_id'));
		  
		  
		  $username=ucwords($first_name.' '.$last_name);
          $seousername = str_replace('&nbsp;', '-', $username);
          $data['recieverseo'] = seo_friendly_urls($seousername,'',$user_id);
		  
		  
		            $data['fullname'] = $username;
					$data['firstname'] = $first_name;
					$data['email'] = $email;
					$data['profile_image'] = $profile_image;
					
					$data['status'] = $status;
                    
					
					
					$message = $this->load->view('message/template/property_status_message', $data, TRUE); 
					$toEmail = $email;
					$fromEmail = array('email' => config_item('no_reply'),'name' => config_item('site_name'));
					$subject = ucwords(config_item('site_name').' - Your Property Status '.$status.'');
					$attachment = array();
					$result = sendUserEmailCI($toEmail, $fromEmail, $subject, $message, $attachment); 
			
			
			   
			   

			   $nearby=$this->input->post('nearby',TRUE); 

			   

			   

			   

			   

			   $table = 'property_nearby_address';

	           $this->Property_model->data_delete($table,'property_id',$id);

			   

			  

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

					$this->Property_model->data_insert('property_nearby_address',$mydata);

					}

			   }

			   $add_auction=$this->input->post('add_auction',TRUE); 

			   

		

			   
                $this->Property_model->data_delete('property_auction','property_id',$id);
			   if(!empty($add_auction)){

			    

				

				

			    $start_data=$this->input->post('start_data',TRUE); 

				

				

				$auctionDate=explode('-',$start_data);

				

				

				$bid_price=$this->input->post('bid_price',TRUE); 

				

				$auction=array('property_id'=>$id,'user_id'=>$user_id,'start_date'=>strtotime($auctionDate[0]),'end_date'=>strtotime($auctionDate[1]),'auction_min_price'=>$bid_price);

			    $this->Property_model->data_insert('property_auction',$auction);		

				

			   

			   }

			   

			   

			   

                $features_name = $this->input->post('features_name',TRUE);

				if(!empty($features_name)){

				$this->Property_model->data_delete('property_features','property_id',$id);

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

					 if($_FILES['property_image']['size'][$i] < 5242880) {	 //5mb  1027*10		 		

						$ext = @pathinfo($file_name, PATHINFO_EXTENSION);				

						$file_name   = time().rand(1000,99999).'.'.$ext;				

						$file_path = $upload_directory.$file_name;				

						@move_uploaded_file($temp_name, $file_path);				

						//insert query  en_property_image

						$property_image_title = $_POST['property_image_title'][$i];	

						$property_image_caption = $_POST['property_image_caption'][$i];

						$data=array('property_id'=>$id,'image_name'=>$file_name,'image_title' => $property_image_title,'image_caption' => $property_image_caption);

                       

						

						$insertId= $this->Property_model->data_insert('property_image',$data);				

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
				  redirect('properties/');
                }else{
				  redirect('auctionproperty/');
				}

			//end validation if

			}else{

			  $data['post_val'] = array($this->input->post());

			  $this->load->view('property/editProperty',$data);

			}

			//end $_POST if

	   }

	      $this->load->view('property/editProperty',$data);

	   	  
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
		  
		 
		   $propertyurl=config_item('site_url').$detailUrl.$property_seo_url;
		  
		  redirect($propertyurl);
         
		
		
	}



    public function category() {

	 $crud = new grocery_CRUD();
$crud->unset_jquery();

	 $crud->unset_print();
	$crud->unset_export();

     $crud->set_table('property_category');

     $crud->set_subject('Property Category');

     $crud->required_fields('categoryName','status');

	 

	 $crud->set_bulk_action_url(base_url('setting/bulk_action'));		

	 $crud->set_data_status_field_name('status');

	 

     $output = $crud->render();

     $page_title = array('page_title' => 'Property Category');     $outputData = array_merge((array) $output , $page_title);

     $this->_example_output($outputData);



    }

	public function type() {



		  $crud = new grocery_CRUD();
$crud->unset_jquery();

		  $crud->unset_print();
		  $crud->unset_export();

	      $crud->set_table('property_types');

	      $crud->set_subject('Property Types');

          $crud->required_fields('typeName','status');

		  

		  $crud->set_bulk_action_url(base_url('setting/bulk_action'));		

		  $crud->set_data_status_field_name('status');

		  

          $output = $crud->render();

          $page_title = array('page_title' => 'Property Types');

        $outputData = array_merge((array) $output , $page_title);

        $this->_example_output($outputData);



    }

	public function featureCategory() {

	 $crud = new grocery_CRUD();
$crud->unset_jquery();
	 $crud->unset_print();
	 $crud->unset_export();
	 $crud->set_subject('Category');
		
	 $crud->set_table('manage_property_features');

	 $crud->where('features_parent_id','0');

	 $crud->columns('features_name','status');

	 $crud->display_as('features_name', 'Category name');

	 $crud->required_fields('features_name','status');

	 $crud->fields('features_name', 'status');

	 $crud->set_bulk_action_url(base_url('properties/bulk_action'));
	
	 $crud->set_data_status_field_name('status');

	 $output = $crud->render();
	
	 $page_title = array('page_title' => 'Feature Category');     
	 $outputData = array_merge((array) $output , $page_title);
	 $this->_example_output($outputData);
    }	

	public function featureList() {

	 $crud = new grocery_CRUD();
$crud->unset_jquery();

	 $crud->unset_print();

	 $crud->unset_export();

	 $crud->set_subject('Feature');

	 

     $crud->set_table('manage_property_features');

	 $crud->where('manage_property_features.features_parent_id >', '0');

	 $crud->set_relation('features_parent_id', 'manage_property_features', 'features_name', array('features_parent_id' => '0'));

	 $crud->columns('features_parent_id', 'features_name', 'status');

	 $crud->display_as('features_parent_id', 'category name');

     $crud->required_fields('features_parent_id', 'features_name', 'status');

	 $crud->fields('features_parent_id', 'features_name', 'status');

	 $crud->set_bulk_action_url(base_url('properties/bulk_action'));		

	 $crud->set_data_status_field_name('status');

	 

     $output = $crud->render();

     $page_title = array('page_title' => 'Property Feature List');

	 $outputData = array_merge((array) $output , $page_title);

     $this->_example_output($outputData);

	 

    }

	public function typeFeatursMap() {

	 $crud = new grocery_CRUD();
$crud->unset_jquery();

	 $crud->unset_print();

	 $crud->unset_export();

	 $crud->unset_add();

	 $crud->unset_delete();

	 $crud->unset_bulk_operations();

	 

	 $crud->set_subject('Feature');

	 

	 $crud->set_table('property_types');

	 $crud->where('property_types.status', 'Active');

	 

	 $crud->set_relation_n_n('assign_features', 'property_type_features_map', 'manage_property_features','property_types_id','feature_id', 'feature_title', 'priority', array('parent_id' => '0', 'status'=>'Active'));

	 

	 $crud->columns('typeName', 'assign_features');	 

	 $crud->fields('typeName', 'assign_features');

	 $crud->field_type('typeName', 'readonly');

	 

	 

     $output = $crud->render();

     $page_title = array('page_title' => 'Property Features Map');

	 $outputData = array_merge((array) $output , $page_title);

     $this->_example_output($outputData);

	 

    }

	

	

	public function appliances(){

        $crud = new grocery_CRUD();
$crud->unset_jquery();

		$crud->set_subject('Appliances');

		      

	    $crud->set_table('manage_appliances');		        	

        //$crud->columns('setting_name','setting_value','status');

        //$crud->required_fields('setting_name' , 'setting_value', 'status');

		

		$crud->set_bulk_action_url(base_url('setting/bulk_action'));		

		$crud->set_data_status_field_name('status');

		

		$crud->unset_fields('createdDate');	



        $output = $crud->render();

        $page_title = array('page_title' => 'Property Appliances');

        $outputData = array_merge((array) $output , $page_title);

        $this->_example_output($outputData);



    }

	public function basement(){

        $crud = new grocery_CRUD();
$crud->unset_jquery();

		$crud->set_subject('Basement');

		      

	    $crud->set_table('manage_basement');		        	

        //$crud->columns('setting_name','setting_value','status');

        //$crud->required_fields('setting_name' , 'setting_value', 'status');

		

		$crud->set_bulk_action_url(base_url('setting/bulk_action'));		

		$crud->set_data_status_field_name('status');

		

		$crud->unset_fields('createdDate');	



        $output = $crud->render();

        $page_title = array('page_title' => 'Property Basement');

        $outputData = array_merge((array) $output , $page_title);

        $this->_example_output($outputData);



    }	

	public function roomsType(){

        $crud = new grocery_CRUD();
$crud->unset_jquery();

		$crud->set_subject('RoomsType');

		      

	    $crud->set_table('manage_rooms');		        	

        //$crud->columns('setting_name','setting_value','status');

        //$crud->required_fields('setting_name' , 'setting_value', 'status');

		

		$crud->set_bulk_action_url(base_url('setting/bulk_action'));		

		$crud->set_data_status_field_name('status');

		

		$crud->unset_fields('createdDate');	



        $output = $crud->render();

        $page_title = array('page_title' => 'Rooms Type');

        $outputData = array_merge((array) $output , $page_title);

        $this->_example_output($outputData);



    }	

	public function indoorFeatures(){

        $crud = new grocery_CRUD();
$crud->unset_jquery();

		$crud->set_subject('Indoor Features');

		      

	    $crud->set_table('manage_indoor_features');		        	

        //$crud->columns('setting_name','setting_value','status');

        //$crud->required_fields('setting_name' , 'setting_value', 'status');

		

		$crud->set_bulk_action_url(base_url('setting/bulk_action'));		

		$crud->set_data_status_field_name('status');

		

		$crud->unset_fields('createdDate');	



        $output = $crud->render();

        $page_title = array('page_title' => 'Indoor Features');

        $outputData = array_merge((array) $output , $page_title);

        $this->_example_output($outputData);



    }	

	public function outdoorAmenities(){

        $crud = new grocery_CRUD();
$crud->unset_jquery();

		$crud->set_subject('Outdoor Amenities');

		      

	    $crud->set_table('manage_outdoor_amenities');		        	

        //$crud->columns('setting_name','setting_value','status');

        //$crud->required_fields('setting_name' , 'setting_value', 'status');

		

		$crud->set_bulk_action_url(base_url('setting/bulk_action'));		

		$crud->set_data_status_field_name('status');

		

		$crud->unset_fields('createdDate');	



        $output = $crud->render();

        $page_title = array('page_title' => 'Outdoor Amenities');

        $outputData = array_merge((array) $output , $page_title);

        $this->_example_output($outputData);



    }	

	public function buildingAmenities(){

        $crud = new grocery_CRUD();
$crud->unset_jquery();

		$crud->set_subject('Building Amenities');

		      

	    $crud->set_table('manage_building_amenities');		        	

        //$crud->columns('setting_name','setting_value','status');

        //$crud->required_fields('setting_name' , 'setting_value', 'status');

		

		$crud->set_bulk_action_url(base_url('setting/bulk_action'));		

		$crud->set_data_status_field_name('status');

		

		$crud->unset_fields('createdDate');	



        $output = $crud->render();

        $page_title = array('page_title' => 'Building Amenities');

        $outputData = array_merge((array) $output , $page_title);

        $this->_example_output($outputData);



    }	

	public function architechturalStyle(){

        $crud = new grocery_CRUD();
$crud->unset_jquery();

		$crud->set_subject('Architechtural Style');

		      

	    $crud->set_table('manage_architectural_style');		        	

        //$crud->columns('setting_name','setting_value','status');

        //$crud->required_fields('setting_name' , 'setting_value', 'status');

		

		$crud->set_bulk_action_url(base_url('setting/bulk_action'));		

		$crud->set_data_status_field_name('status');

		

		$crud->unset_fields('createdDate');	



        $output = $crud->render();

        $page_title = array('page_title' => 'Architechtural Style');

        $outputData = array_merge((array) $output , $page_title);

        $this->_example_output($outputData);



    }

	

	public function floorCovering(){

        $crud = new grocery_CRUD();
$crud->unset_jquery();

		$crud->set_subject('floor Covering');

		      

	    $crud->set_table('manage_floor_covering');		        	

        //$crud->columns('setting_name','setting_value','status');

        //$crud->required_fields('setting_name' , 'setting_value', 'status');

		

		$crud->set_bulk_action_url(base_url('setting/bulk_action'));		

		$crud->set_data_status_field_name('status');

		

		$crud->unset_fields('createdDate');	



        $output = $crud->render();

        $page_title = array('page_title' => 'Floor Covering');

        $outputData = array_merge((array) $output , $page_title);

        $this->_example_output($outputData);



    }

	public function exteriorType(){

        $crud = new grocery_CRUD();
$crud->unset_jquery();

		$crud->set_subject('Exterior Type');

		      

	    $crud->set_table('manage_exterior');		        	

        //$crud->columns('setting_name','setting_value','status');

        //$crud->required_fields('setting_name' , 'setting_value', 'status');

		

		$crud->set_bulk_action_url(base_url('setting/bulk_action'));		

		$crud->set_data_status_field_name('status');

		

		$crud->unset_fields('createdDate');	



        $output = $crud->render();

        $page_title = array('page_title' => 'Exterior Type');

        $outputData = array_merge((array) $output , $page_title);

        $this->_example_output($outputData);



    }

	public function parkingType(){

        $crud = new grocery_CRUD();
$crud->unset_jquery();

		$crud->set_subject('Parking Type');

		      

	    $crud->set_table('manage_parking');		        	

        //$crud->columns('setting_name','setting_value','status');

        //$crud->required_fields('setting_name' , 'setting_value', 'status');

		

		$crud->set_bulk_action_url(base_url('setting/bulk_action'));		

		$crud->set_data_status_field_name('status');

		

		$crud->unset_fields('createdDate');	



        $output = $crud->render();

        $page_title = array('page_title' => 'Parking Type');

        $outputData = array_merge((array) $output , $page_title);

        $this->_example_output($outputData);



    }

	public function roofStyle(){

        $crud = new grocery_CRUD();
$crud->unset_jquery();

		$crud->set_subject('Roof Style');

		      

	    $crud->set_table('manage_roof');		        	

        //$crud->columns('setting_name','setting_value','status');

        //$crud->required_fields('setting_name' , 'setting_value', 'status');

		

		$crud->set_bulk_action_url(base_url('setting/bulk_action'));		

		$crud->set_data_status_field_name('status');

		

		$crud->unset_fields('createdDate');	



        $output = $crud->render();

        $page_title = array('page_title' => 'Roof Style');

        $outputData = array_merge((array) $output , $page_title);

        $this->_example_output($outputData);



    }

	public function areaViews(){

        $crud = new grocery_CRUD();
$crud->unset_jquery();

		$crud->set_subject('Area Views');

		      

	    $crud->set_table('manage_view');		        	

        //$crud->columns('setting_name','setting_value','status');

        //$crud->required_fields('setting_name' , 'setting_value', 'status');

		

		$crud->set_bulk_action_url(base_url('setting/bulk_action'));		

		$crud->set_data_status_field_name('status');

		

		$crud->unset_fields('createdDate');	



        $output = $crud->render();

        $page_title = array('page_title' => 'Area Views');

        $outputData = array_merge((array) $output , $page_title);

        $this->_example_output($outputData);



    }

	public function utilityCoolingType(){

        $crud = new grocery_CRUD();
$crud->unset_jquery();

		$crud->set_subject('Cooling Type');

		      

	    $crud->set_table('manage_cooling_type');		        	

        //$crud->columns('setting_name','setting_value','status');

        //$crud->required_fields('setting_name' , 'setting_value', 'status');

		

		$crud->set_bulk_action_url(base_url('setting/bulk_action'));		

		$crud->set_data_status_field_name('status');

		

		$crud->unset_fields('createdDate');	



        $output = $crud->render();

        $page_title = array('page_title' => 'Utility Cooling Type');

        $outputData = array_merge((array) $output , $page_title);

        $this->_example_output($outputData);



    }

	public function utilityHeatingType(){

        $crud = new grocery_CRUD();
$crud->unset_jquery();

		$crud->set_subject('Heating Type');

		      

	    $crud->set_table('manage_heating_type');		        	

        //$crud->columns('setting_name','setting_value','status');

        //$crud->required_fields('setting_name' , 'setting_value', 'status');

		

		$crud->set_bulk_action_url(base_url('setting/bulk_action'));		

		$crud->set_data_status_field_name('status');

		

		$crud->unset_fields('createdDate');	



        $output = $crud->render();

        $page_title = array('page_title' => 'Utility Heating Type');

        $outputData = array_merge((array) $output , $page_title);

        $this->_example_output($outputData);



    }

	public function utilityHeatingFuel(){

        $crud = new grocery_CRUD();
$crud->unset_jquery();

		$crud->set_subject('Heating Fuel');



	    $crud->set_table('manage_heating_fuel');		        	

        //$crud->columns('setting_name','setting_value','status');

        //$crud->required_fields('setting_name' , 'setting_value', 'status');

		

		$crud->set_bulk_action_url(base_url('setting/bulk_action'));		

		$crud->set_data_status_field_name('status');

		

		$crud->unset_fields('createdDate');	



        $output = $crud->render();

        $page_title = array('page_title' => 'Utility Heating Fuel');

        $outputData = array_merge((array) $output , $page_title);

        $this->_example_output($outputData);



    }
	
	function nearby(){
	
	   $crud = new grocery_CRUD();
$crud->unset_jquery();

	 $crud->unset_print();

	 $crud->unset_export();

	 $crud->set_subject('Nearby');

     $crud->set_table('manage_nearby');
	 
	 $crud->unset_fields('createdDate');
	 
	 $crud->set_field_upload('nearby_logo','applicationMediaFiles/markers');
	 
	 $crud->set_bulk_action_url(base_url('properties/bulk_action'));		
	 $crud->set_data_status_field_name('status');
	 
	 $crud->columns('nearby_name','nearby_logo','status');
	 
	 $crud->required_fields('nearby_name','nearby_logo','status');

     $output = $crud->render();

     $page_title = array('page_title' => 'Nearby');

	 $outputData = array_merge((array) $output , $page_title);

     $this->_example_output($outputData);

	
	}	
    
	
	function pricemodifier(){
	
	   $crud = new grocery_CRUD();
$crud->unset_jquery();

	 $crud->unset_print();

	 $crud->unset_export();

	 $crud->set_subject('Price Modifier');

     $crud->set_table('property_price_modifier');
	 
	 $crud->unset_fields('createdDate');
	 
	 
	 $crud->columns('name','status');
	 
	 $crud->required_fields('name','status');
	 
	 $crud->set_bulk_action_url(base_url('properties/bulk_action'));		
	 $crud->set_data_status_field_name('status');

     $output = $crud->render();

     $page_title = array('page_title' => 'Price Modifier');

	 $outputData = array_merge((array) $output , $page_title);

     $this->_example_output($outputData);

	
	}	
	
	 
	public function deleteImages()

	{

		$id = $this->input->post('id',TRUE);

		$table = 'property_image';

		$where = 'where property_image_id = '.$id;

		$data = $this->Property_model->select($table,$where);

		$image = $data[0]['image_name'];

		$path = config_item('site_url').'applicationMediaFiles/propertiesImage/';

		@unlink($path.$image);	

		$this->Property_model->data_delete($table,'property_image_id',$id);	  

		return TRUE;

	  }//end deleteImages function	  

	public function deleteNearby(){

		$id = $this->input->post('id',TRUE);

		$table = 'property_nearby_address';

		$this->Property_model->data_delete($table,'property_nearby_address_id',$id);	  

		return TRUE;

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
							$this->propertymail($item,$action,$table_name);

						endif;

					endforeach;

					echo count($id_array).' Items published!';

				break;

				case 'unpublish':

					foreach($id_array as $item):

						if($item != '' && $field_name !=''):

							$this->db->update($table_name, array($field_name => 'Inactive'), array($primary_key => $item));
							$this->propertymail($item,$action,$table_name);

						endif;

					endforeach;

					echo count($id_array).' Items unpublished!';

				break;	

			}

		}else{

		   echo 'Kindly Select Atleast One Item!';

		}

	}  

	public function property_import(){
	  
	  $data['page_title'] = 'Property Import XML';
	  $this->load->model('Common');
	  
	  include(config_item('root_url').'assets/xmlimport/xmltoarray.php');
	  
	  
	  
	  if(!empty($_FILES["property_xml"]['name'])){
	    $property_xml=$_FILES["property_xml"]['name'];
	    $temp_name = $_FILES['property_xml']['tmp_name'];

		$ext = @pathinfo($property_xml, PATHINFO_EXTENSION);
		
		if($ext!='xml'){
		   $this->messageci->set('Only XML File are allowed!', 'error');
		}else{
		   
		           $upload_directory = config_item('root_url').'applicationMediaFiles/propertyxml/';

					$property_xml   = time().rand(1000,99999).'.'.$ext;

					$file_path = $upload_directory.$property_xml; 

					@move_uploaded_file($temp_name, $file_path);
										
					$xmlstring  = file_get_contents($file_path);

					$array = XML2Array::createArray($xmlstring);
		
					$array=$array['otrigaproperty']['property'];
					 
					/* echo '<pre/>';					
                     print_r($array);
					die;*/
					if(!empty($array)){
					   foreach($array as $key){
					   
					  $chk= $this->validation($key);
					   if($chk){
					   
					     
					
					      $user_id = $this->session->userdata('user_id');
					      $propertycatagory=$key['propertycatagory'];
						  $property_name=$key['propertyname'];
						  $property_description=$key['propertydescription'];
						  $disclaimer=$key['disclaimer'];
						  $latitude=$key['latitude'];
						  $longitude=$key['longitude'];
						  $county=$key['county'];
						  $address=$key['address'];
						  $country=$key['country'];
						  $state=$key['state'];
						  $city=$key['city'];
						  $zipcode=$key['zipcode'];
						  $propertytype=$key['propertytype'];
						  $bedrooms=$key['bedrooms'];
						  $offertext=$key['offertext'];
						  $prices=$key['prices'];
						  $bathrooms=$key['bathrooms'];
						  $floors=$key['floors'];
						  $numrecepts=$key['numrecepts'];
						  $pricemodifier=$key['pricemodifier'];
						  $propertysqft=$key['propertysqft'];
						  $propertysqft=$key['propertysqft'];
						  $propertyavailability=$key['propertyavailability'];
						  $floorplan=$key['floorplan'];
						  $videourl=$key['videourl'];
						  $closingdate=$key['closingdate'];
						  
						  $data=selectData('property_category',"where categoryName='$propertycatagory'");
						  $type=selectData('property_types',"where typeName='$propertytype'");
						  $pricemodifier=selectData('property_price_modifier',"where name='$pricemodifier'");
						  
						  
						  $countryid=selectData('country',"where country='$country'");
						  $regionid=selectData('country_regions',"where region='$state'");
						  $cityId=selectData('country_region_cities',"where city='$city'");
						  
						  
						  
						  /*For Floor Plan*/
						  
						   if(!empty($floorplan)){
						  
						   $upload_directory = config_item('root_url').'applicationMediaFiles/property/florplan/';
						   
						   $ext = @pathinfo($floorplan, PATHINFO_EXTENSION);

					       $floor_plan_name   = time().rand(1000,99999).'.'.$ext;
						  
						  
                           $this->save_image($floorplan,$upload_directory.$floor_plan_name);
						  
						   }
						   
						   
						   /*For Property Vedio*/
						  
						   if(!empty($videourl)){
						  
						   $upload_directory = config_item('root_url').'applicationMediaFiles/propertiesVedio/';
						   
						   $ext = @pathinfo($videourl, PATHINFO_EXTENSION);

					       $videourl_name   = time().rand(1000,99999).'.'.$ext;
						  
						  
                           $this->save_image($videourl,$upload_directory.$videourl_name);
						  
						   }
						   
						   
						   /**For Offer**/
						   
						   
						   if(!empty($offertext)){
						     $offer='1';
							 $offertext=$offertext;
						   }else{
						     $offer='0';
							 $offertext='';
						   }
						   

						  $data=array(
						              'user_id'=>$user_id,
						              'property_category'=>$data[0]['property_category_id'],
						              'property_name'=>$property_name,
									  'property_description'=>$property_description,
									  'disclaimer'=>$disclaimer,
									  'latitude'=>$latitude,
									  'longitude'=>$longitude,
									  'county'=>$county,
									  'address'=>$address,
									  'country'=>$countryid[0]['countryid']?$countryid[0]['countryid']:'',
									  'state'=>$regionid[0]['regionid']?$regionid[0]['regionid']:'',
									  'city'=>$cityId[0]['cityId']?$cityId[0]['cityId']:'',
									  'zipcode'=>$zipcode,
									  'property_type'=>$type[0]['property_types_id'],
									  'bedrooms'=>$bedrooms,
									  'offer'=>$offer,
									  'offer_text'=>$offertext,
									  'prices'=>$prices,
									  'bathrooms'=>$bathrooms,
									  'num_floors'=>$floors,
									  'num_recepts'=>$numrecepts,
						              'price_modifier'=>$pricemodifier[0]['property_price_modifier_id']?$pricemodifier[0]['property_price_modifier_id']:'',
									  'property_sqft'=>$propertysqft,
									  'property_availability'=>$propertyavailability,
									  'video_url'=>$videourl_name?$videourl_name:'',
									  'floor_plan'=>$floor_plan_name?$floor_plan_name:'',
									  'property_closing_date'=>$closingdate
						  
						             );
						  
						 $success=$this->Common->data_insert('property',$data);
						  
						  
						  
						  
						  /*For Property Features*/
						  
						  $propertyfeatures=$key['propertyfeatureslist']['propertyfeatures'];  
						  
						  
						  if(!empty($propertyfeatures)){
						  
						  for($j=0; $j<count($propertyfeatures); $j++){
						 
						
							 $featurecatagory=$propertyfeatures[$j]["featurecatagory"];
							 $featurename=$propertyfeatures[$j]["featurename"];

							 $catagory=selectData('manage_property_features',"where features_name='$featurecatagory'");
							 
							 $subcatagory=selectData('manage_property_features',"where features_name='$featurename'");
							 
							 $catagory=$catagory[0]['manage_features_id'];
							 
							 $subcatagory=$subcatagory[0]['manage_features_id'];
							 
							 
							 $catdata=array('property_id'=>$success,'features_id'=>$catagory);
							 
							 $subcatdata=array('property_id'=>$success,'features_id'=>$subcatagory);
							 
							 $this->Common->data_insert('property_features',$catdata);
							 
							 $this->Common->data_insert('property_features',$subcatdata);
							
						 }
						  
						 }
						  
						  
						  
						  
						 /*For Nearby Address*/ 
						  
						 $nearby=$key['propertynearby']['nearby'];  
						 
						 if(!empty($nearby)){
						  
						 for($j=0; $j<count($nearby); $j++){
						 
							 $property_key=$nearby[$j]["propertykey"];
							 $property_val=$nearby[$j]["nearbyname"];
							 $nearby_address=$nearby[$j]["nearbyaddress"];
							 $nearbylatitude=$nearby[$j]["nearbylatitude"];
							 $nearbylongitude=$nearby[$j]["nearbylongitude"];
							
							
							$nearby_id=selectData('manage_nearby',"where nearby_name='$property_key'");
							
							
							$mydata=array('property_id'=>$success,'property_key'=>$nearby_id[0]['nearby_id'],'property_val'=>$property_val,'nearby_address'=>$nearby_address,'nearby_lat'=>$nearbylatitude,'nearby_lang'=>$nearbylongitude);
							$this->Common->data_insert('property_nearby_address',$mydata);
						 }
						 
						 }
						 
						 
						 /**For Property Image**/
						 
						 $propertyimage=$key['propertyimage']['images'];  
						 
						 
						 if(!empty($propertyimage)){
						  
						 for($j=0; $j<count($propertyimage); $j++){
						 
							 $image_path=$propertyimage[$j]["imagename"];
							 $image_title=$propertyimage[$j]["imagetitle"];
							 $image_caption=$propertyimage[$j]["imagecaption"];

							
						    $upload_directory = config_item('root_url').'applicationMediaFiles/propertiesImage/';
							
							$ext = @pathinfo($image_path, PATHINFO_EXTENSION);

					        $image_name   = time().rand(1000,99999).'.'.$ext;
						  
						  
                            $this->save_image($image_path,$upload_directory.$image_name);
							
							
							
							$mydata=array('property_id'=>$success,'image_name'=>$image_name,'image_title'=>$image_title,'image_caption'=>$image_caption);
							$this->Common->data_insert('property_image',$mydata);
							
							
							$filePath= $upload_directory.$image_name;					

						    $img = resize_images($filePath,271,221, $upload_directory.'thumb/'); 

						    $img = resize_images($filePath,450,300, $upload_directory.'350325/');

						    $img = resize_images($filePath,1300,400, $upload_directory.'1300400/');

						    $img = resize_images($filePath,800,600, $upload_directory.'800600/'); 	
							
							
						 }
						 
						 }
						 
						 /**For Auction Property**/
						 
						 $addauction=$key['addauction'];  
						 
						 
						  if(!empty($addauction)){
			                $start_data=$addauction["auctiondate"]; 
				            $auctionDate=explode('-',$start_data);
				            $bid_price=$addauction["auctionminprice"]; 
							
				$auction=array('property_id'=>$success,'start_date'=>strtotime($auctionDate[0]),'end_date'=>strtotime($auctionDate[1]),'auction_min_price'=>$bid_price);

			               $this->Common->data_insert('property_auction',$auction);	
			              }
						  
						  
						  
					   }
					   
					   }
					   
					   if(!empty($success)){
					     $data['page_title'] = 'Property Import XML';
						 $this->messageci->set('Property Import successfully!', 'success');
					   }					   
					}
					
		           
				  
		   
		}
		
	  }
	  $this->load->view('property/property_import',$data);	
	}
		
	function validation($key){
	
	
	  $propertycatagory=$key['propertycatagory'];
	  $property_name=$key['propertyname'];
	  $property_description=$key['propertydescription'];
	  $disclaimer=$key['disclaimer'];
	  $latitude=$key['latitude'];
	  $longitude=$key['longitude'];
	  $county=$key['county'];
	  $address=$key['address'];
	  $country=$key['country'];
	  $state=$key['state'];
	  $city=$key['city'];
	  $zipcode=$key['zipcode'];
	  $propertytype=$key['propertytype'];
	  $bedrooms=$key['bedrooms'];
	  $prices=$key['prices'];
	  $bathrooms=$key['bathrooms'];
	  $floors=$key['floors'];
	  $numrecepts=$key['numrecepts'];
	  $pricemodifier=$key['pricemodifier'];
	  $propertysqft=$key['propertysqft'];
	  $propertyavailability=$key['propertyavailability'];
	  $floorplan=$key['floorplan'];
	  $closingdate=$key['closingdate'];
	  
	  if(!empty($propertycatagory)&&($property_name)&&($property_description)&&($disclaimer)&&($latitude)&&($longitude)&&($county)&&($address)&&($country)&&($state)&&($city)&&($zipcode)&&($propertytype)&&($bedrooms)&&($prices)&&($bathrooms)&&($floors)&&($numrecepts)&&($pricemodifier)&&($propertysqft)&&($propertyavailability)&&($floorplan)&&($closingdate)){
	    return true;
	  }else{
	    return false;
	  }					  
	
	}	
	
	public function forcedownload(){
	
	$this->load->helper('download');
	echo $upload_directory = config_item('root_url').'assets/xmlimport/property.xml';


	
	 $data = file_get_contents($upload_directory); // Read the file's contents
    $name = 'sample.xml';

force_download($name, $data);
	
	}
	
	function save_image($inPath,$outPath){ 
	 //Download images from remote server

    $in=    fopen($inPath, "rb");

    $out=   fopen($outPath, "wb");

    while ($chunk = fread($in,8192)){

      fwrite($out, $chunk, 8192);

    }
    fclose($in);
    fclose($out);
   }
   
   public function viewStats($propertyID = NULL){

		

		$data['results'] = 'Property Data';

		if(!empty($propertyID))

		$data['getResult'] = getPropertyData($propertyID);
		
		$data['page_title']='Property overview stats';

		$this->load->view('property/propertyOverViewStats', $data);

	}
	
	public function propertymail($id_array,$action,$type){
	
	  $this->load->model('Common');	
	
	  
	   if($action=='unpublish'){
	   $status='Inactive';
	  }elseif($action=='publish'){
	   $status='Active';
	  }elseif($action=='delete'){
	   $status='Delete';
	  }else{
	   $status=$action;
	  }
	  
	  
	      if(!empty($id_array)&&($type=='property')){
		  
	      $property=  $this->Common->property_info_get($id_array);
		  
		 
		  $bedrooms=$property[0]['bedrooms'];
		  $property_type_name=$property[0]['typeName'];
		  $category_name=$property[0]['categoryName'];
		  

		  $seo_url_string = ucwords($bedrooms.'  Bed '. $property_type_name.'  For '. $category_name);
		  $property_seo_url = seo_friendly_urls($seo_url_string,'',$id_array);
		  
		  $data['property_seo_url']=$property_seo_url;
		  
		  $data['admin_fullname'] = $this->session->userdata('firstName').' ' .$this->session->userdata('lastName');
          $data['admin_firstName']=$this->session->userdata('firstName');
	      $data['status']=$status;    
	      $data['admin_profile_image']=$this->session->userdata('profile_image');  
		  
		  $adusername=ucwords($this->session->userdata('firstName').' '.$this->session->userdata('lastName'));
          $seosendername = str_replace('&nbsp;', '-', $adusername);
          $data['senderseo'] = seo_friendly_urls($seosendername,'',$this->session->userdata('user_id'));
		  
		  
		  $username=ucwords($property[0]['firstName'].' '.$property[0]['lastName']);
          $seousername = str_replace('&nbsp;', '-', $property[0]['firstName']);
          $data['recieverseo'] = seo_friendly_urls($seousername,'',$property[0]['user_id']);
		  
		  
		            $data['fullname'] = $username;
					$data['firstname'] = $property[0]['firstName'];
					$data['email'] = $property[0]['email'];
					$data['profile_image'] = $property[0]['profile_image'];
					
					$data['status'] = $status;
					
					
					
					
					$message = $this->load->view('message/template/property_status_message', $data, TRUE); 
					$toEmail = $property[0]['email'];
					$fromEmail = array('email' => config_item('no_reply'),'name' => config_item('site_name'));
					$subject = ucwords(config_item('site_name').' - Your Property Status '.$status.'');
					$attachment = array();
					$result = sendUserEmailCI($toEmail, $fromEmail, $subject, $message, $attachment);

	  }			
	  
	}
	
	public function editimagetitle(){

		$pkid = @$_REQUEST['id'];

		$imagetitle = @$_REQUEST['image_title'];

		if (empty($pkid)) die('error');

		$where = array('property_image_id' => $pkid);

		$data = array('image_title' => $imagetitle);

	    $this->Property_model->data_update('property_image',$data,$where);

		die('success');

	}
	
	public function editimagecaption(){
		
		$pkid = @$_REQUEST['id'];

		$caption = @$_REQUEST['caption'];

		if (empty($pkid)) die('error');

		$where = array('property_image_id' => $pkid);

		$data = array('image_caption' => $caption);

	    $this->Property_model->data_update('property_image',$data,$where);

		die('success');
		
	}

}
