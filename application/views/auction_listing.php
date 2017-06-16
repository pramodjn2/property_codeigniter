<?php 

$this->load->view('common/header');

$this->load->view('common/top_header');

?>



<div class="main-container inner_maincontainer" style="margin-top:0px !important;">



  <!--- start: LATEST PROPERTY LISTING---->

  <?php $this->load->view('listings/auction_property_listing'); ?>

  <!--- end: LATEST PROPERTY LISTING---->

</div>

<!-- end: MAIN CONTAINER -->



<?php $this->load->view('common/footer_content');?>

<?php $this->load->view('common/footer'); ?>

<style>

.fa-heart-o{

cursor:pointer;

}

</style>

<script src="<?php echo config_item('base_url');?>assets/plugins/flex-slider/jquery.flexslider.js"></script> 

<script src="<?php echo config_item('base_url');?>assets/plugins/colorbox/jquery.colorbox-min.js"></script> 

<script src="<?php echo config_item('base_url');?>assets/plugins/jquery-ui/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script> 

<script src="<?php echo config_item('base_url');?>assets/plugins/jQRangeSlider/jQAllRangeSliders-min.js"></script> 

<script src="<?php echo config_item('base_url');?>assets/plugins/jQuery-Knob/js/jquery.knob.js"></script>

<link rel="stylesheet" href="<?php echo base_url('assets/plugins/jQRangeSlider/css/classic-min.css');?>">



<script src="<?=config_item('base_url');?>assets/js/property_listing.js"></script>

<script src="<?=config_item('base_url');?>assets/js/custom/property_list_save_result.js"></script>

<script>   

var loder_show = function(){

	$('#loader_wrapper').show();

}

var loder_hide = function(){

	$('#loader_wrapper').hide();

}

jQuery(document).ready(function() {

   loder_show();

   PropertyListing.init();

});



$(window).load(function(){

   loder_hide();

});



</script>

 

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false&libraries=places"></script>

<link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/css/pagination.css'); ?>"/>

<script>

var property_listing_data_json = '';

var property_result = '<?php echo $results; ?>';

if(property_result != ''){

	property_listing_data_json = eval('<?php echo $results; ?>');

}

</script> 

<script type="text/javascript" src="<?php echo config_item('base_url');?>assets/js/gmap/property_map_auction.js"></script> 

<script type="text/javascript">

var tempCount = 1;




