<?php
class Whatsapp_api extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function send_sms()
    {
        $data = [
            'phone' => '919172969414', // Receivers phone
            'body' => 'Test MSG offline phone off', // Message
        ];
        $json = json_encode($data); // Encode data to JSON
        // URL for request POST /message
        $token = 'jxl9lbs822jcqc6r';
        $instanceId = '375897';
        $url = 'https://api.chat-api.com/instance' . $instanceId . '/message?token=' . $token;
        // Make a POST request
        $options = stream_context_create([
            'http' => [
                'method'  => 'POST',
                'header'  => 'Content-type: application/json',
                'content' => $json
            ]
        ]);

        // Send a request
        $result = @file_get_contents($url, false, $options);
        echo '<pre>';
        print_r($result);
        exit;
    }
    public function qr_image()
    {
        $token = 'jxl9lbs822jcqc6r';
        $instanceId = '375897';
        $url = 'https://api.chat-api.com/instance' . $instanceId . '/qr_code?token=' . $token;

        echo "<img src=" . $url . ">";
    }
    public function get_qr_status()
    {
        $token = 'jxl9lbs822jcqc6r';
        $instanceId = '375897';
        $url = 'https://api.chat-api.com/instance' . $instanceId . '/status?token=' . $token;

        $url = 'https://api.chat-api.com/instance376628/status?token=7z9v7leleok2no6e';
        $result = @file_get_contents($url);

        $res_arr = json_decode($result, TRUE);

        echo "<img src=" . $res_arr['qrCode'] . ">";
        echo '<pre>';
        print_r($res_arr);
        exit;
    }
}
