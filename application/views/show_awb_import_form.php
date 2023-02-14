<?php $tab_index = 1;
$sessiondata = $this->session->userdata('admin_user');
$this->user_id = $sessiondata['id'];
$this->user_type = $sessiondata['type'] == 'customer' ? 2 : 1;
$setting = get_all_app_setting(" AND module_name IN('docket')");
?>
<div class="row">
    <div class="col-6">
        <div class="box box-default">



            <div class="box-header with-border">
                <h3 class="box-title">
                    <?php echo "AWB IMPORT" ?>
                </h3>
            </div>
            <div class="box-body">
                <div class="row">

                    <div class="col-8">
                        <form enctype="multipart/form-data" method="POST" id="contract_form" action="<?php echo site_url('docket/import_data/insert_data'); ?>">
                            <input type="hidden" name="submit_check" id="submit_check" value="2" />
                            <div class="col-12">
                                <div class="form-group row">
                                    <label for="code" class="col-sm-2 col-form-label">CSV File<span class="required">*</span></label>
                                    <div class="col-sm-6">
                                        <input type="file" name="import_file" accept=".csv" />
                                    </div>
                                    <div class="col-sm-4">
                                        <button type="submit" class="btn btn-primary" onclick="set_click();">Import</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <?php
                    if ($this->user_type == 1) {
                        $awb_sample_file_path = 'media/sample_csv/docket_import_sample1_file.csv';
                    } else {
                        $awb_sample_file_path = 'media/sample_csv/portal_docket_import_sample1_file.csv';
                    }

                    if (isset($awb_sample_file_path) && $awb_sample_file_path != '') { ?>
                        <div class="col-4">
                            <div class="form-group row">
                                <a class="btn bg-olive" href="<?php echo base_url($awb_sample_file_path); ?>"><i class="fa fa-download"></i>&nbsp;DOWNLOAD SAMPLE CSV FILE</a>
                            </div>
                        </div>
                    <?php } ?>
                    <!-- /.row -->
                </div>
            </div>

            <!-- /.box-body -->
        </div>


        <div class="box box-default">

            <div class="box-header with-border">
                <h3 class="box-title">
                    <?php echo "AWB MATERIAL IMPORT" ?>
                </h3>
            </div>
            <div class="box-body">
                <div class="row">

                    <div class="col-8">
                        <form enctype="multipart/form-data" method="POST" id="contract_form" action="<?php echo site_url('docket/import_material_data/insert_data'); ?>">
                            <input type="hidden" name="submit_check" id="submit_check" value="1" />
                            <div class="col-12">
                                <div class="form-group row">
                                    <label for="code" class="col-sm-2 col-form-label">CSV File<span class="required">*</span></label>
                                    <div class="col-sm-6">
                                        <input type="file" name="import_file" accept=".csv" />
                                    </div>
                                    <div class="col-sm-4">
                                        <button type="submit" class="btn btn-primary" onclick="set_click();">Import</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <?php
                    $awb_material_sample_file_path = 'media/sample_csv/docket_material_import.csv';
                    if (isset($awb_material_sample_file_path) && $awb_material_sample_file_path != '') { ?>
                        <div class="col-4">
                            <div class="form-group row">
                                <a class="btn bg-olive" href="<?php echo base_url($awb_material_sample_file_path); ?>"><i class="fa fa-download"></i>&nbsp;DOWNLOAD SAMPLE CSV FILE</a>
                            </div>
                        </div>
                    <?php } ?>

                    <!-- /.row -->
                </div>
            </div>

            <!-- /.box-body -->
        </div>

        <div class="box box-default">

            <div class="box-header with-border">
                <h3 class="box-title">
                    <?php echo "AWB FREE FORM IMPORT" ?>
                </h3>
            </div>
            <div class="box-body">
                <div class="row">

                    <div class="col-8">
                        <form enctype="multipart/form-data" method="POST" id="contract_form" action="<?php echo site_url('docket/import_free_form_data/insert_data'); ?>">
                            <input type="hidden" name="submit_check" id="submit_check" value="1" />
                            <div class="col-12">
                                <div class="form-group row">
                                    <label for="code" class="col-sm-2 col-form-label">CSV File<span class="required">*</span></label>
                                    <div class="col-sm-6">
                                        <input type="file" name="import_file" accept=".csv" />
                                    </div>
                                    <div class="col-sm-4">
                                        <button type="submit" class="btn btn-primary" onclick="set_click();">Import</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <?php
                    $awb_material_sample_file_path = 'media/sample_csv/docket_free_form_import.csv';
                    if (isset($awb_material_sample_file_path) && $awb_material_sample_file_path != '') { ?>
                        <div class="col-4">
                            <div class="form-group row">
                                <a class="btn bg-olive" href="<?php echo base_url($awb_material_sample_file_path); ?>"><i class="fa fa-download"></i>&nbsp;DOWNLOAD SAMPLE CSV FILE</a>
                            </div>
                        </div>
                    <?php } ?>

                    <!-- /.row -->
                </div>
            </div>

            <!-- /.box-body -->
        </div>

        <?php
        if ($this->user_type == 1) {
        ?>
            <div class="box box-default">

                <div class="box-header with-border">
                    <h3 class="box-title">
                        <?php echo "AWB EVENT IMPORT1" ?>
                    </h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-8">
                            <form enctype="multipart/form-data" method="POST" id="contract_form" action="<?php echo site_url('docket/import_event_data/insert_data'); ?>">
                                <input type="hidden" name="submit_check" id="submit_check" value="1" />
                                <div class="col-12">
                                    <div class="form-group row">
                                        <label for="code" class="col-sm-2 col-form-label">CSV File<span class="required">*</span></label>
                                        <div class="col-sm-6">
                                            <input type="file" name="import_file" accept=".csv" />
                                        </div>
                                        <div class="col-sm-4">
                                            <button type="submit" class="btn btn-primary" onclick="set_click();">Import</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <?php
                        $awb_event_sample_file_path = 'media/sample_csv/docket_event_sample.csv';
                        if (isset($awb_event_sample_file_path) && $awb_event_sample_file_path != '') { ?>
                            <div class="col-4">
                                <div class="form-group row">
                                    <a class="btn bg-olive" href="<?php echo base_url($awb_event_sample_file_path); ?>"><i class="fa fa-download"></i>&nbsp;DOWNLOAD SAMPLE CSV FILE</a>
                                </div>
                            </div>
                        <?php } ?>
                        <!-- /.row -->
                    </div>
                </div>

                <!-- /.box-body -->
            </div>
            <?php if (isset($setting['pod_import_grandspeed']) && $setting['pod_import_grandspeed'] == 1) { ?>
                <div class="box box-default">

                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <?php echo "AWB EVENT IMPORT2" ?>
                        </h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-8">
                                <form enctype="multipart/form-data" method="POST" id="contract_form" action="<?php echo site_url('docket/import_event_data/insert_grandspeed_data'); ?>">
                                    <input type="hidden" name="submit_check" id="submit_check" value="1" />
                                    <div class="col-12">
                                        <div class="form-group row">
                                            <label for="code" class="col-sm-2 col-form-label">CSV File<span class="required">*</span></label>
                                            <div class="col-sm-6">
                                                <input type="file" name="import_file" accept=".csv" />
                                            </div>
                                            <div class="col-sm-4">
                                                <button type="submit" class="btn btn-primary" onclick="set_click();">Import</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <?php
                            $awb_grand_event_sample_file_path = 'media/sample_csv/docket_grandspeed_event_sample.csv';
                            if (isset($awb_grand_event_sample_file_path) && $awb_grand_event_sample_file_path != '') { ?>
                                <div class="col-4">
                                    <div class="form-group row">
                                        <a class="btn bg-olive" href="<?php echo base_url($awb_grand_event_sample_file_path); ?>"><i class="fa fa-download"></i>&nbsp;DOWNLOAD SAMPLE CSV FILE</a>
                                    </div>
                                </div>
                            <?php } ?>
                            <!-- /.row -->
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
            <?php } ?>
            <div class="box box-default">

                <div class="box-header with-border">
                    <h3 class="box-title">
                        <?php echo "SALES CHARGES IMPORT" ?>
                    </h3>
                </div>
                <div class="box-body">
                    <div class="row">

                        <div class="col-8">
                            <form enctype="multipart/form-data" method="POST" id="contract_form" action="<?php echo site_url('docket/import_sales_charges/insert_data'); ?>">
                                <input type="hidden" name="submit_check" id="submit_check" value="1" />
                                <div class="col-12">
                                    <div class="form-group row">
                                        <label for="code" class="col-sm-2 col-form-label">CSV File<span class="required">*</span></label>
                                        <div class="col-sm-6">
                                            <input type="file" name="import_file" accept=".csv" />
                                        </div>
                                        <div class="col-sm-4">
                                            <button type="submit" class="btn btn-primary" onclick="set_click();">Import</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <?php
                        $awb_charges_file_path = 'media/sample_csv/docket_charges_sample.csv';
                        if (isset($awb_charges_file_path) && $awb_charges_file_path != '') { ?>
                            <div class="col-4">
                                <div class="form-group row">
                                    <a class="btn bg-olive" href="<?php echo base_url($awb_charges_file_path); ?>"><i class="fa fa-download"></i>&nbsp;DOWNLOAD SAMPLE CSV FILE</a>
                                </div>
                            </div>
                        <?php } ?>
                        <!-- /.row -->
                    </div>
                </div>

                <!-- /.box-body -->
            </div>

            <div class="box box-default">

                <div class="box-header with-border">
                    <h3 class="box-title">
                        <?php echo "PURCHASE CHARGES IMPORT" ?>
                    </h3>
                </div>
                <div class="box-body">
                    <div class="row">

                        <div class="col-8">
                            <form enctype="multipart/form-data" method="POST" id="contract_form" action="<?php echo site_url('docket/import_purchase_charges/insert_data'); ?>">
                                <input type="hidden" name="submit_check" id="submit_check" value="1" />
                                <div class="col-12">
                                    <div class="form-group row">
                                        <label for="code" class="col-sm-2 col-form-label">CSV File<span class="required">*</span></label>
                                        <div class="col-sm-6">
                                            <input type="file" name="import_file" accept=".csv" />
                                        </div>
                                        <div class="col-sm-4">
                                            <button type="submit" class="btn btn-primary" onclick="set_click();">Import</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <?php
                        $awb_charges_file_path = 'media/sample_csv/docket_charges_sample.csv';
                        if (isset($awb_charges_file_path) && $awb_charges_file_path != '') { ?>
                            <div class="col-4">
                                <div class="form-group row">
                                    <a class="btn bg-olive" href="<?php echo base_url($awb_charges_file_path); ?>"><i class="fa fa-download"></i>&nbsp;DOWNLOAD SAMPLE CSV FILE</a>
                                </div>
                            </div>
                        <?php } ?>
                        <!-- /.row -->
                    </div>
                </div>

                <!-- /.box-body -->
            </div>



            <div class="box box-default">

                <div class="box-header with-border">
                    <h3 class="box-title">
                        <?php echo "SALES - PURCHASE CHARGES IMPORT" ?>
                    </h3>
                </div>
                <div class="box-body">
                    <div class="row">

                        <div class="col-8">
                            <form enctype="multipart/form-data" method="POST" id="sales_purchase_charge_form" action="<?php echo site_url('docket/import_sales_charges/import_sales_purchase_charges/insert_data'); ?>">
                                <input type="hidden" name="submit_check" id="submit_check" value="1" />
                                <div class="col-12">
                                    <div class="form-group row">
                                        <label for="code" class="col-sm-2 col-form-label">CSV File<span class="required">*</span></label>
                                        <div class="col-sm-6">
                                            <input type="file" name="import_file" accept=".csv" />
                                        </div>
                                        <div class="col-sm-4">
                                            <button type="submit" class="btn btn-primary" onclick="set_click();">Import</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <?php
                        $awb_charges_file_path = 'media/sample_csv/docket_sales_purchase_charges_sample.csv';
                        if (isset($awb_charges_file_path) && $awb_charges_file_path != '') { ?>
                            <div class="col-4">
                                <div class="form-group row">
                                    <a class="btn bg-olive" href="<?php echo base_url($awb_charges_file_path); ?>"><i class="fa fa-download"></i>&nbsp;DOWNLOAD SAMPLE CSV FILE</a>
                                </div>
                            </div>
                        <?php } ?>
                        <!-- /.row -->
                    </div>
                </div>

                <!-- /.box-body -->
            </div>

            <?php $tab_index = 1; ?>
            <div class="box box-default">

                <div class="box-header with-border">
                    <h3 class="box-title">
                        AWB BULK ATTACHMENT
                    </h3>
                </div>
                <div class="box-body">
                    <div class="row">

                        <div class="col-12">
                            <form enctype="multipart/form-data" method="POST" id="contract_form" action="<?php echo site_url('docket/import_data/upload_attachment'); ?>">

                                <div class="col-12">

                                    <div class="form-group row">
                                        <label for="code" class="col-sm-4 col-form-label">NUMBER TYPE<span class="required">*</span></label>
                                        <div class="col-sm-6">
                                            <select class="form-control" name="number_type">
                                                <option value="">SELECT...</option>
                                                <option value="1">AWB NUMBER</option>
                                                <option value="2">FORWARDING NUMBER</option>
                                                <option value="3">REFERENCE NUMBER</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group row">
                                        <label for="code" class="col-sm-4 col-form-label">File TYpe<span class="required">*</span></label>
                                        <div class="col-sm-6">
                                            <select class="form-control" name="file_type">
                                                <option value="">SELECT...</option>
                                                <option value="1">POD IMAGE</option>
                                                <option value="2">SIGNATURE</option>
                                                <option value="3">SHIPMENT IMAGES</option>
                                                <option value="4">SHIPMENT INVOICE IMAGES</option>
                                                <option value="5">CUSTOMER AWB IMAGE</option>
                                                <option value="6">VENDOR AWB IMAGE</option>
                                                <option value="7">VENDOR CHALLAN IMAGE</option>
                                                <option value="8">EWAY/DECLARATION BILL COPY</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group row">
                                        <label for="code" class="col-sm-4 col-form-label">BULK File<span class="required">*</span></label>
                                        <div class="col-sm-4">
                                            <input type="file" name="import_file[]" accept="image/*,.pdf" multiple />
                                        </div>
                                        <div class="col-sm-4" style="text-align: right;">
                                            <button type="submit" class="btn btn-primary">Import</button>
                                        </div>
                                    </div>
                                </div>

                                <span class="text-danger" style="font-size: 15px;">FILE NAME MUST BE SAME AS AWB,FORWARDING OR REFERENCE NUMBER</span>
                            </form>
                        </div>

                        <?php if (isset($import_response_attach) && $import_response_attach == 1) { ?>
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header section_head">
                                        <h5 style="margin-bottom: 3px !important;margin-top: 3px !important;line-height: 15px !important;">Your file was successfully uploaded</h5>
                                    </div>
                                    <div class="card-body">
                                        <?php
                                        if (isset($upload_error) && $upload_error != '') { ?>
                                            <span class="text-danger" style="font-size: 15px;"><?php echo $upload_error; ?></span>
                                        <?php } else { ?>
                                            <p>Total Number of FILE UPLOADED: <?php echo isset($total_csv_rec_count) ? $total_csv_rec_count : ''; ?></p>
                                            <p>Number of FILE UPLOADED in the system: <?php echo isset($insert_count) ? $insert_count : ''; ?></p>
                                            <?php $not_insert_count = $total_csv_rec_count - $insert_count; ?>
                                            <p>Number of FILE not UPLOADED in the system due to errors: <?php echo isset($not_insert_count) ? $not_insert_count : ''; ?></p>
                                            <?php

                                            if (isset($non_inserted_data) && is_array($non_inserted_data) && count($non_inserted_data) > 0) { ?>
                                                <table class='table table-bordered table-striped cf' id='odv-table'>
                                                    <thead>
                                                        <tr>
                                                            <th>Row No.</th>
                                                            <th>Upload Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        foreach ($non_inserted_data as $key => $value) { ?>
                                                            <tr>
                                                                <td><?php echo $value['count']; ?></td>
                                                                <td><?php echo $value['error']; ?></td>
                                                            </tr>
                                                        <?php  }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            <?php }
                                            ?>
                                        <?php  }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <!-- /.row -->
                    </div>
                </div>

                <!-- /.box-body -->
            </div>
        <?php } ?>


    </div>
    <div class="col-6">
        <?php
        if (isset($import_response) && $import_response == 1) {
            $this->load->view('upload_awb_success_list');
        }
        ?>
    </div>
</div>
<?php $this->load->view('plugin/autosuggest_input'); ?>
<?php $this->load->view('plugin/select_search'); ?>
<?php $this->load->view('plugin/autosuggest_input'); ?>
<?php $this->load->view('plugin/select_search'); ?>
<script type="text/javascript" src="<?php echo JS_PATH_BACKEND; ?>jquery.validate.js"></script>
<script>
    function set_click() {
        $("#submit_check").val(1);
    }
    $(document).ready(function() {

        $("#contract_form").validate({
            rules: {
                'import_file': {
                    required: true
                }
            },
            messages: {
                'import_file': {
                    required: 'FILE REQUIRED'
                }
            },
            errorElement: 'p',
            errorPlacement: function(error, element) {
                error.appendTo(element.parent());
            },
            submitHandler: function(form) {
                $("#contract_form").find(':input[type=submit]').prop('disabled', true);
                $('#loadingModal').modal('show');
                form.submit();
            }
        });
    });
</script>
<script>
    $(document).ready(function() {

        $("#contract_form").validate({
            rules: {
                'import_file': {
                    required: true
                },
                'number_type': {
                    required: true
                },
                'file_type': {
                    required: true
                }
            },
            messages: {
                'import_file': {
                    required: 'FILE REQUIRED'
                },
                'number_type': {
                    required: 'NUMBER TYPE REQUIRED'
                },
                'file_type': {
                    required: 'FILE TYPE REQUIRED'
                }
            },
            errorElement: 'p',
            errorPlacement: function(error, element) {
                error.appendTo(element.parent());
            },
            submitHandler: function(form) {
                $("#contract_form").find(':input[type=submit]').prop('disabled', true);
                $('#loadingModal').modal('show');
                form.submit();
            }
        });
    });
</script>