<?php
class Transfer_manifest_table extends MX_Controller
{
    public function column_list()
    {
        $column_structure = array(
            0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
            1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            2 => 'Field:booking_min, Type:date, Null:NO, Key:, Default:, Extra:',
            3 => 'Field:booking_max, Type:date, Null:NO, Key:, Default:, Extra:',
            4 => 'Field:manifest_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            5 => 'Field:vendor_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            6 => 'Field:co_vendor_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            7 => 'Field:manifest_date, Type:date, Null:NO, Key:, Default:, Extra:',
            8 => 'Field:vendor_cd_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            9 => 'Field:arrival_date, Type:date, Null:NO, Key:, Default:, Extra:',
            10 => 'Field:company_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            11 => 'Field:ori_hub_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            12 => 'Field:dest_hub_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            13 => 'Field:total_bags_count, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            14 => 'Field:weight_total, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            15 => 'Field:awb_count, Type:int(11), Null:NO, Key:, Default:, Extra:',
            16 => 'Field:parcel_count, Type:int(11), Null:NO, Key:, Default:, Extra:',
            17 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            18 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            19 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            20 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            21 => 'Field:created_by_type, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            22 => 'Field:modified_by_type, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            23 => 'Field:migration_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
        );
        return $column_structure;
    }
}
