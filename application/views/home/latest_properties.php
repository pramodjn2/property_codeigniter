<?php if(!empty($featured_results)){



 ?>

<section class="wrapper wrapper-grey padding50 subscribe_shadow latest_propwrap">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h2 class="center prophome_heading padding_bottom"> Latest Properties<br>
          <small>Anytime, Find your way home.</small> </h2>
       
        <div class="padding50 animate-group" data-animation-interval="100">
          <?php
		    foreach($featured_results as $featured){
			 
			 $slug = seo_friendly_urls($featured['firstName'],$featured['lastName'],$featured['user_id']);
				
			$urls = config_item('base_url').'applicationMediaFiles/companyImage/9999';
			$com_img  = getUserProfileImage($featured['agency_logo'],$urls);
			
			$prurls = PROPERTY_IMAGE_350325;
			$propertyImg = getUserProfileImage($featured['image_name'],$prurls);
			
			$user_img_url = USER_IMAGE_150150;
			$user_img  = getUserProfileImage($featured['user_image'],$user_img_url);
			
			
			$property_id = $featured['property_id'];	
			$agency_id = $featured['agency_id'];	
			$user_id = $featured['user_id'];
			
			
			   $bedrooms = $featured["bedrooms"];
			   $property_type_name = $featured['property_types'];
			   $category_name = $featured['property_category_name'];
			   $seo_url_string = ucwords($bedrooms.'  Bed '. $property_type_name.'  For '. $category_name);
			   $property_seo_url = seo_friendly_urls($seo_url_string,'',$featured['property_id']);
			   	
				
			
			
			$session_user_id = $this->session->userdata('user_id');
			$favourites_class='-o';
			if(!empty($session_user_id)){
			$favourites=checkPropertyFavourites($session_user_id,$property_id); 
		      if($favourites == '1'){
				$favourites_class='';
			  }
		  }
			
		  ?>
          <div class="col-sm-3" style="padding-left:0px;">
            <div class="grid-item animate">
              <div class="wishlist-icon" onClick="property_save('<?php echo $property_id;?>')"> <i id="favourites_<?=$property_id;?>" class="fa fa-heart<?php echo $favourites_class;?>" title="Add To Favorite"></i></div>
              <div class="grid-image"> <a href="<?=base_url('professional/details/'.$slug);?>">
                <div class="agency-circle-image circle-img"> <img class="circle-img" src="<?php echo USER_IMAGE_150150.$user_img;?>"> </div>
                </a> <a href="<?=base_url('property/details/'.$property_seo_url);?>"> <img src="<?php echo $prurls.$propertyImg;?>" class="img-responsive"> </a> <span class="overlay_info"> <a href="<?=base_url('property/details/'.$property_seo_url);?>">
                <div class="overlay_price"><?php echo attachCurrencySymbol(convert_currency($featured['property_price']));?></div>
                </a></span></div>
              <div class="grid-content-detail"><a href="<?=base_url('property/details/'.$property_seo_url);?>">
                <?php 
					$propertyaddress = str_replace('\\r', '', $featured['property_address']);
					$propertyaddress = str_replace('\\n', '', $propertyaddress);
					$propertyaddress = str_replace('\\', '', $propertyaddress);
				?>
                <div><?php echo $featured["bedrooms"];?> Bed <?php echo $featured['property_types'];?> For <?php echo $featured['property_category_name']?></div>
                <div><?php echo $propertyaddress; ?></div>
                </a></div>
              <ul class="mini-stats col-sm-12">
                <li class="col-sm-2 col-xs-2">
                  <div class="values"><i class="fa fa-camera"></i>&nbsp;<?php echo $featured["property_image_count"];?></div>
                </li>
                <li class="col-sm-3 col-xs-3">
                  <div class="values"><i class="fa fa-hotel"></i>&nbsp;<?php echo $featured["bedrooms"];?></div>
                </li>
                <li class="col-sm-3 col-xs-3">
                  <div class="values"><img class="baths" src="<?=base_url('assets/images/bath_icom.png')?>">&nbsp;<?php echo $featured["bathrooms"];?></div>
                </li>
                <li class="col-sm-4 col-xs-4">
                  <div class="values"><i class="fa fa-heart-o"></i>&nbsp;<?php echo $featured["property_favorites_count"];?> Favorite</div>
                </li>
                <div class="clear"></div>
              </ul>
              <div class="clear"></div>
            </div>
          </div>
          <?php }?>
        </div>
      </div>
    </div>
  </div>
</section>
<style>
  .grid-content-address{
	  height:30px;}
  </style>
<?php } ?>
