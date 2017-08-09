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
		$response['slide_form'] = true;
		$response['slide_duration'] = 4000;
		$response['message'] = "Password must be the same as repeat password";
		
	} else {
 
		$db = new DbHandler();
		$response = $db->changePassword($password, $new_password1, $username);
	
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
	
	if ($password=="" || $password2==""){
		$response["error"] = true;
		$response["message"] = "password and repeat password cannot be blank!";
	} else if ($password != $password2){
		$response["error"] = true;
		$response["message"] = "password and repeat password fields do not match!";
	} else {
		$db = new DbHandler();
		$response = $db->createUser($phone_number, $password, $email, $first_name, $last_name, $full_names);
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
    verifyRequiredParams(array('id', 'name'));
 
    // reading post params
    $sch_id = $app->request->post('id');
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

//create new student result
$app->post('/createStudentFee', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('amount', 'payment_mode', 'paid_by', 'payment_date', 'student_id', 'fee_year'));
 
    // reading post params
	$amount = $app->request->post('amount');
	$payment_mode = $app->request->post('payment_mode');
	$paid_by = $app->request->post('paid_by');
	$payment_date = $app->request->post('payment_date');
	$student_id = $app->request->post('student_id');
	$year = $app->request->post('fee_year');
 
    $db = new DbHandler();
    
	// insert a new chat
    $response = $db->createStudentFee($amount, $payment_mode, $paid_by, $payment_date, $student_id, $year);
 
    // echo json response
    echoResponse(200, $response);
});

