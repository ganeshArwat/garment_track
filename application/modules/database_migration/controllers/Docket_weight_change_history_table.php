<?php
                        class Docket_weight_change_history_table extends MX_Controller
                        {
                            public function column_list()
                            {
$column_structure = array(
0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
2 => 'Field:docket_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
3 => 'Field:old_wt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
4 => 'Field:new_wt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
5 => 'Field:history_timestamp, Type:datetime, Null:NO, Key:, Default:, Extra:',
6 => 'Field:user_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
7 => 'Field:user_type, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
8 => 'Field:entry_type, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
);
return $column_structure;
}
                        }
                        ?>