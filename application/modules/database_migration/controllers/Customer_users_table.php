<?php
class Customer_users_table extends MX_Controller
{
    public function column_list()
    {
        $column_structure = array(
            0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
            1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            2 => 'Field:customer_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            3 => 'Field:name, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            4 => 'Field:email_id, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            5 => 'Field:password, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            6 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            7 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            8 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            9 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            10 => 'Field:auth_token, Type:text, Null:NO, Key:, Default:, Extra:',
            11 => 'Field:plain_password, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            12 => 'Field:forgot_password_key, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            13 => 'Field:fp_key_expiry_time, Type:datetime, Null:NO, Key:, Default:, Extra:',
            14 => 'Field:is_admin, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:'
        );
        return $column_structure;
    }
}
