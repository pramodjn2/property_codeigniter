<?php $this->load->view('common/header'); ?>
<!-- Start: HEADER -->
<?php //$this->load->view('common/header_content'); ?>        
<!-- end: HEADER -->
<!-- start: MAIN CONTAINER -->
<div class="main-container">
	<div class="navbar-content">
		<!-- start: SIDEBAR -->
		<?php //$this->load->view('common/left_navigation'); ?>
		<!-- end: SIDEBAR -->
	</div>
	<!-- start: PAGE -->
	<div class="main-content">
		<div class="container">        
			<!-- start: PAGE HEADER -->
			<div class="row">
				<div class="col-sm-12">					
					<!-- start: PAGE TITLE & BREADCRUMB -->
                    <?php //$this->load->view('common/breadcrumb'); ?>
					<div class="page-header">
						<!--<h1><?php //echo $page_title;?> <small>overview &amp; stats </small></h1>-->
					</div>
					<!-- end: PAGE TITLE & BREADCRUMB -->
				</div>
			</div>
			<!-- end: PAGE HEADER -->
			<!-- start: PAGE CONTENT 404-->
			<div class="col-sm-12 page-error">
                    
					
					<?php
					$websetting = $this->session->userdata('websetting');
					?>
					
					<?= $websetting['under_construction_msg'];?>
					
					<a href="javascript:void(0);" class="btn red_button" onclick="contactModelShow();">
								Contact
							</a>
				</div>
			<!-- End: PAGE CONTENT 404-->
			</div>
		</div> 
	</div>
</div> 
    
<div class="modal fade" id="contactModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
      <form id="contact_underconstruction" name="contact_underconstruction" action="<?=base_url('underconstraction/send');?>" type="post" method="post">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">CONTACT</h4>
      </div>
     
      <div class="modal-body">
      <div class="PD_whiteblock" id="focusData">
      <div id="returnMessage" style="display:none;"></div>
     
        <div class="form-group">
        
         <div class="col-md-12 no-padding">
         <div id="message"> <i class="fa fa-ok"></i> </div>
         </div>
         
          <div class="col-md-12 no-padding">
            <input type="text" value="" maxlength="50" class="form-control required email" name="email" id="email" placeholder="Email" >
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-12 no-padding">
            <input type="text" value="" maxlength="35" class="form-control required" name="fullname" id="fullname" placeholder="Fullname">
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-12 no-padding">
            <textarea class="form-control required" maxlength="500" id="inquiry_message" name="message" placeholder="Message"></textarea>
          </div>
        </div>
        <div class="clear"></div>
        </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light-grey go-back" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i>CLOSE</button>
         
          <button type="submit" class="btn red_button pull-right">SEND&nbsp;<i class="fa fa-arrow-circle-right"></i></button>
        </div>
     
    </div>  </form>
  </div>
</div>  
<!-- end: PAGE -->
<?php //$this->load->view('common/footer_main'); ?>
<!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->


<script src="<?=base_url();?>assets/plugins/jQuery-lib/2.0.3/jquery.min.js"></script> 
<script src="<?=base_url();?>assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="<?=$base_url?>assets/plugins/rainyday/rainyday.js"></script>
<script src="<?=base_url();?>assets/js/utility-error404.js"></script>


<script src="<?=base_url('assets/js/validation/jquery.validate.min.js');?>"></script> 
<script src="<?=base_url('assets/js/validation/jquery.form.min.js');?>"></script> 
<script src="<?=base_url('assets/js/validation/main.js');?>"></script> 

<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
<script>
    jQuery(document).ready(function() {
       // Main.init();
        //Error404.init();
		setTimeout("saveForm('contact_underconstruction');",400);
				
    });
</script>
<script>
function contactModelShow(){
 $('#contactModel').modal('show');
} 
</script>
<style type="text/css">
.error{ color:#F00; 
border-color : #b94a48;
}
</style>
<?php //$this->load->view('common/footer'); ?>
