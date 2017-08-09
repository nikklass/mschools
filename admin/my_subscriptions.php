<?php 
	include_once("api/includes/DB_handler.php"); 
	include_once("includes/funcs.php"); 
	include_once("api/includes/Config.php"); 
	
	$admin = true;
	$show_bootstrap_dialog = true;
	$show_subs_list = true;
	$show_delete = true;
	$show_form = true;
	$form_validation = true;
	
	$page_title = "My Subscriptions";
	
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
                        
                        
        
                        <div class="col-md-9">
            
                            <!-- Tabbable Widget -->
                            <div class="tabbable paper-shadow relative" data-z="0.5">
            
                                <!-- Tabs -->
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#subscriptions" data-toggle="tab"><i class="fa fa-fw fa-users"></i> <span class="hidden-sm hidden-xs">Subscriptions</span></a></li>                                </ul>
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
                        <div class="col-md-3">
            
                            
            				<!--right-->
                            
                            
                            
                                            
                                <div class="panel panel-default text-center login-form-bg" data-z="0.5">
                                    <h3 class="text-display-1 text-center margin-bottom-none">Add Subscription</h3>
                                    <hr>
                                    <div class="panel-body">
                                        
                                        <form class="form-add-subscription inputform padding-no-top-20" data-parsley-validate>
                                        
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="resultdiv"></div>
                                                </div>
                                            </div>
                                          
                                            <div class="form-group">
                                                <div class="row">
                                                	<label for="sch_prov">Province</label>
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
                                            
                                            <div class="form-group hidden" id="school-list">
                                                <div class="row">
                                                	<label for="sch_name">School</label>
                                                    <select id="sch_name" name="school_id" class="form-control" data-parsley-trigger="change" required>
                                                        
                                                        <option value="">Select School</option>
														
                                                    </select>
                                                </div>
                                               
                                            </div>
                                            
                                            <div class="form-group hidden" id="student-list">
                                                <div class="row">
                                                	<label for="student_name">Student</label>
                                                    <select id="student_name" name="reg_no" class="form-control" data-parsley-trigger="change" required>
                                                                                                                
                                                    </select>
                                                </div>
                                               
                                            </div>
                                            
                                            <p>&nbsp;</p>
                                            
                                            <div class="form-group text-center">
                                                <div class="row">
                                                    <button class="btn btn-primary btn-block">Submit</button>
                                                </div>
                                            </div>
                                            
                                        </form>
                                        
                                    </div>
                                </div>
                            
                            



                            
                            <!--end right-->
                            
            
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