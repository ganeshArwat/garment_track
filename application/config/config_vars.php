<?php

$config['node_api_ip'] = 'http://139.59.43.12/';
$config['email_trigger'] = array(
    1 => array('id' => 1, 'config_key' => 'pod_email', 'config_value' => 'POD Email', 'field' => ''),
    2 => array('id' => 2, 'config_key' => 'weight_change_per_docket', 'config_value' => 'Weight Change Email Per Docket', 'field' => 'docket'),
    3 => array('id' => 3, 'config_key' => 'weight_change_daily', 'config_value' => 'Weight Change Email Daily', 'field' => ''),
    4 => array('id' => 4, 'config_key' => 'booking_email_per_docket', 'config_value' => 'Booking Email Per Docket', 'field' => 'docket'),
    5 => array('id' => 5, 'config_key' => 'booking_email_daily', 'config_value' => 'Booking Email Daily', 'field' => ''),
    6 => array('id' => 6, 'config_key' => 'customer_mis_email', 'config_value' => 'Customer MIS Email', 'field' => ''),
    7 => array('id' => 7, 'config_key' => 'vendor_mis_email', 'config_value' => 'Vendor MIS Email', 'field' => ''),
    8 => array('id' => 8, 'config_key' => 'customer_outstanding_email', 'config_value' => 'Customer Outstanding Email', 'field' => 'outstanding'),
    9 => array('id' => 9, 'config_key' => 'customer_invoice_email', 'config_value' => 'Customer Invoice Email', 'field' => 'invoice'),
);


$config['company_base_url'] = array(
    'test_company' => 'http://139.59.60.106/',
    'universal' => 'http://139.59.12.87/',
);

$config['super_admin'] = 14;
$config['billing_role'] = 2;
$config['director_role'] = 13;

$config["admin_role_name"] = array('software_user');
$config['auth_letter_head'] = array(
    array("id" => '1', "name" => 'fedex'),
    array("id" => '2', "name" => 'dhl'),
    array("id" => '3', "name" => 'ups'),
    array("id" => '4', "name" => 'tnt'),
    array("id" => '5', "name" => 'self'),
    array("id" => '6', "name" => 'self_import'),
    array("id" => '7', "name" => 'self_2'),
);

$config['multiple_login_id'] = array(
    '0' => "virag@itdservices.in",
);
