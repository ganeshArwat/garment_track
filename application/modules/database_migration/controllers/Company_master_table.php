<?php
class Company_master_table extends MX_Controller
{
    public function column_list()
    {
        $column_structure = array(
            0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
            1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            2 => 'Field:name, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            3 => 'Field:code, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            4 => 'Field:email_id, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            5 => 'Field:contact_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            6 => 'Field:website, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            7 => 'Field:city, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            8 => 'Field:state, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            9 => 'Field:address, Type:text, Null:NO, Key:, Default:, Extra:',
            10 => 'Field:billing_company, Type:int(11), Null:NO, Key:, Default:, Extra:',
            11 => 'Field:pan_number, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            12 => 'Field:cin_number, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            13 => 'Field:gst_number, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            14 => 'Field:sac_code, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            15 => 'Field:text_color, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            16 => 'Field:border_color, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            17 => 'Field:background_color, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            18 => 'Field:logo_file, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            19 => 'Field:signature_file, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            20 => 'Field:stamp_file, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            21 => 'Field:authorization_file, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            22 => 'Field:supply_place, Type:text, Null:NO, Key:, Default:, Extra:',
            23 => 'Field:country, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            24 => 'Field:pincode, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            25 => 'Field:address1, Type:text, Null:NO, Key:, Default:, Extra:',
            26 => 'Field:courier_reg_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            27 => 'Field:auth_courier_name, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            28 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            29 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            30 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            31 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            32 => 'Field:tax_type, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            33 => 'Field:migration_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            34 => 'Field:authorization_letter, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            35 => 'Field:customer_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            36 => 'Field:co_vendor_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            37 => 'Field:einvoice_api_user, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            38 => 'Field:einvoice_api_pass, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            39 => 'Field:whatsapp_token, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            40 => 'Field:email_configuration_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            41 => 'Field:trn_number, Type:varchar(255), Null:NO, Key:, Default:, Extra:',

        );
        return $column_structure;
    }
}
