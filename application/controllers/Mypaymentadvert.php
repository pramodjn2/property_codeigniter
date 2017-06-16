<?php
class Mypaymentadvert extends CI_Controller {  
    var $userID;
	var $message = array();
	public function __construct(){
		parent::__construct();
		$this->load->model(array('common'));
		require_once( APPPATH.'/libraries/joomla/joomla.php' );
    } 
	
	function test(){
		echo '<pre/>';
		print_r($_SESSION); die;
		$jdb = JFactory::getDbo();
		$query = $jdb->getQuery(true);
		
		    $pay_id = 1;
		 //  $dbresults = $this->common->select('paypalPayment', " where 	pay_id = '$pay_id'");
		  
		    $sql = "SELECT * FROM paypalPayment where pay_id = " . $jdb->quote($pay_id); 
			$jdb->setQuery($sql);
			$dbresults = $jdb->loadObjectList();
			print_r($dbresults);

		    // if(!empty($dbresults)){
			
			 
			 $payment_status = 'pramod jain';
			echo $sql = "UPDATE paypalPayment SET order_status = ".$jdb->quote($payment_status)." WHERE pay_id = " . $jdb->quote($pay_id);
			 $jdb->setQuery($sql);
			 $result = $jdb->execute();
			
		
		}
//////////////////////////////Aliasger Start for Paypal//////////////////////////////////////////////////////
	public static function advanceRandomString($length = 16, $UC = true, $LC = true, $N = true, $SC = false){
		$randomString = '';
		//$source = 'abcdefghijklmnopqrstuvwxyz';
		$source = '';
		if ($UC)
			$source .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		if ($LC)
			$source .= 'abcdefghijklmnopqrstuvwxyz';
		if ($N)
			$source .= '1234567890';
		if ($SC)
			$source .= '|@#~$%()=^*+[]{}-_';
		if ($length > 0) {
			$randomString = "";
			$length1 = $length - 1;
			$input = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');  
			$rand = array_rand($input, 1);
			$source = str_split($source, 1);
			for ($i = 1; $i <= $length1; $i++) {
				$num = mt_rand(1, count($source));
				$randomString1 .= $source[$num - 1];
				$randomString = "{$rand}{$randomString1}";
			}
		}
		return $randomString;
	}
 
	public function getPaymentStatus( $ID = 0 ){
		$data = $this->common->select('paypalPayment'," where pay_id = $ID");
		return $data[0]['order_status'];
	}
 
 	public function payWithPaypal($planid,$user_id){
	
	
	
		require_once( APPPATH.'/libraries/joomla/joomla.php' );
		
		if (empty($user_id) || $user_id <= 0 || 
			empty($planid) || $planid <= 0){ Redirect(); return; }
		
		$dbresult = $this->common->select('manage_advertise_plan', " where plan_id = " . $planid);
		if (empty($dbresult) || count($dbresult) <= 0){
			Redirect(); return;
		}
		$planamount = $dbresult[0]['plan_price'];
		$durationindays = $dbresult[0]['dueration'];
		$plan_name = $dbresult[0]['plan_name'];
		

       
		$discount=getdiscountprice($dbresult[0]['discount_type'],$dbresult[0]['price_discount'],$dbresult[0]['plan_price']);

		
		
		if(!empty($discount)){
		$planamount = getnumformat($discount);
		}

		
		$dbresults = $this->common->select('website_setting', " where setting_name in ('paypaltestmode','order_payee_email')");
		$setting = array();
		foreach($dbresults as $dbresult){
			$setting[$dbresult['setting_name']] = $dbresult['setting_value'];
		}
		$websetting = $this->session->userdata('websetting');
		$currency = $websetting['currency'];
		
		 $paypal_mode = $websetting['paypal_mode'];
		
		$paypal_email = $websetting['paypal_live_email'];
		if($paypal_mode == 'sandbox'){
			$paypal_email = $websetting['paypal_sandbox_email'];
		}
		
		
		$ORDER_DETAIL 		= '';
		$paypalMode 		= false; //get_setting('paypaltestmode');
		if ($setting['paypaltestmode'] == 'true'){
			$paypalMode = true;
		}
		$item_name			= $plan_name . ' - Advert Subscription ';
		$carttotal 			= $planamount;
		$order_currency		= $currency; //get_setting('currencycode');
		$order_payee_email 	= $setting['order_payee_email']; // 'shobhit.yadav-facilitator@techlect.com'; //get_setting('paypaluname'); 
		$order_payer_email 	= '';
		$order_status 		= 'INPROGRESS';
		$order_modified 	= date("Y-m-d H:i:s");
		$order_created 		= date("Y-m-d H:i:s");
		$order_unique   	= self::advanceRandomString($length = 48, $UC = true, $LC = true, $N = true, $SC = false);
		
		$end_date = date('Y-m-d H:i:s', strtotime($order_modified.' + '.$durationindays.' days'));
		
		
		$data = array(
		            'user_id'		    => $user_id,
					'mem_plan_id' 		=> $planid,
					'order_amount' 		=> $carttotal,
					'order_currency' 	=> $order_currency,
					'order_payee_email' => $order_payee_email,
					'order_payer_email' => $order_payer_email,
					'order_status' 		=> $order_status,
					'order_modified' 	=> $order_modified,
					'order_created' 	=> $order_created,
					'start_date'        => $order_modified,
					'end_date'          => $end_date,
					'order_unique' 		=> $order_unique,
					'subscribe_type'    => 'advert'
		);
		$orderID = $this->common->data_insert('paypalPayment', $data);
		if( $orderID ):
			$ORDER_DETAIL = array(
				'order_id' 		=> (int)$orderID,
				'order_unique' 	=> $order_unique
			);
			$this->session->set_userdata('ORDER_DETAIL', $ORDER_DETAIL);	
			$paypalData['params'] = array(
			                        'paypal_email' => $paypal_email,
					                'paypal_mode' => $paypal_mode,
									'business' 	=> $order_payee_email,
									'currency' 	=> $order_currency, 
									'amount'   	=> $carttotal,
									'location'  => 'GB',
									'custom'   	=> rawurlencode(json_encode($ORDER_DETAIL)),
									'item_name'	=> $item_name,
									'paypalMode'=> $paypalMode,
									'return_url'=> config_item('base_url'). 'Mypaymentadvert/returnurl/1',
									'cancel_url'=> config_item('base_url'). 'Mypaymentadvert/returnurl/cancel',
									'notify_url'=> config_item('base_url'). 'Mypaymentadvert/processIPN'
			);
			$this->load->view('payment/paypal', $paypalData);
		endif;
	}
	public function returnurl( $payment = 0 ){
		

		//$payment_done = $this->input->post("payment_done", TRUE);
		if( $payment == 1 ):
			//print_r($this->session->userdata('ORDER_DETAIL', '')); die;
			$ORDER_DETAIL 		= $this->session->userdata('ORDER_DETAIL');
			if(empty($ORDER_DETAIL)){
				Redirect();
				}
			
			$this->session->set_userdata('LAST_ORDER_DETAIL', $ORDER_DETAIL);
			$LAST_ORDER_DETAIL 	= $this->session->userdata('LAST_ORDER_DETAIL');
			//print_r($LAST_ORDER_DETAIL);
			$this->session->unset_userdata('ORDER_DETAIL');
			//echo $LAST_ORDER_DETAIL['order_id']; die;
			$orderStatus 		= $this->getPaymentStatus( $LAST_ORDER_DETAIL['order_id'] );
			
			$this->resultpage('Thank you', 'thank  you  for  your  payment your  transaction  has  been  '.$orderStatus.' and  a receipt  of  your  purchase  has  been  mailed  to  you.');
           
		    $fullName = $_REQUEST['first_name'].' '.$_REQUEST['last_name'];
			$firstName = $_REQUEST['first_name'];
			
			
            $seousername = str_replace('&nbsp;', '-', $fullName);
            $data['recieverseo'] = seo_friendly_urls($seousername,'',$_REQUEST['user_id']);
							  
						  
			$data['txnid']=$_REQUEST['txn_id'];
			$data['mc_gross_1']=$_REQUEST['mc_gross_1'];
			$data['mc_currency']=$_REQUEST['mc_currency'];
			$data['fullname'] = ucwords($fullName);
			$data['firstname'] = ucwords($firstName);
			$message = $this->load->view('my_account/message/template/payment_message', $data, TRUE); 
			$toEmail = $_REQUEST['payer_email'];
			
			
			$fromEmail = array('email' => config_item('no_reply'),'name' => config_item('site_name'));
			$subject = ucwords(config_item('site_name').' - Payment');
			$attachment = array();
			$result = sendUserEmailCI($toEmail, $fromEmail, $subject, $message, $attachment); 
		   
		   
		   

			
		elseif( $payment == 'cancel'):
			$this->resultpage('Oops!!', 'Your Payment for Subscription has been cancelled');
		else:
			Redirect();
		endif;
	}
	public function resultpage($page_title, $page_content){
	    $data = array();
		$data['scriptsrc'] = array('assets/plugins/revolution_slider/rs-plugin/js/jquery.themepunch.plugins.min.js', 
									'assets/plugins/revolution_slider/rs-plugin/js/jquery.themepunch.revolution.min.js',
									'assets/plugins/flex-slider/jquery.flexslider.js',
									'assets/plugins/stellar.js/jquery.stellar.min.js',
									'assets/plugins/colorbox/jquery.colorbox-min.js',
									'assets/js/front-end-index.js',
									'assets/plugins/select2/select2.min.js',
									'assets/plugins/jQuery-Knob/js/jquery.knob.js',
									'assets/js/custom/email-subscribe.js');
		
		$where="where page_name='help'";
		$result = $this->common->select('manage_static_pages',$where);
		
		$result[0]['page_name'] = $page_title;
		$result[0]['page_content'] = $page_content;
		$data['result'] = $result;
		$this->load->view('page', $data);
	}
 
