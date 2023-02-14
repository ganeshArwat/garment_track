<div class="tab-pane" id="tab-5">

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['include_receipt_create']) ? $setting_comment['include_receipt_create'] : ''; ?>" class="col-sm-6 col-form-label">Include while creating Reciepts</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="account[include_receipt_create]" id="" cols="80" rows="1"><?php echo isset($setting['include_receipt_create']) ? $setting['include_receipt_create'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="include_receipt_create" value="1" autocomplete="nope" name="account[include_receipt_create]" <?php echo isset($setting['include_receipt_create']) && $setting['include_receipt_create'] == 1 ? 'checked' : ''; ?>>
                        <label for="include_receipt_create" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['cap_on_round_off']) ? $setting_comment['cap_on_round_off'] : ''; ?>" class="col-sm-6 col-form-label">CAP ON ROUND OFF</label>
            <div class="col-sm-6 setting_data">
                <?php if (isset($mode) && $mode == "add_comments") { ?>
                    <textarea name="account[cap_on_round_off]" id="" cols="80" rows="1"><?php echo isset($setting['cap_on_round_off']) ? $setting['cap_on_round_off'] : ''; ?></textarea>
                <?php } else { ?>
                    <input type="text" class="form-control" name="account[cap_on_round_off]" value="<?php echo isset($setting['cap_on_round_off']) ? $setting['cap_on_round_off'] : ''; ?>" />
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['cap_on_deduction']) ? $setting_comment['cap_on_deduction'] : ''; ?>" class="col-sm-6 col-form-label">CAP ON DEDUCTION</label>
            <div class="col-sm-6">
                <?php if (isset($mode) && $mode == "add_comments") { ?>
                    <textarea name="account[cap_on_deduction]" id="" cols="80" rows="1"><?php echo isset($setting['cap_on_deduction']) ? $setting['cap_on_deduction'] : ''; ?></textarea>
                <?php } else { ?>
                    <input type="text" class="form-control" name="account[cap_on_deduction]" value="<?php echo isset($setting['cap_on_deduction']) ? $setting['cap_on_deduction'] : ''; ?>" />
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['hide_include_deduction']) ? $setting_comment['hide_include_deduction'] : ''; ?>" class="col-sm-6 col-form-label">Remove Deduction From Payment</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="account[hide_include_deduction]" id="" cols="80" rows="1"><?php echo isset($setting['hide_include_deduction']) ? $setting['hide_include_deduction'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="hide_include_deduction" value="1" autocomplete="nope" name="account[hide_include_deduction]" <?php echo isset($setting['hide_include_deduction']) && $setting['hide_include_deduction'] == 1 ? 'checked' : ''; ?>>
                        <label for="hide_include_deduction" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_only_outstanding_excluding_unbilled']) ? $setting_comment['show_only_outstanding_excluding_unbilled'] : ''; ?>" class="col-sm-6 col-form-label">Show only outstanding excluding unbilled in email reports</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="account[show_only_outstanding_excluding_unbilled]" id="" cols="80" rows="1"><?php echo isset($setting['show_only_outstanding_excluding_unbilled']) ? $setting['show_only_outstanding_excluding_unbilled'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_only_outstanding_excluding_unbilled" value="1" autocomplete="nope" name="account[show_only_outstanding_excluding_unbilled]" <?php echo isset($setting['show_only_outstanding_excluding_unbilled']) && $setting['show_only_outstanding_excluding_unbilled'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_only_outstanding_excluding_unbilled" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['customised_unbilled_dockets_in_leder_and_outstanding_report']) ? $setting_comment['customised_unbilled_dockets_in_leder_and_outstanding_report'] : ''; ?>" class="col-sm-6 col-form-label">SHOW CUSTOMISED UNBILLED AWBS IN LEDER AND OUTSTANDING REPORT</label>

            <div class="col-sm-4">


                <!-- <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="account[customised_unbilled_dockets_in_leder_and_outstanding_report]" id="" cols="80" rows="1"><?php echo isset($setting['customised_unbilled_dockets_in_leder_and_outstanding_report']) ? $setting['customised_unbilled_dockets_in_leder_and_outstanding_report'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="customised_unbilled_dockets_in_leder_and_outstanding_report" value="1" autocomplete="nope" name="account[customised_unbilled_dockets_in_leder_and_outstanding_report]" <?php echo isset($setting['customised_unbilled_dockets_in_leder_and_outstanding_report']) && $setting['customised_unbilled_dockets_in_leder_and_outstanding_report'] == 1 ? 'checked' : ''; ?>>
                        <label for="customised_unbilled_dockets_in_leder_and_outstanding_report" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div> -->


                <div class="radio" style="display: inline;">
                    <input name="account[customised_unbilled_dockets_in_leder_and_outstanding_report]" type="radio" id="date_Option_2" value="3" class="radio_format" autocomplete="nope" <?php echo isset($setting['customised_unbilled_dockets_in_leder_and_outstanding_report']) && $setting['customised_unbilled_dockets_in_leder_and_outstanding_report'] == 3 ? 'checked' : ''; ?>>
                    <label for="date_Option_2">OPENING BALANCE DATE</label>
                </div>
                <div class="radio" style="display: inline;">
                    <input name="account[customised_unbilled_dockets_in_leder_and_outstanding_report]" type="radio" id="date_Option_1" value="1" class="radio_format" autocomplete="nope" <?php echo isset($setting['customised_unbilled_dockets_in_leder_and_outstanding_report']) && $setting['customised_unbilled_dockets_in_leder_and_outstanding_report'] == 1 ? 'checked' : ''; ?>>
                    <label for="date_Option_1">APP SETTING</label>
                </div>


            </div>
            <?php if ($mode != "add_comments") { ?>
                <div class="col-sm-2">
                    <input type="date" class="form-control pull-right datepicker_text" name="account[account_ledger_start_date]" value="<?php echo isset($setting['account_ledger_start_date']) ? get_format_date(DATE_INPUT_FORMAT, ($setting['account_ledger_start_date'])) : ''; ?>">
                </div>
            <?php } ?>
        </div>
    </div>


    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['customised_unbilled_dockets_in_leder_and_outstanding_report_purchase']) ? $setting_comment['customised_unbilled_dockets_in_leder_and_outstanding_report_purchase'] : ''; ?>" class="col-sm-6 col-form-label">SHOW CUSTOMISED UNBILLED AWBS IN LEDER AND OUTSTANDING REPORT - PURCHASE ACCOUNT</label>
            <div class="col-sm-1">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="account[customised_unbilled_dockets_in_leder_and_outstanding_report_purchase]" id="" cols="80" rows="1"><?php echo isset($setting['customised_unbilled_dockets_in_leder_and_outstanding_report_purchase']) ? $setting['customised_unbilled_dockets_in_leder_and_outstanding_report_purchase'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="customised_unbilled_dockets_in_leder_and_outstanding_report_purchase" value="1" autocomplete="nope" name="account[customised_unbilled_dockets_in_leder_and_outstanding_report_purchase]" <?php echo isset($setting['customised_unbilled_dockets_in_leder_and_outstanding_report_purchase']) && $setting['customised_unbilled_dockets_in_leder_and_outstanding_report_purchase'] == 1 ? 'checked' : ''; ?>>
                        <label for="customised_unbilled_dockets_in_leder_and_outstanding_report_purchase" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
            <?php if ($mode != "add_comments") { ?>
                <div class="col-sm-3">
                    <input type="date" class="form-control pull-right datepicker_text" name="account[account_ledger_start_date_purchase]" value="<?php echo isset($setting['account_ledger_start_date_purchase']) ? get_format_date(DATE_INPUT_FORMAT, ($setting['account_ledger_start_date_purchase'])) : ''; ?>">
                </div>
            <?php } ?>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['get_description_from_master_in_credit_and_debit_note']) ? $setting_comment['get_description_from_master_in_credit_and_debit_note'] : ''; ?>" class="col-sm-6 col-form-label">Get Description from Master in Credit and Debit Note</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="account[get_description_from_master_in_credit_and_debit_note]" id="" cols="80" rows="1"><?php echo isset($setting['get_description_from_master_in_credit_and_debit_note']) ? $setting['get_description_from_master_in_credit_and_debit_note'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="get_description_from_master_in_credit_and_debit_note" value="1" autocomplete="nope" name="account[get_description_from_master_in_credit_and_debit_note]" <?php echo isset($setting['get_description_from_master_in_credit_and_debit_note']) && $setting['get_description_from_master_in_credit_and_debit_note'] == 1 ? 'checked' : ''; ?>>
                        <label for="get_description_from_master_in_credit_and_debit_note" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['make_payment_date_as_todays_date']) ? $setting_comment['make_payment_date_as_todays_date'] : ''; ?>" class="col-sm-6 col-form-label">Make Payment Date as Today's Date</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="account[make_payment_date_as_todays_date]" id="" cols="80" rows="1"><?php echo isset($setting['make_payment_date_as_todays_date']) ? $setting['make_payment_date_as_todays_date'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="make_payment_date_as_todays_date" value="1" autocomplete="nope" name="account[make_payment_date_as_todays_date]" <?php echo isset($setting['make_payment_date_as_todays_date']) && $setting['make_payment_date_as_todays_date'] == 1 ? 'checked' : ''; ?>>
                        <label for="make_payment_date_as_todays_date" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['make_payment_date_as_3_days_date']) ? $setting_comment['make_payment_date_as_3_days_date'] : ''; ?>" class="col-sm-6 col-form-label">Make Payment Date as 3 days past and future</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="account[make_payment_date_as_3_days_date]" id="" cols="80" rows="1"><?php echo isset($setting['make_payment_date_as_3_days_date']) ? $setting['make_payment_date_as_3_days_date'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="make_payment_date_as_3_days_date" value="1" autocomplete="nope" name="account[make_payment_date_as_3_days_date]" <?php echo isset($setting['make_payment_date_as_3_days_date']) && $setting['make_payment_date_as_3_days_date'] == 1 ? 'checked' : ''; ?>>
                        <label for="make_payment_date_as_3_days_date" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['make_receipt_date_as_todays_date']) ? $setting_comment['make_receipt_date_as_todays_date'] : ''; ?>" class="col-sm-6 col-form-label">Make Receipt Date as Today's Date</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="account[make_receipt_date_as_todays_date]" id="" cols="80" rows="1"><?php echo isset($setting['make_receipt_date_as_todays_date']) ? $setting['make_receipt_date_as_todays_date'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="make_receipt_date_as_todays_date" value="1" autocomplete="nope" name="account[make_receipt_date_as_todays_date]" <?php echo isset($setting['make_receipt_date_as_todays_date']) && $setting['make_receipt_date_as_todays_date'] == 1 ? 'checked' : ''; ?>>
                        <label for="make_receipt_date_as_todays_date" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['make_receipt_date_as_3_days_date']) ? $setting_comment['make_receipt_date_as_3_days_date'] : ''; ?>" class="col-sm-6 col-form-label">Make Receipt Date as 3 days past and future</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="account[make_receipt_date_as_3_days_date]" id="" cols="80" rows="1"><?php echo isset($setting['make_receipt_date_as_3_days_date']) ? $setting['make_receipt_date_as_3_days_date'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="make_receipt_date_as_3_days_date" value="1" autocomplete="nope" name="account[make_receipt_date_as_3_days_date]" <?php echo isset($setting['make_receipt_date_as_3_days_date']) && $setting['make_receipt_date_as_3_days_date'] == 1 ? 'checked' : ''; ?>>
                        <label for="make_receipt_date_as_3_days_date" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_payment_terms_and_bank_detail_in_credit_debit_pdf']) ? $setting_comment['show_payment_terms_and_bank_detail_in_credit_debit_pdf'] : ''; ?>" class="col-sm-6 col-form-label">Show Payment Terms & Bank Detail In Customer Credit / Debit PDF</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="account[show_payment_terms_and_bank_detail_in_credit_debit_pdf]" id="" cols="80" rows="1"><?php echo isset($setting['show_payment_terms_and_bank_detail_in_credit_debit_pdf']) ? $setting['show_payment_terms_and_bank_detail_in_credit_debit_pdf'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_payment_terms_and_bank_detail_in_credit_debit_pdf" value="1" autocomplete="nope" name="account[show_payment_terms_and_bank_detail_in_credit_debit_pdf]" <?php echo isset($setting['show_payment_terms_and_bank_detail_in_credit_debit_pdf']) && $setting['show_payment_terms_and_bank_detail_in_credit_debit_pdf'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_payment_terms_and_bank_detail_in_credit_debit_pdf" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['auto_generate_credit_debit_number_by_prefix']) ? $setting_comment['auto_generate_credit_debit_number_by_prefix'] : ''; ?>" class="col-sm-6 col-form-label">Auto Generate Credit Debit Number By Prefix</label>
            <div class="col-sm-1">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="account[auto_generate_credit_debit_number_by_prefix]" id="" cols="80" rows="1"><?php echo isset($setting['auto_generate_credit_debit_number_by_prefix']) ? $setting['auto_generate_credit_debit_number_by_prefix'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="auto_generate_credit_debit_number_by_prefix" value="1" autocomplete="nope" name="account[auto_generate_credit_debit_number_by_prefix]" <?php echo isset($setting['auto_generate_credit_debit_number_by_prefix']) && $setting['auto_generate_credit_debit_number_by_prefix'] == 1 ? 'checked' : ''; ?>>
                        <label for="auto_generate_credit_debit_number_by_prefix" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
            <?php if ($mode != "add_comments") { ?>
                <?php
                $autono_style = 'd-none';
                if (isset($setting['auto_generate_credit_debit_number_by_prefix']) && $setting['auto_generate_credit_debit_number_by_prefix'] == 1) {
                    $autono_style = '';
                }
                ?>
                <div id="credit_note_range" class="col-sm-5 <?php echo $autono_style; ?>">
                    <div class="col-sm-4" style="display: inline;float: left;">
                        <div class="form-group row">
                            <label class="col-sm-6">Credit Prefix</label>
                            <input type="text" class="form-control col-sm-6" name="account[credit_note_prefix]" value="<?php echo $setting['credit_note_prefix'] ? $setting['credit_note_prefix'] : ''; ?>" />
                        </div>
                    </div>
                    <div class="col-sm-8" style="display: inline;float: right;">
                        <div class="form-group row">
                            <label class="col-sm-4">Credit Range</label>
                            <input type="text" class="form-control col-sm-8" name="account[credit_note_range]" value="<?php echo $setting['credit_note_range'] ? $setting['credit_note_range'] : ''; ?>" />
                        </div>
                    </div>
                    <div class="col-sm-4" style="display: inline;float: left;">
                        <div class="form-group row">
                            <label class="col-sm-6">Debit Prefix</label>
                            <input type="text" class="form-control col-sm-6" name="account[debit_note_prefix]" value="<?php echo $setting['debit_note_prefix'] ? $setting['debit_note_prefix'] : ''; ?>" />
                        </div>
                    </div>
                    <div class="col-sm-8" style="display: inline;float: right;">
                        <div class="form-group row">
                            <label class="col-sm-4">Debit Range</label>
                            <input type="text" class="form-control col-sm-8" name="account[debit_note_range]" value="<?php echo $setting['debit_note_range'] ? $setting['debit_note_range'] : ''; ?>" />
                        </div>
                    </div>
                    <div class="col-sm-4" style="display: inline;float: left;">
                        <div class="form-group row">
                            <label class="col-sm-6 pl-0">NOT ACCEPTED Prefix</label>
                            <input type="text" class="form-control col-sm-6" name="account[not_accepted_note_prefix]" value="<?php echo $setting['not_accepted_note_prefix'] ? $setting['not_accepted_note_prefix'] : ''; ?>" />
                        </div>
                    </div>
                    <div class="col-sm-8" style="display: inline;float: right;">
                        <div class="form-group row">
                            <label class="col-sm-4">NOT ACCEPTED Range</label>
                            <input type="text" class="form-control col-sm-8" name="account[not_accepted_note_range]" value="<?php echo $setting['not_accepted_note_range'] ? $setting['not_accepted_note_range'] : ''; ?>" />
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['dont_show_tds_and_deductions_and_roundoff_in_outstanding']) ? $setting_comment['dont_show_tds_and_deductions_and_roundoff_in_outstanding'] : ''; ?>" class="col-sm-6 col-form-label">Don't show TDS and deductions and round off in outstanding</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="account[dont_show_tds_and_deductions_and_roundoff_in_outstanding]" id="" cols="80" rows="1"><?php echo isset($setting['dont_show_tds_and_deductions_and_roundoff_in_outstanding']) ? $setting['dont_show_tds_and_deductions_and_roundoff_in_outstanding'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="dont_show_tds_and_deductions_and_roundoff_in_outstanding" value="1" autocomplete="nope" name="account[dont_show_tds_and_deductions_and_roundoff_in_outstanding]" <?php echo isset($setting['dont_show_tds_and_deductions_and_roundoff_in_outstanding']) && $setting['dont_show_tds_and_deductions_and_roundoff_in_outstanding'] == 1 ? 'checked' : ''; ?>>
                        <label for="dont_show_tds_and_deductions_and_roundoff_in_outstanding" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['rename_ledger_to_summary']) ? $setting_comment['rename_ledger_to_summary'] : ''; ?>" class="col-sm-6 col-form-label">Rename ledger to summary</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="account[rename_ledger_to_summary]" id="" cols="80" rows="1"><?php echo isset($setting['rename_ledger_to_summary']) ? $setting['rename_ledger_to_summary'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="rename_ledger_to_summary" value="1" autocomplete="nope" name="account[rename_ledger_to_summary]" <?php echo isset($setting['rename_ledger_to_summary']) && $setting['rename_ledger_to_summary'] == 1 ? 'checked' : ''; ?>>
                        <label for="rename_ledger_to_summary" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['auto_generate_receipt_number']) ? $setting_comment['auto_generate_receipt_number'] : ''; ?>" class="col-sm-6 col-form-label">Auto Generate Receipt Number</label>
            <div class="col-sm-1">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="account[auto_generate_receipt_number]" id="" cols="80" rows="1"><?php echo isset($setting['auto_generate_receipt_number']) ? $setting['auto_generate_receipt_number'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="auto_generate_receipt_number" value="1" autocomplete="nope" name="account[auto_generate_receipt_number]" <?php echo isset($setting['auto_generate_receipt_number']) && $setting['auto_generate_receipt_number'] == 1 ? 'checked' : ''; ?>>
                        <label for="auto_generate_receipt_number" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
            <?php if ($mode != "add_comments") { ?>
                <?php
                $autono_style = 'd-none';
                if (isset($setting['auto_generate_receipt_number']) && $setting['auto_generate_receipt_number'] == 1) {
                    $autono_style = '';
                }
                ?>
            <?php } ?>
        </div>
    </div>
    <?php if ($mode != "add_comments") { ?>
        <div id="receipt_range" class="col-6 <?php echo $autono_style; ?>" style="border: 2px solid black;">
            <div class="form-group row">
                <div class="col-md-12">
                    <div class="col-md-12">
                        <div class="form-group row" style="margin-top: 15px;">
                            <label class="col-md-4">Prefix</label>
                            <input type="text" class="form-control col-sm-8" name="account[receipt_prefix]" value="<?php echo $setting['receipt_prefix'] ? $setting['receipt_prefix'] : ''; ?>" />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-4">Range</label>
                            <div class="col-md-4" style="padding-left: 0 !important;">
                                <label>From</label>
                                <input type="text" class="form-control" name="account[receipt_range_from]" value="<?php echo $setting['receipt_range_from'] ? $setting['receipt_range_from'] : ''; ?>" />
                            </div>
                            <div class="col-md-4" style="padding-right: 0 !important;">
                                <label>Till</label>
                                <input type="text" class="form-control" name="account[receipt_range_till]" value="<?php echo $setting['receipt_range_till'] ? $setting['receipt_range_till'] : ''; ?>" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4">Suffix</label>
                            <input type="text" class="form-control col-sm-8" name="account[receipt_suffix]" value="<?php echo $setting['receipt_suffix'] ? $setting['receipt_suffix'] : ''; ?>" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>


    <div class="col-12 mt-4">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['auto_generate_payment_number']) ? $setting_comment['auto_generate_payment_number'] : ''; ?>" class="col-sm-6 col-form-label">Auto Generate Payment Number</label>
            <div class="col-sm-1">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="account[auto_generate_payment_number]" id="" cols="80" rows="1"><?php echo isset($setting['auto_generate_payment_number']) ? $setting['auto_generate_payment_number'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="auto_generate_payment_number" value="1" autocomplete="nope" name="account[auto_generate_payment_number]" <?php echo isset($setting['auto_generate_payment_number']) && $setting['auto_generate_payment_number'] == 1 ? 'checked' : ''; ?>>
                        <label for="auto_generate_payment_number" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
            <?php if ($mode != "add_comments") { ?>
                <?php
                $autono_style = 'd-none';
                if (isset($setting['auto_generate_payment_number']) && $setting['auto_generate_payment_number'] == 1) {
                    $autono_style = '';
                }
                ?>
            <?php } ?>
        </div>
        <?php if ($mode != "add_comments") { ?>
            <div id="payment_range" class="col-6 <?php echo $autono_style; ?>" style="border: 2px solid black;">
                <div class="form-group row">
                    <div class="col-md-12">
                        <div class="col-md-12">
                            <div class="form-group row" style="margin-top: 15px;">
                                <label class="col-md-4">Prefix</label>
                                <input type="text" class="form-control col-sm-8" name="account[payment_prefix]" value="<?php echo $setting['payment_prefix'] ? $setting['payment_prefix'] : ''; ?>" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-md-4">Range</label>
                                <div class="col-md-4" style="padding-left: 0 !important;">
                                    <label>From</label>
                                    <input type="text" class="form-control" name="account[payment_range_from]" value="<?php echo $setting['payment_range_from'] ? $setting['payment_range_from'] : ''; ?>" />
                                </div>
                                <div class="col-md-4" style="padding-right: 0 !important;">
                                    <label>Till</label>
                                    <input type="text" class="form-control" name="account[payment_range_till]" value="<?php echo $setting['payment_range_till'] ? $setting['payment_range_till'] : ''; ?>" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4">Suffix</label>
                                <input type="text" class="form-control col-sm-8" name="account[payment_suffix]" value="<?php echo $setting['payment_suffix'] ? $setting['payment_suffix'] : ''; ?>" />
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_payment_terms_in_customer_credit_pdf']) ? $setting_comment['show_payment_terms_in_customer_credit_pdf'] : ''; ?>" class="col-sm-6 col-form-label">Show Payment Terms In Customer Credit / Debit PDF</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="account[show_payment_terms_in_customer_credit_pdf]" id="" cols="80" rows="1"><?php echo isset($setting['show_payment_terms_in_customer_credit_pdf']) ? $setting['show_payment_terms_in_customer_credit_pdf'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_payment_terms_in_customer_credit_pdf" value="1" autocomplete="nope" name="account[show_payment_terms_in_customer_credit_pdf]" <?php echo isset($setting['show_payment_terms_in_customer_credit_pdf']) && $setting['show_payment_terms_in_customer_credit_pdf'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_payment_terms_in_customer_credit_pdf" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_signature_img_in_customer_credit_pdf']) ? $setting_comment['show_signature_img_in_customer_credit_pdf'] : ''; ?>" class="col-sm-6 col-form-label">Show Signature Image In Customer Credit / Debit PDF</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="account[show_signature_img_in_customer_credit_pdf]" id="" cols="80" rows="1"><?php echo isset($setting['show_signature_img_in_customer_credit_pdf']) ? $setting['show_signature_img_in_customer_credit_pdf'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_signature_img_in_customer_credit_pdf" value="1" autocomplete="nope" name="account[show_signature_img_in_customer_credit_pdf]" <?php echo isset($setting['show_signature_img_in_customer_credit_pdf']) && $setting['show_signature_img_in_customer_credit_pdf'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_signature_img_in_customer_credit_pdf" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_stamp_img_in_customer_credit_pdf']) ? $setting_comment['show_stamp_img_in_customer_credit_pdf'] : ''; ?>" class="col-sm-6 col-form-label">Show Stamp Image In Customer Credit / Debit PDF</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="account[show_stamp_img_in_customer_credit_pdf]" id="" cols="80" rows="1"><?php echo isset($setting['show_stamp_img_in_customer_credit_pdf']) ? $setting['show_stamp_img_in_customer_credit_pdf'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_stamp_img_in_customer_credit_pdf" value="1" autocomplete="nope" name="account[show_stamp_img_in_customer_credit_pdf]" <?php echo isset($setting['show_stamp_img_in_customer_credit_pdf']) && $setting['show_stamp_img_in_customer_credit_pdf'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_stamp_img_in_customer_credit_pdf" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['customer_outstanding_data']) ? $setting_comment['customer_outstanding_data'] : ''; ?>" class="col-sm-6 col-form-label">SEND CUSTOMER OUTSTANDING DATA FROM</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="account[customer_outstanding_data]" id="" cols="80" rows="1"><?php echo isset($setting['customer_outstanding_data']) ? $setting['customer_outstanding_data'] : ''; ?></textarea>
                    <?php } else { ?>
                        <select name="account[customer_outstanding_data]">
                            <option value="1" <?php echo isset($setting['customer_outstanding_data']) && $setting['customer_outstanding_data'] == 1  ? 'selected' : ''; ?>>LEDGER</option>
                            <option value="2" <?php echo isset($setting['customer_outstanding_data']) && $setting['customer_outstanding_data'] == 2 ? 'selected' : ''; ?>>OUTSTANDING</option>
                        </select>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" class="col-sm-6 col-form-label">By default tick Include option in receipt</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <input type="checkbox" id="tick_receipt_include" value="1" autocomplete="nope" name="account[tick_receipt_include]" <?php echo isset($setting['tick_receipt_include']) && $setting['tick_receipt_include'] == 1 ? 'checked' : ''; ?>>
                    <label for="tick_receipt_include" style="height: 10px !important;"> </label>
                </div>
            </div>
        </div>
    </div>


    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" class="col-sm-6 col-form-label">show NOT ACCEPTED option in Credit Note</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <input type="checkbox" id="show_not_accepted_note" value="1" autocomplete="nope" name="account[show_not_accepted_note]" <?php echo isset($setting['show_not_accepted_note']) && $setting['show_not_accepted_note'] == 1 ? 'checked' : ''; ?>>
                    <label for="show_not_accepted_note" style="height: 10px !important;"> </label>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['lock_all_receipt']) ? $setting_comment['lock_all_receipt'] : ''; ?>" class="col-sm-6 col-form-label">Lock All Receipt</label>

            <div class="col-sm-1">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="account[lock_all_receipt]" id="" cols="80" rows="1"><?php echo isset($setting['lock_all_receipt']) ? $setting['lock_all_receipt'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="lock_all_receipt" value="1" autocomplete="nope" name="account[lock_all_receipt]" <?php echo isset($setting['lock_all_receipt']) && $setting['lock_all_receipt'] == 1 ? 'checked' : ''; ?>>
                        <label for="lock_all_receipt" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
            <?php if ($mode != "add_comments") { ?>
                <div class="col-sm-2" style="display: flex;">
                    <label for="cust_id" title="<?php echo isset($setting_comment['lock_all_receipt']) ? $setting_comment['lock_all_receipt'] : ''; ?>" class="col-sm-6 col-form-label">No of days:</label>
                    <input type="number" class="form-control" name="account[no_of_days_for_receipt_lock]" value="<?php echo isset($setting['no_of_days_for_receipt_lock']) ? $setting['no_of_days_for_receipt_lock'] : 0; ?>" />
                </div>
            <?php } ?>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" class="col-sm-6 col-form-label">HIDE INACTIVE CUSTOMER FROM RECEIPTS</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <input type="checkbox" id="hide_inactive_customer_from_receipt" value="1" autocomplete="nope" name="account[hide_inactive_customer_from_receipt]" <?php echo isset($setting['hide_inactive_customer_from_receipt']) && $setting['hide_inactive_customer_from_receipt'] == 1 ? 'checked' : ''; ?>>
                    <label for="hide_inactive_customer_from_receipt" style="height: 10px !important;"> </label>
                </div>
            </div>
        </div>
    </div>


</div>
<script>
    $("#make_payment_date_as_todays_date").change(function() {
        if (this.checked) {
            //Do stuff
            $('#make_payment_date_as_3_days_date').attr("disabled", true)
        } else {
            $('#make_payment_date_as_3_days_date').removeAttr("disabled", true)
        }
    });

    $("#make_payment_date_as_3_days_date").change(function() {
        if (this.checked) {
            //Do stuff
            $('#make_payment_date_as_todays_date').attr("disabled", true)
        } else {
            $('#make_payment_date_as_todays_date').removeAttr("disabled", true)
        }
    });

    $("#make_receipt_date_as_todays_date").change(function() {
        if (this.checked) {
            //Do stuff
            $('#make_receipt_date_as_3_days_date').attr("disabled", true)
        } else {
            $('#make_receipt_date_as_3_days_date').removeAttr("disabled", true)
        }
    });

    $("#make_receipt_date_as_3_days_date").change(function() {
        if (this.checked) {
            //Do stuff
            $('#make_receipt_date_as_todays_date').attr("disabled", true)
        } else {
            $('#make_receipt_date_as_todays_date').removeAttr("disabled", true)
        }
    });

    $(document).ready(function() {
        $("#make_payment_date_as_todays_date").change();
        $("#make_payment_date_as_3_days_date").change();
        $("#make_receipt_date_as_todays_date").change();
        $("#make_receipt_date_as_3_days_date").change();
        $("#auto_generate_receipt_number").click(function() {
            if (this.checked) {
                $("#receipt_range").removeClass("d-none");
            } else {
                $("#receipt_range").addClass("d-none");
                $('input[name="master[receipt_prefix]"]').val('');
                $('input[name="master[receipt_suffix]"]').val('');
                $('input[name="master[receipt_range_from]"]').val('');
                $('input[name="master[receipt_range_till]"]').val('');
            }
        });

        $("#auto_generate_payment_number").click(function() {
            if (this.checked) {
                $("#payment_range").removeClass("d-none");
            } else {
                $("#payment_range").addClass("d-none");
                $('input[name="master[payment_prefix]"]').val('');
                $('input[name="master[payment_suffix]"]').val('');
                $('input[name="master[payment_range_from]"]').val('');
                $('input[name="master[payment_range_till]"]').val('');
            }
        });

        $("#auto_generate_credit_debit_number_by_prefix").click(function() {
            if (this.checked) {
                $("#credit_note_range").removeClass("d-none");
            } else {
                $("#credit_note_range").addClass("d-none");
                $('input[name="master[credit_note_prefix]"]').val('');
                $('input[name="master[credit_note_range]"]').val('');
                $('input[name="master[debit_note_prefix]"]').val('');
                $('input[name="master[debit_note_range]"]').val('');
            }
        });

    });
</script>