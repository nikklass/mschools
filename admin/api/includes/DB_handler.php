<?php
	
class DbHandler {
 
    public $conn;	
 
    function __construct() {
		
        require_once dirname(__FILE__) . '/DBConnect2.php';
		require_once dirname(__FILE__) . '/Config.php';
		require_once dirname(dirname(__FILE__)) . '/libs/Curl/curl.php';	
		
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
			
			$response["error"] = true;
			$response["noty_msg"] = true;
			$response["error_type"] = "not_activated";
			$response["message"] = "Account not activated Please activate";
			$response["user"] = $this->getUserDetails($phone_number);
        
		} else {
            
			// User exists in the db
            $response["error"] = false;
			$response["reload_page"] = true;
            $response["user"] = $this->getUserDetails($phone_number);
			$user_group_id = $response["user"]["user_group_id"];
			
			//admins array
			$admins_array = array(SUPER_ADMIN_USER_ID, SCHOOL_ADMIN_USER_ID);
			//if user is not an admin
			if (!in_array($user_group_id, $admins_array)) {
				///////////////////////////////////////////////// subscribe user to sms service ///////////////////////////////////////
				//echo "not admin"; exit;
				$this->sdp_que_sub_app($phone_number, "MDSP2000075075") ; 
			}
			
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
    public function createPassword($password, $password2, $sch_id, $user_id, $admin)
	{
        
		$success = 1;
		
		if (!$password || !$password2 || !$sch_id) {
			
			// Invalid phone number
			$response["error"] = true;
			$response["message"] = PLEASE_ENTER_REQUIRED_DATA_ERROR;
			$success = 0;
		
		}
			
		if ($success) {
					
			$response = array();
	 
			// First check if school acct exists in db
			if ($this->isUserExists($sch_id)) {
				
					$current_date = $this->getCurrentDate();
					
					//md5 password
					$password = md5($password);
					
					// update user password
					$query = "UPDATE clients SET password = '$password' WHERE id = $sch_id";
					
					if ($stmt = $this->conn->prepare($query)) {

						$result = $stmt->execute();
						$stmt->close();
			 
						// Check for successful update
						if ($result) {
							
							// password successfully changed
							$response["error"] = false;
							$response["noty_msg"] = true;
							//$response["close_form"] = true;
							$response["clear_form"] = true;
							$response["message"] = "Password has been set";                        
							
						} else {
							
							// Failed to create user
							$response["error"] = true;
							$response["message"] = AN_ERROR_OCCURED_MESSAGE;

						}
					} else {
			
						$response["error"] = true;
						$response["message"] = AN_ERROR_OCCURED_MESSAGE;
						$response['error_type'] = AN_ERROR_OCCURED_ERROR;
					
					}
				
			} else {
				// User with same phone number already existed in the db
				$response["error"] = true;
				$response["message"] = "School User Account does not exist. Try again.";
				$response["slide_form"] = true;
				$response["slide_duration"] = 12000;
			}
		
		}
 
        return $response;
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
    public function createUser($phone_number, $password, $email=NULL, $gender=NULL, $full_names=NULL, $user_group=NULL, $activate=FALSE) 
	{
        		
		$success = 1;
		//check if phone number is valid before proceeding
		if (!$this->isNumberValid($phone_number)) {
			// Invalid phone number
			$response["error"] = true;
			$response["message"] = INVALID_PHONE_NUMBER_ERROR_MESSAGE;
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
					$active_status = ACTIVE_STATUS;
					
					// insert query
					$query = "INSERT INTO clients(phone_number, password, created_at";
					if ($user_group) { $query .= ", user_group"; }
					if ($gender) { $query .= ", gender"; }
					if ($full_names) { $query .= ", full_names"; }
					if ($email) { $query .= ", email"; }
					if ($activate) { $query .= ", status, activated_at"; }
					$query .= ") values(";
					$query .= "'$phone_number', '$password', '$current_date'";
					if ($user_group) { $query .= ", $user_group"; }
					if ($gender) { $query .= ", '$gender'"; }
					if ($full_names) { $query .= ", '$full_names'"; }
					if ($email) { $query .= ", '$email'"; }
					if ($activate) { $query .= ", $active_status, '$current_date'"; }
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
						
                        if (!$activate) {
							///////////////////////////////////////////////// enable later ///////////////////////////////////////
							$this->sdp_que_sub_app($phone_number, "MDSP2000075075") ;                        
						}
                        
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
				//$response["user"] = $this->getUserDetails($phone_number);
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
	
	//breadcrumb
	function BreadCrumb(){
        $req_url = $_SERVER['REQUEST_URI'];$extd='';
        if(strstr($req_url,'?')){
            $req_url = substr($req_url, 0, stripos($req_url, "?"));
        }
        $bc = explode('/',$req_url);
        // we dont want to show the breadcrumb on the index page - lets filter
        if($bc[1]=='index.php' || $bc[1]==NULL)return false;
        // remove bad requests
        foreach($bc as $key => $value) {
            if($value == "" || $value == " " || is_null($value) || $value == "index.php") {
                unset($bc[$key]);
            }
        }
        $lastone = end($bc);
        $bread = array();
        // line below should be changed to the specific site
        $bread['http://'.$_SERVER["SERVER_NAME"].'/'] = 'Home';
        foreach($bc as $d){
            if($d!=NULL){
                $extd.=$d.'/';
                $bread['http://'.$_SERVER["SERVER_NAME"].'/'.$extd] = $d;
            }
        }	
		
		$j = '<ol class="breadcrumb">';

        foreach($bread as $ahref => $bread_display){
            $bread_final = ucwords(str_replace(array('-','.php', '.html'),array(' ',''),$bread_display));
			if(!($lastone==$bread_display)){
                $j .='<li><a href="'.$ahref.'">'.$bread_final.'</a></li>';
            } else {
                $j .= "<li class='active'>".$bread_final."</li>";
            }     
        }
        return $j.'</ol>';

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
	
	//delete image item
	public function deleteImage($image_id) {
		
		//get images and unlink them before deleting record
		$query = "SELECT thumb_img, full_img FROM images WHERE id = ? ";
		
		if ($stmt = $this->conn->prepare($query)) {
			
			$stmt->bind_param("i", $image_id);
			$stmt->execute();	
			$stmt->store_result();
			/* bind result variables */
			$stmt->bind_result($thumb_img, $full_img);	
			/* fetch value */
			$stmt->fetch();		
			$stmt->close();
			
			//unlink the images
			unlink("../../../".$thumb_img);
			unlink("../../../".$full_img);
			
			$query = "DELETE FROM images WHERE id = ? ";
			if ($stmt = $this->conn->prepare($query)) {
				$stmt->bind_param("i", $image_id);
				if ($stmt->execute()) {
					$response = array();
					$response["error"] = false;
					$response["noty_msg"] = true;
					$response["message"] = "Photo deleted successfully";
				} else {
					$response["error"] = true;
					$response["noty_msg"] = true;
					$response["message"] = "An error occured while deleting photo";
				}
				$stmt->close();
			}
		
		}
		
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
	
	
	/*$url = 'http://41.215.126.10:5333/sms/fxd1.php';

	$fields = array(
		'usr'      => "steve",
		'pass'      => "steve1",
		'src'    => "707200",
		'dest'      => $phone_number,
		'msg'      => $message
	);
	
	$result = $this->curl_get($url, $fields, "");*/
	
	/*//open connection
	$ch = curl_init();
	
	//set the url, number of POST vars, POST data
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, count($fields));
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
	
	//execute post
	$result = curl_exec($ch);
	
	//close connection
	curl_close($ch);*/
	
	//var_dump($result);
			
	// send bulk sms
    public function sendBulkSMS($usr, $pass, $src, $phone_number, $message, $sms_type_id = 1) {
				
		$user_agent = @$_SERVER["HTTP_USER_AGENT"]?$_SERVER["HTTP_USER_AGENT"]: "" ;
        $src_ip =  @$_SERVER["REMOTE_ADDR"]? $_SERVER["REMOTE_ADDR"] : "" ; 
        $src_host = @$_SERVER["REMOTE_HOST"]? $_SERVER["REMOTE_HOST"]: "" ; 
		
		$phone_number = $this->formatPhoneNumber($phone_number);
        
        $response = array();
 
		//$send_sms_link = "http://41.215.126.10:5333/sms/fxd1.php?usr=" . $usr . "&pass=" . $pass . "&src=" . $src . "&dest=" . $phone_number . "&msg=" . $message;
		
		$send_sms_link = SEND_BULK_SMS_URL;
		$fields = "usr=" . $usr . "&pass=" . $pass . "&src=" . $src . "&dest=" . $phone_number . "&msg=" . $message;
        $result = $this->executeLink($send_sms_link, $fields, "post");
		
		//$sent = true;
		
		//if ($sent) {
		
		// Check for successful insertion
		if  (($result->error==false) && ($result->mobile)) {
			
			//sms successfully sent
			//store sms info locally
			$current_date = $this->getCurrentDate(); // get current date
			// insert sms query
			$query = "INSERT INTO sms_codes(mobile, message, created_at, user_agent, src_ip, src_host, sms_type_id, sender) VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
			$stmt = $this->conn->prepare($query);
			$stmt->bind_param("ssssssis", $phone_number, $message, $current_date, $user_agent, $src_ip, $src_host, $sms_type_id, $usr); 			
			$result = $stmt->execute();
			$stmt->close(); //echo $query;
			// end store locally
		
			$sms_id = $this->conn->insert_id;
			$response["error"] = false;
			$response["message"] = "SMS sent successfully";
			$response["sms"] = $this->getSentSMS($sms_id);
			
		} else {
			// Failed to create sms
			$response["error"] = true;
			$response["message"] = $result->err_msg;
		}
 
        return $response;
		
    }
	
	
	function is_curl_installed() {
		if  (in_array  ('curl', get_loaded_extensions())) {
			return true;
		}
		else {
			return false;
		}
	}
		
	function executeRequest($url, $parameters = array(), $http_method = 'GET', array $http_headers = null, $form_content_type = 'multipart/form-data', $getResponseHeaders = false)
    {
        $certificate_file = null;
        $curl_options = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_CUSTOMREQUEST  => $http_method
        );

        if ($getResponseHeaders){
            $curl_options[CURLOPT_HEADER] = true;
        }

        switch($http_method) {
            case 'POST':
                $curl_options[CURLOPT_POST] = true;
                /* No break */
            case 'PUT':
            case 'PATCH':

                /**
                 * Passing an array to CURLOPT_POSTFIELDS will encode the data as multipart/form-data,
                 * while passing a URL-encoded string will encode the data as application/x-www-form-urlencoded.
                 * http://php.net/manual/en/function.curl-setopt.php
                 */
                if(is_array($parameters) && 'application/x-www-form-urlencoded' === $form_content_type) {
                    $parameters = http_build_query($parameters, null, '&');
                }
                $curl_options[CURLOPT_POSTFIELDS] = $parameters;
                break;
            case 'HEAD':
                $curl_options[CURLOPT_NOBODY] = true;
                /* No break */
            case 'DELETE':
            case 'GET':
                if (is_array($parameters)) {
                    $url .= '?' . http_build_query($parameters, null, '&');
                } elseif ($parameters) {
                    $url .= '?' . $parameters;
                }
                break;
            default:
                break;
        }

        $curl_options[CURLOPT_URL] = $url;

        if (is_array($http_headers)) {
            $header = array();
            foreach($http_headers as $key => $parsed_urlvalue) {
                $header[] = "$key: $parsed_urlvalue";
            }
            $curl_options[CURLOPT_HTTPHEADER] = $header;
        }

        $ch = curl_init();
        curl_setopt_array($ch, $curl_options);
        // https handling
        if (!empty($certificate_file)) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_CAINFO, $certificate_file);
        } else {
            // bypass ssl verification
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        }
        if (!empty($curl_options)) {
            curl_setopt_array($ch, $curl_options);
        }
        $result = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        if ($curl_error = curl_error($ch)) {
            throw new Exception($curl_error, null);
        } else {
            $json_decode = json_decode($result, true);
        }
        curl_close($ch);

        return array(
            'result' => (null === $json_decode) ? $result : $json_decode,
            'code' => $http_code,
            'content_type' => $content_type
        );
    }


	function getUserProfile($url){
	
		$parameters = array();
	
		$http_headers = array('Accept'=>'application/json',
							  'Content-Type'=>'application/x-www-form-urlencoded');
		$result = executeRequest($url, $parameters, 'GET', $http_headers, 0);
		return $result;
	}
	
	/*$url = $_GET['url'];
	
	$result = getUserProfile($url);
	
	echo $result['result'];*/
	
	//Example usage:
	//index.php?url=https://api.moves-app.com/api/1.1/user/profile?access_token=7hACUBaguM0UI497MrDKJlvYPHu5813EErwFM6UJ7wURsI2d8iLj1BZ0R7Hru2gH

	
	/**
	* Send a GET requst using cURL
	* @param string $url to request
	* @param array $get values to send
	* @param array $options for cURL
	* @return string
	*/
	function curl_get($url)
	{   
			
		$ch = curl_init();
	
		/*if (FALSE === $ch)
			throw new Exception('failed to initialize');*/
	
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 0);
		//curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 20);
		curl_setopt($curl, CURLOPT_FAILONERROR, TRUE);
		curl_setopt ($ch, CURLOPT_AUTOREFERER, true);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 2);
	
		$result = curl_exec($ch);
		
		if($result === false)
		{
			echo 'Curl error: ' . curl_error($ch);
		}
		else
		{
			return $result;
		}

	}
	
	//send school sms	
    public function sendSingleSchoolSMS($sch_id, $phone_number, $message, $user_id=NULL, $admin=NULL) {
				
		$response = array();
		
		$results = array();
		
		$message = urlencode($message);
		

		if (!$user_id) { $user_id = USER_ID; }
		
		//check user permissions
		$super_admin = $this->isSuperAdmin($user_id);
		if ($admin && !$super_admin) {
			$perms = ALL_BULK_SMS_PERMISSIONS; 
			$company_ids = $this->getUserCompanyIds($user_id, $perms, $sch_id); 
		}
		
		if ($super_admin || ($admin && $company_ids)) {
			
			//get sms data
			$bulk_sms_data = $this->getBulkSMSData($sch_id);
			$usr = $sch_id;
			$pass = $bulk_sms_data["passwd"];
			$src = $bulk_sms_data["default_source"];
			
			//$sch_first_name = "asperin";
			
			//SEND SMS 
			$resp = $this->sendBulkSMS($usr, $pass, $src, $phone_number, $message);
			
			if  (!$resp['error']) {
				
				$response['error'] = false;
				$response['noty_msg'] = true;
				$response['message'] = "Message sent successfully to <strong>$phone_number</strong>";
								
			} else {
				
				$response['error'] = true;
				$response['noty_msg'] = true;
				$response['message'] = "Message was not sent";
			
			}
		
		} else {
			
			//show error msg
			$response["message"] = NO_PERMISSION_ERROR_MESSAGE;
			$response["error_type"] = NO_PERMISSION_ERROR;
			$response["error"] = true;
			
		}
		
		return $response;
		
		if ($results) {
			
			// User successfully inserted
			$response["error"] = false;
			$response["noty_msg"] = true;
			$response["clear_form"] = true;
			$response["message"] = "SMS sent successfully";
			
		} else {
			
			// Failed to create sms
			$response["error"] = true;
			$response["noty_msg"] = true;
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
		
		$results = array();
 
		$current_date = $this->getCurrentDate(); // get current date
		// insert sms query
		//change to your table definition
		$query = "INSERT INTO sms_codes(mobile, message, created_at, user_agent, src_ip, src_host, sms_type_id) VALUES(?, ?, ?, ?, ?, ?, ?)";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("ssssssi", $phone_number, $message, $current_date, $user_agent, $src_ip ,$src_host, $sms_type_id); // number of params s - string, i - integer, d - decimal, etc
        
        $result = $stmt->execute();
		$stmt->close();
		
		//send sms via link		
		$message = urlencode($message);
		$send_sms_link = "http://41.215.126.10:5333/pendoschool_app/app_actions.php?tag=send_sms&mobile=" . $phone_number . "&msg=" . $message;
		//$send_sms_link = "http://localhost/pendoschool_app/app_actions.php?tag=send_sms&mobile=" . $phone_number . "&msg=" . $message;
        $results = $this->executeLink($send_sms_link);
		
		if ($results) {
			
			// User successfully inserted
			$response["error"] = false;
			$response["noty_msg"] = true;
			$response["clear_form"] = true;
			$response["message"] = "SMS sent successfully";
			
		} else {
			
			// Failed to create sms
			$response["error"] = true;
			$response["noty_msg"] = true;
			$response["message"] = "An error occurred in Sending SMS";
			
		}
 
        return $response;
		
    }
	
	function save_log($text)
	{  
			  
		$file = sprintf("/logs/curl_%s.log",date("Ymd"));
	  	  
	   	$tlog = sprintf("\n%s: %s",date("Y-m-d H:i:s T: "),$text);
	 
	  	$f = fopen($file, "a");
	  	fwrite($f,$tlog);
	  	fclose($f);
	}
	
	public function getAllSiteSettings($name=NULL)
	{
		
		$settings = array();
		
		$query = "SELECT name, text FROM site_settings WHERE name!='' ";
		if ($name) { $query .= " AND name = '$name' "; }
		$query .= " ORDER BY name";
		//echo "$query"; exit;
		$stmt = $this->conn->prepare($query);
		/* execute statement */
		$stmt->execute();	
		$stmt->store_result();
		/* bind result variables */
		$stmt->bind_result($field, $text);	
		/* fetch values */
		
		while ($stmt->fetch()) {
			$settings["$field"] = $text;
		}
		
		$response = json_encode($settings);
		
		return $response;
		
	}
	
	//get page contents
	public function getWebpage($url){
	  
	  $options = array(
		CURLOPT_RETURNTRANSFER => true,     // return web page
		CURLOPT_HEADER         => false,    // don't return headers
		CURLOPT_FOLLOWLOCATION => true,     // follow redirects
		CURLOPT_ENCODING       => "",       // handle all encodings
		CURLOPT_USERAGENT      => "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)", // who am i
		CURLOPT_AUTOREFERER    => true,     // set referer on redirect
		CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
		CURLOPT_TIMEOUT        => 120,      // timeout on response
		CURLOPT_MAXREDIRS      => 10     
	  );
	
	  $ch = curl_init($url);
	  curl_setopt_array($ch, $options);
	  $content = curl_exec($ch);
	  curl_close($ch);
	
	  return $content;
	  
	}
	
	//execute a link via curl
	public function executeLink($link, $fields=NULL, $method=NULL)
	{
		
		ob_start();  
		$out = fopen('php://output', 'w');

		$agent= 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
			
		//$agent= 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:50.0) Gecko/20100101 Firefox/50.0';
		
		$ch = curl_init();
		
		if ($fields) {
			
			switch (strtoupper($method)) {
				case 'HEAD':
					curl_setopt($ch, CURLOPT_NOBODY, true);
					break;
				case 'GET':
					curl_setopt($ch, CURLOPT_HTTPGET, true);
					break;
				case 'POST':
					curl_setopt($ch, CURLOPT_POST, true);
					break;
				default:
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
			}
			
			if (is_array($fields)) $fields = http_build_query($fields, '', '&');
			
			if (!empty($fields)) curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
			
		}
		
		curl_setopt($ch, CURLOPT_STDERR, $out);  
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL,$link);
		curl_setopt($ch, CURLOPT_USERAGENT, $agent);
		
		$result=curl_exec($ch);
		
		curl_close($ch);
		
		fclose($out);  
		$debug = ob_get_clean();
		//echo "debug - $debug";
		
		//save log
		$this->save_log($debug);		
		
		if ($result === FALSE) {
 
			$result = curl_error($ch);
			//echo "curl_error - " . json_decode($curl_error);
			//print_r($result);
			//print_r($curl_error);
		 
		}
        
        return  json_decode($result);   
		
	}
	
	//get sent sms
	public function getSentSMS($id=NULL, $mobile=NULL, $sms_type_id=NULL) {
		
		$query = "SELECT id, mobile, message, created_at FROM sms_codes WHERE id != '' ";
		if ($id) { $query .= " AND id = $id "; }
		if ($mobile) { $query .= " AND mobile = '$mobile' "; }
		if ($sms_type_id) { $query .= " AND sms_type_id = $sms_type_id "; } 
		$stmt = $this->conn->prepare($query);
        $stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($id, $mobile, $message, $created_at);
		$stmt->fetch();
		if ($mobile) {
            $sms = array();
			$sms["sms_id"] = $id;
			$sms["mobile"] = $mobile;
			$sms["message"] = $message;
            $sms["created_at"] = $this->adjustDate(NULL, $this->php_date($created_at));
            $stmt->close();
            return $sms;
        } else {
            return NULL;
        }
	
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
		
		$start_at = $this->reformatDateTime($start_at);
		$end_at = $this->reformatDateTime($end_at);
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
			$response["reload_grid"] = true;
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
    public function createStudentFee($amount, $payment_mode, $paid_by, $paid_at, $student_id, $year, $total_fees=NULL, $sch_id=NULL, $reg_no=NULL, $ref_no=NULL) {
        				
		$response = array();
		
		$status = CONFIRMED_STATUS;
		
		if (strtolower($payment_mode) == "mpesa") {
			$status = PENDING_STATUS;
		}
		
		//get student_id
		$student_details = $this->getStudentData($reg_no, $sch_id, "", "", $student_id);
		$student_id = $student_details["student_id"];
		
		if ($student_id && $year && (($amount || $paid_at || $payment_mode || $paid_by) || ($total_fees))) {
				
			$payment_mode = strtolower($payment_mode);
			
			if ($amount && (!$this->isDataNumeric($amount))){
				
				$response['message'] = INVALID_AMOUNT_NUMBER_ERROR_MESSAGE;
				$response['error'] = true;	
				$response["noty_msg"] = true;
				
			} else if (($payment_mode != "mpesa") && ($payment_mode != "cash") && ($payment_mode != "cheque")){
				
				$response['message'] = INVALID_PAYMENT_MODE_ERROR_MESSAGE;
				$response['error'] = true;	
				$response["noty_msg"] = true;
				
			} else {
	
				$current_date = $this->getCurrentDate();
				
				//get user id
				$user_id = USER_ID;
				
				//if not user id, set sch_id
				if (!$user_id) { $user_id = $sch_id; }
				
				$paid_at = $this->formatDate($paid_at);
				
				$fees_id = $this->studentFeeExists($student_id, $sch_id, $reg_no, $year);
				
				//if total fees exists and totals exist, update totals
				if ($total_fees && $fees_id) {			
				
					//update total fees
					$fees_id = $this->updateStudentFeeMain($fees_id, $sch_id, $reg_no, $year, $total_fees);
				
				} 
				
				//check if fee exists for this student in this period
				if (!$fees_id) {
					
					//create new fee
					$fees_id = $this->createNewStudentFee($student_id, $sch_id, $reg_no, $year, $total_fees);
					
				}  
				
								
				//if amount is entered
				if ($fees_id && $amount)
				{
					
					// insert query				
					$query = "INSERT INTO sch_fees_payments(fees_id, amount, payment_mode, status, paid_by, paid_at, created_by, created_at ";
					if ($ref_no) { $query .= ", ref_no "; }
					$query .= ") VALUES(?, ?, ?, ?, ?, ?, ?, ?"; 
					if ($ref_no) { $query .= ", '$ref_no' "; }
					$query .= ")"; 
					
					if ($stmt = $this->conn->prepare($query)) {
						
						//echo "$query - $fees_id, $amount, $payment_mode, $paid_by, $paid_at, $user_id, $current_date"; exit;
						$stmt->bind_param("iisissis", $fees_id, $amount, $payment_mode, $status, $paid_by, $paid_at, $user_id, $current_date);
						$result = $stmt->execute();
						$stmt->store_result();
						$stmt->close();
						
						// Check for successful insertion
						if ($result) {
							
							$new_id = $this->conn->insert_id;
							
							//create fee entry history
							$this->saveStudentFeeHistory($new_id, $user_id, $current_date);
							
							$response["error"] = false;
							$response["message"] = "Fee Successfully Added";
							$response["reg_no"] = $reg_no;
							$response["clear_form"] = true;
							$response["noty_msg"] = true;
							$response["reload_grid"] = true;
							//$response["select_id"] = $new_id;	
									
						} else {
							
							// Failed to create record						
							$response["error"] = true;
							$response["reg_no"] = $reg_no;
							$response["noty_msg"] = true;
							$response["error_type"] = ERROR_OCCURED;
							$response["message"] = "An error occurred whle inserting record";
							
						}
						
					} else {
					
						// Failed to create record						
						$response["error"] = true;
						$response["error_type"] = ERROR_OCCURED;
						$response["message"] = $this->conn->error;
						
					}
					
				}
				
				if ($fees_id) {
					//update fees summary data
					$res = $this->updateFeeSummaryData($fees_id);
					//end update fees summary data
					//$response["res"] = $res;
				}
			
			}
		
		} else {
			
			$response["message"] = PLEASE_ENTER_REQUIRED_DATA_ERROR;
			$response["error"] = true;
			$response["noty_msg"] = true;
				
		}
 
        return $response;
    }
	
	
	//create new student fees
	public function createNewStudentFee($student_id, $sch_id, $reg_no, $year, $total_fees=NULL) {
		$response = array();
		$current_date = $this->getCurrentDate();
		$created_by = USER_ID;
		
		$student_details = $this->getStudentData($reg_no, $sch_id, "", "", $student_id);
		$student_id = $student_details["student_id"];
		$sch_id = $student_details["sch_id"];
		$reg_no = $student_details["reg_no"];
	
		// insert query
		$query = "INSERT INTO sch_fees(year, student_id, sch_id, reg_no, created_at, created_by, total_fees) ";
		$query .= " VALUES(?, ?, ?, ?, ?, ?, ?)";
		$stmt = $this->conn->prepare($query); 
		//echo "$query - $year, $student_id, $sch_id, $reg_no, $current_date, $created_by, $total_fees"; exit;
		$stmt->bind_param("iiissid", $year, $student_id, $sch_id, $reg_no, $current_date, $created_by, $total_fees);
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
	
	//create a new student result
    public function createStudentResult($student_id, $sch_id, $reg_no, $year, $term, $subject, $score, $class=NULL) {
        								
		$response = array();
		
		if (!$score || !$sch_id || !($student_id)) {
		
			// Result already exists
			$response["error"] = true;
			$response["noty_msg"] = true;
			$response["message"] = PLEASE_ENTER_REQUIRED_DATA_ERROR;
			
		} else if ($score > 100) {
		
			// Result already exists
			$response["error"] = true;
			$response["noty_msg"] = true;
			$response["message"] = "Subject score cannot be more than 100";
			
		} else {

			$current_date = $this->getCurrentDate();
			$user_id = USER_ID;
			
			$result_id = $this->studentResultExists($student_id, $sch_id, $reg_no, $year, $term);
			
			//get full student data
			$student_data = $this->getStudentData($reg_no, $sch_id, "", "", $student_id);
			$student_id = $student_data["student_id"];
			$reg_no = $student_data["reg_no"];
			$sch_id = $student_data["sch_id"];
			$class = $student_data["current_class"];
			
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
						$response["reload_grid"] = true;
						$response["reload_grid_history"] = true;
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
	
	//get schools
	function getSchools($id=NULL, $page=NULL, $limit=NULL, $sort=NULL, $search_text=NULL, $company_ids = NULL, $admin=NULL, $user_id=NULL, $show_all=NULL){
		
		$response = array();
		$result = array();
		
		$sortqry = "";
		//start sort
		//start sort
		if ($sort['id'] == "asc"){
			$sortqry = " c.id ";
		} else if ($sort['id'] == "desc") {
			$sortqry = " c.id DESC ";
		} else if ($sort['name'] == "asc") {
			$sortqry = " c.names ";
		} else if ($sort['name'] == "desc") {
			$sortqry = " c.names DESC ";
		} else if ($sort['phone1'] == "asc") {
			$sortqry = " phone1 ";
		} else if ($sort['phone1'] == "desc") {
			$sortqry = " phone1 DESC ";
		} else if ($sort['status_name'] == "asc") {
			$sortqry = " s.name ";
		} else if ($sort['status_name'] == "desc") {
			$sortqry = " s.name DESC "; 
		} 
		
		//check if the user is a super admin
		//check if user is school admin, if so check school admin perms
		//check if user is neither of the two, if so get the perms
		
		$perms = ALL_SCHOOL_PERMISSIONS;
		
		if ($admin && !SUPER_ADMIN_USER) {
			//check if user is school admin
			$company_ids = $this->getUserCompanyIds($user_id, $perms);	
		} 
		
		//echo "company_ids - $company_ids"; exit;
		
		if (SUPER_ADMIN_USER || $company_ids || !$admin) {
			
			if (!$page){ $page=1; }
			if (!$limit) { $limit = 10; } //default num records
			$offset = ($page - 1) * $limit;
	
			if ($search_text) {
				$search_text = strtolower(trim($search_text));
				$search_text = $this->clean($search_text);
				$split_text = explode(" ",$search_text);
				$num_items = count($split_text);
				$full_article_search_text = "";
				for ($i=0;$i<$num_items;$i++) {
					$split_text[$i] = trim($split_text[$i]);
					$full_article_search_text .= " su.sch_name LIKE '%" . $split_text[$i] . "%' or cat.name LIKE '%" . $split_text[$i] . "%' or";
				}
				//more than one search term i.e. spaces in between
				if ($num_items > 1){ 
					$full_article_search_text .= " su.sch_name LIKE '%" . $search_text . "%' or cat.name LIKE '%" . $search_text . "%' or"; 
				} 
				//end more than one search term i.e. spaces in between
				$full_article_search_text = $this->removelastor($full_article_search_text);
			}				
			
			//get the data
			$query = "SELECT su.sch_id, su.sch_level, l.name, su.sch_name, su.sch_paybill_no, su.address, su.extra, su.sch_profile, su.phone1, su.phone2";
			$query .= ", su.motto, su.status, s.name, su.sch_category, cat.name, su.sch_province, p.name, su.sch_county, ct.name, su.permalink, su.created_by ";
			$query .= ", su.created_at, su.updated_by, su.updated_at ";
			$query .= " FROM sch_ussd su ";
			$query .= " LEFT JOIN status s ON su.status=s.id ";
			$query .= " LEFT JOIN counties ct ON su.sch_county=ct.id ";
			$query .= " LEFT JOIN sch_categories cat ON su.sch_category=cat.id ";
			$query .= " LEFT JOIN sch_levels l ON su.sch_level=l.id ";
			$query .= " LEFT JOIN provinces p ON su.sch_province=p.id ";
			$query .= " WHERE su.sch_name!='' ";
			if ($id) { $query .= " AND su.sch_id = $id "; }
			if ($company_ids) { $query .= " AND su.sch_id IN ($company_ids) "; }
			if ($search_text) { $query .= " AND ($full_article_search_text) "; } 
			//echo "query - $query"; exit;
			
			//total records
			$stmtMain = $this->conn->prepare($query);
			$stmtMain->execute();
			$stmtMain->store_result();
			$stmtMain->fetch();
			$total_recs = $stmtMain->num_rows;
			$stmtMain->close();
			//end total records
			
			//filtered recordset
			if ($sortqry) { $query .= " ORDER BY $sortqry "; } else { $query .= " ORDER BY su.sch_name "; }//add sort query 
			if (!$show_all) { $query .= " LIMIT $offset,$limit"; } //echo "queryadd - $queryadd";
			//echo "query - $query"; exit;		
					
			if ($stmt = $this->conn->prepare($query)) {
				
				$stmt->execute();
				$stmt->store_result();
				$filtered_total_recs = $stmt->num_rows;
				/* bind result variables */
				$stmt->bind_result($id, $sch_level, $level_name, $sch_name, $sch_paybill_no, $address, $extra, $sch_profile, $phone1, $phone2, $motto, $status, $status_name, $sch_category, $category_name, $sch_province, $province_name, $sch_county, $county_name, $permalink, $created_by, $created_at, $updated_by, $updated_at);
				
				$photo_text = SCHOOL_PROFILE_PHOTO;
				
				while ($stmt->fetch()) 
				{
		  
					if ($created_at){ $created_at_edit = date("d/m/Y", $this->php_date($created_at)); }
					if ($updated_at){ $updated_at_edit = date("d/m/Y", $this->php_date($updated_at)); }
					
					$tmp = array();
					$tmp["sch_id"] = $id;
					$tmp["id"] = $id;
					$tmp["name"] = $sch_name;
					$tmp["sch_level"] = $sch_level;
					$tmp["level_name"] = $level_name;
					$tmp["sch_paybill_no"] = $sch_paybill_no;
					$tmp["address"] = $address;
					$tmp["extra"] = $extra;
					$tmp["sch_profile"] = $sch_profile;
					$tmp["phone1"] = $phone1;
					$tmp["phone2"] = $phone2;
					$tmp["motto"] = $motto;
					$tmp["status"] = $status;
					$tmp["status_name"] = $status_name;
					$tmp["sch_category"] = $sch_category;
					$tmp["category_name"] = $category_name;
					$tmp["sch_province"] = $sch_province;
					$tmp["province_name"] = $province_name;
					$tmp["sch_county"] = $sch_county;
					$tmp["county_name"] = $county_name;
					$tmp["created_at"] = $created_at;
					$tmp["created_at_edit"] = $created_at_edit;
					$tmp["updated_at"] = $updated_at;
					$tmp["updated_at_edit"] = $updated_at_edit;
					$tmp["permalink"] = $permalink;
					$tmp["image"] = $this->getPhoto($photo_text, $id);
					$tmp["url"] = SITEPATH . "$link_text/" . $permalink;
						
					array_push($result, $tmp);		
			
				 }
				 
				 $response['rows'] = $result;
				 $response['total'] = $total_recs;
				 $response['rowCount'] = $limit;
				 $response['current'] = $page;
				 $response["error"] = false;	
			 
			} else {
				//$response["query"] = $query;
				$response["message"] = $this->conn->error;
				$response["error_type"] = AN_ERROR_OCCURED_ERROR;
				$response["error"] = true;	
			}
		
		
		} else {
			//$response["query"] = $query;
			$response['rows'] = "";
			$response['total'] = 0;
			$response['rowCount'] = 0;
			//$response["message"] = "Invalid request";
			//$response["error_type"] = INVALID_ACCESS_ERROR;
			$response["error"] = true;	
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
							
			//sent/ uploaded data
			$reg_no = $fees_array[$i]["reg_no"];
			$year = $fees_array[$i]["year"];
			$amount = $fees_array[$i]["amount_paid"];
			$payment_mode = $fees_array[$i]["payment_mode"];
			$paid_by = $fees_array[$i]["paid_by"];
			$paid_at = $fees_array[$i]["paid_at"];
			$total_fees = $fees_array[$i]["total_fees"];
			
			//student data
			$student_details = $this->getStudentData($reg_no, $sch_id, "", "", "");
			$student_id = $student_details["student_id"]; 
			$full_names = $student_details["student_full_names"]; 
			
			//echo $amount;exit;
			if ($student_details["error"]) {
				//no student with these details exist
				$tmp['error'] = true;
				$tmp['message'] = $student_details["message"];
				
			} else {
								
				//echo "amount - $amount --- reg - $reg_no --- full - $full_names --- paid_at - $paid_at --- total_fees - $total_fees --- paid_by - $paid_by --- payment_mode - $payment_mode"; exit;
				
				//save each fee item to db			
				$tmp = $this->createStudentFee($amount, $payment_mode, $paid_by, $paid_at, $student_id, $year, $total_fees);
			
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
		$response["reload_grid"] = true;
		$response["popup"] = true;
		$response["insert_results"] = $insert_results;
 
        return $response;
    }
	
	//GET PHOTO CAT NAME
	public function getPhotoCatName($category){
		
		if ($category == STUDENT_PROFILE_PHOTO) {
			$photo_cat_name = STUDENT_PROFILE_PHOTO;
		}
		if ($category == USER_PROFILE_PHOTO) {
			$photo_cat_name = USER_PROFILE_PHOTO;
		}
		if ($category == STUDENT_OTHER_PHOTO) {
			$photo_cat_name = STUDENT_OTHER_PHOTO;
		}
		if ($category == USER_OTHER_PHOTO) {
			$photo_cat_name = USER_OTHER_PHOTO;
		}
		if ($category == SCHOOL_PROFILE_PHOTO) {
			$photo_cat_name = SCHOOL_PROFILE_PHOTO;
		}
		if ($category == SCHOOL_OTHER_PHOTO) {
			$photo_cat_name = SCHOOL_OTHER_PHOTO;
		}

		return $photo_cat_name;
		
	}
	
	public function getFileUploadDir($category, $image_size_path=FULL_IMAGE, $type_of_data="upload_path"){
		
		if ($category == STUDENT_PROFILE_PHOTO) {
			$category_path = "students";
		}
		if ($category == USER_PROFILE_PHOTO) {
			$category_path = "users";
		}
		if ($category == STUDENT_OTHER_PHOTO) {
			$category_path = "students";
		}
		if ($category == USER_OTHER_PHOTO) {
			$category_path = "users";
		}
		if ($category == SCHOOL_PROFILE_PHOTO) {
			$category_path = "schools";
		}
		if ($category == SCHOOL_OTHER_PHOTO) {
			$category_path = "schools";
		}
		if ($category == SCHOOL_ACTIVITY_PHOTO) {
			$category_path = "schactivity";
		}
		
		if ($type_of_data=="upload_path") {
			if ($image_size_path==THUMB_IMAGE){
				$return_value = "../../../images/" . $category_path . "/thumbs/";
			}
			if ($image_size_path==FULL_IMAGE){
				$return_value = "../../../images/" . $category_path . "/";
			}
		} else {
			if ($image_size_path==THUMB_IMAGE){
				$return_value = "images/" . $category_path . "/thumbs/";
			}
			if ($image_size_path==FULL_IMAGE){
				$return_value = "images/" . $category_path . "/";
			}	
		}
		
		return $return_value;
		
	}
	
	//upload pictures
    public function uploadPics($category, $category_id, $title, $image_crop_ratio, $image_width, $image_height, $fileData = array()) {
        				
		$response = array();
		$result = array();
		$inner_response = array();
		
		$photo_cat_name = $category;
				
		if (!$fileData) {
			$response["message"] = PLEASE_ENTER_REQUIRED_DATA_ERROR;
			$response["error"] = true;
			$response["ref"] = "none";
		} else {

			$created_by = USER_ID;
			$current_date = $this->getCurrentDate();
					
			//insert images if any
			if (count($fileData) > 0) {
				
				//image dirs
				$thumb_pic_dir = $this->getFileUploadDir($category, THUMB_IMAGE, "upload_path");
				$thumb_name_dir = $this->getFileUploadDir($category, THUMB_IMAGE, "upload_name");
				$pic_dir = $this->getFileUploadDir($category, FULL_IMAGE, "upload_path");
				$name_dir = $this->getFileUploadDir($category, FULL_IMAGE, "upload_name");
				
				//make dirs if they dont exist 
				//Check if the directory already exists.
				if(!is_dir($thumb_pic_dir)){
					//Directory does not exist, so lets create it.
					mkdir($thumb_pic_dir, 755, true);
				}
				if(!is_dir($pic_dir)){
					//Directory does not exist, so lets create it.
					mkdir($pic_dir, 755, true);
				}
				//end make dirs if they dont exist 
				
				//image dimensions
				$max_width = $image_width;
				$max_height = $image_height;
				$thumb_max_height = 80;
				$thumb_max_width = 80;
				/*if ($image_crop_ratio=="1"){
					$cropratio = "1:1";
				} else {
					$cropratio = "";	
				}*/
				
				$image_type = $photo_cat_name;
				$new_id = $category_id;
				$name = $title;

				//get file array values
				for($i=0; $i< count($fileData); $i++) {
										
					$tmp = array();
					$tmpName = $fileData[$i]["tmp"];
					$fileName = $fileData[$i]["name"]; // show files data
					//insert image record here for this club
					//echo "$tmpName - $fileName <br>";
					$new_image = $this->resizeUploadNew($tmpName,$fileName,$pic_dir,$name_dir,$max_width,$max_height,$image_crop_ratio,"", "");
					$new_thumb_image = $this->resizeUploadNew($tmpName,$fileName,$thumb_pic_dir,$thumb_name_dir,$thumb_max_width,$thumb_max_height,"1:1","", "");
					
					//store the new files to db
					$result = $this->savePhoto($image_type, $new_id, $name, $new_thumb_image, $new_image);
					$image_id = $result["image_id"];
					$image_src = $result["image_src"];
					
					$tmp = $this->getItemSingleImage($image_id);
					
					//get image dimensions
					$image_path = SITEPATH . $image_src;
					//list($image_width, $image_height) = getimagesize($image);	
					list($image_width, $image_height) = $this->getimgsize($image_path, SITEPATH);				
					$tmp["image_path"] = $image_path;
					$tmp["image_width"] = $image_width;
					$tmp["image_height"] = $image_height;
					$tmp["image_dimensions"] = $image_width . " X " . $image_height;
				
					array_push($inner_response, $tmp);

				}
				$response["images"] = $inner_response;
				$response["error"] = false;
				$response["message"] = "Photos uploaded successfully";
				
			}
			
		}
 
        return $response;
    }
	
	//get item images
	public function getItemImagesNew($cat_name=NULL, $item_id=NULL, $image_id=NULL){
		
		$response = array();
		$result = array();
		
		if ($cat_name==SCHOOL_PROFILE_PHOTO) { $section = SCHOOL_PROFILE_PHOTO; }
		if ($cat_name==STUDENT_PROFILE_PHOTO) { $section = STUDENT_PROFILE_PHOTO; }
		if ($cat_name==STUDENT_OTHER_PHOTO) { $section = STUDENT_OTHER_PHOTO; }
		if ($cat_name==USER_PROFILE_PHOTO) { $section = USER_PROFILE_PHOTO; }
		if ($cat_name==USER_OTHER_PHOTO) { $section = USER_OTHER_PHOTO; }
		if ($cat_name==SCHOOL_PROFILE_PHOTO) { $section = SCHOOL_PROFILE_PHOTO; }
		if ($cat_name==SCHOOL_OTHER_PHOTO) { $section = SCHOOL_OTHER_PHOTO; }
		if ($cat_name==SCHOOL_ACTIVITY_PHOTO) { $section = SCHOOL_ACTIVITY_PHOTO; }	
		
		$query = "SELECT id, caption,  thumb_img, full_img FROM images WHERE ((image_section = ? AND image_section_id = ?) OR (id = ?)) ORDER BY id DESC"; 

		if($stmt = $this->conn->prepare($query)){
		
			$stmt = $this->conn->prepare($query);
			$stmt->bind_param("sii", $section, $item_id, $image_id);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($image_id, $caption,  $thumb_img, $full_img);
			//if no image, set default
			//if (!$full_img){ $full_img = DEFAULT_USER_IMAGE; }
			//$stmtGetPhoto->close();	
			
			while ($stmt->fetch()) 
			{
	  
				$tmp = array();
				
				//get image dimensions
				$full_image = SITEPATH . $full_img;
				$thumb_image = SITEPATH . $thumb_img;
				//list($image_width, $image_height) = getimagesize($full_image);
				
				list($image_width, $image_height) = $this->getimgsize($full_image, SITEPATH);
				
				$tmp["image_id"] = $image_id;
				$tmp["caption"] = $caption;
				$tmp["thumb_image"] = $thumb_image;
				$tmp["image"] = $full_image;
				
				$tmp["image_width"] = $image_width;
				$tmp["image_height"] = $image_height;
				$tmp["image_dimensions"] = $image_width . " X " . $image_height;
					
				array_push($result, $tmp);		
		
			 }
			 
			 $response['rows'] = $result;
			 $response["error"] = false;	
		 
		} else {

			$response["message"] = "Error occured";
			$response["error_type"] = AN_ERROR_OCCURED_ERROR;
			$response["error"] = true;	
			
		}
		 
		 return $response;
		  	
	}
	
	function getimgsize($url, $referer = '')
	{
		$headers = array(
						'Range: bytes=0-32768'
						);
	
		/* Hint: you could extract the referer from the url */
		if (!empty($referer)) array_push($headers, 'Referer: '.$referer);
	
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($curl);
		curl_close($curl);
	
		$image = imagecreatefromstring($data);
	
		$return = array(imagesx($image), imagesy($image));
	
		imagedestroy($image);
	
		return $return;
	}
	
	//get item images
	public function getItemSingleImage($image_id=NULL){
		
		$response = array();
		$result = array();
		
		$query = "SELECT id, caption, thumb_img, full_img FROM images WHERE id = ?"; 

		if($stmt = $this->conn->prepare($query)){
		
			$stmt = $this->conn->prepare($query);
			$stmt->bind_param("i", $image_id);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($image_id, $caption, $thumb_img, $full_img);
			$stmt->fetch();
	
			$response["image_id"] = $image_id;
			$response["caption"] = $caption;
			$response["thumb_image"] = SITEPATH . $thumb_img;		
			$response["image"] = SITEPATH . $full_img;		
			$response["error"] = false;	
		 
		} else {
			//$response["query"] = $query;
			//$response["message"] = $this->conn->error;
			$response["error_type"] = AN_ERROR_OCCURED_ERROR;
			$response["error"] = true;	
		}
		 
		 return $response;
		  	
	}
	
	
	//add parent
    public function addParent($sch_id, $message, $messageType, $selected = array(), $send_data = array()) {
        				
		$response = array();
		
		$insert_results = array();
		
		$top_sch_id = $sch_id;
		
		//get the message to send
		if ($messageType == "send_msg")
		{
			$sent_message = $message;	
		} 
		
		$created_by = USER_ID;
		$current_date = $this->getCurrentDate();

		$num_recs = count($selected); //echo $num_recs; 
		
		$success_inserts = 0; 
		$fail_inserts = 0;
		$result_row = ""; 
			
		$sch_name = $this->getSchoolName($sch_id);
		$bulk_sms_details = $this->getBulkSMSData($sch_id);
		$usr = $sch_id;
		$pass = $bulk_sms_details["passwd"];
		$src = $bulk_sms_details["default_source"];
			
		//proceed only if school has usr and pass data
		if ($src && $pass) {
			
			for ($i = 0; $i < $num_recs; $i++){
				
				$success = 1;
				$user_exists = false;
				$sub_exists = false;
				$tmp = array();
				$student_id = $selected[$i];
				
				//get student data
				$student_details = $this->getStudentData("", "", "", "", $student_id);
	
				$student_full_names = $student_details["student_full_names"];
				$guardian_name = $student_details["guardian_name"];	
				$guardian_phone_number = $student_details["guardian_phone"];
				$email = $student_details["email"];
				$dob = $student_details["dob"];
				$county = $student_details["county"];
				$reg_no = $student_details["reg_no"];
				
				//echo "aaa- $guardian_phone_number, $sch_id, $reg_no - $email - $email"; exit;
				
				//request is not send_msg
				if (($messageType != "send_msg") && $guardian_phone_number && $guardian_name)
				{
										
					//if user does not exist, create one
					if (!$this->isUserExists($guardian_phone_number)) {
						
						//generate user password from date of birth
						//format stydent birth date
						$full_date_array = explode(" ", $dob);
						$dob_array = explode("-", $full_date_array[0]);
						$new_dob = $dob_array[2] . "" . $dob_array[1] . "" . $dob_array[0];
						//$password = $this->generateCode(5);
						$password = md5($new_dob);
						$user_group = PARENT_USER_ID;
						
						//create parent account
						$res = $this->createUser($guardian_phone_number, $password, $email, "", $guardian_name, $user_group, 1);
						//print_r($res); exit;
						
						$sub_message = "Parent account created.";
						
					} else {

						$user_exists = true;
						//$sub_message = "Parent account already exists.";
						
					}
										
					//insert subscriptions
					// First check if subscription already exist in db
					if (!$this->isSubExists($guardian_phone_number, $top_sch_id, $reg_no)) {
						
						$sub_id = $this->subscribeUser($guardian_phone_number, $top_sch_id, $reg_no);
						
						if ($sub_id){ //no error occured
						
							$sub_message .= "\nSubscription for <b>$guardian_name ($guardian_phone_number) to $reg_no ($student_full_names)</b> successfully added.";
							$tmp["error"] = false;
							$tmp["message"] = $sub_message;
						
						} else {
						
							$sub_message .= "\nAn error occured while subscribing <b>$guardian_name ($guardian_phone_number) to $reg_no ($student_full_names)</b>.";
							$tmp["error"] = true;
							$tmp["message"] = $sub_message;
						
							$success = 0;
						
						}
						
					} else {
						
						//subscription already exists
						$sub_message .= "\nSubscription for <b>$guardian_name ($guardian_phone_number) to $reg_no ($student_full_names)</b> already exists.";
						$tmp["error"] = true;
						$tmp["message"] = $sub_message;
						
						$success = 0;
						
						$sub_exists = true;
						
					}
					//end insert to subscriptions
				
				}
				//end request is not send_msg
				
				if ($success) {
				
					
					//is sub does not exist or we are sending a msg
					if (!$sub_exists || ($messageType == "send_msg")) {
						
						//request is not send_msg
						if (!$sub_exists) {
							
							if ($user_exists) {
								
								//user already exists
								$sent_message = sprintf(ADD_SUB_PARENT_EXISTS_MESSAGE, $guardian_name, $student_full_names); 	
							
							} else {
								
								//send new user credentials
								$password = ADD_PARENT_LOGIN_PASSWORD;
								$sent_message = sprintf(ADD_PARENT_ACCOUNT_MESSAGE, $guardian_name, $student_full_names, $password);
							
							}
						
						} 
					
						//send message
						$sent_message = urlencode($sent_message);
						$tmp = $this->sendBulkSMS($usr, $pass, $src, $guardian_phone_number, $sent_message, ADD_PARENT_REQUEST_SMS);
						//format message to be shown	
						
						if ($messageType == "send_msg") {
						
							$tmp["message"] = "Message Sent";
							
						} else {
							
							$tmp["message"] = $sub_message;
							
						}
					
					}
					
				}
				
				//print_r($tmp);	
								
				if ($tmp['error']) { 
					
					$fail_inserts += 1; 
					$result_rows .= "<tr>";
					
					$result_rows .= "<td>" . $student_full_names . "</td>";
					$result_rows .= "<td>" . $guardian_name . "</td>";
					

					$result_rows .= "<td><span class='text-danger'>" . $tmp['message'] . "</span></td>";
					$result_rows .= "</tr>";
					
				} else { 
					
					$success_inserts += 1; 
					$result_rows .= "<tr>";
					
					$result_rows .= "<td>" . $student_full_names . "</td>";
					$result_rows .= "<td>" . $guardian_name . "</td>";
					
					$result_rows .= "<td><span class='text-success'>" . $tmp['message'] . "</span></td>";
					$result_rows .= "</tr>";
					
				}
				
				array_push($insert_results, $tmp);
				
			}
		
			$stats = $send_data["stats"];
			
			$full_result_rows = "<table class='table table-responsive response-table'>";
			$full_result_rows .= "	<thead>";
			$full_result_rows .= "		<tr>";
			
			$full_result_rows .= "			<th>Student Name</th>";
			$full_result_rows .= "			<th>Parent Name</th>";
			
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
			$response["reload_grid"] = true;
			$response["reload_grid2"] = true;
			$response["reload_grid_history"] = true;
			$response["popup"] = true;
			$response["insert_results"] = $insert_results;
		
		} else {
		
			$response["error"] = true;
			$response["noty_msg"] = true;
			$response["message"] = "School: $sch_name does not have a bulk sms code";
			$response["close_form"] = true;	
			
		}
 
        return $response;
    }
	
	
	//Send bulk sms to user
    public function sendBulkSMSToUser($sch_id, $message, $messageType, $results_year, $fees_year, $term, $selected = array(), $send_data = array(), $student_data) {
        				
		$response = array();
		
		$insert_results = array(); 
		
		$sent_message = $message;
		//$sent_message = urlencode(trim($sent_message));
		
		$created_by = USER_ID;
		$current_date = $this->getCurrentDate();

		$students_array = array();
		$students_array = $selected; //print_r($students2);exit;
		$num_records = count($students_array); //echo "num_records - $num_records"; 
		
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
			
		if ($src) {
			
			for ($p = 0; $p < $num_records; $p++){
				
				$tmp = array();
				$student_id = $students_array[$p];
				
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
						//create fees message
						$sent_message_details = $this->getFeeBalance($student_id, $fees_year);
						if ($guardian_name) { $sent_message .= "Dear " . $guardian_name . ", "; }
						$sent_message .= "Your son/ daughter " . $full_names . " has a fee balance of " . $sent_message_details["bal"] . ". ";
						$sent_message .= $sch_name;
						//echo($sent_message); 	
					}
					
					if ($messageType == "results")
					{
						$sent_message = "";
						$result_period = "Term: " . $term . " Year: ". $results_year;
						$result_summary_details = $this->fetchStudentResults($sch_id, $reg_no, $results_year, $term);
						//print_r($result_summary_details); exit; 
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
						
						//create results message
						if ($guardian_name) { $sent_message .= "Dear " . $guardian_name . ", "; } else { $sent_message .= "Dear Parent, "; }
						$sent_message .= $full_names . " results for " . $result_period . ": ";
						$sent_message .= $subject_results . ". ";
						$sent_message .= $sch_name;
					}
					
					//encode message
					$sent_message = urlencode(trim($sent_message));
										
				} else {
					$phone_number = $student_id;	
				}
				
				//echo $sent_message;exit;
								
				if ($phone_number) {
					
					//send each bulk sms
					$tmp = $this->sendBulkSMS($usr, $pass, $src, $phone_number, $sent_message, SCHOOL_MESSAGE_SMS);
					
				} else {
					
					//show error only for students
					if ($student_data) {
						$tmp["error"] = true;
						$tmp["noty_msg"] = true;
						$tmp["message"] = "Phone number missing for $full_names's guardian";
					}
					
				}
				
				//formulate error/ success mesage
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
			
			//print_r($insert_results); exit;
		
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
		
		} else {
		
			$response["error"] = true;
			$response["noty_msg"] = true;
			$response["message"] = "School: $sch_name does not have a bulk sms code";
			$response["close_form"] = true;	
			
		}
 
        return $response;
    }
	
	
	//Upload student data from excel file
    public function uploadStudents($sch_id, $students=array(), $user_id=NULL) {
        				
		$response = array();
		$students2 = array();
		$insert_results = array();
		
		$user_id = USER_ID;
		$sch_name = $this->getSchoolName($sch_id);
		
		$created_by = USER_ID;
		$current_date = $this->getCurrentDate();

		$students2 = $students["students"]; //print_r($students2);
		
		$num_recs = count($students2); //echo $num_recs; exit;
		
		$success_inserts = 0; 
		$fail_inserts = 0;
		$result_row = ""; 
		
		for ($i = 0; $i < $num_recs; $i++){
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
			$guardian_phone = $students2[$i]["guardian_mobile"];
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
			
			//echo "$full_names, $reg_no, $sch_id, $admin_date, $student_profile, $guardian_name, $guardian_phone, $guardian_address, $dob, $index_no, $nationality, $religion, $previous_school, $house, $club, $guardian_phone, $guardian_id_card, $guardian_relation, $guardian_occupation, $email, $town, $current_class, $village, $county, $location, $disability, $gender, $stream, $constituency"; exit;
						
			//save each student item to db
			$tmp = $this->createStudent($full_names, $reg_no, $sch_id, $admin_date, $student_profile, $guardian_name, $guardian_phone, $guardian_address, $dob, $index_no, $nationality, $religion, $previous_school, $house, $club, $guardian_id_card, $guardian_relation, $guardian_occupation, $email, $town, $current_class, $village, $county, $location, $disability, $gender, $stream, $constituency);
			//print_r($tmp); exit;
			
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
		$response["reload_grid"] = true;
		$response["reload_grid_history"] = true;
		$response["popup"] = true;
		$response["insert_results"] = $insert_results;
 
        return $response;
    }
	
	
	
		
	//edit a score grade
    public function editParent($id, $guardian_name, $guardian_relation, $guardian_occupation, $guardian_phone, $guardian_id_card, $sch_id=NULL, $user_id=NULL, $admin=NULL) {
        				
		$response = array();
		
		if (!$id) {
			$response["message"] = PLEASE_ENTER_REQUIRED_DATA_ERROR;
			$response["error"] = true;
			$response["ref"] = "none";
		} else if ($guardian_phone && !$this->isNumberValid($guardian_phone)) {
			$response["message"] = INVALID_PHONE_NUMBER_ERROR_MESSAGE;
			$response["error"] = true;
			$response["ref"] = "none";
		} else {

			if (SUPER_ADMIN_USER || SCHOOL_ADMIN_USER) {
				
				$report = 1;
				if (!$user_id) { $user_id = USER_ID; }
				$created_by = $user_id;
					
				$current_date = $this->getCurrentDate();
				
				$guardian_phone= $this->formatPhoneNumber($guardian_phone);
				
				// insert query
				$query = "UPDATE sch_students SET guardian_name = ?, guardian_relation = ?, guardian_occupation = ?, guardian_phone = ?, guardian_id_card = ?, updated_by = ?, updated_at = ?";
				$query .= " WHERE id = ?";
				$stmt = $this->conn->prepare($query);
				$stmt->bind_param("sssssisi", $guardian_name, $guardian_relation, $guardian_occupation, $guardian_phone, $guardian_id_card, $created_by, $current_date, $id);
				$result = $stmt->execute();
				$result = $stmt->store_result();
				$stmt->close();
								
				// Check for successful insertion
				if ($result) {
					
					//save score grade history
					$this->saveScoreGradeHistory($id, $created_by, $current_date);
					
					$new_id = $this->conn->insert_id;
					$response["error"] = false;
					$response["noty_msg"] = true;
					$response["reload_grid"] = true;
					$response["reload_grid_history"] = true;
					$response["message"] = "Parent successfully updated";
					$response["select_id"] = $id;	
							
				} else {
					
					// Failed
					$response["error"] = true;
					$response["error_type"] = ERROR_OCCURED;
					$response["message"] = "An error occurred whle updating parent";
					
				}				
				
				
			
			} else {
			
				$response["error"] = true;
				$response["error_type"] = NO_PERMISSION_ERROR;
				$response["message"] = NO_PERMISSION_ERROR_MESSAGE;
			}
		
		}
 
        return $response;
    }
	
	//edit a total score grade
    public function editTotalScoreGrade($id, $min, $max, $grade, $points, $level, $user_id=NULL) {
        				
		$response = array();
		
		if (!$id || !$max) {
			$response["message"] = PLEASE_ENTER_REQUIRED_DATA_ERROR;
			$response["error"] = true;
			$response["ref"] = "none";
		} else {

			if (SUPER_ADMIN_USER) {
				
				$report = 1;
				if (!$user_id) { $user_id = USER_ID; }
				$created_by = $user_id;
				
				//check if subject already exists for this level
				if ((!$this->totalScoreGradeExists($min, $level, $id, $report)) && (!$this->totalScoreGradeExists($max, $level, $id, $report))) {
					
					
					$current_date = $this->getCurrentDate();
					
					// insert query
					$query = "UPDATE total_points_grades SET min = ?, max = ?, points = ?, grade = ?, sch_level = ?, updated_by = ?, updated_at = ?";
					$query .= " WHERE id = ?";
					$stmt = $this->conn->prepare($query);
					$stmt->bind_param("iiisiisi", $min, $max, $points, $grade, $level, $created_by, $current_date, $id);
					$result = $stmt->execute();
					$result = $stmt->store_result();
					$stmt->close();
					//echo "$query - $subject_name, $short_name, $perm, $level, $status, $created_by, $current_date, $id";
					
					// Check for successful insertion
					if ($result) {
						
						//save score grade history
						$this->saveTotalScoreGradeHistory($id, $created_by, $current_date);
						
						$new_id = $this->conn->insert_id;
						$response["error"] = false;
						$response["noty_msg"] = true;
						$response["reload_grid"] = true;
						$response["reload_grid_history"] = true;
						$response["message"] = "Total score grade successfully updated";
						$response["id"] = $new_id;	
								
					} else {
						
						// Failed
						$response["error"] = true;
						$response["error_type"] = ERROR_OCCURED;
						$response["message"] = "An error occurred whle updating total score grade";
						
					}				
					
				} else {
				
					$response["error"] = true;
					$response["error_type"] = ERROR_OCCURED;
					$response["message"] = "Total score grade with min value: $min OR max value: $max already exists for this level";
				}
			
			} else {
			
				$response["error"] = true;
				$response["error_type"] = NO_PERMISSION_ERROR;
				$response["message"] = NO_PERMISSION_ERROR_MESSAGE;
			}
		
		}
 
        return $response;
    }
	
	
	//edit a score grade
    public function editScoreGrade($id, $min, $max, $grade, $points, $level, $user_id=NULL) {
        				
		$response = array();
		
		if (!$id || !$max) {
			$response["message"] = PLEASE_ENTER_REQUIRED_DATA_ERROR;
			$response["error"] = true;
			$response["ref"] = "none";
		} else {

			if (SUPER_ADMIN_USER) {
				
				$report = 1;
				if (!$user_id) { $user_id = USER_ID; }
				$created_by = $user_id;
				
				//check if subject already exists for this level
				if ((!$this->scoreGradeExists($min, $level, $id, $report)) && (!$this->scoreGradeExists($max, $level, $id, $report))) {
					
					
					$current_date = $this->getCurrentDate();
					
					// insert query
					$query = "UPDATE score_grades SET min = ?, max = ?, points = ?, grade = ?, sch_level = ?, updated_by = ?, updated_at = ?";
					$query .= " WHERE id = ?";
					$stmt = $this->conn->prepare($query);
					$stmt->bind_param("iiisiisi", $min, $max, $points, $grade, $level, $created_by, $current_date, $id);
					$result = $stmt->execute();
					$result = $stmt->store_result();
					$stmt->close();
					//echo "$query - $subject_name, $short_name, $perm, $level, $status, $created_by, $current_date, $id";
					
					// Check for successful insertion
					if ($result) {
						
						//save score grade history
						$this->saveScoreGradeHistory($id, $created_by, $current_date);
						
						$new_id = $this->conn->insert_id;
						$response["error"] = false;
						$response["noty_msg"] = true;
						$response["reload_grid"] = true;
						$response["reload_grid_history"] = true;
						$response["message"] = "Score grade successfully updated";
						$response["id"] = $new_id;	
								
					} else {
						
						// Failed
						$response["error"] = true;
						$response["error_type"] = ERROR_OCCURED;
						$response["message"] = "An error occurred whle updating subject";
						
					}				
					
				} else {
				
					$response["error"] = true;
					$response["error_type"] = ERROR_OCCURED;
					$response["message"] = "Score grade with min value: $min OR max value: $max already exists for this level";
				}
			
			} else {
			
				$response["error"] = true;
				$response["error_type"] = NO_PERMISSION_ERROR;
				$response["message"] = NO_PERMISSION_ERROR_MESSAGE;
			}
		
		}
 
        return $response;
    }
	
	
	//edit a subject
    public function editSubject($id, $subject_name, $short_name, $code, $level, $status=NULL, $user_id=NULL) {
        				
		$response = array();
		
		if (!$id || !$subject_name || !$short_name || !$code) {
			$response["message"] = PLEASE_ENTER_REQUIRED_DATA_ERROR;
			$response["error"] = true;
			$response["ref"] = "none";
		} else {

			if (SUPER_ADMIN_USER) {
				
				$report = 1;
				if (!$user_id) { $user_id = USER_ID; }
				$created_by = $user_id;
				
				//check if subject already exists for this level
				if (!$this->subjectExists($subject_name, $level, $id, $report)) {
					
					
					$current_date = $this->getCurrentDate();
					
					//generate permalink
					$perm = $this->generate_seo_link($code,$replace = '-',$remove_words = true,$words_array = array());
					//echo $perm; exit;
					
					//check if code exists
					if ($this->checkIfSubjectPermExists($perm, $id, $report)) {
						$perm = $perm . "-" . $this->generateCode(3, false, 'l');
						if ($this->checkIfSubjectPermExists($perm, $id, $report)) {
							$perm = $perm . "-" . $this->generateCode(3, false, 'l');
							if ($this->checkIfSubjectPermExists($perm, $id, $report)) {
								$perm = $perm . "-" . $this->generateCode(3, false, 'l');
							}
						} 
					} 
					
					// insert query
					$query = "UPDATE sch_subjects SET name = ?, short_name = ?, code = ?, school_level = ?, status = ?, updated_by = ?, updated_at = ?";
					$query .= " WHERE id = ?";
					$stmt = $this->conn->prepare($query);
					$stmt->bind_param("sssiiisi", $subject_name, $short_name, $perm, $level, $status, $created_by, $current_date, $id);
					$result = $stmt->execute();
					$result = $stmt->store_result();
					$stmt->close();
					//echo "$query - $subject_name, $short_name, $perm, $level, $status, $created_by, $current_date, $id";
					
					// Check for successful insertion
					if ($result) {
						
						//save subject history
						$this->saveSubjectHistory($id, $created_by, $current_date);
						
						$new_id = $this->conn->insert_id;
						$response["error"] = false;
						$response["noty_msg"] = true;
						$response["reload_grid"] = true;
						$response["reload_grid_history"] = true;
						$response["message"] = "Subject successfully updated";
						$response["id"] = $new_id;	
								
					} else {
						
						// Failed
						$response["error"] = true;
						$response["error_type"] = ERROR_OCCURED;
						$response["message"] = "An error occurred whle updating subject";
						
					}				
					
				} else {
				
					$response["error"] = true;
					$response["error_type"] = ERROR_OCCURED;
					$response["message"] = "Subject \"$subject_name\" already exists for this level";
				}
			
			} else {
			
				$response["error"] = true;
				$response["error_type"] = NO_PERMISSION_ERROR;
				$response["message"] = NO_PERMISSION_ERROR_MESSAGE;
			}
		
		}
 
        return $response;
    }
	
		
	//create a new total score grade
    public function createTotalScoreGrade($min, $max, $points, $grade, $level, $user_id=NULL) {
        				
		$response = array();
		
		if (!$max) {
			$response["message"] = PLEASE_ENTER_REQUIRED_DATA_ERROR;
			$response["error"] = true;
			$response["ref"] = "none";
		} else {

			//check if score grade already exists for this level
			if ((!$this->totalScoreGradeExists($min, $level)) && (!$this->totalScoreGradeExists($max, $level))) {
				
				if (!$user_id) { $user_id = USER_ID; }
				$created_by = $user_id;
				$current_date = $this->getCurrentDate();
				
				// insert query
				$query = "INSERT INTO total_points_grades(min, max, points, grade, sch_level, created_by, created_at) VALUES(?, ?, ?, ?, ?, ?, ?)";
				$stmt = $this->conn->prepare($query);
				$stmt->bind_param("iiisiis", $min, $max, $points, $grade, $level, $created_by, $current_date);
				$result = $stmt->execute();
				$stmt->close();
				
				// Check for successful insertion
				if ($result) {
					$new_id = $this->conn->insert_id;
					
					//SAVE score grade history
					$this->saveTotalScoreGradeHistory($new_id, $created_by, $current_date);
					
					$response["error"] = false;
					$response["message"] = "Total score grade successfully created";
					$response["id"] = $new_id;	
					$response["noty_msg"] = true;
					$response["clear_form"] = true;
					$response["reload_grid"] = true;
					$response["reload_grid_history"] = true;		
				} else {
					// Failed to create chat
					$response["error"] = true;
					$response["error_type"] = ERROR_OCCURED;
					$response["message"] = "An error occurred whle creating total score grade";
				}				
				
			} else {
			
				$response["error"] = true;
				$response["error_type"] = ERROR_OCCURED;
				$response["message"] = "Total score grade already exists for this level";
			}
		
		}
 
        return $response;
    }
	
	
	//create a new score grade
    public function createScoreGrade($min, $max, $points, $grade, $level, $user_id=NULL) {
        				
		$response = array();
		
		if (!$max) {
			$response["message"] = PLEASE_ENTER_REQUIRED_DATA_ERROR;
			$response["error"] = true;
			$response["ref"] = "none";
		} else {

			//check if score grade already exists for this level
			if ((!$this->scoreGradeExists($min, $level)) && (!$this->scoreGradeExists($max, $level))) {
				
				if (!$user_id) { $user_id = USER_ID; }
				$created_by = $user_id;
				$current_date = $this->getCurrentDate();
				
				// insert query
				$query = "INSERT INTO score_grades(min, max, points, grade, sch_level, created_by, created_at) VALUES(?, ?, ?, ?, ?, ?, ?)";
				$stmt = $this->conn->prepare($query);
				$stmt->bind_param("iiisiis", $min, $max, $points, $grade, $level, $created_by, $current_date);
				$result = $stmt->execute();
				$stmt->close();
				
				// Check for successful insertion
				if ($result) {
					$new_id = $this->conn->insert_id;
					
					//SAVE score grade history
					$this->saveScoreGradeHistory($new_id, $created_by, $current_date);
					
					$response["error"] = false;
					$response["message"] = "Score grade successfully created";
					$response["id"] = $new_id;	
					$response["noty_msg"] = true;
					$response["clear_form"] = true;
					$response["reload_grid"] = true;
					$response["reload_grid_history"] = true;		
				} else {
					// Failed to create chat
					$response["error"] = true;
					$response["error_type"] = ERROR_OCCURED;
					$response["message"] = "An error occurred whle creating subject";
				}				
				
			} else {
			
				$response["error"] = true;
				$response["error_type"] = ERROR_OCCURED;
				$response["message"] = "Score grade already exists for this level";
			}
		
		}
 
        return $response;
    }
	
	//create a new subject
    public function createSubject($subject_name, $short_name, $code, $level) {
        				
		$response = array();
		
		if (!$subject_name || !$short_name || !$code) {
			$response["message"] = PLEASE_ENTER_REQUIRED_DATA_ERROR;
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
					
					//SAVE subject history
					$this->saveSubjectHistory($new_id, $created_by, $current_date);
					
					$response["error"] = false;
					$response["message"] = "Subject successfully created";
					$response["id"] = $new_id;	
					$response["noty_msg"] = true;
					$response["clear_form"] = true;
					$response["reload_grid"] = true;
					$response["reload_grid_history"] = true;		
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
			$response["message"] = PLEASE_ENTER_REQUIRED_DATA_ERROR;
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
    public function createSchool($sch_name, $sch_first_name, $sch_category, $sch_province, $sch_county, $sch_level, $status, $motto, $phone1, $phone2, $sms_welcome1, $sms_welcome2, $address, $paybill_no) {
        				
		$response = array();		
		
		$sch_first_name = $this->removeAllSpecialCharacters($sch_first_name);
		
		if (!$sch_name || !$sch_category) {
			
			$response["message"] = PLEASE_ENTER_REQUIRED_DATA_ERROR;
			$response["error"] = true;
			$response["noty_msg"] = true;
			
		} else if ($sch_first_name && $this->schFirstNameExists($sch_first_name)){
			
			$response['message'] = "School First name \"$sch_first_name\" is already used for another school.<br> Try another name";
			$response['error'] = true;
			$response["noty_msg"] = true;
			
		} else {

			$created_by = USER_ID;
			$current_date = $this->getCurrentDate();
						
			// insert query
			$query = "INSERT INTO sch_ussd(sch_name, sch_first_name, sch_category, sch_level, sch_county, sch_province, sch_paybill_no, status, motto, phone1, phone2, sms_welcome1";
			$query .= ", sms_welcome2, address, created_by, created_at) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
			$stmt = $this->conn->prepare($query);
			$stmt->bind_param("ssiiiiisssssssis", $sch_name, $sch_first_name, $sch_category, $sch_level, $sch_county, $sch_province, $paybill_no, $status, $motto, $phone1, $phone2, $sms_welcome1, $sms_welcome2, $address, $created_by, $current_date);
			$result = $stmt->execute();
			$stmt->close();
			
			
			// Check for successful insertion
			if ($result) {
				
				$new_id = $this->conn->insert_id;
				
				//save history data
				$this->saveSchoolDataHistory($new_id, $created_by, $current_date);
				
				$response["error"] = false;
				$response["message"] = "School successfully created";
				$response["clear_form"] = true;
				$response["reload_grid"] = true;
				$response["noty_msg"] = true;
				$response["id"] = $new_id;	
						
			} else {
				
				// Failed to create chat
				$response["error"] = true;
				$response["noty_msg"] = true;
				$response["error_type"] = ERROR_OCCURED;
				$response["message"] = "An error occurred while creating school";
				
			}
		
		}
 
        return $response;
    }
	
	//create a new STUDENT
    public function createStudent($full_names, $reg_no, $sch_id, $admin_date=NULL, $student_profile=NULL, $guardian_name=NULL, $guardian_phone=NULL, $guardian_address=NULL, $dob=NULL, $index_no=NULL, $nationality=NULL, $religion=NULL, $previous_school=NULL, $house=NULL, $club=NULL, $guardian_id_card=NULL, $guardian_relation=NULL, $guardian_occupation=NULL, $email=NULL, $town=NULL, $current_class=NULL, $village=NULL, $county=NULL, $location=NULL, $disability=NULL, $gender=NULL, $stream=NULL, $constituency=NULL, $user_id=NULL) {
        				
		$response = array();
		
		$sch_name = $this->getSchoolName($sch_id);
		
		if (!$full_names || !$reg_no || !$sch_id) {
			$response["message"] = PLEASE_ENTER_REQUIRED_DATA_ERROR;
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

			if (!$user_id) { $user_id = USER_ID; }
			$created_at = $this->getCurrentDate();
			
			//if ($dob) { $dob = $this->reformat_date($dob); }
			//if ($admin_date) { $admin_date = $this->reformat_date($admin_date); }
			$admin_date = $this->formatDate($admin_date);
			$dob = $this->formatDate($dob);
			if ($guardian_phone) { $guardian_phone = $this->formatPhoneNumber($guardian_phone); }
			
			// insert query
			$query = "INSERT INTO sch_students(full_names, reg_no, sch_id, admin_date, student_profile, guardian_name";
			$query .= ", guardian_phone, guardian_address, dob, index_no, nationality, religion, previous_school, house";
			$query .= ", club, guardian_id_card, guardian_relation, guardian_occupation, email, town, current_class, village";
			$query .= ", county, location, disability, gender, stream, constituency, created_by, created_at)";
			$query .= " VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
			//echo "$query - $full_names, $reg_no, $sch_id, $admin_date, $student_profile, $guardian_name, $guardian_phone, $guardian_address, $dob, $index_no, $nationality, $religion, $previous_school, $house, $club, $guardian_id_card, $guardian_relation, $guardian_occupation, $email, $town, $current_class, $village, $county, $location, $disability, $gender, $stream, $constituency, $user_id, $created_at"; exit;
			$stmt = $this->conn->prepare($query);
			$stmt->bind_param("ssisssssssssssssssssssisssssis", $full_names, $reg_no, $sch_id, $admin_date, $student_profile, $guardian_name, $guardian_phone, $guardian_address, $dob, $index_no, $nationality, $religion, $previous_school, $house, $club, $guardian_id_card, $guardian_relation, $guardian_occupation, $email, $town, $current_class, $village, $county, $location, $disability, $gender, $stream, $constituency, $user_id, $created_at);
			$result = $stmt->execute();
			$stmt->close();
			
			// Check for successful insertion
			if ($result) {
				
				$new_id = $this->conn->insert_id;
				
				//save history data
				$this->saveStudentDataHistory($new_id, $user_id, $created_at);
				
				$response["error"] = false;
				$response["clear_form"] = true;
				$response["message"] = "Record successfully created";
				$response["reg_no"] = $reg_no;
				$response["full_names"] = $full_names;
				//$response["ref"] = "none";
				$response["reload_grid"] = true;
				$response["noty_msg"] = true;
				$response["id"] = $new_id;	
						
			} else {
				
				// Failed to create record
				$response["error"] = true;
				$response["noty_msg"] = true;
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
		
		$start_at = $this->reformatDateTime($start_at);
		$end_at = $this->reformatDateTime($end_at);
		
		$query = "UPDATE sch_activities SET name = ?, description = ?, venue = ?, start_at = ?, end_at = ? WHERE id = ? ";
		$stmt = $this->conn->prepare($query);  
        $stmt->bind_param("sssssi", $name, $description, $venue, $start_at, $end_at, $id);

		if ($stmt->execute()) {
            $response = array();
			$response["message"] = "Successfully updated";
			$response["reload_grid"] = true;
			$response["error"] = false;
        } else {
            $response["message"] = "An error occured";
			$response["error"] = true;
        }
		
		$stmt->close(); 
		
		return $response;
			
    }
		
	//get bulk sms bal
	public function getBulkSmsBalance($sch_id, $user_id, $admin) {
		
		$response = array();
		
		if (!$user_id) { $user_id = USER_ID; }
		
		//check user permissions
		$super_admin = $this->isSuperAdmin($user_id);
		if ($admin && !$super_admin) {
			$perms = ALL_BULK_SMS_PERMISSIONS; 
			$company_ids = $this->getUserCompanyIds($user_id, $perms, $sch_id); 
		}
		
		//echo "company_ids - $company_ids";exit;
		
		if ($super_admin || ($admin && $company_ids)) {
		
			//get bulk sms data
			$bulk_sms_data = $this->getBulkSMSData($sch_id);
			$usr = $sch_id;
			$pass = $bulk_sms_data["passwd"];
			$default_source = $bulk_sms_data["default_source"];
			$sms_balance = $bulk_sms_data["sms_balance"];
						
			// get results
			if  ($default_source) {
				
				//show success msg
				$response["error"] = false;
				$response["balance"] = $sms_balance;
								
			} else {
				
				$sch_name = $this->getSchoolName($sch_id);
				//show error msg
				$response["message"] = sprintf(NO_BULK_SMS_ACCOUNT_ERROR_MESSAGE, $sch_name);
				$response["error_type"] = AN_ERROR_OCCURED_ERROR;
				$response["error"] = true;
			
			}

		} else {
			
			//show error msg
			$response["message"] = NO_PERMISSION_ERROR_MESSAGE;
			$response["error_type"] = NO_PERMISSION_ERROR;
			$response["error"] = true;
			
		}
		
		return $response;
			
    }
	
	
	//check if paybill is valid	
    public function isPaybillValid($sch_id, $user_id=NULL, $admin=NULL) {
		
		$response = array();
		
		$results = array();

		if (!$user_id) { $user_id = USER_ID; }
		
		//check user permissions
		$super_admin = $this->isSuperAdmin($user_id);
		if ($admin && !$super_admin) {
			$perms = ALL_MPESA_TRANS_PERMISSIONS; 
			$company_ids = $this->getUserCompanyIds($user_id, $perms, $sch_id); 
		}
		
		if ($super_admin || ($admin && $company_ids)) {
			
			//get bulk sms data
			$bulk_sms_data = $this->getBulkSMSData($sch_id); 
			$usr = $sch_id;
			$pass = $bulk_sms_data["passwd"];
			$src = $bulk_sms_data["default_source"];
			$paybill_no = $bulk_sms_data["paybill"];
						
			if ($usr && $pass && $paybill_no) {
			
				//show success msg
				$response["message"] = SUCCESS_MESSAGE;
				$response["error"] = false;
			
			} else {
				
				$sch_name = $this->getSchoolName($sch_id);
				//show error msg
				$response["message"] = sprintf(NO_PAYBILL_NUMBER_ERROR_MESSAGE, $sch_name);
				$response["error_type"] = NO_PERMISSION_ERROR;
				$response["error"] = true;
			
			}
			
		} else {
			
			//show error msg
			$response["message"] = NO_PERMISSION_ERROR_MESSAGE;
			$response["error_type"] = NO_PERMISSION_ERROR;
			$response["error"] = true;
			
		}
		
		return $response; 

    }

	
	// fetch MPESA inbox
    public function fetchMPESAInbox($sch_id, $id=NULL, $sender_no=NULL, $account_no=NULL, $paybill_no=NULL, $mpesa_code=NULL, $user_id=NULL, $admin=NULL, $start_date=NULL, $end_date=NULL,$page=NULL, $lperpage=NULL, $sort=NULL, $searchPhrase=NULL, $no_pagination=false) {
		
		//$sort = http_build_query(array($sort));
		//$sort = urlencode($sort);
		//echo "sort --- $sort == "; print_r($sort); 
		
		$response = array();
		
		$results = array();

		if (!$user_id) { $user_id = USER_ID; }
		
		//check user permissions
		$super_admin = $this->isSuperAdmin($user_id);
		if ($admin && !$super_admin) {
			$perms = ALL_MPESA_TRANS_PERMISSIONS; 
			$company_ids = $this->getUserCompanyIds($user_id, $perms, $sch_id); 
		}
		
		if ($super_admin || ($admin && $company_ids)) {
			
			//get bulk sms data
			$bulk_sms_data = $this->getBulkSMSData($sch_id); 
			$usr = $sch_id;
			$pass = $bulk_sms_data["passwd"];
			$src = $bulk_sms_data["default_source"];
			$paybill_no = $bulk_sms_data["paybill"];
						
			if ($usr && $pass && $paybill_no) {
				
				//get mpesa trans
				$url = GET_MPESA_IPNS_URL . "?usr=" . $usr . "&pass=" . $pass . "&paybill_no=" . $paybill_no . "&sort=" . $sort . "&start_date=" . $start_date . "&end_date=" . $end_date . "&id=" . $id . "&sender_no=" . $sender_no .  "&account_no=" . $account_no.  "&mpesa_code=" . $mpesa_code . "&page=" . $page . "&lperpage=" . $lperpage . "&search_text=" . $searchPhrase . "&no_pagination=" . $no_pagination;
				
				//echo "url - $url == "; 
				$items = $this->executeLink($url); 
				//print_r($items);exit;
				$total = $items->total;
				$rowCount = $items->rowCount;
				$current = $items->current;
				
				if ($items->total > 0) {					
					
					foreach ($items->rows as $key => $val) {
					
						$tmp = array();
						
						$account_no = $val->account_no;
						
						$tmp["account_no"] = $account_no;
						
						//get student details
						list($reg_no, $student_name) = explode("-", $account_no);
						$reg_no = trim($reg_no);
						if ($reg_no) {
							$student_data = $this->getStudentData($reg_no, $sch_id);
							$student_full_names = $student_data["student_full_names"];
							$current_class = $student_data["current_class"];
							$stream = $student_data["stream"];
						} else {
							$student_full_names = "";
							$current_class = "";
							$stream = "";	
						}
						
						$tmp["id"] = $val->id;
						$tmp["name"] = $val->name;
						$tmp["received_at"] = $val->received_at;
						$tmp["received_at_fmt"] = $val->received_at_fmt;
						$tmp["mpesa_code"] = $val->mpesa_code;
						$tmp["paybill_no"] = $val->paybill_no;
						$tmp["first_name"] = $val->first_name;
						$tmp["middle_name"] = $val->middle_name;
						$tmp["last_name"] = $val->last_name;
						$tmp["full_names"] = $val->full_names;
						$tmp["sender_no"] = $val->sender_no;
						$tmp["account_no"] = $account_no;
						$tmp["account_name"] = $val->account_name;
						$tmp["amount"] = $val->amount;
						$tmp["amount_fmt"] = $val->amount_fmt;
						$tmp["amount_fmt2"] = $val->amount_fmt2;
						
						//student details
						$tmp["student_full_names"] = $student_full_names;
						$tmp["current_class"] = $current_class;
						$tmp["reg_no"] = $reg_no;
						$tmp["stream"] = $stream;
						
						array_push($results, $tmp);					 
						
					}
									
				}
				
				$response["rows"] = $results;
				$response["total"] = $total;
				$response["rowCount"] = $rowCount;
				$response["current"] = $current;
				
			} else {
				
				$sch_name = $this->getSchoolName($sch_id);
				//show error msg
				$response["message"] = sprintf(NO_PAYBILL_NUMBER_ERROR_MESSAGE, $sch_name);
				$response["error_type"] = NO_PERMISSION_ERROR;
				$response["error"] = true;
			
			}
			
		} else {
			
			//show error msg
			$response["message"] = NO_PERMISSION_ERROR_MESSAGE;
			$response["error_type"] = NO_PERMISSION_ERROR;
			$response["error"] = true;
			
		}
		
		return $response; 

    }

	
	// fetch SMS inbox 
    public function fetchSMSInbox($sch_id, $user_id=NULL, $admin=NULL, $start_date=NULL, $end_date=NULL, $id=NULL, $user_phone_number=NULL, $page=NULL, $lperpage=NULL, $sort=NULL, $searchPhrase=NULL, $no_pagination=FALSE) {

		$response = array();
		
		$results = array();
		

		if (!$user_id) { $user_id = USER_ID; }
		
		//check user permissions
		$super_admin = $this->isSuperAdmin($user_id);
		if ($admin && !$super_admin) {
			$perms = ALL_BULK_SMS_PERMISSIONS; 
			$company_ids = $this->getUserCompanyIds($user_id, $perms, $sch_id); 
		}
		
		if ($super_admin || ($admin && $company_ids)) {
			
			//get school data
			$sch_data = $this->getSchoolData("", $sch_id);
			$sch_first_name = $sch_data["sch_first_name"];
			
			//get bulk sms data
			$bulk_sms_data = $this->getBulkSMSData($sch_id);
			$usr = $sch_id;
			$pass = $bulk_sms_data["passwd"];
			$src = $bulk_sms_data["default_source"];
			
			//$sch_first_name = "asperin";
			
			//FETCH SMS INBOX			
			$url = GET_SCHOOL_SMS_INBOX_URL . "?usr=" . $usr . "&pass=" . $pass . "&src=" . $src . "&sch_first_name=" . $sch_first_name . "&sort=" . $sort . "&start_date=" . $start_date . "&end_date=" . $end_date . "&id=" . $id . "&source=" . $user_phone_number . "&page=" . $page . "&lperpage=" . $lperpage . "&search_text=" . $searchPhrase . "&no_pagination=" . $no_pagination;
			//echo "url == $url";exit;
			
			$response = $this->executeLink($url);
			
		} else {
			
			//show error msg
			$response["message"] = NO_PERMISSION_ERROR_MESSAGE;
			$response["error_type"] = NO_PERMISSION_ERROR;
			$response["error"] = true;
			
		}
		
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
	
	//get counties
	function getCounties(){
		
		$response = array();
		$result = array();
		//get the user types
		$query = "SELECT id, name FROM counties WHERE name!='' ORDER BY name";
		
		if ($stmt = $this->conn->prepare($query)) {
			
			$stmt->execute();
			/* bind result variables */
			$stmt->bind_result($id, $name);
			
			while ($stmt->fetch()) 
			{
	  
				$tmp = array();
				$tmp["id"] = $id;
				$tmp["name"] = $name;	
				array_push($result, $tmp);		
		
			 }
			 
			 $response["rows"] = $result;
		 
		} else {
			//$response["query"] = $queryadd;
			
			$response["message"] = $this->conn->error;
			$response["error_type"] = AN_ERROR_OCCURED_ERROR;
			$response["error"] = true;	
		}
		 
		 return $response;
		  	
	}
	
	//get counties
	function getSchoolLevels(){
		
		$response = array();
		$result = array();
		//get the sch levels
		$query = "SELECT id, name FROM sch_levels ORDER BY name";
		
		if ($stmt = $this->conn->prepare($query)) {
			
			$stmt->execute();
			/* bind result variables */
			$stmt->bind_result($id, $name);
			
			while ($stmt->fetch()) 
			{
	  
				$tmp = array();
				$tmp["id"] = $id;
				$tmp["name"] = $name;	
				array_push($result, $tmp);		
		
			 }
			 
			 $response["rows"] = $result;
		 
		} else {
			//$response["query"] = $queryadd;
			
			$response["error_type"] = AN_ERROR_OCCURED_ERROR;
			$response["error"] = true;
				
		}
		 
		 return $response;
		  	
	}
	
	//get counties
	function getSchoolCategories(){
		
		$response = array();
		$result = array();
		//get the sch levels
		$query = "SELECT id, name FROM sch_categories WHERE active='yes' ORDER BY name";
		
		if ($stmt = $this->conn->prepare($query)) {
			
			$stmt->execute();
			/* bind result variables */
			$stmt->bind_result($id, $name);
			
			while ($stmt->fetch()) 
			{
	  
				$tmp = array();
				$tmp["id"] = $id;
				$tmp["name"] = $name;	
				array_push($result, $tmp);		
		
			 }
			 
			 $response["rows"] = $result;
		 
		} else {
			//$response["query"] = $queryadd;
			
			$response["error_type"] = AN_ERROR_OCCURED_ERROR;
			$response["error"] = true;
				
		}
		 
		 return $response;
		  	
	}
	
	//get counties
	function getPaymentModes(){
		
		$response = array();
		$result = array();
		//get the user types
		$query = "SELECT id, name, code FROM payment_modes WHERE name!='' ORDER BY name";
		
		if ($stmt = $this->conn->prepare($query)) {
			
			$stmt->execute();
			/* bind result variables */
			$stmt->bind_result($id, $name, $code);
			
			while ($stmt->fetch()) 
			{
	  
				$tmp = array();
				$tmp["id"] = $id;
				$tmp["name"] = $name;	
				$tmp["code"] = $code;	
				array_push($result, $tmp);		
		
			 }
			 
			 $response["rows"] = $result;
		 
		} else {
			//$response["query"] = $queryadd;
			
			$response["message"] = $this->conn->error;
			$response["error_type"] = AN_ERROR_OCCURED_ERROR;
			$response["error"] = true;	
		}
		 
		 return $response;
		  	
	}
	
	//get counties
	function getProvinces(){
		
		$response = array();
		$result = array();
		//get the user types
		$query = "SELECT id, name FROM provinces WHERE name!='' ORDER BY name";
		
		if ($stmt = $this->conn->prepare($query)) {
			
			$stmt->execute();
			/* bind result variables */
			$stmt->bind_result($id, $name);
			
			while ($stmt->fetch()) 
			{
	  
				$tmp = array();
				$tmp["id"] = $id;
				$tmp["name"] = $name;	
				array_push($result, $tmp);		
		
			 }
			 
			 $response["rows"] = $result;
		 
		} else {
			//$response["query"] = $queryadd;
			
			$response["message"] = $this->conn->error;
			$response["error_type"] = AN_ERROR_OCCURED_ERROR;
			$response["error"] = true;	
		}
		 
		 return $response;
		  	
	}
	
	//get STATUSES
	function getStatuses($section=NULL){
		
		$response = array();
		$result = array();
		
		//get the status
		$query = "SELECT id, name FROM status WHERE name!='' ";
		if ($section) { $query .= " AND section LIKE '%$section%' "; }
		$query .= " ORDER BY id";
		
		if ($stmt = $this->conn->prepare($query)) {
			
			$stmt->execute();
			/* bind result variables */
			$stmt->bind_result($id, $name);
			
			while ($stmt->fetch()) 
			{
	  
				$tmp = array();
				$tmp["id"] = $id;
				$tmp["name"] = $name;	
				array_push($result, $tmp);		
		
			 }
			 
			 $response["rows"] = $result;
		 
		} else {
			//$response["query"] = $queryadd;
			
			$response["message"] = $this->conn->error;
			$response["error_type"] = AN_ERROR_OCCURED_ERROR;
			$response["error"] = true;	
		}
		 
		 return $response;
		  	
	}
	
	//get statuses
	function getStatus(){
		
		$response = array();
		$result = array();
		//get the user types
		$query = "SELECT id, name FROM status WHERE name!='' ORDER BY name";
		
		if ($stmt = $this->conn->prepare($query)) {
			
			$stmt->execute();
			/* bind result variables */
			$stmt->bind_result($id, $name);
			
			while ($stmt->fetch())  
			{
	  
				$tmp = array();
				$tmp["id"] = $id;
				$tmp["name"] = $name;	
				array_push($result, $tmp);		
		
			 }
			 
			 $response["rows"] = $result;
		 
		} else {
			//$response["query"] = $queryadd;
			$response["message"] = $this->conn->error;
			$response["error_type"] = AN_ERROR_OCCURED_ERROR;
			$response["error"] = true;	
		}
		 
		 return $response;
		  	
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
				
				//get most recent message for this conversation
				$recent_message_data = $this->getRecentMessage($chat_id);
							
				$tmp["recent_message_id"] = $recent_message_data["recent_message_id"];
				$tmp["recent_message"] = $recent_message_data["recent_message"];
				$tmp["recent_message_created_at"] = $recent_message_data["recent_message_created_at"];
				$tmp["unread_count"] = $recent_message_data["unread_count"];
				
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
	
	function getIp2()
	{
		$ip = NULL;
		if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		elseif(isset($_SERVER['HTTP_CLIENT_IP']))
		{
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}
		elseif(isset($_SERVER['REMOTE_ADDR']))
		{
			$ip = $_SERVER['REMOTE_ADDR'];
		}
	
		if(strpos($ip, ",") !== FALSE)
		{
			$ips = explode(",", $ip);
			$ip = trim(array_pop($ips));
		}
	
		return $ip;
	}
	
	// fetching user account
    public function getSchoolData($phone_number, $school_id) {
		
		$school = array();
		
		$phone_number = $this->formatPhoneNumber($phone_number);
		
		//get school data
		$query = "SELECT sch_id, sch_name, sch_first_name, address, province, category, extra, sch_profile, events_calender";
		$query .= " , phone1, phone2, motto, sms_welcome1, sms_welcome2, sch_paybill_no FROM sch_ussd ";
		$query .= " WHERE sch_id = ? ";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i", $school_id);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($sch_id, $sch_name, $sch_first_name, $address, $province, $category, $extra, $sch_profile, $events_calendar, $phone1, $phone2, $motto, $sms_welcome1, $sms_welcome2, $sch_paybill_no);
		$stmt->fetch();
		if ($sch_name) {
            
            $school["sch_id"] = $sch_id;
			$school["sch_name"] = $sch_name;
			$school["sch_first_name"] = $sch_first_name;
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
		
		//get bulk sms data for this client
		$url = GET_BULK_SMS_DATA_URL . "?usr=" . $username;
		$result = $this->executeLink($url);
					
		// get results
		if  (!$result->error) {
			
			//show data
			$response["error"] = false;
			$response["passwd"] = $result->passwd;
			$response["alphanumeric_id"] = $result->alphanumeric_id;
			$response["fullname"] = $result->fullname;
			$response["rights"] = $result->rights;
			$response["active"] = $result->active;
			$response["default_sid"] = $result->default_sid;
			$response["default_source"] = $result->default_source;
			$response["paybill"] = $result->paybill;
			$response["relationship"] = $result->relationship;
			$response["home_ip"] = $result->home_ip;
			$response["default_priority"] = $result->default_priority;
			$response["default_dest"] = $result->default_dest;
			$response["default_msg"] = $result->default_msg;
			$response["sms_balance"] = $result->sms_balance;
			$response["sms_expiry"] = $result->sms_expiry;
			$response["routes"] = $result->routes;
			$response["last_updated"] = $result->last_updated;			
            
        } else {
			
			$response["error"] = true;
			$response["message"] = $result->message;
			
        }
		
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
    public function getSubjectGridListing($level_id, $page=NULL, $search_text=NULL, $lperpage=NULL, $sort=NULL, $id=NULL) {

		$subjects = array();
		
		$sortqry = "";
		//start sort
		if ($sort['name'] == "asc") {
			$sortqry = " ss.name ";
		} else if ($sort['name'] == "desc") {
			$sortqry = " ss.name DESC ";
		} else if ($sort['id'] == "asc") {
			$sortqry = " ss.id ";
		} else if ($sort['id'] == "desc") {
			$sortqry = " ss.id DESC ";
		} else if ($sort['short_name'] == "asc") {
			$sortqry = " short_name ";
		} else if ($sort['short_name'] == "desc") {
			$sortqry = " short_name DESC ";
		} else if ($sort['code'] == "asc") {
			$sortqry = " ss.code ";
		} else if ($sort['code'] == "desc") {
			$sortqry = " ss.code DESC ";
		} else if ($sort['level'] == "asc") {
			$sortqry = " sl.name ";
		} else if ($sort['level'] == "desc") {
			$sortqry = " sl.name DESC ";
		} else if ($sort['status'] == "asc") {
			$sortqry = " s.name ";
		} else if ($sort['status'] == "desc") {
			$sortqry = " s.name DESC ";
		} else if ($sort['created_at'] == "asc") {
			$sortqry = " ss.created_at "; 
		} else if ($sort['created_at'] == "desc") {
			$sortqry = " ss.created_at DESC "; 
		} else if ($sort['created_by'] == "asc") {
			$sortqry = " ss.created_by ";
		} else if ($sort['created_by'] == "desc") {
			$sortqry = " ss.created_by DESC "; 
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
		$queryMain = "SELECT ss.id, ss.name, short_name, ss.code, ss.school_level, sl.name";
		$queryMain .= " , ss.status, s.name, ss.created_at, ss.created_by, ss.updated_at, ss.updated_by FROM sch_subjects ss ";
		$queryMain .= " LEFT JOIN  sch_levels sl ON ss.school_level = sl.id ";
		$queryMain .= " JOIN  status s ON ss.status = s.id ";
		$queryMain .= " WHERE ss.name != '' ";
		if ($id) { $queryMain .= " AND ss.id = $id "; }
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
		//echo "queryMain - $queryMain"; 
		$query = $queryMain; 
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$numberofrows = $stmt->num_rows;
		
		$stmt->bind_result($id, $name, $short_name, $code, $level_id, $level, $status, $status_name, $created_at, $created_by, $updated_at, $updated_by);
		/* fetch values */
		while ( $stmt->fetch() ) {
			
			$tmp = array();
			
			$tmp["id"] = $id;
			$tmp["name"] = $name;
			$tmp["short_name"] = $short_name;
			$tmp["code"] = $code;
			$tmp["level_id"] = $level_id;
			$tmp["level"] = $level;
			$tmp["status"] = $status;
			$tmp["status_name"] = $status_name;
			$tmp["created_at"] = $created_at;
			$tmp["created_by"] = $created_by;
			$tmp["updated_at"] = $updated_at;
			$tmp["updated_by"] = $updated_by;
			
			array_push($subjects, $tmp);
			
		}
		$response['rows'] = $subjects;
		$response['total'] = $total_recs;
		$response['rowCount'] = $lperpage;
		$response['current'] = $page;
        $stmt->close();
		
		return $response; 

    }
	
	// fetch total score grading listing for grid
    public function fetchTotalScoreGradeGridListing($level_id, $page=NULL, $search_text=NULL, $lperpage=NULL, $sort=NULL, $id=NULL) {

		$response = array();
		$results = array();
		
		$sortqry = "";
		//start sort
		if ($sort['name'] == "asc") {
			$sortqry = " ss.name ";
		} else if ($sort['name'] == "desc") {
			$sortqry = " ss.name DESC ";
		} else if ($sort['id'] == "asc") {
			$sortqry = " ss.id ";
		} else if ($sort['id'] == "desc") {
			$sortqry = " ss.id DESC ";
		} else if ($sort['short_name'] == "asc") {
			$sortqry = " short_name ";
		} else if ($sort['short_name'] == "desc") {
			$sortqry = " short_name DESC ";
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
				$full_article_search_text .= " sl.name LIKE '%" . $split_text[$i] . "%' or sg.grade LIKE '%" . $split_text[$i] . "%' or";
			}
			//more than one search term i.e. spaces in between
			if ($num_items > 1){ 
				$full_article_search_text .= " sl.name LIKE '%" . $search_text . "%' or sg.grade LIKE '%" . $search_text . "%' or"; 
			} 
			//end more than one search term i.e. spaces in between
			$full_article_search_text = $this->removelastor($full_article_search_text);
		}			

		//main query - get total number of records
		$query = "SELECT sg.id, sg.min, sg.max, sg.grade, sg.points, sg.sch_level, sl.name";
		$query .= " , sg.created_at, sg.created_by, sg.updated_at, sg.updated_by ";
		$query .= " FROM total_points_grades sg ";
		$query .= " LEFT JOIN  sch_levels sl ON sg.sch_level = sl.id ";
		$query .= " WHERE sg.id != '' ";
		if ($id) { $query .= " AND sg.id = $id "; }
		if ($level_id) { $query .= " AND sg.sch_level = $level_id "; }
		if ($search_text) { $query .= " AND ($full_article_search_text) "; }
		//echo $query; //exit;

		$stmtMain = $this->conn->prepare($query);
		$stmtMain->execute();
		$stmtMain->store_result();
		$stmtMain->fetch();
		$total_recs = $stmtMain->num_rows;
		$stmtMain->close();
		
		//filtered recordset
		if ($sortqry) { $query .= " ORDER BY $sortqry "; } else { $query .= " ORDER BY sg.min "; }//add sort query 
		$query .= " LIMIT $offset,$lperpage"; //echo "queryadd - $queryadd";
		//echo "queryMain - $queryMain"; 
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$numberofrows = $stmt->num_rows;
		
		$stmt->bind_result($id, $min, $max, $grade, $points, $level_id, $level, $created_at, $created_by, $updated_at, $updated_by);
		/* fetch values */
		while ( $stmt->fetch() ) {
			
			$tmp = array();
			
			$tmp["id"] = $id;
			$tmp["min"] = $min;
			$tmp["max"] = $max;
			$tmp["grade"] = $grade;
			$tmp["points"] = $points;
			$tmp["level_id"] = $level_id;
			$tmp["level"] = $level;
			$tmp["created_at"] = $created_at;
			$tmp["created_by"] = $created_by;
			$tmp["updated_at"] = $updated_at;
			$tmp["updated_by"] = $updated_by;
			
			array_push($results, $tmp);
			
		}
		$response['rows'] = $results;
		$response['total'] = $total_recs;
		$response['rowCount'] = $lperpage;
		$response['current'] = $page;
		
        $stmt->close();
		
		return $response; 

    }
	
	
	// fetch activities listing for grid
    public function fetchActivitiesGridListing($id, $sch_id, $start_date=NULL, $end_date=NULL, $page=NULL, $search_text=NULL, $lperpage=NULL, $sort=NULL, $user_id=NULL, $admin=NULL) {

		$response = array();
		$results = array();
		
		$sortqry = "";
		//start sort
		if ($sort['name'] == "asc") {
			$sortqry = " ss.name ";
		} else if ($sort['name'] == "desc") {
			$sortqry = " ss.name DESC ";
		} else if ($sort['id'] == "asc") {
			$sortqry = " ss.id ";
		} else if ($sort['id'] == "desc") {
			$sortqry = " ss.id DESC ";
		} else if ($sort['short_name'] == "asc") {
			$sortqry = " short_name ";
		} else if ($sort['short_name'] == "desc") {
			$sortqry = " short_name DESC ";
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
				$full_article_search_text .= " name LIKE '%" . $split_text[$i] . "%' or description LIKE '%" . $split_text[$i] . "%' or";
			}
			//more than one search term i.e. spaces in between
			if ($num_items > 1){ 
				$full_article_search_text .= " name LIKE '%" . $search_text . "%' or description LIKE '%" . $search_text . "%' or"; 
			} 
			//end more than one search term i.e. spaces in between
			$full_article_search_text = $this->removelastor($full_article_search_text);
		}
		
		if ($start_date) {
			$start_date = urldecode($start_date);
			$start_date_data = explode("/", $start_date);
			$start_date = $start_date_data[2] ."-". $start_date_data[1] ."-". $start_date_data[0];
		}
		
		if ($end_date) {
			$end_date = urldecode($end_date);
			$end_date_data = explode("/", $end_date);
			$end_date = $end_date_data[2] ."-". $end_date_data[1] ."-". $end_date_data[0]; //echo "start_date - $start_date";
		}			

		//main query - get total number of records
		$query = "SELECT id, name, start_at, end_at, description, venue, created_at, created_by, updated_at, updated_by";
		$query .= " FROM sch_activities WHERE id != '' ";
		if ($search_text) { $query .= " AND ($full_article_search_text) "; }
		if ($id) { $query .= " AND id = $id "; }
		if ($sch_id) { $query .= " AND sch_id = $sch_id "; }
		if ($start_date) { $query .= " AND SUBSTR(start_at,1,10) >= '$start_date' "; }
		if ($end_date) { $query .= " AND SUBSTR(start_at,1,10) <= '$end_date' "; }
		//echo $query; //exit;

		$stmtMain = $this->conn->prepare($query);
		$stmtMain->execute();
		$stmtMain->store_result();
		$stmtMain->fetch();
		$total_recs = $stmtMain->num_rows;
		$stmtMain->close();
		
		//filtered recordset
		if ($sortqry) { $query .= " ORDER BY $sortqry "; } else { $query .= " ORDER BY start_at DESC "; }//add sort query 
		$query .= " LIMIT $offset,$lperpage"; 
		//echo "query - $query"; 
		if ($stmt = $this->conn->prepare($query)) {
			
			$stmt->execute();
			$numberofrows = $stmt->num_rows;
			
			$stmt->bind_result($id, $name, $start_at, $end_at, $description, $venue, $created_at, $created_by, $updated_at, $updated_by);
			/* fetch values */
			while ( $stmt->fetch() ) {
				
				$tmp = array();
				
				$tmp["id"] = $id;
				$tmp["name"] = $name;
				$tmp["start_at"] = $start_at;
				$tmp["start_at_fmt"] = date("d/m/Y H:i", $this->php_date($start_at));
				$tmp["end_at"] = $end_at;
				$tmp["end_at_fmt"] = date("d/m/Y H:i", $this->php_date($end_at));
				$tmp["description"] = $description;
				$tmp["venue"] = $venue;
				$tmp["created_at"] = $created_at;
				$tmp["created_by"] = $created_by;
				$tmp["updated_at"] = $updated_at;
				$tmp["updated_by"] = $updated_by;
				
				array_push($results, $tmp);
				
			}
			$response['query'] = $query;
			$response['rows'] = $results;
			$response['total'] = $total_recs;
			$response['rowCount'] = $lperpage;
			$response['current'] = $page;
			
			$stmt->close();
		
		} else {
			
			$response["error"] = true;
			//$response["message"] = $this->conn->error;
			$response["message"] = AN_ERROR_OCCURED_MESSAGE;
			$response['error_type'] = AN_ERROR_OCCURED_ERROR;
			
		}
		
		return $response; 

    }
	
	
	// fetch score grading listing for grid
    public function fetchScoreGradeGridListing($level_id, $page=NULL, $search_text=NULL, $lperpage=NULL, $sort=NULL, $id=NULL) {

		$response = array();
		$results = array();
		
		$sortqry = "";
		//start sort
		if ($sort['name'] == "asc") {
			$sortqry = " ss.name ";
		} else if ($sort['name'] == "desc") {
			$sortqry = " ss.name DESC ";
		} else if ($sort['id'] == "asc") {
			$sortqry = " ss.id ";
		} else if ($sort['id'] == "desc") {
			$sortqry = " ss.id DESC ";
		} else if ($sort['short_name'] == "asc") {
			$sortqry = " short_name ";
		} else if ($sort['short_name'] == "desc") {
			$sortqry = " short_name DESC ";
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
				$full_article_search_text .= " sl.name LIKE '%" . $split_text[$i] . "%' or sg.grade LIKE '%" . $split_text[$i] . "%' or";
			}
			//more than one search term i.e. spaces in between
			if ($num_items > 1){ 
				$full_article_search_text .= " sl.name LIKE '%" . $search_text . "%' or sg.grade LIKE '%" . $search_text . "%' or"; 
			} 
			//end more than one search term i.e. spaces in between
			$full_article_search_text = $this->removelastor($full_article_search_text);
		}			

		//main query - get total number of records
		$query = "SELECT sg.id, sg.min, sg.max, sg.grade, sg.points, sg.sch_level, sl.name";
		$query .= " , sg.created_at, sg.created_by, sg.updated_at, sg.updated_by ";
		$query .= " FROM score_grades sg ";
		$query .= " LEFT JOIN  sch_levels sl ON sg.sch_level = sl.id ";
		$query .= " WHERE sg.id != '' ";
		if ($id) { $query .= " AND sg.id = $id "; }
		if ($level_id) { $query .= " AND sg.sch_level = $level_id "; }
		if ($search_text) { $query .= " AND ($full_article_search_text) "; }
		//echo $query; //exit;

		$stmtMain = $this->conn->prepare($query);
		$stmtMain->execute();
		$stmtMain->store_result();
		$stmtMain->fetch();
		$total_recs = $stmtMain->num_rows;
		$stmtMain->close();
		
		//filtered recordset
		if ($sortqry) { $query .= " ORDER BY $sortqry "; } else { $query .= " ORDER BY sg.min "; }//add sort query 
		$query .= " LIMIT $offset,$lperpage"; //echo "queryadd - $queryadd";
		//echo "queryMain - $queryMain"; 
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$numberofrows = $stmt->num_rows;
		
		$stmt->bind_result($id, $min, $max, $grade, $points, $level_id, $level, $created_at, $created_by, $updated_at, $updated_by);
		/* fetch values */
		while ( $stmt->fetch() ) {
			
			$tmp = array();
			
			$tmp["id"] = $id;
			$tmp["min"] = $min;
			$tmp["max"] = $max;
			$tmp["grade"] = $grade;
			$tmp["points"] = $points;
			$tmp["level_id"] = $level_id;
			$tmp["level"] = $level;
			$tmp["created_at"] = $created_at;
			$tmp["created_by"] = $created_by;
			$tmp["updated_at"] = $updated_at;
			$tmp["updated_by"] = $updated_by;
			
			array_push($results, $tmp);
			
		}
		$response['rows'] = $results;
		$response['total'] = $total_recs;
		$response['rowCount'] = $lperpage;
		$response['current'] = $page;
		
        $stmt->close();
		
		return $response; 

    }
	
	
	// fetch subject score 
    public function fetchSubjectScore($exam_id=NULL, $student_id=NULL, $subject_code=NULL) {

		//main query - get total number of records
		$query = "SELECT sri.id, sri.subject_code, sri.score, sri.grade, sri.points";
		$query .= " , sri.created_at, sri.created_by, sri.updated_at, sri.updated_by ";
		$query .= " FROM sch_results_items sri ";
		$query .= " JOIN sch_results sr ON sri.result_id = sr.id ";
		$query .= " LEFT JOIN exams e ON sr.exam_id = e.id ";
		$query .= " WHERE sr.student_id = $student_id AND sri.subject_code = '$subject_code'";
		if ($exam_id) { $query .= " AND sr.exam_id = $exam_id "; }
		//echo $query; exit;
		
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($id, $subject_code, $score, $grade, $points, $created_at, $created_by, $updated_at, $updated_by);
		/* fetch values */
		$stmt->fetch();
		
		$response['id'] = $id;
		$response['subject_code'] = $subject_code;
		$response['score'] = $score;
		$response['grade'] = $grade;
		$response['points'] = $points;
		$response['created_at'] = $created_at;
		$response['created_by'] = $created_by;
		$response['updated_at'] = $updated_at;
		$response['updated_by'] = $updated_by;
		
        $stmt->close();
		
		return $response; 

    }
	
		
	// fetch sent sms listing
    public function fetchSentSmsGridListing($sch_id=NULL, $phone_number=NULL, $sms_type_id=NULL, $start_date=NULL, $end_date=NULL, $page=NULL, $search_text=NULL, $lperpage=NULL, $sort=NULL, $id=NULL, $admin=NULL, $user_id=NULL, $no_pagination=FALSE) 	{ 

		$results = array();
		
		$sortqry = "";
		//start sort
		if ($sort['id'] == "asc"){
			$sortqry = " sc.id ";
		} else if ($sort['id'] == "desc") {
			$sortqry = " sc.id DESC ";
		} else if ($sort['phone_number'] == "asc") {
			$sortqry = " sc.mobile ";
		} else if ($sort['phone_number'] == "desc") {
			$sortqry = " sc.mobile DESC ";
		} else if ($sort['msg_text'] == "asc") {
			$sortqry = " sc.message ";
		} else if ($sort['msg_text'] == "desc") {
			$sortqry = " sc.message DESC ";
		} else if ($sort['created_at'] == "asc") {
			$sortqry = " sc.created_at ";
		} else if ($sort['created_at'] == "desc") {
			$sortqry = " sc.created_at DESC "; 
		} else if ($sort['status_text'] == "asc") {
			$sortqry = " sc.status_text ";
		} else if ($sort['status_text'] == "desc") {
			$sortqry = " sc.status_text DESC "; 
		} 
				
		if (!$user_id) { $user_id = USER_ID; }
		
		$super_admin = $this->isSuperAdmin($user_id);
		if ($admin && !$super_admin) {
			$perms = ALL_STUDENT_PERMISSIONS; 
			$company_ids = $this->getUserCompanyIds($user_id, $perms, $sch_id); 
		}
		
		//echo "company_ids - $company_ids";exit;
		
		if ($super_admin || ($admin && $company_ids) || !$admin) {
			
		
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
					$full_article_search_text .= " sc.mobile LIKE '%" . $split_text[$i] . "%' or sc.message LIKE '%" . $split_text[$i] . "%' or";
				}
				//more than one search term i.e. spaces in between
				if ($num_items > 1){ 
					$full_article_search_text .= " sc.mobile LIKE '%" . $search_text . "%' or sc.message LIKE '%" . $search_text . "%' or"; 
				} 
				//end more than one search term i.e. spaces in between
				$full_article_search_text = $this->removelastor($full_article_search_text); 
			}
			
			if ($start_date) {
				$start_date = urldecode($start_date);
				$start_date_data = explode("/", $start_date);
				$start_date = $start_date_data[2] ."-". $start_date_data[1] ."-". $start_date_data[0];
			}
			
			if ($end_date) {
				$end_date = urldecode($end_date);
				$end_date_data = explode("/", $end_date);
				$end_date = $end_date_data[2] ."-". $end_date_data[1] ."-". $end_date_data[0]; //echo "start_date - $start_date";
			}
			
			//echo " sch_id";			
	  
			//main query - get total number of records
			$query = "SELECT sc.id, sc.mobile, sc.message, sc.sms_type_id, st.name, sc.sender, sc.sender_account";
			$query .= ", sc.sender_username, sc.status, sc.status_text, sc.created_at";
			$query .= " FROM sms_codes sc  ";
			$query .= " JOIN sms_types st ON st.id = sc.sms_type_id ";
			$query .= " WHERE sc.id!='' ";
			if ($search_text) { $query .= " AND ($full_article_search_text) "; }
			if ($id) { $query .= " AND sc.id = $id "; }
			if ($sms_type_id) { $query .= " AND sc.sms_type_id = $sms_type_id "; }
			if ($phone_number) { $query .= " AND sc.mobile = '$phone_number' "; }
			if ($sch_id) { $query .= " AND sc.sender = $sch_id "; }
			if ($start_date) { $query .= " AND SUBSTR(sc.created_at,1,10) >= '$start_date' "; }
			if ($end_date) { $query .= " AND SUBSTR(sc.created_at,1,10) <= '$end_date' "; }
			//echo $query . " $sch_id"; exit;
	
			$stmtMain = $this->conn->prepare($query);
			//$stmtMain->bind_param("i", $sch_id);
			$stmtMain->execute();
			$stmtMain->store_result();
			$stmtMain->fetch();
			$total_recs = $stmtMain->num_rows;
			$stmtMain->close();
			
			//filtered recordset
			if ($sortqry) { $query .= " ORDER BY $sortqry "; } else { $query .= " ORDER BY sc.id DESC "; }//add sort query 
			
			if (!$no_pagination){
				if ($lperpage > 0) { $query .= " LIMIT $offset,$lperpage"; } //echo "query - $query";
			}
			//echo "query - $query - $sch_id";exit;
			$stmt = $this->conn->prepare($query);
			$stmt->bind_param("i", $sch_id);
			$stmt->execute();
			$stmt->store_result();
			$numberofrows = $stmt->num_rows;
			
			$stmt->bind_result($id, $phone_number, $message, $sms_type_id, $sms_type, $sender, $sender_account, $sender_username, $status, $status_text, $created_at);
			/* fetch values */
			while ( $stmt->fetch() ) {
				
				$tmp = array();
				
				if ($created_at){ $created_at = $this->adjustDate("d-M-Y, H:i", $this->php_date($created_at)); }
				
				//shorten long message
				$msg_text = urldecode($message);
				$msg_text_short = substr($msg_text, 0, 35);
				if (strlen($msg_text) > 35) {
					$msg_text_short .=  "...";	
				} 

				$tmp["id"] = $id;
				$tmp["phone_number"] = $phone_number;
				$tmp["msg_text"] = $msg_text; 
				$tmp["msg_text_short"] = $msg_text_short; 
				$tmp["sms_type_id"] = $sms_type_id;
				$tmp["sms_type"] = $sms_type;
				$tmp["sender"] = $sender;
				$tmp["sender_account"] = $sender_account;
				$tmp["sender_username"] = $sender_username;
				$tmp["status"] = $status;
				$tmp["status_text"] = $status_text;
				$tmp["created_at"] = $created_at;		
				
				array_push($results, $tmp);
				
			}
			$response['rows'] = $results;
			$response['total'] = $total_recs;
			$response['rowCount'] = $lperpage;
			$response['current'] = $page;
			$stmt->close();
			
		} else {
			
			//show error msg
			$response["message"] = NO_PERMISSION_ERROR_MESSAGE;
			$response["error_type"] = NO_PERMISSION_ERROR;
			$response["error"] = true;
			$response['rows'] = 0;
			$response['total'] = 0;
			$response['rowCount'] = $lperpage;
			$response['current'] = $page;
			
		}
		
		return $response; 

    }
	
	// fetch students listing for grid
    public function getStudentGridListing($sch_id=NULL, $page=NULL, $search_text=NULL, $lperpage=NULL, $sort=NULL, $id=NULL, $admin=NULL, $user_id=NULL, $no_pagination=FALSE, $student_ids=NULL, $current_class=NULL, $stream=NULL) { 

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
		} else if ($sort['guardian_phone'] == "asc") {
			$sortqry = " guardian_phone ";
		} else if ($sort['guardian_phone'] == "desc") {
			$sortqry = " guardian_phone DESC "; 
		} 
		
		if (!$user_id) { $user_id = USER_ID; }
		
		$super_admin = $this->isSuperAdmin($user_id);
		if ($admin && !$super_admin) {
			$perms = ALL_STUDENT_PERMISSIONS; 
			$company_ids = $this->getUserCompanyIds($user_id, $perms, $sch_id); 
		}
		
		//echo "company_ids - $company_ids";exit;
		
		if ($super_admin || ($admin && $company_ids) || !$admin) {
			
		
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
			$query = "SELECT id, full_names, reg_no, guardian_name, dob, admin_date, index_no, nationality, religion, previous_school, house";
			$query .= ", club, guardian_id_card, guardian_relation, guardian_occupation, current_class, email, town, village, county, location";
			$query .= ", disability, gender, stream, constituency, student_profile";
			$query .= ", guardian_address, guardian_phone FROM sch_students WHERE id!='' ";
			if ($search_text) { $query .= " AND ($full_article_search_text) "; }
			if ($id) { $query .= " AND id=$id "; }
			if ($company_ids) { $query .= " AND sch_id IN ($company_ids) "; }
			if ($student_ids) { $query .= " AND id IN ($student_ids) "; }
			if ($sch_id) { $query .= " AND sch_id=$sch_id "; }
			if ($current_class) { $query .= " AND current_class=$current_class "; }
			if ($stream) { $query .= " AND stream='$stream' "; }
			//echo $query . " $query"; exit;
	
			$stmtMain = $this->conn->prepare($query);
			$stmtMain->execute();
			$stmtMain->store_result();
			$stmtMain->fetch();
			$total_recs = $stmtMain->num_rows;
			$stmtMain->close();
			
			//filtered recordset
			if ($sortqry) { $query .= " ORDER BY $sortqry "; } else { $query .= " ORDER BY full_names "; }//add sort query 
			
			if (!$no_pagination){
				if ($lperpage > 0) { $query .= " LIMIT $offset,$lperpage"; } //echo "queryadd - $queryadd";
			}
			//echo "query - $query - $sch_id";exit;
			$stmt = $this->conn->prepare($query);
			$stmt->execute();
			$stmt->store_result();
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
				$tmp["status"] = $this->getParentAccountStatus($id, $sch_id, $reg_no);			
				
				array_push($students, $tmp);
				
			}
			
			//$response['query'] = $query;
			$response['rows'] = $students;
			$response['total'] = $total_recs;
			$response['rowCount'] = $lperpage;
			$response['current'] = $page;
			$stmt->close();
			
		}
		
		return $response; 

    }
	
	//whether parent already has an account linked to student
	function getParentAccountStatus($id, $sch_id, $reg_no){
		
		$status = NOT_SUBSCRIBED_STATUS;
				
		//check if user account exists
		$student_data = $this->getStudentData("", "", "", "", $id);
		$guardian_phone = $student_data["guardian_phone"];
		
		$user_data = $this->getUserDetails($guardian_phone); 
		$user_id = $user_data["user_id"];
		$phone_number = $user_data["user_phone_number"];
		$logged_times = $user_data["logged_times"];
		
		//does user account exist?
		if ($user_id) {
					
			//check if parent account request has been sent
			$sent_sms_data = $this->getSentSMS("", $guardian_phone, ADD_PARENT_REQUEST_SMS);
			$sms_id = $sent_sms_data["sms_id"]; 
			
			//add parent request sms exists and parent is subscribed to student
			if ($sms_id && ($this->isSubExists($phone_number, $sch_id, $reg_no))) {
				$status	= REQUEST_SENT_STATUS;	
			}
			
			//if user has ever logged in
			if ($logged_times > 0) {

				//if user has ever logged in, account exists status is true
				//$status	= ACCOUNT_EXISTS_STATUS;
									 
				//is account subscribed to student?, has the parent ever logged in? 
				//if yes, set status to subscribed			
				if ($this->isSubExists($phone_number, $sch_id, $reg_no)) {
					$status	= SUBSCRIBED_STATUS;
				}
			} else {
				//account exists but has never logged in, set status to inactive
				//$status	= INACTIVE_STATUS;
			}
						
		}
		
        return $status;	
		
	}
	
	// fetch classes listing for grid
    public function getClassGridListing($sch_id=NULL, $page=NULL, $search_text=NULL, $lperpage=NULL, $sort=NULL, $id=NULL, $admin=NULL, $user_id=NULL, $no_pagination=FALSE) { 

		$response = array();
		$result = array();
		
		$sortqry = "";
		//start sort
		if ($sort['id'] == "asc"){
			$sortqry = " id ";
		} else if ($sort['id'] == "desc") {
			$sortqry = " id DESC ";
		} 
		
		if (!$user_id) { $user_id = USER_ID; }
		
		$super_admin = $this->isSuperAdmin($user_id);
		if ($admin && !$super_admin) {
			$perms = ALL_STUDENT_PERMISSIONS; 
			$company_ids = $this->getUserCompanyIds($user_id, $perms, $sch_id); 
		}
		
		if ($super_admin || ($admin && $company_ids) || !$admin) {
			
		
			if (!$page){ $page=1; }
			if (!$lperpage) { $lperpage = 10; } //default num records
			$offset = ($page - 1) * $lperpage;
	
			//main query - get total number of records
			$queryMain = "SELECT DISTINCT current_class FROM sch_students WHERE id!='' AND current_class > 0 ";
			if ($search_text) { $queryMain .= " AND ($full_article_search_text) "; }
			if ($id) { $queryMain .= " AND id=$id "; }
			if ($company_ids) { $queryMain .= " AND sch_id IN ($company_ids) "; }
			if ($sch_id) { $queryMain .= " AND sch_id=$sch_id "; }
			//echo $queryMain . " $sch_id"; exit;
	
			$stmtMain = $this->conn->prepare($queryMain);
			//$stmtMain->bind_param("i", $sch_id);
			$stmtMain->execute();
			$stmtMain->store_result();
			$stmtMain->fetch();
			$total_recs = $stmtMain->num_rows;
			$stmtMain->close();
			
			//filtered recordset
			if ($sortqry) { $queryMain .= " ORDER BY $sortqry "; } else { $queryMain .= " ORDER BY current_class "; }//add sort query 
			
			if (!$no_pagination){
				if ($lperpage > 0) { $queryMain .= " LIMIT $offset,$lperpage"; } //echo "queryadd - $queryadd";
			}
			//echo "queryMain - $queryMain - $sch_id";exit;
			$query = $queryMain; 
			$stmt = $this->conn->prepare($query);
			$stmt->bind_param("i", $sch_id);
			$stmt->execute();
			$numberofrows = $stmt->num_rows;
			
			$stmt->bind_result($current_class);
			/* fetch values */
			while ( $stmt->fetch() ) {
				$tmp = array();

				$tmp["current_class"] = $current_class;			
				
				array_push($result, $tmp);
			}
			$response['rows'] = $result;
			$response['total'] = $total_recs;
			$response['rowCount'] = $lperpage;
			$response['current'] = $page;
			$stmt->close();
			
		}
		
		return $response; 

    }
	
	// fetch stream listing for grid
    public function getStreamGridListing($sch_id=NULL, $page=NULL, $search_text=NULL, $lperpage=NULL, $sort=NULL, $id=NULL, $admin=NULL, $user_id=NULL, $no_pagination=FALSE) { 

		$response = array();
		$result = array();
		
		$sortqry = "";
		//start sort
		if ($sort['id'] == "asc"){
			$sortqry = " id ";
		} else if ($sort['id'] == "desc") {
			$sortqry = " id DESC ";
		} 
		
		if (!$user_id) { $user_id = USER_ID; }
		
		$super_admin = $this->isSuperAdmin($user_id);
		if ($admin && !$super_admin) {
			$perms = ALL_STUDENT_PERMISSIONS; 
			$company_ids = $this->getUserCompanyIds($user_id, $perms, $sch_id); 
		}
		
		if ($super_admin || ($admin && $company_ids) || !$admin) {
			
		
			if (!$page){ $page=1; }
			if (!$lperpage) { $lperpage = 10; } //default num records
			$offset = ($page - 1) * $lperpage;
	
			//main query - get total number of records
			$queryMain = "SELECT DISTINCT stream FROM sch_students WHERE id!='' ";
			if ($search_text) { $queryMain .= " AND ($full_article_search_text) "; }
			if ($id) { $queryMain .= " AND id=$id "; }
			if ($company_ids) { $queryMain .= " AND sch_id IN ($company_ids) "; }
			if ($sch_id) { $queryMain .= " AND sch_id=$sch_id "; }
			//echo $queryMain . " $sch_id"; exit;
	
			$stmtMain = $this->conn->prepare($queryMain);
			//$stmtMain->bind_param("i", $sch_id);
			$stmtMain->execute();
			$stmtMain->store_result();
			$stmtMain->fetch();
			$total_recs = $stmtMain->num_rows;
			$stmtMain->close();
			
			//filtered recordset
			if ($sortqry) { $queryMain .= " ORDER BY $sortqry "; } else { $queryMain .= " ORDER BY stream "; }//add sort query 
			
			if (!$no_pagination){
				if ($lperpage > 0) { $queryMain .= " LIMIT $offset,$lperpage"; } //echo "queryadd - $queryadd";
			}
			//echo "queryMain - $queryMain - $sch_id";exit;
			$query = $queryMain; 
			$stmt = $this->conn->prepare($query);
			$stmt->bind_param("i", $sch_id);
			$stmt->execute();
			$numberofrows = $stmt->num_rows;
			
			$stmt->bind_result($stream);
			/* fetch values */
			while ( $stmt->fetch() ) {
				$tmp = array();

				$tmp["stream"] = $stream;			
				
				array_push($result, $tmp);
			}
			$response['rows'] = $result;
			$response['total'] = $total_recs;
			$response['rowCount'] = $lperpage;
			$response['current'] = $page;
			$stmt->close();
			
		}
		
		return $response; 

    }
	
	//fetch subjects
    public function getSubjectsListing($sch_id=NULL, $level_id=NULL, $search_text=NULL, $lperpage=NULL, $page=NULL, $sort=NULL, $user_id=NULL, $admin=NULL, $paginate=TRUE) { 

		$result = array();
		
		$sortqry = "";
		//start sort
		if ($sort['id'] == "asc"){
			$sortqry = " id ";
		} else if ($sort['id'] == "desc") {
			$sortqry = " id DESC ";
		}
		
		if (!$user_id){ $user_id = USER_ID; } 
		
		$super_admin = $this->isSuperAdmin($user_id);
		if ($admin && !$super_admin) {
			$perms = ALL_STUDENT_PERMISSIONS; 
			$company_ids = $this->getUserCompanyIds($user_id, $perms, $sch_id); 
		}
		
		if ($super_admin || ($admin && $company_ids) || !$admin) {
		
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
					$full_article_search_text .= " ss.code LIKE '%" . $split_text[$i] . "%' or ss.name LIKE '%" . "%' or";
				}
				//more than one search term i.e. spaces in between
				if ($num_items > 1){ 
					$full_article_search_text .= " ss.code LIKE '%" . $search_text . "%' or ss.name LIKE '%" . "%' or"; 
				} 
				//end more than one search term i.e. spaces in between
				$full_article_search_text = $this->removelastor($full_article_search_text);
			}			
	  
			//main query - get total number of records
			$queryMain = "SELECT ss.id, ss.code, ss.name FROM sch_subjects ss";
			$queryMain .= " JOIN sch_ussd su ON su.sch_level = ss.school_level";
			$queryMain .= " WHERE su.sch_id != '' ";
			if ($search_text) { $queryMain .= " AND ($full_article_search_text) "; }
			if ($level_id) { $queryMain .= " AND ss.school_level=$level_id "; }
			if ($company_ids) { $queryMain .= " AND su.sch_id IN ($company_ids) "; }
			if ($sch_id) { $queryMain .= " AND su.sch_id=$sch_id "; }
			//echo $queryMain; exit;
	
			$stmtMain = $this->conn->prepare($queryMain);
			//$stmtMain->bind_param("i", $sch_id);
			$stmtMain->execute();
			$stmtMain->store_result();
			$stmtMain->fetch();
			$total_recs = $stmtMain->num_rows;
						
			if ($sortqry) { $queryMain .= " ORDER BY $sortqry "; } else { $queryMain .= " ORDER BY ss.name "; }//add sort query
			 
			//filtered recordset if paginate is set
			if ($paginate) {
				if ($lperpage > 0) { $queryMain .= " LIMIT $offset,$lperpage"; }
			}
			
			$stmt = $this->conn->prepare($queryMain);
			$stmt->execute();
			$stmt->store_result();
			
			$stmt->bind_result($id, $code, $name);
			
			$numberofrows = $stmt->num_rows;
			
			/* fetch values */
			while ( $stmt->fetch() ) {
				
				$tmp = array();

				$tmp["id"] = $id;
				$tmp["code"] = $code;				
				$tmp["name"] = $name;
				
				array_push($result, $tmp);
				
			}
			
			print_r($result);
			
			$response['rows'] = $result;
			$response['total'] = $total_recs;
			$response['rowCount'] = $lperpage;
			$response['current'] = $page;
			$stmt->close();
			
		}
		
		return $response; 

    }
	
	// fetch students listing for grid
    public function fetchStudentResults($sch_id, $reg_no=NULL, $year=NULL, $term=NULL, $page=NULL, $search_text=NULL, $lperpage=NULL, $sort=NULL, $student_id=NULL, $user_id=NULL, $single_student_result=NULL, $id=NULL) { 

		$response = array();
		$results = array();
		$success = 1;
		
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
		
		if ($single_student_result && !$student_id){
			$success = 0;
		}
		
		if ($success) {

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
			$query = "SELECT sri.id, sri.score, sri.grade, sri.points, ss.name, ss.code, sr.mean_score, sr.grade ";
			$query .= ", sr.points, sr.total_score, st.full_names, sr.term, sr.year, st.current_class, st.stream ";
			$query .= " FROM sch_students st  ";
			$query .= " LEFT JOIN sch_results sr ON sr.student_id = st.id ";
			$query .= " JOIN sch_results_items sri ON sr.id = sri.result_id ";
			$query .= " JOIN sch_subjects ss ON sri.subject_code = ss.code ";
			$query .= " WHERE st.id!='' ";
			if ($id) { $query .= " AND sr.id = $id "; }
			if ($year) { $query .= " AND sr.year = $year "; }
			if ($term) { $query .= " AND sr.term = $term "; }
			if ($student_id) { $query .= " AND sr.student_id = $student_id "; }
			if ($reg_no && $sch_id) { $query .= " AND (sr.reg_no = '$reg_no' AND sr.sch_id = $sch_id ) "; }
			if ($search_text) { $query .= " AND ($full_article_search_text) "; }
			//echo $query . " - $sch_id, $year, $term";
	
			$stmtMain = $this->conn->prepare($query);
			//$stmtMain->bind_param("iii", $sch_id, $year, $term);
			$stmtMain->execute();
			$stmtMain->store_result();
			$stmtMain->fetch();
			$total_recs = $stmtMain->num_rows;
			$stmtMain->close();
			
			//filtered recordset
			if ($sortqry) { $query .= " ORDER BY $sortqry "; } else { $query .= " ORDER BY ss.name "; }//add sort query 
			$query .= " LIMIT $offset,$lperpage"; //echo "queryadd - $queryadd";
			//echo "queryMain - $queryMain";
			$stmt = $this->conn->prepare($query);
			//$stmt->bind_param("iii", $sch_id, $year, $term);
			$stmt->execute();
			$numberofrows = $stmt->num_rows;
			
			$stmt->bind_result($id, $score, $grade, $points, $subject_name, $subject_code, $mean_score, $mean_grade, $mean_points, $total_score, $student_full_names, $db_term, $db_year, $current_class, $stream);
			/* fetch values */
			while ( $stmt->fetch() ) {
				
				$tmp = array();
	
				$tmp["id"] = $id;
				$tmp["score"] = $this->format_num($score, 0);
				$tmp["grade"] = $grade;
				$tmp["points"] = $points;
				$tmp["name"] = $subject_name;
				$tmp["code"] = $subject_code;
				
				array_push($results, $tmp);
				
			}
			
			if (!$mean_score) { $mean_score = 0; }
			if (!$mean_grade) { $mean_grade = 0; }
			if (!$mean_points) { $mean_points = 0; }
			if (!$total_score) { $total_score = 0; }
			
			if ($student_id) { 
				$student_data_array = $this->getStudentData("", "", "", "", $student_id);
				$student_full_names = $student_data_array["student_full_names"];
				$current_class = $student_data_array["current_class"];
				$stream = $student_data_array["stream"];
			}
			
			//do we have db data?
			if ($db_term) { $term = $db_term; }
			if ($db_year) { $year = $db_year; }
			
			$response['rows'] = $results;
			$response['total'] = $total_recs;
			$response['rowCount'] = $lperpage;
			$response['current'] = $page;
			$response['student_full_names'] = $student_full_names;
			$response['term'] = $term;
			$response['year'] = $year;
			$response['current_class'] = $current_class;
			$response['stream'] = $stream;
			$response["mean_score"] = $this->format_num($mean_score, 1);
			$response["mean_grade"] = $mean_grade;
			$response["mean_points"] = $mean_points;
			$response["total_score"] = $this->format_num($total_score, 0);
			
			$stmt->close();	
		
		} else {
			
			$response['rows'] = "";
			$response['total'] = 0;
			$response['rowCount'] = 0;
			$response['current'] = 0;
			$response["mean_score"] = 0;
			$response["mean_grade"] = 0;
			$response["mean_points"] = 0;
			$response["total_score"] = 0;	
			
		}
				
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
		$queryMain = "SELECT sfp.id, sfp.amount, sfp.payment_mode, sfp.ref_no, sfp.paid_by, sfp.paid_at, sf.year, sf.student_id, sf.sch_id FROM sch_fees_payments sfp ";
		$queryMain .= " JOIN sch_fees sf ON sfp.fees_id=sf.id ";
		$queryMain .= " JOIN payment_modes pm ON pm.code=sfp.payment_mode ";
		$queryMain .= " WHERE sfp.id = ? "; 
		$stmtMain = $this->conn->prepare($queryMain);
		$stmtMain->bind_param("i", $fee_payment_id);
		$result = $stmtMain->execute();
		$stmtMain->store_result();
		$stmtMain->bind_result($id, $amount, $payment_mode, $ref_no, $paid_by, $paid_at, $year, $student_id, $sch_id);
		$stmtMain->fetch();
		if ($result) {	
			$response['id'] = $id;
			$response['amount'] = $amount;
			$response['payment_mode'] = $payment_mode;
			$response['ref_no'] = $ref_no;
			$response['paid_by'] = $paid_by;
			$response['paid_at'] = date("d/m/Y", $this->php_date($paid_at));
			$response['year'] = $year;
			$response['student_id'] = $student_id;
			$response['sch_id'] = $sch_id;
			$response['error'] = false;
		} else {
			$response['message'] = "No data available";
			$response['error'] = true;
		}
		$stmtMain->close();
		
		return $response; 

    }

	// edit single fee data
    public function editSingleFee($fee_payment_id, $amount, $payment_mode, $paid_by, $paid_at, $user_id=NULL) { 
		
		$response = array();
		
		if (!$this->isDataNumeric($amount)){
			
			$response['message'] = "Amount must be a number";
			$response['error'] = true;	
			
		} else {
			
			$updated_at = $this->getCurrentDate(); // get current date
			if (!$user_id) { $user_id = USER_ID; }
			$updated_by = $user_id;
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
				//$this->saveFeeItemArchiveData($fee_payment_id);				
				//update result summaries
				$this->updateFeeSummaryData($fees_id);
				
				//create fee entry history
				$this->saveStudentFeeHistory($fee_payment_id, $updated_by, $updated_at);
			
				$response['message'] = "Fee Entry updated";
				//$response['reload_grid'] = true;
				$response['reload_grid_history'] = true;
				$response['noty_msg'] = true;
				$response['error'] = false;
				
			} else {
				
				$response['message'] = "An error occured while updating data";
				$response['noty_msg'] = true;
				$response['error'] = true;
				
			}
			$stmtMain->close();
			
		}
		
		return $response; 

    }
	
	// edit single student
    public function editStudent($id, $full_names, $reg_no, $sch_id, $admin_date=NULL, $student_profile=NULL, $guardian_name=NULL, $guardian_phone=NULL, $guardian_address=NULL, $dob=NULL, $index_no=NULL, $nationality=NULL, $religion=NULL, $previous_school=NULL, $house=NULL, $club=NULL, $guardian_id_card=NULL, $guardian_relation=NULL, $guardian_occupation=NULL, $email=NULL, $town=NULL, $current_class=NULL, $village=NULL, $county=NULL, $location=NULL, $disability=NULL, $gender=NULL, $stream=NULL, $constituency=NULL, $user_id=NULL) { 
		
		$response = array();
		
		$sch_name = $this->getSchoolName($sch_id);
		
		$admin_date = $this->formatDate($admin_date);
		$dob = $this->formatDate($dob);
		
		if ($guardian_phone) {
			$guardian_phone = $this->formatPhoneNumber($guardian_phone);
		}
		
		if (!$full_names || !$reg_no) {
			
			$response["message"] = PLEASE_ENTER_REQUIRED_DATA_ERROR;
			$response["error"] = true;
			$response["ref"] = "none";
			
		} else {
			
			$created_at = $this->getCurrentDate(); // get current date
			
			if (!$user_id) {
				$user_id = USER_ID;
			}
			
			//update results
			$query = "UPDATE sch_students SET full_names = ?, reg_no = ?, admin_date = ?, student_profile = ?, guardian_name = ?";
			$query .= ", guardian_phone = ?, guardian_address = ?, dob = ?, index_no = ?, nationality = ?, religion = ?, previous_school = ?, house = ?";
			$query .= ", club = ?, guardian_id_card = ?, guardian_relation = ?, guardian_occupation = ?, email = ?, town = ?, current_class = ?, village = ?";
			$query .= ", county = ?, location = ?, disability = ?, gender = ?, stream = ?, constituency = ?, updated_by = ?, updated_at = ?";
			if ($sch_id) { $query .= " , sch_id = $sch_id "; }
			$query .= " WHERE id = ? ";
			
			//echo "$query - $full_names, $reg_no, $admin_date, $student_profile, $guardian_name, $guardian_phone, $guardian_address, $dob, $index_no, $nationality, $religion, $previous_school, $house, $club, $guardian_id_card, $guardian_relation, $guardian_occupation, $email, $town, $current_class, $village, $county, $location, $disability, $gender, $stream, $constituency, $user_id, $created_at, $id";
			
			$stmt = $this->conn->prepare($query);
			$stmt->bind_param("sssssssssssssssssssisisssssisi", $full_names, $reg_no, $admin_date, $student_profile, $guardian_name, $guardian_phone, $guardian_address, $dob, $index_no, $nationality, $religion, $previous_school, $house, $club, $guardian_id_card, $guardian_relation, $guardian_occupation, $email, $town, $current_class, $village, $county, $location, $disability, $gender, $stream, $constituency, $user_id, $created_at, $id);
			$result = $stmt->execute();
			
			if ($result) {	
				
				//save history data
				$this->saveStudentDataHistory($id, $user_id, $created_at);
			
				$response['message'] = DATA_SUCCESSFULLY_UPDATED_MESSAGE;
				$response['error'] = false;
				$response["reload_grid"] = true;
				$response["reload_grid_history"] = true;
				$response['noty_msg'] = true;
				
			} else {
				
				$response['message'] = FAILED_TO_UPDATE_RECORD_ERROR;
				//$response["message"] = $this->conn->error;
				$response['error'] = true;
				$response["reload_grid"] = true;
				$response['noty_msg'] = true;
				
			}
			$stmt->close();
			
		}
		
		return $response; 

    }
	
	//reformat date
	function formatDate($date) {
			
		//format the date
		$dob_data = explode("/", $date);
		$day = $dob_data[0];
		$month = $dob_data[1];
		$year = $dob_data[2];
		$date=mktime(00, 00, 00, $month, $day, $year);
		$dob_date = date("Y-m-d", $date); //n - month with no leading zeros, j - day with no leading zeros
		//end format the date
		return $dob_date;

	}
	
	// edit single school
    public function editSchool($id, $name, $sch_first_name, $sch_level, $sch_category, $province, $sch_county, $status, $motto, $phone1, $phone2, $sms_welcome1, $sms_welcome2, $address, $sch_profile, $sch_paybill_no, $user_id) { 
		
		$response = array();

		$sch_id = $id;
		
		$sch_first_name = $this->removeAllSpecialCharacters($sch_first_name);
		
		if (!$id || !$name){
			
			$response['message'] = PLEASE_ENTER_REQUIRED_DATA_ERROR;
			$response['error'] = true;	
			$response["noty_msg"] = true;
			
		} else if ((SUPER_ADMIN_USER) &&($sch_first_name && $this->schFirstNameExists($sch_first_name, $id))){
			
			$response['message'] = "School First name \"$sch_first_name\" is already used for another school.<br> Try another name";
			$response['error'] = true;
			$response["noty_msg"] = true;
			
		} else {
			
			//verify permissions
			
			if (!$user_id) { $user_id = USER_ID; }
				
			//check user permissions
			$super_admin = $this->isSuperAdmin($user_id);
			if ($admin && !$super_admin) {
				$perms = ALL_SCHOOL_PERMISSIONS; 
				$company_ids = $this->getUserCompanyIds($user_id, $perms, $sch_id); 
			}
			//echo "yes ... ";		
			if ($super_admin || ($admin && $company_ids || SCHOOL_ADMIN_USER)) {
			
				$created_at = $this->getCurrentDate(); // get current date
				
				if (!$user_id) {
					$user_id = USER_ID;
				}
							
				//update school data
				$query = "UPDATE sch_ussd SET sch_name = ?, sch_level = ?, sch_category = ?, sch_province = ?, sch_county = ?, status = ?, motto = ?, sch_profile = ?";
				$query .= ", sch_paybill_no = ?, phone1 = ?, phone2 = ?, address = ?, updated_at = ?, updated_by = ? ";
				
				if (SUPER_ADMIN_USER && $sch_first_name) { $query .= ", sch_first_name = '$sch_first_name' "; }
				
				$query .= " WHERE sch_id = ? ";
				
				//echo "$query - $name, $sch_level, $sch_category, $province, $sch_county, $status, $motto, $sch_profile, $sch_paybill_no, $phone1, $phone2, $address, $created_at, $user_id, $id";
				
				if ($stmt = $this->conn->prepare($query)) {
					$stmt->bind_param("siiiiisssssssii", $name, $sch_level, $sch_category, $province, $sch_county, $status, $motto, $sch_profile, $sch_paybill_no, $phone1, $phone2, $address, $created_at, $user_id, $id);
					$result = $stmt->execute();
					if ($result) {	
						
						//save history data
						$this->saveSchoolDataHistory($id, $user_id, $created_at);
					
						$response['message'] = DATA_SUCCESSFULLY_UPDATED_MESSAGE;
						$response['error'] = false;
						$response["reload_grid"] = true;
						$response['noty_msg'] = true;
						
					} else {
						
						$response['message'] = FAILED_TO_UPDATE_RECORD_ERROR;
						//$response["message"] = $this->conn->error;
						$response['error'] = true;
						$response["reload_grid"] = true;
						$response['noty_msg'] = true;
						
					}
					
					$stmt->close();
					
				} else {
				
					$response["error"] = true;
					$response["message"] = $this->conn->error;
					//$response["message"] = AN_ERROR_OCCURED_MESSAGE;
					$response['error_type'] = AN_ERROR_OCCURED_ERROR;
						
				}
				
			} else {
				
				$response['rows'] = "";
				$response['total'] = 0;
				$response['rowCount'] = 0;
				$response["error"] = true;
				$response["message"] = INVALID_ACCESS_ERROR_MESSAGE;
					
			}
			
		}
			
		return $response; 

    }
	
		
	//save total score grade history
	public function saveTotalScoreGradeHistory($id, $created_by, $created_at) {
        				
		$response = array();
				
		if (!$id) {
			
			$response["message"] = PLEASE_ENTER_REQUIRED_DATA_ERROR;
			$response["error"] = true;
			$response["noty_msg"] = true;
			$response["ref"] = "none";
			
		} else {

			//get school data
			$item_data_rows = $this->fetchTotalScoreGradeGridListing("", "", "", "", "", $id);
			$item_data = $item_data_rows["rows"][0];			
			$id = $item_data["id"];
			$min = $item_data["min"];
			$max = $item_data["max"];
			$points = $item_data["points"];
			$grade = $item_data["grade"]; 
			$level = $item_data["level_id"];
			
			// insert query
			$query = "INSERT INTO total_points_grades_history(total_score_grade_id, min, max, points, grade, sch_level, created_by, created_at) VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
			
			if ($stmt = $this->conn->prepare($query)){
				$stmt->bind_param("iiiisiis", $id, $min, $max, $points, $grade, $level, $created_by, $created_at);
				$result = $stmt->execute();
				$stmt->close();
				
				// Check for successful insertion
				if ($result) {
		
					$response["error"] = false;
					$response["message"] = "Total Score grade history successfully created";
					$response["reload_grid"] = true;
					$response['noty_msg'] = true;

				} else {
					
					$response["error"] = true;
					$response['noty_msg'] = true;
					$response["error_type"] = ERROR_OCCURED;
					$response["message"] = AN_ERROR_OCCURED_MESSAGE;
					
				}
			
			} else {
				
				$response["error"] = true;
				$response["message"] = AN_ERROR_OCCURED_MESSAGE;
				$response['error_type'] = AN_ERROR_OCCURED_ERROR;
				
			}
		
		}
 
        return $response;
    }
	
	
	//save score grade history
	public function saveScoreGradeHistory($id, $created_by, $created_at) {
        				
		$response = array();
				
		if (!$id) {
			
			$response["message"] = PLEASE_ENTER_REQUIRED_DATA_ERROR;
			$response["error"] = true;
			$response["noty_msg"] = true;
			$response["ref"] = "none";
			
		} else {

			//get school data
			$item_data_rows = $this->fetchScoreGradeGridListing("", "", "", "", "", $id);
			$item_data = $item_data_rows["rows"][0];			
			$id = $item_data["id"];
			$min = $item_data["min"];
			$max = $item_data["max"];
			$points = $item_data["points"];
			$grade = $item_data["grade"]; 
			$level = $item_data["level_id"];
			
			// insert query
			$query = "INSERT INTO score_grades_history(score_grade_id, min, max, points, grade, sch_level, created_by, created_at) VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
			
			if ($stmt = $this->conn->prepare($query)){
				$stmt->bind_param("iiiisiis", $id, $min, $max, $points, $grade, $level, $created_by, $created_at);
				$result = $stmt->execute();
				$stmt->close();
				
				// Check for successful insertion
				if ($result) {
		
					$response["error"] = false;
					$response["message"] = "Score grade history successfully created";
					$response["reload_grid"] = true;
					$response['noty_msg'] = true;

				} else {
					
					$response["error"] = true;
					$response['noty_msg'] = true;
					$response["error_type"] = ERROR_OCCURED;
					$response["message"] = AN_ERROR_OCCURED_MESSAGE;
					
				}
			
			} else {
				
				$response["error"] = true;
				$response["message"] = AN_ERROR_OCCURED_MESSAGE;
				$response['error_type'] = AN_ERROR_OCCURED_ERROR;
				
			}
		
		}
 
        return $response;
    }
	
	
	//save subject history
	public function saveSubjectHistory($id, $created_by, $created_at) {
        				
		$response = array();
				
		if (!$id) {
			
			$response["message"] = PLEASE_ENTER_REQUIRED_DATA_ERROR;
			$response["error"] = true;
			$response["noty_msg"] = true;
			$response["ref"] = "none";
			
		} else {

			//get school data
			$item_data_rows = $this->getSubjectGridListing("", "", "", "", "", $id);
			$item_data = $item_data_rows["rows"][0];
			//echo $id;
			//print_r($item_data); exit;
			
			$id = $item_data["id"];
			$subject_name = $item_data["name"];
			$short_name = $item_data["short_name"];
			$code = $item_data["code"]; 
			$level = $item_data["level_id"];
			$status = $item_data["status"];
			
			// insert query
			$query = "INSERT INTO sch_subjects_history(subject_id, name, short_name, code, school_level, status, created_by, created_at) VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
			//echo "$query - $id, $subject_name, $short_name, $perm, $level, $created_by, $created_at"; exit;
			
			if ($stmt = $this->conn->prepare($query)){
				$stmt->bind_param("isssiiis", $id, $subject_name, $short_name, $code, $level, $status, $created_by, $created_at);
				$result = $stmt->execute();
				$stmt->close();
				
				// Check for successful insertion
				if ($result) {
		
					$response["error"] = false;
					$response["message"] = "Subject history successfully created";
					$response["reload_grid"] = true;
					$response['noty_msg'] = true;

				} else {
					
					$response["error"] = true;
					$response['noty_msg'] = true;
					$response["error_type"] = ERROR_OCCURED;
					$response["message"] = AN_ERROR_OCCURED_MESSAGE;
					
				}
			
			} else {
				
				$response["error"] = true;
				$response["message"] = AN_ERROR_OCCURED_MESSAGE;
				$response['error_type'] = AN_ERROR_OCCURED_ERROR;
				
			}
		
		}
 
        return $response;
    }
		
	//save student history data
	public function saveStudentDataHistory($id, $created_by, $created_at) {
        				
		$response = array();
				
		if (!$id) {
			
			$response["message"] = PLEASE_ENTER_REQUIRED_DATA_ERROR;
			$response["error"] = true;
			$response["noty_msg"] = true;
			$response["ref"] = "none";
			
		} else {

			//get school data
			$student_data_rows = $this->getStudentGridListing("", "", "", "", "", $id);
			$student_data = $student_data_rows["rows"][0];
			//echo $id;
			//print_r($student_data); exit;
			
			$full_names = $student_data["full_names"];
			$reg_no = $student_data["reg_no"];
			$guardian_name = $student_data["guardian_name"];
			$dob = $student_data["dob"]; 
			$admin_date = $student_data["admin_date"];
			$index_no = $student_data["index_no"];
			$nationality = $student_data["nationality"];
			$religion = $student_data["religion"];
			$previous_school = $student_data["previous_school"];
			$house = $student_data["house"]; 
			$club = $student_data["club"];
			$guardian_id_card = $student_data["guardian_id_card"];
			$guardian_relation = $student_data["guardian_relation"];
			$guardian_occupation = $student_data["guardian_occupation"];
			$email = $student_data["email"];
			$town = $student_data["town"];
			$current_class = $student_data["current_class"];
			$town = $student_data["town"]; 
			$village = $student_data["village"];
			$county = $student_data["county"];
			$location = $student_data["location"];
			$disability = $student_data["disability"];
			$gender = $student_data["gender"];
			$stream = $student_data["stream"]; 
			$constituency = $student_data["constituency"];
			$student_profile = $student_data["student_profile"];
			$guardian_address = $student_data["guardian_address"];
			$guardian_phone = $student_data["guardian_phone"];

			// insert query
			$query = "INSERT INTO sch_students_history(sch_student_id, full_names, reg_no, sch_id, admin_date, student_profile, guardian_name";
			$query .= ", guardian_phone, guardian_address, dob, index_no, nationality, religion, previous_school, house";
			$query .= ", club, guardian_id_card, guardian_relation, guardian_occupation, email, town, current_class, village";
			$query .= ", county, location, disability, gender, stream, constituency, created_by, created_at)";
			$query .= " VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; 			
			
			if ($stmt = $this->conn->prepare($query)){
				$stmt->bind_param("ississsssssssssssssssisisssssis", $id, $full_names, $reg_no, $sch_id, $admin_date, $student_profile, $guardian_name, $guardian_phone, $guardian_address, $dob, $index_no, $nationality, $religion, $previous_school, $house, $club, $guardian_id_card, $guardian_relation, $guardian_occupation, $email, $town, $current_class, $village, $county, $location, $disability, $gender, $stream, $constituency, $created_by, $created_at);
				$result = $stmt->execute();
				$stmt->close();
				
				// Check for successful insertion
				if ($result) {
		
					$response["error"] = false;
					$response["message"] = "Student history successfully created";
					$response["reload_grid"] = true;
					$response['noty_msg'] = true;

				} else {
					
					$response["error"] = true;
					$response['noty_msg'] = true;
					$response["error_type"] = ERROR_OCCURED;
					$response["message"] = "An error occurred whle creating student history";
					
				}
			
			} else {
				
				$response["error"] = true;
				$response["message"] = $this->conn->error;
				$response['error_type'] = AN_ERROR_OCCURED_ERROR;
				
			}
		
		}
 
        return $response;
    }
	
	//save student history data
	public function saveStudentFeeHistory($id, $created_by, $created_at) {
        				
		$response = array();
				
		if (!$id) {
			
			$response["message"] = PLEASE_ENTER_REQUIRED_DATA_ERROR;
			$response["error"] = true;
			$response["noty_msg"] = true;
			$response["ref"] = "none";
			
		} else {

			//get school data
			$fee_payment_rows = $this->getStudentFeePayments("", "", "", "", "", "", "", "", "", "", $id);
			$fee_payment_data = $fee_payment_rows["rows"][0];
			//print_r($fee_payment_rows); exit;
			
			$student_id = $fee_payment_data["payment_student_id"];
			$fees_id = $fee_payment_data["payment_fees_id"];
			$amount = $fee_payment_data["payment_amount"];
			$payment_mode = $fee_payment_data["payment_mode_code"]; 
			$ref_no = $fee_payment_data["ref_no"];
			$status = $fee_payment_data["status"]; 
			$paid_at = $fee_payment_data["payment_paid_at"];
			$paid_by = $fee_payment_data["payment_paid_by"];
			
			// insert query				
			$query = "INSERT INTO sch_fees_payments_history(fees_id, fees_payments_id, amount, payment_mode, ref_no, status, paid_by, paid_at, created_by, created_at) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
			//echo "$query - $fees_id, $id, $amount, $payment_mode, $paid_by, $paid_at, $created_by, $created_at"; exit;
			
			if ($stmt = $this->conn->prepare($query)){
				
				$stmt->bind_param("iiississis", $fees_id, $id, $amount, $payment_mode, $ref_no, $status, $paid_by, $paid_at, $created_by, $created_at);
				$result = $stmt->execute();
				$stmt->close();
				
				// Check for successful insertion
				if ($result) {
		
					$response["error"] = false;
					$response["message"] = "Fee payment history successfully created";
					$response["reload_grid"] = true;
					$response['noty_msg'] = true;

				} else {
					
					$response["error"] = true;
					$response['noty_msg'] = true;
					$response["error_type"] = ERROR_OCCURED;
					$response["message"] = "An error occurred whle creating fee payment history";
					
				}
			
			} else {
				
				$response["error"] = true;
				$response["message"] = $this->conn->error;
				$response['error_type'] = AN_ERROR_OCCURED_ERROR;
				
			}
		
		}
 
        return $response;
		
    }	


	//save school history data
	public function saveSchoolDataHistory($id, $created_by, $created_at) {
        				
		$response = array();
				
		if (!$id) {
			
			$response["message"] = PLEASE_ENTER_REQUIRED_DATA_ERROR;
			$response["error"] = true;
			$response["noty_msg"] = true;
			$response["ref"] = "none";
			
		} else {

			//get school data
			$school_data_rows = $this->getSchoolGridListing("", "", "", "", "", $id);
			$school_data = $school_data_rows["rows"][0];
			//print_r($school_data);
			
			$id = $school_data["id"];
			$sch_name = $school_data["sch_name"];
			$sch_first_name = $school_data["sch_first_name"];
			$sch_level = $school_data["level"];
			$address = $school_data["address"];
			$sch_category = $school_data["category"]; 
			$extra = $school_data["extra"];
			$sch_profile = $school_data["profile"];
			$sch_paybill_no = $school_data["paybill_no"];
			$phone1 = $school_data["phone1"];
			$phone2 = $school_data["phone2"];
			$motto = $school_data["motto"]; 
			$sms_welcome1 = $school_data["sms_welcome1"];
			$sms_welcome2 = $school_data["sms_welcome2"];
			$status = $school_data["status"];
			$province = $school_data["province"];
			$sch_county = $school_data["county"];

			// insert query	
			$query = "INSERT INTO sch_ussd_history(sch_ussd_id, sch_name, sch_first_name, sch_level, sch_category, sch_province, sch_county, status, motto";
			$query .= ", sch_profile, sch_paybill_no, phone1, phone2, sms_welcome1, sms_welcome2, address, created_at, created_by";
			$query .= ") VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; 
			//echo "$query";
			
			if ($stmt = $this->conn->prepare($query)){
				$stmt->bind_param("issiiiiisssssssssi", $id, $sch_name, $sch_first_name, $sch_level, $sch_category, $province, $sch_county, $status, $motto, $sch_profile, $sch_paybill_no, $phone1, $phone2, $sms_welcome1, $sms_welcome2, $address, $created_at, $created_by);
				$result = $stmt->execute();
				$stmt->close();
				
				// Check for successful insertion
				if ($result) {
		
					$response["error"] = false;
					$response["message"] = "School history successfully created";
					$response["reload_grid"] = true;
					$response['noty_msg'] = true;

				} else {
					
					$response["error"] = true;
					$response['noty_msg'] = true;
					$response["error_type"] = ERROR_OCCURED;
					$response["message"] = "An error occurred whle creating school history";
					
				}
			
			} else {
				
				$response["error"] = true;
				$response["message"] = $this->conn->error;
				$response['error_type'] = AN_ERROR_OCCURED_ERROR;
				
			}
		
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
			$grade = $this->getSubjectGrade($score, $sch_level);
			
			//update results
			$query = "UPDATE sch_results_items SET score = ?, grade = ?, points = ?, updated_at = ?, updated_by = ? WHERE id = ? ";
			
			$stmtMain = $this->conn->prepare($query);
			$stmtMain->bind_param("dsisii", $score, $grade, $points, $updated_at, $updated_by, $result_item_id);
			$result = $stmtMain->execute();
			
			if ($result) {	
				
				//get result id
				$result_id = $this->getResultId($result_item_id);
				
				//save archive data
				$res = $this->saveResultItemHistory($result_item_id, $updated_at, $updated_by);
				//echo $res;
				//print_r($res);exit;
				
				//update result summaries
				$this->updateResultSummaryData($result_id, $sch_id);
			
				$response['message'] = "Data updated";
				$response['noty_msg'] = true;
				$response['id'] = intval($result_id);
				$response['reload_grid'] = true;
				$response['reload_grid_history'] = true;
				$response['error'] = false;
				
			} else {
				
				$response['message'] = "An error occured while updating data";
				$response['noty_msg'] = true;
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
	
	//fetch student data history
	public function getStudentHistory($page=NULL, $user_id=NULL, $lperpage=NULL, $admin=NULL, $id=NULL) {

		$response = array();
		$result = array();
		$success = 1;
		
		$user_group = $this->getUserGroupId($user_id);
		
		$total_sum = 0;
		
		$perms = ALL_STUDENT_PERMISSIONS;
		
		if (!$user_id){ $user_id = USER_ID; } 
		
		if (($user_group==SUPER_ADMIN_USER_ID) || ($user_group==SCHOOL_ADMIN_USER_ID)) 
		{ 
			
			$success = 1; 
			
		} else {
			
			if ($admin) {
				//check whether user has admin permissions on the data
				if ($sch_id && $admin && (!$this->getPermisssionData($user_id, $sch_id, $perms))) {
					$response["message"] = INVALID_ACCESS_ERROR_MESSAGE;
					$response["error_type"] = INVALID_ACCESS_ERROR;
					$response["error"] = true;
					$success = 0;
				} 
			} else {
				//user is not admin, check whether user is subscribed to this student to view data	
			}
		}
		
		if ($success) {
				
				if (!$page){ $page=1; }
				if (!$lperpage){ $lperpage = 20; } //default num records
				$offset = ($page - 1) * $lperpage;		
		
				//main query
				$query = "SELECT id, full_names, reg_no, guardian_name, dob, admin_date, index_no, nationality, religion, previous_school, house";
				$query .= ", club, guardian_id_card, guardian_relation, guardian_occupation, current_class, email, town, village, county, location";
				$query .= ", disability, gender, stream, constituency, student_profile, guardian_address, guardian_phone, created_at, created_by ";
				$query .= " FROM sch_students_history ";
				$query .= " WHERE sch_student_id = $id ";
				
				//total records
				$stmtMain = $this->conn->prepare($query);
				$stmtMain->execute();
				$stmtMain->store_result();
				$stmtMain->fetch();
				$total_recs = $stmtMain->num_rows;
				$stmtMain->close();
				//end total records
				
				//filtered recordset
				if ($sortqry) { $query .= " ORDER BY $sortqry "; } else { $query .= " ORDER BY created_at DESC"; }
				
				$query .= " LIMIT $offset,$lperpage"; //echo "query - $query"; exit;
				
				if ($stmt = $this->conn->prepare($query)){
					
					//$stmt->bind_param("i", $student_id);
					$stmt->execute();
					$stmt->store_result();
					/* fetch values */
					$stmt->bind_result($id, $full_names, $reg_no, $guardian_name, $dob, $admin_date, $index_no, $nationality, $religion, $previous_school, $house, $club, $guardian_id_card, $guardian_relation, $guardian_occupation, $current_class, $email, $town, $village, $county, $location, $disability, $gender, $stream, $constituency, $student_profile, $guardian_address, $guardian_phone, $created_at, $created_by);

					while ( $stmt->fetch() ) {
						
						$total_sum = $total_sum + $order_total;
						
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
						$tmp["created_at"] = $this->adjustDate(NULL, $this->php_date($created_at), NULL);
						$tmp["created_at_fmt"] = $this->adjustDate("d/m/Y", $this->php_date($created_at), NULL);
						$tmp["created_by"] = $this->getFullNames($created_by);
			
						array_push($result, $tmp);
						
					}
					
					$stmt->close();
					
					$response['rows'] = $result;
					$response['est_name'] = $sch_id;
					$response['total'] = $total_recs;
					$response["totalFmt"] = $this->format_num($total_recs, 0);
					$response['rowCount'] = $limit;
					$response['current'] = $page;
				
				} else {
					
					//$response["query"] = $query;
					$response["error"] = true;
					$response["message"] = AN_ERROR_OCCURED_MESSAGE;
					$response['error_type'] = AN_ERROR_OCCURED_ERROR;
					
				}
							
		} else {
			$response['rows'] = "";
			$response['total'] = 0;
			$response['rowCount'] = 0;
			$response["error"] = true;
			$response["message"] = INVALID_ACCESS_ERROR_MESSAGE;	
		}
				
		return $response; 

    }
	
	//fetch student fee payments history
	public function fetchStudentResultsHistory($page=NULL, $user_id=NULL, $lperpage=NULL, $admin=NULL, $id=NULL, $student_id=NULL, $year=NULL, $sort=NULL, $search_text=NULL, $term=NULL) {

		$response = array();
		$results = array();
		$success = 1;
		
		$user_group = $this->getUserGroupId($user_id);
		
		$total_sum = 0;
		
		$perms = ALL_RESULT_PERMISSIONS;
		
		if (!$user_id){ $user_id = USER_ID; } 
		
		if (($user_group==SUPER_ADMIN_USER_ID) || ($user_group==SCHOOL_ADMIN_USER_ID)) 
		{ 
			
			$success = 1; 
			
		} else {
			
			if ($admin) {
				//check whether user has admin permissions on the data
				if ($sch_id && $admin && (!$this->getPermisssionData($user_id, $sch_id, $perms))) {
					$response["message"] = INVALID_ACCESS_ERROR_MESSAGE;
					$response["error_type"] = INVALID_ACCESS_ERROR;
					$response["error"] = true;
					$success = 0;
				} 
			} else {
				//user is not admin, check whether user is subscribed to this student to view data	
			}
		}
		
		if ($success) {
			
				$sortqry = "";
				//start sort
				if ($sort['name'] == "asc") {
					$sortqry = " ss.name ";
				} else if ($sort['name'] == "desc") {
					$sortqry = " ss.name DESC ";
				} else if ($sort['score'] == "asc") {
					$sortqry = " sri.score ";
				} else if ($sort['score'] == "desc") {
					$sortqry = " sri.score DESC ";
				} else if ($sort['grade'] == "asc") {
					$sortqry = " sri.grade ";
				} else if ($sort['grade'] == "desc") {
					$sortqry = " sri.grade DESC ";
				} else if ($sort['created_at'] == "asc") {
					$sortqry = " sri.created_at ";
				} else if ($sort['created_at'] == "desc") {
					$sortqry = " sri.created_at DESC "; 
				} else if ($sort['created_by'] == "asc") {
					$sortqry = " sri.created_by ";
				} else if ($sort['created_by'] == "desc") {
					$sortqry = " sri.created_by DESC "; 
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
						$full_article_search_text .= " ss.name LIKE '%" . $split_text[$i] . "%' or sri.grade LIKE '%" . $split_text[$i] . "%' or";
					}
					//more than one search term i.e. spaces in between
					if ($num_items > 1){ 
						$full_article_search_text .= " ss.name LIKE '%" . $search_text . "%' or sri.grade LIKE '%" . $search_text . "%' or"; 
					} 
					//end more than one search term i.e. spaces in between
					$full_article_search_text = $this->removelastor($full_article_search_text);
				}
				
				
				if (!$page){ $page=1; }
				if (!$lperpage){ $lperpage = 20; } //default num records
				$offset = ($page - 1) * $lperpage;	
				
				
				//main query - get total number of records
				$query = "SELECT sri.id, sri.score, sri.grade, sri.points, ss.name, sr.mean_score, sr.grade, sr.points";
				$query .= ", sr.total_score, sri.created_at, sri.created_by FROM sch_results_items_history sri ";
				$query .= " JOIN sch_subjects ss ON sri.subject_code = ss.code ";
				$query .= " JOIN sch_results sr ON sr.id = sri.result_id ";
				$query .= " WHERE sri.id!='' ";
				if ($id) { $query .= " AND sr.id = $id "; }
				if ($year) { $query .= " AND sr.year = $year "; }
				if ($term) { $query .= " AND sr.term = $term "; }
				if ($student_id) { $query .= " AND sr.student_id = $student_id "; }
				if ($search_text) { $query .= " AND ($full_article_search_text) "; }
				//echo $query;
		
				if ($stmtMain = $this->conn->prepare($query)){

					$stmtMain->execute();
					$stmtMain->store_result();
					$stmtMain->fetch();
					$total_recs = $stmtMain->num_rows;
					$stmtMain->close();
					
					//filtered recordset
					if ($sortqry) { $query .= " ORDER BY $sortqry "; } else { $query .= " ORDER BY sri.id DESC "; }//add sort query 
					$query .= " LIMIT $offset,$lperpage"; //echo "queryadd - $queryadd";
					//echo "queryMain - $queryMain";
					$stmt = $this->conn->prepare($query);
					//$stmt->bind_param("iii", $sch_id, $year, $term);
					$stmt->execute();
					$stmt->store_result();
					$numberofrows = $stmt->num_rows;
					
					$stmt->bind_result($id, $score, $grade, $points, $subject_name, $mean_score, $mean_grade, $mean_points, $total_score, $created_at, $created_by);
					/* fetch values */
					while ( $stmt->fetch() ) {
						
						$tmp = array();
			
						$tmp["id"] = $id;
						$tmp["score"] = $this->format_num($score, 0);
						$tmp["grade"] = $grade;
						$tmp["points"] = $points;
						$tmp["name"] = $subject_name;
						$tmp["created_at"] = $created_at;
						$tmp["created_at_fmt"] = $this->adjustDate(DATE_FMT1, $this->php_date($created_at));
						$tmp["created_at_fmt3"] = $this->adjustDate(DATE_FMT3, $this->php_date($created_at));
						$tmp["created_by"] = $created_by;
						$tmp["created_by_name"] = $this->getFullNames($created_by);
						
						array_push($results, $tmp);
						
					}
					
					if (!$mean_score) { $mean_score = 0; }
					if (!$mean_grade) { $mean_grade = 0; }
					if (!$mean_points) { $mean_points = 0; }
					if (!$total_score) { $total_score = 0; }
					
					$response['query'] = $query;
					$response['rows'] = $results;
					$response['total'] = $total_recs;
					$response['rowCount'] = $lperpage;
					$response['current'] = $page;
					$response["mean_score"] = $this->format_num($mean_score, 1);
					$response["mean_grade"] = $mean_grade;
					$response["mean_points"] = $mean_points;
					$response["total_score"] = $this->format_num($total_score, 0);				
				
				} else {
					
					$response['rows'] = "";
					$response["error"] = true;
					$response["message"] = AN_ERROR_OCCURED_MESSAGE;
					$response['error_type'] = AN_ERROR_OCCURED_ERROR;
					
				}
							
		} else {

			$response["error"] = true;
			$response["message"] = INVALID_ACCESS_ERROR_MESSAGE;
			$response['rows'] = "";
			$response['total'] = 0;
			$response['rowCount'] = 0;
			$response['current'] = 0;
			$response["mean_score"] = 0;
			$response["mean_grade"] = 0;
			$response["mean_points"] = 0;
			$response["total_score"] = 0;	
		}
				
		return $response; 

    }
	
		
	//fetch total score grade history
	public function fetchTotalScoreGradeHistory($page=NULL, $user_id=NULL, $lperpage=NULL, $admin=NULL, $id=NULL, $sort=NULL, $search_text=NULL) {

		$response = array();
		$results = array();
		$success = 1;
		
		//check user permissions
		if (!$user_id) { $user_id = USER_ID; }
		$super_admin = $this->isSuperAdmin($user_id);

		if ($super_admin) {
			
				$sortqry = "";
				//start sort
				if ($sort['name'] == "asc") {
					$sortqry = " ss.name ";
				} else if ($sort['name'] == "desc") {
					$sortqry = " ss.name DESC ";
				} else if ($sort['short_name'] == "asc") {
					$sortqry = " short_name ";
				} else if ($sort['short_name'] == "desc") {
					$sortqry = " short_name DESC ";
				} else if ($sort['code'] == "asc") {
					$sortqry = " ss.code ";
				} else if ($sort['ss.code'] == "desc") {
					$sortqry = " code DESC ";
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
						$full_article_search_text .= " ss.name LIKE '%" . $split_text[$i] . "%' or short_name LIKE '%" . $split_text[$i] . "%' or sl.name LIKE '%" . $split_text[$i] . "%' or";
					}
					//more than one search term i.e. spaces in between
					if ($num_items > 1){ 
						$full_article_search_text .= " ss.name LIKE '%" . $search_text . "%' or short_name LIKE '%" . $search_text . "%' or sl.name LIKE '%" . $search_text . "%' or"; 
					} 
					//end more than one search term i.e. spaces in between
					$full_article_search_text = $this->removelastor($full_article_search_text);
				}
				
				
				if (!$page){ $page=1; }
				if (!$lperpage){ $lperpage = 20; } //default num records
				$offset = ($page - 1) * $lperpage;		
				
				//main query - get total number of records
				$query = "SELECT sg.id, sg.min, sg.max, sg.grade, sg.points, sg.sch_level, sl.name";
				$query .= " , sg.created_at, sg.created_by, cl.full_names ";
				$query .= " FROM total_points_grades_history sg ";
				$query .= " LEFT JOIN  sch_levels sl ON sg.sch_level = sl.id ";
				$query .= " LEFT JOIN  clients cl ON sg.created_by = cl.id ";
				$query .= " WHERE sg.id != '' ";
				if ($id) { $query .= " AND sg.total_score_grade_id = $id "; }
				if ($level_id) { $query .= " AND sg.sch_level = $level_id "; }
				if ($search_text) { $query .= " AND ($full_article_search_text) "; }
				//echo $query; //exit;
		
				$stmtMain = $this->conn->prepare($query);
				$stmtMain->execute();
				$stmtMain->store_result();
				$stmtMain->fetch();
				$total_recs = $stmtMain->num_rows;
				$stmtMain->close();
				
				//filtered recordset
				if ($sortqry) { $query .= " ORDER BY $sortqry "; } else { $query .= " ORDER BY sg.id DESC "; }//add sort query 
				$query .= " LIMIT $offset,$lperpage"; //echo "queryadd - $queryadd";
				//echo "query - $query"; 
				
				$stmt = $this->conn->prepare($query);
				
				if ($stmt = $this->conn->prepare($query)){
					
					$stmt->execute();
					$numberofrows = $stmt->num_rows;
					
					$stmt->bind_result($id, $min, $max, $grade, $points, $level_id, $level, $created_at, $created_by, $created_by_name);
					/* fetch values */
					while ( $stmt->fetch() ) {
						
						$tmp = array();
						
						$tmp["id"] = $id;
						$tmp["min"] = $min;
						$tmp["max"] = $max;
						$tmp["grade"] = $grade;
						$tmp["points"] = $points;
						$tmp["level_id"] = $level_id;
						$tmp["level"] = $level;
						$tmp["created_at"] = $this->adjustDate("", $this->php_date($created_at));
						$tmp["created_at_fmt"] = $this->adjustDate("d-M-Y, H:i", $this->php_date($created_at));
						$tmp["created_by"] = $created_by;
						$tmp["created_by_name"] = $created_by_name;
						
						array_push($results, $tmp);
						
					}
					$response['rows'] = $results;
					$response['total'] = $total_recs;
					$response['rowCount'] = $lperpage;
					$response['current'] = $page;
					$stmt->close();
									
				} else {
					
					//$response["query"] = $query;
					$response["error"] = true;
					$response["message"] = AN_ERROR_OCCURED_MESSAGE;
					$response['error_type'] = AN_ERROR_OCCURED_ERROR;
					
				}
							
		} else {
			$response['rows'] = "";
			$response['total'] = 0;
			$response['rowCount'] = 0;
			$response["error"] = true;
			$response["message"] = INVALID_ACCESS_ERROR_MESSAGE;	
		}
				
		return $response; 

    }
	
	
	//fetch score grade history
	public function fetchScoreGradeHistory($page=NULL, $user_id=NULL, $lperpage=NULL, $admin=NULL, $id=NULL, $sort=NULL, $search_text=NULL) {

		$response = array();
		$results = array();
		$success = 1;
		
		//check user permissions
		if (!$user_id) { $user_id = USER_ID; }
		$super_admin = $this->isSuperAdmin($user_id);
		
		if ($super_admin) {
			
				$sortqry = "";
				//start sort
				if ($sort['name'] == "asc") {
					$sortqry = " ss.name ";
				} else if ($sort['name'] == "desc") {
					$sortqry = " ss.name DESC ";
				} else if ($sort['short_name'] == "asc") {
					$sortqry = " short_name ";
				} else if ($sort['short_name'] == "desc") {
					$sortqry = " short_name DESC ";
				} else if ($sort['code'] == "asc") {
					$sortqry = " ss.code ";
				} else if ($sort['ss.code'] == "desc") {
					$sortqry = " code DESC ";
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
						$full_article_search_text .= " ss.name LIKE '%" . $split_text[$i] . "%' or short_name LIKE '%" . $split_text[$i] . "%' or sl.name LIKE '%" . $split_text[$i] . "%' or";
					}
					//more than one search term i.e. spaces in between
					if ($num_items > 1){ 
						$full_article_search_text .= " ss.name LIKE '%" . $search_text . "%' or short_name LIKE '%" . $search_text . "%' or sl.name LIKE '%" . $search_text . "%' or"; 
					} 
					//end more than one search term i.e. spaces in between
					$full_article_search_text = $this->removelastor($full_article_search_text);
				}
				
				
				if (!$page){ $page=1; }
				if (!$lperpage){ $lperpage = 20; } //default num records
				$offset = ($page - 1) * $lperpage;		
				
				//main query - get total number of records
				$query = "SELECT sg.id, sg.min, sg.max, sg.grade, sg.points, sg.sch_level, sl.name";
				$query .= " , sg.created_at, sg.created_by, cl.full_names ";
				$query .= " FROM score_grades_history sg ";
				$query .= " LEFT JOIN  sch_levels sl ON sg.sch_level = sl.id ";
				$query .= " LEFT JOIN  clients cl ON sg.created_by = cl.id ";
				$query .= " WHERE sg.id != '' ";
				if ($id) { $query .= " AND sg.score_grade_id = $id "; }
				if ($level_id) { $query .= " AND sg.sch_level = $level_id "; }
				if ($search_text) { $query .= " AND ($full_article_search_text) "; }
				//echo $query; //exit;
		
				$stmtMain = $this->conn->prepare($query);
				$stmtMain->execute();
				$stmtMain->store_result();
				$stmtMain->fetch();
				$total_recs = $stmtMain->num_rows;
				$stmtMain->close();
				
				//filtered recordset
				if ($sortqry) { $query .= " ORDER BY $sortqry "; } else { $query .= " ORDER BY sg.id DESC "; }//add sort query 
				$query .= " LIMIT $offset,$lperpage"; //echo "queryadd - $queryadd";
				//echo "query - $query"; 
				
				$stmt = $this->conn->prepare($query);
				
				if ($stmt = $this->conn->prepare($query)){
					
					$stmt->execute();
					$numberofrows = $stmt->num_rows;
					
					$stmt->bind_result($id, $min, $max, $grade, $points, $level_id, $level, $created_at, $created_by, $created_by_name);
					/* fetch values */
					while ( $stmt->fetch() ) {
						
						$tmp = array();
						
						$tmp["id"] = $id;
						$tmp["min"] = $min;
						$tmp["max"] = $max;
						$tmp["grade"] = $grade;
						$tmp["points"] = $points;
						$tmp["level_id"] = $level_id;
						$tmp["level"] = $level;
						$tmp["created_at"] = $this->adjustDate("", $this->php_date($created_at));
						$tmp["created_at_fmt"] = $this->adjustDate("d-M-Y, H:i", $this->php_date($created_at));
						$tmp["created_by"] = $created_by;
						$tmp["created_by_name"] = $created_by_name;
						
						array_push($results, $tmp);
						
					}
					$response['rows'] = $results;
					$response['total'] = $total_recs;
					$response['rowCount'] = $lperpage;
					$response['current'] = $page;
					$stmt->close();
									
				} else {
					
					//$response["query"] = $query;
					$response["error"] = true;
					$response["message"] = AN_ERROR_OCCURED_MESSAGE;
					$response['error_type'] = AN_ERROR_OCCURED_ERROR;
					
				}
							
		} else {
			$response['rows'] = "";
			$response['total'] = 0;
			$response['rowCount'] = 0;
			$response["error"] = true;
			$response["message"] = INVALID_ACCESS_ERROR_MESSAGE;	
		}
				
		return $response; 

    }
	
	
	//fetch subject history
	public function fetchSubjectHistory($page=NULL, $user_id=NULL, $lperpage=NULL, $admin=NULL, $id=NULL, $sort=NULL, $search_text=NULL) {

		$response = array();
		$subjects = array();
		$success = 1;
		
		$user_group = $this->getUserGroupId($user_id);
				
		$perms = ALL_SUBJECT_PERMISSIONS;
		
		if (!$user_id){ $user_id = USER_ID; } 
		
		if (($user_group==SUPER_ADMIN_USER_ID) || ($user_group==SCHOOL_ADMIN_USER_ID)) 
		{ 
			$success = 1; 
			
		} else {
			
			if ($admin) {
				//check whether user has admin permissions on the data
				if ($sch_id && $admin && (!$this->getPermisssionData($user_id, $sch_id, $perms))) {
					$response["message"] = INVALID_ACCESS_ERROR_MESSAGE;
					$response["error_type"] = INVALID_ACCESS_ERROR;
					$response["error"] = true;
					$success = 0;
				} 
			} else {
				//user is not admin, check whether user is subscribed to this student to view data	
			}
		}
		
		if ($success) {
			
				$sortqry = "";
				//start sort
				if ($sort['name'] == "asc") {
					$sortqry = " ss.name ";
				} else if ($sort['name'] == "desc") {
					$sortqry = " ss.name DESC ";
				} else if ($sort['short_name'] == "asc") {
					$sortqry = " short_name ";
				} else if ($sort['short_name'] == "desc") {
					$sortqry = " short_name DESC ";
				} else if ($sort['code'] == "asc") {
					$sortqry = " ss.code ";
				} else if ($sort['ss.code'] == "desc") {
					$sortqry = " code DESC ";
				} else if ($sort['level'] == "asc") {
					$sortqry = " sl.name ";
				} else if ($sort['level'] == "desc") {
					$sortqry = " sl.name DESC ";
				} else if ($sort['status'] == "asc") {
					$sortqry = " s.name ";
				} else if ($sort['status'] == "desc") {
					$sortqry = " s.name DESC ";
				} else if ($sort['created_at'] == "asc") {
					$sortqry = " ss.created_at "; 
				} else if ($sort['created_at'] == "desc") {
					$sortqry = " ss.created_at DESC "; 
				} else if ($sort['created_by'] == "asc") {
					$sortqry = " ss.created_by ";
				} else if ($sort['created_by'] == "desc") {
					$sortqry = " ss.created_by DESC "; 
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
						$full_article_search_text .= " ss.name LIKE '%" . $split_text[$i] . "%' or short_name LIKE '%" . $split_text[$i] . "%' or sl.name LIKE '%" . $split_text[$i] . "%' or";
					}
					//more than one search term i.e. spaces in between
					if ($num_items > 1){ 
						$full_article_search_text .= " ss.name LIKE '%" . $search_text . "%' or short_name LIKE '%" . $search_text . "%' or sl.name LIKE '%" . $search_text . "%' or"; 
					} 
					//end more than one search term i.e. spaces in between
					$full_article_search_text = $this->removelastor($full_article_search_text);
				}
				
				
				if (!$page){ $page=1; }
				if (!$lperpage){ $lperpage = 20; } //default num records
				$offset = ($page - 1) * $lperpage;		
				
				//main query - get total number of records
				$query = "SELECT ss.id, ss.name, short_name, ss.code, ss.school_level, sl.name";
				$query .= " , ss.status, s.name, ss.created_at, ss.created_by FROM sch_subjects_history ss ";
				$query .= " LEFT JOIN  sch_levels sl ON ss.school_level = sl.id ";
				$query .= " JOIN  status s ON ss.status = s.id ";
				$query .= " WHERE ss.name != '' ";
				if ($id) { $query .= " AND ss.subject_id = $id "; }
				if ($level_id) { $query .= " AND ss.school_level = $level_id "; }
				if ($search_text) { $query .= " AND ($full_article_search_text) "; }
				
				//total records
				$stmtMain = $this->conn->prepare($query);
				$stmtMain->execute();
				$stmtMain->store_result();
				$stmtMain->fetch();
				$total_recs = $stmtMain->num_rows;
				$stmtMain->close();
				//end total records
				
				//filtered recordset
				if ($sortqry) { $query .= " ORDER BY $sortqry "; } else { $query .= " ORDER BY ss.created_at DESC"; }
				
				$query .= " LIMIT $offset,$lperpage"; //echo "query - $query"; exit;
				
				if ($stmt = $this->conn->prepare($query)){
					
					//$stmt->bind_param("i", $student_id);
					$stmt->execute();
					$stmt->store_result();
					$stmt->bind_result($id, $name, $short_name, $code, $level_id, $level, $status, $status_name, $created_at, $created_by);
					
					/* fetch values */
					while ( $stmt->fetch() ) {
						
						$tmp = array();
						
						$tmp["id"] = $id;
						$tmp["name"] = $name;
						$tmp["short_name"] = $short_name;
						$tmp["code"] = $code;
						$tmp["level_id"] = $level_id;
						$tmp["level"] = $level;
						$tmp["status"] = $status;
						$tmp["status_name"] = $status_name;
						$tmp["created_at"] = $this->adjustDate("", $this->php_date($created_at));
						$tmp["created_by"] = $this->getFullNames($created_by);
						
						array_push($subjects, $tmp);
						
					}
					
					$response['rows'] = $subjects;
					$response['total'] = $total_recs;
					$response['rowCount'] = $lperpage;
					$response['current'] = $page;
					$stmt->close();
									
				} else {
					
					//$response["query"] = $query;
					$response["error"] = true;
					$response["message"] = AN_ERROR_OCCURED_MESSAGE;
					$response['error_type'] = AN_ERROR_OCCURED_ERROR;
					
				}
							
		} else {
			$response['rows'] = "";
			$response['total'] = 0;
			$response['rowCount'] = 0;
			$response["error"] = true;
			$response["message"] = INVALID_ACCESS_ERROR_MESSAGE;	
		}
				
		return $response; 

    }
	
	//fetch student fee payments history
	public function getStudentFeePaymentsHistory($page=NULL, $user_id=NULL, $lperpage=NULL, $admin=NULL, $id=NULL, $student_id=NULL, $fee_year=NULL, $sort=NULL, $search_text=NULL) {

		$response = array();
		$result = array();
		$success = 1;
		
		$user_group = $this->getUserGroupId($user_id);
		
		$total_sum = 0;
		
		$perms = ALL_FEE_PERMISSIONS;
		
		if (!$user_id){ $user_id = USER_ID; } 
		
		if (($user_group==SUPER_ADMIN_USER_ID) || ($user_group==SCHOOL_ADMIN_USER_ID)) 
		{ 
			
			$success = 1; 
			
		} else {
			
			if ($admin) {
				//check whether user has admin permissions on the data
				if ($sch_id && $admin && (!$this->getPermisssionData($user_id, $sch_id, $perms))) {
					$response["message"] = INVALID_ACCESS_ERROR_MESSAGE;
					$response["error_type"] = INVALID_ACCESS_ERROR;
					$response["error"] = true;
					$success = 0;
				} 
			} else {
				//user is not admin, check whether user is subscribed to this student to view data	
			}
		}
		
		if ($success) {
			
				$sortqry = "";
				//start sort
				if ($sort['fees_payments_id'] == "asc"){
					$sortqry = " sfp.fees_payments_id ";
				} else if ($sort['fees_payments_id'] == "desc") {
					$sortqry = " sfp.fees_payments_id DESC ";
				} else if ($sort['name'] == "asc") {
					$sortqry = " ss.full_names ";
				} else if ($sort['name'] == "desc") {
					$sortqry = " ss.full_names DESC ";
				} else if ($sort['payment_amount_fmt'] == "asc") {
					$sortqry = " sfp.amount ";
				} else if ($sort['payment_amount_fmt'] == "desc") {
					$sortqry = " sfp.amount DESC ";
				} else if ($sort['payment_paid_at_fmt'] == "asc") {
					$sortqry = " sfp.paid_at ";
				} else if ($sort['payment_paid_at_fmt'] == "desc") {
					$sortqry = " sfp.paid_at DESC ";
				} else if ($sort['payment_mode'] == "asc") {
					$sortqry = " pm.name ";
				} else if ($sort['payment_mode'] == "desc") {
					$sortqry = " pm.name DESC "; 
				} else if ($sort['payment_created_at'] == "asc") {
					$sortqry = " sfp.created_at ";
				} else if ($sort['payment_created_at'] == "desc") {
					$sortqry = " sfp.created_at DESC "; 
				} else if ($sort['payment_created_by'] == "asc") {
					$sortqry = " c.full_names ";
				} else if ($sort['payment_created_by'] == "desc") {
					$sortqry = " c.full_names DESC "; 
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
				
				
				if (!$page){ $page=1; }
				if (!$lperpage){ $lperpage = 20; } //default num records
				$offset = ($page - 1) * $lperpage;		
		
				//main query
				$query = "SELECT sfp.id, sfp.fees_payments_id, sfp.fees_id, sfp.amount";
				$query .= ", pm.name, sf.student_id, sf.year, ss.full_names, sfp.status";
				$query .= ", stat.name, sfp.created_at, sfp.paid_by, sfp.paid_at, c.full_names";
				$query .= " FROM sch_fees_payments_history sfp ";
				$query .= " JOIN sch_fees sf ON sf.id = sfp.fees_id ";
				$query .= " JOIN payment_modes pm ON pm.code = sfp.payment_mode ";
				$query .= " JOIN clients c ON c.id = sfp.created_by ";
				$query .= " JOIN sch_students ss ON ss.id = sf.student_id ";
				$query .= " JOIN status stat ON stat.id = sfp.status ";
				$query .= " WHERE sfp.id != '' ";
				if ($id) { $query .= " AND sfp.fees_id = $id "; }
				if ($fee_year) { $query .= " AND sf.year = $fee_year "; }
				if ($student_id) { $query .= " AND sf.student_id = $student_id "; }
				if ($search_text) { $query .= " AND ($full_article_search_text) "; }
				//total records
				$stmtMain = $this->conn->prepare($query);
				$stmtMain->execute();
				$stmtMain->store_result();
				$stmtMain->fetch();
				$total_recs = $stmtMain->num_rows;
				$stmtMain->close();
				//end total records
				
				//filtered recordset
				if ($sortqry) { $query .= " ORDER BY $sortqry "; } else { $query .= " ORDER BY sfp.created_at DESC"; }
				
				$query .= " LIMIT $offset,$lperpage"; //echo "query - $query"; exit;
				
				if ($stmt = $this->conn->prepare($query)){
					
					//$stmt->bind_param("i", $student_id);
					$stmt->execute();
					$stmt->store_result();
					$stmt->bind_result($payment_id, $fees_payments_id, $payment_fees_id, $amount, $payment_mode, $student_id, $year, $student_name, $status, $status_name, $created_at, $paid_by, $paid_at, $created_by);
					/* fetch values */
					while ( $stmt->fetch() ) {
						
						$total_sum = $total_sum + $order_total;
						
						$tmp = array();
						$tmp["id"] = $payment_id;
						$tmp["payment_id"] = $payment_id;
						$tmp["fees_payments_id"] = $fees_payments_id;
						$tmp["name"] = $student_name;
						$tmp["payment_student_id"] = $student_id;
						$tmp["payment_student_name"] = $student_name;
						$tmp["payment_year"] = $year;
						$tmp["payment_fees_id"] = $payment_fees_id;
						$tmp["payment_amount"] = $amount;
						$tmp["payment_amount_fmt"] = $this->format_num($amount, 0);
						$tmp["payment_amount_fmt2"] = STATIC_DEFAULT_CURRENCY . $this->format_num($amount, 0);
						$tmp["payment_mode"] = $payment_mode;
						$tmp["status"] = $status;
						$tmp["status_name"] = $status_name;
						$tmp["payment_paid_by"] = $paid_by;
						$tmp["payment_paid_at"] = $this->adjustDate(NULL, $this->php_date($paid_at), NULL);
						$tmp["payment_paid_at_fmt"] = $this->adjustDate(DATE_FMT, $this->php_date($paid_at), NULL);
						$tmp["payment_created_at"] = $this->adjustDate(NULL, $this->php_date($created_at), NULL);
						$tmp["payment_created_at_fmt"] = $this->adjustDate(DATE_FMT, $this->php_date($created_at), NULL);
						$tmp["payment_created_at_fmt2"] = $this->adjustDate(DATE_FMT2, $this->php_date($created_at), NULL);
						$tmp["payment_created_by"] = $created_by;
			
						array_push($result, $tmp);
						
					}
					
					$stmt->close();
					
					//$response['query'] = $query;
					$response['rows'] = $result;
					$response['est_name'] = $sch_id;
					$response['totalSum'] = $total_sum;
					$response["totalSumFmt"] = $this->format_num($total_sum, 0);
					$response["totalSumFmt2"] = "Ksh " . $this->format_num($total_sum, 0);
					$response['total'] = $total_recs;
					$response["totalFmt"] = $this->format_num($total_recs, 0);
					$response['rowCount'] = $limit;
					$response['current'] = $page;
				
				} else {
					
					//$response["query"] = $query;
					$response["error"] = true;
					$response["message"] = AN_ERROR_OCCURED_MESSAGE;
					$response['error_type'] = AN_ERROR_OCCURED_ERROR;
					
				}
							
		} else {
			$response['rows'] = "";
			$response['total'] = 0;
			$response['rowCount'] = 0;
			$response["error"] = true;
			$response["message"] = INVALID_ACCESS_ERROR_MESSAGE;	
		}
				
		return $response; 

    }
	
	//fetch student fee payments
	public function getStudentFeePayments($page=NULL, $student_id=NULL, $phone_number=NULL, $year=NULL, $user_id=NULL, $sch_id=NULL, $reg_no=NULL, $lperpage=NULL, $admin=NULL, $id=NULL, $sfpid=NULL, $no_pagination=FALSE, $start_date=NULL, $end_date=NULL, $status=NULL) {

		$response = array();
		$result = array();
		$success = 1;
		
		$top_student_id = $student_id;
		$top_year = $year;
		$top_reg_no = $reg_no;
		 
				
		$total_sum = 0; //echo "fff ... ";
		
		if (!$user_id) { $user_id = USER_ID; }
				
		//check user permissions
		$super_admin = $this->isSuperAdmin($user_id);
		if ($admin && !$super_admin) {
			$perms = ALL_FEE_PERMISSIONS; 
			$company_ids = $this->getUserCompanyIds($user_id, $perms, $sch_id); 
		}
		//echo "yes ... ";		
		if ($super_admin || ($admin && $company_ids) || !$admin) {
				
				if (!$page){ $page=1; }
				if (!$lperpage){ $lperpage = 20; } //default num records
				$offset = ($page - 1) * $lperpage;
				
				//reformat the dates
				if ($start_date) {
					$start_date = urldecode($start_date);
					$start_date_data = explode("/", $start_date);
					$start_date = $start_date_data[2] ."-". $start_date_data[1] ."-". $start_date_data[0];
				}
				
				if ($end_date) {
					$end_date = urldecode($end_date);
					$end_date_data = explode("/", $end_date);
					$end_date = $end_date_data[2] ."-". $end_date_data[1] ."-". $end_date_data[0]; //echo "start_date - $start_date";
				}
		
				//main query
				$query = "SELECT sfp.id, sfp.fees_id, sfp.amount, sfp.ref_no, pm.name, pm.code, sf.student_id, ss.full_names, sfp.created_at";
				$query .= " , sfp.paid_by, sfp.paid_at, sfp.status, s.name, ss.current_class, ss.stream";
				$query .= " FROM sch_fees_payments sfp ";
				$query .= " JOIN sch_fees sf ON sf.id = sfp.fees_id ";
				$query .= " JOIN payment_modes pm ON pm.code = sfp.payment_mode ";
				$query .= " JOIN sch_students ss ON ss.id = sf.student_id ";
				$query .= " JOIN status s ON s.id = sfp.status ";
				$query .= " WHERE sf.id != '' ";
				if ($year) { $query .= " AND sf.year = $year "; }
				if ($student_id) { $query .= " AND sf.student_id = $student_id "; }
				if ($id) { $query .= " AND sf.id = $id "; }
				if ($sfpid) { $query .= " AND sfp.id = $sfpid "; }
				if ($sch_id) { $query .= " AND sf.sch_id = $sch_id "; }
				if ($reg_no) { $query .= " AND sf.reg_no = '$reg_no' "; }
				if ($start_date) { $query .= " AND SUBSTR(sfp.paid_at,1,10) >= '$start_date' "; }
				if ($end_date) { $query .= " AND SUBSTR(sfp.paid_at,1,10) <= '$end_date' "; }
				if ($status) { $query .= " AND sfp.status = $status "; }
				//echo $query;
								
				//total records
				$stmtMain = $this->conn->prepare($query);
				$stmtMain->execute();
				$stmtMain->store_result();
				$stmtMain->fetch();
				$total_recs = $stmtMain->num_rows;
				$stmtMain->close();
				//end total records
				
				//filtered recordset
				if ($sortqry) { $query .= " ORDER BY $sortqry "; } else { $query .= " ORDER BY sfp.id DESC"; }
				
				//$query .= " LIMIT $offset,$lperpage"; 
				if (!$no_pagination) { $query .= " LIMIT $offset,$lperpage"; }
				//echo "query - $query"; 
				//exit;
				
				if ($stmt = $this->conn->prepare($query)){
					
					//$stmt->bind_param("i", $student_id);
					$stmt->execute();
					$stmt->store_result();
					$stmt->bind_result($payment_id, $payment_fees_id, $amount, $ref_no, $payment_mode, $payment_mode_code, $student_id, $student_name, $created_at, $paid_by, $paid_at, $status, $status_name, $current_class, $stream);
					/* fetch values */
					while ( $stmt->fetch() ) {
						
						$total_sum = $total_sum + $order_total;
						
						if ($created_by) { $created_by = $this->getFullNames($created_by); }
						
						/*if this is an mnesa payment, update mpesa payment status */
						
						if (($payment_mode_code == "mpesa") && ($status==PENDING_STATUS || !$status)) {
							$results = $this->updateMpesaPaymentStatus($payment_id);
							$response["mpesa_trans"] = $results;
							$response["status"] = $results["status"];
							$response["status_name"] = $results["status_name"];
							//print_r($response); exit;
						}
						
						/*end if this is an mnesa payment, update mpesa payment status */
						
						$tmp = array();
						$tmp["id"] = $payment_id;
						$tmp["payment_id"] = $payment_id;
						$tmp["name"] = $student_name;
						$tmp["ref_no"] = $ref_no;
						$tmp["current_class"] = $current_class;
						$tmp["stream"] = $stream;
						$tmp["payment_student_id"] = $student_id;
						$tmp["payment_student_name"] = $student_name;
						$tmp["payment_year"] = $year;
						$tmp["payment_fees_id"] = $payment_fees_id;
						$tmp["payment_amount"] = $amount;
						$tmp["payment_amount_fmt"] = $this->format_num($amount, 0);
						$tmp["payment_amount_fmt2"] = STATIC_DEFAULT_CURRENCY . $this->format_num($amount, 0);
						$tmp["payment_mode"] = $payment_mode;
						$tmp["payment_mode_code"] = $payment_mode_code;
						$tmp["status"] = $status;
						$tmp["status_name"] = $status_name;
						$tmp["payment_paid_by"] = $paid_by;
						$tmp["payment_paid_at"] = $paid_at;
						$tmp["payment_paid_at_edit"] = $this->adjustDate(NULL, $this->php_date($paid_at), NULL);
						$tmp["payment_paid_at_fmt"] = $this->adjustDate("d-M-Y", $this->php_date($paid_at), NULL);
						$tmp["payment_paid_at_fmt2"] = $this->adjustDate("d/m/Y", $this->php_date($paid_at), NULL);
						$tmp["payment_created_at"] = $this->adjustDate(NULL, $this->php_date($created_at), NULL);
						$tmp["payment_created_by"] = $created_by;
						
			
						array_push($result, $tmp);
					}
					
					$stmt->close();
					
					//$response['query'] = $query;
					$response['rows'] = $result;
					$response['total'] = $total_recs;
					$response['rowCount'] = $lperpage;
					$response["error"] = false;
				
				} else {
					
					//$response["query"] = $query;
					$response["error"] = true;
					$response["message"] = AN_ERROR_OCCURED_MESSAGE;
					$response['error_type'] = AN_ERROR_OCCURED_ERROR;
					
				}
							
		} else {
			$response['rows'] = "";
			$response['total'] = 0;
			$response['rowCount'] = 0;
			$response["error"] = true;
			$response["message"] = INVALID_ACCESS_ERROR_MESSAGE;	
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
	
	function removeAllSpecialCharacters($string){
		return preg_replace("/[^A-Za-z0-9]/", "", $string);	
	}
	
	//get schools list for grid
    function getSchoolGridListing($page=NULL, $user_id=NULL, $search_text=NULL, $lperpage=NULL, $sort=NULL, $id=NULL, $admin=NULL,  $company_ids=NULL, $show_all=NULL) { 

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
		
		if ($admin && !SUPER_ADMIN_USER) {
			$perms = ALL_SCHOOL_PERMISSIONS; 
			$company_ids = $this->getUserCompanyIds($user_id, $perms); 
		} 
		
		if (SUPER_ADMIN_USER || $company_ids || !$admin) {
		
			if (!$page){ $page = 1; }
			if (!$lperpage || ($lperpage < 0)) { $lperpage = 10; } //default num records
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
			$queryMain = "SELECT s.sch_id, s.sch_name, s.sch_first_name, address, sch_province, p.name, sch_category, sch_level, sl.name, extra, sch_profile";
			$queryMain .= " , phone1, phone2, motto, sms_welcome1, sms_welcome2, sch_paybill_no, s.status, ss.name, sch_county, c.name, sc.name FROM sch_ussd s ";
			$queryMain .= " LEFT JOIN counties c ON s.sch_county=c.id ";
			$queryMain .= " LEFT JOIN sch_levels sl ON s.sch_level=sl.id ";
			$queryMain .= " LEFT JOIN provinces p ON s.sch_province=p.id ";
			$queryMain .= " LEFT JOIN status ss ON s.status=ss.id ";
			$queryMain .= " LEFT JOIN sch_categories sc ON s.sch_category=sc.id ";
			$queryMain .= " WHERE s.sch_name!='' ";
			if ($id) { $queryMain .= " AND s.sch_id = $id "; }
			if ($search_text) { $queryMain .= " AND ($full_search_text) "; }
			if ($category) { $queryMain .= " AND s.sch_category = $category "; }
			if ($status) { $queryMain .= " AND s.status = $status "; }
			if ($company_ids) { $queryMain .= " AND s.sch_id IN ($company_ids) "; }
			if (!SUPER_ADMIN_USER) { $queryMain .= " AND s.status != " . DELETED_STATUS; }
			//echo "query - " . $queryMain; exit;
			
			$stmtMain = $this->conn->prepare($queryMain);
			$stmtMain->execute();
			$stmtMain->store_result();
			$stmtMain->fetch();
			$total_recs = $stmtMain->num_rows;
			$stmtMain->close();
			
			//filtered recordset
			if ($sortqry) { $queryMain .= " ORDER BY $sortqry "; }else { $queryMain .= " ORDER BY s.sch_name "; } //add sort query 
			if (!$show_all) { $queryMain .= " LIMIT $offset,$lperpage"; }
			//echo "queryMain - $queryMain"; exit;
			$query = $queryMain; 
			$stmt = $this->conn->prepare($query);
			//$stmt->bind_param("i", $sch_id);
			$stmt->execute();
			$numberofrows = $stmt->num_rows;
			
			$stmt->bind_result($sch_id, $sch_name, $sch_first_name, $address, $province, $province_name, $category_id, $level, $level_name, $extra, $sch_profile, $phone1, $phone2, $motto, $sms_welcome1, $sms_welcome2, $sch_paybill_no, $status, $status_name, $county_id, $county_name, $category_name);
			/* fetch values */
			while ( $stmt->fetch() ) {
				$tmp = array();
				$tmp["sch_id"] = $sch_id;
				$tmp["id"] = $sch_id;
				$tmp["sch_name"] = $sch_name;
				$tmp["name"] = $sch_name;
				$tmp["sch_first_name"] = $sch_first_name;
				$tmp["address"] = $address;
				$tmp["province"] = $province;
				$tmp["province_name"] = $province_name;
				$tmp["level"] = $level;
				$tmp["level_name"] = $level_name;
				$tmp["category"] = $category_id;
				$tmp["category_name"] = $category_name;
				$tmp["extra"] = $extra;
				$tmp["profile"] = $sch_profile;
				$tmp["paybill_no"] = $sch_paybill_no;
				$tmp["phone1"] = $phone1;
				$tmp["phone2"] = $phone2;
				$tmp["motto"] = $motto;
				$tmp["sms_welcome1"] = $sms_welcome1;
				$tmp["sms_welcome2"] = $sms_welcome2;			
				$tmp["status"] = $status;
				$tmp["status_name"] = $status_name;
				$tmp["province"] = $province;
				$tmp["county"] = $county_id;
				$tmp["county_name"] = $county_name;
				
				array_push($schools, $tmp);
			}
			//$response['queryMain'] = $queryMain;
			$response['rows'] = $schools;
			$response['total'] = $total_recs;
			$response['rowCount'] = $lperpage;
			$response['current'] = $page;
			$stmt->close();
			
		}
		
		return $response; 

    }
	
	
	//show report list- show all results
    function getResultsGridListing($sch_id, $current_class=NULL, $stream=NULL, $reg_no, $year=NULL, $term=NULL, $id=NULL, $page=NULL, $user_id=NULL, $search_text=NULL, $lperpage=NULL, $sort=NULL, $admin=NULL, $no_pagination=false, $start_date=NULL, $end_date=NULL, $exam_id=NULL) { 

		$response = array();
		$results = array();
				
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
			
		if (!$user_id) { $user_id = USER_ID; }
		
		//check user permissions
		$super_admin = $this->isSuperAdmin($user_id);
		if ($admin && !$super_admin) {
			$perms = ALL_RESULT_PERMISSIONS; 
			$company_ids = $this->getUserCompanyIds($user_id, $perms, $sch_id); 
		}
				
		if ($super_admin || ($admin && $company_ids) || (!$admin)) {
		
			if (!$page){ $page = 1; }
			if (!$lperpage || ($lperpage < 0)) { $lperpage = 10; } //default num records
			$offset = ($page - 1) * $lperpage;
			
			//reformat the dates
			if ($start_date) {
				$start_date = urldecode($start_date);
				$start_date_data = explode("/", $start_date);
				$start_date = $start_date_data[2] ."-". $start_date_data[1] ."-". $start_date_data[0];
			}
			
			if ($end_date) {
				$end_date = urldecode($end_date);
				$end_date_data = explode("/", $end_date);
				$end_date = $end_date_data[2] ."-". $end_date_data[1] ."-". $end_date_data[0]; //echo "start_date - $start_date";
			}
			
			if ($search_text) {
				$search_text = strtolower(trim($search_text));
				$search_text = $this->clean($search_text);
				$split_text = explode(" ",$search_text);
				$num_items = count($split_text);
				$full_article_search_text = "";
				for ($i=0;$i<$num_items;$i++) {
					$split_text[$i] = trim($split_text[$i]);
					$full_article_search_text .= " sr.year LIKE '%" . $split_text[$i] . "%' or st.reg_no LIKE '%" . $split_text[$i] . "%' or st.full_names LIKE '%" . $split_text[$i] . "%' or";
				}
				//more than one search term i.e. spaces in between
				if ($num_items > 1){ 
					$full_article_search_text .= " sr.year LIKE '%" . $search_text . "%' or st.reg_no LIKE '%" . $search_text . "%' or st.full_names LIKE '%" . $search_text . "%' or"; 
				} 
				//end more than one search term i.e. spaces in between
				$full_article_search_text = $this->removelastor($full_article_search_text);
			}
			
			//fetch records
			$query = "SELECT sr.id, sr.total_score, sr.mean_score, sr.grade, sr.points, sr.term, sr.created_by, sr.created_at";
			$query .= ", sr.year, st.reg_no, st.current_class, st.stream, st.id, st.full_names";
			$query .= " FROM sch_students st ";
			$query .= " LEFT JOIN sch_results sr ON sr.student_id = st.id";
			$query .= " WHERE st.id != '' ";
			if ($student_id) { $query .= " AND st.id = $student_id "; }
			if ($sch_id) { $query .= " AND st.sch_id = $sch_id "; }
			if ($year) { $query .= " AND sr.year = $year "; }
			if ($term) { $query .= " AND sr.term = $term "; } 
			if ($id) { $query .= " AND sr.id = $id "; }
			if ($reg_no) { $query .= " AND sr.reg_no = '$reg_no' "; }
			if ($exam_id) { $query .= " AND sr.exam_id = $exam_id "; }
			if ($current_class) { $query .= " AND st.current_class = $current_class "; }
			if ($stream) { $query .= " AND st.stream = '$stream' "; }
			if ($start_date) { $query .= " AND SUBSTR(sr.created_at,1,10) >= '$start_date' "; }
			if ($end_date) { $query .= " AND SUBSTR(sr.created_at,1,10) <= '$end_date' "; }
			if ($search_text) { $query .= " AND ($full_article_search_text) "; }
						
			if ($stmtMain = $this->conn->prepare($query)) {
			
				$stmtMain->execute();

				$stmtMain->store_result();
				$stmtMain->fetch();
				$total_recs = $stmtMain->num_rows;
				$stmtMain->close();
				
				//filtered recordset
				if ($sortqry) { $query .= " ORDER BY $sortqry "; }else { $query .= " ORDER BY st.full_names "; } //add sort query 
				if (!$no_pagination) { $query .= " LIMIT $offset,$lperpage"; }
				$stmt = $this->conn->prepare($query);
				$stmt->execute();
				$stmt->store_result();
				$numberofrows = $stmt->num_rows;
				
				$stmt->bind_result($id, $total_score, $mean_score, $grade, $points, $term, $created_by, $created_at, $year, $reg_no, $current_class, $stream, $student_id, $student_full_names);
				
				/* fetch values */
				while ( $stmt->fetch() ) {
										
					$tmp = array();
					
					if ($total_score) { $total_score = $this->format_num($total_score,0); } else { $total_score = ""; }

					$tmp["id"] = $id;
					$tmp["name"] = $student_full_names;
					$tmp["student_id"] = $student_id;
					$tmp["total_score"] = $total_score;
					$tmp["mean_score"] = $mean_score;
					$tmp["grade"] = $grade;
					$tmp["points"] = $points;
					$tmp["stream"] = $stream;
					$tmp["term"] = $term;
					$tmp["created_by"] = $created_by;
					$tmp["created_at"] = $this->set_display_date($created_at);
					$tmp["year"] = $year;
					$tmp["current_class"] = $current_class . " " . $stream;
					$tmp["reg_no"] = $reg_no;
					
					if ($id) {
						$tmp["student_results"] = $this->fetchStudentResults("", "", "", "", "", "", "", "", "", "", "", $id);
					} else {
						$tmp["student_results"] = "";
					}
					
					array_push($results, $tmp);
					
				}

				//$response['query'] = $query;
				$response['rows'] = $results;
				$response['total'] = $total_recs;
				$response['rowCount'] = $lperpage;
				$response['current'] = $page;
				
				$stmt->close();				
				
			} else {
				
				//$response["query"] = $query;
				$response["error"] = true;
				$response["message"] = $this->conn->error;
				$response['error_type'] = AN_ERROR_OCCURED_ERROR;	
			
			}
			
		}
		
		return $response; 

    }
	
	
	
	//get fees summary for grid
    function getFeesSummary($sch_id, $current_class=NULL, $stream=NULL, $reg_no=NULL, $year=NULL, $student_id=NULL, $payment_method=NULL, $start_date=NULL, $end_date=NULL, $user_id=NULL, $admin=NULL) { 

		$response = array();
		$results = array();
		
		if (!$user_id) { $user_id = USER_ID; }
		
		//check user permissions
		$super_admin = $this->isSuperAdmin($user_id);
		if ($admin && !$super_admin) {
			$perms = ALL_FEE_PERMISSIONS; 
			$company_ids = $this->getUserCompanyIds($user_id, $perms, $sch_id); 
		}
				
		if ($super_admin || ($admin && $company_ids) || !$admin) {
		
			if (!$page){ $page = 1; }
			if (!$lperpage || ($lperpage < 0)) { $lperpage = 10; } //default num records
			$offset = ($page - 1) * $lperpage;
			
			//reformat the dates
			if ($start_date) {
				$start_date = urldecode($start_date);
				$start_date_data = explode("/", $start_date);
				$start_date = $start_date_data[2] ."-". $start_date_data[1] ."-". $start_date_data[0];
			}
			
			if ($end_date) {
				$end_date = urldecode($end_date);
				$end_date_data = explode("/", $end_date);
				$end_date = $end_date_data[2] ."-". $end_date_data[1] ."-". $end_date_data[0]; //echo "start_date - $start_date";
			}
			
			
			//Calculate sum total
			$query = "SELECT SUM(sf.fees_paid), SUM(sf.fees_bal)";
			$query .= " FROM sch_fees_payments sfp  ";
			$query .= " JOIN sch_fees sf ON sfp.fees_id = sf.id ";
			$query .= " JOIN sch_students st ON sf.student_id = st.id ";
			$query .= " WHERE sfp.id != '' ";
			if ($student_id) { $query .= " AND sf.student_id = $student_id "; }
			if ($sch_id) { $query .= " AND sf.sch_id = $sch_id "; }
			if ($year) { $query .= " AND (sf.year = $year) "; } 
			if ($reg_no) { $query .= " AND st.reg_no = '$reg_no' "; }
			if ($current_class) { $query .= " AND st.current_class = $current_class "; }
			if ($stream) { $query .= " AND st.stream = '$stream' "; }
			if ($payment_method) { $query .= " AND LCASE(sfp.payment_mode) = '$payment_method' "; }
			if ($start_date) { $query .= " AND SUBSTR(sfp.paid_at,1,10) >= '$start_date' "; }
			if ($end_date) { $query .= " AND SUBSTR(sfp.paid_at,1,10) <= '$end_date' "; }

			//get totals			
			if ($stmt = $this->conn->prepare($query)) {
			
				$stmt->execute();
				$stmt->store_result();
				
				$stmt->bind_result($fees_paid, $fees_bal);
				
				/* fetch values */
				$stmt->fetch();
				
				//fees paid
				if ($fees_paid) {
					$fees_paid_val = $fees_paid;
					$fees_paid_fmt = $this->format_num($fees_paid, 0);
					$fees_paid_fmt2 = STATIC_DEFAULT_CURRENCY . $this->format_num($fees_paid, 0);
				} else {
					$fees_paid_val = "0";
					$fees_paid_fmt = "0";
					$fees_paid_fmt2 = "0";	
				}
				
				//fees paid
				if ($fees_bal) {
					$fees_bal_val = $fees_bal;
					$fees_bal_fmt = $this->format_num($fees_bal, 0);
					$fees_bal_fmt2 = STATIC_DEFAULT_CURRENCY . $this->format_num($fees_bal, 0);
				} else {
					$fees_bal_val = "0";
					$fees_bal_fmt = "0";
					$fees_bal_fmt2 = "0";	
				}
										
				//$response["query"] = $query;
				$response["fees_paid"] = $fees_paid_val;
				$response['fees_paid_fmt'] = $fees_paid_fmt;
				$response['fees_paid_fmt2'] = $fees_paid_fmt2;
				$response["fees_bal"] = $fees_bal_val;
				$response['fees_bal_fmt'] = $fees_bal_fmt;
				$response['fees_bal_fmt2'] = $fees_bal_fmt2;
								
				$stmt->close();				
				
			} else {
				
				//$response["query"] = $query;
				$response["error"] = true;
				$response["message"] = $this->conn->error;
				$response['error_type'] = AN_ERROR_OCCURED_ERROR;	
			
			}
			
		}
		
		return $response; 

    }
	
	
	//get fees list for grid
    function getFeesGridListing($sch_id, $current_class=NULL, $stream=NULL, $reg_no=NULL, $year=NULL, $id=NULL, $page=NULL, $user_id=NULL, $search_text=NULL, $lperpage=NULL, $sort=NULL, $admin=NULL, $no_pagination=false, $start_date=NULL, $end_date=NULL, $status=NULL, $student_id=NULL) { 

		$response = array();
		$results = array();
		
		//$total_sum = 0;
		
		$sortqry = "";
		//start sort
		if ($sort['reg_no'] == "asc"){
			$sortqry = " reg_no ";
		} else if ($sort['reg_no'] == "desc") {
			$sortqry = " reg_no DESC ";
		} else if ($sort['name'] == "asc") {
			$sortqry = " st.full_names ";
		} else if ($sort['name'] == "desc") {
			$sortqry = " st.full_names DESC ";
		} else if ($sort['current_class'] == "asc") {
			$sortqry = " st.current_class, st.stream ";
		} else if ($sort['current_class'] == "desc") {
			$sortqry = " st.current_class DESC, st.stream DESC ";
		} else if ($sort['year'] == "asc") {
			$sortqry = " sf.year ";
		} else if ($sort['year'] == "desc") {
			$sortqry = " sf.year DESC ";
		} else if ($sort['paid_at'] == "asc") {
			$sortqry = " sfp.paid_at ";
		} else if ($sort['paid_at'] == "desc") {
			$sortqry = " sfp.paid_at DESC ";
		} else if ($sort['paid_by'] == "asc") {
			$sortqry = " sfp.paid_by ";
		} else if ($sort['paid_by'] == "desc") {
			$sortqry = " sfp.paid_by DESC ";
		} else if ($sort['amount_fmt'] == "asc") {
			$sortqry = " sfp.amount ";
		} else if ($sort['amount_fmt'] == "desc") {
			$sortqry = " sfp.amount DESC ";
		} 
		//end sort
			
		if (!$user_id) { $user_id = USER_ID; }
		
		//check user permissions
		$super_admin = $this->isSuperAdmin($user_id);
		if ($admin && !$super_admin) {
			$perms = ALL_FEE_PERMISSIONS; 
			$company_ids = $this->getUserCompanyIds($user_id, $perms, $sch_id); 
		}
				
		if ($super_admin || ($admin && $company_ids) || !$admin) {
		
			if (!$page){ $page = 1; }
			if (!$lperpage || ($lperpage < 0)) { $lperpage = 10; } //default num records
			$offset = ($page - 1) * $lperpage;
			
			//reformat the dates
			if ($start_date) {
				$start_date = urldecode($start_date);
				$start_date_data = explode("/", $start_date);
				$start_date = $start_date_data[2] ."-". $start_date_data[1] ."-". $start_date_data[0];
			}
			
			if ($end_date) {
				$end_date = urldecode($end_date);
				$end_date_data = explode("/", $end_date);
				$end_date = $end_date_data[2] ."-". $end_date_data[1] ."-". $end_date_data[0]; //echo "start_date - $start_date";
			}
			
			if ($search_text) {
				$search_text = strtolower(trim($search_text));
				$search_text = $this->clean($search_text);
				$split_text = explode(" ",$search_text);
				$num_items = count($split_text);
				$full_article_search_text = "";
				for ($i=0;$i<$num_items;$i++) {
					$split_text[$i] = trim($split_text[$i]);
					$full_article_search_text .= " cl.full_names LIKE '%" . $split_text[$i] . "%' or st.reg_no LIKE '%" . $split_text[$i] . "%' or st.full_names LIKE '%" . $split_text[$i] . "%' or";
				}
				//more than one search term i.e. spaces in between
				if ($num_items > 1){ 
					$full_article_search_text .= " cl.full_names LIKE '%" . $search_text . "%' or st.reg_no LIKE '%" . $search_text . "%' or st.full_names LIKE '%" . $search_text . "%' or"; 
				} 
				//end more than one search term i.e. spaces in between
				$full_article_search_text = $this->removelastor($full_article_search_text);
			}
			
			//Calculate sum total
			$query = "SELECT SUM(sf.total_fees), SUM(sf.fees_paid), SUM(sf.fees_bal)";
			$query .= " FROM sch_students st  ";
			$query .= " LEFT JOIN sch_fees sf ON sf.student_id = st.id ";
			$query .= " WHERE st.id != '' ";
			if ($student_id) { $query .= " AND sf.student_id = $student_id "; }
			if ($sch_id) { $query .= " AND sf.sch_id = $sch_id "; }
			if ($year) { $query .= " AND (sf.year = $year OR sf.year IS NULL) "; } 
			if ($id) { $query .= " AND sf.id = $id "; }
			if ($reg_no) { $query .= " AND st.reg_no = '$reg_no' "; }
			if ($current_class) { $query .= " AND st.current_class = $current_class "; }
			if ($stream) { $query .= " AND st.stream = '$stream' "; }
			if ($start_date) { $query .= " AND SUBSTR(sf.updated_at,1,10) >= '$start_date' "; }
			if ($end_date) { $query .= " AND SUBSTR(sf.updated_at,1,10) <= '$end_date' "; }
			if ($search_text) { $query .= " AND ($full_article_search_text) "; }

			//get totals
			$stmt = $this->conn->prepare($query);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($total_fees_sum, $fees_paid_sum, $fees_bal_sum);
			$stmt->fetch();
			$stmt->close();
			
			//
			//fetch records
			$query = "SELECT sf.id, sf.total_fees, sf.fees_paid, sf.fees_bal, st.id, st.reg_no";
			$query .= ", sf.sch_id, sf.updated_by, sf.updated_at, sf.created_by, sf.created_at";
			$query .= " , sf.year, st.current_class, st.stream, st.full_names ";
			$query .= " FROM sch_students st  ";
			$query .= " LEFT JOIN sch_fees sf ON sf.student_id = st.id ";
			$query .= " WHERE st.id != '' ";
			if ($student_id) { $query .= " AND sf.student_id = $student_id "; }
			if ($sch_id) { $query .= " AND sf.sch_id = $sch_id "; }
			if ($year) { $query .= " AND (sf.year = $year) "; } 
			if ($id) { $query .= " AND sf.id = $id "; }
			if ($reg_no) { $query .= " AND st.reg_no = '$reg_no' "; }
			if ($current_class) { $query .= " AND st.current_class = $current_class "; }
			if ($stream) { $query .= " AND st.stream = '$stream' "; }
			if ($start_date) { $query .= " AND SUBSTR(sf.updated_at,1,10) >= '$start_date' "; }
			if ($end_date) { $query .= " AND SUBSTR(sf.updated_at,1,10) <= '$end_date' "; }
			if ($search_text) { $query .= " AND ($full_article_search_text) "; }
			
			//echo "query - $query"; 
			
			if ($stmtMain = $this->conn->prepare($query)) {
			
				$stmtMain->execute();
				$stmtMain->store_result();
				$stmtMain->fetch();
				$total_recs = $stmtMain->num_rows;
				$stmtMain->close();
				
				//filtered recordset
				if ($sortqry) { $query .= " ORDER BY $sortqry "; }else { $queryMain .= " ORDER BY st.full_names "; } //add sort query 
				if (!$no_pagination) { $query .= " LIMIT $offset,$lperpage"; }
				$stmt = $this->conn->prepare($query);
				$stmt->execute();
				$stmt->store_result();
				$numberofrows = $stmt->num_rows;
				
				$stmt->bind_result($id, $total_fees, $fees_paid, $fees_bal, $student_id, $reg_no, $sch_id, $updated_by, $updated_at, $created_by, $created_at, $year, $current_class, $stream, $student_full_names);
				
				/* fetch values */
				while ( $stmt->fetch() ) {
										
					$tmp = array();
										
					//total fees
					if ($total_fees) {
						$total_fees_val = $total_fees;
						$total_fees_fmt = $this->format_num($total_fees, 0);
						$total_fees_fmt2 = STATIC_DEFAULT_CURRENCY . $this->format_num($total_fees, 0);
					} else {
						$total_fees_val = "0";
						$fees_paid_fmt = "0";
						$fees_paid_fmt2 = "0";	
					}
					
					//fees paid
					if ($fees_paid) {
						$fees_paid_val = $fees_paid;
						$fees_paid_fmt = $this->format_num($fees_paid, 0);
						$fees_paid_fmt2 = STATIC_DEFAULT_CURRENCY . $this->format_num($fees_paid, 0);
					} else {
						$fees_paid_val = "0";
						$fees_paid_fmt = "0";
						$fees_paid_fmt2 = "0";	
					}
					
					//fees paid
					if ($fees_bal) {
						$fees_bal_val = $fees_bal;
						$fees_bal_fmt = $this->format_num($fees_bal, 0);
						$fees_bal_fmt2 = STATIC_DEFAULT_CURRENCY . $this->format_num($fees_bal, 0);
					} else {
						$fees_bal_val = "0";
						$fees_bal_fmt = "0";
						$fees_bal_fmt2 = "0";	
					}
					
					$tmp["id"] = $id;
					$tmp["name"] = $student_full_names;
					$tmp["total_fees"] = $total_fees_val;
					$tmp['total_fees_fmt'] = $total_fees_fmt;
					$tmp['total_fees_fmt2'] = $total_fees_fmt2;
					$tmp["fees_paid"] = $fees_paid_val;
					$tmp['fees_paid_fmt'] = $fees_paid_fmt;
					$tmp['fees_paid_fmt2'] = $fees_paid_fmt2;
					$tmp["fees_bal"] = $fees_bal_val;
					$tmp['fees_bal_fmt'] = $fees_bal_fmt;
					$tmp['fees_bal_fmt2'] = $fees_bal_fmt2;
					$tmp["student_id"] = $student_id;
					$tmp["sch_id"] = $sch_id;
					$tmp["updated_by"] = $updated_by;
					$tmp["updated_at"] = $this->set_display_date($updated_at);
					$tmp["created_by"] = $created_by;
					$tmp["created_at"] = $this->set_display_date($created_at);
					$tmp["year"] = $year;
					$tmp["current_class"] = $current_class . " " . $stream;
					$tmp["reg_no"] = $reg_no;
					$tmp["fee_payments"] = $this->getStudentFeePayments("", "", "", "", "", "", "", "", "", $id, "", 1);
					
					array_push($results, $tmp);
					
				}

				//$response['query'] = $query;
				$response['error'] = false;
				$response['total'] = $total_recs;
				$response['rowCount'] = $lperpage;
				$response['current'] = $page;
				$response['totalFeesSum'] = $total_fees_sum;
				$response["totalFeesSumFmt"] = $this->format_num($total_fees_sum, 0);
				$response["totalFeesSumFmt2"] = STATIC_DEFAULT_CURRENCY . $this->format_num($total_fees_sum, 0);
				$response['feesPaidSum'] = $fees_paid_sum;
				$response["feesPaidSumFmt"] = $this->format_num($fees_paid_sum, 0);
				$response["feesPaidSumFmt2"] = STATIC_DEFAULT_CURRENCY . $this->format_num($fees_paid_sum, 0);
				$response['feesBalSum'] = $fees_bal_sum;
				$response["feesBalSumFmt"] = $this->format_num($fees_bal_sum, 0);
				$response["feesBalSumFmt2"] = STATIC_DEFAULT_CURRENCY . $this->format_num($fees_bal_sum, 0);
				$response['rows'] = $results;
								
				$stmt->close();				
				
			} else {
				
				//$response["query"] = $query;
				$response["error"] = true;
				$response["message"] = $this->conn->error;
				$response['error_type'] = AN_ERROR_OCCURED_ERROR;	
			
			}
			
		}
		
		return $response; 

    }
	
	
	//get fee payments list for grid
    function getFeePaymentsGridListing($sch_id, $current_class=NULL, $stream=NULL, $reg_no, $id=NULL, $page=NULL, $user_id=NULL, $search_text=NULL, $lperpage=NULL, $sort=NULL, $admin=NULL, $no_pagination=false, $start_date=NULL, $end_date=NULL, $status=NULL) { 

		$response = array();
		$results = array();
		
		//$total_sum = 0;
		
		$sortqry = "";
		//start sort
		if ($sort['reg_no'] == "asc"){
			$sortqry = " reg_no ";
		} else if ($sort['reg_no'] == "desc") {
			$sortqry = " reg_no DESC ";
		} else if ($sort['name'] == "asc") {
			$sortqry = " st.full_names ";
		} else if ($sort['name'] == "desc") {
			$sortqry = " st.full_names DESC ";
		} else if ($sort['current_class'] == "asc") {
			$sortqry = " st.current_class, st.stream ";
		} else if ($sort['current_class'] == "desc") {
			$sortqry = " st.current_class DESC, st.stream DESC ";
		} else if ($sort['year'] == "asc") {
			$sortqry = " sf.year ";
		} else if ($sort['year'] == "desc") {
			$sortqry = " sf.year DESC ";
		} else if ($sort['paid_at'] == "asc") {
			$sortqry = " sfp.paid_at ";
		} else if ($sort['paid_at'] == "desc") {
			$sortqry = " sfp.paid_at DESC ";
		} else if ($sort['paid_by'] == "asc") {
			$sortqry = " sfp.paid_by ";
		} else if ($sort['paid_by'] == "desc") {
			$sortqry = " sfp.paid_by DESC ";
		} else if ($sort['amount_fmt'] == "asc") {
			$sortqry = " sfp.amount ";
		} else if ($sort['amount_fmt'] == "desc") {
			$sortqry = " sfp.amount DESC ";
		} 
		//end sort
			
		if (!$user_id) { $user_id = USER_ID; }
		
		//check user permissions
		$super_admin = $this->isSuperAdmin($user_id);
		if ($admin && !$super_admin) {
			$perms = ALL_FEE_PERMISSIONS; 
			$company_ids = $this->getUserCompanyIds($user_id, $perms, $sch_id); 
		}
				
		if ($super_admin || ($admin && $company_ids)) {
		
			if (!$page){ $page = 1; }
			if (!$lperpage || ($lperpage < 0)) { $lperpage = 10; } //default num records
			$offset = ($page - 1) * $lperpage;
			
			//reformat the dates
			if ($start_date) {
				$start_date = urldecode($start_date);
				$start_date_data = explode("/", $start_date);
				$start_date = $start_date_data[2] ."-". $start_date_data[1] ."-". $start_date_data[0];
			}
			
			if ($end_date) {
				$end_date = urldecode($end_date);
				$end_date_data = explode("/", $end_date);
				$end_date = $end_date_data[2] ."-". $end_date_data[1] ."-". $end_date_data[0]; //echo "start_date - $start_date";
			}
			
			if ($search_text) {
				$search_text = strtolower(trim($search_text));
				$search_text = $this->clean($search_text);
				$split_text = explode(" ",$search_text);
				$num_items = count($split_text);
				$full_article_search_text = "";
				for ($i=0;$i<$num_items;$i++) {
					$split_text[$i] = trim($split_text[$i]);
					$full_article_search_text .= " sfp.paid_by LIKE '%" . $split_text[$i] . "%' or st.reg_no LIKE '%" . $split_text[$i] . "%' or st.full_names LIKE '%" . $split_text[$i] . "%' or";
				}
				//more than one search term i.e. spaces in between
				if ($num_items > 1){ 
					$full_article_search_text .= " sfp.paid_by LIKE '%" . $search_text . "%' or st.reg_no LIKE '%" . $search_text . "%' or st.full_names LIKE '%" . $search_text . "%' or"; 
				} 
				//end more than one search term i.e. spaces in between
				$full_article_search_text = $this->removelastor($full_article_search_text);
			}
			
			//Calculate sum total
			$query = "SELECT SUM(sfp.amount) FROM sch_fees_payments sfp ";
			$query .= " JOIN sch_fees sf ON sf.id = sfp.fees_id ";
			$query .= " JOIN sch_students st ON sf.student_id = st.id ";
			$query .= " LEFT JOIN clients cl ON sfp.created_by = cl.id ";
			$query .= " WHERE sfp.id != '' ";
			if ($student_id) { $query .= " AND sf.student_id = $student_id "; }
			if ($sch_id) { $query .= " AND sf.sch_id = $sch_id "; }
			if ($year) { $query .= " AND sf.year = $year "; } 
			if ($id) { $query .= " AND sfp.id = $id "; }
			if ($reg_no) { $query .= " AND sf.reg_no = '$reg_no' "; }
			if ($current_class) { $query .= " AND st.current_class = $current_class "; }
			if ($stream) { $query .= " AND st.stream = '$stream' "; }
			if ($status) { $query .= " AND stat.name = '$status' "; }
			if ($start_date) { $query .= " AND SUBSTR(sfp.paid_at,1,10) >= '$start_date' "; }
			if ($end_date) { $query .= " AND SUBSTR(sfp.paid_at,1,10) <= '$end_date' "; }
			if ($search_text) { $query .= " AND ($full_article_search_text) "; }
			//get totals
			$stmt = $this->conn->prepare($query);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($total_sum);
			$stmt->fetch();
			$stmt->close();
			
			//
			//fetch records
			$query = "SELECT sfp.id, sfp.amount, sfp.payment_mode, sfp.paid_by, sfp.paid_at, sfp.created_by, sfp.created_at";
			$query .= " , sf.year, st.reg_no, st.current_class, st.stream, st.full_names, cl.full_names ";
			$query .= " FROM sch_fees_payments sfp ";
			$query .= " JOIN sch_fees sf ON sf.id = sfp.fees_id ";
			$query .= " JOIN sch_students st ON sf.student_id = st.id ";
			$query .= " LEFT JOIN clients cl ON sfp.created_by = cl.id ";
			$query .= " WHERE sfp.id != '' ";
			if ($student_id) { $query .= " AND sf.student_id = $student_id "; }
			if ($sch_id) { $query .= " AND sf.sch_id = $sch_id "; }
			if ($year) { $query .= " AND sf.year = $year "; } 
			if ($id) { $query .= " AND sfp.id = $id "; }
			if ($reg_no) { $query .= " AND sf.reg_no = '$reg_no' "; }
			if ($current_class) { $query .= " AND st.current_class = $current_class "; }
			if ($stream) { $query .= " AND st.stream = '$stream' "; }
			if ($status) { $query .= " AND stat.name = '$status' "; }
			if ($start_date) { $query .= " AND SUBSTR(sfp.paid_at,1,10) >= '$start_date' "; }
			if ($end_date) { $query .= " AND SUBSTR(sfp.paid_at,1,10) <= '$end_date' "; }
			if ($search_text) { $query .= " AND ($full_article_search_text) "; }
			
			//echo "query - $query"; exit;
			
			if ($stmtMain = $this->conn->prepare($query)) {
			
				$stmtMain->execute();
				$stmtMain->store_result();
				$stmtMain->fetch();
				$total_recs = $stmtMain->num_rows;
				$stmtMain->close();
				
				//filtered recordset
				if ($sortqry) { $query .= " ORDER BY $sortqry "; }else { $queryMain .= " ORDER BY sfp.id DESC "; } //add sort query 
				if (!$no_pagination) { $query .= " LIMIT $offset,$lperpage"; }
				$stmt = $this->conn->prepare($query);
				$stmt->execute();
				$numberofrows = $stmt->num_rows;
				
				$stmt->bind_result($id, $amount, $payment_mode, $paid_by, $paid_at, $created_by, $created_at, $year, $reg_no, $current_class, $stream, $student_full_names, $client_full_names);
				
				/* fetch values */
				while ( $stmt->fetch() ) {
					
					//$total_sum = $total_sum + $amount;
					
					$tmp = array();

					$tmp["id"] = $id;
					$tmp["name"] = $student_full_names;
					$tmp["amount"] = $amount;
					$tmp['amount_fmt'] = $this->format_num($amount, 0);
					$tmp['amount_fmt2'] = STATIC_DEFAULT_CURRENCY . $this->format_num($amount, 0);
					$tmp["payment_mode"] = $payment_mode;
					//$tmp["status"] = $status;
					//$tmp["status_name"] = $status_name;
					$tmp["paid_by"] = $paid_by;
					$tmp["paid_at"] = $this->set_display_date($paid_at);
					$tmp["created_by"] = $created_by;
					$tmp["created_at"] = $this->set_display_date($created_at);
					$tmp["year"] = $year;
					$tmp["current_class"] = $current_class . " " . $stream;
					$tmp["reg_no"] = $reg_no;
					$tmp["client_names"] = $client_full_names;
					
					array_push($results, $tmp);
					
				}

				//$response['query'] = $query;
				$response['rows'] = $results;
				$response['total'] = $total_recs;
				$response['rowCount'] = $lperpage;
				$response['current'] = $page;
				$response['totalSum'] = $total_sum;
				$response["totalSumFmt"] = $this->format_num($total_sum, 0);
				$response["totalSumFmt2"] = "Ksh " . $this->format_num($total_sum, 0);
				$stmt->close();				
				
			} else {
				
				//$response["query"] = $query;
				$response["error"] = true;
				$response["message"] = $this->conn->error;
				$response['error_type'] = AN_ERROR_OCCURED_ERROR;	
			
			}
			
		}
		
		return $response; 

    }
	
	
	function set_display_date($thedate) {
		return $this->adjustDate("d-M-Y", $this->php_date($thedate), NULL);	
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
	
	// fetching student fees summary 
    public function getStudentFees($sch_id, $student_id, $year, $reg_no, $show_student_fees) {
		
		$response = array();
		$tmp = array();
		
		$success = 1;
		
		if (!$student_id) {
			$success = 0;	
		}
		
		//get student data
		$student_data = $this->getStudentData("", "", "", "", $student_id); //print_r($student_data);exit;
		$student_full_names = $student_data["student_full_names"]; 
		$current_class = $student_data["current_class"]; 
		$stream = $student_data["stream"]; 
		
		if ($success) {
							
			$query = "SELECT sf.id, total_fees, fees_bal, fees_paid, sf.updated_at";
			$query .= " FROM sch_fees sf ";
			$query .= " JOIN sch_students st ON sf.student_id = st.id";
			$query .= " WHERE sf.id != '' "; 
			if ($student_id) { $query .= " AND sf.student_id = $student_id "; }
			if ($sch_id && $reg_no) { $query .= " AND sf.sch_id = $sch_id AND sf.reg_no = '$reg_no' "; }
			if ($year) { $query .= " AND year = $year "; } 
			//echo "query - $query"; exit;
			
			if ($stmt = $this->conn->prepare($query)) {
				
				//$stmt->bind_param("i", $student_id);
				$result = $stmt->execute();
				$stmt->store_result();
				$stmt->bind_result($id, $total_fees,$fees_bal,$fees_paid, $updated_at);
				$stmt->fetch();
												
				if ($result) {
					
					//format values
					
					//total fees
					if ($total_fees) {
						$total_fees_val = $total_fees;
						$total_fees_fmt = $this->format_num($total_fees, 0);
						$total_fees_fmt2 = STATIC_DEFAULT_CURRENCY . $this->format_num($total_fees, 0);
					} else {
						$total_fees_val = 0;
						$fees_paid_fmt = 0;
						$fees_paid_fmt2 = 0;	
					}
					
					//fees paid
					if ($fees_paid) {
						$fees_paid_val = $fees_paid;
						$fees_paid_fmt = $this->format_num($fees_paid, 0);
						$fees_paid_fmt2 = STATIC_DEFAULT_CURRENCY . $this->format_num($fees_paid, 0);
					} else {
						$fees_paid_val = 0;
						$fees_paid_fmt = 0;
						$fees_paid_fmt2 = 0;	
					}
					
					//fees paid
					if ($fees_bal) {
						$fees_bal_val = $fees_bal;
						$fees_bal_fmt = $this->format_num($fees_bal, 0);
						$fees_bal_fmt2 = STATIC_DEFAULT_CURRENCY . $this->format_num($fees_bal, 0);
					} else {
						$fees_bal_val = 0;
						$fees_bal_fmt = 0;
						$fees_bal_fmt2 = 0;	
					}
										
					$tmp["id"] = $id;
					$tmp["total_fees"] = $total_fees_val;
					$tmp['total_fees_fmt'] = $total_fees_fmt;
					$tmp['total_fees_fmt2'] = $total_fees_fmt2;
					$tmp["fees_paid"] = $fees_paid_val;
					$tmp['fees_paid_fmt'] = $fees_paid_fmt;
					$tmp['fees_paid_fmt2'] = $fees_paid_fmt2;
					$tmp["fees_bal"] = $fees_bal_val;
					$tmp['fees_bal_fmt'] = $fees_bal_fmt;
					$tmp['fees_bal_fmt2'] = $fees_bal_fmt2;	
					$tmp['updated_at'] = $this->adjustDate("d-M-Y", $this->php_date($updated_at), NULL);
					$error = false;
					$stmt->close();
					
				} else {	
				
					$error = true;
					$tmp['full_names'] = $this->getStudentName($student_id);
					$tmp["message"] = "No Records Found";
					$tmp['total_fees'] = "Kshs. 0.00";
					$tmp['fees_bal'] = "Kshs. 0.00";
					$tmp['fees_paid'] = "Kshs. 0.00";
					$tmp['updated_at'] = "None";
					
				}
	
				$response = $tmp;
				$response['error'] = $error;
				
			} else {
				
				//$response["query"] = $query;
				$response["error"] = true;
				$response["message"] = $this->conn->error;
				$response['error_type'] = AN_ERROR_OCCURED_ERROR;	
			
			}
		
		} else {	
			
			$error = true;
			$tmp["message"] = "No Records Found";
			$tmp['total_fees'] = "Kshs. 0.00";
			$tmp['fees_bal'] = "Kshs. 0.00";
			$tmp['fees_paid'] = "Kshs. 0.00";
			$tmp['updated_at'] = "None";
			
			$response = $tmp;
			$response['error'] = $error;
			
		}
		
		$response["full_names"] = $student_full_names;
		$response["year"] = $year;
		$response["current_class"] = $current_class;
		$response["stream"] = $stream;
		$response["student_full_names"] = $student_full_names;
	
		return $response; 

    }
	
	public function getStudentFeesReport($sch_id, $student_id, $year, $reg_no) {
		
		$response = array();
		$tmp = array();
		
		$query = "SELECT total_fees, fees_bal, fees_paid, sf.updated_at  FROM sch_fees sf JOIN sch_students st";
		$query .= " ON sf.student_id = st.id WHERE sf.id != '' ";
		if ($student_id) { $query .= " AND sf.student_id = $student_id "; }
		if ($sch_id && $reg_no) { $query .= " AND sf.sch_id = $sch_id AND sf.reg_no = '$reg_no' "; }
		if ($year) { $query .= " AND year = $year "; } 
		//echo "query - $query"; exit;
		
		if ($stmt = $this->conn->prepare($query)) {
			
			//$stmt->bind_param("i", $student_id);
			$result = $stmt->execute();
			$stmt->bind_result($total_fees,$fees_bal,$fees_paid, $updated_at);
			$stmt->fetch();
			if ($result) {	
				
				$tmp['total_fees'] = STATIC_DEFAULT_CURRENCY . $this->format_num($total_fees, 0);
				$tmp['fees_bal'] = STATIC_DEFAULT_CURRENCY . $this->format_num($fees_bal, 0);
				$tmp['fees_paid'] = STATIC_DEFAULT_CURRENCY . $this->format_num($fees_paid, 0);
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
			
			//$response["query"] = $query;
			$response["error"] = true;
			$response["message"] = $this->conn->error;
			$response['error_type'] = AN_ERROR_OCCURED_ERROR;	
		
		}
	
		return $response; 

    }
	
	// fetching single user by id
	public function getUserGroupId($user_id) {
        $stmt = $this->conn->prepare("SELECT user_group FROM clients WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            // $user = $stmt->get_result()->fetch_assoc();
            $stmt->bind_result($user_group);
            $stmt->fetch();
			$stmt->store_result();
            $user = array();
            $stmt->close();
            return $user_group;
        } else {
            return NULL;
        }
    }
	
	//fetching admin company/ school id
	public function getAdminCompanyId($user_id) {
        $stmt = $this->conn->prepare("SELECT sch_id FROM clients WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            // $user = $stmt->get_result()->fetch_assoc();
            $stmt->bind_result($sch_id);
            $stmt->fetch();
			$stmt->store_result();
            $user = array();
            $stmt->close();
            return $sch_id;
        } else {
            return NULL;
        }
    }
	
	//get user company ids
	/*function getUserCompanyIds($user_id, $perms, $top_est_id=NULL) {
		
		$company_ids_array = array();
		
		//get user group
		$user_group = $this->getUserGroupId($user_id);
		//echo "user_group == $user_group - " . SCHOOL_ADMIN_USER_ID; exit;
		
		if ($user_group == SCHOOL_ADMIN_USER_ID) {
			
			//get school id
			$company_ids = $this->getAdminCompanyId($user_id);
			
		} else {

			$est_data = $this->getPermisssionData($user_id, $top_est_id, $perms);  //print_r($est_data);
			foreach ($est_data['rows'] as $key => $val) {
				$est_id = $val["est_id"];
				$company_ids_array[] = $est_id;
			}
			$company_ids = implode(",", $company_ids_array);
			
		}
		
		return $company_ids;	
		
	}
	*/
	//get user company ids
	function getUserCompanyIds($user_id, $perms, $top_est_id=NULL) {
		
		//get user group
		$user_group = $this->getUserGroupId($user_id);
		
		if ($user_group == SCHOOL_ADMIN_USER_ID){ 
			
			$company_ids = $user_id; 
			
		} else {
		
			$company_ids_array = array();
	
			$est_data = $this->getPermisssionData($user_id, $top_est_id, $perms);  print_r($est_data); //exit;
			foreach ($est_data['rows'] as $key => $val) {
				$est_id = $val["est_id"];
				$company_ids_array[] = $est_id;
			}
			$company_ids = implode(",", $company_ids_array);
		
		}
		
		return $company_ids;	
	}
	
	function isSuperAdmin($user_id){
		
		$response = false;
		
		$user_group_id = $this->getUserGroupId($user_id); 
		
		if ($user_group_id==SUPER_ADMIN_USER_ID) {
			$response = true;
		}
		
		return $response;
			
	}
	
	//get permission data
    public function getPermisssionData($user_id=NULL, $sch_id=NULL, $perms=NULL, $page=NULL, $search_text=NULL, $limit=NULL, $sort=NULL) {

		$response = array();
		$result = array();
		
		$sortqry = "";
		//start sort
		if ($sort['id'] == "asc"){
			$sortqry = " cc.id ";
		} else if ($sort['id'] == "desc") {
			$sortqry = " cc.id DESC ";
		} 	
		
		if (!$page){ $page=1; }
		if (!$limit) { $limit = 10; } //default num records
		$offset = ($page - 1) * $limit;

		if ($search_text) {
			$search_text = strtolower(trim($search_text));
			$search_text = $this->clean($search_text);
			$split_text = explode(" ",$search_text);
			$num_items = count($split_text);
			$full_article_search_text = "";
			for ($i=0;$i<$num_items;$i++) {
				$split_text[$i] = trim($split_text[$i]);
				$full_article_search_text .= " su.sch_name LIKE '%" . $split_text[$i] . "%' or g.name LIKE '%" . $split_text[$i] . "%' or";
			}
			//more than one search term i.e. spaces in between
			if ($num_items > 1){ 
				$full_article_search_text .= " su.sch_name LIKE '%" . $search_text . "%' or g.name LIKE '%" . $search_text . "%' or"; 
			} 
			//end more than one search term i.e. spaces in between
			$full_article_search_text = $this->removelastor($full_article_search_text);
		}			
		
		//get summary of permission data
		$query = "SELECT cc.id, cc.group_id, su.sch_id, su.sch_name, cl.id, cl.first_name, cl.last_name, g.name, cc.creator";
		$query .= " FROM client_club cc ";
		$query .= " JOIN sch_ussd su ON su.sch_id = cc.est_id ";
		$query .= " JOIN clients cl ON cc.client_id = cl.id ";
		$query .= " JOIN groups g ON g.id = cc.group_id ";
		$query .= " WHERE cc.id != '' ";
		if ($user_id) { $query .= " AND cc.client_id = $user_id ";  }
		if ($sch_id) { $query .= " AND cc.est_id = $sch_id ";  }
		//echo "query - $query"; exit;
		
		//total records
		$stmtMain = $this->conn->prepare($query);
		$stmtMain->execute();
		$stmtMain->store_result();
		$stmtMain->fetch();
		$total_recs = $stmtMain->num_rows;
		$stmtMain->close();
		//end total records
		
		//filtered recordset
		if ($sortqry) { $query .= " ORDER BY $sortqry "; } else { $query .= " ORDER BY cc.id "; }//add sort query 
		$query .= " LIMIT $offset,$limit"; 
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$stmt->store_result();
		$numberofrows = $stmt->num_rows;
		
		$stmt->bind_result($id, $group_id, $est_id, $est_name, $user_id, $first_name, $last_name, $group_name, $creator);
		
		while ( $stmt->fetch() ) {
			
			$tmp = array();
			
			//get permissions
			$permissions_data = $this->getEstPermissions($user_id, $est_id, $perms);
			//print_r($permissions_data);
			$permissions = array();
			$permissions = $permissions_data["rows"];
			
			$tmp["id"] = $id;
			$tmp["group_id"] = $group_id;
			$tmp["group_name"] = $group_name;
			$tmp["est_name"] = $est_name;
			$tmp["est_id"] = $est_id;
			$tmp["first_name"] = $first_name;
			$tmp["last_name"] = $last_name;
			$tmp["creator"] = $creator;
			$tmp["permissions"] = $permissions;
			array_push($result, $tmp);
			
		}
		
		$response['rows'] = $result;
		$response['total'] = $total_recs;
		$response['rowCount'] = $limit;
		$response['current'] = $page;
        $stmt->close();
		
		return $response; 

    }
	
	//get user permissions for this est
    public function getEstPermissions($user_id, $est_id, $perms = NULL) {

		$response = array();
		$result = array();
		
		$sortqry = "";
		//start sort
		if ($sort['id'] == "asc"){
			$sortqry = " cc.id ";
		} else if ($sort['id'] == "desc") {
			$sortqry = " cc.id DESC ";
		} 
		
		if (!($user_id || $est_id || $perms)) {	

			if ($search_text) {
				$search_text = strtolower(trim($search_text));
				$search_text = $this->clean($search_text);
				$split_text = explode(" ",$search_text);
				$num_items = count($split_text);
				$full_article_search_text = "";
				for ($i=0;$i<$num_items;$i++) {
					$split_text[$i] = trim($split_text[$i]);
					$full_article_search_text .= " p.permalink LIKE '%" . $split_text[$i] . "%' or su.sch_name LIKE '%" . $split_text[$i] . "%' or g.name LIKE '%" . $split_text[$i] . "%' or";
				}
				//more than one search term i.e. spaces in between
				if ($num_items > 1){ 
					$full_article_search_text .= " p.permalink LIKE '%" . $search_text . "%' or su.sch_name LIKE '%" . $search_text . "%' or g.name LIKE '%" . $search_text . "%' or"; 
				} 
				//end more than one search term i.e. spaces in between
				$full_article_search_text = $this->removelastor($full_article_search_text);
			}			
	
			$query = "SELECT p.permalink FROM client_club cc ";
			$query .= " JOIN clubs c ON c.id = cc.est_id ";
			$query .= " JOIN clients cl ON cc.client_id = cl.id ";
			$query .= " JOIN groups g ON g.id = cc.group_id ";
			$query .= " LEFT JOIN group_permissions gp ON gp.group_id = g.id ";
			$query .= " LEFT JOIN permissions p ON p.id = gp.permission_id ";
			$query .= " WHERE cc.client_id = $user_id AND cc.est_id = $est_id ";  //echo "$query $order_id";
			if ($perms) { $query .= " AND p.permalink IN ($perms) "; }
			if ($search_text) { $query .= " AND ($full_article_search_text) "; }
			
			//result recordset
			if ($sortqry) { $query .= " ORDER BY $sortqry "; } else { $query .= " ORDER BY cc.id "; }//add sort query 
			//echo "query - $query"; exit;
			$stmt = $this->conn->prepare($query);
			$stmt->execute();
			$stmt->store_result();
			$total_recs = $stmt->num_rows;
			
			$stmt->bind_result($permission);
			
			while ( $stmt->fetch() ) {
				array_push($result, $permission);
			}
			
			$response['rows'] = $result;
			$response['total'] = $total_recs;
			
			$stmt->close();
			
		} else {
			
			$response['rows'] = "";
			$response['total'] = 0;
			
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
				$response['bal'] = STATIC_DEFAULT_CURRENCY . $this->format_num($fees_bal);
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
	public function requestMpesaFeePayment($sch_id, $sch_name, $reg_no, $student_names, $phone_number, $amount, $paybill_no) {
		
		$phone_number = $this->formatPhoneNumber($phone_number);
		//get school paybill no
		//$bulk_sms_details = $this->getBulkSMSData($sch_id);
		//$paybill_no = $bulk_sms_details["default_source"];
		//$paybill_no = "898740";
		
		if ($paybill_no) {
			$account_number = $reg_no . " - " . $student_names; //echo "paybill_no - $paybill_no == account_number  - $account_number  - $amount  - $phone_number";
			$account_number = urlencode($account_number);
			
			//save mpesa fee entry
			//*************************************************************************************************************************************
			
			$request_mpesa_link = "http://41.215.126.10:5333/pendoschool_app/app_actions.php?tag=checkout&mobile=" . $phone_number . "&amount=" . $amount . "&pid=" . $account_number . "&mid=" . $paybill_no;
			//echo "request_mpesa_link - $request_mpesa_link "; exit;
			$result = $this->executeLink($request_mpesa_link);
			//echo("mobile - " . $result->user->mobile);
			//print_r($result); exit;

			// Check for success
			if ($result->user->mobile) {
				// Mpesa Request successfully sent
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
		
		return $response; 
		
	}
	
	// save new mpesa transaction
    public function saveMpesaFeePayment($creator_id, $student_id, $amount, $phone_number, $paid_by, $year="") {

        $response = array();
		$current_date = $this->getCurrentDate();
		$status = PENDING_STATUS;
		
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
		$query  = "INSERT INTO sch_fees_payments(fees_id, amount, paid_by, paid_at, status, created_at, created_by) VALUES (?, ?, ?, ?, ?, ?) ";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("iisssi", $fees_id, $amount, $paid_by, $current_date, $status, $current_date, $creator_id);
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
    public function getSubscriptions($phone_number=NULL, $page=1, $lperpage=NULL, $sch_id=NULL) {

        $response = array();
		$subs = array();
		
		$isLastPage = true;
		
		if ($phone_number) { $phone_number = $this->formatPhoneNumber($phone_number); }
		
		if (!$page) { $page = 1; }//default page
		if (!$lperpage) { $lperpage = 15; } //default num records
		$offset = ($page - 1) * $lperpage; //page offset
		
		//get subs
		$queryMain  = "SELECT ss.id, ss.sch_id, st.id, ss.reg_no, st.full_names, st.current_class, st.stream";
		$queryMain .= ", su.sch_name, ss.mobile, prov, sub_date, sch_paybill_no, cl.full_names FROM sch_ussd_subs ss";
		$queryMain .= " JOIN sch_students st ON ss.reg_no=st.reg_no ";
		$queryMain .= " JOIN sch_ussd su ON ss.sch_id=su.sch_id ";
		$queryMain .= " JOIN clients cl ON ss.mobile=cl.phone_number ";
		$queryMain .= " WHERE ss.mobile!='' ";
		if ($phone_number) { $queryMain .= " AND ss.mobile = '$phone_number' "; }
		if ($sch_id && ($sch_id!=1)) { $queryMain .= " AND ss.sch_id IN ($sch_id) "; }
		$queryMain .= " ORDER BY sub_date DESC "; //echo $queryMain;
		
		//get total records
		$stmtMain = $this->conn->prepare($queryMain);
		//$stmtMain->bind_param("s", $phone_number);
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
		$stmt->bind_param("ii", $offset, $lperpage);
		$stmt->execute();
		$stmt->store_result();
		$totalRows = $stmt->num_rows;
		
		/* bind result variables */
		$stmt->bind_result($id, $sch_id, $student_id, $regno, $full_names, $current_class, $stream, $sch_name, $mobile, $prov, $sub_date, $paybill_no, $client_name);
		
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
			$tmp["sub_client_name"] = $client_name;
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
		//$response["queryMain"] = $queryMain;
		
        return $response;
    }
	
	// fetching all user chats
    public function getAllUserChats($user_id, $page, $student_id=NULL, $lperpage=NULL, $school_ids=NULL) {

        $chats = array();
		
		$top_user_id = $user_id;
		
		if (!$page) { $page = 1; }//default page
		if (!$lperpage) { $lperpage = 15; } //default num records
		$offset = ($page - 1) * $lperpage; //page offset
		
		//get chats
		$query  = "SELECT cl.id as user_id, c.id as conversation_id, phone_number, c.student_id, c.created_at as created_at, c.updated_at as updated_at, ss.full_names, cl.full_names";
		$query .= " FROM conversations c, clients cl, sch_students ss";
		$query .= " WHERE CASE ";
		$query .= " WHEN c.user_one = ? ";
		$query .= " THEN c.user_two = cl.id ";
		$query .= " WHEN c.user_two = ? ";
		$query .= " THEN c.user_one= cl.id ";
		$query .= " END  ";
		$query .= " AND ( ";
		$query .= " c.user_one = ? ";
		$query .= " OR c.user_two = ? ) ";
		$query .= " AND c.student_id = ss.id ";
		if ($student_id) { $query .= " AND c.student_id = $student_id "; }
		if (($school_ids) && ($school_ids!=1)) { $query .= " AND ss.sch_id IN ($school_ids) "; }
		$query .= " ORDER BY c.id DESC ";
		$query .= " LIMIT ?, ?";
		//echo "query - $query - $user_id, $user_id, $user_id, $user_id, $offset, $lperpage";
		//$query .= " ORDER BY c.id DESC LIMIT 20";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("iiiiii", $user_id, $user_id, $user_id, $user_id, $offset, $lperpage);
		$stmt->execute();
		$stmt->store_result();
		/* bind result variables */
		$stmt->bind_result($user_id, $conversation_id, $phone_number, $student_id, $created_at, $updated_at, $student_name, $full_names);
		
		/* fetch values */
		while ( $stmt->fetch() ) {
			$tmp = array();
			$message = "";
			$message_id = "";
			$recent_message_created_at = "";
			if (!$full_names) { $full_names = $phone_number; }
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
			$recent_message_data = $this->getRecentMessage($conversation_id);
						
			$tmp["recent_message_id"] = $recent_message_data["recent_message_id"];
			$tmp["recent_message"] = $recent_message_data["recent_message"];
			$tmp["recent_message_created_at"] = $recent_message_data["recent_message_created_at"];
			$tmp["unread_count"] = $recent_message_data["unread_count"];

			array_push($chats, $tmp);
			
		}
		
        $stmt->close();
        return $chats;
    }
	
	function getRecentMessage($conversation_id){
		
		$response = array();
		//get most recent message for this conversation
		$recentMessageQuery = "SELECT id, message, created_at FROM messages ";
		$recentMessageQuery .= " WHERE conversation_id = ? ORDER BY id DESC LIMIT 1 ";
		$stmtRecent = $this->conn->prepare($recentMessageQuery);
		$stmtRecent->bind_param("i", $conversation_id);
		$stmtRecent->execute();
		$stmtRecent->store_result();
		$stmtRecent->bind_result($message_id, $message, $recent_message_created_at);
		$stmtRecent->fetch();
		$stmtRecent->close();
		
		//if  message exists, use its timestamp, else use date created timestamp
		if ($message_id){
			$latest_time = $recent_message_created_at;
		} else {
			$latest_time = $created_at;
		}
		
		$response["recent_message_id"] = $message_id;
		$response["recent_message"] = $message;
		$response["recent_message_created_at"] = $this->smartdate($latest_time); //$this->adjustDate("d-M-Y", $this->php_date($latest_time), NULL);
		$response["unread_count"] = $this->getUnreadMessagesCount($conversation_id, $top_user_id);	
		
		return $response;
		
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
		
	// fetch result data
    public function getResultItemData($result_item_id) {
		
		$response = array();
		
		//get chats
		$query  = "SELECT result_id, subject_code, score, grade, points FROM sch_results_items WHERE id != '' "; 
		
		if ($result_item_id) { $query .= " AND id = $result_item_id "; }
		
		if ($stmt = $this->conn->prepare($query)) {
			
			$stmt->execute();
			$stmt->store_result();
			/* bind result variables */
			$stmt->bind_result($result_id, $subject_code, $score, $grade, $points);
			
			$response = array();
			/* fetch values */
			if ( $stmt->fetch() ) {

				$response["result_id"] = $result_id;
				$response["subject_code"] = $subject_code;
				$response["score"] = $score;
				$response["grade"] = $grade;
				$response["points"] = $points;
				
			} else {
				$response["error"] = true;
				$response["message"] = "Incorrect details or student does not exist";	
			}
			$stmt->close();
		
		} else {
			
			$response["error"] = true;
			$response["message"] = AN_ERROR_OCCURED_MESSAGE;
			$response['error_type'] = AN_ERROR_OCCURED_ERROR;	
		
		}
		
        return $response;
    }
	
	// fetch student data
    public function getStudentData($reg_no=NULL, $sch_id=NULL, $phone_number=NULL, $dob=NULL, $student_id=NULL) {

        //echo "chini dtt\nreg_no - $reg_no\n sch_id - $sch_id\n phone_number - $phone_number\n dob - $dob\n student_id - $student_id"; exit;
		
		$student = array();
		
		if ($dob){
			//format the date
			/*$dob_data = explode("/", $dob);
			$day = $dob_data[0];
			$month = $dob_data[1];
			$year = $dob_data[2];
			$date=mktime(00, 00, 00, $month, $day, $year);
			$dob_date = date("Y-m-d", $date); */
			//n - month with no leading zeros, j - day with no leading zeros
			//end format the date
			
			$dob_date_data = explode("/", $dob);
			$dob_date = $dob_date_data[2] ."-". $dob_date_data[1] ."-". $dob_date_data[0];
			
		}
		
		if ($phone_number) {
			$phone_number = $this->formatPhoneNumber($phone_number);
		}
		
		//get chats
		$query  = "SELECT id, full_names, reg_no, sch_id, student_profile, dob, guardian_name, guardian_address ";
		$query .= ", admin_date, index_no, nationality, religion, previous_school, house, club, current_class ";
		$query .= ", guardian_id_card, guardian_relation, guardian_occupation, email, county, town, village, location ";
		$query .= ", disability, gender, stream, constituency ";
		$query .= ", guardian_phone, updated_at, created_at FROM sch_students WHERE full_names != '' "; 
		//if ($dob) { $query .= " AND CONCAT(YEAR(dob),'-',MONTH(dob),'-',DAY(dob)) = '$dob_date'"; }
		if ($dob) { $query .= " AND SUBSTR(dob,1,10) >= '$dob_date' "; }
		if ($reg_no && $sch_id) { $query .= " AND reg_no = '$reg_no' AND sch_id = $sch_id "; }
		if ($student_id) { $query .= " AND id = $student_id "; }
		//echo "$query - $reg_no, $school_id"; exit;
		
		if ($stmt = $this->conn->prepare($query)) {
			
			//$stmt->bind_param("i", $school_id);
			$stmt->execute();
			$stmt->store_result();
			/* bind result variables */
			$stmt->bind_result($student_id, $full_names, $reg_no, $sch_id, $student_profile, $dob, $guardian_name, $guardian_address, 
			$admin_date, $index_no, $nationality, $religion, $previous_school, $house, $club, $current_class, 
			$guardian_id_card, $guardian_relation, $guardian_occupation, $email, $county, $town, $village, $location, 
			$disability, $gender, $stream, $constituency, $guardian_phone, $updated_at, $created_at);
			
			$response = array();
			/* fetch values */
			if ( $stmt->fetch() ) {
				if (!$full_names) { $full_names = $mobile1; }
				$response["student_id"] = $student_id;
				$response["student_full_names"] = $full_names;
				$response["dob"] = $dob;
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
						$response["user_subscribed"] = $this->subscribeUser($phone_number, $sch_id, $reg_no);
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
		$stmt->execute();	
		$stmt->store_result();
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
	
	//get student users names
	function getStudentFullNames($student_id) {
        
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
	
	//get recent message id
	function getRecentMessageId($chat_id) {
        
		$query = "SELECT id FROM messages WHERE conversation_id = ? ORDER BY id DESC LIMIT 0,1";
		$stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $chat_id);
		/* execute statement */
		$stmt->execute();	
		$stmt->store_result();
		/* bind result variables */
		$stmt->bind_result($id);	
		/* fetch value */
		$stmt->fetch();		
        $stmt->close();
		
		if (!$id) {
			$id = NULL;
		}
		
        return $id;
		
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
    function getChat($chat_id, $logged_user_id, $page=NULL, $recent_message_id=NULL) {
        
		if (!$page) { $page = 1; }//default page
		$lperpage = 20; //default num records
		$offset = ($page - 1) * $lperpage; //page offset
		
		$result = array();
		$response = array();
		
		$query  = "SELECT * FROM (";
		$query .= "SELECT m.id as id, cl.id as client_id, cl.user_group, m.created_at, cl.full_names";
		$query .= ", cl.phone_number, m.message ";
		$query .= ", st.full_names as student_name ";
		$query .= " FROM messages m ";
		$query .= " JOIN clients cl ON m.user_id = cl.id ";
		$query .= " JOIN conversations cn ON cn.id = m.conversation_id ";
		$query .= " LEFT JOIN sch_students st ON st.id = cn.student_id ";
		$query .= " WHERE m.conversation_id= ? ";
		if ($recent_message_id) { $query .= " AND m.id > $recent_message_id "; }
		$query .= " ORDER BY m.id DESC ";
		$query .= " LIMIT ?, ? ";
		$query .= ") tmp ORDER BY tmp.id ASC"; //echo "$query - $chat_id, $offset, $lperpage";
		
		if ($stmt = $this->conn->prepare($query)){
			
			$stmt->bind_param("iii", $chat_id, $offset, $lperpage);
			/* execute statement */
			$stmt->execute();	
			$stmt->store_result();
			/* bind result variables */
			$stmt->bind_result($message_id, $user_id, $user_group, $created_at, $full_names, $phone_number, $message, $student_name);	
			/* fetch values */
			
			while ( $stmt->fetch() ) {
				$tmp = array();	
				if ($user_group == SCHOOL_ADMIN_USER_ID) { $photo_field = SCHOOL_PROFILE_PHOTO; } else { $photo_field = USER_PROFILE_PHOTO; }	
				if (!$full_names) { $full_names = $phone_number; }	
				$tmp["message_id"] = $message_id;
				$tmp["user_id"] = $user_id;
				//$tmp["student_id"] = $student_id;
				$tmp["created_at"] = $this->smartdate($created_at);
				$tmp["full_names"] = $full_names;
				$tmp["phone_number"] = $phone_number;
				$tmp["message"] = $message;
				$tmp["user_image"] = $this->getPhoto($photo_field, $user_id, THUMB_IMAGE);
				$tmp["user_large_image"] = $this->getPhoto($photo_field, $user_id);
				array_push($result, $tmp);			
				
			}
			
			$stmt->close();
			
			//get most recent message for this conversation
			$recent_message_data = $this->getRecentMessage($chat_id);
						
			$response["recent_message_id"] = $recent_message_data["recent_message_id"];
			$response["recent_message"] = $recent_message_data["recent_message"];
			$response["recent_message_created_at"] = $recent_message_data["recent_message_created_at"];
			$response["unread_count"] = $recent_message_data["unread_count"];
			
			$response["error"] = false;
			$response["student_name"] = $student_name;
			$response["recipient_name"] = $this->getFullNames($user_id);
			$response["chat_messages"] = $result;
		
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
		$query = "SELECT id FROM clients WHERE ( email = ? OR phone_number = ? OR id = ? )";		
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("ssi", $email, $phone_number, $input);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }
	
	/**
     * Checking if a school entry exists
     * @return boolean
     */
    private function isSchoolAccountExists($sch_id) {
		$email = $input;
		$query = "SELECT id FROM sch_ussd WHERE ( sch_id = ? OR sch_first_name = ? )";		
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("is", $sch_id, $sch_id);
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
     * Checking for duplicate total score grade
     * @return boolean
     */
    private function totalScoreGradeExists($value, $level, $score_id=NULL, $report=false) {
		$query = "SELECT id FROM total_points_grades WHERE id!='' ";
		$query .= " AND (min = $value OR max = $value) AND sch_level = $level "; 
		if ($report) { 
			$query .= " AND id != $score_id "; 
		}
		//echo $query; exit;
		$stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }
	
	/**
     * Checking for duplicate score grade
     * @return boolean
     */
    private function scoreGradeExists($value, $level, $score_id=NULL, $report=false) {
		$query = "SELECT id FROM score_grades WHERE id!='' ";
		$query .= " AND (min = $value OR max = $value) AND sch_level = $level "; 
		if ($report) { 
			$query .= " AND id != $score_id "; 
		}
		//echo $query; exit;
		$stmt = $this->conn->prepare($query);
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
    private function subjectExists($subject_name, $level, $subject_id=NULL, $report=false) {
		$query = "SELECT id FROM sch_subjects WHERE id!='' ";
		$query .= " AND name = '$subject_name' AND school_level = $level "; 
		if ($report) { 
			$query .= " AND id != $subject_id "; 
		}
		//echo $query; exit;
		$stmt = $this->conn->prepare($query);
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
		$phone_number = $this->formatPhoneNumber($phone_number); 
		$current_date = $this->getCurrentDate();
		//get school data
		$school_data = $this->getSchoolData($phone_number, $school_id); 
		$sch_name = $school_data['sch_name']; 
		$province = $school_data['province'];
		//print_r($school_data);
				
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
	
	//update existing totals
	public function updateStudentFeeMain($fees_id, $year, $total_fees) {
		
		$response = array();
		$current_date = $this->getCurrentDate();
		$created_by = USER_ID;
		
		$student_details = $this->getStudentData($reg_no, $sch_id, "", "", $student_id);
		$student_id = $student_details["student_id"];
		$sch_id = $student_details["sch_id"];
		$reg_no = $student_details["reg_no"];
	
		// insert query
		$query = "UPDATE sch_fees SET year = ?, updated_at = ?, updated_by = ?, total_fees = ? ";
		$query .= " WHERE id = ? ";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("isidi", $year, $current_date, $created_by, $total_fees, $fees_id);
		$result = $stmt->execute();
		$stmt->close();
		
		// Check for successful insertion
		if ($result) {
			// successfully updated
			$response = $fees_id;
			
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
		$query = "SELECT id FROM sch_ussd_subs WHERE mobile = ? AND sch_id = ? AND reg_no = ? ";
		//echo "$query - $phone_number, $sch_id, $reg_no"; exit;
		$stmt = $this->conn->prepare($query);
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
	
	//check if school first name exists, if we are on current school (i.e when during editing), ignore it, look elsewhere
	public function schFirstNameExists($sch_first_name, $sch_id=NULL) {
		$query = "SELECT * FROM sch_ussd WHERE sch_first_name = ? ";
		if ($sch_id) { $query .= " AND sch_id != $sch_id"; }
		$stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $sch_first_name);
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
        		
		$phone_number_status = false; 
		
		$phone_number = trim($phone_number);
		
		if (strlen($phone_number) == 12) 
		
		{
			$pattern = "/^2547(\d{8})$/";
			if (preg_match($pattern, $phone_number)) {
				$phone_number_status = true;
			}
		} 
		
		else if (strlen($phone_number) == 13)
		 
		{
			$pattern = "/^\+2547(\d{8})$/";
			if (preg_match($pattern, $phone_number)) {
				$phone_number_status = true;
			}
		}
		
		else if (strlen($phone_number) == 10) 
		
		{
			$pattern = "/^07(\d{8})$/";
			if (preg_match($pattern, $phone_number)) {
				$phone_number_status = true;
			}
		}
		
		else if (strlen($phone_number) == 9) 
		
		{
			$pattern = "/^7(\d{8})$/";
			if (preg_match($pattern, $phone_number)) {
				$phone_number_status = true;
			}
		}
		
        return  $phone_number_status;
		
    }
	
	//reformat the phone number, add 254 at beginning, trim right 9 chars
	private function formatPhoneNumber($phone_number) {
        
        return   "254". substr(trim($phone_number),-9);   
		
    }
	
	//login user exists?
	private function isUserLoginExists($input, $password) {
		
		$password = md5($password);
		$email = $input;
		$phone_number = $this->formatPhoneNumber($input);
		$query = "SELECT id FROM clients WHERE password = ? AND (email = ? OR phone_number = ? OR id = ? )";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("sssi", $password, $email, $phone_number, $input);
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
		$query = "SELECT id FROM clients WHERE (email = ? OR phone_number = ? OR id = ?) AND status = " . ACTIVE_STATUS;	
		$stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssi", $email, $phone_number, $input);
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
	
		$query = "SELECT c.id, full_names, email, user_group, phone_number, receive_messages, status, user_group, logged_times, gcm_registration_id, g.name, c.created_at ";
		$query .= " FROM clients c LEFT JOIN groups g ON c.user_group = g.id ";
		$query .= " WHERE (phone_number = ? OR  email = ? OR  c.id = ?)"; 
		//echo "$query - $phone_number, $username, $username"; exit;
		
		if ($stmt = $this->conn->prepare($query)) {
			
			$stmt->bind_param("ssi", $phone_number, $username, $username);
			$result = $stmt->execute();
			
			if ($result) {
				
				$stmt->store_result();
				$stmt->bind_result($id, $full_names, $email, $user_group, $phone, $receive_messages, $status, $group_id, $logged_times, $gcm_registration_id, $group_name, $created_at);
				$stmt->fetch();
				$stmt->close();
				$tmp = array();
				if ($user_group == SCHOOL_ADMIN_USER_ID) { $photo_field = SCHOOL_PROFILE_PHOTO; } else { $photo_field = USER_PROFILE_PHOTO; }	
				$response['user_id'] = $id;
				$response['user_full_names'] = $full_names;
				$response['user_email'] = $email;
				$response['logged_times'] = $logged_times;
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
		$query .= " FROM sch_ussd_subs ss JOIN sch_students st ON st.reg_no=ss.reg_no WHERE mobile = ? AND ss.sch_id = ? AND ss.reg_no = ? ";
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
		//if (!$format){ $format = 'd-M-Y, h:ia'; } 
		if (!$format){ $format = 'Y-m-d H:i:s'; } 
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
	
	function reformatDateTime($thedate){
		//get components 15/08/2016 08:00
		$date_array = explode(" ", $thedate);
		$date_part = $date_array[0];
		$date_part_array = explode("/", $date_part);
		$new_date_part = $date_part_array[2] . "-" . $date_part_array[1] . "-" . $date_part_array[0];
		$time_part = $date_array[1] . ":00";
		return $new_date_part . " " . $time_part;
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
			//if no image set in db or image path has no image (check image size!), set default	
			if ((!$full_img) || (!@getimagesize(SITEPATH . $full_img))){ $full_img = DEFAULT_USER_IMAGE; }
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
		$query .= " WHERE (phone_number = ? OR  email = ? OR  c.id = ?)";
        $stmt = $this->conn->prepare($query); 
        $stmt->bind_param("ssi", $phone_number, $logged_username, $logged_username);
        
		if ($stmt->execute()) {
            $stmt->store_result();
			$stmt->bind_result($id, $full_names, $first_name, $last_name, $email, $phone, $group_id, $gcm_registration_id, $group_name);
            $stmt->fetch();
			$stmt->close();
			//echo "query - $query == -id - $id, logged_username - $logged_username, logged_username - $logged_username, phone_number - $phone_number, group_id -  $group_id"; //exit;
			//echo "query - $query == $phone_number, $logged_username, $logged_username";
			
			//update login counts
			$this->updateLoginCount($logged_username);
			            
            //store user values in session vars
			session_start('USERS');
			
			//split name
			$full_name_data = explode(" ", $full_names);
			$first_name = $full_name_data[0];
			$last_name = $full_name_data[1];

			$_SESSION['SESS_ID'] = session_id();
			$_SESSION['SESS_LOGGED_USER_NAME'] = $full_names;
			$_SESSION['SESS_FULL_NAMES'] = $full_names;
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
			
			//report perms
			$read_access_report_perms=array(CREATE_REPORT_PERMISSION, UPDATE_REPORT_PERMISSION, READ_REPORT_PERMISSION, DELETE_REPORT_PERMISSION);
			if ($this->groupHasAnyRole($group_id, $read_access_report_perms)){ 
				//user can read fees data
				$_SESSION['HAS_READ_REPORT_PERMISSION'] = 1; 
			}
			if ($this->groupHasAnyRole($group_id, CREATE_REPORT_PERMISSION)){ $_SESSION['HAS_CREATE_REPORT_PERMISSION'] = 1; }
			if ($this->groupHasAnyRole($group_id, UPDATE_REPORT_PERMISSION)){ $_SESSION['HAS_UPDATE_REPORT_PERMISSION'] = 1; }
			if ($this->groupHasAnyRole($group_id, DELETE_REPORT_PERMISSION)){ $_SESSION['HAS_DELETE_REPORT_PERMISSION'] = 1; }
			
			//bulk sms perms
			$read_access_bulk_sms_perms=array(CREATE_BULK_SMS_PERMISSION, UPDATE_BULK_SMS_PERMISSION, READ_BULK_SMS_PERMISSION, DELETE_BULK_SMS_PERMISSION);
			if ($this->groupHasAnyRole($group_id, $read_access_bulk_sms_perms)){ 
				//user can read fees data
				$_SESSION['HAS_READ_BULK_SMS_PERMISSION'] = 1; 
			}
			if ($this->groupHasAnyRole($group_id, CREATE_BULK_SMS_PERMISSION)){ $_SESSION['HAS_CREATE_BULK_SMS_PERMISSION'] = 1; }
			if ($this->groupHasAnyRole($group_id, UPDATE_BULK_SMS_PERMISSION)){ $_SESSION['HAS_UPDATE_BULK_SMS_PERMISSION'] = 1; }
			if ($this->groupHasAnyRole($group_id, DELETE_BULK_SMS_PERMISSION)){ $_SESSION['HAS_DELETE_BULK_SMS_PERMISSION'] = 1; }
			
			//mpesa trans perms
			$read_access_mpesa_trans_perms=array(CREATE_MPESA_TRANS_PERMISSION, UPDATE_MPESA_TRANS_PERMISSION, READ_MPESA_TRANS_PERMISSION, DELETE_MPESA_TRANS_PERMISSION);
			if ($this->groupHasAnyRole($group_id, $read_access_mpesa_trans_perms)){ 
				//user can read fees data
				$_SESSION['HAS_READ_MPESA_TRANS_PERMISSION'] = 1; 
			}
			if ($this->groupHasAnyRole($group_id, CREATE_MPESA_TRANS_PERMISSION)){ $_SESSION['HAS_CREATE_MPESA_TRANS_PERMISSION'] = 1; }
			if ($this->groupHasAnyRole($group_id, UPDATE_MPESA_TRANS_PERMISSION)){ $_SESSION['HAS_UPDATE_MPESA_TRANS_PERMISSION'] = 1; }
			if ($this->groupHasAnyRole($group_id, DELETE_MPESA_TRANS_PERMISSION)){ $_SESSION['HAS_DELETE_MPESA_TRANS_PERMISSION'] = 1; }
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
    function checkIfSubjectPermExists($permalink, $id=NULL, $report=false){
		$query = "SELECT * FROM sch_subjects WHERE code = '$permalink' ";
		if ($report) {
			$query .= " AND id != $id ";	
		}
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
     * Check if user has logged in before
     * @return id
     */
    function loginCountExists($input)
	{
		$phone_number = $this->formatPhoneNumber($input);
		$query = "SELECT id FROM clients WHERE logged_times > 0 AND (id = ? OR phone_number = ?) ";		
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("is", $input, $phone_number);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
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
	
	//updaate mpesa fee payment status
	function updateMpesaPaymentStatus($payment_id)
	{
		
		$response = array();
				
		//get mpesa payment details
		$payment_data = $this->getSingleFee($payment_id);
		//$payment_id = $payment_data["id"];
		$amount = $payment_data["amount"];
		$ref_no = $payment_data["ref_no"];
		$student_id = $payment_data["student_id"];
		$sch_id = $payment_data["sch_id"];
		//$payment_data_rows = "payment_id - $payment_id - amount - $amount - ref_no - $ref_no - student_id - $student_id ";
		//print_r($payment_data_rows); exit;
		
		//check if ref_no exists
		//get bulk sms data
		$bulk_sms_data = $this->getBulkSMSData($sch_id); 
		//print_r($bulk_sms_data); exit; 
		$usr = $sch_id;
		$pass = $bulk_sms_data["passwd"];
		$src = $bulk_sms_data["default_source"];
		$paybill_no = $bulk_sms_data["paybill"];
		
		$creator_id = $sch_id;
		$current_date = $this->getCurrentDate();
					
		if ($usr && $pass && $paybill_no) {
		
			$url = GET_MPESA_IPNS_URL . "?usr=" . $usr . "&pass=" . $pass . "&paybill_no=" . $paybill_no . "&mpesa_code=" . $ref_no;
			//echo "url - $url == "; 
			$resp = $this->executeLink($url); 
			
			if ($resp->total) {
				
				//REF_NO EXISTS
				$status = CONFIRMED_STATUS;
				//update status field in fees payments table for this entry
				$queryUpdate  = " UPDATE sch_fees_payments SET status = ?, updated_at = ?, updated_by = ? ";
				$queryUpdate .= " WHERE id = ? ";
				$stmtUpdate = $this->conn->prepare($queryUpdate);
				$stmtUpdate->bind_param("isii", $status, $current_date, $creator_id, $payment_id);
				$result = $stmtUpdate->execute();			
				$stmtUpdate->close();
				
				// Check for successful insertion
				if ($result) {

					$response['error'] = false;
					$response['status'] = CONFIRMED_STATUS;
					$response['status_name'] = CONFIRMED_TEXT;
					$response['message'] = "Successfully updated mpesa trans";

				} else {

					// Failed
					$response['error'] = true;
					$response['status'] = PENDING_STATUS;
					$response['status_name'] = PENDING_TEXT;
					$response['message'] = "Failed to update mpesa trans";

				}
					
			} 
			//print_r($response);exit;
		} else {
			
			// Failed
			$response['error'] = true;
			$response['message'] = "Failed to get mpesa data";
				
		}
			
		return $response;
		
	}
	
	//update fee balances
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
	
	//update login counts
	function updateLoginCount($logged_username)
	{
		
		$creator_id = USER_ID;
		$current_date = $this->getCurrentDate();
		
		$phone_number = $this->formatPhoneNumber($logged_username);
		
		$query  = " UPDATE clients SET logged_times = logged_times + 1 ";
		$query .= " WHERE (phone_number = ? OR email = ? OR  id = ?)";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("ssi", $phone_number, $logged_username, $logged_username);
		$stmt->execute();			
		$stmt->close();
		
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
		//$query = "SELECT grade FROM total_points_grades WHERE (min <= ? AND max >= ?) AND sch_level = ?";
		$query = "SELECT grade FROM total_points_grades sg WHERE $average BETWEEN sg.min AND sg.max AND sch_level = $sch_level";	
		//echo "$query - $average, $average, $sch_level";	
		$stmt = $this->conn->prepare($query);
		//$stmt->bind_param("iii", $average, $average, $sch_level);
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
		//$query = "SELECT grade FROM score_grades WHERE (min <= ? AND max >= ?) AND sch_level = ?";
		$query = "SELECT grade FROM score_grades sg WHERE $average BETWEEN sg.min AND sg.max AND sch_level = $sch_level";	
		//echo "$query - $average, $average, $sch_level";	
		$stmt = $this->conn->prepare($query);
		//$stmt->bind_param("iii", $average, $average, $sch_level);
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
	function saveResultItemHistory($result_item_id, $updated_at, $updated_by)
	{
					
		//STORE ARCHIVE DATA
		//get result items details
		$result_data = $this->getResultItemData($result_item_id);
		$result_id = $result_data["result_id"];
		$subject_code = $result_data["subject_code"]; 
		$score = $result_data["score"]; 
		$grade = $result_data["grade"]; 
		$points = $result_data["points"];
		
		//store data in history table
		$query  = "INSERT INTO sch_results_items_history(results_items_id, result_id, subject_code, score, grade";
		$query .= ", points, created_at, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?) "; 
		//echo "$query - $result_item_id, $result_id, $subject_code, $score, $grade, $points, $updated_at, $updated_by";
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
	
	//get previous month names
	function getMonthStr($offset)
	{
		return date("F", strtotime("$offset months"));
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
		
		public function resizeUploadNew($tmpImageFile,$imageFileName,$pic_dir,$name_dir,$max_width,$max_height,$cropratio=NULL,$watermark=NULL,$add_to_filename=NULL){
			global $font_path, $font_size, $water_mark_text_1, $water_mark_text;
			$maxwidth = $max_width; // Max new width or height, can not exceed this value.
			$maxheight = $max_height;
			$dir = $pic_dir; // Directory to save resized image. (Include a trailing slash - /)
			// Collect the post variables.
			/*$postvars = array(
				"image"    => trim($_FILES["$field"]["name"]),
				"image_tmp"    => $_FILES["$field"]["tmp_name"],
				"image_size"    => (int)$_FILES["$field"]["size"],
				);*/
				// Array of valid extensions.
				$valid_exts = array("jpg","jpeg","gif","png");
				//$mod_exts = array("gif","png");
				// Select the extension from the file.
				$ext = end(explode(".",strtolower(trim($imageFileName))));
				//echo ("Image size: " . $postvars["image_size"] . "<br> Ext: " . $ext . "<br>");
				// Check is valid extension.
				if(in_array($ext,$valid_exts)){
					if($ext == "jpg" || $ext == "jpeg"){
						$image = imagecreatefromjpeg($tmpImageFile);
					}
					else if($ext == "gif"){
						$image = imagecreatefromgif($tmpImageFile);
					}
					else if($ext == "png"){
						$image = imagecreatefrompng($tmpImageFile);
					}
					// Grab the width and height of the image.
					list($width,$height) = getimagesize($tmpImageFile);
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