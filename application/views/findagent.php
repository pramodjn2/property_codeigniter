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
                <form id="contactForm" novalidate action="<?php echo base_url();?>findagent/agentlist" method="post">
                  <div class="row">
                    <div class="form-group">
                      <div class="col-md-4">
                        <input type="text" value="" maxlength="100" class="form-control head_searchinp" name="address" placeholder="Eg. London UK">
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <input type="text" value="" data-msg-required="Agent name" maxlength="100" class="form-control" name="agentname"  placeholder="Agent Name">
                        </div>
                      </div>
                      <div class="col-md-2">
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
  
  <section class="wrapper subscribe_wrapper subscribe_innerpg">
    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <h2 class="center prophome_heading"> <strong>Subscribe for the newsletter</strong><br>
            <small>Get updates from our latest properties</small> </h2>
        <form class="form-horizontal" role="form" method="post">
            <div class="form-group">
              <div class="col-sm-9">
                <input type="text" class="form-control input-sm input_subscribe-lg" id="subscribe" placeholder="Your email">
              </div>
              <div class="col-sm-3">
                <button class="btn btn-lg btn-squared red_button subscribebtn" type="button" onclick="subscribe_email();"> SUBSCRIBE </button>
              </div>
            </div>
          </form>
          <div id="msg_success" style="display:none;"></div>
        </div>
      </div>
    </div>
  </section>
  
  <!--- end: SUBSCRIPTION AREA ----> 
  
</div>

<!-- end: MAIN CONTAINER --> 

<?php $this->load->view('common/footer_content');?>
<?php $this->load->view('common/footer'); ?>
<?php $this->load->view('common/footer_end');?>
