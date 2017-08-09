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
                <div class="st-content-inner padding-top-none">
        
                    <div class="container-fluid">
        
                        <div class="page-section">
                            <div class="media media-grid v-middle">
                                <div class="media-left">
                                    <span class="icon-block half bg-blue-300 text-white">2</span>
                                </div>
                                <div class="media-body">
                                    <h1 class="text-display-1 margin-none">The MVC architectural pattern</h1>
                                </div>
                            </div>
                            <br/>
                            <p class="text-body-2">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum dicta eius enim inventore minus optio ratione veritatis. Beatae deserunt illum ipsam magni minima mollitia officiis quia tempora! Aliquid autem beatae, dignissimos exercitationem illum, incidunt itaque libero, minima molestiae necessitatibus perferendis quae quas quidem recusandae sit! Esse maxime porro provident quasi?</p>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Architecto assumenda aut debitis, ducimus, ea eaque earum eius enim eos explicabo facilis harum impedit natus nemo, nobis obcaecati omnis perspiciatis praesentium quaerat quas quod reprehenderit sapiente temporibus vel voluptatem voluptates voluptatibus?</p>
                        </div>
        
                        <h5 class="text-subhead-2 text-light">Curriculum</h5>
                        <div class="panel panel-default curriculum open paper-shadow" data-z="0.5">
                            <div class="panel-heading panel-heading-gray" data-toggle="collapse" data-target="#curriculum-1">
                                <div class="media">
                                    <div class="media-left">
                                        <span class="icon-block img-circle bg-indigo-300 half text-white"><i class="fa fa-graduation-cap"></i></span>
                                    </div>
                                    <div class="media-body">
                                        <h4 class="text-headline">Chapter 1</h4>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Asperiores cumque minima nemo repudiandae rerum! Aspernatur at, autem expedita id illum laudantium molestias officiis quaerat, rem sapiente sint totam velit. Enim.</p>
                                    </div>
                                </div>
                                <span class="collapse-status collapse-open">Open</span>
                                <span class="collapse-status collapse-close">Close</span>
                            </div>
                            <div class="list-group collapse in" id="curriculum-1">
                                <div class="list-group-item media" data-target="app-take-course.html">
                                    <div class="media-left">
                                        <div class="text-crt">1.</div>
                                    </div>
                                    <div class="media-body">
                                        <i class="fa fa-fw fa-circle text-green-300"></i> Installation
                                    </div>
                                    <div class="media-right">
                                        <div class="width-100 text-right text-caption">2:03 min</div>
                                    </div>
                                </div>
                                <div class="list-group-item media active" data-target="app-take-course.html">
                                    <div class="media-left">
                                        <div class="text-crt">2.</div>
                                    </div>
                                    <div class="media-body">
                                        <i class="fa fa-fw fa-circle text-blue-300"></i> The MVC architectural pattern
                                    </div>
                                    <div class="media-right">
                                        <div class="width-100 text-right text-caption">25:01 min</div>
                                    </div>
                                </div>
                                <div class="list-group-item media" data-target="app-take-course.html">
                                    <div class="media-left">
                                        <div class="text-crt">3.</div>
                                    </div>
                                    <div class="media-body">
                                        <i class="fa fa-fw fa-circle text-grey-200"></i> Database Models
                                    </div>
                                    <div class="media-right">
                                        <div class="width-100 text-right text-caption">12:10 min</div>
                                    </div>
                                </div>
                                <div class="list-group-item media" data-target="app-take-course.html">
                                    <div class="media-left">
                                        <div class="text-crt">4.</div>
                                    </div>
                                    <div class="media-body">
                                        <i class="fa fa-fw fa-circle text-grey-200"></i> Database Access
                                    </div>
                                    <div class="media-right">
                                        <div class="width-100 text-right text-caption">1:25 min</div>
                                    </div>
                                </div>
                                <div class="list-group-item media" data-target="app-take-course.html">
                                    <div class="media-left">
                                        <div class="text-crt">5.</div>
                                    </div>
                                    <div class="media-body">
                                        <i class="fa fa-fw fa-circle text-grey-200"></i> Eloquent Basics
                                    </div>
                                    <div class="media-right">
                                        <div class="width-100 text-right text-caption">22:30 min</div>
                                    </div>
                                </div>
                                <div class="list-group-item media" data-target="app-take-quiz.html">
                                    <div class="media-left">
                                        <div class="text-crt">6.</div>
                                    </div>
                                    <div class="media-body">
                                        <i class="fa fa-fw fa-circle text-grey-200"></i> Take Quiz
                                    </div>
                                    <div class="media-right">
                                        <div class="width-100 text-right text-caption">10:00 min</div>
                                    </div>
                                </div>
                            </div>
                        </div>
        
                        <div class="panel panel-default curriculum paper-shadow" data-z="0.5">
                            <div class="panel-heading panel-heading-gray" data-toggle="collapse" data-target="#curriculum-2">
                                <div class="media">
                                    <div class="media-left">
                                        <span class="icon-block half img-circle bg-orange-300 text-white"><i class="fa fa-graduation-cap"></i></span>
                                    </div>
                                    <div class="media-body">
                                        <h4 class="text-headline">Chapter 2</h4>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Asperiores cumque minima nemo repudiandae rerum! Aspernatur at, autem expedita id illum laudantium molestias officiis quaerat, rem sapiente sint totam velit. Enim.</p>
                                    </div>
                                </div>
                                <span class="collapse-status collapse-open">Open</span>
                                <span class="collapse-status collapse-close">Close</span>
                            </div>
                            <div class="list-group collapse" id="curriculum-2">
                                <div class="list-group-item media" data-target="app-take-course.html">
                                    <div class="media-left">
                                        <div class="text-crt">1.</div>
                                    </div>
                                    <div class="media-body">
                                        <i class="fa fa-fw fa-circle text-grey-200"></i> Installation
                                    </div>
                                    <div class="media-right">
                                        <div class="width-100 text-right text-caption">2:03 min</div>
                                    </div>
                                </div>
                                <div class="list-group-item media" data-target="app-take-course.html">
                                    <div class="media-left">
                                        <div class="text-crt">2.</div>
                                    </div>
                                    <div class="media-body">
                                        <i class="fa fa-fw fa-circle text-grey-200"></i> The MVC architectural pattern
                                    </div>
                                    <div class="media-right">
                                        <div class="width-100 text-right text-caption">25:01 min</div>
                                    </div>
                                </div>
                                <div class="list-group-item media" data-target="app-take-course.html">
                                    <div class="media-left">
                                        <div class="text-crt">3.</div>
                                    </div>
                                    <div class="media-body">
                                        <i class="fa fa-fw fa-circle text-grey-200"></i> Database Models
                                    </div>
                                    <div class="media-right">
                                        <div class="width-100 text-right text-caption">12:10 min</div>
                                    </div>
                                </div>
                                <div class="list-group-item media" data-target="app-take-course.html">
                                    <div class="media-left">
                                        <div class="text-crt">4.</div>
                                    </div>
                                    <div class="media-body">
                                        <i class="fa fa-fw fa-circle text-grey-200"></i> Database Access
                                    </div>
                                    <div class="media-right">
                                        <div class="width-100 text-right text-caption">1:25 min</div>
                                    </div>
                                </div>
                                <div class="list-group-item media" data-target="app-take-course.html">
                                    <div class="media-left">
                                        <div class="text-crt">5.</div>
                                    </div>
                                    <div class="media-body">
                                        <i class="fa fa-fw fa-circle text-grey-200"></i> Eloquent Basics
                                    </div>
                                    <div class="media-right">
                                        <div class="width-100 text-right text-caption">22:30 min</div>
                                    </div>
                                </div>
                                <div class="list-group-item media" data-target="app-take-quiz.html">
                                    <div class="media-left">
                                        <div class="text-crt">6.</div>
                                    </div>
                                    <div class="media-body">
                                        <i class="fa fa-fw fa-circle text-grey-200"></i> Take Quiz
                                    </div>
                                    <div class="media-right">
                                        <div class="width-100 text-right text-caption">10:00 min</div>
                                    </div>
                                </div>
                            </div>
                        </div>
        
                        <div class="panel panel-default curriculum paper-shadow" data-z="0.5">
                            <div class="panel-heading panel-heading-gray" data-toggle="collapse" data-target="#curriculum-3">
                                <div class="media">
                                    <div class="media-left">
                                        <span class="icon-block half img-circle bg-green-300 text-white"><i class="fa fa-graduation-cap"></i></span>
                                    </div>
                                    <div class="media-body">
                                        <h4 class="text-headline">Chapter 3</h4>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Asperiores cumque minima nemo repudiandae rerum! Aspernatur at, autem expedita id illum laudantium molestias officiis quaerat, rem sapiente sint totam velit. Enim.</p>
                                    </div>
                                </div>
                                <span class="collapse-status collapse-open">Open</span>
                                <span class="collapse-status collapse-close">Close</span>
                            </div>
                            <div class="list-group collapse" id="curriculum-3">
                                <div class="list-group-item media" data-target="app-take-course.html">
                                    <div class="media-left">
                                        <div class="text-crt">1.</div>
                                    </div>
                                    <div class="media-body">
                                        <i class="fa fa-fw fa-circle text-grey-200"></i> Installation
                                    </div>
                                    <div class="media-right">
                                        <div class="width-100 text-right text-caption">2:03 min</div>
                                    </div>
                                </div>
                                <div class="list-group-item media" data-target="app-take-course.html">
                                    <div class="media-left">
                                        <div class="text-crt">2.</div>
                                    </div>
                                    <div class="media-body">
                                        <i class="fa fa-fw fa-circle text-grey-200"></i> The MVC architectural pattern
                                    </div>
                                    <div class="media-right">
                                        <div class="width-100 text-right text-caption">25:01 min</div>
                                    </div>
                                </div>
                                <div class="list-group-item media" data-target="app-take-course.html">
                                    <div class="media-left">
                                        <div class="text-crt">3.</div>
                                    </div>
                                    <div class="media-body">
                                        <i class="fa fa-fw fa-circle text-grey-200"></i> Database Models
                                    </div>
                                    <div class="media-right">
                                        <div class="width-100 text-right text-caption">12:10 min</div>
                                    </div>
                                </div>
                                <div class="list-group-item media" data-target="app-take-course.html">
                                    <div class="media-left">
                                        <div class="text-crt">4.</div>
                                    </div>
                                    <div class="media-body">
                                        <i class="fa fa-fw fa-circle text-grey-200"></i> Database Access
                                    </div>
                                    <div class="media-right">
                                        <div class="width-100 text-right text-caption">1:25 min</div>
                                    </div>
                                </div>
                                <div class="list-group-item media" data-target="app-take-course.html">
                                    <div class="media-left">
                                        <div class="text-crt">5.</div>
                                    </div>
                                    <div class="media-body">
                                        <i class="fa fa-fw fa-circle text-grey-200"></i> Eloquent Basics
                                    </div>
                                    <div class="media-right">
                                        <div class="width-100 text-right text-caption">22:30 min</div>
                                    </div>
                                </div>
                                <div class="list-group-item media" data-target="app-take-quiz.html">
                                    <div class="media-left">
                                        <div class="text-crt">6.</div>
                                    </div>
                                    <div class="media-body">
                                        <i class="fa fa-fw fa-circle text-grey-200"></i> Take Quiz
                                    </div>
                                    <div class="media-right">
                                        <div class="width-100 text-right text-caption">10:00 min</div>
                                    </div>
                                </div>
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

</div><!-- /st-container -->


 <?php include_once("includes/js.php"); ?>
        

</body>
</html>