<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function get_sms_credentials()
{
    $CI = &get_instance();
    $sms_credential = array();
    $qry = "SELECT config_key,config_value FROM app_settings WHERE module_name='sms'";
    $qry_exe = $CI->db->query($qry);
    $setting_data = $qry_exe->result_array();
    if (isset($setting_data) && is_array($setting_data) && count($setting_data) > 0) {
        foreach ($setting_data as $skey => $svalue) {
            $sms_credential[$svalue['config_key']] = $svalue['config_value'];
        }
    }

    return $sms_credential;
}

function get_whatsapp_credentials()
{
    $CI = &get_instance();
    $qry = "SELECT config_key,config_value FROM app_settings WHERE config_key='whatsapp_sms_api_key'";
    $qry_exe = $CI->db->query($qry);
    $setting_data = $qry_exe->row_array();
    $api_key = isset($setting_data['config_value']) ? $setting_data['config_value'] : '';
    return $api_key;
}

function send_booking_sms($docket_id = 0)
{
    $CI = &get_instance();
    $response = array();
    if ($docket_id > 0) {
        $sms_credential = get_sms_credentials();
        $username = isset($sms_credential['sms_username']) ? $sms_credential['sms_username'] : '';
        $sender_id = isset($sms_credential['sms_sender_id']) ? $sms_credential['sms_sender_id'] : '';
        $api_key = isset($sms_credential['sms_api_key']) ? $sms_credential['sms_api_key'] : '';
        $peid = isset($sms_credential['sms_peid']) ? $sms_credential['sms_peid'] : '';
        if ($username != '' && $sender_id != '' && $api_key != '' && $peid != '') {
            $qry = "SELECT id,customer_id FROM docket WHERE id='" . $docket_id . "'";
            $qry_exe = $CI->db->query($qry);
            $docket_data = $qry_exe->row_array();

            if (isset($docket_data['customer_id']) && $docket_data['customer_id'] > 0) {
                $qry = "SELECT c.id,m.config_key,m.config_value FROM customer c 
                JOIN module_setting m ON(c.id=m.module_id AND m.module_type=1) 
                WHERE c.id='" . $docket_data['customer_id'] . "' AND c.status IN(1,2) AND m.status IN(1,2)";
                $qry_exe = $CI->db->query($qry);
                $customer_data = $qry_exe->result_array();
            }

            if (isset($customer_data) && is_array($customer_data) && count($customer_data) > 0) {
                foreach ($customer_data as $key => $value) {
                    $customer_setting[$value['config_key']] = $value['config_value'];
                }
            }

            if (isset($customer_setting['customer_booking_sms']) && $customer_setting['customer_booking_sms'] == 1) {
                $phone_no = get_phone_number('customer', $docket_data['customer_id']);
                $response =  get_msg_template('customer_booking_sms', 'sms', $docket_id, 1, $phone_no);
            }

            if (isset($customer_setting['shipper_booking_sms']) && $customer_setting['shipper_booking_sms'] == 1) {
                $phone_no = get_phone_number('docket_shipper', $docket_id);
                $response = get_msg_template('shipper_booking_sms', 'sms', $docket_id, 1, $phone_no);
            }

            if (isset($customer_setting['consignee_booking_sms']) && $customer_setting['consignee_booking_sms'] == 1) {
                $phone_no = get_phone_number('docket_consignee', $docket_id);
                $response =   get_msg_template('consignee_booking_sms', 'sms', $docket_id, 1, $phone_no);
            }
        }
    }

    return $response;
}



function send_delivery_sms($docket_id = 0)
{
    $CI = &get_instance();
    if ($docket_id > 0) {
        $sms_credential = get_sms_credentials();
        $username = isset($sms_credential['sms_username']) ? $sms_credential['sms_username'] : '';
        $sender_id = isset($sms_credential['sms_sender_id']) ? $sms_credential['sms_sender_id'] : '';
        $api_key = isset($sms_credential['sms_api_key']) ? $sms_credential['sms_api_key'] : '';
        $peid = isset($sms_credential['sms_peid']) ? $sms_credential['sms_peid'] : '';
        if ($username != '' && $sender_id != '' && $api_key != '' && $peid != '') {
            $qry = "SELECT id,customer_id FROM docket WHERE id='" . $docket_id . "'";
            $qry_exe = $CI->db->query($qry);
            $docket_data = $qry_exe->row_array();

            if (isset($docket_data['customer_id']) && $docket_data['customer_id'] > 0) {
                $qry = "SELECT c.id,m.config_key,m.config_value FROM customer c 
                JOIN module_setting m ON(c.id=m.module_id AND m.module_type=1) 
                WHERE c.id='" . $docket_data['customer_id'] . "' AND c.status IN(1,2) AND m.status IN(1,2)";
                $qry_exe = $CI->db->query($qry);
                $customer_data = $qry_exe->result_array();
            }

            if (isset($customer_data) && is_array($customer_data) && count($customer_data) > 0) {
                foreach ($customer_data as $key => $value) {
                    $customer_setting[$value['config_key']] = $value['config_value'];
                }
            }

            if (isset($customer_setting['customer_delivery_sms']) && $customer_setting['customer_delivery_sms'] == 1) {
                $phone_no = get_phone_number('customer', $docket_data['customer_id']);
                get_msg_template('customer_delivery_sms', 'sms', $docket_id, 1, $phone_no);
            }

            if (isset($customer_setting['shipper_delivery_sms']) && $customer_setting['shipper_delivery_sms'] == 1) {
                $phone_no = get_phone_number('docket_shipper', $docket_id);
                get_msg_template('shipper_delivery_sms', 'sms', $docket_id, 1, $phone_no);
            }

            if (isset($customer_setting['consignee_delivery_sms']) && $customer_setting['consignee_delivery_sms'] == 1) {
                $phone_no = get_phone_number('docket_consignee', $docket_id);
                get_msg_template('consignee_delivery_sms', 'sms', $docket_id, 1, $phone_no);
            }
        }
    }
}

function send_pickup_request_sms($pickup_request_id = 0)
{
    $CI = &get_instance();
    if ($pickup_request_id > 0) {
        $sms_credential = get_sms_credentials();
        $username = isset($sms_credential['sms_username']) ? $sms_credential['sms_username'] : '';
        $sender_id = isset($sms_credential['sms_sender_id']) ? $sms_credential['sms_sender_id'] : '';
        $api_key = isset($sms_credential['sms_api_key']) ? $sms_credential['sms_api_key'] : '';
        $peid = isset($sms_credential['sms_peid']) ? $sms_credential['sms_peid'] : '';
        if ($username != '' && $sender_id != '' && $api_key != '' && $peid != '') {
            $qry = "SELECT id,customer_id FROM pickup_request WHERE id='" . $pickup_request_id . "'";
            $qry_exe = $CI->db->query($qry);
            $docket_data = $qry_exe->row_array();

            if (isset($docket_data['customer_id']) && $docket_data['customer_id'] > 0) {
                $qry = "SELECT c.id,m.config_key,m.config_value FROM customer c 
                JOIN module_setting m ON(c.id=m.module_id AND m.module_type=1) 
                WHERE c.id='" . $docket_data['customer_id'] . "' AND c.status IN(1,2) AND m.status IN(1,2)";
                $qry_exe = $CI->db->query($qry);
                $customer_data = $qry_exe->result_array();
            }

            if (isset($customer_data) && is_array($customer_data) && count($customer_data) > 0) {
                foreach ($customer_data as $key => $value) {
                    $customer_setting[$value['config_key']] = $value['config_value'];
                }
            }

            if (isset($customer_setting['customer_pickup_sms']) && $customer_setting['customer_pickup_sms'] == 1) {
                $phone_no = get_phone_number('customer', $docket_data['customer_id']);
                get_msg_template('customer_pickup_sms', 'sms', $pickup_request_id, 4, $phone_no);
            }

            if (isset($customer_setting['shipper_pickup_sms']) && $customer_setting['shipper_pickup_sms'] == 1) {
                $phone_no = get_phone_number('pickup_request_shipper', $pickup_request_id);
                get_msg_template('shipper_pickup_sms', 'sms', $pickup_request_id, 4, $phone_no);
            }

            if (isset($customer_setting['consignee_pickup_sms']) && $customer_setting['consignee_pickup_sms'] == 1) {
                $phone_no = get_phone_number('pickup_request_consignee', $pickup_request_id);
                get_msg_template('consignee_pickup_sms', 'sms', $pickup_request_id, 4, $phone_no);
            }
        }
    }
}

