<?php
class App_settings_table extends MX_Controller
{
    public function column_list()
    {
        $column_structure = array(
            0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
            1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            2 => 'Field:module_name, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            3 => 'Field:config_key, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            4 => 'Field:config_value, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            5 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            6 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            7 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            8 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            9 => 'Field:comment, Type:text, Null:NO, Key:, Default:, Extra:',
        );
        return $column_structure;
    }
}
