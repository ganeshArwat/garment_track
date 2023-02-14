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


        <div class="pull-right" style="width:80px;">
            <a href="<?php echo site_url('login/admin_login/logout'); ?>">
                <div style="margin-top:5px;background: #ffc800;color: #000;padding: 6px 6px;border-radius: 0.25rem;font-size: 12px !important;"><i class="fa fa-arrow-right"></i>Logout</div>
            </a>
        </div>






        <!-- <a href="<?php echo site_url('login/admin_login/logout'); ?>" style="float: right;background: #ffc800;border-radius: 0.25rem;color: #000;padding: 6px 2px;">
        <i class="fa fa-arrow-right"></i> <span>Logout</span>
        <span class="pull-right-container">
            <i class="fa fa-sign-out pull-right"></i>
        </span>
    </a> -->

        </ul>
    </div>

</nav>



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