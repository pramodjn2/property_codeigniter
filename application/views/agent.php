<?php 
$this->load->view('common/header');
$this->load->view('common/top_header');
?>

<!-- start: MAIN CONTAINER -->

<div class="main-container"> 
  
  <!--- start: FIND AGENT---->
  
  <section class="wrapper wrapper-grey padding50 findagentwrap">
    <div class="container_inn">
      <div class="row">
        <div class="threepeople"> <img src="<?php echo base_url();?>assets/images/findagent-3people.png" alt=""> </div>
        <h3>FIND YOUR AGENT</h3>
        <h2>To get started, enter your location or search for a specific agent by name.</h2>
        
        <!--- start: TOP HEAD INNERPAGE SEARCHING BAR---->
        
        <section class="wrapper wrapper-grey padding50 tophead_searching">
          <div class="container_inn">
            <div class="row">
              <div class="col-md-12">
                <form id="contactForm" novalidate action="<?php echo base_url();?>agent/listing" method="post">
                  <div class="row">
                    <div class="form-group">
                      <div class="col-md-5">
                        <input type="text" value="" maxlength="100" class="form-control head_searchinp" name="location" placeholder="Eg. London UK">
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <input type="text" value="" data-msg-required="Agent name" maxlength="100" class="form-control" name="name"  placeholder="Agent Name">
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <select name="type" id="" class="form-control search-select">
                            <option value="">Agent Type</option>
                             <?php echo getagenttype();?>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <select name="specialities" id="" class="form-control search-select">
                            <option value="">Agent Specialities</option>
							
							<?php $type=selectData('agent_specialites','');
                                  foreach($type as $special){
                            ?>
                    <option value="<?php echo $special["agent_specialites_id"];?>"><?php echo $special["name"];?></option>
                    <?php }?>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-1">
                        <button class="btn btn-primary btn-squared red_button" type="submit"> SEARCH </button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </section>
        
        <!--- end: TOP HEAD INNERPAGE SEARCHING BAR----> 
        
      </div>
    </div>
  </section>
  
  <!--- end: FIND AGEN----> 
  
  <!--- start: SUBSCRIPTION AREA---->
  
  <?php $this->load->view('agent/subscribe_agent'); ?>
  
  <!--- end: SUBSCRIPTION AREA ----> 
  
</div>

<!-- end: MAIN CONTAINER --> 

<?php $this->load->view('common/footer_content');?>
<?php $this->load->view('common/footer'); ?>
<?php $this->load->view('common/footer_end');?>
