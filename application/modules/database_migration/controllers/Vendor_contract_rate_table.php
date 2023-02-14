<?php
                        class Vendor_contract_rate_table extends MX_Controller
                        {
                            public function column_list()
                            {
$column_structure = array(
0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
2 => 'Field:vendor_contract_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
3 => 'Field:on_add, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
4 => 'Field:lower_wt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
5 => 'Field:upper_wt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
6 => 'Field:rate, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
7 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
8 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
9 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
10 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
11 => 'Field:migration_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
);
return $column_structure;
}
                        }
                        ?>