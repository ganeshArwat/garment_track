<?php
class Opening_balance_table extends MX_Controller
{
    public function column_list()
    {
        $column_structure = array(
            0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
            1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            2 => 'Field:customer_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            3 => 'Field:opening_amount, Type:int(11), Null:NO, Key:, Default:, Extra:',
            4 => 'Field:opening_date, Type:date, Null:NO, Key:, Default:, Extra:',
            5 => 'Field:particular, Type:text, Null:NO, Key:, Default:, Extra:',
            6 => 'Field:deduction_amt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            7 => 'Field:tds_amt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            8 => 'Field:received_amt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            9 => 'Field:round_off_amt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            10 => 'Field:leftover_amt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            11 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            12 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            13 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            14 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            15 => 'Field:payment_type, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            16 => 'Field:migration_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            17 => 'Field:company_master_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            18 => 'Field:sector_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
        );
        return $column_structure;
    }
}
