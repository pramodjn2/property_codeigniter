<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Myblog extends CI_Controller { 
	
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
		$crud->set_subject('Blog');
		
		$crud->set_table('blog');
		$crud->where('user_id', $this->userID);
		$crud->set_relation('category_id', 'blog_category', 'categoryName');
		
		$crud->set_field_upload('blog_image','applicationMediaFiles/blogImage');
	    $crud->columns('category_id','blog_title' , 'blog_description' , 'blog_image','status');
		
		$crud->add_fields('user_id', 'userType', 'category_id', 'blog_title','blog_description', 'blog_tags', 'blog_image','status','user_id');		
		$crud->edit_fields('category_id','blog_title','blog_description', 'blog_tags', 'blog_image','status','user_id');
        $crud->display_as('category_id','Blog Category');
		$crud->required_fields('category_id','blog_title','blog_description' , 'blog_image','status');
		$crud->unset_fields('createdDate','updatedDate','user_id');
		$crud->unset_texteditor('blog_description','full_text');
		
		$crud->field_type('user_id', 'hidden', $this->userID);
		$crud->field_type('userType', 'hidden', 'User');
		
		$crud->set_bulk_action_url(base_url('myblog/bulk_action'));		
		$crud->set_data_status_field_name('status');
		
		$output = $crud->render();
		
		$page_title = array('page_title' => 'Manage Blogs');
		$outputData = array_merge((array) $output , $page_title);
		$this->_example_output($outputData); 		
	}
	
	
	
	public function _example_output($output = null){
		$this->load->view('my_account/setting',$output);
	}
	function resize_callback_after_upload($uploader_response,$field_info, $files_to_upload)
	{     
		$upload_directory = config_item('root_url').'applicationMediaFiles/abroadimage/';
		
		$filePath= $upload_directory.$uploader_response[0]->name;
		$img = resize_images($filePath,730,325, $upload_directory.'730326/');	
		$img = resize_images($filePath,350,325, $upload_directory.'350325/');	
		return true;
	}	
	public function bulk_action(){
		$action = $this->input->post("action", TRUE);
		$table_name = $this->input->post("table_name", TRUE);
		$field_name = $this->input->post("field_name", TRUE);
		$primary_key = $this->input->post("primary_key", TRUE);
		$selection = rtrim($this->input->post("selection", TRUE), '|');
		$id_array = ($selection) ? explode("|", $selection) : '';
		
		if(isset($id_array) && $id_array != '' && $table_name !='' && $primary_key !=''){
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
