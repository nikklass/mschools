<?php 
	include_once("api/includes/DB_handler.php"); 
	include_once("includes/funcs.php"); 
	include_once("api/includes/Config.php"); 
	
	$admin = true;
	$show_bootstrap_dialog = true;
	$show_form = true;
	$form_validation = true; //form validation classes
	$show_file_upload = true; //show file upload css/ js
	$show_delete_images = true;
	$show_popup = true; // show colorbox
	
	$db = new DbHandler();
	
	//profile pic	
	//$this_page_link = getTheCurrentUrl();
	
	$show_table = true;
	$show_students_list = true;
	
?>

<?php
	
	//echo "USER_ID ". USER_ID;
	$perms = ALL_STUDENT_PERMISSIONS; 
	
	if (!SUPER_ADMIN_USER) {

		$company_ids = $db->getUserCompanyIds(USER_ID, $perms); //echo "co ids - ". $company_ids; exit;
		
	}
	
	if ($_GET["sch_id"] && SUPER_ADMIN_USER) {
		
		$sch_id = $_GET["sch_id"];
		
		$items = $db->getSchoolGridListing("", USER_ID, "", "", "", $sch_id, 1);
                                                                                                                        
		$item_data = $items['rows'][0];
		$top_sch_name = $item_data['name'];	 //echo "id - $id";	exit;		
		
	} else {
		
		if (!SUPER_ADMIN_USER) {
			
			$items = $db->getSchoolGridListing("", USER_ID, "", "", "", "", 1, $company_ids);
			
		} else {

			$items = $db->getSchoolGridListing("", USER_ID, "", "", "", "", 1);
			
		}
		
		$item_data = $items['rows'][0];
		$sch_id = $item_data['id'];
		$top_sch_name = $item_data['name'];	 //echo "id - $id";	exit;												
	
	}
	
	//print_r($items); exit;
		
	$top_sch_id = $sch_id;
	
	$page_title = "Manage Students - $top_sch_name";
	
?>

