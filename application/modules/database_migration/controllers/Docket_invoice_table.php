<?php
class Docket_invoice_table extends MX_Controller
{
    public function column_list()
    {
        $column_structure = array(
            0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
            1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            2 => 'Field:customer_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            3 => 'Field:invoice_range_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            4 => 'Field:invoice_range_no, Type:int(11), Null:NO, Key:, Default:, Extra:',
            5 => 'Field:company_master_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            6 => 'Field:from_date, Type:date, Null:YES, Key:, Default:, Extra:',
            7 => 'Field:to_date, Type:date, Null:YES, Key:, Default:, Extra:',
            8 => 'Field:invoice_date, Type:date, Null:NO, Key:, Default:, Extra:',
            9 => 'Field:due_date, Type:date, Null:NO, Key:, Default:, Extra:',
            10 => 'Field:customer_note, Type:text, Null:NO, Key:, Default:, Extra:',
            11 => 'Field:po_number, Type:text, Null:NO, Key:, Default:, Extra:',
            12 => 'Field:invoice_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            13 => 'Field:docket_count, Type:int(11), Null:NO, Key:, Default:, Extra:',
            14 => 'Field:invoice_type, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
            15 => 'Field:grand_total, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            16 => 'Field:shipper_name, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            17 => 'Field:shipper_code, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            18 => 'Field:shipper_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            19 => 'Field:payment_received, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
            20 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            21 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            22 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            23 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            24 => 'Field:migration_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            25 => 'Field:bank_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            26 => 'Field:invoice_status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            27 => 'Field:project_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            28 => 'Field:vendor_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            29 => 'Field:material_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            30 => 'Field:brand_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            31 => 'Field:is_email_sent, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
            32 => 'Field:docket_shipper_id, Type:int(11), Null:NO, Key:, Default:, Extra:',

            33 => 'Field:non_taxable_amt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            34 => 'Field:taxable_amt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            35 => 'Field:gst_per, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            36 => 'Field:igst_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            37 => 'Field:cgst_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            38 => 'Field:sgst_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            39 => 'Field:co_vendor_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            40 => 'Field:irn_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            41 => 'Field:sac_taxable_amt, Type:text, Null:NO, Key:, Default:, Extra:',
            42 => 'Field:is_cancelled, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
            43 => 'Field:message_line, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            44 => 'Field:message_line_id, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            45 => 'Field:edit_field, Type:text, Null:NO, Key:, Default:, Extra:',
            46 => 'Field:email_send_date, Type:date, Null:NO, Key:, Default:, Extra:',
            47 => 'Field:product_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
        );
        return $column_structure;
    }
}
