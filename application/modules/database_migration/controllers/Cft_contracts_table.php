<?php
class Cft_contracts_table extends MX_Controller
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
            6 => 'Field:effective_min, Type:date, Null:NO, Key:, Default:, Extra:',
            7 => 'Field:effective_max, Type:date, Null:NO, Key:, Default:, Extra:',
            8 => 'Field:billing_type, Type:int(11), Null:NO, Key:, Default:, Extra:',
            9 => 'Field:cft_value, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            10 => 'Field:cft_multiplier, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            11 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            12 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            13 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            14 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            15 => 'Field:migration_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
        );
        return $column_structure;
    }
}
