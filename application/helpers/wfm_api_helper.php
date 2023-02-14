<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


if (!function_exists('get_all_ticket_type_itd')) {
    function get_all_ticket_type_itd()
    {

        $result = array();
        $CI = &get_instance();
        $sessiondata = $CI->session->userdata('admin_user');
        if ($sessiondata["com_id"] > 0) {
            $all_company = get_all_company();
            $company_url = isset($all_company[$sessiondata["com_id"]]) ? $all_company[$sessiondata["com_id"]]['company_domain'] : '';
            $client_id_arr = get_all_client_id_itd($company_url, $sessiondata['com_id']);
        }
        $client_id_arr = json_decode($client_id_arr, true);
        $client_id = isset($client_id_arr['id']) ? $client_id_arr['id'] : 0;

        $url = ITD_WFM_IP . "api/get_all_ticket_type?client_id=" . $client_id;

        $get = array();
        $data = http_build_query($get);
        $getUrl = $url . "?" . $data;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_URL, $getUrl);
        curl_setopt($ch, CURLOPT_TIMEOUT, 80);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}
if (!function_exists('get_all_users_itd')) {
    function get_all_users_itd()
    {
        $result = array();
        $CI = &get_instance();
        $url = ITD_WFM_IP . "api/get_all_users_itd";
        $get = array();
        $data = http_build_query($get);
        $getUrl = $url . "?" . $data;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_URL, $getUrl);
        curl_setopt($ch, CURLOPT_TIMEOUT, 80);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}
if (!function_exists('get_all_ticket_sub_type_itd')) {
    function get_all_ticket_sub_type_itd($ticket_type_id = 0)
    {
        $result = array();
        $CI = &get_instance();
        $sessiondata = $CI->session->userdata('admin_user');
        if ($sessiondata["com_id"] > 0) {
            $all_company = get_all_company();
            $company_url = isset($all_company[$sessiondata["com_id"]]) ? $all_company[$sessiondata["com_id"]]['company_domain'] : '';
            $client_id_arr = get_all_client_id_itd($company_url, $sessiondata['com_id']);
        }
        $client_id_arr = json_decode($client_id_arr, true);
        $client_id = isset($client_id_arr['id']) ? $client_id_arr['id'] : 0;

        $url = ITD_WFM_IP . "api/get_all_ticket_sub_type?client_id=" . $client_id;
        $get = array("ticket_type_id" => $ticket_type_id);
        $data = http_build_query($get);
        $getUrl = $url . "&" . $data;
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_URL, $getUrl);
        curl_setopt($ch, CURLOPT_TIMEOUT, 80);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}
if (!function_exists('get_latest_ticket_no_itd')) {
    function get_latest_ticket_no_itd()
    {
        $result = array();
        $CI = &get_instance();
        $url = ITD_WFM_IP . "api/get_latest_ticket_number";
        $get = array();
        $data = http_build_query($get);
        $getUrl = $url . "?" . $data;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_URL, $getUrl);
        curl_setopt($ch, CURLOPT_TIMEOUT, 80);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}
if (!function_exists('get_all_client_id_itd')) {
    function get_all_client_id_itd($server_ip, $company_id)
    {
        $result = array();
        $CI = &get_instance();
        $url = ITD_WFM_IP . "api/get_client_id_itd";
        $get = array("droplet" => $server_ip, "company_id" => $company_id);
        $data = http_build_query($get);
        $getUrl = $url . "?" . $data;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_URL, $getUrl);
        curl_setopt($ch, CURLOPT_TIMEOUT, 80);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}

if (!function_exists('get_all_module_id_itd')) {
    function get_all_module_id_itd($module_name = "")
    {
        $result = array();
        $CI = &get_instance();
        $url = ITD_WFM_IP . "api/get_module_id_itd";
        $get = array("module_name" => $module_name);
        $data = http_build_query($get);
        $getUrl = $url . "?" . $data;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_URL, $getUrl);
        curl_setopt($ch, CURLOPT_TIMEOUT, 80);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}

if (!function_exists('case_insert_itd')) {
    function case_insert_itd($insert_array)
    {

        $CI = &get_instance();
        $url = ITD_WFM_IP . "api/case_insert";

        $dataArray = array("insert_data" => $insert_array);
        if (is_array($dataArray)) {
            $data = $dataArray;
        } else {
            $data = $dataArray;
        }
        $request_json = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,  $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request_json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type:application/json'
        ));
        $response = curl_exec($ch);


        curl_close($ch);

        return $response;
    }
    /* function call_api_curl_get($url, $dataArray)
    {
        //$call_type=1 (GET)
        //$call_type=2(POST)
        $ch = curl_init();
        $data = http_build_query($dataArray);
        $getUrl = $url . "?" . $data;
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_URL, $getUrl);
        curl_setopt($ch, CURLOPT_TIMEOUT, 80);

        $response = curl_exec($ch);

        if (curl_error($ch)) {
            $error = 'Request Error:' . curl_error($ch);
            return $error;
            curl_close($ch);
        } else {
            curl_close($ch);
            return $response;
        }
    }
    function call_api_curl_post($url, $dataArray) {
        if (is_array($dataArray)) {
            $data = http_build_query($dataArray);
        } else {
            $data = $dataArray;
        }
        if (is_string($data) && is_array(json_decode($data, true)) && (json_last_error() == JSON_ERROR_NONE)) {
    
            $headers = [
                'Content-Type: application/json'
            ];
        }
    
        $cURL = curl_init();
        curl_setopt($cURL, CURLOPT_URL, $url);
        curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
    
        if (is_string($data) && is_array(json_decode($data, true)) && (json_last_error() == JSON_ERROR_NONE)) {
            curl_setopt($cURL, CURLOPT_HTTPHEADER, $headers);
        } else {
            curl_setopt($cURL, CURLOPT_HEADER, true);
        }
    
        curl_setopt($cURL, CURLOPT_CONNECTTIMEOUT, 10);
    
        curl_setopt($cURL, CURLOPT_POST, true);
        curl_setopt($cURL, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($cURL);
        curl_close($cURL);
    
        return $response;
    }*/

    if (!function_exists('get_ticket_tags')) {
        function get_ticket_tags($where = '')
        {
            $result = array(
                '1' => array('id' => 1, 'name' => 'Software Issue / Bug'),
                '2' => array('id' => 2, 'name' => 'Software Deployment'),
                '3' => array('id' => 3, 'name' => 'Software Requirment'),
                '4' => array('id' => 4, 'name' => 'Software query'),
                '5' => array('id' => 5, 'name' => 'Training Request'),
                '6' => array('id' => 6, 'name' => 'Visit'),
            );
            return $result;
        }
    }
}
