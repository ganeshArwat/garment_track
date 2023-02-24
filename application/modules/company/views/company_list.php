<?php $get_data = $this->input->get();
$session_data = $this->session->userdata('admin_user');
?>

<div class="col-12">
    <?php $this->load->view('flashdata_msg'); ?>
    <!-- /.box -->

    <div class="box">
        <div class="box-body no-padding">
            <form id="demo-form2" class="form-horizontal" action="<?php echo site_url('company/advance_search') ?>" method="POST">
                <div class="row">
                    <div class="col-sm-3">
                        <!-- text input -->
                        <!-- <div class="form-group"> -->
                        <!-- <label>Company Name</label> -->
                        <!-- <input type="hidden" value="<?php echo isset($get_data['company_id']) ? $get_data['company_id'] : ''; ?>" id="company_id" name="company_id" /> -->
                        <!-- <input placeholder="Search" class="form-control me-2" id="company_search_name" type="text" value="<?php echo isset($get_data['company_id']) && isset($all_company[$get_data['company_id']]) ? $all_company[$get_data['company_id']]['company_name'] : ''; ?>" /> -->
                        <!-- <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" /> -->
                        <!-- <span class="input-group-text"><i class="tf-icons bx bx-search"></i></span> -->
                        <!-- <input type="text" class="form-control" placeholder="Search..." /> -->

                        <!-- </div> -->
                        <div class="input-group" style="padding-top:23px;">
                            <span class="input-group-text"><i class="tf-icons bx bx-search"></i></span>
                            <input type="text" class="form-control" placeholder="Search..." />
                        </div>
                    </div>

                    <div class="col-sm-9">
                        <!-- text input -->
                        <!-- <button id="btn-search" class="btn btn-outline-primary" tabindex="7" type="submit">Search</button> -->

                        <div class="form-group" style="padding-top:23px; padding-bottom: 11px;margin-left:437px">

                            <a tabindex="7" class="btn btn-info" title="View All Company" href="<?php echo site_url('company/show_list'); ?>"><i aria-hidden="true"></i>&nbsp;RESET</a>
                            <a tabindex="7" class="btn btn-danger" title="Add Company" href="<?php echo site_url('company/add'); ?>"><i aria-hidden="true"></i>&nbsp;Add</a>

                            <!-- <a tabindex="7" class="btn btn-danger" id="backup_btn" href="#"><i class="fa fa-paper" aria-hidden="true"></i>&nbsp;BACKUP ALL COMPANY</a> -->

                            <a tabindex="7" class="btn btn-primary" href="<?php echo site_url('company/download_backup') ?>"><i class="fa fa-list" aria-hidden="true"></i>&nbsp;ALL DATABASE BACKUP LIST</a>
                            <a tabindex="7" class="btn btn-primary" href="<?php echo site_url('script/backup_media') ?>"><i class="fa fa-file-pdf-o" aria-hidden="true"></i>&nbsp;MEDIA BACKUP</a>
                            <?php if ($session_data["email"] == "ashish@itdservices.in" && $session_data["is_restrict"] == 1) { ?>
                                <a tabindex="7" class="btn btn-primary" href="<?php echo site_url('company_dashboard/show_dashboard_data') ?>"><i class="fa fa-file-pdf-o" aria-hidden="true"></i>&nbsp;COMPANY REPORT</a>
                            <?php } ?>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="col-12">
    <!-- /.box -->
    <div class="card-header">
        <div class="box">
            <span style="font-weight:bold">Total No. Of Company: <?php echo isset($total) ? $total : 0; ?></span>
            <span style="font-weight:bold">Total Display: <?php echo isset($list) ? count($list) : 0; ?></span>
            <div class="float-right">
                <?php echo $this->pagination->create_links(); ?>
            </div>
        </div>
    </div>

    <div class="box">

        <!-- /.box-header -->
        <div class="card">

            <div class="table-responsive text-nowrap">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th style="width: 10px">Sr.No.</th>
                            <th>Name</th>
                            <!-- <th>Code</th> -->
                            <th>No. Of Logins</th>
                            <th>Expiry Date</th>
                            <th>SEF URL</th>
                            <th>Created Date</th>
                            <th style="text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <?php
                        $cnt = $offset + 1;

                        if (isset($list) && is_array($list) && count($list) > 0) {
                            foreach ($list as $key => $value) { ?>
                                <tr class="<?php echo $value['status'] == 2 ? 'inactive_row' : ''; ?>">
                                    <td><?php echo $cnt; ?></td>
                                    <td><?php echo $value['company_name']; ?></td>
                                    <!-- <td><?php echo $value['company_code']; ?></td> -->
                                    <td><?php echo $value['login_count']; ?></td>
                                    <td><?php echo get_format_date(DATE_FORMAT, ($value['expiry_date'])); ?></td>
                                    <td><?php echo $value['sef_url']; ?></td>

                                    <td><?php echo get_format_date(DATETIME_FORMAT, $value['created_date']); ?></td>

                                    <td>
                                        <a href="<?php echo site_url('company/edit/' . $value['id']) ?>" title="Update Company" class="btn btn-secondary">Edit</a>

                                        <a target="blank" href="<?php echo site_url('cron/droplet_api/db_backup/' . $value['id'] . '?cron_company=' . $value['id']) ?>" title="BACKUP Company" class="btn btn-info">Backup Database</a>

                                    </td>
                                </tr>
                        <?php
                                $cnt++;
                            }
                        }
                        ?>

                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
</div>
<?php $this->load->view('plugin/autosuggest_input'); ?>
<script>
    $(document).ready(function() {
        autosuggest_input('company_search_name', 'company', 'company_id');
        //search_select('company');

        $("#backup_btn").click(function() {

            <?php
            if (isset($list) && is_array($list) && count($list) > 0) {
                foreach ($list as $key => $value) { ?>
                    var backup_url = '<?php echo site_url('cron/droplet_api/db_backup/' . $value['id'] . '?cron_company=' . $value['id']) ?>';
                    window.open(backup_url, '_blank');
                    //window.location = backup_url;
            <?php   }
            }
            ?>


        });
    });
</script>