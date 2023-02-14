<?php
                        class Weight_bag_table extends MX_Controller
                        {
                            public function column_list()
                            {
$column_structure = array(
0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
1 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
2 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
3 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
4 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
5 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
6 => 'Field:manifest_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
7 => 'Field:run_number, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
8 => 'Field:bag_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
9 => 'Field:weight, Type:float(20,2), Null:NO, Key:, Default:, Extra:',
10 => 'Field:manifest_weight, Type:float(20,2), Null:NO, Key:, Default:, Extra:',
);
return $column_structure;
}
                        }
                        ?>