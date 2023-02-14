<?php
class Refresh_log_table extends MX_Controller
{
    public function column_list()
    {
        $column_structure = array(
            0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
            1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            2 => 'Field:module_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            3 => 'Field:module_type, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
            4 => 'Field:refresh_status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            5 => 'Field:refresh_msg, Type:text, Null:NO, Key:, Default:, Extra:',
            6 => 'Field:processed_datetime, Type:datetime, Null:NO, Key:, Default:, Extra:',
            7 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            8 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            9 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            10 => 'Field:refresh_type, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            11 => 'Field:docket_invoice_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            12 => 'Field:print_label, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
        );
        return $column_structure;
    }
}
