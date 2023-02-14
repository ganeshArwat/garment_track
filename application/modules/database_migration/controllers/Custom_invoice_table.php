<?php
                        class Custom_invoice_table extends MX_Controller
                        {
                            public function column_list()
                            {
$column_structure = array(
0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
2 => 'Field:code, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
3 => 'Field:name, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
4 => 'Field:description, Type:text, Null:NO, Key:, Default:, Extra:',
5 => 'Field:payment_info, Type:text, Null:NO, Key:, Default:, Extra:',
6 => 'Field:prefix_code, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
7 => 'Field:suffix_code, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
8 => 'Field:is_system_default, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
9 => 'Field:is_ntd_invoice, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
10 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
11 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
12 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
13 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
14 => 'Field:is_vat_invoice, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
15 => 'Field:company_name_color, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
);
return $column_structure;
}
                        }
                        ?>