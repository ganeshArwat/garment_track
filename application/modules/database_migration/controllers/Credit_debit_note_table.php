<?php
class Credit_debit_note_table extends MX_Controller
{
    public function column_list()
    {
        $column_structure = array(
            0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
            1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            2 => 'Field:note_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            3 => 'Field:note_category, Type:int(11), Null:NO, Key:, Default:, Extra:',
            4 => 'Field:note_type, Type:int(11), Null:NO, Key:, Default:, Extra:',
            5 => 'Field:customer_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            6 => 'Field:note_date, Type:date, Null:NO, Key:, Default:, Extra:',
            7 => 'Field:grand_total_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            8 => 'Field:auto_generate_no, Type:int(11), Null:NO, Key:, Default:, Extra:',
            9 => 'Field:deduction_amt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            10 => 'Field:tds_amt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            11 => 'Field:received_amt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            12 => 'Field:round_off_amt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            13 => 'Field:leftover_amt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            14 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            15 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            16 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            17 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            18 => 'Field:migration_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            19 => 'Field:company_master_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            20 => 'Field:irn_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            21 => 'Field:sector_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            22 => 'Field:round_off, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            23 => 'Field:is_email_sent, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
            24 => 'Field:email_send_date, Type:date, Null:NO, Key:, Default:, Extra:',
        );
        return $column_structure;
    }
}