function send_pickup_sheet_sms($pickup_request_id = 0)
{
    $CI = &get_instance();
    if ($pickup_request_id > 0) {
        $sms_credential = get_sms_credentials();
        $username = isset($sms_credential['sms_username']) ? $sms_credential['sms_username'] : '';
        $sender_id = isset($sms_credential['sms_sender_id']) ? $sms_credential['sms_sender_id'] : '';
        $api_key = isset($sms_credential['sms_api_key']) ? $sms_credential['sms_api_key'] : '';
        $peid = isset($sms_credential['sms_peid']) ? $sms_credential['sms_peid'] : '';
        if ($username != '' && $sender_id != '' && $api_key != '' && $peid != '') {
            $qry = "SELECT id,customer_id FROM pickup_request WHERE id='" . $pickup_request_id . "'";
            $qry_exe = $CI->db->query($qry);
            $docket_data = $qry_exe->row_array();

            if (isset($docket_data['customer_id']) && $docket_data['customer_id'] > 0) {
                $qry = "SELECT c.id,m.config_key,m.config_value FROM customer c 
                JOIN module_setting m ON(c.id=m.module_id AND m.module_type=1) 
                WHERE c.id='" . $docket_data['customer_id'] . "' AND c.status IN(1,2) AND m.status IN(1,2)";
                $qry_exe = $CI->db->query($qry);
                $customer_data = $qry_exe->result_array();
            }

            if (isset($customer_data) && is_array($customer_data) && count($customer_data) > 0) {
                foreach ($customer_data as $key => $value) {
                    $customer_setting[$value['config_key']] = $value['config_value'];
                }
            }

            if (isset($customer_setting['customer_pickup_sheet_sms']) && $customer_setting['customer_pickup_sheet_sms'] == 1) {
                $phone_no = get_phone_number('customer', $docket_data['customer_id']);
                get_msg_template('customer_pickup_sheet_sms', 'sms', $pickup_request_id, 4, $phone_no);
            }

            if (isset($customer_setting['shipper_pickup_sheet_sms']) && $customer_setting['shipper_pickup_sheet_sms'] == 1) {
                $phone_no = get_phone_number('pickup_request_shipper', $pickup_request_id);
                get_msg_template('shipper_pickup_sheet_sms', 'sms', $pickup_request_id, 4, $phone_no);
            }

            if (isset($customer_setting['consignee_pickup_sheet_sms']) && $customer_setting['consignee_pickup_sheet_sms'] == 1) {
                $phone_no = get_phone_number('pickup_request_consignee', $pickup_request_id);
                get_msg_template('consignee_pickup_sheet_sms', 'sms', $pickup_request_id, 4, $phone_no);
            }
        }
    }
}
function send_booking_whatsapp($docket_id = 0, $attachment_path = "", $is_manual = 0)
{
    $CI = &get_instance();
    if ($docket_id > 0) {
        $api_key = get_whatsapp_credentials();

        if ($api_key != '') {
            $qry = "SELECT id,customer_id FROM docket WHERE id='" . $docket_id . "'";
            $qry_exe = $CI->db->query($qry);
            $docket_data = $qry_exe->row_array();
            // $setting_qry = "SELECT id,module_name,config_key,config_value FROM app_settings WHERE status IN(1,2) AND module_name='docket' AND config_key='allow_manual_whatsapp_trigger_option'";
            // $setting_qry_exe = $CI->db->query($setting_qry);
            // $docket_setting = $setting_qry_exe->row_array();
            if (isset($docket_data['customer_id']) && $docket_data['customer_id'] > 0) {
                $qry = "SELECT c.id,m.config_key,m.config_value FROM customer c 
                JOIN module_setting m ON(c.id=m.module_id AND m.module_type=1) 
                WHERE c.id='" . $docket_data['customer_id'] . "' AND c.status IN(1,2) AND m.status IN(1,2)";
                $qry_exe = $CI->db->query($qry);
                $customer_data = $qry_exe->result_array();
            }

            if (isset($customer_data) && is_array($customer_data) && count($customer_data) > 0) {
                foreach ($customer_data as $key => $value) {
                    $customer_setting[$value['config_key']] = $value['config_value'];
                }
            }

            if (isset($customer_setting['customer_booking_whatsapp']) && $customer_setting['customer_booking_whatsapp'] == 1) {
                $phone_no = get_phone_number('customer', $docket_data['customer_id'], 'whatsapp');
                if (isset($customer_setting["send_booking_whatsapp_with_attachment_customer"]) && $customer_setting["send_booking_whatsapp_with_attachment_customer"] == 1) {
                    get_msg_template('customer_booking_whatsapp', 'whatsapp', $docket_id, 1, $phone_no, $attachment_path, '', '');
                } else {
                    get_msg_template('customer_booking_whatsapp', 'whatsapp', $docket_id, 1, $phone_no, "", '', '');
                }
            } else if (isset($customer_setting["send_awb_pdf_on_whatsapp_to_customer"]) && $customer_setting["send_awb_pdf_on_whatsapp_to_customer"] == 1 && $is_manual != 0) {
                $phone_no = get_phone_number('customer', $docket_data['customer_id'], 'whatsapp');
                get_msg_template('customer_booking_whatsapp', 'whatsapp', $docket_id, 1, $phone_no, $attachment_path, '', '');
            }

            if (isset($customer_setting['shipper_booking_whatsapp']) && $customer_setting['shipper_booking_whatsapp'] == 1) {
                $phone_no = get_phone_number('docket_shipper', $docket_id, 'whatsapp');
                if (isset($customer_setting["send_booking_whatsapp_with_attachment_shipper"]) && $customer_setting["send_booking_whatsapp_with_attachment_shipper"] == 1) {
                    get_msg_template('shipper_booking_whatsapp', 'whatsapp', $docket_id, 1, $phone_no, $attachment_path, '', '');
                } else {
                    get_msg_template('shipper_booking_whatsapp', 'whatsapp', $docket_id, 1, $phone_no, "", '', '');
                }
            } else if (isset($customer_setting["send_awb_pdf_on_whatsapp_to_shipper"]) && $customer_setting["send_awb_pdf_on_whatsapp_to_shipper"] == 1 && $is_manual != 0) {
                $phone_no = get_phone_number('docket_shipper', $docket_id, 'whatsapp');
                get_msg_template('shipper_booking_whatsapp', 'whatsapp', $docket_id, 1, $phone_no, $attachment_path, '', '');
            }

            if (isset($customer_setting['consignee_booking_whatsapp']) && $customer_setting['consignee_booking_whatsapp'] == 1) {
                $phone_no = get_phone_number('docket_consignee', $docket_id, 'whatsapp');
                if (isset($customer_setting["send_booking_whatsapp_with_attachment_consignee"]) && $customer_setting["send_booking_whatsapp_with_attachment_consignee"] == 1) {
                    get_msg_template('consignee_booking_whatsapp', 'whatsapp', $docket_id, 1, $phone_no, $attachment_path, '', '');
                } else {
                    get_msg_template('consignee_booking_whatsapp', 'whatsapp', $docket_id, 1, $phone_no, "", '', '');
                }
            } else if (isset($customer_setting["send_awb_pdf_on_whatsapp_to_consignee"]) && $customer_setting["send_awb_pdf_on_whatsapp_to_consignee"] == 1 && $is_manual != 0) {
                $phone_no = get_phone_number('docket_consignee', $docket_id, 'whatsapp');
                get_msg_template('consignee_booking_whatsapp', 'whatsapp', $docket_id, 1, $phone_no, $attachment_path, '', '');
            }
        }
    }
}
function send_delivery_whatsapp($docket_id = 0)
{
    $CI = &get_instance();
    if ($docket_id > 0) {
        $api_key = get_whatsapp_credentials();

        if ($api_key != '') {
            $qry = "SELECT id,customer_id FROM docket WHERE id='" . $docket_id . "'";
            $qry_exe = $CI->db->query($qry);
            $docket_data = $qry_exe->row_array();

            if (isset($docket_data['customer_id']) && $docket_data['customer_id'] > 0) {
                $qry = "SELECT c.id,m.config_key,m.config_value FROM customer c 
                JOIN module_setting m ON(c.id=m.module_id AND m.module_type=1) 
                WHERE c.id='" . $docket_data['customer_id'] . "' AND c.status IN(1,2) AND m.status IN(1,2)";
                $qry_exe = $CI->db->query($qry);
                $customer_data = $qry_exe->result_array();
            }

            if (isset($customer_data) && is_array($customer_data) && count($customer_data) > 0) {
                foreach ($customer_data as $key => $value) {
                    $customer_setting[$value['config_key']] = $value['config_value'];
                }
            }

            if (isset($customer_setting['customer_delivery_whatsapp']) && $customer_setting['customer_delivery_whatsapp'] == 1) {
                $phone_no = get_phone_number('customer', $docket_data['customer_id'], 'whatsapp');
                get_msg_template('customer_delivery_whatsapp', 'whatsapp', $docket_id, 1, $phone_no);
            }

            if (isset($customer_setting['shipper_delivery_whatsapp']) && $customer_setting['shipper_delivery_whatsapp'] == 1) {
                $phone_no = get_phone_number('docket_shipper', $docket_id, 'whatsapp');
                get_msg_template('shipper_delivery_whatsapp', 'whatsapp', $docket_id, 1, $phone_no);
            }

            if (isset($customer_setting['consignee_delivery_whatsapp']) && $customer_setting['consignee_delivery_whatsapp'] == 1) {
                $phone_no = get_phone_number('docket_consignee', $docket_id, 'whatsapp');
                get_msg_template('consignee_delivery_whatsapp', 'whatsapp', $docket_id, 1, $phone_no);
            }
        }
    }
}

function send_vat_invoice_whatsapp($docket_id = 0, $phone_no = 0, $pdf_path = "")
{
    $CI = &get_instance();
    if ($docket_id > 0) {
        $api_key = get_whatsapp_credentials();

        if ($api_key != '') {
            $qry = "SELECT id,customer_id FROM docket WHERE id='" . $docket_id . "'";
            $qry_exe = $CI->db->query($qry);
            $docket_data = $qry_exe->row_array();

            $qry_con = "SELECT dial_code FROM docket_consignee WHERE docket_id='" . $docket_id . "'";
            $qry_exe = $CI->db->query($qry_con);
            $docket_con_data = $qry_exe->row_array();
            if (isset($docket_con_data['dial_code'])) {
                $phone_no = $docket_con_data['dial_code'] . $phone_no;
            }
            if (isset($docket_data['customer_id']) && $docket_data['customer_id'] > 0) {
                $qry = "SELECT c.id,m.config_key,m.config_value FROM customer c 
                JOIN module_setting m ON(c.id=m.module_id AND m.module_type=1) 
                WHERE c.id='" . $docket_data['customer_id'] . "' AND c.status IN(1,2) AND m.status IN(1,2)";
                $qry_exe = $CI->db->query($qry);
                $customer_data = $qry_exe->result_array();
            }
            send_whatsapp_msg("", $phone_no, $docket_data['customer_id'], $pdf_path);
        }
    }
}

function send_pickup_request_whatsapp($pickup_request_id = 0)
{
    $CI = &get_instance();
    if ($pickup_request_id > 0) {
        $api_key = get_whatsapp_credentials();

        if ($api_key != '') {
            $qry = "SELECT id,customer_id FROM pickup_request WHERE id='" . $pickup_request_id . "'";
            $qry_exe = $CI->db->query($qry);
            $docket_data = $qry_exe->row_array();

            if (isset($docket_data['customer_id']) && $docket_data['customer_id'] > 0) {
                $qry = "SELECT c.id,m.config_key,m.config_value FROM customer c 
                JOIN module_setting m ON(c.id=m.module_id AND m.module_type=1) 
                WHERE c.id='" . $docket_data['customer_id'] . "' AND c.status IN(1,2) AND m.status IN(1,2)";
                $qry_exe = $CI->db->query($qry);
                $customer_data = $qry_exe->result_array();
            }

            if (isset($customer_data) && is_array($customer_data) && count($customer_data) > 0) {
                foreach ($customer_data as $key => $value) {
                    $customer_setting[$value['config_key']] = $value['config_value'];
                }
            }

            if (isset($customer_setting['customer_pickup_whatsapp']) && $customer_setting['customer_pickup_whatsapp'] == 1) {
                $phone_no = get_phone_number('customer', $docket_data['customer_id'], 'whatsapp');
                get_msg_template('customer_pickup_whatsapp', 'whatsapp', $pickup_request_id, 4, $phone_no);
            }

            if (isset($customer_setting['shipper_pickup_whatsapp']) && $customer_setting['shipper_pickup_whatsapp'] == 1) {
                $phone_no = get_phone_number('pickup_request_shipper', $pickup_request_id, 'whatsapp');
                get_msg_template('shipper_pickup_whatsapp', 'whatsapp', $pickup_request_id, 4, $phone_no);
            }

            if (isset($customer_setting['consignee_pickup_whatsapp']) && $customer_setting['consignee_pickup_whatsapp'] == 1) {
                $phone_no = get_phone_number('pickup_request_consignee', $pickup_request_id, 'whatsapp');
                get_msg_template('consignee_pickup_whatsapp', 'whatsapp', $pickup_request_id, 4, $phone_no);
            }
        }
    }
}

