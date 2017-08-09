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
	$show_fees_list = true;
	
	$show_scroll = true;
	
?>

<?php
	
	$perms = ALL_FEE_PERMISSIONS; 
	
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
	
	$page_title = "Manage Fees - $top_sch_name";
	
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
                                        
                                            <h4>Filter Fee Payments</h4>
                                            
                                            <hr>
                                                                                                                                                            
                                            <div id="sms-query">
                                              
                                                <div class="form-group">
                                                                
                                                        <div class="row">
                                                            
                                                            <label for="current_class" class="col-sm-1 control-label text-right">Class</label>
                                                            <div class="col-sm-3">
                                                                
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
                                                            
                                                            <input type="hidden" id="sch_id" value="<?=$sch_id?>">
                                                            
                                                            <label for="stream" class="col-sm-1 control-label text-right">Stream</label>
                                                            <div class="col-sm-3">
                                                                
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
                                                            
                                                            <label for="year" class="col-sm-1 control-label text-right">Year</label>
                                                            <div class="col-sm-3">
                                                                
                                                                <select id="year" name="year" class="form-control selectpicker">
                                                                    
                                                                    <!--<option value="">All</option>-->
                                                                    
                                                                    <?php
                                                                    
																		$items = $db->getYearData();
																																
																		foreach ($items as $key => $val) {
																			$id = $val['id'];
																			$name = $val['name'];
																			echo "<option value='$id'>$name</option>";
																		}
																	
																	?>
                                                                                                                                         
                                                                </select>
                                                                
                                                                <input id="item_type" type="hidden" value="fee_reports">
                                                                <input id="user_id" type="hidden" value="<?=USER_ID?>">
                                                                <input id="admin" type="hidden" value="1">
                                                                
                                                            </div>
                                                            
                                                        </div>
                                                    
                                                    </div>
                                            
                                            </div>
                                                                                                                                                    
                                        </form>
                                        
                                        <hr>
                                        
                                        <div class="text-center">
                                            <a href="" class="btn btn-success export_excel" data-item-type="fee_reports"><i class="fa fa-file-excel-o"></i> Export Data To Excel</a>
                                        </div>
                                    
                                    </div>
                                                
                                </div>
                                
                                <div class="clearfix"></div>
                                
                                
                                
                                <!-- Tabbable Widget -->
                                <div class="tabbable paper-shadow relative" data-z="0.5">
                                
                                    <!-- Tabs -->
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a href="#item_listing2" data-toggle="tab"><i class="fa fa-fw fa-bars"></i> <span class="hidden-sm hidden-xs">Student Fee Payments</span></a></li>
                                        <li><a href="#new_fee_item" data-toggle="tab"><i class="fa fa-fw fa-plus"></i> <span class="hidden-sm hidden-xs">Add Single Fee Payment</span></a></li>
                                        <li><a href="#new_bulk2" data-toggle="tab"><i class="fa fa-fw fa-plus"></i> <span class="hidden-sm hidden-xs">Bulk Upload Fee Payments</span></a></li>
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
                                                                    <th data-column-id="fees_bal_fmt" data-sortable="true">Balance</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody></tbody>
                                                        </table>
                                                                                                            
                                                    </div>
                                                
                                                   
                                                    
                                                    
                                                </div>
                    
                                            </div>
                                            
                                        </div>
                                        
                                        
                                        <div id="new_fee_item" class="tab-pane">

                                            <div class="panel panel-default paper-shadow" data-z="0.5">
                                                
                                                <div class="table-responsive">
                                                        
                                                   <div class="panel-body">

                                                        <form class="form-horizontal form-new-fee inputform"  data-parsley-validate>
                                                        
                                                            <div id="wrapper_form">

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
                                                                        </div>
                                                                    </div>
                                                                
                                                                </div>
                                                                                                                
                                                                <div class="form-group">
                                                                        
                                                                        <div class="row col-data">
                                                                            <label for="year" class="col-sm-3 control-label">Fee Year</label>
                                                                            <div class="col-sm-9">
                                                                                <select id="fee_year" name="fee_year" class="form-control selectpickerz">
                                                                        
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
                                                                        
                                                                    </div>
                                                                
                                                                <hr>
                                                                
                                                                <div class="form-group">
                                                                    
                                                                    <div class="row col-data">
                                                                        <label for="payment_date" class="col-sm-3 control-label">Payment Date</label>
                                                                        <div class="col-sm-9">
                                                                            
                                                                            <div class="input-group date">
                                                                              <input type="text" readonly class="form-control datepicker" name="payment_date" id="payment_date_item">
                                                                              <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                                                            </div>
                    
                                                                        </div>
                                                                    </div>
                                                                    
                                                                </div>
                                                                
                                                                <div class="form-group">
                                                                    
                                                                    <div class="row col-data">
                                                                        <label for="amount" class="col-sm-3 control-label">Amount</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="text" class="form-control numbersOnly" name="amount" id="amount_item" data-parsley-trigger="change" required>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                </div>
                                                                
                                                                <div class="form-group">
                                                                    
                                                                    <div class="row col-data">
                                                                        <label for="paid_by" class="col-sm-3 control-label">Paid By</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="text" class="form-control" name="paid_by" id="paid_by_item" data-parsley-trigger="change" required>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                </div>
                                                                
                                                                <input type="hidden" class="form-control" name="sch_id" value="<?=$top_sch_id?>">
                                                                <input type="hidden" class="form-control" name="user_id" value="<?=USER_ID?>">
                                                                
                                                                <div class="form-group">
                                                                    
                                                                    <div class="row col-data">
                                                                        <label for="payment_mode" class="col-sm-3 control-label">Payment Mode</label>
                                                                        <div class="col-sm-9">
                                                                            <select name="payment_mode" id="payment_mode_item" class="form-control selectpickerz" data-parsley-trigger="change" required>
                                                                    
                                                                                <!--<option value=''>Please select</option>-->
                                                                                
                                                                                <?php
                                                                                        
                                                                                    $items = $db->getPaymentModes(); 
                                                                                                                                            
                                                                                    foreach ($items["rows"] as $key => $val) {
                                                                                        $id = $val['code'];
                                                                                        $name = $val['name'];
                                                                                        echo "<option value='$id'>$name</option>";
                                                                                    }
                                                                                
                                                                                ?>
                                                                                
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                </div>                                                    
                                                                
                                                                <div class="form-group">
                                                                   <div class="row col-data"> 
                                                                        <div class="col-sm-3"></div>
                                                                        <div class="col-sm-9">
                                                                            <button class="btn btn-lg btn-primary btn-block">Submit</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            
                                                            </div>
                                                                                                                    
                                                        </form>
                                                        
                                                   </div>
                                                    
                                                </div>
                    
                                            </div>
                                            
                                        </div>
                                        
                                        <div id="new_bulk2" class="tab-pane">
                                       
                                           <form enctype="multipart/form-data" method="post" class="form-upload-fees">
                                                
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
                                                    <input id="noupload" name="fee_file" type="file" class="myfile">
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <div class="col-sm-3"></div>
                                                    <div class="col-sm-9">
                                                        <div class="padding-20"><i class="fa fa-2x fa-file-excel-o text-success"></i> &nbsp;&nbsp;<a href="<?=SITEPATH?>sample_files/fees_upload.csv">Excel Fees Upload Template (CLICK to download)</a></div>
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
                                        <li class="active"><a href="#details" data-toggle="tab"><i class="fa fa-fw fa-bars"></i> <span class="hidden-sm hidden-xs">Fee Summary</span></a></li>
                                        <li><a href="#item-history" data-toggle="tab"><i class="fa fa-fw fa-credit-card"></i> <span class="hidden-sm hidden-xs">Fee Payment History</span></a></li>
                                        <!--<li><a href="#photos" data-toggle="tab"><i class="fa fa-fw fa-credit-card"></i> <span class="hidden-sm hidden-xs">Fees Summary</span></a></li>-->
                                    </ul>
                                    <!-- // END Tabs -->
                                
                                
                                    <!-- Panes -->
                                    <div class="tab-content">
                                                                                                    
                                        <div class="no-results">Please Select Student to Begin</div>                                                                                               
                                                                                               
                                        <div id="details" class="tab-pane active">
                                                                            
                                            <div class="item-details hidden">
                                                                                                
                                                <div id="fees-container">
                                                
                                                    <div class="container-fluid">
                                                                            
                                                        <div class="panel panel-default text-center login-form-bg" data-z="0.5">
                                                        
                                                        <h3 class="text-display-1 text-center margin-bottom-none" id="student_name_right">Fees Summary</h3>
                                                        <hr>
                                                        <div class="panel-body">
                                                            
                                                            <div class="table-responsive" id="table-data" data-tbl="sch_fees_payments" data-tbl-pk="id">
                                                            
                                                                <table class="large-text table">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td>Opening Balance</td>
                                                                            <td>Fees Paid</td>
                                                                            <td>Outstanding Balance</td>
                                                                        </tr>
                                                                        
                                                                        <tr>
                                                                            <td class="text-info" id="fees_total">0</td>
                                                                            <td class="text-info" id="fees_paid">0</td>
                                                                            <td class="text-success" id="fees_balance">0</td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            
                                                            </div>
                                                        
                                                        </div>
                                                        
                                                    </div>
                                                
                                                    </div>
                                                    
                                                    <div class="container-fluid">
                                                                            
                                                        <div class="panel panel-default paper-shadow" data-z="0.5" id="papershadow">

                                                            <table id="fees-list" class="table table-striped table-responsive" width="100%" cellspacing="0" role="grid" style="width: 100%;">
                                                                
                                                                <thead>
                                                                    
                                                                    <th>ID</th>
                                                                    <th align="right" class="text-right">Amount (Ksh)</th>
                                                                    <th>Mode</th>
                                                                    <th>Paid At</th>
                                                                    <th>Paid By</th>
                                                                    <th>Edit</th>
                                                                    <th>Delete</th>
                                                                    
                                                                </thead>
                                                                
                                                                <tbody id="fees-data"></tbody>
                                                                
                                                                
                                                            </table>
                                                        
                                                        </div>
                                                        
                                                        <hr>
                                                        
                                                        <input type="hidden" id="item_type" value="single_fee">
                                                        <input type="hidden" id="fee_id">
                                                        <input type="hidden" id="admin" value="1">
                                                    
                                                        <div class="row hidden" id="report-summary">
                                                            <div class="col-sm-8">
                                                                <div class="col-sm-12 text-right margin-top-10">
                                                                    <span id="report_total_amount"></span>
                                                                </div>
                                                                
                                                            </div>
                                                            <div class="col-sm-4 save-report">
                                                                <div class="pdf_box animate show_pdf" data-item-type="single_fee">
                                                                    <img style="vertical-align:middle" src="<?=SITEPATH?>admin/images/pdf_icon_sm.png" height="30"> &nbsp;
                                                                    Save Report
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="clearfix"></div>                                                        
                                                
                                                    </div>
                                                    
                                                </div>
                                                
                                                
                                                <div style='display:none'>
                                                    
                                                    <form class="form-horizontal form-edit-fee inputform" id="edit_fee_record">
                                                                                                                          
                                                            <div class="form-group">
                                                            
                                                                <div class="col-sm-3"></div>
                                                                <div class="col-sm-9">
                                                                    <h3>Edit Fees</h3>
                                                                </div>
                                                            
                                                            </div>
                                                                                                        
                                                            <div class="form-group">
                                                                <div class="col-sm-12">
                                                                    <div class="resultdiv"></div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label for="fee_amount" class="col-sm-3 control-label">Amount</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control" name="fee_amount" id="fee_amount">
                                                                    <input type="hidden" name="fee_payment_id" id="fee_payment_id">
                                                                </div>
                                                            </div>
                                                           
                                                            <div class="form-group">
                                                                <label for="score" class="col-sm-3 control-label">Payment Mode</label>
                                                                <div class="col-sm-9">
                                                                    <select id="fee_payment_mode" name="fee_payment_mode" class="form-control selectpickerz">
                                                                        
                                                                        <?php
                                                                                
                                                                            //get the user types
                                                                            $query = "SELECT code, name FROM payment_modes ORDER BY name";
                                                                            $stmt = $db->conn->prepare($query);
                                                                            $stmt->execute();
                                                                            /* bind result variables */
                                                                            $stmt->bind_result($id, $name);
                                                                            
                                                                            while ($stmt->fetch()) 
                                                                            {
                                                                                echo "<option value='$id'>$name</option>";
                                                                            } 
                                                                        
                                                                        ?>
                                                                        
                                                                    </select>
                                                                </div>  
                                                            </div>
                                                                                                           
                                                            <div class="form-group">
                                                                <label for="fee_paid_by" class="col-sm-3 control-label">Paid By</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control" name="fee_paid_by" id="fee_paid_by">
                                                                </div>
                                                            </div>  
                                                            
                                                            <div class="form-group">
                                                                <label for="fee_paid_at" class="col-sm-3 control-label">Payment Date</label>
                                                                <div class="col-sm-9">
                                                                    <div class="input-group date">
                                                                      <input type="text" readonly class="form-control datepicker" name="fee_paid_at" id="fee_paid_at">
                                                                      <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                                                    </div>
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
                                                                                            
                                            </div>
                                            
                                        </div>
                                        
                                        <div id="item-history" class="tab-pane">
                                                                        
                                            <div class="no-results">Please Select Student to Begin</div>  
                                            
                                            <div class="item-details hidden">
                                            
                                                <div class="col-sm-12">
                                                        
                                                   <div class="table-responsive" id="fee-item-history">
                                                
                                                        <table class="table table-condensed table-hover text-subhead v-middle" id="mybootgrid-history">
                                                            <thead>
                                                                <tr>
                                                                    <th data-column-id="id" data-type="numeric" data-identifier="true" data-sortable="true" data-visible="false">ID</th>
                                                                    <th data-column-id="fees_payments_id" data-type="numeric" data-sortable="true" data-visible="true">ID</th>  
                                                                    <th data-column-id="name" data-sortable="true">Student</th>
                                                                    <th data-column-id="payment_amount_fmt" data-sortable="true" data-align="right" data-header-align="right">Amount</th>
                                                                    <th data-column-id="payment_paid_at_fmt" data-sortable="true">Paid at</th>
                                                                    <th data-column-id="payment_mode" data-sortable="true">Mode</th>
                                                                    <th data-column-id="payment_created_at_fmt2" data-sortable="true">Updated</th>
                                                                    <th data-column-id="payment_created_by" data-sortable="true">By</th>
                                                                    
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
                                                                    
                <h3>Fees Upload Results</h3>
                
                <hr>
               
                <div id="fee_upload_results">
                    
                    
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