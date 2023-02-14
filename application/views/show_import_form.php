<?php $tab_index = 1; ?>
<div class="box box-default">

    <div class="box-header with-border">
        <h3 class="box-title">
            <?php echo $heading; ?>
        </h3>
    </div>
    <div class="box-body">
        <div class="row">

            <div class="col-6">
                <form enctype="multipart/form-data" method="POST" id="contract_form" action="<?php echo site_url($file_upload_action); ?>">
                    <input type="hidden" name="submit_check" id="submit_check" value="2" />
                    <div class="col-12">
                        <div class="form-group row">
                            <label for="code" class="col-sm-2 col-form-label">CSV File<span class="required">*</span></label>
                            <div class="col-sm-4">
                                <input type="file" name="import_file" accept=".csv" />
                            </div>
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-primary" onclick="set_click();">Import</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <?php if (isset($sample_file_path) && $sample_file_path != '') { ?>
                <div class="col-6">
                    <div class="form-group row">
                        <a class="btn bg-olive" href="<?php echo base_url($sample_file_path); ?>"><i class="fa fa-download"></i>&nbsp;DOWNLOAD SAMPLE CSV FILE</a>
                    </div>
                </div>
            <?php } ?>


            <?php
            if (isset($import_response) && $import_response == 1) {
                $this->load->view('upload_success_list');
            }
            ?>
            <!-- /.row -->
        </div>
    </div>

    <!-- /.box-body -->
</div>
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