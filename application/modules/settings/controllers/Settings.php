<?php

class Settings extends MX_Controller
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
        $get_all_email = get_all_itd_admin();

        if ($sessiondata['email'] != 'virag@itdservices.in' && !in_array(strtolower($sessiondata["email"]), $get_all_email)) {
            redirect('login/admin_login/logout');
        }
        $this->user_id = $sessiondata['id'];
        $this->heading = 'APP SETTINGS';
        $this->main_db = $this->load->database('main_db', true);
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
    public function show_form($mode = "")
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');
        $sessiondata = $this->session->userdata('admin_user');
        if (isset($mode) && $mode == "add_comments") {

            $result = array();
            $where = '';
            $qry = "SELECT id,module_name,config_key,comment FROM app_settings_comments WHERE status IN(1,2) " . $where;
            $qry_exe = $this->main_db->query($qry);
            $com_res = $qry_exe->result_array();
            if (isset($com_res) && is_array($com_res) && count($com_res) > 0) {
                foreach ($com_res as $key => $value) {
                    $result[$value['config_key']] = $value['comment'];
                }
            }
            $data['setting_comment'] = $result;
            $data['setting'] = $result;
        } else {
            $result = array();
            $where = '';
            $qry = "SELECT id,module_name,config_key,comment FROM app_settings_comments WHERE status IN(1,2) " . $where;
            $qry_exe = $this->main_db->query($qry);
            $com_res = $qry_exe->result_array();
            if (isset($com_res) && is_array($com_res) && count($com_res) > 0) {
                foreach ($com_res as $key => $value) {
                    $result[$value['config_key']] = $value['comment'];
                }
            }
            $data['setting_comment'] = $result;
            $data['setting'] = get_all_app_setting();
        }


        $where = '';
        $qry = "SELECT id,module_name,config_key,config_value FROM app_settings WHERE status IN(1,2) " . $where;
        $qry_exe = $this->db->query($qry);
        $com_res = $qry_exe->result_array();
        if (isset($com_res) && is_array($com_res) && count($com_res) > 0) {
            foreach ($com_res as $key => $value) {
                $data['setting_id'][$value['config_key']] = $value['id'];
            }
        }
        $data['get_all_email'] = array();
        $data['all_company'] = array();
        $data['all_currency'] = array();
        $data['all_unit_type'] = array();

        if (isset($sessiondata['is_restrict']) && $sessiondata['is_restrict'] == 2) {
            $userq = " AND company_id= " . $sessiondata['com_id'];
            $data['all_user'] = get_all_user($userq);
        }

        $data['all_gstin_type'] = array();
        if (isset($mode) && $mode == "add_comments") {
            $data['mode'] = $mode;
        }
        $this->_display('app_setting', $data);
    }

    public function save_setting()
    {
        $this->load->helper('url');
        $this->load->helper('create_table');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');
        $post_data = $this->input->post();
        if (isset($post_data) && is_array($post_data) && count($post_data) > 0) {

            $all_setting = get_all_app_setting();
            $updateq = "UPDATE app_settings SET config_value=2 WHERE config_key!='master_edi_range'";
            $this->db->query($updateq);

            $updateq = "UPDATE app_settings SET config_value='' 
            WHERE (config_key='shipper_auto_search_filter' OR config_key='consignee_auto_search_filter')";
            $this->db->query($updateq);

            foreach ($post_data as $key => $value) {
                $module_name = $key;


                if (isset($value) && is_array($value) && count($value) > 0) {
                    foreach ($value as $ckey => $cvalue) {
                        if ($module_name == "pdf") {
                            if (
                                $ckey == "select_attachment_to_combine"

                            ) {
                                $cvalue = implode(",", $cvalue);
                            }
                        } else if (
                            $ckey == "shipper_auto_search_filter" ||
                            $ckey == "consignee_auto_search_filter"
                        ) {
                            $cvalue = implode(",", $cvalue);
                        }
                        $setting_data = array(
                            'module_name' => $module_name,
                            'config_key' => $ckey,
                            'config_value' => $cvalue
                        );
                        if (isset($all_setting[$ckey])) {
                            $setting_data['modified_by'] = $this->user_id;
                            $setting_data['modified_date'] = date('Y-m-d H:i:s');
                            $this->gm->update('app_settings', $setting_data, '', array('config_key' => $ckey));
                        } else {
                            $setting_data['created_by'] = $this->user_id;
                            $setting_data['created_date'] = date('Y-m-d H:i:s');
                            $this->gm->insert('app_settings', $setting_data);
                        }
                    }
                }
            }


            $this->session->set_flashdata('add_feedback', $this->heading . ' updated successfully');
        }

        redirect('settings/show_form');
    }

    public function get_settings_definition($setting_id = 0)
    {
        if ($setting_id > 0) {
            $query = "SELECT id,setting_definition FROM app_settings WHERE status IN(1,2) AND id='" . $setting_id . "'";
            $qry_exe = $this->db->query($query);
            $setting_res = $qry_exe->row_array();
        }

        if (isset($setting_res) && is_array($setting_res) && count($setting_res) > 0) {
            $response = $setting_res;
        } else {
            $response['error'] = 'Failed to get setting definition';
        }

        echo json_encode($response);
    }

    public function save_definition()
    {

        $post_data = $this->input->post();

        if (isset($post_data['setting_id'])) {
            $query = "update app_settings SET setting_definition = '" . $post_data['definition'] . "',modified_date='" . date('Y-m-d H:i:s') . "',modified_by='" . $this->user_id . "' where id = " . $post_data['setting_id'];
            $this->db->query($query);

            $response['success'] = 'setting definition updated successfully';
        } else {
            $response['error'] = 'Failed to update setting definition';
        }

        echo json_encode($response);
    }

    public function save_comments()
    {
        $this->load->helper('url');
        $this->load->helper('create_table');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');

        $post_data = $this->input->post();
        if (isset($post_data) && is_array($post_data) && count($post_data) > 0) {
            $result = array();
            $where = '';
            $qry = "SELECT id,module_name,config_key,comment FROM app_settings_comments WHERE status IN(1,2) " . $where;
            $qry_exe = $this->main_db->query($qry);
            $com_res = $qry_exe->result_array();
            if (isset($com_res) && is_array($com_res) && count($com_res) > 0) {
                foreach ($com_res as $key => $value) {
                    $result[$value['config_key']] = $value['comment'];
                }
            }
            $all_setting = $result;

            foreach ($post_data as $key => $value) {
                $module_name = $key;

                if (isset($value) && is_array($value) && count($value) > 0) {
                    foreach ($value as $ckey => $cvalue) {
                        $setting_data = array(
                            'module_name' => $module_name,
                            'config_key' => $ckey,
                            'comment' => $cvalue
                        );

                        if (isset($all_setting[$ckey])) {
                            $setting_data['modified_by'] = $this->user_id;
                            $setting_data['modified_date'] = date('Y-m-d H:i:s');
                            $this->main_db->update('app_settings_comments', $setting_data, array('config_key' => $ckey));
                        } else {
                            $setting_data['created_by'] = $this->user_id;
                            $setting_data['created_date'] = date('Y-m-d H:i:s');
                            $this->main_db->insert('app_settings_comments', $setting_data);
                        }
                    }
                }
            }

            $this->session->set_flashdata('add_feedback', $this->heading . ' comment updated successfully');
        }

        redirect('settings/show_form');
    }
}
