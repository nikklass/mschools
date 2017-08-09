<?php 
	include_once("api/includes/DB_handler.php"); 
	include_once("api/includes/Config.php"); 
	//include_once("../api/includes/conns.php"); 
	include_once("includes/funcs.php"); 
	
	$admin = true;
	$show_bootstrap_dialog = true;
	
	$show_form = true;
	
	$show_table = true; // will show bootgrid table
	$show_activities = true; // will load activities into table
	
	$show_lightbox = true; //lightbox
	$show_file_upload = true; //show file upload css/ js
		
	$db = new DbHandler();
		
	$sch_id = $arg_two;
	
	//get the statuses
	$query = "SELECT sch_name, address, province, sch_county, extra, sch_profile, events_calender, sms_welcome1";
	$query .= ",motto, status, sms_welcome2, phone1, phone2, sch_category FROM sch_ussd ";
	$query .= " WHERE sch_id = ? ";
	$stmt = $db->conn->prepare($query);
	$stmt->bind_param("i", $sch_id);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($sch_name, $address, $sch_province, $sch_county, $extra, $sch_profile, $events_calender, $sms_welcome1, $motto, $status, $sms_welcome2, $phone1, $phone2, $sch_category);
	$stmt->fetch();
	
	//echo "sch_province - $sch_province - $sch_name"; exit;
		
	//profile pic
	
	
	$page_title = "Edit - " . $sch_name;
		
?>