	//////////////////////////////////////////////////////////////////////////////////////	
	/////////////          ///        ///          ///        ///    //////  /////////////	
	/////////////  //////  ///  /////////  //////////////  //////  /  /////  /////////////
	/////////////  //////  ///  /////////  //////////////  //////  //  ////  /////////////
	/////////////          ///      /////  ////    //////  //////  ///  ///  /////////////
	/////////////  //////  ///  /////////  //////  //////  //////  ////  //  /////////////
	/////////////  //////  ///  /////////  //////  //////  //////  /////  /  /////////////
	/////////////          ///        ///          ///        ///  //////    /////////////
	//////////////////////////////////////////////////////////////////////////////////////
	public function processIPN(){
		require_once( APPPATH.'/libraries/joomla/joomla.php' );
		//return;
		try { 
			self::addIPNTempEntry();
		} catch ( Exception $e) {
			//// JLog::add('Caught exception(IPN TEMP): ' . $e->getMessage(), // JLog::ALL, 'com_jsystem');
		}
		//////////////////////////////////////////
		$payPalMode = $this->getPayPalMode(); //get_setting('paypaltestmode');
		//////////////////////////////////////////
		
		// STEP 1: read POST data
		// Reading POSTed data directly from $_POST causes serialization issues with array data in the POST.
		// Instead, read raw POST data from the input stream. 
		$raw_post_data 	= file_get_contents('php://input');		
		$raw_post_array = explode('&', $raw_post_data);
		$myPost = array();
		foreach ($raw_post_array as $keyval) {
		  $keyval = explode ('=', $keyval);
		  if (count($keyval) == 2)
			 $myPost[$keyval[0]] = urldecode($keyval[1]);
		}
		// read the IPN message sent from PayPal and prepend 'cmd=_notify-validate'
		$req = 'cmd=_notify-validate';
		if(function_exists('get_magic_quotes_gpc')) {
		   $get_magic_quotes_exists = true;
		} 
		foreach ($myPost as $key => $value) {        
		   if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) { 
				$value = urlencode(stripslashes($value)); 
		   } else {
				$value = urlencode($value);
		   }
		   $req .= "&$key=$value";
		}
		
		// STEP 2: POST IPN data back to PayPal to validate
		if ( $payPalMode == 1 ) :
			$ch = curl_init('https://www.sandbox.paypal.com/cgi-bin/webscr');
		else :
			$ch = curl_init('https://www.paypal.com/cgi-bin/webscr');
		endif;
		
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
		
		// In wamp-like environments that do not come bundled with root authority certificates,
		// please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set 
		// the directory path of the certificate as shown below:
		// curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
		if( !($res = curl_exec($ch)) ) {
			// error_log("Got " . curl_error($ch) . " when processing IPN data");
			curl_close($ch);
			exit;
		}
		curl_close($ch);
		 
