<?php
class Pick_up_sheets_table extends MX_Controller
{
    public function column_list()
    {
        $column_structure = array(
            0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
            1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            2 => 'Field:route_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            3 => 'Field:hub_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            4 => 'Field:driver_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            5 => 'Field:controller_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            6 => 'Field:sheet_date, Type:date, Null:NO, Key:, Default:, Extra:',
            7 => 'Field:vehicle_detail_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            8 => 'Field:sheet_cancel, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
            9 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            10 => 'Field:sheet_status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            11 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            12 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            13 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            14 => 'Field:migration_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            15 => 'Field:sheet_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            16 => 'Field:auto_generate_no, Type:int(11), Null:NO, Key:, Default:, Extra:',
            17 => 'Field:driver_text, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            18 => 'Field:driver_edit, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
        );
        return $column_structure;
    }
}
