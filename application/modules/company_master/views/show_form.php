<?php
$sessiondata = $this->session->userdata('admin_user');
$tab_index = 1; ?>

<div class="col-xl-12">
  <form enctype="multipart/form-data" method="POST" id="company_master_form" action="<?php echo  isset($mode) && $mode == 'update' ?  site_url('company_master/update') : site_url('company_master/insert') ?>">
    <div class="row">
      <div class="mb-3 col-md-10">
        <h3 class="px-2">
          <?php if ($mode == 'update') { ?>
            Edit <?php echo $heading; ?> - <?php echo $company['name']; ?>
          <?php } else { ?>
            Add <?php echo $heading; ?>
          <?php } ?>
        </h3>
      </div>
      <div class="mb-3 col-md-2">
        <button type="submit" class="btn btn-primary me-2">Save</button>
        <button type="reset" class="btn btn-outline-secondary" onclick="history.back()" href="javascript:void(0);">BACK</button>
      </div>
    </div>

    <div class="nav-align-top mb-4">
      <ul class="nav nav-pills mb-3 nav-fill" role="tablist">
        <li class="nav-item">
          <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-justified-home" aria-controls="navs-pills-justified-home" aria-selected="true">
            <i class="tf-icons bx bx-user"></i> Profile
          </button>
        </li>
        <li class="nav-item">
          <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-justified-profile" aria-controls="navs-pills-justified-profile" aria-selected="false">
            <i class="tf-icons bx bx-user"></i> Logo,sign & Stamp
          </button>
        </li>
      </ul>
      <input type="hidden" value="<?php echo isset($company['id']) ? $company['id'] : ''; ?>" name="company_id">

      <div class="tab-content">
        <div class="tab-pane fade show active" id="navs-pills-justified-home" role="tabpanel">
          <!-- company profile starts here -->
          <div class="row">
            <div class="mb-3 col-md-6">
              <label for="company_name" class="form-label">Name</label>
              <input class="form-control" type="text" id="company_name" value="<?php echo isset($company['name']) ? $company['name'] : ''; ?>" name="company[name]" autofocus />
            </div>
            <div class="mb-3 col-md-6">
              <label for="company_name" class="form-label">Code</label>
              <input class="form-control" type="text" id="company_name" value="<?php echo isset($company['code']) ? $company['code'] : ''; ?>" name="company[code]" />
            </div>
            <div class="mb-3 col-md-6">
              <label for="company_email" class="form-label">Email</label>
              <input class="form-control" type="text" id="company_email" type="email" value="<?php echo isset($company['email_id']) ? $company['email_id'] : ''; ?>" name="company[email_id]" />
            </div>
            <div class="mb-3 col-md-6">
              <label for="company_contact_no" class="form-label">CONTACT NO</label>
              <input class="form-control" type="text" id="company_contact_no" value="<?php echo isset($company['contact_no']) ? $company['contact_no'] : ''; ?>" name="company[contact_no]" />
            </div>
            <div class="mb-3 col-md-6">
              <label for="company_address" class="form-label">ADDRESS</label>
              <textarea class="form-control" id="company_address" name="company[address]"><?php echo isset($company['address']) ? $company['address'] : ''; ?></textarea>
            </div>
            <div class="mb-3 col-md-6">
              <label for="company_website" class="form-label">WEBSITE</label>
              <input class="form-control" type="text" id="company_website" value="<?php echo isset($company['website']) ? $company['website'] : ''; ?>" name="company[website]" />
            </div>
            <div class="mb-3 col-md-6">
              <label for="company_city" class="form-label">CITY</label>
              <input class="form-control" type="text" id="company_city" value="<?php echo isset($company['city']) ? $company['city'] : ''; ?>" name="company[city]" />
            </div>
            <div class="mb-3 col-md-6">
              <label for="company_state" class="form-label">STATE</label>
              <input class="form-control" type="text" id="company_state" value="<?php echo isset($company['state']) ? $company['state'] : ''; ?>" name="company[state]" />
            </div>
            <div class="mb-3 col-md-6">
              <label for="company_country" class="form-label">COUNTRY</label>
              <input class="form-control" type="text" id="company_country" value="<?php echo isset($company['country']) ? $company['country'] : ''; ?>" name="company[country]" />
            </div>
            <div class="mb-3 col-md-6">
              <label for="company_pincode" class="form-label">PINCODE</label>
              <input class="form-control" type="text" id="company_pincode" value="<?php echo isset($company['pincode']) ? $company['pincode'] : ''; ?>" name="company[pincode]" />
            </div>

            <div class="mb-3 col-md-6">
              <label for="company_pincode" class="form-label">STATUS</label>
              <div class="form-check">
                <input name="company[status]" class="form-check-input" type="radio" id="Option_1" checked="" value="1">
                <label class="form-check-label" for="Option_1"> Active </label>
              </div>
              <div class="form-check">
                <input name="company[status]" class="form-check-input" type="radio" id="Option_2" value="2" <?php echo isset($company['status']) && $company['status'] == 2 ?  'checked=""' : ''; ?>>
                <label class="form-check-label" for="Option_2"> Active </label>
              </div>
            </div>

          </div>
          <div class="mt-2">
            <button type="submit" class="btn btn-primary me-2">Save</button>
            <button type="reset" class="btn btn-outline-secondary" onclick="history.back()" href="javascript:void(0);">BACK</button>
          </div>
          <!-- company profile ends here -->
        </div>
        <div class="tab-pane fade" id="navs-pills-justified-profile" role="tabpanel">
          <!-- company logo starts here -->
          <div class="mb-3">
            <label for="logo_file" class="form-label">LOGO</label>
            <input class="form-control" id="logo_file" type="file" name="logo_file" accept="image/*">
          </div>
          <div class="col-sm-3">
            <?php if (isset($company['logo_file']) && $company['logo_file'] != '' && file_exists($company['logo_file'])) { ?>
              <a class="file_button" target="blank" href="<?php echo base_url($company['logo_file']); ?>">VIEW FILE</a>
            <?php } ?>
          </div>
          <div class="mb-3">
            <label for="signature_file" class="form-label">DIGITAL SIGNATURE</label>
            <input class="form-control" id="signature_file" type="file" name="signature_file" accept="image/*">
          </div>
          <div class="col-sm-3">
            <?php if (isset($company['signature_file']) && $company['signature_file'] != '' && file_exists($company['signature_file'])) { ?>
              <a class="file_button" target="blank" href="<?php echo base_url($company['signature_file']); ?>">VIEW FILE</a>
            <?php } ?>
          </div>
          <div class="mb-3">
            <label for="stamp_file" class="form-label">STAMP</label>
            <input class="form-control" id="stamp_file" type="file" name="stamp_file" accept="image/*">
          </div>
          <div class="col-sm-3">
            <?php if (isset($company['stamp_file']) && $company['stamp_file'] != '' && file_exists($company['stamp_file'])) { ?>
              <a class="file_button" target="blank" href="<?php echo base_url($company['stamp_file']); ?>">VIEW FILE</a>
            <?php } ?>
          </div>
          <div class="mt-2">
            <button type="submit" class="btn btn-primary me-2">Save</button>
            <button type="reset" class="btn btn-outline-secondary" onclick="history.back()" href="javascript:void(0);">BACK</button>
          </div>
          <!-- company logo upload ends here -->
        </div>

      </div>
    </div>
  </form>
</div>

<?php $this->load->view('plugin/autosuggest_input'); ?>
<script type="text/javascript" src="<?php echo JS_PATH_BACKEND; ?>jquery.validate.js"></script>

<script>
  $(document).ready(function() {

    $('#company_master_form').bind('keypress keydown keyup', function(e) {
      if (e.keyCode == 13 && !$(e.target).is('textarea')) {
        e.preventDefault();
      }
    });


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
              if ($(".error_msg").text() == '') {} else {
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