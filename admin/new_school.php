<?php 
	include_once("api/includes/DB_handler.php"); 
	include_once("api/includes/Config.php"); 
	include_once("includes/funcs.php"); 
	
	$admin = true;
	$show_bootstrap_dialog = true;
	$show_form = true;
	$form_validation = true; //form validation classes
	
	$page_title = "Create New School";
	
	$db = new DbHandler();
	
	$this_page_link = getTheCurrentUrl();
	
?>

<?php include_once("includes/check_if_logged_in.php"); ?>

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

                        <div class="panel panel-default paper-shadow padding-20" data-z="0.5">
                            
                            
                            <form class="form-horizontal form-new-school inputform" data-parsley-validate>
                                            
                                <div class="resultdiv"></div>
                                
                                <div class="form-group">
                                    <label for="sch_name" class="col-sm-3 control-label">School Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="sch_name" placeholder="School Name" data-parsley-type="alphanum" data-parsley-trigger="change" data-parsley-required required>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="category" class="col-sm-3 control-label">Category</label>
                                    <div class="col-sm-9">
                                        <select id="category" name="sch_category" class="form-control" data-parsley-trigger="change" data-parsley-required required>
                                                                                        
                                            <option value=''>Please select</option>
                                            
											<?php
                                                    
                                                //get the user types
                                                $queryTypes = "SELECT id, name FROM sch_categories WHERE active='yes' ORDER BY name";
                                                $stmtTypes = $db->conn->prepare($queryTypes);
                                                $stmtTypes->execute();
                                                /* bind result variables */
                                                $stmtTypes->bind_result($id, $name);
                                                
                                                while ($stmtTypes->fetch()) 
                                                {
                                          
                                                    echo "<option value='$id' ";
                                                    
                                                    if ($sch_category == $id) { echo " selected "; } 
                                                    
                                                    echo ">$name</option>";
                                            
                                                 } 
                                            
                                            ?>
                                            
                                        </select>
                                    </div>
                                    <div class="col-sm-1 result"></div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="province" class="col-sm-3 control-label">Province</label>
                                    <div class="col-sm-9 col-md-3">
                                        <select id="select" name="sch_province" class="form-control" data-parsley-trigger="change" data-parsley-required required>
                                            
                                            <option value=''>Please select</option>
                                                                                        
                                            <?php
                                                    
                                                //get the user types
                                                $queryTypes = "SELECT id, name FROM provinces WHERE active='yes' ORDER BY name";
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
                                    
                                    <label for="county" class="col-sm-3 control-label">County</label>
                                    <div class="col-sm-9 col-md-3">
                                        <select id="select" name="sch_county" class="form-control" data-parsley-trigger="change" data-parsley-required required>
                                            
                                            <option value=''>Please select</option>
                                            
                                            <?php
                                                    
                                                //get the user types
                                                $queryTypes = "SELECT id, name FROM counties WHERE active='yes' ORDER BY name";
                                                $stmtTypes = $db->conn->prepare($queryTypes);
                                                $stmtTypes->execute();
                                                /* bind result variables */
                                                $stmtTypes->bind_result($id, $name);
                                                
                                                while ($stmtTypes->fetch()) 
                                                {
                                          
                                                    echo "<option value='$id' ";
                                                    
                                                    if ($sch_county == $id) { echo " selected "; } 
                                                    
                                                    echo ">$name</option>";
                                            
                                                 } 
                                            
                                            ?>
                                            
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="status" class="col-sm-3 control-label">Status</label>
                                    <div class="col-sm-9">
                                        <select id="select" name="status" class="form-control" data-parsley-trigger="change" data-parsley-required required>
                                                                                        
                                            <?php
                                                    
                                                //get the user types
                                                $queryTypes = "SELECT id, name FROM status ORDER BY name";
                                                $stmtTypes = $db->conn->prepare($queryTypes);
                                                $stmtTypes->execute();
                                                /* bind result variables */
                                                $stmtTypes->bind_result($id, $name);
                                                
                                                while ($stmtTypes->fetch()) 
                                                {
                                          
                                                    echo "<option value='$id' ";
                                                    
                                                    if ($status == $id) { echo " selected "; } 
                                                    
                                                    echo ">$name</option>";
                                            
                                                 } 
                                            
                                            ?>
                                            
                                        </select>
                                    </div>
                                </div>
                                
                                <!--$extra, $sch_profile, $events_calender, $sms_welcome1, $sms_welcome2-->
                                <div class="form-group">
                                    <label for="motto" class="col-sm-3 control-label">School Motto</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="motto" placeholder="School Motto" data-parsley-type="alphanum" data-parsley-trigger="change">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="phone1" class="col-sm-3 control-label">Phone 1</label>
                                    <div class="col-sm-9 col-md-3">
                                        <input type="text" class="form-control" name="phone1" placeholder="Phone 1" data-parsley-type="alphanum" data-parsley-trigger="change">
                                    </div>
                                
                                    <label for="phone2" class="col-sm-3 control-label">Phone 2</label>
                                    <div class="col-sm-9 col-md-3">
                                        <input type="text" class="form-control" name="phone2" placeholder="Phone 2" data-parsley-type="alphanum" data-parsley-trigger="change">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="sms_welcome1" class="col-sm-3 control-label">SMS Welcome 1</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="sms_welcome1" placeholder="SMS Welcome 1" data-parsley-type="alphanum" data-parsley-trigger="change">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="sms_welcome2" class="col-sm-3 control-label">SMS Welcome 2</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="sms_welcome2" placeholder="SMS Welcome 2" data-parsley-type="alphanum" data-parsley-trigger="change">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="address" class="col-sm-3 control-label">Address</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" rows="4" name="address" placeholder="Address" data-parsley-type="alphanum" data-parsley-trigger="change"></textarea>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9">
                                    <button class="btn btn-lg btn-info col-sm-12">Submit</button>
                                    </div>
                                    
                                </div>
                                
                                
                               
                            </form>
                            

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