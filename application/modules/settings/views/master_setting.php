<div class="tab-pane" id="tab-3">

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['add_mailer_head_customer_master']) ? $setting_comment['add_mailer_head_customer_master'] : ''; ?>" class="col-sm-6 col-form-label">Mailer Head Can't be blank in Customer Master</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="master[add_mailer_head_customer_master]" id="" cols="80" rows="1"><?php echo isset($setting['add_mailer_head_customer_master']) ? $setting['add_mailer_head_customer_master'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="add_mailer_head_customer_master" value="1" autocomplete="nope" name="master[add_mailer_head_customer_master]" <?php echo isset($setting['add_mailer_head_customer_master']) && $setting['add_mailer_head_customer_master'] == 1 ? 'checked' : ''; ?>>
                        <label for="add_mailer_head_customer_master" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['default_product_service_wise']) ? $setting_comment['default_product_service_wise'] : ''; ?>" class="col-sm-6 col-form-label">Default Product Service wise</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="master[default_product_service_wise]" id="" cols="80" rows="1"><?php echo isset($setting['default_product_service_wise']) ? $setting['default_product_service_wise'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="default_product_service_wise" value="1" autocomplete="nope" name="master[default_product_service_wise]" <?php echo isset($setting['default_product_service_wise']) && $setting['default_product_service_wise'] == 1 ? 'checked' : ''; ?>>
                        <label for="default_product_service_wise" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['default_vendor_service_wise']) ? $setting_comment['default_vendor_service_wise'] : ''; ?>" class="col-sm-6 col-form-label">Default Vendor Service wise</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="master[default_vendor_service_wise]" id="" cols="80" rows="1"><?php echo isset($setting['default_vendor_service_wise']) ? $setting['default_vendor_service_wise'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="default_vendor_service_wise" value="1" autocomplete="nope" name="master[default_vendor_service_wise]" <?php echo isset($setting['default_vendor_service_wise']) && $setting['default_vendor_service_wise'] == 1 ? 'checked' : ''; ?>>
                        <label for="default_vendor_service_wise" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['auto_generate_shipper_code']) ? $setting_comment['auto_generate_shipper_code'] : ''; ?>" class="col-sm-6 col-form-label">Auto Generate Shipper Code</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="master[auto_generate_shipper_code]" id="" cols="80" rows="1"><?php echo isset($setting['auto_generate_shipper_code']) ? $setting['auto_generate_shipper_code'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="auto_generate_shipper_code" value="1" autocomplete="nope" name="master[auto_generate_shipper_code]" <?php echo isset($setting['auto_generate_shipper_code']) && $setting['auto_generate_shipper_code'] == 1 ? 'checked' : ''; ?>>
                        <label for="auto_generate_shipper_code" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['auto_generate_consignee_code']) ? $setting_comment['auto_generate_consignee_code'] : ''; ?>" class="col-sm-6 col-form-label">Auto Generate Consignee Code</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="master[auto_generate_consignee_code]" id="" cols="80" rows="1"><?php echo isset($setting['auto_generate_consignee_code']) ? $setting['auto_generate_consignee_code'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="auto_generate_consignee_code" value="1" autocomplete="nope" name="master[auto_generate_consignee_code]" <?php echo isset($setting['auto_generate_consignee_code']) && $setting['auto_generate_consignee_code'] == 1 ? 'checked' : ''; ?>>
                        <label for="auto_generate_consignee_code" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['auto_generate_pickup_code']) ? $setting_comment['auto_generate_pickup_code'] : ''; ?>" class="col-sm-6 col-form-label">Auto Generate Pick up Code</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="master[auto_generate_pickup_code]" id="" cols="80" rows="1"><?php echo isset($setting['auto_generate_pickup_code']) ? $setting['auto_generate_pickup_code'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="auto_generate_pickup_code" value="1" autocomplete="nope" name="master[auto_generate_pickup_code]" <?php echo isset($setting['auto_generate_pickup_code']) && $setting['auto_generate_pickup_code'] == 1 ? 'checked' : ''; ?>>
                        <label for="auto_generate_pickup_code" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['auto_generate_customer_code']) ? $setting_comment['auto_generate_customer_code'] : ''; ?>" class="col-sm-6 col-form-label">Auto Generate Customer Code</label>
            <div class="col-sm-1">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="master[auto_generate_customer_code]" id="" cols="80" rows="1"><?php echo isset($setting['auto_generate_customer_code']) ? $setting['auto_generate_customer_code'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="auto_generate_customer_code" value="1" autocomplete="nope" name="master[auto_generate_customer_code]" <?php echo isset($setting['auto_generate_customer_code']) && $setting['auto_generate_customer_code'] == 1 ? 'checked' : ''; ?>>
                        <label for="auto_generate_customer_code" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
            <?php
            $autono_style = 'd-none';
            if (isset($setting['auto_generate_customer_code']) && $setting['auto_generate_customer_code'] == 1) {
                $autono_style = '';
            }
            ?>
            <?php if ($mode != "add_comments") { ?>
                <div class="col-sm-5 <?php echo $autono_style; ?>" id="cust_code_type">
                    <div class="radio" style="display: inline;">
                        <input name="master[cust_code_type]" type="radio" id="Option_1" value="1" class="radio_format" checked="" autocomplete="nope">
                        <label for="Option_1">RANGE WISE</label>
                    </div>
                    <div class="radio" style="display: inline;">
                        <input name="master[cust_code_type]" type="radio" id="Option_2" value="2" class="radio_format" autocomplete="nope" <?php echo isset($setting['cust_code_type']) && $setting['cust_code_type'] == 2 ? 'checked' : ''; ?>>
                        <label for="Option_2">NAME WISE</label>
                    </div>

                    <?php
                    $autono_style = 'd-none';
                    if (isset($setting['cust_code_type']) && $setting['cust_code_type'] == 1) {
                        $autono_style = '';
                    }
                    ?>

                    <div id="cust_code_range" class="col-sm-12 <?php echo $autono_style; ?>" style="padding: 0px !important;">
                        <div class="col-sm-4" style="display: inline;float: left;">
                            <div class="form-group row">
                                <label class="col-sm-6">Prefix</label>
                                <input type="text" class="form-control col-sm-6" name="master[cust_code_prefix]" value="<?php echo $setting['cust_code_prefix'] ? $setting['cust_code_prefix'] : ''; ?>" />
                            </div>
                        </div>
                        <div class="col-sm-8" style="display: inline;float: right;">
                            <div class="form-group row">
                                <label class="col-sm-4">Range</label>
                                <input type="text" class="form-control col-sm-8" name="master[cust_code_range]" value="<?php echo $setting['cust_code_range'] ? $setting['cust_code_range'] : ''; ?>" />
                            </div>
                        </div>
                    </div>
                </div>
            <?php }  ?>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['auto_generate_vendor_code']) ? $setting_comment['auto_generate_vendor_code'] : ''; ?>" class="col-sm-6 col-form-label">Auto Generate Vendor Code</label>
            <div class="col-sm-1">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="master[auto_generate_vendor_code]" id="" cols="80" rows="1"><?php echo isset($setting['auto_generate_vendor_code']) ? $setting['auto_generate_vendor_code'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="auto_generate_vendor_code" value="1" autocomplete="nope" name="master[auto_generate_vendor_code]" <?php echo isset($setting['auto_generate_vendor_code']) && $setting['auto_generate_vendor_code'] == 1 ? 'checked' : ''; ?>>
                        <label for="auto_generate_vendor_code" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
            <?php
            $autono_style = 'd-none';
            if (isset($setting['auto_generate_vendor_code']) && $setting['auto_generate_vendor_code'] == 1) {
                $autono_style = '';
            }
            ?>
            <?php if ($mode != "add_comments") { ?>
                <div id="vendor_code_range" class="col-sm-5 <?php echo $autono_style; ?>">
                    <div class="col-sm-4" style="display: inline;float: left;">
                        <div class="form-group row">
                            <label class="col-sm-6">Prefix</label>
                            <input type="text" class="form-control col-sm-6" name="master[vendor_code_prefix]" value="<?php echo $setting['vendor_code_prefix'] ? $setting['vendor_code_prefix'] : ''; ?>" />
                        </div>
                    </div>
                    <div class="col-sm-8" style="display: inline;float: right;">
                        <div class="form-group row">
                            <label class="col-sm-4">Range</label>
                            <input type="text" class="form-control col-sm-8" name="master[vendor_code_range]" value="<?php echo $setting['vendor_code_range'] ? $setting['vendor_code_range'] : ''; ?>" />
                        </div>
                    </div>
                </div>
            <?php }  ?>
        </div>
    </div>



    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" class="col-sm-6 col-form-label">Auto Generate Pickup Sheet No.</label>
            <div class="col-sm-1">
                <div class="checkbox">
                    <input type="checkbox" id="auto_generate_pickup_sheet_no" value="1" autocomplete="nope" name="master[auto_generate_pickup_sheet_no]" <?php echo isset($setting['auto_generate_pickup_sheet_no']) && $setting['auto_generate_pickup_sheet_no'] == 1 ? 'checked' : ''; ?>>
                    <label for="auto_generate_pickup_sheet_no" style="height: 10px !important;"> </label>
                </div>
            </div>
            <?php
            $autono_style = 'd-none';
            if (isset($setting['auto_generate_pickup_sheet_no']) && $setting['auto_generate_pickup_sheet_no'] == 1) {
                $autono_style = '';
            }
            ?>

            <div id="pickup_sheet_range" class="col-sm-5 <?php echo $autono_style; ?>">
                <div class="col-sm-4" style="display: inline;float: left;">
                    <div class="form-group row">
                        <label class="col-sm-6">Prefix</label>
                        <input type="text" class="form-control col-sm-6" name="master[pickup_sheet_prefix]" value="<?php echo $setting['pickup_sheet_prefix'] ? $setting['pickup_sheet_prefix'] : ''; ?>" />
                    </div>
                </div>
                <div class="col-sm-8" style="display: inline;float: right;">
                    <div class="form-group row">
                        <label class="col-sm-4">Range</label>
                        <input type="text" class="form-control col-sm-8" name="master[pickup_sheet_range]" value="<?php echo $setting['pickup_sheet_range'] ? $setting['pickup_sheet_range'] : ''; ?>" />
                    </div>
                </div>
            </div>

        </div>
    </div>


    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['enable_vendor2']) ? $setting_comment['enable_vendor2'] : ''; ?>" class="col-sm-6 col-form-label">Enable Vendor 2</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="main[enable_vendor2]" id="" cols="80" rows="1"><?php echo isset($setting['enable_vendor2']) ? $setting['enable_vendor2'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_vendor2" value="1" autocomplete="nope" name="main[enable_vendor2]" <?php echo isset($setting['enable_vendor2']) && $setting['enable_vendor2'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_vendor2" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['enable_license_master']) ? $setting_comment['enable_license_master'] : ''; ?>" class="col-sm-6 col-form-label">Enable License Master</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="main[enable_license_master]" id="" cols="80" rows="1"><?php echo isset($setting['enable_license_master']) ? $setting['enable_license_master'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_license_master" value="1" autocomplete="nope" name="main[enable_license_master]" <?php echo isset($setting['enable_license_master']) && $setting['enable_license_master'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_license_master" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['enable_other_address_master']) ? $setting_comment['enable_other_address_master'] : ''; ?>" class="col-sm-6 col-form-label">Enable other address masters</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="main[enable_other_address_master]" id="" cols="80" rows="1"><?php echo isset($setting['enable_other_address_master']) ? $setting['enable_other_address_master'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_other_address_master" value="1" autocomplete="nope" name="main[enable_other_address_master]" <?php echo isset($setting['enable_other_address_master']) && $setting['enable_other_address_master'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_other_address_master" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['enable_runsheet']) ? $setting_comment['enable_runsheet'] : ''; ?>" class="col-sm-6 col-form-label">Enable Runsheet</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="main[enable_runsheet]" id="" cols="80" rows="1"><?php echo isset($setting['enable_runsheet']) ? $setting['enable_runsheet'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_runsheet" value="1" autocomplete="nope" name="main[enable_runsheet]" <?php echo isset($setting['enable_runsheet']) && $setting['enable_runsheet'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_runsheet" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>


    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['cust_kyc_upload_emails']) ? $setting_comment['cust_kyc_upload_emails'] : ''; ?>" class="col-sm-6 col-form-label">Customer KYC Upload Notification Email Addresses</label>
            <div class="col-sm-6">
                <?php if (isset($mode) && $mode == "add_comments") { ?>
                    <textarea name="master[cust_kyc_upload_emails]" id="" cols="80" rows="1"><?php echo isset($setting['cust_kyc_upload_emails']) ? $setting['cust_kyc_upload_emails'] : ''; ?></textarea>
                <?php } else { ?>
                    <input type="text" class="form-control" name="master[cust_kyc_upload_emails]" value="<?php echo isset($setting['cust_kyc_upload_emails']) ? $setting['cust_kyc_upload_emails'] : ''; ?>" />
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['hide_date_and_pcs_in_rate_cals']) ? $setting_comment['hide_date_and_pcs_in_rate_cals'] : ''; ?>" class="col-sm-6 col-form-label">HIDE DATE AND PCS IN RATE CALS</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="master[hide_date_and_pcs_in_rate_cals]" id="" cols="80" rows="1"><?php echo isset($setting['hide_date_and_pcs_in_rate_cals']) ? $setting['hide_date_and_pcs_in_rate_cals'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="hide_date_and_pcs_in_rate_cals" value="1" autocomplete="nope" name="master[hide_date_and_pcs_in_rate_cals]" <?php echo isset($setting['hide_date_and_pcs_in_rate_cals']) && $setting['hide_date_and_pcs_in_rate_cals'] == 1 ? 'checked' : ''; ?>>
                        <label for="hide_date_and_pcs_in_rate_cals" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['enable_einvoice']) ? $setting_comment['enable_einvoice'] : ''; ?>" class="col-sm-6 col-form-label">Enable E-invoice</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="main[enable_einvoice]" id="" cols="80" rows="1"><?php echo isset($setting['enable_einvoice']) ? $setting['enable_einvoice'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_einvoice" value="1" autocomplete="nope" name="main[enable_einvoice]" <?php echo isset($setting['enable_einvoice']) && $setting['enable_einvoice'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_einvoice" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>


    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['rename_service_to_mode']) ? $setting_comment['rename_service_to_mode'] : ''; ?>" class="col-sm-6 col-form-label">Rename Service To Mode</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="main[rename_service_to_mode]" id="" cols="80" rows="1"><?php echo isset($setting['rename_service_to_mode']) ? $setting['rename_service_to_mode'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="rename_service_to_mode" value="1" autocomplete="nope" name="main[rename_service_to_mode]" <?php echo isset($setting['rename_service_to_mode']) && $setting['rename_service_to_mode'] == 1 ? 'checked' : ''; ?>>
                        <label for="rename_service_to_mode" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['auto_generate_inquiry_no']) ? $setting_comment['auto_generate_inquiry_no'] : ''; ?>" class="col-sm-6 col-form-label">AUTO GENERATE INQUIRY NO</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="master[auto_generate_inquiry_no]" id="" cols="80" rows="1"><?php echo isset($setting['auto_generate_inquiry_no']) ? $setting['auto_generate_inquiry_no'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="auto_generate_inquiry_no" value="1" autocomplete="nope" name="master[auto_generate_inquiry_no]" <?php echo isset($setting['auto_generate_inquiry_no']) && $setting['auto_generate_inquiry_no'] == 1 ? 'checked' : ''; ?>>
                        <label for="auto_generate_inquiry_no" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>