function send_pickup_sheet_whatsapp($pickup_request_id = 0, $sheet_id = 0)
{

    $CI = &get_instance();
    if ($pickup_request_id > 0) {
        $api_key = get_whatsapp_credentials();

        if ($api_key != '') {
            $qry = "SELECT id,customer_id FROM pickup_request WHERE id='" . $pickup_request_id . "'";
            $qry_exe = $CI->db->query($qry);
            $docket_data = $qry_exe->row_array();

            if (isset($docket_data['customer_id']) && $docket_data['customer_id'] > 0) {
                $qry = "SELECT c.id,m.config_key,m.config_value FROM customer c 
                JOIN module_setting m ON(c.id=m.module_id AND m.module_type=1) 
                WHERE c.id='" . $docket_data['customer_id'] . "' AND c.status IN(1,2) AND m.status IN(1,2)";
                $qry_exe = $CI->db->query($qry);
                $customer_data = $qry_exe->result_array();
            }

            if (isset($customer_data) && is_array($customer_data) && count($customer_data) > 0) {
                foreach ($customer_data as $key => $value) {
                    $customer_setting[$value['config_key']] = $value['config_value'];
                }
            }

            if (isset($customer_setting['customer_pickup_sheet_whatsapp']) && $customer_setting['customer_pickup_sheet_whatsapp'] == 1) {
                $phone_no = get_phone_number('customer', $docket_data['customer_id'], 'whatsapp');
                get_msg_template('customer_pickup_sheet_whatsapp', 'whatsapp', $pickup_request_id, 4, $phone_no, '', '', $sheet_id);
            }

            if (isset($customer_setting['shipper_pickup_sheet_whatsapp']) && $customer_setting['shipper_pickup_sheet_whatsapp'] == 1) {
                $phone_no = get_phone_number('pickup_request_shipper', $pickup_request_id, 'whatsapp');
                get_msg_template('shipper_pickup_sheet_whatsapp', 'whatsapp', $pickup_request_id, 4, $phone_no, '', '', $sheet_id);
            }

            if (isset($customer_setting['consignee_pickup_sheet_whatsapp']) && $customer_setting['consignee_pickup_sheet_whatsapp'] == 1) {
                $phone_no = get_phone_number('pickup_request_consignee', $pickup_request_id, 'whatsapp');
                get_msg_template('consignee_pickup_sheet_whatsapp', 'whatsapp', $pickup_request_id, 4, $phone_no, '', '', $sheet_id);
            }
        }
    }
}


function send_pickup_done_whatsapp($pickup_request_id = 0, $sheet_id = 0)
{
    $CI = &get_instance();
    if ($pickup_request_id > 0) {
        $api_key = get_whatsapp_credentials();

        if ($api_key != '') {
            $qry = "SELECT id,customer_id FROM pickup_request WHERE id='" . $pickup_request_id . "'";
            $qry_exe = $CI->db->query($qry);
            $docket_data = $qry_exe->row_array();

            if (isset($docket_data['customer_id']) && $docket_data['customer_id'] > 0) {
                $qry = "SELECT c.id,m.config_key,m.config_value FROM customer c 
                JOIN module_setting m ON(c.id=m.module_id AND m.module_type=1) 
                WHERE c.id='" . $docket_data['customer_id'] . "' AND c.status IN(1,2) AND m.status IN(1,2)";
                $qry_exe = $CI->db->query($qry);
                $customer_data = $qry_exe->result_array();
            }

            if (isset($customer_data) && is_array($customer_data) && count($customer_data) > 0) {
                foreach ($customer_data as $key => $value) {
                    $customer_setting[$value['config_key']] = $value['config_value'];
                }
            }

            if (isset($customer_setting['pickup_done_whatsapp_to_customer']) && $customer_setting['pickup_done_whatsapp_to_customer'] == 1) {
                $phone_no = get_phone_number('customer', $docket_data['customer_id'], 'whatsapp');
                get_msg_template('pickup_done_whatsapp_to_customer', 'whatsapp', $pickup_request_id, 4, $phone_no, '', '', $sheet_id);
            }

            if (isset($customer_setting['pickup_done_whatsapp_to_pickup']) && $customer_setting['pickup_done_whatsapp_to_pickup'] == 1) {
                $phone_no = get_phone_number('pickup_request_shipper', $pickup_request_id, 'whatsapp');
                get_msg_template('pickup_done_whatsapp_to_pickup', 'whatsapp', $pickup_request_id, 4, $phone_no, '', '', $sheet_id);
            }

            if (isset($customer_setting['pickup_done_whatsapp_to_consignee']) && $customer_setting['pickup_done_whatsapp_to_consignee'] == 1) {
                $phone_no = get_phone_number('pickup_request_consignee', $pickup_request_id, 'whatsapp');
                get_msg_template('pickup_done_whatsapp_to_consignee', 'whatsapp', $pickup_request_id, 4, $phone_no, '', '', $sheet_id);
            }
        }
    }
}


function send_booking_email($docket_id = 0)
{
    $CI = &get_instance();
    if ($docket_id > 0) {

        $qry = "SELECT id,customer_id FROM docket WHERE id='" . $docket_id . "'";
        $qry_exe = $CI->db->query($qry);
        $docket_data = $qry_exe->row_array();

        if (isset($docket_data['customer_id']) && $docket_data['customer_id'] > 0) {

            $qry = "SELECT c.id,c.send_booking_docket FROM customer c 
                WHERE c.id='" . $docket_data['customer_id'] . "' AND c.status IN(1,2)";
            $qry_exe = $CI->db->query($qry);
            $customer_main_data = $qry_exe->row_array();

            $qry = "SELECT c.id,m.config_key,m.config_value FROM customer c 
                JOIN module_setting m ON(c.id=m.module_id AND m.module_type=1) 
                WHERE c.id='" . $docket_data['customer_id'] . "' AND c.status IN(1,2) AND m.status IN(1,2)";
            $qry_exe = $CI->db->query($qry);
            $customer_data = $qry_exe->result_array();
        }

        if (isset($customer_data) && is_array($customer_data) && count($customer_data) > 0) {
            foreach ($customer_data as $key => $value) {
                $customer_setting[$value['config_key']] = $value['config_value'];
            }
        }

        if (isset($customer_main_data['send_booking_docket']) && $customer_main_data['send_booking_docket'] == 1) {
            $phone_no = get_phone_number('customer', $docket_data['customer_id'], 'email');
            $email_response =  get_msg_template('customer_booking_email', 'email', $docket_id, 1, $phone_no);
            if (isset($email_response['error'])) {
                $response['customer_error'] = 'CUSTOMER ' . $email_response['error'];
            }
        } else {
            $response['customer_error'] = 'CUSTOMER BOOKING EMAIL SETTING IN NOT ENABLE';
        }

        if (isset($customer_setting['shipper_booking_email']) && $customer_setting['shipper_booking_email'] == 1) {
            $phone_no = get_phone_number('docket_shipper', $docket_id, 'email');
            $email_response = get_msg_template('shipper_booking_email', 'email', $docket_id, 1, $phone_no);
            if (isset($email_response['error'])) {
                $response['shipper_error'] = 'SHIPPER ' . $email_response['error'];
            }
        } else {
            $response['shipper_error'] = 'SHIPPER BOOKING EMAIL SETTING IN NOT ENABLE';
        }

        if (isset($customer_setting['consignee_booking_email']) && $customer_setting['consignee_booking_email'] == 1) {
            $phone_no = get_phone_number('docket_consignee', $docket_id, 'email');
            $email_response = get_msg_template('consignee_booking_email', 'email', $docket_id, 1, $phone_no);
            if (isset($email_response['error'])) {
                $response['consignee_error'] = 'CONSIGNEE ' . $email_response['error'];
            }
        } else {
            $response['consignee_error'] = 'CONSIGNEE BOOKING EMAIL SETTING IN NOT ENABLE';
        }
    }

    if (isset($response) && is_array($response) && count($response) > 0) {
        $msg_response = $response;
    } else {
        $msg_response['success'] = 'sent successfully';
    }

    return $msg_response;
}
function send_delivery_email($docket_id = 0)
{
    $CI = &get_instance();
    if ($docket_id > 0) {

        $qry = "SELECT id,customer_id FROM docket WHERE id='" . $docket_id . "'";
        $qry_exe = $CI->db->query($qry);
        $docket_data = $qry_exe->row_array();

        if (isset($docket_data['customer_id']) && $docket_data['customer_id'] > 0) {
            $qry = "SELECT c.id,m.config_key,m.config_value FROM customer c 
                JOIN module_setting m ON(c.id=m.module_id AND m.module_type=1) 
                WHERE c.id='" . $docket_data['customer_id'] . "' AND c.status IN(1,2) AND m.status IN(1,2)";
            $qry_exe = $CI->db->query($qry);
            $customer_data = $qry_exe->result_array();
        }

        if (isset($customer_data) && is_array($customer_data) && count($customer_data) > 0) {
            foreach ($customer_data as $key => $value) {
                $customer_setting[$value['config_key']] = $value['config_value'];
            }
        }

        if (isset($customer_setting['customer_delivery_email']) && $customer_setting['customer_delivery_email'] == 1) {
            $phone_no = get_phone_number('customer', $docket_data['customer_id'], 'email');
            get_msg_template('customer_delivery_email', 'email', $docket_id, 1, $phone_no);
        }

        if (isset($customer_setting['shipper_delivery_email']) && $customer_setting['shipper_delivery_email'] == 1) {
            $phone_no = get_phone_number('docket_shipper', $docket_id, 'email');
            get_msg_template('shipper_delivery_email', 'email', $docket_id, 1, $phone_no);
        }

        if (isset($customer_setting['consignee_delivery_email']) && $customer_setting['consignee_delivery_email'] == 1) {
            $phone_no = get_phone_number('docket_consignee', $docket_id, 'email');
            get_msg_template('consignee_delivery_email', 'email', $docket_id, 1, $phone_no);
        }
    }
}

function send_pickup_request_email($pickup_request_id = 0)
{
    $CI = &get_instance();
    if ($pickup_request_id > 0) {

        $qry = "SELECT id,customer_id FROM pickup_request WHERE id='" . $pickup_request_id . "'";
        $qry_exe = $CI->db->query($qry);
        $docket_data = $qry_exe->row_array();

        if (isset($docket_data['customer_id']) && $docket_data['customer_id'] > 0) {
            $qry = "SELECT c.id,m.config_key,m.config_value FROM customer c 
                JOIN module_setting m ON(c.id=m.module_id AND m.module_type=1) 
                WHERE c.id='" . $docket_data['customer_id'] . "' AND c.status IN(1,2) AND m.status IN(1,2)";
            $qry_exe = $CI->db->query($qry);
            $customer_data = $qry_exe->result_array();
        }

        if (isset($customer_data) && is_array($customer_data) && count($customer_data) > 0) {
            foreach ($customer_data as $key => $value) {
                $customer_setting[$value['config_key']] = $value['config_value'];
            }
        }

        if (isset($customer_setting['customer_pickup_email']) && $customer_setting['customer_pickup_email'] == 1) {
            $phone_no = get_phone_number('customer', $docket_data['customer_id'], 'email');
            get_msg_template('customer_pickup_email', 'email', $pickup_request_id, 4, $phone_no);
        }

        if (isset($customer_setting['shipper_pickup_email']) && $customer_setting['shipper_pickup_email'] == 1) {
            $phone_no = get_phone_number('pickup_request_shipper', $pickup_request_id, 'email');
            get_msg_template('shipper_pickup_email', 'email', $pickup_request_id, 4, $phone_no);
        }

        if (isset($customer_setting['consignee_pickup_email']) && $customer_setting['consignee_pickup_email'] == 1) {
            $phone_no = get_phone_number('pickup_request_consignee', $pickup_request_id, 'email');
            get_msg_template('consignee_pickup_email', 'email', $pickup_request_id, 4, $phone_no);
        }
    }
}

