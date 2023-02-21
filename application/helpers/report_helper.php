<?php
if (!function_exists('get_validation_field_navbar')) {
    function get_validation_field_navbar($module_id = 0, $array_key = 'label_key', $validation_type = 1)
    {
        $CI = &get_instance();
        $sessiondata = $CI->session->userdata('admin_user');
        $validation_field = array();
        $all_module_field = get_all_module_field(" AND module_id=" . $module_id);
        if (isset($all_module_field) && is_array($all_module_field) && count($all_module_field) > 0) {
            foreach ($all_module_field as $key => $value) {
                $all_fields[$value['label_key']] = $value;
            }
        }

        if ($sessiondata['type'] == 'customer') {
            $query = "SELECT * FROM custom_validation_field WHERE module_id =" . $module_id . " AND status = 1 AND validation_type =" . $validation_type . " AND validation_user = 2";
            $qry_exe = $CI->db->query($query);
            $edit_data = $qry_exe->result_array();
        } else {
            $query = "SELECT * FROM custom_validation_field WHERE module_id =" . $module_id . " AND status = 1 AND validation_type =" . $validation_type . " AND validation_user = 1";
            $qry_exe = $CI->db->query($query);
            $edit_data = $qry_exe->result_array();
        }

        if (isset($edit_data) && is_array($edit_data) && count($edit_data) > 0) {
            foreach ($edit_data as $ekey => $evalue) {
                if (isset($all_fields[$evalue['label_key']])) {
                    $validation_field[$all_fields[$evalue['label_key']][$array_key]] = $all_fields[$evalue['label_key']];
                }
            }
        }

        return $validation_field;
    }
}



if (!function_exists('get_app_name')) {
    function get_app_name($where = '')
    {
        $CI = &get_instance();
        $session_data = $CI->session->userdata('admin_user');
        $company_id = isset($session_data['com_id']) ? $session_data['com_id'] : '';

        if ($company_id > 1) {
            $where = " AND config_key='app_name'";
            $qry = "SELECT id,config_key,config_value from app_settings WHERE status IN(1,2) " . $where;
            $qry_exe = $CI->db->query($qry);
            $result = $qry_exe->row_array();

            $app_name = isset($result['config_value']) && $result['config_value'] != '' ? $result['config_value'] : 'TRACKMATE';
        } else {
            $app_name = 'TRACKMATE';
        }

        return $app_name;
    }
}
if (!function_exists('get_app_setting')) {
    function get_app_setting($where = '')
    {
        $CI = &get_instance();
        $final_data = array();
        $sessiondata = $CI->session->userdata('admin_user');

        if (isset($sessiondata['is_restrict']) && $sessiondata['is_restrict'] != 1) {

            $qry = "SELECT id,config_key,config_value from app_settings WHERE status IN(1,2) " . $where;
            $qry_exe = $CI->db->query($qry);
            $result = $qry_exe->result_array();
            if (isset($result) && is_array($result) && count($result) > 0) {
                foreach ($result as $key => $value) {
                    $final_data[$value['config_key']] = $value['config_value'];
                }
            }


            if (isset($final_data['rename_ledger_to_summary']) && $final_data['rename_ledger_to_summary'] == 1) {
                $ledger_label = "SUMMARY";
            } else {
                $ledger_label = "LEDGER";
            }
            $_SESSION['ledger_label'] = $ledger_label;
        }
        return $final_data;
    }
}

if (!function_exists('get_format_date')) {
    function get_format_date($date_format = '', $date_input = '')
    {

        $formatted_date = '';
        $CI = &get_instance();
        if (
            $date_input != '' && $date_input != '1970-01-01' && $date_input != '0000-00-00'
            && $date_input != '0000-00-00 00:00:00'  && $date_input != '1970-01-01 05:30:00'
        ) {
            $formatted_date = date($date_format, strtotime($date_input));
        }

        if ($formatted_date == INFINITE_DATE) {
            $formatted_date = '';
        }
        return $formatted_date;
    }
}

function hoursandmins($time, $format = '%02d:%02d')
{
    if ($time < 1) {
        return;
    }
    $hours = floor($time / 60);
    $minutes = ($time % 60);
    return sprintf($format, $hours, $minutes);
}



