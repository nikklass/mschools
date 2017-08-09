<?php 
	include_once("admin/api/includes/DB_handler.php"); 
	include_once("admin/api/includes/Config.php");
	
	$form_validation = true; //form validation classes 

	$page_title = "Login";
	
?>

<?php
	if (IS_USER_LOGGED_IN) {
		//user is logged in redirect to home page
		$home_page = SITEPATH;
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
                    <span class="icon-block half bg-blue2-600 text-white"><i class="fa fa-lock"></i></span>
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
    
                    <div class="panel panel-default paper-shadow" data-z="0.5">
                        <div class="panel-heading">
                            <h4 class="text-headline">Enter Login Details</h4>
                        </div>
                        <div class="panel-body">
                            
                            <form class="form-horizontal form-signin inputform" method="post" data-parsley-validate>
                                                        
                                <div class="resultdiv"></div>
                                                                            
                                <div id="wrapper_form">
                                 
                                    <div class="form-group padding-tb-20">
                                        <div class="col-sm-12">
                                            <label for="phone_number" class="col-sm-3 control-label">Phone Number/ User ID</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="phone_number" data-parsley-trigger="change" required>
                                            </div>
                                        </div>
                                       
                                    </div>
                                    
                                    
                                    <div class="form-group padding-btm-20">
                                        <div class="col-sm-12">
                                            <label for="password" class="col-sm-3 control-label">Password</label>
                                            <div class="col-sm-9">
                                                <input type="password" class="form-control" name="password" required>
                                            </div>
                                        </div>
                                       
                                    </div>
                                    
                                    <div class="form-group">
                                       <div class="col-sm-12">
                                            <div class="col-sm-3"></div>
                                            <div class="col-sm-9">
                                                <button class="btn btn-lg btn-primary btn-block">Login</button>
                                            </div>
                                        </div>
                                    </div>
                                
                                </div>

                                                                           
                           </form>
                            
                        </div>
                        
                    </div>
    
                </div>
                
                <div class="col-md-4">
    
                    <?php include_once("includes/sidebar_login.php"); ?>
    
                </div>
    
            </div>
        </div>
    
    </div>

	<?php include_once("includes/top_footer.php"); ?>
    
    <?php include_once("includes/bottom_footer.php"); ?>
    
    <?php include_once("includes/js.php"); ?>

</body>
</html>