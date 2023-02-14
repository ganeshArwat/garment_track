<div class="tab-pane" id="drs-tab">
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['location_hub_wise_drs']) ? $setting_comment['location_hub_wise_drs'] : ''; ?>" class="col-sm-6 col-form-label">Location's Hub wise DRS</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="drs[location_hub_wise_drs]" id="" cols="80" rows="1"><?php echo isset($setting['location_hub_wise_drs']) ? $setting['location_hub_wise_drs'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="location_hub_wise_drs" value="1" autocomplete="nope" name="drs[location_hub_wise_drs]" <?php echo isset($setting['location_hub_wise_drs']) && $setting['location_hub_wise_drs'] == 1 ? 'checked' : ''; ?>>
                        <label for="location_hub_wise_drs" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['dont_check_anything_while_creating_drs']) ? $setting_comment['dont_check_anything_while_creating_drs'] : ''; ?>" class="col-sm-6 col-form-label">Dont check anything while creating DRS</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="drs[dont_check_anything_while_creating_drs]" id="" cols="80" rows="1"><?php echo isset($setting['dont_check_anything_while_creating_drs']) ? $setting['dont_check_anything_while_creating_drs'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="dont_check_anything_while_creating_drs" value="1" autocomplete="nope" name="drs[dont_check_anything_while_creating_drs]" <?php echo isset($setting['dont_check_anything_while_creating_drs']) && $setting['dont_check_anything_while_creating_drs'] == 1 ? 'checked' : ''; ?>>
                        <label for="dont_check_anything_while_creating_drs" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_shipper_name_in_drs_pdf']) ? $setting_comment['show_shipper_name_in_drs_pdf'] : ''; ?>" class="col-sm-6 col-form-label">show shipper name in drs PDF instead of customer name</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="drs[show_shipper_name_in_drs_pdf]" id="" cols="80" rows="1"><?php echo isset($setting['show_shipper_name_in_drs_pdf']) ? $setting['show_shipper_name_in_drs_pdf'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_shipper_name_in_drs_pdf" value="1" autocomplete="nope" name="drs[show_shipper_name_in_drs_pdf]" <?php echo isset($setting['show_shipper_name_in_drs_pdf']) && $setting['show_shipper_name_in_drs_pdf'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_shipper_name_in_drs_pdf" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_customized_drs_pdf']) ? $setting_comment['show_customized_drs_pdf'] : ''; ?>" class="col-sm-6 col-form-label">SHOW CUSTOMIZED DRS PDF</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="drs[show_customized_drs_pdf]" id="" cols="80" rows="1"><?php echo isset($setting['show_customized_drs_pdf']) ? $setting['show_customized_drs_pdf'] : ''; ?></textarea>
                    <?php } else { ?>
                        <select name="drs[show_customized_drs_pdf]">
                            <option value="1" <?php echo isset($setting['show_customized_drs_pdf']) && $setting['show_customized_drs_pdf'] == 1 ? 'selected' : ''; ?>>DEFAULT</option>
                            <option value="2" <?php echo isset($setting['show_customized_drs_pdf']) && $setting['show_customized_drs_pdf'] == 2 ? 'selected' : ''; ?>>BONDS</option>
                            <option value="3" <?php echo isset($setting['show_customized_drs_pdf']) && $setting['show_customized_drs_pdf'] == 3 ? 'selected' : ''; ?>>PANNEST</option>
                            <option value="4" <?php echo isset($setting['show_customized_drs_pdf']) && $setting['show_customized_drs_pdf'] == 4 ? 'selected' : ''; ?>>SSBC</option>
                            <option value="5" <?php echo isset($setting['show_customized_drs_pdf']) && $setting['show_customized_drs_pdf'] == 5 ? 'selected' : ''; ?>>AWCC</option>
                            <option value="6" <?php echo isset($setting['show_customized_drs_pdf']) && $setting['show_customized_drs_pdf'] == 6 ? 'selected' : ''; ?>>GRAND SPEED</option>
                        </select>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['drs_upload_image_option']) ? $setting_comment['drs_upload_image_option'] : ''; ?>" class="col-sm-6 col-form-label">SHOW DRS UPLOAD IMAGE OPTION</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="drs[drs_upload_image_option]" id="" cols="80" rows="1"><?php echo isset($setting['drs_upload_image_option']) ? $setting['drs_upload_image_option'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="drs_upload_image_option" value="1" autocomplete="nope" name="drs[drs_upload_image_option]" <?php echo isset($setting['drs_upload_image_option']) && $setting['drs_upload_image_option'] == 1 ? 'checked' : ''; ?>>
                        <label for="drs_upload_image_option" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>