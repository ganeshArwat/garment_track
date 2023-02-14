<?php
$log_data['input']= file_get_contents("php://input");
$log_data['post_data']= $_POST;
file_put_contents('token_api_log.txt', json_encode($log_data));
	
$post_data = $_POST;
$client_code = $post_data['client_code'];
$secret_key = $post_data['secret_key'];
$login_username = $post_data['login_username'];
$login_password = $post_data['login_password'];

$token = ''; $hash = '';
$client_code = $client_code ;
$username = 'shreemaruticourier'; //Static string
$password = '14daf8a3b6244969d9ac951de4871eed'; //Static string
$sec_key = $secret_key;
$request_json = '{"data": {"login_username": "'.$login_username.'", "login_password":"'.$login_password.'"}}';
if (!empty($client_code) && !empty($username) && !empty($password) && !empty($sec_key)) {
$hash = $client_code . '|' . $username . '|' . $password . '|' . $request_json;

$token = hash_pbkdf2('sha256', $hash, $sec_key, 100000, 60, false);
}

$response_data['token'] = $token;
echo json_encode($response_data);
?>