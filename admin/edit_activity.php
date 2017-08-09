<?php 
	include_once("api/includes/DB_handler.php"); 
	include_once("includes/funcs.php"); 
	include_once("api/includes/Config.php"); 
	
	$admin = true;
	$show_bootstrap_dialog = true;
	
	$page_title = "Edit Aactivity";
	
	$db = new DbHandler();
	
	$id = $arg_two;
	
	//get the statuses
	$query = "SELECT name, start_at, end_at, description, venue FROM sch_activities ";
	$query .= " WHERE id = ? ";
	$stmt = $db->conn->prepare($query);
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($activity_name, $start_at, $end_at, $description, $venue);
	$stmt->fetch();
	
	$page_title = "Edit Activity - " . $activity_name;
	
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

                        <div class="panel panel-default paper-shadow padding-20" data-z="0.5">
                            
                            
                            <form class="form-horizontal form-edit-activity" id="activity-form">
                                
                                <div id="wrapper_form">                                                        
                                    
                                    <div class="form-group">
                                        <label for="activity_name" class="col-sm-3 control-label">Activity Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="name" value="<?=$activity_name?>" placeholder="Activity Name">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="description" class="col-sm-3 control-label">Description</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="description" value="<?=$description?>" placeholder="Description">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="venue" class="col-sm-3 control-label">Venue</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="venue" value="<?=$venue?>" placeholder="Venue">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="start_date" class="col-sm-3 control-label">Start Date</label>
                                        <div class="col-sm-3">
                                            <input id="start_date" name="start_date" type="text" value="<?=$start_at?>" class="form-control datepicker">
                                            <input type="hidden" name="id" value="<?=$id?>">
                                        </div>
                                        <label for="start_time" class="col-sm-3 control-label">Start Time</label>
                                        <div class="col-sm-3">
                                            <input id="datepicker" name="start_time" type="text" value="<?=$start_time?>" class="form-control datepicker">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="end_date" class="col-sm-3 control-label">End Date</label>
                                        <div class="col-sm-3">
                                            <input id="end_date" type="text" name="end_date" value="<?=$end_at?>" class="form-control datepicker">
                                        </div>
                                        <label for="end_time" class="col-sm-3 control-label">End Time</label>
                                        <div class="col-sm-3">
                                            <input id="datepicker" name="end_time" type="text" value="<?=$end_time?>" class="form-control datepicker">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                    
                                        <div class="col-sm-3"></div>
                                        <button class="btn btn-info col-sm-9 col-md-4">Submit</button>
                                        
                                    </div>
                                    
                                </div>
                                
                                <div class="form-group">
                                    <div class="resultdiv"></div>
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