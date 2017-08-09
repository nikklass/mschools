<?php 
	
	include_once("api/includes/DB_handler.php"); 
	include_once("includes/funcs.php"); 
	include_once("api/includes/Config.php"); 
	
	$admin = true;
	//$show_bootstrap_dialog = true;
	$show_form = true;
	$form_validation = true; //form validation classes
	$show_file_upload = true; //show file upload css/ js
	$show_delete_images = true;
	$show_popup = true; // show colorbox
	
	$db = new DbHandler();
	
	$show_table = true;
	$show_results_list = true;
	
?>

<?php
	
	$perms = ALL_RESULT_PERMISSIONS; 
	
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
		//echo "items - " . USER_ID . " - ";
		//print_r($items); exit;
		
		$item_data = $items['rows'][0];
		$sch_id = $item_data['id'];
		$top_sch_name = $item_data['name'];	 //echo "id - $id";	exit;	
		//echo "top_sch_name - " . $top_sch_name . " - ";											
	
	}
	
	//print_r($items); exit;
		
	$top_sch_id = $sch_id;
	
	$page_title = "Manage Results - $top_sch_name";
	
?>

<?php 

	//if user has read permissions
	$user_id = USER_ID; 
	if (!(SUPER_ADMIN_USER) && !($db->getEstPermissions($user_id, $sch_id, $perms))) 
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
                        
                        <div class="row">
                            
                            <div class="col-md-6">
                            
                                
                                <a data-toggle="collapse" data-target="#mpesa-filter" class="btn btn-block btn-primary btn-lg margin-btm-10">
                                Click To Filter/ Export Data</a>
                                                                        
                                <div class="panel panel-default paper-shadow panel-grey collapse" data-z="0.5" id="mpesa-filter">
                                    
                                    <div class="margin-20">
                                        
                                        <form class="form-horizontal inputform">
                                        
                                            <h4>Filter Results</h4>
                                            
                                            <hr>
                                              
                                            <div class="form-group">
                                                            
                                                <label for="current_class" class="col-sm-1 control-label text-right">Class</label>
                                                <div class="col-sm-2">
                                                    
                                                    <select id="current_class" name="current_class" class="form-control selectpicker">
                                                        
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
                                                
                                                <label for="stream" class="col-sm-1 control-label text-right">Stream</label>
                                                <div class="col-sm-2">
                                                    
                                                    <select id="stream" name="stream" class="form-control selectpicker">
                                                        
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
                                                
                                                <label for="term" class="col-sm-1 control-label text-right">Term</label>
                                                <div class="col-sm-2">
                                                    
                                                    <select id="term" name="term" class="form-control selectpicker">
                                                        
                                                        <option value="">All</option>
                                                        
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                                                                                             
                                                    </select>
                                                    
                                                </div>
                                                
                                                <label for="year" class="col-sm-1 control-label text-right">Year</label>
                                                <div class="col-sm-2">
                                                    
                                                    <select id="year" name="year" class="form-control selectpicker">
                                                                                                                
                                                        <?php
                                                        
                                                            $items = $db->getYearData();
                                                                                                                    
                                                            foreach ($items as $key => $val) {
                                                                $id = $val['id'];
                                                                $name = $val['name'];
                                                                echo "<option value='$id'>$name</option>";
                                                            }
                                                        
                                                        ?>
                                                                                                                             
                                                    </select>
                                                    
                                                </div>
                                                
                                             </div>
                                                                                                                                                    
                                        </form>
                                        
                                        <hr>
                                        
                                        <div class="text-center">
                                            <a href="" class="btn btn-success export_excel" data-item-type="result_reports"><i class="fa fa-file-excel-o"></i> Export Data To Excel</a>
                                        </div>
                                    
                                    </div>
                                                
                                </div>
                                
                                <div class="clearfix"></div>
                                
                                
                                <!-- Tabbable Widget -->
                                <div class="tabbable paper-shadow relative" data-z="0.5">
                                
                                    <!-- Tabs -->
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a href="#item_listing2" data-toggle="tab"><i class="fa fa-fw fa-bars"></i> <span class="hidden-sm hidden-xs">Student Results</span></a></li>
                                        <li><a href="#new_record_item" data-toggle="tab"><i class="fa fa-fw fa-plus"></i> <span class="hidden-sm hidden-xs">Add Single Result</span></a></li>
                                        <li><a href="#new_record_bulk" data-toggle="tab"><i class="fa fa-fw fa-plus"></i> <span class="hidden-sm hidden-xs">Bulk Upload Results</span></a></li>
                                    </ul>
                                    <!-- // END Tabs -->
                                    
                                    <!-- Panes -->
                                    <div class="tab-content">
                                    
                                    	<div id="item_listing2" class="tab-pane active">

                                            <div class="panel panel-default paper-shadow" data-z="0.5">
                                                                                                                                                
                                                <div class="table-responsive">
                                                                                                                                           
                                                    <div class="table-responsive contactsHeight2" id="table-responsive" data-tbl="sch_students" data-tbl-pk="id">
                                            
                                                        <table class="table table-condensed table-hover text-subhead v-middle" id="mybootgrid">
                                                            <thead>
                                                                <tr>
                                                                    <th data-column-id="id" data-type="numeric" data-identifier="true" data-visible="false" data-sortable="true">ID</th>  
                                                                    <th data-column-id="reg_no" data-sortable="true">Reg.</th>
                                                                    <th data-column-id="name" data-sortable="true">Student</th>
                                                                    <th data-column-id="current_class" data-sortable="true">Class</th>
                                                                    <th data-column-id="year" data-sortable="true">Year</th>
                                                                    <th data-column-id="term" data-sortable="true">Term</th>
                                                                    <th data-column-id="total_score" data-sortable="true" data-visible="false">Total</th>
                                                                    <th data-column-id="points" data-sortable="true">Points</th>
                                                                    <th data-column-id="grade" data-sortable="true">Grade</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody></tbody>
                                                        </table>
                                                                                                            
                                                    </div>
                                                
                                                   
                                                    
                                                    
                                                </div>
                    
                                            </div>
                                            
                                        </div>
                                        
                                        <div id="new_record_item" class="tab-pane">
                                       
                                           <h3 class="text-display-1 text-center margin-bottom-none">Add Student Result</h3>
                                                            
                                           <hr>
                                           
                                           <form class="form-horizontal form-new-result inputform"  data-parsley-validate>
                                                                                                                
                                                <div class="form-group">
                                                        
                                                    <div class="row col-data">
                                                        <label for="student" class="col-sm-3 control-label">Student</label>
                                                        <div class="col-sm-9">
                                                            <select id="student_id" name="student_id" class="form-control selectpickerz">
                                                                                                                                    
                                                                <?php
                                                                        
                                                                    $items = $db->getStudentGridListing($sch_id, 1, "", 10000);
                                                                                                                            
                                                                    foreach ($items["rows"] as $key => $val) {
                                                                        $id = $val['id'];
                                                                        $name = $val['name'];
                                                                        echo "<option value='$id'>$name</option>";
                                                                    }
                                                                
                                                                ?>
                                                                
                                                            </select>
                                                            <input type="hidden" name="sch_id" value="<?=$sch_id?>">
                                                        </div>
                                                    </div>
                                                
                                                </div>
                                                                                                            
                                               <div class="form-group">
                                                    
                                                    <label for="year" class="col-sm-3 control-label">Year</label>
                                                    <div class="col-sm-9">
                                                        <select id="year" name="year" class="form-control selectpickerz">
                                                
                                                            <!--<option value=''>Please select</option>-->
                                                            
                                                            <?php
                                                                    
                                                                $items = $db->getYearData();
                                                                                                                        
                                                                foreach ($items as $key => $val) {
                                                                    $id = $val['id'];
                                                                    $name = $val['name'];
                                                                    echo "<option value='$id'>$name</option>";
                                                                }
                                                            
                                                            ?>
                                                            
                                                        </select>
                                                        
                                                     </div>

                                                </div>
                                                                                                            
                                                <input type="hidden" name="single_student_result" id="single_student_result" value="1">
                                                
                                                <div class="form-group">
                                                                    
                                                    <label for="term" class="col-sm-3 control-label">Term/ Sem</label>
                                                    <div class="col-sm-9">
                                                        <select id="term" name="term" class="form-control selectpickerz" data-parsley-trigger="change" required>
                                                                
                                                            <option value="1">1</option>
                                                            <option value="2">2</option>
                                                            <option value="3">3</option>
                                                            
                                                        </select>
                                                    </div>
                                                
                                                </div>
                                                                                                
                                                <div class="form-group">
                                                                                                                            
                                                    <label for="subject" class="col-sm-3 control-label">Subject</label>
                                                    <div class="col-sm-9">
                                                        <select id="subject_item" name="subject" class="form-control selectpickerz" required>
                                                                
                                                            <option value="">Select Subject</option>
                                                            <?php
                                                                
                                                                $items = $db->getSubjectsListing($sch_id, "", "", "", "", "", USER_ID, 1, false);
                                                                //print_r($items); exit;
                                                                                                                
                                                                foreach ($items["rows"] as $key => $val) {
                                                                    $code = $val['code'];
                                                                    $name = $val['name'];
                                                                    echo "<option value='$code'>$name</option>";
                                                                }
                                                            
                                                            ?>
                                                            
                                                        </select>
                                                    </div>
                                                    
                                                </div>
                                                
                                                <div class="form-group">
                                                
                                                    <label for="score" class="col-sm-3 control-label">Score</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control numbersOnly" maxlength="3" name="score" id="score_item" required>
                                                    </div>
                                                
                                                </div>
                                                
                                                <div class="form-group">
                                        
                                                    <div class="col-sm-3"></div>
                                                    <div class="col-sm-9">
                                                    <button class="btn btn-lg btn-primary btn-block">Submit</button>

                                                    </div>
                                                    
                                                </div>
                                                                                                    
                                            </form>
                                        
                                        </div>
                                        
                                        <div id="new_record_bulk" class="tab-pane">
                                       
                                           <form enctype="multipart/form-data" method="post" class="form-upload-results">
                                                
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
                                                <input type="hidden" class="form-control" name="user_id" id="user_id" value="<?=USER_ID?>">
                                                
                                                <div class="form-group">
                                                    <label for="student_file" class="col-sm-3 control-label">Select File</label>
                                                    <div class="col-sm-9">
                                                    <input id="noupload" name="res_file" type="file" class="myfile">
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <div class="col-sm-3"></div>
                                                    <div class="col-sm-9">
                                                        <div class="padding-20"><i class="fa fa-2x fa-file-excel-o text-success"></i> &nbsp;&nbsp;<a href="<?=SITEPATH?>sample_files/results_upload.csv">Excel Results Template (Download)</a></div>
                                                        <div class="padding-20"><i class="fa fa-2x fa-file-excel-o text-success"></i> &nbsp;&nbsp;<a href="<?=SITEPATH?>sample_files/results_primary.csv">Primary School Excel Results Template (Download)</a></div>
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
                                        <li class="active"><a href="#details" data-toggle="tab"><i class="fa fa-fw fa-bars"></i> <span class="hidden-sm hidden-xs">Result Details</span></a></li>
                                        <li><a href="#item-history" data-toggle="tab"><i class="fa fa-fw fa-credit-card"></i> <span class="hidden-sm hidden-xs">Result History</span></a></li>
                                    </ul>
                                    <!-- // END Tabs -->
                                
                                
                                    <!-- Panes -->
                                    <div class="tab-content">
                                                                                                    
                                        
                                        
                                        
                                        <div class="no-results">Please Select Student to Begin</div>
                                        
                                        <div id="details" class="tab-pane active">
                                                                            
                                            <div class="item-details hidden">
                                            
                                                <div class="container-fluids">
                                                                    
                                                    <div class="panel panel-default text-center login-form-bg" data-z="0.5">
                                                    
                                                        <h3 class="text-display-1 text-center margin-bottom-none" id="student_full_names">Results Summary</h3>
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
                                                                                            
                                                <div class="container-fluids">
                                                                            
                                                    <div class="panel panel-default paper-shadow" data-z="0.5" id="papershadow">

                                                        <table id="results-list" class="table table-striped table-responsive" width="100%" cellspacing="0" role="grid" style="width: 100%;">
                                                            
                                                            <thead>
                                                                
                                                                <th>Subject</th>
                                                                <th>Score</th>
                                                                <th>Grade</th>
                                                                <th>Edit</th>
                                                                <th>Delete</th>
                                                                
                                                            </thead>
                                                            
                                                            <tbody id="results-data"></tbody>
                                                            
                                                            
                                                        </table>
                                                    
                                                    </div>
                                                    
                                                    <hr>
                                                    
                                                    <input type="hidden" id="result_id">
                                                	<input type="hidden" id="admin" value="1">
                                                
                                                    <div class="row hidden" id="report-summary">
                                                        <div class="col-sm-8">
                                                            <div class="col-sm-12 text-right margin-top-10">
                                                                <span id="report_total_amount"></span>
                                                            </div>
                                                            
                                                        </div>
                                                        <div class="col-sm-4 save-report">
                                                            <div class="pdf_box animate show_pdf" data-item-type="single_result">
                                                                <img style="vertical-align:middle" src="<?=SITEPATH?>admin/images/pdf_icon_sm.png" height="30"> &nbsp;
                                                                Save Report
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="clearfix"></div>                                                        
                                            
                                                </div>
                                                
                                                <div style='display:none'>
                                                    
                                                    <form class="form-horizontal form-edit-result inputform" id="edit_record">
                                                                                                                      
                                                        <div class="form-group">
                                                        
                                                            <div class="col-sm-3"></div>
                                                            <div class="col-sm-9">
                                                                <h3>Edit Result</h3>
                                                            </div>
                                                        
                                                        </div>
                                                                                                    
                                                        <hr>
                                                        
                                                        <div class="form-group">
                                                            <label for="subject_name" class="col-sm-3 control-label">Subject</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" class="form-control invisible-text-box" name="subject_name" id="subject_name">
                                                                <input type="hidden" name="result_item_id" id="result_item_id">
                                                                <input type="hidden" name="sch_id" id="sch_id" value="<?=$sch_id?>">
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
                                                            <button type="submit" class="btn btn-primary col-sm-12">Submit</button>
                                                            </div>
                                                            
                                                        </div>
                                                                                                                                                                
                                                	</form>
                                                    
                                                </div>                                                
                                                                                            
                                            </div>
                                            
                                        </div>
                                        
                                        <div id="item-history" class="tab-pane">
                                                                        
                                            <div class="item-details ">
                                            
                                            	<div class="item-details hidden">
                                            
                                                   <div class="col-sm-12">
                                                            
                                                       <div class="table-responsive" id="result-item-history">
                                                    
                                                            <table class="table table-condensed table-hover text-subhead v-middle" id="mybootgrid-history">
                                                                <thead>
                                                                    <tr>
                                                                        <th data-column-id="id" data-type="numeric" data-identifier="true" data-sortable="true" data-visible="false">ID</th>  
                                                                        <th data-column-id="name" data-sortable="true">Subject</th>
                                                                        <th data-column-id="score" data-sortable="true" data-align="right" data-header-align="right">Score</th>
                                                                        <th data-column-id="grade" data-sortable="true">Grade</th>
                                                                        <th data-column-id="created_at_fmt3" data-sortable="true">Updated</th>
                                                                        <th data-column-id="created_by_name" data-sortable="true">By</th>
                                                                        
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
                                        
            <form class="form-horizontal form-show-fee-upload-results" id="edit_show_fee_upload_results">
                                                                    
                <h3>Result Upload Summary</h3>
                
                <hr>
               
                <div id="result_upload_results">
                    
                    
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