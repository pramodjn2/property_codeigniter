<?php 
$this->load->view('common/header');
$this->load->view('common/top_header');
$urls = config_item('base_url').'applicationMediaFiles/companyImage/9934';
//echo '<pre />'; print_r($this->session->all_userdata());																
$userName  = $this->session->userdata('fullName');
$userEmail = $this->session->userdata('email');
$userID    = $this->session->userdata('user_id');
$userInfo = getUserInformation($userID);
if(!empty($userInfo))
{
 $userCell = !empty($userInfo[0]['phone_number'])?$userInfo[0]['phone_number']:'';
 $userCountry = !empty($userInfo[0]['country'])?$userInfo[0]['country']:'';
 $userRegion = !empty($userInfo[0]['region'])?$userInfo[0]['region']:'';
 $userCity = !empty($userInfo[0]['city'])?$userInfo[0]['city']:'';
 $user_pcode = !empty($userInfo[0]['postal_code'])?$userInfo[0]['postal_code']:'';
}else{$userCountry = 253;$user_pcode='';$userCell='';}
?>
<div class="main-container inner_maincontainer nomargintop">
<!--- start: FIND AGENT---->
<section class="wrapper wrapper-grey padding50 freevaluation">
    <div class="container_inn">
        <div class="row">
            <h3 style="text-transform:none">Free valuation</h3>
            <h2>Enter your details to arrange a property valuation</h2>
            <div class="container">
            	<div class="col-sm-8 valuationform_holder">
					              
					<?php echo validation_errors();?>
                    <form action="<?php echo base_url('freevaluation/index/'.safe_b64encode($user_detail[0]['property_id']));?>" method="post" id="freevaluation">
                        
						<?php 
							 //$userEmail
							$set_userName = set_value('uname');
							if(!empty($set_userName)){
							 	$set_userName = $set_userName;
							}elseif(!empty($userName)){
								$set_userName = $userName;
							}else{$set_userName = '';}
							
							
							$set_userEmail = set_value('email');
							if(!empty($set_userEmail)){
								 $set_userEmail = $set_userEmail;
							}elseif(!empty($userEmail)){
								$set_userEmail = $userEmail;
							}else{$set_userEmail = '';}
							
						
						$set_userCell = set_value('cell_number');
						$set_userCell = !empty($set_userCell)?$set_userCell:$userCell;	
						$set_user_pcode = set_value('postal_code');
						$set_user_pcode = !empty($set_user_pcode)?$set_user_pcode:$user_pcode;
						
						?>
						<div class="panel-body">
                            <div class="form-group">
                                <label for="form-field-22">Name<span class="symbol required"></span></label> 
                                <input class="form-control" maxlength="40" type="text" name="uname" value="<?=$set_userName;?>">
                            </div>
                            <div class="form-group">
                                <label for="form-field-22">Email<span class="symbol required"></span></label>
                                <input class="form-control"  maxlength="50" type="text" name="email" value="<?=$set_userEmail;?>">
                            </div>
							
							
                            <div class="form-group">
                                <div class="col-sm-6 no-padding">
                                    <label for="form-field-22">Mobile</label>
									<span class="symbol required"></span>
                                    <input class="form-control" maxlength="15" type="text"  name="cell_number" value="<?=$set_userCell;?>">
                                </div>
                                <div class="col-sm-6">
                                    
									<label for="form-field-22">Telephone</label>
                                    <input class="form-control" type="text" maxlength="15"  name="phone_number" value="<?=set_value('phone_number');?>">
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="form-group">
                                <label for="form-field-22">Address of property to be valued</label>
                                <input class="form-control" maxlength="200" type="text"  name="address" value="<?=set_value('address');?>">
                            </div>
                            <div class="form-group">
                                <div class="col-sm-3 no-padding">
                                    <label for="form-field-22">Country<span class="symbol required"></span></label>
                                    <select name="country" id="country" onchange="getStates(this.value)" class="form-control search-select">
                                    <?php 
                                    $countryData=selectData('country',"where status='Active'");
                                    foreach($countryData as $conData){
                                        $sel = '';
										
										if(!empty($_POST['country'])){
										$chkval=set_value('country');
										}else{
										//$chkval='253';
										$chkval= !empty($userCountry)?$userCountry:253;
										}
										
										
                                        if($chkval == $conData["countryid"])
                                        $sel = 'selected="selected"';
                                    ?>
                                    <option <?=$sel?> value="<?=$conData["countryid"];?>"><?=$conData["country"];?></option>
                                    <?php }?>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label for="form-field-22">State<span class="symbol required"></span></label>
                                    <select name="state" id="state" class="form-control search-select" onchange="getCities(this.value)">
									
									<?=getStatesfront(set_value('country'),set_value('state'));?>
									</select>
                                </div>
                                <div class="col-sm-3 no-padding">
                                    <label for="form-field-22">City<span class="symbol required"></span></label>
                                    <select name="city" class="form-control search-select" id="city">
									<?=getCitiesfront(set_value('state'),set_value('city'));?>
									</select>
                                </div>
                                <div class="col-sm-3">
                                    <label for="form-field-22">Postcode<span class="symbol required"></span></label>
                                    <input class="form-control"  maxlength="8" type="text" name="postal_code" value="<?=$set_user_pcode;?>">
                                </div>
                                <div class="clear"></div>	
                            </div>
                                
                            <div class="form-group">
                                <label for="form-field-22">Message<span class="symbol required"></span></label>
                               
								 <textarea class="form-control limited" id="form-field-23" minlenght="40" maxlength="500" placeholder="message.." name="default_text"><?=set_value('default_text');?></textarea>
                            </div>
                            <label class="checkbox-inline col-sm-5">
                                 <div class="">
                                     <input checked="checked" type="checkbox" class="grey" value="true" name="email_chk">
                                     <ins class="iCheck-helper" ></ins>
                                 </div>
                                 Send a copy to my email.
                            </label>
                            <div class="clear"></div>
                            <a href="<?php echo base_url('property/details/'.$decode_id);?>" class="btn btn-light-grey"><i class="fa fa-arrow-circle-left"></i>CANCEL</a>
                            <button class="btn btn-primary red_button" type="submit">SEND <i class="fa fa-arrow-circle-right"></i></button>
                            
                            <div class="clear"></div>
                            <p class="note">By submitting this form, you confirm that you agree to our website terms of use, our privacy policy and consent to cookies being stored on your computer.</p>
                        </div>
                    </form>                        
				</div>
 <?php 

  $userInfo = getUserInformation($user_detail[0]["user_id"]);
  $reviews=getUserReviews($user_detail[0]["user_id"]);
 
  $user_img_url = USER_IMAGE_150150;
  $user_img  = getUserProfileImage($userInfo[0]['profile_image'],$user_img_url);

  $username=ucwords($userInfo[0]['firstName'].' '.$userInfo[0]['lastName']);
  $seousername = str_replace('&nbsp;', '-', $username);
 
  $userSeoFriendlyUrl = seo_friendly_urls($seousername,'',$user_detail[0]['user_id']); 
  
  
  $companyname = str_replace('&nbsp;', '-', $user_detail[0]["username"]);
  $companySeoFriendlyUrl = seo_friendly_urls($companyname,'',$user_detail[0]['user_id']);
  
  
  ?>
  


  
  
  
                <div class="col-sm-4">
				
                    <div class="promobox promobox_white">   
					<div class="col-sm-4 col-xs-5">                     
                        <a href="<?php echo base_url('professional/details/'.$userSeoFriendlyUrl); ?>" class="center" title="<?php echo $username;?>">
                            <img src="<?php echo USER_IMAGE_150150.$user_img;?>" class="circle-img">
                        </a>
				    </div>	
					<div class="col-sm-8">    	
                        <a href="<?php echo base_url('professional/details/'.$userSeoFriendlyUrl); ?>" class="center" title="<?php echo $username;?>"><?php echo $username;?></a><div class="detail_review">
                <div class="defaultReview"  data-average="<?php echo $reviews['rating_total']?$reviews['rating_total']:'0';?>" data-id="4"></div>
                <div class="count_review c_review"><span class="label label-success"><?php echo $reviews['rating_out_of']; ?></span></div>
                <div class="clear"></div>
              </div>
			  </div>	
						<p><b>About:</b> <?php echo dataLimit($userInfo[0]['about_us'],150,'professional/details/'.$userSeoFriendlyUrl);?></p>
						
						
						
						
						 <a href="<?php echo base_url('professional/details/'.$companySeoFriendlyUrl); ?>" class="center" title="<?php echo $user_detail[0]["username"];?>">
                            <img src="<?php echo $user_detail[0]['image_name'];?>">
                        </a>
						<p><b>Marketed by:</b> 
                        <a href="<?php echo base_url('professional/details/'.$companySeoFriendlyUrl); ?>" class="center" title="<?php echo $user_detail[0]["username"];?>"><?php echo $user_detail[0]["username"];?></a></p>
						<?php if(!empty($user_detail[0]["address"])){?><p><b>Address:</b> <?php echo $user_detail[0]["address"]; ?></p><?php }?>
                        <p>
                            <span class="prop_soldbyus">
                            <span class="col-sm-7"><b>For sale:</b></span> 
                            <span class="label label-info"><?php echo countProperty_by_uid($user_detail[0]['user_id'],1);?></span></span> 
                        </p>
						<p>
                            <span class="prop_soldbyus"> <span class="col-sm-7"><b>To rent:</b></span> 
                            <span class="label label-danger"><?php echo countProperty_by_uid($user_detail[0]['user_id'],2);?></span></span> 
                        </p>
						<p>
                            <span class="prop_soldbyus"><span class="col-sm-7"><b>Total:</b></span> 
                            <span class="label label-warning"><?php echo countProperty_by_uid($user_detail[0]['user_id'],3);?></span></span>
                        </p>
						
						
					 
                       <div class="clear"></div>
                    </div>
                    <!--
                    <a  href="<?php //echo base_url('property/forsale');?>" class="btn btn-blue btn-lg video-free_btn"> ALL SALE PROPERTY </a>
                    <a  href="<?php //echo base_url('property/forrent');?>" class="btn btn-green btn-lg video-free_btn"> ALL RENTAL PROPERTY </a>
                    -->
                    <?php echo displayAdvertise('4', '3');?>
                </div>
            </div>                
        </div>
    </div>
