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
        $data['all_bank_account_type'] = all_bank_account_type();
        $data['all_billing_company'] = get_all_billing_company();
        $data['all_master_type'] = get_all_master_service_type();
        $data['all_invoice_range'] = get_all_invoice_range();
        $data['opening_bal_type'] = all_opening_type();
        $data['all_gst_type'] = all_company_gst_type();

        $data['all_from_email'] = get_all_from_email();
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


            //ADD BANK DETAILS
            $post_data = $this->input->post();
            if (isset($post_data['bank_name']) && is_array($post_data['bank_name']) && count($post_data['bank_name']) > 0) {
                foreach ($post_data['bank_name'] as $key => $value) {
                    if ($value != '') {
                        $bank_data = array(
                            'company_master_id' => $id,
                            'bank_name' => $value,
                            'serial_no' => isset($post_data['serial_no'][$key]) ? $post_data['serial_no'][$key] : 0,
                            'account_type' => isset($post_data['account_type'][$key]) ? $post_data['account_type'][$key] : 0,
                            'bank_swift_id' => isset($post_data['bank_swift_id'][$key]) ? $post_data['bank_swift_id'][$key] : '',
                            'branch' => isset($post_data['branch'][$key]) ? $post_data['branch'][$key] : '',
                            'account_name' => isset($post_data['account_name'][$key]) ? $post_data['account_name'][$key] : '',
                            'ifsc_code' => isset($post_data['ifsc_code'][$key]) ? $post_data['ifsc_code'][$key] : '',
                            'sort_code' => isset($post_data['sort_code'][$key]) ? $post_data['sort_code'][$key] : '',
                            'bank_iban' => isset($post_data['bank_iban'][$key]) ? $post_data['bank_iban'][$key] : '',
                            'account_no' => isset($post_data['account_no'][$key]) ? $post_data['account_no'][$key] : '',
                            'address' => isset($post_data['address'][$key]) ? $post_data['address'][$key] : '',
                            'opening_amount' => isset($post_data['opening_amount'][$key]) ? $post_data['opening_amount'][$key] : '',
                            'opening_date' => isset($post_data['opening_date'][$key]) ? $post_data['opening_date'][$key] : '',
                            'opening_type' => isset($post_data['opening_type'][$key]) ? $post_data['opening_type'][$key] : '',
                            'upi_id' => isset($post_data['upi_id'][$key]) ? $post_data['upi_id'][$key] : '',
                            'upi_number' => isset($post_data['upi_number'][$key]) ? $post_data['upi_number'][$key] : '',
                            'status' => isset($post_data['account_status'][$key]) ? $post_data['account_status'][$key] : 1,
                            'qr_status' => isset($post_data['qr_status'][$key]) ? $post_data['qr_status'][$key] : 2,
                            'created_by' => $this->user_id,
                            'created_date' => date('Y-m-d H:i:s')
                        );

                        if (isset($upi_images[$key])) {
                            $bank_data['upi_image'] = $upi_images[$key];
                        }

                        $company_bank_id =  $this->gm->insert('company_bank', $bank_data);

                        if ($bank_data['opening_amount'] > 0) {
                            add_bank_ledger_item($company_bank_id, 1, $bank_data['opening_type'], $company_bank_id);
                        }
                    }
                }
            }


            if (isset($post_data['master_service_type']) && is_array($post_data['master_service_type']) && count($post_data['master_service_type']) > 0) {
                foreach ($post_data['master_service_type'] as $key => $value) {
                    if (isset($post_data['invoice_range_id'][$key]) && $post_data['invoice_range_id'][$key] > 0) {
                        $range_data = array(
                            'company_master_id' => $id,
                            'master_service_type' => $value,
                            'invoice_range_id' => isset($post_data['invoice_range_id'][$key]) ? $post_data['invoice_range_id'][$key] : '',
                        );
                        $range_data['created_date'] = date('Y-m-d H:i:s');
                        $range_data['created_by'] = $this->user_id;
                        $this->gm->insert('company_invoice_range', $range_data);
                    }
                }
            }

            if (isset($post_data['non_gst']) && is_array($post_data['non_gst']) && count($post_data['non_gst']) > 0) {
                if ($post_data['non_gst']['code'] != '') {
                    $non_gst_data = $post_data['non_gst'];
                    $non_gst_data['name'] = $post_data['non_gst']['code'];
                    $non_gst_data['is_non_gst'] = 1;
                    $non_gst_data['company_master_id'] = $id;
                    $non_gst_data['created_date'] = date('Y-m-d H:i:s');
                    $non_gst_data['created_by'] = $this->user_id;
                    $this->gm->insert('invoice_range', $non_gst_data);
                }
            }
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
        $data['all_billing_company'] = get_all_billing_company();
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
            $data['all_bank_account_type'] = all_bank_account_type();
            $data['all_billing_company'] = get_all_billing_company();
            $data['all_master_type'] = get_all_master_service_type();
            $data['all_invoice_range'] = get_all_invoice_range();
            $data['opening_bal_type'] = all_opening_type();
            $data['all_gst_type'] = all_company_gst_type();
            $data['all_from_email'] = get_all_from_email();
            $data['all_country'] = get_all_country(" AND status IN(1,2)", "name");
            //GET BANK DATA
            $data['bank_data'] = $this->gm->get_data_list('company_bank', array('company_master_id' => $id), array(), array(), '*');

            $data['non_gst_data'] = $this->gm->get_selected_record('invoice_range', '*', array('company_master_id' => $id, 'status' => 1, 'is_non_gst' => 1), array());

            $company_range = $this->gm->get_data_list('company_invoice_range', array('company_master_id' => $id, 'status' => 1), array(), array(), 'id,master_service_type,invoice_range_id');
            if (isset($company_range) && is_array($company_range) && count($company_range) > 0) {
                foreach ($company_range as $ckey => $cvalue) {
                    $data['company_range'][$cvalue['master_service_type']] = $cvalue['invoice_range_id'];
                }
            }

            $data['all_co_vendor'] = get_all_co_vendor();
            $data['all_customer'] = get_all_customer();

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

                //ADD BANK DETAILS
                $id = $post_data['company_id'];
                $updateq = "UPDATE company_bank SET status=3 WHERE company_master_id=" . $id;
                $this->db->query($updateq);



                if (isset($post_data['bank_name']) && is_array($post_data['bank_name']) && count($post_data['bank_name']) > 0) {
                    foreach ($post_data['bank_name'] as $key => $value) {
                        if ($value != '') {
                            $bank_data = array(
                                'company_master_id' => $id,
                                'bank_name' => $value,
                                'serial_no' => isset($post_data['serial_no'][$key]) ? $post_data['serial_no'][$key] : 0,
                                'account_type' => isset($post_data['account_type'][$key]) ? $post_data['account_type'][$key] : 0,
                                'bank_swift_id' => isset($post_data['bank_swift_id'][$key]) ? $post_data['bank_swift_id'][$key] : '',
                                'branch' => isset($post_data['branch'][$key]) ? $post_data['branch'][$key] : '',
                                'account_name' => isset($post_data['account_name'][$key]) ? $post_data['account_name'][$key] : '',
                                'ifsc_code' => isset($post_data['ifsc_code'][$key]) ? $post_data['ifsc_code'][$key] : '',
                                'sort_code' => isset($post_data['sort_code'][$key]) ? $post_data['sort_code'][$key] : '',
                                'bank_iban' => isset($post_data['bank_iban'][$key]) ? $post_data['bank_iban'][$key] : '',
                                'account_no' => isset($post_data['account_no'][$key]) ? $post_data['account_no'][$key] : '',
                                'address' => isset($post_data['address'][$key]) ? $post_data['address'][$key] : '',
                                'opening_amount' => isset($post_data['opening_amount'][$key]) ? $post_data['opening_amount'][$key] : '',
                                'opening_date' => isset($post_data['opening_date'][$key]) ? $post_data['opening_date'][$key] : '',
                                'opening_type' => isset($post_data['opening_type'][$key]) ? $post_data['opening_type'][$key] : '',
                                'status' => isset($post_data['account_status'][$key]) ? $post_data['account_status'][$key] : 1,
                                'upi_id' => isset($post_data['upi_id'][$key]) ? $post_data['upi_id'][$key] : '',
                                'upi_number' => isset($post_data['upi_number'][$key]) ? $post_data['upi_number'][$key] : '',
                                'qr_status' => isset($post_data['qr_status'][$key]) ? $post_data['qr_status'][$key] : 2,
                            );

                            if (isset($upi_images[$key])) {
                                $bank_data['upi_image'] = $upi_images[$key];
                            }

                            if (isset($post_data['bank_id'][$key]) && $post_data['bank_id'][$key] > 0) {
                                $bank_data['modified_date'] = date('Y-m-d H:i:s');
                                $bank_data['modified_by'] = $this->user_id;
                                $bank_data['status'] =  isset($post_data['account_status'][$key]) ? $post_data['account_status'][$key] : 1;
                                $this->gm->update('company_bank', $bank_data, '', array('id' => $post_data['bank_id'][$key]));
                                $company_bank_id = $post_data['bank_id'][$key];
                            } else {
                                $bank_data['created_date'] = date('Y-m-d H:i:s');
                                $bank_data['created_by'] = $this->user_id;
                                $company_bank_id =  $this->gm->insert('company_bank', $bank_data);
                            }

                            if ($bank_data['opening_amount'] > 0) {
                                add_bank_ledger_item($company_bank_id, 1, $bank_data['opening_type'], $company_bank_id);
                            }
                        }
                    }
                }


                //ADD INVOICE RANGE DATA
                $id = $post_data['company_id'];
                $updateq = "UPDATE company_invoice_range SET status=3 WHERE company_master_id=" . $id;
                $this->db->query($updateq);

                if (isset($post_data['master_service_type']) && is_array($post_data['master_service_type']) && count($post_data['master_service_type']) > 0) {
                    foreach ($post_data['master_service_type'] as $key => $value) {
                        if (isset($post_data['invoice_range_id'][$key]) && $post_data['invoice_range_id'][$key] > 0) {
                            //check invoice range present for service type or not
                            $rangeExist = $this->gm->get_selected_record('company_invoice_range', 'id', array('master_service_type' => $value, 'company_master_id' => $id), array());

                            $range_data = array(
                                'company_master_id' => $id,
                                'master_service_type' => $value,
                                'invoice_range_id' => isset($post_data['invoice_range_id'][$key]) ? $post_data['invoice_range_id'][$key] : '',
                            );
                            if (isset($rangeExist) && is_array($rangeExist) && count($rangeExist) > 0) {
                                $range_data['modified_date'] = date('Y-m-d H:i:s');
                                $range_data['modified_by'] = $this->user_id;
                                $range_data['status'] = 1;
                                $this->gm->update('company_invoice_range', $range_data, '', array('id' => $rangeExist['id']));
                            } else {
                                $range_data['created_date'] = date('Y-m-d H:i:s');
                                $range_data['created_by'] = $this->user_id;
                                $this->gm->insert('company_invoice_range', $range_data);
                            }
                        }
                    }
                }

                //ADD NON_GST RANGE
                $updateq = "UPDATE invoice_range SET status=3 WHERE is_non_gst=1 AND company_master_id=" . $id;
                $this->db->query($updateq);

                if (isset($post_data['non_gst']) && is_array($post_data['non_gst']) && count($post_data['non_gst']) > 0) {
                    if ($post_data['non_gst']['code'] != '') {
                        $non_gst_data = $post_data['non_gst'];
                        $non_gst_data['name'] = $post_data['non_gst']['code'];
                        $non_gst_data['is_non_gst'] = 1;
                        $non_gst_data['company_master_id'] = $id;

                        $nonGstExist = $this->gm->get_selected_record('invoice_range', 'id', array('is_non_gst' => 1, 'company_master_id' => $id), array());
                        if (isset($nonGstExist) && is_array($nonGstExist) && count($nonGstExist) > 0) {
                            $non_gst_data['modified_date'] = date('Y-m-d H:i:s');
                            $non_gst_data['modified_by'] = $this->user_id;
                            $non_gst_data['status'] = 1;
                            $this->gm->update('invoice_range', $non_gst_data, '', array('id' => $nonGstExist['id']));
                        } else {
                            $non_gst_data['created_date'] = date('Y-m-d H:i:s');
                            $non_gst_data['created_by'] = $this->user_id;
                            $this->gm->insert('invoice_range', $non_gst_data);
                        }
                    }
                }
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

    function check_invoice_range()
    {
        $post = $this->input->post();
        if (isset($post) && is_array($post) && count($post) > 0) {
            $range_id = $post['range_id'];
            $company_id = $post['company_id'];
            $append = '';
            if ($company_id > 0) {
                $append = " AND r.company_master_id!='" . $company_id . "'";
            }
            $qry = "SELECT r.id FROM company_invoice_range r 
            JOIN company_master c ON(c.id=r.company_master_id)
            WHERE c.status IN(1,2) AND r.status IN(1,2) AND r.invoice_range_id='" . $range_id . "'" .  $append;
            $qry_exe = $this->db->query($qry);
            $result = $qry_exe->row_array();
            if (isset($result) && is_array($result) && count($result) > 0) {
                http_response_code(403);
            } else {
                http_response_code(200);
            }
        } else {
            http_response_code(403);
        }
    }


    function check_account_no()
    {
        $post = $this->input->post();
        $account_result = array();
        if (isset($post) && is_array($post) && count($post) > 0) {
            $account_no = $post['account_no'];
            $company_id = $post['company_id'];
            $append = '';
            if ($company_id > 0) {
                $append = " AND r.company_master_id!='" . $company_id . "'";
            }
            $qry = "SELECT r.id FROM company_bank r 
            JOIN company_master c ON(c.id=r.company_master_id)
            WHERE c.status IN(1,2)  AND r.account_no='" . $account_no . "'" .  $append;
            $qry_exe = $this->db->query($qry);
            $result = $qry_exe->row_array();

            if (isset($result) && is_array($result) && count($result) > 0) {
                $account_result['error'] = 'Account NO. ' . $account_no . ' Present for other company';
            }
        }
        echo json_encode($account_result);
    }
}
