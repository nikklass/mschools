<?php 
	include_once("api/includes/DB_handler.php"); 
	include_once("includes/funcs.php"); 
	include_once("api/includes/Config.php");
	
	if (!isset($_SESSION)) session_start();
	
	$admin = true;
	$show_bootstrap_dialog = true;
	$form_validation = true; //form validation classes
	$show_lightbox = true;
	$show_scroller = true;
	$show_form = true;
	$show_popup = true; // show colorbox
	$show_table = true; // will show bootgrid table
	
	//bulk sms
	$show_bulk_sms = true;
	
	//show incoming sms
	$show_sms_inbox = true;
	
	//show outgoing sms
	$show_sms_outbox = true;
	
	//show sms balance
	$show_sms_balance = true;
	$show_sms_textbox = true;
	
	//display selected items count
	$show_selected_items = true;
	
	//first bootgrid should be multiple
	$show_bootgrid_1_multiple = true;
	$show_bootgrid_2_multiple = false;
		
	$db = new DbHandler();
			
?>

<?php 
	//if user has read permissions
	if (!(HAS_CREATE_BULK_SMS_PERMISSION || HAS_UPDATE_BULK_SMS_PERMISSION || SCHOOL_ADMIN_USER)) 
	{
		//user is not allowed to access page
		$page = LOGIN_URL;
		header("Location: $page"); 
		exit();
	}
	
	if ($_GET["sch_id"] && SUPER_ADMIN_USER) {
		
		$sch_id = $_GET["sch_id"];
		$sch_name = $db->getSchoolName($sch_id);
		
	} else {
		
		//get the school ids
		$query = "SELECT sch_name, sch_id FROM sch_ussd WHERE status =  " . ACTIVE_STATUS;
		if (SCHOOL_ADMIN_USER) { $query .= " AND sch_id IN (" . USER_SCHOOL_IDS . ") "; }
		$query .= " ORDER BY sch_name LIMIT 0,1"; //echo $query; //exit
		//$query .= " WHERE sch_id = ? ";
		$stmt = $db->conn->prepare($query);
		//$stmt->bind_param("i", $sch_name, $sch_id);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($sch_name, $sch_id);
		$stmt->fetch();
	
	}
	
	$top_sch_name = $sch_name;
	
	$page_title = "Manage Bulk SMS - " . $top_sch_name;
	
?>

<?php 
	
	$hide_admin_css = "";
	
	//if user is not super admin, css to hide admin dropdown (school select)
	if (!SUPER_ADMIN_USER){
		$hide_admin_css = "hidden";
	}
	
?>

<!DOCTYPE html>
<html class="st-layout ls-top-navbar-large ls-bottom-footer show-sidebar sidebar-l3" lang="en">

<head>
    
	<?php include_once("includes/head_scripts.php"); ?>
                
    <title><?=$page_title?> :: <?=$page_titles?></title>

</head>

