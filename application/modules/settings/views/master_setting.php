<div class="tab-pane" id="tab-3">
    
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