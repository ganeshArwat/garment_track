<?php
                        class Voucher_data_table extends MX_Controller
                        {
                            public function column_list()
                            {
$column_structure = array(
0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
2 => 'Field:voucher_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
3 => 'Field:voucher_date, Type:date, Null:NO, Key:, Default:, Extra:',
4 => 'Field:voucher_entry_date, Type:date, Null:NO, Key:, Default:, Extra:',
5 => 'Field:cash_given_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
6 => 'Field:voucher_submit_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
7 => 'Field:department_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
8 => 'Field:given_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
9 => 'Field:voucher_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
10 => 'Field:balance_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
11 => 'Field:expense_type_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
12 => 'Field:expense_sub_type_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
13 => 'Field:vehicle_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
14 => 'Field:description, Type:text, Null:NO, Key:, Default:, Extra:',
15 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
16 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
17 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
18 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
19 => 'Field:payment_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
20 => 'Field:voucher_approved_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
);
return $column_structure;
}
                        }
                        ?>