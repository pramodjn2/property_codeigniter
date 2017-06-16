<section class="searchform_container">
  <h2>FEEL SPECIAL </h2>
  <h3>Find your ideal property and get free advice from your local experts</h3>
  <?php  $price_list = priceList(); 

         $numberList = numberList(); 

		 $priceListRent =  priceListRent();

         $getagenttype = getagenttype();

  ?>
    <?php 
  
  $footData = gethowitworksLink();
			   if(!empty($footData)):
			 	foreach($footData as $linkData2):
				  if($linkData2['cat_id'] ==  2):
				  		$link_url_2 = "href='#'";
						if(!empty($linkData2['link_type'])):
							if($linkData2['link_type'] == "external_url"):
								$link_url_2 = 'href="'.$linkData2['link_url'].'" target="_blank"';
							elseif($linkData2['link_type'] == "new_tab"):
							$seo_url = seo_friendly_urls($linkData2['page_name'],'',$linkData2['static_pages_id']);
							    $link_url_2 = 'href="'.base_url($linkData2['link_url'].'/content/'.$seo_url).'" target="_blank"';
							elseif($linkData2['link_type'] == "parent_url"):
						        $link_url_2 = 'href="'.base_url($linkData2['link_url']).'" target="_parent"';	
							endif;	
						endif;
				   echo  '<a '.$link_url_2.' class="btn btn-default btn-xs howitwork_btn">'.strtoupper(str_replace('_','&nbsp;',$linkData2['page_name'])).'</a>';
				  endif; 
				endforeach; 
			   endif;	  
			 ?> 
  
  <!--<button class="btn btn-default btn-xs howitwork_btn how_it_work_toggle" type="button"> HOW IT WORKS </button>--> 
  
  <!--<a href="<?=base_url('home/howItWork');?>" class="btn btn-default btn-xs howitwork_btn"> HOW IT WORKS </a>-->
  <div class="clear"></div>
  <div class="searching_tabholder">
    <div class="tabbable panel-tabs">
      <ul class="nav nav-tabs nav-center">
        <li class="active"><a data-toggle="tab" href="#panel_tab_example1">For sale</a></li>
        <li> <a data-toggle="tab" href="#panel_tab_example2">To rent</a></li>
        <li> <a data-toggle="tab" href="#panel_tab_example3">Find an expert</a></li>
      </ul>
      <div class="clear"></div>
      <div class="tab-content">
        <div id="panel_tab_example1" class="tab-pane active">
          <form id="for_sale_from" novalidate action="<?php echo base_url('property/listing');?>" method="get" type="get" onsubmit="return getAddress('for_sale_from');" >
            <div class="row">
              <div class="form-group">
                <div class="col-md-9 col-sm-9 padding_right">
                  <input type="text" value="" class="form-control head_searchinp autocomplete" name="location" placeholder="Location " id="autocomplete_sale">
                  <span style="display:none" class="help-block error">This fill location.</span> </div>
                <div class="col-md-3 col-sm-3 text-right">
                  <select name="near_by" class="form-control search-select">
                    <option value="" selected="selected">Nearby</option>
                    <option value="" >Any</option>
                    <?php $near=selectData('manage_nearby',"where status='Active'");

						foreach($near as $nearby){

					?>
                    <option value="<?php echo $nearby["nearby_id"];?>"><?php echo $nearby["nearby_name"];?></option>
                    <?php }?>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-2 col-sm-2 padding_right">
                <div class="form-group">
                  <select id="min_price" name="min_price" class="form-control search-select">
                    <option value="10000" >Min price</option>
                    <option value="" >Any</option>
                    <?=$price_list;?>
                  </select>
                </div>
              </div>
              <div class="col-md-2 col-sm-2 padding_right">
                <div class="form-group">
                  <select id="max_price" name="max_price" class="form-control search-select">
                    <option value="6500000" selected="selected">Max price</option>
                    <option value="" >Any</option>
                    <?=$price_list;?>
                  </select>
                </div>
              </div>
              <div class="col-md-3 col-sm-3 padding_right">
                <div class="form-group">
                  <select class="form-control" name="bed_room">
                    <option value="" selected="selected">Bedrooms</option>
                    <option value="" >Studio</option>
                    <?=$numberList;?>
                  </select>
                </div>
              </div>
              <div class="col-md-3 col-sm-3 padding_right">
                <div class="form-group">
                  <select id="property_type" name="property_type" class="form-control search-select">
                    <option value="" selected="selected">Property type</option>
                    <option value="" >Any</option>
                    <?php $type=selectData('property_types',"where status='Active'");



                       foreach($type as $protype){



                       ?>
                    <option value="<?php echo $protype["property_types_id"];?>"><?php echo $protype["typeName"];?></option>
                    <?php }?>
                  </select>
                </div>
              </div>
              <div class="col-md-2 col-sm-2">
                <div class="form-group">
                  <button class="btn btn-primary btn-squared red_button" type="submit"> SEARCH </button>
                </div>
              </div>
            </div>
            <input type="hidden" name="proprty_category" value="Sale"/>            
            <input type="hidden" id="city" name="city" class="locality" value=""/>
            <input type="hidden" id="regions" name="regions" class="administrative_area_level_1" value=""/>
            <input type="hidden" id="country" name="country_code" class="country" value=""/>
            <input type="hidden" id="postal_code" name="postal_code" class="postal_code" value=""/>
            <!--<input type="hidden" value="" id="latitude" name="latitude">
            <input type="hidden" value="" id="longitude" name="longitude">-->
           </form>
        </div>
        <div id="panel_tab_example2" class="tab-pane">
          <form id="for_rent_from" novalidate action="<?php echo base_url('property/listing');?>" method="get" onsubmit="return getAddress('for_rent_from');" >
            <div class="row">
              <div class="form-group">
                <div class="col-md-9 col-sm-9 padding_right">
                  <input type="text" value="" class="form-control head_searchinp autocomplete" name="location" placeholder="Location " id="autocomplete_rent">
                  <span style="display:none" class="help-block error">This fill location.</span> </div>
                <div class="col-md-3 col-sm-3 text-right">
                  <select name="near_by" class="form-control search-select">
                    <option value="" selected="selected">Nearby</option>
                    <?php $near=selectData('manage_nearby', "where status='Active'");

						foreach($near as $nearby){

					?>
                    <option value="<?php echo $nearby["nearby_id"];?>"><?php echo $nearby["nearby_name"];?></option>
                    <?php }?>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-2 col-sm-2 padding_right">
                <div class="form-group">
                  <select id="min_price" name="min_price" class="form-control search-select">
                    <option value="100">Min price</option>
                    <option value="">Any</option>
                    <?=$priceListRent;?>
                  </select>
                </div>
              </div>
              <div class="col-md-2 col-sm-2 padding_right">
                <div class="form-group">
                  <select id="max_price" name="max_price" class="form-control search-select">
                    <option value="39000" selected="selected">Max price</option>
                    <option value="">Any</option>
                    <?=$priceListRent;?>
                  </select>
                </div>
              </div>
              <div class="col-md-3 col-sm-3 padding_right">
                <div class="form-group">
                  <select class="form-control" name="bed_room">
                    <option value="" selected="selected">Bedrooms</option>
                    <option value="">Studio</option>
                    <?php  echo numberList(); ?>
                  </select>
                </div>
              </div>
              <div class="col-md-3 col-sm-3 padding_right">
                <div class="form-group">
                  <select id="property_type" name="property_type" class="form-control search-select">
                    <option value="" selected="selected">Property type</option>
                    <option value="" >Any</option>
                    <?php $type=selectData('property_types',"where status='Active'");

                       foreach($type as $protype){

                       ?>
                    <option value="<?php echo $protype["property_types_id"];?>"><?php echo $protype["typeName"];?></option>
                    <?php }?>
                  </select>
                </div>
              </div>
              <div class="col-md-2 col-sm-2">
                <div class="form-group">
                  <button class="btn btn-primary btn-squared red_button" type="submit"> SEARCH </button>
                </div>
              </div>
            </div>
            <input type="hidden" name="proprty_category" value="Rent"/>
    <input type="hidden" id="city" name="city" class="locality" value=""/>
    <input type="hidden" id="regions" name="regions" class="administrative_area_level_1" value=""/>
    <input type="hidden" id="country" name="country_code" class="country" value=""/>
    <input type="hidden" id="postal_code" name="postal_code" class="postal_code" value=""/>

          </form>
        </div>
        <div id="panel_tab_example3" class="tab-pane">
          <form id="for_professional_from" novalidate action="<?php echo base_url('professional/listing');?>" method="get" type="get">
            <div class="row">
            
            <div class="col-md-4 col-sm-4 col-xs-6 padding_right">
                <div class="form-group">
                  <select name="profession" class="form-control" onchange="getSelectedSpecialist(this.value);">
                    <option value="">Type of expert</option>
                    <?php

                        $table = 'user_group';

                        $oData = array('value'=>'group_id', 'option'=>'groupName');

                        $select = set_value('profession');

                        echo get_type_of_expert($select);

                     ?>
                  </select>
                </div>
              </div>
              
              
              
              <div class="col-md-4 col-sm-4 col-xs-6 padding_right">
                <div class="form-group multiSelectWidth">
                  <select title="Professional Specialities" disabled="disabled" multiple="multiple" class="form-control" data-role="multiselect" name="specialities" id="specialitiesId">
                  </select>
                  <input type="hidden" name="specialitiesIds" id="hiddenSpecialitiesId" value=""  />
                </div>
              </div>
              <div class="col-md-4 col-sm-4 col-xs-6">
                <div class="form-group">
                  <select name="pro_type" class="form-control search-select">
                    <option value="">Team and company</option>
                    <?=$getagenttype;?>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-5 col-sm-5 col-xs-5 padding_right">
                <div class="form-group">
                  <input type="text" class="form-control autocomplete" name="location" placeholder="Location " value="" id="locationprofession">
                  <span style="display:none" class="help-block error">This fill location.</span> </div>
              </div>
              <div class="col-md-5 col-sm-5 col-xs-5 padding_right">
                <div class="form-group">
                  <input type="text" class="form-control" name="name" placeholder="Name" value="">
                </div>
              </div>
              <div class="col-md-2 col-sm-2 col-xs-2 text-right">
                <button class="btn btn-primary btn-squared red_button" type="button" onclick="searchListing('for_professional_from');"> SEARCH </button>
                
                
    <input type="hidden" id="city" name="city" class="locality" value=""/>
    <input type="hidden" id="regions" name="regions" class="administrative_area_level_1" value=""/>
    <input type="hidden" id="country" name="country_code" class="country" value=""/>
    <input type="hidden" id="postal_code" name="postal_code" class="postal_code" value=""/>
    
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="responsive_searchbar">
    <form id="" novalidate action="#" type="post">
      <div class="">
        <div class="form-group">
          <div class="col-md-12">
            <input type="text" value="" data-msg-required="Please enter the subject." maxlength="100" class="form-control head_searchinp" name="subject"  placeholder="Location "  data-toggle="modal" data-target="#myModal_ressearch">
          </div>
        </div>
      </div>
    </form>
  </div>
  
  <!-- start: Modal REsponsive search -->
  
  <div class="modal fade" id="myModal_ressearch" role="dialog">
    <div class="modal-dialog"> 
      
      <!-- start: Modal content-->
      
      <div class="modal-content">
        <div class="modal-header noborder text-left"> <!--Search property &amp; professional-->
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <ul class="nav nav-tabs nav-center">
            <li class="active"><a data-toggle="tab" href="#forsale_tabs">For sale</a></li>
            <li><a data-toggle="tab" href="#torent_tabs">To rent</a></li>
            <li><a data-toggle="tab" href="#findprofessional_tabs">Find an expert</a></li>
          </ul>
          <div class="tab-content">
            <div id="forsale_tabs" class="tab-pane active">
              <form id="sale_from_responsive" action="<?php echo base_url('property/listing');?>" method="get" type="get" onsubmit="return getAddress('sale_from_responsive');">
                <div class="row">
                  <div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <input type="text" value="" class="form-control autocomplete" name="location"placeholder="Location " id="responsivesale">
                      <span style="display:none" class="help-block error">This fill location.</span> </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-2 col-sm-2 col-xs-6">
                    <div class="form-group">
                      <select id="min_price" name="min_price" class="form-control search-select">
                        <option value="10000" >Min price</option>
                        <option value="">Any</option>
                        <?=$price_list;?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-2 col-sm-2 col-xs-6">
                    <div class="form-group">
                      <select id="max_price" name="max_price" class="form-control search-select">
                        <option value="6500000" selected="selected">Max price</option>
                        <option value="" >Any</option>
                        <?=$price_list;?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3 col-sm-3 col-xs-6">
                    <div class="form-group">
                      <select class="form-control" name="bed_room">
                        <option value="" selected="selected">Bedrooms</option>
                        <option value="" >Studio</option>
                        <?=$numberList;?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3 col-sm-3 col-xs-6">
                    <div class="form-group">
                      <select id="property_type" name="property_type" class="form-control search-select">
                        <option value="" selected="selected">Property type</option>
                        <option value="">Any</option>
                        <?php $type=selectData('property_types',"where status='Active'");

                               foreach($type as $protype){

                               ?>
                        <option value="<?php echo $protype["property_types_id"];?>"><?php echo $protype["typeName"];?></option>
                        <?php }?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3 col-sm-3 col-xs-6">
                    <select name="near_by" class="form-control search-select">
                      <option value="" selected="selected">Nearby</option>
                      <option value="" selected="selected">Any</option>
                      <?php $near=selectData('manage_nearby',"where status='Active'");

                                foreach($near as $nearby){

                       ?>
                      <option value="<?php echo $nearby["nearby_id"];?>"><?php echo $nearby["nearby_name"];?></option>
                      <?php }?>
                    </select>
                  </div>
                </div>
                <br />
                <div class="row">
                  <div class="col-md-8 col-md-offset-4">
                    <button class="btn btn-primary btn-squared red_button" type="submit"> SEARCH FOR SALE </button>
                  </div>
                </div>
                <input type="hidden" name="proprty_category" value="Sale"/>
                
               <input type="hidden" id="city" name="city" class="locality" value=""/>
    <input type="hidden" id="regions" name="regions" class="administrative_area_level_1" value=""/>
    <input type="hidden" id="country" name="country_code" class="country" value=""/>
    <input type="hidden" id="postal_code" name="postal_code" class="postal_code" value=""/>
    
              </form>
            </div>
            <div id="torent_tabs" class="tab-pane">
              <form id="rent_from_responsive" novalidate action="<?php echo base_url('property/listing');?>" method="get" onsubmit="return getAddress('rent_from_responsive');" >
                <div class="row">
                  <div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <input type="text" value="" class="form-control autocomplete" name="location" placeholder="Location " id="responsiverent">
                      <span style="display:none" class="help-block error">This fill location.</span> </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-2 col-sm-2 col-xs-6">
                    <div class="form-group">
                      <select id="min_price" name="min_price" class="form-control search-select">
                        <option value="100" >Min price</option>
                        <option value="">Any</option>
                        <?php echo priceListRent(); ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-2 col-sm-2 col-xs-6">
                    <div class="form-group">
                      <select id="max_price" name="max_price" class="form-control search-select">
                        <option value="39000" selected="selected">Max price</option>
                        <option value="" >Any</option>
                        <?=$priceListRent;?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3 col-sm-3 col-xs-6">
                    <div class="form-group">
                      <select class="form-control" name="bed_room">
                        <option value="" selected="selected">Bedrooms</option>
                        <option value="" >Studio</option>
                        <?=$numberList;?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3 col-sm-6 col-xs-6">
                    <div class="form-group">
                      <select id="property_type" name="property_type" class="form-control search-select">
                        <option value="" selected="selected">Property type</option>
                        <option value="">Any</option>
                        <?php $type=selectData('property_types',"where status='Active'");

                                foreach($type as $protype){

                        ?>
                        <option value="<?php echo $protype["property_types_id"];?>"><?php echo $protype["typeName"];?></option>
                        <?php }?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3 col-sm-6 col-xs-6">
                    <select name="near_by" class="form-control search-select">
                      <option value="" selected="selected">Nearby</option>
                      <option value="" selected="selected">Any</option>
                      <?php $near=selectData('manage_nearby',"where status='Active'");

							foreach($near as $nearby){

						?>
                      <option value="<?php echo $nearby["nearby_id"];?>"><?php echo $nearby["nearby_name"];?></option>
                      <?php }?>
                    </select>
                  </div>
                </div>
                <div class="row"></div>
                <br />
                <div class="row">
                  <div class="form-group">
                    <div class="col-md-8 col-md-offset-4">
                      <button class="btn btn-primary btn-squared red_button" type="submit"> SEARCH FOR RENT </button>
                    </div>
                  </div>
                </div>
                <input type="hidden" name="proprty_category" value="Rent"/>
                
                <input type="hidden" id="city" name="city" class="locality" value=""/>
                <input type="hidden" id="regions" name="regions" class="administrative_area_level_1" value=""/>
                <input type="hidden" id="country" name="country_code" class="country" value=""/>
                <input type="hidden" id="postal_code" name="postal_code" class="postal_code" value=""/>
    
              </form>
            </div>
            <div id="findprofessional_tabs" class="tab-pane">
              <form id="professional_from_responsive" novalidate action="<?php echo base_url('professional/listing');?>" method="get" type="get"  >
                <div class="row">
                  <div class="col-md-6 col-sm-6">
                    <div class="form-group">
                      <select name="profession" class="form-control" onchange="getSelectedSpecialist(this.value);">
                        <option value="">Type of expert</option>
                        <?php

                            $table = 'user_group';

                            $oData = array('value'=>'group_id', 'option'=>'groupName');

                            $select = set_value('profession');

                          //  echo $this->Common->generateSelectBox($table, $oData, $select);
						   echo get_type_of_expert($select);

                         ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6 col-sm-6">
                    <div class="form-group multiSelectWidth">
                      <select title="Professional Specialities" disabled="disabled" multiple="multiple" class="form-control" data-role="multiselect" name="specialities" id="specialitiesIdrespo">
                      </select>
                      <input type="hidden" name="specialitiesIds" id="hiddenSpecialitiesIdrespo" value=""/>
                    </div>
                  </div>
                  <div class="col-md-6 col-sm-6">
                    <div class="form-group">
                      <select name="pro_type" class="form-control search-select">
                        <option value="">Team and company</option>
                        <?=$getagenttype;?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6 col-sm-6">
                    <div class="form-group">
                      <input type="text" class="form-control autocomplete" name="location" placeholder="Location " value="" id="responsiveprofession">
                      <span style="display:none" class="help-block error">This fill location.</span> </div>
                  </div>
                  <div class="col-md-6 col-sm-6">
                    <div class="form-group">
                      <input type="text" class="form-control" name="name" placeholder="Name" value="">
                    </div>
                  </div>
                  <div class="col-md-6 col-sm-6">
                    <button class="btn btn-primary btn-squared red_button" type="button" onclick="searchListingresponsive('professional_from_responsive');"> Search Professional</button>
                    
                    <input type="hidden" id="city" name="city" class="locality" value=""/>
    <input type="hidden" id="regions" name="regions" class="administrative_area_level_1" value=""/>
    <input type="hidden" id="country" name="country_code" class="country" value=""/>
    <input type="hidden" id="postal_code" name="postal_code" class="postal_code" value=""/>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<style>

.multiSelectWidth .btn-group, .multiSelectWidth .btn-group button{

	width:100% !important;

	background-color: transparent !important;

    border-color: #666 !important;

    border-radius: 2px;

    box-shadow: none;

	color:#919191;

} 

</style>
<script>

function searchListing(){

	var spec=$('#specialitiesId').val();

	$('#hiddenSpecialitiesId').val(spec);

	$('#searchForm').submit();

}

function getSelectedSpecialist(id){

$('.multiselect-selected-text').html('Please wait....');

var remoteURL = base_url+"ajax/getSpecialities/"+id;	

    $.ajax({url: remoteURL}).done(function(data){

		if(data != ''){

			var options = eval(data);

		}

		if(options.length > 0){

			$("select[data-role=multiselect]").prop('disabled', false);

			$("select[data-role=multiselect]").multiselect('dataprovider', options);

		}

    });

}

</script>