<body>

    <!-- Wrapper required for sidebar transitions -->
    <div class="st-container">

            
        <?php include_once("includes/nav.php"); ?>
        
            
        <?php include_once("includes/left_sidebar1.php"); ?>
            

        <!-- sidebar effects OUTSIDE of st-pusher: -->
        <!-- st-effect-1, st-effect-2, st-effect-4, st-effect-5, st-effect-9, st-effect-10, st-effect-11, st-effect-12, st-effect-13 -->

        <!-- content push wrapper -->
        <div class="st-pusher" id="content">

            <!-- sidebar effects INSIDE of st-pusher: -->
            <!-- st-effect-3, st-effect-6, st-effect-7, st-effect-8, st-effect-14 -->

            <!-- this is the wrapper for the content -->
            <div class="st-content">

                <!-- extra div for emulating position:fixed of the menu -->
                <div class="st-content-inner padding-none">

                    <div class="container-fluid">

                        <?php include_once("includes/bread_crumb.php"); ?>
                        
                        <div class="col-sm-12 margin-top-20 <?=$hide_admin_css?>" id="select-school">
                        	
                            <div id="top_school_id" data-sch-id="<?=$sch_id?>"></div>
                                                        
                            <div class="form-group">
                                <form>
                                
                                        <select id="school-select" name="sch_id" class="form-control" data-parsley-trigger="change" data-parsley-required required>
                                                                                    
                                        <?php
                                                
                                            //get the user types
                                            $query = "SELECT sch_id, sch_name FROM sch_ussd WHERE status =  " . ACTIVE_STATUS;
											if (LOGGED_IN_USER_GROUP_ID == SCHOOL_ADMIN_USER_ID) { $query .= " AND sch_id IN (" . USER_SCHOOL_IDS . ") "; }
											$query .= " ORDER BY sch_name"; echo $query; //exit
                                            $stmt = $db->conn->prepare($query);
                                            $stmt->execute();
                                            /* bind result variables */
                                            $stmt->bind_result($id, $name);
                                            
                                            while ($stmt->fetch()) 
                                            {
                                      
                                                echo "<option value='$id' ";
                                                
                                                if ($sch_id == $id) { echo " selected "; } 
                                                
                                                echo ">$name</option>";
                                        
                                             } 
                                        
                                        ?>
                                        
                                    </select>
                                
                                </form>
                            </div>
                            
                        </div>

                        
                        <div class=" col-md-12">
                        
                        	<!-- Tabbable Widget -->
                            <div class="tabbable paper-shadow relative" data-z="0.5">
                            
                                <!-- Tabs -->
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#main" data-toggle="tab"><i class="fa fa-fw fa-bars"></i> <span class="hidden-sm hidden-xs">Send Bulk SMS</span></a></li>
                                    <li><a href="#inbox" data-toggle="tab"><i class="fa fa-fw fa-bars"></i> <span class="hidden-sm hidden-xs">SMS Inbox</span></a></li>
                                    <li><a href="#sent_sms" data-toggle="tab"><i class="fa fa-fw fa-bars"></i> <span class="hidden-sm hidden-xs">SMS Outbox</span></a></li>
                                </ul>
                                <!-- // END Tabs -->
                            
                            
                                <!-- Panes -->
                                <div class="tab-content">
                                                        

                                    <div id="main" class="tab-pane active">
                                    	                                        
                                        <div class="col-sm-7">
                                        
                                            <div class="panel panel-default paper-shadow panel-grey" data-z="0.5">
                                            	
                                                <div class="margin-20">
                                                    
                                            		<form class="form-horizontal form-bulk-sms-query inputform">
                                                    
                                                        <div class="row">
                                                            <div class="col-sm-4 text-right">
                                                                <strong class="themiddle padding-top-8"></strong>
                                                            </div>
                                                            
                                                            <div class="col-sm-8">
                                                                <div class="checkbox checkbox-info">
                                                                    <input type="checkbox" id="enter_phone_numbers" name="enter_phone_numbers"/>
                                                                    <label for="enter_phone_numbers">Enter Phone Numbers</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <input type="hidden" name="selected_student_ids" id="selected_student_ids" value="">
                                                        <input type="hidden" name="user_id" value="<?=USER_ID?>">
                                                        <input type="hidden" id="admin" name="admin" value="1">
                                                        
                                                        <hr>
                                                        
                                                        <div id="sms-query">
                                                                                                            
                                                            <!--<div class="form-group">
                                                                <div class="row">
                                                                    <label for="student_id" class="col-sm-3 control-label">Student(s)</label>
                                                                    <div class="col-sm-9">
                                                                        
                                                                        <select class="multiselect_box form-control" id="student_id" name="multiselect[]" multiple="multiple">
                                                                      
                                                                            <?php
                                                                                    
                                                                                /*$items = $db->getStudentGridListing($sch_id, "","", "", "", "", 1, USER_ID, 1);
                                                                                //print_r($items); exit;
                                                                                                                                        
                                                                                foreach ($items["rows"] as $key => $val) {
                                                                                    $id = $val['id'];
                                                                                    $name = $val['name'];
                                                                                    $reg_no = $val['reg_no'];
                                                                                    echo "<option value='$id'>$name - $reg_no</option>";
                                                                                }*/
                                                                            
                                                                            ?>
                                                                            
                                                                        </select>
                
                                                                    </div>
                                                                </div>
                                                            </div>-->
                                                           
                                                             
                                                            <div class="form-group">
                                                            
                                                                <div class="row">
                                                                    <label for="current_class" class="col-sm-3 control-label text-right">Class</label>
                                                                    <div class="col-sm-3">
                                                                        <select id="current_class" name="current_class" class="form-control selectpickerz">
                                                                            
                                                                            <option value="">All</option>
                                                                            
                                                                            <?php
                                                                                        
                                                                                $items = $db->getClassGridListing($sch_id, "","", "", "", "", 1, USER_ID, 1);
                                                                                //print_r($items); exit;
                                                                                                                                        
                                                                                foreach ($items["rows"] as $key => $val) {
                                                                                    $current_class = $val['current_class'];
                                                                                    $name = $val['name'];
                                                                                    $reg_no = $val['reg_no'];
                                                                                    echo "<option value='$current_class'>$current_class</option>";
                                                                                }
                                                                            
                                                                            ?>
                                                                                                                                                
                                                                        </select>
                                                                    </div>
                                                                    
                                                                    <label for="stream" class="col-sm-3 control-label text-right">Stream</label>
                                                                    <div class="col-sm-3">
                                                                        <select id="stream" name="stream" class="form-control selectpickerz">
                                                                            
                                                                            <option value="">All</option>
                                                                            
                                                                            <?php
                                                                                        
                                                                                $items = $db->getStreamGridListing($sch_id, "","", "", "", "", 1, USER_ID, 1);
                                                                                //print_r($items); exit;
                                                                                                                                        
                                                                                foreach ($items["rows"] as $key => $val) {
                                                                                    $stream = $val['stream'];
                                                                                    $name = $val['name'];
                                                                                    $reg_no = $val['reg_no'];
                                                                                    echo "<option value='$stream'>$stream</option>";
                                                                                }
                                                                            
                                                                            ?>
                                                                                                                                                 
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            
                                                            </div>
                                                        
                                                        </div>
                                                                                                            
                                                        <div id="enter_numbers_div" class="hidden">
                                            
                                                            <div class="form-group animated" id="enter_contacts">
                                                                <div class="row col-data">
                                                                    <div class="col-sm-12">
                                                                        <label for="enter_contacts_field" class="control-label">Enter Phone Numbers (Separated  by a comma)</label>
                                                                    </div>
                                                                </div>   
                                                                <div class="row col-data">
                                                                    <div class="col-sm-12">
                                                                        <textarea class="form-control" name="enter_contacts_field" id="enter_contacts_field" rows="4"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        
                                                        </div>
                                                    
                                                    </form>
                                                
                                                </div>
                                                            
                                            </div>
                                            
                                            <div class="clearfix"></div>
                                            
                                            <div class="panel panel-default paper-shadow" data-z="0.5" id="contactsHeight2">
                                            
                                                <div>
                                                
                                                    <div class="table-responsive" id="table-responsive" data-tbl="sch_students" data-tbl-pk="id">
                                    
                                                        <table class="table table-condensed table-hover text-subhead v-middle" id="mybootgrid">
                                                            <thead>
                                                                <tr>
                                                                    <th data-column-id="id" data-type="numeric" data-identifier="true" data-visible="false" data-sortable="true">ID</th>  
                                                                    <th data-column-id="reg_no" data-sortable="true">Reg</th>
                                                                    <th data-column-id="name" data-sortable="true">Student Names</th>
                                                                    <th data-column-id="current_class" data-sortable="true">Class</th>
                                                                    <th data-column-id="stream" data-sortable="true">Stream</th>
                                                                    <th data-column-id="guardian_name" data-sortable="true">Guardian Names</th>
                                                                    <th data-column-id="guardian_phone" data-sortable="true">Phone</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody></tbody>
                                                        </table>
                                                        
                                                    </div>
                                                
                                                </div>
                                                            
                                            </div>
                                            
                                            <div class="clearfix"></div>
                                        
                                        </div>
                                        
                                        <div class="panel panel-default paper-shadow col-sm-5 panel-blue relative" data-z="0.5">
                                        
                                            <div class="overlay-div hidden">
                                            
                                            	<div class="no-results vertical-align">
                                                    <div class="panel-body">
                                                        Bulk SMS Not Available.<br>Please Contact Pendo Schools Admin
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            
                                            <div class="panel-spacing">
                                                                                            
                                                <form class="form-horizontal form-send-bulk-sms">
                                                    
                                                    <div id="wrapper_form">
                                                                                                         
                                                        <div class="row bg-blue">
                                                                <h4 class="padding-left-10 text-blue">Users/ Numbers To Send Message To</h4>
                                                        </div>
    
                                                        <br> 
                                                        
                                                        <div class="form-group">
                                                            
                                                            <div class="row col-data">
                                                                <div class="col-sm-6">
                                                                     <div class="text-left padding-top-10 msg_text disabled_div" id="numContacts">
                                                                         Selected Contacts: &nbsp; <span id="users_selected" class="bold_text text-success text-big">0</span>
                                                                     </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                     <div class="text-left padding-top-10 msg_text">
                                                                        SMS Balance: &nbsp; <span id="bulk_sms_balance" class="bold_text text-success text-big">0</span> 
                                                                     </div>
                                                                </div>
                                                                
                                                            </div>
                                                            
                                                        </div>
                                                                                                                                                                
                                                        <hr class="blue"> 
                                                                                                           
                                                        
                                                        
                                                        <div class="row bg-blue">
                                                            <h4 class="padding-left-10 text-blue" id="messageTypeTitle">Select Type of Message To Send</h4>
                                                        </div>
                                                        
                                                        <!--<div class="row">
                                                            <hr class="smaller">
                                                        </div>-->
                                                        
                                                        <br>
                                                            
                                                        <div class="form-group" id="messageTypeRadios">
                                                        
                                                            <div class="row col-data">
                                                                
                                                                <div class="col-sm-4">
                                                                    <div class="radio radio-primary">
                                                                        <input id="memo" name="messageType" value="memo" checked="checked" type="radio">
                                                                        <label for="memo"> Memo </label>
                                                                    </div>
                                                                 </div>
                                                                 
                                                                 <div class="col-sm-4">
                                                                    <div class="radio radio-primary">
                                                                        <input id="results" name="messageType" value="results" type="radio">
                                                                        <label for="results"> Results </label>
                                                                    </div>
                                                                 </div>
                                                                 
                                                                 <div class="col-sm-4">
                                                                    <div class="radio radio-primary">
                                                                        <input id="fees"  name="messageType" value="fees" type="radio">
                                                                        <label for="fees"> Fees </label>
                                                                    </div>
                                                                 </div>
                                                                
                                                                </div>
                                                                
                                                            <hr class="blue smaller">
                                                            
                                                        </div>                                                        
                                                        
                                                        <div id="bulk-sms-form">
                                                        
                                                            <input type="hidden" name="sch_id" value="<?=$sch_id?>">
                                                            <input type="hidden" name="selected" id="selected">
                                                                    
                                                            <div class="form-group" id="memo-fields">
                                                                <div class="row col-data">
                                                                    <div class="col-sm-12">
                                                                        <label for="message" class="control-label">Message</label>
                                                                    </div>
                                                                </div>   
                                                                <div class="row col-data">
                                                                    <div class="col-sm-12">
                                                                        <textarea class="form-control" name="message" id="sms_message" rows="5"><?=$message?></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="row col-data">
                                                                    <div class=" padding-no-top-20 msg_text">
                                                                        <span class="float-left"><strong id="remaining">160</strong> characters remaining</span>&nbsp;&nbsp;&nbsp;&nbsp; 
                                                                        <span class="float-right"><strong id="messages">1</strong> message(s)</span>
                                                                        <div class="clearfix"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div id="results-fields" class="hidden padding-top-20">
                                                          
                                                                <div class="form-group">
                                                                    
                                                                    <label for="results_year" class="col-sm-3 control-label">Year</label>
                                                                    <div class="col-sm-9">
                                                                        <select id="results_year" name="results_year" class="form-control selectpicker text-center" data-parsley-trigger="change" required>
                                                                           <?php
                                                                                    
                                                                                $years = array();
                                                                                $years = $db->getYearData();
                                                                                
                                                                                foreach ($years as $i => $row)
                                                                                {
                                                                                    echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                                                                                }
                                                                            
                                                                            ?>                                                                    
                                                                        </select>
                                                                    </div>
                                                                    
                                                                </div>
                                                                
                                                                <div class="form-group">
                                                                
                                                                    <label for="term" class="col-sm-3 control-label">Term/ Sem</label>
                                                                    <div class="col-sm-9">
                                                                        <select id="term" name="term" class="form-control selectpicker text-center" data-parsley-trigger="change" required>
                                                                                
                                                                            <option value="1">1</option>
                                                                            <option value="2">2</option>
                                                                            <option value="3">3</option>
                                                                            
                                                                        </select>
                                                                    </div>
                                                                
                                                                </div>
                                                                
                                                            </div>
                                                            
                                                            <div id="fees-fields" class="hidden padding-top-20">
                                                          
                                                                <div class="form-group">
                                                                    
                                                                    <label for="fees_year" class="col-sm-3 control-label">Year</label>
                                                                    <div class="col-sm-9">
                                                                        <select id="fees_year" name="fees_year" class="form-control selectpicker text-center">
                                                                           <?php
                                                                                    
                                                                                $years = array();
                                                                                $years = $db->getYearData();
                                                                                
                                                                                foreach ($years as $i => $row)
                                                                                {
                                                                                    echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                                                                                }
                                                                            
                                                                            ?>
                                                                                                                                               
                                                                        </select>
                                                                    </div>
                                                                    
                                                                </div>
                                                                
                                                            </div>
                                                        
                                                        </div>
                                                    
                                                        <div class="form-group">
                                                            
                                                            <div class="row col-data">
                                                            
                                                                <div class="col-sm-12">
                                                                <button class="btn btn-lg btn-primary btn-block">Send Message</button>
                                                                </div>
                                                            </div>
                                                            
                                                        </div>
                                                        
                                                        <!--<div class="form-group">
                                                            
                                                            <div class="row col-data">
                                                            
                                                                <div class="col-sm-12">
                                                                <a class="btn btn-lg btn-primary btn-block" id="soundcloud_btn">Get Soundcloud</a>
                                                                </div>
                                                            </div>
                                                            
                                                        </div>-->
                                                    
                                                    </div>
                                                                                                                                
                                                                                               
                                                </form>
                                                
                                            </div>
                                        
                                        </div>
                                        
                                        <div class="clearfix"></div>
                                        
                                    </div>
                                    
                                    <div id="inbox" class="tab-pane">
                                    	                                        
                                        <div class="col-sm-7">
                                        
                                            <div class="panel panel-default paper-shadow" data-z="0.5" style="background:#fafafa;">
                                            	
                                                <div class="margin-20">
                                                    
                                            		<form class="form-horizontal inputform">
                                                    
                                                        <h4>Filter Inbox</h4>
                                                        
                                                        <hr>
                                                        
                                                        <input type="hidden" name="user_id" value="<?=USER_ID?>">
                                                        <input type="hidden" name="admin" value="1">
                                                                                                                
                                                        <div id="sms-query">
                                                          
                                                            <div class="form-group">
                                                            
                                                                <div class="row">
                                                                                                                                        
                                                                    <label for="start_date" class="col-sm-2 control-label">Start</label>
                                                                    <div class="col-sm-4">
                                                                        
                                                                        <div class="input-group date">
                                                                          <input type="text" readonly class="form-control datepicker" name="start_date" id="start_date">
                                                                          <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                                                        </div>
                                                                        <a href="#" id="clear_start_date" class="noclick hidden">Clear Date</a>
                                                                        
                                                                    </div>
                                                                    
                                                                    <label for="end_date" class="col-sm-2 control-label text-right">End</label>
                                                                    <div class="col-sm-4">
                                                                        
                                                                        <div class="input-group date">
                                                                          <input type="text" readonly class="form-control datepicker" name="end_date" id="end_date">
                                                                          <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                                                        </div>
                                                                        <a href="#" id="clear_end_date" class="noclick hidden">Clear Date</a>
                                                                        
                                                                    </div>
                                                                    
                                                                </div>
                                                            
                                                            </div>
                                                        
                                                        </div>
                                                                                                                                                                
                                                    </form>
                                                
                                                </div>
                                                            
                                            </div>
                                            
                                            <div class="clearfix"></div>
                                                                                        
                                            <div class="panel panel-default paper-shadow" data-z="0.5" id="contactsHeight2">
                                            
                                                <div>
                                                
                                                    <div class="table-responsive" data-tbl="sch_students" data-tbl-pk="id">
                                    
                                                        <table class="table table-condensed table-hover text-subhead v-middle" id="mybootgrid2">
                                                            <thead>
                                                                <tr>
                                                                    <th data-column-id="id" data-type="numeric" data-identifier="true" data-visible="false" data-sortable="true">ID</th>  
                                                                    <th data-column-id="name" data-sortable="true">Phone Number</th>
                                                                    <th data-column-id="msg_text_short" data-sortable="false">Message</th>
                                                                    <th data-column-id="que_date_fmt" data-sortable="true">Sent At</th>
                                                                    <th data-column-id="replied" data-sortable="false" data-visible="false">Replied</th>
                                                                    <th data-column-id="status" data-formatter="status-links" data-sortable="false" data-visible="false">Status</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody></tbody>
                                                        </table>
                                                        
                                                    </div>
                                                
                                                </div>
                                                            
                                            </div>
                                            
                                            <div class="clearfix"></div>
                                        
                                        </div>
                                        
                                        <div class="col-sm-5">
                                                                    
                                            <div class="panel panel-default paper-shadow padding-20" data-z="0.5">
                                            
                                                <div class="no-results2">Please Select Message to Begin</div>
                                                
                                                <div id="details" class="tab-pane active">
                                                                                    
                                                    <div class="item-details2 hidden">
                                                    
                                                       <form class="form-horizontal form-send-single-sms">
                                                                
                                                                <div class="row col-data">
                                                                    <div class="col-sm-6">
                                                                         <div class="text-left padding-top-10 msg_text">
                                                                         	<h4 class="page-inner-title">Message Details</h4>
                                                                         </div>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                         <div class="text-left padding-top-10 msg_text" style="padding-top: 10px;">
                                                                            SMS Balance: &nbsp; <span id="bulk_sms_balance2" class="bold_text text-success text-big">0</span> 
                                                                         </div>
                                                                    </div>
                                                                    
                                                                </div>
                                                        
                                                                
                                                                <hr>
                                                                
                                                                <div class="form-group">
                                                                    <label class="col-sm-3 control-label">Source:</label>
                                                                    <div class="col-sm-9">
                                                                        <div type="text" class="form-control-edit textbox-noborder" id="source_edit"></div>
                                                                        <input type="hidden" class="form-control" name="id" id="id_edit">
                                                                        <input type="hidden" class="form-control" name="phone_number" id="phone_number_edit" value="">
                                                                        <input type="hidden" name="user_id" value="<?=USER_ID?>">
                                                        				<input type="hidden" id="admin" name="admin" value="1">
                                                                        <input type="hidden" name="sch_id" id="sch_id" value="<?=$sch_id?>">
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="form-group">
                                                                    <label class="col-sm-3 control-label">Date:</label>
                                                                    <div class="col-sm-9">
                                                                        <div type="text" class="form-control-edit textbox-noborder" id="que_date_edit"></div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="form-group">
                                                                    <label class="col-sm-3 control-label">Message:</label>
                                                                    <div class="col-sm-9">
                                                                        <div type="text" class="form-control-edit textbox-noborder" id="message_edit"></div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <hr>
                                                                
                                                                <h4 class="page-inner-title">Reply To Message</h4>
                                                                
                                                                <hr>                                                                
                                                                
                                                                <div class="form-group">
                                                                    <div class="col-sm-12">
                                                                        <label for="message" class="control-label">Message</label>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <textarea class="form-control" name="message" id="sms_message2" rows="5"></textarea>
                                                                    </div>
                                                                    <div class="row col-data">
                                                                        <div class=" padding-no-top-20 msg_text">
                                                                            <span class="float-left"><strong id="remaining2">160</strong> characters remaining</span>&nbsp;&nbsp;&nbsp;&nbsp; 
                                                                            <span class="float-right"><strong id="messages2">1</strong> message(s)</span>
                                                                            <div class="clearfix"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                                                                               
                                                                <div class="form-group">
                                                                    
                                                                    <div class="col-sm-12">
                                                                    <button class="btn btn-lg btn-primary btn-block">Send Message</button>
                                                                    </div>
                                                                    
                                                                </div>
                                                                                                                                                                                                    
                                                        </form>
                                                                                                    
                                                    </div>
                                                    
                                                </div>
                                                                                            
                                            </div>
                                                                                                                                        
                                        </div>
                                        
                                        <div class="clearfix"></div>
                                        
                                    </div>
                                                                        
                                    <div id="sent_sms" class="tab-pane">
                            
                                        <div class="col-md-7">
                                                                                                
                                            <div class="panel panel-default paper-shadow panel-grey" data-z="0.5">
                                            	
                                                <div class="margin-20">
                                                    
                                            		<form class="form-horizontal inputform">
                                                    
                                                        <h4>Filter Outbox</h4>
                                                        
                                                        <hr>
                                                        
                                                        <input type="hidden" name="user_id" value="<?=USER_ID?>">
                                                        <input type="hidden" name="admin" value="1">
                                                                                                                
                                                        <div id="sms-query">
                                                          
                                                            <div class="form-group">
                                                            
                                                                <div class="row">
                                                                                                                                        
                                                                    <label for="start_date" class="col-sm-2 control-label">Start</label>
                                                                    <div class="col-sm-4">
                                                                        
                                                                        <div class="input-group date">
                                                                          <input type="text" readonly class="form-control datepicker" name="start_date" id="start_date_3">
                                                                          <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                                                        </div>
                                                                        <a href="#" id="clear_start_date_3" class="noclick hidden">Clear Date</a>
                                                                        
                                                                    </div>
                                                                    
                                                                    <label for="end_date" class="col-sm-2 control-label text-right">End</label>
                                                                    <div class="col-sm-4">
                                                                        
                                                                        <div class="input-group date">
                                                                          <input type="text" readonly class="form-control datepicker" name="end_date" id="end_date_3">
                                                                          <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                                                        </div>
                                                                        <a href="#" id="clear_end_date_3" class="noclick hidden">Clear Date</a>
                                                                        
                                                                    </div>
                                                                    
                                                                </div>
                                                            
                                                            </div>
                                                        
                                                        </div>
                                                                                                                                                                
                                                    </form>
                                                
                                                </div>
                                                            
                                            </div>
                                            
                                            <div class="clearfix"></div>
                                            
                                            <div class="panel panel-default paper-shadow contactsHeight2" data-z="0.5">
                                                
                                                <div class="table-responsive">

                                                    <table class="table table-condensed table-hover text-subhead v-middle" id="mybootgrid3">
                                                        <thead>
                                                            <tr>
                                                                <th data-column-id="id" data-type="numeric" data-identifier="true" data-sortable="true" data-visible="false">ID</th>  
                                                                <th data-column-id="phone_number" data-sortable="true">Phone Number</th>
                                                                <th data-column-id="msg_text_short" data-sortable="true">Message</th>
                                                                <th data-column-id="created_at" data-sortable="true">Sent At</th>
                                                                <th data-column-id="status_text" data-sortable="true" data-visible="false">Status</th>
                                                                
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                          
                                                        </tbody>
                                                    </table>
                                                    
                                                </div>
                                                
                                            </div>
                                                    
                                       </div>
                                       
                                       <div class="col-md-5">
                                       
                                           <div class="panel panel-default paper-shadow padding-20" data-z="0.5">
                                            
                                                <div class="no-results3">Please Select Message to Begin</div>
                                                                                                                                
                                                <div class="item-details3 hidden">
                                                                                                                    
                                                    <form class="form-horizontal">
                                                     
                                                        <div class="row col-data">
                                                            <div>
                                                                 <div class="text-left padding-top-10 msg_text">
                                                                    <h4 class="page-inner-title">Message Details</h4>
                                                                 </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <hr>
                                                        
                                                        <div class="form-group">
                                                            <label class="col-sm-4 control-label">Phone Number:</label>
                                                            <div class="col-sm-8">
                                                                <div type="text" class="form-control-edit textbox-noborder" id="source_edit_3"></div>
                                                                <input type="hidden" class="form-control" name="id" id="id_edit_3">
                                                                <input type="hidden" class="form-control" name="phone_number" id="phone_number_edit_3" value="">
                                                                <input type="hidden" name="user_id" value="<?=USER_ID?>">
                                                                <input type="hidden" id="admin" name="admin" value="1">
                                                                <input type="hidden" name="sch_id" id="sch_id" value="<?=$sch_id?>">
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="form-group">
                                                            <label class="col-sm-4 control-label">Date:</label>
                                                            <div class="col-sm-8">
                                                                <div type="text" class="form-control-edit textbox-noborder" id="que_date_edit_3"></div>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        
                                                        <div class="form-group">
                                                            <label class="col-sm-4 control-label">Message:</label>
                                                            <div class="col-sm-8">
                                                                <div type="text" class="form-control-edit textbox-noborder" id="message_edit_3"></div>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        
                                                        <div class="form-group">
                                                            <label class="col-sm-4 control-label">Status:</label>
                                                            <div class="col-sm-8">
                                                                <div type="text" class="form-control-edit textbox-noborder" id="status_edit_3"></div>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>                                                            

                                                    </form>
                                                    
                                                </div>
                                                
                                                <div class="clearfix"></div>
                                             
                                           </div>
                                       
                                       </div>
                                       
                                       <div class="clearfix"></div>
                                       
                                    </div>
                                                                        
                                    <div style='display:none'>
                                        
                                        <div id="sent_sms_results" class="padding-20">
                                        
                                        </div>
                                        
                                    </div>
                                    
                            		
                                    <div id="fees" class="tab-pane">
                                                                                
                                        <!-- insert single fee record -->
                                        <?php include_once("includes/insert_single_fee.php"); ?>
                                        
                                    </div>
                                    
                                    
                                    
                                    
                            
                                </div>
                                
                                <!-- // END Panes -->
                            
                            </div>
                            <!-- // END Tabbable Widget -->


                        </div>
                        

                    </div>

                </div>
                <!-- /st-content-inner -->

            </div>
            <!-- /st-content -->

        </div>
        <!-- /st-pusher -->

        <!-- Footer -->
        <footer class="footer">
            
            <?php include_once("includes/footer.php"); ?>
            
        </footer>
        <!-- // Footer -->

    </div>
    <!-- /st-container -->

    
    <?php include_once("includes/js.php"); ?>
    

</body>

</html>