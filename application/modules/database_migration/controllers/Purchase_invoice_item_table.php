<?php
                        class Purchase_invoice_item_table extends MX_Controller
                        {
                            public function column_list()
                            {
$column_structure = array(
0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
2 => 'Field:purchase_invoice_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
3 => 'Field:description, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
4 => 'Field:rate, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
5 => 'Field:gst_type, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
6 => 'Field:tax_per, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
7 => 'Field:igst_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
8 => 'Field:cgst_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
9 => 'Field:sgst_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
10 => 'Field:total_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
11 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
12 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
13 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
14 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
15 => 'Field:docket_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
);
return $column_structure;
}
                        }
                        ?>