<?php 
	include_once("api/includes/DB_handler.php"); 
	include_once("includes/funcs.php"); 
	include_once("api/includes/Config.php");
	
	$admin = true;
	$show_bootstrap_dialog = true;
	$show_form = true;
	$form_validation = true; //form validation classes
	$show_popup = true; // show colorbox
	
	$page_title = "Manage Subjects";
	
	$db = new DbHandler();
		
	$show_table = true;
	$show_subjects_list = true;
	
?>

<?php 

	//if user has read permissions
	$user_id = USER_ID; 
	if (!(SUPER_ADMIN_USER)) 
	{
		//user is not allowed to access page
		$page = LOGIN_URL;
		header("Location: $page"); 
		exit();
	} //echo "user_id - $user_id, $est_id, $perms";
	
?>

<?php
	
	if ($_GET["level_id"]) {
		
		$level_id = $_GET["level_id"];
		
	} else {
		
		//get the school ids
		/*$query = "SELECT name, id FROM sch_subjects ORDER BY id LIMIT 0,1";
		//$query .= " WHERE sch_id = ? ";
		$stmt = $db->conn->prepare($query);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($name, $level_id);
		$stmt->fetch();*/
	
	}
	
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
						
                        <div class="row">
                            
                            <div class="col-md-6">
                            
                            	<!-- Tabbable Widget -->
                                <div class="tabbable paper-shadow relative" data-z="0.5">
                                
                                    <!-- Tabs -->
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a href="#item_listing" data-toggle="tab"><i class="fa fa-fw fa-bars"></i> <span class="hidden-sm hidden-xs">Subjects Listing</span></a></li>
                                        <li><a href="#new_item" data-toggle="tab"><i class="fa fa-fw fa-plus"></i> <span class="hidden-sm hidden-xs">Add Subject</span></a></li>
                                    </ul>
                                    <!-- // END Tabs -->
                                    
                                    <!-- Panes -->
                                    <div class="tab-content">
                                    
                                    	<div id="item_listing" class="tab-pane active">
                                        
                                            <div class="panel panel-default paper-shadow" data-z="0.5">
                                                
                                                <div id="top_level_id" data-level-id="<?=$level_id?>"></div>
                                                
                                                <div class="form-group padding-20-all <?=$hide_admin_css?>">
                                                    <form>
                                                        <select id="subject-select" name="level_id" class="form-control">
                                                            <option value="">All</option>
                                                            <?php
                                                                    
                                                                //get the user types
                                                                $query = "SELECT id, name FROM sch_levels ORDER BY id";
                                                                $stmt = $db->conn->prepare($query);
                                                                $stmt->execute();
                                                                /* bind result variables */
                                                                $stmt->bind_result($id, $name);
                                                                
                                                                while ($stmt->fetch()) 
                                                                {
                                                          
                                                                    echo "<option value='$id' ";
                                                                    
                                                                    if ($level_id == $id) { echo " selected "; } 
                                                                    
                                                                    echo ">$name</option>";
                                                            
                                                                 } 
                                                            
                                                            ?>
                                                            
                                                        </select>
                                                    </form>
                                                </div>
                                                
                                                <div class="panel panel-default paper-shadow" data-z="0.5" id="contactsHeight2">
                                                
                                                    <div class="table-responsive" id="table-responsive" data-tbl="sch_subjects" data-tbl-pk="id">
                                                    
                                                        <table class="table table-condensed table-hover text-subhead v-middle" id="mybootgrid">
                                                            <thead>
                                                                <tr>
                                                                    <th data-column-id="id" data-type="numeric" data-identifier="true" data-sortable="true" data-visible="false">ID</th>  
                                                                    <th data-column-id="name" data-sortable="true">Subject</th>
                                                                    <th data-column-id="short_name" data-sortable="true">Short Name</th>
                                                                    <th data-column-id="code" data-sortable="true">Code</th>
                                                                    <th data-column-id="level" data-sortable="true">Level</th>
                                                                    <th data-column-id="status" data-formatter="status-links">Status</th>
                                                                    <th data-column-id="links" data-formatter="links" data-sortable="false" data-visible="false">Edit</th>
                                                                    <th data-column-id="commands" data-formatter="commands" data-sortable="false" data-visible="false">Delete</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                               
                                                              
                                                            </tbody>
                                                        </table>
                                                        
                                                    </div>
                                                
                                                </div>
                    
                                            </div>
                                            
                                        </div>
                                        
                                        <div id="new_item" class="tab-pane">
                                        
                                            <div class="panel panel-default text-center login-form-bg" data-z="0.5">
                                                <h3 class="text-display-1 text-center margin-bottom-none">Add Subject</h3>
                                                <hr>
                                                <div class="panel-body">
                                                    
                                                    <form class="form-add-subject inputform padding-no-top-20" data-parsley-validate>
                                                    
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class="resultdiv"></div>
                                                            </div>
                                                        </div>
                                                      
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <label for="subject" class="col-sm-5 control-label text-right">Subject Name</label>
                                                                <div class="col-sm-7">
                                                                	<input type="text" id="subject_name" name="subject_name" class="form-control text-center" data-parsley-trigger="change" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <label for="short_name" class="col-sm-5 control-label text-right">Short Name/ Initials e.g. ENG</label>
                                                                <div class="col-sm-7">
                                                                	<input type="text" id="short_name" name="short_name" class="form-control text-center" data-parsley-trigger="change" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <label for="short_name" class="col-sm-5 control-label text-right">Unique Code e.g. eng</label>
                                                                <div class="col-sm-7">
                                                                	<input type="text" id="code" name="code" class="form-control text-center" data-parsley-trigger="change" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="form-group">
                                                            <div class="row">      
                                                                <label for="sch_level" class="col-sm-5 control-label text-right">School Level</label>
                                                                <div class="col-sm-7">
                                                                    <select id="level" name="level" class="form-control selectpickerz text-center" data-parsley-trigger="change" required>
                                                                        
                                                                        <option value="">Select Level</option>
                                                                        <?php
                                                                                
                                                                            //get the user types
                                                                            $queryTypes = "SELECT id, name FROM sch_levels ORDER BY name";
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
                                                           
                                                        </div>
                                                        
                                                        <p>&nbsp;</p>
                                                        
                                                        <div class="form-group text-center">
                                                            <div class="row">
                                                                <div class="col-sm-5"></div>
                                                                <div class="col-sm-7">
                                                                    <button class="btn btn-primary btn-block btn-lg"><?=SUBMIT_BTN_TEXT?></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                    </form>
                                                    
                                                </div>
                                            </div>
                                            
                                        </div>
                                        
                                    </div>
                                    
                                </div>
                                
                            </div>
                            
                            <div class="col-md-6">
                            	
                                <!-- Tabbable Widget -->
                                <div class="tabbable paper-shadow relative" data-z="0.5">
                                
                                    <!-- Tabs -->
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a href="#details" data-toggle="tab"><i class="fa fa-fw fa-bars"></i> <span class="hidden-sm hidden-xs">Subject Details</span></a></li>
                                        <li><a href="#item-history" data-toggle="tab"><i class="fa fa-fw fa-bars"></i> <span class="hidden-sm hidden-xs">Subject History</span></a></li>
                                    </ul>
                                    <!-- // END Tabs -->
                                
                                
                                    <!-- Panes -->
                                    <div class="tab-content">
                                                                                                    
                                        <div class="no-results">Please Select Subject to Begin</div>
                                            
                                        <div id="details" class="tab-pane active">
                                                                            
                                            <div class="item-details hidden">
                                
                                                <div class="panel panel-default text-center login-form-bg" data-z="0.5">
                                                    <h3 class="text-display-1 text-center margin-bottom-none">Edit Subject</h3>
                                                    <hr>
                                                    <div class="panel-body">
                                                        
                                                        <form class="form-horizontal form-edit-subject">
                                                                                                                                                            
                                                            <div id="wrapper_form">
                                                                                                                     
                                                                <div class="form-group">
                                                                    <label for="subject_name" class="col-sm-4 control-label">Subject Names</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control" name="subject_name" id="subject_name_edit">
                                                                        <input type="hidden" class="form-control" name="id" id="id_edit">
                                                                        <input type="hidden" class="form-control" name="user_id" value="<?=USER_ID?>">
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="form-group">
                                                                    
                                                                    <label for="short_name" class="col-sm-4 control-label">Short Name/ Initials</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control" name="short_name" id="short_name_edit">
                                                                    </div>
                                                                        
                                                                </div>
                                                                
                                                                <div class="form-group">
                                                                                                                                
                                                                    <label for="code" class="col-sm-4 control-label">Unique Code</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control" name="code" id="code_edit">
                                                                    </div>
                                                                    
                                                                </div>
                                                                
                                                                <div class="form-group">
                                                                
                                                                    <label for="level" class="col-sm-4 control-label">School Level</label>
                                                                    <div class="col-sm-8">
                                                                        <select id="level_edit" name="level" class="form-control">
                                                                
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
                                                                    <label for="status" class="col-sm-4 control-label">Status</label>
                                                                    <div class="col-sm-8">
                                                                        <select id="status_edit" name="status" class="form-control">
                                                                                                                        
                                                                            <?php
                                                                                    
                                                                                $items = $db->getStatuses(SUBJECT_STATUS_SECTION);
                                                                                                                                        
                                                                                foreach ($items["rows"] as $key => $val) {
                                                                                    $id = $val['id'];
                                                                                    $name = $val['name'];
                                                                                    echo "<option value='$id'>$name</option>";
                                                                                }
                                                                            
                                                                            ?>
                                                                           
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                                                                               
                                                                <hr>
                                                                
                                                                <div class="form-group">
                                                                    
                                                                    <div class="col-sm-4"></div>
                                                                    <div class="col-sm-8">
                                                                    <button class="btn btn-lg btn-primary btn-block"><?=SAVE_CHANGES_BTN_TEXT?></button>
                                                                    </div>
                                                                    
                                                                </div>
                                                            
                                                            </div>
                                                                                                                                        
                                                                                                       
                                                        </form>
                                                        
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            
                                        </div>
                                        
                                        <div id="item-history" class="tab-pane">
                                                                        
                                            <div class="item-details hidden">
                                            
                                                <div class="col-sm-12">
                                                        
                                                   <div class="table-responsive" id="result-item-history">
                                                
                                                        <table class="table table-condensed table-hover text-subhead v-middle" id="mybootgrid-history">
                                                            <thead>
                                                                <tr>
                                                                    <th data-column-id="id" data-type="numeric" data-identifier="true" data-sortable="true" data-visible="false">ID</th>  
                                                                    <th data-column-id="name" data-sortable="true">Subject</th>
                                                                    <th data-column-id="short_name" data-sortable="true">Short Name</th>
                                                                    <th data-column-id="code" data-sortable="true">Code</th>
                                                                    <th data-column-id="level" data-sortable="true">Level</th>
                                                                    <th data-column-id="created_at" data-sortable="true">Updated</th>
                                                                    <th data-column-id="created_by" data-sortable="true">By</th>
                                                                    
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