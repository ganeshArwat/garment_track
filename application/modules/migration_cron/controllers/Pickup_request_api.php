<?php
class Pickup_request_api extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_ids()
    {
        $time_start = microtime(true);
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');
        $sessiondata = $this->session->userdata('admin_user');
        $company_id = isset($sessiondata['com_id']) ? $sessiondata['com_id'] : '';
        $main_db = $this->load->database('main_db', true);
        $qry = "SELECT  id,old_domain FROM company WHERE status IN(1,2) AND id='" . $company_id . "'";
        $qry_exe = $main_db->query($qry);
        $com_res = $qry_exe->row_array();
        $base_url = isset($com_res['old_domain']) ? $com_res['old_domain'] : '';

        if ($base_url != '') {
            $url = $base_url . "api/v5/pick_up_requests/get_pick_up_request_ids";

            $request_json[] = array();
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,  $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($ch, CURLOPT_POSTFIELDS, array());
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Accept: application/json'
            ));
            $response_json = curl_exec($ch);
            curl_close($ch);
            $result = json_decode($response_json, TRUE);

            if (isset($result['data']) && is_array($result['data']) && count($result['data']) > 0) {
                foreach ($result['data'] as $rkey => $rvalue) {
                    $qry = "SELECT id FROM pickup_request WHERE status IN(1,2) AND migration_id='" . $rvalue . "'";
                    $qry_exe = $this->db->query($qry);
                    $existData = $qry_exe->row_array();

                    if (isset($existData) && is_array($existData) && count($existData) > 0) {
                    } else {
                        $insert_data[] = array(
                            'migration_id' => $rvalue
                        );
                    }
                }

                if (isset($insert_data) && is_array($insert_data) && count($insert_data) > 0) {
                    $this->db->insert_batch('pickup_request', $insert_data);
                    echo count($insert_data) . " ID INSERTED";
                }
            }
        }

        $time_end = microtime(true);
        $execution_time = ($time_end - $time_start) / 60;

        //execution time of the script
        echo '<b>Total Execution Time:</b> ' . ($time_end - $time_start) . ' Second';
        echo '<b>Total Execution Time:</b> ' . $execution_time . ' Mins';
    }
    public function get_api_data($company_code = '')
    {
        $time_start = microtime(true);
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');
        // $company_data = $this->config->item('company_base_url');
        // $base_url = isset($company_data[$company_code]) ? $company_data[$company_code] : '';
        $sessiondata = $this->session->userdata('admin_user');
        $company_id = isset($sessiondata['com_id']) ? $sessiondata['com_id'] : '';
        $main_db = $this->load->database('main_db', true);
        $qry = "SELECT  id,old_domain FROM company WHERE status IN(1,2) AND id='" . $company_id . "'";
        $qry_exe = $main_db->query($qry);
        $com_res = $qry_exe->row_array();
        $base_url = isset($com_res['old_domain']) ? $com_res['old_domain'] : '';
        // $base_url = "http://165.22.221.202/";
        if ($base_url != '') {

            $url = $base_url . "api/v5/pick_up_requests/fetch_pick_up_requests";

            $qry = "SELECT  id,config_value FROM migration_log WHERE status IN(1,2) AND config_key='pick_up_requests_migrate_last_id' ";
            $qry_exe = $this->db->query($qry);
            $offset_res = $qry_exe->row_array();

            $limit = 100;
            if (isset($offset_res['config_value']) && $offset_res['config_value'] != '') {
                $last_id = $offset_res['config_value'];
            } else {
                $last_id = 0;
            }
            $qry = "SELECT migration_id FROM pickup_request WHERE migration_id > " . $last_id . " ORDER BY migration_id  LIMIT " . $limit . "";
            $qry_exe = $this->db->query($qry);
            $id_res = $qry_exe->result_array();

            if (isset($id_res) && is_array($id_res) && count($id_res) > 0) {
                foreach ($id_res as $ikey => $ivalue) {
                    $id_arr[] = $ivalue['migration_id'];
                }
            }
            //$id_arr[] = '1301';
            if (isset($id_arr) && is_array($id_arr) && count($id_arr) > 0) {
                $request_json['ids'] = $id_arr;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request_json));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Accept: application/json'
                ));
                $response_json = curl_exec($ch);

                curl_close($ch);

                $response_json =  str_replace(array("'"), "", $response_json);
                $result = json_decode($response_json, true);

                $all_cust = get_all_customer(" AND status IN(1,2)", 'code');
                $all_co_vendor = get_all_co_vendor(" AND status IN(1,2)", 'code');
                $all_vendor = get_all_vendor(" AND status IN(1,2)", 'code');
                $all_country = get_all_country(" AND status IN(1,2)", 'name');
                $all_gsyt_type =  get_all_doc_type(" AND show_in_docket_shipper=1", "docket_doc_name");
                //get_all_gstin_type(" AND status IN(1,2)", 'name');
                $all_zone = get_all_zone(" AND status IN(1,2)", 'name');
                // $all_user = get_all_user(" AND status IN(1,2)", '', 'migration_id');
                $all_user = get_all_user(" AND company_id='" . $company_id . "'", "", "migration_id");

                if (isset($result) && is_array($result) && count($result) > 0) {
                    if (isset($result['data']) && is_array($result['data']) && count($result['data']) > 0) {
                        $keys = array_column($result['data'], 'id');
                        array_multisort($keys, SORT_ASC, $result['data']);

                        foreach ($result['data'] as $key => $value) {

                            $customer_id = isset($value['company_code']) && $value['company_code'] != '' && isset($all_cust[strtolower(trim($value['company_code']))]) ? $all_cust[strtolower(trim($value['company_code']))]['id'] : 0;
                            $co_vendor_id = isset($value['co_vendor_code']) && $value['co_vendor_code'] != '' && isset($all_co_vendor[strtolower(trim($value['co_vendor_code']))]) ? $all_co_vendor[strtolower(trim($value['co_vendor_code']))]['id'] : 0;
                            $vendor_id = isset($value['vendor_code']) && $value['vendor_code'] != '' && isset($all_vendor[strtolower(trim($value['vendor_code']))]) ? $all_vendor[strtolower(trim($value['vendor_code']))]['id'] : 0;


                            $insert_data = array(
                                'migration_id' => $value['id'],
                                'ref_no' => $value['reference_no'],
                                'call_date' => $value['pickup_date'],
                                'call_time' => date('H:i:s', strtotime($value['pickup_time'])),
                                'contact_person_no' =>  $value['contact_person_phone'],
                                'contact_person_name' =>  $value['contact_person_name'],
                                'customer_id' => $customer_id,
                                'created_date' =>  $value['created_at'],
                                'modified_date' =>  $value['updated_at'],
                                'co_vendor_id' => $co_vendor_id,
                                'vendor_id' => $vendor_id,
                                'pickup_cancel' => $value['is_cancel'] == 1 ? 1 : 2,
                                'created_by' => isset($all_user[$value['created_by_id']]) ? $all_user[$value['created_by_id']]['id'] : 0,
                                'modified_by' => isset($all_user[$value['updated_by_id']]) ? $all_user[$value['updated_by_id']]['id'] : 0,
                            );


                            $qry = "SELECT id FROM pickup_request WHERE status IN(1,2) AND migration_id='" . $value['id'] . "'";
                            $qry_exe = $this->db->query($qry);
                            $existData = $qry_exe->row_array();

                            if (isset($existData) && is_array($existData) && count($existData) > 0) {
                                $pickup_request_id = $existData['id'];
                                $this->gm->update('pickup_request', $insert_data, '', array('id' => $pickup_request_id));
                            } else {
                                $pickup_request_id = $this->gm->insert('pickup_request', $insert_data);
                            }

                            if (isset($value['pick_up_request_items']) && is_array($value['pick_up_request_items']) && count($value['pick_up_request_items']) > 0) {
                                foreach ($value['pick_up_request_items'] as $pkey => $pvalue) {
                                    $consignee_country_id = isset($pvalue['consignee_country']) && $pvalue['consignee_country'] != '' && isset($all_country[strtolower(trim($pvalue['consignee_country']))]) ? $all_country[strtolower(trim($pvalue['consignee_country']))]['id'] : 0;
                                    $consignee_gstin_type_id = isset($pvalue['consignee_gstin_type']) && $pvalue['consignee_gstin_type'] != '' && isset($all_gsyt_type[strtolower(trim($pvalue['consignee_gstin_type']))]) ? $all_gsyt_type[strtolower(trim($pvalue['consignee_gstin_type']))]['id'] : 0;

                                    $pickup_address_country_id = isset($pvalue['pickup_address_country']) && $pvalue['pickup_address_country'] != '' && isset($all_country[strtolower(trim($pvalue['pickup_address_country']))]) ? $all_country[strtolower(trim($pvalue['pickup_address_country']))]['id'] : 0;
                                    $pickup_address_gstin_type_id = isset($pvalue['pickup_address_gstin_type']) && $pvalue['pickup_address_gstin_type'] != '' && isset($all_gsyt_type[strtolower(trim($pvalue['pickup_address_gstin_type']))]) ? $all_gsyt_type[strtolower(trim($pvalue['pickup_address_gstin_type']))]['id'] : 0;

                                    $pickup_origin_zone = isset($pvalue['origin_zone']) && $pvalue['origin_zone'] != '' && isset($all_zone[strtolower(trim($pvalue['origin_zone']))]) ? $all_gsyt_type[strtolower(trim($pvalue['origin_zone']))]['id'] : 0;
                                    $consignee_dest_zone = isset($pvalue['destination_zone']) && $pvalue['destination_zone'] != '' && isset($all_zone[strtolower(trim($pvalue['destination_zone']))]) ? $all_zone[strtolower(trim($pvalue['destination_zone']))]['id'] : 0;

                                    $user_id = isset($pvalue['user_id']) && $pvalue['user_id'] != '' && isset($all_user[$pvalue['user_id']]) ? $all_user[$pvalue['user_id']]['id'] : 0;

                                    $pickup_priority = 0;
                                    if (strtolower($pvalue['pickup_priority']) == 'regular') {
                                        $pickup_priority = 1;
                                    } else if (strtolower($pvalue['pickup_priority']) == 'urgent') {
                                        $pickup_priority = 2;
                                    }
                                    $pickup_request_detail = array(
                                        'migration_id' => $pvalue['id'],
                                        'pickup_request_id' => $pickup_request_id,
                                        'pickup_date' => $pvalue['pick_up_date'],
                                        'pickup_time' => date('H:i:s', strtotime($pvalue['pick_up_time'])),
                                        'pieces' => $pvalue['pcs'],
                                        'weight' => $pvalue['weight'],
                                        'remark' => $pvalue['remark'],
                                        'consignee_code' => $pvalue['consignee_code'],
                                        'consignee_name' => $pvalue['consignee_name'],
                                        'consignee_address1' => $pvalue['consignee_address_line_1'],
                                        'consignee_address2' => $pvalue['consignee_address_line_2'],
                                        'consignee_address3' => $pvalue['consignee_address_line_3'],
                                        'consignee_city' => $pvalue['consignee_city'],
                                        'consignee_state' => $pvalue['consignee_state'],
                                        'consignee_country' => $consignee_country_id,
                                        'consignee_pincode' => $pvalue['consignee_zip_code'],
                                        'consignee_email' => $pvalue['consignee_email'],
                                        'consignee_phone' => $pvalue['consignee_contact_no'],
                                        'consignee_gstin_type' => $consignee_gstin_type_id,
                                        'consignee_gstin_no' => $pvalue['consignee_gstin_no'],

                                        'pickup_code' => $pvalue['pickup_address_code'],
                                        'pickup_name' => $pvalue['pickup_address_name'],
                                        'pickup_address1' => $pvalue['pickup_address_address_line_1'],
                                        'pickup_address2' => $pvalue['pickup_address_address_line_2'],
                                        'pickup_address3' => $pvalue['pickup_address_address_line_3'],
                                        'pickup_city' => $pvalue['pickup_address_city'],
                                        'pickup_state' => $pvalue['pickup_address_state'],
                                        'pickup_country' => $pickup_address_country_id,
                                        'pickup_pincode' => $pvalue['pickup_address_zip_code'],
                                        'pickup_email' => $pvalue['pickup_address_email'],
                                        'pickup_phone' => $pvalue['pickup_address_contact_no'],
                                        'pickup_gstin_type' => $pickup_address_gstin_type_id,
                                        'pickup_gstin_no' => $pvalue['pickup_address_gstin_no'],
                                        'created_date' =>  $pvalue['created_at'],
                                        'modified_date' =>  $pvalue['updated_at'],
                                        'pickup_priority' => $pickup_priority,
                                        'total_pieces' => $pvalue['total_pcs'],
                                        'total_awb' => $pvalue['total_awbs'],
                                        'po_number' => $pvalue['po_number'],
                                        'estimate_no' => $pvalue['estimate_number'],
                                        'pickup_origin_zone' => $pickup_origin_zone,
                                        'consignee_dest_zone' => $consignee_dest_zone,
                                        'commit_id' => $pvalue['commit_id'],
                                        'user_id' => $user_id,
                                        'created_by' => isset($all_user[$value['created_by_id']]) ? $all_user[$value['created_by_id']]['id'] : 0,
                                        'modified_by' => isset($all_user[$value['updated_by_id']]) ? $all_user[$value['updated_by_id']]['id'] : 0,
                                    );

                                    unset($user_id);
                                    $qry = "SELECT id FROM pickup_request_detail WHERE status IN(1,2) AND pickup_request_id='" . $pickup_request_id . "'";
                                    $qry_exe = $this->db->query($qry);
                                    $existData = $qry_exe->row_array();

                                    if (isset($existData) && is_array($existData) && count($existData) > 0) {
                                        $this->gm->update('pickup_request_detail', $pickup_request_detail, '', array('pickup_request_id' => $pickup_request_id));
                                    } else {
                                        $this->gm->insert('pickup_request_detail', $pickup_request_detail);
                                    }
                                }
                            }

                            //UPDATE OFFSET
                            $qry = "SELECT  id,config_value FROM migration_log WHERE status IN(1,2) AND config_key='pick_up_requests_migrate_last_id' ";
                            $qry_exe = $this->db->query($qry);
                            $configExist = $qry_exe->row_array();
                            if (isset($configExist) && is_array($configExist) && count($configExist) > 0) {
                                $updateq = "UPDATE migration_log SET config_value='" . $value['id'] . "' WHERE status IN(1,2) AND config_key='pick_up_requests_migrate_last_id'";
                                $this->db->query($updateq);
                            } else {
                                $mig_insert_data = array(
                                    'config_key' => 'pick_up_requests_migrate_last_id',
                                    'config_value' => $value['id']
                                );
                                $this->gm->insert('migration_log', $mig_insert_data);
                            }

                            echo "<br>REF NO." . $value['reference_no'] . " added";
                        }
                    }
                }
            }
        }

        $time_end = microtime(true);
        $execution_time = ($time_end - $time_start) / 60;

        //execution time of the script
        echo '<b>Total Execution Time:</b> ' . ($time_end - $time_start) . ' Second';
        echo '<b>Total Execution Time:</b> ' . $execution_time . ' Mins';
    }



    public function update_customer($company_code = '')
    {
        $time_start = microtime(true);
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');
        $sessiondata = $this->session->userdata('admin_user');
        $company_id = isset($sessiondata['com_id']) ? $sessiondata['com_id'] : '';
        $main_db = $this->load->database('main_db', true);
        $qry = "SELECT  id,old_domain FROM company WHERE status IN(1,2) AND id='" . $company_id . "'";
        $qry_exe = $main_db->query($qry);
        $com_res = $qry_exe->row_array();
        $base_url = isset($com_res['old_domain']) ? $com_res['old_domain'] : '';
        //$base_url = "http://165.22.221.202/";
        if ($base_url != '') {

            $url = $base_url . "api/v5/pick_up_requests/fetch_pick_up_requests";

            $qry = "SELECT  id,config_value FROM migration_log WHERE status IN(1,2) AND config_key='pick_up_requests_update_last_id' ";
            $qry_exe = $this->db->query($qry);
            $offset_res = $qry_exe->row_array();

            $limit = 100;
            if (isset($offset_res['config_value']) && $offset_res['config_value'] != '') {
                $last_id = $offset_res['config_value'];
            } else {
                $last_id = 0;
            }
            $qry = "SELECT id,migration_id FROM pickup_request WHERE customer_id= 0 AND migration_id > " . $last_id . " ORDER BY migration_id  LIMIT " . $limit . "";
            $qry_exe = $this->db->query($qry);
            $id_res = $qry_exe->result_array();

            if (isset($id_res) && is_array($id_res) && count($id_res) > 0) {
                foreach ($id_res as $ikey => $ivalue) {
                    $id_arr[] = $ivalue['migration_id'];
                }
            }
            //$id_arr[] = '1301';
            if (isset($id_arr) && is_array($id_arr) && count($id_arr) > 0) {
                $request_json['ids'] = $id_arr;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request_json));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Accept: application/json'
                ));
                $response_json = curl_exec($ch);

                curl_close($ch);

                $response_json =  str_replace(array("'"), "", $response_json);
                $result = json_decode($response_json, true);

                // echo '<pre>';
                // print_r($result);
                // exit;
                $all_cust = get_all_customer(" AND status IN(1,2)", 'code');

                if (isset($result) && is_array($result) && count($result) > 0) {
                    if (isset($result['data']) && is_array($result['data']) && count($result['data']) > 0) {
                        $keys = array_column($result['data'], 'id');
                        array_multisort($keys, SORT_ASC, $result['data']);

                        foreach ($result['data'] as $key => $value) {

                            $customer_id = isset($value['company_code']) && $value['company_code'] != '' && isset($all_cust[strtolower(trim($value['company_code']))]) ? $all_cust[strtolower(trim($value['company_code']))]['id'] : 0;


                            $insert_data = array(
                                'customer_id' => $customer_id,
                            );
                            $this->gm->update('pickup_request', $insert_data, '', array('id' => $value['id']));
                            //UPDATE OFFSET
                            $qry = "SELECT  id,config_value FROM migration_log WHERE status IN(1,2) AND config_key='pick_up_requests_update_last_id' ";
                            $qry_exe = $this->db->query($qry);
                            $configExist = $qry_exe->row_array();
                            if (isset($configExist) && is_array($configExist) && count($configExist) > 0) {
                                $updateq = "UPDATE migration_log SET config_value='" . $value['migration_id'] . "' WHERE status IN(1,2) AND config_key='pick_up_requests_update_last_id'";
                                $this->db->query($updateq);
                            } else {
                                $mig_insert_data = array(
                                    'config_key' => 'pick_up_requests_update_last_id',
                                    'config_value' => $value['migration_id']
                                );
                                $this->gm->insert('migration_log', $mig_insert_data);
                            }

                            echo "<br>REF NO." . $value['reference_no'] . " added";
                        }
                    }
                }
            }
        }

        $time_end = microtime(true);
        $execution_time = ($time_end - $time_start) / 60;

        //execution time of the script
        echo '<b>Total Execution Time:</b> ' . ($time_end - $time_start) . ' Second';
        echo '<b>Total Execution Time:</b> ' . $execution_time . ' Mins';
    }
}
