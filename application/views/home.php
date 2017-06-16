<?php 
$this->load->view('common/header');
$this->load->view('home/header_navigation'); 
?>
<!-- start: MAIN CONTAINER -->
<div class="main-container"> 
  
  <!--<div class="trans_wrap"></div>--> 
  
  <!-- start: SEARCHING FORM -->
  
  <?php $this->load->view('home/home_search_form'); ?>
  
  <!-- end: SEARCHING FORM --> 
  
  <!-- start: REVOLUTION SLIDERS -->
  
  <?php $this->load->view('home/home_slider'); ?>
  
  <!-- end: REVOLUTION SLIDERS --> 
  
  <!--- start: BELOW SLIDER BLACK STRIP----> 
  
  <!--- start: CITY LOCATION LISTING---->
  
  <?php $this->load->view('home/property_abroad'); ?>
  
  <!--- end: CITY LOCATION LISTING----> 
  <?php 
  $position1 = displayAdvertise('1','1');
  $position2 = displayAdvertise('1','2');
  if($position1 !='' || $position2 !=''){
  ?>
  <section class="wrapper wrapper-grey padding50"> 
      <div class="container">
        <div class="row">
          <div class="col-md-6">
          	<?php echo $position1;?>
          </div>
          <div class="col-md-6">
            <?php echo $position2;?>
          </div>
        </div>
      </div>   
  </section>
  <?php 
  }
  ?>
  <!--- start: SUBSCRIPTION AREA---->
  
  <?php $this->load->view('home/subscription'); ?>
  
  <!--- end: SUBSCRIPTION AREA ----> 
  
  <!--- start: LATEST PROPERTY LISTING---->
  
  <?php $this->load->view('home/latest_properties'); ?>
  
  <!--- end: LATEST PROPERTY LISTING----> 
  
  <!--- start: LATEST PROPERTY LISTING---->
  
  
  
  <?php
  $userId=$this->session->userdata('user_id'); 
 // if(!empty($userId)){
  $this->load->view('home/auction_properties'); 
 // }
  ?>
  
  <!--- end: LATEST PROPERTY LISTING----> 
  
</div>
<!-- end: MAIN CONTAINER --> 
<!-- start: FOOTER -->
<?php $this->load->view('common/footer_content');
$this->load->view('home/footer');
$this->load->view('home/footer_home_page');
?>
<script type="text/javascript">
function calculateremaingtime(){
	jQuery('.reverse-counter').each(function(index, element) {
		var dateFuture = new Date(jQuery(this).attr('date-time-end'));
		var dateNow = new Date();	
		
		var seconds = Math.floor((dateFuture - (dateNow))/1000);
		var minutes = Math.floor(seconds/60);
		var hours = Math.floor(minutes/60);
		var days = Math.floor(hours/24);
	
		hours = hours - (days*24);
		minutes = minutes-(days*24*60)-(hours*60);
		seconds = seconds-(days*24*60*60)-(hours*60*60)-(minutes*60);
		jQuery(this).html('Time Left : ' + days + 'days ' + hours + ':' + minutes + ':' + seconds);
	});
}
jQuery(document).ready(function(e) {
//initialize();
 
 //setInterval("locationrent();", 200); 
/*locationprofession();
responsivesale();
responsiverent();
responsiveprofession();*/
   setInterval(calculateremaingtime, 500); // Time in milliseconds	
});
</script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places"></script>
<script>
var autocomplete = {};
var autocompletesWraps = ['for_sale_from', 'for_rent_from','for_professional_from','sale_from_responsive','rent_from_responsive','professional_from_responsive'];
var from_input_name = { street_number: 'short_name', route: 'long_name', locality: 'long_name', administrative_area_level_1: 'short_name', country: 'short_name', postal_code: 'short_name' };
var test2_form = { street_number: 'short_name', route: 'long_name', locality: 'long_name', administrative_area_level_1: 'short_name', country: 'long_name', postal_code: 'short_name' };

