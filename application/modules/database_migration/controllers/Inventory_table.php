<?php
class Inventory_table extends MX_Controller
{
    public function column_list()
    {
        $column_structure = array(
            0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
            1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            2 => 'Field:inventory_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            3 => 'Field:challan_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            4 => 'Field:challan_date, Type:date, Null:NO, Key:, Default:, Extra:',
            5 => 'Field:inventory_vendor_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            6 => 'Field:customer_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            7 => 'Field:brand_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            8 => 'Field:project_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            9 => 'Field:material_received_date, Type:date, Null:NO, Key:, Default:, Extra:',
            10 => 'Field:material_received_time, Type:time, Null:NO, Key:, Default:, Extra:',
            11 => 'Field:user_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            12 => 'Field:warehouse_hub_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            13 => 'Field:rack_number_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            14 => 'Field:sub_rack_number_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            15 => 'Field:remark, Type:text, Null:NO, Key:, Default:, Extra:',
            16 => 'Field:material_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            17 => 'Field:material_image_path, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            18 => 'Field:box_cnt, Type:int(11), Null:NO, Key:, Default:, Extra:',
            19 => 'Field:quantity_per_box, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            20 => 'Field:total_quantity, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            21 => 'Field:hsn_code, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            22 => 'Field:unit_rate, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            23 => 'Field:sub_total, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            24 => 'Field:gst_per, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            25 => 'Field:gst_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            26 => 'Field:grand_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            27 => 'Field:dispatch_quantity, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            28 => 'Field:available_quantity, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            29 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            30 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            31 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            32 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            33 => 'Field:auto_generate_no, Type:int(11), Null:NO, Key:, Default:, Extra:',
            34 => 'Field:challan_image_path, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            35 => 'Field:total_received_qty, Type:int(11), Null:NO, Key:, Default:, Extra:',
            36 => 'Field:product_code, Type:varchar(255), Null:NO, Key:, Default:, Extra:',


        );
        return $column_structure;
    }
}
