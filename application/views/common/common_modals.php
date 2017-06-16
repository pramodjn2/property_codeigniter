<div class="modal fade" id="signin_signup" role="dialog" style="display:none;">
  <div class="modal-dialog"> 
    <div class="modal-content">
      <div class="modal-header noborder">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <ul class="nav nav-tabs tab-padding tab-space-3 tab-blue nav-center">
          <li class="active"><a data-toggle="tab" id="tab_signin_tabs" href="#signin_tabs">
          Sign In</a></li>
          <li><a data-toggle="tab" id="tab_signup_tabs" href="#signup_tabs">Sign Up</a></li>
          <li><a data-toggle="tab" href="#socialmedia_tabs">Sign In With Social Media</a></li>
        </ul>
        <div class="tab-content">
           <div id="message"> <i class="fa fa-ok"></i> </div>
          <div id="signin_tabs" class="tab-pane active">
            <div class="row">
              <div class="main-login col-md-10 col-md-offset-1"> 
              
                <div class="box-login" id="box-login"> 
                  <?php 

					if($this->session->flashdata('success')){

					          echo '<div class="alert alert-success">

									<button class="close" data-dismiss="alert">&times;</button>

									'.$this->session->flashdata('success').'</div>';

	  						

						   }

				   

				     if($this->session->flashdata('danger')){

				          echo '<div class="alert alert-danger">

						        <button class="close" data-dismiss="alert">&times;</button>

								<i class="fa fa-times-circle"></i>'.$this->session->flashdata('danger').'</div>';

						  }
						  $request_url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

				   ?>
                  <?php echo validation_errors();?>
                  <form id="form-login" action="<?=base_url('user/login');?>" method="post">
                    <div class="errorHandler alert alert-danger no-display"> <i class="fa fa-remove-sign"></i> You have some form errors. Please check below. </div>
                    <fieldset>
                      <div class="row">
                        <div class="col-md-12">
                          <div id="form-login_message"> <i class="fa fa-ok"></i> </div>
                        </div>
                      </div>
                      <div class="form-group"> <span class="input-icon">
                        <input type="email" class="form-control required email" maxlength="50" name="email" placeholder="Email" value="<?=set_value('email');?>">
                        <i class="fa fa-user"></i> </span> </div>
                      <div class="form-group form-actions"> <span class="input-icon">
                        <input type="password" minlength="6" maxlength="40" class="form-control required" name="password" placeholder="Password">
                        <input type="hidden" name="request_url" value="<?=$request_url?>"  />
                        <i class="fa fa-lock"></i> <a class="forgot" href="javascript:void(0);">I forgot my password</a> </span> </div>
                      <div class="form-actions">
                        <label for="remember" class="checkbox-inline">
                          <input type="checkbox" class="grey remember icheckbox_minimal-grey" id="remember" name="remember" checked="checked">
                          Keep me signed in </label>
                        <button type="submit" class="btn red_button pull-right"> Login <i class="fa fa-arrow-circle-right"></i> </button>
                       
                      </div>
                      <div class="form-actions" style="display:none;">
                        <button type="submit" class="btn red_button next_btn">NEXT</button>
                      </div>
                      <div class="new-account"> Don't have an account yet? <a class="register" href="javascript:void(0);" onclick="tab_changes('tab_signup_tabs');">Create an account</a> </div>
                    </fieldset>
                  </form>
                </div>
                
                <!-- end: LOGIN BOX --> 
                
                <!-- start: FORGOT BOX -->
                
                <div class="box-forgot" id="box-forgot" style="display:none;">
                  <h3>Forget Password?</h3>
                  <p>Enter your e-mail address below to reset your password.</p>
                  <?php echo validation_errors();?>
                   <div class="row">
                        <div class="col-md-12">
                          <div id="forget_message"> <i class="fa fa-ok"></i> </div>
                        </div>
                      </div>
                  <form id="forgotFrom" action="<?=base_url('user/forgetPassword');?>" method="post">
                    <div class="errorHandler alert alert-danger no-display"> <i class="fa fa-remove-sign"></i> You have some form errors. Please check below. </div>
                    <fieldset>
                      <div class="form-group"> <span class="input-icon">
                        <input type="email" class="form-control required email" name="email" placeholder="Email" maxlength="50" value="<?=set_value('email');?>">
                        <i class="fa fa-envelope"></i> </span> </div>
                      <div class="form-actions"> 
                        
                        <!--   <button class="btn btn-light-grey go-back"> <i class="fa fa-circle-arrow-left"></i> Back </button>--> 
                        
                        <a class="btn btn-light-grey go-back" href="javascript:void(0);"><i class="fa fa-arrow-circle-left"></i> Back</a>
                        <button type="submit" class="btn red_button pull-right"> Submit <i class="fa fa-arrow-circle-right"></i> </button>
                      </div>
                    </fieldset>
                  </form>
                </div>
                
                <!-- end: FORGOT BOX --> 
                
                
                <div class="box-forgetpassword-verification" id="box-forgetpassword-verification" style="display:none;">
  <h3>Reset Password</h3>
  <p>Please enter new password.</p>
  <div class="row">
    <div class="col-md-12">
      <div id="forgetpassword_verification_message"> <i class="fa fa-ok"></i> </div>
    </div>
  </div>
  <form id="forgetpasswordverificationFrom" action="<?=base_url('user/reset_password');?>" method="post">
  
  <fieldset>
                <div class="form-group"> <span class="input-icon">
                  <input type="text" value="" placeholder="verifycode" name="verifycode" class="form-control required alphanumeric">
                  <i class="fa fa-user"></i> </span> </div>
                <div class="form-group form-actions"> <span class="input-icon">
                  <input type="password" placeholder="Password" name="newpass" id="password" minlength="6" maxlength="40" class="form-control required">
                  </span> </div>
                <div class="form-group form-actions"> <span class="input-icon">
                  <input type="password" placeholder="Confirm Password" name="newpassconf" id="confirmpassword" equalto="#password" minlength="6" maxlength="40" class="form-control required">
                  </span> </div>
                <div class="form-actions">
                  <button type="submit" class="btn red_button pull-right"> Reset Password <i class="fa fa-arrow-circle-right"></i> </button>
                </div>
              </fieldset>
  
  
  
  
  
    
  </form>
