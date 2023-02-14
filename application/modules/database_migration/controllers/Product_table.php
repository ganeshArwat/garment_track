<?php
                        class Product_table extends MX_Controller
                        {
                            public function column_list()
                            {
$column_structure = array(
0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
2 => 'Field:name, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
3 => 'Field:code, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
4 => 'Field:pdt_type, Type:int(11), Null:NO, Key:, Default:, Extra:',
5 => 'Field:pdt_format, Type:int(11), Null:NO, Key:, Default:, Extra:',
6 => 'Field:gst_service_code, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
7 => 'Field:gst_per, Type:float(20,2), Null:NO, Key:, Default:, Extra:',
8 => 'Field:hide_in_portal, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
9 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
10 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
11 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
12 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
);
return $column_structure;
}
                        }
                        ?>