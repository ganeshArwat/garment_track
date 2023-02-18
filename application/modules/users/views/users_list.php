<?php $get_data = $this->input->get();

$sessiondata = $this->session->userdata('admin_user');
$super_admin_role = $this->config->item('super_admin');
$admin_role_name = $this->config->item('admin_role_name');

$sort_order = 'asc';
if (isset($get_data['order'])) {
  if ($get_data['order'] == 'desc') {
    $sort_order = 'asc';
  } else {
    $sort_order = 'desc';
  }
}

$up_or_down = str_replace(array('asc', 'desc'), array('down', 'up'), $sort_order);
$column = isset($get_data['column']) ? $get_data['column'] : '';

$currentURL = current_url();
$params_data = $get_data;
if (isset($params_data['order'])) {
  unset($params_data['order']);
  unset($params_data['column']);
}
?>
<div class="col-12">
  <?php $this->load->view('flashdata_msg'); ?>
  <!-- /.box -->

  <div class="box">
    <div class="box-body no-padding">
      <form id="demo-form2" class="form-horizontal" action="<?php echo site_url('users/advance_search') ?>" method="POST">
        <input type="hidden" name="hidden_type" id="hiddenid">
        <div class="row">
          <div class="col-sm-3">
            <!-- text input -->
            <div class="form-group">
              <label>Name:</label>
              <input type="hidden" value="<?php echo isset($get_data['name']) ? $get_data['name'] : '';
                                          ?>" id="name_id" name="name_id" />
              <input class="form-control" id="autosuggest_user_name" type="text" name="name" value="<?php echo isset($get_data['name']) ? $get_data['name'] : ''; ?>" autocomplete="nope" />
              <!-- <input type="text" class="form-control" name="name" autocomplete="off" value="<?php echo isset($get_data['name']) ? $get_data['name'] : ''; ?>"> -->
            </div>
          </div>
          <?php if (isset($session_data['is_restrict']) && $session_data['is_restrict'] == 1) { ?>
            <div class="col-sm-3">
              <div class="form-group">
                <label>Company:</label>
                <select class="form-control" name="company_id" id="company_id" tabindex="<?php $tab_index++; ?>">
                  <option value="">SELECT...</option>
                  <?php
                  if (isset($all_company) && is_array($all_company) && count($all_company) > 0) {
                    foreach ($all_company as $ckey => $cvalue) { ?>
                      <option value="<?php echo $cvalue['id'] ?>" <?php echo isset($get_data['company_id']) && $get_data['company_id'] == $cvalue['id'] ? 'selected' : ''; ?>><?php echo strtoupper($cvalue['company_name']); ?></option>
                  <?php }
                  }
                  ?>
                </select>
              </div>
            </div>
          <?php
          }
          ?>
          <div class="col-sm-3">
            <div class="form-group">
              <label>Role:</label>
              <select class="form-control" name="role_id" id="role_id" tabindex="<?php $tab_index++; ?>">
                <option value="">SELECT...</option>
                <?php
                if (isset($all_role) && is_array($all_role) && count($all_role) > 0) {
                  foreach ($all_role as $ckey => $cvalue) { ?>
                    <option value="<?php echo $cvalue['id'] ?>" <?php echo isset($get_data['role_id']) && $get_data['role_id'] == $cvalue['id'] ? 'selected' : ''; ?>><?php echo strtoupper($cvalue['name']); ?></option>
                <?php }
                }
                ?>
              </select>
            </div>
          </div>
          <div class="col-sm-3">
            <!-- text input -->
            <div class="form-group">
              <label>Email ID:</label>
              <input type="text" class="form-control" name="email" autocomplete="off" value="<?php echo isset($get_data['email']) ? $get_data['email'] : ''; ?>">
            </div>
          </div>
          <div class="col-sm-9">
            <!-- text input -->
            <div class="form-group mt-4">
              <input id="btn-search" tabindex="4" type="submit" class="btn bg-olive" value="Search" onclick="$('#hiddenid').val('');">
              <a tabindex="6" class="btn btn-info" title="View All Docket" href="<?php echo site_url('users/show_list'); ?>"><i class="fa fa-newspaper-o" aria-hidden="true"></i>&nbsp;RESET</a>
              <!-- <a tabindex="7" class="btn btn-danger" title="Add Docket" href="<?php echo site_url('docket/add'); ?>"><i class="fa fa-plus-square" aria-hidden="true"></i>&nbsp;Add</a> -->
              <a tabindex="8" class="btn btn-export" title="EXPORT <?php echo $heading; ?>" id="download_report"><i class="fa fa-download" aria-hidden="true"></i>&nbsp;EXPORT</a>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
  <!-- /.box -->
  <?php $sessiondata = $this->session->userdata('admin_user');
  if (isset($sessiondata['is_restrict']) && $sessiondata['is_restrict'] == 2) { ?>

    <a tabindex="8" class="btn btn-secondary" title="IMPORT <?php echo $heading; ?>" href="<?php echo site_url('users/import_data/show_form') ?>"><i class="fa fa-upload" aria-hidden="true"></i>&nbsp;IMPORT</a>
  <?php } ?>
  <div class="box">
    <div class="box-header with-border">
      <span>Total No. Of Users: <?php echo isset($total) ? $total : 0; ?></span>
      <span>Total Display: <?php echo isset($list) ? count($list) : 0; ?></span>
      <div class="float-right">
        <?php echo $this->pagination->create_links(); ?>
      </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body no-padding">
      <table class="table table-striped table-responsive">
        <tbody>
          <tr>
            <th style="width: 10px">Sr.No.</th>
            <th><a href="<?php echo $currentURL; ?>?column=u.name&order=<?php echo $sort_order . (count($params_data) > 0 ? '&' . http_build_query($params_data) : ''); ?>">Name&nbsp;<i class="fas fa-sort<?php echo $column == 'u.name' ? '-' . $up_or_down : ''; ?>"></i></a></th>
            <!-- <th>User Code</th> -->
            <th><a href="<?php echo $currentURL; ?>?column=u.email&order=<?php echo $sort_order . (count($params_data) > 0 ? '&' . http_build_query($params_data) : ''); ?>">Email ID&nbsp;<i class="fas fa-sort<?php echo $column == 'u.email' ? '-' . $up_or_down : ''; ?>"></i></a></th>
            <?php if (isset($sessiondata['is_restrict']) && $sessiondata['is_restrict'] == 1) { ?>
              <th>Company</th>
            <?php } ?>
            <th>Last Active</th>
            <th><a href="<?php echo $currentURL; ?>?column=ro.name&order=<?php echo $sort_order . (count($params_data) > 0 ? '&' . http_build_query($params_data) : ''); ?>">Role&nbsp;<i class="fas fa-sort<?php echo $column == 'ro.name' ? '-' . $up_or_down : ''; ?>"></i></a></th>
            <th><a href="<?php echo $currentURL; ?>?column=u.created_date&order=<?php echo $sort_order . (count($params_data) > 0 ? '&' . http_build_query($params_data) : ''); ?>">Created Date&nbsp;<i class="fas fa-sort<?php echo $column == 'u.created_date' ? '-' . $up_or_down : ''; ?>"></i></a></th>
            <th>Action</th>
          </tr>
          <?php
          $cnt = $offset + 1;
          if (isset($list) && is_array($list) && count($list) > 0) {
            foreach ($list as $key => $value) { ?>
              <tr class="<?php echo $value['status'] == 2 ? 'inactive_row' : ''; ?>">
                <td><?php echo $cnt; ?></td>
                <td><?php echo $value['name']; ?></td>
                <!-- <td><?php echo $value['user_code']; ?></td> -->
                <td><?php echo $value['email']; ?></td>
                <?php if (isset($sessiondata['is_restrict']) && $sessiondata['is_restrict'] == 1) { ?>
                  <td><?php echo isset($all_company[$value['company_id']]) ? $all_company[$value['company_id']]['company_name'] : ''; ?></td>
                <?php } ?>
                <td><?php echo $value['last_active']; ?></td>
                <td><?php echo isset($all_role[$value['role']]) ? ucwords($all_role[$value['role']]['name']) : ''; ?></td>
                <td><?php echo get_format_date(DATETIME_FORMAT, $value['created_date']); ?></td>
                <td>
                  <?php
                  if (in_array("update_user", $sessiondata['user_permission']) || !in_array($sessiondata['type'], $admin_role_name) || $sessiondata['role'] == $super_admin_role || (isset($session_data['is_restrict']) && $session_data['is_restrict'] == 1)) {
                  ?>
                    <a href="<?php echo site_url('users/edit/' . $value['id']) ?>" title="Update Users" class="edit_button">Edit</a>
                  <?php
                  }
                  if (in_array("delete_user", $sessiondata['user_permission']) || !in_array($sessiondata['type'], $admin_role_name) || $sessiondata['role'] == $super_admin_role || (isset($session_data['is_restrict']) && $session_data['is_restrict'] == 1)) {
                  ?>
                    <a onclick="ask_confirmation('Are you sure you want to delete this User?','<?php echo site_url('users/delete/' . $value['id']) ?>')" href="#" title="Delete Users" class="delete_button">Delete</a>
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
<?php
$autosuggest_arr = array(
  1 => array('input_id' => 'autosuggest_user_name', 'module' => 'admin_user', 'hidden_id' => 'name_id', 2, 'name'),
  // 2 => array('input_id' => 'autosuggest_user_email', 'module' => 'user', 'hidden_id' => 'email_id', 2, 'email'),
  // 3 => array('input_id' => 'autosuggest_role', 'module' => 'user', 'hidden_id' => 'role_id'),
);
?>
<script>
  $(document).ready(function() {
    $("#download_report").click(function() {
      $('#hiddenid').val('downloadreport');
      $('#demo-form2').submit();
    });
    <?php foreach ($autosuggest_arr as $key => $value) { ?>
      autosuggest_input('<?php echo $value['input_id'] ?>', '<?php echo $value['module'] ?>', '<?php echo $value['hidden_id'] ?>');
    <?php } ?>
  });
</script>