<?php
class Customer_users_permission_map_table extends MX_Controller
{
    public function column_list()
    {
        $column_structure = array(
            0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
            1 => 'Field:permission_type, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            2 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            3 => 'Field:user_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            4 => 'Field:permission_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            5 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            6 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            7 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            8 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
        );
        return $column_structure;
    }
}
