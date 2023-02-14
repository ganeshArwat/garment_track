<?php
class Location_migrate_api extends MX_Controller
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
            $url = $base_url . "api/v5/location_masters/get_location_master_ids";

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

                $start = isset($get['start']) ? $get['start'] : 0;
                $slice_data = array_slice($result['data'], $start, 20000, TRUE);
                $slice_data = $result['data'];
                if (isset($slice_data) && is_array($slice_data) && count($slice_data) > 0) {


                    foreach ($slice_data as $rkey => $rvalue) {
                        $qry = "SELECT id FROM location WHERE status IN(1,2) AND migration_id='" . $rvalue . "'";
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
                }
            }
        }

        if (isset($insert_data) && is_array($insert_data) && count($insert_data) > 0) {
            $this->db->insert_batch('location', $insert_data);
            echo count($insert_data) . " ID INSERTED";
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
        $this->load->model('Global_model', 'gm');
        $sessiondata = $this->session->userdata('admin_user');
        $company_id = isset($sessiondata['com_id']) ? $sessiondata['com_id'] : '';
        $main_db = $this->load->database('main_db', true);
        $qry = "SELECT  id,old_domain FROM company WHERE status IN(1,2) AND id='" . $company_id . "'";
        $qry_exe = $main_db->query($qry);
        $com_res = $qry_exe->row_array();
        $base_url = isset($com_res['old_domain']) ? $com_res['old_domain'] : '';
        //$base_url = 'http://139.59.12.87/';
        if ($base_url != '') {
            $url = $base_url . "api/v5/location_masters/fetch_location_masters";


            $qry = "SELECT  id,config_value FROM migration_log WHERE status IN(1,2) AND config_key='location_migrate_last_id' ";
            $qry_exe = $this->db->query($qry);
            $offset_res = $qry_exe->row_array();

            $limit = 500;
            if (isset($offset_res['config_value']) && $offset_res['config_value'] != '') {
                $last_id = $offset_res['config_value'];
            } else {
                $last_id = 0;
            }
            $qry = "SELECT migration_id FROM location WHERE migration_id > " . $last_id . " ORDER BY migration_id  LIMIT " . $limit . "";
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

                if (isset($result['error']) && $result['error'] != '') {
                    echo $result['error'];
                    exit;
                }
            }
        }

        if (isset($result) && is_array($result) && count($result) > 0) {
            $all_city = get_all_city(" AND status IN(1,2) ", "code");
            $all_hub = get_all_hub(" AND status IN(1,2) ", "code");
            $all_zone = get_all_zone(" AND status IN(1,2) ", "code");
            $all_vendor = get_all_vendor(" AND status IN(1,2) ", "code");
            $all_user = get_all_user(" AND company_id='" . $company_id . "'", "", "migration_id");

            $qry = "SELECT id,name,code,is_gst_apply,gst_no,gst_per,is_sale_vendor FROM co_vendor WHERE status IN(1,2) ";
            $qry_exe = $this->db->query($qry);
            $com_res = $qry_exe->result_array();
            if (isset($com_res) && is_array($com_res) && count($com_res) > 0) {
                foreach ($com_res as $key => $value) {
                    $all_co_vendor[strtolower(trim($value['code']))] = $value;
                }
            }



            $this->load->module('location_masters');

            if (isset($result['data']) && is_array($result['data']) && count($result['data']) > 0) {

                $keys = array_column($result['data'], 'id');
                array_multisort($keys, SORT_ASC, $result['data']);

                foreach ($result['data'] as $key => $value) {

                    $customer_insert = array();
                    $city_id = isset($value['city_code']) && isset($all_city[strtolower(trim($value['city_code']))]) ? $all_city[strtolower(trim($value['city_code']))]['id'] : 0;
                    $franchise_id = isset($value['hub_code']) && isset($all_hub[strtolower(trim($value['hub_code']))]) ? $all_hub[strtolower(trim($value['hub_code']))]['id'] : 0;
                    $branch_id = isset($value['branch_hub_code']) && isset($all_hub[strtolower(trim($value['branch_hub_code']))]) ? $all_hub[strtolower(trim($value['branch_hub_code']))]['id'] : 0;
                    $state_hub_id = isset($value['state_capital_hub_code']) && isset($all_hub[strtolower(trim($value['state_capital_hub_code']))]) ? $all_hub[strtolower(trim($value['state_capital_hub_code']))]['id'] : 0;
                    $zonal_hub_id = isset($value['zone_hub_code']) && isset($all_hub[strtolower(trim($value['zone_hub_code']))]) ? $all_hub[strtolower(trim($value['zone_hub_code']))]['id'] : 0;
                    $routing_hub_id = isset($value['routing_hub_code']) && isset($all_hub[strtolower(trim($value['routing_hub_code']))]) ? $all_hub[strtolower(trim($value['routing_hub_code']))]['id'] : 0;
                    $master_dictrict_hub_id = isset($value['master_district_hub_code']) && isset($all_hub[strtolower(trim($value['master_district_hub_code']))]) ? $all_hub[strtolower(trim($value['master_district_hub_code']))]['id'] : 0;

                    $location_type = 5;
                    if ($value['is_pincode'] == true) {
                        $location_type = 2;
                    }
                    $customer_insert['location'] = array(
                        'migration_id' => $value['id'],
                        'name' => $value['name'],
                        'created_date' =>  $value['created_at'],
                        'modified_date' =>  $value['updated_at'],
                        'code' => $value['code'],
                        'location_type' => $location_type,
                        'city_id' => $city_id,
                        'franchise_id' => $franchise_id,
                        'branch_id' => $branch_id,
                        'state_hub_id' => $state_hub_id,
                        'zonal_hub_id' => $zonal_hub_id,
                        'routing_hub_id' => $routing_hub_id,
                        'master_dictrict_hub_id' => $master_dictrict_hub_id,
                        'status' =>  $value['is_active'] == true ? 1 : 2,
                        'created_by' => isset($all_user[$value['created_by_id']]) ? $all_user[$value['created_by_id']]['id'] : 0,
                        'modified_by' => isset($all_user[$value['updated_by_id']]) ? $all_user[$value['updated_by_id']]['id'] : 0,
                    );



                    $qry = "SELECT id FROM location WHERE status IN(1,2) AND migration_id='" . $value['id'] . "'";
                    $qry_exe = $this->db->query($qry);
                    $existData = $qry_exe->row_array();

                    $old_rule = array();
                    if (isset($existData) && is_array($existData) && count($existData) > 0) {
                        $qry = "SELECT id,migration_id FROM location_zone_map WHERE status IN(1,2) AND location_id='" . $existData['id'] . "'";
                        $qry_exe = $this->db->query($qry);
                        $ruleexistData = $qry_exe->result_array();
                        if (isset($ruleexistData) && is_array($ruleexistData) && count($ruleexistData) > 0) {
                            foreach ($ruleexistData as $okey => $ovalue) {
                                $old_rule[$ovalue['migration_id']] = $ovalue['id'];
                            }
                        }
                    }


                    if (isset($value['location_zone_mappings']) && is_array($value['location_zone_mappings']) && count($value['location_zone_mappings']) > 0) {
                        $quote_replace_field1 = array(
                            'vendor_code', 'co_vendor_code', 'co_vendor_code', 'zone_code'
                        );
                        foreach ($value['location_zone_mappings'] as $lkey => $lvalue) {

                            if (isset($quote_replace_field1) && is_array($quote_replace_field1) && count($quote_replace_field1) > 0) {
                                foreach ($quote_replace_field1 as $q1key => $q1value) {
                                    if (isset($lvalue[$q1value])) {
                                        $lvalue[$q1value] = str_replace(array("'", "\""), "", $lvalue[$q1value]);
                                    }
                                }
                            }


                            $vendor_id = isset($lvalue['vendor_code']) && isset($all_vendor[strtolower(trim($lvalue['vendor_code']))]) ? $all_vendor[strtolower(trim($lvalue['vendor_code']))]['id'] : 0;
                            $co_vendor_id = isset($lvalue['co_vendor_code']) && isset($all_co_vendor[strtolower(trim($lvalue['co_vendor_code']))]) ? $all_co_vendor[strtolower(trim($lvalue['co_vendor_code']))]['id'] : 0;
                            $is_sale_vendor = isset($lvalue['co_vendor_code']) && isset($all_co_vendor[strtolower(trim($lvalue['co_vendor_code']))]) ? $all_co_vendor[strtolower(trim($lvalue['co_vendor_code']))]['is_sale_vendor'] : 0;
                            $zone_id = isset($lvalue['zone_code']) && isset($all_zone[strtolower(trim($lvalue['zone_code']))]) ? $all_zone[strtolower(trim($lvalue['zone_code']))]['id'] : 0;

                            if ($zone_id == 0) {
                                //ADD ZONE
                                $zone_insert = array(
                                    'name' => $lvalue['zone_code'],
                                    'code' => $lvalue['zone_code'],
                                    'created_date' => date('Y-m-d H:i:s')
                                );
                                $zone_id = $this->gm->insert('zone', $zone_insert);
                                $all_zone[strtolower(trim($lvalue['zone_code']))]['id'] = $zone_id;
                            }
                            if ($zone_id > 0) {
                                $customer_insert['map_id'][] = isset($old_rule[$lvalue['id']]) ? $old_rule[$lvalue['id']] : 0;
                                $customer_insert['rate_migration_id'][] = $lvalue['id'];
                                $customer_insert['map_id'][] = 0;
                                $customer_insert['zone'][] = $zone_id;

                                if ($is_sale_vendor == 1) {
                                    $customer_insert['billing_type'][] = 3;
                                } else {
                                    $customer_insert['billing_type'][] = 1;
                                }

                                $customer_insert['vendor'][] = $vendor_id;
                                $customer_insert['co_vendor'][] = $co_vendor_id;
                                $customer_insert['min_date'][] = $lvalue['effective_from'];
                                $customer_insert['max_date'][] = $lvalue['effective_till'];
                            }
                        }
                    }



                    if (isset($existData) && is_array($existData) && count($existData) > 0) {
                        $customer_insert['location_id'] = $existData['id'];
                        $this->location_masters->update($customer_insert);
                    } else {
                        $this->location_masters->insert($customer_insert);
                    }


                    //UPDATE OFFSET
                    $qry = "SELECT  id,config_value FROM migration_log WHERE status IN(1,2) AND config_key='location_migrate_last_id' ";
                    $qry_exe = $this->db->query($qry);
                    $configExist = $qry_exe->row_array();

                    if (isset($configExist) && is_array($configExist) && count($configExist) > 0) {
                        $updateq = "UPDATE migration_log SET config_value='" . $value['id'] . "' WHERE status IN(1,2) AND config_key='location_migrate_last_id'";
                        $this->db->query($updateq);
                    } else {
                        $mig_insert_data = array(
                            'config_key' => 'location_migrate_last_id',
                            'config_value' => $value['id']
                        );
                        $this->gm->insert('migration_log', $mig_insert_data);
                    }

                    echo "<br>LOCATION =" . $value['code'] . " added";
                }
            }
            $time_end = microtime(true);
            $execution_time = ($time_end - $time_start) / 60;

            //execution time of the script
            echo '<b>Total Execution Time:</b> ' . $execution_time . ' Mins';
        }
    }
}
