<!-- Start: HEADER -->
<header class="single-menu">  
  <div role="navigation" class="navbar navbar-default navbar-transparent navbar-fixed-top space-top innerpagenav"> 
   <!-- start: TOP NAVIGATION CONTAINER -->
    <div class="container_inn">
      <div class="navbar-header">        
        <!-- start: RESPONSIVE MENU TOGGLER -->
        <button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle" type="button"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
        
        <!-- end: RESPONSIVE MENU TOGGLER --> 
        
        <!-- start: LOGO --> 
        
        <!--<a class="navbar-brand logo_inner" href="<?php echo config_item('base_url');?>"><?php echo config_item('site_name');?></a> -->
			<a class="navbar-brand logo_inner" href="<?php echo config_item('base_url');?>"><img src="<?php echo base_url('assets/images/logo_otriga.png');?>"></a>
        
        <!-- end: LOGO -->
        
        <div class="icon_menu_resp visible-xs hidden-sm hidden-md hidden-lg">
          <ul>
            <li><a href="<?php echo config_item('base_url');?>"> <i class="fa fa-home"></i> </a> </li>
            <li class="dropdown"> <a class="dropdown-toggle" href="#" data-toggle="dropdown" ><i class="fa fa-comment-o"></i></a>
              <ul class="dropdown-menu pull-right">
                <li><a href="<?=base_url('blog');?>"><i class="clip-blogger"></i>&nbsp;&nbsp;Blog</a></li>
                <li> <a href="<?=base_url('review');?>"><i class="fa fa-comments"></i>&nbsp;Write a Review</a> </li>
                <li> <a href="<?=base_url('faq');?>"><i class="fa fa-headphones"></i>&nbsp;Customer Support </a> </li>
              </ul>
            </li>
            
            <!-- start: RECENTLY SEEN DROPDOWN Responsive-->
            
            <li class="dropdown navbar-tools" id="history_navigation"> <a id="recently_seen" class="dropdown-toggle" href="#" data-toggle="dropdown"  title="Recently seen"> <i class="clip-history"></i> <span class="badge total_badge" id="total_badge">0</span> </a>
              <ul class="dropdown-menu posts pull-right recentlydrpdwn">
                <li> <span class="dropdown-menu-title total_seen" id="total_seen">Recently Seen 0 Properties</span> </li>
                <li>
                  <div class="drop-down-wrapper panel-scroll ps-container recently_seen_container" id="recently_seen_container">
                    <ul>
                      <li>
                        <div class="dont_have_recent_activity">You don't have recent seen property!</div>
                      </li>
                    </ul>
                  </div>
                </li>
              </ul>
            </li>
            
            <!-- end: RECENTLY SEEN DROPDOWN -->
            
            <?php if($this->session->userdata('user_id')==''){?>
            <li><a data-toggle="modal" data-target="#signin_signup" href="#"> <i class="fa fa-user"></i> <span class="hidden-xs hidden-sm visible-md visible-lg">Sign in</span> </a> </li>
            <?php }else { ?>
            
            <!-- start: MESSAGE DROPDOWN Responsive-->
            
            <li class="dropdown navbar-tools" id="messageNavigation"> <a class="dropdown-toggle" data-close-others="true"  data-toggle="dropdown" href="javascript:void(0)"> <i class="fa fa-envelope-o"></i> <span class="badge total_badge_mail" id="total_badge_mail">0</span> </a>
              <ul class="dropdown-menu posts pull-right">
                <li> <span class="dropdown-menu-title"> You have <span class="total_badge_mail">0</span> messages</span> </li>
                <li>
                  <div class="drop-down-wrapper panel-scroll ps-container recently_unread_mail" id="recently_unread_mail">
                    <ul>
                      <li>
                        <div class="dont_have_recent_activity">You don't have recent message!</div>
                      </li>
                    </ul>
                  </div>
                </li>
                <li class="view-all"> <a href="<?=base_url('mymessage');?>"> See all messages <i class="fa fa-arrow-circle-o-right"></i> </a> </li>
              </ul>
            </li>
            
            <!-- end: MESSAGE DROPDOWN --> 
            
            <!-- start: USER DROPDOWN -->
            
            <?php
            $user_id = $this->session->userdata('user_id');
            $groupName = $this->session->userdata('groupName');
            $profilePic = getCurrentProfilePic($user_id, USER_IMAGE_150150);
          ?>
            <li class="dropdown current-user" id="userNavigation"> <a data-toggle="dropdown" class="dropdown-toggle" href="javascript:void(0)">
              <?php /*?><img src="<?=$profilePic?>" style="width:30px;height:30px;" class="circle-img"> <span class="username"><?php echo ucfirst($this->session->userdata('name'));?></span> <i class="clip-chevron-down"></i><?php */?>
              <i class="fa fa-user"></i> </a>
              <ul class="dropdown-menu pull-right">
                <li> <a href="<?=base_url('mydashboard/profile');?>"> <i class="clip-user-2"></i>&nbsp;My Profile </a> </li>
                <li> <a href="<?=base_url('mydashboard/editprofile');?>"> <i class="clip-pencil-2"></i>&nbsp;Edit Profile </a> </li>
                <li> <a href="<?=base_url('mydashboard/setting');?>"> <i class="fa fa-wrench"></i>&nbsp;Setting </a> </li>
				
				<?php
				$group_id=$this->session->userdata('group_id');
				
				if(($group_id=='5')||($group_id=='6')){
				
				?>
				
				
				
                <li> <a href="<?=base_url('mydashboard/subscription');?>"> <i class="fa fa-money"></i>&nbsp;Choose Your Plan</a> </li>
				<?php }?>
                <li> <a href="<?=base_url('mydashboard/changePassword');?>"> <i class="fa fa-refresh"></i> &nbsp;Change Password </a> </li>
                
                <!--<li class="divider"></li>-->
                
                <li> <a href="<?php echo base_url('user/signout');?>"> <i class="clip-exit"></i>&nbsp;Log Out </a> </li>
              </ul>
            </li>
            
            <!-- end: USER DROPDOWN -->
            
            <?php }?>
            
            <!-- start: RECENTLY SEEN DROPDOWN --> 
            
            <!--<li class="dropdown navbar-tools" id="history_navigation"> <a id="recently_seen" class="dropdown-toggle" href="#" data-toggle="dropdown"  title="Recently seen"> <i class="clip-history"></i> <span class="badge" id="total_badge">0</span> </a>
            <ul class="dropdown-menu posts pull-right">
              <li> <span class="dropdown-menu-title" id="total_seen">Recently Seen 0 Properties</span> </li>
              <li>
                <div class="drop-down-wrapper panel-scroll ps-container" id="recently_seen_container">
                  <ul>
                    <li>
                      <div class="dont_have_recent_activity">You don't have recent seen property!</div>
                    </li>
                  </ul>
                </div>
              </li>
            </ul>
          </li>--> 
            
            <!-- end: RECENTLY SEEN DROPDOWN -->
            
            <div class="clear"></div>
          </ul>
        </div>
      </div>
      <?php 
	  	$controller = $this->router->class;
		$method = $this->router->method;
	  ?>
      <div class="navbar-collapse collapse">
        <ul class="nav navbar-nav navbar-right" id="deskTopNavigationTools">
          <li <?php if((($controller=='property')&&($method=='forsale'))||($proprty_category=='Sale')){?> class="selected_head"<?php }?>><a href="<?=base_url('property/forsale');?>">For sale</a></li>
          <li <?php if((($controller=='property')&&($method=='forrent'))||($proprty_category=='Rent')){?> class="selected_head"<?php }?>><a href="<?=base_url('property/forrent');?>">To rent</a></li>
          <?php  $userId=$this->session->userdata('user_id'); 
  if(!empty($userId)){?>
          <li <?php if(($controller=='property')&&($method=='auctionlisting')){?> class="selected_head"<?php }?>><a href="<?=base_url('property/auctionlisting');?>">For auction</a></li>
          <?php }?>
          <li <?php if($controller=='professional'){?> class="selected_head"<?php }?>> <a href="<?=base_url('professional');?>"> Find an expert </a> </li>
          <?php 
		  $groupId=$this->session->userdata('group_id');?>
          <li <?php if(($controller=='home')&&($method=='advertise')){?> class="selected_head"<?php }?>> <a href="<?=base_url('home/advertise');?>"> Advertise </a> </li>
          <li id="support_nav" class="dropdown"> <a class="dropdown-toggle" href="#" data-toggle="dropdown" ><i class="fa fa-comment-o"></i></a>
            <ul class="dropdown-menu pull-right">
              
              <!--<li><a href="<?=base_url('advise');?>"><i class="clip-users"></i>&nbsp;&nbsp;Advise</a></li>-->
              
              <li><a href="<?=base_url('blog');?>"><i class="clip-blogger"></i>&nbsp;&nbsp;Blog</a></li>
              <li> <a href="<?=base_url('review');?>"><i class="fa fa-comments"></i>&nbsp;Write a review</a> </li>
              <li> <a href="<?=base_url('faq');?>"><i class="fa fa-headphones"></i>&nbsp;Customer support </a> </li>
            </ul>
          </li>
          
          <!-- start: RECENTLY SEEN DROPDOWN -->
          
          <li id="recent_nav" class="dropdown navbar-tools" id="history_navigation">
          <a id="recently_seen" class="dropdown-toggle" href="#" data-toggle="dropdown"  title="Recently seen"> <i class="clip-history"></i> <span class="badge total_badge" id="total_badge">0</span> </a>
          <ul class="dropdown-menu posts pull-right">
            <li> <span class="dropdown-menu-title total_seen" id="total_seen">Recently Seen 0 Properties</span> </li>
            <li>
              <div class="drop-down-wrapper panel-scroll ps-container recently_seen_container" id="recently_seen_container">
                <ul>
                  <li>
                    <div class="dont_have_recent_activity">You don't have recent seen property!</div>
                  </li>
                </ul>
              </div>
            </li>
          </ul>
          </li>
          
          <!-- end: RECENTLY SEEN DROPDOWN -->
          
          <?php if($this->session->userdata('user_id')==''){?>
          <li id="signin_nav" <?php if(($controller=='user')&&($method=='login')){?> class="selected_head"<?php }?>><a data-toggle="modal" data-target="#signin_signup" href="#"> <i class="fa fa-user"></i> <span class="hidden-sm">Sign in </span> </a> </li>
          <?php }else { ?>
          
          <!-- start: MESSAGE DROPDOWN -->
          
          <li class="dropdown navbar-tools msg_nav" id="messageNavigation"> <a class="dropdown-toggle" data-close-others="true"  data-toggle="dropdown" href="javascript:void(0)"> <i class="fa fa-envelope-o"></i> <span class="badge total_badge_mail" id="total_badge_mail">0</span> </a>
            <ul class="dropdown-menu posts pull-right">
              <li> <span class="dropdown-menu-title"> You have <span class="total_badge_mail">0</span> messages</span> </li>
              <li>
                <div class="drop-down-wrapper panel-scroll ps-container recently_unread_mail" id="recently_unread_mail">
                  <ul>
                    <li>
                      <div class="dont_have_recent_activity">You don't have recent message!</div>
                    </li>
                  </ul>
                </div>
              </li>
              <li class="view-all"> <a href="<?=base_url('mymessage');?>"> See all messages <i class="fa fa-arrow-circle-o-right"></i> </a> </li>
            </ul>
          </li>
          
          <!-- end: MESSAGE DROPDOWN --> 
          
          <!-- start: USER DROPDOWN -->
          
          <?php
			$user_id = $this->session->userdata('user_id');
			$groupName = $this->session->userdata('groupName');
			$profilePic = getCurrentProfilePic($user_id, USER_IMAGE_150150);
		  ?>
          <li class="dropdown current-user user_nav" id="userNavigation"> <a data-toggle="dropdown" class="dropdown-toggle" href="javascript:void(0)"> <img src="<?=$profilePic?>" style="width:30px;height:30px;" class="circle-img"> <span class="username"><?php echo ucfirst($this->session->userdata('name'));?></span> <i class="clip-chevron-down"></i> </a>
            <ul class="dropdown-menu pull-right">
              <li> <a href="<?=base_url('mydashboard/profile');?>"> <i class="clip-user-2"></i>&nbsp;My Profile </a> </li>
              <li> <a href="<?=base_url('mydashboard/editprofile');?>"> <i class="clip-pencil-2"></i>&nbsp;Edit Profile </a> </li>
              <li> <a href="<?=base_url('mydashboard/setting');?>"> <i class="fa fa-wrench"></i>&nbsp;Setting </a> </li>
			  <?php
				$group_id=$this->session->userdata('group_id');
				
				if(($group_id=='5')||($group_id=='6')){
				
				?>
              <li> <a href="<?=base_url('mydashboard/subscription');?>"> <i class="fa fa-money"></i>&nbsp;Choose Your Plan </a> </li>
			  <?php }?>
              <li> <a href="<?=base_url('mydashboard/changePassword');?>"> <i class="fa fa-refresh"></i> &nbsp;Change Password </a> </li>
              
              <!--<li class="divider"></li>-->
              
              <li> <a href="<?php echo base_url('user/signout');?>"> <i class="clip-exit"></i>&nbsp;Log Out </a> </li>
            </ul>
          </li>
          
          <!-- end: USER DROPDOWN -->
          
          <?php }?>
        </ul>
      </div>
    </div>
    
    <!-- end: TOP NAVIGATION CONTAINER --> 
    
  </div>
</header>
