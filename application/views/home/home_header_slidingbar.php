<!-- Start: HEADER -->
<header>
<!-- start: SLIDING BAR (SB) -->
<div id="slidingbar-area">
  <div id="slidingbar"> 
    <!-- start: SLIDING BAR FIRST COLUMN -->
    <div class="col-md-4 col-sm-4">
      <h2>About</h2>
      <p> Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa.
        Nulla consequat massa quis enim.
        Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu.
        In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. </p>
    </div>
    <!-- end: SLIDING BAR FIRST COLUMN --> 
    <!-- start: SLIDING BAR SECOND COLUMN -->
    <div class="col-md-4 col-sm-4">
      <h2>Recent Works</h2>
      <div class="blog-photo-stream margin-bottom-30">
        <ul class="list-unstyled">
          <li> <a href="#"><img alt="" src="<?php echo config_item('base_url');?>assets/images/image01.jpg"></a> </li>
          <li> <a href="#"><img alt="" src="<?php echo config_item('base_url');?>assets/images/image02.jpg"></a> </li>
          <li> <a href="#"><img alt="" src="<?php echo config_item('base_url');?>assets/images/image03.jpg"></a> </li>
          <li> <a href="#"><img alt="" src="<?php echo config_item('base_url');?>assets/images/image04.jpg"></a> </li>
          <li> <a href="#"><img alt="" src="<?php echo config_item('base_url');?>assets/images/image05.jpg"></a> </li>
          <li> <a href="#"><img alt="" src="<?php echo config_item('base_url');?>assets/images/image06.jpg"></a> </li>
          <li> <a href="#"><img alt="" src="<?php echo config_item('base_url');?>assets/images/image07.jpg"></a> </li>
          <li> <a href="#"><img alt="" src="<?php echo config_item('base_url');?>assets/images/image08.jpg"></a> </li>
          <li> <a href="#"><img alt="" src="<?php echo config_item('base_url');?>assets/images/image09.jpg"></a> </li>
          <li> <a href="#"><img alt="" src="<?php echo config_item('base_url');?>assets/images/image10.jpg"></a> </li>
        </ul>
      </div>
    </div>
    <!-- end: SLIDING BAR SECOND COLUMN --> 
    <!-- start: SLIDING BAR THIRD COLUMN -->
    <div class="col-md-4 col-sm-4">
      <h2>Contact Us</h2>
      <address class="margin-bottom-40">
      Clip-One <br>
      12345 Street Name, City Name, United States <br>
      P: (641)-734-4763 <br>
      Email: <a href="#"> info@example.com </a>
      </address>
    </div>
    <!-- end: SLIDING BAR THIRD COLUMN --> 
  </div>
  <!-- start: SLIDING BAR TOGGLE BUTTON --> 
  <!--<a href="#" class="sb_toggle"> </a>--> 
  <!-- end: SLIDING BAR TOGGLE BUTTON --> 
</div>
<!-- end: SLIDING BAR --> 

<!-- start: TOP BAR -->
  <div class="clearfix " id="topbar">
    <div class="container">
      <div class="row">
        <div class="col-sm-2">
          <!-- start: TOP BAR CURRENCY SELECTOR -->
          <?php if($this->config->item('multi-currency')){?>
          <form action="<?=base_url('home/currencies');?>" id="currencies-switch" method="post">
          <div class="bfh-selectbox bfh-currencies" data-currency="<?=defaultCurrency();?>" data-name="currency" data-blank="false" data-flags="true" data-available="USD,GBP,INR,AED"></div>
          </form>
           <?php }?>
          <!-- end: TOP BAR CURRENCY SELECTOR -->
        </div>       
        <div class="col-sm-2"> 
          <!-- start: TOP BAR LANGUAGE SELECTOR -->
           <?php if($this->config->item('multi-language')){?>
          <form action="<?=base_url('home/languages');?>" id="languages-switch" method="post">
          <div class="bfh-selectbox bfh-languages" data-language="<?=defaultLanguage();?>" data-name="language" data-blank="false" data-available="en_US,fr_CA,es_MX" data-flags="true"></div>
          </form>
          <?php }?>
          <!-- end: TOP BAR LANGUAGE SELECTOR --> 
        </div>
                
        <div class="col-sm-8">        	         
          <!-- start: TOP BAR SOCIAL ICONS -->
          <div class="social-icons">
            <ul>
              <li class="social-twitter tooltips" data-original-title="Twitter" data-placement="bottom"> <a target="_blank" href="http://www.twitter.com/"> Twitter </a> </li>
              <li class="social-dribbble tooltips" data-original-title="Dribbble" data-placement="bottom"> <a target="_blank" href="http://dribbble.com/"> Dribbble </a> </li>
              <li class="social-facebook tooltips" data-original-title="Facebook" data-placement="bottom"> <a target="_blank" href="http://facebook.com/"> Facebook </a> </li>
              <li class="social-google tooltips" data-original-title="Google" data-placement="bottom"> <a target="_blank" href="http://google.com/"> Google+ </a> </li>
              <li class="social-linkedin tooltips" data-original-title="LinkedIn" data-placement="bottom"> <a target="_blank" href="http://linkedin.com/"> LinkedIn </a> </li>
              <li class="social-youtube tooltips" data-original-title="YouTube" data-placement="bottom"> <a target="_blank" href="http://youtube.com/"> YouTube </a> </li>
              <li class="social-rss tooltips" data-original-title="RSS" data-placement="bottom"> <a target="_blank" href="#" > RSS </a> </li>
            </ul>
          </div>
          <!-- end: TOP BAR SOCIAL ICONS --> 
        </div>
      </div>
    </div>
  </div>
<!-- end: TOP BAR -->

<div role="navigation" class="navbar navbar-default navbar-transparent navbar-fixed-top space-top"> 
  <!-- start: TOP NAVIGATION CONTAINER -->
  <div class="container">
    <div class="navbar-header"> 
      <!-- start: RESPONSIVE MENU TOGGLER -->
      <button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle" type="button"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
      <!-- end: RESPONSIVE MENU TOGGLER --> 
      <!-- start: LOGO --> 
      <a class="navbar-brand" href="<?=base_url();?>"> OTRIGA <i class="fa fa-home"></i> </a> 
      <!-- end: LOGO --> 
    </div>
    <div class="navbar-collapse collapse">
      <ul class="nav navbar-nav navbar-right">
        <li class="active"> <a href="index.html"> <i class="fa fa-home"></i> <?=translate('Home');?></a> </li>
        <li> <a href="<?=base_url('user/signup');?>"><?=translate('Sign Up');?></a> </li>
        <li> <a href="<?=base_url('user/login');?>"><?=translate('Sign In');?></a> </li>
        <li> <a href="<?=base_url('page/help');?>"><?=translate('Help');?></a> </li>        
        <!--<li class="dropdown"> <a class="dropdown-toggle" href="#" data-toggle="dropdown" data-hover="dropdown"> Help <b class="caret"></b> </a>
          <ul class="dropdown-menu">
            <li> <a href="#"> Help text link 1 </a> </li>
            <li> <a href="#"> Help text link 2 </a> </li>
            <li> <a href="#"> Help text link 3 </a> </li>
            <li> <a href="#"> Help text link 4 </a> </li>
          </ul>
        </li>-->
      </ul>
    </div>
  </div>
  <!-- end: TOP NAVIGATION CONTAINER --> 
</div>
</header>
