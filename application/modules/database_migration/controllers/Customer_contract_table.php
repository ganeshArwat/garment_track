<?php
class Customer_contract_table extends MX_Controller
{
    public function column_list()
    {
        $column_structure = array(
            0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
            1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            2 => 'Field:customer_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            3 => 'Field:vendor_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            4 => 'Field:product_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            5 => 'Field:co_vendor_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            6 => 'Field:dest_zone_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            7 => 'Field:dest_location_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            8 => 'Field:dest_city_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            9 => 'Field:ori_zone_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            10 => 'Field:ori_location_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            11 => 'Field:ori_city_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            12 => 'Field:effective_min, Type:date, Null:NO, Key:, Default:, Extra:',
            13 => 'Field:effective_max, Type:date, Null:NO, Key:, Default:, Extra:',
            14 => 'Field:remark, Type:text, Null:NO, Key:, Default:, Extra:',
            15 => 'Field:method_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            16 => 'Field:tat, Type:int(11), Null:NO, Key:, Default:, Extra:',
            17 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            18 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            19 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            20 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            21 => 'Field:migration_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            22 => 'Field:dest_state_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            23 => 'Field:ori_state_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            24 => 'Field:contract_type, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            25 => 'Field:ori_hub_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            26 => 'Field:dest_hub_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
        );
        return $column_structure;
    }
}
