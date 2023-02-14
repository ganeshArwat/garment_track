<style>
    #bank_body td {
        padding: 0px !important;
    }

    .bank_row {
        border-bottom: 2px solid #ddd;
        padding-top: 10px;
    }
</style>
<?php $bankRow = 0; ?>
<div class="row">
    <div class="col-12" id="bank_body">

        <?php
        if (isset($bank_data) && is_array($bank_data) && count($bank_data) > 0) {
            foreach ($bank_data as $bkey => $bvalue) { ?>
                <div id="bank_row_<?php echo $bankRow; ?>" class="bank_row <?php echo isset($bvalue['status']) && $bvalue['status'] == 2 ?  'inactive_row' : ''; ?>">
                    <div class=" col-12">
                        <div class="form-group row">
                            <label for="cust_name" class="col-sm-1 col-form-label">Serial No</label>
                            <div class="col-sm-3">
                                <input type="number" class="form-control" name="serial_no[<?php echo $bankRow; ?>]" value="<?php echo $bvalue['serial_no']; ?>" />
                            </div>

                            <label for="cust_name" class="col-sm-1 col-form-label">BANK NAME</label>
                            <div class="col-sm-3">
                                <input type="hidden" name="bank_id[<?php echo $bankRow; ?>]" value="<?php echo $bvalue['id']; ?>" />
                                <input type="text" class="form-control" name="bank_name[<?php echo $bankRow; ?>]" value="<?php echo $bvalue['bank_name']; ?>" />
                            </div>

                            <label for="cust_name" class="col-sm-1 col-form-label">ACCOUNT TYPE</label>
                            <div class="col-sm-3">
                                <select class="form-control" name="account_type[<?php echo $bankRow; ?>]">
                                    <option value="">Select...</option>
                                    <?php
                                    if (isset($all_bank_account_type) && is_array($all_bank_account_type) && count($all_bank_account_type) > 0) {
                                        foreach ($all_bank_account_type as $fkey => $fvalue) { ?>
                                            <option value="<?php echo $fvalue['id'] ?>" <?php echo $bvalue['account_type'] == $fvalue['id'] ? 'selected' : ''; ?>><?php echo $fvalue['name']; ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>


                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group row">
                            <label for="cust_name" class="col-sm-1 col-form-label">BANK SWIFT ID</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="bank_swift_id[<?php echo $bankRow; ?>]" value="<?php echo $bvalue['bank_swift_id']; ?>" />
                            </div>

                            <label for="cust_name" class="col-sm-1 col-form-label">BANK BRANCH</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="branch[<?php echo $bankRow; ?>]" value="<?php echo $bvalue['branch']; ?>" />
                            </div>
                            <label for="cust_name" class="col-sm-1 col-form-label">ACCOUNT NAME</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="account_name[<?php echo $bankRow; ?>]" value="<?php echo $bvalue['account_name']; ?>" />
                            </div>


                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group row">
                            <label for="cust_name" class="col-sm-1 col-form-label">IFSC CODE</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="ifsc_code[<?php echo $bankRow; ?>]" value="<?php echo $bvalue['ifsc_code']; ?>" />
                            </div>

                            <label for="cust_name" class="col-sm-1 col-form-label">SORT CODE</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="sort_code[<?php echo $bankRow; ?>]" value="<?php echo $bvalue['sort_code']; ?>" />
                            </div>

                            <label for="cust_name" class="col-sm-1 col-form-label">ACCOUNT NO</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control account_no" name="account_no[<?php echo $bankRow; ?>]" id="account_no_<?php echo $bankRow; ?>" value="<?php echo $bvalue['account_no']; ?>" onblur="check_account_no()" />
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group row">
                            <label for="cust_name" class="col-sm-1 col-form-label">BANK ADDRESS</label>
                            <div class="col-sm-3">
                                <textarea class="form-control" name="address[<?php echo $bankRow; ?>]"><?php echo $bvalue['address']; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">


                        <div class="form-group row">

                            <label for="cust_name" class="col-sm-1 col-form-label">IBAN</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="bank_iban[<?php echo $bankRow; ?>]" value="<?php echo $bvalue['bank_iban']; ?>" />
                            </div>

                            <label for="cust_name" class="col-sm-1 col-form-label">UPI ID</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="upi_id[<?php echo $bankRow; ?>]" value="<?php echo $bvalue['upi_id']; ?>" />
                            </div>

                            <label for="cust_name" class="col-sm-1 col-form-label">UPI NUMBER</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="upi_number[<?php echo $bankRow; ?>]" value="<?php echo $bvalue['upi_number']; ?>" />
                            </div>


                        </div>
                    </div>
                    <div class="col-12">

                        <div class="form-group row">
                            <label for="cust_name" class="col-sm-1 col-form-label">UPI IMAGE</label>
                            <div class="col-sm-3">
                                <input class="form-control" type="file" name="upi_image_<?php echo $bankRow; ?>" accept="image/*">
                                <?php if (isset($bvalue['upi_image']) && $bvalue['upi_image'] != '' && file_exists($bvalue['upi_image'])) { ?>
                                    <a class="file_button" target="blank" href="<?php echo base_url($bvalue['upi_image']); ?>">VIEW FILE</a>
                                <?php } ?>
                            </div>

                            <label for="cust_name" class="col-sm-1 col-form-label">OPENING BLNC AMOUNT</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="opening_amount[<?php echo $bankRow; ?>]" value="<?php echo $bvalue['opening_amount']; ?>" />
                            </div>

                            <label for="cust_name" class="col-sm-1 col-form-label">OPENING BLNC DATE</label>
                            <div class="col-sm-3">
                                <input type="date" class="form-control datepicker_text" name="opening_date[<?php echo $bankRow; ?>]" value="<?php echo isset($bvalue['opening_date']) ? get_format_date(DATE_INPUT_FORMAT, ($bvalue['opening_date'])) : ''; ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group row">
                            <label for="cust_name" class="col-sm-1 col-form-label">OPENING BLNC TYPE</label>
                            <div class="col-sm-3">
                                <select class="form-control" name="opening_type[<?php echo $bankRow; ?>]">
                                    <option value="">Select...</option>
                                    <?php
                                    if (isset($opening_bal_type) && is_array($opening_bal_type) && count($opening_bal_type) > 0) {
                                        foreach ($opening_bal_type as $fkey => $fvalue) { ?>
                                            <option value="<?php echo $fvalue['id'] ?>" <?php echo $bvalue['opening_type'] == $fvalue['id'] ? 'selected' : ''; ?>><?php echo $fvalue['name']; ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                            <label for="cust_name" class="col-sm-1 col-form-label">STATUS</label>
                            <div class="col-sm-3">
                                <div class="radio" style="display: inline;float: left;">
                                    <input name="account_status[<?php echo $bankRow; ?>]" type="radio" id="Option_1_<?php echo $bankRow; ?>" checked="" value="1" <?php echo isset($bvalue['status']) && $bvalue['status'] == 2 ?  'checked=""' : ''; ?>>
                                    <label for="Option_1_<?php echo $bankRow; ?>">Active</label>
                                </div>
                                <div class="radio">
                                    <input name="account_status[<?php echo $bankRow; ?>]" type="radio" id="Option_2_<?php echo $bankRow; ?>" value="2" <?php echo isset($bvalue['status']) && $bvalue['status'] == 2 ?  'checked=""' : ''; ?>>
                                    <label for="Option_2_<?php echo $bankRow; ?>">Inactive</label>
                                </div>
                            </div>
                            <label for="cust_name" class="col-sm-1 col-form-label">Show QR</label>
                            <div class="col-sm-3">
                                <div class="radio" style="display: inline;float: left;">
                                    <input name="qr_status[<?php echo $bankRow; ?>]" type="radio" id="qr_Option_1_<?php echo $bankRow; ?>" checked="" value="1" <?php echo isset($bvalue['qr_status']) && $bvalue['qr_status'] == 2 ?  'checked=""' : ''; ?>>
                                    <label for="qr_Option_1_<?php echo $bankRow; ?>">Yes</label>
                                </div>
                                <div class="radio">
                                    <input name="qr_status[<?php echo $bankRow; ?>]" type="radio" id="qr_Option_2_<?php echo $bankRow; ?>" value="2" <?php echo isset($bvalue['qr_status']) && $bvalue['qr_status'] == 2 ?  'checked=""' : ''; ?>>
                                    <label for="qr_Option_2_<?php echo $bankRow; ?>">No</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-sm-3">
                                <a class="btn text-danger" onclick="remove_bank_fields('<?php echo $bankRow; ?>');"><i class="fa fa-times-circle"></i>&nbsp;Remove</a>
                            </div> -->

                </div>



        <?php
                $bankRow++;
            }
        } ?>

    </div>
    <p><a class="btn bg-olive" href="javascript:void(0);" id="add_bank_row"><i class="fa fa-plus-circle"></i>&nbsp;ADD MORE BANK</a></p>
</div>
<script>
    var bankRow = '<?php echo $bankRow + 1; ?>';

    function check_account_no() {
        $(".error_msg").html('');
        $('.account_no').each(function() {
            var account_no = $(this).val();
            var account_id = $(this).attr('id');
            if (account_no != '') {
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('company_master/check_account_no') ?>",
                    data: {
                        'account_no': account_no,
                        'company_id': '<?php echo isset($company['id']) ? $company['id'] : ''; ?>'
                    },
                    success: function(data) {
                        var returnedData = JSON.parse(data);
                        if (returnedData['error'] != undefined) {
                            $("#" + account_id).val('');
                            var error_msg = $(".error_msg").html();
                            error_msg = error_msg + "<br>" + returnedData['error'];
                            $(".error_msg").html(error_msg);

                            var error_msg = $(".error_msg").html();
                            if (error_msg == '') {
                                $(".error_msg").hide();
                            } else {
                                $(".error_msg").show();
                            }
                        }
                    },
                    error: function(data) {

                    }
                });
            }
        });


    }

    function remove_bank_fields(bankRow) {
        $("#bank_row_" + bankRow).remove();
    }
    $(document).ready(function() {
        $("#add_bank_row").click(function() {
            var rowAppend = '<div id="bank_row_' + bankRow + '" class="bank_row">';
            rowAppend += '<div class=" col-12"><div class="form-group row"> ';
            rowAppend += '<label for="cust_name" class="col-sm-1 col-form-label">Serial No</label>';
            rowAppend += '<div class="col-sm-3">';
            rowAppend += '<input type="number" class="form-control" name="serial_no[' + bankRow + ']" value="" />';
            rowAppend += '</div>';
            rowAppend += '<label for="cust_name" class="col-sm-1 col-form-label">BANK NAME</label><div class="col-sm-3"><input type="hidden" name="bank_id[' + bankRow + ']" value="0" /><input type="text" class="form-control" name="bank_name[' + bankRow + ']" /></div>';
            rowAppend += '<label for="cust_name" class="col-sm-1 col-form-label">ACCOUNT TYPE</label><div class="col-sm-3">';
            rowAppend += '<select class="form-control" name="account_type[' + bankRow + ']">';
            rowAppend += '<option value="">Select...</option>';
            <?php
            if (isset($all_bank_account_type) && is_array($all_bank_account_type) && count($all_bank_account_type) > 0) {
                foreach ($all_bank_account_type as $fkey => $fvalue) { ?>
                    rowAppend += '<option value="<?php echo $fvalue['id'] ?>"><?php echo $fvalue['name']; ?></option>';
            <?php }
            } ?>
            rowAppend += '</select></div>';
            rowAppend += '</div></div>';
            rowAppend += '<div class=" col-12"><div class="form-group row"> ';
            rowAppend += '<label for="cust_name" class="col-sm-1 col-form-label">BANK SWIFT ID</label> <div class="col-sm-3"><input type="text" class="form-control" name="bank_swift_id[' + bankRow + ']" /></div>';
            rowAppend += '<label for="cust_name" class="col-sm-1 col-form-label">BANK BRANCH</label><div class="col-sm-3"><input type="text" class="form-control" name="branch[' + bankRow + ']" /></div>';
            rowAppend += '<label for="cust_name" class="col-sm-1 col-form-label">ACCOUNT NAME</label><div class="col-sm-3"><input type="text" class="form-control" name="account_name[' + bankRow + ']" /></div>';
            rowAppend += '</div></div>'

            rowAppend += '<div class=" col-12"><div class="form-group row"> ';
            rowAppend += '<label for="cust_name" class="col-sm-1 col-form-label">IFSC CODE</label><div class="col-sm-3"><input type="text" class="form-control" name="ifsc_code[' + bankRow + ']" /></div>';
            rowAppend += '<label for="cust_name" class="col-sm-1 col-form-label">SORT CODE</label><div class="col-sm-3"><input type="text" class="form-control" name="sort_code[' + bankRow + ']" /></div>';
            rowAppend += '<label for="cust_name" class="col-sm-1 col-form-label">ACCOUNT NO</label><div class="col-sm-3"><input type="text" class="form-control account_no" id="account_no_' + bankRow + '" name="account_no[' + bankRow + ']" onblur="check_account_no()"/></div>';
            rowAppend += '</div></div>'

            rowAppend += '<div class=" col-12"><div class="form-group row"> ';
            rowAppend += '<label for="cust_name" class="col-sm-1 col-form-label">BANK ADDRESS</label><div class="col-sm-3"><textarea class="form-control" name="address[' + bankRow + ']"></textarea></div>';
            rowAppend += '</div></div>'

            rowAppend += '<div class=" col-12"><div class="form-group row"> ';
            rowAppend += '<label for="cust_name" class="col-sm-1 col-form-label">IBAN</label><div class="col-sm-3"><input type="text" class="form-control" name="bank_iban[' + bankRow + ']" /></div>';
            rowAppend += '<label for="cust_name" class="col-sm-1 col-form-label">UPI ID</label><div class="col-sm-3"><input type="text" class="form-control" name="upi_id[' + bankRow + ']" /></div>';
            rowAppend += '<label for="cust_name" class="col-sm-1 col-form-label">UPI Number</label><div class="col-sm-3"><input type="text" class="form-control" name="upi_number[' + bankRow + ']" /></div>';
            rowAppend += '</div></div>'

            rowAppend += '<div class=" col-12"><div class="form-group row"> ';
            rowAppend += '<label for="cust_name" class="col-sm-1 col-form-label">UPI IMAGE</label><div class="col-sm-3"><input class="form-control" type="file" name="upi_image_' + bankRow + '" accept="image/*"></div>';
            rowAppend += '<label for="cust_name" class="col-sm-1 col-form-label">OPENING BLNC AMOUNT</label><div class="col-sm-3"><input type="text" class="form-control" name="opening_amount[' + bankRow + ']" value="" /></div>';
            rowAppend += '<label for="cust_name" class="col-sm-1 col-form-label">OPENING BLNC DATE</label><div class="col-sm-3"><input type="date" class="form-control datepicker_text" name="opening_date[' + bankRow + ']" value="" /></div>';
            rowAppend += '</div></div>'

            rowAppend += '<div class=" col-12"><div class="form-group row"> ';
            rowAppend += '<label for="cust_name" class="col-sm-1 col-form-label">OPENING BLNC TYPE</label><div class="col-sm-3">';
            rowAppend += '<select class="form-control" name="opening_type[' + bankRow + ']">';
            rowAppend += '<option value="">Select...</option>';
            <?php
            if (isset($opening_bal_type) && is_array($opening_bal_type) && count($opening_bal_type) > 0) {
                foreach ($opening_bal_type as $fkey => $fvalue) { ?>
                    rowAppend += '<option value="<?php echo $fvalue['id'] ?>"><?php echo $fvalue['name']; ?></option>';
            <?php }
            } ?>
            rowAppend += '</select></div>';
            rowAppend += '<label for="cust_name" class="col-sm-1 col-form-label">STATUS</label><div class="col-sm-3">';
            rowAppend += '<div class="radio" style="display: inline;float: left;">';
            rowAppend += '<input name="account_status[' + bankRow + ']" type="radio" id="Option_1_' + bankRow + '" checked="" value="1" checked><label for="Option_1_' + bankRow + '">Active</label></div>';
            rowAppend += '<div class="radio"><input name="account_status[' + bankRow + ']" type="radio" id="Option_2_' + bankRow + '" value="2"><label for="Option_2_' + bankRow + '">Inactive</label>';
            rowAppend += '</div></div>';

            rowAppend += '<div class="col-sm-3"><a class="btn text-danger" onclick="remove_bank_fields(' + bankRow + ');"><i class="fa fa-times-circle"></i>&nbsp;Remove</a></div>';
            rowAppend += '</div></div></div>'

            $("#bank_body").append(rowAppend);
            bankRow = parseInt(bankRow) + 1;

        });
    });
</script>