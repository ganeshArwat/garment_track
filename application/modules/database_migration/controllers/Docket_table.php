<?php
class Docket_table extends MX_Controller
{

    public function create_file()
    {
        $qry = "SHOW TABLES";
        $qry_exe = $this->db->query($qry);
        $result = $qry_exe->result_array();

        if (isset($result) && is_array($result) && count($result) > 0) {
            foreach ($result as $key => $value) {

                foreach ($value as $tkey => $tvalue) {
                    if ($tvalue == 'voucher_submitted_by') {
                        $file_name = $tvalue . "_table.php";
                        $file_name = ucfirst($file_name);
                        $file_path = FCPATH . 'application/modules/database_migration/controllers/' . $file_name;

                        $file = fopen($file_path, "w");

                        $script_code = '<?php
                        class ' . ucfirst($tvalue) . "_table" . ' extends MX_Controller
                        {
                            public function column_list()
                            {' . "\n";
                        $script_code .= '$column_structure = array(' . "\n";


                        $qry = "SHOW COLUMNS FROM `" . $tvalue . "`";
                        $qry_exe = $this->db->query($qry);
                        $result = $qry_exe->result_array();


                        if (isset($result) && is_array($result) && count($result) > 0) {
                            foreach ($result as $key => $value) {
                                $script_code .= "$key => 'Field:" . $value['Field'] . ", Type:" . $value['Type'] . ", Null:" . $value['Null'] .
                                    ", Key:" . $value['Key'] . ", Default:" . $value['Default'] . ", Extra:" . $value['Extra'] . "',\n";
                            }
                        }

                        // $column_structure = "' . $this->column_get($tvalue) . '";
                        // return $column_structure;
                        $script_code .= ');' . "\n";
                        $script_code .= 'return $column_structure;' . "\n";
                        $script_code .= '}
                        }
                        ?>';

                        echo fwrite($file, $script_code);
                        fclose($file);
                    }
                }
            }
        }
    }
    public function column_get($table_name)
    {
        $qry = "SHOW COLUMNS FROM `" . $table_name . "`";
        $qry_exe = $this->db->query($qry);
        $result = $qry_exe->result_array();


        if (isset($result) && is_array($result) && count($result) > 0) {
            foreach ($result as $key => $value) {
                $col_label[] = "'Field:" . $value['Field'] . ", Type:" . $value['Type'] . ", Null:" . $value['Null'] .
                    ", Key:" . $value['Key'] . ", Default:" . $value['Default'] . ", Extra:" . $value['Extra'] . "',";
            }
        }

        return $col_label;
    }

    public function column_list()
    {
        $column_structure = array(
            0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
            1 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            2 => 'Field:awb_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            3 => 'Field:customer_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            4 => 'Field:origin_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            5 => 'Field:destination_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            6 => 'Field:product_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            7 => 'Field:booking_date, Type:date, Null:NO, Key:, Default:, Extra:',
            8 => 'Field:vendor_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            9 => 'Field:co_vendor_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            10 => 'Field:forwarding_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            11 => 'Field:forwarding_no_2, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            12 => 'Field:content, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            13 => 'Field:eway_bill, Type:text, Null:NO, Key:, Default:, Extra:',
            14 => 'Field:invoice_date, Type:date, Null:NO, Key:, Default:, Extra:',
            15 => 'Field:invoice_no, Type:text, Null:NO, Key:, Default:, Extra:',
            16 => 'Field:remarks, Type:text, Null:NO, Key:, Default:, Extra:',
            17 => 'Field:status_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            18 => 'Field:state_id, Type:int(11), Null:NO, Key:, Default:1, Extra:',
            19 => 'Field:shipper_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            20 => 'Field:consignee_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            21 => 'Field:actual_wt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            22 => 'Field:volumetric_wt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            23 => 'Field:consignor_wt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            24 => 'Field:chargeable_wt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            25 => 'Field:create_free_form, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
            26 => 'Field:invoice_type, Type:int(11), Null:NO, Key:, Default:, Extra:',
            27 => 'Field:curreny_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            28 => 'Field:terms_of_trade, Type:int(11), Null:NO, Key:, Default:, Extra:',
            29 => 'Field:free_note, Type:int(11), Null:NO, Key:, Default:, Extra:',
            30 => 'Field:note_desc, Type:text, Null:NO, Key:, Default:, Extra:',
            31 => 'Field:free_total_wt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            32 => 'Field:free_total_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            33 => 'Field:shipper_save_to_add, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
            34 => 'Field:consignee_save_to_add, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
            35 => 'Field:cft_value, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            36 => 'Field:cft_multiplier, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            37 => 'Field:chg_wt_per_item, Type:int(11), Null:NO, Key:, Default:, Extra:',
            38 => 'Field:round_off_chg_wt, Type:int(11), Null:NO, Key:, Default:, Extra:',
            39 => 'Field:customer_contract_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            40 => 'Field:customer_contract_tat, Type:int(11), Null:NO, Key:, Default:, Extra:',
            41 => 'Field:cft_contract_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            42 => 'Field:ori_zone_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            43 => 'Field:dest_zone_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            44 => 'Field:add_wt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            45 => 'Field:freight_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            46 => 'Field:billing_status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            47 => 'Field:shipper_billing_status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            48 => 'Field:total_pcs, Type:int(11), Null:NO, Key:, Default:, Extra:',
            49 => 'Field:old_chargeable_wt, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            50 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            51 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            52 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            53 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            54 => 'Field:wt_change_datetime, Type:datetime, Null:YES, Key:, Default:, Extra:',
            55 => 'Field:shipment_value, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            56 => 'Field:pur_freight_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            57 => 'Field:vendor_contract_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            58 => 'Field:shipment_currency_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            59 => 'Field:vendor_freight, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            60 => 'Field:vendor_fsc, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            61 => 'Field:vendor_weight, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            62 => 'Field:vendor_grand_total, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            63 => 'Field:vendor_invoice_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            64 => 'Field:payment_type, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
            65 => 'Field:ori_hub_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            66 => 'Field:dest_hub_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            67 => 'Field:invoice_range_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            68 => 'Field:project_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            69 => 'Field:created_by_type, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            70 => 'Field:customer_mis_send, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
            71 => 'Field:dispatch_date, Type:date, Null:YES, Key:, Default:, Extra:',
            72 => 'Field:courier_dispatch_date, Type:date, Null:YES, Key:, Default:, Extra:',
            73 => 'Field:is_label_print, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
            74 => 'Field:incoterm_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            75 => 'Field:dispatch_type, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
            76 => 'Field:booking_time, Type:time, Null:NO, Key:, Default:, Extra:',
            77 => 'Field:auto_generate_type, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
            78 => 'Field:auto_generate_no, Type:int(11), Null:NO, Key:, Default:, Extra:',
            79 => 'Field:free_discount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            80 => 'Field:challan_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            81 => 'Field:commit_id, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            82 => 'Field:cod_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            83 => 'Field:insurance_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            84 => 'Field:shipment_priority, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
            85 => 'Field:dispatch_time, Type:time, Null:NO, Key:, Default:, Extra:',
            86 => 'Field:courier_dispatch_time, Type:time, Null:NO, Key:, Default:, Extra:',
            87 => 'Field:is_checked_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            88 => 'Field:company_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            89 => 'Field:is_inscan, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
            90 => 'Field:total_received, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            91 => 'Field:total_balance, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            92 => 'Field:delivery_otp, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            93 => 'Field:instructions, Type:text, Null:NO, Key:, Default:, Extra:',
            94 => 'Field:remote_area_charges, Type:text, Null:NO, Key:, Default:, Extra:',
            95 => 'Field:reference_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            96 => 'Field:entry_type, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
            97 => 'Field:api_awb_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            98 => 'Field:material_grand_total_amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            99 => 'Field:nimbuspost_courier_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            100 => 'Field:is_pickup_scan, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
            101 => 'Field:reference_name, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            102 => 'Field:sales_billing_save, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
            103 => 'Field:purchase_billing_save, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
            104 => 'Field:migration_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            105 => 'Field:inscan_date, Type:date, Null:NO, Key:, Default:, Extra:',
            106 => 'Field:inscan_time, Type:time, Null:NO, Key:, Default:, Extra:',
            107 => 'Field:api_tracking_no, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            108 => 'Field:sac_code, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            109 => 'Field:quick_scan_awb, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
            110 => 'Field:chg_wt_per_item_pur, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
            111 => 'Field:round_off_chg_wt_pur, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
            112 => 'Field:delivery_sync_state, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
            113 => 'Field:is_fedex_doc_uploaded, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
            114 => 'Field:modified_by_type, Type:tinyint(1), Null:NO, Key:, Default:, Extra:',
            115 => 'Field:address_info_needed, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
            116 => 'Field:is_edited_awb_no, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
            117 => 'Field:deleted_datetime, Type:datetime, Null:NO, Key:, Default:, Extra:',
            118 => 'Field:deleted_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            119 => 'Field:deleted_by_type, Type:int(11), Null:NO, Key:, Default:, Extra:',
            120 => 'Field:reprint_datetime, Type:datetime, Null:NO, Key:, Default:, Extra:',
            121 => 'Field:reprint_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            122 => 'Field:reprint_by_type, Type:int(11), Null:NO, Key:, Default:, Extra:',
            123 => 'Field:wt_change_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            124 => 'Field:wt_change_by_type, Type:int(11), Null:NO, Key:, Default:, Extra:',
            125 => 'Field:vendor_invoice_id, Type:int(11), Null:NO, Key:, Default:, Extra:',
            126 => 'Field:stock_id, Type:tinyint(1), Null:NO, Key:, Default:0, Extra:',
            127 => 'Field:stock_reallocation_id, Type:tinyint(1), Null:NO, Key:, Default:0, Extra:',
            128 => 'Field:label_print_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            129 => 'Field:customer_edd_cross, Type:int(11), Null:NO, Key:, Default:, Extra:',
            130 => 'Field:vendor_edd_cross, Type:int(11), Null:NO, Key:, Default:, Extra:',
        );
        return $column_structure;
    }
}
