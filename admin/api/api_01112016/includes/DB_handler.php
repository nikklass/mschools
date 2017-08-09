<?php
	
	
class DbHandler {
 
    public $conn;	
 
    function __construct() {
        require_once dirname(__FILE__) . '/DBConnect2.php';
		require_once dirname(__FILE__) . '/Config.php';
        // opening db connection
        $db = new DbConnect();
        $this->conn = $db->connect();
    }
 
    // creating new user if not existed
    public function loginUser($phone_number, $password) {
        		
		$response = array();
		
        //check if user exist in db
        if (!($this->isUserLoginExists($phone_number, $password))) {
			
			$response["error"] = true;
			$response["noty_msg"] = true;
			$response["error_type"] = "User account does not exist";
			$response["message"] = "Incorrect details";
        
		} else if (!$this->isUserActivated($phone_number)) {
			
			//$path = SITEPATH ."account-activation";
			//$activation_link = "<a href='" . $path . "' target='_blank'>" . $path . "</a>";
			$response["error"] = true;
			$response["noty_msg"] = true;
			$response["error_type"] = "not_activated";
			//$response["slide_form"] = true;
			//$response["slide_duration"] = 5000; // 5 seconds
			$response["message"] = "Account not activated Please activate";
			$response["user"] = $this->getUserDetails($phone_number);
        
		} else {
            
			// User exists in the db
            $response["error"] = false;
			$response["reload_page"] = true;
            $response["user"] = $this->getUserDetails($phone_number);
			$this->createLoginSession($phone_number);
			
        }
 
        return $response;
		
    }

	//set new password for user
	function setPassword($password, $user_id){

		//allow if user is logged in
		if (USER_LOGGED_IN) {
		
			$query = "UPDATE clients SET password='$password' WHERE id = ? ";
			$stmt = $this->conn->prepare($query);
			$stmt->bind_param("i", $user_id);
			$result = $stmt->execute();
			$stmt->close();
	
			// Check for successful insertion
			if ($result) {
				// success
				$response["error"] = false;
				$response["close_form"] = true;
				$response["message"] = "Password has been set";                      
				
			} else {
				// fail
				$response["error"] = true;
				$response["message"] = "An error occurred";
			}
			
			return $response;
		}

	}
	
	//change user password
    public function changePassword($password, $new_password, $phone_number)
	{
        
		$email_check = false;
		$phone_check = false;		
		
		if ($this->validateEmail($phone_number)){ $email_check = true; } else { $phone_check = true; }
		
		$success = 1;
		
		if ($phone_check) {
			//check if phone number is valid before proceeding
			if (!$this->isNumberValid($phone_number)) {
				// Invalid phone number
				$response["error"] = true;
				$response["message"] = "Invalid phone number. Check number again.<br>Required format: 07XXXXXXXX";
				$response["user"] = null;
				$success = 0;
			}
		}
			
		if ($success) {
		
			if ($phone_check) { $phone_number = $this->formatPhoneNumber($phone_number); }
			
			$response = array();
	 
			// First check if user already exist in db
			if ($this->isUserPasswordExists($phone_number, $password)) {
				
					$current_date = $this->getCurrentDate();
					//new password
					$password = md5($new_password);
					
					// update user password
					$query = "UPDATE clients SET password = '$password' WHERE ";
					if ($email_check) { $query .= " email = '$email'"; }
					if ($phone_check) { $query .= " phone_number = '$phone_number'"; }
					
					if ($stmt = $this->conn->prepare($query)) {

						$result = $stmt->execute();
						$stmt->close();
			 
						// Check for successful update
						if ($result) {
							// password successfully changed
							//send sms with new password
							if ($phone_check) {
								$response["sms"] = $this->sendSMS($phone_number, $code, FORGOT_PASSWORD_SMS);
							}
							
							$response["error"] = false;
							$response["close_form"] = true;
							$response["message"] = "Your password has been changed";                        
							
						} else {
							// Failed to create user
							$response["error"] = true;
							$response["message"] = "An error occurred while saving new password. Please try again";
							$response["user"] = "";
						}
					} else {
			
						$response["query"] = $query;
						$response["error"] = true;
						$response["message"] = $this->conn->error;
						$response['error_type'] = AN_ERROR_OCCURED_ERROR;
					
					}
				
			} else {
				// User with same phone number already existed in the db
				$response["error"] = true;
				$response["message"] = "Current password is not correct. Try again.";
				$response["slide_form"] = true;
				$response["slide_duration"] = 12000;
			}
		
		}
 
        return $response;
    }
	
	
	//forgot user password
    public function forgotPassword($phone_number) 
	{
        
		$email_check = false;
		$phone_check = false;		
		
		if ($this->validateEmail($phone_number)){ $email_check = true; } else { $phone_check = true; }
		
		$success = 1;
		
		if ($phone_check) {
			//check if phone number is valid before proceeding
			if (!$this->isNumberValid($phone_number)) {
				// Invalid phone number
				$response["error"] = true;
				$response["noty_msg"] = true;
				$response["message"] = "Invalid phone number. Check number again.<br>Required format: 07XXXXXXXX";
				$response["user"] = null;
				$success = 0;
			}
		}
			
		if ($success) {
		
			if ($phone_check) { $phone_number = $this->formatPhoneNumber($phone_number); } else {
				//find users phone based on email adress	
			}
			
			$response = array();
	 
			// First check if user already exist in db
			if ($this->isUserExists($phone_number)) {
				
					$current_date = $this->getCurrentDate();
					//generate new password
					$code = $this->generateCode(5);
					$password = md5($code);
					
					// update user password
					$query = "UPDATE clients SET password = '$password' WHERE ";
					if ($email_check) { $query .= " email = '$email'"; }
					if ($phone_check) { $query .= " phone_number = '$phone_number'"; }
					
					if ($stmt = $this->conn->prepare($query)) {

						$result = $stmt->execute();
						$stmt->close();
			 
						// Check for successful update
						if ($result) {
							// password successfully changed
							//send sms with new password
							if ($phone_check) {
								$response["sms"] = $this->sendSMS($phone_number, $code, FORGOT_PASSWORD_SMS);
							} else {
								//send confirm email
									
							}
							
							$response["error"] = false;
							$response["noty_msg"] = true;
							$response["message"] = "A new login password has been sent to you. \n Login and change it to a new password\n Select top menu->change password";                        
							
						} else {
							// Failed to create user
							$response["error"] = true;
							$response["noty_msg"] = true;
							$response["message"] = "An error occurred while saving new password. Please try again";
							$response["user"] = "";
						}
					} else {
			
						//$response["query"] = $query;
						$response["error"] = true;
						//$response["message"] = $this->conn->error;
						$response['error_type'] = AN_ERROR_OCCURED_ERROR;
					
					}
				
			} else {
				// User with same phone number already existed in the db
				$response["error"] = true;
				$response["noty_msg"] = true;
				$response["message"] = "Phone number or email does not exist. Try again.";
				$response["slide_form"] = true;
				$response["slide_duration"] = 12000;
			}
		
		}
 
        return $response;
    }
	
	
	// creating new user if not exists
    public function createUser($phone_number, $password, $email="", $first_name="", $last_name="", $full_names="") 
	{
        
		//echo "phone_number - $phone_number"; exit;
		
		$success = 1;
		//check if phone number is valid before proceeding
		if (!$this->isNumberValid($phone_number)) {
			// Invalid phone number
			$response["error"] = true;
			$response["message"] = "Invalid phone number. Check number again.<br>Required format: 07XXXXXXXX";
			$response["user"] = null;
			$success = 0;
		}
			
		if ($success) {
		
			$phone_number = $this->formatPhoneNumber($phone_number);
			
			$response = array();
	 
			// First check if user already exist in db
			if (!$this->isUserExists($phone_number)) {
				
					$current_date = $this->getCurrentDate();
					$password = md5($password);
					// insert query
					$query = "INSERT INTO clients(phone_number, password, created_at";
					if ($first_name) { $query .= ", first_name"; }
					if ($last_name) { $query .= ", last_name"; }
					if ($full_names) { $query .= ", full_names"; }
					if ($email) { $query .= ", email"; }
					$query .= ") values(";
					$query .= "'$phone_number', '$password', '$current_date'";
					if ($first_name) { $query .= ", '$first_name'"; }
					if ($last_name) { $query .= ", '$last_name'"; }
					if ($full_names) { $query .= ", '$full_names'"; }
					if ($email) { $query .= ", '$email'"; }
					$query .= ")";
					
					//echo "query - $query"; exit;
					$stmt = $this->conn->prepare($query);
					//$stmt->bind_param($bind_param, $phone_number, $password, $first_name, $last_name, $full_names, $email, $current_date);
					$result = $stmt->execute();
					$stmt->close();
		 
					// Check for successful insertion
					if ($result) {
						// User successfully inserted
						$response["error"] = false;
						$response["close_form"] = true;
						$response["message"] = "User account created successfully<br><br>Please login";
						$response["user"] = $this->getUserDetails($phone_number);
						
                        ///////////////////////////////////////////////// enable later ///////////////////////////////////////
						$this->sdp_que_sub_app($phone_number,"MDSP2000075075") ;                        
                        
					} else {
						// Failed to create user
						$response["error"] = true;
						$response["message"] = "An error occurred during registration";
						$response["user"] = "";
					}
				
			} else {
				// User with same phone number already existed in the db
				$response["error"] = true;
				$response["message"] = "Phone number or email already exists. Try another.";
				$response["slide_form"] = true;
				$response["slide_duration"] = 12000;
				$response["user"] = $this->getUserDetails($phone_number);
			}
		
		}
 
        return $response;
    }
	
	// edit an existing new user
    public function editUser($user_id, $phone_number, $password, $email, $first_name, $last_name, $full_names, $user_type, $status)
	{
        
		$success = 1;
		//check if phone number is valid before proceeding
		if (!$this->isNumberValid($phone_number)) {
			// Invalid phone number
			$response["error"] = true;
			$response["message"] = "Invalid phone number. Check number again.<br>Required format: 07XXXXXXXX";
			$response["user"] = null;
			$success = 0;
		}
			
		if ($success) {
		
			$phone_number = $this->formatPhoneNumber($phone_number);
			
			$response = array();
			
			$password = md5($password);
	 
			// First check if user already exist in db				
			$current_date = $this->getCurrentDate();
			$password = md5($password);
			// insert query
			$query = "UPDATE clients SET phone_number='$phone_number', updated_at='$current_date'";
			if ($first_name) { $query .= ", first_name = '$first_name' "; }
			if ($last_name) { $query .= ", last_name = '$last_name'"; }
			if ($full_names) { $query .= ", full_names='$full_names'"; }
			if ($user_type_id) { $query .= ", user_type_id='$user_type'"; }
			if ($status) { $query .= ", status=$status"; }
			if ($password) { $query .= ", password='$password'"; }
			if ($email) { $query .= ", email='$email'"; }
			$query .= " WHERE id = $user_id";
			//echo "$query"; exit;
			$stmt = $this->conn->prepare($query);
			//save changes
			$result = $stmt->execute();
			$stmt->close();
 
			// Check for successful insertion
			if ($result) {
				// User successfully inserted
				$response["error"] = false;
				$response["close_form"] = true;
				//$response["success_url"] = SITEPATH . "login";
				$response["message"] = "User account edited successfully";
				$response["user"] = $this->getUserDetails($phone_number);                    
				
			} else {
				// Failed to create user
				$response["error"] = true;
				$response["message"] = "An error occurred during editing";
				$response["user"] = "";
			}
		
		}
 
        return $response;
    }
	
	//get subscription details
	function getSubDetails($sub_id)
	{
		//get sub details
		$query  = "SELECT ss.sch_id, st.id, ss.reg_no, st.full_names, sch_name, ss.mobile, prov, sub_date FROM sch_ussd_subs ss";
		$query .= " JOIN sch_students st ON ss.reg_no=st.reg_no ";
		$query .= " WHERE ss.id = ? ";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i", $sub_id);
		$stmt->execute();
		$stmt->store_result();
		/* bind result variables */
		$stmt->bind_result($id, $sch_id, $student_id, $regno, $full_names, $sch_name, $mobile, $prov, $sub_date);
		
		/* fetch values */
		$tmp = array();
		$tmp["id"] = $id;
		$tmp["sch_id"] = $sch_id;
		$tmp["reg_no"] = $regno;
		$tmp["student_name"] = $full_names;
		$tmp["sch_name"] = $sch_name;
		$tmp["phone_number"] = $mobile;
		$tmp["prov"] = $prov;
		$tmp["created_at"] = $this->adjustDate("d-M-Y", $this->php_date($sub_date), NULL);
		$tmp["user_image"] = $this->getPhoto(STUDENT_PROFILE_PHOTO, $student_id, THUMB_IMAGE);
		$tmp["user_large_image"] = $this->getPhoto(STUDENT_PROFILE_PHOTO, $student_id);
		$stmt->close();
		
		return $tmp;
		
	}
	
	// creating new subscription if not exists
    public function createSubscription($phone_number, $sch_id, $reg_no) {
        				
		$response = array();
		
		$phone_number = $this->formatPhoneNumber($phone_number);
 
		// First check if subscription already exist in db
		if (!$this->isSubExists($phone_number, $sch_id, $reg_no)) {
			
				$sub_id = $this->subscribeUser($phone_number, $sch_id, $reg_no);
				
				if ($sub_id){ //no error occured
					$response['subs'] = $this->getSmsSubscription($phone_number, $sch_id, $reg_no);
					$response["noty_msg"] = true;
					$response["error"] = false;
					$response["message"] = "Subscription successfully added";
				} else {
					$response["error"] = true;
					$response["noty_msg"] = true;
					$response["message"] = "An error occured while subscribing";
				}
			
		} else {
			// User with same email already existed in the db
			$response["error"] = true;
			$response["noty_msg"] = true;
			$response["message"] = "Subscription already exists";
			$response["sms_sub"] = $this->getSmsSubscription($phone_number, $sch_id, $reg_no);
		}
 
        return $response;
    }
	
	
	///////////////////////////////////// SMS FUNCTIONS /////////////////////////////////////
	
	// resend sms
    public function resendRegSMS($phone_number) {
				 
		$phone_number = $this->formatPhoneNumber($phone_number);
		
		$code = $this->generateCode(5); //generate random 5 digit code
		
		$this->disablePreviousSentSMS($phone_number, REGISTRATION_SMS);

		//send the sms with resent registration sms flag
		$response = $this->sendSMS($phone_number, $code, RESENT_REGISTRATION_SMS);
  
        return $response;
		
    }
	
	//disable previously sent but unused sms codes fior this phone number / user
	function disablePreviousSentSMS($phone_number, $sms_type_id){
		if ($sms_type_id == REGISTRATION_SMS)
		{
			$sms_type_query_add = " AND (sms_type_id = " . REGISTRATION_SMS . " OR sms_type_id = " . RESENT_REGISTRATION_SMS . ")";
		}
		//disable other registration sms codes for this user
		$query = "UPDATE sms_codes SET status=1 WHERE mobile = ? AND status = 0 ";
		$query .= $sms_type_query_add;
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("s", $phone_number); // number of params s - string, i - integer, d - decimal, etc
        $result = $stmt->execute();
		$stmt->close();
		//end disable	
	}
	
	// send recommendation sms
    public function sendRecommendSMS($sender_phone_number, $sender_id, $recipient_phone_number) {
		
		$sms_type_id = RECOMMENDATION_SMS;		 
		
		$code = $this->generateCode(5); //generate random 5 digit code
		//get recommend text
		$message = $this->getSettingValue('recommend_text');
		// insert new recommend entry to db and send sms
		$current_date = $this->getCurrentDate();
		// insert query
		$query = "INSERT INTO recommendations(sender_id, sender_phone_number, recipient_phone_number";
		$query .= ", message, recommend_key, created_at) VALUES(?, ?, ?, ?, ?, ?, ?)";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("isssss", $sender_id, $sender_phone_number, $recipient_phone_number, $message, $code, $current_date);
		$result = $stmt->execute();
		$stmt->close();
		
		// Check for successful insertion
		if ($result) {
			// successfully inserted
			$response["error"] = false;
			$response["message"] = "Recommendation sent";
			
		} else {
			// Failed to insert
			$response["error"] = true;
			$response["message"] = "An error occurred";
		}
						
		$response = $this->sendSMS($recipient_phone_number, $message, $sms_type_id);
 
        return $response;
		
    }
	
	//delete items
	public function deleteItem($field_name, $field_value, $table_name) {
		
		$query = "DELETE FROM $table_name WHERE $field_name = ? ";
		$stmt = $this->conn->prepare($query); 
        $stmt->bind_param("i", $field_value);
        if ($stmt->execute()) {
            $response = array();
            $response["success"] = 1;
        } else {
            $response["success"] = 0;
        }
		$stmt->close();
		
		return $response;
		
    }
	
	//delete result record
	public function deleteResultRecord($id, $sch_id) {
		
		//get result id
		$result_id = $this->getResultId($id);
		
		$query = "DELETE FROM sch_results_items WHERE id = ? ";
		$stmt = $this->conn->prepare($query); 
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            //refresh summary data
			$this->updateResultSummaryData($result_id, $sch_id);
			//end refresh summary data
			$response = array();
            $response["success"] = 1;
        } else {
            $response["success"] = 0;
        }
		$stmt->close();
		
