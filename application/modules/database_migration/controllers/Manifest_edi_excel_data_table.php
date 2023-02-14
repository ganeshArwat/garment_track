<?php
class Manifest_edi_excel_data_table extends MX_Controller
{
    public function column_list()
    {
        $column_structure = array(
            0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
            1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            2 => 'Field:manifest_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            3 => 'Field:mawb_number, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            4 => 'Field:no_of_bags_pkgs_pieces_uld, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            5 => 'Field:numbers_of_hawbs, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            6 => 'Field:hawb_number, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            7 => 'Field:description_of_goods, Type:text, Null:NO, Key:, Default:, Extra:',
            8 => 'Field:shipment_value, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            9 => 'Field:consignor_name, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            10 => 'Field:consignor_address_1, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            11 => 'Field:consignor_address_2, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            12 => 'Field:consignor_city, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            13 => 'Field:consignor_state, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            14 => 'Field:consignor_postal_code, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            15 => 'Field:consignor_country, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            16 => 'Field:consignee_name, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            17 => 'Field:consignee_address_1, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            18 => 'Field:consignee_address_2, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            19 => 'Field:consignee_city, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            20 => 'Field:consignee_state, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            21 => 'Field:consignee_postal_code, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            22 => 'Field:consignee_country, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            23 => 'Field:actual_wt, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            24 => 'Field:total_igst_paid, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            25 => 'Field:payment_through_gst, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            26 => 'Field:bond_or_ut, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            27 => 'Field:date_of_export_invoice, Type:date, Null:NO, Key:, Default:, Extra:',
            28 => 'Field:export_invoice_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            29 => 'Field:date_of_gst_invoice, Type:date, Null:NO, Key:, Default:, Extra:',
            30 => 'Field:gst_invoice_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            31 => 'Field:gstin_id, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            32 => 'Field:gstin_type, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            33 => 'Field:mhbs_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            34 => 'Field:hawb_crn, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            35 => 'Field:crn_mhbs_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            36 => 'Field:invoice_value, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            37 => 'Field:invoice_currency, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            38 => 'Field:fob_value, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            39 => 'Field:ad_code, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            40 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            41 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
        );
        return $column_structure;
    }
}
