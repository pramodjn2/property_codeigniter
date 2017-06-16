<footer id="footer">
  <div class="container">
    <div class="row">
    
		<?php $footData = getFooterLink();
		
		/*echo '<pre/>';
		print_r($footData);*/
		
		if(!empty($footData)){
			$countRow = floor(9 / (count($footData)));
			foreach($footData as $val){
				echo '<div class="col-md-'.$countRow.' col-sm-'.$countRow.' col-xs-12">';
		   		
				
				 if(!empty($val)):
				 echo '<h4>'.$val[0]["cat_title"].'</h4>';
				 echo '<ul class="contact quicklinks">';
			 	foreach($val as $linkData2):
				  		$link_url_2 = "href='#'";
						
						if(!empty($linkData2['link_type'])):
							if($linkData2['link_type'] == "external_url"):
								//$link_url_2 = 'href="'.base_url($linkData2['link_url']).'" target="_blank"';								
								$link_url_2 = 'href="'.$linkData2['link_url'].'" target="_blank"';
							elseif($linkData2['link_type'] == "new_tab"):
							$seo_url = seo_friendly_urls($linkData2['page_name'],'',$linkData2['static_pages_id']);
							    $link_url_2 = 'href="'.base_url('page/content/'.$seo_url).'" target="_blank"';
							elseif($linkData2['link_type'] == "parent_url"):
							$seo_url = seo_friendly_urls($linkData2['page_name'],'',$linkData2['static_pages_id']);
						        $link_url_2 = 'href="'.base_url('page/content/'.$seo_url).'"';
							endif;	
						endif;
				   echo  '<li><a '.$link_url_2.'>'.ucfirst(str_replace('_','&nbsp;',$linkData2['page_name'])).'</a></li>';
				 
				endforeach; 
				echo '</ul>';
			   endif;
			   
				echo '</div>';
			}
			
			}
		
		?>
        
      
      
      
	  
	  
	  
	 
      

      <div class="col-md-3 col-sm-3 col-xs-12"> 
      
      
      <!--- DO NOT EDIT - GlobalSign SSL Site Seal Code - DO NOT EDIT ---><table width=125 border=0 cellspacing=0 cellpadding=0 title="CLICK TO VERIFY: This site uses a GlobalSign SSL Certificate to secure your personal information." ><tr><td><span id="ss_img_wrapper_gmogs_image_90-35_en_white"><a href="https://www.globalsign.com/" target=_blank title="GlobalSign Site Seal" rel="nofollow"><img alt="SSL" border=0 id="ss_img" src="//seal.globalsign.com/SiteSeal/images/gs_noscript_90-35_en.gif"></a></span><script type="text/javascript" src="//seal.globalsign.com/SiteSeal/gmogs_image_90-35_en_white.js"></script></td></tr></table><!--- DO NOT EDIT - GlobalSign SSL Site Seal Code - DO NOT EDIT --->
      
      
      <a class="footer_logo" href="http://otriga.co.uk/"> <img alt="Otriga" src="<?php echo base_url('assets/images/otriga_digital.png');?>"> </a>

        <div class="copyright">
		<?php $websetting = $this->session->userdata('websetting');?>
        </div>
		
		<div class="social-icons">

            <ul>
<?php
 if(!empty($websetting) && !empty($websetting['twitter_url'])){
	$twitter_url =  $websetting['twitter_url'];
	echo '<li class="social-twitter tooltips" data-original-title="Twitter" data-placement="top"> <a target="_blank" href="'.$twitter_url.'"> Twitter </a> </li>';
}

 if(!empty($websetting) && !empty($websetting['flicker_url'])){
	$flicker_url =  $websetting['flicker_url'];
	echo '<li class="social-flicker tooltips" data-original-title="Flicker" data-placement="top"> <a target="_blank" href="'.$flicker_url.'"> Flicker </a> </li>';
}

 if(!empty($websetting) && !empty($websetting['facebook_url'])){
	$facebook_url =  $websetting['facebook_url'];
	echo '<li class="social-facebook tooltips" data-original-title="Facebook" data-placement="top"> <a target="_blank" href="'.$facebook_url.'"> Facebook </a> </li>';
}



 if(!empty($websetting) && !empty($websetting['google_url'])){
	$google_url =  $websetting['google_url'];
	echo '<li class="social-google tooltips" data-original-title="Google" data-placement="top"> <a target="_blank" href="'.$google_url.'"> Google+ </a> </li>';
}

 if(!empty($websetting) && !empty($websetting['linkedin_url'])){
	$linkedin_url =  $websetting['linkedin_url'];
	echo '<li class="social-linkedin tooltips" data-original-title="LinkedIn" data-placement="top"> <a target="_blank" href="'.$linkedin_url.'"> LinkedIn </a> </li>';
}

if(!empty($websetting) && !empty($websetting['pinterest_url'])){
	$pinterest_url =  $websetting['pinterest_url'];
	echo ' <li class="social-pinterest tooltips" data-original-title="Pinterest" data-placement="top"> <a target="_blank" href="'.$pinterest_url.'"> Pinterest </a> </li>';
}
?>

             

             <!-- 

              

              

              

             

              <li class="social-instagram tooltips" data-original-title="Instagram" data-placement="top"> <a target="_blank" href="https://instagram.com/"> Instagram </a> </li>-->

            

            </ul>

          </div>
         <div class="clear"></div>
		 
		  <?php if($this->config->item('multi-currency')){?>
              <form action="<?=base_url('home/currencies');?>" id="currencies-switch" method="post">
                <?php echo currency_droupdown(); ?>
              </form>
           <?php }?>
           
          <div class="clear"></div><br/>   
            <!--<a class="footer_logo" href="javascript:void(0);">-->
<img src="<?=base_url('assets/images/otriga-payment-methods.png')?>" alt="Otriga">
<!--</a>-->


      </div>

    </div>

  </div>
   

  <div class="footer-copyright">

    <div class="container">

      <div class="row"> 
      <?php
       if(!empty($websetting)){
	$site_copyright =  $websetting['site_copyright'];
}
			 
			 ?>

         <?=$site_copyright;?>
      </div>

    </div>

  </div>

</footer>


<a id="scroll-top" href="#"><i class="fa fa-angle-up"></i></a> 

<style>
.footer-copyright .container p {
    color: white;
}
#scroll-top .fa {
    margin-right: 0 !important;
}


#panel_tab_example3 .caret, #findprofessional_tabs .caret, #searchForm .caret {
  border-color: #000 transparent -moz-use-text-color;
  border-style: solid solid dotted;
  border-width: 4px 4px 0;
  display: block;
  float: right;
  height: 0;
  margin-left: 2px;
  margin-top: -10px;
  width: 0;
}
@media screen and (-webkit-min-device-pixel-ratio:0) {

.caret {  margin-top: 10px !important;}

}




</style>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-69119073-1', 'auto');
  ga('send', 'pageview');

</script>

<!-- end: FOOTER --> 

