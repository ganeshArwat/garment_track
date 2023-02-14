<?php
                        class Email_cron_setting_table extends MX_Controller
                        {
                            public function column_list()
                            {
$column_structure = array(
0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
2 => 'Field:email_type, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
3 => 'Field:module_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
4 => 'Field:module_type, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
5 => 'Field:custom_report_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
6 => 'Field:email_cycle, Type:int(11), Null:NO, Key:, Default:, Extra:',
7 => 'Field:email_time1, Type:time, Null:NO, Key:, Default:, Extra:',
8 => 'Field:email_time2, Type:time, Null:NO, Key:, Default:, Extra:',
9 => 'Field:email_day, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
10 => 'Field:shipment_range, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
11 => 'Field:email_once_shipment_deliver, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
12 => 'Field:dont_send_last_month_deliver, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
13 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
14 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
15 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
16 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
17 => 'Field:send_negative_amt, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
18 => 'Field:send_positive_amt, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
19 => 'Field:send_zero_amt, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
);
return $column_structure;
}
                        }
                        ?>