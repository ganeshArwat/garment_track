<?php
$session_data = $this->session->userdata('admin_user');
$this->user_type = $session_data['type'] == 'customer' ? 2 : 1;
$super_admin_role = $this->config->item('super_admin');
$director_role = $this->config->item('director_role');
$billing_role = $this->config->item('billing_role');
$admin_role_name = $this->config->item('admin_role_name');
$this->load->helper('frontend_common');
$get_all_email = get_all_itd_admin();
if (isset($session_data['is_restrict']) && $session_data['is_restrict'] == 2) {
    $get_all_ticket = get_all_allocated_ticket();
}
$app_setting = get_app_setting(" AND module_name IN('general')");
?>
<?php $app_setting = get_app_setting(" AND module_name IN('main','manifest','customer_estimate','account','general')");
if (isset($session_data['is_restrict']) && $session_data['is_restrict'] != 1) {
    $validation_field = get_validation_field_navbar(1, 'label_key', 2);
    $forwarding_no_2 = $validation_field['forwarding_no_2'];
    $reference_no = $validation_field['reference_no'];
    $place_holder = '';
    if (isset($forwarding_no_2) && $forwarding_no_2 != "") {
        $place_holder .= "/ FORWARDING NO. 2";
    }

    if (isset($reference_no) && $reference_no != "") {
        $place_holder .= " / REFERENCE NUMBER";
    }
}
// echo '<pre>';
// print_r($forwarding_no_2);
// exit;
?>

