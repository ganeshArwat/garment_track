<?php
class Transfer_manifest_api extends MX_Controller
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
            $url = $base_url . "api/v5/transfer_manifests/get_transfer_manifest_ids";

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
                    $qry = "SELECT id FROM transfer_manifest WHERE status IN(1,2) AND migration_id='" . $rvalue . "'";
                    $qry_exe = $this->db->query($qry);
                    $existData = $qry_exe->row_array();

                    if (isset($existData) && is_array($existData) && count($existData) > 0) {
                    } else {
                        $insert_data[] = array(
                            'id' => $rvalue,
                            'migration_id' => $rvalue
                        );
                    }
                }

                if (isset($insert_data) && is_array($insert_data) && count($insert_data) > 0) {
                    $this->db->insert_batch('transfer_manifest', $insert_data);
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

            $url = $base_url . "api/v5/transfer_manifests/fetch_transfer_manifests";

            $qry = "SELECT  id,config_value FROM migration_log WHERE status IN(1,2) AND config_key='transfer_manifest_migrate_last_id' ";
            $qry_exe = $this->db->query($qry);
            $offset_res = $qry_exe->row_array();

            $limit = 100;
            if (isset($offset_res['config_value']) && $offset_res['config_value'] != '') {
                $last_id = $offset_res['config_value'];
            } else {
                $last_id = 0;
            }
            $qry = "SELECT migration_id FROM transfer_manifest WHERE migration_id > " . $last_id . " ORDER BY migration_id  LIMIT " . $limit . "";
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

                $all_hub = get_all_hub(" AND status IN(1,2)", 'code');
                $all_co_vendor = get_all_co_vendor(" AND status IN(1,2)", 'code');
                $all_vendor = get_all_vendor(" AND status IN(1,2)", 'code');
                $all_product = get_all_product(" AND status IN(1,2)", 'code');
                $all_location = get_all_location(" AND status IN(1,2)", 'code');
                $all_company = get_all_billing_company(" AND status IN(1,2)", 'id');
                $all_cust = get_all_customer(" AND status IN(1,2)", 'migration_id');
                $all_user = get_all_user(" AND company_id='" . $company_id . "'", "", "migration_id");


                if (isset($result) && is_array($result) && count($result) > 0) {
                    if (isset($result['data']) && is_array($result['data']) && count($result['data']) > 0) {
                        $keys = array_column($result['data'], 'id');
                        array_multisort($keys, SORT_ASC, $result['data']);

                        foreach ($result['data'] as $key => $value) {

                            $co_vendor_id = isset($value['co_vendor_code']) && $value['co_vendor_code'] != '' && isset($all_co_vendor[strtolower(trim($value['co_vendor_code']))]) ? $all_co_vendor[strtolower(trim($value['co_vendor_code']))]['id'] : 0;
                            $vendor_id = isset($value['vendor_code']) && $value['vendor_code'] != '' && isset($all_vendor[strtolower(trim($value['vendor_code']))]) ? $all_vendor[strtolower(trim($value['vendor_code']))]['id'] : 0;

                            $origin_hub_id = isset($value['origin_hub_code']) && $value['origin_hub_code'] != '' && isset($all_hub[strtolower(trim($value['origin_hub_code']))]) ? $all_hub[strtolower(trim($value['origin_hub_code']))]['id'] : 0;
                            $dest_hub_id = isset($value['destination_hub_code']) && $value['destination_hub_code'] != '' && isset($all_hub[strtolower(trim($value['destination_hub_code']))]) ? $all_hub[strtolower(trim($value['destination_hub_code']))]['id'] : 0;

                            $company_id = isset($value['company_id']) && $value['company_id'] != '' && isset($all_company[strtolower(trim($value['company_id']))]) ? $all_company[strtolower(trim($value['company_id']))]['id'] : 0;

                            $insert_data = array(
                                'migration_id' => $value['id'],
                                'manifest_no' => $value['manifest_no'],
                                'created_date' =>  $value['created_at'],
                                'modified_date' =>  $value['updated_at'],
                                'manifest_date' => $value['manifest_date'],
                                'vendor_id' => $vendor_id,
                                'co_vendor_id' => $co_vendor_id,
                                'total_bags_count' => $value['no_of_bags'],
                                'weight_total' => $value['weight'],
                                'ori_hub_id' => $origin_hub_id,
                                'dest_hub_id' => $dest_hub_id,
                                'arrival_date' => $value['arrival_date'],
                                'vendor_cd_no' => $value['vendor_cd_no'],
                                'company_id' => $company_id,
                                'created_by_type' => isset($value['created_by_type']) && $value['created_by_type'] == 'WebPortal' ? 2 : 1,
                                'created_by' => isset($all_user[$value['created_by_id']]) ? $all_user[$value['created_by_id']]['id'] : 0,
                                'modified_by' => isset($all_user[$value['updated_by_id']]) ? $all_user[$value['updated_by_id']]['id'] : 0,
                            );

                            // if (isset($value['created_by_type']) && $value['created_by_type'] == 'WebPortal') {
                            //     if (isset($all_cust[$value['created_by_id']])) {
                            //         $insert_data['created_by'] = $all_cust[$value['created_by_id']]['id'];
                            //     }
                            // }

                            $qry = "SELECT id FROM transfer_manifest WHERE status IN(1,2) AND migration_id='" . $value['id'] . "'";
                            $qry_exe = $this->db->query($qry);
                            $existData = $qry_exe->row_array();

                            if (isset($existData) && is_array($existData) && count($existData) > 0) {
                                $manifest_id = $existData['id'];
                                $this->gm->update('transfer_manifest', $insert_data, '', array('id' => $manifest_id));
                            } else {
                                $manifest_id = $this->gm->insert('transfer_manifest', $insert_data);
                            }

                            if (isset($value['transfer_manifest_items']) && is_array($value['transfer_manifest_items']) && count($value['transfer_manifest_items']) > 0) {
                                foreach ($value['transfer_manifest_items'] as $pkey => $pvalue) {

                                    $vendor_id = isset($pvalue['vendor']) && $pvalue['vendor'] != '' && isset($all_vendor[strtolower(trim($pvalue['vendor']))]) ? $all_vendor[strtolower(trim($pvalue['vendor']))]['id'] : 0;
                                    $product_id = isset($pvalue['product']) && $pvalue['product'] != '' && isset($all_product[strtolower(trim($pvalue['product']))]) ? $all_product[strtolower(trim($pvalue['product']))]['id'] : 0;
                                    $destination_id = isset($pvalue['destination_code']) && $pvalue['destination_code'] != '' && isset($all_location[strtolower(trim($pvalue['destination_code']))]) ? $all_location[strtolower(trim($pvalue['destination_code']))]['id'] : 0;


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

                                    $qry = "SELECT id FROM docket WHERE status IN(1,2) AND awb_no='" . $pvalue['tracking_no'] . "'";
                                    $qry_exe = $this->db->query($qry);
                                    $docketData = $qry_exe->row_array();

                                    $transfer_manifest_items = array(
                                        'migration_id' => $pvalue['id'],
                                        'docket_id' => isset($docketData['id']) ? $docketData['id'] : 0,
                                        'docket_item_id' => isset($docketItemData['id']) ? $docketItemData['id'] : 0,
                                        'transfer_manifest_id' => $manifest_id,
                                        'created_date' =>  $pvalue['created_at'],
                                        'modified_date' =>  $pvalue['updated_at'],
                                        'awb_no' => $pvalue['track_by_no'],
                                        'chargeable_wt' =>  $pvalue['weight'],
                                        'bag_no' =>  $pvalue['bag_no'],
                                        'total_pcs' =>  $pvalue['pcs'],
                                        'con_name' => $pvalue['consignee'],
                                        'vendor_id' => $vendor_id,
                                        'product_id' => $product_id,
                                        'destination_id' => $destination_id,
                                        'track_by_type' => $track_by_type,
                                        'created_by' => isset($all_user[$value['created_by_id']]) ? $all_user[$value['created_by_id']]['id'] : 0,
                                        'modified_by' => isset($all_user[$value['updated_by_id']]) ? $all_user[$value['updated_by_id']]['id'] : 0,
                                    );

                                    $qry = "SELECT id FROM transfer_manifest_docket WHERE status IN(1,2) AND migration_id='" . $pvalue['id'] . "'";
                                    $qry_exe = $this->db->query($qry);
                                    $existData = $qry_exe->row_array();

                                    if (isset($existData) && is_array($existData) && count($existData) > 0) {
                                        $this->gm->update('transfer_manifest_docket', $transfer_manifest_items, '', array('migration_id' => $pvalue['id']));
                                    } else {
                                        $this->gm->insert('transfer_manifest_docket', $transfer_manifest_items);
                                    }
                                }
                            }

                            //UPDATE OFFSET
                            $qry = "SELECT  id,config_value FROM migration_log WHERE status IN(1,2) AND config_key='transfer_manifest_migrate_last_id' ";
                            $qry_exe = $this->db->query($qry);
                            $configExist = $qry_exe->row_array();

                            if (isset($configExist) && is_array($configExist) && count($configExist) > 0) {
                                $updateq = "UPDATE migration_log SET config_value='" . $value['id'] . "' WHERE status IN(1,2) AND config_key='transfer_manifest_migrate_last_id'";
                                $this->db->query($updateq);
                            } else {
                                $mig_insert_data = array(
                                    'config_key' => 'transfer_manifest_migrate_last_id',
                                    'config_value' => $value['id']
                                );
                                $this->gm->insert('migration_log', $mig_insert_data);
                            }
                            echo "<br>Transfer MANIFEST NO." . $value['manifest_no'] . " added";
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
