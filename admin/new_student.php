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
	
	$page_title = "Add New Student(s)";
	
	$db = new DbHandler();
	
?>

<?php 
	//if user has read permissions
	if (!(HAS_CREATE_USER_PERMISSION || SCHOOL_ADMIN_USER)) 
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
                                    <li class="active"><a href="#main" data-toggle="tab"><i class="fa fa-fw fa-lock"></i> <span class="hidden-sm hidden-xs">Add Single Student</span></a></li>
                                    <li><a href="#bulk" data-toggle="tab"><i class="fa fa-fw fa-credit-card"></i> <span class="hidden-sm hidden-xs">Add Bulk Student Data</span></a></li>
                                </ul>
                                <!-- // END Tabs -->
                            
                            
                                <!-- Panes -->
                                <div class="tab-content">

                                    
                                    <div id="main" class="tab-pane active">
                                        
                                        <form class="form-horizontal form-new-student inputform" data-parsley-validate>
                                                        
                                            <div class="resultdiv"></div>
                                            
                                            <input type="hidden" name="<?= $token_id; ?>" value="<?= $token_value; ?>" />
                                            
                                            <div id="wrapper_form">
                                             
                                                <div class="form-group">
                                                    <div class="col-sm-6 col-data">
                                                        <label for="full_names" class="col-sm-6 control-label">Student Names</label>
                                                        <div class="col-sm-6">
                                                            <input type="text" class="form-control" name="full_names" data-parsley-trigger="change" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-data">
                                                       
                                                       <label for="county" class="col-sm-6 control-label">School</label>
                                                       <div class="col-sm-6">
                                                            <select id="select" name="sch_id" class="form-control" data-parsley-trigger="change" required>
                                                                                                                                
                                                                <?php
                                                                        
                                                                    //get the user types
                                                                    $queryTypes = "SELECT sch_id, sch_name FROM sch_ussd ORDER BY sch_name";
                                                                    $stmtTypes = $db->conn->prepare($queryTypes);
                                                                    $stmtTypes->execute();
                                                                    /* bind result variables */
                                                                    $stmtTypes->bind_result($id, $name);
                                                                    
                                                                    while ($stmtTypes->fetch()) 
                                                                    {
                                                              
                                                                        echo "<option value='$id' ";
                                                                        
                                                                        if ($sch_id == $id) { echo " selected "; } 
                                                                        
                                                                        echo ">$name</option>";
                                                                
                                                                     } 
                                                                
                                                                ?>
                                                                
                                                            </select>
                                                        </div>
                                                
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <div class="col-sm-6 col-data">
                                                        <label for="reg_no" class="col-sm-6 control-label">Reg No</label>
                                                        <div class="col-sm-6">
                                                            <input type="text" class="form-control" name="reg_no" data-parsley-trigger="change" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-data">
                                                         <label for="index_no" class="col-sm-6 control-label">Index No</label>
                                                        <div class="col-sm-6">
                                                            <input type="text" class="form-control" name="index_no" data-parsley-trigger="change" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <div class="col-sm-6 col-data">
                                                        <label for="admin_date" class="col-sm-6 control-label">Admission Date</label>
                                                        <div class="col-sm-6">
                                                            
                                                            <div class="input-group date">
                                                              <input type="text" readonly class="form-control datepicker" name="admin_date">
                                                              <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                                            </div>
    
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-data">
                                                        <label for="dob" class="col-sm-6 control-label">Date of Birth</label>
                                                        <div class="col-sm-6">
                                                            
                                                            <div class="input-group date">
                                                              <input type="text" readonly class="form-control datepicker" name="dob">
                                                              <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                                            </div>
    
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                 <div class="form-group">
                                                    <div class="col-sm-6 col-data">
                                                        <label for="nationality" class="col-sm-6 control-label">Nationality</label>
                                                        <div class="col-sm-6">
                                                            <input type="text" class="form-control" name="nationality">
                                                        </div>
                                                    </div>
                                                     <div class="col-sm-6 col-data">
                                                        <label for="religion" class="col-sm-6 control-label">Religion</label>
                                                        <div class="col-sm-6">
                                                            <input id="religion" type="text" class="form-control" name="religion">
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <div class="col-sm-6 col-data">
                                                        <label for="current_class" class="col-sm-6 control-label">Current Class</label>
                                                        <div class="col-sm-6">
                                                            <input type="text" class="form-control" name="current_class">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-data">
                                                        <label for="stream" class="col-sm-6 control-label">Stream</label>
                                                        <div class="col-sm-6">
                                                            <input type="text" class="form-control" name="stream">
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                
                                                <div class="form-group">
                                                    <div class="col-sm-6 col-data">
                                                        <label for="house" class="col-sm-6 control-label">House</label>
                                                        <div class="col-sm-6">
                                                            <input id="house" type="text" class="form-control" name="house">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-data">
                                                         <label for="club" class="col-sm-6 control-label">Club</label>
                                                        <div class="col-sm-6">
                                                            <input type="text" class="form-control" name="club">
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                
                                                <div class="form-group">
                                                    <div class="col-sm-6 col-data">
                                                        <label for="disability" class="col-sm-6 control-label">Disability</label>
                                                        <div class="col-sm-6">
                                                            <input id="disability" type="text" class="form-control" name="disability">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-data">
                                                         <label for="gender" class="col-sm-6 control-label">Gender</label>
                                                        <div class="col-sm-6">
                                                            <select id="select" name="gender" class="form-control" required>
                                                                
                                                                <option value='Male'>Male</option>
                                                                <option value='Female'>Female</option>
                                                                
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <div class="col-sm-6 col-data">
                                                         <label for="previous_school" class="col-sm-6 control-label">Previous School</label>
                                                        <div class="col-sm-6">
                                                            <input type="text" class="form-control" name="previous_school">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-data">
                                                        
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <div class="col-sm-6 col-data">
                                                        <label for="guardian_name" class="col-sm-6 control-label">Guardian Name</label>
                                                        <div class="col-sm-6">
                                                            <input id="guardian_name" type="text" class="form-control" name="guardian_name">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-data">
                                                         <label for="guardian_address" class="col-sm-6 control-label">Guardian Address</label>
                                                        <div class="col-sm-6">
                                                            <input type="text" class="form-control" name="guardian_address">
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <div class="col-sm-6 col-data">
                                                        <label for="guardian_phone" class="col-sm-6 control-label">Guardian Phone</label>
                                                        <div class="col-sm-6">
                                                            <input id="guardian_phone" type="text" class="form-control" name="guardian_phone">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-data">
                                                        <label for="guardian_occupation" class="col-sm-6 control-label">Guardian Occupation</label>
                                                        <div class="col-sm-6">
                                                            <input id="guardian_occupation" type="text" class="form-control" name="guardian_occupation">
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <div class="col-sm-6 col-data">
                                                        <label for="guardian_id_card" class="col-sm-6 control-label">Guardian ID Card</label>
                                                        <div class="col-sm-6">
                                                            <input id="guardian_id_card" type="text" class="form-control" name="guardian_id_card">
                                                        </div>
                                                        <div class="col-sm-1 col-md-1 result"></div>
                                                    </div>
                                                    <div class="col-sm-6 col-data">
                                                        <label for="guardian_relation" class="col-sm-6 control-label">Guardian Relation</label>
                                                        <div class="col-sm-6">
                                                            <input type="text" class="form-control" name="guardian_relation">
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <div class="col-sm-6 col-data">
                                                        <label for="constituency" class="col-sm-6 control-label">Constituency</label>
                                                        <div class="col-sm-6">
                                                            <input type="text" class="form-control" name="constituency">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-data">
                                                         <label for="email" class="col-sm-6 control-label">Email</label>
                                                        <div class="col-sm-6">
                                                            <input type="text" class="form-control" name="email">
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <div class="col-sm-6 col-data">
                                                        <label for="county" class="col-sm-6 control-label">County</label>
                                                        <div class="col-sm-6">
                                                            <input id="county" type="text" class="form-control" name="county">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-data">
                                                        <label for="town" class="col-sm-6 control-label">Town</label>
                                                        <div class="col-sm-6">
                                                            <input id="town" type="text" class="form-control" name="town">
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                                
                                                <div class="form-group">
                                                   
                                                    <div class="col-sm-6 col-data">
                                                         <label for="location" class="col-sm-6 control-label">Location</label>
                                                        <div class="col-sm-6">
                                                            <input type="text" class="form-control" name="location">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-data">
                                                         <label for="village" class="col-sm-6 control-label">Village</label>
                                                        <div class="col-sm-6">
                                                            <input type="text" class="form-control" name="village">
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                 <div class="form-group">
                                                    <div class="row col-data">
                                                        <div class="col-sm-3 text-right">
                                                            <label for="student_profile" class="control-label">Student Profile</label>
                                                        </div>
                                                        <div class="col-sm-9">
                                                            <textarea class="form-control" name="student_profile" rows="3"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                   <div class="row col-data"> 
                                                        <div class="col-sm-3"></div>
                                                        <div class="col-sm-9">
                                                        	<button class="btn btn-lg btn-info btn-block">Submit</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            
                                            </div>
                                                                                                                        
                                                                                       
                                        </form>
                                        
                                    </div>
                                    
                                    <div id="bulk" class="tab-pane">
                                        
                                        
                                        <form enctype="multipart/form-data" method="post" class="form-upload-students">

                                            <div class="resultdiv"></div>
                                            
                                            <div class="form-group padding-20">
                                                <div class="col-sm-3">
                                                	<label for="sch_name" class="control-label">School</label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <select id="sch_id" name="sch_id" class="form-control selectpickerz">
                                                        
                                                        <?php
                                                                
                                                            //get the user types
                                                            $queryTypes = "SELECT sch_id, sch_name FROM sch_ussd ORDER BY sch_name";
                                                            $stmtTypes = $db->conn->prepare($queryTypes);
                                                            $stmtTypes->execute();
                                                            /* bind result variables */
                                                            $stmtTypes->bind_result($id, $name);
                                                            
                                                            while ($stmtTypes->fetch()) 
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
                                                <input id="noupload" name="student_file" type="file" class="myfile">
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <div class="col-sm-3"></div>
                                                <div class="col-sm-9">
                                                	<div class="padding-20"><i class="fa fa-2x fa-file-excel-o text-success"></i> &nbsp;&nbsp;<a href="<?=SITEPATH?>sample_files/students_upload.csv">Excel Data Upload Template (CLICK to download)</a></div>
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