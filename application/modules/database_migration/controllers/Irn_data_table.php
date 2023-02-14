<?php
class Irn_data_table extends MX_Controller
{
    public function column_list()
    {
        $column_structure = array(
            0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
            1 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            2 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            3 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            4 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            5 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            6 => 'Field:responce_data, Type:text, Null:NO, Key:, Default:, Extra:',
            7 => 'Field:invoice_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            8 => 'Field:credit_debit_note_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            9 => 'Field:auth_token_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            10 => 'Field:ack_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            11 => 'Field:ack_dt, Type:datetime, Null:NO, Key:, Default:, Extra:',
            12 => 'Field:irn, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            13 => 'Field:signed_invoice, Type:text, Null:NO, Key:, Default:, Extra:',
            14 => 'Field:signed_qr_code, Type:text, Null:NO, Key:, Default:, Extra:',
            15 => 'Field:ewb_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            16 => 'Field:ewb_dt, Type:datetime, Null:NO, Key:, Default:, Extra:',
            17 => 'Field:ewb_valid_till, Type:datetime, Null:NO, Key:, Default:, Extra:',
            18 => 'Field:extracted_signed_invoice_data, Type:text, Null:NO, Key:, Default:, Extra:',
            19 => 'Field:extracted_signed_qr_code, Type:text, Null:NO, Key:, Default:, Extra:',
            20 => 'Field:qr_code_image, Type:text, Null:NO, Key:, Default:, Extra:',
            21 => 'Field:request_data, Type:text, Null:NO, Key:, Default:, Extra:',
            22 => 'Field:entry_type, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
        );
        return $column_structure;
    }
}
