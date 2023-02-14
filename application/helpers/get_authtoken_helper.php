<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function get_authtoken($auth_token = 0, $user_id = 0)
{
    $result = array();
    $CI = &get_instance();
    $main_db = $CI->load->database('main_db', true);
    $qry = "SELECT id FROM admin_user WHERE status IN(1,2) AND id='" . $user_id . "' AND auth_token='" . $auth_token . "'";
    file_put_contents(FCPATH . '/log1/api_qry.txt', serialize($qry));
    $qry_exe = $main_db->query($qry);
    $auth_data = $qry_exe->row_array();
    if (isset($auth_data) && is_array($auth_data) && count($auth_data) > 0) {
        return true;
    } else {
        return false;
    }
}
function get_authtoken_customer_user($auth_token = 0, $user_id = 0, $customer_id = 0, $company_id = 0)
{
    $result = array();
    $CI = &get_instance();

    if ($company_id != 0 && $customer_id != 0) {
        $qry = "SELECT id FROM customer_users WHERE status IN(1,2) AND id='" . $user_id . "' AND auth_token='" . $auth_token . "'";
        file_put_contents(FCPATH . '/log1/api_qry.txt', serialize($qry));
        $qry_exe = $CI->db->query($qry);
        $auth_data = $qry_exe->row_array();
        if (isset($auth_data) && is_array($auth_data) && count($auth_data) > 0) {
            return true;
        } else {
            return false;
        }
    }
}
