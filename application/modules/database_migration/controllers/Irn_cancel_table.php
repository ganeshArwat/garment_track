<?php
                        class Irn_cancel_table extends MX_Controller
                        {
                            public function column_list()
                            {
$column_structure = array(
0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
1 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
2 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
3 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
4 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
5 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
6 => 'Field:responce_data, Type:text, Null:NO, Key:, Default:, Extra:',
7 => 'Field:invoice_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
8 => 'Field:credit_debit_note_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
9 => 'Field:auth_token_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
10 => 'Field:irn, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
11 => 'Field:irn_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
12 => 'Field:cancel_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
13 => 'Field:request_data, Type:text, Null:NO, Key:, Default:, Extra:',
);
return $column_structure;
}
                        }
