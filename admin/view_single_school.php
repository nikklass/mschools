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
	
	$show_single_school = true;
	
	$show_file_upload = true; //show file upload css/ js
	$show_delete_images = true;
		
	$db = new DbHandler();
			
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
		
	$page_title = "Manage School - " . $top_sch_name;
		
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

                        
                        <div class=" col-md-12">
                        
                        	<!-- Tabbable Widget -->
                            <div class="tabbable paper-shadow relative" data-z="0.5">
                            
                                <!-- Tabs -->
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#main" data-toggle="tab"><i class="fa fa-fw fa-bars"></i> <span class="hidden-sm hidden-xs">About School</span></a></li>
                                    <li><a href="#activities" data-toggle="tab"><i class="fa fa-fw fa-bars"></i> <span class="hidden-sm hidden-xs">School Activities</span></a></li>
                                </ul>
                                <!-- // END Tabs -->
                            
                            
                                <!-- Panes -->
                                <div class="tab-content">
                                                        

                                    <div id="main" class="tab-pane active">
                                    	                                        
                                        <div class="col-sm-7">
                                        
                                            <div class="panel panel-default paper-shadow panel-grey" data-z="0.5">
                                            	
                                                <div class="padding-20">
                                                
                                                    <form class="form-horizontal form-edit-school">
                                                                                                    
                                                        <h4>School Details</h4>
                                                        
                                                        <hr>
                                                        
                                                        <div class="form-group">
                                                            <label for="sch_name" class="col-sm-3 control-label">School Name</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" class="form-control" name="sch_name" id="sch_name_edit">
                                                                <input id="id_edit" name="id" type="hidden">
                                                            </div>
                                                        </div>
                                                        
                                                        <!--<div class="form-group">
                                                            <label for="sch_first_name" class="col-sm-3 control-label">School First Name</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" class="form-control" name="sch_first_name" id="sch_first_name_edit">
                                                            </div>
                                                        </div>-->
                                                        
                                                        <!--<hr>
                                                    
                                                        <div class="form-group">
                                                            <label for="sch_paybill_no" class="col-sm-3 control-label">Paybill Number</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" class="form-control" name="sch_paybill_no" id="sch_paybill_no_edit">
                                                            </div>
                                                        </div>
                                                        -->
                                                        
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
                                                                        //print_r($items); exit;
                                                                                                                                
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
                                                        
                                                        <div class="form-group hidden">
                                                            <label for="sms_welcome1" class="col-sm-3 control-label">SMS Welcome 1</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" class="form-control" name="sms_welcome1" id="sms_welcome1_edit">
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="form-group hidden">
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
                                            
                                            <div class="clearfix"></div>
                               
                                        </div>
                                        
                                        <div class="panel panel-default paper-shadow col-sm-5" data-z="0.5">
                                        
                                            <div class="panel-spacing">
                                                                                            
                                                <div class="col-sm-12">
                                                
                                                	<h4>School Logo</h4>
                                                        
                                                    <hr>
                                                        
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
                                        
                                        <div class="clearfix"></div>
                                        
                                    </div>
                                    
                                    <div id="activities" class="tab-pane">
                                    	                                        
                                        <div class="col-sm-6">
                                        
                                            <div class="panel panel-default paper-shadow panel-grey" data-z="0.5">
                                            	
                                                <div class="margin-20">
                                                    
                                            		<form class="form-horizontal inputform">
                                                    
                                                        <h4>Filter Activities</h4>
                                                        
                                                        <hr>
                                                        
                                                        <input type="hidden" name="user_id" value="<?=USER_ID?>">
                                                        <input type="hidden" name="admin" value="1">
                                                                                                                
                                                        <div id="sms-query">
                                                          
                                                            <div class="form-group">
                                                            
                                                                <div class="row">
                                                                                                                                        
                                                                    <label for="start_date" class="col-sm-2 control-label">Start Date</label>
                                                                    <div class="col-sm-4">
                                                                        
                                                                        <div class="input-group date">
                                                                          <input type="text" readonly class="form-control datepicker" name="start_date" id="start_date">
                                                                          <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                                                        </div>
                                                                        <a href="#" id="clear_start_date" class="noclick hidden">Clear Date</a>
                                                                        
                                                                    </div>
                                                                    
                                                                    <label for="end_date" class="col-sm-2 control-label text-right">End Date</label>
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
                                                                                        
                                            <div class="panel panel-default paper-shadow" data-z="0.5">
                                            
                                                <div>
                                                
                                                    <div class="table-responsive contactsHeight2">
                                    
                                                        <table class="table table-condensed table-hover text-subhead v-middle" id="mybootgrid">
                                                            <thead>
                                                                <tr>
                                                                    <th data-column-id="id" data-type="numeric" data-identifier="true" data-visible="false" data-sortable="false">ID</th>  
                                                                    <th data-column-id="name" data-sortable="true">Activity</th>
                                                                    <th data-column-id="venue" data-sortable="true">Venue</th>
                                                                    <th data-column-id="start_at_fmt" data-sortable="true">Start Date</th>
                                                                    <th data-column-id="end_at_fmt" data-sortable="false" data-visible="false">End Date</th>
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
                                        
                                        <div class="col-sm-6">
                                                                    
                                            <!-- Tabbable Widget -->
                                            <div class="tabbable paper-shadow relative panel-grey-dark" data-z="0.5">
                                            
                                                <!-- Tabs -->
                                                <ul class="nav nav-tabs">
                                                    
                                                    <li class="active">
                                                        <a href="#activity-main" data-toggle="tab"><i class="fa fa-fw fa-bars"></i> 
                                                        <span class="hidden-sm hidden-xs">Activity Details</span></a>
                                                    </li>
                                                    <li>
                                                        <a href="#activity-photos" data-toggle="tab"><i class="fa fa-fw fa-file-image-o"></i> 
                                                        <span class="hidden-sm hidden-xs">Activity Photo(s)</span></a>
                                                    </li>
                                                    <li>
                                                        <a href="#new-activity" data-toggle="tab"><i class="fa fa-fw fa-plus"></i> 
                                                        <span class="hidden-sm hidden-xs">Add New Activity</span></a>
                                                    </li>
                                                    
													<!-- <li><a href="#activity-history" data-toggle="tab"><i class="fa fa-fw fa-bars"></i> 
                                                    <span class="hidden-sm hidden-xs">Activity History</span></a></li>-->                                                
                                                    
                                                </ul>
                                                <!-- // END Tabs -->
                                            
                                                <!-- Panes -->
                                                <div class="tab-content">
                                                                        
                                                    <div id="activity-main" class="tab-pane active">
                                                        
                                                        <div class="no-results2">Please Select Activity to Begin</div>
                                            
                                                        <div class="item-details2 hidden">
                                                        
                                                            <form class="form-edit-activity padding-no-top-20" data-parsley-validate>
                                                            
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <label for="name" class="col-sm-4 control-label">Activity Name</label>
                                                                        <div class="col-sm-8">
                                                                            <input id="name_edit2" type="text" name="name" class="form-control" data-parsley-trigger="change" required>
                                                                            <input id="id_edit2" name="id" type="hidden">
                                                                            <input name="user_id" type="hidden" value="<?=USER_ID?>">
                                                                            <input name="admin" type="hidden" value="1">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <label for="venue" class="col-sm-4 control-label">Venue</label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" id="venue_edit2" name="venue" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="form-group">
                                                                     <div class="row">
                                                                        <label for="description" class="col-sm-4 control-label">Description</label>
                                                                        <div class="col-sm-8">
                                                                            <textarea class="form-control" rows="4" name="description" id="description_edit2"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                                                                    
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <label for="start_at" class="col-sm-4 control-label">Start Date/ Time</label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" readonly class="form-control datetimepicker" name="start_at" id="start_at_edit2">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <label for="end_at" class="col-sm-4 control-label">End Date/ Time</label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" readonly class="form-control datetimepicker" name="end_at" id="end_at_edit2">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <hr>
                                                                
                                                                <div class="form-group text-center">
                                                                    <div class="row">
                                                                        <div class="col-sm-12">
                                                                            <button class="btn btn-primary btn-block btn-lg">Save Changes</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                            </form>
                                                        
                                                        </div>
                                                        
                                                    </div>
                                                    
                                                    <div id="activity-photos" class="tab-pane">
                                                    
                                                        <div class="no-results2">Please Select Activity to Begin</div>
                                            
                                                        <div class="item-details2 hidden">
                                                            
                                                            <div class="col-sm-12">
                                                                    
                                                                <!-- the avatar markup -->
                                                                <div id="kv-avatar-errors-1" class="center-block" style="display:none"></div>

                                                                <form enctype="multipart/form-data" method="post" class="form-upload-pics2">
                                                                                                                                        
                                                                    <div class="wrapper_form">
                                                                    
                                                                        <div class="form-group">
                                                                            <label for="multiple-images" class="col-sm-12 control-label">
                                                                            (Hold CRTL to select multiple) Recommended: <?=SQUARE_IMAGE_WIDTH?> X <?=SQUARE_IMAGE_HEIGHT?></label>
                                                                            <div class="col-sm-12">
                                                                                <div class="bs-example">
                                                                                    <label class="control-label">Select File</label>
                                                                                    <input id="multiple-images2" name="multiple-images[]" type="file" multiple class="file-loading">
                                                                                    <input name="item_title" id="item_title2" type="hidden">
                                                                                    <input name="category" id="category2" type="hidden">
                                                                                    <input name="category_id" id="category_id2" type="hidden">
                                                                                    <input name="image_crop_ratio" value="1:1" type="hidden">
                                                                                    <input name="image_width" id="image_width2" value="<?=SQUARE_IMAGE_WIDTH?>" type="hidden">
                                                                                    <input name="image_height" id="image_height2" value="<?=SQUARE_IMAGE_HEIGHT?>" type="hidden">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <?php 
                                                                            //if user has read permissions
                                                                            if (HAS_EDIT_SCHOOL_PERMISSION || SCHOOL_ADMIN_USER) 
                                                                            {
                
                                                                        ?>
                
                                                                            <div class="form-group">
                                                                                <div class="row">
                                                                                    <div class="col-sm-4 col-sm-offset-4">
                                                                                        <button class="btn btn-lg btn-primary btn-block btn-lg">Submit</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        
                                                                        <?php 
                                                                          
                                                                            }
                                                                            
                                                                        ?>
                
                                                                    </div>
                                                                
                                                                </form>
                                                            
                                                            </div>
                                                            
                                                            <hr>
                                                            
                                                            <div class="col-sm-12">
                                                                
                                                                <div id="table-data" data-tbl="images" data-tbl-pk="id">
                                                                    <div class="thumbnail" id="item-images2"></div>
                                                                </div>
                                                                
                                                            </div>
                                                            
                                                            <div class="clear"></div>
                                                        
                                                        </div>
                                                        
                                                    </div>
                                                    
                                                    <div id="new-activity" class="tab-pane">
                                                                                                                                                            
                                                        <form class="form-new-activity padding-no-top-20 inputform" data-parsley-validate>
                                                        
                                                            <div class="form-group">
                                                                <div class="row">
                                                                    <label for="name" class="col-sm-4 control-label">Activity Name</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" name="name" class="form-control" data-parsley-trigger="change" required>
                                                                        <input name="user_id" type="hidden" value="<?=USER_ID?>">
                                                                        <input name="sch_id" type="hidden" value="<?=$sch_id?>">
                                                                        <input name="admin" type="hidden" value="1">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <div class="row">
                                                                    <label for="venue" class="col-sm-4 control-label">Venue</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" name="venue" class="form-control">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                 <div class="row">
                                                                    <label for="description" class="col-sm-4 control-label">Description</label>
                                                                    <div class="col-sm-8">
                                                                        <textarea class="form-control" rows="4" name="description"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                                                                                
                                                            <div class="form-group">
                                                                <div class="row">
                                                                    <label for="start_at" class="col-sm-4 control-label">Start Date/ Time</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" readonly class="form-control datetimepicker" name="start_at">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <div class="row">
                                                                    <label for="end_at" class="col-sm-4 control-label">End Date/ Time</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" readonly class="form-control datetimepicker" name="end_at">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <hr>
                                                            
                                                            <div class="form-group text-center">
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <button class="btn btn-primary btn-block btn-lg">Save Changes</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                        </form>
                                                                                                                
                                                    </div>
                                                                                    
                                                </div>
                                                <!-- // END Panes -->
                                            
                                            </div>
                                            <!-- // END Tabbable Widget -->                                            
                                                                                                                                        
                                        </div>
                                        
                                        <div class="clearfix"></div>
                                        
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