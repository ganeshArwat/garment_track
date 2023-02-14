<div class="tab-pane" id="sms-tab">
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['sms_vendor']) ? $setting_comment['sms_vendor'] : ''; ?>" class="col-sm-6 col-form-label">SMS Vendor</label>
            <div class="col-sm-6">
                <?php if (isset($mode) && $mode == "add_comments") { ?>
                    <textarea name="sms[sms_vendor]" id="" cols="80" rows="1"><?php echo isset($setting['sms_vendor']) ? $setting['sms_vendor'] : ''; ?></textarea>
                <?php } else { ?>
                    <select name="sms[sms_vendor]">
                        <option value="0" <?php echo isset($setting['sms_vendor']) && $setting['sms_vendor'] == 0 ? 'selected' : ''; ?>>Select</option>
                        <option value="1" <?php echo isset($setting['sms_vendor']) && $setting['sms_vendor'] == 1 ? 'selected' : ''; ?>>sms.messageindia.in</option>
                        <option value="2" <?php echo isset($setting['sms_vendor']) && $setting['sms_vendor'] == 2 ? 'selected' : ''; ?>>jskbulksms.in</option>
                    </select>
                <?php } ?>

            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['sms_sender_id']) ? $setting_comment['sms_sender_id'] : ''; ?>" class="col-sm-6 col-form-label">SMS Sender ID</label>
            <div class="col-sm-6">
                <?php if (isset($mode) && $mode == "add_comments") { ?>
                    <textarea name="sms[sms_sender_id]" id="" cols="80" rows="1"><?php echo isset($setting['sms_sender_id']) ? $setting['sms_sender_id'] : ''; ?></textarea>
                <?php } else { ?>
                    <input type="text" class="form-control" name="sms[sms_sender_id]" value="<?php echo isset($setting['sms_sender_id']) ? $setting['sms_sender_id'] : ''; ?>" />
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="sms_username" title="<?php echo isset($setting_comment['sms_username']) ? $setting_comment['sms_username'] : ''; ?>" class="col-sm-6 col-form-label">SMS Username</label>
            <div class="col-sm-6">
                <?php if (isset($mode) && $mode == "add_comments") { ?>
                    <textarea name="sms[sms_username]" id="" cols="80" rows="1"><?php echo isset($setting['sms_username']) ? $setting['sms_username'] : ''; ?></textarea>
                <?php } else { ?>
                    <input type="text" class="form-control" name="sms[sms_username]" value="<?php echo isset($setting['sms_username']) ? $setting['sms_username'] : ''; ?>" />
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="sms_api_key" title="<?php echo isset($setting_comment['sms_api_key']) ? $setting_comment['sms_api_key'] : ''; ?>" class="col-sm-6 col-form-label">SMS API KEY</label>
            <div class="col-sm-6">
                <?php if (isset($mode) && $mode == "add_comments") { ?>
                    <textarea name="sms[sms_api_key]" id="" cols="80" rows="1"><?php echo isset($setting['sms_api_key']) ? $setting['sms_api_key'] : ''; ?></textarea>
                <?php } else { ?>
                    <input type="text" class="form-control" name="sms[sms_api_key]" value="<?php echo isset($setting['sms_api_key']) ? $setting['sms_api_key'] : ''; ?>" />
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="sms_peid" title="<?php echo isset($setting_comment['sms_peid']) ? $setting_comment['sms_peid'] : ''; ?>" class="col-sm-6 col-form-label">SMS PEID</label>
            <div class="col-sm-6">
                <?php if (isset($mode) && $mode == "add_comments") { ?>
                    <textarea name="sms[sms_peid]" id="" cols="80" rows="1"><?php echo isset($setting['sms_peid']) ? $setting['sms_peid'] : ''; ?></textarea>
                <?php } else { ?>
                    <input type="text" class="form-control" name="sms[sms_peid]" value="<?php echo isset($setting['sms_peid']) ? $setting['sms_peid'] : ''; ?>" />
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="sms_peid" title="<?php echo isset($setting_comment['campaign']) ? $setting_comment['campaign'] : ''; ?>" class="col-sm-6 col-form-label">Campaign</label>
            <div class="col-sm-6">
                <?php if (isset($mode) && $mode == "add_comments") { ?>
                    <textarea name="sms[campaign]" id="" cols="80" rows="1"><?php echo isset($setting['campaign']) ? $setting['campaign'] : ''; ?></textarea>
                <?php } else { ?>
                    <input type="text" class="form-control" name="sms[campaign]" value="<?php echo isset($setting['campaign']) ? $setting['campaign'] : ''; ?>" />
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="sms_peid" title="<?php echo isset($setting_comment['routeid']) ? $setting_comment['routeid'] : ''; ?>" class="col-sm-6 col-form-label">RouteId</label>
            <div class="col-sm-6">
                <?php if (isset($mode) && $mode == "add_comments") { ?>
                    <textarea name="sms[routeid]" id="" cols="80" rows="1"><?php echo isset($setting['routeid']) ? $setting['routeid'] : ''; ?></textarea>
                <?php } else { ?>
                    <input type="text" class="form-control" name="sms[routeid]" value="<?php echo isset($setting['routeid']) ? $setting['routeid'] : ''; ?>" />
                <?php } ?>
            </div>
        </div>
    </div>
</div>