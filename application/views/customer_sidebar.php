<?php $session_data = $this->session->userdata('admin_user');
$admin_role_name = array('software_user');
$this->load->helper('frontend_common');
$customer_id = isset($session_data['customer_id']) && $session_data['customer_id'] != "" ? $session_data['customer_id'] : "";
$customer_setting = get_customer_setting($customer_id);
$hub_wise_portal = 2;
if (isset($customer_setting['hub_wise_portal']) && $customer_setting['hub_wise_portal'] == 1) {
    $hub_wise_portal = 1;
}
?>
<style>
    .sidebar li {
        line-height: 1.2 !important;
    }

    .sidebar li a {
        font-size: 12px;
    }
</style>
<?php $app_setting = get_app_setting(" AND module_name IN('main','manifest','customer_estimate','account','general','portal_doc','api')");
if (isset($session_data['is_restrict']) && $session_data['is_restrict'] != 1) {
    $validation_field = get_validation_field_navbar(1, 'label_key', 2);
    $forwarding_no_2 = $validation_field['forwarding_no_2'];
    if (isset($forwarding_no_2) && $forwarding_no_2 != "") {
        $place_holder = "/ FORWARDING NO. 2";
    }
}
$this->load->module('generic_detail');
$note_param['customer_id'] = $customer_id;