function send_pickup_sheet_email($pickup_request_id = 0)
{
    $CI = &get_instance();
    if ($pickup_request_id > 0) {

        $qry = "SELECT id,customer_id FROM pickup_request WHERE id='" . $pickup_request_id . "'";
        $qry_exe = $CI->db->query($qry);
        $docket_data = $qry_exe->row_array();

        if (isset($docket_data['customer_id']) && $docket_data['customer_id'] > 0) {
            $qry = "SELECT c.id,m.config_key,m.config_value FROM customer c 
                JOIN module_setting m ON(c.id=m.module_id AND m.module_type=1) 
                WHERE c.id='" . $docket_data['customer_id'] . "' AND c.status IN(1,2) AND m.status IN(1,2)";
            $qry_exe = $CI->db->query($qry);
            $customer_data = $qry_exe->result_array();
        }

        if (isset($customer_data) && is_array($customer_data) && count($customer_data) > 0) {
            foreach ($customer_data as $key => $value) {
                $customer_setting[$value['config_key']] = $value['config_value'];
            }
        }

        if (isset($customer_setting['customer_pickup_sheet_email']) && $customer_setting['customer_pickup_sheet_email'] == 1) {
            $phone_no = get_phone_number('customer', $docket_data['customer_id'], 'email');
            get_msg_template('customer_pickup_sheet_email', 'email', $pickup_request_id, 4, $phone_no);
        }

        if (isset($customer_setting['shipper_pickup_sheet_email']) && $customer_setting['shipper_pickup_sheet_email'] == 1) {
            $phone_no = get_phone_number('pickup_request_shipper', $pickup_request_id, 'email');
            get_msg_template('shipper_pickup_sheet_email', 'email', $pickup_request_id, 4, $phone_no);
        }

        if (isset($customer_setting['consignee_pickup_sheet_email']) && $customer_setting['consignee_pickup_sheet_email'] == 1) {
            $phone_no = get_phone_number('pickup_request_consignee', $pickup_request_id, 'email');
            get_msg_template('consignee_pickup_sheet_email', 'email', $pickup_request_id, 4, $phone_no);
        }
    }
}

function get_phone_number($module = '', $id = '', $type = 'sms')
{
    $CI = &get_instance();
    $phone_no = '';
    $qry = '';
    if ($module != '' && $id > 0) {
        if ($module == 'customer') {
            $qry = "SELECT id,contact_no,operation_email_id as email_id,dial_code,country FROM customer WHERE status IN(1,2) AND id='" . $id . "'";
        } else if ($module == 'docket_shipper') {
            $qry = "SELECT id,contact_no,email_id,dial_code,country FROM docket_shipper WHERE status IN(1,2) AND docket_id='" . $id . "'";
        } else if ($module == 'docket_consignee') {
            $qry = "SELECT id,contact_no,email_id,dial_code,country FROM docket_consignee WHERE status IN(1,2) AND docket_id='" . $id . "'";
        } else if ($module == 'pickup_request_shipper') {
            $qry = "SELECT id,pickup_phone as contact_no,pickup_email as email_id,pickup_dial_code as dial_code,pickup_country as country FROM pickup_request_detail WHERE status IN(1,2) AND pickup_request_id='" . $id . "'";
        } else if ($module == 'pickup_request_consignee') {
            $qry = "SELECT id,consignee_phone as contact_no,consignee_email as email_id,consignee_dial_code as dial_code,consignee_country as country FROM pickup_request_detail WHERE status IN(1,2) AND pickup_request_id='" . $id . "'";
        }
        $dial_code = '';
        if ($qry != '') {
            $qry_exe = $CI->db->query($qry);
            $module_data = $qry_exe->row_array();
            $phone_no = isset($module_data['contact_no']) ? trim($module_data['contact_no']) : '';

            if ($type == 'whatsapp') {
                if (isset($module_data['country']) && $module_data['country'] > 0) {
                    $qry = "SELECT id,name,code FROM country WHERE status IN(1,2) AND id='" . $module_data['country'] . "'";
                    $qry_exe = $CI->db->query($qry);
                    $country_data = $qry_exe->row_array();

                    $country_name = isset($country_data['name']) ? strtolower(trim($country_data['name'])) : '';
                    if ($country_name != 'india') {
                        if (isset($module_data['dial_code']) && $module_data['dial_code'] != '') {
                            $dial_code = preg_replace("[^0-9]", "", $module_data['dial_code']);
                        }
                    }
                }
            } else if ($type == 'email') {
                $phone_no = isset($module_data['email_id']) ? trim($module_data['email_id']) : '';
            }
        }
    }

    if ($phone_no != '' && $type == 'whatsapp') {
        if ($dial_code != '') {
            $phone_no =  $dial_code . $phone_no;
        } else {
            $phone_no = "91"  . $phone_no;
        }
    }



    return $phone_no;
}
function get_msg_template($sms_identifier = '', $sms_type = '', $module_id = '', $module_type = '', $phone_no = '', $pdf_path = '', $otp_no = '', $sheet_id = '', $pdf_array = array(), $extra_cc_emails="")
{
    // if (isset($_GET['mode']) && $_GET['mode'] == 'test') {
    //     echo $sms_identifier;
    //     echo $sms_type;
    // }
    $CI = &get_instance();
    if ($phone_no != '') {

        if ($sms_type == 'sms') {
            $all_identifier = all_sms_identifier('', 'config_key');
            $identifier_id = isset($all_identifier[$sms_identifier]) ? $all_identifier[$sms_identifier]['id'] : 0;
            if ($identifier_id > 0) {
                $qry = "SELECT id,sms_text,template_name FROM sms_master WHERE status IN(1,2) AND identifier_id='" . $identifier_id . "'";
                $qry_exe = $CI->db->query($qry);
                $sms_data = $qry_exe->row_array();
            }
        } else if ($sms_type == 'whatsapp') {
            $all_identifier = all_whatsapp_identifier('', 'config_key');
            $identifier_id = isset($all_identifier[$sms_identifier]) ? $all_identifier[$sms_identifier]['id'] : 0;
            if ($identifier_id > 0) {
                $qry = "SELECT id,sms_text FROM whatsapp_master WHERE status IN(1,2) AND identifier_id='" . $identifier_id . "'";
                $qry_exe = $CI->db->query($qry);
                $sms_data = $qry_exe->row_array();
            }
        } else if ($sms_type == 'email') {
            // if ($sms_identifier > 0) {
            $qry = "SELECT id,email_configuration_id,email_subject,email_body as sms_text,cc_email FROM trigger_email_setting WHERE status IN(1,2) AND email_trigger_key='" . $sms_identifier . "'";
            $qry_exe = $CI->db->query($qry);
            $sms_data = $qry_exe->row_array();
            // }
        }


        // if (isset($_GET['mode']) && $_GET['mode'] == 'test') {
        // echo $qry;
        // echo '<pre>';
        // print_r($sms_data);
        //}


        if (isset($sms_data) && is_array($sms_data) && count($sms_data) > 0) {
            $module_msg_data = array(
                'sms_type' => $sms_type,
                'master_var_id' => $sms_data['id'],
                'module_id' => $module_id,
                'module_type' => $module_type,
                'message' => $sms_data['sms_text'],
                'email_subject' => isset($sms_data['email_subject']) ?  $sms_data['email_subject'] : '',
                'otp_no' => $otp_no,
                'sheet_id' => $sheet_id,
                'sms_identifier' => $sms_identifier
            );
            $dynamic_res = get_dynamic_email_body($module_msg_data);
            $formatted_msg = $dynamic_res['message'];
            $email_subject = $dynamic_res['email_subject'];

            $send_msg = 1;

            if ($sms_type == 'sms') {
                send_sms_msg($formatted_msg, $phone_no, $sms_data['template_name'], $sms_data['id']);
            } else if ($sms_type == 'whatsapp') {
                send_whatsapp_msg($formatted_msg, $phone_no, $dynamic_res['module_customer_id'], $pdf_path);
            } else if ($sms_type == 'email') {
                if (
                    $sms_identifier == 'customer_docket_pdf_email'
                    || $sms_identifier == 'shipper_docket_pdf_email'
                    || $sms_identifier == 'consignee_docket_pdf_email'
                    ||  $sms_identifier == 'customer_booking_email'
                    || $sms_identifier == 'shipper_booking_email'
                    || $sms_identifier == 'consignee_booking_email'
                ) {
                    $attachment = array();
                    if (
                        $sms_identifier == 'customer_docket_pdf_email'
                        || $sms_identifier == 'shipper_docket_pdf_email'
                        || $sms_identifier == 'consignee_docket_pdf_email'
                    ) {
                        $attachment = array($pdf_path);
                    }


                    $qry = "SELECT d.id,d.reference_no,d.awb_no,d.forwarding_no,d.destination_id,d.total_pcs,d.actual_wt,d.chargeable_wt,
                cust.operation_email_id,d.vendor_id,d.customer_id,d.product_id,dcon.name as con_name,dshi.name as shi_name FROM docket d
                JOIN customer cust ON(cust.id=d.customer_id)
                LEFT OUTER JOIN docket_consignee dcon ON(d.id=dcon.docket_id AND dcon.status IN(1,2))
                LEFT OUTER JOIN docket_shipper dshi ON(d.id=dshi.docket_id AND dshi.status IN(1,2)) 
                WHERE d.status IN(1,2) AND d.id='" . $module_id . "'";
                    $qry_exe = $CI->db->query($qry);
                    $docket_data = $qry_exe->row_array();
                    if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
                        $data['all_location'] = get_all_location(" AND id=" . $docket_data['destination_id']);
                        $data['all_product'] = get_all_product(" AND id=" . $docket_data['product_id']);
                        $data['docket_data'] = $docket_data;
                        $data['msg_body'] = $formatted_msg;
                        $data['all_vendor'] = get_all_vendor();

                        $formatted_msg = $CI->load->view('email_body/booking_per_docket', $data, TRUE);
                    }
                } else if ($sms_identifier == 'extra_charge_email') {
                    //GET DOCKET EXTRA CHARGE

                    $setting = get_all_app_setting(" AND module_name IN('general')");

                    $qry = "SELECT id,customer_id FROM docket WHERE id='" . $module_id . "'";
                    $qry_exe = $CI->db->query($qry);
                    $docket_data = $qry_exe->row_array();

                    if (isset($docket_data['customer_id']) && $docket_data['customer_id'] > 0) {
                        $qry = "SELECT c.id,m.config_key,m.config_value FROM customer c 
                            JOIN module_setting m ON(c.id=m.module_id AND m.module_type=1) 
                            WHERE c.id='" . $docket_data['customer_id'] . "' 
                            AND c.status IN(1,2) AND m.status IN(1) AND config_key='dont_send_default_charge_mail'";
                        $qry_exe = $CI->db->query($qry);
                        $customer_setting = $qry_exe->row_array();
                    }


                    if (isset($customer_setting['config_value']) && $customer_setting['config_value'] == 1) {
                        $chargeq = "SELECT c.id,c.docket_id,c.charge_id,c.rate_mod_id,c.charge_amount,
                        d.awb_no,d.booking_date,ch.is_default
                         FROM docket_charges c
                         JOIN docket d ON(d.id=c.docket_id)
                         JOIN charge_master ch ON(ch.id=c.charge_id)
                WHERE c.status IN(1,2) AND c.billing_type=1 AND c.docket_id ='" . $module_id . "' 
                AND c.charge_amount>0 AND ch.status IN(1,2) AND ch.is_default !=1";
                    } else {
                        $chargeq = "SELECT c.id,c.docket_id,c.charge_id,c.rate_mod_id,c.charge_amount,
                        d.awb_no,d.booking_date,ch.is_default
                         FROM docket_charges c
                         JOIN docket d ON(d.id=c.docket_id)
                         JOIN charge_master ch ON(ch.id=c.charge_id)
                WHERE c.status IN(1,2) AND c.billing_type=1 AND c.docket_id ='" . $module_id . "' 
                AND c.charge_amount>0";
                    }


                    $chargeq_exe = $CI->db->query($chargeq);
                    $data['docket_charge'] = $chargeq_exe->result_array();

                    if (isset($data['docket_charge']) && is_array($data['docket_charge']) && count($data['docket_charge']) > 0) {


                        //DONT SEND EMAIL IF ALL CHARGE ARE DEFAULT
                        if (isset($setting['turn_off_default_charge_email']) && $setting['turn_off_default_charge_email'] == 1) {
                            $default_count = 0;
                            foreach ($data['docket_charge'] as $cha_key => $cha_value) {
                                if ($cha_value['is_default'] == 1) {
                                    $default_count += 1;
                                }
                            }

                            if (count($data['docket_charge']) == $default_count) {
                                $send_msg = 2;
                            } else {
                                $data['all_charge'] = get_all_charge('', 'id,name');
                                $data['msg_body'] = $formatted_msg;
                                $formatted_msg = $CI->load->view('email_body/extra_charge_per_docket', $data, true);
                            }
                        } else {
                            $data['all_charge'] = get_all_charge('', 'id,name');
                            $data['msg_body'] = $formatted_msg;
                            $formatted_msg = $CI->load->view('email_body/extra_charge_per_docket', $data, true);
                        }
                    } else {
                        $send_msg = 2;
                    }
                } else if ($sms_identifier == 'manifest_prealert_email') {
                    $attachment = $pdf_array;
                } else if ($sms_identifier == 'send_awb_free_form') {
                    $sms_data['cc_email'] = $sms_data['cc_email'].",".$extra_cc_emails;
                    $attachment = $pdf_array;
                } else {
                    $attachment = array();
                }



                if ($send_msg == 1) {
                    if ($sms_identifier == 'manifest_prealert_email') {
                        $email_data = array(
                            'email_config_id' => $sms_data['email_configuration_id'],
                            'subject' => $email_subject,
                            'body' => $formatted_msg,
                            'recipient_email' => $phone_no,
                            'reply_email' => '',
                            'cc_email' => $sms_data['cc_email'],
                            'attachment' => $attachment,

                        );
                        return $email_data;
                    }
                    send_email_msg($sms_data['email_configuration_id'], $email_subject, $formatted_msg,  $phone_no, '', $sms_data['cc_email'], $attachment, $dynamic_res['module_customer_id']);
                }
            }
        } else {

            $send_response['error'] = 'Message not added in setup';
        }
    } else {
        $send_response['error'] = 'Email ID not found';
    }

    return $send_response;
}

