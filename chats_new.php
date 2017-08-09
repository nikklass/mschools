<?php 
	include_once("admin/api/includes/DB_handler.php"); 
	include_once("admin/api/includes/Config.php");
	
	$form_validation = true; //form validation classes 
	$show_form = true;
	
	$show_chat_list = true;
	$show_popup = true; // show colorbox
	
	$show_scroll = true;
	$show_chat = true;
	
	$show_waypoints = true;

	$page_title = "My Chats";
	
?>

<?php
	if (!IS_USER_LOGGED_IN) {
		//user is logged in redirect to home page
		$home_page = LOGIN_URL;
		header("Location: $home_page"); 
 		exit();
	}
?>

<!DOCTYPE html>
<html class="transition-navbar-scroll top-navbar-xlarge bottom-footer" lang="en">
<head>
    
    <?php include_once("includes/head_scripts.php"); ?>
                
    <title><?=$page_title?> :: <?=$page_titles?></title>

</head>
<body class="chat-new">


	<?php include_once("includes/nav.php"); ?>
    
    <!--<div class="parallax overflow-hidden page-section bg-blue2-500">
        <div class="container parallax-layer" data-opacity="true">
            <div class="media media-grid v-middle">
                <div class="media-left">
                    <span class="icon-block half bg-blue2-600 text-white"><i class="fa fa-wechat"></i></span>
                </div>
                <div class="media-body">
                    <h3 class="text-display-2 text-white margin-none"><?=$page_title?></h3>
                    <p class="text-white text-subhead"></p>
                </div>
            </div>
        </div>
    </div>-->
    
    <div class="container">
        <div class="page-section">
    
            <div class="row">
                
                <div class="chat_box">
                	<div class="chat_head">Chatbox</div>
                    <div class="chat_body">
                    	<div class="chat-user">Nikk Kute</div>
                    </div>
                
                </div>
                
                <div class="msg_box" style="right:290px;">
                	<div class="msg_head">Nikk Kute
                    	<div class="chat-close">X</div>
                    </div>
                    <div class="msg_body">
                    	<div class="msg_a">This is from A</div>
                        <div class="msg_b">This is from B</div>
                    </div>
                    <div class="msg_footer"><textarea rows="4" class="msg_input">text here</textarea></div>
                </div>
                
            </div>
    
        </div>
    </div>
    
    <!-- create new chat form -->
        <div style='display:none'>
        
            <form class="form-horizontal form-create-new-chat box inputform" method="post" id="create_new_chat_form">
                                                                    
                <h3 class="text-center"><span class="text-center">Click a user below to begin chat</span></h3>
                
                <hr>
                
                <div class="form-group padding-20" id="chat-users-list">
                    
                    
                </div>
               
            </form>
        
        </div>
        <!-- /end create new chat -->

	<?php //include_once("includes/top_footer.php"); ?>
    
    <?php //include_once("includes/bottom_footer.php"); ?>
    
    <?php //include_once("includes/js.php"); ?>

</body>
</html>