<div class="box box-default">
  <div class="box-header with-border">
    <h3 class="box-title">
      <?php if ($mode == 'update') { ?>
        Edit Company - <?php echo $company['company_name']; ?>
      <?php } else { ?>
        Add Company
      <?php } ?>
    </h3>
  </div>
  <!-- /.box-header -->
  <form enctype="multipart/form-data" method="POST" id="company_form" action="<?php echo  isset($mode) && $mode == 'update' ?  site_url('company/update') : site_url('company/insert') ?>">
    <input type="hidden" value="<?php echo isset($company['id']) ? $company['id'] : ''; ?>" name="company_id">
    <div class="box-body">
      <div class="row">
        <div class="col-12">
          <div class="col-12">
            <input type="hidden" id="expiry_date_main" value="<?php echo isset($mode) && $mode == 'update' ? get_format_date(DATE_INPUT_FORMAT, ($company['expiry_date'])) : date(DATE_INPUT_FORMAT); ?>" />
            <div class="form-group row">
              <label for="example-text-input" class="col-sm-2 col-form-label">Company Name</label>
              <div class="col-sm-10">
                <input class="form-control" type="text" value="<?php echo isset($company['company_name']) ? $company['company_name'] : ''; ?>" id="example-text-input" name="company[company_name]">
              </div>
            </div>

            <div class="form-group row">
              <label for="example-text-input" class="col-sm-2 col-form-label">IP ADDRESS/SUBDOMAIN</label>
              <div class="col-sm-10">
                <input class="form-control" type="text" value="<?php echo isset($company['company_domain']) ? $company['company_domain'] : ''; ?>" id="example-text-input" name="company[company_domain]">
              </div>
            </div>

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

            <div class="form-group row">
              <label for="example-text-input" class="col-sm-2 col-form-label">No. Of Software Logins</label>
              <div class="col-sm-10">
                <input class="form-control" type="number" value="<?php echo isset($company['login_count']) ? $company['login_count'] : ''; ?>" id="example-text-input" name="company[login_count]">
              </div>
            </div>

            <div class="form-group row">
              <label for="example-text-input" class="col-sm-2 col-form-label">No. Of Customer Portal Logins</label>
              <div class="col-sm-10">
                <input class="form-control" type="number" value="<?php echo isset($company['portal_login_count']) ? $company['portal_login_count'] : ''; ?>" id="example-text-input" name="company[portal_login_count]">
              </div>
            </div>

            <div class="form-group row">
              <label for="example-text-input" class="col-sm-2 col-form-label">Description</label>
              <div class="col-sm-10">
                <textarea class="form-control" rows="10" name="company[description]"><?php echo isset($company['description']) ? $company['description'] : ''; ?></textarea>
              </div>
            </div>

            <div class="form-group row">
              <label for="cpassword" class="col-sm-2 col-form-label">Onboarding Date<span class="required">*</span></label>
              <div class="col-sm-10">
                <input type="date" class="form-control pull-right datepicker_text" id="datepicker" name="onboard_date" data-inputmask="'alias': 'dd/mm/yyyy'" value="<?php echo isset($company['onboard_date']) ? get_format_date(DATE_INPUT_FORMAT, ($company['onboard_date'])) : date(DATE_INPUT_FORMAT); ?>" autocomplete="off">
              </div>
            </div>

            <div class="form-group row">
              <label for="cpassword" class="col-sm-2 col-form-label">No. Of Days payment made<span class="required">*</span></label>
              <div class="col-sm-10">
                <input type="text" class="form-control pull-right payment_days" name="company[payment_days]" value="<?php echo isset($company['payment_days']) ? $company['payment_days'] : 999999; ?>" autocomplete="off">
              </div>
            </div>

            <div class="form-group row">
              <label for="cpassword" class="col-sm-2 col-form-label">Expiry Date<span class="required">*</span></label>
              <div class="col-sm-10">
                <input type="date" class="form-control pull-right datepicker_text" id="expiry_date" data-inputmask="'alias': 'dd/mm/yyyy'" name="expiry_date" value="<?php echo isset($company['expiry_date']) ? get_format_date(DATE_INPUT_FORMAT, ($company['expiry_date'])) : date(DEFAULT_EXPIRY_DATE); ?>" autocomplete="off">
              </div>
            </div>

            <div class="form-group row">
              <label for="cpassword" class="col-sm-2 col-form-label"></label>
              <div class="col-sm-10">
                <div class="checkbox">
                  <input type="checkbox" id="show_powered_by" name="company[show_powered_by]" value="1" <?php echo isset($company['show_powered_by']) && $company['show_powered_by'] == 1 ? 'checked' : ''; ?>>
                  <label for="show_powered_by">SHOW POWERED BY

                  </label>
                </div>
              </div>
            </div>

            <div class="form-group row">
              <label for="cpassword" class="col-sm-2 col-form-label"></label>
              <div class="col-sm-10">
                <div class="checkbox">
                  <input type="checkbox" id="show_payment_message" name="company[show_payment_message]" value="1" <?php echo isset($company['show_payment_message']) && $company['show_payment_message'] == 1 ? 'checked' : ''; ?>>
                  <label for="show_payment_message">SHOW PAYMENT MESSAGE

                  </label>
                </div>
              </div>
            </div>


            <div class="form-group row">
              <label for="email_id" class="col-sm-2 col-form-label">Upload Logo</label>
              <div class="col-sm-10">
                <input type="file" accept="image/*" name="comp_logo">
                <?php if (isset($company['logo']) && $company['logo'] != '' && file_exists($company['logo'])) { ?>
                  <p class="mb-0 mt-1">
                    <img src="<?php echo base_url($company['logo']); ?>" class="img-responsive" style="height: 150px;width:150px;" />
                  </p>
                <?php } ?>
              </div>
            </div>

            <div class="form-group row">
              <div class="col-sm-12">
                <button type="submit" class="btn btn-primary pull-right">Submit</button>
              </div>
            </div>
          </div>

        </div>
        <!-- /.col -->

      </div>
      <!-- /.row -->
    </div>
  </form>
  <!-- /.box-body -->
</div>
<?php $this->load->view('plugin/datepicker'); ?>
<script type="text/javascript" src="<?php echo JS_PATH_BACKEND; ?>jquery.validate.js"></script>
<script>
  $(document).ready(function() {

    $(".payment_days").on("keyup", function() {
      var expiry_date = $("#expiry_date_main").val();
      var DateArr = expiry_date.split("/");
      var expiry_date = DateArr[1] + '/' + DateArr[0] + '/' + DateArr[2];

      var date = new Date(expiry_date);
      var days = parseInt($(this).val(), 10);

      if (!isNaN(date.getTime())) {
        date.setDate(date.getDate() + days);
        $("#expiry_date").datepicker("update", date);
      }
    });

    $("#company_form").validate({
      rules: {
        'company[company_name]': {
          required: true
        },
        'onboard_date': {
          required: true
        },
        'expiry_date': {
          required: true
        }
      },
      messages: {
        'company[company_name]': {
          required: 'Company Name Required'
        }
      },
      errorElement: 'p',
      errorPlacement: function(error, element) {
        error.insertAfter(element);
      },
      submitHandler: function(form) {
        $("#company_form").find(':input[type=submit]').prop('disabled', true);
        return true;
      }
    });
  });
</script>