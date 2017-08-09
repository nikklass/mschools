<?php 
	include_once("api/includes/DB_handler.php"); 
	include_once("api/includes/Config.php"); 
	include_once("includes/funcs.php"); 
	
	$admin = true;
	$show_bootstrap_dialog = true;
	
?>

<!DOCTYPE html>
<html class="st-layout ls-top-navbar-large ls-bottom-footer show-sidebar sidebar-l3" lang="en">

<head>
    
	<?php include_once("includes/head_scripts.php"); ?>
                
    <title>PendoSchools :: <?=$page_titles?></title>

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
                            <h1 class="text-display-1">Create New Course</h1>
                        </div>
        
                        <!-- Tabbable Widget -->
                        <div class="tabbable paper-shadow relative" data-z="0.5">
        
                            <!-- Tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="app-instructor-course-edit-course.html"><i class="fa fa-fw fa-lock"></i> <span class="hidden-sm hidden-xs">Course</span></a></li>
                                <li><a href="app-instructor-course-edit-meta.html"><i class="fa fa-fw fa-credit-card"></i> <span class="hidden-sm hidden-xs">Meta</span></a></li>
                                <li><a href="app-instructor-course-edit-lessons.html"><i class="fa fa-fw fa-credit-card"></i> <span class="hidden-sm hidden-xs">Lessons</span></a></li>
                            </ul>
                            <!-- // END Tabs -->
        
                            <!-- Panes -->
                            <div class="tab-content">
        
                                <div id="course" class="tab-pane active">
                                    <form action="">
                                        <div class="form-group form-control-material">
                                            <input type="text" name="title" id="title" placeholder="Course Title" class="form-control used" value="Basics of HTML" />
                                            <label for="title">Title</label>
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea name="description" id="description" cols="30" rows="10" class="summernote">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Beatae consectetur dignissimos itaque nesciunt nostrum, provident saepe similique. Delectus dicta distinctio quibusdam velit veniam? Aperiam cum dignissimos doloremque officiis quisquam velit!</textarea>
                                        </div>
                                    </form>
                                    <div class="text-right">
                                        <a href="#" class="btn btn-primary">Save</a>
                                    </div>
                                </div>
        
                            </div>
                            <!-- // END Panes -->
        
                        </div>
                        <!-- // END Tabbable Widget -->
        
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