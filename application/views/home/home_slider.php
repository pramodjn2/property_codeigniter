<?php

$slider=select_query('manage_home_slider',"where status='Active'");



?>


<section class="fullwidthbanner-container">

    <div class="fullwidthabnner">

      <ul>

        <!-- start: FIRST SLIDE -->
         
		 
		<?php
		if(!empty($slider)){
		foreach($slider as $slideimg){
		
		?> 
		 
		 
        <li data-transition="fade" data-slotamount="1" data-masterspeed="1000" > 

        <img src="<?php echo base_url('applicationMediaFiles/homeslider/'.$slideimg["home_slider_image"]);?>"  style="background-color:rgb(246, 246, 246)" alt="<?=$slideimg['home_slider_title']?>"  data-bgfit="cover" data-bgposition="left bottom" data-bgrepeat="no-repeat"> </li>
		
		<?php
		}}
		?>

      </ul>

    </div>

  </section>

  

  <section class="wrapper blackstrip">
    <div class="container">
      <div class="row">
		
       
      </div>
    </div>
  </section>