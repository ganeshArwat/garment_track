<?php
class Docket_items_table extends MX_Controller
{
    public function column_list()
    {
        $column_structure = array(
            0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
            1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            2 => 'Field:docket_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            3 => 'Field:parcel_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            4 => 'Field:box_no, Type:int(11), Null:NO, Key:, Default:, Extra:',
            5 => 'Field:actual_wt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            6 => 'Field:item_length, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            7 => 'Field:item_width, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            8 => 'Field:item_height, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            9 => 'Field:box_count, Type:int(11), Null:NO, Key:, Default:, Extra:',
            10 => 'Field:volumetric_wt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            11 => 'Field:chargeable_wt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            12 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            13 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            14 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            15 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            16 => 'Field:is_pickup_scan, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
            17 => 'Field:is_inscan, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
            18 => 'Field:inscan_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            19 => 'Field:delivery_date, Type:date, Null:NO, Key:, Default:, Extra:',
            20 => 'Field:delivery_time, Type:time, Null:NO, Key:, Default:, Extra:',
            21 => 'Field:receiver_name, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            22 => 'Field:delivery_remarks, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            23 => 'Field:no_of_box, Type:int(11), Null:NO, Key:, Default:, Extra:',
            24 => 'Field:sr_no, Type:int(11), Null:NO, Key:, Default:, Extra:',
            25 => 'Field:inscan_by_type, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',

        );
        return $column_structure;
    }
}
