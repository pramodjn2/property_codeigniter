<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Message_model extends CI_Model {
	
	var $resultDATA = array();
	
	
	
	function messageDetail($message_id, $dataType = 'single'){
		
		$this->db->select("m.*, (SELECT GROUP_CONCAT(mrr.receiver_email separator ',') as receiver FROM message_receiver_relation mrr where mrr.message_id = m.message_id) as allReceiver", false);
		$this->db->from('message m');
		$this->db->where('m.message_id', $message_id);
		$this->db->order_by('m.sendDate','DESC');
		
		$result = $this->db->get();	
		foreach($result->result_array() as $data){			
			$this->resultDATA[] = $data;
			if($data['thread'] > 0 && $dataType !='single'){				
				$this->messageDetail($data['thread'], 'thread');
			}
		}
		return $this->resultDATA;
	}

}
