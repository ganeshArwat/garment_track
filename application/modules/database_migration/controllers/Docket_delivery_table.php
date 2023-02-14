<?php
class Docket_delivery_table extends MX_Controller
{
    public function column_list()
    {
        $column_structure = array(
            0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
            1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            2 => 'Field:expected_date, Type:date, Null:NO, Key:, Default:, Extra:',
            3 => 'Field:expected_time, Type:time, Null:NO, Key:, Default:, Extra:',
            4 => 'Field:delivery_date, Type:date, Null:NO, Key:, Default:, Extra:',
            5 => 'Field:delivery_time, Type:time, Null:NO, Key:, Default:, Extra:',
            6 => 'Field:delivery_cost, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            7 => 'Field:receiver_name, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            8 => 'Field:receiver_mobile, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            9 => 'Field:remark, Type:text, Null:NO, Key:, Default:, Extra:',
            10 => 'Field:status_code, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            11 => 'Field:status_name, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            12 => 'Field:status_reason, Type:text, Null:NO, Key:, Default:, Extra:',
            13 => 'Field:docket_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            14 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            15 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            16 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            17 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            18 => 'Field:pod_uploaded_date, Type:date, Null:NO, Key:, Default:, Extra:',
            19 => 'Field:pod_uploaded_time, Type:time, Null:NO, Key:, Default:, Extra:',
            20 => 'Field:delivered_mark_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            21 => 'Field:connection_date, Type:date, Null:NO, Key:, Default:, Extra:',
            22 => 'Field:connection_time, Type:time, Null:NO, Key:, Default:, Extra:',
            23 => 'Field:receiver_email, Type:varchar(255), Null:NO, Key:, Default:, Extra:'
        );
        return $column_structure;
    }
}
