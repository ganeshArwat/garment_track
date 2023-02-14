<?php
class Run_sheet_docket_table extends MX_Controller
{
    public function column_list()
    {
        $column_structure = array(
            0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
            1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            2 => 'Field:run_sheet_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            3 => 'Field:track_by_type, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
            4 => 'Field:awb_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            5 => 'Field:customer_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            6 => 'Field:weight, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            7 => 'Field:pieces, Type:int(11), Null:NO, Key:, Default:, Extra:',
            8 => 'Field:run_state_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            9 => 'Field:reason_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            10 => 'Field:receiver_name, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            11 => 'Field:delivery_date, Type:date, Null:NO, Key:, Default:, Extra:',
            12 => 'Field:delivery_time, Type:time, Null:NO, Key:, Default:, Extra:',
            13 => 'Field:remark, Type:text, Null:NO, Key:, Default:, Extra:',
            14 => 'Field:pod_file, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            15 => 'Field:docket_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            16 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            17 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            18 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            19 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            20 => 'Field:docket_item_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            21 => 'Field:delivery_otp, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            22 => 'Field:entry_delivery_otp, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            23 => 'Field:migration_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
        );
        return $column_structure;
    }
}
