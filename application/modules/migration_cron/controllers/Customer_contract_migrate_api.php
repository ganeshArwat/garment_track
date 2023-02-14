<?php
class Customer_contract_migrate_api extends MX_Controller
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
            $url = $base_url . "api/v5/customer_contracts/get_customer_contract_ids";

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
                    $qry = "SELECT id FROM customer_contract WHERE status IN(1,2) AND migration_id='" . $rvalue . "'";
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
                    $this->db->insert_batch('customer_contract', $insert_data);
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

    public function get_api_data($company_name = '')
    {
        $time_start = microtime(true);
        $this->load->helper('url');
        $this->load->helper('frontend_common');

        // $company_data = $this->config->item('company_base_url');
        // $base_url = isset($company_data[$company_name]) ? $company_data[$company_name] : '';
        $sessiondata = $this->session->userdata('admin_user');
        $company_id = isset($sessiondata['com_id']) ? $sessiondata['com_id'] : '';
        $main_db = $this->load->database('main_db', true);
        $qry = "SELECT  id,old_domain FROM company WHERE status IN(1,2) AND id='" . $company_id . "'";
        $qry_exe = $main_db->query($qry);
        $com_res = $qry_exe->row_array();
        $base_url = isset($com_res['old_domain']) ? $com_res['old_domain'] : '';

        if ($base_url != '') {
            $api_url = $base_url . "api/v5/customer_contracts/fetch_customer_contracts";


            $qry = "SELECT  id,config_value FROM migration_log WHERE status IN(1,2) AND config_key='customer_contract_migrate_last_id' ";
            $qry_exe = $this->db->query($qry);
            $offset_res = $qry_exe->row_array();

            $limit = 500;
            if (isset($offset_res['config_value']) && $offset_res['config_value'] != '') {
                $last_id = $offset_res['config_value'];
            } else {
                $last_id = 0;
            }
            $qry = "SELECT migration_id FROM customer_contract WHERE migration_id > " . $last_id . " ORDER BY migration_id  LIMIT " . $limit . "";
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
                curl_setopt($ch, CURLOPT_URL, $api_url);
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
            }
        } else {
            echo "SET OLD SOFTWARE DOMAIN IN COMPANY";
            exit;
        }

        if (isset($result) && is_array($result) && count($result) > 0) {

            $all_customer = get_all_customer(" AND status IN(1,2) ", "code");
            $all_vendor = get_all_vendor(" AND status IN(1,2) ", "code");
            $all_product = get_all_product(" AND status IN(1,2) ", "code");
            $all_co_vendor = get_all_co_vendor(" AND status IN(1,2) ", "code");
            $all_zone = get_all_zone(" AND status IN(1,2) ", "code");
            $all_location = get_all_location(" AND status IN(1,2) ", "code");
            $all_city = get_all_city(" AND status IN(1,2) ", "code");
            $all_user = get_all_user(" AND company_id='" . $company_id . "'", "", "migration_id");

            $this->load->module('customer_contracts');

            if (isset($result['data']) && is_array($result['data']) && count($result['data']) > 0) {

                $keys = array_column($result['data'], 'id');
                array_multisort($keys, SORT_ASC, $result['data']);

                foreach ($result['data'] as $key => $value) {


                    $customer_insert = array();

                    $customer_id =  isset($value['customer_code']) && isset($all_customer[strtolower(trim($value['customer_code']))]) ? $all_customer[strtolower(trim($value['customer_code']))]['id'] : 0;
                    $vendor_id =  isset($value['vendor_code']) && isset($all_vendor[strtolower(trim($value['vendor_code']))]) ? $all_vendor[strtolower(trim($value['vendor_code']))]['id'] : 0;
                    $co_vendor_id =  isset($value['co_vendor_code']) && isset($all_co_vendor[strtolower(trim($value['co_vendor_code']))]) ? $all_co_vendor[strtolower(trim($value['co_vendor_code']))]['id'] : 0;
                    $ori_location_id =  isset($value['origin_code']) && isset($all_location[strtolower(trim($value['origin_code']))]) ? $all_location[strtolower(trim($value['origin_code']))]['id'] : 0;
                    $dest_location_id =  isset($value['destination_code']) && isset($all_location[strtolower(trim($value['destination_code']))]) ? $all_location[strtolower(trim($value['destination_code']))]['id'] : 0;
                    $product_id =  isset($value['product_code']) && isset($all_product[strtolower(trim($value['product_code']))]) ? $all_product[strtolower(trim($value['product_code']))]['id'] : 0;
                    $dest_zone_id =  isset($value['zone_code']) && isset($all_zone[strtolower(trim($value['zone_code']))]) ? $all_zone[strtolower(trim($value['zone_code']))]['id'] : 0;
                    $ori_zone_id =  isset($value['origin_zone_code']) && isset($all_zone[strtolower(trim($value['origin_zone_code']))]) ? $all_zone[strtolower(trim($value['origin_zone_code']))]['id'] : 0;
                    $ori_city_id =  isset($value['origin_city']) && isset($all_city[strtolower(trim($value['origin_city']))]) ? $all_city[strtolower(trim($value['origin_city']))]['id'] : 0;
                    $dest_city_id =  isset($value['destination_city']) && isset($all_city[strtolower(trim($value['destination_city']))]) ? $all_city[strtolower(trim($value['destination_city']))]['id'] : 0;

                    $method_id = 0;
                    if ($value['calculation_method'] == 'fixed') {
                        $method_id = 1;
                    } else if ($value['calculation_method'] == 'slabwise') {
                        $method_id = 2;
                    }
                    $customer_insert['contract'] = array(
                        'migration_id' => $value['id'],
                        'effective_min' =>  $value['effective_from'],
                        'effective_max' =>  $value['effective_till'],
                        'customer_id' => $customer_id,
                        'vendor_id' => $vendor_id,
                        'ori_location_id' => $ori_location_id,
                        'product_id' => $product_id,
                        'dest_zone_id' => $dest_zone_id,
                        'dest_location_id' => $dest_location_id,
                        'remark' =>  $value['remarks'] != NULL ? $value['remarks'] : '',
                        'method_id' => $method_id,
                        'created_date' =>  $value['created_at'],
                        'modified_date' =>  $value['updated_at'],
                        'co_vendor_id' => $co_vendor_id,
                        'ori_zone_id' => $ori_zone_id,
                        'ori_city_id' => $ori_city_id,
                        'dest_city_id' => $dest_city_id,
                        'customer_id' => $customer_id,
                        'tat' =>  $value['tat'],
                        'status' =>  1,
                        'created_by' => isset($all_user[$value['created_by_id']]) ? $all_user[$value['created_by_id']]['id'] : 0,
                        'modified_by' => isset($all_user[$value['updated_by_id']]) ? $all_user[$value['updated_by_id']]['id'] : 0,
                    );

                    $qry = "SELECT id FROM customer_contract WHERE status IN(1,2) AND migration_id='" . $value['id'] . "'";
                    $qry_exe = $this->db->query($qry);
                    $existData = $qry_exe->row_array();

                    $old_rule = array();
                    if (isset($existData) && is_array($existData) && count($existData) > 0) {
                        $qry = "SELECT id,migration_id FROM customer_contract_rate WHERE status IN(1,2) AND customer_contract_id='" . $existData['id'] . "'";
                        $qry_exe = $this->db->query($qry);
                        $ruleexistData = $qry_exe->row_array();
                        if (isset($ruleexistData) && is_array($ruleexistData) && count($ruleexistData) > 0) {
                            foreach ($ruleexistData as $okey => $ovalue) {
                                $old_rule[$ovalue['migration_id']] = $ovalue['id'];
                            }
                        }
                    }
                    if (isset($value['customer_contract_rules']) && is_array($value['customer_contract_rules']) && count($value['customer_contract_rules']) > 0) {
                        foreach ($value['customer_contract_rules'] as $ckey => $cvalue) {
                            $customer_insert['map_id'][] = isset($old_rule[$cvalue['id']]) ? $old_rule[$cvalue['id']] : 0;
                            $customer_insert['rate_migration_id'][] = $cvalue['id'];
                            $customer_insert['on_add'][] = $cvalue['on_additional'];
                            $customer_insert['lower_wt'][] = $cvalue['lower_weight'];
                            $customer_insert['upper_wt'][] = $cvalue['upper_weight'];
                            $customer_insert['rate'][] = $cvalue['rate'];
                        }
                    }

                    if (isset($existData) && is_array($existData) && count($existData) > 0) {
                        $customer_insert['contract_id'] = $existData['id'];
                        $this->customer_contracts->update($customer_insert);
                    } else {
                        $this->customer_contracts->insert($customer_insert);
                    }


                    //UPDATE OFFSET
                    $qry = "SELECT  id,config_value FROM migration_log WHERE status IN(1,2) AND config_key='customer_contract_migrate_last_id' ";
                    $qry_exe = $this->db->query($qry);
                    $configExist = $qry_exe->row_array();

                    if (isset($configExist) && is_array($configExist) && count($configExist) > 0) {
                        $updateq = "UPDATE migration_log SET config_value='" . $value['id'] . "' WHERE status IN(1,2) AND config_key='customer_contract_migrate_last_id'";
                        $this->db->query($updateq);
                    } else {
                        $mig_insert_data = array(
                            'config_key' => 'customer_contract_migrate_last_id',
                            'config_value' => $value['id']
                        );
                        $this->gm->insert('migration_log', $mig_insert_data);
                    }
                    echo "<br>CUSTOMER CONTRACT added";
                }
            }
        }

        $time_end = microtime(true);
        $execution_time = ($time_end - $time_start) / 60;

        //execution time of the script
        echo '<b>Total Execution Time:</b> ' . $execution_time . ' Mins';
    }
}
