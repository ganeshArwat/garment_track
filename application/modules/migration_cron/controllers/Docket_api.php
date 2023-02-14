<?php
class Docket_api extends MX_Controller
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
            $url = $base_url . "api/v5/dockets/get_docket_ids";

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
                        $qry = "SELECT id FROM docket WHERE status IN(1,2) AND migration_id='" . $rvalue . "'";
                        $qry_exe = $this->db->query($qry);
                        $existData = $qry_exe->row_array();

                        if (isset($existData) && is_array($existData) && count($existData) > 0) {
                        } else {
                            $insert_data[] = array(
                                'migration_id' => $rvalue
                            );
                        }
                    }
                }
            }
        }

        if (isset($insert_data) && is_array($insert_data) && count($insert_data) > 0) {
            $this->db->insert_batch('docket', $insert_data);
            echo count($insert_data) . " ID INSERTED";
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
        // $base_url = 'http://old.universalcourier.in/';

        if ($base_url != '') {

            $url = $base_url . "api/v5/dockets/fetch_dockets";


            $qry = "SELECT  id,config_value FROM migration_log WHERE status IN(1,2) AND config_key='docket_migrate_last_id' ";
            $qry_exe = $this->db->query($qry);
            $offset_res = $qry_exe->row_array();

            $limit = 100;
            if (isset($offset_res['config_value']) && $offset_res['config_value'] != '') {
                $last_id = $offset_res['config_value'];
            } else {
                $last_id = 0;
            }
            $qry = "SELECT migration_id FROM docket WHERE migration_id > " . $last_id . " ORDER BY migration_id  LIMIT " . $limit . "";
            // $qry = "SELECT migration_id FROM `docket` WHERE `status` = 1 AND `awb_no` = '' AND `migration_id` != 0";
            $qry_exe = $this->db->query($qry);
            $id_res = $qry_exe->result_array();
            if (isset($id_res) && is_array($id_res) && count($id_res) > 0) {
                foreach ($id_res as $ikey => $ivalue) {
                    $id_arr[] = $ivalue['migration_id'];
                }
            }

            //   $id_arr[] = '168725';
            // $url = $base_url . "api/v5/dockets/get_docket_ids";

            // $request_json[] = array();
            // $ch = curl_init();
            // curl_setopt($ch, CURLOPT_URL, $url);
            // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            // curl_setopt($ch, CURLOPT_POSTFIELDS, array());
            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            // curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            //     'Content-Type: application/json',
            //     'Accept: application/json'
            // ));
            // $response_json = curl_exec($ch);
            // curl_close($ch);
            // $result_id = json_decode($response_json, true);
            // if (isset($id_arr) && is_array($id_arr) && count($id_arr) > 0) {
            //     foreach ($id_arr as $ikey => $ivalue) {
            //         if (!in_array($ivalue, $result_id['data'])) {
            //             echo "<br>ID=" . $ivalue . " NOT";
            //         }
            //     }
            // }

            // exit;
            // echo '<pre>';
            // print_r($id_arr);
            // exit;

            //$id_arr[0] = 56370;

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
                // echo '<pre>';
                // print_r($result);
                // exit;

                if (isset($result['error']) && $result['error'] != '') {
                    echo $result['error'];
                    exit;
                }

                $setting = get_all_app_setting(" AND module_name IN('main')");
                $all_customer = get_all_customer(" AND status IN(1,2) ", "code");
                $all_location = get_all_location(" AND status IN(1,2) ", "code");
                $all_shipper = get_all_shipper(" AND status IN(1,2) ", "code");
                $all_consignee = get_all_consignee(" AND status IN(1,2) ", "code");
                $all_product = get_all_product(" AND status IN(1,2) ", "code");
                $all_vendor = get_all_vendor(" AND status IN(1,2) ", "code");
                $all_co_vendor = get_all_co_vendor(" AND status IN(1,2) ", "code");
                $all_hub = get_all_hub(" AND status IN(1,2) ", "code");
                $all_zone = get_all_zone(" AND status IN(1,2) ", "name");
                $all_country = get_all_country(" AND status IN(1,2) ", "code");
                $all_currency = get_all_currency(" AND status IN(1,2) ", "code");
                $all_incoterm = get_all_incoterm(" AND status IN(1,2) ", "code");
                $all_material = get_all_material(" AND status IN(1,2) ", "code");
                $all_account = get_all_bank_account(" AND c.status IN(1,2) ", "", "account_no");
                $all_free_item = all_free_item(" AND status IN(1,2) ", "name");
                $all_unit_type = all_unit_type(" AND status IN(1,2) ", "name");
                $all_invoice_range = get_all_invoice_range(" AND status IN(1,2) ", "code");
                $all_brand = get_all_brand(" AND status IN(1,2) ", "code");
                $all_note = all_free_form_note(" AND status IN(1,2) ", "name");
                $all_project = get_all_project(" AND status IN(1,2) ", "code");
                $all_user = get_all_user(" AND company_id='" . $company_id . "'", "", "migration_id");

                $all_gst_type = get_all_doc_type(" AND show_in_docket_shipper=1", "docket_doc_name", "name");

                $state_code_id = array(
                    'pickup' => 0,
                    'pickup_scan' => 2,
                    'in_scan_at_origin' => 0,
                    'entry' => 1,
                    'manifest' => 4,
                    'remanifest' => 6,
                    'transfer_manifest' => 15,
                    'inscan' => 3,
                    'reinscan' => 0,
                    'transfer_manifest_inscan' => 0,
                    'in_transit' => 14,
                    'drs' => 7,
                    'undelivered' => 8,
                    'delivered' => 10,
                    'rto' => 12,
                    'cancel' => 13,
                    'misplaced' => 11,
                );


                if (isset($result) && is_array($result) && count($result) > 0) {
                    if (isset($result['data']) && is_array($result['data']) && count($result['data']) > 0) {

                        $keys = array_column($result['data'], 'id');
                        array_multisort($keys, SORT_ASC, $result['data']);

                        foreach ($result['data'] as $key => $value) {

                            $sales_edited = array();
                            $purchase_edited = array();
                            $awb_edited = array();
                            $customer_id =  isset($value['customer_code']) && $value['customer_code'] != '' && isset($all_customer[strtolower(trim($value['customer_code']))]) ? $all_customer[strtolower(trim($value['customer_code']))]['id'] : 0;
                            $customer_gst_apply =  isset($value['customer_code']) && $value['customer_code'] != '' && isset($all_customer[strtolower(trim($value['customer_code']))]) ? $all_customer[strtolower(trim($value['customer_code']))]['is_gst_apply'] : 2;

                            $origin_id =  isset($value['origin_code']) && $value['origin_code'] != '' && isset($all_location[strtolower(trim($value['origin_code']))]) ? $all_location[strtolower(trim($value['origin_code']))]['id'] : 0;
                            $shipper_id =  isset($value['shipper_code']) && $value['shipper_code'] != '' && isset($all_shipper[strtolower(trim($value['shipper_code']))]) ? $all_shipper[strtolower(trim($value['shipper_code']))]['id'] : 0;
                            $consignee_id =  isset($value['consignee_code']) && $value['consignee_code'] != '' && isset($all_consignee[strtolower(trim($value['consignee_code']))]) ? $all_consignee[strtolower(trim($value['consignee_code']))]['id'] : 0;
                            $product_id =  isset($value['product_code']) && $value['product_code'] != '' && isset($all_product[strtolower(trim($value['product_code']))]) ? $all_product[strtolower(trim($value['product_code']))]['id'] : 0;
                            $vendor_id =  isset($value['vendor_code']) && $value['vendor_code'] != '' && isset($all_vendor[strtolower(trim($value['vendor_code']))]) ? $all_vendor[strtolower(trim($value['vendor_code']))]['id'] : 0;

                            if (isset($setting['enable_vendor2']) && $setting['enable_vendor2'] == 1) {
                                $vendor_key = 'vendor2_code';
                            } else {
                                $vendor_key = 'vendor_code';
                            }
                            $co_vendor_id =  isset($value[$vendor_key]) && $value[$vendor_key] != '' && isset($all_co_vendor[strtolower(trim($value[$vendor_key]))]) ? $all_co_vendor[strtolower(trim($value[$vendor_key]))]['id'] : 0;
                            $co_vendor_gst_apply =  isset($value[$vendor_key]) && $value[$vendor_key] != '' && isset($all_co_vendor[strtolower(trim($value[$vendor_key]))]) ? $all_co_vendor[strtolower(trim($value[$vendor_key]))]['is_gst_apply'] : 2;
                            $destination_id =  isset($value['destination_code']) && $value['destination_code'] != '' && isset($all_location[strtolower(trim($value['destination_code']))]) ? $all_location[strtolower(trim($value['destination_code']))]['id'] : 0;
                            $ori_hub_id =  isset($value['hub_code']) && $value['hub_code'] != '' && isset($all_hub[strtolower(trim($value['hub_code']))]) ? $all_hub[strtolower(trim($value['hub_code']))]['id'] : 0;
                            $dest_hub_id =  isset($value['destination_hub_code']) && $value['destination_hub_code'] != '' && isset($all_hub[strtolower(trim($value['destination_hub_code']))]) ? $all_hub[strtolower(trim($value['destination_hub_code']))]['id'] : 0;

                            $dest_zone_id =  isset($value['destination_zone']) && $value['destination_zone'] != '' && isset($all_zone[strtolower(trim($value['destination_zone']))]) ? $all_zone[strtolower(trim($value['destination_zone']))]['id'] : 0;
                            $ori_zone_id =  isset($value['origin_zone_code']) && $value['origin_zone_code'] != '' && isset($all_zone[strtolower(trim($value['origin_zone_code']))]) ? $all_zone[strtolower(trim($value['origin_zone_code']))]['id'] : 0;

                            $shipper_gstin_type =  isset($value['shipper_gstin_type']) && $value['shipper_gstin_type'] != '' && isset($all_gst_type[strtolower(trim($value['shipper_gstin_type']))]) ? $all_gst_type[strtolower(trim($value['shipper_gstin_type']))]['id'] : 0;

                            // $status_id = 0;
                            // if ($value['docket_status_code'] != '') {
                            //     $docket_status_code = strtolower($value['docket_status_code']);
                            //     if ($docket_status_code == 'open') {
                            //         $status_id = 1;
                            //     } else if ($docket_status_code == 'locked') {
                            //         $status_id = 2;
                            //     } else if ($docket_status_code == 'void') {
                            //         $status_id = 3;
                            //     } else if ($docket_status_code == 'hold') {
                            //         $status_id = 4;
                            //     }
                            // }

                            $payment_type = 0;
                            if ($value['type_of_payment'] != '') {
                                $docket_status_code = strtolower($value['type_of_payment']);
                                if ($docket_status_code == 'cash') {
                                    $payment_type = 1;
                                } else if ($docket_status_code == 'cheque') {
                                    $payment_type = 2;
                                } else if ($docket_status_code == 'online') {
                                    $payment_type = 3;
                                } else if ($docket_status_code == 'credit') {
                                    $payment_type = 4;
                                } else  if ($docket_status_code == 'foc') {
                                    $payment_type = 5;
                                } else if ($docket_status_code == 'cash-credit') {
                                    $payment_type = 6;
                                } else if ($docket_status_code == 'cod') {
                                    $payment_type = 7;
                                } else if ($docket_status_code == 'to_pay') {
                                    $payment_type = 8;
                                } else if ($docket_status_code == 'cod_topay') {
                                    $payment_type = 9;
                                }
                            }

                            $dispatch_type = 0;
                            if ($value['dispatch_type'] != '') {
                                $dispatch_type_name = strtolower($value['dispatch_type']);
                                if ($dispatch_type_name == 'regular') {
                                    $dispatch_type = 1;
                                } else if ($dispatch_type_name == 'inventory') {
                                    $dispatch_type = 2;
                                } else if ($dispatch_type_name == 'reverse') {
                                    $dispatch_type = 3;
                                } else if ($dispatch_type_name == 'cod') {
                                    $dispatch_type = 4;
                                }
                            }

                            $shipment_priority = 0;
                            if ($value['shipment_priority'] != '') {
                                $shipment_priority_name = strtolower($value['shipment_priority']);
                                if ($shipment_priority_name == 'regular') {
                                    $shipment_priority = 1;
                                } else if ($shipment_priority_name == 'urgent') {
                                    $shipment_priority = 2;
                                }
                            }

                            if ($value['is_custom_volume_weight'] == 1) {
                                $awb_edited[] = 'edit_volume_wt';
                            }
                            if ($value['is_custom_actual_weight'] == 1) {
                                $awb_edited[] = 'edit_actual_wt';
                            }
                            if ($value['is_custom_chargeable_weight'] == 1) {
                                $awb_edited[] = 'edit_charge_wt';
                            }

                            $docket_insert = array(
                                'migration_id' => $value['id'],
                                'awb_no' => $value['tracking_no'],
                                'forwarding_no' => $value['vendor_awb1'],
                                'forwarding_no_2' => $value['vendor_awb2'],
                                'shipment_value' => $value['shipment_value'],
                                'content' => $value['content'],
                                'instructions' => $value['instruction'],
                                'created_date' =>  $value['created_at'],
                                'modified_date' =>  $value['updated_at'],
                                'modified_date' =>  $value['updated_at'],
                                'state_id' => isset($value['state']) && isset($state_code_id[$value['state']]) ? $state_code_id[$value['state']] : 0,
                                'customer_id' => $customer_id,
                                'origin_id' => $origin_id,
                                'shipper_id' => $shipper_id,
                                'consignee_id' => $consignee_id,
                                'product_id' => $product_id,
                                'vendor_id' => $vendor_id,
                                'co_vendor_id' => $co_vendor_id,
                                'destination_id' => $destination_id,
                                'booking_date' => $value['booking_date'],
                                'booking_time' => date('H:i:s', strtotime($value['booking_time'])),
                                'actual_wt' => $value['actual_weight'],
                                'volumetric_wt' => $value['volume_weight'],
                                'chargeable_wt' => $value['chargeable_weight'],
                                'ori_hub_id' => $ori_hub_id,
                                'consignor_wt' => $value['consigner_weight'],
                                'payment_type' => $payment_type,
                                'eway_bill' => $value['eway_bill_no'],
                                'total_pcs' => $value['pcs'] != '' && $value['pcs'] > 0 ? $value['pcs'] : 1,
                                // 'created_by' => $value['created_by_id'],
                                'created_by' => isset($all_user[$value['created_by_id']]) ? $all_user[$value['created_by_id']]['id'] : 0,
                                // 'modified_by' => $value['updated_by_id'],
                                'modified_by' => isset($all_user[$value['updated_by_id']]) ? $all_user[$value['updated_by_id']]['id'] : 0,
                                'cft_value' => $value['cft_value'],
                                'dest_zone_id' => $dest_zone_id,
                                'customer_contract_id' => $value['customer_contract_id'],
                                'vendor_contract_id' => $value['vendor_contract_id'],
                                'content' => $value['shipment_content'],
                                'invoice_date' => $value['shipment_invoice_date'],
                                'invoice_no' => $value['shipment_invoice_no'],
                                'shipment_currency_id' => isset($value['shipment_value_currency']) && $value['shipment_value_currency'] != '' && isset($all_currency[strtolower(trim($value['shipment_value_currency']))]) ? $all_currency[strtolower(trim($value['shipment_value_currency']))]['id'] : 0,
                                'remarks' => $value['remark'],
                                'reference_no' => $value['ref_no'],
                                'remote_area_charges' => $value['remote_area_charges'],
                                'company_id' =>  $value['company_id'],
                                'ori_zone_id' => $ori_zone_id,
                                'cft_contract_id' => $value['cft_contract_id'],
                                'created_by_type' => $value['creator_type'] == 'User' || $value['creator_type'] == '' ? 1 : 2,
                                'created_by' => $value['creator_id'],
                                'dest_hub_id' => $dest_hub_id,
                                'reference_name' => $value['ref_name'],
                                'inscan_date' => $value['inscan_date'],
                                'inscan_time' => date('H:i:s', strtotime($value['inscan_time'])),
                                'is_inscan' => $value['is_being_inscan'] == 1 ? 1 : 2,
                                'invoice_range_id' => isset($value['invoice_range_master_code']) && $value['invoice_range_master_code'] != '' && isset($all_invoice_range[strtolower(trim($value['invoice_range_master_code']))]) ? $all_invoice_range[strtolower(trim($value['invoice_range_master_code']))]['id'] : 0,
                                'dispatch_type' => $dispatch_type,
                                'challan_no' => $value['challan_number'],
                                'dispatch_date' => $value['dispatch_date'],
                                'dispatch_time' => $value['dispatch_time'] != '' ? date('H:i:s', strtotime($value['dispatch_time'])) : '',
                                'courier_dispatch_date' => $value['co_courier_dispatch_date'],
                                'courier_dispatch_time' => $value['co_courier_dispatch_time'] != '' ? date('H:i:s', strtotime($value['co_courier_dispatch_time'])) : '',
                                'customer_contract_tat' => $value['customer_contract_tat'],
                                'invoice_type' => $value['free_form_invoice_type_id'],
                                'note_desc' => $value['free_form_note'],
                                'free_total_amount' => $value['free_form_amount'],
                                'free_note' => isset($value['free_form_note_master_code']) && $value['free_form_note_master_code'] != '' && isset($all_note[strtolower(trim($value['free_form_note_master_code']))]) ? $all_note[strtolower(trim($value['free_form_note_master_code']))]['id'] : 0,
                                'free_discount' => $value['free_form_discount_amount'],
                                'incoterm_id' => isset($value['terms_of_trade']) && $value['terms_of_trade'] != '' && isset($all_incoterm[strtolower(trim($value['terms_of_trade']))]) ? $all_incoterm[strtolower(trim($value['terms_of_trade']))]['id'] : 0,
                                'curreny_id' => isset($value['free_form_currency']) && $value['free_form_currency'] != '' && isset($all_currency[strtolower(trim($value['free_form_currency']))]) ? $all_currency[strtolower(trim($value['free_form_currency']))]['id'] : 0,
                                'project_id' => isset($value['project_code']) && $value['project_code'] != '' && isset($all_project[strtolower(trim($value['project_code']))]) ? $all_project[strtolower(trim($value['project_code']))]['id'] : 0,
                                'insurance_amount' => $value['insurance_amount'],
                                'commit_id' => $value['commit_id'],
                                'is_checked_by' => $value['is_checked'] == 1 ? $value['checked_by_id'] : 0,
                                'shipment_priority' => $shipment_priority,
                                'cod_amount' => $value['cod_amount'],
                                // 'status_id' => $value['is_locked'] == 1 ? 2 : 1,
                                'status_id' => 1,
                                'total_received' =>  $value['paid_amount'],
                                'total_balance' =>  $value['balance_amount'],
                                'sales_billing_save' => 1,
                                'purchase_billing_save' => 1,
                            );

                            if (isset($value['free_form_line_items']) && is_array($value['free_form_line_items']) && count($value['free_form_line_items']) > 0) {
                                $docket_insert['create_free_form'] = 1;
                            } else {
                                $docket_insert['create_free_form'] = 2;
                            }
                            $qry = "SELECT id,awb_no FROM docket WHERE status IN(1,2) AND migration_id='" . $value['id'] . "'";
                            $qry_exe = $this->db->query($qry);
                            $existData = $qry_exe->row_array();


                            if (isset($existData) && is_array($existData) && count($existData) > 0) {
                                $docket_id = $existData['id'];

                                $old_column = 'awb_no,customer_id,origin_id,destination_id,product_id,booking_date,vendor_id,'
                                    . 'co_vendor_id,forwarding_no,content,eway_bill,invoice_date,invoice_no,customer_contract_id,'
                                    . 'customer_contract_tat,cft_contract_id,remarks,status_id,actual_wt,volumetric_wt,consignor_wt,'
                                    . 'add_wt,chargeable_wt,total_pcs,dispatch_type,shipment_priority,company_id,
    project_id,forwarding_no_2,reference_no,reference_name,shipment_value,shipment_currency_id,
    dispatch_date,dispatch_time,courier_dispatch_date,courier_dispatch_time,instructions,payment_type,ori_hub_id,
    dest_hub_id,challan_no,commit_id,cod_amount,insurance_amount';
                                $docket_old_data = $this->gm->get_selected_record('docket', $old_column, $where = array('id' => $docket_id, 'status !=' => 3), array());

                                $docket_extra_column = 'po_number,brand_id,pickup_boy_id,cod_status,inscan_date,paid_amount,balance_amount,entry_number,total_quantity,forwarder_id,estimate_no,license_location_id,token_number,project_name';
                                $extra_old_data = $this->gm->get_selected_record('docket_extra_field', $docket_extra_column, array('docket_id' => $docket_id, 'status' => 1), array());

                                $shipper_consi_col = 'code,name,company_name,address1,address2,address3,pincode,city,state,country,contact_no,email_id,
dob,gstin_type,gstin_no,doc_path';
                                $shipper_old_data = $this->gm->get_selected_record('docket_shipper', $shipper_consi_col, array('docket_id' => $docket_id, 'status' => 1), array());
                                $consignee_old_data = $this->gm->get_selected_record('docket_consignee', $shipper_consi_col, array('docket_id' => $docket_id, 'status' => 1), array());


                                $this->gm->update('docket', $docket_insert, '', array('id' => $docket_id));
                            } else {
                                $docket_id = $this->gm->insert('docket', $docket_insert);
                                $mode = 'insert';
                            }



                            $docket_consignee = array(
                                'docket_id' => $docket_id,
                                'name' =>  $value['consignee_name'],
                                'contact_no' =>  $value['consignee_contact_no'],
                                'consignee_id' => $consignee_id,
                                'address1' => $value['consignee_address_line_1'],
                                'company_name' => $value['consignee_company_name'],
                                'address2' => $value['consignee_address_line_2'],
                                'address3' => $value['consignee_address_line_3'],
                                'city' => $value['consignee_city'],
                                'state' => $value['consignee_state'],
                                'country' => isset($value['consignee_country']) && $value['consignee_country'] != '' && isset($all_country[strtolower(trim($value['consignee_country']))]) ? $all_country[strtolower(trim($value['consignee_country']))]['id'] : 0,
                                'pincode' => $value['consignee_zip_code'],
                                'email_id' => $value['consignee_email'],
                                'gstin_type' =>   isset($value['consignee_gstin_type']) && $value['consignee_gstin_type'] != '' && isset($all_gst_type[strtolower(trim($value['consignee_gstin_type']))]) ? $all_gst_type[strtolower(trim($value['consignee_gstin_type']))]['id'] : 0,
                                'gstin_no' => $value['consignee_gstin_no'],
                                'skynet_service' => $value['skyline_carrier_service'],
                                'contact_no2' => $value['consignee_contact_no_1'],
                                'dial_code' => $value['consignee_dial_code'],
                            );

                            $deletq = "DELETE FROM docket_consignee WHERE docket_id='" . $docket_id . "'";
                            $this->db->query($deletq);
                            $this->gm->insert('docket_consignee', $docket_consignee);

                            // if (isset($existData['awb_no']) && $existData['awb_no'] != '') {
                            //     $this->gm->update('docket_consignee', $docket_consignee, '', array('docket_id' => $docket_id));
                            // } else {
                            //     $this->gm->insert('docket_consignee', $docket_consignee);
                            // }


                            $docket_shipper = array(
                                'docket_id' => $docket_id,
                                'name' =>  $value['shipper_name'],
                                'code' =>  $value['shipper_code'],
                                'shipper_id' => $shipper_id,
                                'address1' => $value['shipper_address_line_1'],
                                'company_name' => $value['shipper_company_name'],
                                'address2' => $value['shipper_address_line_2'],
                                'address3' => $value['shipper_address_line_3'],
                                'city' => $value['shipper_city'],
                                'state' => $value['shipper_state'],
                                'country' => isset($value['shipper_country']) && $value['shipper_country'] != '' && isset($all_country[strtolower(trim($value['shipper_country']))]) ? $all_country[strtolower(trim($value['shipper_country']))]['id'] : 0,
                                'pincode' => $value['shipper_zip_code'],
                                'contact_no' => $value['shipper_contact_no'],
                                'email_id' => $value['shipper_email'],
                                'gstin_type' => $shipper_gstin_type,
                                'gstin_no' => $value['shipper_gstin_no'],
                                'dob' => $value['shipper_date_of_birth'],
                                'dial_code' => $value['shipper_dial_code'],
                            );
                            $deletq = "DELETE FROM docket_shipper WHERE docket_id='" . $docket_id . "'";
                            $this->db->query($deletq);
                            $this->gm->insert('docket_shipper', $docket_shipper);
                            // if (isset($existData['awb_no']) && $existData['awb_no'] != '') {
                            //     $this->gm->update('docket_shipper', $docket_shipper, '', array('docket_id' => $docket_id));
                            // } else {
                            //     $this->gm->insert('docket_shipper', $docket_shipper);
                            // }


                            if ($value['is_custom_amount'] == 1) {
                                $sales_edited[] = 'freight_amount';
                            }
                            if ($value['is_custom_fsc'] == 1) {
                                $sales_edited[] = 'fsc_amount';
                            }
                            if ($value['is_custom_grand_total'] == 1) {
                                $sales_edited[] = 'grand_total';
                            }
                            if ($value['change_is_gst_docket'] == 1) {
                                $sales_edited[] = 'gst_applicable';
                            }
                            $docket_sales_billing = array(
                                'docket_id' => $docket_id,
                                'freight_amount' => $value['amount'],
                                'fsc_amount' => $value['fuel_surcharge_amount'],
                                'sub_total' => $value['sub_total'],
                                'discount_amount' => $value['discount_amount'],
                                'taxable_amt' => $value['taxable_amount'],
                                'non_taxable_amt' => $value['non_taxable_amount'],
                                'cgst_amount' => $value['cgst_amount'],
                                'sgst_amount' => $value['sgst_amount'],
                                'igst_amount' => $value['igst_amount'],
                                'grand_total' => $value['grand_total'],
                                'edited_field' => isset($sales_edited) ? implode(",", $sales_edited) : '',
                                'discount_per' => $value['discount_percentage'],
                                'discount_amount' => $value['total_discount_amount'],
                                'is_gst_apply' => $value['is_gst_docket'] == 1 || $customer_gst_apply == 1 ? 1 : 2,
                                'fsc_per' => $value['fsc_percentage'],
                                'freight_after_dis' => $value['amount'] - $value['total_discount_amount']
                            );

                            if ($value['taxable_amount'] > 0) {
                                $total_tax = $value['cgst_amount'] + $value['sgst_amount'] + $value['igst_amount'];
                                $docket_sales_billing['gst_per'] = ($total_tax * 100) / $value['taxable_amount'];
                            }

                            $deletq = "DELETE FROM docket_sales_billing WHERE docket_id='" . $docket_id . "'";
                            $this->db->query($deletq);
                            $this->gm->insert('docket_sales_billing', $docket_sales_billing);
                            // if (isset($existData['awb_no']) && $existData['awb_no'] != '') {
                            //     $this->gm->update('docket_sales_billing', $docket_sales_billing, '', array('docket_id' => $docket_id));
                            // } else {
                            //     $this->gm->insert('docket_sales_billing', $docket_sales_billing);
                            // }

                            if ($value['is_custom_vendor_amount'] == 1) {
                                $purchase_edited[] = 'freight_amount';
                            }
                            $docket_purchase_billing = array(
                                'docket_id' => $docket_id,
                                'invoice_no1' => $value['vendor_invoice'],
                                'freight_amount' => $value['vendor_amount'],
                                'is_gst_apply' => $co_vendor_gst_apply == 1 ? 1 : 2,
                                'co_vendor_id' => isset($value['purchase_co_vendor_code']) && $value['purchase_co_vendor_code'] != '' && isset($all_co_vendor[strtolower(trim($value['purchase_co_vendor_code']))]) ? $all_co_vendor[strtolower(trim($value['purchase_co_vendor_code']))]['id'] : 0,
                                'vendor_id' => isset($value['purchase_vendor_code']) && $value['purchase_vendor_code'] != '' && isset($all_vendor[strtolower(trim($value['purchase_vendor_code']))]) ? $all_vendor[strtolower(trim($value['purchase_vendor_code']))]['id'] : 0,
                                'product_id' => isset($value['purchase_product_code']) && $value['purchase_product_code'] != '' && isset($all_product[strtolower(trim($value['purchase_product_code']))]) ? $all_product[strtolower(trim($value['purchase_product_code']))]['id'] : 0,
                                'discount_amount' => $value['purchase_discount_amount'],
                                'fsc_amount' => $value['purchase_fuel_surcharge_amount'],
                                'sub_total' => $value['purchase_total'],
                                'cgst_amount' => $value['purchase_cgst_amount'],
                                'sgst_amount' => $value['purchase_sgst_amount'],
                                'igst_amount' => $value['purchase_igst_amount'],
                                'grand_total' => $value['purchase_grand_total'],
                                'chargeable_wt' => $value['vendor_chargeable_weight'],
                                'volumetric_wt' => $value['vendor_volume_weight'],
                                'actual_wt' => $value['vendor_actual_weight'],
                                'edited_field' => isset($purchase_edited) ? implode(",", $purchase_edited) : '',
                                'total_pcs' => $value['vendor_pcs'],
                                'cft_value' => $value['purchase_cft_value'],
                                'cft_contract_id' => $value['purchase_cft_contract_id'],
                                'dest_zone_id' => isset($value['purchase_destination_zone']) && $value['purchase_destination_zone'] != '' && isset($all_zone[strtolower(trim($value['purchase_destination_zone']))]) ? $all_zone[strtolower(trim($value['purchase_destination_zone']))]['id'] : 0,
                                'invoice_no1' => $value['vendor_invoice'],
                                'invoice_no2' => $value['vendor_invoice_2'],
                                'invoice_no3' => $value['vendor_invoice_3'],
                                'invoice_no4' => $value['vendor_invoice_4'],
                                'invoice_remark1' => $value['invoice_remarks_1'],
                                'invoice_remark2' => $value['invoice_remarks_2'],
                                'invoice_remark3' => $value['invoice_remarks_3'],
                                'invoice_remark4' => $value['invoice_remarks_4'],
                                'vendor_contract_tat' => $value['vendor_contract_tat'],
                                'fsc_per' => $value['purchase_fsc_percentage'],
                                'is_gst_apply' => $value['is_purchase_gst_docket'] == 1 ? 1 : 2,
                                'vendor_contract_id' => $value['vendor_contract_id'],
                                'freight_after_dis' => $value['vendor_amount'] - $value['purchase_discount_amount']
                            );


                            $deletq = "DELETE FROM docket_purchase_billing WHERE docket_id='" . $docket_id . "'";
                            $this->db->query($deletq);
                            $this->gm->insert('docket_purchase_billing', $docket_purchase_billing);
                            // if (isset($existData['awb_no']) && $existData['awb_no'] != '') {
                            //     $this->gm->update('docket_purchase_billing', $docket_purchase_billing, '', array('docket_id' => $docket_id));
                            // } else {
                            //     $this->gm->insert('docket_purchase_billing', $docket_purchase_billing);
                            // }



                            $docket_extra_field = array(
                                'docket_id' => $docket_id,
                                'paid_amount' =>  $value['paid_amount'],
                                'balance_amount' =>  $value['balance_amount'],
                                'po_number' => $value['po_number'],
                                'brand_id' => isset($value['brand_code']) && $value['brand_code'] != '' && isset($all_brand[strtolower(trim($value['brand_code']))]) ? $all_brand[strtolower(trim($value['brand_code']))]['id'] : 0,
                                'docket_edit_field' => isset($awb_edited) ? implode(",", $awb_edited) : '',
                            );
                            $deletq = "DELETE FROM docket_extra_field WHERE docket_id='" . $docket_id . "'";
                            $this->db->query($deletq);
                            $this->gm->insert('docket_extra_field', $docket_extra_field);
                            // if (isset($existData['awb_no']) && $existData['awb_no'] != '') {
                            //     $this->gm->update('docket_extra_field', $docket_extra_field, '', array('docket_id' => $docket_id));
                            // } else {
                            //     $this->gm->insert('docket_extra_field', $docket_extra_field);
                            // }


                            $sticker_print_format = 0;
                            if ($value['sticker_print_format'] != '') {
                                if ($value['sticker_print_format'] == 'thermal') {
                                    $sticker_print_format = 1;
                                } else if ($value['sticker_print_format'] == 'a4') {
                                    $sticker_print_format = 2;
                                }
                            }
                            $csb_selection = 0;
                            if ($value['fedex_csb_selection'] != '') {
                                $fedex_csb_selection = strtoupper($value['fedex_csb_selection']);
                                if ($fedex_csb_selection == 'CS4') {
                                    $csb_selection = 1;
                                } else if ($fedex_csb_selection == 'IPHV') {
                                    $csb_selection = 2;
                                } else if ($fedex_csb_selection == 'IPLV') {
                                    $csb_selection = 3;
                                }
                            }
                            $terms_of_invoice = '';
                            if ($value['fedex_terms_of_invoice'] != '') {
                                $terms_of_invoice =  isset($value['fedex_terms_of_invoice']) && $value['fedex_terms_of_invoice'] != '' && isset($all_incoterm[strtolower(trim($value['fedex_terms_of_invoice']))]) ? $all_incoterm[strtolower(trim($value['fedex_terms_of_invoice']))]['id'] : 0;
                            } else if ($value['dhl_terms_of_trade'] != '') {
                                $terms_of_invoice =  isset($value['dhl_terms_of_trade']) && $value['dhl_terms_of_trade'] != '' && isset($all_incoterm[strtolower(trim($value['dhl_terms_of_trade']))]) ? $all_incoterm[strtolower(trim($value['dhl_terms_of_trade']))]['id'] : 0;
                            }

                            $duty_payor = 0;
                            if ($value['fedex_duty_payor'] != '') {
                                $fedex_duty_payor = strtoupper($value['fedex_duty_payor']);
                                if ($fedex_duty_payor == 'RECIPIENT') {
                                    $duty_payor = 1;
                                } else if ($fedex_duty_payor == 'SENDER') {
                                    $duty_payor = 2;
                                }
                            }

                            $fedex_api_service = 0;
                            if ($value['fedex_api_service'] != '') {
                                $fedex_api_service_name = strtoupper($value['fedex_api_service']);
                                if ($fedex_api_service_name == 'FEDEX_EXPRESS_SAVER') {
                                    $fedex_api_service = 1;
                                } else if ($fedex_api_service_name == 'PRIORITY_OVERNIGHT') {
                                    $fedex_api_service = 2;
                                } else if ($fedex_api_service_name == 'STANDARD_OVERNIGHT') {
                                    $fedex_api_service = 3;
                                } else if ($fedex_api_service_name == 'INTERNATIONAL_ECONOMY') {
                                    $fedex_api_service = 4;
                                } else if ($fedex_api_service_name == 'INTERNATIONAL_PRIORITY') {
                                    $fedex_api_service = 5;
                                } else if ($fedex_api_service_name == 'INTERNATIONAL_PRIORITY_FREIGHT') {
                                    $fedex_api_service = 6;
                                }
                            }

                            $dhl_special_service = '';
                            if ($value['dhl_special_service'] == 'DDP') {
                                $dhl_special_service = 1;
                            }

                            $payment_mode = 0;
                            if ($value['delhivery_payment_mode'] != '') {
                                $payment_mode_name = strtolower($value['delhivery_payment_mode']);
                                if ($payment_mode_name == 'prepaid') {
                                    $payment_mode = 1;
                                } else if ($payment_mode_name == 'cod') {
                                    $payment_mode = 2;
                                }
                            }

                            $docket_service_field = array(
                                'docket_id' => $docket_id,
                                'dhl_using_igst' =>  $value['dhl_is_using_igst'] == 1 ? 1 : 2,
                                'dhl_using_bond_ut' =>  $value['dhl_is_using_bond_ut'] == 1 ? 1 : 2,
                                'total_igst' =>  $value['dhl_total_igst'] > 0 ? $value['dhl_total_igst'] : $value['total_gst_paid'],
                                'sticker_print_format' => $sticker_print_format,
                                'declared_value' =>  $value['declared_value'],
                                'dhl_non_plt' => $value['dhl_dxb_is_paperless_trade'] == 1 ? 1 : 2,
                                'csb_selection' => $csb_selection,
                                'terms_of_invoice' => $terms_of_invoice,
                                'is_using_gst' => $value['is_using_gst'] == 1 ? 1 : 2,
                                'special_service' => $value['special_service_required'] == 1 ? 1 : 2,
                                'special_service_type' => $value['special_service_type'] == 'DANGEROUS_GOODS' ? 1 : 0,
                                'special_service_accessibility' => $value['special_service_accessibility'] == 'ACCESSIBLE' ? 1 : 2,
                                'duty_payor' => $duty_payor,
                                'fedex_api_service' => $fedex_api_service,
                                'dhl_special_service' => $dhl_special_service,
                                'dhl_more_than_50k' => $value['is_value_more_than_50_k'] == 1 ? 1 : 2,
                                'payment_mode' => $payment_mode
                            );
                            $deletq = "DELETE FROM docket_service_field WHERE docket_id='" . $docket_id . "'";
                            $this->db->query($deletq);
                            $this->gm->insert('docket_service_field', $docket_service_field);
                            // if (isset($existData['awb_no']) && $existData['awb_no'] != '') {
                            //     $this->gm->update('docket_service_field', $docket_service_field, '', array('docket_id' => $docket_id));
                            // } else {
                            //     $this->gm->insert('docket_service_field', $docket_service_field);
                            // }


                            $docket_delivery = array(
                                'docket_id' => $docket_id,
                                'expected_date' =>  $value['expected_date'],
                                'expected_time' =>  $value['expected_time'] != '' ? date('H:i:s', strtotime($value['expected_time'])) : '',
                                'delivery_date' =>  $value['delivery_date'],
                                'delivery_time' =>  $value['delivery_time'] != '' ? date('H:i:s', strtotime($value['delivery_time'])) : '',
                                'status_reason' => $value['reason_for_status'],
                                'modified_by' => $value['status_updated_by'],
                                'remark' => $value['delivery_remarks'],
                                'receiver_name' => $value['receiever_name'],
                                'status_code' => $value['docket_status_code'],
                                'status_name' => $value['docket_status_name'],
                                'receiver_mobile' => $value['receiver_mobile_no'],
                                'pod_uploaded_date' => $value['pod_uploaded_date'],
                                'pod_uploaded_time' => $value['pod_uploaded_time'] != '' ? date('H:i:s', strtotime($value['pod_uploaded_time'])) : '',
                            );

                            $deletq = "DELETE FROM docket_delivery WHERE docket_id='" . $docket_id . "'";
                            $this->db->query($deletq);
                            $this->gm->insert('docket_delivery', $docket_delivery);
                            // if (isset($existData['awb_no']) && $existData['awb_no'] != '') {
                            //     $this->gm->update('docket_delivery', $docket_delivery, '', array('docket_id' => $docket_id));
                            // } else {
                            //     $this->gm->insert('docket_delivery', $docket_delivery);
                            // }

                            //ADD DOCKET CAHRGES

                            $sale_charge_total = 0;
                            $purchase_charge_total = 0;
                            $docket_charge = array();

                            $docket_charges_api = isset($value['docket_charges']) ? $value['docket_charges'] : array();
                            $keys = array_column($docket_charges_api, 'item_index');
                            array_multisort($keys, SORT_ASC, $docket_charges_api);
                            if (isset($docket_charges_api) && is_array($docket_charges_api) && count($docket_charges_api) > 0) {
                                foreach ($docket_charges_api as $ch_key => $ch_value) {
                                    $docket_charge[] = array(
                                        'docket_id' => $docket_id,
                                        'charge_id' => $ch_value['charge_master_id'],
                                        'rate_mod_id' => $ch_value['sale_rate_modifier_id'],
                                        'charge_amount' => $ch_value['value'],
                                        'charge_check' => $ch_value['is_applied'] == 1 ? 1 : 2,
                                        'billing_type' => 1, //SALE,
                                        'created_date' => $ch_value['created_at'],
                                        'modified_date' => $ch_value['updated_at'],
                                    );
                                    $sale_charge_total = $sale_charge_total + $ch_value['value'];

                                    $docket_charge[] = array(
                                        'docket_id' => $docket_id,
                                        'charge_id' => $ch_value['charge_master_id'],
                                        'rate_mod_id' => $ch_value['purchase_rate_modifier_id'],
                                        'charge_amount' => $ch_value['purchase_value'],
                                        'charge_check' => $ch_value['is_vendor_applied'] == 1 ? 1 : 2,
                                        'billing_type' => 2, //SALE,
                                        'created_date' => $ch_value['created_at'],
                                        'modified_date' => $ch_value['updated_at'],
                                    );
                                    $purchase_charge_total = $purchase_charge_total + $ch_value['purchase_value'];
                                }
                            }
                            $this->gm->update('docket_sales_billing', array('total_other_charge' => $sale_charge_total), '', array('docket_id' => $docket_id));
                            $this->gm->update('docket_purchase_billing', array('total_other_charge' => $purchase_charge_total), '', array('docket_id' => $docket_id));


                            $deletq = "DELETE FROM docket_charges WHERE docket_id='" . $docket_id . "'";

                            if (isset($docket_charge) && is_array($docket_charge) && count($docket_charge) > 0) {
                                $this->db->insert_batch('docket_charges', $docket_charge);
                            }


                            //ADD DOCKET TRACKING
                            $docket_tracking = array();
                            if (isset($value['docket_events']) && is_array($value['docket_events']) && count($value['docket_events']) > 0) {
                                foreach ($value['docket_events'] as $ev_key => $ev_value) {
                                    $docket_tracking[] = array(
                                        'docket_id' => $docket_id,
                                        'created_date' => $ev_value['created_at'],
                                        'modified_date' => $ev_value['updated_at'],
                                        'event_date_time' => $ev_value['event_at'],
                                        'event_type' => $ev_value['event_type'],
                                        'event_desc' => $ev_value['event_description'],
                                        'event_location' => $ev_value['event_location'],
                                        'add_city' => $ev_value['add_city'],
                                        'add_state' => $ev_value['add_state_or_province_code'],
                                        'add_zip' => $ev_value['add_postal_code'],
                                        'add_country' => $ev_value['add_country_code'],
                                        'add_country_name' => $ev_value['add_country_name'],
                                        'event_add_type' => $ev_value['event_type'] == 'APP_ENTRY' ? 1 : 2,
                                        'tracking_type' => 1
                                    );
                                }
                            }
                            $deletq = "DELETE FROM docket_tracking WHERE docket_id='" . $docket_id . "'";
                            $this->db->query($deletq);
                            if (isset($docket_tracking) && is_array($docket_tracking) && count($docket_tracking) > 0) {
                                $this->db->insert_batch('docket_tracking', $docket_tracking);
                            }

                            //ADD DOCKET ITEM
                            $docket_items_api = isset($value['docket_items']) ? $value['docket_items'] : array();
                            $keys = array_column($docket_items_api, 'item_index');
                            array_multisort($keys, SORT_ASC, $docket_items_api);

                            $deletq = "DELETE FROM docket_items WHERE docket_id='" . $docket_id . "'";
                            $this->db->query($deletq);
                            $parcel_tracking = array();
                            if (isset($docket_items_api) && is_array($docket_items_api) && count($docket_items_api) > 0) {
                                foreach ($docket_items_api as $it_key => $it_value) {
                                    $docket_items = array(
                                        'docket_id' => $docket_id,
                                        'created_date' => $it_value['created_at'],
                                        'modified_date' => $it_value['updated_at'],
                                        'parcel_no' => $it_value['awb'],
                                        'item_length' => $it_value['length'],
                                        'item_width' => $it_value['width'],
                                        'item_height' => $it_value['height'],
                                        'box_count' => $it_value['number_of_boxes'],
                                        'volumetric_wt' => $it_value['volume_weight'],
                                        'actual_wt' => $it_value['actual_weight'],
                                        'chargeable_wt' => $it_value['chargeable_weight'],
                                        'box_no' => $it_value['box_no'],
                                        'delivery_date' => $it_value['delivery_date'],
                                        'delivery_time' => date('H:i:s', strtotime($it_value['delivery_time'])),
                                        'receiver_name' => $it_value['receiver_name'],
                                        'delivery_remarks' => $it_value['delivery_remarks'],
                                    );
                                    $docket_items_id = $this->gm->insert('docket_items', $docket_items);


                                    if (isset($it_value['docket_item_events']) && is_array($it_value['docket_item_events']) && count($it_value['docket_item_events']) > 0) {
                                        foreach ($it_value['docket_item_events'] as $ev_key => $ev_value) {
                                            $parcel_tracking[] = array(
                                                'docket_id' => $docket_id,
                                                'created_date' => $ev_value['created_at'],
                                                'modified_date' => $ev_value['updated_at'],
                                                'event_date_time' => $ev_value['event_at'],
                                                'event_type' => $ev_value['event_type'],
                                                'event_desc' => $ev_value['event_description'],
                                                'event_location' => $ev_value['event_location'],
                                                'add_city' => $ev_value['add_city'],
                                                'add_state' => $ev_value['add_state_or_province_code'],
                                                'add_zip' => $ev_value['add_postal_code'],
                                                'add_country' => $ev_value['add_country_code'],
                                                'add_country_name' => $ev_value['add_country_name'],
                                                'event_add_type' => $ev_value['event_type'] == 'APP_ENTRY' ? 1 : 2,
                                                'tracking_type' => 2,
                                                'docket_item_id' => $docket_items_id
                                            );
                                        }
                                    }
                                }
                            }

                            $deletq = "DELETE FROM docket_tracking WHERE tracking_type=2 AND  docket_id='" . $docket_id . "'";
                            $this->db->query($deletq);

                            if (isset($parcel_tracking) && is_array($parcel_tracking) && count($parcel_tracking) > 0) {
                                $this->db->insert_batch('docket_tracking', $parcel_tracking);
                            }

                            //ADD MATERIAL ITEM
                            $material_items_api = isset($value['material_items']) ? $value['material_items'] : array();
                            $keys = array_column($material_items_api, 'item_index');
                            array_multisort($keys, SORT_ASC, $material_items_api);

                            $docket_material = array();
                            if (isset($material_items_api) && is_array($material_items_api) && count($material_items_api) > 0) {
                                foreach ($material_items_api as $mkey => $mvalue) {
                                    $docket_material[] = array(
                                        'docket_id' => $docket_id,
                                        'created_date' => $mvalue['created_at'],
                                        'modified_date' => $mvalue['updated_at'],
                                        'material_id' => isset($mvalue['code']) && $mvalue['code'] != '' && isset($all_material[strtolower(trim($mvalue['code']))]) ? $all_material[strtolower(trim($mvalue['code']))]['id'] : 0,
                                        'hs_code' => $mvalue['hs_code'],
                                        'rate' => $mvalue['unit_rate'],
                                        'total_amount' => $mvalue['total_amount'],
                                        'gst_type' => strtoupper($mvalue['gst_type'] == 'IGST') ? 1 : 2,
                                        'cgst_amount' => $mvalue['cgst_amount'],
                                        'igst_amount' => $mvalue['igst_amount'],
                                        'sgst_amount' => $mvalue['sgst_amount'],
                                    );
                                }
                            }
                            $deletq = "DELETE FROM docket_material WHERE docket_id='" . $docket_id . "'";
                            $this->db->query($deletq);
                            if (isset($docket_material) && is_array($docket_material) && count($docket_material) > 0) {
                                $this->db->insert_batch('docket_material', $docket_material);
                            }

                            //ADD PAYMENT RECEIPT
                            $payment_receipts_api = isset($value['payment_receipts']) ? $value['payment_receipts'] : array();
                            $keys = array_column($payment_receipts_api, 'item_index');
                            array_multisort($keys, SORT_ASC, $payment_receipts_api);

                            $docket_receipt = array();
                            if (isset($payment_receipts_api) && is_array($payment_receipts_api) && count($payment_receipts_api) > 0) {
                                foreach ($payment_receipts_api as $re_key => $re_value) {

                                    $payment_type = 0;
                                    if ($re_value['mode_of_payment'] != '') {
                                        $docket_status_code = strtolower($re_value['mode_of_payment']);
                                        if ($docket_status_code == 'cash') {
                                            $payment_type = 1;
                                        } else if ($docket_status_code == 'cheque') {
                                            $payment_type = 2;
                                        } else if ($docket_status_code == 'online') {
                                            $payment_type = 3;
                                        } else if ($docket_status_code == 'credit') {
                                            $payment_type = 4;
                                        } else  if ($docket_status_code == 'foc') {
                                            $payment_type = 5;
                                        } else if ($docket_status_code == 'cash-credit') {
                                            $payment_type = 6;
                                        } else if ($docket_status_code == 'cod') {
                                            $payment_type = 7;
                                        } else if ($docket_status_code == 'to_pay') {
                                            $payment_type = 8;
                                        } else if ($docket_status_code == 'cod_topay') {
                                            $payment_type = 9;
                                        }
                                    }

                                    $docket_receipt[] = array(
                                        'docket_id' => $docket_id,
                                        'created_date' => $re_value['created_at'],
                                        'modified_date' => $re_value['updated_at'],
                                        'receipt_date' => $re_value['payment_date'],
                                        'payment_type_id' => $payment_type,
                                        'receipt_amount' => $re_value['amount'],
                                        'particular' => $re_value['particulars'],
                                        'payment_no' => $re_value['payment_no'],
                                        'account_no_id' => isset($re_value['account_no']) && $re_value['account_no'] != '' && isset($all_account[strtolower(trim($re_value['account_no']))]) ? $all_account[strtolower(trim($re_value['account_no']))]['id'] : 0,
                                    );
                                }
                            }

                            $deletq = "DELETE FROM docket_receipt WHERE docket_id='" . $docket_id . "'";
                            $this->db->query($deletq);
                            if (isset($docket_receipt) && is_array($docket_receipt) && count($docket_receipt) > 0) {
                                $this->db->insert_batch('docket_receipt', $docket_receipt);
                            }

                            //ADD FREE FORM ITEM 
                            //ADD PAYMENT RECEIPT
                            $free_form_line_items_api = isset($value['free_form_line_items']) ? $value['free_form_line_items'] : array();
                            $keys = array_column($free_form_line_items_api, 'item_index');
                            array_multisort($keys, SORT_ASC, $free_form_line_items_api);
                            $free_form_item = array();
                            if (isset($free_form_line_items_api) && is_array($free_form_line_items_api) && count($free_form_line_items_api) > 0) {
                                foreach ($free_form_line_items_api as $fkey => $fvalue) {
                                    $free_form_item[] = array(
                                        'docket_id' => $docket_id,
                                        'created_date' => $fvalue['created_at'],
                                        'modified_date' => $fvalue['updated_at'],
                                        'final_amount' => $fvalue['amount'] != '' ? $fvalue['amount'] : 0,
                                        'quantity' =>  $fvalue['no_of_packages'] != '' ? $fvalue['no_of_packages'] : 0,
                                        'box_no' => $fvalue['box_no'] != '' ? $fvalue['box_no'] : 0,
                                        'unit_rate' => $fvalue['rate'] != '' ? $fvalue['rate'] : 0,
                                        'hs_code' => $fvalue['hscode'],
                                        'unit_wt' => $fvalue['unit_weight'] != '' ? $fvalue['unit_weight'] : 0,
                                        'igst_amount' => $fvalue['igst_amount'] != '' ? $fvalue['igst_amount'] : 0,
                                        'description_id' => (!isset($fvalue['is_custom_desc']) && $fvalue['is_custom_desc'] != 1) && isset($fvalue['description']) && $fvalue['description'] != '' && isset($all_free_item[strtolower(trim($fvalue['description']))]) ? $all_free_item[strtolower(trim($fvalue['description']))]['id'] : 0,
                                        'description_name' => isset($fvalue['is_custom_desc']) && $fvalue['is_custom_desc'] == 1 ? $fvalue['description'] : '',
                                        'unit_type' => isset($fvalue['unit_of_measurement']) && $fvalue['unit_of_measurement'] != '' && isset($all_unit_type[strtolower(trim($fvalue['unit_of_measurement']))]) ? $all_unit_type[strtolower(trim($fvalue['unit_of_measurement']))]['id'] : 0,
                                    );
                                }
                            }

                            $deletq = "DELETE FROM docket_free_form_invoice WHERE docket_id='" . $docket_id . "'";
                            $this->db->query($deletq);
                            if (isset($free_form_item) && is_array($free_form_item) && count($free_form_item) > 0) {
                                $this->db->insert_batch('docket_free_form_invoice', $free_form_item);
                            }


                            $docket_entry_invoice = array();
                            if (isset($value['shipment_invoice_items']) && is_array($value['shipment_invoice_items']) && count($value['shipment_invoice_items']) > 0) {
                                foreach ($value['shipment_invoice_items'] as $sh_key => $sh_value) {
                                    echo $sh_value['invoice_currency'];
                                    $docket_entry_invoice[] = array(
                                        'docket_id' => $docket_id,
                                        'invoice_date' => $sh_value['invoice_date'] != '' ? $sh_value['invoice_date'] : '0000-00-00',
                                        'invoice_no' => $sh_value['invoice_no'],
                                        'order_no' => $sh_value['order_no'],
                                        'currecny_id' => isset($sh_value['invoice_currency']) && $sh_value['invoice_currency'] != '' && isset($all_currency[strtolower(trim($sh_value['invoice_currency']))]) ? $all_currency[strtolower(trim($sh_value['invoice_currency']))]['id'] : 0,
                                        'invoice_amount' => $sh_value['invoice_value'],
                                        'eway_bill' => $sh_value['eway_bill_no'],
                                        'created_date' => $sh_value['created_at'],
                                        'modified_date' => $sh_value['updated_at'],
                                    );
                                }
                            }

                            $deletq = "DELETE FROM docket_entry_invoice WHERE docket_id='" . $docket_id . "'";
                            $this->db->query($deletq);
                            if (isset($docket_entry_invoice) && is_array($docket_entry_invoice) && count($docket_entry_invoice) > 0) {
                                $this->db->insert_batch('docket_entry_invoice', $docket_entry_invoice);
                            }
                            $attach_insert = array();

                            $attchment_label = array(
                                array('name' => 'docket_image', 'col' => 'docket_image'),
                                array('name' => 'invoice_image', 'col' => 'invoice_image'),
                                array('name' => 'invoice_images', 'col' => 'invoice_image'),
                                array('name' => 'signature', 'col' => 'signature'),
                                array('name' => 'shipment_images', 'col' => 'shipment_image'),
                                array('name' => 'shipment_invoice_images', 'col' => 'shipment_invoice_image'),
                                array('name' => 'pod_image', 'col' => 'pod_image'),
                                array('name' => 'vendor_awb_image', 'col' => 'vendor_awb_image'),
                                array('name' => 'customer_awb_image', 'col' => 'customer_awb_image'),
                                array('name' => 'vendor_challan_image', 'col' => 'vendor_challan_image'),
                                array('name' => 'declaration_bill_copy_image', 'col' => 'eway_bill_copy'),
                                array('name' => 'stickers', 'col' => 'api_label_print'),
                                array('name' => 'docket_inscan_images', 'col' => 'inscan_image'),
                                array('name' => 'receiever_signature', 'col' => 'receiever_signature'),
                            );

                            $deletq = "DELETE FROM media_attachment WHERE module_id='" . $docket_id . "'
                            AND module_type=1";
                            $this->db->query($deletq);


                            foreach ($attchment_label as $at_key => $at_value) {
                                if (isset($value[$at_value['name']]) && is_array($value[$at_value['name']]) && count($value[$at_value['name']]) > 0) {

                                    if (isset($value[$at_value['name']][0])) {
                                        foreach ($value[$at_value['name']] as $fikey => $fivalue) {

                                            $file_name = $docket_id . date('YmdHis') . '_'  . rand(1000, 9999) . '_' . basename($fivalue['file']);  // to get file name
                                            $file_name = urldecode($file_name);
                                            $file_path = create_year_month_dir('docket_media') . '/' . $file_name;
                                            file_put_contents($file_path, @file_get_contents($fivalue['file']));

                                            //ADD ATTACHMENT
                                            $attach_insert[] = array(
                                                'module_id' => $docket_id,
                                                'module_type' => 1,
                                                'media_key' => $at_value['col'],
                                                'media_path' => $file_path,
                                                'created_date' => date('Y-m-d H:i:s'),
                                                'created_mode' => $at_value['name'] == 'stickers' ? 2 : 1
                                            );
                                        }
                                    } else {
                                        $file_value = $value[$at_value['name']];
                                        $file_name = $docket_id . date('YmdHis') . '_'  . rand(1000, 9999) . '_' .  basename($file_value['file']); // to get file name
                                        $file_name = urldecode($file_name);
                                        $file_path = create_year_month_dir('docket_media') . '/' . $file_name;
                                        file_put_contents($file_path, @file_get_contents($file_value['file']));

                                        //ADD ATTACHMENT
                                        $attach_insert[] = array(
                                            'module_id' => $docket_id,
                                            'module_type' => 1,
                                            'media_key' => $at_value['col'],
                                            'media_path' => $file_path,
                                            'created_date' => date('Y-m-d H:i:s'),
                                            'created_mode' => $at_value['name'] == 'stickers' ? 2 : 1
                                        );
                                    }
                                }
                            }

                            if (isset($attach_insert) && is_array($attach_insert) && count($attach_insert) > 0) {
                                $this->db->insert_batch('media_attachment', $attach_insert);
                            }
                            if (isset($value['shipper_kyc_image']['file']) || isset($value['shipper_kyc_image']['file'])) {
                                //ADD SHIPPER KYC
                                $shipper_data = $this->gm->get_selected_record('docket_shipper', 'id,shipper_id', $where = array('docket_id' => $docket_id, 'status' => 1), array());
                                if (isset($shipper_data) && is_array($shipper_data) && count($shipper_data) > 0) {
                                    if ($shipper_data['shipper_id'] > 0) {
                                        $shipper_id = $shipper_data['shipper_id'];
                                        $shipper_type = 2;
                                    } else {
                                        $shipper_id = $shipper_data['id'];
                                        $shipper_type = 5;
                                    }

                                    $doc_insert =  array(
                                        'module_id' => $shipper_id,
                                        'module_type' => $shipper_type,
                                        'doc_type_id' => 4,
                                        'doc_no' => '',
                                        'doc_name' => ''
                                    );

                                    if (isset($value['shipper_kyc_image']['file'])) {
                                        $file_name = $docket_id . date('YmdHis') . '_'  . rand(1000, 9999) . '_' .  basename($value['shipper_kyc_image']['file']); // to get file name
                                        $file_name = urldecode($file_name);
                                        $file_path = create_year_month_dir('shipper_kyc_document') . '/' . $file_name;
                                        file_put_contents($file_path, @file_get_contents($value['shipper_kyc_image']['file']));
                                        $doc_insert['doc_page1'] = $file_path;
                                    }

                                    if (isset($value['shipper_kyc_image1']['file'])) {
                                        $file_name = $docket_id . date('YmdHis') . '_'  . rand(1000, 9999) . '_' .  basename($value['shipper_kyc_image1']['file']); // to get file name
                                        $file_name = urldecode($file_name);
                                        $file_path = create_year_month_dir('shipper_kyc_document') . '/' . $file_name;
                                        file_put_contents($file_path, @file_get_contents($value['shipper_kyc_image1']['file']));
                                        $doc_insert['doc_page2'] = $file_path;
                                    }
                                    $this->gm->insert('document_mapping', $doc_insert);
                                }
                            }

                            if ($mode == 'insert') {
                                add_docket_insert_history($docket_id);
                            } else {

                                //LOG UPDATED DATA HISTORY
                                $docket_data_new = $this->gm->get_selected_record('docket', $old_column, $where = array('id' => $docket_id, 'status !=' => 3), array());
                                $diff_column = array_diff_assoc(array_intersect_key($docket_data_new, $docket_old_data), $docket_old_data);
                                if (isset($diff_column) && is_array($diff_column) && count($diff_column) > 0) {
                                    foreach ($diff_column as $dkey => $dvalue) {
                                        if (isset($docket_old_data[$dkey])) {
                                            $old_data[$dkey] = $docket_old_data[$dkey];
                                        }
                                        $new_data[$dkey] = $dvalue;
                                    }
                                }

                                $extra_insert_new = $this->gm->get_selected_record('docket_extra_field', $docket_extra_column, array('docket_id' => $docket_id, 'status' => 1), array());
                                if (isset($extra_insert_new) && is_array($extra_insert_new) && count($extra_insert_new) > 0) {
                                } else {
                                    $extra_insert_new = array();
                                }
                                $extra_diff_column = array_diff_assoc(array_intersect_key($extra_insert_new, $extra_old_data), $extra_old_data);
                                if (isset($extra_diff_column) && is_array($extra_diff_column) && count($extra_diff_column) > 0) {
                                    foreach ($extra_diff_column as $dkey => $dvalue) {
                                        if (isset($extra_old_data[$dkey])) {
                                            $old_data["de." . $dkey] = $extra_old_data[$dkey];
                                        }
                                        $new_data["de." . $dkey] = $dvalue;
                                    }
                                }


                                $shipper_insert_new = $this->gm->get_selected_record('docket_shipper', $shipper_consi_col, array('docket_id' => $docket_id, 'status' => 1), array());
                                $shipper_diff_column = array_diff_assoc(array_intersect_key($shipper_insert_new, $shipper_old_data), $shipper_old_data);
                                if (isset($shipper_diff_column) && is_array($shipper_diff_column) && count($shipper_diff_column) > 0) {
                                    foreach ($shipper_diff_column as $dkey => $dvalue) {
                                        if (isset($shipper_old_data[$dkey])) {
                                            $old_data["sh." . $dkey] = $shipper_old_data[$dkey];
                                        }
                                        $new_data["sh." . $dkey] = $dvalue;
                                    }
                                }

                                $consignee_insert_new = $this->gm->get_selected_record('docket_consignee', $shipper_consi_col, array('docket_id' => $docket_id, 'status' => 1), array());
                                $consignee_diff_column = array_diff_assoc(array_intersect_key($consignee_insert_new, $consignee_old_data), $consignee_old_data);
                                if (isset($consignee_diff_column) && is_array($consignee_diff_column) && count($consignee_diff_column) > 0) {
                                    foreach ($consignee_diff_column as $dkey => $dvalue) {
                                        if (isset($shipper_old_data[$dkey])) {
                                            $old_data["co." . $dkey] = $shipper_old_data[$dkey];
                                        }
                                        $new_data["co." . $dkey] = $dvalue;
                                    }
                                }

                                if ((isset($new_data) && is_array($new_data) && count($new_data) > 0)
                                    || isset($old_data) && is_array($old_data) && count($old_data) > 0
                                ) {
                                    $old_data['mode'] = 'update';
                                    $insert_data_history = array(
                                        'docket_id' => $docket_id,
                                        'new_data' => isset($new_data) ? json_encode($new_data) : '',
                                        'old_data' => isset($old_data) ? json_encode($old_data) : '',
                                        'created_date' => date('Y-m-d H:i:s'),
                                        'created_by' => $this->user_id,
                                        'created_by_type' => $this->user_type
                                    );

                                    $this->gm->insert('docket_history', $insert_data_history);
                                }
                            }




                            //UPDATE OFFSET
                            $qry = "SELECT  id,config_value FROM migration_log WHERE status IN(1,2) AND config_key='docket_migrate_last_id' ";
                            $qry_exe = $this->db->query($qry);
                            $configExist = $qry_exe->row_array();

                            if (isset($configExist) && is_array($configExist) && count($configExist) > 0) {
                                $updateq = "UPDATE migration_log SET config_value='" . $value['id'] . "' WHERE status IN(1,2) AND config_key='docket_migrate_last_id'";
                                $this->db->query($updateq);
                            } else {
                                $mig_insert_data = array(
                                    'config_key' => 'docket_migrate_last_id',
                                    'config_value' => $value['id']
                                );
                                $this->gm->insert('migration_log', $mig_insert_data);
                            }

                            echo "<br>AWB NO." . $value['tracking_no'] . " added";
                            echo "<br>DOCKET ID=" . $docket_id;
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


    public function get_weight_data($company_code = '')
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
            $url = $base_url . "api/v5/dockets/fetch_docket_weights";


            $qry = "SELECT id,config_value FROM migration_log WHERE status IN(1,2) AND config_key='docket_wt_migrate_last_id' ";
            $qry_exe = $this->db->query($qry);
            $offset_res = $qry_exe->row_array();

            $limit = 1000;
            if (isset($offset_res['config_value']) && $offset_res['config_value'] != '') {
                $last_id = $offset_res['config_value'];
            } else {
                $last_id = 0;
            }

            //GET DOCKET WHOSE LINE ITEM MISSING
            $qry = "SELECT id,migration_id FROM docket 
            WHERE migration_id > " . $last_id . "
            AND (id NOT IN(select docket_id FROM docket_items WHERE status=1))
            ORDER BY migration_id  LIMIT " . $limit . "";
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
                if (isset($result) && is_array($result) && count($result) > 0) {
                    if (isset($result['data']) && is_array($result['data']) && count($result['data']) > 0) {

                        $keys = array_column($result['data'], 'id');
                        array_multisort($keys, SORT_ASC, $result['data']);

                        foreach ($result['data'] as $key => $value) {
                            $qry = "SELECT id FROM docket WHERE migration_id =" . $value['id'];
                            $qry_exe = $this->db->query($qry);
                            $docket_id_data = $qry_exe->row_array();
                            if (isset($docket_id_data) && is_array($docket_id_data) && count($docket_id_data) > 0) {
                                $docket_update = array(
                                    'actual_wt' => $value['actual_weight'],
                                    'volumetric_wt' => $value['volume_weight'],
                                    'add_wt' => $value['weight'],
                                    'total_pcs' => $value['pcs'],
                                );
                                $this->gm->update('docket', $docket_update, '', array('migration_id' => $value['id']));

                                $docket_id = $docket_id_data['id'];

                                $qry = "SELECT id,docket_edit_field FROM docket_extra_field WHERE status=1 AND docket_id =" . $docket_id;
                                $qry_exe = $this->db->query($qry);
                                $extra_data = $qry_exe->row_array();
                                if (isset($extra_data) && is_array($extra_data) && count($extra_data) > 0) {
                                    $extra_update = array(
                                        'docket_edit_field' => $extra_data['docket_edit_field'] == '' ? 'edit_volume_wt' : $extra_data['docket_edit_field'] . ',edit_volume_wt',
                                    );
                                    $this->gm->update('docket_extra_field', $extra_update, '', array('docket_id' => $docket_id));
                                } else {
                                    $extra_update = array(
                                        'docket_edit_field' => 'edit_volume_wt',
                                        'created_date' => date('Y-m-d H:i:s'),
                                        'created_by' => $this->user_id
                                    );
                                    $this->gm->insert('docket_extra_field', $extra_update);
                                }

                                //ADD DOCKET ITEM
                                $item_insert = array(
                                    'docket_id' => $docket_id,
                                    'box_no' => 1,
                                    'actual_wt' => $value['actual_weight'],
                                    'volumetric_wt' => $value['volume_weight'],
                                    'box_count' => 1,
                                    'created_date' => date('Y-m-d H:i:s'),
                                    'created_by' => 99999999
                                );
                                $this->gm->insert('docket_items', $item_insert);
                            } else {
                                echo "<br>DOcket ID=" . $value['id'] . " NOT FOUND";
                            }

                            //UPDATE OFFSET
                            $qry = "SELECT  id,config_value FROM migration_log WHERE status IN(1,2) AND config_key='docket_wt_migrate_last_id' ";
                            $qry_exe = $this->db->query($qry);
                            $configExist = $qry_exe->row_array();

                            if (isset($configExist) && is_array($configExist) && count($configExist) > 0) {
                                $updateq = "UPDATE migration_log SET config_value='" . $value['id'] . "' WHERE status IN(1,2) AND config_key='docket_wt_migrate_last_id'";
                                $this->db->query($updateq);
                            } else {
                                $mig_insert_data = array(
                                    'config_key' => 'docket_wt_migrate_last_id',
                                    'config_value' => $value['id']
                                );
                                $this->gm->insert('migration_log', $mig_insert_data);
                            }

                            echo "<br>DOCKET ID=" . $docket_id;
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



    public function get_docket_history($company_code = '')
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
        //$base_url = 'http://139.59.60.106';
        if ($base_url != '') {
            $url = $base_url . "/api/v5/dockets/docket_history";


            $qry = "SELECT id,config_value FROM migration_log WHERE status IN(1,2) AND config_key='docket_history_last_id' ";
            $qry_exe = $this->db->query($qry);
            $offset_res = $qry_exe->row_array();

            $limit = 1000;
            if (isset($offset_res['config_value']) && $offset_res['config_value'] != '') {
                $last_id = $offset_res['config_value'];
            } else {
                $last_id = 0;
            }

            //GET DOCKET WHOSE LINE ITEM MISSING
            $qry = "SELECT id,migration_id FROM docket WHERE migration_id > " . $last_id . " ORDER BY migration_id  LIMIT " . $limit . "";
            $qry_exe = $this->db->query($qry);
            $id_res = $qry_exe->result_array();

            if (isset($id_res) && is_array($id_res) && count($id_res) > 0) {
                foreach ($id_res as $ikey => $ivalue) {
                    $id_arr[] = $ivalue['migration_id'];
                }
            }
            //$id_arr[] = 1;
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

                $history_dir =  create_year_dir('docket_history', 'history_log');

                if (isset($result['error']) && $result['error'] != '') {
                    echo $result['error'];
                    exit;
                } else {
                    if (isset($result) && is_array($result) && count($result) > 0) {
                        if (isset($result['data']) && is_array($result['data']) && count($result['data']) > 0) {

                            $keys = array_column($result['data'], 'id');
                            array_multisort($keys, SORT_ASC, $result['data']);

                            foreach ($result['data'] as $key => $value) {

                                $docket_id = $value['id'];

                                $filename = $docket_id . "_history.json";

                                $file_content = json_encode($value, JSON_PRETTY_PRINT);
                                file_put_contents(FCPATH . '/' . $history_dir . '/' . $filename, $file_content);
                                //UPDATE OFFSET
                                $qry = "SELECT  id,config_value FROM migration_log WHERE status IN(1,2) AND config_key='docket_history_last_id' ";
                                $qry_exe = $this->db->query($qry);
                                $configExist = $qry_exe->row_array();

                                if (isset($configExist) && is_array($configExist) && count($configExist) > 0) {
                                    $updateq = "UPDATE migration_log SET config_value='" . $value['id'] . "' WHERE status IN(1,2) AND config_key='docket_history_last_id'";
                                    $this->db->query($updateq);
                                } else {
                                    $mig_insert_data = array(
                                        'config_key' => 'docket_history_last_id',
                                        'config_value' => $value['id']
                                    );
                                    $this->gm->insert('migration_log', $mig_insert_data);
                                }

                                echo "<br>DOCKET ID=" . $docket_id;
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



    public function get_docket_payment_receipt($company_code = '')
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
        //$base_url = 'http://159.89.174.205/';
        if ($base_url != '') {
            $url = $base_url . "api/v5/dockets/fetch_dockets";


            $qry = "SELECT id,config_value FROM migration_log WHERE status IN(1,2) AND config_key='last_docket_payment_receipt_last_id' ";
            $qry_exe = $this->db->query($qry);
            $offset_res = $qry_exe->row_array();

            $limit = 500;
            if (isset($offset_res['config_value']) && $offset_res['config_value'] != '') {
                $last_id = $offset_res['config_value'];
            } else {
                $last_id = 0;
            }

            //GET DOCKET WHOSE LINE ITEM MISSING
            // $where = " AND booking_date>='2022-01-01' AND booking_date<='2022-07-28'";
            $where = " AND booking_date<'2022-01-01' ";
            $qry = "SELECT id,migration_id FROM docket WHERE migration_id > " . $last_id . " " . $where . " ORDER BY migration_id  LIMIT " . $limit . "";
            $qry_exe = $this->db->query($qry);
            $id_res = $qry_exe->result_array();

            if (isset($id_res) && is_array($id_res) && count($id_res) > 0) {
                foreach ($id_res as $ikey => $ivalue) {
                    $id_arr[] = $ivalue['migration_id'];
                }
            }


            // $id_arr[] = '26882';
            //$id_arr[] = 1;
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
                // echo '<pre>';
                // print_r($result);
                // exit;
                if (isset($result['error']) && $result['error'] != '') {
                    echo $result['error'];
                    exit;
                } else {
                    $all_account = get_all_bank_account(" AND c.status IN(1,2) ", "", "account_no");
                    if (isset($result) && is_array($result) && count($result) > 0) {
                        if (isset($result['data']) && is_array($result['data']) && count($result['data']) > 0) {

                            $keys = array_column($result['data'], 'id');
                            array_multisort($keys, SORT_ASC, $result['data']);

                            foreach ($result['data'] as $key => $value) {


                                $qry = "SELECT id,awb_no FROM docket WHERE status IN(1,2) AND migration_id='" . $value['id'] . "'";
                                $qry_exe = $this->db->query($qry);
                                $existData = $qry_exe->row_array();



                                if (isset($existData) && is_array($existData) && count($existData) > 0) {
                                    $docket_id = $existData['id'];
                                    $qry = "SELECT id,grand_total FROM docket_sales_billing WHERE status IN(1,2) AND docket_id='" . $docket_id . "'";
                                    $qry_exe = $this->db->query($qry);
                                    $sales_data = $qry_exe->row_array();

                                    $sales_total = isset($sales_data['grand_total']) ? $sales_data['grand_total'] : 0;
                                    $total_received = 0;
                                    $total_balance = 0;
                                    //ADD PAYMENT RECEIPT
                                    $payment_receipts_api = isset($value['payment_receipts']) ? $value['payment_receipts'] : array();
                                    $keys = array_column($payment_receipts_api, 'item_index');
                                    array_multisort($keys, SORT_ASC, $payment_receipts_api);

                                    $docket_receipt = array();
                                    if (isset($payment_receipts_api) && is_array($payment_receipts_api) && count($payment_receipts_api) > 0) {
                                        foreach ($payment_receipts_api as $re_key => $re_value) {

                                            $payment_type = 0;
                                            if ($re_value['mode_of_payment'] != '') {
                                                $docket_status_code = strtolower($re_value['mode_of_payment']);
                                                if ($docket_status_code == 'cash') {
                                                    $payment_type = 1;
                                                } else if ($docket_status_code == 'cheque') {
                                                    $payment_type = 2;
                                                } else if ($docket_status_code == 'online') {
                                                    $payment_type = 3;
                                                } else if ($docket_status_code == 'credit') {
                                                    $payment_type = 4;
                                                } else  if ($docket_status_code == 'foc') {
                                                    $payment_type = 5;
                                                } else if ($docket_status_code == 'cash-credit') {
                                                    $payment_type = 6;
                                                } else if ($docket_status_code == 'cod') {
                                                    $payment_type = 7;
                                                } else if ($docket_status_code == 'to_pay') {
                                                    $payment_type = 8;
                                                } else if ($docket_status_code == 'cod_topay') {
                                                    $payment_type = 9;
                                                }
                                            }

                                            $docket_receipt[] = array(
                                                'docket_id' => $docket_id,
                                                'created_date' => $re_value['created_at'],
                                                'modified_date' => $re_value['updated_at'],
                                                'receipt_date' => $re_value['payment_date'],
                                                'payment_type_id' => $payment_type,
                                                'receipt_amount' => $re_value['amount'],
                                                'particular' => $re_value['particulars'],
                                                'payment_no' => $re_value['payment_no'],
                                                'account_no_id' => isset($re_value['account_no']) && $re_value['account_no'] != '' && isset($all_account[strtolower(trim($re_value['account_no']))]) ? $all_account[strtolower(trim($re_value['account_no']))]['id'] : 0,
                                            );

                                            $total_received += $re_value['amount'];
                                        }
                                    }

                                    if (isset($docket_receipt) && is_array($docket_receipt) && count($docket_receipt) > 0) {
                                        $updateq = "UPDATE docket_receipt SET status =3,modified_date='3000-00-00 00:00:00' WHERE docket_id='" . $docket_id . "' AND created_by=0";
                                        $this->db->query($updateq);

                                        $this->db->insert_batch('docket_receipt', $docket_receipt);
                                    }

                                    $total_balance = $sales_total - $total_received;

                                    $docket_update = array(
                                        'total_received' => $total_received,
                                        'total_balance' => $total_balance,
                                    );
                                    $this->gm->update('docket', $docket_update, '', array('id' => $docket_id));
                                    echo "<br>RECEIPT ADDED DOCKET ID" . $docket_id;
                                } else {
                                    echo "<br>DOCKET " . $value['tracking_no'] . " NOT FOUND";
                                }


                                $qry = "SELECT  id,config_value FROM migration_log WHERE status IN(1,2) AND config_key='last_docket_payment_receipt_last_id' ";
                                $qry_exe = $this->db->query($qry);
                                $configExist = $qry_exe->row_array();

                                if (isset($configExist) && is_array($configExist) && count($configExist) > 0) {
                                    $updateq = "UPDATE migration_log SET config_value='" . $value['id'] . "' WHERE status IN(1,2) AND config_key='last_docket_payment_receipt_last_id'";
                                    $this->db->query($updateq);
                                } else {
                                    $mig_insert_data = array(
                                        'config_key' => 'last_docket_payment_receipt_last_id',
                                        'config_value' => $value['id']
                                    );
                                    $this->gm->insert('migration_log', $mig_insert_data);
                                }

                                echo "<br>DOCKET ID=" . $docket_id;
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


    public function update_line_item_data($company_code = '')
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

            $setting = get_all_app_setting(" AND module_name IN('master','docket','pdf')");
            $parcel_tracking = 1;
            if (isset($setting['docket_show_box_no']) && $setting['docket_show_box_no'] == 1) {
                if (!isset($setting['docket_display_parcel_tracking']) || $setting['docket_display_parcel_tracking'] == 2) {
                    $parcel_tracking = 2;
                }
            }

            if ($parcel_tracking == 1) {
                echo "Parcel Tracking is ON";
                exit;
            } else {
                $qry = "SELECT id,config_value FROM migration_log WHERE status IN(1,2) AND config_key='docket_item_update_last_id' ";
                $qry_exe = $this->db->query($qry);
                $offset_res = $qry_exe->row_array();

                $limit = 10000;
                if (isset($offset_res['config_value']) && $offset_res['config_value'] != '') {
                    $last_id = $offset_res['config_value'];
                } else {
                    $last_id = 0;
                }

                //GET DOCKET WHOSE LINE ITEM MISSING
                $qry = "SELECT id,migration_id FROM docket 
            WHERE migration_id > " . $last_id . " ORDER BY migration_id LIMIT " . $limit . "";
                $qry_exe = $this->db->query($qry);
                $id_res = $qry_exe->result_array();

                if (isset($id_res) && is_array($id_res) && count($id_res) > 0) {

                    foreach ($id_res as $key => $value) {
                        $docket_id = $value['id'];
                        $qry = "SELECT * FROM docket_items WHERE status=1 AND docket_id =" . $value['id'];
                        $qry_exe = $this->db->query($qry);
                        $docket_id_data = $qry_exe->result_array();

                        if (isset($docket_id_data) && is_array($docket_id_data) && count($docket_id_data) > 0) {
                            foreach ($docket_id_data as $ikey => $ivalue) {
                                $update_data = array(
                                    'box_no' => $ikey + 1,
                                    'no_of_box' => $ivalue['box_count'],
                                    'sr_no' => $ikey + 1,
                                );
                                $this->gm->update('docket_items', $update_data, '', array('id' => $ivalue['id']));
                            }
                        }

                        //UPDATE OFFSET
                        $qry = "SELECT  id,config_value FROM migration_log WHERE status IN(1,2) AND config_key='docket_item_update_last_id' ";
                        $qry_exe = $this->db->query($qry);
                        $configExist = $qry_exe->row_array();

                        if (isset($configExist) && is_array($configExist) && count($configExist) > 0) {
                            $updateq = "UPDATE migration_log SET config_value='" . $value['migration_id'] . "' WHERE status IN(1,2) AND config_key='docket_item_update_last_id'";
                            $this->db->query($updateq);
                        } else {
                            $mig_insert_data = array(
                                'config_key' => 'docket_item_update_last_id',
                                'config_value' => $value['migration_id']
                            );
                            $this->gm->insert('migration_log', $mig_insert_data);
                        }

                        echo "<br>DOCKET ID=" . $docket_id;
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



    public function get_kyc_data($company_code = '')
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
        // $base_url = 'http://139.59.12.87/';

        if ($base_url != '') {

            $url = $base_url . "api/v5/dockets/fetch_dockets";

            $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
            $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

            $qry = "SELECT  id,config_value FROM migration_log WHERE status IN(1,2) AND config_key='docket_kyc_migrate_last_id' ";
            $qry_exe = $this->db->query($qry);
            $offset_res = $qry_exe->row_array();

            $limit = 100;
            if (isset($offset_res['config_value']) && $offset_res['config_value'] != '') {
                $last_id = $offset_res['config_value'];
            } else {
                $last_id = 0;
            }

            if ($start_date != '' && $end_date != '') {
                $qry = "SELECT id FROM docket WHERE migration_id = " . $last_id . " 
                AND booking_date >= '" . $start_date . "' AND booking_date <= '" . $end_date . "'";
                $qry_exe = $this->db->query($qry);
                $rangeExist = $qry_exe->row_array();
                if (isset($rangeExist) && is_array($rangeExist) && count($rangeExist) > 0) {
                } else {
                    $last_id = 0;
                }
            }

            $appnedq = "";
            if ($start_date != '' && $end_date != '') {
                $appnedq = " AND booking_date >= '" . $start_date . "' AND booking_date <= '" . $end_date . "'";
            }


            $qry = "SELECT migration_id FROM docket WHERE migration_id > " . $last_id . $appnedq . " ORDER BY migration_id  LIMIT " . $limit . "";
            // $qry = "SELECT migration_id FROM `docket` WHERE `status` = 1 AND `awb_no` = '' AND `migration_id` != 0";
            $qry_exe = $this->db->query($qry);
            $id_res = $qry_exe->result_array();

            if (isset($id_res) && is_array($id_res) && count($id_res) > 0) {
                foreach ($id_res as $ikey => $ivalue) {
                    $id_arr[] = $ivalue['migration_id'];
                }
            }
            //$id_arr[] = 155113;

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

                $all_gst_type = get_all_doc_type(" AND show_in_docket_shipper=1", "docket_doc_name", "name");


                if (isset($result) && is_array($result) && count($result) > 0) {
                    if (isset($result['data']) && is_array($result['data']) && count($result['data']) > 0) {

                        $keys = array_column($result['data'], 'id');
                        array_multisort($keys, SORT_ASC, $result['data']);

                        foreach ($result['data'] as $key => $value) {

                            $qry = "SELECT id,awb_no FROM docket WHERE status IN(1,2) AND migration_id='" . $value['id'] . "'";
                            $qry_exe = $this->db->query($qry);
                            $existData = $qry_exe->row_array();

                            if (isset($existData) && is_array($existData) && count($existData) > 0) {
                                $docket_id = $existData['id'];

                                if (isset($value['shipper_kyc_image']['file']) || isset($value['shipper_kyc_image']['file'])) {
                                    //ADD SHIPPER KYC - WHOSE SHIPPER MASTER NOT ADDED
                                    $shipper_data = $this->gm->get_selected_record('docket_shipper', 'id,shipper_id', $where = array('docket_id' => $docket_id, 'status' => 1), array());
                                    if (isset($shipper_data) && is_array($shipper_data) && count($shipper_data) > 0) {
                                        if ($shipper_data['shipper_id'] == 0) {
                                            $shipper_id = $shipper_data['id'];
                                            $shipper_type = 5;

                                            $doc_type_id =  isset($value['shipper_gstin_type']) && $value['shipper_gstin_type'] != '' && isset($all_gst_type[strtolower(trim($value['shipper_gstin_type']))]) ? $all_gst_type[strtolower(trim($value['shipper_gstin_type']))]['id'] : 4;
                                            $doc_insert =  array(
                                                'module_id' => $shipper_id,
                                                'module_type' => $shipper_type,
                                                'doc_type_id' => $doc_type_id,
                                                'doc_no' => '',
                                                'doc_name' => ''
                                            );

                                            if (isset($value['shipper_kyc_image']['file'])) {
                                                $file_name = $docket_id . date('YmdHis') . '_'  . rand(1000, 9999) . '_' .  basename($value['shipper_kyc_image']['file']); // to get file name
                                                $file_name = urldecode($file_name);
                                                $file_path = create_year_month_dir('shipper_kyc_document') . '/' . $file_name;
                                                file_put_contents($file_path, @file_get_contents($value['shipper_kyc_image']['file']));
                                                $doc_insert['doc_page1'] = $file_path;
                                            }

                                            if (isset($value['shipper_kyc_image1']['file'])) {
                                                $file_name = $docket_id . date('YmdHis') . '_'  . rand(1000, 9999) . '_' .  basename($value['shipper_kyc_image1']['file']); // to get file name
                                                $file_name = urldecode($file_name);
                                                $file_path = create_year_month_dir('shipper_kyc_document') . '/' . $file_name;
                                                file_put_contents($file_path, @file_get_contents($value['shipper_kyc_image1']['file']));
                                                $doc_insert['doc_page2'] = $file_path;
                                            }

                                            //CHECK KYC EXIST
                                            $qry = "SELECT * FROM document_mapping WHERE status=1 
                                            AND module_id='" . $shipper_id . "' AND module_type='" . $shipper_type . "'
                                            AND doc_type_id=" . $doc_type_id . " AND doc_page1!=''";

                                            $qry_exe = $this->db->query($qry);
                                            $kycExist = $qry_exe->row_array();
                                            if (isset($kycExist) && is_array($kycExist) && count($kycExist) > 0) {
                                                echo "<br>KYC EXIST FOR DOCKET ID=" . $docket_id;
                                            } else {
                                                $this->gm->insert('document_mapping', $doc_insert);
                                            }
                                        }
                                    }
                                }
                            }

                            //UPDATE OFFSET
                            $qry = "SELECT  id,config_value FROM migration_log WHERE status IN(1,2) AND config_key='docket_kyc_migrate_last_id' ";
                            $qry_exe = $this->db->query($qry);
                            $configExist = $qry_exe->row_array();

                            if (isset($configExist) && is_array($configExist) && count($configExist) > 0) {
                                $updateq = "UPDATE migration_log SET config_value='" . $value['id'] . "' WHERE status IN(1,2) AND config_key='docket_kyc_migrate_last_id'";
                                $this->db->query($updateq);
                            } else {
                                $mig_insert_data = array(
                                    'config_key' => 'docket_kyc_migrate_last_id',
                                    'config_value' => $value['id']
                                );
                                $this->gm->insert('migration_log', $mig_insert_data);
                            }

                            echo "<br>AWB NO." . $value['tracking_no'] . " added";
                            echo "<br>KYC ADDED DOCKET ID=" . $docket_id;
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



    public function get_shipper_data($company_code = '')
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
        // $base_url = 'http://139.59.12.87/';

        if ($base_url != '') {

            $url = $base_url . "api/v5/dockets/fetch_dockets";

            $qry = "SELECT  id,config_value FROM migration_log WHERE status IN(1,2) AND config_key='docket_shipper_migrate_last_id' ";
            $qry_exe = $this->db->query($qry);
            $offset_res = $qry_exe->row_array();

            $limit = 100;
            if (isset($offset_res['config_value']) && $offset_res['config_value'] != '') {
                $last_id = $offset_res['config_value'];
            } else {
                $last_id = 0;
            }


            $appnedq = "";


            $qry = "SELECT d.migration_id FROM docket_shipper sh 
        JOIN docket d ON(d.id=sh.docket_id) WHERE d.status=1 AND sh.status=1 AND d.migration_id>0 
        AND sh.shipper_id=0 AND sh.code='' AND d.booking_date>='2022-10-01' 
        AND d.booking_date<='2022-11-31' AND migration_id='168725' AND migration_id > " . $last_id . $appnedq . " ORDER BY migration_id  LIMIT " . $limit . "";
            $qry_exe = $this->db->query($qry);
            $id_res = $qry_exe->result_array();

            if (isset($id_res) && is_array($id_res) && count($id_res) > 0) {
                foreach ($id_res as $ikey => $ivalue) {
                    $id_arr[] = $ivalue['migration_id'];
                }
            }
            //$id_arr[] = 155113;

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

                $all_gst_type = get_all_doc_type(" AND show_in_docket_shipper=1", "docket_doc_name", "name");
                $all_shipper = get_all_shipper(" AND status IN(1,2) ", "code");

                if (isset($result) && is_array($result) && count($result) > 0) {
                    if (isset($result['data']) && is_array($result['data']) && count($result['data']) > 0) {

                        $keys = array_column($result['data'], 'id');
                        array_multisort($keys, SORT_ASC, $result['data']);

                        foreach ($result['data'] as $key => $value) {

                            $qry = "SELECT id,awb_no FROM docket WHERE status IN(1,2) AND migration_id='" . $value['id'] . "'";
                            $qry_exe = $this->db->query($qry);
                            $existData = $qry_exe->row_array();

                            if (isset($existData) && is_array($existData) && count($existData) > 0) {
                                $docket_id = $existData['id'];
                                $shipper_id =  isset($value['shipper_code']) && $value['shipper_code'] != '' && isset($all_shipper[strtolower(trim($value['shipper_code']))]) ? $all_shipper[strtolower(trim($value['shipper_code']))]['id'] : 0;
                                if ($shipper_id > 0) {
                                    $update_q = "UPDATE docket_shipper SET shipper_id='" . $shipper_id . "',
                                    code='" . $value['shipper_code'] . "' WHERE docket_id='" . $existData['id'] . "'";

                                    $this->db->query($update_q);
                                } else {
                                    echo "<br>SHIPPER NOT FOUND" . $value['id'];
                                }
                            }

                            //UPDATE OFFSET
                            $qry = "SELECT  id,config_value FROM migration_log WHERE status IN(1,2) AND config_key='docket_shipper_migrate_last_id' ";
                            $qry_exe = $this->db->query($qry);
                            $configExist = $qry_exe->row_array();

                            if (isset($configExist) && is_array($configExist) && count($configExist) > 0) {
                                $updateq = "UPDATE migration_log SET config_value='" . $value['id'] . "' WHERE status IN(1,2) AND config_key='docket_shipper_migrate_last_id'";
                                $this->db->query($updateq);
                            } else {
                                $mig_insert_data = array(
                                    'config_key' => 'docket_shipper_migrate_last_id',
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