function get_dynamic_email_body($module_data = array())
{
    $CI = &get_instance();
    $message = '';


    if (isset($module_data) && is_array($module_data) && count($module_data) > 0) {
        $CI->load->model('Global_model', 'gm');
        $module_id = $module_data['module_id'];
        $module_type = $module_data['module_type'];
        $message = $module_data['message'];
        $email_subject = $module_data['email_subject'];
        $sms_type = $module_data['sms_type'];
        $master_var_id = $module_data['master_var_id'];
        $sheet_id = $module_data['sheet_id'];
        $variable_data = array();

        //$module_type => 1:DOCKET;2:OUTSTANDING;3:INVOICE,4:PICKUP REQUEST,5:CREDIT/DEBIT NOTE,6:RECEIPT
        if ($sms_type == 'sms') {
            $variable_result = $CI->gm->get_data_list('sms_master_variable', array('sms_master_id' => $master_var_id, 'status' => 1), array(), array(), 'variable_name,variable_field');
        } else if ($sms_type == 'email') {
            $variable_result = $CI->gm->get_data_list('whatsapp_master_variable', array('trigger_email_setting_id' => $master_var_id, 'status' => 1), array(), array(), 'variable_name,variable_field');
        } else if ($sms_type == 'whatsapp') {
            $variable_result = $CI->gm->get_data_list('whatsapp_master_variable', array('whatsapp_master_id' => $master_var_id, 'status' => 1), array(), array(), 'variable_name,variable_field');
        }


        if (isset($variable_result) && is_array($variable_result) && count($variable_result) > 0) {
            foreach ($variable_result as $vkey => $vvalue) {
                $variable_data[$vvalue['variable_name']] = $vvalue['variable_field'];
            }
        }

        $module_customer_id  = 0;
        if ($module_type == 2) {


            $customer_data = $CI->gm->get_selected_record('customer', 'id,name,company_id', $where = array('id' => $module_id, 'status' => 1), array());

            $module_customer_id = isset($customer_data['id']) ?  $customer_data['id'] : 0;
            if (isset($customer_data['company_id']) && $customer_data['company_id'] > 0) {
                $company_data = $CI->gm->get_selected_record('company_master', 'id,name,website', $where = array('id' => $customer_data['company_id'], 'status' => 1), array());
                $variable_val['company_name'] = isset($company_data['name']) ? $company_data['name'] : '';
                $variable_val['company_website'] = isset($company_data['website']) ? $company_data['website'] : '';
            }

            if (in_array('total_outstanding', $variable_data)) { //customer_outstanding
                $customer_outstanding = get_customer_ledger($module_id);

                /*  $amountq = "SELECT SUM(amount) as total_amt,customer_id,ledger_type FROM ledger_outstanding_item 
            WHERE status IN(1,2) AND customer_id IN(" . $module_id . ") GROUP BY customer_id,ledger_type";
                $amountq_exe = $CI->db->query($amountq);
                $amount_data = $amountq_exe->result_array();
                if (isset($amount_data) && is_array($amount_data) && count($amount_data) > 0) {
                    foreach ($amount_data as $akey => $avalue) {
                        $customer_ledger[$avalue['ledger_type']][$avalue['customer_id']] = $avalue['total_amt'];
                    }
                }

                //GET UNBILLED AMOUNT
                $product_id = "";
                $product_void = get_all_product(" AND code = 'void'");
                if (isset($product_void) && is_array($product_void) && count($product_void) > 0) {
                    foreach ($product_void as $key => $value) {
                        $product_id = $value['id'];
                    }
                }
                $unbilled_amt_data = 0;
                $unbilledq = "SELECT d.id,d.awb_no,d.booking_date,dcon.name as con_name,dshi.name as shi_name, 
             d.destination_id,d.chargeable_wt,ds.grand_total,d.customer_id FROM `docket` d 
             JOIN customer c ON(c.id=d.customer_id)
           LEFT OUTER JOIN docket_consignee dcon ON(d.id=dcon.docket_id AND dcon.status IN(1,2))
           LEFT OUTER JOIN docket_shipper dshi ON(d.id=dshi.docket_id AND dshi.status IN(1,2)) 
           LEFT OUTER JOIN docket_sales_billing ds ON(d.id=ds.docket_id AND ds.status IN(1,2))
           WHERE d.status IN(1,2)  AND d.status_id!=3 AND d.product_id != '" . $product_id . "' AND c.status IN(1,2) AND d.customer_id IN(" . $module_id . ") AND d.id NOT IN(SELECT id FROM docket_invoice_map WHERE status IN(1,2)) ";
                $unbilledq_exe = $CI->db->query($unbilledq);
                $unbilled_data = $unbilledq_exe->result_array();
                if (isset($unbilled_data) && is_array($unbilled_data) && count($unbilled_data) > 0) {
                    foreach ($unbilled_data as $rkey => $rvalue) {
                        $unbilled_amt_data += $rvalue['grand_total'];
                    }
                }

                $credit_amt = isset($amount_data[1][$module_id]) && $amount_data[1][$module_id] > 0 ? $amount_data[1][$module_id] : 0;
                $debit_amt = isset($amount_data[2][$module_id]) && $amount_data[2][$module_id] > 0 ? $amount_data[2][$module_id] : 0;
                $debit_amt = $debit_amt + $unbilled_amt_data;
                $customer_outstanding = $debit_amt - $credit_amt; */
            }
            $variable_val['customer_name'] = isset($customer_data['name']) ? $customer_data['name'] : '';
            $variable_val['total_outstanding'] = isset($customer_outstanding) ? $customer_outstanding : 0;

            if (isset($module_data['sms_identifier']) && $module_data['sms_identifier'] == 'customer_aging_due_whatsapp') {
                $note_param['customer_id'] = $module_id;
                $aging_data = $CI->generic_detail->get_aging_due_data($note_param);
                if (isset($aging_data) && is_array($aging_data) && count($aging_data) > 0) {
                    foreach ($aging_data as $akey => $avalue) {
                        if ($avalue['type'] == 'Opening Balance') {
                            $aging_msg[] = "Opening Balance for Rs." . round($avalue['total_amt'] - $avalue['paid_amt']) . " due on " . date('d/m/Y', strtotime($avalue['due_date']));
                        } else if ($avalue['type'] == 'Debit Note') {
                            $aging_msg[] = "Debit Note " . $avalue['invoice_no'] . " for Rs." . round($avalue['total_amt'] - $avalue['paid_amt']) . " due on " . date('d/m/Y', strtotime($avalue['due_date']));
                        } else if ($avalue['type'] == 'Invoice') {
                            $aging_msg[] = "Invoice " . $avalue['invoice_no'] . " for Rs." . round($avalue['total_amt'] - $avalue['paid_amt']) . " due on " . date('d/m/Y', strtotime($avalue['due_date']));
                        }
                    }
                }


                if (isset($aging_msg) && is_array($aging_msg) && count($aging_msg) > 0) {
                    $variable_val['aging_data'] = implode(",", $aging_msg);
                }
            }
        } else if ($module_type == 3) {
            //INVOICE
            $invoice_data = $CI->gm->get_selected_record('docket_invoice', 'id,invoice_no,invoice_date,company_master_id,customer_id,shipper_id,invoice_type', $where = array('id' => $module_id, 'status' => 1), array());

            $variable_val['invoice_no'] = isset($invoice_data['invoice_no']) ? $invoice_data['invoice_no'] : '';
            $variable_val['invoice_date'] = isset($invoice_data['invoice_date']) ?  get_format_date('d/m/Y', $invoice_data['invoice_date']) : '';

            if (isset($invoice_data['company_master_id']) && $invoice_data['company_master_id'] > 0) {
                $company_data = $CI->gm->get_selected_record('company_master', 'id,name,website', $where = array('id' => $invoice_data['company_master_id'], 'status' => 1), array());
                $variable_val['company_name'] = isset($company_data['name']) ? $company_data['name'] : '';
            }

            if (isset($invoice_data['customer_id']) && $invoice_data['customer_id'] > 0) {
                $customer_data = $CI->gm->get_selected_record('customer', 'id,name,code', $where = array('id' => $invoice_data['customer_id'], 'status' => 1), array());
                $module_customer_id = isset($customer_data['id']) ?  $customer_data['id'] : 0;
                $variable_val['customer_name'] = isset($customer_data['name']) ? $customer_data['name'] : '';
                $variable_val['customer_code'] = isset($customer_data['code']) ? $customer_data['code'] : '';
            }

            if (isset($invoice_data['shipper_id']) && $invoice_data['shipper_id'] > 0 && $invoice_data['invoice_type'] == 3) {
                $customer_data = $CI->gm->get_selected_record('shipper', 'id,name,code', $where = array('id' => $invoice_data['shipper_id'], 'status' => 1), array());
                $variable_val['customer_name'] = isset($customer_data['name']) ? $customer_data['name'] : '';
                $variable_val['customer_code'] = isset($customer_data['code']) ? $customer_data['code'] : '';
            }
        } else if ($module_type == 1) {
            //DOCKET
            $docket_data = $CI->gm->get_selected_record('docket', 'id,awb_no,company_id,booking_date,origin_id,destination_id,booking_time,actual_wt,chargeable_wt,customer_id,vendor_id,total_pcs', $where = array('id' => $module_id, 'status' => 1), array());

            if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
                $shipper_data = $CI->gm->get_selected_record('docket_shipper', 'id,name,contact_no', $where = array('docket_id' => $module_id, 'status' => 1), array());
                $consignee_data = $CI->gm->get_selected_record('docket_consignee', 'id,name,contact_no', $where = array('docket_id' => $module_id, 'status' => 1), array());
                $company_data = $CI->gm->get_selected_record('company_master', '*', $where = array('id' => $docket_data['company_id'], 'status' => 1), array());

                $customer_data = $CI->gm->get_selected_record('customer', 'id,name,code', $where = array('id' => $docket_data['customer_id'], 'status' => 1), array());
                $module_customer_id = isset($customer_data['id']) ?  $customer_data['id'] : 0;
                $vendor_data = $CI->gm->get_selected_record('vendor', 'id,name,code', $where = array('id' => $docket_data['vendor_id'], 'status' => 1), array());

                $orig_data = $CI->gm->get_selected_record('location', 'id,name,code', $where = array('id' => $docket_data['origin_id'], 'status' => 1), array());
                $dest_data = $CI->gm->get_selected_record('location', 'id,name,code', $where = array('id' => $docket_data['destination_id'], 'status' => 1), array());
                $delivery_data = $CI->gm->get_selected_record('docket_delivery', 'id,receiver_name,delivery_date,delivery_time', $where = array('docket_id' =>  $module_id, 'status' => 1), array());

                $variable_val['tracking_no'] = isset($docket_data['awb_no']) ? $docket_data['awb_no'] : '';
                $variable_val['company_name'] = isset($company_data['name']) ? $company_data['name'] : '';
                $variable_val['company_website'] = isset($company_data['website']) ? $company_data['website'] : '';
                $variable_val['consignee_contact_no'] = isset($consignee_data['contact_no']) ? $consignee_data['contact_no'] : '';
                $variable_val['shipper_contact_no'] = isset($shipper_data['contact_no']) ? $shipper_data['contact_no'] : '';
                $variable_val['formatted_delivery_date'] = isset($delivery_data['delivery_date']) ? get_format_date('d/m/Y', $delivery_data['delivery_date']) : '';
                $variable_val['formatted_booking_date'] = isset($docket_data['booking_date']) ? get_format_date('d/m/Y', $docket_data['booking_date']) : '';
                $variable_val['destination_code'] = isset($dest_data['code']) ? $dest_data['code'] : '';
                $variable_val['destination_name'] = isset($dest_data['name']) ? $dest_data['name'] : '';
                $variable_val['origin_code'] = isset($orig_data['code']) ? $orig_data['code'] : '';
                $variable_val['origin_name'] = isset($orig_data['name']) ? $orig_data['name'] : '';
                $variable_val['formatted_booking_time'] = isset($docket_data['booking_time']) ? get_format_date('h:i A', $docket_data['booking_time']) : '';
                $variable_val['formatted_delivery_time'] = isset($delivery_data['delivery_time']) ? get_format_date('h:i A', $delivery_data['delivery_time']) : '';
                $variable_val['receiever_name'] = isset($delivery_data['receiver_name']) ? $delivery_data['receiver_name'] : '';
                $variable_val['formatted_actual_weight'] = isset($docket_data['actual_wt']) ? $docket_data['actual_wt'] : '';
                $variable_val['formatted_chargeable_weight'] = isset($docket_data['chargeable_wt']) ? $docket_data['chargeable_wt'] : '';
                $variable_val['customer_name'] = isset($customer_data['name']) ? $customer_data['name'] : '';
                $variable_val['shipper_name'] = isset($shipper_data['name']) ? $shipper_data['name'] : '';
                $variable_val['consignee_name'] = isset($consignee_data['name']) ? $consignee_data['name'] : '';
                $variable_val['awb_service_name'] = isset($vendor_data['name']) ? $vendor_data['name'] : '';
                $variable_val['awb_boxes'] = isset($docket_data['total_pcs']) ? $docket_data['total_pcs'] : '';
            }
        } else if ($module_type == 4) {
            //PICKUP REQUEST
            $pickup_request_data = $CI->gm->get_selected_record('pickup_request', 'call_date,id,ref_no,customer_id,vendor_id,co_vendor_id', $where = array('id' => $module_id, 'status' => 1), array());

            if (isset($pickup_request_data) && is_array($pickup_request_data) && count($pickup_request_data) > 0) {
                $pickup_request_detail = $CI->gm->get_selected_record('pickup_request_detail', 'id,user_id', $where = array('pickup_request_id' => $module_id, 'status' => 1), array());

                $user_id = isset($pickup_request_detail['user_id']) ? $pickup_request_detail['user_id'] : 0;
                if ($user_id > 0) {
                    $all_user = get_all_user(" AND id='" . $user_id . "'");
                }
                $vendor_data = $CI->gm->get_selected_record('vendor', 'id,name,code', $where = array('id' => $pickup_request_data['vendor_id'], 'status' => 1), array());
                $co_vendor_data = $CI->gm->get_selected_record('vendor', 'id,name,code', $where = array('id' => $pickup_request_data['co_vendor_id'], 'status' => 1), array());

                $customer_data = $CI->gm->get_selected_record('customer', 'id,name,company_id', $where = array('id' => $pickup_request_data['customer_id'], 'status' => 1), array());
                $module_customer_id = isset($customer_data['id']) ?  $customer_data['id'] : 0;
                if (isset($customer_data['company_id'])) {
                    $company_data = $CI->gm->get_selected_record('company_master', 'id,name,code', $where = array('id' => $customer_data['company_id'], 'status' => 1), array());
                }



                if ($sheet_id > 0) {
                    $sheet_data = $CI->gm->get_selected_record('pick_up_sheets', 'id,driver_id', $where = array('id' => $sheet_id, 'status' => 1), array());
                    $user_id = isset($sheet_data['driver_id']) ? $sheet_data['driver_id'] : 0;
                    if ($user_id > 0) {
                        $all_user = get_all_user(" AND id='" . $user_id . "'");
                    }
                    $variable_val['user_name'] = isset($all_user[$user_id]['name']) ? $all_user[$user_id]['name'] : '';
                    $variable_val['user_phone'] = isset($all_user[$user_id]['contactno']) ? $all_user[$user_id]['contactno'] : '';
                } else {
                    $variable_val['user_name'] = isset($all_user[$user_id]['name']) ? $all_user[$user_id]['name'] : '';
                    $variable_val['user_phone'] = isset($all_user[$user_id]['contactno']) ? $all_user[$user_id]['contactno'] : '';
                }

                $variable_val['reference_no'] = isset($pickup_request_data['ref_no']) ? $pickup_request_data['ref_no'] : '';

                $variable_val['pickup_service_name'] = isset($vendor_data['name']) ? $vendor_data['name'] : '';
                $variable_val['pickup_service_code'] = isset($vendor_data['code']) ? $vendor_data['code'] : '';
                $variable_val['pickup_vendor_name'] = isset($co_vendor_data['name']) ? $co_vendor_data['name'] : '';
                $variable_val['pickup_vendor_code'] =  isset($co_vendor_data['code']) ? $co_vendor_data['code'] : '';
                $variable_val['company_name'] = isset($company_data['name']) ? $company_data['name'] : '';
                $variable_val['formatted_pickup_date'] = isset($pickup_request_data['call_date']) ? get_format_date('d/m/Y', $pickup_request_data['call_date']) : '';
            }
        } else if ($module_type == 5) {
            //CREDIT DEBIT NOTE
            $note_data = $CI->gm->get_selected_record('credit_debit_note', 'id,note_no,note_date,customer_id', $where = array('id' => $module_id, 'status' => 1), array());

            $variable_val['credit_debit_note_no'] = isset($note_data['note_no']) ? $note_data['note_no'] : '';
            $variable_val['credit_debit_note_date'] = isset($note_data['note_date']) ?  get_format_date('d/m/Y', $note_data['note_date']) : '';

            if (isset($note_data['customer_id']) && $note_data['customer_id'] > 0) {

                $customer_data = $CI->gm->get_selected_record('customer', 'id,name,code,company_id', $where = array('id' => $note_data['customer_id'], 'status' => 1), array());
                $module_customer_id = isset($customer_data['id']) ?  $customer_data['id'] : 0;
                $variable_val['customer_name'] = isset($customer_data['name']) ? $customer_data['name'] : '';
                $variable_val['customer_code'] = isset($customer_data['code']) ? $customer_data['code'] : '';
            }
            if (isset($customer_data['company_id']) && $customer_data['company_id'] > 0) {
                $company_data = $CI->gm->get_selected_record('company_master', 'id,name,website', $where = array('id' => $customer_data['company_id'], 'status' => 1), array());
                $variable_val['company_name'] = isset($company_data['name']) ? $company_data['name'] : '';
            }
        } else if ($module_type == 6) {
            //RECEIPT
            $note_data = $CI->gm->get_selected_record('payment_receipt', 'id,payment_no,receipt_date,customer_id', $where = array('id' => $module_id, 'status' => 1), array());

            $variable_val['receipt_no'] = isset($note_data['payment_no']) ? $note_data['payment_no'] : '';
            $variable_val['receipt_date'] = isset($note_data['receipt_date']) ?  get_format_date('d/m/Y', $note_data['receipt_date']) : '';

            if (isset($note_data['customer_id']) && $note_data['customer_id'] > 0) {
                $customer_data = $CI->gm->get_selected_record('customer', 'id,name,code,company_id', $where = array('id' => $note_data['customer_id'], 'status' => 1), array());
                $module_customer_id = isset($customer_data['id']) ?  $customer_data['id'] : 0;
                $variable_val['customer_name'] = isset($customer_data['name']) ? $customer_data['name'] : '';
                $variable_val['customer_code'] = isset($customer_data['code']) ? $customer_data['code'] : '';
            }
            if (isset($customer_data['company_id']) && $customer_data['company_id'] > 0) {
                $company_data = $CI->gm->get_selected_record('company_master', 'id,name,website', $where = array('id' => $customer_data['company_id'], 'status' => 1), array());
                $variable_val['company_name'] = isset($company_data['name']) ? $company_data['name'] : '';
            }
        } else if ($module_type == 7) {
            //MANIFEST  
            $manifest_data = $CI->gm->get_selected_record('manifest', '*', $where = array('id' => $module_id, 'status' => 1), array());

            $all_license = get_all_license();
            $all_mode = get_all_manifest_mode();
            $all_flight = get_all_flight();
            $all_co_vendor = get_all_co_vendor();

            $variable_val['manifest_no'] = isset($manifest_data['manifest_no']) ? $manifest_data['manifest_no'] : '';
            if (isset($manifest_data['flight_no_id']) && $manifest_data['flight_no_id'] > 0) {
                $variable_val['flight_no'] = isset($all_flight[$manifest_data['flight_no_id']]['name']) ? $all_flight[$manifest_data['flight_no_id']]['name'] : '';
            } else {
                $variable_val['flight_no'] = isset($manifest_data['flight_no']) ? $manifest_data['flight_no'] : '';
            }
            $variable_val['vendor_cd_no'] = isset($manifest_data['vendor_cd_no']) ? $manifest_data['vendor_cd_no'] : '';
            $variable_val['run_no'] = isset($manifest_data['run_number']) ? $manifest_data['run_number'] : '';
            $variable_val['vehicle_no'] = isset($manifest_data['vehicle_no']) ? $manifest_data['vehicle_no'] : '';
            $variable_val['no_of_bags'] = isset($manifest_data['bags_count']) ? $manifest_data['bags_count'] : '';
            $variable_val['arrival_date'] = isset($manifest_data['arrival_date']) ? get_format_date("d/m/Y", $manifest_data['arrival_date']) : '';
            $variable_val['license_master_name'] = isset($all_license[$manifest_data['license_master_id']]) ? $all_license[$manifest_data['license_master_id']]['name']  : '';
            $variable_val['mode_name'] = isset($all_mode[$manifest_data['mode_id']]) ? $all_mode[$manifest_data['mode_id']]['name'] : '';
            $variable_val['master_no'] = isset($manifest_data['master_no']) ? $manifest_data['master_no'] : '';
            $variable_val['master_edi_bag_no'] = isset($manifest_data['master_edi_no']) ? $manifest_data['master_edi_no'] : '';
            $variable_val['total_volumetric_wt'] = isset($manifest_data['total_volumetric_wt']) ? $manifest_data['total_volumetric_wt'] : '';
            $variable_val['total_chargeable_wt'] = isset($manifest_data['total_chargeable_wt']) ? $manifest_data['total_chargeable_wt'] : '';
            $variable_val['manifest_comment'] = isset($manifest_data['manifest_comment']) ? $manifest_data['manifest_comment'] : '';
            $variable_val['vendor'] = isset($all_co_vendor[$manifest_data['co_vendor_id']]['name']) ? $all_co_vendor[$manifest_data['co_vendor_id']]['name'] : '';
            $variable_val['manifest_date'] = isset($manifest_data['manifest_date']) ? get_format_date("d/m/Y", $manifest_data['manifest_date']) : '';
            $variable_val['no_of_awb'] = isset($manifest_data['awb_count']) ? $manifest_data['awb_count'] : '';
            $variable_val['no_of_pcs'] = isset($manifest_data['total_bags_count']) ? $manifest_data['total_bags_count'] : '';
            $variable_val['total_actual_wt'] = isset($manifest_data['total_actual_wt']) ? $manifest_data['total_actual_wt'] : '';

            $module_customer_id = 0;
        }

        $variable_val['otp_no'] = isset($module_data['otp_no']) ? $module_data['otp_no'] : '';

        if (isset($variable_data) && is_array($variable_data) && count($variable_data) > 0) {
            foreach ($variable_data as $vf_key => $vf_value) {
                $message = str_ireplace($vf_key, (isset($variable_val[$vf_value]) ? $variable_val[$vf_value] : ''), $message);
                $email_subject = str_ireplace($vf_key, (isset($variable_val[$vf_value]) ? $variable_val[$vf_value] : ''), $email_subject);
            }
        }
    }

    $responseResult = array(
        'message' => $message,
        'email_subject' => $email_subject,
        'module_customer_id' => $module_customer_id
    );
    return $responseResult;
}



