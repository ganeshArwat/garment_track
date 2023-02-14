<?php
class Rate_modifier_migrate_api extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
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
            $api_url = $base_url . "api/v5/rate_modifiers";
            $json_data = @file_get_contents($api_url);
            $json_data =  str_replace(array("'"), "", $json_data);
            $result = json_decode($json_data, TRUE);
        } else {
            echo "SET OLD SOFTWARE DOMAIN IN COMPANY";
            exit;
        }


        if (isset($result) && is_array($result) && count($result) > 0) {
            $all_customer = get_all_customer(" AND status IN(1,2) ", "code");
            $all_vendor = get_all_vendor(" AND status IN(1,2) ", "code");
            $all_product = get_all_product(" AND status IN(1,2) ", "code");
            $all_location = get_all_location(" AND status IN(1,2) ", "code");
            $all_zone = get_all_zone(" AND status IN(1,2) ", "code");
            $all_co_vendor = get_all_co_vendor(" AND status IN(1,2) ", "code");
            $all_charge = get_all_charge(" AND status IN(1,2) ", "id,name,migration_id", "migration_id");
            $all_user = get_all_user(" AND company_id='" . $company_id . "'", "", "migration_id");

            $this->load->module('rate_modifiers');

            if (isset($result['data']) && is_array($result['data']) && count($result['data']) > 0) {

                $keys = array_column($result['data'], 'id');
                array_multisort($keys, SORT_ASC, $result['data']);


                foreach ($result['data'] as $key => $value) {
                    $customer_insert = array();

                    $billing_type = 0;
                    if ($value['billing_type'] == 'sale') {
                        $billing_type = 3;
                    } else if ($value['billing_type'] == 'purchase') {
                        $billing_type = 2;
                    }
                    $rate_per_type = 0;
                    if ($value['rate_per_pcs'] == 1) {
                        $rate_per_type = 1;
                    } else if ($value['rate_per_kg'] == 1) {
                        $rate_per_type = 2;
                    } else if ($value['rate_per_half_kg'] == 1) {
                        $rate_per_type = 3;
                    }
                    $customer_insert['rate_mod'] = array(
                        'id' => $value['id'],
                        'migration_id' => $value['id'],
                        'created_date' =>  $value['created_at'],
                        'modified_date' =>  $value['updated_at'],
                        'fixed_amt' =>  $value['fixed_amount'],
                        'freight_per' =>  $value['percentage_on_freight'],
                        'min_chargeable_wt' =>  $value['min_weight'],
                        'min_dimension' =>  $value['min_dimension'],
                        'min_per_box_wt' =>  $value['min_per_box_weight'],
                        'min_boxes' =>  $value['no_of_boxes'],
                        'charge_id' => isset($all_charge[$value['charge_master_id']]) ? $all_charge[$value['charge_master_id']]['id'] : 0,
                        'billing_type' => $billing_type,
                        'effective_from' =>  $value['effective_from'],
                        'effective_to' =>  $value['effective_till'],
                        'rate_per_type' =>  $rate_per_type,
                        'min_amt' =>  $value['min_amount'],
                        'max_chargeable_wt' =>  $value['max_weight'],
                        'min_volume_wt' =>  $value['min_volume_weight'],
                        'max_volume_wt' =>  $value['max_volume_weight'],
                        'max_per_box_wt' =>  $value['max_per_box_weight'],
                        'max_dimension' =>  $value['max_dimension'],
                        'min_dim_per_pc' =>  $value['min_dimension_per_pcs'] == 1 ? 1 : 2,
                        'max_dim_per_pc' =>  $value['max_dimension_per_pcs'] == 1 ? 1 : 2,
                        'min_actual_wt' =>  $value['min_actual_weight'],
                        'max_actual_wt' =>  $value['max_actual_weight'],
                        'shipment_per' =>  $value['percentage_on_shipment_value'],
                        'min_shipment_value' =>  $value['min_shipment_value'],
                        'max_shipment_value' =>  $value['max_shipment_value'],
                        'status' =>  1,
                        'created_by' => isset($all_user[$value['created_by_id']]) ? $all_user[$value['created_by_id']]['id'] : 0,
                        'modified_by' => isset($all_user[$value['updated_by_id']]) ? $all_user[$value['updated_by_id']]['id'] : 0,
                    );

                    $multiple_field = array(
                        'customer_code' => 'customer_id',
                        'vendor_code' => 'vendor_id',
                        'product_code' => 'product_id',
                        'location_code' => 'dest_location_id',
                        'zone_code' => 'dest_zone_id',
                        'co_vendor_code' => 'co_vendor_id',
                        'vendor2_code' => 'co_vendor_id',
                    );

                    if (isset($multiple_field) && is_array($multiple_field) && count($multiple_field) > 0) {
                        foreach ($multiple_field as $mkey => $mvalue) {
                            if (isset($value[$mkey]) && $value[$mkey] != '') {
                                $cust_arr = explode(",", $value[$mkey]);
                                if (isset($cust_arr) && is_array($cust_arr) && count($cust_arr) > 0) {
                                    foreach ($cust_arr as $ckey => $cvalue) {
                                        $cust_code = str_replace("|", "", $cvalue);
                                        $cust_code = trim($cust_code);
                                        if ($cust_code != '') {
                                            $customer_id = 0;
                                            if ($mkey == 'vendor_code') {
                                                $customer_id = isset($all_vendor[strtolower(trim($cust_code))]) ? $all_vendor[strtolower(trim($cust_code))]['id'] : 0;
                                            } else if ($mkey == 'product_code') {
                                                $customer_id = isset($all_product[strtolower(trim($cust_code))]) ? $all_product[strtolower(trim($cust_code))]['id'] : 0;
                                            } else if ($mkey == 'location_code') {
                                                $customer_id = isset($all_location[strtolower(trim($cust_code))]) ? $all_location[strtolower(trim($cust_code))]['id'] : 0;
                                            } else if ($mkey == 'zone_code') {
                                                $customer_id = isset($all_zone[strtolower(trim($cust_code))]) ? $all_zone[strtolower(trim($cust_code))]['id'] : 0;
                                            } else if ($mkey == 'customer_code') {
                                                $customer_id = isset($all_customer[strtolower(trim($cust_code))]) ? $all_customer[strtolower(trim($cust_code))]['id'] : 0;
                                            } else if ($mkey == 'co_vendor_code' || $mkey == 'vendor2_code') {
                                                $customer_id = isset($all_co_vendor[strtolower(trim($cust_code))]) ? $all_co_vendor[strtolower(trim($cust_code))]['id'] : 0;
                                            }

                                            if ($customer_id > 0) {
                                                $customer_insert[$mvalue][] = $customer_id;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                    $qry = "SELECT id FROM rate_modifier WHERE status IN(1,2) AND migration_id='" . $value['id'] . "'";
                    $qry_exe = $this->db->query($qry);
                    $existData = $qry_exe->row_array();

                    if (isset($existData) && is_array($existData) && count($existData) > 0) {
                        $customer_insert['rate_mod_id'] = $existData['id'];
                        $this->rate_modifiers->update($customer_insert);
                    } else {
                        $this->rate_modifiers->insert($customer_insert);
                    }
                }
            }
        }

        $time_end = microtime(true);
        $execution_time = ($time_end - $time_start) / 60;

        //execution time of the script
        echo '<b>Total Execution Time:</b> ' . $execution_time . ' Mins';
    }
}
