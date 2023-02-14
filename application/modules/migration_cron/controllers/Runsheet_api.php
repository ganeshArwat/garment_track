<?php
class Runsheet_api extends MX_Controller
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
            $url = $base_url . "api/v5/run_sheets/get_run_sheet_ids";

            $request_json[] = array();
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
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
            $result = json_decode($response_json, true);

            if (isset($result['data']) && is_array($result['data']) && count($result['data']) > 0) {
                foreach ($result['data'] as $rkey => $rvalue) {
                    $qry = "SELECT id FROM run_sheet WHERE status IN(1,2) AND migration_id='" . $rvalue . "'";
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
                    $this->db->insert_batch('run_sheet', $insert_data);
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

            $url = $base_url . "api/v5/run_sheets/fetch_run_sheets";

            $qry = "SELECT  id,config_value FROM migration_log WHERE status IN(1,2) AND config_key='drs_migrate_last_id' ";
            $qry_exe = $this->db->query($qry);
            $offset_res = $qry_exe->row_array();

            $limit = 100;
            if (isset($offset_res['config_value']) && $offset_res['config_value'] != '') {
                $last_id = $offset_res['config_value'];
            } else {
                $last_id = 0;
            }
            $qry = "SELECT migration_id FROM run_sheet WHERE migration_id > " . $last_id . " ORDER BY migration_id  LIMIT " . $limit . "";
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
                $all_user = get_all_user(" AND status IN(1,2) ", "migration_id");
                $all_reason =  get_all_undeliver_reason(" AND status IN(1,2) ", "reason_desc");
                $all_user = get_all_user(" AND company_id='" . $company_id . "'", "", "migration_id");


                if (isset($result) && is_array($result) && count($result) > 0) {
                    if (isset($result['data']) && is_array($result['data']) && count($result['data']) > 0) {
                        $keys = array_column($result['data'], 'id');
                        array_multisort($keys, SORT_ASC, $result['data']);

                        foreach ($result['data'] as $key => $value) {

                            $route_id = isset($value['route_master_code']) && isset($all_route[strtolower(trim($value['route_master_code']))]) ? $all_route[strtolower(trim($value['route_master_code']))]['id'] : 0;
                            $hub_id = isset($value['hub_code']) && isset($all_hub[strtolower(trim($value['hub_code']))]) ? $all_hub[strtolower(trim($value['hub_code']))]['id'] : 0;
                            $user_id = isset($value['delivery_guy_id']) && isset($all_user[strtolower(trim($value['delivery_guy_id']))]) ? $all_user[strtolower(trim($value['delivery_guy_id']))]['id'] : 0;

                            $insert_data = array(
                                'migration_id' => $value['id'],
                                'run_sheet_no' => $value['number'],
                                'created_date' =>  $value['created_at'],
                                'modified_date' =>  $value['updated_at'],
                                'drs_date' =>  $value['drs_date'],
                                'route_id' => $route_id,
                                'hub_id' => $hub_id,
                                'drs_time' => date('H:i:s', strtotime($value['drs_time'])),
                                'vehicle_no' => $value['vehicle_number'],
                                'vehicle_type' => $value['vehicle_type'],
                                'driver_name' => $value['driver_name'],
                                'user_id' => $user_id,
                                'created_by' => isset($all_user[$value['created_by_id']]) ? $all_user[$value['created_by_id']]['id'] : 0,
                                'modified_by' => isset($all_user[$value['updated_by_id']]) ? $all_user[$value['updated_by_id']]['id'] : 0,
                            );
                            $qry = "SELECT id FROM run_sheet WHERE status IN(1,2) AND migration_id='" . $value['id'] . "'";
                            $qry_exe = $this->db->query($qry);
                            $existData = $qry_exe->row_array();

                            if (isset($existData) && is_array($existData) && count($existData) > 0) {
                                $run_sheet_id = $existData['id'];
                                $this->gm->update('run_sheet', $insert_data, '', array('id' => $run_sheet_id));
                            } else {
                                $run_sheet_id = $this->gm->insert('run_sheet', $insert_data);
                            }


                            $item_array = isset($value['run_sheet_items']) ? $value['run_sheet_items'] : array();
                            if (isset($item_array) && is_array($item_array) && count($item_array) > 0) {
                                $keys = array_column($item_array, 'item_index');
                                array_multisort($keys, SORT_ASC, $item_array);

                                foreach ($item_array as $pkey => $pvalue) {
                                    $reason_id = isset($pvalue['undelivered_remark']) && $pvalue['undelivered_remark'] != '' && isset($all_reason[strtolower(trim($pvalue['undelivered_remark']))]) ? $all_reason[strtolower(trim($pvalue['undelivered_remark']))]['id'] : 0;

                                    $docketItemData = array();
                                    $track_by_type = 0;
                                    if ($pvalue['track_by'] == 'tracking_no') {
                                        $track_by_type = 1;
                                    } else if ($pvalue['track_by'] == 'parcel_no') {
                                        $track_by_type = 2;

                                        $qry = "SELECT id FROM docket_items WHERE status IN(1,2) AND parcel_no='" . $pvalue['track_by_no'] . "'";
                                        $qry_exe = $this->db->query($qry);
                                        $docketItemData = $qry_exe->row_array();
                                    } else if ($pvalue['track_by'] == 'forwording_no') {
                                        $track_by_type = 3;
                                    }

                                    $run_state_id = 0;
                                    if ($pvalue['delivery_state'] == 'new') {
                                        $run_state_id = 1;
                                    } else if ($pvalue['delivery_state'] == 'delivered') {
                                        $run_state_id = 2;
                                    } else if ($pvalue['delivery_state'] == 'undelivered') {
                                        $run_state_id = 3;
                                    } else if ($pvalue['delivery_state'] == 'rto') {
                                        $run_state_id = 4;
                                    }

                                    $qry = "SELECT id FROM docket WHERE status IN(1,2) AND awb_no='" . $pvalue['tracking_no'] . "'";
                                    $qry_exe = $this->db->query($qry);
                                    $docketData = $qry_exe->row_array();

                                    $manifest_items = array(
                                        'migration_id' => $pvalue['id'],
                                        'run_sheet_id' => $run_sheet_id,
                                        'created_date' =>  $pvalue['created_at'],
                                        'modified_date' =>  $pvalue['updated_at'],
                                        'awb_no' => $pvalue['track_by_no'],
                                        'reason_id' => $reason_id,
                                        'receiver_name' => $pvalue['receiver'],
                                        'delivery_date' =>  $pvalue['delivery_date'],
                                        'remark' =>  $pvalue['remarks'],
                                        'delivery_time' => date('H:i:s', strtotime($pvalue['delivery_time'])),
                                        'track_by_type' => $track_by_type,
                                        'run_state_id' => $run_state_id,
                                        'docket_id' => isset($docketData['id']) ? $docketData['id'] : 0,
                                        'docket_item_id' => isset($docketItemData['id']) ? $docketItemData['id'] : 0,
                                    );

                                    $qry = "SELECT id FROM run_sheet_docket WHERE status IN(1,2) AND migration_id='" . $pvalue['id'] . "'";
                                    $qry_exe = $this->db->query($qry);
                                    $existData = $qry_exe->row_array();
                                    if (isset($existData) && is_array($existData) && count($existData) > 0) {
                                        $this->gm->update('run_sheet_docket', $manifest_items, '', array('migration_id' => $pvalue['id']));
                                    } else {
                                        $this->gm->insert('run_sheet_docket', $manifest_items);
                                    }
                                }
                            }

                            //UPDATE OFFSET
                            $qry = "SELECT  id,config_value FROM migration_log WHERE status IN(1,2) AND config_key='drs_migrate_last_id' ";
                            $qry_exe = $this->db->query($qry);
                            $configExist = $qry_exe->row_array();

                            if (isset($configExist) && is_array($configExist) && count($configExist) > 0) {
                                $updateq = "UPDATE migration_log SET config_value='" . $value['id'] . "' WHERE status IN(1,2) AND config_key='drs_migrate_last_id'";
                                $this->db->query($updateq);
                            } else {
                                $mig_insert_data = array(
                                    'config_key' => 'drs_migrate_last_id',
                                    'config_value' => $value['id']
                                );
                                $this->gm->insert('migration_log', $mig_insert_data);
                            }
                            echo "<br>RUN SHEET NO." . $value['number'] . " added";
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
