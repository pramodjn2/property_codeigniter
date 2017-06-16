<?php 
$this->load->view('common/header');
$this->load->view('common/top_header');
?>


        
<div class="main-container">
<!--- start: TOP HEAD INNERPAGE SEARCHING BAR---->

  <?php $this->load->view('listings/property_details'); ?>
  <?php // $this->load->view('common/subscription.php'); ?>
   <?php $this->load->view('agent/subscribe_agent'); ?>
</div>
<!-- end: MAIN CONTAINER --> 



        
<?php $this->load->view('common/footer_content');?>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
<?php $this->load->view('common/footer');?>

<script>
var property_nearby_data_json = '';
 var property_result = '<?php echo $nearby_property; ?>';
  if(property_result != ''){
	 property_nearby_data_json = eval('<?php echo $nearby_property; ?>');
	}
	
	
 var nearby_propertyLatLng = '';
 <?php if(!empty($nearby_propertyLatLng)){ ?>
 nearby_propertyLatLng =  JSON.parse('<?php echo $nearby_propertyLatLng; ?>');
 
 <?php } ?>
 
</script>

<script src="<?php echo config_item('base_url');?>assets/js/custom/property_details.js"></script>

 <link rel="stylesheet" href="<?php echo config_item('base_url');?>assets/css/front_end_custom.css">
 <link rel="stylesheet" href="<?php echo config_item('base_url');?>assets/css/light_custom.css">
 <script src="<?php echo config_item('base_url');?>assets/plugins/flex-slider/jquery.flexslider.js"></script> 
 <script src="<?php echo config_item('base_url');?>assets/plugins/colorbox/jquery.colorbox-min.js"></script> 
 <style type="text/css">
 .prop_whitebg{min-height:266px;}

.propD_topsection .mini-stats li, .propD_topsection .mini-stats {
  /*border-left: 0 none !important;
  border-right: 0 none !important;*/
  padding: 25px 0 40px 0;
}

.meetmoreagents .thumbnail .img-rounded {
  border: 1px solid #ccc;
  border-radius: 6px;
}
 </style>
 <script>
 
	
function contactModelShow(){
 
 var cklin = check_user_session();
	 if(cklin > 0){	 

 $('#contactModel').modal('show');
 
 
 
 }
} 
function mapModelShow(){
	 
	 
	  $('.banner-hide').hide();
	  $('#map-canvas').show();
	  $('#street-canvas').hide();
	 // $('#streetViewModal').modal('MapViewModal');
	 setTimeout("initialize();",200);
	 $('#scroll-top').click();
}
 
function streetModelShow(){
	
      $('.banner-hide').hide();
	  $('#map-canvas').hide();
	  $('#street-canvas').show();
	  //$('#streetViewModal').modal('show');
	   setTimeout("street_view();",200);
	   $('#scroll-top').click();
	  
}
function floorPlanModelShow(){
 $('#map-canvas').hide();
 $('#street-canvas').hide();
 $('.banner-hide').show();
 $('#myFloorPlan').modal('show');
}
function vedioModelShow(){
$('#map-canvas').hide();
 $('#street-canvas').hide();
 $('.banner-hide').show();
 $('#myModal').modal('show');
}
// function to initiate FlexSlider
var runFlexSlider = function(options) {
	$(".flexslider").each(function() {
		var slider = $(this);
		var defaults = {
			animation: "slide",
			animationLoop: false,
			controlNav: true,
			directionNav: false,
			slideshow: false,
			prevText: "",
			nextText: ""
		};
		var config = $.extend({}, defaults, options, slider.data("plugin-options"));
		if( typeof config.sync !== 'undefined') {
			var carousel = {
				animation: "slide",
				controlNav: false,
				animationLoop: false,
				slideshow: false,
				prevText: "",
				nextText: "",
				asNavFor: slider
			};
			var configCarousel = $.extend({}, carousel, $(config.sync).data("plugin-options"));
			$(config.sync).flexslider(configCarousel);
		}
		// Initialize Slider
		slider.flexslider(config);
	});
};

 var runColorbox = function () {
        $(".group1").colorbox({
            rel: 'group1',
            width:"85%"
        });
    };
 
 
jQuery(document).ready(function() {
	runFlexSlider();  
	runColorbox();
	
	
    var property_id = '<?php echo $property_id; ?>';
       property_view(property_id);
    });

 var property_view = function(property_id){

 var url = base_url+'Ajax/property_views'; 

     $.ajax({

     type: "POST",

     url: url,

     data: {'property_id': property_id}

     })

     .done(function(msg) {

     }); 

 }

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







 <?php $this->load->view('common/footer_end'); ?>
