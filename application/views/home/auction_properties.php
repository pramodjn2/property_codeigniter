<?php if(!empty($auction_results)){ ?>
<section class="wrapper wrapper-grey padding50 subscribe_shadow latest_propwrap">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h2 class="center prophome_heading padding_bottom">See how fast you can sell your property<br></h2>
        <p>&nbsp;</p>
        <div class="animate-group" data-animation-interval="100">
          <?php
		    foreach($auction_results as $auction){
				
				
				
			$urls = config_item('base_url').'applicationMediaFiles/companyImage/9999';
			$com_img  = getUserProfileImage($auction['agency_logo'],$urls);
			
			$prurls = PROPERTY_IMAGE_350325;
			$propertyImg = getUserProfileImage($auction['image_name'],$prurls);
			
			$user_img_url = USER_IMAGE_150150;
			$user_img  = getUserProfileImage($auction['user_image'],$user_img_url);
			
			if(config_item('URL_ENCODE')){
			$property_id = safe_b64encode($auction['property_id']);	
			}else{
			$property_id = $auction['property_id'];	
			}
				
				
		  if(config_item('URL_ENCODE')){
			$agency_id = safe_b64encode($auction['agency_id']);	
			}else{
			$agency_id = $auction['agency_id'];	
			}	
			
			 if(config_item('URL_ENCODE')){
			$user_id = safe_b64encode($auction['user_id']);	
			}else{
			$user_id = $auction['user_id'];	
			}	
			
			
			   $bedrooms = $auction["bedrooms"];
			   $property_type_name = $auction['property_types'];
			   $category_name = $auction['property_category_name'];
			   $seo_url_string = ucwords($bedrooms.'  Bed '. $property_type_name.'  For '. $category_name);
			   $property_seo_url = seo_friendly_urls($seo_url_string,'',$auction['property_id']);
			   	
				
				
			
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
              <div class="wishlist-icon" onClick="property_save('<?php echo $property_id;?>','1')">
              <i id="favourites_<?=$property_id;?>" class="fa fa-heart<?php echo $favourites_class;?>" title="Add To Favorite"></i></div>
              <div class="grid-image"> 
              
              <a href="<?=base_url('professional/details/'.$user_id);?>">
               <?php /*?><div class="agency-circle-image">
                <img class="" src="<?php echo USER_IMAGE_150150.$user_img;?>">
                </div><?php */?>
                </a> 
                
                <a href="javascript:void(0);" onclick="redirect_url('<?=base_url('property/auction/'.$property_seo_url);?>');" >
                <img src="<?php echo $prurls.$propertyImg;?>" class="img-responsive">
                </a>
                <span class="overlay_info">
                
                <a href="javascript:void(0);" onclick="redirect_url('<?=base_url('property/auction/'.$property_seo_url);?>');">
                <div class="overlay_price"><?php echo attachCurrencySymbol(convert_currency($auction['property_price']));?></div>
                </a></span></div>
              <div class="grid-content-detail auction-detail"><a href="javascript:void(0);" onclick="redirect_url('<?=base_url('property/auction/'.$property_seo_url);?>');" >
                <?php 
					$propertyaddress = str_replace('\\r', '', $auction['property_address']);
					$propertyaddress = str_replace('\\n', '', $propertyaddress);
					$propertyaddress = str_replace('\\', '', $propertyaddress);
				?>
                 
                 <div><?php echo $auction["bedrooms"];?> Bed <?php echo $auction['property_types'];?> For <?php echo $auction['property_category_name']?></div>
                 
                <div><?php echo $propertyaddress; ?></div>
               
                
                
                </a></div>
              <!--<div class="reverse-counter" data-time-end="2015/09/04 11:59:40"></div>-->
              <div class="reverse-counter" date-time-end="<?php echo date('Y/m/d H:i:s', $auction['auction_end']); ?>"></div>
              <div class="bid-section">
                <?php  
				$bidtext = '';
				if (empty($auction['auction_highestbid']) || $auction['auction_highestbid'] <= 0){
					$bidtext = 'N/A';
				} else {
					$bidtext = attachCurrencySymbol(convert_currency($auction['auction_highestbid']));
				}
				?>
                <div class="current-bid">CBD : <?php echo $bidtext; ?></div>
                <a href="javascript:void(0);" onclick="redirect_url('<?=base_url('property/auction/'.$property_seo_url);?>');" class="auction-bid-now pull-right btn btn-red btn-sm">Bid Now</a>
              </div>
              <ul class="mini-stats col-sm-12">
                <li class="col-sm-2 col-xs-2">
                  <div class="values"><i class="fa fa-camera"></i>&nbsp;<?php echo $auction["property_image_count"];?></div>
                </li>
                <li class="col-sm-3 col-xs-3">
                  <div class="values"><i class="fa fa-hotel"></i>&nbsp;<?php echo $auction["bedrooms"];?></div>
                </li>
                <li class="col-sm-3 col-xs-3">
                
                 <div class="values"><img class="baths" src="<?=base_url('assets/images/bath_icom.png')?>">&nbsp;<?php echo $auction["bathrooms"];?></div>
                 
                 
                </li>
                <li class="col-sm-4 col-xs-4">
                  <div class="values"><i class="fa fa-heart-o"></i>&nbsp;<?php echo $auction["property_favorites_count"];?> Favorite</div>
                </li>
                <div class="clear"></div>
              </ul>
              <div class="clear"></div>
            </div>
          </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</section>
<style>
.grid-content-address{
	height:30px;
}

.grid-content-detail.auction-detail {
    height: 85px;
}
</style>
<?php } ?>