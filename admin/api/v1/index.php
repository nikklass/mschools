<?php
 
error_reporting(1);
ini_set('display_errors', 'On');
 
require_once '../includes/DB_handler.php';
require_once '../includes/Config.php';
require '../libs/Slim/Slim.php';
 
\Slim\Slim::registerAutoloader();
 
$app = new \Slim\Slim();
 
// User login
$app->post('/user/login', function() use ($app) {
    
	// check for required params
    verifyRequiredParams(array('phone_number', 'password'));
 
    // reading post params
    $phone_number = $app->request->post('phone_number');
    $password = $app->request->post('password');
 
    $db = new DbHandler();
    $response = $db->loginUser($phone_number, $password);
 
    // echo json response
    echoResponse(200, $response);
	
});

// Change User Password
$app->post('/user/changepass', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('password', 'new_password1', 'new_password2', 'username'));
 
    // reading post params
    $password = $app->request->post('password');
    $new_password1 = $app->request->post('new_password1');
	$new_password2 = $app->request->post('new_password2');
	$username = $app->request->post('username');
	
	if ($new_password1 != $new_password2) {
		
		$response['error'] = true;
		$response['noty_msg'] = true;
		$response['message'] = "Password must be the same as repeat password";
		
	} else {
 
		$db = new DbHandler();
		$response = $db->changePassword($password, $new_password1, $username);
	
	}
 
    // echo json response
    echoResponse(200, $response);
});

// Create new User Password
$app->post('/user/createpass', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('password', 'password2'));
 
    // reading post params
    $password = $app->request->post('password');
    $password2 = $app->request->post('password2');
	$sch_id = $app->request->post('sch_id');
	$user_id = $app->request->post('user_id');
	$admin = 1;
	
	if ($password != $password2) {
		
		$response['error'] = true;
		$response['noty_msg'] = true;
		$response['message'] = "Password must be the same as repeat password";
		
	} else {
 
		$db = new DbHandler();
		$response = $db->createPassword($password, $password2, $sch_id, $user_id, $admin);
	
	}
 
    // echo json response
    echoResponse(200, $response);
});

// Set User Password
$app->post('/user/setPassword', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('password', 'password2', 'user_id'));
 
    // reading post params
    $password = $app->request->post('password');
    $password2 = $app->request->post('password2');
	$user_id = $app->request->post('user_id');
	
	if ($password != $password2) {
		
		$response['error'] = true;
		$response['slide_form'] = true;
		$response['slide_duration'] = 4000;
		$response['message'] = "Password must be the same as repeat password";
		
	} else {
 
		$db = new DbHandler();
		$response = $db->setPassword($password, $user_id);
	
	}
 
    // echo json response
    echoResponse(200, $response);
});

// Forgot User Password
$app->post('/user/forgotpass', function() use ($app) {

    // check for required params
    verifyRequiredParams(array('username'));
	
	// reading post params
    $username = $app->request->post('username');
 
	$db = new DbHandler();
	$response = $db->forgotPassword($username);
 
    // echo json response
    echoResponse(200, $response);
	
});

// User logout
$app->post('/user/logout', function() use ($app) {
 
    logoutUser();	
	$response["error"] = false;
	$response["message"] = "";
	echo json_encode($response);
	
});

// Create User
$app->post('/user/register', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('phone_number', 'password', 'terms'));
 
    // reading post params
    $phone_number = $app->request->post('phone_number');
    $password = $app->request->post('password');
	$password2 = $app->request->post('password2');
	$email = $app->request->post('email');
	$first_name = $app->request->post('first_name');
	$last_name = $app->request->post('last_name');
	$full_names = $app->request->post('full_names');
	$gender = $app->request->post('gender'); 
	
	if ($password=="" || $password2==""){
		$response["error"] = true;
		$response["message"] = "password and repeat password cannot be blank!";
	} else if ($password != $password2){
		$response["error"] = true;
		$response["message"] = "password and repeat password fields do not match!";
	} else {
		$db = new DbHandler();
		$response = $db->createUser($phone_number, $password, $email, $gender, $full_names);
	}

    // echo json response
    echoResponse(200, $response);
});

// Create User
$app->post('/user/edituser', function() use ($app) {
    
	// check for required params
    verifyRequiredParams(array('phone_number'));
 
    // reading post params
    $phone_number = $app->request->post('phone_number');
    $password = $app->request->post('password');
	$password2 = $app->request->post('password2');
	$email = $app->request->post('email');
	$first_name = $app->request->post('first_name');
	$last_name = $app->request->post('last_name');
	$full_names = $app->request->post('full_names');
	$user_type = $app->request->post('user_type');
	$status = $app->request->post('status');
	$user_id = $app->request->post('user_id');
	
	
	if ($password != $password2){
		$response["error"] = true;
		$response["message"] = "Password and repeat password fields do not match!";
	} else if (($email) && (!validateEmail($email))) {
		$response["error"] = true;
		$response["message"] = "Please enter a valid email address";
	} else {
		$db = new DbHandler();
		$response = $db->editUser($user_id, $phone_number, $password, $email, $first_name, $last_name, $full_names, $user_type, $status) ;
	}

    // echo json response
    echoResponse(200, $response);
	
});

// send registration sms
$app->post('/user/sendregsms', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('phone_number'));
 
    // read post params
    $phone_number = $app->request->post('phone_number');
 
    $db = new DbHandler();
    $response = $db->sendRegSMS($phone_number);
 
    // echo json response
    echoResponse(200, $response);
});

// change registration phone number
$app->post('/user/changeregphone', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('old_phone_number', 'new_phone_number'));
 
    // read post params
    $old_phone_number = $app->request->post('old_phone_number');
	$new_phone_number = $app->request->post('new_phone_number');
 
    $db = new DbHandler();
    $response = $db->changeRegPhone($old_phone_number, $new_phone_number);
 
    // echo json response
    echoResponse(200, $response);
});

// Update single field data
$app->post('/updateSingleFieldData', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('field_name', 'field_value', 'table_name'));
 
    // reading post params
    $field_name = $app->request->post('field_name');
	$field_value = trim($app->request->post('field_value'));
	$table_name = $app->request->post('table_name');
	$primary_field_name = $app->request->post('primary_field_name');
	$primary_field_value = $app->request->post('primary_field_value');
	$data_type = $app->request->post('data_type');
 
    $db = new DbHandler();
    //$response = $db->updateSingleFieldData($field_namem, $field_value, $table_name);
	$response = $db->updateSingleFieldData($field_name, $field_value, $primary_field_name, $primary_field_value, $data_type, $table_name);
 
    // echo json response
    echoResponse(200, $response);
});

// Update activity
$app->post('/updateActivity', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('id', 'name'));
 
    // reading post params
    $id = $app->request->post('id');
	$name = trim($app->request->post('name'));
	$description = trim($app->request->post('description'));
	$venue = trim($app->request->post('venue'));
	$start_at = trim($app->request->post('start_at'));
	$end_at = trim($app->request->post('end_at'));
 
    $db = new DbHandler();
    //$response = $db->updateSingleFieldData($field_namem, $field_value, $table_name);
	$response = $db->updateActivity($id, $name, $description, $venue, $start_at, $end_at);
 
    // echo json response
    echoResponse(200, $response);
});

//create new activity
$app->post('/createActivity', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('sch_id', 'name'));
 
    // reading post params
    $sch_id = $app->request->post('sch_id');
	$name = trim($app->request->post('name'));
	$description = trim($app->request->post('description'));
	$venue = trim($app->request->post('venue'));
	$start_at = trim($app->request->post('start_at'));
	$end_at = trim($app->request->post('end_at'));
 
    $db = new DbHandler();
    
	// insert a new chat
    $response = $db->createActivity($name, $sch_id, $description, $venue, $start_at, $end_at);
 
    // echo json response
    echoResponse(200, $response);
});

//create new student fee
$app->post('/createStudentFee', function() use ($app) {
    
	// check for required params
    verifyRequiredParams(array('amount', 'payment_mode', 'paid_by', 'payment_date', 'fee_year'));
 
    // reading post params
	$amount = $app->request->post('amount');
	$payment_mode = $app->request->post('payment_mode');
	$paid_by = $app->request->post('paid_by');
	$payment_date = $app->request->post('payment_date');
	$student_id = $app->request->post('student_id');
	$ref_no = $app->request->post('ref_no');
	$sch_id = $app->request->post('sch_id');
	$reg_no = $app->request->post('reg_no');
	$year = $app->request->post('fee_year');
 
    $db = new DbHandler();
    
	// insert a new record
    $response = $db->createStudentFee($amount, $payment_mode, $paid_by, $payment_date, $student_id, $year, "", $sch_id, $reg_no, $ref_no);
 
    // echo json response
    echoResponse(200, $response);
	
});

//create new student result
$app->post('/createStudentResult', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('year', 'term', 'subject', 'score'));
 
    // reading post params
    $student_id = $app->request->post('student_id');
	$sch_id = $app->request->post('sch_id');
	$reg_no = $app->request->post('reg_no');
	$class = $app->request->post('class');
	$year = $app->request->post('year');
	$term = trim($app->request->post('term'));
	$subject = trim($app->request->post('subject'));
	$score = trim($app->request->post('score'));
 
    $db = new DbHandler();
    
	// insert a new chat
    $response = $db->createStudentResult($student_id, $sch_id, $reg_no, $year, $term, $subject, $score, $class);
 
    // echo json response
    echoResponse(200, $response);
});

//upload bulk results data
$app->post('/uploadResults', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('sch_id'));
	$success = 1;
	
	$db = new DbHandler();
	
	$sch_id = $app->request->post('sch_id');
	
	$response = array();
	$results = array();
 
    if (isset($_FILES['res_file']['name']))  
	{
		//get the file
		$file = $_FILES['res_file']['tmp_name'];
		$handle = fopen($file,"r");
		//get file extension
		$name = $_FILES["res_file"]["name"];
		$ext = end((explode(".", $name)));

		/** PHPExcel_IOFactory */
		require_once('../includes/PHPExcel/PHPExcel/IOFactory.php');
		
		
		//  Read Excel workbook
		try {
			
			$inputFileType = PHPExcel_IOFactory::identify($file);
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objPHPExcel = $objReader->load($file);
			
		} catch(Exception $e) {
			
			$response['error'] = "true";
			$response['slide_form'] = "true";
			$response['noty_msg'] = true;
			$response['message'] = "Could not open file for reading";
			$success = 0;
			//die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
			
		}
		
		if ($success) {
			
			//  Get worksheet dimensions
			$sheet = $objPHPExcel->getSheet(0); 
			$highestRow = $sheet->getHighestRow(); 
			$highestColumn = $sheet->getHighestColumn();
			//echo "hir - $highestRow";
			
			$array = $fields = array();
			
			for ($row = 1; $row <= $highestRow; $row++){ 
				
				$tmp = array();
				//  Read a row of data into an array
				$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
												NULL,
												TRUE,
												FALSE);
	
				if (empty($fields)) {
					$fields = $rowData; 
					//check if key/ subject exists before PROCEEING -- TODO ----------------------//////////////////////////////////////////////////////////////
					foreach ($rowData as $k => $value) {
						foreach ($value as $key => $val) {
							if ($key > 2) {
								//check val
								if (!($db->isSubjectCodeExists($val))) {
									$success = 0;
									$response['error'] = true;
									$response['noty_msg'] = true;
									$response['message'] = "The subject code $val is invalid. \nPlease rectify file data.";
								}
							}
						}
					}
					continue;
				} 
				
				foreach ($rowData as $k => $value) {
					
					foreach ($value as $key => $val) {
						$array[$fields[0][$key]] = $val;
					}
					
				}
				
				array_push($results, $array);
				
				$i++;
				
			}
						
			//$response['data'] = $results; 
			
			if ($success) {	
			
				//print_r($results);
				// Echo memory peak usage
				//$results["stats"] =  "Time: " . date('H:i:s') . " Peak memory usage: " . (memory_get_peak_usage(true) / 1024 / 1024) . " MB";
				//$results["sch_id"] =  $sch_id;
							
				// insert a new chat
				$response = $db->uploadResults($sch_id, $results);
			
			}
			
		}
				
	} else {
		$response['error'] = true;
		$response['noty_msg'] = true;
		$response['message'] = "Please select a file";
	}
    // echo json response
    echoResponse(200, $response);
});

