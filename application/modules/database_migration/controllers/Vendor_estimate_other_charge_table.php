<?php
                        class Vendor_estimate_other_charge_table extends MX_Controller
                        {
                            public function column_list()
                            {
$column_structure = array(
0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
2 => 'Field:vendor_estimate_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
3 => 'Field:vendor_estimate_data_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
4 => 'Field:vendor_estimate_contract_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
5 => 'Field:charge_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
6 => 'Field:charge_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
7 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
8 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
);
return $column_structure;
}
                        }
                        ?>