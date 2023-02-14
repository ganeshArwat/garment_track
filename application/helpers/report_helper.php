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

if (!function_exists('get_custom_report_list')) {
    function get_custom_report_list($where = '')
    {
        $CI = &get_instance();
        $final_data = array();
        $qry = "SELECT id,name,role_id,docket_setting_data from custom_report WHERE status IN(1,2) " . $where;
        $qry_exe = $CI->db->query($qry);
        $result = $qry_exe->result_array();
        if (isset($result) && is_array($result) && count($result) > 0) {
            foreach ($result as $key => $value) {
                $final_data[$value['id']] = $value;
            }
        }
        return $final_data;
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


if (!function_exists('get_user_notification')) {
    function get_user_notification($where = '')
    {
        $CI = &get_instance();
        $final_data = array();
        $sessiondata = $CI->session->userdata('admin_user');
        $user_id = $sessiondata['id'];
        $qry = "SELECT module_id,created_date FROM notification WHERE status IN(1,2) AND seen_status=1 AND module_type=1 
        AND notification_user_id='" . $user_id . "' ORDER BY created_date DESC LIMIT 50";

        $qry_exe = $CI->db->query($qry);
        $result = $qry_exe->result_array();
        if (isset($result) && is_array($result) && count($result) > 0) {
            foreach ($result as $key => $value) {
                $task_id_arr[$value['module_id']] = $value['module_id'];
            }
        }

        if (isset($task_id_arr) && is_array($task_id_arr) && count($task_id_arr) > 0) {
            $qry = "SELECT id,title,created_by FROM task WHERE status IN(1,2) AND id IN(" . implode(",", $task_id_arr) . ")";
            $qry_exe = $CI->db->query($qry);
            $task_result = $qry_exe->result_array();
        }

        $data['all_user'] = get_all_notification_user();

        if (isset($task_result) && is_array($task_result) && count($task_result) > 0) {
            foreach ($task_result as $key => $value) {
                $data['task_data'][$value['id']] = $value;
            }
        }
        $data['notification'] =  $result;
        return $data;
    }
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

function get_customer_ledger($customer_id = 0)
{
    $CI = &get_instance();
    $customer_balance = 0;
    $qry = "SELECT c.name,c.code,c.id FROM customer c JOIN ledger_outstanding_item l ON(c.id=l.customer_id)
    WHERE c.status IN(1,2) AND l.status IN(1,2) AND l.particular!='round_off' AND c.id='" . $customer_id . "' GROUP BY c.id ";
    $qry_exe = $CI->db->query($qry);
    $data['customer_data'] = $qry_exe->result_array();

    if (isset($data['customer_data']) && is_array($data['customer_data']) && count($data['customer_data']) > 0) {

        foreach ($data['customer_data'] as $ckey => $cvalue) {
            $cust_id_arr[$cvalue['id']] = $cvalue['id'];
        }
        $appendquery = '';
        $data['setting'] = get_all_app_setting(" AND module_name IN('account')");

        if (
            isset($data['setting']['customised_unbilled_dockets_in_leder_and_outstanding_report']) && $data['setting']['customised_unbilled_dockets_in_leder_and_outstanding_report'] == 1
            && isset($data['setting']['account_ledger_start_date']) && $data['setting']['account_ledger_start_date'] != '' && $data['setting']['account_ledger_start_date'] != '1970-01-01' && $data['setting']['account_ledger_start_date'] != '0000-00-00'
        ) {
            $appendquery = " AND l.ledger_date >='" . $data['setting']['account_ledger_start_date'] . "'";
        } else {
            $account_ledger_start_date = get_customer_opening_balance_date($customer_id);
            if ($account_ledger_start_date != '') {
                $appendquery = " AND l.ledger_date >='" . $account_ledger_start_date . "'";
            }
        }

        $qry = "SELECT id,sector_id FROM payment_receipt WHERE status IN(1,2)";
        $qry_exe = $CI->db->query($qry);
        $receipt_res = $qry_exe->result_array();
        if (isset($receipt_res) && is_array($receipt_res) && count($receipt_res) > 0) {
            foreach ($receipt_res as $rkey => $rvalue) {
                $all_receipt[$rvalue['id']] = $rvalue['sector_id'];
            }
        }
        $data['all_receipt'] = isset($all_receipt) ? $all_receipt : array();

        $qry = "SELECT id,sector_id FROM credit_debit_note WHERE status IN(1,2)";
        $qry_exe = $CI->db->query($qry);
        $note_res = $qry_exe->result_array();
        if (isset($note_res) && is_array($note_res) && count($note_res) > 0) {
            foreach ($note_res as $rkey => $rvalue) {
                $all_note[$rvalue['id']] = $rvalue['sector_id'];
            }
        }
        $data['all_note'] = isset($all_note) ? $all_note : array();

        $qry = "SELECT id,sector_id FROM opening_balance WHERE status IN(1,2)";
        $qry_exe = $CI->db->query($qry);
        $opening_res = $qry_exe->result_array();
        if (isset($opening_res) && is_array($opening_res) && count($opening_res) > 0) {
            foreach ($opening_res as $rkey => $rvalue) {
                $all_opening[$rvalue['id']] = $rvalue['sector_id'];
            }
        }
        $data['all_opening'] = isset($all_opening) ? $all_opening : array();

        //GET TOTAL CREDIT AND DEBIT AMOUNT
        $amountq = "SELECT l.amount,l.payment_type,l.payment_id,l.customer_id,l.ledger_type,di.id as invoice_id FROM ledger_item l
         LEFT OUTER JOIN docket_invoice di ON(di.id=l.payment_id AND l.payment_type=5 AND di.status IN(1,2))
        WHERE l.status IN(1,2) AND l.payment_type!=6
        AND l.customer_id IN(" . implode(",", $cust_id_arr) . ") " . $appendquery;
        $amountq_exe = $CI->db->query($amountq);
        $ledger_amount_data = $amountq_exe->result_array();

        if (isset($ledger_amount_data) && is_array($ledger_amount_data) && count($ledger_amount_data) > 0) {
            foreach ($ledger_amount_data as $akey => $avalue) {

                if ($avalue['payment_type'] == 5 && $avalue['invoice_id'] == '') {
                    unset($ledger_amount_data[$akey]);
                } else if ($avalue['payment_type'] == 1 && (!isset($all_opening[$avalue['payment_id']]))) {
                    unset($ledger_amount_data[$akey]);
                } else if ($avalue['payment_type'] == 2 && (!isset($all_receipt[$avalue['payment_id']]))) {
                    unset($ledger_amount_data[$akey]);
                } else if (($avalue['payment_type'] == 3 || $avalue['payment_type'] == 4) && (!isset($all_note[$avalue['payment_id']]))) {
                    unset($ledger_amount_data[$akey]);
                } else {
                    $ledger_amt = $avalue['amount'];

                    if ($ledger_amt < 0) {
                        $ledger_amt = $ledger_amt * (-1);
                    }

                    if (isset($amount_data[$avalue['ledger_type']][$avalue['customer_id']])) {
                        $amount_data[$avalue['ledger_type']][$avalue['customer_id']] += $ledger_amt;
                    } else {
                        $amount_data[$avalue['ledger_type']][$avalue['customer_id']] = $ledger_amt;
                    }
                }
            }
        }



        $credit_amt = isset($amount_data[1][$customer_id]) && $amount_data[1][$customer_id] > 0 ? $amount_data[1][$customer_id] : 0;
        $debit_amt = isset($amount_data[2][$customer_id]) && $amount_data[2][$customer_id] > 0 ? $amount_data[2][$customer_id] : 0;


        //GET UNBILLED AMT
        $qry = "SELECT id,module_name,config_key,config_value FROM app_settings WHERE status IN(1,2) ";
        $qry_exe = $CI->db->query($qry);
        $com_res = $qry_exe->result_array();
        if (isset($com_res) && is_array($com_res) && count($com_res) > 0) {
            foreach ($com_res as $key => $value) {
                $data['setting'][$value['config_key']] = $value['config_value'];
            }
        }

        $unbill_date_appendquery = "";



        if (
            isset($data['setting']['customised_unbilled_dockets_in_leder_and_outstanding_report']) && $data['setting']['customised_unbilled_dockets_in_leder_and_outstanding_report'] == 1
            && isset($data['setting']['account_ledger_start_date']) && $data['setting']['account_ledger_start_date'] != '' && $data['setting']['account_ledger_start_date'] != '1970-01-01' && $data['setting']['account_ledger_start_date'] != '0000-00-00'
        ) {
            $unbill_date_appendquery = " AND d.booking_date >='" . $data['setting']['account_ledger_start_date'] . "'";
        } else {
            $account_ledger_start_date = get_customer_opening_balance_date($customer_id);
            if ($account_ledger_start_date != '') {
                $unbill_date_appendquery = " AND d.booking_date >='" . $account_ledger_start_date . "'";
            }
        }
        $product_id = "";
        $product_void = get_all_product(" AND code = 'void'");
        if (isset($product_void) && is_array($product_void) && count($product_void) > 0) {
            foreach ($product_void as $key => $value) {
                $product_id = $value['id'];
            }
        }
        $appendquery  = " AND c.id = '" . $customer_id . "'";
        $unbilledq = "SELECT d.id,d.awb_no,d.booking_date,dcon.name as con_name,dshi.name as shi_name, 
        d.destination_id,d.chargeable_wt,ds.grand_total FROM `docket` d 
        JOIN customer c ON(c.id=d.customer_id)
      LEFT OUTER JOIN docket_consignee dcon ON(d.id=dcon.docket_id AND dcon.status IN(1,2))
      LEFT OUTER JOIN docket_shipper dshi ON(d.id=dshi.docket_id AND dshi.status IN(1,2)) 
      LEFT OUTER JOIN docket_sales_billing ds ON(d.id=ds.docket_id AND ds.status IN(1,2))
      WHERE d.status IN(1,2)  AND d.status_id!=3 AND d.product_id != '" . $product_id . "' AND c.status IN(1,2) " . $appendquery . $unbill_date_appendquery . " AND d.id NOT IN(SELECT docket_id FROM docket_invoice_map WHERE status IN(1,2)) ";

        $unbilledq_exe = $CI->db->query($unbilledq);
        $unbilled_data = $unbilledq_exe->result_array();
        $unbilled_amt = 0;
        if (isset($unbilled_data) && is_array($unbilled_data) && count($unbilled_data) > 0) {
            foreach ($unbilled_data as $rkey => $rvalue) {
                $unbilled_amt += $rvalue['grand_total'];
            }
        }

        $debit_amt = $debit_amt  + $unbilled_amt;
        $customer_balance = $debit_amt - $credit_amt;
    }

    return $customer_balance;
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

function add_docket_insert_history($docket_id)
{
    $CI = &get_instance();
    //LOG UPDATED DATA HISTORY
    $old_column = 'awb_no,customer_id,origin_id,destination_id,product_id,booking_date,vendor_id,'
        . 'co_vendor_id,forwarding_no,content,eway_bill,invoice_date,invoice_no,customer_contract_id,'
        . 'customer_contract_tat,cft_contract_id,remarks,status_id,actual_wt,volumetric_wt,consignor_wt,'
        . 'add_wt,chargeable_wt,total_pcs,dispatch_type,shipment_priority,company_id,
project_id,forwarding_no_2,reference_no,reference_name,shipment_value,shipment_currency_id,
dispatch_date,dispatch_time,courier_dispatch_date,courier_dispatch_time,instructions,payment_type,ori_hub_id,
dest_hub_id,challan_no,commit_id,cod_amount,insurance_amount';
    $docket_data_new = $CI->gm->get_selected_record('docket', $old_column, $where = array('id' => $docket_id, 'status !=' => 3), array());

    if (isset($docket_data_new) && is_array($docket_data_new) && count($docket_data_new) > 0) {
        foreach ($docket_data_new as $dkey => $dvalue) {
            $new_data[$dkey] = $dvalue;
        }
    }

    $shipper_consi_col = 'code,name,company_name,address1,address2,address3,pincode,city,state,country,contact_no,email_id,
    dob,gstin_type,gstin_no,doc_path';
    $shipper_insert_new = $CI->gm->get_selected_record('docket_shipper', $shipper_consi_col, array('docket_id' => $docket_id, 'status' => 1), array());

    if (isset($shipper_insert_new) && is_array($shipper_insert_new) && count($shipper_insert_new) > 0) {
        foreach ($shipper_insert_new as $dkey => $dvalue) {
            $new_data["sh." . $dkey] = $dvalue;
        }
    }

    $consignee_insert_new = $CI->gm->get_selected_record('docket_consignee', $shipper_consi_col, array('docket_id' => $docket_id, 'status' => 1), array());

    if (isset($consignee_insert_new) && is_array($consignee_insert_new) && count($consignee_insert_new) > 0) {
        foreach ($consignee_insert_new as $dkey => $dvalue) {
            $new_data["co." . $dkey] = $dvalue;
        }
    }
    $sessiondata = $CI->session->userdata('admin_user');
    $insert_data = array(
        'docket_id' => $docket_id,
        'new_data' => isset($new_data) ? json_encode($new_data) : '',
        'old_data' => isset($old_data) ? json_encode($old_data) : '',
        'created_date' => date('Y-m-d H:i:s'),
        'created_by' => $sessiondata['id'],
        'created_by_type' => $sessiondata['type'] == 'customer' ? 2 : 1
    );

    $CI->gm->insert('docket_history', $insert_data);
}
function add_location_insert_history($location_id)
{
    $CI = &get_instance();
    //LOG UPDATED DATA HISTORY

    $location_data_new['location'] = $CI->gm->get_selected_record('location', '*', $where = array('id' => $location_id, 'status !=' => 3), array());
    $location_data_new['hub_mapping'] = $CI->gm->get_selected_record('location_zone_map', '*', $where = array('location_id' => $location_id, 'status !=' => 3), array());

    //$map_column = "location_id,billing_type,zone_id,vendor_id,,co_vendor_id,eff_min_date,eff_max_date,delivery_area,tat,migration_id,customer_id";
    //$location_zone_map_data_new = $CI->gm->get_data_list('location_zone_map', array('location_id' => $location_id, 'status !=' => 3), array(), array(), $map_column);

    if (isset($location_data_new) && is_array($location_data_new) && count($location_data_new) > 0) {
        unset($location_data_new["id"]);
        unset($location_data_new["created_by"]);
        unset($location_data_new["created_date"]);
        unset($location_data_new["modified_by"]);
        unset($location_data_new["modified_date"]);
        foreach ($location_data_new as $lkey => $lvalue) {
            $new_data[$lkey] = $lvalue;
        }
    }
    $sessiondata = $CI->session->userdata('admin_user');
    $insert_data = array(
        'location_id' => $location_id,
        'new_data' => isset($new_data) ? json_encode($new_data) : '',
        'old_data' => isset($old_data) ? json_encode($old_data) : '',
        'created_date' => date('Y-m-d H:i:s'),
        'created_by' => $sessiondata['id'],
        'created_by_type' => $sessiondata['type'] == 'customer' ? 2 : 1
    );

    $CI->gm->insert('location_history', $insert_data);
}
function add_customer_insert_history($customer_id)
{
    $CI = &get_instance();
    //LOG UPDATED DATA HISTORY
    $customer_data_new = $CI->gm->get_selected_record('customer', '*', $where = array('id' => $customer_id, 'status !=' => 3), array());

    if (isset($customer_data_new) && is_array($customer_data_new) && count($customer_data_new) > 0) {
        unset($customer_data_new["id"]);
        unset($customer_data_new["created_by"]);
        unset($customer_data_new["created_date"]);
        unset($customer_data_new["modified_by"]);
        unset($customer_data_new["modified_date"]);
        foreach ($customer_data_new as $lkey => $lvalue) {
            $new_data[$lkey] = $lvalue;
        }
    }
    $sessiondata = $CI->session->userdata('admin_user');
    $insert_data = array(
        'customer_id' => $customer_id,
        'new_data' => isset($new_data) ? json_encode($new_data) : '',
        'old_data' => isset($old_data) ? json_encode($old_data) : '',
        'created_date' => date('Y-m-d H:i:s'),
        'created_by' => $sessiondata['id'],
        'created_by_type' => $sessiondata['type'] == 'customer' ? 2 : 1
    );

    $CI->gm->insert('customer_master_history', $insert_data);
}

function add_country_insert_history($country_id)
{

    $CI = &get_instance();
    $country_data_new = $CI->gm->get_selected_record('country', '*', $where = array('id' => $country_id, 'status !=' => 3), array());
    if (isset($country_data_new) && is_array($country_data_new) && count($country_data_new) > 0) {
        unset($country_data_new["id"]);
        unset($country_data_new["created_by"]);
        unset($country_data_new["created_date"]);
        unset($country_data_new["modified_by"]);
        unset($country_data_new["modified_date"]);
        foreach ($country_data_new as $lkey => $lvalue) {
            $new_data[$lkey] = $lvalue;
        }
    }
    $sessiondata = $CI->session->userdata('admin_user');
    $insert_data = array(
        'country_master_id' => $country_id,
        'new_data' => isset($new_data) ? json_encode($new_data) : '',
        'old_data' => isset($old_data) ? json_encode($old_data) : '',
        'created_date' => date('Y-m-d H:i:s'),
        'created_by' => $sessiondata['id'],
        'created_by_type' => $sessiondata['type'] == 'customer' ? 2 : 1
    );
    $CI->gm->insert('country_master_history', $insert_data);
}
function add_state_insert_history($state_id)
{

    $CI = &get_instance();
    $state_data_new = $CI->gm->get_selected_record('state', '*', $where = array('id' => $state_id, 'status !=' => 3), array());
    if (isset($state_data_new) && is_array($state_data_new) && count($state_data_new) > 0) {
        unset($state_data_new["id"]);
        unset($state_data_new["created_by"]);
        unset($state_data_new["created_date"]);
        unset($state_data_new["modified_by"]);
        unset($state_data_new["modified_date"]);
        foreach ($state_data_new as $lkey => $lvalue) {
            $new_data[$lkey] = $lvalue;
        }
    }
    $sessiondata = $CI->session->userdata('admin_user');
    $insert_data = array(
        'state_master_id' => $state_id,
        'new_data' => isset($new_data) ? json_encode($new_data) : '',
        'old_data' => isset($old_data) ? json_encode($old_data) : '',
        'created_date' => date('Y-m-d H:i:s'),
        'created_by' => $sessiondata['id'],
        'created_by_type' => $sessiondata['type'] == 'customer' ? 2 : 1
    );
    $CI->gm->insert('state_master_history', $insert_data);
}
function add_city_insert_history($city_id)
{

    $CI = &get_instance();
    $city_data_new = $CI->gm->get_selected_record('city', '*', $where = array('id' => $city_id, 'status !=' => 3), array());
    if (isset($city_data_new) && is_array($city_data_new) && count($city_data_new) > 0) {
        unset($city_data_new["id"]);
        unset($city_data_new["created_by"]);
        unset($city_data_new["created_date"]);
        unset($city_data_new["modified_by"]);
        unset($city_data_new["modified_date"]);
        foreach ($city_data_new as $lkey => $lvalue) {
            $new_data[$lkey] = $lvalue;
        }
    }
    $sessiondata = $CI->session->userdata('admin_user');
    $insert_data = array(
        'city_master_id' => $city_id,
        'new_data' => isset($new_data) ? json_encode($new_data) : '',
        'old_data' => isset($old_data) ? json_encode($old_data) : '',
        'created_date' => date('Y-m-d H:i:s'),
        'created_by' => $sessiondata['id'],
        'created_by_type' => $sessiondata['type'] == 'customer' ? 2 : 1
    );
    $CI->gm->insert('city_master_history', $insert_data);
}
function add_district_insert_history($district_id)
{

    $CI = &get_instance();
    $district_data_new = $CI->gm->get_selected_record('district', '*', $where = array('id' => $district_id, 'status !=' => 3), array());
    if (isset($district_data_new) && is_array($district_data_new) && count($district_data_new) > 0) {
        unset($district_data_new["id"]);
        unset($district_data_new["created_by"]);
        unset($district_data_new["created_date"]);
        unset($district_data_new["modified_by"]);
        unset($district_data_new["modified_date"]);
        foreach ($district_data_new as $lkey => $lvalue) {
            $new_data[$lkey] = $lvalue;
        }
    }
    $sessiondata = $CI->session->userdata('admin_user');
    $insert_data = array(
        'district_master_id' => $district_id,
        'new_data' => isset($new_data) ? json_encode($new_data) : '',
        'old_data' => isset($old_data) ? json_encode($old_data) : '',
        'created_date' => date('Y-m-d H:i:s'),
        'created_by' => $sessiondata['id'],
        'created_by_type' => $sessiondata['type'] == 'customer' ? 2 : 1
    );
    $CI->gm->insert('district_master_history', $insert_data);
}
function add_route_insert_history($route_id)
{

    $CI = &get_instance();
    $route_data_new = $CI->gm->get_selected_record('route', '*', $where = array('id' => $route_id, 'status !=' => 3), array());
    if (isset($route_data_new) && is_array($route_data_new) && count($route_data_new) > 0) {
        unset($route_data_new["id"]);
        unset($route_data_new["created_by"]);
        unset($route_data_new["created_date"]);
        unset($route_data_new["modified_by"]);
        unset($route_data_new["modified_date"]);
        foreach ($route_data_new as $lkey => $lvalue) {
            $new_data[$lkey] = $lvalue;
        }
    }
    $sessiondata = $CI->session->userdata('admin_user');
    $insert_data = array(
        'route_master_id' => $route_id,
        'new_data' => isset($new_data) ? json_encode($new_data) : '',
        'old_data' => isset($old_data) ? json_encode($old_data) : '',
        'created_date' => date('Y-m-d H:i:s'),
        'created_by' => $sessiondata['id'],
        'created_by_type' => $sessiondata['type'] == 'customer' ? 2 : 1
    );
    $CI->gm->insert('route_master_history', $insert_data);
}
function add_zone_insert_history($zone_id)
{

    $CI = &get_instance();
    $zone_data_new = $CI->gm->get_selected_record('zone', '*', $where = array('id' => $zone_id, 'status !=' => 3), array());
    if (isset($zone_data_new) && is_array($zone_data_new) && count($zone_data_new) > 0) {
        unset($zone_data_new["id"]);
        unset($zone_data_new["created_by"]);
        unset($zone_data_new["created_date"]);
        unset($zone_data_new["modified_by"]);
        unset($zone_data_new["modified_date"]);
        foreach ($zone_data_new as $lkey => $lvalue) {
            $new_data[$lkey] = $lvalue;
        }
    }
    $sessiondata = $CI->session->userdata('admin_user');
    $insert_data = array(
        'zone_master_id' => $zone_id,
        'new_data' => isset($new_data) ? json_encode($new_data) : '',
        'old_data' => isset($old_data) ? json_encode($old_data) : '',
        'created_date' => date('Y-m-d H:i:s'),
        'created_by' => $sessiondata['id'],
        'created_by_type' => $sessiondata['type'] == 'customer' ? 2 : 1
    );
    $CI->gm->insert('zone_master_history', $insert_data);
}
function add_hub_insert_history($hub_id)
{

    $CI = &get_instance();
    $hub_data_new = $CI->gm->get_selected_record('hub', '*', $where = array('id' => $hub_id, 'status !=' => 3), array());
    if (isset($hub_data_new) && is_array($hub_data_new) && count($hub_data_new) > 0) {
        unset($hub_data_new["id"]);
        unset($hub_data_new["created_by"]);
        unset($hub_data_new["created_date"]);
        unset($hub_data_new["modified_by"]);
        unset($hub_data_new["modified_date"]);
        foreach ($hub_data_new as $lkey => $lvalue) {
            $new_data[$lkey] = $lvalue;
        }
    }
    $sessiondata = $CI->session->userdata('admin_user');
    $insert_data = array(
        'hub_master_id' => $hub_id,
        'new_data' => isset($new_data) ? json_encode($new_data) : '',
        'old_data' => isset($old_data) ? json_encode($old_data) : '',
        'created_date' => date('Y-m-d H:i:s'),
        'created_by' => $sessiondata['id'],
        'created_by_type' => $sessiondata['type'] == 'customer' ? 2 : 1
    );
    $CI->gm->insert('hub_master_history', $insert_data);
}

function update_docket_delivery_state($docket_delivery_id = 0, $docket_id = 0, $old_data = array(), $add_delivery_event = 2)
{


    $CI = &get_instance();
    //IF DOCKET POD DETAILS UPDATED THEN MARK DOCKET DELIVERED
    if ($docket_delivery_id > 0) {
        $pod_details = $CI->gm->get_selected_record('docket_delivery', '*', $where = array('id' => $docket_delivery_id, 'status=' => 1), array());
        if (isset($pod_details['delivery_date']) && $pod_details['delivery_date'] != '1970-01-01' && $pod_details['delivery_date'] != '0000-00-00') {
            $tracking_event_id = $CI->config->item('tracking_event_id');
            $docket_state_id = $tracking_event_id['delivered'];
            $CI->gm->update('docket', array('state_id' => $docket_state_id), '', array('id' => $pod_details['docket_id']));
        }
    }

    if ($docket_id > 0) {
        $pod_details = $CI->gm->get_selected_record('docket_delivery', '*', $where = array('docket_id' => $docket_id, 'status=' => 1), array());
        if (isset($pod_details['delivery_date']) && $pod_details['delivery_date'] != '1970-01-01' && $pod_details['delivery_date'] != '0000-00-00') {
            $tracking_event_id = $CI->config->item('tracking_event_id');
            $docket_state_id = $tracking_event_id['delivered'];
            $CI->gm->update('docket', array('state_id' => $docket_state_id), '', array('id' => $pod_details['docket_id']));
        }
    }

    if ($add_delivery_event == 1) {
        $tracking_event_id = $CI->config->item('tracking_event_id');
        $docket_id = $pod_details['docket_id'];
        $tracking_data = array(
            'event' => 'delivered',
            'module_id' => $docket_id,
            'module_type' => 1,
            'event_datetime' => $pod_details['delivery_date'] . ' ' . $pod_details['delivery_time'],
        );

        if (isset($old_data['event_location']) && $old_data['event_location'] != '') {
            $tracking_data['event_location'] = $old_data['event_location'];
        }
        if (isset($old_data['event_description']) && $old_data['event_description'] != '') {
            $tracking_data['event_description'] = $old_data['event_description'];
        }


        //check delivery event exist
        $qry = "SELECT id FROM docket_tracking WHERE status=1 AND tracking_type=1 AND docket_id='" . $docket_id . "'
        AND docket_state_id='" . $tracking_event_id['delivered'] . "'";
        $qry_exe = $CI->db->query($qry);
        $deliveryExist = $qry_exe->row_array();
        if (isset($deliveryExist) && is_array($deliveryExist) && count($deliveryExist) > 0) {
        } else {
            if (
                $pod_details['delivery_date'] != '' && $pod_details['delivery_date'] != '1970-01-01'
                && $pod_details['delivery_date'] != '0000-00-00'
            ) {
                add_docket_event($docket_id, $tracking_event_id['delivered'], $tracking_data);
            }
        }
    }
}
function update_docket_tracking_state($docket_tracking_id = 0, $docket_id = 0)
{
    $CI = &get_instance();
    //FETCH LATESH DOCKET STATE ID
    $tracking_event_id = $CI->config->item('tracking_event_id');
    $docket_state_id = $tracking_event_id['entry'];
    if ($docket_tracking_id > 0) {
        $qry = "SELECT id,docket_id,tracking_type,event_desc FROM docket_tracking WHERE id='" . $docket_tracking_id . "'";
        $qry_exe = $CI->db->query($qry);
        $tracking_data = $qry_exe->row_array();
    } else if ($docket_id > 0) {
        $tracking_data = array(
            'docket_id' => $docket_id,
            'tracking_type' => 1,
        );
    }

    $tracking_event_id = $CI->config->item('tracking_event_id');

    if (isset($tracking_data) && is_array($tracking_data) && count($tracking_data) > 0) {
        //GET LATEST TRACKING
        $qry = "SELECT id,docket_id,tracking_type,docket_state_id,event_desc 
        FROM docket_tracking WHERE status IN(1,2) AND docket_id='" . $tracking_data['docket_id'] . "'
          ORDER BY event_date_time DESC";
        $qry_exe = $CI->db->query($qry);
        $latest_state = $qry_exe->row_array();
        if (isset($latest_state) && is_array($latest_state) && count($latest_state) > 0) {
            $docket_state_id = $latest_state['docket_state_id'];
        }


        // If the last event is from awb state master - if yes then get master's state
        // If the last event has RTO in it - then event state = RTO
        // Rest - IN transit

        if ($tracking_data['docket_id']) {
            if ($latest_state['docket_state_id'] > 0) {
                $docket_state_id = $latest_state['docket_state_id'];
            } else if (isset($latest_state['event_desc']) && $latest_state['event_desc'] != '' && strpos(strtolower($latest_state['event_desc']), 'rto') !== false) {
                $docket_state_id = $tracking_event_id['rto'];
            } else {
                $docket_state_id = $tracking_event_id['in_transit'];
            }
        }
        if ($tracking_data['docket_id'] > 0 && $docket_state_id > 0) {
            $CI->gm->update('docket', array('state_id' => $docket_state_id, 'address_info_needed' => 2), '', array('id' => $tracking_data['docket_id']));
        }


        $qry = "SELECT id,docket_id,tracking_type,event_desc FROM docket_tracking 
        WHERE docket_id='" . $tracking_data['docket_id'] . "' AND status=1 AND 
        (LOWER(event_desc) LIKE '%address information needed%' OR LOWER(event_desc) LIKE '%further consignee information needed%')";
        $qry_exe = $CI->db->query($qry);
        $address_needed = $qry_exe->row_array();
        if (isset($address_needed) && is_array($address_needed) && count($address_needed) > 0) {
            $CI->gm->update('docket', array('address_info_needed' => 1), '', array('id' => $tracking_data['docket_id']));
        }
    }
    //IF DOCKET POD DETAILS UPDATED THEN MARK DOCKET DELIVERED
    $pod_details = $CI->gm->get_selected_record('docket_delivery', '*', $where = array('docket_id' => $tracking_data['docket_id'], 'status=' => 1), array());
    if (isset($pod_details['delivery_date']) && $pod_details['delivery_date'] != '1970-01-01' && $pod_details['delivery_date'] != '0000-00-00') {

        $docket_state_id = $tracking_event_id['delivered'];
        $CI->gm->update('docket', array('state_id' => $docket_state_id), '', array('id' => $tracking_data['docket_id']));
    }
}



function update_docket_tracking_api($docket_tracking_id = 0, $docket_id = 0)
{
    $CI = &get_instance();
    //FETCH LATESH DOCKET STATE ID
    $tracking_event_id = $CI->config->item('tracking_event_id');
    $docket_state_id = $tracking_event_id['entry'];
    if ($docket_tracking_id > 0) {
        $qry = "SELECT id,docket_id,tracking_type,event_desc FROM docket_tracking WHERE id='" . $docket_tracking_id . "'";
        $qry_exe = $CI->db->query($qry);
        $tracking_data = $qry_exe->row_array();
    } else if ($docket_id > 0) {
        $tracking_data = array(
            'docket_id' => $docket_id,
            'tracking_type' => 1,
        );
    }

    $tracking_event_id = $CI->config->item('tracking_event_id');

    if (isset($tracking_data) && is_array($tracking_data) && count($tracking_data) > 0) {
        if ($tracking_data['docket_id'] > 0 && $docket_state_id > 0) {
            $CI->gm->update('docket', array('address_info_needed' => 2), '', array('id' => $tracking_data['docket_id']));
        }


        $qry = "SELECT id,docket_id,tracking_type,event_desc FROM docket_tracking 
        WHERE docket_id='" . $tracking_data['docket_id'] . "' AND status=1 AND 
        (LOWER(event_desc) LIKE '%address information needed%' OR LOWER(event_desc) LIKE '%further consignee information needed%')";
        $qry_exe = $CI->db->query($qry);
        $address_needed = $qry_exe->row_array();
        if (isset($address_needed) && is_array($address_needed) && count($address_needed) > 0) {
            $CI->gm->update('docket', array('address_info_needed' => 1), '', array('id' => $tracking_data['docket_id']));
        }
    }
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


if (!function_exists('update_docket_invoice_total')) {
    function update_docket_invoice_total($invoice_map_id = 0, $docket_sales_id = 0, $docket_id = 0, $invoice_id = 0, $mode = '')
    {
        $CI = &get_instance();
        $docket_cnt = 0;
        $invoice_total = 0;
        $sessiondata = $CI->session->userdata('admin_user');
        if ($docket_sales_id > 0) {
            $qry = "SELECT docket_id FROM docket_sales_billing 
            WHERE status IN(1,2) AND id='" . $docket_sales_id . "'";
            $qry_exe = $CI->db->query($qry);
            $sales_data = $qry_exe->row_array();

            if (isset($sales_data) && is_array($sales_data) && count($sales_data) > 0) {
                $qry = "SELECT i.id FROM docket_invoice i
                JOIN docket_invoice_map im ON(i.id=im.docket_invoice_id)
                WHERE i.status IN(1,2) AND im.status IN(1,2) 
                AND im.docket_id='" . $sales_data['docket_id'] . "'";
                $qry_exe = $CI->db->query($qry);
                $docket_data = $qry_exe->row_array();
            }

            if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
                $invoice_id = $docket_data['id'];
            }
        }

        if ($docket_id > 0) {
            $qry = "SELECT i.id FROM docket_invoice i
                JOIN docket_invoice_map im ON(i.id=im.docket_invoice_id)
                WHERE i.status IN(1,2) AND im.status IN(1,2) 
                AND im.docket_id='" . $docket_id . "'";
            $qry_exe = $CI->db->query($qry);
            $docket_data = $qry_exe->row_array();

            if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
                $invoice_id = $docket_data['id'];
            }
        }

        if ($invoice_map_id > 0) {
            $qry = "SELECT i.id FROM docket_invoice i
            JOIN docket_invoice_map im ON(i.id=im.docket_invoice_id)
            WHERE i.status IN(1,2)
            AND im.id='" . $invoice_map_id . "'";
            $qry_exe = $CI->db->query($qry);
            $docket_data = $qry_exe->row_array();

            if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
                $invoice_id = $docket_data['id'];
            }
        }

        if ($invoice_id > 0) {
            //GET INVOCIE ALL DOCKET 
            $qry = "SELECT DISTINCT d.id,bill.grand_total FROM docket_invoice i
            JOIN docket_invoice_map im ON(i.id=im.docket_invoice_id)
            JOIN docket d ON(d.id=im.docket_id)
            JOIN docket_sales_billing bill ON(d.id=bill.docket_id)
            WHERE i.status IN(1,2) AND im.status IN(1,2) AND d.status IN(1,2) 
            AND bill.status IN(1,2) AND i.id='" . $invoice_id . "'";
            $qry_exe = $CI->db->query($qry);
            $bill_data = $qry_exe->result_array();

            if (isset($bill_data) && is_array($bill_data) && count($bill_data) > 0) {
                foreach ($bill_data as $key => $value) {
                    $docket_cnt =  $docket_cnt + 1;
                    $docket_ids[$value['id']] = $value['id'];
                }
            }

            $update_data = array(
                'docket_count' => $docket_cnt,
            );
            $calculation_data =  calculate_invoice_gst($docket_ids);

            $update_data['non_taxable_amt'] = isset($calculation_data['non_taxable_amt']) ? $calculation_data['non_taxable_amt'] : 0;
            $update_data['taxable_amt'] = isset($calculation_data['taxable_amt']) ? $calculation_data['taxable_amt'] : 0;
            $update_data['gst_per'] = isset($calculation_data['gst_per']) ? $calculation_data['gst_per'] : 0;
            $update_data['igst_amount'] = isset($calculation_data['igst_amount']) ? $calculation_data['igst_amount'] : 0;
            $update_data['cgst_amount'] = isset($calculation_data['cgst_amount']) ? $calculation_data['cgst_amount'] : 0;
            $update_data['sgst_amount'] = isset($calculation_data['sgst_amount']) ? $calculation_data['sgst_amount'] : 0;
            $update_data['grand_total'] = isset($calculation_data['grand_total']) ? $calculation_data['grand_total'] : 0;


            $CI->gm->update('docket_invoice', $update_data, '', array('id' => $invoice_id));

            add_ledger_item($invoice_id, 5, 2);

            if ($mode == 'void_docket') {
                $invoice_type = 3;
                //UPDATE ACCOUNT WHERE THIS OPENING BALANCE INCLUDED
                $qry = "SELECT * FROM docket_include_data WHERE status=1 AND invoice_id='" . $invoice_id . "' AND invoice_type='3'";
                $qry_exe = $CI->db->query($qry);
                $include_data = $qry_exe->result_array();

                if (isset($include_data) && is_array($include_data) && count($include_data) > 0) {
                    $include_update = array(
                        'status' => 3,
                        'modified_date' =>  date('Y-m-d H:i:s'),
                        'modified_by' => $sessiondata['id'],
                    );
                    $CI->gm->update('docket_include_data', $include_update, '', array('invoice_id' => $invoice_id, 'invoice_type' => $invoice_type));

                    foreach ($include_data as $key => $value) {
                        $total_received = 0;
                        $deduction_amt = 0;
                        $tds_amt = 0;
                        $received_amt = 0;
                        $round_off_amt = 0;

                        $invoice_data = array();
                        $include_invoice_amt = array();
                        $total_qry = '';

                        if ($value['credit_id_type'] == 1) {
                            $include_invoice_amt =  get_include_data($value['credit_id'], 1, 1);
                            $table = 'payment_receipt';
                            $total_qry = "SELECT id,receipt_amount as total_amt FROM payment_receipt WHERE id='" . $value['credit_id'] . "'";
                        } else if ($value['credit_id_type'] == 2) {
                            $include_invoice_amt =  get_include_data($value['credit_id'], 2, 1);
                            $table = 'credit_debit_note';
                            $total_qry = "SELECT id,grand_total_amount as total_amt FROM credit_debit_note WHERE id='" . $value['credit_id'] . "'";
                        } else if ($value['credit_id_type'] == 3) {
                            $include_invoice_amt =  get_include_data($value['credit_id'], 3, 1);
                            $table = 'opening_balance';
                            $total_qry = "SELECT id,opening_amount as total_amt FROM opening_balance WHERE id='" . $value['credit_id'] . "'";
                        }


                        if (isset($total_qry) && $total_qry != '') {
                            $total_qry_exe = $CI->db->query($total_qry);
                            $total_data = $total_qry_exe->row_array();
                        }

                        $total_amt = isset($total_data['total_amt']) ? $total_data['total_amt'] : 0;
                        if (isset($include_invoice_amt) && is_array($include_invoice_amt) && count($include_invoice_amt) > 0) {

                            foreach ($include_invoice_amt as $in_key => $in_value) {
                                $total_received += $in_value['deduction_amt'] + $in_value['tds_amt'] + $in_value['received_amt'];

                                $deduction_amt += $in_value['deduction_amt'];
                                $tds_amt += $in_value['tds_amt'];
                                $received_amt += $in_value['received_amt'];
                                $round_off_amt += $in_value['round_off_amt'];
                            }
                        }
                        $leftover_amt = $total_amt - $total_received;

                        $mainq = "UPDATE " . $table . " SET deduction_amt=" . $deduction_amt . ",tds_amt=" . $tds_amt . ",
                           received_amt=" . $received_amt . ",round_off_amt=" . $round_off_amt . ",leftover_amt=" . $leftover_amt . "
                           WHERE id=" . $value['credit_id'];
                        $CI->db->query($mainq);

                        if ($value['credit_id_type'] == 1) {
                            $updateq = "UPDATE ledger_outstanding_item SET amount='" . $leftover_amt . "'
                       ,modified_date='" . date('Y-m-d H:i:s') . "',modified_by='" . $sessiondata['id'] . "' WHERE payment_id='" . $value['credit_id'] . "' AND payment_type=2";
                            $CI->db->query($updateq);
                        } else if ($value['credit_id_type'] == 2) {
                            $updateq = "UPDATE ledger_outstanding_item SET amount='" . $leftover_amt . "'
                       ,modified_date='" . date('Y-m-d H:i:s') . "',modified_by='" . $sessiondata['id'] . "' WHERE payment_id='" . $value['credit_id'] . "' AND payment_type IN(3,4)";
                            $CI->db->query($updateq);
                        } else if ($value['credit_id_type'] == 3) {
                            $updateq = "UPDATE ledger_outstanding_item SET amount='" . $leftover_amt . "'
                       ,modified_date='" . date('Y-m-d H:i:s') . "',modified_by='" . $sessiondata['id'] . "' WHERE payment_id='" . $value['credit_id'] . "' AND payment_type =1";
                            $CI->db->query($updateq);
                        }
                    }
                }
            }
        }
    }
}

function get_consignor_wt($contract_id = 0, $module = '')
{
    $consignor_wt = 0;
    $CI = &get_instance();
    if ($contract_id > 0 && $module != '') {

        if ($module == 'customer') {
            $query = "SELECT * FROM customer_contract_rate WHERE status IN(1,2) AND customer_contract_id='" . $contract_id . "'";
        } else if ($module == 'service') {
            $query = "SELECT * FROM vendor_contract_rate WHERE status IN(1,2) AND vendor_contract_id='" . $contract_id . "'";
        }

        if (isset($query)) {
            $query_exe = $CI->db->query($query);
            $rate_data_res  = $query_exe->result_array();
            if (isset($rate_data_res) && is_array($rate_data_res) && count($rate_data_res) > 0) {
                $consignor_wt = $rate_data_res[0]['upper_wt'];
            }
        }
    }

    return $consignor_wt;
}

function check_docket_invoice($data = array())
{
    $CI = &get_instance();
    if (isset($data['docket_invoice_id']) && isset($data['docket_id'])) {
        $query = "SELECT im.id FROM docket_invoice_map im
        JOIN docket_invoice d ON(d.id=im.docket_invoice_id)
        WHERE im.status IN(1,2) AND d.status IN(1,2) AND im.docket_id='" . $data['docket_id'] . "'";
        $query_exe = $CI->db->query($query);
        $invoice_data  = $query_exe->result_array();
    }

    if (isset($invoice_data) && is_array($invoice_data) && count($invoice_data) > 0) {
        return true;
    } else {
        return false;
    }
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

function get_show_payment_message()
{
    $powered_desc = 1;
    $com_id =  isset($_SESSION['admin_user']['com_id']) ? $_SESSION['admin_user']['com_id'] : 0;
    if ($com_id > 0) {
        $CI = &get_instance();
        $main_db = $CI->load->database('main_db', true);

        $qry = "SELECT id,show_payment_message FROM company WHERE status IN(1,2) AND id='" . $com_id . "'";
        $qry_exe = $main_db->query($qry);
        $com_res = $qry_exe->row_array();
        $powered_desc = isset($com_res['show_payment_message']) ? $com_res['show_payment_message'] : '';
    }

    return $powered_desc;
}


function calculate_invoice_gst($docket_ids = array())
{
    $CI = &get_instance();
    $all_setting = get_all_app_setting(" AND module_name IN('invoice')");
    if (isset($docket_ids) && is_array($docket_ids) && count($docket_ids) > 0) {

        $all_vendor = get_all_vendor();
        $all_master_type = get_all_master_service_type();
        $qry = "SELECT d.customer_id,d.vendor_id,d.company_id,ds.non_taxable_amt,ds.taxable_amt,ds.gst_per,
        de.sector_id FROM docket_sales_billing ds 
        JOIN docket d ON(d.id=ds.docket_id)
        LEFT OUTER JOIN docket_extra_field de ON(d.id=de.docket_id AND de.status=1)
        WHERE d.status IN(1,2) AND ds.status IN(1,2) AND ds.docket_id IN(" . implode(",", $docket_ids) . ")";
        $qry_exe = $CI->db->query($qry);
        $docket_sales_res = $qry_exe->result_array();

        $non_taxable_amt = 0;
        $taxable_amt = 0;
        $gst_per = 0;
        $igst_amount = 0;
        $cgst_amount = 0;
        $sgst_amount = 0;
        $grand_total = 0;


        if (isset($docket_sales_res) && is_array($docket_sales_res) && count($docket_sales_res) > 0) {
            foreach ($docket_sales_res as $dskey => $dsvalue) {



                $non_taxable_amt += $dsvalue['non_taxable_amt'];

                if ($dsvalue['taxable_amt'] > 0) {
                    if (isset($all_vendor[$dsvalue['vendor_id']])) {

                        $service_type = $all_vendor[$dsvalue['vendor_id']]['master_service_type'];

                        if (isset($res_service_type[$dsvalue['gst_per']][$service_type])) {
                            $old_amnt = $res_service_type[$dsvalue['gst_per']][$service_type]['taxable_amt'];
                            $res_service_type[$dsvalue['gst_per']][$service_type] = array(
                                'gst_per' => $dsvalue['gst_per'],
                                'taxable_amt' => $dsvalue['taxable_amt'] + $old_amnt,
                                'sac_code' => $all_master_type[$service_type]['sac_code'],
                                'vendor_id' => $dsvalue['vendor_id'],
                                'sector_id' => $dsvalue['sector_id']
                            );
                        } else {
                            $res_service_type[$dsvalue['gst_per']][$service_type] = array(
                                'gst_per' => $dsvalue['gst_per'],
                                'taxable_amt' => $dsvalue['taxable_amt'],
                                'sac_code' => $all_master_type[$service_type]['sac_code'],
                                'vendor_id' => $dsvalue['vendor_id'],
                                'sector_id' => $dsvalue['sector_id']
                            );
                        }


                        $all_gst_per[$dsvalue['gst_per']] = $dsvalue['gst_per'];
                    }
                } else {
                    $non_taxable_amt = round($non_taxable_amt, 2);
                    if (isset($sector_data[$dsvalue['sector_id']])) {
                        $sector_data[$dsvalue['sector_id']] += $non_taxable_amt;
                    } else {
                        $sector_data[$dsvalue['sector_id']] = $non_taxable_amt;
                    }
                }

                // $taxable_amt += $dsvalue['taxable_amt'];
                // $gst_per = $dsvalue['gst_per'];
                $company_id = $dsvalue['company_id'];
                $customer_id = $dsvalue['customer_id'];
                $vendor_id = $dsvalue['vendor_id'];
                $sector_id = $dsvalue['sector_id'];
            }

            if (isset($all_gst_per) && is_array($all_gst_per) && count($all_gst_per) > 0) {
                if (count($all_gst_per) == 1) {
                    if (isset($res_service_type) && is_array($res_service_type) && count($res_service_type) > 0) {
                        foreach ($res_service_type as $gst_key => $gst_value) {
                            foreach ($gst_value as $skey => $svalue) {

                                $taxable_amt += $svalue['taxable_amt'];
                                $gst_per = $svalue['gst_per'];
                                $sac_code = $svalue['sac_code'];
                                $vendor_id = $svalue['vendor_id'];
                                $sector_id = $svalue['sector_id'];
                            }
                        }

                        $taxable_arr[] = array(
                            'taxable_amt' => $taxable_amt,
                            'gst_per' => $gst_per,
                            'sac_code' => $sac_code,
                            'vendor_id' => $vendor_id,
                            'sector_id' => $sector_id,
                        );
                    }
                } else {
                    if (isset($res_service_type) && is_array($res_service_type) && count($res_service_type) > 0) {
                        foreach ($res_service_type as $gst_key => $gst_value) {
                            $taxable_amt = 0;
                            foreach ($gst_value as $skey => $svalue) {
                                $taxable_amt += $svalue['taxable_amt'];
                                $gst_per = $svalue['gst_per'];
                                $sac_code = $svalue['sac_code'];
                                $vendor_id = $svalue['vendor_id'];
                                $sector_id = $svalue['sector_id'];
                            }

                            $taxable_arr[] = array(
                                'taxable_amt' => $taxable_amt,
                                'gst_per' => $gst_per,
                                'sac_code' => $sac_code,
                                'vendor_id' => $vendor_id,
                                'sector_id' => $sector_id,
                            );
                        }
                    }
                }
            }

            if (isset($all_setting['dont_round_grand_total_in_invoice']) && $all_setting['dont_round_grand_total_in_invoice'] == 1) {
                $non_taxable_amt = $non_taxable_amt;
                $total_cgst_amount = 0;
                $total_sgst_amount = 0;
                $total_igst_amount = 0;
                $total_grand_total = 0;
                $total_taxable_amt = 0;

                $total_grand_total += $non_taxable_amt;

                if (isset($taxable_arr) && is_array($taxable_arr) && count($taxable_arr) > 0) {
                    foreach ($taxable_arr as $tkey => $tvalue) {
                        $taxable_amt = $tvalue['taxable_amt'];
                        $total_taxable_amt += $tvalue['taxable_amt'];
                        $gst_per = $tvalue['gst_per'];
                        $vendor_id = $tvalue['vendor_id'];
                        $taxable_amt = $taxable_amt;
                        if ($gst_per > 0) {

                            $gst_amt = ($taxable_amt * $gst_per) / 100;
                            $gst_amt = $gst_amt;


                            if (isset($company_id) && $company_id > 0) {
                                $qry = "SELECT id,gst_number FROM company_master WHERE id='" . $company_id . "'";
                                $qry_exe = $CI->db->query($qry);
                                $billing_company = $qry_exe->row_array();
                                if (isset($billing_company['gst_number']) && $billing_company['gst_number'] != '') {
                                    $company_gst =  substr($billing_company['gst_number'], 0, 2);
                                }
                            }

                            if (isset($customer_id) && $customer_id > 0) {
                                $qry = "SELECT id,gst_number FROM customer WHERE id='" . $customer_id . "'";
                                $qry_exe = $CI->db->query($qry);
                                $billing_customer = $qry_exe->row_array();

                                if (isset($billing_customer['gst_number']) && $billing_customer['gst_number'] != '') {
                                    $customer_gst =  substr($billing_customer['gst_number'], 0, 2);
                                }
                            }

                            if (isset($vendor_id) && $vendor_id > 0) {
                                $qry = "SELECT id,service_type FROM vendor WHERE id='" . $vendor_id . "'";
                                $qry_exe = $CI->db->query($qry);
                                $billing_vendor = $qry_exe->row_array();
                            }


                            if (isset($billing_vendor['service_type']) && $billing_vendor['service_type'] == 1) {
                                $igst_amount = $gst_amt;
                            } else {
                                if ($customer_gst != '' && isset($customer_gst) && isset($company_gst) && $customer_gst == $company_gst) {
                                    $cgst_amount = $gst_amt / 2;
                                    $sgst_amount = $gst_amt / 2;
                                } else {
                                    $igst_amount = $gst_amt;
                                }
                            }
                        }

                        $total_cgst_amount += $cgst_amount;
                        $total_sgst_amount += $sgst_amount;
                        $total_igst_amount += $igst_amount;
                        $total_grand_total += $taxable_amt + $cgst_amount + $sgst_amount + $igst_amount;


                        if (isset($sector_data[$tvalue['sector_id']])) {
                            $sector_data[$tvalue['sector_id']] += ($non_taxable_amt + $taxable_amt + $cgst_amount + $sgst_amount + $igst_amount);
                        } else {
                            $sector_data[$tvalue['sector_id']] = $non_taxable_amt + $taxable_amt + $cgst_amount + $sgst_amount + $igst_amount;
                        }

                        if (count($taxable_arr) > 1) {
                            $tvalue['taxable_amt'] =  $taxable_amt;
                            $tvalue['cgst_amount'] =  $cgst_amount;
                            $tvalue['sgst_amount'] =  $sgst_amount;
                            $tvalue['igst_amount'] =  $igst_amount;
                            $taxable_breakdown[] = $tvalue;
                            $total_gst_per[$gst_per] = $gst_per;
                        } else {
                            $total_gst_per[$gst_per] = $gst_per;
                        }
                    }
                }
            } else {
                $non_taxable_amt = round($non_taxable_amt, 2);

                $total_cgst_amount = 0;
                $total_sgst_amount = 0;
                $total_igst_amount = 0;
                $total_grand_total = 0;
                $total_taxable_amt = 0;

                $total_grand_total += $non_taxable_amt;

                if (isset($taxable_arr) && is_array($taxable_arr) && count($taxable_arr) > 0) {
                    foreach ($taxable_arr as $tkey => $tvalue) {
                        $taxable_amt = $tvalue['taxable_amt'];
                        $total_taxable_amt += $tvalue['taxable_amt'];
                        $gst_per = $tvalue['gst_per'];
                        $vendor_id = $tvalue['vendor_id'];
                        $taxable_amt = round($taxable_amt, 2);
                        if ($gst_per > 0) {

                            $gst_amt = ($taxable_amt * $gst_per) / 100;
                            $gst_amt = round($gst_amt, 2);


                            if (isset($company_id) && $company_id > 0) {
                                $qry = "SELECT id,gst_number FROM company_master WHERE id='" . $company_id . "'";
                                $qry_exe = $CI->db->query($qry);
                                $billing_company = $qry_exe->row_array();
                                if (isset($billing_company['gst_number']) && $billing_company['gst_number'] != '') {
                                    $company_gst =  substr($billing_company['gst_number'], 0, 2);
                                }
                            }

                            if (isset($customer_id) && $customer_id > 0) {
                                $qry = "SELECT id,gst_number FROM customer WHERE id='" . $customer_id . "'";
                                $qry_exe = $CI->db->query($qry);
                                $billing_customer = $qry_exe->row_array();

                                if (isset($billing_customer['gst_number']) && $billing_customer['gst_number'] != '') {
                                    $customer_gst =  substr($billing_customer['gst_number'], 0, 2);
                                }
                            }

                            if (isset($vendor_id) && $vendor_id > 0) {
                                $qry = "SELECT id,service_type FROM vendor WHERE id='" . $vendor_id . "'";
                                $qry_exe = $CI->db->query($qry);
                                $billing_vendor = $qry_exe->row_array();
                            }


                            if (isset($billing_vendor['service_type']) && $billing_vendor['service_type'] == 1) {
                                $igst_amount = $gst_amt;
                            } else {
                                if ($customer_gst != '' && isset($customer_gst) && isset($company_gst) && $customer_gst == $company_gst) {
                                    $cgst_amount = $gst_amt / 2;
                                    $sgst_amount = $gst_amt / 2;
                                } else {
                                    $igst_amount = $gst_amt;
                                }
                            }
                        }

                        $total_cgst_amount += round($cgst_amount, 2);
                        $total_sgst_amount += round($sgst_amount, 2);
                        $total_igst_amount += round($igst_amount, 2);
                        $total_grand_total += $taxable_amt + $cgst_amount + $sgst_amount + $igst_amount;


                        if (isset($sector_data[$tvalue['sector_id']])) {
                            $sector_data[$tvalue['sector_id']] += ($non_taxable_amt + $taxable_amt + $cgst_amount + $sgst_amount + $igst_amount);
                        } else {
                            $sector_data[$tvalue['sector_id']] = $non_taxable_amt + $taxable_amt + $cgst_amount + $sgst_amount + $igst_amount;
                        }

                        if (count($taxable_arr) > 1) {
                            $tvalue['taxable_amt'] =  $taxable_amt;
                            $tvalue['cgst_amount'] =  $cgst_amount;
                            $tvalue['sgst_amount'] =  $sgst_amount;
                            $tvalue['igst_amount'] =  $igst_amount;
                            $taxable_breakdown[] = $tvalue;
                            $total_gst_per[$gst_per] = $gst_per;
                        } else {
                            $total_gst_per[$gst_per] = $gst_per;
                        }
                    }
                }
            }
        }
    }

    $bill_data['non_taxable_amt'] = isset($non_taxable_amt) ? $non_taxable_amt : 0;
    $bill_data['taxable_amt'] = isset($total_taxable_amt) ? $total_taxable_amt : 0;
    $bill_data['gst_per'] = isset($gst_per) ? $gst_per : 0;
    $bill_data['igst_amount'] = isset($total_igst_amount) ? $total_igst_amount : 0;
    $bill_data['cgst_amount'] = isset($total_cgst_amount) ? $total_cgst_amount : 0;
    $bill_data['sgst_amount'] = isset($total_sgst_amount) ? $total_sgst_amount : 0;
    if (isset($all_setting['dont_round_grand_total_in_invoice']) && $all_setting['dont_round_grand_total_in_invoice'] == 1) {
        $bill_data['grand_total'] = isset($total_grand_total) ? $total_grand_total : 0;
    } else {
        $bill_data['grand_total'] = isset($total_grand_total) ? round($total_grand_total) : 0;
    }
    $bill_data['sac_taxable_amt'] = isset($taxable_breakdown) && is_array($taxable_breakdown) ? json_encode($taxable_breakdown) : '';
    $bill_data['sector_data'] = isset($sector_data) ? $sector_data : array();


    return $bill_data;
}

function add_co_vendor_insert_history($co_vendor_id)
{

    $CI = &get_instance();
    $co_vendor_data_new = $CI->gm->get_selected_record('co_vendor', '*', $where = array('id' => $co_vendor_id, 'status !=' => 3), array());
    if (isset($co_vendor_data_new) && is_array($co_vendor_data_new) && count($co_vendor_data_new) > 0) {
        unset($co_vendor_data_new["id"]);
        unset($co_vendor_data_new["created_by"]);
        unset($co_vendor_data_new["created_date"]);
        unset($co_vendor_data_new["modified_by"]);
        unset($co_vendor_data_new["modified_date"]);
        foreach ($co_vendor_data_new as $lkey => $lvalue) {
            $new_data[$lkey] = $lvalue;
        }
    }
    $sessiondata = $CI->session->userdata('admin_user');
    $insert_data = array(
        'co_vendor_master_id' => $co_vendor_id,
        'new_data' => isset($new_data) ? json_encode($new_data) : '',
        'old_data' => isset($old_data) ? json_encode($old_data) : '',
        'created_date' => date('Y-m-d H:i:s'),
        'created_by' => $sessiondata['id'],
        'created_by_type' => $sessiondata['type'] == 'customer' ? 2 : 1
    );
    $CI->gm->insert('co_vendor_master_history', $insert_data);
}
function add_product_insert_history($product_id)
{

    $CI = &get_instance();
    $product_data_new = $CI->gm->get_selected_record('product', '*', $where = array('id' => $product_id, 'status !=' => 3), array());
    if (isset($product_data_new) && is_array($product_data_new) && count($product_data_new) > 0) {
        unset($product_data_new["id"]);
        unset($product_data_new["created_by"]);
        unset($product_data_new["created_date"]);
        unset($product_data_new["modified_by"]);
        unset($product_data_new["modified_date"]);
        foreach ($product_data_new as $lkey => $lvalue) {
            $new_data[$lkey] = $lvalue;
        }
    }
    $sessiondata = $CI->session->userdata('admin_user');
    $insert_data = array(
        'product_master_id' => $product_id,
        'new_data' => isset($new_data) ? json_encode($new_data) : '',
        'old_data' => isset($old_data) ? json_encode($old_data) : '',
        'created_date' => date('Y-m-d H:i:s'),
        'created_by' => $sessiondata['id'],
        'created_by_type' => $sessiondata['type'] == 'customer' ? 2 : 1
    );
    $CI->gm->insert('product_master_history', $insert_data);
}
function add_consignee_insert_history($consignee_id)
{

    $CI = &get_instance();
    $consignee_data_new = $CI->gm->get_selected_record('consignee', '*', $where = array('id' => $consignee_id, 'status !=' => 3), array());
    if (isset($consignee_data_new) && is_array($consignee_data_new) && count($consignee_data_new) > 0) {
        unset($consignee_data_new["id"]);
        unset($consignee_data_new["created_by"]);
        unset($consignee_data_new["created_date"]);
        unset($consignee_data_new["modified_by"]);
        unset($consignee_data_new["modified_date"]);
        foreach ($consignee_data_new as $lkey => $lvalue) {
            $new_data[$lkey] = $lvalue;
        }
    }
    $sessiondata = $CI->session->userdata('admin_user');
    $insert_data = array(
        'consignee_master_id' => $consignee_id,
        'new_data' => isset($new_data) ? json_encode($new_data) : '',
        'old_data' => isset($old_data) ? json_encode($old_data) : '',
        'created_date' => date('Y-m-d H:i:s'),
        'created_by' => $sessiondata['id'],
        'created_by_type' => $sessiondata['type'] == 'customer' ? 2 : 1
    );
    $CI->gm->insert('consignee_master_history', $insert_data);
}
function add_vendor_type_insert_history($vendor_type_id)
{

    $CI = &get_instance();
    $vendor_type_data_new = $CI->gm->get_selected_record('vendor_type', '*', $where = array('id' => $vendor_type_id, 'status !=' => 3), array());
    if (isset($vendor_type_data_new) && is_array($vendor_type_data_new) && count($vendor_type_data_new) > 0) {
        unset($vendor_type_data_new["id"]);
        unset($vendor_type_data_new["created_by"]);
        unset($vendor_type_data_new["created_date"]);
        unset($vendor_type_data_new["modified_by"]);
        unset($vendor_type_data_new["modified_date"]);
        foreach ($vendor_type_data_new as $lkey => $lvalue) {
            $new_data[$lkey] = $lvalue;
        }
    }
    $sessiondata = $CI->session->userdata('admin_user');
    $insert_data = array(
        'vendor_type_master_id' => $vendor_type_id,
        'new_data' => isset($new_data) ? json_encode($new_data) : '',
        'old_data' => isset($old_data) ? json_encode($old_data) : '',
        'created_date' => date('Y-m-d H:i:s'),
        'created_by' => $sessiondata['id'],
        'created_by_type' => $sessiondata['type'] == 'customer' ? 2 : 1
    );
    $CI->gm->insert('vendor_type_master_history', $insert_data);
}
function add_fsc_masters_insert_history($fsc_masters_id)
{

    $CI = &get_instance();
    $fsc_masters_data_new = $CI->gm->get_selected_record('fsc_masters', '*', $where = array('id' => $fsc_masters_id, 'status !=' => 3), array());
    if (isset($fsc_masters_data_new) && is_array($fsc_masters_data_new) && count($fsc_masters_data_new) > 0) {
        unset($fsc_masters_data_new["id"]);
        unset($fsc_masters_data_new["created_by"]);
        unset($fsc_masters_data_new["created_date"]);
        unset($fsc_masters_data_new["modified_by"]);
        unset($fsc_masters_data_new["modified_date"]);
        foreach ($fsc_masters_data_new as $lkey => $lvalue) {
            $new_data[$lkey] = $lvalue;
        }
    }
    $sessiondata = $CI->session->userdata('admin_user');
    $insert_data = array(
        'fsc_master_id' => $fsc_masters_id,
        'new_data' => isset($new_data) ? json_encode($new_data) : '',
        'old_data' => isset($old_data) ? json_encode($old_data) : '',
        'created_date' => date('Y-m-d H:i:s'),
        'created_by' => $sessiondata['id'],
        'created_by_type' => $sessiondata['type'] == 'customer' ? 2 : 1
    );
    $CI->gm->insert('fsc_masters_history', $insert_data);
}
function add_flight_master_insert_history($flight_master_id)
{

    $CI = &get_instance();
    $flight_masters_data_new = $CI->gm->get_selected_record('flight', '*', $where = array('id' => $flight_master_id, 'status !=' => 3), array());
    if (isset($flight_masters_data_new) && is_array($flight_masters_data_new) && count($flight_masters_data_new) > 0) {
        unset($flight_masters_data_new["id"]);
        unset($flight_masters_data_new["created_by"]);
        unset($flight_masters_data_new["created_date"]);
        unset($flight_masters_data_new["modified_by"]);
        unset($flight_masters_data_new["modified_date"]);
        foreach ($flight_masters_data_new as $lkey => $lvalue) {
            $new_data[$lkey] = $lvalue;
        }
    }
    $sessiondata = $CI->session->userdata('admin_user');
    $insert_data = array(
        'flight_master_id' => $flight_master_id,
        'new_data' => isset($new_data) ? json_encode($new_data) : '',
        'old_data' => isset($old_data) ? json_encode($old_data) : '',
        'created_date' => date('Y-m-d H:i:s'),
        'created_by' => $sessiondata['id'],
        'created_by_type' => $sessiondata['type'] == 'customer' ? 2 : 1
    );
    $CI->gm->insert('flight_masters_history', $insert_data);
}
function add_vendor_master_insert_history($vendor_master_id)
{

    $CI = &get_instance();
    $vendor_masters_data_new = $CI->gm->get_selected_record('vendor', '*', $where = array('id' => $vendor_master_id, 'status !=' => 3), array());
    if (isset($vendor_masters_data_new) && is_array($vendor_masters_data_new) && count($vendor_masters_data_new) > 0) {
        unset($vendor_masters_data_new["id"]);
        unset($vendor_masters_data_new["created_by"]);
        unset($vendor_masters_data_new["created_date"]);
        unset($vendor_masters_data_new["modified_by"]);
        unset($vendor_masters_data_new["modified_date"]);
        foreach ($vendor_masters_data_new as $lkey => $lvalue) {
            $new_data[$lkey] = $lvalue;
        }
    }
    $sessiondata = $CI->session->userdata('admin_user');
    $insert_data = array(
        'vendor_master_id' => $vendor_master_id,
        'new_data' => isset($new_data) ? json_encode($new_data) : '',
        'old_data' => isset($old_data) ? json_encode($old_data) : '',
        'created_date' => date('Y-m-d H:i:s'),
        'created_by' => $sessiondata['id'],
        'created_by_type' => $sessiondata['type'] == 'customer' ? 2 : 1
    );
    $CI->gm->insert('vendor_master_history', $insert_data);
}
function add_opening_balance_insert_history($opening_balance_id)
{

    $CI = &get_instance();
    $opening_balance_data_new = $CI->gm->get_selected_record('opening_balance', '*', $where = array('id' => $opening_balance_id, 'status !=' => 3), array());
    if (isset($opening_balance_data_new) && is_array($opening_balance_data_new) && count($opening_balance_data_new) > 0) {
        unset($opening_balance_data_new["id"]);
        unset($opening_balance_data_new["created_by"]);
        unset($opening_balance_data_new["created_date"]);
        unset($opening_balance_data_new["modified_by"]);
        unset($opening_balance_data_new["modified_date"]);
        foreach ($opening_balance_data_new as $lkey => $lvalue) {
            $new_data[$lkey] = $lvalue;
        }
    }
    $sessiondata = $CI->session->userdata('admin_user');
    $insert_data = array(
        'opening_balance_id' => $opening_balance_id,
        'new_data' => isset($new_data) ? json_encode($new_data) : '',
        'old_data' => isset($old_data) ? json_encode($old_data) : '',
        'created_date' => date('Y-m-d H:i:s'),
        'created_by' => $sessiondata['id'],
        'created_by_type' => $sessiondata['type'] == 'customer' ? 2 : 1
    );
    $CI->gm->insert('opening_balance_history', $insert_data);
}
function payment_receipt_insert_history($payment_receipt_id)
{

    $CI = &get_instance();
    $payment_receipt_data_new = $CI->gm->get_selected_record('payment_receipt', '*', $where = array('id' => $payment_receipt_id, 'status !=' => 3), array());
    if (isset($payment_receipt_data_new) && is_array($payment_receipt_data_new) && count($payment_receipt_data_new) > 0) {
        unset($payment_receipt_data_new["id"]);
        unset($payment_receipt_data_new["created_by"]);
        unset($payment_receipt_data_new["created_date"]);
        unset($payment_receipt_data_new["modified_by"]);
        unset($payment_receipt_data_new["modified_date"]);
        foreach ($payment_receipt_data_new as $lkey => $lvalue) {
            $new_data[$lkey] = $lvalue;
        }
    }
    $sessiondata = $CI->session->userdata('admin_user');
    $insert_data = array(
        'payment_receipt_id' => $payment_receipt_id,
        'new_data' => isset($new_data) ? json_encode($new_data) : '',
        'old_data' => isset($old_data) ? json_encode($old_data) : '',
        'created_date' => date('Y-m-d H:i:s'),
        'created_by' => $sessiondata['id'],
        'created_by_type' => $sessiondata['type'] == 'customer' ? 2 : 1
    );
    $CI->gm->insert('payment_receipt_history', $insert_data);
}

function get_customer_currency($customer_id = 0)
{
    $CI = &get_instance();
    $qry = "SELECT id,company_id,currency_id FROM customer WHERE id='" . $customer_id . "'";
    $qry_exe = $CI->db->query($qry);
    $customer_data = $qry_exe->row_array();

    $qry = "SELECT id,module_name,config_key,config_value FROM app_settings WHERE status IN(1,2) 
    AND config_key='get_invoice_currency_from_company'";
    $qry_exe = $CI->db->query($qry);
    $setting_data = $qry_exe->row_array();

    $currency_code = 'INR';

    if (isset($setting_data['config_value']) && $setting_data['config_value'] == 1) {
        //GET CURRENCY FROM CUSTOMER COMPANY
        if (isset($customer_data['company_id']) && $customer_data['company_id'] > 0) {
            $qry = "SELECT id,country FROM company_master WHERE id='" . $customer_data['company_id'] . "'";
            $qry_exe = $CI->db->query($qry);
            $company_data = $qry_exe->row_array();
        }

        if (isset($company_data['country']) && $company_data['country'] != '') {
            $qry = "SELECT id,currency_code_id FROM country WHERE name='" . $company_data['country'] . "'";
            $qry_exe = $CI->db->query($qry);
            $currency_data = $qry_exe->row_array();
        }

        if (isset($currency_data['currency_code_id']) && $currency_data['currency_code_id'] > 0) {
            $all_currency = get_all_currency();
            $currency_code = isset($all_currency[$currency_data['currency_code_id']]) ? $all_currency[$currency_data['currency_code_id']]['code'] : '';
        }
    } else {
        //GET CURRENCY FROM CUSTOMER
        if (isset($customer_data['currency_id']) && $customer_data['currency_id'] > 0) {
            $all_currency = get_all_currency();
            $currency_code = isset($all_currency[$customer_data['currency_id']]) ? $all_currency[$customer_data['currency_id']]['code'] : '';
        }
    }


    return strtoupper($currency_code);
}

function round_amount($amount = 0)
{
    $format_no = round($amount, 2);
    $format_no = floatval($format_no);
    return $format_no;
}
function get_customer_setting($customer_id = 0)
{
    $customer_setting = array();
    $CI = &get_instance();
    if (isset($customer_id) && $customer_id > 0) {
        $qry = "SELECT c.id,m.config_key,m.config_value FROM customer c 
        JOIN module_setting m ON(c.id=m.module_id AND m.module_type=1) 
        WHERE c.id='" . $customer_id . "' AND c.status IN(1,2) AND m.status IN(1,2) AND m.config_value=1";
        $qry_exe = $CI->db->query($qry);
        $customer_data = $qry_exe->result_array();
    }

    if (isset($customer_data) && is_array($customer_data) && count($customer_data) > 0) {
        foreach ($customer_data as $key => $value) {
            $customer_setting[$value['config_key']] = $value['config_value'];
        }
    }
    $sessiondata = $CI->session->userdata('admin_user');
    if ($sessiondata['type'] == 'customer') {
        //CHECK USER IS ADMIN
        $qry = "SELECT id,is_admin FROM customer_users WHERE id='" . $sessiondata['id'] . "' AND is_admin=1";
        $qry_exe = $CI->db->query($qry);
        $admin_data = $qry_exe->row_array();
        if (isset($admin_data) && is_array($admin_data) && count($admin_data) > 0) {
            $customer_setting['hub_wise_portal'] = 2;
        }
    }
    return $customer_setting;
}


function update_manifest_docket_detail($manifest_id = 0, $docket_id = 0)
{
    $CI = &get_instance();
    if (isset($manifest_id) && $manifest_id > 0) {
        $qry = "SELECT * FROM manifest_docket WHERE status=1 AND manifest_id='" . $manifest_id . "'";
        $qry_exe = $CI->db->query($qry);
        $manifest_data = $qry_exe->result_array();
    } else if (isset($docket_id) && $docket_id > 0) {
        $qry = "SELECT id,manifest_id FROM manifest_docket WHERE status=1 AND docket_id='" . $docket_id . "'";
        $qry_exe = $CI->db->query($qry);
        $manifest_docket_data = $qry_exe->result_array();

        if (isset($manifest_docket_data) && is_array($manifest_docket_data) && count($manifest_docket_data) > 0) {
            foreach ($manifest_docket_data as $key => $value) {
                $mani_ids[$value['manifest_id']] = $value['manifest_id'];
            }
        }

        if (isset($mani_ids) && is_array($mani_ids) && count($mani_ids) > 0) {
            $qry = "SELECT * FROM manifest_docket WHERE status=1 AND manifest_id IN(" . implode(",", $mani_ids) . ")";
            $qry_exe = $CI->db->query($qry);
            $manifest_data = $qry_exe->result_array();
        }
    }

    if (isset($manifest_data) && is_array($manifest_data) && count($manifest_data) > 0) {
        foreach ($manifest_data as $key => $value) {
            $manifest_docket[$value['manifest_id']][] = $value;
        }
    }

    if (isset($manifest_docket) && is_array($manifest_docket) && count($manifest_docket) > 0) {
        foreach ($manifest_docket as $mkey => $mvalue) {
            $total_actual_wt = 0;
            $total_volumetric_wt = 0;
            $total_chargeable_wt = 0;
            $sales_total = 0;
            $sales_gst = 0;
            $purchase_total = 0;
            $purchase_gst = 0;
            $parcel_ids = array();
            $docket_ids = array();
            $billing_ids = array();
            foreach ($mvalue as $dkey => $dvalue) {
                if ($dvalue['track_by_type'] == 2) {
                    //PARCEL WISE DATA
                    $parcel_ids[$dvalue['docket_item_id']] = $dvalue['docket_item_id'];
                } else {
                    $docket_ids[$dvalue['docket_id']] = $dvalue['docket_id'];
                }
                $billing_ids[$dvalue['docket_id']] = $dvalue['docket_id'];
            }

            if (isset($parcel_ids) && is_array($parcel_ids) && count($parcel_ids) > 0) {
                $qry = "SELECT SUM(i.actual_wt) as actual_wt,SUM(i.volumetric_wt) as volumetric_wt,SUM(i.chargeable_wt) as chargeable_wt
                 FROM docket_items i JOIN docket d ON(d.id=i.docket_id)
                WHERE d.status=1 AND i.status=1 AND i.id IN(" . implode(",", $parcel_ids) . ")";
                $qry_exe = $CI->db->query($qry);
                $parcel_wt = $qry_exe->row_array();

                $total_actual_wt += isset($parcel_wt['actual_wt']) && $parcel_wt['actual_wt'] != '' ? $parcel_wt['actual_wt'] : 0;
                $total_volumetric_wt += isset($parcel_wt['volumetric_wt']) && $parcel_wt['volumetric_wt'] != '' ? $parcel_wt['volumetric_wt'] : 0;
                $total_chargeable_wt += isset($parcel_wt['chargeable_wt']) && $parcel_wt['chargeable_wt'] != '' ? $parcel_wt['chargeable_wt'] : 0;
            }

            if (isset($docket_ids) && is_array($docket_ids) && count($docket_ids) > 0) {
                $qry = "SELECT SUM(actual_wt) as actual_wt,SUM(volumetric_wt) as volumetric_wt,SUM(chargeable_wt) as chargeable_wt
                FROM docket d WHERE d.status=1 AND d.id IN(" . implode(",", $docket_ids) . ")";
                $qry_exe = $CI->db->query($qry);
                $docket_wt = $qry_exe->row_array();

                $total_actual_wt += isset($docket_wt['actual_wt']) && $docket_wt['actual_wt'] != '' ? $docket_wt['actual_wt'] : 0;
                $total_volumetric_wt += isset($docket_wt['volumetric_wt']) && $docket_wt['volumetric_wt'] != '' ? $docket_wt['volumetric_wt'] : 0;
                $total_chargeable_wt += isset($docket_wt['chargeable_wt']) && $docket_wt['chargeable_wt'] != '' ? $docket_wt['chargeable_wt'] : 0;
            }

            if (isset($billing_ids) && is_array($billing_ids) && count($billing_ids) > 0) {
                $qry = "SELECT SUM(i.igst_amount) as igst_amount,SUM(i.cgst_amount) as cgst_amount,
                SUM(i.sgst_amount) as sgst_amount,SUM(i.grand_total) as grand_total
                 FROM docket_sales_billing i JOIN docket d ON(d.id=i.docket_id)
                WHERE d.status=1 AND i.status=1 AND i.docket_id IN(" . implode(",", $billing_ids) . ")";
                $qry_exe = $CI->db->query($qry);
                $docket_wt = $qry_exe->row_array();

                $sales_gst += isset($docket_wt['igst_amount']) && $docket_wt['igst_amount'] != '' ? $docket_wt['igst_amount'] : 0;
                $sales_gst += isset($docket_wt['cgst_amount']) && $docket_wt['cgst_amount'] != '' ? $docket_wt['cgst_amount'] : 0;
                $sales_gst += isset($docket_wt['sgst_amount']) && $docket_wt['sgst_amount'] != '' ? $docket_wt['sgst_amount'] : 0;
                $sales_total += isset($docket_wt['grand_total']) && $docket_wt['grand_total'] != '' ? $docket_wt['grand_total'] : 0;

                $qry = "SELECT SUM(i.igst_amount) as igst_amount,SUM(i.cgst_amount) as cgst_amount,
                SUM(i.sgst_amount) as sgst_amount,SUM(i.grand_total) as grand_total
                 FROM docket_purchase_billing i JOIN docket d ON(d.id=i.docket_id)
                WHERE d.status=1 AND i.status=1 AND i.docket_id IN(" . implode(",", $billing_ids) . ")";
                $qry_exe = $CI->db->query($qry);
                $docket_wt = $qry_exe->row_array();

                $purchase_gst += isset($docket_wt['igst_amount']) && $docket_wt['igst_amount'] != '' ? $docket_wt['igst_amount'] : 0;
                $purchase_gst += isset($docket_wt['cgst_amount']) && $docket_wt['cgst_amount'] != '' ? $docket_wt['cgst_amount'] : 0;
                $purchase_gst += isset($docket_wt['sgst_amount']) && $docket_wt['sgst_amount'] != '' ? $docket_wt['sgst_amount'] : 0;
                $purchase_total += isset($docket_wt['grand_total']) && $docket_wt['grand_total'] != '' ? $docket_wt['grand_total'] : 0;
            }

            $qry = "SELECT SUM(charge_amount) as charge_amount
            FROM manifest_charge_amt d WHERE d.status=1 AND d.manifest_id ='" . $mkey . "'";
            $qry_exe = $CI->db->query($qry);
            $docket_wt = $qry_exe->row_array();
            $sales_total += isset($docket_wt['charge_amount']) && $docket_wt['charge_amount'] != '' ? $docket_wt['charge_amount'] : 0;

            //UPDATE DATA
            $updateq = "UPDATE manifest SET total_actual_wt='" . $total_actual_wt . "',total_volumetric_wt='" . $total_volumetric_wt . "',
            total_chargeable_wt='" . $total_chargeable_wt . "',sales_total='" . ($sales_total - $sales_gst) . "',
            purchase_total='" . ($purchase_total - $purchase_gst) . "' WHERE id='" . $mkey . "'";

            $CI->db->query($updateq);
        }
    }
}



if (!function_exists('get_all_allocated_ticket')) {
    function get_all_allocated_ticket($where = '')
    {

        $CI = &get_instance();
        $final_data = array();
        $sessiondata = $CI->session->userdata('admin_user');
        if ($sessiondata['type'] != 'customer') {
            $qry = "SELECT * from ticket WHERE status IN(1,2) 
            AND allocated_to='" . $sessiondata['id'] . "' AND sw_status!=4 " . $where;
            $qry_exe = $CI->db->query($qry);
            $result = $qry_exe->result_array();
            if (isset($result) && is_array($result) && count($result) > 0) {
                foreach ($result as $key => $value) {
                    $final_data[$value['id']] = $value;
                }
            }
        }

        return $final_data;
    }
}

function get_customer_opening_balance_date($customer_id = 0)
{
    $CI = &get_instance();
    if ($customer_id > 0) {
        $qry = "SELECT id,opening_date FROM opening_balance WHERE status=1 
        AND customer_id ='" . $customer_id . "' ORDER BY opening_date DESC";
        $qry_exe = $CI->db->query($qry);
        $result = $qry_exe->row_array();
    }

    $opening_date = isset($result['opening_date']) ? $result['opening_date'] : '';
    return $opening_date;
}

function get_all_customer_opening_balance_date()
{
    $CI = &get_instance();
    $opening_date = array();

    $qry = "SELECT id,opening_date,customer_id FROM opening_balance WHERE status=1 
        GROUP BY customer_id";
    $qry_exe = $CI->db->query($qry);
    $result = $qry_exe->result_array();


    if (isset($result) && is_array($result) && count($result) > 0) {
        foreach ($result as $key => $value) {
            $opening_date[$value['customer_id']] = $value['opening_date'];
        }
    }
    return $opening_date;
}
