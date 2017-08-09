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
	//$show_students_list = true;
	//$show_contacts_list = true;
	//$show_mpesa_trans = true;
	
	$show_mpesa_inbox = true;
	
	//first bootgrid should be multiple
	$show_bootgrid_1_multiple = true;
	$show_bootgrid_2_multiple = false;
		
	$db = new DbHandler();
			
?>

<?php 
	//if user has read permissions
	if (!(HAS_CREATE_MPESA_TRANS_PERMISSION || HAS_UPDATE_MPESA_TRANS_PERMISSION || SCHOOL_ADMIN_USER)) 
	{
		//user is not allowed to access page
		$page = LOGIN_URL;
		header("Location: $page"); 
		exit();
	}
	
	
	if ($_GET["sch_id"] && SUPER_ADMIN_USER) {
		
		$sch_id = $_GET["sch_id"];
		$sch_name = $db->getSchoolName($sch_id);
		
	} else {
		
		//get the school ids
		$query = "SELECT sch_name, sch_id FROM sch_ussd WHERE status =  " . ACTIVE_STATUS;
		if (SCHOOL_ADMIN_USER) { $query .= " AND sch_id IN (" . USER_SCHOOL_IDS . ") "; }
		$query .= " ORDER BY sch_name LIMIT 0,1"; //echo $query; //exit
		//$query .= " WHERE sch_id = ? ";
		$stmt = $db->conn->prepare($query);
		//$stmt->bind_param("i", $sch_name, $sch_id);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($sch_name, $sch_id);
		$stmt->fetch();
	
	}
	
	$top_sch_name = $sch_name;
	
	$page_title = "Manage MPESA - " . $top_sch_name;
	
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
                                    <li class="active"><a href="#main" data-toggle="tab"><i class="fa fa-fw fa-bars"></i> <span class="hidden-sm hidden-xs">Incoming MPESA</span></a></li>
                                    <li><a href="#inbox" data-toggle="tab"><i class="fa fa-fw fa-bars"></i> <span class="hidden-sm hidden-xs">MPESA</span></a></li>
                                </ul>
                                <!-- // END Tabs -->
                            
                            
                                <!-- Panes -->
                                <div class="tab-content">
                                                        

                                    <div id="main" class="tab-pane active">
                                    	                                        
                                       <div class="col-sm-7">
                                        
                                            <div class="panel panel-default paper-shadow" data-z="0.5" style="background:#fafafa;">
                                            	
                                                <div class="margin-20">
                                                    
                                            		<form class="form-horizontal inputform">
                                                    
                                                        <h4>Filter Incoming MPESA</h4>
                                                        
                                                        <hr>
                                                        
                                                        <input type="hidden" name="user_id" value="<?=USER_ID?>">
                                                        <input type="hidden" name="admin" value="1">
                                                                                                                
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
                                                                    
                                                                </div>
                                                            

                                                            </div>
                                                        
                                                        </div>
                                                                                                                                                                
                                                    </form>
                                                
                                                </div>
                                                            
                                            </div>
                                            
                                            <div class="clearfix"></div>
                                                                                        
                                            <div class="panel panel-default paper-shadow" data-z="0.5" id="contactsHeight2">
                                            
                                                <div>
                                                
                                                    <div class="table-responsive">
                                    
                                                        <table class="table table-condensed table-hover text-subhead v-middle" id="mybootgrid">
                                                            <thead>
                                                                <tr>
                                                                    <th data-column-id="id" data-type="numeric" data-identifier="true" data-visible="false" data-sortable="true">ID</th>  
                                                                    <th data-column-id="student_full_names" data-sortable="true">Student</th>
                                                                    <th data-column-id="reg_no" data-sortable="true">Reg No</th>
                                                                    <th data-column-id="que_date_fmt" data-sortable="true">Date</th>
                                                                    <th data-column-id="replied" data-sortable="false">Amount</th>
                                                                    <th data-column-id="msg_text_short" data-sortable="false">Acc Name</th>
                                                                    <th data-column-id="status" data-formatter="status-links" data-sortable="false" data-visible="true">Status</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody></tbody>
                                                        </table>
                                                        
                                                    </div>
                                                
                                                </div>
                                                            
                                            </div>
                                            
                                            <div class="clearfix"></div>
                                        
                                        </div>
                                        
                                        <div class="col-sm-5">
                                                                    
                                            <div class="panel panel-default paper-shadow padding-20" data-z="0.5">
                                            
                                                <div class="no-results">Please Select Transaction to Begin</div>
                                                
                                                <div id="details" class="tab-pane active">
                                                                                    
                                                    <div class="item-details hidden">
                                                    
                                                       <form class="form-horizontal form-send-single-sms">
                                                                                                                                                            
                                                                                                                     
                                                                
                                                                
                                                                <div class="row col-data">
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
                                                        
                                                                
                                                                <hr>
                                                                
                                                                <div class="form-group">
                                                                    <label class="col-sm-3 control-label">Source:</label>
                                                                    <div class="col-sm-9">
                                                                        <div type="text" class="form-control textbox-noborder" id="source_edit"></div>
                                                                        <input type="hidden" class="form-control" name="id" id="id_edit">
                                                                        <input type="hidden" class="form-control" name="phone_number" id="phone_number_edit" value="">
                                                                        <input type="hidden" name="user_id" value="<?=USER_ID?>">
                                                        				<input type="hidden" id="admin" name="admin" value="1">
                                                                        <input type="hidden" name="sch_id" id="sch_id" value="<?=$sch_id?>">
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="form-group">
                                                                    <label class="col-sm-3 control-label">Date:</label>
                                                                    <div class="col-sm-9">
                                                                        <div type="text" class="form-control textbox-noborder" id="que_date_edit"></div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="form-group">
                                                                    <label class="col-sm-3 control-label">Amount:</label>
                                                                    <div class="col-sm-9">
                                                                        <div type="text" class="form-control textbox-noborder" id="message_edit"></div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <hr>
                                                                
                                                                <h4 class="page-inner-title">Reply To Message</h4>
                                                                
                                                                <hr>                                                                
                                                                
                                                                                                                               
                                                                <div class="form-group">
                                                                    
                                                                    <div class="col-sm-12">
                                                                    <button class="btn btn-lg btn-primary btn-block">Send Message</button>
                                                                    </div>
                                                                    
                                                                </div>
                                                                                                                                                                                                    
                                                        </form>
                                                                                                    
                                                    </div>
                                                    
                                                </div>
                                                                                            
                                            </div>
                                                                                                                                        
                                        </div>
                                        
                                        <div class="clearfix"></div>
                                        
                                    </div>
                                    
                                    <div id="inbox" class="tab-pane">
                                    	                                        
                                        
                                        
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
                                        
                                        <div id="sent_sms_results" class="padding-20">
                                        
                                        </div>
                                        
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