</div>


                
              </div>
            </div>
          </div>
          
          <div id="signup_tabs" class="tab-pane">
            <div class="row">
              <div class="col-md-10 col-md-offset-1">
                <form action="<?=base_url('user/signup');?>" role="form" id="create-account2" method="post">
                  <input type="hidden" name="group_id" value="8">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="errorHandler alert alert-danger no-display"> <i class="fa fa-times-sign"></i> You have some form errors. Please check below. </div>
                      <div class="successHandler alert alert-success no-display"> <i class="fa fa-ok"></i> Your form validation is successful! </div>
                   
                        <div id="signup_message"> <i class="fa fa-ok"></i> </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group connected-group">
                        <div class="row">
                          <div class="col-md-6 col-sm-6 col-xs-6">
                            <label class="control-label"> First name <span class="symbol required"></span> </label>
                            <input type="text" name="firstName" id="firstName" class="form-control required alphanumeric" maxlength="35" placeholder="First name" value="<?=set_value('firstName');?>">
                          </div>
                          <div class="col-md-6 col-sm-6 col-xs-6">
                            <label class="control-label"> Last name <span class="symbol required"></span> </label>
                            <input type="text" name="lastName" id="lastName" maxlength="35" class="form-control required alphanumeric" placeholder="Last name" value="">
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label"> Email address <span class="symbol required"></span> </label>
                        <input type="email" placeholder="example@otriga.com" class="form-control required email" maxlength="50" id="email" name="email" value="<?=set_value('email');?>">
                      </div>
                      <div class="form-group">
                        <label class="control-label"> Password <span class="symbol required"></span> </label>
                        <input type="password" class="form-control required " minlength="6" maxlength="40" name="password" id="password" value="">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-8">
                      <p> By clicking <b>Get Started</b> button, you are agreeing to the
                       <a href="<?=base_url('page/content/NQ/policies-and-review-guidelines')?>" target="_blank" >Policy</a> and 
                      <a href="<?=base_url('page/content/Ng/terms-and-privacy')?>" target="_blank" >Terms &amp; Conditions.</a> </p>
                      
                      <!--<p><a href="<?=base_url('home/advertise');?>">Enter Otriga Advertise</a></p>--> 
                      
                    </div>
                    <div class="col-md-4"> 
                      
                      <!--  <button class="btn btn-block red_button" type="submit">







                                    Register <i class="fa fa-arrow-circle-right"></i>







                                </button>-->
                      
                      <input type="submit" class="btn btn-block red_button" name="Register" value="Get Started">
                    </div>
                  </div>
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
        <input type="text" class="form-control required" name="verification_code" placeholder="verification code" value="" maxlength="20">
        <i class="fa fa-envelope"></i> </span> </div>
      <div class="form-actions"> 
        <button type="submit" class="btn red_button pull-right"> Submit <i class="fa fa-arrow-circle-right"></i> </button>
      </div>
    </fieldset>
  </form>
