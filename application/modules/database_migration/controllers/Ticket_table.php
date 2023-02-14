<?php
class Ticket_table extends MX_Controller
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
            10 => 'Field:sw_status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            11 => 'Field:portal_status, Type:tinyint(1), Null:NO, Key:, Default:3, Extra:',
            12 => 'Field:main_status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            13 => 'Field:modified_by_portal, Type:int(11), Null:NO, Key:, Default:, Extra:',
            14 => 'Field:created_by_portal, Type:int(11), Null:NO, Key:, Default:, Extra:',
            15 => 'Field:review, Type:text, Null:NO, Key:, Default:, Extra:',
            16 => 'Field:ticket_no, Type:int(11), Null:NO, Key:, Default:, Extra:',
            17 => 'Field:ticket_no_prefix, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            18 => 'Field:allocated_to, Type:int(11), Null:NO, Key:, Default:, Extra:',
            19 => 'Field:customer_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            20 => 'Field:docket_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            21 => 'Field:invoice_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            22 => 'Field:auto_generate_no, Type:int(11), Null:NO, Key:, Default:, Extra:',
            23 => 'Field:status_mark_date, Type:date, Null:NO, Key:, Default:, Extra:',
        );
        return $column_structure;
    }
}
