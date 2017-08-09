<?php 
	include_once("admin/api/includes/DB_handler.php"); 
	include_once("admin/api/includes/Config.php");
	
	$form_validation = true; //form validation classes 
	$show_form = true;

	$page_title = "Register";
	
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
                    <span class="icon-block half bg-blue2-600 text-white"><i class="fa fa-edit"></i></span>
                </div>
                <div class="media-body">
                    <h3 class="text-display-2 text-white margin-none"><?=$page_title?></h3>
                    <!--<p class="text-white text-subhead"></p>-->
                </div>
            </div>
        </div>
    </div>
    
    <div class="container">
    
        <div class="page-section">
            <div class="row">
    
                <div class="col-md-8">
    
                    <div class="panel panel-default paper-shadow" data-z="0.5">
                        
                        <div id="title"></div>
                        
                        <div class="panel-heading">
                            <h4 class="text-headline">Registration Details</h4>
                        </div>
                        
                        <div class="panel-body">
                            
                            <form class="form-horizontal form-register inputform" data-parsley-validate>
                                                        
                                <div class="resultdiv"></div>
                                                                            
                                <div id="wrapper_form">
                                 
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label for="phone_number" class="col-sm-3 control-label">Phone Number <span class="text-danger">*</span></label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="phone_number" data-parsley-trigger="change" required>
                                            </div>
                                        </div>
                                       
                                    </div>
                                    
                                    
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label for="password" class="col-sm-3 control-label">Password <span class="text-danger">*</span></label>
                                            <div class="col-sm-9">
                                                <input type="password" class="form-control" name="password" required>
                                            </div>
                                        </div>
                                       
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label for="password2" class="col-sm-3 control-label">Repeat Password <span class="text-danger">*</span></label>
                                            <div class="col-sm-9">
                                                <input type="password" class="form-control" name="password2" required>
                                            </div>
                                        </div>
                                       
                                    </div>
                                    
                                    <hr>
                                    
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label for="full_names" class="col-sm-3 control-label">Full Names <span class="text-danger">*</span></label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="full_names" data-parsley-trigger="change" required>
                                            </div>
                                        </div>
                                       
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label for="email" class="col-sm-3 control-label">Email Address</label>
                                            <div class="col-sm-9">
                                                <input type="email" class="form-control" name="email">
                                            </div>
                                        </div>
                                       
                                    </div>
                                    
                                    <div class="form-group">
                                    
                                    	<div class="col-sm-12">
                                                        
                                            <label class="col-sm-3 control-label">Gender <span class="text-danger">*</span></label>
                                            
                                            <div class="row col-data col-sm-9">
                                                
                                                <div class="col-sm-6">
                                                    <div class="radio radio-info">
                                                        <input id="male_radio_edit" name="gender" value="m" checked="checked" type="radio">
                                                        <label for="male_radio_edit"> Male </label>
                                                    </div>
                                                 </div>
                                                 
                                                <div class="col-sm-6">
                                                    <div class="radio radio-info">
                                                        <input id="female_radio_edit" name="gender" value="f" type="radio">
                                                        <label for="female_radio_edit"> Female </label>
                                                    </div>
                                                 </div>
                                                 
                                                 <div class="clear"></div> 
                                                
                                            </div>
                                            
                                            <div class="clear"></div>
                                            
                                        </div>
                                        
                                    </div>
                                                                                                                                                                        
                                    <hr>
                                    
                                    <div class="form-group">
                                    
                                        <div class="col-sm-12">
                                            <div class="col-sm-3"></div>
                                            <div class="col-sm-9">
                                                <div class="checkbox checkbox-info">
                                                    <input type="checkbox" id="terms" name="terms" checked required/>
                                                    <label for="terms">&nbsp;&nbsp;&nbsp; I Agree with <a href="#">Terms &amp; Conditions</a></label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    
                                    <hr>  
                                    
                                    <div class="form-group">
                                       <div class="col-sm-12">
                                            <div class="col-sm-3"></div>
                                            <div class="col-sm-9">
                                                <button class="btn btn-lg btn-info btn-block">Submit</button>
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