//upload bulk fees data
$app->post('/uploadFees', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('sch_id'));
	$success = 1;
	
	$sch_id = $app->request->post('sch_id');
	
	$response = array();
	$fees = array();
	$send_fees = array();
 
    if (isset($_FILES['fee_file']['name']))  
	{
		//get the file
		$file = $_FILES['fee_file']['tmp_name'];
		$handle = fopen($file,"r");
		//get file extension
		$name = $_FILES["fee_file"]["name"];
		$ext = end((explode(".", $name)));
		
		// Use PCLZip rather than ZipArchive to create the Excel2007 OfficeOpenXML file
		//PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
		/** PHPExcel_IOFactory */
		require_once('../includes/PHPExcel/PHPExcel/IOFactory.php');
		
		
		//  Read Excel workbook
		try {
			
			$inputFileType = PHPExcel_IOFactory::identify($file);
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objPHPExcel = $objReader->load($file);
		} catch(Exception $e) {
			$response['error'] = "true";
			$response['slide_form'] = "true";
			$response['message'] = "Could not open file for reading";
			$success = 0;
			//die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
		}
		
		if ($success) {
			
			//  Get worksheet dimensions
			$sheet = $objPHPExcel->getSheet(0); 
			$highestRow = $sheet->getHighestRow(); 
			$highestColumn = $sheet->getHighestColumn();
			
			//  Loop through each row of the worksheet in turn
			$counter = 1;
			
			for ($row = 2; $row <= $highestRow; $row++){ 
				$tmp = array();
				//  Read a row of data into an array
				$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
												NULL,
												TRUE,
												FALSE);
	
				//read row data into array 
				//date('Y-m-d',PHPExcel_Shared_Date::ExcelToPHP($objWorksheet->getCellByColumnAndRow(1, $i)->getValue()));
				$paid_at = $rowData[0][4];
				//apply coversion to files other than csv
				if ($paid_at && ($ext!='csv')) {
					$paid_at = excel_date_formatted($paid_at); 
				} else {
					$paid_at = excel_csv_date_formatted($paid_at); 	
				}

				$tmp["reg_no"] = $rowData[0][0]; 
				$tmp["year"] = $rowData[0][1];
				$tmp["payment_mode"] = $rowData[0][2];
				$tmp["paid_by"] = $rowData[0][3];
				$tmp["paid_at"] = $paid_at;
				$tmp["amount_paid"] = $rowData[0][5];
				$tmp["total_fees"] = $rowData[0][6];			
				
				$counter++;
				array_push($fees, $tmp);
				
			}
			
			//print_r($fees); exit;
			$send_fees["fees"] = $fees;
	
			// Echo memory peak usage
			$send_fees["stats"] =  "Time: " . date('H:i:s') . " Peak memory usage: " . (memory_get_peak_usage(true) / 1024 / 1024) . " MB";
			$send_fees["sch_id"] =  $sch_id;
			
			$db = new DbHandler();
		
			// insert fees
			$response = $db->uploadFees($sch_id, $send_fees);
			
		}
				
	} else {
		$response['error'] = "true";
		$response['message'] = "Please select a file";
	}
    // echo json response
    echoResponse(200, $response);
});

//add parent/ send parent sms
$app->post('/addParent', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('sch_id', 'messageType', 'selected'));
	$success = 1;

	$db = new DbHandler();
	
	$sch_id = $app->request->post('sch_id');
	$selected = $app->request->post('selected');
	$message = $app->request->post('message');
	$messageType = $app->request->post('messageType');
	
	if (($message=="" && $messageType=="send_msg")){
		
		$response["error"] = true;
		$response["noty_msg"] = true;
		$response["message"] = "Please enter message to send!";
		
	} else if ($selected==""){
		
		$response["error"] = true;
		$response["noty_msg"] = true;
		$response["message"] = "Please select parents to add/ send message to";
		
	} else {
		
		//check for any invalid contact from list
		if ($selected) {
			//SELECTED CONTACTS
			//split selected items into array
			$selected_array = explode(",", $selected);
			
			$good_phone_numbers = array();
			$bad_phone_numbers = array();
			$empty_parent_names = array();
			$empty_phone_numbers = array();

			for ($i=0; $i<count($selected_array); $i++) {
				$id = trim($selected_array[$i]);
				//get phone number
				$student_data = $db->getStudentData("","","","",$id);
				$guardian_name = $student_data["guardian_name"];
				$phone_number = $student_data["guardian_phone"];
				$student_full_names = $student_data["student_full_names"];
				//print_r($student_data); exit;
				if ($phone_number) {
					if (!$db->isNumberValid($phone_number)){
						//phone number is not valid, add to bad_phone_number var
						$bad_phone_numbers[] = $student_full_names . " /" . $guardian_name;
						$success = 0;
					} 
				}
				
				if (!$guardian_name) {
					$empty_parent_names[] = $student_full_names . " /" . $guardian_name;
					$success = 0;
				}
				
				if (!$phone_number) {
					$empty_phone_numbers[] = $student_full_names."/ ".$guardian_name;
					$success = 0;
				}
			}
			
		} 
		
		if (!$success && (count($bad_phone_numbers) > 0)) {
			
			$bad_phone_numbers_text = implode("\n", $bad_phone_numbers);
			$response["error"] = true;
			$response["noty_msg"] = true;
			$response["message"] = "Invalid Phone Number(s)!\n\nPlease correct the phone numbers for the following student(s)/ parent(s): \n\n$bad_phone_numbers_text ";
			
		} else if (!$success && (count($empty_parent_names) > 0)) {
			
			$empty_parent_names_text = implode("\n", $empty_parent_names); 
			$response["error"] = true;
			$response["noty_msg"] = true;
			$response["message"] = "Empty parent names!\n\nPlease correct the parent names of the following  student(s)/ parent(s): \n\n$empty_parent_names_text ";
			
		} else if (!$success && (count($empty_phone_numbers) > 0)) {
			
			$empty_phone_numbers_text = implode("\n", $empty_phone_numbers); 
			$response["error"] = true;
			$response["noty_msg"] = true;
			$response["message"] = "Empty phone number(s)!\n\nPlease correct the phone numbers for the following  student(s)/ parent(s) entries: \n\n$empty_phone_numbers_text ";
			
		} else {
			
			//OK
			$response = array();		
			
			// Echo memory peak usage
			$send_data["stats"] =  "Time: " . date('H:i:s') . " Peak memory usage: " . (memory_get_peak_usage(true) / 1024 / 1024) . " MB";
			
			// add parent/ send sms
			//echo "top_sch_id - $sch_id"; exit;
			
			$response = $db->addParent($sch_id, $message, $messageType, $selected_array, $send_data);
		
		}
	
	}	
 
    // echo json response
    echoResponse(200, $response);
	
});

//send bulk sms
$app->post('/sendBulkSMS', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('sch_id', 'messageType'));
	$success = 1;

	$db = new DbHandler();
	
	$sch_id = $app->request->post('sch_id');
	$selected = $app->request->post('selected');
	$enter_contacts = $app->request->post('enter_contacts_field');
	$enter_contacts = rtrim($enter_contacts, ", \t\n"); //remove trailing commas and whitespace
	$message = $app->request->post('message');
	$selected = $app->request->post('selected');
	$messageType = $app->request->post('messageType');
	$results_year = $app->request->post('results_year');
	$fees_year = $app->request->post('fees_year');
	$term = $app->request->post('term');
	
	//define arrays
	$good_phone_numbers = array();
	$bad_phone_numbers = array();
	$empty_parent_names = array();
	$empty_phone_numbers = array();
	$no_fee_records = array();
	$no_result_records = array();
	
	if (($message=="" && $messageType=="memo") || ($fees_year=="" && $messageType=="fees") || ($results_year=="" && $messageType=="results")){
		
		$response["error"] = true;
		$response["noty_msg"] = true;
		$response["message"] = "Please enter all required data to proceed!";
		
	} else if ($selected=="" && $enter_contacts==""){
		
		$response["error"] = true;
		$response["noty_msg"] = true;
		$response["message"] = "Please select student(s)/ parent(s) or type the phone numbers to send message to";
		
	} else {		
		
		//if contacts have been typed in, check for any invalid contact from list
		if ($enter_contacts) {
			//TYPED IN CONTACTS
			$student_data = 0;

			//$contacts_array = explode(",", $enter_contacts);
			//split data using comma+, space+ or new lines+
			$contacts_array = preg_split('/[\ \n\,\s]+/', $enter_contacts);
			//print_r($contacts_array);
			for ($i=0; $i<count($contacts_array); $i++) {
				$phone_number = trim($contacts_array[$i]);
				if ($phone_number) {
					if (!$db->isNumberValid($phone_number)){
						//phone number is not valid, add to bad_phone_number var
						$bad_phone_numbers[] = $phone_number;
						$success = 0;
					} else {
						//good number, add to phone number array
						$selected_array[] = $phone_number;
					}
				} else {
					$empty_phone_numbers[] = $phone_number;
					$success = 0;	
				}
			}
			
		} else {
			
			//SELECTED CONTACTS
			//split selected items into array
			$selected_array = explode(",", $selected);
			$student_data = 1;
			
		}
		
		//validate selected items on grid/ list
		//check for any invalid contact 
		if (!($enter_contacts) && count($selected_array) > 0) {

			//SELECTED CONTACTS			

			for ($i=0; $i<count($selected_array); $i++) {
				
				$id = trim($selected_array[$i]);
				//get phone number
				$student_data_array = $db->getStudentData("","","","",$id);
				$guardian_name = $student_data_array["guardian_name"];
				$phone_number = $student_data_array["guardian_phone"];
				$student_full_names = $student_data_array["student_full_names"];
				//print_r($student_data); exit;
				if ($phone_number) {
					if (!$db->isNumberValid($phone_number)){
						//phone number is not valid, add to bad_phone_number var
						$bad_phone_numbers[] = $student_full_names . " /" . $guardian_name;
						$success = 0;
					} 
				}
				
				if (!$guardian_name && $success) {
					$empty_parent_names[] = $student_full_names . " /" . $guardian_name;
					$success = 0;
				}
				
				if (!$phone_number && $success) {
					$empty_phone_numbers[] = $student_full_names."/ ".$guardian_name;
					$success = 0;
				}
				
				//validate fees and results data if they are empty
				//check fees
				if ($messageType=="fees" && $success) {
					
					//does fee record exist?
					if ($db->studentFeeExists($id, "", "", $fees_year)) {
						//fee record exists
						$success = 1;
					} else {
						//no record	exists
						$no_fee_records[] = $student_full_names." &nbsp; <strong>Year:</strong> ".$fees_year;
						$success = 0;
					}
					
				}
				
				//check results
				if ($messageType=="results" && $success) {

					//does result record exist?
					if ($db->studentResultExists($id, "", "", $results_year, $term)) {
						//fee record exists
						$success = 1;
					} else {
						//no record	exists
						$no_result_records[] = $student_full_names." &nbsp; <strong>Year:</strong> ".$results_year."  <strong>Term:</strong> ".$term;
						$success = 0;
					}
					
				}
				
			}
			
		} 
		
		if (!$success && !$enter_contacts && (count($bad_phone_numbers) > 0)) {
			
			$bad_phone_numbers_text = implode("\n", $bad_phone_numbers);
			$response["error"] = true;
			$response["noty_msg"] = true;
			$response["message"] = "Invalid Phone Number(s)!\n\nPlease correct the phone numbers for the following student(s)/ parent(s): \n\n$bad_phone_numbers_text ";
			
		} else if (!$success && $enter_contacts && (count($bad_phone_numbers) > 0)) {
			
			$bad_phone_numbers_text = implode("\n", $bad_phone_numbers);
			$response["error"] = true;
			$response["noty_msg"] = true;
			$response["message"] = "Invalid Phone Number(s)!\n\nPlease correct the following phone numbers: \n\n$bad_phone_numbers_text ";
			
		} else if (!$success && (count($empty_parent_names) > 0)) {
			
			$empty_parent_names_text = implode("\n", $empty_parent_names); 
			$response["error"] = true;
			$response["noty_msg"] = true;
			$response["message"] = "Empty parent names!\n\nPlease correct the parent names of the following  student(s)/ parent(s): \n\n$empty_parent_names_text ";
			
		} else if (!$success && !$enter_contacts && (count($empty_phone_numbers) > 0)) {
			
			$empty_phone_numbers_text = implode("\n", $empty_phone_numbers); 
			$response["error"] = true;
			$response["noty_msg"] = true;
			$response["message"] = "Empty phone number(s)!\n\nPlease correct the phone numbers for the following  student(s)/ parent(s) entries: \n\n$empty_phone_numbers_text ";
			
		}else if (!$success && $enter_contacts && (count($empty_phone_numbers) > 0)) {
			
			$response["error"] = true;
			$response["noty_msg"] = true;
			$response["message"] = "Empty phone number(s)!\n\nPlease enter a single comma after each phone number ";
			
		} else if (!$success && (count($no_result_records) > 0)) {
			
			$no_result_records_text = implode("\n", $no_result_records); 
			$response["error"] = true;
			$response["noty_msg"] = true;
			$response["message"] = "No result records exist for the following student(s) and period!\n\nPlease unselect the student(s) to proceed: \n\n$no_result_records_text ";
			
		} else if (!$success && (count($no_fee_records) > 0)) {
			
			$no_fee_records_text = implode("\n", $no_fee_records); 
			$response["error"] = true;
			$response["noty_msg"] = true;
			$response["message"] = "No fee records exist for the following student(s) and period!\n\nPlease unselect the student(s) to proceed: \n\n$no_fee_records_text ";
			
		} else {
		
			//OK
			$response = array();		
			
			// Echo memory peak usage
			$send_data["stats"] =  "Time: " . date('H:i:s') . " Peak memory usage: " . (memory_get_peak_usage(true) / 1024 / 1024) . " MB";
			
			// send new sms
			$response = $db->sendBulkSMSToUser($sch_id, $message, $messageType, $results_year, $fees_year, $term, $selected_array, $send_data, $student_data);
		
		}
	
	}	
 
    // echo json response
    echoResponse(200, $response);
	
});

