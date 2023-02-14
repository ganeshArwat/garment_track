<?php
                        class Follow_up_table extends MX_Controller
                        {
                            public function column_list()
                            {
$column_structure = array(
0 => 'Field:id, Type:int(4), Null:NO, Key:PRI, Default:, Extra:auto_increment',
1 => 'Field:follow_up_id, Type:smallint(4), Null:NO, Key:, Default:, Extra:',
2 => 'Field:company_name, Type:varchar(191), Null:NO, Key:, Default:, Extra:',
3 => 'Field:company_id, Type:int(4), Null:NO, Key:, Default:, Extra:',
4 => 'Field:follow_up_status, Type:smallint(6), Null:NO, Key:, Default:, Extra:',
5 => 'Field:follow_up_mail_noti, Type:smallint(6), Null:NO, Key:, Default:0, Extra:',
6 => 'Field:follow_up_date, Type:date, Null:NO, Key:, Default:, Extra:',
7 => 'Field:follow_up_time, Type:time, Null:NO, Key:, Default:, Extra:',
8 => 'Field:follow_up_details, Type:text, Null:NO, Key:, Default:, Extra:',
9 => 'Field:follow_up_added_by, Type:varchar(191), Null:NO, Key:, Default:, Extra:',
10 => 'Field:follow_up_added_by_id, Type:smallint(4), Null:NO, Key:, Default:, Extra:',
11 => 'Field:follow_up_alert_noti, Type:smallint(6), Null:NO, Key:, Default:0, Extra:',
12 => 'Field:created_at, Type:datetime, Null:NO, Key:, Default:, Extra:',
13 => 'Field:modified_at, Type:datetime, Null:NO, Key:, Default:current_timestamp(), Extra:',
);
return $column_structure;
}
                        }
                        ?>