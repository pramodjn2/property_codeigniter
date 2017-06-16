<?php $this->load->view('common/header');
$this->load->view('common/top_header'); ?>
<!-- start: MAIN CONTAINER -->

<div class="main-container ">

<!--- start: FIND AGENT---->
<section class="wrapper wrapper-grey padding50 login example2">
<div class="container_inn">
<div class="row">
<div class="main-login col-sm-4 col-sm-offset-4">
<?php if(!empty($message)){?>
<?php echo $message; ?>
<?php } ?>
<!---<a class="logo_inner center" href="index.html" style="font-size: 20px;">
				OTRIGA
				<i class="fa fa-globe" style="font-size: 20px;"></i>
				PORTAL
			</a>-->
<div class="clear"></div>

			
			
			<!-- start: REGISTER BOX -->
			<div class="box-register">
				
                
                 <?php echo validation_errors(); ?>
				<form action="<?=base_url('user/signup');?>" role="form" id="create-account2" method="post">
					<input type="hidden" name="group_id" value="8">
                  <h3>Sign Up</h3>
				<p>
					Enter your Account details below:
				</p>
                  <div class="row">

                    <div class="col-md-12">

                      <div class="errorHandler alert alert-danger no-display"> <i class="fa fa-times-sign"></i> You have some form errors. Please check below. </div>

                      <div class="successHandler alert alert-success no-display"> <i class="fa fa-ok"></i> Your form validation is successful! </div>

                      <div id="signup_message"> <i class="fa fa-ok"></i> </div>

                    </div>

                  </div>
					<fieldset>
						<div class="form-group">
						    <label class="control-label"> First name <span class="symbol required"></span> </label>
							<input type="text" name="firstName" id="firstName" class="form-control required alphanumeric" placeholder="First name" value="<?=set_value('firstName');?>">
						</div>
						<div class="form-group">
						<label class="control-label"> Last name  <span class="symbol required"></span> </label>
								<input type="text" name="lastName" id="lastName" class="form-control required alphanumeric" placeholder="Last name" value="">
						</div>
						<div class="form-group">
							<label class="control-label"> Email address <span class="symbol required"></span> </label>
								 <input type="email" placeholder="example@otriga.com" class="form-control required email" id="email" name="email" value="<?=set_value('email');?>" maxlength="50">
								
						</div>
						<div class="form-group">
							 <label class="control-label"> Password <span class="symbol required"></span> </label>
								<input type="password" class="form-control required " minlength="6" maxlength="40" name="password" id="password" value="">
								
						</div>
						
						
						
						
                        
                          <p class="note"> By clicking <b>Get Started</b> button, you are agreeing to the
                       <a href="<?=base_url('page/content/NQ/policies-and-review-guidelines')?>" target="_blank" >Policy</a> and 
                      <a href="<?=base_url('page/content/Ng/terms-and-privacy')?>" target="_blank" >Terms &amp; Conditions.</a> </p>
                      
                      
						
                        <div class="new-account">

                              Already registered? 

                                <a href="<?=base_url('user/login')?>" class="register">sign in</a>


                            </div>
                            
						<button class="btn red_button pull-right" type="submit">
								GET STARTED <i class="fa fa-arrow-circle-right"></i>
						</button>
						
						
						
						
						
					</fieldset>
				 </form>
				 
				 <div class="box-verification" id="box-verification" style="display:none;">
  <h3>Email verification</h3>
  <p>Enter your e-mail verification code below and actived your account.</p>
  <div class="row">
    <div class="col-md-12">
      <div id="verification_message"> <i class="fa fa-ok"></i> </div>
    </div>
  </div>
  <form id="verificationFrom" action="<?=base_url('user/verification');?>" method="post">
    <fieldset>
      <div class="form-group"> <span class="input-icon">
        <input type="text" class="form-control required alphanumeric" name="verification_code" placeholder="verification code" value=""  maxlength="20">
		<input type="hidden" name="submittype" value="simple"/>
        <i class="fa fa-envelope"></i> </span> </div>
      <div class="form-actions"> 
        <button type="submit" class="btn red_button pull-right"> Submit <i class="fa fa-arrow-circle-right"></i> </button>
      </div>
    </fieldset>
  </form>
</div>
			</div>
			<!-- end: REGISTER BOX -->
			<!-- start: COPYRIGHT -->
			
			<!-- end: COPYRIGHT -->
		</div>
		
		</div>
    </div>
  </section>
  <!--- end: FIND AGEN----> 
  
 
  
</div> 
<!-- end: MAIN CONTAINER -->

	<!--	<script src="<?php echo base_url();?>assets/plugins/jquery-validation/dist/jquery.validate.min.js"></script>
		<script src="<?php echo base_url();?>assets/js/login.js"></script>-->
		



<?php $this->load->view('common/footer_content');?>
<?php $this->load->view('common/footer'); ?>

<script src="<?=base_url('assets/js/validation/jquery.validate.min.js');?>"></script> 
<script src="<?=base_url('assets/js/validation/jquery.form.min.js');?>"></script> 
<script src="<?=base_url('assets/js/validation/registration_main.js');?>"></script>
<script src="<?=base_url('assets/plugins/jquery-validation/dist/jquery.validate.min.js');?>"></script> 
<script src="<?=base_url('assets/js/validation/additional-methods.min.js');?>"></script> 

<script>



 $(document).ready(function(){


setTimeout("ajaxSaveForm('form-login');",200);
setTimeout("ajaxSaveForm('forgotFrom');",100);
setTimeout("ajaxSaveForm('create-account2');",100);
setTimeout("ajaxSaveForm('verificationFrom');",200);
setTimeout("ajaxSaveForm('forgetpasswordverificationFrom');",100);


/*$('#tab_signin_tabs').bind('click', function () {

          $('.box-login').show();
          $('.box-forgot').hide();
		 $('.box-forgetpassword-verification').hide();
		alert('1');  

        });*/
	
		

});


	
	/*$("#tab_signin_tabs").click(function(){
         $('.box-login').show();
          $('.box-forgot').hide();
		 $('.box-forgetpassword-verification').hide();
});	 
		
  
    $("#tab_signup_tabs").click(function(){

            $('#box-verification').hide();

            $('#create-account2').show();

        });*/



</script> 
<style>
.error{ color:#F00 !important;

border-color : #b94a48 !important;

}
</style>

<?php $this->load->view('common/footer_end_witrhout_common_modals');?>