		// STEP 3: Inspect IPN validation result and act accordingly
		if ( strcmp ($res, "VERIFIED") == 0 ) {
			// The IPN is verified, process it:
			// check whether the payment_status is Completed
			// check that txn_id has not been previously processed
			// check that receiver_email is your Primary PayPal email
			// check that payment_amount/payment_currency are correct
			// process the notification
			// assign posted variables to local variables
			$item_name 			= $_POST['item_name'];
			$item_number 		= $_POST['item_number'];
			$payment_status 	= $_POST['payment_status'];
			$payment_amount 	= $_POST['mc_gross'];
			$payment_currency 	= $_POST['mc_currency'];
			$TXN 				= $_POST['txn_id'];
			$receiver_email 	= $_POST['receiver_email'];
			$payer_email 		= $_POST['payer_email'];
			
			/////////////////////////////////////////////////////////////
			self::doIPNOperations();
			/////////////////////////////////////////////////////////////
			
			//if( $payment_status == 'Completed' ):
				//PSB::updatePaymentStatus( $TXN, $receiver_email, $payer_email );
			//endif;
			// IPN message values depend upon the type of notification sent.
			// To loop through the &_POST array and print the NV pairs to the screen:
			foreach($_POST as $key => $value) {
			  echo $key." = ". $value."<br>";
			}
		} else if (strcmp ($res, "INVALID") == 0) {
			// IPN invalid, log for manual investigation
			// echo "The response from IPN was: <b>" .$res ."</b>";
			// JLog::add('Caught INAVLID IPN: The response from IPN was - ' . $res, // JLog::ALL, 'com_jsystem');
			
			$VOID_REASON = 'Invalid IPN - ( ' . $res . ' ) ( Line Number - ' . __LINE__ . ' )';
			$this->doIPNVoidEntry($VOID_REASON);
		}		
	}
	
	private static function addIPNTempEntry(){
		$jdb = JFactory::getDbo();
		$object = new stdClass();
		$object->rawdata = serialize($_REQUEST);
		$object->rawdata = $object->rawdata;
		$result = $jdb->insertObject('jorders_ipn_track_temp', $object);
		
		$serialize = serialize($_REQUEST);
		$st = unserialize($serialize);
		$st = $_REQUEST;
		$payment_status = $st['payment_status'];

		if($st['payer_status'] == 'verified'){
		   $custom =  json_decode(rawurldecode($st['custom']));
		   $pay_id = $custom->order_id;	
		  //  $pay_id = 1;
		  //  $dbresults = $this->common->select('paypalPayment', " where 	pay_id = '$pay_id'");
		   
		    $sql = "SELECT * FROM paypalPayment where pay_id = " . $jdb->quote($pay_id); 
			$jdb->setQuery($sql);
			$dbresults = $jdb->loadObjectList();

		   if(!empty($dbresults)){
			 $data = array('order_status' => $payment_status);
			 $where = array('pay_id' => $pay_id);
			 $payer_email = $st['payer_email'];
			 $txn_id = $st['txn_id'];
			 
			 $sql = "UPDATE paypalPayment SET txn_id = ".$jdb->quote($txn_id).",order_payer_email = ".$jdb->quote($payer_email).", order_status = ".$jdb->quote($payment_status)." WHERE pay_id = " . $jdb->quote($pay_id);
			 $jdb->setQuery($sql);
			 $result = $jdb->execute();
			}
		 }
		
		//mail("pramod.jain@techlect.com",'test',$st['custom']);
		
		return $result;
		
	}
	
	
		  
		
		 
		 
	private function doIPNVoidEntry( $VOID_REASON = 'Void Reason Unknown - Check Raw Data or Hit Raw Data for more Help.'){
		$jInput = new JInput();
		/*
		(`payment_record_id`, `payment_record_type`, `order_id`, `order_unique`, `amount`, `currency`, `tax`, `payer_status`, `payer_email`, `receiver_email`, `payer_id`, `first_name`, `last_name`, `address_status`, `address_name`, `address_street`, `address_country_code`, `address_zip`, `address_city`, `address_state`, `address_country`, `residence_country`, `mc_gross`, `mc_shipping`, `mc_handling`, `mc_currency`, `notify_version`, `custom`, `num_cart_items`, `verify_sign`, `txn_id`, `txn_type`, `transaction_subject`, `payment_gross`, `pending_reason`, `payment_type`, `payment_date`, `payment_status`, ` 	merchant_return_link`, `charset`, `test_ipn`, `auth`, `reason_remark`, `rawdata`, `modified`, `created`, `state`)
		*/
		// Create an object for the record we are going to update.
		$object = new stdClass();
		$customObject = json_decode( rawurldecode ( $jInput->get('custom', '[]', 'filter') ) );
		 
		// Must be a valid primary key value.
		
		//$object->payment_record_id		= $jInput->get('payment_record_id', '', 'filter');
		$object->payment_record_type	= $jInput->get('payment_record_type', 'PAYMENT_PAYPAL_STANDARD', 'filter');
		
		$object->payment_order_id		= (int)$customObject->order_id;
		$object->payment_order_unique	= $customObject->order_unique;
		$object->amount					= $jInput->get('mc_gross', 0.00, 'filter');
		$object->currency				= $jInput->get('mc_currency', '', 'filter');
		
		$object->tax 					= $jInput->get('tax', 0.00, 'filter');
		//$object->tax1 				= $jInput->get('tax1', '', 'filter');
		$object->payer_status 			= $jInput->get('payer_status', '', 'filter');
		$object->payer_email 			= $jInput->get('payer_email', '', 'filter');
		$object->receiver_email			= $jInput->get('receiver_email', '', 'filter');
		$object->payer_id 				= $jInput->get('payer_id', '', 'filter');
		$object->first_name 			= $jInput->get('first_name', '', 'filter');
		$object->last_name 				= $jInput->get('last_name', '', 'filter');
		$object->address_status 		= $jInput->get('address_status', '', 'filter');
		$object->address_name 			= $jInput->get('address_name', '', 'filter');
		$object->address_street			= $jInput->get('address_street', '', 'filter');
		$object->address_country_code	= $jInput->get('address_country_code', '', 'filter');
		$object->address_country		= $jInput->get('address_country', '', 'filter');
		$object->address_zip 			= $jInput->get('address_zip', '', 'filter');
		$object->address_city 			= $jInput->get('address_city', '', 'filter');
		$object->address_state 			= $jInput->get('address_state', '', 'filter');
		$object->residence_country 		= $jInput->get('residence_country', '', 'filter');
		$object->mc_gross				= $jInput->get('mc_gross', 0.00, 'filter');
		$object->mc_shipping 			= $jInput->get('mc_shipping', 0.00, 'filter');
		$object->mc_handling 			= $jInput->get('mc_handling', 0.00, 'filter');
		//$object->mc_handling1 		= $jInput->get('mc_handling1', '', 'filter');
		//$object->mc_shipping1 		= $jInput->get('mc_shipping1', '', 'filter');
		//$object->mc_gross_1 			= $jInput->get('mc_gross_1', '', 'filter');
		$object->mc_currency 			= $jInput->get('mc_currency', '', 'filter');
		$object->notify_version 		= $jInput->get('notify_version', 0.0, 'filter');
		$object->custom	 				= $jInput->get('custom', '', 'filter');
		$object->num_cart_items 		= $jInput->get('num_cart_items', 0, 'filter');
		//$object->item_number1 		= $jInput->get('item_number1', '', 'filter');
		//$object->item_name1 			= $jInput->get('item_name1', '', 'filter');
		//$object->quantity1 				= $jInput->get('quantity1', '', 'filter');
		$object->verify_sign 			= $jInput->get('verify_sign', '', 'filter');
		$object->protection_eligibility = $jInput->get('protection_eligibility', '', 'filter');
		$object->txn_id 				= $jInput->get('txn_id', '', 'filter');
		$object->txn_type 				= $jInput->get('txn_type', '', 'filter');
		$object->transaction_subject 	= $jInput->get('transaction_subject', '', 'filter');
		$object->payment_gross 			= $jInput->get('payment_gross', 0.00, 'filter');
		$object->pending_reason			= $jInput->get('pending_reason', '', 'filter');
		$object->payment_type 			= $jInput->get('payment_type', '', 'filter');
		$object->payment_date 			= $jInput->get('payment_date', '', 'filter');
		$object->payment_status			= $jInput->get('payment_status', 'PAYMENT_STATUS_BLANK', 'filter');
		$object->merchant_return_link 	= $jInput->get('merchant_return_link', '', 'filter');
		$object->charset 				= $jInput->get('charset', '', 'filter');
		$object->test_ipn 				= $jInput->get('test_ipn', '', 'filter');
		$object->auth					= $jInput->get('auth', '', 'filter');
		$object->reason_remark			= '';
		$object->rawdata				= serialize($_REQUEST);
		$object->modified				= date("Y-m-d H:i:s");
		$object->created				= date("Y-m-d H:i:s");
		$object->hits					= 1;
		$object->hit					= date("Y-m-d H:i:s");
		$object->hitrawdata				= serialize($_REQUEST);
		$object->void_reason			= $VOID_REASON;
		$object->mode					= (int)$this->getPayPalMode();
		$object->state					= 1;
		return $result = JFactory::getDbo()->insertObject('jorders_ipn_track_void', $object);
	}
	private function getPayPalMode(){
		$dbresults = $this->common->select('website_setting', " where setting_name = 'paypal_mode'");
		if (!empty($dbresults)){
			if ($dbresults[0]['setting_value'] == 'live'){
				return 1;
			}
		}
		return 0;
	}
	private static function debugMode(){
		$plugin   		= JPluginHelper::getPlugin('psbpayment', 'psbpaypal');
		$pluginParams  	= new JRegistry();
		$pluginParams->loadString($plugin->params);
		return $debugMode 	= (int)$pluginParams->get('psb_debug_mode', 1);	
	}
	private static function doIPNOperations(){
		$jInput = new JInput();
		$customObject 		= json_decode( rawurldecode ( $jInput->get('custom', '[]', 'filter') ) );
		$IPNPaymentStatus	= $jInput->get('payment_status', 'PAYMENT_STATUS_BLANK', 'filter');
		$orderID			= $customObject->order_id;
		$orderUnique		= $customObject->order_unique;
		if ( $orderID != '' && $orderUnique != '' ) :
			$order = self::getOrder( $orderID, $orderUnique );
			if ( $order ) :
				$validateOrderVoid = self::doValidateOrderVoid($order);
				if( $validateOrderVoid === true ) :
					if ( $order->order_status == 'INPROGRESS' && $order->order_status != '' ) :
						if ( self::doCreatePaymentRecord() ) :
							if ( self::doUpdateOrder() ) :
								if ( self::doUpdateUserTransaction() ) :
									// USER ACCOUNT UPDATED SUCCESSFULLY
									if ( $debugMode == 1 ) : 
										// JLog::add('Caught exception(IPN): User Account Updated Successfully', // JLog::ALL, 'com_jsystem');
									endif; 
								else :
									if ( $debugMode == 1 ) : 
										// JLog::add('Caught exception(IPN): Error while updating user account', // JLog::ALL, 'com_jsystem');
									endif; 
									// CREATING VOID TRANSACTION ENTRY
									$VOID_REASON = 'Error while updating user account ( Line Number - ' . __LINE__ . ' )';
									$this->doIPNVoidEntry($VOID_REASON);
								endif;
								
							else :
								if ( $debugMode == 1 ) : 
									// JLog::add('Caught exception(IPN): Error while updating order', // JLog::ALL, 'com_jsystem');
								endif; 
								// CREATING VOID TRANSACTION ENTRY
								$VOID_REASON = 'Error while updating order ( Line Number - ' . __LINE__ . ' )';
								$this->doIPNVoidEntry($VOID_REASON);
							endif;
						else :
							if ( $debugMode == 1 ) : 
								// JLog::add('Caught exception(IPN): Error while create Payment Record', // JLog::ALL, 'com_jsystem');
							endif; 
							// CREATING VOID TRANSACTION ENTRY
							$VOID_REASON = 'Error while create Payment Record ( Line Number - ' . __LINE__ . ' )';
							$this->doIPNVoidEntry($VOID_REASON);
						endif;
					else :
						// TO DO - UPDATE  
						if ( $order->order_status == '' ) :
							if ( $debugMode == 1 ) : 
								// JLog::add('Caught exception(IPN): Order Status Missing', // JLog::ALL, 'com_jsystem');
							endif; 
							// CREATING VOID TRANSACTION ENTRY
							$VOID_REASON = 'Order Status Missing ( Line Number - ' . __LINE__ . ' )';
							$this->doIPNVoidEntry($VOID_REASON);
						else :
							if ( $order->order_status != $IPNPaymentStatus ) :
								// DIFFERENT PAYMENT STATUS FOUND - UPDATING PAYMENT RECORD
								$paymentRecord = self::getPaymentRecord( $orderID, $orderUnique );
								if ( $paymentRecord ) :
									// PAYMENT RECORD FOUND FOR CURRENT ORDER - UPDATING
									///////////////////////////////////////////////
									// TO UPDATE HITS, HIT TIME, PAYMENT STATUS
									///////////////////////////////////////////////
									if ( self::doUpdatePaymentRecord( $paymentRecordID = (int)$paymentRecord->payment_record_id, $paymentRecordType = 'PAYMENT_PAYPAL_STANDARD' ) ) :
										// PAYMENT RECORD UPDATED SUCCESSFULLY
										if ( $debugMode == 1 ) : 
											// JLog::add('Caught exception(IPN): Payment Record Updated Successfully', // JLog::ALL, 'com_jsystem');
										endif; 
										// UPDATATING ORDER ...
										if ( self::doUpdateOrder() ) :
											// GROUP UPDATED SUCCESSFULLY
											if ( $debugMode == 1 ) : 
												// JLog::add('Caught exception(IPN): Order Updated Successfully', // JLog::ALL, 'com_jsystem');
											endif; 
											// UPDATATING USER ACCOUNT - PREMIUM ACCOUNT STATUS ...
											if ( self::doUpdateUserTransaction() ) :
												// USER ACCOUNT UPDATED SUCCESSFULLY
												if ( $debugMode == 1 ) : 
													// JLog::add('Caught exception(IPN): User Account Updated Successfully', // JLog::ALL, 'com_jsystem');
												endif; 
											else :
												if ( $debugMode == 1 ) : 
													// JLog::add('Caught exception(IPN): Error while updating user account', // JLog::ALL, 'com_jsystem');
												endif; 
												// CREATING VOID TRANSACTION ENTRY
												$VOID_REASON = 'Error while updating user account ( Line Number - ' . __LINE__ . ' )';
												$this->doIPNVoidEntry($VOID_REASON);
											endif;
										else :
											if ( $debugMode == 1 ) : 
												// JLog::add('Caught exception(IPN): Error while updating order', // JLog::ALL, 'com_jsystem');
											endif; 
											// CREATING VOID TRANSACTION ENTRY
											$VOID_REASON = 'Error while updating order ( Line Number - ' . __LINE__ . ' )';
											$this->doIPNVoidEntry($VOID_REASON);
										endif;
									else :
										if ( $debugMode == 1 ) : 
											// JLog::add('Caught exception(IPN): Error while updating Payment Record', // JLog::ALL, 'com_jsystem');
										endif; 
										// CREATING VOID TRANSACTION ENTRY
										$VOID_REASON = 'Error while updating Payment Record ( Line Number - ' . __LINE__ . ' )';
										$this->doIPNVoidEntry($VOID_REASON);
									endif;
								else :
									if ( $debugMode == 1 ) : 
										// JLog::add('Caught exception(IPN): No Previous Payment record found with current Order', // JLog::ALL, 'com_jsystem');
									endif; 
									// NO PAYMENT RECORD FOUND FOR CURRENT ORDER - CREATING
									if ( self::doCreatePaymentRecord() ) :
										// PAYMENT RECORD CREATED SUCCESSFULLY
										// UPDATATING ORDER ...
										if ( $debugMode == 1 ) : 
											// JLog::add('Caught exception(IPN): Payment Record Created Successfully', // JLog::ALL, 'com_jsystem');
										endif; 
										if ( self::doUpdateOrder() ) :
											// GROUP UPDATED SUCCESSFULLY
											if ( $debugMode == 1 ) : 
												// JLog::add('Caught exception(IPN): Order Updated Successfully', // JLog::ALL, 'com_jsystem');
											endif; 
											// UPDATATING USER ACCOUNT - PREMIUM ACCOUNT STATUS ...
											if ( self::doUpdateUserTransaction() ) :
												// USER ACCOUNT UPDATED SUCCESSFULLY
												if ( $debugMode == 1 ) : 
													// JLog::add('Caught exception(IPN): User Account Updated Successfully', // JLog::ALL, 'com_jsystem');
												endif; 
											else :
												if ( $debugMode == 1 ) : 
													// JLog::add('Caught exception(IPN): Error while updating user account', // JLog::ALL, 'com_jsystem');
												endif; 
												// CREATING VOID TRANSACTION ENTRY
												$VOID_REASON = 'Error while updating user account ( Line Number - ' . __LINE__ . ' )';
												$this->doIPNVoidEntry($VOID_REASON);
											endif;
										else :
											if ( $debugMode == 1 ) : 
												// JLog::add('Caught exception(IPN): Error while updating order', // JLog::ALL, 'com_jsystem');
											endif; 
											// CREATING VOID TRANSACTION ENTRY
											$VOID_REASON = 'Error while updating order ( Line Number - ' . __LINE__ . ' )';
											$this->doIPNVoidEntry($VOID_REASON);
										endif;
									else :
										if ( $debugMode == 1 ) : 
											// JLog::add('Caught exception(IPN): Error while create Payment Record', // JLog::ALL, 'com_jsystem');
										endif; 
										// CREATING VOID TRANSACTION ENTRY
										$VOID_REASON = 'Error while create Payment Record ( Line Number - ' . __LINE__ . ' )';
										$this->doIPNVoidEntry($VOID_REASON);
									endif;
								endif;
							else :
								// Order Status Allready Updated
								if ( $debugMode == 1 ) : 
									// JLog::add('Caught exception(IPN): Order Status Allready Up to Date', // JLog::ALL, 'com_jsystem');
								endif; 
								
								// CREATING VOID TRANSACTION ENTRY
								$VOID_REASON = 'Order Status Allready Up to Date ( Line Number - ' . __LINE__ . ' )';
								$this->doIPNVoidEntry($VOID_REASON);
	
								//////////////////////////////////////////////////////////////////////////
								// TO DO - UPDATE HITS, HIT TIME AND HIT RAW DATA ONLY - AT PAYMENT RECORD
								//////////////////////////////////////////////////////////////////////////
							endif;
						endif;
					endif;
				else :
					if ( $debugMode == 1 ) : 
						// JLog::add('Caught exception(IPN): Order - Void Validation - Failed( ' . $validateOrderVoid . ' )', // JLog::ALL, 'com_jsystem');
					endif; 
					$VOID_REASON = 'Order - Void Validation - Failed( ' . $validateOrderVoid . ' ) ( Line Number - ' . __LINE__ . ' )';
					$this->doIPNVoidEntry($VOID_REASON);
				endif;
			else :
				if ( $debugMode == 1 ) : 
					// JLog::add('Caught exception(IPN): Order Detail Invalid ', // JLog::ALL, 'com_jsystem');
				endif; 
				// CREATING VOID TRANSACTION ENTRY
				$VOID_REASON = 'Order Detail Invalid ( Line Number - ' . __LINE__ . ' )';
				$this->doIPNVoidEntry($VOID_REASON);
			endif;
		else :
			if ( $debugMode == 1 ) : 
				// JLog::add('Caught exception(IPN): Order Detail Missing ', // JLog::ALL, 'com_jsystem');
			endif; 
			
			// CREATING VOID TRANSACTION ENTRY
			if (  $orderID == '' && $orderUnique == '' ) :
				$VOID_REASON = 'Order Detail(Order ID and Order Unique) Missing ( Line Number - ' . __LINE__ . ' )';
			elseif ( $orderID == '' ) :
				$VOID_REASON = 'Order Detail(Order ID) Missing ( Line Number - ' . __LINE__ . ' )';
			elseif( $orderUnique == '' ) :
				$VOID_REASON = 'Order Detail(Order Unique) Missing ( Line Number - ' . __LINE__ . ' )';
			else :
				$VOID_REASON = 'Order Detail Missing ( Line Number - ' . __LINE__ . ' )';
			endif;
			$this->doIPNVoidEntry($VOID_REASON);
		endif;
		if ( $debugMode == 1 ) : 
			// JLog::add('Notice (IPN): ------- IPN OPERRATIONS END -------- ', // JLog::ALL, 'com_jsystem');
		endif; 
		return true;
	}
	
	private static function doValidateOrderVoid($order) {
		$jInput = new JInput();
		//$jInput 			= new JInput();
		$IPNAmount			= $jInput->get('mc_gross', 0.00, 'filter');
		$IPNCurrency		= $jInput->get('mc_currency', '', 'filter');
		$IPNReceiverEmail	= $jInput->get('receiver_email', '', 'filter');
		
		$voidStatus = false;
		$voidMessages = array();
		///////////////////////////////////////////////////////
		if ( $IPNAmount == '' || $IPNAmount == 0 ) : 
			$voidStatus 	= true;
			$voidMessages[] = 'IPN Amount Missing/Zero ( ' . $IPNAmount . ' )';
		elseif ( $IPNAmount == $order->order_amount || number_format($IPNAmount,2) == number_format($order->order_amount,2) ) : 
			$voidMessages[] = 'IPN & Order Amount Confirmed Valid.';			
		else :
			$voidStatus 	= true;
			$voidMessages[] = 'IPN & Order Amount Mismatch ( Order Amount - ' . $order->order_amount . ' and IPN Amount - ' . $IPNAmount . ' )';
		endif;
		///////////////////////////////////////////////////////
		///////////////////////////////////////////////////////
		if ( $IPNCurrency == '' ) : 
			$voidStatus 	= true;
			$voidMessages[] = 'IPN Currenncy Missing ( ' . $IPNCurrency . ' )';
		elseif ( $IPNCurrency == $order->order_currency ) : 
			$voidMessages[] = 'IPN & Order Currency Confirmed Valid.';					
		else :
			$voidStatus 	= true;
			$voidMessages[] = 'IPN & Order Currency Mismatch ( Order Currency - ' . $order->order_currency . ' and IPN Currency - ' . $IPNCurrency . ' )';
		endif;
		///////////////////////////////////////////////////////
		///////////////////////////////////////////////////////
		if ( $IPNReceiverEmail == '' ) : 
			$voidStatus 	= true;
			$voidMessages[] = 'IPN Receiver Email/Payee Email Missing ( ' . $IPNReceiverEmail . ' )';
		elseif ( $IPNReceiverEmail == $order->order_payee_email ) : 
			// IPN RECEIVER/PAYEE EMAIL VALID			
		else :
			$voidStatus 	= true;
			$voidMessages[] = 'IPN & Order Receiver Email/Payee Email Mismatch ( Order Payee Email - ' . $order->order_payee_email . ' and IPN Receiver Email - ' . $IPNReceiverEmail . ' )';
		endif;
		///////////////////////////////////////////////////////
		
		if ( $voidStatus ) :
			return implode(', ', $voidMessages);
		else :
			return true;		
		endif;
		
	}
	private static function doCreatePaymentRecord(){
		return self::doSavePayPalTXN();
	}
	private static function doUpdatePaymentRecord( $paymentRecordID = 0, $paymentRecordType = 'PAYMENT_PAYPAL_STANDARD'){
		return self::doUpdatePayPalTXN( $paymentRecordID, $paymentRecordType );
	}
	private static function doUpdatePayPalTXN(  $paymentRecordID = 0, $paymentRecordType = 'PAYMENT_PAYPAL_STANDARD'){
		//$jInput = new JInput();
		 $jInput = new JInput();		
		/*
		(`payment_record_id`, `payment_record_type`, `order_id`, `order_unique`, `amount`, `currency`, `tax`, `payer_status`, `payer_email`, `receiver_email`, `payer_id`, `first_name`, `last_name`, `address_status`, `address_name`, `address_street`, `address_country_code`, `address_zip`, `address_city`, `address_state`, `address_country`, `residence_country`, `mc_gross`, `mc_shipping`, `mc_handling`, `mc_currency`, `notify_version`, `custom`, `num_cart_items`, `verify_sign`, `txn_id`, `txn_type`, `transaction_subject`, `payment_gross`, `pending_reason`, `payment_type`, `payment_date`, `payment_status`, ` 	merchant_return_link`, `charset`, `test_ipn`, `auth`, `reason_remark`, `rawdata`, `modified`, `created`, `state`)
		*/
		// Create an object for the record we are going to update.
		$object = new stdClass();
		$customObject = json_decode( rawurldecode ( $jInput->get('custom', '[]', 'filter') ) );
		 
		// Must be a valid primary key value.
		
		$object->payment_record_id		= (int)$paymentRecordID;
		$object->payment_record_type	= $paymentRecordType;
		
		$object->payment_order_id		= (int)$customObject->order_id;
		$object->payment_order_unique	= $customObject->order_unique;
		$object->amount					= $jInput->get('mc_gross', 0.00, 'filter');
		$object->currency				= $jInput->get('mc_currency', '', 'filter');
		
		$object->tax 					= $jInput->get('tax', 0.00, 'filter');
		//$object->tax1 				= $jInput->get('tax1', '', 'filter');
		$object->payer_status 			= $jInput->get('payer_status', '', 'filter');
		$object->payer_email 			= $jInput->get('payer_email', '', 'filter');
		$object->receiver_email			= $jInput->get('receiver_email', '', 'filter');
		$object->payer_id 				= $jInput->get('payer_id', '', 'filter');
		$object->first_name 			= $jInput->get('first_name', '', 'filter');
		$object->last_name 				= $jInput->get('last_name', '', 'filter');
		$object->address_status 		= $jInput->get('address_status', '', 'filter');
		$object->address_name 			= $jInput->get('address_name', '', 'filter');
		$object->address_street			= $jInput->get('address_street', '', 'filter');
		$object->address_country_code	= $jInput->get('address_country_code', '', 'filter');
		$object->address_country		= $jInput->get('address_country', '', 'filter');
		$object->address_zip 			= $jInput->get('address_zip', '', 'filter');
		$object->address_city 			= $jInput->get('address_city', '', 'filter');
		$object->address_state 			= $jInput->get('address_state', '', 'filter');
		$object->residence_country 		= $jInput->get('residence_country', '', 'filter');
		$object->mc_gross				= $jInput->get('mc_gross', 0.00, 'filter');
		$object->mc_shipping 			= $jInput->get('mc_shipping', 0.00, 'filter');
		$object->mc_handling 			= $jInput->get('mc_handling', 0.00, 'filter');
		//$object->mc_handling1 		= $jInput->get('mc_handling1', '', 'filter');
		//$object->mc_shipping1 		= $jInput->get('mc_shipping1', '', 'filter');
		//$object->mc_gross_1 			= $jInput->get('mc_gross_1', '', 'filter');
		$object->mc_currency 			= $jInput->get('mc_currency', '', 'filter');
		$object->notify_version 		= $jInput->get('notify_version', 0.0, 'filter');
		$object->custom	 				= $jInput->get('custom', '', 'filter');
		$object->num_cart_items 		= $jInput->get('num_cart_items', 0, 'filter');
		//$object->item_number1 		= $jInput->get('item_number1', '', 'filter');
		//$object->item_name1 			= $jInput->get('item_name1', '', 'filter');
		//$object->quantity1 				= $jInput->get('quantity1', '', 'filter');
		$object->verify_sign 			= $jInput->get('verify_sign', '', 'filter');
		$object->protection_eligibility = $jInput->get('protection_eligibility', '', 'filter');
		$object->txn_id 				= $jInput->get('txn_id', '', 'filter');
		$object->txn_type 				= $jInput->get('txn_type', '', 'filter');
		$object->transaction_subject 	= $jInput->get('transaction_subject', '', 'filter');
		$object->payment_gross 			= $jInput->get('payment_gross', 0.00, 'filter');
		$object->pending_reason			= $jInput->get('pending_reason', '', 'filter');
		$object->payment_type 			= $jInput->get('payment_type', '', 'filter');
		$object->payment_date 			= $jInput->get('payment_date', '', 'filter');
		$object->payment_status			= $jInput->get('payment_status', 'PAYMENT_STATUS_BLANK', 'filter');
		$object->merchant_return_link 	= $jInput->get('merchant_return_link', '', 'filter');
		$object->charset 				= $jInput->get('charset', '', 'filter');
		$object->test_ipn 				= $jInput->get('test_ipn', '', 'filter');
		$object->auth					= $jInput->get('auth', '', 'filter');
		$object->reason_remark			= '';
		$object->rawdata				= serialize($_REQUEST);
		$object->modified				= date("Y-m-d H:i:s");
		//$object->created				= date("Y-m-d H:i:s");
		$object->hits					= 'hits + 1';
		$object->hit					= date("Y-m-d H:i:s");
		$object->hitrawdata				= serialize($_REQUEST);
		//$object->state					= 1;
		return $result = JFactory::getDbo()->updateObject('jorders_payments_provider', $object, 'payment_record_id');
		//return $result = JFactory::getDbo()->insertObject('jorders_payments_provider', $object);
	}
	private static function doUpdateOrder(){
		$jInput = new JInput();
		mail('ali.asger@techlect.com','test paypal' . __LINE__,'Coming ' . __LINE__,'');
		$customObject 		= json_decode( rawurldecode ( $jInput->get('custom', '[]', 'filter') ) );
		$IPNPaymentStatus	= $jInput->get('payment_status', 'PAYMENT_STATUS_BLANK', 'filter');
		$IPNPayerEmail		= $jInput->get('payer_email', '', 'filter');
		if ( $customObject->order_id == '' || $customObject->order_unique == '' ) return false;
		
		$db = JFactory::getDbo();		 
		$query = $db->getQuery(true);
		 
		// Fields to update.
		$fields = array(
			$db->quoteName('order_status') . ' = ' . $db->quote($IPNPaymentStatus),
			$db->quoteName('order_payer_email') . ' = ' . $db->quote($IPNPayerEmail),
			$db->quoteName('order_modified') . ' = NOW()'
		);
		 
		// Conditions for which records should be updated.
		$conditions = array(
			$db->quoteName('pay_id') . ' = ' .  (int)$customObject->order_id, 
			$db->quoteName('order_unique') . ' = ' . $db->quote($customObject->order_unique)
		);
		 
		$query->update($db->quoteName('paypalPayment'))->set($fields)->where($conditions);
		$db->setQuery($query);
		return $result = $db->execute();
	}
	private static function doValidatePremiumAccount(){ 
		
	}
	private static function userHasPremiumAccount( $orderID = 0 ){ 
		if ( $orderID == 0 || $orderID == '' ) return false;
		
		$db = JFactory::getDbo();		
		$query = $db->getQuery(true);
		$query->select('op.*');
		$query->from($db->quoteName( 'user_subscribe_information', 'op'));
		$query->where($db->quoteName('op.payment_id') . ' = ' . (int)$orderID );
		$db->setQuery($query);
		$userProfile = $db->loadObject();
		
		$userHasPlanActivated 	= (int)$userProfile->user_plan_status;
		$userPlanVilidity 		= strtotime( $userProfile->end_date );
		$now					= strtotime( date('Y-m-d H:i:s') );
		
		if ( $userHasPlanActivated === 1 && $userPlanVilidity > $now ) :
			return true;
		else :
			return false;
		endif; 
	}
	
	private static function getPremiumAccountValidity( $order = '' ){ 
		if ( $order == '' ) return false;
		
		////////////////////////////////////////////////////////////////////////////////////////////////////////
		$userOrderVilidityDuration		= (int)abs($order->order_duration);
		////////////////////////////////////////////////////////////////////////////////////////////////////////
		$userOrderVilidityDurationMDY	= strtolower( $order->order_duration_mdy );
		////////////////////////////////////////////////////////////////////////////////////////////////////////
		$nowDatetime					= date('Y-m-d H:i:s');
		////////////////////////////////////////////////////////////////////////////////////////////////////////
		//$futureDate=date('Y-m-d', strtotime('+1 year', strtotime($startDate)) );
		$increaseDatetimeBy				= '+' . $userOrderVilidityDuration . ' ' . $userOrderVilidityDurationMDY;
		return $futureDatetime 			= date('Y-m-d H:i:s', strtotime($increaseDatetimeBy, strtotime($nowDatetime)) );
		////////////////////////////////////////////////////////////////////////////////////////////////////////
		/*******************************************************************************************************
		if (  $userOrderVilidityDurationMDY == 'year' ) :
			$increaseDatetimeBy	= '+' . $userOrderVilidityDuration . ' ' . $userOrderVilidityDurationMDY;
			$futureDatetime 	= date('Y-m-d H:i:s', strtotime($increaseDatetimeBy, strtotime($nowDatetime)) );
		elseif( $userOrderVilidityDurationMDY  == 'month'):
			$increaseDatetimeBy	= '+' . $userOrderVilidityDuration . ' ' . $userOrderVilidityDurationMDY;
			$futureDatetime 	= date('Y-m-d H:i:s', strtotime($increaseDatetimeBy, strtotime($nowDatetime)) );
		elseif( $userOrderVilidityDurationMDY  == 'day'):
			$increaseDatetimeBy	= '+' . $userOrderVilidityDuration . ' ' . $userOrderVilidityDurationMDY;
			$futureDatetime 	= date('Y-m-d H:i:s', strtotime($increaseDatetimeBy, strtotime($nowDatetime)) );
		endif;					
		********************************************************************************************************/
		////////////////////////////////////////////////////////////////////////////////////////////////////////
	}
	private static function XuserHasPremiumAccount(){ 
		////////////////////////////////////////////////////////////////////////////////////////////////////////
		$userHasPlanActivated 			= (int)$userProfile->sp_user_plan;
		////////////////////////////////////////////////////////////////////////////////////////////////////////
		$userPlanVilidity 				= $userProfile->sp_user_plan_validity;
		////////////////////////////////////////////////////////////////////////////////////////////////////////
		$userOrderVilidityDuration		= (int)abs($order->order_duration);
		////////////////////////////////////////////////////////////////////////////////////////////////////////
		$userOrderVilidityDurationMDY	= strtolower( $order->order_duration_mdy );
		////////////////////////////////////////////////////////////////////////////////////////////////////////
		$nowDatetime					= date('Y-m-d H:i:s');
		////////////////////////////////////////////////////////////////////////////////////////////////////////
		//$futureDate=date('Y-m-d', strtotime('+1 year', strtotime($startDate)) );
		$increaseDatetimeBy				= '+' . $userOrderVilidityDuration . ' ' . $userOrderVilidityDurationMDY;
		$futureDatetime 				= date('Y-m-d H:i:s', strtotime($increaseDatetimeBy, strtotime($nowDatetime)) );
		////////////////////////////////////////////////////////////////////////////////////////////////////////
		/*******************************************************************************************************
		if (  $userOrderVilidityDurationMDY == 'year' ) :
			$increaseDatetimeBy	= '+' . $userOrderVilidityDuration . ' ' . $userOrderVilidityDurationMDY;
			$futureDatetime 	= date('Y-m-d H:i:s', strtotime($increaseDatetimeBy, strtotime($nowDatetime)) );
		elseif( $userOrderVilidityDurationMDY  == 'month'):
			$increaseDatetimeBy	= '+' . $userOrderVilidityDuration . ' ' . $userOrderVilidityDurationMDY;
			$futureDatetime 	= date('Y-m-d H:i:s', strtotime($increaseDatetimeBy, strtotime($nowDatetime)) );
		elseif( $userOrderVilidityDurationMDY  == 'day'):
			$increaseDatetimeBy	= '+' . $userOrderVilidityDuration . ' ' . $userOrderVilidityDurationMDY;
			$futureDatetime 	= date('Y-m-d H:i:s', strtotime($increaseDatetimeBy, strtotime($nowDatetime)) );
		endif;					
		********************************************************************************************************/
		////////////////////////////////////////////////////////////////////////////////////////////////////////
		//strtotime($datetimeStr);
		$userHasPlanVilidity = 0;
		if ( $userPlanVilidity > $userOrderVilidity ) :
			$userHasPlanVilidity = 1;
		endif;
		
		if ( $userHasPlanActivated === 1 && $userHasPlanVilidity === 1) :
			return true;
		endif;
	}
	
	private static function doUpdateUserTransaction(){
		//$jInput 			= new JInput();
		$jInput = new JInput();
		$customObject 		= json_decode( rawurldecode ( $jInput->get('custom', '[]', 'filter') ) );
		$IPNPaymentStatus	= $jInput->get('payment_status', 'PAYMENT_STATUS_BLANK', 'filter');
		$IPNPayerEmail		= $jInput->get('payer_email', '', 'filter');
		$orderID			= $customObject->order_id;
		$orderUnique		= $customObject->order_unique;
		 
		if ( $orderID == '' || $orderUnique == '' ) return false;
		$order = self::getOrder( $orderID, $orderUnique );
		
		if( $order ) :		
			switch ( $IPNPaymentStatus ) :
				case 'Pending' : 
						/*$activateUserAccount = self::activateUserAccount ( $orderID, 1 );
						if( $activateUserAccount ) :
							return true;
							// UPDATE SUCCESSFULL
						else :
							return false;
							// ERROR WHILE UPDATE 						
						endif;*/
				 	break;
					
				case 'Completed' : 
					if ( self::userHasPremiumAccount( $orderID )) :
						return true;
						// ALLREADY UP TO DATE	
					else :
						$activateUserAccount = self::activateUserAccount ( $orderID );
						if( $activateUserAccount ) :
							return true;
							// UPDATE SUCCESSFULL
						else :
							return false;
							// ERROR WHILE UPDATE 						
						endif;
					endif;
				 	break;
				
				case 'Refunded' : 
					/*if ( self::userHasPremiumAccount( $orderTransactionId )) :
						$activateUserAccount = self::activateUserAccount ( $orderTransactionId, 3 );
						if( $activateUserAccount ) :
							return true;
							// UPDATE SUCCESSFULL
						else :
							return false;
							// ERROR WHILE UPDATE 						
						endif;
					else :
						return true;
						// ALLREADY UP TO DATE	
					endif;*/
				 	break;
				case '':
					return false;
					// MISSING
					break;	
				default:
					return true;
					break;
			endswitch;
		else:
			return false;
			// INVALID ORDER
		endif;
	}
	
	private static function activateUserAccount ( $orderID ) {
		if ( empty($orderID) ) return false;
		
		$db 	= JFactory::getDbo();
		$query 	= $db->getQuery(true);
		
		$query->select('*');
		$query->from( $db->quoteName( 'paypalPayment' ));
		$query->where($db->quoteName( 'pay_id' ) . ' = ' . (int)$orderID );
		$db->setQuery($query);
		$paydetail = $db->loadObject();
		if( empty($paydetail) ) return false;
		
		$plan_id = $paydetail->mem_plan_id;
		$user_id = $paydetail->user_id;
		
		$query->select('*');
		$query->from( $db->quoteName( 'membership_plan' ));
		$query->where($db->quoteName( 'plan_id' ) . ' = ' . (int)$plan_id );
		$db->setQuery($query);
		$plandetail = $db->loadObject();
		if (empty($plandetail) || empty($plandetail->durationindays)) return false;
		
		$plan_duration_days = $plandetail->durationindays;
		
		$start_date = date( 'Y/m/d h:i:s' );
		$end_date	= date( 'Y/m/d h:i:s', strtotime("+".(int)$plan_duration_days." day", strtotime($start_date)));
		//$end_date	= Date( 'Y/m/d h:i:s', strtotime("+".(int)$plandetail->durationindays." days") );
		//$end_date 	= $start_date + ( 60*60*24*((int)$plandetail->durationindays) );
		
		$query->select('*');
		$query->from( $db->quoteName( 'user_subscribe_information' ));
		$query->where($db->quoteName( 'payment_id' ) . ' = ' . (int)$orderID );
		$query->where($db->quoteName( 'plan_id' ) . ' = ' . (int)$plan_id );
		$query->where($db->quoteName( 'user_id' ) . ' = ' . (int)$user_id );
		$db->setQuery($query);
		$existObj = $db->loadObject();
		
		if( !empty($existObj) ):
			$conditions = array(
				$db->quoteName('user_id') . ' = ' . (int)$user_id,
				$db->quoteName('payment_id') . ' = ' . (int)$orderID
			);
			 
			$fields = array(
				$db->quoteName('start_date') . 	' = ' . $start_date, 
				$db->quoteName('end_date') . 	' = ' . $end_date,
				$db->quoteName('user_plan_status') . ' = 1 '
			);
				
			$query->update($db->quoteName('user_subscribe_information'))->set($fields)->where($conditions);
			$db->setQuery($query);
			$result = $db->execute();
			if( empty($result) ):
				return false;
			else:
				return $result;
			endif;	
		else:
			$object = new stdClass();
			$object->user_id			= $user_id;
			$object->plan_id			= $plan_id;
			$object->start_date			= $start_date;
			$object->end_date			= $end_date;
			$object->payment_id			= $orderID;
			$object->user_plan_status	= 1;
			return $result = JFactory::getDbo()->insertObject('user_subscribe_information', $object);
		endif;
	}
	
	private static function getOrder( $orderID = 0, $orderUnique = '' ){
		if( $orderID == '' || $orderID == 0 || $orderUnique == '' ) return false;
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('op.*');
		$query->from($db->quoteName( 'paypalPayment', 'op'));
		$query->where($db->quoteName('op.pay_id') . ' = ' . (int)$orderID );
		$query->where($db->quoteName('op.order_unique') . ' = ' . $db->quote($orderUnique) );
		$db->setQuery($query);
		return $result = $db->loadObject();
	}	
	public function testLog() {
		//print_r(// JLog);
		echo // JLog::add('Caught exception(TEST LOG): ', // JLog::ALL, 'com_jsystem');
		die('DIE');
	}
	private static function getPaymentRecord( $orderID = 0, $orderUnique = '' ){
		if( $orderID == '' || $orderID == 0 || $orderUnique == '' ) return false;
		// Get a db connection.
		$db = JFactory::getDbo();
		 
		// Create a new query object.
		$query = $db->getQuery(true);
		 
		// Select all records from the user profile table where key begins with "custom.".
		// Order it by the ordering field.
		$query->select('opp.*');
		$query->from($db->quoteName( 'jorders_payments_provider', 'opp') );
		$query->where($db->quoteName('opp.payment_order_id') . ' = ' . (int)$orderID );
		$query->where($db->quoteName('opp.payment_order_unique') . ' = ' . $db->quote($orderUnique) );
		$query->where($db->quoteName('opp.state') . ' = 1 ' );
		
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		 
		// Load the results as a list of stdClass objects (see later for more options on retrieving data).
		return $result = $db->loadObject();
	}
	
	private static function doSavePayPalTXN(){ 
		//$jInput = new JInput();
		$jInput = new JInput();
		$object = new stdClass();
		$customObject = json_decode( rawurldecode ( $jInput->get('custom', '[]', 'filter') ) );
		$object->payment_record_type	= $jInput->get('payment_record_type', 'PAYMENT_PAYPAL_STANDARD', 'filter');
		$object->payment_order_id		= (int)$customObject->order_id;
		$object->payment_order_unique	= $customObject->order_unique;
		$object->amount					= $jInput->get('mc_gross', 0.00, 'filter');
		$object->currency				= $jInput->get('mc_currency', '', 'filter');
		$object->tax 					= $jInput->get('tax', 0.00, 'filter');
		$object->payer_status 			= $jInput->get('payer_status', '', 'filter');
		$object->payer_email 			= $jInput->get('payer_email', '', 'filter');
		$object->receiver_email			= $jInput->get('receiver_email', '', 'filter');
		$object->payer_id 				= $jInput->get('payer_id', '', 'filter');
		$object->first_name 			= $jInput->get('first_name', '', 'filter');
		$object->last_name 				= $jInput->get('last_name', '', 'filter');
		$object->address_status 		= $jInput->get('address_status', '', 'filter');
		$object->address_name 			= $jInput->get('address_name', '', 'filter');
		$object->address_street			= $jInput->get('address_street', '', 'filter');
		$object->address_country_code	= $jInput->get('address_country_code', '', 'filter');
		$object->address_country		= $jInput->get('address_country', '', 'filter');
		$object->address_zip 			= $jInput->get('address_zip', '', 'filter');
		$object->address_city 			= $jInput->get('address_city', '', 'filter');
		$object->address_state 			= $jInput->get('address_state', '', 'filter');
		$object->residence_country 		= $jInput->get('residence_country', '', 'filter');
		$object->mc_gross				= $jInput->get('mc_gross', 0.00, 'filter');
		$object->mc_shipping 			= $jInput->get('mc_shipping', 0.00, 'filter');
		$object->mc_handling 			= $jInput->get('mc_handling', 0.00, 'filter');
		$object->mc_currency 			= $jInput->get('mc_currency', '', 'filter');
		$object->notify_version 		= $jInput->get('notify_version', 0.0, 'filter');
		$object->custom	 				= $jInput->get('custom', '', 'filter');
		$object->num_cart_items 		= $jInput->get('num_cart_items', 0, 'filter');
		$object->verify_sign 			= $jInput->get('verify_sign', '', 'filter');
		$object->protection_eligibility = $jInput->get('protection_eligibility', '', 'filter');
		$object->txn_id 				= $jInput->get('txn_id', '', 'filter');
		$object->txn_type 				= $jInput->get('txn_type', '', 'filter');
		$object->transaction_subject 	= $jInput->get('transaction_subject', '', 'filter');
		$object->payment_gross 			= $jInput->get('payment_gross', 0.00, 'filter');
		$object->pending_reason			= $jInput->get('pending_reason', '', 'filter');
		$object->payment_type 			= $jInput->get('payment_type', '', 'filter');
		$object->payment_date 			= $jInput->get('payment_date', '', 'filter');
		$object->payment_status			= $jInput->get('payment_status', 'PAYMENT_STATUS_BLANK','filter');
		$object->merchant_return_link 	= $jInput->get('merchant_return_link', '', 'filter');
		$object->charset 				= $jInput->get('charset', '', 'filter');
		$object->test_ipn 				= $jInput->get('test_ipn', '', 'filter');
		$object->auth					= $jInput->get('auth', '', 'filter');
		$object->reason_remark			= '';
		$object->rawdata				= serialize($_REQUEST);
		$object->modified				= date("Y-m-d H:i:s");
		$object->created				= date("Y-m-d H:i:s");
		$object->hits					= 1;
		$object->hit					= date("Y-m-d H:i:s");
		mail('ali.asger@techlect.com','test paypal ' . __LINE__,'Coming ' . __LINE__,'');
		
		$object->hitrawdata				= serialize($_REQUEST);
		$object->mode					= 1; //$this->getPayPalMode();
		mail('ali.asger@techlect.com','test paypal ' . __LINE__,'Coming ' . __LINE__,'');
		$object->state					= 1;
		mail('ali.asger@techlect.com','test paypal ' . __LINE__,'Coming ' . __LINE__,'');
		return $result = JFactory::getDbo()->insertObject('jorders_payments_provider', $object);
	}
	//////////////////////////////////////////////////////////////////////////////////////	
	////////////////////////          ///    //////  ///          ////////////////////////
	////////////////////////  ///////////  /  /////  ///  //////  ////////////////////////
	////////////////////////  ///////////  //  ////  ///  //////  ////////////////////////
	////////////////////////       //////  ///  ///  ///  //////  ////////////////////////
	////////////////////////  ///////////  ////  //  ///  //////  ////////////////////////
	////////////////////////  ///////////  /////  /  ///  //////  ////////////////////////
	////////////////////////          ///  //////    ///          ////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////	
    //////////////////////////////Aliasger End////////////////////////////////////////////////////////////
}