<?php
                        class Customer_estimate_table extends MX_Controller
                        {
                            public function column_list()
                            {
$column_structure = array(
0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
2 => 'Field:estimate_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
3 => 'Field:project_name, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
4 => 'Field:po_number, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
5 => 'Field:po_date, Type:date, Null:NO, Key:, Default:, Extra:',
6 => 'Field:job_received, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
7 => 'Field:particular, Type:text, Null:NO, Key:, Default:, Extra:',
8 => 'Field:estimate_submition, Type:date, Null:NO, Key:, Default:, Extra:',
9 => 'Field:estimate_status, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
10 => 'Field:excel_updated, Type:date, Null:NO, Key:, Default:, Extra:',
11 => 'Field:changes_tag, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
12 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
13 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
14 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
15 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
16 => 'Field:customer_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
17 => 'Field:vendor_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
18 => 'Field:chargeable_wt_total, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
19 => 'Field:grand_total, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
20 => 'Field:fsc_total, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
21 => 'Field:cgst_total, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
22 => 'Field:igst_total, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
23 => 'Field:sgst_total, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
);
return $column_structure;
}
                        }
                        ?>