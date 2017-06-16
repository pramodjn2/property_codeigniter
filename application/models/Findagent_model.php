<?php

class Findagent_model extends CI_Model {

    var $details;

    function usercomment($uu){		
		$this->db->select('g.profile_image,g.name,cl.likes,c.*');
        $this->db->from('comment c');
		$this->db->join('user g', 'c.user_id = g.user_id', 'LEFT'); 
		$this->db->join('comment_like cl', 'c.comment_id = cl.comment_id', 'LEFT'); 
		 $this->db->where('c.comment_parent_id=0');
		 $this->db->where('c.user_id='. $uu);
		
        $result = $this->db->get();
		$records = $result->num_rows();
		$recordsData = $result->result();
        if($records > 0) {			
            // check password with stored password		
			//if($this->decrypt_password($recordsData[0]->password) == trim($password)){				
				$this->details = $recordsData;	
				
			return $this->details;
			//}         
        }
        return false;
    }

   
	
	
}
