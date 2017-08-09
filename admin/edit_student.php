<?php 
	include_once("api/includes/DB_handler.php"); 
	include_once("includes/funcs.php"); 
	include_once("api/includes/Config.php");
	
	if (!isset($_SESSION)) session_start();
	
	$admin = true;
	$show_bootstrap_dialog = true;

	$form_validation = true; //form validation classes
	
	$show_form = true;
	
	$show_popup = true; // show colorbox
	
	$show_table = true; // will show bootgrid table
	$show_results_list = true; // will load activities into table
	$show_fees_list = true;
	
	$show_file_upload = true; //show file upload css/ js
		
	$db = new DbHandler();
		
	$student_id = $arg_two;
	
	//get the statuses
	//$query = "SELECT id, full_names, reg_no, guardian_name, sch_id, student_profile, mobile1, mobile2, guardian_name, guardian_phone, guardian_address FROM sch_students WHERE id = ? ";
	$query = "SELECT id, sch_id, full_names, reg_no, guardian_name, dob, admin_date, index_no, current_class, nationality, religion, previous_school, house";
	$query .= ", club, guardian_id_card, guardian_relation, guardian_occupation, email, town, village, county, location";
	$query .= ", disability, gender, stream, constituency, student_profile";
	$query .= ", guardian_address, guardian_phone FROM sch_students WHERE id = ? ";
	$stmt = $db->conn->prepare($query);
	$stmt->bind_param("i", $student_id);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($id, $sch_id, $full_names, $reg_no, $guardian_name, $dob, $admin_date, $index_no, $current_class, $nationality, $religion, $previous_school, $house, $club, $guardian_id_card, $guardian_relation, $guardian_occupation, $email, $town, $village, $county, $location, $disability, $gender, $stream, $constituency, $student_profile, $guardian_address, $guardian_phone);
	$stmt->fetch();
	if ($dob){ $dob = date("d/m/Y", $db->php_date($dob)); }
	if ($admin_date){ $admin_date = date("d/m/Y", $db->php_date($admin_date)); }
	
	$page_title = "Edit student - " . $full_names;
		
?>

