<style>
    [type=radio]:not(:checked)+label:after,
    [type=radio]:not(:checked)+label:before {
        border: 2px solid #ebe7e7;
    }
</style>
<div>
    <div class="row">
        <?php
        if (isset($service_list) && is_array($service_list) && count($service_list) > 0) {
            foreach ($service_list as $key => $value) {
                if (isset($nimbuspost_courier_id) && $nimbuspost_courier_id == $value['id']) {
                    if ($customer_per > 0) {
                        $extra_amt = ($value['total_charges'] * $customer_per) / 100;
                    } else {
                        $extra_amt = 0;
                    }

                    $total_amount = $value['total_charges'] + $extra_amt;
                    $total_amount = round($total_amount, 2);
                    $nimbuspost_sales_amt = $total_amount;
                    $nimbuspost_purchase_amt = $value['total_charges'];
                }
            }
        }
        ?>
        <input type="hidden" name="docket_extra[nimbuspost_sales_amt]" value="<?php echo $nimbuspost_sales_amt; ?>" id="nimbuspost_sales_amt" />
        <input type="hidden" name="docket_extra[nimbuspost_purchase_amt]" value="<?php echo $nimbuspost_sales_amt; ?>" id="nimbuspost_purchase_amt" />

        <?php
        if (isset($service_list) && is_array($service_list) && count($service_list) > 0) {
            foreach ($service_list as $key => $value) {
                if ($customer_per > 0) {
                    $extra_amt = ($value['total_charges'] * $customer_per) / 100;
                } else {
                    $extra_amt = 0;
                }

                $total_amount = $value['total_charges'] + $extra_amt;
                $total_amount = round($total_amount, 2);
        ?>
                <div class="col-sm-6">
                    <input type="hidden" name="submit_type" id="submit_type" value="<?php echo isset($submit_type) ? $submit_type : ''; ?>" />
                    <div class="alert alert-success fade show" role="alert" style="padding: 3px !important;background-color: #2a3a7f !important;">
                        <div class="custom-control custom-radio" style="padding-left: 10px;">
                            <input type="radio" required="" id="customRadio<?php echo $value['id']; ?>" name="docket[nimbuspost_courier_id]" class="custom-control-input nimbuspost_courier_id" value="<?php echo $value['id']; ?>" <?php echo isset($nimbuspost_courier_id) && $nimbuspost_courier_id == $value['id'] ? 'checked' : ''; ?> data-purchase="<?php echo $value['total_charges']; ?>" data-sales="<?php echo $total_amount; ?>">
                            <label style="padding-right: 0px !important;" class="custom-control-label" for="customRadio<?php echo $value['id']; ?>"><?php echo $value['name']; ?> (₹<?php echo $total_amount; ?>) </label>
                        </div>
                    </div>
                </div>
        <?php }
        }
        ?>
    </div>

</div>

<script>
    function save_sales_billing_nimbus() {
        var submit_type = $("#submit_type").val();
        if (submit_type == 'print_label') {
            $('button[type="submit"]').attr('disabled', 'disabled');
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('docket/update_nimbuspost_service') ?>",
                data: {
                    'docket_id': '<?php echo $docket_id ?>',
                    'nimbuspost_courier_id': $('input[name="docket[nimbuspost_courier_id]"]:checked').val(),
                    'nimbuspost_sales_amt': $('#nimbuspost_sales_amt').val(),
                    'nimbuspost_purchase_amt': $('#nimbuspost_purchase_amt').val()
                },
                success: function(data) {
                    window.location = '<?php echo site_url('shipping_api/label_print_api/get_label_print/' . $docket_id) ?>';
                }
            });

        } else {
            saveFormID = 'docket_form';
            buttonClick = 'submitClick';
            nimbusSubmit = 1;
            save_sales_billing();
        }

    }
    $(document).ready(function() {
        $('#nimbuspost_modal').on('hidden.bs.modal', function() {
            chargeRefresh = 2;
            buttonClick = '';
            nimbusSubmit = 2;
            $(".disabled_field").attr('disabled', 'disabled');
            <?php if (isset($setting['dont_allow_back_dated_docket_entries']) && $setting['dont_allow_back_dated_docket_entries'] == 1) { ?>
                $("#booking_date").attr('min', '<?php echo date('Y-m-d'); ?>');
            <?php } ?>
        });

        $(".nimbuspost_courier_id").change(function() {

            var nimbuspost_courier_id = $("input[name='docket[nimbuspost_courier_id]']:checked").val();
            if (nimbuspost_courier_id != '') {
                var sales_amt = $("input[name='docket[nimbuspost_courier_id]']:checked").attr('data-sales');
                var purchase_amt = $("input[name='docket[nimbuspost_courier_id]']:checked").attr('data-purchase');
            } else {
                var sales_amt = 0;
                var purchase_amt = 0;
            }
            $("#nimbuspost_sales_amt").val(sales_amt);
            $("#nimbuspost_purchase_amt").val(purchase_amt);
        });
    });
</script>