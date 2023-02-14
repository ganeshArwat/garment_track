<?php
                        class Purchase_invoice_table extends MX_Controller
                        {
                            public function column_list()
                            {
$column_structure = array(
0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
2 => 'Field:invoice_number, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
3 => 'Field:purchase_vendor_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
4 => 'Field:company_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
5 => 'Field:from_date, Type:date, Null:NO, Key:, Default:, Extra:',
6 => 'Field:till_date, Type:date, Null:NO, Key:, Default:, Extra:',
7 => 'Field:invoice_date, Type:date, Null:NO, Key:, Default:, Extra:',
8 => 'Field:due_date, Type:date, Null:NO, Key:, Default:, Extra:',
9 => 'Field:grand_total_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
10 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
11 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
12 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
13 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
14 => 'Field:vendor_invoice_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
);
return $column_structure;
}
                        }
                        ?>