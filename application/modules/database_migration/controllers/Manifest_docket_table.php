<?php
class Manifest_docket_table extends MX_Controller
{
    public function column_list()
    {
        $column_structure = array(
            0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
            1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            2 => 'Field:manifest_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            3 => 'Field:docket_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            4 => 'Field:bag_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            5 => 'Field:edi_bag_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            6 => 'Field:track_by_type, Type:int(11), Null:NO, Key:, Default:, Extra:',
            7 => 'Field:awb_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            8 => 'Field:booking_date, Type:date, Null:NO, Key:, Default:, Extra:',
            9 => 'Field:chargeable_wt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            10 => 'Field:total_pcs, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            11 => 'Field:con_name, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            12 => 'Field:destination_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            13 => 'Field:vendor_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            14 => 'Field:product_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            15 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            16 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            17 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            18 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            19 => 'Field:mark_received, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
            20 => 'Field:received_datetime, Type:datetime, Null:YES, Key:, Default:, Extra:',
            21 => 'Field:received_pcs, Type:int(11), Null:NO, Key:, Default:, Extra:',
            22 => 'Field:received_cond, Type:int(11), Null:NO, Key:, Default:, Extra:',
            23 => 'Field:received_reason, Type:text, Null:NO, Key:, Default:, Extra:',
            24 => 'Field:docket_item_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            25 => 'Field:is_inscan, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
            26 => 'Field:inscan_datetime, Type:datetime, Null:NO, Key:, Default:, Extra:',
            27 => 'Field:migration_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            28 => 'Field:manifest_edi_excel_data_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            29 => 'Field:destination_name, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            30 => 'Field:is_csb_v, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
            31 => 'Field:forwarding_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            32 => 'Field:packtrak_api_response, Type:text, Null:NO, Key:, Default:, Extra:',
        );
        return $column_structure;
    }
}
