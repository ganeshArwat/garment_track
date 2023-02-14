<?php
class Shipper_migrate_api extends MX_Controller
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
            $url = $base_url . "api/v5/shipper_masters/get_shipper_master_ids";

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
                    $qry = "SELECT id FROM shipper WHERE status IN(1,2) AND migration_id='" . $rvalue . "'";
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
                    $this->db->insert_batch('shipper', $insert_data);
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
        // $base_url = 'https://admin.uniquecourier.in/';
        if ($base_url != '') {
            $api_url = $base_url . "api/v5/shipper_masters/fetch_shipper_masters";

            $qry = "SELECT  id,config_value FROM migration_log WHERE status IN(1,2) AND config_key='shipper_migrate_last_id' ";
            $qry_exe = $this->db->query($qry);
            $offset_res = $qry_exe->row_array();

            $limit = 500;
            if (isset($offset_res['config_value']) && $offset_res['config_value'] != '') {
                $last_id = $offset_res['config_value'];
            } else {
                $last_id = 0;
            }
            $qry = "SELECT migration_id FROM shipper WHERE migration_id > " . $last_id . " ORDER BY migration_id  LIMIT " . $limit . "";
            $qry_exe = $this->db->query($qry);
            $id_res = $qry_exe->result_array();

            if (isset($id_res) && is_array($id_res) && count($id_res) > 0) {
                foreach ($id_res as $ikey => $ivalue) {
                    $id_arr[] = $ivalue['migration_id'];
                }
            }
            // $id_arr[0] = 137;
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
            //  $all_state = get_all_state(" AND status IN(1,2) ", "code");
            $all_country = get_all_country(" AND status IN(1,2) ", "code");
            //$all_city = get_all_city(" AND status IN(1,2) ", "code");
            $all_customer = get_all_customer(" AND status IN(1,2) ", "code");

            // $all_state_name = get_all_state(" AND status IN(1,2) ", "name");
            $all_country_name = get_all_country(" AND status IN(1,2) ", "name");
            // $all_city_name = get_all_city(" AND status IN(1,2) ", "name");
            $all_gstin_type = get_all_doc_type(" AND show_in_docket_shipper=1", "docket_doc_name", "name");
            $all_user = get_all_user(" AND company_id='" . $company_id . "'", "", "migration_id");

            $this->load->module('shipper_masters');

            if (isset($result['data']) && is_array($result['data']) && count($result['data']) > 0) {

                $keys = array_column($result['data'], 'id');
                array_multisort($keys, SORT_ASC, $result['data']);


                foreach ($result['data'] as $key => $value) {
                    $customer_insert = array();
                    //$state_id = isset($value['state']) && isset($all_state[strtolower(trim($value['state']))]) ? $all_state[strtolower(trim($value['state']))]['id'] : 0;
                    $country_id = isset($value['country']) && isset($all_country[strtolower(trim($value['country']))]) ? $all_country[strtolower(trim($value['country']))]['id'] : 0;
                    //$city_id = isset($value['city']) && isset($all_city[strtolower(trim($value['city']))]) ? $all_city[strtolower(trim($value['city']))]['id'] : 0;


                    if ($country_id == 0) {
                        $country_id = isset($value['country']) && isset($all_country_name[strtolower(trim($value['country']))]) ? $all_country_name[strtolower(trim($value['country']))]['id'] : 0;
                    }


                    $customer_id = isset($value['customer_code']) && isset($all_customer[strtolower(trim($value['customer_code']))]) ? $all_customer[strtolower(trim($value['customer_code']))]['id'] : 0;

                    $customer_insert['shipper'] = array(
                        'migration_id' => $value['id'],
                        'name' =>  $value['name'],
                        'code' =>  $value['code'],
                        'address1' =>  $value['address_line_1'],
                        'created_date' =>  $value['created_at'],
                        'modified_date' =>  $value['updated_at'],
                        'company_name' =>  $value['company_name'],
                        'address2' =>  $value['address_line_2'],
                        'address3' =>  $value['address_line_3'],
                        'city' =>   $value['city'],
                        'state' =>   $value['state'],
                        'country' =>  $country_id,
                        'pincode' =>  $value['zip_code'],
                        'contact_no' =>  $value['contact_no'],
                        'email_id' =>  $value['email'],
                        'gstin_type' =>  isset($value['gstin_type']) && $value['gstin_type'] != '' && isset($all_gstin_type[strtolower(trim($value['gstin_type']))]) ? $all_gstin_type[strtolower(trim($value['gstin_type']))]['id'] : 0,
                        'gstin_no' =>  $value['gstin_no'],
                        'customer_id' =>  $customer_id,
                        'dob' =>  $value['date_of_birth'],
                        'dial_code' =>  $value['dial_code'],
                        'status' =>  1,
                        'created_by' => isset($all_user[$value['created_by_id']]) ? $all_user[$value['created_by_id']]['id'] : 0,
                        'modified_by' => isset($all_user[$value['updated_by_id']]) ? $all_user[$value['updated_by_id']]['id'] : 0,
                    );

                    if (isset($value['kycs']) && is_array($value['kycs']) && count($value['kycs']) > 0) {
                        foreach ($value['kycs'] as $kkey => $kvalue) {
                            $doc_type_id = 0;
                            if ($kvalue['document_type'] == 'Signature') {
                                $doc_type_id = 16;
                            } elseif ($kvalue['document_type'] == 'GSTIN (Diplomats)') {
                                $doc_type_id = 15;
                            } elseif ($kvalue['document_type'] == 'GSTIN (Govt.)') {
                                $doc_type_id = 14;
                            } elseif ($kvalue['document_type'] == 'GSTIN (Normal)') {
                                $doc_type_id = 13;
                            } elseif ($kvalue['document_type'] == 'TAN Number') {
                                $doc_type_id = 12;
                            } elseif ($kvalue['document_type'] == 'IEC') {
                                $doc_type_id = 7;
                            } elseif ($kvalue['document_type'] == 'Aadhaar Card') {
                                $doc_type_id = 4;
                            } elseif ($kvalue['document_type'] == 'PAN Card') {
                                $doc_type_id = 3;
                            } elseif ($kvalue['document_type'] == 'Voter ID Card') {
                                $doc_type_id = 2;
                            } elseif ($kvalue['document_type'] == 'Passport') {
                                $doc_type_id = 1;
                            }

                            if ($doc_type_id > 0) {
                                if (
                                    $kvalue['document_no'] != '' || $kvalue['name_as_per_document'] != ''
                                    || $kvalue['image'] != '' || $kvalue['image1'] != ''
                                ) {
                                    $customer_insert['doc_no_' . $doc_type_id] = $kvalue['document_no'];
                                    $customer_insert['doc_name_' . $doc_type_id] = $kvalue['name_as_per_document'];
                                    $customer_insert['live_file_1_' . $doc_type_id] = $kvalue['image'];
                                    $customer_insert['live_file_2_' . $doc_type_id] = $kvalue['image1'];
                                }
                            }
                        }
                    }

                    $qry = "SELECT id FROM shipper WHERE status IN(1,2) AND migration_id='" . $value['id'] . "'";
                    $qry_exe = $this->db->query($qry);
                    $existData = $qry_exe->row_array();

                    if (isset($existData) && is_array($existData) && count($existData) > 0) {
                        $customer_insert['shipper_id'] = $existData['id'];
                        $this->shipper_masters->update($customer_insert);
                    } else {
                        $this->shipper_masters->insert($customer_insert);
                    }


                    //UPDATE OFFSET
                    $qry = "SELECT  id,config_value FROM migration_log WHERE status IN(1,2) AND config_key='shipper_migrate_last_id' ";
                    $qry_exe = $this->db->query($qry);
                    $configExist = $qry_exe->row_array();

                    if (isset($configExist) && is_array($configExist) && count($configExist) > 0) {
                        $updateq = "UPDATE migration_log SET config_value='" . $value['id'] . "' WHERE status IN(1,2) AND config_key='shipper_migrate_last_id'";

                        $this->db->query($updateq);
                    } else {
                        $mig_insert_data = array(
                            'config_key' => 'shipper_migrate_last_id',
                            'config_value' => $value['id']
                        );
                        $this->gm->insert('migration_log', $mig_insert_data);
                    }
                    echo "<br>SHIPPER CODE " . $value['code'] . " added";
                }
            }
        }
        $time_end = microtime(true);
        $execution_time = ($time_end - $time_start) / 60;

        //execution time of the script
        echo '<b>Total Execution Time:</b> ' . $execution_time . ' Mins';
    }
}
