<?php $tab_index = 1; ?>
<style>
    .permission_table_checkbox label:before {
        border: 2px solid red;
    }

    .inp_style input {
        position: relative !important;
        left: 0 !important;
    }
</style>
<div class="box box-default">
    <form enctype="multipart/form-data" method="POST" id="user_form" action="<?php echo  isset($mode) && $mode == 'update' ?  site_url('users/update') : site_url('users/insert') ?>">

        <div class="box-header with-border">
            <h3 class="box-title">
                <?php if ($mode == 'update') { ?>
                    Edit User - <?php echo $users['name']; ?>
                <?php } else { ?>
                    Add User
                <?php } ?>
            </h3>
            <button type="submit" class="btn btn-primary pull-right submit_btn">Save</button>
        </div>
        <!-- /.box-header -->
        <input type="hidden" value="<?php echo isset($users['id']) ? $users['id'] : ''; ?>" name="user_id">
        <div class="box-body">
            <?php $this->load->view('flashdata_msg'); ?>
            <nav>
                <div class="nav nav-tabs nav-justified user_nav" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="true">
                        <i class="fa fa-user"></i>&nbsp;Profile
                    </a>
                    <a class="nav-item nav-link" id="nav-hubs-tab" data-toggle="tab" href="#nav-hubs" role="tab" aria-controls="nav-hubs" aria-selected="false">
                        <i class="fa fa-home"></i>&nbsp;Hubs
                    </a>
                    <a class="nav-item nav-link" id="nav-permissions-tab" data-toggle="tab" href="#nav-permissions" role="tab" aria-controls="nav-permissions" aria-selected="false">
                        <i class="fa fa-home"></i>&nbsp;Permissions
                    </a>
                </div>
            </nav>

            <div class="tab-content pt-2" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                    <div class="row">
                        <div class="col-12">
                            <?php $sessiondata = $this->session->userdata('admin_user'); ?>

                            <div class="col-6" <?php echo isset($sessiondata['is_restrict']) && $sessiondata['is_restrict'] == 1 ? '' : 'style="display:none;"'; ?>>
                                <div class="form-group row">
                                    <label for="company_id" class="col-sm-2 col-form-label">Company<span class="required">*</span></label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="user[company_id]" id="company_id" tabindex="<?php $tab_index++; ?>">
                                            <option value="">Select Company</option>
                                            <?php
                                            if (isset($all_company) && is_array($all_company) && count($all_company) > 0) {
                                                foreach ($all_company as $ckey => $cvalue) { ?>
                                                    <option data-value="<?php echo date('m/d/Y', strtotime($cvalue['expiry_date'])); ?>" value="<?php echo $cvalue['id'] ?>" <?php echo isset($users['company_id']) && $users['company_id'] == $cvalue['id'] ? 'selected' : ''; ?>><?php echo $cvalue['company_name'] ?></option>
                                            <?php }
                                            }
                                            ?>

                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group row">
                                    <label for="full_name" class="col-sm-2 col-form-label">Name<span class="required">*</span></label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="text" value="<?php echo isset($users['name']) ? $users['name'] : ''; ?>" id="full_name" name="user[name]" tabindex="<?php $tab_index++; ?>" autocomplete="off">
                                    </div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group row">
                                    <label for="email_id" class="col-sm-2 col-form-label">Email ID<span class="required">*</span></label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="text" value="<?php echo isset($users['email']) ? $users['email'] : ''; ?>" id="email_id" name="user[email]" tabindex="<?php $tab_index++; ?>" autocomplete="off" onblur="check_itd_admin();">
                                    </div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group row">
                                    <label for="contact_no" class="col-sm-2 col-form-label">Contact No.<span class="required">*</span></label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="text" value="<?php echo isset($users['contactno']) ? $users['contactno'] : ''; ?>" id="contact_no" name="user[contactno]" tabindex="<?php $tab_index++; ?>" autocomplete="off">
                                    </div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group row">
                                    <label for="role_id" class="col-sm-2 col-form-label">Role<span class="required">*</span></label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="user[role]" id="role_id" tabindex="<?php $tab_index++; ?>">
                                            <option value="">Select Role</option>
                                            <?php
                                            if (isset($all_role) && is_array($all_role) && count($all_role) > 0) {
                                                foreach ($all_role as $key => $value) { ?>
                                                    <option value="<?php echo $value['id'] ?>" <?php echo isset($users['role']) && $users['role'] == $value['id'] ? 'selected' : ''; ?>><?php echo strtoupper($value['name']) ?></option>
                                            <?php }
                                            }
                                            ?>

                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group row">
                                    <label for="contact_no" class="col-sm-2 col-form-label">Birth date</label>
                                    <div class="col-sm-10">
                                    <input  type="date"  id="birth_date" class="form-control datepicker_text" data-inputmask="'alias': 'dd/mm/yyyy'" placeholder="dd/mm/yyyy" name="user[birth_date]" value="<?php echo isset($users['birth_date']) && $users['birth_date'] != '1970-01-01' && $users['birth_date'] != '0000-00-00' ? get_format_date(DATE_INPUT_FORMAT, ($users['birth_date'])) : ''; ?>" autocomplete="nope">
                                    </div>
                                </div>
                            </div>


                            <div class="col-6">
                                <div class="form-group row">
                                    <label for="contact_no" class="col-sm-2 col-form-label">Joing date</label>
                                    <div class="col-sm-10">
                                    <input  type="date"  id="joining_date" class="form-control datepicker_text" data-inputmask="'alias': 'dd/mm/yyyy'" placeholder="dd/mm/yyyy" name="user[joining_date]" value="<?php echo isset($users['joining_date']) && $users['joining_date'] != '1970-01-01' && $users['joining_date'] != '0000-00-00' ? get_format_date(DATE_INPUT_FORMAT, ($users['joining_date'])) : ''; ?>" autocomplete="nope">
                                    </div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group row">
                                    <label for="role_id" class="col-sm-2 col-form-label">KYC TYPE</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="user[gsting_type]" id="gsting_type_id" tabindex="<?php $tab_index++; ?>">
                                            <option value="">Select KYC TYPE</option>
                                            <?php
                                            if (isset($all_doc_type) && is_array($all_doc_type) && count($all_doc_type) > 0) {
                                                foreach ($all_doc_type as $key => $value) { ?>
                                                    <option value="<?php echo $value['id'] ?>" <?php echo isset($users['gsting_type']) && $users['gsting_type'] == $value['id'] ? 'selected' : ''; ?>><?php echo strtoupper($value['name']) ?></option>
                                            <?php }
                                            }
                                            ?>

                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group row">
                                    <label for="contact_no" class="col-sm-2 col-form-label">Kyc No.</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="text" value="<?php echo isset($users['gstin_no']) ? $users['gstin_no'] : ''; ?>" id="gstin_no" name="user[gstin_no]" tabindex="<?php $tab_index++; ?>" autocomplete="off">
                                    </div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group row">
                                    <label for="cust_name" class="col-sm-2 col-form-label">KYC DOC</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="file" name="doc_path" accept="image/*">
                                    </div>
                                    <div class="col-sm-3">
                                        <?php if (isset($users['doc_path']) && $users['doc_path'] != '' && file_exists($users['doc_path'])) { ?>
                                            <a class="file_button" target="blank" href="<?php echo base_url($users['doc_path']); ?>">VIEW FILE</a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group row">
                                    <label for="cust_name" class="col-sm-2 col-form-label">Profile Photo</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="file" name="profile_file" accept="image/*">
                                    </div>
                                    <div class="col-sm-3">
                                        <?php if (isset($users['profile_file']) && $users['profile_file'] != '' && file_exists($users['profile_file'])) { ?>
                                            <a class="file_button" target="blank" href="<?php echo base_url($users['profile_file']); ?>">VIEW FILE</a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>

                            <?php
                            $pass_stye = '';
                            if (isset($mode) && $mode == 'update') {
                                $pass_stye = 'style="display:none;"'; ?>
                                <div class="col-6">
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"></label>
                                        <div class="col-sm-10">
                                            <div class="checkbox">
                                                <input type="checkbox" id="basic_checkbox_1" name="check_password" value="1">
                                                <label for="basic_checkbox_1">Change Password</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>

                            <div class="col-6 password_div" <?php echo $pass_stye; ?>>
                                <div class="form-group row">
                                    <label for="password" class="col-sm-2 col-form-label">Password<span class="required">*</span></label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="password" value="" id="password" name="user[password]" tabindex="<?php $tab_index++; ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 password_div" <?php echo $pass_stye; ?>>
                                <div class="form-group row">
                                    <label for="cpassword" class="col-sm-2 col-form-label">Confirm Password<span class="required">*</span></label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="password" value="" id="cpassword" name="cpassword" tabindex="<?php $tab_index++; ?>">
                                    </div>
                                </div>

                            </div>

                            <div class="col-6" <?php echo isset($sessiondata['is_restrict']) && $sessiondata['is_restrict'] == 1 ? '' : 'style="display:none;"'; ?>>
                                <div class="form-group row">
                                    <label for="cpassword" class="col-sm-2 col-form-label">Login Valid Till<span class="required">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="date" class="form-control pull-right datepicker_text" data-inputmask="'alias': 'dd/mm/yyyy'" id="valid_till" name="valid_till" value="<?php echo isset($users['valid_till']) ? get_format_date(DATE_INPUT_FORMAT, ($users['valid_till'])) : date(LONG_VALID_TILL_DATE); ?>" autocomplete="off">
                                    </div>
                                </div>

                            </div>

                            <div class="col-6">
                                <div class="form-group row">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Status</label>
                                    <div class="form-group">
                                        <div class="radio">
                                            <input name="user[status]" type="radio" id="Option_1" checked="" value="1">
                                            <label for="Option_1">Active</label>
                                        </div>
                                        <div class="radio">
                                            <input name="user[status]" type="radio" id="Option_2" value="2" <?php echo isset($users['status']) && $users['status'] == 2 ?  'checked=""' : ''; ?>>
                                            <label for="Option_2">Inactive</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <button type="submit" class="btn btn-primary pull-right submit_btn">Save</button>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- /.col -->
                    </div>
                </div>


                <div class="tab-pane fade" id="nav-hubs" role="tabpanel" aria-labelledby="nav-hubs-tab">
                    <div class="row">
                        <div class="col-6">
                            <table class="table table-sm table-bordered table-striped">

                                <?php if (isset($all_hub) && is_array($all_hub) && count($all_hub) > 0) { ?>
                                    <tr>
                                        <td>
                                            <strong>Select All</strong>
                                        </td>
                                        <td>
                                            <div class="checkbox">
                                                <input type="checkbox" id="basic_checkbox_all" value="" id="select_all">
                                                <label for="basic_checkbox_all" style="height: 2px !important;"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php foreach ($all_hub as $key => $value) { ?>
                                        <tr>
                                            <td><?php echo $value['name']; ?></td>
                                            <td>

                                                <div class="checkbox">
                                                    <input type="checkbox" class="hub_select" id="hub_basic_checkbox_<?php echo $value['id']; ?>" name="hub_id[]" value="<?php echo $value['id']; ?>" <?php echo isset($hub_data) && in_array($value['id'], $hub_data) ? 'checked' : ''; ?>>
                                                    <label for="hub_basic_checkbox_<?php echo $value['id']; ?>" style="height: 2px !important;"></label>
                                                </div>

                                            </td>
                                        </tr>
                                <?php }
                                }
                                ?>

                            </table>
                        </div>
                    </div>
                </div>


                <div class="tab-pane fade" id="nav-permissions" role="tabpane" aria-labelledby="nav-permissions-tab" style="overflow: scroll;">
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-sm table-bordered table-striped" style="width: auto;">
                                <div style="margin: 0 0 5px 10px;">
                                    <input type="checkbox" id="all_permission" class="selectAllCheckbox2">
                                    <label for="all_permission" style="font-weight:900; height: 10px !important;">SELECT ALL</label>
                                </div>
                                <thead>
                                    <tr class="section_head permission_table_checkbox" style="text-align: center;">
                                        <td style="text-align: center;">
                                            <div>PERMISSIONS</div>
                                        </td>
                                        <td>
                                            <div style="padding-left: 45px;">
                                                <input type="checkbox" id="create_permission" data-all="all_permission_create" class="selectAllCheckbox">
                                                <label for="create_permission" style="height: 10px !important;">CREATE</label>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="padding-left: 45px;">

                                                <input type="checkbox" id="update_permission" data-all="all_permission_update" class="selectAllCheckbox">
                                                <label for="update_permission" style="height: 10px !important;">UPDATE</label>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="padding-left: 30px;">

                                                <input type="checkbox" id="view_permission" data-all="all_permission_view" class="selectAllCheckbox">
                                                <label for="view_permission" style="height: 10px !important;">VIEW</label>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="padding-left: 43px;">

                                                <input type="checkbox" id="delete_permission" data-all="all_permission_delete" class="selectAllCheckbox">
                                                <label for="delete_permission" style="height: 10px !important;">DELETE</label>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="padding-left: 47px;">

                                                <input type="checkbox" id="history_permission" data-all="all_permission_history" class="selectAllCheckbox">
                                                <label for="history_permission" style="height: 10px !important;">HISTORY</label>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="padding-left: 45px;">

                                                <input type="checkbox" id="export_permission" data-all="all_permission_export" class="selectAllCheckbox">
                                                <label for="export_permission" style="height: 10px !important;">EXPORT</label>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="padding-left: 45px;">

                                                <input type="checkbox" id="import_permission" data-all="all_permission_import" class="selectAllCheckbox">
                                                <label for="import_permission" style="height: 10px !important;">IMPORT</label>
                                            </div>
                                        </td>
                                    </tr>
                                </thead>

                                <?php
                                for ($i = 1; $i <= count($module_details); $i++) {
                                    $name = $module_details[$i - 1]['name'];
                                    $permission_name = str_replace(" ", "", strtolower($name));
                                ?>
                                    <tr>
                                        <td style="white-space:nowrap;">
                                            <input type="checkbox" id="<?= $permission_name ?>_permission" class="selectAllCheckbox1" data-all="all_permission_<?= $permission_name ?>">
                                            <label for="<?= $permission_name ?>_permission" style="height: 10px !important;"><?php echo $name == "DOCKETS" ? "AWB" : $name ?></label>
                                        </td>
                                        <?php
                                        foreach ($all_fixed_permission as $key => $value) {
                                            if ($value['module_id'] == $i) {
                                        ?>
                                                <td style="text-align: center;">
                                                    <div class="checkbox">
                                                        <?php
                                                        $v = $value['id'];
                                                        $per_class = (fmod($v, 7) == 1 ? "create_" : (fmod($v, 7) == 2 ? "update_" : (fmod($v, 7) == 3 ? "view_" : (fmod($v, 7) == 4 ? "delete_" : (fmod($v, 7) == 5 ? "history_" : (fmod($v, 7) == 6 ? "export_" : (fmod($v, 7) == 0 ? "import_" : ""))))))); ?>

                                                        <input type="checkbox" class="<?php echo $per_class ?>permission" data-all="all_permission_<?= $value['id'] ?>" data-perm="<?php echo $permission_name ?>_permission" id="<?php echo $permission_name ?>_<?= $value['id'] ?>" name="permission_id[]" value="<?= $value['id'] ?>" <?php echo isset($permission_data) && in_array($value['id'], $permission_data) ? 'checked' : ''; ?>>
                                                        <label for="<?php echo $permission_name ?>_<?= $value['id'] ?>" style="height: 10px !important;"></label>
                                                    </div>
                                                </td>
                                            <?php
                                            }
                                        }
                                        if ($name == "DOCKETS") {
                                            ?>
                                            <td style="font-size: 10px;  white-space:nowrap;" class="inp_style">
                                                <input type="checkbox" data-all="all_permission_316" data-perm="<?php echo $permission_name ?>_permission" id="<?php echo $permission_name ?>_316" name="permission_id[]" value="316" <?php echo isset($permission_data) && in_array(316, $permission_data) ? 'checked' : ''; ?>>
                                                <label style="margin-right:8px;" for="<?php echo $permission_name ?>_316" style="height: 10px !important;">Show Vendor</label>
                                                <input type="checkbox" data-all="all_permission_317" data-perm="<?php echo $permission_name ?>_permission" id="<?php echo $permission_name ?>_317" name="permission_id[]" value="317" <?php echo isset($permission_data) && in_array(317, $permission_data) ? 'checked' : ''; ?>>
                                                <label style="margin-right:8px;" for="<?php echo $permission_name ?>_317" style="height: 10px !important;">Edit Vendor</label>
                                                <input type="checkbox" data-all="all_permission_318" data-perm="<?php echo $permission_name ?>_permission" id="<?php echo $permission_name ?>_318" name="permission_id[]" value="318" <?php echo isset($permission_data) && in_array(318, $permission_data) ? 'checked' : ''; ?>>
                                                <label style="margin-right:8px;" for="<?php echo $permission_name ?>_318" style="height: 10px !important;">Edit Weight After In-Scan</label>
                                                <input type="checkbox" data-all="all_permission_319" data-perm="<?php echo $permission_name ?>_permission" id="<?php echo $permission_name ?>_319" name="permission_id[]" value="319" <?php echo isset($permission_data) && in_array(319, $permission_data) ? 'checked' : ''; ?>>
                                                <label style="margin-right:8px;" for="<?php echo $permission_name ?>_319" style="height: 10px !important;">Show Vendor 2</label>
                                                <input type="checkbox" data-all="all_permission_320" data-perm="<?php echo $permission_name ?>_permission" id="<?php echo $permission_name ?>_320" name="permission_id[]" value="320" <?php echo isset($permission_data) && in_array(320, $permission_data) ? 'checked' : ''; ?>>
                                                <label style="margin-right:8px;" for="<?php echo $permission_name ?>_320" style="height: 10px !important;">Manifest Details Tab</label>
                                                <input type="checkbox" data-all="all_permission_321" data-perm="<?php echo $permission_name ?>_permission" id="<?php echo $permission_name ?>_321" name="permission_id[]" value="321" <?php echo isset($permission_data) && in_array(321, $permission_data) ? 'checked' : ''; ?>>
                                                <label style="margin-right:8px;" for="<?php echo $permission_name ?>_321" style="height: 10px !important;">Pod Upload</label>
                                                <input type="checkbox" data-all="all_permission_322" data-perm="<?php echo $permission_name ?>_permission" id="<?php echo $permission_name ?>_322" name="permission_id[]" value="322" <?php echo isset($permission_data) && in_array(322, $permission_data) ? 'checked' : ''; ?>>
                                                <label style="margin-right:8px;" for="<?php echo $permission_name ?>_322" style="height: 10px !important;">Drs Details Tab</label>
                                                <input type="checkbox" data-all="all_permission_323" data-perm="<?php echo $permission_name ?>_permission" id="<?php echo $permission_name ?>_323" name="permission_id[]" value="323" <?php echo isset($permission_data) && in_array(323, $permission_data) ? 'checked' : ''; ?>>
                                                <label style="margin-right:8px;" for="<?php echo $permission_name ?>_323" style="height: 10px !important;">View L1 L2</label>
                                                <input type="checkbox" data-all="all_permission_324" data-perm="<?php echo $permission_name ?>_permission" id="<?php echo $permission_name ?>_324" name="permission_id[]" value="324" <?php echo isset($permission_data) && in_array(324, $permission_data) ? 'checked' : ''; ?>>
                                                <label style="margin-right:8px;" for="<?php echo $permission_name ?>_324" style="height: 10px !important;">Sales Billing</label>
                                                <input type="checkbox" data-all="all_permission_325" data-perm="<?php echo $permission_name ?>_permission" id="<?php echo $permission_name ?>_325" name="permission_id[]" value="325" <?php echo isset($permission_data) && in_array(325, $permission_data) ? 'checked' : ''; ?>>
                                                <label style="margin-right:8px;" for="<?php echo $permission_name ?>_325" style="height: 10px !important;">Purchase Billing</label>
                                                <input type="checkbox" data-all="all_permission_326" data-perm="<?php echo $permission_name ?>_permission" id="<?php echo $permission_name ?>_326" name="permission_id[]" value="326" <?php echo isset($permission_data) && in_array(326, $permission_data) ? 'checked' : ''; ?>>
                                                <label style="margin-right:8px;" for="<?php echo $permission_name ?>_326" style="height: 10px !important;">Attachment</label>
                                                <input type="checkbox" data-all="all_permission_327" data-perm="<?php echo $permission_name ?>_permission" id="<?php echo $permission_name ?>_327" name="permission_id[]" value="327" <?php echo isset($permission_data) && in_array(327, $permission_data) ? 'checked' : ''; ?>>
                                                <label style="margin-right:8px;" for="<?php echo $permission_name ?>_327" style="height: 10px !important;">Delivery Page View</label>
                                                <input type="checkbox" data-all="all_permission_328" data-perm="<?php echo $permission_name ?>_permission" id="<?php echo $permission_name ?>_328" name="permission_id[]" value="328" <?php echo isset($permission_data) && in_array(328, $permission_data) ? 'checked' : ''; ?>>
                                                <label style="margin-right:8px;" for="<?php echo $permission_name ?>_328" style="height: 10px !important;">Delivery Page Update</label>
                                                <input type="checkbox" data-all="all_permission_329" data-perm="<?php echo $permission_name ?>_permission" id="<?php echo $permission_name ?>_329" name="permission_id[]" value="329" <?php echo isset($permission_data) && in_array(329, $permission_data) ? 'checked' : ''; ?>>
                                                <label style="margin-right:8px;" for="<?php echo $permission_name ?>_329" style="height: 10px !important;">Vendor Charges</label>
                                                <input type="checkbox" data-all="all_permission_330" data-perm="<?php echo $permission_name ?>_permission" id="<?php echo $permission_name ?>_330" name="permission_id[]" value="330" <?php echo isset($permission_data) && in_array(330, $permission_data) ? 'checked' : ''; ?>>
                                                <label style="margin-right:8px;" for="<?php echo $permission_name ?>_330" style="height: 10px !important;">Check AWB</label>
                                                <input type="checkbox" data-all="all_permission_334" data-perm="<?php echo $permission_name ?>_permission" id="<?php echo $permission_name ?>_334" name="permission_id[]" value="334" <?php echo isset($permission_data) && in_array(334, $permission_data) ? 'checked' : ''; ?>>
                                                <label style="margin-right:8px;" for="<?php echo $permission_name ?>_334" style="height: 10px !important;">UNLOCK AWB</label>
                                                <input type="checkbox" data-all="all_permission_337" data-perm="<?php echo $permission_name ?>_permission" id="<?php echo $permission_name ?>_337" name="permission_id[]" value="337" <?php echo isset($permission_data) && in_array(337, $permission_data) ? 'checked' : ''; ?>>
                                                <label style="margin-right:8px;" for="<?php echo $permission_name ?>_337" style="height: 10px !important;">REPRINT AWB LABEL</label>
                                                <input type="checkbox" data-all="all_permission_338" data-perm="<?php echo $permission_name ?>_permission" id="<?php echo $permission_name ?>_338" name="permission_id[]" value="338" <?php echo isset($permission_data) && in_array(338, $permission_data) ? 'checked' : ''; ?>>
                                                <label style="margin-right:8px;" for="<?php echo $permission_name ?>_338" style="height: 10px !important;">Is Account Copy Recevied</label>
                                                <input type="checkbox" data-all="all_permission_339" data-perm="<?php echo $permission_name ?>_permission" id="<?php echo $permission_name ?>_339" name="permission_id[]" value="339" <?php echo isset($permission_data) && in_array(339, $permission_data) ? 'checked' : ''; ?>>
                                                <label style="margin-right:8px;" for="<?php echo $permission_name ?>_339" style="height: 10px !important;">Is ACCOUNT COPY VERIFIED?</label>
                                                <input type="checkbox" data-all="all_permission_350" data-perm="<?php echo $permission_name ?>_permission" id="<?php echo $permission_name ?>_350" name="permission_id[]" value="350" <?php echo isset($permission_data) && in_array(350, $permission_data) ? 'checked' : ''; ?>>
                                                <label style="margin-right:8px;" for="<?php echo $permission_name ?>_350" style="height: 10px !important;">Send Manual Whatsapp</label>
                                                <input type="checkbox" data-all="all_permission_352" data-perm="<?php echo $permission_name ?>_permission" id="<?php echo $permission_name ?>_352" name="permission_id[]" value="352" <?php echo isset($permission_data) && in_array(352, $permission_data) ? 'checked' : ''; ?>>
                                                <label style="margin-right:8px;" for="<?php echo $permission_name ?>_352" style="height: 10px !important;">View Awb Summary</label>
                                            </td>

                                        <?php
                                        }
                                        if ($name == "MANIFEST") {
                                        ?>
                                            <td style="font-size: 10px;  white-space:nowrap;" class="inp_style">
                                                <input type="checkbox" data-all="all_permission_351" data-perm="<?php echo $permission_name ?>_permission" id="<?php echo $permission_name ?>_351" name="permission_id[]" value="351" <?php echo isset($permission_data) && in_array(351, $permission_data) ? 'checked' : ''; ?>>
                                                <label style="margin-right:8px;" for="<?php echo $permission_name ?>_351" style="height: 10px !important;">Send Manifest Pre Alert Email</label>
                                            <?php
                                        }
                                            ?>
                                            <?php if ($name == "REPORTS") {
                                            ?>
                                            <td style="font-size: 10px;  white-space:nowrap;" class="inp_style">
                                                <input type="checkbox" data-all="all_permission_351" data-perm="<?php echo $permission_name ?>_permission" id="<?php echo $permission_name ?>_336" name="permission_id[]" value="336" <?php echo isset($permission_data) && in_array(336, $permission_data) ? 'checked' : ''; ?>>
                                                <label style="margin-right:8px;" for="<?php echo $permission_name ?>_336" style="height: 10px !important;">View Custom Reports</label>
                                            </td>
                                            
                                        <?php
                                            }
                                            if ($name == "SALES ACCOUNT") {
                                        ?>
                                            <td style="font-size: 10px;  white-space:nowrap;" class="inp_style">
                                                <input type="checkbox" data-all="all_permission_340" data-perm="<?php echo $permission_name ?>_permission" id="<?php echo $permission_name ?>_340" name="permission_id[]" value="340" <?php echo isset($permission_data) && in_array(340, $permission_data) ? 'checked' : ''; ?>>
                                                <label style="margin-right:8px;" for="<?php echo $permission_name ?>_340" style="height: 10px !important;">View Profitability Report</label>
                                            <!-- </td> -->
                                            <!-- <td style="font-size: 10px;  white-space:nowrap;" class="inp_style"> -->
                                                <input type="checkbox" data-all="all_permission_353" data-perm="<?php echo $permission_name ?>_permission" id="<?php echo $permission_name ?>_353" name="permission_id[]" value="353" <?php echo isset($permission_data) && in_array(353, $permission_data) ? 'checked' : ''; ?>>
                                                <label style="margin-right:8px;" for="<?php echo $permission_name ?>_353" style="height: 10px !important;">Show Customer Ledger</label>
                                            <!-- </td> -->
                                            <!-- <td style="font-size: 10px;  white-space:nowrap;" class="inp_style"> -->
                                                <input type="checkbox" data-all="all_permission_354" data-perm="<?php echo $permission_name ?>_permission" id="<?php echo $permission_name ?>_354" name="permission_id[]" value="354" <?php echo isset($permission_data) && in_array(354, $permission_data) ? 'checked' : ''; ?>>
                                                <label style="margin-right:8px;" for="<?php echo $permission_name ?>_354" style="height: 10px !important;">Show OS Report</label>
                                            </td>
                                            
                                        <?php
                                            }
                                        ?>
                                    </tr>

                                <?php
                                }
                                ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- /.row -->
    </form>
    <!-- /.box-body -->
</div>
<?php $this->load->view('plugin/datepicker'); ?>

<script type="text/javascript" src="<?php echo JS_PATH_BACKEND; ?>jquery.validate.js"></script>
<script>
    function check_itd_admin() {
        <?php
        if (isset($itd_admin_email) && is_array($itd_admin_email) && count($itd_admin_email) > 0) {
        ?>
            var email_id = $('#email_id').val();
            var exclude_email = <?php echo json_encode($itd_admin_email); ?>;
            console.log(exclude_email);
            if (email_id != '') {
                var email_id = email_id.toLowerCase();
                if (exclude_email.includes(email_id)) {
                    $('#email_id').val('');
                    alert('This Email ID cannot be used');
                }

            }
        <?php } ?>
    }
    $(document).ready(function() {
        $('#basic_checkbox_all').click(function() {
            $(':checkbox.hub_select').prop('checked', this.checked);
        });

        if ($(".create_permission:checked").length == $(".create_permission").length) {
            $("#create_permission").prop("checked", true)
        }

        if ($(".update_permission:checked").length == $(".update_permission").length) {
            $("#update_permission").prop("checked", true)
        }

        if ($(".view_permission:checked").length == $(".view_permission").length) {
            $("#view_permission").prop("checked", true)
        }

        if ($(".delete_permission:checked").length == $(".delete_permission").length) {
            $("#delete_permission").prop("checked", true)
        }

        if ($(".history_permission:checked").length == $(".history_permission").length) {
            $("#history_permission").prop("checked", true)
        }

        if ($(".export_permission:checked").length == $(".export_permission").length) {
            $("#export_permission").prop("checked", true)
        }

        if ($(".import_permission:checked").length == $(".import_permission").length) {
            $("#import_permission").prop("checked", true)
        }


        $("#company_id").on("change", function() {
            var expiry_date = $(this).find(':selected').attr('data-value');
            var date = new Date(expiry_date);

            if (!isNaN(date.getTime())) {
                date.setDate(date.getDate());
                $("#valid_till").datepicker("update", date);
            }
        });

        $("#basic_checkbox_1").change(function() {
            if (this.checked) {
                $(".password_div").show();
                $("#password").val('');
                $("#cpassword").val('');
            } else {
                $(".password_div").hide();
                $("#password").val('');
                $("#cpassword").val('');
            }
        });

        // $('.submit_btn').on('click', function(e) {
        //     var tabError = '';
        //     $('.user_nav').find('a').each(function(e) {
        //         var nav_href = $(this).attr('href');

        //         console.log(nav_href);
        //         $('#user_form .nav-tabs a[href="' + nav_href + '"]').tab('show');
        //         if (!$("#user_form").valid()) {
        //             var tabError = "INVALID";
        //             console.log(tabError);
        //         } else {
        //             console.log(tabError + '222');
        //         }
        //     });

        //     console.log(tabError);
        //     if (tabError == '') {
        //         // $('#user_form').submit();
        //     }

        //     return false;
        // });

        $("#user_form").validate({
            rules: {
                'user[company_id]': {
                    required: true
                },
                'user[name]': {
                    required: true
                },
                'user[email]': {
                    required: true,
                    email: true
                },
                'user[contactno]': {
                    required: true
                },
                'user[role]': {
                    required: true
                },
                'user[password]': {
                    required: true
                },
                'cpassword': {
                    required: true,
                    equalTo: "#password"
                },
                'valid_till': {
                    required: true
                },

            },
            messages: {
                'user[company_id]': {
                    required: 'Select Company'
                },
                'user[name]': {
                    required: 'Name Required'
                },
                'user[email]': {
                    required: 'Email ID Required',
                    email: 'Enter valid Email ID'
                },
                'user[contactno]': {
                    required: 'Contact No. Required'
                },
                'user[role]': {
                    required: 'Select Role'
                },
                'user[password]': {
                    required: 'Password Required'
                },
                'cpassword': {
                    required: 'Confirm Password',
                    equalTo: "Enter Same Password again"
                }
            },
            errorElement: 'p',
            errorPlacement: function(error, element) {
                error.insertAfter(element);
            },
            submitHandler: function(form, event) {
                var selected_hub = $('.hub_select').filter(':checked').length;
                var role_id = $("#role_id").val();
                //DONT CHECK FOR SUPERADMIN
                if (selected_hub == 0 && role_id != 14) {
                    event.preventDefault();
                    bootbox.alert('HUB REQUIRED');
                } else {
                    var id = '<?php echo isset($users['id']) ? $users['id'] : ''; ?>';
                    $.ajax({
                        type: "POST",
                        url: "<?php echo site_url('users/check_email') ?>",
                        data: {
                            'email': $('#email_id').val(),
                            'company_id': $('#company_id').val(),
                            'id': id
                        },
                        success: function(data) {
                            $("#user_form").find(':input[type=submit]').prop('disabled', true);
                            form.submit();
                        },
                        error: function(data) {
                            bootbox.alert('Email ID already Exist...');
                        }
                    });
                }
            }
        });
    });
    $(".selectAllCheckbox").on("change", function() {

        var verticalcheckboxId = $(this).attr('id');

        if ($(this).is(":checked")) {
            $('input:checkbox[class="' + verticalcheckboxId + '"]').each(function() { //loop through each checkbox
                this.checked = true; //select all checkboxes with class "checkbox1"
            });
        } else {
            $('input:checkbox[class="' + verticalcheckboxId + '"]').each(function() { //loop through each checkbox
                this.checked = false; //select all checkboxes with class "checkbox1"
            });
        }
    });

    $(".selectAllCheckbox1").on("change", function() {
        var horizontalcheckboxId = $(this).attr('id');

        if ($(this).is(":checked")) {
            $('input:checkbox[data-perm^="' + horizontalcheckboxId + '"]').each(function() { //loop through each checkbox
                this.checked = true; //select all checkboxes with class "checkbox1"
            });
        } else {
            $('input:checkbox[data-perm^="' + horizontalcheckboxId + '"]').each(function() { //loop through each checkbox
                this.checked = false; //select all checkboxes with class "checkbox1"
            });
        }
    });

    $(".selectAllCheckbox2").on("change", function() {
        var checkboxId = $(this).attr('id');

        if ($(this).is(":checked")) {
            $('input:checkbox[data-all^="' + checkboxId + '"]').each(function() { //loop through each checkbox
                this.checked = true; //select all checkboxes with class "checkbox1"
            });
        } else {
            $('input:checkbox[data-all^="' + checkboxId + '"]').each(function() { //loop through each checkbox
                this.checked = false; //select all checkboxes with class "checkbox1"
            });
        }
    });


    $(".create_permission").change(function() {
        if ($(this).prop("checked") == false) {
            $('#create_permission').prop("checked", false)
        }
        if ($(".create_permission:checked").length == $(".create_permission").length) {
            $("#create_permission").prop("checked", true)
        }
    })

    $(".update_permission").change(function() {
        if ($(this).prop("checked") == false) {
            $('#update_permission').prop("checked", false)
        }
        if ($(".update_permission:checked").length == $(".update_permission").length) {
            $("#update_permission").prop("checked", true)
        }
    })

    $(".view_permission").change(function() {
        if ($(this).prop("checked") == false) {
            $('#view_permission').prop("checked", false)
        }
        if ($(".view_permission:checked").length == $(".view_permission").length) {
            $("#view_permission").prop("checked", true)
        }
    })
    $(".delete_permission").change(function() {
        if ($(this).prop("checked") == false) {
            $('#delete_permission').prop("checked", false)
        }
        if ($(".delete_permission:checked").length == $(".delete_permission").length) {
            $("#delete_permission").prop("checked", true)
        }
    })
    $(".history_permission").change(function() {
        if ($(this).prop("checked") == false) {
            $('#history_permission').prop("checked", false)
        }
        if ($(".history_permission:checked").length == $(".history_permission").length) {
            $("#history_permission").prop("checked", true)
        }
    })
    $(".export_permission").change(function() {
        if ($(this).prop("checked") == false) {
            $('#export_permission').prop("checked", false)
        }
        if ($(".export_permission:checked").length == $(".export_permission").length) {
            $("#export_permission").prop("checked", true)
        }
    })
    $(".import_permission").change(function() {
        if ($(this).prop("checked") == false) {
            $('#import_permission').prop("checked", false)
        }
        if ($(".import_permission:checked").length == $(".import_permission").length) {
            $("#import_permission").prop("checked", true)
        }
    })
</script>