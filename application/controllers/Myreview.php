<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Myreview extends CI_Controller {
    var $userID;
	var $message = array();
	public function __construct(){  
		parent::__construct();
		//$this->load->database();
		$this->load->library(array('file_upload'));	
		$this->load->model(array('user_model','common','Agent_model','Professional_model'));
		$this->load->helper('common_admin');
		
		//check user login and get user_id from session.
		$this->userID = checkUserLogin();
		checkUserAccessPermission();
    } 
	
	public function index(){
		$this->load->library('pagination');
		$data['stylesheet'] = array('assets/plugins/bootstrap-fileupload/bootstrap-fileupload.min.css','assets/css/pagination.css');
		$data['page_title'] = 'Manage Reviews';
		$limit = 5;
		$dispaly = 1;
		$dispaly_from = $limit;
		$offset = ($this->uri->segment(3))? $this->uri->segment(3) : NULL;
		if(!empty($offset)){
			if($offset != 1){
			  $dispaly =  (($offset-1) * $limit);	
			}else{
				$dispaly = $limit;
			}
			
		 	$offset  = (($offset-1) * $limit); 
			$dispaly_from = $offset + $limit ;
		}
		$getCommentsCount = $this->Agent_model->getCommentsCount($this->userID);
	$data['agent_comments']=$this->Agent_model->getUserComments($this->userID,$limit, $offset);
		$config["base_url"] 	= base_url(). "myreview/index/";
		$config["total_rows"] 	= $getCommentsCount;
        $config["per_page"] 	= $limit;
        $config["uri_segment"] 	= 3;
		//$config['num_links']    = 2; 
		$config['display_pages'] = TRUE;  
		$config = array_merge($config, $this->config->item('pagingConfig'));
		$this->pagination->initialize($config);
		$data["links"] = $this->pagination->create_links();	
		$this->load->view('my_account/myreview', $data);	
	}
	
    public function detail($id){
	  
	  
	  $data["agent_comments"]=$this->Agent_model->getComment($id);
	  $data["comment_report"]=$this->Professional_model->comment_report($id);	
		$data['stylesheet'] = array('assets/plugins/bootstrap-fileupload/bootstrap-fileupload.min.css');
		$data['page_title'] = 'My Reviews';
		$this->load->view('my_account/myreview_detail', $data);	
	
	}
	public function deleteReview($id){
	   if(!empty($id)){
	   
	     $this->db->where('comment_id', $id);
         $result=$this->db->delete('comment');
		 
		 $this->db->where('comment_id', $id);
         $this->db->delete('comment_like');
		 
		 
		 $this->db->where('commnet_id', $id);
         $this->db->delete('comment_report');
		 
		 if($result){	

				$this->messageci->set('Review Delete successfully!', 'success');

			}else{

				$this->messageci->set('There is coming problem to delete your review!', 'error');

           }
	     redirect('myreview');
	   }
	}
}
