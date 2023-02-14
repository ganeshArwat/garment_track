<?php
                        class Pickup_address_table extends MX_Controller
                        {
                            public function column_list()
                            {
$column_structure = array(
0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
2 => 'Field:code, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
3 => 'Field:name, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
4 => 'Field:contact_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
5 => 'Field:email_id, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
6 => 'Field:pincode, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
7 => 'Field:gstin_type, Type:int(11), Null:NO, Key:, Default:, Extra:',
8 => 'Field:address1, Type:text, Null:NO, Key:, Default:, Extra:',
9 => 'Field:address2, Type:text, Null:NO, Key:, Default:, Extra:',
10 => 'Field:address3, Type:text, Null:NO, Key:, Default:, Extra:',
11 => 'Field:city, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
12 => 'Field:state, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
13 => 'Field:country, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
14 => 'Field:gstin_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
15 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
16 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
17 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
18 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
);
return $column_structure;
}
                        }
                        ?>