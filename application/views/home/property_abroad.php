
				<section class="wrapper wrapper-grey padding50 home_proplisting"> 
  
  <!-- start: WORKS CONTAINER -->
  
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h2 class="center prophome_heading"> Properties for Sale and Rent<br>
          <!--<small>Be the ﬁrst to see new properties</small>--> </h2>
        <div class="grid-container animate-group" data-animation-interval="100">
          <div class="row">
           <?php 
			
			if(!empty($abroad_result)){
				?>
                 <?php
				
				
			         $i=1;
					 $j=1;
			        foreach($abroad_result as $abresult){ 
					
	
	
					$class='col-lg-4 col-md-4 col-sm-4 col-xs-12';
					$folder = '350325/';
					if(($i==1)||($i==7)){
					 $class='col-lg-8 col-md-8 col-sm-8 col-xs-12';
					 $folder = '730326/';
					}
					
					$filename = config_item('base_url').'applicationMediaFiles/abroadimage/'.$folder.$abresult["abroad_image"];
					if(@getimagesize($filename)){
					
					
			  ?>
            <?php
			 
			  ?>
            <div class="<?php echo $class;?>">
              <div class="grid-item animate"> 
             <a href="javascript:void(0);" onclick="abroad_listing('<?=$abresult["id"]?>');">
                <div class="grid-content cont_name"> <?php echo $abresult["abroad_slogan_name"];?> </div>
                <div class="grid-content totalprop">&nbsp;</div>
                <div class="grid-image"> 
      
 <form action="<?=base_url('property/listing')?>" method="post" id="abroad_id_<?=$abresult["id"]?>" > <input type="hidden" value="<?=$abresult["abroad_slogan_name"]?>" name="location" >
   
  <input type="hidden" value="<?=$abresult["city"]?>" name="city" >
    <input type="hidden" value="<?=$abresult["region"]?>" name="regions">
    <input type="hidden" value="<?=$abresult["country"]?>" name="country_code">
    
    </form>
   
    
                  
                  <!--<a href="<?php echo base_url('property/listing'); ?>">--> 
                  
                  <img src="<?php echo config_item('base_url');?>applicationMediaFiles/abroadimage/<?php echo $folder.$abresult["abroad_image"];?>" class="img-responsive"/> 
                  
                  <!--</a>  --> 
                  
                </div>
                </a> </div>
            </div>
            <?php $j++;
			$i++;
			}else{
			    updateAbroadpropertystatus($abresult["id"]);
			 }
			 }
			
			
			?>
            <?php }?>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  
 
</section>

