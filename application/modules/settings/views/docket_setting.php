<div class="tab-pane" id="tab-2">
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['enable_docket_multiple_desc']) ? $setting_comment['enable_docket_multiple_desc'] : ''; ?>" class="col-sm-6 col-form-label">Enable Multiple Description in AWB Entry</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[enable_docket_multiple_desc]" id="" cols="80" rows="1"><?php echo isset($setting['enable_docket_multiple_desc']) ? $setting['enable_docket_multiple_desc'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_docket_multiple_desc" value="1" autocomplete="nope" name="docket[enable_docket_multiple_desc]" <?php echo isset($setting['enable_docket_multiple_desc']) && $setting['enable_docket_multiple_desc'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_docket_multiple_desc" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['docket_shipper_by_customer']) ? $setting_comment['docket_shipper_by_customer'] : ''; ?>" class="col-sm-6 col-form-label">Autocomplete Shippers By Customer</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[docket_shipper_by_customer]" id="" cols="80" rows="1"><?php echo isset($setting['docket_shipper_by_customer']) ? $setting['docket_shipper_by_customer'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="docket_shipper_by_customer" value="1" autocomplete="nope" name="docket[docket_shipper_by_customer]" <?php echo isset($setting['docket_shipper_by_customer']) && $setting['docket_shipper_by_customer'] == 1 ? 'checked' : ''; ?>>
                        <label for="docket_shipper_by_customer" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['docket_consignee_by_customer']) ? $setting_comment['docket_consignee_by_customer'] : ''; ?>" class="col-sm-6 col-form-label">Autocomplete Consignees By Customer</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[docket_consignee_by_customer]" id="" cols="80" rows="1"><?php echo isset($setting['docket_consignee_by_customer']) ? $setting['docket_consignee_by_customer'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="docket_consignee_by_customer" value="1" autocomplete="nope" name="docket[docket_consignee_by_customer]" <?php echo isset($setting['docket_consignee_by_customer']) && $setting['docket_consignee_by_customer'] == 1 ? 'checked' : ''; ?>>
                        <label for="docket_consignee_by_customer" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <!-- <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" class="col-sm-6 col-form-label">Autocomplete Shippers By Origin</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <input type="checkbox" id="docket_shipper_by_ori" value="1" autocomplete="nope" name="docket[docket_shipper_by_ori]" <?php echo isset($setting['docket_shipper_by_ori']) && $setting['docket_shipper_by_ori'] == 1 ? 'checked' : ''; ?>>
                    <label for="docket_shipper_by_ori" style="height: 10px !important;"> </label>
                </div>
            </div>
        </div>
    </div> -->
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['docket_consignee_by_dest']) ? $setting_comment['docket_consignee_by_dest'] : ''; ?>" class="col-sm-6 col-form-label">Autocomplete Consignees By Destination</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[docket_consignee_by_dest]" id="" cols="80" rows="1"><?php echo isset($setting['docket_consignee_by_dest']) ? $setting['docket_consignee_by_dest'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="docket_consignee_by_dest" value="1" autocomplete="nope" name="docket[docket_consignee_by_dest]" <?php echo isset($setting['docket_consignee_by_dest']) && $setting['docket_consignee_by_dest'] == 1 ? 'checked' : ''; ?>>
                        <label for="docket_consignee_by_dest" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['docket_show_box_no']) ? $setting_comment['docket_show_box_no'] : ''; ?>" class="col-sm-6 col-form-label">Show Number of Boxes In AWB Item</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[docket_show_box_no]" id="" cols="80" rows="1"><?php echo isset($setting['docket_show_box_no']) ? $setting['docket_show_box_no'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="docket_show_box_no" value="1" autocomplete="nope" name="docket[docket_show_box_no]" <?php echo isset($setting['docket_show_box_no']) && $setting['docket_show_box_no'] == 1 ? 'checked' : ''; ?>>
                        <label for="docket_show_box_no" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['docket_show_contract_service']) ? $setting_comment['docket_show_contract_service'] : ''; ?>" class="col-sm-6 col-form-label">Show only Those Services Whose Contract is Present</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[docket_show_contract_service]" id="" cols="80" rows="1"><?php echo isset($setting['docket_show_contract_service']) ? $setting['docket_show_contract_service'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="docket_show_contract_service" value="1" autocomplete="nope" name="docket[docket_show_contract_service]" <?php echo isset($setting['docket_show_contract_service']) && $setting['docket_show_contract_service'] == 1 ? 'checked' : ''; ?>>
                        <label for="docket_show_contract_service" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['fetch_origin_hub_master']) ? $setting_comment['fetch_origin_hub_master'] : ''; ?>" class="col-sm-6 col-form-label">Fetch origin hub from</label>
            <div class="col-sm-6">
                <?php if (isset($mode) && $mode == "add_comments") { ?>
                    <textarea name="docket[fetch_origin_hub_master]" id="" cols="80" rows="1"><?php echo isset($setting['fetch_origin_hub_master']) ? $setting['fetch_origin_hub_master'] : ''; ?></textarea>
                <?php } else { ?>
                    <select name="docket[fetch_origin_hub_master]">
                        <option value="2" <?php echo isset($setting['fetch_origin_hub_master']) && $setting['fetch_origin_hub_master'] == 2 ? 'selected' : ''; ?>>CUSTOMER MASTER</option>
                        <option value="1" <?php echo isset($setting['fetch_origin_hub_master']) && $setting['fetch_origin_hub_master'] == 1 ? 'selected' : ''; ?>>LOCATION MASTER</option>
                    </select>
                <?php } ?>

            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['fetch_gst_applicable']) ? $setting_comment['fetch_gst_applicable'] : ''; ?>" class="col-sm-6 col-form-label">Fetch GST TRUE/FALSE FROM</label>
            <div class="col-sm-6">
                <?php if (isset($mode) && $mode == "add_comments") { ?>
                    <textarea name="docket[fetch_gst_applicable]" id="" cols="80" rows="1"><?php echo isset($setting['fetch_gst_applicable']) ? $setting['fetch_gst_applicable'] : ''; ?></textarea>
                <?php } else { ?>
                    <select name="docket[fetch_gst_applicable]">
                        <option value="1" <?php echo isset($setting['fetch_gst_applicable']) && $setting['fetch_gst_applicable'] == 1 ? 'selected' : ''; ?>>CUSTOMER MASTER</option>
                        <option value="2" <?php echo isset($setting['fetch_gst_applicable']) && $setting['fetch_gst_applicable'] == 2 ? 'selected' : ''; ?>>SERVICE MASTER</option>
                        <option value="3" <?php echo isset($setting['fetch_gst_applicable']) && $setting['fetch_gst_applicable'] == 3 ? 'selected' : ''; ?>>NONE</option>
                    </select>
                <?php } ?>

            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['set_default_currency_new_awb']) ? $setting_comment['set_default_currency_new_awb'] : ''; ?>" class="col-sm-6 col-form-label">Set default currency in new awb entry and free form</label>
            <div class="col-sm-6">
                <?php if (isset($mode) && $mode == "add_comments") { ?>
                    <textarea name="docket[set_default_currency_new_awb]" id="" cols="80" rows="1"><?php echo isset($setting['set_default_currency_new_awb']) ? $setting['set_default_currency_new_awb'] : ''; ?></textarea>
                <?php } else { ?>
                    <select name="docket[set_default_currency_new_awb]">
                        <option value="">Select...</option>
                        <?php
                        if (isset($all_currency) && is_array($all_currency) && count($all_currency) > 0) {
                            foreach ($all_currency as $gkey => $gvalue) { ?>
                                <option value="<?php echo $gvalue['id'] ?>" <?php echo isset($setting['set_default_currency_new_awb']) && $setting['set_default_currency_new_awb'] == $gvalue['id'] ? 'selected' : ''; ?>><?php echo $gvalue['code'] ?></option>
                        <?php }
                        }
                        ?>
                    </select>
                <?php } ?>

            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['docket_display_parcel_tracking']) ? $setting_comment['docket_display_parcel_tracking'] : ''; ?>" class="col-sm-6 col-form-label">Display parcel Tracking</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[docket_display_parcel_tracking]" id="" cols="80" rows="1"><?php echo isset($setting['docket_display_parcel_tracking']) ? $setting['docket_display_parcel_tracking'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="docket_display_parcel_tracking" value="1" autocomplete="nope" name="docket[docket_display_parcel_tracking]" <?php echo isset($setting['docket_display_parcel_tracking']) && $setting['docket_display_parcel_tracking'] == 1 ? 'checked' : ''; ?>>
                        <label for="docket_display_parcel_tracking" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_country_and_service_in_dropdown']) ? $setting_comment['show_country_and_service_in_dropdown'] : ''; ?>" class="col-sm-6 col-form-label">Show Country and Service in Dropdown</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[show_country_and_service_in_dropdown]" id="" cols="80" rows="1"><?php echo isset($setting['show_country_and_service_in_dropdown']) ? $setting['show_country_and_service_in_dropdown'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_country_and_service_in_dropdown" value="1" autocomplete="nope" name="docket[show_country_and_service_in_dropdown]" <?php echo isset($setting['show_country_and_service_in_dropdown']) && $setting['show_country_and_service_in_dropdown'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_country_and_service_in_dropdown" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['disabled_actual_and_volumentric_weight_after_in_scanned']) ? $setting_comment['disabled_actual_and_volumentric_weight_after_in_scanned'] : ''; ?>" class="col-sm-6 col-form-label">Disabled Actual and Volumetric Weight After In Scanned</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[disabled_actual_and_volumentric_weight_after_in_scanned]" id="" cols="80" rows="1"><?php echo isset($setting['disabled_actual_and_volumentric_weight_after_in_scanned']) ? $setting['disabled_actual_and_volumentric_weight_after_in_scanned'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="disabled_actual_and_volumentric_weight_after_in_scanned" value="1" autocomplete="nope" name="docket[disabled_actual_and_volumentric_weight_after_in_scanned]" <?php echo isset($setting['disabled_actual_and_volumentric_weight_after_in_scanned']) && $setting['disabled_actual_and_volumentric_weight_after_in_scanned'] == 1 ? 'checked' : ''; ?>>
                        <label for="disabled_actual_and_volumentric_weight_after_in_scanned" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['enable_postal_code_validation_and_suggestion']) ? $setting_comment['enable_postal_code_validation_and_suggestion'] : ''; ?>" class="col-sm-6 col-form-label">Enable Postal Code Validation And Suggestion</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[enable_postal_code_validation_and_suggestion]" id="" cols="80" rows="1"><?php echo isset($setting['enable_postal_code_validation_and_suggestion']) ? $setting['enable_postal_code_validation_and_suggestion'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_postal_code_validation_and_suggestion" value="1" autocomplete="nope" name="docket[enable_postal_code_validation_and_suggestion]" <?php echo isset($setting['enable_postal_code_validation_and_suggestion']) && $setting['enable_postal_code_validation_and_suggestion'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_postal_code_validation_and_suggestion" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['enable_authorize_letter_grid']) ? $setting_comment['enable_authorize_letter_grid'] : ''; ?>" class="col-sm-6 col-form-label">Enable print authorization letter in awbs grid</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[enable_authorize_letter_grid]" id="" cols="80" rows="1"><?php echo isset($setting['enable_authorize_letter_grid']) ? $setting['enable_authorize_letter_grid'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_authorize_letter_grid" value="1" autocomplete="nope" name="docket[enable_authorize_letter_grid]" <?php echo isset($setting['enable_authorize_letter_grid']) && $setting['enable_authorize_letter_grid'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_authorize_letter_grid" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['set_cust_contract_mandatory']) ? $setting_comment['set_cust_contract_mandatory'] : ''; ?>" class="col-sm-6 col-form-label">Set Customer Contract Mandatory</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[set_cust_contract_mandatory]" id="" cols="80" rows="1"><?php echo isset($setting['set_cust_contract_mandatory']) ? $setting['set_cust_contract_mandatory'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="set_cust_contract_mandatory" value="1" autocomplete="nope" name="docket[set_cust_contract_mandatory]" <?php echo isset($setting['set_cust_contract_mandatory']) && $setting['set_cust_contract_mandatory'] == 1 ? 'checked' : ''; ?>>
                        <label for="set_cust_contract_mandatory" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['booking_datetime_system_default']) ? $setting_comment['booking_datetime_system_default'] : ''; ?>" class="col-sm-6 col-form-label">Disable booking date and time, set to system default on AWB entry</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[booking_datetime_system_default]" id="" cols="80" rows="1"><?php echo isset($setting['booking_datetime_system_default']) ? $setting['booking_datetime_system_default'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="booking_datetime_system_default" value="1" autocomplete="nope" name="docket[booking_datetime_system_default]" <?php echo isset($setting['booking_datetime_system_default']) && $setting['booking_datetime_system_default'] == 1 ? 'checked' : ''; ?>>
                        <label for="booking_datetime_system_default" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['booking_datetime_system_default_in_portal']) ? $setting_comment['booking_datetime_system_default_in_portal'] : ''; ?>" class="col-sm-6 col-form-label">Disable booking date and time in Portal, set to system default on AWB entry</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[booking_datetime_system_default_in_portal]" id="" cols="80" rows="1"><?php echo isset($setting['booking_datetime_system_default_in_portal']) ? $setting['booking_datetime_system_default_in_portal'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="booking_datetime_system_default_in_portal" value="1" autocomplete="nope" name="docket[booking_datetime_system_default_in_portal]" <?php echo isset($setting['booking_datetime_system_default_in_portal']) && $setting['booking_datetime_system_default_in_portal'] == 1 ? 'checked' : ''; ?>>
                        <label for="booking_datetime_system_default_in_portal" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['disable_payment_type_set_from_customer']) ? $setting_comment['disable_payment_type_set_from_customer'] : ''; ?>" class="col-sm-6 col-form-label">Disable type of payment and set it from customer master on AWB entry</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[disable_payment_type_set_from_customer]" id="" cols="80" rows="1"><?php echo isset($setting['disable_payment_type_set_from_customer']) ? $setting['disable_payment_type_set_from_customer'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="disable_payment_type_set_from_customer" value="1" autocomplete="nope" name="docket[disable_payment_type_set_from_customer]" <?php echo isset($setting['disable_payment_type_set_from_customer']) && $setting['disable_payment_type_set_from_customer'] == 1 ? 'checked' : ''; ?>>
                        <label for="disable_payment_type_set_from_customer" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['set_awb_no_length']) ? $setting_comment['set_awb_no_length'] : ''; ?>" class="col-sm-6 col-form-label">Set AWB No. length</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[set_awb_no_length]" id="" cols="80" rows="1"><?php echo isset($setting['set_awb_no_length']) ? $setting['set_awb_no_length'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="text" class="form-control" name="docket[set_awb_no_length]" value="<?php echo isset($setting['set_awb_no_length']) ? $setting['set_awb_no_length'] : ''; ?>" />
                        <label for="cust_id" class="col-sm-6 col-form-label">Set AWB No. length</label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['apply_sepcial_rate_cust_contract']) ? $setting_comment['apply_sepcial_rate_cust_contract'] : ''; ?>" class="col-sm-6 col-form-label">Apply Special Rate In awb Entry for Customer Contract</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[apply_sepcial_rate_cust_contract]" id="" cols="80" rows="1"><?php echo isset($setting['apply_sepcial_rate_cust_contract']) ? $setting['apply_sepcial_rate_cust_contract'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="apply_sepcial_rate_cust_contract" value="1" autocomplete="nope" name="docket[apply_sepcial_rate_cust_contract]" <?php echo isset($setting['apply_sepcial_rate_cust_contract']) && $setting['apply_sepcial_rate_cust_contract'] == 1 ? 'checked' : ''; ?>>
                        <label for="apply_sepcial_rate_cust_contract" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>


    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['apply_sepcial_rate_rate_modifier']) ? $setting_comment['apply_sepcial_rate_rate_modifier'] : ''; ?>" class="col-sm-6 col-form-label">Apply special rate in rate modifier</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[apply_sepcial_rate_rate_modifier]" id="" cols="80" rows="1"><?php echo isset($setting['apply_sepcial_rate_rate_modifier']) ? $setting['apply_sepcial_rate_rate_modifier'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="apply_sepcial_rate_rate_modifier" value="1" autocomplete="nope" name="docket[apply_sepcial_rate_rate_modifier]" <?php echo isset($setting['apply_sepcial_rate_rate_modifier']) && $setting['apply_sepcial_rate_rate_modifier'] == 1 ? 'checked' : ''; ?>>
                        <label for="apply_sepcial_rate_rate_modifier" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>


    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['round_up_chargeable_weight']) ? $setting_comment['round_up_chargeable_weight'] : ''; ?>" class="col-sm-6 col-form-label">Round Up Chargeable Weight</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[round_up_chargeable_weight]" id="" cols="80" rows="1"><?php echo isset($setting['round_up_chargeable_weight']) ? $setting['round_up_chargeable_weight'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="round_up_chargeable_weight" value="1" autocomplete="nope" name="docket[round_up_chargeable_weight]" <?php echo isset($setting['round_up_chargeable_weight']) && $setting['round_up_chargeable_weight'] == 1 ? 'checked' : ''; ?>>
                        <label for="round_up_chargeable_weight" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['infinite_till_date_in_customer_contract']) ? $setting_comment['infinite_till_date_in_customer_contract'] : ''; ?>" class="col-sm-6 col-form-label">Infinite till date in customer contract</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[infinite_till_date_in_customer_contract]" id="" cols="80" rows="1"><?php echo isset($setting['infinite_till_date_in_customer_contract']) ? $setting['infinite_till_date_in_customer_contract'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="infinite_till_date_in_customer_contract" value="1" autocomplete="nope" name="docket[infinite_till_date_in_customer_contract]" <?php echo isset($setting['infinite_till_date_in_customer_contract']) && $setting['infinite_till_date_in_customer_contract'] == 1 ? 'checked' : ''; ?>>
                        <label for="infinite_till_date_in_customer_contract" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_add_weight_in_entry_page']) ? $setting_comment['show_add_weight_in_entry_page'] : ''; ?>" class="col-sm-6 col-form-label">Show Add Weight On Entry Page</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[show_add_weight_in_entry_page]" id="" cols="80" rows="1"><?php echo isset($setting['show_add_weight_in_entry_page']) ? $setting['show_add_weight_in_entry_page'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_add_weight_in_entry_page" value="1" autocomplete="nope" name="docket[show_add_weight_in_entry_page]" <?php echo isset($setting['show_add_weight_in_entry_page']) && $setting['show_add_weight_in_entry_page'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_add_weight_in_entry_page" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['enable_self_label_preview']) ? $setting_comment['enable_self_label_preview'] : ''; ?>" class="col-sm-6 col-form-label">Enable Self Label Preview</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[enable_self_label_preview]" id="" cols="80" rows="1"><?php echo isset($setting['enable_self_label_preview']) ? $setting['enable_self_label_preview'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_self_label_preview" value="1" autocomplete="nope" name="docket[enable_self_label_preview]" <?php echo isset($setting['enable_self_label_preview']) && $setting['enable_self_label_preview'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_self_label_preview" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['enable_hardcoded_hs_code_mapping_for_label_api']) ? $setting_comment['enable_hardcoded_hs_code_mapping_for_label_api'] : ''; ?>" class="col-sm-6 col-form-label">Enable Hardcoded HS Code Mapping For Label API</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[enable_hardcoded_hs_code_mapping_for_label_api]" id="" cols="80" rows="1"><?php echo isset($setting['enable_hardcoded_hs_code_mapping_for_label_api']) ? $setting['enable_hardcoded_hs_code_mapping_for_label_api'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_hardcoded_hs_code_mapping_for_label_api" value="1" autocomplete="nope" name="docket[enable_hardcoded_hs_code_mapping_for_label_api]" <?php echo isset($setting['enable_hardcoded_hs_code_mapping_for_label_api']) && $setting['enable_hardcoded_hs_code_mapping_for_label_api'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_hardcoded_hs_code_mapping_for_label_api" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['display_vendor_in_customer_portal_awb_entry']) ? $setting_comment['display_vendor_in_customer_portal_awb_entry'] : ''; ?>" class="col-sm-6 col-form-label">Display Vendor In Customer Portal AWb Entry</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[display_vendor_in_customer_portal_awb_entry]" id="" cols="80" rows="1"><?php echo isset($setting['display_vendor_in_customer_portal_awb_entry']) ? $setting['display_vendor_in_customer_portal_awb_entry'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="display_vendor_in_customer_portal_awb_entry" value="1" autocomplete="nope" name="docket[display_vendor_in_customer_portal_awb_entry]" <?php echo isset($setting['display_vendor_in_customer_portal_awb_entry']) && $setting['display_vendor_in_customer_portal_awb_entry'] == 1 ? 'checked' : ''; ?>>
                        <label for="display_vendor_in_customer_portal_awb_entry" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['multiple_shipment_invoice_number_in_entry']) ? $setting_comment['multiple_shipment_invoice_number_in_entry'] : ''; ?>" class="col-sm-6 col-form-label">Add Multiple Shipment Invoice Number Option In Entry Page</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[multiple_shipment_invoice_number_in_entry]" id="" cols="80" rows="1"><?php echo isset($setting['multiple_shipment_invoice_number_in_entry']) ? $setting['multiple_shipment_invoice_number_in_entry'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="multiple_shipment_invoice_number_in_entry" value="1" autocomplete="nope" name="docket[multiple_shipment_invoice_number_in_entry]" <?php echo isset($setting['multiple_shipment_invoice_number_in_entry']) && $setting['multiple_shipment_invoice_number_in_entry'] == 1 ? 'checked' : ''; ?>>
                        <label for="multiple_shipment_invoice_number_in_entry" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['enable_skyline_carrier_service']) ? $setting_comment['enable_skyline_carrier_service'] : ''; ?>" class="col-sm-6 col-form-label">Enable Skynet Carrier Service</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[enable_skyline_carrier_service]" id="" cols="80" rows="1"><?php echo isset($setting['enable_skyline_carrier_service']) ? $setting['enable_skyline_carrier_service'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_skyline_carrier_service" value="1" autocomplete="nope" name="docket[enable_skyline_carrier_service]" <?php echo isset($setting['enable_skyline_carrier_service']) && $setting['enable_skyline_carrier_service'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_skyline_carrier_service" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['add_edit_volume_weight_option_on_entry_page']) ? $setting_comment['add_edit_volume_weight_option_on_entry_page'] : ''; ?>" class="col-sm-6 col-form-label">Add Edit Volume Weight Option On Entry Page</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[add_edit_volume_weight_option_on_entry_page]" id="" cols="80" rows="1"><?php echo isset($setting['add_edit_volume_weight_option_on_entry_page']) ? $setting['add_edit_volume_weight_option_on_entry_page'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="add_edit_volume_weight_option_on_entry_page" value="1" autocomplete="nope" name="docket[add_edit_volume_weight_option_on_entry_page]" <?php echo isset($setting['add_edit_volume_weight_option_on_entry_page']) && $setting['add_edit_volume_weight_option_on_entry_page'] == 1 ? 'checked' : ''; ?>>
                        <label for="add_edit_volume_weight_option_on_entry_page" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['add_edit_actual_weight_option_on_entry_page']) ? $setting_comment['add_edit_actual_weight_option_on_entry_page'] : ''; ?>" class="col-sm-6 col-form-label">Add Edit Actual Weight Option On Entry Page</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[add_edit_actual_weight_option_on_entry_page]" id="" cols="80" rows="1"><?php echo isset($setting['add_edit_actual_weight_option_on_entry_page']) ? $setting['add_edit_actual_weight_option_on_entry_page'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="add_edit_actual_weight_option_on_entry_page" value="1" autocomplete="nope" name="docket[add_edit_actual_weight_option_on_entry_page]" <?php echo isset($setting['add_edit_actual_weight_option_on_entry_page']) && $setting['add_edit_actual_weight_option_on_entry_page'] == 1 ? 'checked' : ''; ?>>
                        <label for="add_edit_actual_weight_option_on_entry_page" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['enable_shipper_kyc_validation']) ? $setting_comment['enable_shipper_kyc_validation'] : ''; ?>" class="col-sm-6 col-form-label">Enable Shipper's KYC Document Validation and Suggestion</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[enable_shipper_kyc_validation]" id="" cols="80" rows="1"><?php echo isset($setting['enable_shipper_kyc_validation']) ? $setting['enable_shipper_kyc_validation'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_shipper_kyc_validation" value="1" autocomplete="nope" name="docket[enable_shipper_kyc_validation]" <?php echo isset($setting['enable_shipper_kyc_validation']) && $setting['enable_shipper_kyc_validation'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_shipper_kyc_validation" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_indian_state_under_shipper']) ? $setting_comment['show_indian_state_under_shipper'] : ''; ?>" class="col-sm-6 col-form-label">Show Indian States Under Shipper</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[show_indian_state_under_shipper]" id="" cols="80" rows="1"><?php echo isset($setting['show_indian_state_under_shipper']) ? $setting['show_indian_state_under_shipper'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_indian_state_under_shipper" value="1" autocomplete="nope" name="docket[show_indian_state_under_shipper]" <?php echo isset($setting['show_indian_state_under_shipper']) && $setting['show_indian_state_under_shipper'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_indian_state_under_shipper" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_discount_amount_in_awb_entry_page_free_form_invoice']) ? $setting_comment['show_discount_amount_in_awb_entry_page_free_form_invoice'] : ''; ?>" class="col-sm-6 col-form-label">Show Discount Amount In AWB Entry Page of Free Form Invoice</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[show_discount_amount_in_awb_entry_page_free_form_invoice]" id="" cols="80" rows="1"><?php echo isset($setting['show_discount_amount_in_awb_entry_page_free_form_invoice']) ? $setting['show_discount_amount_in_awb_entry_page_free_form_invoice'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_discount_amount_in_awb_entry_page_free_form_invoice" value="1" autocomplete="nope" name="docket[show_discount_amount_in_awb_entry_page_free_form_invoice]" <?php echo isset($setting['show_discount_amount_in_awb_entry_page_free_form_invoice']) && $setting['show_discount_amount_in_awb_entry_page_free_form_invoice'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_discount_amount_in_awb_entry_page_free_form_invoice" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['enable_2nd_telephone_number_option_under_consignee_address']) ? $setting_comment['enable_2nd_telephone_number_option_under_consignee_address'] : ''; ?>" class="col-sm-6 col-form-label">Enable 2nd Telephone Number Option Under Consignee Address in AWB Entry</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[enable_2nd_telephone_number_option_under_consignee_address]" id="" cols="80" rows="1"><?php echo isset($setting['enable_2nd_telephone_number_option_under_consignee_address']) ? $setting['enable_2nd_telephone_number_option_under_consignee_address'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_2nd_telephone_number_option_under_consignee_address" value="1" autocomplete="nope" name="docket[enable_2nd_telephone_number_option_under_consignee_address]" <?php echo isset($setting['enable_2nd_telephone_number_option_under_consignee_address']) && $setting['enable_2nd_telephone_number_option_under_consignee_address'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_2nd_telephone_number_option_under_consignee_address" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['copy_awb_number_to_invoice_number_with_option_to_edit_it']) ? $setting_comment['copy_awb_number_to_invoice_number_with_option_to_edit_it'] : ''; ?>" class="col-sm-6 col-form-label">Copy AWB Number To Invoice Number With Option to Edit It</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[copy_awb_number_to_invoice_number_with_option_to_edit_it]" id="" cols="80" rows="1"><?php echo isset($setting['copy_awb_number_to_invoice_number_with_option_to_edit_it']) ? $setting['copy_awb_number_to_invoice_number_with_option_to_edit_it'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="copy_awb_number_to_invoice_number_with_option_to_edit_it" value="1" autocomplete="nope" name="docket[copy_awb_number_to_invoice_number_with_option_to_edit_it]" <?php echo isset($setting['copy_awb_number_to_invoice_number_with_option_to_edit_it']) && $setting['copy_awb_number_to_invoice_number_with_option_to_edit_it'] == 1 ? 'checked' : ''; ?>>
                        <label for="copy_awb_number_to_invoice_number_with_option_to_edit_it" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['enable_free_form_invoice_docket']) ? $setting_comment['enable_free_form_invoice_docket'] : ''; ?>" class="col-sm-6 col-form-label">Enable Free from invoice in AWB</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[enable_free_form_invoice_docket]" id="" cols="80" rows="1"><?php echo isset($setting['enable_free_form_invoice_docket']) ? $setting['enable_free_form_invoice_docket'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_free_form_invoice_docket" value="1" autocomplete="nope" name="docket[enable_free_form_invoice_docket]" <?php echo isset($setting['enable_free_form_invoice_docket']) && $setting['enable_free_form_invoice_docket'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_free_form_invoice_docket" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['enable_invoice_master_range_code_validation_on_entry']) ? $setting_comment['enable_invoice_master_range_code_validation_on_entry'] : ''; ?>" class="col-sm-6 col-form-label">Enable Invoice Master Range Code Validation On Entry Page</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[enable_invoice_master_range_code_validation_on_entry]" id="" cols="80" rows="1"><?php echo isset($setting['enable_invoice_master_range_code_validation_on_entry']) ? $setting['enable_invoice_master_range_code_validation_on_entry'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_invoice_master_range_code_validation_on_entry" value="1" autocomplete="nope" name="docket[enable_invoice_master_range_code_validation_on_entry]" <?php echo isset($setting['enable_invoice_master_range_code_validation_on_entry']) && $setting['enable_invoice_master_range_code_validation_on_entry'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_invoice_master_range_code_validation_on_entry" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['single_forwarding_number_in_multiple_dockets']) ? $setting_comment['single_forwarding_number_in_multiple_dockets'] : ''; ?>" class="col-sm-6 col-form-label">Single Forwarding number in Multiple AWBS</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[single_forwarding_number_in_multiple_dockets]" id="" cols="80" rows="1"><?php echo isset($setting['single_forwarding_number_in_multiple_dockets']) ? $setting['single_forwarding_number_in_multiple_dockets'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="single_forwarding_number_in_multiple_dockets" value="1" autocomplete="nope" name="docket[single_forwarding_number_in_multiple_dockets]" <?php echo isset($setting['single_forwarding_number_in_multiple_dockets']) && $setting['single_forwarding_number_in_multiple_dockets'] == 1 ? 'checked' : ''; ?>>
                        <label for="single_forwarding_number_in_multiple_dockets" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['super_admin_remove_void_docket']) ? $setting_comment['super_admin_remove_void_docket'] : ''; ?>" class="col-sm-6 col-form-label">Only super admins can remove a AWB from void status</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[super_admin_remove_void_docket]" id="" cols="80" rows="1"><?php echo isset($setting['super_admin_remove_void_docket']) ? $setting['super_admin_remove_void_docket'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="super_admin_remove_void_docket" value="1" autocomplete="nope" name="docket[super_admin_remove_void_docket]" <?php echo isset($setting['super_admin_remove_void_docket']) && $setting['super_admin_remove_void_docket'] == 1 ? 'checked' : ''; ?>>
                        <label for="super_admin_remove_void_docket" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['fetch_origin_hub_from_the_docket_in_event_location_while_inscan']) ? $setting_comment['fetch_origin_hub_from_the_docket_in_event_location_while_inscan'] : ''; ?>" class="col-sm-6 col-form-label">Fetch origin hub from the AWB in event location while inscan</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[fetch_origin_hub_from_the_docket_in_event_location_while_inscan]" id="" cols="80" rows="1"><?php echo isset($setting['fetch_origin_hub_from_the_docket_in_event_location_while_inscan']) ? $setting['fetch_origin_hub_from_the_docket_in_event_location_while_inscan'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="fetch_origin_hub_from_the_docket_in_event_location_while_inscan" value="1" autocomplete="nope" name="docket[fetch_origin_hub_from_the_docket_in_event_location_while_inscan]" <?php echo isset($setting['fetch_origin_hub_from_the_docket_in_event_location_while_inscan']) && $setting['fetch_origin_hub_from_the_docket_in_event_location_while_inscan'] == 1 ? 'checked' : ''; ?>>
                        <label for="fetch_origin_hub_from_the_docket_in_event_location_while_inscan" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_declaration_per_awb']) ? $setting_comment['show_declaration_per_awb'] : ''; ?>" class="col-sm-6 col-form-label">Show Declaration Per AWB</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[show_declaration_per_awb]" id="" cols="80" rows="1"><?php echo isset($setting['show_declaration_per_awb']) ? $setting['show_declaration_per_awb'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_declaration_per_awb" value="1" autocomplete="nope" name="docket[show_declaration_per_awb]" <?php echo isset($setting['show_declaration_per_awb']) && $setting['show_declaration_per_awb'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_declaration_per_awb" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_product_service_type_wise']) ? $setting_comment['show_product_service_type_wise'] : ''; ?>" class="col-sm-6 col-form-label">Show product service type wise</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[show_product_service_type_wise]" id="" cols="80" rows="1"><?php echo isset($setting['show_product_service_type_wise']) ? $setting['show_product_service_type_wise'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_product_service_type_wise" value="1" autocomplete="nope" name="docket[show_product_service_type_wise]" <?php echo isset($setting['show_product_service_type_wise']) && $setting['show_product_service_type_wise'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_product_service_type_wise" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['new_free_from_invoice_abc']) ? $setting_comment['new_free_from_invoice_abc'] : ''; ?>" class="col-sm-6 col-form-label">New free from invoice - ABC</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[new_free_from_invoice_abc]" id="" cols="80" rows="1"><?php echo isset($setting['new_free_from_invoice_abc']) ? $setting['new_free_from_invoice_abc'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="new_free_from_invoice_abc" value="1" autocomplete="nope" name="docket[new_free_from_invoice_abc]" <?php echo isset($setting['new_free_from_invoice_abc']) && $setting['new_free_from_invoice_abc'] == 1 ? 'checked' : ''; ?>>
                        <label for="new_free_from_invoice_abc" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['docket_items_for_product_format_nondox']) ? $setting_comment['docket_items_for_product_format_nondox'] : ''; ?>" class="col-sm-6 col-form-label">AWB items mandatory for Product format Nondox</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[docket_items_for_product_format_nondox]" id="" cols="80" rows="1"><?php echo isset($setting['docket_items_for_product_format_nondox']) ? $setting['docket_items_for_product_format_nondox'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="docket_items_for_product_format_nondox" value="1" autocomplete="nope" name="docket[docket_items_for_product_format_nondox]" <?php echo isset($setting['docket_items_for_product_format_nondox']) && $setting['docket_items_for_product_format_nondox'] == 1 ? 'checked' : ''; ?>>
                        <label for="docket_items_for_product_format_nondox" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['mandatory_free_from_invoice_for_product_format_nondox']) ? $setting_comment['mandatory_free_from_invoice_for_product_format_nondox'] : ''; ?>" class="col-sm-6 col-form-label">Mandatory free from invoice for product format nondox</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[mandatory_free_from_invoice_for_product_format_nondox]" id="" cols="80" rows="1"><?php echo isset($setting['mandatory_free_from_invoice_for_product_format_nondox']) ? $setting['mandatory_free_from_invoice_for_product_format_nondox'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="mandatory_free_from_invoice_for_product_format_nondox" value="1" autocomplete="nope" name="docket[mandatory_free_from_invoice_for_product_format_nondox]" <?php echo isset($setting['mandatory_free_from_invoice_for_product_format_nondox']) && $setting['mandatory_free_from_invoice_for_product_format_nondox'] == 1 ? 'checked' : ''; ?>>
                        <label for="mandatory_free_from_invoice_for_product_format_nondox" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['copy_1st_4_nuumber_of_awb_to_invoice_number']) ? $setting_comment['copy_1st_4_nuumber_of_awb_to_invoice_number'] : ''; ?>" class="col-sm-6 col-form-label">Copy AWB Number To Invoice Number With Option to Edit It</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[copy_1st_4_nuumber_of_awb_to_invoice_number]" id="" cols="80" rows="1"><?php echo isset($setting['copy_1st_4_nuumber_of_awb_to_invoice_number']) ? $setting['copy_1st_4_nuumber_of_awb_to_invoice_number'] : ''; ?></textarea>
                    <?php } else { ?>
                        <select name="docket[copy_1st_4_nuumber_of_awb_to_invoice_number]">
                            <option value="">SELECT</option>
                            <option value="1" <?php echo isset($setting['copy_1st_4_nuumber_of_awb_to_invoice_number']) && $setting['copy_1st_4_nuumber_of_awb_to_invoice_number'] == 1 ? 'selected' : ''; ?>>FIRST FOUR</option>
                            <option value="2" <?php echo isset($setting['copy_1st_4_nuumber_of_awb_to_invoice_number']) && $setting['copy_1st_4_nuumber_of_awb_to_invoice_number'] == 2 ? 'selected' : ''; ?>>LAST FIVE</option>
                        </select>
                        <!-- <input type="checkbox" id="copy_1st_4_nuumber_of_awb_to_invoice_number" value="1" autocomplete="nope" name="docket[copy_1st_4_nuumber_of_awb_to_invoice_number]" <?php //echo isset($setting['copy_1st_4_nuumber_of_awb_to_invoice_number']) && $setting['copy_1st_4_nuumber_of_awb_to_invoice_number'] == 1 ? 'checked' : ''; 
                                                                                                                                                                                                ?>>
                    <label for="copy_1st_4_nuumber_of_awb_to_invoice_number" style="height: 10px !important;"> </label> -->
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['dont_allow_back_dated_docket_entries']) ? $setting_comment['dont_allow_back_dated_docket_entries'] : ''; ?>" class="col-sm-6 col-form-label">Don't Allow back dated AWB entries</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[dont_allow_back_dated_docket_entries]" id="" cols="80" rows="1"><?php echo isset($setting['dont_allow_back_dated_docket_entries']) ? $setting['dont_allow_back_dated_docket_entries'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="dont_allow_back_dated_docket_entries" value="1" autocomplete="nope" name="docket[dont_allow_back_dated_docket_entries]" <?php echo isset($setting['dont_allow_back_dated_docket_entries']) && $setting['dont_allow_back_dated_docket_entries'] == 1 ? 'checked' : ''; ?>>
                        <label for="dont_allow_back_dated_docket_entries" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['add_documents_only_in_content_if_product_format_dox']) ? $setting_comment['add_documents_only_in_content_if_product_format_dox'] : ''; ?>" class="col-sm-6 col-form-label">Add documents only in content if product format dox</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[add_documents_only_in_content_if_product_format_dox]" id="" cols="80" rows="1"><?php echo isset($setting['add_documents_only_in_content_if_product_format_dox']) ? $setting['add_documents_only_in_content_if_product_format_dox'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="add_documents_only_in_content_if_product_format_dox" value="1" autocomplete="nope" name="docket[add_documents_only_in_content_if_product_format_dox]" <?php echo isset($setting['add_documents_only_in_content_if_product_format_dox']) && $setting['add_documents_only_in_content_if_product_format_dox'] == 1 ? 'checked' : ''; ?>>
                        <label for="add_documents_only_in_content_if_product_format_dox" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['enable_vendor_rate_l1_l2']) ? $setting_comment['enable_vendor_rate_l1_l2'] : ''; ?>" class="col-sm-6 col-form-label">L1 L2 calc in AWB</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[enable_vendor_rate_l1_l2]" id="" cols="80" rows="1"><?php echo isset($setting['enable_vendor_rate_l1_l2']) ? $setting['enable_vendor_rate_l1_l2'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_vendor_rate_l1_l2" value="1" autocomplete="nope" name="docket[enable_vendor_rate_l1_l2]" <?php echo isset($setting['enable_vendor_rate_l1_l2']) && $setting['enable_vendor_rate_l1_l2'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_vendor_rate_l1_l2" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['add_edit_chargeable_weight_option_in_docket']) ? $setting_comment['add_edit_chargeable_weight_option_in_docket'] : ''; ?>" class="col-sm-6 col-form-label">Add Edit Chargeable Weight Option On Entry Page</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[add_edit_chargeable_weight_option_in_docket]" id="" cols="80" rows="1"><?php echo isset($setting['add_edit_chargeable_weight_option_in_docket']) ? $setting['add_edit_chargeable_weight_option_in_docket'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="add_edit_chargeable_weight_option_in_docket" value="1" autocomplete="nope" name="docket[add_edit_chargeable_weight_option_in_docket]" <?php echo isset($setting['add_edit_chargeable_weight_option_in_docket']) && $setting['add_edit_chargeable_weight_option_in_docket'] == 1 ? 'checked' : ''; ?>>
                        <label for="add_edit_chargeable_weight_option_in_docket" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_abc_address_label']) ? $setting_comment['show_abc_address_label'] : ''; ?>" class="col-sm-6 col-form-label">Show ABC Address Label</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[show_abc_address_label]" id="" cols="80" rows="1"><?php echo isset($setting['show_abc_address_label']) ? $setting['show_abc_address_label'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_abc_address_label" value="1" autocomplete="nope" name="docket[show_abc_address_label]" <?php echo isset($setting['show_abc_address_label']) && $setting['show_abc_address_label'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_abc_address_label" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['remove_shipper_label_from_address_labels']) ? $setting_comment['remove_shipper_label_from_address_labels'] : ''; ?>" class="col-sm-6 col-form-label">Remove Shipper Label From Address Labels</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[remove_shipper_label_from_address_labels]" id="" cols="80" rows="1"><?php echo isset($setting['remove_shipper_label_from_address_labels']) ? $setting['remove_shipper_label_from_address_labels'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="remove_shipper_label_from_address_labels" value="1" autocomplete="nope" name="docket[remove_shipper_label_from_address_labels]" <?php echo isset($setting['remove_shipper_label_from_address_labels']) && $setting['remove_shipper_label_from_address_labels'] == 1 ? 'checked' : ''; ?>>
                        <label for="remove_shipper_label_from_address_labels" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['vendor_reco_by_service']) ? $setting_comment['vendor_reco_by_service'] : ''; ?>" class="col-sm-6 col-form-label">Limit consignee address length</label>

            <div class="col-sm-1">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[vendor_reco_by_service]" id="" cols="80" rows="1"><?php echo isset($setting['vendor_reco_by_service']) ? $setting['vendor_reco_by_service'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="limit_consignee_address_length" value="1" autocomplete="nope" name="docket[limit_consignee_address_length]" <?php echo isset($setting['limit_consignee_address_length']) && $setting['limit_consignee_address_length'] == 1 ? 'checked' : ''; ?>>
                        <label for="limit_consignee_address_length" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
            <?php if ($mode != "add_comments") { ?>
                <div class="col-sm-2">
                    <input type="text" class="form-control" name="docket[consignee_address_length]" value="<?php echo isset($setting['consignee_address_length']) ? $setting['consignee_address_length'] : ''; ?>" />
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['delete_manifest_items_after_docket_delete']) ? $setting_comment['delete_manifest_items_after_docket_delete'] : ''; ?>" class="col-sm-6 col-form-label">If ABW is delete then remove it from manifest as well.</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[delete_manifest_items_after_docket_delete]" id="" cols="80" rows="1"><?php echo isset($setting['delete_manifest_items_after_docket_delete']) ? $setting['delete_manifest_items_after_docket_delete'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="delete_manifest_items_after_docket_delete" value="1" autocomplete="nope" name="docket[delete_manifest_items_after_docket_delete]" <?php echo isset($setting['delete_manifest_items_after_docket_delete']) && $setting['delete_manifest_items_after_docket_delete'] == 1 ? 'checked' : ''; ?>>
                        <label for="delete_manifest_items_after_docket_delete" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['hide_save_to_master_from_app_and_portal']) ? $setting_comment['hide_save_to_master_from_app_and_portal'] : ''; ?>" class="col-sm-6 col-form-label">Hide Save To Master of consignee From Entry And Customer Portal. Don't Show Master Data</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[hide_save_to_master_from_app_and_portal]" id="" cols="80" rows="1"><?php echo isset($setting['hide_save_to_master_from_app_and_portal']) ? $setting['hide_save_to_master_from_app_and_portal'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="hide_save_to_master_from_app_and_portal" value="1" autocomplete="nope" name="docket[hide_save_to_master_from_app_and_portal]" <?php echo isset($setting['hide_save_to_master_from_app_and_portal']) && $setting['hide_save_to_master_from_app_and_portal'] == 1 ? 'checked' : ''; ?>>
                        <label for="hide_save_to_master_from_app_and_portal" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['hide_unit_rate_gst_type_tax_cgst_igst_sgst_and_amount_from_material']) ? $setting_comment['hide_unit_rate_gst_type_tax_cgst_igst_sgst_and_amount_from_material'] : ''; ?>" class="col-sm-6 col-form-label">Hide Unit Rate, GST Type, Tax %, CGST, IGST, SGST and Amount From Materials</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[hide_unit_rate_gst_type_tax_cgst_igst_sgst_and_amount_from_material]" id="" cols="80" rows="1"><?php echo isset($setting['hide_unit_rate_gst_type_tax_cgst_igst_sgst_and_amount_from_material']) ? $setting['hide_unit_rate_gst_type_tax_cgst_igst_sgst_and_amount_from_material'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="hide_unit_rate_gst_type_tax_cgst_igst_sgst_and_amount_from_material" value="1" autocomplete="nope" name="docket[hide_unit_rate_gst_type_tax_cgst_igst_sgst_and_amount_from_material]" <?php echo isset($setting['hide_unit_rate_gst_type_tax_cgst_igst_sgst_and_amount_from_material']) && $setting['hide_unit_rate_gst_type_tax_cgst_igst_sgst_and_amount_from_material'] == 1 ? 'checked' : ''; ?>>
                        <label for="hide_unit_rate_gst_type_tax_cgst_igst_sgst_and_amount_from_material" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['set_origin_hub_from_origin_code_in_docket_entry']) ? $setting_comment['set_origin_hub_from_origin_code_in_docket_entry'] : ''; ?>" class="col-sm-6 col-form-label">Set Origin Hub From Origin Code In AWB Entry</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[set_origin_hub_from_origin_code_in_docket_entry]" id="" cols="80" rows="1"><?php echo isset($setting['set_origin_hub_from_origin_code_in_docket_entry']) ? $setting['set_origin_hub_from_origin_code_in_docket_entry'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="set_origin_hub_from_origin_code_in_docket_entry" value="1" autocomplete="nope" name="docket[set_origin_hub_from_origin_code_in_docket_entry]" <?php echo isset($setting['set_origin_hub_from_origin_code_in_docket_entry']) && $setting['set_origin_hub_from_origin_code_in_docket_entry'] == 1 ? 'checked' : ''; ?>>
                        <label for="set_origin_hub_from_origin_code_in_docket_entry" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['hyperlink_on_forwarding_number_in_awb_grid']) ? $setting_comment['hyperlink_on_forwarding_number_in_awb_grid'] : ''; ?>" class="col-sm-6 col-form-label">Hyperlink on forwarding number in awb grid</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[hyperlink_on_forwarding_number_in_awb_grid]" id="" cols="80" rows="1"><?php echo isset($setting['hyperlink_on_forwarding_number_in_awb_grid']) ? $setting['hyperlink_on_forwarding_number_in_awb_grid'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="hyperlink_on_forwarding_number_in_awb_grid" value="1" autocomplete="nope" name="docket[hyperlink_on_forwarding_number_in_awb_grid]" <?php echo isset($setting['hyperlink_on_forwarding_number_in_awb_grid']) && $setting['hyperlink_on_forwarding_number_in_awb_grid'] == 1 ? 'checked' : ''; ?>>
                        <label for="hyperlink_on_forwarding_number_in_awb_grid" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_dockets_origin_and_destination_hub_wise_only_manifested']) ? $setting_comment['show_dockets_origin_and_destination_hub_wise_only_manifested'] : ''; ?>" class="col-sm-6 col-form-label">Show awbs origin and destination hub wise, only manifested</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[show_dockets_origin_and_destination_hub_wise_only_manifested]" id="" cols="80" rows="1"><?php echo isset($setting['show_dockets_origin_and_destination_hub_wise_only_manifested']) ? $setting['show_dockets_origin_and_destination_hub_wise_only_manifested'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_dockets_origin_and_destination_hub_wise_only_manifested" value="1" autocomplete="nope" name="docket[show_dockets_origin_and_destination_hub_wise_only_manifested]" <?php echo isset($setting['show_dockets_origin_and_destination_hub_wise_only_manifested']) && $setting['show_dockets_origin_and_destination_hub_wise_only_manifested'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_dockets_origin_and_destination_hub_wise_only_manifested" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_customs_grid_for_abc']) ? $setting_comment['show_customs_grid_for_abc'] : ''; ?>" class="col-sm-6 col-form-label">Show Customs grid for ABC</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[show_customs_grid_for_abc]" id="" cols="80" rows="1"><?php echo isset($setting['show_customs_grid_for_abc']) ? $setting['show_customs_grid_for_abc'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_customs_grid_for_abc" value="1" autocomplete="nope" name="docket[show_customs_grid_for_abc]" <?php echo isset($setting['show_customs_grid_for_abc']) && $setting['show_customs_grid_for_abc'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_customs_grid_for_abc" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['make_custom_desc_and_edit_rate_by_default_checked']) ? $setting_comment['make_custom_desc_and_edit_rate_by_default_checked'] : ''; ?>" class="col-sm-6 col-form-label">Make Custom desc and Edit Rate by_default checked In free form Invoice</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[make_custom_desc_and_edit_rate_by_default_checked]" id="" cols="80" rows="1"><?php echo isset($setting['make_custom_desc_and_edit_rate_by_default_checked']) ? $setting['make_custom_desc_and_edit_rate_by_default_checked'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="make_custom_desc_and_edit_rate_by_default_checked" value="1" autocomplete="nope" name="docket[make_custom_desc_and_edit_rate_by_default_checked]" <?php echo isset($setting['make_custom_desc_and_edit_rate_by_default_checked']) && $setting['make_custom_desc_and_edit_rate_by_default_checked'] == 1 ? 'checked' : ''; ?>>
                        <label for="make_custom_desc_and_edit_rate_by_default_checked" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['check_option_in_docket']) ? $setting_comment['check_option_in_docket'] : ''; ?>" class="col-sm-6 col-form-label">Add check option in awb entry page</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[check_option_in_docket]" id="" cols="80" rows="1"><?php echo isset($setting['check_option_in_docket']) ? $setting['check_option_in_docket'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="check_option_in_docket" value="1" autocomplete="nope" name="docket[check_option_in_docket]" <?php echo isset($setting['check_option_in_docket']) && $setting['check_option_in_docket'] == 1 ? 'checked' : ''; ?>>
                        <label for="check_option_in_docket" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_run_number_in_docket']) ? $setting_comment['show_run_number_in_docket'] : ''; ?>" class="col-sm-6 col-form-label">Show Run number in awb</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[show_run_number_in_docket]" id="" cols="80" rows="1"><?php echo isset($setting['show_run_number_in_docket']) ? $setting['show_run_number_in_docket'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_run_number_in_docket" value="1" autocomplete="nope" name="docket[show_run_number_in_docket]" <?php echo isset($setting['show_run_number_in_docket']) && $setting['show_run_number_in_docket'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_run_number_in_docket" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['add_docket_credit_breached']) ? $setting_comment['add_docket_credit_breached'] : ''; ?>" class="col-sm-6 col-form-label">Add awb after credit limit breached</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[add_docket_credit_breached]" id="" cols="80" rows="1"><?php echo isset($setting['add_docket_credit_breached']) ? $setting['add_docket_credit_breached'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="add_docket_credit_breached" value="1" autocomplete="nope" name="docket[add_docket_credit_breached]" <?php echo isset($setting['add_docket_credit_breached']) && $setting['add_docket_credit_breached'] == 1 ? 'checked' : ''; ?>>
                        <label for="add_docket_credit_breached" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['only_superadmin_and_manager_can_update_dockets_if_credit_limit_is_breached']) ? $setting_comment['only_superadmin_and_manager_can_update_dockets_if_credit_limit_is_breached'] : ''; ?>" class="col-sm-6 col-form-label">only super admins and managers roles can update awbs if the credit limit is breached</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[only_superadmin_and_manager_can_update_dockets_if_credit_limit_is_breached]" id="" cols="80" rows="1"><?php echo isset($setting['only_superadmin_and_manager_can_update_dockets_if_credit_limit_is_breached']) ? $setting['only_superadmin_and_manager_can_update_dockets_if_credit_limit_is_breached'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="only_superadmin_and_manager_can_update_dockets_if_credit_limit_is_breached" value="1" autocomplete="nope" name="docket[only_superadmin_and_manager_can_update_dockets_if_credit_limit_is_breached]" <?php echo isset($setting['only_superadmin_and_manager_can_update_dockets_if_credit_limit_is_breached']) && $setting['only_superadmin_and_manager_can_update_dockets_if_credit_limit_is_breached'] == 1 ? 'checked' : ''; ?>>
                        <label for="only_superadmin_and_manager_can_update_dockets_if_credit_limit_is_breached" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>


    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['different_origin_from_shipper']) ? $setting_comment['different_origin_from_shipper'] : ''; ?>" class="col-sm-6 col-form-label">Origin should not be same as shipper's pincode or country</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[different_origin_from_shipper]" id="" cols="80" rows="1"><?php echo isset($setting['different_origin_from_shipper']) ? $setting['different_origin_from_shipper'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="different_origin_from_shipper" value="1" autocomplete="nope" name="docket[different_origin_from_shipper]" <?php echo isset($setting['different_origin_from_shipper']) && $setting['different_origin_from_shipper'] == 1 ? 'checked' : ''; ?>>
                        <label for="different_origin_from_shipper" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['zone_from_shipper_consignee_pincode']) ? $setting_comment['zone_from_shipper_consignee_pincode'] : ''; ?>" class="col-sm-6 col-form-label">Fetch zones from shipper and consignee pincode</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[zone_from_shipper_consignee_pincode]" id="" cols="80" rows="1"><?php echo isset($setting['zone_from_shipper_consignee_pincode']) ? $setting['zone_from_shipper_consignee_pincode'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="zone_from_shipper_consignee_pincode" value="1" autocomplete="nope" name="docket[zone_from_shipper_consignee_pincode]" <?php echo isset($setting['zone_from_shipper_consignee_pincode']) && $setting['zone_from_shipper_consignee_pincode'] == 1 ? 'checked' : ''; ?>>
                        <label for="zone_from_shipper_consignee_pincode" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_bonds_pdf5_format_in_pdf5_at_docket_list']) ? $setting_comment['show_bonds_pdf5_format_in_pdf5_at_docket_list'] : ''; ?>" class="col-sm-6 col-form-label">Show Bonds Pdf5 Format in pdf5 at Docket List</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[show_bonds_pdf5_format_in_pdf5_at_docket_list]" id="" cols="80" rows="1"><?php echo isset($setting['show_bonds_pdf5_format_in_pdf5_at_docket_list']) ? $setting['show_bonds_pdf5_format_in_pdf5_at_docket_list'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_bonds_pdf5_format_in_pdf5_at_docket_list" value="1" autocomplete="nope" name="docket[show_bonds_pdf5_format_in_pdf5_at_docket_list]" <?php echo isset($setting['show_bonds_pdf5_format_in_pdf5_at_docket_list']) && $setting['show_bonds_pdf5_format_in_pdf5_at_docket_list'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_bonds_pdf5_format_in_pdf5_at_docket_list" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_blueline_pdf5_format_in_pdf5_at_docket_list']) ? $setting_comment['show_blueline_pdf5_format_in_pdf5_at_docket_list'] : ''; ?>" class="col-sm-6 col-form-label">Show Blueline Pdf5 Format in pdf5 at Docket List</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[show_blueline_pdf5_format_in_pdf5_at_docket_list]" id="" cols="80" rows="1"><?php echo isset($setting['show_blueline_pdf5_format_in_pdf5_at_docket_list']) ? $setting['show_blueline_pdf5_format_in_pdf5_at_docket_list'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_blueline_pdf5_format_in_pdf5_at_docket_list" value="1" autocomplete="nope" name="docket[show_blueline_pdf5_format_in_pdf5_at_docket_list]" <?php echo isset($setting['show_blueline_pdf5_format_in_pdf5_at_docket_list']) && $setting['show_blueline_pdf5_format_in_pdf5_at_docket_list'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_blueline_pdf5_format_in_pdf5_at_docket_list" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_bhavani_pdf5_format_in_pdf5_at_docket_list']) ? $setting_comment['show_bhavani_pdf5_format_in_pdf5_at_docket_list'] : ''; ?>" class="col-sm-6 col-form-label">Show Bhavani Pdf5 Format in pdf5 at Docket List</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[show_bhavani_pdf5_format_in_pdf5_at_docket_list]" id="" cols="80" rows="1"><?php echo isset($setting['show_bhavani_pdf5_format_in_pdf5_at_docket_list']) ? $setting['show_bhavani_pdf5_format_in_pdf5_at_docket_list'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_bhavani_pdf5_format_in_pdf5_at_docket_list" value="1" autocomplete="nope" name="docket[show_bhavani_pdf5_format_in_pdf5_at_docket_list]" <?php echo isset($setting['show_bhavani_pdf5_format_in_pdf5_at_docket_list']) && $setting['show_bhavani_pdf5_format_in_pdf5_at_docket_list'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_bhavani_pdf5_format_in_pdf5_at_docket_list" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_tsc_pdf6_format_in_pdf6_at_docket_list']) ? $setting_comment['show_tsc_pdf6_format_in_pdf6_at_docket_list'] : ''; ?>" class="col-sm-6 col-form-label">Show TSC Pdf6 Format in pdf6 at Docket List</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[show_tsc_pdf6_format_in_pdf6_at_docket_list]" id="" cols="80" rows="1"><?php echo isset($setting['show_tsc_pdf6_format_in_pdf6_at_docket_list']) ? $setting['show_tsc_pdf6_format_in_pdf6_at_docket_list'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_tsc_pdf6_format_in_pdf6_at_docket_list" value="1" autocomplete="nope" name="docket[show_tsc_pdf6_format_in_pdf6_at_docket_list]" <?php echo isset($setting['show_tsc_pdf6_format_in_pdf6_at_docket_list']) && $setting['show_tsc_pdf6_format_in_pdf6_at_docket_list'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_tsc_pdf6_format_in_pdf6_at_docket_list" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_pannest_pdf6_format_in_pdf6_at_docket_list']) ? $setting_comment['show_pannest_pdf6_format_in_pdf6_at_docket_list'] : ''; ?>" class="col-sm-6 col-form-label">Show Pannest Pdf6 Format in pdf6 at Docket List</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[show_pannest_pdf6_format_in_pdf6_at_docket_list]" id="" cols="80" rows="1"><?php echo isset($setting['show_pannest_pdf6_format_in_pdf6_at_docket_list']) ? $setting['show_pannest_pdf6_format_in_pdf6_at_docket_list'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_pannest_pdf6_format_in_pdf6_at_docket_list" value="1" autocomplete="nope" name="docket[show_pannest_pdf6_format_in_pdf6_at_docket_list]" <?php echo isset($setting['show_pannest_pdf6_format_in_pdf6_at_docket_list']) && $setting['show_pannest_pdf6_format_in_pdf6_at_docket_list'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_pannest_pdf6_format_in_pdf6_at_docket_list" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['block_past_dated_entry_for_awb']) ? $setting_comment['block_past_dated_entry_for_awb'] : ''; ?>" class="col-sm-6 col-form-label">BLOCK PAST DATED ENTRY FOR AWB</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[block_past_dated_entry_for_awb]" id="" cols="80" rows="1"><?php echo isset($setting['block_past_dated_entry_for_awb']) ? $setting['block_past_dated_entry_for_awb'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="block_past_dated_entry_for_awb" value="1" autocomplete="nope" name="docket[block_past_dated_entry_for_awb]" <?php echo isset($setting['block_past_dated_entry_for_awb']) && $setting['block_past_dated_entry_for_awb'] == 1 ? 'checked' : ''; ?>>
                        <label for="block_past_dated_entry_for_awb" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['block_future_dated_entry_for_awb']) ? $setting_comment['block_future_dated_entry_for_awb'] : ''; ?>" class="col-sm-6 col-form-label">BLOCK FUTURE DATED ENTRY FOR AWB</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[block_future_dated_entry_for_awb]" id="" cols="80" rows="1"><?php echo isset($setting['block_future_dated_entry_for_awb']) ? $setting['block_future_dated_entry_for_awb'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="block_future_dated_entry_for_awb" value="1" autocomplete="nope" name="docket[block_future_dated_entry_for_awb]" <?php echo isset($setting['block_future_dated_entry_for_awb']) && $setting['block_future_dated_entry_for_awb'] == 1 ? 'checked' : ''; ?>>
                        <label for="block_future_dated_entry_for_awb" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['add_docket_wise_payment_option_in_awb_entry_page']) ? $setting_comment['add_docket_wise_payment_option_in_awb_entry_page'] : ''; ?>" class="col-sm-6 col-form-label">Add docket wise payment option in awb entry page</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[add_docket_wise_payment_option_in_awb_entry_page]" id="" cols="80" rows="1"><?php echo isset($setting['add_docket_wise_payment_option_in_awb_entry_page']) ? $setting['add_docket_wise_payment_option_in_awb_entry_page'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="add_docket_wise_payment_option_in_awb_entry_page" value="1" autocomplete="nope" name="docket[add_docket_wise_payment_option_in_awb_entry_page]" <?php echo isset($setting['add_docket_wise_payment_option_in_awb_entry_page']) && $setting['add_docket_wise_payment_option_in_awb_entry_page'] == 1 ? 'checked' : ''; ?>>
                        <label for="add_docket_wise_payment_option_in_awb_entry_page" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['entry_no_range']) ? $setting_comment['entry_no_range'] : ''; ?>" class="col-sm-6 col-form-label">Set Entry Number Range</label>
            <div class="col-sm-6">
                <?php if (isset($mode) && $mode == "add_comments") { ?>
                    <textarea name="docket[entry_no_range]" id="" cols="80" rows="1"><?php echo isset($setting['entry_no_range']) ? $setting['entry_no_range'] : ''; ?></textarea>
                <?php } else { ?>
                    <input type="text" class="form-control" name="docket[entry_no_range]" value="<?php echo isset($setting['entry_no_range']) ? $setting['entry_no_range'] : ''; ?>" />
                <?php } ?>

            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['free_text_in_free_form']) ? $setting_comment['free_text_in_free_form'] : ''; ?>" class="col-sm-6 col-form-label">Free Text In Free From Invoice PDF</label>
            <div class="col-sm-6">
                <?php if (isset($mode) && $mode == "add_comments") { ?>
                    <textarea name="docket[free_text_in_free_form]" id="" cols="80" rows="1"><?php echo isset($setting['free_text_in_free_form']) ? $setting['free_text_in_free_form'] : ''; ?></textarea>
                <?php } else { ?>
                    <input type="checkbox" id="free_text_in_free_form" value="1" autocomplete="nope" name="docket[free_text_in_free_form]" <?php echo isset($setting['free_text_in_free_form']) && $setting['free_text_in_free_form'] == 1 ? 'checked' : ''; ?>>
                    <label for="free_text_in_free_form" style="height: 10px !important;"> </label>
                <?php } ?>

            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['free_form_details_bharat_parcel']) ? $setting_comment['free_form_details_bharat_parcel'] : ''; ?>" class="col-sm-6 col-form-label">Free Form Invoice Defaults for Bharat Parcel</label>
            <div class="col-sm-6">
                <?php if (isset($mode) && $mode == "add_comments") { ?>
                    <textarea name="docket[free_form_details_bharat_parcel]" id="" cols="80" rows="1"><?php echo isset($setting['free_form_details_bharat_parcel']) ? $setting['free_form_details_bharat_parcel'] : ''; ?></textarea>
                <?php } else { ?>
                    <input type="checkbox" id="free_form_details_bharat_parcel" value="1" autocomplete="nope" name="docket[free_form_details_bharat_parcel]" <?php echo isset($setting['free_form_details_bharat_parcel']) && $setting['free_form_details_bharat_parcel'] == 1 ? 'checked' : ''; ?>>
                    <label for="free_form_details_bharat_parcel" style="height: 10px !important;"> </label>
                <?php } ?>

            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['send_fedex_reference_number_in_fedex_api']) ? $setting_comment['send_fedex_reference_number_in_fedex_api'] : ''; ?>" class="col-sm-6 col-form-label">Send Reference number from AWB page's FEDEX REF in FEDEX API</label>
            <div class="col-sm-6">
                <?php if (isset($mode) && $mode == "add_comments") { ?>
                    <textarea name="docket[send_fedex_reference_number_in_fedex_api]" id="" cols="80" rows="1"><?php echo isset($setting['send_fedex_reference_number_in_fedex_api']) ? $setting['send_fedex_reference_number_in_fedex_api'] : ''; ?></textarea>
                <?php } else { ?>
                    <input type="checkbox" id="send_fedex_reference_number_in_fedex_api" value="1" autocomplete="nope" name="docket[send_fedex_reference_number_in_fedex_api]" <?php echo isset($setting['send_fedex_reference_number_in_fedex_api']) && $setting['send_fedex_reference_number_in_fedex_api'] == 1 ? 'checked' : ''; ?>>
                    <label for="send_fedex_reference_number_in_fedex_api" style="height: 10px !important;"> </label>
                <?php } ?>

            </div>
        </div>
    </div>

    <!-- <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" class="col-sm-6 col-form-label">Do not multiply PARCEL actual weight and No. of boxes for actual weight</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <input type="checkbox" id="not_multiply_wt_boxes" value="1" autocomplete="nope" name="docket[not_multiply_wt_boxes]" <?php echo isset($setting['not_multiply_wt_boxes']) && $setting['not_multiply_wt_boxes'] == 1 ? 'checked' : ''; ?>>
                    <label for="not_multiply_wt_boxes" style="height: 10px !important;"> </label>
                </div>
            </div>
        </div>
    </div> -->

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['not_multiply_wt_boxes_migrated']) ? $setting_comment['not_multiply_wt_boxes_migrated'] : ''; ?>" class="col-sm-6 col-form-label">Do not multiply PARCEL actual weight and No. of boxes for actual weight FOR MIGRATED AWB (ONLY FOR PANNEST)</label>
            <div class="col-sm-6">
                <div class="checkbox"><?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[not_multiply_wt_boxes_migrated]" id="" cols="80" rows="1"><?php echo isset($setting['not_multiply_wt_boxes_migrated']) ? $setting['not_multiply_wt_boxes_migrated'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="not_multiply_wt_boxes_migrated" value="1" autocomplete="nope" name="docket[not_multiply_wt_boxes_migrated]" <?php echo isset($setting['not_multiply_wt_boxes_migrated']) && $setting['not_multiply_wt_boxes_migrated'] == 1 ? 'checked' : ''; ?>>
                        <label for="not_multiply_wt_boxes_migrated" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>


    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['send_total_booking_report']) ? $setting_comment['send_total_booking_report'] : ''; ?>" class="col-sm-6 col-form-label">Send total Booking report</label>
            <div class="col-sm-6 setting_data">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[send_total_booking_report]" id="" cols="80" rows="1"><?php echo isset($setting['send_total_booking_report']) ? $setting['send_total_booking_report'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="send_total_booking_report" value="1" autocomplete="nope" name="general[send_total_booking_report]" <?php echo isset($setting['send_total_booking_report']) && $setting['send_total_booking_report'] == 1 ? 'checked' : ''; ?>>
                        <label for="send_total_booking_report" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['total_booking_report_email']) ? $setting_comment['total_booking_report_email'] : ''; ?>" class="col-sm-6 col-form-label">Total Booking Report Emails</label>
            <div class="col-sm-6 setting_data">
                <?php if (isset($mode) && $mode == "add_comments") { ?>
                    <textarea name="docket[total_booking_report_email]" id="" cols="80" rows="1"><?php echo isset($setting['total_booking_report_email']) ? $setting['total_booking_report_email'] : ''; ?></textarea>
                <?php } else { ?>
                    <input type="text" class="form-control" name="general[total_booking_report_email]" value="<?php echo isset($setting['total_booking_report_email']) ? $setting['total_booking_report_email'] : ''; ?>" />
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['enable_radio_buttons_for_free_form']) ? $setting_comment['enable_radio_buttons_for_free_form'] : ''; ?>" class="col-sm-6 col-form-label">ENABLE RADIO BUTTONS FOR FREE FORM INVOICE (DTD)</label>
            <div class="col-sm-6">
                <?php if (isset($mode) && $mode == "add_comments") { ?>
                    <textarea name="docket[enable_radio_buttons_for_free_form]" id="" cols="80" rows="1"><?php echo isset($setting['enable_radio_buttons_for_free_form']) ? $setting['enable_radio_buttons_for_free_form'] : ''; ?></textarea>
                <?php } else { ?>
                    <input type="checkbox" id="enable_radio_buttons_for_free_form" value="1" autocomplete="nope" name="docket[enable_radio_buttons_for_free_form]" <?php echo isset($setting['enable_radio_buttons_for_free_form']) && $setting['enable_radio_buttons_for_free_form'] == 1 ? 'checked' : ''; ?>>
                    <label for="enable_radio_buttons_for_free_form" style="height: 10px !important;"> </label>
                <?php } ?>

            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['set_free_form_default_currency_from']) ? $setting_comment['set_free_form_default_currency_from'] : ''; ?>" class="col-sm-6 col-form-label">SET FREE FORM DEFAULT CURRENCY FROM</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[set_free_form_default_currency_from]" id="" cols="80" rows="1"><?php echo isset($setting['set_free_form_default_currency_from']) ? $setting['set_free_form_default_currency_from'] : ''; ?></textarea>
                    <?php } else { ?>
                        <select name="docket[set_free_form_default_currency_from]">
                            <option value="1" <?php echo isset($setting['set_free_form_default_currency_from']) && $setting['set_free_form_default_currency_from'] == 1 ? 'selected' : ''; ?>>APP SETTING</option>
                            <option value="2" <?php echo isset($setting['set_free_form_default_currency_from']) && $setting['set_free_form_default_currency_from'] == 2 ? 'selected' : ''; ?>>SERVICE MASTER</option>
                        </select>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['enable_pincode_api']) ? $setting_comment['enable_pincode_api'] : ''; ?>" class="col-sm-6 col-form-label">Enable Pincode API</label>
            <div class="col-sm-6 setting_data">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[enable_pincode_api]" id="" cols="80" rows="1"><?php echo isset($setting['enable_pincode_api']) ? $setting['enable_pincode_api'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_pincode_api" value="1" autocomplete="nope" name="general[enable_pincode_api]" <?php echo isset($setting['enable_pincode_api']) && $setting['enable_pincode_api'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_pincode_api" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['set_default_unit_type_in_free_form']) ? $setting_comment['set_default_unit_type_in_free_form'] : ''; ?>" class="col-sm-6 col-form-label">Set default Unit type in new awb entry and free form</label>
            <div class="col-sm-6">
                <?php if (isset($mode) && $mode == "add_comments") { ?>
                    <textarea name="docket[set_default_unit_type_in_free_form]" id="" cols="80" rows="1"><?php echo isset($setting['set_default_unit_type_in_free_form']) ? $setting['set_default_unit_type_in_free_form'] : ''; ?></textarea>
                <?php } else { ?>
                    <select name="docket[set_default_unit_type_in_free_form]">
                        <option value="">Select...</option>
                        <?php
                        if (isset($all_unit_type) && is_array($all_unit_type) && count($all_unit_type) > 0) {
                            foreach ($all_unit_type as $gkey => $gvalue) { ?>
                                <option value="<?php echo $gvalue['id'] ?>" <?php echo isset($setting['set_default_unit_type_in_free_form']) && $setting['set_default_unit_type_in_free_form'] == $gvalue['id'] ? 'selected' : ''; ?>><?php echo $gvalue['name'] ?></option>
                        <?php }
                        } ?>
                    </select>
                <?php } ?>

            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['mandate_freeform_invoice_for_shipper_in_product_non_dox']) ? $setting_comment['mandate_freeform_invoice_for_shipper_in_product_non_dox'] : ''; ?>" class="col-sm-6 col-form-label">Mandate Free from invoice for Shipper's country IN and Product Type NONDOX</label>
            <div class="col-sm-6">
                <?php if (isset($mode) && $mode == "add_comments") { ?>
                    <textarea name="docket[mandate_freeform_invoice_for_shipper_in_product_non_dox]" id="" cols="80" rows="1"><?php echo isset($setting['mandate_freeform_invoice_for_shipper_in_product_non_dox']) ? $setting['mandate_freeform_invoice_for_shipper_in_product_non_dox'] : ''; ?></textarea>
                <?php } else { ?>
                    <input type="checkbox" id="mandate_freeform_invoice_for_shipper_in_product_non_dox" value="1" autocomplete="nope" name="docket[mandate_freeform_invoice_for_shipper_in_product_non_dox]" <?php echo isset($setting['mandate_freeform_invoice_for_shipper_in_product_non_dox']) && $setting['mandate_freeform_invoice_for_shipper_in_product_non_dox'] == 1 ? 'checked' : ''; ?>>
                    <label for="mandate_freeform_invoice_for_shipper_in_product_non_dox" style="height: 10px !important;"> </label>
                <?php } ?>

            </div>
        </div>
    </div>


    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['turn_off_default_charge_email']) ? $setting_comment['turn_off_default_charge_email'] : ''; ?>" class="col-sm-6 col-form-label">turn off Default charges emails</label>
            <div class="col-sm-6 setting_data">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[turn_off_default_charge_email]" id="" cols="80" rows="1"><?php echo isset($setting['turn_off_default_charge_email']) ? $setting['turn_off_default_charge_email'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="turn_off_default_charge_email" value="1" autocomplete="nope" name="general[turn_off_default_charge_email]" <?php echo isset($setting['turn_off_default_charge_email']) && $setting['turn_off_default_charge_email'] == 1 ? 'checked' : ''; ?>>
                        <label for="turn_off_default_charge_email" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['mandate_kyc_for_shipper_in_product_non_dox']) ? $setting_comment['mandate_kyc_for_shipper_in_product_non_dox'] : ''; ?>" class="col-sm-6 col-form-label">Mandate KYC for Shipper's country IN and Product Type NONDOX</label>
            <div class="col-sm-6">
                <?php if (isset($mode) && $mode == "add_comments") { ?>
                    <textarea name="docket[mandate_kyc_for_shipper_in_product_non_dox]" id="" cols="80" rows="1"><?php echo isset($setting['mandate_kyc_for_shipper_in_product_non_dox']) ? $setting['mandate_kyc_for_shipper_in_product_non_dox'] : ''; ?></textarea>
                <?php } else { ?>
                    <input type="checkbox" id="mandate_kyc_for_shipper_in_product_non_dox" value="1" autocomplete="nope" name="docket[mandate_kyc_for_shipper_in_product_non_dox]" <?php echo isset($setting['mandate_kyc_for_shipper_in_product_non_dox']) && $setting['mandate_kyc_for_shipper_in_product_non_dox'] == 1 ? 'checked' : ''; ?>>
                    <label for="mandate_kyc_for_shipper_in_product_non_dox" style="height: 10px !important;"> </label>
                <?php } ?>

            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['mandate_upload_doc_for_shipper_in_product_non_dox']) ? $setting_comment['mandate_upload_doc_for_shipper_in_product_non_dox'] : ''; ?>" class="col-sm-6 col-form-label">UPLOAD DOCUMENT Mandatory for Shipper's country IN and Product Type NONDOX</label>
            <div class="col-sm-6">
                <?php if (isset($mode) && $mode == "add_comments") { ?>
                    <textarea name="docket[mandate_upload_doc_for_shipper_in_product_non_dox]" id="" cols="80" rows="1"><?php echo isset($setting['mandate_upload_doc_for_shipper_in_product_non_dox']) ? $setting['mandate_upload_doc_for_shipper_in_product_non_dox'] : ''; ?></textarea>
                <?php } else { ?>
                    <input type="checkbox" id="mandate_upload_doc_for_shipper_in_product_non_dox" value="1" autocomplete="nope" name="docket[mandate_upload_doc_for_shipper_in_product_non_dox]" <?php echo isset($setting['mandate_upload_doc_for_shipper_in_product_non_dox']) && $setting['mandate_upload_doc_for_shipper_in_product_non_dox'] == 1 ? 'checked' : ''; ?>>
                    <label for="mandate_upload_doc_for_shipper_in_product_non_dox" style="height: 10px !important;"> </label>
                <?php } ?>

            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['calculate_vat_in_entry_page_in_aed']) ? $setting_comment['calculate_vat_in_entry_page_in_aed'] : ''; ?>" class="col-sm-6 col-form-label">CALCULATE VAT IN ENTRY PAGE IN AED</label>
            <div class="col-sm-6">
                <?php if (isset($mode) && $mode == "add_comments") { ?>
                    <textarea name="docket[calculate_vat_in_entry_page_in_aed]" id="" cols="80" rows="1"><?php echo isset($setting['calculate_vat_in_entry_page_in_aed']) ? $setting['calculate_vat_in_entry_page_in_aed'] : ''; ?></textarea>
                <?php } else { ?>
                    <input type="checkbox" id="calculate_vat_in_entry_page_in_aed" value="1" autocomplete="nope" name="docket[calculate_vat_in_entry_page_in_aed]" <?php echo isset($setting['calculate_vat_in_entry_page_in_aed']) && $setting['calculate_vat_in_entry_page_in_aed'] == 1 ? 'checked' : ''; ?>>
                    <label for="calculate_vat_in_entry_page_in_aed" style="height: 10px !important;"> </label>
                <?php } ?>

            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['mandate_shipment_date_invoice_number_and_currency_for_non_dox']) ? $setting_comment['mandate_shipment_date_invoice_number_and_currency_for_non_dox'] : ''; ?>" class="col-sm-6 col-form-label">MANDATE SHIPMENT DATE,INVOICE NUMBER AND CURRENCY FOR Product Type NONDOX</label>
            <div class="col-sm-6">
                <?php if (isset($mode) && $mode == "add_comments") { ?>
                    <textarea name="docket[mandate_shipment_date_invoice_number_and_currency_for_non_dox]" id="" cols="80" rows="1"><?php echo isset($setting['mandate_shipment_date_invoice_number_and_currency_for_non_dox']) ? $setting['mandate_shipment_date_invoice_number_and_currency_for_non_dox'] : ''; ?></textarea>
                <?php } else { ?>
                    <input type="checkbox" id="mandate_shipment_date_invoice_number_and_currency_for_non_dox" value="1" autocomplete="nope" name="docket[mandate_shipment_date_invoice_number_and_currency_for_non_dox]" <?php echo isset($setting['mandate_shipment_date_invoice_number_and_currency_for_non_dox']) && $setting['mandate_shipment_date_invoice_number_and_currency_for_non_dox'] == 1 ? 'checked' : ''; ?>>
                    <label for="mandate_shipment_date_invoice_number_and_currency_for_non_dox" style="height: 10px !important;"> </label>
                <?php } ?>

            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['merge_sales_purchase_billing']) ? $setting_comment['merge_sales_purchase_billing'] : ''; ?>" class="col-sm-6 col-form-label">MERGE SALES AND PURCHASE BILLING IN AWB ENTRY</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[merge_sales_purchase_billing]" id="" cols="80" rows="1"><?php echo isset($setting['merge_sales_purchase_billing']) ? $setting['merge_sales_purchase_billing'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="merge_sales_purchase_billing" value="1" autocomplete="nope" name="docket[merge_sales_purchase_billing]" <?php echo isset($setting['merge_sales_purchase_billing']) && $setting['merge_sales_purchase_billing'] == 1 ? 'checked' : ''; ?>>
                        <label for="merge_sales_purchase_billing" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['set_default_shipper_kyc_type_new_awb']) ? $setting_comment['set_default_shipper_kyc_type_new_awb'] : ''; ?>" class="col-sm-6 col-form-label">Set default SHIPPER KYC TYpe in new awb entry </label>
            <div class="col-sm-6">
                <?php if (isset($mode) && $mode == "add_comments") { ?>
                    <textarea name="docket[set_default_shipper_kyc_type_new_awb]" id="" cols="80" rows="1"><?php echo isset($setting['set_default_shipper_kyc_type_new_awb']) ? $setting['set_default_shipper_kyc_type_new_awb'] : ''; ?></textarea>
                <?php } else { ?>
                    <select name="docket[set_default_shipper_kyc_type_new_awb]">
                        <option value="">Select GSTIN Type</option>
                        <?php
                        if (isset($all_gstin_type) && is_array($all_gstin_type) && count($all_gstin_type) > 0) {
                            foreach ($all_gstin_type as $gkey => $gvalue) { ?>
                                <option value="<?php echo $gvalue['id'] ?>" <?php echo isset($setting['set_default_shipper_kyc_type_new_awb']) && $setting['set_default_shipper_kyc_type_new_awb'] == $gvalue['id'] ? 'selected' : ''; ?>><?php echo $gvalue['name'] ?></option>
                        <?php }
                        }
                        ?>
                    </select>
                <?php } ?>

            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['set_default_admin_charge']) ? $setting_comment['set_default_admin_charge'] : ''; ?>" class="col-sm-6 col-form-label">Set Default Admin Charge</label>
            <div class="col-sm-6"><?php if (isset($mode) && $mode == "add_comments") { ?>
                    <textarea name="docket[set_default_admin_charge]" id="" cols="80" rows="1"><?php echo isset($setting['set_default_admin_charge']) ? $setting['set_default_admin_charge'] : ''; ?></textarea>
                <?php } else { ?>
                    <input type="text" class="form-control" name="docket[set_default_admin_charge]" value="<?php echo isset($setting['set_default_admin_charge']) ? $setting['set_default_admin_charge'] : ''; ?>" />
                <?php } ?>
            </div>
        </div>
    </div>

    <!-- <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['single_pc_rate_wise_rate']) ? $setting_comment['single_pc_rate_wise_rate'] : ''; ?>" class="col-sm-6 col-form-label">Single PC rate wise rate</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[single_pc_rate_wise_rate]" id="" cols="80" rows="1"><?php echo isset($setting['single_pc_rate_wise_rate']) ? $setting['single_pc_rate_wise_rate'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="single_pc_rate_wise_rate" value="1" autocomplete="nope" name="docket[single_pc_rate_wise_rate]" <?php echo isset($setting['single_pc_rate_wise_rate']) && $setting['single_pc_rate_wise_rate'] == 1 ? 'checked' : ''; ?>>
                        <label for="single_pc_rate_wise_rate" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div> -->

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['remove_edit_option_from_free_form_desc']) ? $setting_comment['remove_edit_option_from_free_form_desc'] : ''; ?>" class="col-sm-6 col-form-label">Remove edit option from free from description</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[remove_edit_option_from_free_form_desc]" id="" cols="80" rows="1"><?php echo isset($setting['remove_edit_option_from_free_form_desc']) ? $setting['remove_edit_option_from_free_form_desc'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="remove_edit_option_from_free_form_desc" value="1" autocomplete="nope" name="docket[remove_edit_option_from_free_form_desc]" <?php echo isset($setting['remove_edit_option_from_free_form_desc']) && $setting['remove_edit_option_from_free_form_desc'] == 1 ? 'checked' : ''; ?>>
                        <label for="remove_edit_option_from_free_form_desc" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['hide_trn_no_from_shipper_kyc']) ? $setting_comment['hide_trn_no_from_shipper_kyc'] : ''; ?>" class="col-sm-6 col-form-label">trn no. in consignee kyc type</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[hide_trn_no_from_shipper_kyc]" id="" cols="80" rows="1"><?php echo isset($setting['hide_trn_no_from_shipper_kyc']) ? $setting['hide_trn_no_from_shipper_kyc'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="hide_trn_no_from_shipper_kyc" value="1" autocomplete="nope" name="docket[hide_trn_no_from_shipper_kyc]" <?php echo isset($setting['hide_trn_no_from_shipper_kyc']) && $setting['hide_trn_no_from_shipper_kyc'] == 1 ? 'checked' : ''; ?>>
                        <label for="hide_trn_no_from_shipper_kyc" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['get_token_no_from_pickup_sheet']) ? $setting_comment['get_token_no_from_pickup_sheet'] : ''; ?>" class="col-sm-6 col-form-label">Get Token Number From Pickup Sheet</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[get_token_no_from_pickup_sheet]" id="" cols="80" rows="1"><?php echo isset($setting['get_token_no_from_pickup_sheet']) ? $setting['get_token_no_from_pickup_sheet'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="get_token_no_from_pickup_sheet" value="1" autocomplete="nope" name="docket[get_token_no_from_pickup_sheet]" <?php echo isset($setting['get_token_no_from_pickup_sheet']) && $setting['get_token_no_from_pickup_sheet'] == 1 ? 'checked' : ''; ?>>
                        <label for="get_token_no_from_pickup_sheet" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_purchase_billing_details_in_sales_billing']) ? $setting_comment['show_purchase_billing_details_in_sales_billing'] : ''; ?>" class="col-sm-6 col-form-label">Show Purchase Billing Details In Sales Billing</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[show_purchase_billing_details_in_sales_billing]" id="" cols="80" rows="1"><?php echo isset($setting['show_purchase_billing_details_in_sales_billing']) ? $setting['show_purchase_billing_details_in_sales_billing'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_purchase_billing_details_in_sales_billing" value="1" autocomplete="nope" name="docket[show_purchase_billing_details_in_sales_billing]" <?php echo isset($setting['show_purchase_billing_details_in_sales_billing']) && $setting['show_purchase_billing_details_in_sales_billing'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_purchase_billing_details_in_sales_billing" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>


    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['enable_phone_validation']) ? $setting_comment['enable_phone_validation'] : ''; ?>" class="col-sm-6 col-form-label">ENABLE PHONE NUMBER VALIDATION</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <input type="checkbox" id="enable_phone_validation" value="1" autocomplete="nope" name="docket[enable_phone_validation]" <?php echo isset($setting['enable_phone_validation']) && $setting['enable_phone_validation'] == 1 ? 'checked' : ''; ?>>
                    <label for="enable_phone_validation" style="height: 10px !important;"> </label>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['create_awb_from_pickup_sheet']) ? $setting_comment['create_awb_from_pickup_sheet'] : ''; ?>" class="col-sm-6 col-form-label">CREATE AWB FROM PICKUP SHEET</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <input type="checkbox" id="create_awb_from_pickup_sheet" value="1" autocomplete="nope" name="docket[create_awb_from_pickup_sheet]" <?php echo isset($setting['create_awb_from_pickup_sheet']) && $setting['create_awb_from_pickup_sheet'] == 1 ? 'checked' : ''; ?>>
                    <label for="create_awb_from_pickup_sheet" style="height: 10px !important;"> </label>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" class="col-sm-6 col-form-label">SHOW OLD WEIGHT</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <input type="checkbox" id="show_docket_old_wt" value="1" autocomplete="nope" name="docket[show_docket_old_wt]" <?php echo isset($setting['show_docket_old_wt']) && $setting['show_docket_old_wt'] == 1 ? 'checked' : ''; ?>>
                    <label for="show_docket_old_wt" style="height: 10px !important;"> </label>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['pod_import_grandspeed']) ? $setting_comment['pod_import_grandspeed'] : ''; ?>" class="col-sm-6 col-form-label">GRANDSPEED POD IMPORT</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <input type="checkbox" id="pod_import_grandspeed" value="1" autocomplete="nope" name="docket[pod_import_grandspeed]" <?php echo isset($setting['pod_import_grandspeed']) && $setting['pod_import_grandspeed'] == 1 ? 'checked' : ''; ?>>
                    <label for="pod_import_grandspeed" style="height: 10px !important;"> </label>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" class="col-sm-6 col-form-label">CHECK DUPLICATE SHIPPER NAME - CUSTOMER WISE</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <input type="checkbox" id="check_customer_duplicate_shipper" value="1" autocomplete="nope" name="docket[check_customer_duplicate_shipper]" <?php echo isset($setting['check_customer_duplicate_shipper']) && $setting['check_customer_duplicate_shipper'] == 1 ? 'checked' : ''; ?>>
                    <label for="check_customer_duplicate_shipper" style="height: 10px !important;"> </label>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['mandate_hs_code_service_wise']) ? $setting_comment['mandate_hs_code_service_wise'] : ''; ?>" class="col-sm-6 col-form-label">MANDATE HS CODE SERVICE WISE</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <input type="checkbox" id="mandate_hs_code_service_wise" value="1" autocomplete="nope" name="docket[mandate_hs_code_service_wise]" <?php echo isset($setting['mandate_hs_code_service_wise']) && $setting['mandate_hs_code_service_wise'] == 1 ? 'checked' : ''; ?>>
                    <label for="mandate_hs_code_service_wise" style="height: 10px !important;"> </label>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['enable_edit_dial_code']) ? $setting_comment['enable_edit_dial_code'] : ''; ?>" class="col-sm-6 col-form-label">ENABLE EDIT DIAL CODE</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[enable_edit_dial_code]" id="" cols="80" rows="1"><?php echo isset($setting['enable_edit_dial_code']) ? $setting['enable_edit_dial_code'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_edit_dial_code" value="1" autocomplete="nope" name="docket[enable_edit_dial_code]" <?php echo isset($setting['enable_edit_dial_code']) && $setting['enable_edit_dial_code'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_edit_dial_code" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_data_uppercase']) ? $setting_comment['show_data_uppercase'] : ''; ?>" class="col-sm-6 col-form-label">Show AWB Details Uppercase</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[show_data_uppercase]" id="" cols="80" rows="1"><?php echo isset($setting['show_data_uppercase']) ? $setting['show_data_uppercase'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_data_uppercase" value="1" autocomplete="nope" name="docket[show_data_uppercase]" <?php echo isset($setting['show_data_uppercase']) && $setting['show_data_uppercase'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_data_uppercase" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['check_unique_ref_no']) ? $setting_comment['check_unique_ref_no'] : ''; ?>" class="col-sm-6 col-form-label">CHECK UNIQUE REFERENCE NUMBER IN AWB ENTRY</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[check_unique_ref_no]" id="" cols="80" rows="1"><?php echo isset($setting['check_unique_ref_no']) ? $setting['check_unique_ref_no'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="check_unique_ref_no" value="1" autocomplete="nope" name="docket[check_unique_ref_no]" <?php echo isset($setting['check_unique_ref_no']) && $setting['check_unique_ref_no'] == 1 ? 'checked' : ''; ?>>
                        <label for="check_unique_ref_no" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>


    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['shipper_auto_search_filter']) ? $setting_comment['shipper_auto_search_filter'] : ''; ?>" class="col-sm-6 col-form-label">Shipper Autosearch Filter</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[shipper_auto_search_filter]" id="" cols="80" rows="1"><?php echo isset($setting['shipper_auto_search_filter']) ? $setting['shipper_auto_search_filter'] : ''; ?></textarea>
                    <?php } else {
                        if (isset($setting['shipper_auto_search_filter']) && $setting['shipper_auto_search_filter'] != '') {
                            $shipper_auto_search_filter = explode(",", $setting['shipper_auto_search_filter']);
                        } else {
                            $shipper_auto_search_filter = array(0 => "name", "1" => "code", "2" => "company_name");
                        }

                        $filter_array = array(
                            "name" => "NAME",
                            "code" => "CODE",
                            "company_name" => "COMPANY",
                            "contact_no" => "CONTACT NO",
                            "email_id" => "EMAIL ID",
                            "pincode" => "PINCODE",
                            "gstin_no" => "GSTIN NO",
                            "address1" => "ADDRESS LINE 1",
                            "address2" => "ADDRESS LINE 2",
                            "address3" => "ADDRESS LINE 3",
                            "city" => "CITY",
                            "state" => "STATE",
                            "country" => "COUNTRY",
                        );
                    ?>
                        <select class="form-control select_search" name="docket[shipper_auto_search_filter][]" multiple="multiple">
                            <?php
                            foreach ($filter_array as $fkey => $fvalue) { ?>
                                <option value="<?php echo $fkey; ?>" <?php echo in_array($fkey, $shipper_auto_search_filter) ? "selected" : ''; ?>><?php echo $fvalue; ?></option>
                            <?php }
                            ?>
                        </select>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['consignee_auto_search_filter']) ? $setting_comment['consignee_auto_search_filter'] : ''; ?>" class="col-sm-6 col-form-label">consignee Autosearch Filter</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[consignee_auto_search_filter]" id="" cols="80" rows="1"><?php echo isset($setting['consignee_auto_search_filter']) ? $setting['consignee_auto_search_filter'] : ''; ?></textarea>
                    <?php } else {
                        if (isset($setting['consignee_auto_search_filter']) && $setting['consignee_auto_search_filter'] != '') {
                            $consignee_auto_search_filter = explode(",", $setting['consignee_auto_search_filter']);
                        } else {
                            $consignee_auto_search_filter = array(0 => "name", "1" => "code", "2" => "company_name");
                        }
                    ?>
                        <select class="form-control select_search" name="docket[consignee_auto_search_filter][]" multiple="multiple">
                            <?php
                            foreach ($filter_array as $fkey => $fvalue) { ?>
                                <option value="<?php echo $fkey; ?>" <?php echo in_array($fkey, $consignee_auto_search_filter) ? "selected" : ''; ?>><?php echo $fvalue; ?></option>
                            <?php }
                            ?>
                        </select>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['awcc_ups_norks_combine_api_setting']) ? $setting_comment['awcc_ups_norks_combine_api_setting'] : ''; ?>" class="col-sm-6 col-form-label">Show awcc combine api setting in service/vendor</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[awcc_ups_norks_combine_api_setting]" id="" cols="80" rows="1"><?php echo isset($setting['awcc_ups_norks_combine_api_setting']) ? $setting['awcc_ups_norks_combine_api_setting'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="awcc_ups_norks_combine_api_setting" value="1" autocomplete="nope" name="docket[awcc_ups_norks_combine_api_setting]" <?php echo isset($setting['awcc_ups_norks_combine_api_setting']) && $setting['awcc_ups_norks_combine_api_setting'] == 1 ? 'checked' : ''; ?>>
                        <label for="awcc_ups_norks_combine_api_setting" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['dont_update_awb_by_import']) ? $setting_comment['dont_update_awb_by_import'] : ''; ?>" class="col-sm-6 col-form-label">DONT UPDATE AWB BY IMPORT</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[dont_update_awb_by_import]" id="" cols="80" rows="1"><?php echo isset($setting['dont_update_awb_by_import']) ? $setting['dont_update_awb_by_import'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="dont_update_awb_by_import" value="1" autocomplete="nope" name="docket[dont_update_awb_by_import]" <?php echo isset($setting['dont_update_awb_by_import']) && $setting['dont_update_awb_by_import'] == 1 ? 'checked' : ''; ?>>
                        <label for="dont_update_awb_by_import" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['send_free_form_email']) ? $setting_comment['send_free_form_email'] : ''; ?>" class="col-sm-6 col-form-label">Send Free Form email</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[send_free_form_email]" id="" cols="80" rows="1"><?php echo isset($setting['send_free_form_email']) ? $setting['send_free_form_email'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="send_free_form_email" value="1" autocomplete="nope" name="docket[send_free_form_email]" <?php echo isset($setting['send_free_form_email']) && $setting['send_free_form_email'] == 1 ? 'checked' : ''; ?>>
                        <label for="send_free_form_email" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_ch_and_con_weight_to_superadmin_billing']) ? $setting_comment['show_ch_and_con_weight_to_superadmin_billing'] : ''; ?>" class="col-sm-6 col-form-label">SHOW CHARGEABLE AND CONSIGNOR WEIGHT TO
                SUPERADMIN AND BILLING ROLE</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="docket[show_ch_and_con_weight_to_superadmin_billing]" id="" cols="80" rows="1"><?php echo isset($setting['show_ch_and_con_weight_to_superadmin_billing']) ? $setting['show_ch_and_con_weight_to_superadmin_billing'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_ch_and_con_weight_to_superadmin_billing" value="1" autocomplete="nope" name="docket[show_ch_and_con_weight_to_superadmin_billing]" <?php echo isset($setting['show_ch_and_con_weight_to_superadmin_billing']) && $setting['show_ch_and_con_weight_to_superadmin_billing'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_ch_and_con_weight_to_superadmin_billing" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
</div>