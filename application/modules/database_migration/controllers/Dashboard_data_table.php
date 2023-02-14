<?php
                        class Dashboard_data_table extends MX_Controller
                        {
                            public function column_list()
                            {
$column_structure = array(
0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
2 => 'Field:report_date, Type:date, Null:NO, Key:, Default:, Extra:',
3 => 'Field:customer_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
4 => 'Field:vendor_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
5 => 'Field:report_key, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
6 => 'Field:report_value, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
7 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
);
return $column_structure;
}
                        }
                        ?>