<?php 
	//if user has read permissions
	if (!(HAS_CREATE_STUDENT_PERMISSION || HAS_EDIT_STUDENT_PERMISSION || SCHOOL_ADMIN_USER)) 
	{
		//user is not allowed to access page
		$page = SITEPATH."error";
		header("Location: $page"); 
		exit();
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
                            	<h1 class="text-display-1"><?=$page_title?></h1>
                            </div>
                            <div class="col-sm-6">
                            	
                                <?=BreadCrumb()?>
                                
                            </div>
                            <div class="clear"></div>
                        </div>

                        
                        <div class=" col-md-12">
                        
                        	<!-- Tabbable Widget -->
                            <div class="tabbable paper-shadow relative" data-z="0.5">
                            
                                <!-- Tabs -->
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#main" data-toggle="tab"><i class="fa fa-fw fa-lock"></i> <span class="hidden-sm hidden-xs">Student Details</span></a></li>
                                    <li><a href="#photos" data-toggle="tab"><i class="fa fa-fw fa-credit-card"></i> <span class="hidden-sm hidden-xs">Student Profile Photo</span></a></li>
                                    <li><a href="#results" data-toggle="tab"><i class="fa fa-fw fa-credit-card"></i> <span class="hidden-sm hidden-xs">Student Results</span></a></li>
                                    <li><a href="#fees" data-toggle="tab"><i class="fa fa-fw fa-credit-card"></i> <span class="hidden-sm hidden-xs">Student Fees</span></a></li>
                                </ul>
                                <!-- // END Tabs -->
                            
                            
                                <!-- Panes -->
                                <div class="tab-content">
                                                        

                                    <div id="main" class="tab-pane active">
                                        <form class="form-horizontal form-edit-student">
                                            
                                            <div id="tbl-settings" data-tbl="sch_students" data-pk="id" data-pkval="<?=$id?>"></div>
                                            
                                            <input type="hidden" name="<?= $token_id; ?>" value="<?= $token_value; ?>" />
                                            
                                            <div id="wrapper_form">
                                             
                                                <div class="row">
                                                	<div class="col-sm-6 col-sm-offset-3">
                                                    	<h4 class="text-info">Enter data and the system will automatically save</h4>
                                                    </div>
                                                </div>
                                                <hr>
                                                
                                                <div class="form-group">
                                                    <div class="col-sm-6 col-data">
                                                        <label for="full_names" class="col-sm-5 control-label">Student Names</label>
                                                        <div class="col-sm-6">
                                                            <input type="text" class="form-control input" name="full_names" data-tp="s" placeholder="Student Name" value="<?=$full_names?>">
                                                        </div>
                                                        <div class="col-sm-1 col-md-1 result"></div>
                                                    </div>
                                                    <div class="col-sm-6 col-data">
                                                       
                                                       <label for="county" class="col-sm-5 control-label">School</label>
                                                       <div class="col-sm-6">
                                                            <select id="select" name="sch_id" class="form-control input" data-tp="i">
                                                                
                                                                <option value=''>Please select</option>
                                                                
                                                                <?php
                                                                        
                                                                    //get the user types
                                                                    $queryTypes = "SELECT sch_id, sch_name FROM sch_ussd ORDER BY sch_name";
                                                                    $stmtTypes = $db->conn->prepare($queryTypes);
                                                                    $stmtTypes->execute();
                                                                    /* bind result variables */
                                                                    $stmtTypes->bind_result($id, $name);
                                                                    
                                                                    while ($stmtTypes->fetch()) 
                                                                    {
                                                              
                                                                        echo "<option value='$id' ";
                                                                        
                                                                        if ($sch_id == $id) { echo " selected "; } 
                                                                        
                                                                        echo ">$name</option>";
                                                                
                                                                     } 
                                                                
                                                                ?>
                                                                
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-1 result"></div>
                                                
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <div class="col-sm-6 col-data">
                                                        <label for="reg_no" class="col-sm-5 control-label">Reg No</label>
                                                        <div class="col-sm-6">
                                                            <input type="text" class="form-control input" name="reg_no" data-tp="s" placeholder="Reg No" value="<?=$reg_no?>">
                                                        </div>
                                                        <div class="col-sm-1 col-md-1 result"></div>
                                                    </div>
                                                    <div class="col-sm-6 col-data">
                                                         <label for="index_no" class="col-sm-5 control-label">Index No</label>
                                                        <div class="col-sm-6">
                                                            <input type="text" class="form-control input" name="index_no" data-tp="s" placeholder="Index No" value="<?=$index_no?>">
                                                        </div>
                                                        <div class="col-sm-1 result"></div>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <div class="col-sm-6 col-data">
                                                        <label for="dob" class="col-sm-5 control-label">Date of Birth</label>
                                                        <div class="col-sm-6">                                                            
                                                            <div class="input-group date">
                                                              <input type="text" readonly class="form-control datepicker input" name="dob" data-tp="d" value="<?=$dob?>">
                                                              <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-1 result"></div>
                                                    </div>
                                                    <div class="col-sm-6 col-data">
                                                        <label for="admin_date" class="col-sm-5 control-label">Admission Date</label>
                                                        <div class="col-sm-6">                                                            
                                                            <div class="input-group date">
                                                              <input type="text" readonly class="form-control datepicker input" name="admin_date" data-tp="d" value="<?=$admin_date?>">
                                                              <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-1 result"></div>
                                                    </div>
                                                </div>
                                                
                                                 <div class="form-group">
                                                    <div class="col-sm-6 col-data">
                                                        <label for="nationality" class="col-sm-5 control-label">Nationality</label>
                                                        <div class="col-sm-6">
                                                            <input type="text" class="form-control input" name="nationality" data-tp="s" value="<?=$nationality?>">
                                                        </div>
                                                        <div class="col-sm-1 result"></div>
                                                    </div>
                                                    <div class="col-sm-6 col-data">
                                                        <label for="religion" class="col-sm-5 control-label">Religion</label>
                                                        <div class="col-sm-6">
                                                            <input id="religion" type="text" class="form-control input" name="religion" data-tp="s" value="<?=$religion?>">
                                                        </div>
                                                        <div class="col-sm-1 col-md-1 result"></div>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <div class="col-sm-6 col-data">
                                                        <label for="current_class" class="col-sm-5 control-label">Current Class</label>
                                                        <div class="col-sm-6">
                                                            <input type="text" class="form-control input" name="current_class" data-tp="i" value="<?=$current_class?>">
                                                        </div>
                                                        <div class="col-sm-1 col-md-1 result"></div>
                                                    </div>
                                                    <div class="col-sm-6 col-data">
                                                        <label for="stream" class="col-sm-5 control-label">Stream</label>
                                                        <div class="col-sm-6">
                                                            <input type="text" class="form-control input" name="stream" data-tp="s" value="<?=$stream?>">
                                                        </div>
                                                        <div class="col-sm-1 col-md-1 result"></div>
                                                    </div>
                                                </div>
                                                
                                                
                                                <div class="form-group">
                                                    <div class="col-sm-6 col-data">
                                                        <label for="house" class="col-sm-5 control-label">House</label>
                                                        <div class="col-sm-6">
                                                            <input id="house" type="text" class="form-control input" name="house" data-tp="s" value="<?=$house?>">
                                                        </div>
                                                        <div class="col-sm-1 col-md-1 result"></div>
                                                    </div>
                                                    <div class="col-sm-6 col-data">
                                                         <label for="club" class="col-sm-5 control-label">Club</label>
                                                        <div class="col-sm-6">
                                                            <input type="text" class="form-control input" name="club" data-tp="s" value="<?=$club?>">
                                                        </div>
                                                        <div class="col-sm-1 col-md-1 result"></div>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <div class="col-sm-6 col-data">
                                                        <label for="disability" class="col-sm-5 control-label">Disability</label>
                                                        <div class="col-sm-6">
                                                            <input id="disability" type="text" class="form-control input" name="disability" data-tp="s" value="<?=$disability?>">
                                                        </div>
                                                        <div class="col-sm-1 col-md-1 result"></div>
                                                    </div>
                                                    <div class="col-sm-6 col-data">
                                                         <label for="gender" class="col-sm-5 control-label">Gender</label>
                                                        <div class="col-sm-6">
                                                            <select id="select" name="gender" class="form-control input" data-tp="s">
                                                               
                                                                <option value='Male' <?php if ($gender=='Male') { echo " selected"; } ?>>Male</option>
                                                                <option value='Female' <?php if ($gender=='Female') { echo " selected"; } ?>>Female</option>
                                                                
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-1 col-md-1 result"></div>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <div class="col-sm-6 col-data">
                                                         <label for="previous_school" class="col-sm-5 control-label">Previous School</label>
                                                        <div class="col-sm-6">
                                                            <input type="text" class="form-control input" name="previous_school" data-tp="s" value="<?=$previous_school?>">
                                                        </div>
                                                        <div class="col-sm-1 col-md-1 result"></div>
                                                    </div>
                                                    <div class="col-sm-6 col-data">
                                                        
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <div class="col-sm-6 col-data">
                                                        <label for="guardian_name" class="col-sm-5 control-label">Guardian Name</label>
                                                        <div class="col-sm-6">
                                                            <input id="guardian_name" type="text" class="form-control input" name="guardian_name" data-tp="s" value="<?=$guardian_name?>">
                                                        </div>
                                                        <div class="col-sm-1 col-md-1 result"></div>
                                                    </div>
                                                    <div class="col-sm-6 col-data">
                                                         <label for="guardian_address" class="col-sm-5 control-label">Guardian Address</label>
                                                        <div class="col-sm-6">
                                                            <input type="text" class="form-control input" name="guardian_address" data-tp="s" value="<?=$guardian_address?>">
                                                        </div>
                                                        <div class="col-sm-1 col-md-1 result"></div>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <div class="col-sm-6 col-data">
                                                        <label for="guardian_phone" class="col-sm-5 control-label">Guardian Phone</label>
                                                        <div class="col-sm-6">
                                                            <input id="guardian_phone" type="text" class="form-control input" name="guardian_phone" data-tp="s" value="<?=$guardian_phone?>">
                                                        </div>
                                                        <div class="col-sm-1 col-md-1 result"></div>
                                                    </div>
                                                    <div class="col-sm-6 col-data">
                                                        <label for="guardian_occupation" class="col-sm-5 control-label">Guardian Occupation</label>
                                                        <div class="col-sm-6">
                                                            <input id="guardian_occupation" type="text" class="form-control input" name="guardian_occupation" data-tp="s" value="<?=$guardian_occupation?>">
                                                        </div>
                                                        <div class="col-sm-1 col-md-1 result"></div>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <div class="col-sm-6 col-data">
                                                        <label for="guardian_id_card" class="col-sm-5 control-label">Guardian ID Card</label>
                                                        <div class="col-sm-6">
                                                            <input id="guardian_id_card" type="text" class="form-control input" name="guardian_id_card" data-tp="s" value="<?=$guardian_id_card?>">
                                                        </div>
                                                        <div class="col-sm-1 col-md-1 result"></div>
                                                    </div>
                                                    <div class="col-sm-6 col-data">
                                                        <label for="guardian_relation" class="col-sm-5 control-label">Guardian Relation</label>
                                                        <div class="col-sm-6">
                                                            <input type="text" class="form-control input" name="guardian_relation" data-tp="s" value="<?=$guardian_relation?>">
                                                        </div>
                                                        <div class="col-sm-1 col-md-1 result"></div>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <div class="col-sm-6 col-data">
                                                        <label for="constituency" class="col-sm-5 control-label">Constituency</label>
                                                        <div class="col-sm-6">
                                                            <input type="text" class="form-control input" name="constituency" data-tp="s" value="<?=$constituency?>">
                                                        </div>
                                                        <div class="col-sm-1 col-md-1 result"></div>
                                                    </div>
                                                    <div class="col-sm-6 col-data">
                                                         <label for="email" class="col-sm-5 control-label">Email</label>
                                                        <div class="col-sm-6">
                                                            <input type="text" class="form-control input" name="email" data-tp="s" value="<?=$email?>">
                                                        </div>
                                                        <div class="col-sm-1 col-md-1 result"></div>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <div class="col-sm-6 col-data">
                                                        <label for="county" class="col-sm-5 control-label">County</label>
                                                        <div class="col-sm-6">
                                                            <input id="county" type="text" class="form-control input" name="county" data-tp="s" value="<?=$county?>">
                                                        </div>
                                                        <div class="col-sm-1 col-md-1 result"></div>
                                                    </div>
                                                    <div class="col-sm-6 col-data">
                                                        <label for="town" class="col-sm-5 control-label">Town</label>
                                                        <div class="col-sm-6">
                                                            <input id="town" type="text" class="form-control input" name="town" data-tp="s" value="<?=$town?>">
                                                        </div>
                                                        <div class="col-sm-1 col-md-1 result"></div>
                                                    </div>
                                                    
                                                </div>
                                                
                                                <div class="form-group">
                                                   
                                                    <div class="col-sm-6 col-data">
                                                         <label for="location" class="col-sm-5 control-label">Location</label>
                                                        <div class="col-sm-6">
                                                            <input type="text" class="form-control input" name="location" data-tp="s" value="<?=$location?>">
                                                        </div>
                                                        <div class="col-sm-1 col-md-1 result"></div>
                                                    </div>
                                                    <div class="col-sm-6 col-data">
                                                         <label for="village" class="col-sm-5 control-label">Village</label>
                                                        <div class="col-sm-6">
                                                            <input type="text" class="form-control input" name="village" data-tp="s" value="<?=$village?>">
                                                        </div>
                                                        <div class="col-sm-1 col-md-1 result"></div>
                                                    </div>
                                                </div>
                                               
                                                 <div class="form-group">
                                                    <div class="row col-data">
                                                        <div class="col-sm-3 text-right">
                                                            <label for="student_profile" class="control-label">Student Profile</label>
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <textarea class="form-control input" name="student_profile" data-tp="s" rows="3"><?=$student_profile?></textarea>
                                                        </div>
                                                        <div class="col-sm-1 result"></div>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    
                                                    <div class="col-sm-3"></div>
                                                    <div class="col-sm-8">
                                                    <button class="btn btn-lg btn-info btn-block">Submit</button>
                                                    </div>
                                                    
                                                </div>
                                            
                                            </div>
                                                                                                                        
                                                                                       
                                        </form>
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