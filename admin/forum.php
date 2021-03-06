<?php 
	include_once("api/includes/DB_handler.php"); 
	include_once("api/includes/Config.php"); 
	include_once("../includes/funcs.php"); 
	
	$admin = true;
	$show_bootstrap_dialog = true;
	
?>

<!DOCTYPE html>
<html class="st-layout ls-top-navbar-large ls-bottom-footer show-sidebar sidebar-l3" lang="en">

<head>
    
	<?php include_once("includes/head_scripts.php"); ?>
                
    <title>Forum ::
    <?=$page_titles?>
    </title>

</head>

<body>

    <!-- Wrapper required for sidebar transitions -->
    <div class="st-container">

            
        <?php include_once("includes/nav.php"); ?>
            

        <?php include_once("includes/left_sidebar1.php"); ?>
        
        
        <?php //include_once("includes/right_sidebar2.php"); ?>
            

        
        
        
        
        
       	<!-- sidebar effects OUTSIDE of st-pusher: -->
        <!-- st-effect-1, st-effect-2, st-effect-4, st-effect-5, st-effect-9, st-effect-10, st-effect-11, st-effect-12, st-effect-13 -->
        
        <!-- content push wrapper -->
        <div class="st-pusher" id="content">
        
            <!-- sidebar effects INSIDE of st-pusher: -->
            <!-- st-effect-3, st-effect-6, st-effect-7, st-effect-8, st-effect-14 -->
        
            <!-- this is the wrapper for the content -->
            <div class="st-content">
        
                <!-- extra div for emulating position:fixed of the menu -->
                <div class="st-content-inner padding-top-none">
        
                    <div class="container-fluid">
        
                        <div class="page-section">
                            <div class="media v-middle">
                                <div class="media-body">
                                    <h1 class="text-display-1 margin-none">Forums</h1>
                                    <p class="text-subhead text-light">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolores, illo.</p>
                                </div>
                                <div class="media-right">
                                    <a href="#" class="btn btn-white paper-shadow relative" data-z="0.5" data-hover-z="1" data-animated><i class="fa fa-fw fa-plus"></i> New Topic</a>
                                </div>
                            </div>
                        </div>
        
                        <div class="panel panel-default paper-shadow" data-z="0.5">
                            <ul class="list-group">
        
                                <li class="list-group-item media v-middle">
                                    <div class="media-left">
                                        <div class="icon-block half img-circle bg-grey-300">
                                            <i class="fa fa-file-text text-white"></i>
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        <h4 class="text-subhead margin-none">
                    <a href="app-course-forum-thread.html" class="link-text-color">Am I learning the right way?</a>
                </h4>
                                        <div class="text-light text-caption">
                                            posted by
                                            <a href="#"><img src="images/people/110/guy-6.jpg" alt="person" class="img-circle width-20" /> Adrian Demian</a> &nbsp; | <i class="fa fa-clock-o fa-fw"></i> 5 mins
                                        </div>
                                    </div>
                                    <div class="media-right">
                                        <a href="app-course-forum-thread.html" class="btn btn-white text-light"><i class="fa fa-comments fa-fw"></i> 12</a>
                                    </div>
                                </li>
        
                                <li class="list-group-item media v-middle">
                                    <div class="media-left">
                                        <div class="icon-block half img-circle bg-grey-300">
                                            <i class="fa fa-file-text text-white"></i>
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        <h4 class="text-subhead margin-none">
                    <a href="app-course-forum-thread.html" class="link-text-color">Can someone help me? I need a design advice</a>
                </h4>
                                        <div class="text-light text-caption">
                                            posted by
                                            <a href="#"><img src="images/people/110/woman-6.jpg" alt="person" class="img-circle width-20" /> Jennifer Hudson</a> &nbsp; | <i class="fa fa-clock-o fa-fw"></i> 5 mins
                                        </div>
                                    </div>
                                    <div class="media-right">
                                        <a href="app-course-forum-thread.html" class="btn btn-white text-light"><i class="fa fa-comments fa-fw"></i> 4</a>
                                    </div>
                                </li>
        
                                <li class="list-group-item media v-middle">
                                    <div class="media-left">
                                        <div class="icon-block half img-circle bg-grey-300">
                                            <i class="fa fa-file-text text-white"></i>
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        <h4 class="text-subhead margin-none">
                    <a href="app-course-forum-thread.html" class="link-text-color">I think this is the right way?</a>
                </h4>
                                        <div class="text-light text-caption">
                                            posted by
                                            <a href="#"><img src="images/people/110/woman-4.jpg" alt="person" class="img-circle width-20" /> Michelle Gustav</a> &nbsp; | <i class="fa fa-clock-o fa-fw"></i> 5 mins
                                        </div>
                                    </div>
                                    <div class="media-right">
                                        <a href="app-course-forum-thread.html" class="btn btn-white text-light"><i class="fa fa-comments fa-fw"></i> 8</a>
                                    </div>
                                </li>
        
                                <li class="list-group-item media v-middle">
                                    <div class="media-left">
                                        <div class="icon-block half img-circle bg-grey-300">
                                            <i class="fa fa-file-text text-white"></i>
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        <h4 class="text-subhead margin-none">
                    <a href="app-course-forum-thread.html" class="link-text-color">Getting around AngularJS</a>
                </h4>
                                        <div class="text-light text-caption">
                                            posted by
                                            <a href="#"><img src="images/people/110/guy-4.jpg" alt="person" class="img-circle width-20" /> Jonny Francine</a> &nbsp; | <i class="fa fa-clock-o fa-fw"></i> 5 mins
                                        </div>
                                    </div>
                                    <div class="media-right">
                                        <a href="app-course-forum-thread.html" class="btn btn-white text-light"><i class="fa fa-comments fa-fw"></i> 15</a>
                                    </div>
                                </li>
        
                                <li class="list-group-item media v-middle">
                                    <div class="media-left">
                                        <div class="icon-block half img-circle bg-grey-300">
                                            <i class="fa fa-file-text text-white"></i>
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        <h4 class="text-subhead margin-none">
                    <a href="app-course-forum-thread.html" class="link-text-color">I think this is the right way?</a>
                </h4>
                                        <div class="text-light text-caption">
                                            posted by
                                            <a href="#"><img src="images/people/110/woman-4.jpg" alt="person" class="img-circle width-20" /> Michelle Gustav</a> &nbsp; | <i class="fa fa-clock-o fa-fw"></i> 5 mins
                                        </div>
                                    </div>
                                    <div class="media-right">
                                        <a href="app-course-forum-thread.html" class="btn btn-white text-light"><i class="fa fa-comments fa-fw"></i> 17</a>
                                    </div>
                                </li>
        
                                <li class="list-group-item media v-middle">
                                    <div class="media-left">
                                        <div class="icon-block half img-circle bg-grey-300">
                                            <i class="fa fa-file-text text-white"></i>
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        <h4 class="text-subhead margin-none">
                    <a href="app-course-forum-thread.html" class="link-text-color">Responsive Bootstrap Question</a>
                </h4>
                                        <div class="text-light text-caption">
                                            posted by
                                            <a href="#"><img src="images/people/110/woman-5.jpg" alt="person" class="img-circle width-20" /> Mary Deb</a> &nbsp; | <i class="fa fa-clock-o fa-fw"></i> 5 mins
                                        </div>
                                    </div>
                                    <div class="media-right">
                                        <a href="app-course-forum-thread.html" class="btn btn-white text-light"><i class="fa fa-comments fa-fw"></i> 9</a>
                                    </div>
                                </li>
        
                                <li class="list-group-item media v-middle">
                                    <div class="media-left">
                                        <div class="icon-block half img-circle bg-grey-300">
                                            <i class="fa fa-file-text text-white"></i>
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        <h4 class="text-subhead margin-none">
                    <a href="app-course-forum-thread.html" class="link-text-color">Am I learning the right way?</a>
                </h4>
                                        <div class="text-light text-caption">
                                            posted by
                                            <a href="#"><img src="images/people/110/guy-6.jpg" alt="person" class="img-circle width-20" /> Adrian Demian</a> &nbsp; | <i class="fa fa-clock-o fa-fw"></i> 5 mins
                                        </div>
                                    </div>
                                    <div class="media-right">
                                        <a href="app-course-forum-thread.html" class="btn btn-white text-light"><i class="fa fa-comments fa-fw"></i> 6</a>
                                    </div>
                                </li>
        
                                <li class="list-group-item media v-middle">
                                    <div class="media-left">
                                        <div class="icon-block half img-circle bg-grey-300">
                                            <i class="fa fa-file-text text-white"></i>
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        <h4 class="text-subhead margin-none">
                    <a href="app-course-forum-thread.html" class="link-text-color">Responsive Bootstrap Question</a>
                </h4>
                                        <div class="text-light text-caption">
                                            posted by
                                            <a href="#"><img src="images/people/110/woman-5.jpg" alt="person" class="img-circle width-20" /> Mary Deb</a> &nbsp; | <i class="fa fa-clock-o fa-fw"></i> 5 mins
                                        </div>
                                    </div>
                                    <div class="media-right">
                                        <a href="app-course-forum-thread.html" class="btn btn-white text-light"><i class="fa fa-comments fa-fw"></i> 17</a>
                                    </div>
                                </li>
        
                                <li class="list-group-item media v-middle">
                                    <div class="media-left">
                                        <div class="icon-block half img-circle bg-grey-300">
                                            <i class="fa fa-file-text text-white"></i>
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        <h4 class="text-subhead margin-none">
                    <a href="app-course-forum-thread.html" class="link-text-color">Am I learning the right way?</a>
                </h4>
                                        <div class="text-light text-caption">
                                            posted by
                                            <a href="#"><img src="images/people/110/guy-6.jpg" alt="person" class="img-circle width-20" /> Adrian Demian</a> &nbsp; | <i class="fa fa-clock-o fa-fw"></i> 5 mins
                                        </div>
                                    </div>
                                    <div class="media-right">
                                        <a href="app-course-forum-thread.html" class="btn btn-white text-light"><i class="fa fa-comments fa-fw"></i> 7</a>
                                    </div>
                                </li>
        
                                <li class="list-group-item media v-middle">
                                    <div class="media-left">
                                        <div class="icon-block half img-circle bg-grey-300">
                                            <i class="fa fa-file-text text-white"></i>
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        <h4 class="text-subhead margin-none">
                    <a href="app-course-forum-thread.html" class="link-text-color">Can someone help me? I need a design advice</a>
                </h4>
                                        <div class="text-light text-caption">
                                            posted by
                                            <a href="#"><img src="images/people/110/woman-6.jpg" alt="person" class="img-circle width-20" /> Jennifer Hudson</a> &nbsp; | <i class="fa fa-clock-o fa-fw"></i> 5 mins
                                        </div>
                                    </div>
                                    <div class="media-right">
                                        <a href="app-course-forum-thread.html" class="btn btn-white text-light"><i class="fa fa-comments fa-fw"></i> 4</a>
                                    </div>
                                </li>
        
                            </ul>
                        </div>
        
                        <ul class="pagination margin-top-none">
                            <li class="disabled"><a href="#">&laquo;</a></li>
                            <li class="active"><a href="#">1</a></li>
                            <li><a href="#">2</a></li>
                            <li><a href="#">3</a></li>
                            <li><a href="#">&raquo;</a></li>
                        </ul>
        
                        <br/>
        
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