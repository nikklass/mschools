<!-- Fixed navbar -->
<div class="navbar navbar-size-large navbar-default navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <a href="#sidebar-menu" data-toggle="sidebar-menu" class="toggle pull-left visible-xs"><i class="fa fa-ellipsis-v"></i></a>
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-nav">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <div class="navbar-brand navbar-brand-primary navbar-brand-logo navbar-nav-padding-left">
                <a class="svg" href="<?=SITEPATH?>"><img src="<?=SITEPATH?>admin/images/logo-white.png" height="50" alt="pendoschools"></a>
            </div>
        </div>
    
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="main-nav">
            <ul class="nav navbar-nav">
                
                <?php 
					//check permissions whether user has permissions to perform tasks or if user is a school admin
					$add_school_perms=array(CREATE_SCHOOL_PERMISSION, UPDATE_SCHOOL_PERMISSION, READ_SCHOOL_PERMISSION, DELETE_SCHOOL_PERMISSION);
					if ($db->groupHasAnyRole(LOGGED_IN_USER_GROUP_ID, $add_school_perms) || SCHOOL_ADMIN_USER){ 
				?>
                        <li class="dropdown">
                            <a href="<?=SITEPATH?>admin/manage-school" class="dropdown-toggle" data-toggle="dropdown">School <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?=SITEPATH?>admin/manage-school">Manage School</a></li>
                            </ul>
                        </li>
                <?php 
					}
				?>
                                
                <?php 
					$add_student_perms=array(CREATE_STUDENT_PERMISSION, UPDATE_STUDENT_PERMISSION, READ_STUDENT_PERMISSION, DELETE_STUDENT_PERMISSION);
					if ($db->groupHasAnyRole(LOGGED_IN_USER_GROUP_ID, $add_student_perms) || SCHOOL_ADMIN_USER){ 
				?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Students <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?=SITEPATH?>admin/manage-students">Manage Students</a></li>
                            </ul>
                        </li>
                <?php 
					}
				?>
                
               
                <?php 
					$add_result_perms=array(CREATE_RESULT_PERMISSION, UPDATE_RESULT_PERMISSION, READ_RESULT_PERMISSION, DELETE_RESULT_PERMISSION);
					if ($db->groupHasAnyRole(LOGGED_IN_USER_GROUP_ID, $add_result_perms) || SCHOOL_ADMIN_USER){ 
				?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Results <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <!--<li><a href="<?//=SITEPATH?>admin/new-results">Upload New Results</a></li>-->
                                <li><a href="<?=SITEPATH?>admin/manage-results">Manage Results</a></li>
                            </ul>
                        </li>
                <?php 
					}
				?>
                
                <?php 
					$add_fee_perms=array(CREATE_FEE_PERMISSION, UPDATE_FEE_PERMISSION, READ_FEE_PERMISSION, DELETE_FEE_PERMISSION);
					if ($db->groupHasAnyRole(LOGGED_IN_USER_GROUP_ID, $add_fee_perms) || SCHOOL_ADMIN_USER){ 
				?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Fees <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?=SITEPATH?>admin/manage-fees">Manage Fees</a></li>
                            </ul>
                        </li>
                <?php 
					}
				?>
                
                <?php 
					$add_bulk_sms_perms=array(CREATE_BULKS_SMS_PERMISSION, UPDATE_BULKS_SMS_PERMISSION, READ_BULKS_SMS_PERMISSION, DELETE_BULKS_SMS_PERMISSION);
					if ($db->groupHasAnyRole(LOGGED_IN_USER_GROUP_ID, $add_bulk_sms_perms) || SCHOOL_ADMIN_USER){ 
				?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Bulk SMS <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?=SITEPATH?>admin/manage-bulk-sms">Manage Bulk SMS</a></li>
                            </ul>
                        </li>
                <?php 
					}
				?>
                
                
                <?php 
					$add_mpesa_trans_perms=array(CREATE_MPESA_TRANS_PERMISSION, UPDATE_MPESA_TRANS_PERMISSION, READ_MPESA_TRANS_PERMISSION, DELETE_MPESA_TRANS_PERMISSION);
					if ($db->groupHasAnyRole(LOGGED_IN_USER_GROUP_ID, $add_mpesa_trans_perms) || SCHOOL_ADMIN_USER){ 
				?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">MPESA <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?=SITEPATH?>admin/manage-mpesa">Manage MPESA Transactions</a></li>
                            </ul>
                        </li>
                <?php 
					}
				?>
                
                
                <?php 
					$add_student_perms=array(CREATE_STUDENT_PERMISSION, UPDATE_STUDENT_PERMISSION, READ_STUDENT_PERMISSION, DELETE_STUDENT_PERMISSION);
					if ($db->groupHasAnyRole(LOGGED_IN_USER_GROUP_ID, $add_student_perms) || SCHOOL_ADMIN_USER){ 
				?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Parents <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?=SITEPATH?>admin/manage-parents">Manage Parents</a></li>
                            </ul>
                        </li>
                <?php 
					}
				?>
                
                
                <?php 
					$add_reports_perms=array(CREATE_REPORT_PERMISSION, UPDATE_REPORT_PERMISSION, READ_REPORT_PERMISSION, DELETE_REPORT_PERMISSION);
					if ($db->groupHasAnyRole(LOGGED_IN_USER_GROUP_ID, $add_reports_perms) || SCHOOL_ADMIN_USER){ 
				?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Reports <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?=SITEPATH?>admin/fee-reports">View Fee Reports</a></li>
                                <li><a href="<?=SITEPATH?>admin/result-reports">View Result Reports</a></li>
                                <li><a href="<?=SITEPATH?>admin/mpesa-reports">View MPESA Reports</a></li>
                                <li><a href="<?=SITEPATH?>admin/bulk-sms-reports">View Bulk SMS Reports</a></li>
                            </ul>
                        </li>
                <?php 
					}
				?>
                
                
                <?php 
					if (SUPER_ADMIN_USER){ 
				?>
                
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Users <span class="caret"></span></a>
                            <ul class="dropdown-menu">

                                <li><a href="<?=SITEPATH?>admin/new-user">Add New User(s)</a></li>
                                <li><a href="<?=SITEPATH?>admin/manage-users">Manage Users</a></li>
                                <li><a href="<?=SITEPATH?>admin/manage-permissions">Manage User Permissions</a></li>
                                
                            </ul>
                        </li>
                
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Manage <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?=SITEPATH?>admin/manage-subjects">Manage Subjects</a></li>
                                <li><a href="<?=SITEPATH?>admin/manage-score-grades">Manage Subject Score Grades</a></li>
                                <li><a href="<?=SITEPATH?>admin/manage-total-points-grades">Manage Total Points Grades</a></li>
                                <li><a href="<?=SITEPATH?>admin/manage-schools">Manage Schools</a></li>
                            </ul>
                        </li>
                        
                <?php 
					}
				?>               
                
            </ul>
            <ul class="nav navbar-nav navbar-nav-bordered navbar-right">
                <!-- notifications -->
                <li class="dropdown notifications updates">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell-o"></i>
                        <!--<span class="badge badge-primary">4</span>-->
                    </a>
                    <ul class="dropdown-menu" role="notification">
                        <li class="dropdown-header">Notifications</li>
                        <!--<li class="media">
                            <div class="pull-right">
                                <span class="label label-success">New</span>
                            </div>
                            <div class="media-left">
                                <img src="<?=SITEPATH?>admin/images/people/guy-2.jpg" alt="people" class="img-circle" width="30">
                            </div>
                            <div class="media-body">
                                <a href="#">Adrian D.</a> posted <a href="#">a photo</a> on his timeline.
                                <br/>
                                <span class="text-caption text-muted">5 mins ago</span>
                            </div>
                        </li>
                        <li class="media">
                            <div class="pull-right">
                                <span class="label label-success">New</span>
                            </div>
                            <div class="media-left">
                                <img src="<?=SITEPATH?>admin/images/people/guy-6.jpg" alt="people" class="img-circle" width="30">
                            </div>
                            <div class="media-body">
                                <a href="#">Bill</a> posted <a href="#">a comment</a> on Adrian's recent <a href="#">post</a>.
                                <br/>
                                <span class="text-caption text-muted">3 hrs ago</span>
                            </div>
                        </li>
                        <li class="media">
                            <div class="media-left">
                                <span class="icon-block s30 bg-grey-200"><i class="fa fa-plus"></i></span>
                            </div>
                            <div class="media-body">
                                <a href="#">Mary D.</a> and <a href="#">Michelle</a> are now friends.
                                <p>
                                    <span class="text-caption text-muted">1 day ago</span>
                                </p>
                                <a href=""><img class="width-30 img-circle" src="<?=SITEPATH?>admin/images/people/woman-6.jpg" alt="people"></a>
                                <a href=""><img class="width-30 img-circle" src="<?=SITEPATH?>admin/images/people/woman-3.jpg" alt="people"></a>
                            </div>
                        </li>-->
                    </ul>
                </li>
                <!-- // END notifications -->
                <!-- User -->
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle user" data-toggle="dropdown">
                        <img src="<?=$profile_image?>" alt="<?=FIRST_NAME?>" class="img-circle" width="40" /> <?=FIRST_NAME?> <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="">Account</a></li>
                        <li><a href="">Profile</a></li>
                        <li><a href="" class="logout">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    
    </div>
    
</div>