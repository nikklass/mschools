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
        <div class="st-pusher" id="content" >
    
            <!-- sidebar effects INSIDE of st-pusher: -->
            <!-- st-effect-3, st-effect-6, st-effect-7, st-effect-8, st-effect-14 -->
    
            <!-- this is the wrapper for the content -->
            <div class="st-content">
    
                <!-- extra div for emulating position:fixed of the menu -->
                <div class="st-content-inner padding-none">
    
                    <div class="container-fluid">
                        
                    
                        <div class="page-section">
                            <h1 class="text-display-1">Earnings</h1>
                        </div>
                    
                        <div class="page-section">
                            <div class="panel panel-default paper-shadow" data-z="0.5">
                        <div class="panel-heading">
                    
                            <div class="media v-middle media-clearfix-xs">
                                <div class="media-body">
                                    <div class="max-width-300 form-group daterangepicker-report" id="reportrange">
                                        <div class="form-control overflow-hidden">
                                            <i class="fa fa-calendar fa-fw"></i>
                                            <span>December 10, 2014 - January 9, 2015</span>
                                            <b class="caret"></b>
                                        </div>
                                    </div>
                                </div>
                                <div class="media-right">
                                    <div class="width-300 width-auto-xs">
                                        <div class="row text-center">
                                            <div class="col-sm-6">
                                                <h4 class="margin-none">Gross Revenue</h4>
                                                <p class="text-display-1 text-warning margin-none">102.4k</p>
                                            </div>
                                            <div class="col-sm-6">
                                                <h4 class="margin-none">Net Revenue</h4>
                                                <p class="text-display-1 text-success margin-none">55k</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    
                        </div>
                        <div class="panel-body">
                            <div id="line-holder" data-toggle="flot-chart-earnings" class="flotchart-holder height-300"></div>
                        </div>
                        <div class="panel-footer">
                            <div class="table-responsive">
                                <table class="table table-headerless table-condensed text-subhead v-middle margin-none">
                                    <tbody>
                                    <tr>
                                        <td><a href="#">January 2015</a></td>
                                        <td class="width-100"><i class="fa fa-circle-o fa-fw text-caption text-orange-200"></i> &dollar;99993</td>
                                        <td class="width-100"><i class="fa fa-circle-o fa-fw text-caption text-green-200"></i> &dollar;6613</td>
                                    </tr>
                                    <tr>
                                        <td><a href="#">February 2015</a></td>
                                        <td class="width-100"><i class="fa fa-circle-o fa-fw text-caption text-orange-200"></i> &dollar;7898</td>
                                        <td class="width-100"><i class="fa fa-circle-o fa-fw text-caption text-green-200"></i> &dollar;30947</td>
                                    </tr>
                                    <tr>
                                        <td><a href="#">March 2015</a></td>
                                        <td class="width-100"><i class="fa fa-circle-o fa-fw text-caption text-orange-200"></i> &dollar;22616</td>
                                        <td class="width-100"><i class="fa fa-circle-o fa-fw text-caption text-green-200"></i> &dollar;91219</td>
                                    </tr>
                                    <tr>
                                        <td><a href="#">April 2015</a></td>
                                        <td class="width-100"><i class="fa fa-circle-o fa-fw text-caption text-orange-200"></i> &dollar;80936</td>
                                        <td class="width-100"><i class="fa fa-circle-o fa-fw text-caption text-green-200"></i> &dollar;100670</td>
                                    </tr>
                                    <tr>
                                        <td><a href="#">May 2015</a></td>
                                        <td class="width-100"><i class="fa fa-circle-o fa-fw text-caption text-orange-200"></i> &dollar;100693</td>
                                        <td class="width-100"><i class="fa fa-circle-o fa-fw text-caption text-green-200"></i> &dollar;96548</td>
                                    </tr>
                                    <tr>
                                        <td><a href="#">June 2015</a></td>
                                        <td class="width-100"><i class="fa fa-circle-o fa-fw text-caption text-orange-200"></i> &dollar;67995</td>
                                        <td class="width-100"><i class="fa fa-circle-o fa-fw text-caption text-green-200"></i> &dollar;31647</td>
                                    </tr>
                                    <tr>
                                        <td><a href="#">July 2015</a></td>
                                        <td class="width-100"><i class="fa fa-circle-o fa-fw text-caption text-orange-200"></i> &dollar;37388</td>
                                        <td class="width-100"><i class="fa fa-circle-o fa-fw text-caption text-green-200"></i> &dollar;20786</td>
                                    </tr>
                                    <tr>
                                        <td><a href="#">August 2015</a></td>
                                        <td class="width-100"><i class="fa fa-circle-o fa-fw text-caption text-orange-200"></i> &dollar;62035</td>
                                        <td class="width-100"><i class="fa fa-circle-o fa-fw text-caption text-green-200"></i> &dollar;1927</td>
                                    </tr>
                                    <tr>
                                        <td><a href="#">September 2015</a></td>
                                        <td class="width-100"><i class="fa fa-circle-o fa-fw text-caption text-orange-200"></i> &dollar;42626</td>
                                        <td class="width-100"><i class="fa fa-circle-o fa-fw text-caption text-green-200"></i> &dollar;32727</td>
                                    </tr>
                                    <tr>
                                        <td><a href="#">October 2015</a></td>
                                        <td class="width-100"><i class="fa fa-circle-o fa-fw text-caption text-orange-200"></i> &dollar;71043</td>
                                        <td class="width-100"><i class="fa fa-circle-o fa-fw text-caption text-green-200"></i> &dollar;94976</td>
                                    </tr>
                                    <tr>
                                        <td><a href="#">November 2015</a></td>
                                        <td class="width-100"><i class="fa fa-circle-o fa-fw text-caption text-orange-200"></i> &dollar;40872</td>
                                        <td class="width-100"><i class="fa fa-circle-o fa-fw text-caption text-green-200"></i> &dollar;93929</td>
                                    </tr>
                                    <tr>
                                        <td><a href="#">December 2015</a></td>
                                        <td class="width-100"><i class="fa fa-circle-o fa-fw text-caption text-orange-200"></i> &dollar;5143</td>
                                        <td class="width-100"><i class="fa fa-circle-o fa-fw text-caption text-green-200"></i> &dollar;27588</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                        </div>
    
    
                    </div>
    
                </div><!-- /st-content-inner -->
    
            </div><!-- /st-content -->
    
        </div><!-- /st-pusher -->

















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