//upload bulk students data
$app->post('/uploadStudents', function() use ($app) {
    
	// check for required params
    verifyRequiredParams(array('sch_id'));
	$success = 1;
	
	$db = new DbHandler();
	
	$sch_id = $app->request->post('sch_id');
	$user_id = $app->request->post('user_id');
	
	$response = array();
	$students = array();
	$send_students = array();
 
    if (isset($_FILES['student_file']['name']))  
	{
		//get the file
		$file = $_FILES['student_file']['tmp_name'];
		$handle = fopen($file,"r");
		//get file extension
		$name = $_FILES["student_file"]["name"];
		$ext = end((explode(".", $name)));
		
		/** PHPExcel_IOFactory */
		require_once('../includes/PHPExcel/PHPExcel/IOFactory.php');
		
		//  Read Excel workbook
		try {
			// Use PCLZip rather than ZipArchive to create the Excel2007 OfficeOpenXML file
			//PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
			$inputFileType = PHPExcel_IOFactory::identify($file);
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objPHPExcel = $objReader->load($file);
		} catch(Exception $e) {
			$response['error'] = "true";
			$response['slide_form'] = "true";
			$response['message'] = "Could not open file for reading";
			$success = 0;
			//die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
		}
		
		if ($success) {
			
			//  Get worksheet dimensions
			$sheet = $objPHPExcel->getSheet(0); 
			$highestRow = $sheet->getHighestRow(); 
			$highestColumn = $sheet->getHighestColumn();
			
			//  Loop through each row of the worksheet in turn
			$counter = 1;
			
			//define error arrays
			$bad_phone_numbers = array();
			$bad_dobs = array();
			$bad_admin_dates = array();
			
			
			for ($row = 2; $row <= $highestRow; $row++){ 
				
				$success = 1;
				
				$tmp = array();
				//  Read a row of data into an array
				$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
												NULL,
												TRUE,
												FALSE);
	
				//read row data into array 
				$admin_date = $rowData[0][2];
				$dob = $rowData[0][4];
				$student_full_names = $rowData[0][1];
				$guardian_mobile = $rowData[0][16];
				
				//echo "admin_date - $admin_date - dob - $dob -  student_full_names - $student_full_names - guardian_mobile -$guardian_mobile == ";
				
				//check for invalid values
				if ($guardian_mobile) {
					if (!$db->isNumberValid($guardian_mobile)){
						//phone number is not valid, add to bad_phone_number var
						$bad_phone_numbers[] = $student_full_names. " - " .$guardian_mobile;
						$success = 0;
					} //else { echo "hapaaa ... $guardian_mobile ... "; }
				}
				
				if ($dob && $ext=='csv' && !valid_date($dob, 1)) {
					$bad_dobs[] = $student_full_names. " - " .$dob;
					$success = 0;
				}
				
				if ($dob && $ext!='csv' && !valid_date($dob)) {
					$bad_dobs[] = $student_full_names. " - " .$dob;
					$success = 0;
				}
				
				if ($admin_date && $ext=='csv' && !valid_date($admin_date, 1)) {
					$bad_admin_dates[] = $student_full_names. " - " .$admin_date;
					$success = 0;
				}
				
				if ($admin_date && $ext!='csv' && !valid_date($admin_date)) {
					$bad_admin_dates[] = $student_full_names. " - " .$admin_date;
					$success = 0;
				}
				//end check for invalid values
				
				if ($success) {
				
					//apply coversion to files other than csv
					if ($admin_date && ($ext!='csv')) {
						$admin_date = excel_date_formatted($admin_date); 
					} else {
						$admin_date = excel_csv_date_formatted($admin_date); 	
					}
					
					if ($dob && ($ext!='csv')) {
						$dob = excel_date_formatted($dob); 
					} else {
						$dob = excel_csv_date_formatted($dob); 	
					}
					//echo "$admin_date - $dob - $guardian_mobile == "; 
					
					$tmp["reg_no"] = $rowData[0][0]; 
					$tmp["full_names"] = $rowData[0][1];
					$tmp["admin_date"] = $admin_date;
					$tmp["student_profile"] = $rowData[0][3];
					$tmp["dob"] = $dob;
					$tmp["index_no"] = $rowData[0][5];
					$tmp["nationality"] = $rowData[0][6];
					$tmp["religion"] = $rowData[0][7];
					$tmp["previous_school"] = $rowData[0][8];			
					$tmp["house"] = $rowData[0][9]; 
					$tmp["club"] = $rowData[0][10];
					$tmp["guardian_id_card"] = $rowData[0][11];
					$tmp["guardian_name"] = $rowData[0][12];
					$tmp["guardian_relation"] = $rowData[0][13];
					$tmp["guardian_occupation"] = $rowData[0][14];
					$tmp["guardian_address"] = $rowData[0][15];
					$tmp["guardian_mobile"] = $guardian_mobile;
					$tmp["email"] = $rowData[0][17];
					$tmp["town"] = $rowData[0][18];
					$tmp["village"] = $rowData[0][19];
					$tmp["location"] = $rowData[0][20];
					$tmp["county"] = $rowData[0][21];
					$tmp["disability"] = $rowData[0][22];
					$tmp["gender"] = $rowData[0][23];
					$tmp["current_class"] = $rowData[0][24];
					$tmp["stream"] = $rowData[0][25];
					$tmp["constituency"] = $rowData[0][26];
					
					$counter++;
					array_push($students, $tmp);
					
				}
									
			}
			
			//echo "exit"; exit;
			
			//check if we have an error
			if (count($bad_phone_numbers) > 0) {
					
				$bad_phone_numbers_text = implode("\n", $bad_phone_numbers);
				$response["error"] = true;
				$response["noty_msg"] = true;
				$response["message"] = "Invalid Guardian Phone Number(s)!\n\nPlease correct the guardian phone numbers for the following student(s): \n\n$bad_phone_numbers_text ";
				
			} else if (count($bad_dobs) > 0) {
				
				$bad_dobs_text = implode("\n", $bad_dobs); 
				$response["error"] = true;
				$response["noty_msg"] = true;
				$response["message"] = "Invalid Date of Birth value(s)! \n\n(Required format: <strong>mm/dd/yyyy</strong> e.g. <strong>03/20/2000</strong>)\n\nPlease correct the date of birth values of the following  student(s): \n\n$bad_dobs_text ";
				
			} else if (count($bad_admin_dates) > 0) {
				
				$bad_admin_dates_text = implode("\n", $bad_admin_dates); 
				$response["error"] = true;
				$response["noty_msg"] = true;
				$response["message"] = "Invalid Admission Date value(s)! \n\n(Required format: <strong>mm/dd/yyyy</strong> e.g. <strong>03/20/2000</strong>)\n\nPlease correct the admision date values of the following  student(s): \n\n$bad_admin_dates_text ";
				
			} else {
	
				//print_r($students); exit;
				$send_students["students"] = $students;
		
				// Echo memory peak usage
				$send_students["stats"] =  "Time: " . date('H:i:s') . " Peak memory usage: " . (memory_get_peak_usage(true) / 1024 / 1024) . " MB";
				$send_students["sch_id"] =  $sch_id;
							
				// insert a new student
				$response = $db->uploadStudents($sch_id, $send_students, $user_id);
			
			}
			
		}
				
	} else {
		$response['error'] = "true";
		$response['message'] = "Please select a file";
	}
		
    
 
    // echo json response
    echoResponse(200, $response);
});

//upload bulk students data
$app->post('/uploadUserPic', function() use ($app) {
    // check for required params
    //verifyRequiredParams(array('user_id'));
	
	$user_id = $app->request->post('user_id');
	$user_profile = $app->request->post('user_profile');
	$student_id = $app->request->post('student_id');
	$student_profile = $app->request->post('student_profile');
	$school_id = $app->request->post('school_id');
	$school_profile = $app->request->post('school_profile');
	$image_caption = $app->request->post('image_caption');
	
	//SET PHOTO VARIABLES
	if ($user_id){
		$item_id = $user_id;
		$user_profile==1 ? $image_type = USER_PROFILE_PHOTO : $image_type = USER_OTHER_PHOTO;	 
	}
	
	if ($student_id){
		$item_id = $student_id;
		$student_profile==1 ? $image_type = STUDENT_PROFILE_PHOTO : $image_type = STUDENT_OTHER_PHOTO;	 
	}
	
	if ($school_id){
		$item_id = $school_id;
		$school_profile==1 ? $image_type = SCHOOL_PROFILE_PHOTO : $image_type = SCHOOL_OTHER_PHOTO;	 
	}
	//END SET PHOTO VARIABLES
	
	$response = array();
	
	$db = new DbHandler();
 
    if (isset($_FILES['user_pic']['size']))  
	{
				
		$field = "user_pic";
		
		//set the directories
		
		$users_thumb_pic_dir = "../../images/users/thumbs/";
		$users_thumb_name_dir = "images/users/thumbs/";
		$users_pic_dir = "../../images/users/";
		$users_name_dir = "images/users/";
		
		$schools_thumb_pic_dir = "../../images/schools/thumbs/";
		$schools_thumb_name_dir = "images/schools/thumbs/";
		$schools_pic_dir = "../../images/schools/";
		$schools_name_dir = "images/schools/"; 
		
		$students_thumb_pic_dir = "../../images/students/thumbs/";
		$students_thumb_name_dir = "images/students/thumbs/";
		$students_pic_dir = "../../images/students/";
		$students_name_dir = "images/students/";
		
		if ($user_id){ $thumb_pic_dir = $users_thumb_pic_dir; } else 
		if ($school_id){ $thumb_pic_dir = $schools_thumb_pic_dir; } else 
		if ($student_id){ $thumb_pic_dir = $students_thumb_pic_dir; }
		
		if ($user_id){ $thumb_name_dir = $users_thumb_name_dir; } else 
		if ($school_id){ $thumb_name_dir = $schools_thumb_name_dir; } else 
		if ($student_id){ $thumb_name_dir = $students_thumb_name_dir; }
		
		if ($user_id){ $pic_dir = $users_pic_dir; } else 
		if ($school_id){ $pic_dir = $schools_pic_dir; } else 
		if ($student_id){ $pic_dir = $students_pic_dir; }
		
		if ($user_id){ $name_dir = $users_name_dir; } else 
		if ($school_id){ $name_dir = $schools_name_dir; } else 
		if ($student_id){ $name_dir = $students_name_dir; }
		
		//End set the directories
		
		$max_width = 400;
		$max_height = 400;
		$thumb_max_height = 80;
		$thumb_max_width = 80;
		$cropratio = "1:1";
		
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

		//resize and upload the image
		//resizeUpload($field,$pic_dir,$name_dir,$max_width,$max_height,$cropratio=NULL,$watermark=NULL,$add_to_filename=NULL);
		$new_image = $db->resizeUpload($field,$pic_dir,$name_dir,$max_width,$max_height,$cropratio);
		$new_thumb_image = $db->resizeUpload($field,$thumb_pic_dir,$thumb_name_dir,$thumb_max_width,$thumb_max_height,$cropratio,"","");
		//store the new files to db
		$response = $db->savePhoto($image_type, $item_id, $image_caption, $new_thumb_image, $new_image);
				
	} else {
		$response['error'] = true;
		$response['message'] = "An error occured. Please try again.";
	}
 
    // echo json response
    echoResponse(200, $response);
	
});

//create new school
$app->post('/createSchool', function() use ($app) {

    // reading post params
	$sch_name = trim($app->request->post('sch_name'));
	$sch_first_name = trim($app->request->post('sch_first_name'));
    $sch_category = $app->request->post('sch_category');
	$sch_province = $app->request->post('sch_province');
	$sch_county = trim($app->request->post('sch_county'));
	$status = trim($app->request->post('status'));
	$motto = trim($app->request->post('motto'));
	$phone1 = trim($app->request->post('phone1'));
	$phone2 = trim($app->request->post('phone2'));
	$sms_welcome1 = trim($app->request->post('sms_welcome1'));
	$sms_welcome2 = trim($app->request->post('sms_welcome2'));
	$address = trim($app->request->post('address'));
	$sch_level = trim($app->request->post('sch_level'));
	$paybill_no = trim($app->request->post('paybill_no'));
 
    $db = new DbHandler();
    
	// insert a new chat
    $response = $db->createSchool($sch_name, $sch_first_name, $sch_category, $sch_province, $sch_county, $sch_level, $status, $motto, $phone1, $phone2, $sms_welcome1, $sms_welcome2, $address, $paybill_no);
 
    // echo json response
    echoResponse(200, $response);
	
});


//create new total score grade
$app->post('/createTotalScoreGrade', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('max'));
 
    // reading post params
	$min = trim($app->request->post('min'));
    $max = $app->request->post('max');
	$points = $app->request->post('points');
	$grade = $app->request->post('grade');
	$level = $app->request->post('level');
 
    $db = new DbHandler();
    
	// insert a new chat
    $response = $db->createTotalScoreGrade($min, $max, $points, $grade, $level);
 
    // echo json response
    echoResponse(200, $response);
});


//get activities
$app->post('/fetchActivitiesGridListing', function() use ($app) {
 
    // reading post params
	$id = $app->request->post('id');
	$sch_id = $app->request->post('sch_id');
	$start_date = $app->request->post('start_date');
	$end_date = $app->request->post('end_date');
	$page = $app->request->post('current');
	$limit = $app->request->post('limit');
	$sort = $app->request->post('sort');
	$search_text = $app->request->post('searchPhrase');
	$user_id = $app->request->post('user_id');
	$admin = $app->request->post('admin');
 
    $db = new DbHandler();
    
	// fetch data
	$result = $db->fetchActivitiesGridListing($id, $sch_id, $start_date, $end_date, $page, $search_text, $limit, $sort, $user_id, $admin);
 
    // echo json response
    echoResponse(200, $result);
	
});



//get score grade history
$app->post('/fetchScoreGradeHistory', function() use ($app) {
 
    // reading post params
	$id = $app->request->post('id');
	$page = $app->request->post('current');
	$limit = $app->request->post('limit');
	$sort = $app->request->post('sort');
	$search_text = $app->request->post('searchPhrase');
	$user_id = $app->request->post('user_id');
	$admin = $app->request->post('admin');
 
    $db = new DbHandler();
    
	// fetch data
    $result = $db->fetchScoreGradeHistory($page, $user_id, $limit, $admin, $id, $sort, $search_text);
 
    // echo json response
    echoResponse(200, $result);
	
});

//get total score grade history
$app->post('/fetchTotalScoreGradeHistory', function() use ($app) {
 
    // reading post params
	$id = $app->request->post('id');
	$page = $app->request->post('current');
	$limit = $app->request->post('limit');
	$sort = $app->request->post('sort');
	$search_text = $app->request->post('searchPhrase');
	$user_id = $app->request->post('user_id');
	$admin = $app->request->post('admin');
 
    $db = new DbHandler();
    
	// fetch data
    $result = $db->fetchTotalScoreGradeHistory($page, $user_id, $limit, $admin, $id, $sort, $search_text);
 
    // echo json response
    echoResponse(200, $result);
	
});


