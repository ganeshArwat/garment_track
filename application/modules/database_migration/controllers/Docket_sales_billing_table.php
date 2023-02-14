<?php
class Docket_sales_billing_table extends MX_Controller
{
    public function column_list()
    {
        $column_structure = array(
            0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
            1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            2 => 'Field:docket_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            3 => 'Field:freight_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            4 => 'Field:total_other_charge, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            5 => 'Field:fsc_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            6 => 'Field:discount_per, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            7 => 'Field:discount_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            8 => 'Field:freight_after_dis, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            9 => 'Field:sub_total, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            10 => 'Field:non_taxable_amt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            11 => 'Field:taxable_amt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            12 => 'Field:gst_per, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            13 => 'Field:igst_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            14 => 'Field:cgst_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            15 => 'Field:sgst_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            16 => 'Field:grand_total, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            17 => 'Field:sales_fsc_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            18 => 'Field:is_gst_apply, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
            19 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            20 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            21 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            22 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            23 => 'Field:edited_field, Type:text, Null:NO, Key:, Default:, Extra:',
            24 => 'Field:fsc_per, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            25 => 'Field:gst_type, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            26 => 'Field:adjustment_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            27 => 'Field:edi_discount_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            28 => 'Field:company_tax_type, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            29 => 'Field:invoice_remark, Type:text, Null:NO, Key:, Default:, Extra:',
            30 => 'Field:invoice_remark2, Type:text, Null:NO, Key:, Default:, Extra:',
            31 => 'Field:invoice_remark3, Type:text, Null:NO, Key:, Default:, Extra:',
            32 => 'Field:invoice_remark4, Type:text, Null:NO, Key:, Default:, Extra:',
            33 => 'Field:ori_zone_service_type, Type:int(11), Null:NO, Key:, Default:, Extra:',
            34 => 'Field:dest_zone_service_type, Type:int(11), Null:NO, Key:, Default:, Extra:',
        );
        return $column_structure;
    }
}