<?php 
	//if user has read permissions
	if (!(HAS_CREATE_SCHOOL_PERMISSION || HAS_EDIT_SCHOOL_PERMISSION)) 
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
                                    <li class="active"><a href="#main" data-toggle="tab"><i class="fa fa-fw fa-lock"></i> <span class="hidden-sm hidden-xs">School Details</span></a></li>
                                    <li><a href="#photos" data-toggle="tab"><i class="fa fa-fw fa-credit-card"></i> <span class="hidden-sm hidden-xs">School Profile Photo</span></a></li>
                                    <li><a href="#third" data-toggle="tab"><i class="fa fa-fw fa-credit-card"></i> <span class="hidden-sm hidden-xs">School Activities</span></a></li>
                                </ul>
                                <!-- // END Tabs -->
                            
                            
                                <!-- Panes -->
                                <div class="tab-content">
                            
                                    
                            
                                    
                                    <div id="main" class="tab-pane active">
                                        <form class="form-horizontal">
                                            
                                            <div id="tbl-settings" data-tbl="sch_ussd" data-pk="sch_id" data-pkval="<?=$sch_id?>"></div>
                                            
                                            <div class="form-group">
                                                <label for="sch_name" class="col-sm-3 control-label">School Name</label>
                                                <div class="col-sm-8 col-md-6">
                                                    <input type="text" class="form-control input" name="sch_name" data-tp="s" placeholder="School Name" value="<?=$sch_name?>">
                                                </div>
                                                <div class="col-sm-1 result"></div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="category" class="col-sm-3 control-label">Category</label>
                                                <div class="col-sm-9 col-md-6">
                                                    <select id="category" name="sch_category" class="form-control input" data-tp="i">
                                                        
                                                        <option value=''>Please select</option>
                                                        
														<?php
																
															//get the user types
															$queryTypes = "SELECT id, name FROM sch_levels ORDER BY name";
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
                                                <div class="col-sm-9 col-md-6">
                                                    <select id="select" name="province" class="form-control input" data-tp="s">
                                                        
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
													  
																echo "<option value='$name' ";
																
																if ($sch_province == $name) { echo " selected "; } 
																
																echo ">$name</option>";
														
															 } 
														
														?>
                                                        
                                                    </select>
                                                </div>
                                                <div class="col-sm-1 result"></div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="county" class="col-sm-3 control-label">County</label>
                                                <div class="col-sm-9 col-md-6">
                                                    <select id="select" name="sch_county" class="form-control input" data-tp="i">
                                                        
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
                                                <div class="col-sm-1 result"></div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="status" class="col-sm-3 control-label">Status</label>
                                                <div class="col-sm-9 col-md-6">
                                                    <select id="select" name="status" class="form-control input" data-tp="i">
                                                        
                                                        <option value=''>Please select</option>
                                                        
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
                                                <div class="col-sm-1 result"></div>
                                            </div>
                                            
                                            <!--$extra, $sch_profile, $events_calender, $sms_welcome1, $sms_welcome2-->
                                            <div class="form-group">
                                                <label for="motto" class="col-sm-3 control-label">School Motto</label>
                                                <div class="col-sm-9 col-md-6">
                                                    <input type="text" class="form-control input" name="motto" placeholder="School Motto" value="<?=$motto?>" data-tp="s">
                                                </div>
                                                <div class="col-sm-1 result"></div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="phone1" class="col-sm-3 control-label">Phone 1</label>
                                                <div class="col-sm-9 col-md-6">
                                                    <input type="text" class="form-control input" name="phone1" placeholder="Phone 1" value="<?=$phone1?>" data-tp="s">
                                                </div>
                                                <div class="col-sm-1 result"></div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="phone2" class="col-sm-3 control-label">Phone 2</label>
                                                <div class="col-sm-9 col-md-6">
                                                    <input type="text" class="form-control input" name="phone2" placeholder="Phone 2" value="<?=$phone2?>" data-tp="s">
                                                </div>
                                                <div class="col-sm-1 result"></div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="sms_welcome1" class="col-sm-3 control-label">SMS Welcome 1</label>
                                                <div class="col-sm-9 col-md-6">
                                                    <input type="text" class="form-control input" name="sms_welcome1" placeholder="SMS Welcome 1" value="<?=$sms_welcome1?>" data-tp="s">
                                                </div>
                                                <div class="col-sm-1 result"></div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="sms_welcome2" class="col-sm-3 control-label">SMS Welcome 2</label>
                                                <div class="col-sm-9 col-md-6">
                                                    <input type="text" class="form-control input" name="sms_welcome2" placeholder="SMS Welcome 2" value="<?=$sms_welcome2?>" data-tp="s">
                                                </div>
                                                <div class="col-sm-1 result"></div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="address" class="col-sm-3 control-label">Address</label>
                                                <div class="col-sm-9 col-md-6">
                                                    <textarea class="form-control input" rows="4" name="address" placeholder="Address" class="input" data-tp="s"><?=$address?></textarea>
                                                </div>
                                                <div class="col-sm-1 result"></div>
                                            </div>
                                           
                                        </form>
                                    </div>
                                    
                                    <div id="photos" class="tab-pane">
                                    
                                        <div class="col-sm-8">
                                        
                                            <div class="text-center">
                                            	<h3>Upload A Profile Photo</h3>
                                            </div>
                                            
                                            <!-- the avatar markup -->
                                            <div id="kv-avatar-errors-1" class="center-block" style="width:800px;display:none"></div>
                                            <form enctype="multipart/form-data" method="post" class="form-upload-user-pic">
                                                
                                                <div class="resultdiv"></div>
                                                
                                                <div class="wrapper_form">
                                                    <div class="form-group padding-20">
                                                        <div class="kv-avatar center-block" style="width:200px">
                                                            <input id="avatar-1" name="user_pic" type="file" class="file-loading">
                                                            <input type="hidden" name="school_id" value="<?=$sch_id?>">
                                                            <input type="hidden" name="school_profile" value="1">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-sm-4 col-sm-offset-4">
                                                                <button class="btn btn-lg btn-primary btn-block">Submit</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            
                                            </form>
                                        
                                        </div>
                                        
                                        <div class="col-sm-4">
                                        	
                                            <div class="thumbnail">
                                                
                                                <?php
                                                	$img = $db->getPhoto(SCHOOL_PROFILE_PHOTO, $sch_id); // echo "img - $img";
                                                ?>
                                                <img src="<?=$img?>" width="400">
                                                
                                            </div>
                                            
                                        </div>
                                        
                                        <div class="clear"></div>
                                        
                                    </div>
                                    
                                    <div id="third" class="tab-pane">
                                        <div class="col-sm-5">
                                        <form class="form-horizontal form-new-activity inputform" id="activity-form">
                                            
                                            <h3>Add New Activity</h3>
                                            <br>
                                            
                                            <div id="title"></div>
                                            
                                            <div class="resultdiv"></div>
                                            
                                            <div id="wrapper_form">
                                                                                            
                                                <div class="form-group">
                                                    <label for="activity_name" class="col-sm-3 control-label">Activity Name</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" name="name" placeholder="Activity Name">
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="description" class="col-sm-3 control-label">Description</label>
                                                    <div class="col-sm-9">
                                                        <textarea class="form-control" rows="3" name="description" placeholder="Description"></textarea>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="venue" class="col-sm-3 control-label">Venue</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" name="venue" placeholder="Venue">
                                                        <input type="hidden" name="id" value="<?=$sch_id?>">
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="start_date" class="col-sm-3 control-label">Start Date</label>
                                                    <div class="col-sm-3">
                                                        <input id="datepicker" name="start_date" type="text" class="form-control datepicker">
                                                    </div>
                                                    <label for="start_time" class="col-sm-3 control-label">Start Time</label>
                                                    <div class="col-sm-3">
                                                        <input id="datepicker" name="start_time" type="text" class="form-control datepicker">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="end_date" class="col-sm-3 control-label">End Date</label>
                                                    <div class="col-sm-3">
                                                        <input id="datepicker" type="text" name="end_date" class="form-control datepicker">
                                                    </div>
                                                    <label for="end_time" class="col-sm-3 control-label">End Time</label>
                                                    <div class="col-sm-3">
                                                        <input id="datepicker" name="end_time" type="text" class="form-control datepicker">
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                
                                                    <div class="col-sm-3"></div>
                                                    <div class="col-sm-9">
                                                    <button class="btn btn-info col-sm-12">Submit</button>
                                                    </div>
                                                    
                                                </div>
                                                
                                            </div>
                                            
                                            
                                            
                                        </form>
                                        </div>
                                        <div class="col-sm-7">
                                            
                                            
                                            <div class="container-fluid">
                                                                    
                                                <div class="panel panel-default paper-shadow" data-z="0.5" id="papershadow">
                                                    
                                                    <div class="table-responsive" id="table-responsive" data-tbl="sch_activities">
                                                        <table class="table table-condensed table-hover table-striped" id="mybootgrid">
                                                            <thead>
                                                                <tr>
                                                                    <th data-column-id="id" data-type="numeric" data-identifier="true">ID</th>  
                                                                    <th data-column-id="name">Activity Title</th>
                                                                    <th data-column-id="start_at" data-converter="datetime">Start Date</th>
                                                                    <th data-column-id="end_at" data-converter="datetime">End Date</th>
                                                                    <th data-column-id="links" data-formatter="links" data-sortable="false">Edit</th>
                                                                    <th data-column-id="commands" data-formatter="commands" data-sortable="false">Delete</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                               
                                                              
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="panel-footer">
                                                        
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