</section>
</div>
<?php $this->load->view('agent/subscribe_agent'); ?>
<?php $this->load->view('common/footer_content');?>
<?php $this->load->view('common/footer'); ?>
<script>
$(document).ready(function(){
   ValuationFormValidator.init();
   
   var countryID = '<?=$userCountry;?>';
   var regionID = '<?=$userRegion;?>';
   var cityID = '<?=$userCity;?>';
   if(countryID != "" && regionID != ""){
   	 	getStates(countryID,regionID);
		if(cityID != ""){
			getCities(regionID,cityID);
		}
   }else if(countryID != ""){getStates(countryID);}	
    
});	
$( window ).load(function() {
 //$("#country").trigger("change");
});
</script>
<link rel="stylesheet" href="<?php echo config_item('base_url');?>assets/js/jRating/jRating.jquery.css" type="text/css" />
 <script type="text/javascript" src="<?php echo config_item('base_url');?>assets/js/jRating/jRating.jquery.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	   $('.defaultReview').jRating({
			isDisabled:true,
			rateMax : 5,
		});
	});
</script>	
<script src="<?=base_url('assets/js/custom/freevaluation_validation.js');?>"></script>
<script src="<?=base_url('assets/plugins/jquery-inputlimiter/jquery.inputlimiter.1.3.1.min.js');?>"></script>

<?php $this->load->view('common/footer_end');?>