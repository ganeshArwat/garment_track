<style>
  .table-bordered,
  .table-bordered>tbody>tr>td,
  .table-bordered>tbody>tr>th,
  .table-bordered>tfoot>tr>td,
  .table-bordered>tfoot>tr>th,
  .table-bordered>thead>tr>td,
  .table-bordered>thead>tr>th {
    border: 1px solid #d7c5c5 !important;
  }
</style>

<?php $tab_index = 1; ?>
<div class="box box-default">
  <?php $this->load->view('flashdata_msg'); ?>
  <form enctype="multipart/form-data" method="POST" id="invoice_form" action="<?php echo site_url('settings/custom_validation/insert_data') ?>">

    <div class="box-header with-border">
      <h3 class="box-title">
        SET <?php echo $heading; ?>
      </h3>
      <button type="submit" class="btn btn-primary pull-right">Save</button>
      <?php if (isset($mode) && $mode != 'update') { ?>
        <a class="pull-right btn btn-secondary text-white" onclick="history.back()" href="javascript:void(0);">BACK</a>
      <?php } ?>
    </div>
    <!-- /.box-header -->
    <input type="hidden" value="<?php echo isset($module_id) ? $module_id : ''; ?>" name="module_id">
    <div class="box-body">

      <?php if ($module_id == 1) { ?>
        <div class="row">
          <div class="col-12">
            <div class="col-6">
              <div class="form-group row">
                <label for="cust_id" class="col-sm-2 col-form-label">AWB SETTING</label>
                <div class="col-sm-8">
                  <div class="checkbox">
                    <input type="hidden" name="setting[docket_auto_generate]" id="auto_gen_check_hidden" value="<?php echo isset($setting_data['docket_auto_generate']) ? $setting_data['docket_auto_generate'] : 2; ?>">
                    <input type="checkbox" id="auto_gen_check" value="1" <?php echo isset($setting_data['docket_auto_generate']) && $setting_data['docket_auto_generate'] == 1 ? 'checked' : ''; ?>>
                    <label for="auto_gen_check" style="height: 10px !important;">ENABLE Auto generation of awb number </label>
                  </div>
                </div>
              </div>
            </div>

            <?php
            $format_style = "style='display:none';";
            if (isset($setting_data['docket_auto_generate']) && $setting_data['docket_auto_generate'] == 1) {
              $format_style = "";
            }

            ?>

            <div class="col-12 awb_no_format_div" <?php echo $format_style; ?>>
              <div class="form-group row">
                <label for="shipper_code" class="col-sm-1 col-form-label">AWB NO FORMAT<span class="required">*</span></label>
                <div class="col-sm-11">
                  <div class="radio" style="display: inline;">
                    <input name="setting[docket_format_type]" type="radio" id="Option_1" value="1" class="radio_format" <?php echo isset($setting_data['docket_format_type']) && $setting_data['docket_format_type'] == 1 ? 'checked' : 'checked'; ?>>
                    <label for="Option_1">RANGE WISE</label>
                  </div>
                  <div class="radio" style="display: inline;">
                    <input name="setting[docket_format_type]" type="radio" id="Option_2" value="2" class="radio_format" <?php echo isset($setting_data['docket_format_type']) && $setting_data['docket_format_type'] == 2 ? 'checked' : ''; ?>>
                    <label for="Option_2">CUSTOMER CODE WISE</label>
                  </div>
                  <div class="radio" style="display: inline;">
                    <input name="setting[docket_format_type]" type="radio" id="Option_3" value="3" class="radio_format" <?php echo isset($setting_data['docket_format_type']) && $setting_data['docket_format_type'] == 3 ? 'checked' : ''; ?>>
                    <label for="Option_3">CUSTOMER PREFIX WISE</label>
                  </div>
                  <div class="radio" style="display: inline;">
                    <input name="setting[docket_format_type]" type="radio" id="Option_4" value="4" class="radio_format" <?php echo isset($setting_data['docket_format_type']) && $setting_data['docket_format_type'] == 4 ? 'checked' : ''; ?>>
                    <label for="Option_4">CUSTOMER & SERVICE WISE</label>
                  </div>
                  <div class="radio" style="display: inline;">
                    <input name="setting[docket_format_type]" type="radio" id="Option_5" value="5" class="radio_format" <?php echo isset($setting_data['docket_format_type']) && $setting_data['docket_format_type'] == 5 ? 'checked' : ''; ?>>
                    <label for="Option_5">6 DIGIT RANDOM NO.</label>
                  </div>
                  <div class="radio" style="display: inline;">
                    <input name="setting[docket_format_type]" type="radio" id="Option_6" value="6" class="radio_format" <?php echo isset($setting_data['docket_format_type']) && $setting_data['docket_format_type'] == 6 ? 'checked' : ''; ?>>
                    <label for="Option_6">Custom Prefix.</label>
                  </div>
                  <div class="radio" style="display: inline;">
                    <input name="setting[docket_format_type]" type="radio" id="Option_7" value="7" class="radio_format" <?php echo isset($setting_data['docket_format_type']) && $setting_data['docket_format_type'] == 7 ? 'checked' : ''; ?>>
                    <label for="Option_7">CUSTOMER CODE & RANGE WISE</label>
                  </div>
                </div>
              </div>
            </div>


            <?php
            $range_style = "style='display:none';";
            if (
              isset($setting_data['docket_auto_generate']) && $setting_data['docket_auto_generate'] == 1
              && (isset($setting_data['docket_format_type']) && $setting_data['docket_format_type'] == 1 ||
                isset($setting_data['docket_format_type']) && $setting_data['docket_format_type'] == 7)
            ) {
              $range_style = "";
            }
            ?>
            <div class="col-6 range_format_div" <?php echo $range_style; ?>>
              <div class="form-group row">
                <label for="cust_code" class="col-sm-2 col-form-label">AWB NO Range<span class="required">*</span></label>
                <div class="col-sm-2">
                  <input type="text" value="START NUMBER" readonly style="background: #ededed;border: 1px solid #827272;" />
                </div>
                <div class="col-sm-2">
                  <input class="form-control" type="text" value="<?php echo isset($setting_data['docket_range_start']) ? $setting_data['docket_range_start'] : ''; ?>" id="range_start" name="setting[docket_range_start]" tabindex="<?php echo $tab_index++; ?>">
                </div>
                <div class="col-sm-2">
                  <input type="text" value="END NUMBER" readonly style="background: #ededed;border: 1px solid #827272;" />
                </div>
                <div class="col-sm-2">
                  <input class="form-control" type="text" value="<?php echo isset($setting_data['docket_range_end']) ? $setting_data['docket_range_end'] : ''; ?>" id="range_end" name="setting[docket_range_end]" tabindex="<?php echo $tab_index++; ?>">
                </div>

              </div>
            </div>
            <?php
            $range_style = "style='display:none';";
            if (
              isset($setting_data['docket_auto_generate']) && $setting_data['docket_auto_generate'] == 1
              && isset($setting_data['docket_format_type']) && $setting_data['docket_format_type'] == 6
            ) {
              $range_style = "";
            }
            ?>
            <div class="col-10 range_format_prefix_div" <?php echo $range_style; ?>>
              <div class="form-group row">
                <label for="cust_code" class="col-sm-3 col-form-label">AWB NO Range<span class="required">*</span></label>
                <div class="col-sm-1">
                  <input type="text" value="PREFIX" readonly style="background: #ededed;border: 1px solid #827272;" />
                </div>
                <div class="col-sm-2">
                  <input class="form-control" type="text" value="<?php echo isset($setting_data['docket_prefix']) ? $setting_data['docket_prefix'] : ''; ?>" id="docket_prefix" name="setting[docket_prefix]" tabindex="<?php echo $tab_index++; ?>">
                </div>
                <div class="col-sm-1">
                  <input type="text" value="START NUMBER PREFIX" readonly style="background: #ededed;border: 1px solid #827272;" />
                </div>
                <div class="col-sm-2">
                  <input class="form-control" type="text" value="<?php echo isset($setting_data['docket_prefix_range_start']) ? $setting_data['docket_prefix_range_start'] : ''; ?>" id="prefix_range_start" name="setting[docket_prefix_range_start]" tabindex="<?php echo $tab_index++; ?>">
                </div>
                <div class="col-sm-1">
                  <input type="text" value="END NUMBER PREFIX" readonly style="background: #ededed;border: 1px solid #827272;" />
                </div>
                <div class="col-sm-2">
                  <input class="form-control" type="text" value="<?php echo isset($setting_data['docket_prefix_range_end']) ? $setting_data['docket_prefix_range_end'] : ''; ?>" id="prefix_range_end" name="setting[docket_prefix_range_end]" tabindex="<?php echo $tab_index++; ?>">
                </div>

              </div>
            </div>

          </div>


          <!-- <div class="col-6">
          <div class="col-12">
            <div class="form-group row">
              <label for="cust_id" class="col-sm-3 col-form-label">CUSTOMER SETTING</label>
              <div class="col-sm-9">
                <div class="checkbox">
                  <input type="hidden" name="setting[customer_code_auto_generate]" id="auto_gen_cust_code_hidden" value="<?php echo isset($setting_data['customer_code_auto_generate']) ? $setting_data['customer_code_auto_generate'] : 2; ?>">
                  <input type="checkbox" id="auto_gen_cust_code" value="1" <?php echo isset($setting_data['customer_code_auto_generate']) && $setting_data['customer_code_auto_generate'] == 1 ? 'checked' : ''; ?>>
                  <label for="auto_gen_cust_code" style="height: 10px !important;">ENABLE Auto generation of CUSTOMER CODE </label>
                </div>
              </div>
            </div>
          </div>

          <?php
          $range_style = "style='display:none';";
          if (isset($setting_data['customer_code_auto_generate']) && $setting_data['customer_code_auto_generate'] == 1) {
            $range_style = "";
          }
          ?>
          <div class="col-12 cust_range_format_div" <?php echo $range_style; ?>>
            <div class="form-group row">
              <label for="cust_code" class="col-sm-3 col-form-label">CODE Range<span class="required">*</span></label>
              <div class="col-sm-2">
                <input type="text" value="START NUMBER" readonly style="background: #ededed;border: 1px solid #827272;" />
              </div>
              <div class="col-sm-2">
                <input class="form-control" type="text" value="<?php echo isset($setting_data['customer_code_range_start']) ? $setting_data['customer_code_range_start'] : ''; ?>" id="code_range_start" name="setting[customer_code_range_start]" tabindex="<?php echo $tab_index++; ?>">
              </div>
              <div class="col-sm-2">
                <input type="text" value="END NUMBER" readonly style="background: #ededed;border: 1px solid #827272;" />
              </div>
              <div class="col-sm-2">
                <input class="form-control" type="text" value="<?php echo isset($setting_data['customer_code_range_end']) ? $setting_data['customer_code_range_end'] : ''; ?>" id="code_range_end" name="setting[customer_code_range_end]" tabindex="<?php echo $tab_index++; ?>">
              </div>

            </div>
          </div>

        </div> -->

        </div>
      <?php } ?>

      <div class="row">
        <?php
        $table_cnt = 1;
        if (isset($all_fields) && is_array($all_fields) && count($all_fields) > 0) {
          foreach ($all_fields as $cat_key => $cat_value) { ?>
            <div class="col-3">
              <table class="table table-sm table-bordered table-striped">

                <tr class="section_head">
                  <td colspan="<?php echo $cat_key == 'GENERAL' ? 5 : 4 ?>;" style="text-align: center;"><?php echo $cat_key; ?></td>
                </tr>
                <tr class="section_head">
                  <td style="width:70%;font-size:10px;">FIELD</td>
                  <td style="width:15%;font-size:10px;">MAKE COMPLU<br>SORY

                    <div class="checkbox">
                      <input type="checkbox" id="complusory_<?php echo $table_cnt; ?>" class="selectAllCheckbox">
                      <label for="complusory_<?php echo $table_cnt; ?>" style="height: 10px !important;"></label>
                    </div>

                  </td>
                  <td style="width:15%;font-size:10px;">SHOW IN FORM
                    <div class="checkbox">
                      <input type="checkbox" id="show_<?php echo $table_cnt; ?>" class="selectAllCheckbox">
                      <label for="show_<?php echo $table_cnt; ?>" style="height: 10px !important;"></label>
                    </div>
                  </td>
                  <?php if ($cat_key == 'GENERAL') { ?>
                    <td style="width:15%;font-size:10px;">SHOW DROP<br>DOWN
                      <div class="checkbox">
                        <input type="checkbox" id="dropdown_<?php echo $table_cnt; ?>" class="selectAllCheckbox">
                        <label for="dropdown_<?php echo $table_cnt; ?>" style="height: 10px !important;"></label>
                      </div>
                    </td>
                  <?php } ?>
                  <?php if ($module_id == 1) { ?>
                    <td style="width:15%;font-size:10px;">SHOW <br>IN SUMMARY
                      <div class="checkbox">
                        <input type="checkbox" id="show_summary_<?php echo $table_cnt; ?>" class="selectAllCheckbox">
                        <label for="show_summary_<?php echo $table_cnt; ?>" style="height: 10px !important;"></label>
                      </div>
                    </td>
                  <?php } ?>
                </tr>
                <?php
                foreach ($cat_value as $ckey => $cvalue) { ?>
                  <tr>
                    <td>
                      <?php echo $cvalue['label_value']; ?>
                    </td>
                    <td>
                      <?php if ($cvalue['label_field_name'] != '') { ?>
                        <div class="checkbox">
                          <input type="checkbox" class="complusory_<?php echo $table_cnt; ?>" id="basic_company_<?php echo $cvalue['id']; ?>" name="validation_setting[]" value="<?php echo $cvalue['label_key']; ?>" <?php echo isset($setting[1]) && in_array($cvalue['label_key'], $setting[1]) ? 'checked' : ''; ?>>
                          <label for="basic_company_<?php echo $cvalue['id']; ?>" style="height: 10px !important;"></label>
                        </div>
                      <?php } ?>
                    </td>
                    <td>
                      <div class="checkbox">
                        <input type="checkbox" class="show_<?php echo $table_cnt; ?>" id="basic_company_show_<?php echo $cvalue['id']; ?>" name="validation_show[]" value="<?php echo $cvalue['label_key']; ?>" <?php echo isset($setting[2]) && in_array($cvalue['label_key'], $setting[2]) ? 'checked' : ''; ?>>
                        <label for="basic_company_show_<?php echo $cvalue['id']; ?>" style="height: 10px !important;"></label>
                      </div>
                    </td>
                    <?php if ($cat_key == 'GENERAL') {
                      $dropdown_field = array('origin_name', 'destination_name', 'product_name', 'vendor_name', 'co_vendor_name');

                    ?>
                      <td>
                        <?php if (in_array($cvalue['label_key'], $dropdown_field)) { ?>
                          <div class="checkbox">
                            <input type="checkbox" class="dropdown_<?php echo $table_cnt; ?>" id="basic_company_dropdown_<?php echo $cvalue['id']; ?>" name="dropdown_show[]" value="<?php echo $cvalue['label_key']; ?>" <?php echo isset($setting[3]) && in_array($cvalue['label_key'], $setting[3]) ? 'checked' : ''; ?>>
                            <label for="basic_company_dropdown_<?php echo $cvalue['id']; ?>" style="height: 10px !important;"></label>
                          </div>
                        <?php } ?>
                      </td>
                    <?php
                    } ?>
                    <?php if ($module_id == 1) { ?>
                      <td>
                        <div class="checkbox">
                          <input type="checkbox" class="show_summary_<?php echo $table_cnt; ?>" id="show_in_summary_<?php echo $cvalue['id']; ?>" name="summary_show[]" value="<?php echo $cvalue['label_key']; ?>" <?php echo isset($setting[4]) && in_array($cvalue['label_key'], $setting[4]) ? 'checked' : ''; ?>>
                          <label for="show_in_summary_<?php echo $cvalue['id']; ?>" style="height: 10px !important;"></label>
                        </div>
                      </td>
                    <?php } ?>
                  </tr>
                <?php } ?>
              </table>

              <?php if ($table_cnt == 4) { ?>
                <table class="table table-sm table-bordered table-striped">
                  <tbody>
                    <tr class="section_head">
                      <td colspan="2">KYC DETAILS</td>
                    </tr>
                    <tr class="section_head">
                      <td style="width:70%;font-size:10px;">FIELD</td>
                      <td style="width:15%;font-size:10px;">SHOW IN FORM
                        <div class="checkbox">
                          <input type="checkbox" id="show_kyc_<?php echo $table_cnt; ?>" class="selectAllCheckbox">
                          <label for="show_kyc_<?php echo $table_cnt; ?>" style="height: 10px !important;"></label>
                        </div>
                      </td>
                    </tr>

                    <?php
                    if (isset($all_gstin_type) && is_array($all_gstin_type) && count($all_gstin_type) > 0) {
                      foreach ($all_gstin_type as $gkey => $gvalue) {
                        $kyc_setting = isset($setting_data['kyc_setting']) && $setting_data['kyc_setting'] != '' ? explode(",", $setting_data['kyc_setting']) : array();
                        if (isset($kyc_setting) && is_array($kyc_setting) && count($kyc_setting) > 0) {
                          if (in_array($gvalue['id'], $kyc_setting)) {
                            $kyc_checked = "checked";
                          } else {
                            $kyc_checked = "";
                          }
                        } else {
                          $kyc_checked = "checked";
                        }
                    ?>
                        <tr>
                          <td><?php echo $gvalue['name']; ?></td>
                          <td>
                            <div class="checkbox">
                              <input type="checkbox" class="show_kyc_<?php echo $table_cnt; ?>" id="basic_kyc_show_<?php echo $gvalue['id']; ?>" name="kyc_field[]" value="<?php echo $gvalue['id']; ?>" <?php echo $kyc_checked; ?>>
                              <label for="basic_kyc_show_<?php echo $gvalue['id']; ?>" style="height: 10px !important;"></label>
                            </div>
                          </td>
                        </tr>
                    <?php    }
                    }
                    ?>
                  </tbody>
                </table>
              <?php } ?>

            </div>
        <?php $table_cnt++;
          }
        }
        ?>

      </div>


      <div class="col-12" id="awb_grid_col_sort_div">
        <div class="form-group row">
          <label for="address" class="col-sm-2 col-form-label">AWB GRID COLUMN SORTING<span class="required">*</span>
            <p class="text-danger">(Each column must be separated by comma)</p>
          </label>

          <div class="col-sm-10">
            <textarea required rows="10" onblur="getVal()" class="form-control selected_field" name="setting[docket_col_sort]" tabindex="<?php echo $tab_index++; ?>"><?php echo isset($setting_data['docket_col_sort']) ? $setting_data['docket_col_sort'] : ''; ?></textarea>

          </div>
        </div>
      </div>
      <table class="table table-bordered" id="awb_col_table">
        <tr class="section_head">
          <td colspan="4">Available AWB GRID Columns</td>
        </tr>
        <?php
        $invoice_col = array_chunk($grid_column, 4);
        foreach ($invoice_col as $ikey => $ivalue) {
        ?>
          <tr>
            <?php foreach ($ivalue as $key => $value) {
              if ($value['label_key'] == 'shipper_name') {
                $grid_label = 'SHIPPER';
              } else if ($value['label_key'] == 'consignee_name') {
                $grid_label = 'consignee';
              } else {
                $grid_label = $value['label_value'];
              }

            ?>
              <td>
                <div class="checkbox">
                  <span class="invoice-column-copy-btn" data-text="<?php echo $value['label_key']; ?>" title="Click to Copy">
                    <i class="fa fa-clone" aria-hidden="true"></i>
                  </span>
                  <label for="basic_checkbox_<?php echo $value['id']; ?>" style="height: 10px !important;"><?php echo $grid_label; ?></label>
                </div>


              </td>
            <?php } ?>
          </tr>
        <?php } ?>
      </table>
      <div class="col-12">
        <div class="col-12">
          <div class="form-group row">
            <div class="col-sm-12">
              <button type="submit" class="btn btn-primary pull-right">Save</button>
              <?php if (isset($mode) && $mode != 'update') { ?>
                <a class="pull-right btn btn-secondary text-white" onclick="history.back()" href="javascript:void(0);">BACK</a>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>

    </div>
  </form>
  <!-- /.box-body -->
</div>
<?php $this->load->view('plugin/select_search'); ?>

<script type="text/javascript" src="<?php echo JS_PATH_BACKEND; ?>jquery.validate.js"></script>
<script>
  $('input').attr('autocomplete', 'nope');
  $('textarea').attr('autocomplete', 'nope');

  function show_range_div() {
    var radio_format_value = $("input[class='radio_format']:checked").val();
    if ((radio_format_value == 1 || radio_format_value == 7) && $("#auto_gen_check").is(":checked")) {
      $(".range_format_div").show();
      $("#docket_prefix").val('');
      $(".range_format_prefix_div").hide();
      $("#prefix_range_end").val('');
      $("#prefix_range_start").val('');
    } else if (radio_format_value == 6 && $("#auto_gen_check").is(":checked")) {
      $(".range_format_prefix_div").show();
      $(".range_format_div").hide();
      $("#range_start").val('');
      $("#range_end").val('');
    } else {
      $(".range_format_div").hide();
      $(".range_format_prefix_div").hide();
      $("#range_start").val('');
      $("#range_end").val('');
      $("#prefix_range_end").val('');
      $("#prefix_range_start").val('');
      $("#docket_prefix").val('');
    }
  }

  function getVal() {
    const val = document.querySelector('textarea').value;
    var val_selected = val.split(',');

    $('.invoice-column-copy-btn').each(function() {

      var text_a = ($(this).attr('data-text'));
      if (val_selected.includes(text_a)) {
        $(this).closest('td').attr('style', 'color:white;background:black');
      }
    });
  }
  $(document).ready(function() {

    getVal();

    $('.invoice-column-copy-btn').click(function() {
      var $temp = $("<input>");
      $("body").append($temp);
      $temp.val($(this).data('text')).select();
      document.execCommand("copy");
      $temp.remove();
    });

    <?php if ($module_id != 1) { ?>
      $('#awb_grid_col_sort_div').hide();
      $('#awb_col_table').hide();
    <?php } ?>
    $('#auto_gen_check').change(function() {
      if (this.checked) {
        $(".awb_no_format_div").show();
        $(".range_format_div").show();
        $("#auto_gen_check_hidden").val(1);
      } else {
        $(".awb_no_format_div").hide();
        $(".range_format_div").hide();
        $("#auto_gen_check_hidden").val(2);
      }
      show_range_div();
    });
    $('.radio_format').change(function() {
      show_range_div();
    });

    $('#auto_gen_cust_code').change(function() {
      if (this.checked) {
        $(".cust_range_format_div").show();
        $("#auto_gen_cust_code_hidden").val(1);
      } else {
        $(".cust_range_format_div").hide();
        $("#auto_gen_cust_code_hidden").val(2);
        $("#code_range_start").val('');
        $("#code_range_end").val('');
      }
    });

    $('#basic_checkbox_all').click(function() {
      $(':checkbox.hub_select').prop('checked', this.checked);
    });


    $(".selectAllCheckbox").on("change", function() {
      var checkboxId = $(this).attr('id');
      console.log(checkboxId);
      if ($(this).is(":checked")) {
        $('input:checkbox[class^="' + checkboxId + '"]').each(function() { //loop through each checkbox
          this.checked = true; //select all checkboxes with class "checkbox1"
        });
      } else {
        $('input:checkbox[class^="' + checkboxId + '"]').each(function() { //loop through each checkbox
          this.checked = false; //select all checkboxes with class "checkbox1"
        });
      }
    });
    $.validator.addMethod("alphanumeric", function(value, element) {
      return this.optional(element) || /^[a-zA-Z0-9-_\s]+$/i.test(value);
    }, "Use only alphabet,number,hyphen(-),underscore(_) and space");

    $("#invoice_form").validate({
      rules: {
        'invoice[name]': {
          required: true
        },
        'invoice[code]': {
          required: true,
          alphanumeric: true
        },
        'setting[docket_range_start]': {
          required: true,
          digits: true
        },
        'setting[docket_range_end]': {
          required: true,
          digits: true
        },
        'setting[customer_code_range_start]': {
          required: true,
          digits: true
        },
        'setting[customer_code_range_end]': {
          required: true,
          digits: true
        }
      },
      messages: {
        'invoice[name]': {
          required: 'INVOICE HEADER Required'
        },
        'invoice[code]': {
          required: 'Code Required',
          alphanumeric: 'Use only alphabet,number,hyphen(-),underscore(_) and space'
        }
      },
      errorElement: 'p',
      errorPlacement: function(error, element) {
        error.insertAfter(element);
      },
      submitHandler: function(form) {
        var id = "<?php echo isset($invoice['id']) ? $invoice['id'] : '0'; ?>";
        $.ajax({
          type: "POST",
          url: "<?php echo site_url('generic_detail/check_code') ?>",
          data: {
            'code': $('#cust_code').val(),
            'id': id,
            'module': 'custom_invoice'
          },
          success: function(data) {
            $("#invoice_form").find(':input[type=submit]').prop('disabled', true);
            form.submit();
          },
          error: function(data) {
            bootbox.alert('Invoice Code already Exist');
          }
        });

      }
    });
  });
</script>