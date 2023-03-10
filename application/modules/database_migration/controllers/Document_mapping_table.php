<?php
class Document_mapping_table extends MX_Controller
{
    public function column_list()
    {
        $column_structure = array(
            0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
            1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            2 => 'Field:module_type, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            3 => 'Field:module_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            4 => 'Field:doc_type_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            5 => 'Field:doc_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            6 => 'Field:doc_name, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            7 => 'Field:doc_page1, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            8 => 'Field:doc_page2, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            9 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            10 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            11 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            12 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            13 => 'Field:docket_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
        );
        return $column_structure;
    }
}
