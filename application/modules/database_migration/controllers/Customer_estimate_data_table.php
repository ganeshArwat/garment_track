<?php
class Customer_estimate_data_table extends MX_Controller
{
    public function column_list()
    {
        $column_structure = array(
            0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
            1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            2 => 'Field:customer_estimate_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            3 => 'Field:customer_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            4 => 'Field:origin_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            5 => 'Field:destination_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            6 => 'Field:dest_zone_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            7 => 'Field:vendor_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            8 => 'Field:co_vendor_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            9 => 'Field:booking_date, Type:date, Null:NO, Key:, Default:, Extra:',
            10 => 'Field:material, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            11 => 'Field:quantity, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            12 => 'Field:pcs, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            13 => 'Field:product_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            14 => 'Field:actual_weight, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            15 => 'Field:volumetric_weight, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            16 => 'Field:shipment_value, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            17 => 'Field:chargeable_wt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            18 => 'Field:freight_amt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            19 => 'Field:fsc_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            20 => 'Field:cgst_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            21 => 'Field:sgst_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            22 => 'Field:igst_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            23 => 'Field:grand_total_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            24 => 'Field:taxable_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            25 => 'Field:non_taxable_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            26 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            27 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            28 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            29 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',

            30 => 'Field:consignor_wt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            31 => 'Field:customer_contract_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            32 => 'Field:customer_contract_tat, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            33 => 'Field:cft_value, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            34 => 'Field:cft_multiplier, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            35 => 'Field:cft_contract_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            36 => 'Field:company_tax_type, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
            37 => 'Field:gst_per, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            38 => 'Field:fsc_per, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            39 => 'Field:total_other_charge, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            40 => 'Field:ori_zone_id, Type:int(11), Null:NO, Key:, Default:, Extra:'

        );
        return $column_structure;
    }
}
