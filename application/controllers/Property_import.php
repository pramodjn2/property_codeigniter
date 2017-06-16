<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Property_import extends CI_Controller { 
	
	var $userID;
	var $message;
	public function __construct() {
		
		parent::__construct();
		$this->load->helper(array('common_admin'));
		$this->load->library(array('file_upload','fileupload','grocery_CRUD', 'ajax_grocery_crud'));			 		
		//check user login and get user_id from session.
		$this->userID = checkUserLogin();
		checkUserAccessPermission();
	}	
	
	public function index(){
	    $data['page_title'] = 'Property Import XML';
		$this->load->view('my_account/property_import',$data);		
	}
	
	public function importxml(){
	  
	  $data['page_title'] = 'Property Import XML';
	  $this->load->model('Common');
	  
	  include(config_item('site_url').'assets/xmlimport/xmltoarray.php');
	  
	  $property_xml=$_FILES["property_xml"]['name'];
	  
	  if(!empty($property_xml)){
	  
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
						  
						  $data=select('property_category',"where categoryName='$propertycatagory'");
						  $type=select('property_types',"where typeName='$propertytype'");
						  $pricemodifier=select('property_price_modifier',"where name='$pricemodifier'");
						  
						  
						  $countryid=select('country',"where country='$country'");
						  $regionid=select('country_regions',"where region='$state'");
						  $cityId=select('country_region_cities',"where city='$city'");
						  
						  
						  
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

							 $catagory=select('manage_property_features',"where features_name='$featurecatagory'");
							 
							 $subcatagory=select('manage_property_features',"where features_name='$featurename'");
							 
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
							
							
							$nearby_id=select('manage_nearby',"where nearby_name='$property_key'");
							
							
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
	  $this->load->view('my_account/property_import',$data);	
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
}
