<?php
class Otp_log_table extends MX_Controller
{
    public function column_list()
    {
        $column_structure = array(
            0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
            1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            2 => 'Field:mobile_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            3 => 'Field:email_id, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            4 => 'Field:otp_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            5 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
        );
        return $column_structure;
    }
}
