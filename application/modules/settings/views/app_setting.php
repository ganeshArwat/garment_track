<style>
    .nav-tabs .nav-link.serachTab {
        background: #8ebfe6 !important;
    }
</style>
<?php
$session_data = $this->session->userdata('admin_user');
$admin_role_name = array('software_user');
?>

<div class="col-12">
    <?php $this->load->view('flashdata_msg'); ?>

    <div style="background: #1667a8;color: rgb(252 245 245);padding: 5px;font-size: 13px;display:none;" class="success_msg">
        <span><b></b></span>
    </div>

    <div style="background: #ff0000;color: #fff;padding: 5px;font-size: 13px;display:none;" class="error_msg">
        <span><b></b></span>
    </div>

    <div class="modal" tabindex="-1" role="dialog" id="definition_modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="width: 615px;">
                <div class="modal-header">
                    <h5 class="modal-title">APP DEFINITION</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>
                <div class="modal-body" id="definition_service">
                    <input type="hidden" id="modal_setting_id" />


                    <div class="col-12">
                        <div class="form-group row">
                            <label for="cust_id" class="col-sm-4 col-form-label">SETTING</label>
                            <div class="col-sm-8">
                                <span id="modal_setting_name"></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group row">
                            <label for="cust_id" class="col-sm-4 col-form-label">Setting Definition</label>
                            <div class="col-sm-8">
                                <textarea style="height: 95px;" id="modal_setting_definition" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>


                    <button type="button" class="btn btn-primary" onclick="save_definition();">SAVE DEFINITION</button>
                </div>
            </div>
        </div>
    </div>

    <!-- /.box -->
    <form enctype="multipart/form-data" method="POST" id="setting_form" action="<?php echo  isset($mode) && $mode == 'add_comments' ? site_url('settings/save_comments') : site_url('settings/save_setting'); ?>">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <?php echo $heading; ?>
                </h3>

                <?php

                if ($session_data["email"] == "virag@itdservices.in") { ?>
                    <input type="text" class="form-control" id="setting_search" style="display: inline;width: 55%;margin-left: 30px;" placeholder="SEARCH SETTINGS HERE..." />
                    <?php if (isset($mode) && $mode == "add_comments" && $session_data['email'] == "virag@itdservices.in") { ?>
                        <button type="submit" class="btn btn-primary pull-right">SAVE COMMENTS</button>
                    <?php } else { ?>
                        <button type="submit" class="btn btn-primary pull-right">Save</button>
                    <?php } ?>
                    <?php if ($mode != "add_comments" && $session_data['email'] == "virag@itdservices.in") { ?>
                        <a class="btn btn-secondary pull-right" href="<?php echo site_url('settings/show_form/add_comments'); ?>">Add Comments</a>
                    <?php } ?>
                    <?php if (isset($mode) && $mode != 'update') { ?>
                        <a class="pull-right btn btn-secondary text-white" onclick="history.back()" href="javascript:void(0);">BACK</a>
                <?php }
                } ?>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-5 col-lg-3">
                        <ul class="nav nav-tabs flex-column mb-3">
                            <li class="nav-item">
                                <a class="nav-link active show" data-toggle="tab" href="#general-tab">General</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#main-tab">Main</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link " data-toggle="tab" href="#tab-3">MASTER</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tab-2">AWB</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tab-1">Manifest</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tab-4">Pick Up</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tab-5">Accounts</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tab-6">Estimate</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tab-7">Vendor Estimate - L1 L2</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#shipping-api-tab">API</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#drs-tab">RunSheets</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#inventory-tab">Inventory</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#invoice-tab">Invoice</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#pdf-tab">PDF</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#mobile_app-tab">Mobile App</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#sms-tab">SMS</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#dashboard-tab">Dashboard</a>
                            </li>
                          
                        </ul>
                    </div>
                    <div class="col-sm-7 col-lg-9" id="setting_div">
                        <div class="tab-content">
                            <?php $this->load->view('general_setting'); ?>
                            <?php $this->load->view('main_setting'); ?>
                            <?php $this->load->view('master_setting'); ?>

                            <?php $this->load->view('docket_setting'); ?>
                            <?php $this->load->view('manifest_setting'); ?>
                            <?php $this->load->view('dashboard_setting'); ?>





                            <div class="tab-pane" id="tab-4">
                                <div class="col-12">
                                    <div class="form-group row">
                                        <label for="cust_id" title="<?php echo isset($setting_comment['pick_up_ref_no_prefix']) ? $setting_comment['pick_up_ref_no_prefix'] : ''; ?>" class="col-sm-6 col-form-label">Set Pickup Request Reference Number Range Prefix</label>
                                        <div class="col-sm-6">
                                            <?php if (isset($mode) && $mode == "add_comments") { ?>
                                                <textarea name="pick_up[pick_up_ref_no_prefix]" id="" cols="80" rows="1"><?php echo isset($setting['pick_up_ref_no_prefix']) ? $setting['pick_up_ref_no_prefix'] : ''; ?></textarea>
                                            <?php } else { ?>
                                                <input type="text" class="form-control" name="pick_up[pick_up_ref_no_prefix]" value="<?php echo isset($setting['pick_up_ref_no_prefix']) ? $setting['pick_up_ref_no_prefix'] : ''; ?>" />
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group row">
                                        <label for="cust_id" title="<?php echo isset($setting_comment['pick_up_ref_no_range']) ? $setting_comment['pick_up_ref_no_range'] : ''; ?>" class="col-sm-6 col-form-label">Set Pickup Request Reference Number Range</label>
                                        <div class="col-sm-6">
                                            <?php if (isset($mode) && $mode == "add_comments") { ?>
                                                <textarea name="pick_up[pick_up_ref_no_range]" id="" cols="80" rows="1"><?php echo isset($setting['pick_up_ref_no_range']) ? $setting['pick_up_ref_no_range'] : ''; ?></textarea>
                                            <?php } else { ?>
                                                <input type="text" class="form-control" name="pick_up[pick_up_ref_no_range]" value="<?php echo isset($setting['pick_up_ref_no_range']) ? $setting['pick_up_ref_no_range'] : ''; ?>" />
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php $this->load->view('account_setting'); ?>
                            <?php $this->load->view('sms_setting'); ?>


                            <div class="tab-pane" id="tab-6">
                                <div class="col-12">
                                    <div class="form-group row">
                                        <label for="cust_id" title="<?php echo isset($setting_comment['estimate_charge_id']) ? $setting_comment['estimate_charge_id'] : ''; ?>" class="col-sm-6 col-form-label">Enter comma saperated Charge Master ids for Estimate</label>
                                        <div class="col-sm-6">
                                            <?php if (isset($mode) && $mode == "add_comments") { ?>
                                                <textarea name="customer_estimate[estimate_charge_id]" id="" cols="80" rows="1"><?php echo isset($setting['estimate_charge_id']) ? $setting['estimate_charge_id'] : ''; ?></textarea>
                                            <?php } else { ?>
                                                <input type="text" class="form-control" name="customer_estimate[estimate_charge_id]" value="<?php echo isset($setting['estimate_charge_id']) ? $setting['estimate_charge_id'] : ''; ?>" />
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>


                            </div>


                            <div class="tab-pane" id="tab-7">
                                <div class="col-12">
                                    <div class="form-group row">
                                        <label for="vendor_estimate_charge_id" title="<?php echo isset($setting_comment['vendor_estimate_charge_id']) ? $setting_comment['vendor_estimate_charge_id'] : ''; ?>" class="col-sm-6 col-form-label">Enter comma saperated Charge Master ids for Vendor Estimate</label>
                                        <div class="col-sm-6">
                                            <?php if (isset($mode) && $mode == "add_comments") { ?>
                                                <textarea name="vendor_estimate[vendor_estimate_charge_id]" id="" cols="80" rows="1"><?php echo isset($setting['vendor_estimate_charge_id']) ? $setting['vendor_estimate_charge_id'] : ''; ?></textarea>
                                            <?php } else { ?>
                                                <input type="text" class="form-control" name="vendor_estimate[vendor_estimate_charge_id]" value="<?php echo isset($setting['vendor_estimate_charge_id']) ? $setting['vendor_estimate_charge_id'] : ''; ?>" />
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="tab-pane" id="shipping-api-tab">
                                <div class="col-12">
                                    <div class="form-group row">
                                        <label for="cust_id" title="<?php echo isset($setting_comment['label_api_test_mode']) ? $setting_comment['label_api_test_mode'] : ''; ?>" class="col-sm-6 col-form-label">Enable Label API Test Mode</label>
                                        <div class="col-sm-6">
                                            <div class="checkbox">
                                                <?php if (isset($mode) && $mode == "add_comments") { ?>
                                                    <textarea name="label_api[label_api_test_mode]" id="" cols="80" rows="1"><?php echo isset($setting['label_api_test_mode']) ? $setting['label_api_test_mode'] : ''; ?></textarea>
                                                <?php } else { ?>
                                                    <input type="checkbox" id="label_api_test_mode" value="1" autocomplete="nope" name="label_api[label_api_test_mode]" <?php echo isset($setting['label_api_test_mode']) && $setting['label_api_test_mode'] == 1 ? 'checked' : ''; ?>>
                                                    <label for="label_api_test_mode" style="height: 10px !important;"> </label>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group row">
                                        <label for="cust_id" title="<?php echo isset($setting_comment['dhl_send_actual_wt']) ? $setting_comment['dhl_send_actual_wt'] : ''; ?>" class="col-sm-6 col-form-label">Send Actual Weight In DHL Label API</label>
                                        <div class="col-sm-6">
                                            <div class="checkbox">
                                                <?php if (isset($mode) && $mode == "add_comments") { ?>
                                                    <textarea name="label_api[dhl_send_actual_wt]" id="" cols="80" rows="1"><?php echo isset($setting['dhl_send_actual_wt']) ? $setting['dhl_send_actual_wt'] : ''; ?></textarea>
                                                <?php } else { ?>
                                                    <input type="checkbox" id="dhl_send_actual_wt" value="1" autocomplete="nope" name="label_api[dhl_send_actual_wt]" <?php echo isset($setting['dhl_send_actual_wt']) && $setting['dhl_send_actual_wt'] == 1 ? 'checked' : ''; ?>>
                                                    <label for="dhl_send_actual_wt" style="height: 10px !important;"> </label>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group row">
                                        <label for="dhl_headoffice_distance" title="<?php echo isset($setting_comment['dhl_headoffice_distance']) ? $setting_comment['dhl_headoffice_distance'] : ''; ?>" class="col-sm-6 col-form-label">Distance of DHL HeadOffice</label>
                                        <div class="col-sm-6">
                                            <?php if (isset($mode) && $mode == "add_comments") { ?>
                                                <textarea name="label_api[dhl_headoffice_distance]" id="" cols="80" rows="1"><?php echo isset($setting['dhl_headoffice_distance']) ? $setting['dhl_headoffice_distance'] : ''; ?></textarea>
                                            <?php } else { ?>
                                                <input type="text" class="form-control" name="label_api[dhl_headoffice_distance]" value="<?php echo isset($setting['dhl_headoffice_distance']) ? $setting['dhl_headoffice_distance'] : ''; ?>" />
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group row">
                                        <label for="cust_id" title="<?php echo isset($setting_comment['send_pdx_for_dox_and_ppx_for_nondox_product_in_aramex_api']) ? $setting_comment['send_pdx_for_dox_and_ppx_for_nondox_product_in_aramex_api'] : ''; ?>" class="col-sm-6 col-form-label">Send PDX For Dox And PPX For Nondox Product In Aramex API</label>
                                        <div class="col-sm-6">
                                            <div class="checkbox">
                                                <?php if (isset($mode) && $mode == "add_comments") { ?>
                                                    <textarea name="label_api[send_pdx_for_dox_and_ppx_for_nondox_product_in_aramex_api]" id="" cols="80" rows="1"><?php echo isset($setting['send_pdx_for_dox_and_ppx_for_nondox_product_in_aramex_api']) ? $setting['send_pdx_for_dox_and_ppx_for_nondox_product_in_aramex_api'] : ''; ?></textarea>
                                                <?php } else { ?>
                                                    <input type="checkbox" id="send_pdx_for_dox_and_ppx_for_nondox_product_in_aramex_api" value="1" autocomplete="nope" name="label_api[send_pdx_for_dox_and_ppx_for_nondox_product_in_aramex_api]" <?php echo isset($setting['send_pdx_for_dox_and_ppx_for_nondox_product_in_aramex_api']) && $setting['send_pdx_for_dox_and_ppx_for_nondox_product_in_aramex_api'] == 1 ? 'checked' : ''; ?>>
                                                    <label for="send_pdx_for_dox_and_ppx_for_nondox_product_in_aramex_api" style="height: 10px !important;"> </label>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group row">
                                        <label for="cust_id" title="<?php echo isset($setting_comment['dtdc_intl_service_selection_for_mys']) ? $setting_comment['dtdc_intl_service_selection_for_mys'] : ''; ?>" class="col-sm-6 col-form-label">DTDC International Service Selection For MYS</label>
                                        <div class="col-sm-6">
                                            <div class="checkbox">
                                                <?php if (isset($mode) && $mode == "add_comments") { ?>
                                                    <textarea name="label_api[dtdc_intl_service_selection_for_mys]" id="" cols="80" rows="1"><?php echo isset($setting['dtdc_intl_service_selection_for_mys']) ? $setting['dtdc_intl_service_selection_for_mys'] : ''; ?></textarea>
                                                <?php } else { ?>
                                                    <input type="checkbox" id="dtdc_intl_service_selection_for_mys" value="1" autocomplete="nope" name="label_api[dtdc_intl_service_selection_for_mys]" <?php echo isset($setting['dtdc_intl_service_selection_for_mys']) && $setting['dtdc_intl_service_selection_for_mys'] == 1 ? 'checked' : ''; ?>>
                                                    <label for="dtdc_intl_service_selection_for_mys" style="height: 10px !important;"> </label>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group row">
                                        <label for="cust_id" title="<?php echo isset($setting_comment['send_dtdc_intl_sender_details_from_credentials']) ? $setting_comment['send_dtdc_intl_sender_details_from_credentials'] : ''; ?>" class="col-sm-6 col-form-label">Send DTDC International API Sender Details From Credentials</label>
                                        <div class="col-sm-6">
                                            <div class="checkbox">
                                                <?php if (isset($mode) && $mode == "add_comments") { ?>
                                                    <textarea name="label_api[send_dtdc_intl_sender_details_from_credentials]" id="" cols="80" rows="1"><?php echo isset($setting['send_dtdc_intl_sender_details_from_credentials']) ? $setting['send_dtdc_intl_sender_details_from_credentials'] : ''; ?></textarea>
                                                <?php } else { ?>
                                                    <input type="checkbox" id="send_dtdc_intl_sender_details_from_credentials" value="1" autocomplete="nope" name="label_api[send_dtdc_intl_sender_details_from_credentials]" <?php echo isset($setting['send_dtdc_intl_sender_details_from_credentials']) && $setting['send_dtdc_intl_sender_details_from_credentials'] == 1 ? 'checked' : ''; ?>>
                                                    <label for="send_dtdc_intl_sender_details_from_credentials" style="height: 10px !important;"> </label>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group row">
                                        <label for="dpd_shipping_reference_prefix" title="<?php echo isset($setting_comment['dpd_shipping_reference_prefix']) ? $setting_comment['dpd_shipping_reference_prefix'] : ''; ?>" class="col-sm-6 col-form-label">DPD Shipping Reference Prefix</label>
                                        <div class="col-sm-6">
                                            <?php if (isset($mode) && $mode == "add_comments") { ?>
                                                <textarea name="label_api[dpd_shipping_reference_prefix]" id="" cols="80" rows="1"><?php echo isset($setting['dpd_shipping_reference_prefix']) ? $setting['dpd_shipping_reference_prefix'] : ''; ?></textarea>
                                            <?php } else { ?>
                                                <input type="text" class="form-control" name="label_api[dpd_shipping_reference_prefix]" value="<?php echo isset($setting['dpd_shipping_reference_prefix']) ? $setting['dpd_shipping_reference_prefix'] : ''; ?>" />
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group row">
                                        <label for="cust_id" title="<?php echo isset($setting_comment['send_parcel_info_in_dpd_label_api_from_free_form_invoice']) ? $setting_comment['send_parcel_info_in_dpd_label_api_from_free_form_invoice'] : ''; ?>" class="col-sm-6 col-form-label">Send Parcel Info In DPD Label API From Free Form Invoice</label>
                                        <div class="col-sm-6">
                                            <div class="checkbox">
                                                <?php if (isset($mode) && $mode == "add_comments") { ?>
                                                    <textarea name="label_api[send_parcel_info_in_dpd_label_api_from_free_form_invoice]" id="" cols="80" rows="1"><?php echo isset($setting['send_parcel_info_in_dpd_label_api_from_free_form_invoice']) ? $setting['send_parcel_info_in_dpd_label_api_from_free_form_invoice'] : ''; ?></textarea>
                                                <?php } else { ?>
                                                    <input type="checkbox" id="send_parcel_info_in_dpd_label_api_from_free_form_invoice" value="1" autocomplete="nope" name="label_api[send_parcel_info_in_dpd_label_api_from_free_form_invoice]" <?php echo isset($setting['send_parcel_info_in_dpd_label_api_from_free_form_invoice']) && $setting['send_parcel_info_in_dpd_label_api_from_free_form_invoice'] == 1 ? 'checked' : ''; ?>>
                                                    <label for="send_parcel_info_in_dpd_label_api_from_free_form_invoice" style="height: 10px !important;"> </label>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group row">
                                        <label for="cust_id" title="<?php echo isset($setting_comment['send_dpd_service_by_destination_code']) ? $setting_comment['send_dpd_service_by_destination_code'] : ''; ?>" class="col-sm-6 col-form-label">Send DPD Service By Destination Code</label>
                                        <div class="col-sm-6">
                                            <div class="checkbox">
                                                <?php if (isset($mode) && $mode == "add_comments") { ?>
                                                    <textarea name="label_api[send_dpd_service_by_destination_code]" id="" cols="80" rows="1"><?php echo isset($setting['send_dpd_service_by_destination_code']) ? $setting['send_dpd_service_by_destination_code'] : ''; ?></textarea>
                                                <?php } else { ?>
                                                    <input type="checkbox" id="send_dpd_service_by_destination_code" value="1" autocomplete="nope" name="label_api[send_dpd_service_by_destination_code]" <?php echo isset($setting['send_dpd_service_by_destination_code']) && $setting['send_dpd_service_by_destination_code'] == 1 ? 'checked' : ''; ?>>
                                                    <label for="send_dpd_service_by_destination_code" style="height: 10px !important;"> </label>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group row">
                                        <label for="cust_id" title="<?php echo isset($setting_comment['send_foreign_awb_and_reference_1_in_aramex_label_api']) ? $setting_comment['send_foreign_awb_and_reference_1_in_aramex_label_api'] : ''; ?>" class="col-sm-6 col-form-label">Send Foreign HAWB And Reference 1 In Aramex Label API</label>
                                        <div class="col-sm-6">
                                            <div class="checkbox">
                                                <?php if (isset($mode) && $mode == "add_comments") { ?>
                                                    <textarea name="label_api[send_foreign_awb_and_reference_1_in_aramex_label_api]" id="" cols="80" rows="1"><?php echo isset($setting['send_foreign_awb_and_reference_1_in_aramex_label_api']) ? $setting['send_foreign_awb_and_reference_1_in_aramex_label_api'] : ''; ?></textarea>
                                                <?php } else { ?>
                                                    <input type="checkbox" id="send_foreign_awb_and_reference_1_in_aramex_label_api" value="1" autocomplete="nope" name="label_api[send_foreign_awb_and_reference_1_in_aramex_label_api]" <?php echo isset($setting['send_foreign_awb_and_reference_1_in_aramex_label_api']) && $setting['send_foreign_awb_and_reference_1_in_aramex_label_api'] == 1 ? 'checked' : ''; ?>>
                                                    <label for="send_foreign_awb_and_reference_1_in_aramex_label_api" style="height: 10px !important;"> </label>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group row">
                                        <label for="cust_id" title="<?php echo isset($setting_comment['send_shipper_account_number_from_customer_master_for_ups_api']) ? $setting_comment['send_shipper_account_number_from_customer_master_for_ups_api'] : ''; ?>" class="col-sm-6 col-form-label">Send Shipper Account Number From Customer Master For UPS API</label>
                                        <div class="col-sm-6">
                                            <div class="checkbox">
                                                <?php if (isset($mode) && $mode == "add_comments") { ?>
                                                    <textarea name="label_api[send_shipper_account_number_from_customer_master_for_ups_api]" id="" cols="80" rows="1"><?php echo isset($setting['send_shipper_account_number_from_customer_master_for_ups_api']) ? $setting['send_shipper_account_number_from_customer_master_for_ups_api'] : ''; ?></textarea>
                                                <?php } else { ?>
                                                    <input type="checkbox" id="send_shipper_account_number_from_customer_master_for_ups_api" value="1" autocomplete="nope" name="label_api[send_shipper_account_number_from_customer_master_for_ups_api]" <?php echo isset($setting['send_shipper_account_number_from_customer_master_for_ups_api']) && $setting['send_shipper_account_number_from_customer_master_for_ups_api'] == 1 ? 'checked' : ''; ?>>
                                                    <label for="send_shipper_account_number_from_customer_master_for_ups_api" style="height: 10px !important;"> </label>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group row">
                                        <label for="cust_id" title="<?php echo isset($setting_comment['send_shipper_account_number_from_entry_page_for_ups_api']) ? $setting_comment['send_shipper_account_number_from_entry_page_for_ups_api'] : ''; ?>" class="col-sm-6 col-form-label">Send Shipper Account Number From Entry Page For UPS API</label>
                                        <div class="col-sm-6">
                                            <div class="checkbox">
                                                <?php if (isset($mode) && $mode == "add_comments") { ?>
                                                    <textarea name="label_api[send_shipper_account_number_from_entry_page_for_ups_api]" id="" cols="80" rows="1"><?php echo isset($setting['send_shipper_account_number_from_entry_page_for_ups_api']) ? $setting['send_shipper_account_number_from_entry_page_for_ups_api'] : ''; ?></textarea>
                                                <?php } else { ?>
                                                    <input type="checkbox" id="send_shipper_account_number_from_entry_page_for_ups_api" value="1" autocomplete="nope" name="label_api[send_shipper_account_number_from_entry_page_for_ups_api]" <?php echo isset($setting['send_shipper_account_number_from_entry_page_for_ups_api']) && $setting['send_shipper_account_number_from_entry_page_for_ups_api'] == 1 ? 'checked' : ''; ?>>
                                                    <label for="send_shipper_account_number_from_entry_page_for_ups_api" style="height: 10px !important;"> </label>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group row">
                                        <label for="send_default_country_of_origin_for_ups_api" title="<?php echo isset($setting_comment['send_default_country_of_origin_for_ups_api']) ? $setting_comment['send_default_country_of_origin_for_ups_api'] : ''; ?>" class="col-sm-6 col-form-label">Send Default Country Of Origin For UPS API</label>
                                        <div class="col-sm-6">
                                            <?php if (isset($mode) && $mode == "add_comments") { ?>
                                                <textarea name="label_api[send_default_country_of_origin_for_ups_api]" id="" cols="80" rows="1"><?php echo isset($setting['send_default_country_of_origin_for_ups_api']) ? $setting['send_default_country_of_origin_for_ups_api'] : ''; ?></textarea>
                                            <?php } else { ?>
                                                <input type="text" class="form-control" name="label_api[send_default_country_of_origin_for_ups_api]" value="<?php echo isset($setting['send_default_country_of_origin_for_ups_api']) ? $setting['send_default_country_of_origin_for_ups_api'] : ''; ?>" />
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group row">
                                        <label for="ups_domestic_service_country_code" title="<?php echo isset($setting_comment['ups_domestic_service_country_code']) ? $setting_comment['ups_domestic_service_country_code'] : ''; ?>" class="col-sm-6 col-form-label">UPS Domestic Service Country Code</label>
                                        <div class="col-sm-6">
                                            <?php if (isset($mode) && $mode == "add_comments") { ?>
                                                <textarea name="label_api[ups_domestic_service_country_code]" id="" cols="80" rows="1"><?php echo isset($setting['ups_domestic_service_country_code']) ? $setting['ups_domestic_service_country_code'] : ''; ?></textarea>
                                            <?php } else { ?>
                                                <input type="text" class="form-control" name="label_api[ups_domestic_service_country_code]" value="<?php echo isset($setting['ups_domestic_service_country_code']) ? $setting['ups_domestic_service_country_code'] : ''; ?>" />
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group row">
                                        <label for="cust_id" title="<?php echo isset($setting_comment['send_shipper_info_from_service_master_for_bluedart_api']) ? $setting_comment['send_shipper_info_from_service_master_for_bluedart_api'] : ''; ?>" class="col-sm-6 col-form-label">Send Shipper Info From Service Master For Bluedart API</label>
                                        <div class="col-sm-6">
                                            <div class="checkbox">
                                                <?php if (isset($mode) && $mode == "add_comments") { ?>
                                                    <textarea name="label_api[send_shipper_info_from_service_master_for_bluedart_api]" id="" cols="80" rows="1"><?php echo isset($setting['send_shipper_info_from_service_master_for_bluedart_api']) ? $setting['send_shipper_info_from_service_master_for_bluedart_api'] : ''; ?></textarea>
                                                <?php } else { ?>
                                                    <input type="checkbox" id="send_shipper_info_from_service_master_for_bluedart_api" value="1" autocomplete="nope" name="label_api[send_shipper_info_from_service_master_for_bluedart_api]" <?php echo isset($setting['send_shipper_info_from_service_master_for_bluedart_api']) && $setting['send_shipper_info_from_service_master_for_bluedart_api'] == 1 ? 'checked' : ''; ?>>
                                                    <label for="send_shipper_info_from_service_master_for_bluedart_api" style="height: 10px !important;"> </label>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group row">
                                        <label for="cust_id" title="<?php echo isset($setting_comment['aramex_product_manual_select']) ? $setting_comment['aramex_product_manual_select'] : ''; ?>" class="col-sm-6 col-form-label">ARAMEX PDX and PPX AND GPX manual selection</label>
                                        <div class="col-sm-6">
                                            <div class="checkbox">
                                                <?php if (isset($mode) && $mode == "add_comments") { ?>
                                                    <textarea name="label_api[aramex_product_manual_select]" id="" cols="80" rows="1"><?php echo isset($setting['aramex_product_manual_select']) ? $setting['aramex_product_manual_select'] : ''; ?></textarea>
                                                <?php } else { ?>
                                                    <input type="checkbox" id="aramex_product_manual_select" value="1" autocomplete="nope" name="label_api[aramex_product_manual_select]" <?php echo isset($setting['aramex_product_manual_select']) && $setting['aramex_product_manual_select'] == 1 ? 'checked' : ''; ?>>
                                                    <label for="aramex_product_manual_select" style="height: 10px !important;"> </label>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-12">
                                    <div class="form-group row">
                                        <label for="cust_id" title="<?php echo isset($setting_comment['send_event_remark_in_tracking_api']) ? $setting_comment['send_event_remark_in_tracking_api'] : ''; ?>" class="col-sm-6 col-form-label">send event remark per line item in self tracking API</label>
                                        <div class="col-sm-6">
                                            <div class="checkbox">
                                                <?php if (isset($mode) && $mode == "add_comments") { ?>
                                                    <textarea name="tracking_api[send_event_remark_in_tracking_api]" id="" cols="80" rows="1"><?php echo isset($setting['send_event_remark_in_tracking_api']) ? $setting['send_event_remark_in_tracking_api'] : ''; ?></textarea>
                                                <?php } else { ?>
                                                    <input type="checkbox" id="send_event_remark_in_tracking_api" value="1" autocomplete="nope" name="tracking_api[send_event_remark_in_tracking_api]" <?php echo isset($setting['send_event_remark_in_tracking_api']) && $setting['send_event_remark_in_tracking_api'] == 1 ? 'checked' : ''; ?>>
                                                    <label for="send_event_remark_in_tracking_api" style="height: 10px !important;"> </label>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-12">
                                    <div class="form-group row">
                                        <label for="cust_id" title="<?php echo isset($setting_comment['enable_mig_payment']) ? $setting_comment['enable_mig_payment'] : ''; ?>" class="col-sm-6 col-form-label">Enable MIG Crypto payment</label>
                                        <div class="col-sm-6">
                                            <div class="checkbox">
                                                <?php if (isset($mode) && $mode == "add_comments") { ?>
                                                    <textarea name="api[enable_mig_payment]" id="" cols="80" rows="1"><?php echo isset($setting['enable_mig_payment']) ? $setting['enable_mig_payment'] : ''; ?></textarea>
                                                <?php } else { ?>
                                                    <input type="checkbox" id="enable_mig_payment" value="1" autocomplete="nope" name="api[enable_mig_payment]" <?php echo isset($setting['enable_mig_payment']) && $setting['enable_mig_payment'] == 1 ? 'checked' : ''; ?>>
                                                    <label for="enable_mig_payment" style="height: 10px !important;"> </label>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-12">
                                    <div class="form-group row">
                                        <label for="cust_id" class="col-sm-6 col-form-label">Enable Razorpay payment</label>
                                        <div class="col-sm-6">
                                            <div class="checkbox">

                                                <input type="checkbox" id="enable_razorpay_payment" value="1" autocomplete="nope" name="api[enable_razorpay_payment]" <?php echo isset($setting['enable_razorpay_payment']) && $setting['enable_razorpay_payment'] == 1 ? 'checked' : ''; ?>>
                                                <label for="enable_razorpay_payment" style="height: 10px !important;"> </label>

                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-12">
                                    <div class="form-group row">
                                        <label for="cust_id" title="<?php echo isset($setting_comment['enable_mig_payment']) ? $setting_comment['enable_mig_payment'] : ''; ?>" class="col-sm-6 col-form-label">Enable Cashfree payment</label>
                                        <div class="col-sm-6">
                                            <div class="checkbox">

                                                <input type="checkbox" id="enable_cashfree_payment" value="1" autocomplete="nope" name="api[enable_cashfree_payment]" <?php echo isset($setting['enable_cashfree_payment']) && $setting['enable_cashfree_payment'] == 1 ? 'checked' : ''; ?>>
                                                <label for="enable_cashfree_payment" style="height: 10px !important;"> </label>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group row">
                                        <label for="mig_payment_token" title="<?php echo isset($setting_comment['mig_payment_token']) ? $setting_comment['mig_payment_token'] : ''; ?>" class="col-sm-6 col-form-label">MIG Payment Token</label>
                                        <div class="col-sm-6">
                                            <?php if (isset($mode) && $mode == "add_comments") { ?>
                                                <textarea name="api[mig_payment_token]" id="" cols="80" rows="1"><?php echo isset($setting['mig_payment_token']) ? $setting['mig_payment_token'] : ''; ?></textarea>
                                            <?php } else { ?>
                                                <input type="text" class="form-control" name="api[mig_payment_token]" value="<?php echo isset($setting['mig_payment_token']) ? $setting['mig_payment_token'] : ''; ?>" />
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-12">
                                    <div class="form-group row">
                                        <label for="cust_id" title="<?php echo isset($setting_comment['ups_shipper_address_from_awb_entry']) ? $setting_comment['ups_shipper_address_from_awb_entry'] : ''; ?>" class="col-sm-6 col-form-label">ADDRESS 1 and ADDRESS 2 in UPS DXB label - send it from awb page and not from service</label>
                                        <div class="col-sm-6">
                                            <div class="checkbox">
                                                <?php if (isset($mode) && $mode == "add_comments") { ?>
                                                    <textarea name="label_api[ups_shipper_address_from_awb_entry]" id="" cols="80" rows="1"><?php echo isset($setting['ups_shipper_address_from_awb_entry']) ? $setting['ups_shipper_address_from_awb_entry'] : ''; ?></textarea>
                                                <?php } else { ?>
                                                    <input type="checkbox" id="ups_shipper_address_from_awb_entry" value="1" autocomplete="nope" name="label_api[ups_shipper_address_from_awb_entry]" <?php echo isset($setting['ups_shipper_address_from_awb_entry']) && $setting['ups_shipper_address_from_awb_entry'] == 1 ? 'checked' : ''; ?>>
                                                    <label for="ups_shipper_address_from_awb_entry" style="height: 10px !important;"> </label>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group row">
                                        <label for="cust_id" title="<?php echo isset($setting_comment['send_todays_date_as_booking_date']) ? $setting_comment['send_todays_date_as_booking_date'] : ''; ?>" class="col-sm-6 col-form-label">SEND TODAY'S DATE AS BOOKING DATE IN INTERANAL API</label>
                                        <div class="col-sm-6">
                                            <div class="checkbox">
                                                <?php if (isset($mode) && $mode == "add_comments") { ?>
                                                    <textarea name="label_api[send_todays_date_as_booking_date]" id="" cols="80" rows="1"><?php echo isset($setting['send_todays_date_as_booking_date']) ? $setting['send_todays_date_as_booking_date'] : ''; ?></textarea>
                                                <?php } else { ?>
                                                    <input type="checkbox" id="send_todays_date_as_booking_date" value="1" autocomplete="nope" name="label_api[send_todays_date_as_booking_date]" <?php echo isset($setting['send_todays_date_as_booking_date']) && $setting['send_todays_date_as_booking_date'] == 1 ? 'checked' : ''; ?>>
                                                    <label for="send_todays_date_as_booking_date" style="height: 10px !important;"> </label>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-12">
                                    <div class="form-group row">
                                        <label for="cust_id" title="<?php echo isset($setting_comment['j_zone_send_for_71']) ? $setting_comment['j_zone_send_for_71'] : ''; ?>" class="col-sm-6 col-form-label">J zone Send 70.5 account for 71+</label>
                                        <div class="col-sm-6">
                                            <div class="checkbox">
                                                <?php if (isset($mode) && $mode == "add_comments") { ?>
                                                    <textarea name="label_api[j_zone_send_for_71]" id="" cols="80" rows="1"><?php echo isset($setting['j_zone_send_for_71']) ? $setting['j_zone_send_for_71'] : ''; ?></textarea>
                                                <?php } else { ?>
                                                    <input type="checkbox" id="j_zone_send_for_71" value="1" autocomplete="nope" name="label_api[j_zone_send_for_71]" <?php echo isset($setting['j_zone_send_for_71']) && $setting['j_zone_send_for_71'] == 1 ? 'checked' : ''; ?>>
                                                    <label for="j_zone_send_for_71" style="height: 10px !important;"> </label>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-12">
                                    <div class="form-group row">
                                        <label class="col-sm-6 col-form-label">Show Rate API in customer portal</label>
                                        <div class="col-sm-6">
                                            <div class="checkbox">
                                                <input type="checkbox" id="rate_api_portal_doc" value="1" autocomplete="nope" name="portal_doc[rate_api_portal_doc]" <?php echo isset($setting['rate_api_portal_doc']) && $setting['rate_api_portal_doc'] == 1 ? 'checked' : ''; ?>>
                                                <label for="rate_api_portal_doc" style="height: 10px !important;"> </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group row">
                                        <label class="col-sm-6 col-form-label">Show TRACKING API in customer portal</label>
                                        <div class="col-sm-6">
                                            <div class="checkbox">
                                                <input type="checkbox" id="tracking_api_portal_doc" value="1" autocomplete="nope" name="portal_doc[tracking_api_portal_doc]" <?php echo isset($setting['tracking_api_portal_doc']) && $setting['tracking_api_portal_doc'] == 1 ? 'checked' : ''; ?>>
                                                <label for="tracking_api_portal_doc" style="height: 10px !important;"> </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group row">
                                        <label class="col-sm-6 col-form-label">Show LABEL API in customer portal</label>
                                        <div class="col-sm-6">
                                            <div class="checkbox">
                                                <input type="checkbox" id="label_api_portal_doc" value="1" autocomplete="nope" name="portal_doc[label_api_portal_doc]" <?php echo isset($setting['label_api_portal_doc']) && $setting['label_api_portal_doc'] == 1 ? 'checked' : ''; ?>>
                                                <label for="label_api_portal_doc" style="height: 10px !important;"> </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group row">
                                        <label for="cust_id" title="<?php echo isset($setting_comment['enable_customize_blue_dart_label_bhavani']) ? $setting_comment['enable_customize_blue_dart_label_bhavani'] : ''; ?>" class="col-sm-6 col-form-label">ENABLE CUSTOMIZE BLUE DART LABEL BHAVANI</label>
                                        <div class="col-sm-6">
                                            <div class="checkbox">
                                                <?php if (isset($mode) && $mode == "add_comments") { ?>
                                                    <textarea name="label_api[enable_customize_blue_dart_label_bhavani]" id="" cols="80" rows="1"><?php echo isset($setting['enable_customize_blue_dart_label_bhavani']) ? $setting['enable_customize_blue_dart_label_bhavani'] : ''; ?></textarea>
                                                <?php } else { ?>
                                                    <input type="checkbox" id="enable_customize_blue_dart_label_bhavani" value="1" autocomplete="nope" name="label_api[enable_customize_blue_dart_label_bhavani]" <?php echo isset($setting['enable_customize_blue_dart_label_bhavani']) && $setting['enable_customize_blue_dart_label_bhavani'] == 1 ? 'checked' : ''; ?>>
                                                    <label for="enable_customize_blue_dart_label_bhavani" style="height: 10px !important;"> </label>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-12">
                                    <div class="form-group row">
                                        <label for="cust_id" class="col-sm-6 col-form-label">Free Form mandatory for FEDEX label head</label>
                                        <div class="col-sm-6">
                                            <div class="checkbox">
                                                <input type="checkbox" id="mandatory_free_form_for_fedex" value="1" autocomplete="nope" name="label_api[mandatory_free_form_for_fedex]" <?php echo isset($setting['mandatory_free_form_for_fedex']) && $setting['mandatory_free_form_for_fedex'] == 1 ? 'checked' : ''; ?>>
                                                <label for="mandatory_free_form_for_fedex" style="height: 10px !important;"> </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group row">
                                        <label for="cust_id" title="<?php echo isset($setting_comment['send_aramex_service_from_api_credentials']) ? $setting_comment['send_aramex_service_from_api_credentials'] : ''; ?>" class="col-sm-6 col-form-label">Send Aramex Service from API credentails</label>
                                        <div class="col-sm-6">
                                            <div class="checkbox">
                                                <?php if (isset($mode) && $mode == "add_comments") { ?>
                                                    <textarea name="label_api[send_aramex_service_from_api_credentials]" id="" cols="80" rows="1"><?php echo isset($setting['send_aramex_service_from_api_credentials']) ? $setting['send_aramex_service_from_api_credentials'] : ''; ?></textarea>
                                                <?php } else { ?>
                                                    <input type="checkbox" id="send_aramex_service_from_api_credentials" value="1" autocomplete="nope" name="label_api[send_aramex_service_from_api_credentials]" <?php echo isset($setting['send_aramex_service_from_api_credentials']) && $setting['send_aramex_service_from_api_credentials'] == 1 ? 'checked' : ''; ?>>
                                                    <label for="send_aramex_service_from_api_credentials" style="height: 10px !important;"> </label>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group row">
                                        <label for="cust_id" title="<?php echo isset($setting_comment['fetch_services_from_api_credentials_dpd']) ? $setting_comment['fetch_services_from_api_credentials_dpd'] : ''; ?>" class="col-sm-6 col-form-label">FETCH SERVICES FROM API CREDENTIALS DPD</label>
                                        <div class="col-sm-6">
                                            <div class="checkbox">
                                                <?php if (isset($mode) && $mode == "add_comments") { ?>
                                                    <textarea name="label_api[fetch_services_from_api_credentials_dpd]" id="" cols="80" rows="1"><?php echo isset($setting['fetch_services_from_api_credentials_dpd']) ? $setting['fetch_services_from_api_credentials_dpd'] : ''; ?></textarea>
                                                <?php } else { ?>
                                                    <input type="checkbox" id="fetch_services_from_api_credentials_dpd" value="1" autocomplete="nope" name="label_api[fetch_services_from_api_credentials_dpd]" <?php echo isset($setting['fetch_services_from_api_credentials_dpd']) && $setting['fetch_services_from_api_credentials_dpd'] == 1 ? 'checked' : ''; ?>>
                                                    <label for="fetch_services_from_api_credentials_dpd" style="height: 10px !important;"> </label>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group row">
                                        <label for="cust_id" title="<?php echo isset($setting_comment['dont_check_shipper_kyc_in_api']) ? $setting_comment['dont_check_shipper_kyc_in_api'] : ''; ?>" class="col-sm-6 col-form-label">DONT CHECK SHIPPER KYC DOC IN API</label>
                                        <div class="col-sm-6">
                                            <div class="checkbox">
                                                <?php if (isset($mode) && $mode == "add_comments") { ?>
                                                    <textarea name="label_api[dont_check_shipper_kyc_in_api]" id="" cols="80" rows="1"><?php echo isset($setting['dont_check_shipper_kyc_in_api']) ? $setting['dont_check_shipper_kyc_in_api'] : ''; ?></textarea>
                                                <?php } else { ?>
                                                    <input type="checkbox" id="dont_check_shipper_kyc_in_api" value="1" autocomplete="nope" name="label_api[dont_check_shipper_kyc_in_api]" <?php echo isset($setting['dont_check_shipper_kyc_in_api']) && $setting['dont_check_shipper_kyc_in_api'] == 1 ? 'checked' : ''; ?>>
                                                    <label for="dont_check_shipper_kyc_in_api" style="height: 10px !important;"> </label>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group row">
                                        <label for="cust_id" title="<?php echo isset($setting_comment['hardcode_value_dpd_label_dest_not_ie_or_gb']) ? $setting_comment['hardcode_value_dpd_label_dest_not_ie_or_gb'] : ''; ?>" class="col-sm-6 col-form-label">HARD CODE VALUE IN DPD LABEL WHEN DESTINATION NOT IE OR GB</label>
                                        <div class="col-sm-6">
                                            <div class="checkbox">
                                                <?php if (isset($mode) && $mode == "add_comments") { ?>
                                                    <textarea name="label_api[hardcode_value_dpd_label_dest_not_ie_or_gb]" id="" cols="80" rows="1"><?php echo isset($setting['hardcode_value_dpd_label_dest_not_ie_or_gb']) ? $setting['hardcode_value_dpd_label_dest_not_ie_or_gb'] : ''; ?></textarea>
                                                <?php } else { ?>
                                                    <input type="checkbox" id="hardcode_value_dpd_label_dest_not_ie_or_gb" value="1" autocomplete="nope" name="label_api[hardcode_value_dpd_label_dest_not_ie_or_gb]" <?php echo isset($setting['hardcode_value_dpd_label_dest_not_ie_or_gb']) && $setting['hardcode_value_dpd_label_dest_not_ie_or_gb'] == 1 ? 'checked' : ''; ?>>
                                                    <label for="hardcode_value_dpd_label_dest_not_ie_or_gb" style="height: 10px !important;"> </label>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>



                            <?php $this->load->view('inventory_setting'); ?>
                            <?php $this->load->view('invoice_setting'); ?>
                            <?php $this->load->view('drs_setting'); ?>
                            <?php $this->load->view('pdf_setting'); ?>
                            <?php $this->load->view('mobile_app_setting'); ?>

                            <?php if ($session_data["email"] == "virag@itdservices.in") { ?>
                                <div class="col-12 mt-3">
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <?php if (isset($mode) && $mode == "add_comments") { ?>
                                                <button type="submit" class="btn btn-primary pull-right">SAVE COMMENTS</button>
                                            <?php } else { ?>
                                                <button type="submit" class="btn btn-primary pull-right">Save</button>
                                            <?php } ?>
                                            <?php if (isset($mode) && $mode != 'update') { ?>
                                                <a class="pull-right btn btn-secondary text-white" onclick="history.back()" href="javascript:void(0);">BACK</a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
<script>
    function searchSetting(ActiveTab) {
        var serachTab = [];
        var value = $("#setting_search").val().toLowerCase();

        if (value == '') {
            $("#setting_div .col-form-label").filter(function() {
                $(this).parent().parent().find("*").show();
                $('.nav-link').removeClass('active');
                $('.tab-pane').removeClass('active');
                $('.nav-link').removeClass('serachTab');
                //FISRT TAB NAME
                var firstTab = "general-tab";
                $('a[href$="#' + firstTab + '"]').addClass('active');
                $("#" + firstTab).addClass('active');
            });
        } else {
            $("#setting_div .col-form-label").filter(function() {
                if ($(this).text().toLowerCase().indexOf(value) > -1) {

                    $(this).parent().parent().find("*").show();
                    //GET TAB ID
                    var TabID = $(this).parent().parent().parent().attr('id');
                    serachTab.push(TabID);
                } else {
                    $(this).parent().parent().find("*").hide();
                }
            });

            $('.nav-link').removeClass('active');
            $('.tab-pane').removeClass('active');
            $('.nav-link').removeClass('serachTab');
            if (serachTab.length > 0) {
                var serachTabUnique = serachTab.filter(function(itm, i, serachTab) {
                    return i == serachTab.indexOf(itm);
                });

                console.log(serachTabUnique);
                for (i = 0; i < serachTabUnique.length; ++i) {
                    var activeTabID = serachTabUnique[i];
                    if (ActiveTab != '') {
                        if (ActiveTab == '#' + activeTabID) {
                            $('a[href$="#' + activeTabID + '"]').addClass('active');
                            $("#" + activeTabID).addClass('active');
                        } else {
                            $('a[href$="#' + activeTabID + '"]').addClass('serachTab');
                        }
                    } else {
                        if (i == 0) {
                            $('a[href$="#' + activeTabID + '"]').addClass('active');
                            $("#" + activeTabID).addClass('active');
                        } else {
                            $('a[href$="#' + activeTabID + '"]').addClass('serachTab');
                        }
                    }


                }
            }
        }

    }

    function show_setting_definition(setting_id) {
        var setting_name = $("div").find("[data-id='" + setting_id + "']").children("label").text();

        $.ajax({
            type: "POST",
            url: '<?php echo site_url('settings/get_settings_definition/') ?>' + setting_id,
            data: '',
            success: function(serviceData) {
                var returnedData = JSON.parse(serviceData);
                if (returnedData['error'] != undefined) {
                    alert(returnedData['error']);
                    $("#definition_modal").modal('hide');
                } else {
                    $("#modal_setting_id").val(returnedData['id']);
                    $("#modal_setting_name").text(setting_name);
                    $("#modal_setting_definition").text(returnedData['setting_definition']);
                    $("#definition_modal").modal('show');
                }


            }
        });
    }

    function save_definition() {
        $("#definition_modal").modal('hide');
        $.ajax({
            type: "POST",
            url: '<?php echo site_url('settings/save_definition/') ?>',
            data: {
                'setting_id': $("#modal_setting_id").val(),
                'definition': $("#modal_setting_definition").val(),
            },
            success: function(serviceData) {
                var returnedData = JSON.parse(serviceData);
                if (returnedData['error'] != undefined) {
                    $(".success_msg").html('').hide();
                    $(".error_msg").html(returnedData['error']).show();
                } else {
                    $(".error_msg").html('').hide();
                    $(".success_msg").html(returnedData['success']).show();
                }

            }
        });

    }
    $(document).ready(function() {
        $("#setting_search").on("keyup", function() {
            searchSetting('');
        });
        <?php if (in_array(strtolower($session_data["email"]), $get_all_email) && $session_data['email'] != "virag@itdservices.in") { ?>
            $("#setting_form :input").prop("disabled", true);
            $("#setting_search").prop("disabled", false);
        <?php } ?>

        $(".nav-link").on("click", function() {
            $(this).removeClass('serachTab');
            var ActiveTab = $(this).attr('href');
            searchSetting(ActiveTab);
        });



        // $('.setting_data').each(function() {
        //     var setting_id = $(this).attr('data-id');
        //     if (setting_id != undefined && setting_id > 0) {
        //         var definition_append = '<div class="col-sm-2">';
        //         definition_append += '<a onclick="show_setting_definition(' + setting_id + ')"><i class="fa fa-info-circle setting_info" title="CLICK HERE TO VIEW SETTING DEFINITION">';
        //         definition_append += '</i></a>';
        //         definition_append += '</div>';
        //         $(this).append(definition_append);
        //     }
        // });



    });
</script>