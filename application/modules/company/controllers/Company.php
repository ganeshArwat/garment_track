<?php

class Company extends MX_Controller
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
        if (!in_array(strtolower($sessiondata["email"]), $get_all_email)) {
            redirect(site_url());
        }

        $this->user_id = $sessiondata['id'];
    }
    public function _display($view, $data)
    {
        $data['heading'] = 'Company';
        $this->load->view('admin_header', $data);
        $this->load->view('sidebar', $data);
        $this->load->view($view, $data);
        $this->load->view('admin_footer');
    }

    public function add()
    {
        $this->load->helper('url');
        $data['mode'] = 'insert';
        $this->_display('company_add', $data);
    }
    public function add_user()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $data['all_role'] = get_all_role(" AND is_restrict=2");
        $data['mode'] = 'insert';
        $this->_display('company_user_form', $data);
    }

    public function edit($id = 0)
    {
        $this->load->helper('url');
        $this->load->model('Global_model', 'gm');
        $data['company'] = $this->gm->get_selected_record('company', '*', $where = array('id' => $id), array());
        if (isset($data['company']) && is_array($data['company']) && count($data['company']) > 0) {
            $data['mode'] = 'update';
            $this->_display('company_add', $data);
        } else {
            redirect(site_url('company/show_list'));
        }
    }

    public function insert()
    {
        $this->load->helper('url');
        $this->load->helper('create_table');
        $this->load->helper('frontend_common');
        $this->load->helper('database_manage');
        $this->load->model('Global_model', 'gm');
        $this->load->helper('upload');
        $company_data = $this->input->post('company');
        $post_data = $this->input->post();
        if (isset($company_data['company_name']) && $company_data['company_name'] != '') {

            //upload media
            if ($_FILES['comp_logo'] != '') {
                $proof1_res = upload_file('company_media', 'comp_logo');
                if (isset($proof1_res['status']) && $proof1_res['status'] == 'success') {
                    $company_data['logo'] =  $proof1_res['file_path'];
                }
            }

            $company_data['company_code'] = $company_data['company_name'];
            $company_data['show_powered_by'] = isset($company_data['show_powered_by']) && $company_data['show_powered_by'] == 1 ? 1 : 2;
            $company_data['show_payment_message'] = isset($company_data['show_payment_message']) && $company_data['show_payment_message'] == 1 ? 1 : 2;
            $titleURL = strtolower(url_title($company_data['company_name']));

            $urlq = "SELECT id FROM company WHERE sef_url='" . $titleURL . "'";
            $urlq_exe = $this->db->query($urlq);
            $url_exist = $urlq_exe->result_array();

            if (isset($url_exist) && is_array($url_exist) && count($url_exist) > 0) {
                $titleURL = $titleURL . '-' . time();
            }
            $company_data['onboard_date'] = date('Y-m-d', strtotime(str_replace("/", "-", $post_data['onboard_date'])));
            $company_data['expiry_date'] = date('Y-m-d', strtotime(str_replace("/", "-", $post_data['expiry_date'])));
            $company_data['sef_url'] = $titleURL;
            $company_data['created_by'] = $this->user_id;
            $company_data['created_date'] = date('Y-m-d H:i:s');
            $company_id =  $this->gm->insert('company', $company_data);

            $password = md5('Sunan@1217');
            $insert_query = "INSERT INTO `admin_user`(`status`, `name`, `user_name`,`password`, `email`,`role`, `company_id`, `valid_till`, `created_date`, `created_by`, `modified_date`, `modified_by`) VALUES (1,'Virag','virag@itdservices.in','$password','virag@itdservices.in',14, '$company_id' ,'2032-12-31',CURRENT_TIMESTAMP, 1,CURRENT_TIMESTAMP, 1)";
            $insert_query_exe = $this->db->query($insert_query);

            create_company_database($company_id);

            $this->session->set_flashdata('add_feedback', 'Company added successfully');
        } else {
            $this->session->set_flashdata('add_feedback', 'Company name cannot be empty');
        }
        redirect(site_url('company/show_list'));
    }

    public function update()
    {
        $this->load->helper('url');
        $this->load->model('Global_model', 'gm');
        $this->load->helper('upload');

        $post_data = $this->input->post();
        $company_data = $this->input->post('company');
        if (isset($company_data['company_name']) && $company_data['company_name'] != '') {
            if (isset($post_data['company_id']) && $post_data['company_id'] > 0) {
                //upload media
                if ($_FILES['comp_logo'] != '') {
                    $proof1_res = upload_file('company_media', 'comp_logo');
                    if (isset($proof1_res['status']) && $proof1_res['status'] == 'success') {
                        $company_data['logo'] =  $proof1_res['file_path'];
                    }
                }

                $company_data['onboard_date'] = date('Y-m-d', strtotime(str_replace("/", "-", $post_data['onboard_date'])));
                $company_data['expiry_date'] = date('Y-m-d', strtotime(str_replace("/", "-", $post_data['expiry_date'])));
                $company_data['show_powered_by'] = isset($company_data['show_powered_by']) && $company_data['show_powered_by'] == 1 ? 1 : 2;
                $company_data['show_payment_message'] = isset($company_data['show_payment_message']) && $company_data['show_payment_message'] == 1 ? 1 : 2;
                $company_data['modified_by'] = $this->user_id;
                $company_data['modified_date'] = date('Y-m-d H:i:s');

                $this->gm->update('company', $company_data, '', array('id' => $post_data['company_id']));

                //update company all user expiry date
                $update_expiry = "UPDATE admin_user SET valid_till='" . $company_data['expiry_date'] . "' WHERE company_id=" . $post_data['company_id'];
                $this->db->query($update_expiry);

                $this->session->set_flashdata('add_feedback', 'Company updated successfully');
            } else {
                redirect(site_url('company/show_list'));
            }
        } else {
            $this->session->set_flashdata('add_feedback', 'Company name cannot be empty');
        }
        redirect(site_url('company/show_list'));
    }


    public function show_list()
    {
        $this->load->helper('pagination');
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $data = array();

        $this->load->model('Global_model', 'gm');

        $page = $this->uri->segment(3);
        $offset = page_offset($page);

        $appendquery = '';
        $get = $this->input->get();

        if (isset($get['company_name']) && $get['company_name'] != '') {
            $appendquery .= " AND company_name LIKE '%" . $get['company_name'] . "%'";
        }
        if (isset($get['company_id']) && $get['company_id'] != '') {
            $appendquery .= " AND id ='" . $get['company_id'] . "'";
            $data['all_company'] = get_all_company(" AND id='" . $get['company_id'] . "'");
        }

        if (isset($get['company_code']) && $get['company_code'] != '') {
            $appendquery .= " AND company_code LIKE '%" . $get['company_code'] . "%'";
        }

        $query = "SELECT * FROM `company` WHERE status!='3' AND is_restrict=2 " . $appendquery . " ORDER BY created_date DESC LIMIT " . PER_PAGE . " OFFSET " . $offset;
        $query_exec = $this->db->query($query);
        $result = $query_exec->result_array();

        $cquery = "SELECT count(id) as id FROM `company` WHERE status!='3'  AND is_restrict=2 " . $appendquery;
        $cquery_exec = $this->db->query($cquery);
        $count = $cquery_exec->row_array();

        $data['list'] = $result;

        $data['total'] = isset($count['id']) ? $count['id'] : 0;
        $data['offset'] = $offset;

        $pagination_data = array(
            'url' => site_url('company/show_list'),
            'total_rows' =>  isset($count['id']) ? $count['id'] : 0
        );
        pagination_config($pagination_data);

        $this->_display('company_list', $data);
    }
    public function advance_search()
    {
        $post = $this->input->post();
        $url = '';

        if (isset($post) && is_array($post) && count($post) > 0) {
            if (isset($post['company_name']) && $post['company_name'] != "") {
                $url = $url . "&company_name=" . $post['company_name'];
            }
            if (isset($post['company_code']) && $post['company_code'] != "") {
                $url = $url . "&company_code=" . $post['company_code'];
            }
            if (isset($post['company_id']) && $post['company_id'] != "") {
                $url = $url . "&company_id=" . $post['company_id'];
            }
        }
        redirect('company/show_list?' . $url);
    }

    function chmod_r($path)
    {
        $dir = new DirectoryIterator($path);
        foreach ($dir as $item) {
            chmod($item->getPathname(), 0777);
            if ($item->isDir() && !$item->isDot()) {
                $this->chmod_r($item->getPathname());
            }
        }
    }

    function test()
    {
        //GIVE 777 PERMISSION TO DIR
        @exec("sudo sudo chmod -R 777 /var/www/html/garment_track_/db_backup_daily");
    }

    public function download_backup()
    {
        $zip_files = array();
        $dirPath = FCPATH . "/db_backup_daily/daily";
        $files = [];
        $allFiles = (new \RecursiveTreeIterator(new \RecursiveDirectoryIterator($dirPath, \RecursiveDirectoryIterator::SKIP_DOTS)));
        foreach ($allFiles as $file) {
            if (strpos($file, '.sql.gz') !== false) {
                $folder_arr = explode("/", $file);
                $folder_name = $folder_arr[count($folder_arr) - 2];
                $zip_files[] = array(
                    'folder' => $folder_name,
                    'file_name' => basename($file)
                );
            }
        }

        $data['files'] = $zip_files;

        $this->_display('show_db_backup_list', $data);
    }
}
