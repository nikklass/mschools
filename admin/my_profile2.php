<?php 
	include_once("api/includes/DB_handler.php"); 
	include_once("includes/funcs.php"); 
	include_once("api/includes/Config.php"); 
	
	$admin = true;
	$show_bootstrap_dialog = true;
	
	$page_title = "My Profile";
	
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
                        
                        
        
                        <div class="col-md-9">
            
                            <!-- Tabbable Widget -->
                            <div class="tabbable paper-shadow relative" data-z="0.5">
            
                                <!-- Tabs -->
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#main" data-toggle="tab"><i class="fa fa-fw fa-user"></i> <span class="hidden-sm hidden-xs">Main</span></a></li>
                                    <li><a href="#subscriptions" data-toggle="tab"><i class="fa fa-fw fa-users"></i> <span class="hidden-sm hidden-xs">Subscriptions</span></a></li>                                </ul>
                                <!-- // END Tabs -->
            
                                <!-- Panes -->
                                <div class="tab-content">
            
                                    <div id="main" class="tab-pane active">
                                        <form action="#" class="form-horizontal">
                                            <div class="form-group">
                                                <label for="name" class="col-md-2 control-label">Name on Invoice</label>
                                                <div class="col-md-6">
                                                    <div class="form-control-material">
                                                        <input type="text" class="form-control used" id="name" value="Adrian Demian">
                                                        <label for="name">Name on Invoice</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="address" class="col-md-2 control-label">Address</label>
                                                <div class="col-md-6">
                                                    <div class="form-control-material">
                                                        <textarea class="form-control used" id="address">Sunny Street 21, MI</textarea>
                                                        <label for="address">Address</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="country" class="col-md-2 control-label">Country</label>
                                                <div class="col-md-6">
                                                    <select id="country" data-toggle="select2" class="width-100">
                                                        <option value="1" selected>USA</option>
                                                        <option value="2">Country</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group margin-bottom-none">
                                                <div class="col-md-offset-2 col-md-10">
                                                    <button type="submit" class="btn btn-success paper-shadow relative" data-z="0.5" data-hover-z="1" data-animated>Update Billing</button>
                                                </div>
                                            </div>
                                        </form>
                                        <hr/>
            
                                        <div class="media v-middle s-container">
                                            <div class="media-body">
                                                <h5 class="text-subhead">Payment details</h5>
                                            </div>
                                            <div class="media-right">
                                                <a href="#modal-update-credit-card" data-toggle="modal" class="btn btn-white paper-shadow relative" data-animated data-z="0.5" data-hover-z="1" href="">Add Credit Card</a>
                                            </div>
                                        </div>
                                        <div class="list-group margin-none">
                                            <div class="list-group-item media v-middle">
                                                <div class="media-left">
                                                    <div class="icon-block half img-circle bg-primary">
                                                        <i class="fa fa-credit-card"></i>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <h4 class="text-title media-heading">
                            <a href="#modal-update-credit-card" data-toggle="modal" class="link-text-color">**** **** **** 2422</a>
                        </h4>
                                                    <div class="text-caption">updated 1 month ago</div>
                                                </div>
                                                <div class="media-right">
                                                    <a href="#modal-update-credit-card" data-toggle="modal" class="btn btn-white btn-flat"><i class="fa fa-pencil fa-fw"></i> Edit</a>
                                                </div>
                                            </div>
                                            <div class="list-group-item media v-middle">
                                                <div class="media-left">
                                                    <div class="icon-block half img-circle bg-grey-100 text-light">
                                                        <i class="fa fa-credit-card"></i>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <h4 class="text-title media-heading">
                            <a href="#modal-update-credit-card" data-toggle="modal" class="link-text-color">**** **** **** 3365</a>
                        </h4>
                                                    <div class="text-caption">updated 1 year ago</div>
                                                </div>
                                                <div class="media-right">
                                                    <a href="#modal-update-credit-card" data-toggle="modal" class="btn btn-white btn-flat"><i class="fa fa-pencil fa-fw"></i> Edit</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div id="subscriptions" class="tab-pane active">
                                                   
                                        <div class="media v-middle s-container">
                                            <div class="media-body">
                                                <h5 class="text-subhead">Subscriptions</h5>
                                            </div>
                                            <div class="media-right">
                                                <a href="#modal-update-credit-card" data-toggle="modal" class="btn btn-white paper-shadow relative" data-animated data-z="0.5" data-hover-z="1" href="">Add Credit Card</a>
                                            </div>
                                        </div>
                                        <div class="list-group margin-none">
                                            <div class="list-group-item media v-middle">
                                                <div class="media-left">
                                                    <div class="icon-block half img-circle bg-primary">
                                                        <i class="fa fa-credit-card"></i>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <h4 class="text-title media-heading">
                            <a href="#modal-update-credit-card" data-toggle="modal" class="link-text-color">**** **** **** 2422</a>
                        </h4>
                                                    <div class="text-caption">updated 1 month ago</div>
                                                </div>
                                                <div class="media-right">
                                                    <a href="#modal-update-credit-card" data-toggle="modal" class="btn btn-white btn-flat"><i class="fa fa-pencil fa-fw"></i> Edit</a>
                                                </div>
                                            </div>
                                            <div class="list-group-item media v-middle">
                                                <div class="media-left">
                                                    <div class="icon-block half img-circle bg-grey-100 text-light">
                                                        <i class="fa fa-credit-card"></i>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <h4 class="text-title media-heading">
                            <a href="#modal-update-credit-card" data-toggle="modal" class="link-text-color">**** **** **** 3365</a>
                        </h4>
                                                    <div class="text-caption">updated 1 year ago</div>
                                                </div>
                                                <div class="media-right">
                                                    <a href="#modal-update-credit-card" data-toggle="modal" class="btn btn-white btn-flat"><i class="fa fa-pencil fa-fw"></i> Edit</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
            
                                </div>
                                <!-- // END Panes -->
            
                            </div>
                            <!-- // END Tabbable Widget -->
            
                            <!--MODAL-->
                            <?php include_once("includes/modal.php"); ?>
                            <!--END MODAL-->
            
                            <br/>
                            <br/>
            
                        </div>
                        <div class="col-md-3">
            
                            
            				<?php include_once("includes/right_snippets.php"); ?>
                            
            
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