function send_docket_pdf_email($docket_id = 0, $pdf_path = '')
{
    $CI = &get_instance();
    if ($docket_id > 0) {

        $qry = "SELECT id,customer_id FROM docket WHERE id='" . $docket_id . "'";
        $qry_exe = $CI->db->query($qry);
        $docket_data = $qry_exe->row_array();

        if (isset($docket_data['customer_id']) && $docket_data['customer_id'] > 0) {
            $qry = "SELECT c.id,m.config_key,m.config_value FROM customer c 
                JOIN module_setting m ON(c.id=m.module_id AND m.module_type=1) 
                WHERE c.id='" . $docket_data['customer_id'] . "' AND c.status IN(1,2) AND m.status IN(1,2)";
            $qry_exe = $CI->db->query($qry);
            $customer_data = $qry_exe->result_array();
        }

        if (isset($customer_data) && is_array($customer_data) && count($customer_data) > 0) {
            foreach ($customer_data as $key => $value) {
                $customer_setting[$value['config_key']] = $value['config_value'];
            }
        }


        if (isset($customer_setting['customer_docket_pdf_email']) && $customer_setting['customer_docket_pdf_email'] == 1) {
            $phone_no = get_phone_number('customer', $docket_data['customer_id'], 'email');
            get_msg_template('customer_docket_pdf_email', 'email', $docket_id, 1, $phone_no, $pdf_path);
        }

        if (isset($customer_setting['shipper_docket_pdf_email']) && $customer_setting['shipper_docket_pdf_email'] == 1) {
            $phone_no = get_phone_number('docket_shipper', $docket_id, 'email');
            get_msg_template('shipper_docket_pdf_email', 'email', $docket_id, 1, $phone_no, $pdf_path);
        }

        if (isset($customer_setting['consignee_docket_pdf_email']) && $customer_setting['consignee_docket_pdf_email'] == 1) {
            $phone_no = get_phone_number('docket_consignee', $docket_id, 'email');
            get_msg_template('consignee_docket_pdf_email', 'email', $docket_id, 1, $phone_no, $pdf_path);
        }
    }
}


