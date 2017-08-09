<?php 
	include_once("admin/api/includes/DB_handler.php"); 
	include_once("admin/api/includes/Config.php");
	
	$form_validation = true; //form validation classes 
	$show_form = true;
	
	$show_subs_list = true;
	$show_popup = true; // show colorbox
	
	$show_scroll = true;
	$show_delete = true;
	
	$show_waypoints = true;

	$page_title = "My Subscriptions";
	
?>

<?php
	if (!IS_USER_LOGGED_IN) {
		//user is logged in redirect to home page
		$home_page = LOGIN_URL;
		header("Location: $home_page"); 
 		exit();
	}
?>

<!DOCTYPE html>
<html class="transition-navbar-scroll top-navbar-xlarge bottom-footer" lang="en">
<head>
    
    <?php include_once("includes/head_scripts.php"); ?>
                
    <title><?=$page_title?> :: <?=$page_titles?></title>

</head>
<body>


	<?php include_once("includes/nav.php"); ?>
    
    <div class="parallax overflow-hidden page-section bg-blue2-500">
        <div class="container parallax-layer" data-opacity="true">
            <div class="media media-grid v-middle">
                <div class="media-left">
                    <span class="icon-block half bg-blue2-600 text-white"><i class="fa fa-users"></i></span>
                </div>
                <div class="media-body">
                    <h3 class="text-display-2 text-white margin-none"><?=$page_title?></h3>
                    <p class="text-white text-subhead"></p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container">
        <div class="page-section">
    
            <div class="row">
                
                   <div class="col-md-8">
                
                        <!-- Tabbable Widget -->
                        <div class="tabbable paper-shadow relative" data-z="0.5">
        
                            <!-- Tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#subscriptions" data-toggle="tab"><i class="fa fa-fw fa-users"></i> <span class="hidden-sm hidden-xs">My Subscriptions</span></a></li>                                </ul>
                            <!-- // END Tabs -->
        
                            <!-- Panes -->
                            <div class="tab-content">
        
                                
                                
                                <div id="subscriptions" class="tab-pane active">
                                               
                                    <div class="media v-middle s-container">
                                        <div class="media-body">
                                            <h5 class="text-subhead">&nbsp;</h5>
                                        </div>
                                        <div class="media-right">
                                        </div>
                                    </div>
                                    <div class="counter-data" data-total-items="0" data-last-page="true" data-items-per-page="10"></div>
                                    <div class="list-group margin-none tbl-data" id="subs_list" data-tbl="sch_ussd_subs" data-tbl-pk="id">
                                        
                                       <!--  SUBSCRIPTIONS HERE  -->
                                        
                                    </div>
                                </div>
        
                            </div>
                            <!-- // END Panes -->
        
                        </div>
                        <!-- // END Tabbable Widget -->
        
                        <!--MODAL-->
                        <?php include_once("includes/modal.php"); ?>
                        <!--END MODAL-->
        
                        <br/>
                        <br/>
        
                    </div>
                    
                   <div class="col-md-4">
                        
                        <!--right-->
     
                            <div class="panel panel-default text-center login-form-bg padding-10" data-z="0.5">
                                <h3 class="text-display-1 text-center margin-bottom-none">Add Subscription</h3>
                                <hr>
                                <div class="panel-body">
                                   
                                   <a href="#" class="btn btn-primary btn-block" id="add-new-sub">Add New Subscription</a>
                                    
                                </div>
                            </div>
                        
                        <!--end right-->
        
                    </div>
                
            </div>
    
        </div>
    </div>
    
    <!-- create new sub form -->
    
    <div style='display:none'>
    
        <div class="padding-20">
        
            <form class="form-add-subscription inputform margin-20" data-parsley-validate id="create_new_sub_form">
                                    
                <h3>Add New Subscription</h3>
                
                <hr>
              
                <div class="form-group">
                    <div class="row">
                        <label for="sch_prov">Province <span class="text-danger">*</span></label>
                        <select id="sch_prov" name="sch_prov" class="form-control" data-parsley-trigger="change" required>
                            
                            <option value="">Select Province</option>
                            <?php
                                    
                                //get the user types
                                $queryTypes = "SELECT id, name FROM provinces WHERE active='yes' ORDER BY name";
                                $stmtTypes = $db->conn->prepare($queryTypes);
                                $stmtTypes->execute();
                                /* bind result variables */
                                $stmtTypes->bind_result($id, $name);
                                
                                while ($stmtTypes->fetch()) 
                                {
                          
                                    echo "<option value='$name'>$name</option>";
                            
                                 } 
                            
                            ?>
                            
                        </select>
                        <input type="hidden" name="phone_number" value="<?=USER_PHONE?>">
                    </div>
                   
                </div>
                
                <div class="form-group" id="school-list">
                    <div class="row">
                        <label for="sch_name">School <span class="text-danger">*</span></label>
                        <select id="sch_name" name="school_id" class="form-control" data-parsley-trigger="change" required>
                            
                            <option value="">Select Province First!</option>
                            
                        </select>
                    </div>
                   
                </div>
                
                <div class="form-group" id="student-list">
                    <div class="row">
                        <label for="student_name">Student <span class="text-danger">*</span></label>
                        <select id="student_name" name="reg_no" class="form-control" data-parsley-trigger="change" required>
                            
                            <option value="">Select School First!</option>
                                                                                
                        </select>
                    </div>
                   
                </div>
                
                <div class="form-group">
                                    
                    <div class="row">
                        <label for="dob" class="control-label">Date of Birth <span class="text-danger">*</span></label>
                        <div>
                            <div class="input-group date">
                              <input type="text" readonly class="form-control datepicker" name="dob" id="dob">
                              <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                            </div>
                        </div>
                    </div>
                
                </div>
                
                <hr>
                
                <div class="form-group text-center">
                    <div class="row">
                        <button class="btn btn-primary btn-block">Submit</button>
                    </div>
                </div>
                
            </form>
                                
        </div>
    
    </div>
    
    <!-- /end create new chat -->

	<?php include_once("includes/top_footer.php"); ?>
    
    <?php include_once("includes/bottom_footer.php"); ?>
    
    <?php include_once("includes/js.php"); ?>

</body>
</html>