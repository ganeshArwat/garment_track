<?php
class Script extends MX_Controller
{
    public function get_table_create_query()
    {
        $query = "SHOW TABLES";
        $query_exe = $this->db->query($query);
        $result =  $query_exe->result_array();
        if (isset($result) && is_array($result) && count($result) > 0) {
            foreach ($result as $tkey => $tvalue) {
                foreach ($tvalue as $tnkey => $tnvalue) {
                    $all_table[] = $tnvalue;
                }
            }
        }
        if (isset($all_table) && is_array($all_table) && count($all_table) > 0) {
            foreach ($all_table as $key => $value) {
                $query = "SHOW CREATE TABLE " . $value;
                $query_exe = $this->db->query($query);
                $result =  $query_exe->row_array();

                $query = '';
                if (isset($result['Create Table']) && $result['Create Table'] != '') {
                    $start_pos = strpos($result['Create Table'], 'InnoDB AUTO_INCREMENT');
                    $end_pos = strpos($result['Create Table'], 'DEFAULT CHARSET');
                    $replace_len = $end_pos - $start_pos - 8;
                    if ($start_pos > 0) {
                        $result['Create Table'] = substr_replace($result['Create Table'], '', $start_pos + 7, $replace_len);
                    }
                    $result['Create Table'] = str_replace("CREATE TABLE `", "CREATE TABLE IF NOT EXISTS `", $result['Create Table']);
                    //$result['Create Table'] = preg_replace("/['AUTO_INCREMENT=']+[0-99999]{2}/", "", $result['Create Table']);
                    $query = "<br>";
                    $query .= "if (!function_exists('" . $value . "_table_qry')) {<br>";
                    $query .=    "function " . $value . "_table_qry()<br>";
                    $query .=  "{<br>";
                    $query .= " \$qry = \"" . str_replace("\n", "<br>", $result['Create Table']) . "\";<br>";
                    $query .=  "return \$qry;<br>";
                    $query .= "}<br>";
                    $query .= "}";
                    echo $query;
                }
            }
        }
    }

    public function test()
    {
        $this->load->helper('url');
        $this->load->helper('database_manage');
        $this->load->helper('create_table');

        //RUN TABLE COLUMN MIGRATION SCRIPT
        $this->load->module('database_migration/column_script');
        $this->column_script->check_table_structure();
    }

    public function test_query()
    {
        $qry  = "CREATE FUNCTION  `REGEXP_REPLACE1`(original VARCHAR(1000),pattern VARCHAR(1000),replacement VARCHAR(1000))
        RETURNS VARCHAR(1000)
        DETERMINISTIC
        BEGIN 
         DECLARE temp VARCHAR(1000); 
         DECLARE ch VARCHAR(1); 
         DECLARE i INT;
         SET i = 1;
         SET temp = '';
         IF original REGEXP pattern THEN 
          loop_label: LOOP 
           IF i>CHAR_LENGTH(original) THEN
            LEAVE loop_label;  
           END IF;
           SET ch = SUBSTRING(original,i,1);
           IF NOT ch REGEXP pattern THEN
            SET temp = CONCAT(temp,ch);
           ELSE
            SET temp = CONCAT(temp,replacement);
           END IF;
           SET i=i+1;
          END LOOP;
         ELSE
          SET temp = original;
         END IF;
         RETURN temp;
        END";
        $this->db->query($qry);
    }

    function portal_special_permission()
    {
        $query = "SELECT id as user_id FROM customer_users";
        $qry_exe = $this->db->query($query);
        $result = $qry_exe->result_array();

        foreach ($result as $key => $value) {
            # code...
            $qry = "INSERT INTO customer_users_permission_map (`permission_type`,`status`,`user_id`,`permission_id`) VALUES ('Special',1," . $value['user_id'] . ",337);";
            $qry_exe = $this->db->query($qry);
            echo $value['user_id'] . "Done <br>";
        }
    }

    function remove_duplicate_invoice()
    {
        $qry = "SELECT COUNT(m.id) AS `Rows`,m.docket_id,i.id FROM `docket_invoice_map` m JOIN docket_invoice i ON(i.id=m.docket_invoice_id) WHERE m.status IN(1,2) AND i.status IN(1,2) GROUP BY m.docket_id HAVING COUNT(m.id) >1";
        $qry_exe = $this->db->query($qry);
        $result = $qry_exe->result_array();
        if (isset($result) && is_array($result) && count($result) > 0) {
            foreach ($result as $key => $value) {
                $qry = "SELECT * FROM `docket_invoice_map` WHERE `docket_id` = " . $value['docket_id'] . " and status IN(1,2) ORDER BY id DESC";
                $qry_exe = $this->db->query($qry);
                $duplicate_data = $qry_exe->result_array();
                $deleted_ids = array();
                if (isset($duplicate_data) && is_array($duplicate_data) && count($duplicate_data) > 0) {
                    foreach ($duplicate_data as $dkey => $dvalue) {
                        $deleted_ids[] = $dvalue['id'];
                    }
                }

                unset($deleted_ids[0]);

                if (isset($deleted_ids) && is_array($deleted_ids) && count($deleted_ids) > 0) {
                    $deletq = "UPDATE docket_invoice_map SET status=3,modified_date='2000-01-01'
                     WHERE id IN(" . implode(",", $deleted_ids) . ");";

                    // echo "<br>DELETQ=" . $deletq;
                    // echo "<br>ID=" . $value['id'];
                    echo "<br>" . $deletq;
                }
            }
        }
    }
    function parcel_script($where_qry = '')
    {

        $this->load->helper('url');
        $this->load->model('Global_model', 'gm');
        $this->load->helper('frontend_common');

        $setting = get_all_app_setting(" AND module_name IN('docket','pdf')");

        if (isset($setting['docket_show_box_no']) && $setting['docket_show_box_no'] == 1) {
            $query =  "SELECT *,COUNT(id) as box_total FROM `docket_items` WHERE status = 1 " . $where_qry . " GROUP BY actual_wt,item_length,item_width,item_height,docket_id ORDER BY COUNT(id) DESC";
        } else {
            $query =  "SELECT * FROM `docket_items` WHERE status = 1 " . $where_qry . "";
        }


        $qry_exe = $this->db->query($query);
        $result = $qry_exe->result_array();
        if (isset($result) && is_array($result) && count($result) > 0) {
            foreach ($result as $key => $value) {
                $docket_Data[$value['docket_id']][] =  $value;
            }


            if (isset($docket_Data) && is_array($docket_Data) && count($docket_Data) > 0) {
                foreach ($docket_Data as $doc_key => $doc_val) {
                    foreach ($doc_val as $dkey => $dvalue) {

                        if (isset($setting['docket_show_box_no']) && $setting['docket_show_box_no'] == 1) {
                            $updatre_data = array(
                                'sr_no' => $dkey + 1,
                                'no_of_box' => $dvalue['box_total']
                            );

                            $update_con = array(
                                'actual_wt' => $dvalue['actual_wt'],
                                'item_length' => $dvalue['item_length'],
                                'item_width' => $dvalue['item_width'],
                                'item_height' => $dvalue['item_height'],
                                'docket_id' => $dvalue['docket_id'],
                                'status' => 1
                            );
                        } else {
                            $updatre_data = array(
                                'sr_no' => $dkey + 1,
                                'no_of_box' => 1
                            );

                            $update_con = array(
                                'id' => $dvalue['id'],
                                'status' => 1
                            );
                        }


                        $this->gm->update('docket_items', $updatre_data, '', $update_con);

                        echo "<br>DOCKET_ID " . $dvalue['docket_id'];
                    }
                }
            }
        }
    }

    function deleted_parcel()
    {
        $this->load->helper('url');
        $this->load->model('Global_model', 'gm');
        $this->load->helper('frontend_common');
        $qry = "SELECT * FROM docket_items WHERE status=3 AND no_of_box=0 AND docket_id IN( SELECT id FROM `docket` WHERE `modified_date` >= '2022-07-06 18:00:00') ORDER BY created_date DESC";
        $qry_exe = $this->db->query($qry);
        $result = $qry_exe->result_array();
        if (isset($result) && is_array($result) && count($result) > 0) {
            foreach ($result as $key => $value) {
                $docket_Data[$value['docket_id']][$value['parcel_no']][] =  $value;
            }
        }

        if (isset($docket_Data) && is_array($docket_Data) && count($docket_Data) > 0) {
            foreach ($docket_Data as $doc_key => $doc_val) {
                foreach ($doc_val as $dkey => $dvalue) {
                    if (isset($dvalue[0])) {
                        $this->gm->update('docket_items', array('status' => 1), '', array('id' => $dvalue[0]['id']));
                        echo  $this->db->last_query();
                        echo "<br>STATUS_1 " . $dvalue[0]['id'];
                        echo "<br>DOCKET_ID " . $doc_key;
                        $refresh_docket[$doc_key] = $doc_key;
                    }
                }
            }
        }


        if (isset($refresh_docket) && is_array($refresh_docket) && count($refresh_docket) > 0) {
            $where_qry = " AND docket_id IN(" . implode(",", $refresh_docket) . ")";
            $this->parcel_script($where_qry);
            foreach ($refresh_docket as $id_key => $id_val) {
                $refresh_insert_data = array(
                    'module_id' => $id_val,
                    'module_type' => 1,
                    'refresh_status' => 1, //IN QUEUE
                    'refresh_type' => 'refresh_import_awb',
                    'created_date' => date('Y-m-d H:i:s'),
                    'created_by' => $this->user_id,
                );
                $this->gm->insert('refresh_log', $refresh_insert_data);
            }
        }
    }

    function change_inactive_status()
    {
        $table_arry = array(
            'credit_debit_note_item', 'media_attachment',
            'run_sheet_docket', 'company_bank', 'company_invoice_range',
            'docket_charges', 'currency_master_rate', 'customer_users',
            'customer_contract_head', 'docket_service_field', 'docket_tracking',
            'docket_comment', 'docket_receipt', 'docket_state_description',
            'whatsapp_master_variable', 'inquiry_follow_up', 'docket_invoice_map',
            'location_zone_map', 'purchase_credit_debit_note_item', 'purchase_invoice_item', 'custom_report_field',
            'sms_master_variable', 'task_assignee', 'voucher_data'
        );
        foreach ($table_arry as $key => $value) {
            $tableq = "UPDATE " . $value . " SET status=3 WHERE status=2;";

            echo "<br>" . $tableq;
            $this->db->query($tableq);
        }
    }
    function assign_portal_permission()
    {
        $qry = "SELECT id FROM customer_users WHERE status IN(1,2)";
        $qry_exe = $this->db->query($qry);
        $result = $qry_exe->result_array();
        if (isset($result) && is_array($result) && count($result) > 0) {
            foreach ($result as $key => $value) {
                $permiq = "INSERT INTO `customer_users_permission_map` (`permission_type`, `status`, `user_id`, `permission_id`, `created_date`, `created_by`, `modified_date`, `modified_by`) VALUES
                ('Fixed', 1, " . $value['id'] . ", 8, '2022-06-13 12:29:28', 248, '0000-00-00 00:00:00', 0),
                ('Fixed', 1, " . $value['id'] . ", 9, '2022-06-13 12:29:28', 248, '0000-00-00 00:00:00', 0),
                ('Fixed', 1, " . $value['id'] . ", 10, '2022-06-13 12:29:28', 248, '0000-00-00 00:00:00', 0),
                ('Fixed', 1, " . $value['id'] . ", 11, '2022-06-13 12:29:28', 248, '0000-00-00 00:00:00', 0),
                ('Fixed', 1, " . $value['id'] . ", 12, '2022-06-13 12:29:28', 248, '0000-00-00 00:00:00', 0),
                ('Fixed', 1, " . $value['id'] . ", 13, '2022-06-13 12:29:28', 248, '0000-00-00 00:00:00', 0),
                ('Fixed', 1, " . $value['id'] . ", 14, '2022-06-13 12:29:28', 248, '0000-00-00 00:00:00', 0),
                ('Fixed', 1, " . $value['id'] . ", 15, '2022-06-13 12:29:28', 248, '0000-00-00 00:00:00', 0),
                ('Fixed', 1, " . $value['id'] . ", 16, '2022-06-13 12:29:28', 248, '0000-00-00 00:00:00', 0),
                ('Fixed', 1, " . $value['id'] . ", 17, '2022-06-13 12:29:28', 248, '0000-00-00 00:00:00', 0),
                ('Fixed', 1, " . $value['id'] . ", 18, '2022-06-13 12:29:28', 248, '0000-00-00 00:00:00', 0),
                ('Fixed', 1, " . $value['id'] . ", 19, '2022-06-13 12:29:28', 248, '0000-00-00 00:00:00', 0),
                ('Fixed', 1, " . $value['id'] . ", 20, '2022-06-13 12:29:28', 248, '0000-00-00 00:00:00', 0),
                ('Fixed', 1, " . $value['id'] . ", 21, '2022-06-13 12:29:28', 248, '0000-00-00 00:00:00', 0),
                ('Fixed', 1, " . $value['id'] . ", 29, '2022-06-13 12:29:28', 248, '0000-00-00 00:00:00', 0),
                ('Fixed', 1, " . $value['id'] . ", 30, '2022-06-13 12:29:28', 248, '0000-00-00 00:00:00', 0),
                ('Fixed', 1, " . $value['id'] . ", 31, '2022-06-13 12:29:28', 248, '0000-00-00 00:00:00', 0),
                ('Fixed', 1, " . $value['id'] . ", 32, '2022-06-13 12:29:28', 248, '0000-00-00 00:00:00', 0),
                ('Fixed', 1, " . $value['id'] . ", 33, '2022-06-13 12:29:28', 248, '0000-00-00 00:00:00', 0),
                ('Fixed', 1, " . $value['id'] . ", 34, '2022-06-13 12:29:28', 248, '0000-00-00 00:00:00', 0),
                ('Fixed', 1, " . $value['id'] . ", 35, '2022-06-13 12:29:28', 248, '0000-00-00 00:00:00', 0),
                ('Fixed', 1, " . $value['id'] . ", 38, '2022-06-13 12:29:28', 248, '0000-00-00 00:00:00', 0),
                ('Fixed', 1, " . $value['id'] . ", 41, '2022-06-13 12:29:28', 248, '0000-00-00 00:00:00', 0),
                ('Fixed', 1, " . $value['id'] . ", 57, '2022-06-13 12:29:28', 248, '0000-00-00 00:00:00', 0),
                ('Fixed', 1, " . $value['id'] . ", 58, '2022-06-13 12:29:28', 248, '0000-00-00 00:00:00', 0),
                ('Fixed', 1, " . $value['id'] . ", 59, '2022-06-13 12:39:02', 248, '0000-00-00 00:00:00', 0),
                ('Fixed', 1, " . $value['id'] . ", 60, '2022-06-13 12:39:02', 248, '0000-00-00 00:00:00', 0),
                ('Fixed', 1, " . $value['id'] . ", 61, '2022-06-13 12:39:02', 248, '0000-00-00 00:00:00', 0),
                ('Fixed', 1, " . $value['id'] . ", 62, '2022-06-13 12:39:02', 248, '0000-00-00 00:00:00', 0),
                ('Fixed', 1, " . $value['id'] . ", 63, '2022-06-13 12:39:02', 248, '0000-00-00 00:00:00', 0),
                ('Fixed', 1, " . $value['id'] . ", 64, '2022-06-13 12:39:02', 248, '0000-00-00 00:00:00', 0),
                ('Fixed', 1, " . $value['id'] . ", 65, '2022-06-13 12:39:02', 248, '0000-00-00 00:00:00', 0),
                ('Fixed', 1, " . $value['id'] . ", 66, '2022-06-13 12:39:02', 248, '0000-00-00 00:00:00', 0),
                ('Fixed', 1, " . $value['id'] . ", 67, '2022-06-13 12:39:02', 248, '0000-00-00 00:00:00', 0),
                ('Fixed', 1, " . $value['id'] . ", 68, '2022-06-13 12:39:02', 248, '0000-00-00 00:00:00', 0),
                ('Fixed', 1, " . $value['id'] . ", 69, '2022-06-13 12:39:02', 248, '0000-00-00 00:00:00', 0),
                ('Fixed', 1, " . $value['id'] . ", 70, '2022-06-13 12:39:02', 248, '0000-00-00 00:00:00', 0),
                ('Fixed', 1, " . $value['id'] . ", 78, '2022-06-13 12:39:02', 248, '0000-00-00 00:00:00', 0),
                ('Fixed', 1, " . $value['id'] . ", 79, '2022-06-13 12:39:02', 248, '0000-00-00 00:00:00', 0),
                ('Fixed', 1, " . $value['id'] . ", 80, '2022-06-13 12:39:02', 248, '0000-00-00 00:00:00', 0),
                ('Fixed', 1, " . $value['id'] . ", 81, '2022-06-13 12:39:02', 248, '0000-00-00 00:00:00', 0),
                ('Fixed', 1, " . $value['id'] . ", 82, '2022-06-13 12:39:02', 248, '0000-00-00 00:00:00', 0),
                ('Fixed', 1, " . $value['id'] . ", 83, '2022-06-13 12:39:02', 248, '0000-00-00 00:00:00', 0),
                ('Fixed', 1, " . $value['id'] . ", 84, '2022-06-13 12:39:02', 248, '0000-00-00 00:00:00', 0);";
                $this->db->query($permiq);
            }
        }
    }

