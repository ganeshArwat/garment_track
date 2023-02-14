<?php
class Vendor_estimate_data_table extends MX_Controller
{
    public function column_list()
    {
        $column_structure = array(
            0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
            1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            2 => 'Field:vendor_estimate_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            3 => 'Field:customer_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            4 => 'Field:origin_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            5 => 'Field:destination_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            6 => 'Field:dest_zone_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            7 => 'Field:vendor_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            8 => 'Field:booking_date, Type:date, Null:NO, Key:, Default:, Extra:',
            9 => 'Field:material, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            10 => 'Field:quantity, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            11 => 'Field:pcs, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            12 => 'Field:product_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            13 => 'Field:actual_weight, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            14 => 'Field:volumetric_weight, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            15 => 'Field:shipment_value, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            16 => 'Field:chargeable_wt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            17 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            18 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            19 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            20 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
        );
        return $column_structure;
    }
}
