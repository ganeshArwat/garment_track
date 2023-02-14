<?php
class Ticket_itd_table extends MX_Controller
{
    public function column_list()
    {
        $column_structure = array(
            0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
            1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            2 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            3 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            4 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            5 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            6 => 'Field:ticket_type, Type:int(11), Null:NO, Key:, Default:, Extra:',
            7 => 'Field:ticket_sub_type, Type:int(11), Null:NO, Key:, Default:, Extra:',
            8 => 'Field:subject, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            9 => 'Field:description, Type:text, Null:NO, Key:, Default:, Extra:',
            10 => 'Field:docket_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            11 => 'Field:invoice_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            12 => 'Field:ticket_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            13 => 'Field:sw_status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            14 => 'Field:portal_status, Type:tinyint(1), Null:NO, Key:, Default:3, Extra:',
            15 => 'Field:main_status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            16 => 'Field:user_allocated, Type:int(11), Null:NO, Key:, Default:, Extra:',
            17 => 'Field:created_by_trackmate, Type:int(11), Null:NO, Key:, Default:, Extra:',
            18 => 'Field:modified_by_trackmate, Type:int(11), Null:NO, Key:, Default:, Extra:',
            19 => 'Field:review, Type:text, Null:NO, Key:, Default:, Extra:',
            20 => 'Field:close_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            21 => 'Field:ticket_tag_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            22 => 'Field:wfm_case_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
        );
        return $column_structure;
    }
}
