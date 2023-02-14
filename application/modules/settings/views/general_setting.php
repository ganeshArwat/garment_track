<div class="tab-pane active show" id="general-tab">
    <div class="col-12">
        <div class="form-group row setting_data" data-id="<?php echo isset($setting_id['app_name']) ? $setting_id['app_name'] : 0; ?>">
            <label for="cust_id" title="<?php echo isset($setting_comment['app_name']) ? $setting_comment['app_name'] : ''; ?>" class="col-sm-4 col-form-label">App Name</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="general[app_name]" id="" cols="80" rows="1"><?php echo isset($setting['app_name']) ? $setting['app_name'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="text" class="form-control" name="general[app_name]" value="<?php echo isset($setting['app_name']) ? $setting['app_name'] : ''; ?>" />
                    <?php } ?>
                </div>

            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['client_settings_only_visible_to_emails']) ? $setting_comment['client_settings_only_visible_to_emails'] : ''; ?>" class="col-sm-4 col-form-label">Client Settings Only Visible To Emails</label>
            <div class="col-sm-6 setting_data">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="general[client_settings_only_visible_to_emails]" id="" cols="80" rows="1"><?php echo isset($setting['client_settings_only_visible_to_emails']) ? $setting['client_settings_only_visible_to_emails'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="text" class="form-control" name="general[client_settings_only_visible_to_emails]" value="<?php echo isset($setting['client_settings_only_visible_to_emails']) ? $setting['client_settings_only_visible_to_emails'] : ''; ?>" />
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['display_logo_of_which_company']) ? $setting_comment['display_logo_of_which_company'] : ''; ?>" class="col-sm-4 col-form-label">Show display logo of which company in software and portal</label>
            <div class="col-sm-6 setting_data">
                <?php if (isset($mode) && $mode == "add_comments") { ?>
                    <textarea name="general[display_logo_of_which_company]" id="" cols="80" rows="1"><?php echo isset($setting['display_logo_of_which_company']) ? $setting['display_logo_of_which_company'] : ''; ?></textarea>
                <?php } else { ?>
                    <select name="general[display_logo_of_which_company]">
                        <?php
                        if (isset($all_company) && is_array($all_company) && count($all_company) > 0) {
                            foreach ($all_company as $ckey => $cvalue) { ?>
                                <option value="<?php echo $cvalue['id']; ?>" <?php echo isset($setting['display_logo_of_which_company']) && $setting['display_logo_of_which_company'] == $cvalue['id'] ? 'selected' : ''; ?>><?php echo $cvalue['name']; ?></option>
                        <?php  }
                        }
                        ?>

                    </select>
                <?php } ?>

            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['whatsapp_sms_api_key']) ? $setting_comment['whatsapp_sms_api_key'] : ''; ?>" class="col-sm-4 col-form-label">WHATSAPP SMS API Key</label>
            <div class="col-sm-6 setting_data">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="general[whatsapp_sms_api_key]" id="" cols="80" rows="1"><?php echo isset($setting['whatsapp_sms_api_key']) ? $setting['whatsapp_sms_api_key'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="text" class="form-control" name="general[whatsapp_sms_api_key]" value="<?php echo isset($setting['whatsapp_sms_api_key']) ? $setting['whatsapp_sms_api_key'] : ''; ?>" />
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['whatsapp_sms_instance_id']) ? $setting_comment['whatsapp_sms_instance_id'] : ''; ?>" class="col-sm-4 col-form-label">WHATSAPP SMS Instance ID</label>
            <div class="col-sm-6 setting_data">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="general[whatsapp_sms_instance_id]" id="" cols="80" rows="1"><?php echo isset($setting['whatsapp_sms_instance_id']) ? $setting['whatsapp_sms_instance_id'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="text" class="form-control" name="general[whatsapp_sms_instance_id]" value="<?php echo isset($setting['whatsapp_sms_instance_id']) ? $setting['whatsapp_sms_instance_id'] : ''; ?>" />
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['whatsapp_sms_access_token']) ? $setting_comment['whatsapp_sms_access_token'] : ''; ?>" class="col-sm-4 col-form-label">WHATSAPP SMS ACCESS TOKEN</label>
            <div class="col-sm-6 setting_data">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="general[whatsapp_sms_access_token]" id="" cols="80" rows="1"><?php echo isset($setting['whatsapp_sms_access_token']) ? $setting['whatsapp_sms_access_token'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="text" class="form-control" name="general[whatsapp_sms_access_token]" value="<?php echo isset($setting['whatsapp_sms_access_token']) ? $setting['whatsapp_sms_access_token'] : ''; ?>" />
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['inquiry_cc_email']) ? $setting_comment['inquiry_cc_email'] : ''; ?>" class="col-sm-4 col-form-label">Inquiry CC Email</label>
            <div class="col-sm-6 setting_data">
                <?php if (isset($mode) && $mode == "add_comments") { ?>
                    <textarea name="general[inquiry_cc_email]" id="" cols="80" rows="1"><?php echo isset($setting['inquiry_cc_email']) ? $setting['inquiry_cc_email'] : ''; ?></textarea>
                <?php } else { ?>
                    <input type="text" class="form-control" name="general[inquiry_cc_email]" value="<?php echo isset($setting['inquiry_cc_email']) ? $setting['inquiry_cc_email'] : ''; ?>" />
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['send_total_oustanding_report']) ? $setting_comment['send_total_oustanding_report'] : ''; ?>" class="col-sm-4 col-form-label">Send total outstanding report</label>
            <div class="col-sm-6 setting_data">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="general[send_total_oustanding_report]" id="" cols="80" rows="1"><?php echo isset($setting['send_total_oustanding_report']) ? $setting['send_total_oustanding_report'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="send_total_oustanding_report" value="1" autocomplete="nope" name="general[send_total_oustanding_report]" <?php echo isset($setting['send_total_oustanding_report']) && $setting['send_total_oustanding_report'] == 1 ? 'checked' : ''; ?>>
                        <label for="send_total_oustanding_report" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['total_outstanding_report_email']) ? $setting_comment['total_outstanding_report_email'] : ''; ?>" class="col-sm-4 col-form-label">Total Outstanding Report Emails</label>
            <div class="col-sm-6 setting_data">
                <?php if (isset($mode) && $mode == "add_comments") { ?>
                    <textarea name="general[total_outstanding_report_email]" id="" cols="80" rows="1"><?php echo isset($setting['total_outstanding_report_email']) ? $setting['total_outstanding_report_email'] : ''; ?></textarea>
                <?php } else { ?>
                    <input type="text" class="form-control" name="general[total_outstanding_report_email]" value="<?php echo isset($setting['total_outstanding_report_email']) ? $setting['total_outstanding_report_email'] : ''; ?>" />
                <?php } ?>
            </div>
        </div>
    </div>


    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['send_deleted_awb_report']) ? $setting_comment['send_deleted_awb_report'] : ''; ?>" class="col-sm-4 col-form-label">Send Deleted,reprint and wt change report</label>
            <div class="col-sm-6 setting_data">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="general[send_deleted_awb_report]" id="" cols="80" rows="1"><?php echo isset($setting['send_deleted_awb_report']) ? $setting['send_deleted_awb_report'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="send_deleted_awb_report" value="1" autocomplete="nope" name="general[send_deleted_awb_report]" <?php echo isset($setting['send_deleted_awb_report']) && $setting['send_deleted_awb_report'] == 1 ? 'checked' : ''; ?>>
                        <label for="send_deleted_awb_report" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['deleted_awb_report_email']) ? $setting_comment['deleted_awb_report_email'] : ''; ?>" class="col-sm-4 col-form-label">Deleted,reprint and wt change Emails</label>
            <div class="col-sm-6 setting_data">
                <?php if (isset($mode) && $mode == "add_comments") { ?>
                    <textarea name="general[deleted_awb_report_email]" id="" cols="80" rows="1"><?php echo isset($setting['deleted_awb_report_email']) ? $setting['deleted_awb_report_email'] : ''; ?></textarea>
                <?php } else { ?>
                    <input type="text" class="form-control" name="general[deleted_awb_report_email]" value="<?php echo isset($setting['deleted_awb_report_email']) ? $setting['deleted_awb_report_email'] : ''; ?>" />
                <?php } ?>
            </div>
        </div>
    </div>


    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['send_invoice_report']) ? $setting_comment['send_invoice_report'] : ''; ?>" class="col-sm-4 col-form-label">Send Invoice report</label>
            <div class="col-sm-6 setting_data">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="general[send_invoice_report]" id="" cols="80" rows="1"><?php echo isset($setting['send_invoice_report']) ? $setting['send_invoice_report'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="send_invoice_report" value="1" autocomplete="nope" name="general[send_invoice_report]" <?php echo isset($setting['send_invoice_report']) && $setting['send_invoice_report'] == 1 ? 'checked' : ''; ?>>
                        <label for="send_invoice_report" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['invoice_report_email']) ? $setting_comment['invoice_report_email'] : ''; ?>" class="col-sm-4 col-form-label">Invoice REport Emails</label>
            <div class="col-sm-6 setting_data">
                <?php if (isset($mode) && $mode == "add_comments") { ?>
                    <textarea name="general[invoice_report_email]" id="" cols="80" rows="1"><?php echo isset($setting['invoice_report_email']) ? $setting['invoice_report_email'] : ''; ?></textarea>
                <?php } else { ?>
                    <input type="text" class="form-control" name="general[invoice_report_email]" value="<?php echo isset($setting['invoice_report_email']) ? $setting['invoice_report_email'] : ''; ?>" />
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['kyc_only_for_in']) ? $setting_comment['kyc_only_for_in'] : ''; ?>" class="col-sm-4 col-form-label">Mandate KYC only for Origin IN</label>
            <div class="col-sm-6 setting_data">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="general[kyc_only_for_in]" id="" cols="80" rows="1"><?php echo isset($setting['kyc_only_for_in']) ? $setting['kyc_only_for_in'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="kyc_only_for_in" value="1" autocomplete="nope" name="general[kyc_only_for_in]" <?php echo isset($setting['kyc_only_for_in']) && $setting['kyc_only_for_in'] == 1 ? 'checked' : ''; ?>>
                        <label for="kyc_only_for_in" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_unmanifest_on_dashboard']) ? $setting_comment['show_unmanifest_on_dashboard'] : ''; ?>" class="col-sm-4 col-form-label">Show Unmanifested AWB's On Dashboard</label>
            <div class="col-sm-6 setting_data">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="general[show_unmanifest_on_dashboard]" id="" cols="80" rows="1"><?php echo isset($setting['show_unmanifest_on_dashboard']) ? $setting['show_unmanifest_on_dashboard'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_unmanifest_on_dashboard" value="1" autocomplete="nope" name="general[show_unmanifest_on_dashboard]" <?php echo isset($setting['show_unmanifest_on_dashboard']) && $setting['show_unmanifest_on_dashboard'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_unmanifest_on_dashboard" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>


    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['enable_account_report']) ? $setting_comment['enable_account_report'] : ''; ?>" class="col-sm-4 col-form-label">enable acounting reports</label>
            <div class="col-sm-6 setting_data">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="general[enable_account_report]" id="" cols="80" rows="1"><?php echo isset($setting['enable_account_report']) ? $setting['enable_account_report'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_account_report" value="1" autocomplete="nope" name="general[enable_account_report]" <?php echo isset($setting['enable_account_report']) && $setting['enable_account_report'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_account_report" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['enable_quick_scan_awb']) ? $setting_comment['enable_quick_scan_awb'] : ''; ?>" class="col-sm-4 col-form-label">enable quick scan awb entry</label>
            <div class="col-sm-6 setting_data">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="general[enable_quick_scan_awb]" id="" cols="80" rows="1"><?php echo isset($setting['enable_quick_scan_awb']) ? $setting['enable_quick_scan_awb'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_quick_scan_awb" value="1" autocomplete="nope" name="general[enable_quick_scan_awb]" <?php echo isset($setting['enable_quick_scan_awb']) && $setting['enable_quick_scan_awb'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_quick_scan_awb" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['enable_pincode_master']) ? $setting_comment['enable_pincode_master'] : ''; ?>" class="col-sm-4 col-form-label">enable Pincode master</label>
            <div class="col-sm-6 setting_data">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="general[enable_pincode_master]" id="" cols="80" rows="1"><?php echo isset($setting['enable_pincode_master']) ? $setting['enable_pincode_master'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_pincode_master" value="1" autocomplete="nope" name="general[enable_pincode_master]" <?php echo isset($setting['enable_pincode_master']) && $setting['enable_pincode_master'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_pincode_master" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>


    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['send_account_opening_otp_whatsapp']) ? $setting_comment['send_account_opening_otp_whatsapp'] : ''; ?>" class="col-sm-4 col-form-label">Send account opening OTP on whatsapp</label>
            <div class="col-sm-6 setting_data">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="general[send_account_opening_otp_whatsapp]" id="" cols="80" rows="1"><?php echo isset($setting['send_account_opening_otp_whatsapp']) ? $setting['send_account_opening_otp_whatsapp'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="send_account_opening_otp_whatsapp" value="1" autocomplete="nope" name="general[send_account_opening_otp_whatsapp]" <?php echo isset($setting['send_account_opening_otp_whatsapp']) && $setting['send_account_opening_otp_whatsapp'] == 1 ? 'checked' : ''; ?>>
                        <label for="send_account_opening_otp_whatsapp" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" class="col-sm-4 col-form-label">enable quick scan awb entry</label>
            <div class="col-sm-6 setting_data">
                <div class="checkbox">
                    <input type="checkbox" id="enable_quick_scan_awb" value="1" autocomplete="nope" name="general[enable_quick_scan_awb]" <?php echo isset($setting['enable_quick_scan_awb']) && $setting['enable_quick_scan_awb'] == 1 ? 'checked' : ''; ?>>
                    <label for="enable_quick_scan_awb" style="height: 10px !important;"> </label>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['copy_shipment_value_as_total_of_invoice_value']) ? $setting_comment['copy_shipment_value_as_total_of_invoice_value'] : ''; ?>" class="col-sm-4 col-form-label">Copy Shipment Value as total of Invoice Value(Pannest) </label>
            <div class="col-sm-6 setting_data">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="general[copy_shipment_value_as_total_of_invoice_value]" id="" cols="80" rows="1"><?php echo isset($setting['copy_shipment_value_as_total_of_invoice_value']) ? $setting['copy_shipment_value_as_total_of_invoice_value'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="copy_shipment_value_as_total_of_invoice_value" value="1" autocomplete="nope" name="general[copy_shipment_value_as_total_of_invoice_value]" <?php echo isset($setting['copy_shipment_value_as_total_of_invoice_value']) && $setting['copy_shipment_value_as_total_of_invoice_value'] == 1 ? 'checked' : ''; ?>>
                        <label for="copy_shipment_value_as_total_of_invoice_value" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['atlantic_label_service_master']) ? $setting_comment['atlantic_label_service_master'] : ''; ?>" class="col-sm-4 col-form-label">Atlantic Label api service master</label>
            <div class="col-sm-6 setting_data">
                <?php if (isset($mode) && $mode == "add_comments") { ?>
                    <textarea name="general[atlantic_label_service_master]" id="" cols="80" rows="1"><?php echo isset($setting['atlantic_label_service_master']) ? $setting['atlantic_label_service_master'] : ''; ?></textarea>
                <?php } else { ?>
                    <textarea class="form-control" name="general[atlantic_label_service_master]"><?php echo isset($setting['atlantic_label_service_master']) ? $setting['atlantic_label_service_master'] : ''; ?></textarea>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['atlantic_label_vendor_master']) ? $setting_comment['atlantic_label_vendor_master'] : ''; ?>" class="col-sm-4 col-form-label">Atlantic Label api vendor master</label>
            <div class="col-sm-6 setting_data">
                <?php if (isset($mode) && $mode == "add_comments") { ?>
                    <textarea name="general[atlantic_label_vendor_master]" id="" cols="80" rows="1"><?php echo isset($setting['atlantic_label_vendor_master']) ? $setting['atlantic_label_vendor_master'] : ''; ?></textarea>
                <?php } else { ?>
                    <textarea class="form-control" name="general[atlantic_label_vendor_master]"><?php echo isset($setting['atlantic_label_vendor_master']) ? $setting['atlantic_label_vendor_master'] : ''; ?></textarea>
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" class="col-sm-4 col-form-label">enable quick scan awb entry</label>
            <div class="col-sm-6 setting_data">
                <div class="checkbox">
                    <input type="checkbox" id="enable_quick_scan_awb" value="1" autocomplete="nope" name="general[enable_quick_scan_awb]" <?php echo isset($setting['enable_quick_scan_awb']) && $setting['enable_quick_scan_awb'] == 1 ? 'checked' : ''; ?>>
                    <label for="enable_quick_scan_awb" style="height: 10px !important;"> </label>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" class="col-sm-4 col-form-label">ENABLE TICKET SYSTEM </label>
            <div class="col-sm-6 setting_data">
                <div class="checkbox">
                    <input type="checkbox" id="enable_ticket_system" value="1" autocomplete="nope" name="general[enable_ticket_system]" <?php echo isset($setting['enable_ticket_system']) && $setting['enable_ticket_system'] == 1 ? 'checked' : ''; ?>>
                    <label for="enable_ticket_system" style="height: 10px !important;"> </label>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" class="col-sm-4 col-form-label">ENABLE TICKET SYSTEM ITD</label>
            <div class="col-sm-6 setting_data">
                <div class="checkbox">
                    <input type="checkbox" id="enable_ticket_system_itd" value="1" autocomplete="nope" name="general[enable_ticket_system_itd]" <?php echo isset($setting['enable_ticket_system_itd']) && $setting['enable_ticket_system_itd'] == 1 ? 'checked' : ''; ?>>
                    <label for="enable_ticket_system_itd" style="height: 10px !important;"> </label>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" class="col-sm-4 col-form-label">Send account opening OTP on whatsapp</label>
            <div class="col-sm-6 setting_data">
                <div class="checkbox">
                    <input type="checkbox" id="send_account_opening_otp_whatsapp" value="1" autocomplete="nope" name="general[send_account_opening_otp_whatsapp]" <?php echo isset($setting['send_account_opening_otp_whatsapp']) && $setting['send_account_opening_otp_whatsapp'] == 1 ? 'checked' : ''; ?>>
                    <label for="send_account_opening_otp_whatsapp" style="height: 10px !important;"> </label>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" class="col-sm-4 col-form-label">Atlantic Label api service master</label>
            <div class="col-sm-6 setting_data">
                <textarea class="form-control" name="general[atlantic_label_service_master]"><?php echo isset($setting['atlantic_label_service_master']) ? $setting['atlantic_label_service_master'] : ''; ?></textarea>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" class="col-sm-4 col-form-label">Atlantic Label api vendor master</label>
            <div class="col-sm-6 setting_data">
                <textarea class="form-control" name="general[atlantic_label_vendor_master]"><?php echo isset($setting['atlantic_label_vendor_master']) ? $setting['atlantic_label_vendor_master'] : ''; ?></textarea>
            </div>
        </div>
    </div>


    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['remove_service_show_popup']) ? $setting_comment['remove_service_show_popup'] : ''; ?>" class="col-sm-4 col-form-label">Remove Service and show Service Pop up </label>
            <div class="col-sm-6 setting_data">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="general[remove_service_show_popup]" id="" cols="80" rows="1"><?php echo isset($setting['remove_service_show_popup']) ? $setting['remove_service_show_popup'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="remove_service_show_popup" value="1" autocomplete="nope" name="general[remove_service_show_popup]" <?php echo isset($setting['remove_service_show_popup']) && $setting['remove_service_show_popup'] == 1 ? 'checked' : ''; ?>>
                        <label for="remove_service_show_popup" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>


    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['enable_sectorwise_report']) ? $setting_comment['enable_sectorwise_report'] : ''; ?>" class="col-sm-4 col-form-label">Sectorwise profitibility</label>
            <div class="col-sm-6 setting_data">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="general[enable_sectorwise_report]" id="" cols="80" rows="1"><?php echo isset($setting['enable_sectorwise_report']) ? $setting['enable_sectorwise_report'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_sectorwise_report" value="1" autocomplete="nope" name="general[enable_sectorwise_report]" <?php echo isset($setting['enable_sectorwise_report']) && $setting['enable_sectorwise_report'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_sectorwise_report" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['sectorwise_report_email']) ? $setting_comment['sectorwise_report_email'] : ''; ?>" class="col-sm-4 col-form-label">Sectorwise profitibility Report Email</label>
            <div class="col-sm-6 setting_data">
                <?php if (isset($mode) && $mode == "add_comments") { ?>
                    <textarea name="general[sectorwise_report_email]" id="" cols="80" rows="1"><?php echo isset($setting['sectorwise_report_email']) ? $setting['sectorwise_report_email'] : ''; ?></textarea>
                <?php } else { ?>
                    <textarea class="form-control" name="general[sectorwise_report_email]"><?php echo isset($setting['sectorwise_report_email']) ? $setting['sectorwise_report_email'] : ''; ?></textarea>
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['vendor_reco_by_service']) ? $setting_comment['vendor_reco_by_service'] : ''; ?>" class="col-sm-4 col-form-label">Vendor reco by service</label>
            <div class="col-sm-6 setting_data">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="general[vendor_reco_by_service]" id="" cols="80" rows="1"><?php echo isset($setting['vendor_reco_by_service']) ? $setting['vendor_reco_by_service'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="vendor_reco_by_service" value="1" autocomplete="nope" name="general[vendor_reco_by_service]" <?php echo isset($setting['vendor_reco_by_service']) && $setting['vendor_reco_by_service'] == 1 ? 'checked' : ''; ?>>
                        <label for="vendor_reco_by_service" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>


    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['norsk_rate_api_in_customer_rate']) ? $setting_comment['norsk_rate_api_in_customer_rate'] : ''; ?>" class="col-sm-4 col-form-label">Norks rate calc API in software rate calc</label>
            <div class="col-sm-6 setting_data">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="master[norsk_rate_api_in_customer_rate]" id="" cols="80" rows="1"><?php echo isset($setting['norsk_rate_api_in_customer_rate']) ? $setting['norsk_rate_api_in_customer_rate'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="norsk_rate_api_in_customer_rate" value="1" autocomplete="nope" name="master[norsk_rate_api_in_customer_rate]" <?php echo isset($setting['norsk_rate_api_in_customer_rate']) && $setting['norsk_rate_api_in_customer_rate'] == 1 ? 'checked' : ''; ?>>
                        <label for="norsk_rate_api_in_customer_rate" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" class="col-sm-4 col-form-label">Remove Co Vendor From Vendor Rate Calc</label>
            <div class="col-sm-6 setting_data">
                <div class="checkbox">
                    <input type="checkbox" id="remove_co_vendor_from_vendor_rate_calc" value="1" autocomplete="nope" name="master[remove_co_vendor_from_vendor_rate_calc]" <?php echo isset($setting['remove_co_vendor_from_vendor_rate_calc']) && $setting['remove_co_vendor_from_vendor_rate_calc'] == 1 ? 'checked' : ''; ?>>
                    <label for="remove_co_vendor_from_vendor_rate_calc" style="height: 10px !important;"> </label>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" class="col-sm-4 col-form-label">ENABLE TICKET SYSTEM </label>
            <div class="col-sm-6 setting_data">
                <div class="checkbox">
                    <input type="checkbox" id="enable_ticket_system" value="1" autocomplete="nope" name="general[enable_ticket_system]" <?php echo isset($setting['enable_ticket_system']) && $setting['enable_ticket_system'] == 1 ? 'checked' : ''; ?>>
                    <label for="enable_ticket_system" style="height: 10px !important;"> </label>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['hide_vendor_copy_for_xpression_in_portal']) ? $setting_comment['hide_vendor_copy_for_xpression_in_portal'] : ''; ?>" class="col-sm-4 col-form-label">HIDE VENDOR COPY FOR EXPRESSION IN PORTAL</label>
            <div class="col-sm-6 setting_data">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="master[hide_vendor_copy_for_xpression_in_portal]" id="" cols="80" rows="1"><?php echo isset($setting['hide_vendor_copy_for_xpression_in_portal']) ? $setting['hide_vendor_copy_for_xpression_in_portal'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="hide_vendor_copy_for_xpression_in_portal" value="1" autocomplete="nope" name="master[hide_vendor_copy_for_xpression_in_portal]" <?php echo isset($setting['hide_vendor_copy_for_xpression_in_portal']) && $setting['hide_vendor_copy_for_xpression_in_portal'] == 1 ? 'checked' : ''; ?>>
                        <label for="hide_vendor_copy_for_xpression_in_portal" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['shipkar_domestic_zone_mapping']) ? $setting_comment['shipkar_domestic_zone_mapping'] : ''; ?>" class="col-sm-4 col-form-label">SHIPKAR DOMESTIC ZONE MAPPING</label>
            <div class="col-sm-6 setting_data">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="master[shipkar_domestic_zone_mapping]" id="" cols="80" rows="1"><?php echo isset($setting['shipkar_domestic_zone_mapping']) ? $setting['shipkar_domestic_zone_mapping'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="shipkar_domestic_zone_mapping" value="1" autocomplete="nope" name="master[shipkar_domestic_zone_mapping]" <?php echo isset($setting['shipkar_domestic_zone_mapping']) && $setting['shipkar_domestic_zone_mapping'] == 1 ? 'checked' : ''; ?>>
                        <label for="shipkar_domestic_zone_mapping" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['grey_company_fetch_from_customer']) ? $setting_comment['grey_company_fetch_from_customer'] : ''; ?>" class="col-sm-4 col-form-label">Grey out company dropdown of company and fetch it from customer master.</label>
            <div class="col-sm-6 setting_data">
                <?php if (isset($mode) && $mode == "add_comments") { ?>
                    <textarea name="general[grey_company_fetch_from_customer]" id="" cols="80" rows="1"><?php echo isset($setting['grey_company_fetch_from_customer']) ? $setting['grey_company_fetch_from_customer'] : ''; ?></textarea>
                <?php } else { ?>
                    <input type="checkbox" id="grey_company_fetch_from_customer" value="1" autocomplete="nope" name="general[grey_company_fetch_from_customer]" <?php echo isset($setting['grey_company_fetch_from_customer']) && $setting['grey_company_fetch_from_customer'] == 1 ? 'checked' : ''; ?>>
                    <label for="grey_company_fetch_from_customer" style="height: 10px !important;"> </label>
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['keep_customer_gst_unique']) ? $setting_comment['keep_customer_gst_unique'] : ''; ?>" class="col-sm-4 col-form-label">Keep Customer GST unique</label>
            <div class="col-sm-6 setting_data">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="master[keep_customer_gst_unique]" id="" cols="80" rows="1"><?php echo isset($setting['keep_customer_gst_unique']) ? $setting['keep_customer_gst_unique'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="keep_customer_gst_unique" value="1" autocomplete="nope" name="master[keep_customer_gst_unique]" <?php echo isset($setting['keep_customer_gst_unique']) && $setting['keep_customer_gst_unique'] == 1 ? 'checked' : ''; ?>>
                        <label for="keep_customer_gst_unique" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>


    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['default_vendor_rate_calc_in']) ? $setting_comment['default_vendor_rate_calc_in'] : ''; ?>" class="col-sm-4 col-form-label">DEFAULT VENDOR RATE CALC ORIGIN IN</label>
            <div class="col-sm-6 setting_data">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="master[default_vendor_rate_calc_in]" id="" cols="80" rows="1"><?php echo isset($setting['default_vendor_rate_calc_in']) ? $setting['default_vendor_rate_calc_in'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="default_vendor_rate_calc_in" value="1" autocomplete="nope" name="master[default_vendor_rate_calc_in]" <?php echo isset($setting['default_vendor_rate_calc_in']) && $setting['default_vendor_rate_calc_in'] == 1 ? 'checked' : ''; ?>>
                        <label for="default_vendor_rate_calc_in" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['fetch_tat_from']) ? $setting_comment['fetch_tat_from'] : ''; ?>" class="col-sm-4 col-form-label">Fetch Tat From:</label>
            <div class="col-sm-6 setting_data">
                <?php if (isset($mode) && $mode == "add_comments") { ?>
                    <textarea name="general[fetch_tat_from]" id="" cols="80" rows="1"><?php echo isset($setting['fetch_tat_from']) ? $setting['fetch_tat_from'] : ''; ?></textarea>
                <?php } else { ?>
                    <select name="general[fetch_tat_from]">
                        <option value="1" <?php echo isset($setting['fetch_tat_from']) && $setting['fetch_tat_from'] == 1 ? 'selected' : ''; ?>>Contract</option>
                        <option value="2" <?php echo isset($setting['fetch_tat_from']) && $setting['fetch_tat_from'] == 2 ? 'selected' : ''; ?>>Tat Master</option>
                    </select>
                <?php } ?>

            </div>
        </div>
    </div>


    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['fetch_rate_api_docket']) ? $setting_comment['fetch_rate_api_docket'] : ''; ?>" class="col-sm-4 col-form-label">Fetch rates from API in AWB ENTRY</label>
            <div class="col-sm-6 setting_data">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="master[fetch_rate_api_docket]" id="" cols="80" rows="1"><?php echo isset($setting['fetch_rate_api_docket']) ? $setting['fetch_rate_api_docket'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="fetch_rate_api_docket" value="1" autocomplete="nope" name="master[fetch_rate_api_docket]" <?php echo isset($setting['fetch_rate_api_docket']) && $setting['fetch_rate_api_docket'] == 1 ? 'checked' : ''; ?>>
                        <label for="fetch_rate_api_docket" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['enable_vendor_login']) ? $setting_comment['enable_vendor_login'] : ''; ?>" class="col-sm-4 col-form-label">Enable Vendor login</label>
            <div class="col-sm-6 setting_data">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="master[enable_vendor_login]" id="" cols="80" rows="1"><?php echo isset($setting['enable_vendor_login']) ? $setting['enable_vendor_login'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_vendor_login" value="1" autocomplete="nope" name="master[enable_vendor_login]" <?php echo isset($setting['enable_vendor_login']) && $setting['enable_vendor_login'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_vendor_login" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>


</div>