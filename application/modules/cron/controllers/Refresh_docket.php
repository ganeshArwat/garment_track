<?php
class Refresh_docket extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $sessiondata = $this->session->userdata('admin_user');
        $this->customer_id = isset($sessiondata['customer_id']) && $sessiondata['customer_id'] != "" ? $sessiondata['customer_id'] : "";
        $this->user_id = $sessiondata['id'];
        $this->user_type = $sessiondata['type'] == 'customer' ? 2 : 1;
    }


    public function refresh_awb()
    {
        $this->load->model('Global_model', 'gm');
        $qry = "SELECT * FROM refresh_log WHERE status IN(1,2) AND refresh_status=1 AND module_type=1 
        AND (refresh_type='refresh_awb' OR refresh_type='refresh_import_awb') LIMIT 500";
        $qry_exe = $this->db->query($qry);
        $queue_data = $qry_exe->result_array();

        $_SESSION['admin_user']['com_id'] = isset($_GET['cron_company']) ? $_GET['cron_company'] : 0;
        $this->load->module('shipping_api/label_print_api');
        if (isset($queue_data) && is_array($queue_data) && count($queue_data) > 0) {
            foreach ($queue_data as $qkey => $qvalue) {
                //MARK PROCESSED
                $updateq = "UPDATE refresh_log SET refresh_status=2,processed_datetime='" . date('Y-m-d H:i:s') . "' WHERE id='" . $qvalue['id'] . "'";
                $this->db->query($updateq);

                //REFRESH ONLY THOSE DOCKET WHOSE INVOICE NOT MADE OR INVOICE IS UN-LOCKED
                $invoiceq = "SELECT i.id,i.invoice_no,i.invoice_date,i.customer_note FROM docket_invoice i JOIN docket_invoice_map im ON(i.id=im.docket_invoice_id)
                WHERE i.status IN(1,2) AND im.status IN(1,2) AND im.docket_id='" . $qvalue['module_id'] . "'";
                $invoiceq_exe = $this->db->query($invoiceq);
                $invoice_data =  $invoiceq_exe->row_array();

                if (isset($invoice_data) && is_array($invoice_data) && count($invoice_data) > 0) {
                    if ($invoice_data['invoice_status'] == 2) {

                        $this->update_docket($qvalue['module_id']);
                        $this->update_docket_sales($qvalue['module_id']);
                        $this->update_docket_purchase($qvalue['module_id']);
                        if ($qvalue['print_label'] == 1 && $qvalue['refresh_type'] == 'refresh_import_awb') {
                            $shipping_response = $this->label_print_api->get_label_print($qvalue['module_id'], 1, 1, 1);
                        }

                        //MARK DONE
                        $updateq = "UPDATE refresh_log SET refresh_status=3,processed_datetime='" . date('Y-m-d H:i:s') . "' WHERE id='" . $qvalue['id'] . "'";
                        $this->db->query($updateq);
                    } else {
                        //MARK DONE
                        $updateq = "UPDATE refresh_log SET refresh_status=4,processed_datetime='" . date('Y-m-d H:i:s') . "',refresh_msg='locked_docket' WHERE id='" . $qvalue['id'] . "'";
                        $this->db->query($updateq);
                    }
                } else {
                    $this->update_docket($qvalue['module_id']);
                    $this->update_docket_sales($qvalue['module_id']);
                    $this->update_docket_purchase($qvalue['module_id']);
                    if ($qvalue['print_label'] == 1 && $qvalue['refresh_type'] == 'refresh_import_awb') {
                        $shipping_response = $this->label_print_api->get_label_print($qvalue['module_id'], 1, 1, 1);
                    }
                    //MARK DONE
                    $updateq = "UPDATE refresh_log SET refresh_status=3,processed_datetime='" . date('Y-m-d H:i:s') . "' WHERE id='" . $qvalue['id'] . "'";
                    $this->db->query($updateq);
                }
            }
        }
    }




    public function update_docket($docket_id = 0)
    {
        $docket_update_data = array();
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');

        $this->load->module('generic_detail');
        $this->load->module('docket');

        $setting = get_all_app_setting(" AND module_name IN('master','docket','main','general')");

        $old_column = 'awb_no,customer_id,origin_id,destination_id,product_id,booking_date,vendor_id,'
            . 'co_vendor_id,forwarding_no,content,eway_bill,invoice_date,invoice_no,customer_contract_id,'
            . 'customer_contract_tat,cft_contract_id,remarks,status_id,actual_wt,volumetric_wt,consignor_wt,'
            . 'add_wt,chargeable_wt,total_pcs,dispatch_type,shipment_priority,company_id,
    project_id,forwarding_no_2,reference_no,reference_name,shipment_value,shipment_currency_id,
    dispatch_date,dispatch_time,courier_dispatch_date,courier_dispatch_time,instructions,payment_type,ori_hub_id,
    dest_hub_id,challan_no,commit_id,cod_amount,insurance_amount';
        $docket_old_data = $this->gm->get_selected_record('docket', $old_column, $where = array('id' => $docket_id, 'status !=' => 3), array());

        $docket_extra_column = 'po_number,brand_id,pickup_boy_id,cod_status,inscan_date,paid_amount,balance_amount,entry_number,total_quantity,forwarder_id,estimate_no,license_location_id,token_number,project_name';
        $extra_old_data = $this->gm->get_selected_record('docket_extra_field', $docket_extra_column, array('docket_id' => $docket_id, 'status' => 1), array());

        $shipper_consi_col = 'code,name,company_name,address1,address2,address3,pincode,city,state,country,contact_no,email_id,
dob,gstin_type,gstin_no,doc_path';
        $shipper_old_data = $this->gm->get_selected_record('docket_shipper', $shipper_consi_col, array('docket_id' => $docket_id, 'status' => 1), array());
        $consignee_old_data = $this->gm->get_selected_record('docket_consignee', $shipper_consi_col, array('docket_id' => $docket_id, 'status' => 1), array());

        $docketq = "SELECT d.customer_id,d.product_id,d.vendor_id,d.co_vendor_id,d.booking_date,
        d.origin_id,d.destination_id,shi.country as shi_country,con.country as con_country,
        shi.pincode as shi_pincode,con.pincode as con_pincode,shi.city as shi_city,con.city as con_city,
        shi.state as shi_state,con.state as con_state,
        d.ori_zone_id,d.dest_zone_id,d.chargeable_wt,d.shipment_priority,d.ori_hub_id,d.dest_zone_id
        FROM docket d 
        LEFT OUTER JOIN docket_shipper shi ON(d.id=shi.docket_id AND shi.status IN(1,2))
        LEFT OUTER JOIN docket_consignee con ON(d.id=con.docket_id AND con.status IN(1,2))
        where d.status IN(1,2) AND d.id='" . $docket_id . "'";
        $docketq_exe = $this->db->query($docketq);
        $docket_data = $docketq_exe->row_array();
        $sales_update = array();
        $docket_edited_field = array();
        $docket_extra_field = $this->gm->get_selected_record('docket_extra_field', 'id,docket_edit_field', $where = array('docket_id' => $docket_id, 'status' => 1), array());
        if (isset($docket_extra_field['docket_edit_field']) && $docket_extra_field['docket_edit_field'] != '') {
            $docket_edited_field = explode(",", $docket_extra_field['docket_edit_field']);
        }

        $dataAjaxMode = 1;
        $customer_id = isset($docket_data['customer_id']) ? $docket_data['customer_id'] : 0;
        $product_id = isset($docket_data['product_id']) ? $docket_data['product_id'] : 0;
        $vendor_id = isset($docket_data['vendor_id']) ? $docket_data['vendor_id'] : 0;
        $co_vendor_id = isset($docket_data['co_vendor_id']) ? $docket_data['co_vendor_id'] : 0;
        $booking_date = isset($docket_data['booking_date']) ? $docket_data['booking_date'] : 0;
        $origin_id = isset($docket_data['origin_id']) ? $docket_data['origin_id'] : 0;
        $destination_id = isset($docket_data['destination_id']) ? $docket_data['destination_id'] : 0;

        if (!in_array('edit_shipper_dial_code', $docket_edited_field)) {
            $shipper_dial = $this->getShipperDialCode($docket_data);
            if (isset($shipper_dial['dial_code']) && $shipper_dial['dial_code'] != '') {
                $docket_update_data['shipper']['dial_code'] = isset($shipper_dial['dial_code']) ? $shipper_dial['dial_code'] : '';
            }
        }


        if (!(in_array('edit_consignee_dial_code', $docket_edited_field))) {
            $consignee_dial = $this->getConsigneeDialCode($docket_data);
            if (isset($consignee_dial['dial_code']) && $consignee_dial['dial_code'] != '') {
                $docket_update_data['consignee']['dial_code'] = isset($consignee_dial['dial_code']) ? $consignee_dial['dial_code'] : '';
            }
        }



        //get service configuration
        $vendor_id =  isset($docket_data['vendor_id']) ? $docket_data['vendor_id'] : 0;

        $ajax_data = array(
            'vendor_id' => $vendor_id,
        );
        $returnedData = $this->generic_detail->get_vendor_data($ajax_data);

        if (isset($returnedData['id'])) {
            $docket_update_data['docket'] = array(
                'chg_wt_per_item' => isset($returnedData['chg_wt_per_item']) ? $returnedData['chg_wt_per_item'] : 2,
                'round_off_chg_wt' => isset($returnedData['round_off_chg_wt']) ? $returnedData['round_off_chg_wt'] : 2
            );

            if ($returnedData['label_head_id'] == 58) {
                $is_nimbuspost_service = 1;
            } else {
                $is_nimbuspost_service = 2;
            }

            if (isset($setting['default_vendor_service_wise']) && $setting['default_vendor_service_wise'] == 1) {
                if ($docket_data['co_vendor_id'] == 0) {
                    $docket_update_data['docket']['co_vendor_id'] = isset($returnedData['co_vendor_id']) ? $returnedData['co_vendor_id'] : 0;
                    $co_vendor_id = $docket_update_data['docket']['co_vendor_id'];
                } else {
                    $co_vendor_id = $docket_data['co_vendor_id'];
                }
            }
            if (isset($setting['default_product_service_wise']) && $setting['default_product_service_wise'] == 1) {
                if ($docket_data['product_id'] == 0) {
                    $docket_update_data['docket']['product_id'] = isset($returnedData['product_id']) ? $returnedData['product_id'] : 0;
                }
            }
        }


        $ajax_data = array(
            'customer_id' => $customer_id,
            'vendor_id' => $vendor_id,
            'co_vendor_id' => $co_vendor_id,
            'origin_id' => $origin_id,
            'destination_id' => $destination_id,
            'shipper_country' => isset($docket_data['shi_country']) ? $docket_data['shi_country'] : 0,
            'consignee_country' => isset($docket_data['con_country']) ? $docket_data['con_country'] : 0,
            'shipper_pincode' => isset($docket_data['shi_pincode']) ? $docket_data['shi_pincode'] : 0,
            'consignee_pincode' => isset($docket_data['con_pincode']) ? $docket_data['con_pincode'] : 0,
            'type' => 3,
            'booking_date' => $booking_date,
            'shipper_state' => isset($docket_data['shi_state']) ? $docket_data['shi_state'] : '',
            'shipper_city' => isset($docket_data['shi_city']) ? $docket_data['shi_city'] : '',
            'consignee_state' => isset($docket_data['con_state']) ? $docket_data['con_state'] : '',
            'consignee_city' => isset($docket_data['con_city']) ? $docket_data['con_city'] : '',
        );
        $returnedData2 =  $this->generic_detail->get_origin_dest_zone($ajax_data);

        if (isset($returnedData2['ori_zone_id']) && $returnedData2['ori_zone_id'] > 0) {
            $docket_update_data['docket']['ori_zone_id'] = $returnedData2['ori_zone_id'];
            $sales_update['ori_zone_service_type'] = $returnedData2['ori_zone_service_type'];
        } else {
            $docket_update_data['docket']['ori_zone_id'] = 0;
            $sales_update['ori_zone_service_type'] = 0;
        }
        $docket_data['ori_zone_id'] = $docket_update_data['docket']['ori_zone_id'];
        $docket_data['ori_zone_service_type'] = $sales_update['ori_zone_service_type'];

        if (isset($returnedData2['dest_zone_id'])) {
            $docket_update_data['docket']['dest_zone_id'] = $returnedData2['dest_zone_id'];
            $sales_update['dest_zone_service_type'] = $returnedData2['dest_zone_service_type'];
        } else {
            $docket_update_data['docket']['dest_zone_id'] = 0;
            $sales_update['dest_zone_service_type'] = 0;
        }
        $docket_data['dest_zone_id'] = $docket_update_data['docket']['dest_zone_id'];
        $docket_data['dest_zone_service_type'] = $sales_update['dest_zone_service_type'];

        if ($docket_data['ori_hub_id'] == 0) {
            if (isset($returnedData2['ori_hub_id'])) {
                $docket_update_data['docket']['ori_hub_id'] = $returnedData2['ori_hub_id'];
            } else {
                $docket_update_data['docket']['ori_hub_id'] = 0;
            }
            $docket_data['ori_hub_id'] = $docket_update_data['docket']['ori_hub_id'];
        }

        if ($docket_data['dest_hub_id'] == 0) {
            if (isset($returnedData2['dest_hub_id'])) {
                $docket_update_data['docket']['dest_hub_id'] = $returnedData2['dest_hub_id'];
            } else {
                $docket_update_data['docket']['dest_hub_id'] = 0;
            }
            $docket_data['dest_hub_id'] = $docket_update_data['docket']['dest_hub_id'];
        }

        $location_arr = array('origin_id', 'destination_id');
        foreach ($location_arr as $lkey => $lvalue) {
            $input_id = $lvalue;

            if ($input_id == 'origin_id') {
                $location_id = $origin_id;
                $category_name = 'shipper';
            } elseif ($input_id == 'destination_id') {
                $location_id =  $destination_id;
                $category_name = 'consignee';
            }

            $ajax_data = array(
                'location_id' => $location_id
            );
            $returnedData3 = $this->generic_detail->get_location_data($ajax_data);
            if (isset($returnedData3['id'])) {
                if ($returnedData['location_type'] == 2) {
                    if ($returnedData3['pincode'] == '') {
                        $docket_update_data[$category_name]['pincode'] = '';
                    } else {
                        $docket_update_data[$category_name]['pincode'] = $returnedData3['pincode'];
                    }
                }

                if ($returnedData['location_type'] == 2 || $returnedData['location_type'] == 3) {
                    if ($returnedData['city_id'] == '') {
                        if ($returnedData3['city_id'] == '') {
                            $docket_update_data[$category_name]['city'] = '';
                        } else {
                            $docket_update_data[$category_name]['city'] = $returnedData3['city_name'];
                        }
                    }
                }

                if (
                    $returnedData['location_type'] == 2 || $returnedData['location_type'] == 3 ||
                    $returnedData['location_type'] == 4
                ) {
                    if ($returnedData3['state_id'] == '') {
                        $docket_update_data[$category_name]['state'] = '';
                    } else {
                        $docket_update_data[$category_name]['state'] = $returnedData3['state_name'];
                    }
                }

                if ($returnedData3['country_id'] == '') {
                    $docket_update_data[$category_name]['country'] = '';
                } else {
                    $docket_update_data[$category_name]['country'] = $returnedData3['country_id'];
                }
            } else {
                if ($category_name == 'shipper') {
                    $docket_col_cat = 'shi';
                } else {
                    $docket_col_cat = 'con';
                }
                $docket_update_data[$category_name]['city'] = $docket_data[$docket_col_cat . '_city'];
                $docket_update_data[$category_name]['state'] = $docket_data[$docket_col_cat . '_state'];
                $docket_update_data[$category_name]['country'] =  $docket_data[$docket_col_cat . '_country'];
                $docket_update_data[$category_name]['pincode'] =  $docket_data[$docket_col_cat . '_pincode'];
            }

            if ($category_name == 'shipper') {
                $docket_data['shi_city'] =  $docket_update_data[$category_name]['city'];
            } else {
                $docket_data['con_city'] =  $docket_update_data[$category_name]['city'];
            }

            if ($category_name == 'shipper') {

                $docket_data['shi_country'] =  $docket_update_data[$category_name]['country'];
            } else {
                $docket_data['con_country'] =  $docket_update_data[$category_name]['country'];
            }

            if ($category_name == 'shipper') {
                $docket_data['shi_pincode'] =  $docket_update_data[$category_name]['pincode'];
            } else {
                $docket_data['con_pincode'] =  $docket_update_data[$category_name]['pincode'];
            }
        }
        //GET CUSTOMER ORIGIN

        $ajax_data = array(
            'customer_id' => $customer_id
        );
        $returnedData4 =  $this->docket->get_customer_origin($ajax_data);

        if ($docket_data['origin_id'] == 0) {
            if (!isset($returnedData4['id'])) {
                $docket_update_data['docket']['origin_id'] = '';
            } else {
                $docket_update_data['docket']['origin_id'] = $returnedData4['id'];
            }
            $docket_data['origin_id'] =  $docket_update_data['docket']['origin_id'];
        }


        if (!isset($docket_edited_field) || !in_array('company_id', $docket_edited_field)) {
            $query = "select c.company_id from customer c
            where c.status IN(1,2) AND c.id='" . $customer_id . "'";
            $query_exec = $this->db->query($query);
            $cust_company_data = $query_exec->row_array();
            $docket_update_data['docket']['company_id'] = isset($cust_company_data['company_id']) ? $cust_company_data['company_id'] : 0;
            $docket_data['company_id'] =  isset($cust_company_data['company_id']) ? $cust_company_data['company_id'] : 0;
        }
        $cft_data =  $this->get_cft_contract($docket_data);
        if (isset($cft_data) && is_array($cft_data) && count($cft_data) > 0) {
            foreach ($cft_data as $ckey => $cvalue) {
                $docket_update_data['docket'][$ckey] = $cvalue;
            }
        }

        $sales_fsc_data = $this->docket->get_sales_fsc($docket_id);

        $bill_exist = $this->gm->get_selected_record('docket_sales_billing', 'id,edited_field', $where = array('docket_id' => $docket_id, 'status=' => 1), array());

        if (isset($bill_exist) && is_array($bill_exist) && count($bill_exist) > 0) {
            $edited_field = explode(",", $bill_exist['edited_field']);
            if (!in_array('gst_applicable', $edited_field)) {
                $sales_update['is_gst_apply'] = isset($sales_fsc_data['is_gst_apply']) ? $sales_fsc_data['is_gst_apply'] : 2;
            }
        } else {
            $sales_update['is_gst_apply'] = isset($sales_fsc_data['is_gst_apply']) ? $sales_fsc_data['is_gst_apply'] : 2;
        }

        if (isset($sales_update) && is_array($sales_update) && count($sales_update) > 0) {
            if (isset($bill_exist) && is_array($bill_exist) && count($bill_exist) > 0) {
                $this->gm->update('docket_sales_billing', $sales_update, '', array('docket_id' => $docket_id));
            } else {
                $sales_update['docket_id'] = $docket_id;
                $sales_update['created_by'] = $this->user_id;
                $sales_update['created_date'] = date('Y-m-d H:i:s');
                $this->gm->insert('docket_sales_billing', $sales_update);
            }
        }


        // echo '<pre>';
        // print_r($docket_update_data);

        // exit;
        // http_response_code(403);
        if (isset($docket_update_data['docket']) && is_array($docket_update_data['docket']) && count($docket_update_data['docket']) > 0) {
            $this->gm->update('docket', $docket_update_data['docket'], '', array('id' => $docket_id));
            $this->docket->update_docket_invoice_range($docket_id);
            update_docket_sac_code($docket_id);
        }
        if (!isset($setting['different_origin_from_shipper']) || $setting['different_origin_from_shipper'] == 2) {
            if (isset($docket_update_data['shipper']) && is_array($docket_update_data['shipper']) && count($docket_update_data['shipper']) > 0) {
                $this->gm->update('docket_shipper', $docket_update_data['shipper'], '', array('docket_id' => $docket_id));
            }
        }
        if (isset($docket_update_data['consignee']) && is_array($docket_update_data['consignee']) && count($docket_update_data['consignee']) > 0) {
            $this->gm->update('docket_consignee', $docket_update_data['consignee'], '', array('docket_id' => $docket_id));
        }



        //LOG UPDATED DATA HISTORY
        $docket_data_new = $this->gm->get_selected_record('docket', $old_column, $where = array('id' => $docket_id, 'status !=' => 3), array());
        $diff_column = array_diff_assoc(@array_intersect_key($docket_data_new, $docket_old_data), $docket_old_data);
        if (isset($diff_column) && is_array($diff_column) && count($diff_column) > 0) {
            foreach ($diff_column as $dkey => $dvalue) {
                if (isset($docket_old_data[$dkey])) {
                    $old_data[$dkey] = $docket_old_data[$dkey];
                }
                $new_data[$dkey] = $dvalue;
            }
        }

        $extra_insert_new = $this->gm->get_selected_record('docket_extra_field', $docket_extra_column, array('docket_id' => $docket_id, 'status' => 1), array());
        if (isset($extra_insert_new) && is_array($extra_insert_new) && count($extra_insert_new) > 0) {
        } else {
            $extra_insert_new = array();
        }
        if (isset($extra_old_data) && is_array($extra_old_data) && count($extra_old_data) > 0) {
        } else {
            $extra_old_data  = array();
        }
        $extra_diff_column = array_diff_assoc(@array_intersect_key($extra_insert_new, $extra_old_data), $extra_old_data);
        if (isset($extra_diff_column) && is_array($extra_diff_column) && count($extra_diff_column) > 0) {
            foreach ($extra_diff_column as $dkey => $dvalue) {
                if (isset($extra_old_data[$dkey])) {
                    $old_data["de." . $dkey] = $extra_old_data[$dkey];
                }
                $new_data["de." . $dkey] = $dvalue;
            }
        }


        $shipper_insert_new = $this->gm->get_selected_record('docket_shipper', $shipper_consi_col, array('docket_id' => $docket_id, 'status' => 1), array());
        $shipper_diff_column = array_diff_assoc(@array_intersect_key($shipper_insert_new, $shipper_old_data), $shipper_old_data);
        if (isset($shipper_diff_column) && is_array($shipper_diff_column) && count($shipper_diff_column) > 0) {
            foreach ($shipper_diff_column as $dkey => $dvalue) {
                if (isset($shipper_old_data[$dkey])) {
                    $old_data["sh." . $dkey] = $shipper_old_data[$dkey];
                }
                $new_data["sh." . $dkey] = $dvalue;
            }
        }

        $consignee_insert_new = $this->gm->get_selected_record('docket_consignee', $shipper_consi_col, array('docket_id' => $docket_id, 'status' => 1), array());
        $consignee_diff_column = array_diff_assoc(@array_intersect_key($consignee_insert_new, $consignee_old_data), $consignee_old_data);
        if (isset($consignee_diff_column) && is_array($consignee_diff_column) && count($consignee_diff_column) > 0) {
            foreach ($consignee_diff_column as $dkey => $dvalue) {
                if (isset($shipper_old_data[$dkey])) {
                    $old_data["co." . $dkey] = $shipper_old_data[$dkey];
                }
                $new_data["co." . $dkey] = $dvalue;
            }
        }

        update_manifest_docket_detail(0, $docket_id);
        if ($docket_id > 0) {
            if ((isset($new_data) && is_array($new_data) && count($new_data) > 0)
                || isset($old_data) && is_array($old_data) && count($old_data) > 0
            ) {
                $old_data['mode'] = 'update_docket';
                $insert_data_history = array(
                    'docket_id' => $docket_id,
                    'new_data' => isset($new_data) ? json_encode($new_data) : '',
                    'old_data' => isset($old_data) ? json_encode($old_data) : '',
                    'created_date' => date('Y-m-d H:i:s'),
                    'created_by' => $this->user_id,
                    'created_by_type' => $this->user_type
                );

                $this->gm->insert('docket_history', $insert_data_history);
            }
        }
    }

    public function get_cft_contract($docket_data)
    {
        $ajax_data = array(
            'customer_id' => isset($docket_data['customer_id']) ? $docket_data['customer_id'] : 0,
            'product_id' => isset($docket_data['product_id']) ? $docket_data['product_id'] : 0,
            'vendor_id' => isset($docket_data['vendor_id']) ? $docket_data['vendor_id'] : 0,
            'co_vendor_id' => isset($docket_data['co_vendor_id']) ? $docket_data['co_vendor_id'] : 0,
            'booking_date' => isset($docket_data['booking_date']) ? $docket_data['booking_date'] : 0,
        );
        $cft_returnedData = $this->generic_detail->get_cft_contract($ajax_data);
        $response = $this->get_customer_contract($docket_data);
        $response['cft_value'] = isset($cft_returnedData['cft_value']) && $cft_returnedData['cft_value'] != '' && $cft_returnedData['cft_value'] > 0 ? $cft_returnedData['cft_value'] : 1;
        $response['cft_multiplier'] = isset($cft_returnedData['cft_multiplier']) && $cft_returnedData['cft_multiplier'] != '' && $cft_returnedData['cft_multiplier'] > 0 ? $cft_returnedData['cft_multiplier'] : 1;
        $response['cft_contract_id'] = isset($cft_returnedData['id']) ? $cft_returnedData['id'] : 0;

        return  $response;
    }
    public function get_customer_contract($docket_data)
    {
        $contract_res = array();
        $ajax_data = array(
            'customer_id' => isset($docket_data['customer_id']) ? $docket_data['customer_id'] : 0,
            'product_id' => isset($docket_data['product_id']) ? $docket_data['product_id'] : 0,
            'vendor_id' => isset($docket_data['vendor_id']) ? $docket_data['vendor_id'] : 0,
            'co_vendor_id' => isset($docket_data['co_vendor_id']) ? $docket_data['co_vendor_id'] : 0,
            'origin_id' => isset($docket_data['origin_id']) ? $docket_data['origin_id'] : 0,
            'destination_id' => isset($docket_data['destination_id']) ? $docket_data['destination_id'] : 0,
            'ori_zone_id' => isset($docket_data['ori_zone_id']) ? $docket_data['ori_zone_id'] : 0,
            'dest_zone_id' => isset($docket_data['dest_zone_id']) ? $docket_data['dest_zone_id'] : 0,
            'ori_city_id' =>  isset($docket_data['shi_city']) ? $docket_data['shi_city'] : '',
            'dest_city_id' =>  isset($docket_data['con_city']) ? $docket_data['con_city'] : '',

            'ori_state_id' =>  isset($docket_data['shi_state']) ? $docket_data['shi_state'] : '',
            'dest_state_id' =>  isset($docket_data['con_state']) ? $docket_data['con_state'] : '',

            'ori_hub_id' =>  isset($docket_data['ori_hub_id']) ? $docket_data['ori_hub_id'] : '',
            'dest_hub_id' =>  isset($docket_data['dest_hub_id']) ? $docket_data['dest_hub_id'] : '',

            'booking_date' => isset($docket_data['booking_date']) ? $docket_data['booking_date'] : '',
            'chargeable_wt_total' => isset($docket_data['chargeable_wt']) ? $docket_data['chargeable_wt'] : '',
            'shipment_priority' => isset($docket_data['shipment_priority']) ? $docket_data['shipment_priority'] : '',
        );
        $contract_returnedData = $this->generic_detail->get_customer_contract($ajax_data);

        if (isset($contract_returnedData['consigner_wt'])) {
            $contract_res['consignor_wt'] = $contract_returnedData['consigner_wt'];
        } else {
            $contract_res['consignor_wt'] = 0;
        }

        if (isset($contract_returnedData['id']) || isset($contract_returnedData['tat_id'])) {
            $contract_res['customer_contract_id'] = $contract_returnedData['id'];
            $contract_res['customer_contract_tat'] = $contract_returnedData['tat'];
        } else {
            $contract_res['customer_contract_id'] = 0;
            $contract_res['customer_contract_tat'] = 0;
        }

        if (isset($contract_returnedData['setting_co_vendor_id']) && $contract_returnedData['setting_co_vendor_id'] > 0) {
            $contract_res['co_vendor_id'] = $contract_returnedData['setting_co_vendor_id'];
        }

        return $contract_res;
    }

    public function getShipperDialCode($docket_data = array())
    {
        $response = array();
        $ajax_data  = array(
            'country_id' => isset($docket_data['shi_country']) ? $docket_data['shi_country'] : 0
        );
        $dial_code_res = $this->generic_detail->get_country_dial_code($ajax_data);
        if (isset($dial_code_res['id'])) {
            $response['dial_code'] = $dial_code_res['dial_code'];
        }

        return $response;
    }
    public function getConsigneeDialCode($docket_data = array())
    {
        $response = array();
        $ajax_data  = array(
            'country_id' => isset($docket_data['con_country']) ? $docket_data['con_country'] : 0
        );
        $dial_code_res = $this->generic_detail->get_country_dial_code($ajax_data);
        if (isset($dial_code_res['id'])) {
            $response['dial_code'] = $dial_code_res['dial_code'];
        }

        return $response;
    }

    public function refresh_sales()
    {
        $this->load->model('Global_model', 'gm');
        $qry = "SELECT id,module_id,refresh_type,docket_invoice_id FROM refresh_log WHERE status IN(1,2) AND refresh_status=1 AND module_type=1 AND 
        (refresh_type='refresh_sales_billing' OR refresh_type='refresh_invoice' ) 
        LIMIT 500";
        $qry_exe = $this->db->query($qry);
        $queue_data = $qry_exe->result_array();


        if (isset($queue_data) && is_array($queue_data) && count($queue_data) > 0) {
            foreach ($queue_data as $qkey => $qvalue) {
                //MARK PROCESSED
                $updateq = "UPDATE refresh_log SET refresh_status=2,processed_datetime='" . date('Y-m-d H:i:s') . "' WHERE id='" . $qvalue['id'] . "'";
                $this->db->query($updateq);

                //REFRESH ONLY THOSE DOCKET WHOSE INVOICE NOT MADE OR INVOICE IS UN-LOCKED
                $invoiceq = "SELECT i.id,i.invoice_no,i.invoice_date,i.customer_note,i.invoice_status FROM docket_invoice i JOIN docket_invoice_map im ON(i.id=im.docket_invoice_id)
                WHERE i.status IN(1,2) AND im.status IN(1,2) AND im.docket_id='" . $qvalue['module_id'] . "'";
                $invoiceq_exe = $this->db->query($invoiceq);
                $invoice_data =  $invoiceq_exe->row_array();

                if (isset($invoice_data) && is_array($invoice_data) && count($invoice_data) > 0) {
                    if ($invoice_data['invoice_status'] == 2) {
                        $this->update_docket($qvalue['module_id']);
                        $this->update_docket_sales($qvalue['module_id']);
                        //MARK DONE
                        $updateq = "UPDATE refresh_log SET refresh_status=3,processed_datetime='" . date('Y-m-d H:i:s') . "' WHERE id='" . $qvalue['id'] . "'";
                        $this->db->query($updateq);
                    } else {
                        //MARK DONE
                        $updateq = "UPDATE refresh_log SET refresh_status=4,processed_datetime='" . date('Y-m-d H:i:s') . "',refresh_msg='locked_docket' WHERE id='" . $qvalue['id'] . "'";
                        $this->db->query($updateq);
                    }
                } else {

                    if ($qvalue['refresh_type'] == 'refresh_invoice') {
                        //CHECK INVOICE PRESENT
                        $invoiceq = "SELECT i.id,i.invoice_no,i.invoice_date,i.customer_note FROM docket_invoice i 
                        WHERE i.status IN(1,2) AND im.id='" . $qvalue['docket_invoice_id'] . "'";
                        $invoiceq_exe = $this->db->query($invoiceq);
                        $invoiceExist =  $invoiceq_exe->row_array();

                        if (isset($invoiceExist) && is_array($invoiceExist) && count($invoiceExist) > 0) {
                            $this->update_docket($qvalue['module_id']);
                            $this->update_docket_sales($qvalue['module_id']);


                            //MARK DONE
                            $updateq = "UPDATE refresh_log SET refresh_status=3,processed_datetime='" . date('Y-m-d H:i:s') . "' WHERE id='" . $qvalue['id'] . "'";
                            $this->db->query($updateq);
                        } else {
                            $updateq = "UPDATE refresh_log SET refresh_status=4,processed_datetime='" . date('Y-m-d H:i:s') . "',refresh_msg='invoice_not_found' WHERE id='" . $qvalue['id'] . "'";
                            $this->db->query($updateq);
                        }
                    } else {
                        $this->update_docket_sales($qvalue['module_id']);
                        //MARK DONE
                        $updateq = "UPDATE refresh_log SET refresh_status=3,processed_datetime='" . date('Y-m-d H:i:s') . "' WHERE id='" . $qvalue['id'] . "'";
                        $this->db->query($updateq);
                    }
                }


                if ($qvalue['refresh_type'] == 'refresh_invoice') {
                    //LOCK INVOICE IF NO DOCKET IN QUEUE LEFT
                    $qry = "SELECT id FROM refresh_log WHERE status=1 AND refresh_type='refresh_invoice' AND refresh_status=1";
                    $qry_exe = $this->db->query($qry);
                    $queueLeft =  $qry_exe->row_array();

                    if (isset($queueLeft) && is_array($queueLeft) && count($queueLeft) > 0) {
                        //UNLOCK INVOICE
                        $qry = "UPDATE docket_invoice SET invoice_status=2 WHERE id='" . $qvalue['docket_invoice_id'] . "'";
                        if (isset($_GET['test'])) {
                            echo $qry;
                        } else {
                            $this->db->query($qry);
                        }

                        // $this->gm->update('docket_invoice', array('invoice_status' => 2), '', array('id' => $qvalue['docket_invoice_id']));

                    } else {
                        //LOCK INVOICE
                        $this->gm->update('docket_invoice', array('invoice_status' => 1), '', array('id' => $qvalue['docket_invoice_id']));
                    }
                }
            }
        }
    }

    public function update_docket_sales($docket_id = 0, $bill_old_data = '')
    {

        $docket_update_data = array();
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');

        $this->load->module('generic_detail');
        $this->load->module('docket');

        $setting = get_all_app_setting(" AND module_name IN('master','docket','main','general')");

        $old_column = 'awb_no,customer_id,origin_id,destination_id,product_id,booking_date,vendor_id,'
            . 'co_vendor_id,forwarding_no,content,eway_bill,invoice_date,invoice_no,customer_contract_id,'
            . 'customer_contract_tat,cft_contract_id,remarks,status_id,actual_wt,volumetric_wt,consignor_wt,'
            . 'add_wt,chargeable_wt,total_pcs,dispatch_type,shipment_priority,company_id,
    project_id,forwarding_no_2,reference_no,reference_name,shipment_value,shipment_currency_id,
    dispatch_date,dispatch_time,courier_dispatch_date,courier_dispatch_time,instructions,payment_type,ori_hub_id,
    dest_hub_id,challan_no,commit_id,cod_amount,insurance_amount';
        $docket_old_data = $this->gm->get_selected_record('docket', $old_column, $where = array('id' => $docket_id, 'status !=' => 3), array());
        if (isset($docket_old_data) && is_array($docket_old_data) && count($docket_old_data) > 0) {
        } else {
            $docket_old_data = array();
        }
        $docket_extra_column = 'po_number,brand_id,pickup_boy_id,cod_status,inscan_date,paid_amount,balance_amount,entry_number,total_quantity,forwarder_id,estimate_no,license_location_id,token_number,project_name';
        $extra_old_data = $this->gm->get_selected_record('docket_extra_field', $docket_extra_column, array('docket_id' => $docket_id, 'status' => 1), array());
        if (isset($extra_old_data) && is_array($extra_old_data) && count($extra_old_data) > 0) {
        } else {
            $extra_old_data = array();
        }
        $shipper_consi_col = 'code,name,company_name,address1,address2,address3,pincode,city,state,country,contact_no,email_id,
dob,gstin_type,gstin_no,doc_path';
        $shipper_old_data = $this->gm->get_selected_record('docket_shipper', $shipper_consi_col, array('docket_id' => $docket_id, 'status' => 1), array());
        $consignee_old_data = $this->gm->get_selected_record('docket_consignee', $shipper_consi_col, array('docket_id' => $docket_id, 'status' => 1), array());

        if (isset($shipper_old_data) && is_array($shipper_old_data) && count($shipper_old_data) > 0) {
        } else {
            $shipper_old_data = array();
        }
        if (isset($consignee_old_data) && is_array($consignee_old_data) && count($consignee_old_data) > 0) {
        } else {
            $consignee_old_data = array();
        }

        if (isset($bill_old_data) && is_array($bill_old_data) && count($bill_old_data) > 0) {
            $sales_billing_old_data = $bill_old_data;
        } else {
            $billing_col = 'freight_amount,total_other_charge,fsc_amount,discount_per,discount_amount,freight_after_dis,'
                . 'sub_total,non_taxable_amt,taxable_amt,gst_per,igst_amount,cgst_amount,sgst_amount,grand_total';
            $sales_billing_old_data = $this->gm->get_selected_record('docket_sales_billing', $billing_col, array('docket_id' => $docket_id, 'status' => 1), array());
        }

        if (isset($sales_billing_old_data) && is_array($sales_billing_old_data) && count($sales_billing_old_data) > 0) {
        } else {
            $sales_billing_old_data = array();
        }
        $docketq = "SELECT d.customer_id,d.product_id,d.vendor_id,d.co_vendor_id,d.booking_date,
        d.origin_id,d.destination_id,shi.country as shi_country,con.country as con_country,
        shi.pincode as shi_pincode,con.pincode as con_pincode,shi.city as shi_city,con.city as con_city,
        shi.state as shi_state,con.state as con_state,
        d.ori_zone_id,d.dest_zone_id,d.chargeable_wt,d.shipment_priority,d.company_id,d.id as docket_id,
		d.shipment_value,d.actual_wt,d.volumetric_wt,d.total_pcs,bill.discount_per,bill.is_gst_apply,
        bill.edited_field as bill_edited,bill.freight_amount as bill_freight_amount,
        bill.fsc_amount as bill_fsc_amount,bill.igst_amount as igst_amount,
        bill.cgst_amount as cgst_amount,bill.sgst_amount as sgst_amount,d.consignor_wt,bill.grand_total,d.ori_hub_id,d.dest_hub_id,
        bill.adjustment_amount,d.id,d.add_wt,bill.edi_discount_amount,d.status_id
        FROM docket d 
        JOIN docket_sales_billing bill ON(d.id=bill.docket_id)
        LEFT OUTER JOIN docket_shipper shi ON(d.id=shi.docket_id AND shi.status IN(1,2))
        LEFT OUTER JOIN docket_consignee con ON(d.id=con.docket_id AND con.status IN(1,2))
        where d.status IN(1,2) AND d.id='" . $docket_id . "'  AND bill.status IN(1,2)";
        $docketq_exe = $this->db->query($docketq);
        $docket_data = $docketq_exe->row_array();



        if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
            $edited_field = array();
            if (isset($docket_data['bill_edited']) && $docket_data['bill_edited'] != '') {
                $edited_field = explode(",", $docket_data['bill_edited']);
            }

            $cft_data =  $this->get_cft_contract($docket_data);

            if (isset($cft_data) && is_array($cft_data) && count($cft_data) > 0) {
                foreach ($cft_data as $ckey => $cvalue) {
                    $docket_update_data['docket'][$ckey] = $cvalue;
                    $docket_data[$ckey] = $cvalue;
                }
            }

            $all_company = get_all_billing_company();
            $docket_update_data['sales_billing']['company_tax_type'] = isset($all_company[$docket_data['company_id']]) ? $all_company[$docket_data['company_id']]['tax_type'] : 1;
            $docket_data['company_tax_type'] = isset($all_company[$docket_data['company_id']]) ? $all_company[$docket_data['company_id']]['tax_type'] : 1;


            //CALCULATE WT
            $weight_data =  $this->calculate_wt($docket_data);

            if (isset($weight_data['item']) && is_array($weight_data['item']) && count($weight_data['item']) > 0) {
                $docket_update_data['item'] = $weight_data['item'];
                $docket_data['item'] = $weight_data['item'];
            }

            $docket_data['chargeable_wt'] = isset($weight_data['docket']['chargeable_wt']) ? $weight_data['docket']['chargeable_wt'] : 0;
            $docket_update_data['docket']['chargeable_wt'] = isset($weight_data['docket']['chargeable_wt']) ? $weight_data['docket']['chargeable_wt'] : 0;

            $docket_edited_field = array();
            $docket_extra_field = $this->gm->get_selected_record('docket_extra_field', 'id,docket_edit_field', $where = array('docket_id' => $docket_id, 'status' => 1), array());
            if (isset($docket_extra_field['docket_edit_field']) && $docket_extra_field['docket_edit_field'] != '') {
                $docket_edited_field = explode(",", $docket_extra_field['docket_edit_field']);
            }

            if (!in_array('edit_actual_wt', $docket_edited_field)) {
                $docket_update_data['docket']['actual_wt'] = isset($weight_data['docket']['actual_wt']) ? $weight_data['docket']['actual_wt'] : 0;
            }
            if (!in_array('edit_volume_wt', $docket_edited_field)) {
                $docket_update_data['docket']['volumetric_wt'] = isset($weight_data['docket']['volumetric_wt']) ? $weight_data['docket']['volumetric_wt'] : 0;
            }

            if (isset($cft_data['customer_contract_id']) && $cft_data['customer_contract_id'] > 0) {
                $freight_round_data = $this->calculate_freight($docket_data);
                $freight_data['chargeable_wt'] = $freight_round_data['chargeable_wt'];
                $docket_data['chargeable_wt'] = $freight_data['chargeable_wt'];
            }


            if (!in_array('grand_total', $edited_field)) {
                if (!in_array('freight_amount', $edited_field)) {
                    $docket_data['chargeable_wt'] = isset($weight_data['docket']['chargeable_wt']) ? $weight_data['docket']['chargeable_wt'] : 0;
                    $freight_data = $this->calculate_freight($docket_data);
                } else {
                    $freight_data['freight_amount'] = $docket_data['bill_freight_amount'];
                    $freight_data['chargeable_wt'] = $docket_data['chargeable_wt'];
                }



                $freight_data['freight_amount'] = round($freight_data['freight_amount'], 2);



                $docket_update_data['docket']['freight_amount'] =  $freight_data['freight_amount'];
                $docket_data['freight_amount'] = $freight_data['freight_amount'];
                $docket_data['chargeable_wt'] = $freight_data['chargeable_wt'];

                $docket_update_data['sales_billing']['freight_amount'] = $freight_data['freight_amount'];
                $docket_update_data['docket']['chargeable_wt'] = $freight_data['chargeable_wt'];

                //CALCULATE OTHER CHARGE
                $charge_amount_data = $this->get_charge_amt($docket_data);

                $all_charge = get_all_charge(" AND status IN(1,2)", '*');


                //get dokcet manual charges
                $sales_charge = $this->gm->get_data_list('docket_charges', array('docket_id' => $docket_id, 'status' => 1, 'billing_type' => 1, 'charge_check' => 1), array(), array(), 'id,charge_id,charge_amount,rate_mod_id,charge_check,billing_type');
                if (isset($sales_charge) && is_array($sales_charge) && count($sales_charge) > 0) {
                    foreach ($sales_charge as $okey => $ovalue) {
                        $docket_sales_charges[$ovalue['charge_id']] = $ovalue;
                    }
                }


                if (isset($charge_amount_data) && is_array($charge_amount_data) && count($charge_amount_data) > 0) {
                    foreach ($charge_amount_data as $ch_key => $ch_value) {
                        if (isset($all_charge[$ch_key])) {
                            if ($all_charge[$ch_key]['is_manual'] == 1) {
                                $docket_update_data['charge'][$ch_key] = array(
                                    'rate_mod_id' => isset($docket_sales_charges[$ch_key]) ? $docket_sales_charges[$ch_key]['rate_mod_id'] : 0,
                                    'charge_amount' => isset($docket_sales_charges[$ch_key]) ? round($docket_sales_charges[$ch_key]['charge_amount'], 2) : 0,
                                    'charge_check' => isset($docket_sales_charges[$ch_key]) ? $docket_sales_charges[$ch_key]['charge_check'] : 2,
                                );
                            } else {
                                $docket_update_data['charge'][$ch_key] = array(
                                    'rate_mod_id' => $ch_value['rate_mod_id'],
                                    'charge_amount' => round($ch_value['rate_charge_amt'], 2),
                                    'charge_check' => $ch_value['charge_check'],
                                );
                            }

                            $docket_data['charge'][$ch_key] = $docket_update_data['charge'][$ch_key];

                            if ($ch_value['freight_fsc_per'] > 0 && $all_charge[$ch_key]['is_fsc_apply'] == 2) {

                                $fsc_rate_found = 1;
                            }
                        }
                    }
                }



                $fsc_amt_data = $this->get_sales_fsc($docket_data);

                if (in_array('fsc_amount', $edited_field)) {
                    $fsc_amt_data['fsc_amount'] = $docket_data['bill_fsc_amount'];
                }

                if (isset($fsc_amt_data['fsc_amount'])) {
                    $fsc_amt_data['fsc_amount'] = round($fsc_amt_data['fsc_amount'], 2);
                }


                if (isset($fsc_rate_found) && $fsc_rate_found == 1) {
                    $docket_data['fsc_amount'] = $fsc_amt_data['fsc_amount'];

                    $charge_amount_data = $this->get_charge_amt($docket_data);

                    $all_charge = get_all_charge(" AND status IN(1,2)", '*');

                    //get dokcet manual charges
                    $sales_charge = $this->gm->get_data_list('docket_charges', array('docket_id' => $docket_id, 'status' => 1, 'billing_type' => 1, 'charge_check' => 1), array(), array(), 'id,charge_id,charge_amount,rate_mod_id,charge_check,billing_type');
                    if (isset($sales_charge) && is_array($sales_charge) && count($sales_charge) > 0) {
                        foreach ($sales_charge as $okey => $ovalue) {
                            $docket_sales_charges[$ovalue['charge_id']] = $ovalue;
                        }
                    }


                    if (isset($charge_amount_data) && is_array($charge_amount_data) && count($charge_amount_data) > 0) {
                        foreach ($charge_amount_data as $ch_key => $ch_value) {
                            if (isset($all_charge[$ch_key])) {
                                if ($all_charge[$ch_key]['is_manual'] == 1) {
                                    $docket_update_data['charge'][$ch_key] = array(
                                        'rate_mod_id' => isset($docket_sales_charges[$ch_key]) ? $docket_sales_charges[$ch_key]['rate_mod_id'] : 0,
                                        'charge_amount' => isset($docket_sales_charges[$ch_key]) ? round($docket_sales_charges[$ch_key]['charge_amount'], 2) : 0,
                                        'charge_check' => isset($docket_sales_charges[$ch_key]) ? $docket_sales_charges[$ch_key]['charge_check'] : 2,
                                    );
                                } else {
                                    $docket_update_data['charge'][$ch_key] = array(
                                        'rate_mod_id' => $ch_value['rate_mod_id'],
                                        'charge_amount' => round($ch_value['rate_charge_amt'], 2),
                                        'charge_check' => $ch_value['charge_check'],
                                    );
                                }

                                $docket_data['charge'][$ch_key] = $docket_update_data['charge'][$ch_key];
                            }
                        }
                    }
                }



                $docket_update_data['sales_billing']['fsc_amount'] = isset($fsc_amt_data['fsc_amount']) ? $fsc_amt_data['fsc_amount'] : '';
                $docket_update_data['sales_billing']['gst_per'] = isset($fsc_amt_data['gst_per']) ? $fsc_amt_data['gst_per'] : '';
                $docket_update_data['sales_billing']['fsc_per'] = isset($fsc_amt_data['fsc_per']) ? $fsc_amt_data['fsc_per'] : '';

                $docket_data['fsc_amount'] = isset($fsc_amt_data['fsc_amount']) ? $fsc_amt_data['fsc_amount'] : '';
                $docket_data['service_type'] = isset($fsc_amt_data['service_type']) ? $fsc_amt_data['service_type'] : '';
                $docket_data['gst_type'] = isset($fsc_amt_data['gst_type']) ? $fsc_amt_data['gst_type'] : '';
                $docket_data['gst_per'] = isset($fsc_amt_data['gst_per']) ? $fsc_amt_data['gst_per'] : '';

                $total_data =  $this->calculate_other_charge($docket_data);
                if (isset($total_data) && is_array($total_data) && count($total_data) > 0) {
                    foreach ($total_data as $tkey => $tvalue) {
                        $docket_update_data['sales_billing'][$tkey] = $tvalue;
                    }
                }
            } else {
                //REVERSE CALCULATION

                $grand_total = isset($docket_data['grand_total']) ? $docket_data['grand_total'] : 0;

                $docket_update_data['sales_billing']['grand_total'] = $grand_total;

                $fsc_amt_data = $this->get_sales_fsc($docket_data);

                $docket_update_data['sales_billing']['gst_per'] = isset($fsc_amt_data['gst_per']) ? $fsc_amt_data['gst_per'] : '';

                $docket_data['gst_per'] = isset($fsc_amt_data['gst_per']) ? $fsc_amt_data['gst_per'] : 0;
                $docket_data['service_type'] =  isset($fsc_amt_data['service_type']) ? $fsc_amt_data['service_type'] : '';
                $docket_data['gst_type'] = isset($fsc_amt_data['gst_type']) ? $fsc_amt_data['gst_type'] : '';


                //APPLY FSC ON TOTAL CHARGES
                $other_charge_fsc_total = 0;
                $other_charge_gst_total = 0;
                $fsc_amount = 0;
                $gst_amount = 0;
                $non_taxable_amt = 0;
                $other_charge_total = 0;

                //CALCULATE OTHER CHARGE
                $charge_amount_data = $this->get_charge_amt($docket_data);

                $all_charge = get_all_charge(" AND status IN(1,2)", '*');

                //get dokcet manual charges
                $sales_charge = $this->gm->get_data_list('docket_charges', array('docket_id' => $docket_id, 'status' => 1, 'billing_type' => 1, 'charge_check' => 1), array(), array(), 'id,charge_id,charge_amount,rate_mod_id,charge_check,billing_type');
                if (isset($sales_charge) && is_array($sales_charge) && count($sales_charge) > 0) {
                    foreach ($sales_charge as $okey => $ovalue) {
                        $docket_sales_charges[$ovalue['charge_id']] = $ovalue;
                    }
                }



                if (isset($charge_amount_data) && is_array($charge_amount_data) && count($charge_amount_data) > 0) {
                    foreach ($charge_amount_data as $ch_key => $ch_value) {
                        if (isset($all_charge[$ch_key])) {
                            $chrage_amt = 0;

                            if ($all_charge[$ch_key]['is_manual'] == 1) {
                                $docket_update_data['charge'][$ch_key] = array(
                                    'rate_mod_id' => isset($docket_sales_charges[$ch_key]) ? $docket_sales_charges[$ch_key]['rate_mod_id'] : 0,
                                    'charge_amount' => isset($docket_sales_charges[$ch_key]) ? round($docket_sales_charges[$ch_key]['charge_amount'], 2) : 0,
                                    'charge_check' => isset($docket_sales_charges[$ch_key]) ? $docket_sales_charges[$ch_key]['charge_check'] : 2,
                                );
                            } else {
                                $docket_update_data['charge'][$ch_key] = array(
                                    'rate_mod_id' => $ch_value['rate_mod_id'],
                                    'charge_amount' => round($ch_value['rate_charge_amt'], 2),
                                    'charge_check' => $ch_value['charge_check'],
                                );
                            }

                            if ($all_charge[$ch_key]['is_manual'] == 1) {
                                $chrage_amt = isset($docket_sales_charges[$ch_key]) ? $docket_sales_charges[$ch_key]['charge_amount'] : 0;
                            } else {
                                $chrage_amt = $ch_value['rate_charge_amt'];
                            }
                            $other_charge_total = $other_charge_total + $chrage_amt;

                            if (isset($fsc_amt_data['customer_fsc_apply']) && $fsc_amt_data['customer_fsc_apply'] == 1) {
                                if ($all_charge[$ch_key]['is_fsc_apply'] == 1) {
                                    $other_charge_fsc_total = $other_charge_fsc_total + $chrage_amt;
                                }
                            }

                            if ($all_charge[$ch_key]['is_gst_apply'] == 1) {
                                if ($docket_data['is_gst_apply'] == 1) {
                                    $other_charge_gst_total = $other_charge_gst_total + $chrage_amt;
                                } else {
                                    $non_taxable_amt = $non_taxable_amt + $chrage_amt;
                                }
                            } else {
                                $non_taxable_amt = $non_taxable_amt + $chrage_amt;
                            }

                            $docket_data['charge'][$ch_key] = $docket_update_data['charge'][$ch_key];
                        }
                    }
                }

                $remaining_amt = $grand_total - $other_charge_total;
                if ($docket_data['is_gst_apply'] != 1) {
                    $non_taxable_amt = $non_taxable_amt + $remaining_amt;
                }

                $other_charge_total = round($other_charge_total, 2);
                $other_charge_total = (float)$other_charge_total;

                $non_taxable_amt = round($non_taxable_amt, 2);
                $non_taxable_amt = (float)$non_taxable_amt;

                $taxable_amt = $grand_total - $non_taxable_amt;

                if ($docket_data['gst_per'] == 0) {
                    $non_taxable_amt = $non_taxable_amt + $taxable_amt;
                    $taxable_amt = 0;
                }
                $taxable_amt = round($taxable_amt, 2);
                $taxable_amt = (float)$taxable_amt;

                $tax_amount = ($taxable_amt / (100 + $docket_data['gst_per'])) * $docket_data['gst_per'];
                $tax_amount = round($tax_amount, 2);
                $tax_amount = (float)$tax_amount;

                $p12 = ($grand_total - $non_taxable_amt) - $tax_amount;
                $sub_total = $non_taxable_amt + $p12;

                $sub_total = round($sub_total, 2);
                $sub_total = (float)$sub_total;

                $fsc_amt_data = $this->get_sales_fsc($docket_data);
                $docket_data['fsc_per'] = isset($fsc_amt_data['fsc_per']) ? $fsc_amt_data['fsc_per'] : '';


                $fsc_charge_per = (100 + $docket_data['fsc_per']) / 100;
                $fsc_charge_per = (float)$fsc_charge_per;

                $fsc_amount = $sub_total / $fsc_charge_per;

                $fsc_amount = round($fsc_amount, 2);
                $fsc_amount = (float)$fsc_amount;

                if (in_array('fsc_amount', $edited_field)) {
                    $total_fsc = $docket_data['bill_fsc_amount'];
                } else {
                    $total_fsc = $sub_total - $fsc_amount;
                }



                $total_fsc = round($total_fsc, 2);
                $total_fsc = (float)$total_fsc;

                $p2 = $other_charge_total + $total_fsc + $tax_amount;

                $freight = $grand_total - $p2;
                $freight = round($freight, 2);
                $freight = (float)$freight;

                $docket_update_data['sales_billing']['freight_amount'] = $freight;
                $docket_update_data['docket']['freight_amount'] = $freight;
                $docket_data['freight_amount'] = $freight;

                if (isset($fsc_amt_data['id'])) {
                    $docket_update_data['sales_billing']['sales_fsc_id'] = $fsc_amt_data['id'];
                    $docket_update_data['sales_billing']['fsc_amount'] = $total_fsc;
                } else {
                    $docket_update_data['sales_billing']['sales_fsc_id'] = 0;
                    $docket_update_data['sales_billing']['fsc_amount'] = 0;
                }

                $FscAmt = $docket_update_data['sales_billing']['fsc_amount'];
                if (isset($docket_data['fsc_per'])) {
                    if (!in_array('fsc_amount', $edited_field)) {
                        $docket_update_data['sales_billing']['fsc_per'] = isset($fsc_amt_data['fsc_per']) ? $fsc_amt_data['fsc_per'] : '';
                        $docket_data['fsc_per'] = isset($fsc_amt_data['fsc_per']) ? $fsc_amt_data['fsc_per'] : '';
                    }
                }

                $docket_data['fsc_amount'] = $docket_update_data['sales_billing']['fsc_amount'];
                $total_data =  $this->calculate_other_charge($docket_data);
                if (isset($total_data) && is_array($total_data) && count($total_data) > 0) {
                    foreach ($total_data as $tkey => $tvalue) {
                        $docket_update_data['sales_billing'][$tkey] = $tvalue;
                    }
                }
            }

            $docket_update_data['docket']['sales_billing_save'] = 1;



            //GET  SALES CURRENCY
            $currency_id = 66; //INDIA
            if (isset($docket_data['company_id']) && $docket_data['company_id'] > 0) {
                $qry = "SELECT id,country FROM company_master WHERE status IN(1,2) AND id='" . $docket_data['company_id'] . "'";
                $qry_exe = $this->db->query($qry);
                $company_country_data = $qry_exe->row_array();
                if (isset($company_country_data) && is_array($company_country_data) && count($company_country_data) > 0) {
                    $country_name = strtolower(trim($company_country_data['country']));
                    if ($country_name == 'india') {
                        $currency_id = 66; //INDIA
                    } else {
                        $qry = "SELECT id,name,currency_code_id FROM country WHERE status IN(1,2) AND LOWER(name)='" .  $country_name . "'";
                        $qry_exe = $this->db->query($qry);
                        $country_data = $qry_exe->row_array();
                        if (isset($country_data) && is_array($country_data) && count($country_data) > 0) {
                            $currency_id = $country_data['currency_code_id'];
                        }
                    }
                }
            }


            if (isset($_GET['test'])) {
                echo '<pre>';
                print_r($docket_update_data);
                exit;
            }

            $docket_update_data['docket_extra']['sector_id'] = 0;
            if (isset($setting['enable_sectorwise_report']) && $setting['enable_sectorwise_report'] == 1) {
                if (isset($docket_data['vendor_id']) && $docket_data['vendor_id'] > 0) {
                    $qry = "SELECT id,sector_id FROM vendor WHERE status IN(1,2) AND id='" . $docket_data['vendor_id'] . "'";
                    $qry_exe = $this->db->query($qry);
                    $vendor_sec_data = $qry_exe->row_array();

                    $docket_update_data['docket_extra']['sector_id'] = isset($vendor_sec_data['sector_id']) ? $vendor_sec_data['sector_id'] : 0;
                }
            }
            $docket_update_data['docket_extra']['sales_currency_id'] = $currency_id;
            if (isset($docket_update_data['docket_extra']) && is_array($docket_update_data['docket_extra']) && count($docket_update_data['docket_extra']) > 0) {
                $this->gm->update('docket_extra_field', $docket_update_data['docket_extra'], '', array('docket_id' => $docket_id));
            }

            if (isset($docket_update_data['item']) && is_array($docket_update_data['item']) && count($docket_update_data['item']) > 0) {
                foreach ($docket_update_data['item'] as $ikey => $ivalue) {
                    $this->gm->update('docket_items', $ivalue, '', array('id' => $ikey));
                }
            }

            if (isset($docket_update_data['docket']) && is_array($docket_update_data['docket']) && count($docket_update_data['docket']) > 0) {
                $this->gm->update('docket', $docket_update_data['docket'], '', array('id' => $docket_id));
            }

            if (isset($docket_data['status_id']) && $docket_data['status_id'] == 3) {
                //FOR VOID DOCKET SET ALL VALUE TO 0
                $sales_val_arr = array(
                    'freight_amount', 'total_other_charge', 'fsc_amount',
                    'discount_per', 'discount_amount', 'freight_after_dis', 'sub_total', 'non_taxable_amt',
                    'taxable_amt', 'gst_per', 'igst_amount', 'cgst_amount', 'sgst_amount', 'grand_total',
                    'fsc_per', 'adjustment_amount'
                );
                foreach ($sales_val_arr as $skey => $svalue) {
                    $docket_update_data['sales_billing'][$svalue] = 0;
                }
            }

            if (isset($docket_data['status_id']) && $docket_data['status_id'] > 4) {
                //FOR DOCKET STATUS MASTER SET DONT CALCULATE CHARGE SET ALL VALUE TO 0

                $docket_status_qry = "SELECT * FROM `docket_status` WHERE `id` = " . $docket_data['status_id'];
                $docket_status_exe = $this->db->query($docket_status_qry);
                $docket_status_data = $docket_status_exe->row_array();

                if ((isset($docket_status_data) && is_array($docket_status_data) && count($docket_status_data) > 0) && (isset($docket_status_data['dont_calculate_charge']) && $docket_status_data['dont_calculate_charge'] == 1)) {
                    $sales_val_arr = array(
                        'freight_amount', 'total_other_charge', 'fsc_amount',
                        'discount_per', 'discount_amount', 'freight_after_dis', 'sub_total', 'non_taxable_amt',
                        'taxable_amt', 'gst_per', 'igst_amount', 'cgst_amount', 'sgst_amount', 'grand_total',
                        'fsc_per', 'adjustment_amount'
                    );
                    foreach ($sales_val_arr as $skey => $svalue) {
                        $docket_update_data['sales_billing'][$svalue] = 0;
                    }
                }
            }

            if (isset($docket_update_data['sales_billing']) && is_array($docket_update_data['sales_billing']) && count($docket_update_data['sales_billing']) > 0) {
                $this->gm->update('docket_sales_billing', $docket_update_data['sales_billing'], '', array('docket_id' => $docket_id));
            }

            // exit;
            // http_response_code(403);
            if (isset($docket_update_data['charge']) && is_array($docket_update_data['charge']) && count($docket_update_data['charge']) > 0) {
                $qry = "SELECT id,charge_id,charge_check FROM docket_charges WHERE status IN(1,2) 
                AND docket_id='" . $docket_id . "' AND billing_type=1";
                $qry_exe = $this->db->query($qry);
                $result = $qry_exe->result_array();

                if (isset($result) && is_array($result) && count($result) > 0) {
                    foreach ($result as $ckey => $cvalue) {
                        $docket_old_charge[$cvalue['charge_id']] = $cvalue['id'];
                    }
                }

                $updateq = "UPDATE docket_charges SET status=3 WHERE status IN(1,2) 
                AND docket_id='" . $docket_id . "' AND billing_type=1";
                $this->db->query($updateq);

                foreach ($docket_update_data['charge'] as $ci_key => $ci_value) {
                    if (isset($docket_old_charge[$ci_key])) {
                        $charge_update = array(
                            'rate_mod_id' => $ci_value['rate_mod_id'],
                            'charge_amount' => isset($docket_data['status_id']) && $docket_data['status_id'] == 3 ? 0 : $ci_value['charge_amount'],
                            'charge_check' => $ci_value['charge_check'],
                            'modified_date' => date('Y-m-d H:i:s'),
                            'status' => 1
                        );
                        $this->gm->update('docket_charges', $charge_update, '', array('id' => $docket_old_charge[$ci_key]));
                    } else {
                        $charge_insert = array(
                            'docket_id' => $docket_id,
                            'charge_id' => $ci_key,
                            'rate_mod_id' => $ci_value['rate_mod_id'],
                            'charge_amount' => isset($docket_data['status_id']) && $docket_data['status_id'] == 3 ? 0 : $ci_value['charge_amount'],
                            'charge_check' => $ci_value['charge_check'],
                            'billing_type' => 1,
                            'created_date' => date('Y-m-d H:i:s'),
                            'status' => 1
                        );
                        $this->gm->insert('docket_charges', $charge_insert);
                    }
                    //   echo "<br>qry=" . $this->db->last_query();
                }
            }
        }

        //LOG UPDATED DATA HISTORY
        $docket_data_new = $this->gm->get_selected_record('docket', $old_column, $where = array('id' => $docket_id, 'status !=' => 3), array());

        if (isset($docket_data_new) && is_array($docket_data_new) && count($docket_data_new) > 0) {
        } else {
            $docket_data_new = array();
        }
        $intersect_data  = @array_intersect_key($docket_data_new, $docket_old_data);
        if (isset($intersect_data) && is_array($intersect_data) && count($intersect_data) > 0) {
        } else {
            $intersect_data = array();
        }
        $diff_column = array_diff_assoc($intersect_data, $docket_old_data);
        if (isset($diff_column) && is_array($diff_column) && count($diff_column) > 0) {
            foreach ($diff_column as $dkey => $dvalue) {
                if (isset($docket_old_data[$dkey])) {
                    $old_data[$dkey] = $docket_old_data[$dkey];
                }
                $new_data[$dkey] = $dvalue;
            }
        }

        $extra_insert_new = $this->gm->get_selected_record('docket_extra_field', $docket_extra_column, array('docket_id' => $docket_id, 'status' => 1), array());
        if (isset($extra_insert_new) && is_array($extra_insert_new) && count($extra_insert_new) > 0) {
        } else {
            $extra_insert_new = array();
        }
        $extra_diff_column = array_diff_assoc(@array_intersect_key($extra_insert_new, $extra_old_data), $extra_old_data);
        if (isset($extra_diff_column) && is_array($extra_diff_column) && count($extra_diff_column) > 0) {
            foreach ($extra_diff_column as $dkey => $dvalue) {
                if (isset($extra_old_data[$dkey])) {
                    $old_data["de." . $dkey] = $extra_old_data[$dkey];
                }
                $new_data["de." . $dkey] = $dvalue;
            }
        }


        $shipper_insert_new = $this->gm->get_selected_record('docket_shipper', $shipper_consi_col, array('docket_id' => $docket_id, 'status' => 1), array());
        if (isset($shipper_insert_new) && is_array($shipper_insert_new) && count($shipper_insert_new) > 0) {
        } else {
            $shipper_insert_new = array();
        }

        $shipper_diff_column = array_diff_assoc(@array_intersect_key($shipper_insert_new, $shipper_old_data), $shipper_old_data);
        if (isset($shipper_diff_column) && is_array($shipper_diff_column) && count($shipper_diff_column) > 0) {
            foreach ($shipper_diff_column as $dkey => $dvalue) {
                if (isset($shipper_old_data[$dkey])) {
                    $old_data["sh." . $dkey] = $shipper_old_data[$dkey];
                }
                $new_data["sh." . $dkey] = $dvalue;
            }
        }

        $consignee_insert_new = $this->gm->get_selected_record('docket_consignee', $shipper_consi_col, array('docket_id' => $docket_id, 'status' => 1), array());
        if (isset($consignee_insert_new) && is_array($consignee_insert_new) && count($consignee_insert_new) > 0) {
        } else {
            $consignee_insert_new = array();
        }
        $consignee_diff_column = array_diff_assoc(@array_intersect_key($consignee_insert_new, $consignee_old_data), $consignee_old_data);
        if (isset($consignee_diff_column) && is_array($consignee_diff_column) && count($consignee_diff_column) > 0) {
            foreach ($consignee_diff_column as $dkey => $dvalue) {
                if (isset($shipper_old_data[$dkey])) {
                    $old_data["co." . $dkey] = $shipper_old_data[$dkey];
                }
                $new_data["co." . $dkey] = $dvalue;
            }
        }

        update_manifest_docket_detail(0, $docket_id);
        if ($docket_id > 0) {
            if ((isset($new_data) && is_array($new_data) && count($new_data) > 0)
                || isset($old_data) && is_array($old_data) && count($old_data) > 0
            ) {
                $old_data['mode'] = 'update_docket_sales';
                $insert_data_history = array(
                    'docket_id' => $docket_id,
                    'new_data' => isset($new_data) ? json_encode($new_data) : '',
                    'old_data' => isset($old_data) ? json_encode($old_data) : '',
                    'created_date' => date('Y-m-d H:i:s'),
                    'created_by' => $this->user_id,
                    'created_by_type' => $this->user_type
                );

                $this->gm->insert('docket_history', $insert_data_history);
            }



            $sales_data_new = $this->gm->get_selected_record('docket_sales_billing', $billing_col, array('docket_id' => $docket_id, 'status' => 1), array());

            file_put_contents(FCPATH . 'log1/billing', json_encode($sales_data_new), FILE_APPEND);
            file_put_contents(FCPATH . 'log1/billing', json_encode($sales_billing_old_data), FILE_APPEND);

            $sales_billing_diff_column = array_diff_assoc(array_intersect_key($sales_data_new, $sales_billing_old_data), $sales_billing_old_data);
            if (isset($sales_billing_diff_column) && is_array($sales_billing_diff_column) && count($sales_billing_diff_column) > 0) {
                foreach ($sales_billing_diff_column as $dkey => $dvalue) {
                    if (isset($sales_billing_old_data[$dkey])) {
                        $sales_old_data[$dkey] = $sales_billing_old_data[$dkey];
                    }
                    $sales_new_data[$dkey] = $dvalue;
                }

                $history_new['sales'] = isset($sales_new_data) ? $sales_new_data : '';
                $history_old['sales'] = isset($sales_old_data) ? $sales_old_data : '';
            }

            if (
                isset($history_old) && is_array($history_old) && count($history_old) > 0
            ) {
                $history_old['mode'] = 'update_docket_sales';
                $insert_data_history = array(
                    'docket_id' => $docket_id,
                    'new_data' => isset($history_new) ? json_encode($history_new) : '',
                    'old_data' => isset($history_old) ? json_encode($history_old) : '',
                    'created_date' => date('Y-m-d H:i:s'),
                    'created_by' => $this->user_id,
                    'created_by_type' => $this->user_type
                );
                $this->gm->insert('billing_history', $insert_data_history);
            }
        }
    }


    public function calculate_wt($calculate_data = array())
    {

        $total_actual_wt = 0;
        $total_volu_wt = 0;
        $total_charge_wt = 0;
        $total_pieces = 0;
        $wt_update_data = array();
        if (isset($calculate_data['validate_docket']) && $calculate_data['validate_docket'] == 1) {
            $docket_data = $calculate_data['docket'];
        } else {
            $docket_data = $calculate_data;
        }
        $cft_value = isset($docket_data['cft_value']) ? $docket_data['cft_value'] : 0;
        $cft_multiplier = isset($docket_data['cft_multiplier']) ? $docket_data['cft_multiplier'] : 0;
        $cft_contract_id = isset($docket_data['cft_contract_id']) ? $docket_data['cft_contract_id'] : 0;
        $customer_contract_id = isset($docket_data['customer_contract_id']) ? $docket_data['customer_contract_id'] : 0;

        $edited_field = array();

        if (isset($docket_data['calculation']) && $docket_data['calculation'] == 'estimate') {
            $edited_field = array('edit_actual_wt', 'edit_volume_wt');
        } else {
            if (isset($calculate_data['validate_docket']) && $calculate_data['validate_docket'] == 1) {
                $edited_field_form = $calculate_data['edit_field'];
                if (isset($edited_field_form) && is_array($edited_field_form) && count($edited_field_form) > 0) {
                    foreach ($edited_field_form as $ekey => $evalue) {
                        $edited_field[] =  $ekey;
                    }
                }
            } else {
                $docket_extra_field = $this->gm->get_selected_record('docket_extra_field', 'id,docket_edit_field', $where = array('docket_id' => $docket_data['id'], 'status' => 1), array());
                if (isset($docket_extra_field['docket_edit_field']) && $docket_extra_field['docket_edit_field'] != '') {
                    $edited_field = explode(",", $docket_extra_field['docket_edit_field']);
                }
            }
        }


        if ($cft_contract_id == '' || $cft_contract_id <= 0) {
            $cft_contract_id = 0;
        }
        $cft_contract_id = (int)$cft_contract_id;
        $cft_value = (float)$cft_value;
        $cft_multiplier = (float)$cft_multiplier;

        $id = isset($docket_data['id']) ? $docket_data['id'] : NULL;
        if (isset($calculate_data['validate_docket']) && $calculate_data['validate_docket'] == 1) {
            if (isset($calculate_data['act_wt']) && is_array($calculate_data['act_wt']) && count($calculate_data['act_wt']) > 0) {
                $box_sr_no = 1;
                foreach ($calculate_data['act_wt'] as $key => $value) {

                    if (
                        $calculate_data['dim_box_count'][$key] > 0 ||
                        $value != '' || $calculate_data['dim_len'][$key] != '' || $calculate_data['dim_wid'][$key] != '' || $calculate_data['dim_hei'][$key] != ''
                    ) {
                        if ($calculate_data['dim_box_count'][$key] == '' || $calculate_data['dim_box_count'][$key] == 0) {
                            $calculate_data['dim_box_count'][$key] = 1;
                        }
                        if ($calculate_data['vol_wt'][$key] == '') {
                            $calculate_data['vol_wt'][$key] = 0;
                        }
                        if ($calculate_data['char_wt'][$key] == '') {
                            $calculate_data['char_wt'][$key] = 0;
                        }
                        for ($box = 1; $box <= $calculate_data['dim_box_count'][$key]; $box++) {

                            $line_actual_wt = $value;
                            $calculate_data['char_wt'][$key] = (float)$calculate_data['char_wt'][$key];
                            $calculate_data['vol_wt'][$key] = (float)$calculate_data['vol_wt'][$key];
                            $calculate_data['dim_box_count'][$key] = (int)$calculate_data['dim_box_count'][$key];
                            $item_data[] = array(
                                'box_no' => $box_sr_no,
                                'actual_wt' => $line_actual_wt,
                                'item_length' => isset($calculate_data['dim_len'][$key]) ? (float)$calculate_data['dim_len'][$key] : 0,
                                'item_width' => isset($calculate_data['dim_wid'][$key]) ? (float)$calculate_data['dim_wid'][$key] : 0,
                                'item_height' => isset($calculate_data['dim_hei'][$key]) ? (float)$calculate_data['dim_hei'][$key] : 0,
                                'box_count' => 1,
                                'no_of_box' => $calculate_data['dim_box_count'][$key],
                                'sr_no' => $key,
                                'volumetric_wt' => isset($calculate_data['vol_wt'][$key]) ? round(($calculate_data['vol_wt'][$key] / $calculate_data['dim_box_count'][$key]), 2) : 0,
                                'chargeable_wt' => isset($calculate_data['char_wt'][$key]) ? round(($calculate_data['char_wt'][$key] / $calculate_data['dim_box_count'][$key]), 2) : 0,
                                'created_date' => date('Y-m-d H:i:s'),
                                'created_by' => $this->user_id
                            );

                            $box_sr_no++;
                        }
                    }
                }
            }
        } else {
            $item_data = $this->gm->get_data_list('docket_items', array('docket_id' => $id, 'status' => 1), array(), array('box_no' => 'asc'), '*');
        }



        //GET CUSTOMER SETTING
        if (isset($docket_data['customer_id']) && $docket_data['customer_id'] > 0) {
            $qry = "SELECT c.id,m.config_key,m.config_value FROM customer c 
            JOIN module_setting m ON(c.id=m.module_id AND m.module_type=1) 
            WHERE c.id='" . $docket_data['customer_id'] . "' AND c.status IN(1,2) AND m.status IN(1,2) 
            AND m.config_key='cust_parcel_wise_discounted_wt'";
            $qry_exe = $this->db->query($qry);
            $customer_setting = $qry_exe->row_array();
        }
        if (isset($docket_data['vendor_id']) && $docket_data['vendor_id'] > 0) {
            $qry = "SELECT id,parcel_wise_discounted_ch_wt FROM vendor WHERE status IN(1,2) AND id='" . $docket_data['vendor_id'] . "'";
            $qry_exe = $this->db->query($qry);
            $service_setting = $qry_exe->row_array();
        }

        $parcel_wise_discounted_ch_wt = 2;
        if (
            isset($customer_setting['config_value']) && $customer_setting['config_value'] == 1 &&
            isset($service_setting['parcel_wise_discounted_ch_wt']) && $service_setting['parcel_wise_discounted_ch_wt'] == 1
        ) {
            $parcel_wise_discounted_ch_wt = 1;
        }

        if (isset($item_data) && is_array($item_data) && count($item_data) > 0) {
            foreach ($item_data as $ikey => $ivalue) {
                $act_wt = $ivalue['actual_wt'];
                $box_count = $ivalue['box_count'];
                if ($box_count == '' || $box_count <= 0) {
                    $box_count = 1;
                }
                if ($act_wt == '' || $act_wt <= 0) {
                    $act_wt = 0;
                }

                if ($act_wt > 0) {
                    $act_wt = (float)$act_wt;
                    $box_count = (float)$box_count;
                    $multiplication_val = $act_wt * $box_count;
                    $multiplication_val = round($multiplication_val, 2);
                    $multiplication_val = (float)$multiplication_val;
                    $total_actual_wt = $total_actual_wt + $multiplication_val;
                }

                $item_length = $ivalue['item_length'];
                $item_width = $ivalue['item_width'];
                $item_height = $ivalue['item_height'];

                if ($item_length == '' || $item_length == 0) {
                    $item_length = 1;
                }
                if ($item_width == '' || $item_width == 0) {
                    $item_width = 1;
                }
                if ($item_height == '' || $item_height == 0) {
                    $item_height = 1;
                }
                $item_height = (float)$item_height;
                $item_width = (float)$item_width;
                $item_height = (float)$item_height;
                if ($cft_contract_id > 0) {
                    $volumetric_wt = ($item_length * $item_width * $item_height * $box_count * $cft_multiplier) / $cft_value;
                } else {
                    $volumetric_wt = 0;
                }

                $volumetric_wt = round($volumetric_wt, 2);
                $volumetric_wt = (float) $volumetric_wt;

                $wt_update_data['item'][$ivalue['id']]['volumetric_wt'] = $volumetric_wt;

                $total_volu_wt = $total_volu_wt + $volumetric_wt;

                if ($volumetric_wt > ($act_wt * $box_count)) {
                    $wt_update_data['item'][$ivalue['id']]['chargeable_wt'] = $volumetric_wt;
                } else {
                    $multiplication_val = $act_wt * $box_count;
                    $multiplication_val = round($multiplication_val, 2);
                    $multiplication_val = (float)$multiplication_val;
                    $wt_update_data['item'][$ivalue['id']]['chargeable_wt']  = $multiplication_val;
                }

                if ($parcel_wise_discounted_ch_wt == 1) {
                    if ((float)$wt_update_data['item'][$ivalue['id']]['chargeable_wt'] > (float)$act_wt) {
                        $wt_update_data['item'][$ivalue['id']]['chargeable_wt'] = (float)$wt_update_data['item'][$ivalue['id']]['chargeable_wt'];
                        $act_wt = (float)$act_wt;
                        $wt_diff = $wt_update_data['item'][$ivalue['id']]['chargeable_wt'] - $act_wt;
                        $half_wt = $wt_diff / 2;
                        $half_wt = round($half_wt, 2);
                        $half_wt = (float)$half_wt;
                        $new_chare_wt = $act_wt + $half_wt;
                        $wt_update_data['item'][$ivalue['id']]['chargeable_wt']  = $new_chare_wt;
                    }
                }

                //ROUNF OFF CHARGEABLE WT PER LINE ITEM AS PER CONTRACT
                if (isset($docket_data['vendor_id']) && $docket_data['vendor_id'] > 0) {
                    $service_data = $this->gm->get_selected_record('vendor', 'id,round_off_chg_wt,chg_wt_per_item', $where = array('id' => $docket_data['vendor_id']), array());
                }

                $round_off_chg_wt = isset($service_data['round_off_chg_wt']) && $service_data['round_off_chg_wt'] == 1 ? 1 : 2;
                if ($round_off_chg_wt == 1) {
                    $charge_wt = $wt_update_data['item'][$ivalue['id']]['chargeable_wt'];
                    //Round OFF CHARGEABLE WEIGTH
                    $this->load->module('generic_detail');
                    $rate_data['contract_id'] = $customer_contract_id;
                    $customerContractData = $this->generic_detail->get_customer_contract($rate_data);

                    if (isset($customerContractData['rate']) && is_array($customerContractData['rate']) && count($customerContractData['rate']) > 0) {
                        $charge_slab_found = 2;

                        foreach ($customerContractData['rate'] as $rate_key => $value) {
                            $lower_wt = $value['lower_wt'];
                            $upper_wt = $value['upper_wt'];

                            $lower_wt = (float) $lower_wt;
                            $charge_wt = (float) $charge_wt;
                            $upper_wt = (float) $upper_wt;
                            if ($charge_wt >= $lower_wt && $charge_wt <= $upper_wt) {
                                $charge_slab_found = 1;
                            }
                        }

                        //round up  wt if slab not found
                        if ($charge_slab_found == 2) {
                            $charge_wt = ceil($charge_wt);
                        }

                        foreach ($customerContractData['rate'] as $rate_key => $value) {
                            $lower_wt = $value['lower_wt'];
                            $upper_wt = $value['upper_wt'];

                            $lower_wt = (float) $lower_wt;
                            $charge_wt = (float) $charge_wt;
                            $upper_wt = (float) $upper_wt;

                            if ($charge_wt >= $lower_wt && $charge_wt <= $upper_wt) {

                                $on_add = $value['on_add'];
                                $on_add = (string)$on_add;

                                if ($charge_slab_found == 2) {
                                    $charge_wt = sprintf('%0.2f', $charge_wt);
                                }
                                $charge_wt = (string)$charge_wt;

                                if (strpos($charge_wt, '.') !== false) {

                                    $decimal_amt = 0;
                                    $decimal_arr = explode(".", $on_add);

                                    if (isset($decimal_arr[1]) && $decimal_arr[1] != '') {
                                        $decimal_amt = $decimal_arr[1];
                                        if (strlen($decimal_arr[1]) == 1) {
                                            $decimal_amt = $decimal_arr[1] . '0';
                                        }
                                    }
                                    $round_ch_wt = $charge_wt;

                                    $charge_decimal_arr = explode(".", $charge_wt);


                                    if (isset($charge_decimal_arr[1]) && $charge_decimal_arr[1] != '') {
                                        $chare_decimal_amt = $charge_decimal_arr[1];

                                        if (strlen($charge_decimal_arr[1]) == 1) {
                                            $chare_decimal_amt = $charge_decimal_arr[1] . '0';
                                        }
                                    }


                                    if (isset($chare_decimal_amt) && $decimal_amt != 0) {

                                        $charge_remain = ($chare_decimal_amt % $decimal_amt);


                                        if ($charge_remain > 0) {
                                            $decimal_to_add = $decimal_amt - $charge_remain;
                                            $chare_decimal_amt = $chare_decimal_amt + $decimal_to_add;
                                            $chare_decimal_amt = $chare_decimal_amt / 100;
                                            $round_ch_wt = $charge_decimal_arr[0] + $chare_decimal_amt;
                                        } else {
                                            $round_ch_wt = $charge_wt;
                                        }


                                        $round_ch_wt = round($round_ch_wt, 2);
                                    } else {
                                        $round_ch_wt = ceil($charge_wt);
                                    }

                                    $wt_update_data['item'][$ivalue['id']]['chargeable_wt']  = $round_ch_wt;
                                }
                                break;
                            }
                        }
                    }
                }

                $total_charge_wt = $total_charge_wt + $wt_update_data['item'][$ivalue['id']]['chargeable_wt'];
            }
        }

        if (in_array('edit_actual_wt', $edited_field)) {
            $total_actual_wt = isset($docket_data['actual_wt']) ? $docket_data['actual_wt'] : 0;
        }

        if (in_array('edit_volume_wt', $edited_field)) {
            $total_volu_wt = isset($docket_data['volumetric_wt']) ? $docket_data['volumetric_wt'] : 0;
        }
        $wt_update_data['docket']['actual_wt'] = $total_actual_wt;
        $wt_update_data['docket']['volumetric_wt'] = $total_volu_wt;
        $chg_wt_per_item = isset($service_data['chg_wt_per_item']) && $service_data['chg_wt_per_item'] == 1 ? 1 : 2;

        if ($chg_wt_per_item == 1) {
            $calculate_wt_by_line = 1;
            if (in_array('edit_actual_wt', $edited_field)) {
                $calculate_wt_by_line = 2;
            }
            if (in_array('edit_volume_wt', $edited_field)) {
                $calculate_wt_by_line = 2;
            }
        } else {
            $calculate_wt_by_line = 2;
        }
        if ($calculate_wt_by_line == 1) {

            $wt_update_data['total_charge_wt'] = $total_charge_wt;

            //IF ADD WT GREATER THAN CHARGEABLE THEN SET ADD WT
            $add_wt_total = isset($docket_data['add_wt']) ? $docket_data['add_wt'] : 0;
            if ($add_wt_total == '' || $add_wt_total <= 0) {
                $add_wt_total = 0;
            }

            $chargeable_wt_total = $total_charge_wt;
            if ($chargeable_wt_total == '' || $chargeable_wt_total <= 0) {
                $chargeable_wt_total = 0;
            }
            $add_wt_total = (float) $add_wt_total;
            $chargeable_wt_total = (float) $chargeable_wt_total;

            if ($add_wt_total > $chargeable_wt_total) {
                $chargeable_wt_total = $add_wt_total;
            }

            if (in_array('edit_charge_wt', $edited_field)) {
                $chargeable_wt_total = isset($docket_data['chargeable_wt']) ? $docket_data['chargeable_wt'] : 0;
            }

            $wt_update_data['docket']['chargeable_wt'] = $chargeable_wt_total;
        } else {
            //find largest between ACTUAL WEIGHT,VOLUMETRIC WEIGHT,CONSIGNER WEIGHT,ADD WEIGHT
            $actual_wt_total = $total_actual_wt;
            $volumetric_wt_total = $total_volu_wt;
            $consignor_wt_total =  isset($docket_data['consignor_wt']) ? $docket_data['consignor_wt'] : 0;
            $add_wt_total = isset($docket_data['add_wt']) ? $docket_data['add_wt'] : 0;
            if ($actual_wt_total == '' || $actual_wt_total <= 0) {
                $actual_wt_total = 0;
            }
            if ($volumetric_wt_total == '' || $volumetric_wt_total <= 0) {
                $volumetric_wt_total = 0;
            }
            if ($consignor_wt_total == '' || $consignor_wt_total <= 0) {
                $consignor_wt_total = 0;
            }
            if ($add_wt_total == '' || $add_wt_total <= 0) {
                $add_wt_total = 0;
            }
            $actual_wt_total = (float)$actual_wt_total;
            $volumetric_wt_total = (float)$volumetric_wt_total;
            $consignor_wt_total = (float)$consignor_wt_total;
            $add_wt_total = (float)$add_wt_total;

            $largest_wt = max($actual_wt_total, $volumetric_wt_total, $consignor_wt_total, $add_wt_total);
            $largest_wt = (float)$largest_wt;


            if (in_array('edit_charge_wt', $edited_field)) {
                $chargeable_wt_total = isset($docket_data['chargeable_wt']) ? $docket_data['chargeable_wt'] : 0;
            } else {
                $chargeable_wt_total = $largest_wt;
            }
            $wt_update_data['docket']['chargeable_wt'] = $chargeable_wt_total;
        }


        return $wt_update_data;
    }
    public function calculate_other_charge($docket_data)
    {

        $all_charge = get_all_charge(" AND status IN(1,2)", '*');
        $total_other_charge = 0;
        $discount_amount = 0;
        $edited_field = array();
        if (isset($docket_data['bill_edited']) && $docket_data['bill_edited'] != '') {
            $edited_field = explode(",", $docket_data['bill_edited']);
        }
        if (isset($docket_data['charge']) && is_array($docket_data['charge']) && count($docket_data['charge']) > 0) {
            foreach ($docket_data['charge'] as $ch_key => $ch_value) {
                $total_other_charge += $ch_value['charge_amount'];
            }
        }

        $total_other_charge = round($total_other_charge, 2);
        $response['total_other_charge'] = $total_other_charge;

        if (isset($docket_data['discount_per']) && $docket_data['discount_per'] > 0) {
            $discount_amount = ($docket_data['freight_amount'] * $docket_data['discount_per']) / 100;
            $discount_amount = round($discount_amount, 2);
            $response['discount_amount'] = $discount_amount;
        } else if (isset($docket_data['edi_discount_amount']) && $docket_data['edi_discount_amount'] > 0) {
            $discount_amount = round($docket_data['edi_discount_amount'], 2);
            $response['discount_amount'] = $discount_amount;
        }

        $adjustment_amount = isset($docket_data['adjustment_amount']) ? $docket_data['adjustment_amount'] : 0;
        $adjustment_amount = round($adjustment_amount, 2);


        $freight_after_dis = $docket_data['freight_amount'] -  $discount_amount;
        $freight_after_dis = round($freight_after_dis, 2);
        $response['freight_after_dis'] = $freight_after_dis;

        $sub_total = $total_other_charge + $docket_data['fsc_amount'] + $freight_after_dis + $adjustment_amount;
        $sub_total = round($sub_total, 2);
        $response['sub_total'] = $sub_total;

        if (isset($docket_data['is_gst_apply']) && $docket_data['is_gst_apply'] == 1) {
            $freight_gst =  $freight_after_dis + $docket_data['fsc_amount'];
        } else {
            $non_taxable_amt =  $freight_after_dis + $docket_data['fsc_amount'];
        }

        //  $response['edited_field'] = '';

        if (isset($docket_data['charge']) && is_array($docket_data['charge']) && count($docket_data['charge']) > 0) {
            foreach ($docket_data['charge'] as $ch_key => $ch_value) {
                if (isset($all_charge[$ch_key]) && $all_charge[$ch_key]['is_gst_apply'] != 1) {
                    $non_taxable_amt +=  $ch_value['charge_amount'];
                }
            }
        }

        $non_taxable_amt = round($non_taxable_amt, 2);

        if (isset($docket_data['is_gst_apply']) && $docket_data['is_gst_apply'] == 1) {
            $non_taxable_amt =  isset($non_taxable_amt) ? $non_taxable_amt : 0;
            $response['non_taxable_amt'] = $non_taxable_amt;

            $taxable_amt = $sub_total - $non_taxable_amt;
            $taxable_amt = round($taxable_amt, 2);
            $response['taxable_amt'] = $taxable_amt;
        } else {
            $non_taxable_amt =  $sub_total;
            $response['non_taxable_amt'] = $non_taxable_amt;

            $taxable_amt = 0;
            $response['taxable_amt'] = $taxable_amt;
        }



        $gst_amount = 0;
        $gst_per = isset($docket_data['gst_per']) ? $docket_data['gst_per'] : 0;
        if ($gst_per == 0) {
            $non_taxable_amt += $taxable_amt;
            $response['non_taxable_amt'] = $non_taxable_amt;
            $taxable_amt = 0;
            $response['taxable_amt'] = $taxable_amt;
        }
        if ($gst_per > 0) {
            $gst_amount = ($taxable_amt *  $gst_per) / 100;
            $gst_amount = round($gst_amount, 2);
        }

        if (isset($docket_data['vendor_id']) &&  $docket_data['vendor_id'] > 0) {
            $all_vendor = get_all_vendor(" AND id='" . $docket_data['vendor_id'] . "'");
        }

        if (isset($docket_data['service_type']) && $docket_data['service_type'] != '') {
            if ($docket_data['service_type'] == 1) {
                $igst_amt = $gst_amount;
                $gst_type = 'igst';
            } else {
                if ($docket_data['gst_type'] == 'igst') {
                    $igst_amt = $gst_amount;
                    $gst_type = 'igst';
                } else {
                    $cgst = $gst_amount / 2;
                    $sgst = $gst_amount / 2;
                    $gst_type = 'cgst';
                }
            }
        } else {
            if (isset($docket_data['vendor_id']) && isset($all_vendor[$docket_data['vendor_id']]) && $all_vendor[$docket_data['vendor_id']]['service_type'] == 1) {
                $igst_amt = $gst_amount;
            } else {
                if ($docket_data['gst_type'] == 'igst') {
                    $igst_amt = $gst_amount;
                    $gst_type = 'igst';
                } else {
                    $cgst = $gst_amount / 2;
                    $sgst = $gst_amount / 2;
                    $gst_type = 'cgst';
                }
            }
        }

        if (isset($docket_data['company_tax_type']) && $docket_data['company_tax_type'] == 2) {
            //VAT TYPE
            $igst_amt = $gst_amount;
            $cgst = 0;
            $sgst = 0;
            $gst_type = 'igst';
        }
        $igst_amt = isset($igst_amt) ? round($igst_amt, 2) : 0;
        $cgst = isset($cgst) ? round($cgst, 2) : 0;
        $sgst = isset($sgst) ? round($sgst, 2) : 0;


        if ($gst_type == 'igst') {
            if (in_array('igst_amount', $edited_field)) {
                $igst_amt = $docket_data['igst_amount'];
            }
        } else {
            if ($gst_type == 'cgst') {
                if (in_array('cgst_mount', $edited_field)) {
                    $cgst = $docket_data['cgst_amount'];
                }
                if (in_array('sgst_amount', $edited_field)) {
                    $sgst = $docket_data['sgst_amount'];
                }
            }
        }
        $response['igst_amount'] = $igst_amt;
        $response['cgst_amount'] = $cgst;
        $response['sgst_amount'] = $sgst;

        $grand_total = $non_taxable_amt + $taxable_amt + $igst_amt + $cgst + $sgst;
        $response['grand_total'] = $grand_total;

        return $response;
    }
    public function get_charge_amt($docket_data, $data_type = '')
    {

        $docket_id = isset($docket_data['docket_id']) ? $docket_data['docket_id'] : NULL;
        if ($docket_id > 0) {
            //$all_charge = get_all_charge(" AND status IN(1,2)", '*');;

            $docket_charge_res = $this->gm->get_data_list('docket_charges', array('docket_id' => $docket_id, 'status' => 1, 'charge_check' => 1), array(), array(), 'id,docket_id,charge_id');
            if (isset($docket_charge_res) && is_array($docket_charge_res) && count($docket_charge_res) > 0) {
                foreach ($docket_charge_res as $key => $value) {
                    $charge_id[$value['charge_id']] = $value['charge_id'];
                }
            }
        }

        if ($docket_id > 0) {
            if (isset($charge_id) && is_array($charge_id) && count($charge_id) > 0) {
                $qry = "SELECT * FROM charge_master WHERE status=1 OR id IN(" . implode(",", $charge_id) . ") ORDER BY name";
                $qry_exe = $this->db->query($qry);
                $charge_name_data = $qry_exe->result_array();
                if (isset($charge_name_data) && is_array($charge_name_data) && count($charge_name_data) > 0) {
                    foreach ($charge_name_data as $key => $value) {
                        $all_charge[strtolower(trim($value['id']))] = $value;
                    }
                }
            } else {
                $all_charge = get_all_charge(' AND status=1', '*');
            }
        } else if ($data_type == 'estimate_data') {
            $setting_data = get_all_app_setting(" AND module_name ='customer_estimate'");
            if (isset($setting_data['estimate_charge_id']) && $setting_data['estimate_charge_id'] != '') {
                $all_charge = get_all_charge(" AND status IN(1,2) AND id IN(" . $setting_data['estimate_charge_id'] . ")", '*');
            }
        }


        if (isset($all_charge) && is_array($all_charge) && count($all_charge) > 0) {

            $qry = "SELECT id,charge_id,charge_check FROM docket_charges WHERE status IN(1,2) 
            AND docket_id='" . $docket_id . "' AND billing_type=1";
            $qry_exe = $this->db->query($qry);
            $result = $qry_exe->result_array();

            if (isset($result) && is_array($result) && count($result) > 0) {
                foreach ($result as $ckey => $cvalue) {
                    $docket_charge[$cvalue['charge_id']] = $cvalue;
                }
            }

            //GET DOCKET ITEM
            $item_data = $this->gm->get_data_list('docket_items', array('docket_id' => $docket_id, 'status' => 1), array(), array('box_no' => 'asc'), '*');
            if (isset($item_data) && is_array($item_data) && count($item_data) > 0) {
                foreach ($item_data as $ikey => $ivalue) {
                    $item_len[] = $ivalue['item_length'];
                    $item_wid[] = $ivalue['item_width'];
                    $item_hei[] = $ivalue['item_height'];
                    $item_act_wt[] = $ivalue['actual_wt'];
                }
            }

            if (isset($docket_data['item']) && is_array($docket_data['item']) && count($docket_data['item']) > 0) {
                foreach ($docket_data['item'] as $ikey => $ivalue) {
                    $item_char_wt[] = $ivalue['chargeable_wt'];
                    $item_vol_wt[] = $ivalue['volumetric_wt'];
                }
            }


            if (isset($all_charge) && is_array($all_charge) && count($all_charge) > 0) {
                foreach ($all_charge as $key => $value) {
                    $get_charge = 2;
                    if (isset($docket_charge[$value['id']])) {
                        if ($docket_charge[$value['id']]['charge_check'] == 1) {
                            $get_charge = 1;
                        } else {
                            $get_charge = 2;
                        }
                    } else {
                        if ($value['is_default'] == 1) {
                            $get_charge = 1;
                        }
                    }


                    //DONT GET MANUAL CHARGE
                    if ($value['is_manual'] == 1) {
                        $get_charge = 2;
                    }

                    if ($get_charge == 2) {
                        $charge_amount[$value['id']]['rate_mod_id'] = 0;
                        $charge_amount[$value['id']]['rate_charge_amt'] = 0;
                        $charge_amount[$value['id']]['charge_check'] = 2;
                    } else {
                        $ajax_data = array(
                            'customer_id' => isset($docket_data['customer_id']) ? $docket_data['customer_id'] : 0,
                            'product_id' => isset($docket_data['product_id']) ? $docket_data['product_id'] : 0,
                            'vendor_id' => isset($docket_data['vendor_id']) ? $docket_data['vendor_id'] : 0,
                            'co_vendor_id' => isset($docket_data['co_vendor_id']) ? $docket_data['co_vendor_id'] : 0,
                            'destination_id' => isset($docket_data['destination_id']) ? $docket_data['destination_id'] : 0,
                            'dest_zone_id' => isset($docket_data['dest_zone_id']) ? $docket_data['dest_zone_id'] : 0,
                            'dest_zone_service_type' => isset($docket_data['dest_zone_service_type']) ? $docket_data['dest_zone_service_type'] : 0,
                            'origin_id' => isset($docket_data['origin_id']) ? $docket_data['origin_id'] : 0,
                            'ori_zone_id' => isset($docket_data['ori_zone_id']) ? $docket_data['ori_zone_id'] : 0,
                            'ori_zone_service_type' => isset($docket_data['ori_zone_service_type']) ? $docket_data['ori_zone_service_type'] : 0,
                            'ori_hub_id' => isset($docket_data['ori_hub_id']) ? $docket_data['ori_hub_id'] : 0,
                            'dest_hub_id' => isset($docket_data['dest_hub_id']) ? $docket_data['dest_hub_id'] : 0,
                            'booking_date' => isset($docket_data['booking_date']) ? $docket_data['booking_date'] : 0,
                            'freight_amount' => isset($docket_data['freight_amount']) ? $docket_data['freight_amount'] : 0,
                            'fsc_amount' => isset($docket_data['fsc_amount']) ? $docket_data['fsc_amount'] : 0,
                            'shipment_value' => isset($docket_data['shipment_value']) ? $docket_data['shipment_value'] : 0,
                            'chargeable_wt' => isset($docket_data['chargeable_wt']) ? $docket_data['chargeable_wt'] : 0,
                            'actual_wt' => isset($docket_data['actual_wt']) ? $docket_data['actual_wt'] : 0,
                            'volumetric_wt' => isset($docket_data['volumetric_wt']) ? $docket_data['volumetric_wt'] : 0,
                            'total_pcs' => isset($docket_data['total_pcs']) ? $docket_data['total_pcs'] : 0,
                            'con_pincode' => isset($docket_data['con_pincode']) ? $docket_data['con_pincode'] : '',
                            'charge_id' => $value['id'],
                            'charge_type' => 3,
                            'act_wt' => isset($item_act_wt) ?  $item_act_wt : array(),
                            'char_wt' => isset($item_char_wt) ?  $item_char_wt : array(),
                            'vol_wt' => isset($item_vol_wt) ?  $item_vol_wt : array(),
                            'dim_len' => isset($item_len) ?  $item_len : array(),
                            'dim_wid' => isset($item_wid) ?  $item_wid : array(),
                            'dim_hei' => isset($item_hei) ?  $item_hei : array(),
                        );
                        $charge_data = $this->generic_detail->get_sales_charge($ajax_data);
                        if (isset($charge_data['id'])) {
                            $charge_amount[$value['id']]['rate_mod_id'] = $charge_data['id'];
                            $charge_amount[$value['id']]['freight_fsc_per'] = $charge_data['freight_fsc_per'];
                            $charge_amount[$value['id']]['rate_charge_amt'] = round($charge_data['rate_amt'], 2);
                            $charge_amount[$value['id']]['charge_check'] = 1;
                        }
                    }
                }
            }
        }

        return $charge_amount;
    }
    public function calculate_freight($docket_data)
    {
        $response = array();

        $ajax_data = array(
            'customer_contract_id' => isset($docket_data['customer_contract_id']) ? $docket_data['customer_contract_id'] : 0,
            'chargeable_wt_total' => isset($docket_data['chargeable_wt']) ? $docket_data['chargeable_wt'] : 0,
            'total_pcs' => isset($docket_data['total_pcs']) ? $docket_data['total_pcs'] : 0,
            'consignor_wt' => isset($docket_data['consignor_wt']) ? $docket_data['consignor_wt'] : 0,
            'vendor_id' => isset($docket_data['vendor_id']) ? $docket_data['vendor_id'] : 0,
        );
        $freight_data = $this->generic_detail->get_freight_amount($ajax_data);
        $response['freight_amount'] = isset($freight_data['freight_amt']) ? round($freight_data['freight_amt'], 2) : 0;
        $response['chargeable_wt'] = isset($freight_data['round_ch_wt']) && $freight_data['round_ch_wt'] > 0 ? $freight_data['round_ch_wt'] : $docket_data['chargeable_wt'];
        return  $response;
    }

    public function get_sales_fsc($docket_data)
    {
        $response = array();
        $ajax_data = array(
            'customer_id' => isset($docket_data['customer_id']) ? $docket_data['customer_id'] : 0,
            'vendor_id' => isset($docket_data['vendor_id']) ? $docket_data['vendor_id'] : 0,
            'co_vendor_id' => isset($docket_data['co_vendor_id']) ? $docket_data['co_vendor_id'] : 0,
            'booking_date' => isset($docket_data['booking_date']) ? $docket_data['booking_date'] : 0,
            'company_id' => isset($docket_data['company_id']) ? $docket_data['company_id'] : 0,
        );


        $sales_fsc_data = $this->docket->get_sales_fsc(0, $ajax_data);

        $response['fsc_amount'] = 0;
        $fsc_chargeable = 0;
        if (isset($sales_fsc_data['id'])) {
            if (isset($sales_fsc_data['customer_fsc_apply']) && $sales_fsc_data['customer_fsc_apply'] == 1) {
                if ($sales_fsc_data['fsc_percentage'] > 0) {
                    $freight_amount = isset($docket_data['freight_amount']) ? $docket_data['freight_amount'] : 0;
                    $fsc_chargeable += $freight_amount;


                    $adjustment_amount = isset($docket_data['adjustment_amount']) ? $docket_data['adjustment_amount'] : 0;
                    $fsc_chargeable += $adjustment_amount;


                    $all_charge = get_all_charge(" AND status IN(1,2)", '*');

                    if (isset($docket_data['charge']) && is_array($docket_data['charge']) && count($docket_data['charge']) > 0) {
                        foreach ($docket_data['charge'] as $ch_key => $ch_value) {
                            if (isset($all_charge[$ch_key]) && $all_charge[$ch_key]['is_fsc_apply'] == 1) {
                                $fsc_chargeable +=  $ch_value['charge_amount'];
                            }
                        }
                    }

                    if ($fsc_chargeable > 0) {
                        $fsc_amount = ($fsc_chargeable * $sales_fsc_data['fsc_percentage']) / 100;
                        $response['fsc_amount'] = round($fsc_amount, 2);
                    }
                } else {
                    $response['fsc_amount'] = 0;
                }
            }
        } else {
            $response['fsc_amount'] = 0;
        }

        if (isset($response['fsc_amount'])) {
            $response['fsc_amount'] = round($response['fsc_amount'], 2);
        }
        $response['gst_per'] = isset($sales_fsc_data['service_gst_per']) ? $sales_fsc_data['service_gst_per'] : 0;
        $response['service_type'] = isset($sales_fsc_data['service_type']) ? $sales_fsc_data['service_type'] : 0;
        $response['gst_type'] = isset($sales_fsc_data['gst_type']) ? $sales_fsc_data['gst_type'] : 0;
        $response['fsc_per'] = isset($sales_fsc_data['fsc_percentage']) ? $sales_fsc_data['fsc_percentage'] : 0;
        $response['customer_fsc_apply'] = isset($sales_fsc_data['customer_fsc_apply']) ? $sales_fsc_data['customer_fsc_apply'] : 2;
        $response['id'] = isset($sales_fsc_data['id']) ? $sales_fsc_data['id'] : 0;

        return  $response;
    }



    public function refresh_purchase()
    {
        $this->load->model('Global_model', 'gm');
        $qry = "SELECT id,module_id FROM refresh_log WHERE status IN(1,2) AND refresh_status=1 AND module_type=1 AND refresh_type='refresh_purchase_billing' LIMIT 500";
        $qry_exe = $this->db->query($qry);
        $queue_data = $qry_exe->result_array();

        if (isset($queue_data) && is_array($queue_data) && count($queue_data) > 0) {
            foreach ($queue_data as $qkey => $qvalue) {
                //MARK PROCESSED
                $updateq = "UPDATE refresh_log SET refresh_status=2,processed_datetime='" . date('Y-m-d H:i:s') . "' WHERE id='" . $qvalue['id'] . "'";
                $this->db->query($updateq);

                //REFRESH ONLY THOSE DOCKET WHOSE INVOICE RECO NOT MADE
                $invoiceq = "SELECT i.id FROM purchase_invoice i JOIN purchase_invoice_item im ON(i.id=im.purchase_invoice_id)
                WHERE i.status IN(1,2) AND im.status IN(1,2) AND im.docket_id='" . $qvalue['module_id'] . "'";
                $invoiceq_exe = $this->db->query($invoiceq);
                $invoice_data =  $invoiceq_exe->row_array();

                if (isset($invoice_data) && is_array($invoice_data) && count($invoice_data) > 0) {
                    //MARK DONE
                    $updateq = "UPDATE refresh_log SET refresh_status=4,processed_datetime='" . date('Y-m-d H:i:s') . "',refresh_msg='locked_docket' WHERE id='" . $qvalue['id'] . "'";
                    $this->db->query($updateq);
                } else {
                    $this->update_docket_purchase($qvalue['module_id']);
                    //MARK DONE
                    $updateq = "UPDATE refresh_log SET refresh_status=3,processed_datetime='" . date('Y-m-d H:i:s') . "' WHERE id='" . $qvalue['id'] . "'";
                    $this->db->query($updateq);
                }
            }
        }
    }

    public function update_docket_purchase($docket_id = 0, $bill_old_data = '')
    {


        $docket_update_data = array();
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');

        $this->load->module('generic_detail');
        $this->load->module('docket');

        $setting = get_all_app_setting(" AND module_name IN('master','docket','main','general')");
        $old_column = 'awb_no,customer_id,origin_id,destination_id,product_id,booking_date,vendor_id,'
            . 'co_vendor_id,forwarding_no,content,eway_bill,invoice_date,invoice_no,customer_contract_id,'
            . 'customer_contract_tat,cft_contract_id,remarks,status_id,actual_wt,volumetric_wt,consignor_wt,'
            . 'add_wt,chargeable_wt,total_pcs,dispatch_type,shipment_priority,company_id,
project_id,forwarding_no_2,reference_no,reference_name,shipment_value,shipment_currency_id,
dispatch_date,dispatch_time,courier_dispatch_date,courier_dispatch_time,instructions,payment_type,ori_hub_id,
dest_hub_id,challan_no,commit_id,cod_amount,insurance_amount';
        $docket_old_data = $this->gm->get_selected_record('docket', $old_column, $where = array('id' => $docket_id, 'status !=' => 3), array());

        $docket_extra_column = 'po_number,brand_id,pickup_boy_id,cod_status,inscan_date,paid_amount,balance_amount,entry_number,total_quantity,forwarder_id,estimate_no,license_location_id,token_number,project_name';
        $extra_old_data = $this->gm->get_selected_record('docket_extra_field', $docket_extra_column, array('docket_id' => $docket_id, 'status' => 1), array());

        $shipper_consi_col = 'code,name,company_name,address1,address2,address3,pincode,city,state,country,contact_no,email_id,
dob,gstin_type,gstin_no,doc_path';
        $shipper_old_data = $this->gm->get_selected_record('docket_shipper', $shipper_consi_col, array('docket_id' => $docket_id, 'status' => 1), array());
        $consignee_old_data = $this->gm->get_selected_record('docket_consignee', $shipper_consi_col, array('docket_id' => $docket_id, 'status' => 1), array());


        if (isset($bill_old_data) && is_array($bill_old_data) && count($bill_old_data) > 0) {
            $pur_billing_old_data = $bill_old_data;
        } else {
            $pur_billing_col = 'freight_amount,total_other_charge,fsc_amount,discount_per,discount_amount,freight_after_dis,'
                . 'sub_total,non_taxable_amt,taxable_amt,gst_per,igst_amount,cgst_amount,sgst_amount,grand_total';
            $pur_billing_old_data = $this->gm->get_selected_record('docket_purchase_billing', $pur_billing_col, array('docket_id' => $docket_id, 'status' => 1), array());
        }

        if (isset($pur_billing_old_data) && is_array($pur_billing_old_data) && count($pur_billing_old_data) > 0) {
        } else {
            $pur_billing_old_data = array();
        }
        $docketq = "SELECT d.customer_id,d.product_id,d.vendor_id,d.co_vendor_id,d.booking_date,
        d.origin_id,d.destination_id,shi.country as shi_country,con.country as con_country,
        shi.pincode as shi_pincode,con.pincode as con_pincode,shi.city as shi_city,con.city as con_city,
        shi.state as shi_state,con.state as con_state,
        bill.ori_zone_id,bill.dest_zone_id,d.chargeable_wt,d.shipment_priority,d.company_id,d.id as docket_id,
		d.shipment_value,bill.fsc_amount as pur_bill_fsc_amount,d.actual_wt,d.volumetric_wt,d.total_pcs,bill.discount_per,bill.is_gst_apply,
        bill.co_vendor_id as pur_co_vendor_id,d.ori_hub_id,d.dest_hub_id,bill.grand_total,bill.edi_discount_amount,
        bill.vendor_contract_id,d.status_id,bill.edited_field,bill.fsc_per,bill.igst_amount as igst_amount,
        bill.cgst_amount as cgst_amount,bill.sgst_amount as sgst_amount
        FROM docket d 
        JOIN docket_purchase_billing bill ON(d.id=bill.docket_id)
        LEFT OUTER JOIN docket_shipper shi ON(d.id=shi.docket_id AND shi.status IN(1,2))
        LEFT OUTER JOIN docket_consignee con ON(d.id=con.docket_id AND con.status IN(1,2))
        where d.status IN(1,2) AND d.id='" . $docket_id . "'  AND bill.status IN(1,2)";
        $docketq_exe = $this->db->query($docketq);
        $docket_data = $docketq_exe->row_array();

        if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {

            $bill_exist = $this->gm->get_selected_record('docket_purchase_billing', 'id,edited_field,freight_amount,vendor_id,co_vendor_id,product_id,actual_wt,volumetric_wt,chargeable_wt', $where = array('docket_id' => $docket_id, 'status=' => 1), array());

            $update_vendor = 1;
            if (isset($bill_exist) && is_array($bill_exist) && count($bill_exist) > 0) {
                $edited_field = explode(",", $bill_exist['edited_field']);
                if (in_array('edit_vendor_detail', $edited_field)) {
                    $update_vendor = 2;
                }
            }


            //GET PURCHASE CURRENCY
            $currency_id = 66; //INDIA
            if (isset($docket_data['co_vendor_id']) && $docket_data['co_vendor_id'] > 0) {
                //GET COMPANY FROM VENDOR
                $qry = "SELECT id,company_id FROM co_vendor WHERE status IN(1,2) AND id='" . $docket_data['co_vendor_id'] . "'";
                $qry_exe = $this->db->query($qry);
                $vendor_company_data = $qry_exe->row_array();

                if (isset($vendor_company_data['company_id']) && $vendor_company_data['company_id'] > 0) {
                    $qry = "SELECT id,country,tax_type FROM company_master WHERE status IN(1,2) AND id='" . $vendor_company_data['company_id'] . "'";
                    $qry_exe = $this->db->query($qry);
                    $company_country_data = $qry_exe->row_array();
                }

                if (isset($company_country_data) && is_array($company_country_data) && count($company_country_data) > 0) {
                    $purchase_company_id = $company_country_data['id'];
                    $purchase_tax_type = $company_country_data['tax_type'];

                    $country_name = strtolower(trim($company_country_data['country']));
                    if ($country_name == 'india') {
                        $currency_id = 66; //INDIA
                    } else {
                        $qry = "SELECT id,name,currency_code_id FROM country WHERE status IN(1,2) AND LOWER(name)='" .  $country_name . "'";
                        $qry_exe = $this->db->query($qry);
                        $country_data = $qry_exe->row_array();
                        if (isset($country_data) && is_array($country_data) && count($country_data) > 0) {
                            $currency_id = $country_data['currency_code_id'];
                        }
                    }
                }
            }



            $docket_update_data['purchase_billing']['company_id'] = isset($purchase_company_id) ? $purchase_company_id : 0;
            $docket_update_data['purchase_billing']['company_tax_type'] = isset($purchase_tax_type) ? $purchase_tax_type : 1;


            $docket_data['company_id'] = isset($purchase_company_id) ? $purchase_company_id : 0;
            $docket_update_data['docket_extra']['purchase_currency_id'] = $currency_id;
            $docket_data['company_tax_type'] = isset($purchase_tax_type) ? $purchase_tax_type : 1;
            if ($update_vendor == 1) {

                $pur_docket_item = $this->gm->get_data_list('docket_items', array('docket_id' => $docket_id, 'status' => 1), array(), array(), '*');


                $purchase_actual = 0;
                $purchase_volumetric = 0;
                $purchase_chargeable = 0;

                if (isset($pur_docket_item) && is_array($pur_docket_item) && count($pur_docket_item) > 0) {
                    foreach ($pur_docket_item as $di_key => $di_value) {
                        $purchase_actual += $di_value['actual_wt'];
                        $purchase_volumetric += $di_value['volumetric_wt'];
                    }
                } else {
                    $purchase_actual = $docket_data['actual_wt'];
                    $purchase_volumetric = $docket_data['volumetric_wt'];
                }


                $docket_edited_field = array();
                $docket_extra = $this->gm->get_selected_record('docket_extra_field', 'id,docket_edit_field', $where = array('docket_id' => $docket_id, 'status=' => 1), array());
                if (isset($docket_extra) && is_array($docket_extra) && count($docket_extra) > 0) {
                    $docket_edited_field = explode(",", $docket_extra['docket_edit_field']);
                }
                $docket_edited_data = $this->gm->get_selected_record('docket', 'id,actual_wt,volumetric_wt,chargeable_wt', $where = array('id' => $docket_id, 'status=' => 1), array());
                if (in_array('edit_actual_wt', $docket_edited_field)) {
                    $purchase_actual = $docket_edited_data['actual_wt'];
                }
                if (in_array('edit_volume_wt', $docket_edited_field)) {
                    $purchase_volumetric = $docket_edited_data['volumetric_wt'];
                }


                $purchase_vendor_update = array(
                    'product_id' => $docket_data['product_id'],
                    'vendor_id' => $docket_data['vendor_id'],
                    'co_vendor_id' => $docket_data['co_vendor_id'],
                    'actual_wt' => $purchase_actual,
                    'volumetric_wt' => $purchase_volumetric,
                    'chargeable_wt' => $purchase_actual > $purchase_volumetric ? $purchase_actual : $purchase_volumetric,
                );


                $docket_data['actual_wt'] = $purchase_vendor_update['actual_wt'];
                $docket_data['volumetric_wt'] = $purchase_vendor_update['volumetric_wt'];
                $docket_data['chargeable_wt'] = $purchase_vendor_update['chargeable_wt'];

                $this->gm->update('docket_purchase_billing', $purchase_vendor_update, '', array('docket_id' => $docket_id));
            } else {
                $purchase_vendor_update = array(
                    'total_pcs' => $docket_data['total_pcs'],
                );
                $this->gm->update('docket_purchase_billing', $purchase_vendor_update, '', array('docket_id' => $docket_id));
                $docket_data['product_id'] = $bill_exist['product_id'];
                $docket_data['vendor_id'] = $bill_exist['vendor_id'];
                $docket_data['co_vendor_id'] = $bill_exist['co_vendor_id'];
                $docket_data['actual_wt'] = $bill_exist['actual_wt'];
                $docket_data['volumetric_wt'] = $bill_exist['volumetric_wt'];
                $docket_data['chargeable_wt'] = $bill_exist['chargeable_wt'];
            }



            //GET DEST ZONE
            $ajax_data = array(
                'customer_id' => isset($docket_data['customer_id']) ? $docket_data['customer_id'] : 0,
                'vendor_id' => isset($docket_data['vendor_id']) ? $docket_data['vendor_id'] : 0,
                'co_vendor_id' => isset($docket_data['co_vendor_id']) ? $docket_data['co_vendor_id'] : 0,
                'origin_id' => isset($docket_data['origin_id']) ? $docket_data['origin_id'] : 0,
                'destination_id' => isset($docket_data['destination_id']) ? $docket_data['destination_id'] : 0,
                'shipper_country' => isset($docket_data['shi_country']) ? $docket_data['shi_country'] : 0,
                'consignee_country' => isset($docket_data['con_country']) ? $docket_data['con_country'] : 0,
                'shipper_pincode' => isset($docket_data['shi_pincode']) ? $docket_data['shi_pincode'] : 0,
                'consignee_pincode' => isset($docket_data['con_pincode']) ? $docket_data['con_pincode'] : 0,
                'type' => 2,
                'booking_date' => isset($docket_data['booking_date']) ? $docket_data['booking_date'] : '',
                'shipper_state' => isset($docket_data['shi_state']) ? $docket_data['shi_state'] : 0,
                'consignee_state' => isset($docket_data['con_state']) ? $docket_data['con_state'] : 0,

            );
            $dest_zone_data =  $this->generic_detail->get_purchase_location_zone($ajax_data);
            // echo '<pre>';
            // print_r($dest_zone_data);
            // exit;

            if ($dest_zone_data['dest_zone_id'] > 0) {
                $purchase_vendor_update = array(
                    'dest_zone_id' => $dest_zone_data['dest_zone_id'],
                    'dest_zone_service_type' => $dest_zone_data['dest_zone_service_type'],
                );
                $this->gm->update('docket_purchase_billing', $purchase_vendor_update, '', array('docket_id' => $docket_id));
                $docket_data['dest_zone_id'] = $dest_zone_data['dest_zone_id'];
                $docket_data['dest_zone_service_type'] = $dest_zone_data['dest_zone_service_type'];
            }

            if ($dest_zone_data['ori_zone_id'] > 0) {
                $purchase_vendor_update = array(
                    'ori_zone_id' => $dest_zone_data['ori_zone_id'],
                    'ori_zone_service_type' => $dest_zone_data['ori_zone_service_type'],
                );
                $this->gm->update('docket_purchase_billing', $purchase_vendor_update, '', array('docket_id' => $docket_id));
                $docket_data['ori_zone_id'] = $dest_zone_data['ori_zone_id'];
                $docket_data['ori_zone_service_type'] = $dest_zone_data['ori_zone_service_type'];
            }

            $cft_data =  $this->get_purchase_cft_contract($docket_data);



            if (isset($cft_data) && is_array($cft_data) && count($cft_data) > 0) {
                foreach ($cft_data as $ckey => $cvalue) {
                    $docket_update_data['purchase_billing'][$ckey] = $cvalue;
                    $docket_data[$ckey] = $cvalue;
                }
            }




            $weight_data =  $this->calculate_vendor_wt($docket_data);

            if (isset($weight_data['purchase_billing']['actual_wt'])) {
                $docket_data['actual_wt'] = $weight_data['purchase_billing']['actual_wt'];
                $docket_update_data['purchase_billing']['actual_wt'] = $weight_data['purchase_billing']['actual_wt'];
            }
            if (isset($weight_data['purchase_billing']['volumetric_wt'])) {
                $docket_data['volumetric_wt'] = $weight_data['purchase_billing']['volumetric_wt'];
                $docket_update_data['purchase_billing']['volumetric_wt'] = $weight_data['purchase_billing']['volumetric_wt'];
            }
            if (isset($weight_data['purchase_billing']['chargeable_wt'])) {
                $docket_data['chargeable_wt'] = $weight_data['purchase_billing']['chargeable_wt'];
                $docket_update_data['purchase_billing']['chargeable_wt'] = $weight_data['purchase_billing']['chargeable_wt'];
            }




            if (isset($weight_data['item']) && is_array($weight_data['item']) && count($weight_data['item']) > 0) {
                $docket_update_data['item'] = $weight_data['item'];
                $docket_data['item'] = $weight_data['item'];
            }


            if (isset($cft_data['vendor_contract_id']) && $cft_data['vendor_contract_id'] > 0) {
                $freight_round_data = $this->calculate_purchase_freight($docket_data);
                //ROUND OFF CHARGEABLE WT IF CONTRACT FOUND
                $freight_data['freight_amount'] = $freight_round_data['freight_amount'];
                $freight_data['chargeable_wt'] = $freight_round_data['chargeable_wt'];
                $docket_update_data['purchase_billing']['chargeable_wt'] = $freight_round_data['chargeable_wt'];
                $docket_data['chargeable_wt'] = $freight_round_data['chargeable_wt'];
            } else {
                $freight_data['chargeable_wt'] = $docket_data['chargeable_wt'];
            }



            if (!in_array('grand_total', $edited_field)) {
                $editable_freight = 2;
                if (isset($bill_exist) && is_array($bill_exist) && count($bill_exist) > 0) {
                    $edited_field = explode(",", $bill_exist['edited_field']);
                    if (in_array('freight_amount', $edited_field)) {
                        $freight_data['freight_amount'] = $bill_exist['freight_amount'];
                        $editable_freight = 1;
                    }
                }
                if (in_array('edit_vendor_detail', $edited_field)) {
                    $freight_data['chargeable_wt'] = $bill_exist['chargeable_wt'];
                }
                $docket_update_data['purchase_billing']['freight_amount'] =  $freight_data['freight_amount'];

                $docket_data['freight_amount'] = $freight_data['freight_amount'];

                if ($editable_freight == 2) {
                    $docket_update_data['purchase_billing']['chargeable_wt'] =  $freight_data['chargeable_wt'];
                    $docket_data['chargeable_wt'] = $freight_data['chargeable_wt'];
                }


                //CALCULATE OTHER CHARGE
                $charge_amount_data = $this->get_purchase_charge_amt($docket_data);

                $all_charge = get_all_charge(" AND status IN(1,2)", '*');

                //get dokcet manual charges
                $purchase_charge = $this->gm->get_data_list('docket_charges', array('docket_id' => $docket_id, 'status' => 1, 'billing_type' => 2, 'charge_check' => 1), array(), array(), 'id,charge_id,charge_amount,rate_mod_id,charge_check,billing_type');
                if (isset($purchase_charge) && is_array($purchase_charge) && count($purchase_charge) > 0) {
                    foreach ($purchase_charge as $okey => $ovalue) {
                        $docket_purchase_charge[$ovalue['charge_id']] = $ovalue;
                    }
                }




                if (isset($charge_amount_data) && is_array($charge_amount_data) && count($charge_amount_data) > 0) {
                    foreach ($charge_amount_data as $ch_key => $ch_value) {
                        if (isset($all_charge[$ch_key]) && $all_charge[$ch_key]['is_manual'] == 1) {
                            $docket_update_data['charge'][$ch_key] = array(
                                'rate_mod_id' => isset($docket_purchase_charge[$ch_key]) ? $docket_purchase_charge[$ch_key]['rate_mod_id'] : 0,
                                'charge_amount' => isset($docket_purchase_charge[$ch_key]) ? $docket_purchase_charge[$ch_key]['charge_amount'] : 0,
                                'charge_check' => isset($docket_purchase_charges[$ch_key]) ? $docket_purchase_charges[$ch_key]['charge_check'] : 2,
                            );
                        } else {
                            $docket_update_data['charge'][$ch_key] = array(
                                'rate_mod_id' => $ch_value['rate_mod_id'],
                                'charge_amount' => $ch_value['rate_charge_amt'],
                                'charge_check' => $ch_value['charge_check'],
                            );
                        }

                        $docket_data['charge'][$ch_key] = $docket_update_data['charge'][$ch_key];

                        if ($ch_value['freight_fsc_per'] > 0 && $all_charge[$ch_key]['is_fsc_apply'] == 2) {
                            $fsc_rate_found = 1;
                        }
                    }
                }

                $fsc_amt_data = $this->get_purchase_fsc($docket_data);

                if (in_array('pur_fsc_amount', $edited_field)) {
                    $fsc_amt_data['fsc_amount'] = $docket_data['pur_bill_fsc_amount'];
                }

                if (isset($fsc_amt_data['fsc_amount'])) {
                    $fsc_amt_data['fsc_amount'] = round($fsc_amt_data['fsc_amount'], 2);
                }

                if (isset($bill_exist) && is_array($bill_exist) && count($bill_exist) > 0) {
                    $edited_field = explode(",", $bill_exist['edited_field']);
                    if (!in_array('pur_gst_applicable', $edited_field)) {
                        $docket_update_data['purchase_billing']['is_gst_apply'] = isset($fsc_amt_data['is_gst_apply']) ? $fsc_amt_data['is_gst_apply'] : 2;
                        $docket_data['is_gst_apply'] = isset($fsc_amt_data['is_gst_apply']) ? $fsc_amt_data['is_gst_apply'] : 2;
                    }
                } else {
                    $docket_update_data['purchase_billing']['is_gst_apply'] = isset($fsc_amt_data['is_gst_apply']) ? $fsc_amt_data['is_gst_apply'] : 2;
                    $docket_data['is_gst_apply'] = isset($fsc_amt_data['is_gst_apply']) ? $fsc_amt_data['is_gst_apply'] : 2;
                }


                if (isset($fsc_rate_found) && $fsc_rate_found == 1) {
                    $docket_data['fsc_amount'] = isset($fsc_amt_data['fsc_amount']) ? $fsc_amt_data['fsc_amount'] : '';
                    $charge_amount_data = $this->get_purchase_charge_amt($docket_data);
                    $all_charge = get_all_charge(" AND status IN(1,2)", '*');

                    //get dokcet manual charges
                    $purchase_charge = $this->gm->get_data_list('docket_charges', array('docket_id' => $docket_id, 'status' => 1, 'billing_type' => 2, 'charge_check' => 1), array(), array(), 'id,charge_id,charge_amount,rate_mod_id,charge_check,billing_type');
                    if (isset($purchase_charge) && is_array($purchase_charge) && count($purchase_charge) > 0) {
                        foreach ($purchase_charge as $okey => $ovalue) {
                            $docket_purchase_charge[$ovalue['charge_id']] = $ovalue;
                        }
                    }




                    if (isset($charge_amount_data) && is_array($charge_amount_data) && count($charge_amount_data) > 0) {
                        foreach ($charge_amount_data as $ch_key => $ch_value) {
                            if (isset($all_charge[$ch_key]) && $all_charge[$ch_key]['is_manual'] == 1) {
                                $docket_update_data['charge'][$ch_key] = array(
                                    'rate_mod_id' => isset($docket_purchase_charge[$ch_key]) ? $docket_purchase_charge[$ch_key]['rate_mod_id'] : 0,
                                    'charge_amount' => isset($docket_purchase_charge[$ch_key]) ? $docket_purchase_charge[$ch_key]['charge_amount'] : 0,
                                    'charge_check' => isset($docket_purchase_charge[$ch_key]) ? $docket_purchase_charge[$ch_key]['charge_check'] : 2,
                                );
                            } else {
                                $docket_update_data['charge'][$ch_key] = array(
                                    'rate_mod_id' => $ch_value['rate_mod_id'],
                                    'charge_amount' => $ch_value['rate_charge_amt'],
                                    'charge_check' => $ch_value['charge_check'],
                                );
                            }

                            $docket_data['charge'][$ch_key] = $docket_update_data['charge'][$ch_key];
                        }
                    }
                }
                $docket_update_data['purchase_billing']['fsc_amount'] = isset($fsc_amt_data['fsc_amount']) ? $fsc_amt_data['fsc_amount'] : '';
                $docket_update_data['purchase_billing']['gst_per'] = isset($fsc_amt_data['gst_per']) ? $fsc_amt_data['gst_per'] : '';
                $docket_update_data['purchase_billing']['fsc_per'] = isset($fsc_amt_data['fsc_percentage']) ? $fsc_amt_data['fsc_percentage'] : '';


                $docket_data['fsc_amount'] = isset($fsc_amt_data['fsc_amount']) ? $fsc_amt_data['fsc_amount'] : '';
                $docket_data['service_type'] = isset($fsc_amt_data['service_type']) ? $fsc_amt_data['service_type'] : '';
                $docket_data['gst_type'] = isset($fsc_amt_data['gst_type']) ? $fsc_amt_data['gst_type'] : '';
                $docket_data['gst_per'] = isset($fsc_amt_data['gst_per']) ? $fsc_amt_data['gst_per'] : '';
                $docket_data['fsc_per'] = isset($fsc_amt_data['fsc_percentage']) ? $fsc_amt_data['fsc_percentage'] : '';

                $total_data =  $this->calculate_purchase_other_charge($docket_data);

                if (isset($total_data) && is_array($total_data) && count($total_data) > 0) {
                    foreach ($total_data as $tkey => $tvalue) {
                        $docket_update_data['purchase_billing'][$tkey] = $tvalue;
                    }
                }
            } else {
                //REVERSE CALCULATION
                $edited_field = array();
                if (isset($bill_exist['edited_field']) && $bill_exist['edited_field'] != '') {
                    $edited_field = explode(",", $bill_exist['edited_field']);
                }
                $grand_total = isset($docket_data['grand_total']) ? $docket_data['grand_total'] : 0;

                $docket_update_data['purchase_billing']['grand_total'] = $grand_total;


                $fsc_amt_data = $this->get_purchase_fsc($docket_data);
                $docket_update_data['purchase_billing']['gst_per'] = isset($fsc_amt_data['gst_per']) ? $fsc_amt_data['gst_per'] : '';
                $docket_data['gst_per'] = isset($fsc_amt_data['gst_per']) ? $fsc_amt_data['gst_per'] : 0;
                $docket_data['gst_type'] = isset($fsc_amt_data['gst_type']) ? $fsc_amt_data['gst_type'] : '';
                //APPLY FSC ON TOTAL CHARGES
                $other_charge_fsc_total = 0;
                $other_charge_gst_total = 0;
                $fsc_amount = 0;
                $gst_amount = 0;
                $non_taxable_amt = 0;
                $other_charge_total = 0;
                //CALCULATE OTHER CHARGE
                $charge_amount_data = $this->get_purchase_charge_amt($docket_data);

                $all_charge = get_all_charge(" AND status IN(1,2)", '*');
                //get dokcet manual charges
                $sales_charge = $this->gm->get_data_list('docket_charges', array('docket_id' => $docket_id, 'status' => 1, 'billing_type' => 2, 'charge_check' => 1), array(), array(), 'id,charge_id,charge_amount,rate_mod_id,charge_check,billing_type');
                if (isset($sales_charge) && is_array($sales_charge) && count($sales_charge) > 0) {
                    foreach ($sales_charge as $okey => $ovalue) {
                        $docket_sales_charges[$ovalue['charge_id']] = $ovalue;
                    }
                }


                if (isset($charge_amount_data) && is_array($charge_amount_data) && count($charge_amount_data) > 0) {
                    foreach ($charge_amount_data as $ch_key => $ch_value) {
                        if (isset($all_charge[$ch_key])) {
                            $chrage_amt = 0;

                            if ($all_charge[$ch_key]['is_manual'] == 1) {
                                $docket_update_data['charge'][$ch_key] = array(
                                    'rate_mod_id' => isset($docket_sales_charges[$ch_key]) ? $docket_sales_charges[$ch_key]['rate_mod_id'] : 0,
                                    'charge_amount' => isset($docket_sales_charges[$ch_key]) ? $docket_sales_charges[$ch_key]['charge_amount'] : 0,
                                    'charge_check' => $ch_value['charge_check'],
                                );
                            } else {
                                $docket_update_data['charge'][$ch_key] = array(
                                    'rate_mod_id' => $ch_value['rate_mod_id'],
                                    'charge_amount' => $ch_value['rate_charge_amt'],
                                    'charge_check' => $ch_value['charge_check'],
                                );
                            }

                            if ($all_charge[$ch_key]['is_manual'] == 1) {
                                $chrage_amt = isset($docket_sales_charges[$ch_key]) ? $docket_sales_charges[$ch_key]['charge_amount'] : 0;
                            } else {
                                $chrage_amt = $ch_value['rate_charge_amt'];
                            }
                            $other_charge_total = $other_charge_total + $chrage_amt;

                            if ($all_charge[$ch_key]['is_fsc_apply'] == 1) {
                                $other_charge_fsc_total = $other_charge_fsc_total + $chrage_amt;
                            }

                            if ($all_charge[$ch_key]['is_gst_apply'] == 1) {
                                if ($docket_data['is_gst_apply'] == 1) {
                                    $other_charge_gst_total = $other_charge_gst_total + $chrage_amt;
                                } else {
                                    $non_taxable_amt = $non_taxable_amt + $chrage_amt;
                                }
                            } else {
                                $non_taxable_amt = $non_taxable_amt + $chrage_amt;
                            }

                            $docket_data['charge'][$ch_key] = $docket_update_data['charge'][$ch_key];
                        }
                    }
                }

                $remaining_amt = $grand_total - $other_charge_total;
                if ($docket_data['is_gst_apply'] != 1) {
                    $non_taxable_amt = $non_taxable_amt + $remaining_amt;
                }
                $other_charge_total = round($other_charge_total, 2);
                $other_charge_total = (float)$other_charge_total;
                $non_taxable_amt = round($non_taxable_amt, 2);
                $non_taxable_amt = (float)$non_taxable_amt;

                $taxable_amt = $grand_total - $non_taxable_amt;
                if ($docket_data['gst_per'] == 0) {
                    $non_taxable_amt = $non_taxable_amt + $taxable_amt;
                    $taxable_amt = 0;
                }


                $taxable_amt = round($taxable_amt, 2);
                $taxable_amt = (float)$taxable_amt;


                $tax_amount = ($taxable_amt / (100 + $docket_data['gst_per'])) * $docket_data['gst_per'];
                $tax_amount = round($tax_amount, 2);
                $tax_amount = (float)$tax_amount;

                $p12 = ($grand_total - $non_taxable_amt) - $tax_amount;
                $sub_total = $non_taxable_amt + $p12;

                $sub_total = round($sub_total, 2);
                $sub_total = (float)$sub_total;
                $docket_data['fsc_per'] = isset($fsc_amt_data['fsc_per']) ? $fsc_amt_data['fsc_per'] : '';

                $fsc_charge_per = (100 + $docket_data['fsc_per']) / 100;
                $fsc_charge_per = (float)$fsc_charge_per;

                $fsc_amount = $sub_total / $fsc_charge_per;

                $fsc_amount = round($fsc_amount, 2);
                $fsc_amount = (float)$fsc_amount;


                if (in_array('pur_fsc_amount', $edited_field)) {
                    $total_fsc = $docket_data['pur_bill_fsc_amount'];
                } else {
                    $total_fsc = $sub_total - $fsc_amount;
                }
                $total_fsc = round($total_fsc, 2);
                $total_fsc = (float)$total_fsc;



                $p2 = $other_charge_total + $total_fsc + $tax_amount;

                $freight = $grand_total - $p2;
                $freight = round($freight, 2);
                $freight = (float)$freight;

                $docket_update_data['purchase_billing']['freight_amount'] = $freight;
                $docket_data['freight_amount'] = $freight;

                if (isset($fsc_amt_data['id'])) {
                    $docket_update_data['purchase_billing']['purchase_fsc_id'] = $fsc_amt_data['id'];
                    $docket_update_data['purchase_billing']['fsc_amount'] = $total_fsc;
                } else {
                    $docket_update_data['purchase_billing']['purchase_fsc_id'] = 0;
                    $docket_update_data['purchase_billing']['fsc_amount'] = 0;
                }

                $FscAmt = $docket_update_data['purchase_billing']['fsc_amount'];
                if (isset($docket_data['fsc_per'])) {
                    $docket_update_data['purchase_billing']['fsc_per'] = isset($fsc_amt_data['fsc_per']) ? $fsc_amt_data['fsc_per'] : '';
                    $docket_data['fsc_per'] = isset($fsc_amt_data['fsc_per']) ? $fsc_amt_data['fsc_per'] : '';
                }

                $docket_data['fsc_amount'] = $docket_update_data['purchase_billing']['fsc_amount'];
                $total_data =  $this->calculate_purchase_other_charge($docket_data);
                if (isset($total_data) && is_array($total_data) && count($total_data) > 0) {
                    foreach ($total_data as $tkey => $tvalue) {
                        $docket_update_data['purchase_billing'][$tkey] = $tvalue;
                    }
                }
            }
            $docket_update_data['docket']['purchase_billing_save'] = 1;



            if (isset($_GET['test'])) {
                echo '<pre>';
                print_r($docket_update_data);
                exit;
            }
            if (isset($docket_update_data['docket_extra']) && is_array($docket_update_data['docket_extra']) && count($docket_update_data['docket_extra']) > 0) {
                $this->gm->update('docket_extra_field', $docket_update_data['docket_extra'], '', array('docket_id' => $docket_id));
            }

            if (isset($docket_update_data['item']) && is_array($docket_update_data['item']) && count($docket_update_data['item']) > 0) {
                foreach ($docket_update_data['item'] as $ikey => $ivalue) {
                    $this->gm->update('vendor_docket_item', $ivalue, '', array('id' => $ikey));
                }
            }

            if (isset($docket_update_data['docket']) && is_array($docket_update_data['docket']) && count($docket_update_data['docket']) > 0) {
                $this->gm->update('docket', $docket_update_data['docket'], '', array('id' => $docket_id));
            }

            if (isset($docket_data['status_id']) && $docket_data['status_id'] == 3) {
                //FOR VOID DOCKET SET ALL VALUE TO 0
                $sales_val_arr = array(
                    'freight_amount', 'total_other_charge', 'fsc_amount',
                    'discount_per', 'discount_amount', 'freight_after_dis', 'sub_total', 'non_taxable_amt',
                    'taxable_amt', 'gst_per', 'igst_amount', 'cgst_amount', 'sgst_amount', 'grand_total',
                    'fsc_per'
                );
                foreach ($sales_val_arr as $skey => $svalue) {
                    $docket_update_data['purchase_billing'][$svalue] = 0;
                }
            }

            if (isset($docket_update_data['purchase_billing']) && is_array($docket_update_data['purchase_billing']) && count($docket_update_data['purchase_billing']) > 0) {
                $this->gm->update('docket_purchase_billing', $docket_update_data['purchase_billing'], '', array('docket_id' => $docket_id));
            }


            if (isset($docket_update_data['charge']) && is_array($docket_update_data['charge']) && count($docket_update_data['charge']) > 0) {
                $qry = "SELECT id,charge_id,charge_check FROM docket_charges WHERE status IN(1,2) 
                AND docket_id='" . $docket_id . "' AND billing_type=2";
                $qry_exe = $this->db->query($qry);
                $result = $qry_exe->result_array();

                if (isset($result) && is_array($result) && count($result) > 0) {
                    foreach ($result as $ckey => $cvalue) {
                        $docket_old_charge[$cvalue['charge_id']] = $cvalue['id'];
                    }
                }

                $updateq = "UPDATE docket_charges SET status=3 WHERE status IN(1,2) 
                AND docket_id='" . $docket_id . "' AND billing_type=2";
                $this->db->query($updateq);

                foreach ($docket_update_data['charge'] as $ci_key => $ci_value) {
                    if (isset($docket_old_charge[$ci_key])) {
                        $charge_update = array(
                            'rate_mod_id' => $ci_value['rate_mod_id'],
                            'charge_amount' => isset($docket_data['status_id']) && $docket_data['status_id'] == 3 ? 0 : $ci_value['charge_amount'],
                            'modified_date' => date('Y-m-d H:i:s'),
                            'status' => 1
                        );
                        $this->gm->update('docket_charges', $charge_update, '', array('id' => $docket_old_charge[$ci_key]));
                    } else {
                        $charge_insert = array(
                            'docket_id' => $docket_id,
                            'charge_id' => $ci_key,
                            'rate_mod_id' => $ci_value['rate_mod_id'],
                            'charge_amount' => isset($docket_data['status_id']) && $docket_data['status_id'] == 3 ? 0 : $ci_value['charge_amount'],
                            'charge_check' => $ci_value['charge_check'],
                            'billing_type' => 2,
                            'created_date' => date('Y-m-d H:i:s'),
                            'status' => 1
                        );
                        $this->gm->insert('docket_charges', $charge_insert);
                    }
                }
            }
        }

        //LOG UPDATED DATA HISTORY
        $docket_data_new = $this->gm->get_selected_record('docket', $old_column, $where = array('id' => $docket_id, 'status !=' => 3), array());
        $diff_column = array_diff_assoc(@array_intersect_key($docket_data_new, $docket_old_data), $docket_old_data);
        if (isset($diff_column) && is_array($diff_column) && count($diff_column) > 0) {
            foreach ($diff_column as $dkey => $dvalue) {
                if (isset($docket_old_data[$dkey])) {
                    $old_data[$dkey] = $docket_old_data[$dkey];
                }
                $new_data[$dkey] = $dvalue;
            }
        }

        $extra_insert_new = $this->gm->get_selected_record('docket_extra_field', $docket_extra_column, array('docket_id' => $docket_id, 'status' => 1), array());
        if (isset($extra_insert_new) && is_array($extra_insert_new) && count($extra_insert_new) > 0) {
        } else {
            $extra_insert_new = array();
        }
        if (isset($extra_old_data) && is_array($extra_old_data) && count($extra_old_data) > 0) {
        } else {
            $extra_old_data = array();
        }
        $extra_diff_column = array_diff_assoc(@array_intersect_key($extra_insert_new, $extra_old_data), $extra_old_data);
        if (isset($extra_diff_column) && is_array($extra_diff_column) && count($extra_diff_column) > 0) {
            foreach ($extra_diff_column as $dkey => $dvalue) {
                if (isset($extra_old_data[$dkey])) {
                    $old_data["de." . $dkey] = $extra_old_data[$dkey];
                }
                $new_data["de." . $dkey] = $dvalue;
            }
        }


        $shipper_insert_new = $this->gm->get_selected_record('docket_shipper', $shipper_consi_col, array('docket_id' => $docket_id, 'status' => 1), array());
        $shipper_diff_column = array_diff_assoc(@array_intersect_key($shipper_insert_new, $shipper_old_data), $shipper_old_data);
        if (isset($shipper_diff_column) && is_array($shipper_diff_column) && count($shipper_diff_column) > 0) {
            foreach ($shipper_diff_column as $dkey => $dvalue) {
                if (isset($shipper_old_data[$dkey])) {
                    $old_data["sh." . $dkey] = $shipper_old_data[$dkey];
                }
                $new_data["sh." . $dkey] = $dvalue;
            }
        }

        $consignee_insert_new = $this->gm->get_selected_record('docket_consignee', $shipper_consi_col, array('docket_id' => $docket_id, 'status' => 1), array());
        $consignee_diff_column = array_diff_assoc(@array_intersect_key($consignee_insert_new, $consignee_old_data), $consignee_old_data);
        if (isset($consignee_diff_column) && is_array($consignee_diff_column) && count($consignee_diff_column) > 0) {
            foreach ($consignee_diff_column as $dkey => $dvalue) {
                if (isset($shipper_old_data[$dkey])) {
                    $old_data["co." . $dkey] = $shipper_old_data[$dkey];
                }
                $new_data["co." . $dkey] = $dvalue;
            }
        }

        update_manifest_docket_detail(0, $docket_id);
        if ($docket_id > 0) {
            if ((isset($new_data) && is_array($new_data) && count($new_data) > 0)
                || isset($old_data) && is_array($old_data) && count($old_data) > 0
            ) {
                $old_data['mode'] = 'update_docket_purchase';
                $insert_data_history = array(
                    'docket_id' => $docket_id,
                    'new_data' => isset($new_data) ? json_encode($new_data) : '',
                    'old_data' => isset($old_data) ? json_encode($old_data) : '',
                    'created_date' => date('Y-m-d H:i:s'),
                    'created_by' => $this->user_id,
                    'created_by_type' => $this->user_type
                );

                $this->gm->insert('docket_history', $insert_data_history);
            }
            $purchase_data_new = $this->gm->get_selected_record('docket_purchase_billing', $pur_billing_col, array('docket_id' => $docket_id, 'status' => 1), array());
            if (isset($purchase_data_new) && is_array($purchase_data_new) && count($purchase_data_new) > 0) {
            } else {
                $purchase_data_new = array();
            }
            $pur_billing_diff_column = array_diff_assoc(array_intersect_key($purchase_data_new, $pur_billing_old_data), $pur_billing_old_data);
            if (isset($pur_billing_diff_column) && is_array($pur_billing_diff_column) && count($pur_billing_diff_column) > 0) {
                foreach ($pur_billing_diff_column as $dkey => $dvalue) {
                    if (isset($pur_billing_old_data[$dkey])) {
                        $pur_old_data[$dkey] = $pur_billing_old_data[$dkey];
                    }
                    $pur_new_data[$dkey] = $dvalue;
                }

                $history_new['purchase'] = isset($pur_new_data) ? $pur_new_data : '';
                $history_old['purchase'] = isset($pur_old_data) ? $pur_old_data : '';
            }

            if (
                isset($history_old) && is_array($history_old) && count($history_old) > 0
            ) {

                $history_old['mode'] = 'update_docket_purchase';
                $insert_data_history = array(
                    'docket_id' => $docket_id,
                    'new_data' => isset($history_new) ? json_encode($history_new) : '',
                    'old_data' => isset($history_old) ? json_encode($history_old) : '',
                    'created_date' => date('Y-m-d H:i:s'),
                    'created_by' => $this->user_id,
                    'created_by_type' => $this->user_type
                );
                $this->gm->insert('billing_history', $insert_data_history);
            }
        }
    }

    public function calculate_purchase_other_charge($docket_data)
    {
        $all_charge = get_all_charge(" AND status IN(1,2)", '*');;
        $total_other_charge = 0;
        $discount_amount = 0;

        $edited_field = array();
        if (isset($docket_data['edited_field']) && $docket_data['edited_field'] != '') {
            $edited_field = explode(",", $docket_data['edited_field']);
        }

        if (isset($docket_data['charge']) && is_array($docket_data['charge']) && count($docket_data['charge']) > 0) {
            foreach ($docket_data['charge'] as $ch_key => $ch_value) {
                $total_other_charge += $ch_value['charge_amount'];
            }
        }

        if (isset($docket_data['freight_amount'])) {
            $docket_data['freight_amount'] = round($docket_data['freight_amount'], 2);
        }
        $response['total_other_charge'] = $total_other_charge;

        if (isset($docket_data['discount_per']) && $docket_data['discount_per'] > 0) {
            $discount_amount = ($docket_data['freight_amount'] * $docket_data['discount_per']) / 100;
            $discount_amount = round($discount_amount, 2);
            $response['discount_amount'] = $discount_amount;
        } else if (isset($docket_data['edi_discount_amount']) && $docket_data['edi_discount_amount'] > 0) {
            $discount_amount = round($docket_data['edi_discount_amount'], 2);
            $response['discount_amount'] = $discount_amount;
        }

        $freight_after_dis = $docket_data['freight_amount'] -  $discount_amount;
        $freight_after_dis = round($freight_after_dis, 2);
        $response['freight_after_dis'] = $freight_after_dis;

        $adjustment_amount = isset($docket_data['adjustment_amount']) ? $docket_data['adjustment_amount'] : 0;
        $adjustment_amount = round($adjustment_amount, 2);


        if (isset($docket_data['fsc_amount'])) {
            $docket_data['fsc_amount'] = round($docket_data['fsc_amount'], 2);
        }
        $sub_total = $total_other_charge + $docket_data['fsc_amount'] + $freight_after_dis + $adjustment_amount;
        $sub_total = round($sub_total, 2);
        $response['sub_total'] = $sub_total;

        if (isset($docket_data['is_gst_apply']) && $docket_data['is_gst_apply'] == 1) {
            $freight_gst =  $freight_after_dis + $docket_data['fsc_amount'];
        } else {
            $non_taxable_amt =  $freight_after_dis + $docket_data['fsc_amount'];
        }

        //        $response['edited_field'] = '';

        if (isset($docket_data['charge']) && is_array($docket_data['charge']) && count($docket_data['charge']) > 0) {
            foreach ($docket_data['charge'] as $ch_key => $ch_value) {
                if (isset($all_charge[$ch_key]) && $all_charge[$ch_key]['is_gst_apply'] != 1) {
                    $non_taxable_amt +=  $ch_value['charge_amount'];
                }
            }
        }

        $non_taxable_amt = round($non_taxable_amt, 2);

        if (isset($docket_data['is_gst_apply']) && $docket_data['is_gst_apply'] == 1) {
            $non_taxable_amt =  isset($non_taxable_amt) ? $non_taxable_amt : 0;
            $response['non_taxable_amt'] = $non_taxable_amt;

            $taxable_amt = $sub_total - $non_taxable_amt;
            $taxable_amt = round($taxable_amt, 2);
            $response['taxable_amt'] = $taxable_amt;
        } else {
            $non_taxable_amt =  $sub_total;
            $response['non_taxable_amt'] = $non_taxable_amt;

            $taxable_amt = 0;
            $response['taxable_amt'] = $taxable_amt;
        }

        $gst_amount = 0;
        $gst_per = isset($docket_data['gst_per']) ? $docket_data['gst_per'] : 0;
        if ($gst_per == 0) {
            $non_taxable_amt += $taxable_amt;
            $response['non_taxable_amt'] = $non_taxable_amt;
            $taxable_amt = 0;
            $response['taxable_amt'] = $taxable_amt;
        }
        if ($gst_per > 0) {
            $gst_amount = ($taxable_amt *  $gst_per) / 100;
            $gst_amount = round($gst_amount, 2);
        }

        if (isset($docket_data['vendor_id']) &&  $docket_data['vendor_id'] > 0) {
            $all_vendor = get_all_vendor(" AND id='" . $docket_data['vendor_id'] . "'");
        }


        if (isset($docket_data['service_type']) && $docket_data['service_type'] != '') {
            if ($docket_data['service_type'] == 1) {
                $igst_amt = $gst_amount;
                $gst_type = 'igst';
            } else {
                if ($docket_data['gst_type'] == 'igst') {
                    $igst_amt = $gst_amount;
                    $gst_type = 'igst';
                } else {
                    $cgst = $gst_amount / 2;
                    $sgst = $gst_amount / 2;
                    $gst_type = 'cgst';
                }
            }
        } else {
            if (isset($docket_data['vendor_id']) && isset($all_vendor[$docket_data['vendor_id']]) && $all_vendor[$docket_data['vendor_id']]['service_type'] == 1) {
                $igst_amt = $gst_amount;
                $gst_type = 'igst';
            } else {
                if ($docket_data['gst_type'] == 'igst') {
                    $igst_amt = $gst_amount;
                    $gst_type = 'igst';
                } else {
                    $cgst = $gst_amount / 2;
                    $sgst = $gst_amount / 2;
                    $gst_type = 'cgst';
                }
            }
        }


        if (isset($docket_data['company_tax_type']) && $docket_data['company_tax_type'] == 2) {
            //VAT TYPE
            $igst_amt = $gst_amount;
            $cgst = 0;
            $sgst = 0;
            $gst_type = 'igst';
        }

        $igst_amt = isset($igst_amt) ? round($igst_amt, 2) : 0;
        $cgst = isset($cgst) ? round($cgst, 2) : 0;
        $sgst = isset($sgst) ? round($sgst, 2) : 0;

        if ($gst_type == 'igst') {
            if (in_array('igst_amount', $edited_field)) {
                $igst_amt = $docket_data['igst_amount'];
            }
        } else {
            if ($gst_type == 'cgst') {
                if (in_array('cgst_mount', $edited_field)) {
                    $cgst = $docket_data['cgst_amount'];
                }
                if (in_array('sgst_amount', $edited_field)) {
                    $sgst = $docket_data['sgst_amount'];
                }
            }
        }

        $response['igst_amount'] = $igst_amt;
        $response['cgst_amount'] = $cgst;
        $response['sgst_amount'] = $sgst;

        $grand_total = $non_taxable_amt + $taxable_amt + $igst_amt + $cgst + $sgst;
        $response['grand_total'] = $grand_total;

        return $response;
    }
    public function get_purchase_charge_amt($docket_data)
    {
        $docket_id = isset($docket_data['docket_id']) ? $docket_data['docket_id'] : 0;
        if ($docket_id > 0) {
            //$all_charge = get_all_charge(" AND status IN(1,2)", '*');;

            $docket_charge_res = $this->gm->get_data_list('docket_charges', array('docket_id' => $docket_id, 'status' => 1, 'charge_check' => 1), array(), array(), 'id,docket_id,charge_id');

            if (isset($docket_charge_res) && is_array($docket_charge_res) && count($docket_charge_res) > 0) {
                foreach ($docket_charge_res as $key => $value) {
                    $charge_id[$value['charge_id']] = $value['charge_id'];
                }
            }


            if (isset($charge_id) && is_array($charge_id) && count($charge_id) > 0) {
                $qry = "SELECT * FROM charge_master WHERE status=1 OR id IN(" . implode(",", $charge_id) . ") ORDER BY name";
                $qry_exe = $this->db->query($qry);
                $charge_name_data = $qry_exe->result_array();
                if (isset($charge_name_data) && is_array($charge_name_data) && count($charge_name_data) > 0) {
                    foreach ($charge_name_data as $key => $value) {
                        $all_charge[strtolower(trim($value['id']))] = $value;
                    }
                }
            } else {
                $all_charge = get_all_charge(' AND status=1', '*');
            }


            $qry = "SELECT id,charge_id,charge_check FROM docket_charges WHERE status IN(1,2) 
            AND docket_id='" . $docket_id . "' AND billing_type=2";
            $qry_exe = $this->db->query($qry);
            $result = $qry_exe->result_array();

            if (isset($result) && is_array($result) && count($result) > 0) {
                foreach ($result as $ckey => $cvalue) {
                    $docket_charge[$cvalue['charge_id']] = $cvalue;
                }
            }

            //GET DOCKET ITEM
            $item_data = $this->gm->get_data_list('vendor_docket_item', array('docket_id' => $docket_id, 'status' => 1), array(), array(), '*');
            if (isset($item_data) && is_array($item_data) && count($item_data) > 0) {
                foreach ($item_data as $ikey => $ivalue) {
                    $item_len[] = $ivalue['item_length'];
                    $item_wid[] = $ivalue['item_width'];
                    $item_hei[] = $ivalue['item_height'];
                    $item_act_wt[] = $ivalue['actual_wt'];
                }
            }
            if (isset($docket_data['item']) && is_array($docket_data['item']) && count($docket_data['item']) > 0) {
                foreach ($docket_data['item'] as $ikey => $ivalue) {
                    $item_char_wt[] = $ivalue['chargeable_wt'];
                    $item_vol_wt[] = $ivalue['volumetric_wt'];
                }
            }

            if (isset($all_charge) && is_array($all_charge) && count($all_charge) > 0) {
                foreach ($all_charge as $key => $value) {
                    $get_charge = 2;
                    if (isset($docket_charge[$value['id']])) {
                        if ($docket_charge[$value['id']]['charge_check'] == 1) {
                            $get_charge = 1;
                        } else {
                            $get_charge = 2;
                        }
                    } else {
                        if ($value['is_default'] == 1) {
                            $get_charge = 1;
                        }
                    }

                    if ($value['is_manual'] == 1) {
                        $get_charge = 2;
                    }

                    if ($get_charge == 2) {
                        $charge_amount[$value['id']]['rate_mod_id'] = 0;
                        $charge_amount[$value['id']]['rate_charge_amt'] = 0;
                        $charge_amount[$value['id']]['charge_check'] = 2;
                    } else {
                        $ajax_data = array(
                            'customer_id' => isset($docket_data['customer_id']) ? $docket_data['customer_id'] : 0,
                            'product_id' => isset($docket_data['product_id']) ? $docket_data['product_id'] : 0,
                            'vendor_id' => isset($docket_data['vendor_id']) ? $docket_data['vendor_id'] : 0,
                            'co_vendor_id' => isset($docket_data['co_vendor_id']) ? $docket_data['co_vendor_id'] : 0,
                            'destination_id' => isset($docket_data['destination_id']) ? $docket_data['destination_id'] : 0,
                            'dest_zone_id' => isset($docket_data['dest_zone_id']) ? $docket_data['dest_zone_id'] : 0,
                            'dest_zone_service_type' => isset($docket_data['dest_zone_service_type']) ? $docket_data['dest_zone_service_type'] : 0,
                            'origin_id' => isset($docket_data['origin_id']) ? $docket_data['origin_id'] : 0,
                            'ori_zone_id' => isset($docket_data['ori_zone_id']) ? $docket_data['ori_zone_id'] : 0,
                            'ori_zone_service_type' => isset($docket_data['ori_zone_service_type']) ? $docket_data['ori_zone_service_type'] : 0,
                            'ori_hub_id' => isset($docket_data['ori_hub_id']) ? $docket_data['ori_hub_id'] : 0,
                            'dest_hub_id' => isset($docket_data['dest_hub_id']) ? $docket_data['dest_hub_id'] : 0,
                            'booking_date' => isset($docket_data['booking_date']) ? $docket_data['booking_date'] : 0,
                            'freight_amount' => isset($docket_data['freight_amount']) ? $docket_data['freight_amount'] : 0,
                            'fsc_amount' => isset($docket_data['fsc_amount']) ? $docket_data['fsc_amount'] : 0,
                            'shipment_value' => isset($docket_data['shipment_value']) ? $docket_data['shipment_value'] : 0,
                            'chargeable_wt' => isset($docket_data['chargeable_wt']) ? $docket_data['chargeable_wt'] : 0,
                            'actual_wt' => isset($docket_data['actual_wt']) ? $docket_data['actual_wt'] : 0,
                            'volumetric_wt' => isset($docket_data['volumetric_wt']) ? $docket_data['volumetric_wt'] : 0,
                            'total_pcs' => isset($docket_data['total_pcs']) ? $docket_data['total_pcs'] : 0,
                            'con_pincode' => isset($docket_data['con_pincode']) ? $docket_data['con_pincode'] : '',
                            'charge_id' => $value['id'],
                            'charge_type' => 2,
                            'act_wt' => isset($item_act_wt) ?  $item_act_wt : array(),
                            'char_wt' => isset($item_char_wt) ?  $item_char_wt : array(),
                            'vol_wt' => isset($item_vol_wt) ?  $item_vol_wt : array(),
                            'dim_len' => isset($item_len) ?  $item_len : array(),
                            'dim_wid' => isset($item_wid) ?  $item_wid : array(),
                            'dim_hei' => isset($item_hei) ?  $item_hei : array(),
                        );
                        $charge_data = $this->generic_detail->get_purchase_charge($ajax_data);

                        // if (isset($_GET['mode']) && $_GET['mode'] == 'test') {
                        //     echo '<pre>';
                        //     print_r($ajax_data);

                        //     echo "<br>CHARGE1";
                        //     echo '<pre>';
                        //     print_r($charge_data);
                        // }


                        if (isset($charge_data['id'])) {
                            $charge_amount[$value['id']]['rate_mod_id'] = $charge_data['id'];
                            $charge_amount[$value['id']]['freight_fsc_per'] = $charge_data['freight_fsc_per'];
                            $charge_amount[$value['id']]['rate_charge_amt'] = round($charge_data['rate_amt'], 2);
                            $charge_amount[$value['id']]['charge_check'] = 1;
                        }
                    }
                }
            }
        }


        return $charge_amount;
    }
    public function calculate_purchase_freight($docket_data)
    {
        $response = array();
        $ajax_data = array(
            'vendor_contract_id' => isset($docket_data['vendor_contract_id']) ? $docket_data['vendor_contract_id'] : 0,
            'chargeable_wt_total' => isset($docket_data['chargeable_wt']) ? $docket_data['chargeable_wt'] : 0,
            'total_pcs' => isset($docket_data['total_pcs']) ? $docket_data['total_pcs'] : 0,
        );
        $freight_data = $this->generic_detail->get_purchase_freight_amount($ajax_data);


        $response['freight_amount'] = isset($freight_data['freight_amt']) ? round($freight_data['freight_amt'], 2) : 0;
        $response['chargeable_wt'] = isset($freight_data['round_ch_wt']) && $freight_data['round_ch_wt'] > 0 ? $freight_data['round_ch_wt'] : $docket_data['chargeable_wt'];
        return  $response;
    }

    public function get_purchase_fsc($docket_data)
    {
        $response = array();
        $ajax_data = array(
            'customer_id' => isset($docket_data['customer_id']) ? $docket_data['customer_id'] : 0,
            'vendor_id' => isset($docket_data['vendor_id']) ? $docket_data['vendor_id'] : 0,
            'co_vendor_id' => isset($docket_data['co_vendor_id']) ? $docket_data['co_vendor_id'] : 0,
            'booking_date' => isset($docket_data['booking_date']) ? $docket_data['booking_date'] : 0,
            'company_id' => isset($docket_data['company_id']) ? $docket_data['company_id'] : 0,
        );
        $sales_fsc_data = $this->docket->get_purchase_fsc(0, $ajax_data);
        $sales_fsc_data['customer_fsc_apply'] = 1;
        $response['fsc_amount'] = 0;
        $fsc_chargeable = 0;
        if (isset($sales_fsc_data['id'])) {
            //if (isset($sales_fsc_data['customer_fsc_apply']) && $sales_fsc_data['customer_fsc_apply'] == 1) {
            $freight_amount = isset($docket_data['freight_amount']) ? $docket_data['freight_amount'] : 0;
            $fsc_chargeable += $freight_amount;

            $all_charge = get_all_charge(" AND status IN(1,2)", '*');;

            if (isset($docket_data['charge']) && is_array($docket_data['charge']) && count($docket_data['charge']) > 0) {
                foreach ($docket_data['charge'] as $ch_key => $ch_value) {
                    if (isset($all_charge[$ch_key]) && $all_charge[$ch_key]['is_fsc_apply'] == 1) {
                        $fsc_chargeable +=  $ch_value['charge_amount'];
                    }
                }
            }

            $edited_field = explode(",", $docket_data['edited_field']);
            if (isset($edited_field) && is_array($edited_field) && count($edited_field) > 0) {
                if (in_array('pur_fsc_per', $edited_field)) {
                    $sales_fsc_data['fsc_percentage'] = $docket_data['fsc_per'];
                }
            }

            if ($fsc_chargeable > 0) {
                $fsc_amount = ($fsc_chargeable * $sales_fsc_data['fsc_percentage']) / 100;
                $response['fsc_amount'] = round($fsc_amount, 2);
            }

            //}
        } else {
            $freight_amount = isset($docket_data['freight_amount']) ? $docket_data['freight_amount'] : 0;
            $fsc_chargeable += $freight_amount;

            $all_charge = get_all_charge(" AND status IN(1,2)", '*');;

            if (isset($docket_data['charge']) && is_array($docket_data['charge']) && count($docket_data['charge']) > 0) {
                foreach ($docket_data['charge'] as $ch_key => $ch_value) {
                    if (isset($all_charge[$ch_key]) && $all_charge[$ch_key]['is_fsc_apply'] == 1) {
                        $fsc_chargeable +=  $ch_value['charge_amount'];
                    }
                }
            }

            $edited_field = explode(",", $docket_data['edited_field']);
            if (isset($edited_field) && is_array($edited_field) && count($edited_field) > 0) {
                if (in_array('pur_fsc_per', $edited_field)) {
                    $sales_fsc_data['fsc_percentage'] = $docket_data['fsc_per'];
                }
            }

            if ($fsc_chargeable > 0 && isset($sales_fsc_data['fsc_percentage']) && $sales_fsc_data['fsc_percentage'] > 0) {
                $fsc_amount = ($fsc_chargeable * $sales_fsc_data['fsc_percentage']) / 100;
                $response['fsc_amount'] = round($fsc_amount, 2);
            } else {
                $response['fsc_amount'] = 0;
            }
        }

        if (isset($response['fsc_amount'])) {
            $response['fsc_amount'] = round($response['fsc_amount'], 2);
        }
        $response['id'] = isset($sales_fsc_data['id']) ? $sales_fsc_data['id'] : 0;
        $response['fsc_percentage'] = isset($sales_fsc_data['fsc_percentage']) ? $sales_fsc_data['fsc_percentage'] : 0;
        $response['fsc_per'] = isset($sales_fsc_data['fsc_percentage']) ? $sales_fsc_data['fsc_percentage'] : 0;
        $response['gst_per'] = isset($sales_fsc_data['gst_per']) ? $sales_fsc_data['gst_per'] : 0;
        $response['service_type'] = isset($sales_fsc_data['service_type']) ? $sales_fsc_data['service_type'] : 0;
        $response['gst_type'] = isset($sales_fsc_data['gst_type']) ? $sales_fsc_data['gst_type'] : 0;
        $response['is_gst_apply'] = isset($sales_fsc_data['is_gst_apply']) ? $sales_fsc_data['is_gst_apply'] : 0;
        return  $response;
    }

    public function get_purchase_cft_contract($docket_data)
    {
        $ajax_data = array(
            'customer_id' => isset($docket_data['customer_id']) ? $docket_data['customer_id'] : 0,
            'product_id' => isset($docket_data['product_id']) ? $docket_data['product_id'] : 0,
            'vendor_id' => isset($docket_data['vendor_id']) ? $docket_data['vendor_id'] : 0,
            'co_vendor_id' => isset($docket_data['co_vendor_id']) ? $docket_data['co_vendor_id'] : 0,
            'booking_date' => isset($docket_data['booking_date']) ? $docket_data['booking_date'] : 0,
        );
        $cft_returnedData = $this->generic_detail->get_purchase_cft_contract($ajax_data);
        $response = $this->get_vendor_contract($docket_data);
        $response['cft_value'] = isset($cft_returnedData['cft_value']) && $cft_returnedData['cft_value'] != '' && $cft_returnedData['cft_value'] > 0 ? $cft_returnedData['cft_value'] : 1;
        $response['cft_multiplier'] = isset($cft_returnedData['cft_multiplier']) && $cft_returnedData['cft_multiplier'] != '' && $cft_returnedData['cft_multiplier'] > 0 ? $cft_returnedData['cft_multiplier'] : 1;
        $response['cft_contract_id'] = isset($cft_returnedData['id']) ? $cft_returnedData['id'] : 0;

        return  $response;
    }
    public function get_vendor_contract($docket_data)
    {
        $contract_res = array();
        $ajax_data = array(
            'customer_id' => isset($docket_data['customer_id']) ? $docket_data['customer_id'] : 0,
            'product_id' => isset($docket_data['product_id']) ? $docket_data['product_id'] : 0,
            'vendor_id' => isset($docket_data['vendor_id']) ? $docket_data['vendor_id'] : 0,
            'co_vendor_id' => isset($docket_data['co_vendor_id']) ? $docket_data['co_vendor_id'] : 0,
            'origin_id' => isset($docket_data['origin_id']) ? $docket_data['origin_id'] : 0,
            'destination_id' => isset($docket_data['destination_id']) ? $docket_data['destination_id'] : 0,
            'ori_zone_id' => isset($docket_data['ori_zone_id']) ? $docket_data['ori_zone_id'] : 0,
            'dest_zone_id' => isset($docket_data['dest_zone_id']) ? $docket_data['dest_zone_id'] : 0,
            'ori_city_id' =>  isset($docket_data['shi_city']) ? $docket_data['shi_city'] : '',
            'dest_city_id' =>  isset($docket_data['con_city']) ? $docket_data['con_city'] : '',
            'ori_state_id' =>  isset($docket_data['shi_state']) ? $docket_data['shi_state'] : '',
            'dest_state_id' =>  isset($docket_data['con_state']) ? $docket_data['con_state'] : '',
            'ori_hub_id' =>  isset($docket_data['ori_hub_id']) ? $docket_data['ori_hub_id'] : '',
            'dest_hub_id' =>  isset($docket_data['dest_hub_id']) ? $docket_data['dest_hub_id'] : '',
            'booking_date' => isset($docket_data['booking_date']) ? $docket_data['booking_date'] : '',
            'chargeable_wt_total' => isset($docket_data['chargeable_wt']) ? $docket_data['chargeable_wt'] : '',
            'shipment_priority' => isset($docket_data['shipment_priority']) ? $docket_data['shipment_priority'] : '',
        );
        $contract_returnedData = $this->generic_detail->get_vendor_contract($ajax_data);


        if (isset($contract_returnedData['id'])) {
            $contract_res['vendor_contract_id'] = $contract_returnedData['id'];
            $contract_res['vendor_contract_tat'] = $contract_returnedData['tat'];
        } else {
            $contract_res['vendor_contract_id'] = 0;
            $contract_res['vendor_contract_tat'] = 0;
        }

        return $contract_res;
    }

    public function refresh_delivery()
    {
        $this->load->model('Global_model', 'gm');
        $qry = "SELECT id,module_id FROM refresh_log WHERE status IN(1,2) 
        AND refresh_status=1 AND module_type=1 AND refresh_type='refresh_delivery' LIMIT 300";
        $qry_exe = $this->db->query($qry);
        $queue_data = $qry_exe->result_array();

        $tracking_event_id = $this->config->item('tracking_event_id');

        if (isset($queue_data) && is_array($queue_data) && count($queue_data) > 0) {
            foreach ($queue_data as $qkey => $qvalue) {
                //MARK PROCESSED
                $updateq = "UPDATE refresh_log SET refresh_status=2,processed_datetime='" . date('Y-m-d H:i:s') . "' WHERE id='" . $qvalue['id'] . "'";
                $this->db->query($updateq);

                $docketq = "SELECT d.state_id FROM docket d 
                where d.status IN(1,2) AND d.id='" . $qvalue['module_id'] . "'";
                $docketq_exe = $this->db->query($docketq);
                $docket_data = $docketq_exe->row_array();

                //DONT TRACK DELIVERED AND RTO STATE DOCKET
                if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
                    if (
                        $docket_data['state_id'] != $tracking_event_id['delivered']
                        || $docket_data['state_id'] != $tracking_event_id['rto']
                    ) {
                        $track_res = $this->update_delivery($qvalue['module_id']);

                        if (isset($track_res['error']) && $track_res['error'] != '') {
                            //MARK FAILED
                            $updateq = "UPDATE refresh_log SET refresh_status=4,processed_datetime='" . date('Y-m-d H:i:s') . "',refresh_msg='" . $track_res['error'] . "' WHERE id='" . $qvalue['id'] . "'";
                            $this->db->query($updateq);
                        } else {
                            //MARK DONE
                            $updateq = "UPDATE refresh_log SET refresh_status=3,processed_datetime='" . date('Y-m-d H:i:s') . "' WHERE id='" . $qvalue['id'] . "'";
                            $this->db->query($updateq);
                        }
                    } else {
                        //MARK FAILED
                        $updateq = "UPDATE refresh_log SET refresh_status=4,processed_datetime='" . date('Y-m-d H:i:s') . "',refresh_msg='delivered_docket' WHERE id='" . $qvalue['id'] . "'";
                        $this->db->query($updateq);
                    }
                }
            }
        }
    }

    public function update_delivery($docket_id)
    {
        $this->load->module('api/tracking_sync');
        $track_res =  $this->tracking_sync->sync_tracking_event($docket_id, 'get_arr_response');
        return $track_res;
    }


    public function calculate_vendor_wt($docket_data = array())
    {
        $total_actual_wt = 0;
        $total_volu_wt = 0;
        $total_charge_wt = 0;
        $total_pieces = 0;
        $wt_update_data = array();
        $edited_field = array();
        $cft_value = isset($docket_data['cft_value']) ? $docket_data['cft_value'] : 0;
        $cft_multiplier = isset($docket_data['cft_multiplier']) ? $docket_data['cft_multiplier'] : 0;
        $cft_contract_id = isset($docket_data['cft_contract_id']) ? $docket_data['cft_contract_id'] : 0;
        $vendor_contract_id = isset($docket_data['vendor_contract_id']) ? $docket_data['vendor_contract_id'] : 0;

        if ($cft_contract_id == '' || $cft_contract_id <= 0) {
            $cft_contract_id = 0;
        }
        $cft_contract_id = (int)$cft_contract_id;
        $cft_value = (float)$cft_value;
        $cft_multiplier = (float)$cft_multiplier;

        $docket_id = isset($docket_data['docket_id']) ? $docket_data['docket_id'] : 0;
        $item_data = $this->gm->get_data_list('vendor_docket_item', array('docket_id' => $docket_id, 'status' => 1), array(), array(), '*');

        $bill_exist = $this->gm->get_selected_record('docket_purchase_billing', 'id,edited_field,freight_amount,vendor_id,co_vendor_id,product_id,actual_wt,volumetric_wt,chargeable_wt', $where = array('docket_id' => $docket_id, 'status=' => 1), array());

        if (isset($bill_exist) && is_array($bill_exist) && count($bill_exist) > 0) {
            $edited_field = explode(",", $bill_exist['edited_field']);
        }

        if (isset($docket_data['vendor_id']) && $docket_data['vendor_id'] > 0) {
            $qry = "SELECT id,parcel_wise_discounted_ch_wt_purchase FROM vendor WHERE status IN(1,2) AND id='" . $docket_data['vendor_id'] . "'";
            $qry_exe = $this->db->query($qry);
            $service_setting = $qry_exe->row_array();
        }

        $parcel_wise_discounted_ch_wt = 2;
        if (
            isset($service_setting['parcel_wise_discounted_ch_wt_purchase']) && $service_setting['parcel_wise_discounted_ch_wt_purchase'] == 1
        ) {
            $parcel_wise_discounted_ch_wt = 1;
        }

        if (isset($item_data) && is_array($item_data) && count($item_data) > 0) {
            foreach ($item_data as $ikey => $ivalue) {
                $act_wt = $ivalue['actual_wt'];
                $box_count = 1;
                if ($box_count == '' || $box_count <= 0) {
                    $box_count = 1;
                }

                if ($act_wt > 0) {
                    $act_wt = (float)$act_wt;
                    $box_count = (float)$box_count;
                    $multiplication_val = $act_wt * $box_count;
                    $multiplication_val = round($multiplication_val, 2);
                    $multiplication_val = (float)$multiplication_val;
                    $total_actual_wt = $total_actual_wt + $multiplication_val;
                }

                $item_length = $ivalue['item_length'];
                $item_width = $ivalue['item_width'];
                $item_height = $ivalue['item_height'];

                if ($item_length == '' || $item_width == '' || $item_height == '') {
                    $wt_update_data['item'][$ivalue['id']]['volumetric_wt'] = 0;
                } else {

                    if ($item_length == '' || $item_length == 0) {
                        $item_length = 1;
                    }
                    if ($item_width == '' || $item_width == 0) {
                        $item_width = 1;
                    }
                    if ($item_height == '' || $item_height == 0) {
                        $item_height = 1;
                    }
                    $item_height = (float)$item_height;
                    $item_width = (float)$item_width;
                    $item_height = (float)$item_height;

                    if ($cft_contract_id > 0) {
                        $volumetric_wt = ($item_length * $item_width * $item_height * $box_count * $cft_multiplier) / $cft_value;
                    } else {
                        $volumetric_wt = 0;
                    }

                    $volumetric_wt = round($volumetric_wt, 2);
                    $volumetric_wt = (float) $volumetric_wt;

                    $wt_update_data['item'][$ivalue['id']]['volumetric_wt'] = $volumetric_wt;
                    $total_volu_wt = $total_volu_wt + $volumetric_wt;

                    if ($volumetric_wt > ($act_wt * $box_count)) {
                        $wt_update_data['item'][$ivalue['id']]['chargeable_wt'] = $volumetric_wt;
                    } else {
                        $multiplication_val = $act_wt * $box_count;
                        $multiplication_val = round($multiplication_val, 2);
                        $multiplication_val = (float)$multiplication_val;
                        $wt_update_data['item'][$ivalue['id']]['chargeable_wt']  = $multiplication_val;
                    }

                    if ($parcel_wise_discounted_ch_wt == 1) {
                        if ((float)$wt_update_data['item'][$ivalue['id']]['chargeable_wt'] > (float)$act_wt) {
                            $wt_update_data['item'][$ivalue['id']]['chargeable_wt'] = (float)$wt_update_data['item'][$ivalue['id']]['chargeable_wt'];
                            $act_wt = (float)$act_wt;
                            $wt_diff = $wt_update_data['item'][$ivalue['id']]['chargeable_wt'] - $act_wt;
                            $half_wt = $wt_diff / 2;
                            $half_wt = round($half_wt, 2);
                            $half_wt = (float)$half_wt;
                            $new_chare_wt = $act_wt + $half_wt;
                            $wt_update_data['item'][$ivalue['id']]['chargeable_wt']  = $new_chare_wt;
                        }
                    }


                    //ROUNF OFF CHARGEABLE WT PER LINE ITEM AS PER CONTRACT
                    if (isset($docket_data['vendor_id']) && $docket_data['vendor_id'] > 0) {
                        $service_data = $this->gm->get_selected_record('vendor', 'id,round_off_chg_wt_pur,chg_wt_per_item_pur', $where = array('id' => $docket_data['vendor_id']), array());
                    }

                    $round_off_chg_wt = isset($service_data['round_off_chg_wt_pur']) && $service_data['round_off_chg_wt_pur'] == 1 ? 1 : 2;
                    if ($round_off_chg_wt == 1) {
                        $charge_wt = $wt_update_data['item'][$ivalue['id']]['chargeable_wt'];
                        //Round OFF CHARGEABLE WEIGTH
                        $this->load->module('generic_detail');
                        $rate_data['contract_id'] = $vendor_contract_id;
                        $customerContractData = $this->generic_detail->get_vendor_contract($rate_data);

                        if (isset($customerContractData['rate']) && is_array($customerContractData['rate']) && count($customerContractData['rate']) > 0) {
                            $charge_slab_found = 2;

                            foreach ($customerContractData['rate'] as $rate_key => $value) {
                                $lower_wt = $value['lower_wt'];
                                $upper_wt = $value['upper_wt'];

                                $lower_wt = (float) $lower_wt;
                                $charge_wt = (float) $charge_wt;
                                $upper_wt = (float) $upper_wt;
                                if ($charge_wt >= $lower_wt && $charge_wt <= $upper_wt) {
                                    $charge_slab_found = 1;
                                }
                            }

                            //round up  wt if slab not found
                            if ($charge_slab_found == 2) {
                                $charge_wt = ceil($charge_wt);
                            }

                            foreach ($customerContractData['rate'] as $rate_key => $value) {
                                $lower_wt = $value['lower_wt'];
                                $upper_wt = $value['upper_wt'];

                                $lower_wt = (float) $lower_wt;
                                $charge_wt = (float) $charge_wt;
                                $upper_wt = (float) $upper_wt;

                                if ($charge_wt >= $lower_wt && $charge_wt <= $upper_wt) {
                                    $on_add = $value['on_add'];
                                    $on_add = (string)$on_add;

                                    if ($charge_slab_found == 2) {
                                        $charge_wt = sprintf('%0.2f', $charge_wt);
                                    }
                                    $charge_wt = (string)$charge_wt;
                                    if (strpos($charge_wt, '.') !== false) {


                                        $decimal_amt = 0;
                                        $decimal_arr = explode(".", $on_add);

                                        if (isset($decimal_arr[1]) && $decimal_arr[1] != '') {
                                            $decimal_amt = $decimal_arr[1];
                                            if (strlen($decimal_arr[1]) == 1) {
                                                $decimal_amt = $decimal_arr[1] . '0';
                                            }
                                        }
                                        $round_ch_wt = $charge_wt;

                                        $charge_decimal_arr = explode(".", $charge_wt);


                                        if (isset($charge_decimal_arr[1]) && $charge_decimal_arr[1] != '') {
                                            $chare_decimal_amt = $charge_decimal_arr[1];

                                            if (strlen($charge_decimal_arr[1]) == 1) {
                                                $chare_decimal_amt = $charge_decimal_arr[1] . '0';
                                            }
                                        }


                                        if (isset($chare_decimal_amt) && $decimal_amt != 0) {

                                            $charge_remain = ($chare_decimal_amt % $decimal_amt);


                                            if ($charge_remain > 0) {
                                                $decimal_to_add = $decimal_amt - $charge_remain;
                                                $chare_decimal_amt = $chare_decimal_amt + $decimal_to_add;
                                                $chare_decimal_amt = $chare_decimal_amt / 100;
                                                $round_ch_wt = $charge_decimal_arr[0] + $chare_decimal_amt;
                                            } else {
                                                $round_ch_wt = $charge_wt;
                                            }


                                            $round_ch_wt = round($round_ch_wt, 2);
                                        } else {
                                            $round_ch_wt = ceil($charge_wt);
                                        }

                                        $wt_update_data['item'][$ivalue['id']]['chargeable_wt']  = $round_ch_wt;
                                    }
                                    break;
                                }
                            }
                        }
                    }

                    $total_charge_wt = $total_charge_wt + $wt_update_data['item'][$ivalue['id']]['chargeable_wt'];
                }
            }
        }

        $docket_edited_field = array();
        $docket_extra = $this->gm->get_selected_record('docket_extra_field', 'id,docket_edit_field', $where = array('docket_id' => $docket_id, 'status=' => 1), array());
        if (isset($docket_extra) && is_array($docket_extra) && count($docket_extra) > 0) {
            $docket_edited_field = explode(",", $docket_extra['docket_edit_field']);
        }
        $docket_edited_data = $this->gm->get_selected_record('docket', 'id,actual_wt,volumetric_wt,chargeable_wt', $where = array('id' => $docket_id, 'status=' => 1), array());
        if (in_array('edit_actual_wt', $docket_edited_field)) {
            $total_actual_wt = $docket_edited_data['actual_wt'];
        }
        if (in_array('edit_volume_wt', $docket_edited_field)) {
            $total_volu_wt = $docket_edited_data['volumetric_wt'];
        }

        if (in_array('edit_actual_wt', $docket_edited_field) || in_array('edit_volume_wt', $docket_edited_field)) {
            $total_charge_wt = $total_actual_wt > $total_volu_wt ? $total_actual_wt : $total_volu_wt;
        }
        if (in_array('edit_vendor_detail', $edited_field)) {
            $total_actual_wt = isset($docket_data['actual_wt']) ? $docket_data['actual_wt'] : 0;
        }
        if (in_array('edit_vendor_detail', $edited_field)) {
            $total_volu_wt = isset($docket_data['volumetric_wt']) ? $docket_data['volumetric_wt'] : 0;
        }
        $wt_update_data['purchase_billing']['actual_wt'] = $total_actual_wt;
        $wt_update_data['purchase_billing']['volumetric_wt'] = $total_volu_wt;
        $chg_wt_per_item = isset($service_data['chg_wt_per_item_pur']) && $service_data['chg_wt_per_item_pur'] == 1 ? 1 : 2;

        if ($chg_wt_per_item == 1) {

            $wt_update_data['total_charge_wt'] = $total_charge_wt;

            $chargeable_wt_total = $total_charge_wt;
            if ($chargeable_wt_total == '' || $chargeable_wt_total <= 0) {
                $chargeable_wt_total = 0;
            }
            $chargeable_wt_total = round($chargeable_wt_total, 2);

            if (in_array('edit_vendor_detail', $edited_field)) {
                $chargeable_wt_total = isset($docket_data['chargeable_wt']) ? $docket_data['chargeable_wt'] : 0;
            }

            $wt_update_data['purchase_billing']['chargeable_wt'] = $chargeable_wt_total;
        } else {
            //find largest between ACTUAL WEIGHT,VOLUMETRIC WEIGHT
            $actual_wt_total = $total_actual_wt;
            $volumetric_wt_total = $total_volu_wt;
            if ($actual_wt_total == '' || $actual_wt_total <= 0) {
                $actual_wt_total = 0;
            }
            if ($volumetric_wt_total == '' || $volumetric_wt_total <= 0) {
                $volumetric_wt_total = 0;
            }

            $actual_wt_total = (float)$actual_wt_total;
            $volumetric_wt_total = (float)$volumetric_wt_total;

            $largest_wt = max($actual_wt_total, $volumetric_wt_total);
            $largest_wt = (float)$largest_wt;


            if (in_array('edit_vendor_detail', $edited_field)) {
                $chargeable_wt_total = isset($docket_data['chargeable_wt']) ? $docket_data['chargeable_wt'] : 0;
            } else {
                $chargeable_wt_total = $largest_wt;
            }
            $wt_update_data['purchase_billing']['chargeable_wt'] = $chargeable_wt_total;
        }

        return $wt_update_data;
    }



    public function calculate_estimate_data($docket_data = array())
    {
        $docket_update_data = array();
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');

        $this->load->module('generic_detail');
        $this->load->module('docket');
        $docket_data['calculation'] = 'estimate';
        $docket_update_data = array();
        if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
            $edited_field = array();
            if (isset($docket_data['bill_edited']) && $docket_data['bill_edited'] != '') {
                $edited_field = explode(",", $docket_data['bill_edited']);
            }

            $cft_data =  $this->get_cft_contract($docket_data);

            if (isset($cft_data) && is_array($cft_data) && count($cft_data) > 0) {
                foreach ($cft_data as $ckey => $cvalue) {
                    $docket_update_data['docket'][$ckey] = $cvalue;
                    $docket_data[$ckey] = $cvalue;
                }
            }

            $all_company = get_all_billing_company();
            $docket_update_data['sales_billing']['company_tax_type'] = isset($all_company[$docket_data['company_id']]) ? $all_company[$docket_data['company_id']]['tax_type'] : 1;
            $docket_data['company_tax_type'] = isset($all_company[$docket_data['company_id']]) ? $all_company[$docket_data['company_id']]['tax_type'] : 1;


            //CALCULATE WT
            $weight_data =  $this->calculate_wt($docket_data);

            if (isset($weight_data['item']) && is_array($weight_data['item']) && count($weight_data['item']) > 0) {
                $docket_update_data['item'] = $weight_data['item'];
                $docket_data['item'] = $weight_data['item'];
            }

            $docket_data['chargeable_wt'] = isset($weight_data['docket']['chargeable_wt']) ? $weight_data['docket']['chargeable_wt'] : 0;
            $docket_update_data['docket']['chargeable_wt'] = isset($weight_data['docket']['chargeable_wt']) ? $weight_data['docket']['chargeable_wt'] : 0;

            if (isset($cft_data['customer_contract_id']) && $cft_data['customer_contract_id'] > 0) {
                $freight_round_data = $this->calculate_freight($docket_data);
                $freight_data['chargeable_wt'] = $freight_round_data['chargeable_wt'];
                $docket_data['chargeable_wt'] = $freight_data['chargeable_wt'];
            }



            if (!in_array('grand_total', $edited_field)) {
                if (!in_array('freight_amount', $edited_field)) {
                    $docket_data['chargeable_wt'] = isset($weight_data['docket']['chargeable_wt']) ? $weight_data['docket']['chargeable_wt'] : 0;
                    $freight_data = $this->calculate_freight($docket_data);
                } else {
                    $freight_data['freight_amount'] = $docket_data['bill_freight_amount'];
                    $freight_data['chargeable_wt'] = $docket_data['chargeable_wt'];
                }

                $freight_data['freight_amount'] = round($freight_data['freight_amount'], 2);



                $docket_update_data['docket']['freight_amount'] =  $freight_data['freight_amount'];
                $docket_data['freight_amount'] = $freight_data['freight_amount'];
                $docket_data['chargeable_wt'] = $freight_data['chargeable_wt'];

                $docket_update_data['sales_billing']['freight_amount'] = $freight_data['freight_amount'];
                $docket_update_data['docket']['chargeable_wt'] = $freight_data['chargeable_wt'];

                //CALCULATE OTHER CHARGE
                $setting_data = get_all_app_setting(" AND module_name ='customer_estimate'");
                if (isset($setting_data['estimate_charge_id']) && $setting_data['estimate_charge_id'] != '') {
                    $all_charge = get_all_charge(" AND status IN(1,2)", '*');
                }
                $charge_amount_data = $this->get_charge_amt($docket_data, 'estimate_data');


                if (isset($charge_amount_data) && is_array($charge_amount_data) && count($charge_amount_data) > 0) {
                    foreach ($charge_amount_data as $ch_key => $ch_value) {
                        if (isset($all_charge[$ch_key])) {
                            $docket_update_data['charge'][$ch_key] = array(
                                'rate_mod_id' => $ch_value['rate_mod_id'],
                                'charge_amount' => round($ch_value['rate_charge_amt'], 2),
                                'charge_check' => $ch_value['charge_check'],
                            );
                            $docket_data['charge'][$ch_key] = $docket_update_data['charge'][$ch_key];

                            if ($ch_value['freight_fsc_per'] > 0 && $all_charge[$ch_key]['is_fsc_apply'] == 2) {

                                $fsc_rate_found = 1;
                            }
                        }
                    }
                }


                $fsc_amt_data = $this->get_sales_fsc($docket_data);

                if (in_array('fsc_amount', $edited_field)) {
                    $fsc_amt_data['fsc_amount'] = $docket_data['bill_fsc_amount'];
                }

                if (isset($fsc_amt_data['fsc_amount'])) {
                    $fsc_amt_data['fsc_amount'] = round($fsc_amt_data['fsc_amount'], 2);
                }


                if (isset($fsc_rate_found) && $fsc_rate_found == 1) {
                    $docket_data['fsc_amount'] = $fsc_amt_data['fsc_amount'];

                    $charge_amount_data = $this->get_charge_amt($docket_data);

                    $all_charge = get_all_charge(" AND status IN(1,2)", '*');

                    if (isset($charge_amount_data) && is_array($charge_amount_data) && count($charge_amount_data) > 0) {
                        foreach ($charge_amount_data as $ch_key => $ch_value) {
                            if (isset($all_charge[$ch_key])) {
                                if ($all_charge[$ch_key]['is_manual'] == 1) {
                                    $docket_update_data['charge'][$ch_key] = array(
                                        'rate_mod_id' => isset($docket_sales_charges[$ch_key]) ? $docket_sales_charges[$ch_key]['rate_mod_id'] : 0,
                                        'charge_amount' => isset($docket_sales_charges[$ch_key]) ? round($docket_sales_charges[$ch_key]['charge_amount'], 2) : 0,
                                    );
                                } else {
                                    $docket_update_data['charge'][$ch_key] = array(
                                        'rate_mod_id' => $ch_value['rate_mod_id'],
                                        'charge_amount' => round($ch_value['rate_charge_amt'], 2),
                                        'charge_check' => $ch_value['charge_check'],
                                    );
                                }

                                $docket_data['charge'][$ch_key] = $docket_update_data['charge'][$ch_key];
                            }
                        }
                    }
                }



                $docket_update_data['sales_billing']['fsc_amount'] = isset($fsc_amt_data['fsc_amount']) ? $fsc_amt_data['fsc_amount'] : '';
                $docket_update_data['sales_billing']['gst_per'] = isset($fsc_amt_data['gst_per']) ? $fsc_amt_data['gst_per'] : '';
                $docket_update_data['sales_billing']['fsc_per'] = isset($fsc_amt_data['fsc_per']) ? $fsc_amt_data['fsc_per'] : '';

                $docket_data['fsc_amount'] = isset($fsc_amt_data['fsc_amount']) ? $fsc_amt_data['fsc_amount'] : '';
                $docket_data['service_type'] = isset($fsc_amt_data['service_type']) ? $fsc_amt_data['service_type'] : '';
                $docket_data['gst_type'] = isset($fsc_amt_data['gst_type']) ? $fsc_amt_data['gst_type'] : '';
                $docket_data['gst_per'] = isset($fsc_amt_data['gst_per']) ? $fsc_amt_data['gst_per'] : '';

                $total_data =  $this->calculate_other_charge($docket_data);
                if (isset($total_data) && is_array($total_data) && count($total_data) > 0) {
                    foreach ($total_data as $tkey => $tvalue) {
                        $docket_update_data['sales_billing'][$tkey] = $tvalue;
                    }
                }
            }
        }

        return $docket_update_data;
    }
}
