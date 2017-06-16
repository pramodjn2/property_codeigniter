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
            <h3>Reset Password</h3>
            <p>Please enter your new password.</p>
            <p>
              <?php if(!empty($msg)){

					         if($msg['warning']){

							   echo '<div class="alert alert-danger"><i class="fa fa-times-circle"></i> <strong>Warning!</strong> '.$msg['warning'].'</div>'; 

							}else if($msg['success'])

							   echo '<div class="alert alert-success"><i class="fa fa-times-circle"></i><strong>Well done!</strong> '.$msg['success'].'</div>';

					   } ?>
            </p>
            <?php echo validation_errors();?>
            <form class="forget_password" action="<?=base_url('user/emailverify');?>" method="post">
              <div class="errorHandler alert alert-danger no-display"> <i class="fa fa-remove-sign"></i> You have some form errors. Please check below. </div>
              <fieldset>
                <div class="form-group"> <span class="input-icon">
                  <input type="text" class="form-control required" name="verifycode" placeholder="verifycode" value="<?=@$_GET['verifycode'];?>"  maxlength="20">
                  <i class="fa fa-user"></i> </span> </div>
                <div class="form-group form-actions"> <span class="input-icon">
                  <input type="password" class="form-control required"  minlength="6" maxlength="40" id="password" name="newpass" placeholder="Password">
                  </span> </div>
                <div class="form-group form-actions"> <span class="input-icon">
                  <input type="password" class="form-control required" minlength="6" maxlength="40" equalTo="#password" id="confirmpassword" name="newpassconf" placeholder="Confirm Password">
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
  </section>
</div>

<!-- end: MAIN CONTAINER -->

<?php $this->load->view('common/footer_content'); ?>
<?php $this->load->view('common/footer'); ?>
<script src="<?=base_url()?>assets/plugins/jquery-validation/dist/jquery.validate.min.js"></script> 
<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY --> 


<?php $this->load->view('common/footer_end');?>
<script>
$(document).ready(function(){
$('.forget_password').validate();
	

});
</script>
<style>
.error{ color:#F00; 

border-color : #b94a48;

}
</style>