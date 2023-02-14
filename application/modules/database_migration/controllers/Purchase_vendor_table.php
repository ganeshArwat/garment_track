<?php
                        class Purchase_vendor_table extends MX_Controller
                        {
                            public function column_list()
                            {
$column_structure = array(
0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
2 => 'Field:name, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
3 => 'Field:code, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
4 => 'Field:company_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
5 => 'Field:email_id, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
6 => 'Field:pincode, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
7 => 'Field:city, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
8 => 'Field:state, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
9 => 'Field:country, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
10 => 'Field:contact_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
11 => 'Field:contact_no2, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
12 => 'Field:address, Type:text, Null:NO, Key:, Default:, Extra:',
13 => 'Field:origin_hub_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
14 => 'Field:origin_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
15 => 'Field:contact_person, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
16 => 'Field:payment_type, Type:int(11), Null:NO, Key:, Default:, Extra:',
17 => 'Field:gst_number, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
18 => 'Field:is_gst_apply, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
19 => 'Field:vendor_type, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
20 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
21 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
22 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
23 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
);
return $column_structure;
}
                        }
                        ?>