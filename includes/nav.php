<!-- Fixed navbar -->
<div class="navbar navbar-default navbar-fixed-top navbar-size-large navbar-size-xlarge paper-shadow" data-z="0" data-animated role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-nav">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <div class="navbar-brand navbar-brand-logo">
                <a href="<?=SITEPATH?>">
                	<img src="<?=SITEPATH?>admin/images/logo.png" alt="PendoSchools" height="100%"/>
                </a>
            </div>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="main-nav">
            <ul class="nav navbar-nav navbar-nav-margin-left">
               
                <li <?php if (!$arg_one){ echo ' class="active"'; } ?>>
                    <a href="<?=SITEPATH?>">Home</a>
                </li>
                
                <li <?php if ($arg_one=="about"){ echo ' class="active"'; } ?>>
                    <a href="<?=SITEPATH?>about">About Us</a>
                </li>
                
                <li <?php if ($arg_one=="schools"){ echo ' class="active"'; } ?>>
                    <a href="<?=SITEPATH?>schools">Schools</a>
                </li>
                
                <li <?php if ($arg_one=="subscriptions"){ echo ' class="active"'; } ?>>
                    <a href="<?=SITEPATH?>subscriptions">Subscriptions</a>
                </li>
                
                <li <?php if ($arg_one=="chats"){ echo ' class="active"'; } ?>>
                    <a href="<?=SITEPATH?>chats">Chats</a>
                </li>
                
                <li <?php if ($arg_one=="contacts"){ echo ' class="active"'; } ?>>
                    <a href="<?=SITEPATH?>contacts">Contact Us</a>
                </li>
                
            </ul>
            <div class="navbar-right">
                
				<?php if (USER_LOGGED_IN) { ?>
                
                    <ul class="nav navbar-nav navbar-nav-bordered navbar-nav-margin-right">
                        <!-- user -->
                        <li class="dropdown user">
                            <a href="#" class="dropdown-toggle user" data-toggle="dropdown">
                                <img src="<?=$profile_image?>" alt="<?=FIRST_NAME?>" class="img-circle" width="40" /> <?=FIRST_NAME?> <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="#"><i class="fa fa-user"></i> Profile</a></li>
                                <li><a href="<?=CHANGE_PASS_URL?>"><i class="fa fa-lock"></i> Change Password</a></li>
                                <li><a href="#" class="logout"><i class="fa fa-sign-out"></i> Logout</a></li>
                            </ul>
                        </li>
                        <!-- // END user -->
                    </ul>
                
                <?php } ?>
                
                
                <?php if (USER_LOGGED_IN) { ?>
                
                	<?php if (SUPER_ADMIN_USER || COMPANY_ADMIN_USER) { ?>
                    
                    	<a href="<?=SITEPATH?>admin" class="navbar-btn btn btn-success">Admin Panel</a>
                        
                    <?php } else { ?>
                    
                        <a href="<?=SITEPATH?>profile" class="navbar-btn btn btn-success">My Profile</a>
                        
                    <?php } ?>
                
                <?php } else { ?>
                
                	<a href="<?=LOGIN_URL?>" class="navbar-btn btn btn-primary">Log In</a> &nbsp;&nbsp;
                    <a href="<?=REGISTER_URL?>" class="navbar-btn btn btn-primary">Register</a> 
                    
                <?php } ?>
            </div>
        </div><!-- /.navbar-collapse -->

    </div>
</div>