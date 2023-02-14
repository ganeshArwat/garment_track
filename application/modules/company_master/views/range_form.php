<h4 style="border-bottom: 2px solid #212529;font-size:15px;">NON GST INVOICE NUMBER</h4>
<div class="row">
    <div class="col-12">
        <div class="col-12">
            <div class="form-group row">
                <label for="cust_code" class="col-sm-2 col-form-label">NON GST INVOICE RANGE CODE</label>
                <div class="col-sm-2">
                    <select class="form-control" name="non_gst[code]">
                        <option value="">SELECT...</option>
                        <option value="NONGST" <?php echo isset($non_gst_data['code']) && $non_gst_data['code'] == 'NONGST' ? 'selected' : ''; ?>>NONGST</option>
                    </select>
                </div>
                <label for="cust_code" class="col-sm-1 col-form-label">PREFIX</label>
                <div class="col-sm-2">
                    <input class="form-control" type="text" value="<?php echo isset($non_gst_data['invoice_prefix']) ? $non_gst_data['invoice_prefix'] : ''; ?>" name="non_gst[invoice_prefix]" tabindex="<?php echo $tab_index++; ?>">
                </div>
                <label for="cust_code" class="col-sm-1 col-form-label">RANGE</label>
                <div class="col-sm-1">
                    <input type="text" value="START NUMBER" readonly="" style="background: #ededed;border: 1px solid #827272;">
                </div>
                <div class="col-sm-1">
                    <input class="form-control" type="text" value="<?php echo isset($non_gst_data['range_start']) ? $non_gst_data['range_start'] : ''; ?>" id="range_start" name="non_gst[range_start]" tabindex="4">
                </div>
                <div class="col-sm-1">
                    <input type="text" value="END NUMBER" readonly="" style="background: #ededed;border: 1px solid #827272;">
                </div>
                <div class="col-sm-1">
                    <input class="form-control" type="text" value="<?php echo isset($non_gst_data['range_end']) ? $non_gst_data['range_end'] : ''; ?>" id="range_end" name="non_gst[range_end]" tabindex="5">
                </div>
            </div>
        </div>
    </div>
</div>
<h4 style="border-bottom: 2px solid #212529;font-size:15px;">SERVICE TYPE WISE INVOICE RANGES</h4>
<div class="row">
    <div class="col-6">
        <table class="table table-sm table-bordered">
            <thead style="background: #e6f4ff;">
                <tr>
                    <th>SERVICE TYPE</th>
                    <th>RANGE MASTER HEAD</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $range_cnt = 1;
                if (isset($all_master_type) && is_array($all_master_type) && count($all_master_type) > 0) {
                    foreach ($all_master_type as $skey => $svalue) {  ?>
                        <tr>
                            <td>
                                <?php echo $svalue['name']; ?>
                                <input type="hidden" name="master_service_type[]" value="<?php echo $svalue['id']; ?>" />
                            </td>
                            <td>
                                <select class="form-control range_select" name="invoice_range_id[]" id="range_<?php echo $range_cnt; ?>">
                                    <option value="">SELECT...</option>
                                    <?php
                                    if (isset($all_invoice_range) && is_array($all_invoice_range) && count($all_invoice_range) > 0) {
                                        foreach ($all_invoice_range as $ckey => $cvalue) { ?>
                                            <option data-id="<?php echo $range_cnt; ?>" value="<?php echo $cvalue['id'] ?>" <?php echo isset($company_range[$svalue['id']]) && $company_range[$svalue['id']] == $cvalue['id'] ? 'selected' : ''; ?>><?php echo $cvalue['name'] ?></option>
                                    <?php }
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                <?php $range_cnt++;
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    $(document).ready(function() {
        $(".range_select").change(function() {
            var range_id = $(this).val();
            var range_cnt = $(this).find(':selected').attr('data-id');
            console.log(range_cnt);
            if (range_id > 0) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('company_master/check_invoice_range') ?>",
                    data: {
                        'company_id': '<?php echo isset($company['id']) ? $company['id'] : ''; ?>',
                        'range_id': range_id,
                    },
                    success: function(data) {
                        //cehck in current 
                    },
                    error: function(data) {
                        $("#range_" + range_cnt).val('');
                        alert('YOU CAN NOT USE THIS RANGE BECAUSE IT IS USED IN OTHER COMPANY.');

                    }
                });
            }

        });
    });
</script>