$note_error = $this->generic_detail->check_opening_note_limit($note_param);
if (isset($note_error['error']) && is_array($note_error['error']) && count($note_error['error']) > 0) {
    $due_expire = true;
} else {
    $due_expire = false;
}
// echo '<pre>';
// print_r($_SESSION);
// exit;
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark custom_navbar" style="background: #000000 !important;
    box-shadow: 0px 1px 5px 1px rgb(0 0 0 / 30%) !important;
    padding: 0 !important;">
    <div class="container-fluid">
        <?php if (isset($session_data['logo']) && $session_data['logo'] != '' && file_exists($session_data['logo'])) { ?>
            <a class="navbar-brand" href="<?php echo site_url(); ?>"><img src="<?php echo base_url($session_data['logo']); ?>"></a>
        <?php } ?>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbar">
            <ul class="navbar-nav">


                <?php if (isset($session_data['is_restrict']) && $session_data['is_restrict'] == 2) { ?>
                    <li class="nav-item dropdown <?php echo active('docket', 1); ?>">
                        <a class="nav-link" href="" id="dropdown02" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-warehouse"></i>
                            <span>AWB Entries</span>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdown02">
                            <div class="row">
                                <div class="col-sm-12 col-lg-12">
                                    <?php
                                    if (in_array("view_docket", $session_data['user_permission']) || !in_array($session_data['type'], $admin_role_name) || $session_data['role'] == $super_admin_role) {
                                    ?>
                                        <a class="dropdown-item" href="<?php echo site_url('docket/show_list'); ?>"> All AWB Entry</a>
                                    <?php
                                    }
                                    if (in_array("create_docket", $session_data['user_permission']) || !in_array($session_data['type'], $admin_role_name) || $session_data['role'] == $super_admin_role) {
                                    ?>
                                        <a class="dropdown-item" href="<?php echo site_url('docket/add'); ?>"> New AWB Entry</a>

                                    <?php }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </li>

                    <?php
                    if ((isset($app_setting['enable_manifest']) && $app_setting['enable_manifest'] == 1) ||
                        (isset($app_setting['enable_transfer_manifest']) && $app_setting['enable_transfer_manifest'] == 1)
                    ) {
                        if (in_array("view_manifest", $session_data['user_permission'])) {
                            if (isset($app_setting['enable_transfer_manifest']) && $app_setting['enable_transfer_manifest'] == 1) { ?>
                                <li class="nav-item dropdown <?php echo isset($parent_nav) && $parent_nav == 'manifest' ? 'active' : ''; ?>">
                                    <a class="nav-link" href="" id="dropdown02" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-database"></i>
                                        <span>Manifest</span>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="dropdown02">
                                        <div class="row">
                                            <div class="col-sm-12 col-lg-12">
                                                <h3>Transfer Manifests</h3>
                                                <?php
                                                if (in_array("create_manifest", $session_data['user_permission'])) {
                                                ?>
                                                    <a class="dropdown-item" href="<?php echo site_url('transfer_manifests/add'); ?>">New Transfer Manifest</a>
                                                <?php
                                                }
                                                if (in_array("view_manifest", $session_data['user_permission'])) {
                                                ?>
                                                    <a class="dropdown-item" href="<?php echo site_url('transfer_manifests/show_list'); ?>">All Transfer Manifests</a>

                                                <?php }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                    <?php }
                        }
                    } ?>
                    <?php if (isset($app_setting['enable_pickup']) && $app_setting['enable_pickup'] == 1) { ?>
                        <li class="nav-item dropdown <?php echo active('pick_up_requests', 1); ?>">
                            <a class="nav-link" href="" id="dropdown02" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-dolly"></i>
                                <span>PICKUP</span>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdown02">
                                <div class="row">
                                    <div class="col-sm-12 col-lg-12">
                                        <?php
                                        if (in_array("view_pickuprequest", $session_data['user_permission'])) {
                                        ?>
                                            <a class="dropdown-item" href="<?php echo site_url('pick_up_requests/show_list'); ?>">PICKUP REQUEST</a>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php } ?>

                    <?php
                    if (in_array("view_invoice", $session_data['user_permission'])) {
                    ?>
                        <li class="nav-item dropdown <?php echo active('invoice', 1); ?>">
                            <a class="nav-link" href="" id="dropdown02" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-file-invoice"></i> <span>INVOICES</span>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdown02">
                                <div class="row">
                                    <div class="col-sm-12 col-lg-12">
                                        <?php
                                        if (in_array("view_invoice", $session_data['user_permission'])) {
                                        ?>
                                            <a class="dropdown-item" href="<?php echo site_url('invoice/show_list'); ?>">All INVOICES</a>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php
                    }
                    ?>

                    <?php if (isset($app_setting['enable_rate_cal']) && $app_setting['enable_rate_cal'] == 1) { ?>
                        <li class="nav-item dropdown <?php echo active('rate_cal', 1); ?>">
                            <a class="nav-link" href="" id="dropdown02" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-inr"></i> <span>RATE CALS</span>

                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdown02">
                                <div class="row">
                                    <div class="col-sm-12 col-lg-12">
                                        <?php
                                        if (in_array("view_customer_rate_calc", $session_data['user_permission'])) {
                                        ?>
                                            <a class="dropdown-item" href="<?php echo site_url('rate_cal/live_customer_rate'); ?>">CUSTOMER RATE COMPARE</a>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php } ?>

                    <?php if ($hub_wise_portal == 2 && isset($app_setting['enable_sales_account']) && $app_setting['enable_sales_account'] == 1) { ?>
                        <?php
                        if (in_array("view_sales_account", $session_data['user_permission'])) {
                        ?>
                            <li class="nav-item dropdown <?php echo active('account', 1); ?>">
                                <a class="nav-link" href="" id="dropdown02" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-landmark"></i> <span>ACCOUNTS</span>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdown02">
                                    <div class="row">
                                        <div class="col-sm-12 col-lg-12">
                                            <a class="dropdown-item" href="<?php echo site_url('account/customer_credit/show_list'); ?>">CUSTOMER CREDIT/DEBIT</a>
                                            <a class="dropdown-item" href="<?php echo site_url('account/receipt/show_list'); ?>">RECEIPT</a>
                                            <a class="dropdown-item" href="<?php echo site_url('account/ledger/show_list'); ?>"><?php echo isset($_SESSION['ledger_label']) ? $_SESSION['ledger_label'] : 'LEDGER'; ?></a>
                                            <?php if (in_array($session_data['type'], $admin_role_name)) { ?>
                                                <a class="dropdown-item" href="<?php echo site_url('account/bank_ledger/show_list'); ?>">BANK <?php echo isset($_SESSION['ledger_label']) ? $_SESSION['ledger_label'] : 'LEDGER'; ?></a>
                                            <?php
                                            }
                                            ?>
                                            <a class="dropdown-item" href="<?php echo site_url('report/outstanding_report/show_list'); ?>">OUTSTANDING</a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php
                        }
                        ?>
                    <?php } ?>


                    <?php
                    if (in_array("view_reports", $session_data['user_permission']) && $hub_wise_portal == 2) {
                    ?>
                        <li class="nav-item dropdown <?php echo active('report', 1); ?>">
                            <a class="nav-link" href="" id="dropdown02" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-chart-area"></i> <span>REPORT</span>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdown02">
                                <div class="row">
                                    <div class="col-sm-12 col-lg-12">
                                        <?php $custom_report_data = get_custom_report_list();
                                        if (isset($custom_report_data) && is_array($custom_report_data) && count($custom_report_data) > 0) { ?>
                                            <h3 class="text-black"><strong>CUSTOM</strong></h3>
                                            <?php foreach ($custom_report_data as $rkey => $rvalue) {
                                                $report_Setting = isset($rvalue['docket_setting_data']) ? explode(",", $rvalue['docket_setting_data']) : array();
                                                if (in_array('is_portal_active', $report_Setting)) {
                                            ?>
                                                    <a class="dropdown-item" href="<?php echo site_url('report/custom_report/show_list/' . $rvalue['id']); ?>"><?php echo ucwords($rvalue['name']); ?></a>
                                        <?php }
                                            }
                                        }
                                        ?>

                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php
                    }
                    ?>


                    <?php if ((isset($app_setting['rate_api_portal_doc']) && $app_setting['rate_api_portal_doc'] == 1)
                        || isset($app_setting['tracking_api_portal_doc']) && $app_setting['tracking_api_portal_doc'] == 1
                        || isset($app_setting['label_api_portal_doc']) && $app_setting['label_api_portal_doc'] == 1
                    ) { ?>
                        <li class="nav-item dropdown <?php echo active('documentation', 1); ?>">
                            <a class="nav-link" href="" id="dropdown02" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-newspaper"></i> <span>API DOCS</span>

                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdown02">
                                <div class="row">
                                    <div class="col-sm-12 col-lg-12">
                                        <?php
                                        if (isset($app_setting['label_api_portal_doc']) && $app_setting['label_api_portal_doc'] == 1) {
                                        ?>
                                            <a class="dropdown-item" href="<?php echo site_url('documentation/show_label_doc'); ?>">LABEL API</a>
                                        <?php
                                        }
                                        ?>
                                        <?php
                                        if (isset($app_setting['tracking_api_portal_doc']) && $app_setting['tracking_api_portal_doc'] == 1) {
                                        ?>
                                            <a class="dropdown-item" href="<?php echo site_url('documentation/show_track_doc'); ?>">TRACKING API</a>
                                        <?php
                                        }
                                        ?>
                                        <?php
                                        if (isset($app_setting['rate_api_portal_doc']) && $app_setting['rate_api_portal_doc'] == 1) {
                                        ?>
                                            <a class="dropdown-item" href="<?php echo site_url('documentation/show_rate_doc'); ?>">RATE API</a>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php } ?>


                    <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" <?php //echo $btn_style; 
                                                                                                                        ?>>Balance : <?php //echo isset($balance_credit) ? $balance_credit : 0 
                                                                                                                                        ?> INR
                    </button> -->
                    <?php /*<a href="<?php echo site_url("account/pay"); ?>" class="btn btn-primary" <?php echo $btn_style; ?>>Balance : <?php echo isset($balance_credit) ? $balance_credit : 0 ?> INR</a>*/ ?>

                    <?php if (isset($app_setting["enable_ticket_system"]) && $app_setting["enable_ticket_system"] == 1) { ?>
                        <a href="<?php echo site_url("ticket_system/show_list") ?>">
                            <div style="margin-top:5px;background: #fff !important;color: #000 !important;font-size: 12px !important;" class="btn btn-danger">Raise a Query.</div>
                        </a>
                    <?php } ?>



        </div>


        <div class="pull-right" style="width:80px;">
            <a href="<?php echo site_url('login/admin_login/logout'); ?>">
                <div style="margin-top:5px;background: #ffc800;color: #000;padding: 6px 6px;border-radius: 0.25rem;font-size: 12px !important;"><i class="fa fa-arrow-right"></i>Logout</div>
            </a>
        </div>



    <?php } ?>



    <!-- <a href="<?php echo site_url('login/admin_login/logout'); ?>" style="float: right;background: #ffc800;border-radius: 0.25rem;color: #000;padding: 6px 2px;">
        <i class="fa fa-arrow-right"></i> <span>Logout</span>
        <span class="pull-right-container">
            <i class="fa fa-sign-out pull-right"></i>
        </span>
    </a> -->

    </ul>
    </div>

</nav>


<section class="info_bar">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-12 custom_col">
                <form class="simple_form docket" action="<?php echo site_url('docket/show_list') ?>" method="GET">
                    <div class="input-group">
                        <input type="text" name="header_tracking_no" id="docket_tracking_no" class="form-control" placeholder="AWB NO. / FORWARDING NO. <?php echo $place_holder ?>" value="<?php echo isset($_GET['header_tracking_no']) ? $_GET['header_tracking_no'] : ''; ?>" style="height: 30px;">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-warning btn-xs" style="color:#000 !important;">Search</button>
                        </div>
                    </div>
                </form>
            </div>


            <div class="col-md-4 col-12">
                <?php
                $sessiondata = $this->session->userdata('admin_user');
                $customer_id = $sessiondata['customer_id'];
                $customer_currency = get_customer_currency($customer_id);
                $balance_credit = get_customer_ledger($customer_id);
                $credit_limit = get_customer_credit_limit($customer_id);


                if ($balance_credit < 0) {
                    $btn_style = "style='background:green;font-size: 13px;float: right;'";
                } else {
                    $btn_style = "style='background:red;font-size: 13px;float: right;'";
                }
                ?>
                <!-- <a href="<?php echo site_url("account/pay"); ?>" class="btn btn-primary" <?php echo $btn_style; ?>>Balance : <?php echo isset($balance_credit) ? $balance_credit : 0 ?> INR</a> -->

                <?php

                if ((isset($app_setting['enable_mig_payment']) && $app_setting['enable_mig_payment'] == 1)
                    || (isset($app_setting['enable_razorpay_payment']) && $app_setting['enable_razorpay_payment'] == 1)
                    || (isset($app_setting['enable_cashfree_payment']) && $app_setting['enable_cashfree_payment'] == 1)
                ) {
                    $payment_url = site_url('account/make_payment');
                } else {
                    $payment_url = "#";
                }
                ?>
                <a href="<?php echo $payment_url; ?>" class="btn btn-primary" <?php echo $btn_style; ?>>Balance : <?php echo isset($balance_credit) ? number_format($balance_credit, 2) : 0 ?> <?php echo $customer_currency; ?></a>
                <?php
                if ($credit_limit > 0) {
                    $balance_cust_credit = $credit_limit - $balance_credit;
                    if ($balance_cust_credit < 0 || $due_expire) {
                        $btn_style = "style='background:red;font-size: 12px;'";
                    } else {
                        $btn_style = "style='background:green;font-size: 12px;'";
                    } ?>
                    <a href="javascript:void(0);" class="btn btn-primary" <?php echo $btn_style; ?>>Available Credit Limit : <?php echo isset($balance_cust_credit) ? number_format($balance_cust_credit, 2) : 0 ?> <?php echo $customer_currency; ?></a>
                <?php }
                ?>
            </div>

            <?php if ($due_expire && $hub_wise_portal == 2) { ?>
                <div class="col-md-3 col-12 custom_col">
                    <div class="card1" style="background: #ff0000;border-radius: 0.25rem;">
                        <div class="card-body" style="padding: 3px;text-align:center;">
                            <p style="padding: 0;margin: 0;font-weight: 500;">
                                <a href="<?php echo site_url('report/outstanding_report/show_list'); ?>" style="color: #fff !important;">
                                    <span style="font-size: 12px !important;">EXPIRED DUE DATE</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-sign-out pull-right"></i>
                                    </span>
                                </a>
                            </p>
                        </div>

                    </div>
                </div>
            <?php } ?>

            <?php
            $session_data = $this->session->userdata('admin_user');
            ?>
            <div class="col-md-2 col-12 custom_col ml-auto">
                <a href="javascript:void(0)" class="btn btn-warning btn-xs w-100" style="color:#000 !important;font-size: 12px !important;"><i class="fa fa-user"></i> <span><?php echo $session_data['name']; ?></span></a>
            </div>

        </div>
    </div>
</section>

</section>

<div class="wrapper">
    <div class="content-wrapper">
        <?php
        $list_no_add = array(
            'incoming_manifests', 'report',
            'account/ledger', 'account/bank_ledger',
            'purchase_account/ledger', 'vendor_rate_estimates', 'ticket_system'
        );

        $show_form_module = array(
            'inventories', 'vouchers', 'send_address_book_emails', 'purchase_invoices'
        );
        $segment1 = $this->uri->segment(1);
        $segment2 = $this->uri->segment(2);
        $segment3 = $this->uri->segment(3);
        if (isset($heading) && $heading != '') {
            $add_function = 'add';
            if ($segment2 == 'edit' || $segment2 == 'show_list' || $segment3 == 'edit' || $segment3 == 'show_list') {
                if ($segment2 == 'invoice_range_masters') {
                    $segment1 = "invoice/invoice_range_masters";
                } else if ($segment1 == 'invoice') {
                    $add_function = 'customer_single';
                } else if ($segment1 == 'estimates') {
                    $add_function = 'import_form';
                } else if ($segment1 == 'vendor_invoices') {
                    $add_function = 'import_invoice/show_form';
                } else if ($segment1 == 'account' || $segment1 == 'purchase_account') {
                    $segment1 = $segment1 . '/' . $segment2;
                    $add_function = 'show_form';
                } else if (in_array($segment1, $show_form_module)) {
                    $add_function = 'show_form';
                } else if ($segment1 == 'leave') {
                    $segment1 = $segment1 . '/' . $segment2;
                    $add_function = 'add';
                } ?>

                <section class="content-header">
                    <h6><?php echo $heading; ?></h6>
                    <?php
                    $sessiondata = $this->session->userdata('admin_user');
                    $super_admin_role = $this->config->item('super_admin');
                    ?>
                    <div class="breadcrumb btn-group" rdive="group" aria-label="Basic example">
                        <a href="#" onclick="history.back()" class="btn btn-default btn-xs">Back</a>
                        <?php if (!in_array($segment1, $list_no_add)) {
                            if (isset($sessiondata['url_permission']) && in_array($segment1 . '/' . $add_function, $sessiondata['url_permission']) || $sessiondata['role'] == $super_admin_role) { ?>
                                <a href="<?php echo site_url($segment1 . '/' . $add_function); ?>" class="btn btn-info btn-xs" style="color:#fff;">New <?php echo $heading; ?></a>
                        <?php }
                        } ?>
                        </ol>
                </section>
        <?php }
        } ?>
        <div class="content">