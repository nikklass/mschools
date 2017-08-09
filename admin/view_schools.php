<?php 
	include_once("api/includes/DB_handler.php"); 
	include_once("includes/funcs.php"); 
	include_once("api/includes/Config.php");
	
	$admin = true;
	//$show_bootstrap_dialog = true;
	
	$show_form = true;
	$form_validation = true; //form validation classes
	$show_popup = true; // show colorbox
	$show_file_upload = true; //show file upload css/ js
	$show_delete_images = true;
	
	$page_title = "Manage Schools";
	
	$db = new DbHandler();
	
	$show_table = true;
	$show_schools_list = true;
	
?>

<?php
	
	//echo "USER_ID ". USER_ID;
	$perms = ALL_SCHOOL_PERMISSIONS; 
	
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
		
		$item_data = $items['rows'][0];
		$sch_id = $item_data['id'];
		$top_sch_name = $item_data['name'];	 //echo "id - $id";	exit;												
	
	}
	
	//print_r($items); exit;
		
	$top_sch_id = $sch_id;
		
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
                            	<h1 class="text-display-2"><?=$page_title?></h1>
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
                                        <li class="active"><a href="#school_listing" data-toggle="tab"><i class="fa fa-fw fa-bars"></i> <span class="hidden-sm hidden-xs">Schools Listing</span></a></li>
                                        <li><a href="#new_school" data-toggle="tab"><i class="fa fa-fw fa-credit-card"></i> <span class="hidden-sm hidden-xs">Add New School</span></a></li>
                                    </ul>
                                    <!-- // END Tabs -->
                                    
                                    <!-- Panes -->
                                    <div class="tab-content">
                                                                                                    
                                        <div id="school_listing" class="tab-pane active">
                                    
                                            <div class="panel panel-default paper-shadow" data-z="0.5" id="contactsHeight2">
                                                
                                                <div class="table-responsive" id="table-responsive" data-tbl="sch_ussd" data-tbl-pk="sch_id">
                                                
                                                    <table class="table table-condensed table-hover text-subhead v-middle" id="mybootgrid">
                                                        <thead>
                                                            <tr>
                                                                <th data-column-id="sch_id" data-type="numeric" data-identifier="true" data-sortable="true">ID</th>  
                                                                <th data-column-id="sch_name" data-sortable="true">School Name</th>
                                                                <th data-column-id="sch_first_name" data-sortable="true">First Name</th>
                                                                <th data-column-id="category_name" data-sortable="true">Category</th>
                                                                <th data-column-id="province_name" data-sortable="true">Province</th>
                                                                <th data-column-id="status" data-formatter="status-links">Status</th>
                                                                <!--<th data-column-id="links" data-formatter="links" data-sortable="false">Edit</th>-->
                                                                <th data-column-id="commands" data-formatter="commands" data-sortable="false">Delete</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                           
                                                          
                                                        </tbody>
                                                    </table>
                                                    
                                                </div>
                    
                                            </div>
                                            
                                        </div>
                                        
                                        <div id="new_school" class="tab-pane">
                            
                                            <form class="form-horizontal form-new-school inputform" data-parsley-validate>
                                                            
                                                <div class="resultdiv"></div>
                                                
                                                <div class="form-group">
                                                    <label for="sch_name" class="col-sm-3 control-label">School Name</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" name="sch_name" placeholder="School Name" data-parsley-trigger="change" required>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="sch_first_name" class="col-sm-3 control-label">School First Name</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" name="sch_first_name" placeholder="School First Name" data-parsley-trigger="change" required>
                                                    </div>
                                                </div>
                                                <!--
                                                <hr>
                                                
                                                <div class="form-group">
                                                    <label for="paybill_no" class="col-sm-3 control-label">Paybill Number</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" name="paybill_no" placeholder="Paybill Number">
                                                    </div>
                                                </div>-->
                                                
                                                <hr>
                                                
                                                <div class="form-group">
                                                    <label for="sch_level" class="col-sm-3 control-label">Level</label>
                                                    <div class="col-sm-9">
                                                        <select id="sch_level" name="sch_level" class="form-control" data-parsley-trigger="change" required>
                                                                                                        
                                                            <option value=''>Please select</option>
                                                            
                                                            <?php
                                                                    																
																$items = $db->getSchoolLevels();
                                                                                                                        
                                                                foreach ($items["rows"] as $key => $val) {
                                                                    $id = $val['id'];
                                                                    $name = $val['name'];
                                                                    echo "<option value='$id'>$name</option>";
                                                                } 
                                                            
                                                            ?>
                                                            
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-1 result"></div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="category" class="col-sm-3 control-label">Category</label>
                                                    <div class="col-sm-9">
                                                        <select id="category" name="sch_category" class="form-control" data-parsley-trigger="change" required>
                                                                                                        
                                                            <option value=''>Please select</option>
                                                            
                                                            <?php
                                                                    
																$items = $db->getSchoolCategories();
                                                                                                                        
                                                                foreach ($items["rows"] as $key => $val) {
                                                                    $id = $val['id'];
                                                                    $name = $val['name'];
                                                                    echo "<option value='$id'>$name</option>";
                                                                } 
                                                            
                                                            ?>
                                                            
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-1 result"></div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="province" class="col-sm-3 control-label">Province</label>
                                                    <div class="col-sm-9 col-md-3">
                                                        <select id="sch_province" name="sch_province" class="form-control" data-parsley-trigger="change" required>
                                                            
                                                            <option value=''>Please select</option>
                                                                                                        
                                                            <?php
                                                                    
																$items = $db->getProvinces();
                                                                                                                        
                                                                foreach ($items["rows"] as $key => $val) {
                                                                    $id = $val['id'];
                                                                    $name = $val['name'];
                                                                    echo "<option value='$id'>$name</option>";
                                                                } 
                                                            
                                                            ?>
                                                            
                                                        </select>
                                                    </div>
                                                    
                                                    <label for="county" class="col-sm-3 control-label">County</label>
                                                    <div class="col-sm-9 col-md-3">
                                                        <select id="select" name="sch_county" class="form-control">
                                                            
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
                                                
                                                <div class="form-group">
                                                    <label for="status" class="col-sm-3 control-label">Status</label>
                                                    <div class="col-sm-9">
                                                        <select id="select" name="status" class="form-control" data-parsley-trigger="change" required>
                                                                                                        
                                                            <?php
                                                                    
                                                                $items = $db->getStatuses(SCHOOL_STATUS_SECTION);
                                                                                                                        
                                                                foreach ($items["rows"] as $key => $val) {
                                                                    $id = $val['id'];
                                                                    $name = $val['name'];
                                                                    echo "<option value='$id'>$name</option>";
                                                                }
                                                            
                                                            ?>
                                                           
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <!--$extra, $sch_profile, $events_calender, $sms_welcome1, $sms_welcome2-->
                                                <div class="form-group">
                                                    <label for="motto" class="col-sm-3 control-label">School Motto</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" name="motto" placeholder="School Motto" data-parsley-trigger="change">
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="phone1" class="col-sm-3 control-label">Phone 1</label>
                                                    <div class="col-sm-9 col-md-3">
                                                        <input type="text" class="form-control" name="phone1" placeholder="Phone 1" data-parsley-trigger="change">
                                                    </div>
                                                
                                                    <label for="phone2" class="col-sm-3 control-label">Phone 2</label>
                                                    <div class="col-sm-9 col-md-3">
                                                        <input type="text" class="form-control" name="phone2" placeholder="Phone 2" data-parsley-trigger="change">
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="sms_welcome1" class="col-sm-3 control-label">SMS Welcome 1</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" name="sms_welcome1" placeholder="SMS Welcome 1" data-parsley-trigger="change">
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="sms_welcome2" class="col-sm-3 control-label">SMS Welcome 2</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" name="sms_welcome2" placeholder="SMS Welcome 2" data-parsley-trigger="change">
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="address" class="col-sm-3 control-label">Address</label>
                                                    <div class="col-sm-9">
                                                        <textarea class="form-control" rows="4" name="address" placeholder="Address" data-parsley-trigger="change"></textarea>
                                                    </div>
                                                </div>
                                                
                                                <!--<div class="form-group">
                                                    <label for="multiple-images" class="col-sm-3 control-label">Product Images <br>(Hold CRTL to select multiple)<br>Recommended: 800 X 530</label>
                                                    <div class="col-sm-9">
                                                        <div class="bs-example">
                                                            <label class="control-label">Select File</label>
                                                            <input id="multiple-images" name="multiple-images[]" type="file" multiple class="file-loading">
                                                        </div>
                                                    </div>
                                                </div>-->
                                                
                                                <div class="form-group">
                                                    
                                                    <div class="col-sm-3"></div>
                                                    <div class="col-sm-9">
                                                    <button class="btn btn-lg btn-block btn-primary">Submit</button>
                                                    </div>
                                                    
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
                                        <li class="active"><a href="#main" data-toggle="tab"><i class="fa fa-fw fa-bars"></i> <span class="hidden-sm hidden-xs">School Details</span></a></li>
                                        <li><a href="#logo" data-toggle="tab"><i class="fa fa-fw fa-credit-card"></i> <span class="hidden-sm hidden-xs">School Logo</span></a></li>
                                        <li><a href="#login" data-toggle="tab"><i class="fa fa-fw fa-lock"></i> <span class="hidden-sm hidden-xs">Set Login Password</span></a></li>
                                    </ul>
                                    <!-- // END Tabs -->
                                
                                
                                    <!-- Panes -->
                                    <div class="tab-content">
                                                            
                                        <div class="no-results">Please Select School to Begin</div>
                                        
                                        <div id="main" class="tab-pane active">
                                                                            
                                            <div class="item-details hidden">
                                            
                                               <form class="form-horizontal form-edit-school inputform">
                                                                                                
                                                    <div class="form-group">
                                                        <label for="sch_name" class="col-sm-3 control-label">School Name</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" name="sch_name" id="sch_name_edit">
                                                            <input id="id_edit" name="id" type="hidden">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label for="sch_first_name" class="col-sm-3 control-label">School First Name</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" name="sch_first_name" id="sch_first_name_edit">
                                                        </div>
                                                    </div>
                                                    
                                                    <!--<hr>
                                                
                                                    <div class="form-group">
                                                        <label for="sch_paybill_no" class="col-sm-3 control-label">Paybill Number</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" name="sch_paybill_no" id="sch_paybill_no_edit">
                                                        </div>
                                                    </div>-->
                                                    
                                                    <hr>
                                                    
                                                    <div class="form-group">
                                                        
                                                        <label for="sch_level" class="col-sm-3 control-label">Level</label>                                                        
                                                        <div class="col-sm-9">
                                                        	
                                                            <select id="sch_level_edit" name="sch_level" class="form-control" data-parsley-trigger="change" required>
                                                                                                        
                                                                <option value=''>Please select</option>
                                                                
                                                                <?php
                                                                                                                                        
                                                                    $items = $db->getSchoolLevels();
                                                                                                                            
                                                                    foreach ($items["rows"] as $key => $val) {
                                                                        $id = $val['id'];
                                                                        $name = $val['name'];
                                                                        echo "<option value='$id'>$name</option>";
                                                                    } 
                                                                
                                                                ?>
                                                                
                                                            </select>
                                                            
                                                      </div>

                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        
                                                        <label for="sch_category" class="col-sm-3 control-label">Category</label>                                                        
                                                        <div class="col-sm-9">
                                                        	
                                                            <select id="sch_category_edit" name="sch_category" class="form-control">
                                                                                                        
                                                                <option value=''>Please select</option>
                                                                
                                                                <?php
                                                                        
                                                                    $items = $db->getSchoolCategories();
                                                                                                                            
                                                                    foreach ($items["rows"] as $key => $val) {
                                                                        $id = $val['id'];
                                                                        $name = $val['name'];
                                                                        echo "<option value='$id'>$name</option>";
                                                                    } 
                                                                
                                                                ?>
                                                                
                                                            </select>
                                                            
                                                      </div>

                                                    </div>
                                                    
                                                    <div class="form-group">
                                                    
                                                        <label for="province" class="col-sm-3 control-label">Province</label>
                                                        <div class="col-sm-9">
                                                            
                                                            <select id="province_edit" name="province" class="form-control">
                                                            
                                                                <option value=''>Please select</option>
                                                                                                            
                                                                <?php
                                                                        
                                                                    $items = $db->getProvinces();
                                                                                                                            
                                                                    foreach ($items["rows"] as $key => $val) {
                                                                        $id = $val['id'];
                                                                        $name = $val['name'];
                                                                        echo "<option value='$id'>$name</option>";
                                                                    } 
                                                                
                                                                ?>
                                                                
                                                            </select>
                                                            
                                                        </div>

                                                    </div>
                                                    
                                                    <div class="form-group">
                                                    
                                                        <label for="sch_county" class="col-sm-3 control-label">County</label>
                                                        <div class="col-sm-9">
                                                            <select id="sch_county_edit" name="sch_county" class="form-control">
                                                                
                                                                <option value=''>Please select</option>
                                                                    
																<?php
                                                                        
                                                                    $items = $db->getCounties();
																	//print_r($items);
                                                                                                                            
                                                                    foreach ($items["rows"] as $key => $val) {
                                                                        $id = $val['id'];
                                                                        $name = $val['name'];
                                                                        echo "<option value='$id'>$name</option>";
                                                                    }
                                                                
                                                                ?>
                                                                
                                                            </select>
                                                            
                                                            
                                                        </div>

                                                    </div>
                                                    
                                                    <div class="form-group">
                                                    
                                                        <label for="status" class="col-sm-3 control-label">Status</label>
                                                        <div class="col-sm-9">
                                                            
                                                            <select id="status_edit" name="status" class="form-control">
                                                                                                        
																<?php
                                                                        
                                                                    $items = $db->getStatuses(SCHOOL_STATUS_SECTION);
                                                                                                                            
                                                                    foreach ($items["rows"] as $key => $val) {
                                                                        $id = $val['id'];
                                                                        $name = $val['name'];
                                                                        echo "<option value='$id'>$name</option>";
                                                                    }
                                                                
                                                                ?>
                                                               
                                                            </select>
                                                        
                                                        </div>

                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label for="motto" class="col-sm-3 control-label">School Motto</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" name="motto" id="motto_edit">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label for="phone1" class="col-sm-3 control-label">Phone 1</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" name="phone1" id="phone1_edit">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label for="phone2" class="col-sm-3 control-label">Phone 2</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" name="phone2" id="phone2_edit">                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label for="sms_welcome1" class="col-sm-3 control-label">SMS Welcome 1</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" name="sms_welcome1" id="sms_welcome1_edit">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label for="sms_welcome2" class="col-sm-3 control-label">SMS Welcome 2</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" name="sms_welcome2" id="sms_welcome2_edit">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label for="address" class="col-sm-3 control-label">Address</label>
                                                        <div class="col-sm-9">
                                                            <textarea class="form-control" rows="4" name="address" id="address_edit"></textarea>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label for="sch_profile" class="col-sm-3 control-label">School Profile</label>
                                                        <div class="col-sm-9">
                                                            <textarea class="form-control" rows="4" name="sch_profile" id="sch_profile_edit"></textarea>
                                                        </div>
                                                    </div>
                                                                                                       
                                                    <div class="form-group margin-top-30">
                                                        
                                                        <div class="col-sm-3"></div>
                                                        <div class="col-sm-9">
                                                        <button class="btn btn-lg btn-primary btn-block">Save Changes</button>
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
                                                    
                                                    <form enctype="multipart/form-data" method="post" class="form-upload-pics">
                                                        
                                                        <div class="resultdiv"></div>
                                                        
                                                        <div class="wrapper_form">
                                                            <div class="form-group">
                                                                <label for="multiple-images" class="col-sm-12 control-label">Recommended Logo Size: <?=SQUARE_IMAGE_WIDTH?> X <?=SQUARE_IMAGE_HEIGHT?></label>
                                                                <div class="col-sm-12">
                                                                    <div class="bs-example">
                                                                        <label class="control-label">Select File</label>
                                                                        <input id="multiple-images" name="multiple-images[]" type="file" multiple class="file-loading">
                                                                        <input name="item_title" id="item_title_logo" type="hidden">
                                                                        <input name="category" id="category_logo" value="" type="hidden">
                                                                        <input name="category_id" id="category_id_logo" type="hidden">
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
                                        
                                        <div id="login" class="tab-pane">
                                                                            
                                            <div class="item-details hidden">
                                            
                                               <div id="title"></div>
                                               
                                               <form class="form-horizontal form-create-password inputform">
                                                    
                                                    <h4>Set Login Password</h4>
                                                    
                                                    <hr>
                                                                                              
                                                    <div id="wrapper_form">
                                                    
                                                        <div class="form-group">
                                                            <label for="" class="col-sm-3 control-label">School</label>
                                                            <div class="col-sm-9">
                                                                <div class="form-control textbox-noborder" id="sch_name_edit2">My School</div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="form-group">
                                                            <label for="password" class="col-sm-3 control-label">Password</label>
                                                            <div class="col-sm-9">
                                                                <input type="password" class="form-control" name="password">
                                                                <input id="id_edit2" name="sch_id" type="hidden">
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="form-group">
                                                            <label for="password2" class="col-sm-3 control-label">Repeat Password</label>
                                                            <div class="col-sm-9">
                                                                <input type="password" class="form-control" name="password2">
                                                            </div>
                                                        </div>
                                                       
                                                       
                                                        <div class="form-group margin-top-30">
                                                            
                                                            <div class="col-sm-3"></div>
                                                            <div class="col-sm-9">
                                                            <button class="btn btn-lg btn-primary btn-block">Save</button>
                                                            </div>
                                                            
                                                        </div>
                                                    
                                                    </div>
                                                   
                                                </form>
                                                                                            
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