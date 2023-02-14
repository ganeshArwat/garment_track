<?php
class Vendor_estimate_contract_table extends MX_Controller
{
    public function column_list()
    {
        $column_structure = array(
            0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
            1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            2 => 'Field:vendor_estimate_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            3 => 'Field:vendor_estimate_data_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            4 => 'Field:vendor_contract_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            5 => 'Field:freight_amt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            6 => 'Field:fsc_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            7 => 'Field:cgst_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            8 => 'Field:sgst_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            9 => 'Field:igst_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            10 => 'Field:grand_total_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            11 => 'Field:taxable_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            12 => 'Field:non_taxable_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            13 => 'Field:co_vendor_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            14 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            15 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            16 => 'Field:other_charge_total, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
        );
        return $column_structure;
    }
}
