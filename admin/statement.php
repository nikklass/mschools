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
                            <h1 class="text-display-1">Statement</h1>
                        </div>

                        <div class="panel panel-default paper-shadow" data-z="0.5">
                            <div class="panel-heading">
                                <div class="max-width-300 form-group daterangepicker-report">
                                    <div class="form-control">
                                        <i class="fa fa-calendar fa-fw"></i>
                                        <span>December 10, 2014 - January 9, 2015</span>
                                        <b class="caret"></b>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table text-subhead v-middle">
                                    <thead>
                                        <tr>
                                            <th class="width-100">Date</th>
                                            <th>Item</th>
                                            <th class="width-80 text-center">Reference</th>
                                            <th class="width-50 text-center">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="label label-grey-200">12 Jan 2015</div>
                                            </td>
                                            <td>Adrian Demian</td>
                                            <td class="text-center"><a href="#">#7576</a></td>
                                            <td class="text-center">&dollar;7</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="label label-grey-200">12 Jan 2015</div>
                                            </td>
                                            <td>Adrian Demian</td>
                                            <td class="text-center"><a href="#">#11875</a></td>
                                            <td class="width-50 text-center">&dollar;96</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="label label-grey-200">12 Jan 2015</div>
                                            </td>
                                            <td>Adrian Demian</td>
                                            <td class="text-center"><a href="#">#2645</a></td>
                                            <td class="text-center">&dollar;80</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="label label-grey-200">12 Jan 2015</div>
                                            </td>
                                            <td>Adrian Demian</td>
                                            <td class="text-center"><a href="#">#4591</a></td>
                                            <td class="text-center">&dollar;60</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="label label-grey-200">12 Jan 2015</div>
                                            </td>
                                            <td>Adrian Demian</td>
                                            <td class="text-center"><a href="#">#8051</a></td>
                                            <td class="text-center">&dollar;24</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="label label-grey-200">12 Jan 2015</div>
                                            </td>
                                            <td>Adrian Demian</td>
                                            <td class="text-center"><a href="#">#11338</a></td>
                                            <td class="width-50 text-center">&dollar;33</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="label label-grey-200">12 Jan 2015</div>
                                            </td>
                                            <td>Adrian Demian</td>
                                            <td class="text-center"><a href="#">#4077</a></td>
                                            <td class="text-center">&dollar;23</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="label label-grey-200">12 Jan 2015</div>
                                            </td>
                                            <td>Adrian Demian</td>
                                            <td class="text-center"><a href="#">#11831</a></td>
                                            <td class="text-center">&dollar;47</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="panel-footer">
                                <ul class="pagination margin-none">
                                    <li class="disabled"><a href="#">&laquo;</a></li>
                                    <li class="active"><a href="#">1</a></li>
                                    <li><a href="#">2</a></li>
                                    <li><a href="#">3</a></li>
                                    <li><a href="#">&raquo;</a></li>
                                </ul>
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