//create new score grade
$app->post('/createScoreGrade', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('max'));
 
    // reading post params
	$min = trim($app->request->post('min'));
    $max = $app->request->post('max');
	$points = $app->request->post('points');
	$grade = $app->request->post('grade');
	$level = $app->request->post('level'); 
 
    $db = new DbHandler();
    
	// insert a new chat
    $response = $db->createScoreGrade($min, $max, $points, $grade, $level);
 
    // echo json response
    echoResponse(200, $response);
});


//create new subject
$app->post('/createSubject', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('subject_name', 'short_name', 'code'));
 
    // reading post params
	$subject_name = trim($app->request->post('subject_name'));
    $short_name = $app->request->post('short_name');
	$code = $app->request->post('code');
	$level = $app->request->post('level');
 
    $db = new DbHandler();
    
	// insert a new chat
    $response = $db->createSubject($subject_name, $short_name, $code, $level);
 
    // echo json response
    echoResponse(200, $response);
});

//edit subject
$app->post('/editSubject', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('subject_name', 'short_name', 'code'));
 
    // reading post params
	$id = trim($app->request->post('id'));
	$subject_name = trim($app->request->post('subject_name'));
    $short_name = $app->request->post('short_name');
	$code = $app->request->post('code');
	$level = $app->request->post('level');
	$status = $app->request->post('status');
 
    $db = new DbHandler();
    
	// insert a new chat
    $response = $db->editSubject($id, $subject_name, $short_name, $code, $level, $status);
 
    // echo json response
    echoResponse(200, $response);
});


//edit total score grade
$app->post('/editTotalScoreGrade', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('min', 'max', 'level'));
 
    // reading post params
	$id = trim($app->request->post('id'));
	$min = trim($app->request->post('min'));
    $max = $app->request->post('max');
	$grade = $app->request->post('grade');
	$points = $app->request->post('points');
	$level = $app->request->post('level');
 
    $db = new DbHandler();
    
	// insert a new chat
    $response = $db->editTotalScoreGrade($id, $min, $max, $grade, $points, $level, $user_id);
 
    // echo json response
    echoResponse(200, $response);
});

//edit score grade
$app->post('/editScoreGrade', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('min', 'max', 'level'));
 
    // reading post params
	$id = trim($app->request->post('id'));
	$min = trim($app->request->post('min'));
    $max = $app->request->post('max');
	$grade = $app->request->post('grade');
	$points = $app->request->post('points');
	$level = $app->request->post('level');
 
    $db = new DbHandler();
    
	// insert a new chat
    $response = $db->editScoreGrade($id, $min, $max, $grade, $points, $level, $user_id);
 
    // echo json response
    echoResponse(200, $response);
});

//edit score grade
$app->post('/editParent', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('id'));
 
    // reading post params
	$id = trim($app->request->post('id'));
	$guardian_name = trim($app->request->post('guardian_name'));
    $guardian_relation = trim($app->request->post('guardian_relation'));
	$guardian_occupation = trim($app->request->post('guardian_occupation'));
	$guardian_phone = trim($app->request->post('guardian_phone'));
	$guardian_id_card = trim($app->request->post('guardian_id_card'));
	$user_id = $app->request->post('user_id');
	$sch_id = $app->request->post('sch_id');
	$admin = $app->request->post('admin');
 
    $db = new DbHandler();
    
	// insert a new chat
    $response = $db->editParent($id, $guardian_name, $guardian_relation, $guardian_occupation, $guardian_phone, $guardian_id_card, $sch_id, $user_id, $admin);
 
    // echo json response
    echoResponse(200, $response);
});



//get bulk sms balance
$app->post('/getBulkSmsBalance', function() use ($app) {
 
    // reading post params
	$sch_id = $app->request->post('sch_id');
	$user_id = $app->request->post('user_id');
	$admin = $app->request->post('admin');
 
    $db = new DbHandler();
    
	// insert a new chat
    $response = $db->getBulkSmsBalance($sch_id, $user_id, $admin);
 
    // echo json response
    echoResponse(200, $response);
	
});

//get sms inbox
$app->post('/fetchSMSInbox', function() use ($app) {
 
    // reading post params
	$sch_id = $app->request->post('sch_id');
	$user_id = $app->request->post('user_id');
	$admin = $app->request->post('admin');
	$start_date = $app->request->post('start_date');
	$end_date = $app->request->post('end_date');
	$id = $app->request->post('id');
	$user_phone_number = $app->request->post('user_phone_number');
	$page = $app->request->post('current');
	$lperpage = $app->request->post('rowCount');
	$searchPhrase = $app->request->post('searchPhrase');
	$sort = $app->request->post('sort');
 
    $db = new DbHandler();
    
	// insert a new chat
    $response = $db->fetchSMSInbox($sch_id, $user_id, $admin, $start_date, $end_date, $id, $user_phone_number, $page, $lperpage, $sort, $searchPhrase);
 
    // echo json response
    echoResponse(200, $response);
	
});

//get mpesa inbox
$app->post('/fetchMPESAInbox', function() use ($app) {
 
    // reading post params
	$sch_id = $app->request->post('sch_id');
	$sender_no = $app->request->post('sender_no');
	$account_no = $app->request->post('account_no');
	$paybill_no = $app->request->post('paybill_no');
	$mpesa_code = $app->request->post('mpesa_code');
	$user_id = $app->request->post('user_id');
	$admin = $app->request->post('admin');
	$start_date = $app->request->post('start_date');
	$end_date = $app->request->post('end_date');
	$id = $app->request->post('id');
	$page = $app->request->post('current');
	$lperpage = $app->request->post('rowCount');
	$searchPhrase = $app->request->post('searchPhrase');
	$sort = $app->request->post('sort');
	$no_pagination = $app->request->post('no_pagination');
 
    $db = new DbHandler();
    
	// insert a new chat
    $response = $db->fetchMPESAInbox($sch_id, $id, $sender_no, $account_no, $paybill_no, $mpesa_code, $user_id, $admin, $start_date, $end_date, $page, $lperpage, $sort, $searchPhrase, $no_pagination);
 
    // echo json response
    echoResponse(200, $response);
	
});


//check if paybill is valid
$app->post('/isPaybillValid', function() use ($app) {
 
    // reading post params
	$sch_id = $app->request->post('sch_id');
	$user_id = $app->request->post('user_id');
	$admin = $app->request->post('admin');
 
    $db = new DbHandler();
    
	// fetch
    $response = $db->isPaybillValid($sch_id, $user_id, $admin);
 
    // echo json response
    echoResponse(200, $response);
	
});


//update group permissions
$app->post('/updateGroupPermissions', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('user_id', 'check_name', 'check_val'));
 
    // reading post params
	$user_id = $app->request->post('user_id');
    $check_name = $app->request->post('check_name');
	$check_val = $app->request->post('check_val');
	$sch_id = $app->request->post('sch_id');
 
    $db = new DbHandler();
    
	// insert a new chat
    $response = $db->updateGroupPermissions($user_id, $check_name, $check_val, $sch_id);
 
    // echo json response
    echoResponse(200, $response);
});


//create new user group
$app->post('/createUserGroup', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('group_name'));
 
    // reading post params
	$group_name = trim($app->request->post('group_name'));
    $group_description = $app->request->post('group_description');
 
    $db = new DbHandler();
    
	// insert a new chat
    $response = $db->createUserGroup($group_name, $group_description);
 
    // echo json response
    echoResponse(200, $response);
});

//create new student
$app->post('/createStudent', function() use ($app) {
    // check for required params
    //verifyRequiredParams(array('sch_name', 'sch_category'));
 
    // reading post params
	$full_names = trim($app->request->post('full_names'));
    $reg_no = trim($app->request->post('reg_no'));
	$sch_id = $app->request->post('sch_id');
	$admin_date = $app->request->post('admin_date');
	$student_profile = trim($app->request->post('student_profile'));
	$guardian_name = trim($app->request->post('guardian_name'));
	$guardian_phone = trim($app->request->post('guardian_phone'));
	$guardian_address = trim($app->request->post('guardian_address'));
	$dob = trim($app->request->post('dob'));
	$index_no = trim($app->request->post('index_no'));
	$nationality = trim($app->request->post('nationality'));
	$religion = trim($app->request->post('religion'));
	$previous_school = trim($app->request->post('previous_school'));
	$house = trim($app->request->post('house'));
	$club = trim($app->request->post('club'));
	$guardian_id_card = trim($app->request->post('guardian_id_card'));
	$guardian_relation = trim($app->request->post('guardian_relation'));
	$guardian_occupation = trim($app->request->post('guardian_occupation'));
	$email = trim($app->request->post('email'));
	$town = trim($app->request->post('town'));
	$current_class = trim($app->request->post('current_class'));
	$village = trim($app->request->post('village'));
	$county = $app->request->post('county');
	$location = trim($app->request->post('location'));
	$disability = trim($app->request->post('disability'));
	$gender = trim($app->request->post('gender'));
	$stream = trim($app->request->post('stream'));
	$constituency = trim($app->request->post('constituency'));
	$user_id = $app->request->post('user_id');
	
	//CHECK VALID EMAIL ADDRESS
	if ($email && !validateEmail($email)) {
		
		$response["error"] = true;
		$response["message"] = INVALID_EMAIL_ERROR_MESSAGE;
		
	} else {
 
		$db = new DbHandler();
		// insert a new chat
		$response = $db->createStudent($full_names, $reg_no, $sch_id, $admin_date, $student_profile, $guardian_name, $guardian_phone, $guardian_address, $dob, $index_no, $nationality, $religion, $previous_school, $house, $club, $guardian_id_card, $guardian_relation, $guardian_occupation, $email, $town, $current_class, $village, $county, $location, $disability, $gender, $stream, $constituency, $user_id);
	
	}
 
    // echo json response
    echoResponse(200, $response);
});

//edit student
$app->post('/editStudent', function() use ($app) {
 
    // reading post params
	$id = trim($app->request->post('id'));
	$user_id = trim($app->request->post('user_id'));
	$full_names = trim($app->request->post('full_names'));
    $reg_no = trim($app->request->post('reg_no'));
	$sch_id = $app->request->post('sch_id');
	$admin_date = $app->request->post('admin_date');
	$student_profile = trim($app->request->post('student_profile'));
	$guardian_name = trim($app->request->post('guardian_name'));
	$guardian_phone = trim($app->request->post('guardian_phone'));
	$guardian_address = trim($app->request->post('guardian_address'));
	$dob = trim($app->request->post('dob'));
	$index_no = trim($app->request->post('index_no'));
	$nationality = trim($app->request->post('nationality'));
	$religion = trim($app->request->post('religion'));
	$previous_school = trim($app->request->post('previous_school'));
	$house = trim($app->request->post('house'));
	$club = trim($app->request->post('club'));
	$guardian_id_card = trim($app->request->post('guardian_id_card'));
	$guardian_relation = trim($app->request->post('guardian_relation'));
	$guardian_occupation = trim($app->request->post('guardian_occupation'));
	$email = trim($app->request->post('email'));
	$town = trim($app->request->post('town'));
	$current_class = trim($app->request->post('current_class'));
	$village = trim($app->request->post('village'));
	$county = trim($app->request->post('county'));
	$location = trim($app->request->post('location'));
	$disability = trim($app->request->post('disability'));
	$gender = trim($app->request->post('gender'));
	$stream = trim($app->request->post('stream'));
	$constituency = trim($app->request->post('constituency'));
 
    $db = new DbHandler();
	// insert a new chat
    $response = $db->editStudent($id, $full_names, $reg_no, $sch_id, $admin_date, $student_profile, $guardian_name, $guardian_phone, $guardian_address, $dob, $index_no, $nationality, $religion, $previous_school, $house, $club, $guardian_id_card, $guardian_relation, $guardian_occupation, $email, $town, $current_class, $village, $county, $location, $disability, $gender, $stream, $constituency, $user_id);
 
    // echo json response
    echoResponse(200, $response);
	
});

//upload item pics
$app->post('/uploadPics', function() use ($app) {
 
    $success = 1;
	// reading post params
    $category = $app->request->post('category');
	$category_id = $app->request->post('category_id');
	$title = $app->request->post('item_title');
	$image_crop_ratio = $app->request->post('image_crop_ratio');
	$image_width = $app->request->post('image_width');
	$image_height = $app->request->post('image_height');
	
	$fileData = "";	
	
	//check if file exists before proceeding
	if(file_exists($_FILES['multiple-images']['tmp_name'][0])) 
	{
		$tmpName = array();
		
		foreach($_FILES['multiple-images']['tmp_name'] as $index => $tmpName)
		{
			if (!empty($_FILES['multiple-images']['error'][$index]))
			{
				// some error occured with the file in index $index
				// yield an error here
				$response['error'] = true;
				$response['message'] = "An error occured with file - " . $_FILES['multiple-images']['name'] . ". Please try again.";
				return false; // return false also immediately perhaps??
			}

			// extract the temporary location
			$fileData[$index]['tmp'] = $_FILES['multiple-images']['tmp_name'][$index];
			$fileData[$index]['name'] = $_FILES['multiple-images']['name'][$index];

		}
		
	} 
	 
    if ($success) {
		$db = new DbHandler();
		// insert a new chat
		$response = $db->uploadPics($category, $category_id, $title, $image_crop_ratio, $image_width, $image_height, $fileData);
	}
    // echo json response
    echoResponse(200, $response);
});



//get item images
$app->post('/getItemImagesNew', function() use ($app) {
 
    // reading post params
	$item_id = $app->request->post('item_id');
	$item_cat = $app->request->post('item_cat');
 
    $db = new DbHandler();
    
    $result = $db->getItemImagesNew($item_cat, $item_id);
 
    // echo json response
    echoResponse(200, $result);
});

//get counties
$app->post('/getCounties', function() use ($app) {
  
    $db = new DbHandler();
    $result = $db->getCounties();
    // echo json response
    echoResponse(200, $result);
});

