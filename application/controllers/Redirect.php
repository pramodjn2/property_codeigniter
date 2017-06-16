<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Redirect extends CI_Controller { 

	    public function __construct(){
		   parent::__construct();
		   $this->load->database();
		   //$this->output->enable_profiler(TRUE);
        } 

	public function index(){
		/**Redirect From Edit-Profile**/
		$this->messageci->set('Your a/c is inactive. Please verify email after that login', 'success');
			  redirect(base_url('user/login'));	die;
	}
}