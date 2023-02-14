<?php
$body = file_get_contents("php://input");
$headers = getallheaders();
// file_put_contents('./log1/norsk_input.txt', $body);
// file_put_contents('./log1/norsk_header.txt', json_encode($headers));

if (isset($headers) && is_array($headers) && count($headers) > 0) {
    foreach ($headers as $key => $value) {
        $final_credential[strtolower($key)] = $value;
    }
}

$NorskAccessKeyId = isset($final_credential['norskaccesskeyid']) ? $final_credential['norskaccesskeyid'] : '';
$NorskSecretAccessKey = isset($final_credential['norsksecretaccesskey']) ? $final_credential['norsksecretaccesskey'] : '';

// $NorskAccessKeyId = "P3FD3NOMDCL7SMQW";
// $NorskSecretAccessKey = "SC2FJ5V7S7QPUHZAAD7RTUZRVEDLKXW7Q4MHOMWPFDUWL2BU";
date_default_timezone_set('Asia/Kolkata');
$d = time();
$now = date('D, d M Y H:i:s', $d);
$contentType = "application/json";

$md5 = md5($body, false);
$StringToSign = "POST\n" . $md5 . "\n" . $contentType . "\n" . $now . " GMT\n" . "/api/shipment";

$Signature = Base64_encode(hash_hmac("SHA1", $StringToSign, $NorskSecretAccessKey, true));
$url = 'http://api.norsk-global.com/api/shipment';

// use key 'http' even if you send the request to https://...
$options = array(
    'http' => array(
        'ignore_errors' => true,
        'header' => 'Authorization: ' . $NorskAccessKeyId . ":" . $Signature . "\r\n" .
            'Date: ' . $now . "\r\n" .
            "Accept: application/json\r\n" .
            "Content-Type: application/json\r\n",
        'method'  => 'POST',
        'content' => $body
    )
);
$context  = stream_context_create($options);
$result = @file_get_contents($url, false, $context);

echo $result;
