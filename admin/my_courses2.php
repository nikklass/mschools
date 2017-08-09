<?php 
	include_once("api/includes/DB_handler.php"); 
	include_once("api/includes/Config.php"); 
	include_once("includes/funcs.php"); 
	
	$admin = true;
	$show_bootstrap_dialog = true;
	
?>

<!DOCTYPE html>
<html class="st-layout ls-top-navbar-large ls-bottom-footer show-sidebar sidebar-l1 sidebar-r3" lang="en">
<head>
    
	<?php include_once("includes/head_scripts.php"); ?>
                
    <title>Take Course ::
    <?=$page_titles?>
    </title>

</head>
<body>

<!-- Wrapper required for sidebar transitions -->
<div class="st-container">

	
    <?php include_once("includes/nav_collapsed.php"); ?>
    

    <?php include_once("includes/left_sidebar_collapsed.php"); ?>
    
    
    <?php include_once("includes/right_sidebar_collapsed.php"); ?>
        

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
    <div class="media v-middle">
        <div class="media-body">
            <h1 class="text-display-1 margin-none">My courses</h1>
        </div>
        <div class="media-right">
            <a class="btn btn-white paper-shadow relative" data-z="0.5" data-hover-z="1" data-animated href="app-directory-list.html">All Courses</a>
        </div>
    </div>
</div>

<div class="row" data-toggle="isotope">
    <div class="item col-xs-12 col-sm-6 col-lg-4">
        <div class="panel panel-default paper-shadow" data-z="0.5">
    
    <div class="panel-heading">
        <div class="media media-clearfix-xs-min v-middle">
            <div class="media-body text-caption text-light">
                Lessons 5 of 8
            </div>
            <div class="media-right">
                <div class="progress progress-mini width-100 margin-none">
                    <div class="progress-bar progress-bar-grey-600" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width:75%;">
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="cover overlay cover-image-full hover">
        <span class="img icon-block height-100 bg-default"></span>
        <a href="app-take-course.html" class="padding-none overlay overlay-full icon-block bg-default">
            <span class="v-center">
                <i class="fa fa-github"></i>
            </span>
        </a>
        
        <a href="app-take-course.html" class="overlay overlay-full overlay-hover overlay-bg-white">
            <span class="v-center">
                <span class="btn btn-circle btn-white btn-lg"><i class="fa fa-graduation-cap"></i></span>
            </span>
        </a>
        
    </div>
    
    <div class="panel-body">
        <h4 class="text-headline margin-v-0-10"><a href="app-take-course.html">Github Webhooks for Beginners</a></h4>
        
    </div>
    <hr class="margin-none" />
    <div class="panel-body">
        
        
        
        
        
        <a class="btn btn-white btn-flat paper-shadow relative" data-z="0" data-hover-z="1" data-animated href="app-take-course.html">Go to course</a>
        
    </div>
    
</div>
    </div>
    <div class="item col-xs-12 col-sm-6 col-lg-4">
        <div class="panel panel-default paper-shadow" data-z="0.5">
    
    <div class="panel-heading">
        <div class="media media-clearfix-xs-min v-middle">
            <div class="media-body text-caption text-light">
                Lessons 1 of 16
            </div>
            <div class="media-right">
                <div class="progress progress-mini width-100 margin-none">
                    <div class="progress-bar progress-bar-blue-300" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width:75%;">
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="cover overlay cover-image-full hover">
        <span class="img icon-block height-100 bg-primary"></span>
        <a href="app-take-course.html" class="padding-none overlay overlay-full icon-block bg-primary">
            <span class="v-center">
                <i class="fa fa-css3"></i>
            </span>
        </a>
        
        <a href="app-take-course.html" class="overlay overlay-full overlay-hover overlay-bg-white">
            <span class="v-center">
                <span class="btn btn-circle btn-primary btn-lg"><i class="fa fa-graduation-cap"></i></span>
            </span>
        </a>
        
    </div>
    
    <div class="panel-body">
        <h4 class="text-headline margin-v-0-10"><a href="app-take-course.html">Awesome CSS with LESS Processing</a></h4>
        
    </div>
    <hr class="margin-none" />
    <div class="panel-body">
        
        
        
        
        
        <a class="btn btn-white btn-flat paper-shadow relative" data-z="0" data-hover-z="1" data-animated href="app-take-course.html">Go to course</a>
        
    </div>
    
</div>
    </div>
    <div class="item col-xs-12 col-sm-6 col-lg-4">
        <div class="panel panel-default paper-shadow" data-z="0.5">
    
    <div class="panel-heading">
        <div class="media media-clearfix-xs-min v-middle">
            <div class="media-body text-caption text-light">
                Lessons 5 of 15
            </div>
            <div class="media-right">
                <div class="progress progress-mini width-100 margin-none">
                    <div class="progress-bar progress-bar-red-300" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width:75%;">
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="cover overlay cover-image-full hover">
        <span class="img icon-block height-100 bg-lightred"></span>
        <a href="app-take-course.html" class="padding-none overlay overlay-full icon-block bg-lightred">
            <span class="v-center">
                <i class="fa fa-windows"></i>
            </span>
        </a>
        
        <a href="app-take-course.html" class="overlay overlay-full overlay-hover overlay-bg-white">
            <span class="v-center">
                <span class="btn btn-circle btn-red-500 btn-lg"><i class="fa fa-graduation-cap"></i></span>
            </span>
        </a>
        
    </div>
    
    <div class="panel-body">
        <h4 class="text-headline margin-v-0-10"><a href="app-take-course.html">Vagrant Portable Environments</a></h4>
        
    </div>
    <hr class="margin-none" />
    <div class="panel-body">
        
        
        
        
        
        <a class="btn btn-white btn-flat paper-shadow relative" data-z="0" data-hover-z="1" data-animated href="app-take-course.html">Go to course</a>
        
    </div>
    
