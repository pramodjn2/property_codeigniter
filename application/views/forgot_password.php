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

                    <h3>Update Password</h3>

                    <p>Please enter your name and password to log in.</p>
                   <p> <?php if(!empty($msg)){
					         if($msg['warning']){
							   echo '<div class="alert alert-danger"><i class="fa fa-times-circle"></i> <strong>Warning!</strong> '.$msg['warning'].'</div>'; 
							}else if($msg['success'])
							   echo '<div class="alert alert-success"><i class="fa fa-times-circle"></i><strong>Well done!</strong> '.$msg['success'].'</div>';
					   } ?> </p>

                    <?php echo validation_errors();?>

                    <form class="form-login" action="<?=base_url('user/update_password');?>" method="post">

                        <div class="errorHandler alert alert-danger no-display">

                            <i class="fa fa-remove-sign"></i> You have some form errors. Please check below.

                        </div>

                        <fieldset>

                            

                            <div class="form-group form-actions">

                                <span class="input-icon">

                                    <input type="password" class="form-control password" name="update_password" placeholder="New Password">

                                    

                                </span>

                            </div>

                            <div class="form-actions">

                                <label for="remember" class="checkbox-inline">

                                    <input type="checkbox" class="grey remember icheckbox_minimal-grey" id="remember" name="remember" checked="checked">

                                    Keep me signed in

                                </label>

                                <button type="submit" class="btn red_button pull-right">

                                    Update <i class="fa fa-arrow-circle-right"></i>

                                </button>

                            </div>

                            <div class="form-actions" style="display:none;">

                                <button type="submit" class="btn red_button next_btn">NEXT</button>

                            </div>

                            <div class="new-account">

                                Don't have an account yet?

                                <a href="<?=base_url('?login=signin');?>" class="register">Create an account</a>

                            </div>

                        </fieldset>

                    </form>

                </div>

                <!-- end: LOGIN BOX -->

                

                <!-- start: FORGOT BOX -->

                 <div class="box-forgot" style="display:none;">

                    <h3>Forget Password?</h3>

                    <p>Enter your e-mail address below to reset your password.</p>

                    <?php echo validation_errors();?>

                    <form class="form-forgot" action="<?=base_url('user/forgetPassword');?>" method="post">

                        <div class="errorHandler alert alert-danger no-display">

                            <i class="fa fa-remove-sign"></i> You have some form errors. Please check below.

                        </div>

                        <fieldset>

                            <div class="form-group">

                                <span class="input-icon">

                                    <input type="email" class="form-control" name="email" placeholder="Email" value="<?=set_value('email');?>">

                                    <i class="fa fa-envelope"></i> 

                                </span>

                            </div>

                            <div class="form-actions">

                                <button class="btn btn-light-grey go-back">

                                    <i class="fa fa-circle-arrow-left"></i> Back

                                </button>

                                <button type="submit" class="btn red_button pull-right">

                                    Submit <i class="fa fa-arrow-circle-right"></i>

                                </button>

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

<script src="<?=base_url()?>assets/plugins/jquery-validation/dist/jquery.validate.min.js"></script>

<script src="<?=base_url()?>assets/js/custom/create-account.js"></script>

<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->

<script>

	jQuery(document).ready(function() {

		Login.init();

	});

</script>

<?php $this->load->view('common/footer_end');?>