<?php
class Vendor_mapping_table extends MX_Controller
{
    public function column_list()
    {
        $column_structure = array(
            0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
            1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            2 => 'Field:effective_min, Type:date, Null:NO, Key:, Default:, Extra:',
            3 => 'Field:effective_max, Type:date, Null:NO, Key:, Default:, Extra:',
            4 => 'Field:vendor_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            5 => 'Field:ori_location_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            6 => 'Field:dest_location_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            7 => 'Field:co_vendor_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            8 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            9 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            10 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            11 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            12 => 'Field:ori_hub_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            13 => 'Field:dest_hub_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
        );
        return $column_structure;
    }
}
