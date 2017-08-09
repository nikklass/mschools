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
                                    
                                    
                                    
                                    
                                    <div class="row">
                                        <div class="col-sm-9 chat-inputbar">
                                            <input class="form-control chat-input" type="text" placeholder="Enter your text">
                                            </div>
                                            <div class="col-sm-3 chat-send">
                                            <button class="btn btn-info btn-block waves-effect waves-light" type="submit">Send</button>
                                        </div>
                                    </div>
                                                                        
                                    
                                    
                                    
                                    
                                    
                                    
                                     	<!-- CHAT -->
                                        <div class="col-lg-4">
                                            <div class="panel panel-default">
                                                <div class="panel-heading"> 
                                                    <h3 class="panel-title">Chat</h3> 
                                                </div> 
                                                <div class="panel-body"> 
                                                    <div class="chat-conversation">
                                                        <ul class="conversation-list nicescroll">
                                                            <li class="clearfix">
                                                                <div class="chat-avatar">
                                                                    <img src="assets/images/avatar-1.jpg" alt="male">
                                                                    <i>10:00</i>
                                                                </div>
                                                                <div class="conversation-text">
                                                                    <div class="ctext-wrap">
                                                                        <i>John Deo</i>
                                                                        <p>
                                                                            Hello!
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li class="clearfix odd">
                                                                <div class="chat-avatar">
                                                                    <img src="assets/images/users/avatar-5.jpg" alt="Female">
                                                                    <i>10:01</i>
                                                                </div>
                                                                <div class="conversation-text">
                                                                    <div class="ctext-wrap">
                                                                        <i>Smith</i>
                                                                        <p>
                                                                            Hi, How are you? What about our next meeting?
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li class="clearfix">
                                                                <div class="chat-avatar">
                                                                    <img src="assets/images/avatar-1.jpg" alt="male">
                                                                    <i>10:01</i>
                                                                </div>
                                                                <div class="conversation-text">
                                                                    <div class="ctext-wrap">
                                                                        <i>John Deo</i>
                                                                        <p>
                                                                            Yeah everything is fine
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li class="clearfix odd">
                                                                <div class="chat-avatar">
                                                                    <img src="assets/images/users/avatar-5.jpg" alt="male">
                                                                    <i>10:02</i>
                                                                </div>
                                                                <div class="conversation-text">
                                                                    <div class="ctext-wrap">
                                                                        <i>Smith</i>
                                                                        <p>
                                                                            Wow that's great
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                        <div class="row">
                                                            <div class="col-sm-9 chat-inputbar">
                                                                <input type="text" class="form-control chat-input" placeholder="Enter your text">
                                                            </div>
                                                            <div class="col-sm-3 chat-send">
                                                                <button type="submit" class="btn btn-info btn-block waves-effect waves-light">Send</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> 
                                            </div>
                                        </div> <!-- end col-->
            
            

                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                    
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