<?php

class Adminx extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->module('login/admin_login');

        $login = new Admin_login();
        $check_login = $login->_is_logged_in();
        $check_login = true;

        if (!($check_login)) {
            $this->session->set_userdata('url_page', $url_page);
            $this->session->set_userdata('login_page', 'backend');
            $this->load->helper('url');
            redirect(site_url('login/admin_login'));
        }
    }

    function login_redirect()
    {
        $this->load->helper('url');
        $this->load->helper('database_manage');
        $this->load->model('Global_model', 'gm');
        $this->load->helper('create_table');
        $session_data = $this->session->userdata('admin_user');
        //check database and all table for login user company present or not
        $company_id = isset($session_data['com_id']) ? $session_data['com_id'] : '';

        if ($company_id > 1 && isset($session_data['is_restrict']) && $session_data['is_restrict'] == 2) {
            if (create_company_database($company_id)) {
                create_all_table();

                //RUN TABLE COLUMN MIGRATION SCRIPT
                $this->load->module('database_migration/column_script');
                $this->column_script->check_table_structure();

                $this->load->module('database_migration/optimize_table');
                $this->optimize_table->run_qry();
            }
        }


        $insert_log = array(
            'login_date' => date('Y-m-d H:i:s'),
            'user_id' => $session_data['id'],
            'user_type' => $session_data['type'] == 'software_user' ? 1 : 2,
            'ip_address' => $_SERVER['REMOTE_ADDR']
        );
        $this->gm->insert('login_log', $insert_log);


        redirect('adminx');
    }
    public function index()
    {
        $this->load->helper('url');
        $this->load->helper('database_manage');
        $this->load->helper('create_table');
        $session_data = $this->session->userdata('admin_user');
        //check database and all table for login user company present or not
        $company_id = isset($session_data['com_id']) ? $session_data['com_id'] : '';

        $data = array();

        $session_data = $this->session->userdata('admin_user');
        if ($session_data['type'] == 'customer') {
            $this->show_customer_dashboard();
        } else if ($session_data['type'] == 'vendor') {
            redirect('incoming_manifests/show_list');
        } else {
            if ($session_data['is_restrict'] == 2) {
                // $data = $this->show_company_dashboard();
                $data = array();
            } else {
                $data = array();
            }

            $this->_display('dashboard', $data);
        }

        //redirect('page_management/dashboard');
    }

    public function show_form()
    {
        $sessiondata = $this->session->userdata('admin_user');
        $customer_id = $sessiondata['customer_id'];

        if ($sessiondata['type'] == 'customer') {
            //CHEKC TO SHOW KYC PAGE
            $query = "SELECT c.id FROM customer c 
        JOIN module_setting m ON(c.id=m.module_id AND m.module_type=1 AND m.status IN(1,2))
        WHERE  m.status IN(1,2) AND  m.config_key='show_kyc_after_login'
        AND  m.config_value='1' AND c.id='" . $customer_id . "'";

            $qry_exe = $this->db->query($query);
            $show_kyc = $qry_exe->result_array();
            if (isset($show_kyc) && is_array($show_kyc) && count($show_kyc) > 0) {
                redirect('customer_masters/show_kyc');
            } else {
                $this->show_customer_dashboard();
            }
        }
    }

    public function show_company_dashboard()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');
        $this->load->helper('pagination');

        $sessiondata = $this->session->userdata('admin_user');
        $data =  array();
        return  $data;
    }
    public function show_customer_dashboard()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');
        $sessiondata = $this->session->userdata('admin_user');
        $customer_id = $sessiondata['customer_id'];

        $data = array();
        $this->_display('customer_dashboard/dashboard1', $data);
    }

    public function _display($view, $data)
    {
        $data['heading'] = 'DASHBOARD';
        $this->load->view('admin_header', $data);
        $this->load->view('sidebar', $data);
        $this->load->view($view, $data);
        $this->load->view('admin_footer');
    }
}