function send_drs_delivery_otp_sms($docket_id = 0, $otp_no = '')
{
    $CI = &get_instance();
    if ($docket_id > 0) {
        $sms_credential = get_sms_credentials();
        $username = isset($sms_credential['sms_username']) ? $sms_credential['sms_username'] : '';
        $sender_id = isset($sms_credential['sms_sender_id']) ? $sms_credential['sms_sender_id'] : '';
        $api_key = isset($sms_credential['sms_api_key']) ? $sms_credential['sms_api_key'] : '';
        $peid = isset($sms_credential['sms_peid']) ? $sms_credential['sms_peid'] : '';
        if ($username != '' && $sender_id != '' && $api_key != '' && $peid != '') {
            $qry = "SELECT id,customer_id FROM docket WHERE id='" . $docket_id . "'";
            $qry_exe = $CI->db->query($qry);
            $docket_data = $qry_exe->row_array();

            if (isset($docket_data['customer_id']) && $docket_data['customer_id'] > 0) {
                $qry = "SELECT c.id,m.config_key,m.config_value FROM customer c 
                JOIN module_setting m ON(c.id=m.module_id AND m.module_type=1) 
                WHERE c.id='" . $docket_data['customer_id'] . "' AND c.status IN(1,2) AND m.status IN(1,2)";
                $qry_exe = $CI->db->query($qry);
                $customer_data = $qry_exe->result_array();
            }

            if (isset($customer_data) && is_array($customer_data) && count($customer_data) > 0) {
                foreach ($customer_data as $key => $value) {
                    $customer_setting[$value['config_key']] = $value['config_value'];
                }
            }

            if (isset($customer_setting['verify_delivery_otp']) && $customer_setting['verify_delivery_otp'] == 1) {
                if (isset($customer_setting['customer_drs_otp_sms']) && $customer_setting['customer_drs_otp_sms'] == 1) {
                    $phone_no = get_phone_number('customer', $docket_data['customer_id']);
                    get_msg_template('customer_drs_otp_sms', 'sms', $docket_id, 1, $phone_no, '', $otp_no);
                }


                if (isset($customer_setting['send_consignee_otp_sms']) && $customer_setting['send_consignee_otp_sms'] == 1) {
                    $phone_no = get_phone_number('docket_consignee', $docket_id);
                    get_msg_template('consignee_drs_otp_sms', 'sms', $docket_id, 1, $phone_no, '', $otp_no);
                }
            }
        }
    }
}

function send_drs_delivery_otp_email($docket_id = 0, $otp_no = '')
{
    $CI = &get_instance();
    if ($docket_id > 0) {

        $qry = "SELECT id,customer_id FROM docket WHERE id='" . $docket_id . "'";
        $qry_exe = $CI->db->query($qry);
        $docket_data = $qry_exe->row_array();

        if (isset($docket_data['customer_id']) && $docket_data['customer_id'] > 0) {
            $qry = "SELECT c.id,m.config_key,m.config_value FROM customer c 
                JOIN module_setting m ON(c.id=m.module_id AND m.module_type=1) 
                WHERE c.id='" . $docket_data['customer_id'] . "' AND c.status IN(1,2) AND m.status IN(1,2)";
            $qry_exe = $CI->db->query($qry);
            $customer_data = $qry_exe->result_array();
        }

        if (isset($customer_data) && is_array($customer_data) && count($customer_data) > 0) {
            foreach ($customer_data as $key => $value) {
                $customer_setting[$value['config_key']] = $value['config_value'];
            }
        }
        if (isset($customer_setting['verify_delivery_otp']) && $customer_setting['verify_delivery_otp'] == 1) {
            if (isset($customer_setting['send_customer_otp_email']) && $customer_setting['send_customer_otp_email'] == 1) {
                $phone_no = get_phone_number('customer', $docket_data['customer_id'], 'email');
                get_msg_template('customer_drs_otp_email', 'email', $docket_id, 1, $phone_no, '', $otp_no);
            }
            if (isset($customer_setting['send_consignee_otp_email']) && $customer_setting['send_consignee_otp_email'] == 1) {
                $phone_no = get_phone_number('docket_consignee', $docket_id, 'email');
                get_msg_template('consignee_drs_otp_email', 'email', $docket_id, 1, $phone_no, '', $otp_no);
            }
        }
    }
}

