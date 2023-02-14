<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


function send_sms_msg_old($message = '', $phone_no = '', $template_id = '', $sms_templated_id = 0)
{
    $response_data = array();
    $CI = &get_instance();

    $qry = "SELECT config_key,config_value FROM app_settings WHERE module_name='sms'";
    $qry_exe = $CI->db->query($qry);
    $setting_data = $qry_exe->result_array();

    if (isset($setting_data) && is_array($setting_data) && count($setting_data) > 0) {
        foreach ($setting_data as $skey => $svalue) {
            $sms_credential[$svalue['config_key']] = $svalue['config_value'];
        }
    }

    if ($message != '' && $phone_no != '') {
        $username = isset($sms_credential['sms_username']) ? $sms_credential['sms_username'] : '';
        $sender_id = isset($sms_credential['sms_sender_id']) ? $sms_credential['sms_sender_id'] : '';
        $api_key = isset($sms_credential['sms_api_key']) ? $sms_credential['sms_api_key'] : '';
        $peid = isset($sms_credential['sms_peid']) ? $sms_credential['sms_peid'] : '';

        // $api_url = "http://sms.messageindia.in/v2/sendSMS?username=" . $username . "&message=" . $message
        //     . "&sendername=" . $sender_id . "&smstype=TRANS&numbers=" . $phone_no
        //     . "&apikey=" . $api_key . "&peid=" . $peid . "&templateid=" . $template_id;
        //$api_response_json = file_get_contents($api_url);

        $url = "http://sms.messageindia.in/v2/sendSMS?";
        $get_sms_data = array(
            "username" => $username,
            "message" => $message,
            "sendername" => $sender_id,
            "smstype" => "TRANS",
            "numbers" => $phone_no,
            "apikey" => $api_key,
            "peid" => $peid,
            "templateid" => $template_id,
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $get_sms_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $api_response_json = curl_exec($ch);
        curl_close($ch);

        $api_response = json_decode($api_response_json, true);

        if (isset($api_response[0]['status']) && $api_response[0]['status'] == 'error') {
            $response_data = array(
                'status' => 'failed',
                'message' => $api_response[0]['msg']
            );
        } else {
            $response_data = array(
                'status' => 'success',
                'message' => 'sent'
            );
        }
    }

    // $response_data = array(
    //     'status' => 'success',
    //     'message' => 'sent'
    // );

    return $response_data;
}


function send_sms_msg($message = '', $phone_no = '', $template_id = '', $sms_templated_id = 0)
{
    $response_data = array();
    $CI = &get_instance();

    $qry = "SELECT config_key,config_value FROM app_settings WHERE module_name='sms'";
    $qry_exe = $CI->db->query($qry);
    $setting_data = $qry_exe->result_array();
    if (isset($setting_data) && is_array($setting_data) && count($setting_data) > 0) {
        foreach ($setting_data as $skey => $svalue) {
            $sms_credential[$svalue['config_key']] = $svalue['config_value'];
        }
    }

    if ($message != '' && $phone_no != '') {
        $username = isset($sms_credential['sms_username']) ? $sms_credential['sms_username'] : '';
        $sender_id = isset($sms_credential['sms_sender_id']) ? $sms_credential['sms_sender_id'] : '';
        $api_key = isset($sms_credential['sms_api_key']) ? $sms_credential['sms_api_key'] : '';
        $peid = isset($sms_credential['sms_peid']) ? $sms_credential['sms_peid'] : '';
        $campaign = isset($sms_credential['campaign']) ? $sms_credential['campaign'] : '';
        $routeid = isset($sms_credential['routeid']) ? $sms_credential['routeid'] : '';
        $sms_vendor = isset($sms_credential['sms_vendor']) ? $sms_credential['sms_vendor'] : '';

        $sms_text = rawurlencode($message);
        if ($sms_vendor == 1) {
            $api_url = "http://193.46.243.10/app/smsapi/index.php?key=" . $api_key . "&campaign=" . $campaign . "&routeid=" . $routeid . "&type=text&contacts=" . $phone_no . "&senderid=" . $sender_id . "&msg=" . $sms_text . '&template_id=' . $template_id . "&pe_id=" . $peid;
        } else if ($sms_vendor == 2) {
            $api_url = "http://jskbulksms.in/app/smsapi/index.php?key=" . $api_key . "&campaign=1&routeid=46&type=text&contacts=" . $phone_no . "&senderid=" . $sender_id . "&msg=" . $sms_text . '&template_id=' . $template_id;
        }
        $api_response_json = file_get_contents($api_url);

        // $url = "http://jskbulksms.in/app/smsapi/index.php?";
        // $get_sms_data = array(
        //     "key" => $api_key,
        //     "campaign" => '1',
        //     "routeid" => '46',
        //     "type" => "text",
        //     "contacts" => $phone_no,
        //     "senderid" => $sender_id,
        //     "peid" => $peid,
        //     "msg" => $message,
        //     "template_id" => $template_id,
        // );
        // $get_param = http_build_query($get_sms_data);
        // $api_url = $url . $get_param;
        // $api_response_json = file_get_contents($api_url);

        //echo $api_response_json;

        if (strpos($api_response_json, 'ERR:') !== false) {
            $response_data = array(
                'status' => 'failed',
                'message' => $api_response_json
            );
        } else {
            $response_data = array(
                'status' => 'success',
                'message' => 'sent'
            );

            $mail_tracking_insert = array(
                'mail_type' => 2,
                'recipient' => $phone_no,
                'trigger_date' => date('Y-m-d H:i:s'),
                'created_date' => date('Y-m-d H:i:s'),
            );
            $qry_exe = $CI->db->insert('mail_tracking', $mail_tracking_insert);
        }
    }

    return $response_data;
}
