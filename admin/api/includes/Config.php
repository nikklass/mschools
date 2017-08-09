<?php
	
	ob_start();
	if (!isset($_SESSION)) session_start();
	error_reporting(0);
	@ini_set('display_errors', 0);
 
	//include_once("DB_handler.php");
	include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'DB_handler.php';
	

	
	/********************************** EDITABLE LINES - PATH TO IMAGE(S) AND IMAGE NAMES ****************************************************/
	
	//pendosoftwares login
	/*define("DB_HOST", "localhost");
	define("DB_USER", "pendosof_user1");
	define("DB_DATABASE", "pendosof_pendo");
	define("DB_PASSWORD", "123pesof2016");*/
	
	//41 server login
	define("DB_HOST", "192.168.5.211");
    define("DB_USER", "root");
    define("DB_DATABASE", "showbiz_pendo");
    define("DB_PASSWORD", "PMaX@x2016"); 
	
	//*************************************************************
	
	//localhost login
	/*define("DB_HOST", "localhost");
	define("DB_USER", "showbiz_user1");
	define("DB_DATABASE", "showbiz_pendo");
	define("DB_PASSWORD", "123xyz456");*/
	
	//mschools login
	/*define("DB_HOST", "localhost");
	define("DB_USER", "mschools_user1");
	define("DB_DATABASE", "mschools_pendo");
	define("DB_PASSWORD", "123xyz456");*/

	
	//define("SITEPATH", "http://localhost/pendoschools/");
	define("SITEPATH", "http://mschools.co.ke/pendoschool_app/");
	//define("SITEPATH", "http://41.215.126.10:5333/pendoschool_app/");
	define("DEFAULT_USER_IMAGE", "images/no_image.jpg");
	define("DEFAULT_SCHOOL_IMAGE", "images/no_school_image.jpg");
	define("SITE_LOGO", SITEPATH . "admin/images/logo.png");
	
	
	//******************************************************************************************************
	define("CURRENT_API_PATH", SITEPATH . "admin/api/v1/");
	//******************************************************************************************************
	
	
	//START remote api settings ****************************************************************************
	
	define("REMOTE_API_SITEPATH", "http://41.215.126.10:5333/api/v1/");

	define("SEND_BULK_SMS_URL", REMOTE_API_SITEPATH . "send_bulk_sms");
	define("GET_BULK_SMS_DATA_URL", REMOTE_API_SITEPATH. "get_bulk_sms_data");
	define("GET_SCHOOL_SMS_INBOX_URL", REMOTE_API_SITEPATH. "get_sms_inbox");
	define("GET_MPESA_IPNS_URL", REMOTE_API_SITEPATH. "get_mpesa_ipns");
	
	define("GET_BULK_SMS_BALANCE", "http://41.215.126.10:5333/sms/fxd1.php");
	
	//END remote api settings ******************************************************************************
	
		
	$page_titles = "Pendoschools - Find all about your child school information";
	
	//define("SEND_SMS_URL", "http://localhost/pendoschool_app/?tag=send_sms"); 
	//define("SEND_SMS_URL", "http://41.215.126.10:5333/pendoschool_app/?tag=send_sms"); //receives &mobile=" . $phone_number . "&msg=" . $message;
	
	define("SERVICE_ID_SCHOOLS", "MDSP2000075075");
	
	define("ADD_PARENT_LOGIN_PASSWORD", "Student Date of Birth (ddmmyyyy)");
	
	define("ADD_SUB_PARENT_EXISTS_MESSAGE", "Dear %s, follow this link http://bit.ly/pendo_app to download pendo school app on playstore and access results,fees balance and pay fees on Mpesa for %s from your phone. Sign in using your user name and password. You can also dial *533*13# on your Safaricom line to access the service.");
	
	define("ADD_PARENT_ACCOUNT_MESSAGE", "Dear %s, follow this link http://bit.ly/pendo_app to download pendo school app on playstore and access results,fees balance and pay fees on Mpesa for %s from your phone. Sign in using your phone number as your user name and %s as your password. You can also dial *533*13# on your Safaricom line to access the service.");
	
	
	define("MPESA_CONFIRMATION_MESSAGE", "Dear %s, Your school fees payment of %S for %s of Reg No. %s has been well received. The new fees balance is %s. Thank you. %s. Call %s for any queries.");
	
	
	/********************************** END EDITABLE LINES ****************************************************/
	
	$db = new DbHandler();
	
	//TIMEZONE
	define('TIMEZONE', 'Africa/Nairobi'); //default timezone
	
	// set the default timezone	
	$defaultTimeZone='Africa/Nairobi';
	//if(date_default_timezone_get()!=$defaultTimeZone) { date_default_timezone_set($defaultTimeZone); }
	date_default_timezone_set(TIMEZONE);
		
	//define("GOOGLE_API_KEY", "AIzaSyARWTpwl_C9zjEELK70q8qDLwB6nzKcxeg");
	
	//define("IPINFODB_API_KEY", "2cc4db46a2be1a349528c3bd9bf8726480b1c665435c91bb43c42fe101da6cde");
	
	// push notification flags
	define('PUSH_FLAG_TICKET', 1);
	define('PUSH_FLAG_USER', 2);
	define('PUSH_FLAG_CHATROOM', 3);
	
	define('TOPIC_CHAT_PREFIX', "/topics/topic_message_chat_");
	define('TOPIC_TICKET_NOTIFICATION_PREFIX', "/topics/topic_ticket_notitication_");
	
	define('GOOGLE_FCM_MESSAGING_KEY', 'AIzaSyB9htQEMSmHSuoeRsPOObSBIT0cn4sZzCo');
	
	define('COMPANY_APP_MESSAGING_TITLE', "Pendo Schools App Messaging");
	
	
	define("PROFILE_PIC_CAT_ID", 1);
	define("SCHOOL_PIC_CAT_ID", 2);
	define("STUDENT_PROFILE_PIC_CAT_ID", 3);
	
	
	//DEFINE REPORT TABLES
	define('REPORT_TABLE_USER_VISITS_PER_DAY', "rpt_users_visits_per_day");
	define('REPORT_TABLE_USERS_CREATED_PER_DAY', "rpt_users_created_per_day");
	define('REPORT_TABLE_DEPOSITS_CREATED_PER_DAY', "rpt_deposits_made_per_day");
	define('REPORT_TABLE_TICKETS_CREATED_PER_DAY', "rpt_tickets_created_per_day");
	
	define('REPORT_TABLE_USER_VISITS_PER_MONTH', "rpt_users_visits_per_month");
	define('REPORT_TABLE_USERS_CREATED_PER_MONTH', "rpt_users_created_per_month");
	define('REPORT_TABLE_DEPOSITS_CREATED_PER_MONTH', "rpt_deposits_made_per_month");
	define('REPORT_TABLE_TICKETS_CREATED_PER_MONTH', "rpt_tickets_created_per_month");	
	//END DEFINE REPORT TABLES
	
	define('CHAT_OWNER_TEXT', "You");
	
	define('NO_CHATS_FOUND_MESSAGE', "No Chats Found");
	define('NO_CHAT_MESSAGES_FOUND_MESSAGE', "No Chat Messages Found");
	
	
	//general
	define('STATIC_DEFAULT_CURRENCY', "Kshs. ");
	define('UNDEFINED_TEXT', "undefined");
	
	define('NOTY_MESSAGE_LOCATION', 'center');
	
	//SUCCESS
	define('SUCCESS_MESSAGE', "Success");
	
	//ERROR MESSAGES
	define('NO_PERMISSION_ERROR_MESSAGE', "You have no permission");
	define('PLEASE_ENTER_MESSAGE_ERROR', "Please enter message");
	define('AN_ERROR_OCCURED_MESSAGE', "An error occured");
	define('DATA_SUCCESSFULLY_UPDATED_MESSAGE', "Data successfully updated");
	define('PLEASE_ENTER_REQUIRED_DATA_ERROR', "Please enter all required details");
	define('MISSING_USERNAME_PASSWORD_ERROR', "Missing username or password");
	define('FAILED_TO_UPDATE_RECORD_ERROR', "Failed to update record");
	define('FAILED_TO_INSERT_RECORD_ERROR', "Failed to insert record");
	define('INVALID_ACCESS_ERROR_MESSAGE', "Invalid Access");
	define('SELECT_ESTABLISHMENT_ERROR_MESSAGE', "Please select establishment");
	define('INVALID_EMAIL_ERROR_MESSAGE', "Please enter a valid email address");
	define('PLEASE_LOGIN_FIRST_ERROR_MESSAGE', "Please login first");
	define('INVALID_PHONE_NUMBER_ERROR_MESSAGE', "Please enter a valid phone number in any of these formats: \n 07XXXXXXXX \n or 2547XXXXXXXX \n or +2547XXXXXXXX");
	define('INVALID_AGE_ERROR_MESSAGE', "Your age is NOT allowed to access this site.\n You MUST be 18yrs and above.");
	define('INVALID_AMOUNT_NUMBER_ERROR_MESSAGE', "Amount must be a number");
	define('INVALID_PAYMENT_MODE_ERROR_MESSAGE', "Invalid payment mode. Valid options: cash, mpesa, cheque");
	define('NO_PAYBILL_NUMBER_ERROR_MESSAGE', "No Paybill Number exists for \"<b>%s</b>\".<br><br> Please contact PendoSchools admin to create one.");
	define('NO_BULK_SMS_ACCOUNT_ERROR_MESSAGE', "No Bulk SMS Account exists for \"<b>%s</b>\".<br><br> Please contact PendoSchools admin to create one.");
	
	
	
	define("MESSAGE_DIALOG_TIMEOUT", 4000);
	//END ERROR MESSAGES
	
	//DEFINE error messages
	define("ERROR_OCCURED", "error_occured");
	define("AN_ERROR_OCCURED_ERROR", "error_occured");
	define("INVALID_ACCESS_ERROR", "invalid_access");
	define("NOT_VALIDATED", "not_validated");
	define("NAN_ERROR", "not_a_number");
	define("NO_PERMISSION_ERROR", "no_permission");
	
	
	//status sections
	define("SCHOOL_STATUS_SECTION", "school");
	define("SUBJECT_STATUS_SECTION", "subject");
	define("ADD_PARENT_STATUS_SECTION", "add-parent");
	define("SMS_STATUS_SECTION", "sms");
	
	//button texts
	define("SAVE_CHANGES_BTN_TEXT", "Save Changes");
	define("SUBMIT_BTN_TEXT", "Submit");
	
	//DATE FORMATS
	define("DATE_FMT", "Y-m-d H:i:s");
	define("DATE_FMT1", "d-M-Y");
	define("DATE_FMT2", "d/m/Y");
	define("DATE_FMT3", "d-M-Y, H:i");
	
	
	//user types
	define("NORMAL_USER_ID", 1);
	define("SCHOOL_ADMIN_USER_ID", 5);
	define("NORMAL_ADMIN_USER_ID", 2);
	define("SUPER_ADMIN_USER_ID", 4); 
	define("SCHOOL_USER_ID", 6);
	define("PARENT_USER_ID", 7); 
	
	//sms types
	define("REGISTRATION_SMS", 1);
	define("RECOMMENDATION_SMS", 2);
	define("RESENT_REGISTRATION_SMS", 3);
	define("FORGOT_PASSWORD_SMS", 4);
	define("SCHOOL_MESSAGE_SMS", 5);
	define("ADD_PARENT_REQUEST_SMS", 6);
	
	//errors
	define("ERROR_OCCURED", "error_occured");
	define("NOT_VALIDATED", "not_validated");
	
	//SDP Queue
	define("SDP_QUEUE_NUMBER", "MDSP2000075075");
	
	//OBJECT CONFIGS
	//STUDENT OBJECT
	define("STUDENT_ID", "student_id");
	define("STUDENT_FULL_NAMES", "student_full_names");
	define("STUDENT_GENDER", "student_gender");
	define("STUDENT_REG_NO", "student_reg_no");
	define("STUDENT_INDEX_NO", "student_index_no");
	define("STUDENT_ADMIN_DATE", "student_admin_date");
	define("STUDENT_FULL_NAMES", "student_full_names");
	define("STUDENT_DOB", "student_dob");
	define("STUDENT_SCH_ID", "student_sch_id");
	define("STUDENT_NATIONALITY", "student_nationality");
	define("STUDENT_RELIGION", "student_religion");
	define("STUDENT_PREVIOUS_SCHOOL", "student_previous_school");
	define("STUDENT_HOUSE", "student_house");
	define("STUDENT_CLUB", "student_club");
	define("STUDENT_CURRENT_CLASS", "student_current_class");
	define("STUDENT_GUARDIAN_ID_CARD", "student_guardian_id_card");
	define("STUDENT_RELATION", "student_relation");
	define("STUDENT_OCCUPATION", "student_occupation");
	define("STUDENT_EMAIL", "student_email");
	define("STUDENT_COUNTY", "student_county");
	define("STUDENT_TOWN", "student_town");
	define("STUDENT_VILLAGE", "student_village");
	define("STUDENT_LOCATION", "student_location");
	define("STUDENT_DISABILITY", "student_disability");
	define("STUDENT_GENDER", "student_gender");
	define("STUDENT_STREAM", "student_stream");
	define("STUDENT_CONSTITUENCY", "student_constituency");
	define("STUDENT_PROFILE", "student_profile");
	define("STUDENT_GUARDIAN_NAME", "student_guardian_name");
	define("STUDENT_GUARDIAN_PHONE", "student_guardian_phone");
	define("STUDENT_GUARDIAN_ADDRESS", "student_guardian_address");
	define("STUDENT_CREATED_AT", "student_created_at");
	define("STUDENT_CREATED_BY", "student_created_by");
	define("STUDENT_UPDATED_AT", "student_updated_at");
	define("STUDENT_UPDATED_BY", "student_updated_by");

	//END STUDENT OBJECT
	
	
	define("MAX_CHAR_LENGTH", 480);
	
	define("GET_SINGLE_RESULT_URL", SITEPATH . "admin/api/v1/fetchSingleResult");
	define("GET_SINGLE_FEE_URL", SITEPATH . "admin/api/v1/fetchSingleFee");
	define("GET_SINGLE_SUBJECT_URL", SITEPATH . "admin/api/v1/fetchSingleSubject");
	
	define("LOGIN_URL", SITEPATH . "login");
	define("REGISTER_URL", SITEPATH . "register");
	define("CHANGE_PASS_URL", SITEPATH . "change-password");
	
	
	define("MESSAGE_DIALOG_TIMEOUT", 4000);
	define("MESSAGE_DIALOG_TIMEOUT_LONG", 10000);
	
	//SETTINGS
	//Define user login session vars
	
	define("SESSION_ID", $_SESSION['SESS_ID']);
	define("USER_LOGGED_IN", $_SESSION['SESS_USER_LOGGED_IN']);
	define("USER_NAME", $_SESSION['SESS_LOGGED_USER_NAME']);
	define("FULL_NAMES", $_SESSION['SESS_FULL_NAMES']);
	define("FIRST_NAME", $_SESSION['SESS_FIRST_NAME']);
	define("LAST_NAME", $_SESSION['SESS_LAST_NAME']);
	define("USER_PHONE", $_SESSION['SESS_USER_PHONE']);
	define("USER_ID", $_SESSION['SESS_USER_ID']);
	define("IS_USER_LOGGED_IN", $_SESSION['SESS_USER_LOGGED_IN']);
	define("USER_EMAIL", $_SESSION['SESS_USER_EMAIL']);
	define("USER_IMAGE", $_SESSION['SESS_USER_IMAGE']);
	define("USER_COUNTRY", $_SESSION['SESS_USER_COUNTRY']);
	define("LOGGED_IN_USER_GROUP_ID", $_SESSION['SESS_USER_GROUP_ID']);
	define("LOGGED_IN_USER_GROUP_NAME", $_SESSION['SESS_USER_GROUP_NAME']);
	define("USER_PERMISSIONS", $_SESSION['SESS_USER_PERMISSIONS']);
	//TRACK SUBSCRIPTIONS
	define("USER_SUBSCRIBED", $_SESSION['USER_SUBSCRIBED']);
	define("USER_SCHOOL_IDS", $_SESSION['USER_SCHOOL_IDS']);
	//ACCOUNT SETUPS
	define("SUPER_ADMIN_USER", $_SESSION['SUPER_ADMIN_USER']);  
	define("SCHOOL_ADMIN_USER", $_SESSION['SCHOOL_ADMIN_USER']);  
	define("NORMAL_ADMIN_USER", $_SESSION['NORMAL_ADMIN_USER']); 
	define("NORMAL_USER", $_SESSION['NORMAL_USER']); 
	//END ACCOUNT SETUPS	
	
	//PHOTOS CONFIGS
	define("STUDENT_PROFILE_PHOTO", "stud");
	define("STUDENT_OTHER_PHOTO", "studother");
	define("USER_PROFILE_PHOTO", "user");
	define("USER_OTHER_PHOTO", "userother");
	define("SCHOOL_ACTIVITY_PHOTO", "schact");
	define("SCHOOL_PROFILE_PHOTO", "sch");
	define("SCHOOL_OTHER_PHOTO", "schother");
	
	define("THUMB_IMAGE", "thumbimg");
	define("FULL_IMAGE", "fullimg");
	//END PHOTOS CONFIGS
	
	//IMAGE DIMENSIONS
	define("RECTANGULAR_IMAGE_WIDTH", 800);
	define("RECTANGULAR_IMAGE_HEIGHT", 530);
	
	//HOME SLIDER DIMENSIONS
	define("HOME_SLIDER_IMAGE_WIDTH", 1140);
	define("HOME_SLIDER_IMAGE_HEIGHT", 466);
	
	define("SQUARE_IMAGE_WIDTH", 400);
	define("SQUARE_IMAGE_HEIGHT", 400);
	
	//Table NAMES
	define("TABLE_PRODUCT_ADMIN", "products_admin");
	define("TABLE_EVENTS", "events");
	define("TABLE_CLUBS", "clubs");
	define("TABLE_CLIENTS", "clients");
	define("TABLE_OFFERS", "offers");
	define("TABLE_HOME_SLIDER", "home_slider");
	define("TABLE_ORDERS", "orders");
	define("TABLE_ORDER_ITEMS", "order_items");
	define("TABLE_PAYMENT_ITEMS", "payment_items");
	define("TABLE_OFFER_PRODUCTS", "offer_products");
	define("TABLE_CLUB_PAYMENTS", "club_payments");
	
	
	//PERMISSION CONFIGS
	define("CREATE_USER_PERMISSION", "create-user");
	define("UPDATE_USER_PERMISSION", "update-user");
	define("READ_USER_PERMISSION", "view-user");
	define("DELETE_USER_PERMISSION", "delete-user");
	
	define("CREATE_STUDENT_PERMISSION", "create-student");
	define("UPDATE_STUDENT_PERMISSION", "update-student");
	define("READ_STUDENT_PERMISSION", "view-student");
	define("DELETE_STUDENT_PERMISSION", "delete-student");
	
	define("CREATE_SCHOOL_PERMISSION", "create-school");
	define("UPDATE_SCHOOL_PERMISSION", "update-school");
	define("READ_SCHOOL_PERMISSION", "view-school");
	define("DELETE_SCHOOL_PERMISSION", "delete-school");
	
	define("CREATE_SUBJECT_PERMISSION", "create-subject");
	define("UPDATE_SUBJECT_PERMISSION", "update-subject");
	define("READ_SUBJECT_PERMISSION", "view-subject");
	define("DELETE_SUBJECT_PERMISSION", "delete-subject");
	
	define("CREATE_RESULT_PERMISSION", "create-result");
	define("UPDATE_RESULT_PERMISSION", "update-result");
	define("READ_RESULT_PERMISSION", "view-result");
	define("DELETE_RESULT_PERMISSION", "delete-result");
	
	define("CREATE_FEE_PERMISSION", "create-fee");
	define("UPDATE_FEE_PERMISSION", "update-fee");
	define("READ_FEE_PERMISSION", "view-fee");
	define("DELETE_FEE_PERMISSION", "delete-fee");
	
	define("CREATE_REPORT_PERMISSION", "create-report");
	define("UPDATE_REPORT_PERMISSION", "update-report");
	define("READ_REPORT_PERMISSION", "view-report");
	define("DELETE_REPORT_PERMISSION", "delete-report");
	
	define("CREATE_BULK_SMS_PERMISSION", "create-bulk-sms");
	define("UPDATE_BULK_SMS_PERMISSION", "update-bulk-sms");
	define("READ_BULK_SMS_PERMISSION", "view-bulk-sms");
	define("DELETE_BULK_SMS_PERMISSION", "delete-bulk-sms");
	
	define("CREATE_MPESA_TRANS_PERMISSION", "create-mpesa-trans");
	define("UPDATE_MPESA_TRANS_PERMISSION", "update-mpesa-trans");
	define("READ_MPESA_TRANS_PERMISSION", "view-mpesa-trans");
	define("DELETE_MPESA_TRANS_PERMISSION", "delete-mpesa-trans");	
	//END PERMISSION CONFIGS
	
	/******************************** OBJECT PERMISSIONS ***********************************************/
	 
	 //USER OBJECT PERMISSIONS
	 define("HAS_READ_USER_PERMISSION", $_SESSION['HAS_READ_USER_PERMISSION']);
	 define("HAS_CREATE_USER_PERMISSION", $_SESSION['HAS_CREATE_USER_PERMISSION']);
	 define("HAS_UPDATE_USER_PERMISSION", $_SESSION['HAS_UPDATE_USER_PERMISSION']);
	 define("HAS_DELETE_USER_PERMISSION", $_SESSION['HAS_DELETE_USER_PERMISSION']);
	 
	 define("ALL_USER_PERMISSIONS", "'" . READ_USER_PERMISSION . "','" . CREATE_USER_PERMISSION . "','" . UPDATE_USER_PERMISSION . "','" . DELETE_USER_PERMISSION . "'");
	 
	 //STUDENT OBJECT PERMISSIONS
	 define("HAS_READ_STUDENT_PERMISSION", $_SESSION['HAS_READ_STUDENT_PERMISSION']);
	 define("HAS_CREATE_STUDENT_PERMISSION", $_SESSION['HAS_CREATE_STUDENT_PERMISSION']);
	 define("HAS_UPDATE_STUDENT_PERMISSION", $_SESSION['HAS_UPDATE_STUDENT_PERMISSION']);
	 define("HAS_DELETE_STUDENT_PERMISSION", $_SESSION['HAS_DELETE_STUDENT_PERMISSION']);
	 
	 define("ALL_STUDENT_PERMISSIONS", "'" . READ_STUDENT_PERMISSION . "','" . CREATE_STUDENT_PERMISSION . "','" . UPDATE_STUDENT_PERMISSION . "','" . DELETE_STUDENT_PERMISSION . "'");
	 
	 //SCHOOL OBJECT PERMISSIONS
	 define("HAS_READ_SCHOOL_PERMISSION", $_SESSION['HAS_READ_SCHOOL_PERMISSION']);
	 define("HAS_CREATE_SCHOOL_PERMISSION", $_SESSION['HAS_CREATE_SCHOOL_PERMISSION']);
	 define("HAS_UPDATE_SCHOOL_PERMISSION", $_SESSION['HAS_UPDATE_SCHOOL_PERMISSION']);
	 define("HAS_DELETE_SCHOOL_PERMISSION", $_SESSION['HAS_DELETE_SCHOOL_PERMISSION']);
	 
	 define("ALL_SCHOOL_PERMISSIONS", "'" . READ_SCHOOL_PERMISSION . "','" . CREATE_SCHOOL_PERMISSION . "','" . UPDATE_SCHOOL_PERMISSION . "','" . DELETE_SCHOOL_PERMISSION . "'");
	 
	 //SUBJECT OBJECT PERMISSIONS
	 define("HAS_READ_SUBJECT_PERMISSION", $_SESSION['HAS_READ_SUBJECT_PERMISSION']);
	 define("HAS_CREATE_SUBJECT_PERMISSION", $_SESSION['HAS_CREATE_SUBJECT_PERMISSION']);
	 define("HAS_UPDATE_SUBJECT_PERMISSION", $_SESSION['HAS_UPDATE_SUBJECT_PERMISSION']);
	 define("HAS_DELETE_SUBJECT_PERMISSION", $_SESSION['HAS_DELETE_SUBJECT_PERMISSION']);
	 
	 define("ALL_SUBJECT_PERMISSIONS", "'" . READ_SUBJECT_PERMISSION . "','" . CREATE_SUBJECT_PERMISSION . "','" . UPDATE_SUBJECT_PERMISSION . "','" . DELETE_SUBJECT_PERMISSION . "'");
	 
	 //RESULT OBJECT PERMISSIONS
	 define("HAS_READ_RESULT_PERMISSION", $_SESSION['HAS_READ_RESULT_PERMISSION']);
	 define("HAS_CREATE_RESULT_PERMISSION", $_SESSION['HAS_CREATE_RESULT_PERMISSION']);
	 define("HAS_UPDATE_RESULT_PERMISSION", $_SESSION['HAS_UPDATE_RESULT_PERMISSION']);
	 define("HAS_DELETE_RESULT_PERMISSION", $_SESSION['HAS_DELETE_RESULT_PERMISSION']);
	 
	 define("ALL_RESULT_PERMISSIONS", "'" . READ_RESULT_PERMISSION . "','" . CREATE_RESULT_PERMISSION . "','" . UPDATE_RESULT_PERMISSION . "','" . DELETE_RESULT_PERMISSION . "'");
	 
	 //FEE OBJECT PERMISSIONS
	 define("HAS_READ_FEE_PERMISSION", $_SESSION['HAS_READ_FEE_PERMISSION']);
	 define("HAS_CREATE_FEE_PERMISSION", $_SESSION['HAS_CREATE_FEE_PERMISSION']);
	 define("HAS_UPDATE_FEE_PERMISSION", $_SESSION['HAS_UPDATE_FEE_PERMISSION']);
	 define("HAS_DELETE_FEE_PERMISSION", $_SESSION['HAS_DELETE_FEE_PERMISSION']);
	 
	 define("ALL_FEE_PERMISSIONS", "'" . READ_FEE_PERMISSION . "','" . CREATE_FEE_PERMISSION . "','" . UPDATE_FEE_PERMISSION . "','" . DELETE_FEE_PERMISSION . "'");
	 
	 //BULK SMS OBJECT PERMISSIONS
	 define("HAS_READ_BULK_SMS_PERMISSION", $_SESSION['HAS_READ_BULK_SMS_PERMISSION']);
	 define("HAS_CREATE_BULK_SMS_PERMISSION", $_SESSION['HAS_CREATE_BULK_SMS_PERMISSION']);
	 define("HAS_UPDATE_BULK_SMS_PERMISSION", $_SESSION['HAS_UPDATE_BULK_SMS_PERMISSION']);
	 define("HAS_DELETE_BULK_SMS_PERMISSION", $_SESSION['HAS_DELETE_BULK_SMS_PERMISSION']);
	 
	 define("ALL_BULK_SMS_PERMISSIONS", "'" . READ_BULK_SMS_PERMISSION . "','" . CREATE_BULK_SMS_PERMISSION . "','" . UPDATE_BULK_SMS_PERMISSION . "','" . DELETE_BULK_SMS_PERMISSION . "'");
	 
	 //MPESA TRANS OBJECT PERMISSIONS
	 define("HAS_READ_MPESA_TRANS_PERMISSION", $_SESSION['HAS_READ_MPESA_TRANS_PERMISSION']);
	 define("HAS_CREATE_MPESA_TRANS_PERMISSION", $_SESSION['HAS_CREATE_MPESA_TRANS_PERMISSION']);
	 define("HAS_UPDATE_MPESA_TRANS_PERMISSION", $_SESSION['HAS_UPDATE_MPESA_TRANS_PERMISSION']);
	 define("HAS_DELETE_MPESA_TRANS_PERMISSION", $_SESSION['HAS_DELETE_MPESA_TRANS_PERMISSION']);
	 
	 define("ALL_MPESA_TRANS_PERMISSIONS", "'" . READ_MPESA_TRANS_PERMISSION . "','" . CREATE_MPESA_TRANS_PERMISSION . "','" . UPDATE_MPESA_TRANS_PERMISSION . "','" . DELETE_MPESA_TRANS_PERMISSION . "'");
	
	/******************************** END OBJECT PERMISSIONS ***********************************************/
	
	
	//DEFINE STATUSES
	/*define("ACTIVE_STATUS", 1);
	define("DISABLED_STATUS", 2);
	define("SUSPENDED_STATUS", 3)*/;
	define("ACTIVE_STATUS", 1);
	define("INACTIVE_STATUS", 2);
	define("SUSPENDED_STATUS", 3);
	define("EXPIRED_STATUS", 4);
	define("COMPLETED_STATUS", 5);
	define("PENDING_STATUS", 6);
	define("AWAITING_DELIVERY_STATUS", 7);
	define("CONFIRMED_STATUS", 8);
	define("NOT_CONFIRMED_STATUS", 9);
	define("NOT_ACTIVATED_STATUS", 99);
	define("DELETED_STATUS", 98);
	define("PAID_STATUS", 10);
	define("NOT_PAID_STATUS", 11);
	//add parent acount statuses
	define("ACCOUNT_EXISTS_STATUS", 12);
	define("REQUEST_SENT_STATUS", 13);
	define("NOT_SUBSCRIBED_STATUS", 14);
	define("SUBSCRIBED_STATUS", 18);
	//sms status
	define("SENT_STATUS", 15);
	define("DELIVERED_STATUS", 16);
	define("NOT_DELIVERED_STATUS", 17);
	
	define("ACTIVE_TEXT", "Active");
	define("INACTIVE_TEXT", "Inactive Account");
	define("EXPIRED_TEXT", "Expired");
	define("SUSPENDED_TEXT", "Suspended");
	define("COMPLETED_TEXT", "Completed");
	define("PENDING_TEXT", "Pending");
	define("CONFIRMED_TEXT", "Confirmed");
	define("NOT_CONFIRMED_TEXT", "Not Confirmed");
	define("AWAITING_DELIVERY_TEXT", "Awaiting Delivery");
	define("NOT_ACTIVATED_TEXT", "Not Activated");
	define("PAID_STATUS_TEXT", "Paid");
	define("NOT_PAID_STATUS_TEXT", "Not Paid");
	define("ACCOUNT_EXISTS_STATUS_TEXT", "Account Exists");
	define("REQUEST_SENT_STATUS_TEXT", "Request Sent");
	define("NOT_SUBSCRIBED_STATUS_TEXT", "Not Subscribed");
	define("SUBSCRIBED_STATUS_TEXT", "Subscribed");
	
	//SMS STATUS TEXT
	define("SENT_STATUS_TEXT", "Sent");
	define("DELIVERED_STATUS_TEXT", "Delivered");
	define("NOT_DELIVERED_STATUS_TEXT", "Not Delivered");
		
	//website configs
	//GET SITE SETTINGS INTO SINGLE ARRAY session if not present
	//if (!$_SESSION['SESS_SITE_SETTINGS']) {
	$_SESSION['SESS_SITE_SETTINGS'] = $db->getAllSiteSettings();
	//}
	define("SITE_SETTINGS", $_SESSION['SESS_SITE_SETTINGS']);

	$site_settings = json_decode(SITE_SETTINGS);
	//print_r($site_settings);
	//echo "co name: " . $site_settings->company_name_title;	

	//assign individual setting to constants
	define("NO_REPLY_EMAIL", $site_settings->no_reply_mail);
	define("CONTACT_EMAIL", $site_settings->contact_email);
	define("CONTACT_EMAIL_PLAIN", $site_settings->contact_email_plain);
	define("CONTACT_PHONE", $site_settings->contact_phone);
	define("CONTACT_PHONE_2", $site_settings->contact_phone_2);
	define("CONTACT_WEBSITE", $site_settings->contact_website);
	define("CONTACT_SKYPE", $site_settings->contact_skype);
	define("COMPANY_LOCATION", $site_settings->company_location);
	define("COMPANY_FOOTER_DESC", $site_settings->company_website_footer_desc);
	define("COMPANY_FULL_NAME_LTD", $site_settings->company_full_name_ltd);
	define("COMPANY_NAME", $site_settings->company_name_title);
	define("SEND_EMAIL_1", $site_settings->send_email_1);
	define("SEND_EMAIL_2", $site_settings->send_email_2);
	define("SEND_EMAIL_3", $site_settings->send_email_3);
	define("SEND_EMAIL_GROUP", $site_settings->send_email_group);
	define("WEBSITE_ADDRESS", $site_settings->company_website_address);
	define("WEBSITE_DESC", $site_settings->company_website_desc);
	define("WEBSITE_SHORT_ADDRESS", $site_settings->company_website_short_address);
	define("ACCOUNT_ACTIVATION_INSTRUCTIONS", $site_settings->account_activation_instructions);
	
	
	define("GOOGLE_MAPS_KEY", $site_settings->google_maps_key);
	define("WEBSITE_ADDRESS", $site_settings->company_website_address);
	define("WEBSITE_DESC", $site_settings->company_website_desc);
	define("WEBSITE_SHORT_ADDRESS", $site_settings->company_website_short_address);
	
	//FACEBOOK VARIABLES
	define("OG_SITE_NAME", $site_settings->company_website_address);
	define("OG_SITE_DESC", $site_settings->company_website_desc);
	define("FB_PAGE_ID", $site_settings->facebook_page_id);
	define("FB_PAGE_NAME", $site_settings->facebook_page_name);
	define("FB_APP_ID", $site_settings->facebook_app_id);
	define("FB_APP_SECRET", $site_settings->facebook_app_secret);
	define("FB_PAGE_URL", $site_settings->facebook_page_url);
	define("FB_PAGE_ADMIN_ID", $site_settings->site_fb_admin_id);
	//END FACEBOOK VARIABLES
	
	define("INSTAGRAM_PAGE_URL", $site_settings->instagram_page_url);
	
	//TWITTER VARIABLES
	define("TWITTER_ACCESS_TOKEN", $site_settings->twitter_access_token);
	define("TWITTER_ACCESS_TOKEN_SECRET", $site_settings->twitter_access_token_secret);
	define("TWITTER_CONSUMER_KEY", $site_settings->twitter_consumer_key);
	define("TWITTER_CONSUMER_SECRET", $site_settings->twitter_consumer_secret);
	define("TWITTER_PAGE_NAME", $site_settings->twitter_page_name);
	define("TWITTER_PAGE_URL", $site_settings->twitter_page_url);
	//END TWITTER VARIABLES
	
	//LINKEDIN VARIABLES
	define("LINKEDIN_PAGE_NAME", $site_settings->linkedin_page_name);
	define("LINKEDIN_PAGE_URL", $site_settings->linkedin_page_url);
	//END LINKEDIN VARIABLES
	
	//RECAPTCHA VARIABLES
	define("RECAPTCHA_SITE_KEY", $site_settings->recaptcha_site_key);
	define("RECAPTCHA_SECRET_KEY", $site_settings->recaptcha_secret_key);
	define("RECAPTCHA_LANGUAGE", $site_settings->recaptcha_language);
	//END RECAPTCHA VARIABLES
	
	//BITLY VARIABLES
	define("BITLY_ACCESS_TOKEN", $site_settings->bitly_access_token);
	define("BITLY_DOMAIN", $site_settings->bitly_domain);
	define("BITLY_CLIENT_ID", $site_settings->bitly_client_id);
	define("BITLY_CLIENT_SECRET", $site_settings->bitly_client_secret);
	define("BITLY_LOGIN", $site_settings->bitly_login);
	define("BITLY_API_KEY", $site_settings->bitly_api_key);
	//END BITLY VARIABLES
	
	//MAIL MESSAGE VARIABLES
	define("MSG_TITLE_BG_COLOR", $site_settings->msg_title_bg_color);
	define("MSG_TITLE_FONT_COLOR", $site_settings->msg_title_font_color);
	define("MSG_BG_COLOR", $site_settings->msg_bg_color);
	//END MAIL MESSAGE VARIABLES
	
	//GOOGLE VRIABLES
	define('GOOGLE_LOGIN_CLIENT_ID', $site_settings->google_login_client_id);
	define('GOOGLE_LOGIN_CLIENT_SECRET', $site_settings->google_login_client_secret);
	define('GOOGLE_LOGIN_REDIRECT_URL', $site_settings->google_login_redirect_url);
	define('GOOGLE_LOGIN_APPLICATION_NAME', $site_settings->google_login_application_name);
	define("YOUTUBE_PAGE_URL", $site_settings->youtube_page_url);
	define("GOOGLE_PAGE_URL", $site_settings->google_page_url);
	define('GOOGLE_MAPS_KEY', $site_settings->google_maps_key);
	define('COMPANY_OFFICE_1_LATITUDE', $site_settings->company_office_1_latitude);
	define('COMPANY_OFFICE_1_LONGITUDE', $site_settings->company_office_1_longitude);
	//END GOOGLE VRIABLES
	
	//TIMEZONE
	//define('TIMEZONE', $site_settings->default_timezone); //default timezone
	
	//DEFAULT LANGUAGE
	define('LANG_ID', $site_settings->default_lang_id); //english
	define('LANG_NAME', $site_settings->default_lang_name); 
	
	//PAYPAL VARIABLES
	define('DEFAULT_CURRENCY', $site_settings->default_currency);
	//define('PAYPAL_CLIENT_ID', $site_settings->paypal_client_id); // Paypal client id
	//define('PAYPAL_SECRET', $site_settings->paypal_client_secret); // Paypal secret
 	//END PAYPAL VARIABLES
	
	define("GOOGLE_API_KEY", $site_settings->google_api_key);
	
	define("IPINFODB_API_KEY", $site_settings->ipinfodb_api_key);
	
	define('PAYPAL_CLIENT_ID', "AaOmefrf1fQnKhQ7Gcs2zxsGc1HL1BBdT-OkYpCcFe4Q8YyABFSievdgPFdpn1om9WeBxVwZCVftwTPt");
	define('PAYPAL_SECRET', "ENzkkToYKFwInrAT-67up4018F53A01-88mSxhtxO_Lk9EGK-3vYJNxHCKe7oIIPQas11rnY8w2_V2I7");
	
	
	define('COMPANY_APP_TITLE', "PendoSchools");
	
	function getAllSiteSettings()
	{
		
		$settings = array();
		$qry = "SELECT name, text FROM pt_site_settings ORDER BY name";
		$result = mysql_query($qry);
		//loop thru the results and display
		while ($row = mysql_fetch_assoc($result))
		{
			$field = $row["name"];
			$settings["$field"] = $row["text"];
		}
		$response = json_encode($settings);
		return $response;
		
	}
	
	$profile_image = $db->getPhoto(USER_PROFILE_PHOTO, USER_ID, THUMB_IMAGE);
	if ($sch_id) { $school_image = $db->getPhoto(SCHOOL_PROFILE_PHOTO, $sch_id, THUMB_IMAGE); }
	//$this_page_link = getTheCurrentUrl();
	
	
?>