$(function(){	

	applyPagination();

    function applyPagination() {

	 $("#ajax_paging a").click(function() {

        var url = $(this).attr("href");

      ajaxPaging(url); 

      return false;

      });

    }	

	
	/*$( ".applySearch" ).click(function() {
		var location = jQuery('#location').val();
		var country = $('.country').val();
            if(oldLocation != location){
				var newCountry = get_address();
				console.log(newCountry+'--'+country+'--'+oldLocation+'--'+location);
			   if(newCountry != country && oldLocation != location){
				   setTimeout(serachResult(),3000);
	             //
			   }
				 oldLocation = location;
			}
	});*/
 
/*	$("#location" ).on('blur', function() {
		var mt = get_address();
		
		
	});
	*/
/*$( "#location" ).change(function() {

	  serachResult();

	});*/
	
	$( "#howmanyrooms" ).change(function() {

	  serachResult();

	});

	$( "#property_type" ).change(function() {

	  serachResult();

	});

	$( "#near_by" ).change(function() {

	  serachResult();

	});

	$( "#bed_room" ).change(function() {

	  serachResult();

	});



    $(".sliderRange" ).change(function() {

	  serachResult();

	});
	

});

	function serachResult(){

		var url = base_url+"ajax/listing/1";

		ajaxPaging(url);

		return false;

	}

	

	var from_input_name = { street_number: 'short_name', route: 'long_name', locality: 'long_name', administrative_area_level_1: 'short_name', country: 'short_name', postal_code: 'short_name' };

	

	function get_address(){

		var location = jQuery('#location').val();

		var country = $('.country').val();

		var autocomplete = encodeURIComponent($.trim(location));

		var googleLocation = '';		 

		if(location != ''){

			$.ajax({

				url: "http://maps.googleapis.com/maps/api/geocode/json?address="+autocomplete+"&sensor=false;",

				type: "Get",

				success: function (data) {				

					$('.locality').val('');

					$('.administrative_area_level_1').val('');

					$('.country').val('');

					$('.postal_code').val('');								

					for (var i = 0; i < data.results[0].address_components.length; i++) {

						var addressType = data.results[0].address_components[i].types[0];

						if (typeof from_input_name[addressType] !== 'undefined') {

						  var val = data.results[0].address_components[i][from_input_name[addressType]];

						  //$('#'+target+' .'+addressType).val(val);						 

						  $('.'+addressType).val(val);

						  googleLocation = val;						  						  

						}

					}

					//alert(googleLocation);

					if(googleLocation !=''){
						//alert(googleLocation);

						//return googleLocation;
						serachResult()

					}else{

						alert('Entered location address is not valid!');						

						$('#error').show();

						$("#error_check").removeClass("form-group").addClass("form-group has-error");

						$('#location').focus();

						return false; 

					}				

				}

			});

			$('#error').hide();

			$("#error_check").removeClass("form-group has-error").addClass("form-group");	

		}else{

			alert('Kindly specify the location');

			$('#error').show();

			$("#error_check").removeClass("form-group").addClass("form-group has-error");

			$('#location').focus();

			return false;

		}

		

	}



	

	function ajaxPaging(url){

		

		var location = jQuery('#location').val();	
		//alert(location);
		//console.log('--'+location);		

		if(location == ''){

			/*$('#error').show();

			$("#error_check").removeClass("form-group").addClass("form-group has-error");			

			$('.locality').val('');

			$('.administrative_area_level_1').val('');

			$('.country').val('');

			$('.postal_code').val('');*/

			//alert('Kindly specify the location');					 

			return false;

		}else{

			

			$('#error').hide();

			$("#error_check").removeClass("form-group has-error").addClass("form-group");	

		    var mt = 'yes';//get_address();

			if(mt=='yes'){				

				var location = jQuery('#location').val();

				if(tempCount == 2){

				  return false;	

				}

				tempCount = 2;

				loder_show();

				var res = url.split("/");

				var ln = res.length -1;

				

				var url = base_url+'ajax/auctionlisting/'+res[ln];

				var min_price = jQuery('#min_price').val();

				var max_price = jQuery('#max_price').val();

				

				var city = jQuery('#city').val();

				var regions = jQuery('#regions').val();

				var country = jQuery('#country').val();

				var postal_code = jQuery('#postal_code').val();

				

				var latitude = jQuery('#latitude').val();

				var longitude = jQuery('#longitude').val();				

				

				var proprty_category = jQuery('#howmanyrooms').val();

				var property_type = jQuery('#property_type').val();

				var near_by = jQuery('#near_by').val();

				var bed_room = jQuery('#bed_room').val();

				var server_url = window.location.pathname;
  var filename = server_url.substring(server_url.lastIndexOf('/')+1);

				$.ajax({

					type: "GET",		

					url: url,

					data: {'min_price': min_price,'max_price': max_price,'location':location,'proprty_category':proprty_category,'property_type':property_type,'near_by':near_by,'bed_room':bed_room,'latitude': latitude,'longitude': longitude, 'city': city,'regions': regions,'country_code': country,'postal_code': postal_code}		

				}).done(function(data){

					
					 var stateObj = {'min_price': min_price,'max_price': max_price,'location':location,'proprty_category':proprty_category,'property_type':property_type,'near_by':near_by,'bed_room':bed_room,'latitude': latitude,'longitude': longitude, 'city': city,'regions': regions,'country_code': country,'postal_code': postal_code};
			
			
	
var url = base_url+'property/'+filename+'?location='+location+'&near_by=&min_price='+max_price+'&max_price='+max_price+'&bed_room=&property_type='+property_type+'&proprty_category='+proprty_category+'&city='+city+'&regions='+regions+'&country_code='+country+'&postal_code='+postal_code+'';

			        window.history.pushState(stateObj, '', url);
					

					//alert(data);

					

					var json_result = JSON.parse(data);

					var message=json_result.message;

		

					if(message=='success'){	

						var results = json_result.results;			

						var total_count = json_result.total_count;  			

						var pagination_link = json_result.pagination_link; 			

						jQuery('#ajax_paging').html(pagination_link);			

						var num_display = json_result.num_display; 			

						jQuery('#nodisplay').html(num_display);			

						var script = json_result.script;

						console.log(script);			

						jQuery('#script_attach').html(script);			

						property_listing_data_json = results;			

						getSearch();			

					}else{		

						$('#nodisplay').html(' ');			

						$("#showList").html(' ');			

						$("#showList").html('<div class="alert alert-warning">No record found</div>');			

						property_listing_data_json = '';			

						initialize();			

					}	

					tempCount = 1;		

					loder_hide();

				});

			}

		}		

		

	} // close ajaxSearchPaging function

	

	var range_min = '<?=$min_rang_price?>';

	var range_max = '<?=$max_rang_price?>';

	var currency_symbol = '<?php echo $currency_symbol; ?>';

	var current_rate = parseInt('<?php echo $current_rate; ?>');



	$( ".sliderRange" ).slider({

		range: true,

		min: parseInt(<?php echo $range_slider_min_length; ?>),

        max: parseInt(<?php echo $range_slider_max_length; ?>),

		values: [parseInt(range_min), parseInt(range_max)],

		slide: function( event, ui ) {

			$('#min_price').val(ui.values[0]);

 		    $('#max_price').val(ui.values[1]);

			minprice = numberformat(parseInt(ui.values[0])*current_rate);

			maxprice = numberformat(parseInt(ui.values[1])*current_rate);

			$( ".sliderRangeLabel" ).html( currency_symbol +' '+ minprice + " - "+ currency_symbol + ' '+maxprice);

		},

		 stop: function( event, ui ) {

			 serachResult();

		}

		

		

	

	});



	



	var numberformat = function(nStr){

		nStr += '';

		x = nStr.split('.');

		x1 = x[0];

		x2 = x.length > 1 ? '.' + x[1] : '';

		var rgx = /(\d+)(\d{3})/;

		while (rgx.test(x1)) {

			x1 = x1.replace(rgx, '$1' + ',' + '$2');

		}

		return x1 + x2;

	}

	

/*});*/



</script>

<?php $this->load->view('common/footer_end');?>

