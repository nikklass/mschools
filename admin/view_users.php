<?php 
	include_once("api/includes/DB_handler.php"); 
	include_once("includes/funcs.php"); 
	include_once("api/includes/Config.php");
	
	$admin = true;
	$show_bootstrap_dialog = true;
	$show_form = true;
	
	$page_title = "Users Listing";
	
	$db = new DbHandler();
	
	$show_table = true;
	$show_users_list = true;
	
?>

<?php 
	//if user has read permissions
	if (!(HAS_READ_USER_PERMISSION || SCHOOL_ADMIN_USER)) 
	{
		//user is not allowed to access page
		$page = LOGIN_URL;
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

                        <?php include_once("includes/bread_crumb.php"); ?>

                        <div class="panel panel-default paper-shadow" data-z="0.5">
                          
                            <div class="table-responsive" id="table-responsive" data-tbl="clients" data-tbl-pk="id">
                            
                                <table class="table table-condensed table-hover text-subhead v-middle table-responsive" id="mybootgrid">
                                    <thead>
                                        <tr>
                                            <th data-column-id="id" data-type="numeric" data-identifier="true" data-sortable="true">ID</th>  
                                            <th data-column-id="name" data-sortable="true">Full Names</th>
                                            <th data-column-id="email" data-sortable="true">Email</th>
                                            <th data-column-id="phone" data-sortable="true">Phone</th>
                                            <th data-column-id="user_type" data-sortable="true">User Group</th>
                                            <th data-column-id="status" data-formatter="status-links" data-sortable="true">Status</th>
                                            <th data-column-id="links" data-formatter="links" data-sortable="false">Edit</th>
                                            <th data-column-id="commands" data-formatter="commands" data-sortable="false">Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       
                                      
                                    </tbody>
                                </table>
                                
                            </div>

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