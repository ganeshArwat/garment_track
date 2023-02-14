<?php
                        class Leads_table extends MX_Controller
                        {
                            public function column_list()
                            {
$column_structure = array(
0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
2 => 'Field:company_name, Type:varchar(191), Null:NO, Key:UNI, Default:, Extra:',
3 => 'Field:poc_name, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
4 => 'Field:poc_contact, Type:bigint(10), Null:NO, Key:, Default:, Extra:',
5 => 'Field:director_name, Type:varchar(191), Null:NO, Key:, Default:, Extra:',
6 => 'Field:director_contact, Type:bigint(10), Null:NO, Key:, Default:, Extra:',
7 => 'Field:company_website, Type:varchar(191), Null:NO, Key:, Default:No, Extra:',
8 => 'Field:company_address, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
9 => 'Field:company_mail, Type:varchar(191), Null:NO, Key:, Default:, Extra:',
10 => 'Field:hub_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
11 => 'Field:billing_type, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
12 => 'Field:payment_type_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
13 => 'Field:contract_head, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
14 => 'Field:business_type_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
15 => 'Field:lead_generated_by, Type:varchar(191), Null:NO, Key:, Default:, Extra:',
16 => 'Field:lead_generated_by_id, Type:int(4), Null:NO, Key:, Default:, Extra:',
17 => 'Field:lead_type_id, Type:int(11), Null:NO, Key:, Default:0, Extra:',
18 => 'Field:lead_status_id, Type:int(11), Null:NO, Key:, Default:0, Extra:',
19 => 'Field:lead_reason, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
20 => 'Field:lead_interaction, Type:varchar(10), Null:NO, Key:, Default:, Extra:',
21 => 'Field:interacted_via, Type:smallint(4), Null:NO, Key:, Default:, Extra:',
22 => 'Field:meeting_minutes, Type:text, Null:NO, Key:, Default:, Extra:',
23 => 'Field:lead_delete, Type:tinyint(1), Null:NO, Key:, Default:0, Extra:',
24 => 'Field:convert_customer, Type:smallint(4), Null:NO, Key:, Default:1, Extra:',
25 => 'Field:lead_mail_noti, Type:smallint(6), Null:NO, Key:, Default:0, Extra:',
26 => 'Field:modified_at, Type:timestamp, Null:NO, Key:, Default:current_timestamp(), Extra:on update current_timestamp()',
27 => 'Field:created_at, Type:datetime, Null:NO, Key:, Default:, Extra:',
);
return $column_structure;
}
                        }
                        ?>