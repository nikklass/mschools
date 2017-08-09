<?php 
	include_once("admin/api/includes/DB_handler.php"); 
	include_once("admin/api/includes/Config.php"); 

	$page_title = "Home";
	
?>

<!DOCTYPE html>
<html class="transition-navbar-scroll top-navbar-xlarge bottom-footer" lang="en">
<head>
    
    <?php include_once("includes/head_scripts.php"); ?>
                
    <title><?=$page_title?> :: <?=$page_titles?></title>

</head>
<body>


<?php include_once("includes/nav.php"); ?>


    <div class="parallax cover overlay cover-image-full home">
    <img class="parallax-layer" src="<?=SITEPATH?>images/home.jpg" alt="Pendo Main Image" />
    <div class="parallax-layer overlay overlay-full overlay-bg-white bg-transparent" data-speed="8" data-opacity="true">
        <div class="v-center">
            <div class="page-section overlay-bg-white-strong relative paper-shadow" data-z="1">
                <h1 class="text-display-2 margin-v-0-15 display-inline-block">Access Your Child's School &amp; Performance Data</h1>
                <p class="text-subhead">Get all info regarding your child's performance, fees and school activities</p>
                
                <?php if (!USER_LOGGED_IN) { ?>
                	<a class="btn btn-green-500 btn-lg paper-shadow" data-hover-z="2" href="<?=REGISTER_URL?>">Sign Up - Free</a>
                <?php } ?>
                <br>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="page-section-heading">
        <h2 class="text-display-1">Accessible Data</h2>
        <p class="lead text-muted">You can access the information listed below</p>
    </div>
    <div class="row" data-toggle="gridalicious">

        <div class="media">
            <div class="media-left padding-none">
                <div class="bg-green-300 text-white">
                    <div class="panel-body">
                        <i class="fa fa-film fa-2x fa-fw"></i>
                    </div>
                </div>
            </div>
            <div class="media-body">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="text-headline">Student Performance</div>
                        <p>Track your childs performance on mschools. This system enables you to view your childs performance for each exam on Pendo Schools mobile app, USSD code *533*13# or by logging into www.mschools.co.ke.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="media">
            <div class="media-left padding-none">
                <div class="bg-purple-300 text-white">
                    <div class="panel-body">
                        <i class="fa fa-life-bouy fa-2x fa-fw"></i>
                    </div>
                </div>
            </div>
            <div class="media-body">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="text-headline">Pay School Fees</div>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aspernatur aut culpa fugiat iusto, molestias nemo nostrum quia rerum temporibus voluptatum.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="media">
            <div class="media-left padding-none">
                <div class="bg-orange-400 text-white">
                    <div class="panel-body">
                        <i class="fa fa-user fa-2x fa-fw"></i>
                    </div>
                </div>
            </div>
            <div class="media-body">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="text-headline">Track School Activities</div>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aspernatur aut culpa fugiat iusto, molestias nemo nostrum quia rerum temporibus voluptatum.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="media">
            <div class="media-left padding-none">
                <div class="bg-cyan-400 text-white">
                    <div class="panel-body">
                        <i class="fa fa-wechat fa-2x fa-fw"></i>
                    </div>
                </div>
            </div>
            <div class="media-body">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="text-headline">School Chat</div>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aspernatur aut culpa fugiat iusto, molestias nemo nostrum quia rerum temporibus voluptatum.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="media">
            <div class="media-left padding-none">
                <div class="bg-pink-400 text-white">
                    <div class="panel-body">
                        <i class="fa fa-print fa-2x fa-fw"></i>
                    </div>
                </div>
            </div>
            <div class="media-body">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="text-headline">View Child Info</div>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aspernatur aut culpa fugiat iusto, molestias nemo nostrum quia rerum temporibus voluptatum.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="media">
            <div class="media-left padding-none">
                <div class="bg-red-400 text-white">
                    <div class="panel-body">
                        <i class="fa fa-tasks fa-2x fa-fw"></i>

                    </div>
                </div>
            </div>
            <div class="media-body">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="text-headline">View Historical Data</div>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aspernatur aut culpa fugiat iusto, molestias nemo nostrum quia rerum temporibus voluptatum.</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
<br/>

<?php include_once("includes/top_footer.php"); ?>

<?php include_once("includes/bottom_footer.php"); ?>

<?php include_once("includes/js.php"); ?>

</body>
</html>
