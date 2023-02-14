<?php
class Run_sheet_table extends MX_Controller
{
    public function column_list()
    {
        $column_structure = array(
            0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
            1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            2 => 'Field:run_sheet_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            3 => 'Field:drs_date, Type:date, Null:NO, Key:, Default:, Extra:',
            4 => 'Field:drs_time, Type:time, Null:NO, Key:, Default:, Extra:',
            5 => 'Field:hub_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            6 => 'Field:user_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            7 => 'Field:route_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            8 => 'Field:vehicle_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            9 => 'Field:vehicle_type, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            10 => 'Field:driver_name, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            11 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            12 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            13 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            14 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            15 => 'Field:migration_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            16 => 'Field:collected_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            17 => 'Field:total_cash, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            18 => 'Field:route_name, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            19 => 'Field:drs_upload_img, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
        );
        return $column_structure;
    }
}