//create new student result
$app->post('/createStudentResult', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('year', 'term', 'subject', 'score', 'student_id', 'sch_id', 'reg_no', 'class'));
 
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
				$paid_at = $rowData[0][5];
				//apply coversion to files other than csv
				if ($paid_at && ($ext!='csv')) {
					$paid_at = excel_date_formatted($paid_at); 
				} else {
					$paid_at = excel_csv_date_formatted($paid_at); 	
				}

				$tmp["reg_no"] = $rowData[0][0]; 
				$tmp["year"] = $rowData[0][1];
				$tmp["amount_paid"] = $rowData[0][2];
				$tmp["payment_mode"] = $rowData[0][3];
				$tmp["paid_by"] = $rowData[0][4];
				$tmp["paid_at"] = $paid_at;
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
		
			// insert a new chat
			$response = $db->uploadFees($sch_id, $send_fees);
			
		}
				
	} else {
		$response['error'] = "true";
		$response['message'] = "Please select a file";
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
	$message = $app->request->post('message');
	$selected = $app->request->post('selected');
	$messageType = $app->request->post('messageType');
	$results_year = $app->request->post('results_year');
	$fees_year = $app->request->post('fees_year');
	$term = $app->request->post('term');
	
	if (($message=="" && $messageType=="memo") || ($fees_year=="" && $messageType=="fees") || ($results_year=="" && $messageType=="results")){
		
		$response["error"] = true;
		$response["noty_msg"] = true;
		$response["message"] = "Please enter all required data to proceed!";
		
	} else if ($selected=="" && $enter_contacts==""){
		
		$response["error"] = true;
		$response["noty_msg"] = true;
		$response["message"] = "Please select contacts or type the contacts in";
		
	} else {
	
		
		
		//if contacts have been typed in, check for any invalid contact from list
		if ($enter_contacts) {
			//TYPED IN CONTACTS
			$student_data = 0;
			$selected_array = array();
			$bad_phone_numbers = array();
			$contacts_array = explode(",", $enter_contacts);
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
				}
			}
			
		} else {
			
			//SELECTED CONTACTS
			//split selected items into array
			$selected_array = explode(",", $selected);
			$student_data = 1;
		
		}
		
		if (!$success) {
			$bad_phone_numbers_text = implode(",", $bad_phone_numbers); //print_r($bad_phone_numbers); exit;
			//ERROR OCCURED IN TYPED CONTACTS
			$response["error"] = true;
			$response["noty_msg"] = true;
			$response["message"] = "Invalid Phone Numbers!\n\nPlease correct the following numbers: $bad_phone_numbers_text ";
			
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
	
	$sch_id = $app->request->post('sch_id');
	
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
			
			for ($row = 2; $row <= $highestRow; $row++){ 
				$tmp = array();
				//  Read a row of data into an array
				$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
												NULL,
												TRUE,
												FALSE);
	
				//read row data into array 
				$admin_date = $rowData[0][2];
				$dob = $rowData[0][4];
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
				$tmp["guardian_mobile"] = $rowData[0][16];
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
			//print_r($students); exit;
			$send_students["students"] = $students;
	
			// Echo memory peak usage
			$send_students["stats"] =  "Time: " . date('H:i:s') . " Peak memory usage: " . (memory_get_peak_usage(true) / 1024 / 1024) . " MB";
			$send_students["sch_id"] =  $sch_id;
			
			$db = new DbHandler();
		
			// insert a new chat
			$response = $db->uploadStudents($sch_id, $send_students);
			
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
    // check for required params
    //verifyRequiredParams(array('sch_name', 'sch_category'));
 
    // reading post params
	$sch_name = trim($app->request->post('sch_name'));
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
 
    $db = new DbHandler();
    
	// insert a new chat
    $response = $db->createSchool($sch_name, $sch_category, $sch_province, $status, $motto, $phone1, $phone2, $sms_welcome1, $sms_welcome2, $address);
 
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
	$county = trim($app->request->post('county'));
	$location = trim($app->request->post('location'));
	$disability = trim($app->request->post('disability'));
	$gender = trim($app->request->post('gender'));
	$stream = trim($app->request->post('stream'));
	$constituency = trim($app->request->post('constituency'));
 
    $db = new DbHandler();
	// insert a new chat
    $response = $db->createStudent($full_names, $reg_no, $sch_id, $admin_date, $student_profile, $guardian_name, $guardian_phone, $guardian_address, $dob, $index_no, $nationality, $religion, $previous_school, $house, $club, $guardian_id_card, $guardian_relation, $guardian_occupation, $email, $town, $current_class, $village, $county, $location, $disability, $gender, $stream, $constituency);
 
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

// send general sms
$app->post('/user/sendsms', function() use ($app) {
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
    verifyRequiredParams(array('reg_no', 'school_id', 'phone_number'));
 
    // reading post params
    $reg_no = $app->request->post('reg_no');
	$school_id = $app->request->post('school_id');
	$phone_number = $app->request->post('phone_number');
	$dob = $app->request->post('dob');
 
    $db = new DbHandler();
    
	// fetch data
	$result['error'] = false;
    $result['student'] = $db->getStudentData($reg_no, $school_id, $phone_number, $dob);
 
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

//get school listing
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

//get school listing
$app->post('/fetchStudentGridListing', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('sch_id'));
 
    // reading post params
	$page = $app->request->post('current');
	$sch_id = $app->request->post('sch_id');
	$lperpage = $app->request->post('rowCount');
	$searchPhrase = $app->request->post('searchPhrase');
	$sort = $app->request->post('sort');
	//$orderstyle = $app->request->post('orderstyle');
 
    $db = new DbHandler();
    
	// fetch data
	$result['error'] = false;
    $result = $db->getStudentGridListing($sch_id, $page, $searchPhrase, $lperpage, $sort);
 
    // echo json response
    echoResponse(200, $result);
});

//get student results listing
$app->post('/fetchStudentResults', function() use ($app) {
    // reading post params
	$sch_id = $app->request->post('sch_id');
	$reg_no = $app->request->post('reg_no');
	$year = $app->request->post('year');
	$term = $app->request->post('term');
	$page = $app->request->post('current');
	$lperpage = $app->request->post('rowCount');
	$searchPhrase = $app->request->post('searchPhrase');
	$sort = $app->request->post('sort');
 
    $db = new DbHandler();
    
	// fetch data
	$result['error'] = false;
    $result = $db->getStudentResultsGridListing($sch_id, $reg_no, $year, $term, $page, $searchPhrase, $lperpage, $sort);
 
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
 
    $db = new DbHandler();
    
	// fetch data
	$result['error'] = false;
    $result = $db->editSingleFee($fee_payment_id, $amount, $payment_mode, $paid_by, $paid_at);
 
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
	$level_id = $app->request->post('level_id');
	$lperpage = $app->request->post('rowCount');
	$searchPhrase = $app->request->post('searchPhrase');
	$sort = $app->request->post('sort');
 
    $db = new DbHandler();
    
	// fetch data
	$result['error'] = false;
    $result = $db->getSubjectGridListing($level_id, $page, $searchPhrase, $lperpage, $sort);
 
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
 	
	$page = $app->request->post('current');
	$user_id = $app->request->post('user_id');
	$lperpage = $app->request->post('rowCount');
	$searchPhrase = $app->request->post('searchPhrase');
	$sort = $app->request->post('sort');
	
    $db = new DbHandler();
    
	// fetch data
	//$result['error'] = false;
    $result = $db->getSchoolGridListing($page, $user_id, $searchPhrase, $lperpage, $sort);
 
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

//get school listing
$app->post('/fetchStudentFeePayments', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('student_id'));
 
    // reading post params
	$student_id = $app->request->post('student_id');
	$phone_number = $app->request->post('phone_number');
	$year = $app->request->post('year');
	$page = $app->request->post('page');
 
    $db = new DbHandler();
    
	// fetch data
	$result['error'] = false;
    $result['fee_payment'] = $db->getStudentFeePayments($page, $student_id, $phone_number, $year);
 
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
$app->post('/fetchStudentResults', function() use ($app) {
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
    // check for required params
    verifyRequiredParams(array('school_id', 'student_id', 'year'));
 
    // reading post params
	$school_id = $app->request->post('school_id');
	$student_id = $app->request->post('student_id');
	$year = $app->request->post('year');
 
    $db = new DbHandler();
    
	// fetch data
	//$result['error'] = false;
    $result= $db->getStudentFees($school_id, $student_id, $year);
 
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
    verifyRequiredParams(array('phone_number'));
 
    // reading post params
    $phone_number = $app->request->post('phone_number');
	$page = $app->request->post('page');
 
    $db = new DbHandler();
    
	// fetching all user tickets
	$result['error'] = false;
    $result = $db->getSubscriptions($phone_number, $page);
 
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
 
    $db = new DbHandler();
    
	//save new transaction
    $result = $db->getPaybillNumber($sch_id, $sch_name);
 
    // echo json response
    echoResponse(200, $result);
	
}); 

//request user to perform mpesa fee payment
$app->post('/requestMpesaFeePayment', function() use ($app) {
	
    // check for required params
    verifyRequiredParams(array('amount', 'phone_number', 'sch_id', 'reg_no', 'student_names'));
 
    // reading post params
    $sch_id = $app->request->post('sch_id');
	$sch_name = $app->request->post('sch_name');
	$reg_no = $app->request->post('reg_no');
	$student_names = $app->request->post('student_names');
	$phone_number = $app->request->post('phone_number');
	$amount = $app->request->post('amount');
 
    $db = new DbHandler();
    
	//save new transaction
    $result = $db->requestMpesaFeePayment($sch_id, $sch_name, $reg_no, $student_names, $phone_number, $amount);
 
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
	$student_id = $app->request->post('student_id');
 
    $db = new DbHandler();
    
	// fetching all user tickets
	$result['error'] = false;
    $result['chats'] = $db->getAllUserChats($user_id, $page, $student_id);
 
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
	verifyRequiredParams(array('page', 'user_id'));
    $page = $app->request->post('page');
	$user_id = $app->request->post('user_id');
    $db = new DbHandler();
    $result['chat_messages']  = $db->getChat($chat_id, $page, $user_id);	
	// fetching all chat msgs
	$result['error'] = false;
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
		$response["ref"] = "none";
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

function excel_date_formatted($date)
{
	$UNIX_DATE = ($date - 25569) * 86400;
	return gmdate("Y-m-d H:i:s", $UNIX_DATE);
}

function excel_csv_date_formatted($date)
{
	$date_parts = explode("/", $date);
	//mktime(hour,minute,second,month,day,year,is_dst);
	return date("Y-m-d H:i:s", mktime(0, 0, 0, $date_parts[0], $date_parts[1], $date_parts[2]));				
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