//get status
$app->post('/getStatus', function() use ($app) {
  
    $db = new DbHandler();
    $result = $db->getStatus();
    // echo json response
    echoResponse(200, $result);
});

// Delete items
$app->post('/deleteImage', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('image_id'));
 
    // reading post params
	$image_id = $app->request->post('image_id');
 
    //resend confirmation email
	$db = new DbHandler();
	$response = $db->deleteImage($image_id);

    // echo json response
    echoResponse(200, $response);
});

// Delete items
$app->post('/deleteItem', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('field_name', 'field_value', 'table_name'));
 
    // reading post params
	$field_name = $app->request->post('field_name');
	$field_value = $app->request->post('field_value');
	$table_name = $app->request->post('table_name');
 
    //resend confirmation email
	$db = new DbHandler();
	$response = $db->deleteItem($field_name, $field_value, $table_name);

    // echo json response
    echoResponse(200, $response);
});

// Delete Fee Record
$app->post('/deleteFeeRecord', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('id'));
 
    // reading post params
	$id = $app->request->post('id');
 
    //resend confirmation email
	$db = new DbHandler();
	$response = $db->deleteFeeRecord($id);

    // echo json response
    echoResponse(200, $response);
});

// Delete Result Record
$app->post('/deleteResultRecord', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('id', 'sch_id'));
 
    // reading post params
	$id = $app->request->post('id');
	$sch_id = $app->request->post('sch_id');
 
    //resend confirmation email
	$db = new DbHandler();
	$response = $db->deleteResultRecord($id, $sch_id);

    // echo json response
    echoResponse(200, $response);
});

// Delete user group
$app->post('/deleteGroup', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('field_name', 'field_value', 'table_name'));
 
    // reading post params
	$field_name = $app->request->post('field_name');
	$field_value = $app->request->post('field_value');
	$table_name = $app->request->post('table_name');
 
    //resend confirmation email
	$db = new DbHandler();
	$response = $db->deleteGroup($field_name, $field_value, $table_name);

    // echo json response
    echoResponse(200, $response);
});

// send single company/ school sms
$app->post('/sendSingleSchoolSMS', function() use ($app) {
    
	// check for required params
    verifyRequiredParams(array('sch_id', 'phone_number', 'message'));
 
    // read post params
    $phone_number = $app->request->post('phone_number');
	$message = $app->request->post('message');
	$sch_id = $app->request->post('sch_id');
	$user_id = $app->request->post('user_id');
	$admin = $app->request->post('admin');
 
    $db = new DbHandler();
    $response = $db->sendSingleSchoolSMS($sch_id, $phone_number, $message, $user_id=NULL, $admin=NULL);
 
    // echo json response
    echoResponse(200, $response);
	
});

// send general sms
$app->post('/sendsms', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('phone_number', 'message'));
 
    // read post params
    $phone_number = $app->request->post('phone_number');
	$message = $app->request->post('message');
 
    $db = new DbHandler();
    $response = $db->sendSMS($phone_number, $message);
 
    // echo json response
    echoResponse(200, $response);
});

// send general sms
$app->post('/user/resendregsms', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('phone_number'));
 
    // read post params
    $phone_number = $app->request->post('phone_number');
 
    $db = new DbHandler();
    $response = $db->resendRegSMS($phone_number);
 
    // echo json response
    echoResponse(200, $response);
});

// send recommend sms
$app->post('/user/sendrecommendsms', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('sender_phone_number', 'sender_id', 'recipient_phone_number'));
 
    // read post params
    $sender_phone_number = $app->request->post('sender_phone_number');
	$sender_id = $app->request->post('sender_id');
	$recipient_phone_number = $app->request->post('recipient_phone_number');
 
    $db = new DbHandler();
    $response = $db->sendRecommendSMS($sender_phone_number, $sender_id, $recipient_phone_number);
 
    // echo json response
    echoResponse(200, $response);
});

// verify sms
$app->post('/user/verifysms', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('phone_number', 'sms_code'));
 
    // read post params
    $phone_number = $app->request->post('phone_number');
    $sms_code = $app->request->post('sms_code');
 
    $db = new DbHandler();
    $response = $db->verifySMSCode($phone_number, $sms_code);
 
    // echo json response
    echoResponse(200, $response);
});

// subscribe User
$app->post('/user/subscribe', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('phone_number', 'school_id', 'reg_no'));
 
    // reading post params
    $school_id = $app->request->post('school_id');
	$reg_no = $app->request->post('reg_no');
	$phone_number = $app->request->post('phone_number');
 
    if ($phone_number=="" || $school_id=="" || $reg_no=="") {
		$response['error'] = "true";
		$response['noty_msg'] = "true";
		$response['message'] = "Please select all required information";
	} else {
		$db = new DbHandler();
		$response = $db->createSubscription($phone_number, $school_id, $reg_no);
	}
 
    // echo json response
    echoResponse(200, $response);
});
 
 
/* * *
 * Updating user gcm id (Google Cloud Messaging)
 *  we use this url to update user's gcm registration id
 */
$app->post('/user/:id', function($user_id) use ($app) {
    global $app;
 
    verifyRequiredParams(array('token'));
 
    $token = $app->request->post('token');
 
    $db = new DbHandler();
    $response = $db->updateFcmToken($user_id, $token);
 
    echoResponse(200, $response);
});

//get users other than this user
$app->post('/fetchStudentsInSchool', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('sch_id'));
 
    // reading post params
    $sch_id = $app->request->post('sch_id');
 
    $db = new DbHandler();
    
	// fetching all user tickets
	$result['error'] = false;
    $result['students'] = $db->getStudentsInSchool($sch_id);
 
    // echo json response
    echoResponse(200, $result);
});

//get users other than this user
$app->post('/fetchOtherChatUsers', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('user_id', 'user_type_id', 'phone_number'));
 
    // reading post params
    $user_id = $app->request->post('user_id');
	$user_type_id = $app->request->post('user_type_id');
	$phone_number = $app->request->post('phone_number');
	$student_id = $app->request->post('student_id');
 
    $db = new DbHandler();
    
	// fetching all user tickets
	$result['error'] = false;
    $result['chatUsers'] = $db->getOtherChatUsers($user_id, $user_type_id, $phone_number, $student_id);
 
    // echo json response
    echoResponse(200, $result);
});

//create new chat
$app->post('/createNewChat', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('creator_id', 'recipient_id', 'student_id'));
 
    // reading post params
    $creator_id = $app->request->post('creator_id');
	$recipient_id = $app->request->post('recipient_id');
	$student_id = $app->request->post('student_id');
 
    $db = new DbHandler();
    
	// insert a new chat
	$result['error'] = false;
    $result['chat'] = $db->createNewChat($creator_id, $recipient_id, $student_id);
 
    // echo json response
    echoResponse(200, $result);
});

//get student details
$app->post('/fetchStudentData', function() use ($app) {
    // check for required params
    //verifyRequiredParams(array('reg_no', 'school_id', 'phone_number'));
 
    // reading post params
    $reg_no = $app->request->post('reg_no');
	$school_id = $app->request->post('school_id');
	$phone_number = $app->request->post('phone_number');
	$dob = $app->request->post('dob');
	$student_id = $app->request->post('student_id');
 
    $db = new DbHandler();
    
	// fetch data
    $result['student'] = $db->getStudentData($reg_no, $school_id, $phone_number, $dob, $student_id);
 
    // echo json response
    echoResponse(200, $result);
});

//get student details
$app->post('/fetchSchoolName', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('school_id'));
 
    // reading post params
	$school_id = $app->request->post('school_id');
 
    $db = new DbHandler();
    
	// fetch data
    $result["sch_name"] = $db->getSchoolName($school_id);
	
    // echo json response
    echoResponse(200, $result);
});

//get fee grid listing
$app->post('/getFeesGridListing', function() use ($app) {
 
    // reading post params
	$sch_id = $app->request->post('sch_id');
	$current_class = $app->request->post('current_class');
	$stream = $app->request->post('stream');
	$reg_no = $app->request->post('reg_no');
	$year = $app->request->post('year');
	$id = $app->request->post('id');
	$student_id = $app->request->post('student_id');
	$page = $app->request->post('current');
	$user_id = $app->request->post('user_id');
	$search_text = $app->request->post('searchPhrase');
	$limit = $app->request->post('rowCount');
	$sort = $app->request->post('sort');
	$admin = $app->request->post('admin');
	$no_pagination = $app->request->post('no_pagination');
	$start_date = $app->request->post('start_date');
	$end_date = $app->request->post('end_date');
	$status = $app->request->post('status');
 
    $db = new DbHandler();
    
	// fetch data
    $result = $db->getFeesGridListing($sch_id, $current_class, $stream, $reg_no, $year, $id, $page, $user_id, $search_text, $limit, $sort, $admin, $no_pagination, $start_date, $end_date, $status, $student_id);
 
    // echo json response
    echoResponse(200, $result);
	
});


//get fee summaries
$app->post('/getFeesSummary', function() use ($app) {
 
    // reading post params
	$sch_id = $app->request->post('sch_id');
	$current_class = $app->request->post('current_class');
	$stream = $app->request->post('stream');
	$reg_no = $app->request->post('reg_no');
	$year = $app->request->post('year');
	$student_id = $app->request->post('student_id');
	$payment_method = $app->request->post('payment_method');
	$start_date = $app->request->post('start_date');
	$end_date = $app->request->post('end_date');
	$user_id = $app->request->post('user_id');
	$admin = $app->request->post('admin');
 
    $db = new DbHandler();
    
	// fetch data
    $result = $db->getFeesSummary($sch_id, $current_class, $stream, $reg_no, $year, $student_id, $payment_method, $start_date, $end_date, $user_id, $admin);
 
    // echo json response
    echoResponse(200, $result);
	
});


//get month fee summaries
$app->post('/getMonthFeesSummary', function() use ($app) {
 
    // reading post params
	$sch_id = $app->request->post('sch_id');
	$payment_date = $app->request->post('payment_date');
	
	if (!$payment_date) {
		// Current timestamp is assumed, so these find first and last day of THIS month
		$start_date = date('01/m/Y'); // hard-coded '01' for first day of the month
		$end_date  = date('t/m/Y'); //t is end month
	} else {
		list($month, $year) = explode("-", $payment_date);
		$start_date = date("01/$month/$year"); // hard-coded '01' for first day
		$end_date  = date("t/$month/$year"); 	
	}
	//echo "$start_date - $end_date";exit;
 
    $db = new DbHandler();
    
	// fetch monthly  data
    $result["cash"] = $db->getFeesSummary($sch_id, "", "", "", "", "", "cash", $start_date, $end_date);
	$result["mpesa"] = $db->getFeesSummary($sch_id, "", "", "", "", "", "mpesa", $start_date, $end_date);
	$result["cheque"] = $db->getFeesSummary($sch_id, "", "", "", "", "", "cheque", $start_date, $end_date);
 
    // echo json response
    echoResponse(200, $result);
	
});


//get results grid listing
$app->post('/getResultsGridListing', function() use ($app) {
 
    // reading post params
	$sch_id = $app->request->post('sch_id');
	$current_class = $app->request->post('current_class');
	$stream = $app->request->post('stream');
	$reg_no = $app->request->post('reg_no');
	$year = $app->request->post('year');
	$term = $app->request->post('term');
	$id = $app->request->post('id');
	$page = $app->request->post('current');
	$user_id = $app->request->post('user_id');
	$search_text = $app->request->post('searchPhrase');
	$limit = $app->request->post('rowCount');
	$sort = $app->request->post('sort');
	$admin = $app->request->post('admin');
	$no_pagination = $app->request->post('no_pagination');
	$start_date = $app->request->post('start_date');
	$end_date = $app->request->post('end_date');
 
    $db = new DbHandler();
    
	// fetch data
    $result = $db->getResultsGridListing($sch_id, $current_class, $stream, $reg_no, $year, $term, $id, $page, $user_id, $search_text, $limit, $sort, $admin, $no_pagination, $start_date, $end_date);
 
    // echo json response
    echoResponse(200, $result);
	
});

//get subject listing
$app->post('/getSubjectsListing', function() use ($app) {
	
    // check for required params
    //verifyRequiredParams(array('sch_id', 'search_term'));
 
    // reading post params
	$search_term = $app->request->post('search_term');
	$page = $app->request->post('page');
	$sch_id = $app->request->post('sch_id');
	$level_id = $app->request->post('level_id');
	$lperpage = $app->request->post('lperpage');
	$page = $app->request->post('page');
	$user_id = $app->request->post('user_id');
	$admin = $app->request->post('admin');
	$paginate = $app->request->post('paginate');
	$sort = $app->request->post('sort');
 
    $db = new DbHandler();
    
	// fetch data
    $result = $db->getSubjectsListing($sch_id, $level_id, $search_term, $lperpage, $page, $sort, $user_id, $admin, $paginate);
 
    // echo json response
    echoResponse(200, $result);
	
});

//get student listing
$app->post('/fetchStudentListing', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('sch_id', 'search_term'));
 
    // reading post params
	$search_term = $app->request->post('search_term');
	$page = $app->request->post('page');
	$sch_id = $app->request->post('sch_id');
	$grid_list = $app->request->post('grid_list');
	//$orderstyle = $app->request->post('orderstyle');
 
    $db = new DbHandler();
    
	// fetch data
	$result['error'] = false;
    $result['students'] = $db->getStudentListing($sch_id, $page, $search_term, $grid_list);
 
    // echo json response
    echoResponse(200, $result);
});