if (!function_exists('get_all_notification_user')) {
    function get_all_notification_user($where = '', $is_restrict = '')
    {
        $result = array();
        $CI = &get_instance();
        $main_db = $CI->load->database('main_db', true);

        $qry = "SELECT id,name FROM admin_user WHERE status IN(1,2) " . $where . " ORDER BY name";
        $qry_exe = $main_db->query($qry);
        $com_res = $qry_exe->result_array();
        if (isset($com_res) && is_array($com_res) && count($com_res) > 0) {
            foreach ($com_res as $key => $value) {
                $result[$value['id']] = $value;
            }
        }
        return $result;
    }
}
if (!function_exists('get_random_str')) {
    function get_random_str(
        int $length = 64,
        string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
    ) {
        if ($length < 1) {
            throw new \RangeException("Length must be a positive integer");
        }
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces[] = $keyspace[random_int(0, $max)];
        }
        return implode('', $pieces);
    }
}

function isValidEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function escape_string($str = '')
{
    $CI = &get_instance();
    // $escaped_str = $CI->db->escape_like_str($str);
    $escaped_str = str_replace(array('\'', '"'), '', $str);
    return $escaped_str;
}
function get_customer_credit_limit($customer_id = 0)
{
    $CI = &get_instance();
    $customer_credit_limit = 0;
    $qry = "SELECT c.name,c.code,c.id,c.credit_limit FROM customer c 
    WHERE c.status IN(1,2)  AND c.id='" . $customer_id . "'";
    $qry_exe = $CI->db->query($qry);
    $customer_data = $qry_exe->row_array();
    $customer_credit_limit = isset($customer_data['credit_limit']) ? $customer_data['credit_limit'] : 0;
    return $customer_credit_limit;
}

function create_year_dir($file_directory = '', $file_directory2 = '')
{

    if (isset($_SESSION['admin_user']['com_id']) && $_SESSION['admin_user']['com_id'] != '' && $_SESSION['admin_user']['com_id'] > 0) {
        $company_dir = "company_" . $_SESSION['admin_user']['com_id'];
    } else if (isset($_GET['cron_company']) && $_GET['cron_company'] != '' && $_GET['cron_company'] > 0) {
        $company_dir = "company_" . $_GET['cron_company'];
    }

    if (file_exists('client_media') == false) {
        mkdir('client_media', 0777);
    }

    if (isset($company_dir)) {
        if (file_exists('client_media/' . $company_dir) == false) {
            mkdir('client_media/' . $company_dir, 0777);
        }

        $next_dir = 'client_media/' . $company_dir . '/';
    } else {
        $next_dir = 'client_media/';
    }

    if ($file_directory != '') {
        $root_direc1 = $next_dir . $file_directory . '/';
        if (file_exists($root_direc1) == false) {
            mkdir($root_direc1, 0777);
        }
    } else {
        $root_direc1 = $next_dir . '/';
    }


    if ($file_directory2 != '') {
        $root_direc = $root_direc1 . $file_directory2;
        if (file_exists($root_direc) == false) {
            mkdir($root_direc, 0777);
        }
    } else {
        $root_direc = $root_direc1;
    }

    if ($file_directory2 == '') {
        /**
         * create year month wise directory
         */
        $year = date("Y");
        $month = date("m");
        $filename = $root_direc . '/' . $year;
        $filename2 = $root_direc . '/' . $year . '/' . $month;

        if (file_exists($filename)) {
            if (file_exists($filename2) == false) {
                mkdir($filename2, 0777);
            }
        } else {
            mkdir($filename, 0777);
        }
        if (file_exists($filename)) {
            if (file_exists($filename2) == false) {
                mkdir($filename2, 0777);
            }
        } else {
            mkdir($filename, 0777);
        }
    } else {
        $filename2 = $root_direc;
    }
    return $filename2;
}


function get_header_data()
{
    $header_res = array();
    $headers = getallheaders();
    if (isset($headers) && is_array($headers) && count($headers) > 0) {
        foreach ($headers as $key => $value) {
            $header_res[strtolower($key)] = $value;
        }
    }

    return $header_res;
}

function get_company_powered_by()
{
    $powered_desc = 1;
    $com_id =  isset($_SESSION['admin_user']['com_id']) ? $_SESSION['admin_user']['com_id'] : 0;
    if ($com_id > 0) {
        $CI = &get_instance();
        $main_db = $CI->load->database('main_db', true);

        $qry = "SELECT id,powered_by_desc,show_powered_by FROM company WHERE status IN(1,2) AND id='" . $com_id . "'";
        $qry_exe = $main_db->query($qry);
        $com_res = $qry_exe->row_array();
        $powered_desc = isset($com_res['show_powered_by']) ? $com_res['show_powered_by'] : '';
    }

    return $powered_desc;
}



function round_amount($amount = 0)
{
    $format_no = round($amount, 2);
    $format_no = floatval($format_no);
    return $format_no;
}

