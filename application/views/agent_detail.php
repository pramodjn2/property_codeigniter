<?php $this->load->view('common/header'); ?>
<?php $this->load->view('common/top_header'); 

//echo '<pre/>';
//print_r($agent_detail);die;
?>

<!-- start: MAIN CONTAINER -->

<div class="main-container inner_maincontainer" style=" margin-top: 111px !important;">

  


<!--- start: PROPERTY DETAIL SLIDERBANNER---->
        <section class="wrapper wrapper-grey padding50 propdetail_Wrap">
    <div class="container_inn">
      
      <div class="row prop_whitebg">
      		<div class="container">
              <!--- start: AGENT DETAIL Section one---->
              
              <div class="col-sm-12 agent_ofthis_prop">
              		<div class="col-sm-8">
						
						<div class="clear"></div>
						<div class="col-sm-2 col-xs-5">
						
						<?php
						 $urls = config_item('base_url').'applicationMediaFiles/usersImage/150150';
						 $agent_img  = getUserProfileImage($agent_detail[0]["profile_image"],$urls);
						 
						 
						 if(config_item('URL_ENCODE')){
			                  $agent_id = safe_b64encode($agent_detail[0]["user_id"]);	
							}else{
								$agent_id = $agent_detail[0]["user_id"];	
							}
		                 $reviews=getUserReviews($agent_id);
						?>
							<img class="circle-img" alt="" src="<?php echo $urls.$agent_img;?>"> 
						</div>
						<div class="col-sm-10">
						<h3><?php echo ucfirst($agent_detail[0]["firstName"]);?></h3>
						<span class=""> 
							<div  class="defaultReview" data-average="<?php echo $reviews['rating_total'];?>" data-id="4"></div> <?php echo $reviews['review_total'];?>-Reviews  
						</span>
						<p>
							Hello,<br/>
<br/>
<?php if(!empty($agent_detail[0]["about_us"])){echo $agent_detail[0]["about_us"]; }?>
						</p>
						<span class="prop_soldbyus"><span class="bluetext">Property sold By Us:</span><strong> <?php echo countProperty_by_uid($agent_detail[0]["user_id"]);?> Properties</strong></span>
                        <span class="prop_soldbyus"><span class="redtext">Property Rent By Us:</span><strong> <?php echo countProperty_by_uid($agent_detail[0]["user_id"]);?> Properties</strong></span>
                        <span class="prop_soldbyus"><span class="yellowtext">Properties to Sold:</span><strong> <?php echo countProperty_by_uid($agent_detail[0]["user_id"]);?> Properties</strong></span>
						<button class="btn btn-default btn-green" onclick="getfocus();"><i class="fa fa-envelope-o"></i> Contact</button>
						<a href="<?php echo config_item('base_url');?>professional/write_review/<?php echo $agent_id;?>"><button class="btn btn-default btn-info"><i class="fa fa-pencil"></i>WRITE A REVIEW</button></a>
						<button class="btn btn-default btn-yellow"><i class="fa fa-plus-square-o"></i> SHARE</button>
						
						</div>
					
                    <div class="clear dotted_line"></div>
						<div class="col-sm-2">
							CONTACT 
						</div>
						<div class="col-sm-10">
							<strong><a href="#">Call <?php if(!empty($agent_detail[0]["phone_number"])){ echo $agent_detail[0]["phone_number"];} else{ echo '**********';}?></a></strong>
						</div>
						<div class="clear dotted_line"></div>
						<div class="col-sm-2">
							SOCIAL 
						</div>
						<div class="col-sm-10">
						<!-- start:  SOCIAL CONTACT AGENT ICONS -->
						  <div class="social-icons">
							<ul>
							  <li class="social-twitter tooltips" data-original-title="Twitter" data-placement="bottom"> <a target="_blank" href="http://www.twitter.com/"> Twitter </a> </li>
							  <li class="social-facebook tooltips" data-original-title="Facebook" data-placement="bottom"> <a target="_blank" href="http://facebook.com/"> Facebook </a> </li>
							  <li class="social-google tooltips" data-original-title="Google" data-placement="bottom"> <a target="_blank" href="http://google.com/"> Google+ </a> </li>
							  <li class="social-linkedin tooltips" data-original-title="LinkedIn" data-placement="bottom"> <a target="_blank" href="http://linkedin.com/"> LinkedIn </a> </li>
							</ul>
						  </div>
						  <!-- end: SOCIAL CONTACT AGENT ICONS --> 
						</div>
						<div class="clear"></div>
						
						<div class="dotted_line"></div>
						
						<!-- start:  LISTING AND SALES MAP SECTION -->
						<?php $this->load->view('professional/listing_sales');?>
						<!-- end:  LISTING AND SALES MAP SECTION -->
						
						<!-- start:  ACTIVE LISTING  SECTION -->
						 <?php $this->load->view('professional/active_property');?>
						<!-- end:  ACTIVE LISTINGSECTION -->
						
						<!-- start:  PAST SALE   SECTION -->
						  <?php //$this->load->view('agent/past_sales');?>
						<!-- end:  PAST SALE SECTION -->
						
						
						<!-- start:  REVIEW SECTION -->
                           <?php $this->load->view('professional/rating_review');?>
						<!-- end:  REVIEW SECTION -->
						
					</div> 
             
                     <?php $this->load->view('professional/agent_detail_right_sidebar'); ?>
                    <div class="clear"></div>
              </div>
            <!--- end: PROPERTY DETAIL Section one---->
            </div>
      </div>
      
      
      
    </div>
  </section>	
