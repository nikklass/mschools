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
                
    <title>Take Quiz ::
    <?=$page_titles?>
    </title>

</head>

<body>

    <!-- Wrapper required for sidebar transitions -->
    <div class="st-container">

            
        <?php include_once("includes/nav.php"); ?>
      

        <!-- Sidebar component with st-effect-1 (set on the toggle button within the navbar) -->
        
            
        <?php include_once("includes/left_sidebar1.php"); ?>
        
        
        <?php include_once("includes/right_sidebar.php"); ?>
        
        
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
        
                    <div class="page-section half bg-white">
                        <div class="container-fluid">
                            <div class="section-toolbar">
                                <div class="cell">
                                    <div class="media width-120 v-middle margin-none">
                                        <div class="media-left">
                                            <div class="icon-block bg-grey-200 s30"><i class="fa fa-question"></i></div>
                                        </div>
                                        <div class="media-body">
                                            <p class="text-body-2 text-light margin-none">Questions</p>
                                            <p class="text-title text-primary margin-none">25</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="cell">
                                    <div class="media width-120 v-middle margin-none">
                                        <div class="media-left">
                                            <div class="icon-block bg-grey-200 s30"><i class="fa fa-diamond"></i></div>
                                        </div>
                                        <div class="media-body">
                                            <p class="text-body-2 text-light margin-none">Score</p>
                                            <p class="text-title text-success margin-none">800 pt</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                  </div>
        
                    <div class="page-section equal">
                        <div class="container-fluid">
                            <div class="text-subhead-2 text-light">Question 2 of 25</div>
                            <div class="panel panel-default paper-shadow" data-z="0.5">
                                <div class="panel-heading">
                                    <h4 class="text-headline">Step by Step</h4>
                                </div>
                                <div class="panel-body">
                                    <p class="text-body-2">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Asperiores cumque minima nemo repudiandae rerum! Aspernatur at, autem expedita id illum laudantium molestias officiis quaerat, rem sapiente sint totam velit. Enim.</p>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Beatae consectetur consequuntur dignissimos dolorem dolores eaque error eum excepturi fugit in iste laboriosam, libero maiores neque officiis omnis, pariatur possimus, quidem quo quod recusandae rem repudiandae rerum saepe sequi suscipit tempora? A aperiam autem deleniti distinctio ea expedita facere fugiat, fugit iure labore laboriosam laudantium nam neque nihil numquam, obcaecati, quam quibusdam ratione recusandae rem sapiente sed veritatis voluptas? Accusantium et laborum minima perferendis praesentium vel. Aliquid architecto, aspernatur autem blanditiis consequuntur debitis, ducimus eaque eos in iste nisi pariatur quidem rem sapiente tempora, tenetur vitae. Dolorem quae quo recusandae similique?</p>
                                </div>
                            </div>
        
                            <div class="text-subhead-2 text-light">Your Answer</div>
                            <div class="panel panel-default paper-shadow" data-z="0.5">
                                <div class="panel-body">
                                    <div class="checkbox checkbox-primary">
                                        <input id="checkbox1" type="checkbox">
                                        <label for="checkbox1">Aspernatur corporis deserunt dolorum eos nulla</label>
                                    </div>
                                    <div class="checkbox checkbox-primary">
                                        <input id="checkbox2" type="checkbox" checked>
                                        <label for="checkbox2">Accusantium aperiam aut cumque deleniti dolores</label>
                                    </div>
                                    <div class="checkbox checkbox-primary">
                                        <input id="checkbox5" type="checkbox">
                                        <label for="checkbox5">Culpa doloribus enim explicabo ipsa iste porro</label>
                                    </div>
                                </div>
                                <div class="panel-footer">
                                    <div class="text-right">
                                        <button class="btn btn-success"><i class="fa fa-save fa-fw"></i> Save Answer</button>
                                        <button class="btn btn-primary"><i class="fa fa-chevron-right fa-fw"></i> Next Question</button>
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

    </div>
    <!-- /st-container -->

    
    <?php include_once("includes/js.php"); ?>
    

</body>

</html>