<?php

class Portal_validation extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->module('login/admin_login');

        $login = new Admin_login();
        $check_login = $login->_is_logged_in();

        if (!($check_login)) {
            $this->session->set_userdata('login_page', 'backend');
            $this->load->helper('url');
            redirect(site_url());
        }
        $sessiondata = $this->session->userdata('admin_user');
        $this->user_id = $sessiondata['id'];
        $this->heading = 'Portal Validation';
    }
    public function _display($view, $data)
    {
        $data['heading'] = $this->heading;
        $data['parent_nav'] = 'settings';
        $this->load->view('admin_header', $data);
        $this->load->view('sidebar', $data);
        $this->load->view($view, $data);
        $this->load->view('admin_footer');
    }
    public function show_form($module_id = 0)
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');

        $data['mode'] = 'insert';
        $all_module_field = get_all_module_field(" AND show_in_validation=1 AND module_id=" . $module_id);
        if (isset($all_module_field) && is_array($all_module_field) && count($all_module_field) > 0) {
            foreach ($all_module_field as $key => $value) {
                if ($value['field_parent'] > 0) {
                    $parent_name = isset($all_module_field[$value['field_parent']]) ? $all_module_field[$value['field_parent']]['label_value'] : '';
                    $data['all_fields'][$parent_name][] = $value;
                }
            }
        }

        $edit_data = $this->gm->get_data_list('custom_validation_field', array('module_id' => $module_id, 'status' => 1, 'validation_user' => 2), array(), array(), 'id,label_key,validation_type');
        if (isset($edit_data) && is_array($edit_data) && count($edit_data) > 0) {
            foreach ($edit_data as $ekey => $evalue) {
                $data['setting'][$evalue['validation_type']][$evalue['id']] = $evalue['label_key'];
            }
        }

        //get setting data
        $result = $this->gm->get_data_list('setting_data', array('status' => '1'), array(), array(), 'id,config_key,config_value');
        if (isset($result) && is_array($result) && count($result) > 0) {
            foreach ($result as $skey => $svalue) {
                $setting_data[$svalue['config_key']] = $svalue['config_value'];
            }
        }
        $data['setting_data'] = isset($setting_data) ? $setting_data : array();
        $data['module_id'] = $module_id;


        //GET GRID COLUMN
        $data['grid_column'] = get_all_module_field(" AND show_in_list=1 AND module_id=" . $module_id);

        if ($data['setting_data']['portal_docket_col_sort'] == '') {
            $data['setting_data']['portal_docket_col_sort'] = 'awb_no,booking_date,forwarding_no,customer_id,product_name,vendor_name,co_vendor_name,origin_name,destination_name,consignee_name,shipper_name';
        }

        $data['all_gstin_type'] = get_all_doc_type(" AND show_in_docket_shipper=1", "docket_doc_name");

        $this->_display('show_portal_form', $data);
    }
    public function insert_data()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');
        $post = $this->input->post();
        if (isset($post) && is_array($post) && count($post) > 0) {
            //get setting data
            $setting_data = $this->gm->get_data_list('setting_data', array('status' => '1'), array(), array(), 'id,config_key');
            if (isset($setting_data) && is_array($setting_data) && count($setting_data) > 0) {
                foreach ($setting_data as $skey => $svalue) {
                    $existing_setting[$svalue['config_key']] = $svalue['id'];
                }
            }

            if (isset($post['setting']) && is_array($post['setting']) && count($post['setting']) > 0) {
                foreach ($post['setting'] as $key => $value) {
                    $insert_data['config_value'] = $value;
                    $insert_data['config_key'] = $key;
                    if (isset($existing_setting[$key])) {
                        $insert_data['modified_date'] = date('Y-m-d H:i:s');
                        $insert_data['modified_by'] = $this->user_id;
                        $this->gm->update('setting_data', $insert_data, '', array('id' => $existing_setting[$key]));
                    } else {
                        $insert_data['created_date'] = date('Y-m-d H:i:s');
                        $insert_data['created_by'] = $this->user_id;
                        $this->gm->insert('setting_data', $insert_data);
                    }
                    unset($insert_data);
                }
            }

            $insert_data = $this->input->post('validation_setting');
            $validation_show = $this->input->post('validation_show');
            $dropdown_show = $this->input->post('dropdown_show');

            $deleq = "DELETE FROM custom_validation_field WHERE validation_user=2 AND module_id=" . $post['module_id'];
            $this->db->query($deleq);

            if (isset($insert_data) && is_array($insert_data) && count($insert_data) > 0) {
                foreach ($insert_data as $key => $value) {
                    $setting_insert = array(
                        'module_id' => $post['module_id'],
                        'label_key' => $value,
                        'created_date' => date('Y-m-d H:i:s'),
                        'created_by' => $this->user_id,
                        'validation_user' => 2
                    );
                    $this->gm->insert('custom_validation_field', $setting_insert);
                }
            }

            if (isset($validation_show) && is_array($validation_show) && count($validation_show) > 0) {
                foreach ($validation_show as $skey => $svalue) {
                    $setting_insert = array(
                        'module_id' => $post['module_id'],
                        'label_key' => $svalue,
                        'validation_type' => 2,
                        'created_date' => date('Y-m-d H:i:s'),
                        'created_by' => $this->user_id,
                        'validation_user' => 2
                    );
                    $this->gm->insert('custom_validation_field', $setting_insert);
                }
            }

            if (isset($dropdown_show) && is_array($dropdown_show) && count($dropdown_show) > 0) {
                foreach ($dropdown_show as $skey => $svalue) {
                    $setting_insert = array(
                        'module_id' => $post['module_id'],
                        'label_key' => $svalue,
                        'validation_type' => 3,
                        'created_date' => date('Y-m-d H:i:s'),
                        'created_by' => $this->user_id,
                        'validation_user' => 2
                    );
                    $this->gm->insert('custom_validation_field', $setting_insert);
                }
            }

            $qry = "UPDATE setting_data SET config_value='' WHERE status=1 AND config_key='portal_kyc_setting'";
            $this->db->query($qry);
            if (isset($post['kyc_field']) && is_array($post['kyc_field']) && count($post['kyc_field']) > 0) {
                $setting_insert = array(
                    'config_value' => implode(",", $post['kyc_field']),
                    'config_key' => 'portal_kyc_setting',
                    'created_date' => date('Y-m-d H:i:s'),
                    'created_by' => $this->user_id
                );
                $qry = "SELECT id FROM setting_data WHERE status=1 AND config_key='portal_kyc_setting'";
                $cquery_exec = $this->db->query($qry);
                $kyc_data_exist = $cquery_exec->row_array();
                if (isset($kyc_data_exist) && is_array($kyc_data_exist) && count($kyc_data_exist) > 0) {
                    $this->gm->update('setting_data', $setting_insert, '', array('id' => $kyc_data_exist['id']));
                } else {
                    $this->gm->insert('setting_data', $setting_insert);
                }
            }

            $this->session->set_flashdata('add_feedback', $this->heading . ' added successfully');
        } else {
            $this->session->set_flashdata('add_feedback', $this->heading . ' name cannot be empty');
        }
        redirect(site_url('settings/portal_validation/show_form/' . $post['module_id']));
    }
}