//get student listing
$app->post('/fetchStudentGridListing', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('sch_id'));
 
    // reading post params
	$page = $app->request->post('current');
	$sch_id = $app->request->post('sch_id');
	$id = $app->request->post('id');
	$student_ids = $app->request->post('student_ids');
	$current_class = $app->request->post('current_class');
	$stream = $app->request->post('stream');
	$lperpage = $app->request->post('rowCount');
	$searchPhrase = $app->request->post('searchPhrase');
	$sort = $app->request->post('sort');
	$user_id = $app->request->post('user_id');
	$admin = $app->request->post('admin');
	$no_pagination = $app->request->post('no_pagination');
 
    $db = new DbHandler();
    
	// fetch data
	$result['error'] = false;
    $result = $db->getStudentGridListing($sch_id, $page, $searchPhrase, $lperpage, $sort, $id, $admin, $user_id, $no_pagination, $student_ids, $current_class, $stream);
 
    // echo json response
    echoResponse(200, $result);
});


//get sent sms grid listing
$app->post('/fetchSentSmsGridListing', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('sch_id'));
 
    // reading post params
	$page = $app->request->post('current');
	$sch_id = $app->request->post('sch_id');
	$id = $app->request->post('id');
	$sms_type_id = $app->request->post('sms_type_id');
	$phone_number = $app->request->post('phone_number');
	$start_date = $app->request->post('start_date');
	$end_date = $app->request->post('end_date');
	$lperpage = $app->request->post('rowCount');
	$searchPhrase = $app->request->post('searchPhrase');
	$sort = $app->request->post('sort');
	$user_id = $app->request->post('user_id');
	$admin = $app->request->post('admin');
	$no_pagination = $app->request->post('no_pagination');
 
    $db = new DbHandler();
    
	// fetch data
    $result = $db->fetchSentSmsGridListing($sch_id, $phone_number, $sms_type_id, $start_date, $end_date, $page, $searchPhrase, $lperpage, $sort, $id, $admin, $user_id, $no_pagination);
 
    // echo json response
    echoResponse(200, $result);
});



//show classes listing
$app->post('/getClassGridListing', function() use ($app) {
    // check for required params
    //verifyRequiredParams(array('sch_id'));
 
    // reading post params
	$page = $app->request->post('current');
	$sch_id = $app->request->post('sch_id');
	$id = $app->request->post('id');
	$lperpage = $app->request->post('rowCount');
	$searchPhrase = $app->request->post('searchPhrase');
	$sort = $app->request->post('sort');
	$user_id = $app->request->post('user_id');
	$no_pagination = $app->request->post('no_pagination');
 
    $db = new DbHandler();
    
	// fetch data
	$result['error'] = false;
    $result = $db->getClassGridListing($sch_id, $page, $searchPhrase, $lperpage, $sort, $id, $user_id, $no_pagination);
 
    // echo json response
    echoResponse(200, $result);
});

//show stream listing
$app->post('/getStreamGridListing', function() use ($app) {
    // check for required params
    //verifyRequiredParams(array('sch_id'));
 
    // reading post params
	$page = $app->request->post('current');
	$sch_id = $app->request->post('sch_id');
	$id = $app->request->post('id');
	$lperpage = $app->request->post('rowCount');
	$searchPhrase = $app->request->post('searchPhrase');
	$sort = $app->request->post('sort');
	$user_id = $app->request->post('user_id');
	$no_pagination = $app->request->post('no_pagination');
 
    $db = new DbHandler();
    
	// fetch data
	$result['error'] = false;
    $result = $db->getStreamGridListing($sch_id, $page, $searchPhrase, $lperpage, $sort, $id, $user_id, $no_pagination);
 
    // echo json response
    echoResponse(200, $result);
});

//get student results listing
$app->post('/fetchStudentResults', function() use ($app) {
    
	// reading post params
	$id = $app->request->post('id');
	$sch_id = $app->request->post('sch_id');
	$reg_no = $app->request->post('reg_no');
	$student_id = $app->request->post('student_id');
	$year = $app->request->post('year');
	$term = $app->request->post('term');
	$page = $app->request->post('current');
	$lperpage = $app->request->post('rowCount');
	$searchPhrase = $app->request->post('searchPhrase');
	$sort = $app->request->post('sort');
	$user_id = $app->request->post('user_id');
	//$single_student_result = $app->request->post('single_student_result');
 
    $db = new DbHandler();
    
	// fetch data
    $result = $db->fetchStudentResults($sch_id, $reg_no, $year, $term, $page, $searchPhrase, $lperpage, $sort, $student_id, $user_id, $single_student_result, $id);
 
    // echo json response
    echoResponse(200, $result);
	
});

//get single subject result
$app->post('/fetchSchoolIdFromStudentId', function() use ($app) {
    
	// reading post params
	$student_id = $app->request->post('student_id');
 
    $db = new DbHandler();
    
	// fetch data
	$result['error'] = false;
    $result = $db->fetchSchoolIdFromStudentId($student_id);
 
    // echo json response
    echoResponse(200, $result);
	
});

//get single subject result
$app->post('/fetchSingleResult', function() use ($app) {
    
	// reading post params
	$result_item_id = $app->request->post('id');
 
    $db = new DbHandler();
    
	// fetch data
	$result['error'] = false;
    $result = $db->getSingleResult($result_item_id);
 
    // echo json response
    echoResponse(200, $result);
	
});

//get single subject result
$app->post('/fetchSingleFee', function() use ($app) {
    
	// reading post params
	$fee_payment_id = $app->request->post('id');
 
    $db = new DbHandler();
    
	// fetch data
	$result['error'] = false;
    $result = $db->getSingleFee($fee_payment_id);
 
    // echo json response
    echoResponse(200, $result);
	
});

//edit single student fee item
$app->post('/editSingleFee', function() use ($app) {
    
	// check for required params
    verifyRequiredParams(array('fee_payment_id', 'fee_amount'));   
	
	// read post params
	$fee_payment_id = $app->request->post('fee_payment_id');
	$amount = $app->request->post('fee_amount');
	$payment_mode = $app->request->post('fee_payment_mode');
	$paid_by = $app->request->post('fee_paid_by');
	$paid_at = $app->request->post('fee_paid_at');
	$user_id = $app->request->post('user_id');
 
    $db = new DbHandler();
    
	// fetch data
	$result['error'] = false;
    $result = $db->editSingleFee($fee_payment_id, $amount, $payment_mode, $paid_by, $paid_at, $user_id);
 
    // echo json response
    echoResponse(200, $result);
	
});

//edit single school
$app->post('/editSchool', function() use ($app) {
    
	// check for required params
    verifyRequiredParams(array('id', 'sch_name'));   
	
	// reading post params
	$id = $app->request->post('id');
	$name = $app->request->post('sch_name');
	$sch_first_name = $app->request->post('sch_first_name');
	$sch_level = $app->request->post('sch_level');
	$sch_category = $app->request->post('sch_category');
	$province = $app->request->post('province');
	$sch_county = $app->request->post('sch_county');
	$status = $app->request->post('status');
	$sch_profile = $app->request->post('sch_profile');
	$sch_paybill_no = $app->request->post('sch_paybill_no');
	$motto = $app->request->post('motto');
	$phone1 = $app->request->post('phone1');
	$phone2 = $app->request->post('phone2');
	$sms_welcome1 = $app->request->post('sms_welcome1');
	$sms_welcome2 = $app->request->post('sms_welcome2');
	$address = $app->request->post('address');
	$user_id = $app->request->post('user_id');	
 
    $db = new DbHandler();
    
	// fetch data
    $result = $db->editSchool($id, $name, $sch_first_name, $sch_level, $sch_category, $province, $sch_county, $status, $motto, $phone1, $phone2, $sms_welcome1, $sms_welcome2, $address, $sch_profile, $sch_paybill_no, $user_id);
 
    // echo json response
    echoResponse(200, $result);
	
});

//edit single student result
$app->post('/editSingleResult', function() use ($app) {
    
	// check for required params
    verifyRequiredParams(array('result_item_id', 'score', 'sch_id'));   
	
	// reading post params
	$result_item_id = $app->request->post('result_item_id');
	$score = $app->request->post('score');
	$sch_id = $app->request->post('sch_id');
 
    $db = new DbHandler();
    
	// fetch data
	$result['error'] = false;
    $result = $db->editSingleResult($result_item_id, $score, $sch_id);
 
    // echo json response
    echoResponse(200, $result);
	
});

//get subject listing
$app->post('/fetchSubjectGridListing', function() use ($app) {
    // reading post params
	$page = $app->request->post('current');
	$id = $app->request->post('id');
	$level_id = $app->request->post('level_id');
	$lperpage = $app->request->post('rowCount');
	$searchPhrase = $app->request->post('searchPhrase');
	$sort = $app->request->post('sort');
 
    $db = new DbHandler();
    
	// fetch data
	$result['error'] = false;
    $result = $db->getSubjectGridListing($level_id, $page, $searchPhrase, $lperpage, $sort, $id);
 
    // echo json response
    echoResponse(200, $result);
});

//get total score grade listing
$app->post('/fetchTotalScoreGradeGridListing', function() use ($app) {
    // reading post params
	$page = $app->request->post('current');
	$id = $app->request->post('id');
	$level_id = $app->request->post('level_id');
	$lperpage = $app->request->post('rowCount');
	$searchPhrase = $app->request->post('searchPhrase');
	$sort = $app->request->post('sort');
 
    $db = new DbHandler();
    
	// fetch data
	$result['error'] = false;
    $result = $db->fetchTotalScoreGradeGridListing($level_id, $page, $searchPhrase, $lperpage, $sort, $id);
 
    // echo json response
    echoResponse(200, $result);
});


//get subject score grade listing
$app->post('/fetchScoreGradeGridListing', function() use ($app) {
    // reading post params
	$page = $app->request->post('current');
	$id = $app->request->post('id');
	$level_id = $app->request->post('level_id');
	$lperpage = $app->request->post('rowCount');
	$searchPhrase = $app->request->post('searchPhrase');
	$sort = $app->request->post('sort');
 
    $db = new DbHandler();
    
	// fetch data
	$result['error'] = false;
    $result = $db->fetchScoreGradeGridListing($level_id, $page, $searchPhrase, $lperpage, $sort, $id);
 
    // echo json response
    echoResponse(200, $result);
});

//get user listing
$app->post('/fetchUserGridListing', function() use ($app) {
    // check for required params
    //verifyRequiredParams(array('sch_id'));
 
    // reading post params	
	$page = $app->request->post('current');
	$sch_id = $app->request->post('sch_id');
	$lperpage = $app->request->post('rowCount');
	$searchPhrase = $app->request->post('searchPhrase');
	$sort = $app->request->post('sort');
 
    $db = new DbHandler();
    
	// fetch data
	$result['error'] = false;
    $result = $db->getUserGridListing($page, $searchPhrase, $lperpage, $sort);
 
    // echo json response
    echoResponse(200, $result);
});

//get school listing
$app->post('/fetchSchoolActivities', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('sch_id'));
 	
	$sch_id = $app->request->post('sch_id');
	$page = $app->request->post('current');
	$lperpage = $app->request->post('rowCount');
	$searchPhrase = $app->request->post('searchPhrase');
	$sort = $app->request->post('sort');
	
    $db = new DbHandler();
    
	// fetch data
	//$result['error'] = false;
    $result = $db->getSchoolActivities($sch_id, $page, $searchPhrase, $lperpage, $sort);
 
    // echo json response
    echoResponse(200, $result);
});

//get school listing
$app->post('/fetchSchoolGridListing', function() use ($app) {
    // check for required params
    //verifyRequiredParams(array('page', 'sch_id'));
 	
	$id = $app->request->post('id');
	$page = $app->request->post('current');
	$admin = $app->request->post('admin');
	$user_id = $app->request->post('user_id');
	$lperpage = $app->request->post('rowCount');
	$searchPhrase = $app->request->post('searchPhrase');
	$sort = $app->request->post('sort');
	
    $db = new DbHandler();
    
	// fetch data
	//$result['error'] = false;
    $result = $db->getSchoolGridListing($page, $user_id, $searchPhrase, $lperpage, $sort, $id, $admin);
 
    // echo json response
    echoResponse(200, $result);
});



//get school listing
$app->post('/fetchSchoolListing', function() use ($app) {
	 
    // reading post params
	//$search_term = $app->request->post('search_term');
	$page = $app->request->post('page');
	$search_text = $app->request->post('search_term');
	$full_list = $app->request->post('full_list');
	$province = $app->request->post('province');
	//$orderstyle = $app->request->post('orderstyle');
 
    $db = new DbHandler();
    
	// fetch data
	$result['error'] = false;
    $result['schools'] = $db->getSchoolListing($page, $search_text, $province, $full_list);
 
    // echo json response
    echoResponse(200, $result);
});

//get fee payments listing
$app->post('/fetchStudentFeePayments', function() use ($app) {
 
    // reading post params
	$student_id = $app->request->post('student_id');
	$id = $app->request->post('id');
	$sfp_id = $app->request->post('sfp_id');
	$phone_number = $app->request->post('phone_number');
	$year = $app->request->post('year');
	$page = $app->request->post('page');
	$limit = $app->request->post('limit');
	$user_id = $app->request->post('user_id');
	$sch_id = $app->request->post('sch_id');
	$reg_no = $app->request->post('reg_no');
	$admin = $app->request->post('admin');
 
    $db = new DbHandler();
    
	// fetch data
    $result = $db->getStudentFeePayments($page, $student_id, $phone_number, $year, $user_id, $sch_id, $reg_no, $limit, $admin, $id, $sfp_id);
 
    // echo json response
    echoResponse(200, $result);
	
});

//get fee payments listing
$app->post('/fetchStudentFeePaymentsHistory', function() use ($app) {
    // check for required params
    //verifyRequiredParams(array('student_id'));
 
    // reading post params
	$id = $app->request->post('id');
	$page = $app->request->post('page');
	$limit = $app->request->post('limit');
	$sort = $app->request->post('sort');
	$search_text = $app->request->post('searchPhrase');
	$user_id = $app->request->post('user_id');
	$admin = $app->request->post('admin');
	$student_id = $app->request->post('student_id');
	$fee_year = $app->request->post('fee_year');
 
    $db = new DbHandler();
    
	// fetch data
    $result = $db->getStudentFeePaymentsHistory($page, $user_id, $limit, $admin, $id, $student_id, $fee_year, $sort, $search_text);
 
    // echo json response
    echoResponse(200, $result);
	
});

