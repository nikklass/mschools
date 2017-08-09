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
	$show_students_list = true;
	$show_contacts_list = true;
	$show_bulk_sms = true;
		
	$db = new DbHandler();
	
	$page_title = "Bulk SMS";
		
?>

<?php 
	//if user has read permissions
	if (!(HAS_CREATE_BULK_SMS_PERMISSION || HAS_UPDATE_BULK_SMS_PERMISSION || SCHOOL_ADMIN_USER)) 
	{
		//user is not allowed to access page
		$page = SITEPATH."error";
		header("Location: $page"); 
		exit();
	}
	
	
	if ($_GET["sch_id"]) {
		
		$sch_id = $_GET["sch_id"];
		
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

                        <div class="page-section">
                            <div class="col-sm-6">
                            	<!--<h1 class="text-display-1"><?//=$page_title?></h1>-->
                            </div>
                            <div class="col-sm-6">
                            	
                                <?=BreadCrumb()?>
                                
                            </div>
                            <div class="clear"></div>
                        </div>
                        
                        <div class="col-sm-12" id="select-school">
                        	<div id="top_school_id" data-sch-id="<?=$sch_id?>"></div>
                            
                            <hr class="small">
                            
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
                                    <li class="active"><a href="#main" data-toggle="tab"><i class="fa fa-fw fa-lock"></i> <span class="hidden-sm hidden-xs">Send Bulk SMS</span></a></li>
                                    <li><a href="#photos" data-toggle="tab"><i class="fa fa-fw fa-credit-card"></i> <span class="hidden-sm hidden-xs">Inbox</span></a></li>
                                    <li><a href="#results" data-toggle="tab"><i class="fa fa-fw fa-credit-card"></i> <span class="hidden-sm hidden-xs">Parents</span></a></li>
                                    <li><a href="#fees" data-toggle="tab"><i class="fa fa-fw fa-credit-card"></i> <span class="hidden-sm hidden-xs">SMS Stats</span></a></li>
                                </ul>
                                <!-- // END Tabs -->
                            
                            
                                <!-- Panes -->
                                <div class="tab-content">
                                                        

                                    <div id="main" class="tab-pane active">
                                    	                                        
                                        <div class="col-sm-7">
                                        
                                            <div class="panel panel-default paper-shadow" data-z="0.5">
                                            
                                                <div id="contactsHeight2">
                                                
                                                    <div class="table-responsive" id="table-responsive" data-tbl="sch_students" data-tbl-pk="id">
                                    
                                                        <table class="table table-condensed table-hover text-subhead v-middle" id="mybootgrid">
                                                            <thead>
                                                                <tr>
                                                                    <th data-column-id="id" data-type="numeric" data-identifier="true" data-visible="false" data-sortable="true">ID</th>  
                                                                    <th data-column-id="reg_no" data-sortable="true">Reg</th>
                                                                    <th data-column-id="name" data-sortable="true">Student Names</th>
                                                                    <th data-column-id="current_class_desc" data-sortable="true">Class</th>
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
                                        
                                        <div class="col-sm-5" style="background:#e7f3fb; padding:20px 30px;">
                                        	
                                        	<form class="form-horizontal form-send-bulk-sms">
                                                
                                                <div id="wrapper_form">
                                                                                                     
                                                    <div class="row bg-blue">
                                                            <h4 class="padding-left-10 text-blue">Select Users/ Numbers To Send SMS To</h4>
                                                    </div>

                                                    <br> 
                                                    
                                                    <div class="form-group">
                                                        
                                                        <div class="row col-data">
                                                            <div class="col-sm-6">
                                                            	 <div class="text-left padding-top-10 msg_text disabled_div" id="numContacts">
                                                                 	<span id="users_selected" class="bold_text text-success">0</span> &nbsp;Contacts Selected
                                                                 </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                            	 <div class="text-left padding-top-10 msg_text">
                                                                 	SMS Balance: &nbsp; <span id="sms_balance" class="bold_text text-success">5100</span> 
                                                                 </div>
                                                            </div>
                                                            
                                                        </div>
                                                        
                                                    </div>
                                                                                                                                                            
                                                    <hr class="blue"> 
                                                                                                       
                                                    <div class="row">
                                                    	<div class="col-sm-4 text-right">
                                                        	<strong class="themiddle padding-top-8">OR</strong>
                                                        </div>
                                                        
                                                        <div class="col-sm-8">
                                                            <div class="checkbox checkbox-info">
                                                                <input type="checkbox" id="enter_phone_numbers" name="enter_phone_numbers"/>
                                                                <label for="enter_phone_numbers">Enter Phone Numbers</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <hr class="blue">
                                                    
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
                                                    
                                                    <div class="row bg-blue">
                                                        <h4 class="padding-left-10 text-blue">Select Type of Message To Send</h4>
                                                    </div>
                                                    
                                                    <!--<div class="row">
                                                    	<hr class="smaller">
                                                    </div>-->
                                                    
                                                    <br>
                                                        
                                                    <div class="form-group">
                                                    
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
                                                        
                                                    </div>
                                                                                                            
                                                    <hr class="blue smaller">
                                                    
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
                                                                <div class="text-right padding-no-top-20 msg_text">
                                                                    <span id="text_counter" class="bold_text"><?=MAX_CHAR_LENGTH?></span> &nbsp;Chars Remaining
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
                                                            <button class="btn btn-lg btn-primary btn-block">Send</button>
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
                                        
                                        <div class="clearfix"></div>
                                        
                                    </div>
                                    
                                    <div id="photos" class="tab-pane">
                                    
                                        <div class="col-sm-8">
                                        
                                            <div class="text-center">
                                            	<h3>Upload A Profile Photo</h3>
                                            </div>
                                            
                                            <!-- the avatar markup -->
                                            <div id="kv-avatar-errors-1" class="center-block" style="width:800px;display:none"></div>
                                            <form enctype="multipart/form-data" method="post" class="form-upload-user-pic">
                                                
                                                <div class="resultdiv"></div>
                                                
                                                <div class="wrapper_form">
                                                    <div class="form-group padding-20">
                                                        <div class="kv-avatar center-block" style="width:200px">
                                                            <input id="avatar-1" name="user_pic" type="file" class="file-loading">
                                                            <input type="hidden" name="student_id" value="<?=$student_id?>">
                                                            <input type="hidden" name="student_profile" value="1">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-sm-4 col-sm-offset-4">
                                                                <button class="btn btn-lg btn-primary btn-block">Submit</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            
                                            </form>
                                        
                                        </div>
                                        
                                        <div class="col-sm-4">
                                        	
                                            <div class="thumbnail">
                                                
                                                <?php
                                                	$img = $db->getPhoto(STUDENT_PROFILE_PHOTO, $student_id); // echo "img - $img";
                                                ?>
                                                <img src="<?=$img?>" width="400">
                                                
                                            </div>
                                            
                                        </div>
                                        
                                        <div class="clear"></div>
                                        
                                    </div>
                                    
                                    
                                    
                                    <div id="results" class="tab-pane">
                                                                                
                                        <form class="form-horizontal form-new-result inputform"  data-parsley-validate>
                                        
                                        <div class="col-sm-5">
                                        
                                            <div class="panel panel-default text-center login-form-bg" data-z="0.5">
                                                
                                                <h3 class="text-display-1 text-center margin-bottom-none">Select Period</h3>
                                                <hr>
                                                <div class="panel-body">
                                                    
                                                        <div class="form-group">
                                                        
                                                            <input type="hidden" name="student_id" value="<?=$student_id?>">
                                                            <input type="hidden" name="sch_id" value="<?=$sch_id?>">
                                                            <input type="hidden" name="reg_no" value="<?=$reg_no?>">
                                                            <input type="hidden" name="class" value="<?=$current_class?>">
                                                            
                                                            <label for="year" class="col-sm-3 control-label">Year</label>
                                                            <div class="col-sm-9">
                                                                <select id="year" name="year" class="form-control selectpickerz text-center" data-parsley-trigger="change" required>
                                                                        
                                                                    <?php
                                                                            
                                                                        $years = array();
																		$years = $db->getYearData();
																		
																		foreach ($years as $i => $row)
																		{
																			echo "id - " . $row['id'];
																			echo "name - " .$row['name'];
																			echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
																		}
                                                                    
                                                                    ?>
                                                                    
                                                                </select>
                                                            </div>
                                                            
                                                        </div>
                                                        
                                                        <div class="form-group">
                                                        
                                                            <label for="term" class="col-sm-3 control-label">Term/ Sem</label>
                                                            <div class="col-sm-9">
                                                                <select id="term" name="term" class="form-control selectpickerz text-center" data-parsley-trigger="change" required>
                                                                        
                                                                    <option value="1">1</option>
                                                                    <option value="2">2</option>
                                                                    <option value="3">3</option>
                                                                    
                                                                </select>
                                                            </div>
                                                        
                                                        </div>
                                                        
                                                    
                                                    
                                                </div>
                                                
                                            </div>
                                            
                                            <div class="panel panel-default text-center login-form-bg" data-z="0.5">
                                                
                                                <h3 class="text-display-1 text-center margin-bottom-none">Add Student Result</h3>
                                                <hr>
                                                <div class="panel-body">
                                                    
                                                        <div class="form-group">
                                                        
                                                            <div class="resultdiv"></div>
                                                        
                                                            <label for="subject" class="col-sm-3 control-label">Subject</label>
                                                            <div class="col-sm-9">
                                                                <select id="subject" name="subject" class="form-control selectpickerz text-center" data-parsley-trigger="change" required>
                                                                        
                                                                    <option value="">Select Subject</option>
                                                                    <?php
                                                                            
                                                                        //get the user types
                                                                        $queryTypes = "SELECT ss.code, ss.name FROM sch_subjects ss";
                                                                        $queryTypes .= " JOIN sch_ussd su ON su.sch_level = ss.school_level";
                                                                        $queryTypes .= " WHERE su.sch_id = $sch_id ORDER BY name";
                                                                        $stmtTypes = $db->conn->prepare($queryTypes);
                                                                        $stmtTypes->execute();
                                                                        /* bind result variables */
                                                                        $stmtTypes->bind_result($code, $name);
                                                                        
                                                                        while ($stmtTypes->fetch()) 
                                                                        {
                                                                  
                                                                            echo "<option value='$code'>$name</option>";
                                                                    
                                                                         } 
                                                                    
                                                                    ?>
                                                                    
                                                                </select>
                                                            </div>
                                                            
                                                        </div>
                                                        
                                                        <div class="form-group">
                                                        
                                                            <label for="score" class="col-sm-3 control-label">Score</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" class="form-control text-center numbersOnly" maxlength="3" name="score" required>
                                                            </div>
                                                        
                                                        </div>
                                                        
                                                        <div class="form-group">
                                                
                                                            <div class="col-sm-3"></div>
                                                            <div class="col-sm-9">
                                                            <button class="btn btn-info col-sm-12">Submit</button>
                                                            </div>
                                                            
                                                        </div>
                                                        
                                                    
                                                    
                                                </div>
                                                
                                            </div>

                                        </div>
                                        
                                        </form>
                                        
                                        <div class="col-sm-7">
                                            
                                            <div class="container-fluid">
                                                                    
                                                <div class="panel panel-default text-center login-form-bg" data-z="0.5">
                                                
                                                <h3 class="text-display-1 text-center margin-bottom-none">Results Summary</h3>
                                                <hr>
                                                <div class="panel-body">
                                                    
                                                       <div class="col-sm-6 large-text">
                                                       		
                                                            <div class="col-sm-3">
                                                            	Total:
                                                       		</div> 
                                                            <div class="col-sm-3 text-info" id="total_score"></div> 
                                                            
                                                            <div class="col-sm-3">
                                                            	Average:
                                                       		</div> 
                                                            <div class="col-sm-3 text-info" id="mean_score"></div> 
                                                            
                                                       </div>
                                                       
                                                       <div class="col-sm-6 large-text">
                                                       
                                                       		<div class="col-sm-3">
                                                            	Points:
                                                       		</div> 
                                                            <div class="col-sm-3 text-success" id="mean_points"></div> 
                                                            
                                                            <div class="col-sm-3">
                                                            	Grade:
                                                       		</div> 
                                                            <div class="col-sm-3 text-success" id="mean_grade"></div> 
                                                            
                                                       </div>
                                                    
                                                </div>
                                                
                                            </div>
                        
                                            </div>
                                            
                                            <div class="container-fluid">
                                                                    
                                                <div class="panel panel-default paper-shadow" data-z="0.5" id="papershadow">
                                                    
                                                    <div class="table-responsive" id="table-data" data-tbl="sch_results_items" data-tbl-pk="id">
                                                                                                             
                                                        <div class="div-table" id="results-list">
                                                
                                                        </div>
                                        
                                                    </div>
                                                
                                                </div>
                        
                                            </div>
                
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    
                                    
                                    <div style='display:none'>
                                        
                                        <form class="form-horizontal form-edit-result inputform" id="edit_record">
                                                                                                              
                                                <div class="form-group">
                                                
                                                    <div class="col-sm-3"></div>
                                                    <div class="col-sm-9">
                                                        <h3>Edit Result</h3>
                                                    </div>
                                                
                                                </div>
                                                                                            
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                    	<div class="resultdiv"></div>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="subject_name" class="col-sm-3 control-label">Subject</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control invisible-text-box" name="subject_name" id="subject_name">
                                                        <input type="hidden" name="result_item_id" id="result_item_id">
                                                        <input type="hidden" name="sch_id" id="sch_id">
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="score" class="col-sm-3 control-label">Score</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control numbersOnly" maxlength="3" name="score"  id="score">
                                                    </div>
                                                </div>
                                               
                                                <div class="form-group">
                                                
                                                    <div class="col-sm-3"></div>
                                                    <div class="col-sm-9">
                                                    <button type="submit" class="btn btn-info col-sm-12">Submit</button>
                                                    </div>
                                                    
                                                </div>
                                                                                                                                                        
                                        </form>
                                        
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