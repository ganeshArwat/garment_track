<?php $get_data = $this->input->get();
$session_data = $this->session->userdata('admin_user');
$super_admin_role = $this->config->item('super_admin');
$admin_role_name = $this->config->item('admin_role_name');
?>
<div class="col-12">
    <?php $this->load->view('flashdata_msg'); ?>
    <!-- /.box -->

    <div class="box">
        <div class="box-body no-padding">
            <form id="demo-form2" class="form-horizontal" action="<?php echo site_url('company_master/advance_search') ?>" method="POST">
                <div class="row">
                    <div class="col-sm-3">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Name</label>
                            <input class="form-control" type="text" value="<?php echo isset($get_data['com_name'])  ? $get_data['com_name'] : ''; ?>" />
                        </div>
                    </div>


                    <div class="col-sm-3">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Created Date:</label>
                            <div class="row">
                                <div class="col-6" style="padding-right: 0px;"> <input type="date" class="form-control pull-right datepicker_text" name="created_min" data-inputmask="'alias': 'dd/mm/yyyy'" value="<?php echo isset($get_data['created_min']) && $get_data['created_min'] != '' ? date(DATE_INPUT_FORMAT, strtotime($get_data['created_min'])) : ''; ?>"></div>
                                <div class="col-6" style="padding-left: 0px;"> <input type="date" class="form-control pull-right datepicker_text" name="created_max" data-inputmask="'alias': 'dd/mm/yyyy'" value="<?php echo isset($get_data['created_max']) && $get_data['created_max'] != '' ? date(DATE_INPUT_FORMAT, strtotime($get_data['created_max'])) : ''; ?>"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>STATUS</label>
                            <select class="form-control" name="status" id="status">
                                <option value="">SELECT...</option>
                                <option value="1" <?php echo isset($get_data['status']) && $get_data['status'] == 1 ? 'selected' : ''; ?>>ACTIVE</option>
                                <option value="2" <?php echo isset($get_data['status']) && $get_data['status'] == 2 ? 'selected' : ''; ?>>INACTIVE</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 mt-3">
                        <!-- text input -->
                        <div class="form-group">
                            <input id="btn-search" tabindex="4" type="submit" class="btn bg-olive" value="Search">
                            <a tabindex="6" class="btn btn-info" title="View All Item" href="<?php echo site_url('company_master/show_list'); ?>"><i class="fa fa-newspaper-o" aria-hidden="true"></i>&nbsp;RESET</a>
                            <?php
                            if (in_array("create_company_master", $session_data['user_permission']) || !in_array($session_data['type'], $admin_role_name) || $session_data['role'] == $super_admin_role) {
                                if ($session_data['email'] == 'virag@itdservices.in') {
                            ?>
                                    <a tabindex="7" class="btn btn-danger" title="Add Company" href="<?php echo site_url('company_master/add'); ?>"><i class="fa fa-plus-square" aria-hidden="true"></i>&nbsp;Add</a>
                            <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>


</div>
<div class="col-12">

    <div class="box">
        <div class="box-header with-border">
            <span>Total No. Of <?php echo $heading; ?>: <?php echo isset($total) ? $total : 0; ?></span>
            <span>Total Display: <?php echo isset($list) ? count($list) : 0; ?></span>
            <div class="float-right">
                <?php echo $this->pagination->create_links(); ?>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body no-padding table-responsive" style="overflow-x: scroll;">
            <table class="table table-striped table-responsive">
                <tbody>
                    <tr>
                        <th style="width: 10px">Sr.No.</th>
                        <th>Code</th>
                        <th>Name</th>
                        <th>EMAIL</th>
                        <th>CONTACT NO</th>
                        <th>WEBSITE</th>
                        <th>CITY</th>
                        <th>STATE</th>
                        <th>PAN NUMBER</th>
                        <th>CIN NUMBER</th>
                        <th>GST NUMBER</th>
                        <th>SAC CODE</th>
                        <th>Created Date</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    $cnt = $offset + 1;
                    if (isset($list) && is_array($list) && count($list) > 0) {
                        foreach ($list as $key => $value) { ?>
                            <tr class="<?php echo $value['status'] == 2 ? 'inactive_row' : ''; ?>" id="result_row_<?php echo $value['id']; ?>">
                                <td><?php echo $cnt; ?></td>
                                <td class="upper-show">
                                    <a class="link_highlight" href="<?php echo site_url('company_master/edit/' . $value['id']) ?>"><?php echo $value['code']; ?></a>
                                </td>
                                <td><?php echo $value['name']; ?></td>
                                <td><?php echo $value['email_id']; ?></td>
                                <td><?php echo $value['contact_no']; ?></td>
                                <td><?php echo $value['website']; ?></td>
                                <td><?php echo $value['city']; ?></td>
                                <td><?php echo $value['state']; ?></td>
                                <td><?php echo $value['pan_number']; ?></td>
                                <td><?php echo $value['cin_number']; ?></td>
                                <td><?php echo $value['gst_number']; ?></td>
                                <td><?php echo $value['sac_code']; ?></td>
                                <td><?php echo get_format_date(DATETIME_FORMAT, $value['created_date']); ?></td>
                                <td>
                                    <?php
                                    if (in_array("update_company_master", $session_data['user_permission']) || !in_array($session_data['type'], $admin_role_name) || $session_data['role'] == $super_admin_role) {
                                    ?>
                                        <a href="<?php echo site_url('company_master/edit/' . $value['id']) ?>" title="Update <?php echo $heading; ?>" class="edit_button">Edit</a>
                                    <?php
                                    }
                                    if (in_array("delete_company_master", $session_data['user_permission']) || !in_array($session_data['type'], $admin_role_name) || $session_data['role'] == $super_admin_role) {
                                    ?>
                                        <a onclick="delete_data('<?php echo $value['id']; ?>','company_master','<?php echo $heading; ?>');" href="#" title="Delete Company" class="delete_button">Delete</a>
                                    <?php
                                    }
                                    ?>
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
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
</div>


<?php $this->load->view('plugin/autosuggest_input'); ?>
<?php $this->load->view('plugin/datepicker'); ?>