<div class="tab-pane" id="invoice-tab">
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['create_invoice_by_inscan_date']) ? $setting_comment['create_invoice_by_inscan_date'] : ''; ?>" class="col-sm-6 col-form-label">Create Invoices by inscan date and not booking date</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="invoice[create_invoice_by_inscan_date]" id="" cols="80" rows="1"><?php echo isset($setting['create_invoice_by_inscan_date']) ? $setting['create_invoice_by_inscan_date'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="create_invoice_by_inscan_date" value="1" autocomplete="nope" name="invoice[create_invoice_by_inscan_date]" <?php echo isset($setting['create_invoice_by_inscan_date']) && $setting['create_invoice_by_inscan_date'] == 1 ? 'checked' : ''; ?>>
                        <label for="create_invoice_by_inscan_date" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['auto_generate_invoice_no']) ? $setting_comment['auto_generate_invoice_no'] : ''; ?>" class="col-sm-6 col-form-label">Auto Generate Invoice Number</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="invoice[auto_generate_invoice_no]" id="" cols="80" rows="1"><?php echo isset($setting['auto_generate_invoice_no']) ? $setting['auto_generate_invoice_no'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="auto_generate_invoice_no" value="1" autocomplete="nope" name="invoice[auto_generate_invoice_no]" <?php echo isset($setting['auto_generate_invoice_no']) && $setting['auto_generate_invoice_no'] == 1 ? 'checked' : ''; ?>>
                        <label for="auto_generate_invoice_no" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['enable_refresh_invoice_rates']) ? $setting_comment['enable_refresh_invoice_rates'] : ''; ?>" class="col-sm-6 col-form-label">Enable Refresh Invoice Rates Asynchronously</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="invoice[enable_refresh_invoice_rates]" id="" cols="80" rows="1"><?php echo isset($setting['enable_refresh_invoice_rates']) ? $setting['enable_refresh_invoice_rates'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_refresh_invoice_rates" value="1" autocomplete="nope" name="invoice[enable_refresh_invoice_rates]" <?php echo isset($setting['enable_refresh_invoice_rates']) && $setting['enable_refresh_invoice_rates'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_refresh_invoice_rates" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['remove_invoice_head_customer']) ? $setting_comment['remove_invoice_head_customer'] : ''; ?>" class="col-sm-6 col-form-label">remove invoice head from customer head and fetch invoice head from invoice range master</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="invoice[remove_invoice_head_customer]" id="" cols="80" rows="1"><?php echo isset($setting['remove_invoice_head_customer']) ? $setting['remove_invoice_head_customer'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="remove_invoice_head_customer" value="1" autocomplete="nope" name="invoice[remove_invoice_head_customer]" <?php echo isset($setting['remove_invoice_head_customer']) && $setting['remove_invoice_head_customer'] == 1 ? 'checked' : ''; ?>>
                        <label for="remove_invoice_head_customer" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['remove_invoice_head_customer']) ? $setting_comment['remove_invoice_head_customer'] : ''; ?>" class="col-sm-6 col-form-label">Enable TCPDF INVOICE</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="invoice[remove_invoice_head_customer]" id="" cols="80" rows="1"><?php echo isset($setting['remove_invoice_head_customer']) ? $setting['remove_invoice_head_customer'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_tcpdf_invoice" value="1" autocomplete="nope" name="invoice[enable_tcpdf_invoice]" <?php echo isset($setting['enable_tcpdf_invoice']) && $setting['enable_tcpdf_invoice'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_tcpdf_invoice" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_new_docketless_pdf_format']) ? $setting_comment['show_new_docketless_pdf_format'] : ''; ?>" class="col-sm-6 col-form-label">SHOW NEW DOCKETLESS PDF FORMAT</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="invoice[show_new_docketless_pdf_format]" id="" cols="80" rows="1"><?php echo isset($setting['show_new_docketless_pdf_format']) ? $setting['show_new_docketless_pdf_format'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_new_docketless_pdf_format" value="1" autocomplete="nope" name="invoice[show_new_docketless_pdf_format]" <?php echo isset($setting['show_new_docketless_pdf_format']) && $setting['show_new_docketless_pdf_format'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_new_docketless_pdf_format" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['get_invoice_currency_from_company']) ? $setting_comment['get_invoice_currency_from_company'] : ''; ?>" class="col-sm-6 col-form-label">Remove currency from customer and get it from company Master</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="invoice[get_invoice_currency_from_company]" id="" cols="80" rows="1"><?php echo isset($setting['get_invoice_currency_from_company']) ? $setting['get_invoice_currency_from_company'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="get_invoice_currency_from_company" value="1" autocomplete="nope" name="invoice[get_invoice_currency_from_company]" <?php echo isset($setting['get_invoice_currency_from_company']) && $setting['get_invoice_currency_from_company'] == 1 ? 'checked' : ''; ?>>
                        <label for="get_invoice_currency_from_company" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_stamp_img_in_docketless_pdf']) ? $setting_comment['show_stamp_img_in_docketless_pdf'] : ''; ?>" class="col-sm-6 col-form-label">Show Stamp in Docketless PDF </label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="invoice[show_stamp_img_in_docketless_pdf]" id="" cols="80" rows="1"><?php echo isset($setting['show_stamp_img_in_docketless_pdf']) ? $setting['show_stamp_img_in_docketless_pdf'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_stamp_img_in_docketless_pdf" value="1" autocomplete="nope" name="invoice[show_stamp_img_in_docketless_pdf]" <?php echo isset($setting['show_stamp_img_in_docketless_pdf']) && $setting['show_stamp_img_in_docketless_pdf'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_stamp_img_in_docketless_pdf" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_signature_img_in_docketless_pdf']) ? $setting_comment['show_signature_img_in_docketless_pdf'] : ''; ?>" class="col-sm-6 col-form-label">Show Signature in Docketless PDF</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="invoice[show_signature_img_in_docketless_pdf]" id="" cols="80" rows="1"><?php echo isset($setting['show_signature_img_in_docketless_pdf']) ? $setting['show_signature_img_in_docketless_pdf'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_signature_img_in_docketless_pdf" value="1" autocomplete="nope" name="invoice[show_signature_img_in_docketless_pdf]" <?php echo isset($setting['show_signature_img_in_docketless_pdf']) && $setting['show_signature_img_in_docketless_pdf'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_signature_img_in_docketless_pdf" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['set_due_date_in_invoice']) ? $setting_comment['set_due_date_in_invoice'] : ''; ?>" class="col-sm-6 col-form-label">SET DUE DATE IN INVOICE</label>
            <div class="col-sm-1">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="invoice[set_due_date_in_invoice]" id="" cols="80" rows="1"><?php echo isset($setting['set_due_date_in_invoice']) ? $setting['set_due_date_in_invoice'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="set_due_date_in_invoice" value="1" autocomplete="nope" name="invoice[set_due_date_in_invoice]" <?php echo isset($setting['set_due_date_in_invoice']) && $setting['set_due_date_in_invoice'] == 1 ? 'checked' : ''; ?>>
                        <label for="set_due_date_in_invoice" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
            <?php if ($mode != "add_comments") { ?>
                <div class="col-sm-3 row">
                    <label for="cust_id" class="col-sm-6 col-form-label">NO OF DAYS</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control pull-right datepicker_text" name="invoice[no_of_due_days_invoice]" value="<?php echo isset($setting['no_of_due_days_invoice']) ? $setting['no_of_due_days_invoice'] : ''; ?>">
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_customized_invoice_pdf']) ? $setting_comment['show_customized_invoice_pdf'] : ''; ?>" class="col-sm-6 col-form-label">SHOW CUSTOMIZED INVOICE PDF</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="invoice[show_customized_invoice_pdf]" id="" cols="80" rows="1"><?php echo isset($setting['show_customized_invoice_pdf']) ? $setting['show_customized_invoice_pdf'] : ''; ?></textarea>
                    <?php } else { ?>
                        <select name="invoice[show_customized_invoice_pdf]">
                            <option value="2" <?php echo isset($setting['show_customized_invoice_pdf']) && $setting['show_customized_invoice_pdf'] == 2 ? 'selected' : ''; ?>>HIDE</option>
                            <option value="1" <?php echo isset($setting['show_customized_invoice_pdf']) && $setting['show_customized_invoice_pdf'] == 1 ? 'selected' : ''; ?>>DEFAULT</option>
                            <option value="3" <?php echo isset($setting['show_customized_invoice_pdf']) && $setting['show_customized_invoice_pdf'] == 3 ? 'selected' : ''; ?>>DTD</option>
                            <option value="4" <?php echo isset($setting['show_customized_invoice_pdf']) && $setting['show_customized_invoice_pdf'] == 4 ? 'selected' : ''; ?>>PAFEX</option>
                        </select>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_customized_invoice_report']) ? $setting_comment['show_customized_invoice_report'] : ''; ?>" class="col-sm-6 col-form-label">SHOW CUSTOMIZED REPORT</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="invoice[show_customized_invoice_report]" id="" cols="80" rows="1"><?php echo isset($setting['show_customized_invoice_report']) ? $setting['show_customized_invoice_report'] : ''; ?></textarea>
                    <?php } else { ?>
                        <select name="invoice[show_customized_invoice_report]">
                            <option value="2" <?php echo isset($setting['show_customized_invoice_report']) && $setting['show_customized_invoice_report'] == 2 ? 'selected' : ''; ?>>SELECT</option>
                            <option value="1" <?php echo isset($setting['show_customized_invoice_report']) && $setting['show_customized_invoice_report'] == 1 ? 'selected' : ''; ?>>DEFAULT</option>
                            <option value="3" <?php echo isset($setting['show_customized_invoice_report']) && $setting['show_customized_invoice_report'] == 3 ? 'selected' : ''; ?>>OLD</option>
                        </select>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_message_line_in_invoice_entry']) ? $setting_comment['show_message_line_in_invoice_entry'] : ''; ?>" class="col-sm-6 col-form-label">SHOW MESSAGE LINE IN INVOICE ENTRY (DTD)</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="invoice[show_message_line_in_invoice_entry]" id="" cols="80" rows="1"><?php echo isset($setting['show_message_line_in_invoice_entry']) ? $setting['show_message_line_in_invoice_entry'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_message_line_in_invoice_entry" value="1" autocomplete="nope" name="invoice[show_message_line_in_invoice_entry]" <?php echo isset($setting['show_message_line_in_invoice_entry']) && $setting['show_message_line_in_invoice_entry'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_message_line_in_invoice_entry" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>


    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['void_docket_after_invoice_create']) ? $setting_comment['void_docket_after_invoice_create'] : ''; ?>" class="col-sm-6 col-form-label">void shipment even if the invoice is created</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="invoice[void_docket_after_invoice_create]" id="" cols="80" rows="1"><?php echo isset($setting['void_docket_after_invoice_create']) ? $setting['void_docket_after_invoice_create'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="void_docket_after_invoice_create" value="1" autocomplete="nope" name="invoice[void_docket_after_invoice_create]" <?php echo isset($setting['void_docket_after_invoice_create']) && $setting['void_docket_after_invoice_create'] == 1 ? 'checked' : ''; ?>>
                        <label for="void_docket_after_invoice_create" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['invoice_no_in_file_name']) ? $setting_comment['invoice_no_in_file_name'] : ''; ?>" class="col-sm-6 col-form-label">Show Invoive No In Excel File Name </label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="invoice[invoice_no_in_file_name]" id="" cols="80" rows="1"><?php echo isset($setting['invoice_no_in_file_name']) ? $setting['invoice_no_in_file_name'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="invoice_no_in_file_name" value="1" autocomplete="nope" name="invoice[invoice_no_in_file_name]" <?php echo isset($setting['invoice_no_in_file_name']) && $setting['invoice_no_in_file_name'] == 1 ? 'checked' : ''; ?>>
                        <label for="invoice_no_in_file_name" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['send_excel_in_email']) ? $setting_comment['send_excel_in_email'] : ''; ?>" class="col-sm-6 col-form-label">SEND EXCEL IN INVOICE EMAIL ATTACHMENT</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="invoice[send_excel_in_email]" id="" cols="80" rows="1"><?php echo isset($setting['send_excel_in_email']) ? $setting['send_excel_in_email'] : ''; ?></textarea>
                    <?php } else { ?>
                        <select name="invoice[send_excel_in_email]">
                            <option value="1" <?php echo isset($setting['send_excel_in_email']) && $setting['send_excel_in_email'] == 1 ? 'selected' : ''; ?>>DEFAULT</option>
                            <option value="2" <?php echo isset($setting['send_excel_in_email']) && $setting['send_excel_in_email'] == 2 ? 'selected' : ''; ?>>EXCEL 2</option>
                            <option value="3" <?php echo isset($setting['send_excel_in_email']) && $setting['send_excel_in_email'] == 3 ? 'selected' : ''; ?>>BOTH</option>
                        </select>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['dont_round_grand_total_in_invoice']) ? $setting_comment['dont_round_grand_total_in_invoice'] : ''; ?>" class="col-sm-6 col-form-label">DON'T ROUND OFF INVOICE GRAND TOTAL</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="invoice[dont_round_grand_total_in_invoice]" id="" cols="80" rows="1"><?php echo isset($setting['dont_round_grand_total_in_invoice']) ? $setting['dont_round_grand_total_in_invoice'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="dont_round_grand_total_in_invoice" value="1" autocomplete="nope" name="invoice[dont_round_grand_total_in_invoice]" <?php echo isset($setting['dont_round_grand_total_in_invoice']) && $setting['dont_round_grand_total_in_invoice'] == 1 ? 'checked' : ''; ?>>
                        <label for="dont_round_grand_total_in_invoice" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>