</div>
<script>
    $(document).ready(function() {
        $("#auto_generate_vendor_code").click(function() {
            if (this.checked) {
                $("#vendor_code_range").removeClass("d-none");
            } else {
                $("#vendor_code_range").addClass("d-none");
                $('input[name="master[vendor_code_prefix]"]').val('');
                $('input[name="master[vendor_code_range]"]').val('');
            }
        });

        $("#auto_generate_pickup_sheet_no").click(function() {
            if (this.checked) {
                $("#pickup_sheet_range").removeClass("d-none");
            } else {
                $("#pickup_sheet_range").addClass("d-none");
                $('input[name="master[pickup_sheet_prefix]"]').val('');
                $('input[name="master[pickup_sheet_range]"]').val('');
            }
        });

        $("#auto_generate_customer_code").click(function() {
            if (this.checked) {
                $("#cust_code_type").removeClass("d-none");
                $("#cust_code_range").removeClass("d-none");
            } else {
                $("#cust_code_type").addClass("d-none");
                $("#cust_code_range").addClass("d-none");
                $('input[name="master[cust_code_prefix]"]').val('');
                $('input[name="master[cust_code_range]"]').val('');
            }
        });
        $(".radio_format").click(function() {
            var radio_format_value = $("input[class='radio_format']:checked").val();
            if (radio_format_value == 1 && $("#auto_generate_customer_code").is(":checked")) {
                $("#cust_code_range").removeClass("d-none");
            } else {
                $("#cust_code_range").addClass("d-none");
                $('input[name="master[cust_code_prefix]"]').val('');
                $('input[name="master[cust_code_range]"]').val('');
            }
        });
    });
</script>