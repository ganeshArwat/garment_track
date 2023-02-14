<?php
$session_data = $this->session->userdata('admin_user');
$role_id = isset($session_data['role']) ? $session_data['role'] : '';
$permission = isset($session_data['assigned_permission']) ? $session_data['assigned_permission'] : '';
?>

<header class="page-topbar" id="header">
    <div class="navbar navbar-fixed ">
        <nav class="navbar-main navbar-color nav-collapsible sideNav-lock gradient-shadow  navbar-dark gradient-45deg-purple-deep-orange ">
        </nav>
    </div>
</header>

<aside class="sidenav-main nav-expanded nav-lock nav-collapsible navbar-full  sidenav-active-rounded  sidenav-light">
    <div class="brand-sidebar">
        <h1 class="logo-wrapper">
            <a class="brand-logo darken-1" href="#">
                <img src="<?php echo base_url();?>images/backend/itd_logo.png" alt="ASMART" style="height: 35px;">

                <span class="logo-text hide-on-med-and-down">
                    Admin
                </span>
            </a>
            <a class="navbar-toggler" href="javascript:void(0)"><i class="material-icons">radio_button_checked</i></a></h1>
    </div>
    <ul class="sidenav sidenav-collapsible leftside-navigation collapsible sidenav-fixed menu-shadow" id="slide-out" data-menu="menu-navigation" data-collapsible="menu-accordion" style="overflow: scroll;">


    <?php if ($role_id==1 || in_array('add_employee', $permission) || in_array('view_emp_list', $permission)) { ?>
        <li class="bold">
            <a class="collapsible-header waves-effect waves-cyan " href="javascript:void(0) " tabindex="0">
                <i class="material-icons">dvr</i>
                <span class="menu-title" data-i18n="Templates">Employee Management</span>
            </a>
            <div class="collapsible-body" style="">
                <ul class="collapsible collapsible-sub" data-collapsible="accordion">
                    <?php if ($role_id==1 || in_array('add_employee', $permission)) { ?>
                    <li class="">
                        <a href="<?php echo site_url('employee/employee_admin/add');?>" class=" ">
                            <i class="material-icons">radio_button_unchecked</i>
                            <span data-i18n="Modern Menu">Add</span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if ($role_id==1 || in_array('view_emp_list', $permission)) { ?>
                    <li class="">
                        <a href="<?php echo site_url('employee/employee_admin/show_list');?>" class=" ">
                            <i class="material-icons">radio_button_unchecked</i>
                            <span data-i18n="Navbar Dark">View List</span>
                        </a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </li>
        <?php } ?>


        <?php if ($role_id==1 || in_array('add_software_portal', $permission) || in_array('view_software_portal_list', $permission)) { ?>
        <li class="bold">
            <a class="collapsible-header waves-effect waves-cyan " href="javascript:void(0) " tabindex="0">
                <i class="material-icons">dvr</i>
                <span class="menu-title" data-i18n="Templates">Software Portal Management</span>
            </a>
            <div class="collapsible-body" style="">
                <ul class="collapsible collapsible-sub" data-collapsible="accordion">
                <?php if ($role_id==1 || in_array('add_software_portal', $permission)) { ?>
                    <li class="">
                        <a href="<?php echo site_url('project/project_admin/add');?>" class=" ">
                            <i class="material-icons">radio_button_unchecked</i>
                            <span data-i18n="Modern Menu">Add</span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if ($role_id==1 || in_array('view_software_portal_list', $permission)) { ?>
                    <li class="">
                        <a href="<?php echo site_url('project/project_admin/show_list');?>" class="">
                            <i class="material-icons">radio_button_unchecked</i>
                            <span data-i18n="Navbar Dark">View List</span>
                        </a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </li>
        <?php } ?>

        <?php if ($role_id==1 || in_array('add_master_workflow', $permission) || in_array('view_master_workflow_list', $permission)) { ?>
        <li class="bold">
            <a class="collapsible-header waves-effect waves-cyan " href="javascript:void(0) " tabindex="0">
                <i class="material-icons">dvr</i>
                <span class="menu-title" data-i18n="Templates">Master workflow Management</span>
            </a>
            <div class="collapsible-body" style="">
                <ul class="collapsible collapsible-sub" data-collapsible="accordion">
                <?php if ($role_id==1 || in_array('add_master_workflow', $permission)) { ?>
                    <li class="">
                        <a href="<?php echo site_url('master/master_admin/add');?>" class=" ">
                            <i class="material-icons">radio_button_unchecked</i>
                            <span data-i18n="Modern Menu">Add</span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if ($role_id==1 || in_array('view_master_workflow_list', $permission)) { ?>
                    <li class="">
                        <a href="<?php echo site_url('master/master_admin/show_list');?>" class="">
                            <i class="material-icons">radio_button_unchecked</i>
                            <span data-i18n="Navbar Dark">View List</span>
                        </a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </li>
        <?php } ?>

        <?php if ($role_id==1 || in_array('add_error', $permission) || in_array('view_error_list', $permission)) { ?>
        <li class="bold">
            <a class="collapsible-header waves-effect waves-cyan " href="javascript:void(0) " tabindex="0">
                <i class="material-icons">dvr</i>
                <span class="menu-title" data-i18n="Templates">Error Management</span>
            </a>
            <div class="collapsible-body" style="">
                <ul class="collapsible collapsible-sub" data-collapsible="accordion">
                <?php if ($role_id==1 || in_array('add_error', $permission)) { ?>
                    <li class="">
                        <a href="<?php echo site_url('error/error_admin/add');?>" class=" ">
                            <i class="material-icons">radio_button_unchecked</i>
                            <span data-i18n="Modern Menu">Add</span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if ($role_id==1 || in_array('view_error_list', $permission)) { ?>
                    <li class="">
                        <a href="<?php echo site_url('error/error_admin/show_list');?>" class="">
                            <i class="material-icons">radio_button_unchecked</i>
                            <span data-i18n="Navbar Dark">View List</span>
                        </a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </li>
        <?php } ?>

        

        <li class="bold ">
            <a class="waves-effect waves-cyan " href="<?php echo site_url('login/admin_login/logout');?>">
                <span class="menu-title" data-i18n="Logout">Logout</span>
            </a>
        </li>
    </ul>
    <div class="navigation-background"></div>
    <a class="sidenav-trigger btn-sidenav-toggle btn-floating btn-medium waves-effect waves-light hide-on-large-only" href="#" data-target="slide-out"><i class="material-icons">menu</i></a>
</aside>