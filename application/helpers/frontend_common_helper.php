<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
global $all_hub_node;



if (!function_exists('active')) {
    function active($uri, $level = 1, $class_suffix = 'active')
    {
        $ci = &get_instance();

        if ($ci->uri->segment($level) == $uri) {
            return $class_suffix;
        } else {
            return;
        }
    }
}



if (!function_exists('get_all_module')) {
    function get_all_module($where = '', $is_restrict = '')
    {
        $result = array();
        $CI = &get_instance();
        $main_db = $CI->load->database('main_db', true);
        $qry = "SELECT id,name FROM `module` WHERE status IN(1,2) " . $where . " ORDER BY id";
        $qry_exe = $main_db->query($qry);;
        $per_res = $qry_exe->result_array();
        if (isset($per_res) && is_array($per_res) && count($per_res) > 0) {

            foreach ($per_res as $key => $value) {
                $result[$value['id']] = $value;
            }
        }
        return $result;
    }
}

if (!function_exists('get_all_special_permission')) {
    function get_all_special_permission($where = '', $is_restrict = '')
    {
        $result = array();
        $CI = &get_instance();
        $main_db = $CI->load->database('main_db', true);
        $qry = "SELECT id,module_id,config_key,name,permission_type FROM `permission` WHERE status IN(1,2) AND permission_type = 'Special'" . $where . " ORDER BY id";
        $qry_exe = $main_db->query($qry);;
        $per_res = $qry_exe->result_array();
        if (isset($per_res) && is_array($per_res) && count($per_res) > 0) {

            foreach ($per_res as $key => $value) {
                $result[$value['id']] = $value;
            }
        }
        return $result;
    }
}

if (!function_exists('get_all_fixed_permission')) {
    function get_all_fixed_permission($where = '', $is_restrict = '')
    {
        $result = array();
        $CI = &get_instance();
        $main_db = $CI->load->database('main_db', true);
        $qry = "SELECT id,module_id,config_key,name,permission_type FROM `permission` WHERE status IN(1,2) AND permission_type = 'Fixed'" . $where . " ORDER BY id";
        $qry_exe = $main_db->query($qry);;
        $per_res = $qry_exe->result_array();
        if (isset($per_res) && is_array($per_res) && count($per_res) > 0) {

            foreach ($per_res as $key => $value) {
                $result[$value['id']] = $value;
            }
        }
        return $result;
    }
}

