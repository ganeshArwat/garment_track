<?php
class Rate_modifier_table extends MX_Controller
{
    public function column_list()
    {
        $column_structure = array(
            0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
            1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            2 => 'Field:charge_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            3 => 'Field:billing_type, Type:int(11), Null:NO, Key:, Default:, Extra:',
            4 => 'Field:fixed_amt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            5 => 'Field:min_amt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            6 => 'Field:freight_per, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            7 => 'Field:shipment_per, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            8 => 'Field:min_chargeable_wt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            9 => 'Field:max_chargeable_wt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            10 => 'Field:min_actual_wt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            11 => 'Field:max_actual_wt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            12 => 'Field:min_volume_wt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            13 => 'Field:max_volume_wt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            14 => 'Field:min_boxes, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            15 => 'Field:max_boxes, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            16 => 'Field:min_dimension, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            17 => 'Field:max_dimension, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            18 => 'Field:min_per_box_wt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            19 => 'Field:max_per_box_wt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            20 => 'Field:min_shipment_value, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            21 => 'Field:max_shipment_value, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            22 => 'Field:effective_from, Type:date, Null:NO, Key:, Default:, Extra:',
            23 => 'Field:effective_to, Type:date, Null:NO, Key:, Default:, Extra:',
            24 => 'Field:rate_per_type, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
            25 => 'Field:min_dim_per_pc, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
            26 => 'Field:max_dim_per_pc, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
            27 => 'Field:grith_check, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
            28 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            29 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            30 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            31 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            32 => 'Field:migration_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            33 => 'Field:slab_wise_rate, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
            34 => 'Field:freight_fsc_per, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            35 => 'Field:consignee_pincode, Type:text, Null:NO, Key:, Default:, Extra:',
            36 => 'Field:min_per_box_vol_wt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            37 => 'Field:max_per_box_vol_wt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            38 => 'Field:min_per_box_cha_wt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            39 => 'Field:max_per_box_cha_wt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            40 => 'Field:is_origin_oda, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
            41 => 'Field:is_dest_oda, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
        );
        return $column_structure;
    }
}
