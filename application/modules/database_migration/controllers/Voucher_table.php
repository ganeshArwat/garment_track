<?php
class Voucher_table extends MX_Controller
{
    public function column_list()
    {
        $column_structure = array(
            0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
            1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            2 => 'Field:voucher_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            3 => 'Field:given_amount_total, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            4 => 'Field:voucher_amount_total, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            5 => 'Field:balance_amount_total, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            6 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            7 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            8 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            9 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            10 => 'Field:is_checked_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            11 => 'Field:is_locked, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
            12 => 'Field:company_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            13 => 'Field:bank_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            14 => 'Field:voucher_date, Type:date, Null:NO, Key:, Default:, Extra:',
        );
        return $column_structure;
    }
}
