<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Faq_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 	
	function faqSectionCategory($parent_id=0){				
		$this->db->where('faq_parent_id', $parent_id);
		$this->db->where('status','Active');		
		$result = $this->db->get('manage_faq_category')->result_array();
		return $result;
	}
	
	function faqSearchResult($faqKey){
		$this->db->select("mf.*", false);
		$this->db->from('manage_faq mf');
		$this->db->like('mf.question', $faqKey);
		$this->db->where('mf.status','Active');
		$result = $this->db->get();
		$totalRecords =	$result->num_rows();
		$resultData = $result->result_array();
		$prepareData = array('totalRecords' => $totalRecords, 'resultData'=>$resultData);
		return $resultData;
	}
	
	function faqCategoryDetail($category_id){
		$this->db->select("c1.faq_category_id, c1.category_name, c2.category_name as section_name,c2.faq_category_id as section_id", false);
		$this->db->from('manage_faq_category c1');
		$this->db->join('manage_faq_category c2', 'c2.faq_category_id = c1.faq_parent_id', 'LEFT');
		$this->db->where('c1.faq_category_id', $category_id);
		$this->db->where('c1.status','Active');		
		$result = $this->db->get()->result_array();
		return $result;
	}	
	
	function faqList($category_id){
		$this->db->select("mf.*", false);
		$this->db->from('manage_faq mf');
		//$this->db->join('manage_faq_category mfc', 'mfc.faq_category_id = mf.category_id', 'LEFT');
		$this->db->where('mf.category_id', $category_id);
		$this->db->where('mf.status','Active');
		$result = $this->db->get();
		$totalRecords =	$result->num_rows();
		$resultData = $result->result_array();
		$prepareData = array('totalRecords' => $totalRecords, 'resultData'=>$resultData);
		return $resultData;
	}	
	
    function faqDetail($faq_id){
		$this->db->select("mf.*, mfc.category_name, (SELECT COUNT(mfl.like_id) FROM manage_faq_like mfl WHERE mfl.faq_id = mf.faq_id) as totalLike, (SELECT count(mfl2.like_id) FROM manage_faq_like mfl2 WHERE mfl2.like ='Yes' AND mfl2.faq_id = mf.faq_id) as totalHelpful", false);
		$this->db->from('manage_faq mf');
		$this->db->join('manage_faq_category mfc', 'mfc.faq_category_id = mf.category_id', 'LEFT');
		$this->db->where('mf.faq_id', $faq_id);
		$this->db->where('mf.status', 'Active');
		$result = $this->db->get();		
		$resultData = $result->result_array();		
		return $resultData;    

	}
    
	function recentlyViewFaq(){
		$client_id = $_SERVER['REMOTE_ADDR'];	
		
		$this->db->select("mfv.faq_id, mf.question");
		$this->db->from('manage_faq_view mfv');
		$this->db->join('manage_faq mf', 'mf.faq_id = mfv.faq_id', 'LEFT');
		$this->db->where(array('mfv.client_ipaddress' => $client_id));			
		$this->db->order_by('mfv.viewDate', 'DESC');
		$this->db->limit(10, 0);
		
		$result = $this->db->get();
		$totalRecords =	$result->num_rows();
		$resultData =	$result->result_array();
		//$prepareData = array('totalRecords' => $totalRecords, 'resultData'=>$resultData);
		return $resultData;
	}
    function relatedFaq($cat_id){
		
		$this->db->select("mf.faq_id, mf.question");		
		$this->db->from('manage_faq mf');		
		$this->db->where('mf.category_id', $cat_id);
		$this->db->where('mf.status','Active');
		$this->db->order_by('mf.faq_id', 'RANDOM');
    	$this->db->limit(5);
		$result = $this->db->get();
		$totalRecords =	$result->num_rows();
		$resultData =	$result->result_array();
		//$prepareData = array('totalRecords' => $totalRecords, 'resultData'=>$resultData);
		return $resultData;
	}
	
	function updateFaqView($faq_id){
		$client_id = $_SERVER['REMOTE_ADDR'];
		//check existing view records
		$where = array('faq_id'=>$faq_id, 'client_ipaddress' => $client_id);
		$this->db->where($where);
		$result = $this->db->get('manage_faq_view')->num_rows();
		if($result > 0){
			$this->db->update('manage_faq_view', array('viewDate' => date('Y-m-d h:i:s')), $where);
		}else{
			$data = $where;
			$this->db->insert('manage_faq_view', $data);
		}
		
	}
	function updateFaqLike($faq_id, $user_id, $like){
		$client_id = $_SERVER['REMOTE_ADDR'];
		//check existing view records
		$where = array('faq_id'=>$faq_id, 'user_id' => $user_id);
		$this->db->where($where);
		$result = $this->db->get('manage_faq_like')->num_rows();
		if($result > 0){
			return 'You have already liked this article!';
			//$this->db->update('manage_faq_like', array('viewDate' => date('Y-m-d h:i:s')), $where);
		}else{
			$data = array('faq_id'=>$faq_id, 'user_id' => $user_id, 'like'=>$like);
			$this->db->insert('manage_faq_like', $data);
			return 'Thankyou to find helpful article!';
		}
		
	}
     
}
 
/* End of file blog_model.php */
/* Location: ./application/models/blog_model.php */
