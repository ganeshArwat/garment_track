<?php $get_data = $this->input->get();
$session_data = $this->session->userdata('admin_user');
$super_admin_role = $this->config->item('super_admin');
$admin_role_name = $this->config->item('admin_role_name');
?>

<div class="row">
    <div class="mb-3 col-md-9">
        <h3 class="px-2">
            COMPANY LIST
        </h3>
    </div>
    <div class="mb-3 col-md-3"> 
        <a href="<?php echo site_url("company_master/add"); ?>" class="btn btn-primary me-2">
            ADD COMPANY
        </a>

        
        <button type="reset" class="btn btn-outline-secondary" onclick="history.back()" href="javascript:void(0);">BACK</button>
    </div>
</div>
<div class="card">
    <div class="table-responsive text-nowrap">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="text-center">Sr.No.</th>
                    <th class="text-center">Code</th>
                    <th class="text-center">Name</th>
                    <th class="text-center">EMAIL</th>
                    <th class="text-center">CONTACT NO</th>
                    <th class="text-center">WEBSITE</th>
                    <th class="text-center">CITY</th>
                    <th class="text-center">STATE</th>
                    <th class="text-center">Created Date</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                <?php
                $cnt = $offset + 1;
                if (isset($list) && is_array($list) && count($list) > 0) {
                    foreach ($list as $key => $value) { ?>
                        <tr class="<?php echo $value['status'] == 2 ? 'inactive_row' : ''; ?>" id="result_row_<?php echo $value['id']; ?>">
                            <td class="text-center"><?php echo $cnt; ?></td>
                            <td class="text-center" class="upper-show">
                                <a class="link_highlight" href="<?php echo site_url('company_master/edit/' . $value['id']) ?>"><?php echo $value['code']; ?></a>
                            </td>
                            <td class="text-center"><?php echo $value['name']; ?></td>
                            <td class="text-center"><?php echo $value['email_id']; ?></td>
                            <td class="text-center"><?php echo $value['contact_no']; ?></td>
                            <td class="text-center"><?php echo $value['website']; ?></td>
                            <td class="text-center"><?php echo $value['city']; ?></td>
                            <td class="text-center"><?php echo $value['state']; ?></td>
                            <td class="text-center"><?php echo get_format_date(DATETIME_FORMAT, $value['created_date']); ?></td>
                            <td class="text-center">
                                <?php
                                if (in_array("update_company_master", $session_data['user_permission']) || !in_array($session_data['type'], $admin_role_name) || $session_data['role'] == $super_admin_role) {
                                ?>
                                    <a href="<?php echo site_url('company_master/edit/' . $value['id']) ?>" title="Update <?php echo $heading; ?>" class="btn btn-secondary">Edit</a>
                                <?php
                                }
                                if (in_array("delete_company_master", $session_data['user_permission']) || !in_array($session_data['type'], $admin_role_name) || $session_data['role'] == $super_admin_role) {
                                ?>
                                    <a onclick="delete_data('<?php echo $value['id']; ?>','company_master','<?php echo $heading; ?>');" href="#" title="Delete Company" class="btn btn-warning">Delete</a>
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
</div>



<?php $this->load->view('plugin/autosuggest_input'); ?>
<?php $this->load->view('plugin/datepicker'); ?>