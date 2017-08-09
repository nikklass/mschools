<!-- Sidebar component with st-effect-1 (set on the toggle button within the navbar) -->
<div class="sidebar left sidebar-size-3 sidebar-offset-0 sidebar-visible-desktop sidebar-visible-mobile sidebar-skin-dark" id="sidebar-menu" data-type="collapse">
    <div data-scrollable>

        
        <div class="sidebar-block">
            <div class="profile">
                <a href="#">
                    <img src="<?=SITEPATH?>images/people/kibaki.jpg" alt="Kibaki" class="img-circle width-80"/>
                </a>
                <h4 class="text-display-1 margin-none">Mwai Mwizi</h4>
            </div>
        </div>
        

        <ul class="sidebar-menu">
            <li class="active"><a href="<?=SITEPATH?>admin"><i class="fa fa-bar-chart-o"></i><span>Dashboard</span></a></li>
            <li class="hasSubmenu">
                <a href="#course-menu"><i class="fa fa-mortar-board"></i><span>Fees</span></a>
                <ul id="course-menu">
                    <li><a href="<?=SITEPATH?>admin"><span>Fees Balance</span></a></li>
                    <li><a href="<?=SITEPATH?>admin"><span>Fees Statement</span></a></li>
                </ul>
            </li>
            <li class="hasSubmenu">
                <a href="#forum-menu"><i class="fa fa-file-text-o"></i><span>Results</span></a>
                <ul id="forum-menu">
                    <li><a href="<?=SITEPATH?>admin"><span>Find Results</span></a></li>
                    <li><a href="<?=SITEPATH?>admin"><span>My Results</span></a></li>
                </ul>
            </li>
            <li class="hasSubmenu">
                <a href="#account-menu"><i class="fa fa-user"></i><span>School</span></a>
                <ul id="account-menu">
                    <li ><a href="<?=SITEPATH?>admin"><span>School Info</span></a></li>
                    <li ><a href="<?=SITEPATH?>admin"><span>School Activities</span></a></li>
                </ul>
            </li>
            <li><a href="<?=SITEPATH?>messages"><i class="fa fa-comments"></i><span>My Messages</span></a></li>
            <li><a href="<?=SITEPATH?>admin" class="logout"><i class="fa fa-sign-out"></i><span>Logout</span></a></li>
        </ul>
    </div>
</div>