<?php 

	//if user has read permissions
	$user_id = USER_ID; 
	if (!(SUPER_ADMIN_USER) && !($db->getEstPermissions($user_id, $est_id, $perms))) 
	{
		//user is not allowed to access page
		$page = LOGIN_URL;
		header("Location: $page"); 
		exit();
	} //echo "user_id - $user_id, $est_id, $perms";
	
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
                        
                        <div class="row">
                            
                            <div class="col-md-6">
                            
                                <!-- Tabbable Widget -->
                                <div class="tabbable paper-shadow relative" data-z="0.5">
                                
                                    <!-- Tabs -->
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a href="#student_listing" data-toggle="tab"><i class="fa fa-fw fa-bars"></i> <span class="hidden-sm hidden-xs">Students Listing</span></a></li>
                                        <li><a href="#new_item" data-toggle="tab"><i class="fa fa-fw fa-plus"></i> <span class="hidden-sm hidden-xs">Add Single Student</span></a></li>
                                        <li><a href="#new_bulk" data-toggle="tab"><i class="fa fa-fw fa-plus"></i> <span class="hidden-sm hidden-xs">Bulk Upload Students</span></a></li>
                                    </ul>
                                    <!-- // END Tabs -->
                                    
                                    <!-- Panes -->
                                    <div class="tab-content">
                                    
                                    	<div id="student_listing" class="tab-pane active">

                                            <div class="panel panel-default paper-shadow" data-z="0.5">
                                                
                                                <div id="top_school_id" data-sch-id="<?=$sch_id?>"></div>
                                                
                                                <div class="form-group padding-20-all <?=$hide_admin_css?>">
                                                    <form>
                                                        <label for="sch_id">Select School</label>
                                                        <select id="school-select" name="sch_id" class="form-control" data-parsley-trigger="change" data-parsley-required required>
                                                                                                        
                                                            <?php
                                                                    
                                                                if (SUPER_ADMIN_USER) {
																	$items = $db->getSchoolGridListing("", USER_ID, "", "", "", "", "", "", 1);
																} else if ($company_ids) {
																	$items = $db->getSchoolGridListing("", USER_ID, "", "", "", "", "", $company_ids, 1);
																} //print_r($items); exit;
                                                                                                                        
                                                                if ($items['total']) {
																
																	foreach ($items['rows'] as $key => $val) {
																		$id = $val['id'];
																		$name = $val['name'];														
																		echo "<option value='$id' ";
																		if ($sch_id == $id) { echo " selected "; } 
																		echo ">$name</option>";
																	}
																
																}
                                                            
                                                            ?>
                                                            
                                                        </select>
                                                    </form>
                                                </div>
                                                
                                                <div class="table-responsive" id="table-responsive" data-tbl="sch_students" data-tbl-pk="id">
                                                
                                                    <table class="table table-condensed table-hover text-subhead v-middle" id="mybootgrid">
                                                        <thead>
                                                            <tr>
                                                                <th data-column-id="id" data-type="numeric" data-identifier="true" data-sortable="true" data-visible="false">ID</th>  
                                                                <th data-column-id="name" data-sortable="true">Student Names</th>
                                                                <th data-column-id="reg_no" data-sortable="true">Reg. No</th>
                                                                <th data-column-id="index_no" data-sortable="true">Index No</th>
                                                                <th data-column-id="current_class" data-sortable="true">Class</th>
                                                                <th data-column-id="stream" data-sortable="true">Stream</th>
                                                                <!--<th data-column-id="house" data-sortable="true" data-visible="false">House</th>
                                                                <th data-column-id="county" data-sortable="true" data-visible="false">County</th>
                                                                <th data-column-id="guardian_name" data-sortable="true" data-visible="false">Guardian Name</th>
                                                                <th data-column-id="links" data-formatter="links" data-sortable="false" data-visible="false">Edit</th>
                                                                <th data-column-id="commands" data-formatter="commands" data-sortable="false" data-visible="false">Delete</th>-->
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                           
                                                          
                                                        </tbody>
                                                    </table>
                                                    
                                                </div>
                    
                                            </div>
                                            
                                        </div>
                                        
                                        <div id="new_item" class="tab-pane">
                                        
                                        	<form class="form-horizontal form-new-student inputform" data-parsley-validate>
                                                        
                                                <div class="resultdiv"></div>
                                                                                                
                                                <div id="wrapper_form">
                                                 
                                                    <div class="form-group">
                                                        
                                                        <div class="row col-data">
                                                            <label for="full_names" class="col-sm-3 control-label">Student Names</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" class="form-control" name="full_names" data-parsley-trigger="change" required>
                                                                <input type="hidden" class="form-control" name="sch_id" value="<?=$top_sch_id?>">
                                            					<input type="hidden" class="form-control" name="user_id" value="<?=USER_ID?>">
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        
                                                        <div class="row col-data">
                                                            <label for="reg_no" class="col-sm-3 control-label">Reg No</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" class="form-control" name="reg_no" data-parsley-trigger="change" required>
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                       
                                                        <div class="row col-data">
                                                             <label for="index_no" class="col-sm-3 control-label">Index No</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" class="form-control" name="index_no">
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                    
                                                    <hr>
                                                    
                                                    <div class="form-group">
                                                        
                                                        <div class="col-sm-6 col-data">
                                                            <label for="dob" class="col-sm-6 control-label">Date of Birth</label>
                                                            <div class="col-sm-6">
                                                                
                                                                <div class="input-group date">
                                                                  <input type="text" readonly class="form-control datepicker" name="dob">
                                                                  <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                                                </div>
        
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-sm-6 col-data">
                                                            <label for="admin_date" class="col-sm-6 control-label">Admission Date</label>
                                                            <div class="col-sm-6">
                                                                
                                                                <div class="input-group date">
                                                                  <input type="text" readonly class="form-control datepicker" name="admin_date">
                                                                  <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                                                </div>
        
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                    
                                                     <div class="form-group">
                                                        <div class="col-sm-6 col-data">
                                                            <label for="nationality" class="col-sm-6 control-label">Nationality</label>
                                                            <div class="col-sm-6">
                                                                <input type="text" class="form-control" name="nationality">
                                                            </div>
                                                        </div>
                                                         <div class="col-sm-6 col-data">
                                                            <label for="religion" class="col-sm-6 control-label">Religion</label>
                                                            <div class="col-sm-6">
                                                                <input id="religion" type="text" class="form-control" name="religion">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <div class="col-sm-6 col-data">
                                                            <label for="current_class" class="col-sm-6 control-label">Current Class</label>
                                                            <div class="col-sm-6">
                                                                <input type="text" class="form-control" name="current_class">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6 col-data">
                                                            <label for="stream" class="col-sm-6 control-label">Stream</label>
                                                            <div class="col-sm-6">
                                                                <input type="text" class="form-control" name="stream">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    
                                                    <div class="form-group">
                                                        <div class="col-sm-6 col-data">
                                                            <label for="house" class="col-sm-6 control-label">House</label>
                                                            <div class="col-sm-6">
                                                                <input id="house" type="text" class="form-control" name="house">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6 col-data">
                                                             <label for="club" class="col-sm-6 control-label">Club</label>
                                                            <div class="col-sm-6">
                                                                <input type="text" class="form-control" name="club">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    
                                                    <div class="form-group">
                                                        <div class="col-sm-6 col-data">
                                                            <label for="disability" class="col-sm-6 control-label">Disability</label>
                                                            <div class="col-sm-6">
                                                                <input id="disability" type="text" class="form-control" name="disability">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6 col-data">
                                                             <label for="gender" class="col-sm-6 control-label">Gender</label>
                                                            <div class="col-sm-6">
                                                                <select id="select" name="gender" class="form-control" required>
                                                                    
                                                                    <option value='Male'>Male</option>
                                                                    <option value='Female'>Female</option>
                                                                    
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <div class="row col-data">
                                                             <label for="previous_school" class="col-sm-3 control-label">Previous School</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" class="form-control" name="previous_school">
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                    
                                                    <hr>
                                                    
                                                    <div class="form-group">
                                                    
                                                        <div class="row col-data">
                                                            <label for="guardian_name" class="col-sm-3 control-label">Guardian Name</label>
                                                            <div class="col-sm-9">
                                                                <input id="guardian_name" type="text" class="form-control" name="guardian_name">
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                        
                                                    <div class="form-group">
                                                    
                                                        <div class="row col-data">
                                                             <label for="guardian_address" class="col-sm-3 control-label">Guardian Address</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" class="form-control" name="guardian_address">
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                    
                                                        <div class="row col-data">
                                                            <label for="guardian_phone" class="col-sm-3 control-label">Guardian Phone</label>
                                                            <div class="col-sm-9">
                                                                <input id="guardian_phone" type="text" class="form-control" name="guardian_phone">
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                        
                                                    <div class="form-group">
                                                    
                                                        <div class="row col-data">
                                                            <label for="guardian_occupation" class="col-sm-3 control-label">Guardian Occupation</label>
                                                            <div class="col-sm-9">
                                                                <input id="guardian_occupation" type="text" class="form-control" name="guardian_occupation">
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <div class="col-sm-6 col-data">
                                                            <label for="guardian_id_card" class="col-sm-6 control-label">Guardian ID Card</label>
                                                            <div class="col-sm-6">
                                                                <input id="guardian_id_card" type="text" class="form-control" name="guardian_id_card">
                                                            </div>
                                                            <div class="col-sm-1 col-md-1 result"></div>
                                                        </div>
                                                        <div class="col-sm-6 col-data">
                                                            <label for="guardian_relation" class="col-sm-6 control-label">Guardian Relation</label>
                                                            <div class="col-sm-6">
                                                                <input type="text" class="form-control" name="guardian_relation">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <div class="col-sm-6 col-data">
                                                            <label for="constituency" class="col-sm-6 control-label">Constituency</label>
                                                            <div class="col-sm-6">
                                                                <input type="text" class="form-control" name="constituency">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6 col-data">
                                                             <label for="email" class="col-sm-6 control-label">Email</label>
                                                            <div class="col-sm-6">
                                                                <input type="text" class="form-control" name="email">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <div class="col-sm-6 col-data">
                                                            <label for="county" class="col-sm-6 control-label">County</label>
                                                            <div class="col-sm-6">
                                                                <select name="county" class="form-control" data-parsley-trigger="change" required>
                                                            
                                                                    <option value=''>Please select</option>
                                                                    
                                                                    <?php
                                                                            
                                                                        $items = $db->getCounties();
                                                                                                                                
                                                                        foreach ($items["rows"] as $key => $val) {
                                                                            $id = $val['id'];
                                                                            $name = $val['name'];
                                                                            echo "<option value='$id'>$name</option>";
                                                                        }
                                                                    
                                                                    ?>
                                                                    
                                                                </select>
                                                        
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6 col-data">
                                                            <label for="town" class="col-sm-6 control-label">Town</label>
                                                            <div class="col-sm-6">
                                                                <input id="town" type="text" class="form-control" name="town">
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                       
                                                        <div class="col-sm-6 col-data">
                                                             <label for="location" class="col-sm-6 control-label">Location</label>
                                                            <div class="col-sm-6">
                                                                <input type="text" class="form-control" name="location">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6 col-data">
                                                             <label for="village" class="col-sm-6 control-label">Village</label>
                                                            <div class="col-sm-6">
                                                                <input type="text" class="form-control" name="village">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                     <div class="form-group">
                                                        <div class="row col-data">
                                                            <div class="col-sm-3 text-right">
                                                                <label for="student_profile" class="control-label">Student Profile</label>
                                                            </div>
                                                            <div class="col-sm-9">
                                                                <textarea class="form-control" name="student_profile" rows="3"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <hr>
                                                    
                                                    <div class="form-group">
                                                       <div class="row col-data"> 
                                                            <div class="col-sm-3"></div>
                                                            <div class="col-sm-9">
                                                                <button class="btn btn-lg btn-info btn-block">Submit</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                
                                                </div>
                                                                                                                            
                                                                                           
                                            </form>
                                        
                                        </div>
                                        
                                        <div id="new_bulk" class="tab-pane">
                                        
                                        	<form enctype="multipart/form-data" method="post" class="form-upload-students">
                                            
                                            <div class="form-group padding-20">
                                                <div class="col-sm-3">
                                                	<label for="sch_name" class="control-label">&nbsp;</label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <h4><?=$top_sch_name?></h4>
                                                </div>
                                               
                                            </div>
                                            
                                            <hr>
                                            
                                            <input type="hidden" class="form-control" name="sch_id" value="<?=$top_sch_id?>">
                                            <input type="hidden" class="form-control" name="user_id" value="<?=USER_ID?>">
                                            
                                            <div class="form-group">
                                                <label for="student_file" class="col-sm-3 control-label">Select File</label>
                                                <div class="col-sm-9">
                                                <input id="noupload" name="student_file" type="file" class="myfile">
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <div class="col-sm-3"></div>
                                                <div class="col-sm-9">
                                                	<div class="padding-20"><i class="fa fa-2x fa-file-excel-o text-success"></i> &nbsp;&nbsp;<a href="<?=SITEPATH?>sample_files/students_upload.csv">Excel Students Upload Template (CLICK to download)</a></div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group">
                                                <div class="col-sm-3"></div>
                                                <div class="col-sm-9">
                                                	<button class="btn btn-lg btn-primary btn-block">Submit</button>
                                                </div>
                                                <div class="clear"></div>
                                            </div>
                                            
                                       </form>
                                        
                                        </div>
                                        
                                    </div>
                                    
                                </div>
                                
                           </div>
                           
                           <div class="col-md-6">
                           
                           		<!-- Tabbable Widget -->
                                <div class="tabbable paper-shadow relative" data-z="0.5">
                                
                                    <!-- Tabs -->
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a href="#details" data-toggle="tab"><i class="fa fa-fw fa-bars"></i> <span class="hidden-sm hidden-xs">Student Details</span></a></li>
                                        <li><a href="#logo" data-toggle="tab"><i class="fa fa-fw fa-image"></i> <span class="hidden-sm hidden-xs">Student Photo</span></a></li>
                                        <li><a href="#item-history" data-toggle="tab"><i class="fa fa-fw fa-credit-card"></i> <span class="hidden-sm hidden-xs">Student Item History</span></a></li>
                                        <!--<li><a href="#photos" data-toggle="tab"><i class="fa fa-fw fa-credit-card"></i> <span class="hidden-sm hidden-xs">School Photos</span></a></li>-->
                                    </ul>
                                    <!-- // END Tabs -->
                                
                                
                                    <!-- Panes -->
                                    <div class="tab-content">
                                                            
                                        <div class="no-results">Please Select Student to Begin</div>
                                        
                                        <div id="details" class="tab-pane active">
                                                                            
                                            <div class="item-details hidden">
                                            
                                               <form class="form-horizontal form-edit-student">
                                                                                                                                                    
                                                    <div id="wrapper_form">
                                                                                                             
                                                        <div class="form-group">
                                                            <label for="full_names" class="col-sm-3 control-label">Student Names</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" class="form-control" name="full_names" id="full_names_edit">
                                                                <input type="hidden" class="form-control" name="sch_id" value="<?=$top_sch_id?>">
                                                                <input type="hidden" class="form-control" name="id" id="id_edit">
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="form-group">
                                                            
                                                            <label for="reg_no" class="col-sm-3 control-label">Reg No</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" class="form-control" name="reg_no" id="reg_no_edit">
                                                            </div>
                                                                
                                                        </div>
                                                        
                                                        <div class="form-group">
                                                                                                                        
                                                            <label for="index_no" class="col-sm-3 control-label">Index No</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" class="form-control" name="index_no" id="index_no_edit">
                                                            </div>
                                                            
                                                        </div>
                                                        
                                                        <hr>
                                                        
                                                        <div class="form-group">
                                                            <div class="col-sm-6 col-data">
                                                                <label for="dob" class="col-sm-6 control-label">Date of Birth</label>
                                                                <div class="col-sm-6">                                                            
                                                                    <div class="input-group date">
                                                                      <input type="text" readonly class="form-control datepicker" name="dob" id="dob_edit">
                                                                      <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6 col-data">
                                                                <label for="admin_date" class="col-sm-6 control-label">Admission Date</label>
                                                                <div class="col-sm-6">                                                            
                                                                    <div class="input-group date">
                                                                      <input type="text" readonly class="form-control datepicker" name="admin_date" id="admin_date_edit">
                                                                      <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                         <div class="form-group">
                                                            <div class="col-sm-6 col-data">
                                                                <label for="nationality" class="col-sm-6 control-label">Nationality</label>
                                                                <div class="col-sm-6">
                                                                    <input type="text" class="form-control" name="nationality" id="nationality_edit">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6 col-data">
                                                                <label for="religion" class="col-sm-6 control-label">Religion</label>
                                                                <div class="col-sm-6">
                                                                    <input type="text" class="form-control" name="religion" id="religion_edit">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="form-group">
                                                            <div class="col-sm-6 col-data">
                                                                <label for="current_class" class="col-sm-6 control-label">Current Class</label>
                                                                <div class="col-sm-6">
                                                                    <input type="text" class="form-control" name="current_class" id="current_class_edit">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6 col-data">
                                                                <label for="stream" class="col-sm-6 control-label">Stream</label>
                                                                <div class="col-sm-6">
                                                                    <input type="text" class="form-control" name="stream" id="stream_edit">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        
                                                        <div class="form-group">
                                                            <div class="col-sm-6 col-data">
                                                                <label for="house" class="col-sm-6 control-label">House</label>
                                                                <div class="col-sm-6">
                                                                    <input type="text" class="form-control" name="house" id="house_edit">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6 col-data">
                                                                 <label for="club" class="col-sm-6 control-label">Club</label>
                                                                <div class="col-sm-6">
                                                                    <input type="text" class="form-control" name="club" id="club_edit">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="form-group">
                                                            <div class="col-sm-6 col-data">
                                                                <label for="disability" class="col-sm-6 control-label">Disability</label>
                                                                <div class="col-sm-6">
                                                                    <input id="disability_edit" type="text" class="form-control" name="disability">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6 col-data">
                                                                 <label for="gender" class="col-sm-6 control-label">Gender</label>
                                                                <div class="col-sm-6">
                                                                    <select id="gender_edit" name="gender" class="form-control">
                                                                       
                                                                        <option value='Male' <?php if ($gender=='Male') { echo " selected"; } ?>>Male</option>
                                                                        <option value='Female' <?php if ($gender=='Female') { echo " selected"; } ?>>Female</option>
                                                                        
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="form-group">
                                                            <div class="col-sm-12 col-data">
                                                                 <label for="previous_school" class="col-sm-3 control-label">Previous School</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control" name="previous_school" id="previous_school_edit">
                                                                </div>
                                                            </div>
                                                           
                                                        </div>
                                                        
                                                        <div class="form-group">
                                                            <div class="col-sm-12 col-data">
                                                                <label for="student_profile" class="col-sm-3 control-label">Student Profile</label>
                                                                <div class="col-sm-9">
                                                                    <textarea class="form-control" name="student_profile" id="student_profile_edit"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <hr>
                                                        
                                                        <div class="form-group">
                                                            <div class="col-sm-12 col-data">
                                                                <label for="guardian_name" class="col-sm-3 control-label">Guardian Name</label>
                                                                <div class="col-sm-9">
                                                                    <input id="guardian_name_edit" type="text" class="form-control" name="guardian_name">
                                                                </div>
                                                            </div>
                                                        </div>
                                                            
                                                        <div class="form-group">
                                                            <div class="col-sm-12 col-data">
                                                                 <label for="guardian_address" class="col-sm-3 control-label">Guardian Address</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control" name="guardian_address" id="guardian_address_edit">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="form-group">
                                                            <div class="col-sm-12 col-data">
                                                                <label for="guardian_phone" class="col-sm-3 control-label">Guardian Phone</label>
                                                                <div class="col-sm-9">
                                                                    <input id="guardian_phone_edit" type="text" class="form-control" name="guardian_phone">
                                                                </div>
                                                            </div>
                                                            
                                                        </div>
                                                            
                                                        <div class="form-group">
                                                            <div class="col-sm-12 col-data">
                                                                <label for="guardian_occupation" class="col-sm-3 control-label">Guardian Occupation</label>
                                                                <div class="col-sm-9">
                                                                    <input id="guardian_occupation_edit" type="text" class="form-control" name="guardian_occupation">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="form-group">
                                                            <div class="col-sm-6 col-data">
                                                                <label for="guardian_id_card" class="col-sm-6 control-label">Guardian ID Card</label>
                                                                <div class="col-sm-6">
                                                                    <input id="guardian_id_card_edit" type="text" class="form-control" name="guardian_id_card">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6 col-data">
                                                                <label for="guardian_relation" class="col-sm-6 control-label">Guardian Relation</label>
                                                                <div class="col-sm-6">
                                                                    <input type="text" class="form-control" name="guardian_relation" id="guardian_relation_edit">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="form-group">
                                                            <div class="col-sm-6 col-data">
                                                                <label for="constituency" class="col-sm-6 control-label">Constituency</label>
                                                                <div class="col-sm-6">
                                                                    <input type="text" class="form-control" name="constituency" id="constituency_edit">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6 col-data">
                                                                 <label for="email" class="col-sm-6 control-label">Email</label>
                                                                <div class="col-sm-6">
                                                                    <input type="text" class="form-control" name="email" id="email_edit">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="form-group">
                                                            <div class="col-sm-6 col-data">
                                                                <label for="county" class="col-sm-6 control-label">County</label>
                                                                <div class="col-sm-6">
                                                                    <select id="county_edit" name="county" class="form-control">
                                                            
                                                                        <option value=''>Please select</option>
                                                                        
                                                                        <?php
                                                                                
                                                                            $items = $db->getCounties();
                                                                                                                                    
                                                                            foreach ($items["rows"] as $key => $val) {
                                                                                $id = $val['id'];
                                                                                $name = $val['name'];
                                                                                echo "<option value='$id'>$name</option>";
                                                                            }
                                                                        
                                                                        ?>
                                                                        
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6 col-data">
                                                                <label for="town" class="col-sm-6 control-label">Town</label>
                                                                <div class="col-sm-6">
                                                                    <input id="town_edit" type="text" class="form-control" name="town">
                                                                </div>
                                                            </div>
                                                            
                                                        </div>
                                                        
                                                        <div class="form-group">
                                                           
                                                            <div class="col-sm-6 col-data">
                                                                 <label for="location" class="col-sm-6 control-label">Location</label>
                                                                <div class="col-sm-6">
                                                                    <input type="text" class="form-control" name="location" id="location_edit">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6 col-data">
                                                                 <label for="village" class="col-sm-6 control-label">Village</label>
                                                                <div class="col-sm-6">
                                                                    <input type="text" class="form-control" name="village" id="village_edit">
                                                                </div>
                                                            </div>
                                                        </div>
                                                       
                                                        <hr>
                                                        
                                                        <div class="form-group">
                                                            
                                                            <div class="col-sm-3"></div>
                                                            <div class="col-sm-9">
                                                            <button class="btn btn-lg btn-primary btn-block">Save Changes</button>
                                                            </div>
                                                            
                                                        </div>
                                                    
                                                    </div>
                                                                                                                                
                                                                                               
                                                </form>
                                                                                            
                                            </div>
                                            
                                        </div>
                                        
                                        <div id="logo" class="tab-pane">
                                                                        
                                            <div class="item-details hidden">
                                            
                                                <div class="col-sm-12">
                                                        
                                                    <!-- the avatar markup -->
                                                    <div id="kv-avatar-errors-1" class="center-block" style="display:none"></div>
                                                    
                                                    <form enctype="multipart/form-data" class="form-upload-pics">
                                                        
                                                        <div class="resultdiv"></div>
                                                        
                                                        <div class="wrapper_form">
                                                            <div class="form-group">
                                                                <label for="multiple-images" class="col-sm-12 control-label">Recommended Image Size: <?=SQUARE_IMAGE_WIDTH?> X <?=SQUARE_IMAGE_HEIGHT?></label>
                                                                <div class="col-sm-12">
                                                                    <div class="bs-example">
                                                                        <label class="control-label">Select File</label>
                                                                        <input id="multiple-images2" name="multiple-images[]" type="file" multiple class="file-loading">
                                                                        <input name="item_title" id="item_title" type="hidden">
                                                                        <input name="category" id="category" value="" type="hidden">
                                                                        <input name="category_id" id="category_id" type="hidden">
                                                                        <input name="image_crop_ratio" value="1:1" type="hidden">
                                                                        <input name="image_width" id="image_width" value="<?=SQUARE_IMAGE_WIDTH?>" type="hidden">
                                                                        <input name="image_height" id="image_height" value="<?=SQUARE_IMAGE_HEIGHT?>" type="hidden">
                                                                    </div>
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
                                                
                                                <hr>
                                                
                                                <div class="col-sm-12">
                                                    
                                                    <div id="table-data" data-tbl="images" data-tbl-pk="id">
                                                        <div class="thumbnailz" id="item-images"></div>
                                                    </div>
                                                    
                                                </div>
                                                
                                                <div class="clear"></div>
                                            
                                            </div>
                                            
                                        </div>
                                        
                                        <div id="item-history" class="tab-pane">
                                                                        
                                            <div class="item-details hidden">
                                            
                                                <div class="col-sm-12">
                                                        
                                                   <div class="table-responsive" id="student-history" data-tbl="sch_students_history" data-tbl-pk="id">
                                                
                                                        <table class="table table-condensed table-hover text-subhead v-middle" id="mybootgrid-history">
                                                            <thead>
                                                                <tr>
                                                                    
                                                                    <th data-column-id="id" data-type="numeric" data-identifier="true" data-sortable="true" data-visible="false">ID</th>  
                                                                    <th data-column-id="name" data-sortable="true">Student Names</th>
                                                                    <th data-column-id="reg_no" data-sortable="true">Reg.</th>
                                                                    <!--<th data-column-id="index_no" data-sortable="true">Index No</th>-->
                                                                    <th data-column-id="current_class" data-sortable="true">Class</th>
                                                                    <!--<th data-column-id="stream" data-sortable="true">Stream</th>-->
                                                                    <th data-column-id="created_at" data-sortable="true">Updated</th>
                                                                    <th data-column-id="created_by" data-sortable="true">By</th>
                                                                    <th data-column-id="views" data-formatter="views" data-sortable="false">View</th>
                                                                    
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                              
                                                            </tbody>
                                                        </table>
                                                        
                                                    </div>
                                                
                                                </div>
                                                
                                               <div class="clearfix"></div>
                                            
                                            </div>
                                            
                                        </div>
                                        
                                        <div id="photos" class="tab-pane hidden">
                                                                        
                                            <div class="item-details hidden">
                                            
                                                <div class="col-sm-12">
                                                        
                                                    <!-- the avatar markup -->
                                                    <div id="kv-avatar-errors-1" class="center-block" style="display:none"></div>
                                                    
                                                    <form enctype="multipart/form-data" method="post" class="form-upload-pics">
                                                        
                                                        <div class="resultdiv"></div>
                                                        
                                                        <div class="wrapper_form">
                                                            <div class="form-group">
                                                                <label for="multiple-images" class="col-sm-12 control-label">Recommended Image Size: <?=SQUARE_IMAGE_WIDTH?> X <?=SQUARE_IMAGE_HEIGHT?></label>
                                                                <div class="col-sm-12">
                                                                    <div class="bs-example">
                                                                        <label class="control-label">Select File</label>
                                                                        <input id="multiple-images3" name="multiple-images[]" type="file" multiple class="file-loading">
                                                                        <input name="item_title" id="item_image_title" type="hidden">
                                                                        <input name="category" id="category" value="<?=PRODUCT_CATEGORY?>" type="hidden">
                                                                        <input name="category_id" id="category_id" type="hidden">
                                                                        <input name="category_char" class="category_char" type="hidden">
                                                                        <input name="image_crop_ratio" value="1:1" type="hidden">
                                                                        <input name="image_width" id="image_width" value="<?=SQUARE_IMAGE_WIDTH?>" type="hidden">
                                                                        <input name="image_height" id="image_height" value="<?=SQUARE_IMAGE_HEIGHT?>" type="hidden">
                                                                    </div>
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
                                                
                                                <hr>
                                                
                                                <div class="col-sm-12">
                                                    
                                                    <div id="table-data" data-tbl="images" data-tbl-pk="id">
                                                        <div class="thumbnailz" id="item-images"></div>
                                                    </div>
                                                    
                                                </div>
                                                
                                                <div class="clear"></div>
                                            
                                            </div>
                                            
                                        </div>
                                        
                                    </div>
                                    <!-- // END Panes -->
                                
                                </div>
                                <!-- // END Tabbable Widget -->
                           
                           </div>
                           
                        </div>

                    </div>

                </div>
                <!-- /st-content-inner -->

            </div>
            <!-- /st-content -->

        </div>
        <!-- /st-pusher -->
        
        <!-- show bulk upload results -->
        <div style='display:none'>
                                        
            <form class="form-horizontal form-show-student-upload-results" id="edit_show_student_upload_results">
                                                                    
                <h3>Student Upload Results</h3>
                
                <hr>
               
                <div id="student_upload_results">
                    
                    
                </div>
               
            </form>
            
        </div>
        <!-- end show bulk upload results -->

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