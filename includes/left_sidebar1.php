
<div class="sidebar left sidebar-size-3 sidebar-offset-0 sidebar-visible-desktop sidebar-visible-mobile sidebar-skin-dark" id="sidebar-menu" data-type="collapse">
    <div data-scrollable>
    
        <div class="sidebar-block">
            <div class="profile">
                <a href="<?=SITEPATH?>admin/my-profile">
                    <img src="<?=$profile_image?>" alt="<?=FULL_NAMES?>" class="img-circle width-80" />
                </a>
                <h4 class="text-display-1 margin-none"><?=FULL_NAMES?></h4>
            </div>
        </div>
    
        <ul class="sidebar-menu">
            <li  <?php if ($arg_one=='') { echo " class='active'"; } ?>><a href="<?=SITEPATH?>admin/"><i class="fa fa-home"></i><span>Dashboard</span></a></li>
            <li  <?php if ($arg_one=='my-profile') { echo " class='active'"; } ?>><a href="<?=SITEPATH?>admin/my-profile"><i class="fa fa-user"></i><span>My Profile</span></a></li>
            <li <?php if ($arg_one=='messages') { echo " class='active'"; } ?>><a href="<?=SITEPATH?>admin/messages"><i class="fa fa-paper-plane"></i><span>Chat Messages</span></a></li>
            <li <?php if ($arg_one=='my-subscriptions') { echo " class='active'"; } ?>><a href="<?=SITEPATH?>admin/my-subscriptions"><i class="fa fa-mortar-board"></i><span>My Subscriptions</span></a></li>
            <li <?php if ($arg_one=='student-results') { echo " class='active'"; } ?>><a href="<?=SITEPATH?>admin/student-results"><i class="fa fa-bar-chart-o"></i><span>My Student Results</span></a></li>
            <li <?php if ($arg_one=='student-fees') { echo " class='active'"; } ?>><a href="<?=SITEPATH?>admin/student-fees"><i class="fa fa-bar-chart-o"></i><span>My Student Fees</span></a></li>
            <li><a href="#" class="logout"><i class="fa fa-sign-out"></i><span>Logout</span></a></li>
        </ul>
    </div>
</div>