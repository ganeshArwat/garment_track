<?php
class Transfer_manifest_docket_table extends MX_Controller
{
    public function column_list()
    {
        $column_structure = array(
            0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
            1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            2 => 'Field:transfer_manifest_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            3 => 'Field:docket_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            4 => 'Field:bag_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            5 => 'Field:track_by_type, Type:int(11), Null:NO, Key:, Default:, Extra:',
            6 => 'Field:awb_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            7 => 'Field:remark, Type:text, Null:NO, Key:, Default:, Extra:',
            8 => 'Field:chargeable_wt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            9 => 'Field:total_pcs, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            10 => 'Field:con_name, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            11 => 'Field:destination_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            12 => 'Field:vendor_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            13 => 'Field:co_vendor_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            14 => 'Field:product_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            15 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            16 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            17 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            18 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            19 => 'Field:migration_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            20 => 'Field:docket_item_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
        );
        return $column_structure;
    }
}
