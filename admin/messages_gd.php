<?php 
	include_once("api/includes/DB_handler.php"); 
	include_once("api/includes/Config.php"); 
	include_once("includes/funcs.php"); 
	
	$admin = true;
	$show_bootstrap_dialog = true;
	$show_chat_list = true;
	
	$show_scroll = true;
	
?>

<?php include_once("includes/check_if_logged_in.php"); ?>

<!DOCTYPE html>
<html class="st-layout ls-top-navbar-large ls-bottom-footer show-sidebar sidebar-l3" lang="en">

<head>
    
	<?php include_once("includes/head_scripts.php"); ?>
                
    <title>Messages :: <?=$page_titles?></title>

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
                            <div class="media messages-container media-clearfix-xs-min media-grid">
                                <div class="media-left">
                                    <div class="messages-list">
                                        <div class="panel panel-default paper-shadow" data-z="0.5" data-scrollable-h>
                                            <div id="chatPageNum" data-page="1"></div>
                                            <ul class="list-group" id="chats-list">
                                                
                                                <!--SHOW USER CHATS HERE-->
                                                
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="media-body">
        
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-btn">
                                                <a class="btn btn-primary" href="#">
                                                    <i class="fa fa-envelope"></i> Send
                                                </a>
                                            </div>
                                            <!-- /btn-group -->
                                            <input type="text" class="form-control share-text" placeholder="Write message..." />
                                        </div>
                                        <!-- /input-group -->
                                    </div>
                                    
                                    <div id="messagesPageNum" data-page="1"></div>
                                    
                                    <div id="messages-list" class="nicescroll">
        
                                        <!-- DISPLAY USER MESSAGES HERE FOR THE SELEECTED CHAT -->
                                    
                                    </div>
                                    
                                    
                                    
                                    
                                    <!--<div class="row">
                                        <div class="col-sm-9 chat-inputbar">
                                            <input class="form-control chat-input" type="text" placeholder="Enter your text">
                                            </div>
                                            <div class="col-sm-3 chat-send">
                                            <button class="btn btn-info btn-block waves-effect waves-light" type="submit">Send</button>
                                        </div>
                                    </div>-->
                                    
                                    
                                    
                                    
                                    
                       
                                                <div class="panel panel-primary">
                                                    <div class="panel-heading">
                                                        <span class="glyphicon glyphicon-comment"></span> Chat
                                                        <div class="btn-group pull-right">
                                                            <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                                                <span class="glyphicon glyphicon-chevron-down"></span>
                                                            </button>
                                                            <ul class="dropdown-menu slidedown">
                                                                <li><a href="http://www.jquery2dotnet.com"><span class="glyphicon glyphicon-refresh">
                                                                </span>Refresh</a></li>
                                                                <li><a href="http://www.jquery2dotnet.com"><span class="glyphicon glyphicon-ok-sign">
                                                                </span>Available</a></li>
                                                                <li><a href="http://www.jquery2dotnet.com"><span class="glyphicon glyphicon-remove">
                                                                </span>Busy</a></li>
                                                                <li><a href="http://www.jquery2dotnet.com"><span class="glyphicon glyphicon-time"></span>
                                                                    Away</a></li>
                                                                <li class="divider"></li>
                                                                <li><a href="http://www.jquery2dotnet.com"><span class="glyphicon glyphicon-off"></span>
                                                                    Sign Out</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <ul class="chat">
                                                            <li class="left clearfix"><span class="chat-img pull-left">
                                                                <img src="http://placehold.it/50/55C1E7/fff&amp;text=U" alt="User Avatar" class="img-circle">
                                                            </span>
                                                                <div class="chat-body clearfix">
                                                                    <div class="header">
                                                                        <strong class="primary-font">Jack Sparrow</strong> <small class="pull-right text-muted">
                                                                            <span class="glyphicon glyphicon-time"></span>12 mins ago</small>
                                                                    </div>
                                                                    <p>
                                                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare
                                                                        dolor, quis ullamcorper ligula sodales.
                                                                    </p>
                                                                </div>
                                                            </li>
                                                            <li class="right clearfix"><span class="chat-img pull-right">
                                                                <img src="http://placehold.it/50/FA6F57/fff&amp;text=ME" alt="User Avatar" class="img-circle">
                                                            </span>
                                                                <div class="chat-body clearfix">
                                                                    <div class="header">
                                                                        <small class=" text-muted"><span class="glyphicon glyphicon-time"></span>13 mins ago</small>
                                                                        <strong class="pull-right primary-font">Bhaumik Patel</strong>
                                                                    </div>
                                                                    <p>
                                                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare
                                                                        dolor, quis ullamcorper ligula sodales.
                                                                    </p>
                                                                </div>
                                                            </li>
                                                            <li class="left clearfix"><span class="chat-img pull-left">
                                                                <img src="http://placehold.it/50/55C1E7/fff&amp;text=U" alt="User Avatar" class="img-circle">
                                                            </span>
                                                                <div class="chat-body clearfix">
                                                                    <div class="header">
                                                                        <strong class="primary-font">Jack Sparrow</strong> <small class="pull-right text-muted">
                                                                            <span class="glyphicon glyphicon-time"></span>14 mins ago</small>
                                                                    </div>
                                                                    <p>
                                                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare
                                                                        dolor, quis ullamcorper ligula sodales.
                                                                    </p>
                                                                </div>
                                                            </li>
                                                            <li class="right clearfix"><span class="chat-img pull-right">
                                                                <img src="http://placehold.it/50/FA6F57/fff&amp;text=ME" alt="User Avatar" class="img-circle">
                                                            </span>
                                                                <div class="chat-body clearfix">
                                                                    <div class="header">
                                                                        <small class=" text-muted"><span class="glyphicon glyphicon-time"></span>15 mins ago</small>
                                                                        <strong class="pull-right primary-font">Bhaumik Patel</strong>
                                                                    </div>
                                                                    <p>
                                                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare
                                                                        dolor, quis ullamcorper ligula sodales.
                                                                    </p>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="panel-footer">
                                                        <div class="input-group">
                                                            <input id="btn-input" type="text" class="form-control input-sm" placeholder="Type your message here...">
                                                            <span class="input-group-btn">
                                                                <button class="btn btn-warning btn-sm" id="btn-chat">
                                                                    Send</button>
                                                            </span>
                                                        </div>
                                                    </div>
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