<?php 
	
	include_once("api/includes/DB_handler.php"); 
	include_once("api/includes/Config.php"); 
	include_once("includes/funcs.php"); 
	
	$admin = true;
	$show_bootstrap_dialog = true;
	$show_chart = true;
	
	$show_admin_home = true;
	$show_scroll = true;
	$show_pie = true;
	
	$page_title = "Dashboard";
	
	//print_r($_SESSION); exit;
	
?>

<?php 
	//if user has read permissions
	if (!(SCHOOL_ADMIN_USER || SUPER_ADMIN_USER)) 
	{
		//user is not allowed to access page
		$page = LOGIN_URL;
		header("Location: $page"); 
		exit();
	}
	
	
	if ($_GET["sch_id"] && SUPER_ADMIN_USER) {
		
		$sch_id = $_GET["sch_id"];
		
	} else {
		
		//if (SCHOOL_ADMIN_USER) {
		
			//get the school ids
			$query = "SELECT sch_name, sch_id FROM sch_ussd WHERE status =  " . ACTIVE_STATUS;
			if (SCHOOL_ADMIN_USER) { $query .= " AND sch_id IN (" . USER_SCHOOL_IDS . ") "; }
			$query .= " ORDER BY sch_name LIMIT 0,1"; //echo $query; //exit
			//$query .= " WHERE sch_id = ? ";
			$stmt = $db->conn->prepare($query);
			//$stmt->bind_param("i", $sch_name, $sch_id);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($sch_name, $sch_id);
			$stmt->fetch();
		
		//}
	
	}
	
?>

<?php 
	
	$hide_admin_css = "";
	$bottom_margin_css = "";
	
	//if user is not super admin, css to hide admin dropdown (school select)
	if (!SUPER_ADMIN_USER){
		$hide_admin_css = "hidden";
		$bottom_margin_css = "margin-btm-20";
	}
	
?>

<?php include_once("includes/check_if_logged_in.php"); ?>

<!DOCTYPE html>
<html class="st-layout ls-top-navbar-large ls-bottom-footer show-sidebar sidebar-l3" lang="en">

<head>
    
	<?php include_once("includes/head_scripts.php"); ?>
                
    <title><?=$page_title?> :: <?=$page_titles?>
    </title>

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
        
                        <div class="page-section <?=$bottom_margin_css?>">
                            <h1 class="text-display-1"><?=$page_title?></h1>
                        </div>
                        
                        
                        <div class="<?=$hide_admin_css?>">
                        
                            <hr>
                            
                            <div class="form-group margin-tb-20 margin-side-20">
                                
                                <form>
                                    <select id="school-select" name="sch_id" class="form-control" data-parsley-trigger="change" data-parsley-required required>
                                                                                    
                                        <?php
                                                
                                            //get the user types
                                            $query = "SELECT sch_id, sch_name FROM sch_ussd WHERE status =  " . ACTIVE_STATUS;
                                            if (SCHOOL_ADMIN_USER) { $query .= " AND sch_id IN (" . USER_SCHOOL_IDS . ") "; }
                                            $query .= " ORDER BY sch_name"; //echo $query; //exit
                                            $stmt = $db->conn->prepare($query);
                                            $stmt->execute();
                                            /* bind result variables */
                                            $stmt->bind_result($id, $name);
                                            
                                            if (SUPER_ADMIN_USER) {
                                                echo "<option value=''>All</option> ";	
                                            }
                                            
                                            while ($stmt->fetch()) 
                                            {
                                      
                                                echo "<option value='$id' ";
                                                
                                                if ($sch_id == $id) { echo " selected "; } 
                                                
                                                echo ">$name</option>";
                                        
                                             } 
                                        
                                        ?>
                                        
                                    </select>
                                </form>
                                
                            </div>
                            
                            <hr>
                        
                        </div>
                        
        
                        <div class="row" data-toggle="isotope">
                                
                                <div class="item  col-sm-6 equalheight">
                                
                                    <div class="panel panel-default paper-shadow" data-z="0.5">
                                        <div class="panel-heading">
                                            <div class="media v-middle">
                                                <div class="media-body">
                                                    <h4 class="text-headline margin-none">Payments Stats</h4>
                                                    <p class="text-subhead text-light">
                                                    
                                                    	<select id="payment-select" name="payment_id" class="form-control">
                                                                                    
															<?php
                                                                    
                                                                for ($i = 0; $i >= -12; $i--){
																  $month = date('M', strtotime("$i month"));
																  $month_digit = date('m', strtotime("$i month"));
																  $year = date('Y', strtotime("$i month"));
																  echo "<option value='$month_digit-$year'>$month - $year</option>";
																}
                                                            
                                                            ?>
                                                            
                                                        </select>
                                                        
                                                    </p>
                                                </div>
                                                <div class="media-right">
                                                    <!--<a class="btn btn-white btn-flat" href="#">Full Reports</a>-->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            <div id="flot-chart-pie" class="height-300"></div>
                                        </div>
                                        <hr/>
                                        <div class="panel-body hidden">
                                            <div class="row text-center">
                                                <div class="col-md-4">
                                                    <h4 class="margin-none">Cash</h4>
                                                    <p class="text-display-1 text-info margin-none">102.4k</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <h4 class="margin-none">MPESA</h4>
                                                    <p class="text-display-1 text-success margin-none">550k</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <h4 class="margin-none">Cheque</h4>
                                                    <p class="text-display-1 text-warning margin-none">10k</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                
                                <div class="item col-sm-6 equalheight">
                                
                                    <div class="panel panel-default paper-shadow" data-z="0.5">
                                        <div class="panel-heading">
                                            <h4 class="text-headline margin-none">Fees Payments</h4>
                                            <p class="text-subhead text-light">Recent fees payments</p>
                                        </div>
                                        
                                        <table class="table text-subhead v-middle" id="table-fee-payments">
                                            <thead id="latest-fee-payments-head">
                                                <tr>
                                                    <th width="20%">Date</th>
                                                    <th width="30%">Student</th>
                                                    <th width="20%">Paid By</th>
                                                    <th width="10%">Mode</th>
                                                    <th width="20%" align="right" class="text-right">Amount (Ksh)</th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody id="latest-fee-payments">
                                           
                                            </tbody>
                                        </table>
        
                                    </div>
                                   
                                </div>
                                
                                <div class="clearfix"></div>
                                                            
                            <!--
                                <div class="item col-sm-6 equalheight">
                                    <div class="s-container">
                                        <h4 class="text-headline margin-none">New Chats</h4>
                                    </div>
                                    <div class="panel panel-default">
                                        <ul class="list-group" id="my-new-chats">
                                            
                                            <div id="messagesPageNum" data-page="1"></div>
                                            
                                            <div id="messages-list" class="nicescroll">
                
                                            
                                            </div>
                                            
                                        </ul>
            
                                    </div>
                                </div>
                                
                                <div class="item col-sm-6 equalheight">
                                    <div class="s-container">
                                        <h4 class="text-headline margin-none">New School Subscriptions</h4>
                                    </div>
                                    <div class="panel panel-default">
                                        <ul class="list-group" id="my-new-school-subs">
                                                                                        
                                            <div id="school-sub-list" class="nicescroll">
                
                                            
                                            </div>
                                            
                                        </ul>
            
                                    </div>
                                </div>

                                <div class="clearfix"></div> -->
                        
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