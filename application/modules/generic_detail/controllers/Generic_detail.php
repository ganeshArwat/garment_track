<?php

class Generic_detail extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();

        $all_header = get_header_data();
        if (!isset($all_header['authorization']) || $all_header['authorization'] == '') {
            $this->load->module('login/admin_login');
            $login = new Admin_login();
            $check_login = $login->_is_logged_in();

            if (!($check_login)) {
                $this->session->set_userdata('login_page', 'backend');
                $this->load->helper('url');
                redirect(site_url());
            }
            $sessiondata = $this->session->userdata('admin_user');
            $this->user_type = $sessiondata['type'] == 'customer' ? 2 : 1;
            $this->customer_id = isset($sessiondata['customer_id']) && $sessiondata['customer_id'] != "" ? $sessiondata['customer_id'] : "";
        }
    }



    public function get_pickup_request_data()
    {
        $result = array();
        $token_number = $this->input->post('token_number');

        if ($token_number != "") {
            $query = "SELECT * FROM pickup_request WHERE STATUS = 1 AND ref_no='" . $token_number . "'";
            $query_exec = $this->db->query($query);
            $result = $query_exec->row_array();

            if (isset($result) && is_array($result) && count($result) > 0) {
                $data['pickup_request_data'] = $result;
                if (isset($data['pickup_request_data']['id']) && $data['pickup_request_data']['id'] != "") {
                    $query1 = "SELECT * FROM pickup_request_detail WHERE STATUS = 1 AND pickup_request_id='" . $data['pickup_request_data']['id'] . "'";
                    $query_exec1 = $this->db->query($query1);
                    $pickup_detail = $query_exec1->row_array();
                    if (isset($pickup_detail) && is_array($pickup_detail) && count($pickup_detail) > 0) {
                        $data['pickup_request_detail_data'] = $pickup_detail;
                    }
                }
                if (isset($data['pickup_request_data']['customer_id']) && $data['pickup_request_data']['customer_id'] != "") {
                    $cust_query = "SELECT id,name,code FROM customer WHERE STATUS IN(1,2) AND id='" . $data['pickup_request_data']['customer_id'] . "'";
                    $cust_query_exec1 = $this->db->query($cust_query);
                    $customer_detail = $cust_query_exec1->row_array();
                    if (isset($customer_detail) && is_array($customer_detail) && count($customer_detail) > 0) {
                        $data['customer_data'] = $customer_detail;
                    }
                }
            }

            $docket_query = "SELECT COUNT(token_number) as token_count,GROUP_CONCAT(docket_id) as docket_id FROM docket_extra_field WHERE STATUS = 1 AND token_number='" . $token_number . "'";
            $query_exec1 = $this->db->query($docket_query);
            $docket_data = $query_exec1->row_array();
            if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
                if (isset($docket_data['docket_id']) && $docket_data['docket_id'] != "") {
                    $pcquery = "SELECT SUM(total_pcs) as total_pcs FROM docket WHERE status = 1 AND id IN(" . $docket_data['docket_id'] . ")";
                    $pcquery_exec1 = $this->db->query($pcquery);
                    $pcs_data = $pcquery_exec1->row_array();
                }
                if (isset($data['pickup_request_detail_data']['total_awb']) && $data['pickup_request_detail_data']['total_awb'] != "") {
                    $data['pickup_awb_count']['awb_pending'] = (int)$data['pickup_request_detail_data']['total_awb'] - (int)$docket_data['token_count'];
                    $data['pickup_awb_count']['awb_created'] = (int)$docket_data['token_count'];
                    $data['pickup_awb_count']['total_awb'] = (int)$data['pickup_request_detail_data']['total_awb'];
                    $data['pickup_awb_count']['total_pieces'] = (int)$data['pickup_request_detail_data']['total_pieces'];
                    // if (isset($pcs_data) && is_array($pcs_data) && count($pcs_data) > 0) {
                    $data['pickup_awb_count']['total_pcs_created'] = isset($pcs_data['total_pcs']) ? (int)$pcs_data['total_pcs'] : 0;
                    $data['pickup_awb_count']['total_pcs_pending'] = (int)$data['pickup_awb_count']['total_pieces'] - (isset($pcs_data['total_pcs']) ? (int)$pcs_data['total_pcs'] : 0);
                    // }
                }
            }
        }

        echo json_encode($data);
    }

    public function check_run_no()
    {
        /*
         * check ajax request
         */
        if ($this->input->is_ajax_request()) {
            $run_number = $this->input->post('run_number');
            $id = $this->input->post('manifest_id');
            $where = " AND status IN (1,2) ";
            if (isset($id) && $id != '') {
                $where .= " AND id != " . $id;
            }

            if ($run_number != '') {

                $query = "SELECT id,manifest_no FROM manifest WHERE run_number = '" . strtolower(trim($run_number)) . "'" . $where;
                $query_exec = $this->db->query($query);
                $ans = $query_exec->row_array();
            }

            if (isset($ans) && is_array($ans) && count($ans) > 0) {
                $error_data['error'] = "RUN NUMBER ALREADY EXIST IN MANIFEST NO : " . $ans['manifest_no'];
                echo json_encode($error_data);
                http_response_code(403);
            } else {
                http_response_code(200);
            }
        } else {
            http_response_code(500);
        }
    }
    public function check_customer_gst()
    {
        /*
         * check ajax request
         */
        $setting = get_all_app_setting(" AND module_name IN('master')");
        if ($this->input->is_ajax_request()) {
            $gst_number = $this->input->post('gst_number');
            $id = $this->input->post('id');
            $where = " AND status !=3";
            // $where = "";
            if (isset($id) && $id != '') {
                $where .= " AND id != " . $id;
            }
            if ($gst_number != '') {
                $query = "SELECT * FROM customer WHERE gst_number = '" . $gst_number . "'" . $where;
                $query_exec = $this->db->query($query);
                $ans = $query_exec->row_array();
            }
          
            //if setting is on then only check gst no for duplicate
            if (isset($setting['keep_customer_gst_unique']) && $setting['keep_customer_gst_unique'] == 1) {
                if (isset($ans) && is_array($ans) && count($ans) > 0) {
                    echo "GST_EXISTS";
                }
            }
        } else {
            http_response_code(500);
        }
    }

    public function get_awb_id()
    {
        /*
         * check ajax request
         */
        if ($this->input->is_ajax_request()) {
            $awb_no = $this->input->post('awb_no');
            $id = $this->input->post('id');
            $where = "";
            if (isset($id) && $id != '') {
                $where .= " AND id != " . $id;
            }

            if ($awb_no != '') {
                $query = "SELECT id FROM docket WHERE awb_no = '" . strtolower(trim($awb_no)) . "'" . $where;
                $query_exec = $this->db->query($query);
                $ans = $query_exec->row_array();
            }

            if (isset($ans) && is_array($ans) && count($ans) > 0) {
                echo $ans['id'];
            }
        } else {
            http_response_code(500);
        }
    }
    public function check_awb_no()
    {
        /*
         * check ajax request
         */
        if ($this->input->is_ajax_request()) {
            $awb_no = $this->input->post('awb_no');
            $id = $this->input->post('id');
            $where = " AND status IN (1,2) ";
            if (isset($id) && $id != '') {
                $where .= " AND id != " . $id;
            }

            if ($awb_no != '') {

                $query = "SELECT id FROM docket WHERE awb_no = '" . strtolower(trim($awb_no)) . "'" . $where;
                $query_exec = $this->db->query($query);
                $ans = $query_exec->row_array();
            }


            if (isset($ans) && is_array($ans) && count($ans) > 0) {
                echo "AWB_EXISTS";
            }
        } else {
            http_response_code(500);
        }
    }

    public function check_code()
    {
        /*
         * check ajax request
         */
        if ($this->input->is_ajax_request()) {
            $code = $this->input->post('code');
            $module = $this->input->post('module');
            $id = $this->input->post('id');
            $where = "";
            if ($code != '') {
                if (isset($id) && $id != '') {
                    $where .= " AND id != " . $id;
                }
                if ($module == 'manifest_charge') {
                    $column_name = "name";
                } else  if ($module == 'edit_bag_no') {
                    $column_name = "master_bag_no";
                } else  if ($module == 'docket_invoice') {
                    $column_name = "invoice_no";
                } else  if ($module == 'invoice_type') {
                    $column_name = "name";
                } else  if ($module == 'pincode') {
                    $column_name = "name";
                } else {
                    $column_name = "code";
                }
                if ($module == 'pincode') {
                    $country_id = $this->input->post('country_id');
                    $query = "select id from " . $module . " where status IN(1,2) AND " . $column_name . "='" . $code . "'
                     AND country_id='" . $country_id . "'" . $where;
                } else {
                    $query = "select id from " . $module . " where status IN(1,2) AND " . $column_name . "='" . $code . "'" . $where;
                }
                $query_exec = $this->db->query($query);
                $ans = $query_exec->row_array();
            }

            if (isset($ans) && is_array($ans) && count($ans) > 0) {
                http_response_code(403);
            } else {
                //USER CAN ADD DELETED INVOICE NO. ONLY-
                if ($module == 'docket_invoice' && $code != '' && ($id == '' || $id == 0)) {
                    $setting = get_all_app_setting(" AND module_name IN('invoice')");
                    if (isset($setting['auto_generate_invoice_no']) && $setting['auto_generate_invoice_no'] == 1) {
                        $query = "select id from docket_invoice where status =3 AND " . $column_name . "='" . $code . "'" . $where;
                        $query_exec = $this->db->query($query);
                        $deleted_invoice_res = $query_exec->row_array();
                        if (isset($deleted_invoice_res) && is_array($deleted_invoice_res) && count($deleted_invoice_res) > 0) {
                            http_response_code(200);
                        } else {
                            $error_data['error_msg'] = "You can enter only deleted invoice no.";
                            echo json_encode($error_data);
                            http_response_code(200);
                        }
                    } else {
                        http_response_code(200);
                    }
                }
                http_response_code(200);
            }
        } else {
            http_response_code(403);
        }
    }


    public function check_customer_shipper_exist()
    {
        /*
         * check ajax request
         */
        //"Check Shipper name and Customer code on new Shipper code creation. If Shipper with same name is already created
        // for that customer then dont allow. Show Shipper name already exist.
        // But can be created for same name for other Customer code"
        if ($this->input->is_ajax_request()) {
            $shipper_name = $this->input->post('shipper_name');
            $customer_id = $this->input->post('customer_id');
            $id = $this->input->post('id');
            $where = "";
            if ($shipper_name != '' && $customer_id > 0) {
                if (isset($id) && $id != '') {
                    $where .= " AND id != " . $id;
                }
                $query = "select id from shipper where status IN(1,2) 
                AND name='" . $shipper_name . "' AND customer_id='" . $customer_id . "'" . $where;
                $query_exec = $this->db->query($query);
                $ans = $query_exec->row_array();
            }

            if (isset($ans) && is_array($ans) && count($ans) > 0) {
                http_response_code(403);
            } else {
                http_response_code(200);
            }
        } else {
            http_response_code(403);
        }
    }

    public function check_name()
    {
        /*
         * check ajax request
         */
        if ($this->input->is_ajax_request()) {
            $name = $this->input->post('name');
            $module = $this->input->post('module');
            $id = $this->input->post('id');
            $where = "";
            if (isset($id) && $id != '') {
                $where .= " AND id != " . $id;
            }
            $query = "select id from " . $module . " where status = 1 AND LOWER(name)='" . strtolower(trim($name)) . "'" . $where;
            $query_exec = $this->db->query($query);
            $ans = $query_exec->row_array();

            if (isset($ans) && is_array($ans) && count($ans) > 0) {
                http_response_code(403);
            } else {
                http_response_code(200);
            }
        } else {
            http_response_code(403);
        }
    }


    public function check_rate_charge_fsc()
    {
        /*
         * check ajax request
         */
        if ($this->input->is_ajax_request()) {
            $charge_id = $this->input->post('charge_id');
            $freight_fsc_per = $this->input->post('freight_fsc_per');

            if ((float)$freight_fsc_per > 0) {
                $query = "select id,is_fsc_apply from charge_master where status IN(1,2) AND id='" . $charge_id  . "' AND is_fsc_apply=1";
                $query_exec = $this->db->query($query);
                $ans = $query_exec->row_array();

                if (isset($ans) && is_array($ans) && count($ans) > 0) {
                    http_response_code(403);
                } else {
                    http_response_code(200);
                }
            } else {
                http_response_code(200);
            }
        } else {
            http_response_code(403);
        }
    }

    public function check_charge_fsc_rate()
    {
        /*
         * check ajax request
         */
        if ($this->input->is_ajax_request()) {
            $charge_id = $this->input->post('charge_id');

            if ($charge_id > 0) {
                $query = "select id from rate_modifier where status IN(1,2) AND charge_id='" . $charge_id  . "' AND freight_fsc_per>0";
                $query_exec = $this->db->query($query);
                $ans = $query_exec->row_array();

                if (isset($ans) && is_array($ans) && count($ans) > 0) {
                    http_response_code(403);
                } else {
                    http_response_code(200);
                }
            } else {
                http_response_code(200);
            }
        } else {
            http_response_code(403);
        }
    }


    public function search_keyword($module)
    {
        $query = '';
        $session_data = $this->session->userdata('admin_user');
        $this->customer_id = isset($session_data['customer_id']) && $session_data['customer_id'] != "" ? $session_data['customer_id'] : "";

        $where = "";
        if ($module == 'customer') {
            if (isset($this->customer_id) && $this->customer_id != "") {
                $where .= " AND id IN (" . $this->customer_id . ")";
            }
        }
        if ($module == 'dest_location' || $module == 'ori_location') {
            $module = 'location';
        } else if ($module == 'dest_zone' || $module == 'ori_zone') {
            $module = 'zone';
        } else if ($module == 'dest_hub' || $module == 'ori_hub') {
            $module = 'hub';
        } else if ($module == 'dest_city' || $module == 'ori_city') {
            $module = 'city';
        }
        if ($module == 'company') {
            $query = 'SELECT id,company_name as text FROM company WHERE  status IN(1,2)  
            AND (company_name LIKE "%' . trim(($this->input->get('q'))) . '%")';
        } else if ($module == 'zone') {
            $query = 'SELECT id,code as text FROM zone WHERE  status IN(1,2)  
            AND (code LIKE "%' . trim(($this->input->get('q'))) . '%")';
        } else if ($module == 'free_form_item') {
            $query = 'SELECT id,name as text FROM free_form_item WHERE  status IN(1,2)  
            AND (name LIKE "%' . trim(($this->input->get('q'))) . '%")';
        } else {
            $query = 'SELECT id,CONCAT(code," - ",name) as text FROM ' . $module . ' WHERE  status IN(1,2)
            ' . $where . '  
            AND (name LIKE "%' . trim(($this->input->get('q'))) . '%"
            OR code LIKE "%' . trim(($this->input->get('q'))) . '%")';
        }
        $result_set['items'] = array();
        $result_set['count'] = 0;
        if ($this->input->get('q') && $query != '') {

            if ($module == 'company') {
                $main_db = $this->load->database('main_db', true);
                $name_query = $main_db->query($query);
            } else {
                $name_query = $this->db->query($query);
            }

            $search_data  = $name_query->result_array();

            if (isset($search_data) && is_array($search_data) && count($search_data) > 0) {
                $result_set['incomplete_results'] = false;
                $result_set['items'] = $name_query->result_array();
                $result_set['total_count'] = $name_query->num_rows();
            }
        }
        echo json_encode($result_set);
    }

    function get_autosuggest_data($module_name = '', $segment1 = '', $segment2 = '')
    {
        $response = array();
        $query = '';
        $this->load->model('Global_model', 'gm');
        $this->load->helper('frontend_common');
        $search_keyword = $this->input->post('phrase');
        $sessiondata = $this->session->userdata('admin_user');

        $post_data = $this->input->post();

        if ($module_name == 'purchase_vendor') {
            $module = 'co_vendor';
        } else if ($module_name == 'inventory_vendor') {
            $module = 'co_vendor';
        } else {
            $module = $module_name;
        }

        if (($segment1 == 'docket' || $segment1 == 'pick_up_requests') && ($module_name == 'city' || $module_name == 'state')) {
            $concat_col = 'CONCAT(name," - ",code)';
        } else if (($segment1 == 'customer_masters') && ($module_name == 'city' || $module_name == 'state' || $module_name == 'country')) {
            $concat_col = 'CONCAT(name," - ",code)';
        } else {
            $concat_col = 'CONCAT(code," - ",name)';
        }

        unset($post_data['column_filter']);


        $or_query = '';
        if (($module == 'shipper' || $module == 'consignee') && $segment1 == 'docket'
            && ($segment2 == 'add' || $segment2 == 'edit')
        ) {
            $setting = get_all_app_setting(" AND module_name IN('docket')");
            if ($module == 'shipper') {
                if (isset($setting['shipper_auto_search_filter']) && $setting['shipper_auto_search_filter'] != '') {
                    $auto_search_filter = explode(",", $setting['shipper_auto_search_filter']);
                }
            } else {
                if (isset($setting['consignee_auto_search_filter']) && $setting['consignee_auto_search_filter'] != '') {
                    $auto_search_filter = explode(",", $setting['consignee_auto_search_filter']);
                }
            }
            $or_query .= " OR (company_name LIKE '%" . trim(($this->input->post('phrase'))) . "%')";
            if (isset($auto_search_filter) && is_array($auto_search_filter) && count($auto_search_filter) > 0) {
                foreach ($auto_search_filter as $akey => $avalue) {
                    if ($avalue == 'country') {
                        $qry = "SELECT id FROM country WHERE status IN(1,2)
                        AND (name='" . trim(($this->input->post('phrase'))) . "' OR 
                        code='" . trim(($this->input->post('phrase'))) . "')";
                        $qry_exe = $this->db->query($qry);
                        $country_data =  $qry_exe->row_array();
                        if (isset($country_data) && is_array($country_data) && count($country_data) > 0) {
                            $or_query .= " OR (country='" . $country_data['id'] . "')";
                        }
                    } else {
                        $or_query .= " OR (" . $avalue . " LIKE '%" . trim(($this->input->post('phrase'))) . "%')";
                    }
                }
            }
        }

        if (isset($post_data['searchBy']) && $post_data['searchBy'] == 'start') {
            if ($module == 'company') {
                $query = 'SELECT id,company_name as text FROM company WHERE  status IN(1,2)  
                AND (company_name LIKE "' . trim(($this->input->post('phrase'))) . '%")';
            } else if ($module == 'admin_user') {
                if (isset($sessiondata['is_restrict']) && $sessiondata['is_restrict'] == 1) {
                    $query = 'SELECT id,name as text FROM admin_user WHERE  status IN(1,2)  
                AND (name LIKE "' . trim(($this->input->post('phrase'))) . '%")';
                } else {
                    $company_id = $sessiondata['com_id'];
                    $query = 'SELECT id,name as text FROM admin_user WHERE  status IN(1,2) AND company_id =' . $company_id . '
                    AND (name LIKE "' . trim(($this->input->post('phrase'))) . '%")';
                }
            } else if ($module == 'free_form_note') {
                $query = 'SELECT id,name as text FROM ' . $module . ' WHERE  status IN(1,2)  
                AND (name LIKE "' . trim(($this->input->post('phrase'))) . '%")';
            } else if ($module == 'zone') {
                $query = 'SELECT id,code as text FROM ' . $module . ' WHERE  status IN(1,2)  
                AND (code LIKE "' . trim(($this->input->post('phrase'))) . '%")';
            } else if ($module == 'free_form_item') {
                $query = 'SELECT id,name as text FROM free_form_item WHERE  status IN(1,2)  
                AND (name LIKE "' . trim(($this->input->post('q'))) . '%")';
            } else if ($module == 'location_pincode') {
                $query = 'SELECT id,code as text FROM location WHERE  status IN(1,2) AND location_type=2
                AND (code LIKE "' . trim(($this->input->post('q'))) . '%")';
            } else if ($module == 'currency_masters') {
                $query = 'SELECT id,currency_name as text FROM currency WHERE  status IN(1,2)  
                AND (currency_name LIKE "%' . trim(($this->input->post('phrase'))) . '%")';
            } else {
                if (isset($post_data['column_filter']) && $post_data['column_filter'] == 'name') {
                    $query = 'SELECT id,' . $concat_col . ' as text FROM ' . $module . ' WHERE  status IN(1,2)  
                    AND name LIKE "' . trim(($this->input->post('phrase'))) . '%"';
                } else if (isset($post_data['column_filter']) && $post_data['column_filter'] == 'code') {
                    $query = 'SELECT id,' . $concat_col . ' as text FROM ' . $module . ' WHERE  status IN(1,2)  
                    AND code LIKE "' . trim(($this->input->post('phrase'))) . '%"';
                } else {
                    $check_name_codeq = 'SELECT id FROM ' . $module . ' WHERE code = "' . trim(($this->input->post('phrase'))) . '"';
                    $check_name_exe = $this->db->query($check_name_codeq);
                    $code_exist = $check_name_exe->row_array();

                    if (isset($code_exist) && is_array($code_exist) && count($code_exist) > 0) {
                        $query = 'SELECT id,' . $concat_col . ' as text FROM ' . $module . ' WHERE  status IN(1,2)  
                    AND code = "' . trim(($this->input->post('phrase'))) . '"';
                    } else {

                        $query = 'SELECT id,' . $concat_col . ' as text FROM ' . $module . ' WHERE  status IN(1,2)  
                        AND (name LIKE "' . trim(($this->input->post('phrase'))) . '%"
                        OR code LIKE "' . trim(($this->input->post('phrase'))) . '%" ' . $or_query . ') ';
                    }
                }
            }
        } else {
            if ($module == 'company') {
                $query = 'SELECT id,company_name as text FROM company WHERE  status IN(1,2)  
                AND (company_name LIKE "%' . trim(($this->input->post('phrase'))) . '%")';
            } else if ($module == 'admin_user') {
                if (isset($sessiondata['is_restrict']) && $sessiondata['is_restrict'] == 1) {
                    $query = 'SELECT id,name as text FROM admin_user WHERE  status IN(1,2)  
                AND (name LIKE "' . trim(($this->input->post('phrase'))) . '%")';
                } else {
                    $company_id = $sessiondata['com_id'];
                    $query = 'SELECT id,name as text FROM admin_user WHERE  status IN(1,2) AND company_id =' . $company_id . '
                    AND (name LIKE "' . trim(($this->input->post('phrase'))) . '%")';
                }
            } else if ($module == 'free_form_note') {
                $query = 'SELECT id,name as text FROM ' . $module . ' WHERE  status IN(1,2)  
                AND (name LIKE "%' . trim(($this->input->post('phrase'))) . '%")';
            } else if ($module == 'zone') {
                $query = 'SELECT id,code as text FROM ' . $module . ' WHERE  status IN(1,2)  
                AND (code LIKE "%' . trim(($this->input->post('phrase'))) . '%")';
            } else if ($module == 'free_form_item') {
                $query = 'SELECT id,name as text FROM free_form_item WHERE  status IN(1,2)  
                AND (name LIKE "%' . trim(($this->input->post('phrase'))) . '%")';
            } else if ($module == 'location_pincode') {
                $query = 'SELECT id,code as text FROM location WHERE  status IN(1,2) AND location_type=2
                AND (code LIKE "' . trim(($this->input->post('phrase'))) . '%")';
            } else if ($module == 'currency_masters') {
                $query = 'SELECT id,code as text FROM currency WHERE  status IN(1,2)  
                AND (code LIKE "%' . trim(($this->input->post('phrase'))) . '%")';
            } else {
                if (isset($post_data['column_filter']) && $post_data['column_filter'] == 'name') {
                    $query = 'SELECT id,' . $concat_col . ' as text FROM ' . $module . ' WHERE  status IN(1,2)  
                    AND name LIKE "%' . trim(($this->input->post('phrase'))) . '%"';
                } else if (isset($post_data['column_filter']) && $post_data['column_filter'] == 'code') {
                    $query = 'SELECT id,' . $concat_col . ' as text FROM ' . $module . ' WHERE  status IN(1,2)  
                    AND code LIKE "%' . trim(($this->input->post('phrase'))) . '%"';
                } else {
                    $query = 'SELECT id,' . $concat_col . ' as text FROM ' . $module . ' WHERE  status IN(1,2)  
                    AND (name LIKE "%' . trim(($this->input->post('phrase'))) . '%"
                    OR code LIKE "%' . trim(($this->input->post('phrase'))) . '%" ' . $or_query . ')';
                }
            }
        }

        if ($module == 'consignee' && $segment1 == 'docket') {
            //check Autocomplete Consignees By Destination Setting
            $setting = get_all_app_setting(" AND module_name IN('docket')");
            if (isset($setting['docket_consignee_by_dest']) && $setting['docket_consignee_by_dest'] == 1) {
                $dest_id = isset($post_data['destination_id']) ? $post_data['destination_id'] : 0;
                if ($dest_id > 0) {
                    $location_data = $this->gm->get_selected_record('location', 'id,location_type,name', array('id' =>  $dest_id, 'status' => 1), array());
                    $location_type = isset($location_data['location_type']) ? $location_data['location_type'] : 0;
                    if ($location_type == 2) {
                        //IS PINCODE
                        $query .= " AND pincode='" . $location_data['name'] . "'";
                    } else if ($location_type == 3) {
                        //IS CITY
                        $city_id = check_record_exist(array('name' => $location_data['name']), '', 'city');
                        $query .= " AND city='" . $city_id . "'";
                    } else if ($location_type == 4) {
                        //IS STATE
                        $state_id = check_record_exist(array('name' => $location_data['name']), '', 'state');
                        $query .= " AND state='" . $state_id . "'";
                    } else {
                        //IS COUNTRY
                        $country_id = check_record_exist(array('name' => $location_data['name']), '', 'country');
                        $query .= " AND country='" . $country_id . "'";
                    }
                }
            }

            if (isset($setting['docket_consignee_by_customer']) && $setting['docket_consignee_by_customer'] == 1) {
                if ($post_data['customer_id'] > 0) {
                    $query .= " AND customer_id='" . $post_data['customer_id'] . "'";
                }
            }
        }



        if ($module == 'shipper' && $segment1 == 'docket') {
            //check AUTOCOMPLETE SHIPPERS BY CUSTOMER
            $setting = get_all_app_setting(" AND module_name IN('docket')");
            if ($post_data['customer_id'] > 0) {
                if (isset($setting['docket_shipper_by_customer']) && $setting['docket_shipper_by_customer'] == 1) {
                    $query .= " AND customer_id='" . $post_data['customer_id'] . "'";
                }
            }
        }



        if ($module_name == 'co_vendor') {
            //SHOW CO_COURIER VENDOR EVERYWHERE
            $query .= " AND vendor_type='1'";
        } else if ($module_name == 'inventory_vendor') {
            $query .= " AND vendor_type='3'";
        } else if ($module_name == 'purchase_vendor') {
            $query .= " AND vendor_type IN(1,2)";
        }

        if ($this->user_type == 2 && $module_name == 'vendor') {
            $query .= " AND hide_this_service_in_portal != '1'";
        }

        $session_data = $this->session->userdata('admin_user');
        $this->customer_id = isset($session_data['customer_id']) && $session_data['customer_id'] != "" ? $session_data['customer_id'] : "";
        $admin_role_name = array('software_user');
        if (!in_array($session_data['type'], $admin_role_name) && $module_name == 'product') {
            $query .= " AND hide_in_portal!=1";
        }

        if (isset($this->customer_id) && $this->customer_id != "" && $module_name == 'customer') {
            $query .= " AND id IN (" . $this->customer_id . ")";
        }

        if ($this->user_type != 2) {
            $this->user_id = isset($session_data['id']) && $session_data['id'] != "" ? $session_data['id'] : "";
            //HUB WISE CUSTOMER SUGGEST
            $hub_id_arr = get_user_all_hub($this->user_id);
            if ((isset($hub_id_arr) && is_array($hub_id_arr) && count($hub_id_arr) > 0)) {
                $qry = "SELECT module_id FROM hub_mapping WHERE module_type = 1 AND hub_id IN(" . implode(",", $hub_id_arr) . ") AND status IN(1,2)";
                $qry_exe = $this->db->query($qry);
                $customer_hub_wise = $qry_exe->result_array();
                if (isset($customer_hub_wise) && is_array($customer_hub_wise) && count($customer_hub_wise) > 0) {
                    foreach ($customer_hub_wise as $chwkey => $chwvalue) {
                        $cust_hub_id_arr[$chwvalue['module_id']] = $chwvalue['module_id'];
                    }
                }
            }

            if ($module_name == 'customer') {
                if (isset($cust_hub_id_arr) && is_array($cust_hub_id_arr) && count($cust_hub_id_arr) > 0) {
                    $query .= " AND id IN(" . implode(",", $cust_hub_id_arr) . ")";
                } else {
                    //No Hub Selected For user
                    $query .= " AND id IN(0)";
                }
            }
        }


        if ($segment1 == 'docket') {
            $mode =  isset($post_data['mode']) ? $post_data['mode'] : '';
            if ($mode == 'insert') {
                $query .= " AND status=1";
            } else if ($mode == 'update') {
                $docket_id =  isset($post_data['module_id']) ? $post_data['module_id'] : '';
                if ($docket_id > 0) {
                    $col_mapping = array(
                        'vendor' => 'vendor_id as edit_id',
                        'co_vendor' => 'co_vendor_id as edit_id',
                        'location' => 'CONCAT(origin_id,",",destination_id) as edit_id',
                        'customer' => 'customer_id as edit_id',
                    );
                    if ($module_name != '' && isset($col_mapping[$module_name])) {
                        $docketq = "SELECT " . $col_mapping[$module_name] . " FROM docket WHERE id='" . $docket_id . "'";
                        $docketq_exe = $this->db->query($docketq);
                        $docket_data  = $docketq_exe->row_array();

                        if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
                            $query .= " AND(status=1 OR id IN(" . $docket_data['edit_id'] . "))";
                        } else {
                            $query .= " AND status=1";
                        }
                    }
                }
            }
        }


        //IF CUSTOMER PORTAL - SHOW CUSTOMER SHIPPER AND CONSIGNEE ONLY
        if ($this->user_type == 2) {
            if ($module_name == 'shipper' || $module_name == 'consignee') {
                $query .= " AND customer_id='" . $this->customer_id . "'";
            }
        }


        //create App Setting under Accounts, HIDE INACTIVE CUSTOMER FROM RECEIPTS
        // if setting is TRUE, then dont show inactive Customers in Receipt creation
        if ($module == 'customer' && $segment1 == 'account' && $segment2 == 'receipt') {
            $account_setting = get_all_app_setting(" AND module_name='account'");
            if (isset($account_setting['hide_inactive_customer_from_receipt']) && $account_setting['hide_inactive_customer_from_receipt'] == 1) {
                $query .= " AND status=1";
            }
        }

        if ($this->input->post('phrase') && $query != '') {

            if ($module == 'currency_masters') {
                $query .= " ORDER BY currency_name";
            } else {
                $query .= " ORDER BY name";
            }
            $query =  $query . " LIMIT 10";

            if ($module == 'company' || $module == 'admin_user') {
                $main_db = $this->load->database('main_db', true);
                $name_query = $main_db->query($query);
            } else {
                $name_query = $this->db->query($query);
            }
            $search_data  = $name_query->result_array();
        }
        if (isset($search_data) && is_array($search_data) && count($search_data) > 0) {
            foreach ($search_data as $key => $value) {
                $response[] = array(
                    'name' => $value['text'],
                    'id' => $value['id']
                );
            }
        }
        echo json_encode($response);
    }

    function get_full_data($table_name = '')
    {
        $this->load->helper('frontend_common');
        $post_data = $this->input->post();
        $final_res = array();
        $setting = get_all_app_setting(" AND module_name='docket'");

        if (isset($post_data['id']) && $post_data['id'] > 0) {
            if ($post_data['category'] == 'shipper' && $post_data['sub_category'] == 'customer') {
                if (isset($setting['docket_shipper_by_customer']) && $setting['docket_shipper_by_customer'] == 1) {
                    $table_name = 'customer';
                }
            }
            if ($table_name != '') {
                if (isset($post_data['form']) && $post_data['form'] == 'pickup_request') {
                    if ($table_name == 'pickup') {
                        $table_name = 'pickup_address';
                    }
                    $query = "SELECT * FROM " . $table_name . " WHERE status IN(1,2) AND id=" . $post_data['id'];
                    $query_exe = $this->db->query($query);
                    $final_res  = $query_exe->row_array();
                } else if (isset($post_data['category']) && $post_data['category'] == 'pickup_address') {
                    $table_name = 'pickup_address';
                    $query = "SELECT * FROM " . $table_name . " WHERE status IN(1,2) AND id=" . $post_data['id'];
                    $query_exe = $this->db->query($query);
                    $final_res  = $query_exe->row_array();
                    if (isset($final_res) && is_array($final_res) && count($final_res) > 0) {
                        $final_res['country_code'] = '';
                        $final_res['country_name'] = '';
                        if (isset($final_res['country']) && $final_res['country'] > 0) {
                            $all_country = get_all_country(" AND id='" . $final_res['country'] . "'");
                            $final_res['country_code'] = isset($all_country[$final_res['country']]) ? $all_country[$final_res['country']]['code'] : '';
                            $final_res['country_name'] = isset($all_country[$final_res['country']]) ? $all_country[$final_res['country']]['name'] : '';
                        }
                    }
                } else {
                    $query = "SELECT * FROM " . $table_name . " WHERE status IN(1,2) AND id=" . $post_data['id'];
                    if ($post_data['category'] == 'shipper') {
                        if (isset($setting['docket_shipper_by_customer']) && $setting['docket_shipper_by_customer'] == 1) {
                            // $query .= " AND customer_id='" . $post_data['customer_id'] . "'";
                        }
                        // if (isset($setting['docket_shipper_by_ori']) && $setting['docket_shipper_by_ori'] == 1) {
                        //     $query .= " AND LOWER(country)='" . strtolower($post_data['ori_country']) . "'";
                        // }
                    }
                    if ($post_data['category'] == 'consignee') {
                        if (isset($setting['docket_consignee_by_customer']) && $setting['docket_consignee_by_customer'] == 1) {
                            $query .= " AND customer_id='" . $post_data['customer_id'] . "'";
                        }
                        // if (isset($setting['docket_consignee_by_dest']) && $setting['docket_consignee_by_dest'] == 1) {
                        //     $query .= " AND LOWER(country)='" . strtolower($post_data['dest_country']) . "'";
                        // }
                    }

                    $query_exe = $this->db->query($query);
                    $final_res  = $query_exe->row_array();
                    if (isset($final_res) && is_array($final_res) && count($final_res) > 0) {
                        $final_res['country_code'] = '';
                        $final_res['country_name'] = '';
                        if (isset($final_res['country']) && $final_res['country'] > 0) {
                            $all_country = get_all_country(" AND id='" . $final_res['country'] . "'");
                            $final_res['country_code'] = isset($all_country[$final_res['country']]) ? $all_country[$final_res['country']]['code'] : '';
                            $final_res['country_name'] = isset($all_country[$final_res['country']]) ? $all_country[$final_res['country']]['name'] : '';
                        }
                        if ($post_data['category'] == 'shipper' && $post_data['sub_category'] == 'customer') {
                            if (isset($setting['docket_shipper_by_customer']) && $setting['docket_shipper_by_customer'] == 1) {
                                $address = explode(",",  $final_res['address']);
                                $final_res['address1'] = "";
                                $final_res['address2'] = "";
                                $final_res['address3'] = "";
                                foreach ($address as $key => $value) {
                                    if ($key == 0 || $key == 1) {
                                        $final_res['address1'] .= trim($value) . ",";
                                    } else if ($key == 2 || $key == 3) {
                                        $final_res['address2'] .= trim($value) . ",";
                                    } else {
                                        $final_res['address3'] .= trim($value);
                                    }
                                }
                            }
                        }
                    } else {
                        $final_res['error'] = 'YOU CAN NOT SELECT THIS ' . strtoupper($table_name);
                    }
                }
            }
        }
        echo json_encode($final_res);
    }

    function get_cft_contract($docket_data = array())
    {
        $response_data = array();
        if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
            $post_data = $docket_data;
        } else {
            $post_data = $this->input->post();
        }

        if (isset($post_data) && is_array($post_data) && count($post_data) > 0) {
            $booking_date = date('Y-m-d', strtotime(str_replace("/", "-", $post_data['booking_date'])));
            $customer_id = $post_data['customer_id'] > 0 ? $post_data['customer_id'] : 0;
            $vendor_id = $post_data['vendor_id'] > 0 ? $post_data['vendor_id'] : 0;
            $co_vendor_id = $post_data['co_vendor_id'] > 0 ? $post_data['co_vendor_id'] : 0;
            $product_id = $post_data['product_id'] > 0 ? $post_data['product_id'] : 0;

            $contract_combination = array(
                array('customer_id' => $customer_id, 'vendor_id' => $vendor_id, 'co_vendor_id' => $co_vendor_id, 'product_id' => $product_id),

                array('customer_id' => $customer_id, 'vendor_id' => $vendor_id, 'co_vendor_id' => $co_vendor_id, 'product_id' => 0),
                array('customer_id' => $customer_id, 'vendor_id' => $vendor_id, 'co_vendor_id' => 0, 'product_id' => $product_id),
                array('customer_id' => $customer_id, 'vendor_id' => 0, 'co_vendor_id' => $co_vendor_id, 'product_id' => $product_id),
                array('customer_id' => 0, 'vendor_id' => $vendor_id, 'co_vendor_id' => $co_vendor_id, 'product_id' => $product_id),

                array('customer_id' => $customer_id, 'vendor_id' => $vendor_id, 'co_vendor_id' => 0, 'product_id' => 0),
                array('customer_id' => $customer_id, 'vendor_id' => 0, 'co_vendor_id' => $co_vendor_id, 'product_id' => 0),
                array('customer_id' => $customer_id, 'vendor_id' => 0, 'co_vendor_id' => 0, 'product_id' => $product_id),
                array('customer_id' => 0, 'vendor_id' => $vendor_id, 'co_vendor_id' => $co_vendor_id, 'product_id' => 0),
                array('customer_id' => 0, 'vendor_id' => $vendor_id, 'co_vendor_id' => 0, 'product_id' => $product_id),
                array('customer_id' => 0, 'vendor_id' => 0, 'co_vendor_id' => $co_vendor_id, 'product_id' => $product_id),

                array('customer_id' => $customer_id, 'vendor_id' => 0, 'co_vendor_id' => 0, 'product_id' => 0),
                array('customer_id' => 0, 'vendor_id' => $vendor_id, 'co_vendor_id' => 0, 'product_id' => 0),
                array('customer_id' => 0, 'vendor_id' => 0, 'co_vendor_id' => $co_vendor_id, 'product_id' => 0),
                array('customer_id' => 0, 'vendor_id' => 0, 'co_vendor_id' => 0, 'product_id' => $product_id),

                array('customer_id' => 0, 'vendor_id' => 0, 'co_vendor_id' => 0, 'product_id' => 0),
            );

            foreach ($contract_combination as $key => $value) {
                $query = "SELECT * FROM cft_contracts WHERE status IN(1,2) 
                AND '" . $booking_date . "' BETWEEN effective_min AND effective_max AND billing_type IN(1,3) 
                AND customer_id='" . $value['customer_id'] . "' AND vendor_id=" . $value['vendor_id']
                    . " AND co_vendor_id=" . $value['co_vendor_id'] . " AND product_id=" . $value['product_id'] . " ORDER BY effective_min DESC";
                $query_exe = $this->db->query($query);
                $final_res  = $query_exe->row_array();
                if (isset($final_res) && is_array($final_res) && count($final_res) > 0) {
                    $response_data = $final_res;
                    break;
                }
            }
        }
        if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
            return $response_data;
        } else {
            echo json_encode($response_data);
        }
    }



    function get_purchase_cft_contract($docket_data = array())
    {
        $response_data = array();
        if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
            $post_data = $docket_data;
        } else {
            $post_data = $this->input->post();
        }

        if (isset($post_data) && is_array($post_data) && count($post_data) > 0) {
            $booking_date = date('Y-m-d', strtotime(str_replace("/", "-", $post_data['booking_date'])));
            $vendor_id = $post_data['vendor_id'] > 0 ? $post_data['vendor_id'] : 0;
            $co_vendor_id = $post_data['co_vendor_id'] > 0 ? $post_data['co_vendor_id'] : 0;
            $product_id = $post_data['product_id'] > 0 ? $post_data['product_id'] : 0;

            $contract_combination = array(
                array('vendor_id' => $vendor_id, 'co_vendor_id' => $co_vendor_id, 'product_id' => $product_id),

                array('vendor_id' => $vendor_id, 'co_vendor_id' => $co_vendor_id, 'product_id' => 0),
                array('vendor_id' => $vendor_id, 'co_vendor_id' => 0, 'product_id' => $product_id),
                array('vendor_id' => 0, 'co_vendor_id' => $co_vendor_id, 'product_id' => $product_id),

                array('vendor_id' => $vendor_id, 'co_vendor_id' => 0, 'product_id' => 0),
                array('vendor_id' => 0, 'co_vendor_id' => $co_vendor_id, 'product_id' => 0),
                array('vendor_id' => 0, 'co_vendor_id' => 0, 'product_id' => $product_id),

                array('vendor_id' => 0, 'co_vendor_id' => 0, 'product_id' => 0)
            );

            foreach ($contract_combination as $key => $value) {
                $query = "SELECT * FROM cft_contracts WHERE status IN(1,2) 
                AND '" . $booking_date . "' BETWEEN effective_min AND effective_max  AND billing_type IN(1,2) 
                AND customer_id='" . $value['customer_id'] . "' AND vendor_id=" . $value['vendor_id']
                    . " AND co_vendor_id=" . $value['co_vendor_id'] . " AND product_id=" . $value['product_id'] . " ORDER BY effective_min DESC";
                $query_exe = $this->db->query($query);
                $final_res  = $query_exe->row_array();
                if (isset($final_res) && is_array($final_res) && count($final_res) > 0) {
                    $response_data = $final_res;
                    break;
                } else {
                    $query = "SELECT * FROM cft_contracts WHERE status IN(1,2) 
                AND '" . $booking_date . "' BETWEEN effective_min AND effective_max  AND billing_type IN(1,2) 
                AND customer_id='0' AND vendor_id=" . $value['vendor_id']
                        . " AND co_vendor_id=" . $value['co_vendor_id'] . " AND product_id=" . $value['product_id'] . " ORDER BY effective_min DESC";
                    $query_exe = $this->db->query($query);
                    $final_res  = $query_exe->row_array();
                    if (isset($final_res) && is_array($final_res) && count($final_res) > 0) {
                        $response_data = $final_res;
                        break;
                    }
                }
            }
        }
        if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
            return $response_data;
        } else {
            echo json_encode($response_data);
        }
    }



    function get_vendor_data($post = array())
    {
        $this->load->helper('frontend_common');
        $response_data = array();

        if (isset($post) && is_array($post) && count($post) > 0) {
            $post_data = $post;
        } else {
            $post_data = $this->input->post();
        }

        $vendor_id = $post_data['vendor_id'] > 0 ? $post_data['vendor_id'] : 0;
        $query = "SELECT * FROM vendor WHERE status IN(1,2) AND id=" . $vendor_id;
        $query_exe = $this->db->query($query);
        if ($query_exe->num_rows() > 0) {
            $response_data  = $query_exe->row_array();
        }

        if (isset($response_data) && is_array($response_data) && count($response_data) > 0) {
            $setting = get_app_setting(" AND module_name IN('master')");
            if (isset($setting['default_vendor_service_wise']) && $setting['default_vendor_service_wise'] == 1) {
                $all_co_vendor = get_all_co_vendor(" AND id =" . $response_data['co_vendor_id']);
                $response_data['co_vendor_code'] = isset($all_co_vendor[$response_data['co_vendor_id']]) ? strtoupper($all_co_vendor[$response_data['co_vendor_id']]['code']) : '';
                $response_data['co_vendor_name'] = isset($all_co_vendor[$response_data['co_vendor_id']]) ? strtoupper($all_co_vendor[$response_data['co_vendor_id']]['name']) : '';
            }
            if (isset($setting['default_product_service_wise']) && $setting['default_product_service_wise'] == 1) {
                $all_product = get_all_product(" AND id =" . $response_data['product_id']);
                $response_data['product_code'] = isset($all_product[$response_data['product_id']]) ? strtoupper($all_product[$response_data['product_id']]['code']) : '';
                $response_data['product_name'] = isset($all_product[$response_data['product_id']]) ? strtoupper($all_product[$response_data['product_id']]['name']) : '';
            }
            if (isset($response_data['mode_id']) && $response_data['mode_id'] > 0) {
                $all_mode = get_all_mode(" AND id = " . $response_data['mode_id']);
                $response_data['mode_name'] = isset($all_mode[$response_data['mode_id']]) ? strtoupper($all_mode[$response_data['mode_id']]['name']) : '';
            }
        }

        if (isset($response_data['label_head_id']) && $response_data['label_head_id'] == '59') {
            $co_vendor_id = $post_data['co_vendor_id'] > 0 ? $post_data['co_vendor_id'] : 0;
            $query = "SELECT id,label_head_id FROM co_vendor WHERE status IN(1,2) AND id=" . $co_vendor_id;
            $query_exe = $this->db->query($query);
            $co_vendor_data = $query_exe->row_array();
            $response_data['label_head_id'] = isset($co_vendor_data['label_head_id']) ? $co_vendor_data['label_head_id'] : 0;
        }

        if (isset($post) && is_array($post) && count($post) > 0) {
            return $response_data;
        } else {
            echo json_encode($response_data);
        }
    }

    function get_purchase_location_zone($docket_data = array())
    {
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');
        $response_data = array();
        if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
            $post_data = $docket_data;
        } else {
            $post_data = $this->input->post();
        }

        if (isset($post_data) && is_array($post_data) && count($post_data) > 0) {
            $booking_date = date('Y-m-d', strtotime(str_replace("/", "-", $post_data['booking_date'])));
            $customer_id = $post_data['customer_id'] > 0 ? $post_data['customer_id'] : 0;
            $vendor_id = $post_data['vendor_id'] > 0 ? $post_data['vendor_id'] : 0;
            $co_vendor_id = $post_data['co_vendor_id'] > 0 ? $post_data['co_vendor_id'] : 0;
            $origin_id = $post_data['origin_id'] > 0 ? $post_data['origin_id'] : 0;
            $destination_id = $post_data['destination_id'] > 0 ? $post_data['destination_id'] : 0;
            $type = $post_data['type'] > 0 ? $post_data['type'] : 0;
            $shipper_country = $post_data['shipper_country'] != '' ? strtolower($post_data['shipper_country']) : '';
            $consignee_country = $post_data['consignee_country'] != '' ?  strtolower($post_data['consignee_country']) : '';

            $all_country = get_all_country("", "code");
            if (is_numeric($shipper_country)) {
                $ori_coun_id = $shipper_country;
            } else {
                $ori_coun_id = isset($all_country[$shipper_country]) ? $all_country[$shipper_country]['id'] : '';
            }

            if (is_numeric($consignee_country)) {
                $dest_coun_id = $consignee_country;
            } else {
                $dest_coun_id = isset($all_country[$consignee_country]) ? $all_country[$consignee_country]['id'] : '';
            }

            $contract_combination_location = array(
                array('customer_id' => $customer_id, 'co_vendor_id' => $co_vendor_id, 'vendor_id' => $vendor_id),
                array('customer_id' => $customer_id, 'co_vendor_id' => $co_vendor_id, 'vendor_id' => 0),
                array('customer_id' => $customer_id, 'co_vendor_id' => 0, 'vendor_id' => $vendor_id),
                array('customer_id' => 0, 'co_vendor_id' => $co_vendor_id, 'vendor_id' => $vendor_id),
                array('customer_id' => $customer_id, 'co_vendor_id' => 0, 'vendor_id' => 0),
                array('customer_id' => 0, 'co_vendor_id' => $co_vendor_id, 'vendor_id' => 0),
                array('customer_id' => 0, 'co_vendor_id' => 0, 'vendor_id' => $vendor_id),
                array('customer_id' => 0, 'co_vendor_id' => 0, 'vendor_id' => 0),
            );

            if ($type == 3) {
                $ori_setting = "fetch_customer_ori_zone";
                $dest_setting = "fetch_customer_dest_zone";
            } else if ($type == 2) {
                $ori_setting = "fetch_vendor_ori_zone";
                $dest_setting = "fetch_vendor_dest_zone";
            }


            if ($vendor_id > 0) {
                //GET SERVICE ZONE SETTING
                $query = "SELECT id,fetch_zone_from_shipper,fetch_customer_ori_zone,fetch_customer_ori_zone_type,
                fetch_vendor_ori_zone,fetch_vendor_ori_zone_type,fetch_customer_dest_zone,fetch_customer_dest_zone_type,
                fetch_vendor_dest_zone,fetch_vendor_dest_zone_type FROM vendor WHERE status IN(1,2) AND id='" . $vendor_id . "'";
                $query_exe = $this->db->query($query);
                $vendor_setting  = $query_exe->row_array();
                if (isset($dest_setting) && isset($vendor_setting[$dest_setting]) && $vendor_setting[$dest_setting] == 1) {
                    $fetch_type = isset($vendor_setting[$dest_setting . '_type']) && $vendor_setting[$dest_setting . '_type'] > 0 ? $vendor_setting[$dest_setting . '_type'] : 1;
                    if ($fetch_type == 1) {
                        //GET SHIPPER STATE
                        $shipper_state = isset($post_data['shipper_state']) ? $post_data['shipper_state'] : '';
                        if ($shipper_state != '') {
                            $shipper_state = strtolower(trim($shipper_state));
                            $query = "SELECT id,code FROM state WHERE status IN(1,2) AND LOWER(name)='" . $shipper_state . "'";
                            $query_exe = $this->db->query($query);
                            $shipper_state_data  = $query_exe->row_array();
                            $zone_state = isset($shipper_state_data['id']) ? $shipper_state_data['id'] : 0;
                        }
                    } else {
                        //GET SHIPPER PINCODE
                        $consignee_pincode = isset($post_data['consignee_pincode']) ? $post_data['consignee_pincode'] : '';
                        if ($consignee_pincode != '') {
                            $consignee_pincode = strtolower(trim($consignee_pincode));
                            $query = "SELECT id,name FROM pincode WHERE status IN(1,2) 
                            AND LOWER(name)='" . $consignee_pincode . "' AND country_id='" . $dest_coun_id . "'";
                            $query_exe = $this->db->query($query);
                            $consignee_pincode_data  = $query_exe->row_array();
                            $zone_pincode = isset($consignee_pincode_data['id']) ? $consignee_pincode_data['id'] : 0;
                        }
                    }
                }
            }

            $table_name = "";
            $dest_append = "";
            if ($zone_state != '' && $zone_state > 0) {
                $master_id = $zone_state;
                $table_name = "state";
            } else if ($zone_pincode != '' && $zone_pincode > 0) {
                $master_id = $zone_pincode;
                $table_name = "pincode";
                $dest_append = " AND l.country_id='" . $dest_coun_id . "'";
            } else if ($destination_id > 0) {
                $master_id = $destination_id;
                $table_name = "location";
            }

            if ($table_name != "") {
                foreach ($contract_combination_location as $ckey => $cvalue) {
                    $ozoneq = "SELECT z.name,z.id,zm.service_type as map_id FROM " . $table_name . " l
                    JOIN " . $table_name . "_zone_map zm ON(l.id=zm." . $table_name . "_id)
                    JOIN zone z ON(z.id=zm.zone_id)
                    WHERE l.status IN(1,2) AND zm.status IN(1,2) AND z.status IN(1,2) 
                    AND l.id='" . $master_id . "'
                    AND (zm.customer_id='" . $cvalue['customer_id'] . "' OR zm.customer_id=0)
                     AND (zm.vendor_id='" . $cvalue['vendor_id'] . "'  OR zm.vendor_id=0)
                     AND (zm.co_vendor_id='" . $cvalue['co_vendor_id'] . "' OR zm.co_vendor_id=0)
                     AND (zm.billing_type=1 OR zm.billing_type='" . $type . "')
                     " . $dest_append . " AND '" . $booking_date . "' BETWEEN zm.eff_min_date AND zm.eff_max_date ORDER BY zm.eff_min_date DESC";
                    $ozoneq_exe = $this->db->query($ozoneq);
                    $destination_data  = $ozoneq_exe->row_array();

                    if (isset($destination_data) && is_array($destination_data) && count($destination_data) > 0) {
                        break;
                    }
                }
            }



            if ($vendor_id > 0) {
                //GET SERVICE ZONE SETTING
                $query = "SELECT id,fetch_zone_from_shipper,fetch_customer_ori_zone,fetch_customer_ori_zone_type,
                fetch_vendor_ori_zone,fetch_vendor_ori_zone_type,fetch_customer_dest_zone,fetch_customer_dest_zone_type,
                fetch_vendor_dest_zone,fetch_vendor_dest_zone_type FROM vendor WHERE status IN(1,2) AND id='" . $vendor_id . "'";
                $query_exe = $this->db->query($query);
                $vendor_setting  = $query_exe->row_array();
                if (isset($ori_setting) && isset($vendor_setting[$ori_setting]) && $vendor_setting[$ori_setting] == 1) {
                    $fetch_type = isset($vendor_setting[$ori_setting . '_type']) && $vendor_setting[$ori_setting . '_type'] > 0 ? $vendor_setting[$ori_setting . '_type'] : 1;
                    if ($fetch_type == 1) {
                        //GET SHIPPER STATE
                        $shipper_state = isset($post_data['shipper_state']) ? $post_data['shipper_state'] : '';
                        if ($shipper_state != '') {
                            $shipper_state = strtolower(trim($shipper_state));
                            $query = "SELECT id,code FROM state WHERE status IN(1,2) AND LOWER(name)='" . $shipper_state . "'";
                            $query_exe = $this->db->query($query);
                            $shipper_state_data  = $query_exe->row_array();
                            $zone_state = isset($shipper_state_data['id']) ? $shipper_state_data['id'] : 0;
                        }
                    } else {
                        //GET SHIPPER PINCODE
                        $shipper_pincode = isset($post_data['shipper_pincode']) ? $post_data['shipper_pincode'] : '';
                        if ($shipper_pincode != '') {
                            $shipper_pincode = strtolower(trim($shipper_pincode));
                            $query = "SELECT id,name FROM pincode WHERE 
                            status IN(1,2) AND LOWER(name)='" . $shipper_pincode . "'  AND country_id='" . $ori_coun_id . "'";
                            $query_exe = $this->db->query($query);
                            $shipper_pincode_data  = $query_exe->row_array();
                            $zone_pincode = isset($shipper_pincode_data['id']) ? $shipper_pincode_data['id'] : 0;
                        }
                    }
                }
            }

            $ori_append = "";
            $table_name = "";
            if ($zone_state != '' && $zone_state > 0) {
                $master_id = $zone_state;
                $table_name = "state";
            } else if ($zone_pincode != '' && $zone_pincode > 0) {
                $master_id = $zone_pincode;
                $table_name = "pincode";
                $ori_append = " AND l.country_id='" . $ori_coun_id . "'";
            } else if ($origin_id > 0) {
                $master_id = $origin_id;
                $table_name = "location";
            }
            if ($table_name != "") {
                foreach ($contract_combination_location as $ckey => $cvalue) {
                    $ozoneq = "SELECT z.name,z.id,zm.service_type as map_id FROM " . $table_name . " l
                    JOIN " . $table_name . "_zone_map zm ON(l.id=zm." . $table_name . "_id)
                    JOIN zone z ON(z.id=zm.zone_id)
                    WHERE l.status IN(1,2) AND zm.status IN(1,2) AND z.status IN(1,2) 
                    AND l.id='" . $master_id . "'
                    AND (zm.customer_id='" . $cvalue['customer_id'] . "' OR zm.customer_id=0)
                     AND (zm.vendor_id='" . $cvalue['vendor_id'] . "'  OR zm.vendor_id=0)
                     AND (zm.co_vendor_id='" . $cvalue['co_vendor_id'] . "' OR zm.co_vendor_id=0)
                     AND (zm.billing_type=1 OR zm.billing_type='" . $type . "')
                     " . $ori_append . " AND '" . $booking_date . "' BETWEEN zm.eff_min_date AND zm.eff_max_date ORDER BY zm.eff_min_date DESC";
                    $ozoneq_exe = $this->db->query($ozoneq);
                    $origin_data  = $ozoneq_exe->row_array();

                    if (isset($origin_data) && is_array($origin_data) && count($origin_data) > 0) {
                        break;
                    }
                }
            }
        }

        $response_data = array(
            'dest_zone_id' => isset($destination_data['id']) ? $destination_data['id'] : '',
            'dest_zone_name' => isset($destination_data['name']) ? $destination_data['name'] : '',
            'dest_zone_service_type' => isset($destination_data['map_id']) ? $destination_data['map_id'] : '',
            'ori_zone_id' => isset($origin_data['id']) ? $origin_data['id'] : '',
            'ori_zone_name' => isset($origin_data['name']) ? $origin_data['name'] : '',
            'ori_zone_service_type' => isset($origin_data['map_id']) ? $origin_data['map_id'] : '',
        );


        if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
            return $response_data;
        } else {
            echo json_encode($response_data);
        }
    }
    function get_origin_dest_zone($docket_data = array())
    {
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');
        $response_data = array();
        if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
            $post_data = $docket_data;
        } else {
            $post_data = $this->input->post();
        }
        $setting = get_all_app_setting(" AND module_name='docket'");

        if (isset($post_data) && is_array($post_data) && count($post_data) > 0) {
            $booking_date = date('Y-m-d', strtotime(str_replace("/", "-", $post_data['booking_date'])));
            $customer_id = $post_data['customer_id'] > 0 ? $post_data['customer_id'] : 0;
            $vendor_id = $post_data['vendor_id'] > 0 ? $post_data['vendor_id'] : 0;
            $co_vendor_id = $post_data['co_vendor_id'] > 0 ? $post_data['co_vendor_id'] : 0;
            $origin_id = $post_data['origin_id'] > 0 ? $post_data['origin_id'] : 0;
            $destination_id = $post_data['destination_id'] > 0 ? $post_data['destination_id'] : 0;
            $type = $post_data['type'] > 0 ? $post_data['type'] : 0;
            $shipper_country = $post_data['shipper_country'] != '' ? strtolower($post_data['shipper_country']) : '';
            $consignee_country = $post_data['consignee_country'] != '' ?  strtolower($post_data['consignee_country']) : '';

            $all_country = get_all_country("", "code");
            if (is_numeric($shipper_country)) {
                $ori_coun_id = $shipper_country;
            } else {
                $ori_coun_id = isset($all_country[$shipper_country]) ? $all_country[$shipper_country]['id'] : '';
            }

            if (is_numeric($consignee_country)) {
                $dest_coun_id = $consignee_country;
            } else {
                $dest_coun_id = isset($all_country[$consignee_country]) ? $all_country[$consignee_country]['id'] : '';
            }


            $contract_combination_location = array(
                array('customer_id' => $customer_id, 'co_vendor_id' => $co_vendor_id, 'vendor_id' => $vendor_id, 'billing_type' => $type),
                array('customer_id' => $customer_id, 'co_vendor_id' => $co_vendor_id, 'vendor_id' => 0, 'billing_type' => $type),
                array('customer_id' => $customer_id, 'co_vendor_id' => 0, 'vendor_id' => $vendor_id, 'billing_type' => $type),
                array('customer_id' => $customer_id, 'co_vendor_id' => 0, 'vendor_id' => 0, 'billing_type' => $type),
                array('customer_id' => 0, 'co_vendor_id' => $co_vendor_id, 'vendor_id' => $vendor_id, 'billing_type' => $type),
                array('customer_id' => 0, 'co_vendor_id' => $co_vendor_id, 'vendor_id' => 0, 'billing_type' => $type),
                array('customer_id' => 0, 'co_vendor_id' => 0, 'vendor_id' => $vendor_id, 'billing_type' => $type),
                array('customer_id' => 0, 'co_vendor_id' => 0, 'vendor_id' => 0, 'billing_type' => $type),

                array('customer_id' => $customer_id, 'co_vendor_id' => $co_vendor_id, 'vendor_id' => $vendor_id, 'billing_type' => 1),
                array('customer_id' => $customer_id, 'co_vendor_id' => $co_vendor_id, 'vendor_id' => 0, 'billing_type' => 1),
                array('customer_id' => $customer_id, 'co_vendor_id' => 0, 'vendor_id' => $vendor_id, 'billing_type' => 1),
                array('customer_id' => $customer_id, 'co_vendor_id' => 0, 'vendor_id' => 0, 'billing_type' => 1),
                array('customer_id' => 0, 'co_vendor_id' => $co_vendor_id, 'vendor_id' => $vendor_id, 'billing_type' => 1),
                array('customer_id' => 0, 'co_vendor_id' => $co_vendor_id, 'vendor_id' => 0, 'billing_type' => 1),
                array('customer_id' => 0, 'co_vendor_id' => 0, 'vendor_id' => $vendor_id, 'billing_type' => 1),
                array('customer_id' => 0, 'co_vendor_id' => 0, 'vendor_id' => 0, 'billing_type' => 1),
            );


            if ($type == 3) {
                $ori_setting = "fetch_customer_ori_zone";
                $dest_setting = "fetch_customer_dest_zone";
            } else if ($type == 2) {
                $ori_setting = "fetch_vendor_ori_zone";
                $dest_setting = "fetch_vendor_dest_zone";
            }
            if ($vendor_id > 0) {
                //GET SERVICE ZONE SETTING
                $query = "SELECT id,fetch_zone_from_shipper,fetch_customer_ori_zone,fetch_customer_ori_zone_type,
                fetch_vendor_ori_zone,fetch_vendor_ori_zone_type,fetch_customer_dest_zone,fetch_customer_dest_zone_type,
                fetch_vendor_dest_zone,fetch_vendor_dest_zone_type,lane_wise_zone_mapping FROM vendor WHERE status IN(1,2) AND id='" . $vendor_id . "'";
                $query_exe = $this->db->query($query);
                $vendor_setting  = $query_exe->row_array();

                if (isset($vendor_setting['fetch_zone_from_shipper']) && $vendor_setting['fetch_zone_from_shipper'] == 1) {
                    $shipper_country = isset($post_data['shipper_country']) ? $post_data['shipper_country'] : '';
                    if ($shipper_country != '') {
                        $shipper_country = strtolower(trim($shipper_country));

                        $query = "SELECT id,code FROM location WHERE status IN(1,2) AND LOWER(code)='" . $shipper_country . "' AND location_type IN(1,5)";
                        $query_exe = $this->db->query($query);
                        $shipper_location  = $query_exe->row_array();
                    }

                    $zone_location = isset($shipper_location['id']) ? $shipper_location['id'] : 0;
                } else if (isset($ori_setting) && isset($vendor_setting[$ori_setting]) && $vendor_setting[$ori_setting] == 1) {
                    $fetch_type = isset($vendor_setting[$ori_setting . '_type']) && $vendor_setting[$ori_setting . '_type'] > 0 ? $vendor_setting[$ori_setting . '_type'] : 1;
                    if ($fetch_type == 1) {
                        //GET SHIPPER STATE
                        $shipper_state = isset($post_data['shipper_state']) ? $post_data['shipper_state'] : '';
                        if ($shipper_state != '') {
                            $shipper_state = strtolower(trim($shipper_state));
                            $query = "SELECT id,code FROM state WHERE status IN(1,2) AND LOWER(name)='" . $shipper_state . "'";
                            $query_exe = $this->db->query($query);
                            $shipper_state_data  = $query_exe->row_array();
                            $zone_state = isset($shipper_state_data['id']) ? $shipper_state_data['id'] : 0;
                        }
                    } else {
                        //GET SHIPPER PINCODE
                        $shipper_pincode = isset($post_data['shipper_pincode']) ? $post_data['shipper_pincode'] : '';
                        if ($shipper_pincode != '') {
                            $shipper_pincode = strtolower(trim($shipper_pincode));
                            $query = "SELECT id,name FROM pincode WHERE 
                            status IN(1,2) AND LOWER(name)='" . $shipper_pincode . "'  AND country_id='" . $ori_coun_id . "'";
                            $query_exe = $this->db->query($query);
                            $shipper_pincode_data  = $query_exe->row_array();
                            $zone_pincode = isset($shipper_pincode_data['id']) ? $shipper_pincode_data['id'] : 0;
                        }
                    }
                } else if (isset($setting['zone_from_shipper_consignee_pincode']) && $setting['zone_from_shipper_consignee_pincode'] == 1) {
                    $shipper_pincode = isset($post_data['shipper_pincode']) ? $post_data['shipper_pincode'] : '';
                    if ($shipper_pincode != '') {
                        $shipper_pincode = strtolower(trim($shipper_pincode));

                        $query = "SELECT id,code FROM location WHERE status IN(1,2) AND LOWER(code)='" . $shipper_pincode . "' AND location_type=2";
                        $query_exe = $this->db->query($query);
                        $shipper_location  = $query_exe->row_array();
                    }

                    $zone_location = isset($shipper_location['id']) ? $shipper_location['id'] : 0;
                } else {
                    $zone_location = $origin_id;
                }
            } else {
                $zone_location = $origin_id;
            }
            $ori_append = "";
            $table_name = "";
            if ($zone_location != '' && $zone_location > 0) {
                $master_id = $zone_location;
                $table_name = "location";
            } else if ($zone_state != '' && $zone_state > 0) {
                $master_id = $zone_state;
                $table_name = "state";
            } else if ($zone_pincode != '' && $zone_pincode > 0) {
                $master_id = $zone_pincode;
                $table_name = "pincode";
                $ori_append = " AND l.country_id='" . $ori_coun_id . "'";
            }
            if ($table_name != "") {
                foreach ($contract_combination_location as $ckey => $cvalue) {
                    $ozoneq = "SELECT z.name,z.id,zm.service_type as map_id FROM " . $table_name . " l
                        JOIN " . $table_name . "_zone_map zm ON(l.id=zm." . $table_name . "_id)
                        JOIN zone z ON(z.id=zm.zone_id)
                        WHERE l.status IN(1,2) AND zm.status IN(1) AND z.status IN(1) AND l.id='" . $master_id . "'
                        AND (zm.customer_id='" . $cvalue['customer_id'] . "')
                        AND (zm.vendor_id='" . $cvalue['vendor_id'] . "')
                        AND (zm.co_vendor_id='" . $cvalue['co_vendor_id'] . "')"
                        //AND (zm.billing_type=1 OR zm.billing_type='" . $type . "')
                        . " AND zm.billing_type='" . $cvalue['billing_type'] . "'"
                        . $ori_append  . "  AND '" . $booking_date . "' BETWEEN zm.eff_min_date AND zm.eff_max_date ORDER BY zm.eff_min_date DESC";
                    $ozoneq_exe = $this->db->query($ozoneq);
                    $origin_data  = $ozoneq_exe->row_array();

                    if (isset($origin_data) && is_array($origin_data) && count($origin_data) > 0) {
                        break;
                    }
                }
            }

            if (isset($dest_setting) && isset($vendor_setting[$dest_setting]) && $vendor_setting[$dest_setting] == 1) {
                $fetch_type = isset($vendor_setting[$dest_setting . '_type']) && $vendor_setting[$dest_setting . '_type'] > 0 ? $vendor_setting[$dest_setting . '_type'] : 1;
                if ($fetch_type == 1) {
                    //GET CONSIGNEE STATE
                    $consignee_state = isset($post_data['consignee_state']) ? $post_data['consignee_state'] : '';
                    if ($consignee_state != '') {
                        $consignee_state = strtolower(trim($consignee_state));
                        $query = "SELECT id,code FROM state WHERE status IN(1,2) AND LOWER(name)='" . $consignee_state . "'";
                        $query_exe = $this->db->query($query);
                        $consignee_state_data  = $query_exe->row_array();
                        $zone_dest_state = isset($consignee_state_data['id']) ? $consignee_state_data['id'] : 0;
                    }
                } else {
                    //GET CONSIGNEE PINCODE
                    $consignee_pincode = isset($post_data['consignee_pincode']) ? $post_data['consignee_pincode'] : '';
                    if ($consignee_pincode != '') {
                        $consignee_pincode = strtolower(trim($consignee_pincode));
                        $query = "SELECT id,name FROM pincode WHERE status IN(1,2) 
                        AND LOWER(name)='" . $consignee_pincode . "' AND country_id='" . $dest_coun_id . "'";
                        $query_exe = $this->db->query($query);
                        $consignee_pincode_data  = $query_exe->row_array();
                        $zone_dest_pincode = isset($consignee_pincode_data['id']) ? $consignee_pincode_data['id'] : 0;
                    }
                }
            } else if (isset($setting['zone_from_shipper_consignee_pincode']) && $setting['zone_from_shipper_consignee_pincode'] == 1) {
                $consignee_pincode = isset($post_data['consignee_pincode']) ? $post_data['consignee_pincode'] : '';
                if ($consignee_pincode != '') {
                    $consignee_pincode = strtolower(trim($consignee_pincode));

                    $query = "SELECT id,code FROM location WHERE status IN(1,2) AND LOWER(code)='" . $consignee_pincode . "' AND location_type=2";
                    $query_exe = $this->db->query($query);
                    $consignee_location  = $query_exe->row_array();
                }

                $zone_dest_location = isset($consignee_location['id']) ? $consignee_location['id'] : 0;
            } else {
                $zone_dest_location = $destination_id;
            }

            $table_name = "";
            $dest_append = "";
            if ($zone_dest_location != '' && $zone_dest_location > 0) {
                $master_id = $zone_dest_location;
                $table_name = "location";
            } else if ($zone_dest_state != '' && $zone_dest_state > 0) {
                $master_id = $zone_dest_state;
                $table_name = "state";
            } else if ($zone_dest_pincode != '' && $zone_dest_pincode > 0) {
                $master_id = $zone_dest_pincode;
                $table_name = "pincode";
                $dest_append = " AND l.country_id='" . $dest_coun_id . "'";
            }
            if ($table_name != "") {
                foreach ($contract_combination_location as $ckey => $cvalue) {
                    //GET DESTINATION ZONE
                    $dzoneq = "SELECT z.name,z.id,zm.service_type as map_id FROM " . $table_name . " l
                    JOIN " . $table_name . "_zone_map zm ON(l.id=zm." . $table_name . "_id)
                    JOIN zone z ON(z.id=zm.zone_id)
                    WHERE l.status IN(1,2) AND zm.status IN(1) AND z.status IN(1) AND l.id='" . $master_id . "'
                    AND (zm.customer_id='" . $cvalue['customer_id'] . "')
                    AND (zm.vendor_id='" . $cvalue['vendor_id'] . "')
                    AND (zm.co_vendor_id='" . $cvalue['co_vendor_id'] . "')"
                        //AND (zm.billing_type=1 OR zm.billing_type='" . $type . "')
                        . " AND zm.billing_type='" . $cvalue['billing_type'] . "'"
                        . $dest_append . " AND '" . $booking_date . "' BETWEEN zm.eff_min_date AND zm.eff_max_date ORDER BY zm.eff_min_date DESC";
                    $dzoneq_exe = $this->db->query($dzoneq);
                    $destination_data  = $dzoneq_exe->row_array();
                    if (isset($destination_data) && is_array($destination_data) && count($destination_data) > 0) {
                        break;
                    }
                }
            }
        }
        $orig_hubq = '';
        //GET ORIGIN AND DESTINATION HUB
        if (isset($setting['fetch_origin_hub_master']) && $setting['fetch_origin_hub_master'] == 1) {
            $orig_hubq = "SELECT id,franchise_id as hub_id FROM `location` WHERE id='" . $origin_id . "' AND status IN(1,2)";
        } else  if ($customer_id > 0) {
            $orig_hubq = "SELECT id,origin_hub_id as hub_id FROM `customer` WHERE id='" . $customer_id . "' AND status IN(1,2)";
        }


        if ($orig_hubq != '') {
            $orig_hubq_exe = $this->db->query($orig_hubq);
            $ori_hub_data =  $orig_hubq_exe->row_array();
        }

        $dest_hubq = "SELECT id,franchise_id as hub_id FROM `location` WHERE id='" . $destination_id . "' AND status IN(1,2)";
        $dest_hubq_exe = $this->db->query($dest_hubq);
        $dest_hub_data =  $dest_hubq_exe->row_array();

        if (isset($vendor_setting['lane_wise_zone_mapping']) && $vendor_setting['lane_wise_zone_mapping'] == 1) {
            $shipper_state = isset($post_data['shipper_state']) ? $post_data['shipper_state'] : '';
            $shipper_city = isset($post_data['shipper_city']) ? $post_data['shipper_city'] : '';
            $consignee_state = isset($post_data['consignee_state']) ? $post_data['consignee_state'] : '';
            $consignee_city = isset($post_data['consignee_city']) ? $post_data['consignee_city'] : '';

            $squery = "SELECT c.id,c.name,c.code,c.country_id,c.is_north_east_and_jk FROM state c WHERE status IN(1,2) AND name='" . strtolower(escape_string($shipper_state)) . "'";
            $squery_exe = $this->db->query($squery);
            $shipper_state_data  = $squery_exe->row_array();

            $csquery = "SELECT cs.id,cs.name,cs.code,cs.country_id,cs.is_north_east_and_jk FROM state cs WHERE status IN(1,2) AND name='" . strtolower(escape_string($consignee_state)) . "'";
            $csquery_exe = $this->db->query($csquery);
            $consignee_state_data  = $csquery_exe->row_array();

            $scquery = "SELECT sc.id,sc.name,sc.code,sc.is_master_city FROM city sc WHERE status IN(1,2) AND name='" . strtolower(escape_string($shipper_city)) . "'";
            $scquery_exe = $this->db->query($scquery);
            $shipper_city_data  = $scquery_exe->row_array();

            $ccquery = "SELECT cc.id,cc.name,cc.code,cc.is_master_city FROM city cc WHERE status IN(1,2) AND name='" . strtolower(escape_string($consignee_city)) . "'";
            $ccquery_exe = $this->db->query($ccquery);
            $consignee_city_data  = $ccquery_exe->row_array();

            $zone_data  = get_all_zone("", "code");

            if (isset($shipper_city) && isset($consignee_city) && strtolower($shipper_city) == strtolower($consignee_city)) {
                if (isset($zone_data['a'])) {
                    $destination_data['id'] = isset($zone_data['a']) ? $zone_data['a']['id'] : "";
                    $destination_data['name'] = isset($zone_data['a']) ? $zone_data['a']['name'] : "";
                }
            } else if (isset($shipper_state) && isset($consignee_state) && strtolower($shipper_state) == strtolower($consignee_state)) {
                if (isset($zone_data['b'])) {
                    $destination_data['id'] = isset($zone_data['b']) ? $zone_data['b']['id'] : "";
                    $destination_data['name'] = isset($zone_data['b']) ? $zone_data['b']['name'] : "";
                }
            } else if (
                isset($shipper_city_data['is_master_city']) && isset($consignee_city_data['is_master_city']) &&
                $shipper_city_data['is_master_city'] == 1 && $consignee_city_data['is_master_city'] == 1
            ) {
                if (isset($zone_data['c'])) {
                    $destination_data['id'] = isset($zone_data['c']) ? $zone_data['c']['id'] : "";
                    $destination_data['name'] = isset($zone_data['c']) ? $zone_data['c']['name'] : "";
                }
            } else if (isset($consignee_state_data) && $consignee_state_data['is_north_east_and_jk'] != 1) {
                if (isset($zone_data['d'])) {
                    $destination_data['id'] = isset($zone_data['d']) ? $zone_data['d']['id'] : "";
                    $destination_data['name'] = isset($zone_data['d']) ? $zone_data['d']['name'] : "";
                }
            } else if (isset($consignee_state_data) && $consignee_state_data['is_north_east_and_jk'] == 1) {
                if (isset($zone_data['e'])) {
                    $destination_data['id'] = isset($zone_data['e']) ? $zone_data['e']['id'] : "";
                    $destination_data['name'] = isset($zone_data['e']) ? $zone_data['e']['name'] : "";
                }
            }
        }

        $response_data = array(
            'ori_zone_id' => isset($origin_data['id']) ? $origin_data['id'] : '',
            'ori_zone_name' => isset($origin_data['name']) ? $origin_data['name'] : '',
            'ori_zone_service_type' => isset($origin_data['map_id']) ? $origin_data['map_id'] : '',
            'dest_zone_id' => isset($destination_data['id']) ? $destination_data['id'] : '',
            'dest_zone_name' => isset($destination_data['name']) ? $destination_data['name'] : '',
            'dest_zone_service_type' => isset($destination_data['map_id']) ? $destination_data['map_id'] : '',
            'ori_hub_id' => isset($ori_hub_data['hub_id']) ? $ori_hub_data['hub_id'] : '',
            'dest_hub_id' => isset($dest_hub_data['hub_id']) ? $dest_hub_data['hub_id'] : '',
        );


        if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
            return $response_data;
        } else {
            echo json_encode($response_data);
        }
    }
    function get_freight_amount($post = array())
    {

        //TEST COMMIT CHECK
        $total_freight_amt = 0;

        if (isset($post) && is_array($post) && count($post) > 0) {
            $post_data = $post;
        } else {
            $post_data = $this->input->post();
        }
        $setting_data = get_all_app_setting(" AND module_name IN('docket')");

        if (isset($post_data) && is_array($post_data) && count($post_data) > 0) {
            $customer_contract_id = $post_data['customer_contract_id'] > 0 ? $post_data['customer_contract_id'] : 0;
            $chargeable_wt_total = $post_data['chargeable_wt_total'] > 0 ? $post_data['chargeable_wt_total'] : 0;
            $vendor_id = $post_data['vendor_id'] > 0 ? $post_data['vendor_id'] : 0;
            $chargeable_wt_total = (float)$chargeable_wt_total;
            //FIELD USE FOR BOX WISE CONTRACT TYPE
            $total_pcs = $post_data['total_pcs'] > 0 ? $post_data['total_pcs'] : 0;
            $total_pcs = (int)$total_pcs;
            // "CREATE SETTING ""sINGLE PC RATE WISE RATE"" IN SERVICE MASTER.
            // For a 3 pc shipemnt chargeweight is 75. 
            // (Chargeable weight  / pc ) * contracted amount * PC"

            if ($vendor_id > 0) {
                $query = "SELECT id,single_pc_rate_wise_rate FROM vendor WHERE status IN(1,2) AND id='" . $vendor_id . "'";
                $query_exe = $this->db->query($query);
                $service_setting  = $query_exe->row_array();
                if (isset($service_setting['single_pc_rate_wise_rate']) && $service_setting['single_pc_rate_wise_rate'] == 1) {
                    $chargeable_wt_total = $chargeable_wt_total / $total_pcs;
                }
            }




            $consignor_wt = get_consignor_wt($customer_contract_id, 'customer');
            $consignor_wt = (float)$consignor_wt;

            $query = "SELECT * FROM customer_contract WHERE status IN(1,2) AND id='" . $customer_contract_id . "'";
            $query_exe = $this->db->query($query);
            $result  = $query_exe->row_array();


            if (isset($result) && is_array($result) && count($result) > 0) {

                if (isset($result['contract_type']) && $result['contract_type'] == 2) {
                    //BOX WISE
                    if ($total_pcs > $consignor_wt) {
                        $box_to_match = $total_pcs;
                    } else {
                        $box_to_match = $consignor_wt;
                    }

                    $query = "SELECT * FROM customer_contract_rate WHERE status IN(1,2) 
                    AND customer_contract_id=" . $customer_contract_id
                        . " AND " . $box_to_match . " BETWEEN lower_wt AND upper_wt";
                    $query_exe = $this->db->query($query);
                    $rate_data  = $query_exe->row_array();

                    if (isset($rate_data) && is_array($rate_data) && count($rate_data) > 0) {
                        if ((float)$rate_data['lower_wt'] == 0) {
                            $total_freight_amt = $rate_data['rate'];
                        } else {
                            $total_freight_amt = $rate_data['rate'] * $box_to_match;
                        }
                    }
                } else {
                    $query = "SELECT * FROM customer_contract_rate WHERE status IN(1,2) 
                    AND customer_contract_id=" . $customer_contract_id
                        . " AND " . $chargeable_wt_total . " BETWEEN lower_wt AND upper_wt";

                    $query_exe = $this->db->query($query);
                    $rate_data  = $query_exe->row_array();

                    if (!isset($rate_data['id'])) {
                        //ADD 1 to wt if slab not found
                        //$chargeable_wt_total = $chargeable_wt_total + 1;
                        $chargeable_wt_total = ceil($chargeable_wt_total);
                        $query = "SELECT * FROM customer_contract_rate WHERE status IN(1,2) 
                        AND customer_contract_id=" . $customer_contract_id
                            . " AND " . $chargeable_wt_total . " BETWEEN lower_wt AND upper_wt";

                        $query_exe = $this->db->query($query);
                        $rate_data  = $query_exe->row_array();
                    }




                    if (isset($rate_data) && is_array($rate_data) && count($rate_data) > 0) {
                        $decimal_amt = 0;
                        $on_add = $rate_data['on_add'];
                        $decimal_arr = explode(".", $on_add);

                        if (isset($decimal_arr[1]) && $decimal_arr[1] != '') {
                            $decimal_amt = $decimal_arr[1];
                            if (strlen($decimal_arr[1]) == 1) {
                                $decimal_amt = $decimal_arr[1] . '0';
                            }
                        }
                        $round_ch_wt = $chargeable_wt_total;


                        $charge_decimal_arr = explode(".", $chargeable_wt_total);


                        if (isset($charge_decimal_arr[1]) && $charge_decimal_arr[1] != '') {
                            $chare_decimal_amt = $charge_decimal_arr[1];

                            if (strlen($charge_decimal_arr[1]) == 1) {
                                $chare_decimal_amt = $charge_decimal_arr[1] . '0';
                            }
                        }

                        if (isset($chare_decimal_amt) && $decimal_amt != 0) {


                            $charge_remain = ($chare_decimal_amt % $decimal_amt);


                            if ($charge_remain > 0) {
                                $decimal_to_add = $decimal_amt - $charge_remain;
                                $chare_decimal_amt = $chare_decimal_amt + $decimal_to_add;
                                $chare_decimal_amt = $chare_decimal_amt / 100;
                                $round_ch_wt = $charge_decimal_arr[0] + $chare_decimal_amt;
                            } else {
                                $round_ch_wt = $chargeable_wt_total;
                            }

                            $round_ch_wt = round($round_ch_wt, 2);

                            // echo "<br>chare_decimal_amt=" . $chare_decimal_amt;
                            // echo "<br>decimal_amt=" . $decimal_amt;
                            // echo "<br>decimal_to_add=" . $decimal_to_add;
                            // echo "<br>chare_decimal_amt=" . $chare_decimal_amt;
                            // echo "<br>round_ch_wt=" . $round_ch_wt;
                        } else {
                            $round_ch_wt = ceil($chargeable_wt_total);
                        }

                        if ($result['migration_id'] > 0) {

                            if ($result['method_id'] == 2) {
                                //SLAB WISE
                                $total_freight_amt = 0;
                                $query = "SELECT * FROM customer_contract_rate WHERE status IN(1,2) 
                    AND customer_contract_id=" . $result['id'];
                                $query_exe = $this->db->query($query);
                                $rate_rule_data  = $query_exe->result_array();
                                $rate_remaining = $round_ch_wt;
                                $start_wt = $rate_remaining;
                                if (isset($rate_rule_data) && is_array($rate_rule_data) && count($rate_rule_data) > 0) {
                                    foreach ($rate_rule_data as $rkey => $rvalue) {
                                        if (
                                            $rate_remaining > 0 && ($start_wt >= $rvalue['lower_wt'] && $start_wt <= $rvalue['upper_wt']) ||
                                            ($start_wt >= $rvalue['upper_wt'])
                                        ) {


                                            $rate_weight = ($rvalue['upper_wt'] - $rvalue['lower_wt']);
                                            if ($rate_weight > $rate_remaining) {
                                                $rate_weight =  $rate_remaining;
                                            }
                                            $start_wt = $rvalue['upper_wt'] + 1;

                                            $rate_remaining = $rate_remaining - $rate_weight;

                                            if ((float)$rvalue['lower_wt'] == 0) {
                                                //IF LOWER WT IS 0 THEN DONT MULTIPLE WT & RATE
                                                //BUT FOR MIGRATED CONTRCT CHECK IF SALB ABOVE THIS IN CONTRACT HAVING 
                                                //LOWER WT MORE THAN 0 
                                                //IF UPPER SLAB WT IS MORE THAN 0 THEN MULTIPLE WT ELSE NOT
                                                $query = "SELECT * FROM customer_contract_rate WHERE status IN(1,2) AND lower_wt!=0 
                                        AND customer_contract_id=" . $customer_contract_id . " AND id<" . $rvalue['id'];
                                                $query_exe = $this->db->query($query);
                                                $rate_lower_data  = $query_exe->row_array();
                                                if (isset($rate_lower_data) && is_array($rate_lower_data) && count($rate_lower_data) > 0) {
                                                    $rate_on_add = (float)$rvalue['on_add'];
                                                    $multiply_fact = ($rate_weight / $rate_on_add);
                                                    $multiply_fact = ceil($multiply_fact);
                                                    $freight_amt = $rvalue['rate'] * $multiply_fact;
                                                } else {
                                                    $freight_amt = $rvalue['rate'];
                                                }
                                            } else {
                                                $rate_on_add = (float)$rvalue['on_add'];
                                                $multiply_fact = ($rate_weight / $rate_on_add);
                                                $multiply_fact = ceil($multiply_fact);
                                                $freight_amt = $rvalue['rate'] * $multiply_fact;
                                            }

                                            $total_freight_amt = $total_freight_amt + $freight_amt;
                                        }
                                    }
                                }
                            } else {
                                //FIXED SLAB
                                if ((float)$rate_data['lower_wt'] == 0) {
                                    //IF LOWER WT IS 0 THEN DONT MULTIPLE WT & RATE
                                    //BUT FOR MIGRATED CONTRCT CHECK IF SALB ABOVE THIS IN CONTRACT HAVING 
                                    //LOWER WT MORE THAN 0 
                                    //IF UPPER SLAB WT IS MORE THAN 0 THEN MULTIPLE WT ELSE NOT
                                    $query = "SELECT * FROM customer_contract_rate WHERE status IN(1,2) AND lower_wt!=0 
                            AND customer_contract_id=" . $customer_contract_id . " AND id<" . $rate_data['id'];
                                    $query_exe = $this->db->query($query);
                                    $rate_lower_data  = $query_exe->row_array();
                                    if (isset($rate_lower_data) && is_array($rate_lower_data) && count($rate_lower_data) > 0) {
                                        $round_ch_wt = (float)$round_ch_wt;
                                        $rate_on_add = (float)$rate_data['on_add'];
                                        $multiply_fact = ($round_ch_wt / $rate_on_add);
                                        $multiply_fact = ceil($multiply_fact);
                                        $total_freight_amt = $rate_data['rate'] * $multiply_fact;
                                    } else {
                                        $total_freight_amt = $rate_data['rate'];
                                    }
                                } else {
                                    $round_ch_wt = (float)$round_ch_wt;
                                    $rate_on_add = (float)$rate_data['on_add'];
                                    $multiply_fact = ($round_ch_wt / $rate_on_add);
                                    $multiply_fact = ceil($multiply_fact);
                                    $total_freight_amt = $rate_data['rate'] * $multiply_fact;
                                }
                            }
                        } else {

                            if ($result['method_id'] == 2) {
                                //SLAB WISE
                                $total_freight_amt = 0;
                                $query = "SELECT * FROM customer_contract_rate WHERE status IN(1,2) 
                    AND customer_contract_id=" . $result['id'];
                                $query_exe = $this->db->query($query);
                                $rate_rule_data  = $query_exe->result_array();

                                $rate_remaining = $round_ch_wt;
                                $start_wt = $rate_remaining;
                                if (isset($rate_rule_data) && is_array($rate_rule_data) && count($rate_rule_data) > 0) {
                                    foreach ($rate_rule_data as $rkey => $rvalue) {
                                        //echo "<br>RATE=" . $rate_remaining;

                                        if (
                                            $rate_remaining > 0 && ($start_wt >= $rvalue['lower_wt'] && $start_wt <= $rvalue['upper_wt']) ||
                                            ($start_wt >= $rvalue['upper_wt'])
                                        ) {
                                            $rate_weight = ($rvalue['upper_wt'] - $rvalue['lower_wt']);

                                            if ($rate_weight > $rate_remaining) {
                                                $rate_weight =  $rate_remaining;
                                            }
                                            $start_wt = $rvalue['upper_wt'] + 1;
                                            $rate_remaining = $rate_remaining - $rate_weight;

                                            if ((float)$rvalue['lower_wt'] == 0) {
                                                $freight_amt = $rvalue['rate'];
                                            } else {
                                                $freight_amt = $rvalue['rate'] * $rate_weight;
                                            }

                                            $total_freight_amt = $total_freight_amt + $freight_amt;
                                        }
                                    }
                                }
                            } else {

                                //FIXED SLAB
                                if ((float)$rate_data['lower_wt'] == 0) {
                                    $total_freight_amt = $rate_data['rate'];
                                } else {
                                    $total_freight_amt = $rate_data['rate'] * $round_ch_wt;
                                }
                            }
                        }
                    }
                }
            }
        }

        $total_freight_amt = round($total_freight_amt, 2);
        $response_data['freight_amt'] = $total_freight_amt;
        $response_data['round_ch_wt'] = isset($round_ch_wt) ? $round_ch_wt : $chargeable_wt_total;
        if (isset($service_setting['single_pc_rate_wise_rate']) && $service_setting['single_pc_rate_wise_rate'] == 1) {
            $response_data['freight_amt'] = $round_ch_wt * $response_data['freight_amt'] * $total_pcs;
            $response_data['round_ch_wt'] = $post_data['chargeable_wt_total'] > 0 ? $post_data['chargeable_wt_total'] : 0;
        }

        if (isset($post) && is_array($post) && count($post) > 0) {
            return $response_data;
        } else {
            echo json_encode($response_data);
        }
    }

    function get_location_data($post = array())
    {
        $this->load->model('Global_model', 'gm');
        $response_data = array();

        if (isset($post) && is_array($post) && count($post) > 0) {
            $post_data = $post;
        } else {
            $post_data = $this->input->post();
        }

        $location_id = $post_data['location_id'] > 0 ? $post_data['location_id'] : 0;
        if (isset($post_data['pincode']) && $post_data['pincode'] != '') {
            $query = "SELECT l.id,l.name,l.code,l.location_type,l.city_id FROM location l
        WHERE l.status IN(1,2) AND l.location_type=2 AND l.code='" . $post_data['pincode'] . "'";
            $query_exe = $this->db->query($query);
            $pincode_data  = $query_exe->row_array();
            $location_id =  isset($pincode_data['id']) ? $pincode_data['id'] : 0;
        }
        // $query = "SELECT l.id,l.name,l.code,l.is_pincode,c.name as city_name,s.name as state_name,co.name as country_name FROM location l
        // LEFT OUTER JOIN city c ON(c.id=l.city_id AND c.status IN(1,2))
        // LEFT OUTER JOIN state s ON(s.id=l.state_id AND s.status IN(1,2))
        // LEFT OUTER JOIN country co ON(co.id=l.country_id AND co.status IN(1,2))
        // WHERE l.status IN(1,2) AND l.id=" . $location_id;

        $query = "SELECT l.id,l.name,l.code,l.location_type,l.city_id FROM location l
        WHERE l.status IN(1,2) AND l.id=" . $location_id;
        $query_exe = $this->db->query($query);
        if ($query_exe->num_rows() > 0) {
            $response_data  = $query_exe->row_array();


            $response_data['pincode'] = '';
            if ($response_data['location_type'] == 2) {
                //PINCODE
                $response_data['pincode'] = $response_data['code'];
                if ($response_data['city_id'] > 0) {
                    $query = "SELECT c.id,c.name,c.code,c.district_id FROM city c WHERE status IN(1,2) AND id='" . $response_data['city_id'] . "'";
                    $query_exe = $this->db->query($query);
                    $city_data  = $query_exe->row_array();
                    if (isset($city_data['district_id']) && $city_data['district_id'] > 0) {
                        $district_data  = $this->gm->get_selected_record('district', 'id,name,code,state_id', $where = array('id' => $city_data['district_id'], 'status=' => 1), array());
                    }

                    if (isset($district_data['state_id']) && $district_data['state_id'] > 0) {
                        $state_data  = $this->gm->get_selected_record('state', 'id,name,code,country_id', $where = array('id' => $district_data['state_id'], 'status=' => 1), array());
                    }
                    if (isset($state_data['country_id']) && $state_data['country_id'] > 0) {
                        $country_data  = $this->gm->get_selected_record('country', 'id,name,code', $where = array('id' => $state_data['country_id'], 'status=' => 1), array());
                    }
                }
            } else if ($response_data['location_type'] == 3) {
                //CITY
                $query = "SELECT c.id,c.name,c.code,c.district_id FROM city c WHERE status IN(1,2) AND code='" . escape_string($response_data['code']) . "'";
                $query_exe = $this->db->query($query);
                $city_data  = $query_exe->row_array();
                if (isset($city_data['district_id']) && $city_data['district_id'] > 0) {
                    $district_data  = $this->gm->get_selected_record('district', 'id,name,code,state_id', $where = array('id' => $city_data['district_id'], 'status=' => 1), array());
                }

                if (isset($district_data['state_id']) && $district_data['state_id'] > 0) {
                    $state_data  = $this->gm->get_selected_record('state', 'id,name,code,country_id', $where = array('id' => $district_data['state_id'], 'status=' => 1), array());
                }
                if (isset($state_data['country_id']) && $state_data['country_id'] > 0) {
                    $country_data  = $this->gm->get_selected_record('country', 'id,name,code', $where = array('id' => $state_data['country_id'], 'status=' => 1), array());
                }
            } else if ($response_data['location_type'] == 4) {
                //STATE
                $query = "SELECT c.id,c.name,c.code,c.country_id FROM state c WHERE status IN(1,2) AND code='" . escape_string($response_data['code']) . "'";
                $query_exe = $this->db->query($query);
                $state_data  = $query_exe->row_array();

                if (isset($state_data['country_id']) && $state_data['country_id'] > 0) {
                    $country_data  = $this->gm->get_selected_record('country', 'id,name,code', $where = array('id' => $state_data['country_id'], 'status=' => 1), array());
                }
            } else if ($response_data['location_type'] == 5) {
                //COUNTRY
                $query = "SELECT c.id,c.name,c.code FROM country c WHERE status IN(1,2) AND code='" . escape_string($response_data['code']) . "'";
                $query_exe = $this->db->query($query);
                $country_data  = $query_exe->row_array();
            }




            $response_data['city_id'] = isset($city_data['id']) ? $city_data['id'] : '';
            $response_data['city_name'] = isset($city_data['name']) ? $city_data['name'] : '';
            $response_data['city_code'] = isset($city_data['code']) ? $city_data['code'] : '';


            $response_data['state_id'] = isset($state_data['id']) ? $state_data['id'] : '';
            $response_data['state_name'] = isset($state_data['name']) ? $state_data['name'] : '';
            $response_data['state_code'] = isset($state_data['code']) ? $state_data['code'] : '';

            $response_data['country_id'] = isset($country_data['id']) ? $country_data['id'] : '';
            $response_data['country_name'] = isset($country_data['name']) ? $country_data['name'] : '';
            $response_data['country_code'] = isset($country_data['code']) ? $country_data['code'] : '';
        }
        if (isset($post) && is_array($post) && count($post) > 0) {
            return $response_data;
        } else {
            echo json_encode($response_data);
        }
    }

    function get_purchase_freight_amount($post = array())
    {
        $total_freight_amt = 0;

        if (isset($post) && is_array($post) && count($post) > 0) {
            $post_data = $post;
        } else {
            $post_data = $this->input->post();
        }


        if (isset($post_data) && is_array($post_data) && count($post_data) > 0) {
            $vendor_contract_id = $post_data['vendor_contract_id'] > 0 ? $post_data['vendor_contract_id'] : 0;
            $chargeable_wt_total = $post_data['chargeable_wt_total'] > 0 ? $post_data['chargeable_wt_total'] : 0;
            $chargeable_wt_total = (float)$chargeable_wt_total;

            //FIELD USE FOR BOX WISE CONTRACT TYPE
            $total_pcs = $post_data['total_pcs'] > 0 ? $post_data['total_pcs'] : 0;
            $total_pcs = (int)$total_pcs;

            $consignor_wt = get_consignor_wt($vendor_contract_id, 'service');
            $consignor_wt = (float)$consignor_wt;

            $query = "SELECT * FROM vendor_contract WHERE status IN(1,2) AND id='" . $vendor_contract_id . "'";
            $query_exe = $this->db->query($query);
            $result  = $query_exe->row_array();

            if (isset($result) && is_array($result) && count($result) > 0) {
                if (isset($result['contract_type']) && $result['contract_type'] == 2) {
                    //BOX WISE
                    if ($total_pcs > $consignor_wt) {
                        $box_to_match = $total_pcs;
                    } else {
                        $box_to_match = $consignor_wt;
                    }

                    $query = "SELECT * FROM vendor_contract_rate WHERE status IN(1,2) 
                    AND vendor_contract_id=" . $vendor_contract_id
                        . " AND " . $box_to_match . " BETWEEN lower_wt AND upper_wt";
                    $query_exe = $this->db->query($query);
                    $rate_data  = $query_exe->row_array();
                    if (isset($rate_data) && is_array($rate_data) && count($rate_data) > 0) {
                        if ((float)$rate_data['lower_wt'] == 0) {
                            $total_freight_amt = $rate_data['rate'];
                        } else {
                            $total_freight_amt = $rate_data['rate'] * $box_to_match;
                        }
                    }
                } else {

                    $query = "SELECT * FROM vendor_contract_rate WHERE status IN(1,2) 
                AND vendor_contract_id=" . $vendor_contract_id
                        . " AND " . $chargeable_wt_total . " BETWEEN lower_wt AND upper_wt";

                    $query_exe = $this->db->query($query);
                    $rate_data  = $query_exe->row_array();

                    if (!isset($rate_data['id'])) {
                        //$chargeable_wt_total = $chargeable_wt_total + 1;
                        $chargeable_wt_total = ceil($chargeable_wt_total);
                        $query = "SELECT * FROM vendor_contract_rate WHERE status IN(1,2) 
                AND vendor_contract_id=" . $vendor_contract_id
                            . " AND " . $chargeable_wt_total . " BETWEEN lower_wt AND upper_wt";

                        $query_exe = $this->db->query($query);
                        $rate_data  = $query_exe->row_array();
                    }
                    if (isset($rate_data) && is_array($rate_data) && count($rate_data) > 0) {
                        $decimal_amt = 0;
                        $on_add = $rate_data['on_add'];
                        $decimal_arr = explode(".", $on_add);
                        if (isset($decimal_arr[1]) && $decimal_arr[1] != '') {
                            $decimal_amt = $decimal_arr[1];
                            if (strlen($decimal_arr[1]) == 1) {
                                $decimal_amt = $decimal_arr[1] . '0';
                            }
                        }
                        $round_ch_wt = $chargeable_wt_total;


                        $charge_decimal_arr = explode(".", $chargeable_wt_total);

                        if (isset($charge_decimal_arr[1]) && $charge_decimal_arr[1] != '') {
                            $chare_decimal_amt = $charge_decimal_arr[1];

                            if (strlen($charge_decimal_arr[1]) == 1) {
                                $chare_decimal_amt = $charge_decimal_arr[1] . '0';
                            }
                        }

                        if (isset($chare_decimal_amt) && $decimal_amt != 0) {
                            $charge_remain = ($chare_decimal_amt % $decimal_amt);

                            if ($charge_remain > 0) {
                                $decimal_to_add = $decimal_amt - $charge_remain;
                                $chare_decimal_amt = $chare_decimal_amt + $decimal_to_add;
                                $chare_decimal_amt = $chare_decimal_amt / 100;
                                $round_ch_wt = $charge_decimal_arr[0] + $chare_decimal_amt;
                            } else {
                                $round_ch_wt = $chargeable_wt_total;
                            }

                            $round_ch_wt = round($round_ch_wt, 2);

                            // echo "<br>chare_decimal_amt=" . $chare_decimal_amt;
                            // echo "<br>decimal_amt=" . $decimal_amt;
                            // echo "<br>decimal_to_add=" . $decimal_to_add;
                            // echo "<br>chare_decimal_amt=" . $chare_decimal_amt;
                            // echo "<br>round_ch_wt=" . $round_ch_wt;
                        } else {
                            $round_ch_wt = ceil($chargeable_wt_total);
                        }


                        if ($result['method_id'] == 2) {
                            //SLAB WISE
                            $total_freight_amt = 0;
                            $query = "SELECT * FROM vendor_contract_rate WHERE status IN(1,2) 
                AND vendor_contract_id=" . $result['id'];
                            $query_exe = $this->db->query($query);
                            $rate_rule_data  = $query_exe->result_array();

                            $rate_remaining = $round_ch_wt;
                            $start_wt = $rate_remaining;
                            if (isset($rate_rule_data) && is_array($rate_rule_data) && count($rate_rule_data) > 0) {
                                foreach ($rate_rule_data as $rkey => $rvalue) {
                                    //echo "<br>RATE=" . $rate_remaining;

                                    if (
                                        $rate_remaining > 0 && ($start_wt >= $rvalue['lower_wt'] && $start_wt <= $rvalue['upper_wt']) ||
                                        ($start_wt >= $rvalue['upper_wt'])
                                    ) {
                                        $rate_weight = ($rvalue['upper_wt'] - $rvalue['lower_wt']);

                                        if ($rate_weight > $rate_remaining) {
                                            $rate_weight =  $rate_remaining;
                                        }
                                        $start_wt = $rvalue['upper_wt'] + 1;
                                        $rate_remaining = $rate_remaining - $rate_weight;

                                        if ((float)$rvalue['lower_wt'] == 0) {
                                            $freight_amt = $rvalue['rate'];
                                        } else {
                                            $freight_amt = $rvalue['rate'] * $rate_weight;
                                        }

                                        $total_freight_amt = $total_freight_amt + $freight_amt;
                                    }
                                }
                            }
                        } else {
                            //FIXED SLAB
                            if ((float)$rate_data['lower_wt'] == 0) {
                                $total_freight_amt = $rate_data['rate'];
                            } else {
                                $total_freight_amt = $rate_data['rate'] * $round_ch_wt;
                            }
                        }
                    }
                }
            }
        }
        $total_freight_amt = round($total_freight_amt, 2);
        $response_data['freight_amt'] = $total_freight_amt;
        $response_data['round_ch_wt'] = isset($round_ch_wt) ? $round_ch_wt : $chargeable_wt_total;
        if (isset($post) && is_array($post) && count($post) > 0) {
            return $response_data;
        } else {
            echo json_encode($response_data);
        }
    }


    public function delete($id)
    {
        $this->load->helper('url');
        $this->load->model('Global_model', 'gm');
        $post_data = $this->input->post();
        if (isset($id) && $id != '' && isset($post_data['module']) && $post_data['module'] != '') {

            $module_name = $post_data['module'];

            //CHECK MASTER USED IN DOCKET.IF YES DONT ALLOW USER TO DELETE IT.
            if ($module_name == 'customer') {
                $query_arr = array(
                    array('name' => 'Docket', 'query' => "SELECT id FROM docket WHERE status IN(1,2) AND customer_id = " . $id . " LIMIT 1"),
                    array('name' => 'CFT Contract', 'query' => "SELECT id FROM cft_contracts WHERE status IN(1,2) AND customer_id = " . $id . " LIMIT 1"),
                    array('name' => 'Consignee', 'query' => "SELECT id FROM consignee WHERE status IN(1,2) AND customer_id = " . $id . " LIMIT 1"),
                    array('name' => 'Customer Contract', 'query' => "SELECT id FROM customer_contract WHERE status IN(1,2) AND customer_id = " . $id . " LIMIT 1"),
                    array('name' => 'FSC', 'query' => "SELECT id FROM fsc_masters WHERE status IN(1,2) AND customer_id = " . $id . " LIMIT 1"),
                    array('name' => 'Shipper', 'query' => "SELECT id FROM shipper WHERE status IN(1,2) AND customer_id = " . $id . " LIMIT 1"),
                );
            } else if ($module_name == 'location') {
                $query_arr = array(
                    array('name' => 'Docket', 'query' => "SELECT id FROM docket WHERE status IN(1,2) AND (origin_id = " . $id . " OR destination_id = " . $id . ") LIMIT 1"),
                    array('name' => 'Customer', 'query' => "SELECT id FROM customer WHERE status IN(1,2) AND origin_id = " . $id . " LIMIT 1"),
                );
            } else if ($module_name == 'product') {
                $query_arr = array(
                    array('name' => 'Docket', 'query' => "SELECT id FROM docket WHERE status IN(1,2) AND product_id = " . $id . " LIMIT 1"),
                    array('name' => 'CFT Contract', 'query' => "SELECT id FROM cft_contracts WHERE status IN(1,2) AND product_id = " . $id . " LIMIT 1"),
                    array('name' => 'Customer Contract', 'query' => "SELECT id FROM customer_contract WHERE status IN(1,2) AND product_id = " . $id . " LIMIT 1"),
                    array('name' => 'Service Contract', 'query' => "SELECT id FROM vendor_contract WHERE status IN(1,2) AND product_id = " . $id . " LIMIT 1"),
                    array('name' => 'Purchase Billing', 'query' => "SELECT d.id FROM docket d JOIN docket_purchase_billing pur ON(d.id=pur.docket_id)
                    WHERE d.status IN(1,2) AND pur.status IN(1,2)  AND pur.product_id = " . $id . " LIMIT 1"),
                );
            } else if ($module_name == 'vendor') {
                $query_arr = array(
                    array('name' => 'Docket', 'query' => "SELECT id FROM docket WHERE status IN(1,2) AND vendor_id = " . $id . " LIMIT 1"),
                    array('name' => 'CFT Contract', 'query' => "SELECT id FROM cft_contracts WHERE status IN(1,2) AND vendor_id = " . $id . " LIMIT 1"),
                    array('name' => 'Customer Contract', 'query' => "SELECT id FROM customer_contract WHERE status IN(1,2) AND vendor_id = " . $id . " LIMIT 1"),
                    array('name' => 'Purchase Billing', 'query' => "SELECT d.id FROM docket d JOIN docket_purchase_billing pur ON(d.id=pur.docket_id)
                    WHERE d.status IN(1,2) AND pur.status IN(1,2)  AND pur.vendor_id = " . $id . " LIMIT 1"),
                    array('name' => 'FSC', 'query' => "SELECT id FROM fsc_masters WHERE status IN(1,2) AND vendor_id = " . $id . " LIMIT 1"),
                    array('name' => 'Service Contract', 'query' => "SELECT id FROM vendor_contract WHERE status IN(1,2) AND vendor_id = " . $id . " LIMIT 1"),
                    // array('name' => 'Vendor Invoice', 'query' => "SELECT id FROM vendor_invoice WHERE status IN(1,2) AND vendor_id = " . $id . " LIMIT 1"),
                    array('name' => 'Location', 'query' => "SELECT l.id FROM location l JOIN location_zone_map zm ON(l.id=zm.location_id)
                    WHERE l.status IN(1,2) AND zm.status IN(1,2)  AND zm.vendor_id = " . $id . " LIMIT 1"),
                );
            } else if ($module_name == 'co_vendor') {
                $query_arr = array(
                    array('name' => 'Docket', 'query' => "SELECT id FROM docket WHERE status IN(1,2) AND co_vendor_id = " . $id . " LIMIT 1"),
                    array('name' => 'CFT Contract', 'query' => "SELECT id FROM cft_contracts WHERE status IN(1,2) AND co_vendor_id = " . $id . " LIMIT 1"),
                    array('name' => 'Customer Contract', 'query' => "SELECT id FROM customer_contract WHERE status IN(1,2) AND co_vendor_id = " . $id . " LIMIT 1"),
                    array('name' => 'Purchase Billing', 'query' => "SELECT d.id FROM docket d JOIN docket_purchase_billing pur ON(d.id=pur.docket_id)
                    WHERE d.status IN(1,2) AND pur.status IN(1,2)  AND pur.co_vendor_id = " . $id . " LIMIT 1"),
                    array('name' => 'FSC', 'query' => "SELECT id FROM fsc_masters WHERE status IN(1,2) AND co_vendor_id = " . $id . " LIMIT 1"),
                    array('name' => 'Location', 'query' => "SELECT l.id FROM location l JOIN location_zone_map zm ON(l.id=zm.location_id)
                    WHERE l.status IN(1,2) AND zm.status IN(1,2)  AND zm.co_vendor_id = " . $id . " LIMIT 1"),
                    array('name' => 'Service Contract', 'query' => "SELECT id FROM vendor_contract WHERE status IN(1,2) AND co_vendor_id = " . $id . " LIMIT 1"),
                );
            } else if ($module_name == 'shipper') {
                $query_arr = array(
                    array('name' => 'Docket', 'query' => "SELECT id FROM docket WHERE status IN(1,2) AND shipper_id = " . $id . " LIMIT 1"),
                    array('name' => 'Invoice', 'query' => "SELECT id FROM docket_invoice WHERE status IN(1,2) AND shipper_id = " . $id . " LIMIT 1"),
                );
            } else if ($module_name == 'consignee') {
                $query_arr = array(
                    array('name' => 'Docket', 'query' => "SELECT id FROM docket WHERE status IN(1,2) AND consignee_id = " . $id . " LIMIT 1"),
                );
            } else if ($module_name == 'free_form_note') {
                $query_arr = array(
                    array('name' => 'Docket', 'query' => "SELECT id FROM docket WHERE status IN(1,2) AND free_note = " . $id . " LIMIT 1"),
                );
            } else if ($module_name == 'free_form_item') {
                $query_arr = array(
                    array('name' => 'Docket', 'query' => "SELECT d.id FROM docket d 
                    JOIN docket_free_form_invoice fr ON(d.id=fr.docket_id)
                    WHERE d.status IN(1,2) AND fr.status IN(1,2)  AND fr.description_id = " . $id . " LIMIT 1"),
                );
            } else if ($module_name == 'customer_contract') {
                $query_arr = array(
                    array('name' => 'Docket', 'query' => "SELECT id FROM docket WHERE status IN(1,2) AND customer_contract_id = " . $id . " LIMIT 1"),
                );
            } else if ($module_name == 'vendor_contract') {
                $query_arr = array(
                    array('name' => 'Docket', 'query' => "SELECT id FROM docket WHERE status IN(1,2) AND vendor_contract_id = " . $id . " LIMIT 1"),
                );
            } else if ($module_name == 'cft_contracts') {
                $query_arr = array(
                    array('name' => 'Docket', 'query' => "SELECT id FROM docket WHERE status IN(1,2) AND cft_contract_id = " . $id . " LIMIT 1"),
                    array('name' => 'Purchase Billing', 'query' => "SELECT d.id FROM docket d JOIN docket_purchase_billing pur ON(d.id=pur.docket_id)
                    WHERE d.status IN(1,2) AND pur.status IN(1,2)  AND pur.cft_contract_id = " . $id . " LIMIT 1"),
                );
            } else if ($module_name == 'fsc_masters') {
                $query_arr = array(
                    array('name' => 'Sales Billing', 'query' => "SELECT d.id FROM docket d 
                    JOIN docket_sales_billing fr ON(d.id=fr.docket_id)
                    WHERE d.status IN(1,2) AND fr.status IN(1,2)  AND fr.sales_fsc_id = " . $id . " LIMIT 1"),
                    array('name' => 'Purchase Billing', 'query' => "SELECT d.id FROM docket d JOIN docket_purchase_billing pur ON(d.id=pur.docket_id)
                    WHERE d.status IN(1,2) AND pur.status IN(1,2)  AND pur.purchase_fsc_id = " . $id . " LIMIT 1"),
                );
            } else if ($module_name == 'mode') {
                $query_arr = array(
                    array('name' => 'Service', 'query' => "SELECT id FROM vendor WHERE status IN(1,2) AND mode_id = " . $id . " LIMIT 1"),
                );
            } else if ($module_name == 'hub') {
                $query_arr = array(
                    array('name' => 'Location', 'query' => "SELECT id FROM location WHERE status IN(1,2) AND hub_id = " . $id . " LIMIT 1"),
                    array('name' => 'Customer', 'query' => "SELECT c.id FROM customer c 
                    JOIN hub_mapping hma ON(c.id=hma.module_id AND hma.module_type=1)
                    WHERE c.status IN(1,2) AND hma.status IN(1,2)  AND hma.hub_id = " . $id . " LIMIT 1"),
                );
            } else if ($module_name == 'invoice_range') {
                $query_arr = array(
                    array('name' => 'Service', 'query' => "SELECT id FROM vendor WHERE status IN(1,2) AND invoice_range_id = " . $id . " LIMIT 1"),
                    array('name' => 'Invoice', 'query' => "SELECT id FROM docket_invoice WHERE status IN(1,2) AND invoice_range_id = " . $id . " LIMIT 1"),
                );
            } else if ($module_name == 'custom_invoice') {
                $query_arr = array(
                    array('name' => 'Customer', 'query' => "SELECT id FROM customer WHERE status IN(1,2) AND custom_invoice_id = " . $id . " LIMIT 1"),
                );
            } else if ($module_name == 'charge_master') {
                $query_arr = array(
                    array('name' => 'Rate Modifier', 'query' => "SELECT id FROM rate_modifier WHERE status IN(1,2) AND charge_id = " . $id . " LIMIT 1"),
                    array('name' => 'AWB', 'query' => "SELECT ch.id FROM docket_charges ch JOIN docket d ON(d.id=ch.docket_id) WHERE ch.status IN(1,2) AND d.status IN(1,2) AND ch.charge_check=1 AND charge_id = " . $id . " LIMIT 1"),
                );
            } else if ($module_name == 'address_book') {
                $query_arr = array(
                    array('name' => 'Customer', 'query' => "SELECT id FROM customer WHERE status IN(1,2) AND address_book_id = " . $id . " LIMIT 1"),
                    array('name' => 'Send Address Book Email', 'query' => "SELECT id FROM send_address_book_email WHERE status IN(1,2) AND address_book_id = " . $id . " LIMIT 1"),
                );
            }

            $master_used_module = '';

            if (isset($query_arr) && is_array($query_arr) && count($query_arr) > 0) {
                foreach ($query_arr as $qkey => $qvalue) {
                    $docketq_exe = $this->db->query($qvalue['query']);
                    $docket_res = $docketq_exe->row_array();
                    if (isset($docket_res) && is_array($docket_res) && count($docket_res) > 0) {
                        $master_used_module = $qvalue['name'];
                        break;
                    }
                }
            }

            if (isset($docket_res) && is_array($docket_res) && count($docket_res) > 0) {
                $response['error'] = "You Cannot delete this record because it is used in " . $master_used_module . ".";
            } else {

                if ($post_data['module'] == 'pickup_request') {
                    $qry = "SELECT id,pickup_sheet_id FROM pickup_request where id = '" . $id . "'";
                    $qry_exe = $this->db->query($qry);
                    $pickup_sheet_data = $qry_exe->row_array();
                }


                $query = "UPDATE " . $post_data['module'] . " SET status = 3,modified_date='" . date('Y-m-d H:i:s') . "',modified_by='" . $this->user_id . "' where id = " . $id;
                $this->db->query($query);

                if ($pickup_sheet_data['pickup_sheet_id'] > 0) {
                    $this->load->module('pick_up_sheets');
                    $this->pick_up_sheets->update_sheet_status($pickup_sheet_data['pickup_sheet_id']);
                }

                $response['success'] = "record deleted successfully.";
            }
            echo json_encode($response);
        } else {
            http_response_code(403);
        }
    }


    function get_customer_contract($docket_data = array())
    {
        $this->load->helper('frontend_common');
        $response_data = array();
        $final_res = array();
        $setting = get_all_app_setting(" AND module_name IN('general')");
        if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
            $post_data = $docket_data;
        } else {
            $post_data = $this->input->post();
        }


        $all_vendor_id = array();
        if (isset($post_data) && is_array($post_data) && count($post_data) > 0) {


            if (isset($post_data['contract_id']) && $post_data['contract_id'] > 0) {
                $query =  "SELECT * FROM customer_contract WHERE status IN(1,2) AND id='" . $post_data['contract_id'] . "'";
                $query_exe = $this->db->query($query);
                $response_data  = $query_exe->row_array();
            } else {

                $booking_date = date('Y-m-d', strtotime(str_replace("/", "-", $post_data['booking_date'])));
                $post_data['customer_id'] = $post_data['customer_id'] > 0 ? $post_data['customer_id'] : 'NULL';
                $post_data['vendor_id'] = $post_data['vendor_id'] > 0 ? $post_data['vendor_id'] : 0;
                $post_data['co_vendor_id'] = $post_data['co_vendor_id'] > 0 ? $post_data['co_vendor_id'] : 0;
                $post_data['product_id'] = $post_data['product_id'] > 0 ? $post_data['product_id'] : 'NULL';
                $post_data['origin_id'] = $post_data['origin_id'] > 0 ? $post_data['origin_id'] : 'NULL';
                $post_data['destination_id'] = $post_data['destination_id'] > 0 ? $post_data['destination_id'] : 'NULL';
                $post_data['ori_zone_id'] = $post_data['ori_zone_id'] > 0 ? $post_data['ori_zone_id'] : 'NULL';
                $post_data['dest_zone_id'] = $post_data['dest_zone_id'] > 0 ? $post_data['dest_zone_id'] : 'NULL';
                $post_data['ori_hub_id'] = $post_data['ori_hub_id'] > 0 ? $post_data['ori_hub_id'] : 'NULL';
                $post_data['dest_hub_id'] = $post_data['dest_hub_id'] > 0 ? $post_data['dest_hub_id'] : 'NULL';


                if ($post_data['co_vendor_id'] == 0) {
                    $setting_data = get_all_app_setting(" AND module_name IN('main','docket')");

                    if (isset($setting_data['enable_vendor_rate_l1_l2']) && $setting_data['enable_vendor_rate_l1_l2'] == 1) {
                        $this->load->module('vendor_rate_estimates');
                        $l1_l2_data = $this->vendor_rate_estimates->get_vendor_rate($post_data);
                        // $sessiondata = $this->session->userdata('admin_user');
                        // if ($sessiondata["email"] == "virag@itdservices.in") {
                        //     echo '<pre>';
                        //     print_r($l1_l2_data);
                        //     print_r($post_data);
                        //     exit;
                        // }
                        if (isset($l1_l2_data['contract'][0])) {
                            //if (isset($l1_l2_data['contract'][0]) && is_array($l1_l2_data['contract'][0]) && count($l1_l2_data['contract'][0]) > 0) {
                            $vendor_data = isset($l1_l2_data['all_vendor']) ? $l1_l2_data['all_vendor'] : array();
                            $firstKey = array_keys($l1_l2_data['contract'][0])[0];
                            //$firstKey = array_key_first($l1_l2_data['contract'][0]);

                            $post_data['co_vendor_id'] = $firstKey;
                            $setting_co_vendor_id = $firstKey;
                            $setting_co_vendor_name = $vendor_data[$firstKey]['name'];
                            $setting_co_vendor_code = $vendor_data[$firstKey]['code'];
                        }
                    } else {
                        //CHECK VENDOR MAPPING
                        $post_vendor_id = $post_data['vendor_id'] > 0 ? $post_data['vendor_id'] : 0;
                        $post_origin_id = $post_data['origin_id'] > 0 ? $post_data['origin_id'] : '0';
                        $post_ori_hub_id = $post_data['ori_hub_id'] > 0 ? $post_data['ori_hub_id'] : '0';
                        $post_dest_hub_id = $post_data['dest_hub_id'] > 0 ? $post_data['dest_hub_id'] : '0';
                        $post_destination_id = $post_data['destination_id'] > 0 ? $post_data['destination_id'] : '0';


                        //     $contractq = "SELECT * FROM vendor_mapping 
                        // WHERE status IN(1) AND '" . $booking_date . "' 
                        // BETWEEN effective_min AND effective_max
                        // AND (vendor_id ='" .   $post_vendor_id . "' OR vendor_id=0)
                        // AND (ori_location_id ='" .   $post_origin_id . "' OR ori_location_id=0)
                        // AND (ori_hub_id ='" .   $post_ori_hub_id . "' OR ori_hub_id=0)
                        // AND (dest_location_id ='" .   $post_destination_id . "' OR dest_location_id=0)
                        // AND (dest_hub_id ='" .   $post_dest_hub_id . "' OR dest_hub_id=0)
                        // ORDER BY effective_min DESC";

                        $contractq = "SELECT * FROM vendor_mapping 
                    WHERE status IN(1) AND '" . $booking_date . "' 
                    BETWEEN effective_min AND effective_max
                    AND (vendor_id ='" .   $post_vendor_id . "' OR vendor_id=0)
                    ORDER BY effective_min DESC";
                        $contractq_exe = $this->db->query($contractq);
                        $mapping_condition_data = $contractq_exe->result_array();
                        if (isset($mapping_condition_data) && is_array($mapping_condition_data) && count($mapping_condition_data) > 0) {
                            foreach ($mapping_condition_data as $ma_key => $ma_value) {
                                $map_condition_cnt = 0;
                                //MATCH ORIGIN LOCATION OR HUB
                                if ($ma_value['ori_location_id'] == $post_origin_id) {
                                    $map_condition_cnt += 1;
                                } else if ($ma_value['ori_hub_id'] == $post_ori_hub_id) {
                                    $map_condition_cnt += 1;
                                }

                                //MATCH DESTINATION LOCATION OR HUB
                                if ($ma_value['dest_location_id'] == $post_destination_id) {
                                    $map_condition_cnt += 1;
                                } else if ($ma_value['dest_hub_id'] == $post_dest_hub_id) {
                                    $map_condition_cnt += 1;
                                }

                                if ($map_condition_cnt == 2) {
                                    $mapping_effective[$ma_value['effective_min']][$ma_value['id']] = $map_condition_cnt;

                                    $mapping_match[$ma_value['id']] = $ma_value;
                                    $mapping_match[$ma_value['id']]['point'] = $map_condition_cnt;
                                }
                            }


                            if (isset($mapping_match) && is_array($mapping_match) && count($mapping_match) > 0) {
                                $max_mapping_date =  max(array_column($mapping_match, 'effective_min'));

                                if (isset($mapping_effective[$max_mapping_date])) {
                                    arsort($mapping_effective[$max_mapping_date], SORT_NUMERIC);
                                    $latest_mapping_id = array_keys($mapping_effective[$max_mapping_date])[0];

                                    if (isset($mapping_match[$latest_mapping_id])) {
                                        $mapping_data = $mapping_match[$latest_mapping_id];
                                    }
                                }
                            }
                        }
                        if (isset($mapping_data) && is_array($mapping_data) && count($mapping_data) > 0) {
                            $setting_co_vendor_id = $mapping_data['co_vendor_id'];

                            $all_co_vendor = get_all_co_vendor(" AND id ='" . $mapping_data['co_vendor_id'] . "'");
                            $setting_co_vendor_name = isset($all_co_vendor[$mapping_data['co_vendor_id']]['name']) ? $all_co_vendor[$mapping_data['co_vendor_id']]['name'] : '';
                            $setting_co_vendor_code = isset($all_co_vendor[$mapping_data['co_vendor_id']]['name']) ? $all_co_vendor[$mapping_data['co_vendor_id']]['code'] : '';
                        }
                    }
                }

                if (isset($post_data['dest_city_id']) && is_numeric($post_data['dest_city_id']) && $post_data['dest_city_id'] > 0) {
                    $post_data['dest_city_id'] = $post_data['dest_city_id'];
                } else {
                    $post_data['dest_city_id'] = $post_data['dest_city_id'] != '' ? check_record_exist(array('name' => $post_data['dest_city_id']), '', 'city') : 'NULL';
                }

                if (isset($post_data['ori_city_id']) && is_numeric($post_data['ori_city_id']) && $post_data['ori_city_id'] > 0) {
                    $post_data['ori_city_id'] = $post_data['ori_city_id'];
                } else {
                    $post_data['ori_city_id'] = $post_data['ori_city_id'] != '' ? check_record_exist(array('name' => $post_data['ori_city_id']), '', 'city') : 'NULL';
                }


                if ($post_data['dest_city_id'] > 0) {
                } else {
                    $post_data['dest_city_id'] = 'NULL';
                }

                if ($post_data['ori_city_id'] > 0) {
                } else {
                    $post_data['ori_city_id'] = 'NULL';
                }

                if (isset($post_data['ori_state_id']) && is_numeric($post_data['ori_state_id']) && $post_data['ori_state_id'] > 0) {
                    $post_data['ori_state_id'] = $post_data['ori_state_id'];
                } else {
                    $post_data['ori_state_id'] = $post_data['ori_state_id'] != '' ? check_record_exist(array('name' => $post_data['ori_state_id']), '', 'state') : 'NULL';
                }

                if (isset($post_data['dest_state_id']) && is_numeric($post_data['dest_state_id']) && $post_data['dest_state_id'] > 0) {
                    $post_data['dest_state_id'] = $post_data['dest_state_id'];
                } else {
                    $post_data['dest_state_id'] = $post_data['dest_state_id'] != '' ? check_record_exist(array('name' => $post_data['dest_state_id']), '', 'state') : 'NULL';
                }

                if ($post_data['ori_state_id'] > 0) {
                } else {
                    $post_data['ori_state_id'] = 'NULL';
                }
                if ($post_data['dest_state_id'] > 0) {
                } else {
                    $post_data['dest_state_id'] = 'NULL';
                }


                if ($post_data['ori_hub_id'] > 0) {
                    $post_data['ori_hub_id'] = $post_data['ori_hub_id'];
                } else {
                    $post_data['ori_hub_id'] = 'NULL';
                }
                if ($post_data['dest_hub_id'] > 0) {
                    $post_data['dest_hub_id'] = $post_data['dest_hub_id'];
                } else {
                    $post_data['dest_hub_id'] = 'NULL';
                }


                $setting = get_all_app_setting(" AND module_name IN('docket','master','general')");


                //CHECK CUSTOMER CONTRACT HEAD PRESENT
                if ($post_data['customer_id'] > 0 && $booking_date != '' && $booking_date != '1970-01-01' && $booking_date != '0000-00-00') {
                    $query = "SELECT contract_customer_id,from_date,till_date FROM customer_contract_head
                    WHERE status IN(1) AND customer_id='" . $post_data['customer_id'] . "' AND head_type =1 
                    AND '" . $booking_date . "' BETWEEN from_date AND till_date ORDER BY from_date DESC";
                    $query_exe = $this->db->query($query);
                    $customer_contract_head = $query_exe->row_array();
                }

                if (isset($setting['apply_sepcial_rate_cust_contract']) && $setting['apply_sepcial_rate_cust_contract'] == 1) {
                    $contract_customer_id[$post_data['customer_id']] = $post_data['customer_id'];
                    if (isset($customer_contract_head) && is_array($customer_contract_head) && count($customer_contract_head) > 0) {
                        $contract_customer_id[$customer_contract_head['contract_customer_id']] = $customer_contract_head['contract_customer_id'];
                    }
                } else {

                    if (isset($customer_contract_head) && is_array($customer_contract_head) && count($customer_contract_head) > 0) {
                        $contract_customer_id[$customer_contract_head['contract_customer_id']] = $customer_contract_head['contract_customer_id'];
                    }
                    $contract_customer_id[$post_data['customer_id']] = $post_data['customer_id'];
                }

                if (isset($contract_customer_id) && is_array($contract_customer_id) && count($contract_customer_id) > 0) {
                    foreach ($contract_customer_id as $ch_key => $ch_value) {
                        //EXACT MATCH CUTOMER,SERVICE,PRODUCT
                        if (!isset($post_data['mode'])) {
                            $sel_col = "*";
                        } else {
                            $sel_col = "id,vendor_id";
                        }
                        if (!isset($post_data['mode'])) {
                            $append = " AND vendor_id='" . $post_data['vendor_id'] . "'";
                        }

                        if (isset($post_data['mode'])) {
                            $query =  "SELECT " . $sel_col . " FROM customer_contract WHERE status IN(1) "
                                . " AND '" . $booking_date . "' BETWEEN effective_min AND effective_max"
                                . " AND customer_id=" . $ch_value
                                . $append . " ORDER BY effective_min DESC,id DESC";
                            $sessiondata = $this->session->userdata('admin_user');

                            $query_exe = $this->db->query($query);
                            $contract_data = $query_exe->result_array();

                            if (isset($contract_data) && is_array($contract_data) && count($contract_data) > 0) {
                                foreach ($contract_data as $con_key => $con_value) {
                                    $all_vendor_id[$con_value['vendor_id']] = $con_value['vendor_id'];
                                }
                            }
                        } else {
                            $query =  "SELECT " . $sel_col . " FROM customer_contract WHERE status IN(1) "
                                . " AND '" . $booking_date . "' BETWEEN effective_min AND effective_max"
                                . " AND customer_id=" . $ch_value . " AND product_id='" . $post_data['product_id'] . "'"
                                . $append . " ORDER BY effective_min DESC,id DESC";
                            $query_exe = $this->db->query($query);
                            $contract_data = $query_exe->result_array();
                            // echo  $query;
                            // echo '<pre>';
                            // print_r($contract_data);

                            if (isset($contract_data) && is_array($contract_data) && count($contract_data) > 0) {
                                foreach ($contract_data as $con_key => $con_value) {

                                    $contract_point = 0;
                                    $condition_match = 0;
                                    if ($con_value['co_vendor_id'] == 0 || $con_value['co_vendor_id'] == $post_data['co_vendor_id']) {
                                        $contract_point += 1;

                                        if ($con_value['co_vendor_id'] == $post_data['co_vendor_id']) {
                                            $contract_point += 10;
                                        }
                                        $condition_match += 1;
                                    }

                                    if (isset($setting['shipkar_domestic_zone_mapping']) && $setting['shipkar_domestic_zone_mapping'] == 1) {
                                        $vendor_setting_data = $this->gm->get_selected_record('vendor', 'id,lane_wise_zone_mapping', $where = array('id' => $contract_data[0]['vendor_id']), array(), array('status' => array(1, 2)));
                                        if (isset($vendor_setting_data['lane_wise_zone_mapping']) && $vendor_setting_data['lane_wise_zone_mapping'] == 1) {
                                            $contract_point += 4;
                                            $condition_match += 1;
                                        } else {
                                            if ($con_value['ori_location_id'] == $post_data['origin_id']) {
                                                $contract_point += 4;
                                                $condition_match += 1;
                                            } else if ($con_value['ori_city_id'] == $post_data['ori_city_id']) {
                                                $contract_point += 3;
                                                $condition_match += 1;
                                            } else if ($con_value['ori_state_id'] == $post_data['ori_state_id']) {
                                                $contract_point += 2;
                                                $condition_match += 1;
                                            } else if ($con_value['ori_zone_id'] == $post_data['ori_zone_id']) {
                                                $contract_point += 1;
                                                $condition_match += 1;
                                            } else if ($con_value['ori_hub_id'] == $post_data['ori_hub_id']) {
                                                $contract_point += 1;
                                                $condition_match += 1;
                                            }
                                        }
                                    } else {
                                        //MATCH ORIGIN LOCATION ON location,city,state,zone priority
                                        if ($con_value['ori_location_id'] == $post_data['origin_id']) {
                                            $contract_point += 4;
                                            $condition_match += 1;
                                        } else if ($con_value['ori_city_id'] == $post_data['ori_city_id']) {
                                            $contract_point += 3;
                                            $condition_match += 1;
                                        } else if ($con_value['ori_state_id'] == $post_data['ori_state_id']) {
                                            $contract_point += 2;
                                            $condition_match += 1;
                                        } else if ($con_value['ori_zone_id'] == $post_data['ori_zone_id']) {
                                            $contract_point += 1;
                                            $condition_match += 1;
                                        } else if ($con_value['ori_hub_id'] == $post_data['ori_hub_id']) {
                                            $contract_point += 1;
                                            $condition_match += 1;
                                        }
                                    }


                                    //MATCH DEST LOCATION ON location,city,state,zone priority
                                    if ($con_value['dest_location_id'] == $post_data['destination_id']) {
                                        $contract_point += 4;
                                        $condition_match += 1;
                                    } else if ($con_value['dest_city_id'] == $post_data['dest_city_id']) {
                                        $contract_point += 3;
                                        $condition_match += 1;
                                    } else if ($con_value['dest_state_id'] == $post_data['dest_state_id']) {
                                        $contract_point += 2;
                                        $condition_match += 1;
                                    } else if ($con_value['dest_zone_id'] == $post_data['dest_zone_id']) {
                                        $contract_point += 1;
                                        $condition_match += 1;
                                    } else if ($con_value['dest_hub_id'] == $post_data['dest_hub_id']) {
                                        $contract_point += 1;
                                        $condition_match += 1;
                                    }

                                    if ($condition_match == 3) {



                                        if ($con_value['customer_id'] != $post_data['customer_id']) {
                                            //CONTRACT HEAD CUSTOMER
                                            $contract_head_match[$con_value['id']] = $con_value;
                                            $contract_head_match[$con_value['id']]['point'] = $contract_point;
                                        } else {
                                            $contract_match[$con_value['id']] = $con_value;
                                            $contract_match[$con_value['id']]['point'] = $contract_point;
                                        }

                                        if (!isset($post_data['mode'])) {
                                            if ($con_value['customer_id'] != $post_data['customer_id']) {
                                                //CONTRACT HEAD CUSTOMER
                                                $contract_head_effective[$con_value['effective_min']][$con_value['id']] = $contract_point;
                                            } else {
                                                $contract_effective[$con_value['effective_min']][$con_value['id']] = $contract_point;
                                            }
                                        } else {
                                            if (!in_array($con_value['vendor_id'], $all_vendor_id)) {
                                                $all_vendor_id[] = $con_value['vendor_id'];
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                if (!isset($post_data['mode'])) {
                    $response_data = array();
                    if (isset($setting['apply_sepcial_rate_cust_contract']) && $setting['apply_sepcial_rate_cust_contract'] == 1) {
                        if (isset($contract_match) && is_array($contract_match) && count($contract_match) > 0) {
                            $max_contract_date =  max(array_column($contract_match, 'effective_min'));

                            if (isset($contract_effective[$max_contract_date])) {
                                arsort($contract_effective[$max_contract_date], SORT_NUMERIC);
                                $latest_contract_id = array_keys($contract_effective[$max_contract_date])[0];

                                if (isset($contract_match[$latest_contract_id])) {
                                    $response_data = $contract_match[$latest_contract_id];
                                }
                            }
                        }

                        if (isset($response_data) && is_array($response_data) && count($response_data) > 0) {
                        } else {
                            //CHECK IN CONTRACT HEAD
                            if (isset($contract_head_match) && is_array($contract_head_match) && count($contract_head_match) > 0) {
                                $max_contract_head_date =  max(array_column($contract_head_match, 'effective_min'));

                                if (isset($contract_head_effective[$max_contract_head_date])) {
                                    arsort($contract_head_effective[$max_contract_head_date], SORT_NUMERIC);
                                    $latest_contract_id = array_keys($contract_head_effective[$max_contract_head_date])[0];

                                    if (isset($contract_head_match[$latest_contract_id])) {
                                        $response_data = $contract_head_match[$latest_contract_id];
                                    }
                                }
                            }
                        }
                    } else {

                        //CHECK IN CONTRACT HEAD
                        if (isset($contract_head_match) && is_array($contract_head_match) && count($contract_head_match) > 0) {
                            $max_contract_head_date =  max(array_column($contract_head_match, 'effective_min'));

                            if (isset($contract_head_effective[$max_contract_head_date])) {
                                arsort($contract_head_effective[$max_contract_head_date], SORT_NUMERIC);
                                $latest_contract_id = array_keys($contract_head_effective[$max_contract_head_date])[0];

                                if (isset($contract_head_match[$latest_contract_id])) {
                                    $response_data = $contract_head_match[$latest_contract_id];
                                }
                            }
                        }
                        if (isset($response_data) && is_array($response_data) && count($response_data) > 0) {
                        } else {
                            if (isset($contract_match) && is_array($contract_match) && count($contract_match) > 0) {
                                $max_contract_date =  max(array_column($contract_match, 'effective_min'));

                                if (isset($contract_effective[$max_contract_date])) {
                                    arsort($contract_effective[$max_contract_date], SORT_NUMERIC);
                                    $latest_contract_id = array_keys($contract_effective[$max_contract_date])[0];

                                    if (isset($contract_match[$latest_contract_id])) {
                                        $response_data = $contract_match[$latest_contract_id];
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if (!isset($post_data['mode'])) {


                if (isset($response_data) && is_array($response_data) && count($response_data) > 0) {
                    $query = "SELECT * FROM customer_contract_rate WHERE status IN(1) AND customer_contract_id=" . $response_data['id'];
                    $query_exe = $this->db->query($query);
                    $rate_data_res  = $query_exe->result_array();


                    if (isset($rate_data_res) && is_array($rate_data_res) && count($rate_data_res) > 0) {
                        foreach ($rate_data_res as $key => $value) {
                            $on_add = floatval($value['on_add']);
                            if (!is_numeric($on_add) || floor($on_add) == $on_add) {
                                $on_add = number_format($on_add, 1, '.', '');
                            }

                            $rate_data[$key] = $value;
                            $rate_data[$key]['on_add'] = $on_add;
                        }
                        $response_data['rate'] = $rate_data;
                        $response_data['consigner_wt'] = get_consignor_wt($response_data['id'], 'customer');
                    }
                }
                $response_data['setting_co_vendor_id'] = isset($setting_co_vendor_id) ? $setting_co_vendor_id : 0;
                $response_data['setting_co_vendor_name'] = isset($setting_co_vendor_name) ? $setting_co_vendor_name : '';
                $response_data['setting_co_vendor_code'] = isset($setting_co_vendor_code) ? $setting_co_vendor_code : '';
            } else {
                if (isset($all_vendor_id) && is_array($all_vendor_id) && count($all_vendor_id) > 0) {
                    if ($post_data['awb_view_mode'] == 'update') {
                        $docket_id = isset($post_data['docket_id']) && $post_data['docket_id'] != "" ? $post_data['docket_id'] : "";
                        if ($docket_id != "") {
                            $query =  "SELECT vendor_id FROM docket WHERE status IN(1) AND id='" . $docket_id . "'";
                            $query_exe = $this->db->query($query);
                            $vendor_id_data  = $query_exe->row_array();
                            $vendor_id = $vendor_id_data['vendor_id'];
                            $response_data = get_all_vendor(" AND (id IN(" . implode(",", $all_vendor_id) . ") AND status = 1) OR id =" . $vendor_id);
                        }
                    }
                    if ($post_data['awb_view_mode'] == 'insert') {
                        $response_data = get_all_vendor(" AND id IN(" . implode(",", $all_vendor_id) . ") AND status = 1");
                    }
                }
            }
        }

        if (isset($setting['fetch_tat_from']) && $setting['fetch_tat_from'] == 2) {
            $post_data['is_sales'] = 1;
            $tat_data = $this->get_tat_contract($post_data);
            if (isset($tat_data) && is_array($tat_data) && count($tat_data) > 0) {
                $response_data['tat'] = $tat_data['tat'];
                $response_data['tat_id'] = $tat_data['id'];
                $response_data['is_sales'] = 1;
            }
            unset($post_data['is_sales']);
        }

        if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
            return $response_data;
        } else {
            echo json_encode($response_data);
        }
    }



    function get_vendor_contract($docket_data = array())
    {
        $this->load->helper('frontend_common');
        $response_data = array();
        if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
            $post_data = $docket_data;
        } else {
            $post_data = $this->input->post();
        }

        if (isset($post_data) && is_array($post_data) && count($post_data) > 0) {

            if (isset($post_data['contract_id']) && $post_data['contract_id'] > 0) {
                $query =  "SELECT * FROM vendor_contract WHERE status IN(1,2) AND id='" . $post_data['contract_id'] . "'";
                $query_exe = $this->db->query($query);
                $response_data  = $query_exe->row_array();
            } else {
                $booking_date = date('Y-m-d', strtotime(str_replace("/", "-", $post_data['booking_date'])));
                $post_data['vendor_id'] = $post_data['vendor_id'] > 0 ? $post_data['vendor_id'] : 'NULL';
                $post_data['co_vendor_id'] = $post_data['co_vendor_id'] > 0 ? $post_data['co_vendor_id'] : 0;
                $post_data['product_id'] = $post_data['product_id'] > 0 ? $post_data['product_id'] : 'NULL';
                $post_data['origin_id'] = $post_data['origin_id'] > 0 ? $post_data['origin_id'] : 'NULL';
                $post_data['destination_id'] = $post_data['destination_id'] > 0 ? $post_data['destination_id'] : 'NULL';
                $post_data['ori_zone_id'] = $post_data['ori_zone_id'] > 0 ? $post_data['ori_zone_id'] : 'NULL';
                $post_data['dest_zone_id'] = $post_data['dest_zone_id'] > 0 ? $post_data['dest_zone_id'] : 'NULL';

                if (isset($post_data['dest_city_id']) && is_numeric($post_data['dest_city_id']) && $post_data['dest_city_id'] > 0) {
                    $post_data['dest_city_id'] = $post_data['dest_city_id'];
                } else {
                    $post_data['dest_city_id'] = $post_data['dest_city_id'] != '' ? check_record_exist(array('name' => $post_data['dest_city_id']), '', 'city') : 'NULL';
                }

                if (isset($post_data['ori_city_id']) && is_numeric($post_data['ori_city_id']) && $post_data['ori_city_id'] > 0) {
                    $post_data['ori_city_id'] = $post_data['ori_city_id'];
                } else {
                    $post_data['ori_city_id'] = $post_data['ori_city_id'] != '' ? check_record_exist(array('name' => $post_data['ori_city_id']), '', 'city') : 'NULL';
                }



                if ($post_data['dest_city_id'] > 0) {
                } else {
                    $post_data['dest_city_id'] = 'NULL';
                }

                if ($post_data['ori_city_id'] > 0) {
                } else {
                    $post_data['ori_city_id'] = 'NULL';
                }

                if (isset($post_data['ori_state_id']) && is_numeric($post_data['ori_state_id']) && $post_data['ori_state_id'] > 0) {
                    $post_data['ori_state_id'] = $post_data['ori_state_id'];
                } else {
                    $post_data['ori_state_id'] = $post_data['ori_state_id'] != '' ? check_record_exist(array('name' => $post_data['ori_state_id']), '', 'state') : 'NULL';
                }

                if (isset($post_data['dest_state_id']) && is_numeric($post_data['dest_state_id']) && $post_data['dest_state_id'] > 0) {
                    $post_data['dest_state_id'] = $post_data['dest_state_id'];
                } else {
                    $post_data['dest_state_id'] = $post_data['dest_state_id'] != '' ? check_record_exist(array('name' => $post_data['dest_state_id']), '', 'state') : 'NULL';
                }

                if ($post_data['ori_state_id'] > 0) {
                } else {
                    $post_data['ori_state_id'] = 'NULL';
                }
                if ($post_data['dest_state_id'] > 0) {
                } else {
                    $post_data['dest_state_id'] = 'NULL';
                }

                if ($post_data['ori_hub_id'] > 0) {
                    $post_data['ori_hub_id'] = $post_data['ori_hub_id'];
                } else {
                    $post_data['ori_hub_id'] = 'NULL';
                }

                if ($post_data['dest_hub_id'] > 0) {
                    $post_data['dest_hub_id'] = $post_data['dest_hub_id'];
                } else {
                    $post_data['dest_hub_id'] = 'NULL';
                }


                $contract_combination_location = array(
                    array('co_vendor_id' => $post_data['co_vendor_id']),
                    array('co_vendor_id' => 0),
                );


                $location_combination = array(
                    array('ori_location_id' => $post_data['origin_id'], 'dest_location_id' => $post_data['destination_id']),
                    array('ori_location_id' => $post_data['origin_id'], 'dest_city_id' => $post_data['dest_city_id']),
                    array('ori_location_id' => $post_data['origin_id'], 'dest_state_id' => $post_data['dest_state_id']),
                    array('ori_location_id' => $post_data['origin_id'], 'dest_zone_id' => $post_data['dest_zone_id']),

                    array('ori_city_id' => $post_data['ori_city_id'], 'dest_location_id' => $post_data['destination_id']),
                    array('ori_city_id' => $post_data['ori_city_id'], 'dest_city_id' => $post_data['dest_city_id']),
                    array('ori_city_id' => $post_data['ori_city_id'], 'dest_state_id' => $post_data['dest_state_id']),
                    array('ori_city_id' => $post_data['ori_city_id'], 'dest_zone_id' => $post_data['dest_zone_id']),

                    array('ori_state_id' => $post_data['ori_state_id'], 'dest_location_id' => $post_data['destination_id']),
                    array('ori_state_id' => $post_data['ori_state_id'], 'dest_city_id' => $post_data['dest_city_id']),
                    array('ori_state_id' => $post_data['ori_state_id'], 'dest_state_id' => $post_data['dest_state_id']),
                    array('ori_state_id' => $post_data['ori_state_id'], 'dest_zone_id' => $post_data['dest_zone_id']),

                    array('ori_zone_id' => $post_data['ori_zone_id'], 'dest_location_id' => $post_data['destination_id']),
                    array('ori_zone_id' => $post_data['ori_zone_id'], 'dest_city_id' => $post_data['dest_city_id']),
                    array('ori_zone_id' => $post_data['ori_zone_id'], 'dest_state_id' => $post_data['dest_state_id']),
                    array('ori_zone_id' => $post_data['ori_zone_id'], 'dest_zone_id' => $post_data['dest_zone_id']),
                );

                // foreach ($contract_combination_location as $lkey => $lvalue) {
                //     foreach ($location_combination as $lokey => $lovalue) {
                //         $query =  "SELECT * FROM vendor_contract WHERE status IN(1) "
                //             . " AND '" . $booking_date . "' BETWEEN effective_min AND effective_max"
                //             . " AND vendor_id= " . $post_data['vendor_id'] . " AND product_id=" . $post_data['product_id']
                //             . " AND co_vendor_id=" . $lvalue['co_vendor_id'];

                //         foreach ($lovalue as $qkey => $qvalue) {
                //             $query .= " AND $qkey = " . $qvalue . "";
                //         }
                //         $query .= " ORDER BY effective_min DESC";
                //         //  echo "<br>qry=" . $query;


                //         $query_exe = $this->db->query($query);
                //         $final_res  = $query_exe->row_array();
                //         if (isset($final_res) && is_array($final_res) && count($final_res) > 0) {
                //             $response_data = $final_res;
                //             break;
                //         }
                //     }
                //     if (isset($final_res) && is_array($final_res) && count($final_res) > 0) {
                //         break;
                //     }
                // }

                $query =  "SELECT * FROM vendor_contract WHERE status IN(1) "
                    . " AND '" . $booking_date . "' BETWEEN effective_min AND effective_max"
                    . " AND vendor_id= " . $post_data['vendor_id'] . " AND product_id=" . $post_data['product_id']
                    . " ORDER BY effective_min DESC";
                $query_exe = $this->db->query($query);
                $contract_data = $query_exe->result_array();

                if (isset($contract_data) && is_array($contract_data) && count($contract_data) > 0) {
                    foreach ($contract_data as $con_key => $con_value) {
                        $contract_point = 0;
                        $condition_match = 0;

                        if ($con_value['co_vendor_id'] == 0 || $con_value['co_vendor_id'] == $post_data['co_vendor_id']) {
                            $contract_point += 1;

                            if ($con_value['co_vendor_id'] == $post_data['co_vendor_id']) {
                                $contract_point += 10;
                            }
                            $condition_match += 1;
                        }

                        //MATCH ORIGIN LOCATION ON location,city,state,zone priority
                        if ($con_value['ori_location_id'] == $post_data['origin_id']) {
                            $contract_point += 4;
                            $condition_match += 1;
                        } elseif ($con_value['ori_city_id'] == $post_data['ori_city_id']) {
                            $contract_point += 3;
                            $condition_match += 1;
                        } elseif ($con_value['ori_state_id'] == $post_data['ori_state_id']) {
                            $contract_point += 2;
                            $condition_match += 1;
                        } elseif ($con_value['ori_zone_id'] == $post_data['ori_zone_id']) {
                            $contract_point += 1;
                            $condition_match += 1;
                        } else if ($con_value['ori_hub_id'] == $post_data['ori_hub_id']) {
                            $contract_point += 1;
                            $condition_match += 1;
                        }

                        //MATCH DEST LOCATION ON location,city,state,zone priority
                        if ($con_value['dest_location_id'] == $post_data['destination_id']) {
                            $contract_point += 4;
                            $condition_match += 1;
                        } elseif ($con_value['dest_city_id'] == $post_data['dest_city_id']) {
                            $contract_point += 3;
                            $condition_match += 1;
                        } elseif ($con_value['dest_state_id'] == $post_data['dest_state_id']) {
                            $contract_point += 2;
                            $condition_match += 1;
                        } elseif ($con_value['dest_zone_id'] == $post_data['dest_zone_id']) {
                            $contract_point += 1;
                            $condition_match += 1;
                        } else if ($con_value['dest_hub_id'] == $post_data['dest_hub_id']) {
                            $contract_point += 1;
                            $condition_match += 1;
                        }

                        if ($condition_match == 3) {
                            $contract_match[$con_value['id']] = $con_value;
                            $contract_match[$con_value['id']]['point'] = $contract_point;
                            $contract_effective[$con_value['effective_min']][$con_value['id']] = $contract_point;
                        }
                    }
                }


                if (isset($contract_match) && is_array($contract_match) && count($contract_match) > 0) {
                    $max_contract_date =  max(array_column($contract_match, 'effective_min'));

                    if (isset($contract_effective[$max_contract_date])) {
                        arsort($contract_effective[$max_contract_date], SORT_NUMERIC);
                        $latest_contract_id = array_keys($contract_effective[$max_contract_date])[0];

                        if (isset($contract_match[$latest_contract_id])) {
                            $response_data = $contract_match[$latest_contract_id];
                        }
                    }
                }
            }
            if (isset($response_data) && is_array($response_data) && count($response_data) > 0) {
                $query = "SELECT * FROM vendor_contract_rate WHERE status IN(1) AND vendor_contract_id=" . $response_data['id'];
                $query_exe = $this->db->query($query);
                $rate_data_res  = $query_exe->result_array();


                if (isset($rate_data_res) && is_array($rate_data_res) && count($rate_data_res) > 0) {
                    foreach ($rate_data_res as $key => $value) {
                        $on_add = floatval($value['on_add']);
                        if (!is_numeric($on_add) || floor($on_add) == $on_add) {
                            $on_add = number_format($on_add, 1, '.', '');
                        }

                        $rate_data[$key] = $value;
                        $rate_data[$key]['on_add'] = $on_add;
                    }
                    $response_data['rate'] = $rate_data;
                }
            }
        }

        if (isset($setting['fetch_tat_from']) && $setting['fetch_tat_from'] == 2) {
            $post_data['is_purchase'] = 1;
            $tat_data = $this->get_tat_contract($post_data);
            if (isset($tat_data) && is_array($tat_data) && count($tat_data) > 0) {
                $response_data['tat'] = $tat_data['tat'];
                $response_data['tat_id'] = $tat_data['id'];
                $response_data['is_purchase'] = 1;
            }
            unset($post_data['is_purchase']);
        }

        if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
            return $response_data;
        } else {
            echo json_encode($response_data);
        }
    }

    public function get_sales_charge_old($post = array())
    {

        if (isset($post) && is_array($post) && count($post) > 0) {
            $post_data = $post;
        } else {
            $post_data = $this->input->post();
        }

        $result = array();
        if (isset($post_data) && is_array($post_data) && count($post_data) > 0) {
            $customer_id = isset($post_data['customer_id']) && $post_data['customer_id'] > 0 ? $post_data['customer_id'] : 'NULL';
            $product_id = isset($post_data['product_id']) && $post_data['product_id'] > 0 ? $post_data['product_id'] : 'NULL';
            $vendor_id = isset($post_data['vendor_id']) && $post_data['vendor_id'] > 0 ? $post_data['vendor_id'] : 'NULL';
            $co_vendor_id = isset($post_data['co_vendor_id']) && $post_data['co_vendor_id'] > 0 ? $post_data['co_vendor_id'] : 'NULL';
            $destination_id = isset($post_data['destination_id']) && $post_data['destination_id'] > 0 ? $post_data['destination_id'] : 'NULL';
            $dest_zone_id = isset($post_data['dest_zone_id']) && $post_data['dest_zone_id'] > 0 ? $post_data['dest_zone_id'] : 'NULL';
            $origin_id = isset($post_data['origin_id']) && $post_data['origin_id'] > 0 ? $post_data['origin_id'] : 'NULL';
            $ori_zone_id = isset($post_data['ori_zone_id']) && $post_data['ori_zone_id'] > 0 ? $post_data['ori_zone_id'] : 'NULL';

            $ori_hub_id = isset($post_data['ori_hub_id']) && $post_data['ori_hub_id'] > 0 ? $post_data['ori_hub_id'] : 'NULL';
            $dest_hub_id = isset($post_data['dest_hub_id']) && $post_data['dest_hub_id'] > 0 ? $post_data['dest_hub_id'] : 'NULL';

            $charge_id = isset($post_data['charge_id']) && $post_data['charge_id'] > 0 ? $post_data['charge_id'] : '';
            $booking_date = isset($post_data['booking_date']) && $post_data['booking_date'] > 0 ? $post_data['booking_date'] : '';

            $chargeable_wt = isset($post_data['chargeable_wt']) && $post_data['chargeable_wt'] > 0 ? $post_data['chargeable_wt'] : 0;
            $actual_wt = isset($post_data['actual_wt']) && $post_data['actual_wt'] > 0 ? $post_data['actual_wt'] : 0;
            $volumetric_wt = isset($post_data['volumetric_wt']) && $post_data['volumetric_wt'] > 0 ? $post_data['volumetric_wt'] : 0;
            $total_pcs = isset($post_data['total_pcs']) && $post_data['total_pcs'] > 0 ? $post_data['total_pcs'] : 0;
            $shipment_value = isset($post_data['shipment_value']) && $post_data['shipment_value'] > 0 ? $post_data['shipment_value'] : 0;
            $charge_type = isset($post_data['charge_type']) && $post_data['charge_type'] > 0 ? $post_data['charge_type'] : 0;

            if ($charge_id > 0 && $booking_date != '') {

                //CHECK RATE MODIFIER HEAD PRESENT
                if ($customer_id > 0 && $booking_date != '' && $booking_date != '1970-01-01' && $booking_date != '0000-00-00') {
                    $query = "SELECT contract_customer_id,from_date,till_date FROM customer_contract_head
                    WHERE status IN(1) AND customer_id='" . $post_data['customer_id'] . "' AND head_type =2 
                    AND '" . $booking_date . "' BETWEEN from_date AND till_date ORDER BY from_date DESC";
                    $query_exe = $this->db->query($query);
                    $customer_rate_head = $query_exe->row_array();
                }

                if (isset($customer_rate_head) && is_array($customer_rate_head) && count($customer_rate_head) > 0) {
                    $rate_customer_id = $customer_rate_head['contract_customer_id'];
                } else {
                    $rate_customer_id = $customer_id;
                }

                $qry = "SELECT r.*,rmc.module_id as customer_id,rmp.module_id as product_id,
                rms.module_id as vendor_id,rmv.module_id as co_vendor_id,
                rmdl.module_id as destination_id,rmdz.module_id as dest_zone_id,rmdh.module_id as dest_hub_id,
                rmol.module_id as origin_id,rmoz.module_id as ori_zone_id,rmoh.module_id as ori_hub_id FROM `rate_modifier` r 
            LEFT OUTER JOIN rate_modifier_data rmc ON(r.id=rmc.rate_modifier_id AND rmc.status IN(1,2) AND rmc.module_type=1)
            LEFT OUTER JOIN rate_modifier_data rmp ON(r.id=rmp.rate_modifier_id AND rmp.status IN(1,2) AND rmp.module_type=2)
            LEFT OUTER JOIN rate_modifier_data rms ON(r.id=rms.rate_modifier_id AND rms.status IN(1,2) AND rms.module_type=3)
            LEFT OUTER JOIN rate_modifier_data rmv ON(r.id=rmv.rate_modifier_id AND rmv.status IN(1,2) AND rmv.module_type=4)
            LEFT OUTER JOIN rate_modifier_data rmdl ON(r.id=rmdl.rate_modifier_id AND rmdl.status IN(1,2) AND rmdl.module_type=5)
            LEFT OUTER JOIN rate_modifier_data rmdz ON(r.id=rmdz.rate_modifier_id AND rmdz.status IN(1,2) AND rmdz.module_type=6)
            LEFT OUTER JOIN rate_modifier_data rmol ON(r.id=rmol.rate_modifier_id AND rmol.status IN(1,2) AND rmol.module_type=7)
            LEFT OUTER JOIN rate_modifier_data rmoz ON(r.id=rmoz.rate_modifier_id AND rmoz.status IN(1,2) AND rmoz.module_type=8)
            LEFT OUTER JOIN rate_modifier_data rmdh ON(r.id=rmdh.rate_modifier_id AND rmdh.status IN(1,2) AND rmoz.module_type=9)
            LEFT OUTER JOIN rate_modifier_data rmoh ON(r.id=rmoh.rate_modifier_id AND rmoh.status IN(1,2) AND rmoz.module_type=10)
            WHERE r.status IN(1) AND (rmc.module_id=" . $rate_customer_id . " OR rmc.module_id is NULL) 
           AND (rmp.module_id=" . $product_id . " OR rmp.module_id is NULL) 
           AND (rms.module_id=" . $vendor_id . " OR rms.module_id is NULL) 
           AND (rmv.module_id=" . $co_vendor_id . " OR rmv.module_id is NULL) 
           AND (rmdl.module_id=" . $destination_id . " OR rmdl.module_id is NULL) 
           AND (rmdz.module_id=" . $dest_zone_id . " OR rmdz.module_id is NULL) 
           AND (rmol.module_id=" . $origin_id . " OR rmol.module_id is NULL) 
           AND (rmoz.module_id=" . $ori_zone_id . " OR rmoz.module_id is NULL) 
           AND (r.min_chargeable_wt<=0 OR r.max_chargeable_wt<=0 OR (r.min_chargeable_wt<=" . $chargeable_wt . " AND r.max_chargeable_wt>=" . $chargeable_wt . "))
           AND (r.min_actual_wt<=0 OR r.max_actual_wt<=0 OR (r.min_actual_wt<=" . $actual_wt . " AND r.max_actual_wt>=" . $actual_wt . "))
           AND (r.min_volume_wt<=0 OR r.max_volume_wt<=0 OR (r.min_volume_wt<=" . $volumetric_wt . " AND r.max_volume_wt>=" . $volumetric_wt . "))
           AND (r.min_boxes<=0 OR r.max_boxes<=0 OR (r.min_boxes<=" . $total_pcs . " AND r.max_boxes>=" . $total_pcs . "))
           AND (r.min_shipment_value<=0 OR r.max_shipment_value<=0 OR (r.min_shipment_value<=" . $shipment_value . " AND r.max_shipment_value>=" . $shipment_value . "))
           AND r.billing_type IN(1," . $charge_type . ")
           AND r.charge_id='" . $charge_id . "' AND '" . $booking_date . "' BETWEEN r.effective_from AND r.effective_to";



                $query_exe = $this->db->query($qry);
                $result  = $query_exe->result_array();

                $condition_arr = array(
                    'customer_id', 'product_id', 'vendor_id', 'co_vendor_id',
                    'destination_id', 'dest_zone_id', 'origin_id', 'ori_zone_id',
                    'ori_hub_id', 'dest_hub_id'
                );
                //CHECK MAXIMUM CONDITION MATCH
                $highest_match = 0;
                $highest_date = '';

                if (isset($result) && is_array($result) && count($result) > 0) {
                    foreach ($result as $key => $value) {

                        if ($chargeable_wt == '') {
                            $chargeable_wt = 0;
                        }
                        if ($actual_wt == '') {
                            $actual_wt = 0;
                        }
                        if ($volumetric_wt == '') {
                            $volumetric_wt = 0;
                        }
                        if ($total_pcs == '') {
                            $total_pcs = 0;
                        }
                        if ($shipment_value == '') {
                            $shipment_value = 0;
                        }
                        if ((int)$value['min_chargeable_wt'] > 0 && $chargeable_wt < $value['min_chargeable_wt']) {
                            continue;
                        }
                        if ((int)$value['min_actual_wt'] > 0 && $actual_wt < $value['min_actual_wt']) {
                            continue;
                        }
                        if ((int)$value['min_volume_wt'] > 0 && $volumetric_wt < $value['min_volume_wt']) {
                            continue;
                        }
                        if ((int)$value['min_boxes'] > 0 && $total_pcs < $value['min_boxes']) {
                            continue;
                        }
                        if ((int)$value['min_shipment_value'] > 0 && $shipment_value < $value['min_shipment_value']) {
                            continue;
                        }

                        if ((int)$value['max_chargeable_wt'] > 0 && $chargeable_wt > $value['max_chargeable_wt']) {
                            continue;
                        }
                        if ((int)$value['max_actual_wt'] > 0 && $actual_wt > $value['max_actual_wt']) {
                            continue;
                        }
                        if ((int)$value['max_volume_wt'] > 0 && $volumetric_wt > $value['max_volume_wt']) {
                            continue;
                        }
                        if ((int)$value['max_boxes'] > 0 && $total_pcs > $value['max_boxes']) {
                            continue;
                        }
                        if ((int)$value['max_shipment_value'] > 0 && $shipment_value > $value['max_shipment_value']) {
                            continue;
                        }

                        //MIN DIMENSION

                        if ((int)$value['min_dimension'] > 0) {
                            $min_dimension_match = array();
                            if (isset($post_data['dim_len']) && is_array($post_data['dim_len']) && count($post_data['dim_len']) > 0) {
                                foreach ($post_data['dim_len'] as $lkey => $lvalue) {
                                    if ($lvalue >= $value['min_dimension']) {
                                        $min_dimension_match[] = 1;
                                    }
                                }
                            }
                            if (isset($post_data['dim_wid']) && is_array($post_data['dim_wid']) && count($post_data['dim_wid']) > 0) {
                                foreach ($post_data['dim_wid'] as $bkey => $bvalue) {
                                    if ($bvalue >= $value['min_dimension']) {
                                        $min_dimension_match[] = 1;
                                    }
                                }
                            }
                            if (isset($post_data['dim_hei']) && is_array($post_data['dim_hei']) && count($post_data['dim_hei']) > 0) {
                                foreach ($post_data['dim_hei'] as $hkey => $hvalue) {
                                    if ($hvalue >= $value['min_dimension']) {
                                        $min_dimension_match[] = 1;
                                    }
                                }
                            }


                            if (count($min_dimension_match) == 0) {
                                continue;
                            }
                        }

                        //MAX DIMENSION
                        if ((int)$value['max_dimension'] > 0) {
                            $max_dimension_match = array();
                            if (isset($post_data['dim_len']) && is_array($post_data['dim_len']) && count($post_data['dim_len']) > 0) {
                                foreach ($post_data['dim_len'] as $lkey => $lvalue) {
                                    if ($lvalue <= $value['max_dimension']) {
                                        $max_dimension_match[] = 1;
                                    }
                                }
                            }
                            if (isset($post_data['dim_wid']) && is_array($post_data['dim_wid']) && count($post_data['dim_wid']) > 0) {
                                foreach ($post_data['dim_wid'] as $bkey => $bvalue) {
                                    if ($bvalue <= $value['max_dimension']) {
                                        $max_dimension_match[] = 1;
                                    }
                                }
                            }
                            if (isset($post_data['dim_hei']) && is_array($post_data['dim_hei']) && count($post_data['dim_hei']) > 0) {
                                foreach ($post_data['dim_hei'] as $hkey => $hvalue) {
                                    if ($hvalue <= $value['max_dimension']) {
                                        $max_dimension_match[] = 1;
                                    }
                                }
                            }

                            if (count($max_dimension_match) == 0) {
                                continue;
                            }
                        }




                        $condition_match_cnt = 0;
                        foreach ($condition_arr as $ckey => $cvalue) {
                            if ($value[$cvalue] > 0) {
                                $condition_match_cnt = $condition_match_cnt + 1;
                            }
                        }

                        if ($condition_match_cnt > $highest_match) {
                            $highest_match = $condition_match_cnt;
                            $highest_date = $value['effective_from'];
                            $rate_id_data = $value;
                        } else if ($condition_match_cnt == $highest_match && $value['effective_from'] >  $highest_date) {
                            $highest_match = $condition_match_cnt;
                            $highest_date = $value['effective_from'];
                            $rate_id_data = $value;
                        }

                        if ($key == 0) {
                            $highest_match = $condition_match_cnt;
                            $highest_date = $value['effective_from'];
                            $rate_id_data = $value;
                        }
                        // $match_rate[$value['id']] =  $condition_match_cnt;
                        // $match_rate_date[$value['id']] =  $value['effective_from'];
                    }
                } else {
                    $rate_id_data = isset($result[0]) ? $result[0] : array();
                }
            }
        }
        $rate_amt = 0;
        $rate_apply_amt = 0;


        if (isset($rate_id_data) && is_array($rate_id_data) && count($rate_id_data) > 0) {
            $freight_amount = isset($post_data['freight_amount']) && $post_data['freight_amount'] > 0 ? $post_data['freight_amount'] : 0;
            $fsc_amount = isset($post_data['fsc_amount']) && $post_data['fsc_amount'] > 0 ? $post_data['fsc_amount'] : 0;

            if ($rate_id_data['freight_per'] > 0) {
                $rate_amt = ($freight_amount * $rate_id_data['freight_per']) / 100;
            } else if ($rate_id_data['shipment_per'] > 0) {
                $rate_amt = ($shipment_value * $rate_id_data['shipment_per']) / 100;
            } else if ($rate_id_data['freight_fsc_per'] > 0) {

                //THIS SETTING APPLY ONLY WHEN FSC APPLY DISABLE IN CHARGE
                $qry = "SELECT id FROM charge_master WHERE status IN(1,2) AND id='" . $rate_id_data['charge_id'] . "' AND is_fsc_apply=2";
                $qry_exe = $this->db->query($qry);
                $charge_res = $qry_exe->result_array();

                if (isset($charge_res) && is_array($charge_res) && count($charge_res) > 0) {
                    $rate_amt = (($freight_amount + $fsc_amount) * $rate_id_data['freight_fsc_per']) / 100;
                }
            } else if ($rate_id_data['fixed_amt'] > 0) {

                $rate_type = $rate_id_data['rate_per_type'];
                $rate_apply_amt = $rate_id_data['fixed_amt'];
                if ($rate_type == 1) {
                    $rate_amt = $total_pcs * $rate_apply_amt;
                } elseif ($rate_type == 2) {
                    $chargeable_wt = ceil($chargeable_wt);
                    $rate_amt = $chargeable_wt * $rate_apply_amt;
                } elseif ($rate_type == 3) {
                    $round_no = $this->round_half_number($chargeable_wt);
                    $rate_amt = $round_no * 2 * $rate_apply_amt;
                } else {
                    $rate_amt = $rate_apply_amt;
                }


                if ($rate_type == 4) {
                    if ((int)$value['min_per_box_wt'] > 0) {
                        if (isset($post_data['char_wt']) && is_array($post_data['char_wt']) && count($post_data['char_wt']) > 0) {
                            foreach ($post_data['char_wt'] as $lkey => $lvalue) {
                                if ($lvalue > $value['min_per_box_wt']) {
                                    $rate_pieces[$lkey] = $lvalue;
                                }
                            }
                        }
                    }
                } else if ($rate_id_data['grith_check'] == 1) {
                    if (isset($post_data['dim_len']) && is_array($post_data['dim_len']) && count($post_data['dim_len']) > 0) {
                        foreach ($post_data['dim_len'] as $lkey => $lvalue) {
                            $dim_arr[0] = $lvalue > 0 ? $lvalue : 0;
                            $dim_arr[1] = isset($post_data['dim_wid'][$lkey]) && $post_data['dim_wid'][$lkey] > 0 ? $post_data['dim_wid'][$lkey] : 0;
                            $dim_arr[2] = isset($post_data['dim_hei'][$lkey]) && $post_data['dim_hei'][$lkey] > 0 ? $post_data['dim_hei'][$lkey] : 0;


                            asort($dim_arr);
                            $total_dim = 0;
                            foreach ($dim_arr as $dkey => $dvalue) {
                                if ($dkey == 0) {
                                    $new_dim = $dvalue;
                                } elseif ($dkey == 1) {
                                    $new_dim = $dvalue * 2;
                                } elseif ($dkey == 2) {
                                    $new_dim = $dvalue * 2;
                                }
                                $total_dim = $total_dim + $new_dim;
                            }
                            $min_dimension = $rate_id_data['min_dimension'];
                            if ($min_dimension > 0) {
                                if ($total_dim < $min_dimension) {
                                    $rate_pieces[$lkey] = $lvalue;
                                }
                            }

                            $max_dimension = $rate_id_data['max_dimension'];
                            if ($max_dimension > 0) {
                                if ($total_dim > $max_dimension) {
                                    $rate_pieces[$lkey] = $lvalue;
                                }
                            }
                        }
                    }
                } else {
                    //CHECK MIN MAX DIMENSION PER PIECES

                    if ($rate_id_data['min_dim_per_pc'] == 1) {
                        $min_dimension = $rate_id_data['min_dimension'];

                        if ($min_dimension > 0) {
                            if (isset($post_data['dim_len']) && is_array($post_data['dim_len']) && count($post_data['dim_len']) > 0) {
                                foreach ($post_data['dim_len'] as $lkey => $lvalue) {
                                    if ($lvalue >= $min_dimension) {
                                        $rate_pieces[$lkey] = $lvalue;
                                    }
                                }
                            }

                            if (isset($post_data['dim_wid']) && is_array($post_data['dim_wid']) && count($post_data['dim_wid']) > 0) {
                                foreach ($post_data['dim_wid'] as $bkey => $bvalue) {
                                    if ($bvalue >= $min_dimension) {
                                        $rate_pieces[$bkey] = $bvalue;
                                    }
                                }
                            }


                            if (isset($post_data['dim_hei']) && is_array($post_data['dim_hei']) && count($post_data['dim_hei']) > 0) {
                                foreach ($post_data['dim_hei'] as $hkey => $hvalue) {
                                    if ($hvalue >= $min_dimension) {
                                        $rate_pieces[$hkey] = $hvalue;
                                    }
                                }
                            }
                        }
                    }


                    if ($rate_id_data['max_dim_per_pc'] == 1) {
                        $max_dimension = $rate_id_data['max_dimension'];
                        if ($max_dimension > 0) {
                            if (isset($post_data['dim_len']) && is_array($post_data['dim_len']) && count($post_data['dim_len']) > 0) {
                                foreach ($post_data['dim_len'] as $lkey => $lvalue) {
                                    if ($lvalue <= $max_dimension) {
                                        $rate_pieces[$lkey] = $lvalue;
                                    }
                                }
                            }

                            if (isset($post_data['dim_wid']) && is_array($post_data['dim_wid']) && count($post_data['dim_wid']) > 0) {
                                foreach ($post_data['dim_wid'] as $bkey => $bvalue) {
                                    if ($bvalue <= $max_dimension) {
                                        $rate_pieces[$bkey] = $bvalue;
                                    }
                                }
                            }

                            if (isset($post_data['dim_hei']) && is_array($post_data['dim_hei']) && count($post_data['dim_hei']) > 0) {
                                foreach ($post_data['dim_hei'] as $hkey => $hvalue) {
                                    if ($hvalue <= $max_dimension) {
                                        $rate_pieces[$hkey] = $hvalue;
                                    }
                                }
                            }
                        }
                    }
                }


                if (isset($rate_pieces) && is_array($rate_pieces) && count($rate_pieces) > 0) {
                    $extra_rate = count($rate_pieces) * $rate_apply_amt;
                    // $rate_amt = $rate_amt  +  $extra_rate;
                    $rate_amt = $extra_rate;
                } else if ($rate_type == 4) {
                    $rate_amt = 0;
                }

                if ($rate_amt == 0 && $rate_type != 4) {
                    $rate_amt = $rate_id_data['fixed_amt'];
                }
            }
        }

        if (isset($rate_id_data['min_amt']) && $rate_id_data['min_amt'] > 0 && $rate_type != 4) {
            if ($rate_amt < $rate_id_data['min_amt']) {
                $rate_amt = $rate_id_data['min_amt'];
            }
        }

        if (isset($rate_id_data['slab_wise_rate']) && $rate_id_data['slab_wise_rate'] == 1 && $rate_id_data['fixed_amt'] > 0 && $rate_id_data['min_amt'] > 0) {

            $slab_add = 0;
            $rate_type = $rate_id_data['rate_per_type'];
            $fixed_amt = $rate_id_data['fixed_amt'];
            $min_amt = $rate_id_data['min_amt'];

            $rate_amt = $min_amt;
            if ($rate_type == 1) {
                $slab_wt = $total_pcs - $rate_id_data['min_boxes'];
                $rate_amt += $slab_wt * $fixed_amt;
            } elseif ($rate_type == 2) {
                $chargeable_wt = ceil($chargeable_wt);
                $slab_wt = $chargeable_wt - $rate_id_data['min_chargeable_wt'];
                $rate_amt += $slab_wt * $fixed_amt;
            } elseif ($rate_type == 3) {
                $chargeable_wt = round($chargeable_wt, 2);
                $slab_wt = $chargeable_wt - $rate_id_data['min_chargeable_wt'];
                $rate_amt += $slab_wt * $fixed_amt * 2;
            }
        }
        $final_data['id'] = isset($rate_id_data['id']) ? $rate_id_data['id'] : 0;
        $final_data['freight_fsc_per'] = isset($rate_id_data['freight_fsc_per']) ? $rate_id_data['freight_fsc_per'] : 0;
        $final_data['rate_amt'] = isset($rate_amt) ? $rate_amt : 0;
        if (isset($post) && is_array($post) && count($post) > 0) {
            return $final_data;
        } else {
            echo json_encode($final_data);
        }
    }



    public function get_purchase_charge_old($post = array())
    {
        if (isset($post) && is_array($post) && count($post) > 0) {
            $post_data = $post;
        } else {
            $post_data = $this->input->post();
        }

        $result = array();
        if (isset($post_data) && is_array($post_data) && count($post_data) > 0) {
            $customer_id = isset($post_data['customer_id']) && $post_data['customer_id'] > 0 ? $post_data['customer_id'] : 'NULL';
            $product_id = isset($post_data['product_id']) && $post_data['product_id'] > 0 ? $post_data['product_id'] : 'NULL';
            $vendor_id = isset($post_data['vendor_id']) && $post_data['vendor_id'] > 0 ? $post_data['vendor_id'] : 'NULL';
            $co_vendor_id = isset($post_data['co_vendor_id']) && $post_data['co_vendor_id'] > 0 ? $post_data['co_vendor_id'] : 'NULL';
            $destination_id = isset($post_data['destination_id']) && $post_data['destination_id'] > 0 ? $post_data['destination_id'] : 'NULL';
            $dest_zone_id = isset($post_data['dest_zone_id']) && $post_data['dest_zone_id'] > 0 ? $post_data['dest_zone_id'] : 'NULL';
            $origin_id = isset($post_data['origin_id']) && $post_data['origin_id'] > 0 ? $post_data['origin_id'] : 'NULL';
            $ori_zone_id = isset($post_data['ori_zone_id']) && $post_data['ori_zone_id'] > 0 ? $post_data['ori_zone_id'] : 'NULL';

            $dest_hub_id = isset($post_data['dest_hub_id']) && $post_data['dest_hub_id'] > 0 ? $post_data['dest_hub_id'] : 'NULL';
            $ori_hub_id = isset($post_data['ori_hub_id']) && $post_data['ori_hub_id'] > 0 ? $post_data['ori_hub_id'] : 'NULL';

            $charge_id = isset($post_data['charge_id']) && $post_data['charge_id'] > 0 ? $post_data['charge_id'] : '';
            $booking_date = isset($post_data['booking_date']) && $post_data['booking_date'] > 0 ? $post_data['booking_date'] : '';

            $chargeable_wt = isset($post_data['chargeable_wt']) && $post_data['chargeable_wt'] > 0 ? $post_data['chargeable_wt'] : 0;
            $actual_wt = isset($post_data['actual_wt']) && $post_data['actual_wt'] > 0 ? $post_data['actual_wt'] : 0;
            $volumetric_wt = isset($post_data['volumetric_wt']) && $post_data['volumetric_wt'] > 0 ? $post_data['volumetric_wt'] : 0;
            $total_pcs = isset($post_data['total_pcs']) && $post_data['total_pcs'] > 0 ? $post_data['total_pcs'] : 0;
            $shipment_value = isset($post_data['shipment_value']) && $post_data['shipment_value'] > 0 ? $post_data['shipment_value'] : 0;
            $charge_type = isset($post_data['charge_type']) && $post_data['charge_type'] > 0 ? $post_data['charge_type'] : 0;

            if ($charge_id > 0 && $booking_date != '') {

                //CHECK RATE MODIFIER HEAD PRESENT
                if ($customer_id > 0 && $booking_date != '' && $booking_date != '1970-01-01' && $booking_date != '0000-00-00') {
                    $query = "SELECT contract_customer_id,from_date,till_date FROM customer_contract_head
                    WHERE status IN(1,2) AND customer_id='" . $post_data['customer_id'] . "' AND head_type =2 
                    AND '" . $booking_date . "' BETWEEN from_date AND till_date ORDER BY from_date DESC";
                    $query_exe = $this->db->query($query);
                    $customer_rate_head = $query_exe->row_array();
                }

                if (isset($customer_rate_head) && is_array($customer_rate_head) && count($customer_rate_head) > 0) {
                    $rate_customer_id = $customer_rate_head['contract_customer_id'];
                } else {
                    $rate_customer_id = $customer_id;
                }

                $qry = "SELECT r.*,rmc.module_id as customer_id,rmp.module_id as product_id,
                rms.module_id as vendor_id,rmv.module_id as co_vendor_id,
                rmdl.module_id as destination_id,rmdz.module_id as dest_zone_id,
                rmol.module_id as origin_id,rmoz.module_id as ori_zone_id FROM `rate_modifier` r 
            LEFT OUTER JOIN rate_modifier_data rmc ON(r.id=rmc.rate_modifier_id AND rmc.status IN(1,2) AND rmc.module_type=1)
            LEFT OUTER JOIN rate_modifier_data rmp ON(r.id=rmp.rate_modifier_id AND rmp.status IN(1,2) AND rmp.module_type=2)
            LEFT OUTER JOIN rate_modifier_data rms ON(r.id=rms.rate_modifier_id AND rms.status IN(1,2) AND rms.module_type=3)
            LEFT OUTER JOIN rate_modifier_data rmv ON(r.id=rmv.rate_modifier_id AND rmv.status IN(1,2) AND rmv.module_type=4)
            LEFT OUTER JOIN rate_modifier_data rmdl ON(r.id=rmdl.rate_modifier_id AND rmdl.status IN(1,2) AND rmdl.module_type=5)
            LEFT OUTER JOIN rate_modifier_data rmdz ON(r.id=rmdz.rate_modifier_id AND rmdz.status IN(1,2) AND rmdz.module_type=6)
            LEFT OUTER JOIN rate_modifier_data rmol ON(r.id=rmol.rate_modifier_id AND rmol.status IN(1,2) AND rmol.module_type=7)
            LEFT OUTER JOIN rate_modifier_data rmoz ON(r.id=rmoz.rate_modifier_id AND rmoz.status IN(1,2) AND rmoz.module_type=8)
            WHERE r.status IN(1,2) AND (rmc.module_id=" . $rate_customer_id . " OR rmc.module_id is NULL) 
           AND (rmp.module_id=" . $product_id . " OR rmp.module_id is NULL) 
           AND (rms.module_id=" . $vendor_id . " OR rms.module_id is NULL) 
           AND (rmv.module_id=" . $co_vendor_id . " OR rmv.module_id is NULL) 
           AND (r.min_chargeable_wt<=0 OR r.max_chargeable_wt<=0 OR (r.min_chargeable_wt<=" . $chargeable_wt . " AND r.max_chargeable_wt>=" . $chargeable_wt . "))
           AND (r.min_actual_wt<=0 OR r.max_actual_wt<=0 OR (r.min_actual_wt<=" . $actual_wt . " AND r.max_actual_wt>=" . $actual_wt . "))
           AND (r.min_volume_wt<=0 OR r.max_volume_wt<=0 OR (r.min_volume_wt<=" . $volumetric_wt . " AND r.max_volume_wt>=" . $volumetric_wt . "))
           AND (r.min_boxes<=0 OR r.max_boxes<=0 OR (r.min_boxes<=" . $total_pcs . " AND r.max_boxes>=" . $total_pcs . "))
           AND (r.min_shipment_value<=0 OR r.max_shipment_value<=0 OR (r.min_shipment_value<=" . $shipment_value . " AND r.max_shipment_value>=" . $shipment_value . "))
           AND r.billing_type IN(1," . $charge_type . ")
           AND r.charge_id='" . $charge_id . "' AND '" . $booking_date . "' BETWEEN r.effective_from AND r.effective_to";


                $query_exe = $this->db->query($qry);
                $rate_result  = $query_exe->result_array();


                if (isset($rate_result) && is_array($rate_result) && count($rate_result) > 0) {
                    foreach ($rate_result as $ra_key => $ra_value) {
                        $rate_group[$ra_value['id']] = $ra_value;
                        if ($ra_value['destination_id'] > 0) {
                            $rate_group[$ra_value['id']]['destination_id_arr'][] = $ra_value['destination_id'];
                        }
                        if ($ra_value['dest_zone_id'] > 0) {
                            $rate_group[$ra_value['id']]['dest_zone_id_arr'][] = $ra_value['dest_zone_id'];
                        }
                        if ($ra_value['dest_hub_id'] > 0) {
                            $rate_group[$ra_value['id']]['dest_hub_id_arr'][] = $ra_value['dest_hub_id'];
                        }

                        if ($ra_value['origin_id'] > 0) {
                            $rate_group[$ra_value['id']]['origin_id_arr'][] = $ra_value['origin_id'];
                        }
                        if ($ra_value['ori_zone_id'] > 0) {
                            $rate_group[$ra_value['id']]['ori_zone_id_arr'][] = $ra_value['ori_zone_id'];
                        }
                        if ($ra_value['ori_hub_id'] > 0) {
                            $rate_group[$ra_value['id']]['ori_hub_id_arr'][] = $ra_value['ori_hub_id'];
                        }
                    }
                }

                if (isset($rate_group) && is_array($rate_group) && count($rate_group) > 0) {
                    foreach ($rate_group as $gkey => $gvalue) {
                        $rate_match = 0;

                        if (isset($gvalue['destination_id_arr']) && in_array($destination_id, $gvalue['destination_id_arr'])) {
                            $rate_match += 1;
                        } else if (isset($gvalue['dest_zone_id_arr']) && in_array($dest_zone_id, $gvalue['dest_zone_id_arr'])) {
                            $rate_match += 1;
                        } else if (isset($gvalue['dest_hub_id_arr']) && in_array($dest_hub_id, $gvalue['dest_hub_id_arr'])) {
                            $rate_match += 1;
                        } else if (!isset($gvalue['destination_id_arr']) && !isset($gvalue['dest_zone_id_arr']) && !isset($gvalue['dest_hub_id_arr'])) {
                            $rate_match += 1;
                        }

                        if (isset($gvalue['origin_id_arr']) && in_array($origin_id, $gvalue['origin_id_arr'])) {
                            $rate_match += 1;
                        } else if (isset($gvalue['ori_zone_id_arr']) && in_array($ori_zone_id, $gvalue['ori_zone_id_arr'])) {
                            $rate_match += 1;
                        } else if (isset($gvalue['ori_hub_id_arr']) && in_array($ori_hub_id, $gvalue['ori_hub_id_arr'])) {
                            $rate_match += 1;
                        } else if (!isset($gvalue['origin_id_arr']) && !isset($gvalue['ori_zone_id_arr']) && !isset($gvalue['ori_hub_id_arr'])) {
                            $rate_match += 1;
                        }

                        if ($rate_match == 2) {
                            $mapping_effective[$gvalue['effective_from']][$gkey] = $rate_match;
                            $mapping_match[$gkey] = $gvalue;
                            $mapping_match[$gkey]['point'] = $rate_match;
                        }
                    }


                    if (isset($mapping_match) && is_array($mapping_match) && count($mapping_match) > 0) {
                        $max_mapping_date =  max(array_column($mapping_match, 'effective_from'));


                        if (isset($mapping_effective[$max_mapping_date])) {
                            arsort($mapping_effective[$max_mapping_date], SORT_NUMERIC);
                            $latest_mapping_id = array_keys($mapping_effective[$max_mapping_date])[0];

                            if (isset($mapping_match[$latest_mapping_id])) {
                                if (isset($mapping_match[$latest_mapping_id][0])) {
                                    $result = $mapping_match[$latest_mapping_id];
                                } else {
                                    $result[] = $mapping_match[$latest_mapping_id];
                                }
                            }
                        }
                    }
                }

                $condition_arr = array(
                    'customer_id', 'product_id', 'vendor_id', 'co_vendor_id',
                    'destination_id', 'dest_zone_id', 'origin_id', 'ori_zone_id'
                );
                //CHECK MAXIMUM CONDITION MATCH
                $highest_match = 0;
                $highest_date = '';


                if (isset($result) && is_array($result) && count($result) > 0) {
                    foreach ($result as $key => $value) {
                        if ($chargeable_wt == '') {
                            $chargeable_wt = 0;
                        }
                        if ($actual_wt == '') {
                            $actual_wt = 0;
                        }
                        if ($volumetric_wt == '') {
                            $volumetric_wt = 0;
                        }
                        if ($total_pcs == '') {
                            $total_pcs = 0;
                        }
                        if ($shipment_value == '') {
                            $shipment_value = 0;
                        }

                        if ((int)$value['min_chargeable_wt'] > 0 && $chargeable_wt < $value['min_chargeable_wt']) {
                            continue;
                        }
                        if ((int)$value['min_actual_wt'] > 0 && $actual_wt < $value['min_actual_wt']) {
                            continue;
                        }
                        if ((int)$value['min_volume_wt'] > 0 && $volumetric_wt < $value['min_volume_wt']) {
                            continue;
                        }
                        if ((int)$value['min_boxes'] > 0 && $total_pcs < $value['min_boxes']) {
                            continue;
                        }
                        if ((int)$value['min_shipment_value'] > 0 && $shipment_value < $value['min_shipment_value']) {
                            continue;
                        }

                        if ((int)$value['max_chargeable_wt'] > 0 && $chargeable_wt > $value['max_chargeable_wt']) {
                            continue;
                        }
                        if ((int)$value['max_actual_wt'] > 0 && $actual_wt > $value['max_actual_wt']) {
                            continue;
                        }
                        if ((int)$value['max_volume_wt'] > 0 && $volumetric_wt > $value['max_volume_wt']) {
                            continue;
                        }
                        if ((int)$value['max_boxes'] > 0 && $total_pcs > $value['max_boxes']) {
                            continue;
                        }
                        if ((int)$value['max_shipment_value'] > 0 && $shipment_value > $value['max_shipment_value']) {
                            continue;
                        }

                        //MIN DIMENSION

                        if ((int)$value['min_dimension'] > 0) {
                            $min_dimension_match = array();
                            if (isset($post_data['dim_len']) && is_array($post_data['dim_len']) && count($post_data['dim_len']) > 0) {
                                foreach ($post_data['dim_len'] as $lkey => $lvalue) {
                                    if ($lvalue >= $value['min_dimension']) {
                                        $min_dimension_match[] = 1;
                                    }
                                }
                            }
                            if (isset($post_data['dim_wid']) && is_array($post_data['dim_wid']) && count($post_data['dim_wid']) > 0) {
                                foreach ($post_data['dim_wid'] as $bkey => $bvalue) {
                                    if ($bvalue >= $value['min_dimension']) {
                                        $min_dimension_match[] = 1;
                                    }
                                }
                            }
                            if (isset($post_data['dim_hei']) && is_array($post_data['dim_hei']) && count($post_data['dim_hei']) > 0) {
                                foreach ($post_data['dim_hei'] as $hkey => $hvalue) {
                                    if ($hvalue >= $value['min_dimension']) {
                                        $min_dimension_match[] = 1;
                                    }
                                }
                            }


                            if (count($min_dimension_match) == 0) {
                                continue;
                            }
                        }

                        //MAX DIMENSION
                        if ((int)$value['max_dimension'] > 0) {
                            $max_dimension_match = array();
                            if (isset($post_data['dim_len']) && is_array($post_data['dim_len']) && count($post_data['dim_len']) > 0) {
                                foreach ($post_data['dim_len'] as $lkey => $lvalue) {
                                    if ($lvalue <= $value['max_dimension']) {
                                        $max_dimension_match[] = 1;
                                    }
                                }
                            }
                            if (isset($post_data['dim_wid']) && is_array($post_data['dim_wid']) && count($post_data['dim_wid']) > 0) {
                                foreach ($post_data['dim_wid'] as $bkey => $bvalue) {
                                    if ($bvalue <= $value['max_dimension']) {
                                        $max_dimension_match[] = 1;
                                    }
                                }
                            }
                            if (isset($post_data['dim_hei']) && is_array($post_data['dim_hei']) && count($post_data['dim_hei']) > 0) {
                                foreach ($post_data['dim_hei'] as $hkey => $hvalue) {
                                    if ($hvalue <= $value['max_dimension']) {
                                        $max_dimension_match[] = 1;
                                    }
                                }
                            }

                            if (count($max_dimension_match) == 0) {
                                continue;
                            }
                        }

                        //MIN PER BOX WEIGHT

                        // if ((int)$value['min_per_box_wt'] > 0) {
                        //     if (isset($post_data['act_wt']) && is_array($post_data['act_wt']) && count($post_data['act_wt']) > 0) {
                        //         foreach ($post_data['act_wt'] as $lkey => $lvalue) {
                        //             if ($lvalue < $value['min_per_box_wt']) {
                        //                 $min_wt_breach[] = 1;
                        //             }
                        //         }
                        //     }

                        //     if (isset($min_wt_breach) && is_array($min_wt_breach) && count($min_wt_breach) > 0) {
                        //         continue;
                        //     }
                        // }

                        // //MAX PER BOX WEIGHT
                        // if ((int)$value['max_per_box_wt'] > 0) {
                        //     if (isset($post_data['act_wt']) && is_array($post_data['act_wt']) && count($post_data['act_wt']) > 0) {
                        //         foreach ($post_data['act_wt'] as $lkey => $lvalue) {
                        //             if ($lvalue > $value['max_per_box_wt']) {
                        //                 $max_wt_breach[] = 1;
                        //             }
                        //         }
                        //     }
                        //     if (isset($max_wt_breach) && is_array($max_wt_breach) && count($max_wt_breach) > 0) {
                        //         continue;
                        //     }
                        // }

                        $condition_match_cnt = 0;
                        foreach ($condition_arr as $ckey => $cvalue) {
                            if ($value[$cvalue] > 0) {
                                $condition_match_cnt = $condition_match_cnt + 1;
                            }
                        }

                        if ($condition_match_cnt > $highest_match) {
                            $highest_match = $condition_match_cnt;
                            $highest_date = $value['effective_from'];
                            $rate_id_data = $value;
                        } else if ($condition_match_cnt == $highest_match && $value['effective_from'] >  $highest_date) {
                            $highest_match = $condition_match_cnt;
                            $highest_date = $value['effective_from'];
                            $rate_id_data = $value;
                        }

                        if ($key == 0) {
                            $highest_match = $condition_match_cnt;
                            $highest_date = $value['effective_from'];
                            $rate_id_data = $value;
                        }
                        // $match_rate[$value['id']] =  $condition_match_cnt;
                        // $match_rate_date[$value['id']] =  $value['effective_from'];
                    }
                } else {
                    $rate_id_data = isset($result[0]) ? $result[0] : array();
                }
            }
        }
        $rate_amt = 0;
        $rate_apply_amt = 0;

        if (isset($rate_id_data) && is_array($rate_id_data) && count($rate_id_data) > 0) {
            $freight_amount = isset($post_data['freight_amount']) && $post_data['freight_amount'] > 0 ? $post_data['freight_amount'] : 0;
            $fsc_amount = isset($post_data['fsc_amount']) && $post_data['fsc_amount'] > 0 ? $post_data['fsc_amount'] : 0;
            if ($rate_id_data['freight_per'] > 0) {
                $rate_amt = ($freight_amount * $rate_id_data['freight_per']) / 100;
            } else if ($rate_id_data['shipment_per'] > 0) {
                $rate_amt = ($shipment_value * $rate_id_data['shipment_per']) / 100;
            } else if ($rate_id_data['freight_fsc_per'] > 0) {
                //THIS SETTING APPLY ONLY WHEN FSC APPLY DISABLE IN CHARGE
                $qry = "SELECT id FROM charge_master WHERE status IN(1,2) AND id='" . $rate_id_data['charge_id'] . "' AND is_fsc_apply=2";
                $qry_exe = $this->db->query($qry);
                $charge_res = $qry_exe->result_array();

                if (isset($charge_res) && is_array($charge_res) && count($charge_res) > 0) {
                    $rate_amt = (($freight_amount + $fsc_amount) * $rate_id_data['freight_fsc_per']) / 100;
                }
            } else if ($rate_id_data['fixed_amt'] > 0) {

                $rate_type = $rate_id_data['rate_per_type'];
                $rate_apply_amt = $rate_id_data['fixed_amt'];
                if ($rate_type == 1) {
                    $rate_amt = $total_pcs * $rate_apply_amt;
                } elseif ($rate_type == 2) {
                    $chargeable_wt = ceil($chargeable_wt);
                    $rate_amt = $chargeable_wt * $rate_apply_amt;
                } elseif ($rate_type == 3) {
                    $round_no = $this->round_half_number($chargeable_wt);
                    $rate_amt = $round_no * 2 * $rate_apply_amt;
                } else {
                    $rate_amt = $rate_apply_amt;
                }


                if ($rate_type == 4) {
                    if ((int)$value['min_per_box_wt'] > 0) {
                        if (isset($post_data['char_wt']) && is_array($post_data['char_wt']) && count($post_data['char_wt']) > 0) {
                            foreach ($post_data['char_wt'] as $lkey => $lvalue) {
                                if ($lvalue > $value['min_per_box_wt']) {
                                    $rate_pieces[$lkey] = $lvalue;
                                }
                            }
                        }
                    }
                } else  if ($rate_id_data['grith_check'] == 1) {
                    if (isset($post_data['dim_len']) && is_array($post_data['dim_len']) && count($post_data['dim_len']) > 0) {
                        foreach ($post_data['dim_len'] as $lkey => $lvalue) {
                            $dim_arr[0] = $lvalue > 0 ? $lvalue : 0;
                            $dim_arr[1] = isset($post_data['dim_wid'][$lkey]) && $post_data['dim_wid'][$lkey] > 0 ? $post_data['dim_wid'][$lkey] : 0;
                            $dim_arr[2] = isset($post_data['dim_hei'][$lkey]) && $post_data['dim_hei'][$lkey] > 0 ? $post_data['dim_hei'][$lkey] : 0;


                            asort($dim_arr);
                            $total_dim = 0;
                            foreach ($dim_arr as $dkey => $dvalue) {
                                if ($dkey == 0) {
                                    $new_dim = $dvalue;
                                } elseif ($dkey == 1) {
                                    $new_dim = $dvalue * 2;
                                } elseif ($dkey == 2) {
                                    $new_dim = $dvalue * 2;
                                }
                                $total_dim = $total_dim + $new_dim;
                            }
                            $min_dimension = $rate_id_data['min_dimension'];
                            if ($min_dimension > 0) {
                                if ($total_dim < $min_dimension) {
                                    $rate_pieces[$lkey] = $lvalue;
                                }
                            }

                            $max_dimension = $rate_id_data['max_dimension'];
                            if ($max_dimension > 0) {
                                if ($total_dim > $max_dimension) {
                                    $rate_pieces[$lkey] = $lvalue;
                                }
                            }
                        }
                    }
                } else {
                    //CHECK MIN MAX DIMENSION PER PIECES
                    if ($rate_id_data['min_dim_per_pc'] == 1) {
                        $min_dimension = $rate_id_data['min_dimension'];
                        if ($min_dimension > 0) {
                            if (isset($post_data['dim_len']) && is_array($post_data['dim_len']) && count($post_data['dim_len']) > 0) {
                                foreach ($post_data['dim_len'] as $lkey => $lvalue) {
                                    if ($lvalue < $min_dimension) {
                                        $rate_pieces[$lkey] = $lvalue;
                                    }
                                }
                            }

                            if (isset($post_data['dim_wid']) && is_array($post_data['dim_wid']) && count($post_data['dim_wid']) > 0) {
                                foreach ($post_data['dim_wid'] as $bkey => $bvalue) {
                                    if ($bvalue < $min_dimension) {
                                        $rate_pieces[$bkey] = $bvalue;
                                    }
                                }
                            }

                            if (isset($post_data['dim_hei']) && is_array($post_data['dim_hei']) && count($post_data['dim_hei']) > 0) {
                                foreach ($post_data['dim_hei'] as $hkey => $hvalue) {
                                    if ($hvalue < $min_dimension) {
                                        $rate_pieces[$hkey] = $hvalue;
                                    }
                                }
                            }
                        }
                    }


                    if ($rate_id_data['max_dim_per_pc'] == 1) {
                        $max_dimension = $rate_id_data['max_dimension'];
                        if ($max_dimension > 0) {
                            if (isset($post_data['dim_len']) && is_array($post_data['dim_len']) && count($post_data['dim_len']) > 0) {
                                foreach ($post_data['dim_len'] as $lkey => $lvalue) {
                                    if ($lvalue > $max_dimension) {
                                        $rate_pieces[$lkey] = $lvalue;
                                    }
                                }
                            }

                            if (isset($post_data['dim_wid']) && is_array($post_data['dim_wid']) && count($post_data['dim_wid']) > 0) {
                                foreach ($post_data['dim_wid'] as $bkey => $bvalue) {
                                    if ($bvalue > $max_dimension) {
                                        $rate_pieces[$bkey] = $bvalue;
                                    }
                                }
                            }

                            if (isset($post_data['dim_hei']) && is_array($post_data['dim_hei']) && count($post_data['dim_hei']) > 0) {
                                foreach ($post_data['dim_hei'] as $hkey => $hvalue) {
                                    if ($hvalue > $max_dimension) {
                                        $rate_pieces[$hkey] = $hvalue;
                                    }
                                }
                            }
                        }
                    }
                }

                if (isset($rate_pieces) && is_array($rate_pieces) && count($rate_pieces) > 0) {
                    $extra_rate = count($rate_pieces) * $rate_apply_amt;
                    // $rate_amt = $rate_amt  +  $extra_rate;
                    $rate_amt = $extra_rate;
                } else if ($rate_type == 4) {
                    $rate_amt = 0;
                }

                if ($rate_amt == 0 && $rate_type != 4) {
                    $rate_amt = $rate_id_data['fixed_amt'];
                }
            }
        }

        if (isset($rate_id_data['min_amt']) && $rate_id_data['min_amt'] > 0 && $rate_type != 4) {
            if ($rate_amt < $rate_id_data['min_amt']) {
                $rate_amt = $rate_id_data['min_amt'];
            }
        }


        if (isset($rate_id_data['slab_wise_rate']) && $rate_id_data['slab_wise_rate'] == 1 && $rate_id_data['fixed_amt'] > 0 && $rate_id_data['min_amt'] > 0) {

            $slab_add = 0;
            $rate_type = $rate_id_data['rate_per_type'];
            $fixed_amt = $rate_id_data['fixed_amt'];
            $min_amt = $rate_id_data['min_amt'];

            $rate_amt = $min_amt;
            if ($rate_type == 1) {
                $slab_wt = $total_pcs - $rate_id_data['min_boxes'];
                $rate_amt += $slab_wt * $fixed_amt;
            } elseif ($rate_type == 2) {
                $chargeable_wt = ceil($chargeable_wt);
                $slab_wt = $chargeable_wt - $rate_id_data['min_chargeable_wt'];
                $rate_amt += $slab_wt * $fixed_amt;
            } elseif ($rate_type == 3) {
                $chargeable_wt = round($chargeable_wt, 2);
                $slab_wt = $chargeable_wt - $rate_id_data['min_chargeable_wt'];
                $rate_amt += $slab_wt * $fixed_amt * 2;
            }
        }


        $final_data['id'] = isset($rate_id_data['id']) ? $rate_id_data['id'] : 0;
        $final_data['freight_fsc_per'] = isset($rate_id_data['freight_fsc_per']) ? $rate_id_data['freight_fsc_per'] : 0;
        $final_data['rate_amt'] = isset($rate_amt) ? $rate_amt : 0;
        if (isset($post) && is_array($post) && count($post) > 0) {
            return $final_data;
        } else {
            echo json_encode($final_data);
        }
    }
    public function get_customer_gst()
    {
        $result = array();
        $customer_id = $this->input->post('customer_id');
        if ($customer_id > 0) {
            $query = "select id,is_gst_apply from customer where status = 1 AND id='" . $customer_id . "'";
            $query_exec = $this->db->query($query);
            $ans = $query_exec->row_array();
        }

        if (isset($ans) && is_array($ans) && count($ans) > 0) {
            $result  = $ans;
        } else {
            $result['error'] = 'customer not found';
        }
        echo json_encode($result);
    }
    public function get_vendor_gst()
    {
        $result = array();
        $co_vendor_id = $this->input->post('co_vendor_id');
        if ($co_vendor_id > 0) {
            $query = "select id,is_gst_apply from co_vendor where status = 1 AND id='" . $co_vendor_id . "'";
            $query_exec = $this->db->query($query);
            $ans = $query_exec->row_array();
        }

        if (isset($ans) && is_array($ans) && count($ans) > 0) {
            $result  = $ans;
        } else {
            $result['error'] = 'customer not found';
        }
        echo json_encode($result);
    }

    public function get_product_data()
    {
        $result = array();
        $product_id = $this->input->post('product_id');
        if ($product_id > 0) {
            $query = "select id,pdt_format from product where status = 1 AND id='" . $product_id . "'";
            $query_exec = $this->db->query($query);
            $result = $query_exec->row_array();
        }
        echo json_encode($result);
    }

    function get_customer_project()
    {
        $this->load->helper('frontend_common');
        $response_data['all_project'] = array();
        $post_data = $this->input->post();
        $customer_id = $post_data['customer_id'] > 0 ? $post_data['customer_id'] : 0;
        $query = "SELECT id,name,code FROM project WHERE status IN(1,2) AND customer_id='" . $customer_id . "'";
        $query_exe = $this->db->query($query);
        if ($query_exe->num_rows() > 0) {
            $response_data['all_project'] = $query_exe->result_array();
        }

        //GET CUSTOMER BRAND
        $query = "SELECT id,name,code FROM brand WHERE status IN(1,2) AND customer_id='" . $customer_id . "'";
        $query_exe = $this->db->query($query);
        if ($query_exe->num_rows() > 0) {
            $response_data['all_brand'] = $query_exe->result_array();
        }
        //GET AWB EDIT PERMISSION
        $query1 = "SELECT id,config_value FROM module_setting WHERE status IN(1,2) AND module_type=1 AND module_id='" . $customer_id . "' AND config_key='enable_edited_awb_no'";
        $qry_exe1 = $this->db->query($query1);
        $customer_setting = $qry_exe1->row_array();

        $qry = "SELECT c.id,m.config_key,m.config_value FROM customer c 
        JOIN module_setting m ON(c.id=m.module_id AND m.module_type=1) 
        WHERE c.id='" . $customer_id . "' AND c.status IN(1,2) AND m.status IN(1,2) 
        AND m.config_key='cust_parcel_wise_discounted_wt'";
        $qry_exe = $this->db->query($qry);
        $response_data['customer_setting'] = $qry_exe->row_array();

        if (isset($customer_setting) && is_array($customer_setting) && count($customer_setting) > 0) {
            $response_data['edit_awb_no'] = true;
        }

        $setting = get_all_app_setting(" AND module_name IN('docket')");
        if (isset($setting['disable_payment_type_set_from_customer']) && $setting['disable_payment_type_set_from_customer'] == 1) {
            $query = "SELECT id,name,payment_type FROM customer WHERE status IN(1,2) AND id='" . $customer_id . "'";
            $query_exe = $this->db->query($query);
            $cust_data = $query_exe->row_array();
        }
        $response_data['payment_type'] = isset($cust_data['payment_type']) ? $cust_data['payment_type'] : 0;

        if (isset($setting['get_token_no_from_pickup_sheet']) && $setting['get_token_no_from_pickup_sheet'] == 1) {
            $pick_query = "SELECT pr.id,pr.ref_no,pr.customer_id,prd.total_awb FROM pickup_request pr LEFT OUTER JOIN pickup_request_detail prd ON(pr.id = prd.pickup_request_id) WHERE pr.status IN(1,2) AND prd.status IN(1,2) AND pr.ref_no != '' AND pr.customer_id='" . $customer_id . "'";
            $pick_query_exe = $this->db->query($pick_query);
            $pickup_data = $pick_query_exe->result_array();

            if (isset($pickup_data) && is_array($pickup_data) && count($pickup_data) > 0) {
                foreach ($pickup_data as $key => $value) {
                    $pick_query = "SELECT count(docket_id) as awb_count FROM docket_extra_field WHERE status IN(1,2) AND token_number='" . $value['ref_no'] . "'";
                    $pick_query_exe = $this->db->query($pick_query);
                    $pickup_data1 = $pick_query_exe->row_array();

                    if (isset($pickup_data1) && is_array($pickup_data1) && count($pickup_data1) > 0) {
                        if ($value['total_awb'] == $pickup_data1['awb_count']) {
                            unset($pickup_data[$key]);
                        }
                    }
                }
            }
        }
        $response_data['pickup_data'] = isset($pickup_data) ? $pickup_data : "";

        //GET CUSTOMER TYPE
        $all_customer_type_data = get_all_customer(" AND id=" . $customer_id);
        if (isset($all_customer_type_data) && is_array($all_customer_type_data) && count($all_customer_type_data) > 0) {
            $customer_type_id = $all_customer_type_data[$customer_id]['customer_type'];
            $customer_type_data = gat_all_cust_type();
        }

        $response_data['customer_type_data'] = $customer_type_data[$customer_type_id];

        //GET CUSTOMER COMPANY
        $query = "SELECT c.id,c.name FROM customer cu 
        JOIN company_master c ON(c.id=cu.company_id) WHERE cu.status IN(1,2) AND c.status IN(1,2)  AND cu.id='" . $customer_id . "'";
        $query_exe = $this->db->query($query);
        $response_data['company_data'] = $query_exe->row_array();
        echo json_encode($response_data);
    }

    function get_customer_details()
    {
        $this->load->helper('frontend_common');
        $post_data = $this->input->post();
        $customer_id = $post_data['customer_id'] > 0 ? $post_data['customer_id'] : 0;

        //GET CUSTOMER COMPANY
        $query = "SELECT cu.id,cu.origin_id,cu.company_id FROM customer cu WHERE cu.status IN(1,2) AND cu.id='" . $customer_id . "'";
        $query_exe = $this->db->query($query);
        $response_data['customer_data'] = $query_exe->row_array();

        if (isset($response_data['customer_data']['origin_id']) && $response_data['customer_data']['origin_id'] > 0) {
            $all_location = get_all_location(" AND id='" . $response_data['customer_data']['origin_id'] . "'");
            $response_data['origin_id'] = $response_data['customer_data']['origin_id'];
            $response_data['origin_code'] = isset($all_location[$response_data['origin_id']]) ? $all_location[$response_data['origin_id']]['code'] : '';
            $response_data['origin_name'] = isset($all_location[$response_data['origin_id']]) ? $all_location[$response_data['origin_id']]['name'] : '';
        }
        if (isset($response_data['customer_data']['company_id']) && $response_data['customer_data']['company_id'] > 0) {
            $all_location = get_all_company(" AND id='" . $response_data['customer_data']['company_id'] . "'");
            $response_data['company_id'] = $response_data['customer_data']['company_id'];
        }

        echo json_encode($response_data);
    }

    function get_customer_brand()
    {
        $response_data = array();
        $post_data = $this->input->post();
        $customer_id = $post_data['customer_id'] > 0 ? $post_data['customer_id'] : 0;
        $query = "SELECT id,name,code FROM brand WHERE status IN(1,2) AND customer_id='" . $customer_id . "'";
        $query_exe = $this->db->query($query);
        if ($query_exe->num_rows() > 0) {
            $response_data  = $query_exe->result_array();
        }
        echo json_encode($response_data);
    }


    function get_material_inventory()
    {
        $response_data['all_inventory'] = array();
        $post_data = $this->input->post();
        $material_id = $post_data['material_id'] > 0 ? $post_data['material_id'] : 0;
        $appendqry = "";
        if ($material_id > 0) {
            $appendqry .= " AND material_id='" . $material_id . "'";
        }
        $customer_id = $post_data['customer_id'] > 0 ? $post_data['customer_id'] : 0;
        $dispatch_type = $post_data['dispatch_type'] > 0 ? $post_data['dispatch_type'] : 0;
        if ((isset($dispatch_type) && $dispatch_type > 0 && $dispatch_type == 2) && (isset($customer_id) && $customer_id > 0)) {
            $appendqry .= " AND customer_id = '" . $customer_id . "' AND available_quantity > 0";
        }
        $query = "SELECT id,inventory_no FROM inventory WHERE status IN(1,2)" . $appendqry;
        $query_exe = $this->db->query($query);
        if ($query_exe->num_rows() > 0) {
            $response_data['all_inventory']  = $query_exe->result_array();
        }
        echo json_encode($response_data);
    }

    function get_material_name()
    {
        $response_data['all_inventory'] = array();
        $post_data = $this->input->post();
        $inventory_id = $post_data['inventory_id'] > 0 ? $post_data['inventory_id'] : 0;
        $appendqry = "";
        $customer_id = $post_data['customer_id'] > 0 ? $post_data['customer_id'] : 0;
        $dispatch_type = $post_data['dispatch_type'] > 0 ? $post_data['dispatch_type'] : 0;
        if ((isset($dispatch_type) && $dispatch_type > 0 && $dispatch_type == 2) && (isset($customer_id) && $customer_id > 0)) {
            $appendqry .= " AND customer_id = '" . $customer_id . "' AND available_quantity > 0";
        }
        $query = "SELECT material_id,available_quantity FROM inventory WHERE status IN(1,2) AND id='" . $inventory_id . "'" . $appendqry;
        $query_exe = $this->db->query($query);
        if ($query_exe->num_rows() > 0) {
            $response_data['all_inventory']  = $query_exe->result_array();
        }
        if (isset($response_data['all_inventory']) && is_array($response_data['all_inventory']) && count($response_data['all_inventory'])) {
            $mat_query = "SELECT id,name,code FROM material WHERE status IN(1,2) AND id = '" . $response_data['all_inventory'][0]['material_id'] . "'";
            $mat_query_exe = $this->db->query($mat_query);
            if ($mat_query_exe->num_rows() > 0) {
                $response_data['all_material']  = $mat_query_exe->result_array();
            }
        }
        echo json_encode($response_data);
    }

    function get_inventory_available_qty($post = array())
    {
        $response_data['available_qty'] = 0;
        $dispatch_qty = 0;
        if (isset($post) && is_array($post) && count($post) > 0) {
            $post_data = $post;
        } else {
            $post_data = $this->input->post();
        }

        $material_id = $post_data['material_id'] > 0 ? $post_data['material_id'] : 0;
        $inventory_id = $post_data['inventory_id'] > 0 ? $post_data['inventory_id'] : 0;
        $docket_id = $post_data['docket_id'] > 0 ? $post_data['docket_id'] : 0;

        $query = "SELECT sum(im.total_quantity) as total_quantity, i.id FROM inventory_multiple_field im
        JOIN inventory i ON(i.id=im.inventory_id)
        WHERE i.status IN(1,2) AND im.status IN(1,2) AND i.id='" . $inventory_id . "'
        AND i.material_id='" . $material_id . "'";
        $query_exe = $this->db->query($query);
        $result_data = $query_exe->row_array();

        $total_quantity = isset($result_data['total_quantity']) ? $result_data['total_quantity'] : 0;

        $query = "SELECT SUM(i.dispatch_qty) as dispatch_qty FROM docket_inventory_map i
        JOIN docket d ON(d.id=i.docket_id) 
        WHERE d.status IN(1,2) AND i.status IN(1,2)  AND i.inventory_id='" . $inventory_id  . "'
        AND i.material_id='" . $material_id . "'";
        if ($docket_id > 0) {
            $query .= " AND docket_id!='" . $docket_id . "'";
        }
        $query_exe = $this->db->query($query);
        $qty_data = $query_exe->row_array();

        if (isset($qty_data['dispatch_qty']) && $qty_data['dispatch_qty'] != '') {
            $dispatch_qty = $qty_data['dispatch_qty'];
        } else {
            $dispatch_qty = 0;
        }

        $response_data['available_qty'] = $total_quantity -  $dispatch_qty;
        $response_data['dispatch_qty'] =  $dispatch_qty;
        if (isset($post) && is_array($post) && count($post) > 0) {
            return $response_data;
        } else {
            echo json_encode($response_data);
        }
    }
    function get_sub_rack()
    {
        $response_data = array();
        $post_data = $this->input->post();
        $rack_number_id = $post_data['rack_number_id'] > 0 ? $post_data['rack_number_id'] : 0;
        $query = "SELECT id,name,code FROM sub_rack_number WHERE status IN(1,2) AND rack_number_id='" . $rack_number_id . "'";
        $query_exe = $this->db->query($query);
        if ($query_exe->num_rows() > 0) {
            $response_data  = $query_exe->result_array();
        }
        echo json_encode($response_data);
    }

    function get_all_hub_user()
    {
        $response_data = array();
        $post_data = $this->input->get();
        $hub_id = $post_data['hub_id'] > 0 ? $post_data['hub_id'] : 0;
        $hub_query = "SELECT module_id FROM hub_mapping WHERE module_type = 2 AND hub_id ='" . $hub_id . "'";
        $hub_query_exe = $this->db->query($hub_query);
        $result = $hub_query_exe->result_array();
        foreach ($result as $key => $value) {
            $hub_id_array[$value['module_id']] = $value['module_id'];
        }

        $query = "SELECT id,name FROM admin_user WHERE status IN(1,2) AND id IN(" . implode(",", $hub_id_array) . ")";
        $main_db = $this->load->database('main_db', true);
        $query_exe = $main_db->query($query);
        $result1 = $query_exe->result_array();

        if (count($result1) > 0) {
            $response_data  = $query_exe->result_array();
        }
        echo json_encode($response_data);
    }
    function get_sub_ticket_type()
    {
        $response_data = array();
        $post_data = $this->input->post();
        $ticket_type_id = $post_data['ticket_type_id'] > 0 ? $post_data['ticket_type_id'] : 0;
        $query = "SELECT id,name,code FROM ticket_sub_type WHERE status IN(1,2) AND ticket_type_id='" . $ticket_type_id . "'";
        $query_exe = $this->db->query($query);
        if ($query_exe->num_rows() > 0) {
            $response_data  = $query_exe->result_array();
        }
        echo json_encode($response_data);
    }

    function get_country_dial_code($post = array())
    {
        $response_data = array();
        $post_data = $this->input->post();
        if (isset($post) && is_array($post) && count($post) > 0) {
            $post_data = $post;
        } else {
            $post_data = $this->input->post();
        }

        $country_id = $post_data['country_id'] > 0 ? $post_data['country_id'] : 0;
        $query = "SELECT id,name,code,dial_code FROM country WHERE status IN(1,2) AND id='" . $country_id . "'";
        $query_exe = $this->db->query($query);
        if ($query_exe->num_rows() > 0) {
            $response_data  = $query_exe->row_array();
        }
        $vendor_id = isset($post_data['vendor_id']) ? $post_data['vendor_id'] : 0;
        if ($vendor_id > 0 && $vendor_id != '') {
            //GET SERVICE ZONE SETTING
            $query = "SELECT id,fetch_zone_from_shipper FROM vendor WHERE status IN(1,2) AND id='" . $vendor_id . "'";
            $query_exe = $this->db->query($query);
            $vendor_setting  = $query_exe->row_array();

            if (isset($vendor_setting['fetch_zone_from_shipper']) && $vendor_setting['fetch_zone_from_shipper'] == 1) {
                $response_data['fetch_zone_from_shipper'] = 1;
            }
        }

        if (isset($post) && is_array($post) && count($post) > 0) {
            return $response_data;
        } else {
            echo json_encode($response_data);
        }
    }

    function check_bank_serial_no()
    {
        $post = $this->input->post();
        if (isset($post['serial_no']) && is_array($post['serial_no']) && count($post['serial_no']) > 0) {
            foreach ($post['serial_no'] as $key => $value) {
                if ($value > 0) {
                    $qry = "SELECT s.id FROM company_bank s 
                    JOIN company_master c ON(c.id=s.company_master_id)
                    WHERE s.status IN(1,2) AND s.serial_no='" . $value . "' AND c.status IN(1,2)";
                    if (isset($post['id']) && $post['id'] > 0) {
                        $qry .= " AND s.company_master_id!='" . $post['id'] . "'";
                    }

                    $query_exe = $this->db->query($qry);
                    $serial_data  = $query_exe->row_array();
                    if (isset($serial_data) && is_array($serial_data) && count($serial_data) > 0) {
                        $error_data[] = "SERIAL NO ." . $value . " ALREADY PRESENT FOR OTHER BANK";
                    }
                }
            }
        }

        if (isset($error_data) && is_array($error_data) && count($error_data) > 0) {
            $return_data['error'] = implode("<br>", $error_data);
        } else {
            $return_data['success'] = 1;
        }
        echo json_encode($return_data);
    }


    public function get_purchase_charge($post = array())
    {
        if (isset($post) && is_array($post) && count($post) > 0) {
            $post_data = $post;
        } else {
            $post_data = $this->input->post();
        }

        $result = array();
        if (isset($post_data) && is_array($post_data) && count($post_data) > 0) {
            $customer_id = isset($post_data['customer_id']) && $post_data['customer_id'] > 0 ? $post_data['customer_id'] : 'NULL';
            $product_id = isset($post_data['product_id']) && $post_data['product_id'] > 0 ? $post_data['product_id'] : 'NULL';
            $vendor_id = isset($post_data['vendor_id']) && $post_data['vendor_id'] > 0 ? $post_data['vendor_id'] : 'NULL';
            $co_vendor_id = isset($post_data['co_vendor_id']) && $post_data['co_vendor_id'] > 0 ? $post_data['co_vendor_id'] : 'NULL';
            $destination_id = isset($post_data['destination_id']) && $post_data['destination_id'] > 0 ? $post_data['destination_id'] : 'NULL';
            $dest_zone_id = isset($post_data['dest_zone_id']) && $post_data['dest_zone_id'] > 0 ? $post_data['dest_zone_id'] : 'NULL';
            $origin_id = isset($post_data['origin_id']) && $post_data['origin_id'] > 0 ? $post_data['origin_id'] : 'NULL';
            $ori_zone_id = isset($post_data['ori_zone_id']) && $post_data['ori_zone_id'] > 0 ? $post_data['ori_zone_id'] : 'NULL';

            $dest_hub_id = isset($post_data['dest_hub_id']) && $post_data['dest_hub_id'] > 0 ? $post_data['dest_hub_id'] : 'NULL';
            $ori_hub_id = isset($post_data['ori_hub_id']) && $post_data['ori_hub_id'] > 0 ? $post_data['ori_hub_id'] : 'NULL';

            $charge_id = isset($post_data['charge_id']) && $post_data['charge_id'] > 0 ? $post_data['charge_id'] : '';
            $booking_date = isset($post_data['booking_date']) && $post_data['booking_date'] > 0 ? $post_data['booking_date'] : '';

            $chargeable_wt = isset($post_data['chargeable_wt']) && $post_data['chargeable_wt'] > 0 ? $post_data['chargeable_wt'] : 0;
            $actual_wt = isset($post_data['actual_wt']) && $post_data['actual_wt'] > 0 ? $post_data['actual_wt'] : 0;
            $volumetric_wt = isset($post_data['volumetric_wt']) && $post_data['volumetric_wt'] > 0 ? $post_data['volumetric_wt'] : 0;
            $total_pcs = isset($post_data['total_pcs']) && $post_data['total_pcs'] > 0 ? $post_data['total_pcs'] : 0;
            $shipment_value = isset($post_data['shipment_value']) && $post_data['shipment_value'] > 0 ? $post_data['shipment_value'] : 0;
            $charge_type = isset($post_data['charge_type']) && $post_data['charge_type'] > 0 ? $post_data['charge_type'] : 0;

            $con_pincode = isset($post_data['con_pincode']) ? $post_data['con_pincode'] : '';

            $ori_zone_service_type = isset($post_data['ori_zone_service_type']) && $post_data['ori_zone_service_type'] > 0 ? $post_data['ori_zone_service_type'] : 0;
            $dest_zone_service_type = isset($post_data['dest_zone_service_type']) && $post_data['dest_zone_service_type'] > 0 ? $post_data['dest_zone_service_type'] : 0;

            if ($charge_id > 0 && $booking_date != '') {

                //CHECK RATE MODIFIER HEAD PRESENT
                if ($customer_id > 0 && $booking_date != '' && $booking_date != '1970-01-01' && $booking_date != '0000-00-00') {
                    $query = "SELECT contract_customer_id,from_date,till_date FROM customer_contract_head
                    WHERE status IN(1,2) AND customer_id='" . $post_data['customer_id'] . "' AND head_type =2 
                    AND '" . $booking_date . "' BETWEEN from_date AND till_date ORDER BY from_date DESC";
                    $query_exe = $this->db->query($query);
                    $customer_rate_head = $query_exe->row_array();
                }

                if (isset($customer_rate_head) && is_array($customer_rate_head) && count($customer_rate_head) > 0) {
                    $rate_customer_id = $customer_rate_head['contract_customer_id'];
                } else {
                    $rate_customer_id = $customer_id;
                }

                $qry = "SELECT r.* FROM `rate_modifier` r 
            WHERE r.status IN(1,2) 
           AND (r.min_chargeable_wt<=0 OR r.max_chargeable_wt<=0 OR (r.min_chargeable_wt<=" . $chargeable_wt . " AND r.max_chargeable_wt>=" . $chargeable_wt . "))
           AND (r.min_actual_wt<=0 OR r.max_actual_wt<=0 OR (r.min_actual_wt<=" . $actual_wt . " AND r.max_actual_wt>=" . $actual_wt . "))
           AND (r.min_volume_wt<=0 OR r.max_volume_wt<=0 OR (r.min_volume_wt<=" . $volumetric_wt . " AND r.max_volume_wt>=" . $volumetric_wt . "))
           AND (r.min_boxes<=0 OR r.max_boxes<=0 OR (r.min_boxes<=" . $total_pcs . " AND r.max_boxes>=" . $total_pcs . "))
           AND (r.min_shipment_value<=0 OR r.max_shipment_value<=0 OR (r.min_shipment_value<=" . $shipment_value . " AND r.max_shipment_value>=" . $shipment_value . "))
           AND r.billing_type IN(1," . $charge_type . ")
           AND r.charge_id='" . $charge_id . "' AND '" . $booking_date
                    . "' BETWEEN r.effective_from AND r.effective_to ORDER BY r.id DESC";

                $query_exe = $this->db->query($qry);
                $rate_modi_result  = $query_exe->result_array();


                if (isset($rate_modi_result) && is_array($rate_modi_result) && count($rate_modi_result) > 0) {
                    foreach ($rate_modi_result as $ra_key => $ra_value) {
                        $rate_ids[$ra_value['id']] = $ra_value['id'];
                        $rate_ids_data[$ra_value['id']] = $ra_value;

                        if ($ra_value['consignee_pincode'] != '') {
                            $consignee_pincode_arr = explode(",", $ra_value['consignee_pincode']);
                            if (isset($consignee_pincode_arr) && is_array($consignee_pincode_arr) && count($consignee_pincode_arr) > 0) {
                                $con_pincode_arr[$ra_value['id']] = $consignee_pincode_arr;
                            }
                        }
                    }
                }

                if (isset($rate_ids) && is_array($rate_ids) && count($rate_ids) > 0) {
                    $qry = "SELECT * FROM rate_modifier_data WHERE status=1 AND rate_modifier_id IN(" . implode(",", $rate_ids) . ")
                    ORDER BY rate_modifier_id DESC";
                    $query_exe = $this->db->query($qry);
                    $rate_data_res  = $query_exe->result_array();
                }


                if (isset($rate_data_res) && is_array($rate_data_res) && count($rate_data_res) > 0) {
                    foreach ($rate_data_res as $key => $value) {
                        $rate_result[$key] = isset($rate_ids_data[$value['rate_modifier_id']]) ? $rate_ids_data[$value['rate_modifier_id']] : array();


                        if (isset($con_pincode_arr[$value['rate_modifier_id']]) && is_array($con_pincode_arr[$value['rate_modifier_id']]) && count($con_pincode_arr[$value['rate_modifier_id']]) > 0) {
                            $result[$value['rate_modifier_id']]['con_pincode_arr'] = $con_pincode_arr[$value['rate_modifier_id']];
                        }

                        if ($value['module_type'] == 1) {
                            $rate_result[$key]['customer_id'] = $value['module_id'];
                        } else if ($value['module_type'] == 2) {
                            $rate_result[$key]['product_id'] = $value['module_id'];
                        } else if ($value['module_type'] == 3) {
                            $rate_result[$key]['vendor_id'] = $value['module_id'];
                        } else if ($value['module_type'] == 4) {
                            $rate_result[$key]['co_vendor_id'] = $value['module_id'];
                        } else if ($value['module_type'] == 5) {
                            $rate_result[$key]['destination_id'] = $value['module_id'];
                        } else if ($value['module_type'] == 6) {
                            $rate_result[$key]['dest_zone_id'] = $value['module_id'];
                        } else if ($value['module_type'] == 7) {
                            $rate_result[$key]['origin_id'] = $value['module_id'];
                        } else if ($value['module_type'] == 8) {
                            $rate_result[$key]['ori_zone_id'] = $value['module_id'];
                        } else if ($value['module_type'] == 9) {
                            $rate_result[$key]['dest_hub_id'] = $value['module_id'];
                        } else if ($value['module_type'] == 10) {
                            $rate_result[$key]['ori_hub_id'] = $value['module_id'];
                        }
                    }
                }


                if (isset($rate_result) && is_array($rate_result) && count($rate_result) > 0) {
                    foreach ($rate_result as $ra_key => $ra_value) {
                        if (!isset($rate_group[$ra_value['id']])) {
                            $rate_group[$ra_value['id']] = $ra_value;
                        }


                        if ($ra_value['destination_id'] > 0) {
                            $rate_group[$ra_value['id']]['destination_id_arr'][] = $ra_value['destination_id'];
                        }
                        if ($ra_value['dest_zone_id'] > 0) {
                            $rate_group[$ra_value['id']]['dest_zone_id_arr'][] = $ra_value['dest_zone_id'];
                        }
                        if ($ra_value['dest_hub_id'] > 0) {
                            $rate_group[$ra_value['id']]['dest_hub_id_arr'][] = $ra_value['dest_hub_id'];
                        }

                        if ($ra_value['origin_id'] > 0) {
                            $rate_group[$ra_value['id']]['origin_id_arr'][] = $ra_value['origin_id'];
                        }
                        if ($ra_value['ori_zone_id'] > 0) {
                            $rate_group[$ra_value['id']]['ori_zone_id_arr'][] = $ra_value['ori_zone_id'];
                        }
                        if ($ra_value['ori_hub_id'] > 0) {
                            $rate_group[$ra_value['id']]['ori_hub_id_arr'][] = $ra_value['ori_hub_id'];
                        }

                        if (isset($ra_value['customer_id']) && $ra_value['customer_id'] > 0) {
                            $rate_group[$ra_value['id']]['customer_id_arr'][] = $ra_value['customer_id'];
                        }
                        if (isset($ra_value['product_id']) && $ra_value['product_id'] > 0) {
                            $rate_group[$ra_value['id']]['product_id_arr'][] = $ra_value['product_id'];
                        }
                        if (isset($ra_value['vendor_id']) && $ra_value['vendor_id'] > 0) {
                            $rate_group[$ra_value['id']]['vendor_id_arr'][] = $ra_value['vendor_id'];
                        }
                        if (isset($ra_value['co_vendor_id']) && $ra_value['co_vendor_id'] > 0) {
                            $rate_group[$ra_value['id']]['co_vendor_id_arr'][] = $ra_value['co_vendor_id'];
                        }
                    }
                }


                if (isset($rate_group) && is_array($rate_group) && count($rate_group) > 0) {
                    foreach ($rate_group as $gkey => $gvalue) {
                        $rate_match = 0;

                        if (isset($gvalue['con_pincode_arr']) && is_array($gvalue['con_pincode_arr']) && count($gvalue['con_pincode_arr']) > 0) {
                            $pincode_match = false;
                            foreach ($gvalue['con_pincode_arr'] as $pkey => $pvalue) {
                                if ($pvalue == $con_pincode || strpos($con_pincode, $pvalue) === 0) {
                                    $pincode_match = true;
                                    break;
                                }
                            }

                            if (!$pincode_match) {
                                unset($result[$gkey]);
                                continue;
                            }
                        }

                        // if (isset($gvalue['con_pincode_arr']) && !in_array($con_pincode, $gvalue['con_pincode_arr'])) {
                        //     unset($result[$gkey]);
                        //     continue;
                        // }

                        if (isset($gvalue['customer_id_arr']) && !in_array($rate_customer_id, $gvalue['customer_id_arr'])) {
                            continue;
                        }
                        if (isset($gvalue['product_id_arr']) && !in_array($product_id, $gvalue['product_id_arr'])) {
                            continue;
                        }
                        if (isset($gvalue['vendor_id_arr']) && !in_array($vendor_id, $gvalue['vendor_id_arr'])) {
                            continue;
                        }
                        if (isset($gvalue['co_vendor_id_arr']) && !in_array($co_vendor_id, $gvalue['co_vendor_id_arr'])) {
                            continue;
                        }
                        if (isset($gvalue['destination_id_arr']) && in_array($destination_id, $gvalue['destination_id_arr'])) {
                            $rate_match += 1;
                        } else if (isset($gvalue['dest_zone_id_arr']) && in_array($dest_zone_id, $gvalue['dest_zone_id_arr'])) {
                            $rate_match += 1;
                        } else if (isset($gvalue['dest_hub_id_arr']) && in_array($dest_hub_id, $gvalue['dest_hub_id_arr'])) {
                            $rate_match += 1;
                        } else if (!isset($gvalue['destination_id_arr']) && !isset($gvalue['dest_zone_id_arr']) && !isset($gvalue['dest_hub_id_arr'])) {
                            $rate_match += 1;
                        }

                        if (isset($gvalue['origin_id_arr']) && in_array($origin_id, $gvalue['origin_id_arr'])) {
                            $rate_match += 1;
                        } else if (isset($gvalue['ori_zone_id_arr']) && in_array($ori_zone_id, $gvalue['ori_zone_id_arr'])) {
                            $rate_match += 1;
                        } else if (isset($gvalue['ori_hub_id_arr']) && in_array($ori_hub_id, $gvalue['ori_hub_id_arr'])) {
                            $rate_match += 1;
                        } else if (!isset($gvalue['origin_id_arr']) && !isset($gvalue['ori_zone_id_arr']) && !isset($gvalue['ori_hub_id_arr'])) {
                            $rate_match += 1;
                        }

                        if ($rate_match == 2) {
                            $result[] = $gvalue;
                            $mapping_effective[$gvalue['effective_from']][$gkey] = $rate_match;
                            $mapping_match[$gkey] = $gvalue;
                            $mapping_match[$gkey]['point'] = $rate_match;
                        }
                    }


                    // if (isset($mapping_match) && is_array($mapping_match) && count($mapping_match) > 0) {
                    //     $max_mapping_date =  max(array_column($mapping_match, 'effective_from'));


                    //     if (isset($mapping_effective[$max_mapping_date])) {
                    //         arsort($mapping_effective[$max_mapping_date], SORT_NUMERIC);
                    //         $latest_mapping_id = array_keys($mapping_effective[$max_mapping_date])[0];

                    //         if (isset($mapping_match[$latest_mapping_id])) {
                    //             if (isset($mapping_match[$latest_mapping_id][0])) {
                    //                 $result = $mapping_match[$latest_mapping_id];
                    //             } else {
                    //                 $result[] = $mapping_match[$latest_mapping_id];
                    //             }
                    //         }
                    //     }
                    // }
                }

                $condition_arr = array(
                    'customer_id', 'product_id', 'vendor_id', 'co_vendor_id',
                    'destination_id', 'dest_zone_id', 'origin_id', 'ori_zone_id'
                );
                //CHECK MAXIMUM CONDITION MATCH
                $highest_match = 0;
                $highest_date = '';


                if (isset($result) && is_array($result) && count($result) > 0) {
                    foreach ($result as $key => $value) {
                        if ($chargeable_wt == '') {
                            $chargeable_wt = 0;
                        }
                        if ($actual_wt == '') {
                            $actual_wt = 0;
                        }
                        if ($volumetric_wt == '') {
                            $volumetric_wt = 0;
                        }
                        if ($total_pcs == '') {
                            $total_pcs = 0;
                        }
                        if ($shipment_value == '') {
                            $shipment_value = 0;
                        }

                        if ((int)$value['min_chargeable_wt'] > 0 && $chargeable_wt < $value['min_chargeable_wt']) {
                            continue;
                        }
                        if ((int)$value['min_actual_wt'] > 0 && $actual_wt < $value['min_actual_wt']) {
                            continue;
                        }
                        if ((int)$value['min_volume_wt'] > 0 && $volumetric_wt < $value['min_volume_wt']) {
                            continue;
                        }
                        if ((int)$value['min_boxes'] > 0 && $total_pcs < $value['min_boxes']) {
                            continue;
                        }
                        if ((int)$value['min_shipment_value'] > 0 && $shipment_value < $value['min_shipment_value']) {
                            continue;
                        }

                        if ((int)$value['max_chargeable_wt'] > 0 && $chargeable_wt > $value['max_chargeable_wt']) {
                            continue;
                        }
                        if ((int)$value['max_actual_wt'] > 0 && $actual_wt > $value['max_actual_wt']) {
                            continue;
                        }
                        if ((int)$value['max_volume_wt'] > 0 && $volumetric_wt > $value['max_volume_wt']) {
                            continue;
                        }
                        if ((int)$value['max_boxes'] > 0 && $total_pcs > $value['max_boxes']) {
                            continue;
                        }
                        if ((int)$value['max_shipment_value'] > 0 && $shipment_value > $value['max_shipment_value']) {
                            continue;
                        }

                        //MIN DIMENSION


                        if ((int)$value['min_dimension'] > 0) {
                            $min_dimension_match = array();
                            if (isset($post_data['dim_len']) && is_array($post_data['dim_len']) && count($post_data['dim_len']) > 0) {
                                foreach ($post_data['dim_len'] as $lkey => $lvalue) {
                                    if ($lvalue >= $value['min_dimension']) {
                                        $min_dimension_match[] = 1;
                                    }
                                }
                            }
                            if (isset($post_data['dim_wid']) && is_array($post_data['dim_wid']) && count($post_data['dim_wid']) > 0) {
                                foreach ($post_data['dim_wid'] as $bkey => $bvalue) {
                                    if ($bvalue >= $value['min_dimension']) {
                                        $min_dimension_match[] = 1;
                                    }
                                }
                            }
                            if (isset($post_data['dim_hei']) && is_array($post_data['dim_hei']) && count($post_data['dim_hei']) > 0) {
                                foreach ($post_data['dim_hei'] as $hkey => $hvalue) {
                                    if ($hvalue >= $value['min_dimension']) {
                                        $min_dimension_match[] = 1;
                                    }
                                }
                            }


                            if (count($min_dimension_match) == 0) {
                                continue;
                            }
                        }

                        //MAX DIMENSION
                        if ((int)$value['max_dimension'] > 0) {
                            $max_dimension_match = array();
                            if (isset($post_data['dim_len']) && is_array($post_data['dim_len']) && count($post_data['dim_len']) > 0) {
                                foreach ($post_data['dim_len'] as $lkey => $lvalue) {
                                    if ($lvalue <= $value['max_dimension']) {
                                        $max_dimension_match[] = 1;
                                    }
                                }
                            }
                            if (isset($post_data['dim_wid']) && is_array($post_data['dim_wid']) && count($post_data['dim_wid']) > 0) {
                                foreach ($post_data['dim_wid'] as $bkey => $bvalue) {
                                    if ($bvalue <= $value['max_dimension']) {
                                        $max_dimension_match[] = 1;
                                    }
                                }
                            }
                            if (isset($post_data['dim_hei']) && is_array($post_data['dim_hei']) && count($post_data['dim_hei']) > 0) {
                                foreach ($post_data['dim_hei'] as $hkey => $hvalue) {
                                    if ($hvalue <= $value['max_dimension']) {
                                        $max_dimension_match[] = 1;
                                    }
                                }
                            }

                            if (count($max_dimension_match) == 0) {
                                continue;
                            }
                        }


                        $min_wt_breach = array();
                        $max_wt_breach = array();
                        $min_vol_wt_breach = array();
                        $max_vol_wt_breach = array();
                        $min_cha_wt_breach = array();
                        $max_cha_wt_breach = array();

                        //MIN PER BOX ACTUAL WEIGHT
                        if ((int)$value['min_per_box_wt'] > 0) {
                            if (isset($post_data['act_wt']) && is_array($post_data['act_wt']) && count($post_data['act_wt']) > 0) {
                                foreach ($post_data['act_wt'] as $lkey => $lvalue) {
                                    if ($lvalue < $value['min_per_box_wt']) {
                                        $min_wt_breach[] = 1;
                                    }
                                }
                            }

                            if (
                                isset($min_wt_breach) && is_array($min_wt_breach) && count($min_wt_breach) > 0
                                && count($min_wt_breach) == count($post_data['act_wt'])
                            ) {
                                continue;
                            }
                        }

                        //MAX PER BOX ACTUAL WEIGHT
                        if ((int)$value['max_per_box_wt'] > 0) {
                            if (isset($post_data['act_wt']) && is_array($post_data['act_wt']) && count($post_data['act_wt']) > 0) {
                                foreach ($post_data['act_wt'] as $lkey => $lvalue) {
                                    if ($lvalue > $value['max_per_box_wt']) {
                                        $max_wt_breach[] = 1;
                                    }
                                }
                            }

                            if (
                                isset($max_wt_breach) && is_array($max_wt_breach) && count($max_wt_breach) > 0
                                && count($max_wt_breach) == count($post_data['act_wt'])
                            ) {
                                continue;
                            }
                        }

                        //MIN PER BOX VOLUMETRIC WEIGHT
                        if ((int)$value['min_per_box_vol_wt'] > 0) {
                            if (isset($post_data['vol_wt']) && is_array($post_data['vol_wt']) && count($post_data['vol_wt']) > 0) {
                                foreach ($post_data['vol_wt'] as $lkey => $lvalue) {
                                    if ($lvalue < $value['min_per_box_vol_wt']) {
                                        $min_vol_wt_breach[] = 1;
                                    }
                                }
                            }

                            if (
                                isset($min_vol_wt_breach) && is_array($min_vol_wt_breach) && count($min_vol_wt_breach) > 0
                                && count($min_vol_wt_breach) == count($post_data['vol_wt'])
                            ) {
                                continue;
                            }
                        }

                        //MAX PER BOX VOLUMETRIC WEIGHT
                        if ((int)$value['max_per_box_vol_wt'] > 0) {
                            if (isset($post_data['vol_wt']) && is_array($post_data['vol_wt']) && count($post_data['vol_wt']) > 0) {
                                foreach ($post_data['vol_wt'] as $lkey => $lvalue) {
                                    if ($lvalue > $value['max_per_box_vol_wt']) {
                                        $max_vol_wt_breach[] = 1;
                                    }
                                }
                            }
                            if (
                                isset($max_vol_wt_breach) && is_array($max_vol_wt_breach) && count($max_vol_wt_breach) > 0
                                && count($max_vol_wt_breach) == count($post_data['vol_wt'])
                            ) {
                                continue;
                            }
                        }


                        //MIN PER BOX CHARGEABLE WEIGHT
                        if ((int)$value['min_per_box_cha_wt'] > 0) {
                            if (isset($post_data['char_wt']) && is_array($post_data['char_wt']) && count($post_data['char_wt']) > 0) {
                                foreach ($post_data['char_wt'] as $lkey => $lvalue) {
                                    if ($lvalue < $value['min_per_box_cha_wt']) {
                                        $min_cha_wt_breach[] = 1;
                                    }
                                }
                            }

                            if (
                                isset($min_cha_wt_breach) && is_array($min_cha_wt_breach) && count($min_cha_wt_breach) > 0
                                && count($min_cha_wt_breach) == count($post_data['char_wt'])
                            ) {
                                continue;
                            }
                        }

                        //MAX PER BOX CHARGEABLE WEIGHT
                        if ((int)$value['max_per_box_cha_wt'] > 0) {
                            if (isset($post_data['char_wt']) && is_array($post_data['char_wt']) && count($post_data['char_wt']) > 0) {
                                foreach ($post_data['char_wt'] as $lkey => $lvalue) {
                                    if ($lvalue > $value['max_per_box_cha_wt']) {
                                        $max_cha_wt_breach[] = 1;
                                    }
                                }
                            }
                            if (
                                isset($max_cha_wt_breach) && is_array($max_cha_wt_breach) && count($max_cha_wt_breach) > 0
                                && count($max_cha_wt_breach) == count($post_data['char_wt'])
                            ) {
                                continue;
                            }
                        }

                        // $condition_match_cnt = 0;
                        // foreach ($condition_arr as $ckey => $cvalue) {
                        //     if ($value[$cvalue] > 0) {
                        //         $condition_match_cnt = $condition_match_cnt + 1;
                        //     }
                        // }

                        $condition_match_cnt = 0;
                        if (isset($value['customer_id_arr']) && in_array($customer_id, $value['customer_id_arr'])) {
                            $condition_match_cnt += 8;
                        }
                        if (isset($value['vendor_id_arr']) && in_array($vendor_id, $value['vendor_id_arr'])) {
                            $condition_match_cnt += 4;
                        }
                        if (isset($value['co_vendor_id_arr']) && in_array($co_vendor_id, $value['co_vendor_id_arr'])) {
                            $condition_match_cnt += 2;
                        }
                        if (isset($value['product_id_arr']) && in_array($product_id, $value['product_id_arr'])) {
                            $condition_match_cnt += 1;
                        }

                        if (isset($value['destination_id_arr']) && in_array($destination_id, $value['destination_id_arr'])) {
                            $condition_match_cnt += 1;
                        }
                        if (isset($value['dest_zone_id_arr']) && in_array($dest_zone_id, $value['dest_zone_id_arr'])) {
                            $condition_match_cnt += 1;
                        }
                        if (isset($value['dest_hub_id_arr']) && in_array($dest_hub_id, $value['dest_hub_id_arr'])) {
                            $condition_match_cnt += 1;
                        }

                        if (isset($value['origin_id_arr']) && in_array($origin_id, $value['origin_id_arr'])) {
                            $condition_match_cnt += 1;
                        }
                        if (isset($value['ori_zone_id_arr']) && in_array($ori_zone_id, $value['ori_zone_id_arr'])) {
                            $condition_match_cnt += 1;
                        }
                        if (isset($value['ori_hub_id_arr']) && in_array($ori_hub_id, $value['ori_hub_id_arr'])) {
                            $condition_match_cnt += 1;
                        }


                        if ($ori_zone_service_type == 2 && $value['is_origin_oda'] == 1) {
                            $condition_match_cnt += 1;
                        }
                        if ($dest_zone_service_type == 2 && $value['is_dest_oda'] == 1) {
                            $condition_match_cnt += 1;
                        }

                        if (isset($gvalue['con_pincode_arr']) && is_array($gvalue['con_pincode_arr']) && count($gvalue['con_pincode_arr']) > 0) {
                            $pincode_match = false;
                            foreach ($gvalue['con_pincode_arr'] as $pkey => $pvalue) {
                                if ($pvalue == $con_pincode || strpos($con_pincode, $pvalue) === 0) {
                                    $pincode_match = true;
                                    break;
                                }
                            }

                            if ($pincode_match) {
                                $condition_match_cnt += 1;
                            }
                        }

                        if ($condition_match_cnt > $highest_match) {
                            $highest_match = $condition_match_cnt;
                            $highest_date = $value['effective_from'];
                            $rate_id_data = $value;
                        } else if ($condition_match_cnt == $highest_match && $value['effective_from'] >  $highest_date) {
                            $highest_match = $condition_match_cnt;
                            $highest_date = $value['effective_from'];
                            $rate_id_data = $value;
                        }

                        if ($key == 0) {
                            $highest_match = $condition_match_cnt;
                            $highest_date = $value['effective_from'];
                            $rate_id_data = $value;
                        }
                        // $match_rate[$value['id']] =  $condition_match_cnt;
                        // $match_rate_date[$value['id']] =  $value['effective_from'];
                    }
                } else {
                    $rate_id_data = isset($result[0]) ? $result[0] : array();
                }
            }
        }
        $rate_amt = 0;
        $rate_apply_amt = 0;

        if (isset($rate_id_data) && is_array($rate_id_data) && count($rate_id_data) > 0) {
            $freight_amount = isset($post_data['freight_amount']) && $post_data['freight_amount'] > 0 ? $post_data['freight_amount'] : 0;
            $fsc_amount = isset($post_data['fsc_amount']) && $post_data['fsc_amount'] > 0 ? $post_data['fsc_amount'] : 0;
            if ($rate_id_data['freight_per'] > 0) {
                $rate_amt = ($freight_amount * $rate_id_data['freight_per']) / 100;
            } else if ($rate_id_data['shipment_per'] > 0) {
                $rate_amt = ($shipment_value * $rate_id_data['shipment_per']) / 100;
            } else if ($rate_id_data['freight_fsc_per'] > 0) {
                //THIS SETTING APPLY ONLY WHEN FSC APPLY DISABLE IN CHARGE
                $qry = "SELECT id FROM charge_master WHERE status IN(1,2) AND id='" . $rate_id_data['charge_id'] . "' AND is_fsc_apply=2";
                $qry_exe = $this->db->query($qry);
                $charge_res = $qry_exe->result_array();

                if (isset($charge_res) && is_array($charge_res) && count($charge_res) > 0) {
                    $rate_amt = (($freight_amount + $fsc_amount) * $rate_id_data['freight_fsc_per']) / 100;
                }
            } else if ($rate_id_data['fixed_amt'] > 0) {

                $rate_type = $rate_id_data['rate_per_type'];
                $rate_apply_amt = $rate_id_data['fixed_amt'];
                if ($rate_type == 1) {
                    $rate_amt = $total_pcs * $rate_apply_amt;
                } elseif ($rate_type == 2) {
                    $chargeable_wt = ceil($chargeable_wt);
                    $rate_amt = $chargeable_wt * $rate_apply_amt;
                } elseif ($rate_type == 3) {
                    $round_no = $this->round_half_number($chargeable_wt);
                    $rate_amt = $round_no * 2 * $rate_apply_amt;
                } else {
                    $rate_amt = $rate_apply_amt;
                }


                if ($rate_type == 4) {
                    if ((int)$rate_id_data['min_per_box_wt'] > 0) {
                        if (isset($post_data['char_wt']) && is_array($post_data['char_wt']) && count($post_data['char_wt']) > 0) {
                            foreach ($post_data['char_wt'] as $lkey => $lvalue) {
                                if ($lvalue > $value['min_per_box_wt']) {
                                    $rate_pieces[$lkey] = $lvalue;
                                }
                            }
                        }
                    }
                } else if ($rate_id_data['grith_check'] == 1) {
                    if (isset($post_data['dim_len']) && is_array($post_data['dim_len']) && count($post_data['dim_len']) > 0) {
                        foreach ($post_data['dim_len'] as $lkey => $lvalue) {
                            $dim_arr[0] = $lvalue > 0 ? $lvalue : 0;
                            $dim_arr[1] = isset($post_data['dim_wid'][$lkey]) && $post_data['dim_wid'][$lkey] > 0 ? $post_data['dim_wid'][$lkey] : 0;
                            $dim_arr[2] = isset($post_data['dim_hei'][$lkey]) && $post_data['dim_hei'][$lkey] > 0 ? $post_data['dim_hei'][$lkey] : 0;


                            asort($dim_arr);
                            $total_dim = 0;
                            foreach ($dim_arr as $dkey => $dvalue) {
                                if ($dkey == 0) {
                                    $new_dim = $dvalue;
                                } elseif ($dkey == 1) {
                                    $new_dim = $dvalue * 2;
                                } elseif ($dkey == 2) {
                                    $new_dim = $dvalue * 2;
                                }
                                $total_dim = $total_dim + $new_dim;
                            }
                            $min_dimension = $rate_id_data['min_dimension'];
                            if ($min_dimension > 0) {
                                if ($total_dim < $min_dimension) {
                                    $rate_pieces[$lkey] = $lvalue;
                                }
                            }

                            $max_dimension = $rate_id_data['max_dimension'];
                            if ($max_dimension > 0) {
                                if ($total_dim > $max_dimension) {
                                    $rate_pieces[$lkey] = $lvalue;
                                }
                            }
                        }
                    }
                } else {
                    //CHECK MIN MAX DIMENSION PER PIECES
                    if ($rate_id_data['min_dim_per_pc'] == 1) {
                        $min_dimension = $rate_id_data['min_dimension'];
                        if ($min_dimension > 0) {
                            if (isset($post_data['dim_len']) && is_array($post_data['dim_len']) && count($post_data['dim_len']) > 0) {
                                foreach ($post_data['dim_len'] as $lkey => $lvalue) {
                                    if ($lvalue < $min_dimension) {
                                        $rate_pieces[$lkey] = $lvalue;
                                    }
                                }
                            }

                            if (isset($post_data['dim_wid']) && is_array($post_data['dim_wid']) && count($post_data['dim_wid']) > 0) {
                                foreach ($post_data['dim_wid'] as $bkey => $bvalue) {
                                    if ($bvalue < $min_dimension) {
                                        $rate_pieces[$bkey] = $bvalue;
                                    }
                                }
                            }

                            if (isset($post_data['dim_hei']) && is_array($post_data['dim_hei']) && count($post_data['dim_hei']) > 0) {
                                foreach ($post_data['dim_hei'] as $hkey => $hvalue) {
                                    if ($hvalue < $min_dimension) {
                                        $rate_pieces[$hkey] = $hvalue;
                                    }
                                }
                            }
                        }
                    }


                    if ($rate_id_data['max_dim_per_pc'] == 1) {
                        $max_dimension = $rate_id_data['max_dimension'];
                        if ($max_dimension > 0) {
                            if (isset($post_data['dim_len']) && is_array($post_data['dim_len']) && count($post_data['dim_len']) > 0) {
                                foreach ($post_data['dim_len'] as $lkey => $lvalue) {
                                    if ($lvalue > $max_dimension) {
                                        $rate_pieces[$lkey] = $lvalue;
                                    }
                                }
                            }

                            if (isset($post_data['dim_wid']) && is_array($post_data['dim_wid']) && count($post_data['dim_wid']) > 0) {
                                foreach ($post_data['dim_wid'] as $bkey => $bvalue) {
                                    if ($bvalue > $max_dimension) {
                                        $rate_pieces[$bkey] = $bvalue;
                                    }
                                }
                            }

                            if (isset($post_data['dim_hei']) && is_array($post_data['dim_hei']) && count($post_data['dim_hei']) > 0) {
                                foreach ($post_data['dim_hei'] as $hkey => $hvalue) {
                                    if ($hvalue > $max_dimension) {
                                        $rate_pieces[$hkey] = $hvalue;
                                    }
                                }
                            }
                        }
                    }
                }



                if (isset($rate_pieces) && is_array($rate_pieces) && count($rate_pieces) > 0) {
                    $extra_rate = count($rate_pieces) * $rate_apply_amt;
                    // $rate_amt = $rate_amt  +  $extra_rate;
                    $rate_amt = $extra_rate;
                } else if ($rate_type == 4) {
                    $rate_amt = 0;
                }

                if ($rate_amt == 0 && $rate_type != 4) {
                    $rate_amt = $rate_id_data['fixed_amt'];
                }
            }
        }

        if (isset($rate_id_data['min_amt']) && $rate_id_data['min_amt'] > 0 && $rate_type != 4) {
            if ($rate_amt < $rate_id_data['min_amt']) {
                $rate_amt = $rate_id_data['min_amt'];
            }
        }


        if (isset($rate_id_data['slab_wise_rate']) && $rate_id_data['slab_wise_rate'] == 1 && $rate_id_data['fixed_amt'] > 0 && $rate_id_data['min_amt'] > 0) {
            $slab_add = 0;
            $rate_type = $rate_id_data['rate_per_type'];
            $fixed_amt = $rate_id_data['fixed_amt'];
            $min_amt = $rate_id_data['min_amt'];
            $rate_amt = $min_amt;
            if ($rate_type == 1) {
                $slab_wt = $total_pcs - $rate_id_data['min_boxes'];
                $rate_amt += $slab_wt * $fixed_amt;
            } elseif ($rate_type == 2) {
                $chargeable_wt = ceil($chargeable_wt);
                $slab_wt = $chargeable_wt - $rate_id_data['min_chargeable_wt'];
                $rate_amt += $slab_wt * $fixed_amt;
            } elseif ($rate_type == 3) {
                $chargeable_wt = round($chargeable_wt, 2);
                $slab_wt = $chargeable_wt - $rate_id_data['min_chargeable_wt'];
                $rate_amt += $slab_wt * $fixed_amt * 2;
            }
        }


        $final_data['id'] = isset($rate_id_data['id']) ? $rate_id_data['id'] : 0;
        $final_data['freight_fsc_per'] = isset($rate_id_data['freight_fsc_per']) ? $rate_id_data['freight_fsc_per'] : 0;
        $final_data['rate_amt'] = isset($rate_amt) ? $rate_amt : 0;
        if (isset($post) && is_array($post) && count($post) > 0) {
            return $final_data;
        } else {
            echo json_encode($final_data);
        }
    }


    public function get_sales_charge($post = array())
    {
        $this->load->helper('frontend_common');
        if (isset($post) && is_array($post) && count($post) > 0) {
            $post_data = $post;
        } else {
            $post_data = $this->input->post();
        }

        $setting = get_all_app_setting(" AND module_name='docket'");

        $result = array();
        if (isset($post_data) && is_array($post_data) && count($post_data) > 0) {
            $customer_id = isset($post_data['customer_id']) && $post_data['customer_id'] > 0 ? $post_data['customer_id'] : 'NULL';
            $product_id = isset($post_data['product_id']) && $post_data['product_id'] > 0 ? $post_data['product_id'] : 'NULL';
            $vendor_id = isset($post_data['vendor_id']) && $post_data['vendor_id'] > 0 ? $post_data['vendor_id'] : 'NULL';
            $co_vendor_id = isset($post_data['co_vendor_id']) && $post_data['co_vendor_id'] > 0 ? $post_data['co_vendor_id'] : 'NULL';
            $destination_id = isset($post_data['destination_id']) && $post_data['destination_id'] > 0 ? $post_data['destination_id'] : 'NULL';
            $dest_zone_id = isset($post_data['dest_zone_id']) && $post_data['dest_zone_id'] > 0 ? $post_data['dest_zone_id'] : 'NULL';
            $origin_id = isset($post_data['origin_id']) && $post_data['origin_id'] > 0 ? $post_data['origin_id'] : 'NULL';
            $ori_zone_id = isset($post_data['ori_zone_id']) && $post_data['ori_zone_id'] > 0 ? $post_data['ori_zone_id'] : 'NULL';

            $ori_hub_id = isset($post_data['ori_hub_id']) && $post_data['ori_hub_id'] > 0 ? $post_data['ori_hub_id'] : 'NULL';
            $dest_hub_id = isset($post_data['dest_hub_id']) && $post_data['dest_hub_id'] > 0 ? $post_data['dest_hub_id'] : 'NULL';

            $charge_id = isset($post_data['charge_id']) && $post_data['charge_id'] > 0 ? $post_data['charge_id'] : '';
            $booking_date = isset($post_data['booking_date']) && $post_data['booking_date'] > 0 ? $post_data['booking_date'] : '';

            $chargeable_wt = isset($post_data['chargeable_wt']) && $post_data['chargeable_wt'] > 0 ? $post_data['chargeable_wt'] : 0;
            $actual_wt = isset($post_data['actual_wt']) && $post_data['actual_wt'] > 0 ? $post_data['actual_wt'] : 0;
            $volumetric_wt = isset($post_data['volumetric_wt']) && $post_data['volumetric_wt'] > 0 ? $post_data['volumetric_wt'] : 0;
            $total_pcs = isset($post_data['total_pcs']) && $post_data['total_pcs'] > 0 ? $post_data['total_pcs'] : 0;
            $shipment_value = isset($post_data['shipment_value']) && $post_data['shipment_value'] > 0 ? $post_data['shipment_value'] : 0;
            $charge_type = isset($post_data['charge_type']) && $post_data['charge_type'] > 0 ? $post_data['charge_type'] : 0;

            $con_pincode = isset($post_data['con_pincode']) ? $post_data['con_pincode'] : '';

            $ori_zone_service_type = isset($post_data['ori_zone_service_type']) && $post_data['ori_zone_service_type'] > 0 ? $post_data['ori_zone_service_type'] : 0;
            $dest_zone_service_type = isset($post_data['dest_zone_service_type']) && $post_data['dest_zone_service_type'] > 0 ? $post_data['dest_zone_service_type'] : 0;


            if ($charge_id > 0 && $booking_date != '') {

                //CHECK RATE MODIFIER HEAD PRESENT
                if ($customer_id > 0 && $booking_date != '' && $booking_date != '1970-01-01' && $booking_date != '0000-00-00') {
                    $query = "SELECT contract_customer_id,from_date,till_date FROM customer_contract_head
                    WHERE status IN(1) AND customer_id='" . $post_data['customer_id'] . "' AND head_type =2 
                    AND '" . $booking_date . "' BETWEEN from_date AND till_date ORDER BY from_date DESC";
                    $query_exe = $this->db->query($query);
                    $customer_rate_head = $query_exe->row_array();
                }

                if (isset($setting['apply_sepcial_rate_rate_modifier']) && $setting['apply_sepcial_rate_rate_modifier'] == 1) {
                    $contract_customer_id[$post_data['customer_id']] = $post_data['customer_id'];
                    if (isset($customer_rate_head) && is_array($customer_rate_head) && count($customer_rate_head) > 0) {
                        $contract_customer_id[$customer_rate_head['contract_customer_id']] = $customer_rate_head['contract_customer_id'];
                    }
                } else {

                    if (isset($customer_rate_head) && is_array($customer_rate_head) && count($customer_rate_head) > 0) {
                        $contract_customer_id[$customer_rate_head['contract_customer_id']] = $customer_rate_head['contract_customer_id'];
                    }
                    $contract_customer_id[$post_data['customer_id']] = $post_data['customer_id'];
                }

                // if (isset($customer_rate_head) && is_array($customer_rate_head) && count($customer_rate_head) > 0) {
                //     $rate_customer_id = $customer_rate_head['contract_customer_id'];
                // } else {
                //     $rate_customer_id = $customer_id;
                // }


                $rate_id_data = array();
                if (isset($contract_customer_id) && is_array($contract_customer_id) && count($contract_customer_id) > 0) {
                    foreach ($contract_customer_id as $ch_key => $ch_value) {

                        if (isset($rate_id_data) && is_array($rate_id_data) && count($rate_id_data) > 0) {
                            break;
                        }
                        $rate_customer_id = $ch_value;
                        $qry = "SELECT r.* FROM `rate_modifier` r 
            WHERE r.status IN(1) 
           AND (r.min_chargeable_wt<=0 OR r.max_chargeable_wt<=0 OR (r.min_chargeable_wt<=" . $chargeable_wt . " AND r.max_chargeable_wt>=" . $chargeable_wt . "))
           AND (r.min_actual_wt<=0 OR r.max_actual_wt<=0 OR (r.min_actual_wt<=" . $actual_wt . " AND r.max_actual_wt>=" . $actual_wt . "))
           AND (r.min_volume_wt<=0 OR r.max_volume_wt<=0 OR (r.min_volume_wt<=" . $volumetric_wt . " AND r.max_volume_wt>=" . $volumetric_wt . "))
           AND (r.min_boxes<=0 OR r.max_boxes<=0 OR (r.min_boxes<=" . $total_pcs . " AND r.max_boxes>=" . $total_pcs . "))
           AND (r.min_shipment_value<=0 OR r.max_shipment_value<=0 OR (r.min_shipment_value<=" . $shipment_value . " AND r.max_shipment_value>=" . $shipment_value . "))
           AND r.billing_type IN(1," . $charge_type . ")
           AND r.charge_id='" . $charge_id . "' AND '" . $booking_date
                            . "' BETWEEN r.effective_from AND r.effective_to ORDER BY r.id DESC";


                        $query_exe = $this->db->query($qry);
                        $rate_modi_result  = $query_exe->result_array();

                        $rate_ids = array();
                        $rate_table_data = array();
                        $rate_data_res = array();
                        $result = array();
                        $rate_group = array();

                        if (isset($rate_modi_result) && is_array($rate_modi_result) && count($rate_modi_result) > 0) {
                            foreach ($rate_modi_result as $ra_key => $ra_value) {
                                $rate_ids[$ra_value['id']] = $ra_value['id'];
                                $rate_table_data[$ra_value['id']] = $ra_value;

                                if ($ra_value['consignee_pincode'] != '') {
                                    $consignee_pincode_arr = explode(",", $ra_value['consignee_pincode']);
                                    if (isset($consignee_pincode_arr) && is_array($consignee_pincode_arr) && count($consignee_pincode_arr) > 0) {
                                        $con_pincode_arr[$ra_value['id']] = $consignee_pincode_arr;
                                    }
                                }
                            }
                        }

                        if (isset($rate_ids) && is_array($rate_ids) && count($rate_ids) > 0) {
                            $qry = "SELECT * FROM rate_modifier_data WHERE status=1 AND rate_modifier_id IN(" . implode(",", $rate_ids) . ")
                    ORDER BY rate_modifier_id DESC";
                            $query_exe = $this->db->query($qry);
                            $rate_data_res  = $query_exe->result_array();
                        }

                        if (isset($rate_data_res) && is_array($rate_data_res) && count($rate_data_res) > 0) {
                            foreach ($rate_data_res as $key => $value) {
                                if (!isset($result[$value['rate_modifier_id']])) {
                                    $result[$value['rate_modifier_id']] = isset($rate_table_data[$value['rate_modifier_id']]) ? $rate_table_data[$value['rate_modifier_id']] : array();
                                }


                                if (isset($con_pincode_arr[$value['rate_modifier_id']]) && is_array($con_pincode_arr[$value['rate_modifier_id']]) && count($con_pincode_arr[$value['rate_modifier_id']]) > 0) {
                                    $result[$value['rate_modifier_id']]['con_pincode_arr'] = $con_pincode_arr[$value['rate_modifier_id']];
                                }

                                if ($value['module_type'] == 1) {
                                    $result[$value['rate_modifier_id']]['customer_id_arr'][] = $value['module_id'];
                                } else if ($value['module_type'] == 2) {
                                    $result[$value['rate_modifier_id']]['product_id_arr'][] = $value['module_id'];
                                } else if ($value['module_type'] == 3) {
                                    $result[$value['rate_modifier_id']]['vendor_id_arr'][] = $value['module_id'];
                                } else if ($value['module_type'] == 4) {
                                    $result[$value['rate_modifier_id']]['co_vendor_id_arr'][] = $value['module_id'];
                                } else if ($value['module_type'] == 5) {
                                    $result[$value['rate_modifier_id']]['destination_id_arr'][] = $value['module_id'];
                                } else if ($value['module_type'] == 6) {
                                    $result[$value['rate_modifier_id']]['dest_zone_id_arr'][] = $value['module_id'];
                                } else if ($value['module_type'] == 7) {
                                    $result[$value['rate_modifier_id']]['origin_id_arr'][] = $value['module_id'];
                                } else if ($value['module_type'] == 8) {
                                    $result[$value['rate_modifier_id']]['ori_zone_id_arr'][] = $value['module_id'];
                                } else if ($value['module_type'] == 9) {
                                    $result[$value['rate_modifier_id']]['dest_hub_id_arr'][] = $value['module_id'];
                                } else if ($value['module_type'] == 10) {
                                    $result[$value['rate_modifier_id']]['ori_hub_id_arr'][] = $value['module_id'];
                                }
                            }
                        }

                        if (isset($result) && is_array($result) && count($result) > 0) {
                            foreach ($result as $ra_key => $ra_value) {
                                $rate_group[$ra_value['id']] = $ra_value;
                                if (isset($ra_value['destination_id_arr']) && $ra_value['destination_id_arr'] > 0) {
                                    $rate_group[$ra_value['id']]['destination_id_arr'] = $ra_value['destination_id_arr'];
                                }
                                if (isset($ra_value['dest_zone_id_arr']) && $ra_value['dest_zone_id_arr'] > 0) {
                                    $rate_group[$ra_value['id']]['dest_zone_id_arr'] = $ra_value['dest_zone_id_arr'];
                                }
                                if (isset($ra_value['dest_hub_id_arr']) && $ra_value['dest_hub_id_arr'] > 0) {
                                    $rate_group[$ra_value['id']]['dest_hub_id_arr'] = $ra_value['dest_hub_id_arr'];
                                }

                                if (isset($ra_value['origin_id_arr']) && $ra_value['origin_id_arr'] > 0) {
                                    $rate_group[$ra_value['id']]['origin_id_arr'] = $ra_value['origin_id_arr'];
                                }
                                if (isset($ra_value['ori_zone_id_arr']) && $ra_value['ori_zone_id_arr'] > 0) {
                                    $rate_group[$ra_value['id']]['ori_zone_id_arr'] = $ra_value['ori_zone_id_arr'];
                                }
                                if (isset($ra_value['ori_hub_id']) && $ra_value['ori_hub_id'] > 0) {
                                    $rate_group[$ra_value['id']]['ori_hub_id_arr'] = $ra_value['ori_hub_id'];
                                }

                                if (isset($ra_value['customer_id_arr']) && $ra_value['customer_id_arr'] > 0) {
                                    $rate_group[$ra_value['id']]['customer_id_arr'] = $ra_value['customer_id_arr'];
                                }

                                if (isset($ra_value['product_id_arr']) && $ra_value['product_id_arr'] > 0) {
                                    $rate_group[$ra_value['id']]['product_id_arr'] = $ra_value['product_id_arr'];
                                }
                                if (isset($ra_value['vendor_id_arr']) && $ra_value['vendor_id_arr'] > 0) {
                                    $rate_group[$ra_value['id']]['vendor_id_arr'] = $ra_value['vendor_id_arr'];
                                }
                                if (isset($ra_value['co_vendor_id_arr']) && $ra_value['co_vendor_id_arr'] > 0) {
                                    $rate_group[$ra_value['id']]['co_vendor_id_arr'] = $ra_value['co_vendor_id_arr'];
                                }
                            }
                        }

                        if (isset($rate_group) && is_array($rate_group) && count($rate_group) > 0) {
                            foreach ($rate_group as $gkey => $gvalue) {
                                $rate_match = 0;


                                if (isset($gvalue['con_pincode_arr']) && is_array($gvalue['con_pincode_arr']) && count($gvalue['con_pincode_arr']) > 0) {
                                    $pincode_match = false;
                                    foreach ($gvalue['con_pincode_arr'] as $pkey => $pvalue) {
                                        if ($pvalue == $con_pincode || strpos($con_pincode, $pvalue) === 0) {
                                            $pincode_match = true;
                                            break;
                                        }
                                    }

                                    if (!$pincode_match) {
                                        unset($result[$gkey]);
                                        continue;
                                    }
                                }

                                // if (isset($gvalue['con_pincode_arr']) && !in_array($con_pincode, $gvalue['con_pincode_arr'])) {
                                //     unset($result[$gkey]);
                                //     continue;
                                // }

                                if (isset($gvalue['customer_id_arr']) && !in_array($rate_customer_id, $gvalue['customer_id_arr'])) {
                                    unset($result[$gkey]);
                                    continue;
                                }
                                if (isset($gvalue['product_id_arr']) && !in_array($product_id, $gvalue['product_id_arr'])) {
                                    unset($result[$gkey]);
                                    continue;
                                }
                                if (isset($gvalue['vendor_id_arr']) && !in_array($vendor_id, $gvalue['vendor_id_arr'])) {
                                    unset($result[$gkey]);
                                    continue;
                                }
                                if (isset($gvalue['co_vendor_id_arr']) && !in_array($co_vendor_id, $gvalue['co_vendor_id_arr'])) {
                                    unset($result[$gkey]);
                                    continue;
                                }
                                if (isset($gvalue['destination_id_arr']) && in_array($destination_id, $gvalue['destination_id_arr'])) {
                                    $rate_match += 1;
                                } else if (isset($gvalue['dest_zone_id_arr']) && in_array($dest_zone_id, $gvalue['dest_zone_id_arr'])) {
                                    $rate_match += 1;
                                } else if (isset($gvalue['dest_hub_id_arr']) && in_array($dest_hub_id, $gvalue['dest_hub_id_arr'])) {
                                    $rate_match += 1;
                                } else if (
                                    !isset($gvalue['destination_id_arr'])
                                    && !isset($gvalue['dest_zone_id_arr'])
                                    && !isset($gvalue['dest_hub_id_arr'])
                                ) {
                                    $rate_match += 1;
                                }

                                if (isset($gvalue['origin_id_arr']) && in_array($origin_id, $gvalue['origin_id_arr'])) {
                                    $rate_match += 1;
                                } else if (isset($gvalue['ori_zone_id_arr']) && in_array($ori_zone_id, $gvalue['ori_zone_id_arr'])) {
                                    $rate_match += 1;
                                } else if (isset($gvalue['ori_hub_id_arr']) && in_array($ori_hub_id, $gvalue['ori_hub_id_arr'])) {
                                    $rate_match += 1;
                                } else if (!isset($gvalue['origin_id_arr']) && !isset($gvalue['ori_zone_id_arr']) && !isset($gvalue['ori_hub_id_arr'])) {
                                    $rate_match += 1;
                                }


                                if ($rate_match < 2) {
                                    unset($result[$gkey]);
                                }
                            }
                        }
                        $condition_arr = array(
                            'customer_id_arr', 'product_id_arr', 'vendor_id_arr', 'co_vendor_id_arr',
                            'destination_id_arr', 'dest_zone_id_arr', 'origin_id_arr', 'ori_zone_id_arr',
                            'ori_hub_id_arr', 'dest_hub_id_arr'
                        );
                        //CHECK MAXIMUM CONDITION MATCH
                        $highest_match = 0;
                        $highest_date = '';


                        // if (isset($_GET['test']) && $charge_id == 17) {
                        //     echo '<pre>';
                        //     print_r($result);
                        //     exit;
                        // }

                        if (isset($result) && is_array($result) && count($result) > 0) {
                            foreach ($result as $key => $value) {

                                if ($chargeable_wt == '') {
                                    $chargeable_wt = 0;
                                }
                                if ($actual_wt == '') {
                                    $actual_wt = 0;
                                }
                                if ($volumetric_wt == '') {
                                    $volumetric_wt = 0;
                                }
                                if ($total_pcs == '') {
                                    $total_pcs = 0;
                                }
                                if ($shipment_value == '') {
                                    $shipment_value = 0;
                                }
                                if ((int)$value['min_chargeable_wt'] > 0 && $chargeable_wt < $value['min_chargeable_wt']) {
                                    continue;
                                }
                                if ((int)$value['min_actual_wt'] > 0 && $actual_wt < $value['min_actual_wt']) {
                                    continue;
                                }
                                if ((int)$value['min_volume_wt'] > 0 && $volumetric_wt < $value['min_volume_wt']) {
                                    continue;
                                }
                                if ((int)$value['min_boxes'] > 0 && $total_pcs < $value['min_boxes']) {
                                    continue;
                                }
                                if ((int)$value['min_shipment_value'] > 0 && $shipment_value < $value['min_shipment_value']) {
                                    continue;
                                }

                                if ((int)$value['max_chargeable_wt'] > 0 && $chargeable_wt > $value['max_chargeable_wt']) {
                                    continue;
                                }
                                if ((int)$value['max_actual_wt'] > 0 && $actual_wt > $value['max_actual_wt']) {
                                    continue;
                                }
                                if ((int)$value['max_volume_wt'] > 0 && $volumetric_wt > $value['max_volume_wt']) {
                                    continue;
                                }
                                if ((int)$value['max_boxes'] > 0 && $total_pcs > $value['max_boxes']) {
                                    continue;
                                }
                                if ((int)$value['max_shipment_value'] > 0 && $shipment_value > $value['max_shipment_value']) {
                                    continue;
                                }

                                //MIN DIMENSION

                                if ((int)$value['min_dimension'] > 0) {
                                    $min_dimension_match = array();
                                    if (isset($post_data['dim_len']) && is_array($post_data['dim_len']) && count($post_data['dim_len']) > 0) {
                                        foreach ($post_data['dim_len'] as $lkey => $lvalue) {
                                            if ($lvalue >= $value['min_dimension']) {
                                                $min_dimension_match[] = 1;
                                            }
                                        }
                                    }
                                    if (isset($post_data['dim_wid']) && is_array($post_data['dim_wid']) && count($post_data['dim_wid']) > 0) {
                                        foreach ($post_data['dim_wid'] as $bkey => $bvalue) {
                                            if ($bvalue >= $value['min_dimension']) {
                                                $min_dimension_match[] = 1;
                                            }
                                        }
                                    }
                                    if (isset($post_data['dim_hei']) && is_array($post_data['dim_hei']) && count($post_data['dim_hei']) > 0) {
                                        foreach ($post_data['dim_hei'] as $hkey => $hvalue) {
                                            if ($hvalue >= $value['min_dimension']) {
                                                $min_dimension_match[] = 1;
                                            }
                                        }
                                    }


                                    if (count($min_dimension_match) == 0) {
                                        continue;
                                    }
                                }

                                //MAX DIMENSION
                                if ((int)$value['max_dimension'] > 0) {
                                    $max_dimension_match = array();
                                    if (isset($post_data['dim_len']) && is_array($post_data['dim_len']) && count($post_data['dim_len']) > 0) {
                                        foreach ($post_data['dim_len'] as $lkey => $lvalue) {
                                            if ($lvalue <= $value['max_dimension']) {
                                                $max_dimension_match[] = 1;
                                            }
                                        }
                                    }
                                    if (isset($post_data['dim_wid']) && is_array($post_data['dim_wid']) && count($post_data['dim_wid']) > 0) {
                                        foreach ($post_data['dim_wid'] as $bkey => $bvalue) {
                                            if ($bvalue <= $value['max_dimension']) {
                                                $max_dimension_match[] = 1;
                                            }
                                        }
                                    }
                                    if (isset($post_data['dim_hei']) && is_array($post_data['dim_hei']) && count($post_data['dim_hei']) > 0) {
                                        foreach ($post_data['dim_hei'] as $hkey => $hvalue) {
                                            if ($hvalue <= $value['max_dimension']) {
                                                $max_dimension_match[] = 1;
                                            }
                                        }
                                    }

                                    if (count($max_dimension_match) == 0) {
                                        continue;
                                    }
                                }

                                $min_wt_breach = array();
                                $max_wt_breach = array();
                                $min_vol_wt_breach = array();
                                $max_vol_wt_breach = array();
                                $min_cha_wt_breach = array();
                                $max_cha_wt_breach = array();

                                //MIN PER BOX ACTUAL WEIGHT
                                if ((int)$value['min_per_box_wt'] > 0) {
                                    if (isset($post_data['act_wt']) && is_array($post_data['act_wt']) && count($post_data['act_wt']) > 0) {
                                        foreach ($post_data['act_wt'] as $lkey => $lvalue) {
                                            if ($lvalue < $value['min_per_box_wt']) {
                                                $min_wt_breach[] = 1;
                                            }
                                        }
                                    }

                                    if (
                                        isset($min_wt_breach) && is_array($min_wt_breach) && count($min_wt_breach) > 0
                                        && count($min_wt_breach) == count($post_data['act_wt'])
                                    ) {
                                        continue;
                                    }
                                }

                                //MAX PER BOX ACTUAL WEIGHT
                                if ((int)$value['max_per_box_wt'] > 0) {
                                    if (isset($post_data['act_wt']) && is_array($post_data['act_wt']) && count($post_data['act_wt']) > 0) {
                                        foreach ($post_data['act_wt'] as $lkey => $lvalue) {
                                            if ($lvalue > $value['max_per_box_wt']) {
                                                $max_wt_breach[] = 1;
                                            }
                                        }
                                    }
                                    if (
                                        isset($max_wt_breach) && is_array($max_wt_breach) && count($max_wt_breach) > 0
                                        && count($max_wt_breach) == count($post_data['act_wt'])
                                    ) {
                                        continue;
                                    }
                                }

                                //MIN PER BOX VOLUMETRIC WEIGHT
                                if ((int)$value['min_per_box_vol_wt'] > 0) {
                                    if (isset($post_data['vol_wt']) && is_array($post_data['vol_wt']) && count($post_data['vol_wt']) > 0) {
                                        foreach ($post_data['vol_wt'] as $lkey => $lvalue) {
                                            if ($lvalue < $value['min_per_box_vol_wt']) {
                                                $min_vol_wt_breach[] = 1;
                                            }
                                        }
                                    }

                                    if (
                                        isset($min_vol_wt_breach) && is_array($min_vol_wt_breach) && count($min_vol_wt_breach) > 0
                                        && count($min_vol_wt_breach) == count($post_data['vol_wt'])
                                    ) {
                                        continue;
                                    }
                                }

                                //MAX PER BOX VOLUMETRIC WEIGHT
                                if ((int)$value['max_per_box_vol_wt'] > 0) {
                                    if (isset($post_data['vol_wt']) && is_array($post_data['vol_wt']) && count($post_data['vol_wt']) > 0) {
                                        foreach ($post_data['vol_wt'] as $lkey => $lvalue) {
                                            if ($lvalue > $value['max_per_box_vol_wt']) {
                                                $max_vol_wt_breach[] = 1;
                                            }
                                        }
                                    }
                                    if (
                                        isset($max_vol_wt_breach) && is_array($max_vol_wt_breach) && count($max_vol_wt_breach) > 0
                                        && count($max_vol_wt_breach) == count($post_data['vol_wt'])
                                    ) {
                                        continue;
                                    }
                                }


                                //MIN PER BOX CHARGEABLE WEIGHT
                                if ((int)$value['min_per_box_cha_wt'] > 0) {
                                    if (isset($post_data['char_wt']) && is_array($post_data['char_wt']) && count($post_data['char_wt']) > 0) {
                                        foreach ($post_data['char_wt'] as $lkey => $lvalue) {
                                            if ($lvalue < $value['min_per_box_cha_wt']) {
                                                $min_cha_wt_breach[] = 1;
                                            }
                                        }
                                    }

                                    if (
                                        isset($min_cha_wt_breach) && is_array($min_cha_wt_breach) && count($min_cha_wt_breach) > 0
                                        && count($min_cha_wt_breach) == count($post_data['char_wt'])
                                    ) {
                                        continue;
                                    }
                                }

                                //MAX PER BOX CHARGEABLE WEIGHT
                                if ((int)$value['max_per_box_cha_wt'] > 0) {
                                    if (isset($post_data['char_wt']) && is_array($post_data['char_wt']) && count($post_data['char_wt']) > 0) {
                                        foreach ($post_data['char_wt'] as $lkey => $lvalue) {
                                            if ($lvalue > $value['max_per_box_cha_wt']) {
                                                $max_cha_wt_breach[] = 1;
                                            }
                                        }
                                    }
                                    if (
                                        isset($max_cha_wt_breach) && is_array($max_cha_wt_breach) && count($max_cha_wt_breach) > 0
                                        && count($max_cha_wt_breach) == count($post_data['char_wt'])
                                    ) {
                                        continue;
                                    }
                                }

                                $condition_match_cnt = 0;
                                if (isset($value['customer_id_arr']) && in_array($rate_customer_id, $value['customer_id_arr'])) {
                                    $condition_match_cnt += 8;
                                }
                                if (isset($value['vendor_id_arr']) && in_array($vendor_id, $value['vendor_id_arr'])) {
                                    $condition_match_cnt += 4;
                                }
                                if (isset($value['co_vendor_id_arr']) && in_array($co_vendor_id, $value['co_vendor_id_arr'])) {
                                    $condition_match_cnt += 2;
                                }
                                if (isset($value['product_id_arr']) && in_array($product_id, $value['product_id_arr'])) {
                                    $condition_match_cnt += 1;
                                }


                                if (isset($value['destination_id_arr']) && in_array($destination_id, $value['destination_id_arr'])) {
                                    $condition_match_cnt += 1;
                                }
                                if (isset($value['dest_zone_id_arr']) && in_array($dest_zone_id, $value['dest_zone_id_arr'])) {
                                    $condition_match_cnt += 1;
                                }
                                if (isset($value['dest_hub_id_arr']) && in_array($dest_hub_id, $value['dest_hub_id_arr'])) {
                                    $condition_match_cnt += 1;
                                }

                                if (isset($value['origin_id_arr']) && in_array($origin_id, $value['origin_id_arr'])) {
                                    $condition_match_cnt += 1;
                                }
                                if (isset($value['ori_zone_id_arr']) && in_array($ori_zone_id, $value['ori_zone_id_arr'])) {
                                    $condition_match_cnt += 1;
                                }
                                if (isset($value['ori_hub_id_arr']) && in_array($ori_hub_id, $value['ori_hub_id_arr'])) {
                                    $condition_match_cnt += 1;
                                }

                                if ($ori_zone_service_type == 2 && $value['is_origin_oda'] == 1) {
                                    $condition_match_cnt += 1;
                                }
                                if ($dest_zone_service_type == 2 && $value['is_dest_oda'] == 1) {
                                    $condition_match_cnt += 1;
                                }

                                if (isset($gvalue['con_pincode_arr']) && is_array($gvalue['con_pincode_arr']) && count($gvalue['con_pincode_arr']) > 0) {
                                    $pincode_match = false;
                                    foreach ($gvalue['con_pincode_arr'] as $pkey => $pvalue) {
                                        if ($pvalue == $con_pincode || strpos($con_pincode, $pvalue) === 0) {
                                            $pincode_match = true;
                                            break;
                                        }
                                    }

                                    if ($pincode_match) {
                                        $condition_match_cnt += 1;
                                    }
                                }

                                // foreach ($condition_arr as $ckey => $cvalue) {
                                //     if ($value[$cvalue] > 0) {
                                //         $condition_match_cnt = $condition_match_cnt + 1;
                                //     }
                                // }

                                if ($condition_match_cnt > $highest_match) {
                                    $highest_match = $condition_match_cnt;
                                    $highest_date = $value['effective_from'];
                                    $rate_id_data = $value;
                                } else if ($condition_match_cnt == $highest_match && $value['effective_from'] >  $highest_date) {
                                    $highest_match = $condition_match_cnt;
                                    $highest_date = $value['effective_from'];
                                    $rate_id_data = $value;
                                }

                                if ($key == 0) {
                                    $highest_match = $condition_match_cnt;
                                    $highest_date = $value['effective_from'];
                                    $rate_id_data = $value;
                                }
                                // $match_rate[$value['id']] =  $condition_match_cnt;
                                // $match_rate_date[$value['id']] =  $value['effective_from'];
                            }
                        } else {
                            $rate_id_data = isset($result[0]) ? $result[0] : array();
                        }
                    }
                }
            }
        }
        $rate_amt = 0;
        $rate_apply_amt = 0;


        if (isset($rate_id_data) && is_array($rate_id_data) && count($rate_id_data) > 0) {
            $freight_amount = isset($post_data['freight_amount']) && $post_data['freight_amount'] > 0 ? $post_data['freight_amount'] : 0;
            $fsc_amount = isset($post_data['fsc_amount']) && $post_data['fsc_amount'] > 0 ? $post_data['fsc_amount'] : 0;

            if ($rate_id_data['freight_per'] > 0) {
                $rate_amt = ($freight_amount * $rate_id_data['freight_per']) / 100;
            } else if ($rate_id_data['shipment_per'] > 0) {
                $rate_amt = ($shipment_value * $rate_id_data['shipment_per']) / 100;
            } else if ($rate_id_data['freight_fsc_per'] > 0) {

                //THIS SETTING APPLY ONLY WHEN FSC APPLY DISABLE IN CHARGE
                $qry = "SELECT id FROM charge_master WHERE status IN(1,2) AND id='" . $rate_id_data['charge_id'] . "' AND is_fsc_apply=2";
                $qry_exe = $this->db->query($qry);
                $charge_res = $qry_exe->result_array();

                if (isset($charge_res) && is_array($charge_res) && count($charge_res) > 0) {
                    $rate_amt = (($freight_amount + $fsc_amount) * $rate_id_data['freight_fsc_per']) / 100;
                }
            } else if ($rate_id_data['fixed_amt'] > 0) {

                $rate_type = $rate_id_data['rate_per_type'];
                $rate_apply_amt = $rate_id_data['fixed_amt'];
                if ($rate_type == 1) {
                    $rate_amt = $total_pcs * $rate_apply_amt;
                } elseif ($rate_type == 2) {
                    $chargeable_wt = ceil($chargeable_wt);
                    $rate_amt = $chargeable_wt * $rate_apply_amt;
                } elseif ($rate_type == 3) {
                    $round_no = $this->round_half_number($chargeable_wt);
                    $rate_amt = $round_no * 2 * $rate_apply_amt;
                } else {
                    $rate_amt = $rate_apply_amt;
                }


                if ($rate_type == 4) {
                    if ((int)$rate_id_data['min_per_box_wt'] > 0) {
                        if (isset($post_data['char_wt']) && is_array($post_data['char_wt']) && count($post_data['char_wt']) > 0) {
                            foreach ($post_data['char_wt'] as $lkey => $lvalue) {
                                if ($lvalue > $rate_id_data['min_per_box_wt']) {
                                    $rate_pieces[$lkey] = $lvalue;
                                }
                            }
                        }
                    }
                } else if ($rate_id_data['grith_check'] == 1) {
                    if (isset($post_data['dim_len']) && is_array($post_data['dim_len']) && count($post_data['dim_len']) > 0) {
                        foreach ($post_data['dim_len'] as $lkey => $lvalue) {
                            $dim_arr[0] = $lvalue > 0 ? $lvalue : 0;
                            $dim_arr[1] = isset($post_data['dim_wid'][$lkey]) && $post_data['dim_wid'][$lkey] > 0 ? $post_data['dim_wid'][$lkey] : 0;
                            $dim_arr[2] = isset($post_data['dim_hei'][$lkey]) && $post_data['dim_hei'][$lkey] > 0 ? $post_data['dim_hei'][$lkey] : 0;


                            asort($dim_arr);
                            $total_dim = 0;
                            foreach ($dim_arr as $dkey => $dvalue) {
                                if ($dkey == 0) {
                                    $new_dim = $dvalue;
                                } elseif ($dkey == 1) {
                                    $new_dim = $dvalue * 2;
                                } elseif ($dkey == 2) {
                                    $new_dim = $dvalue * 2;
                                }
                                $total_dim = $total_dim + $new_dim;
                            }
                            $min_dimension = $rate_id_data['min_dimension'];
                            if ($min_dimension > 0) {
                                if ($total_dim < $min_dimension) {
                                    $rate_pieces[$lkey] = $lvalue;
                                }
                            }

                            $max_dimension = $rate_id_data['max_dimension'];
                            if ($max_dimension > 0) {
                                if ($total_dim > $max_dimension) {
                                    $rate_pieces[$lkey] = $lvalue;
                                }
                            }
                        }
                    }
                } else {
                    //CHECK MIN MAX DIMENSION PER PIECES

                    if ($rate_id_data['min_dim_per_pc'] == 1) {
                        $min_dimension = $rate_id_data['min_dimension'];

                        if ($min_dimension > 0) {
                            if (isset($post_data['dim_len']) && is_array($post_data['dim_len']) && count($post_data['dim_len']) > 0) {
                                foreach ($post_data['dim_len'] as $lkey => $lvalue) {
                                    if ($lvalue >= $min_dimension) {
                                        $rate_pieces[$lkey] = $lvalue;
                                    }
                                }
                            }

                            if (isset($post_data['dim_wid']) && is_array($post_data['dim_wid']) && count($post_data['dim_wid']) > 0) {
                                foreach ($post_data['dim_wid'] as $bkey => $bvalue) {
                                    if ($bvalue >= $min_dimension) {
                                        $rate_pieces[$bkey] = $bvalue;
                                    }
                                }
                            }


                            if (isset($post_data['dim_hei']) && is_array($post_data['dim_hei']) && count($post_data['dim_hei']) > 0) {
                                foreach ($post_data['dim_hei'] as $hkey => $hvalue) {
                                    if ($hvalue >= $min_dimension) {
                                        $rate_pieces[$hkey] = $hvalue;
                                    }
                                }
                            }
                        }
                    }


                    if ($rate_id_data['max_dim_per_pc'] == 1) {
                        $max_dimension = $rate_id_data['max_dimension'];
                        if ($max_dimension > 0) {
                            if (isset($post_data['dim_len']) && is_array($post_data['dim_len']) && count($post_data['dim_len']) > 0) {
                                foreach ($post_data['dim_len'] as $lkey => $lvalue) {
                                    if ($lvalue <= $max_dimension) {
                                        $rate_pieces[$lkey] = $lvalue;
                                    }
                                }
                            }

                            if (isset($post_data['dim_wid']) && is_array($post_data['dim_wid']) && count($post_data['dim_wid']) > 0) {
                                foreach ($post_data['dim_wid'] as $bkey => $bvalue) {
                                    if ($bvalue <= $max_dimension) {
                                        $rate_pieces[$bkey] = $bvalue;
                                    }
                                }
                            }

                            if (isset($post_data['dim_hei']) && is_array($post_data['dim_hei']) && count($post_data['dim_hei']) > 0) {
                                foreach ($post_data['dim_hei'] as $hkey => $hvalue) {
                                    if ($hvalue <= $max_dimension) {
                                        $rate_pieces[$hkey] = $hvalue;
                                    }
                                }
                            }
                        }
                    }
                }


                if (isset($rate_pieces) && is_array($rate_pieces) && count($rate_pieces) > 0) {
                    $extra_rate = count($rate_pieces) * $rate_apply_amt;
                    // $rate_amt = $rate_amt  +  $extra_rate;
                    $rate_amt = $extra_rate;
                } else if ($rate_type == 4) {
                    $rate_amt = 0;
                }

                if ($rate_amt == 0 && $rate_type != 4) {
                    $rate_amt = $rate_id_data['fixed_amt'];
                }
            }
        }

        if (isset($rate_id_data['min_amt']) && $rate_id_data['min_amt'] > 0 && $rate_type != 4) {
            if ($rate_amt < $rate_id_data['min_amt']) {
                $rate_amt = $rate_id_data['min_amt'];
            }
        }

        if (isset($rate_id_data['slab_wise_rate']) && $rate_id_data['slab_wise_rate'] == 1 && $rate_id_data['fixed_amt'] > 0 && $rate_id_data['min_amt'] > 0) {

            $slab_add = 0;
            $rate_type = $rate_id_data['rate_per_type'];
            $fixed_amt = $rate_id_data['fixed_amt'];
            $min_amt = $rate_id_data['min_amt'];

            $rate_amt = $min_amt;
            if ($rate_type == 1) {
                $slab_wt = $total_pcs - $rate_id_data['min_boxes'];
                $rate_amt += $slab_wt * $fixed_amt;
            } elseif ($rate_type == 2) {
                $chargeable_wt = ceil($chargeable_wt);
                $slab_wt = $chargeable_wt - $rate_id_data['min_chargeable_wt'];
                $rate_amt += $slab_wt * $fixed_amt;
            } elseif ($rate_type == 3) {
                $chargeable_wt = round($chargeable_wt, 2);
                $slab_wt = $chargeable_wt - $rate_id_data['min_chargeable_wt'];
                $rate_amt += $slab_wt * $fixed_amt * 2;
            }
        }
        $final_data['id'] = isset($rate_id_data['id']) ? $rate_id_data['id'] : 0;
        $final_data['freight_fsc_per'] = isset($rate_id_data['freight_fsc_per']) ? $rate_id_data['freight_fsc_per'] : 0;
        $final_data['rate_amt'] = isset($rate_amt) ? $rate_amt : 0;
        if (isset($post) && is_array($post) && count($post) > 0) {
            return $final_data;
        } else {
            echo json_encode($final_data);
        }
    }


    public function show_service_popup()
    {
        $data = array();
        $post_data = $this->input->post();
        if (isset($post_data) && is_array($post_data) && count($post_data) > 0) {
            $this->load->module('rate_cal');

            $filter_data['customer_id'] = isset($post_data['docket']['customer_id']) ? $post_data['docket']['customer_id'] : 0;
            if (isset($post_data['docket']['product_id']) && $post_data['docket']['product_id'] != '') {
                $all_product = get_all_product(" AND id='" . $post_data['docket']['product_id'] . "'", 'id');
            }

            $filter_data['pdt_format_id'] = isset($all_product[$post_data['docket']['product_id']]) ? $all_product[$post_data['docket']['product_id']]['pdt_format'] : 0;
            $filter_data['destination_id'] = isset($post_data['docket']['destination_id']) && $post_data['docket']['destination_id'] > 0 ? $post_data['docket']['destination_id'] : 0;
            $filter_data['origin_id'] = isset($post_data['docket']['origin_id']) && $post_data['docket']['origin_id'] > 0 ? $post_data['docket']['origin_id'] : 0;
            $filter_data['pieces'] = isset($post_data['docket']['total_pcs']) ? $post_data['docket']['total_pcs'] : 0;

            if (isset($post_data['docket']['booking_date']) && $post_data['docket']['booking_date'] != '') {
                $filter_data['booking_date'] = $post_data['docket']['booking_date'];
            }

            if (isset($post_data['docket']['actual_wt']) && $post_data['docket']['actual_wt'] != '') {
                $filter_data['ch_weight'] = $post_data['docket']['actual_wt'];
            }
            $filter_data['mobile_api'] = 1;
            $filter_data['total_amount'] = 1;
            $filter_data['order'] = 'asc';

            $data = $this->rate_cal->live_customer_rate($filter_data);


            //GET NIMBUSPOSTSERVICE
            $nimbus_filter = array(
                'docket' => array(
                    'vendor_id' => isset($post_data['docket']['vendor_id']) ? $post_data['docket']['vendor_id'] : 0,
                    'customer_id' => isset($post_data['docket']['customer_id']) ? $post_data['docket']['customer_id'] : 0,
                    'payment_type' => isset($post_data['docket']['payment_type']) ? $post_data['docket']['payment_type'] : 0,
                    'shipment_value' => isset($post_data['docket']['shipment_value']) ? $post_data['docket']['shipment_value'] : 0,
                    'actual_wt' => isset($post_data['docket']['actual_wt']) ? $post_data['docket']['actual_wt'] : 0,
                    'shipper' => array(
                        'pincode' =>  isset($post_data['shipper']['pincode']) ? $post_data['shipper']['pincode'] : '',
                    ),
                    'consignee' => array(
                        'pincode' =>  isset($post_data['consignee']['pincode']) ? $post_data['consignee']['pincode'] : '',
                    ),
                    'act_wt' => isset($post_data['act_wt']) ? $post_data['act_wt'] : '',
                    'dim_box_count' =>  isset($post_data['dim_box_count']) ? $post_data['dim_box_count'] : '',
                    'dim_len' =>  isset($post_data['dim_len']) ? $post_data['dim_len'] : '',
                    'dim_wid' =>  isset($post_data['dim_wid']) ? $post_data['dim_wid'] : '',
                    'dim_hei' =>  isset($post_data['dim_hei']) ? $post_data['dim_hei'] : '',
                )
            );
            $this->load->module('shipping_api/nimbuspost_domestic');
            $nimbus_filter['popup_api'] = 1;
            $data['nimbus_rate'] = $this->nimbuspost_domestic->get_serviceability(0, '', $nimbus_filter);
            $supported_vendor = $this->config->item('supported_label_vendor');
            $norsk_service_id = array_search('norsk', $supported_vendor);

            $nimbus_service_id = array_search('nimbuspost', $supported_vendor);
            $query = "SELECT v.id,v.label_head_id,v.api_credentials FROM vendor v 
            WHERE v.status IN(1,2) AND v.label_head_id='" . $nimbus_service_id . "'";
            $query_exe = $this->db->query($query);
            $nimbus_vendor_data = $query_exe->row_array();
            $data['nimbus_vendor_id'] = isset($nimbus_vendor_data['id']) ? $nimbus_vendor_data['id'] : 0;


            if ($norsk_service_id > 0) {
                $query = "SELECT v.id,v.label_head_id,v.api_credentials,v.code FROM vendor v 
            WHERE v.status IN(1,2) AND v.label_head_id='" . $norsk_service_id . "'";
                $query_exe = $this->db->query($query);
                $vendor_data = $query_exe->result_array();

                $query =  "SELECT v.id,v.label_head_id,v.api_credentials,v.code
                FROM  co_vendor v
                 WHERE v.status IN(1,2) AND v.label_head_id='" . $norsk_service_id . "'";
                $query_exe = $this->db->query($query);
                $co_vendor_data = $query_exe->result_array();
            }

            if (isset($vendor_data) && is_array($vendor_data) && count($vendor_data) > 0) {
                $final_credential = array();
                foreach ($vendor_data as $vkey => $vvalue) {
                    $credential_data = $vvalue;
                    if (isset($credential_data) && is_array($credential_data) && count($credential_data) > 0) {
                        if ($credential_data['api_credentials'] != '') {
                            $credential_data = explode("\n", $credential_data['api_credentials']);


                            if (isset($credential_data) && is_array($credential_data) && count($credential_data) > 0) {
                                foreach ($credential_data as $ckey => $cvalue) {
                                    if ($cvalue != '') {
                                        $credential_arr = explode(":", $cvalue);
                                        $final_credential[strtolower(trim($credential_arr[0]))] = str_ireplace(array("\r", "\n", '\r', '\n'), "", $credential_arr[1]);
                                    }
                                }
                            }
                        }
                    }

                    $all_country = get_all_country();
                    $all_currency = get_all_currency();

                    //GET NORSK SERVICE
                    $norsk_filter = array(
                        'consignee_pincode' => isset($post_data['consignee']['pincode']) ? $post_data['consignee']['pincode'] : 0,
                        'consignee_city' => isset($post_data['consignee']['city']) ? $post_data['consignee']['city'] : 0,
                        'consignee_country' => isset($post_data['consignee']['country']) && isset($all_country[$post_data['consignee']['country']]) ? $all_country[$post_data['consignee']['country']]['code'] : '',
                        'shipment_value' => isset($post_data['docket']['shipment_value']) ? $post_data['docket']['shipment_value'] : 0,
                        'shipment_currency' => isset($post_data['docket']['shipment_currency_id']) && isset($all_currency[$post_data['docket']['shipment_currency_id']]) ? $all_currency[$post_data['docket']['shipment_currency_id']]['code'] : '',
                        'act_wt' =>  isset($post_data['act_wt']) ? $post_data['act_wt'] : '',
                        'char_wt' =>  isset($post_data['char_wt']) ? $post_data['char_wt'] : '',
                        'dim_box_count' =>  isset($post_data['dim_box_count']) ? $post_data['dim_box_count'] : '',
                        'dim_len' =>  isset($post_data['dim_len']) ? $post_data['dim_len'] : '',
                        'dim_wid' =>  isset($post_data['dim_wid']) ? $post_data['dim_wid'] : '',
                        'dim_hei' =>  isset($post_data['dim_hei']) ? $post_data['dim_hei'] : '',
                        'norsk_vendor_id' =>  isset($vvalue['id']) ? $vvalue['id'] : '',
                    );
                    $this->load->module('shipping_api/norsk_quote');
                    $norsk_rate = $this->norsk_quote->get_quote($final_credential, $norsk_filter);

                    // file_put_contents(FCPATH . 'log1/norsk.txt', json_encode($norsk_rate), FILE_APPEND);


                    $api_service = isset($final_credential['service']) ? strtolower($final_credential['service']) : '';

                    if ($api_service != '') {
                        if (isset($norsk_rate['Quotes']) && is_array($norsk_rate['Quotes']) && count($norsk_rate['Quotes']) > 0) {
                            foreach ($norsk_rate['Quotes'] as $nkey => $nvalue) {
                                if (isset($nvalue['ServiceCode']) && strtolower($nvalue['ServiceCode']) == $api_service) {
                                    $norsk_res[$nkey] = $nvalue;
                                    $norsk_res[$nkey]['software_service'] = $vvalue['code'];
                                    $norsk_res[$nkey]['norsk_vendor_id'] = isset($vvalue['id']) ? $vvalue['id'] : '';
                                }
                            }
                        }
                    }
                }
            }



            if (isset($co_vendor_data) && is_array($co_vendor_data) && count($co_vendor_data) > 0) {
                $final_credential = array();
                foreach ($co_vendor_data as $vkey => $vvalue) {
                    $credential_data = $vvalue;
                    if (isset($credential_data) && is_array($credential_data) && count($credential_data) > 0) {
                        if ($credential_data['api_credentials'] != '') {
                            $credential_data = explode("\n", $credential_data['api_credentials']);


                            if (isset($credential_data) && is_array($credential_data) && count($credential_data) > 0) {
                                foreach ($credential_data as $ckey => $cvalue) {
                                    if ($cvalue != '') {
                                        $credential_arr = explode(":", $cvalue);
                                        $final_credential[strtolower(trim($credential_arr[0]))] = str_ireplace(array("\r", "\n", '\r', '\n'), "", $credential_arr[1]);
                                    }
                                }
                            }
                        }
                    }

                    //GET NORSK SERVICE
                    $norsk_filter = array(
                        'consignee_pincode' => isset($post_data['consignee_pincode']) ? $post_data['consignee_pincode'] : 0,
                        'consignee_city' => isset($post_data['consignee_city']) ? $post_data['consignee_city'] : 0,
                        'consignee_country' => isset($post_data['consignee_country']) ? $post_data['consignee_country'] : 0,
                        'shipment_value' => isset($post_data['shipment_value']) ? $post_data['shipment_value'] : 0,
                        'shipment_currency_id' => isset($post_data['shipment_currency_id']) ? $post_data['shipment_currency_id'] : 0,
                        'act_wt' =>  isset($post_data['act_wt']) ? $post_data['act_wt'] : '',
                        'char_wt' =>  isset($post_data['char_wt']) ? $post_data['char_wt'] : '',
                        'dim_box_count' =>  isset($post_data['dim_box_count']) ? $post_data['dim_box_count'] : '',
                        'dim_len' =>  isset($post_data['dim_len']) ? $post_data['dim_len'] : '',
                        'dim_wid' =>  isset($post_data['dim_wid']) ? $post_data['dim_wid'] : '',
                        'dim_hei' =>  isset($post_data['dim_hei']) ? $post_data['dim_hei'] : '',
                        'norsk_vendor_id' =>  isset($vvalue['id']) ? $vvalue['id'] : '',
                    );
                    $this->load->module('shipping_api/norsk_quote');
                    $norsk_rate = $this->norsk_quote->get_quote($final_credential, $norsk_filter);

                    // echo '<pre>';
                    // print_r($norsk_rate);

                    $api_service = isset($final_credential['service']) ? strtolower($final_credential['service']) : '';
                    if ($api_service != '') {
                        if (isset($norsk_rate['Quotes']) && is_array($norsk_rate['Quotes']) && count($norsk_rate['Quotes']) > 0) {
                            foreach ($norsk_rate['Quotes'] as $nkey => $nvalue) {
                                if (strtolower($nvalue['ServiceCode'] == $api_service)) {
                                    $norsk_res[$nkey] = $nvalue;
                                    $norsk_res[$nkey]['software_service'] = $vvalue['name'];
                                    $norsk_res[$nkey]['norsk_vendor_id'] = isset($vvalue['id']) ? $vvalue['id'] : '';
                                }
                            }
                        }
                    }
                }
            }
        }


        if (isset($post_data['docket']['customer_id']) && $post_data['docket']['customer_id'] > 0) {
            $credential_data['customer_id'] = $post_data['docket']['customer_id'];
            $qry = "SELECT c.id,m.config_key,m.config_value FROM customer c 
            JOIN module_setting m ON(c.id=m.module_id AND m.module_type=1) 
            WHERE c.id='" . $credential_data['customer_id'] . "' AND c.status IN(1,2) AND m.status IN(1,2)
            AND config_key='norsk_per'";
            $qry_exe = $this->db->query($qry);
            $norsk_per = $qry_exe->row_array();

            $qry = "SELECT c.id,m.config_key,m.config_value FROM customer c 
            JOIN module_setting m ON(c.id=m.module_id AND m.module_type=1) 
            WHERE c.id='" . $credential_data['customer_id'] . "' AND c.status IN(1,2) AND m.status IN(1,2)
            AND config_key='norsk_min_amount'";
            $qry_exe = $this->db->query($qry);
            $norsk_min_amount = $qry_exe->row_array();
        }


        if (isset($norsk_res) && is_array($norsk_res) && count($norsk_res) > 0) {
            foreach ($norsk_res as $no_key => $no_value) {
                $total_amt = 0;


                if (isset($no_value['Costs']) && is_array($no_value['Costs']) && count($no_value['Costs']) > 0) {
                    foreach ($no_value['Costs'] as $key => $value) {
                        $total_amt += $value['TotalCost'];
                    }
                }
                $norsk_rate_res[$no_key] = $no_value;
                $extra_amt = 0;
                if (isset($norsk_per['config_value']) && $norsk_per['config_value'] > 0) {
                    $extra_amt = ($total_amt * $norsk_per['config_value']) / 100;
                }

                if (isset($norsk_min_amount['config_value']) && $norsk_min_amount['config_value'] != '' && $norsk_min_amount['config_value'] > 0 && $extra_amt < $norsk_min_amount['config_value']) {
                    $extra_amt = $norsk_min_amount['config_value'];
                }
                $norsk_rate_res[$no_key]['total_amount'] = $total_amt + $extra_amt;
                $norsk_rate_res[$no_key]['total_amount'] = round($norsk_rate_res[$no_key]['total_amount'], 2);
            }
        }


        $data['norsk_rate'] = isset($norsk_rate_res) ? $norsk_rate_res : array();
        $data['get_data']['destination_id'] = $post_data['docket']['destination_id'];
        $data['get_data']['ch_weight'] = $post_data['docket']['chargeable_wt'];
        if ($post_data['docket_id'] > 0) {
            $data['docket_data'] = $this->gm->get_selected_record('docket', "id,vendor_id,nimbuspost_courier_id", array('id' => $post_data['docket_id'], 'status' => 1), array());
            $data['docket_extra_field'] = $this->gm->get_selected_record('docket_extra_field', "*", array('docket_id' => $post_data['docket_id'], 'status' => 1), array());
        }

        $dhl_service_id = array_search('dhl', $supported_vendor);
        $fedex_service_id = array_search('fedex', $supported_vendor);
        $in_express_service_id = array_search('in_express', $supported_vendor);

        $query = "SELECT v.id,v.label_head_id,v.api_credentials,v.code FROM vendor v 
            WHERE v.status IN(1,2) AND v.label_head_id='" . $dhl_service_id . "'";
        $query_exe = $this->db->query($query);
        $vendor_data = $query_exe->row_array();
        if (isset($vendor_data) && is_array($vendor_data) && count($vendor_data) > 0) {
            $data['dhl_vendor_id'] = isset($vendor_data['id']) ? $vendor_data['id'] : 0;
            $final_credential = array();
            if ($vendor_data['api_credentials'] != '') {
                $credential_data = explode("\n", $vendor_data['api_credentials']);
                if (isset($credential_data) && is_array($credential_data) && count($credential_data) > 0) {
                    foreach ($credential_data as $ckey => $cvalue) {
                        if ($cvalue != '') {
                            $credential_arr = explode(":", $cvalue);
                            $final_credential[strtolower(trim($credential_arr[0]))] = str_ireplace(array("\r", "\n", '\r', '\n'), "", $credential_arr[1]);
                        }
                    }
                }

                $this->load->module('shipping_api/dhl_label');
                $param_data['shipper_data'] = $post_data['shipper'];
                $param_data['consignee_data'] = $post_data['consignee'];
                $param_data['vendor_data'] = $post_data['docket'];
                $param_data['product_data'] = $this->gm->get_selected_record('product', 'id,name,code,pdt_format', $where = array('id' => $post_data['docket']['product_id'], 'status' => 1), array());
                $item_insert = array();
                if (isset($post_data['act_wt']) && is_array($post_data['act_wt']) && count($post_data['act_wt']) > 0) {
                    $box_sr_no = 1;
                    foreach ($post_data['act_wt'] as $key => $value) {

                        if (
                            $post_data['dim_box_count'][$key] > 0 ||
                            $value != '' || $post_data['dim_len'][$key] != '' || $post_data['dim_wid'][$key] != '' || $post_data['dim_hei'][$key] != ''
                        ) {
                            if ($post_data['dim_box_count'][$key] == '' || $post_data['dim_box_count'][$key] == 0) {
                                $post_data['dim_box_count'][$key] = 1;
                            }
                            if ($post_data['vol_wt'][$key] == '') {
                                $post_data['vol_wt'][$key] = 0;
                            }
                            if ($post_data['char_wt'][$key] == '') {
                                $post_data['char_wt'][$key] = 0;
                            }
                            for ($box = 1; $box <= $post_data['dim_box_count'][$key]; $box++) {
                                $line_actual_wt = $value;
                                $post_data['char_wt'][$key] = (float)$post_data['char_wt'][$key];
                                $post_data['vol_wt'][$key] = (float)$post_data['vol_wt'][$key];
                                $post_data['dim_box_count'][$key] = (int)$post_data['dim_box_count'][$key];
                                $item_insert[] = array(
                                    'docket_id' => 0,
                                    'parcel_no' =>  $post_data['docket']['awb_no'] . sprintf("%02d", $box_sr_no),
                                    'box_no' => $box_sr_no,
                                    'actual_wt' => $line_actual_wt,
                                    'item_length' => isset($post_data['dim_len'][$key]) ? (float)$post_data['dim_len'][$key] : 0,
                                    'item_width' => isset($post_data['dim_wid'][$key]) ? (float)$post_data['dim_wid'][$key] : 0,
                                    'item_height' => isset($post_data['dim_hei'][$key]) ? (float)$post_data['dim_hei'][$key] : 0,
                                    'box_count' => 1,
                                    'no_of_box' => $post_data['dim_box_count'][$key],
                                    'sr_no' => $key,
                                    'volumetric_wt' => isset($post_data['vol_wt'][$key]) ? round(($post_data['vol_wt'][$key] / $post_data['dim_box_count'][$key]), 2) : 0,
                                    'chargeable_wt' => isset($post_data['char_wt'][$key]) ? round(($post_data['char_wt'][$key] / $post_data['dim_box_count'][$key]), 2) : 0,
                                    'created_date' => date('Y-m-d H:i:s'),
                                    'created_by' => $this->user_id
                                );

                                $box_sr_no++;
                            }
                        }
                    }
                }
                $param_data['item_data'] = $item_insert;
                $sessiondata = $this->session->userdata('admin_user');

                $charge_arr =  $this->dhl_label->post_quote_ras(0, $final_credential, 1, $param_data);
                if (isset($charge_arr) && is_array($charge_arr) && count($charge_arr) > 0) {
                    $credential_data['customer_id'] = $post_data['docket']['customer_id'];
                    $qry = "SELECT c.id,m.config_key,m.config_value FROM customer c 
            JOIN module_setting m ON(c.id=m.module_id AND m.module_type=1) 
            WHERE c.id='" . $credential_data['customer_id'] . "' AND c.status IN(1,2) AND m.status IN(1,2)
            AND config_key='dhl_per'";
                    $qry_exe = $this->db->query($qry);
                    $dhl_per = $qry_exe->row_array();

                    $qry = "SELECT c.id,m.config_key,m.config_value FROM customer c 
            JOIN module_setting m ON(c.id=m.module_id AND m.module_type=1) 
            WHERE c.id='" . $credential_data['customer_id'] . "' AND c.status IN(1,2) AND m.status IN(1,2)
            AND config_key='dhl_min_amount'";
                    $qry_exe = $this->db->query($qry);
                    $dhl_min_amount = $qry_exe->row_array();

                    if (isset($charge_arr) && is_array($charge_arr) && count($charge_arr) > 0) {
                        $total_amt = 0;
                        if (isset($charge_arr) && is_array($charge_arr) && count($charge_arr) > 0) {
                            foreach ($charge_arr as $ckey => $cvalue) {
                                $charge_name = explode(":", $cvalue);
                                if (isset($charge_name) && is_array($charge_name) && count($charge_name) > 0) {
                                    $chargeInsertData["charge"][$charge_name[0]] = $charge_name[1];
                                    $total_amt += (float)$charge_name[1];
                                }
                            }
                        }


                        $extra_amt = 0;
                        if (isset($dhl_per['config_value']) && $dhl_per['config_value'] > 0) {
                            $extra_amt = ($total_amt * $dhl_per['config_value']) / 100;
                        }

                        if (isset($dhl_min_amount['config_value']) && $dhl_min_amount['config_value'] != '' && $dhl_min_amount['config_value'] > 0 && $extra_amt < $dhl_min_amount['config_value']) {
                            $extra_amt = $dhl_min_amount['config_value'];
                        }
                        $dhl_rate_res[$no_key]['total_amount'] = $total_amt + $extra_amt;
                        $dhl_rate_res[$no_key]['total_amount'] = round($dhl_rate_res[$no_key]['total_amount'], 2);
                        $data['dhl_rate'][] = isset($dhl_rate_res) ? $dhl_rate_res : array();
                    }
                }
            }



            $query = "SELECT v.id,v.label_head_id,v.api_credentials,v.code FROM vendor v 
            WHERE v.status IN(1,2) AND v.label_head_id='" . $fedex_service_id . "'";
            $query_exe = $this->db->query($query);
            $fedex_data = $query_exe->result_array();
            if (isset($fedex_data) && is_array($fedex_data) && count($fedex_data) > 0) {
                foreach ($fedex_data as $fkey => $vendor_data) {
                    $fedex_rate_res = array();

                    $final_credential = array();
                    if ($vendor_data['api_credentials'] != '') {
                        $vendor_data['api_credentials'] = stripslashes(html_entity_decode($vendor_data['api_credentials']));
                        $vendor_data['api_credentials'] = json_decode($vendor_data['api_credentials'], TRUE);

                        $final_credential_wt = $this->choose_credentials_by_weight($vendor_data['api_credentials'], $post_data["docket"]["chargeable_wt"]);
                        $sessiondata = $this->session->userdata('admin_user');

                        $credential_data = explode("\n", $final_credential_wt);
                        if (isset($credential_data) && is_array($credential_data) && count($credential_data) > 0) {
                            foreach ($credential_data as $ckey => $cvalue) {

                                if ($cvalue != '') {

                                    $credential_arr = explode(":", $cvalue);

                                    $final_credential[strtolower(trim($credential_arr[0]))] = str_ireplace(array("\r", "\n", '\r', '\n'), "", $credential_arr[1]);
                                }
                            }
                        }

                        $this->load->module('shipping_api/fedex_label');
                        $param_data['shipper_data'] = $post_data['shipper'];
                        $param_data['consignee_data'] = $post_data['consignee'];
                        $param_data['vendor_data'] = $post_data['docket'];
                        $param_data['product_data'] = $this->gm->get_selected_record('product', 'id,name,code,pdt_format', $where = array('id' => $post_data['docket']['product_id'], 'status' => 1), array());
                        $item_insert = array();
                        if (isset($post_data['act_wt']) && is_array($post_data['act_wt']) && count($post_data['act_wt']) > 0) {
                            $box_sr_no = 1;
                            foreach ($post_data['act_wt'] as $key => $value) {

                                if (
                                    $post_data['dim_box_count'][$key] > 0 ||
                                    $value != '' || $post_data['dim_len'][$key] != '' || $post_data['dim_wid'][$key] != '' || $post_data['dim_hei'][$key] != ''
                                ) {
                                    if ($post_data['dim_box_count'][$key] == '' || $post_data['dim_box_count'][$key] == 0) {
                                        $post_data['dim_box_count'][$key] = 1;
                                    }
                                    if ($post_data['vol_wt'][$key] == '') {
                                        $post_data['vol_wt'][$key] = 0;
                                    }
                                    if ($post_data['char_wt'][$key] == '') {
                                        $post_data['char_wt'][$key] = 0;
                                    }
                                    for ($box = 1; $box <= $post_data['dim_box_count'][$key]; $box++) {
                                        $line_actual_wt = $value;
                                        $post_data['char_wt'][$key] = (float)$post_data['char_wt'][$key];
                                        $post_data['vol_wt'][$key] = (float)$post_data['vol_wt'][$key];
                                        $post_data['dim_box_count'][$key] = (int)$post_data['dim_box_count'][$key];
                                        $item_insert[] = array(
                                            'docket_id' => 0,
                                            'parcel_no' =>  $post_data['docket']['awb_no'] . sprintf("%02d", $box_sr_no),
                                            'box_no' => $box_sr_no,
                                            'actual_wt' => $line_actual_wt,
                                            'item_length' => isset($post_data['dim_len'][$key]) ? (float)$post_data['dim_len'][$key] : 0,
                                            'item_width' => isset($post_data['dim_wid'][$key]) ? (float)$post_data['dim_wid'][$key] : 0,
                                            'item_height' => isset($post_data['dim_hei'][$key]) ? (float)$post_data['dim_hei'][$key] : 0,
                                            'box_count' => 1,
                                            'no_of_box' => $post_data['dim_box_count'][$key],
                                            'sr_no' => $key,
                                            'volumetric_wt' => isset($post_data['vol_wt'][$key]) ? round(($post_data['vol_wt'][$key] / $post_data['dim_box_count'][$key]), 2) : 0,
                                            'chargeable_wt' => isset($post_data['char_wt'][$key]) ? round(($post_data['char_wt'][$key] / $post_data['dim_box_count'][$key]), 2) : 0,
                                            'created_date' => date('Y-m-d H:i:s'),
                                            'created_by' => $this->user_id
                                        );

                                        $box_sr_no++;
                                    }
                                }
                            }
                        }
                        $param_data['item_data'] = $item_insert;
                        $sessiondata = $this->session->userdata('admin_user');
                        // if ($sessiondata["email"] == "virag@itdservices.in") {
                        //     echo '<pre>';
                        //     print_r($param_data);
                        //     exit;
                        // }
                        $charge_arr =  $this->fedex_label->post_quote_ras(0, $final_credential, 1, $param_data);

                        if (isset($charge_arr) && is_array($charge_arr) && count($charge_arr) > 0) {
                            $credential_data['customer_id'] = $post_data['docket']['customer_id'];
                            $qry = "SELECT c.id,m.config_key,m.config_value FROM customer c 
            JOIN module_setting m ON(c.id=m.module_id AND m.module_type=1) 
            WHERE c.id='" . $credential_data['customer_id'] . "' AND c.status IN(1,2) AND m.status IN(1,2)
            AND config_key='fedex_per'";
                            $qry_exe = $this->db->query($qry);
                            $fedex_per = $qry_exe->row_array();

                            $qry = "SELECT c.id,m.config_key,m.config_value FROM customer c 
            JOIN module_setting m ON(c.id=m.module_id AND m.module_type=1) 
            WHERE c.id='" . $credential_data['customer_id'] . "' AND c.status IN(1,2) AND m.status IN(1,2)
            AND config_key='fedex_min_amount'";
                            $qry_exe = $this->db->query($qry);
                            $fedex_min_amount = $qry_exe->row_array();

                            if (isset($charge_arr) && is_array($charge_arr) && count($charge_arr) > 0) {
                                $total_amt = 0;
                                foreach ($charge_arr as $ckey => $cvalue) {
                                    $charge_name = explode(":", $cvalue);

                                    if (isset($charge_name) && is_array($charge_name) && count($charge_name) > 0) {
                                        if (strtolower($charge_name[0]) == 'fuel') {
                                            $data['fedex_rate'][$fkey]['fsc_amount'] = (float)$charge_name[1];
                                        }
                                        $total_amt += (float)$charge_name[1];
                                    }
                                }


                                $extra_amt = 0;
                                if (isset($fedex_per['config_value']) && $fedex_per['config_value'] > 0) {
                                    $extra_amt = ($total_amt * $fedex_per['config_value']) / 100;
                                }

                                if (isset($fedex_min_amount['config_value']) && $fedex_min_amount['config_value'] != '' && $fedex_min_amount['config_value'] > 0 && $extra_amt < $fedex_min_amount['config_value']) {
                                    $extra_amt = $fedex_min_amount['config_value'];
                                }
                                $data['fedex_rate'][$fkey]['total_amount'] = $total_amt + $extra_amt;
                                $data['fedex_rate'][$fkey]['software_service'] = $vendor_data['code'];
                                $data['fedex_rate'][$fkey]['fedex_vendor_id'] = isset($vendor_data['id']) ? $vendor_data['id'] : 0;
                            }
                        }
                    }
                }
            }
        }

        $all_company = get_all_billing_company();
        $data['company_tax_type'] = isset($all_company[$post_data['docket']['company_id']]) ? $all_company[$post_data['docket']['company_id']]['tax_type'] : 1;


        echo $this->load->view('service_rate_list', $data);
    }

    public function check_credit_debit_note()
    {
        /*
         * check ajax request
         */
        if ($this->input->is_ajax_request()) {
            $note_no = $this->input->post('note_no');
            $id = $this->input->post('id');
            $where = "";
            if (isset($id) && $id != '') {
                $where .= " AND id != " . $id;
            }
            if ($note_no != '') {
                $query = "SELECT id FROM credit_debit_note WHERE status IN(1,2) AND note_no = '" . $note_no . "'" . $where;
                $query_exec = $this->db->query($query);
                $ans = $query_exec->row_array();
            }

            //if setting is on then only check gst no for duplicate
            if (isset($ans) && is_array($ans) && count($ans) > 0) {
                echo "NOTE_EXISTS";
            }
        } else {
            http_response_code(500);
        }
    }

    function check_opening_note_limit($post_data = array())
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');

        if (isset($post_data) && is_array($post_data) && count($post_data) > 0) {
            $post = $post_data;
        } else {
            $post = $this->input->post();
        }
        $all_cust_opening = get_all_customer_opening_balance_date();
        if (isset($post) && is_array($post) && count($post) > 0) {
            $customer_id = isset($post['customer_id']) ? $post['customer_id'] : 0;

            // $all_cust = get_all_customer(" AND id=" . $customer_id);
            // $all_currency = get_all_currency();

            $curreny_name = get_customer_currency($customer_id);

            if ($customer_id > 0) {
                $error_data = array();
                $customer_data = $this->gm->get_selected_record('customer', 'id,credit_limit,aging_limit', $where = array('id' => $customer_id, 'status' => 1), array());
                if (isset($customer_data['aging_limit']) && $customer_data['aging_limit'] != '' && (float)$customer_data['aging_limit'] > 0) {
                    //GET DEBIT OPENING BALANCE
                    $qry = "SELECT * FROM opening_balance WHERE status=1 AND customer_id='" . $customer_id . "'
                AND payment_type=2 AND DATEDIFF('" . date('Y-m-d') . "', `opening_date`) > " . $customer_data['aging_limit'];

                    $account_setting = get_all_app_setting(" AND module_name IN('account')");
                    if (
                        isset($account_setting['customised_unbilled_dockets_in_leder_and_outstanding_report']) && $account_setting['customised_unbilled_dockets_in_leder_and_outstanding_report'] == 1
                        && isset($account_setting['account_ledger_start_date']) && $account_setting['account_ledger_start_date'] != '' && $account_setting['account_ledger_start_date'] != '1970-01-01' && $account_setting['account_ledger_start_date'] != '0000-00-00'
                    ) {
                        $qry .= " AND opening_date >='" . $account_setting['account_ledger_start_date'] . "'";
                    } else {
                        $account_ledger_start_date = get_customer_opening_balance_date($customer_id);
                        if ($account_ledger_start_date != '') {
                            $qry .= " AND opening_date >='" . $account_ledger_start_date . "'";
                        }
                    }

                    $query_exec = $this->db->query($qry);
                    $opening_data = $query_exec->result_array();

                    if (isset($opening_data) && is_array($opening_data) && count($opening_data) > 0) {
                        foreach ($opening_data as $okey => $ovalue) {
                            if ($ovalue['opening_amount'] > 0) {
                                $total_received = 0;
                                $include_invoice_amt =  get_include_in_data($ovalue['id'], 1, 1);
                                if (isset($include_invoice_amt) && is_array($include_invoice_amt) && count($include_invoice_amt) > 0) {
                                    foreach ($include_invoice_amt as $amt_key => $amt_value) {
                                        foreach ($amt_value as $in_key => $in_value) {
                                            $total_received += $in_value['deduction_amt'] + $in_value['tds_amt'] + $in_value['received_amt'];
                                        }
                                    }
                                }

                                if ($ovalue['opening_amount'] > $total_received) {
                                    $balance_amt = $ovalue['opening_amount'] - $total_received;
                                    if ($balance_amt > 0) {
                                        $due_date = date('Y-m-d', strtotime($ovalue['opening_date'] . ' + ' . $customer_data['aging_limit'] . ' day'));
                                        $error_data[] = "Opening Balance for " . $curreny_name . " " . round($balance_amt) . " due on " . date('d/m/Y', strtotime($due_date));
                                    }
                                }
                            }
                        }
                    }



                    //GET DEBIT NOTE
                    $qry = "SELECT * FROM credit_debit_note WHERE status=1 AND customer_id='" . $customer_id . "'
                AND note_category=2 AND DATEDIFF('" . date('Y-m-d') . "', `note_date`) > " . $customer_data['aging_limit'];
                    if (
                        isset($account_setting['customised_unbilled_dockets_in_leder_and_outstanding_report']) && $account_setting['customised_unbilled_dockets_in_leder_and_outstanding_report'] == 1
                        && isset($account_setting['account_ledger_start_date']) && $account_setting['account_ledger_start_date'] != '' && $account_setting['account_ledger_start_date'] != '1970-01-01' && $account_setting['account_ledger_start_date'] != '0000-00-00'
                    ) {
                        $qry .= " AND note_date >='" . $account_setting['account_ledger_start_date'] . "'";
                    } else {
                        $account_ledger_start_date = get_customer_opening_balance_date($customer_id);
                        if ($account_ledger_start_date != '') {
                            $qry .= " AND note_date >='" . $account_ledger_start_date . "'";
                        }
                    }
                    $query_exec = $this->db->query($qry);
                    $note_data = $query_exec->result_array();

                    if (isset($note_data) && is_array($note_data) && count($note_data) > 0) {
                        foreach ($note_data as $okey => $ovalue) {
                            if ($ovalue['grand_total_amount'] > 0) {
                                $total_received = 0;
                                $include_invoice_amt =  get_include_in_data($ovalue['id'], 2, 1);

                                if (isset($include_invoice_amt) && is_array($include_invoice_amt) && count($include_invoice_amt) > 0) {
                                    foreach ($include_invoice_amt as $amt_key => $amt_value) {
                                        foreach ($amt_value as $in_key => $in_value) {
                                            $total_received += $in_value['deduction_amt'] + $in_value['tds_amt'] + $in_value['received_amt'];
                                        }
                                    }
                                }

                                if ($ovalue['grand_total_amount'] > $total_received) {

                                    $balance_amt = $ovalue['grand_total_amount'] - $total_received;
                                    if ($balance_amt > 0) {
                                        $due_date = date('Y-m-d', strtotime($ovalue['note_date'] . ' + ' . $customer_data['aging_limit'] . ' day'));
                                        $error_data[] = "Debit Note " . $ovalue['note_no'] . " for " . $curreny_name . " " . round($balance_amt) . " due on " . date('d/m/Y', strtotime($due_date));
                                    }
                                }
                            }
                        }
                    }



                    //CHECK INVOICE
                    $invoiceq = "SELECT id,invoice_date,grand_total,invoice_no,customer_id,company_master_id FROM `docket_invoice` WHERE customer_id='" . $customer_id . "'
                    AND status IN(1,2) AND payment_received=2 AND DATEDIFF('" . date('Y-m-d') . "', `invoice_date`) > " . $customer_data['aging_limit'];
                    if (
                        isset($account_setting['customised_unbilled_dockets_in_leder_and_outstanding_report']) && $account_setting['customised_unbilled_dockets_in_leder_and_outstanding_report'] == 1
                        && isset($account_setting['account_ledger_start_date']) && $account_setting['account_ledger_start_date'] != '' && $account_setting['account_ledger_start_date'] != '1970-01-01' && $account_setting['account_ledger_start_date'] != '0000-00-00'
                    ) {
                        $invoiceq .= " AND invoice_date >='" . $account_setting['account_ledger_start_date'] . "'";
                    } else {
                        $account_ledger_start_date = get_customer_opening_balance_date($customer_id);
                        if ($account_ledger_start_date != '') {
                            $invoiceq .= " AND invoice_date >='" . $account_ledger_start_date . "'";
                        }
                    }
                    $invoiceq_exe = $this->db->query($invoiceq);
                    $invoicer_res  = $invoiceq_exe->result_array();
                    if (isset($invoicer_res) && is_array($invoicer_res) && count($invoicer_res) > 0) {

                        foreach ($invoicer_res as $ikey => $ivalue) {

                            $total_received = 0;
                            $include_invoice_amt =  get_include_in_data($ivalue['id'], 3, 1);

                            if (isset($include_invoice_amt) && is_array($include_invoice_amt) && count($include_invoice_amt) > 0) {
                                foreach ($include_invoice_amt as $amt_key => $amt_value) {
                                    foreach ($amt_value as $in_key => $in_value) {
                                        $total_received += $in_value['deduction_amt'] + $in_value['tds_amt'] + $in_value['received_amt'];
                                    }
                                }
                            }

                            if ($ivalue['grand_total'] - $total_received > 0) {
                                $due_date = date('Y-m-d', strtotime($ivalue['invoice_date'] . ' + ' . $customer_data['aging_limit'] . ' day'));



                                $error_data[] = "Invoice number " . $ivalue['invoice_no'] . " for " . $curreny_name . " " . round($ivalue['grand_total'] - $total_received) . " due on " . date('d/m/Y', strtotime($due_date));
                            }
                        }
                    }
                }
            }
        }

        if (isset($error_data) && is_array($error_data) && count($error_data) > 0) {
            $respoonse['error'] = $error_data;
        } else {
            $respoonse['success'] = "SUCCESS";
        }
        if (isset($post_data) && is_array($post_data) && count($post_data) > 0) {
            return $respoonse;
        } else {
            echo json_encode($respoonse);
        }
    }


    function get_aging_due_data($post_data = array())
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');

        if (isset($post_data) && is_array($post_data) && count($post_data) > 0) {
            $post = $post_data;
        } else {
            $post = $this->input->post();
        }
        $result = array();
        if (isset($post) && is_array($post) && count($post) > 0) {
            $customer_id = isset($post['customer_id']) ? $post['customer_id'] : 0;
            if ($customer_id > 0) {

                $customer_data = $this->gm->get_selected_record('customer', 'id,credit_limit,aging_limit', $where = array('id' => $customer_id, 'status' => 1), array());
                if (isset($customer_data['aging_limit']) && $customer_data['aging_limit'] != '' && (float)$customer_data['aging_limit'] > 0) {
                    //GET DEBIT OPENING BALANCE
                    $qry = "SELECT * FROM opening_balance WHERE status=1 AND customer_id='" . $customer_id . "'
                AND payment_type=2 AND (DATEDIFF('" . date('Y-m-d') . "', `opening_date`) -" . $customer_data['aging_limit'] . " =1 OR DATEDIFF('" . date('Y-m-d') . "', `opening_date`)  > " . $customer_data['aging_limit'] . ") ";

                    $account_setting = get_all_app_setting(" AND module_name IN('account')");
                    if (
                        isset($account_setting['customised_unbilled_dockets_in_leder_and_outstanding_report']) && $account_setting['customised_unbilled_dockets_in_leder_and_outstanding_report'] == 1
                        && isset($account_setting['account_ledger_start_date']) && $account_setting['account_ledger_start_date'] != '' && $account_setting['account_ledger_start_date'] != '1970-01-01' && $account_setting['account_ledger_start_date'] != '0000-00-00'
                    ) {
                        $qry .= " AND opening_date >='" . $account_setting['account_ledger_start_date'] . "'";
                    } else {
                        $account_ledger_start_date = get_customer_opening_balance_date($customer_id);
                        if ($account_ledger_start_date != '') {
                            $qry .= " AND opening_date >='" . $account_ledger_start_date . "'";
                        }
                    }

                    $query_exec = $this->db->query($qry);
                    $opening_data = $query_exec->result_array();

                    if (isset($opening_data) && is_array($opening_data) && count($opening_data) > 0) {
                        foreach ($opening_data as $okey => $ovalue) {
                            if ($ovalue['opening_amount'] > 0) {
                                $total_received = 0;
                                $include_invoice_amt =  get_include_in_data($ovalue['id'], 1, 1);
                                if (isset($include_invoice_amt) && is_array($include_invoice_amt) && count($include_invoice_amt) > 0) {
                                    foreach ($include_invoice_amt as $amt_key => $amt_value) {
                                        foreach ($amt_value as $in_key => $in_value) {
                                            $total_received += $in_value['deduction_amt'] + $in_value['tds_amt'] + $in_value['received_amt'];
                                        }
                                    }
                                }

                                if ($ovalue['opening_amount'] > $total_received) {
                                    $balance_amt = $ovalue['opening_amount'] - $total_received;
                                    if ($balance_amt > 0) {
                                        $due_date = date('Y-m-d', strtotime($ovalue['opening_date'] . ' + ' . $customer_data['aging_limit'] . ' day'));
                                        $result[] = array(
                                            'id' => $ovalue['id'],
                                            'ledger_date' => $ovalue['opening_date'],
                                            'invoice_no' => '',
                                            'type' => 'Opening Balance',
                                            'total_amt' => $ovalue['opening_amount'],
                                            'paid_amt' => $total_received,
                                            'due_date' => $due_date
                                        );
                                    }
                                }
                            }
                        }
                    }



                    //GET DEBIT NOTE
                    $qry = "SELECT * FROM credit_debit_note WHERE status=1 AND customer_id='" . $customer_id . "'
                AND note_category=2 AND (DATEDIFF('" . date('Y-m-d') . "', `note_date`)-" . $customer_data['aging_limit'] . "=1" .
                        " OR DATEDIFF('" . date('Y-m-d') . "', `note_date`) > " . $customer_data['aging_limit'] . ")";
                    if (
                        isset($account_setting['customised_unbilled_dockets_in_leder_and_outstanding_report']) && $account_setting['customised_unbilled_dockets_in_leder_and_outstanding_report'] == 1
                        && isset($account_setting['account_ledger_start_date']) && $account_setting['account_ledger_start_date'] != '' && $account_setting['account_ledger_start_date'] != '1970-01-01' && $account_setting['account_ledger_start_date'] != '0000-00-00'
                    ) {
                        $qry .= " AND note_date >='" . $account_setting['account_ledger_start_date'] . "'";
                    } else {
                        $account_ledger_start_date = get_customer_opening_balance_date($customer_id);
                        if ($account_ledger_start_date != '') {
                            $qry .= " AND note_date >='" . $account_ledger_start_date . "'";
                        }
                    }
                    $query_exec = $this->db->query($qry);
                    $note_data = $query_exec->result_array();

                    if (isset($note_data) && is_array($note_data) && count($note_data) > 0) {
                        foreach ($note_data as $okey => $ovalue) {
                            if ($ovalue['grand_total_amount'] > 0) {
                                $total_received = 0;
                                $include_invoice_amt =  get_include_in_data($ovalue['id'], 2, 1);

                                if (isset($include_invoice_amt) && is_array($include_invoice_amt) && count($include_invoice_amt) > 0) {
                                    foreach ($include_invoice_amt as $amt_key => $amt_value) {
                                        foreach ($amt_value as $in_key => $in_value) {
                                            $total_received += $in_value['deduction_amt'] + $in_value['tds_amt'] + $in_value['received_amt'];
                                        }
                                    }
                                }

                                if ($ovalue['grand_total_amount'] > $total_received) {

                                    $balance_amt = $ovalue['grand_total_amount'] - $total_received;
                                    if ($balance_amt > 0) {
                                        $due_date = date('Y-m-d', strtotime($ovalue['note_date'] . ' + ' . $customer_data['aging_limit'] . ' day'));
                                        $result[] = array(
                                            'id' => $ovalue['id'],
                                            'ledger_date' => $ovalue['note_date'],
                                            'invoice_no' => $ovalue['note_no'],
                                            'type' => 'Debit Note',
                                            'total_amt' => $ovalue['grand_total_amount'],
                                            'paid_amt' => $total_received,
                                            'due_date' => $due_date
                                        );
                                    }
                                }
                            }
                        }
                    }


                    //CHECK INVOICE
                    $invoiceq = "SELECT id,invoice_date,grand_total,invoice_no FROM `docket_invoice` WHERE customer_id='" . $customer_id . "'
                    AND status IN(1,2) AND payment_received=2 AND (DATEDIFF('" . date('Y-m-d') . "', `invoice_date`) =" . $customer_data['aging_limit'] . " =1 "
                        . "OR DATEDIFF('" . date('Y-m-d') . "', `invoice_date`) > " . $customer_data['aging_limit'] . ")";
                    if (
                        isset($account_setting['customised_unbilled_dockets_in_leder_and_outstanding_report']) && $account_setting['customised_unbilled_dockets_in_leder_and_outstanding_report'] == 1
                        && isset($account_setting['account_ledger_start_date']) && $account_setting['account_ledger_start_date'] != '' && $account_setting['account_ledger_start_date'] != '1970-01-01' && $account_setting['account_ledger_start_date'] != '0000-00-00'
                    ) {
                        $invoiceq .= " AND invoice_date >='" . $account_setting['account_ledger_start_date'] . "'";
                    } else {
                        $account_ledger_start_date = get_customer_opening_balance_date($customer_id);
                        if ($account_ledger_start_date != '') {
                            $invoiceq .= " AND invoice_date >='" . $account_ledger_start_date . "'";
                        }
                    }
                    $invoiceq_exe = $this->db->query($invoiceq);
                    $invoicer_res  = $invoiceq_exe->result_array();
                    if (isset($invoicer_res) && is_array($invoicer_res) && count($invoicer_res) > 0) {

                        foreach ($invoicer_res as $ikey => $ivalue) {

                            $total_received = 0;
                            $include_invoice_amt =  get_include_in_data($ivalue['id'], 3, 1);

                            if (isset($include_invoice_amt) && is_array($include_invoice_amt) && count($include_invoice_amt) > 0) {
                                foreach ($include_invoice_amt as $amt_key => $amt_value) {
                                    foreach ($amt_value as $in_key => $in_value) {
                                        $total_received += $in_value['deduction_amt'] + $in_value['tds_amt'] + $in_value['received_amt'];
                                    }
                                }
                            }

                            if ($ivalue['grand_total'] - $total_received > 0) {
                                $due_date = date('Y-m-d', strtotime($ivalue['invoice_date'] . ' + ' . $customer_data['aging_limit'] . ' day'));
                                $result[] = array(
                                    'id' => $ivalue['id'],
                                    'ledger_date' => $ivalue['invoice_date'],
                                    'invoice_no' => $ivalue['invoice_no'],
                                    'type' => 'Invoice',
                                    'total_amt' => $ivalue['grand_total'],
                                    'paid_amt' => $total_received,
                                    'due_date' => $due_date
                                );
                            }
                        }
                    }
                }
            }
        }


        if (isset($post_data) && is_array($post_data) && count($post_data) > 0) {
            return $result;
        } else {
            echo json_encode($result);
        }
    }

    function round_half_number($chargeable_wt_total = '')
    {
        $decimal_amt = 0;
        $on_add = "0.5";
        $decimal_arr = explode(".", $on_add);

        if (isset($decimal_arr[1]) && $decimal_arr[1] != '') {
            $decimal_amt = $decimal_arr[1];
            if (strlen($decimal_arr[1]) == 1) {
                $decimal_amt = $decimal_arr[1] . '0';
            }
        }
        $round_ch_wt = $chargeable_wt_total;


        $charge_decimal_arr = explode(".", $chargeable_wt_total);


        if (isset($charge_decimal_arr[1]) && $charge_decimal_arr[1] != '') {
            $chare_decimal_amt = $charge_decimal_arr[1];

            if (strlen($charge_decimal_arr[1]) == 1) {
                $chare_decimal_amt = $charge_decimal_arr[1] . '0';
            }
        }

        if (isset($chare_decimal_amt) && $decimal_amt != 0) {


            $charge_remain = ($chare_decimal_amt % $decimal_amt);


            if ($charge_remain > 0) {
                $decimal_to_add = $decimal_amt - $charge_remain;
                $chare_decimal_amt = $chare_decimal_amt + $decimal_to_add;
                $chare_decimal_amt = $chare_decimal_amt / 100;
                $round_ch_wt = $charge_decimal_arr[0] + $chare_decimal_amt;
            } else {
                $round_ch_wt = $chargeable_wt_total;
            }

            $round_ch_wt = round($round_ch_wt, 2);
        } else {
            $round_ch_wt = ceil($chargeable_wt_total);
        }

        return $round_ch_wt;
    }

    function check_ticket_sub_type_setting()
    {
        $is_mandate = 2;
        $ticket_sub_type = $this->input->post('sub_type_id');
        if ($ticket_sub_type > 0) {
            $query = "SELECT id,mandate_invoice_awb FROM ticket_sub_type WHERE id='" . $ticket_sub_type . "' AND status IN(1,2)";
            $query_exec = $this->db->query($query);
            $result = $query_exec->row_array();

            $is_mandate  = isset($result['mandate_invoice_awb']) ? $result['mandate_invoice_awb'] : 2;
        }
        echo $is_mandate;
    }

    public function get_reference_no()
    {
        $result = array();
        $customer_id = $this->input->post('customer_id');
        $sheet_id = $this->input->post('sheet_id');

        if ($customer_id != "" && $sheet_id != "") {
            $query = "SELECT id,ref_no FROM pickup_request WHERE STATUS = 1 AND customer_id='" . $customer_id . "' AND pickup_sheet_id='" . $sheet_id . "'";
            $query_exec = $this->db->query($query);
            $result = $query_exec->result_array();
            $options = '<option value="">Select...</option>';

            if (isset($result) && is_array($result) && count($result) > 0) {
                foreach ($result as $gkey => $gvalue) {
                    if (isset($response_data['ref_no']) && $response_data['ref_no'] == $gvalue['id']) {
                        $options .= "<option value=" . $gvalue['ref_no'] . " selected >" . $gvalue['ref_no'] . "</option>";
                    } else {
                        $options .= "<option value=" . $gvalue['ref_no'] . ">" . $gvalue['ref_no'] . "</option>";
                    }
                }
            }
            if (isset($post) && is_array($post) && count($post) > 0) {
                return $options;
            } else {
                // echo json_encode($response_data);
                echo $options;
            }
        }
    }

    public function get_pincode_master_data()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');

        $pincode = $this->input->post("pincode");
        $country = $this->input->post("country");
        $response['status'] = 'failed';
        if ($pincode != '' && $country != '') {
            $qry = "SELECT * FROM pincode WHERE status IN(1,2) AND name='" . $pincode . "'
            AND country_id='" . $country . "'";

            $query_exec = $this->db->query($qry);
            $result = $query_exec->row_array();

            if (isset($result) && is_array($result) && count($result) > 0) {
                $all_city = get_all_city();
                $all_state = get_all_state();
                $all_country = get_all_country();
                $response['status'] = 'success';
                $response['city'] = isset($all_city[$result['city_id']]) ? $all_city[$result['city_id']]['name'] : '';
                $response['state'] = isset($all_state[$result['state_id']]) ? $all_state[$result['state_id']]['name'] : '';
                $response['country_id'] = $result['country_id'];
                $response['country_code'] = isset($all_country[$result['country_id']]) ? $all_country[$result['country_id']]['code'] : '';
                $response['country_name'] = isset($all_country[$result['country_id']]) ? $all_country[$result['country_id']]['name'] : '';
            }
        }

        echo json_encode($response);
    }

    public function get_pickup_customer_id()
    {
        $result = array();
        $sheet_id = $this->input->post('sheet_id');

        if ($sheet_id != "") {
            $query = "SELECT customer_id FROM pickup_request WHERE STATUS = 1 AND pickup_sheet_id='" . $sheet_id . "'";
            $query_exec = $this->db->query($query);
            $result = $query_exec->result_array();
            $options = '<option value="">Select...</option>';

            if (isset($result) && is_array($result) && count($result) > 0) {
                foreach ($result as $gkey => $gvalue) {
                    $pick_cust_id_arry[$gvalue['customer_id']] = $gvalue['customer_id'];
                }

                if (isset($pick_cust_id_arry) && is_array($pick_cust_id_arry) && count($pick_cust_id_arry) > 0) {
                    $picked_up_customer = get_all_customer(" AND status=1 AND id IN(" . implode(",", $pick_cust_id_arry) . ")");
                }

                if (isset($picked_up_customer) && is_array($picked_up_customer) && count($picked_up_customer) > 0) {
                    foreach ($picked_up_customer as $puckey => $pucvalue) {
                        if (isset($response_data['id']) && $response_data['id'] == $pucvalue['id']) {
                            $options .= "<option value=" . $pucvalue['id'] . " selected >" . $pucvalue['code'] . "</option>";
                        } else {
                            $options .= "<option value=" . $pucvalue['id'] . ">" . $pucvalue['code'] . "</option>";
                        }
                    }
                }
            }
            if (isset($post) && is_array($post) && count($post) > 0) {
                return $options;
            } else {
                // echo json_encode($response_data);
                echo $options;
            }
        }
    }

    function get_doc_type_for_pk()
    {
        $response_data = array();
        $post_data = $this->input->get();
        $CI = &get_instance();
        $main_db = $CI->load->database('main_db', true);
        $qry = "SELECT id,name,docket_doc_name FROM document_type WHERE status IN(1) AND name = 'PNIC NUMBER'";
        $qry_exe = $main_db->query($qry);
        $result1 = $qry_exe->result_array();
        if (count($result1) > 0) {
            $response_data  = $qry_exe->result_array();
        }
        echo json_encode($response_data);
    }

    function get_doc_type_for_ae()
    {
        $response_data = array();
        $post_data = $this->input->get();
        $CI = &get_instance();
        $main_db = $CI->load->database('main_db', true);
        $qry = "SELECT id,name,docket_doc_name FROM document_type WHERE status IN(1) AND name = 'TRN NO'";
        $qry_exe = $main_db->query($qry);
        $result1 = $qry_exe->result_array();
        if (count($result1) > 0) {
            $response_data  = $qry_exe->result_array();
        }
        echo json_encode($response_data);
    }

    function get_tat_contract($docket_data = array())
    {
        $this->load->helper('frontend_common');
        $response_data = array();
        $final_res = array();
        if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
            $post_data = $docket_data;
        } else {
            $post_data = $this->input->post();
        }

        $all_vendor_id = array();
        if (isset($post_data) && is_array($post_data) && count($post_data) > 0) {

            $booking_date = date('Y-m-d', strtotime(str_replace("/", "-", $post_data['booking_date'])));
            $post_data['customer_id'] = $post_data['customer_id'] > 0 ? $post_data['customer_id'] : 'NULL';
            $post_data['vendor_id'] = $post_data['vendor_id'] > 0 ? $post_data['vendor_id'] : 0;
            $post_data['co_vendor_id'] = $post_data['co_vendor_id'] > 0 ? $post_data['co_vendor_id'] : 0;
            $post_data['product_id'] = $post_data['product_id'] > 0 ? $post_data['product_id'] : 'NULL';
            $post_data['origin_id'] = $post_data['origin_id'] > 0 ? $post_data['origin_id'] : 'NULL';
            $post_data['destination_id'] = $post_data['destination_id'] > 0 ? $post_data['destination_id'] : 'NULL';
            $post_data['ori_zone_id'] = $post_data['ori_zone_id'] > 0 ? $post_data['ori_zone_id'] : 'NULL';
            $post_data['dest_zone_id'] = $post_data['dest_zone_id'] > 0 ? $post_data['dest_zone_id'] : 'NULL';
            $post_data['ori_hub_id'] = $post_data['ori_hub_id'] > 0 ? $post_data['ori_hub_id'] : 'NULL';
            $post_data['dest_hub_id'] = $post_data['dest_hub_id'] > 0 ? $post_data['dest_hub_id'] : 'NULL';


            if ($post_data['co_vendor_id'] == 0) {
                $setting_data = get_all_app_setting(" AND module_name IN('main')");

                if (isset($setting_data['enable_l1_l2']) && $setting_data['enable_l1_l2'] == 1) {
                    $this->load->module('vendor_rate_estimates');
                    $l1_l2_data = $this->vendor_rate_estimates->get_vendor_rate($post_data);

                    if (isset($l1_l2_data['contract'][0])) {
                        //if (isset($l1_l2_data['contract'][0]) && is_array($l1_l2_data['contract'][0]) && count($l1_l2_data['contract'][0]) > 0) {
                        $vendor_data = isset($l1_l2_data['all_vendor']) ? $l1_l2_data['all_vendor'] : array();
                        $firstKey = array_keys($l1_l2_data['contract'][0])[0];
                        //$firstKey = array_key_first($l1_l2_data['contract'][0]);

                        $post_data['co_vendor_id'] = $firstKey;
                        $setting_co_vendor_id = $firstKey;
                        $setting_co_vendor_name = $vendor_data[$firstKey]['name'];
                        $setting_co_vendor_code = $vendor_data[$firstKey]['code'];
                    }
                } else {
                    //CHECK VENDOR MAPPING
                    $post_vendor_id = $post_data['vendor_id'] > 0 ? $post_data['vendor_id'] : 0;
                    $post_origin_id = $post_data['origin_id'] > 0 ? $post_data['origin_id'] : '0';
                    $post_ori_hub_id = $post_data['ori_hub_id'] > 0 ? $post_data['ori_hub_id'] : '0';
                    $post_dest_hub_id = $post_data['dest_hub_id'] > 0 ? $post_data['dest_hub_id'] : '0';
                    $post_destination_id = $post_data['destination_id'] > 0 ? $post_data['destination_id'] : '0';

                    $contractq = "SELECT * FROM vendor_mapping 
                    WHERE status IN(1) AND '" . $booking_date . "' 
                    BETWEEN effective_min AND effective_max
                    AND (vendor_id ='" .   $post_vendor_id . "' OR vendor_id=0)
                    ORDER BY effective_min DESC";
                    $contractq_exe = $this->db->query($contractq);
                    $mapping_condition_data = $contractq_exe->result_array();
                    if (isset($mapping_condition_data) && is_array($mapping_condition_data) && count($mapping_condition_data) > 0) {
                        foreach ($mapping_condition_data as $ma_key => $ma_value) {
                            $map_condition_cnt = 0;
                            //MATCH ORIGIN LOCATION OR HUB
                            if ($ma_value['ori_location_id'] == $post_origin_id) {
                                $map_condition_cnt += 1;
                            } else if ($ma_value['ori_hub_id'] == $post_ori_hub_id) {
                                $map_condition_cnt += 1;
                            }

                            //MATCH DESTINATION LOCATION OR HUB
                            if ($ma_value['dest_location_id'] == $post_destination_id) {
                                $map_condition_cnt += 1;
                            } else if ($ma_value['dest_hub_id'] == $post_dest_hub_id) {
                                $map_condition_cnt += 1;
                            }

                            if ($map_condition_cnt == 2) {
                                $mapping_effective[$ma_value['effective_min']][$ma_value['id']] = $map_condition_cnt;

                                $mapping_match[$ma_value['id']] = $ma_value;
                                $mapping_match[$ma_value['id']]['point'] = $map_condition_cnt;
                            }
                        }


                        if (isset($mapping_match) && is_array($mapping_match) && count($mapping_match) > 0) {
                            $max_mapping_date =  max(array_column($mapping_match, 'effective_min'));

                            if (isset($mapping_effective[$max_mapping_date])) {
                                arsort($mapping_effective[$max_mapping_date], SORT_NUMERIC);
                                $latest_mapping_id = array_keys($mapping_effective[$max_mapping_date])[0];

                                if (isset($mapping_match[$latest_mapping_id])) {
                                    $mapping_data = $mapping_match[$latest_mapping_id];
                                }
                            }
                        }
                    }
                    if (isset($mapping_data) && is_array($mapping_data) && count($mapping_data) > 0) {
                        $setting_co_vendor_id = $mapping_data['co_vendor_id'];

                        $all_co_vendor = get_all_co_vendor(" AND id ='" . $mapping_data['co_vendor_id'] . "'");
                        $setting_co_vendor_name = isset($all_co_vendor[$mapping_data['co_vendor_id']]['name']) ? $all_co_vendor[$mapping_data['co_vendor_id']]['name'] : '';
                        $setting_co_vendor_code = isset($all_co_vendor[$mapping_data['co_vendor_id']]['name']) ? $all_co_vendor[$mapping_data['co_vendor_id']]['code'] : '';
                    }
                }
            }

            if (isset($post_data['dest_city_id']) && is_numeric($post_data['dest_city_id']) && $post_data['dest_city_id'] > 0) {
                $post_data['dest_city_id'] = $post_data['dest_city_id'];
            } else {
                $post_data['dest_city_id'] = $post_data['dest_city_id'] != '' ? check_record_exist(array('name' => $post_data['dest_city_id']), '', 'city') : 'NULL';
            }

            if (isset($post_data['ori_city_id']) && is_numeric($post_data['ori_city_id']) && $post_data['ori_city_id'] > 0) {
                $post_data['ori_city_id'] = $post_data['ori_city_id'];
            } else {
                $post_data['ori_city_id'] = $post_data['ori_city_id'] != '' ? check_record_exist(array('name' => $post_data['ori_city_id']), '', 'city') : 'NULL';
            }


            if ($post_data['dest_city_id'] > 0) {
            } else {
                $post_data['dest_city_id'] = 'NULL';
            }

            if ($post_data['ori_city_id'] > 0) {
            } else {
                $post_data['ori_city_id'] = 'NULL';
            }

            if (isset($post_data['ori_state_id']) && is_numeric($post_data['ori_state_id']) && $post_data['ori_state_id'] > 0) {
                $post_data['ori_state_id'] = $post_data['ori_state_id'];
            } else {
                $post_data['ori_state_id'] = $post_data['ori_state_id'] != '' ? check_record_exist(array('name' => $post_data['ori_state_id']), '', 'state') : 'NULL';
            }

            if (isset($post_data['dest_state_id']) && is_numeric($post_data['dest_state_id']) && $post_data['dest_state_id'] > 0) {
                $post_data['dest_state_id'] = $post_data['dest_state_id'];
            } else {
                $post_data['dest_state_id'] = $post_data['dest_state_id'] != '' ? check_record_exist(array('name' => $post_data['dest_state_id']), '', 'state') : 'NULL';
            }

            if ($post_data['ori_state_id'] > 0) {
            } else {
                $post_data['ori_state_id'] = 'NULL';
            }
            if ($post_data['dest_state_id'] > 0) {
            } else {
                $post_data['dest_state_id'] = 'NULL';
            }


            if ($post_data['ori_hub_id'] > 0) {
                $post_data['ori_hub_id'] = $post_data['ori_hub_id'];
            } else {
                $post_data['ori_hub_id'] = 'NULL';
            }
            if ($post_data['dest_hub_id'] > 0) {
                $post_data['dest_hub_id'] = $post_data['dest_hub_id'];
            } else {
                $post_data['dest_hub_id'] = 'NULL';
            }


            $setting = get_all_app_setting(" AND module_name IN('docket')");


            //CHECK CUSTOMER CONTRACT HEAD PRESENT
            if ($post_data['customer_id'] > 0 && $booking_date != '' && $booking_date != '1970-01-01' && $booking_date != '0000-00-00') {
                $query = "SELECT tat_customer_id,from_date,till_date FROM customer_tat_head
                    WHERE status IN(1) AND customer_id='" . $post_data['customer_id'] . "' AND '" . $booking_date . "' BETWEEN from_date 
                    AND till_date ORDER BY from_date DESC";
                $query_exe = $this->db->query($query);
                $customer_tat_head = $query_exe->row_array();
            }

            if (isset($setting['apply_sepcial_rate_cust_contract']) && $setting['apply_sepcial_rate_cust_contract'] == 1) {
                $tat_customer_id[$post_data['customer_id']] = $post_data['customer_id'];
                if (isset($customer_tat_head) && is_array($customer_tat_head) && count($customer_tat_head) > 0) {
                    $tat_customer_id[$customer_tat_head['tat_customer_id']] = $customer_tat_head['tat_customer_id'];
                }
            } else {

                if (isset($customer_tat_head) && is_array($customer_tat_head) && count($customer_tat_head) > 0) {
                    $tat_customer_id[$customer_tat_head['tat_customer_id']] = $customer_tat_head['tat_customer_id'];
                }
                $tat_customer_id[$post_data['customer_id']] = $post_data['customer_id'];
            }

            if (isset($tat_customer_id) && is_array($tat_customer_id) && count($tat_customer_id) > 0) {
                foreach ($tat_customer_id as $ch_key => $ch_value) {
                    //EXACT MATCH CUTOMER,SERVICE,PRODUCT
                    $append = "";
                    if (!isset($post_data['mode'])) {
                        $sel_col = "*";
                    } else {
                        $sel_col = "id,vendor_id";
                    }
                    if (!isset($post_data['mode'])) {
                        $append .= " AND vendor_id='" . $post_data['vendor_id'] . "'";
                    }
                    if (isset($post_data['is_sales']) && $post_data['is_sales'] == 1) {
                        $append .= " AND tat_type IN(1,2)";
                    }
                    if (isset($post_data['is_purchase']) && $post_data['is_purchase'] == 1) {
                        $append .= " AND tat_type IN(1,3)";
                    }

                    if (isset($post_data['mode'])) {
                        $query =  "SELECT " . $sel_col . " FROM tat_masters WHERE status IN(1) 
                        AND '" . $booking_date . "' BETWEEN effective_min AND effective_max
                        AND customer_id='" . $ch_value . "'"
                            . $append . " ORDER BY effective_min DESC,id DESC";
                        $query_exe = $this->db->query($query);
                        $contract_data = $query_exe->result_array();
                        if (isset($contract_data) && is_array($contract_data) && count($contract_data) > 0) {
                            foreach ($contract_data as $con_key => $con_value) {
                                $all_vendor_id[$con_value['vendor_id']] = $con_value['vendor_id'];
                            }
                        }
                    } else {
                        $query =  "SELECT " . $sel_col . " FROM tat_masters WHERE status IN(1)
                         AND '" . $booking_date . "' BETWEEN effective_min AND effective_max 
                         AND customer_id=" . $ch_value . " AND product_id='" . $post_data['product_id'] . "'"
                            . $append . " ORDER BY effective_min DESC,id DESC";
                        $query_exe = $this->db->query($query);
                        $contract_data = $query_exe->result_array();
                        // echo  $query;
                        // echo '<pre>';
                        // print_r($contract_data);

                        if (isset($contract_data) && is_array($contract_data) && count($contract_data) > 0) {
                            foreach ($contract_data as $con_key => $con_value) {

                                $contract_point = 0;
                                $condition_match = 0;
                                if ($con_value['co_vendor_id'] == 0 || $con_value['co_vendor_id'] == $post_data['co_vendor_id']) {
                                    $contract_point += 1;

                                    if ($con_value['co_vendor_id'] == $post_data['co_vendor_id']) {
                                        $contract_point += 10;
                                    }
                                    $condition_match += 1;
                                }

                                //MATCH ORIGIN LOCATION ON location,city,state,zone priority
                                if ($con_value['ori_location_id'] == $post_data['origin_id']) {
                                    $contract_point += 4;
                                    $condition_match += 1;
                                } else if ($con_value['ori_city_id'] == $post_data['ori_city_id']) {
                                    $contract_point += 3;
                                    $condition_match += 1;
                                } else if ($con_value['ori_state_id'] == $post_data['ori_state_id']) {
                                    $contract_point += 2;
                                    $condition_match += 1;
                                } else if ($con_value['ori_zone_id'] == $post_data['ori_zone_id']) {
                                    $contract_point += 1;
                                    $condition_match += 1;
                                } else if ($con_value['ori_hub_id'] == $post_data['ori_hub_id']) {
                                    $contract_point += 1;
                                    $condition_match += 1;
                                }


                                //MATCH DEST LOCATION ON location,city,state,zone priority
                                if ($con_value['dest_location_id'] == $post_data['destination_id']) {
                                    $contract_point += 4;
                                    $condition_match += 1;
                                } else if ($con_value['dest_city_id'] == $post_data['dest_city_id']) {
                                    $contract_point += 3;
                                    $condition_match += 1;
                                } else if ($con_value['dest_state_id'] == $post_data['dest_state_id']) {
                                    $contract_point += 2;
                                    $condition_match += 1;
                                } else if ($con_value['dest_zone_id'] == $post_data['dest_zone_id']) {
                                    $contract_point += 1;
                                    $condition_match += 1;
                                } else if ($con_value['dest_hub_id'] == $post_data['dest_hub_id']) {
                                    $contract_point += 1;
                                    $condition_match += 1;
                                }

                                if ($condition_match == 3) {



                                    if ($con_value['customer_id'] != $post_data['customer_id']) {
                                        //CONTRACT HEAD CUSTOMER
                                        $contract_head_match[$con_value['id']] = $con_value;
                                        $contract_head_match[$con_value['id']]['point'] = $contract_point;
                                    } else {
                                        $contract_match[$con_value['id']] = $con_value;
                                        $contract_match[$con_value['id']]['point'] = $contract_point;
                                    }

                                    if (!isset($post_data['mode'])) {
                                        if ($con_value['customer_id'] != $post_data['customer_id']) {
                                            //CONTRACT HEAD CUSTOMER
                                            $contract_head_effective[$con_value['effective_min']][$con_value['id']] = $contract_point;
                                        } else {
                                            $contract_effective[$con_value['effective_min']][$con_value['id']] = $contract_point;
                                        }
                                    } else {
                                        if (!in_array($con_value['vendor_id'], $all_vendor_id)) {
                                            $all_vendor_id[] = $con_value['vendor_id'];
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if (!isset($post_data['mode'])) {
                $response_data = array();
                if (isset($setting['apply_sepcial_rate_cust_contract']) && $setting['apply_sepcial_rate_cust_contract'] == 1) {
                    if (isset($contract_match) && is_array($contract_match) && count($contract_match) > 0) {
                        $max_contract_date =  max(array_column($contract_match, 'effective_min'));

                        if (isset($contract_effective[$max_contract_date])) {
                            arsort($contract_effective[$max_contract_date], SORT_NUMERIC);
                            $latest_contract_id = array_keys($contract_effective[$max_contract_date])[0];

                            if (isset($contract_match[$latest_contract_id])) {
                                $response_data = $contract_match[$latest_contract_id];
                            }
                        }
                    }

                    if (isset($response_data) && is_array($response_data) && count($response_data) > 0) {
                    } else {
                        //CHECK IN CONTRACT HEAD
                        if (isset($contract_head_match) && is_array($contract_head_match) && count($contract_head_match) > 0) {
                            $max_contract_head_date =  max(array_column($contract_head_match, 'effective_min'));

                            if (isset($contract_head_effective[$max_contract_head_date])) {
                                arsort($contract_head_effective[$max_contract_head_date], SORT_NUMERIC);
                                $latest_contract_id = array_keys($contract_head_effective[$max_contract_head_date])[0];

                                if (isset($contract_head_match[$latest_contract_id])) {
                                    $response_data = $contract_head_match[$latest_contract_id];
                                }
                            }
                        }
                    }
                } else {

                    //CHECK IN CONTRACT HEAD
                    if (isset($contract_head_match) && is_array($contract_head_match) && count($contract_head_match) > 0) {
                        $max_contract_head_date =  max(array_column($contract_head_match, 'effective_min'));

                        if (isset($contract_head_effective[$max_contract_head_date])) {
                            arsort($contract_head_effective[$max_contract_head_date], SORT_NUMERIC);
                            $latest_contract_id = array_keys($contract_head_effective[$max_contract_head_date])[0];

                            if (isset($contract_head_match[$latest_contract_id])) {
                                $response_data = $contract_head_match[$latest_contract_id];
                            }
                        }
                    }
                    if (isset($response_data) && is_array($response_data) && count($response_data) > 0) {
                    } else {
                        if (isset($contract_match) && is_array($contract_match) && count($contract_match) > 0) {
                            $max_contract_date =  max(array_column($contract_match, 'effective_min'));

                            if (isset($contract_effective[$max_contract_date])) {
                                arsort($contract_effective[$max_contract_date], SORT_NUMERIC);
                                $latest_contract_id = array_keys($contract_effective[$max_contract_date])[0];

                                if (isset($contract_match[$latest_contract_id])) {
                                    $response_data = $contract_match[$latest_contract_id];
                                }
                            }
                        }
                    }
                }
            }
        }
        // echo "<pre>";
        // print_r($response_data);
        // exit;
        if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
            return $response_data;
        } else {
            echo json_encode($response_data);
        }
    }

    function choose_credentials_by_weight($credential_data, $weight)
    {
        $weight = (float)$weight;

        if (isset($credential_data) && is_array($credential_data) && count($credential_data) > 0) {
            if ($weight <= 5.0 && $weight > 0) {
                $credential_data = "password:" . $credential_data["5.0"]["password"] . "\n" . "key:" . $credential_data["5.0"]["key"] . "\n" . "meter_number:" . $credential_data["5.0"]["meter_number"] . "\n" . "account_number:" . $credential_data["5.0"]["account_number"] . "\n" . "fedex_api_service:" . $credential_data["5.0"]["fedex_api_service"]
                    . "\n" . "api_key:"   . $credential_data["5.0"]["api_key"] . "\n" . "secret_key:" . $credential_data["5.0"]["secret_key"];
                return $credential_data;
            } elseif ($weight > 5.0 && $weight <= 70.5) {
                $credential_data = "password:" . $credential_data["70.5"]["password"] . "\n" . "key:" . $credential_data["70.5"]["key"] . "\n" . "meter_number:" . $credential_data["70.5"]["meter_number"] . "\n" . "account_number:" . $credential_data["70.5"]["account_number"] . "\n" . "fedex_api_service:" . $credential_data["5.0"]["fedex_api_service"]
                    . "\n" . "api_key:"   . $credential_data["70.5"]["api_key"] . "\n" . "secret_key:" . $credential_data["70.5"]["secret_key"];
                return $credential_data;
            } else if ($weight > 70.5) {
                $credential_data = "password:" . $credential_data["70.5+"]["password"] . "\n" . "key:" . $credential_data["70.5+"]["key"] . "\n" . "meter_number:" . $credential_data["70.5+"]["meter_number"] . "\n" . "account_number:" . $credential_data["70.5+"]["account_number"] . "\n" . "fedex_api_service:" . $credential_data["5.0"]["fedex_api_service"]
                    . "\n" . "api_key:"   . $credential_data["70.5+"]["api_key"] . "\n" . "secret_key:" . $credential_data["70.5+"]["secret_key"];
                return $credential_data;
            }
        }
    }
}