<!--- end: AGENT DETAIL SLIDERBANNER----> 

 <!--- start: SUBSCRIPTION AREA---->
  <?php $this->load->view('professional/subscribe_agent'); ?>
  <!--- end: SUBSCRIPTION AREA ----> 
  
</div>
<!-- end: MAIN CONTAINER -->

 <?php $this->load->view('common/footer_content'); ?>
 
 <?php $this->load->view('common/footer'); ?>


<link rel="stylesheet" href="<?php echo config_item('base_url');?>assets/js/jRating/jRating.jquery.css" type="text/css" />
 
 <script type="text/javascript" src="<?php echo config_item('base_url');?>assets/js/jRating/jRating.jquery.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
	   $('.local-knowledge').jRating({
			isDisabled:true,
			rateMax : 5,
		});
		$('.progress-expertise').jRating({
			isDisabled:true,
			rateMax : 5,
		});
		$('.responsiveness').jRating({
			isDisabled:true,
			rateMax : 5,
		});
		$('.negotiation-skills').jRating({
			isDisabled:true,
			rateMax : 5,
		});
		$('.defaultReview').jRating({
			isDisabled:true,
			rateMax : 5,
		});
	});
	
	
	function likeunlike(comment_id){
	 var url = base_url+'ajax/likeunlike';  
	 	$.ajax({
			type: "POST",
			url: url,
			data: {'comment_id': comment_id},
		})
		.done(function(result) {
		    var result_data = JSON.parse(result);
		    var result_class = result_data.class;
            var result_message = result_data.message;
			var total_likes = result_data.totalLikes;
            var data = '<div class="'+result_class+'">'+result_message+'</div>';
			
			//$('#msg_success').html(data);
			$('#total_likes').html(total_likes);

			if( parseInt(result_data.success) == 1 ) {
				if( result_data.action == 'LIKE_SUBMITTED' ) {
					var newLabelText = '<i class="fa fa-thumbs-down"></i> Unlike';
					$('#comment_like_unlike_link').html(newLabelText);
				} else if( result_data.action == 'LIKE_REMOVED' ) {
					var newLabelText = '<i class="fa fa-thumbs-up"></i> Like';
					$('#comment_like_unlike_link').html(newLabelText);
				}
			} else {
				if( result_data.action == 'FAILED_LOGIN_REQUIRED' ) {
				}
			}
			var responseText = '<span style="padding: 3px; border-radius: 3px;" class="x'+result_class+'">'+result_message+'</span>';
			$('#comment_like_unlike_response').html(responseText);
			
			

		});	
}	
</script> 
<script>
function getfocus() {
    $('#inquiry_name').focus();
}
</script>



<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY --> 

<style>
body{
	padding-top:0px !important;
}
.cmt_row.reply_row {
    margin-left: 50px;
    margin-top: 30px;
}
.navbar-transparent{
	border-bottom:none !important; 
}
</style>
 <?php $this->load->view('common/footer_end'); ?>
