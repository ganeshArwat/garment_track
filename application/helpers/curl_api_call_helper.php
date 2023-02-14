<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function call_api_curl_get($url, $dataArray) {
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
}
