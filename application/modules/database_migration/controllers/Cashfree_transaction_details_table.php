<?php
                        class Cashfree_transaction_details_table extends MX_Controller
                        {
                            public function column_list()
                            {
$column_structure = array(
0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
2 => 'Field:order_id, Type:varchar(255), Null:NO, Key:MUL, Default:, Extra:',
3 => 'Field:order_amount, Type:float(20,2), Null:NO, Key:, Default:, Extra:',
4 => 'Field:reference_id, Type:varchar(255), Null:NO, Key:MUL, Default:, Extra:',
5 => 'Field:tx_status, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
6 => 'Field:payment_mode, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
7 => 'Field:tx_msg, Type:text, Null:NO, Key:, Default:, Extra:',
8 => 'Field:tx_time, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
9 => 'Field:signature, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
10 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
11 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
12 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
13 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
);
return $column_structure;
}
                        }
                        ?>