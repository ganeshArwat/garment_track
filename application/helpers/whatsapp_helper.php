<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function send_whatsapp_msg_old($message = '', $phone_no = '')
{
    $response_data = array();
    $CI = &get_instance();

    $qry = "SELECT config_key,config_value FROM app_settings WHERE config_key='whatsapp_sms_api_key'";
    $qry_exe = $CI->db->query($qry);
    $setting_data = $qry_exe->row_array();

    $api_key = isset($setting_data['config_value']) ? $setting_data['config_value'] : '';
    if ($message != '' && $phone_no != '') {
        $api_url = "http://148.251.129.118/wapp/api/send?apikey=" . $api_key . "&mobile=" . $phone_no . "msg=" . $message;
        $api_response_json = file_get_contents($api_url);
        $api_response = json_decode($api_response_json, TRUE);
        if (isset($api_response['status']) && $api_response['status'] == 'ERROR') {
            $response_data = array(
                'status' => 'failed',
                'message' => $api_response['errormsg']
            );
        } else {
            $response_data = array(
                'status' => 'success',
                'message' => 'sent'
            );
        }
    }

    return $response_data;
}


function send_whatsapp_msg($message = '', $phone_no = '', $customer_id = 0, $pdf_path = "")
{
    $response_data = array();
    $CI = &get_instance();

    // $qry = "SELECT config_key,config_value FROM app_settings WHERE config_key IN('whatsapp_sms_instance_id','whatsapp_sms_access_token')";
    // $qry_exe = $CI->db->query($qry);
    // $setting_data = $qry_exe->result_array();

    // if (isset($setting_data) && is_array($setting_data) && count($setting_data) > 0) {
    //     foreach ($setting_data as $skey => $svalue) {
    //         $setting_credential[$svalue['config_key']] = $svalue['config_value'];
    //     }
    // }


    // $instance_id = isset($setting_credential['whatsapp_sms_instance_id']) ? $setting_credential['whatsapp_sms_instance_id'] : '';
    // $access_token = isset($setting_credential['whatsapp_sms_access_token']) ? $setting_credential['whatsapp_sms_access_token'] : '';

    $qry = "SELECT id,company_id FROM customer WHERE id='" . $customer_id . "'";
    $qry_exe = $CI->db->query($qry);
    $customer_data = $qry_exe->row_array();

    if (isset($customer_data['company_id']) && $customer_data['company_id'] > 0) {
        $qry = "SELECT id,whatsapp_token FROM company_master WHERE id='" . $customer_data['company_id'] . "'";
        $qry_exe = $CI->db->query($qry);
        $token_data = $qry_exe->row_array();
    } else {
        $qry = "SELECT id,whatsapp_token FROM company_master WHERE status=1 AND whatsapp_token!=''";
        $qry_exe = $CI->db->query($qry);
        $token_data = $qry_exe->row_array();
    }


    $access_token = isset($token_data['whatsapp_token']) ? $token_data['whatsapp_token'] : '';
    if ($message != '' && $phone_no != '' && $access_token != '') {

        if (strpos($phone_no, '+91') === 0 || strpos($phone_no, '91') === 0) {
            $api_url = "http://bulkwhatsapp.live/whatsapp/api/send?apikey=" . $access_token . "&mobile=" . $phone_no . "&msg=" . urlencode($message);
            if ($pdf_path != "") {
                $api_url = "http://148.251.129.118/whatsapp/api/send?mobile=" . $phone_no . "&msg=" . urlencode($message) . "&apikey=" . $access_token . "&pdf=" . $pdf_path;
            }
        } else {
            $api_url = "http://bulkwhatsapp.live/whatsapp/api/send?mobile=" . $phone_no . "&msg=" . urlencode($message) . "&apikey=" . $access_token . "&intl=true";
            if ($pdf_path != "") {
                $api_url = "http://148.251.129.118/whatsapp/api/send?mobile=" . $phone_no . "&msg=" . urlencode($message) . "&apikey=" . $access_token . "&pdf=" . $pdf_path . "&intl=true";
            }
        }

        if (isset($_GET['test_msg'])) {
            echo "<br>API_URL=" . $api_url;
        } else {
            //$api_url = "http://www.api37.com/api/send.php?number=" . $phone_no . "&type=text&message=" . urlencode($message) . "&instance_id=" . $instance_id . "&access_token=" . $access_token;
            $api_response_json = file_get_contents($api_url);

            // $api_response_json = '{"status":"success","message":"Success","data":{"key":{"remoteJid":"8390810863@c.us","fromMe":true,"id":"BAE50B841343DF5C"},"message":{"extendedTextMessage":{"text":"Thank you for shipping with Blue Line Express Cargp, your shipment has been booked on 02\/09\/2021 under AWB no. GST03. Please track your shipment on awcc.in"}},"messageTimestamp":"1650088103","status":"PENDING"}}';
            $api_response = json_decode($api_response_json, TRUE);
        }



        if (isset($api_response['status']) && $api_response['status'] == 'error') {
            $response_data = array(
                'status' => 'failed',
                'message' => $api_response['errormsg']
            );
        } else {
            $response_data = array(
                'status' => 'success',
                'message' => 'sent'
            );
            $mail_tracking_insert = array(
                'mail_type' => 3,
                'recipient' => $phone_no,
                'trigger_date' => date('Y-m-d H:i:s'),
                'created_date' => date('Y-m-d H:i:s'),
            );
            $CI->db->insert('mail_tracking', $mail_tracking_insert);
        }
    } else {
        $response_data = array(
            'status' => 'failed',
            'message' => 'Message/Phone/Instance ID OR Access Token Required'
        );
    }

    return $response_data;
}
