<?php 
	include_once("api/includes/DB_handler.php"); 
	include_once("includes/funcs.php");
	include_once("api/includes/Config.php"); 
	
	$form_validation = true;
	$show_form = true; //show form css/js
	
	$page_title = "Forgot Password";
	
	$db = new DbHandler();
	
	$dont_show_sidebar=true;

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
<html class="st-layout ls-top-navbar-large ls-bottom-footer show-sidebar sidebar-l3" lang="en">

<head>
    
	<?php include_once("includes/head_scripts.php"); ?>
                
    <title><?=$page_title?> :: <?=$page_titles?></title>

</head>

<body class="login">

            
        <div id="content">
        
            <div class="container-fluid padding-20-all">

                
                <div class="col-sm-4 col-sm-offset-4">
                    <div class="panel panel-default text-center login-form-bg" data-z="0.5">
                        <h1 class="text-display-1 text-center margin-bottom-none"><?=$page_title?></h1>
                        <img src="images/icon_big.png" class="img-circle width-80">
                        <div class="panel-body">
                            
                            <form class="form-horizontal form-forgot-pass inputform padding-20-all" data-parsley-validate>
                            
                                <div class="form-group">
                                    <div class="row">
                                		<div class="resultdiv"></div>
                                	</div>
                                </div>
                                
                                <div class="form-group">
                                    <div class="row">
                                		<strong>Enter your phone/ email and we will resend your password</strong>
                                	</div>
                                </div>
                                
                                <hr>
                                
                                <div class="form-group">
                                    <div class="row">
                                        <label for="username">Email/ Phone No</label>
                                        <input class="form-control text-center" id="username" name="username" type="text" data-parsley-trigger="change focusout" data-parsley-required required>
                                        
                                    </div>
                                </div>
                                
                                <div class="form-group text-center">
                                    <div class="row hidden-field hidden-1">
                                        <button class="btn btn-primary btn-block">Login<i class="fa fa-fw fa-unlock-alt"></i></button>
                                    </div>
                                    <p>&nbsp;</p>
                                    <hr>
                                    <div class="row spacing-top-form-link">

                                        <div class="col-sm-6">
                                            <a href="<?=SITEPATH?>login" class="login">Login</a>
                                        </div>

                                        <div class="col-sm-6">
                                            <a href="<?=SITEPATH?>register">Create account</a>
                                        </div>

                                        <div class="clearfix"></div>

                                    </div>

                                </div>
                                
                            </form>
                            
                        </div>
                    </div>
                </div>

        
            </div>
            
        </div>
        
        <!-- Footer -->
        <footer class="footer">
            
            <?php include_once("includes/footer.php"); ?>
            
        </footer>
        <!-- // Footer -->


    
    <?php include_once("includes/js.php"); ?>
    

</body>

</html>