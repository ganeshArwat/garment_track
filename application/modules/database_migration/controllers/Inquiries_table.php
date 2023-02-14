<?php
                        class Inquiries_table extends MX_Controller
                        {
                            public function column_list()
                            {
$column_structure = array(
0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
2 => 'Field:name, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
3 => 'Field:contact_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
4 => 'Field:email_id, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
5 => 'Field:vendor_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
6 => 'Field:dest_location_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
7 => 'Field:rate, Type:float(20,2), Null:NO, Key:, Default:, Extra:',
8 => 'Field:is_converted, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
9 => 'Field:type, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
10 => 'Field:remark, Type:text, Null:NO, Key:, Default:, Extra:',
11 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
12 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
13 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
14 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
15 => 'Field:customer_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
16 => 'Field:co_vendor_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
17 => 'Field:product_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
18 => 'Field:pieces, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
19 => 'Field:weight, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
20 => 'Field:status_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
21 => 'Field:curreny_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
22 => 'Field:inquiry_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
);
return $column_structure;
}
                        }
                        ?>