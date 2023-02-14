<?php
class Docket_service_field_table extends MX_Controller
{
    public function column_list()
    {
        $column_structure = array(
            0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
            1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            2 => 'Field:service_name, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            3 => 'Field:dhl_special_service, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
            4 => 'Field:dhl_using_igst, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
            5 => 'Field:dhl_using_bond_ut, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
            6 => 'Field:dhl_non_plt, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
            7 => 'Field:dhl_more_than_50k, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
            8 => 'Field:total_igst, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            9 => 'Field:sticker_print_format, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
            10 => 'Field:extended_liability, Type:int(11), Null:NO, Key:, Default:, Extra:',
            11 => 'Field:declared_value, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            12 => 'Field:extended_liability_charge, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            13 => 'Field:liability_currency, Type:int(11), Null:NO, Key:, Default:, Extra:',
            14 => 'Field:docket_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            15 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            16 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            17 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            18 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            19 => 'Field:payment_mode, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
            20 => 'Field:cod_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            21 => 'Field:fedex_api_service, Type:int(11), Null:NO, Key:, Default:, Extra:',
            22 => 'Field:csb_selection, Type:int(11), Null:NO, Key:, Default:, Extra:',
            23 => 'Field:terms_of_invoice, Type:int(11), Null:NO, Key:, Default:, Extra:',
            24 => 'Field:special_service_accessibility, Type:int(11), Null:NO, Key:, Default:, Extra:',
            25 => 'Field:duty_payor, Type:int(11), Null:NO, Key:, Default:, Extra:',
            26 => 'Field:is_using_gst, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
            27 => 'Field:special_service, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
            28 => 'Field:special_service_type, Type:int(11), Null:NO, Key:, Default:, Extra:',
            29 => 'Field:rov_insurance, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            30 => 'Field:atlantic_service_name, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            31 => 'Field:atlantic_vendor_name, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            32 => 'Field:product_code, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            33 => 'Field:total_insured_value, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            34 => 'Field:shipping_charge_payor, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            35 => 'Field:account_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            36 => 'Field:duty_shipping_charge_payor, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            37 => 'Field:duty_account_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:'
        );
        return $column_structure;
    }
}
