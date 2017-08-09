<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="<?=SITEPATH?>admin/images/icon.ico">
    
    <!-- Google Fonts Library -->
    <link href='https://fonts.googleapis.com/css?family=Roboto:500,400,300,100,700,300italic' rel='stylesheet' type='text/css'>

    <!-- fontawesome -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    
    <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

 
    <link href="<?=SITEPATH?>admin/css/vendor/picto.css" rel="stylesheet">
    <link href="<?=SITEPATH?>admin/css/vendor/material-design-iconic-font.css" rel="stylesheet">
    <link href="<?=SITEPATH?>admin/css/vendor/jquery.minicolors.css" rel="stylesheet">
    <link href="<?=SITEPATH?>admin/css/vendor/railscasts.css" rel="stylesheet">
    <link href="<?=SITEPATH?>admin/css/vendor/owl.carousel.css" rel="stylesheet">
    <link href="<?=SITEPATH?>admin/css/vendor/slick.css" rel="stylesheet">    
    <link href="<?=SITEPATH?>admin/css/vendor/jquery.countdown.css" rel="stylesheet">
    <link href="<?=SITEPATH?>admin/css/vendor/sweetalert/dist/sweetalert.css" rel="stylesheet" type="text/css">
	<link href="<?=SITEPATH?>css/app/main.css" rel="stylesheet">
    <link href="<?=SITEPATH?>admin/css/app/common.css" rel="stylesheet">
    
    <?php if ($show_popup) { ?>
	
	<link href="<?=SITEPATH?>admin/css/vendor/colorbox/colorbox.css" rel="stylesheet">
	
	<?php } ?>
    
    <?php if ($show_scroller) { ?>
    
        <link href="<?=SITEPATH?>admin/css/vendor/customscroll/jquery.mCustomScrollbar.min.css" rel="stylesheet">
        <link href="<?=SITEPATH?>admin/css/app/scroller.css" rel="stylesheet">
        
    <?php } ?>
    
    <?php if ($show_form) { ?>
    	<link href="<?=SITEPATH?>admin/css/vendor/form_loader.css" rel="stylesheet">
        <link href="<?=SITEPATH?>admin/css/vendor/jquery.bootstrap-touchspin.css" rel="stylesheet">
   	 	<link href="<?=SITEPATH?>admin/css/app/bootstrap-select.min.css" rel="stylesheet">
        <link href="<?=SITEPATH?>admin/css/app/datepicker3.css" rel="stylesheet">
        <link href="<?=SITEPATH?>admin/css/vendor/daterangepicker-bs3.css" rel="stylesheet">
        <link href="<?=SITEPATH?>admin/css/app/checks.css" rel="stylesheet">
             
    <?php } ?>

    
    
    <?php if ($show_file_upload) { ?>
    	<link href="<?=SITEPATH?>admin/css/vendor/fileinput.min.css" rel="stylesheet">   
    <?php } ?>
    
    <?php if ($show_table) { ?>
        <link href="<?=SITEPATH?>admin/css/vendor/jquery.bootgrid.css" rel="stylesheet" />
    <?php } ?>

    <link href="<?=SITEPATH?>css/app/essentials.css" rel="stylesheet" />
    <link href="<?=SITEPATH?>admin/css/app/material.css" rel="stylesheet" />
    <link href="<?=SITEPATH?>admin/css/app/layout.css" rel="stylesheet" />
    
    <?php if (!$dont_show_sidebar) { ?>
    	<link href="<?=SITEPATH?>admin/css/app/sidebar.css" rel="stylesheet" />
        <link href="<?=SITEPATH?>admin/css/app/sidebar-skins.css" rel="stylesheet" />
    <?php }  ?>
     
    <link href="<?=SITEPATH?>css/app/navbar.css" rel="stylesheet" />
    
    <link href="<?=SITEPATH?>admin/css/app/media.css" rel="stylesheet" />
    <link href="<?=SITEPATH?>admin/css/app/charts.css" rel="stylesheet" />
    <link href="<?=SITEPATH?>admin/css/app/maps.css" rel="stylesheet" />
    <link href="<?=SITEPATH?>admin/css/app/colors-alerts.css" rel="stylesheet" />
    <link href="<?=SITEPATH?>admin/css/app/colors-background.css" rel="stylesheet" />
    <link href="<?=SITEPATH?>admin/css/app/colors-buttons.css" rel="stylesheet" />
    <link href="<?=SITEPATH?>admin/css/app/colors-text.css" rel="stylesheet" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries
WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!-- If you don't need support for Internet Explorer <= 8 you can safely remove these -->
    <!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
	
    <!-- Library - Animate CSS -->
    <link rel="stylesheet" href="<?=SITEPATH?>admin/css/vendor/animate.css">
    
    <?php if ($ladda_button) { ?>
    
    <link rel="stylesheet" href="<?=SITEPATH?>admin/css/vendor/ladda/ladda.min.css">    
    
    <?php } ?>
    
    <?php if ($show_bootstrap_dialog) { ?>
    
    <link rel="stylesheet" href="<?=SITEPATH?>admin/css/vendor/bootstrap-dialog.min.css">
    
    <?php } ?>
    
    <?php if ($show_chat) { ?>
        <link href="<?=SITEPATH?>admin/css/app/messages.css" rel="stylesheet" />  
        
        <link href="<?=SITEPATH?>css/app/chat2.css" rel="stylesheet" />  
    <?php } ?>
