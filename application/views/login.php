<?php 

$this->load->view('common/header');

$this->load->view('common/top_header');

?>



<div class="main-container">

    <section class="wrapper wrapper-grey padding50 login example2">

        <div class="container_inn">

            <div class="row">

                

               <div class="main-login col-sm-4 col-sm-offset-4">

                <!-- start: LOGIN BOX -->

                <div class="box-login">

                    

                    <form  action="<?=base_url('user/login');?>" method="post" id="form-login">
					
					<h3>Sign in to your account</h3>

                    <p>Please enter your name and password to log in.</p>
					<div id="form-login_message"> <i class="fa fa-ok"></i> </div>
                   <p> <?php if(!empty($msg)){
					         if($msg['warning']){
							   echo '<div class="alert alert-danger"><i class="fa fa-times-circle"></i> <strong>Warning!</strong> '.$msg['warning'].'</div>'; 
							}else if($msg['success'])
							   echo '<div class="alert alert-success"><i class="fa fa-times-circle"></i><strong>Well done!</strong> '.$msg['success'].'</div>';
					   } ?> </p>

                    <?php echo validation_errors();?>
					

                        <div class="errorHandler alert alert-danger no-display">

                            <i class="fa fa-remove-sign"></i> You have some form errors. Please check below.
							
							

                        </div>

                        <fieldset>

                            <div class="form-group">

                                <span class="input-icon">

                                    <input type="email" class="form-control required email" name="email" placeholder="Email" value="<?=set_value('email');?>" maxlength="50">

                                    <i class="fa fa-user"></i> 

                                </span>

                            </div>

                            <div class="form-group form-actions">

                                <span class="input-icon">

                                    <input type="password" minlength="5" class="form-control required" name="password" placeholder="Password" minlength="6" maxlength="40">

                                    <i class="fa fa-lock"></i><a class="forgot" href="javascript:void(0);">I forgot my password</a>

                                </span>

                            </div>

                            <div class="form-actions">

                                <label for="remember" class="checkbox-inline">

                                    <input type="checkbox" class="grey remember icheckbox_minimal-grey" id="remember" name="remember" checked="checked">

                                    Keep me signed in

                                </label>

                                <button type="submit" class="btn red_button pull-right">

                                    Login <i class="fa fa-arrow-circle-right"></i>

                                </button>

                            </div>

                            <div class="form-actions" style="display:none;">

                                <button type="submit" class="btn red_button next_btn">NEXT</button>

                            </div>

                            <div class="new-account">

                                Don't have an account yet?

                                <a href="<?=base_url('user/signupform');?>" class="register">Create an account</a>

                            </div>

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
                        <input type="email" class="form-control required email" name="email" placeholder="Email" value="<?=set_value('email');?>"  maxlength="50">
						<input type="hidden" name="submittype" value="simple"/>
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

                            

            </div>		

          </div>

       </div>

    </section>

</div>



<!-- end: MAIN CONTAINER -->



<?php $this->load->view('common/footer_content'); ?>

<?php $this->load->view('common/footer'); ?>

<script src="<?=base_url('assets/js/validation/jquery.validate.min.js');?>"></script> 
<script src="<?=base_url('assets/js/validation/jquery.form.min.js');?>"></script> 
<script src="<?=base_url('assets/js/validation/registration_main.js');?>"></script>
<script src="<?=base_url('assets/plugins/jquery-validation/dist/jquery.validate.min.js');?>"></script> 

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
<script src="<?=base_url()?>assets/js/custom/create-account.js"></script>

<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->

<script>

	jQuery(document).ready(function() {

		Login.init();

	});

</script>
<style>
.error{ color:#F00; 

border-color : #b94a48;

}
</style>

<?php $this->load->view('common/footer_end_witrhout_common_modals');?>