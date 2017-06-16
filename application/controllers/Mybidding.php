<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mybidding extends CI_Controller {
	var $userID;
	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->library(array('grocery_CRUD', 'ajax_grocery_crud'));
		$this->load->model(array('common', 'property_model'));
		$this->load->helper('common_admin');		 		
		$this->language =  language_load();
		// check user login			 
		$this->userID = checkUserLogin();
		//checkUserAccessPermission(); 
	}
	public function index(){		
		$crud = new grocery_CRUD();
		$crud->unset_print(); 
		$crud->unset_export();
		$crud->set_subject('Property Auction');
		$crud->set_table('user_set_auction_price');
		$crud->where('user_set_auction_price.user_id', $this->userID);
		$crud->set_relation('property_id', 'property', 'property_name');	
		$crud->columns('property_id', 'price', 'createdDate','Action');		
		$crud->display_as('property_id', 'Property Name')->display_as('price', 'Bidding Price');
		$crud->unset_add();
		$crud->unset_edit();
		$crud->unset_read();
		$crud->unset_delete();
		$crud->unset_bulk_publish();
		$crud->unset_bulk_unpublish();
		$crud->unset_bulk_delete();
		$crud->callback_column('Action', array($this,'_property_link'));
		$crud->callback_column('price',array($this,'_callback_price'));
		$output = $crud->render();
		$page_title = array('page_title'=>'My Auction Bid');
		$outputData = array_merge((array)$output, $page_title);
		$this->_example_output($outputData);		
	}
	
	function _property_link($primary_key , $row){ 
	  $id     = safe_b64encode($row->property_id);
	  return '<a class="edit_button" title="View Property Auction" href="'.base_url('property/auction/'.$id).'"><button class="btn btn-info" type="button"><i class="clip-expand"></i></button></a>';
	}
	
	public function _callback_price($value, $row){ 
	   $results = attachCurrencySymbol(convert_currency($row->price));
       return $results;
    }
	
	public function _example_output($output = null){

		$this->load->view('my_account/setting',$output);

	}

}