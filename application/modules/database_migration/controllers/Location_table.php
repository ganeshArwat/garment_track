<?php
                        class Location_table extends MX_Controller
                        {
                            public function column_list()
                            {
$column_structure = array(
0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
1 => 'Field:status, Type:tinyint(1), Null:YES, Key:, Default:1, Extra:',
2 => 'Field:name, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
3 => 'Field:code, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
4 => 'Field:hub_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
5 => 'Field:franchise_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
6 => 'Field:branch_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
7 => 'Field:state_hub_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
8 => 'Field:zonal_hub_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
9 => 'Field:district_hub_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
10 => 'Field:master_dictrict_hub_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
11 => 'Field:routing_hub_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
12 => 'Field:city_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
13 => 'Field:state_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
14 => 'Field:country_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
15 => 'Field:pincode, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
16 => 'Field:is_city, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
17 => 'Field:default_origin, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
18 => 'Field:is_pincode, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
19 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
20 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
21 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
22 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
23 => 'Field:location_type, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
24 => 'Field:migration_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
);
return $column_structure;
}
                        }
                        ?>