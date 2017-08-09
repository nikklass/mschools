<?php 
	include_once("api/includes/DB_handler.php"); 
	include_once("includes/funcs.php"); 
	include_once("api/includes/Config.php");
	
	$admin = true;
	$show_bootstrap_dialog = true;
	$show_chat_list = true;
	
	$show_scroll = true;
	$show_chat = true;
	
?>

<?php
	
	//echo "USER_ID ". USER_ID;
	$perms = ALL_STUDENT_PERMISSIONS; 
	
	if (!SUPER_ADMIN_USER) {

		$company_ids = $db->getUserCompanyIds(USER_ID, $perms); //echo "co ids - ". $company_ids; exit;
		
	}
	
	if ($_GET["sch_id"] && SUPER_ADMIN_USER) {
		
		$sch_id = $_GET["sch_id"];
		
		$items = $db->getSchoolGridListing("", USER_ID, "", "", "", $sch_id, 1);
                                                                                                                        
		$item_data = $items['rows'][0];
		$top_sch_name = $item_data['name'];	 //echo "id - $id";	exit;		
		
	} else {
		
		if (!SUPER_ADMIN_USER) {
			
			$items = $db->getSchoolGridListing("", USER_ID, "", "", "", "", 1, $company_ids);
			
		} else {

			$items = $db->getSchoolGridListing("", USER_ID, "", "", "", "", 1);
			
		}
		
		$item_data = $items['rows'][0];
		$sch_id = $item_data['id'];
		$top_sch_name = $item_data['name'];	 //echo "id - $id";	exit;												
	
	}
	
	//print_r($items); exit;
		
	$top_sch_id = $sch_id;
	
	$page_title = "Manage Students - $top_sch_name";
	
?>

<?php 

	//if user has read permissions
	$user_id = USER_ID; 
	if (!(SUPER_ADMIN_USER) && !($db->getEstPermissions($user_id, $est_id, $perms))) 
	{
		//user is not allowed to access page
		$page = LOGIN_URL;
		header("Location: $page"); 
		exit();
	} //echo "user_id - $user_id, $est_id, $perms";
	
?>

<?php 
	
	$hide_admin_css = "";
	
	//if user is not super admin, css to hide admin dropdown (school select)
	if (!SUPER_ADMIN_USER){
		$hide_admin_css = "hidden";
	}
	
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
                    
                    	<div class="form-group padding-20-all <?=$hide_admin_css?>">
                            <form>
                                <select id="school-select" name="sch_id" class="form-control">
                                                                                
                                    <?php
                                            
                                        if (SUPER_ADMIN_USER) {
                                            $items = $db->getSchoolGridListing("", USER_ID, "", "", "", "", "", "", 1);
                                        } else if ($company_ids) {
                                            $items = $db->getSchoolGridListing("", USER_ID, "", "", "", "", "", $company_ids, 1);
                                        } //print_r($items); exit;
                                                                                                
                                        if ($items['total']) {
                                        
                                            foreach ($items['rows'] as $key => $val) {
                                                $id = $val['id'];
                                                $name = $val['name'];														
                                                echo "<option value='$id' ";
                                                if ($sch_id == $id) { echo " selected "; } 
                                                echo ">$name</option>";
                                            }
                                        
                                        }
                                    
                                    ?>
                                    
                                </select>
                            </form>
                        </div>
        
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
                                        
                                        <div class="media">
                                                <a href="#modal-general" data-toggle="modal" class="btn btn-success btn-block noclick" id="start-new-chat">
                                                	<i class="fa fa-plus"></i> <span class="btn-text">Start a new chat</span>
                                                </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="media-body">
        
                                    <div class="form-group">
                                        <div class="input-group">
                                            
                                            <input type="text" name="message" id="message" class="form-control share-text" placeholder="Write message..." />
                                            <div class="input-group-btn" id="send-msg">
                                                <a class="btn btn-primary" href="#">
                                                    <i class="fa fa-envelope"></i> Send
                                                </a>
                                            </div>
                                            <!-- /btn-group -->
                                        </div>
                                        <!-- /input-group -->
                                    </div>
                                    
                                    <div id="messagesPageNum" data-page="1"></div>
                                    <div id="currentChatId" data-chat-id=""></div>
                                    
                                    <div id="messages-list" class="nicescroll">
        
                                        <!-- DISPLAY USER MESSAGES HERE FOR THE SELEECTED CHAT -->
                                    
                                    </div>

                                    
                                </div>
                            </div>
                        </div>
                        
                        <!--MODAL-->
						<?php include_once("includes/modal.php"); ?>
                        <!--END MODAL-->
        
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