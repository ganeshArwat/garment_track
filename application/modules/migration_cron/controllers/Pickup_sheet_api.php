<?php
class Pickup_sheet_api extends MX_Controller
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
            $url = $base_url . "api/v5/pick_up_sheets/get_pick_up_sheet_ids";

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
                    $qry = "SELECT id FROM pick_up_sheets WHERE status IN(1,2) AND migration_id='" . $rvalue . "'";
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
                    $this->db->insert_batch('pick_up_sheets', $insert_data);
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

        if ($base_url != '') {

            $url = $base_url . "api/v5/pick_up_sheets/fetch_pick_up_sheets";

            $qry = "SELECT  id,config_value FROM migration_log WHERE status IN(1,2) AND config_key='pick_up_sheet_migrate_last_id' ";
            $qry_exe = $this->db->query($qry);
            $offset_res = $qry_exe->row_array();

            $limit = 500;
            if (isset($offset_res['config_value']) && $offset_res['config_value'] != '') {
                $last_id = $offset_res['config_value'];
            } else {
                $last_id = 0;
            }
            $qry = "SELECT migration_id FROM pick_up_sheets WHERE migration_id > " . $last_id . " ORDER BY migration_id  LIMIT " . $limit . "";
            $qry_exe = $this->db->query($qry);
            $id_res = $qry_exe->result_array();

            if (isset($id_res) && is_array($id_res) && count($id_res) > 0) {
                foreach ($id_res as $ikey => $ivalue) {
                    $id_arr[] = $ivalue['migration_id'];
                }
            }

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

                $all_route = get_all_route(" AND status IN(1,2) ", "code");
                $all_hub = get_all_hub(" AND status IN(1,2) ", "code");
                // $all_user = get_all_user(" AND status IN(1,2) ", "migration_id");
                $all_cust = get_all_customer(" AND status IN(1,2)", 'code');
                $all_zone = get_all_zone(" AND status IN(1,2)", 'name');
                $all_user = get_all_user(" AND company_id='" . $company_id . "'", "", "migration_id");

                if (isset($result) && is_array($result) && count($result) > 0) {
                    if (isset($result['data']) && is_array($result['data']) && count($result['data']) > 0) {
                        $keys = array_column($result['data'], 'id');
                        array_multisort($keys, SORT_ASC, $result['data']);

                        foreach ($result['data'] as $key => $value) {

                            $route_id = isset($value['route_master_code']) && $value['route_master_code'] != '' && isset($all_route[strtolower(trim($value['route_master_code']))]) ? $all_route[strtolower(trim($value['route_master_code']))]['id'] : 0;
                            $hub_id = isset($value['hub_code']) && $value['hub_code'] != '' && isset($all_hub[strtolower(trim($value['hub_code']))]) ? $all_hub[strtolower(trim($value['hub_code']))]['id'] : 0;
                            $driver_id = isset($value['user_id']) && $value['user_id'] != '' && isset($all_user[$value['user_id']]) ? $all_user[$value['user_id']]['id'] : 0;
                            $controller_id = isset($value['controller_id']) && $value['controller_id'] != '' && isset($all_user[$value['controller_id']]) ? $all_user[$value['controller_id']]['id'] : 0;

                            $vehicle_detail_id = 0;
                            if ($value['vehical_detail'] == 'self') {
                                $vehicle_detail_id = 1;
                            } else if ($value['vehical_detail'] == 'hired') {
                                $vehicle_detail_id = 2;
                            }

                            $insert_data = array(
                                'migration_id' => $value['id'],
                                'sheet_date' => $value['date'],
                                'route_id' => $route_id,
                                'hub_id' => $hub_id,
                                'driver_id' => $driver_id,
                                'created_date' =>  $value['created_at'],
                                'modified_date' =>  $value['updated_at'],
                                'controller_id' => $controller_id,
                                'vehicle_detail_id' => $vehicle_detail_id,
                                'sheet_cancel' => $value['is_cancel'] == 1 ? 1 : 2,
                                'created_by' => isset($all_user[$value['created_by_id']]) ? $all_user[$value['created_by_id']]['id'] : 0,
                                'modified_by' => isset($all_user[$value['updated_by_id']]) ? $all_user[$value['updated_by_id']]['id'] : 0,
                            );


                            $qry = "SELECT id FROM pick_up_sheets WHERE status IN(1,2) AND migration_id='" . $value['id'] . "'";
                            $qry_exe = $this->db->query($qry);
                            $existData = $qry_exe->row_array();

                            if (isset($existData) && is_array($existData) && count($existData) > 0) {
                                $pick_up_sheets_id = $existData['id'];
                                $this->gm->update('pick_up_sheets', $insert_data, '', array('id' => $pick_up_sheets_id));
                            } else {
                                $pick_up_sheets_id = $this->gm->insert('pick_up_sheets', $insert_data);
                            }

                            $item_array = isset($value['pick_up_sheet_items']) ? $value['pick_up_sheet_items'] : array();

                            if (isset($item_array) && is_array($item_array) && count($item_array) > 0) {
                                $keys = array_column($item_array, 'item_index');
                                array_multisort($keys, SORT_ASC, $item_array);

                                foreach ($item_array as $pkey => $pvalue) {

                                    $qry = "SELECT id FROM pickup_request WHERE status IN(1,2) AND ref_no='" . $pvalue['reference_no'] . "'";
                                    $qry_exe = $this->db->query($qry);
                                    $ref_data = $qry_exe->row_array();

                                    if (isset($ref_data) && is_array($ref_data) && count($ref_data) > 0) {

                                        $customer_id = isset($pvalue['company_code']) && $pvalue['company_code'] != '' && isset($all_cust[strtolower(trim($pvalue['company_code']))]) ? $all_cust[strtolower(trim($pvalue['company_code']))]['id'] : 0;
                                        $pickup_origin_zone = isset($pvalue['origin_zone']) && $pvalue['origin_zone'] != '' && isset($all_zone[strtolower(trim($pvalue['origin_zone']))]) ? $all_zone[strtolower(trim($pvalue['origin_zone']))]['id'] : 0;
                                        $consignee_dest_zone = isset($pvalue['destination_zone']) && $pvalue['destination_zone'] != '' && isset($all_zone[strtolower(trim($pvalue['destination_zone']))]) ? $all_zone[strtolower(trim($pvalue['destination_zone']))]['id'] : 0;


                                        $pickup_request_update = array(
                                            'pickup_sheet_id' => $pick_up_sheets_id,
                                            'contact_person_no' => $pvalue['contact_no'],
                                            'contact_person_name' => $pvalue['contact_person'],
                                            'pickup_status' => $pvalue['is_pick_up_done'] == 1 ? 3 : 1,
                                            'customer_id' => $customer_id,
                                            'pickup_cancel' => $pvalue['is_cancel'] == 1 ? 1 : 2,
                                        );
                                        $this->gm->update('pickup_request', $pickup_request_update, '', array('id' => $ref_data['id']));

                                        $pickup_request_detail_update = array(
                                            'pickup_address1' => $pvalue['address_line_1'],
                                            'pickup_address2' => $pvalue['address_line_2'],
                                            'pickup_address3' => $pvalue['address_line_3'],
                                            'pickup_pincode' => $pvalue['zip_code'],
                                            'pickup_city' => $pvalue['city'],
                                            'total_pieces' => $pvalue['pcs'],
                                            'weight' => $pvalue['weight'],
                                            'total_awb' => $pvalue['awb_no'],
                                            'amount' => $pvalue['amount'],
                                            'remark' => $pvalue['remark'],
                                            'awb_received' => $pvalue['total_awbs_received'],
                                            'pieces_received' => $pvalue['total_pcs_received'],
                                            'vol_weight' => $pvalue['volume_weight'],
                                            'pickup_origin_zone' =>  $pickup_origin_zone,
                                            'consignee_dest_zone' =>  $consignee_dest_zone,
                                            'pickup_time' => date('H:i:s', strtotime($pvalue['pickup_time'])),
                                        );
                                        $this->gm->update('pickup_request_detail', $pickup_request_detail_update, '', array('pickup_request_id' => $ref_data['id']));
                                    }
                                }
                            }

                            //UPDATE OFFSET
                            $qry = "SELECT  id,config_value FROM migration_log WHERE status IN(1,2) AND config_key='pick_up_sheet_migrate_last_id' ";
                            $qry_exe = $this->db->query($qry);
                            $configExist = $qry_exe->row_array();

                            if (isset($configExist) && is_array($configExist) && count($configExist) > 0) {
                                $updateq = "UPDATE migration_log SET config_value='" . $value['id'] . "' WHERE status IN(1,2) AND config_key='pick_up_sheet_migrate_last_id'";
                                $this->db->query($updateq);
                            } else {
                                $mig_insert_data = array(
                                    'config_key' => 'pick_up_sheet_migrate_last_id',
                                    'config_value' => $value['id']
                                );
                                $this->gm->insert('migration_log', $mig_insert_data);
                            }
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
