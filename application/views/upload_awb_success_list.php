<div class="col-md-12">

    <div class="card">
        <div class="card-header section_head">
            <h5 style="margin-bottom: 3px !important;margin-top: 3px !important;line-height: 15px !important;">UPLOAD RESPONSE</h5>
        </div>
        <div class="card-body">
            <p>Total Number of records in CSV file: <?php echo isset($total_csv_rec_count) ? $total_csv_rec_count : ''; ?></p>
            <p>Number of records imported in the system: <?php echo isset($insert_count) ? $insert_count : ''; ?></p>
            <?php $not_insert_count = $total_csv_rec_count - $insert_count; ?>
            <p>Number of records not imported in the system due to errors: <?php echo isset($not_insert_count) ? $not_insert_count : ''; ?></p>
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
        </div>
    </div>
</div>
<script type="text/javascript" src='https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js'></script>
<script>
    function set_click() {
        $("#submit_check").val(1);
    }
    $(document).ready(function() {
        $.extend($.fn.dataTableExt.oStdClasses, {
            "sFilterInput": "form-control",
            "sLengthSelect": "form-control"
        });

        var table = $('#odv-table').DataTable({
            'language': {
                search: "_INPUT_",
                sLengthMenu: "_MENU_",
                searchPlaceholder: "Search..."
            }
        });
    });
</script>

<?php $this->load->view('plugin/autosuggest_input'); ?>
<?php $this->load->view('plugin/select_search'); ?>
<script type="text/javascript" src="<?php echo JS_PATH_BACKEND; ?>jquery.validate.js"></script>
<script>
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