//get fee payments listing
$app->post('/fetchStudentResultsHistory', function() use ($app) {
    // check for required params
    //verifyRequiredParams(array('student_id'));
 
    // reading post params
	$id = $app->request->post('id');
	$page = $app->request->post('current');
	$limit = $app->request->post('limit');
	$sort = $app->request->post('sort');
	$search_text = $app->request->post('searchPhrase');
	$user_id = $app->request->post('user_id');
	$admin = $app->request->post('admin');
	$student_id = $app->request->post('student_id');
	$year = $app->request->post('year');
	$term = $app->request->post('term');
 
    $db = new DbHandler();
    
	// fetch data
    $result = $db->fetchStudentResultsHistory($page, $user_id, $limit, $admin, $id, $student_id, $year, $sort, $search_text, $term);
 
    // echo json response
    echoResponse(200, $result);
	
});

//get subject history
$app->post('/fetchSubjectHistory', function() use ($app) {
 
    // reading post params
	$id = $app->request->post('id');
	$page = $app->request->post('current');
	$limit = $app->request->post('limit');
	$sort = $app->request->post('sort');
	$search_text = $app->request->post('searchPhrase');
	$user_id = $app->request->post('user_id');
	$admin = $app->request->post('admin');
 
    $db = new DbHandler();
    
	// fetch data
    $result = $db->fetchSubjectHistory($page, $user_id, $limit, $admin, $id, $sort, $search_text);
 
    // echo json response
    echoResponse(200, $result);
	
});

//get student history
$app->post('/fetchStudentHistory', function() use ($app) {
 
    // reading post params
	$id = $app->request->post('id');
	$page = $app->request->post('page');
	$limit = $app->request->post('limit');
	$user_id = $app->request->post('user_id');
	$admin = $app->request->post('admin');
 
    $db = new DbHandler();
    
	// fetch data
    $result = $db->getStudentHistory($page, $user_id, $limit, $admin, $id);
 
    // echo json response
    echoResponse(200, $result);
	
});

//get school listing
$app->post('/fetchSchoolSubListing', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('page', 'phone_number'));
 
    // reading post params
	$search_term = $app->request->post('search_term');
	$page = $app->request->post('page');
	$school_name_sort = $app->request->post('school_name_sort');
	$province_sort = $app->request->post('province_sort');
	$cat_sort = $app->request->post('cat_sort');
	$phone_number = $app->request->post('phone_number');
	//$orderstyle = $app->request->post('orderstyle');
 
    $db = new DbHandler();
    
	// fetch data
	$result['error'] = false;
    $result['schools'] = $db->getSchoolSubListing($page, $search_term, $school_name_sort, $province_sort, $cat_sort, $phone_number);
 
    // echo json response
    echoResponse(200, $result);
});

//get school details for specific user (phone_number)
$app->post('/fetchSchoolData', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('school_id', 'phone_number'));
 
    // reading post params
	$school_id = $app->request->post('school_id');
	$phone_number = $app->request->post('phone_number');
 
    $db = new DbHandler();
    
	// fetch data
	$result['error'] = false;
	//check whether user is already subscribed to a school
	if (!$db->isUserSubExists($phone_number)) {
		//subscribe user to this school
		$result["user_subscribed"] = false;
	} else {
		$result["user_subscribed"] = true;	
	}
    $result['school'] = $db->getSchoolData($phone_number, $school_id);
 
    // echo json response
    echoResponse(200, $result);
});

//get school terms
$app->post('/fetchTermData', function() use ($app) {
 
    $db = new DbHandler();
    
	// fetch data
	$result['error'] = false;
    $result['terms'] = $db->getTermData();
 
    // echo json response
    echoResponse(200, $result);
});

//get years
$app->post('/fetchYearData', function() use ($app) {
 
    $db = new DbHandler();
    
	// fetch data
	$result['error'] = false;
    $result['years'] = $db->getYearData();
 
    // echo json response
    echoResponse(200, $result);
});

//get subs
$app->post('/fetchSubStudentData', function() use ($app) {
 
    // check for required params
    verifyRequiredParams(array('phone_number'));
	// reading post params
	$phone_number = $app->request->post('phone_number');
	
	$db = new DbHandler();
    
	// fetch data
	$result['error'] = false;
	//check whether user is already subscribed to a school
	if (!$db->isUserSubExists($phone_number)) {
		//subscribe user to this school
		$result["user_subscribed"] = false;
	} else {
		$result["user_subscribed"] = true;	
	}
    $result['students'] = $db->getSubStudentsData($phone_number);
 
    // echo json response
    echoResponse(200, $result);
});

// Get total unread messages for user
$app->post('/getUnreadMessagesTotal', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('user_id'));
 
    // reading post params
	$user_id = $app->request->post('user_id');
 
    //resend confirmation email
	$db = new DbHandler();
	$response = $db->getUnreadMessagesTotal($user_id);

    // echo json response
    echoResponse(200, $response);
});

//get student results
$app->post('/fetchStudentResults2', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('school_id', 'reg_no', 'year', 'term'));
 
    // reading post params
	$school_id = $app->request->post('school_id');
	$reg_no = $app->request->post('reg_no');
	$year = $app->request->post('year');
	$term = $app->request->post('term');
 
    $db = new DbHandler();
    
	// fetch data
    $response = $db->getStudentResults($school_id, $reg_no, $term, $year);
 
    // echo json response
    echoResponse(200, $response);
}); 

//get student FEES
$app->post('/fetchStudentFees', function() use ($app) {
 
    // reading post params
	$show_student_fees = $app->request->post('show_student_fees');
	$id = $app->request->post('id');
	$sch_id = $app->request->post('sch_id');
	$reg_no = $app->request->post('reg_no');
	$student_id = $app->request->post('student_id');
	$year = $app->request->post('year');
	$page = $app->request->post('current');
	$lperpage = $app->request->post('rowCount');
	$searchPhrase = $app->request->post('searchPhrase');
	$sort = $app->request->post('sort');
	$user_id = $app->request->post('user_id');
	$single_student_result = $app->request->post('single_student_result');
 
    $db = new DbHandler();
    
	// fetch data
	//$result['error'] = false;
    $result= $db->getStudentFees($sch_id, $reg_no, $year, $page, $search_text, $lperpage, $sort, $student_id, $user_id, $single_student_result, $id);
 
    // echo json response
    echoResponse(200, $result);
	
});

//get student FEES balance
$app->post('/fetchFeeBalance', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('student_id'));
 
    // reading post params
	$student_id = $app->request->post('student_id');
	$year = $app->request->post('year');
	if (!$year) { $year = date("Y"); }
 
    $db = new DbHandler();
    
	// fetch data
    $result["fees_balance"]= $db->getFeeBalance($student_id, $year);
 
    // echo json response
    echoResponse(200, $result);
});

//get subscriptions for this user
$app->post('/fetchSubscriptions', function() use ($app) {
    // check for required params
    //verifyRequiredParams(array('phone_number'));
 
    // reading post params
    $phone_number = $app->request->post('phone_number');
	$page = $app->request->post('page');
	$limit = $app->request->post('limit');
	$sch_id = $app->request->post('sch_id');
 
    $db = new DbHandler();
    
	// fetching all user tickets
	$result['error'] = false;
    $result = $db->getSubscriptions($phone_number, $page, $limit, $sch_id);
 
    // echo json response
    echoResponse(200, $result);
});

//delete single subscription
$app->post('/deleteSubscription', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('sub_id', 'phone_number'));
 
    // reading post params
    $sub_id = $app->request->post('sub_id');
	$phone_number = $app->request->post('phone_number');
 
    $db = new DbHandler();
    
	//delete sub and return
	$result['error'] = false;
    $result['subs'] = $db->deleteSubscription($sub_id, $phone_number);
 
    // echo json response
    echoResponse(200, $result);
});

//save mpesa fee payment
$app->post('/saveMpesaFeePayment', function() use ($app) {
	
    // check for required params
    verifyRequiredParams(array('user_id', 'phone_number', 'amount', 'student_id'));
 
    // reading post params
    $user_id = $app->request->post('user_id');
	$student_id = $app->request->post('student_id');
	$amount = $app->request->post('amount');
	$phone_number = $app->request->post('phone_number');
	$year = $app->request->post('year');
 
    $db = new DbHandler();
    
	//save new transaction
    $result = $db->saveMpesaFeePayment($user_id, $student_id, $amount, $phone_number, $year);
 
    // echo json response
    echoResponse(200, $result);
	
});

//request user to perform mpesa fee payment
$app->post('/getPaybillNumber', function() use ($app) {
	
    // check for required params
    verifyRequiredParams(array('sch_id', 'sch_name'));
 
    // reading post params
    $sch_id = $app->request->post('sch_id');
	$sch_name = $app->request->post('sch_name');
 
    echoResponse(200, $result);
    $db = new DbHandler();
    
	//save new transaction
    $result = $db->getPaybillNumber($sch_id, $sch_name);
 
    // echo json response
	
}); 

//request user to perform mpesa fee payment
$app->post('/requestMpesaFeePayment', function() use ($app) {
	
    // check for required params
    verifyRequiredParams(array('amount', 'phone_number', 'sch_id', 'reg_no', 'student_names', 'paybill_no'));
 
    // reading post params
    $sch_id = $app->request->post('sch_id');
	$sch_name = $app->request->post('sch_name');
	$reg_no = $app->request->post('reg_no');
	$student_names = $app->request->post('student_names');
	$phone_number = $app->request->post('phone_number');
	$amount = $app->request->post('amount');
	$paybill_no = $app->request->post('paybill_no');
 
    $db = new DbHandler();
    
	//save new transaction
    $result = $db->requestMpesaFeePayment($sch_id, $sch_name, $reg_no, $student_names, $phone_number, $amount, $paybill_no);
 
    // echo json response
    echoResponse(200, $result);
	
}); 

//get chats for this user
$app->post('/chats', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('user_id', 'page'));
 
    // reading post params
    $user_id = $app->request->post('user_id');
	$page = $app->request->post('page');
	$limit = $app->request->post('limit');
	$school_ids = $app->request->post('school_ids');
	$student_id = $app->request->post('student_id');
 
    $db = new DbHandler();
    
	// fetching all user tickets
	$result['error'] = false;
    $result['chats'] = $db->getAllUserChats($user_id, $page, $student_id, $limit, $school_ids);
 
    // echo json response
    echoResponse(200, $result);
});

//get all chats for this user
$app->post('/chatsAll', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('user_id'));
 
    // reading post params
    $user_id = $app->request->post('user_id');
 
    $db = new DbHandler();
    
	// fetching all 
	$result['error'] = false;
    $result['chats'] = $db->getAllUserFullChats($user_id);
 
    // echo json response
    echoResponse(200, $result);
});

/**
 * Messaging in a chat room
 * Will send push notification using Topic Messaging
 *  */
$app->post('/chats/:id/message', function($chat_id) {
    global $app;
    $db = new DbHandler();
 
    verifyRequiredParams(array('user_id', 'chat_id', 'message'));
 
    $user_id = $app->request->post('user_id');
	$chat_id = $app->request->post('chat_id');
    $message = $app->request->post('message');
	//$recipient_id = $db->getRecipientId($user_id, $chat_id);
 
    $result = $db->addChatMessage($user_id, $chat_id, $message);
 
    if ($result['error'] == false) {
        require_once __DIR__ . '/../libs/gcm/gcm.php';
        require_once __DIR__ . '/../libs/gcm/push.php';
        $gcm = new GCM();
        $push = new Push();
 
        // get the user using userid
        $user = $db->getUserDetails($user_id);
		$recipient_id = $result['message']['recipient_id'];

		$data = array();
        //$data['error'] = false;
		$data['user'] = $user;
        $data['message'] = $result['message'];

		//generate chat message params
		$topic_id = TOPIC_CHAT_PREFIX . $chat_id;
 
        $push->setTitle(COMPANY_APP_MESSAGING_TITLE);
        $push->setIsBackground(FALSE);
        $push->setFlag(PUSH_FLAG_CHATROOM);
        $push->setData($data);
 
        // sending push message to a topic
        $status = $gcm->sendToTopic($topic_id, $push->getPush());
 
        //$response['topic_id'] = $topic_id;
		//$response['push'] = $push->getPush();
		$response['user'] = $user;
		$response['message'] = $result['message'];
        $response['status'] = $status;
		$response['error'] = false;
    }
 
    echoResponse(200, $response);
	
});
 
 
/**
 * Sending push notification to a single user
 * We use user's gcm registration id to send the message
 * * */
$app->post('/users/:id/message', function($to_user_id) {
    global $app;
    $db = new DbHandler();
 
    verifyRequiredParams(array('message'));
 
    $from_user_id = $app->request->post('user_id');
    $message = $app->request->post('message');
 
    $response = $db->addMessage($from_user_id, $to_user_id, $message);
 
    if ($response['error'] == false) {
        require_once __DIR__ . '/../libs/gcm/gcm.php';
        require_once __DIR__ . '/../libs/gcm/push.php';
        $gcm = new GCM();
        $push = new Push();
 
        $user = $db->getUser($to_user_id);
 
        $data = array();
        $data['user'] = $user;
        $data['message'] = $response['message'];
        $data['image'] = '';
 
        $push->setTitle("Pendo Schools Messaging");

        $push->setIsBackground(FALSE);
        $push->setFlag(PUSH_FLAG_USER);
        $push->setData($data);
 
        // sending push message to single user
        $gcm->send($user['gcm_registration_id'], $push->getPush());
 
        $response['user'] = $user;
        $response['error'] = false;
    }
 
    echoResponse(200, $response);
});
 
 
/**
 * Sending push notification to multiple users
 * We use gcm registration ids to send notification message
 * At max you can send message to 1000 recipients
 * * */