var addressData = '';
var previousAddress = '';

function initialize(){	
	$.each(autocompletesWraps, function(index, name){
		if($('#'+name).length == 0){
			return;
		}
		autocomplete[name] = new google.maps.places.Autocomplete($('#'+name+' .autocomplete')[0], { types: ['geocode'] });				
		google.maps.event.addListener(autocomplete[name], 'place_changed', function(){
			var place = autocomplete[name].getPlace();		
			var form = eval(from_input_name);						
			 $('.locality').val('');
			 $('.administrative_area_level_1').val('');
			 $('.country').val('');
			 $('.postal_code').val('');
			 addressData = '';
			 previousAddress = place.formatted_address;
			if(place.address_components.length != ''){
			for (var i = 0; i < place.address_components.length; i++) {
				var addressType = place.address_components[i].types[0];
				if (typeof form[addressType] !== 'undefined') {
				  var val = place.address_components[i][form[addressType]];						  
				  console.log('#'+name+' .'+addressType +' = '+val);
				  //$('#'+name+' .'+addressType).val(val);
				  $('.'+addressType).val(val);
				  addressData = val;
				}
			}
		   }
		});
	});
}
google.maps.event.addDomListener(window, 'load', initialize);
/*$('.autocomplete').on('blur', function(){
	var address = $(this).val();
	//alert(address+' = '+previousAddress + ' : '+addressData);
	if((addressData =='' && address !='') || (addressData !='' && address != previousAddress)){
		var id = $(this).closest('form').attr('id');		
		getAddress(id);
		previousAddress = address;
	}
});*/

var abroad_listing = function(id){
	if(typeof id === 'undefined'){
		return false;
	};
	  $('#abroad_id_'+id).submit();

	}
</script>
<?php 
$this->load->view('common/footer_end'); 
$loginType = @$_GET['login'];
?>
<script>
$(window).load(function() {
     var loginType = '<?=$loginType;?>';
	 if(loginType != ''){
	 	$('#signin_signup').modal('show');
	 }
});

function searchListing(){
	var st = getAddress('for_professional_from');
	if(st){
		var spec=$('#specialitiesId').val();
		$('#hiddenSpecialitiesId').val(spec);
		$('#for_professional_from').submit();
	}
}

function searchListingresponsive(){
	var st = getAddress('professional_from_responsive');
	if(st){
		var spec=$('#specialitiesIdrespo').val();
		$('#hiddenSpecialitiesIdrespo').val(spec);
		$('#professional_from_responsive').submit();
	}
}

</script> 
<script>
// [END region_geolocation]
function getAddress(id){
 var country = $('#'+id+' .country').val();
 var autocomplete = $('#'+id+' .autocomplete').val();
  var autocomplete = encodeURIComponent($.trim(autocomplete));
 if(autocomplete == ''){
       alert('Please fill your search area name.');
	   return false;
 }else{
	 //if(country == ''){
      	$.ajax({
				url: "https://maps.googleapis.com/maps/api/geocode/json?address="+autocomplete+"&sensor=false;",
				type: "Get",
				success: function (data) {					
				 $('.locality').val('');
				 $('.administrative_area_level_1').val('');
				 $('.country').val('');
				 $('.postal_code').val('');
					addressData = '';		
					for (var i = 0; i < data.results[0].address_components.length; i++) {
						var addressType = data.results[0].address_components[i].types[0];
						if (typeof from_input_name[addressType] !== 'undefined') {
						  var val = data.results[0].address_components[i][from_input_name[addressType]];
						  //$('#'+target+' .'+addressType).val(val);
						  //console.log('#'+id+' .'+addressType +' = '+val);
						  $('.'+addressType).val(val);
						  addressData = val;		  
						}
					}
					//alert(data.results[0].formatted_address);
				}
				
			});
			return true;
 	//}
			
 }
}
</script> 
    
