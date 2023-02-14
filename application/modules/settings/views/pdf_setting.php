<div class="tab-pane" id="pdf-tab">
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_customized_pdf1']) ? $setting_comment['show_customized_pdf1'] : ''; ?>" class="col-sm-6 col-form-label">SHOW CUSTOMIZED PDF1</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[show_customized_pdf1]" id="" cols="80" rows="1"><?php echo isset($setting['show_customized_pdf1']) ? $setting['show_customized_pdf1'] : ''; ?></textarea>
                    <?php } else { ?>
                        <select name="pdf[show_customized_pdf1]">
                            <option value="1" <?php echo isset($setting['show_customized_pdf1']) && $setting['show_customized_pdf1'] == 1  ? 'selected' : ''; ?>>DEFAULT</option>
                            <option value="2" <?php echo isset($setting['show_customized_pdf1']) && $setting['show_customized_pdf1'] == 2 ? 'selected' : ''; ?>>HIDE</option>
                            <option value="3" <?php echo isset($setting['show_customized_pdf1']) && $setting['show_customized_pdf1'] == 3 ? 'selected' : ''; ?>>SOHEM</option>
                            <option value="4" <?php echo isset($setting['show_customized_pdf1']) && $setting['show_customized_pdf1'] == 4 ? 'selected' : ''; ?>>ACM</option>
                            <option value="5" <?php echo isset($setting['show_customized_pdf1']) && $setting['show_customized_pdf1'] == 5 ? 'selected' : ''; ?>>ORBIT</option>
                            <option value="6" <?php echo isset($setting['show_customized_pdf1']) && $setting['show_customized_pdf1'] == 6 ? 'selected' : ''; ?>>ORIENT</option>
                            <option value="7" <?php echo isset($setting['show_customized_pdf1']) && $setting['show_customized_pdf1'] == 7 ? 'selected' : ''; ?>>RHL</option>
                            <option value="8" <?php echo isset($setting['show_customized_pdf1']) && $setting['show_customized_pdf1'] == 8 ? 'selected' : ''; ?>>BHARAT</option>
                            <option value="9" <?php echo isset($setting['show_customized_pdf1']) && $setting['show_customized_pdf1'] == 9 ? 'selected' : ''; ?>>GWE</option>
                            <option value="10" <?php echo isset($setting['show_customized_pdf1']) && $setting['show_customized_pdf1'] == 10 ? 'selected' : ''; ?>>V WIN</option>
                            <option value="11" <?php echo isset($setting['show_customized_pdf1']) && $setting['show_customized_pdf1'] == 11 ? 'selected' : ''; ?>>SAIRAJ</option>
                            <option value="12" <?php echo isset($setting['show_customized_pdf1']) && $setting['show_customized_pdf1'] == 12 ? 'selected' : ''; ?>>ELS</option>
                            <option value="13" <?php echo isset($setting['show_customized_pdf1']) && $setting['show_customized_pdf1'] == 13 ? 'selected' : ''; ?>>SUPREME</option>
                            <option value="14" <?php echo isset($setting['show_customized_pdf1']) && $setting['show_customized_pdf1'] == 14 ? 'selected' : ''; ?>>DTD</option>
                            <option value="15" <?php echo isset($setting['show_customized_pdf1']) && $setting['show_customized_pdf1'] == 15 ? 'selected' : ''; ?>>BONDS</option>
                            <option value="16" <?php echo isset($setting['show_customized_pdf1']) && $setting['show_customized_pdf1'] == 16 ? 'selected' : ''; ?>>PANNEST</option>
                            <option value="17" <?php echo isset($setting['show_customized_pdf1']) && $setting['show_customized_pdf1'] == 17 ? 'selected' : ''; ?>>ONS</option>
                            <option value="18" <?php echo isset($setting['show_customized_pdf1']) && $setting['show_customized_pdf1'] == 18 ? 'selected' : ''; ?>>SSBC</option>
                            <option value="19" <?php echo isset($setting['show_customized_pdf1']) && $setting['show_customized_pdf1'] == 19 ? 'selected' : ''; ?>>PEGASUS</option>
                            <option value="20" <?php echo isset($setting['show_customized_pdf1']) && $setting['show_customized_pdf1'] == 20 ? 'selected' : ''; ?>>EEPL</option>
                            <option value="21" <?php echo isset($setting['show_customized_pdf1']) && $setting['show_customized_pdf1'] == 21 ? 'selected' : ''; ?>>MYS</option>
                            <option value="22" <?php echo isset($setting['show_customized_pdf1']) && $setting['show_customized_pdf1'] == 22 ? 'selected' : ''; ?>>BHAVANI</option>
                            <option value="23" <?php echo isset($setting['show_customized_pdf1']) && $setting['show_customized_pdf1'] == 23 ? 'selected' : ''; ?>>OM INTL</option>
                            <option value="24" <?php echo isset($setting['show_customized_pdf1']) && $setting['show_customized_pdf1'] == 24 ? 'selected' : ''; ?>>NAVALAI</option>
                            <option value="25" <?php echo isset($setting['show_customized_pdf1']) && $setting['show_customized_pdf1'] == 25 ? 'selected' : ''; ?>>AWCC</option>
                            <option value="26" <?php echo isset($setting['show_customized_pdf1']) && $setting['show_customized_pdf1'] == 26 ? 'selected' : ''; ?>>SHREE SHYAM</option>
                            <option value="27" <?php echo isset($setting['show_customized_pdf1']) && $setting['show_customized_pdf1'] == 27 ? 'selected' : ''; ?>>SHREE SHAKTI</option>
                            <option value="28" <?php echo isset($setting['show_customized_pdf1']) && $setting['show_customized_pdf1'] == 28 ? 'selected' : ''; ?>>GRAND SPEED</option>
                            <option value="29" <?php echo isset($setting['show_customized_pdf1']) && $setting['show_customized_pdf1'] == 29 ? 'selected' : ''; ?>>TOTAL </option>
                            <option value="30" <?php echo isset($setting['show_customized_pdf1']) && $setting['show_customized_pdf1'] == 30 ? 'selected' : ''; ?>>HI SPEED</option>
                            <option value="31" <?php echo isset($setting['show_customized_pdf1']) && $setting['show_customized_pdf1'] == 31 ? 'selected' : ''; ?>>DAAKIYAWALA</option>
                        </select>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_customized_pdf2']) ? $setting_comment['show_customized_pdf2'] : ''; ?>" class="col-sm-6 col-form-label">SHOW CUSTOMIZED PDF2</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[show_customized_pdf2]" id="" cols="80" rows="1"><?php echo isset($setting['show_customized_pdf2']) ? $setting['show_customized_pdf2'] : ''; ?></textarea>
                    <?php } else { ?>
                        <select name="pdf[show_customized_pdf2]">
                            <option value="1" <?php echo isset($setting['show_customized_pdf2']) && $setting['show_customized_pdf2'] == 1 ? 'selected' : ''; ?>>DEFAULT</option>
                            <option value="2" <?php echo isset($setting['show_customized_pdf2']) && $setting['show_customized_pdf2'] == 2 ? 'selected' : ''; ?>>HIDE</option>
                            <option value="3" <?php echo isset($setting['show_customized_pdf2']) && $setting['show_customized_pdf2'] == 3 ? 'selected' : ''; ?>>SOHEM</option>
                            <option value="4" <?php echo isset($setting['show_customized_pdf2']) && $setting['show_customized_pdf2'] == 4 ? 'selected' : ''; ?>>ACM</option>
                            <option value="5" <?php echo isset($setting['show_customized_pdf2']) && $setting['show_customized_pdf2'] == 5 ? 'selected' : ''; ?>>Orbit</option>
                            <option value="6" <?php echo isset($setting['show_customized_pdf2']) && $setting['show_customized_pdf2'] == 6 ? 'selected' : ''; ?>>SAIRAJ</option>
                            <option value="7" <?php echo isset($setting['show_customized_pdf2']) && $setting['show_customized_pdf2'] == 7 ? 'selected' : ''; ?>>V WIN</option>
                            <option value="8" <?php echo isset($setting['show_customized_pdf2']) && $setting['show_customized_pdf2'] == 8 ? 'selected' : ''; ?>>AWCC</option>
                            <option value="9" <?php echo isset($setting['show_customized_pdf2']) && $setting['show_customized_pdf2'] == 9 ? 'selected' : ''; ?>>SUPREME</option>
                            <option value="10" <?php echo isset($setting['show_customized_pdf2']) && $setting['show_customized_pdf2'] == 10 ? 'selected' : ''; ?>>PANNEST</option>
                            <option value="11" <?php echo isset($setting['show_customized_pdf2']) && $setting['show_customized_pdf2'] == 11 ? 'selected' : ''; ?>>PEGASUS</option>
                            <option value="12" <?php echo isset($setting['show_customized_pdf2']) && $setting['show_customized_pdf2'] == 12 ? 'selected' : ''; ?>>MYS</option>
                            <option value="13" <?php echo isset($setting['show_customized_pdf2']) && $setting['show_customized_pdf2'] == 13 ? 'selected' : ''; ?>>BHAVANI</option>
                            <option value="14" <?php echo isset($setting['show_customized_pdf2']) && $setting['show_customized_pdf2'] == 14 ? 'selected' : ''; ?>>OM INTL</option>
                            <option value="15" <?php echo isset($setting['show_customized_pdf2']) && $setting['show_customized_pdf2'] == 15 ? 'selected' : ''; ?>>ORIENT</option>
                            <option value="16" <?php echo isset($setting['show_customized_pdf2']) && $setting['show_customized_pdf2'] == 16 ? 'selected' : ''; ?>>NAVALAI</option>
                            <option value="17" <?php echo isset($setting['show_customized_pdf2']) && $setting['show_customized_pdf2'] == 17 ? 'selected' : ''; ?>>DTD</option>
                            <option value="18" <?php echo isset($setting['show_customized_pdf2']) && $setting['show_customized_pdf2'] == 18 ? 'selected' : ''; ?>>SHREE SHAKTI</option>
                            <option value="19" <?php echo isset($setting['show_customized_pdf2']) && $setting['show_customized_pdf2'] == 19 ? 'selected' : ''; ?>>ELS</option>
                            <option value="20" <?php echo isset($setting['show_customized_pdf2']) && $setting['show_customized_pdf2'] == 20 ? 'selected' : ''; ?>>TOTAL </option>
                            <option value="21" <?php echo isset($setting['show_customized_pdf2']) && $setting['show_customized_pdf2'] == 21 ? 'selected' : ''; ?>>STARLINE</option>
                            <option value="22" <?php echo isset($setting['show_customized_pdf2']) && $setting['show_customized_pdf2'] == 22 ? 'selected' : ''; ?>>DAAKIYAWALA</option>
                        </select>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_customized_pdf3']) ? $setting_comment['show_customized_pdf3'] : ''; ?>" class="col-sm-6 col-form-label">SHOW CUSTOMIZED PDF3</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[show_customized_pdf3]" id="" cols="80" rows="1"><?php echo isset($setting['show_customized_pdf3']) ? $setting['show_customized_pdf3'] : ''; ?></textarea>
                    <?php } else { ?>
                        <select name="pdf[show_customized_pdf3]">
                            <option value="2" <?php echo isset($setting['show_customized_pdf3']) && $setting['show_customized_pdf3'] == 2 ? 'selected' : ''; ?>>HIDE</option>
                            <option value="1" <?php echo isset($setting['show_customized_pdf3']) && $setting['show_customized_pdf3'] == 1 ? 'selected' : ''; ?>>DEFAULT</option>
                            <option value="3" <?php echo isset($setting['show_customized_pdf3']) && $setting['show_customized_pdf3'] == 3 ? 'selected' : ''; ?>>SOHEM</option>
                            <option value="4" <?php echo isset($setting['show_customized_pdf3']) && $setting['show_customized_pdf3'] == 4 ? 'selected' : ''; ?>>ACM</option>
                            <option value="5" <?php echo isset($setting['show_customized_pdf3']) && $setting['show_customized_pdf3'] == 5 ? 'selected' : ''; ?>>CARGO FORCE</option>
                            <option value="6" <?php echo isset($setting['show_customized_pdf3']) && $setting['show_customized_pdf3'] == 6 ? 'selected' : ''; ?>>ORBIT</option>
                            <option value="7" <?php echo isset($setting['show_customized_pdf3']) && $setting['show_customized_pdf3'] == 7 ? 'selected' : ''; ?>>DTD</option>
                            <option value="8" <?php echo isset($setting['show_customized_pdf3']) && $setting['show_customized_pdf3'] == 8 ? 'selected' : ''; ?>>BHAVANI</option>
                            <option value="9" <?php echo isset($setting['show_customized_pdf3']) && $setting['show_customized_pdf3'] == 9 ? 'selected' : ''; ?>>SSBC</option>
                            <option value="10" <?php echo isset($setting['show_customized_pdf3']) && $setting['show_customized_pdf3'] == 10 ? 'selected' : ''; ?>>AWCC</option>
                        </select>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_customized_pdf4']) ? $setting_comment['show_customized_pdf4'] : ''; ?>" class="col-sm-6 col-form-label">SHOW CUSTOMIZED PDF4</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[show_customized_pdf4]" id="" cols="80" rows="1"><?php echo isset($setting['show_customized_pdf4']) ? $setting['show_customized_pdf4'] : ''; ?></textarea>
                    <?php } else { ?>
                        <select name="pdf[show_customized_pdf4]">
                            <option value="2" <?php echo isset($setting['show_customized_pdf4']) && $setting['show_customized_pdf4'] == 2 ? 'selected' : ''; ?>>HIDE</option>
                            <option value="1" <?php echo isset($setting['show_customized_pdf4']) && $setting['show_customized_pdf4'] == 1 ? 'selected' : ''; ?>>DEFAULT</option>
                        </select>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_customized_pdf5']) ? $setting_comment['show_customized_pdf5'] : ''; ?>" class="col-sm-6 col-form-label">SHOW CUSTOMIZED PDF5</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[show_customized_pdf5]" id="" cols="80" rows="1"><?php echo isset($setting['show_customized_pdf5']) ? $setting['show_customized_pdf5'] : ''; ?></textarea>
                    <?php } else { ?>
                        <select name="pdf[show_customized_pdf5]">
                            <option value="2" <?php echo isset($setting['show_customized_pdf5']) && $setting['show_customized_pdf5'] == 2 ? 'selected' : ''; ?>>HIDE</option>
                            <option value="1" <?php echo isset($setting['show_customized_pdf5']) && $setting['show_customized_pdf5'] == 1 ? 'selected' : ''; ?>>DEFAULT</option>
                            <option value="3" <?php echo isset($setting['show_customized_pdf5']) && $setting['show_customized_pdf5'] == 3 ? 'selected' : ''; ?>>BONDS</option>
                            <option value="4" <?php echo isset($setting['show_customized_pdf5']) && $setting['show_customized_pdf5'] == 4 ? 'selected' : ''; ?>>PANNEST</option>
                            <option value="5" <?php echo isset($setting['show_customized_pdf5']) && $setting['show_customized_pdf5'] == 5 ? 'selected' : ''; ?>>BHAVANI</option>
                            <option value="6" <?php echo isset($setting['show_customized_pdf5']) && $setting['show_customized_pdf5'] == 6 ? 'selected' : ''; ?>>SSBC</option>
                        </select>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_customized_pdf6']) ? $setting_comment['show_customized_pdf6'] : ''; ?>" class="col-sm-6 col-form-label">SHOW CUSTOMIZED PDF6</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[show_customized_pdf6]" id="" cols="80" rows="1"><?php echo isset($setting['show_customized_pdf6']) ? $setting['show_customized_pdf6'] : ''; ?></textarea>
                    <?php } else { ?>
                        <select name="pdf[show_customized_pdf6]">
                            <option value="2" <?php echo isset($setting['show_customized_pdf6']) && $setting['show_customized_pdf6'] == 2 ? 'selected' : ''; ?>>HIDE</option>
                            <option value="1" <?php echo isset($setting['show_customized_pdf6']) && $setting['show_customized_pdf6'] == 1 ? 'selected' : ''; ?>>DEFAULT</option>
                            <option value="3" <?php echo isset($setting['show_customized_pdf6']) && $setting['show_customized_pdf6'] == 3 ? 'selected' : ''; ?>>EVELYN</option>
                            <option value="4" <?php echo isset($setting['show_customized_pdf6']) && $setting['show_customized_pdf6'] == 4 ? 'selected' : ''; ?>>PANNEST</option>
                            <option value="5" <?php echo isset($setting['show_customized_pdf6']) && $setting['show_customized_pdf6'] == 5 ? 'selected' : ''; ?>>BHAVANI</option>
                            <option value="6" <?php echo isset($setting['show_customized_pdf6']) && $setting['show_customized_pdf6'] == 6 ? 'selected' : ''; ?>>ONS</option>
                        </select>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_customized_pdf7']) ? $setting_comment['show_customized_pdf7'] : ''; ?>" class="col-sm-6 col-form-label">SHOW CUSTOMIZED PDF7</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[show_customized_pdf7]" id="" cols="80" rows="1"><?php echo isset($setting['show_customized_pdf7']) ? $setting['show_customized_pdf7'] : ''; ?></textarea>
                    <?php } else { ?>
                        <select name="pdf[show_customized_pdf7]">
                            <option value="2" <?php echo isset($setting['show_customized_pdf7']) && $setting['show_customized_pdf7'] == 2 ? 'selected' : ''; ?>>HIDE</option>
                            <option value="1" <?php echo isset($setting['show_customized_pdf7']) && $setting['show_customized_pdf7'] == 1 ? 'selected' : ''; ?>>DEFAULT</option>
                            <option value="3" <?php echo isset($setting['show_customized_pdf7']) && $setting['show_customized_pdf7'] == 3 ? 'selected' : ''; ?>>BHAVANI</option>
                        </select>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_customized_address_label']) ? $setting_comment['show_customized_address_label'] : ''; ?>" class="col-sm-6 col-form-label">SHOW CUSTOMIZED ADDRESS LABEL</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[show_customized_address_label]" id="" cols="80" rows="1"><?php echo isset($setting['show_customized_address_label']) ? $setting['show_customized_address_label'] : ''; ?></textarea>
                    <?php } else { ?>
                        <select name="pdf[show_customized_address_label]">
                            <option value="1" <?php echo isset($setting['show_customized_address_label']) && $setting['show_customized_address_label'] == 1 ? 'selected' : ''; ?>>DEFAULT</option>
                            <option value="2" <?php echo isset($setting['show_customized_address_label']) && $setting['show_customized_address_label'] == 2 ? 'selected' : ''; ?>>ORBIT</option>
                            <option value="3" <?php echo isset($setting['show_customized_address_label']) && $setting['show_customized_address_label'] == 3 ? 'selected' : ''; ?>>BHARAT -003</option>
                            <option value="4" <?php echo isset($setting['show_customized_address_label']) && $setting['show_customized_address_label'] == 4 ? 'selected' : ''; ?>>ORBIT1 -004</option>
                            <option value="5" <?php echo isset($setting['show_customized_address_label']) && $setting['show_customized_address_label'] == 5 ? 'selected' : ''; ?>>GWE -005</option>
                            <option value="6" <?php echo isset($setting['show_customized_address_label']) && $setting['show_customized_address_label'] == 6 ? 'selected' : ''; ?>>ELS -006</option>
                            <option value="7" <?php echo isset($setting['show_customized_address_label']) && $setting['show_customized_address_label'] == 7 ? 'selected' : ''; ?>>SUPREME -007</option>
                            <option value="8" <?php echo isset($setting['show_customized_address_label']) && $setting['show_customized_address_label'] == 8 ? 'selected' : ''; ?>>DTD -008</option>
                            <option value="9" <?php echo isset($setting['show_customized_address_label']) && $setting['show_customized_address_label'] == 9 ? 'selected' : ''; ?>>ONS -009</option>
                            <option value="10" <?php echo isset($setting['show_customized_address_label']) && $setting['show_customized_address_label'] == 10 ? 'selected' : ''; ?>>OM INTL -010</option>
                            <option value="11" <?php echo isset($setting['show_customized_address_label']) && $setting['show_customized_address_label'] == 11 ? 'selected' : ''; ?>>GRANDSPEED -011</option>

                        </select>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_customized_free_form_pdf']) ? $setting_comment['show_customized_free_form_pdf'] : ''; ?>" class="col-sm-6 col-form-label">SHOW CUSTOMIZED FREE FORM INVOICE PDF</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[show_customized_free_form_pdf]" id="" cols="80" rows="1"><?php echo isset($setting['show_customized_free_form_pdf']) ? $setting['show_customized_free_form_pdf'] : ''; ?></textarea>
                    <?php } else { ?>
                        <select name="pdf[show_customized_free_form_pdf]">
                            <option value="1" <?php echo isset($setting['show_customized_free_form_pdf']) && $setting['show_customized_free_form_pdf'] == 1 ? 'selected' : ''; ?>>DEFAULT</option>
                            <option value="2" <?php echo isset($setting['show_customized_free_form_pdf']) && $setting['show_customized_free_form_pdf'] == 2 ? 'selected' : ''; ?>>DTD</option>
                            <option value="3" <?php echo isset($setting['show_customized_free_form_pdf']) && $setting['show_customized_free_form_pdf'] == 3 ? 'selected' : ''; ?>>SUPREME</option>
                            <option value="4" <?php echo isset($setting['show_customized_free_form_pdf']) && $setting['show_customized_free_form_pdf'] == 4 ? 'selected' : ''; ?>>STARLINE</option>
                            <option value="5" <?php echo isset($setting['show_customized_free_form_pdf']) && $setting['show_customized_free_form_pdf'] == 5 ? 'selected' : ''; ?>>MYS</option>
                            <option value="6" <?php echo isset($setting['show_customized_free_form_pdf']) && $setting['show_customized_free_form_pdf'] == 6 ? 'selected' : ''; ?>>DEFAULT 2</option>
                            <option value="7" <?php echo isset($setting['show_customized_free_form_pdf']) && $setting['show_customized_free_form_pdf'] == 7 ? 'selected' : ''; ?>>TOTAL</option>
                        </select>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_unit_weight_in_free_form_invoice_pdf']) ? $setting_comment['show_unit_weight_in_free_form_invoice_pdf'] : ''; ?>" class="col-sm-6 col-form-label">Show Unit Weight In Free Form Invoice pdf</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[show_unit_weight_in_free_form_invoice_pdf]" id="" cols="80" rows="1"><?php echo isset($setting['show_unit_weight_in_free_form_invoice_pdf']) ? $setting['show_unit_weight_in_free_form_invoice_pdf'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_unit_weight_in_free_form_invoice_pdf" value="1" autocomplete="nope" name="pdf[show_unit_weight_in_free_form_invoice_pdf]" <?php echo isset($setting['show_unit_weight_in_free_form_invoice_pdf']) && $setting['show_unit_weight_in_free_form_invoice_pdf'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_unit_weight_in_free_form_invoice_pdf" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_forwording_no_in_address_label']) ? $setting_comment['show_forwording_no_in_address_label'] : ''; ?>" class="col-sm-6 col-form-label">show forwarding number and its barcode in address label</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[show_forwording_no_in_address_label]" id="" cols="80" rows="1"><?php echo isset($setting['show_forwording_no_in_address_label']) ? $setting['show_forwording_no_in_address_label'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_forwording_no_in_address_label" value="1" autocomplete="nope" name="pdf[show_forwording_no_in_address_label]" <?php echo isset($setting['show_forwording_no_in_address_label']) && $setting['show_forwording_no_in_address_label'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_forwording_no_in_address_label" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['replace_forwording_no_with_remark_in_pdf2']) ? $setting_comment['replace_forwording_no_with_remark_in_pdf2'] : ''; ?>" class="col-sm-6 col-form-label">Replace Forwording No With Remark In pdf2</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[replace_forwording_no_with_remark_in_pdf2]" id="" cols="80" rows="1"><?php echo isset($setting['replace_forwording_no_with_remark_in_pdf2']) ? $setting['replace_forwording_no_with_remark_in_pdf2'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="replace_forwording_no_with_remark_in_pdf2" value="1" autocomplete="nope" name="pdf[replace_forwording_no_with_remark_in_pdf2]" <?php echo isset($setting['replace_forwording_no_with_remark_in_pdf2']) && $setting['replace_forwording_no_with_remark_in_pdf2'] == 1 ? 'checked' : ''; ?>>
                        <label for="replace_forwording_no_with_remark_in_pdf2" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['remove_logo_and_company_address_from_docket_pdf2']) ? $setting_comment['remove_logo_and_company_address_from_docket_pdf2'] : ''; ?>" class="col-sm-6 col-form-label">Remove Logo And Company Address From AWB pdf2</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[remove_logo_and_company_address_from_docket_pdf2]" id="" cols="80" rows="1"><?php echo isset($setting['remove_logo_and_company_address_from_docket_pdf2']) ? $setting['remove_logo_and_company_address_from_docket_pdf2'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="remove_logo_and_company_address_from_docket_pdf2" value="1" autocomplete="nope" name="pdf[remove_logo_and_company_address_from_docket_pdf2]" <?php echo isset($setting['remove_logo_and_company_address_from_docket_pdf2']) && $setting['remove_logo_and_company_address_from_docket_pdf2'] == 1 ? 'checked' : ''; ?>>
                        <label for="remove_logo_and_company_address_from_docket_pdf2" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['remove_insurance_and_show_skynet_carrier_service_in_docket_pdf2']) ? $setting_comment['remove_insurance_and_show_skynet_carrier_service_in_docket_pdf2'] : ''; ?>" class="col-sm-6 col-form-label">Remove Insurance And Show Skynet Carrier Service In AWB PDF2</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[remove_insurance_and_show_skynet_carrier_service_in_docket_pdf2]" id="" cols="80" rows="1"><?php echo isset($setting['remove_insurance_and_show_skynet_carrier_service_in_docket_pdf2']) ? $setting['remove_insurance_and_show_skynet_carrier_service_in_docket_pdf2'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="remove_insurance_and_show_skynet_carrier_service_in_docket_pdf2" value="1" autocomplete="nope" name="pdf[remove_insurance_and_show_skynet_carrier_service_in_docket_pdf2]" <?php echo isset($setting['remove_insurance_and_show_skynet_carrier_service_in_docket_pdf2']) && $setting['remove_insurance_and_show_skynet_carrier_service_in_docket_pdf2'] == 1 ? 'checked' : ''; ?>>
                        <label for="remove_insurance_and_show_skynet_carrier_service_in_docket_pdf2" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['add_customer_name_to_docket_pdf2']) ? $setting_comment['add_customer_name_to_docket_pdf2'] : ''; ?>" class="col-sm-6 col-form-label">Add Customer Name To awb PDF2</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[add_customer_name_to_docket_pdf2]" id="" cols="80" rows="1"><?php echo isset($setting['add_customer_name_to_docket_pdf2']) ? $setting['add_customer_name_to_docket_pdf2'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="add_customer_name_to_docket_pdf2" value="1" autocomplete="nope" name="pdf[add_customer_name_to_docket_pdf2]" <?php echo isset($setting['add_customer_name_to_docket_pdf2']) && $setting['add_customer_name_to_docket_pdf2'] == 1 ? 'checked' : ''; ?>>
                        <label for="add_customer_name_to_docket_pdf2" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['replace_shipment_value_with_booking_date_in_pdf2']) ? $setting_comment['replace_shipment_value_with_booking_date_in_pdf2'] : ''; ?>" class="col-sm-6 col-form-label">Replace Shipment Value With Booking Date In PDF2</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[replace_shipment_value_with_booking_date_in_pdf2]" id="" cols="80" rows="1"><?php echo isset($setting['replace_shipment_value_with_booking_date_in_pdf2']) ? $setting['replace_shipment_value_with_booking_date_in_pdf2'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="replace_shipment_value_with_booking_date_in_pdf2" value="1" autocomplete="nope" name="pdf[replace_shipment_value_with_booking_date_in_pdf2]" <?php echo isset($setting['replace_shipment_value_with_booking_date_in_pdf2']) && $setting['replace_shipment_value_with_booking_date_in_pdf2'] == 1 ? 'checked' : ''; ?>>
                        <label for="replace_shipment_value_with_booking_date_in_pdf2" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['remove_parcel_number_barcode_from_pdf_2']) ? $setting_comment['remove_parcel_number_barcode_from_pdf_2'] : ''; ?>" class="col-sm-6 col-form-label">Remove - PARCEL NUMBER barcode from pdf 2</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[remove_parcel_number_barcode_from_pdf_2]" id="" cols="80" rows="1"><?php echo isset($setting['remove_parcel_number_barcode_from_pdf_2']) ? $setting['remove_parcel_number_barcode_from_pdf_2'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="remove_parcel_number_barcode_from_pdf_2" value="1" autocomplete="nope" name="pdf[remove_parcel_number_barcode_from_pdf_2]" <?php echo isset($setting['remove_parcel_number_barcode_from_pdf_2']) && $setting['remove_parcel_number_barcode_from_pdf_2'] == 1 ? 'checked' : ''; ?>>
                        <label for="remove_parcel_number_barcode_from_pdf_2" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['show_ref_number_in_docket_pdf2']) ? $setting_comment['show_ref_number_in_docket_pdf2'] : ''; ?>" class="col-sm-6 col-form-label">Show REF number in awb PDF2</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[show_ref_number_in_docket_pdf2]" id="" cols="80" rows="1"><?php echo isset($setting['show_ref_number_in_docket_pdf2']) ? $setting['show_ref_number_in_docket_pdf2'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_ref_number_in_docket_pdf2" value="1" autocomplete="nope" name="pdf[show_ref_number_in_docket_pdf2]" <?php echo isset($setting['show_ref_number_in_docket_pdf2']) && $setting['show_ref_number_in_docket_pdf2'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_ref_number_in_docket_pdf2" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['show_terms_and_conditions_in_docket_pdf1']) ? $setting_comment['show_terms_and_conditions_in_docket_pdf1'] : ''; ?>" class="col-sm-6 col-form-label">Show Terms And Conditions In Awb PDF1</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[show_terms_and_conditions_in_docket_pdf1]" id="" cols="80" rows="1"><?php echo isset($setting['show_terms_and_conditions_in_docket_pdf1']) ? $setting['show_terms_and_conditions_in_docket_pdf1'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_terms_and_conditions_in_docket_pdf1" value="1" autocomplete="nope" name="pdf[show_terms_and_conditions_in_docket_pdf1]" <?php echo isset($setting['show_terms_and_conditions_in_docket_pdf1']) && $setting['show_terms_and_conditions_in_docket_pdf1'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_terms_and_conditions_in_docket_pdf1" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['hide_all_amounts_in_docket_pdf_1']) ? $setting_comment['hide_all_amounts_in_docket_pdf_1'] : ''; ?>" class="col-sm-6 col-form-label">Hide All Amounts In AWB PDF_1</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[hide_all_amounts_in_docket_pdf_1]" id="" cols="80" rows="1"><?php echo isset($setting['hide_all_amounts_in_docket_pdf_1']) ? $setting['hide_all_amounts_in_docket_pdf_1'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="hide_all_amounts_in_docket_pdf_1" value="1" autocomplete="nope" name="pdf[hide_all_amounts_in_docket_pdf_1]" <?php echo isset($setting['hide_all_amounts_in_docket_pdf_1']) && $setting['hide_all_amounts_in_docket_pdf_1'] == 1 ? 'checked' : ''; ?>>
                        <label for="hide_all_amounts_in_docket_pdf_1" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['hide_all_amounts_in_docket_pdf_2']) ? $setting_comment['hide_all_amounts_in_docket_pdf_2'] : ''; ?>" class="col-sm-6 col-form-label">Hide All Amounts In AWB PDF_2</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[hide_all_amounts_in_docket_pdf_2]" id="" cols="80" rows="1"><?php echo isset($setting['hide_all_amounts_in_docket_pdf_2']) ? $setting['hide_all_amounts_in_docket_pdf_2'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="hide_all_amounts_in_docket_pdf_2" value="1" autocomplete="nope" name="pdf[hide_all_amounts_in_docket_pdf_2]" <?php echo isset($setting['hide_all_amounts_in_docket_pdf_2']) && $setting['hide_all_amounts_in_docket_pdf_2'] == 1 ? 'checked' : ''; ?>>
                        <label for="hide_all_amounts_in_docket_pdf_2" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['remove_gstin_from_pdf1']) ? $setting_comment['remove_gstin_from_pdf1'] : ''; ?>" class="col-sm-6 col-form-label">Remove GSTIN From PDF1</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[remove_gstin_from_pdf1]" id="" cols="80" rows="1"><?php echo isset($setting['remove_gstin_from_pdf1']) ? $setting['remove_gstin_from_pdf1'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="remove_gstin_from_pdf1" value="1" autocomplete="nope" name="pdf[remove_gstin_from_pdf1]" <?php echo isset($setting['remove_gstin_from_pdf1']) && $setting['remove_gstin_from_pdf1'] == 1 ? 'checked' : ''; ?>>
                        <label for="remove_gstin_from_pdf1" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['remove_customer_name_from_docket_pdf1']) ? $setting_comment['remove_customer_name_from_docket_pdf1'] : ''; ?>" class="col-sm-6 col-form-label">REMOVE Customer Name FROM AWB PDF1</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[remove_customer_name_from_docket_pdf1]" id="" cols="80" rows="1"><?php echo isset($setting['remove_customer_name_from_docket_pdf1']) ? $setting['remove_customer_name_from_docket_pdf1'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="remove_customer_name_from_docket_pdf1" value="1" autocomplete="nope" name="pdf[remove_customer_name_from_docket_pdf1]" <?php echo isset($setting['remove_customer_name_from_docket_pdf1']) && $setting['remove_customer_name_from_docket_pdf1'] == 1 ? 'checked' : ''; ?>>
                        <label for="remove_customer_name_from_docket_pdf1" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['show_accounts_copy_at_first_place_in_docket_pdf1']) ? $setting_comment['show_accounts_copy_at_first_place_in_docket_pdf1'] : ''; ?>" class="col-sm-6 col-form-label">Show Accounts Copy At First Place In AWB PDF1</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[show_accounts_copy_at_first_place_in_docket_pdf1]" id="" cols="80" rows="1"><?php echo isset($setting['show_accounts_copy_at_first_place_in_docket_pdf1']) ? $setting['show_accounts_copy_at_first_place_in_docket_pdf1'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_accounts_copy_at_first_place_in_docket_pdf1" value="1" autocomplete="nope" name="pdf[show_accounts_copy_at_first_place_in_docket_pdf1]" <?php echo isset($setting['show_accounts_copy_at_first_place_in_docket_pdf1']) && $setting['show_accounts_copy_at_first_place_in_docket_pdf1'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_accounts_copy_at_first_place_in_docket_pdf1" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['show_admin_copy_in_pdf_1']) ? $setting_comment['show_admin_copy_in_pdf_1'] : ''; ?>" class="col-sm-6 col-form-label">Show Admin Copy in PDF 1</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[show_admin_copy_in_pdf_1]" id="" cols="80" rows="1"><?php echo isset($setting['show_admin_copy_in_pdf_1']) ? $setting['show_admin_copy_in_pdf_1'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_admin_copy_in_pdf_1" value="1" autocomplete="nope" name="pdf[show_admin_copy_in_pdf_1]" <?php echo isset($setting['show_admin_copy_in_pdf_1']) && $setting['show_admin_copy_in_pdf_1'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_admin_copy_in_pdf_1" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['show_origin_and_destination_name_from_location_master_in_pdf_1']) ? $setting_comment['show_origin_and_destination_name_from_location_master_in_pdf_1'] : ''; ?>" class="col-sm-6 col-form-label">Show Origin and Destination Name From Location Master In PDF 1</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[show_origin_and_destination_name_from_location_master_in_pdf_1]" id="" cols="80" rows="1"><?php echo isset($setting['show_origin_and_destination_name_from_location_master_in_pdf_1']) ? $setting['show_origin_and_destination_name_from_location_master_in_pdf_1'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_origin_and_destination_name_from_location_master_in_pdf_1" value="1" autocomplete="nope" name="pdf[show_origin_and_destination_name_from_location_master_in_pdf_1]" <?php echo isset($setting['show_origin_and_destination_name_from_location_master_in_pdf_1']) && $setting['show_origin_and_destination_name_from_location_master_in_pdf_1'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_origin_and_destination_name_from_location_master_in_pdf_1" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['hide_volume_weight_from_pdf_1']) ? $setting_comment['hide_volume_weight_from_pdf_1'] : ''; ?>" class="col-sm-6 col-form-label">Hide Volume Weight From PDF 1</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[hide_volume_weight_from_pdf_1]" id="" cols="80" rows="1"><?php echo isset($setting['hide_volume_weight_from_pdf_1']) ? $setting['hide_volume_weight_from_pdf_1'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="hide_volume_weight_from_pdf_1" value="1" autocomplete="nope" name="pdf[hide_volume_weight_from_pdf_1]" <?php echo isset($setting['hide_volume_weight_from_pdf_1']) && $setting['hide_volume_weight_from_pdf_1'] == 1 ? 'checked' : ''; ?>>
                        <label for="hide_volume_weight_from_pdf_1" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['hide_chargeable_weight_from_pdf_1']) ? $setting_comment['hide_chargeable_weight_from_pdf_1'] : ''; ?>" class="col-sm-6 col-form-label">Hide Chargeable Weight From PDF 1</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[hide_chargeable_weight_from_pdf_1]" id="" cols="80" rows="1"><?php echo isset($setting['hide_chargeable_weight_from_pdf_1']) ? $setting['hide_chargeable_weight_from_pdf_1'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="hide_chargeable_weight_from_pdf_1" value="1" autocomplete="nope" name="pdf[hide_chargeable_weight_from_pdf_1]" <?php echo isset($setting['hide_chargeable_weight_from_pdf_1']) && $setting['hide_chargeable_weight_from_pdf_1'] == 1 ? 'checked' : ''; ?>>
                        <label for="hide_chargeable_weight_from_pdf_1" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['show_only_1_pdf_1_copy']) ? $setting_comment['show_only_1_pdf_1_copy'] : ''; ?>" class="col-sm-6 col-form-label">Show Only 1 PDF 1 Copy</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[show_only_1_pdf_1_copy]" id="" cols="80" rows="1"><?php echo isset($setting['show_only_1_pdf_1_copy']) ? $setting['show_only_1_pdf_1_copy'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_only_1_pdf_1_copy" value="1" autocomplete="nope" name="pdf[show_only_1_pdf_1_copy]" <?php echo isset($setting['show_only_1_pdf_1_copy']) && $setting['show_only_1_pdf_1_copy'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_only_1_pdf_1_copy" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['show_terms_and_conditions_for_rhl_in_docket_pdf1']) ? $setting_comment['show_terms_and_conditions_for_rhl_in_docket_pdf1'] : ''; ?>" class="col-sm-6 col-form-label">Show Terms And Conditions For RHL In AWB PDF1</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[show_terms_and_conditions_for_rhl_in_docket_pdf1]" id="" cols="80" rows="1"><?php echo isset($setting['show_terms_and_conditions_for_rhl_in_docket_pdf1']) ? $setting['show_terms_and_conditions_for_rhl_in_docket_pdf1'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_terms_and_conditions_for_rhl_in_docket_pdf1" value="1" autocomplete="nope" name="pdf[show_terms_and_conditions_for_rhl_in_docket_pdf1]" <?php echo isset($setting['show_terms_and_conditions_for_rhl_in_docket_pdf1']) && $setting['show_terms_and_conditions_for_rhl_in_docket_pdf1'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_terms_and_conditions_for_rhl_in_docket_pdf1" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['remove_dimension_detail_form_docket_pdf_1']) ? $setting_comment['remove_dimension_detail_form_docket_pdf_1'] : ''; ?>" class="col-sm-6 col-form-label">Remove Dimension Detail Form AWB PDF 1</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[remove_dimension_detail_form_docket_pdf_1]" id="" cols="80" rows="1"><?php echo isset($setting['remove_dimension_detail_form_docket_pdf_1']) ? $setting['remove_dimension_detail_form_docket_pdf_1'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="remove_dimension_detail_form_docket_pdf_1" value="1" autocomplete="nope" name="pdf[remove_dimension_detail_form_docket_pdf_1]" <?php echo isset($setting['remove_dimension_detail_form_docket_pdf_1']) && $setting['remove_dimension_detail_form_docket_pdf_1'] == 1 ? 'checked' : ''; ?>>
                        <label for="remove_dimension_detail_form_docket_pdf_1" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['remove_logo_and_company_address_from_docket_pdf1']) ? $setting_comment['remove_logo_and_company_address_from_docket_pdf1'] : ''; ?>" class="col-sm-6 col-form-label">Remove Logo And Company Address From AWB pdf1</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[remove_logo_and_company_address_from_docket_pdf1]" id="" cols="80" rows="1"><?php echo isset($setting['remove_logo_and_company_address_from_docket_pdf1']) ? $setting['remove_logo_and_company_address_from_docket_pdf1'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="remove_logo_and_company_address_from_docket_pdf1" value="1" autocomplete="nope" name="pdf[remove_logo_and_company_address_from_docket_pdf1]" <?php echo isset($setting['remove_logo_and_company_address_from_docket_pdf1']) && $setting['remove_logo_and_company_address_from_docket_pdf1'] == 1 ? 'checked' : ''; ?>>
                        <label for="remove_logo_and_company_address_from_docket_pdf1" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['enable_pdf1_for_blueline']) ? $setting_comment['enable_pdf1_for_blueline'] : ''; ?>" class="col-sm-6 col-form-label">Enable PDF1 For Blueline(Customized)</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[enable_pdf1_for_blueline]" id="" cols="80" rows="1"><?php echo isset($setting['enable_pdf1_for_blueline']) ? $setting['enable_pdf1_for_blueline'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_pdf1_for_blueline" value="1" autocomplete="nope" name="pdf[enable_pdf1_for_blueline]" <?php echo isset($setting['enable_pdf1_for_blueline']) && $setting['enable_pdf1_for_blueline'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_pdf1_for_blueline" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['show_ref_number_in_pdf1_and_shippers_and_accounts_copy']) ? $setting_comment['show_ref_number_in_pdf1_and_shippers_and_accounts_copy'] : ''; ?>" class="col-sm-6 col-form-label">Show REF number in PDF1 and Shippers and Accounts copy</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[show_ref_number_in_pdf1_and_shippers_and_accounts_copy]" id="" cols="80" rows="1"><?php echo isset($setting['show_ref_number_in_pdf1_and_shippers_and_accounts_copy']) ? $setting['show_ref_number_in_pdf1_and_shippers_and_accounts_copy'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_ref_number_in_pdf1_and_shippers_and_accounts_copy" value="1" autocomplete="nope" name="pdf[show_ref_number_in_pdf1_and_shippers_and_accounts_copy]" <?php echo isset($setting['show_ref_number_in_pdf1_and_shippers_and_accounts_copy']) && $setting['show_ref_number_in_pdf1_and_shippers_and_accounts_copy'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_ref_number_in_pdf1_and_shippers_and_accounts_copy" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['enable_new_pdf1_for_ssbc']) ? $setting_comment['enable_new_pdf1_for_ssbc'] : ''; ?>" class="col-sm-6 col-form-label">Enable New PDF1 For SSBC</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[enable_new_pdf1_for_ssbc]" id="" cols="80" rows="1"><?php echo isset($setting['enable_new_pdf1_for_ssbc']) ? $setting['enable_new_pdf1_for_ssbc'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_new_pdf1_for_ssbc" value="1" autocomplete="nope" name="pdf[enable_new_pdf1_for_ssbc]" <?php echo isset($setting['enable_new_pdf1_for_ssbc']) && $setting['enable_new_pdf1_for_ssbc'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_new_pdf1_for_ssbc" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['enable_new_pdf1_for_ons']) ? $setting_comment['enable_new_pdf1_for_ons'] : ''; ?>" class="col-sm-6 col-form-label">Enable New PDF1 For ONS</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[enable_new_pdf1_for_ons]" id="" cols="80" rows="1"><?php echo isset($setting['enable_new_pdf1_for_ons']) ? $setting['enable_new_pdf1_for_ons'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_new_pdf1_for_ons" value="1" autocomplete="nope" name="pdf[enable_new_pdf1_for_ons]" <?php echo isset($setting['enable_new_pdf1_for_ons']) && $setting['enable_new_pdf1_for_ons'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_new_pdf1_for_ons" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['enable_pdf1_for_the_shipping_company']) ? $setting_comment['enable_pdf1_for_the_shipping_company'] : ''; ?>" class="col-sm-6 col-form-label">Enable PDF1 For The Shipping Company</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[enable_pdf1_for_the_shipping_company]" id="" cols="80" rows="1"><?php echo isset($setting['enable_pdf1_for_the_shipping_company']) ? $setting['enable_pdf1_for_the_shipping_company'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_pdf1_for_the_shipping_company" value="1" autocomplete="nope" name="pdf[enable_pdf1_for_the_shipping_company]" <?php echo isset($setting['enable_pdf1_for_the_shipping_company']) && $setting['enable_pdf1_for_the_shipping_company'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_pdf1_for_the_shipping_company" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['enable_docket_pdf1_and_pdf2_for_orbit']) ? $setting_comment['enable_docket_pdf1_and_pdf2_for_orbit'] : ''; ?>" class="col-sm-6 col-form-label">Enable AWB PDF1 & PDF2 for Orbit</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[enable_docket_pdf1_and_pdf2_for_orbit]" id="" cols="80" rows="1"><?php echo isset($setting['enable_docket_pdf1_and_pdf2_for_orbit']) ? $setting['enable_docket_pdf1_and_pdf2_for_orbit'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_docket_pdf1_and_pdf2_for_orbit" value="1" autocomplete="nope" name="pdf[enable_docket_pdf1_and_pdf2_for_orbit]" <?php echo isset($setting['enable_docket_pdf1_and_pdf2_for_orbit']) && $setting['enable_docket_pdf1_and_pdf2_for_orbit'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_docket_pdf1_and_pdf2_for_orbit" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['new_pdf_1b']) ? $setting_comment['new_pdf_1b'] : ''; ?>" class="col-sm-6 col-form-label">NEW PDF 1B</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[new_pdf_1b]" id="" cols="80" rows="1"><?php echo isset($setting['new_pdf_1b']) ? $setting['new_pdf_1b'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="new_pdf_1b" value="1" autocomplete="nope" name="pdf[new_pdf_1b]" <?php echo isset($setting['new_pdf_1b']) && $setting['new_pdf_1b'] == 1 ? 'checked' : ''; ?>>
                        <label for="new_pdf_1b" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['display_custom_logo_and_name_in_pdf_1_and_2_pxc_pacific']) ? $setting_comment['display_custom_logo_and_name_in_pdf_1_and_2_pxc_pacific'] : ''; ?>" class="col-sm-6 col-form-label">Display custom logo and name in PDF 1 and 2 - PXC PACIFIC</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[display_custom_logo_and_name_in_pdf_1_and_2_pxc_pacific]" id="" cols="80" rows="1"><?php echo isset($setting['display_custom_logo_and_name_in_pdf_1_and_2_pxc_pacific']) ? $setting['display_custom_logo_and_name_in_pdf_1_and_2_pxc_pacific'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="display_custom_logo_and_name_in_pdf_1_and_2_pxc_pacific" value="1" autocomplete="nope" name="pdf[display_custom_logo_and_name_in_pdf_1_and_2_pxc_pacific]" <?php echo isset($setting['display_custom_logo_and_name_in_pdf_1_and_2_pxc_pacific']) && $setting['display_custom_logo_and_name_in_pdf_1_and_2_pxc_pacific'] == 1 ? 'checked' : ''; ?>>
                        <label for="display_custom_logo_and_name_in_pdf_1_and_2_pxc_pacific" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <?php /*<div class="col-12">
        <div class="form-group row">
            <label class="col-sm-6 col-form-label">Show Company logo and AWB number in AWB PDF 1</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <input type="checkbox" id="show_company_logo_and_awb_number_in_docket_pdf_1" value="1" autocomplete="nope" name="pdf[show_company_logo_and_awb_number_in_docket_pdf_1]" <?php echo isset($setting['show_company_logo_and_awb_number_in_docket_pdf_1']) && $setting['show_company_logo_and_awb_number_in_docket_pdf_1'] == 1 ? 'checked' : ''; ?>>
                    <label for="show_company_logo_and_awb_number_in_docket_pdf_1" style="height: 10px !important;"> </label>
                </div>
            </div>
        </div>
    </div> */ ?>
    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['show_service_in_docket_pdf_4']) ? $setting_comment['show_service_in_docket_pdf_4'] : ''; ?>" class="col-sm-6 col-form-label">Show Service In AWB PDF 4</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[show_service_in_docket_pdf_4]" id="" cols="80" rows="1"><?php echo isset($setting['show_service_in_docket_pdf_4']) ? $setting['show_service_in_docket_pdf_4'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_service_in_docket_pdf_4" value="1" autocomplete="nope" name="pdf[show_service_in_docket_pdf_4]" <?php echo isset($setting['show_service_in_docket_pdf_4']) && $setting['show_service_in_docket_pdf_4'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_service_in_docket_pdf_4" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['hide_all_amounts_in_docket_pdf4_if_payment_type_is_credit']) ? $setting_comment['hide_all_amounts_in_docket_pdf4_if_payment_type_is_credit'] : ''; ?>" class="col-sm-6 col-form-label">Hide All Amounts In AWB PDF4 If Payment Type Is Credit</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[hide_all_amounts_in_docket_pdf4_if_payment_type_is_credit]" id="" cols="80" rows="1"><?php echo isset($setting['hide_all_amounts_in_docket_pdf4_if_payment_type_is_credit']) ? $setting['hide_all_amounts_in_docket_pdf4_if_payment_type_is_credit'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="hide_all_amounts_in_docket_pdf4_if_payment_type_is_credit" value="1" autocomplete="nope" name="pdf[hide_all_amounts_in_docket_pdf4_if_payment_type_is_credit]" <?php echo isset($setting['hide_all_amounts_in_docket_pdf4_if_payment_type_is_credit']) && $setting['hide_all_amounts_in_docket_pdf4_if_payment_type_is_credit'] == 1 ? 'checked' : ''; ?>>
                        <label for="hide_all_amounts_in_docket_pdf4_if_payment_type_is_credit" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['enable_pdf6_in_dockets_grid']) ? $setting_comment['enable_pdf6_in_dockets_grid'] : ''; ?>" class="col-sm-6 col-form-label">Enable PDF6 In AWBS Grid</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[enable_pdf6_in_dockets_grid]" id="" cols="80" rows="1"><?php echo isset($setting['enable_pdf6_in_dockets_grid']) ? $setting['enable_pdf6_in_dockets_grid'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_pdf6_in_dockets_grid" value="1" autocomplete="nope" name="pdf[enable_pdf6_in_dockets_grid]" <?php echo isset($setting['enable_pdf6_in_dockets_grid']) && $setting['enable_pdf6_in_dockets_grid'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_pdf6_in_dockets_grid" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <?php /*<div class="col-12">
        <div class="form-group row">
            <label class="col-sm-6 col-form-label">Show Invoice Number In PDF 1</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <input type="checkbox" id="show_invoice_number_in_pdf_1" value="1" autocomplete="nope" name="pdf[show_invoice_number_in_pdf_1]" <?php echo isset($setting['show_invoice_number_in_pdf_1']) && $setting['show_invoice_number_in_pdf_1'] == 1 ? 'checked' : ''; ?>>
                    <label for="show_invoice_number_in_pdf_1" style="height: 10px !important;"> </label>
                </div>
            </div>
        </div>
    </div>


    <div class="col-12">
        <div class="form-group row">
            <label class="col-sm-6 col-form-label">Show Origin and Destination Name From Location Master In PDF 1 & Show Account Code From Customer Master In PDF 1</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <input type="checkbox" id="show_origin_and_destination_name_from_location_master_in_pdf_1_and_show_account_code_from_customer_master_in_pdf_1" value="1" autocomplete="nope" name="pdf[show_origin_and_destination_name_from_location_master_in_pdf_1_and_show_account_code_from_customer_master_in_pdf_1]" <?php echo isset($setting['show_origin_and_destination_name_from_location_master_in_pdf_1_and_show_account_code_from_customer_master_in_pdf_1']) && $setting['show_origin_and_destination_name_from_location_master_in_pdf_1_and_show_account_code_from_customer_master_in_pdf_1'] == 1 ? 'checked' : ''; ?>>
                    <label for="show_origin_and_destination_name_from_location_master_in_pdf_1_and_show_account_code_from_customer_master_in_pdf_1" style="height: 10px !important;"> </label>
                </div>
            </div>
        </div>
    </div>*/ ?>
    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['add_invoice_number_in_pdf_6']) ? $setting_comment['add_invoice_number_in_pdf_6'] : ''; ?>" class="col-sm-6 col-form-label">Add invoice number in PDF 6</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[add_invoice_number_in_pdf_6]" id="" cols="80" rows="1"><?php echo isset($setting['add_invoice_number_in_pdf_6']) ? $setting['add_invoice_number_in_pdf_6'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="add_invoice_number_in_pdf_6" value="1" autocomplete="nope" name="pdf[add_invoice_number_in_pdf_6]" <?php echo isset($setting['add_invoice_number_in_pdf_6']) && $setting['add_invoice_number_in_pdf_6'] == 1 ? 'checked' : ''; ?>>
                        <label for="add_invoice_number_in_pdf_6" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['show_four_copies_for_docket_pdf_4']) ? $setting_comment['show_four_copies_for_docket_pdf_4'] : ''; ?>" class="col-sm-6 col-form-label">Show Four Copies for AWB PDF 4</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[show_four_copies_for_docket_pdf_4]" id="" cols="80" rows="1"><?php echo isset($setting['show_four_copies_for_docket_pdf_4']) ? $setting['show_four_copies_for_docket_pdf_4'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_four_copies_for_docket_pdf_4" value="1" autocomplete="nope" name="pdf[show_four_copies_for_docket_pdf_4]" <?php echo isset($setting['show_four_copies_for_docket_pdf_4']) && $setting['show_four_copies_for_docket_pdf_4'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_four_copies_for_docket_pdf_4" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['show_origin_and_destination_zone_in_pdf_5']) ? $setting_comment['show_origin_and_destination_zone_in_pdf_5'] : ''; ?>" class="col-sm-6 col-form-label">Show Origin And Destination Zone In PDF 5</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[show_origin_and_destination_zone_in_pdf_5]" id="" cols="80" rows="1"><?php echo isset($setting['show_origin_and_destination_zone_in_pdf_5']) ? $setting['show_origin_and_destination_zone_in_pdf_5'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_origin_and_destination_zone_in_pdf_5" value="1" autocomplete="nope" name="pdf[show_origin_and_destination_zone_in_pdf_5]" <?php echo isset($setting['show_origin_and_destination_zone_in_pdf_5']) && $setting['show_origin_and_destination_zone_in_pdf_5'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_origin_and_destination_zone_in_pdf_5" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['enable_bonds_pdf5']) ? $setting_comment['enable_bonds_pdf5'] : ''; ?>" class="col-sm-6 col-form-label">Enable Bonds PDF5</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[enable_bonds_pdf5]" id="" cols="80" rows="1"><?php echo isset($setting['enable_bonds_pdf5']) ? $setting['enable_bonds_pdf5'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_bonds_pdf5" value="1" autocomplete="nope" name="pdf[enable_bonds_pdf5]" <?php echo isset($setting['enable_bonds_pdf5']) && $setting['enable_bonds_pdf5'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_bonds_pdf5" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['hide_parcel_number_from_pdf2_and_show_awb_number_with_barcode']) ? $setting_comment['hide_parcel_number_from_pdf2_and_show_awb_number_with_barcode'] : ''; ?>" class="col-sm-6 col-form-label">Hide Parcel number from PDF2 and Show AWB number with Barcode</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[hide_parcel_number_from_pdf2_and_show_awb_number_with_barcode]" id="" cols="80" rows="1"><?php echo isset($setting['hide_parcel_number_from_pdf2_and_show_awb_number_with_barcode']) ? $setting['hide_parcel_number_from_pdf2_and_show_awb_number_with_barcode'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="hide_parcel_number_from_pdf2_and_show_awb_number_with_barcode" value="1" autocomplete="nope" name="pdf[hide_parcel_number_from_pdf2_and_show_awb_number_with_barcode]" <?php echo isset($setting['hide_parcel_number_from_pdf2_and_show_awb_number_with_barcode']) && $setting['hide_parcel_number_from_pdf2_and_show_awb_number_with_barcode'] == 1 ? 'checked' : ''; ?>>
                        <label for="hide_parcel_number_from_pdf2_and_show_awb_number_with_barcode" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['add_master_label_in_docket_pdf3']) ? $setting_comment['add_master_label_in_docket_pdf3'] : ''; ?>" class="col-sm-6 col-form-label">Add Master Label In AWB PDF3</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[add_master_label_in_docket_pdf3]" id="" cols="80" rows="1"><?php echo isset($setting['add_master_label_in_docket_pdf3']) ? $setting['add_master_label_in_docket_pdf3'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="add_master_label_in_docket_pdf3" value="1" autocomplete="nope" name="pdf[add_master_label_in_docket_pdf3]" <?php echo isset($setting['add_master_label_in_docket_pdf3']) && $setting['add_master_label_in_docket_pdf3'] == 1 ? 'checked' : ''; ?>>
                        <label for="add_master_label_in_docket_pdf3" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['add_master_label_in_docket_pdf3']) ? $setting_comment['add_master_label_in_docket_pdf3'] : ''; ?>" class="col-sm-6 col-form-label">Add Master Label In AWB PDF3</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[add_master_label_in_docket_pdf3]" id="" cols="80" rows="1"><?php echo isset($setting['add_master_label_in_docket_pdf3']) ? $setting['add_master_label_in_docket_pdf3'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="add_master_label_in_docket_pdf3" value="1" autocomplete="nope" name="pdf[add_master_label_in_docket_pdf3]" <?php echo isset($setting['add_master_label_in_docket_pdf3']) && $setting['add_master_label_in_docket_pdf3'] == 1 ? 'checked' : ''; ?>>
                        <label for="add_master_label_in_docket_pdf3" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['enable_docket_pdf5_for_blueline']) ? $setting_comment['enable_docket_pdf5_for_blueline'] : ''; ?>" class="col-sm-6 col-form-label">Enable AWB PDF5 For Blueline</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[enable_docket_pdf5_for_blueline]" id="" cols="80" rows="1"><?php echo isset($setting['enable_docket_pdf5_for_blueline']) ? $setting['enable_docket_pdf5_for_blueline'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_docket_pdf5_for_blueline" value="1" autocomplete="nope" name="pdf[enable_docket_pdf5_for_blueline]" <?php echo isset($setting['enable_docket_pdf5_for_blueline']) && $setting['enable_docket_pdf5_for_blueline'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_docket_pdf5_for_blueline" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['show_pdf_7_in_awb_grid_and_customer_portal']) ? $setting_comment['show_pdf_7_in_awb_grid_and_customer_portal'] : ''; ?>" class="col-sm-6 col-form-label">Show PDF 7 in awb grid and customer portal</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[show_pdf_7_in_awb_grid_and_customer_portal]" id="" cols="80" rows="1"><?php echo isset($setting['show_pdf_7_in_awb_grid_and_customer_portal']) ? $setting['show_pdf_7_in_awb_grid_and_customer_portal'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_pdf_7_in_awb_grid_and_customer_portal" value="1" autocomplete="nope" name="pdf[show_pdf_7_in_awb_grid_and_customer_portal]" <?php echo isset($setting['show_pdf_7_in_awb_grid_and_customer_portal']) && $setting['show_pdf_7_in_awb_grid_and_customer_portal'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_pdf_7_in_awb_grid_and_customer_portal" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['enable_pdf3_for_the_ssbc']) ? $setting_comment['enable_pdf3_for_the_ssbc'] : ''; ?>" class="col-sm-6 col-form-label">Enable PDF3 for The SSBC</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[enable_pdf3_for_the_ssbc]" id="" cols="80" rows="1"><?php echo isset($setting['enable_pdf3_for_the_ssbc']) ? $setting['enable_pdf3_for_the_ssbc'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_pdf3_for_the_ssbc" value="1" autocomplete="nope" name="pdf[enable_pdf3_for_the_ssbc]" <?php echo isset($setting['enable_pdf3_for_the_ssbc']) && $setting['enable_pdf3_for_the_ssbc'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_pdf3_for_the_ssbc" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['customized_pdf6_for_tsc']) ? $setting_comment['customized_pdf6_for_tsc'] : ''; ?>" class="col-sm-6 col-form-label">Customized PDF6 for TSC</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[customized_pdf6_for_tsc]" id="" cols="80" rows="1"><?php echo isset($setting['customized_pdf6_for_tsc']) ? $setting['customized_pdf6_for_tsc'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="customized_pdf6_for_tsc" value="1" autocomplete="nope" name="pdf[customized_pdf6_for_tsc]" <?php echo isset($setting['customized_pdf6_for_tsc']) && $setting['customized_pdf6_for_tsc'] == 1 ? 'checked' : ''; ?>>
                        <label for="customized_pdf6_for_tsc" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['remove_barcode_and_awbno_from_free_form_invoice_pdf']) ? $setting_comment['remove_barcode_and_awbno_from_free_form_invoice_pdf'] : ''; ?>" class="col-sm-6 col-form-label">Remove Barcode And AWB No From Free Form Invoice PDF</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[remove_barcode_and_awbno_from_free_form_invoice_pdf]" id="" cols="80" rows="1"><?php echo isset($setting['remove_barcode_and_awbno_from_free_form_invoice_pdf']) ? $setting['remove_barcode_and_awbno_from_free_form_invoice_pdf'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="remove_barcode_and_awbno_from_free_form_invoice_pdf" value="1" autocomplete="nope" name="pdf[remove_barcode_and_awbno_from_free_form_invoice_pdf]" <?php echo isset($setting['remove_barcode_and_awbno_from_free_form_invoice_pdf']) && $setting['remove_barcode_and_awbno_from_free_form_invoice_pdf'] == 1 ? 'checked' : ''; ?>>
                        <label for="remove_barcode_and_awbno_from_free_form_invoice_pdf" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['enable_non_dg']) ? $setting_comment['enable_non_dg'] : ''; ?>" class="col-sm-6 col-form-label">Enable NON DG</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[enable_non_dg]" id="" cols="80" rows="1"><?php echo isset($setting['enable_non_dg']) ? $setting['enable_non_dg'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_non_dg" value="1" autocomplete="nope" name="pdf[enable_non_dg]" <?php echo isset($setting['enable_non_dg']) && $setting['enable_non_dg'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_non_dg" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['remove_volume_weight_from_free_form_invoice_pdf']) ? $setting_comment['remove_volume_weight_from_free_form_invoice_pdf'] : ''; ?>" class="col-sm-6 col-form-label">Remove Volume Weight from Free Form Invoice PDF</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[remove_volume_weight_from_free_form_invoice_pdf]" id="" cols="80" rows="1"><?php echo isset($setting['remove_volume_weight_from_free_form_invoice_pdf']) ? $setting['remove_volume_weight_from_free_form_invoice_pdf'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="remove_volume_weight_from_free_form_invoice_pdf" value="1" autocomplete="nope" name="pdf[remove_volume_weight_from_free_form_invoice_pdf]" <?php echo isset($setting['remove_volume_weight_from_free_form_invoice_pdf']) && $setting['remove_volume_weight_from_free_form_invoice_pdf'] == 1 ? 'checked' : ''; ?>>
                        <label for="remove_volume_weight_from_free_form_invoice_pdf" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['hide_incoterm_from_free_form']) ? $setting_comment['hide_incoterm_from_free_form'] : ''; ?>" class="col-sm-6 col-form-label">HIDE INCOTERM FROM FREE FORM</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[hide_incoterm_from_free_form]" id="" cols="80" rows="1"><?php echo isset($setting['hide_incoterm_from_free_form']) ? $setting['hide_incoterm_from_free_form'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="hide_incoterm_from_free_form" value="1" autocomplete="nope" name="pdf[hide_incoterm_from_free_form]" <?php echo isset($setting['hide_incoterm_from_free_form']) && $setting['hide_incoterm_from_free_form'] == 1 ? 'checked' : ''; ?>>
                        <label for="hide_incoterm_from_free_form" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['hide_chargeable_wt_from_free_form']) ? $setting_comment['hide_chargeable_wt_from_free_form'] : ''; ?>" class="col-sm-6 col-form-label">HIDE CHARGEABLE WEIGHT FROM FREE FORM INVOICE</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[hide_chargeable_wt_from_free_form]" id="" cols="80" rows="1"><?php echo isset($setting['hide_chargeable_wt_from_free_form']) ? $setting['hide_chargeable_wt_from_free_form'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="hide_chargeable_wt_from_free_form" value="1" autocomplete="nope" name="pdf[hide_chargeable_wt_from_free_form]" <?php echo isset($setting['hide_chargeable_wt_from_free_form']) && $setting['hide_chargeable_wt_from_free_form'] == 1 ? 'checked' : ''; ?>>
                        <label for="hide_chargeable_wt_from_free_form" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['hide_pcs_from_free_form']) ? $setting_comment['hide_pcs_from_free_form'] : ''; ?>" class="col-sm-6 col-form-label">HIDE PIECES FROM FREE FORM INVOICE</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[hide_pcs_from_free_form]" id="" cols="80" rows="1"><?php echo isset($setting['hide_pcs_from_free_form']) ? $setting['hide_pcs_from_free_form'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="hide_pcs_from_free_form" value="1" autocomplete="nope" name="pdf[hide_pcs_from_free_form]" <?php echo isset($setting['hide_pcs_from_free_form']) && $setting['hide_pcs_from_free_form'] == 1 ? 'checked' : ''; ?>>
                        <label for="hide_pcs_from_free_form" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['show_awb_no_from_free_form']) ? $setting_comment['show_awb_no_from_free_form'] : ''; ?>" class="col-sm-6 col-form-label">SHOW AWB NUMBER IN FREE FORM INVOICE</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[show_awb_no_from_free_form]" id="" cols="80" rows="1"><?php echo isset($setting['show_awb_no_from_free_form']) ? $setting['show_awb_no_from_free_form'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_awb_no_from_free_form" value="1" autocomplete="nope" name="pdf[show_awb_no_from_free_form]" <?php echo isset($setting['show_awb_no_from_free_form']) && $setting['show_awb_no_from_free_form'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_awb_no_from_free_form" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['rename_company_name_to_name_free_form']) ? $setting_comment['rename_company_name_to_name_free_form'] : ''; ?>" class="col-sm-6 col-form-label">RENAME SHIPPER/CONSIGNEE COMAPNY NAME TO NAME IN FREE FORM INVOICE</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[rename_company_name_to_name_free_form]" id="" cols="80" rows="1"><?php echo isset($setting['rename_company_name_to_name_free_form']) ? $setting['rename_company_name_to_name_free_form'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="rename_company_name_to_name_free_form" value="1" autocomplete="nope" name="pdf[rename_company_name_to_name_free_form]" <?php echo isset($setting['rename_company_name_to_name_free_form']) && $setting['rename_company_name_to_name_free_form'] == 1 ? 'checked' : ''; ?>>
                        <label for="rename_company_name_to_name_free_form" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['add_forwarding_in_freeform_invoice_pdf']) ? $setting_comment['add_forwarding_in_freeform_invoice_pdf'] : ''; ?>" class="col-sm-6 col-form-label">Add Forwarding Number In Free From Invoice PDF</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[add_forwarding_in_freeform_invoice_pdf]" id="" cols="80" rows="1"><?php echo isset($setting['add_forwarding_in_freeform_invoice_pdf']) ? $setting['add_forwarding_in_freeform_invoice_pdf'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="add_forwarding_in_freeform_invoice_pdf" value="1" autocomplete="nope" name="pdf[add_forwarding_in_freeform_invoice_pdf]" <?php echo isset($setting['add_forwarding_in_freeform_invoice_pdf']) && $setting['add_forwarding_in_freeform_invoice_pdf'] == 1 ? 'checked' : ''; ?>>
                        <label for="add_forwarding_in_freeform_invoice_pdf" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['show_old_free_form']) ? $setting_comment['show_old_free_form'] : ''; ?>" class="col-sm-6 col-form-label">SHOW OLD FREE FORM PDF</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[show_old_free_form]" id="" cols="80" rows="1"><?php echo isset($setting['show_old_free_form']) ? $setting['show_old_free_form'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_old_free_form" value="1" autocomplete="nope" name="pdf[show_old_free_form]" <?php echo isset($setting['show_old_free_form']) && $setting['show_old_free_form'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_old_free_form" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['remove_dims_from_free_form']) ? $setting_comment['remove_dims_from_free_form'] : ''; ?>" class="col-sm-6 col-form-label">REMOVE DIMS FROM FREE FORM</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[remove_dims_from_free_form]" id="" cols="80" rows="1"><?php echo isset($setting['remove_dims_from_free_form']) ? $setting['remove_dims_from_free_form'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="remove_dims_from_free_form" value="1" autocomplete="nope" name="pdf[remove_dims_from_free_form]" <?php echo isset($setting['remove_dims_from_free_form']) && $setting['remove_dims_from_free_form'] == 1 ? 'checked' : ''; ?>>
                        <label for="remove_dims_from_free_form" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['hide_act_wt_from_free_form']) ? $setting_comment['hide_act_wt_from_free_form'] : ''; ?>" class="col-sm-6 col-form-label">HIDE ACTUAL WEIGHT FROM FREE FORM</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[hide_act_wt_from_free_form]" id="" cols="80" rows="1"><?php echo isset($setting['hide_act_wt_from_free_form']) ? $setting['hide_act_wt_from_free_form'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="hide_act_wt_from_free_form" value="1" autocomplete="nope" name="pdf[hide_act_wt_from_free_form]" <?php echo isset($setting['hide_act_wt_from_free_form']) && $setting['hide_act_wt_from_free_form'] == 1 ? 'checked' : ''; ?>>
                        <label for="hide_act_wt_from_free_form" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['hide_dial_code_from_free_form']) ? $setting_comment['hide_dial_code_from_free_form'] : ''; ?>" class="col-sm-6 col-form-label">HIDE DIAL CODE FROM FREE FORM INVOICE PDF</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[hide_dial_code_from_free_form]" id="" cols="80" rows="1"><?php echo isset($setting['hide_dial_code_from_free_form']) ? $setting['hide_dial_code_from_free_form'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="hide_dial_code_from_free_form" value="1" autocomplete="nope" name="pdf[hide_dial_code_from_free_form]" <?php echo isset($setting['hide_dial_code_from_free_form']) && $setting['hide_dial_code_from_free_form'] == 1 ? 'checked' : ''; ?>>
                        <label for="hide_dial_code_from_free_form" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['show_barcode_in_free_from']) ? $setting_comment['show_barcode_in_free_from'] : ''; ?>" class="col-sm-6 col-form-label">SHOW BARCODE IN FREE FORM INVOICE PDF</label>
            <div class="col-sm-6">
                <div class="checkbox">

                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[show_barcode_in_free_from]" id="" cols="80" rows="1"><?php echo isset($setting['show_barcode_in_free_from']) ? $setting['show_barcode_in_free_from'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_barcode_in_free_from" value="1" autocomplete="nope" name="pdf[show_barcode_in_free_from]" <?php echo isset($setting['show_barcode_in_free_from']) && $setting['show_barcode_in_free_from'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_barcode_in_free_from" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="combine_attachment" title="<?php echo isset($setting_comment['select_attachment_to_combine']) ? $setting_comment['select_attachment_to_combine'] : ''; ?>" class="col-sm-6 col-form-label">Select Attachment to Combine </label>
            <div class="col-sm-6">
                <?php if (isset($mode) && $mode == "add_comments") { ?>
                    <textarea name="pdf[select_attachment_to_combine]" id="" cols="80" rows="1"><?php echo isset($setting['select_attachment_to_combine']) ? $setting['select_attachment_to_combine'] : ''; ?></textarea>
                <?php } else { ?>
                    <?php
                    $selected_attachment = isset($setting['select_attachment_to_combine']) && $setting['select_attachment_to_combine'] != "" ? explode(",", $setting['select_attachment_to_combine']) : array();
                    ?>
                    <select id="combine_attachment" class="form-control col-12 combine_attachment" name="pdf[select_attachment_to_combine][]" multiple>
                        <option value="">Select Attachment to Combine</option>
                        <option value="1" <?php echo in_array("1", $selected_attachment) ? 'selected' : ''; ?>>Shipper Copy</option>
                        <option value="2" <?php echo in_array("2", $selected_attachment) ? 'selected' : ''; ?>>Box Label</option>
                        <option value="3" <?php echo in_array("3", $selected_attachment) ? 'selected' : ''; ?>>Free Form</option>
                        <option value="4" <?php echo in_array("4", $selected_attachment) ? 'selected' : ''; ?>>Address Label</option>
                        <option value="5" <?php echo in_array("5", $selected_attachment) ? 'selected' : ''; ?>>Auth Letter</option>
                        <option value="6" <?php echo in_array("6", $selected_attachment) ? 'selected' : ''; ?>>KYC</option>
                    </select>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['tif_size']) ? $setting_comment['tif_size'] : ''; ?>" class="col-sm-6 col-form-label">COMPRESS TIF SIZE </label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[tif_size]" id="" cols="80" rows="1"><?php echo isset($setting['tif_size']) ? $setting['tif_size'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="tif_size" value="1" autocomplete="nope" name="pdf[tif_size]" <?php echo isset($setting['tif_size']) && $setting['tif_size'] == 1 ? 'checked' : ''; ?>>
                        <label for="tif_size" style="height: 10px !important;"> </label>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['enable_rd_invoice_pdf']) ? $setting_comment['enable_rd_invoice_pdf'] : ''; ?>" class="col-sm-6 col-form-label">ENABLE RD INVOICE PDF</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[enable_rd_invoice_pdf]" id="" cols="80" rows="1"><?php echo isset($setting['enable_rd_invoice_pdf']) ? $setting['enable_rd_invoice_pdf'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="enable_rd_invoice_pdf" value="1" autocomplete="nope" name="pdf[enable_rd_invoice_pdf]" <?php echo isset($setting['enable_rd_invoice_pdf']) && $setting['enable_rd_invoice_pdf'] == 1 ? 'checked' : ''; ?>>
                        <label for="enable_rd_invoice_pdf" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['show_reference_no_in_free_form_pdf']) ? $setting_comment['show_reference_no_in_free_form_pdf'] : ''; ?>" class="col-sm-6 col-form-label">SHOW REFERENCE NO IN FREE FORM PDF</label>
            <div class="col-sm-6">
                <div class="checkbox">

                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[show_reference_no_in_free_form_pdf]" id="" cols="80" rows="1"><?php echo isset($setting['show_reference_no_in_free_form_pdf']) ? $setting['show_reference_no_in_free_form_pdf'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_reference_no_in_free_form_pdf" value="1" autocomplete="nope" name="pdf[show_reference_no_in_free_form_pdf]" <?php echo isset($setting['show_reference_no_in_free_form_pdf']) && $setting['show_reference_no_in_free_form_pdf'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_reference_no_in_free_form_pdf" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label title="<?php echo isset($setting_comment['show_two_box_labels']) ? $setting_comment['show_two_box_labels'] : ''; ?>" class="col-sm-6 col-form-label">Show 2 box labels. PDF 2 and 3 </label>
            <div class="col-sm-6">
                <div class="checkbox">

                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="pdf[show_two_box_labels]" id="" cols="80" rows="1"><?php echo isset($setting['show_two_box_labels']) ? $setting['show_two_box_labels'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_two_box_labels" value="1" autocomplete="nope" name="pdf[show_two_box_labels]" <?php echo isset($setting['show_two_box_labels']) && $setting['show_two_box_labels'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_two_box_labels" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('plugin/select_search'); ?>

<script>
    $(document).ready(function() {
        $(".combine_attachment").select2();
    });
</script>