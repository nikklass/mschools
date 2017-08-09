<?php 
	include_once("api/includes/DB_handler.php"); 
	include_once("includes/funcs.php"); 
	include_once("api/includes/Config.php");
	
	$admin = true;
	$show_bootstrap_dialog = true;
	
?>

<!DOCTYPE html>
<html class="st-layout ls-top-navbar-large ls-bottom-footer show-sidebar sidebar-l3" lang="en">

<head>
    
	<?php include_once("includes/head_scripts.php"); ?>
                
    <title>Edit Profile :: <?=$page_titles?></title>

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
        
                        <div class="page-section third">
                            <!-- Tabbable Widget -->
                            <div class="tabbable paper-shadow relative" data-z="0.5">
        
                                <!-- Tabs -->
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="app-instructor-profile.html"><i class="fa fa-fw fa-lock"></i> <span class="hidden-sm hidden-xs">Manage Account</span></a></li>
                                    <li><a href="app-instructor-billing.html"><i class="fa fa-fw fa-credit-card"></i> <span class="hidden-sm hidden-xs">Billing Details</span></a></li>
                                </ul>
                                <!-- // END Tabs -->
        
                                <!-- Panes -->
                                <div class="tab-content">
        
                                    <div id="account" class="tab-pane active">
                                        <form class="form-horizontal">
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-2 control-label">Avatar</label>
                                                <div class="col-md-6">
                                                    <div class="media v-middle">
                                                        <div class="media-left">
                                                            <div class="icon-block width-100 bg-grey-100">
                                                                <i class="fa fa-photo text-light"></i>
                                                            </div>
                                                        </div>
                                                        <div class="media-body">
                                                            <a href="#" class="btn btn-white btn-sm paper-shadow relative" data-z="0.5" data-hover-z="1" data-animated> Add Image<i class="fa fa-upl"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-md-2 control-label">Full Name</label>
                                                <div class="col-md-8">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-control-material">
                                                                <input type="email" class="form-control" id="exampleInputFirstName" placeholder="Your first name">
                                                                <label for="exampleInputFirstName">First name</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-control-material">
                                                                <input type="email" class="form-control" id="exampleInputLastName" placeholder="Your last name">
                                                                <label for="exampleInputLastName">Last name</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-md-2 control-label">Email</label>
                                                <div class="col-md-6">
                                                    <div class="form-control-material">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                                            <input type="email" class="form-control" id="inputEmail3" placeholder="Email">
                                                            <label for="inputEmail3">Email address</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-md-2 control-label">Website</label>
                                                <div class="col-md-6">
                                                    <div class="form-control-material">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-link"></i></span>
                                                            <input type="text" class="form-control used" id="website" value="www.mosaicpro.biz">
                                                            <label for="website">Website</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputPassword3" class="col-md-2 control-label">Change Password</label>
                                                <div class="col-md-6">
                                                    <div class="form-control-material">
                                                        <input type="password" class="form-control" id="inputPassword3" placeholder="Password">
                                                        <label for="inputPassword3">Password</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-offset-2 col-md-6">
                                                    <div class="checkbox checkbox-success">
                                                        <input id="checkbox3" type="checkbox" checked="">
                                                        <label for="checkbox3">Subscribe to our Newsletter</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group margin-none">
                                                <div class="col-md-offset-2 col-md-10">
                                                    <button type="submit" class="btn btn-primary paper-shadow relative" data-z="0.5" data-hover-z="1" data-animated>Save Changes</button>
                                                </div>
                                            </div>
                                        </form>
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