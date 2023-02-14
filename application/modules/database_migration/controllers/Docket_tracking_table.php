<?php
class Docket_tracking_table extends MX_Controller
{
    public function column_list()
    {
        $column_structure = array(
            0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
            1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            2 => 'Field:docket_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            3 => 'Field:event_date_time, Type:datetime, Null:NO, Key:, Default:, Extra:',
            4 => 'Field:event_desc, Type:text, Null:NO, Key:, Default:, Extra:',
            5 => 'Field:event_location, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            6 => 'Field:event_type, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            7 => 'Field:add_city, Type:varchar(255), Null:YES, Key:, Default:, Extra:',
            8 => 'Field:add_state, Type:varchar(255), Null:YES, Key:, Default:, Extra:',
            9 => 'Field:add_zip, Type:varchar(255), Null:YES, Key:, Default:, Extra:',
            10 => 'Field:add_country, Type:varchar(255), Null:YES, Key:, Default:, Extra:',
            11 => 'Field:add_country_name, Type:varchar(255), Null:YES, Key:, Default:, Extra:',
            12 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            13 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            14 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:current_timestamp(), Extra:',
            15 => 'Field:modified_by, Type:int(11), Null:YES, Key:, Default:, Extra:',
            16 => 'Field:event_add_type, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
            17 => 'Field:docket_state_id, Type:int(11), Null:YES, Key:, Default:, Extra:',
            18 => 'Field:module_id, Type:int(11), Null:YES, Key:, Default:, Extra:',
            19 => 'Field:module_type, Type:tinyint(1), Null:YES, Key:, Default:, Extra:',
            20 => 'Field:tracking_type, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            21 => 'Field:docket_item_id, Type:int(11), Null:YES, Key:, Default:, Extra:',
            22 => 'Field:event_remark, Type:text, Null:YES, Key:, Default:, Extra:',
            23 => 'Field:desc_edited, Type:tinyint(1), Null:YES, Key:, Default:2, Extra:',
        );
        return $column_structure;
    }
}
