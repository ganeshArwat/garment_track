<?php
                        class Vendor_docket_item_table extends MX_Controller
                        {
                            public function column_list()
                            {
$column_structure = array(
0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
2 => 'Field:docket_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
3 => 'Field:actual_wt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
4 => 'Field:item_length, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
5 => 'Field:item_width, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
6 => 'Field:item_height, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
7 => 'Field:volumetric_wt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
8 => 'Field:chargeable_wt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
9 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
10 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
11 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
12 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
);
return $column_structure;
}
                        }
                        ?>