		return $response;
		
    }
	
	//delete fee record
	public function deleteFeeRecord($fee_payment_id) {
		
		//get fee id
		$fee_id = $this->getFeeId($fee_payment_id);
		
		$query = "DELETE FROM sch_fees_payments WHERE id = ? ";
		$stmt = $this->conn->prepare($query); 
        $stmt->bind_param("i", $fee_payment_id);
        if ($stmt->execute()) {
            //refresh summary data
			$this->updateFeeSummaryData($fee_id);
			//end refresh summary data
			$response = array();
            $response["success"] = 1;
        } else {
            $response["success"] = 0;
        }
		$stmt->close();
		
		return $response;
		
    }	
	
	//delete user groups
	public function deleteGroup($field_name, $field_value, $table_name) {
		$forbidden_users = array(SUPER_ADMIN_USER_ID, NORMAL_ADMIN_USER_ID, SCHOOL_ADMIN_USER_ID, NORMAL_USER_ID, SCHOOL_USER_ID);
		//cannot delete admin groups
		if (in_array($field_value, $forbidden_users)) {
			$response["error"] = true;
			$response["message"] = "You cannot delete system's internal groups";
		} else {
			
			//delete all permissions assigned to this user
			$query = "DELETE FROM group_permissions WHERE group_id = ? ";
			$stmtDel = $this->conn->prepare($query); 
			$stmtDel->bind_param("i", $field_value);
			if ($stmtDel->execute()) {
			
				//delete this group
				$query = "DELETE FROM groups WHERE id = ? ";
				$stmt = $this->conn->prepare($query); 
				$stmt->bind_param("i", $field_value);
				if ($stmt->execute()) {
					$response = array();
					$response["success"] = 1;
				} else {
					$response["success"] = 0;
				}
				
			}
			
			$stmtDel->close();
			$stmt->close();
			
		}
		
		return $response;
		
    }
   
	public function getconfig($param_name){
		
		$qry = "SELECT param_text from sms_gw.sysconfig where param_name='$param_name' limit 0,1";

		$value = "";

		$res = $this->do_qry($qry);

		while ($row = mysqli_fetch_object($res)){
				$value = $row->param_text;
		}

		return $value;
		
	}

	public function do_qry($sql,$logmsg=true){
		
		if($logmsg) {
			
			$this -> log_this($sql);    
			
		}
	   
		return mysqli_query($this->conn, $sql);
		
	}
			
	public function log_this($lmsg)
	{  
	  
		$flog = sprintf("/data/log/pendo_school_%s.log",date("Ymd"));
		  
		$tlog = sprintf("\n%s%s",date("Y-m-d H:i:s T: "),$lmsg);
		$f = fopen($flog, "a");
		fwrite($f,$tlog);
		fclose($f);
	}

	public function sdp_que_out_app($dest, $msg, $cat, $sid,$delay=0)  // With Minutes Delay HACK
	{
		
		$source_qry= sprintf("SELECT * FROM sms_gw.sdp_service_map where service_id='%s'",$sid);
		$rw1 = mysqli_fetch_object(do_qry($source_qry));

		$source = $rw1->service_code ;
   
		$msg = str_replace('\n', '\r\n', $msg);
		$sql=sprintf("INSERT INTO sms_gw.sdp_outgoing_que(dest,msg_text,category,target_date,route,charge,msg_type,service_id,priority) VALUES('%s','%s','%s',DATE_ADD(NOW(),INTERVAL $delay MINUTE),'%s','%s','%s','%s','0')",  $dest,$msg,$cat,$source,'0','text',$sid);
		  
		$this->do_qry($sql);
   
		return true ;                          
	   
	}

	public function sdp_que_sub_app($msisdn,$pid,$delay=0)  // With Delay HACK
	{    

		$spid = $this->getconfig("sdp_sp_id"); 

		$sp_passwd = $this->getconfig("sdp_passwd");
	 
		$sql=sprintf("INSERT INTO sms_gw.sdp_sub_man_que(msisdn,date_stamp,target_date,prod_id,sp_id,sp_passwd,priority) VALUES('%s',NOW(),DATE_ADD(NOW(),INTERVAL $delay SECOND),'%s','%s','%s','0')",  $msisdn,$pid,$spid,$sp_passwd);
	 
		$this->do_qry($sql);
	 
		return true ;                          
	}

	// send registration sms
    public function sendRegSMS($phone_number) {
				 
		$code = $this->generateCode(5); //generate random 5 digit code
		// insert sms		
		$response = $this->sendSMS($phone_number, $code, REGISTRATION_SMS);
 
        return $response;
		
    }
		
	// send bulk sms
    public function sendBulkSMS($usr, $pass, $src, $phone_number, $message, $sms_type_id = 1) {
				
		$user_agent = @$_SERVER["HTTP_USER_AGENT"]?$_SERVER["HTTP_USER_AGENT"]: "" ;
        $src_ip =  @$_SERVER["REMOTE_ADDR"]? $_SERVER["REMOTE_ADDR"] : "" ; 
        $src_host = @$_SERVER["REMOTE_HOST"]? $_SERVER["REMOTE_HOST"]: "" ; 
        
        $response = array();
 
		$current_date = $this->getCurrentDate(); // get current date
		// insert sms query
		//change to your table definition
		$query = "INSERT INTO sms_codes(mobile, message, created_at, user_agent, src_ip, src_host, sms_type_id, sender) VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("ssssssis", $phone_number, $message, $current_date, $user_agent, $src_ip, $src_host, $sms_type_id, $usr); // number of params s - string, i - integer, d - decimal, etc
        
        $result = $stmt->execute();
		$stmt->close();
		
		///////////////////////////////////////////////// enable later ////////////////////////////////////////////////////////////////////////
		$send_sms_link = "http://41.215.126.10:5333/sms/fxd1.php?usr=" . $usr . "&pass=" . $pass . "&src=" . $src . "&dest=" . $phone_number . "&msg=" . $message . "";
        $result = $this->executeLink($send_sms_link);
		//echo "send_sms_link - $send_sms_link";
		//print_r($response);
		
		// Check for successful insertion
		if ($result->error == false) {
			// sms successfully sent
			$sms_id = $this->conn->insert_id;
			$response["error"] = false;
			$response["message"] = "SMS sent successfully";
			$response["sms"] = $this->getSentSMS($sms_id);
			
		} else {
			// Failed to create sms
			$response["error"] = true;
			$response["message"] = "An error occurred in Sending SMS";
		}
 
        return $response;
		
    }
	
	// send sms
    public function sendSMS($phone_number, $message, $sms_type_id = 1) {
				
		$user_agent = @$_SERVER["HTTP_USER_AGENT"]?$_SERVER["HTTP_USER_AGENT"]: "" ;
        $src_ip =  @$_SERVER["REMOTE_ADDR"]? $_SERVER["REMOTE_ADDR"] : "" ; 
        $src_host = @$_SERVER["REMOTE_HOST"]? $_SERVER["REMOTE_HOST"]: "" ; 
        
        $response = array();
 
		$current_date = $this->getCurrentDate(); // get current date
		// insert sms query
		//change to your table definition
		$query = "INSERT INTO sms_codes(mobile, message, created_at, user_agent, src_ip, src_host, sms_type_id) VALUES(?, ?, ?, ?, ?, ?, ?)";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("ssssssi", $phone_number, $message, $current_date, $user_agent, $src_ip ,$src_host, $sms_type_id); // number of params s - string, i - integer, d - decimal, etc
        
        $result = $stmt->execute();
		$stmt->close();
		
		
		//send sms via link
		//$send_sms_link = "http://41.215.126.10:5333/pendoschool_app/?tag=send_sms&mobile=" . $phone_number . "&msg=" . $message;
		
		///////////////////////////////////////////////// enable later ////////////////////////////////////////////////////////////////////////
		$message = urlencode($message);
		$send_sms_link = "http://localhost/pendoschool_app/?tag=send_sms&mobile=" . $phone_number . "&msg=" . $message;
        $response["sms_reply"] = $this->executeLink($send_sms_link);
		
		// Check for successful insertion
		if ($result) {
			// User successfully inserted
			//get the inserted item id
			$sms_id = $this->conn->insert_id;
			$response["error"] = false;
			$response["message"] = "SMS sent successfully";
			$response["sms"] = $this->getSentSMS($sms_id);
			
		} else {
			// Failed to create sms
			$response["error"] = true;
			$response["message"] = "An error occurred in Sending SMS";
		}
 
        return $response;
		
    }
	
	//execute a link via curl
	public function executeLink($link)
	{
		
		$agent= 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL,$link);
		curl_setopt($ch, CURLOPT_USERAGENT, $agent);
		$result=curl_exec($ch);
		curl_close($ch);
        
        return  json_decode($result);   
		
	}
	
	//get sent sms
	public function getSentSMS($sms_id) {
		
		$query = "SELECT mobile, message, created_at FROM sms_codes WHERE id = ? ";
		$stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $sms_id);
        $stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($mobile, $message, $created_at);
		$stmt->fetch();
		if ($mobile) {
            $sms = array();
			$sms["sms_id"] = $sms_id;
			$sms["mobile"] = $mobile;
			$sms["message"] = $message;
            $sms["created_at"] = $this->adjustDate(NULL, $this->php_date($created_at), NULL);
            $stmt->close();
            return $sms;
        } else {
            return NULL;
        }
		
		return $sms;
			
    }
	
	//function top format datae like 21/05/2007 to a valid mysql date
	public function reformat_date($date){
		$date_parts = explode("/", $date);
		$new_date = strtotime($date_parts[1] . '/' . $date_parts[0] . '/' . $date_parts[2]);
		return date('Y-m-d H:i:s',$new_date);
	}
	//update single field
	public function updateSingleFieldData($field_name, $field_value, $primary_field_name, $primary_field_value, $datatype, $table_name) {
		$response = array();
		//handle date values differently
		if ($datatype=='d'){
			//handle the date
			//split the date using / and create a new date
			$field_value = $this->reformat_date($field_value);
			//set datatype to string	
			$datatype = "s";
		}
		$datatypes = $datatype."i";
		$query = "UPDATE $table_name SET $field_name = ? WHERE $primary_field_name = ? ";
		$stmt = $this->conn->prepare($query); 
        $stmt->bind_param($datatypes, $field_value, $primary_field_value);

		if ($stmt->execute()) {
            $response = array();
			$response["message"] = "Successfully updated";
			$response["error"] = false;
        } else {
            $response["message"] = "An error occured";
			$response["error"] = true;
        }
		
		$stmt->close(); 
		
		return $response;
			
    }
	
	//create a new ACTIVITY
    public function createActivity($name, $sch_id, $description, $venue, $start_at, $end_at) {
        				
		$response = array();
		
		$start_at = $this->reformatDate($start_at);
		$end_at = $this->reformatDate($end_at);
		$current_date = $this->getCurrentDate();
		$user_id = USER_ID;
		
		// insert query
		$query = "INSERT INTO sch_activities(name, sch_id, description, venue, start_at, end_at, created_by, created_at) VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
		$stmt = $this->conn->prepare($query);
		//echo "query - $query - dt -  $name, $sch_id, $description, $venue, $start_at, $end_at,". USER_ID .", $current_date"; exit;
		$stmt->bind_param("sissssis", $name, $sch_id, $description, $venue, $start_at, $end_at, $user_id, $current_date);
		$result = $stmt->execute();
		$stmt->close();
		
		
		// Check for successful insertion
		if ($result) {
			$activity_id = $this->conn->insert_id;
			$response["error"] = false;
			$response["message"] = "Activity created";
			$response["clear_form"] = true;
			$response["activity_id"] = $activity_id;			
		} else {
			// Failed to create chat
			$response["error"] = true;
			$response["error_type"] = ERROR_OCCURED;
			$response["message"] = "An error occurred whle creating activity";
		}
 
        return $response;
    }
	
	//create a new student fee
    public function createStudentFee($amount, $payment_mode, $paid_by, $paid_at, $student_id, $year) {
        				
		$response = array();
		
		//echo "fees_id - $student_id, $sch_id, $reg_no, $year"; exit;
		
		if (!$amount || !$student_id || !$year) {
			$response["message"] = "Please fill in all required details";
			$response["error"] = true;
			$response["ref"] = "none";
		} else {
				
			if (!$this->isDataNumeric($amount)){
				
				$response['message'] = "Amount must be a number";
				$response['error'] = true;	
				
			} else {
	
				$current_date = $this->getCurrentDate();
				$user_id = USER_ID;
				
				$fees_id = $this->studentFeeExists($student_id, $sch_id, $reg_no, $year);
				
				//check if fee exists for this student in this period
				if (!$fees_id) {
					//create new result
					$fees_id = $this->createNewStudentFee($student_id, $sch_id, $reg_no, $year);
				} 
				
				//echo "fees_id - $fees_id";
				
				if ($fees_id)
				{
					// insert query				
					$query = "INSERT INTO sch_fees_payments(fees_id, amount, payment_mode, paid_by, paid_at, created_by, created_at) VALUES(?, ?, ?, ?, ?, ?, ?)";
					$stmt = $this->conn->prepare($query);
					//echo "query - $query - $fees_id, $amount, $payment_mode, $paid_by, $paid_at, $user_id, $current_date"; exit;
					$stmt->bind_param("iisssis", $fees_id, $amount, $payment_mode, $paid_by, $paid_at, $user_id, $current_date);
					$result = $stmt->execute();
					$stmt->close();
					
					// Check for successful insertion
					if ($result) {
						
						$new_id = $this->conn->insert_id;
						
						//update fees summary data
						$res = $this->updateFeeSummaryData($fees_id);
						//end update fees summary data
						
						$response["res"] = $res;
						$response["error"] = false;
						$response["message"] = "Fees Added";
						$response["reg_no"] = $reg_no;
						$response["clear_form"] = true;
						$response["new_id"] = $new_id;			
					} else {
						// Failed to create record						
						$response["error"] = true;
						$response["reg_no"] = $reg_no;
						//$response["full_names"] = $full_names;
						$response["error_type"] = ERROR_OCCURED;
						$response["message"] = "An error occurred whle inserting record";
					}
				}
			
			}
		
		}
 
        return $response;
    }
	
	//create a new student result
	//createStudentResult("", $sch_id, $reg_no, $year, $term, $key, $val);
    public function createStudentResult($student_id, $sch_id, $reg_no, $year, $term, $subject, $score, $class=NULL) {
        						
		//echo "$student_id, $sch_id, $reg_no, $year, $term, $subject, $score\n";
		
		$response = array();
		
		if ($score > 100)
		{
			// Result already exists
			$response["error"] = true;
			$response["message"] = "Subject score cannot be more than 100";
			
		} else {

			$current_date = $this->getCurrentDate();
			$user_id = USER_ID;
			
			$result_id = $this->studentResultExists($student_id, $sch_id, $reg_no, $year, $term);
			
			//check if result exists for this student in this period
			if (!$result_id) {
				//create new result
				$result_id = $this->createNewStudentResult($student_id, $sch_id, $reg_no, $year, $term, $class);
			} 
			
			if ($result_id && $student_id && $sch_id && $reg_no && $year && $term && $score) {				
		
				if ($this->studentResultItemExists($result_id, $subject))
				{
					$student_result_details = $this->getResultData($result_id);
					$result_year = $student_result_details["year"];
					$result_term = $student_result_details["term"];
					$subject_name = $this->getSubjectName($subject);
					// Result already exists
					$response["error"] = true;
					$response["message"] = "Result for (subject: <strong>$subject_name</strong> term: <strong>$result_term</strong> year: <strong>$result_year</strong>) already exists";
					
				} else {
				
					$sch_level = $this->getSchoolLevel($sch_id);
					$grade = $this->getSubjectGrade($score, $sch_level);
					$points = $this->getSubjectPoints($score, $sch_level);
					// insert query
					$query = "INSERT INTO sch_results_items(result_id, subject_code, score, grade, points, created_by, created_at) VALUES(?, ?, ?, ?, ?, ?, ?)";
					$stmt = $this->conn->prepare($query);
					//echo "query - $query - $result_id, $subject, $score, $grade, $points, $user_id, $current_date"; exit;
					$stmt->bind_param("isdsiis", $result_id, $subject, $score, $grade, $points, $user_id, $current_date);
					$result = $stmt->execute();
					$stmt->store_result();
					$stmt->close();
					
					// Check for successful insertion
					if ($result) {
						$new_id = $this->conn->insert_id;
						
						//update result summary data
						$res = $this->updateResultSummaryData($result_id, $sch_id);
						//end update result summary data
						
						$response["res"] = $res;
						$response["error"] = false;
						$response["message"] = "Result Added";
						$response["clear_form"] = true;
						$response["new_id"] = $new_id;			
					} else {
						// Failed to create chat
						$response["error"] = true;
						$response["error_type"] = ERROR_OCCURED;
						$response["message"] = "An error occurred whle creating record == $query - $result_id, $subject, $score, $grade, $points, $user_id, $current_date";
					}
				}
				
			} else {
				// Failed to get result id
				$response["error"] = true;
				$response["error_type"] = ERROR_OCCURED;
				$response["message"] = "No result found";
			}
		
		}
 
        return $response;
    }
	
	//Upload fee data from excel file
    public function uploadResults($sch_id, $results) {
        				
		
		$response = array();
		$res_array = array();
		$insert_results = array();
		
		$user_id = USER_ID;
		$sch_name = $this->getSchoolName($sch_id);
		
		$created_by = USER_ID;
		$current_date = $this->getCurrentDate();

		$res_array = $results; //print_r($res_array); exit;
		
		$num_recs = count($res_array); //echo $num_recs; exit;
		
		$success_inserts = 0; 
		$fail_inserts = 0;
		$result_row = ""; 
		
		for ($i = 0; $i < count($res_array); $i++){

			$tmp = array();
			
			$reg_no = ""; $year = ""; $term = "";
			
			foreach ($res_array[$i] as $key => $val) {
				if (!$reg_no) { $reg_no = $val; continue; } // 1st array element
				if ($reg_no && !$year) { $year = $val; continue; } // 2nd array element
				if ($reg_no && $year && !$term) { $term = $val; continue; } // 3rd array element
								
				//check if student exists in school beofre inserting
				if ($this->isStudentExists($sch_id, $reg_no)) {
					//fetch any missing student data
					$student_details = $this->getStudentData($reg_no, $sch_id, "", "", "");
					//print_r($student_details);
					$student_id = $student_details["student_id"];
					$reg_no = $student_details["reg_no"];
					$class = $student_details["current_class"];
					$full_names = $student_details["student_full_names"];
			
					//insert record
					if ($sch_id && $reg_no && $year && $term && $key && $val) {
						//echo "$sch_id, $reg_no, $year, $term, $key, $val\n"; 
						$tmp = $this->createStudentResult($student_id, $sch_id, $reg_no, $year, $term, $key, $val, $class);
						
						//store error/ success msgs
						if ($tmp['error']) { 
							$fail_inserts += 1; 
							$result_rows .= "<tr><td>" . $reg_no . "</td><td>" . $full_names . "</td><td colspan='2'><span class='text-danger'>" . $tmp['message'] . "</span></td></tr>";
						} else { 
							$success_inserts += 1; 
							$result_rows .= "<tr><td>" . $reg_no . "</td><td>" . $full_names . "</td><td colspan='2'><span class='text-success'>" . $tmp['message'] . "</span></td></tr>";
						}
						
					}
					
				} else {
					//student does not exist in this school	
					$tmp["message"] = "Student <strong>$reg_no</strong> does not exist in <strong>$sch_name</strong>";
					$tmp["error"] = true;
					
					//store error msgs
					$fail_inserts += 1; 
					$result_rows .= "<tr><td>" . $reg_no . "</td><td>" . $full_names . "</td><td colspan='2'><span class='text-danger'>" . $tmp['message'] . "</span></td></tr>";
						
					break; //get out of foreach loop and execute outer for loop
				}
			
			}
			
			array_push($insert_results, $tmp);
			
		}
		
		$stats = $students["stats"];
		
		$full_result_rows = "<table class='table table-responsive response-table'>";
		$full_result_rows .= "	<thead>";
		$full_result_rows .= "		<tr>";
		$full_result_rows .= "			<th>Reg No</th>";
		$full_result_rows .= "			<th>Student</th>";
		$full_result_rows .= "			<th colspan='2'>Message</th>";
		$full_result_rows .= "		</tr>";
		$full_result_rows .= "	</thead>";
		$full_result_rows .= "	<tbody>";
		$full_result_rows .= $result_rows;
		$full_result_rows .= "	</tbody>";
		$full_result_rows .= "</table>";
		
		$response_message = "<h3>$sch_name</h3>";
		$response_message .= "<hr>";
		$response_message .= "<h4><span class='text-success'>$success_inserts</strong> successful inserts</span> ";
		$response_message .= "| <span class='text-danger'><strong>$fail_inserts</strong> failed inserts</span></h4>";
		$response_message .= "<hr>";
		$response_message .= $full_result_rows;
		$response_message .= "<hr>";
		$response_message .= "<br><br>$stats";
		
		// Success
		$response["error"] = false;
		$response["message"] = $response_message;
		$response["close_form"] = true;
		$response["popup"] = true;
		$response["insert_results"] = $insert_results;
 
        return $response;
    }

	//Upload fee data from excel file
    public function uploadFees($sch_id, $fees) {
        				
		
		$response = array();
		$fees_array = array();
		$insert_results = array();
		
		$user_id = USER_ID;
		$sch_name = $this->getSchoolName($sch_id);
		
		$created_by = USER_ID;
		$current_date = $this->getCurrentDate();

		$fees_array = $fees["fees"]; //print_r($fees_array); exit;
		
		$num_recs = count($fees_array); //echo $num_recs; exit;
		
		$success_inserts = 0; 
		$fail_inserts = 0;
		$result_row = ""; 
		
		for ($i = 0; $i < count($fees_array); $i++){

			$tmp = array();
							
			$reg_no = $fees_array[$i]["reg_no"];
			$year = $fees_array[$i]["year"];
			$amount = $fees_array[$i]["amount_paid"];
			$student_details = $this->getStudentData($reg_no, $sch_id, "", "", "");
			//echo $amount;exit;
			if ($student_details["error"]) {
				//no student with these details exist
				$tmp['error'] = true;
				$tmp['message'] = $student_details["message"];
				
			} else {
				
				$student_id = $student_details["student_id"]; 
				$full_names = $student_details["student_full_names"]; 
				$payment_mode = $fees_array[$i]["payment_mode"];
				$paid_by = $fees_array[$i]["paid_by"];
				$paid_at = $fees_array[$i]["paid_at"];
				$total_fees = $fees_array[$i]["total_fees"];
				
				//echo "reg - $reg_no --- full - $full_names --- paid_at - $paid_at"; exit;
				
				//save each fee item to db			
				$tmp = $this->createStudentFee($amount, $payment_mode, $paid_by, $paid_at, $student_id, $year);
			
			}
			
			if ($tmp['error']) { 
				$fail_inserts += 1; 
				$result_rows .= "<tr><td>" . $reg_no . "</td><td>" . $full_names . "</td><td colspan='2'><span class='text-danger'>" . $tmp['message'] . "</span></td></tr>";
			} else { 
				$success_inserts += 1; 
				$result_rows .= "<tr><td>" . $reg_no . "</td><td>" . $full_names . "</td><td>" . $this->format_num($amount, 2) . "</td><td><span class='text-success'>" . $tmp['message'] . "</span></td></tr>";
			}
			
			array_push($insert_results, $tmp);
			
		}
		
		$stats = $students["stats"];
		
		$full_result_rows = "<table class='table table-responsive response-table'>";
		$full_result_rows .= "	<thead>";
		$full_result_rows .= "		<tr>";
		$full_result_rows .= "			<th>Reg No</th>";
		$full_result_rows .= "			<th>Student Names</th>";
		$full_result_rows .= "			<th>Amount</th>";
		$full_result_rows .= "			<th>Message</th>";
		$full_result_rows .= "		</tr>";
		$full_result_rows .= "	</thead>";
		$full_result_rows .= "	<tbody>";
		$full_result_rows .= $result_rows;
		$full_result_rows .= "	</tbody>";
		$full_result_rows .= "</table>";
		
		$response_message = "<h3>$sch_name</h3>";
		$response_message .= "<hr>";
		$response_message .= "<h4><span class='text-success'>$success_inserts</strong> successful inserts</span> ";
		$response_message .= "| <span class='text-danger'><strong>$fail_inserts</strong> failed inserts</span></h4>";
		$response_message .= "<hr>";
		$response_message .= $full_result_rows;
		$response_message .= "<hr>";
		$response_message .= "<br><br>$stats";
		
		// Success
		$response["error"] = false;
		$response["message"] = $response_message;
		$response["close_form"] = true;
		$response["popup"] = true;
		$response["insert_results"] = $insert_results;
 
        return $response;
    }

	//Upload student data from excel file
    public function sendBulkSMSToUser($sch_id, $message, $messageType, $results_year, $fees_year, $term, $selected = array(), $send_data = array(), $student_data) {
        				
		$response = array();
		
		$insert_results = array();
		
		//get the message to send
		if ($messageType == "memo")
		{
			$sent_message = $message;	
		}
		
		$msg = $message;
		$sent_message = $msg;
		
		$created_by = USER_ID;
		$current_date = $this->getCurrentDate();

		$students2 = array();
		$students2 = $selected; //print_r($students2);exit;
		$num_recs = count($selected2); //echo $num_recs; 
		
		$success_inserts = 0; 
		$fail_inserts = 0;
		$result_row = ""; 
			
		$sch_name = $this->getSchoolName($sch_id);
		$bulk_sms_details = $this->getBulkSMSData($sch_id);
		$usr = $sch_id;
		$pass = $bulk_sms_details["passwd"];
		$src = $bulk_sms_details["default_source"];
		//print_r($bulk_sms_details);
		//echo "pass == $pass - src == $src"; exit;
			
		for ($i = 0; $i < count($students2); $i++){
			
			$tmp = array();
			$student_id = $students2[$i];
			
			//send student data
			if ($student_data) {
			
				$student_details = $this->getStudentData("", "", "", "", $student_id);
	
				$full_names = $student_details["student_full_names"];
				$guardian_name = $student_details["guardian_name"];	
				$phone_number = $student_details["guardian_phone"];
				$reg_no = $student_details["reg_no"];
				
				if ($messageType == "fees")
				{
					$sent_message = "";
					$sent_message_details = $this->getFeeBalance($student_id, $fees_year);
					if ($guardian_name) { $sent_message .= "Dear " . $guardian_name . ", "; }
					$sent_message .= "Your son/ daughter " . $full_names . " has a fee balance of " . $sent_message_details["bal"] . ". ";
					$sent_message .= $sch_name;
					//echo($sent_message); exit;	
				}
				
				if ($messageType == "results")
				{
					$sent_message = "";
					$result_period = "Term: " . $term . " Year: ". $results_year;
					$result_summary_details = $this->getStudentResultsGridListing($sch_id, $reg_no, $results_year, $term);
					$total_score = $result_summary_details["total_score"];
					$mean_score = $result_summary_details["mean_score"];
					$mean_grade = $result_summary_details["mean_grade"];
					$mean_points = $result_summary_details["mean_points"];
					$result_items_details = $result_summary_details["rows"];
					$subject_results = "";
					for ($i=0; $i<count($result_items_details); $i++) {
						$result = $result_items_details[$i];
						$subject_results .= $result["name"]. " - " .$result["score"]. " (" .$result["grade"]. ") ";
					}
					$subject_results .= " Total: " . $total_score . " [" .$mean_grade. "] ";
					
					//create message
					if ($guardian_name) { $sent_message .= "Dear " . $guardian_name . ", "; }
					$sent_message .= $full_names . " results for " . $result_period . ": ";
					$sent_message .= $subject_results . ". ";
					$sent_message .= $sch_name;
					//$sent_message .= $sch_name;
					//echo($sent_message); exit;
					//print_r($result_summary_details);	exit;
				}
				
			} else {
				$phone_number = $student_id;	
			}
			
			if ($phone_number) {
				//send each bulk sms
				$sent_message = urlencode($sent_message);
				$tmp = $this->sendBulkSMS($usr, $pass, $src, $phone_number, $sent_message, SCHOOL_MESSAGE_SMS);
			} else {
				//show error only for students
				if ($student_data) {
					$tmp["error"] = true;
					$tmp["noty_msg"] = true;
					$tmp["message"] = "Phone number missing for $full_names's guardian";
				}
				
			}
			
			if ($tmp['error']) { 
				$fail_inserts += 1; 
				$result_rows .= "<tr>";
				
				if ($student_data) {
					$result_rows .= "<td>" . $full_names . "</td>";
					$result_rows .= "<td>" . $guardian_name . "</td>";
				} else {
					$result_rows .= "<td>" . $phone_number . "</td>";
				}
				
				$result_rows .= "<td><span class='text-danger'>" . $tmp['message'] . "</span></td>";
				$result_rows .= "</tr>";
			} else { 
				$success_inserts += 1; 
				$result_rows .= "<tr>";
				
				if ($student_data) {
					$result_rows .= "<td>" . $full_names . "</td>";
					$result_rows .= "<td>" . $guardian_name . "</td>";
				} else {
					$result_rows .= "<td>" . $phone_number . "</td>";
				}
				
				$result_rows .= "<td><span class='text-success'>" . $tmp['message'] . "</span></td>";
				$result_rows .= "</tr>";
			}
			
			array_push($insert_results, $tmp);
			
		}
		
		$stats = $send_data["stats"];
		
		$full_result_rows = "<table class='table table-responsive response-table'>";
		$full_result_rows .= "	<thead>";
		$full_result_rows .= "		<tr>";
		
		if ($student_data) {
			$full_result_rows .= "			<th>Student Name</th>";
			$full_result_rows .= "			<th>Guardian Name</th>";
		} else {
			$full_result_rows .= "			<th>Phone Number</th>";
		}
		
		$full_result_rows .= "			<th>Message</th>";
		$full_result_rows .= "		</tr>";
		$full_result_rows .= "	</thead>";
		$full_result_rows .= "	<tbody>";
		$full_result_rows .= $result_rows;
		$full_result_rows .= "	</tbody>";
		$full_result_rows .= "</table>";
		
		$response_message = "<h3>$sch_name</h3>";
		$response_message .= "<hr>";
		$response_message .= "<h4><span class='text-success'>$success_inserts</strong> successful inserts</span> ";
		$response_message .= "| <span class='text-danger'><strong>$fail_inserts</strong> failed inserts</span></h4>";
		$response_message .= "<hr>";
		$response_message .= $full_result_rows;
		$response_message .= "<hr>";
		$response_message .= "<br><br>$stats";
		
		// Success
		$response["error"] = false;
		$response["message"] = $response_message;
		$response["close_form"] = true;
		$response["popup"] = true;
		$response["insert_results"] = $insert_results;
 
        return $response;
    }
	
	//Upload student data from excel file
    public function uploadStudents($sch_id, $students) {
        				
		$response = array();
		$students2 = array();
		$insert_results = array();
		
		$user_id = USER_ID;
		$sch_name = $this->getSchoolName($sch_id);
		
		$created_by = USER_ID;
		$current_date = $this->getCurrentDate();

		$students2 = $students["students"]; //print_r($students);exit;
		
		$num_recs = count($students2); //echo $num_recs; 
		
		$success_inserts = 0; 
		$fail_inserts = 0;
		$result_row = ""; 
		
		for ($i = 0; $i < count($students2); $i++){
			$tmp = array();
			$reg_no = $students2[$i]["reg_no"];
			$full_names = $students2[$i]["full_names"];
			$admin_date = $students2[$i]["admin_date"];
			$dob = $students2[$i]["dob"];
			$index_no = $students2[$i]["index_no"];
			$nationality = $students2[$i]["nationality"];
			$religion = $students2[$i]["religion"];
			$previous_school = $students2[$i]["previous_school"];
			$house = $students2[$i]["house"];
			$club = $students2[$i]["club"];
			$email = $students2[$i]["email"];
			$town = $students2[$i]["town"];
			$village = $students2[$i]["village"];
			$student_profile = $students2[$i]["student_profile"];
			$guardian_name = $students2[$i]["guardian_name"];
			$guardian_address = $students2[$i]["guardian_address"];
			$guardian_phone = $students2[$i]["guardian_phone"];
			$guardian_id_card = $students2[$i]["guardian_id_card"];
			$guardian_relation = $students2[$i]["guardian_relation"];
			$guardian_occupation = $students2[$i]["guardian_occupation"];
			$location = $students2[$i]["location"];
			$county = $students2[$i]["county"];
			$disability = $students2[$i]["disability"];
			$gender = $students2[$i]["gender"];
			$current_class = $students2[$i]["current_class"];
			$stream = $students2[$i]["stream"];
			$constituency = $students2[$i]["constituency"];
			
			//save each student item to db
			$tmp = $this->createStudent($full_names, $reg_no, $sch_id, $admin_date, $student_profile, $guardian_name, $guardian_phone, $guardian_address, $dob, $index_no, $nationality, $religion, $previous_school, $house, $club, $guardian_id_card, $guardian_relation, $guardian_occupation, $email, $town, $current_class, $village, $county, $location, $disability, $gender, $stream, $constituency);
			
			if ($tmp['error']) { 
				$fail_inserts += 1; 
				$result_rows .= "<tr><td>" . $tmp['reg_no'] . "</td><td>" . $tmp['full_names'] . "</td><td><span class='text-danger'>" . $tmp['message'] . "</span></td></tr>";
			} else { 
				$success_inserts += 1; 
				$result_rows .= "<tr><td>" . $tmp['reg_no'] . "</td><td>" . $tmp['full_names'] . "</td><td><span class='text-success'>" . $tmp['message'] . "</span></td></tr>";
			}
			
			array_push($insert_results, $tmp);
			
		}
		
		$stats = $students["stats"];
		
		$full_result_rows = "<table class='table table-responsive response-table'>";
		$full_result_rows .= "	<thead>";
		$full_result_rows .= "		<tr>";
		$full_result_rows .= "			<th>Reg No</th>";
		$full_result_rows .= "			<th>Student Names</th>";
		$full_result_rows .= "			<th>Message</th>";
		$full_result_rows .= "		</tr>";
		$full_result_rows .= "	</thead>";
		$full_result_rows .= "	<tbody>";
		$full_result_rows .= $result_rows;
		$full_result_rows .= "	</tbody>";
		$full_result_rows .= "</table>";
		
		$response_message = "<h3>$sch_name</h3>";
		$response_message .= "<hr>";
		$response_message .= "<h4><span class='text-success'>$success_inserts</strong> successful inserts</span> ";
		$response_message .= "| <span class='text-danger'><strong>$fail_inserts</strong> failed inserts</span></h4>";
		$response_message .= "<hr>";
		$response_message .= $full_result_rows;
		$response_message .= "<hr>";
		$response_message .= "<br><br>$stats";
		
		// Success
		$response["error"] = false;
		$response["message"] = $response_message;
		$response["close_form"] = true;
		$response["popup"] = true;
		$response["insert_results"] = $insert_results;
 
        return $response;
    }
	
	//create a new subject
    public function createSubject($subject_name, $short_name, $code, $level) {
        				
		$response = array();
		
		if (!$subject_name || !$short_name || !$code) {
			$response["message"] = "Please fill in all required details";
			$response["error"] = true;
			$response["ref"] = "none";
		} else {

			//check if subject already exists for this level
			if (!$this->subjectExists($subject_name, $level)) {
				
				$created_by = USER_ID;
				$current_date = $this->getCurrentDate();
				
				//generate permalink
				$perm = $this->generate_seo_link($code,$replace = '-',$remove_words = true,$words_array = array());
				//echo $perm; exit;
				
				//check if code exists
				if ($this->checkIfSubjectPermExists($perm)) {
					$perm = $perm . "-" . $this->generateCode(3, false, 'l');
					if ($this->checkIfSubjectPermExists($perm)) {
						$perm = $perm . "-" . $this->generateCode(3, false, 'l');
						if ($this->checkIfSubjectPermExists($perm)) {
							$perm = $perm . "-" . $this->generateCode(3, false, 'l');
						}
					} 
				} 
				
				// insert query
				$query = "INSERT INTO sch_subjects(name, short_name, code, school_level, created_by, created_at) VALUES(?, ?, ?, ?, ?, ?)";
				$stmt = $this->conn->prepare($query);
				$stmt->bind_param("sssiis", $subject_name, $short_name, $perm, $level, $created_by, $current_date);
				$result = $stmt->execute();
				$stmt->close();
				
				// Check for successful insertion
				if ($result) {
					$new_id = $this->conn->insert_id;
					$response["error"] = false;
					$response["message"] = "Subject successfully created";
					$response["id"] = $new_id;			
				} else {
					// Failed to create chat
					$response["error"] = true;
					$response["error_type"] = ERROR_OCCURED;
					$response["message"] = "An error occurred whle creating subject";
				}				
				
			} else {
			
				$response["error"] = true;
				$response["error_type"] = ERROR_OCCURED;
				$response["message"] = "Subject \"$subject_name\" already exists for this level";
			}
		
		}
 
        return $response;
    }
	
	//create a new subject
    public function createUserGroup($group_name, $group_description) {
        				
		$response = array();
		
		if (!$group_name) {
			$response["message"] = "Please fill in all required details";
			$response["error"] = true;
			$response["ref"] = "none";
		} else {

			//check if group already exists for this user
			if (!$this->userGroupExists($group_name, USER_ID)) {
				
				$created_by = USER_ID;
				$current_date = $this->getCurrentDate();
				
				// insert query
				$query = "INSERT INTO groups(name, description, created_by, created_at, updated_at) VALUES(?, ?, ?, ?, ?)";
				$stmt = $this->conn->prepare($query);
				$stmt->bind_param("ssiis", $group_name, $group_description, $created_by, $current_date, $current_date);
				$result = $stmt->execute();
				$stmt->close();
				
				// Check for successful insertion
				if ($result) {
					$new_id = $this->conn->insert_id;
					$response["error"] = false;
					$response["message"] = "Group successfully created";
					$response["clear_form"] = true;
					$response["reload_page"] = true;	
					$response["id"] = $new_id;			
				} else {
					// Failed to create chat
					$response["error"] = true;
					$response["error_type"] = ERROR_OCCURED;
					$response["message"] = "An error occurred whle creating group";
				}
				
			} else {
			
				$response["error"] = true;
				$response["error_type"] = ERROR_OCCURED;
				$response["message"] = "Group \"$group_name\" already exists";
			}
		
		}
 
        return $response;
    }
	
	//create a new SCHOOL
    public function createSchool($sch_name, $sch_category, $sch_province, $status, $motto, $phone1, $phone2, $sms_welcome1, $sms_welcome2, $address) {
        				
		$response = array();
		
		if (!$sch_name || !$sch_category) {
			$response["message"] = "Please fill in all required details";
			$response["error"] = true;
			$response["ref"] = "none";
		} else {

			$created_by = USER_ID;
			$current_date = $this->getCurrentDate();
			
			// insert query
			$query = "INSERT INTO sch_ussd(sch_name, sch_category, sch_province, status, motto, phone1, phone2, sms_welcome1";
			$query .= ", sms_welcome2, address, created_by, created_at) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
			$stmt = $this->conn->prepare($query);
			$stmt->bind_param("siiissssssis", $sch_name, $sch_category, $sch_province, $status, $motto, $phone1, $phone2, $sms_welcome1, $sms_welcome2, $address, $created_by, $current_date);
			$result = $stmt->execute();
			$stmt->close();
			
			
			// Check for successful insertion
			if ($result) {
				$new_id = $this->conn->insert_id;
				$response["error"] = false;
				$response["message"] = "School successfully created";
				$response["clear_form"] = true;
				$response["ref"] = "none";
				$response["id"] = $new_id;			
			} else {
				// Failed to create chat
				$response["error"] = true;
				$response["ref"] = "none";
				$response["error_type"] = ERROR_OCCURED;
				$response["message"] = "An error occurred whle creating school";
			}
		
		}
 
        return $response;
    }
	
	//create a new STUDENT
    public function createStudent($full_names, $reg_no, $sch_id, $admin_date=NULL, $student_profile=NULL, $guardian_name=NULL, $guardian_phone=NULL, $guardian_address=NULL, $dob=NULL, $index_no=NULL, $nationality=NULL, $religion=NULL, $previous_school=NULL, $house=NULL, $club=NULL, $guardian_id_card=NULL, $guardian_relation=NULL, $guardian_occupation=NULL, $email=NULL, $town=NULL, $current_class=NULL, $village=NULL, $county=NULL, $location=NULL, $disability=NULL, $gender=NULL, $stream=NULL, $constituency=NULL) {
        				
		$response = array();
		
		$sch_name = $this->getSchoolName($sch_id);
		
		if (!$full_names || !$reg_no || !$sch_id) {
			$response["message"] = "Please fill in all required details";
			$response["error"] = true;
			$response["ref"] = "none";
		} else if ($this->isStudentExists($sch_id, $reg_no)){
			$response["message"] = "Student with reg no: <strong>$reg_no</strong>  already exists";
			$response["reg_no"] = $reg_no;
			$response["full_names"] = $full_names;
			$response["error"] = true;
		} else if ($this->isStudentExists($sch_id, $index_no)){
			$response["message"] = "Student with index no: <strong>$index_no</strong> already exists";
			$response["reg_no"] = $reg_no;
			$response["full_names"] = $full_names;
			$response["error"] = true;
		} else {

			$created_by = USER_ID;
			$created_at = $this->getCurrentDate();
			
			//if ($dob) { $dob = $this->reformat_date($dob); }
			//if ($admin_date) { $admin_date = $this->reformat_date($admin_date); }
			
			// insert query
			$query = "INSERT INTO sch_students(full_names, reg_no, sch_id, admin_date, student_profile, guardian_name";
			$query .= ", guardian_phone, guardian_address, dob, index_no, nationality, religion, previous_school, house";
			$query .= ", club, guardian_id_card, guardian_relation, guardian_occupation, email, town, current_class, village";
			$query .= ", county, location, disability, gender, stream, constituency, created_by, created_at)";
			$query .= " VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; 
			//echo "$query - $full_names, $reg_no, $sch_id, $student_profile, $mobile1, $mobile2, $guardian_name, $guardian_phone, $guardian_address, $dob, $index_no, $nationality, $religion, $previous_school, $house, $club, $guardian_id_card, $guardian_relation, $guardian_occupation, $email, $town, $current_class, $village, $sublocation, $location, $district, $disability, $gender, $stream, $constituency, $created_by, $created_at";
			$stmt = $this->conn->prepare($query);
			$stmt->bind_param("ssisssssssssssssssssssssssssis", $full_names, $reg_no, $sch_id, $admin_date, $student_profile, $guardian_name, $guardian_phone, $guardian_address, $dob, $index_no, $nationality, $religion, $previous_school, $house, $club, $guardian_id_card, $guardian_relation, $guardian_occupation, $email, $town, $current_class, $village, $county, $location, $disability, $gender, $stream, $constituency, $created_by, $created_at);
			$result = $stmt->execute();
			$stmt->close();
			
			// Check for successful insertion
			if ($result) {
				$new_id = $this->conn->insert_id;
				$response["error"] = false;
				$response["clear_form"] = true;
				$response["message"] = "Record successfully created";
				$response["reg_no"] = $reg_no;
				$response["full_names"] = $full_names;
				$response["ref"] = "none";
				$response["id"] = $new_id;			
			} else {
				// Failed to create record
				$response["error"] = true;
				$response["reg_no"] = $reg_no;
				$response["full_names"] = $full_names;
				$response["error_type"] = ERROR_OCCURED;
				$response["message"] = "An error occurred whle inserting record";
			}
		
		}
 
        return $response;
    }
	
	//update activity
	public function updateActivity($id, $name, $description, $venue, $start_at, $end_at) {
		
		$response = array();
		
		$start_at = $this->reformatDate($start_at);
		$end_at = $this->reformatDate($end_at);
		
		$query = "UPDATE sch_activities SET name = ?, description = ?, venue = ?, start_at = ?, end_at = ? WHERE id = ? ";
		$stmt = $this->conn->prepare($query); 
        $stmt->bind_param("sssssi", $name, $description, $venue, $start_at, $end_at, $id);

		if ($stmt->execute()) {
            $response = array();
			$response["message"] = "Successfully updated";
			$response["close_form"] = true;
			$response["error"] = false;
        } else {
            $response["message"] = "An error occured";
			$response["error"] = true;
        }
		
		$stmt->close(); 
		
		return $response;
			
    }
	
	//update group perms
	public function updateGroupPermissions($group_id, $check_name, $check_val, $sch_id) {
		
		$response = array();
		$proceed = false;
		
		$permission_id = $this->getPermissionId($check_name);
		$created_at = $this->getCurrentDate();
		$created_by = USER_ID;
		
		if ($check_val=='true') {
			//check if permission exists 
			if ($this->hasRole($group_id, $check_name, true)){
				//ignore
				$response['error'] = false;	
			} else {
				//add the permission if checkval is on	
				$query = "INSERT INTO group_permissions (group_id, permission_id, sch_id, created_by, created_at) ";
				$query .= " VALUES (?, ?, ?, ?, ?) ";
				$stmt = $this->conn->prepare($query); 
				$stmt->bind_param("iiiis", $group_id, $permission_id, $sch_id, $created_by, $created_at);
				$proceed = true;
			}
		} else {
			//check if permission exists 
			if ($this->hasRole($group_id, $check_name, true)){
				//remove permission	
				//remove the permission if checkval is off	
				$query = "DELETE FROM group_permissions WHERE group_id = ? AND permission_id = ? ";
				$stmt = $this->conn->prepare($query); 
				$stmt->bind_param("ii", $group_id, $permission_id);
				$proceed = true;
			} else {
				//ignore
				$response['error'] = false;
			}	
		}
		
		if ($proceed) {
	
			if ($stmt->execute()) {
				$response = array();
				$response["message"] = "Successfully updated";
				$response["error"] = false;
				//$response["query"] = $query . " " . $group_id . " " . $permission_id;
			} else {
				$response["message"] = "An error occured";
				$response["error"] = true;
			}
			
			$stmt->close(); 
		
		}		
		
		return $response;
			
    }
	
	public function getGroupPermissions($group_name) {
		
	}
	
	//verify sms code
	public function verifySMSCode($phone_number, $sms_code) {
		
		$phone_number = $this->formatPhoneNumber($phone_number);
		
		$response = array();
 
		// check sms query
		$query = "SELECT id FROM sms_codes WHERE mobile = ? AND message = ? AND status != 1";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("ss", $phone_number, $sms_code); // number of params s - string, i - integer, d - decimal, etc
		$result = $stmt->execute();
		$stmt->bind_result($unverified_id);
		$stmt->fetch();
		$stmt->store_result();
		$stmt->close();
		
		if ($unverified_id) {
			//update user record and return
			$response["error"] = false;
			$response["message"] = "Code verified successfully";
			//update the user account and return user
			$this->updateUser($phone_number);
			//return user json object
			$response["user"] = $this->getUserDetails($phone_number);
			
		} else {
			// Failed to create sms
			$response["error"] = true;
			$response["message"] = "Invalid code";
		}
 
        return $response;
		
    }
	
	
	//change registration phone number
	public function changeRegPhone($old_phone_number, $new_phone_number) {
		
		$response = array();
		
		$success = 1;
		
		//check if phone number is valid before proceeding
		if (!$this->isNumberValid($new_phone_number)) {
			// Invalid phone number
			$response["error"] = true;
			$response["message"] = "Invalid phone number. Check number again.";
			$response["user"] = null;
			$success = 0;
		}
		
		//format phone numbers
		$new_phone_number = $this->formatPhoneNumber($new_phone_number);
		$old_phone_number = $this->formatPhoneNumber($old_phone_number);
		
		//check if old and new numbers are the same
		if ($new_phone_number == $old_phone_number) {
			// Invalid phone number
			$response["error"] = true;
			$response["message"] = "Phone number is the same as previous one. Try another.";
			$response["user"] = null;
			$success = 0;
		}
		//check if old number exists. if not, dont proceed
		if (!$this->isUserExists($old_phone_number)) {
			// Non existent account, stop here, dont proceed
			$response["error"] = true;
			$response["message"] = "Original Phone number is non existent. Please create account first.";
			$response["user"] = null;
			$success = 0;
		}
			
		if ($success) {
			
			$response = array();
	 
			// First check if new phone number already exist in db
			if (!$this->isUserExists($new_phone_number)) {
				
					// update users phone number query
					$stmt = $this->conn->prepare("UPDATE clients SET phone_number = ? WHERE phone_number = ? ");
					$stmt->bind_param("ss", $new_phone_number, $old_phone_number);
					$result = $stmt->execute();
					$stmt->close();
		 
					// Check for successful insertion
					if ($result) {
						// User successfully inserted
						$code = $this->generateCode(5); //generate random 5 digit code
						$response["error"] = false;
						$response["message"] = "Phone number updated";
						$response["user"] = $this->getUserDetails($new_phone_number);
						//resend the sms with resent registration sms flag
						$this->disablePreviousSentSMS($old_phone_number, REGISTRATION_SMS); //Disable prev sent but unused sms codes
						$response["sms"] = $this->sendSMS($new_phone_number, $code, RESENT_REGISTRATION_SMS);
						                        
					} else {
						// Failed to create user
						$response["error"] = true;
						$response["message"] = "An error occurred";
						$response["user"] = "";
					}
				
			} else {
				// User with same phone number already existed in the db
				$response["error"] = true;
				$response["message"] = "New Phone number already exists. Try another.";
				$response["user"] = $this->getUserDetails($old_phone_number);
			}
		
		}
 
        return $response;
		
    }
	
	public function updateUser($phone_number)
	{
				
		$current_date = $this->getCurrentDate();
		
		$response = array();
 
		// update sms_codes table record
		$query = "UPDATE sms_codes SET status = 1 WHERE mobile = ? ";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("s", $phone_number); // number of params s - string, i - integer, d - decimal, etc
		$result = $stmt->execute();
		$stmt->close();
		
		if ($result) {
			//update user record and return
			$queryUser = "UPDATE clients SET status = 1, activated_at = ? WHERE phone_number = ? ";
			$stmtUser = $this->conn->prepare($queryUser);
			$stmtUser->bind_param("ss", $current_date, $phone_number); // number of params s - string, i - integer, d - decimal, etc
			$resultUser = $stmtUser->execute();
			$stmtUser->close();
		
			$response["error"] = false;
			$response["message"] = "User verified successfully";
			//update the user account and return user
			
		} else {
			// Failed to create sms
			$response["error"] = true;
			$response["message"] = "An error occurred in verifying user";
		}
 
        return $response;	
	}
	
	///////////////////////////////////// END SMS FUNCTIONS /////////////////////////////////////
	
 
    // updating user FCM registration ID
    public function updateFcmToken($user_id, $gcm_registration_id) {
        $response = array();
		$query = "UPDATE clients SET gcm_registration_id = ? WHERE id = ?";
        
		if ($stmt = $this->conn->prepare($query)) {
		
			$stmt->bind_param("si", $gcm_registration_id, $user_id);
	 
			if ($stmt->execute()) {
				// User successfully updated
				$response["error"] = false;
				$response["message"] = 'FCM registration ID updated successfully';
			} else {
				// Failed to update user
				$response["error"] = true;
				$response["message"] = "Failed to update FCM registration ID";
				$stmt->error;
			}
			$stmt->close();
		
		} else {
			
			$response["error"] = true;
			$response["message"] = $this->conn->error;
			$response['error_type'] = AN_ERROR_OCCURED_ERROR;
		
		}
		
		//$response["query"] = $query;
		$response["gcm_registration_id"] = $gcm_registration_id;
		$response["user_id"] = $user_id;
 
        return $response;
    }
 
    // fetching single user by id
	public function getUser($user_id) {
        $stmt = $this->conn->prepare("SELECT phone_number, full_names, gcm_registration_id, created_at FROM clients WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            // $user = $stmt->get_result()->fetch_assoc();
            $stmt->bind_result($phone_number, $full_names, $gcm_registration_id, $created_at);
            $stmt->fetch();
			$stmt->store_result();
            $user = array();
			if (!$full_names){ $full_names = $phone_number; }
            $user["user_id"] = $user_id;
            $user["phone_number"] = $phone_number;
			$user["full_names"] = $full_names;
			$user["user_image"] = $this->getPhoto(USER_PROFILE_PHOTO, $user_id, THUMB_IMAGE);
			$user["user_large_image"] = $this->getPhoto(USER_PROFILE_PHOTO, $user_id);
            $user["gcm_registration_id"] = $gcm_registration_id;
            $user["created_at"] = $this->adjustDate(NULL, $this->php_date($created_at), NULL);
            $stmt->close();
            return $user;
        } else {
            return NULL;
        }
    }
	
	//get total unread messages for all user conversations/ chats
	function getUnreadMessagesTotal($user_id)
	{
		
		$response = array();
		
		//check if user has account balance
		$query  = "SELECT count(m.conversation_id) AS unread FROM messages m ";
		$query .= " JOIN conversations c ON m.conversation_id=c.id ";
		$query .= " WHERE CASE ";
		$query .= " WHEN c.user_one = ? ";
		$query .= " THEN m.user_one_viewed != 1 AND m.user_id != ? ";
		$query .= " WHEN c.user_two = ? ";
		$query .= " THEN m.user_two_viewed != 1 AND m.user_id != ? ";
		$query .= " END  ";
		
		//echo $query; exit;
		
		if ($stmtCheckUnread = $this->conn->prepare($query)){
			$stmtCheckUnread->bind_param("iiii", $user_id, $user_id, $user_id, $user_id);
			$stmtCheckUnread->execute();
			$stmtCheckUnread->store_result();
			$stmtCheckUnread->bind_result($unread);
			$stmtCheckUnread->fetch();
			//end check
			if ($unread) { $total = $unread; } else { $total = 0; }
			$response["error"] = false;
			
		} else {
			$response["error"] = true;
			$stmtCheckUnread["message"] = $this->conn->error;
			$stmtCheckUnread['error_type'] = AN_ERROR_OCCURED_ERROR;
			return $stmtCheckUnread;
		}
		
		$response["total"] = $total;
		
		return $response;
	}
 
    // fetching multiple users by ids
    public function getUsers($user_ids) {
 
        $users = array();
        if (sizeof($user_ids) > 0) {
            $query = "SELECT id, phone_number, gcm_registration_id, created_at FROM clients WHERE id IN (";
 
            foreach ($user_ids as $user_id) {
                $query .= $user_id . ',';
            }
 
            $query = substr($query, 0, strlen($query) - 1);
            $query .= ')';
 
            /*
			$stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
 
            while ($user = $result->fetch_assoc()) {
                $tmp = array();
                $tmp["user_id"] = $user['id'];
                $tmp["phone_number"] = $user['phone_number'];
                $tmp["gcm_registration_id"] = $user['gcm_registration_id'];
                $tmp["created_at"] = $user['created_at'];
                array_push($users, $tmp);
            }
			*/
        }
 
        return $users;
    }
	
	//create a new chat
    public function createNewChat($creator_id, $recipient_id, $student_id="") {
        				
		$response = array();
 
		// First check if chat already exists
		if (!$this->isChatExists($creator_id, $recipient_id, $student_id)) {
			
				$current_date = $this->getCurrentDate();
				// insert query
				$query = "INSERT INTO conversations(user_one, user_two, student_id, created_by, created_at) VALUES(?, ?, ?, ?, ?)";
				$stmt = $this->conn->prepare($query);
				$stmt->bind_param("iiiis", $creator_id, $recipient_id, $student_id, $creator_id, $current_date);
				$result = $stmt->execute();
				$stmt->store_result();
				$stmt->close();
				
				// Check for successful insertion
				if ($result) {
					
					$chat_id = $this->conn->insert_id;
					$response["error"] = false;
					$response["message"] = "Chat created";
					$response["chat_id"] = $chat_id;
					$response["user_image"] = $this->getPhoto(USER_PROFILE_PHOTO, $recipient_id, THUMB_IMAGE);
					$response["user_large_image"] = $this->getPhoto(USER_PROFILE_PHOTO, $recipient_id);
					$response["recent_message_created_at"] = $this->adjustDate("d-M-Y", $this->php_date($current_date), NULL);
					$response["recent_message"] = "";
					$response["full_names"] = $this->getFullNames($recipient_id);
					
				} else {
					
					// Failed to create chat
					$response["error"] = true;
					$response["error_type"] = ERROR_OCCURED;
					$response["message"] = "An error occurred whle creating chat";
					
				}
			
		} else {
			
			$response["error"] = true;
			$response["error_type"] = "chat_exists";
			$response["message"] = "Chat already exists";
			$response["chat-item"] = $this->getChatDetails($creator_id, $recipient_id, $student_id);
			//$response["full_names"] = $this->getStudentNames($this->getStudentId($creator_id, $recipient_id)) . " - " . $this->getFullNames($recipient_id);
			
		}
 
        return $response;
    }
	
	//add new chat message
	public function addChatMessage($user_id, $chat_id, $message) {
        $response = array();
		
		$query = "INSERT INTO messages (conversation_id, user_id, message, created_at, ip) "; 
		$query .= " VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
		$current_date = $this->getCurrentDate();
		$stmt->store_result();
		$the_ip = $this->get_ip();
        $stmt->bind_param("iisss", $chat_id, $user_id, $message, $current_date, $the_ip);
 
        if ($result = $stmt->execute()) {
            
			$response['error'] = false;
			$message_id = $this->conn->insert_id;
            // get the message
			$query = "SELECT m.id, m.user_id, first_name, last_name, c.user_group, c.phone_number, m.conversation_id, m.message, m.created_at";
			$query .= " FROM messages m ";
			$query .= " LEFT JOIN clients c ON  m.user_id = c.id ";
			$query .= " WHERE m.id = ? ";
			
            $stmtRead = $this->conn->prepare($query);
            $stmtRead->bind_param("i", $message_id);
            if ($stmtRead->execute()) {
				$stmtRead->store_result();
				$stmtRead->bind_result($id, $user_id, $first_name, $last_name, $user_group, $phone_number, $chat_id, $message, $created_at);
                $stmtRead->fetch();
                $tmp = array();
				if ($user_group == SCHOOL_ADMIN_USER_ID) { $photo_field = SCHOOL_PROFILE_PHOTO; } else { $photo_field = USER_PROFILE_PHOTO; }	
                $tmp['message_id'] = $id;
                $tmp['user_id'] = $user_id;
				$tmp['chat_id'] = $chat_id;
				$tmp['recipient_id'] = $this->getRecipientId($user_id, $chat_id);
				$tmp['full_names'] = $first_name . " ". $last_name;
				$tmp['phone_number'] = $phone_number;
				$tmp['user_image'] = $this->getPhoto($photo_field, $user_id, THUMB_IMAGE);
				$tmp['user_large_image'] = $this->getPhoto($photo_field, $user_id);
				$tmp['message'] = $message;
                $tmp['created_at'] = $this->smartdate($created_at);
                $response['message'] = $tmp;
								
				//update conversations table "updated_at" field
				$queryUpdate  = "UPDATE conversations SET updated_at = ? WHERE id = ? ";
				$stmtUpdate = $this->conn->prepare($queryUpdate);
				$stmtUpdate->bind_param("si", $current_date, $chat_id);
				/* execute statement */
				$stmtUpdate->execute();			
				$stmtUpdate->close();
			
            }
        } else {
            $response['error'] = true;
            $response['message'] = 'Failed to send message';
        }
 		//print_r($response);
        return $response;
    }
	
	function getRecipientId($user_id, $chat_id){
		// get the message
		$query = "SELECT user_one, user_two FROM conversations WHERE id = ? AND (user_one = ? OR user_two = ?) ";
		if($stmtRead = $this->conn->prepare($query)){
			$stmtRead->bind_param("iii", $chat_id, $user_id, $user_id);
			$stmtRead->execute();
			$stmtRead->bind_result($user_id1, $user_id2);
			$stmtRead->fetch();
			//$tmp = array();
		}else{
		   //error !! don't go further
		   var_dump($this->conn->error);
		}		
		
		if ($user_id == $user_id1){ return $user_id2; } else { return $user_id1; }
	}
 
    // new data saved for this topic group
    public function addMessage($user_id, $ticket_id, $message) {
        $response = array();
		
 
        $stmt = $this->conn->prepare("INSERT INTO notifications (ticket_id, client_id, notification_text, created_at) values(?, ?, ?, '".$this->getCurrentDate()."')");
        $stmt->bind_param("iis", $ticket_id, $user_id, $message);
 
        //$result = $stmt->execute();
 
        if ($result = $stmt->execute()) {
            $response['error'] = false;
			$message_id = $this->conn->insert_id;
            // get the message
			$query = "SELECT n.id as message_id, n.client_id AS client_id, c.id AS center_id, s.id AS service_id, ticket_id, ";
			$query .= "notification_text, n.created_at AS created_at FROM notifications n JOIN tickets t ON t.id=n.ticket_id ";
			$query .= " JOIN centers c ON c.id = t.center_id ";
			$query .= " JOIN services s ON s.id = t.service_id WHERE n.id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $message_id);
            if ($stmt->execute()) {
                $stmt->bind_result($id, $user_id, $center_id, $service_id, $ticket_id, $message, $created_at);
                $stmt->fetch();
                $tmp = array();
				$the_ip = $this->get_ip();
                $tmp['message_id'] = $id;
                $tmp['ticket_id'] = $ticket_id;
				$tmp['center_id'] = $center_id;
				$tmp['service_id'] = $service_id;
                $tmp['message'] = $message;
				$tmp['system_date'] = $this->getCurrentDate();
				//$tmp['the_ip'] = $the_ip;
                $tmp['created_at'] = $this->adjustDate(NULL, $this->php_date($created_at), NULL);
				//$tmp['my_ip_data'] = $this->getv4IpData($the_ip);
                $response['message'] = $tmp;
            }
        } else {
            $response['error'] = true;
            $response['message'] = 'Failed to send message';
        }
 		//print_r($response);
        return $response;
    }
	
	
	private function get_ip() {
		//Just get the headers if we can or else use the SERVER global
		if ( function_exists( 'apache_request_headers' ) ) {
			$headers = apache_request_headers();
		} else {
			$headers = $_SERVER;
		}
		//Get the forwarded IP if it exists
		if ( array_key_exists( 'X-Forwarded-For', $headers ) && filter_var( $headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) ) {
			$the_ip = $headers['X-Forwarded-For'];
		} elseif ( array_key_exists( 'HTTP_X_FORWARDED_FOR', $headers ) && filter_var( $headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 )
		) {
			$the_ip = $headers['HTTP_X_FORWARDED_FOR'];
		} else {
			
			$the_ip = filter_var( $_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 );
		}
		return $the_ip;
	}
	
	// fetching user account
    public function getSchoolData($phone_number, $school_id) {
		
		$school = array();
		
		$phone_number = $this->formatPhoneNumber($phone_number);
		
		//get school data
		$query = "SELECT sch_id, sch_name, address, province, category, extra, sch_profile, events_calender, phone1, phone2, motto, sms_welcome1, sms_welcome2, sch_paybill_no FROM sch_ussd ";
		$query .= " WHERE sch_id = ? ";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i", $school_id);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($sch_id, $sch_name, $address, $province, $category, $extra, $sch_profile, $events_calendar, $phone1, $phone2, $motto, $sms_welcome1, $sms_welcome2, $sch_paybill_no);
		$stmt->fetch();
		if ($sch_name) {
            
            $school["sch_id"] = $sch_id;
			$school["sch_name"] = $sch_name;
			if ((!$address) || is_null($address)) { $address = "None"; }
			$school["address"] = $address;
			if ((!$province) || is_null($province)) { $province = "None"; }
			$school["province"] = $province;
			$school["category"] = $category;
			$school["extra"] = $extra;
			if ((!$sch_profile) || is_null($sch_profile)) { $sch_profile = "None"; }
			$school["sch_profile"] = $sch_profile;
			if ((!$events_calendar) || is_null($events_calendar)) { $events_calendar = "None"; }
			$school["events_calendar"] = $events_calendar;
			if ((!$phone1) || is_null($phone1)) { $phone1 = "None"; }
			$school["phone1"] = $phone1;
			if ((!$phone2) || is_null($phone2)) { $phone2 = "None"; }
			$school["phone2"] = $phone2;
			$school["motto"] = $motto;
			$school["sms_welcome1"] = $sms_welcome1;
			$school["sch_paybill_no"] = $sch_paybill_no;
			$school["sch_image"] = $this->getPhoto(SCHOOL_PROFILE_PHOTO, $sch_id, THUMB_IMAGE);
			$school["sch_large_image"] = $this->getPhoto(SCHOOL_PROFILE_PHOTO, $sch_id);
			$school["error"] = false;
            $stmt->close();
        } else {
			$school["error"] = true;
			$school["message"] = "An error occured";
        }
		return $school; 
    }
	
	// fetching bulk sms data
    public function getBulkSMSData($username) {
		
		$response = array();
				
		//get school data
		$query = "SELECT passwd,alphanumeric_id,fullname,rights,active,default_sid,default_source";
		$query .= ",relationship,home_ip,default_priority,default_dest,default_msg,sms_balance,sms_expiry,routes,last_updated";
		$query .= " FROM bulk_sms_users ";
		$query .= " WHERE username = ? ";
		$stmt = $this->conn->prepare($query); //echo "$query - $username == ";
		$stmt->bind_param("s", $username);
		$result = $stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($passwd, $alphanumeric_id, $fullname, $rights, $active, $default_sid, $default_source, $relationship, $home_ip, $default_priority, $default_dest, $default_msg, $sms_balance, $sms_expiry, $routes, $last_updated);
		$stmt->fetch();
		if ($result) {
            
            $response["username"] = $username;
			$response["passwd"] = $passwd;
			$response["alphanumeric_id"] = $alphanumeric_id;
			$response["fullname"] = $fullname;
			$response["rights"] = $rights;
			$response["active"] = $active;
			$response["default_sid"] = $default_sid;
			$response["default_source"] = $default_source;
			$response["relationship"] = $relationship;
			$response["home_ip"] = $home_ip;
			$response["default_priority"] = $default_priority;
			$response["default_dest"] = $default_dest;
			$response["default_msg"] = $default_msg;
			$response["sms_balance"] = $sms_balance;
			$response["sms_expiry"] = $sms_expiry;
			$response["routes"] = $routes;
			$response["last_updated"] = $last_updated;
			$response["error"] = false;
            
        } else {
			$response["error"] = true;
			$response["message"] = "An error occured";
        }
		$stmt->close();
		
		return $response; 
		
    }
	
	// fetch students listing
    public function getStudentListing($sch_id, $page, $search_text="", $grid_list=NULL) {

		$students = array();
		
		if (!$page){ $page=1; }
		$lperpage = 20; //default num records
		$offset = ($page - 1) * $lperpage;

		if ($search_text) {
			$search_text = strtolower(trim($search_text));
			$search_text = $this->clean($search_text);
			$split_text = explode(" ",$search_text);
			$num_items = count($split_text);
			$full_article_search_text = "";
			for ($i=0;$i<$num_items;$i++) {
				$split_text[$i] = trim($split_text[$i]);
				$full_article_search_text .= " full_names LIKE '%" . $split_text[$i] . "%' or reg_no LIKE '%" . $split_text[$i] . "%' or";
			}
			//more than one search term i.e. spaces in between
			if ($num_items > 1){ 
				$full_article_search_text .= " full_names LIKE '%" . $search_text . "%' or reg_no LIKE '%" . $search_text . "%' or"; 
			} 
			//end more than one search term i.e. spaces in between
			$full_article_search_text = $this->removelastor($full_article_search_text);
		}			

		//main query
		$queryadd = "SELECT id, full_names, current_class, stream, reg_no, guardian_name FROM sch_students WHERE sch_id=$sch_id ";
		//set equivalent values to field submitted values
		
		//if search is done, add the query texts
		if ($search_text) { $queryadd .= " AND ($full_article_search_text) "; }

		if ($province_sort=='on') { $thecat="province"; }
		else if ($cat_sort=='on') { $thecat="category"; }
		else if ($school_name_sort=='on') { $thecat="sch_name"; }
		else { $thecat = "full_names"; }

		$queryadd .= " ORDER BY $thecat "; 
		
		//if ($orderstyle=='d') { $queryadd .= " DESC "; }

		$queryadd .= " LIMIT $offset,$lperpage"; //echo "queryadd - $queryadd";
		
		$query = $queryadd;
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($student_id, $full_names, $current_class, $stream, $reg_no, $guardian_name);
		/* fetch values */
		while ( $stmt->fetch() ) {
			$tmp = array();
			$tmp["student_id"] = $student_id;
			$tmp["student_full_names"] = $full_names;
			$tmp["name"] = $full_names;
			$tmp["reg_no"] = $reg_no;
			$tmp["current_class"] = $current_class;
			$tmp["stream"] = $stream;
			$tmp["guardian_name"] = $guardian_name;
			$tmp["student_image"] = $this->getPhoto(STUDENT_PROFILE_PHOTO, $student_id, THUMB_IMAGE); //STUDENT_PROFILE_PIC
			$tmp["student_large_image"] = $this->getPhoto(STUDENT_PROFILE_PHOTO, $student_id);

			array_push($students, $tmp);
			
		}
		
        $stmt->close();
		return $students; 

    }
	
	// fetch subjects listing for grid
    public function getSubjectGridListing($level_id, $page=NULL, $search_text=NULL, $lperpage=NULL, $sort=NULL) {

		$subjects = array();
		
		$sortqry = "";
		//start sort
		//start sort
		if ($sort['id'] == "asc"){
			$sortqry = " ss.id ";
		} else if ($sort['id'] == "desc") {
			$sortqry = " ss.id DESC ";
		} else if ($sort['name'] == "asc") {
			$sortqry = " ss.name ";
		} else if ($sort['name'] == "desc") {
			$sortqry = " ss.name DESC ";
		} else if ($sort['short_name'] == "asc") {
			$sortqry = " short_name ";
		} else if ($sort['short_name'] == "desc") {
			$sortqry = " short_name DESC ";
		} else if ($sort['level'] == "asc") {
			$sortqry = " school_level ";
		} else if ($sort['level'] == "desc") {
			$sortqry = " school_level DESC "; 
		} 	
		
		if (!$page){ $page=1; }
		if (!$lperpage) { $lperpage = 10; } //default num records
		$offset = ($page - 1) * $lperpage;

		if ($search_text) {
			$search_text = strtolower(trim($search_text));
			$search_text = $this->clean($search_text);
			$split_text = explode(" ",$search_text);
			$num_items = count($split_text);
			$full_article_search_text = "";
			for ($i=0;$i<$num_items;$i++) {
				$split_text[$i] = trim($split_text[$i]);
				$full_article_search_text .= " ss.name LIKE '%" . $split_text[$i] . "%' or short_name LIKE '%" . $split_text[$i] . "%' or";
			}
			//more than one search term i.e. spaces in between
			if ($num_items > 1){ 
				$full_article_search_text .= " ss.name LIKE '%" . $search_text . "%' or short_name LIKE '%" . $search_text . "%' or"; 
			} 
			//end more than one search term i.e. spaces in between
			$full_article_search_text = $this->removelastor($full_article_search_text);
		}			

		//main query - get total number of records
		$queryMain = "SELECT ss.id, ss.name, short_name, ss.code, sl.name, ss.active FROM sch_subjects ss ";
		$queryMain .= " LEFT JOIN  sch_levels sl ON ss.school_level = sl.id ";
		$queryMain .= " WHERE ss.name != '' ";
		if ($level_id) { $queryMain .= " AND ss.school_level = $level_id "; }
		if ($search_text) { $queryMain .= " AND ($full_article_search_text) "; }
		//echo $queryMain; exit;

		$stmtMain = $this->conn->prepare($queryMain);
		$stmtMain->execute();
		$stmtMain->store_result();
		$stmtMain->fetch();
		$total_recs = $stmtMain->num_rows;
		$stmtMain->close();
		
		//filtered recordset
		if ($sortqry) { $queryMain .= " ORDER BY $sortqry "; } else { $queryMain .= " ORDER BY ss.name "; }//add sort query 
		$queryMain .= " LIMIT $offset,$lperpage"; //echo "queryadd - $queryadd";
		//echo "queryMain - $queryMain"; exit;
		$query = $queryMain; 
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$numberofrows = $stmt->num_rows;
		
		$stmt->bind_result($id, $name, $short_name, $code, $level, $active);
		/* fetch values */
		while ( $stmt->fetch() ) {
			$tmp = array();
			$tmp["id"] = $id;
			$tmp["name"] = $name;
			$tmp["short_name"] = $short_name;
			$tmp["code"] = $code;
			$tmp["level"] = $level;
			$tmp["active"] = $active;
			array_push($subjects, $tmp);
		}
		$response['rows'] = $subjects;
		$response['total'] = $total_recs;
		$response['rowCount'] = $lperpage;
		$response['current'] = $page;
        $stmt->close();
		
		return $response; 

    }
	
	// fetch students listing for grid
    public function getStudentGridListing($sch_id, $page=NULL, $search_text=NULL, $lperpage=NULL, $sort=NULL) { 

		$students = array();
		
		$sortqry = "";
		//start sort
		if ($sort['id'] == "asc"){
			$sortqry = " id ";
		} else if ($sort['id'] == "desc") {
			$sortqry = " id DESC ";
		} else if ($sort['name'] == "asc") {
			$sortqry = " full_names ";
		} else if ($sort['name'] == "desc") {
			$sortqry = " full_names DESC ";
		} else if ($sort['reg_no'] == "asc") {
			$sortqry = " reg_no ";
		} else if ($sort['reg_no'] == "desc") {
			$sortqry = " reg_no DESC ";
		} else if ($sort['guardian_name'] == "asc") {
			$sortqry = " guardian_name ";
		} else if ($sort['guardian_name'] == "desc") {
			$sortqry = " guardian_name DESC "; 
		} else if ($sort['admin_date'] == "asc") {
			$sortqry = " admin_date ";
		} else if ($sort['admin_date'] == "desc") {
			$sortqry = " admin_date DESC "; 
		} 	
		
		if (!$page){ $page=1; }
		if (!$lperpage) { $lperpage = 10; } //default num records
		$offset = ($page - 1) * $lperpage;

		if ($search_text) {
			$search_text = strtolower(trim($search_text));
			$search_text = $this->clean($search_text);
			$split_text = explode(" ",$search_text);
			$num_items = count($split_text);
			$full_article_search_text = "";
			for ($i=0;$i<$num_items;$i++) {
				$split_text[$i] = trim($split_text[$i]);
				$full_article_search_text .= " full_names LIKE '%" . $split_text[$i] . "%' or reg_no LIKE '%" . $split_text[$i] . "%' or guardian_name LIKE '%" . $split_text[$i] . "%' or current_class LIKE '%" . $split_text[$i] . "%' or stream LIKE '%" . $split_text[$i] . "%' or";
			}
			//more than one search term i.e. spaces in between
			if ($num_items > 1){ 
				$full_article_search_text .= " full_names LIKE '%" . $search_text . "%' or reg_no LIKE '%" . $search_text . "%' or guardian_name LIKE '%" . $search_text . "%' or current_class LIKE '%" . $split_text[$i] . "%' or stream LIKE '%" . $split_text[$i] . "%' or"; 
			} 
			//end more than one search term i.e. spaces in between
			$full_article_search_text = $this->removelastor($full_article_search_text);
		}			
  
		//main query - get total number of records
		$queryMain = "SELECT id, full_names, reg_no, guardian_name, dob, admin_date, index_no, nationality, religion, previous_school, house";
		$queryMain .= ", club, guardian_id_card, guardian_relation, guardian_occupation, current_class, email, town, village, county, location";
		$queryMain .= ", disability, gender, stream, constituency, student_profile";
		$queryMain .= ", guardian_address, guardian_phone FROM sch_students WHERE sch_id = ? ";
		if ($search_text) { $queryMain .= " AND ($full_article_search_text) "; }
		//echo $queryMain . " $sch_id"; exit;

		$stmtMain = $this->conn->prepare($queryMain);
		$stmtMain->bind_param("i", $sch_id);
		$stmtMain->execute();
		$stmtMain->store_result();
		$stmtMain->fetch();
		$total_recs = $stmtMain->num_rows;
		$stmtMain->close();
		
		//filtered recordset
		if ($sortqry) { $queryMain .= " ORDER BY $sortqry "; } else { $queryMain .= " ORDER BY full_names "; }//add sort query 
		if ($lperpage > 0) { $queryMain .= " LIMIT $offset,$lperpage"; } //echo "queryadd - $queryadd";
		//echo "queryMain - $queryMain - $sch_id";exit;
		$query = $queryMain; 
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i", $sch_id);
		$stmt->execute();
		$numberofrows = $stmt->num_rows;
		
		$stmt->bind_result($id, $full_names, $reg_no, $guardian_name, $dob, $admin_date, $index_no, $nationality, $religion, $previous_school, $house, $club, $guardian_id_card, $guardian_relation, $guardian_occupation, $current_class, $email, $town, $village, $county, $location, $disability, $gender, $stream, $constituency, $student_profile, $guardian_address, $guardian_phone);
		/* fetch values */
		while ( $stmt->fetch() ) {
			$tmp = array();
			if ($dob!=""){ $dob = date("d/m/Y", $this->php_date($dob)); }
			if ($admin_date!=""){ $admin_date = date("d/m/Y", $this->php_date($admin_date)); }
			$tmp["id"] = $id;
			$tmp["full_names"] = $full_names;
			$tmp["name"] = $full_names;
			$tmp["reg_no"] = $reg_no;
			$tmp["guardian_name"] = $guardian_name;
			$tmp["dob"] = $dob;
			$tmp["admin_date"] = $admin_date;
			$tmp["index_no"] = $index_no;
			$tmp["nationality"] = $nationality;
			$tmp["religion"] = $religion;
			$tmp["previous_school"] = $previous_school;
			$tmp["house"] = $house;
			$tmp["club"] = $club;
			$tmp["guardian_id_card"] = $guardian_id_card;
			$tmp["guardian_relation"] = $guardian_relation;
			$tmp["guardian_occupation"] = $guardian_occupation;
			$tmp["email"] = $email;
			$tmp["town"] = $guardian_name;
			$tmp["current_class"] = $current_class;
			$tmp["current_class_desc"] = $current_class . " " . $stream;
			$tmp["town"] = $town;
			$tmp["village"] = $village;
			$tmp["county"] = $county;
			$tmp["location"] = $location;
			$tmp["disability"] = $disability;
			$tmp["gender"] = $gender;
			$tmp["stream"] = $stream;
			$tmp["constituency"] = $constituency;
			$tmp["student_profile"] = $student_profile;
			$tmp["guardian_address"] = $guardian_address;
			$tmp["guardian_phone"] = $guardian_phone;			
			
			array_push($students, $tmp);
		}
		$response['rows'] = $students;
		$response['total'] = $total_recs;
		$response['rowCount'] = $lperpage;
		$response['current'] = $page;
        $stmt->close();
		
		return $response; 

    }
	
	// fetch students listing for grid
    public function getStudentResultsGridListing($sch_id, $reg_no=NULL, $year=NULL, $term=NULL, $page=NULL, $search_text=NULL, $lperpage=NULL, $sort=NULL) { 

		$results = array();
		
		$sortqry = "";
		//start sort
		if ($sort['id'] == "asc"){
			$sortqry = " sri.id ";
		} else if ($sort['id'] == "desc") {
			$sortqry = " sri.id DESC ";
		} else if ($sort['name'] == "asc") {
			$sortqry = " ss.name ";
		} else if ($sort['name'] == "desc") {
			$sortqry = " ss.name DESC ";
		} else if ($sort['score'] == "asc") {
			$sortqry = " sri.score ";
		} else if ($sort['score'] == "desc") {
			$sortqry = " sri.score DESC ";
		}
		
		if (!$page){ $page=1; }
		if (!$lperpage) { $lperpage = 10; } //default num records
		$offset = ($page - 1) * $lperpage;

		if ($search_text) {
			$search_text = strtolower(trim($search_text));
			$search_text = $this->clean($search_text);
			$split_text = explode(" ",$search_text);
			$num_items = count($split_text);
			$full_article_search_text = "";
			for ($i=0;$i<$num_items;$i++) {
				$split_text[$i] = trim($split_text[$i]);
				$full_article_search_text .= " ss.name LIKE '%" . $split_text[$i] . "%' or sr.reg_no LIKE '%" . $split_text[$i] . "%' or sr.year LIKE '%" . $split_text[$i] . "%' or";
			}
			//more than one search term i.e. spaces in between
			if ($num_items > 1){ 
				$full_article_search_text .= " ss.name LIKE '%" . $search_text . "%' or sr.reg_no LIKE '%" . $search_text . "%' or sr.year LIKE '%" . $search_text . "%' or"; 
			} 
			//end more than one search term i.e. spaces in between
			$full_article_search_text = $this->removelastor($full_article_search_text);
		}			
  
		//main query - get total number of records
		$queryMain = "SELECT sri.id, sri.score, sri.grade, sri.points, ss.name, sr.mean_score, sr.grade, sr.points, sr.total_score FROM sch_results_items sri ";
		$queryMain .= " JOIN sch_subjects ss ON sri.subject_code = ss.code ";
		$queryMain .= " JOIN sch_results sr ON sr.id = sri.result_id ";
		$queryMain .= " WHERE sr.sch_id = ? AND sr.year = ? AND sr.term = ? ";
		if ($reg_no) { $queryMain .= " AND (sr.reg_no = '$reg_no') "; }
		if ($search_text) { $queryMain .= " AND ($full_article_search_text) "; }
		//echo $queryMain . " - $sch_id, $year, $term";

		$stmtMain = $this->conn->prepare($queryMain);
		$stmtMain->bind_param("iii", $sch_id, $year, $term);
		$stmtMain->execute();
		$stmtMain->store_result();
		$stmtMain->fetch();
		$total_recs = $stmtMain->num_rows;
		$stmtMain->close();
		
		//filtered recordset
		if ($sortqry) { $queryMain .= " ORDER BY $sortqry "; } else { $queryMain .= " ORDER BY ss.name "; }//add sort query 
		$queryMain .= " LIMIT $offset,$lperpage"; //echo "queryadd - $queryadd";
		//echo "queryMain - $queryMain";
		$query = $queryMain; 
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("iii", $sch_id, $year, $term);
		$stmt->execute();
		$numberofrows = $stmt->num_rows;
		
		$stmt->bind_result($id, $score, $grade, $points, $subject_name, $mean_score, $mean_grade, $mean_points, $total_score);
		/* fetch values */
		while ( $stmt->fetch() ) {
			
			$tmp = array();

			$tmp["id"] = $id;
			$tmp["score"] = $this->format_num($score, 0);
			$tmp["grade"] = $grade;
			$tmp["points"] = $points;
			$tmp["name"] = $subject_name;
					
			
			array_push($results, $tmp);
			
		}
		$response['rows'] = $results;
		$response['total'] = $total_recs;
		$response['rowCount'] = $lperpage;
		$response['current'] = $page;
		$response["mean_score"] = $this->format_num($mean_score, 1);
		$response["mean_grade"] = $mean_grade;
		$response["mean_points"] = $mean_points;
		$response["total_score"] = $this->format_num($total_score, 0);	
        $stmt->close();
		
		return $response; 

    }
	
	// fetch school id from student id
    public function fetchSchoolIdFromStudentId($student_id) { 
		
		$response = array();
		//main query - get total number of records
		$queryMain = "SELECT sch_id, reg_no FROM sch_students WHERE id = ? "; 
		$stmtMain = $this->conn->prepare($queryMain);
		$stmtMain->bind_param("i", $student_id);
		$result = $stmtMain->execute();
		$stmtMain->store_result();
		$stmtMain->bind_result($sch_id, $reg_no);
		$stmtMain->fetch();
		if ($result) {	
			$response['sch_id'] = $sch_id;
			$response['reg_no'] = $reg_no;
			$response['error'] = false;
		} else {
			$response['message'] = "No data available";
			$response['error'] = true;
		}
		$stmtMain->close();
		
		return $response; 

    }
	
	// fetch single result data
    public function getSingleResult($result_item_id) { 
		
		$response = array();
		//main query - get total number of records
		$queryMain = "SELECT sri.id, sri.score, ss.name FROM sch_results_items sri ";
		$queryMain .= " JOIN sch_subjects ss ON sri.subject_code = ss.code ";
		$queryMain .= " WHERE sri.id = ? "; 
		$stmtMain = $this->conn->prepare($queryMain);
		$stmtMain->bind_param("i", $result_item_id);
		$result = $stmtMain->execute();
		$stmtMain->store_result();
		$stmtMain->bind_result($id, $score, $subject_name);
		$stmtMain->fetch();
		if ($result) {	
			$response['id'] = $id;
			$response['subject_name'] = $subject_name;
			$response['score'] = $this->format_num($score, 0);
			$response['error'] = false;
		} else {
			$response['message'] = "No data available";
			$response['error'] = true;
		}
		$stmtMain->close();
		
		return $response; 

    }
	
	// fetch single result data
    public function getSingleFee($fee_payment_id) { 
		
		$response = array();
		//main query - get total number of records
		$queryMain = "SELECT sfp.id, sfp.amount, sfp.payment_mode, sfp.cheque_no, sfp.paid_by, sfp.paid_at, sf.year FROM sch_fees_payments sfp ";
		$queryMain .= " JOIN sch_fees sf ON sfp.fees_id=sf.id ";
		$queryMain .= " JOIN payment_modes pm ON pm.code=sfp.payment_mode ";
		$queryMain .= " WHERE sfp.id = ? "; 
		$stmtMain = $this->conn->prepare($queryMain);
		$stmtMain->bind_param("i", $fee_payment_id);
		$result = $stmtMain->execute();
		$stmtMain->store_result();
		$stmtMain->bind_result($id, $amount, $payment_mode, $cheque_no, $paid_by, $paid_at, $year );
		$stmtMain->fetch();
		if ($result) {	
			$response['id'] = $id;
			$response['amount'] = $amount;
			$response['payment_mode'] = $payment_mode;
			$response['cheque_no'] = $cheque_no;
			$response['paid_by'] = $paid_by;
			$response['paid_at'] = date("d/m/Y", $this->php_date($paid_at));
			$response['year'] = $year;
			$response['error'] = false;
		} else {
			$response['message'] = "No data available";
			$response['error'] = true;
		}
		$stmtMain->close();
		
		return $response; 

    }

	// edit single fee data
    public function editSingleFee($fee_payment_id, $amount, $payment_mode, $paid_by, $paid_at) { 
		
		$response = array();
		
		if (!$this->isDataNumeric($amount)){
			
			$response['message'] = "Amount must be a number";
			$response['error'] = true;	
			
		} else {
			
			$updated_at = $this->getCurrentDate(); // get current date
			$updated_by = USER_ID;
			//format the date
			//06/06/2016, 2016-08-27 05:30:33
			$dateparts = explode("/", $paid_at);
			$new_paid_at = $dateparts[2]."-".$dateparts[1]."-".$dateparts[0]." 00:00:00";
 
			//update results
			$queryMain = "UPDATE sch_fees_payments SET amount = ?, payment_mode = ?, paid_at = ?, paid_by = ?, updated_at = ?, updated_by = ? WHERE id = ? ";
			//echo "$queryMain - $amount, $payment_mode, $new_paid_at, $paid_by, $updated_at, $updated_by, $fee_payment_id ";
			$stmtMain = $this->conn->prepare($queryMain);
			$stmtMain->bind_param("issssii", $amount, $payment_mode, $new_paid_at, $paid_by, $updated_at, $updated_by, $fee_payment_id);
			$result = $stmtMain->execute();
			if ($result) {	
				
				//get result id
				$fees_id = $this->getFeeId($fee_payment_id);
				
				//save archive data
				$this->saveFeeItemArchiveData($fee_payment_id);
				
				//update result summaries
				$this->updateFeeSummaryData($fees_id);
			
				$response['message'] = "Data updated";
				$response['error'] = false;
				
			} else {
				
				$response['message'] = "An error occured while updating data";
				$response['error'] = true;
				
			}
			$stmtMain->close();
			
		}
		
		return $response; 

    }


