<?php
                        class Leave_application_table extends MX_Controller
                        {
                            public function column_list()
                            {
$column_structure = array(
0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
2 => 'Field:user_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
3 => 'Field:leave_type_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
4 => 'Field:leave_duration, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
5 => 'Field:start_date, Type:date, Null:NO, Key:, Default:, Extra:',
6 => 'Field:start_time, Type:time, Null:NO, Key:, Default:, Extra:',
7 => 'Field:end_time, Type:time, Null:NO, Key:, Default:, Extra:',
8 => 'Field:end_date, Type:date, Null:NO, Key:, Default:, Extra:',
9 => 'Field:reason, Type:text, Null:NO, Key:, Default:, Extra:',
10 => 'Field:application_status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
11 => 'Field:approved_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
12 => 'Field:rejected_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
13 => 'Field:approve_date, Type:datetime, Null:YES, Key:, Default:, Extra:',
14 => 'Field:rejected_date, Type:datetime, Null:YES, Key:, Default:, Extra:',
15 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
16 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
17 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
18 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
);
return $column_structure;
}
                        }
                        ?>