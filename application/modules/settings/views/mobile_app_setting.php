<div class="tab-pane" id="mobile_app-tab">
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_service_while_inscan_from_mobile_app']) ? $setting_comment['show_service_while_inscan_from_mobile_app'] : ''; ?>" class="col-sm-6 col-form-label">Show service while inscan from mobile app</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="mobile_api[show_service_while_inscan_from_mobile_app]" id="" cols="80" rows="1"><?php echo isset($setting['show_service_while_inscan_from_mobile_app']) ? $setting['show_service_while_inscan_from_mobile_app'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_service_while_inscan_from_mobile_app" value="1" autocomplete="nope" name="mobile_api[show_service_while_inscan_from_mobile_app]" <?php echo isset($setting['show_service_while_inscan_from_mobile_app']) && $setting['show_service_while_inscan_from_mobile_app'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_service_while_inscan_from_mobile_app" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_grand_total_in_mobile_app']) ? $setting_comment['show_grand_total_in_mobile_app'] : ''; ?>" class="col-sm-6 col-form-label">Show Grand Total In Mobile App</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="mobile_api[show_grand_total_in_mobile_app]" id="" cols="80" rows="1"><?php echo isset($setting['show_grand_total_in_mobile_app']) ? $setting['show_grand_total_in_mobile_app'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_grand_total_in_mobile_app" value="1" autocomplete="nope" name="mobile_api[show_grand_total_in_mobile_app]" <?php echo isset($setting['show_grand_total_in_mobile_app']) && $setting['show_grand_total_in_mobile_app'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_grand_total_in_mobile_app" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['hide_cd_no_from_manifest_api_mobile_app']) ? $setting_comment['hide_cd_no_from_manifest_api_mobile_app'] : ''; ?>" class="col-sm-6 col-form-label">Hide CD NO From Manifest API Mobile App</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="mobile_api[hide_cd_no_from_manifest_api_mobile_app]" id="" cols="80" rows="1"><?php echo isset($setting['hide_cd_no_from_manifest_api_mobile_app']) ? $setting['hide_cd_no_from_manifest_api_mobile_app'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="hide_cd_no_from_manifest_api_mobile_app" value="1" autocomplete="nope" name="mobile_api[hide_cd_no_from_manifest_api_mobile_app]" <?php echo isset($setting['hide_cd_no_from_manifest_api_mobile_app']) && $setting['hide_cd_no_from_manifest_api_mobile_app'] == 1 ? 'checked' : ''; ?>>
                        <label for="hide_cd_no_from_manifest_api_mobile_app" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['hide_co_vendor_code_from_manifest_api_mobile_app']) ? $setting_comment['hide_co_vendor_code_from_manifest_api_mobile_app'] : ''; ?>" class="col-sm-6 col-form-label">Hide Co-Vendor From Manifest API Mobile App</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="mobile_api[hide_co_vendor_code_from_manifest_api_mobile_app]" id="" cols="80" rows="1"><?php echo isset($setting['hide_co_vendor_code_from_manifest_api_mobile_app']) ? $setting['hide_co_vendor_code_from_manifest_api_mobile_app'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="hide_co_vendor_code_from_manifest_api_mobile_app" value="1" autocomplete="nope" name="mobile_api[hide_co_vendor_code_from_manifest_api_mobile_app]" <?php echo isset($setting['hide_co_vendor_code_from_manifest_api_mobile_app']) && $setting['hide_co_vendor_code_from_manifest_api_mobile_app'] == 1 ? 'checked' : ''; ?>>
                        <label for="hide_co_vendor_code_from_manifest_api_mobile_app" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['hide_run_number_from_manifest_api_mobile_app']) ? $setting_comment['hide_run_number_from_manifest_api_mobile_app'] : ''; ?>" class="col-sm-6 col-form-label">Hide Run Number From Manifest API Mobile App</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="mobile_api[hide_run_number_from_manifest_api_mobile_app]" id="" cols="80" rows="1"><?php echo isset($setting['hide_run_number_from_manifest_api_mobile_app']) ? $setting['hide_run_number_from_manifest_api_mobile_app'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="hide_run_number_from_manifest_api_mobile_app" value="1" autocomplete="nope" name="mobile_api[hide_run_number_from_manifest_api_mobile_app]" <?php echo isset($setting['hide_run_number_from_manifest_api_mobile_app']) && $setting['hide_run_number_from_manifest_api_mobile_app'] == 1 ? 'checked' : ''; ?>>
                        <label for="hide_run_number_from_manifest_api_mobile_app" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['hide_vendor_delivery_code_from_manifest_api_mobile_app']) ? $setting_comment['hide_vendor_delivery_code_from_manifest_api_mobile_app'] : ''; ?>" class="col-sm-6 col-form-label">Hide Vendor Delivery Code From Manifest API Mobile App</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="mobile_api[hide_vendor_delivery_code_from_manifest_api_mobile_app]" id="" cols="80" rows="1"><?php echo isset($setting['hide_vendor_delivery_code_from_manifest_api_mobile_app']) ? $setting['hide_vendor_delivery_code_from_manifest_api_mobile_app'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="hide_vendor_delivery_code_from_manifest_api_mobile_app" value="1" autocomplete="nope" name="mobile_api[hide_vendor_delivery_code_from_manifest_api_mobile_app]" <?php echo isset($setting['hide_vendor_delivery_code_from_manifest_api_mobile_app']) && $setting['hide_vendor_delivery_code_from_manifest_api_mobile_app'] == 1 ? 'checked' : ''; ?>>
                        <label for="hide_vendor_delivery_code_from_manifest_api_mobile_app" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['hide_vendor_code_from_manifest_api_mobile_app']) ? $setting_comment['hide_vendor_code_from_manifest_api_mobile_app'] : ''; ?>" class="col-sm-6 col-form-label">Hide Vendor Code From Manifest API Mobile App</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="mobile_api[hide_vendor_code_from_manifest_api_mobile_app]" id="" cols="80" rows="1"><?php echo isset($setting['hide_vendor_code_from_manifest_api_mobile_app']) ? $setting['hide_vendor_code_from_manifest_api_mobile_app'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="hide_vendor_code_from_manifest_api_mobile_app" value="1" autocomplete="nope" name="mobile_api[hide_vendor_code_from_manifest_api_mobile_app]" <?php echo isset($setting['hide_vendor_code_from_manifest_api_mobile_app']) && $setting['hide_vendor_code_from_manifest_api_mobile_app'] == 1 ? 'checked' : ''; ?>>
                        <label for="hide_vendor_code_from_manifest_api_mobile_app" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['hide_origin_hub_code_from_manifest_api_mobile_app']) ? $setting_comment['hide_origin_hub_code_from_manifest_api_mobile_app'] : ''; ?>" class="col-sm-6 col-form-label">Hide Origin Hub Code From Manifest API Mobile App</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="mobile_api[hide_origin_hub_code_from_manifest_api_mobile_app]" id="" cols="80" rows="1"><?php echo isset($setting['hide_origin_hub_code_from_manifest_api_mobile_app']) ? $setting['hide_origin_hub_code_from_manifest_api_mobile_app'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="hide_origin_hub_code_from_manifest_api_mobile_app" value="1" autocomplete="nope" name="mobile_api[hide_origin_hub_code_from_manifest_api_mobile_app]" <?php echo isset($setting['hide_origin_hub_code_from_manifest_api_mobile_app']) && $setting['hide_origin_hub_code_from_manifest_api_mobile_app'] == 1 ? 'checked' : ''; ?>>
                        <label for="hide_origin_hub_code_from_manifest_api_mobile_app" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['hide_destination_hub_code_from_manifest_api_mobile_app']) ? $setting_comment['hide_destination_hub_code_from_manifest_api_mobile_app'] : ''; ?>" class="col-sm-6 col-form-label">Hide Destination Hub Code From Manifest API Mobile App</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="mobile_api[hide_destination_hub_code_from_manifest_api_mobile_app]" id="" cols="80" rows="1"><?php echo isset($setting['hide_destination_hub_code_from_manifest_api_mobile_app']) ? $setting['hide_destination_hub_code_from_manifest_api_mobile_app'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="hide_destination_hub_code_from_manifest_api_mobile_app" value="1" autocomplete="nope" name="mobile_api[hide_destination_hub_code_from_manifest_api_mobile_app]" <?php echo isset($setting['hide_destination_hub_code_from_manifest_api_mobile_app']) && $setting['hide_destination_hub_code_from_manifest_api_mobile_app'] == 1 ? 'checked' : ''; ?>>
                        <label for="hide_destination_hub_code_from_manifest_api_mobile_app" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['hide_flight_no_from_manifest_api_mobile_app']) ? $setting_comment['hide_flight_no_from_manifest_api_mobile_app'] : ''; ?>" class="col-sm-6 col-form-label">Hide Flight No From Manifest API Mobile App</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="mobile_api[hide_flight_no_from_manifest_api_mobile_app]" id="" cols="80" rows="1"><?php echo isset($setting['hide_flight_no_from_manifest_api_mobile_app']) ? $setting['hide_flight_no_from_manifest_api_mobile_app'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="hide_flight_no_from_manifest_api_mobile_app" value="1" autocomplete="nope" name="mobile_api[hide_flight_no_from_manifest_api_mobile_app]" <?php echo isset($setting['hide_flight_no_from_manifest_api_mobile_app']) && $setting['hide_flight_no_from_manifest_api_mobile_app'] == 1 ? 'checked' : ''; ?>>
                        <label for="hide_flight_no_from_manifest_api_mobile_app" style="height: 10px !important;"> </label>>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['hide_line_haul_vendor_from_manifest_api_mobile_app']) ? $setting_comment['hide_line_haul_vendor_from_manifest_api_mobile_app'] : ''; ?>" class="col-sm-6 col-form-label">Hide line_haul_vendor From Manifest API Mobile App</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="mobile_api[hide_line_haul_vendor_from_manifest_api_mobile_app]" id="" cols="80" rows="1"><?php echo isset($setting['hide_line_haul_vendor_from_manifest_api_mobile_app']) ? $setting['hide_line_haul_vendor_from_manifest_api_mobile_app'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="hide_line_haul_vendor_from_manifest_api_mobile_app" value="1" autocomplete="nope" name="mobile_api[hide_line_haul_vendor_from_manifest_api_mobile_app]" <?php echo isset($setting['hide_line_haul_vendor_from_manifest_api_mobile_app']) && $setting['hide_line_haul_vendor_from_manifest_api_mobile_app'] == 1 ? 'checked' : ''; ?>>
                        <label for="hide_line_haul_vendor_from_manifest_api_mobile_app" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['hide_master_no_from_manifest_api_mobile_app']) ? $setting_comment['hide_master_no_from_manifest_api_mobile_app'] : ''; ?>" class="col-sm-6 col-form-label">Hide Master No From Manifest API Mobile App</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="mobile_api[hide_master_no_from_manifest_api_mobile_app]" id="" cols="80" rows="1"><?php echo isset($setting['hide_master_no_from_manifest_api_mobile_app']) ? $setting['hide_master_no_from_manifest_api_mobile_app'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="hide_master_no_from_manifest_api_mobile_app" value="1" autocomplete="nope" name="mobile_api[hide_master_no_from_manifest_api_mobile_app]" <?php echo isset($setting['hide_master_no_from_manifest_api_mobile_app']) && $setting['hide_master_no_from_manifest_api_mobile_app'] == 1 ? 'checked' : ''; ?>>
                        <label for="hide_master_no_from_manifest_api_mobile_app" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['hide_master_edi_bag_no_from_manifest_api_mobile_app']) ? $setting_comment['hide_master_edi_bag_no_from_manifest_api_mobile_app'] : ''; ?>" class="col-sm-6 col-form-label">Hide Master EDI Bag No From Manifest API Mobile App</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="mobile_api[hide_master_edi_bag_no_from_manifest_api_mobile_app]" id="" cols="80" rows="1"><?php echo isset($setting['hide_master_edi_bag_no_from_manifest_api_mobile_app']) ? $setting['hide_master_edi_bag_no_from_manifest_api_mobile_app'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="hide_master_edi_bag_no_from_manifest_api_mobile_app" value="1" autocomplete="nope" name="mobile_api[hide_master_edi_bag_no_from_manifest_api_mobile_app]" <?php echo isset($setting['hide_master_edi_bag_no_from_manifest_api_mobile_app']) && $setting['hide_master_edi_bag_no_from_manifest_api_mobile_app'] == 1 ? 'checked' : ''; ?>>
                        <label for="hide_master_edi_bag_no_from_manifest_api_mobile_app" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['upload_pod_details_for_multiple_dockets_in_delivery_api']) ? $setting_comment['upload_pod_details_for_multiple_dockets_in_delivery_api'] : ''; ?>" class="col-sm-6 col-form-label">Upload POD Details for multiple dockets in delivery API</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="mobile_api[upload_pod_details_for_multiple_dockets_in_delivery_api]" id="" cols="80" rows="1"><?php echo isset($setting['upload_pod_details_for_multiple_dockets_in_delivery_api']) ? $setting['upload_pod_details_for_multiple_dockets_in_delivery_api'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="upload_pod_details_for_multiple_dockets_in_delivery_api" value="1" autocomplete="nope" name="mobile_api[upload_pod_details_for_multiple_dockets_in_delivery_api]" <?php echo isset($setting['upload_pod_details_for_multiple_dockets_in_delivery_api']) && $setting['upload_pod_details_for_multiple_dockets_in_delivery_api'] == 1 ? 'checked' : ''; ?>>
                        <label for="upload_pod_details_for_multiple_dockets_in_delivery_api" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>


</div>