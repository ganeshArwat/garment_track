<?php
class Invoice_range_table extends MX_Controller
{
    public function column_list()
    {
        $column_structure = array(
            0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
            1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            2 => 'Field:name, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            3 => 'Field:code, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            4 => 'Field:invoice_prefix, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            5 => 'Field:range_start, Type:int(11), Null:NO, Key:, Default:, Extra:',
            6 => 'Field:range_end, Type:int(11), Null:NO, Key:, Default:, Extra:',
            7 => 'Field:invoice_suffix, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            8 => 'Field:is_non_gst, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
            9 => 'Field:company_master_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            10 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            11 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            12 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            13 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            14 => 'Field:custom_invoice_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
        );
        return $column_structure;
    }
}