<div id="pcoded" class="pcoded">
    <div class="pcoded-overlay-box"></div>
    <div class="pcoded-container navbar-wrapper">
        <nav class="navbar header-navbar pcoded-header">
            <div class="navbar-wrapper">
                <div class="navbar-logo">
                    <a class="mobile-menu waves-effect waves-light" id="mobile-collapse" href="#!">
                        <i class="ti-menu"></i>
                    </a>
                    <div class="mobile-search waves-effect waves-light">
                        <div class="header-search">
                            <div class="main-search morphsearch-search">
                                <div class="input-group">
                                    <span class="input-group-addon search-close"><i class="ti-close"></i></span>
                                    <input type="text" class="form-control" placeholder="Enter Keyword">
                                    <span class="input-group-addon search-btn"><i class="ti-search"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="<?php echo site_url(); ?>">
                        <?php if (isset($session_data['logo']) && $session_data['logo'] != '' && file_exists($session_data['logo'])) {  ?>
                            <img class="img-fluid" src="<?php echo base_url($session_data['logo']); ?>" alt="Comapny-Logo" />
                        <?php } ?>
                    </a>
                    <a class="mobile-options waves-effect waves-light">
                        <i class="ti-more"></i>
                    </a>
                </div>
                <div class="navbar-container container-fluid">
                    <ul class="nav-left">
                        <li>
                            <div class="sidebar_toggle"><a href="javascript:void(0)"><i class="ti-menu"></i></a></div>
                        </li>
                        <li class="header-search">
                            <div class="main-search morphsearch-search">
                                <div class="input-group">
                                    <span class="input-group-addon search-close"><i class="ti-close"></i></span>
                                    <input type="text" class="form-control">
                                    <span class="input-group-addon search-btn"><i class="ti-search"></i></span>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <ul class="nav-right">
                        <li class="user-profile header-notification">
                            <a href="#!" class="waves-effect waves-light">
                                <img src="assets/assets/images/faq_man.png" class="img-radius" alt="User-Profile-Image">
                                <span><?php echo isset($session_data['name']) && $session_data['name'] != "" ?  $session_data['name'] : ""; ?></span>
                                <i class="ti-angle-down"></i>
                            </a>
                            <ul class="show-notification profile-notification">
                                <li class="waves-effect waves-light">
                                    <a href="#!">
                                        <i class=""></i> role
                                    </a>
                                </li>
                                <li class="waves-effect waves-light">
                                    <a href="#!">
                                        <i class="ti-settings"></i> Settings
                                    </a>
                                </li>
                                <li class="waves-effect waves-light">
                                    <a href="user-profile.html">
                                        <i class="ti-user"></i> Profile
                                    </a>
                                </li>
                                <li class="waves-effect waves-light">
                                    <a href="<?php echo site_url('login/admin_login/logout'); ?>">
                                        <i class="ti-layout-sidebar-left"></i> Logout
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="pcoded-main-container">
            <div class="pcoded-wrapper">
                <nav class="pcoded-navbar">
                    <div class="sidebar_toggle"><a href="#"><i class="icon-close icons"></i></a></div>
                    <div class="pcoded-inner-navbar main-menu">
                        <?php if (isset($session_data['is_restrict']) && $session_data['is_restrict'] == 1) { ?>
                            <ul class="pcoded-item pcoded-left-item">
                                <li class="pcoded-hasmenu ">
                                    <a href="javascript:void(0)" class="waves-effect waves-dark">
                                        <span class="pcoded-micon"><i class="ti-direction-alt"></i><b>M</b></span>
                                        <span class="pcoded-mtext" data-i18n="nav.menu-levels.main">Company</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                    <ul class="pcoded-submenu">
                                        <li class="">
                                            <a href="<?php echo site_url('company/show_list'); ?>" class="waves-effect waves-dark">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext" data-i18n="nav.menu-levels.menu-level-21">COMPANY LIST</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>

                                    </ul>
                                </li>
                            </ul>
                        <?php } ?>
                        <?php if (in_array($session_data['type'], $admin_role_name)) {
                            if (in_array("view_user", $session_data['user_permission']) || !in_array($session_data['type'], $admin_role_name) || $session_data['role'] == $super_admin_role || (isset($session_data['is_restrict']) && $session_data['is_restrict'] == 1)) {
                        ?>
                                <ul class="pcoded-item pcoded-left-item">
                                    <li class="pcoded-hasmenu ">
                                        <a href="javascript:void(0)" class="waves-effect waves-dark">
                                            <span class="pcoded-micon"><i class="ti-direction-alt"></i><b>M</b></span>
                                            <span class="pcoded-mtext" data-i18n="nav.menu-levels.main">USERS</span>
                                            <span class="pcoded-mcaret"></span>
                                        </a>
                                        <?php
                                        if (in_array("create_user", $session_data['user_permission']) || !in_array($session_data['type'], $admin_role_name) || $session_data['role'] == $super_admin_role || (isset($session_data['is_restrict']) && $session_data['is_restrict'] == 1)) {
                                        ?>
                                            <ul class="pcoded-submenu">
                                                <li class="">
                                                    <a href="<?php echo site_url('company/show_list'); ?>" class="waves-effect waves-dark">
                                                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                        <span class="pcoded-mtext" data-i18n="nav.menu-levels.menu-level-21">ALL USERS</span>
                                                        <span class="pcoded-mcaret"></span>
                                                    </a>
                                                </li>

                                            </ul>
                                        <?php
                                        }
                                        if (in_array("view_user", $session_data['user_permission']) || !in_array($session_data['type'], $admin_role_name) || $session_data['role'] == $super_admin_role || (isset($session_data['is_restrict']) && $session_data['is_restrict'] == 1)) {
                                        ?>
                                            <ul class="pcoded-submenu">
                                                <li class="">
                                                    <a href="<?php echo site_url('company/show_list'); ?>" class="waves-effect waves-dark">
                                                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                        <span class="pcoded-mtext" data-i18n="nav.menu-levels.menu-level-21">ITD ADMIN EMAIL</span>
                                                        <span class="pcoded-mcaret"></span>
                                                    </a>
                                                </li>

                                            </ul>
                                        <?php
                                        }
                                        ?>
                                        <?php if (isset($session_data['is_restrict']) && $session_data['is_restrict'] == 1) { ?>
                                            <ul class="pcoded-submenu">
                                                <li class="">
                                                    <a href="<?php echo site_url('company/show_list'); ?>" class="waves-effect waves-dark">
                                                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                        <span class="pcoded-mtext" data-i18n="nav.menu-levels.menu-level-21">COMPANY LIST</span>
                                                        <span class="pcoded-mcaret"></span>
                                                    </a>
                                                </li>

                                            </ul>
                                        <?php } ?>
                                    </li>
                                </ul>
                        <?php
                            }
                        }
                        ?>

                    </div>
                </nav>
                <div class="pcoded-content">
                    <div class="page-header">
                    </div>