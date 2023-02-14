<?php
class Customer_credit_api extends MX_Controller
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
            $url = $base_url . "api/v5/customer_credits/get_customer_credit_ids";


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
                    $qry = "SELECT id FROM credit_debit_note WHERE status IN(1,2) AND migration_id='" . $rvalue . "'";
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
                    $this->db->insert_batch('credit_debit_note', $insert_data);
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
            $api_url = $base_url . "api/v5/customer_credits/fetch_customer_credits";

            $qry = "SELECT  id,config_value FROM migration_log WHERE status IN(1,2) AND config_key='customer_credit_migrate_last_id' ";
            $qry_exe = $this->db->query($qry);
            $offset_res = $qry_exe->row_array();

            $limit = 100;
            if (isset($offset_res['config_value']) && $offset_res['config_value'] != '') {
                $last_id = $offset_res['config_value'];
            } else {
                $last_id = 0;
            }
            $qry = "SELECT migration_id FROM credit_debit_note WHERE migration_id > " . $last_id . " ORDER BY migration_id  LIMIT " . $limit . "";
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

        $all_customer = get_all_customer(" AND status IN(1,2) ", "code");
        $setting = get_all_app_setting(" AND module_name='account'");
        $all_description = get_all_description(" AND status IN(1,2) ", "code");
        $all_user = get_all_user(" AND company_id='" . $company_id . "'", "", "migration_id");

        if (isset($result['data']) && is_array($result['data']) && count($result['data']) > 0) {
            $this->load->module('account/customer_credit');
            $this->load->module('account');
            $keys = array_column($result['data'], 'id');
            array_multisort($keys, SORT_ASC, $result['data']);

            foreach ($result['data'] as $key => $value) {


                $customer_id =  isset($value['customer_code']) && $value['customer_code'] != '' && isset($all_customer[strtolower(trim($value['customer_code']))]) ? $all_customer[strtolower(trim($value['customer_code']))]['id'] : 0;
                $payment_insert = array();
                $payment_insert['account'] = array(
                    'note_category' => $value['transaction_type'] == 'credit note' ? 1 : 2,
                    'note_type' => $value['note_type'] == 'general' ? 1 : 2,
                    'note_date' => $value['note_date'],
                    'note_no' => $value['note_no'],
                    'customer_id' => $customer_id,
                    'created_date' =>  $value['created_at'],
                    'modified_date' =>  $value['updated_at'],
                    'grand_total_amount' =>  $value['total_amount'],
                    'leftover_amt' =>  $value['total_amount'],
                );

                if (isset($value['customer_credit_items']) && is_array($value['customer_credit_items']) && count($value['customer_credit_items']) > 0) {
                    foreach ($value['customer_credit_items'] as $ikey => $ivalue) {

                        if (isset($setting['get_description_from_master_in_credit_and_debit_note']) && $setting['get_description_from_master_in_credit_and_debit_note'] == 1) {
                            $payment_insert['item_desc_id'][] = "TEST|" . (isset($all_description[strtolower(trim($ivalue['description']))]) ? $all_description[strtolower(trim($ivalue['description']))]['id'] : 0);
                            $payment_insert['item_desc'][] = '';
                        } else {
                            $payment_insert['item_desc'][] = $ivalue['description'];
                            $payment_insert['item_desc_id'][] = '';
                        }

                        $payment_insert['customer_ref_no'][] = $ivalue['customer_ref_no'];
                        $payment_insert['awb_no'][] = $ivalue['tracking_no'];
                        $payment_insert['hs_code'][] = $ivalue['hs_code'];
                        $payment_insert['invoice_no'][] = $ivalue['invoice_number'];
                        $payment_insert['invoice_date'][] = $ivalue['invoice_date'];
                        $payment_insert['chargeable_weight'][] = $ivalue['customer_ref_no'];
                        $payment_insert['destination_code'][] = $ivalue['chargeable_weight'];
                        $payment_insert['rate'][] = $ivalue['rate'];
                        $payment_insert['gst_type'][] = isset($ivalue['gst_type']) && strtolower(trim($ivalue['gst_type'])) == 'igst' ? 1 : 2;
                        $payment_insert['tax_per'][] = $ivalue['tax_percentage'];
                        $payment_insert['igst_amount'][] = $ivalue['igst_amount'];
                        $payment_insert['cgst_amount'][] = $ivalue['cgst_amount'];
                        $payment_insert['sgst_amount'][] = $ivalue['sgst_amount'];
                        $payment_insert['total_amount'][] = $ivalue['total_amount'];
                        $payment_insert['item_id'][] = $ivalue['id'];
                    }
                }



                $qry = "SELECT id FROM credit_debit_note WHERE status IN(1,2) AND migration_id='" . $value['id'] . "'";
                $qry_exe = $this->db->query($qry);
                $existData = $qry_exe->row_array();

                if (isset($existData) && is_array($existData) && count($existData) > 0) {
                    $payment_insert['credit_debit_id'] = $existData['id'];
                    $payment_id = $this->customer_credit->update($payment_insert);
                } else {
                    $payment_id = $this->customer_credit->insert($payment_insert);
                }

                $updateq = "UPDATE docket_include_data SET status=3,modified_date='3000-00-00 00:00:00' WHERE credit_id= '" . $payment_id . "' AND credit_id_type=2";
                $this->db->query($updateq);

                if (isset($value['payment_invoices']) && is_array($value['payment_invoices']) && count($value['payment_invoices']) > 0) {
                    foreach ($value['payment_invoices'] as $pkey => $pvalue) {
                        $invoice_id = 0;
                        $invoice_type = 0;
                        $idData = array();
                        if ($pvalue['invoice_id'] > 0) {

                            $qry = "SELECT id,grand_total FROM docket_invoice WHERE status IN(1,2) AND migration_id='" . $pvalue['invoice_id'] . "'";
                            $qry_exe = $this->db->query($qry);
                            $idData = $qry_exe->row_array();
                            $invoice_id = isset($idData['id']) ? $idData['id'] : 0;

                            $qry = "SELECT i.invoice_type,round_off_amt,id,invoice_id,grand_total_amt,deduction_amt,tds_amt,received_amt,credit_id,credit_id_type FROM docket_include_data i
            WHERE i.status IN(1,2) AND i.invoice_id ='" . $invoice_id . "' AND i.invoice_type=3";
                            $qry_exe = $this->db->query($qry);
                            $received_data = $qry_exe->result_array();

                            $invoice_type = 3;
                        } else if ($pvalue['customer_debit_id'] > 0) {
                            $invoice_id = $pvalue['customer_debit_id'];
                            $qry = "SELECT i.invoice_type,round_off_amt,id,invoice_id,grand_total_amt,deduction_amt,tds_amt,received_amt,credit_id,credit_id_type FROM docket_include_data i
            WHERE i.status IN(1,2) AND i.invoice_id ='" . $invoice_id . "' AND i.invoice_type=2";
                            $qry_exe = $this->db->query($qry);
                            $received_data = $qry_exe->result_array();

                            $qry = "SELECT id,grand_total_amount as grand_total FROM credit_debit_note WHERE status IN(1,2) AND id='" . $invoice_id . "'";
                            $qry_exe = $this->db->query($qry);
                            $idData = $qry_exe->row_array();

                            $invoice_type = 2;
                        } else if ($pvalue['debit_opening_balance_id'] > 0) {
                            $invoice_id = $pvalue['debit_opening_balance_id'];

                            $qry = "SELECT i.invoice_type,round_off_amt,id,invoice_id,grand_total_amt,deduction_amt,tds_amt,received_amt,credit_id,credit_id_type FROM docket_include_data i
            WHERE i.status IN(1,2) AND i.invoice_id ='" . $invoice_id . "' AND i.invoice_type=1";
                            $qry_exe = $this->db->query($qry);
                            $received_data = $qry_exe->result_array();

                            $qry = "SELECT id,opening_amount as grand_total FROM opening_balance WHERE status IN(1,2) AND id='" . $invoice_id . "'";
                            $qry_exe = $this->db->query($qry);
                            $idData = $qry_exe->row_array();

                            $invoice_type = 1;
                        }

                        $total_received = 0;
                        if (isset($received_data) && is_array($received_data) && count($received_data) > 0) {
                            foreach ($received_data as $re_key => $re_value) {
                                $total_received += $re_value['deduction_amt'];
                                $total_received += $re_value['tds_amt'];
                                $total_received += $re_value['received_amt'];
                                $total_received += $re_value['round_off_amt'];
                            }
                        }

                        $grand_total = isset($idData['grand_total']) ? $idData['grand_total'] : 0;
                        $insert_data = array(
                            'credit_id' => $payment_id,
                            'credit_id_type' => 2,
                            'invoice_id' => $invoice_id,
                            'invoice_type' => $invoice_type,
                            'grand_total_amt' => $value['amount'],
                            'deduction_amt' => $pvalue['deduction_amount'],
                            'tds_amt' => $pvalue['tds_amount'],
                            'received_amt' => $pvalue['amount_paid'],
                            'round_off_amt' => $pvalue['round_off_amount'],
                            'reference' => $pvalue['reference_number'],
                            'created_date' =>  date('Y-m-d H:i:s'),
                            'created_by' => $this->user_id,
                            'grand_total' => $grand_total,
                            'outstanding_amt' => $grand_total - $total_received,
                            'include_date' => date('Y-m-d', strtotime($pvalue['created_at'])),
                            'created_by' => isset($all_user[$value['created_by_id']]) ? $all_user[$value['created_by_id']]['id'] : 0,
                            'modified_by' => isset($all_user[$value['updated_by_id']]) ? $all_user[$value['updated_by_id']]['id'] : 0,
                        );

                        $this->account->save_include_invoice($insert_data);
                        // $docket_include_data_id =  $this->gm->insert('docket_include_data', $insert_data);
                        // $legder_data = array(
                        //     'customer_id' => $customer_id,
                        //     'payment_id' => $docket_include_data_id,
                        //     'payment_type' => 6,
                        //     'amount' => $pvalue['amount_paid'],
                        //     'ledger_date' => date('Y-m-d'),
                        //     'ledger_no' =>  $value['payment_no'],
                        //     'ledger_type' => 1,
                        //     'created_date' =>  date('Y-m-d H:i:s'),
                        //     'created_by' => $this->user_id,
                        // );
                        // $this->gm->insert('ledger_item', $legder_data);
                        // $this->gm->insert('ledger_outstanding_item', $legder_data);
                    }
                }
                //UPDATE OFFSET
                $qry = "SELECT  id,config_value FROM migration_log WHERE status IN(1,2) AND config_key='customer_credit_migrate_last_id' ";
                $qry_exe = $this->db->query($qry);
                $configExist = $qry_exe->row_array();

                if (isset($configExist) && is_array($configExist) && count($configExist) > 0) {
                    $updateq = "UPDATE migration_log SET config_value='" . $value['id'] . "' WHERE status IN(1,2) AND config_key='customer_credit_migrate_last_id'";
                    $this->db->query($updateq);
                } else {
                    $mig_insert_data = array(
                        'config_key' => 'customer_credit_migrate_last_id',
                        'config_value' => $value['id']
                    );
                    $this->gm->insert('migration_log', $mig_insert_data);
                }
                echo "<br>NOTE NO= " . $value['note_no'] . " added";
            }
        }

        $time_end = microtime(true);
        $execution_time = ($time_end - $time_start) / 60;

        //execution time of the script
        echo '<b>Total Execution Time:</b> ' . $execution_time . ' Mins';
    }
}
