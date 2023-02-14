<?php
                        class Purchase_include_data_table extends MX_Controller
                        {
                            public function column_list()
                            {
$column_structure = array(
0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
2 => 'Field:credit_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
3 => 'Field:credit_id_type, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
4 => 'Field:invoice_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
5 => 'Field:invoice_type, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
6 => 'Field:grand_total_amt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
7 => 'Field:deduction_amt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
8 => 'Field:tds_amt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
9 => 'Field:received_amt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
10 => 'Field:round_off_amt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
11 => 'Field:reference, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
12 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
13 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
14 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
15 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
);
return $column_structure;
}
                        }
                        ?>