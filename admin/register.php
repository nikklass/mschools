<?php 
	include_once("api/includes/DB_handler.php"); 
	include_once("includes/funcs.php"); 
	include_once("api/includes/Config.php"); 
	
	//$ladda_button = true;
	
	$form_validation = true;
	$show_form = true; //show form css/js
	
	$page_title = "Register";
	
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
                                    
                    <div class="col-md-4 col-md-offset-4">
                                                
                            <div class="panel panel-default login-form-bg">
                                <h1 class="text-display-1 text-center margin-bottom-none"><?=$page_title?></h1>
                                <hr>
                                <div class="panel-body">
                                    <div id="title"></div>
                                    <form class="form-register inputform" data-parsley-validate>
                                        
                                        <div class="resultdiv"></div>
                                    
                                        <!-- Wrapper Form -->
                                        
                                        <div id="wrapper_form">
                                    
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group form-control-material static required form-spacing-top">
                                                        <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Your first name" data-parsley-trigger="change focusout" required>
                                                        <label for="first_name">First name</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-control-material static required form-spacing-top">
                                                        <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Your last name" data-parsley-trigger="change focusout" required>
                                                        <label for="last_name">Last name</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group form-control-material static required form-spacing-top">
                                                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" data-parsley-trigger="change focusout" required>
                                                        <label for="password">Password</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-control-material static required form-spacing-top">
                                                        <input type="password" class="form-control" id="password2" name="password2" placeholder="Enter Repeat Password" data-parsley-equalto="#password" data-parsley-trigger="change focusout" required data-parsley-error-message="Must be same as password field">
                                                        <label for="password2">Repeat Password</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group form-control-material static required form-spacing-top">
                                                <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="Enter phone no" data-parsley-trigger="change focusout" required maxlength="10" required data-parsley-error-message="Should be a valid phone - 07XXXXXXXX">
                                                <label for="phone_number">Phone No.</label>
                                            </div>
                                            <div class="form-group form-control-material static form-spacing-top">
                                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email address" data-parsley-trigger="change focusout" data-parsley-error-message="Should be a valid email">
                                                <label for="email">Email Address</label>
                                            </div>
                                            
                                            <div class="form-group static required form-spacing-top">
                                                <div class="checkbox">
                                                    <input type="checkbox" id="terms" name="terms" checked required/>
                                                    <label for="terms">* I Agree with <a href="#">Terms &amp; Conditions!</a></label>
                                                </div>
                                            </div>
                                            <p>&nbsp;</p>
                                            <div class="form-group text-center">
                                                <div class=" col-sm-12">
                                                    <div class="row">
                                                        <button class="btn btn-primary btn-block">Create Account<i class="fa fa-fw fa-unlock-alt"></i></button>
                                                    </div>
                                                    <p>&nbsp;</p>
                                                    <hr>
                                                </div>
                                            </div>
                                        
                                     	</div>
                                        
                                        <!-- End Wrapper Form -->
                                        
                                        <div class="form-group text-center">
                                        <div class=" col-sm-3"></div>
                                        <div class=" col-sm-9">
                                            <div class="row spacing-top-form-link">

                                                <div class="col-sm-6">
                                                    <a href="<?=SITEPATH?>forgotpass" class="forgot-password">Forgot password?</a>
                                                </div>

                                                <div class="col-sm-6">
                                                    <a href="<?=SITEPATH?>login">Login</a>
                                                </div>

                                                <div class="clearfix"></div>

                                            </div>

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