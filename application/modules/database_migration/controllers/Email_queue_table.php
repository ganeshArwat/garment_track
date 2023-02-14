<?php
                        class Email_queue_table extends MX_Controller
                        {
                            public function column_list()
                            {
$column_structure = array(
0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
2 => 'Field:email_configuration_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
3 => 'Field:receiver_email, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
4 => 'Field:email_subject, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
5 => 'Field:email_body, Type:text, Null:NO, Key:, Default:, Extra:',
6 => 'Field:email_attachment, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
7 => 'Field:email_send_datetime, Type:datetime, Null:NO, Key:, Default:, Extra:',
8 => 'Field:send_status, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
9 => 'Field:module_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
10 => 'Field:module_type, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
11 => 'Field:description, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
12 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
13 => 'Field:failed_status, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
14 => 'Field:send_datetime, Type:datetime, Null:YES, Key:, Default:, Extra:',
);
return $column_structure;
}
                        }
                        ?>