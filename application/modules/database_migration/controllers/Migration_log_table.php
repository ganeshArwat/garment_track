<?php
class Migration_log_table extends MX_Controller
{
    public function column_list()
    {
        $column_structure = array(
            0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
            1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            2 => 'Field:config_key, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            3 => 'Field:config_value, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
        );
        return $column_structure;
    }
}