if (!function_exists('get_all_company')) {
    function get_all_company($where = '', $is_restrict = '', $select = '')
    {
        $result = array();
        $CI = &get_instance();
        $main_db = $CI->load->database('main_db', true);

        if ($select == '') {
            $sel_col = 'id,company_name,expiry_date,sef_url,company_domain';
        } else {
            $sel_col = $select;
        }
        $qry = "SELECT  " . $sel_col . " FROM company WHERE status IN(1,2) " . $where . " ORDER BY company_name";
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


if (!function_exists('get_all_user')) {
    function get_all_user($where = '', $is_restrict = '', $result_key = 'id')
    {
        $result = array();
        $CI = &get_instance();
        $main_db = $CI->load->database('main_db', true);

        $qry = "SELECT id,name,contactno,email,migration_id,role,profile_file FROM admin_user WHERE status IN(1,2) " . $where . " ORDER BY name";
        $qry_exe = $main_db->query($qry);
        $com_res = $qry_exe->result_array();
        if (isset($com_res) && is_array($com_res) && count($com_res) > 0) {
            foreach ($com_res as $key => $value) {
                $result[strtolower(trim($value[$result_key]))] = $value;
            }
        }
        return $result;
    }
}

if (!function_exists('get_all_itd_admin')) {
    function get_all_itd_admin($where = '', $is_restrict = '')
    {
        $result = array();
        $CI = &get_instance();
        $main_db = $CI->load->database('main_db', true);

        $qry = "SELECT id,email_id FROM itd_admin_email WHERE status IN(1,2) " . $where;
        $qry_exe = $main_db->query($qry);
        $com_res = $qry_exe->result_array();
        if (isset($com_res) && is_array($com_res) && count($com_res) > 0) {
            foreach ($com_res as $key => $value) {
                $result[$value['id']] = strtolower($value['email_id']);
            }
        }
        return $result;
    }
}


if (!function_exists('check_db_table')) {
    function check_db_table($table_name = '')
    {
        $CI = &get_instance();
        if ($table_name != '') {
            $table_name = $table_name . "_table_qry";
            // check table creation function exist or not
            if (function_exists($table_name)) {
                $qry =  $table_name();
                $CI->db->query($qry);
            }
        }
    }
}

if (!function_exists('random_strings')) {
    function random_strings($company_name, $length_of_string = 3)
    {
        // String of all alphanumeric character
        $str_result = $company_name;

        // Shufle the $str_result and returns substring
        // of specified length
        $code =  substr(
            str_shuffle($str_result),
            0,
            $length_of_string
        );
        $code = $code . rand(10, 99);
        return strtoupper($code);
    }
}


if (!function_exists('get_all_module_field')) {
    function get_all_module_field($where = '', $order_by = '')
    {
        $result = array();
        $CI = &get_instance();
        $main_db = $CI->load->database('main_db', true);
        $qry = "SELECT * FROM module_field WHERE status IN(1,2) " . $where . " " . $order_by;
        $qry_exe = $main_db->query($qry);
        $role_data = $qry_exe->result_array();

        if (isset($role_data) && is_array($role_data) && count($role_data) > 0) {
            foreach ($role_data as $key => $value) {
                $result[$value['id']] = $value;
            }
        }
        return $result;
    }
}


/*
 * function used to format date 
 */
if (!function_exists('format_date')) {

    function format_date($dateinput = '')
    {
        $ymd = '';

        if ($dateinput != '' && strpos($dateinput, '/') !== false) {
            $date_arr = explode("/", $dateinput);
            if (isset($date_arr[2]) && strlen(trim($date_arr[2])) == 2) {

                if ($date_arr[1] <= 12) {
                    $format_date = DateTime::createFromFormat('d/m/y', $dateinput);
                } else {
                    $format_date = DateTime::createFromFormat('m/d/y', $dateinput);
                }
            } else {
                if ($date_arr[1] <= 12) {
                    $format_date = DateTime::createFromFormat('d/m/Y', $dateinput);
                } else {
                    $format_date = DateTime::createFromFormat('m/d/Y', $dateinput);
                }
            }
            if ($format_date !== false) {
                $ymd = $format_date->format('Y-m-d');
            }
        } else if ($dateinput != '' && strpos($dateinput, '-') !== false) {
            $date_arr = explode("-", $dateinput);

            /*
             * For Year
             */
            if (isset($date_arr[2]) && strlen(trim($date_arr[2])) == 2) {

                if ($date_arr[1] <= 12) {
                    $format_date = DateTime::createFromFormat('d-m-y', $dateinput);
                } else {
                    $format_date = DateTime::createFromFormat('m-d-y', $dateinput);
                }
            } else {
                if ($date_arr[1] <= 12) {
                    $format_date = DateTime::createFromFormat('d-m-Y', $dateinput);
                } else {
                    $format_date = DateTime::createFromFormat('m-d-Y', $dateinput);
                }
            }

            if ($format_date !== false) {
                $ymd = $format_date->format('Y-m-d');
            }
        }

        if ($ymd == '') {
            $ymd = date('Y-m-d', strtotime($dateinput));
        }


        return $ymd;
    }

}

if (!function_exists('get_all_config')) {
    function get_all_config($where = '')
    {
        $result = array();
        $CI = &get_instance();
        $qry = "SELECT id,config_key,config_value FROM setting_data WHERE status IN(1,2) " . $where;

        $qry_exe = $CI->db->query($qry);
        $com_res = $qry_exe->result_array();
        if (isset($com_res) && is_array($com_res) && count($com_res) > 0) {
            foreach ($com_res as $key => $value) {
                $result[$value['config_key']] = $value;
            }
        }
        return $result;
    }
}

if (!function_exists('get_last_range')) {
    function get_last_range($master = '', $column_name = 'code', $where = "")
    {
        $max_range = 0;
        $CI = &get_instance();
        $auto_module = array(
            'credit_debit_note', 'payment_receipt', 'purchase_payment_receipt', 'docket',
            'pickup_request', 'manifest', 'purchase_credit_debit_note', 'docket_extra_field', 'ticket', 'inventory',
            'pick_up_sheets'
            // 'consignee', 'shipper'
        );
        // if (in_array($master, $auto_module)) {
        //     $qry = "SELECT MAX(cast(" . $column_name . " as unsigned)) as max_code FROM " . $master;
        // } else {
        //     $qry = "SELECT MAX(cast(code as unsigned)) as max_code FROM " . $master;
        // }


        if ($column_name == 'entry_number') {
            //ENTRY NUMBER WILL ALWAYS BE NUMBER - NO NEED TO REPLACE CHARACTER AS IT QUERY TAKE TOO MUCH TIME
            $qry = "SELECT MAX(entry_number) as max_code FROM `" . $master . "` WHERE id>0 " . $where;
        } else {
            if (in_array($master, $auto_module)) {
                $qry = "SELECT MAX(CAST(REGEXP_REPLACE(`" . $column_name . "`, '[^0-9]', '') as unsigned)) as max_code FROM `" . $master . "` WHERE id>0 " . $where;
                // $qry = "SELECT MAX(CAST(replace(reverse(FORMAT(reverse(" . $column_name . "), 0)), ',', '') as unsigned)) as max_code FROM " . $master;
            } else {
                $qry = "SELECT MAX(CAST(REGEXP_REPLACE(`code`, '[^0-9]', '') as unsigned)) as max_code FROM `" . $master . "` WHERE id>0 " . $where;
                //$qry = "SELECT MAX(CAST(replace(reverse(FORMAT(reverse(code), 0)), ',', '') as unsigned)) as max_code FROM " . $master;
            }
        }

        $qry .= " AND status IN(1,2)";
        $qry_exe = $CI->db->query($qry);
        $result = $qry_exe->row_array();
        $max_range = isset($result['max_code']) ? $result['max_code'] : 0;
        return $max_range;
    }
}

if (!function_exists('auto_generate_awb_no')) {
    function auto_generate_awb_no($docket_id = '', $auto_awb_data = array(), $default_type = '', $docket_form_data = array())
    {
        $result_data = array();
        $CI = &get_instance();

        $config = get_all_config(" AND config_key LIKE '%docket_%'");

        if ($default_type != '') {
            $config['docket_format_type']['config_value'] = $default_type;
        }
        if ($config['docket_format_type']['config_value'] == 1) {
            //RANGE WSIE DOCKET
            // $last_no =  get_last_range('docket', 'auto_generate_no', " AND auto_generate_type=1");

            $qry = "SELECT MAX(CAST(REGEXP_REPLACE(`awb_no`, '[^0-9]', '') as unsigned)) as max_code 
            FROM `docket` WHERE auto_generate_type IN(0,1) AND is_edited_awb_no!=1";
            $qry_exe = $CI->db->query($qry);
            $result = $qry_exe->row_array();
            $last_no = isset($result['max_code']) ? $result['max_code'] : 0;
            $next_code = $last_no + 1;

            if ($last_no == 0) {
                $next_code = $config['docket_range_start']['config_value'];

                $next_no_avail = false;
                while ($next_no_avail == false) {
                    $query = "SELECT id FROM docket WHERE awb_no='" . $next_code . "'";
                    $query_exe = $CI->db->query($query);
                    $codeExist = $query_exe->row_array();

                    if (isset($codeExist) && is_array($codeExist) && count($codeExist) > 0) {
                        $next_code  = $next_code + 1;
                    } else {
                        $next_no_avail = true;
                    }
                }


                $result_data = array(
                    'status' => 'success',
                    'code' => $next_code
                );
            } else if ($next_code < $config['docket_range_start']['config_value']) {
                $next_code = $config['docket_range_start']['config_value'];

                $next_no_avail = false;
                while ($next_no_avail == false) {
                    $query = "SELECT id FROM docket WHERE awb_no='" . $next_code . "'";
                    $query_exe = $CI->db->query($query);
                    $codeExist = $query_exe->row_array();

                    if (isset($codeExist) && is_array($codeExist) && count($codeExist) > 0) {
                        $next_code  = $next_code + 1;
                    } else {
                        $next_no_avail = true;
                    }
                }

                $result_data = array(
                    'status' => 'success',
                    'code' => $next_code
                );
            } else if ($next_code > $config['docket_range_end']['config_value']) {
                $result_data = array(
                    'status' => 'error',
                    'code' => 'AWB NO. range is upto ' . $config['docket_range_end']['config_value'] . '. Increase range in setting'
                );
            } else {
                $next_code = $last_no + 1;

                $next_no_avail = false;
                while ($next_no_avail == false) {
                    $query = "SELECT id FROM docket WHERE awb_no='" . $next_code . "'";
                    $query_exe = $CI->db->query($query);
                    $codeExist = $query_exe->row_array();

                    if (isset($codeExist) && is_array($codeExist) && count($codeExist) > 0) {
                        $next_code  = $next_code + 1;
                    } else {
                        $next_no_avail = true;
                    }
                }

                $result_data = array(
                    'status' => 'success',
                    'code' => $next_code
                );
            }
            $result_data['auto_generate_type'] = 1;
            $result_data['auto_generate_no'] = $next_code;
        } else if ($config['docket_format_type']['config_value'] == 2) {
            //CUSTOM CODE AND DATE WSIE AWB NO.

            if (isset($docket_form_data['docket']['customer_id'])) {
                $qry = "SELECT c.id as customer_id,c.code FROM customer c 
                    WHERE c.status IN(1,2) AND c.id='" . $docket_form_data['docket']['customer_id'] . "'";
            } else if (isset($docket_form_data['customer_id'])) {
                $qry = "SELECT c.id as customer_id,c.code FROM customer c 
                    WHERE c.status IN(1,2) AND c.id='" . $docket_form_data['customer_id'] . "'";
            } else {
                $qry = "SELECT d.id,d.customer_id,c.code FROM docket d
                    JOIN customer c ON(c.id=d.customer_id)
                    WHERE d.status IN(1,2) AND c.status IN(1,2) AND d.id='" . $docket_id . "'";
            }
            $qry_exe = $CI->db->query($qry);
            $cust_data = $qry_exe->row_array();
            if (isset($cust_data) && is_array($cust_data) && count($cust_data) > 0) {
                // check docket count of today for this customer
                // $qry = "SELECT count(id) as doc_cnt FROM docket WHERE 
                //     DATE_FORMAT(created_date, '%Y-%m-%d') = '" . date('Y-m-d') . "' AND 
                //     customer_id='" . $cust_data['customer_id'] . "' AND status IN(1,2)";
                // $qry_exe = $CI->db->query($qry);
                // $count_data = $qry_exe->row_array();

                $qry = "SELECT awb_no FROM docket WHERE 
                awb_no LIKE '%" . $cust_data['code'] . get_format_date("dmy", $docket_form_data['docket']['booking_date']) . "%'";
                $qry_exe = $CI->db->query($qry);
                $count_data = $qry_exe->result_array();

                if (isset($count_data) && is_array($count_data) && count($count_data) > 0) {
                    foreach ($count_data as $ckey => $cvalue) {
                        $replace_str = $cust_data['code'] . get_format_date("dmy", $docket_form_data['docket']['booking_date']);
                        $awb_no = str_ireplace($replace_str, '', $cvalue['awb_no']);
                        if (is_numeric($awb_no)) {
                            $awb_no_arr[$awb_no] = $awb_no;
                        }
                    }

                    if (isset($awb_no_arr) && is_array($awb_no_arr) && count($awb_no_arr) > 0) {
                        $next_cnt = max($awb_no_arr) + 1;
                    } else {
                        $next_cnt = 1;
                    }
                } else {
                    $next_cnt = 1;
                }

                $next_code = $cust_data['code'] . get_format_date("dmy", $docket_form_data['docket']['booking_date']) . sprintf("%02d", $next_cnt);;
                $result_data = array(
                    'status' => 'success',
                    'code' => $next_code
                );
            } else {
                $result_data = array(
                    'status' => 'error',
                    'code' => 'UNABLE TO GENERATE AWB NO BECAUSE CUSTOMER NOT FOUND'
                );
            }
            $result_data['auto_generate_type'] = 2;
        } else if ($config['docket_format_type']['config_value'] == 3) {
            //CUSTOM PREFIX AND DATE WSIE AWB NO.
            if (isset($docket_form_data['docket']['customer_id'])) {
                $qry = "SELECT c.id as customer_id,c.awb_no_prefix,c.is_awb_auto_generate FROM customer c 
                    WHERE c.status IN(1,2) AND c.id='" . $docket_form_data['docket']['customer_id'] . "'";
            } else if (isset($docket_form_data['customer_id'])) {
                $qry = "SELECT c.id as customer_id,c.code FROM customer c 
                    WHERE c.status IN(1,2) AND c.id='" . $docket_form_data['customer_id'] . "'";
            } else {
                $qry = "SELECT d.id,d.customer_id,c.awb_no_prefix,c.is_awb_auto_generate FROM docket d
                    JOIN customer c ON(c.id=d.customer_id)
                    WHERE d.status IN(1,2) AND c.status IN(1,2) AND d.id='" . $docket_id . "'";
            }
            $qry_exe = $CI->db->query($qry);
            $cust_data = $qry_exe->row_array();
            if (isset($cust_data) && is_array($cust_data) && count($cust_data) > 0) {

                if ($cust_data['is_awb_auto_generate'] != 1 || $cust_data['awb_no_prefix'] == '') {
                    $result_data = array(
                        'status' => 'error',
                        'code' => 'SET AWB NO. AUTO GENERATION SETTING IN CUSTOMER'
                    );
                } else {
                    // check docket count of today for this customer

                    $qry = "SELECT awb_no FROM docket WHERE 
                awb_no LIKE '%" . $cust_data['awb_no_prefix'] . date('dmy') . "%'";
                    $qry_exe = $CI->db->query($qry);
                    $count_data = $qry_exe->result_array();

                    if (isset($count_data) && is_array($count_data) && count($count_data) > 0) {
                        foreach ($count_data as $ckey => $cvalue) {
                            $replace_str = $cust_data['awb_no_prefix'] . date('dmy');
                            $awb_no = str_ireplace($replace_str, '', $cvalue['awb_no']);
                            if (is_numeric($awb_no)) {
                                $awb_no_arr[$awb_no] = $awb_no;
                            }
                        }

                        if (isset($awb_no_arr) && is_array($awb_no_arr) && count($awb_no_arr) > 0) {
                            $next_cnt = max($awb_no_arr) + 1;
                        } else {
                            $next_cnt = 1;
                        }
                    } else {
                        $next_cnt = 1;
                    }

                    //                     $qry = "SELECT count(id) as doc_cnt FROM docket WHERE 
                    // DATE_FORMAT(created_date, '%Y-%m-%d') = '" . date('Y-m-d') . "' AND 
                    // customer_id='" . $cust_data['customer_id'] . "' AND status IN(1,2)";
                    //                     $qry_exe = $CI->db->query($qry);
                    //                     $count_data = $qry_exe->row_array();
                    //                     if (isset($count_data) && is_array($count_data) && count($count_data) > 0) {
                    //                         $next_cnt = $count_data['doc_cnt'] + 1;
                    //                     } else {
                    //                         $next_cnt = 1;
                    //                     }

                    $next_code = $cust_data['awb_no_prefix'] . date('dmy') . sprintf("%02d", $next_cnt);;
                    $result_data = array(
                        'status' => 'success',
                        'code' => $next_code
                    );
                }
            } else {
                $result_data = array(
                    'status' => 'error',
                    'code' => 'UNABLE TO GENERATE AWB NO BECAUSE CUSTOMER NOT FOUND'
                );
            }
            $result_data['auto_generate_type'] = 3;
        } else if ($config['docket_format_type']['config_value'] == 4) {
            //CUSTOM CODE AND SERVICE WSIE AWB NO.
            if (isset($docket_form_data['docket']['customer_id'])) {
                $qry = "SELECT c.id as customer_id,c.code FROM customer c 
                    WHERE c.status IN(1,2) AND c.id='" . $docket_form_data['docket']['customer_id'] . "'";
            } else if (isset($docket_form_data['customer_id'])) {
                $qry = "SELECT c.id as customer_id,c.code FROM customer c 
                    WHERE c.status IN(1,2) AND c.id='" . $docket_form_data['customer_id'] . "'";
            } else {
                $qry = "SELECT d.id,d.customer_id,c.code FROM docket d
                    JOIN customer c ON(c.id=d.customer_id)
                    WHERE d.status IN(1,2) AND c.status IN(1,2) AND d.id='" . $docket_id . "'";
            }

            $qry_exe = $CI->db->query($qry);
            $cust_data = $qry_exe->row_array();

            if (isset($docket_form_data['docket']['customer_id'])) {
                $qry = "SELECT c.id as vendor_id,c.code,c.service_code FROM vendor c 
                    WHERE c.status IN(1,2) AND c.id='" . $docket_form_data['docket']['vendor_id'] . "'";
            }
            if (isset($docket_form_data['customer_id'])) {
                $qry = "SELECT c.id as vendor_id,c.code,c.service_code FROM vendor c 
                    WHERE c.status IN(1,2) AND c.id='" . $docket_form_data['vendor_id'] . "'";
            } else {
                $qry = "SELECT d.id,d.vendor_id,c.code,c.service_code FROM docket d
                 JOIN vendor c ON(c.id=d.vendor_id)
                 WHERE d.status IN(1,2) AND c.status IN(1,2) AND d.id='" . $docket_id . "'";
            }


            $qry_exe = $CI->db->query($qry);
            $service_data = $qry_exe->row_array();

            if (isset($cust_data) && is_array($cust_data) && count($cust_data) > 0) {
                if (isset($service_data) && is_array($service_data) && count($service_data) > 0) {

                    if ($service_data['service_code'] != '') {
                        // check docket count of today for this customer
                        //                         $qry = "SELECT count(id) as doc_cnt FROM docket WHERE 
                        // DATE_FORMAT(created_date, '%Y-%m-%d') = '" . date('Y-m-d') . "' AND 
                        // customer_id='" . $cust_data['customer_id'] . "' AND vendor_id='" . $service_data['vendor_id'] . "' AND status IN(1,2)";
                        //                         $qry_exe = $CI->db->query($qry);
                        //                         $count_data = $qry_exe->row_array();
                        //                         if (isset($count_data) && is_array($count_data) && count($count_data) > 0) {
                        //                             $next_cnt = $count_data['doc_cnt'] + 1;
                        //                         } else {
                        //                             $next_cnt = 1;
                        //                         }


                        $qry = "SELECT awb_no FROM docket WHERE 
                awb_no LIKE '%" . $service_data['service_code'] . $cust_data['code'] . date('dmy') . "%' 
               AND is_edited_awb_no!=1";
                        $qry_exe = $CI->db->query($qry);
                        $count_data = $qry_exe->result_array();

                        if (isset($count_data) && is_array($count_data) && count($count_data) > 0) {
                            foreach ($count_data as $ckey => $cvalue) {
                                $replace_str = $service_data['service_code'] . $cust_data['code'] . date('dmy');
                                $awb_no = str_ireplace($replace_str, '', $cvalue['awb_no']);
                                if (is_numeric($awb_no)) {
                                    $awb_no_arr[$awb_no] = $awb_no;
                                }
                            }

                            if (isset($awb_no_arr) && is_array($awb_no_arr) && count($awb_no_arr) > 0) {
                                $next_cnt = max($awb_no_arr) + 1;
                            } else {
                                $next_cnt = 1;
                            }
                        } else {
                            $next_cnt = 1;
                        }

                        $next_no_avail = false;
                        $next_code = $service_data['service_code'] . $cust_data['code'] . date('dmy') . sprintf("%02d", $next_cnt);
                        while ($next_no_avail == false) {
                            $query = "SELECT id FROM docket WHERE awb_no='" . $next_code . "' AND status IN(1,2)";
                            $query_exe = $CI->db->query($query);
                            $codeExist = $query_exe->row_array();

                            if (isset($codeExist) && is_array($codeExist) && count($codeExist) > 0) {
                                $next_cnt  = $next_cnt + 1;
                                $next_code = $service_data['service_code'] . $cust_data['code'] . date('dmy') . sprintf("%02d", $next_cnt);
                            } else {
                                $next_no_avail = true;
                            }
                        }
                        $result_data = array(
                            'status' => 'success',
                            'code' => $next_code
                        );
                    } else {
                        $result_data = array(
                            'status' => 'error',
                            'code' => 'UNABLE TO GENERATE AWB NO - SET SERVICE CODE IN SERVICE MASTER'
                        );
                    }
                } else {
                    $result_data = array(
                        'status' => 'error',
                        'code' => 'UNABLE TO GENERATE AWB NO BECAUSE SERVICE NOT FOUND'
                    );
                }
            } else {
                $result_data = array(
                    'status' => 'error',
                    'code' => 'UNABLE TO GENERATE AWB NO BECAUSE CUSTOMER NOT FOUND'
                );
            }
            $result_data['auto_generate_type'] = 4;
        } else if ($config['docket_format_type']['config_value'] == 5) {

            $next_no_avail = false;
            $next_code = rand(100000, 999999);
            while ($next_no_avail == false) {
                $query = "SELECT id FROM docket WHERE awb_no='" . $next_code . "'";
                $query_exe = $CI->db->query($query);
                $codeExist = $query_exe->row_array();

                if (isset($codeExist) && is_array($codeExist) && count($codeExist) > 0) {
                    $next_code = rand(100000, 999999);
                } else {
                    $next_no_avail = true;
                }
            }
            $result_data = array(
                'status' => 'success',
                'code' => $next_code
            );

            $result_data['auto_generate_type'] = 5;
        } else if ($config['docket_format_type']['config_value'] == 6) {
            //PREFIX RANGE WSIE DOCKET
            // $last_no =  get_last_range('docket', 'auto_generate_no', " AND auto_generate_type=1");
            $prefix = $config['docket_prefix']["config_value"];

            // $qry = "SELECT awb_no FROM docket WHERE awb_no LIKE '%" . $prefix . "%' AND status IN(1,2)";
            $qry = "SELECT awb_no FROM docket WHERE awb_no REGEXP '^" . $prefix . "[0-9]*$' ORDER BY created_date DESC,id DESC";
            $qry_exe = $CI->db->query($qry);
            $count_data = $qry_exe->result_array();
            if (isset($count_data) && is_array($count_data) && count($count_data) > 0) {
                $replace_awb = str_ireplace($prefix, '', $count_data[0]['awb_no']);
                $last_no = (int)$replace_awb + 1;
            } else {
                $last_no = 1;
            }

            $next_code = $last_no;
            //file_put_contents(FCPATH . 'log1/awb_next.txt', $next_code, FILE_APPEND);
            if ($last_no == 0) {
                $next_code = $config['docket_prefix_range_start']['config_value'];
                $result_data = array(
                    'status' => 'success',
                    'code' => $prefix . $next_code
                );
            } else if ($next_code < $config['docket_prefix_range_start']['config_value']) {
                $next_code = $config['docket_prefix_range_start']['config_value'];
                $result_data = array(
                    'status' => 'success',
                    'code' => $prefix . $next_code
                );
            } else if ($next_code > $config['docket_prefix_range_end']['config_value']) {
                $result_data = array(
                    'status' => 'error',
                    'code' => 'AWB NO. range is upto ' . $config['docket_prefix_range_end']['config_value'] . '. Increase range in setting'
                );
            } else {
                $result_data = array(
                    'status' => 'success',
                    'code' => $prefix . $next_code
                );
            }
            $result_data['auto_generate_type'] = 6;
            $result_data['auto_generate_no'] = $next_code;
        } else if ($config['docket_format_type']['config_value'] == 7) {
            //CUSTOMER CODE & RANGE WISE
            if (isset($docket_form_data['docket']['customer_id'])) {
                $qry = "SELECT c.id as customer_id,c.code FROM customer c 
                    WHERE c.status IN(1,2) AND c.id='" . $docket_form_data['docket']['customer_id'] . "'";
            } else if (isset($docket_form_data['customer_id'])) {
                $qry = "SELECT c.id as customer_id,c.code FROM customer c 
                    WHERE c.status IN(1,2) AND c.id='" . $docket_form_data['customer_id'] . "'";
            } else {
                $qry = "SELECT d.id,d.customer_id,c.code FROM docket d
                    JOIN customer c ON(c.id=d.customer_id)
                    WHERE d.status IN(1,2) AND c.status IN(1,2) AND d.id='" . $docket_id . "'";
            }
            $qry_exe = $CI->db->query($qry);
            $cust_data = $qry_exe->row_array();
            if (isset($cust_data) && is_array($cust_data) && count($cust_data) > 0) {
                $prefix = $cust_data['code'];

                $qry = "SELECT MAX(auto_generate_no) as max_code 
            FROM `docket` WHERE auto_generate_type =7 AND status IN(1,2)";
                $qry_exe = $CI->db->query($qry);
                $result = $qry_exe->row_array();
                $last_no = isset($result['max_code']) ? $result['max_code'] : 0;
                $next_code = $last_no + 1;

                $next_no_avail = false;


                if ($last_no == 0) {
                    $next_code = $config['docket_range_start']['config_value'];

                    while ($next_no_avail == false) {
                        $awb_no = $prefix . $next_code;
                        $query = "SELECT id FROM docket WHERE awb_no='" . $awb_no . "'";
                        $query_exe = $CI->db->query($query);
                        $codeExist = $query_exe->row_array();
                        if (isset($codeExist) && is_array($codeExist) && count($codeExist) > 0) {
                            if ($next_code > $config['docket_range_end']['config_value']) {
                                $result_data = array(
                                    'status' => 'error',
                                    'code' => 'AWB NO. range is upto ' . $config['docket_range_end']['config_value'] . '. Increase range in setting'
                                );
                                $next_no_avail = true;
                            } else {
                                $next_code = $next_code + 1;
                            }
                        } else {
                            $next_no_avail = true;
                            $result_data = array(
                                'status' => 'success',
                                'code' => $prefix . $next_code
                            );
                        }
                    }
                } else if ($next_code < $config['docket_range_start']['config_value']) {
                    $next_code = $config['docket_range_start']['config_value'];


                    while ($next_no_avail == false) {
                        $awb_no = $prefix . $next_code;

                        $query = "SELECT id FROM docket WHERE awb_no='" . $awb_no . "'";
                        $query_exe = $CI->db->query($query);
                        $codeExist = $query_exe->row_array();

                        if (isset($codeExist) && is_array($codeExist) && count($codeExist) > 0) {
                            if ($next_code > $config['docket_range_end']['config_value']) {
                                $result_data = array(
                                    'status' => 'error',
                                    'code' => 'AWB NO. range is upto ' . $config['docket_range_end']['config_value'] . '. Increase range in setting'
                                );
                            } else {
                                $next_code = $next_code + 1;
                            }
                        } else {
                            $next_no_avail = true;
                            $result_data = array(
                                'status' => 'success',
                                'code' => $prefix . $next_code
                            );
                        }
                    }
                } else if ($next_code > $config['docket_range_end']['config_value']) {
                    $result_data = array(
                        'status' => 'error',
                        'code' => 'AWB NO. range is upto ' . $config['docket_range_end']['config_value'] . '. Increase range in setting'
                    );
                } else {

                    while ($next_no_avail == false) {
                        $awb_no = $prefix . $next_code;
                        $query = "SELECT id FROM docket WHERE awb_no='" . $awb_no . "'";
                        $query_exe = $CI->db->query($query);
                        $codeExist = $query_exe->row_array();
                        if (isset($codeExist) && is_array($codeExist) && count($codeExist) > 0) {
                            if ($next_code > $config['docket_range_end']['config_value']) {
                                $result_data = array(
                                    'status' => 'error',
                                    'code' => 'AWB NO. range is upto ' . $config['docket_range_end']['config_value'] . '. Increase range in setting'
                                );
                                $next_no_avail = true;
                            } else {
                                $next_code = $next_code + 1;
                            }
                        } else {
                            $next_no_avail = true;
                            $result_data = array(
                                'status' => 'success',
                                'code' => $prefix . $next_code
                            );
                        }
                    }
                }



                $result_data['auto_generate_type'] = 7;
                $result_data['auto_generate_no'] = $next_code;
            } else {
                $result_data = array(
                    'status' => 'error',
                    'code' => 'UNABLE TO GENERATE AWB NO BECAUSE CUSTOMER NOT FOUND'
                );
            }
        }




        if (isset($auto_awb_data) && is_array($auto_awb_data) && count($auto_awb_data) > 0) {
            $config = get_all_config(" AND config_key LIKE '%docket_%'");
            if ($config['docket_format_type']['config_value'] == 1) {
                //RANGE WSIE DOCKET
                $last_no =  get_last_range('docket', 'awb_no');
                $next_code = $last_no + 1;
                if ($last_no == 0) {
                    $next_code = $config['docket_range_start']['config_value'];
                    $result_data = array(
                        'status' => 'success',
                        'code' => $next_code
                    );
                } else if ($next_code < $config['docket_range_start']['config_value']) {
                    $next_code = $config['docket_range_start']['config_value'];
                    $result_data = array(
                        'status' => 'success',
                        'code' => $next_code
                    );
                } else if ($next_code > $config['docket_range_end']['config_value']) {
                    $result_data = array(
                        'status' => 'error',
                        'code' => 'AWB NO. range is upto ' . $config['docket_range_end']['config_value'] . '. Increase range in setting'
                    );
                } else {
                    $next_code = $last_no + 1;
                    $result_data = array(
                        'status' => 'success',
                        'code' => $next_code
                    );
                }
            } else if ($config['docket_format_type']['config_value'] == 2) {

                if (isset($auto_awb_data['customer_id']) && $auto_awb_data['customer_id'] > 0) {
                    // check docket count of today for this customer
                    // $qry = "SELECT count(id) as doc_cnt FROM docket WHERE 
                    // DATE_FORMAT(created_date, '%Y-%m-%d') = '" . date('Y-m-d') . "' AND 
                    // customer_id='" . $auto_awb_data['customer_id'] . "'";
                    // $qry_exe = $CI->db->query($qry);
                    // $count_data = $qry_exe->row_array();
                    // if (isset($count_data) && is_array($count_data) && count($count_data) > 0) {
                    //     $next_cnt = $count_data['doc_cnt'] + 1;
                    // } else {
                    //     $next_cnt = 1;
                    // }


                    $qry = "SELECT awb_no FROM docket WHERE 
                awb_no LIKE '%" . $auto_awb_data['cust_code'] . date('dmy') . "%'";
                    $qry_exe = $CI->db->query($qry);
                    $count_data = $qry_exe->result_array();

                    if (isset($count_data) && is_array($count_data) && count($count_data) > 0) {
                        foreach ($count_data as $ckey => $cvalue) {
                            $replace_str = $auto_awb_data['cust_code'] . date('dmy');
                            $awb_no = str_ireplace($replace_str, '', $cvalue['awb_no']);
                            if (is_numeric($awb_no)) {
                                $awb_no_arr[$awb_no] = $awb_no;
                            }
                        }

                        if (isset($awb_no_arr) && is_array($awb_no_arr) && count($awb_no_arr) > 0) {
                            $next_cnt = max($awb_no_arr) + 1;
                        } else {
                            $next_cnt = 1;
                        }
                    } else {
                        $next_cnt = 1;
                    }


                    $next_code = $auto_awb_data['cust_code'] . date('dmy') . sprintf("%02d", $next_cnt);
                    $result_data = array(
                        'status' => 'success',
                        'code' => $next_code
                    );
                } else {
                    $result_data = array(
                        'status' => 'error',
                        'code' => 'CUSTOMER NOT FOUND'
                    );
                }
            }
        }
        return $result_data;
    }
}


if (!function_exists('get_validation_field')) {
    function get_validation_field($module_id = 0, $array_key = 'label_key', $validation_type = 1, $validation_user = 1)
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
            $edit_data = $CI->gm->get_data_list('custom_validation_field', array('module_id' => $module_id, 'status' => 1, 'validation_type' => $validation_type, 'validation_user' => 2), array(), array(), 'id,label_key');
        } else {
            $edit_data = $CI->gm->get_data_list('custom_validation_field', array('module_id' => $module_id, 'status' => 1, 'validation_type' => $validation_type, 'validation_user' => $validation_user), array(), array(), 'id,label_key');
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


if (!function_exists('get_all_app_setting')) {
    function get_all_app_setting($where = '')
    {
        $result = array();
        $CI = &get_instance();
        $qry = "SELECT id,module_name,config_key,config_value FROM app_settings WHERE status IN(1,2) " . $where;

        $qry_exe = $CI->db->query($qry);
        $com_res = $qry_exe->result_array();
        if (isset($com_res) && is_array($com_res) && count($com_res) > 0) {
            foreach ($com_res as $key => $value) {
                $result[$value['config_key']] = $value['config_value'];
            }
        }

        return $result;
    }
}


if (!function_exists('create_year_month_dir')) {
    function create_year_month_dir($file_directory = '')
    {
        $root_direc = 'client_media/' . $file_directory;

        if (file_exists('client_media') == false) {
            mkdir('client_media', 0777);
        }

        if (file_exists($root_direc) == false) {
            mkdir($root_direc, 0777);
        }

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
        return $filename2;
    }
}


