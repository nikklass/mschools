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
	
	//profile pic	
	//$this_page_link = getTheCurrentUrl();
	
	$show_table = true;
	$show_fees_report = true;
	
?>

<?php
	
	//echo "USER_ID ". USER_ID;
	$perms = ALL_REPORT_PERMISSIONS; 
	
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
	
	$page_title = "Fee Reports - $top_sch_name";
	
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
                            
                            <div class="col-md-5">
                            
                                <!-- Tabbable Widget -->
                                <div class="tabbable paper-shadow relative" data-z="0.5">
                                
                                    <!-- Tabs -->
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a href="#student_listing" data-toggle="tab"><i class="fa fa-fw fa-bars"></i> <span class="hidden-sm hidden-xs">Filter Fees Report</span></a></li>
                                    </ul>
                                    <!-- // END Tabs -->
                                    
                                    <!-- Panes -->
                                    <div class="tab-content">
                                    
                                    	<div id="fee_reports" class="tab-pane active">
                                            
                                            <div id="events-list" class="tab-pane active">
                                            
                                            <div class="panel panel-default paper-shadow" data-z="0.5">
                                                                                                                                                
                                                <div class="form-group margin-tb-20 margin-side-20">
                                                    
                                                      <div class="<?=$hide_admin_css?>">
                                                            <form>
                                                                <div class="form-group">
                                                         
                                                                    <div class="row">
                                                                        <label for="sch_id" class="col-sm-4 control-label text-right">Select School</label>
                                                                    	<div class="col-sm-8">
                                                                            <select id="school-select" name="sch_id" class="form-control" data-parsley-trigger="change" required>
                                                                                                                            
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
                                                                        </div>
                                                                    
                                                                    </div>
                                                                    
                                                                </div>
                                                                
                                                            </form>
                                                                
                                                      </div>
                                                        
                                                      <form class="filter-fee-report margin-top-20">
                                                           
                                                            <div class="form-group">
                                                             
                                                                <div class="row">
                                                                    <label for="current_class" class="col-sm-4 control-label text-right">Class</label>
                                                                    <div class="col-sm-8">
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
                                                                    
                                                                </div>
                                                                
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                         
                                                                <div class="row">
                                                                        
                                                                    <label for="stream" class="col-sm-4 control-label text-right">Stream</label>
                                                                    <div class="col-sm-8">
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
                                                            
                                                            <hr>
                                                            
                                                            <div class="form-group">
                                                               <div class="row">
                                                                    <label for="start_date" class="col-sm-4 control-label text-right">Start Payment Date</label>
                                                                    <div class="col-sm-8">
                                                                        
                                                                        <div class="input-group date">
                                                                          <input type="text" readonly class="form-control datepicker" name="start_date" id="start_date">
                                                                          <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                                                        </div>
                                                                        <a href="#" id="clear_start_date" class="noclick hidden">Clear Date</a>
                    
                                                                    </div>
                                                               </div>
                                                            </div>
                                                                                                                      
                                                            <div class="form-group">
                                                               <div class="row">
                                                                    <label for="end_date" class="col-sm-4 control-label text-right">End Payment Date</label>
                                                                    <div class="col-sm-8">
                                                                        
                                                                        <div class="input-group date">
                                                                          <input type="text" readonly class="form-control datepicker" name="end_date" id="end_date">
                                                                          <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                                                        </div>
                                                                        <a href="#" id="clear_end_date" class="noclick hidden">Clear Date</a>
                    
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            
                                                            <!--<div class="form-group">
                                                                <div class="row">
                                                                    <label for="status" class="col-sm-3 control-label text-right">Order Status</label>
                                                                    <div class="col-sm-9">
                                                                        <select id="status" name="status" class="form-control">
                                                                                                                        
                                                                            <option value=''>All</option>
                                                                            
                                                                            <?php
                                                                                    
                                                                                /*$items = $db->getStatuses();
                                                                                                                                        
                                                                                foreach ($items as $key => $val) {
                                                                                    $id = $val['id'];
                                                                                    $name = $val['name'];
                                                                                    echo "<option value='$id'>$name</option>";
                                                                                }*/
                                                                            
                                                                            ?>
                                                                            
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>-->
                                                            
                                                            <div class="form-group">
                                                                <div class="row">
                                                                    <label for="client_id" class="col-sm-4 control-label text-right">Student Reg. No.</label>
                                                                    <div class="col-sm-8">
                                                                        <input id="reg_no" name="reg_no" class="form-control numbersOnly">
                                                                        <input name="user_id" id="user_id" class="form-control" type="hidden" value="<?=USER_ID?>">
                                                                        <input name="sch_id" id="sch_id" class="form-control" type="hidden" value="<?=$sch_id?>">
                                                                        <input type="hidden" id="admin" name="admin" value="1">
                                                                        <input type="hidden" id="item_type" value="fee_reports">
                                                                    </div>
                                                                </div>
                                                            </div> 
                                                            
                                                            <hr>
                                                            
                                                             <div class="form-group text-center">
                                                                 <div class="row">
                                                                    <div class="col-sm-4"></div>
                                                                    <div class="col-sm-8">
                                                                    	<button class="btn btn-primary btn-lg btn-block">Show Report</button>
                                                                    </div>
                                                                 </div>
                                                             </div>  
                                                            
                                                            <div class="clearfix"></div>
                                                            
                                                        </form>
                                                        
                                                </div>                                                
                    
                                            </div>
                                            
                                        </div>
                                            
                                        </div>
                                        
                                    </div>
                                    
                                </div>
                                
                           </div>
                           
                           <div class="col-md-7">
                           
                           		<!-- Tabbable Widget -->
                                <div class="tabbable paper-shadow relative" data-z="0.5">
                                
                                    <!-- Tabs -->
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a href="#details" data-toggle="tab"><i class="fa fa-fw fa-bars"></i> <span class="hidden-sm hidden-xs">Fees Report</span></a></li>
                                    </ul>
                                    <!-- // END Tabs -->
                                
                                
                                    <!-- Panes -->
                                    <div class="tab-content">
                                                                                                    
                                        <div id="details" class="tab-pane active">
                                                                            
                                           <div class="panel panel-default paper-shadow" data-z="0.5">
                                                                                           
                                                <div class="table-responsive contactsHeight2" id="table-responsive" data-tbl="sch_students" data-tbl-pk="id">
                                        
                                                    <table class="table table-condensed table-hover text-subhead v-middle" id="mybootgrid">
                                                        <thead>
                                                            <tr>
                                                                <th data-column-id="id" data-type="numeric" data-identifier="true" data-visible="false" data-sortable="true">ID</th>  
                                                                <th data-column-id="reg_no" data-sortable="true">Reg.</th>
                                                                <th data-column-id="name" data-sortable="true">Student</th>
                                                                <th data-column-id="current_class" data-sortable="true">Class</th>
                                                                <th data-column-id="year" data-sortable="true">Year</th>
                                                                <th data-column-id="paid_at" data-sortable="true">Paid At</th>
                                                                <th data-column-id="paid_by" data-sortable="true">By</th>
                                                                <th data-column-id="amount_fmt" data-sortable="true" data-align="right"  data-header-align="right">Amount</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>
                                                    
                                                </div>
                                                
                                                <hr>
                                                
                                                <div class="row hidden padding-20" id="report-summary">
                                                	<div class="col-sm-8">
                                                    	<div class="col-sm-12 text-right margin-top-10">
                                                            Total: <span id="report_total_amount">Ksh 100,000</span>
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="col-sm-4 save-report">
                                                    	<div class="pdf_box animate" id="show_pdf">
                                                            <img style="vertical-align:middle" src="<?=SITEPATH?>admin/images/pdf_icon_sm.png" height="30"> &nbsp;
                                                            Save Report
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                            
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