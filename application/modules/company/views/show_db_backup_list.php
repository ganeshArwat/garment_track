<?php $get_data = $this->input->get(); ?>

<div class="col-12">
    <?php $this->load->view('flashdata_msg'); ?>
    <!-- /.box -->

</div>
<div class="col-12">
    <!-- /.box -->

    <div class="box">
        <div class="box-header with-border">
            <!-- <span>Total No. Of Files: <?php echo isset($files) ? count($files) : 0; ?></span> -->

            <?php
            if (isset($files) && is_array($files) && count($files) > 0) { ?>
                <a tabindex="7" class="btn btn-danger" id="backup_btn" href="#"><i class="fa fa-paper" aria-hidden="true"></i>&nbsp;DOWNALOD ALL FILES</a>
            <?php  }
            ?>

        </div>
        <!-- /.box-header -->
        <div class="box-body no-padding">
            <table class="table table-striped table-responsive">
                <tbody>
                    <tr>
                        <th style="width: 10px">Sr.No.</th>
                        <th>File Name</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    $cnt = $offset + 1;

                    if (isset($files) && is_array($files) && count($files) > 0) {
                        foreach ($files as $key => $value) {
                            $file_path = base_url() . 'db_backup_daily/daily/' . $value['folder'] . '/' . $value['file_name'];

                            $relative_file_path = FCPATH . '/db_backup_daily/daily/' . $value['folder'] . '/' . $value['file_name'];
                            $file_created_date = date('Y-m-d', filemtime($relative_file_path));
                            if ($file_created_date == date('Y-m-d')) {
                    ?>
                                <tr>
                                    <td><?php echo $cnt; ?></td>
                                    <td><?php echo $value['file_name']; ?></td>
                                    <td>
                                        <a tabindex="7" class="btn btn-primary" href="<?php echo $file_path; ?>" download><i class="fa fa-download" aria-hidden="true"></i>&nbsp;DOWNLOAD</a>
                                    </td>
                                </tr>
                    <?php
                                $cnt++;
                            }
                        }
                    }
                    ?>

                </tbody>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
</div>
<script>
    $(document).ready(function() {

        $("#backup_btn").click(function() {

            <?php
            if (isset($files) && is_array($files) && count($files) > 0) {
                foreach ($files as $key => $value) {
                    $file_path = base_url() . 'db_backup_daily/daily/' . $value['folder'] . '/' . $value['file_name'];
            ?>
                    var backup_url = '<?php echo $file_path; ?>';
                    window.open(backup_url);
            <?php   }
            }
            ?>


        });
    });
</script>