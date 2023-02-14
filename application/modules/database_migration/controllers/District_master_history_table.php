<?php
                        class District_master_history_table extends MX_Controller
                        {
                            public function column_list()
                            {
$column_structure = array(
0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
2 => 'Field:district_master_id, Type:int(11), Null:NO, Key:MUL, Default:, Extra:',
3 => 'Field:new_data, Type:longtext, Null:NO, Key:, Default:, Extra:',
4 => 'Field:old_data, Type:longtext, Null:NO, Key:, Default:, Extra:',
5 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
6 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
7 => 'Field:created_by_type, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
);
return $column_structure;
}
                        }
                        ?>