    function remove_duplicate_zone()
    {
        $this->load->helper('database_manage');
        $table_list = create_all_table('get_table');

        foreach ($table_list as $key => $table_name) {

            $qry = "SHOW COLUMNS FROM `" . $table_name . "`";
            $qry_exe = $this->db->query($qry);
            $company_db_table = $qry_exe->result_array();

            $session_data = $this->session->userdata('admin_user');
            $company_id = isset($session_data['com_id']) ? $session_data['com_id'] : '';
            $db_name = "garment_track_company_" . $company_id;

            if (isset($company_db_table) && is_array($company_db_table) && count($company_db_table) > 0) {
                foreach ($company_db_table as $ct_key => $ct_value) {
                    $column_name_split_arr = explode("_", $ct_value['Field']);
                    if (isset($column_name_split_arr) && is_array($column_name_split_arr) && count($column_name_split_arr) > 0) {
                        if (in_array('zone', $column_name_split_arr)) {
                            $zone_table[$table_name][$ct_value['Field']] = $ct_value['Field'];
                        }
                    }
                }
            }
        }


        //GET DUPLICATE ZONE
        $qry = "SELECT * FROM `zone` WHERE status IN(1,2)";
        $qry_exe = $this->db->query($qry);
        $zone_duplicate = $qry_exe->result_array();
        if (isset($zone_duplicate) && is_array($zone_duplicate) && count($zone_duplicate) > 0) {
            foreach ($zone_duplicate as $zkey => $zvalue) {
                $zone_name = strtolower(trim($zvalue['name']));
                $zone_id[$zone_name][] = $zvalue['id'];
            }
        }


        if (isset($zone_id) && is_array($zone_id) && count($zone_id) > 0) {
            foreach ($zone_id as $zone_name_k => $zone_name_v) {
                if (count($zone_name_v) > 1) {
                    $all_id = $zone_name_v;
                    $id_to_keep = $zone_name_v[0];
                    unset($all_id[0]);
                    $id_to_delete = $all_id;

                    $updateq = "UPDATE zone SET status=3 WHERE id IN(" . implode(",", $id_to_delete) . ")";
                    $this->db->query($updateq);

                    //UPDATE DELETED ZONE ID TO ACTIVE ZONE IN RELATION TABLE
                    if (isset($zone_table) && is_array($zone_table) && count($zone_table) > 0) {
                        foreach ($zone_table as $tkey => $tvalue) {
                            foreach ($tvalue as $col_key => $col_value) {
                                if ($tkey != 'vendor') {
                                    $updateq = "UPDATE " . $tkey . " SET " . $col_value . "=" . $id_to_keep . "
                                     WHERE " . $col_value . " IN(" . implode(",", $id_to_delete) . ")";

                                    //echo "<br>" . $updateq;
                                    $this->db->query($updateq);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    function delete_blank_delivery_event()
    {
        //GET DELIVERED EVENT
        $qry = "SELECT t.*,del.delivery_date FROM docket_tracking t 
        JOIN docket_delivery del ON(del.docket_id=t.docket_id)
        WHERE t.status=1 AND t.docket_state_id=10";
        $qry_exe = $this->db->query($qry);
        $delivered_data = $qry_exe->result_array();

        if (isset($delivered_data) && is_array($delivered_data) && count($delivered_data) > 0) {
            foreach ($delivered_data as $key => $value) {
                if ($value['delivery_date'] == '' || $value['delivery_date'] == '1970-01-01' || $value['delivery_date'] == '0000-00-00') {
                    //DELETE DELIVERY EVENT
                    $deleq = "UPDATE docket_tracking SET status=3,modified_by=`id` WHERE id='" . $value['id'] . "'";
                    $this->db->query($deleq);

                    //MARK DOCKET IN TRANSIT STATE
                    $deleq = "UPDATE docket SET state_id=14,modified_by=`state_id` WHERE id='" . $value['docket_id'] . "'";
                    $this->db->query($deleq);
                }
            }
        }
    }

    function delete_receipt_ledger()
    {

        $this->load->helper('url');
        $this->load->model('Global_model', 'gm');
        $this->load->helper('frontend_common');

        //GET DELETED RECEIPT,OPENING AND NOTE
        $qry = "SELECT id FROM payment_receipt WHERE status=3";
        $qry_exe = $this->db->query($qry);
        $receipt_data = $qry_exe->result_array();

        $qry = "SELECT id FROM opening_balance WHERE status=3";
        $qry_exe = $this->db->query($qry);
        $opening_data = $qry_exe->result_array();

        $qry = "SELECT id FROM credit_debit_note WHERE status=3";
        $qry_exe = $this->db->query($qry);
        $note_data = $qry_exe->result_array();

        if (isset($receipt_data) && is_array($receipt_data) && count($receipt_data) > 0) {
            foreach ($receipt_data  as $key => $value) {
                $deleted_data[] = array(
                    'credit_id' => $value['id'],
                    'credit_id_type' => 1
                );
            }
        }
        if (isset($opening_data) && is_array($opening_data) && count($opening_data) > 0) {
            foreach ($opening_data  as $key => $value) {
                $deleted_data[] = array(
                    'credit_id' => $value['id'],
                    'credit_id_type' => 3
                );
            }
        }
        if (isset($note_data) && is_array($note_data) && count($note_data) > 0) {
            foreach ($note_data  as $key => $value) {
                $deleted_data[] = array(
                    'credit_id' => $value['id'],
                    'credit_id_type' => 2
                );
            }
        }

        if (isset($deleted_data) && is_array($deleted_data) && count($deleted_data) > 0) {
            foreach ($deleted_data as $key => $value) {
                $credit_id = $value['credit_id'];
                $credit_id_type = $value['credit_id_type'];
                if ($credit_id > 0 && $credit_id_type > 0) {
                    $this->db->trans_begin();

                    $qry = "SELECT * FROM docket_include_data WHERE credit_id='" . $credit_id . "'
                     AND credit_id_type='" . $credit_id_type . "'";
                    $qry_exe = $this->db->query($qry);
                    $include_data = $qry_exe->result_array();

                    if (isset($include_data) && is_array($include_data) && count($include_data) > 0) {
                        $include_update = array(
                            'status' => 3,
                            'modified_date' =>  date('Y-m-d H:i:s'),
                            'modified_by' => $this->user_id,
                        );
                        $this->gm->update('docket_include_data', $include_update, '', array('credit_id' => $credit_id, 'credit_id_type' => $credit_id_type));



                        foreach ($include_data as $ikey => $ivalue) {

                            //ADD DEBIT LEGDER FOR OUTSTANDING
                            $payment_id = $ivalue['invoice_id'];
                            $invoice_type = $ivalue['invoice_type'];
                            $total_received = 0;

                            if ($invoice_type == 1) {
                                $payment_type = 1; //OPENING
                            } else if ($invoice_type == 2) {
                                $payment_type = '3,4';
                            } else if ($invoice_type == 3) {
                                $payment_type = 5;
                            }

                            if ($payment_type > 0) {
                                $updateq = "UPDATE ledger_outstanding_item SET status=3,modified_date='3000-01-01 00:00:00' WHERE payment_id='" . $payment_id . "' 
            AND payment_type IN(" . $payment_type . ") AND status IN(1,2)";
                                $this->db->query($updateq);

                                //REMOVE DEDUCTION,TDS,ROUND OFF IN LEDGER
                                $updateq = "UPDATE ledger_item SET status=3,modified_date='3000-01-01 00:00:00' WHERE payment_id='" . $ivalue['id'] . "' 
                            AND payment_type='6' AND status IN(1,2)";
                                $this->db->query($updateq);


                                $updateq = "UPDATE ledger_outstanding_item SET status=3,modified_date='3000-01-01 00:00:00' WHERE payment_id='" . $ivalue['id'] . "' 
                                                AND payment_type='6' AND status IN(1,2)";
                                $this->db->query($updateq);

                                if ($ivalue['invoice_type'] == 1) {
                                    $payment_type = 1;
                                    $invoice_data = $this->gm->get_selected_record('opening_balance', 'id,opening_amount', $where = array('id' => $ivalue['invoice_id']), array(), array('status' => array(1, 2)));

                                    if (isset($invoice_data) && is_array($invoice_data) && count($invoice_data) > 0) {
                                        $include_invoice_amt =  get_include_in_data($ivalue['invoice_id'], 1, 1);
                                        if (isset($include_invoice_amt) && is_array($include_invoice_amt) && count($include_invoice_amt) > 0) {
                                            foreach ($include_invoice_amt as $amt_key => $amt_value) {
                                                foreach ($amt_value as $in_key => $in_value) {
                                                    $total_received += $in_value['deduction_amt'] + $in_value['tds_amt'] + $in_value['received_amt'];
                                                }
                                            }
                                        }
                                    }
                                    $received_amt = $total_received;
                                    $total_amt = isset($invoice_data['opening_amount']) ? $invoice_data['opening_amount'] : 0;
                                } else if ($ivalue['invoice_type'] == 2) {
                                    $payment_type = 4;
                                    $invoice_data = $this->gm->get_selected_record('credit_debit_note', 'id,grand_total_amount', $where = array('id' => $ivalue['invoice_id']), array(), array('status' => array(1, 2)));
                                    if (isset($invoice_data) && is_array($invoice_data) && count($invoice_data) > 0) {
                                        $include_invoice_amt =  get_include_in_data($ivalue['invoice_id'], 2, 1);
                                        if (isset($include_invoice_amt) && is_array($include_invoice_amt) && count($include_invoice_amt) > 0) {
                                            foreach ($include_invoice_amt as $amt_key => $amt_value) {
                                                foreach ($amt_value as $in_key => $in_value) {
                                                    $total_received += $in_value['deduction_amt'] + $in_value['tds_amt'] + $in_value['received_amt'];
                                                }
                                            }
                                        }
                                    }
                                    $received_amt = $total_received;
                                    $total_amt = isset($invoice_data['grand_total_amount']) ? $invoice_data['grand_total_amount'] : 0;
                                } else if ($ivalue['invoice_type'] == 3) {
                                    $payment_type = 5;
                                    //MARK INVOICE AS PAYMENT RECEVIED OR NOT
                                    $invoice_data = $this->gm->get_selected_record('docket_invoice', 'id,grand_total', $where = array('id' => $ivalue['invoice_id'], 'status' => 1), array());

                                    if (isset($invoice_data) && is_array($invoice_data) && count($invoice_data) > 0) {


                                        $include_invoice_amt =  get_include_in_data($ivalue['invoice_id'], 3, 1);
                                        if (isset($include_invoice_amt) && is_array($include_invoice_amt) && count($include_invoice_amt) > 0) {
                                            foreach ($include_invoice_amt as $amt_key => $amt_value) {
                                                foreach ($amt_value as $in_key => $in_value) {
                                                    $total_received += $in_value['deduction_amt'] + $in_value['tds_amt'] + $in_value['received_amt'];
                                                }
                                            }
                                        }

                                        if ($total_received >= $invoice_data['grand_total']) {
                                            //MARK PAYMENT RECEIVED
                                            $this->gm->update('docket_invoice', array('payment_received' => 1), '', array('id' => $invoice_data['id']));
                                        } else {
                                            $this->gm->update('docket_invoice', array('payment_received' => 2), '', array('id' => $invoice_data['id']));
                                        }
                                    }
                                    $received_amt = $total_received;
                                    $total_amt = isset($invoice_data['grand_total']) ? $invoice_data['grand_total'] : 0;
                                }

                                //ADD RECORD
                                if ($payment_type == 1) {
                                    $qry = "SELECT customer_id,opening_date as ledger_date,particular FROM opening_balance WHERE id='" . $payment_id . "'";
                                } else if ($payment_type == 4) {
                                    $qry = "SELECT customer_id,grand_total_amount as amount,note_date as ledger_date,note_no as ledger_no FROM credit_debit_note WHERE id='" . $payment_id . "'";
                                } else if ($payment_type == 5) {
                                    $qry = "SELECT customer_id,invoice_date as ledger_date,invoice_no as ledger_no FROM docket_invoice WHERE id='" . $payment_id . "'";
                                }

                                $qry_exe = $this->db->query($qry);
                                $debit_data = $qry_exe->row_array();
                                $legder_data = array(
                                    'customer_id' => $debit_data['customer_id'],
                                    'payment_id' => $payment_id,
                                    'payment_type' => $payment_type,
                                    'amount' => $total_amt - $received_amt,
                                    'particular' => isset($debit_data['particular']) ? $debit_data['particular'] : '',
                                    'ledger_date' => $debit_data['ledger_date'],
                                    'ledger_no' => isset($debit_data['ledger_no']) ? $debit_data['ledger_no'] : '',
                                    'ledger_type' => 2,
                                    'created_date' =>  date('Y-m-d H:i:s'),
                                    'created_by' => $this->user_id,
                                );

                                $this->gm->insert('ledger_outstanding_item', $legder_data);
                            }
                        }
                    }

                    if ($credit_id_type == 1) {
                        $table_name = "payment_receipt";
                        $amount_col = 'receipt_amount';
                    } else if ($credit_id_type == 2) {
                        $table_name = "credit_debit_note";
                        $amount_col = 'grand_total_amount';
                    } else if ($credit_id_type == 3) {
                        $table_name = "opening_balance";
                        $amount_col = 'opening_balance';
                    }
                    if ($table_name != '') {
                        $updateq = "UPDATE " . $table_name . " SET 
                        deduction_amt=0,
                        tds_amt=0,
                        received_amt=0,
                        leftover_amt=" . $amount_col . ",
                        round_off_amt=0,,modified_date='3000-01-01 00:00:00' WHERE id='" . $credit_id . "'";
                        $this->db->query($updateq);

                        $post_data['credit_id'] = $credit_id;
                        $post_data['credit_id_type'] = $credit_id_type;
                        //ADD CREDIT LEDGER FOR OUTSTANDING
                        $leftover_data = $this->gm->get_selected_record($table_name, 'id,leftover_amt', $where = array('id' => $post_data['credit_id'], 'status!=' => 3), array());
                        $payment_id = $post_data['credit_id'];
                        if ($post_data['credit_id_type'] == 1) {
                            $payment_type = 2;
                        } else if ($post_data['credit_id_type'] == 2) {
                            $payment_type = 3;
                        } else if ($post_data['credit_id_type'] == 3) {
                            $payment_type = 1;
                        }

                        $updateq = "UPDATE ledger_outstanding_item SET status=3,modified_date='3000-01-01 00:00:00' WHERE  payment_id='" . $payment_id . "' 
                        AND payment_type='" . $payment_type . "' AND status IN(1,2) ";
                        $this->db->query($updateq);

                        //ADD RECORD
                        if ($payment_type == 1) {
                            $qry = "SELECT customer_id,opening_amount as amount,opening_date as ledger_date,particular FROM opening_balance WHERE id='" . $payment_id . "'";
                        } else if ($payment_type == 2) {
                            $qry = "SELECT customer_id,receipt_date as ledger_date,payment_no as ledger_no,particular FROM payment_receipt WHERE id='" . $payment_id . "'";
                        } else if ($payment_type == 3) {
                            $qry = "SELECT customer_id,grand_total_amount as amount,note_date as ledger_date,note_no as ledger_no FROM credit_debit_note WHERE id='" . $payment_id . "'";
                        }
                        $qry_exe = $this->db->query($qry);
                        $debit_data = $qry_exe->row_array();
                        $legder_data = array(
                            'customer_id' => $debit_data['customer_id'],
                            'payment_id' => $payment_id,
                            'payment_type' => $payment_type,
                            'amount' => $leftover_data['leftover_amt'],
                            'particular' => $debit_data['particular'],
                            'ledger_date' => $debit_data['ledger_date'],
                            'ledger_no' => isset($debit_data['ledger_no']) ? $debit_data['ledger_no'] : '',
                            'ledger_type' => 1,
                            'created_date' =>  date('Y-m-d H:i:s'),
                            'created_by' => $this->user_id,
                        );
                        $this->gm->insert('ledger_outstanding_item', $legder_data);
                    }



                    //DELETE MASTER
                    $delete_update = array(
                        'status' => 3,
                        'modified_date' =>  date('Y-m-d H:i:s'),
                        'modified_by' => $this->user_id,
                    );
                    $this->gm->update($table_name, $delete_update, '', array('id' => $credit_id));

                    //REMOVE RECEIPT/NOTE OPENING IN LEDGER
                    if ($post_data['credit_id_type'] == 1) {
                        $payment_type = 2;
                    } else if ($post_data['credit_id_type'] == 2) {
                        $payment_type = '3,4';
                    } else if ($post_data['credit_id_type'] == 3) {
                        $payment_type = 1;
                    }

                    $updateq = "UPDATE ledger_item SET status=3,modified_date='3000-01-01 00:00:00' WHERE payment_id='" . $credit_id . "' 
                        AND payment_type IN(" . $payment_type . ")";
                    $this->db->query($updateq);

                    $updateq = "UPDATE ledger_outstanding_item SET status=3,modified_date='3000-01-01 00:00:00' WHERE payment_id='" . $credit_id . "' 
                        AND payment_type IN(" . $payment_type . ")";
                    $this->db->query($updateq);

                    $this->db->trans_commit();
                    $this->db->trans_complete();
                }
            }
        }
    }


    function update_shipper_customer()
    {
        $this->load->helper('url');
        $this->load->model('Global_model', 'gm');
        $qry = "SELECT id FROM shipper WHERE status IN(1,2) AND customer_id=0";
        $qry_exe = $this->db->query($qry);
        $result = $qry_exe->result_array();

        if (isset($result) && is_array($result) && count($result) > 0) {
            foreach ($result as $key => $value) {
                //GET SHIPPER FIRST DOCKET
                $qry = "SELECT d.id,d.customer_id FROM docket d
                JOIN docket_shipper ds ON(d.id=ds.docket_id)
                WHERE d.status IN(1,2) AND  ds.status IN(1,2) AND ds.shipper_id='" . $value['id'] . "'";
                $qry_exe = $this->db->query($qry);
                $docket_data = $qry_exe->row_array();

                if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
                    $this->gm->update('shipper', array('customer_id' => $docket_data['customer_id'], 'modified_date' => '2000-01-01 00:00:00'), '', array('id' => $value['id']));
                }
            }
        }
    }

    function update_consignee_customer()
    {
        $this->load->helper('url');
        $this->load->model('Global_model', 'gm');
        $qry = "SELECT id FROM consignee WHERE status IN(1,2) AND customer_id=0";
        $qry_exe = $this->db->query($qry);
        $result = $qry_exe->result_array();

        if (isset($result) && is_array($result) && count($result) > 0) {
            foreach ($result as $key => $value) {
                //GET SHIPPER FIRST DOCKET
                $qry = "SELECT d.id,d.customer_id FROM docket d
                JOIN docket_consignee ds ON(d.id=ds.docket_id)
                WHERE d.status IN(1,2) AND  ds.status IN(1,2) AND ds.consignee_id='" . $value['id'] . "'";
                $qry_exe = $this->db->query($qry);
                $docket_data = $qry_exe->row_array();

                if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
                    $this->gm->update('consignee', array('customer_id' => $docket_data['customer_id'], 'modified_date' => '2000-01-01 00:00:00'), '', array('id' => $value['id']));
                }
            }
        }
    }

    function update_invoice_gst($where = '')
    {
        $this->load->helper('url');
        $this->load->model('Global_model', 'gm');
        $this->load->helper('frontend_common');

        $qry = "SELECT id FROM docket_invoice WHERE status IN(1,2) AND invoice_type!=4 " . $where;
        $qry_exe = $this->db->query($qry);
        $invoice_data = $qry_exe->result_array();


        if (isset($invoice_data) && is_array($invoice_data) && count($invoice_data) > 0) {
            foreach ($invoice_data as $key => $value) {
                //GET INVOICE DOCKET
                $qry = "SELECT docket_id FROM docket_invoice_map WHERE status IN(1,2) AND docket_invoice_id ='" . $value['id'] . "'";
                $qry_exe = $this->db->query($qry);
                $docket_data = $qry_exe->result_array();



                $docket_ids = array();
                if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
                    foreach ($docket_data as $dkey => $dvalue) {
                        if ($dvalue['docket_id'] > 0) {
                            $docket_ids[$dvalue['docket_id']] = $dvalue['docket_id'];
                        }
                    }
                }
                $calculation_data =  calculate_invoice_gst($docket_ids);

                $bill_data['non_taxable_amt'] = isset($calculation_data['non_taxable_amt']) ? $calculation_data['non_taxable_amt'] : 0;
                $bill_data['taxable_amt'] = isset($calculation_data['taxable_amt']) ? $calculation_data['taxable_amt'] : 0;
                $bill_data['gst_per'] = isset($calculation_data['gst_per']) ? $calculation_data['gst_per'] : 0;
                $bill_data['igst_amount'] = isset($calculation_data['igst_amount']) ? $calculation_data['igst_amount'] : 0;
                $bill_data['cgst_amount'] = isset($calculation_data['cgst_amount']) ? $calculation_data['cgst_amount'] : 0;
                $bill_data['sgst_amount'] = isset($calculation_data['sgst_amount']) ? $calculation_data['sgst_amount'] : 0;
                $bill_data['grand_total'] = isset($calculation_data['grand_total']) ? $calculation_data['grand_total'] : 0;

                $this->gm->update('docket_invoice', $bill_data, '', array('id' => $value['id']));
                add_ledger_item($value['id'], 5, 2);
                echo "<br>INVOICE_ID" . $value['id'];
                //echo "<br><br>qry=" . $this->db->last_query();
            }
        }



        if (isset($_GET['update_docketless'])) {
            //UPDATE DOCKETLESS 
            $qry = "SELECT id FROM docket_invoice WHERE status IN(1,2) AND invoice_type=4";
            $qry_exe = $this->db->query($qry);
            $less_invoice_data = $qry_exe->result_array();

            if (isset($less_invoice_data) && is_array($less_invoice_data) && count($less_invoice_data) > 0) {
                foreach ($less_invoice_data as $lkey => $lvalue) {
                    $qry = "SELECT SUM(total_amount) as total_amount  FROM `docket_less_invoice_item` WHERE `invoice_id` = '" . $lvalue['id'] . "'";
                    $qry_exe = $this->db->query($qry);
                    $total_data = $qry_exe->row_array();

                    $less_update = array(
                        'grand_total' => isset($total_data['total_amount']) ? $total_data['total_amount'] : 0
                    );
                    $this->gm->update('docket_invoice', $less_update, '', array('id' => $lvalue['id']));
                    add_ledger_item($lvalue['id'], 5, 2);
                    echo "<br>DOCKET LESS INVOICE_ID" . $lvalue['id'];
                }
            }
        }
    }


    function test_gst($where = '')
    {
        $this->load->helper('url');
        $this->load->model('Global_model', 'gm');
        $this->load->helper('frontend_common');

        $where = " AND id=207";
        $qry = "SELECT id FROM docket_invoice WHERE status IN(1,2) " . $where;
        $qry_exe = $this->db->query($qry);
        $invoice_data = $qry_exe->result_array();


        if (isset($invoice_data) && is_array($invoice_data) && count($invoice_data) > 0) {
            foreach ($invoice_data as $key => $value) {
                //GET INVOICE DOCKET
                $qry = "SELECT docket_id FROM docket_invoice_map WHERE status IN(1,2) AND docket_invoice_id ='" . $value['id'] . "'";
                $qry_exe = $this->db->query($qry);
                $docket_data = $qry_exe->result_array();



                $docket_ids = array();
                if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
                    foreach ($docket_data as $dkey => $dvalue) {
                        if ($dvalue['docket_id'] > 0) {
                            $docket_ids[$dvalue['docket_id']] = $dvalue['docket_id'];
                        }
                    }
                }
                $calculation_data =  calculate_invoice_gst($docket_ids);

                $bill_data['non_taxable_amt'] = isset($calculation_data['non_taxable_amt']) ? $calculation_data['non_taxable_amt'] : 0;
                $bill_data['taxable_amt'] = isset($calculation_data['taxable_amt']) ? $calculation_data['taxable_amt'] : 0;
                $bill_data['gst_per'] = isset($calculation_data['gst_per']) ? $calculation_data['gst_per'] : 0;
                $bill_data['igst_amount'] = isset($calculation_data['igst_amount']) ? $calculation_data['igst_amount'] : 0;
                $bill_data['cgst_amount'] = isset($calculation_data['cgst_amount']) ? $calculation_data['cgst_amount'] : 0;
                $bill_data['sgst_amount'] = isset($calculation_data['sgst_amount']) ? $calculation_data['sgst_amount'] : 0;
                $bill_data['grand_total'] = isset($calculation_data['grand_total']) ? $calculation_data['grand_total'] : 0;

                echo '<pre>';
                print_r($bill_data);
                exit;
            }
        }
    }

    function remove_duplicate_docket_extra()
    {
        $qry = "SELECT COUNT(*) AS `Rows`, `docket_id` FROM `docket_extra_field` WHERE status=1 GROUP BY `docket_id` HAVING COUNT(*) >1";
        $qry_exe = $this->db->query($qry);
        $duplicate_data = $qry_exe->result_array();

        if (isset($duplicate_data) && is_array($duplicate_data) && count($duplicate_data) > 0) {
            foreach ($duplicate_data as $key => $value) {
                $qry = "SELECT id FROM docket_extra_field WHERE status=1 AND docket_id='" . $value['docket_id'] . "' ORDER BY id DESC";
                $qry_exe = $this->db->query($qry);
                $group_data = $qry_exe->result_array();
                $extra_id = array();
                if (isset($group_data) && is_array($group_data) && count($group_data) > 0) {
                    foreach ($group_data as $gkey => $gvalue) {
                        $extra_id[] = $gvalue['id'];
                    }
                }


                if (isset($extra_id) && is_array($extra_id) && count($extra_id) > 0) {
                    unset($extra_id[0]);
                    $updateq = "UPDATE docket_extra_field SET status=3,modified_date='3000-01-01 00:00:00'
                    WHERE id IN(" . implode(",", $extra_id) . ")";
                    $this->db->query($updateq);
                    echo "<br>QRY=" . $updateq;
                }
            }
        }
    }




    public function update_docket_invoice_range()
    {

        $this->load->helper('url');
        $this->load->helper('upload');
        $this->load->model('Global_model', 'gm');

        $qry = "SELECT id,config_value FROM migration_log WHERE status IN(1,2) AND config_key='invoice_range_update_script' ";
        $qry_exe = $this->db->query($qry);
        $offset_res = $qry_exe->row_array();


        $limit = 10000;
        if (isset($offset_res['config_value']) && $offset_res['config_value'] != '') {
            $offset = $offset_res['config_value'];
        } else {
            $offset = 0;
        }

        //GET DOCKET DATA
        $qry = "SELECT id FROM docket WHERE invoice_range_id=0 LIMIT " . $limit . " OFFSET " . $offset;
        $qry_exe = $this->db->query($qry);
        $docket_res = $qry_exe->result_array();

        if (isset($docket_res) && is_array($docket_res) && count($docket_res) > 0) {
            foreach ($docket_res as $key => $value) {

                $docket_id = $value['id'];
                $docket_data = $this->gm->get_selected_record('docket', 'id,customer_id,vendor_id,company_id,total_pcs', array('id' => $docket_id, 'status' => 1), array());
                if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
                    //GET CUSTOMER COMPANY
                    if ($docket_data['company_id'] > 0) {
                        $companyq = "SELECT c.id FROM company_master c 
                WHERE c.status IN(1,2) AND c.id='" . $docket_data['company_id'] . "'";
                        $companyq_exe = $this->db->query($companyq);
                        $company_data = $companyq_exe->row_array();
                    }

                    if ($docket_data['vendor_id'] > 0) {
                        $service_type_data = $this->gm->get_selected_record('vendor', 'id,master_service_type', array('id' => $docket_data['vendor_id'], 'status' => 1), array());
                    }

                    $company_id = isset($company_data['id']) && $company_data['id'] != '' ? $company_data['id'] : 0;
                    $master_service_type_id = isset($service_type_data['master_service_type']) ? $service_type_data['master_service_type'] : 0;


                    //CHECK DOCKET SALES BILLING GST APPLY OR NOT
                    $biiling_data = $this->gm->get_selected_record('docket_sales_billing', 'id,is_gst_apply', array('docket_id' => $docket_id, 'status' => 1), array());

                    if (isset($biiling_data['is_gst_apply']) && $biiling_data['is_gst_apply'] == 1) {
                        //GET GST RANGE
                        if ($company_id > 0 && $master_service_type_id > 0) {
                            $range_data = $this->gm->get_selected_record('company_invoice_range', 'id,invoice_range_id', array('company_master_id' => $company_id, 'master_service_type' => $master_service_type_id, 'status' => 1), array());
                        }
                    } else {
                        //GET NON-GST RANGE
                        $rangeq = "SELECT id as invoice_range_id FROM invoice_range i WHERE i.status IN(1,2) AND i.code='NONGST'
                 AND i.is_non_gst=1 AND i.company_master_id='" . $company_id . "'";
                        $rangeq_exe = $this->db->query($rangeq);
                        $range_data = $rangeq_exe->row_array();
                    }

                    if (isset($range_data) && is_array($range_data) && count($range_data) > 0) {
                        if ($range_data['invoice_range_id'] > 0) {

                            $updateq = "UPDATE docket SET invoice_range_id='" . $range_data['invoice_range_id'] . "' WHERE id='" . $docket_id . "'";
                            $this->db->query($updateq);
                        }
                    }
                }


                //UPDATE OFFSET
                $qry = "SELECT  id,config_value FROM migration_log WHERE status IN(1,2) AND config_key='invoice_range_update_script' ";
                $qry_exe = $this->db->query($qry);
                $configExist = $qry_exe->row_array();

                $offset = $offset + 1;
                if (isset($configExist) && is_array($configExist) && count($configExist) > 0) {
                    $updateq = "UPDATE migration_log SET config_value='" . $offset . "' WHERE status IN(1,2) AND config_key='invoice_range_update_script'";
                    $this->db->query($updateq);
                } else {
                    $mig_insert_data = array(
                        'config_key' => 'invoice_range_update_script',
                        'config_value' => $offset
                    );
                    $this->gm->insert('migration_log', $mig_insert_data);
                }

                echo "<br>DOCKET ID=" . $docket_id;
            }
        }
    }

    function set_users()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');
        $sessiondata = $this->session->userdata('admin_user');
        $company_id = isset($sessiondata['com_id']) ? $sessiondata['com_id'] : '';
        $result = array();
        $CI = &get_instance();
        $main_db = $CI->load->database('main_db', true);
        $all_user = get_all_user(" AND company_id='" . $company_id . "' AND migration_id != 0", "", "migration_id");
        $docket_update = "";
        $docket_data = "SELECT id FROM docket where migration_id != 0";
        if (isset($all_user) && is_array($all_user) && count($all_user) > 0) {
            foreach ($all_user as $key => $value) {
                if ($value['migration_id'] != 0) {
                    $query = "UPDATE docket SET created_by = '" . $value['id'] . "' WHERE migration_id != 0 AND created_by = '" . $value['migration_id'] . "'";
                    if ($this->db->query($query)) {
                        echo "Successfull";
                    } else {
                        echo "Failed";
                    }
                }
            }
        }
    }

    function update_tracking_event()
    {
        $this->load->helper('url');
        $this->load->helper('report');
        $this->load->model('Global_model', 'gm');

        $qry = "SELECT id FROM `docket` WHERE `booking_date` >= '2022-05-01'";
        $qry_exe = $this->db->query($qry);
        $docket_data = $qry_exe->result_array();

        foreach ($docket_data as $key => $value) {
            # code...
            update_docket_tracking_state(0, $value['id']);
            echo $value['id'] . "Updated <br>";
        }
    }

    public function update_docket_invoice_eway()
    {
        $this->load->helper('url');
        $this->load->helper('report');
        $this->load->model('Global_model', 'gm');

        $qry = "SELECT docket_id,GROUP_CONCAT(invoice_no) as invoice_nos,GROUP_CONCAT(eway_bill) as eway_bill, SUM(invoice_amount) as invoice_val FROM `docket_entry_invoice` WHERE `status` != 3 GROUP BY docket_id";
        $qry_exe = $this->db->query($qry);
        $docket_data = $qry_exe->result_array();

        foreach ($docket_data as $key => $value) {
            $qry = "UPDATE docket SET invoice_no = '" . $value['invoice_nos'] . "' WHERE id = '" . $value['docket_id'] . "' AND invoice_no = ''";
            $qry_exe = $this->db->query($qry);
            echo "INVOICE NOS UPDATED FOR " . $value['docket_id'] . "<br>";

            $qry = "UPDATE docket SET eway_bill = '" . $value['eway_bill'] . "' WHERE id = '" . $value['docket_id'] . "' AND eway_bill = ''";
            $qry_exe = $this->db->query($qry);
            echo "EWAY BILL NOS UPDATED FOR " . $value['docket_id'] . "<br>";

            $qry = "UPDATE docket SET shipment_value = '" . $value['invoice_val'] . "' WHERE id = '" . $value['docket_id'] . "' AND shipment_value = 0";
            $qry_exe = $this->db->query($qry);
            echo "SHIPMENT VALUE UPDATED FOR " . $value['docket_id'] . "<br>";
        }
    }

    public function update_pcs()
    {
        $this->load->helper('url');
        $this->load->helper('report');
        $this->load->model('Global_model', 'gm');

        $qry = "SELECT SUM(box_count) as pcs,docket_id FROM docket_items WHERE status=1 
        AND DATE_FORMAT(`created_date`,'%Y-%m-%d')>='2022-08-15' GROUP BY docket_id";
        $qry_exe = $this->db->query($qry);
        $docket_data = $qry_exe->result_array();

        if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
            foreach ($docket_data as $key => $value) {
                $this->gm->update('docket', array('total_pcs' => $value['pcs']), '', array('id' => $value['docket_id']));
            }
        }
    }

    public function update_history_actual_wt()
    {
        $this->load->helper('url');
        $this->load->helper('report');
        $this->load->model('Global_model', 'gm');

        $qry = "SELECT id FROM `docket` WHERE DATE_FORMAT(`created_date`,'%Y-%m-%d')>='2022-08-15' AND (actual_wt=0)";
        $qry_exe = $this->db->query($qry);
        $docket_data = $qry_exe->result_array();

        if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
            foreach ($docket_data as $key => $value) {

                $qry = "SELECT * FROM `docket_history` WHERE docket_id='" . $value['id'] . "'";
                $qry_exe = $this->db->query($qry);
                $history_data = $qry_exe->row_array();


                if (isset($history_data) && is_array($history_data) && count($history_data) > 0) {
                    $json_data = $history_data['new_data'];
                    $array_data = json_decode($json_data, TRUE);
                    if (isset($array_data['actual_wt']) && $array_data['actual_wt'] > 0) {
                        $this->gm->update('docket', array('actual_wt' => $array_data['actual_wt']), '', array('id' => $value['id']));
                    }
                    if (isset($array_data['chargeable_wt']) && $array_data['chargeable_wt'] > 0) {
                        $this->gm->update('docket', array('chargeable_wt' => $array_data['chargeable_wt']), '', array('id' => $value['id']));
                    }
                }
            }
        }
    }


