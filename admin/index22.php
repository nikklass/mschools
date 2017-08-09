<?php
	if (!isset($_SESSION)) session_start();
    error_reporting(0);
    //split the url generated by htaccess
	$self_url = $_GET['x'];
	$self_url_split = explode("/",$self_url);
	if ($self_url_split[0]){ $arg_one = strtolower($self_url_split[0]); }
	if ($self_url_split[1]){ $arg_two = strtolower($self_url_split[1]); }
	if ($self_url_split[2]){ $arg_three = strtolower($self_url_split[2]); }
	  
	//load appropriate file based on url length
	if ($arg_one=='') {
		include_once("home.php");
	} else if (($arg_one=='login') && (!$arg_two)){
        include_once("../login.php");
    } else if (($arg_one=='forgotpass') && (!$arg_two)){
        include_once("forgotpass.php");
    } else if (($arg_one=='register') && (!$arg_two)){
        include_once("register.php");
    } else if (($arg_one=='messages') && (!$arg_two)){
        include_once("messages.php");
    } else if (($arg_one=='new-user') && (!$arg_two)){
        include_once("create_user.php");
    } else if (($arg_one=='manage-users') && (!$arg_two)){
        include_once("view_users.php");
    } else if (($arg_one=='manage-users') && ($arg_two)){
        include_once("edit_user.php");
    } else if (($arg_one=='new-school') && (!$arg_two)){
        include_once("new_school.php");
    } else if (($arg_one=='new-fees') && (!$arg_two)){
        include_once("new_fee.php");
    } else if (($arg_one=='new-results') && (!$arg_two)){
        include_once("new_results.php");
    } else if (($arg_one=='new-student') && (!$arg_two)){
        include_once("new_student.php");
    } else if (($arg_one=='manage-students') && (!$arg_two)){
        include_once("view_students.php");
    } else if (($arg_one=='manage-bulk-sms') && (!$arg_two)){
        include_once("view_bulk_sms.php");
    } else if (($arg_one=='manage-mpesa') && (!$arg_two)){
        include_once("view_mpesa.php");
    } else if (($arg_one=='manage-subjects') && (!$arg_two)){
        include_once("view_subjects.php");
    } else if (($arg_one=='manage-score-grades') && (!$arg_two)){
        include_once("view_grading.php");
    } else if (($arg_one=='manage-total-points-grades') && (!$arg_two)){
        include_once("view_total_grading.php");
    } else if (($arg_one=='manage-students') && ($arg_two)){
        include_once("edit_student.php");
    } else if (($arg_one=='edit-result') && ($arg_two)){
        include_once("edit_result.php");
    } else if ($arg_one=='manage-permissions'){
        include_once("view_permissions.php");
    } else if (($arg_one=='manage-schools') && (!$arg_two)){
        include_once("view_schools.php");
    } else if (($arg_one=='manage-school')){
        include_once("view_single_school.php");
    } else if (($arg_one=='manage-fees') && (!$arg_two)){
        include_once("view_fees.php");
    } else if (($arg_one=='manage-results') && (!$arg_two)){
        include_once("view_results.php");
    } else if (($arg_one=='manage-fees') && ($arg_two)){
        include_once("edit_fee.php");
    } else if (($arg_one=='edit-activity') && (!$arg_two)){
        include_once("view_schools.php");
    } else if (($arg_one=='edit-activity') && ($arg_two)){
        include_once("edit_activity.php");
    } else if (($arg_one=='student-results')){
        include_once("student_results.php");
    } else if (($arg_one=='student-fees')){
        include_once("student_fees.php");
    } else if (($arg_one=='manage-parents')){
        include_once("view_parents.php");
    } else if ($arg_one=='account-activation'){
        include_once("account_activation.php");
    } else if ($arg_one=='my-profile'){
        include_once("my_profile.php");
    } else if ($arg_one=='my-subscriptions'){
        include_once("my_subscriptions.php");
    } else if ($arg_one=='error'){
        include_once("error_page.php");
    } else if ($arg_one=='fee-reports'){
        include_once("view_fee_reports.php");
    } else if ($arg_one=='result-reports'){
        include_once("view_fee_reports.php");
    } else if ($arg_one=='mpesa-reports'){
        include_once("view_fee_reports.php");
    } else if ($arg_one=='bulk-sms-reports'){
        include_once("view_fee_reports.php");
    } 
	 
?>