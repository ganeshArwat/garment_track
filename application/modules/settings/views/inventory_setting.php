<div class="tab-pane" id="inventory-tab">
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['auto_generate_inventory_no']) ? $setting_comment['auto_generate_inventory_no'] : ''; ?>" class="col-sm-6 col-form-label">Auto Generate Inventory No.</label>
            <div class="col-sm-1">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="inventory[auto_generate_inventory_no]" id="" cols="80" rows="1"><?php echo isset($setting['auto_generate_inventory_no']) ? $setting['auto_generate_inventory_no'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="auto_generate_inventory_no" value="1" autocomplete="nope" name="inventory[auto_generate_inventory_no]" <?php echo isset($setting['auto_generate_inventory_no']) && $setting['auto_generate_inventory_no'] == 1 ? 'checked' : ''; ?>>
                        <label for="auto_generate_inventory_no" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
            <?php if ($mode != "add_comments") { ?>
                <?php
                $autono_style = 'd-none';
                if (isset($setting['auto_generate_inventory_no']) && $setting['auto_generate_inventory_no'] == 1) {
                    $autono_style = '';
                }
                ?>
                    <div id="inventory_range" class="col-sm-5 <?php echo $autono_style; ?>">
                        <div class="col-sm-4" style="display: inline;float: left;">
                            <div class="form-group row">
                                <label class="col-sm-6">Prefix</label>
                                <input type="text" class="form-control col-sm-6" name="inventory[inventory_no_prefix]" value="<?php echo $setting['inventory_no_prefix'] ? $setting['inventory_no_prefix'] : ''; ?>" />
                            </div>
                        </div>
                        <div class="col-sm-8" style="display: inline;float: right;">
                            <div class="form-group row">
                                <label class="col-sm-4">Range</label>
                                <input type="text" class="form-control col-sm-8" name="inventory[inventory_no_range]" value="<?php echo $setting['inventory_no_range'] ? $setting['inventory_no_range'] : ''; ?>" />
                            </div>
                        </div>
                    </div>
            <?php } ?>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $("#auto_generate_inventory_no").click(function() {
            if (this.checked) {
                $("#inventory_range").removeClass("d-none");
            } else {
                $("#inventory_range").addClass("d-none");
                $('input[name="inventory[inventory_no_prefix]"]').val('');
                $('input[name="inventory[inventory_no_range]"]').val('');
            }
        });

    });
</script>