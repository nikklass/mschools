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
	
	$show_table = true;
	$show_fees_list2 = true;
	
?>

<?php
	
	$perms = ALL_FEE_PERMISSIONS; 
	
	if (!SUPER_ADMIN_USER) {

		$company_ids = $db->getUserCompanyIds(USER_ID, $perms); //echo "co ids - ". $company_ids; exit;
		
	}
	
	if ($_GET["sch_id"]) {
		
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
		//print_r($items);
		
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
		$page = SITEPATH."error";
		header("Location: $page"); 
		exit();
	} //echo "user_id - $user_id, $est_id, $perms";
	
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
                        
                        <div class="row">
                            
                            <div class="col-md-6">
                            
                                <!-- Tabbable Widget -->
                                <div class="tabbable paper-shadow relative" data-z="0.5">
                                
                                    <!-- Tabs -->
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a href="#item_listing" data-toggle="tab"><i class="fa fa-fw fa-bars"></i> <span class="hidden-sm hidden-xs">Recent Fees</span></a></li>
                                        <li><a href="#new_item" data-toggle="tab"><i class="fa fa-fw fa-plus"></i> <span class="hidden-sm hidden-xs">Add Single Fee</span></a></li>
                                        <li><a href="#new_bulk" data-toggle="tab"><i class="fa fa-fw fa-plus"></i> <span class="hidden-sm hidden-xs">Bulk Upload Fees</span></a></li>
                                    </ul>
                                    <!-- // END Tabs -->
                                    
                                    <!-- Panes -->
                                    <div class="tab-content">
                                    
                                    	<div id="item_listing" class="tab-pane active">

                                            <div class="panel panel-default paper-shadow" data-z="0.5">
                                                
                                                <div id="top_school_id" data-sch-id="<?=$sch_id?>"></div>
                                                
                                                <div class="form-group padding-20-all">
                                                    <form>
                                                        <select id="school-select" name="sch_id" class="form-control">
                                                                                                        
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
                                                                <!--<th data-column-id="reg_no" data-sortable="true">Reg. No</th>-->
                                                                <th data-column-id="payment_amount_fmt" data-sortable="true" data-align="right" data-header-align="right">Amount</th>
                                                                <th data-column-id="payment_paid_at_edit" data-sortable="true">Payment Date</th>
                                                                <th data-column-id="payment_mode" data-sortable="true">Mode</th>
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
                                        
                                        	<form class="form-horizontal form-new-fees inputform" data-parsley-validate>
                                                        
                                                <div class="resultdiv"></div>
                                                                                                
                                                <div id="wrapper_form">
                                                                                                     
                                                    <div class="form-group">
                                                    
                                                        <div class="row col-data">
                                                            <label for="student" class="col-sm-3 control-label">Student</label>
                                                            <div class="col-sm-9">
                                                                <select id="student" name="student_id" class="form-control selectpickerz">
                                                        
                                                                    <option value=''>Please select</option>
                                                                    
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
                                                                <select id="year" name="fee_year" class="form-control selectpickerz">
                                                        
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
                                                                                                        
                                                    <div class="form-group">
                                                        
                                                        <div class="row col-data">
                                                            <label for="payment_date" class="col-sm-3 control-label">Payment Date</label>
                                                            <div class="col-sm-9">
                                                                
                                                                <div class="input-group date">
                                                                  <input type="text" readonly class="form-control datepicker" name="payment_date">
                                                                  <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                                                </div>
        
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        
                                                        <div class="row col-data">
                                                            <label for="amount" class="col-sm-3 control-label">Amount</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" class="form-control numbersOnly" name="amount" data-parsley-trigger="change" required>
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        
                                                        <div class="row col-data">
                                                            <label for="paid_by" class="col-sm-3 control-label">Paid By</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" class="form-control" name="paid_by" data-parsley-trigger="change" required>
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                    
                                                    <input type="hidden" class="form-control" name="sch_id" value="<?=$top_sch_id?>">
                                            		<input type="hidden" class="form-control" name="user_id" value="<?=USER_ID?>">
                                                    
                                                    <div class="form-group">
                                                        
                                                        <div class="row col-data">
                                                            <label for="payment_mode" class="col-sm-3 control-label">Payment Mode</label>
                                                            <div class="col-sm-9">
                                                                <select name="payment_mode" class="form-control selectpickerz" data-parsley-trigger="change" required>
                                                        
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
                                        
                                        <div id="new_bulk" class="tab-pane">
                                       
                                           <form enctype="multipart/form-data" method="post" class="form-upload-fees">
                                                
                                                <div class="form-group padding-20">
                                                    <div class="col-sm-3">
                                                        <label for="sch_name" class="control-label">&nbsp;</label>
                                                    </div>
                                                    <div class="col-sm-9">
                                                        <h4><?=$sch_name?></h4>
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
                                        <li class="active"><a href="#details" data-toggle="tab"><i class="fa fa-fw fa-bars"></i> <span class="hidden-sm hidden-xs">Fee Details</span></a></li>
                                        <li><a href="#item-history" data-toggle="tab"><i class="fa fa-fw fa-credit-card"></i> <span class="hidden-sm hidden-xs">Fee Item History</span></a></li>
                                        <li><a href="#photos" data-toggle="tab"><i class="fa fa-fw fa-credit-card"></i> <span class="hidden-sm hidden-xs">Fees Summary</span></a></li>
                                    </ul>
                                    <!-- // END Tabs -->
                                
                                
                                    <!-- Panes -->
                                    <div class="tab-content">
                                                            
                                        <div class="no-results">Please Select Fee Item to Begin</div>
                                        
                                        <div id="details" class="tab-pane active">
                                                                            
                                            <div class="item-details hidden">
                                            
                                               <form class="form-horizontal form-edit-fees inputform" data-parsley-validate>

                                                        <div class="form-group">
                                                    
                                                            <div class="row col-data">
                                                                <label for="student" class="col-sm-3 control-label">Student</label>
                                                                <div class="col-sm-9">
                                                                    <select id="student_name_edit" name="student_name" class="form-control selectpickerz">
                                                            
                                                                        <option value=''>Please select</option>
                                                                        
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
                                                                    <select id="year_edit" name="year" class="form-control selectpickerz">
                                                            
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
                                                        
                                                        <div class="form-group">
                                                        
                                                            <div class="row col-data">
                                                                <label for="payment_date" class="col-sm-3 control-label">Payment Date</label>
                                                                <div class="col-sm-9">
                                                                    
                                                                    <div class="input-group date">
                                                                      <input type="text" readonly class="form-control datepicker" name="fee_paid_at" id="payment_date_edit">
                                                                      <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                                                    </div>
            
                                                                </div>
                                                            </div>
                                                            
                                                        </div>
                                                                                                                                                                    
                                                        <div class="form-group">
                                                            <div class="row col-data">
                                                                <label for="payment_amount" class="col-sm-3 control-label">Amount</label>
                                                                <div class="col-sm-9">
                                                                    <input class="form-control" name="fee_amount" id="payment_amount_edit" data-parsley-trigger="change" required>
                                                                    <input class="form-control" type="hidden" name="fee_payment_id" id="id_edit">
                                                                    <input class="form-control" type="hidden" name="user_id" value="<?=USER_ID?>">
                                                                    
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="form-group">
                                                            <div class="row col-data">
                                                                <label for="payment_mode" class="col-sm-3 control-label">Payment Mode</label>
                                                                <div class="col-sm-9">
                                                                    <!--<input class="form-control" name="fee_payment_mode" id="payment_mode_edit" data-parsley-trigger="change" required>-->
                                                                    
                                                                    <select name="fee_payment_mode" id="payment_mode_edit" class="form-control selectpickerz" data-parsley-trigger="change" required>
                                                                                                                            
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
                                                                <label for="paid_by" class="col-sm-3 control-label">Paid By</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control" name="fee_paid_by" id="paid_by_edit" data-parsley-trigger="change" required>
                                                                </div>
                                                            </div>
                                                            
                                                        </div>
                                                        
                                                        <div class="form-group">
                                                            
                                                            <div class="col-sm-3"></div>
                                                            <div class="col-sm-9">
                                                            <button class="btn btn-lg btn-primary btn-block">Save Changes</button>
                                                            </div>
                                                            
                                                        </div>
                                                                                               
                                                </form>
                                                                                            
                                            </div>
                                            
                                        </div>
                                        
                                        <div id="item-history" class="tab-pane">
                                                                        
                                            <div class="item-details hidden">
                                            
                                                <div class="col-sm-12">
                                                        
                                                   <div class="table-responsive" id="fee-item-history">
                                                
                                                        <table class="table table-condensed table-hover text-subhead v-middle" id="mybootgrid-history">
                                                            <thead>
                                                                <tr>
                                                                    <th data-column-id="id" data-type="numeric" data-identifier="true" data-sortable="true" data-visible="false">ID</th>  
                                                                    <th data-column-id="name" data-sortable="true">Student</th>
                                                                    <th data-column-id="payment_amount_fmt" data-sortable="true" data-align="right" data-header-align="right">Amount</th>
                                                                    <th data-column-id="payment_paid_at_fmt" data-sortable="true">Paid at</th>
                                                                    <th data-column-id="payment_mode" data-sortable="true">Mode</th>
                                                                    <th data-column-id="payment_created_at" data-sortable="true">Updated</th>
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