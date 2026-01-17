<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?php print base_url() ?>" class="brand-link">
        <img src="<?php print base_url() ?>images/<?php echo $logo?>"  alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8; border-radius:45;">
        <br><span class="brand-text font-weight-light"><?php echo $name ?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?php print base_url() ?>dist/img/AdminLTELogo.png" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"><?php echo strtoupper($this->session->userdata("admin_pass")); ?></a>
            </div>
        </div>


        <!-- Sidebar Menu -->
        <nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
            <a href="<?php print base_url() ?>welcome/opendashboard" class="nav-link">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>Dashboard</p>
            </a>
        </li>
        
        <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-university"></i>
                <p>Academic Structure <i class="right fas fa-angle-left"></i></p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="<?php print base_url() ?>welcome/openschools" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Schools</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php print base_url() ?>welcome/opendepartments" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Departments</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php print base_url() ?>welcome/opencourses" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Courses</p>
                    </a>
                </li>
            </ul>
        </li>
        
        <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-book"></i>
                <p>Content Management <i class="right fas fa-angle-left"></i></p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="<?php print base_url() ?>welcome/opentopics" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Topics</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php print base_url() ?>welcome/openquestions" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Questions</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php print base_url() ?>welcome/opennotes" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Notes</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php print base_url() ?>welcome/batchupload" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Batch Upload</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php print base_url() ?>welcome/opennews" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>News/Updates</p>
                    </a>
                </li>
            </ul>
        </li>
        
        <li class="nav-item">
            <a href="<?php print base_url() ?>welcome/openinfo" class="nav-link" onclick="return confirm('Are you sure you want to go to settings?');">
                <i class="nav-icon fas fa-cogs"></i>
                <p>Settings</p>
            </a>
        </li>
        
        <li class="nav-item">
            <a href="<?php print base_url() ?>welcome/logout" class="nav-link" onclick="return confirm('Are you sure you want to logout?');">
                <i class="fas fa-sign-out-alt nav-icon text-danger"></i>
                <p>Log out</p>
            </a>
        </li>
    </ul>
</nav>        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
