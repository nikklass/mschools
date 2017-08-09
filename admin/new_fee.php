<?php 
	include_once("api/includes/DB_handler.php"); 
	include_once("includes/funcs.php"); 
	include_once("api/includes/Config.php"); 

	$admin = true;
	$show_bootstrap_dialog = true;
	$show_form = true;
	$show_lightbox = true;
	$show_popup = true;
	$show_scroller = true;
	$show_file_upload = true;
	$form_validation = true; //form validation classes
		
	$page_title = "Add New Fees";
	
	$db = new DbHandler();
	
?>

<?php 
	//if user has read permissions
	if (!(HAS_CREATE_FEE_PERMISSION || SCHOOL_ADMIN_USER)) 
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

                        <div id="title"></div>
                        
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
                                    <li class="active"><a href="#bulk" data-toggle="tab"><i class="fa fa-fw fa-credit-card"></i> <span class="hidden-sm hidden-xs">Add Bulk Student Fees</span></a></li>
                                </ul>
                                <!-- // END Tabs -->
                            
                                <!-- Panes -->
                                <div class="tab-content">
                                                                        
                                    <div id="bulk" class="tab-pane active">
                                        
                                        <form enctype="multipart/form-data" method="post" class="form-upload-fees">

                                            <div class="resultdiv"></div>
                                            
                                            <div class="form-group padding-20">
                                                <div class="col-sm-3">
                                                	<label for="sch_name" class="control-label">School</label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <select id="sch_id" name="sch_id" class="form-control selectpickerz">
                                                        
                                                        <?php
															
															//get the schools
															$query = "SELECT sch_id,  sch_name FROM sch_ussd WHERE status =  " . ACTIVE_STATUS;
															if (SCHOOL_ADMIN_USER) { $query .= " AND sch_id IN (" . USER_SCHOOL_IDS . ") "; }
															$query .= " ORDER BY sch_name"; //echo $query; //exit
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
                                            <br>
                                            
                                            <div class="form-group">
                                                <label for="sch_name" class="col-sm-3 control-label">Select File</label>
                                                <div class="col-sm-9">
                                                <input id="noupload" name="fee_file" type="file" class="myfile">
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <div class="col-sm-3"></div>
                                                <div class="col-sm-9">
                                                	<div class="padding-20"><i class="fa fa-2x fa-file-excel-o text-success"></i> &nbsp;&nbsp;<a href="<?=SITEPATH?>sample_files/fees_upload.csv">Excel Data Upload Template (CLICK to download)</a></div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group">
                                                <div class="col-sm-3"></div>
                                                <div class="col-sm-9">
                                                	<button class="btn btn-lg btn-primary col-sm-12">Submit</button>
                                                </div>
                                                <div class="clear"></div>
                                            </div>
                                            
                                       </form>
            
                                        
                                    </div>
                                                                        
                               </div>
                               
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