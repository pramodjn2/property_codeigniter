<?php $this->load->view('common/header');
$this->load->view('common/top_header'); ?>
<!-- start: MAIN CONTAINER -->

<div class="main-container">

  
  <!--- start: FIND AGENT---->
  <section class="wrapper wrapper-grey padding50 thankyouwrap">
    <div class="container_inn">
		<div class="row">
        	
            <div class="clear"></div>
			<h3>Thankyou for REGISTERing YOUR AGENCY with us</h3>
			<div class="smiley">
            	<img src="<?php echo base_url('assets/images/smiley.png');?>" alt="smiley">
            </div>
			<h2>Get in front of buyers and sellers in the largest online real estate network.</h2>
		
		</div>
    </div>
  </section>
  <!--- end: FIND AGEN----> 
  
  <!--- start: SUBSCRIPTION AREA---->
    <?php $this->load->view('agent/subscribe_agent'); ?>
  <!--- end: SUBSCRIPTION AREA ----> 
  
</div> 
		

<script type="application/javascript" src='https://www.google.com/recaptcha/api.js'></script>
<?php $this->load->view('common/footer_content');?>
<?php $this->load->view('common/footer'); ?>
<?php $this->load->view('common/footer_end');?>