$app->post('/users/message', function() use ($app) {
 
    $response = array();
    verifyRequiredParams(array('user_id', 'to', 'message'));
 
    require_once __DIR__ . '/../libs/gcm/gcm.php';
    require_once __DIR__ . '/../libs/gcm/push.php';
 
    $db = new DbHandler();
 
    $user_id = $app->request->post('user_id');
    $to_user_ids = array_filter(explode(',', $app->request->post('to')));
    $message = $app->request->post('message');
 
    $user = $db->getUser($user_id);
    $users = $db->getUsers($to_user_ids);
 
    $registration_ids = array();
 
    // preparing gcm registration ids array
    foreach ($users as $u) {
        array_push($registration_ids, $u['gcm_registration_id']);
    }
 
    // insert messages in db
    // send push to multiple users
    $gcm = new GCM();
    $push = new Push();
 
    // creating tmp message, skipping database insertion
    $msg = array();
    $msg['message'] = $message;
    $msg['message_id'] = '';
    $msg['chat_room_id'] = '';
    $msg['created_at'] = date('Y-m-d G:i:s');
 
    $data = array();
    $data['user'] = $user;
    $data['message'] = $msg;
    $data['image'] = '';
 
    $push->setTitle("Pendo Schools Messaging");
    $push->setIsBackground(FALSE);
    $push->setFlag(PUSH_FLAG_USER);
    $push->setData($data);
 
    // sending push message to multiple users
    $gcm->sendMultiple($registration_ids, $push->getPush());
 
    $response['error'] = false;
 
    echoResponse(200, $response);
});
 
$app->post('/users/send_to_all', function() use ($app) {
 
    $response = array();
    verifyRequiredParams(array('user_id', 'message'));
 
    require_once __DIR__ . '/../libs/gcm/gcm.php';
    require_once __DIR__ . '/../libs/gcm/push.php';
 
    $db = new DbHandler();
 
    $user_id = $app->request->post('user_id');
    $message = $app->request->post('message');
 
    require_once __DIR__ . '/../libs/gcm/gcm.php';
    require_once __DIR__ . '/../libs/gcm/push.php';
    $gcm = new GCM();
    $push = new Push();
 
    // get the user using userid
    $user = $db->getUser($user_id);
     
    // creating tmp message, skipping database insertion
    $msg = array();
    $msg['message'] = $message;
    $msg['message_id'] = '';
    $msg['chat_room_id'] = '';
    $msg['created_at'] = date('Y-m-d G:i:s');
 
    $data = array();
    $data['user'] = $user;
    $data['message'] = $msg;
 
    $push->setTitle("Pendo Schools Messaging");
    $push->setIsBackground(FALSE);
    $push->setFlag(PUSH_FLAG_USER);
    $push->setData($data);
 
    // sending message to topic `global`
    // On the device every user should subscribe to `global` topic
    $gcm->sendToTopic('global', $push->getPush());
 
    $response['user'] = $user;
    $response['error'] = false;
 
    echoResponse(200, $response);
});

/**
 * Fetching single chat including all the  chat messages
 *  */
$app->post('/chats/:id', function($chat_id) {
    // check for required params
 	global $app;
	verifyRequiredParams(array('user_id'));
    $page = $app->request->post('page');
	$user_id = $app->request->post('user_id');
	$chat_id = $app->request->post('chat_id');
	$recent_message_id = $app->request->post('recent_message_id');
	
    $db = new DbHandler();
    $result  = $db->getChat($chat_id, $user_id, $page, $recent_message_id);	

    // echo json response
    echoResponse(200, $result);
});
 
/**
 * Verifying required params posted or not
 */
function verifyRequiredParams($required_fields) {
    $error = false;
    $error_fields = "";
    $request_params = array();
    $request_params = $_REQUEST;
    // Handling PUT request params
    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        $app = \Slim\Slim::getInstance();
        parse_str($app->request()->getBody(), $request_params);
    }
    foreach ($required_fields as $field) {
        if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
            $error = true;
            $error_fields .= $field . ', ';
        }
    }
 
    if ($error) {
        // Required field(s) are missing or empty
        // echo error json and stop the app
        $response = array();
        $app = \Slim\Slim::getInstance();
        $response["error"] = true;
		$response["noty_msg"] = true;
        $response["message"] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
        //echoResponse(400, $response);
		echoResponse(200, $response);
        $app->stop();
    }
}

function excel_date_format($date)
{
	$admin_date = date('m/d/Y',PHPExcel_Shared_Date::ExcelToPHP($date)); 
	$date_parts = explode("/", $admin_date);
	$new_date = $date_parts[1]."/".$date_parts[0]."/".$date_parts[2];
	return $new_date;	
}

//check for valid dates == format d/m/Y
function valid_date($date, $csv=false)
{
	
	if (!$csv) {
		$UNIX_DATE = ($date - 25569) * 86400;
		//return gmdate("Y-m-d H:i:s", $UNIX_DATE);
		$date = gmdate("m/d/Y", $UNIX_DATE);
	}

	$date_arr  = explode('/', $date);
	if (count($date_arr) == 3) {
		if (checkdate($date_arr[0], $date_arr[1], $date_arr[2])) {
			// valid date
			$response = true;
		} else {
			// problem with dates
			$response = false;
		}
	} else {
		// problem with input
		$response = false;
	}
	
	return $response;
			
}

function excel_date_formatted($date)
{
	$UNIX_DATE = ($date - 25569) * 86400;
	//return gmdate("Y-m-d H:i:s", $UNIX_DATE);
	return gmdate("d/m/Y", $UNIX_DATE);
}

function excel_csv_date_formatted($date)
{
	$date_parts = explode("/", $date);
	//mktime(hour,minute,second,month,day,year,is_dst);
	return date("d/m/Y", mktime(0, 0, 0, $date_parts[0], $date_parts[1], $date_parts[2]));				
}
 
/**
 * Validating email address
 */
/*function validateEmail($email) {
    $app = \Slim\Slim::getInstance();
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response["error"] = true;
        $response["message"] = 'Email address is not valid';
        echoResponse(400, $response);
        $app->stop();
    }
}*/

function validateEmail($email) {

	return preg_match("/^(((([^]<>()[\.,;:@\" ]|(\\\[\\x00-\\x7F]))\\.?)+)|(\"((\\\[\\x00-\\x7F])|[^\\x0D\\x0A\"\\\])+\"))@((([[:alnum:]]([[:alnum:]]|-)*[[:alnum:]]))(\\.([[:alnum:]]([[:alnum:]]|-)*[[:alnum:]]))*|(#[[:digit:]]+)|(\\[([[:digit:]]{1,3}(\\.[[:digit:]]{1,3}){3})]))$/", $email);

} 
	

function IsNullOrEmptyString($str) {
    return (!isset($str) || trim($str) === '');
}
 
/**
 * Echoing json response to client
 * @param String $status_code Http response code
 * @param Int $response Json response
 */
function echoResponse($status_code, $response) {
    $app = \Slim\Slim::getInstance();
    // Http response code
    $app->status($status_code);
 
    // setting response content type to json
    $app->contentType('application/json');
 
    echo json_encode($response);
}

function logoutUser() {
	   
	   $response = array(); 
	   
	   //clear all user session vars
	   unset($_SESSION['USERS']);
	   if (isset($_COOKIE[session_name()])) {
		   setcookie(session_name(), '', time()-42000, '/');
	   }   
		 
	   $_SESSION = NULL;
	   $_SESSION['SESS_USER_ID']=NULL;
	   $_SESSION['SESS_USER_NAME']=NULL;
	   $_SESSION['SESS_FIRST_NAME'] = NULL;
	   $_SESSION['SESS_LAST_NAME'] = NULL;
	   $_SESSION['SESS_FULL_NAMES'] = NULL;
	   $_SESSION['SESS_USER_TYPE'] = NULL;
	   $_SESSION['SESS_USER_EMAIL'] = NULL;
	   $_SESSION['SESS_USER_COUNTRY'] = NULL;
	   $_SESSION['SESS_USER_PERMISSIONS'] = NULL;
	   $_SESSION['SESS_USER_LOGGED_IN'] = NULL;
	   
	   $_SESSION['SUPER_ADMIN_USER'] = NULL;
	   $_SESSION['SCHOOL_ADMIN_USER'] = NULL;
	   $_SESSION['NORMAL_ADMIN_USER'] = NULL;
	   $_SESSION['NORMAL_USER'] = NULL;
	   
	   $_SESSION['HAS_READ_USER_PERMISSION'] = NULL;
	   $_SESSION['HAS_CREATE_USER_PERMISSION'] = NULL;
	   $_SESSION['HAS_UPDATE_USER_PERMISSION'] = NULL;
	   $_SESSION['HAS_DELETE_USER_PERMISSION'] = NULL;
	   
	   $_SESSION['HAS_READ_STUDENT_PERMISSION'] = NULL;
	   $_SESSION['HAS_CREATE_STUDENT_PERMISSION'] = NULL;
	   $_SESSION['HAS_UPDATE_STUDENT_PERMISSION'] = NULL;
	   $_SESSION['HAS_DELETE_STUDENT_PERMISSION'] = NULL;
	   
	   $_SESSION['HAS_READ_SCHOOL_PERMISSION'] = NULL;
	   $_SESSION['HAS_CREATE_SCHOOL_PERMISSION'] = NULL;
	   $_SESSION['HAS_UPDATE_SCHOOL_PERMISSION'] = NULL;
	   $_SESSION['HAS_DELETE_SCHOOL_PERMISSION'] = NULL;
	   
	   $_SESSION['HAS_READ_SUBJECT_PERMISSION'] = NULL;
	   $_SESSION['HAS_CREATE_SUBJECT_PERMISSION'] = NULL;
	   $_SESSION['HAS_UPDATE_SUBJECT_PERMISSION'] = NULL;
	   $_SESSION['HAS_DELETE_SUBJECT_PERMISSION'] = NULL;
	   
	   $_SESSION['HAS_READ_RESULT_PERMISSION'] = NULL;
	   $_SESSION['HAS_CREATE_RESULT_PERMISSION'] = NULL;
	   $_SESSION['HAS_UPDATE_RESULT_PERMISSION'] = NULL;
	   $_SESSION['HAS_DELETE_RESULT_PERMISSION'] = NULL;
	   
	   $_SESSION['HAS_READ_FEE_PERMISSION'] = NULL;
	   $_SESSION['HAS_CREATE_FEE_PERMISSION'] = NULL;
	   $_SESSION['HAS_UPDATE_FEE_PERMISSION'] = NULL;
	   $_SESSION['HAS_DELETE_FEE_PERMISSION'] = NULL;
	
	   unset($_SESSION['SESS_USER_ID']);
	   unset($_SESSION['SESS_USER_NAME']);
	   unset($_SESSION['SESS_FIRST_NAME']);
	   unset($_SESSION['SESS_LAST_NAME']);
	   unset($_SESSION['SESS_FULL_NAMES']);
	   unset($_SESSION['SESS_USER_TYPE']);
	   unset($_SESSION['SESS_USER_EMAIL']);
	   unset($_SESSION['SESS_USER_COUNTRY']);
	   unset($_SESSION['SESS_USER_PERMISSIONS']);
	   unset($_SESSION['SESS_USER_LOGGED_IN']);
	   
	   unset($_SESSION['SUPER_ADMIN_USER']);
	   unset($_SESSION['SCHOOL_ADMIN_USER']);
	   unset($_SESSION['NORMAL_ADMIN_USER']);
	   unset($_SESSION['NORMAL_USER']);
	   
	   unset($_SESSION['HAS_READ_USER_PERMISSION']);
	   unset($_SESSION['HAS_CREATE_USER_PERMISSION']);
	   unset($_SESSION['HAS_UPDATE_USER_PERMISSION']);
	   unset($_SESSION['HAS_DELETE_USER_PERMISSION']);
	   
	   unset($_SESSION['HAS_READ_STUDENT_PERMISSION']);
	   unset($_SESSION['HAS_CREATE_STUDENT_PERMISSION']);
	   unset($_SESSION['HAS_UPDATE_STUDENT_PERMISSION']);
	   unset($_SESSION['HAS_DELETE_STUDENT_PERMISSION']);
	   
	   unset($_SESSION['HAS_READ_SCHOOL_PERMISSION']);
	   unset($_SESSION['HAS_CREATE_SCHOOL_PERMISSION']);
	   unset($_SESSION['HAS_UPDATE_SCHOOL_PERMISSION']);
	   unset($_SESSION['HAS_DELETE_SCHOOL_PERMISSION']);
	   
	   unset($_SESSION['HAS_READ_SUBJECT_PERMISSION']);
	   unset($_SESSION['HAS_CREATE_SUBJECT_PERMISSION']);
	   unset($_SESSION['HAS_UPDATE_SUBJECT_PERMISSION']);
	   unset($_SESSION['HAS_DELETE_SUBJECT_PERMISSION']);
	   
	   unset($_SESSION['HAS_READ_RESULT_PERMISSION']);
	   unset($_SESSION['HAS_CREATE_RESULT_PERMISSION']);
	   unset($_SESSION['HAS_UPDATE_RESULT_PERMISSION']);
	   unset($_SESSION['HAS_DELETE_RESULT_PERMISSION']);
	   
	   unset($_SESSION['HAS_READ_FEE_PERMISSION']);
	   unset($_SESSION['HAS_CREATE_FEE_PERMISSION']);
	   unset($_SESSION['HAS_UPDATE_FEE_PERMISSION']);
	   unset($_SESSION['HAS_DELETE_FEE_PERMISSION']);

	   session_destroy();

	   
}
 
$app->run();
?>