</div>
    </div>
    <div class="item col-xs-12 col-sm-6 col-lg-4">
        <div class="panel panel-default paper-shadow" data-z="0.5">
    
    <div class="panel-heading">
        <div class="media media-clearfix-xs-min v-middle">
            <div class="media-body text-caption text-light">
                Lessons 4 of 8
            </div>
            <div class="media-right">
                <div class="progress progress-mini width-100 margin-none">
                    <div class="progress-bar progress-bar-orange-300" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width:75%;">
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="cover overlay cover-image-full hover">
        <span class="img icon-block height-100 bg-brown"></span>
        <a href="app-take-course.html" class="padding-none overlay overlay-full icon-block bg-brown">
            <span class="v-center">
                <i class="fa fa-wordpress"></i>
            </span>
        </a>
        
        <a href="app-take-course.html" class="overlay overlay-full overlay-hover overlay-bg-white">
            <span class="v-center">
                <span class="btn btn-circle btn-orange-500 btn-lg"><i class="fa fa-graduation-cap"></i></span>
            </span>
        </a>
        
    </div>
    
    <div class="panel-body">
        <h4 class="text-headline margin-v-0-10"><a href="app-take-course.html">WordPress Theme Development</a></h4>
        
    </div>
    <hr class="margin-none" />
    <div class="panel-body">
        
        
        
        
        
        <a class="btn btn-white btn-flat paper-shadow relative" data-z="0" data-hover-z="1" data-animated href="app-take-course.html">Go to course</a>
        
    </div>
    
</div>
    </div>
    <div class="item col-xs-12 col-sm-6 col-lg-4">
        <div class="panel panel-default paper-shadow" data-z="0.5">
    
    <div class="panel-heading">
        <div class="media media-clearfix-xs-min v-middle">
            <div class="media-body text-caption text-light">
                Lessons 2 of 16
            </div>
            <div class="media-right">
                <div class="progress progress-mini width-100 margin-none">
                    <div class="progress-bar progress-bar-deep-purple-300" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width:75%;">
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="cover overlay cover-image-full hover">
        <span class="img icon-block height-100 bg-purple"></span>
        <a href="app-take-course.html" class="padding-none overlay overlay-full icon-block bg-purple">
            <span class="v-center">
                <i class="fa fa-jsfiddle"></i>
            </span>
        </a>
        
        <a href="app-take-course.html" class="overlay overlay-full overlay-hover overlay-bg-white">
            <span class="v-center">
                <span class="btn btn-circle btn-purple-500 btn-lg"><i class="fa fa-graduation-cap"></i></span>
            </span>
        </a>
        
    </div>
    
    <div class="panel-body">
        <h4 class="text-headline margin-v-0-10"><a href="app-take-course.html">Browserify: Writing Modular JavaScript</a></h4>
        
    </div>
    <hr class="margin-none" />
    <div class="panel-body">
        
        
        
        
        
        <a class="btn btn-white btn-flat paper-shadow relative" data-z="0" data-hover-z="1" data-animated href="app-take-course.html">Go to course</a>
        
    </div>
    
</div>
    </div>
    <div class="item col-xs-12 col-sm-6 col-lg-4">
        <div class="panel panel-default paper-shadow" data-z="0.5">
    
    <div class="panel-heading">
        <div class="media media-clearfix-xs-min v-middle">
            <div class="media-body text-caption text-light">
                Lessons 4 of 8
            </div>
            <div class="media-right">
                <div class="progress progress-mini width-100 margin-none">
                    <div class="progress-bar progress-bar-pink-300" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width:75%;">
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="cover overlay cover-image-full hover">
        <span class="img icon-block height-100 bg-pink-400 text-white"></span>
        <a href="app-take-course.html" class="padding-none overlay overlay-full icon-block bg-pink-400 text-white">
            <span class="v-center">
                <i class="fa fa-cc-visa"></i>
            </span>
        </a>
        
        <a href="app-take-course.html" class="overlay overlay-full overlay-hover overlay-bg-white">
            <span class="v-center">
                <span class="btn btn-circle btn-pink-500 btn-lg"><i class="fa fa-graduation-cap"></i></span>
            </span>
        </a>
        
    </div>
    
    <div class="panel-body">
        <h4 class="text-headline margin-v-0-10"><a href="app-take-course.html">Online Payments with Stripe</a></h4>
        
    </div>
    <hr class="margin-none" />
    <div class="panel-body">
        
        
        
        
        
        <a class="btn btn-white btn-flat paper-shadow relative" data-z="0" data-hover-z="1" data-animated href="app-take-course.html">Go to course</a>
        
    </div>
    
</div>
    </div>
</div>

<ul class="pagination margin-top-none">
    <li class="disabled"><a href="#">&laquo;</a></li>
    <li class="active"><a href="#">1</a></li>
    <li><a href="#">2</a></li>
    <li><a href="#">3</a></li>
    <li><a href="#">&raquo;</a></li>
</ul>

                </div>

            </div><!-- /st-content-inner -->

        </div><!-- /st-content -->

    </div><!-- /st-pusher -->

    <!-- Footer -->
<footer class="footer">
    
    <?php include_once("includes/footer.php"); ?>
    
</footer>
<!-- // Footer -->

</div><!-- /st-container -->


 <?php include_once("includes/js.php"); ?>
        

</body>
</html>