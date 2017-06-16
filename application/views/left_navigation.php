<div class="navbar-content">
	<?php 
    $controller = $this->router->class;
    $method = $this->router->method;
    $admin_type = $this->session->userdata('groupName');
    ?>
    <div class="main-navigation navbar-collapse collapse"> 
      <!-- start: MAIN MENU TOGGLER BUTTON -->
      <div class="navigation-toggler"> <i class="clip-chevron-left"></i> <i class="clip-chevron-right"></i> </div>
      <!-- end: MAIN MENU TOGGLER BUTTON --> 
      <!-- start: MAIN NAVIGATION MENU -->
      <ul class="main-navigation-menu">
        <li<?php if($controller=='mydashboard' && $method=='index'){?> class="active open"<?php }?>> <a href="<?php echo base_url('mydashboard');?>"><i class="clip-home-3"></i> <span class="title"> Dashboard </span><span class="selected"></span></a></li>
        
        <li<?php if($controller=='mymessage'){?> class="active open"<?php }?>> <a href="<?=base_url('mymessage');?>"><i class="fa fa-envelope"></i> <span class="title"> Message </span> <span class="selected"></span> </a> </li>
        
        <li<?php if($controller=='myproperties'){?> class="active open"<?php }?>> <a href="<?=base_url('myproperties');?>"><i class="clip-pie"></i> <span class="title"> Properties</span><span class="selected"></span> </a> </li>
        
        <li<?php if($controller=='myagencies'){?> class="active open"<?php }?>> <a href="<?php echo base_url('myagencies');?>"><i class="clip-user-5"></i> <span class="title"> Agencies</span><span class="selected"></span> </a> </li>
    
        <li<?php if($controller=='myteammember'){?> class="active open"<?php }?>> <a href="<?php echo base_url('myteammember');?>"><i class="clip-users"></i> <span class="title"> Team-member</span><span class="selected"></span> </a> </li>
        
         <li> <a href="javascript:void(0);" onclick="FacebookInviteFriends();"><i class="clip-users"></i> <span class="title">FB Invite Friends</span><span class="selected"></span> </a> </li>
    
      </ul>
      <!-- end: MAIN NAVIGATION MENU --> 
    </div>
</div>

