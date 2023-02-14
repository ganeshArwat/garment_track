<div class="tab-pane" id="tab-1">
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['intl_manifest']) ? $setting_comment['intl_manifest'] : ''; ?>" class="col-sm-6 col-form-label">International Manifests?</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[intl_manifest]" id="" cols="80" rows="1"><?php echo isset($setting['intl_manifest']) ? $setting['intl_manifest'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="intl_manifest" value="1" autocomplete="nope" name="manifest[intl_manifest]" <?php echo isset($setting['intl_manifest']) && $setting['intl_manifest'] == 1 ? 'checked' : ''; ?>>
                        <label for="intl_manifest" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['domestic_manifest']) ? $setting_comment['domestic_manifest'] : ''; ?>" class="col-sm-6 col-form-label">Domestic Manifests?</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[domestic_manifest]" id="" cols="80" rows="1"><?php echo isset($setting['domestic_manifest']) ? $setting['domestic_manifest'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="domestic_manifest" value="1" autocomplete="nope" name="manifest[domestic_manifest]" <?php echo isset($setting['domestic_manifest']) && $setting['domestic_manifest'] == 1 ? 'checked' : ''; ?>>
                        <label for="domestic_manifest" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['add_run_no_val']) ? $setting_comment['add_run_no_val'] : ''; ?>" class="col-sm-6 col-form-label">Add Run Number Validation From Manifest(ALPHABET-NUMBER) FORMAT <br>(eg.AAA-9999)</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[add_run_no_val]" id="" cols="80" rows="1"><?php echo isset($setting['add_run_no_val']) ? $setting['add_run_no_val'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="add_run_no_val" value="1" autocomplete="nope" name="manifest[add_run_no_val]" <?php echo isset($setting['add_run_no_val']) && $setting['add_run_no_val'] == 1 ? 'checked' : ''; ?>>
                        <label for="add_run_no_val" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['run_code_date']) ? $setting_comment['run_code_date'] : ''; ?>" class="col-sm-6 col-form-label">Auto Generate Run Number from Vendor Code and Date</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[run_code_date]" id="" cols="80" rows="1"><?php echo isset($setting['run_code_date']) ? $setting['run_code_date'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="run_code_date" value="1" autocomplete="nope" name="manifest[run_code_date]" <?php echo isset($setting['run_code_date']) && $setting['run_code_date'] == 1 ? 'checked' : ''; ?>>
                        <label for="run_code_date" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['run_code_sr']) ? $setting_comment['run_code_sr'] : ''; ?>" class="col-sm-6 col-form-label">Auto Generate Run Number from Vendor Code and Sr No</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[run_code_sr]" id="" cols="80" rows="1"><?php echo isset($setting['run_code_sr']) ? $setting['run_code_sr'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="run_code_sr" value="1" autocomplete="nope" name="manifest[run_code_sr]" <?php echo isset($setting['run_code_sr']) && $setting['run_code_sr'] == 1 ? 'checked' : ''; ?>>
                        <label for="run_code_sr" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>

    <!-- <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" class="col-sm-6 col-form-label">Remove Run Number Validation From Manifest</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <input type="checkbox" id="remove_run_valid" value="1" autocomplete="nope" name="manifest[remove_run_valid]" <?php echo isset($setting['remove_run_valid']) && $setting['remove_run_valid'] == 1 ? 'checked' : ''; ?>>
                    <label for="remove_run_valid" style="height: 10px !important;"> </label>
                </div>
            </div>
        </div>
    </div> -->


    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['forwarding_in_pdf']) ? $setting_comment['forwarding_in_pdf'] : ''; ?>" class="col-sm-6 col-form-label">Add forwarding number in manifest pdf</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[forwarding_in_pdf]" id="" cols="80" rows="1"><?php echo isset($setting['forwarding_in_pdf']) ? $setting['forwarding_in_pdf'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="forwarding_in_pdf" value="1" autocomplete="nope" name="manifest[forwarding_in_pdf]" <?php echo isset($setting['forwarding_in_pdf']) && $setting['forwarding_in_pdf'] == 1 ? 'checked' : ''; ?>>
                        <label for="forwarding_in_pdf" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['manifest_pdf_show_customer_name']) ? $setting_comment['manifest_pdf_show_customer_name'] : ''; ?>" class="col-sm-6 col-form-label">Show Customer Name Instead Of Shipper In Manifest PDF</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[manifest_pdf_show_customer_name]" id="" cols="80" rows="1"><?php echo isset($setting['manifest_pdf_show_customer_name']) ? $setting['manifest_pdf_show_customer_name'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="manifest_pdf_show_customer_name" value="1" autocomplete="nope" name="manifest[manifest_pdf_show_customer_name]" <?php echo isset($setting['manifest_pdf_show_customer_name']) && $setting['manifest_pdf_show_customer_name'] == 1 ? 'checked' : ''; ?>>
                        <label for="manifest_pdf_show_customer_name" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="col-12">
                                        <div class="form-group row">
                                            <label for="cust_id" class="col-sm-6 col-form-label">Show Dropdown For Destination Hub In Manifest</label>
                                            <div class="col-sm-6">
                                                <div class="checkbox">
                                                    <input type="checkbox" id="manifest_dest_hub_show" value="1" autocomplete="nope" name="manifest[manifest_dest_hub_show]" <?php echo isset($setting['manifest_dest_hub_show']) && $setting['manifest_dest_hub_show'] == 1 ? 'checked' : ''; ?>>
                                                    <label for="manifest_dest_hub_show" style="height: 10px !important;"> </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['manifest_name_show_hub']) ? $setting_comment['manifest_name_show_hub'] : ''; ?>" class="col-sm-6 col-form-label">Show destination, origin hubs in drop down and show name not code in manifest</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[manifest_name_show_hub]" id="" cols="80" rows="1"><?php echo isset($setting['manifest_name_show_hub']) ? $setting['manifest_name_show_hub'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="manifest_name_show_hub" value="1" autocomplete="nope" name="manifest[manifest_name_show_hub]" <?php echo isset($setting['manifest_name_show_hub']) && $setting['manifest_name_show_hub'] == 1 ? 'checked' : ''; ?>>
                        <label for="manifest_name_show_hub" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['manifest_by_forwording_no']) ? $setting_comment['manifest_by_forwording_no'] : ''; ?>" class="col-sm-6 col-form-label">create manifest by forwarding number in software</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[manifest_by_forwording_no]" id="" cols="80" rows="1"><?php echo isset($setting['manifest_by_forwording_no']) ? $setting['manifest_by_forwording_no'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="manifest_by_forwording_no" value="1" autocomplete="nope" name="manifest[manifest_by_forwording_no]" <?php echo isset($setting['manifest_by_forwording_no']) && $setting['manifest_by_forwording_no'] == 1 ? 'checked' : ''; ?>>
                        <label for="manifest_by_forwording_no" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['manifest_auto_generate_edi_bag']) ? $setting_comment['manifest_auto_generate_edi_bag'] : ''; ?>" class="col-sm-6 col-form-label">Auto Generate EDI Bag No</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[manifest_auto_generate_edi_bag]" id="" cols="80" rows="1"><?php echo isset($setting['manifest_auto_generate_edi_bag']) ? $setting['manifest_auto_generate_edi_bag'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="manifest_auto_generate_edi_bag" value="1" autocomplete="nope" name="manifest[manifest_auto_generate_edi_bag]" <?php echo isset($setting['manifest_auto_generate_edi_bag']) && $setting['manifest_auto_generate_edi_bag'] == 1 ? 'checked' : ''; ?>>
                        <label for="manifest_auto_generate_edi_bag" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>


    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['master_edi_range']) ? $setting_comment['master_edi_range'] : ''; ?>" class="col-sm-6 col-form-label">Set Master EDI Bag NO Range</label>
            <div class="col-sm-6">
                <?php if (isset($mode) && $mode == "add_comments") { ?>
                    <textarea name="manifest[master_edi_range]" id="" cols="80" rows="1"><?php echo isset($setting['master_edi_range']) ? $setting['master_edi_range'] : ''; ?></textarea>
                <?php } else { ?>
                    <div class="col-sm-4" style="display: inline;float: left;">
                        <div class="form-group row">
                            <label class="col-sm-6">Prefix</label>
                            <input type="text" class="form-control col-sm-6" name="manifest[master_edi_prefix]" value="<?php echo $setting['master_edi_prefix'] ? $setting['master_edi_prefix'] : ''; ?>" />
                        </div>
                    </div>
                    <div class="col-sm-8" style="display: inline;float: right;">
                        <div class="form-group row">
                            <label class="col-sm-4">Range</label>
                            <input type="text" class="form-control col-sm-8" name="manifest[master_edi_range]" value="<?php echo $setting['master_edi_range'] ? $setting['master_edi_range'] : ''; ?>" />
                        </div>
                    </div>
                <?php } ?>

            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['copy_run_number_to_master_edi_bad_no']) ? $setting_comment['copy_run_number_to_master_edi_bad_no'] : ''; ?>" class="col-sm-6 col-form-label">COPY RUN NUMBER TO MASTER EDI BAG NO</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[copy_run_number_to_master_edi_bad_no]" id="" cols="80" rows="1"><?php echo isset($setting['copy_run_number_to_master_edi_bad_no']) ? $setting['copy_run_number_to_master_edi_bad_no'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="copy_run_number_to_master_edi_bad_no" value="1" autocomplete="nope" name="manifest[copy_run_number_to_master_edi_bad_no]" <?php echo isset($setting['copy_run_number_to_master_edi_bad_no']) && $setting['copy_run_number_to_master_edi_bad_no'] == 1 ? 'checked' : ''; ?>>
                        <label for="copy_run_number_to_master_edi_bad_no" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <!-- <div class="col-12">
                                        <div class="form-group row">
                                            <label for="cust_id" class="col-sm-6 col-form-label">Customized Manifest Report For OM</label>
                                            <div class="col-sm-6">
                                                <div class="checkbox">
                                                    <div class="checkbox">
                                                        <input type="checkbox" id="manifest_om_report" value="1" autocomplete="nope" name="manifest[manifest_om_report]" <?php echo isset($setting['manifest_om_report']) && $setting['manifest_om_report'] == 1 ? 'checked' : ''; ?>>
                                                        <label for="manifest_om_report" style="height: 10px !important;"> </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group row">
                                            <label for="cust_id" class="col-sm-6 col-form-label">Customized Manifest Report For PACIFIC</label>
                                            <div class="col-sm-6">
                                                <div class="checkbox">
                                                    <input type="checkbox" id="manifest_pacific_report" value="1" autocomplete="nope" name="manifest[manifest_pacific_report]" <?php echo isset($setting['manifest_pacific_report']) && $setting['manifest_pacific_report'] == 1 ? 'checked' : ''; ?>>
                                                    <label for="manifest_pacific_report" style="height: 10px !important;"> </label>
                                                </div>

                                            </div>
                                        </div>
                                    </div> -->
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['manifest_track_option_default']) ? $setting_comment['manifest_track_option_default'] : ''; ?>" class="col-sm-6 col-form-label">by default track by option in manifest</label>
            <div class="col-sm-6">
                <?php if (isset($mode) && $mode == "add_comments") { ?>
                    <textarea name="manifest[manifest_track_option_default]" id="" cols="80" rows="1"><?php echo isset($setting['manifest_track_option_default']) ? $setting['manifest_track_option_default'] : ''; ?></textarea>
                <?php } else { ?>
                    <select name="manifest[manifest_track_option_default]">
                        <option value="1" <?php echo isset($setting['manifest_track_option_default']) && $setting['manifest_track_option_default'] == 1 ? 'selected' : ''; ?>>AWB number</option>
                        <option value="2" <?php echo isset($setting['manifest_track_option_default']) && $setting['manifest_track_option_default'] == 2 ? 'selected' : ''; ?>>Parcel number</option>
                        <option value="3" <?php echo isset($setting['manifest_track_option_default']) && $setting['manifest_track_option_default'] == 3 ? 'selected' : ''; ?>>Forwarding Number</option>
                    </select>
                <?php } ?>

            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['manifest_vendor_wise']) ? $setting_comment['manifest_vendor_wise'] : ''; ?>" class="col-sm-6 col-form-label">Vendor wise Manifest</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[manifest_vendor_wise]" id="" cols="80" rows="1"><?php echo isset($setting['manifest_vendor_wise']) ? $setting['manifest_vendor_wise'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="manifest_vendor_wise" value="1" autocomplete="nope" name="manifest[manifest_vendor_wise]" <?php echo isset($setting['manifest_vendor_wise']) && $setting['manifest_vendor_wise'] == 1 ? 'checked' : ''; ?>>
                        <label for="manifest_vendor_wise" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['manifesting_location_hub_mapping_wise']) ? $setting_comment['manifesting_location_hub_mapping_wise'] : ''; ?>" class="col-sm-6 col-form-label">Manifesting location hub mapping wise</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[manifesting_location_hub_mapping_wise]" id="" cols="80" rows="1"><?php echo isset($setting['manifesting_location_hub_mapping_wise']) ? $setting['manifesting_location_hub_mapping_wise'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="manifesting_location_hub_mapping_wise" value="1" autocomplete="nope" name="manifest[manifesting_location_hub_mapping_wise]" <?php echo isset($setting['manifesting_location_hub_mapping_wise']) && $setting['manifesting_location_hub_mapping_wise'] == 1 ? 'checked' : ''; ?>>
                        <label for="manifesting_location_hub_mapping_wise" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['validate_inscan_parcel_before_manifest']) ? $setting_comment['validate_inscan_parcel_before_manifest'] : ''; ?>" class="col-sm-6 col-form-label">Validate Inscanned Parcel Before create manifest</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[validate_inscan_parcel_before_manifest]" id="" cols="80" rows="1"><?php echo isset($setting['validate_inscan_parcel_before_manifest']) ? $setting['validate_inscan_parcel_before_manifest'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="validate_inscan_parcel_before_manifest" value="1" autocomplete="nope" name="manifest[validate_inscan_parcel_before_manifest]" <?php echo isset($setting['validate_inscan_parcel_before_manifest']) && $setting['validate_inscan_parcel_before_manifest'] == 1 ? 'checked' : ''; ?>>
                        <label for="validate_inscan_parcel_before_manifest" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['dont_check_manifest_create']) ? $setting_comment['dont_check_manifest_create'] : ''; ?>" class="col-sm-6 col-form-label">don't check anything while creating manifest</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[dont_check_manifest_create]" id="" cols="80" rows="1"><?php echo isset($setting['dont_check_manifest_create']) ? $setting['dont_check_manifest_create'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="dont_check_manifest_create" value="1" autocomplete="nope" name="manifest[dont_check_manifest_create]" <?php echo isset($setting['dont_check_manifest_create']) && $setting['dont_check_manifest_create'] == 1 ? 'checked' : ''; ?>>
                        <label for="dont_check_manifest_create" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['customized_manifest_pdf']) ? $setting_comment['customized_manifest_pdf'] : ''; ?>" class="col-sm-6 col-form-label">Customized Manifest PDF</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[customized_manifest_pdf]" id="" cols="80" rows="1"><?php echo isset($setting['customized_manifest_pdf']) ? $setting['customized_manifest_pdf'] : ''; ?></textarea>
                    <?php } else { ?>
                        <select name="manifest[customized_manifest_pdf]">
                            <option value="1" <?php echo isset($setting['customized_manifest_pdf']) && $setting['customized_manifest_pdf'] == 1  ? 'selected' : ''; ?>>DEFAULT</option>
                            <option value="2" <?php echo isset($setting['customized_manifest_pdf']) && $setting['customized_manifest_pdf'] == 2 ? 'selected' : ''; ?>>ORBIT</option>
                            <option value="3" <?php echo isset($setting['customized_manifest_pdf']) && $setting['customized_manifest_pdf'] == 3 ? 'selected' : ''; ?>>PHOENIX</option>
                            <option value="4" <?php echo isset($setting['customized_manifest_pdf']) && $setting['customized_manifest_pdf'] == 4 ? 'selected' : ''; ?>>HUDA</option>
                            <option value="5" <?php echo isset($setting['customized_manifest_pdf']) && $setting['customized_manifest_pdf'] == 5 ? 'selected' : ''; ?>>BONDS</option>
                            <option value="6" <?php echo isset($setting['customized_manifest_pdf']) && $setting['customized_manifest_pdf'] == 6 ? 'selected' : ''; ?>>PANNEST</option>
                            <option value="7" <?php echo isset($setting['customized_manifest_pdf']) && $setting['customized_manifest_pdf'] == 7 ? 'selected' : ''; ?>>ONS</option>
                            <option value="8" <?php echo isset($setting['customized_manifest_pdf']) && $setting['customized_manifest_pdf'] == 8 ? 'selected' : ''; ?>>SUPREME</option>
                            <option value="9" <?php echo isset($setting['customized_manifest_pdf']) && $setting['customized_manifest_pdf'] == 9 ? 'selected' : ''; ?>>GWE</option>
                            <option value="10" <?php echo isset($setting['customized_manifest_pdf']) && $setting['customized_manifest_pdf'] == 10 ? 'selected' : ''; ?>>GRANSPEED</option>
                        </select>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['edi_from']) ? $setting_comment['edi_from'] : ''; ?>" class="col-sm-6 col-form-label">EDI FROM</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[edi_from]" id="" cols="80" rows="1"><?php echo isset($setting['edi_from']) ? $setting['edi_from'] : ''; ?></textarea>
                    <?php } else { ?>
                        <select name="manifest[edi_from]">
                            <option value="1" <?php echo isset($setting['edi_from']) && $setting['edi_from'] == 1  ? 'selected' : ''; ?>>COMPANY</option>
                            <option value="2" <?php echo isset($setting['edi_from']) && $setting['edi_from'] == 2 ? 'selected' : ''; ?>>LICENSE</option>
                        </select>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['edi_to']) ? $setting_comment['edi_to'] : ''; ?>" class="col-sm-6 col-form-label">EDI TO</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[edi_to]" id="" cols="80" rows="1"><?php echo isset($setting['edi_to']) ? $setting['edi_to'] : ''; ?></textarea>
                    <?php } else { ?>
                        <select name="manifest[edi_to]">
                            <option value="1" <?php echo isset($setting['edi_to']) && $setting['edi_to'] == 1  ? 'selected' : ''; ?>>VENDOR</option>
                            <option value="2" <?php echo isset($setting['edi_to']) && $setting['edi_to'] == 2 ? 'selected' : ''; ?>>FORWARDER</option>
                        </select>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['edi_bag_master_pdf']) ? $setting_comment['edi_bag_master_pdf'] : ''; ?>" class="col-sm-6 col-form-label">EDI BAG MASTER PDF</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[edi_bag_master_pdf]" id="" cols="80" rows="1"><?php echo isset($setting['edi_bag_master_pdf']) ? $setting['edi_bag_master_pdf'] : ''; ?></textarea>
                    <?php } else { ?>
                        <select name="manifest[edi_bag_master_pdf]">
                            <option value="1" <?php echo isset($setting['edi_bag_master_pdf']) && $setting['edi_bag_master_pdf'] == 1  ? 'selected' : ''; ?>>DEFAULT</option>
                            <option value="2" <?php echo isset($setting['edi_bag_master_pdf']) && $setting['edi_bag_master_pdf'] == 2 ? 'selected' : ''; ?>>AWCC</option>
                            <option value="3" <?php echo isset($setting['edi_bag_master_pdf']) && $setting['edi_bag_master_pdf'] == 3 ? 'selected' : ''; ?>>OM INTL</option>
                            <option value="4" <?php echo isset($setting['edi_bag_master_pdf']) && $setting['edi_bag_master_pdf'] == 4 ? 'selected' : ''; ?>>UC </option>
                        </select>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_complete_invoice_in_edi_report']) ? $setting_comment['show_complete_invoice_in_edi_report'] : ''; ?>" class="col-sm-6 col-form-label">Show Complete Invoice Number in EDI Report</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[show_complete_invoice_in_edi_report]" id="" cols="80" rows="1"><?php echo isset($setting['show_complete_invoice_in_edi_report']) ? $setting['show_complete_invoice_in_edi_report'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_complete_invoice_in_edi_report" value="1" autocomplete="nope" name="manifest[show_complete_invoice_in_edi_report]" <?php echo isset($setting['show_complete_invoice_in_edi_report']) && $setting['show_complete_invoice_in_edi_report'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_complete_invoice_in_edi_report" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_4_line_item_edi_report']) ? $setting_comment['show_4_line_item_edi_report'] : ''; ?>" class="col-sm-6 col-form-label">Show Only 4 line items content in EDI report</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[show_4_line_item_edi_report]" id="" cols="80" rows="1"><?php echo isset($setting['show_4_line_item_edi_report']) ? $setting['show_4_line_item_edi_report'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_4_line_item_edi_report" value="1" autocomplete="nope" name="manifest[show_4_line_item_edi_report]" <?php echo isset($setting['show_4_line_item_edi_report']) && $setting['show_4_line_item_edi_report'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_4_line_item_edi_report" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['mumbai_report']) ? $setting_comment['mumbai_report'] : ''; ?>" class="col-sm-6 col-form-label">Mumbai Manifest Export</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[mumbai_report]" id="" cols="80" rows="1"><?php echo isset($setting['mumbai_report']) ? $setting['mumbai_report'] : ''; ?></textarea>
                    <?php } else { ?>
                        <select name="manifest[mumbai_report]">
                            <option value="0" <?php echo isset($setting['mumbai_report']) && $setting['mumbai_report'] == 0  ? 'selected' : ''; ?>>SELECT</option>
                            <option value="1" <?php echo isset($setting['mumbai_report']) && $setting['mumbai_report'] == 1  ? 'selected' : ''; ?>>DEFAULT</option>
                            <option value="2" <?php echo isset($setting['mumbai_report']) && $setting['mumbai_report'] == 2  ? 'selected' : ''; ?>>OM Export</option>
                            <option value="3" <?php echo isset($setting['mumbai_report']) && $setting['mumbai_report'] == 3  ? 'selected' : ''; ?>>STARLINE Export</option>
                            <option value="4" <?php echo isset($setting['mumbai_report']) && $setting['mumbai_report'] == 4  ? 'selected' : ''; ?>>AWCC Export</option>
                            <option value="5" <?php echo isset($setting['mumbai_report']) && $setting['mumbai_report'] == 5  ? 'selected' : ''; ?>>NAVALI INT</option>
                        </select>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>



    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['delhi_report']) ? $setting_comment['delhi_report'] : ''; ?>" class="col-sm-6 col-form-label">Delhi Manifest Export</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[delhi_report]" id="" cols="80" rows="1"><?php echo isset($setting['delhi_report']) ? $setting['delhi_report'] : ''; ?></textarea>
                    <?php } else { ?>
                        <select name="manifest[delhi_report]">
                            <option value="0" <?php echo isset($setting['delhi_report']) && $setting['delhi_report'] == 0  ? 'selected' : ''; ?>>SELECT</option>
                            <option value="1" <?php echo isset($setting['delhi_report']) && $setting['delhi_report'] == 1  ? 'selected' : ''; ?>>CORPOCO</option>
                        </select>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['dxb_report']) ? $setting_comment['dxb_report'] : ''; ?>" class="col-sm-6 col-form-label">DXB Manifest Export</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[dxb_report]" id="" cols="80" rows="1"><?php echo isset($setting['dxb_report']) ? $setting['dxb_report'] : ''; ?></textarea>
                    <?php } else { ?>
                        <select name="manifest[dxb_report]">
                            <option value="0" <?php echo isset($setting['dxb_report']) && $setting['dxb_report'] == 0  ? 'selected' : ''; ?>>SELECT</option>
                            <option value="1" <?php echo isset($setting['dxb_report']) && $setting['dxb_report'] == 1  ? 'selected' : ''; ?>>ELS</option>
                            <option value="2" <?php echo isset($setting['dxb_report']) && $setting['dxb_report'] == 2  ? 'selected' : ''; ?>>UC</option>
                        </select>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['lhr_report']) ? $setting_comment['lhr_report'] : ''; ?>" class="col-sm-6 col-form-label">LHR Manifest Export</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[lhr_report]" id="" cols="80" rows="1"><?php echo isset($setting['lhr_report']) ? $setting['lhr_report'] : ''; ?></textarea>
                    <?php } else { ?>
                        <select name="manifest[lhr_report]">
                            <option value="0" <?php echo isset($setting['lhr_report']) && $setting['lhr_report'] == 0  ? 'selected' : ''; ?>>SELECT</option>
                            <option value="1" <?php echo isset($setting['lhr_report']) && $setting['lhr_report'] == 1  ? 'selected' : ''; ?>>ELS</option>
                        </select>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['canada_report']) ? $setting_comment['canada_report'] : ''; ?>" class="col-sm-6 col-form-label">CANADA Manifest Export</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[canada_report]" id="" cols="80" rows="1"><?php echo isset($setting['canada_report']) ? $setting['canada_report'] : ''; ?></textarea>
                    <?php } else { ?>
                        <select name="manifest[canada_report]">
                            <option value="0" <?php echo isset($setting['canada_report']) && $setting['canada_report'] == 0  ? 'selected' : ''; ?>>SELECT</option>
                            <option value="1" <?php echo isset($setting['canada_report']) && $setting['canada_report'] == 1  ? 'selected' : ''; ?>>ELS</option>
                        </select>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['aus_report']) ? $setting_comment['aus_report'] : ''; ?>" class="col-sm-6 col-form-label">AUS Manifest Export</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[aus_report]" id="" cols="80" rows="1"><?php echo isset($setting['aus_report']) ? $setting['aus_report'] : ''; ?></textarea>
                    <?php } else { ?>
                        <select name="manifest[aus_report]">
                            <option value="0" <?php echo isset($setting['aus_report']) && $setting['aus_report'] == 0  ? 'selected' : ''; ?>>SELECT</option>
                            <option value="1" <?php echo isset($setting['aus_report']) && $setting['aus_report'] == 1  ? 'selected' : ''; ?>>ELS</option>
                        </select>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['customized_manifest_excel']) ? $setting_comment['customized_manifest_excel'] : ''; ?>" class="col-sm-6 col-form-label">Customized Manifest REPORT 1</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[customized_manifest_excel]" id="" cols="80" rows="1"><?php echo isset($setting['customized_manifest_excel']) ? $setting['customized_manifest_excel'] : ''; ?></textarea>
                    <?php } else { ?>
                        <select name="manifest[customized_manifest_excel]">
                            <option value="0" <?php echo isset($setting['customized_manifest_excel']) && $setting['customized_manifest_excel'] == 0  ? 'selected' : ''; ?>>SELECT</option>
                            <option value="1" <?php echo isset($setting['customized_manifest_excel']) && $setting['customized_manifest_excel'] == 1  ? 'selected' : ''; ?>>DEFAULT</option>
                            <option value="2" <?php echo isset($setting['customized_manifest_excel']) && $setting['customized_manifest_excel'] == 2 ? 'selected' : ''; ?>>MYS </option>
                            <option value="3" <?php echo isset($setting['customized_manifest_excel']) && $setting['customized_manifest_excel'] == 3 ? 'selected' : ''; ?>>NAVALI INT</option>
                            <option value="4" <?php echo isset($setting['customized_manifest_excel']) && $setting['customized_manifest_excel'] == 4 ? 'selected' : ''; ?>>STARLINE </option>
                            <option value="5" <?php echo isset($setting['customized_manifest_excel']) && $setting['customized_manifest_excel'] == 5 ? 'selected' : ''; ?>>OM </option>
                            <option value="6" <?php echo isset($setting['customized_manifest_excel']) && $setting['customized_manifest_excel'] == 6 ? 'selected' : ''; ?>>ELS LHR</option>
                            <option value="7" <?php echo isset($setting['customized_manifest_excel']) && $setting['customized_manifest_excel'] == 7 ? 'selected' : ''; ?>>ELS DXB</option>
                            <option value="8" <?php echo isset($setting['customized_manifest_excel']) && $setting['customized_manifest_excel'] == 8 ? 'selected' : ''; ?>>ELS US </option>
                            <option value="9" <?php echo isset($setting['customized_manifest_excel']) && $setting['customized_manifest_excel'] == 9 ? 'selected' : ''; ?>>AWCC </option>
                            <option value="10" <?php echo isset($setting['customized_manifest_excel']) && $setting['customized_manifest_excel'] == 10 ? 'selected' : ''; ?>>DELTA DELHI</option>
                            <option value="12" <?php echo isset($setting['customized_manifest_excel']) && $setting['customized_manifest_excel'] == 12 ? 'selected' : ''; ?>>ELS US 2</option>
                            <option value="13" <?php echo isset($setting['customized_manifest_excel']) && $setting['customized_manifest_excel'] == 13 ? 'selected' : ''; ?>>DTD</option>
                            <option value="14" <?php echo isset($setting['customized_manifest_excel']) && $setting['customized_manifest_excel'] == 14 ? 'selected' : ''; ?>>GRAND SPEED DOMESTIC</option>


                        </select>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['customized_manifest_excel_2']) ? $setting_comment['customized_manifest_excel_2'] : ''; ?>" class="col-sm-6 col-form-label">Customized Manifest REPORT 2</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[customized_manifest_excel_2]" id="" cols="80" rows="1"><?php echo isset($setting['customized_manifest_excel_2']) ? $setting['customized_manifest_excel_2'] : ''; ?></textarea>
                    <?php } else { ?>
                        <select name="manifest[customized_manifest_excel_2]">
                            <option value="0" <?php echo isset($setting['customized_manifest_excel_2']) && $setting['customized_manifest_excel_2'] == 0  ? 'selected' : ''; ?>>SELECT</option>
                            <option value="1" <?php echo isset($setting['customized_manifest_excel_2']) && $setting['customized_manifest_excel_2'] == 1  ? 'selected' : ''; ?>>DEFAULT</option>
                            <option value="2" <?php echo isset($setting['customized_manifest_excel_2']) && $setting['customized_manifest_excel_2'] == 2 ? 'selected' : ''; ?>>MYS CUSTOM</option>
                            <option value="3" <?php echo isset($setting['customized_manifest_excel_2']) && $setting['customized_manifest_excel_2'] == 3 ? 'selected' : ''; ?>>NAVALI INT</option>
                            <option value="4" <?php echo isset($setting['customized_manifest_excel_2']) && $setting['customized_manifest_excel_2'] == 4 ? 'selected' : ''; ?>>STARLINE CUSTOM</option>
                            <option value="5" <?php echo isset($setting['customized_manifest_excel_2']) && $setting['customized_manifest_excel_2'] == 5 ? 'selected' : ''; ?>>OM </option>
                            <option value="6" <?php echo isset($setting['customized_manifest_excel_2']) && $setting['customized_manifest_excel_2'] == 6 ? 'selected' : ''; ?>>ELS DXB </option>
                            <option value="7" <?php echo isset($setting['customized_manifest_excel_2']) && $setting['customized_manifest_excel_2'] == 7 ? 'selected' : ''; ?>>ELS LHR </option>
                            <option value="8" <?php echo isset($setting['customized_manifest_excel_2']) && $setting['customized_manifest_excel_2'] == 8 ? 'selected' : ''; ?>>ELS US </option>
                            <option value="9" <?php echo isset($setting['customized_manifest_excel_2']) && $setting['customized_manifest_excel_2'] == 9 ? 'selected' : ''; ?>>AWCC </option>
                            <option value="10" <?php echo isset($setting['customized_manifest_excel_2']) && $setting['customized_manifest_excel_2'] == 10 ? 'selected' : ''; ?>>DELTA DELHI</option>
                            <option value="12" <?php echo isset($setting['customized_manifest_excel_2']) && $setting['customized_manifest_excel_2'] == 12 ? 'selected' : ''; ?>>ELS US 2</option>
                            <option value="13" <?php echo isset($setting['customized_manifest_excel_2']) && $setting['customized_manifest_excel_2'] == 13 ? 'selected' : ''; ?>>DTD</option>
                            <option value="14" <?php echo isset($setting['customized_manifest_excel_2']) && $setting['customized_manifest_excel_2'] == 14 ? 'selected' : ''; ?>>GRAND SPEED DOMESTIC</option>


                        </select>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['custom_edi_report']) ? $setting_comment['custom_edi_report'] : ''; ?>" class="col-sm-6 col-form-label"> Manifest EDI report CSB IV</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[custom_edi_report]" id="" cols="80" rows="1"><?php echo isset($setting['custom_edi_report']) ? $setting['custom_edi_report'] : ''; ?></textarea>
                    <?php } else { ?>
                        <select name="manifest[custom_edi_report]">
                            <option value="0" <?php echo isset($setting['custom_edi_report']) && $setting['custom_edi_report'] == 0  ? 'selected' : ''; ?>>SELECT</option>
                            <option value="1" <?php echo isset($setting['custom_edi_report']) && $setting['custom_edi_report'] == 1  ? 'selected' : ''; ?>>DEFAULT</option>
                            <option value="2" <?php echo isset($setting['custom_edi_report']) && $setting['custom_edi_report'] == 2  ? 'selected' : ''; ?>>STARLINE CUSTOM EDI</option>
                            <option value="3" <?php echo isset($setting['custom_edi_report']) && $setting['custom_edi_report'] == 3  ? 'selected' : ''; ?>>OM CUSTOM EDI</option>
                            <option value="4" <?php echo isset($setting['custom_edi_report']) && $setting['custom_edi_report'] == 4  ? 'selected' : ''; ?>>NAVALI EDI</option>
                            <option value="5" <?php echo isset($setting['custom_edi_report']) && $setting['custom_edi_report'] == 5  ? 'selected' : ''; ?>>DELTA</option>
                        </select>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['custom_edi_report_csb_v']) ? $set_csb_vting_comment['custom_edi_report_csb_v'] : ''; ?>" class="col-sm-6 col-form-label"> Manifest EDI report CSB V</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[custom_edi_report_csb_v]" id="" cols="80" rows="1"><?php echo isset($setting['custom_edi_report_csb_v']) ? $setting['custom_edi_report_csb_v'] : ''; ?></textarea>
                    <?php } else { ?>
                        <select name="manifest[custom_edi_report_csb_v]">
                            <option value="0" <?php echo isset($setting['custom_edi_report_csb_v']) && $setting['custom_edi_report_csb_v'] == 0  ? 'selected' : ''; ?>>SELECT</option>
                            <option value="1" <?php echo isset($setting['custom_edi_report_csb_v']) && $setting['custom_edi_report_csb_v'] == 1  ? 'selected' : ''; ?>>DEFAULT</option>
                            <!-- <option value="2" <?php echo isset($setting['custom_edi_report_csb_v']) && $setting['custom_edi_report_csb_v'] == 2  ? 'selected' : ''; ?>>STARLINE CUSTOM EDI</option>
                            <option value="3" <?php echo isset($setting['custom_edi_report_csb_v']) && $setting['custom_edi_report_csb_v'] == 3  ? 'selected' : ''; ?>>OM CUSTOM EDI</option>
                            <option value="4" <?php echo isset($setting['custom_edi_report_csb_v']) && $setting['custom_edi_report_csb_v'] == 4  ? 'selected' : ''; ?>>NAVALI EDI</option> -->
                            <option value="5" <?php echo isset($setting['custom_edi_report_csb_v']) && $setting['custom_edi_report_csb_v'] == 5  ? 'selected' : ''; ?>>DELTA </option>
                            <option value="6" <?php echo isset($setting['custom_edi_report_csb_v']) && $setting['custom_edi_report_csb_v'] == 6  ? 'selected' : ''; ?>>UC </option>
                        </select>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['custom_summary_report']) ? $setting_comment['custom_summary_report'] : ''; ?>" class="col-sm-6 col-form-label"> Manifest SUMMARY report</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[custom_summary_report]" id="" cols="80" rows="1"><?php echo isset($setting['custom_summary_report']) ? $setting['custom_summary_report'] : ''; ?></textarea>
                    <?php } else { ?>
                        <select name="manifest[custom_summary_report]">
                            <option value="0" <?php echo isset($setting['custom_summary_report']) && $setting['custom_summary_report'] == 0  ? 'selected' : ''; ?>>SELECT</option>
                            <option value="1" <?php echo isset($setting['custom_summary_report']) && $setting['custom_summary_report'] == 1  ? 'selected' : ''; ?>>DEFAULT</option>
                            <option value="2" <?php echo isset($setting['custom_summary_report']) && $setting['custom_summary_report'] == 2  ? 'selected' : ''; ?>>AWCC CUSTOM SUMMARY</option>
                            <option value="3" <?php echo isset($setting['custom_summary_report']) && $setting['custom_summary_report'] == 3  ? 'selected' : ''; ?>>CORPOCO</option>
                        </select>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['manifest_import_license_holder']) ? $setting_comment['manifest_import_license_holder'] : ''; ?>" class="col-sm-6 col-form-label">Enable manifest import for license holders</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[manifest_import_license_holder]" id="" cols="80" rows="1"><?php echo isset($setting['manifest_import_license_holder']) ? $setting['manifest_import_license_holder'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="manifest_import_license_holder" value="1" autocomplete="nope" name="manifest[manifest_import_license_holder]" <?php echo isset($setting['manifest_import_license_holder']) && $setting['manifest_import_license_holder'] == 1 ? 'checked' : ''; ?>>
                        <label for="manifest_import_license_holder" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['hide_amount_from_transfer_manifest_in_portal']) ? $setting_comment['hide_amount_from_transfer_manifest_in_portal'] : ''; ?>" class="col-sm-6 col-form-label">Hide Amount From Transfer Manifest In Portal</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[hide_amount_from_transfer_manifest_in_portal]" id="" cols="80" rows="1"><?php echo isset($setting['hide_amount_from_transfer_manifest_in_portal']) ? $setting['hide_amount_from_transfer_manifest_in_portal'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="hide_amount_from_transfer_manifest_in_portal" value="1" autocomplete="nope" name="manifest[hide_amount_from_transfer_manifest_in_portal]" <?php echo isset($setting['hide_amount_from_transfer_manifest_in_portal']) && $setting['hide_amount_from_transfer_manifest_in_portal'] == 1 ? 'checked' : ''; ?>>
                        <label for="hide_amount_from_transfer_manifest_in_portal" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['rename_vendor_cd_no_to_co_loader_cd_no']) ? $setting_comment['rename_vendor_cd_no_to_co_loader_cd_no'] : ''; ?>" class="col-sm-6 col-form-label">Rename Vendor Cd No to Co-Loader CD No</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[rename_vendor_cd_no_to_co_loader_cd_no]" id="" cols="80" rows="1"><?php echo isset($setting['rename_vendor_cd_no_to_co_loader_cd_no']) ? $setting['rename_vendor_cd_no_to_co_loader_cd_no'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="rename_vendor_cd_no_to_co_loader_cd_no" value="1" autocomplete="nope" name="manifest[rename_vendor_cd_no_to_co_loader_cd_no]" <?php echo isset($setting['rename_vendor_cd_no_to_co_loader_cd_no']) && $setting['rename_vendor_cd_no_to_co_loader_cd_no'] == 1 ? 'checked' : ''; ?>>
                        <label for="rename_vendor_cd_no_to_co_loader_cd_no" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['manifest_run_performance_report']) ? $setting_comment['manifest_run_performance_report'] : ''; ?>" class="col-sm-6 col-form-label">Manifest Run Performance Report</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[manifest_run_performance_report]" id="" cols="80" rows="1"><?php echo isset($setting['manifest_run_performance_report']) ? $setting['manifest_run_performance_report'] : ''; ?></textarea>
                    <?php } else { ?>
                        <select name="manifest[manifest_run_performance_report]">
                            <!-- <option value="0" <?php echo isset($setting['manifest_run_performance_report']) && $setting['manifest_run_performance_report'] == 0  ? 'selected' : ''; ?>>SELECT</option> -->
                            <option value="1" <?php echo isset($setting['manifest_run_performance_report']) && $setting['manifest_run_performance_report'] == 1  ? 'selected' : ''; ?>>DEFAULT</option>
                            <option value="2" <?php echo isset($setting['manifest_run_performance_report']) && $setting['manifest_run_performance_report'] == 2  ? 'selected' : ''; ?>>AWCC</option>
                        </select>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['remove_unwanted_fields_from_manifest']) ? $setting_comment['remove_unwanted_fields_from_manifest'] : ''; ?>" class="col-sm-6 col-form-label">Remove Unwanted Fields From Manifest</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[remove_unwanted_fields_from_manifest]" id="" cols="80" rows="1"><?php echo isset($setting['remove_unwanted_fields_from_manifest']) ? $setting['remove_unwanted_fields_from_manifest'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="remove_unwanted_fields_from_manifest" value="1" autocomplete="nope" name="manifest[remove_unwanted_fields_from_manifest]" <?php echo isset($setting['remove_unwanted_fields_from_manifest']) && $setting['remove_unwanted_fields_from_manifest'] == 1 ? 'checked' : ''; ?>>
                        <label for="remove_unwanted_fields_from_manifest" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['update_co_vendor_in_awb_from_manifest']) ? $setting_comment['update_co_vendor_in_awb_from_manifest'] : ''; ?>" class="col-sm-6 col-form-label">UPDATE VENDOR IN AWB FROM MANIFEST</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[update_co_vendor_in_awb_from_manifest]" id="" cols="80" rows="1"><?php echo isset($setting['update_co_vendor_in_awb_from_manifest']) ? $setting['update_co_vendor_in_awb_from_manifest'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="update_co_vendor_in_awb_from_manifest" value="1" autocomplete="nope" name="manifest[update_co_vendor_in_awb_from_manifest]" <?php echo isset($setting['update_co_vendor_in_awb_from_manifest']) && $setting['update_co_vendor_in_awb_from_manifest'] == 1 ? 'checked' : ''; ?>>
                        <label for="update_co_vendor_in_awb_from_manifest" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['manifest_report_awb_id_wise']) ? $setting_comment['manifest_report_awb_id_wise'] : ''; ?>" class="col-sm-6 col-form-label">Show manifest report AWB ID wise</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[manifest_report_awb_id_wise]" id="" cols="80" rows="1"><?php echo isset($setting['manifest_report_awb_id_wise']) ? $setting['manifest_report_awb_id_wise'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="manifest_report_awb_id_wise" value="1" autocomplete="nope" name="manifest[manifest_report_awb_id_wise]" <?php echo isset($setting['manifest_report_awb_id_wise']) && $setting['manifest_report_awb_id_wise'] == 1 ? 'checked' : ''; ?>>
                        <label for="manifest_report_awb_id_wise" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['edi_report_awb_id_wise']) ? $setting_comment['edi_report_awb_id_wise'] : ''; ?>" class="col-sm-6 col-form-label">Show EDI report AWB ID wise</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[edi_report_awb_id_wise]" id="" cols="80" rows="1"><?php echo isset($setting['edi_report_awb_id_wise']) ? $setting['edi_report_awb_id_wise'] : ''; ?></textarea>
                    <?php } else { ?>
                    <?php } ?>
                    <input type="checkbox" id="edi_report_awb_id_wise" value="1" autocomplete="nope" name="manifest[edi_report_awb_id_wise]" <?php echo isset($setting['edi_report_awb_id_wise']) && $setting['edi_report_awb_id_wise'] == 1 ? 'checked' : ''; ?>>
                    <label for="edi_report_awb_id_wise" style="height: 10px !important;"> </label>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['summary_eport_awb_id_wise']) ? $setting_comment['summary_eport_awb_id_wise'] : ''; ?>" class="col-sm-6 col-form-label">Show SUMMARY report AWB ID wise</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[summary_eport_awb_id_wise]" id="" cols="80" rows="1"><?php echo isset($setting['summary_eport_awb_id_wise']) ? $setting['summary_eport_awb_id_wise'] : ''; ?></textarea>
                    <?php } else { ?>
                    <?php } ?>
                    <input type="checkbox" id="summary_eport_awb_id_wise" value="1" autocomplete="nope" name="manifest[summary_eport_awb_id_wise]" <?php echo isset($setting['summary_eport_awb_id_wise']) && $setting['summary_eport_awb_id_wise'] == 1 ? 'checked' : ''; ?>>
                    <label for="summary_eport_awb_id_wise" style="height: 10px !important;"> </label>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['send_run_number_in_tracking_remarks']) ? $setting_comment['send_run_number_in_tracking_remarks'] : ''; ?>" class="col-sm-6 col-form-label">SEND RUN NUMBER INSTEAD OF MANIFEST NUMBER IN TRACKING REMARKS</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[send_run_number_in_tracking_remarks]" id="" cols="80" rows="1"><?php echo isset($setting['send_run_number_in_tracking_remarks']) ? $setting['send_run_number_in_tracking_remarks'] : ''; ?></textarea>
                    <?php } else { ?>
                    <?php } ?>
                    <input type="checkbox" id="send_run_number_in_tracking_remarks" value="1" autocomplete="nope" name="manifest[send_run_number_in_tracking_remarks]" <?php echo isset($setting['send_run_number_in_tracking_remarks']) && $setting['send_run_number_in_tracking_remarks'] == 1 ? 'checked' : ''; ?>>
                    <label for="send_run_number_in_tracking_remarks" style="height: 10px !important;"> </label>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['vendor_reco_by_service']) ? $setting_comment['vendor_reco_by_service'] : ''; ?>" class="col-sm-6 col-form-label"> SHOW VAT INVOICE PDF </label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[vendor_reco_by_service]" id="" cols="80" rows="1"><?php echo isset($setting['vendor_reco_by_service']) ? $setting['vendor_reco_by_service'] : ''; ?></textarea>
                    <?php } else { ?>
                    <?php } ?>
                    <input type="checkbox" id="show_vat_invoice_pdf" value="1" autocomplete="nope" name="manifest[show_vat_invoice_pdf]" <?php echo isset($setting['show_vat_invoice_pdf']) && $setting['show_vat_invoice_pdf'] == 1 ? 'checked' : ''; ?>>
                    <label for="show_vat_invoice_pdf" style="height: 10px !important;"> </label>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['customized_vat_invoice_pdf']) ? $setting_comment['customized_vat_invoice_pdf'] : ''; ?>" class="col-sm-6 col-form-label">Customized VAT INVOICE PDF</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[customized_vat_invoice_pdf]" id="" cols="80" rows="1"><?php echo isset($setting['customized_vat_invoice_pdf']) ? $setting['customized_vat_invoice_pdf'] : ''; ?></textarea>
                    <?php } else { ?>
                        <select name="manifest[customized_vat_invoice_pdf]">
                            <option value="1" <?php echo isset($setting['customized_vat_invoice_pdf']) && $setting['customized_vat_invoice_pdf'] == 1  ? 'selected' : ''; ?>>SELECT</option>
                            <option value="2" <?php echo isset($setting['customized_vat_invoice_pdf']) && $setting['customized_vat_invoice_pdf'] == 2 ? 'selected' : ''; ?>>NAVALAI</option>
                            <option value="3" <?php echo isset($setting['customized_vat_invoice_pdf']) && $setting['customized_vat_invoice_pdf'] == 3 ? 'selected' : ''; ?>>AWCC - 001</option>
                        </select>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['xml_delta_manifest']) ? $setting_comment['xml_delta_manifest'] : ''; ?>" class="col-sm-6 col-form-label">XML requirement for Delta</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[xml_delta_manifest]" id="" cols="80" rows="1"><?php echo isset($setting['xml_delta_manifest']) ? $setting['xml_delta_manifest'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="xml_delta_manifest" value="1" autocomplete="nope" name="manifest[xml_delta_manifest]" <?php echo isset($setting['xml_delta_manifest']) && $setting['xml_delta_manifest'] == 1 ? 'checked' : ''; ?>>
                        <label for="xml_delta_manifest" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div> -->
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['uk_flat_report']) ? $setting_comment['uk_flat_report'] : ''; ?>" class="col-sm-6 col-form-label">UK FLAT FILE </label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[uk_flat_report]" id="" cols="80" rows="1"><?php echo isset($setting['uk_flat_report']) ? $setting['uk_flat_report'] : ''; ?></textarea>
                    <?php } else { ?>
                        <select name="manifest[uk_flat_report]">
                            <option value="0" <?php echo isset($setting['uk_flat_report']) && $setting['uk_flat_report'] == 0  ? 'selected' : ''; ?>>SELECT</option>
                            <option value="1" <?php echo isset($setting['uk_flat_report']) && $setting['uk_flat_report'] == 1  ? 'selected' : ''; ?>>AWCC</option>
                        </select>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['dxb_flat_report']) ? $setting_comment['dxb_flat_report'] : ''; ?>" class="col-sm-6 col-form-label">DUBAI FLAT FILE</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[dxb_flat_report]" id="" cols="80" rows="1"><?php echo isset($setting['dxb_flat_report']) ? $setting['dxb_flat_report'] : ''; ?></textarea>
                    <?php } else { ?>
                        <select name="manifest[dxb_flat_report]">
                            <option value="0" <?php echo isset($setting['dxb_flat_report']) && $setting['dxb_flat_report'] == 0  ? 'selected' : ''; ?>>SELECT</option>
                            <option value="1" <?php echo isset($setting['dxb_flat_report']) && $setting['dxb_flat_report'] == 1  ? 'selected' : ''; ?>>AWCC</option>
                        </select>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" class="col-sm-6 col-form-label">Hide Amount From Transfer Manifest</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <input type="checkbox" id="hide_amount_from_transfer_manifest" value="1" autocomplete="nope" name="manifest[hide_amount_from_transfer_manifest]" <?php echo isset($setting['hide_amount_from_transfer_manifest']) && $setting['hide_amount_from_transfer_manifest'] == 1 ? 'checked' : ''; ?>>
                    <label for="hide_amount_from_transfer_manifest" style="height: 10px !important;"> </label>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['mandate_forwarder']) ? $setting_comment['mandate_forwarder'] : ''; ?>" class="col-sm-6 col-form-label">MANDATE FORWARDER</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[mandate_forwarder]" id="" cols="80" rows="1"><?php echo isset($setting['mandate_forwarder']) ? $setting['mandate_forwarder'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="mandate_forwarder" value="1" autocomplete="nope" name="manifest[mandate_forwarder]" <?php echo isset($setting['mandate_forwarder']) && $setting['mandate_forwarder'] == 1 ? 'checked' : ''; ?>>
                        <label for="mandate_forwarder" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['hide_consignee_name_in_manifest_pdf']) ? $setting_comment['hide_consignee_name_in_manifest_pdf'] : ''; ?>" class="col-sm-6 col-form-label">HIDE CONSIGNEE NAME IN MANIFEST PDF</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[hide_consignee_name_in_manifest_pdf]" id="" cols="80" rows="1"><?php echo isset($setting['hide_consignee_name_in_manifest_pdf']) ? $setting['hide_consignee_name_in_manifest_pdf'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="hide_consignee_name_in_manifest_pdf" value="1" autocomplete="nope" name="manifest[hide_consignee_name_in_manifest_pdf]" <?php echo isset($setting['hide_consignee_name_in_manifest_pdf']) && $setting['hide_consignee_name_in_manifest_pdf'] == 1 ? 'checked' : ''; ?>>
                        <label for="hide_consignee_name_in_manifest_pdf" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['apply_origin_hub_filter_in_manifest']) ? $setting_comment['apply_origin_hub_filter_in_manifest'] : ''; ?>" class="col-sm-6 col-form-label">APPLY ORIGIN HUB FILTER IN MANIFEST</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[apply_origin_hub_filter_in_manifest]" id="" cols="80" rows="1"><?php echo isset($setting['apply_origin_hub_filter_in_manifest']) ? $setting['apply_origin_hub_filter_in_manifest'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="apply_origin_hub_filter_in_manifest" value="1" autocomplete="nope" name="manifest[apply_origin_hub_filter_in_manifest]" <?php echo isset($setting['apply_origin_hub_filter_in_manifest']) && $setting['apply_origin_hub_filter_in_manifest'] == 1 ? 'checked' : ''; ?>>
                        <label for="apply_origin_hub_filter_in_manifest" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['update_awb_purchase_vendor_service_from_manifest']) ? $setting_comment['update_awb_purchase_vendor_service_from_manifest'] : ''; ?>" class="col-sm-6 col-form-label">UPDATE AWB PURCHASE VENDOR SERVICE FROM MANIFEST</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[update_awb_purchase_vendor_service_from_manifest]" id="" cols="80" rows="1"><?php echo isset($setting['update_awb_purchase_vendor_service_from_manifest']) ? $setting['update_awb_purchase_vendor_service_from_manifest'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="update_awb_purchase_vendor_service_from_manifest" value="1" autocomplete="nope" name="manifest[update_awb_purchase_vendor_service_from_manifest]" <?php echo isset($setting['update_awb_purchase_vendor_service_from_manifest']) && $setting['update_awb_purchase_vendor_service_from_manifest'] == 1 ? 'checked' : ''; ?>>
                        <label for="update_awb_purchase_vendor_service_from_manifest" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['customized_transfer_manifest_pdf']) ? $setting_comment['customized_transfer_manifest_pdf'] : ''; ?>" class="col-sm-6 col-form-label">CUSTOMIZED TRANSFER MANIFEST PDF</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[customized_transfer_manifest_pdf]" id="" cols="80" rows="1"><?php echo isset($setting['customized_transfer_manifest_pdf']) ? $setting['customized_transfer_manifest_pdf'] : ''; ?></textarea>
                    <?php } else { ?>
                        <select name="manifest[customized_transfer_manifest_pdf]">
                            <option value="1" <?php echo isset($setting['customized_transfer_manifest_pdf']) && $setting['customized_transfer_manifest_pdf'] == 1  ? 'selected' : ''; ?>>DEFAULT</option>
                            <option value="2" <?php echo isset($setting['customized_transfer_manifest_pdf']) && $setting['customized_transfer_manifest_pdf'] == 2 ? 'selected' : ''; ?>>UC </option>
                        </select>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['add_awb_status_filter']) ? $setting_comment['add_awb_status_filter'] : ''; ?>" class="col-sm-6 col-form-label">ADD AWB STATUS FILTER</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[add_awb_status_filter]" id="" cols="80" rows="1"><?php echo isset($setting['add_awb_status_filter']) ? $setting['add_awb_status_filter'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="add_awb_status_filter" value="1" autocomplete="nope" name="manifest[add_awb_status_filter]" <?php echo isset($setting['add_awb_status_filter']) && $setting['add_awb_status_filter'] == 1 ? 'checked' : ''; ?>>
                        <label for="add_awb_status_filter" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_destionation_instead_of_consignee']) ? $setting_comment['show_destionation_instead_of_consignee'] : ''; ?>" class="col-sm-6 col-form-label">SHOW DESTINATION CODE INSTEAD OF CONSIGNEE</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[show_destionation_instead_of_consignee]" id="" cols="80" rows="1"><?php echo isset($setting['show_destionation_instead_of_consignee']) ? $setting['show_destionation_instead_of_consignee'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_destionation_instead_of_consignee" value="1" autocomplete="nope" name="manifest[show_destionation_instead_of_consignee]" <?php echo isset($setting['show_destionation_instead_of_consignee']) && $setting['show_destionation_instead_of_consignee'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_destionation_instead_of_consignee" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['dont_check_origin_hub_for_manifest']) ? $setting_comment['dont_check_origin_hub_for_manifest'] : ''; ?>" class="col-sm-6 col-form-label">DONT CHECK ORIGIN HUB FOR MANIFEST</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[dont_check_origin_hub_for_manifest]" id="" cols="80" rows="1"><?php echo isset($setting['dont_check_origin_hub_for_manifest']) ? $setting['dont_check_origin_hub_for_manifest'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="dont_check_origin_hub_for_manifest" value="1" autocomplete="nope" name="manifest[dont_check_origin_hub_for_manifest]" <?php echo isset($setting['dont_check_origin_hub_for_manifest']) && $setting['dont_check_origin_hub_for_manifest'] == 1 ? 'checked' : ''; ?>>
                        <label for="dont_check_origin_hub_for_manifest" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['remove_custom_status_name_for_delta_xml']) ? $setting_comment['remove_custom_status_name_for_delta_xml'] : ''; ?>" class="col-sm-6 col-form-label">REMOVE CUSTOM STATUS NAME FOR DELTA XML</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[remove_custom_status_name_for_delta_xml]" id="" cols="80" rows="1"><?php echo isset($setting['remove_custom_status_name_for_delta_xml']) ? $setting['remove_custom_status_name_for_delta_xml'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="remove_custom_status_name_for_delta_xml" value="1" autocomplete="nope" name="manifest[remove_custom_status_name_for_delta_xml]" <?php echo isset($setting['remove_custom_status_name_for_delta_xml']) && $setting['remove_custom_status_name_for_delta_xml'] == 1 ? 'checked' : ''; ?>>
                        <label for="remove_custom_status_name_for_delta_xml" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['auto_generate_manifest_number_by_prefix_suffix']) ? $setting_comment['auto_generate_manifest_number_by_prefix_suffix'] : ''; ?>" class="col-sm-6 col-form-label">Auto Generate Manifest Number By Prefix</label>
            <div class="col-sm-1">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[auto_generate_manifest_number_by_prefix_suffix]" id="" cols="80" rows="1"><?php echo isset($setting['auto_generate_manifest_number_by_prefix_suffix']) ? $setting['auto_generate_manifest_number_by_prefix_suffix'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="auto_generate_manifest_number_by_prefix_suffix" value="1" autocomplete="nope" name="manifest[auto_generate_manifest_number_by_prefix_suffix]" <?php echo isset($setting['auto_generate_manifest_number_by_prefix_suffix']) && $setting['auto_generate_manifest_number_by_prefix_suffix'] == 1 ? 'checked' : ''; ?>>
                        <label for="auto_generate_manifest_number_by_prefix_suffix" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
            <?php if ($mode != "add_comments") { ?>
                <?php
                $autono_style = 'd-none';
                if (isset($setting['auto_generate_manifest_number_by_prefix_suffix']) && $setting['auto_generate_manifest_number_by_prefix_suffix'] == 1) {
                    $autono_style = '';
                }
                ?>
                <div id="manifest_auto_range" class="col-sm-6 <?php echo $autono_style; ?>">
                    <div class="col-sm-4" style="display: inline;float: left;">
                        <div class="form-group row">
                            <label class="col-sm-6">Prefix</label>
                            <input type="text" class="form-control col-sm-6" name="manifest[manifest_auto_prefix]" value="<?php echo $setting['manifest_auto_prefix'] ? $setting['manifest_auto_prefix'] : ''; ?>" />
                        </div>
                    </div>
                    <div class="col-sm-4" style="display: inline;float: right;">
                        <div class="form-group row">
                            <label class="col-sm-4">Range</label>
                            <input type="text" class="form-control col-sm-8" name="manifest[manifest_auto_range]" value="<?php echo $setting['manifest_auto_range'] ? $setting['manifest_auto_range'] : ''; ?>" />
                        </div>
                    </div>
                    <div class="col-sm-4" style="display: inline;float: left;">
                        <div class="form-group row">
                            <label class="col-sm-6">SUFFIX</label>
                            <input type="text" class="form-control col-sm-6" name="manifest[manifest_auto_suffix]" value="<?php echo $setting['manifest_auto_suffix'] ? $setting['manifest_auto_suffix'] : ''; ?>" />
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['manifest_fedex_pdf']) ? $setting_comment['manifest_fedex_pdf'] : ''; ?>" class="col-sm-6 col-form-label">Manifest FEDEX Pdf</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[manifest_fedex_pdf]" id="" cols="80" rows="1"><?php echo isset($setting['manifest_fedex_pdf']) ? $setting['manifest_fedex_pdf'] : ''; ?></textarea>
                    <?php } else { ?>
                        <select name="manifest[manifest_fedex_pdf]">
                            <option value="2" <?php echo isset($setting['manifest_fedex_pdf']) && $setting['manifest_fedex_pdf'] == 2  ? 'selected' : ''; ?>>SELECT</option>
                            <option value="1" <?php echo isset($setting['manifest_fedex_pdf']) && $setting['manifest_fedex_pdf'] == 1 ? 'selected' : ''; ?>>TFC</option>
                        </select>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>



    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['enable_ams_integration']) ? $setting_comment['enable_ams_integration'] : ''; ?>" class="col-sm-6 col-form-label">ENABLE AMS SET UP</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[enable_ams_integration]" id="" cols="80" rows="1"><?php echo isset($setting['enable_ams_integration']) ? $setting['enable_ams_integration'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_ams_integration" value="1" autocomplete="nope" name="manifest[enable_ams_integration]" <?php echo isset($setting['enable_ams_integration']) && $setting['enable_ams_integration'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_ams_integration" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    $("#run_code_date").change(function() {
        if (this.checked) {
            //Do stuff
            $('#run_code_sr').attr("disabled", true)
        } else {
            $('#run_code_sr').removeAttr("disabled", true)
        }
    });

    $("#run_code_sr").change(function() {
        if (this.checked) {
            //Do stuff
            $('#run_code_date').attr("disabled", true)
        } else {
            $('#run_code_date').removeAttr("disabled", true)
        }
    });

    $("#auto_generate_manifest_number_by_prefix_suffix").click(function() {
        if (this.checked) {
            $("#manifest_auto_range").removeClass("d-none");
        } else {
            $("#manifest_auto_range").addClass("d-none");
            $('input[name="master[manifest_auto_prefix]"]').val('');
            $('input[name="master[manifest_auto_range]"]').val('');
            $('input[name="master[manifest_auto_suffix]"]').val('');
        }
    });
</script>