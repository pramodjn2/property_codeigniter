<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mymessage extends CI_Controller { 
	
	var $userID;
	var $userEmail;
	var $message;
	public function __construct() {
		
		parent::__construct();
		$this->load->helper(array('common_admin'));
		$this->load->library(array('file_upload','fileupload','grocery_CRUD', 'ajax_grocery_crud'));
		$this->load->model('Message_model');					 		
		$this->language =  language_load();
		//check user login and get user_id from session.
		$this->userID = checkUserLogin();
		$this->userEmail = $this->session->userdata('email');		
		checkUserAccessPermission();
	}
	
	public function _result_data_output($output = null){
		$this->load->view('my_account/message/inbox',$output);
	}	
	
	public function index(){
		
		$crud = new grocery_CRUD();
		$crud->set_subject('Item');
				
		$userEmail = $this->session->userdata('email');
		$crud->set_model('custom_query_model');
		$crud->set_table('message_receiver_relation'); 
		$crud->basic_model->set_query_str("SELECT mrr.*, CONCAT(u.firstName,' ',u.lastName) as sender_name, m.subject, m.message, m.attachment FROM message_receiver_relation mrr LEFT JOIN user u ON u.email = mrr.sender_email LEFT JOIN message m ON m.message_id = mrr.message_id WHERE mrr.receiver_email='".$userEmail."' AND mrr.trash=0 ORDER BY mrr.send_date DESC"); //Query text here
					
		$crud->columns('sender_name', 'mail', 'subject', 'message', 'send_date', 'action');
		$crud->unset_columns('subject', 'message');
		$crud->display_as('mail', 'Message');
		
		$crud->callback_column('sender_name',array($this,'_display_sender'));
		$crud->callback_column('send_date',array($this,'_filter_date'));
		$crud->callback_column('mail',array($this,'_filter_html'));
		$crud->callback_column('action',array($this,'_custome_action_button'));
		
		//$crud->unset_add();
		$crud->unset_edit();
		$crud->unset_delete();
		$crud->unset_read();
		$crud->unset_print();
		$crud->unset_export();
		
		$crud->set_add_url_path(base_url('mymessage/composeMail'));
		$crud->set_bulk_delete_button_text('Delete');
		$crud->set_bulk_publish_button_text('Mark Read');
		$crud->set_bulk_unpublish_button_text('Mark Unread');
		$crud->set_add_button_text('Compose');
		
		$crud->set_bulk_action_url(base_url('mymessage/bulk_action'));		
		$crud->set_data_status_field_name('read_status');
		
		//$crud->set_edit_url_path(base_url('myproperties/edit'));
		//$crud->set_read_url_path(base_url('myproperties/view'));		
		
		
		$output = $crud->render();		
		$page_title = array('page_title'=>'Inbox');
		$outputData = array_merge((array)$output, $page_title);
		$this->_result_data_output($outputData);		
	}
	
	public function sentbox(){
		
		$crud = new grocery_CRUD();
		$crud->set_subject('Item');
		$crud->set_model('custom_query_model');
		
		$crud->set_table('message');		 
		$crud->basic_model->set_query_str("SELECT m.*, (SELECT GROUP_CONCAT(mrr.receiver_email) FROM message_receiver_relation mrr WHERE mrr.message_id = m.message_id) as sent_to FROM message m WHERE m.sender_email='".$this->userEmail."' AND m.trash=0 ORDER BY m.sendDate DESC");
					
		$crud->columns('mail', 'subject', 'message', 'sent_to', 'sendDate', 'action');
		$crud->unset_columns('subject', 'message','total_recipients');
		$crud->display_as('mail', 'Message');
		
		$crud->callback_column('sent_to',array($this,'_display_recipients'));
		$crud->callback_column('sendDate',array($this,'_filter_date'));
		$crud->callback_column('mail',array($this,'_sentbox_filter_html'));
		$crud->callback_column('action',array($this,'_sentbox_custome_action_button'));
		
		$crud->unset_add();
		$crud->unset_edit();
		$crud->unset_delete();
		$crud->unset_read();
		$crud->unset_print();
		$crud->unset_export();
		//$crud->unset_bulk_delete();
		$crud->unset_bulk_publish();
		$crud->unset_bulk_unpublish();
		
		$crud->set_add_url_path(base_url('mymessage/composeMail'));
		$crud->set_bulk_delete_button_text('Delete');
		$crud->set_bulk_publish_button_text('Mark Read');
		$crud->set_bulk_unpublish_button_text('Mark Unread');		
		$crud->set_add_button_text('Compose');
		
		$crud->set_bulk_action_url(base_url('mymessage/bulk_action'));		
		$crud->set_data_status_field_name('read_status');
		
		$output = $crud->render();		
		$page_title = array('page_title'=>'Sentbox');
		$outputData = array_merge((array)$output, $page_title);
		$this->_result_data_output($outputData);		
	}
	
	public function trash(){
		
		$crud = new grocery_CRUD();
		$crud->set_subject('Item');
						
		$userEmail = $this->session->userdata('email');
		$crud->set_model('custom_query_model');
		$crud->set_table('message_receiver_relation'); 
		$crud->basic_model->set_query_str("SELECT mrr.*, CONCAT(u.firstName,' ',u.lastName) as sender_name, m.subject, m.message, m.attachment FROM message_receiver_relation mrr LEFT JOIN user u ON u.email = mrr.sender_email LEFT JOIN message m ON m.message_id = mrr.message_id WHERE (mrr.sender_email='".$userEmail."' OR mrr.receiver_email='".$userEmail."') AND mrr.trash=1 ORDER BY mrr.send_date DESC"); //Query text here
					
		$crud->columns('sender_name', 'mail', 'subject', 'message', 'send_date', 'action');
		$crud->unset_columns('subject', 'message');
		$crud->display_as('mail', 'Message');
		
		$crud->callback_column('sender_name',array($this,'_display_sender'));
		$crud->callback_column('send_date',array($this,'_filter_date'));
		$crud->callback_column('mail',array($this,'_filter_html'));
		$crud->callback_column('action',array($this,'_custome_action_button'));
		
		//$crud->unset_add();
		$crud->unset_edit();
		$crud->unset_delete();
		$crud->unset_read();
		$crud->unset_print();
		$crud->unset_export();
		
		$crud->set_add_url_path(base_url('mymessage/composeMail'));
		$crud->set_bulk_delete_button_text('Move To Inbox');
		$crud->set_bulk_publish_button_text('Mark Read');
		$crud->set_bulk_unpublish_button_text('Mark Unread');
		$crud->set_add_button_text('Compose');
		
		$crud->set_bulk_action_url(base_url('mymessage/bulk_action_for_trash'));	
		$crud->set_data_status_field_name('read_status');
		
		//$crud->set_edit_url_path(base_url('myproperties/edit'));
		//$crud->set_read_url_path(base_url('myproperties/view'));		
		
		
		$output = $crud->render();		
		$page_title = array('page_title'=>'Trash Box');
		$outputData = array_merge((array)$output, $page_title);
		$this->_result_data_output($outputData);		
	}
	
	public function _display_sender($value, $row){	
		$name = ($value=='') ? $row->sender_email : $value;		
		$msgid = config_item('URL_ENCODE') ? safe_b64encode($row->message_id) : $row->message_id;
		$read_status = ($row->read_status==0) ? 'unread_mail' : 'read_mail';
		return '<a class="'.$read_status.'" href="'.base_url('mymessage/messageDetail/'.$msgid).'">'.$name.'</a>';
	}
	public function _filter_date($value, $row){		
		return get_time_ago($value);
	}	
	public function _filter_html($value, $row){	
		$msgid = config_item('URL_ENCODE') ? safe_b64encode($row->message_id) : $row->message_id;
		$read_status = ($row->read_status==0) ? 'unread_mail' : 'read_mail';
		$data='<a class="'.$read_status.'" href="'.base_url('mymessage/messageDetail/'.$msgid).'"><strong>'.$row->subject.'</strong>'.' - ';	
		$data .= substr(strip_tags($row->message), 0, 100).'...';
		if($row->attachment !='N;' && ($row->attachment !='N;' && $row->attachment !='')){
			$data .= '<i class="clip-attachment"></i>';
		}
		$data .= '</a>';
		return $data;
	}
	public function _custome_action_button($primary_key, $row){
		//$row->user_id;
		$button = '<a title="View" href="'.base_url('mymessage/messageDetail/'.$row->message_id).'" class="btn btn-xs btn-default"><i class="fa fa-eye"></i></a>';
		$button .= '<a title="Reply" href="'.base_url('mymessage/reply/'.$row->message_id).'" class="btn btn-xs btn-default"><i class="fa fa-reply"></i></a>';
		$button .= '<a title="Forward" href="'.base_url('mymessage/forward/'.$row->message_id).'" class="btn btn-xs btn-default"><i class="fa fa-mail-forward"></i></a>';
		
		return $button;
	}
	
	//this (_display_recipients) using in sentbox mail function 	
	public function _display_recipients($value, $row){
		$recipients =  explode(',',$row->sent_to);
		$firstEmail = $recipients[0];		
		if(count($recipients) > 1) $firstEmail .= '..('.(count($recipients)-1).' more)';		
		$msgid = config_item('URL_ENCODE') ? safe_b64encode($row->message_id) : $row->message_id;		
		return '<a href="'.base_url('mymessage/sentMsgDetail/'.$msgid).'">'.$firstEmail.'</a>';
	}
	//this (_sentbox_filter_html) using in sentbox mail function 
	public function _sentbox_filter_html($value, $row){
		$msgid = config_item('URL_ENCODE') ? safe_b64encode($row->message_id) : $row->message_id;
		//$read_status = ($row->read_status==0) ? 'unread_mail' : 'read_mail';
		$data='<a href="'.base_url('mymessage/sentMsgDetail/'.$msgid).'"><strong>'.$row->subject.'</strong>'.' - ';	
		$data .= substr(strip_tags($row->message), 0, 75).'...';
		if($row->attachment !='N;' && ($row->attachment !='N;' && $row->attachment !='')){
			$data .= '<i class="clip-attachment"></i>';
		}
		$data .= '</a>';
		return $data;
	}	
	//this (_sentbox_custome_action_button) using in sentbox mail function
	public function _sentbox_custome_action_button($primary_key, $row){
		//$row->user_id;
		$button = '<a title="View" href="'.base_url('mymessage/sentMsgDetail/'.$row->message_id).'" class="btn btn-xs btn-default"><i class="fa fa-eye"></i></a>';
		//$button .= '<a title="Reply" href="'.base_url('mymessage/reply/'.$row->message_id).'" class="btn btn-xs btn-default"><i class="fa fa-reply"></i></a>';
		$button .= '<a title="Forward" href="'.base_url('mymessage/forward/'.$row->message_id).'" class="btn btn-xs btn-default"><i class="fa fa-mail-forward"></i></a>';
		
		return $button;
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
							$this->db->update($table_name, array('trash' => '1'), array($primary_key => $item));
						endif;
					endforeach;
					echo count($id_array).' Items deleted!';
				break;
				case 'publish':
					foreach($id_array as $item):
						if($item != '' && $field_name !=''):
							$this->db->update($table_name, array($field_name => '1'), array($primary_key => $item));
						endif;
					endforeach;
					echo count($id_array).' Items marked as read!';
				break;
				case 'unpublish':
					foreach($id_array as $item):
						if($item != '' && $field_name !=''):
							$this->db->update($table_name, array($field_name => '0'), array($primary_key => $item));
						endif;
					endforeach;
					echo count($id_array).' Items marked as unread!';
				break;	
			}
		}else{
		   echo 'Kindly Select Atleast One Item!';
		}
	}
	public function bulk_action_for_trash(){
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
							$this->db->update($table_name, array('trash' => '0'), array($primary_key => $item));
						endif;
					endforeach;
					echo count($id_array).' Items moved to inbox!';
				break;
				case 'publish':
					foreach($id_array as $item):
						if($item != '' && $field_name !=''):
							$this->db->update($table_name, array($field_name => '1'), array($primary_key => $item));
						endif;
					endforeach;
					echo count($id_array).' Items marked as read!';
				break;
				case 'unpublish':
					foreach($id_array as $item):
						if($item != '' && $field_name !=''):
							$this->db->update($table_name, array($field_name => '0'), array($primary_key => $item));
						endif;
					endforeach;
					echo count($id_array).' Items marked as unread!';
				break;	
			}
		}else{
		   echo 'Kindly Select Atleast One Item!';
		}
	}	
	public function messageDetail(){
		$message_id = $this->uri->segment(3);
		$this->lang->load('message/message', $this->language);
		$data['lang_data'] = $this->lang->language;
		
		$result = $this->Message_model->messageDetail($message_id, 'thread');
		$data['result'] = $result;
		
	    if(isset($data['result'])){
			$this->db->where(array('message_id' => $message_id, 'receiver_email' => $this->userEmail));
			$this->db->update('message_receiver_relation', array('read_status'=>1));
		}
		
		$data['stylesheet'] = array('assets/plugins/select2/select2.css',
									'assets/plugins/jQuery-Tags-Input/jquery.tagsinput.css');
									
        $data['page_title'] = 'Message Detail';
		$this->load->view('my_account/message/messageDetail',$data);	
		
	}
	public function sentMsgDetail(){
		$message_id = $this->uri->segment(3);
		$this->lang->load('message/message', $this->language);
		$data['lang_data'] = $this->lang->language;
		
		$result = $this->Message_model->messageDetail($message_id, 'thread');
		$data['result'] = $result;
		
	    if(isset($data['result'])){
			$this->db->where(array('message_id' => $message_id, 'receiver_email' => $this->userEmail));
			$this->db->update('message_receiver_relation', array('read_status'=>1));
		}
		
		$data['stylesheet'] = array('assets/plugins/select2/select2.css',
									'assets/plugins/jQuery-Tags-Input/jquery.tagsinput.css');
									
        $data['page_title'] = 'Message Detail';
		$this->load->view('my_account/message/messageDetail',$data);	
		
	}	
	public function composeMail(){
		
		if($_GET["e"] && $_GET["i"]){
		 	$data['sender_email'] = safe_b64decode($_GET["e"]);
			$data['freevaluationID'] = safe_b64decode($_GET["i"]);
			$data['typereply'] = $_GET["t"];
		}
		
		
		$this->lang->load('message', $this->language);
        $data['lang_data']=$this->lang->language;
		$composeFormValidation = $this->__setFormRules('compose');
		
		if($composeFormValidation){
			$this->sendMail($this->input->post());
			/*$recipienties = $this->input->post('recipienties');
			$subject = $this->input->post('subject');
			$message = $this->input->post('message');
			$attachment = $this->input->post('attachment', TRUE);
			$attachedFilename = $this->input->post('attachedFilename', TRUE);
			
			$preparedData = array('sender_email' => $this->userEmail,
								  'subject' => $subject,
								  'message' => $message,
								  'attachment' => serialize($attachment),
								  'attachedFilename' => serialize($attachedFilename));  //var_dump($preparedData);die;
			$result = $this->db->insert('message', $preparedData);
			$message_id = $this->db->insert_id();
			
			if($result && $message_id){		
				
				$emaildata['body'] = $message;
				$message = $this->load->view('my_account/message/template/simple_message', $emaildata, TRUE);
				$toEmail = getUserEmailByIDs($toArray);
				$fromEmail = array('email' => $this->session->userdata('email'),
									'name' => $this->session->userdata('fullName'));
				
				if(sendEmailCI($toEmail, $fromEmail, $subj, $message, $attachment))
					$mail_status = 'Sent';
				else
					$mail_status = 'Failed';
				
				$recipienties = explode(',',$recipienties);
				$message = array();	
				for($i=0; $i < count($recipienties); $i++){	
										
					$preparedData = array('sender_email' => $this->userEmail,
								  		  'receiver_email' => $recipienties[$i],
										  'message_id' => $message_id,
										  'send_status' => $mail_status);
										  
					if($this->db->insert('message_receiver_relation', $preparedData)){
						$message[] = array('success'=>'Your message has been sent to "'.$recipienties[$i].'"');
					}else{
						$message[] = array('error'=>'Message sending failed to "'.$recipienties[$i].'"');
					}
				}
				$this->session->set_userdata(array('message' => $message));
				redirect(base_url('mymessage'));			
			}*/
		}
		
		$data['stylesheet'] = array('assets/plugins/select2/select2.css',
									'assets/plugins/jQuery-Tags-Input/jquery.tagsinput.css');
									
        $data['page_title']='Compose Message';
		$this->load->view('my_account/message/composeMail',$data);	
	}
	public function reply($message_id=NULL){
		
		$this->lang->load('message', $this->language);
        $data['lang_data']=$this->lang->language;
		
		$composeFormValidation = $this->__setFormRules('compose');
		
		if($composeFormValidation){					
			$this->sendMail($this->input->post());
		}
		
		$data['result'] = $this->Message_model->messageDetail($message_id);
		$data['my_email'] =$this->userEmail;
		
		$data['stylesheet'] = array('assets/plugins/jQuery-Tags-Input/jquery.tagsinput.css');
									
        $data['page_title']='Reply';
		$data['mailType'] = 'reply';
		$this->load->view('my_account/message/replyMail', $data);
	}	
	public function replyToAll($message_id=NULL){
		
		$this->lang->load('message', $this->language);
        $data['lang_data']=$this->lang->language;
		
		$composeFormValidation = $this->__setFormRules('compose');
		
		if($composeFormValidation){						
			$this->sendMail($this->input->post());
		}
		
		$data['result'] = $this->Message_model->messageDetail($message_id);
		$data['my_email'] =$this->userEmail;
		
		$data['stylesheet'] = array('assets/plugins/jQuery-Tags-Input/jquery.tagsinput.css');
									
        $data['page_title']='Reply To All';
		$data['mailType'] = 'replyToAll';
		$this->load->view('my_account/message/replyMail', $data);
	}
	public function forward($message_id=NULL){
		
		$this->lang->load('message', $this->language);
        $data['lang_data']=$this->lang->language;
		
		$composeFormValidation = $this->__setFormRules('compose');
		
		if($composeFormValidation){					
			$this->sendMail($this->input->post());
		}		
		$data['result'] = $this->Message_model->messageDetail($message_id);		
		$data['my_email'] =$this->userEmail;
		$data['stylesheet'] = array('assets/plugins/jQuery-Tags-Input/jquery.tagsinput.css');
        $data['page_title']='Message Forward';
		$data['mailType'] = 'forward';
		$this->load->view('my_account/message/replyMail', $data);
	}
	public function moveInTrash($msgID = NULL){
		$updateData = array('trash' => '1');
		$where = array('receiver_email' => $this->userEmail, 'message_id' => $msgID);
		$result = $this->db->update('message_receiver_relation', $updateData, $where);
		if($result){
			$this->messageci->set('Message successfully deleted', 'success');
		}else{
			$this->messageci->set('Message deletion failed', 'error');
		}
		redirect('mymessage');
	}
	public function sentItemDelete($msgID = NULL){	
		$updateData = array('trash' => '1');
		$where = array('sender_email' => $this->userEmail, 'message_id' => $msgID);
		$result = $this->db->update('message', $updateData, $where);
		if($result){
			$this->messageci->set('Message successfully deleted', 'success');
		}else{
			$this->messageci->set('Message deletion failed', 'error');
		}
		redirect('mymessage/sentbox');
	}
	function sendMail($formData = ''){
		if(isset($formData)){
			
			
			$message_id 	  = $formData['message_id'];
			$recipienties 	  = $formData['recipienties'];
			$subject 		  = $formData['subject'];
			$message 		  = $formData['message'];
			$attachment 	  = $formData['attachment'];
			$attachedFilename = $formData['attachedFilename'];
			
			$free_valuationID = $formData['free_valuationID'] ? $formData['free_valuationID'] : 0;
			$type_reply       = $formData['type_reply'] ? $formData['type_reply'] : 'message';
			
			$preparedData = array('sender_email' => $this->userEmail,
								  'subject' => $subject,
								  'message' => $message,
								  'attachment' => serialize($attachment),
								  'attachedFilename' => serialize($attachedFilename),
								  'message_type'=>$type_reply,
								  'message_type_id'=>$free_valuationID);
								  
					if(!empty($free_valuationID) && $type_reply == 'freevaluation'){					 	
						$data_typereply = array('message_status' => 'Replied');
						$this->db->where('id', $free_valuationID);
						$this->db->update('free_valuation', $data_typereply);						
					}
								  
			if($message_id!=''){
				$preparedData = array_merge($preparedData, array('thread' =>$message_id));
			}
								  
			$result = $this->db->insert('message', $preparedData);
			$message_id = $this->db->insert_id();
			
			if($result && $message_id){		
				
				$emaildata['reply_url'] = config_item('base_url').'mymessage/reply/'.$message_id;
				$emaildata['reply_to_all_url'] = config_item('base_url').'mymessage/replyToAll/'.$message_id;
				
				$emaildata['body'] = $message;
				$emaildata['subject'] = $subject;
				$emaildata['message'] = $message;
				
				
				$item = explode("@", $recipienties);

				$emaildata['fullname'] = $item[0];
				$emaildata['firstname'] = $item[0];
				$emaildata['email'] = $item[0];
				
				$adminfulldata=getUserDetails($this->session->userdata('user_id'));
				
				$emaildata['admin_fullname'] = $adminfulldata[0]['firstName'].' ' .$adminfulldata[0]['lastName'];
               $emaildata['admin_firstName']=$adminfulldata[0]['firstName'];   
               $emaildata['admin_profile_image']=$adminfulldata[0]['profile_image'];  
    
          $adusername=ucwords($emaildata['admin_fullname']);
          $seosendername = str_replace('&nbsp;', '-', $adusername);
          $emaildata['senderseo'] = seo_friendly_urls($seosendername,'',$this->session->userdata('user_id'));
				  
				  $mydata=selectData('user',"where email='$recipienties'");
				
				if(!empty($mydata)){
				   
				   $username=ucwords($mydata[0]['firstName'].' '.$mydata[0]['lastName']);
                   $seousername = str_replace('&nbsp;', '-', $username);
 
                   $emaildata['recieverseo'] = seo_friendly_urls($seousername,'',$mydata[0]['user_id']);
				   
				   $emaildata['profile_image'] = $mydata[0]['profile_image'];
				
				} 
	 
				
				$message = $this->load->view('my_account/message/template/simple_message_template', $emaildata, TRUE);
				//$toEmail = getUserEmailByIDs($toArray);
				$toEmail = $recipienties;
				$fromEmail = array('email' => $this->session->userdata('email'),
									'name' => $this->session->userdata('fullName'));
				
				
				$subject = ucwords(config_item('site_name').' - '.$subject);
					$emailStatus = sendUserEmailCI($toEmail, $fromEmail, $subject, $message, $attachment);


				if($emailStatus)
					$mail_status = 'Sent';
				else
					$mail_status = 'Failed';
				
				$recipienties = explode(',',$recipienties);
				$message = array();	
				for($i=0; $i < count($recipienties); $i++){	
										
					$preparedData = array('sender_email' => $this->userEmail,
								  		  'receiver_email' => $recipienties[$i],
										  'message_id' => $message_id,
										  'send_status' => $mail_status);
										  
					if($this->db->insert('message_receiver_relation', $preparedData)){
						$message[] = array('success'=>'Your message has been sent to "'.$recipienties[$i].'"');
					}else{
						$message[] = array('error'=>'Message sending failed to "'.$recipienties[$i].'"');
					}
				}
				$this->session->set_userdata(array('message' => $message));
				redirect(base_url('mymessage'));			
			}
		}else{
			$error = array('error'=>'Form data submission faild, Please try agian!');
			$this->session->set_userdata(array('message' => $error));
			  
		}
	}
	
	function checkMail(){
		
		/*$to  = 'manoj.techlect@gmail.com'; // note the comma
		// subject
		$subject = 'Birthday Reminders for August';
		// message
		$message = '';
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'To: Mary <manoj.techlect@gmail.com>' . "\r\n";
		$headers .= 'From: Birthday Reminder <testing@techlect.in>' . "\r\n";
		$result = mail($to, $subject, $message, $headers);*/	
		
		$emaildata['body'] = 'This is testing mail'; //$message;
		$attachment = array();
		$subj = 'This is testing mail';
		$message = $this->load->view('my_account/message/template/simple_message', $emaildata, TRUE);
		$toEmail = 'manoj.techlect@gmail.com';
		$fromEmail = array('email' => 'testing@techlect.in',
							'name' => $this->session->userdata('fullName'));
		$result = sendEmailCI($toEmail, $fromEmail, $subj, $message, $attachment);
			
		if($result)
			echo $mail_status = 'Sent';
		else
			echo $mail_status = 'Failed';
	}
	public function sendmessage(){
		$formValidation = $this->__setFormRules('sendMail');
		if($formValidation){
			$this->load->model('Message');
			$fromemail = array();	
			$from = $to = $subj = $msg = '';
			$from = $this->input->post('frommsgid',TRUE);
			$toArray = $this->input->post('tomsg',TRUE);
			$subj = $this->input->post('msgsubj',TRUE);
			$msg = $this->input->post('msgbody',TRUE);
			$attachments = $this->input->post('attachment',TRUE);
			$attachedFilename = $this->input->post('attachedFilename',TRUE);
			$newuniqueid = $this->Message->getNewMessageUniqueID();
			$serializeAttachment = serialize($attachments);
			$serializeattachedFilename = serialize($attachedFilename);
			$emaildata['body'] = $msg;
			$message = $this->load->view('my_account/message/email', $emaildata, TRUE);
			$toemail = getUserEmailByIDs($toArray);
			$fromuser = getUserDetails($from);
			$fromemail['email'] = $fromuser[0]['email'];
			$fromemail['name'] = $fromuser[0]['firstName'].' '.$fromuser[0]['lastName'];
			sendEmailCI($toemail, $fromemail, $subj, $message, $attachments);
			$this->load->helper('path');
			/* Enables you to send an attachment */
			//echo $this->email->print_debugger(); 
			$data = array('message_unique_id' => $newuniqueid,
						  'subject' => $subj,
						  'message' => $msg,
						  'sender' => $from,
						  'receiver' => serialize($toArray),
						  'attachments' => $serializeAttachment,
						  'attachment_name' => $serializeattachedFilename,
						  'owner' => $from,
						  'date_time'=>time());
			$this->Message->data_insert('messages', $data);
			foreach($toArray as $sv){
				$data['receiver'] = serialize($toArray);			
				$data['owner'] = $sv;
				$this->Message->data_insert('messages',$data);
			}
			redirect(base_url('mymessage'));
		}else{
			$this->lang->load('message', $this->language);
			$data['lang_data']=$this->lang->language;
			$data['page_title']='Compose Message';
			$this->load->view('my_account/message/composeMessage',$data);
		}
	}
	public function mkdir_r($dirName, $rights=0755){
		$dirs = explode('/', $dirName);
		$dir='';
		foreach ($dirs as $part) {
			$dir.=$part.'/';
			if (!is_dir($dir) && strlen($dir)>0)
				mkdir($dir, $rights);
		}
	}
    public function uploadAttachment(){
		
		$user = $this->session->userdata();
		$Id = !empty($user['user_id'])? $user['user_id'] : 0;
		$this->lang->load('message', $this->language);
        $lang_data = $this->lang->language;
		if(empty($Id)){
			echo json_encode(array('fail' => false, 'msg' => $lang_data['message_please_login']));   die;
		}
		if (!is_dir(config_item('root_url').'assets/property/message/' . $Id )) {
				$this->mkdir_r(config_item('root_url').'assets/property/message/' . $Id );
		}
		$upload_dir =  config_item('root_url').'assets/property/message/' . $Id ;
		//$uploader = new FileUpload('uploadfile');
		// Handle the upload
		$result = $this->fileupload->handleUpload($upload_dir);
		$filepath = 'assets/property/message/' . $Id .'/'.$this->fileupload->getFileName();
		if (!$result) {
			echo json_encode(array('fail' => false, 'msg' => $this->fileupload->getErrorMsg()));   
		} else {
			echo json_encode(array('success' => true, 'file' => $filepath, 'filename' => $this->fileupload->getOriginalFileName()));
		}
		die;
	}
	
	public function trashMsg(){
	 $user_id = $this->session->userdata('user_id');
	 $msgid = $this->input->post('msgid',TRUE);
	 $data = array('trash'=>1);
	 $array = array('owner'=>$user_id,'message_id'=>$msgid);
	 //echo '<pre/>'; print_r($data);
	 //echo '<pre/>'; print_r($array);
	 $this->db->where($array);
	 $this->db->update('messages',$data);
	}
	public function viewTrashMessage(){
	 $this->lang->load('message/message', $this->language);
     $lang_data=$this->lang->language;
	 $msgid = $this->input->post('msgid',TRUE); //message id
	 $user_id = $this->session->userdata('user_id');
	 $this->db->select('m.*,u.email,us.email senderemail')
	 ->from('messages m')
	 ->join('user u', 'u.user_id='.$user_id)
	 ->join('user us','us.user_id = m.sender ','left')
	 ->where('m.message_id',$msgid);
	 $result = $this->db->get()->result_array();
	 //echo '<pre/>'; print_r($result); die;
	 if(!empty($result)){
	 $html_r='';
	   
	   $html_r='<div class="message-header">
											<div class="message-time">
												'.date('d-M-Y h:i A',$result[0]['date_time']).'
											</div>
										<!--	<div class="message-from">
&lt;'.$result[0]["email"].'&gt;</div>
											<div class="message-to">
												'.$lang_data["to"].':';
											$rec_data = unserialize($result[0]['receiver']);
											foreach($rec_data as $rec_val){
										$html_r .=ucfirst(getUserName($rec_val)).',&nbsp;';
											}
											$html_r .= '
											</div>-->
											<div class="message-subject">
												'.$lang_data['subject'].$result[0]['subject'].'
											</div>
											<div class="message-actions">
												<a title="'.$lang_data['message_move'].'" href="javascript:void(0);" onclick="deleteMessage('.$msgid.')"><i class="fa fa-trash-o"></i></a>
												<a title="'.$lang_data['message_back'].'" href="javascript:void(0);" onclick="resetMessage('.$msgid.')"><i class="fa fa-reply"></i></a>
											<!--	<a title="'.$lang_data['message_replyall'].'" href="wsdindex.html#"><i class="fa fa-reply-all"></i></a>
												<a title="'.$lang_data['message_forward'].'" href="wsdindex.html#"><i class="fa fa-long-arrow-right"></i></a>-->
											</div>
										</div>
										<div class="message-content">
											<p>'.$result[0]['message'].'</p>
										</div>';
	   
	   echo $html_r;  
	 }
	 
	}
	public function delete(){
		$msgid = $this->input->post('msgid',TRUE);
		$this->db->delete('messages', array('message_id' => $msgid)); 
		
	}
	public function resetMessage(){
	 $msgid = $this->input->post('msgid',TRUE);
	 $data = array('trash'=>0);
	 $this->db->where('message_id',$msgid);
	 $this->db->update('messages',$data);
	 
	}
	public function viewMessage(){
	
	   $this->lang->load('message/message', $this->language);
       $lang_data=$this->lang->language;
	   
	   $id = $this->input->post('id',TRUE); //message id
	   $user_id = $this->session->userdata('user_id');
	   
	    $msg_read = array('receiver_read' => 1);
		$this->db->where('message_id', $id);
		$this->db->update('messages',$msg_read); 
	   $this->db->select('m.*,u.email,us.email senderemail');
	   $this->db->from('messages m');
	   $this->db->join('user u', 'u.user_id='.$user_id);
	   $this->db->join('user us', 'm.sender = us.user_id');
	   $this->db->where('m.message_id',$id);
	   $result = $this->db->get()->result_array();
	   $html_r='';
	   
	   $html_r='<div class="message-header">
											<div class="message-time">
												'.date('d-M-Y h:i A',$result[0]['date_time']).'
											</div>
											<div class="message-from">
					'.ucfirst(getUserName($result[0]["sender"])).' &lt;'.$result[0]["senderemail"].'&gt;
											</div>
											<div class="message-to">
												'.$lang_data["to"].': '.$result[0]["email"].'
											</div>
											<div class="message-subject">
												'.$lang_data['subject'].$result[0]['subject'].'
											</div>
											<div class="message-actions">
												<a title="'.$lang_data['message_move'].'" href="javascript:void(0);" onclick="deleteMessage('.$id.')"><i class="fa fa-trash-o"></i></a>
											<!--	<a title="'.$lang_data['message_reply'].'" href="javascript:void(0);"><i class="fa fa-reply"></i></a>
												<a title="'.$lang_data['message_replyall'].'" href="javascript:void(0);"><i class="fa fa-reply-all"></i></a>
												<a title="'.$lang_data['message_forward'].'" href="javascript:void(0);" ><i class="fa fa-long-arrow-right"></i></a>-->
											</div>
										</div>
										<div class="message-content">
											<p>'.$result[0]['message'].'</p>
										</div>';
	   
	   echo $html_r;  
	}
	public function viewOutboxMessage(){
	
	   $this->lang->load('message/message', $this->language);
       $lang_data=$this->lang->language;
	   $id = $this->input->post('id',TRUE); //message id
	   $user_id = $this->session->userdata('user_id');
	   
	   $this->db->select('m.*,u.email');
	   $this->db->from('messages m');
	   $this->db->join('user u', 'u.user_id='.$user_id);
	   $this->db->join('user us', 'm.sender = us.user_id');
	   $this->db->where('m.message_id',$id);
	   $result = $this->db->get()->result_array();
	   $html_r='';
	   $html_r.='<div class="message-header">
											<div class="message-time">
												'.date('d-M-Y h:i A',$result[0]['date_time']).'
											</div>
											<div class="message-from">
'.ucfirst(getUserName($result[0]["sender"])).'&lt;'.$result[0]["email"].'&gt;</div>
											<div class="message-to">
												'.$lang_data["to"].':';
											$rec_data = unserialize($result[0]['receiver']);
											$rec_user = '';
											foreach($rec_data as $rec_val){
										   $rec_user .=ucfirst(getUserName($rec_val)).',&nbsp;';
											}
											
											
										$html_r .=	trim($rec_user, ',&nbsp;').'
											</div>
											<div class="message-subject">
												'.$lang_data['subject'].$result[0]['subject'].'
											</div>
											<div class="message-actions">
	<a title="'.$lang_data['message_move'].'" href="javascript:void(0);" onclick="deleteMessage('.$id.');"><i class="fa fa-trash-o"></i></a>
	<!--											<a title="'.$lang_data['message_reply'].'" href="wsdindex.html#"><i class="fa fa-reply"></i></a>
												<a title="'.$lang_data['message_replyall'].'" href="wsdindex.html#"><i class="fa fa-reply-all"></i></a>
												<a title="'.$lang_data['message_forward'].'" href="wsdindex.html#"><i class="fa fa-long-arrow-right"></i></a>-->
											</div>
										</div>
										<div class="message-content">
											<p>'.$result[0]['message'].'</p>
										</div>';
	   
	   echo $html_r;  
	}
	public function searchdata(){
	 $this->lang->load('message/message', $this->language);
     $lang_data=$this->lang->language;
	 $search_text = $this->input->post('searchdata',TRUE);
	 $uid = $this->session->userdata('user_id');
	 
	 $this->db->select('m.*,u.email,us.profile_image senderimage,us.email senderemail')
	 ->from('messages m')
	 ->like('m.subject',$search_text);
	 $this->db->join('user u', 'u.user_id ='.$uid);
	 $this->db->join('user us', 'm.sender = us.user_id');
	 $this->db->like('m.receiver',$uid);
	 $this->db->where('m.owner',$uid);
	 $this->db->where('m.trash',0);
	 $result = $this->db->get()->result_array();
	 //echo '<pre/>'; print_r($result); die;
	 $html_r='';
	 if(!empty($result))
	 {
	  foreach($result as $rdata)
	  {
	   $sender_user    = ucfirst(getUserName($rdata['sender']));	
	   $subject        = $rdata['subject'];
	   $message        = $rdata['message'];
	   $date_time      = ago($rdata['date_time']);
       $urls = config_item('site_url').'applicationMediaFiles/usersImage';
       $chk_img   = getUserProfileImage($rdata['senderimage'],$urls);
	   
	   if($rdata['receiver_read']!=1)
	   {
	    $readclass = 'read_msg';
	   }
	   else{$readclass = '';}
	   
	  
	   $html_r .= '<a href="javascript:void(0);" onclick="viewMessage('.$rdata['message_id'].')" style="text-decoration:none;">
	   				<li class="messages-item inboxlistecho '.$readclass.'">
	               <img alt="" src="'.$urls.$chk_img.'" class="messages-item-avatar">
				   <span class="messages-item-from">
				    '.$sender_user.'
				   </span>
				   <div class="messages-item-time">
                   <span class="text">'.$date_time.'</span>
                   </div>
				   <span class="messages-item-subject">'.dataLimit($subject,'26').'</span>
				   <span class="messages-item-preview">'.dataLimit($message,'72').'</span>
				   </li>
				   </a>
				   ';
	  }
	 }
	 else{$html_r .='<div class="alert alert-danger">'.$lang_data['message_no_found'].'</div>';}
	 
	 echo $html_r;
	}
	public function searchoutboxdata(){
	   $this->lang->load('message/message', $this->language);
       $lang_data=$this->lang->language;
	   $search_text = $this->input->post('searchdata',TRUE);
	   $user_id = $this->session->userdata('user_id');
	   
	   $this->db->select('m.*')
	   			->from('messages m')
				->join('user u', 'u.user_id='.$user_id)
				->join('user us', 'm.sender = us.user_id')
				->like('m.subject',$search_text)
				->where('m.owner',$user_id)
	            ->where('m.sender',$user_id)
				->where('m.trash',0);
	   $result = $this->db->get()->result_array();
	   $html_r='';
	   if(!empty($result))
	   {
	    foreach($result as $inbxdata)
		{
		 $sender_user    = getUserName($inbxdata['sender']);	
		 $subject        = $inbxdata['subject'];
		 $message        = $inbxdata['message'];
						  
		 $rec_data = unserialize($inbxdata['receiver']);
		 $rec_size = sizeof($rec_data);
		 if($rec_size ==1)
		 {
		  $rec_id = $rec_data[0];
		  $user_data = getUserDetails($rec_id);
				   
		  $urls = config_item('site_url').'applicationMediaFiles/usersImage';
		  $chk_img   = getUserProfileImage($user_data[0]['profile_image'],$urls);
		 }else{$chk_img = '/default.png';}
		 
		 $html_r .= '<a href="javascript:void(0);" onclick="viewMessage('.$inbxdata['message_id'].')" style="text-decoration:none;">
		 			<li class="messages-item">
<img alt="" src="'.config_item('site_url').'applicationMediaFiles/usersImage'.$chk_img.'" class="messages-item-avatar">
					<span class="messages-item-from">';
					$rec_data = unserialize($inbxdata['receiver']);
					$rec_user = '';
					foreach($rec_data as $rec_val){
					if(!empty($rec_val))
					{
					 $rec_user .= ucfirst(getUserName($rec_val));
					}
					$rec_user .= ',';
					}
					
					$html_r .= dataLimit(trim($rec_user, ','),'10');
					$html_r .='</span>
                              <div class="messages-item-time">
                              <span class="text">'.ago($inbxdata['date_time']).'</span>
							  </div>
                         <span class="messages-item-subject">'.dataLimit($subject,'26').'</span>
				         <span class="messages-item-preview">'.dataLimit($message,'72').'</span>
						 </li></a>
							  ';
		}
	   }else{$html_r .='<div class="alert alert-danger">'.$lang_data['message_no_found'].'</div>';}
	   echo $html_r;  
	}
	function __setFormRules($setRulesFor = ''){
		switch($setRulesFor){
			case'compose':				
				$this->form_validation->set_rules('recipienties', 'Recipient', 'trim|required');
				$this->form_validation->set_rules('subject', 'Subject', 'trim|required');
				$this->form_validation->set_rules('message', 'Message', 'trim|required');
				$this->form_validation->set_rules('message_id', 'Thread', 'trim');								
			break;			
			case'sendMail':
				$this->form_validation->set_rules('tomsg', 'Send TO', 'trim|required');
				$this->form_validation->set_rules('frommsgid', 'Sender ID', 'trim|required');
				$this->form_validation->set_rules('msgsubj', 'Subject', 'trim|required');
				$this->form_validation->set_rules('msgbody', 'Message Body', 'trim|required');
			break;
			default:
				$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
				$this->form_validation->set_rules('password', 'Password', 'trim|required');
			break;
		}
		$this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button data-dismiss="alert" class="close">×</button><i class="fa fa-times-circle"></i> ', '</div>');
		
		return $this->form_validation->run();
	}
}
