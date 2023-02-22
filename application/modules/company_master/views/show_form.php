<?php
$sessiondata = $this->session->userdata('admin_user');
$tab_index = 1; ?>
<div class="box box-default">
  <form enctype="multipart/form-data" method="POST" id="item_form" action="<?php echo  isset($mode) && $mode == 'update' ?  site_url('company_master/update') : site_url('company_master/insert') ?>">
    <div class="box-header with-border">
      <h3 class="box-title">
        <?php if ($mode == 'update') { ?>
          Edit <?php echo $heading; ?> - <?php echo $company['name']; ?>
        <?php } else { ?>
          Add <?php echo $heading; ?>
        <?php } ?>
      </h3>
      <button type="submit" class="btn btn-primary pull-right">Save</button>
      <?php if (isset($mode) && $mode != 'update') { ?>
        <a class="pull-right btn btn-secondary text-white" onclick="history.back()" href="javascript:void(0);">BACK</a>
      <?php } ?>
    </div>
    <!-- /.box-header -->
    <input type="hidden" value="<?php echo isset($company['id']) ? $company['id'] : ''; ?>" name="company_id">
    <div class="box-body">
      <?php $this->load->view('flashdata_msg'); ?>
      <nav>
        <div class="nav nav-tabs nav-justified" id="nav-tab" role="tablist">
          <a class="nav-item nav-link active" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="true">
            <i class="fa fa-user"></i>&nbsp;Profile
          </a>
          <a class="nav-item nav-link" id="nav-logo-tab" data-toggle="tab" href="#nav-logo" role="tab" aria-controls="nav-logo" aria-selected="false">
            <i class="fa fa-image"></i>&nbsp;Logo,sign & Stamp
          </a>
        </div>
      </nav>
      <div style="background: #ffd4d4;color: #7f2828;padding: 2px;display:none;" class="error_msg">
        <span><b></b></span>
      </div>

      <div class="tab-content pt-2" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
          <div class="row">
            <div class="col-6">
              <div class="col-12">
                <div class="form-group row">
                  <label for="cust_name" class="col-sm-2 col-form-label">Name<span class="required">*</span></label>
                  <div class="col-sm-10">
                    <input class="form-control" type="text" value="<?php echo isset($company['name']) ? $company['name'] : ''; ?>" name="company[name]" tabindex="<?php echo $tab_index++; ?>">
                  </div>
                </div>
              </div>
              <div class="col-12">
                <div class="form-group row">
                  <label for="cust_code" class="col-sm-2 col-form-label">Code<span class="required">*</span></label>
                  <?php
                  $code_style = '';
                  if ($mode == 'update') {
                    $code_style = "disabled";
                  }
                  ?>
                  <div class="col-sm-10">
                    <input <?= $code_style ?> class="form-control" type="text" value="<?php echo isset($company['code']) ? $company['code'] : ''; ?>" name="company[code]" tabindex="<?php echo $tab_index++; ?>">
                  </div>
                </div>
              </div>

              <div class="col-12">
                <div class="form-group row">
                  <label for="cust_code" class="col-sm-2 col-form-label">Email</label>
                  <div class="col-sm-10">
                    <input class="form-control" type="email" value="<?php echo isset($company['email_id']) ? $company['email_id'] : ''; ?>" name="company[email_id]" tabindex="<?php echo $tab_index++; ?>">
                  </div>
                </div>
              </div>
              <div class="col-12">
                <div class="form-group row">
                  <label for="cust_code" class="col-sm-2 col-form-label">CONTACT NO</label>
                  <div class="col-sm-10">
                    <input class="form-control" type="text" value="<?php echo isset($company['contact_no']) ? $company['contact_no'] : ''; ?>" name="company[contact_no]" tabindex="<?php echo $tab_index++; ?>">
                  </div>
                </div>
              </div>
              <div class="col-12">
                <div class="form-group row">
                  <label for="cust_code" class="col-sm-2 col-form-label">WEBSITE</label>
                  <div class="col-sm-10">
                    <input class="form-control" type="text" value="<?php echo isset($company['website']) ? $company['website'] : ''; ?>" name="company[website]" tabindex="<?php echo $tab_index++; ?>">
                  </div>
                </div>
              </div>

              <div class="col-12">
                <div class="form-group row">
                  <label for="cust_code" class="col-sm-2 col-form-label">ADDRESS</label>
                  <div class="col-sm-10">
                    <textarea class="form-control" name="company[address]" tabindex="<?php echo $tab_index++; ?>"><?php echo isset($company['address']) ? $company['address'] : ''; ?></textarea>
                  </div>
                </div>
              </div>

              <div class="col-12">
                <div class="form-group row">
                  <label for="cust_code" class="col-sm-2 col-form-label">CITY</label>
                  <div class="col-sm-10">
                    <input class="form-control" type="text" value="<?php echo isset($company['city']) ? $company['city'] : ''; ?>" name="company[city]" tabindex="<?php echo $tab_index++; ?>">
                  </div>
                </div>
              </div>

              <div class="col-12">
                <div class="form-group row">
                  <label for="cust_name" class="col-sm-2 col-form-label">STATE</label>
                  <div class="col-sm-10">
                    <input class="form-control" type="text" value="<?php echo isset($company['state']) ? $company['state'] : ''; ?>" name="company[state]" tabindex="<?php echo $tab_index++; ?>">
                  </div>
                </div>
              </div>

              <!-- <div class="col-12">
                <div class="form-group row">
                  <label for="cust_name" class="col-sm-2 col-form-label">COUNTRY</label>
                  <div class="col-sm-10">
                    <input class="form-control" type="text" value="<?php echo isset($company['country']) ? $company['country'] : ''; ?>" name="company[country]" tabindex="<?php echo $tab_index++; ?>">
                  </div>
                </div>
              </div> -->

              <div class="col-12">
                <div class="form-group row">
                  <label for="country" class="col-sm-2 col-form-label">Country</label>
                  <div class="col-sm-10">
                    <input type="hidden" value="" id="autosuggest_country_id" />
                    <input class="form-control code_input_short" id="autosuggest_country" name="autosuggest_country" type="text" value="<?php echo isset($company['country']) && isset($all_country[strtolower(trim($company['country']))]) ? $all_country[strtolower(trim($company['country']))]['code'] : ''; ?>" tabindex="<?php echo $tab_index++; ?>" autocomplete="nope" />
                    <input readonly class="form-control name_input_long" id="autosuggest_country_name" type="text" name="company[country]" value="<?php echo isset($company['country']) && isset($all_country[strtolower(trim($company['country']))]) ? $all_country[strtolower(trim($company['country']))]['name'] : ''; ?>" autocomplete="nope" />
                  </div>
                </div>
              </div>

              <div class="col-12">
                <div class="form-group row">
                  <label for="cust_name" class="col-sm-2 col-form-label">PINCODE</label>
                  <div class="col-sm-10">
                    <input class="form-control" type="text" value="<?php echo isset($company['pincode']) ? $company['pincode'] : ''; ?>" name="company[pincode]" tabindex="<?php echo $tab_index++; ?>">
                  </div>
                </div>
              </div>

              <div class="col-12">
                <div class="form-group row">
                  <label for="code" class="col-sm-2 col-form-label">DEFAULT Customer</label>
                  <div class="col-sm-10">
                    <input type="hidden" value="<?php echo isset($company['customer_id']) ? $company['customer_id'] : ''; ?>" id="customer_id" name="company[customer_id]" />
                    <input placeholder="SEARCH HERE..." class="form-control code_input" id="customer_search_name" type="text" value="<?php echo isset($company['customer_id']) && isset($all_customer[$company['customer_id']]) ? $all_customer[$company['customer_id']]['code'] : ''; ?>" tabindex="<?php echo $tab_index++; ?>" />
                    <input disabled class="form-control name_input" id="customer_search_name_name" type="text" value="<?php echo isset($company['customer_id']) && isset($all_customer[$company['customer_id']]) ? $all_customer[$company['customer_id']]['name'] : ''; ?>" />
                  </div>
                </div>
              </div>

              <div class="col-12">
                <div class="form-group row">
                  <label for="code" class="col-sm-2 col-form-label">DEFAULT Vendor</label>
                  <div class="col-sm-10">
                    <input type="hidden" value="<?php echo isset($company['co_vendor_id']) ? $company['co_vendor_id'] : ''; ?>" id="co_vendor_id" name="company[co_vendor_id]" />
                    <input placeholder="SEARCH HERE..." class="form-control code_input" id="co_vendor_search_name" type="text" value="<?php echo isset($company['co_vendor_id']) && isset($all_co_vendor[$company['co_vendor_id']]) ? $all_co_vendor[$company['co_vendor_id']]['code'] : ''; ?>" tabindex="<?php echo $tab_index++; ?>" />
                    <input disabled class="form-control name_input" id="co_vendor_search_name_name" type="text" value="<?php echo isset($company['co_vendor_id']) && isset($all_co_vendor[$company['co_vendor_id']]) ? $all_co_vendor[$company['co_vendor_id']]['name'] : ''; ?>" />
                  </div>
                </div>
              </div>


              <div class="col-12">
                <div class="form-group row">
                  <label for="example-text-input" class="col-sm-2 col-form-label">Status</label>
                  <div class="form-group">
                    <div class="radio">
                      <input name="company[status]" type="radio" id="Option_1" checked="" value="1">
                      <label for="Option_1">Active</label>
                    </div>
                    <div class="radio">
                      <input name="company[status]" type="radio" id="Option_2" value="2" <?php echo isset($company['status']) && $company['status'] == 2 ?  'checked=""' : ''; ?>>
                      <label for="Option_2">Inactive</label>
                    </div>
                  </div>
                </div>
              </div>
              <?php if ($sessiondata["email"] == "virag@itdservices.in") { ?>
                <div class="col-12">
                  <div class="form-group row">
                    <label for="cust_name" class="col-sm-2 col-form-label">E-INVOICE API USERNAME</label>
                    <div class="col-sm-10">
                      <input class="form-control" type="text" value="<?php echo isset($company['einvoice_api_user']) ? $company['einvoice_api_user'] : ''; ?>" name="company[einvoice_api_user]" tabindex="<?php echo $tab_index++; ?>">
                    </div>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group row">
                    <label for="cust_name" class="col-sm-2 col-form-label">E-INVOICE API PASSWORD</label>
                    <div class="col-sm-10">
                      <input class="form-control" type="text" value="<?php echo isset($company['einvoice_api_pass']) ? $company['einvoice_api_pass'] : ''; ?>" name="company[einvoice_api_pass]" tabindex="<?php echo $tab_index++; ?>">
                    </div>
                  </div>
                </div>
              <?php } ?>
              <!-- /.col -->
            </div>

            <div class="col-6">

              <div class="col-12">
                <div class="form-group row">
                  <label for="cust_name" class="col-sm-2 col-form-label">PAN NUMBER</label>
                  <div class="col-sm-10">
                    <input class="form-control" type="text" value="<?php echo isset($company['pan_number']) ? $company['pan_number'] : ''; ?>" name="company[pan_number]" tabindex="<?php echo $tab_index++; ?>">
                  </div>
                </div>
              </div>
              <div class="col-12">
                <div class="form-group row">
                  <label for="cust_name" class="col-sm-2 col-form-label">CIN NUMBER</label>
                  <div class="col-sm-10">
                    <input class="form-control" type="text" value="<?php echo isset($company['cin_number']) ? $company['cin_number'] : ''; ?>" name="company[cin_number]" tabindex="<?php echo $tab_index++; ?>">
                  </div>
                </div>
              </div>

              <div class="col-12">
                <div class="form-group row">
                  <label for="cust_name" class="col-sm-2 col-form-label">TRN NUMBER</label>
                  <div class="col-sm-10">
                    <input class="form-control" type="text" value="<?php echo isset($company['trn_number']) ? $company['trn_number'] : ''; ?>" name="company[trn_number]" tabindex="<?php echo $tab_index++; ?>">
                  </div>
                </div>
              </div>

              <div class="col-12">
                <div class="form-group row">
                  <label for="cust_name" class="col-sm-2 col-form-label">TAX TYPE</label>
                  <div class="col-sm-10">
                    <select class="form-control" name="company[tax_type]">
                      <?php
                      if (isset($all_gst_type) && is_array($all_gst_type) && count($all_gst_type) > 0) {
                        foreach ($all_gst_type as $fkey => $fvalue) { ?>
                          <option value="<?php echo $fvalue['id'] ?>" <?php echo isset($company['tax_type']) && $company['tax_type'] == $fvalue['id'] ? 'selected' : ''; ?>><?php echo $fvalue['name']; ?></option>
                      <?php }
                      } ?>
                    </select>
                  </div>
                </div>
              </div>

              <div class="col-12">
                <div class="form-group row">
                  <label for="cust_name" class="col-sm-2 col-form-label">GST/VAT No.</label>
                  <div class="col-sm-10">
                    <input class="form-control" type="text" value="<?php echo isset($company['gst_number']) ? $company['gst_number'] : ''; ?>" name="company[gst_number]" tabindex="<?php echo $tab_index++; ?>">
                  </div>
                </div>
              </div>
              <div class="col-12">
                <div class="form-group row">
                  <label for="cust_name" class="col-sm-2 col-form-label">SAC CODE</label>
                  <div class="col-sm-10">
                    <input class="form-control" type="text" value="<?php echo isset($company['sac_code']) ? $company['sac_code'] : ''; ?>" name="company[sac_code]" tabindex="<?php echo $tab_index++; ?>">
                  </div>
                </div>
              </div>

              <div class="col-12">
                <div class="form-group row">
                  <label for="cust_code" class="col-sm-2 col-form-label">PLACE OF SUPPLY</label>
                  <div class="col-sm-10">
                    <textarea class="form-control" name="company[supply_place]" tabindex="<?php echo $tab_index++; ?>"><?php echo isset($company['supply_place']) ? $company['supply_place'] : ''; ?></textarea>
                  </div>
                </div>
              </div>

              <div class="col-12">
                <div class="form-group row">
                  <label for="cust_name" class="col-sm-2 col-form-label">ADDRESS LINE 1</label>
                  <div class="col-sm-10">
                    <textarea class="form-control" name="company[address1]" tabindex="<?php echo $tab_index++; ?>"><?php echo isset($company['address1']) ? $company['address1'] : ''; ?></textarea>
                  </div>
                </div>
              </div>
              <div class="col-12">
                <div class="form-group row">
                  <label for="cust_name" class="col-sm-2 col-form-label">COURIER REGISTRATION NUMBER</label>
                  <div class="col-sm-10">
                    <input class="form-control" type="text" value="<?php echo isset($company['courier_reg_no']) ? $company['courier_reg_no'] : ''; ?>" name="company[courier_reg_no]" tabindex="<?php echo $tab_index++; ?>">
                  </div>
                </div>
              </div>
              <div class="col-12">
                <div class="form-group row">
                  <label for="cust_name" class="col-sm-2 col-form-label">NAME OF THE AUTHORIZED COURIER</label>
                  <div class="col-sm-10">
                    <input class="form-control" type="text" value="<?php echo isset($company['auth_courier_name']) ? $company['auth_courier_name'] : ''; ?>" name="company[auth_courier_name]" tabindex="<?php echo $tab_index++; ?>">
                  </div>
                </div>
              </div>


              <!-- <div class="col-12">
                <div class="form-group row">
                  <label for="cust_name" class="col-sm-2 col-form-label">From Email</label>
                  <div class="col-sm-10">
                    <select class="form-control" name="company[email_configuration_id]" tabindex="<?php echo $tab_index++; ?>">
                      <option value="">SELECT...</option>
                      <?php
                      if (isset($all_from_email) && is_array($all_from_email) && count($all_from_email) > 0) {
                        foreach ($all_from_email as $ckey => $cvalue) { ?>
                          <option value="<?php echo $cvalue['id'] ?>" <?php echo isset($company['email_configuration_id']) && $company['email_configuration_id'] == $cvalue['id'] ? 'selected' : ''; ?>><?php echo strtoupper($cvalue['name']) ?></option>
                      <?php }
                      }
                      ?>
                    </select>
                  </div>
                </div>
              </div> -->

              <div class="col-12">
                <div class="form-group row">
                  <label for="cust_name" class="col-sm-2 col-form-label">Whatsapp TOKEN</label>
                  <div class="col-sm-10">
                    <input class="form-control" type="text" value="<?php echo isset($company['whatsapp_token']) ? $company['whatsapp_token'] : ''; ?>" name="company[whatsapp_token]" tabindex="<?php echo $tab_index++; ?>">
                  </div>
                </div>
              </div>


              <div class="col-12 p-0">
                <h4 style="border-bottom: 2px solid #212529;font-size:15px;">COLOR SCHEME</h4>
              </div>
              <div class="col-12">
                <div class="form-group row">
                  <label for="cust_name" class="col-sm-2 col-form-label">TEXT COLOR</label>
                  <div class="col-sm-10">
                    <input style="width: 50%;height: 30px;" class="form-control" type="color" value="<?php echo isset($company['text_color']) ? $company['text_color'] : ''; ?>" name="company[text_color]" tabindex="<?php echo $tab_index++; ?>">
                  </div>
                </div>
              </div>
              <div class="col-12">
                <div class="form-group row">
                  <label for="cust_name" class="col-sm-2 col-form-label">BORDER COLOR</label>
                  <div class="col-sm-10">
                    <input style="width: 50%;height: 30px;" class="form-control" type="color" value="<?php echo isset($company['border_color']) ? $company['border_color'] : ''; ?>" name="company[border_color]" tabindex="<?php echo $tab_index++; ?>">
                  </div>
                </div>
              </div>
              <div class="col-12">
                <div class="form-group row">
                  <label for="cust_name" class="col-sm-2 col-form-label">BACKGROUND COLOR</label>
                  <div class="col-sm-10">
                    <input style="width: 50%;height: 30px;" class="form-control" type="color" value="<?php echo isset($company['background_color']) ? $company['background_color'] : ''; ?>" name="company[background_color]" tabindex="<?php echo $tab_index++; ?>">
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>

        <div class="tab-pane fade" id="nav-logo" role="tabpanel" aria-labelledby="nav-logo-tab">
          <div class="row">
            <div class="col-6">
              <div class="col-12">
                <div class="form-group row">
                  <label for="cust_name" class="col-sm-2 col-form-label">LOGO</label>
                  <div class="col-sm-7">
                    <input class="form-control" type="file" name="logo_file" accept="image/*">
                  </div>
                  <div class="col-sm-3">
                    <?php if (isset($company['logo_file']) && $company['logo_file'] != '' && file_exists($company['logo_file'])) { ?>
                      <a class="file_button" target="blank" href="<?php echo base_url($company['logo_file']); ?>">VIEW FILE</a>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="col-12">
                <div class="form-group row">
                  <label for="cust_name" class="col-sm-2 col-form-label">DIGITAL SIGNATURE</label>
                  <div class="col-sm-7">
                    <input class="form-control" type="file" name="signature_file" accept="image/*">
                  </div>
                  <div class="col-sm-3">
                    <?php if (isset($company['signature_file']) && $company['signature_file'] != '' && file_exists($company['signature_file'])) { ?>
                      <a class="file_button" target="blank" href="<?php echo base_url($company['signature_file']); ?>">VIEW FILE</a>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="col-12">
                <div class="form-group row">
                  <label for="cust_name" class="col-sm-2 col-form-label">STAMP</label>
                  <div class="col-sm-7">
                    <input class="form-control" type="file" name="stamp_file" accept="image/*">
                  </div>
                  <div class="col-sm-3">
                    <?php if (isset($company['stamp_file']) && $company['stamp_file'] != '' && file_exists($company['stamp_file'])) { ?>
                      <a class="file_button" target="blank" href="<?php echo base_url($company['stamp_file']); ?>">VIEW FILE</a>
                    <?php } ?>
                  </div>
                </div>
              </div>


              <div class="col-12">
                <div class="form-group row">
                  <label for="cust_name" class="col-sm-2 col-form-label">Authorization letter</label>
                  <div class="col-sm-7">
                    <input class="form-control" type="file" name="authorization_letter">
                  </div>
                  <div class="col-sm-3">
                    <?php if (isset($company['authorization_letter']) && $company['authorization_letter'] != '' && file_exists($company['authorization_letter'])) { ?>
                      <a class="file_button" target="blank" href="<?php echo base_url($company['authorization_letter']); ?>">VIEW FILE</a>
                    <?php } ?>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>


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
      <!-- /.row -->
    </div>
  </form>
  <!-- /.box-body -->
</div>

<?php $this->load->view('plugin/autosuggest_input'); ?>
<script type="text/javascript" src="<?php echo JS_PATH_BACKEND; ?>jquery.validate.js"></script>
<?php
$autosuggest_arr = array(
  14 => array('input_id' => 'autosuggest_country', 'module' => 'country', 'hidden_id' => 'autosuggest_country_id'),
);
?>
<script>
  $(document).ready(function() {
    autosuggest_input('co_vendor_search_name', 'co_vendor', 'co_vendor_id');
    autosuggest_input('customer_search_name', 'customer', 'customer_id');
    $('#item_form').bind('keypress keydown keyup', function(e) {
      if (e.keyCode == 13 && !$(e.target).is('textarea')) {
        e.preventDefault();
      }
    });

    <?php foreach ($autosuggest_arr  as $key => $value) { ?>
      autosuggest_input('<?php echo $value['input_id'] ?>', '<?php echo $value['module'] ?>', '<?php echo $value['hidden_id'] ?>');
    <?php } ?>

    $.validator.addMethod("alphanumeric", function(value, element) {
      return this.optional(element) || /^[a-zA-Z0-9-_\s]+$/i.test(value);
    }, "Use only alphabet,number,hyphen(-),underscore(_) and space");

    $("#item_form").validate({
      rules: {
        'company[name]': {
          required: true
        },
        'company[code]': {
          alphanumeric: true,
          required: true
        }
      },
      messages: {
        'company[name]': {
          required: 'Name Required'
        },
        'company[code]': {
          required: 'Code Required'
        }
      },
      errorElement: 'p',
      errorPlacement: function(error, element) {
        error.insertAfter(element);
      },
      submitHandler: function(form) {
        console.log('ssd');
        var serial_arr = [];
        var SerialErrorMsg = '';
        $('input[name^="serial_no["]').each(function() {
          var serial_value = $(this).val();
          if (serial_arr.indexOf(serial_value) == -1) {
            serial_arr.push(serial_value);
          } else {
            SerialErrorMsg = "DUPLICATE SERIAL NO." + serial_value + " FOUND";
          }
        });



        check_account_no();
        var error_msg = $(".error_msg").text();
        if (SerialErrorMsg == '' && error_msg == '') {
          var id = "<?php echo isset($company['id']) ? $company['id'] : '0'; ?>";
          $.ajax({
            type: "POST",
            url: "<?php echo site_url('generic_detail/check_name'); ?>",
            data: {
              'name': $('#pdt_name').val(),
              'id': id,
              'module': 'company_master'
            },
            success: function(data) {
              if ($(".error_msg").text() == '') {
                $.ajax({
                  type: "POST",
                  url: "<?php echo site_url('generic_detail/check_bank_serial_no'); ?>",
                  data: {
                    'serial_no': serial_arr,
                    'id': id,
                  },
                  success: function(data) {
                    var returnedData = JSON.parse(data);
                    if (returnedData['error'] != undefined) {
                      $(".error_msg").text(returnedData['error']).show();
                    } else {
                      $("#item_form").find(':input[type=submit]').prop('disabled', true);
                      form.submit();
                    }
                  }
                });


              } else {
                return false;
              }

            },
            error: function(data) {
              bootbox.alert('Code already Exist');
            }
          });
        } else {
          bootbox.alert(SerialErrorMsg);
        }
      }
    });
  });
</script>