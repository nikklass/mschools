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
	
	//$show_mpesa_trans = true;
	
	$show_mpesa_inbox = true;
		
	$db = new DbHandler();
			
?>

<?php
	
	$perms = ALL_MPESA_TRANS_PERMISSIONS; 
	
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
	
	$page_title = "Manage MPESA - $top_sch_name";
	
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

                        
                        <div class=" col-md-12" id="mpesa-container">
                        
                        	<!-- Tabbable Widget -->
                            <div class="tabbable paper-shadow relative" data-z="0.5">
                            
                                <!-- Tabs -->
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#main" data-toggle="tab"><i class="fa fa-fw fa-bars"></i> <span class="hidden-sm hidden-xs">MPESA Transactions</span></a></li>
                                    <!--<li><a href="#inbox" data-toggle="tab"><i class="fa fa-fw fa-bars"></i> <span class="hidden-sm hidden-xs">SMS Inbox</span></a></li>
                                    <li><a href="#sent_sms" data-toggle="tab"><i class="fa fa-fw fa-bars"></i> <span class="hidden-sm hidden-xs">SMS Outbox</span></a></li>-->
                                </ul>
                                <!-- // END Tabs -->
                            
                            
                                <!-- Panes -->
                                <div class="tab-content relative">
                                    
                                    <div class="overlay-div hidden">
                                            
                                        <div class="no-results vertical-align">
                                            <div class="panel-body">
                                                MPESA Services Not Available.<br>Please Contact Pendo Schools Admin
                                            </div>
                                        </div>
                                        
                                    </div>                 

                                    <div id="main" class="tab-pane active">
                                    	                                        
                                        
                                        <div class="col-sm-7" id="page-container">
                                        
                                        	<a data-toggle="collapse" data-target="#mpesa-filter" class="btn btn-block btn-primary btn-lg margin-btm-10">
                                            Click To Filter/ Export Data</a>
                                                                                    
                                            <div class="panel panel-default paper-shadow panel-grey collapse" data-z="0.5" id="mpesa-filter">
                                            	
                                                <div class="margin-20">
                                                    
                                            		<form class="form-horizontal inputform">
                                                    
                                                        <h4>Filter MPESA Transactions</h4>
                                                        
                                                        <hr>
                                                                                                                                                                        
                                                        <div id="sms-query">
                                                          
                                                            <div class="form-group">
                                                            
                                                                <div class="row">
                                                                                                                                        
                                                                    <label for="start_date" class="col-sm-2 control-label">Start</label>
                                                                    <div class="col-sm-4">
                                                                        
                                                                        <div class="input-group date">
                                                                          <input type="text" readonly class="form-control datepicker" name="start_date" id="start_date">
                                                                          <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                                                        </div>
                                                                        <a href="#" id="clear_start_date" class="noclick hidden">Clear Date</a>
                                                                        
                                                                    </div>
                                                                    
                                                                    <label for="end_date" class="col-sm-2 control-label text-right">End</label>
                                                                    <div class="col-sm-4">
                                                                        
                                                                        <div class="input-group date">
                                                                          <input type="text" readonly class="form-control datepicker" name="end_date" id="end_date">
                                                                          <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                                                        </div>
                                                                        <a href="#" id="clear_end_date" class="noclick hidden">Clear Date</a>
                                                                        
                                                                    </div>
                                                                    
                                                                    <input id="item_type" type="hidden" value="mpesa_reports">
                                                                    <input id="user_id" type="hidden" value="<?=USER_ID?>">
                                                                    <input id="admin" type="hidden" value="1">
                                                                    
                                                                </div>
                                                            
                                                            </div>
                                                        
                                                        </div>
                                                                                                                                                                
                                                    </form>
                                                    
                                                    <hr>
                                                    
                                                    <div class="text-center">
                                                    	<a href="" class="btn btn-success export_excel" data-item-type="mpesa_reports"><i class="fa fa-file-excel-o"></i> Export Data To Excel</a>
                                                    </div>
                                                
                                                </div>
                                                            
                                            </div>
                                            
                                            <div class="clearfix"></div>
                                            
                                            <div class="panel panel-default paper-shadow" data-z="0.5" id="contactsHeight2">
                                            
                                                <div>
                                                
                                                    <div class="table-responsive" id="table-responsive" data-tbl="sch_students" data-tbl-pk="id">
                                    
                                                        <table class="table table-condensed table-hover text-subhead v-middle" id="mybootgrid">
                                                            <thead>
                                                                <tr>
                                                                    <th data-column-id="id" data-type="numeric" data-identifier="true" data-visible="false" data-sortable="true">ID</th>  
                                                                    <th data-column-id="student_full_names" data-sortable="true">Student</th>
                                                                    <th data-column-id="reg_no" data-sortable="true">Reg No</th>
                                                                    <th data-column-id="amount_fmt" data-sortable="true" data-align="right" data-header-align="right">Amount</th>
                                                                    <th data-column-id="sender_no" data-sortable="true">Sender No</th>                                                                    <th data-column-id="received_at_fmt" data-sortable="true">Received</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody></tbody>
                                                        </table>
                                                        
                                                    </div>
                                                
                                                </div>
                                                            
                                            </div>
                                            
                                            <div class="clearfix"></div>
                                        
                                        </div>
                                        
                                        <div class="panel panel-default paper-shadow col-sm-5 panel-grey" data-z="0.5">
                                        
                                            <div class="panel-spacing">
                                                                                            
                                                <div class="no-results">Please Select Item to Begin</div>
                                                
                                                <div class="tab-pane active">
                                                                                    
                                                    <div class="item-details hidden">
                                                    
                                                       <form class="form-horizontal">
                                                                
                                                               <!-- <div class="row col-data">
                                                                    <div class="col-sm-6">
                                                                         <div class="text-left padding-top-10 msg_text">
                                                                         	<h4 class="page-inner-title">Message Details</h4>
                                                                         </div>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                         <div class="text-left padding-top-10 msg_text" style="padding-top: 10px;">
                                                                            SMS Balance: &nbsp; <span id="bulk_sms_balance2" class="bold_text text-success text-big">0</span> 
                                                                         </div>
                                                                    </div>
                                                                    
                                                                </div>
                                                        
                                                                
                                                                <hr>-->
                                                                
                                                                <h4>Transaction Details</h4>
                                                                
                                                                <hr>
                                                                
                                                                <div class="form-group hidden">
                                                                    <div class="col-sm-9">
                                                                        <div type="text" class="form-control-edit textbox-noborder" id="source_edit"></div>
                                                                        <input type="hidden" class="form-control" name="id" id="id_edit">
                                                                        <input type="hidden" name="user_id" value="<?=USER_ID?>">
                                                        				<input type="hidden" id="admin" name="admin" value="1">
                                                                        <input type="hidden" name="sch_id" id="sch_id" value="<?=$sch_id?>">
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="form-group">
                                                                    <label class="col-sm-3 control-label">Sender Name:</label>
                                                                    <div class="col-sm-9">
                                                                        <div type="text" class="form-control-edit textbox-noborder" id="client_names_edit"></div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="form-group">
                                                                    <label class="col-sm-3 control-label">Sender No.:</label>
                                                                    <div class="col-sm-9">
                                                                        <div type="text" class="form-control-edit textbox-noborder" id="sender_no_edit"></div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="form-group">
                                                                    <label class="col-sm-3 control-label">Mpesa Code:</label>
                                                                    <div class="col-sm-9">
                                                                        <div type="text" class="form-control-edit textbox-noborder" id="mpesa_code_edit"></div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="form-group">
                                                                    <label class="col-sm-3 control-label">Amount:</label>
                                                                    <div class="col-sm-9">
                                                                        <div type="text" class="form-control-edit textbox-noborder" id="amount_edit"></div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="form-group">
                                                                    <label class="col-sm-3 control-label">Received At:</label>
                                                                    <div class="col-sm-9">
                                                                        <div type="text" class="form-control-edit textbox-noborder" id="received_edit"></div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <hr>
                                                                
                                                                <div class="form-group">
                                                                    <label class="col-sm-3 control-label">Reg No.:</label>
                                                                    <div class="col-sm-9">
                                                                        <div type="text" class="form-control-edit textbox-noborder" id="reg_no_edit"></div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="form-group">
                                                                    <label class="col-sm-3 control-label">Student Name:</label>
                                                                    <div class="col-sm-9">
                                                                        <div type="text" class="form-control-edit textbox-noborder" id="student_full_names_edit"></div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="form-group">
                                                                    <label class="col-sm-3 control-label">Class:</label>
                                                                    <div class="col-sm-9">
                                                                        <div type="text" class="form-control-edit textbox-noborder" id="current_class_edit"></div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <!--<h4 class="page-inner-title">Reply To Message</h4>
                                                                
                                                                <hr>                                                                
                                                                
                                                                <div class="form-group">
                                                                    <div class="col-sm-12">
                                                                        <label for="message" class="control-label">Message</label>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <textarea class="form-control" name="message" id="sms_message2" rows="5"></textarea>
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
                                                                    <button class="btn btn-lg btn-primary btn-block">Send Message</button>
                                                                    </div>
                                                                    
                                                                </div>-->
                                                                                                                                                                                                    
                                                        </form>
                                                                                                    
                                                    </div>
                                                    
                                                </div>
                                                                                                                                            
                                            </div>
                                        
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