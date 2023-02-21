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
   
</div>