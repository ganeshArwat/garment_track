<?php
class Payment_receipt_table extends MX_Controller
{
    public function column_list()
    {
        $column_structure = array(
            0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
            1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            2 => 'Field:payment_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            3 => 'Field:customer_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            4 => 'Field:receipt_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            5 => 'Field:payment_type_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            6 => 'Field:cheque_date, Type:date, Null:NO, Key:, Default:, Extra:',
            7 => 'Field:cheque_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            8 => 'Field:account_no_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            9 => 'Field:receipt_date, Type:date, Null:NO, Key:, Default:, Extra:',
            10 => 'Field:particular, Type:text, Null:NO, Key:, Default:, Extra:',
            11 => 'Field:auto_generate_no, Type:int(11), Null:NO, Key:, Default:, Extra:',
            12 => 'Field:deduction_amt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            13 => 'Field:tds_amt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            14 => 'Field:received_amt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            15 => 'Field:round_off_amt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            16 => 'Field:leftover_amt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            17 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            18 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            19 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            20 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            21 => 'Field:purchase_payment_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            22 => 'Field:migration_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            23 => 'Field:include_invoice, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
            24 => 'Field:include_opening_balance, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
            25 => 'Field:include_debit_note, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
            26 => 'Field:include_unbilled, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
            28 => 'Field:company_master_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            29 => 'Field:include_future_invoice, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
            30 => 'Field:send_account_no_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            31 => 'Field:sector_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            32 => 'Field:include_future_debit_note, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
            33 => 'Field:receipt_status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
        );
        return $column_structure;
    }
}
