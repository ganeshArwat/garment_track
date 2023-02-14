<?php $tab_index = 1; ?>
<div class="box box-default">

    <div class="box-header with-border">
        <h3 class="box-title">
            <?php echo $heading; ?>
        </h3>
    </div>
    <div class="box-body">
        <div class="row">
            <?php $this->load->view('flashdata_msg'); ?>
            <div class="col-6">
                <form enctype="multipart/form-data" method="POST" id="contract_form" action="<?php echo site_url('mirgation/' . $module_name . '/insert_data'); ?>">


                    <div class="col-12">
                        <div class="form-group row">
                            <label for="code" class="col-sm-2 col-form-label">CSV File<span class="required">*</span></label>
                            <div class="col-sm-10">
                                <input type="file" name="import_file" accept=".csv" />
                                <button type="submit" class="btn btn-primary pull-right">Import</button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>


            <!-- /.row -->
        </div>
    </div>

    <!-- /.box-body -->
</div>
<?php $this->load->view('plugin/autosuggest_input'); ?>
<?php $this->load->view('plugin/select_search'); ?>
<script type="text/javascript" src="<?php echo JS_PATH_BACKEND; ?>jquery.validate.js"></script>
<script>
    $(document).ready(function() {

        $.validator.addMethod("alphanumeric", function(value, element) {
            return this.optional(element) || /^[a-zA-Z0-9-_\s]+$/i.test(value);
        }, "Use only alphabet,number,hyphen(-),underscore(_) and space");

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
<?php $this->load->view('plugin/datepicker'); ?>