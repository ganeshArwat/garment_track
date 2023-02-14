<?php
                        class Inquiry_follow_up_table extends MX_Controller
                        {
                            public function column_list()
                            {
$column_structure = array(
0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
2 => 'Field:inquiry_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
3 => 'Field:follow_up_date, Type:date, Null:NO, Key:, Default:, Extra:',
4 => 'Field:follow_up_desc, Type:text, Null:NO, Key:, Default:, Extra:',
5 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
6 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
7 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
8 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
9 => 'Field:follow_up_time, Type:time, Null:NO, Key:, Default:, Extra:',
10 => 'Field:follow_up_response, Type:text, Null:NO, Key:, Default:, Extra:',
11 => 'Field:follow_up_status, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
);
return $column_structure;
}
                        }
                        ?>