<?php 
$this->load->view('common/header');
$this->load->view('common/top_header'); 
?>

<!-- start: MAIN CONTAINER -->

<div class="main-container inner_maincontainer"> 
  
  <!--- start: FIND AGENT---->
  
  <section class="wrapper wrapper-grey padding50 findagentwrap">
    <div class="container_inn">
      <div class="row">
        <div class="threepeople"> <img src="<?php echo base_url();?>assets/images/findagent-3people.png" alt=""> </div>
        <h3>FIND YOUR AGENCIES</h3>
        <h2>To get started, enter your location or search for a specific agencies by name.</h2>
        
        <!--- start: TOP HEAD INNERPAGE SEARCHING BAR---->
        
        <section class="wrapper wrapper-grey padding50 tophead_searching">
          <div class="container_inn">
            <div class="row">
              <div class="col-md-12">
                <form id="contactForm" novalidate action="<?php echo base_url();?>agency/listing" method="post">
                  <div class="row">
                    <div class="form-group">
                      <div class="col-md-7">
                        <input type="text" value="" maxlength="100" class="form-control head_searchinp" name="location" placeholder="Eg. London UK">
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <input type="text" value="" data-msg-required="Agent name" maxlength="100" class="form-control" name="name"  placeholder="Agencies Name">
                        </div>
                      </div>
                     <!-- <div class="col-md-2">
                        <div class="form-group">
                          <select id="" class="form-control search-select">
                            <option value="">Agent Type</option>
                            <option value="AL">1</option>
                            <option value="AK">2</option>
                            <option value="AZ">3</option>
                            <option value="AR">4</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <select id="" class="form-control search-select">
                            <option value="">Agent Specialities</option>
                            <option value="AL">1</option>
                            <option value="AK">2</option>
                            <option value="AZ">3</option>
                            <option value="AR">4</option>
                          </select>
                        </div>
                      </div>-->
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

<?php $this->load->view('common/footer_content');
$this->load->view('common/footer'); ?>

<style>
body{
	padding-top:0px !important;
}
.navbar-transparent{
	border-bottom:none !important; 
}
</style>
<?php $this->load->view('common/footer_end');?>
