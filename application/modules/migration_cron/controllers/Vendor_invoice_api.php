<?php
class Vendor_invoice_api extends MX_Controller
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
            $url = $base_url . "api/v5/vendor_invoices/get_vendor_invoice_ids";
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
                    $qry = "SELECT id FROM vendor_invoice WHERE status IN(1,2) AND migration_id='" . $rvalue . "'";
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
                    $this->db->insert_batch('vendor_invoice', $insert_data);
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
        $this->load->model('Global_model', 'gm');

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
            $api_url = $base_url . "api/v5/vendor_invoices/fetch_vendor_invoices";

            $qry = "SELECT  id,config_value FROM migration_log WHERE status IN(1,2) AND config_key='vendor_invoice_migrate_last_id' ";
            $qry_exe = $this->db->query($qry);
            $offset_res = $qry_exe->row_array();

            $limit = 50;
            if (isset($offset_res['config_value']) && $offset_res['config_value'] != '') {
                $last_id = $offset_res['config_value'];
            } else {
                $last_id = 0;
            }
            $qry = "SELECT migration_id FROM vendor_invoice WHERE migration_id > " . $last_id . " ORDER BY migration_id  LIMIT " . $limit . "";
            $qry_exe = $this->db->query($qry);
            $id_res = $qry_exe->result_array();

            if (isset($id_res) && is_array($id_res) && count($id_res) > 0) {
                foreach ($id_res as $ikey => $ivalue) {
                    $id_arr[] = $ivalue['migration_id'];
                }
            }
            //$id_arr[0] = 9;
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


        $query = "SELECT id,co_vendor_id,forwarding_no FROM docket WHERE status IN(1,2) 
        AND forwarding_no!=''";
        $query_exe = $this->db->query($query);
        $docket_data  = $query_exe->result_array();
        if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
            foreach ($docket_data as $dkey => $dvalue) {
                $docket_awb[trim($dvalue['forwarding_no'])] = $dvalue;
            }
        }

        // echo '<pre>';
        // print_r($docket_awb);
        // exit;
        $all_co_vendor = get_all_co_vendor(" AND status IN(1,2) ", "code");
        if (isset($result['data']) && is_array($result['data']) && count($result['data']) > 0) {

            $keys = array_column($result['data'], 'id');
            array_multisort($keys, SORT_ASC, $result['data']);
            $all_user = get_all_user(" AND company_id='" . $company_id . "'", "", "migration_id");


            foreach ($result['data'] as $key => $value) {
                $co_vendor_id =  isset($value['vendor_code']) && $value['vendor_code'] != '' && isset($all_co_vendor[strtolower(trim($value['vendor_code']))]) ? $all_co_vendor[strtolower(trim($value['vendor_code']))]['id'] : 0;
                $import_insert = array(
                    'invoice_no' => $value['invoice_number'],
                    'refrence_name' => $value['vendor_code'],
                    'co_vendor_id' => $co_vendor_id,
                    'created_date' =>  $value['created_at'],
                    'modified_date' =>  $value['updated_at'],
                );

                $qry = "SELECT id FROM vendor_invoice WHERE status IN(1,2) AND migration_id='" . $value['id'] . "'";
                $qry_exe = $this->db->query($qry);
                $existData = $qry_exe->row_array();

                if (isset($existData) && is_array($existData) && count($existData) > 0) {
                    $vendor_invoice_id = $existData['id'];
                    $this->gm->update('vendor_invoice', $import_insert, '', array('id' => $vendor_invoice_id));
                } else {
                    $vendor_invoice_id = $this->gm->insert('vendor_invoice', $import_insert);
                }


                $deleq = "DELETE FROM vendor_invoice_docket WHERE vendor_invoice_id='" . $vendor_invoice_id . "'";
                $this->db->query($deleq);

                if (isset($value['vendor_invoice_items']) && is_array($value['vendor_invoice_items']) && count($value['vendor_invoice_items']) > 0) {
                    foreach ($value['vendor_invoice_items'] as $in_key => $in_value) {
                        // $query = "SELECT id,co_vendor_id FROM docket WHERE status IN(1,2) 
                        //     AND awb_no='" . trim($in_value['awb_no']) . "'";

                        // $query = "SELECT id,co_vendor_id FROM docket WHERE status IN(1,2) 
                        //     AND forwarding_no='" . trim($in_value['awb_no']) . "'";
                        // $query_exe = $this->db->query($query);
                        // $row_exist  = $query_exe->row_array();
                        $row_exist  = isset($docket_awb[trim($in_value['awb_no'])]) ? $docket_awb[trim($in_value['awb_no'])] : array();

                        if (isset($row_exist) && is_array($row_exist) && count($row_exist) > 0) {
                            $update_data = array(
                                'vendor_freight' =>  $in_value['amount'],
                                'vendor_fsc' =>  $in_value['fsc_amount'],
                                'vendor_grand_total' =>  $in_value['grand_total'],
                                'vendor_weight' =>  $in_value['vendor_weight'],
                                'vendor_invoice_no' => $value['invoice_number'],
                            );
                            $this->gm->update('docket', $update_data, '', array('id' => $row_exist['id']));


                            $import_docket_insert = array(
                                'vendor_invoice_id' => $vendor_invoice_id,
                                'docket_id' => $row_exist['id'],
                                'created_date' => date('Y-m-d H:i:s'),
                                'created_by' => isset($all_user[$value['created_by_id']]) ? $all_user[$value['created_by_id']]['id'] : 0,
                            );

                            $this->gm->insert('vendor_invoice_docket', $import_docket_insert);
                        }
                    }
                }



                //UPDATE OFFSET
                $qry = "SELECT  id,config_value FROM migration_log WHERE status IN(1,2) AND config_key='vendor_invoice_migrate_last_id' ";
                $qry_exe = $this->db->query($qry);
                $configExist = $qry_exe->row_array();

                if (isset($configExist) && is_array($configExist) && count($configExist) > 0) {
                    $updateq = "UPDATE migration_log SET config_value='" . $value['id'] . "' WHERE status IN(1,2) AND config_key='vendor_invoice_migrate_last_id'";
                    $this->db->query($updateq);
                } else {
                    $mig_insert_data = array(
                        'config_key' => 'vendor_invoice_migrate_last_id',
                        'config_value' => $value['id']
                    );
                    $this->gm->insert('migration_log', $mig_insert_data);
                }
                echo "<br>VENDOR INVOICE " . $value['invoice_number'] . " added";
            }
        }

        $time_end = microtime(true);
        $execution_time = ($time_end - $time_start) / 60;

        //execution time of the script
        echo '<b>Total Execution Time:</b> ' . $execution_time . ' Mins';
    }
}
