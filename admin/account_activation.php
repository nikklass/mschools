<?php 
	include_once("api/includes/DB_handler.php"); 
	include_once("includes/funcs.php"); 
	include_once("api/includes/Config.php");
	
	$form_validation = true;
	
	$page_title = "Account Activation Steps";
	
	$db = new DbHandler();
	
	$dont_show_sidebar=true;

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
                    
                        <div class="panel panel-default text-center login-form-bg" data-z="0.5">
                            
                            <h1 class="text-display-1 text-center margin-bottom-none"><?=$page_title?></h1>
                            
                            <div class="panel-body">
                                
                                <?=ACCOUNT_ACTIVATION_INSTRUCTIONS?>

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