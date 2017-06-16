<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mystaff extends CI_Controller {
    public function __construct(){
		parent::__construct();
		//$this->load->database();
		$this->load->library(array('file_upload'));	
		$this->load->model(array('user_model'));		
		//$this->output->enable_profiler(TRUE);
		checkUserAccessPermission();
    } 
	
	public function index(){
		
		$data['page_title'] = 'My Staff';
		$this->load->view('my_account/staff', $data);	
	}
}