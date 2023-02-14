<?php
class Docket_free_form_invoice_table extends MX_Controller
{
    public function column_list()
    {
        $column_structure = array(
            0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
            1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            2 => 'Field:docket_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            3 => 'Field:box_no, Type:int(11), Null:NO, Key:, Default:, Extra:',
            4 => 'Field:sr_no, Type:int(11), Null:NO, Key:, Default:, Extra:',
            5 => 'Field:description_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            6 => 'Field:hs_code, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            7 => 'Field:unit_type, Type:int(11), Null:NO, Key:, Default:, Extra:',
            8 => 'Field:quantity, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            9 => 'Field:unit_wt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            10 => 'Field:igst_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            11 => 'Field:unit_rate, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            12 => 'Field:final_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            13 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            14 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            15 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            16 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            17 => 'Field:description_name, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
        );
        return $column_structure;
    }
}
