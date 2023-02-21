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
    
}
