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
<body>


	<?php include_once("includes/nav.php"); ?>
    
    <div class="parallax overflow-hidden page-section bg-blue2-500">
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
    </div>
    
    <div class="container">
        <div class="page-section">
    
            <div class="row">
                
                <div class="col-md-4 col-lg-3">
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">Chats</h4>
                        </div>
                        <ul class="list-group list-group-menu">
                                                        
                            <div id="chats-list">
                            
                            
                            
                            </div>
                            
                            <li class="list-group-item noclick margin-top-20">
                            	<a href="#modal-general" data-toggle="modal" class="btn btn-success btn-block noclick" id="start-new-chat">
                                    <i class="fa fa-plus"></i> <span class="btn-text">Add a new chat</span>
                                </a>
                            </li>
                            
                        </ul>
                    </div>
                </div>
                <div class="col-md-8 col-lg-9" id="messages-list-container">
                        
                    
                    
                    <div id="messagesPageNum" data-page="1"></div>
                    
                    <div id="currentChatId" data-chatid=""></div>
                    
                    <div id="currentMessageId" data-msgid="" data-chatid=""></div>                   
                    
                    <div class="menu">
                        <div class="name" id="recipient_name"></div>
                    </div>
                    
                    <div id="messages-list" class="nicescroll bg-white mychat margin-btm-20">
                    
                            <!--<ol class="chat">
                            <li class="other">
                              <div class="msg">
                                  <div class="user">Marga<span class="range admin">Admin</span></div>
                                <p>Dude</p>
                                <p>Want to go dinner? <emoji class="pizza"></emoji></p>
                                <time>20:17</time>
                              </div>
                            </li>
                            <li class="self">
                              <div class="msg">
                                <p>Puff...</p>
                                <p>I'm still doing the Góngora comment... <emoji class="books"></emoji></p>
                                <p>Better other day</p>
                                <time>20:18</time>
                              </div>
                            </li>
                            <li class="other">
                              <div class="msg">
                                  <div class="user">Brotons</div>
                                <p>What comment about Góngora? <emoji class="suffocated"></emoji></p>
                                <time>20:18</time>
                              </div>
                            </li>
                            <li class="self">
                              <div class="msg">
                                <p>The comment sent Marialu</p>
                                <p>It's for tomorrow</p>
                                <time>20:18</time>
                              </div>
                            </li>
                            <li class="other">
                              <div class="msg">
                                  <div class="user">Brotons</div>
                                <p><emoji class="scream"></emoji></p>
                                <p>Hand it to me! <emoji class="please"></emoji></p>
                                <time>20:18</time>
                              </div>
                            </li>
                            <li class="self">
                              <div class="msg">
                                <time>20:19</time>
                              </div>
                            </li>
                            <li class="other">
                              <div class="msg">
                                  <div class="user">Brotons</div>
                                <p>Thank you! <emoji class="hearth_blue"></emoji></p>
                                <time>20:20</time>
                              </div>
                            </li>
                                <div class="day">Today</div>
                            <li class="self">
                              <div class="msg">
                                <p>Who wants to play Minecraft?</p>
                                <time>18:03</time>
                              </div>
                            </li>
                            <li class="other">
                              <div class="msg">
                                  <div class="user">Charo</div>
                                <p>Come on, I didn't play it for four months</p>
                                <time>18:07</time>
                              </div>
                            </li>
                            <li class="self">
                              <div class="msg">
                                <p>Ehh, the launcher crash... <emoji class="cryalot"></emoji></p>
                                <time>18:08</time>
                              </div>
                            </li>
                            <li class="other">
                              <div class="msg">
                                  <div class="user">Charo</div>
                                <p><emoji class="lmao"></emoji></p>
                                <p>Sure that is the base code</p>
                                <p>I told it to Mojang</p>
                                <time>18:08</time>
                              </div>
                            </li>
                            <li class="self">
                              <div class="msg">
                                <p>It's a joke</p>
                                <p>Moai attack!</p>
                                <p><emoji class="moai"></emoji><emoji class="moai"></emoji><emoji class="moai"></emoji><emoji class="moai"></emoji><emoji class="moai"></emoji><emoji class="moai"></emoji></p>
                                <time>18:10</time>
                              </div>
                            </li>
                            <li class="other">
                              <div class="msg">
                                  <div class="user">Charo</div>
                                <p>XD</p>
                                <p><emoji class="funny"></emoji></p>
                                <p>Heart for this awesome design!</p>
                                <time>18:08</time>
                              </div>
                            </li>
                                <p class="notification">David joined the group <time>18:09</time></p>
                            <li class="self">
                              <div class="msg">
                                <p>Heeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeellooooooooooooooooooooooooooooooo David <emoji class="smile"/></p>
                                <time>18:09</time>
                              </div>
                            </li>
                            <li class="other">
                              <div class="msg">
                                  <div class="user">David</div>
                                  <p>What is that <emoji class="shit"></emoji> ?</p>
                                <time>18:10</time>
                              </div>
                            </li>
                                <p class="notification">David left the group <time>18:11</time></p>
                            <li class="other">
                              <div class="msg">
                                  <div class="user">Brotons</div>
                                <p>Lol?</p>
                                <time>18:12</time>
                              </div>
                            </li>
                            <li class="other">
                              <div class="msg">
                                  <div class="user">Marga<span class="range admin">Admin</span></div>
                                <p>I'm boring...</p>
                                <p>Who wants to do some logarithms? <emoji class="smile"></emoji></p>
                                <time>18:15</time>
                              </div>
                            </li>
                            </ol>-->
                            
                            <!--<div class="typezone">
                                <form><textarea type="text" placeholder="Say something"></textarea><input type="submit" class="send" value=""/></form>
                                <div class="emojis"></div>
                            </div>-->
                            
                    </div>
                    
                    <div class="form-group typezone" id="message-send-form">
                        <div class="input-group">
                            
                            <input type="text" name="message" id="message" class="form-control share-text" placeholder="Write message..." />
                            <div class="input-group-btn">
                                <a class="btn btn-primary noclick" href="" id="send-msg">
                                    <i class="fa fa-envelope"></i> Send
                                </a>
                            </div>
                            <!-- /btn-group -->
                        </div>
                        <!-- /input-group -->
                    </div>
    
                    <br/>
    
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

	<?php include_once("includes/top_footer.php"); ?>
    
    <?php include_once("includes/bottom_footer.php"); ?>
    
    <?php include_once("includes/js.php"); ?>

</body>
</html>