<?php
class Co_vendor_table extends MX_Controller
{
    public function column_list()
    {
        $column_structure = array(
            0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
            1 => 'Field:status, Type:tinyint(1), Null:YES, Key:, Default:1, Extra:',
            2 => 'Field:name, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            3 => 'Field:code, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            4 => 'Field:email_id, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            5 => 'Field:contact_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            6 => 'Field:address, Type:text, Null:NO, Key:, Default:, Extra:',
            7 => 'Field:co_vendor_type, Type:int(11), Null:NO, Key:, Default:, Extra:',
            8 => 'Field:tracking_head_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            9 => 'Field:label_head_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            10 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            11 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            12 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            13 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            14 => 'Field:calculate_tat, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            15 => 'Field:check_tat, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            16 => 'Field:vendor_send_auto_mis_email, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
            17 => 'Field:receiver_auto_mis_report, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            18 => 'Field:auto_generate_no, Type:int(11), Null:NO, Key:, Default:, Extra:',
            19 => 'Field:vendor_type, Type:int(11), Null:NO, Key:, Default:1, Extra:',
            20 => 'Field:is_gst_apply, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
            21 => 'Field:gst_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            22 => 'Field:gst_per, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            23 => 'Field:api_credentials, Type:text, Null:NO, Key:, Default:, Extra:',

            24 => 'Field:email_time1, Type:time, Null:NO, Key:, Default:, Extra:',
            25 => 'Field:shipment_range, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
            26 => 'Field:email_once_shipment_deliver, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
            27 => 'Field:dont_send_last_month_deliver, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
            28 => 'Field:send_forwarding_no_2_in_website, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
            29 => 'Field:dont_show_click_link, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
            30 => 'Field:enable_api_link, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
            31 => 'Field:api_base_url, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            32 => 'Field:api_username, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            34 => 'Field:api_customer_code, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            35 => 'Field:api_password, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            36 => 'Field:is_sale_vendor, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
            37 => 'Field:company_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            38 => 'Field:expense_type_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            39 => 'Field:send_forwarding_no_2_in_tracking_api, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
            40 => 'Field:vat_per, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            41 => 'Field:vendor_head_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            42 => 'Field:api_service_code, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            43 => 'Field:api_vendor_code, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            44 => 'Field:api_company_id, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            45 => 'Field:api_envelope_code, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            46 => 'Field:api_dox_code, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            47 => 'Field:api_nondox_code, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            48 => 'Field:hide_pod_image_from_website, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
            49 => 'Field:hide_pod_image_from_portal, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
            50 => 'Field:bluedart_product_code, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            51 => 'Field:bluedart_sub_product_code, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
        );
        return $column_structure;
    }
}
