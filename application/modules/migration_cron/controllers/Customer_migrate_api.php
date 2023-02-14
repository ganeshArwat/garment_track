<?php
class Customer_migrate_api extends MX_Controller
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
            $url = $base_url . "api/v5/customer_masters/get_customer_ids";

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
                    $qry = "SELECT id FROM customer WHERE status IN(1,2) AND migration_id='" . $rvalue . "'";
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
                    $this->db->insert_batch('customer', $insert_data);
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
        //$base_url = "http://157.245.111.2/";
        if ($base_url != '') {
            // $api_url = $base_url . "api/v5/customer_masters";
            // $json_data = @file_get_contents($api_url);
            // $result = json_decode($json_data, TRUE);

            $url = $base_url . "api/v5/customer_masters/fetch_customers";

            $qry = "SELECT  id,config_value FROM migration_log WHERE status IN(1,2) AND config_key='customer_migrate_last_id' ";
            $qry_exe = $this->db->query($qry);
            $offset_res = $qry_exe->row_array();

            $limit = 100;
            if (isset($offset_res['config_value']) && $offset_res['config_value'] != '') {
                $last_id = $offset_res['config_value'];
            } else {
                $last_id = 0;
            }
            $qry = "SELECT migration_id FROM customer WHERE migration_id > " . $last_id . " ORDER BY migration_id  LIMIT " . $limit . "";
            $qry_exe = $this->db->query($qry);
            $id_res = $qry_exe->result_array();

            if (isset($id_res) && is_array($id_res) && count($id_res) > 0) {
                foreach ($id_res as $ikey => $ivalue) {
                    $id_arr[] = $ivalue['migration_id'];
                }
            }
            // $id_arr[] = 270;

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
            }
        }

        // echo '<pre>';
        // print_r($result);
        // exit;

        if (isset($result) && is_array($result) && count($result) > 0) {

            $all_location = get_all_location(" AND status IN(1,2) ", "code");
            $all_hub = get_all_hub(" AND status IN(1,2) ", "code");
            // $all_state = get_all_state(" AND status IN(1,2) ", "code");
            //$all_country = get_all_country(" AND status IN(1,2) ", "code");
            // $all_city = get_all_city(" AND status IN(1,2) ", "code");
            $all_currency = get_all_currency(" AND status IN(1,2) ", "code");
            $all_address_book = get_all_address_book(" AND status IN(1,2) ", "code");
            $all_user = get_all_user(" AND company_id='" . $company_id . "'", "", "migration_id");

            $this->load->module('customer_masters');
            $mail_cycle = array(
                'manually' => 1,
                'daily' => 2,
                'weekly' => 3,
                'monthly' => 4,
            );
            $mail_time = array(
                '8_AM' => "08:00:00",
                '11_AM' => "11:00:00",
                '2_PM' => "14:00:00",
                '4_PM' => "16:00:00",
                '7_PM' => "19:00:00",
            );
            if (isset($result['data']) && is_array($result['data']) && count($result['data']) > 0) {

                $keys = array_column($result['data'], 'id');
                array_multisort($keys, SORT_ASC, $result['data']);

                foreach ($result['data'] as $key => $value) {

                    $customer_insert = array();
                    $origin_id = isset($value['location_code']) && isset($all_location[strtolower(trim($value['location_code']))]) ? $all_location[strtolower(trim($value['location_code']))]['id'] : 0;
                    $hub_id = isset($value['origin_hub_code']) && isset($all_hub[strtolower(trim($value['origin_hub_code']))]) ? $all_hub[strtolower(trim($value['origin_hub_code']))]['id'] : 0;
                    // $state_id = isset($value['state']) && isset($all_state[strtolower(trim($value['state']))]) ? $all_state[strtolower(trim($value['state']))]['id'] : 0;
                    // $country_id = isset($value['customer_country']) && isset($all_country[strtolower(trim($value['customer_country']))]) ? $all_country[strtolower(trim($value['customer_country']))]['id'] : 0;
                    // $city_id = isset($value['city']) && isset($all_city[strtolower(trim($value['city']))]) ? $all_city[strtolower(trim($value['city']))]['id'] : 0;


                    $payment_type = 0;
                    if (isset($value['type_of_payment']) && $value['type_of_payment'] == 'cash') {
                        $payment_type = 1;
                    } elseif (isset($value['type_of_payment']) && $value['type_of_payment'] == 'cheque') {
                        $payment_type = 2;
                    } elseif (isset($value['type_of_payment']) && $value['type_of_payment'] == 'online') {
                        $payment_type = 3;
                    } elseif (isset($value['type_of_payment']) && $value['type_of_payment'] == 'credit') {
                        $payment_type = 4;
                    } elseif (isset($value['type_of_payment']) && $value['type_of_payment'] == 'foc') {
                        $payment_type = 5;
                    } elseif (isset($value['type_of_payment']) && $value['type_of_payment'] == 'cash-credit') {
                        $payment_type = 6;
                    } elseif (isset($value['type_of_payment']) && $value['type_of_payment'] == 'cod') {
                        $payment_type = 7;
                    } elseif (isset($value['type_of_payment']) && $value['type_of_payment'] == 'to_pay') {
                        $payment_type = 8;
                    } elseif (isset($value['type_of_payment']) && $value['type_of_payment'] == 'cod_topay') {
                        $payment_type = 9;
                    }

                    $customer_type = 0;
                    if (isset($value['type_of_customer']) && $value['type_of_customer'] == 'direct') {
                        $customer_type = 1;
                    } elseif (isset($value['type_of_customer']) && $value['type_of_customer'] == 'cocourier') {
                        $customer_type = 2;
                    } elseif (isset($value['type_of_customer']) && $value['type_of_customer'] == 'outstation') {
                        $customer_type = 3;
                    } elseif (isset($value['type_of_customer']) && $value['type_of_customer'] == 'other') {
                        $customer_type = 4;
                    }

                    $cust_category = 0;
                    if (isset($value['customer_category']) && $value['customer_category'] == 'individual') {
                        $cust_category = 1;
                    } elseif (isset($value['customer_category']) && $value['customer_category'] == 'sole_proprietorship') {
                        $cust_category = 2;
                    } elseif (isset($value['customer_category']) && $value['customer_category'] == 'partnership_llp') {
                        $cust_category = 3;
                    } elseif (isset($value['customer_category']) && $value['customer_category'] == 'company') {
                        $cust_category = 4;
                    }

                    $billing_cycle = 0;
                    if (isset($value['billing_cycle']) && $value['billing_cycle'] == 'manually') {
                        $billing_cycle = 1;
                    } elseif (isset($value['billing_cycle']) && $value['billing_cycle'] == 'daily') {
                        $billing_cycle = 2;
                    } elseif (isset($value['billing_cycle']) && $value['billing_cycle'] == 'weekly') {
                        $billing_cycle = 3;
                    } elseif (isset($value['billing_cycle']) && $value['billing_cycle'] == 'monthly') {
                        $billing_cycle = 4;
                    } elseif (isset($value['billing_cycle']) && $value['billing_cycle'] == 'fortnight') {
                        $billing_cycle = 5;
                    }

                    if ($value['bill_at_in_cycle'] == 1) {
                        $customer_insert['daily_billing_day'] = 1;
                    }



                    $customer_insert['customer'] = array(
                        'migration_id' => $value['id'],
                        'name' =>  $value['name'],
                        'code' =>  $value['code'],
                        'address' =>  $value['address'],
                        'email_id' =>  $value['email'],
                        'billing_email_id' =>  $value['email'],
                        'operation_email_id' =>  $value['email'],
                        'account_code' =>  $value['account_code'],
                        'created_date' =>  $value['created_at'],
                        'modified_date' =>  $value['updated_at'],
                        'status' =>  $value['is_active'] == 1 ? 1 : 2,
                        'gst_number' =>  $value['gst_number'],
                        'origin_id' =>  $origin_id,
                        'credit_limit' =>  $value['credit_limit'],
                        'adc_noc' =>  $value['adc_noc'],
                        'is_gst_apply' =>  isset($value['is_gst_applicable']) && $value['is_gst_applicable'] == 1 ? 1 : 2,
                        'payment_type' =>  $payment_type,
                        'send_booking_daily' =>  isset($value['send_booking_email']) && $value['send_booking_email'] == 1 ? 1 : 2,
                        'send_wt_change_docket' =>  isset($value['send_wt_change_email']) && $value['send_wt_change_email'] == 1 ? 1 : 2,
                        'origin_hub_id' =>  $hub_id,
                        'customer_type' =>  $customer_type,
                        'multiple_awb_generate' =>  isset($value['enable_multiple_awb_generation']) && $value['enable_multiple_awb_generation'] == 1 ? 1 : 2,
                        'pincode' =>  $value['pincode'],
                        'state' =>  $value['state'],
                        'country' => $value['customer_country'],
                        'contact_no' =>  $value['contact_no'],
                        'contact_no2' =>  $value['phone_no_2'],
                        'contact_person' =>  $value['contact_person'],
                        'company_id' =>  $value['company_id'],
                        'send_invoice_email' =>  isset($value['send_email_invoice']) && $value['send_email_invoice'] == 1 ? 1 : 2,
                        'send_pod_email' =>  isset($value['send_email_pod']) && $value['send_email_pod'] == 1 ? 1 : 2,
                        'aging_limit' =>  $value['aging_limit'],
                        'is_awb_auto_generate' =>  isset($value['is_awb_no_auto_generate']) && $value['is_awb_no_auto_generate'] == 1 ? 1 : 2,
                        'awb_no_prefix' =>  $value['customer_awb_no_range'],
                        'city' => $value['city'],
                        'cust_category' => $cust_category,
                        'customer_send_auto_mis_email' => isset($value['send_docket_pdf_to_consignee']) && $value['send_docket_pdf_to_consignee'] == 1 ? 1 : 2,
                        'customer_auto_mis_project_wise' => isset($value['send_auto_mis_email_project_wise']) && $value['send_auto_mis_email_project_wise'] == 1 ? 1 : 2,
                        'send_outstanding_email' => isset($value['send_total_outstanding_email']) && $value['send_total_outstanding_email'] == 1 ? 1 : 2,
                        'outstand_sms_cycle' => isset($value['outstanding_sms_cycle']) && $value['outstanding_sms_cycle'] != '' && isset($mail_cycle[$value['outstanding_sms_cycle']]) ? $mail_cycle[$value['outstanding_sms_cycle']] : 0,
                        'outstand_sms_day' => isset($value['outstanding_sms_at_in_cycle']) && $value['outstanding_sms_at_in_cycle'] == 0 ? 7 : $value['outstanding_sms_at_in_cycle'],
                        // 'salesman_id' =>  $value['salesman_id'],
                        'salesman_id' => isset($all_user[$value['salesman_id']]) ? $all_user[$value['salesman_id']]['id'] : 0,
                        'cs_person_id' => isset($all_user[$value['cs_person_id']]) ? $all_user[$value['cs_person_id']]['id'] : 0,
                        // 'cs_person_id' =>  $value['cs_person_id'],
                        'custom_invoice_id' => $value['custom_invoice_master_id'],
                        'billing_cycle' => $billing_cycle,
                        'api_address_line_1' =>  $value['address_line_1'],
                        'api_address_line_2' =>  $value['address_line_2'],
                        'api_address_line_3' =>  $value['address_line_3'],
                        'account_number' =>  $value['account_number'],
                        'country_code' =>  $value['country_code'],
                        'state_province_code' =>  $value['state_province_code'],
                        'api_name' =>  $value['api_name'],
                        'api_company_name' =>  $value['api_company_name'],
                        'api_phone_number' =>  $value['api_phone_number'],
                        'api_email_address' =>  $value['api_email_address'],
                        'api_city' =>  $value['api_city'],
                        'api_postal_code' =>  $value['api_postal_code'],
                        'custom_billing_customer_name' =>  $value['custom_billing_customer_name'],
                        'custom_billing_customer_address' =>  $value['custom_billing_address'],
                        'custom_billing_customer_number' =>  $value['custom_billing_contact_no'],
                        'custom_billing_customer_gst_no' =>  $value['custom_billing_gst_no'],
                        'custom_billing_contact_person' =>  $value['custom_billing_contact_person'],
                        'currency_id' => isset($value['currency']) && $value['currency'] != '' && isset($all_currency[strtolower(trim($value['currency']))]) ? $all_currency[strtolower(trim($value['currency']))]['id'] : 0,
                        'address_book_id' => isset($value['mailer_head']) && $value['mailer_head'] != '' && isset($all_address_book[strtolower(trim($value['mailer_head']))]) ? $all_address_book[strtolower(trim($value['mailer_head']))]['id'] : 0,
                        'created_by' => isset($all_user[$value['created_by_id']]) ? $all_user[$value['created_by_id']]['id'] : 0,
                        'modified_by' => isset($all_user[$value['updated_by_id']]) ? $all_user[$value['updated_by_id']]['id'] : 0,
                    );
                    $customer_insert['mis_email'] = array(
                        'email_once_shipment_deliver' => isset($value['send_email_only_once_if_shipment_delivered']) && $value['send_email_only_once_if_shipment_delivered'] == 1 ? 1 : 2,
                        'email_cycle' => isset($value['custom_email_cycle']) && $value['custom_email_cycle'] != '' && isset($mail_cycle[$value['custom_email_cycle']]) ? $mail_cycle[$value['custom_email_cycle']] : 0,
                        'email_day' => isset($value['custom_email_at_in_cycle']) && $value['custom_email_at_in_cycle'] == 0 ? 7 : $value['custom_email_at_in_cycle'],
                        'shipment_range' => isset($value['custom_email_shipment_range']) && $value['custom_email_shipment_range'] == 'last_two_month' ? 2 : 1,
                        'dont_send_last_month_deliver' => isset($value['dont_send_email_shipments_are_delivered_for_the_last_month']) && $value['dont_send_email_shipments_are_delivered_for_the_last_month'] == 1 ? 1 : 2,
                        'email_time1' => isset($value['custom_email_time']) && $value['custom_email_time'] != '' && isset($mail_time[$value['custom_email_time']]) ? $mail_time[$value['custom_email_time']] : 0,
                    );

                    $customer_insert['outstand_email'] = array(
                        'send_negative_amt' => isset($value['send_outstanding_email_if_negative_amount']) && $value['send_outstanding_email_if_negative_amount'] == 1 ? 1 : 2,
                        'send_positive_amt' => isset($value['send_outstanding_email_if_positive_amount']) && $value['send_outstanding_email_if_positive_amount'] == 1 ? 1 : 2,
                        'send_zero_amt' => isset($value['send_outstanding_email_if_zero_amount']) && $value['send_outstanding_email_if_zero_amount'] == 1 ? 1 : 2,
                        'shipment_range' => isset($value['ledger_date_range']) && $value['ledger_date_range'] == 'last_two_month' ? 2 : 1,
                        'email_cycle' => isset($value['outstanding_email_cycle']) && $value['outstanding_email_cycle'] != '' && isset($mail_cycle[$value['outstanding_email_cycle']]) ? $mail_cycle[$value['outstanding_email_cycle']] : 0,
                        'email_day' => isset($value['outstanding_email_at_in_cycle']) && $value['outstanding_email_at_in_cycle'] == 0 ? 7 : $value['outstanding_email_at_in_cycle'],
                    );

                    $customer_insert['customer_setting'] = array(
                        'create_auto_invoice' => isset($value['create_auto_invoice']) && $value['create_auto_invoice'] == 1 ? 1 : 2,
                        'dont_show_it_in_unbilled_or_outstanding' => isset($value['dont_show_it_in_unbilled_or_outstanding']) && $value['dont_show_it_in_unbilled_or_outstanding'] == 1 ? 1 : 2,
                        'customized_booking_whatsapp_to_shipper' => isset($value['send_customized_booking_whatsapp_message_to_the_shipper']) && $value['send_customized_booking_whatsapp_message_to_the_shipper'] == 1 ? 1 : 2,
                        'customized_booking_whatsapp_to_consignee' => isset($value['send_customized_booking_whatsapp_message_to_the_consignee']) && $value['send_customized_booking_whatsapp_message_to_the_consignee'] == 1 ? 1 : 2,
                        'dont_allow_po_number_in_docket_entry_page' => isset($value['dont_allow_po_number_blank_in_docket_entry_page']) && $value['dont_allow_po_number_blank_in_docket_entry_page'] == 1 ? 1 : 2,
                        'display_custom_billing_details_in_invoice' => isset($value['display_custom_billing_details_in_invoice']) && $value['display_custom_billing_details_in_invoice'] == 1 ? 1 : 2,
                        'is_fsc_apply' => isset($value['is_fuel_surcharge']) && $value['is_fuel_surcharge'] == 1 ? 1 : 2,
                        'show_amount_in_docket_pdf1_attachment' => isset($value['show_amount_in_dockets_email_pdf1']) && $value['show_amount_in_dockets_email_pdf1'] == 1 ? 1 : 2,
                        'invoice_generation_sms_to_customer' => isset($value['send_invoice_generation_sms_to_customer']) && $value['send_invoice_generation_sms_to_customer'] == 1 ? 1 : 2,
                        'show_reference_no_in_booking_email' => isset($value['send_reference_no_in_booking_email']) && $value['send_reference_no_in_booking_email'] == 1 ? 1 : 2,
                        'extra_charge_email' => isset($value['send_email_for_extra_charges']) && $value['send_email_for_extra_charges'] == 1 ? 1 : 2,
                        'add_docket_credit_breached' => isset($value['add_docket_after_credit_limit_breached']) && $value['add_docket_after_credit_limit_breached'] == 1 ? 1 : 2,
                        'shipper_booking_sms' => isset($value['send_booking_sms_to_shipper']) && $value['send_booking_sms_to_shipper'] == 1 ? 1 : 2,
                        'consignee_booking_sms' => isset($value['send_booking_sms_to_consignee']) && $value['send_booking_sms_to_consignee'] == 1 ? 1 : 2,
                        'shipper_delivery_sms' => isset($value['send_delivery_sms_to_shipper']) && $value['send_delivery_sms_to_shipper'] == 1 ? 1 : 2,
                        'consignee_delivery_sms' => isset($value['send_delivery_sms_to_consignee']) && $value['send_delivery_sms_to_consignee'] == 1 ? 1 : 2,
                        'customer_booking_sms' =>  isset($value['send_booking_sms_to_customer']) && $value['send_booking_sms_to_customer'] == 1 ? 1 : 2,
                        'customer_delivery_sms' =>  isset($value['send_delivery_sms_to_customer']) && $value['send_delivery_sms_to_customer'] == 1 ? 1 : 2,
                        'customer_docket_pdf_email' => isset($value['send_shippers_copy_on_email']) && $value['send_shippers_copy_on_email'] == 1 ? 1 : 2,
                        'shipper_docket_pdf_email' => isset($value['send_docket_pdf_to_shipper']) && $value['send_docket_pdf_to_shipper'] == 1 ? 1 : 2,
                        'consignee_docket_pdf_email' => isset($value['send_custom_report_auto_email']) && $value['send_custom_report_auto_email'] == 1 ? 1 : 2,
                        'shipper_pickup_sms' => isset($value['send_pick_up_request_sms_to_pick_up']) && $value['send_pick_up_request_sms_to_pick_up'] == 1 ? 1 : 2,
                        'customer_pickup_sms' => isset($value['send_pick_up_request_sms_to_customer']) && $value['send_pick_up_request_sms_to_customer'] == 1 ? 1 : 2,
                        'consignee_pickup_sms' => isset($value['send_pick_up_request_sms_to_consignee']) && $value['send_pick_up_request_sms_to_consignee'] == 1 ? 1 : 2,
                        'shipper_pickup_email' => isset($value['send_pick_up_request_email_to_pick_up']) && $value['send_pick_up_request_email_to_pick_up'] == 1 ? 1 : 2,
                        'customer_pickup_email' => isset($value['send_pick_up_request_email_to_customer']) && $value['send_pick_up_request_email_to_customer'] == 1 ? 1 : 2,
                        'consignee_pickup_email' => isset($value['send_pick_up_request_email_to_consignee']) && $value['send_pick_up_request_email_to_consignee'] == 1 ? 1 : 2,
                        'shipper_pickup_sheet_sms' => isset($value['send_pick_up_sheet_sms_to_pick_up']) && $value['send_pick_up_sheet_sms_to_pick_up'] == 1 ? 1 : 2,
                        'customer_pickup_sheet_sms' => isset($value['send_pick_up_sheet_sms_to_customer']) && $value['send_pick_up_sheet_sms_to_customer'] == 1 ? 1 : 2,
                        'consignee_pickup_sheet_sms' => isset($value['send_pick_up_sheet_sms_to_consignee']) && $value['send_pick_up_sheet_sms_to_consignee'] == 1 ? 1 : 2,
                        'shipper_pickup_sheet_email' => isset($value['send_pick_up_sheet_email_to_pick_up']) && $value['send_pick_up_sheet_email_to_pick_up'] == 1 ? 1 : 2,
                        'customer_pickup_sheet_email' => isset($value['send_pick_up_sheet_email_to_customer']) && $value['send_pick_up_sheet_email_to_customer'] == 1 ? 1 : 2,
                        'consignee_pickup_sheet_email' => isset($value['send_pick_up_sheet_email_to_consignee']) && $value['send_pick_up_sheet_email_to_consignee'] == 1 ? 1 : 2,
                        'customer_booking_whatsapp' => isset($value['send_booking_whatsapp_message_to_the_customer']) && $value['send_booking_whatsapp_message_to_the_customer'] == 1 ? 1 : 2,
                        'shipper_booking_whatsapp' => isset($value['send_booking_whatsapp_message_to_the_shipper']) && $value['send_booking_whatsapp_message_to_the_shipper'] == 1 ? 1 : 2,
                        'consignee_booking_whatsapp' => isset($value['send_booking_whatsapp_message_to_the_consignee']) && $value['send_booking_whatsapp_message_to_the_consignee'] == 1 ? 1 : 2,
                        'customer_delivery_whatsapp' => isset($value['send_delivery_whatsapp_message_to_the_customer']) && $value['send_delivery_whatsapp_message_to_the_customer'] == 1 ? 1 : 2,
                        'shipper_delivery_whatsapp' => isset($value['send_delivery_whatsapp_message_to_the_shipper']) && $value['send_delivery_whatsapp_message_to_the_shipper'] == 1 ? 1 : 2,
                        'consignee_delivery_whatsapp' => isset($value['send_delivery_whatsapp_message_to_the_consignee']) && $value['send_delivery_whatsapp_message_to_the_consignee'] == 1 ? 1 : 2,
                        'customer_pickup_whatsapp' => isset($value['send_pick_up_done_whatsapp_message_to_the_customer']) && $value['send_pick_up_done_whatsapp_message_to_the_customer'] == 1 ? 1 : 2,
                        'shipper_pickup_whatsapp' => isset($value['send_pick_up_done_whatsapp_message_to_the_pick_up']) && $value['send_pick_up_done_whatsapp_message_to_the_pick_up'] == 1 ? 1 : 2,
                        'consignee_pickup_whatsapp' => isset($value['send_pick_up_done_whatsapp_message_to_the_consignee']) && $value['send_pick_up_done_whatsapp_message_to_the_consignee'] == 1 ? 1 : 2,
                        'customer_pickup_sheet_whatsapp' => isset($value['send_pick_up_sheet_whatsapp_message_to_the_customer']) && $value['send_pick_up_sheet_whatsapp_message_to_the_customer'] == 1 ? 1 : 2,
                        'shipper_pickup_sheet_whatsapp' => isset($value['send_pick_up_sheet_whatsapp_message_to_the_pick_up']) && $value['send_pick_up_sheet_whatsapp_message_to_the_pick_up'] == 1 ? 1 : 2,
                        'consignee_pickup_sheet_whatsapp' => isset($value['send_pick_up_sheet_whatsapp_message_to_the_consignee']) && $value['send_pick_up_sheet_whatsapp_message_to_the_consignee'] == 1 ? 1 : 2,
                        'customer_outstanding_whatsapp' => isset($value['send_total_outstanding_whatsapp_message_to_the_customer']) && $value['send_total_outstanding_whatsapp_message_to_the_customer'] == 1 ? 1 : 2,
                        'verify_delivery_otp' => isset($value['verify_otp_for_delivery']) && $value['verify_otp_for_delivery'] == 1 ? 1 : 2,
                        'customer_drs_otp_sms' => isset($value['send_otp_on_customer_phone_number_sms_before_delivery']) && $value['send_otp_on_customer_phone_number_sms_before_delivery'] == 1 ? 1 : 2,
                        'send_customer_otp_whatsapp' => isset($value['send_otp_on_customers_phone_number_whatsapp_delivery']) && $value['send_otp_on_customers_phone_number_whatsapp_delivery'] == 1 ? 1 : 2,
                        'send_customer_otp_email' => isset($value['send_otp_on_customer_phone_number_email_delivery']) && $value['send_otp_on_customer_phone_number_email_delivery'] == 1 ? 1 : 2,
                        'send_consignee_otp_sms' => isset($value['send_otp_on_consignee_phone_number_sms_before_delivery']) && $value['send_otp_on_consignee_phone_number_sms_before_delivery'] == 1 ? 1 : 2,
                        'send_consignee_otp_whatsapp' => isset($value['send_otp_on_consignee_phone_number_whatsapp_delivery']) && $value['send_otp_on_consignee_phone_number_whatsapp_delivery'] == 1 ? 1 : 2,
                        'send_consignee_otp_email' => isset($value['send_otp_on_consignee_phone_number_email_delivery']) && $value['send_otp_on_consignee_phone_number_email_delivery'] == 1 ? 1 : 2,


                    );

                    if (isset($value['customer_hubs']) && is_array($value['customer_hubs']) && count($value['customer_hubs']) > 0) {
                        foreach ($value['customer_hubs'] as $ch_key => $ch_value) {
                            $hub_code = $ch_value['hub_code'];
                            if ($hub_code != '') {
                                $hub_id = isset($all_hub[strtolower(trim($hub_code))]) ? $all_hub[strtolower(trim($hub_code))]['id'] : 0;
                                if ($hub_id > 0) {
                                    $customer_insert['hub_id'][] = $hub_id;
                                }
                            }
                        }
                    }

                    if (isset($value['kycs']) && is_array($value['kycs']) && count($value['kycs']) > 0) {
                        foreach ($value['kycs'] as $kkey => $kvalue) {
                            $doc_type_id = 0;
                            if ($kvalue['document_type'] == 'Telephone Bill') {
                                $doc_type_id = 10;
                            } elseif ($kvalue['document_type'] == 'Electric Bill') {
                                $doc_type_id = 9;
                            } elseif ($kvalue['document_type'] == 'Driving Licence') {
                                $doc_type_id = 8;
                            } elseif ($kvalue['document_type'] == 'Others') {
                                $doc_type_id = 11;
                            } elseif ($kvalue['document_type'] == 'IEC Certificate') {
                                $doc_type_id = 7;
                            } elseif ($kvalue['document_type'] == 'Authorization Letter') {
                                $doc_type_id = 6;
                            } elseif ($kvalue['document_type'] == 'GST Certificate') {
                                $doc_type_id = 5;
                            } elseif ($kvalue['document_type'] == 'Aadhar Card') {
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


                    $qry = "SELECT id FROM customer WHERE status IN(1,2) AND migration_id='" . $value['id'] . "'";
                    $qry_exe = $this->db->query($qry);
                    $existData = $qry_exe->row_array();


                    //CONTRACT HEAD
                    if ($value['contract_head'] != '') {
                        //GET CUSTOMER ID
                        $selectq = "SELECT id FROM customer WHERE code='" . $value['contract_head'] . "' AND status IN(1,2)";
                        $selectq_exe = $this->db->query($selectq);
                        $contractCustomer = $selectq_exe->row_array();
                        if (isset($contractCustomer) && is_array($contractCustomer) && count($contractCustomer) > 0) {
                            $customer_insert['cc_customer_id'][0] = $contractCustomer['id'];
                            $customer_insert['cc_from_date'][0] = '2000-01-01';
                            $customer_insert['cc_till_date'][0] = '3000-01-01';
                        }
                    }

                    //RATE MODIFIER HEAD
                    if ($value['rate_modifier_head'] != '') {
                        //GET CUSTOMER ID
                        $selectq = "SELECT id FROM customer WHERE code='" . $value['rate_modifier_head'] . "' AND status IN(1,2)";
                        $selectq_exe = $this->db->query($selectq);
                        $contractCustomer = $selectq_exe->row_array();
                        if (isset($contractCustomer) && is_array($contractCustomer) && count($contractCustomer) > 0) {
                            $customer_insert['rate_customer_id'][0] = $contractCustomer['id'];
                            $customer_insert['rate_from_date'][0] = '2000-01-01';
                            $customer_insert['rate_till_date'][0] = '3000-01-01';
                        }
                    }


                    if (isset($existData) && is_array($existData) && count($existData) > 0) {
                        $customer_insert['customer_id'] = $existData['id'];
                        $this->customer_masters->update($customer_insert);
                    } else {
                        $this->customer_masters->insert($customer_insert);
                    }

                    //UPDATE OFFSET
                    $qry = "SELECT  id,config_value FROM migration_log WHERE status IN(1,2) AND config_key='customer_migrate_last_id' ";
                    $qry_exe = $this->db->query($qry);
                    $configExist = $qry_exe->row_array();
                    if (isset($configExist) && is_array($configExist) && count($configExist) > 0) {
                        $updateq = "UPDATE migration_log SET config_value='" . $value['id'] . "' WHERE status IN(1,2) AND config_key='customer_migrate_last_id'";
                        $this->db->query($updateq);
                    } else {
                        $mig_insert_data = array(
                            'config_key' => 'customer_migrate_last_id',
                            'config_value' => $value['id']
                        );
                        $this->gm->insert('migration_log', $mig_insert_data);
                    }

                    echo "<br>CUSTOMER CODE " . $value['code'] . " added";
                }
            }
        }
        $time_end = microtime(true);
        $execution_time = ($time_end - $time_start) / 60;

        //execution time of the script
        echo '<b>Total Execution Time:</b> ' . ($time_end - $time_start) . ' Second';
        echo '<b>Total Execution Time:</b> ' . $execution_time . ' Mins';
    }



    public function save_kyc_data($company_name = '')
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
        //$base_url = "http://old.awcc.online/";
        if ($base_url != '') {
            // $api_url = $base_url . "api/v5/customer_masters";
            // $json_data = @file_get_contents($api_url);
            // $result = json_decode($json_data, TRUE);

            $url = $base_url . "api/v5/customer_masters/fetch_customers";

            $qry = "SELECT  id,config_value FROM migration_log WHERE status IN(1,2) AND config_key='customer_kyc_migrate_last_id' ";
            $qry_exe = $this->db->query($qry);
            $offset_res = $qry_exe->row_array();

            $limit = 100;
            if (isset($offset_res['config_value']) && $offset_res['config_value'] != '') {
                $last_id = $offset_res['config_value'];
            } else {
                $last_id = 0;
            }
            $qry = "SELECT migration_id FROM customer WHERE migration_id > " . $last_id . " ORDER BY migration_id  LIMIT " . $limit . "";
            $qry_exe = $this->db->query($qry);
            $id_res = $qry_exe->result_array();

            if (isset($id_res) && is_array($id_res) && count($id_res) > 0) {
                foreach ($id_res as $ikey => $ivalue) {
                    $id_arr[] = $ivalue['migration_id'];
                }
            }
            // $id_arr[] = 4623;

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
            }
        }

        // echo '<pre>';
        // print_r($result);
        // exit;

        if (isset($result) && is_array($result) && count($result) > 0) {

            if (isset($result['data']) && is_array($result['data']) && count($result['data']) > 0) {

                $keys = array_column($result['data'], 'id');
                array_multisort($keys, SORT_ASC, $result['data']);

                foreach ($result['data'] as $key => $value) {

                    $customer_insert = array();
                    if (isset($value['kycs']) && is_array($value['kycs']) && count($value['kycs']) > 0) {
                        foreach ($value['kycs'] as $kkey => $kvalue) {
                            $doc_type_id = 0;
                            if ($kvalue['document_type'] == 'Telephone Bill') {
                                $doc_type_id = 10;
                            } elseif ($kvalue['document_type'] == 'Electric Bill') {
                                $doc_type_id = 9;
                            } elseif ($kvalue['document_type'] == 'Driving Licence') {
                                $doc_type_id = 8;
                            } elseif ($kvalue['document_type'] == 'Others') {
                                $doc_type_id = 11;
                            } elseif ($kvalue['document_type'] == 'IEC Certificate') {
                                $doc_type_id = 7;
                            } elseif ($kvalue['document_type'] == 'Authorization Letter') {
                                $doc_type_id = 6;
                            } elseif ($kvalue['document_type'] == 'GST Certificate') {
                                $doc_type_id = 5;
                            } elseif ($kvalue['document_type'] == 'Aadhar Card') {
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
                                    $customer_insert[$doc_type_id]['doc_no'] = $kvalue['document_no'];
                                    $customer_insert[$doc_type_id]['doc_name'] = $kvalue['name_as_per_document'];
                                    $customer_insert[$doc_type_id]['live_file_1'] = $kvalue['image'];
                                    $customer_insert[$doc_type_id]['live_file_2'] = $kvalue['image1'];
                                }
                            }
                        }
                    }
                    $qry = "SELECT id FROM customer WHERE status IN(1,2) AND migration_id='" . $value['id'] . "'";
                    $qry_exe = $this->db->query($qry);
                    $existData = $qry_exe->row_array();

                    if (isset($existData) && is_array($existData) && count($existData) > 0) {
                        $customer_id = $existData['id'];
                        if (isset($customer_insert) && is_array($customer_insert) && count($customer_insert) > 0) {
                            foreach ($customer_insert as $kkey => $kvalue) {
                                $query = "SELECT * FROM document_mapping WHERE status IN(1,2) AND doc_type_id = " . $kkey . " AND module_type=1 AND module_id=" . $customer_id;
                                $query_exe = $this->db->query($query);
                                $kycExist = $query_exe->row_array();

                                if (isset($kycExist) && is_array($kycExist) && count($kycExist) > 0) {

                                    $doc_insert =  array(
                                        'module_id' => $customer_id,
                                        'module_type' => 1,
                                        'doc_type_id' => $kkey,
                                        'modified_date' => date('Y-m-d H:i:s'),
                                        'modified_by' => $this->user_id
                                    );

                                    if ($kycExist['doc_no'] == '') {
                                        $doc_insert['doc_no'] = $kvalue['doc_no'];
                                    }
                                    if ($kycExist['doc_name'] == '') {
                                        $doc_insert['doc_name'] = $kvalue['doc_name'];
                                    }

                                    if ($kycExist['doc_page1'] == '' && $kvalue['live_file_1'] != '') {
                                        $file_name = $kkey . date('YmdHis') . '_' . basename($kvalue['live_file_1']); // to get file name
                                        $file_name = urldecode($file_name);
                                        $doc_insert['doc_page1'] = create_year_month_dir('shipper_kyc_document') . '/' . $file_name;
                                        file_put_contents($doc_insert['doc_page1'], @file_get_contents($kvalue['live_file_1']));
                                    }
                                    if ($kycExist['doc_page2'] == '' && $kvalue['live_file_2'] != '') {
                                        $file_name = $kkey . date('YmdHis') . '_' . basename($kvalue['live_file_2']); // to get file name
                                        $file_name = urldecode($file_name);
                                        $doc_insert['doc_page2'] = create_year_month_dir('shipper_kyc_document') . '/' . $file_name;
                                        file_put_contents($doc_insert['doc_page2'], @file_get_contents($kvalue['live_file_2']));
                                    }

                                    $this->gm->update('document_mapping', $doc_insert, '', array('id' => $kycExist['id']));
                                } else {
                                    $doc_insert =  array(
                                        'module_id' => $customer_id,
                                        'module_type' => 1,
                                        'doc_type_id' => $kkey,
                                        'doc_no' => $kvalue['doc_no'],
                                        'doc_name' => $kvalue['doc_name'],
                                        'created_date' => date('Y-m-d H:i:s'),
                                        'created_by' => $this->user_id
                                    );

                                    if ($kvalue['live_file_1'] != '') {
                                        $file_name = $kkey . date('YmdHis') . '_' . basename($kvalue['live_file_1']); // to get file name
                                        $file_name = urldecode($file_name);
                                        $doc_insert['doc_page1'] = create_year_month_dir('shipper_kyc_document') . '/' . $file_name;
                                        file_put_contents($doc_insert['doc_page1'], @file_get_contents($kvalue['live_file_1']));
                                    }
                                    if ($kvalue['live_file_2'] != '') {
                                        $file_name = $kkey . date('YmdHis') . '_' . basename($kvalue['live_file_2']); // to get file name
                                        $file_name = urldecode($file_name);
                                        $doc_insert['doc_page2'] = create_year_month_dir('shipper_kyc_document') . '/' . $file_name;
                                        file_put_contents($doc_insert['doc_page2'], @file_get_contents($kvalue['live_file_2']));
                                    }

                                    $this->gm->insert('document_mapping', $doc_insert);
                                }
                            }
                        }
                    }

                    //UPDATE OFFSET
                    $qry = "SELECT  id,config_value FROM migration_log WHERE status IN(1,2) AND config_key='customer_kyc_migrate_last_id' ";
                    $qry_exe = $this->db->query($qry);
                    $configExist = $qry_exe->row_array();
                    if (isset($configExist) && is_array($configExist) && count($configExist) > 0) {
                        $updateq = "UPDATE migration_log SET config_value='" . $value['id'] . "' WHERE status IN(1,2) AND config_key='customer_kyc_migrate_last_id'";
                        $this->db->query($updateq);
                    } else {
                        $mig_insert_data = array(
                            'config_key' => 'customer_kyc_migrate_last_id',
                            'config_value' => $value['id']
                        );
                        $this->gm->insert('migration_log', $mig_insert_data);
                    }

                    echo "<br>CUSTOMER CODE " . $value['code'] . " kyc added";
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
