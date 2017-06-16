<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mysavedresult extends CI_Controller { 
	
	var $userID;
	var $message;
	public function __construct() {
		
		parent::__construct();
		$this->load->helper(array('common_admin'));
		$this->load->library(array('file_upload','fileupload','grocery_CRUD', 'ajax_grocery_crud'));			 		
		//check user login and get user_id from session.
		$this->userID = checkUserLogin();
		checkUserAccessPermission();
	}	
	
	public function index(){		
		$crud = new grocery_CRUD();
		
		$crud->unset_print();
		$crud->unset_export();
		$crud->unset_add();		
		$crud->unset_edit();	
		$crud->unset_bulk_publish();
		$crud->unset_bulk_unpublish();
		
		$crud->unset_read();

        $crud->unset_edit();

		$crud->unset_delete();
		
		$crud->set_subject('Results');
		
		$crud->set_table('user_property_save_result');
		$crud->where('user_id', $this->userID);
	    $crud->columns('save_title','save_alert','action');	
		$crud->order_by('createDate','desc');	
		$crud->display_as('save_title','Title')->display_as('save_alert','Alert')->display_as('result_count','Result-Count');		
		//$crud->callback_column('result_count',array($this,'_callback_count'));
		
		//$crud->set_read_url_path(base_url('property/save_result/'));
		$crud->set_bulk_action_url(base_url('mysavedresult/bulk_action'));
		
		$crud->callback_column('action', array($this,'_custom_action'));
		
		//$crud->set_data_status_field_name('status');
		
		$output = $crud->render();		
		$page_title = array('page_title' => 'Manage Save Results');
		$outputData = array_merge((array) $output , $page_title);
		$this->_example_output($outputData);			
	}
	
	
	function _custom_action($primary_key , $row)
	{
		
	
						
	 return ' 
	 <a class="delete-row" title="Delete Results" href="'.base_url('mysavedresult/index/delete/'.$row->user_property_save_id).'">
                <button class="btn btn-danger" type="button">
					<i class="fa fa-trash-o"></i>
			     </button>
             </a>
			 <a class="edit_button" target="_blank" title="View Property" href="'.base_url('property/save_result/'.$row->user_property_save_id).'">
      			<button class="btn btn-info" type="button">
	       			<i class="clip-expand"></i>
	  			</button>
			 </a>';
	}

	
	
	
	
	public function _example_output($output = null){
		$this->load->view('my_account/setting',$output);
	}
	public function _callback_count($value, $row)
	{ 
		$results=unserialize($row->save_result);	   
		$results=count($results);	   
		return $results;
	}
	
	public function bulk_action(){
				
		$action = $this->input->post("action", TRUE);
		$table_name = $this->input->post("table_name", TRUE);
		$field_name = $this->input->post("field_name", TRUE);
		$primary_key = $this->input->post("primary_key", TRUE);
		
		$selection = rtrim($this->input->post("selection", TRUE), '|');
		$id_array = ($selection) ? explode("|", $selection) : '';
		
		if($id_array != '' && $table_name !='' && $primary_key !=''){
			switch($action){
				case 'delete':
					foreach($id_array as $item):
						if($item != ''):
							$this->db->delete($table_name, array($primary_key => $item));
						endif;
					endforeach;
					echo count($id_array).' Items deleted!';
				break;
				case 'publish':
					foreach($id_array as $item):
						if($item != '' && $field_name !=''):
							$this->db->update($table_name, array($field_name => 'Active'), array($primary_key => $item));
						endif;
					endforeach;
					echo count($id_array).' Items published!';
				break;
				case 'unpublish':
					foreach($id_array as $item):
						if($item != '' && $field_name !=''):
							$this->db->update($table_name, array($field_name => 'Inactive'), array($primary_key => $item));
						endif;
					endforeach;
					echo count($id_array).' Items unpublished!';
				break;	
			}
		}else{
		   echo 'Kindly Select Atleast One Item!';
		}
	}
}
