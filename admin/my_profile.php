<?php 
	include_once("api/includes/DB_handler.php"); 
	include_once("includes/funcs.php"); 
	include_once("api/includes/Config.php");
	
	$admin = true;
	$show_bootstrap_dialog = true;
	
	$show_form = true;
	
	$show_table = true; // will show bootgrid table
	//$show_activities = true; // will load activities into table
	
	//$show_lightbox = true; //lightbox
	$show_file_upload = true; //show file upload css/ js
		
	$db = new DbHandler();
		
	$user_id = USER_ID;
	
	//get the statuses
	$query = "SELECT full_names, first_name, last_name, email, phone_number, user_group, status, receive_messages FROM clients WHERE id = ? ";
	//echo "query - $query - $user_id"; exit;
	$stmt = $db->conn->prepare($query);
	$stmt->bind_param("i", $user_id);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($full_names, $first_name, $last_name, $email, $phone_number, $user_group, $status, $receive_messages);
	$stmt->fetch();
	
	$tfull_names = $full_names;
	$tfull_names = trim($tfull_names);
	if (!$tfull_names){ $tfull_names = $first_name." ".$last_name; }
	
	$page_title = "My Profile - " . $tfull_names;
		
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
                                    <li class="active"><a href="#main" data-toggle="tab"><i class="fa fa-fw fa-lock"></i> <span class="hidden-sm hidden-xs">User Details</span></a></li>
                                    <li><a href="#photos" data-toggle="tab"><i class="fa fa-fw fa-credit-card"></i> <span class="hidden-sm hidden-xs">User Photo</span></a></li>
                                </ul>
                                <!-- // END Tabs -->
                            	
                                <div id="title"></div>
                            
                                <!-- Panes -->
                                <div class="tab-content">

                                    <div id="main" class="tab-pane active">
                                        
                                        <form class="form-horizontal form-edit-user">
                                            
                                            <div class="form-group">
                                            	<div class="col-sm-3"></div>
                                                <div class="col-sm-9">
                                                	<div class="resultdiv"></div>
                                                </div>
                                            </div>
                                            
                                            <div class="wrapper_form">
                                            
                                                <div class="form-group">
                                                    
                                                    <label for="first_name" class="col-sm-3 control-label">First Name</label>
                                                    <div class="col-sm-3">
                                                        <input type="text" class="form-control" name="first_name" value="<?=$first_name?>">
                                                        <input type="hidden" name="user_id" value="<?=$user_id?>">
                                                    </div>
                                                    
                                                    <label for="last_name" class="col-sm-3 control-label">Last Name</label>
                                                    <div class="col-sm-3">
                                                        <input type="text" class="form-control" name="last_name" value="<?=$last_name?>">
                                                    </div>
                                                    
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="full_names" class="col-sm-3 control-label">Full Names</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" name="full_names" value="<?=$full_names?>" placeholder="Enter Full Names">
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    
                                                    <label for="email" class="col-sm-3 control-label">Email</label>
                                                    <div class="col-sm-3">
                                                        <input type="text" class="form-control" name="email" value="<?=$email?>">
                                                    </div>
                                                    
                                                    <label for="phone_number" class="col-sm-3 control-label">Phone Number</label>
                                                    <div class="col-sm-3">
                                                        <input type="text" class="form-control" name="phone_number" value="<?=$phone_number?>">
                                                    </div>
                                                    
                                                </div>
                                                
                                                
                                                
                                                
                                                <div class="form-group">
                                                    
                                                    <div class="col-sm-3"></div>
                                                    <div class="col-sm-9">
                                                    <button type="submit" class="btn btn-lg btn-primary btn-block">Submit</button>
                                                    </div>
                                                    
                                                </div>
                                                          
                                            </div>                                                              
                                                                                       
                                        </form>
                                    </div>
                                    
                                    <div id="photos" class="tab-pane">
                                    
                                        <div class="col-sm-8">
                                        
                                            <div class="text-center">
                                            	<h3>Upload A New Photo</h3>
                                            </div>
                                            
                                            <!-- the avatar markup -->
                                            <div id="kv-avatar-errors-1" class="center-block" style="width:800px;display:none"></div>
                                            <form enctype="multipart/form-data" method="post" class="form-upload-user-pic">
                                                
                                                <div class="resultdiv"></div>
                                                
                                                <div class="wrapper_form">
                                                    <div class="form-group padding-20">
                                                        <div class="kv-avatar center-block" style="width:200px">
                                                            <input id="avatar-1" name="user_pic" type="file" class="file-loading">
                                                            <input type="hidden" name="user_id" value="<?=$user_id?>">
                                                            <input type="hidden" name="user_profile" value="1">
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
                                                	$img = $db->getPhoto(USER_PROFILE_PHOTO, $user_id); // echo "img - $img";
                                                ?>
                                                <img src="<?=$img?>" width="400">
                                                
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