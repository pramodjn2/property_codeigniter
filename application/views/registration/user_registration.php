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
				<h3>Sign Up</h3>
				<p>
					Enter your Account details below:
				</p>
                
                 <?php echo validation_errors(); ?>
                 <?php echo form_open("user/registration"); ?>
				<form class="form-register" novalidate="novalidate">
					<div class="errorHandler alert alert-danger no-display">
						<i class="fa fa-remove-sign"></i> You have some form errors. Please check below.
					</div>
					<fieldset>
						<div class="form-group">
							<input type="text" placeholder="Full Name" name="fullname" id="fullname" class="form-control" value="<?php if(!empty($fullname)){ echo $fullname; }?>">
						</div>
						<div class="form-group">
							<span class="input-icon">
								<input type="email" placeholder="Email" name="email" id="email" class="form-control" value="<?php if(!empty($email)){ echo $email; }?>">
								<i class="fa fa-envelope"></i> </span>
						</div>
						<div class="form-group">
							<span class="input-icon">
								<input type="password" placeholder="Password" name="password" id="password" class="form-control" value="<?php if(!empty($password)){ echo $password; }?>">
								<i class="fa fa-lock"></i> </span>
						</div>
						<div class="form-group">
							<span class="input-icon">
								<input type="password" placeholder="Password Again" name="password_again" id="password_again" class="form-control">
								<i class="fa fa-lock"></i> </span>
						</div>
						<div class="form-group">
							<div>
								<label class="radio-inline">
									<input type="radio" name="gender" value="Female" class="grey">
									Female
								</label>
								<label class="radio-inline">
									<input type="radio" name="gender" value="Male" class="grey">
									Male
								</label>
							</div>
						</div>
						<div class="form-group">
							<div>
								<label class="checkbox-inline" for="agree">
									<input type="checkbox" class="grey agree" id="agree" name="agree">
									I Wish to receive information from Otriga.com
								</label>
							</div>
						</div>
						<div class="form-group">
							<div>
								 <div class="g-recaptcha" data-sitekey="<?=config_item('google_captch_site_key');?>"></div>
							</div>
						</div>
						<p class="note">By registering you accept our Terms of Use and Privacy and our selected partners may contact you with relevant offers and services. You may unsubscribe or update your preferences at any time in Otriga.com</p>
						<div class="form-actions pull-left">
							<div class="form-group">
							<div>
								<label for="agree" class="checkbox-inline">
									<input type="checkbox" class="grey agree" id="agree" name="agree">
									Already Register <a href="<?php echo base_url('userlogin');?>">LOGIN</a>
								</label>
							</div>
						</div>	
						</div>
						<button class="btn red_button pull-right" type="submit">
								Submit <i class="fa fa-arrow-circle-right"></i>
						</button>
						
						
						
						
						
					</fieldset>
				 <?php echo form_close(); ?>
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
		

<script type="application/javascript" src='https://www.google.com/recaptcha/api.js'></script>
<?php $this->load->view('common/footer_content');?>
<?php $this->load->view('common/footer'); ?>
<?php $this->load->view('common/footer_end');?>