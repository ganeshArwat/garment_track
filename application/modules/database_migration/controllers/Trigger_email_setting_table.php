<?php
class Trigger_email_setting_table extends MX_Controller
{
    public function column_list()
    {
        $column_structure = array(
            0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
            1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            2 => 'Field:email_configuration_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            3 => 'Field:email_trigger_key, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            4 => 'Field:email_subject, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            5 => 'Field:email_receipient, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            6 => 'Field:email_body, Type:text, Null:NO, Key:, Default:, Extra:',
            7 => 'Field:send_email, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            8 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            9 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            10 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            11 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            12 => 'Field:cc_email, Type:text, Null:NO, Key:, Default:, Extra:',
            13 => 'Field:company_master_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            14 => 'Field:table_data_master, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            15 => 'Field:table_data_field, Type:text, Null:NO, Key:, Default:, Extra:',
        );
        return $column_structure;
    }
}
