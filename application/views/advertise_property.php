<?php 

$this->load->view('common/header');

$this->load->view('common/top_header');



$style='style="display:block;"';

$styleadvert='style="display:none;"';



if(!empty($group_id)){

$style='style="display:none;"';

$styleadvert='style="display:block;"';

}



?>

<div class="main-container">

  <section class="wrapper wrapper-grey padding50 freevaluation advertisepg">

    <div class="container_inn">

      <div class="row">

        <div class="col-md-12">

          <h3 class="center">Advertise your property or business</h3>
          
         

          <h2 class="center"> Get in front of buyers and sellers on the premier personal property network. </h2>

          <hr>

        </div>

      </div>

      <div class="row">

        <section class="wrapper" style="min-height:200px; background-image: url('<?=base_url();?>assets/images/advertise.jpg')" data-stellar-background-ratio="0.8" data-stellar-vertical-offset="-750">

          <div class="container">

            <div class="row animate-group">

              <div class="col-sm-4 pull-right">

                <div class="icon-box animate" data-animation-options='{"animation":"flipInY", "duration":"600"}' <?php echo $style;?>> <i class="icon-box-icon fa fa-pencil"></i>

                  <h3 class="icon-box-title">Create an account</h3>

                  <div class="PD_whiteblock contactagent">

                  <div id="message"></div>

                    <form action="<?=base_url('home/advertise');?>" type="post" method="post" id="advertise_from">

                    

                    

                    <div class="form-group">

                        <div class="col-md-12 no-padding">

                         <select required="required" id="group_id" name="group_id" class="form-control">

                         <option selected="selected" disabled="true" value="">I am:</option>                         

                         <?php

                         	$table = 'user_group';

							$oData = array('value' => 'group_id', 'option' => 'groupName');

							$selected = set_value('group_id');

							echo $this->Common->generateSelectBox($table, $oData, $selected);

						 ?>

                         </select>

                        </div>

                      </div>

                      

                      

                      

                      <div class="form-group">

                        <div class="col-md-12 no-padding">

                          <input type="text" value="" maxlength="40" class="form-control required " name="name" id="name" placeholder="Name">

                        </div>

                      </div>

                      <div class="form-group">

                        <div class="col-md-12 no-padding">

                          <input type="text" value="" maxlength="15" class="form-control required alphanumeric" name="phone_number" id="phone_number" placeholder="Telephone">

                        </div>

                      </div>

                      <div class="form-group">

                        <div class="col-md-12 no-padding">

                          <input type="text" value="" maxlength="50" class="form-control required email" name="email" id="email" placeholder="Email">

                        </div>

                      </div>

                      <div class="form-group">

                        <div class="col-md-12 no-padding">

                         <input type="password" value="" minlength="6" maxlength="20" class="form-control required" name="password" id="password" placeholder="Password">

                        </div>

                      </div>

                      <button type="submit" class="btn btn-red btn-lg"> NEXT </button>

                    </form>

                    

                    

                    

                    

                    

                    <div class="clear"></div>

                     </div>

                </div>

				<div class="icon-box animate" data-animation-options='{"animation":"flipInY", "duration":"600"}' <?php echo $styleadvert;?>> <i class="icon-box-icon fa fa-pencil"></i>

                  <h3 class="icon-box-title">Advertise Property on Otriga</h3>

                  <div class="advertise_search">                  

                      <div class="form-group">

                        <div class="col-md-12 no-padding">

						  

						  <?php if(!empty($results)){

						        foreach($results as $adresult){

						  ?>

						  

                          <i class="<?php echo $adresult['icon_class'];?>"></i> <a href="<?php echo base_url($adresult['menu_key']);?>" title="<?php echo $adresult['menu_title']?>"><?php echo $adresult['menu_title']?></a><br/><br/>

						  

						  <?php }}else{

							  echo '<div class="alert alert-danger"><i class="fa fa-times-circle"></i> No listing found. <a href="'.base_url().'">Click here</a></div>';

							  }?>

						  

                        </div>

                      </div>

                      



                    

                    

                    

                    

                    

                    <div class="clear"></div>

                     </div>

                </div>

              </div>

            </div>

          </div>

        </section>

        <!--<section class="wrapper"> 

         

          <div class="container">

            <div class="row">

              <div class="col-sm-6"> <img src="<?=base_url();?>assets/images/iphoneIpad-495x400.png" class="img-responsive animate-if-visible" data-animation-options='{"animation":"tada", "duration":"600"}'> </div>

              <div class="col-sm-6">

                <h2>Contact and Book</h2>

                <hr class="fade-right">

                <p> Lid est laborum dolo rumes fugats untras. Etha rums ser quidem rerum facilis dolores nemis onis fugats vitaes nemo minima rerums unsers sadips amets. </p>

                <p> Ut enim ad minim veniam, quis nostrud Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci amets uns. </p>

                <p> Etharums ser quidem rerum facilis dolores nemis omnis fugats vitaes nemo minima rerums unsers sadips ameet quasi architecto beatae vitae dicta sunt explicabo. </p>

                <hr class="fade-right">

                <a class="btn btn-default  red_button" href="#"><i class="fa fa-info"></i> Learn more...</a> </div>

            </div>

          </div>

          

          

          

        </section>-->

        <!--<section class="wrapper wrapper-grey"> 

          

          

          

          <div class="container">

            <div class="row">

              <div class="col-sm-6">

                <h2>Stand Out Where It Matters</h2>

                <hr class="fade-right">

                <p> Lid est laborum dolo rumes fugats untras. Etha rums ser quidem rerum facilis dolores nemis onis fugats vitaes nemo minima rerums unsers sadips amets. </p>

                <p> Ut enim ad minim veniam, quis nostrud Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci amets uns. </p>

                <p> Etharums ser quidem rerum facilis dolores nemis omnis fugats vitaes nemo minima rerums unsers sadips ameet quasi architecto beatae vitae dicta sunt explicabo. </p>

                <hr class="fade-right">

                <a class="btn btn-default  red_button" href="#"><i class="fa fa-info"></i> Learn more...</a> </div>

              <div class="col-sm-6"> <img src="<?=base_url();?>assets/images/iphoneIpad-495x400_2.png" class="img-responsive animate-if-visible" data-animation-options='{"animation":"tada", "duration":"600"}'> </div>

            </div>

          </div>

          

          

          

        </section>-->

        <!--<section class="wrapper" style="min-height:400px; background-image: url('<?=base_url();?>assets/images/photodune-4043508-3d-modern-office-room-l.jpg')" data-stellar-background-ratio="0.8" data-stellar-vertical-offset="-750">

          <div class="container"> 

            

            

            <div class="row animate-group">

              <div class="col-sm-4">

                <div class="icon-box animate" data-animation-options='{"animation":"flipInY", "duration":"600"}'> <i class="icon-box-icon fa fa-users"></i>

                  <h3 class="icon-box-title">Set Yourself Apart</h3>

                  <div class="icon-box-content">

                    <p> Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. <br>

                      <a href="#"> Learn more </a> </p>

                  </div>

                </div>

              </div>

              <div class="col-sm-4">

                <div class="icon-box animate" data-animation-options='{"animation":"flipInY", "duration":"600"}'> <i class="icon-box-icon fa fa-briefcase"></i>

                  <h3 class="icon-box-title">Close More Business</h3>

                  <div class="icon-box-content">

                    <p> Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. <br>

                      <a href="#"> Learn more </a> </p>

                  </div>

                </div>

              </div>

              <div class="col-sm-4">

                <div class="icon-box animate" data-animation-options='{"animation":"flipInY", "duration":"600"}'> <i class="icon-box-icon fa fa-smile-o"></i>

                  <h3 class="icon-box-title">Success Stories</h3>

                  <div class="icon-box-content">

                    <p> Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. <br>

                      <a href="#"> Learn more </a> </p>

                  </div>

                </div>

              </div>

            </div>

          </div>

        </section>-->

      </div>

    </div>

  </section>

</div>

<?php $this->load->view('common/footer_content'); ?>

<?php $this->load->view('common/footer'); ?>

<script src="<?=base_url('assets/js/validation/jquery.validate.min.js');?>"></script> 

<script src="<?=base_url('assets/js/validation/jquery.form.min.js');?>"></script> 

<script src="<?=base_url('assets/js/validation/main.js');?>"></script> 

<script>

 $(document).ready(function(){

	setTimeout("saveForm('advertise_from');",400);

	setTimeout("saveForm('advertiseSuccess_from');",400);

});

</script>

<style type="text/css">

/*.error {

    clear: left;

    color: white;

}*/

.contactagent {

    background-color: #f1f1f1;

}

.advertisepg .help-block{text-align:left; }

</style>
<style>
.error{ color:#F00; 

border-color : #b94a48;

}
</style>

<?php $this->load->view('common/footer_end');?>