function send_drs_delivery_otp_whatsapp($docket_id = 0, $otp_no = '')
{
    $CI = &get_instance();
    if ($docket_id > 0) {
        $api_key = get_whatsapp_credentials();

        if ($api_key != '') {
            $qry = "SELECT id,customer_id FROM docket WHERE id='" . $docket_id . "'";
            $qry_exe = $CI->db->query($qry);
            $docket_data = $qry_exe->row_array();

            if (isset($docket_data['customer_id']) && $docket_data['customer_id'] > 0) {
                $qry = "SELECT c.id,m.config_key,m.config_value FROM customer c 
                JOIN module_setting m ON(c.id=m.module_id AND m.module_type=1) 
                WHERE c.id='" . $docket_data['customer_id'] . "' AND c.status IN(1,2) AND m.status IN(1,2)";
                $qry_exe = $CI->db->query($qry);
                $customer_data = $qry_exe->result_array();
            }

            if (isset($customer_data) && is_array($customer_data) && count($customer_data) > 0) {
                foreach ($customer_data as $key => $value) {
                    $customer_setting[$value['config_key']] = $value['config_value'];
                }
            }
            if (isset($customer_setting['verify_delivery_otp']) && $customer_setting['verify_delivery_otp'] == 1) {
                if (isset($customer_setting['send_customer_otp_whatsapp']) && $customer_setting['send_customer_otp_whatsapp'] == 1) {
                    $phone_no = get_phone_number('customer', $docket_data['customer_id'], 'whatsapp');
                    get_msg_template('customer_drs_otp_whatsapp', 'whatsapp', $docket_id, 1, $phone_no, '', $otp_no);
                }

                if (isset($customer_setting['send_consignee_otp_whatsapp']) && $customer_setting['send_consignee_otp_whatsapp'] == 1) {
                    $phone_no = get_phone_number('docket_consignee', $docket_id, 'whatsapp');
                    get_msg_template('consignee_drs_otp_whatsapp', 'whatsapp', $docket_id, 1, $phone_no, '', $otp_no);
                }
            }
        }
    }
}


function send_extra_charge_email($docket_id = 0)
{
    $CI = &get_instance();
    if ($docket_id > 0) {



        $qry = "SELECT id,customer_id FROM docket WHERE id='" . $docket_id . "'";
        $qry_exe = $CI->db->query($qry);
        $docket_data = $qry_exe->row_array();

        if (isset($docket_data['customer_id']) && $docket_data['customer_id'] > 0) {
            $qry = "SELECT c.id,m.config_key,m.config_value FROM customer c 
                JOIN module_setting m ON(c.id=m.module_id AND m.module_type=1) 
                WHERE c.id='" . $docket_data['customer_id'] . "' AND c.status IN(1,2) AND m.status IN(1,2)";
            $qry_exe = $CI->db->query($qry);
            $customer_data = $qry_exe->result_array();
        }

        if (isset($customer_data) && is_array($customer_data) && count($customer_data) > 0) {
            foreach ($customer_data as $key => $value) {
                $customer_setting[$value['config_key']] = $value['config_value'];
            }
        }
        if (isset($customer_setting['extra_charge_email']) && $customer_setting['extra_charge_email'] == 1) {
            $phone_no = get_phone_number('customer', $docket_data['customer_id'], 'email');

            $email_response =  get_msg_template('extra_charge_email', 'email', $docket_id, 1, $phone_no);
            if (isset($email_response['error'])) {
                $response['customer_error'] = 'CUSTOMER ' . $email_response['error'];
            }
        } else {
            $response['customer_error'] = 'CUSTOMER EXTRA CHARGE EMAIL SETTING IN NOT ENABLE';
        }
    }

    return $response;
}



function send_invoice_sms($docket_invoice_id = 0)
{
    $CI = &get_instance();
    if ($docket_invoice_id > 0) {
        $sms_credential = get_sms_credentials();
        $username = isset($sms_credential['sms_username']) ? $sms_credential['sms_username'] : '';
        $sender_id = isset($sms_credential['sms_sender_id']) ? $sms_credential['sms_sender_id'] : '';
        $api_key = isset($sms_credential['sms_api_key']) ? $sms_credential['sms_api_key'] : '';
        $peid = isset($sms_credential['sms_peid']) ? $sms_credential['sms_peid'] : '';
        if ($username != '' && $sender_id != '' && $api_key != '' && $peid != '') {
            $qry = "SELECT id,customer_id FROM docket_invoice WHERE id='" . $docket_invoice_id . "'";
            $qry_exe = $CI->db->query($qry);
            $docket_data = $qry_exe->row_array();

            if (isset($docket_data['customer_id']) && $docket_data['customer_id'] > 0) {
                $qry = "SELECT c.id,m.config_key,m.config_value FROM customer c 
                JOIN module_setting m ON(c.id=m.module_id AND m.module_type=1) 
                WHERE c.id='" . $docket_data['customer_id'] . "' AND c.status IN(1,2) AND m.status IN(1,2)";
                $qry_exe = $CI->db->query($qry);
                $customer_data = $qry_exe->result_array();
            }

            if (isset($customer_data) && is_array($customer_data) && count($customer_data) > 0) {
                foreach ($customer_data as $key => $value) {
                    $customer_setting[$value['config_key']] = $value['config_value'];
                }
            }

            if (isset($customer_setting['invoice_generation_sms_to_customer']) && $customer_setting['invoice_generation_sms_to_customer'] == 1) {
                $phone_no = get_phone_number('customer', $docket_data['customer_id']);
                get_msg_template('invoice_generation_sms_to_customer', 'sms', $docket_invoice_id, 3, $phone_no);
            }
        }
    }
}
function send_out_for_delivery_sms($docket_id = 0)
{
    $CI = &get_instance();
    if ($docket_id > 0) {
        $sms_credential = get_sms_credentials();
        $username = isset($sms_credential['sms_username']) ? $sms_credential['sms_username'] : '';
        $sender_id = isset($sms_credential['sms_sender_id']) ? $sms_credential['sms_sender_id'] : '';
        $api_key = isset($sms_credential['sms_api_key']) ? $sms_credential['sms_api_key'] : '';
        $peid = isset($sms_credential['sms_peid']) ? $sms_credential['sms_peid'] : '';
        if ($username != '' && $sender_id != '' && $api_key != '' && $peid != '') {
            $qry = "SELECT id,customer_id FROM docket WHERE id='" . $docket_id . "'";
            $qry_exe = $CI->db->query($qry);
            $docket_data = $qry_exe->row_array();

            if (isset($docket_data['customer_id']) && $docket_data['customer_id'] > 0) {
                $qry = "SELECT c.id,m.config_key,m.config_value FROM customer c 
                JOIN module_setting m ON(c.id=m.module_id AND m.module_type=1) 
                WHERE c.id='" . $docket_data['customer_id'] . "' AND c.status IN(1,2) AND m.status IN(1,2)";
                $qry_exe = $CI->db->query($qry);
                $customer_data = $qry_exe->result_array();
            }

            if (isset($customer_data) && is_array($customer_data) && count($customer_data) > 0) {
                foreach ($customer_data as $key => $value) {
                    $customer_setting[$value['config_key']] = $value['config_value'];
                }
            }

            if (isset($customer_setting['send_out_for_delivery_to_customer']) && $customer_setting['send_out_for_delivery_to_customer'] == 1) {
                $phone_no = get_phone_number('customer', $docket_data['customer_id']);
                get_msg_template('customer_out_for_delivery_sms', 'sms', $docket_id, 1, $phone_no, '', '');
            }


            if (isset($customer_setting['send_out_for_delivery_to_consignee']) && $customer_setting['send_out_for_delivery_to_consignee'] == 1) {
                $phone_no = get_phone_number('docket_consignee', $docket_id);
                get_msg_template('consignee_out_for_delivery_sms', 'sms', $docket_id, 1, $phone_no, '', '');
            }
            if (isset($customer_setting['send_out_for_delivery_to_shipper']) && $customer_setting['send_out_for_delivery_to_shipper'] == 1) {
                $phone_no = get_phone_number('docket_shipper', $docket_id);
                get_msg_template('shipper_out_for_delivery_sms', 'sms', $docket_id, 1, $phone_no, '', '');
            }
        }
    }
}


function send_aging_limit_whatsapp($customer_id = 0)
{
    $CI = &get_instance();
    $resposne = array();
    if ($customer_id > 0) {
        $api_key = get_whatsapp_credentials();

        if ($api_key != '') {

            if ($customer_id > 0) {
                $qry = "SELECT c.id,m.config_key,m.config_value FROM customer c 
                JOIN module_setting m ON(c.id=m.module_id AND m.module_type=1) 
                WHERE c.id='" . $customer_id . "' AND c.status IN(1,2) AND m.status IN(1,2)";
                $qry_exe = $CI->db->query($qry);
                $customer_data = $qry_exe->result_array();
            }

            if (isset($customer_data) && is_array($customer_data) && count($customer_data) > 0) {
                foreach ($customer_data as $key => $value) {
                    $customer_setting[$value['config_key']] = $value['config_value'];
                }
            }

            if (isset($customer_setting['customer_aging_due_whatsapp']) && $customer_setting['customer_aging_due_whatsapp'] == 1) {
                $phone_no = get_phone_number('customer', $customer_id, 'whatsapp');

                get_msg_template('customer_aging_due_whatsapp', 'whatsapp', $customer_id, 2, $phone_no);
            }
        } else {
            $resposne['error'] = "Whatsapp api key not set";
        }
    }

    return $resposne;
}


function send_manifest_prealert_email($manifest_id = 0, $pdf_array = array())
{

    $CI = &get_instance();
    if ($manifest_id > 0) {

        $qry = "SELECT id,co_vendor_id FROM manifest WHERE id='" . $manifest_id . "'";
        $qry_exe = $CI->db->query($qry);
        $manifest_data = $qry_exe->row_array();
        if (isset($manifest_data['co_vendor_id']) && $manifest_data['co_vendor_id'] > 0) {
            $qry = "SELECT c.id,c.email_id FROM co_vendor c 
            WHERE c.id='" . $manifest_data['co_vendor_id'] . "' AND c.status IN(1,2)";
            $qry_exe = $CI->db->query($qry);
            $co_vendor_data = $qry_exe->result_array();
        }
        $phone_no = isset($co_vendor_data[0]['email_id']) && $co_vendor_data[0]['email_id'] != "" ?  $co_vendor_data[0]['email_id'] : "";


        $email_data = get_msg_template('manifest_prealert_email', 'email', $manifest_id, 7, $phone_no, $pdf_path = '', $otp_no = '', $sheet_id = '', $pdf_array);
        return $email_data;
        // echo "<pre>";print_r($email_data);exit;
    }
}

function send_free_form_email($docket_id = 0, $pdf_array = array(), $extra_cc_emails = "")
{
    $CI = &get_instance();
    if ($docket_id > 0) {
        $qry = "SELECT id,customer_id FROM docket WHERE id='" . $docket_id . "'";
        $qry_exe = $CI->db->query($qry);
        $docket_data = $qry_exe->row_array();

        $qry = "SELECT id,contact_no,operation_email_id as email_id,dial_code,country FROM customer WHERE status IN(1,2) AND id='" . $docket_data['customer_id'] . "'";
        $qry_exe = $CI->db->query($qry);
        $module_data = $qry_exe->row_array();
        $phone_no = isset($module_data['email_id']) ? trim($module_data['email_id']) : '';

        get_msg_template('send_awb_free_form', 'email', $docket_id, 1, $phone_no, $pdf_path = '', $otp_no = '', $sheet_id = '', $pdf_array, $extra_cc_emails);
    }
}

