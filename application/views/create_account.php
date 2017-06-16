<?php 
$this->load->view('common/header');
$this->load->view('common/top_header'); 
?>
<div class="main-container">

	<section class="wrapper-create-account">
    	<div class="create-account-banner"></div>
    </section>
    
	<section class="wrapper-create-account">
    	<div class="row no-margin">
        	<div class="col-md-12"> 		                    
                <ul class="custom-tab">        	
                    <li class="active"><a onclick="return false;" href="#professional">Are you professional?</a></li>
                    <li><a onclick="return false;" href="#customer">Are you looking for house?</a></li>
                </ul>
            </div>
        </div>
    </section>
    
	<section class="wrapper wrapper-grey padding50 create-account-form">
    	<div class="container_inn">
        	<div class="row">
        		<div class="col-md-6">
                	<h2>Why Otriga-portal.com</h2>
                    <div><img src="<?=base_url('assets/images/why-we-are.png');?>" class="img-responsive"></div>
                </div>
                <div class="col-md-6" id="professional">  
                <?php echo validation_errors();?>
                    <form action="<?=base_url('user/signup');?>" role="form" id="create-account" method="post">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="errorHandler alert alert-danger no-display">                                	
                                    <i class="fa fa-times-sign"></i> You have some form errors. Please check below.
                                </div>
                                <div class="successHandler alert alert-success no-display">
                                    <i class="fa fa-ok"></i> Your form validation is successful!
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">                            
                            <div class="col-md-12">
                            	<div class="form-group">
                                    <label class="control-label">
                                        Select your profession <span class="symbol required"></span>
                                    </label>
                                    <div>
                                        <label class="radio-inline">
                                            <input <?php echo (set_value('group_id') == '4')?'checked="checked"':'';?> type="radio" class="grey" name="group_id" id="agency" value="4">
                                            Agency
                                        </label>
                                        <label class="radio-inline">
                                            <input <?php echo (set_value('group_id') == '5')?'checked="checked"':'';?> type="radio" class="grey" name="group_id" id="agent" value="5">
                                            Agent
                                        </label>
                                        <label class="radio-inline">
                                            <input <?php echo (set_value('group_id') == '6')?'checked="checked"':'';?> type="radio" class="grey" name="group_id" id="solicitor" value="6">
                                            Solicitor
                                        </label>
                                        <label class="radio-inline">
                                            <input <?php echo (set_value('group_id') == '7')?'checked="checked"':'';?> type="radio" class="grey" name="group_id" id="contractor"  value="7">
                                            Contractor
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group connected-group">
                                    <label class="control-label">
                                        Full Name <span class="symbol required"></span>
                                    </label>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-6">
                                            <input type="text" name="firstName" id="firstName" class="form-control" placeholder="First Name" value="<?=set_value('firstName');?>">
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-6">
                                            <input type="text" name="lastName" id="lastName" class="form-control" placeholder="Last Name" value="<?=set_value('lastName');?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">
                                        Email Address <span class="symbol required"></span>
                                    </label>
                                    <input type="email" placeholder="example@otriga-portal.com" class="form-control" id="email" name="email" value="<?=set_value('email');?>">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">
                                        Password <span class="symbol required"></span>
                                    </label>
                                    <input type="password" class="form-control" name="password" id="password" value="">
                                </div>
                            </div>                            
                        </div>
                        
                        <div class="row">
                            <div class="col-md-8">
                                <p>
                                    By clicking REGISTER, you are agreeing to the Policy and Terms &amp; Conditions.
                                </p>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-block" type="submit">
                                    Register <i class="fa fa-arrow-circle-right"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-6" id="customer" style="display:none">
                	<?php echo validation_errors();?>
                	<form action="<?=base_url('user/signup');?>" role="form" id="create-account2" method="post">
                    	<input type="hidden" name="group_id" value="8">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="errorHandler alert alert-danger no-display">                                	
                                    <i class="fa fa-times-sign"></i> You have some form errors. Please check below.
                                </div>
                                <div class="successHandler alert alert-success no-display">
                                    <i class="fa fa-ok"></i> Your form validation is successful!
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">                            
                            <div class="col-md-12">                       	
                                <div class="form-group connected-group">
                                    <label class="control-label">
                                        Full Name <span class="symbol required"></span>
                                    </label>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-6">
                                            <input type="text" name="firstName" id="firstName" class="form-control" placeholder="First Name" value="<?=set_value('firstName');?>">
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-6">
                                            <input type="text" name="lastName" id="lastName" class="form-control" placeholder="Last Name" value="<?=set_value('lastName');?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">
                                        Email Address <span class="symbol required"></span>
                                    </label>
                                    <input type="email" placeholder="example@otriga-portal.com" class="form-control" id="email" name="email" value="<?=set_value('email');?>">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">
                                        Password <span class="symbol required"></span>
                                    </label>
                                    <input type="password" class="form-control" name="password" id="password" value="">
                                </div>
                            </div>                            
                        </div>
                        
                        <div class="row">
                            <div class="col-md-8">
                                <p>
                                    By clicking REGISTER, you are agreeing to the Policy and Terms &amp; Conditions.
                                </p>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-block" type="submit">
                                    Register <i class="fa fa-arrow-circle-right"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    
    <section class="wrapper wrapper-grey padding50 create-account-form">
    	<div class="container_inn">
        	<div class="row">
            	<h2>How it work?</h2>   
        		<div class="col-md-4">
                	<h3><span class="badge badge-danger">1</span> You can manage your Property & Agent</h3>
                    <p>Add your property details, photos and payment policies during your registration. Once we confirm your details, you set your property live and can start receiving reservations immediately.</p>
                </div>                
                <div class="col-md-4">
                	<h3><span class="badge badge-danger">2</span> We tell the world about you</h3>
                    <p>We show your property in a way that is relevant to guests around the world, in up to 41 languages. We also market your property on search engines like Google, Bing and Yahoo to help you sell more rooms and increase revenue!</p>
                </div>
                <div class="col-md-4">   
                	<h3><span class="badge badge-danger">3</span> You get instant bookings & reviews</h3> 
                    <p>All bookings made through Booking.com are confirmed instantly. Booking.com guests leave reviews of their stay which help your future guests make the decision to stay with you.</p>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- end: MAIN CONTAINER --> 

<?php $this->load->view('common/footer_content'); ?>
<?php $this->load->view('common/footer'); ?>
<script src="<?=base_url()?>assets/plugins/jquery-validation/dist/jquery.validate.min.js"></script>
<!--<script src="<?=base_url()?>assets/plugins/summernote/build/summernote.min.js"></script>
<script src="<?=base_url()?>assets/plugins/ckeditor/ckeditor.js"></script>
<script src="<?=base_url()?>assets/plugins/ckeditor/adapters/jquery.js"></script>-->
<script src="<?=base_url()?>assets/js/custom/create-account.js"></script>
<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
<script>
	jQuery(document).ready(function() {
		createAccountFormValidator.init();
	});
	$('.custom-tab a').click(function(e){
		$(this).closest('ul').find('li').removeClass('active');
		$(this).closest('li').addClass('active');		
		var url = $(this).attr('href');//"www.site.com/index.php#hello";
		var hash = url.substring(url.indexOf('#')+1);
		if(hash=='customer'){
			$('#professional').hide();
			$('#customer').show();
		}else{
			$('#customer').hide();
			$('#professional').show();
		}        
    });
</script>
<?php $this->load->view('common/footer_end');?>