</div>

              </div>
            </div>
          </div>
          <div id="socialmedia_tabs" class="tab-pane">
            <div class="row">
              <div class="col-md-10 col-md-offset-1">
                <form action="<?php echo base_url('agent/listing');?>" method="post" type="post">
                  <div class="row">
                    <div class="form-group center"> <a class="btn btn-facebook" href="<?php echo base_url('social/index.php?login&oauth_provider=facebook');?>"> <i class="fa fa-facebook"></i> | Sign In with Facebook </a> </div>
                    <div class="form-group center"> <a class="btn btn-twitter" href="<?php echo base_url('social/index.php?login&oauth_provider=twitter');?>"> <i class="fa fa-twitter"></i> | Sign In with Twitter </a> </div>
                    <div class="form-group center"> <a class="btn btn-linkedin" href="<?php echo base_url('linkedin_signup/initiate');?>" > <i class="fa fa-linkedin"></i> | Sign In with LinkedIn </a> </div>
                    <div class="form-group center"> <a class="btn btn-google-plus" href="<?php echo base_url('google');?>"> <i class="fa fa-google-plus"></i> | Sign In with Google Plus </a> </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer"> 
        
        <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>--> 
        
      </div>
    </div>
    
    <!-- end: Modal content--> 
    
  </div>
</div>

<!-- end: Modal for registration and login  --> 

<script src="<?=base_url('assets/js/validation/jquery.validate.min.js');?>"></script> 
<script src="<?=base_url('assets/js/validation/jquery.form.min.js');?>"></script> 
<script src="<?=base_url('assets/js/validation/registration_main.js');?>"></script> 
<script src="<?=base_url('assets/js/validation/main.js');?>"></script> 
<script src="<?=base_url('assets/js/custom/create-account.js');?>"></script> 
<script src="<?=base_url('assets/plugins/jquery-validation/dist/jquery.validate.min.js');?>"></script> 

<script src="<?=base_url('assets/js/validation/additional-methods.min.js');?>"></script> 


<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY --> 

<script>

	jQuery(document).ready(function() {
		createAccountFormValidator.init();
		Login.init();
	});



	/*jQuery(document).ready(function() {



		createAccountFormValidator.init();



		Login.init();



	});*/



</script> 
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
<script>

                function tab_changes(id){

					$("#"+id).trigger( "click" );

					}

                </script>
                 <style>.error{ color:#F00; 
border-color : #b94a48;
}
body.modal-open, #signin_signup.modal{
    overflow: auto !important;
}

.box-login .input-icon > [class*="fa-"], .input-icon > [class*="clip-"] {
 line-height: 41px !important;
}
</style>