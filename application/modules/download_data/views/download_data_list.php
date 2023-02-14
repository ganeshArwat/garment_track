<div class="box box-default">
    <div class="box-body">
        <form id="docket_filter" class="form-horizontal" action="<?php echo site_url('download_data/start_download') ?>" method="POST">
            <input type="hidden" name="hidden_type" id="hiddenid">
            <div class="row">

                <?php //if (in_array($session_data['type'], $admin_role_name)) { ?>
                <div class="col-sm-3">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Customer Code:</label>
                        <input type="hidden" value="<?php echo isset($get_data['customer_id']) ? $get_data['customer_id'] : ''; ?>" id="customer_id" name="customer_id" />
                        <input class="form-control" id="autosuggest_customer" type="text" value="<?php echo isset($get_data['customer_id']) && isset($all_customer[$get_data['customer_id']]) ? $all_customer[$get_data['customer_id']]['code'] : ''; ?>" autocomplete="nope" />
                    </div>
                </div>
                <?php //} ?>
                <div class="col-sm-3">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Vendor Code:</label>
                        <input type="hidden" value="<?php echo isset($get_data['co_vendor_id']) ? $get_data['co_vendor_id'] : ''; ?>" id="co_vendor_id" name="co_vendor_id" />
                        <input class="form-control" id="autosuggest_co_vendor" type="text" value="<?php echo isset($get_data['co_vendor_id']) && isset($all_co_vendor[$get_data['co_vendor_id']]) ? $all_co_vendor[$get_data['co_vendor_id']]['code'] : ''; ?>" autocomplete="nope" />
                    </div>
                </div>
                <div class="col-sm-3">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Service Code:</label>
                        <input type="hidden" value="<?php echo isset($get_data['vendor_id']) ? $get_data['vendor_id'] : ''; ?>" id="vendor_id" name="vendor_id" />
                        <input class="form-control" id="autosuggest_vendor" type="text" value="<?php echo isset($get_data['vendor_id']) && isset($all_vendor[$get_data['vendor_id']]) ? $all_vendor[$get_data['vendor_id']]['code'] : ''; ?>" autocomplete="nope" />
                    </div>
                </div>
                <div class="col-sm-3">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Booking Date:</label>
                        <div class="row">
                            <div class="col-6" style="padding-right: 0px;"> <input type="date" class="form-control pull-right datepicker_text" name="booking_min" data-inputmask="'alias': 'dd/mm/yyyy'" value="<?php echo isset($get_data['booking_min']) && $get_data['booking_min'] != '' ? date(DATE_INPUT_FORMAT, strtotime($get_data['booking_min'])) : ''; ?>"></div>
                            <div class="col-6" style="padding-left: 0px;"> <input type="date" class="form-control pull-right datepicker_text" name="booking_max" data-inputmask="'alias': 'dd/mm/yyyy'" value="<?php echo isset($get_data['booking_max']) && $get_data['booking_max'] != '' ? date(DATE_INPUT_FORMAT, strtotime($get_data['booking_max'])) : ''; ?>"></div>
                        </div>
                    </div>
                </div>



                <div class="col-sm-6">
                    <!-- text input -->
                    <div class="form-group mt-4">
                        <?php //if (in_array($session_data['type'], $admin_role_name)) { ?>
                        <input id="btn-search" tabindex="4" type="submit" class="btn bg-olive" value="Search" onclick="$('#hiddenid').val('');">
                        <?php //} ?>
                    </div>
                </div>
            </div>
        </form>
        <?php $this->load->view('plugin/autosuggest_input'); ?>
        <?php
        $autosuggest_arr = array(
            //2 => array('input_id' => 'autosuggest_origin', 'module' => 'location', 'hidden_id' => 'origin_id'),
            //3 => array('input_id' => 'autosuggest_destination', 'module' => 'location', 'hidden_id' => 'destination_id'),
            //4 => array('input_id' => 'autosuggest_product', 'module' => 'product', 'hidden_id' => 'product_id'),
            2 => array('input_id' => 'autosuggest_vendor', 'module' => 'vendor', 'hidden_id' => 'vendor_id'),
            3 => array('input_id' => 'autosuggest_co_vendor', 'module' => 'co_vendor', 'hidden_id' => 'co_vendor_id'),
                // 7 => array('input_id' => 'autosuggest_shipper', 'module' => 'shipper', 'hidden_id' => 'shipper_id'),
                //  8 => array('input_id' => 'autosuggest_consignee', 'module' => 'consignee', 'hidden_id' => 'consignee_id'),
        );
       // if (in_array($session_data['type'], $admin_role_name)) {
            $autosuggest_arr[1] = array('input_id' => 'autosuggest_customer', 'module' => 'customer', 'hidden_id' => 'customer_id');
        //}
        ?>
        <script>
            $(document).ready(function () {
<?php foreach ($autosuggest_arr as $key => $value) { ?>
                    autosuggest_input('<?php echo $value['input_id'] ?>', '<?php echo $value['module'] ?>', '<?php echo $value['hidden_id'] ?>');
<?php } ?>
            });
        </script>
        <?php $this->load->view('plugin/datepicker'); ?>
    </div>
</div>
