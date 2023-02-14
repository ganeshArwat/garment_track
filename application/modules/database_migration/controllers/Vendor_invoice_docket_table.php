<?php
class Vendor_invoice_docket_table extends MX_Controller
{
    public function column_list()
    {
        $column_structure = array(
            0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
            1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            2 => 'Field:vendor_invoice_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            3 => 'Field:docket_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            4 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            5 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            6 => 'Field:forwarding_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            7 => 'Field:amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            8 => 'Field:fsc_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            9 => 'Field:grand_total, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            10 => 'Field:vendor_weight, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
        );
        return $column_structure;
    }
}
