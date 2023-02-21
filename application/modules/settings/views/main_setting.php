<div class="tab-pane" id="main-tab">
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
</div>