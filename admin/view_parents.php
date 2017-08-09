<?php 
	
	include_once("api/includes/DB_handler.php"); 
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
	$show_sms_balance = true;
	$show_parents_list = true;
	$show_parents_sent_sms_list = true;
	$show_sms_textbox = true;
	
	//display selected items count
	$show_selected_items = true;
	
	$show_bootgrid_1_multiple = true;
	
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
	
	$page_title = "Manage Parents - $top_sch_name";
	
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
                        
                        <div class="col-sm-12">
                        
                        	<!-- Tabbable Widget -->
                            <div class="tabbable paper-shadow relative" data-z="0.5">
                            
                                <!-- Tabs -->
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#main" data-toggle="tab"><i class="fa fa-fw fa-bars"></i> <span class="hidden-sm hidden-xs">Parents Listing</span></a></li>
                                    <li><a href="#sent_requests" data-toggle="tab"><i class="fa fa-fw fa-bars"></i> <span class="hidden-sm hidden-xs">Sent Parent Requests</span></a></li>
                                </ul>
                                <!-- // END Tabs -->
                            
                            
                                <!-- Panes -->
                                <div class="tab-content">
                                                        
                                    <div id="main" class="tab-pane active">
                            
                                       <div class="col-md-7">
                                        
                                            <div class="panel panel-default paper-shadow" data-z="0.5">
                                                
                                                <div id="top_school_id" data-sch-id="<?=$sch_id?>"></div>
                                                
                                                <div class="panel panel-default paper-shadow" data-z="0.5" id="contactsHeight2">
                                                
                                                    <div class="table-responsive" id="table-responsive" data-tbl="sch_students" data-tbl-pk="id">
                                                    
                                                        <table class="table table-condensed table-hover text-subhead v-middle" id="mybootgrid">
                                                            <thead>
                                                                <tr>
                                                                    <th data-column-id="id" data-type="numeric" data-identifier="true" data-sortable="true" data-visible="false">ID</th>  
                                                                    <th data-column-id="name" data-sortable="true">Student Names</th>
                                                                    <th data-column-id="reg_no" data-sortable="true">Reg.</th>
                                                                    <th data-column-id="guardian_name" data-sortable="true">Parent Names</th>
                                                                    <th data-column-id="guardian_phone" data-sortable="true">Phone No.</th>
                                                                    <th data-column-id="status" data-formatter="status-links" data-sortable="false">Parent Account</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                              
                                                            </tbody>
                                                        </table>                                                        
                                                        
                                                    </div>
                                                
                                                </div>
                    
                                            </div>
                                                                                                                                                    
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

                                                    <div id="send-msg" class="tab-pane active">
                                                                                        
                                                        <div class="item-details">
                                                        
                                                           <form class="form-horizontal form-add-parent">

                                                                <div class="row bg-blue">
                                                                        <h4 class="padding-left-10 text-blue">Add Parent Account(s) / Send Message(s)</h4>
                                                                </div>
                                                                
                                                                <hr class="blue"> 
                                                                
                                                                <div class="row col-data">
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group">
                                                                                <div class="text-left padding-top-10 msg_text">Selected Parents: &nbsp; 
                                                                                    <span id="users_selected" class="bold_text text-success text-big">0</span> 
                                                                                </div>
                                                                                <input type="hidden" name="selected" id="selected">
                                                                                <input type="hidden" name="sch_id" value="<?=$sch_id?>">
                                                                           
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                         <div class="text-left padding-top-10 msg_text">
                                                                            SMS Balance: &nbsp; <span id="bulk_sms_balance2" class="bold_text text-success text-big">0</span> 
                                                                         </div>
                                                                    </div>
                                                                    
                                                                </div>
                                                        
                                                                
                                                                <hr class="small blue">
                                                                
                                                                <div id="wrapper_form">
                                                                                                                                          
                                                                    <div class="form-group">
                                                                        <div class="row">
                                                                            
                                                                            <div class="col-sm-12">
                                                                                <div class="radio radio-primary">
                                                                                    <input id="add_account" name="messageType" value="add_account" checked="checked" type="radio">
                                                                                    <label for="add_account"> Add Parent Account(s) And Send Notification SMS</label>
                                                                                </div>
                                                                             </div>
                                                                             
                                                                        </div>
                                                                    </div>
                                                                         
                                                                    <div class="form-group">
                                                                        <div class="row">
                                                                             
                                                                             <div class="col-sm-12">
                                                                                <div class="radio radio-primary">
                                                                                    <input id="send_msg" name="messageType" value="send_msg" type="radio">
                                                                                    <label for="send_msg"> Send A Message </label>
                                                                                </div>
                                                                             </div>
                                                                             
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <hr class="blue">   
                                                                    
                                                                    <div class="form-group hidden" id="send_msg_div">
                                                                        <div class="col-sm-12">
                                                                            <label for="message" class="control-label">Message</label>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <div class="row">
                                                                                <textarea class="form-control" name="message" id="sms_message2" rows="5"></textarea>
                                                                            </div>
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
                                                                            <div class="row">
                                                                                <button class="btn btn-lg btn-primary btn-block" id="send_msg_btn">Add Parent Accounts</button>
                                                                            </div>
                                                                        </div>
                                                                        
                                                                    </div>
                                                                
                                                                </div>
                                                                                                                                            
                                                                                                           
                                                            </form>
                                                                                                        
                                                        </div>
                                                        
                                                    </div>
                                                                                                                
                                                    <div id="details2" class="tab-pane hidden">
                                                                                        
                                                        <div class="item-details">
                                                        
                                                           <form class="form-horizontal form-edit-parent">
                                                                                                                                                                                                                   
                                                                <div id="wrapper_form">
                                                                                                                                                                                 
                                                                    <div class="form-group">
                                                                        <label for="student_name" class="col-sm-3 control-label">Student Names</label>
                                                                        <div class="col-sm-9">
                                                                            <div class="form-control textbox-noborder" id="student_name_edit2"></div>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="form-group">
                                                                        <label for="reg_no" class="col-sm-3 control-label">Reg No.</label>
                                                                        <div class="col-sm-9">
                                                                            <div class="form-control textbox-noborder" id="reg_no_edit2"></div>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <hr>
                                                                    
                                                                    <div class="form-group">
                                                                        <label for="guardian_name" class="col-sm-3 control-label">Parent Names</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="text" class="form-control" name="guardian_name" id="guardian_name_edit2">
                                                                            <input type="hidden" class="form-control" name="sch_id" value="<?=$top_sch_id?>">
                                                                            <input type="hidden" class="form-control" name="id" id="id_edit2">
                                                                            <input type="hidden" name="user_id" value="<?=USER_ID?>">
                                                                            <input type="hidden" id="admin" name="admin" value="1">
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="form-group">
                                                                        <label for="guardian_phone" class="col-sm-3 control-label">Phone No</label>
                                                                        <div class="col-sm-9">
                                                                           <input type="text" class="form-control" name="guardian_phone" id="guardian_phone_edit2">
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="form-group">
                                                                        <label for="email" class="col-sm-3 control-label">Email</label>
                                                                        <div class="col-sm-9">
                                                                           <input type="email" class="form-control" name="email" id="email_edit2">
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="form-group">
                                                                        <label for="guardian_id_card" class="col-sm-3 control-label">Parent ID Card</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="text" class="form-control" name="guardian_id_card" id="guardian_id_card_edit2">
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="form-group">
                                                                        <label for="guardian_relation" class="col-sm-3 control-label">Parent Relation</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="text" class="form-control" name="guardian_relation" id="guardian_relation_edit2">
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="form-group">
                                                                        <label for="guardian_occupation" class="col-sm-3 control-label">Occupation</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="text" class="form-control" name="guardian_occupation" id="guardian_occupation_edit2">
                                                                        </div>
                                                                    </div>
                                                                                                             
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
                                                        
                                               </div>
                                           
                                            </div>
                                       
                                       
                                    </div>
                                    
                                    
                                    <div id="sent_requests" class="tab-pane">
                            
                                        <div class="col-md-7">
                                                                                                
                                            <div class="panel panel-default paper-shadow contactsHeight2" data-z="0.5">
                                                
                                                <div class="table-responsive">

                                                    <table class="table table-condensed table-hover text-subhead v-middle" id="mybootgrid2">
                                                            <thead>
                                                                <tr>
                                                                    <th data-column-id="id" data-type="numeric" data-identifier="true" data-sortable="true" data-visible="false">ID</th>  
                                                                    <th data-column-id="phone_number" data-sortable="true">Phone Number</th>
                                                                    <th data-column-id="msg_text_short" data-sortable="true">Message</th>
                                                                    <th data-column-id="created_at" data-sortable="true">Sent At</th>
                                                                    <th data-column-id="status_text" data-sortable="true">Status</th>
                                                                    
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
                                            
                                                <div class="no-results">Please Select Message to Begin</div>
                                                                                                                                
                                                <div class="item-details hidden">
                                                                                                                    
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
                                                                    <div type="text" class="form-control-edit textbox-noborder" id="source_edit"></div>
                                                                    <input type="hidden" class="form-control" name="id" id="id_edit">
                                                                    <input type="hidden" class="form-control" name="phone_number" id="phone_number_edit" value="">
                                                                    <input type="hidden" name="user_id" value="<?=USER_ID?>">
                                                                    <input type="hidden" id="admin" name="admin" value="1">
                                                                    <input type="hidden" name="sch_id" id="sch_id" value="<?=$sch_id?>">
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label class="col-sm-4 control-label">Date:</label>
                                                                <div class="col-sm-8">
                                                                    <div type="text" class="form-control-edit textbox-noborder" id="que_date_edit"></div>
                                                                </div>
                                                                <div class="clearfix"></div>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label class="col-sm-4 control-label">Message:</label>
                                                                <div class="col-sm-8">
                                                                    <div type="text" class="form-control-edit textbox-noborder" id="message_edit"></div>
                                                                </div>
                                                                <div class="clearfix"></div>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label class="col-sm-4 control-label">Status:</label>
                                                                <div class="col-sm-8">
                                                                    <div type="text" class="form-control-edit textbox-noborder" id="status_edit"></div>
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
                                    
                                    <div class="clearfix"></div>
                                    
                                </div>
                                
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
        
        
        <!--results of add parent-->
        <div style='display:none'>
                                        
            <div id="sent_sms_results" class="padding-20">
            
            </div>
            
        </div>

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