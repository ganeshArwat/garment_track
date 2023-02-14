<?php
class Company_bank_table extends MX_Controller
{
    public function column_list()
    {
        $column_structure = array(
            0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
            1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            2 => 'Field:company_master_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            3 => 'Field:bank_name, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            4 => 'Field:account_type, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
            5 => 'Field:bank_swift_id, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            6 => 'Field:branch, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            7 => 'Field:account_name, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            8 => 'Field:ifsc_code, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            9 => 'Field:account_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            10 => 'Field:address, Type:text, Null:NO, Key:, Default:, Extra:',
            11 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            12 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            13 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            14 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            15 => 'Field:opening_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            16 => 'Field:opening_date, Type:date, Null:YES, Key:, Default:, Extra:',
            17 => 'Field:opening_type, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
            18 => 'Field:available_balance, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            19 => 'Field:serial_no, Type:int(11), Null:NO, Key:, Default:, Extra:',
            20 => 'Field:bank_iban, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            21 => 'Field:upi_id, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            22 => 'Field:sort_code, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            23 => 'Field:upi_number, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            24 => 'Field:upi_image, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            25 => 'Field:qr_status, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
            26 => 'Field:closing_balance, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
        );
        return $column_structure;
    }
}
