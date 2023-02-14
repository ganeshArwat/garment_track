<div class="tab-pane" id="main-tab">
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['enable_manifest']) ? $setting_comment['enable_manifest'] : ''; ?>" class="col-sm-6 col-form-label">Enable Manifests</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="manifest[enable_manifest]" id="" cols="80" rows="1"><?php echo isset($setting['enable_manifest']) ? $setting['enable_manifest'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_manifest" value="1" autocomplete="nope" name="manifest[enable_manifest]" <?php echo isset($setting['enable_manifest']) && $setting['enable_manifest'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_manifest" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['enable_transfer_manifest']) ? $setting_comment['enable_transfer_manifest'] : ''; ?>" class="col-sm-6 col-form-label">Enable Transfer Manifest</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="main[enable_transfer_manifest]" id="" cols="80" rows="1"><?php echo isset($setting['enable_transfer_manifest']) ? $setting['enable_transfer_manifest'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_transfer_manifest" value="1" autocomplete="nope" name="main[enable_transfer_manifest]" <?php echo isset($setting['enable_transfer_manifest']) && $setting['enable_transfer_manifest'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_transfer_manifest" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['enable_pickup']) ? $setting_comment['enable_pickup'] : ''; ?>" class="col-sm-6 col-form-label">Enable Pick up</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="main[enable_pickup]" id="" cols="80" rows="1"><?php echo isset($setting['enable_pickup']) ? $setting['enable_pickup'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_pickup" value="1" autocomplete="nope" name="main[enable_pickup]" <?php echo isset($setting['enable_pickup']) && $setting['enable_pickup'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_pickup" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['enable_customer_estimate']) ? $setting_comment['enable_customer_estimate'] : ''; ?>" class="col-sm-6 col-form-label">Enable Estimate</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="customer_estimate[enable_customer_estimate]" id="" cols="80" rows="1"><?php echo isset($setting['enable_customer_estimate']) ? $setting['enable_customer_estimate'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_customer_estimate" value="1" autocomplete="nope" name="customer_estimate[enable_customer_estimate]" <?php echo isset($setting['enable_customer_estimate']) && $setting['enable_customer_estimate'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_customer_estimate" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['enable_rate_cal']) ? $setting_comment['enable_rate_cal'] : ''; ?>" class="col-sm-6 col-form-label">Enable Rate calc</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="main[enable_rate_cal]" id="" cols="80" rows="1"><?php echo isset($setting['enable_rate_cal']) ? $setting['enable_rate_cal'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_rate_cal" value="1" autocomplete="nope" name="main[enable_rate_cal]" <?php echo isset($setting['enable_rate_cal']) && $setting['enable_rate_cal'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_rate_cal" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['enable_vendor_reco']) ? $setting_comment['enable_vendor_reco'] : ''; ?>" class="col-sm-6 col-form-label">Enable Vendor Reco</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="main[enable_vendor_reco]" id="" cols="80" rows="1"><?php echo isset($setting['enable_vendor_reco']) ? $setting['enable_vendor_reco'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_vendor_reco" value="1" autocomplete="nope" name="main[enable_vendor_reco]" <?php echo isset($setting['enable_vendor_reco']) && $setting['enable_vendor_reco'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_vendor_reco" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['enable_sales_account']) ? $setting_comment['enable_sales_account'] : ''; ?>" class="col-sm-6 col-form-label">Enable Sales Accounts</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="main[enable_sales_account]" id="" cols="80" rows="1"><?php echo isset($setting['enable_sales_account']) ? $setting['enable_sales_account'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_sales_account" value="1" autocomplete="nope" name="main[enable_sales_account]" <?php echo isset($setting['enable_sales_account']) && $setting['enable_sales_account'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_sales_account" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['enable_purchase_account']) ? $setting_comment['enable_purchase_account'] : ''; ?>" class="col-sm-6 col-form-label">Enable Purchase Accounts</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="main[enable_purchase_account]" id="" cols="80" rows="1"><?php echo isset($setting['enable_purchase_account']) ? $setting['enable_purchase_account'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_purchase_account" value="1" autocomplete="nope" name="main[enable_purchase_account]" <?php echo isset($setting['enable_purchase_account']) && $setting['enable_purchase_account'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_purchase_account" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['enable_l1_l2']) ? $setting_comment['enable_l1_l2'] : ''; ?>" class="col-sm-6 col-form-label">Enable L1 L2</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="main[enable_l1_l2]" id="" cols="80" rows="1"><?php echo isset($setting['enable_l1_l2']) ? $setting['enable_l1_l2'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_l1_l2" value="1" autocomplete="nope" name="main[enable_l1_l2]" <?php echo isset($setting['enable_l1_l2']) && $setting['enable_l1_l2'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_l1_l2" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['enable_task']) ? $setting_comment['enable_task'] : ''; ?>" class="col-sm-6 col-form-label">Enable Tasks</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="main[enable_task]" id="" cols="80" rows="1"><?php echo isset($setting['enable_task']) ? $setting['enable_task'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_task" value="1" autocomplete="nope" name="main[enable_task]" <?php echo isset($setting['enable_task']) && $setting['enable_task'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_task" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['enable_inquiries']) ? $setting_comment['enable_inquiries'] : ''; ?>" class="col-sm-6 col-form-label">Enable inquiries</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="main[enable_inquiries]" id="" cols="80" rows="1"><?php echo isset($setting['enable_inquiries']) ? $setting['enable_inquiries'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_inquiries" value="1" autocomplete="nope" name="main[enable_inquiries]" <?php echo isset($setting['enable_inquiries']) && $setting['enable_inquiries'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_inquiries" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['enable_hrms']) ? $setting_comment['enable_hrms'] : ''; ?>" class="col-sm-6 col-form-label">Enable HRMS - Attendence, holiday, leave</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="main[enable_hrms]" id="" cols="80" rows="1"><?php echo isset($setting['enable_hrms']) ? $setting['enable_hrms'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_hrms" value="1" autocomplete="nope" name="main[enable_hrms]" <?php echo isset($setting['enable_hrms']) && $setting['enable_hrms'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_hrms" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>


    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['enable_material_master']) ? $setting_comment['enable_material_master'] : ''; ?>" class="col-sm-6 col-form-label">Enable Material Master</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="main[enable_material_master]" id="" cols="80" rows="1"><?php echo isset($setting['enable_material_master']) ? $setting['enable_material_master'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_material_master" value="1" autocomplete="nope" name="main[enable_material_master]" <?php echo isset($setting['enable_material_master']) && $setting['enable_material_master'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_material_master" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['enable_internal_api']) ? $setting_comment['enable_internal_api'] : ''; ?>" class="col-sm-6 col-form-label">Enable Internal API</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="main[enable_internal_api]" id="" cols="80" rows="1"><?php echo isset($setting['enable_internal_api']) ? $setting['enable_internal_api'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_internal_api" value="1" autocomplete="nope" name="main[enable_internal_api]" <?php echo isset($setting['enable_internal_api']) && $setting['enable_internal_api'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_internal_api" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['enable_custom_report_master']) ? $setting_comment['enable_custom_report_master'] : ''; ?>" class="col-sm-6 col-form-label">Enable Custom Report Master</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="main[enable_custom_report_master]" id="" cols="80" rows="1"><?php echo isset($setting['enable_custom_report_master']) ? $setting['enable_custom_report_master'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_custom_report_master" value="1" autocomplete="nope" name="main[enable_custom_report_master]" <?php echo isset($setting['enable_custom_report_master']) && $setting['enable_custom_report_master'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_custom_report_master" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['enable_crm']) ? $setting_comment['enable_crm'] : ''; ?>" class="col-sm-6 col-form-label">Enable CRM</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="main[enable_crm]" id="" cols="80" rows="1"><?php echo isset($setting['enable_crm']) ? $setting['enable_crm'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_crm" value="1" autocomplete="nope" name="main[enable_crm]" <?php echo isset($setting['enable_crm']) && $setting['enable_crm'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_crm" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['enable_inventories']) ? $setting_comment['enable_inventories'] : ''; ?>" class="col-sm-6 col-form-label">Enable Inventories</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="main[enable_inventories]" id="" cols="80" rows="1"><?php echo isset($setting['enable_inventories']) ? $setting['enable_inventories'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_inventories" value="1" autocomplete="nope" name="main[enable_inventories]" <?php echo isset($setting['enable_inventories']) && $setting['enable_inventories'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_inventories" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['enable_edi']) ? $setting_comment['enable_edi'] : ''; ?>" class="col-sm-6 col-form-label">Enable EDI</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="main[enable_edi]" id="" cols="80" rows="1"><?php echo isset($setting['enable_edi']) ? $setting['enable_edi'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_edi" value="1" autocomplete="nope" name="main[enable_edi]" <?php echo isset($setting['enable_edi']) && $setting['enable_edi'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_edi" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['hide_company_edit_from_awb_page']) ? $setting_comment['hide_company_edit_from_awb_page'] : ''; ?>" class="col-sm-6 col-form-label">Hide Company EDIT option From AWB Entry Page</label>
            <div class="col-sm-6">
                <div class="checkbox">
                <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="main[hide_company_edit_from_awb_page]" id="" cols="80" rows="1"><?php echo isset($setting['hide_company_edit_from_awb_page']) ? $setting['hide_company_edit_from_awb_page'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="hide_company_edit_from_awb_page" value="1" autocomplete="nope" name="main[hide_company_edit_from_awb_page]" <?php echo isset($setting['hide_company_edit_from_awb_page']) && $setting['hide_company_edit_from_awb_page'] == 1 ? 'checked' : ''; ?>>
                    <label for="hide_company_edit_from_awb_page" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['enable_stock_management']) ? $setting_comment['enable_stock_management'] : ''; ?>" class="col-sm-6 col-form-label">ENABLE STOCK MASTER</label>
            <div class="col-sm-6">
                <div class="checkbox">
                <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="main[enable_stock_management]" id="" cols="80" rows="1"><?php echo isset($setting['enable_stock_management']) ? $setting['enable_stock_management'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_stock_management" value="1" autocomplete="nope" name="main[enable_stock_management]" <?php echo isset($setting['enable_stock_management']) && $setting['enable_stock_management'] == 1 ? 'checked' : ''; ?>>
                    <label for="enable_stock_management" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

</div>