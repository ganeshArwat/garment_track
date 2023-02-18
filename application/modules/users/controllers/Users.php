<?php

class Users extends MX_Controller
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
        $this->main_db = $this->load->database('main_db', true);
        $sessiondata = $this->session->userdata('admin_user');

        // $segment1 = $this->uri->segment(1);
        // $segment2 = $this->uri->segment(2);
        // $url = $segment1 . "/" . $segment2;

        // if(!in_array($url,$sessiondata['url_permission'])){
        //     redirect('trackmate_lite/adminx');
        // }

        $this->user_id = $sessiondata['id'];
        $this->is_restrict = $sessiondata['is_restrict'];
    }
    public function _display($view, $data)
    {
        $data['heading'] = 'Users';
        $this->load->view('admin_header', $data);
        $this->load->view('sidebar', $data);
        $this->load->view($view, $data);
        $this->load->view('admin_footer');
    }


    public function add()
    {
        $sessiondata = $this->session->userdata('admin_user');
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $data['all_role'] = get_all_role(" AND is_restrict=2");
        $data['all_company'] = get_all_company();
        $data['all_fixed_permission'] =  get_all_fixed_permission();
        $data['all_special_permission'] =  get_all_special_permission();

        //Get modules details

        $query = "SELECT name FROM `module` WHERE status = 1";
        $query_exec = $this->main_db->query($query);
        $data['module_details'] = $query_exec->result_array();

        $data['mode'] = 'insert';
        $sessiondata = $this->session->userdata('admin_user');
        if (isset($sessiondata['is_restrict']) && $sessiondata['is_restrict'] == 2) {
            $data['users']['company_id'] = $sessiondata['com_id'];
            $data['all_hub'] = get_all_hub();
            $itd_admin_email =  get_all_itd_admin();
            if (isset($itd_admin_email) && is_array($itd_admin_email) && count($itd_admin_email) > 0) {
                foreach ($itd_admin_email as $key => $value) {
                    $data['itd_admin_email'][] = strtolower(trim($value));
                }
            }
        }
        $data['all_doc_type'] = get_all_doc_type();
        $this->_display('company_user_form', $data);
    }
    public function show_itd_admin()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');

        $sessiondata = $this->session->userdata('admin_user');
        if (isset($sessiondata['is_restrict']) && $sessiondata['is_restrict'] == 1) {
            $qry = "SELECT GROUP_CONCAT(email_id) as admin_id FROM itd_admin_email WHERE status IN(1,2)";
            $qry_exe = $this->main_db->query($qry);
            $data['result'] = $qry_exe->row_array();
            $this->_display('itd_admin_form', $data);
        } else {
            redirect(site_url());
        }
    }

    public function update_itd_admin_email()
    {
        $this->load->helper('url');
        $this->load->model('Global_model', 'gm');
        $this->load->helper('frontend_common');
        $admin_email = $this->input->post('admin_email');

        $updateq = "DELETE FROM itd_admin_email";
        $this->main_db->query($updateq);

        if ($admin_email != '') {
            $email_arr = explode(",", $admin_email);
            if (isset($email_arr) && is_array($email_arr) && count($email_arr) > 0) {
                foreach ($email_arr as $key => $value) {
                    if ($value != '') {
                        $insert_email[$value] = $value;
                    }
                }
            }

            if (isset($insert_email) && is_array($insert_email) && count($insert_email) > 0) {
                foreach ($insert_email as $ekey => $evalue) {
                    $admin_email = array(
                        'email_id' => $evalue,
                        'created_by' => $this->user_id,
                        'created_date' => date('Y-m-d H:i:s')
                    );
                    $this->main_db->insert('itd_admin_email', $admin_email);
                }
            }
        }
        redirect('users/show_itd_admin');
    }
    public function edit($id = 0)
    {
        $this->load->helper('url');
        $this->load->model('Global_model', 'gm');
        $this->load->helper('frontend_common');
        $sessiondata = $this->session->userdata('admin_user');
        $append = "";
        if ($sessiondata['is_restrict'] == 2) {
            //SHOW LOGGED IN COMPANY USER ONLY
            $append = " AND company_id='" . $sessiondata['com_id'] . "'";
        }
        $qry = "SELECT * FROM admin_user WHERE status IN(1,2) AND id = " . $id . $append;
        $qry_exe = $this->main_db->query($qry);
        $data['users'] = $qry_exe->row_array();

        $query = "SELECT name FROM `module` WHERE status = 1";
        $query_exec = $this->main_db->query($query);
        $data['module_details'] = $query_exec->result_array();

        if (isset($data['users']) && is_array($data['users']) && count($data['users']) > 0) {
            $data['mode'] = 'update';
            $data['all_role'] = get_all_role(" AND is_restrict=2");
            $data['all_company'] = get_all_company("");
            $data['all_fixed_permission'] = get_all_fixed_permission();
            $data['all_special_permission'] =  get_all_special_permission();

            $sessiondata = $this->session->userdata('admin_user');
            if (isset($sessiondata['is_restrict']) && $sessiondata['is_restrict'] == 2) {
                $data['all_hub'] = get_all_hub();

                //get hub data
                $hubq = "SELECT id,hub_id FROM hub_mapping WHERE status IN(1,2) AND module_type=2 AND module_id=" . $id;
                $hubq_exe = $this->db->query($hubq);
                $hub_data = $hubq_exe->result_array();
                if (isset($hub_data) && is_array($hub_data) && count($hub_data) > 0) {
                    foreach ($hub_data as $key => $value) {
                        $data['hub_data'][$value['hub_id']] = $value['hub_id'];
                    }
                }

                //get permission data
                $CI = &get_instance();
                $main_db = $CI->load->database('main_db', true);
                $perm = "SELECT id,user_id,permission_id FROM user_permission_map WHERE status IN(1,2) AND user_id =" . $id;
                $perm_exe = $main_db->query($perm);
                $perm_data = $perm_exe->result_array();

                if (isset($perm_data) && is_array($perm_data) && count($perm_data) > 0) {
                    foreach ($perm_data as $key => $value) {
                        $data['permission_data'][$key] = $value['permission_id'];
                    }
                }

                //itd admin

                $itd_admin_email =  get_all_itd_admin();
                if (isset($itd_admin_email) && is_array($itd_admin_email) && count($itd_admin_email) > 0) {
                    foreach ($itd_admin_email as $key => $value) {
                        $data['itd_admin_email'][] = strtolower(trim($value));
                    }
                }
            }

            $data['all_doc_type'] = get_all_doc_type();

            $this->_display('company_user_form', $data);
        } else {
            redirect(site_url('users/show_list'));
        }
    }

    public function insert()
    {
        $this->load->helper('url');
        $this->load->model('Global_model', 'gm');
        $this->load->helper('upload');
        $this->load->helper('frontend_common');
        $post_data  = $this->input->post();
        $insert_data = $this->input->post('user');

        /**
         * check company login count and active users
         */
        $qry = "SELECT * FROM company WHERE status IN(1,2) AND id = " . $insert_data['company_id'];
        $qry_exe = $this->main_db->query($qry);
        $company_data = $qry_exe->row_array();

        $itd_admin_email =  get_all_itd_admin();


        if (isset($company_data) && is_array($company_data) && count($company_data) > 0) {
            //IF EMAIL ID IS ITD EMAIL THEN ASSIGN ROLE 8
            if ($insert_data['company_id'] > 1 && !in_array($insert_data['email'], $itd_admin_email)) {
                $cquery = "SELECT id FROM `admin_user` WHERE status='1' AND role!=8 AND company_id=" . $insert_data['company_id'];
                $cquery_exec = $this->main_db->query($cquery);
                $company_users = $cquery_exec->result_array();
                $login_count = $company_data['login_count'];
                $active_user = count($company_users);
            }


            if ((isset($login_count) && $login_count > $active_user) || ($insert_data['company_id'] > 1 && in_array($insert_data['email'], $itd_admin_email))) {
                $insert_data['password'] = md5($insert_data['password']);
                if ($post_data['valid_till'] == '') {
                    $cquery = "SELECT id,expiry_date FROM `company` WHERE status='1' AND id=" . $insert_data['company_id'];
                    $cquery_exec = $this->main_db->query($cquery);
                    $company_data = $cquery_exec->row_array();

                    $post_data['valid_till'] = $company_data['expiry_date'];
                }
                $insert_data['valid_till'] = date('Y-m-d', strtotime(str_replace("/", "-", $post_data['valid_till'])));

                $name_str = preg_replace('/[^a-z]/i', '', $insert_data['name']);
                $insert_data['user_code'] = random_strings($name_str);
                $insert_data['user_name'] = $insert_data['email'];
                $insert_data['created_by'] = $this->user_id;
                $insert_data['created_date'] = date('Y-m-d H:i:s');

                //IF EMAIL ID IS ITD EMAIL THEN ASSIGN ROLE 8
                // if ($insert_data['company_id'] > 1 && in_array($insert_data['email'], $itd_admin_email)) {
                //     $insert_data['role'] = 8;
                // }

                //upload profile_file
                if ($_FILES['profile_file'] != '') {
                    $logo_response = upload_file('user_profile', 'profile_file');
                    if (isset($logo_response['status']) && $logo_response['status'] == 'success') {
                        $insert_data['profile_file'] =  $logo_response['file_path'];
                    }
                }

                //upload doc_path
                if ($_FILES['doc_path'] != '') {
                    $logo_response = upload_file('user_doc_path', 'doc_path');
                    if (isset($logo_response['status']) && $logo_response['status'] == 'success') {
                        $insert_data['doc_path'] =  $logo_response['file_path'];
                    }
                }

                $this->main_db->insert('admin_user', $insert_data);
                $user_id = $this->main_db->insert_id();
                // $admin_user_new['admin_user'] = $this->main_db->gm->get_selected_record('admin_user', '*', $where = array('id' => $user_id, 'status !=' => 3), array());


                $sessiondata = $this->session->userdata('admin_user');
                if (isset($sessiondata['is_restrict']) && $sessiondata['is_restrict'] == 2) {
                    if (isset($post_data['hub_id']) && is_array($post_data['hub_id']) && count($post_data['hub_id']) > 0) {
                        foreach ($post_data['hub_id'] as $hkey => $hvalue) {
                            if ($hvalue > 0) {
                                $hub_insert = array(
                                    'module_id' => $user_id,
                                    'module_type' => 2,
                                    'hub_id' => $hvalue,
                                    'created_date' => date('Y-m-d H:i:s'),
                                    'created_by' => $this->user_id
                                );
                                $this->gm->insert('hub_mapping', $hub_insert);
                                // $admin_user_new['hub_mapping'] = $this->gm->get_selected_record('hub_mapping', '*', $where = array('hub_id ' => $post_data['hub_id'], 'status !=' => 3), array());
                            }
                        }
                    }
                }

                $query = "SELECT id,permission_type FROM `permission` WHERE status = 1";
                $query_exec = $this->main_db->query($query);
                $data['permission'] = $query_exec->result_array();
                foreach ($data['permission'] as $key => $value) {

                    if ($value['permission_type'] == 'Fixed') {
                        $data['fixed_permission'][$key] = $value['id'];
                    }
                    if ($value['permission_type'] == 'Special') {
                        $data['special_permission'][$key] = $value['id'];
                    }
                }

                $sessiondata = $this->session->userdata('admin_user');
                if (isset($sessiondata['is_restrict']) && $sessiondata['is_restrict'] == 2) {
                    if (isset($post_data['permission_id']) && is_array($post_data['permission_id']) && count($post_data['permission_id']) > 0) {
                        foreach ($post_data['permission_id'] as $pkey => $pvalue) {

                            if ($pvalue > 0) {
                                if (in_array($pvalue, $data['fixed_permission'])) {
                                    $permission_type = 'Fixed';
                                }
                                if (in_array($pvalue, $data['special_permission'])) {
                                    $permission_type = 'Special';
                                }
                                $permission_insert = array(
                                    'permission_type' => $permission_type,
                                    'user_id' => $user_id,
                                    'permission_id' => $pvalue,
                                    'created_date' => date('Y-m-d H:i:s'),
                                    'created_by' => $this->user_id
                                );

                                $this->main_db->insert('user_permission_map', $permission_insert);
                                // $admin_user_new['user_permission_map'] = $this->gm->get_selected_record('user_permission_map', '*', $where = array('user_id' => $user_id, 'status !=' => 3), array());
                            }
                        }
                    }
                }
                // if (isset($admin_user_new) && is_array($admin_user_new)) {
                //     $insert_data = array(
                //         'user_id' => $user_id,
                //         'new_data' => isset($admin_user_new) ? json_encode($admin_user_new) : '',
                //         'old_data' => '',
                //         'created_date' => date('Y-m-d H:i:s'),
                //         'created_by' => $sessiondata['id'],
                //         'created_by_type' => $sessiondata['type'] == 'customer' ? 2 : 1
                //     );
                //     $this->gm->insert('users_history', $insert_data);
                // }
                $this->session->set_flashdata('add_feedback', 'User added successfully');
                redirect(site_url('users/edit/' . $user_id));
            } else {
                $this->session->set_flashdata('add_feedback', 'Only ' . $login_count . ' Users can be added.');
            }
        } else {
            $this->session->set_flashdata('add_feedback', 'Company not present.');
        }
        redirect(site_url('users/show_list'));
    }

    public function update()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->helper('upload');
        $this->load->model('Global_model', 'gm');
        $post_data = $this->input->post();

        $insert_data = $this->input->post('user');

        $itd_admin_email =  get_all_itd_admin();

        if (isset($post_data) && is_array($post_data) && count($post_data) > 0) {
            $user_id = $this->input->post('user_id');
            if (isset($post_data['check_password']) && $post_data['check_password'] == 1) {
                $insert_data['password'] = md5($insert_data['password']);
            } else {
                unset($insert_data['password']);
            }

            $insert_data['valid_till'] = date('Y-m-d', strtotime(str_replace("/", "-", $post_data['valid_till'])));

            $insert_data['user_name'] = $insert_data['email'];
            $insert_data['modified_by'] = $this->user_id;
            $insert_data['modified_date'] = date('Y-m-d H:i:s');
            //IF EMAIL ID IS ITD EMAIL THEN ASSIGN ROLE 8
            // if ($insert_data['company_id'] > 1 && in_array($insert_data['email'], $itd_admin_email)) {
            //     $insert_data['role'] = 8;
            // }
            $this->main_db->where(array('id' => $user_id));


            //upload doc_path
            if ($_FILES['doc_path'] != '') {
                $logo_response = upload_file('user_doc_path', 'doc_path');
                if (isset($logo_response['status']) && $logo_response['status'] == 'success') {
                    $insert_data['doc_path'] =  $logo_response['file_path'];
                }
            }

            //upload profile_file
            if ($_FILES['profile_file'] != '') {
                $logo_response = upload_file('user_profile', 'profile_file');
                if (isset($logo_response['status']) && $logo_response['status'] == 'success') {
                    $insert_data['profile_file'] =  $logo_response['file_path'];
                }
            }

            $this->main_db->update('admin_user', $insert_data);

            $sessiondata = $this->session->userdata('admin_user');
            if (isset($sessiondata['is_restrict']) && $sessiondata['is_restrict'] == 2) {
                $deleteq = "DELETE FROM hub_mapping WHERE module_type=2 AND module_id=" . $user_id;
                $this->db->query($deleteq);
                if (isset($post_data['hub_id']) && is_array($post_data['hub_id']) && count($post_data['hub_id']) > 0) {
                    foreach ($post_data['hub_id'] as $hkey => $hvalue) {
                        if ($hvalue > 0) {
                            $hub_insert[] = array(
                                'module_id' => $user_id,
                                'module_type' => 2,
                                'hub_id' => $hvalue,
                                'created_date' => date('Y-m-d H:i:s'),
                                'created_by' => $this->user_id
                            );
                        }
                    }
                }
            }

            if (isset($hub_insert) && is_array($hub_insert) && count($hub_insert) > 0) {
                $this->db->insert_batch('hub_mapping', $hub_insert);
            }

            $query = "SELECT id,permission_type FROM `permission` WHERE status = 1";
            $query_exec = $this->main_db->query($query);
            $data['permission'] = $query_exec->result_array();
            foreach ($data['permission'] as $key => $value) {

                if ($value['permission_type'] == 'Fixed') {
                    $data['fixed_permission'][$key] = $value['id'];
                }
                if ($value['permission_type'] == 'Special') {
                    $data['special_permission'][$key] = $value['id'];
                }
            }

            $sessiondata = $this->session->userdata('admin_user');
            if (isset($sessiondata['is_restrict']) && $sessiondata['is_restrict'] == 2) {
                $deleteq = "DELETE FROM user_permission_map WHERE user_id=" . $user_id;
                $this->main_db->query($deleteq);
                if (isset($post_data['permission_id']) && is_array($post_data['permission_id']) && count($post_data['permission_id']) > 0) {
                    foreach ($post_data['permission_id'] as $hkey => $pvalue) {
                        if ($pvalue > 0) {
                            if (in_array($pvalue, $data['fixed_permission'])) {
                                $permission_type = 'Fixed';
                            }
                            if (in_array($pvalue, $data['special_permission'])) {
                                $permission_type = 'Special';
                            }
                            $permission_insert = array(
                                'permission_type' => $permission_type,
                                'user_id' => $user_id,
                                'permission_id' => $pvalue,
                                'created_date' => date('Y-m-d H:i:s'),
                                'created_by' => $this->user_id
                            );



                            $this->main_db->insert('user_permission_map', $permission_insert);
                        }
                    }
                }
            }

            $this->session->set_flashdata('update_feedback', 'User updated successfully');
            redirect(site_url('users/edit/' . $user_id));
        }
        redirect(site_url('users/show_list'));
    }

    public function show_list()
    {
        $this->load->helper('pagination');
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');
        $mode = $this->input->get('hidden_type');
        if ($mode == 'downloadreport') {
            $this->export_data();
        } else {
            $get = $this->input->get();
            $page = $this->uri->segment(3);
            $offset = page_offset($page);
            // echo "<pre>";print_r($get);exit;
            $data = $this->_generate_data(PER_PAGE, $offset);
            $pagination_data = array(
                'url' => site_url('users/show_list'),
                'total_rows' => isset($data['total']) ? $data['total'] : 0
            );
            pagination_config($pagination_data);

            $this->_display('users_list', $data);
        }
    }

    public function _generate_data($limit = 0, $offset = 0)
    {
        $this->load->helper('frontend_common');
        $appendquery = '';
        $get = $this->input->get();
        $limitQ = '';
        if ($limit > 0) {
            $limitQ = " LIMIT " . $limit . " OFFSET " . $offset;
        }
        $sessiondata = $this->session->userdata('admin_user');
        if (isset($sessiondata['is_restrict']) && $sessiondata['is_restrict'] == 2) {
            $appendquery .= " AND u.company_id= " . $sessiondata['com_id'];
            $appendquery .= " AND u.role !=8";
        }
        if (isset($get['name']) && $get['name'] != '') {
            $appendquery .= " AND u.name LIKE '%" . $get['name'] . "%'";
        }

        if (isset($get['email']) && $get['email'] != '') {
            $appendquery .= " AND u.email ='" . $get['email'] . "'";
        }
        // echo "<pre>";print_r($appendquery);exit;

        if (isset($get['company_id']) && $get['company_id'] != '') {
            $appendquery .= " AND u.company_id ='" . $get['company_id'] . "'";
        }

        if (isset($get['role_id']) && $get['role_id'] != '') {
            $appendquery .= " AND u.role ='" . $get['role_id'] . "'";
        }
        if (isset($get['role']) && $get['role'] != '') {
            $get_all_role = get_all_role(" AND status = 1", "name");
            foreach ($get_all_role as $key => $value) {
                if (true) {
                    $user_id = $key;
                }
            }
        }
        $joinQry = "";
        $orderBY = " ORDER BY u.created_date DESC";
        if (isset($get['column']) && $get['column'] != '') {
            if ($get['column'] == 'ro.name') {
                $joinQry = " LEFT OUTER JOIN role ro ON(ro.id=u.role AND ro.status IN(1,2))";
            }

            $orderBY = " ORDER BY " . $get['column'] . " " . (isset($get['order']) && $get['order'] != '' ? $get['order'] : 'asc');
        }

        $query = "SELECT u.* FROM `admin_user` u " . $joinQry . "WHERE u.status!='3' " . $appendquery . $orderBY . $limitQ;

        $query_exec = $this->main_db->query($query);
        $result = $query_exec->result_array();

        //get user last active date-time
        if (isset($result) && is_array($result) && count($result) > 0) {
            foreach ($result as $key => $value) {
                $query = "SELECT id,timestamp FROM `ci_sessions` WHERE user_id='" . $value['id'] . "' ORDER BY timestamp DESC";
                $query_exec = $this->main_db->query($query);
                $last_active = $query_exec->row_array();
                $result[$key]['last_active'] = isset($last_active['timestamp']) ? date('d/m/Y h:i a', $last_active['timestamp']) : '';
            }
        }

        $cquery = "SELECT count(u.id) as id FROM `admin_user` u " . $joinQry . " WHERE u.status!='3' " . $appendquery;
        $cquery_exec = $this->main_db->query($cquery);
        $count = $cquery_exec->row_array();


        $data['all_role'] = get_all_role();

        $data['list'] = $result;
        $data['total'] = isset($count['id']) ? $count['id'] : 0;
        $data['offset'] = $offset;
        $data['all_company'] = get_all_company();
        $data['all_role'] = get_all_role();
        // echo "<pre>";print_r($data['all_role']);exit;


        return $data;
    }

    public function advance_search()
    {
        $post = $this->input->post();
        $url = '';
        if (isset($post) && is_array($post) && count($post) > 0) {
            $id_label = array(
                'name', 'email', 'company_id', 'role_id', 'hidden_type'
            );
            foreach ($id_label as $key => $value) {
                if (isset($post[$value]) && $post[$value] != "") {
                    $url = $url . "&" . $value . "=" . $post[$value];
                }
            }

            if (isset($post['created_min']) && $post['created_min'] != "" && isset($post['created_max']) && $post['created_max'] != "") {
                $url = $url . "&created_min=" . date('Y-m-d', strtotime(str_replace('/', '-', $post['created_min'])));
                $url = $url . "&created_max=" . date('Y-m-d', strtotime(str_replace('/', '-', $post['created_max'])));
            }
        }
        redirect('users/show_list?' . $url);
    }

    public function delete($id)
    {
        $this->load->helper('url');
        $this->load->model('Global_model', 'gm');
        if (isset($id) && $id != '') {
            $query = "update admin_user SET status = 3,modified_date='" . date('Y-m-d H:i:s') . "',modified_by='" . $this->user_id . "' where id = " . $id . " AND email != 'virag@itdservices.in'";
            $this->main_db->query($query);
            if($this->main_db->affected_rows() > 0){
                $this->session->set_flashdata('delete_feedback', 'User deleted successfully');
            } else {
                $this->session->set_flashdata('delete_feedback', 'USER CANNOT BE DELETED');
            }

        }
        redirect(site_url('users/show_list'));
    }
    public function check_email()
    {
        /*
         * check ajax request
         */
        if ($this->input->is_ajax_request()) {
            $email = $this->input->post('email');
            $id = $this->input->post('id');
            $company_id = $this->input->post('company_id');
            $where = "";
            if (isset($id) && $id != '') {
                $where .= " AND id != " . $id;
            }

            if ($company_id > 0) {
                $where .= " AND company_id= " . $company_id;
            }
            $query = "select * from admin_user where status = 1 " . $where . " AND email = '" . $email . "'";
            $query_exec = $this->main_db->query($query);
            $ans = $query_exec->row_array();
            if (isset($ans) && is_array($ans) && count($ans) > 0) {
                http_response_code(403);
            } else {
                http_response_code(200);
            }
        } else {
            http_response_code(500);
        }
    }

    public function forgot_password()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $data = array();
        $this->load->view('forgot_password_form', $data);
    }

    public function send_reset_password_email()
    {
    }

    public function export_data()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');

        $filename = 'users_' . date('d-M-Y-h-i-A') . '.csv';
        $handle = fopen('php://memory', 'w');
        $data = $this->_generate_data(0, 0);

        $sessiondata = $this->session->userdata('admin_user');

        // if ($sessiondata['email'] == 'virag@itdservices.in') {}
        $line = array(
            "ID", "NAME", "EMAIL ID", "CONTACT NO.", "ROLE", "STATUS", "LAST ACTIVE",  "CREATED DATE"
        );


        fputcsv($handle, $line);
        unset($line);


        $list = isset($data['list']) ? $data['list'] :  array();
        if (isset($list) && is_array($list) && count($list) > 0) {
            $all_role = isset($data['all_role']) && is_array($data['all_role']) && count($data['all_role']) > 0 ? $data['all_role'] : array();


            foreach ($list as $key => $value) {

                $row_data = array(
                    'id' => isset($value['id']) && $value['id'] != "" ? $value['id'] : "",
                    'name' => isset($value['name']) && $value['name'] != "" ? $value['name'] : "",
                    'email' => isset($value['email']) && $value['email'] != "" ? $value['email'] : "",
                    'contactno' => isset($value['contactno']) && $value['contactno'] != "" ? $value['contactno'] : "",
                    'role' => isset($all_role[$value['role']]['name']) && $all_role[$value['role']]['name'] != "" ? $all_role[$value['role']]['name'] : "",
                    'status' => isset($value['status']) && $value['status'] == 1 ? "ACTIVE" : "INACTIVE",
                    'last_active' => isset($value['last_active']) && $value['last_active'] != "" ? $value['last_active'] : "",
                    'created_date' => isset($value['created_date']) && $value['created_date'] != "" ? $value['created_date'] : "",
                );


                fputcsv($handle, $row_data);
                unset($row_data);
            }
        }

        // rewind the "file" with the csv lines
        fseek($handle, 0);
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        // make php send the generated csv lines to the browser
        fpassthru($handle);
        fclose($handle);
    }
}
