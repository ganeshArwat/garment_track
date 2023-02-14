<?php
class Docket_less_invoice_table extends MX_Controller
{
    public function column_list()
    {
        $column_structure = array(
            0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
            1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            2 => 'Field:invoice_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            3 => 'Field:addr_type, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
            4 => 'Field:sub_agent, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            5 => 'Field:irn, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            6 => 'Field:good_desc, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            7 => 'Field:job_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            8 => 'Field:awb_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            9 => 'Field:mawb_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            10 => 'Field:departure_port, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            11 => 'Field:arrival_port, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            12 => 'Field:gross_wt, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            13 => 'Field:package_cnt, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            14 => 'Field:airline, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            15 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            16 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            17 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            18 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            19 => 'Field:migration_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
        );
        return $column_structure;
    }
}