// edit single result data
    public function editSingleResult($result_item_id, $score, $sch_id) { 
		
		$response = array();
		
		if (!$this->isDataNumeric($score)){
			
			$response['message'] = "Score must be a number";
			$response['error'] = true;	
			
		} else if ($score > 100){
			
			$response['message'] = "Score cannot be more than 100";
			$response['error'] = true;	
			
		} else {
			
			$updated_at = $this->getCurrentDate(); // get current date
			$updated_by = USER_ID;
			
			//calculate points for this score
			$sch_level = $this->getSchoolLevel($sch_id);
			$points = $this->getSubjectPoints($score, $sch_level);
			
			//update results
			$queryMain = "UPDATE sch_results_items SET score = ?, points = ?, updated_at = ?, updated_by = ? WHERE id = ? ";
			$stmtMain = $this->conn->prepare($queryMain);
			$stmtMain->bind_param("disii", $score, $points, $updated_at, $updated_by, $result_item_id);
			$result = $stmtMain->execute();
			if ($result) {	
				
				//get result id
				$result_id = $this->getResultId($result_item_id);
				
				//save archive data
				$this->saveResultItemArchiveData($result_item_id);
				
				//update result summaries
				$this->updateResultSummaryData($result_id, $sch_id);
			
				$response['message'] = "Data updated";
				$response['error'] = false;
				
			} else {
				
				$response['message'] = "An error occured while updating data";
				$response['error'] = true;
				
			}
			$stmtMain->close();
			
		}
		
		return $response; 

    }

	// fetch students listing for grid
    public function getUserGridListing($page=NULL, $search_text=NULL, $lperpage=NULL, $sort=NULL) {

		$users = array();
		
		$sortqry = "";
		//start sort
		if ($sort['id'] == "asc"){
			$sortqry = " c.id ";
		} else if ($sort['id'] == "desc") {
			$sortqry = " c.id DESC ";
		} else if ($sort['name'] == "asc") {
			$sortqry = " full_names ";
		} else if ($sort['name'] == "desc") {
			$sortqry = " full_names DESC ";
		} else if ($sort['phone'] == "asc") {
			$sortqry = " phone_number ";
		} else if ($sort['phone'] == "desc") {
			$sortqry = " phone_number DESC ";
		} else if ($sort['email'] == "asc") {
			$sortqry = " email ";
		} else if ($sort['email'] == "desc") {
			$sortqry = " email DESC ";
		} else if ($sort['user_type'] == "asc") {
			$sortqry = " ut.name ";
		} else if ($sort['user_type'] == "desc") {
			$sortqry = " ut.name DESC "; 
		} else if ($sort['status'] == "asc") {
			$sortqry = " status ";
		} else if ($sort['status'] == "desc") {
			$sortqry = " status DESC "; 
		} 	
		
		if (!$page){ $page=1; }
		if (!$lperpage) { $lperpage = 10; } //default num records
		$offset = ($page - 1) * $lperpage;

		if ($search_text) {
			$search_text = strtolower(trim($search_text));
			$search_text = $this->clean($search_text);
			$split_text = explode(" ",$search_text);
			$num_items = count($split_text);
			$full_article_search_text = "";
			for ($i=0;$i<$num_items;$i++) {
				$split_text[$i] = trim($split_text[$i]);
				$full_article_search_text .= " first_name LIKE '%" . $split_text[$i] . "%' or last_name LIKE '%" . $split_text[$i] . "%' or full_names LIKE '%" . $split_text[$i] . "%' or";
			}
			//more than one search term i.e. spaces in between
			if ($num_items > 1){ 
				$full_article_search_text .= " first_name LIKE '%" . $search_text . "%' or last_name LIKE '%" . $search_text . "%' or full_names LIKE '%" . $search_text . "%' or"; 
			} 
			//end more than one search term i.e. spaces in between
			$full_article_search_text = $this->removelastor($full_article_search_text);
		}			

		//main query - get total number of records
		$queryMain = "SELECT c.id, first_name, last_name, full_names, email, phone_number, g.name, status, c.created_at FROM clients c ";
		$queryMain .= " LEFT JOIN groups g ON c.user_group = g.id ";
		
		$queryMain .= " WHERE c.id!='' ";
		
		if (SCHOOL_ADMIN_USER) { 
			$queryMain .= " AND user_group IN (SELECT id FROM groups WHERE created_by = " . USER_ID . ") "; 
		}
		
		if ($search_text) { $queryMain .= " AND ($full_article_search_text) "; }
		//echo $queryMain; exit;

		$stmtMain = $this->conn->prepare($queryMain);
		//$stmtMain->bind_param("i", $sch_id);
		$stmtMain->execute();
		$stmtMain->store_result();
		$stmtMain->fetch();
		$total_recs = $stmtMain->num_rows;
		$stmtMain->close();
		
		//filtered recordset
		if ($sortqry) { $queryMain .= " ORDER BY $sortqry "; } else { $queryMain .= " ORDER BY full_names, first_name "; }//add sort query 
		$queryMain .= " LIMIT $offset,$lperpage"; //echo "queryadd - $queryadd";
		//echo "queryMain - $queryMain";
		$query = $queryMain; 
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i", $sch_id);
		$stmt->execute();
		$numberofrows = $stmt->num_rows;
		
		$stmt->bind_result($id, $first_name, $last_name, $full_names, $email, $phone_number, $user_type, $status, $created_at);
		
		while ( $stmt->fetch() ) {
			$tmp = array();

			$tfull_names = $full_names;
			$tfull_names = trim($tfull_names);
			if (!$tfull_names){ $tfull_names = $first_name." ".$last_name; }
		
			$tmp["id"] = $id;
			$tmp["name"] = $tfull_names;
			$tmp["email"] = $email;
			$tmp["phone"] = $phone_number;
			$tmp["status"] = $status;
			$tmp["user_type"] = $user_type;
			$tmp["created_at"] = $created_at;
			array_push($users, $tmp);
		}
		$response['rows'] = $users;
		$response['total'] = $total_recs;
		$response['rowCount'] = $lperpage;
		$response['current'] = $page;
        $stmt->close();
		
		return $response; 

    }
	    
	// fetch schools listing
    public function getSchoolListing($page, $search_text, $province=NULL, $full_list=NULL) {

		$response = array();
		
		if (!$page) { $page = 1; }
		
		$lperpage = 20; //default num records
		$offset = ($page - 1) * $lperpage;

		if ($search_text) {
			$search_text = strtolower(trim($search_text));
			$search_text = $this->clean($search_text);
			$split_text = explode(" ",$search_text);
			$num_items = count($split_text);
			$full_article_search_text = "";
			for ($i=0;$i<$num_items;$i++) {
				$split_text[$i] = trim($split_text[$i]);
				$full_article_search_text .= " sch_name LIKE '%" . $split_text[$i] . "%' or province LIKE '%" . $split_text[$i] . "%' or";
				if ($this->isNumber($split_text[$i])){ $full_article_search_text .= " sch_id = " . $split_text[$i] . " or"; }
			}
			//more than one search term i.e. spaces in between
			if ($num_items > 1){ 
				$full_article_search_text .= " sch_name LIKE '%" . $search_text . "%' or province LIKE '%" . $search_text . "%' or";
				if ($this->isNumber($split_text[$i])){ $full_article_search_text .= " sch_id = " . $split_text[$i] . " or"; } 
			} 
			//end more than one search term i.e. spaces in between
			$full_article_search_text = $this->removelastor($full_article_search_text);
		}			

		//main query
		$queryadd = "SELECT sch_name, address, province, sch_id, category, extra, sch_profile, events_calender, sms_welcome1";
		$queryadd .= ", sms_welcome2, phone1, phone2, motto, sch_paybill_no FROM sch_ussd WHERE sch_name!='' AND sch_id!='' ";
		//set equivalent values to field submitted values
		
		//if search is done, add the query texts
		if ($search_text) { $queryadd .= " AND ($full_article_search_text) "; }
		if ($province) { $queryadd .= " AND province='$province' "; }
		$queryadd .= " ORDER BY sch_name "; 
		if (!$full_list) { $queryadd .= " LIMIT $offset,$lperpage"; } //echo "queryadd - $queryadd";
		$query = $queryadd;
		
		if ($stmt = $this->conn->prepare($query)){
			
			//$stmt->bind_param("siii", $reg_no, $school_id, $term, $year);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($sch_name, $address, $province, $sch_id, $category, $extra, $sch_profile, $events_calendar, 
							   $sms_welcome1, $sms_welcome2, $phone1, $phone2, $motto, $sch_paybill_no);
			/* fetch values */
			while ( $stmt->fetch() ) {
				$tmp = array();
				$tmp["sch_id"] = $sch_id;
				$tmp["sch_name"] = trim($sch_name);
				$tmp["province"] = $province;
				$tmp["category"] = $category;
				$tmp["address"] = $address;
				$tmp["extra"] = $extra;
				$tmp["sch_profile"] = $sch_profile;
				$tmp["events_calendar"] = $events_calendar;
				$tmp["sms_welcome1"] = $sms_welcome1;
				$tmp["sms_welcome2"] = $sms_welcome2;
				$tmp["phone1"] = $phone1;
				$tmp["phone2"] = $phone2;
				$tmp["motto"] = $motto;
				$tmp["sch_paybill_no"] = $sch_paybill_no;
				$tmp["sch_image"] = $this->getPhoto(SCHOOL_PROFILE_PHOTO, $sch_id, THUMB_IMAGE);
				$tmp["sch_large_image"] = $this->getPhoto(SCHOOL_PROFILE_PHOTO, $sch_id);
	
				array_push($response, $tmp);
			}
			
			$stmt->close();
		
		} else {
			// Failed to create 
			//$response["query"] = $queryadd;
			$response["message"] = $this->conn->error;
			$response["error_type"] = AN_ERROR_OCCURED_ERROR;
			$response["error"] = true;	
		}
		
		return $response; 

    }
	
	function isNumber($num){
		return ctype_digit($num) && (int) $num > 0;
	}
	
	//fetch student fee payments
	public function getStudentFeePayments($page=1, $student_id, $phone_number, $year) {

		$response = array();
		
		if (!$page){ $page=1; }
		$lperpage = 20; //default num records
		$offset = ($page - 1) * $lperpage;		

		//main query
		$query = "SELECT sfp.id, sfp.fees_id, sfp.amount, pm.name, sfp.created_at, sfp.paid_by, sfp.paid_at, c.full_names";
		$query .= " FROM sch_fees_payments sfp ";
		$query .= " JOIN sch_fees sf ON sf.id = sfp.fees_id ";
		$query .= " JOIN payment_modes pm ON pm.code = sfp.payment_mode ";
		$query .= " JOIN clients c ON c.id = sfp.created_by ";
		$query .= " WHERE sf.student_id = ? ";
		if ($year) { $query .= " AND sf.year = $year "; }
		$query .= " ORDER BY sfp.created_at DESC "; 
		$query .= " LIMIT $offset,$lperpage"; 
		
		if ($stmt = $this->conn->prepare($query)){
			
			$stmt->bind_param("i", $student_id);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($payment_id, $payment_fees_id, $amount, $payment_mode, $created_at, $paid_by, $paid_at, $created_by);
			/* fetch values */
			while ( $stmt->fetch() ) {
				$tmp = array();
				$tmp["payment_id"] = $payment_id;
				$tmp["payment_fees_id"] = $payment_fees_id;
				$tmp["payment_amount"] = "Kshs. " . $this->format_num($amount);
				$tmp["payment_mode"] = $payment_mode;
				$tmp["payment_paid_by"] = $paid_by;
				$tmp["payment_paid_at"] = date("d/m/Y", $this->php_date($paid_at));
				$tmp["payment_created_at"] = $this->adjustDate(NULL, $this->php_date($created_at), NULL);
				$tmp["payment_created_by"] = $created_by;
	
				array_push($response, $tmp);
			}
			
			$stmt->close();
		
		} else {
			
			$response["query"] = $query;
			$response["error"] = true;
			$response["message"] = $this->conn->error;
			$response['error_type'] = AN_ERROR_OCCURED_ERROR;
			
		}
		
		return $response; 

    }
	
	// fetch school activities listing  
    public function getSchoolActivities($sch_id, $page=NULL, $search_text=NULL, $lperpage=NULL, $sort=NULL) {

		$activities = array();
		
		$sortqry = "";
		//start sort
		if ($sort['id'] == "asc"){
			$sortqry = " id ";
		} else if ($sort['id'] == "desc") {
			$sortqry = " id DESC ";
		} else if ($sort['name'] == "asc") {
			$sortqry = " name ";
		} else if ($sort['name'] == "desc") {
			$sortqry = " name DESC ";
		} else if ($sort['start_at'] == "asc") {
			$sortqry = " start_at ";
		} else if ($sort['start_at'] == "desc") {
			$sortqry = " start_at DESC ";
		} else if ($sort['end_at'] == "asc") {
			$sortqry = " end_at ";
		} else if ($sort['end_at'] == "desc") {
			$sortqry = " end_at DESC "; 
		} 
		//end sort
		
		if (!$page){ $page = 1; }
		if (!$lperpage) { $lperpage = 10; } //default num records
		$offset = ($page - 1) * $lperpage;

		if ($search_text) {
			$search_text = strtolower(trim($search_text));
			$search_text = $this->clean($search_text);
			$split_text = explode(" ",$search_text);
			$num_items = count($split_text);
			$full_article_search_text = "";
			for ($i=0;$i<$num_items;$i++) {
				$split_text[$i] = trim($split_text[$i]);
				$full_article_search_text .= " name LIKE '%" . $split_text[$i] . "%' or";
			}
			//more than one search term i.e. spaces in between
			if ($num_items > 1){ 
				$full_article_search_text .= " name LIKE '%" . $search_text . "%' or"; 
			} 
			//end more than one search term i.e. spaces in between
			$full_article_search_text = $this->removelastor($full_article_search_text);
		}	 

		//main query - get total number of records
		$queryMain = "SELECT id, name, start_at, end_at, description, venue FROM sch_activities WHERE sch_id = ? ";
		if ($search_text) { $queryMain .= " AND ($full_article_search_text) "; }
		//echo $queryMain; exit;
		
		if ($stmtMain = $this->conn->prepare($queryMain)) {
			
			$stmtMain->bind_param("i", $sch_id);
			$stmtMain->execute();
			$stmtMain->store_result();
			$stmtMain->fetch();
			$total_recs = $stmtMain->num_rows;
			$stmtMain->close();
			
			//filtered recordset
			if ($sortqry) { $queryMain .= " ORDER BY $sortqry "; } else { $queryMain .= " ORDER BY start_at DESC "; }//add sort query 
			$queryMain .= " LIMIT $offset,$lperpage"; //echo "queryadd - $queryadd";
			//echo "queryMain - $queryMain";
			$query = $queryMain; 
			$stmt = $this->conn->prepare($query);
			$stmt->bind_param("i", $sch_id);
			$stmt->execute();
			$numberofrows = $stmt->num_rows;
			
			$stmt->bind_result($id, $name, $start_date, $end_date, $description, $venue);
			/* fetch values */
			while ( $stmt->fetch() ) {
				$tmp = array();
				$tmp["id"] = $id;
				$tmp["name"] = $name;
				$tmp["start_at"] =  $this->adjustDate(NULL, $this->php_date($start_date), NULL);
				$tmp["end_at"] =  $this->adjustDate(NULL, $this->php_date($end_date), NULL);
				$tmp["venue"] = $venue;
				$tmp["description"] = $description;
				array_push($activities, $tmp);
			}
			$response['rows'] = $activities;
			$response['activities'] = $activities;
			$response['total'] = $total_recs;
			$response['rowCount'] = $lperpage;
			$response['current'] = $page;
			$stmt->close();
			
		} else {
			
			$response["queryMain"] = $queryMain;
			$response["error"] = true;
			$response["message"] = $this->conn->error;
			$response['error_type'] = AN_ERROR_OCCURED_ERROR;
			
		}
		
		return $response; 

    }
	
	//get schools list for grid
    function getSchoolGridListing($page=NULL, $user_id=NULL, $search_text=NULL, $lperpage=NULL, $sort=NULL) {

		$schools = array();
		
		$sortqry = "";
		//start sort
		if ($sort['sch_id'] == "asc"){
			$sortqry = " sch_id ";
		} else if ($sort['sch_id'] == "desc") {
			$sortqry = " sch_id DESC ";
		} else if ($sort['sch_name'] == "asc") {
			$sortqry = " sch_name ";
		} else if ($sort['sch_name'] == "desc") {
			$sortqry = " sch_name DESC ";
		} else if ($sort['province'] == "asc") {
			$sortqry = " province ";
		} else if ($sort['province'] == "desc") {
			$sortqry = " province DESC ";
		} else if ($sort['category'] == "asc") {
			$sortqry = " sc.name ";
		} else if ($sort['category'] == "desc") {
			$sortqry = " sc.name DESC ";
		} 
		//end sort
		
		if (!$page){ $page = 1; }
		if (!$lperpage) { $lperpage = 10; } //default num records
		$offset = ($page - 1) * $lperpage;

		if ($search_text) {
            $search_text = trim($search_text);
            $split_text = explode(" ",$search_text);
            $num_items = count($split_text);
            $full_search_text = "";
            for ($i=0;$i<$num_items;$i++) {
                $split_text[$i] = trim($split_text[$i]);
                $full_search_text .= " sch_name LIKE '%" . $split_text[$i] . "%' or motto LIKE '%" . $split_text[$i] . "%' or";
            }
            $full_search_text = $this->removelastor($full_search_text);
		}
		
		//fetch records based on user_id
			
		//Get the records
		$queryMain = "SELECT s.sch_id, s.sch_name, s.status, s.province, c.name, sc.name FROM sch_ussd s ";
		$queryMain .= " LEFT JOIN counties c ON s.sch_county=c.id ";
		$queryMain .= " LEFT JOIN sch_levels sl ON s.sch_level=sl.id ";
		$queryMain .= " LEFT JOIN sch_categories sc ON s.sch_category=sc.id ";
		$queryMain .= " WHERE s.sch_name!='' ";
		if ($search_text) { $queryMain .= " AND ($full_search_text) "; }
		if ($category) { $queryMain .= " AND s.sch_category = $category "; }
		if ($status) { $queryMain .= " AND s.status = $status "; }
		//echo $queryMain; exit;
		
		$stmtMain = $this->conn->prepare($queryMain);
		//$stmtMain->bind_param("i", $sch_id);
		$stmtMain->execute();
		$stmtMain->store_result();
		$stmtMain->fetch();
		$total_recs = $stmtMain->num_rows;
		$stmtMain->close();
		
		//filtered recordset
		if ($sortqry) { $queryMain .= " ORDER BY $sortqry "; } //add sort query 
		$queryMain .= " LIMIT $offset,$lperpage"; //echo "queryadd - $queryadd";
		$query = $queryMain; 
		$stmt = $this->conn->prepare($query);
		//$stmt->bind_param("i", $sch_id);
		$stmt->execute();
		$numberofrows = $stmt->num_rows;
		
		$stmt->bind_result($sch_id, $sch_name, $status, $province, $county, $category);
		/* fetch values */
		while ( $stmt->fetch() ) {
			$tmp = array();
			$tmp["sch_id"] = $sch_id;
			$tmp["id"] = $sch_id;
			$tmp["sch_name"] = $sch_name;
			$tmp["name"] = $sch_name;
			$tmp["status"] = $status;
			$tmp["province"] = $province;
			$tmp["county"] = $county;
			$tmp["category"] = $category;
			array_push($schools, $tmp);
		}
		$response['rows'] = $schools;
		$response['total'] = $total_recs;
		$response['rowCount'] = $lperpage;
		$response['current'] = $page;
        $stmt->close();
		
		return $response; 

    }
		
	// fetch schools listing flagging those that user has subscribed to
    public function getSchoolSubListing($page, $search_text="", $school_name_sort="off", $province_sort="off", $cat_sort="off", $phone_number) {

		$schools = array();
		
		$lperpage = 20; //default num records
		$offset = ($page - 1) * $lperpage;

		if ($search_text) {
			$search_text = strtolower(trim($search_text));
			$search_text = $this->clean($search_text);
			$split_text = explode(" ",$search_text);
			$num_items = count($split_text);
			$full_article_search_text = "";
			for ($i=0;$i<$num_items;$i++) {
				$split_text[$i] = trim($split_text[$i]);
				$full_article_search_text .= " sch_name LIKE '%" . $split_text[$i] . "%' or province LIKE '%" . $split_text[$i] . "%' or category LIKE '%" . $split_text[$i] . "%' or";
			}
			//more than one search term i.e. spaces in between
			if ($num_items > 1){ 
				$full_article_search_text .= " sch_name LIKE '%" . $search_text . "%' or province LIKE '%" . $search_text . "%' or category LIKE '%" . $search_text . "%' or"; 
			} 
			//end more than one search term i.e. spaces in between
			$full_article_search_text = $this->removelastor($full_article_search_text);
		}			

		//main query
		$queryadd = "SELECT sch_name, address, province, sch_id, category, extra, sch_profile, events_calender, sms_welcome1";
		$queryadd .= ", sms_welcome2, phone1, phone2, motto FROM sch_ussd WHERE sch_name!='' AND sch_id!='' ";
		//set equivalent values to field submitted values
		
		//if filter/ search text is entered is done, add the query texts
		if ($search_text) { $queryadd .= " AND ($full_article_search_text) "; }

		if ($province_sort=='on') { $thecat="province"; } //if province filter is on
		else if ($cat_sort=='on') { $thecat="category"; } //if category filter is on
		else if ($school_name_sort=='on') { $thecat="sch_name"; } //if school name filter is on
		else { $thecat = "sch_name"; }

		$queryadd .= " ORDER BY $thecat "; 
		
		$queryadd .= " LIMIT $offset,$lperpage"; //limit results based on current page we are on
		
		$query = $queryadd;
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($sch_name, $address, $province, $sch_id, $category, $extra, $sch_profile, $events_calendar, $sms_welcome1, $sms_welcome2, $phone1, $phone2, $motto);
		/* fetch values */
		while ( $stmt->fetch() ) {
			$tmp = array();
			$tmp["sch_id"] = $sch_id;
			$tmp["sch_name"] = trim($sch_name);
			$tmp["province"] = $province;
			$tmp["category"] = $category;
			$tmp["address"] = $address;
			$tmp["extra"] = $extra;
			$tmp["sch_profile"] = $sch_profile;
			$tmp["events_calendar"] = $events_calendar;
			$tmp["sms_welcome1"] = $sms_welcome1;
			$tmp["sms_welcome2"] = $sms_welcome2;
			$tmp["phone1"] = $phone1;
			$tmp["phone2"] = $phone2;
			$tmp["motto"] = $motto;
			$tmp["sub"] = $this->getUserSubscription($sch_id, $phone_number); //return whether user is subscribed to this school or not

			array_push($schools, $tmp);
		}
		
        $stmt->close();
		return $schools; 

    }
	
	// fetching student fees data
    public function getStudentFees($school_id, $student_id, $year) {
		
		$response = array();		 
		
		$query = "SELECT total_fees, fees_bal, fees_paid, sf.updated_at  FROM sch_fees sf JOIN sch_students st";
		$query .= " ON sf.student_id = st.id WHERE sf.student_id = ? ";
		if ($year) { $query .= " AND year = $year "; }
		
		if ($stmt = $this->conn->prepare($query)) {
			
			$stmt->bind_param("i", $student_id);
			$result = $stmt->execute();
			$stmt->bind_result($total_fees,$fees_bal,$fees_paid, $updated_at);
			$stmt->fetch();
			if ($result) {	
				$tmp = array();
				$tmp['total_fees'] = "Kshs. " . $this->format_num($total_fees);
				$tmp['fees_bal'] = "Kshs. " . $this->format_num($fees_bal);
				$tmp['fees_paid'] = "Kshs. " . $this->format_num($fees_paid);
				$tmp['updated_at'] = $this->adjustDate("d-M-Y", $this->php_date($updated_at), NULL);
				$error = false;
				$stmt->close();
			} else {	
				$error = true;
				$tmp["message"] = "No Records Found";
				$tmp['total_fees'] = "Kshs. 0.00";
				$tmp['fees_bal'] = "Kshs. 0.00";
				$tmp['fees_paid'] = "Kshs. 0.00";
				$tmp['updated_at'] = "None";
			}

			$response['fees_summary'] = $tmp;
			$response['error'] = $error;
			
		} else {
			
			$response["query"] = $query;
			$response["error"] = true;
			$response["message"] = $this->conn->error;
			$response['error_type'] = AN_ERROR_OCCURED_ERROR;	
		
		}
	
		return $response; 

    }
	
	// fetching user account
    public function getFeeBalance($student_id, $year) {
		
		$response = array();		 
		
		$query = "SELECT fees_bal FROM sch_fees WHERE student_id = ? AND year = ? "; //echo "$query - $student_id, $year";
		
		if ($stmt = $this->conn->prepare($query)){
			
			$stmt->bind_param("ii", $student_id, $year);
			$result = $stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($fees_bal);
			$stmt->fetch();
			if ($result) {	
				$response['bal'] = "Kshs. " . $this->format_num($fees_bal);
				$response["error"] = false;
				$response["message"] = "";
			} else {	
				$response["error"] = true;
				$response["message"] = "No Records Found";
			}
			$stmt->close();
		
		} else {
			
			//$response["query"] = $query;
			$response["error"] = true;
			$response["message"] = $this->conn->error;
			$response['error_type'] = AN_ERROR_OCCURED_ERROR;	
		
		}
		
		return $response; 

    }	
	
		// fetching user account
    public function getPaybillNumber($sch_id, $sch_name) {
		
		$response = array();		 
		
		$query = "SELECT default_source FROM bulk_sms_users WHERE username = ? "; //echo "$query - $student_id, $year";
		
		if ($stmt = $this->conn->prepare($query)){
			
			$stmt->bind_param("i", $sch_id);
			$result = $stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($default_source);
			$stmt->fetch();
			if ($result) {	
				$response['sch_paybill_no'] = $default_source;
				$response["error"] = false;
				$response["message"] = "";
			} else {	
				$response["error"] = true;
				$response["message"] = "No Paybill Number set for $sch_name. \nPlease contact the school.";
			}
			$stmt->close();
		
		} else {
			
			//$response["query"] = $query;
			$response["error"] = true;
			$response["message"] = $this->conn->error;
			$response['error_type'] = AN_ERROR_OCCURED_ERROR;	
		
		}
		
		return $response; 

    }	
	
	// request new mpesa transaction
	public function requestMpesaFeePayment($sch_id, $sch_name, $reg_no, $student_names, $phone_number, $amount) {
		
		$phone_number = $this->formatPhoneNumber($phone_number);
		//get school paybill no
		$bulk_sms_details = $this->getBulkSMSData($sch_id);
		$paybill_no = $bulk_sms_details["default_source"];
		
		if ($paybill_no) {
			$account_number = $reg_no . " - " . $student_names; //echo "paybill_no - $paybill_no == account_number  - $account_number  - $amount  - $phone_number"; exit;
			$request_mpesa_link = "http://41.215.126.10:5333/pendoschool_app/?tag=checkout&mobile=" . $phone_number . "&amount=" . $amount . "&pid=" . $account_number . "&mid=" . $paybill_no;
			//echo "request_mpesa_link - $request_mpesa_link ";
			$response = $this->executeLink($request_mpesa_link);
			print_r($response);exit;
			
			// Check for success
			if ($response["error"]==false) {
				// Mpesa Request successfully sent
				$sms_id = $this->conn->insert_id;
				$response["error"] = false;
				$response["message"] = "Mpesa Request sent successfully.\n\nTo check your payment status, go to this link: \n\n Find Student Fees -> Select Year -> Fees Payment History";
				
			} else {
				// Failed to send Mpesa Request
				$response["error"] = true;
				$response["message"] = "An error occurred in Sending Mpesa Request";
			}
		} else {
			// Failed to send Mpesa Request
			$response["error"] = true;
			$response["message"] = "No paybill number found for <strong>$sch_name</strong>";
		}
	}
	
	// save new mpesa transaction
    public function saveMpesaFeePayment($creator_id, $student_id, $amount, $phone_number, $paid_by, $year="") {

        $response = array();
		$current_date = $this->getCurrentDate();
		
		//get user full names
		$paid_by = $this->getFullNames($creator_id);
		
		//get student's fees_id entry
		$fees_id = $this->getStudentFeeEntry($student_id, $year);
		
		//is fees_id available
		if (!$fees_id) {
			$fees_id = $this->insertStudentFeeEntry($student_id, $creator_id, $year);
		}
		
		//update fees_paid record
		$this->addFeesPaid($amount, $fees_id);
		//end update fees_paid record	
		
		// insert query
		$query  = "INSERT INTO sch_fees_payments(fees_id, amount, paid_by, paid_at, created_at, created_by) VALUES (?, ?, ?, ?, ?, ?) ";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("iisssi", $fees_id, $amount, $paid_by, $current_date, $current_date, $creator_id);
		$result = $stmt->execute();
		$stmt->store_result();
		$stmt->close();
		
		// Check for successful insertion
		if ($result) {
			//update fee summaries
			$this->updateFeeSummaryData($fees_id);
			//end update fee summaries
			$new_id = $this->conn->insert_id;
			$response["error"] = false;
			$response["message"] = "Waiting for MPESA response";
			$response["new_id"] = $new_id;
		} else {
			// Failed to create 
			$response["error"] = true;
			$response["error_type"] = ERROR_OCCURED;
			$response["message"] = "An error occurred whle saving payment";
		}
		
        $stmt->close();
		
        return $response;
		
    }
		
	// fetching all user subscriptions
    public function deleteSubscription($sub_id, $phone_number) {

        $school = array();
				
		//get subs
		$query  = "DELETE FROM sch_ussd_subs WHERE id = ? ";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i", $sub_id);
	
		//check whether user is already subscribed to a school
		if ($stmt->execute()) {
			$school["error"] = false;
			if (!$this->isUserSubExists($phone_number)) {
				//subscribe user to this school
				$school["user_subscribed"] = false;
			} else {
				$school["user_subscribed"] = true;	
			}
			
		} else {
			$school["error"] = true;
			$school["message"] = "An error occured";
		}
		
        $stmt->close();
        return $school;
    }

	// fetching all user subscriptions
    public function getSubscriptions($phone_number, $page=1) {

        $response = array();
		$subs = array();
		
		$isLastPage = true;
		
		$phone_number = $this->formatPhoneNumber($phone_number);
		
		if (!$page) { $page = 1; }//default page
		$lperpage = 15; //default num records
		$offset = ($page - 1) * $lperpage; //page offset
		
		//get subs
		$queryMain  = "SELECT ss.id, ss.sch_id, st.id, ss.reg_no, st.full_names, st.current_class, st.stream, su.sch_name, ss.mobile, prov, sub_date, sch_paybill_no FROM sch_ussd_subs ss";
		$queryMain .= " JOIN sch_students st ON ss.reg_no=st.reg_no ";
		$queryMain .= " JOIN sch_ussd su ON ss.sch_id=su.sch_id ";
		$queryMain .= " WHERE ss.mobile = ? ";
		$queryMain .= " ORDER BY sub_date DESC "; //echo $queryMain;
		
		//get total records
		$stmtMain = $this->conn->prepare($queryMain);
		$stmtMain->bind_param("s", $phone_number);
		$stmtMain->execute();
		$stmtMain->store_result();
		//$stmtMain->fetch();
		$totalRecs = $stmtMain->num_rows;
		$stmtMain->close();
		
		//if totalrecs is more than lperpage, calculate total number of pages, otherwise pages=1
		$totalRecs > $lperpage ? $pages = ceil($totalRecs / $lperpage) : $pages = 1;
		
		//set isLaastPage if more records exist
		if ($pages > $page) { $isLastPage = false; }
		
		//if last page, set row count equal to totalrecs, otherwise set it to quotient of totalrecs % limit per page
		$page == 1 ? $rowCount = $totalRecs : $rowCount = $totalRecs % $lperpage;		
		
		//filtered recordset
		$queryMain .= " LIMIT ?, ? ";
		//echo "queryMain - $queryMain";
		$query = $queryMain; 
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("sii", $phone_number, $offset, $lperpage);
		$stmt->execute();
		$stmt->store_result();
		$totalRows = $stmt->num_rows;
		
		/* bind result variables */
		$stmt->bind_result($id, $sch_id, $student_id, $regno, $full_names, $current_class, $stream, $sch_name, $mobile, $prov, $sub_date, $paybill_no);
		
		/* fetch values */
		while ( $stmt->fetch() ) {
			$tmp = array();
			$tmp["sub_id"] = $id;
			$tmp["sub_sch_id"] = $sch_id;
			$tmp["sub_reg_no"] = $regno;
			$tmp["sub_student_name"] = $full_names;
			$tmp["sub_student_id"] = $student_id;
			$tmp["sub_current_class"] = $current_class;
			$tmp["sub_stream"] = $stream;
			$tmp["sub_sch_name"] = $sch_name;
			$tmp["sub_phone_number"] = $mobile;
			$tmp["sub_prov"] = $prov;
			$tmp["sub_paybill_no"] = $paybill_no;
			$tmp["sub_created_at"] = $this->adjustDate("d-M-Y", $this->php_date($sub_date), NULL);
			$tmp["sub_user_image"] = $this->getPhoto(STUDENT_PROFILE_PHOTO, $student_id, THUMB_IMAGE);
			$tmp["sub_user_large_image"] = $this->getPhoto(STUDENT_PROFILE_PHOTO, $student_id);

			array_push($subs, $tmp);
		}
        $stmt->close();		
		
		$response["page"] = $page;
		$response["pages"] = $pages;
		$response["isLastPage"] = $isLastPage;
		$response["rowCount"] = $rowCount;
		$response["total"] = $totalRecs;
		$response["rowsTotal"] = $totalRows;
		$response["rows"] = $subs;
		
        return $response;
    }
	
	// fetching all user chats
    public function getAllUserChats($user_id, $page, $student_id="") {

        $chats = array();
		
		$top_user_id = $user_id;
		
		if (!$page) { $page = 1; }//default page
		$lperpage = 15; //default num records
		$offset = ($page - 1) * $lperpage; //page offset
		
		//get chats
		$query  = "SELECT cl.id as user_id, c.id as conversation_id, phone_number, student_id, c.created_at as created_at, c.updated_at as updated_at, full_names";
		$query .= " FROM conversations c, clients cl";
		$query .= " WHERE CASE ";
		$query .= " WHEN c.user_one = ? ";
		$query .= " THEN c.user_two = cl.id ";
		$query .= " WHEN c.user_two = ? ";
		$query .= " THEN c.user_one= cl.id ";
		$query .= " END  ";
		$query .= " AND ( ";
		$query .= " c.user_one = ? ";
		$query .= " OR c.user_two = ? ) ";
		if ($student_id) { $query .= " AND student_id = $student_id "; }
		$query .= " ORDER BY c.id DESC ";
		$query .= " LIMIT ?, ?";
		//$query .= " ORDER BY c.id DESC LIMIT 20";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("iiiiii", $user_id, $user_id, $user_id, $user_id, $offset, $lperpage);
		$stmt->execute();
		$stmt->store_result();
		/* bind result variables */
		$stmt->bind_result($user_id, $conversation_id, $phone_number, $student_id, $created_at, $updated_at, $full_names);
		
		/* fetch values */
		while ( $stmt->fetch() ) {
			$tmp = array();
			$message = "";
			$message_id = "";
			$recent_message_created_at = "";
			if (!$full_names) { $full_names = $phone_number; }
			$student_name = $this->getStudentName($student_id);
			$tmp["user_id"] = $user_id;
			$tmp["chat_id"] = $conversation_id;
			$tmp["student_id"] = $student_id;
			$tmp["full_names"] = $full_names;
			$tmp["student_full_names"] = $student_name;
			$tmp["phone_number"] = $phone_number;
			$tmp["created_at"] = $this->smartdate($created_at);
			$tmp["updated_at"] = $this->smartdate($updated_at); 
			$tmp["user_image"] = $this->getPhoto(STUDENT_PROFILE_PHOTO, $student_id, THUMB_IMAGE);
			$tmp["user_large_image"] = $this->getPhoto(STUDENT_PROFILE_PHOTO, $student_id);
			
			//get most recent message for this conversation
			$recentMessageQuery = "SELECT id, message, created_at FROM messages ";
			$recentMessageQuery .= " WHERE conversation_id = ? ORDER BY id DESC LIMIT 1 ";
			$stmtRecent = $this->conn->prepare($recentMessageQuery);
			$stmtRecent->bind_param("i", $conversation_id);
			$stmtRecent->execute();
			$stmtRecent->store_result();
			$stmtRecent->bind_result($message_id, $message, $recent_message_created_at);
			$stmtRecent->fetch();
			
			//if  message exists, use its timestamp, else use date created timestamp
			if ($message_id){
				$latest_time = $recent_message_created_at;
			} else {
				$latest_time = $created_at;
			}
			
			$tmp["recent_message_id"] = $message_id;
			$tmp["recent_message"] = $message;
			$tmp["recent_message_created_at"] = $this->smartdate($latest_time); //$this->adjustDate("d-M-Y", $this->php_date($latest_time), NULL);
			$tmp["unread_count"] = $this->getUnreadMessagesCount($conversation_id, $top_user_id);

			array_push($chats, $tmp);
			$stmtRecent->close();
		}
		
        $stmt->close();
        return $chats;
    }
	
	// fetching all user chats with no paging
    public function getAllUserFullChats($user_id) {

        $chats = array();
		
		$top_user_id = $user_id;
		
		//get chats
		$query  = "SELECT cl.id as user_id, c.id as conversation_id, phone_number, student_id, c.created_at as created_at, c.updated_at as updated_at, full_names";
		$query .= " FROM conversations c, clients cl";
		$query .= " WHERE CASE ";
		$query .= " WHEN c.user_one = ? ";
		$query .= " THEN c.user_two = cl.id ";
		$query .= " WHEN c.user_two = ? ";
		$query .= " THEN c.user_one= cl.id ";
		$query .= " END  ";
		$query .= " AND ( ";
		$query .= " c.user_one = ? ";
		$query .= " OR c.user_two = ? ) ";
		$query .= " ORDER BY c.id DESC ";
		//$query .= " ORDER BY c.id DESC LIMIT 20";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("iiii", $user_id, $user_id, $user_id, $user_id);
		$stmt->execute();
		$stmt->store_result();
		/* bind result variables */
		$stmt->bind_result($user_id, $conversation_id, $phone_number, $student_id, $created_at, $updated_at, $full_names);
		
		/* fetch values */
		while ( $stmt->fetch() ) {
			$tmp = array();
			$message = "";
			$message_id = "";
			$recent_message_created_at = "";
			if (!$full_names) { $full_names = $phone_number; }
			$student_name = $this->getStudentName($student_id);
			$tmp["user_id"] = $user_id;
			$tmp["chat_id"] = $conversation_id;
			$tmp["student_id"] = $student_id;
			$tmp["full_names"] = $full_names;
			$tmp["student_full_names"] = $student_name;
			$tmp["phone_number"] = $phone_number;
			$tmp["created_at"] = $this->smartdate($created_at);
			$tmp["updated_at"] = $this->smartdate($updated_at); 
			$tmp["user_image"] = $this->getPhoto(STUDENT_PROFILE_PHOTO, $student_id, THUMB_IMAGE);
			$tmp["user_large_image"] = $this->getPhoto(STUDENT_PROFILE_PHOTO, $student_id);
			
			//get most recent message for this conversation
			$recentMessageQuery = "SELECT id, message, created_at FROM messages ";
			$recentMessageQuery .= " WHERE conversation_id = ? ORDER BY id DESC LIMIT 1 ";
			$stmtRecent = $this->conn->prepare($recentMessageQuery);
			$stmtRecent->bind_param("i", $conversation_id);
			$stmtRecent->execute();
			$stmtRecent->store_result();
			$stmtRecent->bind_result($message_id, $message, $recent_message_created_at);
			$stmtRecent->fetch();
			
			//if  message exists, use its timestamp, else use date created timestamp
			if ($message_id){
				$latest_time = $recent_message_created_at;
			} else {
				$latest_time = $created_at;
			}
			
			$tmp["recent_message_id"] = $message_id;
			$tmp["recent_message"] = $message;
			$tmp["recent_message_created_at"] = $this->smartdate($latest_time); //$this->adjustDate("d-M-Y", $this->php_date($latest_time), NULL);
			$tmp["unread_count"] = $this->getUnreadMessagesCount($conversation_id, $top_user_id);

			array_push($chats, $tmp);
			$stmtRecent->close();
		}
		
        $stmt->close();
        return $chats;
    }
	
	// fetching all terms data
    public function getTermData() {

        $terms = array();
		//get terms
		$query  = "SELECT id, name FROM sch_term WHERE active = ? ";
		$active = 1;
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i", $active);
		$stmt->execute();
		/* bind result variables */
		$stmt->bind_result($id, $name);
		
		/* fetch values */
		while ( $stmt->fetch() ) {
			$tmp = array();
			$tmp["id"] = $id;
			$tmp["name"] = $name;
			array_push($terms, $tmp);
		}
		
        $stmt->close();
        return $terms;
    }
	
	// fetching all years data
    public function getYearData() {
		
		$years = array();
        
		$from = date('Y');
		$to = $from - 100;
		//$to = 1900;
		
		for($i=$to;$i<=$from;$i++)
	    {
			$tmp = array();
			//$years[$i] = $i;
			$tmp["id"] = $i;
			$tmp["name"] = $i;
			array_push($years, $tmp);
		}

	    $years = array_reverse($years);
		
		return $years;
		
    }
	
	// fetching all students current user has subscribed to
    public function getSubStudentsData($phone_number) {
		
		$students = array();
		
		$phone_number = $this->formatPhoneNumber($phone_number);

		//main query
		$query = "SELECT sb.reg_no, sch_paybill_no, admin_date, index_no, nationality, religion, previous_school, house, club "; 
		$query .= ", guardian_id_card, guardian_relation, guardian_occupation, email, county, town, village, location, disability, gender ";
		$query .= ", sc.sch_id, sc.sch_name, sc.province, sc.category, sc.address, sc.extra, sc.sch_profile";
		$query .= ", sc.events_calender, sc.sms_welcome1, sc.sms_welcome2, sc.phone1, sc.phone2, sc.motto ";
		$query .= ", st.id, st.full_names, st.reg_no, student_profile, current_class, stream, guardian_name, guardian_address ";
		$query .= ", guardian_phone, st.updated_at, st.created_at FROM sch_ussd_subs sb";
		$query .= " JOIN sch_ussd sc ON sb.sch_id=sc.sch_id ";
		$query .= " JOIN sch_students st ON st.reg_no=sb.reg_no ";
		$query .= " WHERE sb.mobile = ? ";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("s", $phone_number);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($reg_no, $paybill_no, $admin_date, $index_no, $nationality, $religion, $previous_school, $house, $club, $guardian_id_card, $guardian_relation, $guardian_occupation, 
		$email, $county, $town, $village, $location, $disability, $gender, $sch_id, $sch_name, $province, $category, $address, $extra, $sch_profile, $events_calendar, $sms_welcome1, 
		$sms_welcome2, $phone1, $phone2, $motto, $student_id, $full_names, $reg_no, $student_profile, $current_class, $stream, $guardian_name, $guardian_address, $guardian_phone, 
		$updated_at, $created_at);
		/* fetch values */
		while ( $stmt->fetch() ) {
			$tmp = array();
			$tmp["reg_no"] = $reg_no;
			$tmp["sch_paybill_no"] = $paybill_no;
			$tmp["sch_id"] = $sch_id;
			$tmp["sch_name"] = trim($sch_name);
			$tmp["province"] = $province;
			$tmp["category"] = $category;
			$tmp["address"] = $address;
			$tmp["extra"] = $extra;
			$tmp["sch_profile"] = $sch_profile;
			$tmp["events_calendar"] = $events_calendar;
			$tmp["sms_welcome1"] = $sms_welcome1;
			$tmp["sms_welcome2"] = $sms_welcome2;
			$tmp["phone1"] = $phone1;
			$tmp["phone2"] = $phone2;
			$tmp["motto"] = $motto;
			$tmp["sch_image"] = $this->getPhoto(SCHOOL_PROFILE_PHOTO, $sch_id, THUMB_IMAGE);
			$tmp["sch_large_image"] = $this->getPhoto(SCHOOL_PROFILE_PHOTO, $sch_id);
			
			$tmp["student_id"] = $student_id;
			$tmp["student_full_names"] = $full_names;
			$tmp["reg_no"] = $reg_no;
			$tmp["current_class"] = $current_class;
			$tmp["stream"] = $stream;
			$tmp["student_profile"] = $student_profile;
			$tmp["admin_date"] = $admin_date;
			$tmp["index_no"] = $index_no;
			$tmp["nationality"] = $nationality;
			$tmp["religion"] = $religion;
			$tmp["previous_school"] = $previous_school;
			$tmp["house"] = $house;
			$tmp["club"] = $club;
			$tmp["guardian_id_card"] = $guardian_id_card;
			$tmp["guardian_relation"] = $guardian_relation;
			$tmp["guardian_occupation"] = $guardian_occupation;
			$tmp["email"] = $email;
			$tmp["county"] = $county;
			$tmp["town"] = $town;
			$tmp["village"] = $village;
			$tmp["location"] = $location;
			$tmp["disability"] = $disability;
			$tmp["student_gender"] = $gender;
			$tmp["guardian_name"] = $guardian_name;
			$tmp["guardian_address"] = $guardian_address;
			$tmp["guardian_phone"] = $guardian_phone;
			$tmp["student_updated_at"] = $this->smartdate($updated_at);
			$tmp["student_created_at"] = $this->smartdate($created_at); //echo "student_id - ". THUMB_IMAGE; exit;
			$tmp["student_image"] = $this->getPhoto(STUDENT_PROFILE_PHOTO, $student_id, THUMB_IMAGE);
			$tmp["student_large_image"] = $this->getPhoto(STUDENT_PROFILE_PHOTO, $student_id);
		
			//$tmp = $reg_no;
			array_push($students, $tmp);
		}
		
        $stmt->close();
		return $students;
		
    }
	
	// fetching all students in this school
    public function getStudentsInSchool($sch_id) {

        $students = array();
		
		$query  = "SELECT reg_no, full_names FROM sch_students WHERE  sch_id = ? ";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i", $sch_id);
		$stmt->execute();
		//$stmt->store_result();
		/* bind result variables */
		$stmt->bind_result($reg_no, $full_names);
		
		/* fetch values */
		while ( $stmt->fetch() ) {
			$tmp = array();
			$tmp["reg_no"] = $reg_no;
			$tmp["full_names"] = trim($full_names);
			array_push($students, $tmp);
		}
        $stmt->close();
        return $students;
    }
	
	// fetching all user chats
    public function getOtherChatUsers($user_id, $user_type_id, $phone_number, $student_id) {

        $chatUsers = array();
		
		$phone_number = $this->formatPhoneNumber($phone_number);
		
		//display chat users based on account. 
		//if user is a school, only show parents subscribed to that school.
		
		//get school user chat users i.e. parents who have subscribed to the school
		if ($user_type_id == SCHOOL_ADMIN_USER_ID)
		{
			$query  = "SELECT c.id as id, c.full_names, st.full_names, st.id, c.created_at as created_at FROM clients c ";
			$query .= " JOIN sch_ussd_subs sb ON c.phone_number = sb.mobile ";
			$query .= " JOIN sch_students st ON sb.reg_no = st.reg_no ";
			$query .= " JOIN sch_ussd su ON su.sch_id = sb.sch_id ";
			$query .= " WHERE c.id != ? AND c.full_names != '' AND sb.sch_id= ? ";
			if ($student_id) { $query .= " AND st.id = $student_id"; }
			$query .= " ORDER BY c.full_names, c.phone_number";
			$stmt = $this->conn->prepare($query);
			$stmt->bind_param("ii", $user_id, $user_id);
		} else {
			$query  = "SELECT c.id as id, c.full_names, st.full_names, su.phone1, st.id, c.created_at as created_at FROM clients c ";
			$query .= " JOIN sch_ussd_subs sb ON c.id = sb.sch_id ";
			$query .= " JOIN sch_students st ON sb.reg_no = st.reg_no ";
			$query .= " JOIN sch_ussd su ON su.sch_id = sb.sch_id ";
			$query .= " WHERE  c.id != ? AND c.full_names != '' AND sb.mobile= ? ";
			if ($student_id) { $query .= " AND st.id = $student_id"; }
			$query .= " ORDER BY c.full_names, c.phone_number";
			$stmt = $this->conn->prepare($query);
			$stmt->bind_param("is", $user_id, $phone_number);
		}
		//echo "$query - $user_id, $phone_number"; exit;
		$stmt->execute();
		$stmt->store_result();
		/* bind result variables */
		$stmt->bind_result($user_id, $full_names, $student_names, $sch_phone_number, $student_id, $created_at);
		
		/* fetch values */
		while ( $stmt->fetch() ) {
			$tmp = array();
			if (!$full_names) { $full_names = $phone_number; }
			$tmp["user_id"] = $user_id;
			$tmp["user_full_names"] = trim($full_names);
			$tmp["student_full_names"] = trim($student_names);
			$tmp["student_id"] = $student_id;
			$tmp["school_phone_number"] = $sch_phone_number;
			$tmp["created_at"] = $this->smartdate($created_at);
			$tmp["student_image"] = $this->getPhoto(STUDENT_PROFILE_PHOTO, $student_id, THUMB_IMAGE);
			$tmp["student_large_image"] = $this->getPhoto(STUDENT_PROFILE_PHOTO, $student_id);
			array_push($chatUsers, $tmp);
		}
        $stmt->close();
        return $chatUsers;
		
    }
	
	//get sub data
	public function getUserSubscription($sch_id, $phone_number) {
	
		//get sub		
		$query  = "SELECT id, sch_id, mobile FROM sch_ussd_subs WHERE sch_id = ? AND mobile = ? ";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("ss", $sch_id, $phone_number);
		$stmt->execute();
		$stmt->bind_result($id, $school_id, $mobile);
		
		$tmp = array();
		/* fetch values */
		if ( $stmt->fetch() ) {
			$tmp["sub_id"] = $id;
			$tmp["sch_id"] = $school_id;
			$tmp["phone_number"] = $mobile;
		} else {
			$tmp["sub_id"] = "";
			$tmp["sch_id"] = "";
			$tmp["phone_number"] = "";
		}
        $stmt->close();
        return $tmp;
		
    }
	
	// fetch student data
	//getStudentData($reg_no, $sch_id, "", "", $student_id);
    public function getStudentData($reg_no=NULL, $sch_id=NULL, $phone_number=NULL, $dob=NULL, $student_id=NULL) {

        //echo "chini dtt\nreg_no - $reg_no\n sch_id - $sch_id\n phone_number - $phone_number\n dob - $dob\n student_id - $student_id"; exit;
		
		$student = array();
		
		if ($dob){
			//format the date
			$day = substr($dob, 0, 2);
			$month = substr($dob, 2, 2);
			$year = substr($dob, 4, 4);
			//mktime(hour,minute,second,month,day,year) 
			$date=mktime(00, 00, 00, $month, $day, $year);
			$dob_date = date("Y-n-j", $date); //n - month with no leading zeros, j - day with no leading zeros
			//end format the date
		}
		
		$phone_number = $this->formatPhoneNumber($phone_number);
		
		//get chats
		$query  = "SELECT id, full_names, reg_no, sch_id, student_profile, guardian_name, guardian_address ";
		$query .= ", admin_date, index_no, nationality, religion, previous_school, house, club, current_class ";
		$query .= ", guardian_id_card, guardian_relation, guardian_occupation, email, county, town, village, location ";
		$query .= ", disability, gender, stream, constituency, student_profile ";
		$query .= ", guardian_phone, updated_at, created_at FROM sch_students WHERE full_names != '' "; 
		if ($dob) { $query .= " AND CONCAT(YEAR(dob),'-',MONTH(dob),'-',DAY(dob)) = '$dob_date'"; }
		if ($reg_no && $sch_id) { $query .= " AND reg_no = '$reg_no' AND sch_id = $sch_id "; }
		if ($student_id) { $query .= " AND id = $student_id "; }
		//echo "$query - $reg_no, $school_id"; exit;
		
		if ($stmt = $this->conn->prepare($query)) {
			
			$stmt->bind_param("i", $school_id);
			$stmt->execute();
			$stmt->store_result();
			/* bind result variables */
			$stmt->bind_result($student_id, $full_names, $reg_no, $sch_id, $student_profile, $guardian_name, $guardian_address, 
			$admin_date, $index_no, $nationality, $religion, $previous_school, $house, $club, $current_class, 
			$guardian_id_card, $guardian_relation, $guardian_occupation, $email, $county, $town, $village, $location, 
			$disability, $gender, $stream, $constituency, $student_profile, $guardian_phone, $updated_at, $created_at);
			
			$response = array();
			/* fetch values */
			if ( $stmt->fetch() ) {
				if (!$full_names) { $full_names = $mobile1; }
				$response["student_id"] = $student_id;
				$response["student_full_names"] = $full_names;
				$response["reg_no"] = $reg_no;
				$response["sch_id"] = $sch_id;
				$response["student_profile"] = $student_profile;
				$response["guardian_name"] = $guardian_name;
				$response["guardian_address"] = $guardian_address;
				$response["guardian_phone"] = $guardian_phone;
				$response["admin_date"] = $admin_date;
				$response["index_no"] = $index_no;
				$response["nationality"] = $nationality;
				$response["religion"] = $religion;
				$response["previous_school"] = $previous_school;
				$response["house"] = $house;
				$response["club"] = $club;
				$response["current_class"] = $current_class;
				$response["guardian_id_card"] = $guardian_id_card;
				$response["guardian_relation"] = $guardian_relation;
				$response["guardian_occupation"] = $guardian_occupation;
				$response["email"] = $email;
				$response["county"] = $county;
				$response["town"] = $town;
				$response["village"] = $village;
				$response["location"] = $location;
				$response["disability"] = $disability;
				$response["student_gender"] = $gender;
				$response["stream"] = $stream;
				$response["constituency"] = $constituency;
				$response["student_profile"] = $student_profile;
				$response["student_updated_at"] = $this->smartdate($updated_at);
				$response["student_created_at"] = $this->smartdate($created_at);
				$response["student_image"] = $this->getPhoto(STUDENT_PROFILE_PHOTO, $user_id, THUMB_IMAGE);
				$response["student_large_image"] = $this->getPhoto(STUDENT_PROFILE_PHOTO, $user_id);
				$response["error"] = false;
				//$response["query"] = $query;
				
				if ($phone_number) {
					//check whether user is already subscribed to a school
					if (!$this->isUserSubExists($phone_number)) {
						//subscribe user to this school
						$response["user_subscribed"] = $this->subscribeUser($phone_number, $school_id, $reg_no);
						$response["user_subscribed"] = true;	
					} else {
						$response["user_subscribed"] = true;	
					}
				}
				
			} else {
				$response["error"] = true;
				//$response["query"] = $query;
				$response["message"] = "Incorrect details or student does not exist";	
			}
			$stmt->close();
		
		} else {
			
			$response["error"] = true;
			$response["message"] = $this->conn->error;
			$response['error_type'] = AN_ERROR_OCCURED_ERROR;	
		
		}
		
        return $response;
    }
	
	//getStudentData($reg_no, $sch_id, "", "", $student_id);
    public function getResultData($result_id=NULL, $term=NULL, $year=NULL, $reg_no=NULL, $sch_id=NULL, $student_id=NULL) {

        //echo "chini dtt\nreg_no - $reg_no\n sch_id - $sch_id\n phone_number - $phone_number\n dob - $dob\n student_id - $student_id"; exit;
		
		$result = array();
		
		//get chats
		$query  = "SELECT id, term, year, class, total_score, mean_score, grade, points, student_id, sch_id, reg_no, updated_at, updated_by";
		$query .= ", created_at, created_by FROM sch_results WHERE term != '' "; 
		if ($result_id) { $query .= " AND id = $result_id "; }
		if ($reg_no && $sch_id && $term && $year) { $query .= " AND reg_no = '$reg_no' AND sch_id = $sch_id AND term = $term AND year = $year "; }
		if ($student_id && $term && $year) { $query .= " AND student_id = $student_id AND term = $term AND year = $year "; }
		//echo "$query - $reg_no, $school_id"; exit;
		
		if ($stmt = $this->conn->prepare($query)) {
			
			$stmt->execute();
			$stmt->store_result();
			/* bind result variables */
			$stmt->bind_result($id, $term, $year, $class, $total_score, $mean_score, $grade, $points, $student_id, $sch_id, $reg_no, $updated_at, $updated_by,
		$created_at, $created_by);
			
			$response = array();
			/* fetch values */
			if ( $stmt->fetch() ) {
				if (!$full_names) { $full_names = $mobile1; }
				$response["result_id"] = $id;
				$response["term"] = $term;
				$response["year"] = $year;
				$response["sch_id"] = $sch_id;
				$response["current_class"] = $class;
				$response["total_score"] = $total_score;
				$response["mean_score"] = $mean_score;
				$response["grade"] = $grade;
				$response["points"] = $points;
				$response["student_id"] = $student_id;
				$response["reg_no"] = $reg_no;
				$response["updated_at"] = $updated_at;
				$response["updated_by"] = $updated_by;
				$response["created_at"] = $created_at;
				$response["created_by"] = $created_by;
				$response["error"] = false;
				//$response["query"] = $query;
				
			} else {
				$response["error"] = true;
				//$response["query"] = $query;
				$response["message"] = "Incorrect details or result does not exist";	
			}
			$stmt->close();
		
		} else {
			
			$response["error"] = true;
			$response["message"] = $this->conn->error;
			$response['error_type'] = AN_ERROR_OCCURED_ERROR;	
		
		}
		
        return $response;
    }
	
	
	//get chat id
	function getChatId($creator_id, $recipient_id) {
        
		$query = "SELECT id FROM conversations WHERE ((user_one = ? AND user_two = ? ) OR (user_two = ? AND user_one = ? )) ";
		$stmt = $this->conn->prepare($query);
        $stmt->bind_param("iiii", $creator_id, $recipient_id, $creator_id, $recipient_id);
		/* execute statement */
		$stmt->execute();	
		$stmt->store_result();
		/* bind result variables */
		$stmt->bind_result($chat_id);	
		/* fetch value */
		$stmt->fetch();		
        $stmt->close();
		
        return $chat_id;
    }
	
	//get chat id
	function getChatDetails($creator_id, $recipient_id, $student_id) {
        
		$query = "SELECT id FROM conversations WHERE ((user_one = ? AND user_two = ? AND student_id = ? ) ";
		$query .= " OR (user_two = ? AND user_one = ? AND student_id = ? )) "; //echo "$query  -  $creator_id, $recipient_id, $student_id"; exit;
		$stmt = $this->conn->prepare($query);
        $stmt->bind_param("iiiiii", $creator_id, $recipient_id, $student_id, $creator_id, $recipient_id, $student_id);
		/* execute statement */
		$stmt->execute();	
		$stmt->store_result();
		/* bind result variables */
		$stmt->bind_result($chat_id);	
		/* fetch value */
		$stmt->fetch();		
        $stmt->close();
		
		//get details
		$query = "SELECT su.sch_name, ss.full_names, ss.id FROM conversations c ";
		$query .= " JOIN sch_students ss ON ss.id = c.student_id ";
		$query .= " JOIN sch_ussd su ON su.sch_id = ss.sch_id ";
		$query .= " WHERE c.id = ? "; //echo "$query  -  $chat_id"; exit;
		$stmtDet = $this->conn->prepare($query);
        $stmtDet->bind_param("i", $chat_id);
		/* execute statement */
		$stmtDet->execute();	
		$stmtDet->store_result();
		/* bind result variables */
		$stmtDet->bind_result($sch_name, $student_full_names, $student_id);	
		/* fetch value */
		$stmtDet->fetch();		
        $stmtDet->close();
		
		$response["chat_id"] = $chat_id;
		$response["sch_name"] = $sch_name;
		$response["student_full_names"] = $student_full_names;
		$response["student_id"] = $student_id;
		
        return $response;
		
    }
	
	//get chat details
	function getStudentId($creator_id, $recipient_id) {
        
		$query = "SELECT student_id FROM conversations WHERE ((user_one = ? AND user_two = ? ) OR (user_two = ? AND user_one = ? )) ";
		$stmt = $this->conn->prepare($query);
        $stmt->bind_param("iiii", $creator_id, $recipient_id, $creator_id, $recipient_id);
		/* execute statement */
		$stmt->execute();	
		$stmt->store_result();
		/* bind result variables */
		$stmt->bind_result($student_id);	
		/* fetch value */
		$stmt->fetch();		
        $stmt->close();
		
        return $student_id;
    }
	
	//get permission id
	function getPermissionId($permission_name) {
        
		$query = "SELECT id FROM permissions WHERE permalink = ? ";
		$stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $permission_name);
		$stmt->execute();	
		$stmt->store_result();
		/* bind result variables */
		$stmt->bind_result($id);	
		/* fetch value */
		$stmt->fetch();		
        $stmt->close();
		
        return $id;
    }	
	
	//get student name given student id
	function getStudentName($student_id) {
        
		$query = "SELECT full_names FROM sch_students WHERE id = ? ";
		$stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $student_id);
		/* execute statement */
		$stmt->execute();	
		//$stmt->store_result();
		/* bind result variables */
		$stmt->bind_result($student_name);	
		/* fetch value */
		$stmt->fetch();		
        $stmt->close();
		
        return $student_name;
    }
	
	//set chat elements as read by this user after user opens chat
	function setChatItemsRead($chat_id, $user_id)
	{		
		//get field to update
		$query = "SELECT user_one, user_two FROM conversations WHERE id = ? ";
		$stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $chat_id);
		
		/* execute statement */
		$stmt->execute();	
		$stmt->store_result();
		/* bind result variables */
		$stmt->bind_result($user_one, $user_two);
		/* fetch value */
		$stmt->fetch();		
        $stmt->close();
		
		//echo "chat_id - $chat_id | user_id - $user_id | user_one - $user_one | user_two - $user_two ";
		
		if ($user_one) 
		{
			
			if ($user_one == $user_id) {
				$field_to_update_read = "user_one_viewed";
				$field_to_update_read_at = "user_one_viewed_at";
			} 
			
			if ($user_two == $user_id) {
				$field_to_update_read = "user_two_viewed";
				$field_to_update_read_at = "user_two_viewed_at";
			}	
			
			$queryUpdate  = " UPDATE messages SET $field_to_update_read = 1, $field_to_update_read_at = ? ";
			$queryUpdate .= " WHERE conversation_id = ? AND $field_to_update_read = 0 ";
			$stmtUpdate = $this->conn->prepare($queryUpdate);
			$current_date = $this->getCurrentDate();
			$stmtUpdate->bind_param("si", $current_date, $chat_id);
			/* execute statement */
			$stmtUpdate->execute();			
			$stmtUpdate->close();	
		
		}
		
	}
	
	//get chat data - chat id and full_names
	function getChatData($creator_id, $recipient_id) {
        
		$query = "SELECT id FROM conversations WHERE ((user_one = ? AND user_two = ? ) OR (user_two = ? AND user_one = ? )) ";
		$stmt = $this->conn->prepare($query);
        $stmt->bind_param("iiii", $creator_id, $recipient_id, $creator_id, $recipient_id);
		/* execute statement */
		$stmt->execute();	
		$stmt->store_result();
		/* bind result variables */
		$stmt->bind_result($chat_id);	
		/* fetch value */
		$stmt->fetch();		
        $stmt->close();
		
        return $chat_id;
    }
	
	//get users names
	function getFullNames($recipient_id) {
        
		$query = "SELECT full_names, phone_number FROM clients WHERE id = ? ";
		$stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $recipient_id);
		/* execute statement */
		$stmt->execute();	
		$stmt->store_result();
		/* bind result variables */
		$stmt->bind_result($full_names, $phone_number);	
		/* fetch value */
		$stmt->fetch();		
        $stmt->close();
		
		if (!$full_names) {
			$full_names = $phone_number;
		}
		
        return $full_names;
    }
	
	//get users names
	function getStudentNames($student_id) {
        
		$query = "SELECT full_names FROM sch_students WHERE id = ? ";
		$stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $student_id);
		/* execute statement */
		$stmt->execute();	
		$stmt->store_result();
		/* bind result variables */
		$stmt->bind_result($full_names);	
		/* fetch value */
		$stmt->fetch();		
        $stmt->close();
		
        return $full_names;
    }
	
	
	
	// fetching chat messages by id
    function getChat($chat_id, $page, $logged_user_id) {
        
		if (!$page) { $page = 1; }//default page
		$lperpage = 20; //default num records
		$offset = ($page - 1) * $lperpage; //page offset
		
		$response = array();
		
		$query  = "SELECT * FROM (";
		$query .= "SELECT m.id as id, cl.id as client_id, cl.user_group, m.created_at as created_at, full_names";
		$query .= ", phone_number, m.message as message FROM messages m ";
		$query .= " JOIN clients cl ON m.user_id = cl.id ";
		$query .= " JOIN conversations cn ON cn.id = m.conversation_id ";
		$query .= " WHERE m.conversation_id= ? ORDER BY m.id DESC ";
		$query .= " LIMIT ?, ? ";
		$query .= ") tmp ORDER BY tmp.id ASC";
		
		if ($stmt = $this->conn->prepare($query)){
			
			$stmt->bind_param("iii", $chat_id, $offset, $lperpage);
			/* execute statement */
			$stmt->execute();	
			$stmt->store_result();
			/* bind result variables */
			$stmt->bind_result($message_id, $user_id, $user_group, $created_at, $full_names, $phone_number, $message);	
			/* fetch values */
			
			while ( $stmt->fetch() ) {
				$tmp = array();	
				if ($user_group == SCHOOL_ADMIN_USER_ID) { $photo_field = SCHOOL_PROFILE_PHOTO; } else { $photo_field = USER_PROFILE_PHOTO; }	
				if (!$full_names) { $full_names = $phone_number; }	
				$tmp["message_id"] = $message_id;
				$tmp["user_id"] = $user_id;
				$tmp["created_at"] = $this->smartdate($created_at);
				$tmp["full_names"] = $full_names;
				$tmp["phone_number"] = $phone_number;
				$tmp["message"] = $message;
				$tmp["user_image"] = $this->getPhoto($photo_field, $user_id, THUMB_IMAGE);
				$tmp["user_large_image"] = $this->getPhoto(USER_PROFILE_PHOTO, $user_id);
				array_push($response, $tmp);			
				
			}
			
			$stmt->close();
		
		} else {
		
			//$response["query"] = $query;
			$response["error"] = true;
			$response["message"] = $this->conn->error;
			$response['error_type'] = AN_ERROR_OCCURED_ERROR;
		
		}
		
		//set chat elements as already read
		$this->setChatItemsRead($chat_id, $logged_user_id);
		
        return $response;
		
    }
	
	// fetching user school ids
    function getUserSchoolIds($id, $user_group) {
       
		if ($user_group == SCHOOL_ADMIN_USER_ID){ 
			
			return $id; 
			
		} else if (($user_group == SUPER_ADMIN_USER_ID) || ($user_group == NORMAL_ADMIN_USER_ID)){ 
			
			return 1; 
			
		} else {

			//else fetch user school ids
			$school_ids = array();
			$query = "SELECT DISTINCT sch_id FROM group_permissions ";
			$query .= " WHERE group_id = ? ";
			//echo "$query - $id, $user_group"; exit;
			$stmt = $this->conn->prepare($query);
			$stmt->bind_param("i", $user_group);
			/* execute statement */
			$stmt->execute();	
			$stmt->store_result();
			/* bind result variables */
			$stmt->bind_result($sch_id);	
			/* fetch values */
			
			while ($stmt->fetch()) {
				$school_ids = $sch_id;		
			}
			$school_ids = implode(",", $school_ids);
			
			$stmt->close();
			
			return $school_ids;
		
		}
		
    }
	 
    /**
     * Checking for duplicate user by phone
     * @return boolean
     */
    private function isUserExists($input) {
		$email = $input;
		$phone_number = $this->formatPhoneNumber($input);
		$query = "SELECT id FROM clients WHERE ( email = ? OR phone_number = ? )";		
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("ss", $email, $phone_number);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }
	
	/**
     * Checking for exitint user-password combi
     * @return boolean
     */
    private function isUserPasswordExists($input, $password) {
		$email = $input;
		$password = md5($password);
		$phone_number = $this->formatPhoneNumber($input);
		$query = "SELECT id FROM clients WHERE ( email = ? OR phone_number = ? ) AND password = ?";		
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("sss", $email, $phone_number, $password);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }
		
	/**
     * Checking for duplicate user by phone
     * @return boolean
     */
    private function isStudentExists($sch_id, $id) {
		$email = $input;
		$phone_number = $this->formatPhoneNumber($input);
		$query = "SELECT id FROM sch_students WHERE sch_id = ? AND ( reg_no = ? OR index_no = ? )";		
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("iii", $sch_id, $id, $id);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }

	/**
     * Checking for duplicate subject
     * @return boolean
     */
    private function subjectExists($subject_name, $level) {
		$query = "SELECT id FROM sch_subjects WHERE name = ? AND school_level = ? ";		
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("si", $subject_name, $level);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }
	
	/**
     * Check if subject code exists
     * @return boolean
     */
    public function isSubjectCodeExists($subject_code) {
		$query = "SELECT id FROM sch_subjects WHERE code = ? ";		
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("s", $subject_code);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }
	
	
	/**
     * Checking for duplicate user Groups
     * @return boolean
     */
    public function userGroupExists($group_name, $user_id) {
		$query = "SELECT id FROM groups WHERE name = ? AND created_by = ? ";		
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("si", $group_name, $user_id);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }	

	//subscribe user
	public function subscribeUser($phone_number, $school_id, $reg_no) {
		$response = array();
		$current_date = $this->getCurrentDate();
		//get school data
		$stmtSchool = $this->conn->prepare("SELECT sch_name, province FROM sch_ussd WHERE sch_id = ? ");
        $stmtSchool->bind_param("i", $school_id);
        $stmtSchool->execute();
		$stmtSchool->store_result();
		/* bind result variables */
		$stmtSchool->bind_result($sch_name, $province);	
		/* fetch values */
		$stmtSchool->fetch();
		$stmtSchool->close();
		
		if ($sch_name)
		{
			// insert sub query
			$query = "INSERT INTO sch_ussd_subs(mobile, sch_id, sch_name, sub_date, prov, reg_no) VALUES(?, ?, ?, ?, ?, ?)";
			$stmt = $this->conn->prepare($query);
			$stmt->bind_param("sissss", $phone_number, $school_id, $sch_name, $current_date, $province, $reg_no);
			$result = $stmt->execute();
			$stmt->close();
			
			// Check for successful insertion
			if ($result) {
				// successfully inserted
				$new_sub_id = $this->conn->insert_id;
				$response = $new_sub_id;
				
			} else {
				// Failed to create 
				$response = false;
			}
		}
		return $response;
	}
	
	//create new student fees
	public function createNewStudentFee($student_id, $sch_id, $reg_no, $year) {
		$response = array();
		$current_date = $this->getCurrentDate();
		$created_by = USER_ID;
		
		$student_details = $this->getStudentData($reg_no, $sch_id, "", "", $student_id);
		$student_id = $student_details["student_id"];
		$sch_id = $student_details["sch_id"];
		$reg_no = $student_details["reg_no"];
	
		// insert query
		$query = "INSERT INTO sch_fees(year, student_id, sch_id, reg_no, created_at, created_by) ";
		$query .= " VALUES(?, ?, ?, ?, ?, ?)";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("iiisss", $year, $student_id, $sch_id, $reg_no, $current_date, $created_by);
		$result = $stmt->execute();
		$stmt->close();
		
		// Check for successful insertion
		if ($result) {
			// successfully inserted
			$new_id = $this->conn->insert_id;
			$response = $new_id;
			
		} else {
			// Failed to create 
			$response = false;
		}
		
		return $response;
		
	}
	
	//create new student results
	//createNewStudentResult($student_id, $sch_id, $reg_no, $year, $term, $class);
	public function createNewStudentResult($student_id, $sch_id, $reg_no, $year, $term, $class) {
		//echo "in xx1 - $student_id, $sch_id, $reg_no, $year, $term, $class == end in \n\n ";
		$response = array();
		$current_date = $this->getCurrentDate();
		$created_by = USER_ID;
		
		//echo "reg_no - $reg_no\n sch_id - $sch_id\n year - $year\n term - $term\n class - $class\n student_id - $student_id"; exit;
	
		// insert query
		$query = "INSERT INTO sch_results(year, term, class, student_id, sch_id, reg_no, created_at, created_by) ";
		$query .= " VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("iiiiissi", $year, $term, $class, $student_id, $sch_id, $reg_no, $current_date, $created_by);
		$result = $stmt->execute();
		$stmt->store_result();
		$stmt->close();
		
		// Check for successful insertion
		if ($result) {
			// successfully inserted
			$new_id = $this->conn->insert_id;
			$response = $new_id;
			
		} else {
			// Failed to create 
			$response = false;
		}
		
		return $response;
		
	}	
	
	//check if user is already subscribed
	public function isSubExists($phone_number, $sch_id, $reg_no) {
		$phone_number = $this->formatPhoneNumber($phone_number);
		$stmt = $this->conn->prepare("SELECT id FROM sch_ussd_subs WHERE mobile = ? AND sch_id = ? AND reg_no = ? ");
        $stmt->bind_param("sis", $phone_number, $sch_id, $reg_no);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }
	
	//check if user has any subscriptions
	public function isUserSubExists($phone_number) {
		$phone_number = $this->formatPhoneNumber($phone_number);
		$stmt = $this->conn->prepare("SELECT id FROM sch_ussd_subs WHERE mobile = ? ");
        $stmt->bind_param("s", $phone_number);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }
	
	//check if a chat aleady exists
	public function isChatExists($creator_id, $recipient_id, $student_id) {
		$stmt = $this->conn->prepare("SELECT id FROM conversations WHERE ((user_one = ? AND user_two = ?) OR (user_two = ? AND user_one = ?)) AND student_id = ? ");
        $stmt->bind_param("iiiii", $creator_id, $recipient_id, $creator_id, $recipient_id, $student_id);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }
	
	//check for valid phone number
	public function isNumberValid($phone_number) {
		
		$success = 1;
		
		if (strlen($phone_number) == 10) {
			
			if (substr($phone_number,0,1) != "0") { $success = 0; } //first digit not zero (0)
			if (substr($phone_number,1,1) != "7") { $success = 0; } //second digit not zero (7)			
			
		} else if (strlen($phone_number) == 12) {
			
			if (substr($phone_number,0,3) != "254") { $success = 0; } //first digit not zero (0)
			if (substr($phone_number,3,1) != "7") { $success = 0; } //second digit not zero (7)
			
		} else {
			
			$success = 0;
			
		}
		
        return $success;
		
    }
	
	//reformat the phone number, add 254 at beginning and remove initial zero (0)
	private function formatPhoneNumber($phone_number) {
        
               return   "254". substr(trim($phone_number),-9);   
        
		//first check length of number - if 12 chars return as it is if 10, proceed
		/*
		if (strlen($phone_number) == 12) 
		{
			$phone_number = $phone_number;
		}
		
		if (strlen($phone_number) == 10) 
		{
			//remove initial zero
			$phone_number = substr($phone_number, 1, strlen($phone_number));
			
			//add 254 at the beginning
			$phone_number = "254" . $phone_number;
		}
		
        return   $phone_number;
		
		*/
		
    }
	
	//login user exists?
	private function isUserLoginExists($input, $password) {
		
		$password = md5($password);
		$email = $input;
		$phone_number = $this->formatPhoneNumber($input);
		$query = "SELECT id FROM clients WHERE password = ? AND (email = ? OR phone_number = ? )";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("sss", $password, $email, $phone_number);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }
	
	//is user activated?
	private function isUserActivated($input) {
		$email = $input;
		$phone_number = $this->formatPhoneNumber($input);
		$query = "SELECT id FROM clients WHERE ((email = ? OR phone_number = ?) AND status = 1)";	
		$stmt = $this->conn->prepare($query);
        $stmt->bind_param("ss", $email, $phone_number);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }
	
    public function activateUserStatus($phone_number){
        $stmt = $this->conn->prepare("UPDATE clients set active = 1 where phone_number = ?");
        $stmt->bind_param("i", $phone_number);
         
        $stmt->execute();
         
        $stmt = $this->conn->prepare("UPDATE sms_codes set status = 1 where mobile = ?");
        $stmt->bind_param("i", $phone_number);
         
        $stmt->execute();
    }
 
    /**
     * Generating random Unique MD5 String for user Api key
     */
    private function generateApiKey() {
        return md5(uniqid(rand(), true));
    }
 
    /**
     * Fetching user record
     */
	 // user details
    public function getUserDetails($username) {
        
		$response = array();
			
		// get the details
		$phone_number = $this->formatPhoneNumber($username);
	
		$query = "SELECT c.id, full_names, email, user_group, phone_number, receive_messages, status, user_group, gcm_registration_id, g.name, c.created_at ";
		$query .= " FROM clients c LEFT JOIN groups g ON c.user_group = g.id ";
		$query .= " WHERE (phone_number = ? OR  email = ? OR  c.id = ?)"; //echo "$query - $phone_number, $username"; exit;
		
		if ($stmt = $this->conn->prepare($query)) {
			
			$stmt->bind_param("ssi", $phone_number, $username, $username);
			$result = $stmt->execute();
			
			if ($result) {
				
				$stmt->store_result();
				$stmt->bind_result($id, $full_names, $email, $user_group, $phone, $receive_messages, $status, $group_id, $gcm_registration_id, $group_name, $created_at);
				$stmt->fetch();
				$stmt->close();
				$tmp = array();
				if ($user_group == SCHOOL_ADMIN_USER_ID) { $photo_field = SCHOOL_PROFILE_PHOTO; } else { $photo_field = USER_PROFILE_PHOTO; }	
				$response['user_id'] = $id;
				$response['user_full_names'] = $full_names;
				$response['user_email'] = $email;
				$response['user_group_id'] = $group_id;
				$response['user_group_name'] = $group_name;
				$response['user_phone_number'] = $phone;
				$response["gcm_registration_id"] = $gcm_registration_id;
				$response['user_image'] = $this->getPhoto($photo_field, $id, THUMB_IMAGE);
				$response['user_large_image'] = $this->getPhoto($photo_field, $id);
				$response["created_at"] = $this->adjustDate(NULL, $this->php_date($created_at), NULL);
			
			}
			
		} else {
			
			//$response["query"] = $query;
			$response["error"] = true;
			$response["message"] = $this->conn->error;
			$response['error_type'] = AN_ERROR_OCCURED_ERROR;
			
		}

        return $response;
    }
	
	/**
     * Fetching sms subs by phone number, sch_id, reg_no
     */
    public function getSmsSubscription($phone_number, $sch_id, $reg_no) {
        $query = "SELECT ss.id, st.id, mobile, ss.sch_id, full_names, sch_name, prov, sub_date, cat, active, agent, Processed ";
		$query .= " FROM sch_ussd_subs ss LEFT JOIN sch_students st ON st.reg_no=ss.reg_no WHERE mobile = ? AND ss.sch_id = ? AND ss.reg_no = ? ";
		$stmt = $this->conn->prepare($query);
        $stmt->bind_param("sis", $phone_number, $sch_id, $reg_no);
        $stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($sub_id, $student_id, $mobile, $sch_id, $full_names, $sch_name, $prov, $sub_date, $cat, $active, $agent, $processed);
		$stmt->fetch();
		if ($mobile) {
            $sms = array();
            $sms["sub_id"] = $sub_id;
			$sms["sub_phone_number"] = $mobile;
			$sms["sub_sch_id"] = $sch_id;
            $sms["sub_sch_name"] = $sch_name;
			$sms["sub_student_name"] = $full_names;
			$sms["sub_prov"] = $prov;
            $sms["sub_cat"] = $cat;
			$sms["sub_active"] = $active;
			$sms["sub_agent"] = $agent;
            $sms["sub_processed"] = $processed;
			$sms["sub_user_image"] = $this->getPhoto(STUDENT_PROFILE_PHOTO, $student_id, THUMB_IMAGE);
			$sms["sub_user_large_image"] = $this->getPhoto(STUDENT_PROFILE_PHOTO, $student_id);
            $sms["sub_created_at"] = $this->adjustDate("d-M-Y", $this->php_date($sub_date), NULL);			
			
            $stmt->close();
            return $sms;
        } else {
            return NULL;
        }
    }
	
	//get recommend text
	public function getSettingValue($setting_name) {
        $query = "SELECT value FROM app_settings WHERE name = ? ";
		$stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $setting_name);
        $stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($value);
		$stmt->fetch();
		$stmt->close();
		return trim($value);
    }
	
	
	
	public function mysql_date($thedate) {
		return date( 'Y-m-d H:i:s', $thedate );
	}
	
	public function php_date($thedate) {
		return strtotime( $thedate );
	}
	public function friendly_date($timestamp){
		return date( 'd-M-Y', $timestamp );
	}
	public function format_num($num, $decimals=2) {
		return number_format($num,$decimals, '.', ',');
	}
	
	public function removelastor($str) {
		  $startpos = strlen($str) - 2;	
		  $getstring = substr($str,$startpos);	
		  if ($getstring == "or") {	
			  return substr($str,0,$startpos);	
		  } else {	
			  return $str;	
		  }
	}
		
	public function clean($value) {
		//function to check for both sql injection and cross site scripting
		 //Trim the value
		 $value = trim($value);
		// Stripslashes
		if (get_magic_quotes_gpc()) {
			$value = stripslashes($value);
		}
		 // Convert all &lt;, &gt; etc. to normal html and then strip these
		 $value = strtr($value,array_flip(get_html_translation_table(HTML_ENTITIES)));
		 // Strip HTML Tags
		 $value = strip_tags($value);
		// Quote the value
		//$value = mysql_real_escape_string($value);
		$value = htmlspecialchars ($value);
		return $value;
	}
	
	// format the date
	function adjustDate($format=false, $timestamp=false, $timezone=false)
	{
		if (!$format){ $format = 'd-M-Y, h:ia'; } 
		if (!$timezone){ $timezone = 'Africa/Nairobi'; }
		$userTimezone = new DateTimeZone(!empty($timezone) ? $timezone : 'GMT');
		$gmtTimezone = new DateTimeZone('GMT');
		$myDateTime = new DateTime(($timestamp!=false?date("r",(int)$timestamp):date("r")), $gmtTimezone);
		$offset = $userTimezone->getOffset($myDateTime);
		return date($format, ($timestamp!=false?(int)$timestamp:$myDateTime->format('U')) + $offset);
	}
	
	//check whether supplied url actually exists
	public function checkUrlExists($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
	//end check supplied url
	
	
	public function getUnreadCount($ticket_id)
	{
		return "3";	
	}
	
	public function getUserImage($user_id)
	{
		return "http://showbiz.co.ke/images/articles/467821435996180.jpg";	
	}
	
	function reformatDate($thedate){
		return date("Y-m-d H:i:s");
	}
	
	function getCurrentDate()
	{
		return date("Y-m-d H:i:s");
	}
	
	function getStudentFeeEntry($student_id, $year){
		$query = "SELECT id FROM sch_fees WHERE student_id = ? AND year = ? ";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("ii", $student_id, $year); // number of params s - string, i - integer, d - decimal, etc
		$result = $stmt->execute();
		$stmt->bind_result($fees_id);
		$stmt->fetch();
		$stmt->store_result();
		$stmt->close();
		return $fees_id;	
	}
	
	function insertStudentFeeEntry($student_id, $creator_id, $year){
		
		$current_date = $this->getCurrentDate();
		
		$query  = "INSERT INTO sch_fees(student_id, created_at, created_by, year) VALUES (?, ?, ?, ?) ";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("isii", $student_id, $current_date, $creator_id, $year);
		$result = $stmt->execute();
		$stmt->store_result();
		$stmt->close();

		return $this->conn->insert_id;
		
	}
	
	//get photo
	function getPhoto($section, $section_item_id, $image_type = NULL)
	{
		if ($image_type == THUMB_IMAGE){ $image_field = "thumb_img"; } else { $image_field = "full_img"; }
		$query = "SELECT $image_field FROM images WHERE image_section = ? AND image_section_id = ? ORDER BY id DESC LIMIT 0,1"; 
		//echo "query - $query == section - $section == section_item_id - $section_item_id"; exit;

		if($stmt = $this->conn->prepare($query)){
		
			$stmtGetPhoto = $this->conn->prepare($query);
			$stmtGetPhoto->bind_param("si", $section, $section_item_id);
			$stmtGetPhoto->execute();
			$stmtGetPhoto->store_result();
			$stmtGetPhoto->bind_result($full_img);
			$stmtGetPhoto->fetch();
			//if no image, set default
			if (!$full_img){ $full_img = DEFAULT_USER_IMAGE; }
			$stmtGetPhoto->close();	
			
			return SITEPATH . $full_img; 
			
		}else{
		   //error !! don't go further
		   //var_dump($this->conn->error); ********************************************************************************* CHECK*********
		   $full_img = DEFAULT_USER_IMAGE;
		   return SITEPATH . $full_img; 
		}

	}
	
	//save new photo
	public function savePhoto($section, $section_item_id, $caption, $thumb_img, $full_img) {
		$response = array();
		$current_date = $this->getCurrentDate();
		$created_by = USER_ID;
		
		// insert photo query
		$query = "INSERT INTO images(image_section, image_section_id, caption, thumb_img, full_img, created_at, created_by) VALUES(?, ?, ?, ?, ?, ?, ?)";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("sissssi", $section, $section_item_id, $caption, $thumb_img, $full_img, $current_date, $created_by);
		$result = $stmt->execute();
		$stmt->close();
		
		// Check for successful insertion
		if ($result) {
			// successfully inserted
			$new_image_id = $this->conn->insert_id;
			$response['error'] = false;
			$response['message'] = "Photo uploaded successfully";
			//$response['close_form'] = true;
			$response['image_id'] = $new_image_id;
			$response['image_src'] = $full_img;
			
		} else {
			// Failed to create 
			$response['error'] = true;
			$response['message'] = "An error occured. Please try again.";
		}
			
		return $response;
	}
	
	
	//get school name
	function getSchoolName($sch_id)
	{
		$query = "SELECT sch_name FROM sch_ussd WHERE sch_id= ? ";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i", $sch_id);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($sch_name);
		$stmt->fetch();
		$stmt->close();	
		return $sch_name; 
	}
	
	//get school's main/ large photo
	function getSchoolMainPhoto($sch_id)
	{
		$query = "SELECT full_img FROM images WHERE category_item_id= ? AND image_category_id = ? ";
		$stmtGetPhoto = $this->conn->prepare($query);
		$stmtGetPhoto->bind_param("ii", $sch_id, SCHOOL_PIC_CAT_ID);
		$stmtGetPhoto->execute();
		$stmtGetPhoto->bind_result($full_img);
		$stmtGetPhoto->fetch();
		$stmtGetPhoto->close();	
		if (!$full_img){ $full_img = DEFAULT_SCHOOL_IMAGE; }
		return SITEPATH . $full_img; 
	}
		
	//get unread messages for this conversation/ chat
	function getUnreadMessagesCount($conversation_id, $user_id)
	{
		//check if user has account balance
		$query  = "SELECT count(m.conversation_id) AS unread FROM messages m ";
		$query .= " JOIN conversations c ON m.conversation_id=c.id ";
		$query .= " WHERE CASE ";
		$query .= " WHEN c.user_one = ? ";
		$query .= " THEN m.user_one_viewed != 1 AND m.user_id != ? ";
		$query .= " WHEN c.user_two = ? ";
		$query .= " THEN m.user_two_viewed != 1 AND m.user_id != ? ";
		$query .= " END  ";
		$query .= " AND c.id = ? ";
		
		//echo $query; exit;
		
		$stmtCheckUnread = $this->conn->prepare($query);
		$stmtCheckUnread->bind_param("iiiii", $user_id, $user_id, $user_id, $user_id, $conversation_id);
		$stmtCheckUnread->execute();
		$stmtCheckUnread->bind_result($unread);
		$stmtCheckUnread->fetch();
		//end check
		if ($unread) { return $unread; } else { return 0; }
				
		//return 2;
		
	}
	
	/// function to generate random number ///////////////

	function generateCode($length = 5, $add_dashes = false, $available_sets = 'ud')
	{
		$sets = array();
		if(strpos($available_sets, 'l') !== false)
			$sets[] = 'abcdefghjkmnpqrstuvwxyz';
		if(strpos($available_sets, 'u') !== false)
			$sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
		if(strpos($available_sets, 'd') !== false)
			$sets[] = '23456789';
		if(strpos($available_sets, 's') !== false)
			$sets[] = '!@#$%&*?';
		$all = '';
		$password = '';
		foreach($sets as $set)
		{
			$password .= $set[array_rand(str_split($set))];
			$all .= $set;
		}
		$all = str_split($all);
		for($i = 0; $i < $length - count($sets); $i++)
			$password .= $all[array_rand($all)];
		$password = str_shuffle($password);
		if(!$add_dashes)
			return $password;
		$dash_len = floor(sqrt($length));
		$dash_str = '';
		while(strlen($password) > $dash_len)
		{
			$dash_str .= substr($password, 0, $dash_len) . '-';
			$password = substr($password, $dash_len);
		}
		$dash_str .= $password;
		return $dash_str;
	} 
	
	// end of generate random number function


	function smartdate($date) {
		
		$timestamp = $this->php_date($date); 
		$diff = time() - $timestamp;
		
		//$date = $this->adjustDate("Y-m-d G:i:s", $this->php_date($date), TIMEZONE);//2016-04-04 23:14:34
		
		//echo "$timestamp - ". time(); exit;
		if ($diff <= 0) {
			return 'Now';
		}
		else if ($diff < 60) {
			return $this->grammar_date(floor($diff), ' sec(s)'); // 12 secs ago
		}
		else if ($diff < 60*60) {
			return $this->grammar_date(floor($diff/60), ' min(s)'); // 12 mins ago
		}
		else if ($diff < 60*60*24) { // 1 day
			return $this->grammar_date(floor($diff/(60*60)), ' hr(s)'); // 12 hrs ago
		}
		else if ($diff < 60*60*24*7) { //7 days
			//date("D g:ia", $timestamp);
			return $this->grammar_date(floor($diff/(60*60*24)), ' day(s)'); //show as: Mon 3pm, Tue 1am, etc
		}
		else {
			//date("j-M-Y ga", $timestamp);
			//return date("j-M-Y ga",$timestamp);					//show as 12-Dec-2016 5pm
			return $this->adjustDate("d-M-Y, g:ia", $this->php_date($date), TIMEZONE);
		}
	}
	
	function grammar_date($val, $sentence) {
	
		if ($val > 1) {
	
			return $val.str_replace('(s)', 's', $sentence);
	
		} else {
	
			return $val.str_replace('(s)', '', $sentence);
	
		}
	
	}
	
	public function createLoginSession($logged_username) {
		
		$phone_number = $this->formatPhoneNumber($logged_username);
		
		$query = "SELECT c.id, full_names, first_name, last_name, email, phone_number, user_group, gcm_registration_id, g.name ";
		$query .= " FROM clients c LEFT JOIN groups g ON c.user_group = g.id ";
		$query .= " WHERE (phone_number = ? OR  email = ?)";
        $stmt = $this->conn->prepare($query); 
        $stmt->bind_param("ss", $phone_number, $logged_username);
        
		if ($stmt->execute()) {
            $stmt->store_result();
			$stmt->bind_result($id, $full_names, $first_name, $last_name, $email, $phone, $group_id, $gcm_registration_id, $group_name);
            $stmt->fetch();
			$stmt->close();
			//echo "query - $query == -id - $id, logged_username - $logged_username, logged_username - $logged_username, phone_number - $phone_number, group_id -  $group_id"; //exit;
            
            //store user values in session vars
			session_start('USERS');

			$_SESSION['SESS_ID'] = session_id();
			$_SESSION['SESS_LOGGED_USER_NAME'] = $full_names;
			$_SESSION['SESS_FULL_NAMES'] = $first_name ." ".$last_name;
			$_SESSION['SESS_FIRST_NAME'] = $first_name;
			$_SESSION['SESS_LAST_NAME'] = $last_name;
			$_SESSION['SESS_USER_ID'] = $id;
			$_SESSION['SESS_USER_EMAIL'] = $email;
			$_SESSION['SESS_USER_PHONE'] = $phone;
			$_SESSION['SESS_USER_GROUP_ID'] = $group_id;
			$_SESSION['SESS_USER_GROUP_NAME'] = $group_name;
			$_SESSION['SESS_USER_IMAGE'] = $this->getPhoto(USER_PROFILE_PHOTO, $account_id, THUMB_IMAGE);
			$_SESSION['SESS_GCM_ID'] = $gcm_registration_id;
			$_SESSION['SESS_USER_LOGGED_IN'] = true;
			$_SESSION['USER_SCHOOL_IDS'] = $this->getUserSchoolIds($id, $group_id);	
			
			if ($group_id == SUPER_ADMIN_USER_ID) { $_SESSION['SUPER_ADMIN_USER'] = 1; }  
			if ($group_id == SCHOOL_ADMIN_USER_ID) { $_SESSION['SCHOOL_ADMIN_USER'] = 1; }  
			if ($group_id == NORMAL_ADMIN_USER_ID) { $_SESSION['NORMAL_ADMIN_USER'] = 1; }  
			if ($group_id == NORMAL_USER_ID) { $_SESSION['NORMAL_USER'] = 1; }	
			
			//SET GROUP PERMISSIONS
			//user perms
			$read_access_user_perms=array(CREATE_USER_PERMISSION, UPDATE_USER_PERMISSION, READ_USER_PERMISSION, DELETE_USER_PERMISSION);
			if ($this->groupHasAnyRole($group_id, $read_access_user_perms)){ 
				//user can read user data
				$_SESSION['HAS_READ_USER_PERMISSION'] = 1;
			} 
			if ($this->groupHasAnyRole($group_id, CREATE_USER_PERMISSION)){ $_SESSION['HAS_CREATE_USER_PERMISSION'] = 1; }
			if ($this->groupHasAnyRole($group_id, UPDATE_USER_PERMISSION)){ $_SESSION['HAS_UPDATE_USER_PERMISSION'] = 1; }
			if ($this->groupHasAnyRole($group_id, DELETE_USER_PERMISSION)){ $_SESSION['HAS_DELETE_USER_PERMISSION'] = 1; }
			
			//student perms
			$read_access_student_perms=array(CREATE_STUDENT_PERMISSION, UPDATE_STUDENT_PERMISSION, READ_STUDENT_PERMISSION, DELETE_STUDENT_PERMISSION);
			if ($this->groupHasAnyRole($group_id, $read_access_student_perms)){ 
				//user can read student data
				$_SESSION['HAS_READ_STUDENT_PERMISSION'] = 1;
			}
			if ($this->groupHasAnyRole($group_id, CREATE_STUDENT_PERMISSION)){ $_SESSION['HAS_CREATE_STUDENT_PERMISSION'] = 1; }
			if ($this->groupHasAnyRole($group_id, UPDATE_STUDENT_PERMISSION)){ $_SESSION['HAS_UPDATE_STUDENT_PERMISSION'] = 1; }
			if ($this->groupHasAnyRole($group_id, DELETE_STUDENT_PERMISSION)){ $_SESSION['HAS_DELETE_STUDENT_PERMISSION'] = 1; }
			
			//school perms
			$read_access_school_perms=array(CREATE_SCHOOL_PERMISSION, UPDATE_SCHOOL_PERMISSION, READ_SCHOOL_PERMISSION, DELETE_SCHOOL_PERMISSION);
			if ($this->groupHasAnyRole($group_id, $read_access_school_perms)){ 
				//user can read school data
				$_SESSION['HAS_READ_SCHOOL_PERMISSION'] = 1; 
			}
			if ($this->groupHasAnyRole($group_id, CREATE_SCHOOL_PERMISSION)){ $_SESSION['HAS_CREATE_SCHOOL_PERMISSION'] = 1; }
			if ($this->groupHasAnyRole($group_id, UPDATE_SCHOOL_PERMISSION)){ $_SESSION['HAS_UPDATE_SCHOOL_PERMISSION'] = 1; }
			if ($this->groupHasAnyRole($group_id, DELETE_SCHOOL_PERMISSION)){ $_SESSION['HAS_DELETE_SCHOOL_PERMISSION'] = 1; }
			
			//subject perms
			$read_access_subject_perms=array(CREATE_SUBJECT_PERMISSION, UPDATE_SUBJECT_PERMISSION, READ_SUBJECT_PERMISSION, DELETE_SUBJECT_PERMISSION);
			if ($this->groupHasAnyRole($group_id, $read_access_subject_perms)){ 
				//user can read subject data
				$_SESSION['HAS_READ_SUBJECT_PERMISSION'] = 1; 
			}
			if ($this->groupHasAnyRole($group_id, CREATE_SUBJECT_PERMISSION)){ $_SESSION['HAS_CREATE_SUBJECT_PERMISSION'] = 1; }
			if ($this->groupHasAnyRole($group_id, UPDATE_SUBJECT_PERMISSION)){ $_SESSION['HAS_UPDATE_SUBJECT_PERMISSION'] = 1; }
			if ($this->groupHasAnyRole($group_id, DELETE_SUBJECT_PERMISSION)){ $_SESSION['HAS_DELETE_SUBJECT_PERMISSION'] = 1; }
			
			//result perms
			$read_access_result_perms=array(CREATE_RESULT_PERMISSION, UPDATE_RESULT_PERMISSION, READ_RESULT_PERMISSION, DELETE_RESULT_PERMISSION);
			if ($this->groupHasAnyRole($group_id, $read_access_result_perms)){ 
				//user can read result data
				$_SESSION['HAS_READ_RESULT_PERMISSION'] = 1;
			}
			if ($this->groupHasAnyRole($group_id, CREATE_RESULT_PERMISSION)){ $_SESSION['HAS_CREATE_RESULT_PERMISSION'] = 1; }
			if ($this->groupHasAnyRole($group_id, UPDATE_RESULT_PERMISSION)){ $_SESSION['HAS_UPDATE_RESULT_PERMISSION'] = 1; }
			if ($this->groupHasAnyRole($group_id, DELETE_RESULT_PERMISSION)){ $_SESSION['HAS_DELETE_RESULT_PERMISSION'] = 1; }
			
			//fee perms
			$read_access_fee_perms=array(CREATE_FEE_PERMISSION, UPDATE_FEE_PERMISSION, READ_FEE_PERMISSION, DELETE_FEE_PERMISSION);
			if ($this->groupHasAnyRole($group_id, $read_access_fee_perms)){ 
				//user can read fees data
				$_SESSION['HAS_READ_FEE_PERMISSION'] = 1; 
			}
			if ($this->groupHasAnyRole($group_id, CREATE_FEE_PERMISSION)){ $_SESSION['HAS_CREATE_FEE_PERMISSION'] = 1; }
			if ($this->groupHasAnyRole($group_id, UPDATE_FEE_PERMISSION)){ $_SESSION['HAS_UPDATE_FEE_PERMISSION'] = 1; }
			if ($this->groupHasAnyRole($group_id, DELETE_FEE_PERMISSION)){ $_SESSION['HAS_DELETE_FEE_PERMISSION'] = 1; }
			
			//bulk sms perms
			$read_access_bulk_sms_perms=array(CREATE_BULK_SMS_PERMISSION, UPDATE_BULK_SMS_PERMISSION, READ_BULK_SMS_PERMISSION, DELETE_BULK_SMS_PERMISSION);
			if ($this->groupHasAnyRole($group_id, $read_access_bulk_sms_perms)){ 
				//user can read fees data
				$_SESSION['HAS_READ_BULK_SMS_PERMISSION'] = 1; 
			}
			if ($this->groupHasAnyRole($group_id, CREATE_BULK_SMS_PERMISSION)){ $_SESSION['HAS_CREATE_BULK_SMS_PERMISSION'] = 1; }
			if ($this->groupHasAnyRole($group_id, UPDATE_BULK_SMS_PERMISSION)){ $_SESSION['HAS_UPDATE_BULK_SMS_PERMISSION'] = 1; }
			if ($this->groupHasAnyRole($group_id, DELETE_BULK_SMS_PERMISSION)){ $_SESSION['HAS_DELETE_BULK_SMS_PERMISSION'] = 1; }
			//END SET GROUP READ PERMISSIONS
						
			//sesion lifetime
			ini_set('session.cookie_lifetime',400);
			
        }
		
    }
	
	//function to check if user has a specific role from db
    public function hasRole($group_id, $perm_name, $check=false)
    {
        $query = "SELECT * FROM group_permissions gp ";
		$query .= " LEFT JOIN groups g ON gp.group_id=g.id ";
		$query .= " LEFT JOIN permissions p ON gp.permission_id=p.id ";
		$query .= " WHERE  gp.group_id = ? AND p.permalink = ? ";
		$stmt = $this->conn->prepare($query); // echo "$query - $group_id - $perm_name"; exit;
		$stmt->bind_param("is", $group_id, $perm_name);
        $stmt->execute();
        $stmt->store_result();
        $rows = $stmt->num_rows;
        $stmt->close(); //echo $rows; exit;
        //if user has permissions or user is super admin
		if ($check) { //are we checking permissions on forms or checking on site usage
			return $rows > 0; 
		} else {
			if (($rows > 0) || ($group_id==SUPER_ADMIN_USER_ID)) { return true; } else { return false; }
		}
    }
	
	//check user roles
    public function groupHasAnyRole($group_id, $roles)
    {
		if (is_array($roles))
        {
            foreach ($roles as $role)
            {
                if ($this->hasRole($group_id, $role))
                {
                    return true;
                }
            }
        } else {
            if ($this->hasRole($group_id, $roles)) 
            {
                return true;
            }
        }
        //no role return false
        return false;
    }

	/**
     * Check if subject permaink exists
     * @return boolean
     */
    function checkIfSubjectPermExists($permalink){
		$query = "SELECT * FROM sch_subjects WHERE code = ? ";		
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("s", $permalink);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }
	
	/**
     * Check if student results exists
     * @return id
     */
    function studentResultExists($student_id, $sch_id, $reg_no, $year, $term)
	{
		$query = "SELECT id FROM sch_results WHERE year = ? AND term = ? AND (student_id = ? OR (sch_id = ? AND reg_no = ?)) ";		
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("iiiis", $year, $term, $student_id, $sch_id, $reg_no);
        $stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($id);
		$stmt->fetch();
        $stmt->close();
        return $id;
    }
	
	/**
     * Check if student fees exists
     * @return id
     */
    function studentFeeExists($student_id, $sch_id, $reg_no, $year)
	{
		$query = "SELECT id FROM sch_fees WHERE year = ? AND (student_id = ? OR (sch_id = ? AND reg_no = ?)) ";		
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("iiis", $year, $student_id, $sch_id, $reg_no);
        $stmt->execute();
		$stmt->bind_result($id);
		$stmt->fetch();
        $stmt->close();
        return $id;
    }
	
	/**
     * Check if student result item exists
     * @return boolean
     */
    function studentResultItemExists($result_id, $subject)
	{
		$query = "SELECT * FROM sch_results_items WHERE result_id = ? AND subject_code = ? ";		
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("is", $result_id, $subject);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }
	
	/**
     * update result summaries
     */
    function updateResultSummaryData($result_id, $sch_id)
	{
		//get totals sum from sch_result_items table
		$sch_level = $this->getSchoolLevel($sch_id);
		$totals = $this->getResultTotals($result_id);
		$average = $this->getResultAverage($result_id);
		$points = $this->getResultPoints($result_id);
		$grade = $this->getResultGrade($points, $sch_level);
		
		//update the new totals
		$query = "UPDATE sch_results SET mean_score = ?, total_score = ?, grade = ?, points = ? WHERE id = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("iisii", $average, $totals, $grade, $points, $result_id);
        $result = $stmt->execute();
        $stmt->store_result();
		$stmt->close();
			
        // Check for successful insertion
		if ($result) {
			$response['error'] = false;
			$response['message'] = "Successfully updated summaries";			
		} else {
			// Failed
			$response['error'] = true;
			$response['message'] = "Failed to update summaries";
		}
			
		return $response;
		
    }
	
	/**
     * update fee summaries
     */
    function updateFeeSummaryData($fees_id)
	{
		$creator_id = USER_ID;
		$current_date = $this->getCurrentDate();
		
		//get totals sum from sch_result_items table
		$required_fees = $this->getRequiredFees($fees_id);
		$total_fees_paid = $this->getFeesTotals($fees_id);
		$fees_bal = $required_fees - $total_fees_paid;
		//update the new totals
		$query = "UPDATE sch_fees SET fees_paid = ?, fees_bal = ?, updated_at = ?, updated_by = ? WHERE id = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("iisii", $total_fees_paid, $fees_bal, $current_date, $creator_id, $fees_id);
        $result = $stmt->execute();
        $stmt->store_result();
		$stmt->close();
			
        // Check for successful insertion
		if ($result) {
			$response['error'] = false;
			$response['message'] = "Successfully updated summaries";			
		} else {
			// Failed
			$response['error'] = true;
			$response['message'] = "Failed to update summaries";
		}
			
		return $response;
		
    }
	
	function getSchoolLevel($sch_id)
	{
		//get totals sum from sch_result_items table
		$query = "SELECT sch_level FROM sch_ussd WHERE sch_id = ?";		
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i", $sch_id);
        $stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($sch_level);
		$stmt->fetch();
        $stmt->close();
        return $sch_level;
    }
	
	//calculate fees totals
	function getFeesTotals($fees_id)
	{
		//get totals sum from sch_result_items table
		$query = "SELECT SUM(amount) as totals FROM sch_fees_payments WHERE fees_id = ?"; 
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i", $fees_id);
        $stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($totals);
		$stmt->fetch();
        $stmt->close();
        return $totals;
    }
	
	//get required fees
	function getRequiredFees($fees_id)
	{
		//get totals sum from sch_result_items table
		$query = "SELECT total_fees FROM sch_fees WHERE id = ?";		
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i", $fees_id);
        $stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($total_fees);
		$stmt->fetch();
        $stmt->close();
        return $total_fees;
    }
	
	function addFeesPaid($amount, $fees_id)
	{
		$creator_id = USER_ID;
		$current_date = $this->getCurrentDate();
		
		$queryUpdate  = " UPDATE sch_fees SET fees_paid = fees_paid + $amount, updated_at = ?, updated_by = ? ";
		$queryUpdate .= " WHERE id = ? ";
		$stmtUpdate = $this->conn->prepare($queryUpdate);
		$stmtUpdate->bind_param("sii", $current_date, $creator_id, $fees_id);
		$stmtUpdate->execute();	
		$stmt->store_result();		
		$stmtUpdate->close();
	}
	
	function updateFeesBalance($fees_id)
	{
		$creator_id = USER_ID;
		$current_date = $this->getCurrentDate();
		
		$queryUpdate  = " UPDATE sch_fees SET fees_bal = total_fees - fees_paid, updated_at = ?, updated_by = ? ";
		$queryUpdate .= " WHERE id = ? ";
		$stmtUpdate = $this->conn->prepare($queryUpdate);
		$stmtUpdate->bind_param("sii", $current_date, $creator_id, $fees_id);
		$stmtUpdate->execute();			
		$stmtUpdate->close();
	}
	
	function getResultTotals($result_id)
	{
		//get totals sum from sch_result_items table
		$query = "SELECT SUM(score) as totals FROM sch_results_items WHERE result_id = ?";		
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i", $result_id);
        $stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($totals);
		$stmt->fetch();
        $stmt->close();
        return $totals;
    }
	
	function getResultAverage($result_id)
	{
		//get totals sum from sch_result_items table
		$query = "SELECT SUM(score)/ COUNT(score) as avg FROM sch_results_items WHERE result_id = ?";		
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i", $result_id);
        $stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($avg);
		$stmt->fetch();
        $stmt->close();
        return $avg;
    }
	
	function getResultPoints($result_id)
	{
		//get totals sum from sch_result_items table
		$query = "SELECT SUM(points) as total FROM sch_results_items WHERE result_id = ?";		
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i", $result_id);
        $stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($total);
		$stmt->fetch();
        $stmt->close();
        return $total;
    }
	
	function getFeeId($fee_payment_id)
	{
		//get totals sum from sch_result_items table
		$query = "SELECT fees_id FROM sch_fees_payments WHERE id = ?";		
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i", $fee_payment_id);
        $stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($fees_id);
		$stmt->fetch();
        $stmt->close();
        return $fees_id;
    }
	
	function getResultId($sch_result_item_id)
	{
		//get totals sum from sch_result_items table
		$query = "SELECT result_id FROM sch_results_items WHERE id = ?";		
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i", $sch_result_item_id);
        $stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($result_id);
		$stmt->fetch();
        $stmt->close();
        return $result_id;
    }
	
	function getSubjectPoints($score, $sch_level)
	{
		//get totals sum from sch_result_items table
		$query = "SELECT points FROM score_grades WHERE (min <= ? AND max >= ?) AND sch_level = ?";		
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("iii", $score, $score, $sch_level);
        $stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($points);
		$stmt->fetch();
        $stmt->close();
        return $points;
    }
	
	function getResultGrade($average, $sch_level)
	{
		//get totals sum from sch_result_items table
		$query = "SELECT grade FROM total_points_grades WHERE (min <= ? AND max >= ?) AND sch_level = ?";	
		//echo "$query - $average, $average, $sch_level";	
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("iii", $average, $average, $sch_level);
        $stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($grade);
		$stmt->fetch();
        $stmt->close();
        return $grade;
    }
	
	function getSubjectGrade($average, $sch_level)
	{
		//get totals sum from sch_result_items table
		$query = "SELECT grade FROM score_grades WHERE (min <= ? AND max >= ?) AND sch_level = ?";	
		//echo "$query - $average, $average, $sch_level";	
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("iii", $average, $average, $sch_level);
        $stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($grade);
		$stmt->fetch();
        $stmt->close();
        return $grade;
    }
	
	//get subject name from code or id
	function getSubjectName($code, $subject_id)
	{
		//get totals sum from sch_result_items table
		$query = "SELECT name FROM sch_subjects WHERE code = ? OR id = ?";	
		if ($stmt = $this->conn->prepare($query)){
			$stmt->bind_param("si", $code, $subject_id);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($name);
			$stmt->fetch();
			$stmt->close();
			
			return $name;
		} else {
			//$response["query"] = $query;
			$response["error"] = true;
			$response["message"] = $this->conn->error;
			$response['error_type'] = AN_ERROR_OCCURED_ERROR;
			return $response;
			
		}
        
    }
	
	//create fee payment archive data	
	function saveFeeItemArchiveData($fee_payment_id)
	{
		
		$updated_at = $this->getCurrentDate(); // get current date
		$updated_by = USER_ID; // get logged in user id
					
		//STORE ARCHIVE DATA
		//get fee items details
		$querySelect = "SELECT fees_id, amount, payment_mode, paid_at, paid_by FROM sch_fees_payments WHERE id = ? ";		
		$stmtSelect = $this->conn->prepare($querySelect);
		$stmtSelect->bind_param("i", $fee_payment_id);
		$stmtSelect->execute();
		$stmt->store_result();
		$stmtSelect->bind_result($fees_id, $amount, $payment_mode, $paid_at, $paid_by);
		$stmtSelect->fetch();
		$stmtSelect->close();
		
		//store data in history table
		
		$dateparts = explode("/", $paid_at);
		$new_paid_at = $dateparts[2]."-".$dateparts[1]."-".$dateparts[0]." 00:00:00";
			
		$query  = "INSERT INTO sch_fees_payments_history(fees_payments_id, amount, payment_mode, paid_at, paid_by";
		$query .= ", created_at, created_by) VALUES (?, ?, ?, ?, ?, ?, ?) ";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("iissssi", $fee_payment_id, $amount, $payment_mode, $new_paid_at, $paid_by, $updated_at, $updated_by);
		$result = $stmt->execute();
		$stmt->store_result();
		$stmt->close();
		//END ARCHIVE DATA
	}
	
	//create result archive data
	function saveResultItemArchiveData($result_item_id)
	{
		
		$updated_at = $this->getCurrentDate(); // get current date
		$updated_by = USER_ID; // get logged in user id
					
		//STORE ARCHIVE DATA
		//get result items details
		$querySelect = "SELECT result_id, subject_code, score, grade, points FROM sch_results_items WHERE id = ? ";		
		$stmtSelect = $this->conn->prepare($querySelect);
		$stmtSelect->bind_param("i", $result_item_id);
		$stmtSelect->execute();
		$stmt->store_result();
		$stmtSelect->bind_result($result_id, $subject_code, $score, $grade, $points);
		$stmtSelect->fetch();
		$stmtSelect->close();
		
		//store data in history table
		$query  = "INSERT INTO sch_results_items_history(results_items_id, result_id, subject_code, score, grade";
		$query .= ", points, created_at, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?) ";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("iisdsisi", $result_item_id, $result_id, $subject_code, $score, $grade, $points, $updated_at, $updated_by);
		$result = $stmt->execute();
		$stmt->store_result();
		$stmt->close();
		//END ARCHIVE DATA
	}
	
	function validateEmail($email) {

		return preg_match("/^(((([^]<>()[\.,;:@\" ]|(\\\[\\x00-\\x7F]))\\.?)+)|(\"((\\\[\\x00-\\x7F])|[^\\x0D\\x0A\"\\\])+\"))@((([[:alnum:]]([[:alnum:]]|-)*[[:alnum:]]))(\\.([[:alnum:]]([[:alnum:]]|-)*[[:alnum:]]))*|(#[[:digit:]]+)|(\\[([[:digit:]]{1,3}(\\.[[:digit:]]{1,3}){3})]))$/", $email);
	
	}

	function isDataNumeric($num){
		
		//return ctype_digit($num) && (int) $num > 0;
		if (is_numeric($num)){ return true; } else { return false; }
	
	}
	
	function generate_seo_link($input,$replace = '-',$remove_words = true,$words_array = array())
	{
	
		  //make it lowercase, remove punctuation, remove multiple/leading/ending spaces
		  $return = trim(ereg_replace(' +',' ',preg_replace('/[^a-zA-Z0-9\s]/','',strtolower($input))));
		  //remove words, if not helpful to seo
		  //i like my defaults list in remove_words(), so I wont pass that array
		  if($remove_words) { $return = $this->remove_words($return,$replace,$words_array); }
		  return str_replace(' ',$replace,$return);
	
	}
	
	/* takes an input, scrubs unnecessary words */
	function remove_words($input,$replace,$words_array = array(),$unique_words = true)
	{
		  
		  //separate all words based on spaces
		  $input_array = explode(' ',$input);
		  //create the return array
		  $return = array();
		  //loops through words, remove bad words, keep good ones
		  foreach($input_array as $word)
		  {
			//if it's a word we should add...
			if(!in_array($word,$words_array) && ($unique_words ? !in_array($word,$return) : true))
			{
			  $return[] = $word;
			}
		  }
		  //return good words separated by dashes
		  return implode($replace,$return);
	
	}
	
	//generate dates
	function generate_options($from,$to,$callback=false)
	{
	    $reverse=false;

	    if($from>$to)
	    {
	        $tmp=$from;
	        $from=$to;
	        $to=$tmp;
	        $reverse=true;
	    }
	    $return_string=array();
	    for($i=$from;$i<=$to;$i++)
	    {
	        $return_string[]='
	        <option value="'.$i.'">'.($callback?$callback($i):$i).'</option>';
	    }

	    if($reverse)
	    {
	        $return_string=array_reverse($return_string);
	    }

	    return join('',$return_string);
	}

	function callback_month($month)
	{
	    return date('M',mktime(0,0,0,$month,1));
	}

	/* usage:
	generate_options(1,31);             // generate days
	generate_options(date('Y'),1900);           // generate years, in reverse
	generate_options(1,12,'callback_month');        // generate months
	*/
	
	//upload and resize image
	//resizeUpload($field, $pic_dir, $name_dir, NULL, NULL, $max_width, $max_height)
	public function resizeUpload($field,$pic_dir,$name_dir,$max_width,$max_height,$cropratio=NULL,$watermark=NULL,$add_to_filename=NULL){
			global $font_path, $font_size, $water_mark_text_1, $water_mark_text;
			$maxwidth = $max_width; // Max new width or height, can not exceed this value.
			$maxheight = $max_height;
			$dir = $pic_dir; // Directory to save resized image. (Include a trailing slash - /)
			// Collect the post variables.
			$postvars = array(
				"image"    => trim($_FILES["$field"]["name"]),
				"image_tmp"    => $_FILES["$field"]["tmp_name"],
				"image_size"    => (int)$_FILES["$field"]["size"],
				);
				// Array of valid extensions.
				$valid_exts = array("jpg","jpeg","gif","png");
				$mod_exts = array("gif","png");
				// Select the extension from the file.
				$ext = end(explode(".",strtolower(trim($_FILES["$field"]["name"]))));
				//echo ("Image size: " . $postvars["image_size"] . "<br> Ext: " . $ext . "<br>");
				// Check is valid extension.
				if(in_array($ext,$valid_exts)){
					if($ext == "jpg" || $ext == "jpeg"){
						$image = imagecreatefromjpeg($postvars["image_tmp"]);
					}
					else if($ext == "gif"){
						$image = imagecreatefromgif($postvars["image_tmp"]);
					}
					else if($ext == "png"){
						$image = imagecreatefrompng($postvars["image_tmp"]);
					}
					// Grab the width and height of the image.
					list($width,$height) = getimagesize($postvars["image_tmp"]);
					// Ratio cropping
					$offsetX	= 0;
					$offsetY	= 0;
					if ($cropratio) {
							$cropRatio = explode(':', (string) $cropratio);
							$ratioComputed		= $width / $height;
							$cropRatioComputed	= (float) $cropRatio[0] / (float) $cropRatio[1];
							if ($ratioComputed < $cropRatioComputed)
	
							{ // Image is too tall so we will crop the top and bottom
	
								$origHeight	= $height;
								$height		= $width / $cropRatioComputed;
								$offsetY	= ($origHeight - $height) / 2;
								$smallestSide = $width;
	
							}
	
							else if ($ratioComputed > $cropRatioComputed)
							{ // Image is too wide so we will crop off the left and right sides
								$origWidth	= $width;
								$width		= $height * $cropRatioComputed;
								$offsetX	= ($origWidth - $width) / 2;
								$smallestSide = $height;
							}
					}
					// We get the other dimension by multiplying the quotient of the new width or height divided by
					// the old width or height.
				   $w_adjust = ($maxwidth / $width);
				   $h_adjust = ($maxheight / $height);
				   if (($width >= $maxwidth)||($height >= $maxheight)) {
					   if($w_adjust <= $h_adjust)
					   {
						   $newwidth=floor($width*$w_adjust);
						   $newheight=floor($height*$w_adjust);
					   } else {
						   $newwidth=floor($width*$h_adjust);
						   $newheight=floor($height*$h_adjust);
					   }
	
				   } else {
						$newwidth=$width;
						$newheight=$height;
				   }
					// Create temporary image file.
					$tmp = imagecreatetruecolor($newwidth,$newheight);
					
					// Copy the image to one with the new width and height.
	
						imagecopyresampled($tmp,$image,0,0,$offsetX,$offsetY,$newwidth,$newheight,$width,$height);
						// Create random 5 digit number for filename. Add to current timestamp.
						$rand = rand(10000,99999);
						$rand .= time();
	
						$origfilename = $name_dir.$rand ;
						if ($add_to_filename){ $origfilename .= "_".$add_to_filename; }
						$origfilename .= ".jpg";
	
						$filename = $dir.$rand;
						if ($add_to_filename){ $filename .= "_".$add_to_filename; }
						$filename .= ".jpg";
	
					if ($watermark) {
	
						//Apply watermark here
						$maroon = imagecolorallocate($tmp, 134, 22, 0);
						$white = imagecolorallocate($tmp, 255, 255, 255);
						/*$base_height = $newheight-20;
						$base_width = $newwidth/5;*/
						//$borderOffset = 4;
						$dimensions = imagettfbbox($font_size, 0, $font_path, $water_mark_text);
						$lineWidth = ($dimensions[2] - $dimensions[0]);
						$textX = (ImageSx($tmp) - $lineWidth) / 2;
						//$textY = $borderOffset - $dimensions[7];
						$textY = ($newheight/10)*9;
					   // Add some shadow to the text
						imagettftext($tmp, $font_size, 0,  $textX+1,$textY+1,  $white, $font_path, $water_mark_text);
						imagettftext($tmp, $font_size, 0, $textX, $textY,  $maroon, $font_path, $water_mark_text);
	
					}
	
					// Create image file with 80% quality.
					imagejpeg($tmp,$filename,90);
					return $origfilename;
					/*echo "<strong>Image Preview:</strong><br/>
					<img src=\"".$filename."\" border=\"0\" title=\"Resized  Image Preview\" style=\"padding: 4px 0px 4px 0px;background-color:#e0e0e0\" /><br/>
					Resized image successfully generated. <a href=\"".$filename."\" target=\"_blank\" name=\"Download your resized image now!\">Click here to download your image.</a>";*/
					imagedestroy($image);
					imagedestroy($tmp);
	
				}
	
		}
	 
	}
 
?>