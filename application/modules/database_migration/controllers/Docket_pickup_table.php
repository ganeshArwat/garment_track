<?php
                        class Docket_pickup_table extends MX_Controller
                        {
                            public function column_list()
                            {
$column_structure = array(
0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
2 => 'Field:docket_id, Type:int(11), Null:NO, Key:MUL, Default:, Extra:',
3 => 'Field:code, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
4 => 'Field:name, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
5 => 'Field:contact_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
6 => 'Field:email_id, Type:varchar(255), Null:NO, Key:MUL, Default:, Extra:',
7 => 'Field:pincode, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
8 => 'Field:gstin_type, Type:int(11), Null:NO, Key:, Default:, Extra:',
9 => 'Field:address1, Type:text, Null:NO, Key:, Default:, Extra:',
10 => 'Field:address2, Type:text, Null:NO, Key:, Default:, Extra:',
11 => 'Field:address3, Type:text, Null:NO, Key:, Default:, Extra:',
12 => 'Field:city, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
13 => 'Field:state, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
14 => 'Field:country, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
15 => 'Field:gstin_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
16 => 'Field:seller_diff_from_shipper, Type:tinyint(3), Null:NO, Key:, Default:, Extra:',
17 => 'Field:doc_path, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
18 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
19 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
20 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
21 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
22 => 'Field:auto_generate_no, Type:int(11), Null:NO, Key:, Default:, Extra:',
23 => 'Field:dial_code, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
24 => 'Field:doc_path2, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
25 => 'Field:gstin_type_2, Type:int(11), Null:NO, Key:, Default:, Extra:',
26 => 'Field:gstin_no_2, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
27 => 'Field:doc_path3, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
28 => 'Field:doc_path4, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
);
return $column_structure;
}
                        }
                        ?>