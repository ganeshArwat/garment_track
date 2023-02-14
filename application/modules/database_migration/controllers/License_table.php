<?php
class License_table extends MX_Controller
{
    public function column_list()
    {
        $column_structure = array(
            0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
            1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            2 => 'Field:name, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            3 => 'Field:code, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            4 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            5 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            6 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            7 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            8 => 'Field:logo_file, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            9 => 'Field:email, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            10 => 'Field:phone_number, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            11 => 'Field:address, Type:text, Null:NO, Key:, Default:, Extra:',
            12 => 'Field:website, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            13 => 'Field:city, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            14 => 'Field:state, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            15 => 'Field:address, Type:text, Null:NO, Key:, Default:, Extra:',
            16 => 'Field:pincode, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            17 => 'Field:country, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            18 => 'Field:courier_reg_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            19 => 'Field:auth_courier_name, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            20 => 'Field:file_name, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            21 => 'Field:address2, Type:text, Null:NO, Key:, Default:, Extra:',
        );
        return $column_structure;
    }
}
