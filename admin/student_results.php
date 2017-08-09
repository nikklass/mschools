<?php 
	include_once("api/includes/DB_handler.php"); 
	include_once("includes/funcs.php"); 
	include_once("api/includes/Config.php");
	
	if (!isset($_SESSION)) session_start();

	$show_bootstrap_dialog = true;

	$form_validation = true; //form validation classes
	
	$show_form = true;
	
	$show_table = true; // will show bootgrid table
	$regular_show_results_list = true; // will load activities into table
		
	$db = new DbHandler();
	
	$page_title = "Student Results";
		
?>

<?php 
	//if user has read permissions
	if (!(HAS_READ_RESULT_PERMISSION || SCHOOL_ADMIN_USER)) 
	{
		//user is not allowed to access page
		$page = SITEPATH."error";
		header("Location: $page"); 
		exit();
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

                        <div class="page-section">
                            <div class="col-sm-6">
                            	<h1 class="text-display-1"><?=$page_title?></h1>
                            </div>
                            <div class="col-sm-6">
                            	
                                <?=BreadCrumb()?>
                                
                            </div>
                            <div class="clear"></div>
                        </div>

                        
                        <div class=" col-md-12">
                        
                        	<!-- Tabbable Widget -->
                            <div class="tabbable paper-shadow relative" data-z="0.5">
                            
                                <!-- Tabs -->
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#results" data-toggle="tab"><i class="fa fa-fw fa-credit-card"></i> <span class="hidden-sm hidden-xs">Student Results</span></a></li>
                                </ul>
                                <!-- // END Tabs -->
                            
                            
                                <!-- Panes -->
                                <div class="tab-content">                                    
                                    
                                    <div id="results" class="tab-pane active">
                                                                                
                                        <form class="form-horizontal form-new-result inputform"  data-parsley-validate>
                                        
                                        <div class="col-sm-5">
                                        	
                                            <div class="panel panel-default text-center login-form-bg" data-z="0.5">
                                                
                                                <h3 class="text-display-1 text-center margin-bottom-none">Select Student</h3>
                                                <hr>
                                                <div class="panel-body">
                                                    
                                                        <div class="form-group">
                                                        
                                                            <div class="resultdiv"></div>
                                                        
                                                            <label for="student_id" class="col-sm-3 control-label">Student</label>
                                                            <div class="col-sm-9">
                                                                
                                                                <select id="student_id" name="student_id" class="form-control selectpickerz" data-parsley-trigger="change" required>
                                                                        
                                                                    <?php
                                                                            
																		$phone_number = USER_PHONE;
																		$queryMain  = "SELECT st.id, su.sch_name, su.sch_id, st.reg_no, st.full_names FROM sch_ussd_subs ss";
																		$queryMain .= " JOIN sch_students st ON ss.reg_no=st.reg_no ";
																		$queryMain .= " JOIN sch_ussd su ON ss.sch_id=su.sch_id ";
																		$queryMain .= " WHERE ss.mobile = ? ";
																		$queryMain .= " ORDER BY sub_date DESC "; 
																		$stmt = $db->conn->prepare($queryMain);																		
																		$stmt->bind_param("s", $phone_number);
																		$stmt->execute();
																		$stmt->store_result();
                                                                        /* bind result variables */
                                                                        $stmt->bind_result($id, $sch_name, $sch_id, $reg_no, $full_names);
                                                                        $i=0;
                                                                        while ($stmt->fetch()) 
                                                                        {
                                                                            if ($i==0) { $this_sch_id = $sch_id; $this_reg_no = $reg_no; } //store first value
																			echo "<option value='$id'>$full_names - $sch_name</option>";
																			$i++;
                                                                        } 
                                                                    
                                                                    ?>
                                                                    
                                                                </select>
                                                                <input type="hidden" id="sch_id" name="sch_id" value="<?=$this_sch_id?>">
                                                                <input type="hidden" id="reg_no" name="reg_no" value="<?=$this_reg_no?>">
                                                                
                                                                
                                                            </div>
                                                            
                                                        </div>
                                                    
                                                    
                                                </div>
                                                
                                            </div>
                                            
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
                                                        
                                                        <hr>
                                                        <div class="panel-body padding-10">
                                                        	<div class="col-sm-3">
                                                            	<a href="#" id="show_pdf" class="noclick hidden" title="Save as PDF"><img src="<?=SITEPATH?>admin/images/pdf_icon_sm.png"></a>
                                                            </div>
                                                            <div class="col-sm-9">
                                                            </div>
                                                        </div>
                                        
                                                    </div>
                                                
                                                </div>
                        
                                            </div>
                
                                        </div>
                                        <div class="clear"></div>
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