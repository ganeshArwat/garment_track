<?php
class Charge_master_table extends MX_Controller
{
    public function column_list()
    {
        $column_structure = array(
            0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
            1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            2 => 'Field:name, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            3 => 'Field:is_gst_apply, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
            4 => 'Field:is_fsc_apply, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
            5 => 'Field:is_manual, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
            6 => 'Field:is_cust_optional, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
            7 => 'Field:is_vendor_optional, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
            8 => 'Field:is_default, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
            9 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            10 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            11 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            12 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            13 => 'Field:migration_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            14 => 'Field:hide_in_invoice, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
            15 => 'Field:fedex_name, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            16 => 'Field:dhl_name, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            17 => 'Field:ups_name, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            18 => 'Field:norsk_name, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            19 => 'Field:inexpress_name, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
        );
        return $column_structure;
    }
}
