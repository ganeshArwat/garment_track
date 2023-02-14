<?php
                        class Other_address_table extends MX_Controller
                        {
                            public function column_list()
                            {
$column_structure = array(
0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
2 => 'Field:customer_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
3 => 'Field:code, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
4 => 'Field:name, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
5 => 'Field:company_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
6 => 'Field:contact_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
7 => 'Field:email_id, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
8 => 'Field:pincode, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
9 => 'Field:gstin_type, Type:int(11), Null:NO, Key:, Default:, Extra:',
10 => 'Field:dob, Type:date, Null:NO, Key:, Default:, Extra:',
11 => 'Field:address1, Type:text, Null:NO, Key:, Default:, Extra:',
12 => 'Field:address2, Type:text, Null:NO, Key:, Default:, Extra:',
13 => 'Field:address3, Type:text, Null:NO, Key:, Default:, Extra:',
14 => 'Field:city, Type:int(11), Null:NO, Key:, Default:, Extra:',
15 => 'Field:state, Type:int(11), Null:NO, Key:, Default:, Extra:',
16 => 'Field:country, Type:int(11), Null:NO, Key:, Default:, Extra:',
17 => 'Field:gstin_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
18 => 'Field:proof1, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
19 => 'Field:proof2, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
20 => 'Field:dial_code, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
21 => 'Field:auto_generate_no, Type:int(11), Null:NO, Key:, Default:, Extra:',
22 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
23 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
24 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
25 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
);
return $column_structure;
}
                        }
                        ?>