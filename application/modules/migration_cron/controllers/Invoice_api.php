<?php
class Invoice_api extends MX_Controller
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
            $url = $base_url . "api/v5/invoices/get_invoice_ids";

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
                    $qry = "SELECT id FROM docket_invoice WHERE status IN(1,2) AND migration_id='" . $rvalue . "'";
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
                    $this->db->insert_batch('docket_invoice', $insert_data);
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

    public function get_api_data($company_code = '', $migrated_id = '')
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
        // $base_url = 'http://old.awcc.online/';
        if ($base_url != '') {

            $url = $base_url . "api/v5/invoices/fetch_invoices";
            $qry = "SELECT  id,config_value FROM migration_log WHERE status IN(1,2) 
            AND config_key='invoice_migrate_last_id' ";
            $qry_exe = $this->db->query($qry);
            $offset_res = $qry_exe->row_array();

            $limit = 100;
            if (isset($offset_res['config_value']) && $offset_res['config_value'] != '') {
                $last_id = $offset_res['config_value'];
            } else {
                $last_id = 0;
            }

            $qry = "SELECT MAX(migration_id) as last_id FROM docket_invoice WHERE migration_id >0";
            $qry_exe = $this->db->query($qry);
            $last_data = $qry_exe->row_array();
            if ($last_id == 0) {
                $last_id = $last_data['last_id'];
            }

            if ($migrated_id != '') {
                $qry = "SELECT migration_id FROM docket_invoice WHERE migration_id = " . $migrated_id . " 
                ORDER BY migration_id DESC  LIMIT " . $limit . "";
            } else {
                $qry = "SELECT migration_id FROM docket_invoice WHERE migration_id < " . $last_id . " 
                ORDER BY migration_id DESC  LIMIT " . $limit . "";
            }


            $qry = "SELECT * FROM `docket_invoice` WHERE status=1 AND 
            id NOT IN(SELECT docket_invoice_id FROM docket_invoice_map WHERE status=1 AND docket_id>0) AND migration_id > 0 LIMIT " . $limit . "";
            $qry_exe = $this->db->query($qry);
            $id_res = $qry_exe->result_array();


            if (isset($id_res) && is_array($id_res) && count($id_res) > 0) {
                foreach ($id_res as $ikey => $ivalue) {
                    $id_arr[] = $ivalue['migration_id'];
                }
            }
            // $id_arr[] = 8622;

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


                $all_customer = get_all_customer(" AND status IN(1,2) ", "code");
                $all_company = get_all_billing_company(" AND status IN(1,2) ", "id");
                $all_range = get_all_invoice_range(" AND status IN(1,2) ", "code");
                $all_account = get_all_bank_account(" AND c.status IN(1,2) ", "", "account_no");
                // echo '<pre>';
                // print_r($all_account);
                // print_r($result);
                // exit;
                $all_project = get_all_project(" AND status IN(1,2) ", "code");
                $all_vendor = get_all_vendor(" AND status IN(1,2) ", "code");
                $all_material = get_all_material(" AND status IN(1,2) ", "code");
                $all_brand = get_all_brand(" AND status IN(1,2) ", "code");
                $all_user = get_all_user(" AND company_id='" . $company_id . "'", "", "migration_id");

                if (isset($result) && is_array($result) && count($result) > 0) {
                    $this->load->module('script');
                    if (isset($result['data']) && is_array($result['data']) && count($result['data']) > 0) {

                        $keys = array_column($result['data'], 'id');
                        array_multisort($keys, SORT_DESC, $result['data']);


                        foreach ($result['data'] as $key => $value) {


                            $customer_id = isset($value['customer_code']) && $value['customer_code'] != '' && isset($all_customer[strtolower(trim($value['customer_code']))]) ? $all_customer[strtolower(trim($value['customer_code']))]['id'] : 0;
                            $company_master_id = isset($value['company_id']) && $value['company_id'] != '' && isset($all_company[strtolower(trim($value['company_id']))]) ? $all_company[strtolower(trim($value['company_id']))]['id'] : 0;
                            $invoice_range_id = isset($value['invoice_range_master_code']) && $value['invoice_range_master_code'] != '' && isset($all_range[strtolower(trim($value['invoice_range_master_code']))]) ? $all_range[strtolower(trim($value['invoice_range_master_code']))]['id'] : 0;

                            $project_id = isset($value['project_code']) && $value['project_code'] != '' && isset($all_project[strtolower(trim($value['project_code']))]) ? $all_project[strtolower(trim($value['project_code']))]['id'] : 0;
                            $vendor_id = isset($value['vendor_code']) && $value['vendor_code'] != '' && isset($all_vendor[strtolower(trim($value['vendor_code']))]) ? $all_vendor[strtolower(trim($value['vendor_code']))]['id'] : 0;
                            $material_id = isset($value['material_code']) && $value['material_code'] != '' && isset($all_material[strtolower(trim($value['material_code']))]) ? $all_material[strtolower(trim($value['material_code']))]['id'] : 0;
                            $brand_id = isset($value['brand_code']) && $value['brand_code'] != '' && isset($all_brand[strtolower(trim($value['brand_code']))]) ? $all_brand[strtolower(trim($value['brand_code']))]['id'] : 0;

                            if ($value['is_docketless'] == 1) {
                                $address_type = 0;
                                if ($value['address_type'] == 'customer') {
                                    $address_type = 1;
                                } else if ($value['address_type'] == 'shipper') {
                                    $address_type = 2;
                                }

                                $insert_data = array(
                                    'migration_id' => $value['id'],
                                    'invoice_no' => $value['invoice_number'],
                                    'invoice_date' => $value['invoice_date'],
                                    'due_date' => $value['due_date'],
                                    'customer_id' => $customer_id,
                                    'customer_note' => $value['note'],
                                    'created_date' =>  $value['created_at'],
                                    'modified_date' =>  $value['updated_at'],
                                    'from_date' => $value['from_date'],
                                    'to_date' => $value['till_date'],
                                    'invoice_status' => $value['is_locked'] == 1 ? 1 : 2,
                                    'payment_received' => $value['status'] == 'paid' ? 1 : 2,
                                    'invoice_type' => 4, //docketless invoice
                                    'company_master_id' => $company_master_id,
                                    'grand_total' => $value['grand_total'],
                                    'invoice_range_id' => $invoice_range_id,
                                    'po_number' => $value['po_number'],
                                    'project_id' => $project_id,
                                    'vendor_id' => $vendor_id,
                                    'material_id' => $material_id,
                                    'brand_id' => $brand_id,
                                    'docket_count' => count($value['invoice_items']),
                                    'bank_id' => isset($value['bank_account']) && $value['bank_account'] != '' && isset($all_account[strtolower(trim($value['bank_account']))]) ? $all_account[strtolower(trim($value['bank_account']))]['id'] : 0,
                                    'created_by' => isset($all_user[$value['created_by_id']]) ? $all_user[$value['created_by_id']]['id'] : 0,
                                    'modified_by' => isset($all_user[$value['updated_by_id']]) ? $all_user[$value['updated_by_id']]['id'] : 0,
                                    'is_email_sent' => $value['is_email_send'] != '' && $value['is_email_send'] == 1 ? 1 : 2
                                );

                                $qry = "SELECT id FROM docket_invoice WHERE status IN(1,2) AND migration_id='" . $value['id'] . "'";
                                $qry_exe = $this->db->query($qry);
                                $existData = $qry_exe->row_array();
                                if (isset($existData) && is_array($existData) && count($existData) > 0) {
                                    $docket_invoice_id = $existData['id'];
                                    $this->gm->update('docket_invoice', $insert_data, '', array('id' => $docket_invoice_id));
                                } else {
                                    $docket_invoice_id = $this->gm->insert('docket_invoice', $insert_data);
                                }


                                $insert_data = array(
                                    'invoice_id' => $docket_invoice_id,
                                    'created_date' =>  $value['created_at'],
                                    'modified_date' =>  $value['updated_at'],
                                    'addr_type' => $address_type,
                                    'sub_agent' => $value['sub_agent'],
                                    'irn' => $value['irn'],
                                    'good_desc' => $value['goods_description'],
                                    'job_no' => $value['job_no'],
                                    'awb_no' => $value['awb_no'],
                                    'mawb_no' => $value['mawb_no'],
                                    'departure_port' => $value['port_of_departure'],
                                    'arrival_port' => $value['port_of_arrival'],
                                    'gross_wt' => $value['gross_weight'],
                                    'package_cnt' => $value['packages'],
                                    'airline' => $value['airline'],
                                    'created_by' => isset($all_user[$value['created_by_id']]) ? $all_user[$value['created_by_id']]['id'] : 0,
                                    'modified_by' => isset($all_user[$value['updated_by_id']]) ? $all_user[$value['updated_by_id']]['id'] : 0,

                                );
                                $qry = "SELECT id FROM docket_less_invoice WHERE status IN(1,2) AND invoice_id='" . $docket_invoice_id . "'";
                                $qry_exe = $this->db->query($qry);
                                $existData = $qry_exe->row_array();
                                if (isset($existData) && is_array($existData) && count($existData) > 0) {
                                    $docket_less_invoice_id = $existData['id'];
                                    $this->gm->update('docket_less_invoice', $insert_data, '', array('id' => $docket_less_invoice_id));
                                } else {
                                    $docket_less_invoice_id = $this->gm->insert('docket_less_invoice', $insert_data);
                                }


                                //ADD INVOCIE ITEM
                                $item_array = isset($value['docket_less_invoice_items']) ? $value['docket_less_invoice_items'] : array();

                                if (isset($item_array) && is_array($item_array) && count($item_array) > 0) {
                                    $keys = array_column($item_array, 'item_index');
                                    array_multisort($keys, SORT_ASC, $item_array);

                                    foreach ($item_array as $ikey => $ivalue) {
                                        $item_insert = array(
                                            'id' => $ivalue['id'],
                                            'invoice_id' => $docket_invoice_id,
                                            'docket_less_invoice_id' => $docket_less_invoice_id,
                                            'desc_id' => isset($ivalue['docket_less_invoice_item_master_id']) ? $ivalue['docket_less_invoice_item_master_id'] : '',
                                            'desc_text' => $ivalue['is_custom_desc'] == 1 ? $ivalue['description'] : '',
                                            'sac_code' => isset($ivalue['sac']) ? $ivalue['sac'] : '',
                                            'amount' => isset($ivalue['amount']) ? $ivalue['amount'] : '',
                                            'gst_type' => isset($ivalue['gst_type']) && $ivalue['gst_type'] == 'CGST/SGST' ? 2 : 1,
                                            'tax_per' => isset($ivalue['tax_percentage']) ? $ivalue['tax_percentage'] : '',
                                            'igst_amount' => isset($ivalue['igst']) ? $ivalue['igst'] : '',
                                            'cgst_amount' => isset($ivalue['cgst']) ? $ivalue['cgst'] : '',
                                            'sgst_amount' => isset($ivalue['cgst']) ? $ivalue['cgst'] : '',
                                            'total_amount' => isset($ivalue['grand_total']) ? $ivalue['grand_total'] : '',
                                            'status' => 1,
                                            'created_by' => isset($all_user[$value['created_by_id']]) ? $all_user[$value['created_by_id']]['id'] : 0,
                                            'modified_by' => isset($all_user[$value['updated_by_id']]) ? $all_user[$value['updated_by_id']]['id'] : 0,
                                        );

                                        $qry = "SELECT id FROM docket_less_invoice_item WHERE id='" . $ivalue['id'] . "'";
                                        $qry_exe = $this->db->query($qry);
                                        $existData = $qry_exe->row_array();
                                        if (isset($existData) && is_array($existData) && count($existData) > 0) {
                                            $this->gm->update('docket_less_invoice_item', $item_insert, '', array('id' => $ivalue['id']));
                                        } else {
                                            $this->gm->insert('docket_less_invoice_item', $item_insert);
                                        }
                                    }
                                }
                            } else {
                                $insert_data = array(
                                    'migration_id' => $value['id'],
                                    'invoice_no' => $value['invoice_number'],
                                    'invoice_date' => $value['invoice_date'],
                                    'due_date' => $value['due_date'],
                                    'customer_id' => $customer_id,
                                    'customer_note' => $value['note'],
                                    'created_date' =>  $value['created_at'],
                                    'modified_date' =>  $value['updated_at'],
                                    'from_date' => $value['from_date'],
                                    'to_date' => $value['till_date'],
                                    'invoice_status' => $value['is_locked'] == 1 ? 1 : 2,
                                    'payment_received' => $value['status'] == 'paid' ? 1 : 2,
                                    'invoice_type' => 1, //single customer
                                    'company_master_id' => $company_master_id,
                                    'grand_total' => $value['grand_total'],
                                    'invoice_range_id' => $invoice_range_id,
                                    'po_number' => $value['po_number'],
                                    'project_id' => $project_id,
                                    'vendor_id' => $vendor_id,
                                    'material_id' => $material_id,
                                    'brand_id' => $brand_id,
                                    'docket_count' => count($value['invoice_items']),
                                    'bank_id' => isset($value['bank_account']) && $value['bank_account'] != '' && isset($all_account[strtolower(trim($value['bank_account']))]) ? $all_account[strtolower(trim($value['bank_account']))]['id'] : 0,
                                    'non_taxable_amt' => $value['non_taxable_amount'],
                                    'taxable_amt' => $value['taxable_amount'],
                                    'igst_amount' => $value['igst_amount'],
                                    'sgst_amount' => $value['sgst_amount'],
                                    'cgst_amount' => $value['cgst_amount'],
                                    'gst_per' => $value['till_date'],
                                    'created_by' => isset($all_user[$value['created_by_id']]) ? $all_user[$value['created_by_id']]['id'] : 0,
                                    'modified_by' => isset($all_user[$value['updated_by_id']]) ? $all_user[$value['updated_by_id']]['id'] : 0,
                                    'is_email_sent' => $value['is_email_send'] != '' && $value['is_email_send'] == 1 ? 1 : 2
                                );
                                if ($value['taxable_amount'] > 0) {
                                    $total_tax = $value['cgst_amount'] + $value['sgst_amount'] + $value['igst_amount'];
                                    $insert_data['gst_per'] = ($total_tax * 100) / $value['taxable_amount'];
                                }


                                $qry = "SELECT id FROM docket_invoice WHERE status IN(1,2) AND migration_id='" . $value['id'] . "'";
                                $qry_exe = $this->db->query($qry);
                                $existData = $qry_exe->row_array();
                                if (isset($existData) && is_array($existData) && count($existData) > 0) {
                                    $docket_invoice_id = $existData['id'];
                                    $this->gm->update('docket_invoice', $insert_data, '', array('id' => $docket_invoice_id));
                                } else {
                                    $docket_invoice_id = $this->gm->insert('docket_invoice', $insert_data);
                                }




                                if (isset($value['invoice_items']) && is_array($value['invoice_items']) && count($value['invoice_items']) > 0) {
                                    foreach ($value['invoice_items'] as $pkey => $pvalue) {
                                        $qry = "SELECT id FROM docket WHERE status IN(1,2) AND awb_no='" . $pvalue['tracking_no'] . "'";
                                        $qry_exe = $this->db->query($qry);
                                        $docketData = $qry_exe->row_array();
                                        $manifest_items = array(
                                            'migration_id' => $pvalue['id'],
                                            'docket_invoice_id' => $docket_invoice_id,
                                            'created_date' =>  $pvalue['created_at'],
                                            'modified_date' =>  $pvalue['updated_at'],
                                            'docket_id' => isset($docketData['id']) ? $docketData['id'] : 0,
                                        );
                                        $qry = "SELECT id FROM docket_invoice_map WHERE status IN(1,2) AND migration_id='" . $pvalue['id'] . "'";
                                        $qry_exe = $this->db->query($qry);
                                        $existData = $qry_exe->row_array();
                                        if (isset($existData) && is_array($existData) && count($existData) > 0) {
                                            $this->gm->update('docket_invoice_map', $manifest_items, '', array('migration_id' => $pvalue['id']));
                                        } else {
                                            $this->gm->insert('docket_invoice_map', $manifest_items);
                                        }

                                        if (isset($docketData['id']) && $docketData['id'] > 0) {
                                            $updateq = "UPDATE docket SET billing_status=2 WHERE id ='" . $docketData['id'] . "'";
                                            $this->db->query($updateq);
                                        }
                                    }
                                }


                                //$this->script->update_invoice_gst(" AND id='" . $docket_invoice_id . "'");
                                add_ledger_item($docket_invoice_id, 5, 2);
                            }

                            if (isset($value['e_invoice_details']) && is_array($value['e_invoice_details']) && count($value['e_invoice_details']) > 0) {
                                foreach ($value['e_invoice_details'] as $ekey => $evalue) {
                                    $qry = "SELECT id FROM irn_data WHERE status IN(1,2) AND invoice_id='" . $docket_invoice_id . "'";
                                    $qry_exe = $this->db->query($qry);
                                    $irnExist = $qry_exe->row_array();
                                    $irn_insert = array(
                                        'responce_data' => json_encode($evalue),
                                        'invoice_id' => $docket_invoice_id,
                                        'ack_no' => $evalue['e_invoice_ack_no'],
                                        'ack_dt' => date('Y-m-d H:i:s', strtotime($evalue['e_invoice_ack_date'])),
                                        'irn' => $evalue['e_invoice_irn'],
                                        'signed_invoice' => $evalue['signed_e_invoice'],
                                        'signed_qr_code' => $evalue['e_invoice_signed_qr_code'],
                                        'entry_type' => 2,
                                        'status' => 1
                                    );

                                    if (isset($irnExist) && is_array($irnExist) && count($irnExist) > 0) {
                                        $irn_insert['modified_date'] = date('Y-m-d H:i:s');
                                        $this->gm->update('irn_data', $irn_insert, '', array('id' => $irnExist['id']));
                                        $irn_data_id = $irnExist['id'];
                                    } else {
                                        $irn_insert['created_date'] = date('Y-m-d H:i:s');
                                        $irn_data_id =  $this->gm->insert('irn_data', $irn_insert);
                                    }


                                    if ($evalue['is_e_invoice_canceled'] == true) {
                                        $updateq = "UPDATE irn_data SET status=2 WHERE id='" . $irn_data_id . "'";
                                        $this->db->query($updateq);

                                        $qry = "SELECT id FROM irn_cancel WHERE status IN(1,2) AND irn_id='" . $irn_data_id . "'";
                                        $qry_exe = $this->db->query($qry);
                                        $irnCancelExist = $qry_exe->row_array();
                                        $irn_cancel_insert = array(
                                            'responce_data' => json_encode($evalue),
                                            'invoice_id' => $docket_invoice_id,
                                            'irn' => $evalue['e_invoice_irn'],
                                            'irn_id' => $irn_data_id,
                                            'status' => 1
                                        );

                                        if (isset($irnCancelExist) && is_array($irnCancelExist) && count($irnCancelExist) > 0) {
                                            $irn_insert['modified_date'] = date('Y-m-d H:i:s');
                                            $this->gm->update('irn_cancel', $irn_cancel_insert, '', array('id' => $irnCancelExist['id']));
                                        } else {
                                            $irn_insert['created_date'] = date('Y-m-d H:i:s');
                                            $this->gm->insert('irn_cancel', $irn_cancel_insert);
                                        }
                                    }
                                }
                            }



                            //UPDATE OFFSET
                            if ($migrated_id == '') {
                                $qry = "SELECT  id,config_value FROM migration_log WHERE status IN(1,2) AND config_key='invoice_migrate_last_id' ";
                                $qry_exe = $this->db->query($qry);
                                $configExist = $qry_exe->row_array();

                                if (isset($configExist) && is_array($configExist) && count($configExist) > 0) {
                                    $updateq = "UPDATE migration_log SET config_value='" . $value['id'] . "' WHERE status IN(1,2) AND config_key='invoice_migrate_last_id'";
                                    $this->db->query($updateq);
                                } else {
                                    $mig_insert_data = array(
                                        'config_key' => 'invoice_migrate_last_id',
                                        'config_value' => $value['id']
                                    );
                                    $this->gm->insert('migration_log', $mig_insert_data);
                                }
                            }

                            echo "<br>INVOICE NO." . $value['invoice_number'] . " added";
                            echo "<br>INVOICE ID." . $value['id'] . " added";
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



    public function get_invoice_history($company_code = '')
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
        //$base_url = 'http://134.209.155.59/';
        if ($base_url != '') {
            $url = $base_url . "/api/v5/invoices/invoice_history";

            $qry = "SELECT id,config_value FROM migration_log WHERE status IN(1,2) AND config_key='invoice_history_last_id' ";
            $qry_exe = $this->db->query($qry);
            $offset_res = $qry_exe->row_array();

            $limit = 1000;
            if (isset($offset_res['config_value']) && $offset_res['config_value'] != '') {
                $last_id = $offset_res['config_value'];
            } else {
                $last_id = 0;
            }

            //GET DOCKET WHOSE LINE ITEM MISSING
            $qry = "SELECT id,migration_id FROM docket_invoice WHERE migration_id > " . $last_id . " ORDER BY migration_id  LIMIT " . $limit . "";
            $qry_exe = $this->db->query($qry);
            $id_res = $qry_exe->result_array();

            if (isset($id_res) && is_array($id_res) && count($id_res) > 0) {
                foreach ($id_res as $ikey => $ivalue) {
                    $id_arr[] = $ivalue['migration_id'];
                }
            }
            // $id_arr[] = 1043;
            // echo $url;
            // echo '<pre>';
            // print_r($id_arr);

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


                // echo $response_json;
                // exit;
                curl_close($ch);

                $response_json =  str_replace(array("'"), "", $response_json);

                $result = json_decode($response_json, true);

                $history_dir =  create_year_dir('invoice_history', 'history_log');

                if (isset($result['error']) && $result['error'] != '') {
                    echo $result['error'];
                    exit;
                } else {
                    if (isset($result) && is_array($result) && count($result) > 0) {
                        if (isset($result['data']) && is_array($result['data']) && count($result['data']) > 0) {

                            $keys = array_column($result['data'], 'id');
                            array_multisort($keys, SORT_ASC, $result['data']);

                            foreach ($result['data'] as $key => $value) {

                                $invoice_id = $value['id'];

                                $filename = $invoice_id . "_history.json";

                                $file_content = json_encode($value, JSON_PRETTY_PRINT);
                                file_put_contents(FCPATH . '/' . $history_dir . '/' . $filename, $file_content);
                                //UPDATE OFFSET
                                $qry = "SELECT  id,config_value FROM migration_log WHERE status IN(1,2) AND config_key='invoice_history_last_id' ";
                                $qry_exe = $this->db->query($qry);
                                $configExist = $qry_exe->row_array();

                                if (isset($configExist) && is_array($configExist) && count($configExist) > 0) {
                                    $updateq = "UPDATE migration_log SET config_value='" . $value['id'] . "' WHERE status IN(1,2) AND config_key='invoice_history_last_id'";
                                    $this->db->query($updateq);
                                } else {
                                    $mig_insert_data = array(
                                        'config_key' => 'invoice_history_last_id',
                                        'config_value' => $value['id']
                                    );
                                    $this->gm->insert('migration_log', $mig_insert_data);
                                }

                                echo "<br>INVOICE ID=" . $invoice_id;
                            }
                        }
                    }
                }
            }
        }
        $time_end = microtime(true);
        $execution_time = ($time_end - $time_start) / 60;

        //execution time of the script
        echo '<br><b>Total Execution Time:</b> ' . ($time_end - $time_start) . ' Second';
        echo '<br><b>Total Execution Time:</b> ' . $execution_time . ' Mins';
    }



    public function update_migration_calculation($company_code = '')
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
        //$base_url = 'http://134.209.155.59/';
        if ($base_url != '') {

            $url = $base_url . "api/v5/invoices/fetch_invoices";
            $qry = "SELECT  id,config_value FROM migration_log WHERE status IN(1,2) AND config_key='invoice_migrate_calculation_last_id' ";
            $qry_exe = $this->db->query($qry);
            $offset_res = $qry_exe->row_array();

            $limit = 100;
            if (isset($offset_res['config_value']) && $offset_res['config_value'] != '') {
                $last_id = $offset_res['config_value'];
            } else {
                $last_id = 0;
            }
            $qry = "SELECT migration_id FROM docket_invoice WHERE migration_id > " . $last_id . " ORDER BY migration_id  LIMIT " . $limit . "";
            $qry_exe = $this->db->query($qry);
            $id_res = $qry_exe->result_array();


            if (isset($id_res) && is_array($id_res) && count($id_res) > 0) {
                foreach ($id_res as $ikey => $ivalue) {
                    $id_arr[] = $ivalue['migration_id'];
                }
            }
            //$id_arr[] = 1043;

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



                if (isset($result) && is_array($result) && count($result) > 0) {
                    if (isset($result['data']) && is_array($result['data']) && count($result['data']) > 0) {

                        $keys = array_column($result['data'], 'id');
                        array_multisort($keys, SORT_ASC, $result['data']);


                        foreach ($result['data'] as $key => $value) {

                            if ($value['is_docketless'] == 1) {
                                //SKIP DOCKET_LESS
                            } else {

                                $insert_data = array(
                                    'grand_total' => $value['grand_total'],
                                    'non_taxable_amt' => $value['non_taxable_amount'],
                                    'taxable_amt' => $value['taxable_amount'],
                                    'igst_amount' => $value['igst_amount'],
                                    'sgst_amount' => $value['sgst_amount'],
                                    'cgst_amount' => $value['cgst_amount'],
                                    'gst_per' => $value['till_date'],
                                );
                                if ($value['taxable_amount'] > 0) {
                                    $total_tax = $value['cgst_amount'] + $value['sgst_amount'] + $value['igst_amount'];
                                    $insert_data['gst_per'] = ($total_tax * 100) / $value['taxable_amount'];
                                    $insert_data['gst_per'] = round($insert_data['gst_per'], 2);
                                }


                                $qry = "SELECT id FROM docket_invoice WHERE status IN(1,2) AND migration_id='" . $value['id'] . "'";
                                $qry_exe = $this->db->query($qry);
                                $existData = $qry_exe->row_array();
                                if (isset($existData) && is_array($existData) && count($existData) > 0) {
                                    $docket_invoice_id = $existData['id'];

                                    $qry = "SELECT id FROM invoice_history WHERE status IN(1,2) AND docket_invoice_id='" . $docket_invoice_id . "'";
                                    $qry_exe = $this->db->query($qry);
                                    $historyData = $qry_exe->row_array();

                                    if (isset($historyData) && is_array($historyData) && count($historyData) > 0) {
                                        //IF INVOICE HISTORY PRESENT SKIP
                                    } else {
                                        // echo "<br>NUMBER=" . $value['invoice_number'];
                                        // echo "<br>ID=" . $docket_invoice_id;
                                        echo '<pre>';
                                        print_r($insert_data);

                                        $this->gm->update('docket_invoice', $insert_data, '', array('id' => $docket_invoice_id));
                                        add_ledger_item($docket_invoice_id, 5, 2);
                                    }


                                    echo "<br>INVOICE UPDATED ID" . $docket_invoice_id;
                                } else {
                                    echo "<br>MIGRATION_ID NOT FOUND " . $value['id'];
                                }
                            }



                            //UPDATE OFFSET
                            $qry = "SELECT  id,config_value FROM migration_log WHERE status IN(1,2) AND config_key='invoice_migrate_calculation_last_id' ";
                            $qry_exe = $this->db->query($qry);
                            $configExist = $qry_exe->row_array();

                            if (isset($configExist) && is_array($configExist) && count($configExist) > 0) {
                                $updateq = "UPDATE migration_log SET config_value='" . $value['id'] . "' WHERE status IN(1,2) AND config_key='invoice_migrate_calculation_last_id'";
                                $this->db->query($updateq);
                            } else {
                                $mig_insert_data = array(
                                    'config_key' => 'invoice_migrate_calculation_last_id',
                                    'config_value' => $value['id']
                                );
                                $this->gm->insert('migration_log', $mig_insert_data);
                            }

                            echo "<br>INVOICE NO." . $value['invoice_number'];
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
