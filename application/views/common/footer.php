<!-- start: MAIN JAVASCRIPTS --> 
<!--[if lt IE 9]>
<script src="<?=base_url();?>assets/plugins/respond.min.js"></script>
<script src="<?=base_url();?>assets/plugins/excanvas.min.js"></script>
<script src="<?=base_url();?>assets/plugins/html5shiv.js"></script>
<script src="<?=base_url();?>assets/plugins/jQuery-lib/1.10.2/jquery.min.js" type="text/javascript"></script>
<![endif]-->
<!--[if gte IE 9]><!--> 
<script src="<?=base_url();?>assets/plugins/jQuery-lib/2.0.3/jquery.min.js"></script> 
<!--<![endif]--> 
<script src="<?=base_url();?>assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="<?=base_url();?>assets/plugins/bootstrap/js/bootstrap-formhelpers.min.js"></script>
<script src="<?=base_url();?>assets/plugins/jquery.transit/jquery.transit.js"></script>
<script src="<?=base_url();?>assets/plugins/jquery.appear/jquery.appear.js"></script> 
<script src="<?=base_url();?>assets/plugins/blockUI/jquery.blockUI.js"></script> 
<script src="<?=base_url();?>assets/plugins/bootstrap-multiselect/js/bootstrap-multiselect.js"></script>
<script src="<?=base_url();?>assets/plugins/iCheck/jquery.icheck.min.js"></script>
<script src="<?=base_url();?>assets/plugins/perfect-scrollbar/src/jquery.mousewheel.js"></script>
<script src="<?=base_url();?>assets/plugins/perfect-scrollbar/src/perfect-scrollbar.js"></script>
<script src="<?=base_url();?>assets/plugins/jQuery-Dropdown/js/jquery.dd.min.js"></script>
<script src="<?=base_url();?>assets/plugins/hover-dropdown/twitter-bootstrap-hover-dropdown.min.js"></script>
<script src="<?=base_url();?>assets/plugins/jquery-cookie/jquery.cookie.js"></script> 
<script src="<?=base_url();?>assets/js/custom/apply-style.js"></script>
<script src="<?=base_url();?>assets/js/front-end-main.js"></script>
<script src="<?=base_url();?>assets/js/custom/recent_activity_alert.js"></script>
<script src="<?=base_url();?>assets/js/custom/email-subscribe.js"></script>

<!-- end: MAIN JAVASCRIPTS -->
<script>
    jQuery(document).ready(function() {
        Main.init();
		//site_visit();
		
		
    });	
	

	
//genrateSession('<?php echo $this->session->userdata('user_id');?>');
function genrateSession(id){
	if(session_user_id != ''){
  var url = base_url+'advise/Ajax/forum_login'; 
     $.ajax({
     type: "POST",
     url: url,
     data: {'id': id}
     })
     .done(function(result) {

	 });
	}
}	
	
	



</script> 
<?php 
	if(isset($scriptsrc) && !empty($scriptsrc)){
		$i=0;
		foreach($scriptsrc as $value){$i++;
			$tab = ($i!=1)?"\t\t ":"";
			echo $tab.'<script src="'.base_url().$value.'" type="text/javascript"></script>'."\n";
		}
	}
	if(isset($script) && !empty($script)){
		foreach($script as $value){
			echo $value;
		}
	}
?>
<script>
//var tc;
$(document).ready(function(e) {	
	$("#currency").msDropdown({mainCSS:'transparent'}).data("dd");
});

</script>