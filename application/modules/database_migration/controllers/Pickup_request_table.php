<?php
class Pickup_request_table extends MX_Controller
{
    public function column_list()
    {
        $column_structure = array(
            0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
            1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            2 => 'Field:ref_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            3 => 'Field:customer_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            4 => 'Field:vendor_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            5 => 'Field:call_date, Type:date, Null:NO, Key:, Default:, Extra:',
            6 => 'Field:call_time, Type:time, Null:NO, Key:, Default:, Extra:',
            7 => 'Field:co_vendor_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            8 => 'Field:contact_person_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            9 => 'Field:contact_person_name, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            10 => 'Field:pickup_cancel, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
            11 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            12 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            13 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            14 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            15 => 'Field:auto_generate_no, Type:int(11), Null:NO, Key:, Default:, Extra:',
            16 => 'Field:pickup_sheet_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            17 => 'Field:pickup_status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            18 => 'Field:migration_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            19 => 'Field:pickup_dispatch_type, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            20 => 'Field:docket_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            21 => 'Field:edited_field, Type:text, Null:NO, Key:, Default:, Extra:',
            22 => 'Field:customer_text, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
        );
        return $column_structure;
    }
}
