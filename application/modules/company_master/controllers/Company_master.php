<?php

class Company_master extends MX_Controller
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
        $this->heading = 'Company';
    }
    public function _display($view, $data)
    {
        $data['heading'] = $this->heading;
        $data['parent_nav'] = 'master';
        $this->load->view('admin_header', $data);
        $this->load->view('sidebar', $data);
        $this->load->view($view, $data);
        $this->load->view('admin_footer');
    }
    public function add()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $data['mode'] = 'insert';
        $data['all_bank_account_type'] = array();
        $data['all_billing_company'] = array();
        $data['all_master_type'] = array();
        $data['all_invoice_range'] = array();
        $data['opening_bal_type'] = array();
        $data['all_gst_type'] = array();

        $data['all_from_email'] = array();
        $data['bank_data'][] = array();
        $this->_display('show_form', $data);
    }
    public function insert()
    {
        $this->load->helper('url');
        $this->load->helper('create_table');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');

        $insert_data = $this->input->post('company');

        if (isset($insert_data) && is_array($insert_data) && count($insert_data) > 0) {
            //UPLOAD FILES
            $this->load->helper('upload');
            if (isset($_FILES) && is_array($_FILES) && count($_FILES) > 0) {
                foreach ($_FILES as $fkey => $fvalue) {
                    if ($fvalue['name'] != '') {
                        $logo_response = upload_file('company_file', $fkey);
                        if (isset($logo_response['status']) && $logo_response['status'] == 'success') {

                            if (strpos($fkey, 'upi_image') !== false) {
                                $image_arr = explode("_", $fkey);
                                if (isset($image_arr[2])) {
                                    $upi_images[$image_arr[2]] = $logo_response['file_path'];
                                }
                            } else {
                                $insert_data[$fkey] =  $logo_response['file_path'];
                            }
                        }
                    }
                }
            }

            $insert_data['created_by'] = $this->user_id;
            $insert_data['created_date'] = date('Y-m-d H:i:s');
            $id = $this->gm->insert('company_master', $insert_data);

            $this->session->set_flashdata('add_feedback', $this->heading . ' added successfully');
            redirect(site_url('company_master/edit/' . $id));
        } else {
            $this->session->set_flashdata('add_feedback', $this->heading . ' name cannot be empty');
        }
        redirect(site_url('company_master/show_list'));
    }

    public function _generate_data($limit = 0, $offset = 0)
    {
        $this->load->helper('frontend_common');
        $limitQ = '';
        if ($limit > 0) {
            $limitQ = " LIMIT " . $limit . " OFFSET " . $offset;
        }
        $appendquery = '';
        $get = $this->input->get();

        if (isset($get['com_name']) && $get['com_name'] != '') {
            $appendquery .= " AND name LIKE '%" . $get['com_name'] . "%'";
        }
        if (isset($get['created_min']) && $get['created_min'] != '') {
            $appendquery .= " AND created_date >='" . $get['created_min'] . " 00:00:00'";
        }
        if (isset($get['created_max']) && $get['created_max'] != '') {
            $appendquery .= " AND created_date <='" . $get['created_max'] . " 23:59:59'";
        }

        if (isset($get['status']) && $get['status'] != '') {
            $appendquery .= " AND status = '" . $get['status'] . "'";
        }

        $query = "SELECT * FROM `company_master` WHERE status!='3' " . $appendquery . " ORDER BY created_date DESC " . $limitQ;
        $query_exec = $this->db->query($query);
        $result = $query_exec->result_array();

        $cquery = "SELECT count(id) as id FROM `company_master` WHERE status!='3' " . $appendquery;
        $cquery_exec = $this->db->query($cquery);
        $count = $cquery_exec->row_array();


        $data['list'] = $result;

        $data['total'] = isset($count['id']) ? $count['id'] : 0;
        $data['offset'] = $offset;
        $data['all_billing_company'] = array();
        return $data;
    }
    public function show_list()
    {
        $this->load->helper('pagination');
        $this->load->helper('url');
        $this->load->model('Global_model', 'gm');

        //check company exist or not
        $cquery = "SELECT  id FROM `company_master` WHERE status!='3' ";
        $cquery_exec = $this->db->query($cquery);
        $count = $cquery_exec->num_rows();

        if ($count == 0) {
            //ADD COMPANY
            $session_data = $this->session->userdata('admin_user');
            $main_db = $this->load->database('main_db', true);
            $qry = "SELECT id,company_code,company_name,logo FROM company WHERE status IN(1,2) AND id='" . $session_data['com_id'] . "'";
            $qry_exe = $main_db->query($qry);
            $company_data = $qry_exe->row_array();
            if (isset($company_data) && is_array($company_data) && count($company_data) > 0) {
                $company_insert = array(
                    'name' => $company_data['company_name'],
                    'code' => $company_data['company_code'],
                    'logo_file' => $company_data['logo'],
                    'created_date' => date('Y-m-d H:i:s')
                );
                $this->gm->insert('company_master', $company_insert);
            }
        }

        $page = $this->uri->segment(3);
        $offset = page_offset($page);
        $data = $this->_generate_data(PER_PAGE, $offset);

        $pagination_data = array(
            'url' => site_url('company_master/show_list'),
            'total_rows' => isset($data['total']) ? $data['total'] : 0
        );
        pagination_config($pagination_data);
        $this->_display('show_list', $data);
    }
    public function edit($id = 0)
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');
        $data['company'] = $this->gm->get_selected_record('company_master', '*', $where = array('id' => $id, 'status !=' => 3), array());

        if (isset($data['company']) && is_array($data['company']) && count($data['company']) > 0) {
            $data['mode'] = 'update';
            $data['all_bank_account_type'] = array();
            $data['all_billing_company'] = array();
            $data['all_master_type'] = array();
            $data['all_invoice_range'] = array();
            $data['opening_bal_type'] = array();
            $data['all_gst_type'] = array();
            $data['all_from_email'] = array();
            $data['all_country'] = array();
       
            $this->_display('show_form', $data);
        } else {
            redirect(site_url('company_master/show_list'));
        }
    }

    public function update()
    {
        $this->load->helper('url');
        $this->load->helper('upload');
        $this->load->model('Global_model', 'gm');
        $this->load->helper('frontend_common');
        $post_data = $this->input->post();
        // echo '<pre>';
        // print_r($post_data);
        // exit;
        $session_data = $this->session->userdata('admin_user');
        $insert_data = $this->input->post('company');
        if (isset($insert_data) && is_array($insert_data) && count($insert_data) > 0) {
            if (isset($post_data['company_id']) && $post_data['company_id'] > 0) {
                //UPLOAD FILES
                if (isset($_FILES) && is_array($_FILES) && count($_FILES) > 0) {
                    foreach ($_FILES as $fkey => $fvalue) {
                        if ($fvalue['name'] != '') {

                            $logo_response = upload_file('company_file', $fkey);
                            if (isset($logo_response['status']) && $logo_response['status'] == 'success') {
                                if (strpos($fkey, 'upi_image') !== false) {
                                    $image_arr = explode("_", $fkey);
                                    if (isset($image_arr[2])) {
                                        $upi_images[$image_arr[2]] = $logo_response['file_path'];
                                    }
                                } else {
                                    $insert_data[$fkey] =  $logo_response['file_path'];
                                }
                            }

                            // if ($fkey == 'logo_file') {
                            //     $main_company_logo = isset($insert_data['logo_file']) ? $insert_data['logo_file'] : '';
                            //     $main_db = $this->load->database('main_db', true);
                            //     $updateq = "UPDATE company SET logo='" . $main_company_logo . "' WHERE id='" . $session_data['com_id'] . "'";
                            //     $main_db->query($updateq);
                            // }
                        }
                    }
                }

                $insert_data['modified_by'] = $this->user_id;
                $insert_data['modified_date'] = date('Y-m-d H:i:s');

                $this->gm->update('company_master', $insert_data, '', array('id' => $post_data['company_id']));

                $id = $post_data['company_id'];
                
                $this->session->set_flashdata('add_feedback', $this->heading . ' updated successfully');
                redirect(site_url('company_master/edit/' . $id));
            } else {
                redirect(site_url('company_master/show_list'));
            }
        } else {
            $this->session->set_flashdata('add_feedback', $this->heading . ' name cannot be empty');
        }
        redirect(site_url('company_master/show_list'));
    }

    public function delete($id)
    {
        $this->load->helper('url');
        $this->load->model('Global_model', 'gm');
        if (isset($id) && $id != '') {
            $query = "update company_master SET status = 3,modified_date='" . date('Y-m-d H:i:s') . "',modified_by='" . $this->user_id . "' where id = " . $id;
            $this->db->query($query);
            $this->session->set_flashdata('delete_feedback', 'Company deleted successfully');
        }
        redirect(site_url('company_master/show_list'));
    }
    public function advance_search()
    {
        $post = $this->input->post();
        $url = '';

        if (isset($post) && is_array($post) && count($post) > 0) {
            if (isset($post['com_name']) && $post['com_name'] != "") {
                $url = $url . "&com_name=" . $post['com_name'];
            }
            if (isset($post['created_min']) && $post['created_min'] != "" && isset($post['created_max']) && $post['created_max'] != "") {
                $url = $url . "&created_min=" . date('Y-m-d', strtotime(str_replace('/', '-', $post['created_min'])));
                $url = $url . "&created_max=" . date('Y-m-d', strtotime(str_replace('/', '-', $post['created_max'])));
            }
            if (isset($post['status']) && $post['status'] != "") {
                $url = $url . "&status=" . $post['status'];
            }
        }
        redirect('company_master/show_list?' . $url);
    }

}