    public function update_history_chargeable_wt()
    {
        $this->load->helper('url');
        $this->load->helper('report');
        $this->load->model('Global_model', 'gm');

        $qry = "SELECT id FROM `docket` WHERE DATE_FORMAT(`created_date`,'%Y-%m-%d')>='2022-08-15' AND (chargeable_wt=0)";
        $qry_exe = $this->db->query($qry);
        $docket_data = $qry_exe->result_array();

        if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
            foreach ($docket_data as $key => $value) {

                $qry = "SELECT * FROM `docket_history` WHERE docket_id='" . $value['id'] . "'";
                $qry_exe = $this->db->query($qry);
                $history_data = $qry_exe->row_array();


                if (isset($history_data) && is_array($history_data) && count($history_data) > 0) {
                    $json_data = $history_data['new_data'];
                    $array_data = json_decode($json_data, TRUE);

                    if (isset($array_data['chargeable_wt']) && $array_data['chargeable_wt'] > 0) {
                        $this->gm->update('docket', array('chargeable_wt' => $array_data['chargeable_wt']), '', array('id' => $value['id']));
                    }
                }
            }
        }
    }

    public function update_history_pcs()
    {
        $this->load->helper('url');
        $this->load->helper('report');
        $this->load->model('Global_model', 'gm');

        $qry = "SELECT id FROM `docket` WHERE DATE_FORMAT(`created_date`,'%Y-%m-%d')>='2022-08-15' AND total_pcs=0";
        $qry_exe = $this->db->query($qry);
        $docket_data = $qry_exe->result_array();

        if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
            foreach ($docket_data as $key => $value) {

                $qry = "SELECT * FROM `docket_history` WHERE docket_id='" . $value['id'] . "'";
                $qry_exe = $this->db->query($qry);
                $history_data = $qry_exe->row_array();


                if (isset($history_data) && is_array($history_data) && count($history_data) > 0) {
                    $json_data = $history_data['new_data'];
                    $array_data = json_decode($json_data, TRUE);
                    if (isset($array_data['total_pcs']) && $array_data['total_pcs'] > 0) {
                        $this->gm->update('docket', array('total_pcs' => $array_data['total_pcs']), '', array('id' => $value['id']));
                    }
                }
            }
        }
    }
    public function remove_duplicate_outstanding()
    {
        $this->load->helper('url');
        $this->load->helper('report');
        $this->load->model('Global_model', 'gm');

        $qry = "SELECT l.* FROM `ledger_outstanding_item` l JOIN credit_debit_note n ON(n.id=l.payment_id) WHERE l.status=1 AND n.status=1 AND l.amount=0 AND l.payment_type=4";
        $qry_exe = $this->db->query($qry);
        $result = $qry_exe->result_array();
        if (isset($result) && is_array($result) && count($result) > 0) {
            foreach ($result as $key => $value) {
                $ids = array();
                $qry = "SELECT id FROM ledger_outstanding_item WHERE 
                payment_id='" . $value['payment_id'] . "' AND payment_type='" . $value['payment_type'] . "'
                AND status=3 AND amount!=0 AND ledger_type=2";
                $qry_exe = $this->db->query($qry);
                $duplicate_data = $qry_exe->row_array();
                if (isset($duplicate_data) && is_array($duplicate_data) && count($duplicate_data) > 0) {
                    $qry = "UPDATE ledger_outstanding_item SET status=1 WHERE id =" . $duplicate_data['id'];
                    $this->db->query($qry);

                    $qry = "UPDATE ledger_outstanding_item SET status=3 WHERE id =" . $value['id'];
                    $this->db->query($qry);
                } else {
                    echo "NOT FOUND==" . $value['payment_id'];
                }
            }
        }

        // $qry = "SELECT COUNT(id),payment_id,payment_type FROM `ledger_outstanding_item` 
        // WHERE status=1 AND payment_type!=6 GROUP BY payment_id,payment_type HAVING COUNT(id)>1";
        // $qry_exe = $this->db->query($qry);
        // $result = $qry_exe->result_array();
        // if (isset($result) && is_array($result) && count($result) > 0) {
        //     foreach ($result as $key => $value) {
        //         $ids = array();
        //         $qry = "SELECT id FROM ledger_outstanding_item WHERE 
        //         payment_id='" . $value['payment_id'] . "' AND payment_type='" . $value['payment_type'] . "'
        //         AND status=1";
        //         $qry_exe = $this->db->query($qry);
        //         $duplicate_data = $qry_exe->result_array();
        //         if (isset($duplicate_data) && is_array($duplicate_data) && count($duplicate_data) > 1) {
        //             foreach ($duplicate_data as $dkey => $dvalue) {
        //                 if ($dkey != count($duplicate_data) - 1) {
        //                     $ids[] = $dvalue['id'];
        //                 }
        //             }
        //         }

        //         if (isset($ids) && is_array($ids) && count($ids) > 0) {
        //             $qry = "UPDATE ledger_outstanding_item SET status=3 WHERE id IN(" . implode(",", $ids) . ")";
        //             $qry_exe = $this->db->query($qry);
        //         }
        //     }
        // }
    }
    public function update_outstanding()
    {
        $this->load->helper('url');
        $this->load->helper('report');
        $this->load->model('Global_model', 'gm');

        //FOR DEBIT NOTE THAT IS SHOWING IN CREDIT
        $updateq = "UPDATE
        ledger_outstanding_item l
        JOIN credit_debit_note n ON(n.id=l.payment_id)
        SET l.ledger_type=2,l.payment_type=4
    WHERE l.status=1 AND n.status=1 AND n.note_category=2 AND l.payment_type=3";
        $this->db->query($updateq);

        $qry = "SELECT *  FROM `ledger_outstanding_item` WHERE status=1 AND payment_type!=5 AND amount!=0";
        $qry_exe = $this->db->query($qry);
        $result = $qry_exe->result_array();

        if (isset($result) && is_array($result) && count($result) > 0) {
            $this->load->module('account');
            foreach ($result as $key => $value) {
                if ($value['payment_type'] == 1) {
                    $credit_id_type = 3; //OPENING
                } else if ($value['payment_type'] == 2) {
                    $credit_id_type = 1; //RECEIPT
                } else if ($value['payment_type'] == 3 || $value['payment_type'] == 4) {
                    $credit_id_type = 2; //NOTE
                }
                $credit_id = $value['payment_id'];
                $qry = '';
                $payment_data = array();
                if ($credit_id_type == 1) {
                    $qry = "SELECT customer_id,receipt_amount as amount,receipt_date as ledger_date,payment_no as ledger_no,particular FROM payment_receipt WHERE id='" . $credit_id . "'";
                } else if ($credit_id_type == 2) {
                    $qry = "SELECT customer_id,id as credit_debit_note_id,grand_total_amount as amount,note_date as ledger_date,note_no as ledger_no FROM credit_debit_note WHERE id='" . $credit_id . "'";
                } else if ($credit_id_type == 3) {
                    $qry = "SELECT customer_id,opening_amount as amount,opening_date as ledger_date,particular FROM opening_balance WHERE id='" . $credit_id . "'";
                }

                if ($qry != '') {
                    $qry_exe = $this->db->query($qry);
                    $payment_data = $qry_exe->row_array();
                }
                if (isset($payment_data) && is_array($payment_data) && count($payment_data) > 0) {
                    $include_param = array(
                        'credit_id' => $value['payment_id'],
                        'credit_id_type' => $credit_id_type,
                        'response_type' => 'data'
                    );

                    $received_amt = 0;
                    $total_amt = isset($payment_data['amount']) ? $payment_data['amount'] : 0;
                    $include_data =  $this->account->get_include_data($include_param);




                    if (isset($include_data['include_data']) && is_array($include_data['include_data']) && count($include_data['include_data']) > 0) {
                        foreach ($include_data['include_data'] as $ikey => $ivalue) {
                            $received_amt += $ivalue['received_amt'];
                        }
                    }

                    $leftover_amt = $total_amt - $received_amt;
                    $updateq = "UPDATE ledger_outstanding_item SET amount='" . $leftover_amt . "'
                    ,modified_date='3000-00-00 00:00:00' WHERE id='" . $value['id'] . "'";

                    $this->db->query($updateq);
                }
            }
        }
    }


    public function update_include_outstanding($payment_type = 0)
    {
        $this->load->helper('url');
        $this->load->helper('report');
        $this->load->model('Global_model', 'gm');

        $qry = "SELECT *  FROM `ledger_outstanding_item` WHERE status=1 AND amount!=0  AND payment_type=" . $payment_type;
        $qry_exe = $this->db->query($qry);
        $result = $qry_exe->result_array();

        if (isset($result) && is_array($result) && count($result) > 0) {
            $this->load->module('account');
            foreach ($result as $key => $value) {
                $total_received = 0;
                $invoice_data = array();
                $include_invoice_amt = array();
                if ($value['payment_type'] == 1) {
                    $payment_type = 1;
                    $invoice_data = $this->gm->get_selected_record('opening_balance', 'id,opening_amount', $where = array('id' => $value['payment_id']), array(), array('status' => array(1, 2)));

                    if (isset($invoice_data) && is_array($invoice_data) && count($invoice_data) > 0) {
                        $include_invoice_amt =  get_include_in_data($value['payment_id'], 1, 1);
                        if (isset($include_invoice_amt) && is_array($include_invoice_amt) && count($include_invoice_amt) > 0) {
                            foreach ($include_invoice_amt as $amt_key => $amt_value) {
                                foreach ($amt_value as $in_key => $in_value) {
                                    $total_received += $in_value['deduction_amt'] + $in_value['tds_amt'] + $in_value['received_amt'];
                                }
                            }
                        }
                    }
                    $received_amt = $total_received;
                    $total_amt = isset($invoice_data['opening_amount']) ? $invoice_data['opening_amount'] : 0;
                } else if ($value['payment_type'] == 3 || $value['payment_type'] == 4) {
                    $payment_type = 4;
                    $invoice_data = $this->gm->get_selected_record('credit_debit_note', 'id,grand_total_amount', $where = array('id' => $value['payment_id']), array(), array('status' => array(1, 2)));
                    if (isset($invoice_data) && is_array($invoice_data) && count($invoice_data) > 0) {
                        $include_invoice_amt =  get_include_in_data($value['payment_id'], 2, 1);
                        if (isset($include_invoice_amt) && is_array($include_invoice_amt) && count($include_invoice_amt) > 0) {
                            foreach ($include_invoice_amt as $amt_key => $amt_value) {
                                foreach ($amt_value as $in_key => $in_value) {
                                    $total_received += $in_value['deduction_amt'] + $in_value['tds_amt'] + $in_value['received_amt'];
                                }
                            }
                        }
                    }
                    $received_amt = $total_received;
                    $total_amt = isset($invoice_data['grand_total_amount']) ? $invoice_data['grand_total_amount'] : 0;
                } else if ($value['payment_type'] == 5) {
                    $payment_type = 5;
                    //MARK INVOICE AS PAYMENT RECEVIED OR NOT
                    $invoice_data = $this->gm->get_selected_record('docket_invoice', 'id,grand_total', $where = array('id' => $value['payment_id'], 'status' => 1), array());

                    if (isset($invoice_data) && is_array($invoice_data) && count($invoice_data) > 0) {
                        $include_invoice_amt =  get_include_in_data($value['payment_id'], 3, 1);

                        if (isset($include_invoice_amt) && is_array($include_invoice_amt) && count($include_invoice_amt) > 0) {
                            foreach ($include_invoice_amt as $amt_key => $amt_value) {
                                foreach ($amt_value as $in_key => $in_value) {
                                    $total_received += $in_value['deduction_amt'] + $in_value['tds_amt'] + $in_value['received_amt'];
                                }
                            }
                        }
                    }
                    $received_amt = $total_received;
                    $total_amt = isset($invoice_data['grand_total']) ? $invoice_data['grand_total'] : 0;
                }

                if (isset($include_invoice_amt) && is_array($include_invoice_amt) && count($include_invoice_amt) > 0) {
                    $leftover_amt = $total_amt - $received_amt;
                    $updateq = "UPDATE ledger_outstanding_item SET amount='" . $leftover_amt . "'
                ,modified_date='3000-00-00 00:00:00' WHERE id='" . $value['id'] . "'";


                    echo "<br>QRY=" . $updateq;
                    $this->db->query($updateq);
                } else {
                    $leftover_amt = $total_amt;
                    $updateq = "UPDATE ledger_outstanding_item SET amount='" . $leftover_amt . "'
                ,modified_date='3000-00-00 00:00:00' WHERE id='" . $value['id'] . "'";


                    echo "<br>QRY=" . $updateq;
                    $this->db->query($updateq);
                }
            }
        }
    }



    public function update_included_outstanding($payment_type = 0)
    {
        $this->load->helper('url');
        $this->load->helper('report');
        $this->load->model('Global_model', 'gm');




        if ($payment_type == 1) {
            $qry = "SELECT *  FROM `payment_receipt` WHERE status IN(1,2)";
            $qry_exe = $this->db->query($qry);
            $result = $qry_exe->result_array();
            $table = "payment_receipt";
        } else if ($payment_type == 2) {
            $qry = "SELECT *  FROM `credit_debit_note` WHERE status IN(1,2) ";
            $qry_exe = $this->db->query($qry);
            $result = $qry_exe->result_array();
            $table = "credit_debit_note";
        } else if ($payment_type == 3) {
            $qry = "SELECT *  FROM `opening_balance` WHERE status IN(1,2) ";
            $qry_exe = $this->db->query($qry);
            $result = $qry_exe->result_array();
            $table = "opening_balance";
        }


        if (isset($result) && is_array($result) && count($result) > 0) {
            $this->load->module('account');
            foreach ($result as $key => $value) {
                $total_received = 0;
                $deduction_amt = 0;
                $tds_amt = 0;
                $received_amt = 0;
                $round_off_amt = 0;

                $invoice_data = array();
                $include_invoice_amt = array();
                if ($payment_type == 1) {
                    $include_invoice_amt =  get_include_data($value['id'], 1, 1);
                    $total_amt = isset($value['receipt_amount']) ? $value['receipt_amount'] : 0;
                } else if ($payment_type == 2) {
                    $include_invoice_amt =  get_include_data($value['id'], 2, 1);
                    $total_amt = isset($value['grand_total_amount']) ? $value['grand_total_amount'] : 0;
                } else if ($payment_type == 3) {
                    $include_invoice_amt =  get_include_data($value['id'], 3, 1);
                    $total_amt = isset($value['opening_amount']) ? $value['opening_amount'] : 0;
                }
                if (isset($include_invoice_amt) && is_array($include_invoice_amt) && count($include_invoice_amt) > 0) {
                    foreach ($include_invoice_amt as $in_key => $in_value) {
                        //$total_received += $in_value['deduction_amt'] + $in_value['tds_amt'] + $in_value['received_amt'];
                        $total_received += $in_value['received_amt'];

                        $deduction_amt += $in_value['deduction_amt'];
                        $tds_amt += $in_value['tds_amt'];
                        $received_amt += $in_value['received_amt'];
                        $round_off_amt += $in_value['round_off_amt'];
                    }

                    $leftover_amt = $total_amt - $total_received;

                    $mainq = "UPDATE " . $table . " SET deduction_amt=" . $deduction_amt . ",tds_amt=" . $tds_amt . ",
                    received_amt=" . $received_amt . ",round_off_amt=" . $round_off_amt . ",leftover_amt=" . $leftover_amt . "
                    WHERE id=" . $value['id'];

                    echo "<br>QRY=" . $mainq;
                    $this->db->query($mainq);

                    if ($payment_type == 1) {
                        $updateq = "UPDATE ledger_outstanding_item SET amount='" . $leftover_amt . "'
                ,modified_date='3000-00-00 00:00:00' WHERE payment_id='" . $value['id'] . "' AND payment_type=2";
                    } else if ($payment_type == 2) {
                        $updateq = "UPDATE ledger_outstanding_item SET amount='" . $leftover_amt . "'
                ,modified_date='3000-00-00 00:00:00' WHERE payment_id='" . $value['id'] . "' AND payment_type IN(3,4)";
                    } else if ($payment_type == 3) {
                        $updateq = "UPDATE ledger_outstanding_item SET amount='" . $leftover_amt . "'
                ,modified_date='3000-00-00 00:00:00' WHERE payment_id='" . $value['id'] . "' AND payment_type =1";
                    }
                    echo "<br>QRY=" . $updateq;
                    $this->db->query($updateq);
                }
            }
        }
    }


    public function report_permission()
    {
        $this->load->helper('url');
        $this->load->helper('report');
        $this->load->model('Global_model', 'gm');
        $CI = &get_instance();
        $main_db = $CI->load->database('main_db', true);
        $qry = "SELECT user_id  FROM `user_permission_map` WHERE status=1 AND permission_id = 59";
        $qry_exe = $main_db->query($qry);
        $result = $qry_exe->result_array();

        if (isset($result) && is_array($result) && count($result) > 0) {
            foreach ($result as $key => $value) {
                if ($value['user_id'] != "") {
                    $user_id = $value['user_id'];
                    $qry = "INSERT INTO `user_permission_map` (`id`, `permission_type`, `status`, `user_id`, `permission_id`, `created_date`, `created_by`, `modified_date`, `modified_by`) 
                VALUES (NULL, 'Special', '1', $user_id , '336', '2022-09-02 16:25:06.000000', '', '2022-09-02 16:25:06.000000', '');";
                    $qry_exe = $main_db->query($qry);
                }
                echo $user_id . "<br>";
            }
        }
    }

    function add_opening_ledger()
    {
        $this->load->helper('url');
        $this->load->helper('report');
        $this->load->model('Global_model', 'gm');

        $qry = "SELECT * FROM opening_balance WHERE status=1";
        $qry_exe = $this->db->query($qry);
        $result = $qry_exe->result_array();

        if (isset($result) && is_array($result) && count($result) > 0) {

            $this->load->module('account/opening_balance');
            foreach ($result as $rkey => $rvalue) {
                $update_data = array(
                    'opening_id' => $rvalue['id'],
                    'opening' => array(
                        'customer_id' => $rvalue['customer_id'],
                        'opening_amount' => $rvalue['opening_amount'],
                        'opening_date' => '25-Sep-2022',
                        'particular' => $rvalue['particular'],
                        'payment_type' => $rvalue['payment_type'],
                    )
                );
                $this->opening_balance->update($update_data);
            }
        }
    }

    function update_co_vendor()
    {
        $file = fopen(FCPATH . '/log1/awcc_import.csv', 'r');
        while (($line = fgetcsv($file)) !== FALSE) {
            //$line is an array of the csv elements
            $result[] = $line;
        }
        fclose($file);


        if (isset($result) && is_array($result) && count($result) > 0) {
            foreach ($result as $key => $value) {
                if ($key != 0) {
                    $qry = "SELECT id FROM docket WHERE status=1 AND awb_no='" . $value[0] . "'";
                    $qry_exe = $this->db->query($qry);
                    $docket_data = $qry_exe->row_array();

                    if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
                        $updateq = "UPDATE docket SET co_vendor_id='" . $value[2] . "' WHERE id='" . $docket_data['id'] . "'";
                    }
                }
            }
        }
        echo '<pre>';
        print_r($result);
        exit;
    }

    function set_reprint_label_permission()
    {
        $this->load->helper('url');
        $this->load->helper('report');
        $this->load->model('Global_model', 'gm');
        $CI = &get_instance();
        $main_db = $CI->load->database('main_db', true);
        $qry = "SELECT id  FROM `admin_user` WHERE status=1";
        $qry_exe = $main_db->query($qry);
        $result = $qry_exe->result_array();
        if (isset($result) && is_array($result) && count($result) > 0) {
            foreach ($result as $key => $value) {
                if ($value['id'] != "") {
                    $user_id = $value['id'];
                    $qry = "INSERT INTO `user_permission_map` (`id`, `permission_type`, `status`, `user_id`, `permission_id`, `created_date`, `created_by`, `modified_date`, `modified_by`) 
                VALUES (NULL, 'Special', '1', $user_id , '337', '2022-09-02 16:25:06.000000', '', '2022-09-02 16:25:06.000000', '');";
                    $qry_exe = $main_db->query($qry);
                }
                echo $user_id . "<br>";
            }
        }
    }

    function update_account_company($table_name = '')
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');

        $all_cust = get_all_customer();
        if (isset($all_cust) && is_array($all_cust) && count($all_cust) > 0) {
            foreach ($all_cust as $ckey => $cvalue) {
                $cust_company[$cvalue['id']] = $cvalue['company_id'];
            }
        }

        if ($table_name != '') {
            $qry = "SELECT * FROM " . $table_name . " WHERE status IN(1,2) AND company_master_id=0";
            $qry_exe = $this->db->query($qry);
            $result = $qry_exe->result_array();

            if (isset($result) && is_array($result) && count($result) > 0) {
                foreach ($result as $key => $value) {
                    if (isset($cust_company[$value['customer_id']])) {
                        $company_id = $cust_company[$value['customer_id']];
                        $updateq = "UPDATE " . $table_name . " SET company_master_id='" . $company_id . "'
                         WHERE id='" . $value['id'] . "'";
                        // echo "<br>QRY=" . $updateq;
                        $this->db->query($updateq);
                    }
                }
            }
        }
    }

    public function update_shipper_copy()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');

        $qry = "SELECT m.*,m.module_id as docket_id FROM media_attachment m
        JOIN docket d ON(d.id=m.module_id)
         WHERE m.status=1 AND m.third_party_url LIKE '%shipper_copy.pdf%' AND d.status=1
         AND m.module_type=1";
        $qry_exe = $this->db->query($qry);
        $result = $qry_exe->result_array();

        $supported_vendor = $this->config->item('supported_label_vendor');
        if (isset($result) && is_array($result) && count($result) > 0) {
            foreach ($result as $key => $value) {
                $docket_id = $value['docket_id'];
                $query = "SELECT v.id,v.label_head_id,v.api_credentials,d.is_label_print,d.vendor_id,d.co_vendor_id FROM docket d 
             JOIN vendor v ON(v.id=d.vendor_id) WHERE v.status IN(1,2) AND d.id='" . $docket_id . "'";
                $query_exe = $this->db->query($query);
                $vendor_data = $query_exe->row_array();
                $final_credential = array();


                if (isset($vendor_data) && is_array($vendor_data) && count($vendor_data) > 0) {
                    if (isset($supported_vendor[$vendor_data['label_head_id']])) {
                        $query = "SELECT v.id,v.label_head_id,v.api_credentials,d.chargeable_wt,d.awb_no FROM docket d 
                        JOIN vendor v ON(v.id=d.vendor_id) WHERE v.status IN(1,2) AND d.id='" . $docket_id . "'";
                        $query_exe = $this->db->query($query);
                        $credential_data = $query_exe->row_array();

                        if (isset($credential_data) && is_array($credential_data) && count($credential_data) > 0) {
                            $vendor_id = $credential_data['id'];
                            $awb_no = $credential_data['awb_no'];
                            if ($credential_data['api_credentials'] != '') {

                                if ($supported_vendor[$vendor_data['label_head_id']] == 'itd_self_service') {

                                    $credential_data = explode("\n", $credential_data['api_credentials']);


                                    if (isset($credential_data) && is_array($credential_data) && count($credential_data) > 0) {
                                        foreach ($credential_data as $ckey => $cvalue) {
                                            if ($cvalue != '') {
                                                $credential_arr = explode(":", $cvalue);
                                                $final_credential[strtolower(trim($credential_arr[0]))] = str_ireplace(array("\r", "\n", '\r', '\n'), "", $credential_arr[1]);
                                            }
                                        }
                                    }

                                    $query = "SELECT enable_api_link,api_base_url,api_username,api_password,api_service_code,api_company_id FROM vendor WHERE id='" . $vendor_id . "' AND status IN(1,2)";

                                    $query_exe = $this->db->query($query);
                                    $vendor_data = $query_exe->row_array();

                                    if (isset($vendor_data) && is_array($vendor_data) && count($vendor_data) > 0) {
                                        if (
                                            $vendor_data['enable_api_link'] == 1 && $vendor_data['api_base_url'] != ''
                                            && $vendor_data['api_username'] != '' && $vendor_data['api_password'] != ''
                                        ) {
                                            $url = $vendor_data['api_base_url'] . '/docket_api/get_shipper_copy/' . $awb_no . '?cron_company=' . $vendor_data['api_company_id'];
                                            $ch = curl_init();
                                            curl_setopt($ch, CURLOPT_URL,  $url);
                                            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                                            curl_setopt($ch, CURLOPT_POSTFIELDS, array());
                                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                                                'Content-Type: application/json',
                                                'Accept: application/json'
                                            ));
                                            $response_json = curl_exec($ch);
                                            curl_close($ch);

                                            if ($response_json != '') {
                                                $file_name = "vendor_shipper_copy.pdf";
                                                $file_path = create_year_dir('docket_label_print',  $docket_id . '_' . $awb_no) . '/' . $file_name;
                                                $decode_data = base64_decode($response_json);
                                                file_put_contents($file_path, $decode_data);

                                                $update_data = array(
                                                    'media_path' => $file_path
                                                );
                                                $this->gm->update('media_attachment', $update_data, '', array('id' => $value['id']));
                                                echo "<br>QRY=" . $this->db->last_query();
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }



    public function update_box_label()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');

        $qry = "SELECT m.*,m.module_id as docket_id FROM media_attachment m
        JOIN docket d ON(d.id=m.module_id)
         WHERE m.status=1 AND m.third_party_url LIKE '%box_label.pdf%' AND d.status=1
         AND m.module_type=1";
        $qry_exe = $this->db->query($qry);
        $result = $qry_exe->result_array();

        $supported_vendor = $this->config->item('supported_label_vendor');
        if (isset($result) && is_array($result) && count($result) > 0) {
            foreach ($result as $key => $value) {
                $docket_id = $value['docket_id'];
                $query = "SELECT v.id,v.label_head_id,v.api_credentials,d.is_label_print,d.vendor_id,d.co_vendor_id FROM docket d 
             JOIN vendor v ON(v.id=d.vendor_id) WHERE v.status IN(1,2) AND d.id='" . $docket_id . "'";
                $query_exe = $this->db->query($query);
                $vendor_data = $query_exe->row_array();
                $final_credential = array();


                if (isset($vendor_data) && is_array($vendor_data) && count($vendor_data) > 0) {
                    if (isset($supported_vendor[$vendor_data['label_head_id']])) {
                        $query = "SELECT v.id,v.label_head_id,v.api_credentials,d.chargeable_wt,d.awb_no FROM docket d 
                        JOIN vendor v ON(v.id=d.vendor_id) WHERE v.status IN(1,2) AND d.id='" . $docket_id . "'";
                        $query_exe = $this->db->query($query);
                        $credential_data = $query_exe->row_array();

                        if (isset($credential_data) && is_array($credential_data) && count($credential_data) > 0) {
                            $vendor_id = $credential_data['id'];
                            $awb_no = $credential_data['awb_no'];
                            if ($credential_data['api_credentials'] != '') {

                                if ($supported_vendor[$vendor_data['label_head_id']] == 'itd_self_service') {

                                    $credential_data = explode("\n", $credential_data['api_credentials']);


                                    if (isset($credential_data) && is_array($credential_data) && count($credential_data) > 0) {
                                        foreach ($credential_data as $ckey => $cvalue) {
                                            if ($cvalue != '') {
                                                $credential_arr = explode(":", $cvalue);
                                                $final_credential[strtolower(trim($credential_arr[0]))] = str_ireplace(array("\r", "\n", '\r', '\n'), "", $credential_arr[1]);
                                            }
                                        }
                                    }

                                    $query = "SELECT enable_api_link,api_base_url,api_username,api_password,api_service_code,api_company_id FROM vendor WHERE id='" . $vendor_id . "' AND status IN(1,2)";

                                    $query_exe = $this->db->query($query);
                                    $vendor_data = $query_exe->row_array();

                                    if (isset($vendor_data) && is_array($vendor_data) && count($vendor_data) > 0) {
                                        if (
                                            $vendor_data['enable_api_link'] == 1 && $vendor_data['api_base_url'] != ''
                                            && $vendor_data['api_username'] != '' && $vendor_data['api_password'] != ''
                                        ) {
                                            $url = $vendor_data['api_base_url'] . '/docket_api/get_box_label/' . $awb_no . '?cron_company=' . $vendor_data['api_company_id'];
                                            $ch = curl_init();
                                            curl_setopt($ch, CURLOPT_URL,  $url);
                                            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                                            curl_setopt($ch, CURLOPT_POSTFIELDS, array());
                                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                                                'Content-Type: application/json',
                                                'Accept: application/json'
                                            ));
                                            $response_json = curl_exec($ch);
                                            curl_close($ch);

                                            if ($response_json != '') {
                                                $file_name = "vendor_box_label.pdf";
                                                $file_path = create_year_dir('docket_label_print',  $docket_id . '_' . $awb_no) . '/' . $file_name;
                                                $decode_data = base64_decode($response_json);
                                                file_put_contents($file_path, $decode_data);

                                                $update_data = array(
                                                    'media_path' => $file_path
                                                );
                                                $this->gm->update('media_attachment', $update_data, '', array('id' => $value['id']));
                                                echo "<br>QRY=" . $this->db->last_query();
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function update_manifest_docket_tracking()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');

        $query = "SELECT docket_id FROM docket_tracking WHERE `event_desc` LIKE 'SHIPMENT FORWARDED TO DESTINATION HUB' AND  `created_date` > '2022-07-01 00:00:00' AND status != 3";
        $qry_exe = $this->db->query($query);
        $docket_data = $qry_exe->result_array();

        if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
            foreach ($docket_data as $key => $value) {
                $docket_id_array[$value['docket_id']] = $value['docket_id'];
            }
        }

        if (isset($docket_id_array) && is_array($docket_id_array) && count($docket_id_array) > 0) {
            $query1 = "SELECT docket_id,manifest_id FROM manifest_docket WHERE `docket_id` IN(" . implode(",", $docket_id_array) . ") and status != 3";
            $qry_exe1 = $this->db->query($query1);
            $manifest_data = $qry_exe1->result_array();
        }

        if (isset($manifest_data) && is_array($manifest_data) && count($manifest_data) > 0) {
            foreach ($manifest_data as $key => $value) {
                $update_query = "UPDATE `docket_tracking` SET `module_type` = '2',`module_id` = '" . $value['manifest_id'] . "' WHERE `docket_id` = '" . $value['docket_id'] . "' AND `event_desc` LIKE 'SHIPMENT FORWARDED TO DESTINATION HUB' and status != 3;";
                $update_qry_exe = $this->db->query($update_query);
                // $manifest_data = $update_qry_exe->result_array();
                echo "<pre>";
                print_r($value['docket_id']);
                echo "<br>";
            }
        }
    }

    function remove_docket_duplicate($table_name = '')
    {
        $qry = "SELECT COUNT(*) AS `Rows`, `docket_id` FROM `" . $table_name . "` WHERE status=1 GROUP BY `docket_id` HAVING COUNT(*)>1";
        $qry_exe = $this->db->query($qry);
        $result = $qry_exe->result_array();

        if (isset($result) && is_array($result) && count($result) > 0) {
            foreach ($result as $key => $value) {
                $qry = "SELECT id FROM `" . $table_name . "` WHERE status=1 AND docket_id='" . $value['docket_id'] . "'";
                $qry_exe = $this->db->query($qry);
                $duplicate_data = $qry_exe->result_array();
                $delete_ids = array();
                if (count($duplicate_data) > 1) {
                    foreach ($duplicate_data as $dkey => $dvalue) {
                        if ($dkey != 0) {
                            $delete_ids[] = $dvalue['id'];
                        }
                    }
                }


                if (isset($delete_ids) && is_array($delete_ids) && count($delete_ids) > 0) {
                    $qry = "UPDATE " . $table_name . " SET status=3,modified_date='3000-00-00 00:00:00' 
                    WHERE id IN(" . implode(",", $delete_ids) . ")";
                    echo '<br>' . $qry . ";";
                }
            }
        }
    }

    function backup_media()
    {
        // Get real path for our folder
        $rootPath = realpath(FCPATH . 'client_media');

        // Initialize archive object
        $zip = new ZipArchive();
        $zip->open(FCPATH . 'log1/backup_media.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);

        // Create recursive directory iterator
        /** @var SplFileInfo[] $files */
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file) {
            // Skip directories (they would be added automatically)
            if (!$file->isDir()) {
                // Get real and relative path for current file
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);

                // Add current file to archive
                $zip->addFile($filePath, $relativePath);
            }
        }

        // Zip archive will be created only after closing object
        $zip->close();
        $archive_file_name = FCPATH . 'log1/backup_media.zip';
        //then send the headers to force download the zip file
        header("Content-type: application/zip");
        header("Content-Disposition: attachment; filename=$archive_file_name");
        header("Pragma: no-cache");
        header("Expires: 0");
        if (readfile($archive_file_name)) {
            unlink($archive_file_name);
        }
        exit;
    }
    function update_sac_from_service_type()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');
        $remquery = "SELECT id FROM `docket` where vendor_id !=0 AND sac_code='' AND status=1";
        $remquery_exe = $this->db->query($remquery);
        $docket_data_count = $remquery_exe->num_rows();
        echo "remaining data" . $docket_data_count . "<br>";
        $query = "SELECT id, vendor_id FROM `docket` where vendor_id !=0 AND sac_code='' AND status=1 LIMIT 10000";
        $qry_exe = $this->db->query($query);
        $docket_data = $qry_exe->result_array();

        if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
            foreach ($docket_data as $key => $value) {
                if (isset($value["vendor_id"]) && $value["vendor_id"] != NULL && !empty($value["vendor_id"]) && isset($value["id"]) && !empty($value["id"]) && $value["id"] != NULL) {
                    $all_vendor = get_all_vendor(" AND id=" . $value["vendor_id"]);
                    $service_query = "SELECT id,sac_code FROM `service_type` where status=1 AND id=" . $all_vendor[$value["vendor_id"]]["service_type"];
                    $service_query_exe = $this->db->query($service_query);
                    $service_data = $service_query_exe->row_array();
                    if (isset($service_data['sac_code']) && !empty($service_data['sac_code']) && $service_data['sac_code'] != "" && $service_data['sac_code'] != NULL) {
                        $updateqry = "update docket set sac_code=" . $service_data["sac_code"] . " where status=1 AND id=" . $value["id"];
                        $updateqry_exe = $this->db->query($updateqry);
                        //echo $updateqry . "<br>";
                    }
                }
            }
        } else {
            echo "no data found";
        }
        exit;
    }
    function update_duplicate_awb_no()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');
        $remquery = "SELECT COUNT(*) AS `Rows`,TRIM(awb_no) as awb_no FROM `docket` GROUP BY  TRIM(`awb_no`) HAVING COUNT(*)>1";
        $remquery_exe = $this->db->query($remquery);
        $docket_data = $remquery_exe->result_array();

        if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
            foreach ($docket_data as $key => $value) {

                $remquery = "SELECT id,TRIM(awb_no) as awb_no FROM `docket` WHERE awb_no='" . $value['awb_no'] . "'";
                $remquery_exe = $this->db->query($remquery);
                $id_data = $remquery_exe->result_array();

                if (isset($id_data) && is_array($id_data) && count($id_data) > 0) {
                    foreach ($id_data as $ikey => $ivalue) {
                        $duplicate_data[$ivalue['awb_no']][] = $ivalue;
                    }
                }
            }
        }


        if (isset($duplicate_data) && is_array($duplicate_data) && count($duplicate_data) > 0) {
            foreach ($duplicate_data as $dkey => $dvalue) {
                foreach ($dvalue as $dikey => $divalue) {

                    if ($dikey != 0) {
                        $new_awb_no = ' ' . $divalue['awb_no'] . str_repeat(' ', (int) $dikey);
                        $updateq = "UPDATE docket SET awb_no='" . $new_awb_no . "' WHERE id='" . $divalue['id'] . "'";
                        echo "<br>qry-" . $updateq;

                        $this->db->query($updateq);
                    }
                }
            }
        }

        $qry = "ALTER TABLE `docket` ADD UNIQUE(`awb_no`);";
        $this->db->query($qry);


        $duplicate_data = array();
        $remquery = "SELECT COUNT(*) AS `Rows`,TRIM(entry_number) as entry_number FROM `docket_extra_field` GROUP BY  TRIM(`entry_number`) HAVING COUNT(*)>1";
        $remquery_exe = $this->db->query($remquery);
        $docket_data = $remquery_exe->result_array();

        if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
            foreach ($docket_data as $key => $value) {

                $remquery = "SELECT id,TRIM(entry_number) as entry_number FROM `docket_extra_field` WHERE entry_number='" . $value['entry_number'] . "'";
                $remquery_exe = $this->db->query($remquery);
                $id_data = $remquery_exe->result_array();

                if (isset($id_data) && is_array($id_data) && count($id_data) > 0) {
                    foreach ($id_data as $ikey => $ivalue) {
                        $duplicate_data[$ivalue['entry_number']][] = $ivalue;
                    }
                }
            }
        }


        if (isset($duplicate_data) && is_array($duplicate_data) && count($duplicate_data) > 0) {
            foreach ($duplicate_data as $dkey => $dvalue) {
                foreach ($dvalue as $dikey => $divalue) {

                    if ($dikey != 0) {
                        $new_awb_no = ' ' . $divalue['entry_number'] . str_repeat(' ', (int) $dikey);
                        $updateq = "UPDATE docket_extra_field SET entry_number='" . $new_awb_no . "' WHERE id='" . $divalue['id'] . "'";
                        echo "<br>qry-" . $updateq;

                        $this->db->query($updateq);
                    }
                }
            }
        }

        $qry = "ALTER TABLE `docket_extra_field` ADD UNIQUE(`entry_number`);";
        $this->db->query($qry);
    }

    public function update_manifest_docket_billing()
    {
        $remquery = "SELECT id FROM manifest WHERE status=1";
        $remquery_exe = $this->db->query($remquery);
        $docket_data = $remquery_exe->result_array();

        if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
            foreach ($docket_data as $dkey => $dvalue) {
                update_manifest_docket_detail($dvalue['id']);
            }
        }
    }

    public function update_material_content()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');

        $query = "SELECT docket_id,material_id,material_quantity FROM `docket_material` WHERE status=1";
        $qry_exe = $this->db->query($query);
        $docket_data = $qry_exe->result_array();
        $all_material = get_all_material(" AND status IN(1,2) ", "id");
        foreach ($docket_data as $key => $value) {
            $material_name = isset($all_material[$value['material_id']]) ? $all_material[$value['material_id']]['name'] : "";
            $mtax_quantity = isset($value['material_quantity']) ? trim($value['material_quantity']) : 0;
            $material_content[$value['docket_id']][] = $material_name . " - " . $mtax_quantity;
            $final_material_content[$value['docket_id']] = implode(", ", $material_content[$value['docket_id']]);
            $this->gm->update('docket_extra_field', array('material_content' => $final_material_content[$value['docket_id']]), '', array('docket_id' => $value['docket_id']));
            echo "<pre>";
            print_r($value['docket_id']);
            echo "<br> updated successfully";
        }
    }

    public function update_no_of_awb_manifest()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');

        $query = "SELECT manifest_id,COUNT(DISTINCT(docket_id)) as count_awb FROM `manifest_docket` WHERE status=1 GROUP BY manifest_id";
        $qry_exe = $this->db->query($query);
        $docket_data = $qry_exe->result_array();
        foreach ($docket_data as $key => $value) {
            $manifest_id = $value['manifest_id'];
            $total_of_awb = $value['count_awb'];
            $this->gm->update('manifest', array('awb_count' => $total_of_awb), '', array('id' => $manifest_id));
            echo "<pre>";
            print_r($value['manifest_id']);

            echo "<br> updated successfully";
        }
    }


    function update_route_code_name_runsheet()
    {
        $remquery = "SELECT route_id FROM `run_sheet` where status!=3";
        $remquery_exe = $this->db->query($remquery);
        $docket_data = $remquery_exe->result_array();
        $all_route = get_all_route();
        if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
            foreach ($docket_data as $key => $value) {
                if ((isset($value["route_id"]) && $value["route_id"] != NULL
                    && !empty($value["route_id"]) && (isset($all_route[$value["route_id"]])
                        && is_array($all_route[$value["route_id"]]) &&  count($all_route[$value["route_id"]]) > 0))) {
                    $query = "UPDATE `run_sheet` SET `route_name` = '" . $all_route[$value["route_id"]]['name'] . "' WHERE `route_id` =" . $value["route_id"] . ";";
                    $remquery_exe = $this->db->query($query);
                }
            }
        } else {
            echo "no data found";
        }
        exit;
    }

    public function auto_include_credit()
    {
        //auto-include script
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');
        $setting = get_all_app_setting(" AND module_name IN('account')");
        $all_cust_opening = get_all_customer_opening_balance_date();
        //GET ALL RECEIPT
        $query = "SELECT * FROM payment_receipt WHERE status=1 AND leftover_amt>0";

        if (
            isset($setting['customised_unbilled_dockets_in_leder_and_outstanding_report']) && $setting['customised_unbilled_dockets_in_leder_and_outstanding_report'] == 1
            && isset($setting['account_ledger_start_date']) && $setting['account_ledger_start_date'] != '' && $setting['account_ledger_start_date'] != '1970-01-01' && $setting['account_ledger_start_date'] != '0000-00-00'
        ) {
            $query .= " AND receipt_date>='" . $setting['account_ledger_start_date'] . "'";
        }

        $qry_exe = $this->db->query($query);
        $receipt_res = $qry_exe->result_array();
        $this->load->module('account');
        if (isset($receipt_res) && is_array($receipt_res) && count($receipt_res) > 0) {
            foreach ($receipt_res as $rkey => $receipt_data) {

                if ($receipt_data['customer_id'] > 0) {
                    if (
                        isset($data['setting']['customised_unbilled_dockets_in_leder_and_outstanding_report']) && $data['setting']['customised_unbilled_dockets_in_leder_and_outstanding_report'] == 3
                    ) {
                        $account_ledger_start_date = isset($all_cust_opening[$receipt_data['customer_id']]) ? $all_cust_opening[$receipt_data['customer_id']] : 0;
                        if ($account_ledger_start_date != '' && $receipt_data['receipt_date'] < $account_ledger_start_date) {
                            continue;
                        }
                    }
                }

                if ($receipt_data['leftover_amt'] > 0) {
                    $receipt_id = $receipt_data['id'];
                    $include_data = $this->account->include_invoice('receipt', $receipt_id, 1);
                    $received_amt = isset($include_data['received_amt']) ? $include_data['received_amt'] : array();
                    $invoice_data = isset($include_data['invoice_data']) ? $include_data['invoice_data'] : array();
                    if (isset($invoice_data) && is_array($invoice_data) && count($invoice_data) > 0) {
                        foreach ($invoice_data as $key => $value) {
                            $received_till_date = isset($received_amt[$value['invoice_type']][$value['id']]) ? $received_amt[$value['invoice_type']][$value['id']]['received'] : 0;
                            $grand_total_amt = $value['grand_total'];
                            $outstanding_amt = $grand_total_amt -  $received_till_date;
                            if ($outstanding_amt > 0) {
                                $leftover_data = $this->gm->get_selected_record('payment_receipt', 'id,leftover_amt', $where = array('id' => $receipt_id, 'status!=' => 3), array());
                                if (isset($leftover_data['leftover_amt']) && $leftover_data['leftover_amt'] > 0) {
                                    $include_post_data = array(
                                        'credit_id' => $receipt_id,
                                        'credit_id_type' => 1, //RECEIPT
                                        'invoice_id' => $value['id'],
                                        'invoice_type' => $value['invoice_type'],
                                        'grand_total' => $value['grand_total'],
                                        'deduction_amt' => 0,
                                        'tds_amt' => 0,
                                        'received_amt' => $outstanding_amt > $leftover_data['leftover_amt'] ? $leftover_data['leftover_amt'] : $outstanding_amt,
                                        'round_off_amt' => 0,
                                        'reference' => '',
                                        'outstanding_amt' => $outstanding_amt
                                    );
                                    $this->account->save_include_invoice($include_post_data);
                                } else {
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        }




        //GET ALL CREDIT NOTE
        $query = "SELECT * FROM credit_debit_note WHERE status=1 AND leftover_amt>0 AND note_category=1";
        if (
            isset($setting['customised_unbilled_dockets_in_leder_and_outstanding_report']) && $setting['customised_unbilled_dockets_in_leder_and_outstanding_report'] == 1
            && isset($setting['account_ledger_start_date']) && $setting['account_ledger_start_date'] != '' && $setting['account_ledger_start_date'] != '1970-01-01' && $setting['account_ledger_start_date'] != '0000-00-00'
        ) {
            $query .= " AND note_date >='" . $setting['account_ledger_start_date'] . "'";
        }
        $qry_exe = $this->db->query($query);
        $note_res = $qry_exe->result_array();


        if (isset($note_res) && is_array($note_res) && count($note_res) > 0) {
            foreach ($note_res as $rkey => $receipt_data) {
                if ($receipt_data['customer_id'] > 0) {
                    if (
                        isset($data['setting']['customised_unbilled_dockets_in_leder_and_outstanding_report']) && $data['setting']['customised_unbilled_dockets_in_leder_and_outstanding_report'] == 3
                    ) {
                        $account_ledger_start_date = isset($all_cust_opening[$receipt_data['customer_id']]) ? $all_cust_opening[$receipt_data['customer_id']] : 0;
                        if ($account_ledger_start_date != '' && $receipt_data['note_date'] < $account_ledger_start_date) {
                            continue;
                        }
                    }
                }

                if ($receipt_data['leftover_amt'] > 0) {
                    $receipt_id = $receipt_data['id'];
                    $include_data = $this->account->include_invoice('note', $receipt_id, 1);
                    $received_amt = isset($include_data['received_amt']) ? $include_data['received_amt'] : array();
                    $invoice_data = isset($include_data['invoice_data']) ? $include_data['invoice_data'] : array();
                    if (isset($invoice_data) && is_array($invoice_data) && count($invoice_data) > 0) {
                        foreach ($invoice_data as $key => $value) {
                            $received_till_date = isset($received_amt[$value['invoice_type']][$value['id']]) ? $received_amt[$value['invoice_type']][$value['id']]['received'] : 0;
                            $grand_total_amt = $value['grand_total'];
                            $outstanding_amt = $grand_total_amt -  $received_till_date;
                            if ($outstanding_amt > 0) {
                                $leftover_data = $this->gm->get_selected_record('credit_debit_note', 'id,leftover_amt', $where = array('id' => $receipt_id, 'status!=' => 3), array());
                                if (isset($leftover_data['leftover_amt']) && $leftover_data['leftover_amt'] > 0) {
                                    $include_post_data = array(
                                        'credit_id' => $receipt_id,
                                        'credit_id_type' => 2, //CREDIT NOTE
                                        'invoice_id' => $value['id'],
                                        'invoice_type' => $value['invoice_type'],
                                        'grand_total' => $value['grand_total'],
                                        'deduction_amt' => 0,
                                        'tds_amt' => 0,
                                        'received_amt' => $outstanding_amt > $leftover_data['leftover_amt'] ? $leftover_data['leftover_amt'] : $outstanding_amt,
                                        'round_off_amt' => 0,
                                        'reference' => '',
                                        'outstanding_amt' => $outstanding_amt
                                    );
                                    $this->account->save_include_invoice($include_post_data);
                                } else {
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        }


        //GET ALL CREDIT OPENING BALANCE
        $query = "SELECT * FROM opening_balance WHERE status=1 AND leftover_amt>0 AND payment_type=1";
        if (
            isset($setting['customised_unbilled_dockets_in_leder_and_outstanding_report']) && $setting['customised_unbilled_dockets_in_leder_and_outstanding_report'] == 1
            && isset($setting['account_ledger_start_date']) && $setting['account_ledger_start_date'] != '' && $setting['account_ledger_start_date'] != '1970-01-01' && $setting['account_ledger_start_date'] != '0000-00-00'
        ) {
            $query .= " AND opening_date >='" . $setting['account_ledger_start_date'] . "'";
        }
        $qry_exe = $this->db->query($query);
        $opening_res = $qry_exe->result_array();

        if (isset($opening_res) && is_array($opening_res) && count($opening_res) > 0) {
            foreach ($opening_res as $rkey => $receipt_data) {
                if ($receipt_data['customer_id'] > 0) {
                    if (
                        isset($data['setting']['customised_unbilled_dockets_in_leder_and_outstanding_report']) && $data['setting']['customised_unbilled_dockets_in_leder_and_outstanding_report'] == 3
                    ) {
                        $account_ledger_start_date = isset($all_cust_opening[$receipt_data['customer_id']]) ? $all_cust_opening[$receipt_data['customer_id']] : 0;
                        if ($account_ledger_start_date != '' && $receipt_data['opening_date'] < $account_ledger_start_date) {
                            continue;
                        }
                    }
                }

                if ($receipt_data['leftover_amt'] > 0) {
                    $receipt_id = $receipt_data['id'];
                    $include_data = $this->account->include_invoice('opening', $receipt_id, 1);
                    $received_amt = isset($include_data['received_amt']) ? $include_data['received_amt'] : array();
                    $invoice_data = isset($include_data['invoice_data']) ? $include_data['invoice_data'] : array();
                    if (isset($invoice_data) && is_array($invoice_data) && count($invoice_data) > 0) {
                        foreach ($invoice_data as $key => $value) {
                            $received_till_date = isset($received_amt[$value['invoice_type']][$value['id']]) ? $received_amt[$value['invoice_type']][$value['id']]['received'] : 0;
                            $grand_total_amt = $value['grand_total'];
                            $outstanding_amt = $grand_total_amt -  $received_till_date;
                            if ($outstanding_amt > 0) {
                                $leftover_data = $this->gm->get_selected_record('opening_balance', 'id,leftover_amt', $where = array('id' => $receipt_id, 'status!=' => 3), array());
                                if (isset($leftover_data['leftover_amt']) && $leftover_data['leftover_amt'] > 0) {
                                    $include_post_data = array(
                                        'credit_id' => $receipt_id,
                                        'credit_id_type' => 3, //CREDIT OPENING
                                        'invoice_id' => $value['id'],
                                        'invoice_type' => $value['invoice_type'],
                                        'grand_total' => $value['grand_total'],
                                        'deduction_amt' => 0,
                                        'tds_amt' => 0,
                                        'received_amt' => $outstanding_amt > $leftover_data['leftover_amt'] ? $leftover_data['leftover_amt'] : $outstanding_amt,
                                        'round_off_amt' => 0,
                                        'reference' => '',
                                        'outstanding_amt' => $outstanding_amt
                                    );
                                    $this->account->save_include_invoice($include_post_data);
                                } else {
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        }

        echo "AUTO INCLUDE DONE";
    }

    public function update_connection_date()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');
        $connection_message = array(
            "tdg_1" => "Item has arrived at the The Delivery Group depot",
            "tdg_2" => "Corporate Courier is preparing your item for The Delivery Group to handle",
            "tdg_3" => "The Delivery Group has advised the item to",
            "DHL" => "Shipment picked up",
            "FEDEX" => "Shipment information sent to FedEx",
            "UPS" => "Origin Scan",
            "DPD" => "Confirmed at Hub",
            "FDL" => "The order was received in the sortation depot",
            "DTDC INTL" => "Confirmed at Hub",
            "CANADA_POST" => "RECEIVED"
        );
        foreach ($connection_message as $cmkey => $cmvalue) {
            $query = "SELECT event_desc,event_date_time,docket_id FROM `docket_tracking` WHERE status=1 and (event_desc = '" . $cmvalue . "' OR `event_desc` LIKE '%" . $cmvalue . "%') and event_add_type = 1";
            $qry_exe = $this->db->query($query);
            $docket_data = $qry_exe->result_array();
            foreach ($docket_data as $key => $value) {
                $docket_id = $value['docket_id'];
                $connection_date = isset($value['event_date_time']) && $value['event_date_time'] != '' ? date('Y-m-d', strtotime($value['event_date_time'])) : '';;
                $connection_time = isset($value['event_date_time']) && $value['event_date_time'] != '' ? date('H:i:s', strtotime($value['event_date_time'])) : '';;

                $this->gm->update('docket_delivery', array('connection_date' => $connection_date, 'connection_time' => $connection_time), '', array('docket_id' => $docket_id));
                echo "<pre>";
                print_r($value['docket_id']);
                echo "<br> updated successfully";
            }
        }
    }

    public function update_awb_total_qty()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');

        $query = "SELECT SUM(material_quantity) as quantity,docket_id FROM `docket_material` WHERE status = 1 AND material_quantity != 0 GROUP BY docket_id";
        $qry_exe = $this->db->query($query);
        $docket_data = $qry_exe->result_array();
        foreach ($docket_data as $key => $value) {
            $this->gm->update('docket_extra_field', array('total_quantity' => $value['quantity']), '', array('docket_id' => $value['docket_id']));
            echo "<pre>";
            print_r($value['docket_id']);
            echo "<br> updated successfully";
        }
    }

    public function delete_opening_balance()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');

        $query = "SELECT id FROM `opening_balance` WHERE status=1";
        $qry_exe = $this->db->query($query);
        $opening_data = $qry_exe->result_array();

        if (isset($opening_data) && is_array($opening_data) && count($opening_data) > 0) {
            foreach ($opening_data as $key => $value) {
                $opening_id = $value['id'];

                $Update_ledger_query = "UPDATE ledger_item  SET `status` = '3' WHERE `payment_id` = '" . $opening_id . "' AND payment_type = '1' AND status != 3;";
                $update_qry_exe = $this->db->query($Update_ledger_query);

                $Update_ledger_out_query = "UPDATE ledger_outstanding_item  SET `status` = '3' WHERE `payment_id` = '" . $opening_id . "' AND payment_type = '1' AND status != 3;";
                $update_qry_exe = $this->db->query($Update_ledger_out_query);

                $opening_query = "UPDATE opening_balance  SET `status` = '3' WHERE `id` = '" . $opening_id . "' AND status != 3;";
                $opening_qry_exe = $this->db->query($opening_query);

                echo "<pre>";
                echo "<br> Updated successfully";
            }
        }
    }

    public function delete_all_include()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');
        $this->load->module('account');

        $query = "SELECT o.id FROM `payment_receipt` o WHERE o.status='1'";
        $query_exec = $this->db->query($query);
        $reciept_result = $query_exec->result_array();

        if (isset($reciept_result) && is_array($reciept_result) && count($reciept_result) > 0) {
            foreach ($reciept_result as $rec_key => $rec_value) {
                if ($rec_value['id'] != "") {
                    $data =  $this->account->delete_include($rec_value['id'], 1, "", 1);
                }
            }
        }

        $open_query = "SELECT o.* FROM `opening_balance` o WHERE o.status='1' AND o.payment_type = 1";
        $open_query_exec = $this->db->query($open_query);
        $open_result = $open_query_exec->result_array();

        if (isset($open_result) && is_array($open_result) && count($open_result) > 0) {
            foreach ($open_result as $open_key => $open_value) {
                if ($open_value['id'] != "") {
                    $data = $this->account->delete_include($open_value['id'], 3, "", 1);
                }
            }
        }

        $credit_query = "SELECT o.* FROM `credit_debit_note` o WHERE o.status='1' AND o.note_category = 1";
        $credit_query_exec = $this->db->query($credit_query);
        $credit_result = $credit_query_exec->result_array();

        if (isset($credit_result) && is_array($credit_result) && count($credit_result) > 0) {
            foreach ($credit_result as $credit_key => $credit_value) {
                if ($credit_value['id'] != "") {
                    $data = $this->account->delete_include($credit_value['id'], 2, "", 1);
                }
            }
        }
    }


    public function update_docket_total_charge()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');

        $setting = get_all_app_setting(" AND module_name IN('docket')");

        $query = "SELECT id,awb_no,cod_amount FROM `docket` WHERE status IN (1,2)";
        $qry_exe = $this->db->query($query);
        $docket_data = $qry_exe->result_array();
        foreach ($docket_data as $key => $value) {

            $query = "SELECT vat,admin_charge,extra_charge,total_charges FROM `docket_extra_field` WHERE docket_id = " . $value['id'] . " AND status IN (1,2)";
            $qry_exe = $this->db->query($query);
            $charge_data = $qry_exe->result_array();

            $vat = isset($charge_data[0]['vat']) && $charge_data[0]['vat'] != "" ? $charge_data[0]['vat'] : 0;
            $extra_charge = isset($charge_data[0]['extra_charge']) && $charge_data[0]['extra_charge'] != "" ? $charge_data[0]['extra_charge'] : 0;
            $cod_amount = isset($value['cod_amount'])  && $value['cod_amount'] != ""  && $value['cod_amount'] != 0 ? $value['cod_amount'] : 0;
            if (isset($setting['set_default_admin_charge']) && $setting['set_default_admin_charge'] != 0 && $setting['set_default_admin_charge'] != "") {
                $admin_charge = $setting['set_default_admin_charge'];
            } else if (isset($charge_data[0]['admin_charge']) && $charge_data[0]['admin_charge'] != "" && $charge_data[0]['admin_charge'] != 0) {
                $admin_charge = $charge_data[0]['admin_charge'];
            } else {
                $admin_charge = 0;
            }

            $inser_data['admin_charge'] = $admin_charge;
            $inser_data['total_charges'] = $vat + $extra_charge + $admin_charge + $cod_amount;

            $this->gm->update('docket_extra_field', $inser_data, '', array('docket_id' => $value['id']));

            echo "<pre>";
            echo $value['awb_no'] . " updated successfully <br>";
            echo "<br>";
        }
    }

    function set_profitability_report_permission()
    {
        $this->load->helper('url');
        $this->load->helper('report');
        $this->load->model('Global_model', 'gm');
        $CI = &get_instance();
        $main_db = $CI->load->database('main_db', true);
        $qry = "SELECT user_id,permission_id FROM `user_permission_map` WHERE permission_id = 66 and status = 1;";
        $qry_exe = $main_db->query($qry);
        $result = $qry_exe->result_array();
        if (isset($result) && is_array($result) && count($result) > 0) {
            foreach ($result as $key => $value) {
                if ($value['user_id'] != "") {
                    $user_id = $value['user_id'];
                    $qry = "INSERT INTO `user_permission_map` (`id`, `permission_type`, `status`, `user_id`, `permission_id`, `created_date`, `created_by`, `modified_date`, `modified_by`) 
                                VALUES (NULL, 'Special', '1', $user_id , '340', '2022-09-02 16:25:06.000000', '', '2022-09-02 16:25:06.000000', '');";
                    $qry_exe = $main_db->query($qry);
                }
                echo $user_id . "<br>";
            }
        }
    }
    public function check_fsc_in_all_customer()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');

        //UPDATE 1 FOR EVERY CUSTOMER PRESENT
        $upquery1 = " UPDATE `module_setting` SET `config_value` = '1' WHERE `module_type` = 1 AND `config_key` LIKE 'is_fsc_apply'";
        $upqry_exe1 = $this->db->query($upquery1);

        $query1 = "SELECT module_id FROM `module_setting` WHERE `module_type` = 1 AND `config_key` LIKE 'is_fsc_apply'";
        $qry_exe1 = $this->db->query($query1);
        $docket_data1 = $qry_exe1->result_array();
        $cust_module_id = array();
        if (isset($docket_data1) && is_array($docket_data1) && count($docket_data1) > 0) {
            foreach ($docket_data1 as $key1 => $value1) {
                $cust_module_id[] = $value1['module_id'];
            }
        }

        if (isset($cust_module_id) && is_array($cust_module_id) && count($cust_module_id) > 0) {
            $query = "SELECT id FROM `customer` WHERE status IN (1,2) AND id  NOT IN(" . implode(",", $cust_module_id) . ")";
        } else {
            $query = "SELECT id FROM `customer` WHERE status IN (1,2)";
        }

        $qry_exe = $this->db->query($query);
        $docket_data = $qry_exe->result_array();

        if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
            foreach ($docket_data as $key => $value) {
                $cust_id = $value['id'];
                $query = "INSERT INTO `module_setting` (`status`, `module_id`, `module_type`, `config_key`, `config_value`) VALUES
                            (1, $cust_id, 1, 'is_fsc_apply', '1');";
                $qry_exe = $this->db->query($query);
                echo "<pre>";
                echo $value['id'] . " updated successfully <br>";
                echo "<br>";
            }
        }
    }


    public function send_wt_change_email()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');
        $this->load->helper('upload');
        $this->load->helper('docket_email');
        $this->load->helper('email');

        $this->load->helper('sms');
        $this->load->helper('whatsapp');
        $this->load->helper('message');
        $query = "SELECT id,old_chargeable_wt,chargeable_wt FROM `docket` WHERE DATE_FORMAT(`wt_change_datetime`,'%Y-%m-%d') >= '2022-11-21' AND DATE_FORMAT(`wt_change_datetime`,'%Y-%m-%d')<= '2022-11-22'
                AND status=1";
        $qry_exe = $this->db->query($query);
        $docket_data = $qry_exe->result_array();
        foreach ($docket_data as $key => $value) {
            $docket_id = $value['id'];
            send_wt_change_email($docket_id, array('old_wt' => $value['old_chargeable_wt']));
            echo "<br>Mail send for docket id=" . $docket_id;
        }
    }

    function update_shipper()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');
        $query = "SELECT sh.code as shipper_code,sh.* FROM docket_shipper sh 
        JOIN docket d ON(d.id=sh.docket_id) 
        WHERE d.status=1 AND sh.status=1 AND d.migration_id>0 AND sh.shipper_id=0 AND sh.code!=''";
        $qry_exe = $this->db->query($query);
        $docket_data = $qry_exe->result_array();
        $all_shipper = get_all_shipper(" AND status IN(1,2) ", "code");

        if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
            foreach ($docket_data as $key => $value) {
                $shipper_id =  isset($value['shipper_code']) && $value['shipper_code'] != '' && isset($all_shipper[strtolower(trim($value['shipper_code']))]) ? $all_shipper[strtolower(trim($value['shipper_code']))]['id'] : 0;
                if ($shipper_id > 0) {
                    $update_q = "UPDATE docket_shipper SET shipper_id='" . $shipper_id . "' WHERE id='" . $value['id'] . "'";

                    $this->db->query($update_q);
                } else {
                    echo "<br>NOT FOUND" . $value['shipper_code'];
                }
            }
        }
    }
    function update_shipper_code()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');
        $query = "SELECT *  FROM `docket_shipper` WHERE `status` = 1 AND `code` = 'Array' AND `shipper_id` > 0";
        $qry_exe = $this->db->query($query);
        $docket_data = $qry_exe->result_array();
        $all_shipper = get_all_shipper(" AND status IN(1,2) ", "id");

        if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
            foreach ($docket_data as $key => $value) {
                $shipper_code =  $all_shipper[$value['shipper_id']]['code'];
                if ($shipper_code != '' && isset($all_shipper[$value['shipper_id']])) {
                    $update_q = "UPDATE docket_shipper SET code='" . $shipper_code . "' WHERE id='" . $value['id'] . "'";

                    $this->db->query($update_q);
                } else {
                    echo "<br>NOT FOUND" . $value['shipper_code'];
                }
            }
        }
    }

    function update_mig_shipper()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');
        $query = "SELECT d.migration_id,sh.code as shipper_code,sh.* FROM docket_shipper sh 
        JOIN docket d ON(d.id=sh.docket_id) WHERE d.status=1 AND sh.status=1 AND d.migration_id>0 
        AND sh.shipper_id=0 AND sh.code='' AND d.booking_date>='2022-10-01' 
        AND d.booking_date<='2022-11-31'";
        $qry_exe = $this->db->query($query);
        $docket_data = $qry_exe->result_array();
        $all_shipper = get_all_shipper(" AND status IN(1,2) ", "code");

        if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
            foreach ($docket_data as $key => $value) {
                $id_arr[] = $value['migration_id'];
            }
        }

        if (isset($id_arr) && is_array($id_arr) && count($id_arr) > 0) {
            $url = "http://old.universalcourier.in/api/v5/dockets/fetch_dockets";
            $request_json['ids'] = $id_arr;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request_json));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Accept: application/json'
            ));
            $response_json = curl_exec($ch);


            curl_close($ch);

            $response_json =  str_replace(array("'"), "", $response_json);
            $result = json_decode($response_json, true);

            if (isset($result) && is_array($result) && count($result) > 0) {
                if (isset($result['data']) && is_array($result['data']) && count($result['data']) > 0) {

                    $keys = array_column($result['data'], 'id');
                    array_multisort($keys, SORT_ASC, $result['data']);

                    foreach ($result['data'] as $key => $value) {
                        $qry = "SELECT id,awb_no FROM docket WHERE status IN(1,2) AND migration_id='" . $value['id'] . "'";
                        $qry_exe = $this->db->query($qry);
                        $existData = $qry_exe->row_array();

                        if (isset($existData) && is_array($existData) && count($existData) > 0) {
                            $shipper_id =  isset($value['shipper_code']) && $value['shipper_code'] != '' && isset($all_shipper[strtolower(trim($value['shipper_code']))]) ? $all_shipper[strtolower(trim($value['shipper_code']))]['id'] : 0;
                            if ($shipper_id > 0) {
                                $update_q = "UPDATE docket_shipper SET shipper_id='" . $shipper_id . "' WHERE docket_id='" . $existData['id'] . "'";
                                echo $update_q;
                                exit;
                                $this->db->query($update_q);
                            } else {
                                echo "<br>NOT FOUND" . $value['id'];
                            }
                        } else {
                            echo "<br>NOT EXIST" . $value['id'];
                        }
                    }
                }
            }
        }
    }

    function update_address_needed()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');
        $query = "SELECT id,docket_id,tracking_type,event_desc FROM docket_tracking WHERE status=1 AND (LOWER(event_desc) LIKE '%address information needed%' OR LOWER(event_desc) LIKE '%further consignee information needed%')";
        $qry_exe = $this->db->query($query);
        $docket_data = $qry_exe->result_array();
        if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
            foreach ($docket_data as $key => $value) {
                $this->gm->update('docket', array('address_info_needed' => 1), '', array('id' => $value['docket_id']));
            }
        }
    }


    function update_weight_bag()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');
        $query = "SELECT * FROM `weight_bag` WHERE `status` = 1 AND `bag_no` = '' GROUP BY manifest_id";
        $qry_exe = $this->db->query($query);
        $manifest_data = $qry_exe->result_array();
        if (isset($manifest_data) && is_array($manifest_data) && count($manifest_data) > 0) {
            foreach ($manifest_data as $key => $value) {
                $query = "SELECT * FROM `weight_bag` WHERE `status` = 1 AND `manifest_id` = " . $value['manifest_id'];
                $qry_exe = $this->db->query($query);
                $wt_data = $qry_exe->result_array();
                if (isset($wt_data) && is_array($wt_data) && count($wt_data) > 0) {
                    $bag_no = 1;


                    foreach ($wt_data as $wkey => $wvalue) {
                        $format_no = sprintf("%02d", $bag_no);
                        $updateq = "UPDATE weight_bag SET bag_no='" . $format_no . "' WHERE id=" . $wvalue['id'];
                        echo "<br>" . $updateq . ";";
                        $bag_no++;
                    }
                }
            }
        }
    }



    function update_duplicate_weight_bag()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');
        $query = "SELECT COUNT(*),manifest_id,bag_no  FROM `weight_bag` WHERE `status` = 1 GROUP BY manifest_id,bag_no HAVING COUNT(*) >1";
        $qry_exe = $this->db->query($query);
        $manifest_data = $qry_exe->result_array();
        if (isset($manifest_data) && is_array($manifest_data) && count($manifest_data) > 0) {
            foreach ($manifest_data as $key => $value) {
                $query = "SELECT * FROM `weight_bag` WHERE `status` = 1 AND `manifest_id` = " . $value['manifest_id'] . " AND bag_no='" . $value['bag_no'] . "' ORDER BY id DESC";
                $qry_exe = $this->db->query($query);
                $wt_data = $qry_exe->result_array();

                $deleted_ids = array();
                if (isset($wt_data) && is_array($wt_data) && count($wt_data) > 1) {
                    foreach ($wt_data as $wkey => $wvalue) {
                        if ($wkey > 0) {
                            $deleted_ids[] = $wvalue['id'];
                        }
                    }

                    if (isset($deleted_ids) && is_array($deleted_ids) && count($deleted_ids) > 0) {

                        $updateq = "UPDATE weight_bag SET status=4 WHERE id IN(" . implode(",", $deleted_ids) . ")";
                        echo "<br>" . $updateq . ";";
                    }
                }
            }
        }
    }

    function download_data()
    {
        $url = "https://www.movin.in/shipment/api/label/thermal/a7395a019401aeb966ffbfd6bd7fc0f8";
        $file_path = FCPATH . "log1/kanchan1.pdf";
        // file_put_contents($file_path, @file_get_contents($url));
        // exit;

        $url = "https://www.movin.in/shipment/api/label/thermal/a7395a019401aeb966ffbfd6bd7fc0f8";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,  $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_POSTFIELDS, '');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        file_put_contents($file_path, $response);
        echo $response;
        exit;
        //$response = @file_get_contents('fedex_dxb.txt');
        $response_result = json_decode($response, TRUE);
        curl_close($ch);
    }

    function update_invoice_shipment()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');
        $query = "SELECT SUM(invoice_amount) as shipment_value,docket_id  FROM `docket_entry_invoice` WHERE `status` = 1 GROUP BY docket_id";
        $qry_exe = $this->db->query($query);
        $invoice_data = $qry_exe->result_array();
        if (isset($invoice_data) && is_array($invoice_data) && count($invoice_data) > 0) {
            foreach ($invoice_data as $key => $value) {
                $this->gm->update('docket', array('shipment_value' => $value['shipment_value']), '', array('id' => $value['docket_id']));
            }
        }
    }


    function check_db_table_col()
    {
        $this->load->helper('url');
        $this->load->helper('database_manage');
        $this->load->helper('create_table');
        $this->load->helper('frontend_common');
        $session_data = $this->session->userdata('admin_user');
        //check database and all table for login user company present or not

        $all_company = get_all_company();
        if (isset($all_company) && is_array($all_company) && count($all_company) > 0) {
            foreach ($all_company as $key => $value) {
                $_GET['cron_company'] = $value['id'];
                $this->add_company_db_structure($value['id']);

                echo "<br>COMPANY DONE=" . $value['id'];
            }
        }
    }
    function add_company_db_structure($company_id = 0)
    {
        if ($company_id > 1) {

            $this->load->database();
            $this->load->dbutil();
            $company_db_name = "garment_track_company_" . $company_id;

            if ($this->dbutil->database_exists($company_db_name)) {

                $_SESSION['current_db'] = $company_id;

                create_all_table();

                //RUN TABLE COLUMN MIGRATION SCRIPT
                $this->load->module('database_migration/column_script');
                $this->column_script->check_table_structure();

                $this->load->module('database_migration/optimize_table');
                $this->optimize_table->run_qry();
            }
        }
    }

    function add_new_column()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');

        $query = "SHOW DATABASES";
        $query_exe = $this->db->query($query);
        $data = $query_exe->result_array();

        foreach ($data as $key => $value) {
            if (strpos(strtolower($value['Database']), 'garment_track_company_') !== false) {
                $databases[] = $value['Database'];
            }
        }

        // STEPS:
        // 1: CHANGE THE TABLE NAME IN SHOW TABLE QUERY
        // 2: ADD YOUR QUERY IN COLUMN QUERY 
        foreach ($databases as $key => $value) {
            $conn = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, $value);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            //CHECK IF TABLE EXISTS OR NOT
            $table_exist_query = "SHOW TABLES LIKE 'docket'";
            $table_result = $conn->query($table_exist_query);
            if ($table_result->num_rows > 0) {
                $column_qury = "ALTER TABLE `docket` ADD `customer_edd_cross` int(11) NOT NULL,
                ADD `vendor_edd_cross` int(11) NOT NULL;";
                $result = $conn->query($column_qury);

                echo $value . " inserted successfully<br>";
            }
        }

        //FOR FEDEX-IMPORT add create_pickup in docket_service_field
    }

    function more_include()
    {
        //SELECT (IFNULL(din.grand_total, 0)+IFNULL(dno.grand_total_amount, 0)+IFNULL(ob.opening_amount, 0)) as in_amount,i.received_amt, i.*
        // FROM docket_include_data i 
        // LEFT OUTER JOIN docket_invoice din ON(din.id=i.invoice_id AND i.invoice_type=3 AND din.status IN(1,2))
        // LEFT OUTER JOIN credit_debit_note dno ON(dno.id=i.invoice_id AND i.invoice_type=2 AND dno.status IN(1,2))
        // LEFT OUTER JOIN opening_balance ob ON(ob.id=i.invoice_id AND i.invoice_type=1 AND ob.status IN(1,2))
        // WHERE i.status =1 AND i.received_amt>(IFNULL(din.grand_total, 0)+IFNULL(dno.grand_total_amount, 0)+IFNULL(ob.opening_amount, 0))
    }
    function dtd_fsc_gst_setting()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');
        $query = "SELECT id  FROM `customer` WHERE `status` = 1";
        $qry_exe = $this->db->query($query);
        $customer_data = $qry_exe->result_array();

        if (isset($customer_data) && is_array($customer_data) && count($customer_data) > 0) {
            foreach ($customer_data as $key => $value) {
                $module_data = $this->gm->get_selected_record('module_setting', 'id', $where = array('module_type' => 1, 'module_id' => $value["id"], "config_key" => "is_gst_apply"), array(), array('status' => array(1, 2))); //is_fsc_apply
                if (isset($module_data) && is_array($module_data) && count($module_data) > 1) {
                    echo "already exist<br>";
                } else {
                    $setting_insert = array(
                        'module_id' => $value["id"],
                        'module_type' => 1,
                        'config_key' => "is_gst_apply",
                        'config_value' => 1
                    );
                    $setting_insert['created_date'] = date('Y-m-d H:i:s');
                    $setting_insert['created_by'] = $this->user_id;
                    $this->gm->insert('module_setting', $setting_insert);
                    print_r($this->db->last_query());
                    echo "<br>";
                }
            }
        }
    }


    function check_cancel_invoice()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');

        $query = "SHOW DATABASES";
        $query_exe = $this->db->query($query);
        $data = $query_exe->result_array();

        foreach ($data as $key => $value) {
            if (strpos(strtolower($value['Database']), 'garment_track_company_') !== false) {
                $databases[] = $value['Database'];
            }
        }

        // STEPS:
        // 1: CHANGE THE TABLE NAME IN SHOW TABLE QUERY
        // 2: ADD YOUR QUERY IN COLUMN QUERY 
        foreach ($databases as $key => $value) {
            $conn = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, $value);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            //CHECK IF TABLE EXISTS OR NOT
            $table_exist_query = "SHOW TABLES LIKE 'docket_invoice'";
            $table_result = $conn->query($table_exist_query);
            if ($table_result->num_rows > 0) {
                $qry = "SELECT * FROM `docket_invoice` WHERE status=1 AND is_cancelled=1";
                $qry_exe = $conn->query($qry);
                if ($qry_exe->num_rows > 0) {
                    echo "<br>COMPANY=" . $value . "====COUNT====" . $qry_exe->num_rows;
                }
            }
        }

        //FOR FEDEX-IMPORT add create_pickup in docket_service_field
    }
    function add_new_entry()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');

        $query = "SHOW DATABASES";
        $query_exe = $this->db->query($query);
        $data = $query_exe->result_array();

        foreach ($data as $key => $value) {
            if (strpos(strtolower($value['Database']), 'garment_track_company_') !== false) {
                $databases[] = $value['Database'];
            }
        }

        // STEPS:
        // 1: CHANGE THE TABLE NAME IN SHOW TABLE QUERY
        // 2: ADD YOUR QUERY IN COLUMN QUERY 
        foreach ($databases as $key => $value) {
            $conn = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, $value);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            //CHECK IF TABLE EXISTS OR NOT
            $table_exist_query = "SHOW TABLES LIKE 'custom_validation_field'";
            $table_result = $conn->query($table_exist_query);
            if ($table_result->num_rows > 0) {


                $check_query = "SELECT * FROM `custom_validation_field` WHERE `module_id` = 5 AND status = 1;";
                $checke_result = $conn->query($check_query);
                $check_array[] = $checke_result;
                if ($checke_result->num_rows == 0) {

                    $insert_qry = "INSERT INTO `custom_validation_field` (`id`, `status`, `module_id`, `label_key`, `created_date`, `created_by`, `modified_date`, `modified_by`, `validation_type`, `validation_user`) VALUES
                    (NULL, 1, 5, 'ref_no', '2023-02-03 12:45:22', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'customer_id', '2023-02-03 12:45:22', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'vendor_id', '2023-02-03 12:45:22', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'pickup_dispatch_type', '2023-02-03 12:45:22', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'call_date', '2023-02-03 12:45:22', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'call_time', '2023-02-03 12:45:22', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'co_vendor_id', '2023-02-03 12:45:23', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'contact_person_no', '2023-02-03 12:45:23', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'contact_person_name', '2023-02-03 12:45:23', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'pickup_cancel', '2023-02-03 12:45:23', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'pickup_date', '2023-02-03 12:45:23', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'pickup_time', '2023-02-03 12:45:23', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'pickup_priority', '2023-02-03 12:45:23', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'commit_id', '2023-02-03 12:45:23', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'pieces', '2023-02-03 12:45:23', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'weight', '2023-02-03 12:45:23', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'remark', '2023-02-03 12:45:23', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'user_id', '2023-02-03 12:45:23', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'total_pieces', '2023-02-03 12:45:23', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'total_awb', '2023-02-03 12:45:23', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'po_number', '2023-02-03 12:45:23', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'estimate_no', '2023-02-03 12:45:23', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'pickup_code', '2023-02-03 12:45:23', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'pickup_name', '2023-02-03 12:45:23', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'pickup_company', '2023-02-03 12:45:23', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'pickup_address1', '2023-02-03 12:45:23', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'pickup_address2', '2023-02-03 12:45:23', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'pickup_address3', '2023-02-03 12:45:23', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'pickup_pincode', '2023-02-03 12:45:23', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'pickup_city', '2023-02-03 12:45:23', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'pickup_state', '2023-02-03 12:45:23', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'pickup_country', '2023-02-03 12:45:23', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'pickup_dial_code', '2023-02-03 12:45:23', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'pickup_phone', '2023-02-03 12:45:23', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'pickup_email', '2023-02-03 12:45:23', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'pickup_gstin_type', '2023-02-03 12:45:23', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'pickup_gstin_no', '2023-02-03 12:45:23', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'pickup_origin_zone', '2023-02-03 12:45:23', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'consignee_code', '2023-02-03 12:45:23', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'consignee_name', '2023-02-03 12:45:23', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'consignee_company', '2023-02-03 12:45:23', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'consignee_address1', '2023-02-03 12:45:23', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'consignee_address2', '2023-02-03 12:45:23', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'consignee_address3', '2023-02-03 12:45:23', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'consignee_pincode', '2023-02-03 12:45:23', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'consignee_city', '2023-02-03 12:45:23', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'consignee_state', '2023-02-03 12:45:23', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'consignee_country', '2023-02-03 12:45:23', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'consignee_dial_code', '2023-02-03 12:45:23', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'consignee_phone', '2023-02-03 12:45:23', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'consignee_email', '2023-02-03 12:45:23', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'consignee_gstin_type', '2023-02-03 12:45:23', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'consignee_gstin_no', '2023-02-03 12:45:23', 0, '0000-00-00 00:00:00', 0, 2, 1),
                    (NULL, 1, 5, 'consignee_dest_zone', '2023-02-26 12:45:23', 0, '0000-00-00 00:00:00', 0, 2, 1);";
                    $result = $conn->query($insert_qry);

                    echo $value . " inserted successfully<br>";
                }
            }
        }

        //FOR FEDEX-IMPORT add create_pickup in docket_service_field
    }

    function update_token_pickup()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');
        $qry = "SELECT id,token_number,docket_id FROM docket_extra_field 
        WHERE status=1 AND token_number!=''";
        $qry_exe = $this->db->query($qry);
        $docket_data = $qry_exe->result_array();

        $qry = "SELECT id,ref_no FROM pickup_request WHERE status=1";
        $qry_exe = $this->db->query($qry);
        $pickup_data = $qry_exe->result_array();
        if (isset($pickup_data) && is_array($pickup_data) && count($pickup_data) > 0) {
            foreach ($pickup_data as $key => $value) {
                $ref_no[$value['ref_no']] = $value['id'];
            }
        }

        if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
            foreach ($docket_data as $key => $value) {
                if (isset($ref_no[$value['token_number']])) {
                    $updateq = "UPDATE docket_extra_field SET pickup_request_id='" . $ref_no[$value['token_number']] . "'
                     WHERE id='" . $value['id'] . "'";
                    $this->db->query($updateq);
                }
            }
        }
    }
}
