<?php
class State_zone_map_table extends MX_Controller
{
    public function column_list()
    {
        $column_structure = array(
            0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
            1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            2 => 'Field:state_id, Type:int(11), Null:NO, Key:MUL, Default:, Extra:',
            3 => 'Field:billing_type, Type:int(11), Null:NO, Key:, Default:, Extra:',
            4 => 'Field:zone_id, Type:int(11), Null:NO, Key:MUL, Default:, Extra:',
            5 => 'Field:vendor_id, Type:int(11), Null:NO, Key:MUL, Default:, Extra:',
            6 => 'Field:co_vendor_id, Type:int(11), Null:NO, Key:MUL, Default:, Extra:',
            7 => 'Field:eff_min_date, Type:date, Null:NO, Key:, Default:, Extra:',
            8 => 'Field:eff_max_date, Type:date, Null:NO, Key:, Default:, Extra:',
            9 => 'Field:delivery_area, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            10 => 'Field:tat, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            11 => 'Field:created_date, Type:date, Null:NO, Key:, Default:, Extra:',
            12 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            13 => 'Field:modified_date, Type:date, Null:NO, Key:, Default:, Extra:',
            14 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            15 => 'Field:migration_id, Type:int(11), Null:NO, Key:MUL, Default:, Extra:',
            16 => 'Field:customer_id, Type:int(11), Null:NO, Key:MUL, Default:, Extra:',
            17 => 'Field:service_type, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
        );
        return $column_structure;
    }
}
