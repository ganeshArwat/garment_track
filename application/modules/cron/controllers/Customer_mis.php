<?php
class Customer_mis extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function insert_custom_report_email($customer_id = 0)
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->helper('email');
        $this->load->helper('message');
        $this->load->model('Global_model', 'gm');
        $this->load->module('report/custom_report');
        file_put_contents(FCPATH . 'log1/customer_mis_insert_custom_report_email.txt', date('Y-m-d-H-i-s-a') . "\n", FILE_APPEND);


        //CHECK CUSTOMER WHOSE MIS REPORT ENABLE
        $append = '';
        if ($customer_id > 0) {
            $append = " AND id='" . $customer_id . "'";
        }
        $query = "SELECT id,company_id,receiver_auto_mis_report,customer_auto_mis_project_wise,
        customer_auto_mis_void_docket,customer_auto_mis_hold_docket FROM customer 
        WHERE status IN(1,2) AND customer_send_auto_mis_email=1 AND receiver_auto_mis_report!=''" . $append;
        $query_exec = $this->db->query($query);
        $result = $query_exec->result_array();

        if (isset($result) && is_array($result) && count($result) > 0) {
            foreach ($result as $key => $value) {
                $cust_id_arr[$value['id']] = $value['id'];
                $cust_company[$value['id']] = $value['company_id'];
                $cust_receiver_email[$value['id']] = $value['receiver_auto_mis_report'];
                $customer_res[$value['id']] = $value;
            }
        } else {
            if ($customer_id > 0) {
                $response = array(
                    'status' => 'failed',
                    'message' => 'MIS EMAIL DISABLE FOR THIS CUSTOMER'
                );
            }
        }

        if (isset($cust_id_arr) && is_array($cust_id_arr) && count($cust_id_arr) > 0) {
            $query = "SELECT * FROM email_cron_setting WHERE status IN(1,2) AND module_type=1 AND email_type='customer_mis'
            AND module_id IN(" . implode(",", $cust_id_arr) . ") GROUP BY module_type,module_id";
            $query_exec = $this->db->query($query);
            $email_result = $query_exec->result_array();
        }

        $tracking_event_id = $this->config->item('tracking_event_id');
        $delivery_state_id = $tracking_event_id['delivered'];

        if (isset($email_result) && is_array($email_result) && count($email_result) > 0) {

            $qry = "SELECT te.* FROM trigger_email_setting te 
            JOIN email_configuration co ON(co.id=te.email_configuration_id)
            WHERE te.status IN(1,2) AND co.status IN(1,2) AND te.email_trigger_key='customer_mis_email'
            AND te.send_email=1";
            $qry_exe = $this->db->query($qry);
            $msg_data = $qry_exe->row_array();
            if (isset($msg_data) && is_array($msg_data) && count($msg_data) > 0) {

                foreach ($email_result as $ekey => $evalue) {
                    //CHECK WHETHER TO SEND EMAIL TODAY OR NOT
                    $send_report = 2;
                    if ($evalue['email_cycle'] == 2) {
                        //DAILY
                        $send_report = 1;
                    } elseif ($evalue['email_cycle'] == 3) {
                        //WEEKLY
                        $todayDate = date('Y-m-d');
                        $todayDay = date('N', strtotime($todayDate));
                        if ($todayDay == $evalue['email_day']) {
                            $send_report = 1;
                        }
                    } elseif ($evalue['email_cycle'] == 4) {
                        //MONTHLY
                        $todayDate = date('Y-m-d');
                        $firstDate = date('Y-m-d', strtotime('first day of this month'));
                        if ($todayDate == $firstDate) {
                            $send_report = 1;
                        }
                    } elseif ($evalue['email_cycle'] == 5) {
                        //TWICE A DAY
                        $send_report = 1;
                    } elseif ($evalue['email_cycle'] == 1) {
                        //MANUALLY
                        if ($customer_id > 0) {
                            $send_report = 1;
                        } else {
                            $send_report = 2;
                        }
                    }


                    if ($evalue['email_cycle'] == 5) {
                        $time1 = $evalue['email_time1'];
                        $time2 = $evalue['email_time1'];
                    } else {
                        $time1 = $evalue['email_time1'];
                        $time2 = '';
                    }

                    if ($time1 != '') {

                        $to_time = strtotime(date("Y-m-d " . $time1));
                        $from_time = strtotime(date("Y-m-d H:i:s"));

                        //DONT ADD EMAIL IF MORE THAN HOUR REMAINING
                        $remaining_min =  round(abs($to_time - $from_time) / 60, 2);

                        if ($remaining_min > 60) {
                            $send_report = 2;
                        } else {
                            //CHECK THAT CUSTOMER TODAY MAIL SENT
                            $qry = "SELECT id FROM email_queue WHERE status=1 AND module_type=1 
                            AND module_id='" . $evalue['module_id'] . "' AND send_status!=1 
                            AND email_send_datetime='" . date('Y-m-d') . " " . $time1 . "'";
                            $qry_exe = $this->db->query($qry);
                            $mail_sent_exist = $qry_exe->row_array();
                            if (isset($mail_sent_exist) && is_array($mail_sent_exist) && count($mail_sent_exist) > 0) {
                                $send_report = 2;
                            }
                        }
                    }

                    if ($time2 != '') {
                        $to_time = strtotime(date("Y-m-d " . $time2));
                        $from_time = strtotime(date("Y-m-d H:i:s"));

                        //DONT ADD EMAIL IF MORE THAN HOUR REMAINING
                        $remaining_min =  round(abs($to_time - $from_time) / 60, 2);

                        if ($remaining_min > 60) {
                            $send_report = 2;
                        } else {
                            //CHECK THAT CUSTOMER TODAY MAIL SENT
                            $qry = "SELECT id FROM email_queue WHERE status=1 AND module_type=1 
                            AND module_id='" . $evalue['module_id'] . "' AND send_status!=1 
                            AND email_send_datetime='" . date('Y-m-d') . " " . $time2 . "'";
                            $qry_exe = $this->db->query($qry);
                            $mail_sent_exist = $qry_exe->row_array();
                            if (isset($mail_sent_exist) && is_array($mail_sent_exist) && count($mail_sent_exist) > 0) {
                                $send_report = 2;
                            }
                        }
                    }


                    if ($customer_id > 0) {
                        $send_report = 1;
                    }
                    if ($send_report == 1) {

                        if ($evalue['email_cycle'] == 5) {
                            $time1 = $evalue['email_time1'];
                            $time2 = $evalue['email_time1'];
                        } else {
                            $time1 = $evalue['email_time1'];
                            $time2 = '';
                        }


                        if (isset($cust_company[$evalue['module_id']]) && $cust_company[$evalue['module_id']] > 0) {
                            $company_name = '';
                            $company_id = $cust_company[$evalue['module_id']];
                            $all_company = get_all_billing_company(" AND id= '" . $company_id . "'");
                            $company_name = isset($all_company[$company_id]) ? $all_company[$company_id]['name'] : '';
                        }
                        $email_subject = $msg_data['email_subject'];
                        $email_subject = str_ireplace("[[company]]", $company_name, $email_subject);

                        $email_body = $msg_data['email_body'];
                        $email_body = str_ireplace("[[company]]", $company_name, $email_body);
                        $email_body = str_ireplace("[[content]]", '', $email_body);


                        $module_data = array(
                            'email_configuration_id' => $msg_data['email_configuration_id'],
                            'master_var_id' => $msg_data['id'],
                            'module_id' => $evalue['module_id'],
                            'module_type' => 2,
                            'message' => $email_body,
                            'email_subject' => $email_subject,
                            'sms_type' => 'email'
                        );
                        $dynamic_res = get_dynamic_email_body($module_data);

                        $msg_body = $dynamic_res['message'];
                        $email_subject = $dynamic_res['email_subject'];

                        $data['msg_body'] = $msg_body;
                        $html_body = $this->load->view('email_body/customer_mis_email', $data, TRUE);



                        $receiver_email = isset($cust_receiver_email[$evalue['module_id']]) ? $cust_receiver_email[$evalue['module_id']] : '';

                        if ($receiver_email != '') {
                            //create attachment file
                            $report_filter = array(
                                'customer_id' =>  $evalue['module_id'],
                                //'state_id' => $delivery_state_id,
                            );
                            if ($evalue['shipment_range'] == 2) {
                                $report_filter['booking_min'] = date('Y-m-01', strtotime('-1 MONTH'));
                                $report_filter['booking_max'] = date('Y-m-t');
                            } else {
                                $report_filter['booking_min'] = date('Y-m-01');
                                $report_filter['booking_max'] = date('Y-m-t');
                            }

                            if ($evalue['dont_send_last_month_deliver'] == 1 && $evalue['shipment_range'] == 2) {
                                //check last month total deliver docket
                                $pre_first = date('Y-m-01', strtotime('-1 MONTH'));
                                $pre_last = date('Y-m-t', strtotime('-1 MONTH'));
                                $dockq = "SELECT count(id) as dok_cnt FROM docket WHERE status IN(1,2)
                             AND booking_date>='" . $pre_first . "' AND booking_date<='" . $pre_last . "'
                             AND state_id=" . $delivery_state_id;
                                $dockq_exe = $this->db->query($dockq);
                                $doc_res = $dockq_exe->row_array();

                                $dockq = "SELECT count(id) as send_cnt FROM docket WHERE status IN(1,2)
                             AND booking_date>='" . $pre_first . "' AND booking_date<='" . $pre_last . "'
                             AND customer_mis_send=1 AND state_id=" . $delivery_state_id;
                                $dockq_exe = $this->db->query($dockq);
                                $send_res = $dockq_exe->row_array();

                                if ($doc_res['dok_cnt'] == $send_res['send_cnt']) {
                                    $report_filter['booking_min'] = date('Y-m-01');
                                    $report_filter['booking_max'] = date('Y-m-t');
                                }
                            }

                            $report_filter['email_type'] = 'customer_mis';

                            if ($evalue['email_once_shipment_deliver'] == 1) {
                                $report_filter['customer_mis_send'] = 2;
                            }

                            $report_filter['status_id'][] = 1;
                            $report_filter['status_id'][] = 2;
                            if (isset($customer_res[$evalue['module_id']]) && $customer_res[$evalue['module_id']]['customer_auto_mis_void_docket'] == 1) {
                                $report_filter['status_id'][] = 3;
                            }
                            if (isset($customer_res[$evalue['module_id']]) && $customer_res[$evalue['module_id']]['customer_auto_mis_hold_docket'] == 1) {
                                $report_filter['status_id'][] = 4;
                            }


                            $attachment_file = array();

                            $attachment_file_name = array();
                            $send_docket_ids = array();
                            //check whether to send project wise email or not
                            if (isset($customer_res[$evalue['module_id']]) && $customer_res[$evalue['module_id']]['customer_auto_mis_project_wise'] == 1) {
                                //get all project og that customer
                                $all_project = get_all_project(" AND customer_id=" . $evalue['module_id']);
                                if (isset($all_project) && is_array($all_project) && count($all_project) > 0) {
                                    foreach ($all_project as $pkey => $pvalue) {
                                        $report_filter['project_id'] = $pvalue['id'];
                                        $attachment_file_name = $this->custom_report->export_data($evalue['custom_report_id'], $report_filter, 1);
                                        if (isset($attachment_file_name['file_save_path']) && $attachment_file_name['file_save_path'] != '') {
                                            $attachment_file[] =  FCPATH . $attachment_file_name['file_save_path'];
                                            $send_docket_ids[] = $attachment_file_name['docket'];
                                        }
                                    }
                                }
                            } else {
                                $attachment_file_name = $this->custom_report->export_data($evalue['custom_report_id'], $report_filter, 1);

                                if (isset($attachment_file_name['file_save_path']) && $attachment_file_name['file_save_path'] != '') {
                                    $attachment_file[] =  FCPATH . $attachment_file_name['file_save_path'];
                                    $send_docket_ids[] = $attachment_file_name['docket'];
                                }
                            }

                            if (isset($attachment_file) && is_array($attachment_file) && count($attachment_file) > 0) {
                                foreach ($attachment_file as $akey => $avalue) {
                                    $email_attachment[0] = $avalue;
                                    if ($customer_id > 0) {
                                        $send_status =  send_email_msg($msg_data['email_configuration_id'], $email_subject, $html_body, $receiver_email, '', $msg_data['cc_email'], $email_attachment);
                                        if ($send_status) {
                                            if (isset($send_docket_ids[$akey]) && is_array($send_docket_ids[$akey]) && count($send_docket_ids[$akey]) > 0) {
                                                $updateq = "UPDATE docket SET customer_mis_send=1 WHERE id IN(" . implode(",", $send_docket_ids[$akey]) . ")";
                                                $this->db->query($updateq);
                                            }
                                        }
                                    } else {
                                        $insert_data = array(
                                            'email_configuration_id' => $msg_data['email_configuration_id'],
                                            'receiver_email' => $receiver_email,
                                            'email_subject' => $email_subject,
                                            'email_body' => $html_body,
                                            'email_attachment' => $avalue,
                                            'description' => 'Customer mis report',
                                            'send_status' => 1,
                                            'module_id' => $evalue['module_id'],
                                            'module_type' => $evalue['module_type'],
                                            'created_date' => date('Y-m-d H:i:s')
                                        );


                                        if ($time1 != '') {
                                            $insert_data['email_send_datetime'] =  date('Y-m-d') . " " . $time1;

                                            $qry = "SELECT id FROM email_queue WHERE status=1 AND module_type=1 
                            AND module_id='" . $evalue['module_id'] . "' AND send_status=1 
                            AND email_send_datetime='" . date('Y-m-d') . " " . $time1 . "'";
                                            $qry_exe = $this->db->query($qry);
                                            $mail_queue_exist = $qry_exe->row_array();
                                            if (isset($mail_queue_exist) && is_array($mail_queue_exist) && count($mail_queue_exist) > 0) {
                                                $this->gm->update('email_queue', $insert_data, '', array('id' => $mail_queue_exist['id']));
                                            } else {
                                                $this->gm->insert('email_queue', $insert_data);
                                            }
                                        }
                                        if ($time2 != '') {
                                            $insert_data['email_send_datetime'] =  date('Y-m-d') . " " . $time2;

                                            $qry = "SELECT id FROM email_queue WHERE status=1 AND module_type=1 
                            AND module_id='" . $evalue['module_id'] . "' AND send_status=1 
                            AND email_send_datetime='" . date('Y-m-d') . " " . $time2 . "'";
                                            $qry_exe = $this->db->query($qry);
                                            $mail_queue_exist = $qry_exe->row_array();
                                            if (isset($mail_queue_exist) && is_array($mail_queue_exist) && count($mail_queue_exist) > 0) {
                                                $this->gm->update('email_queue', $insert_data, '', array('id' => $mail_queue_exist['id']));
                                            } else {
                                                $this->gm->insert('email_queue', $insert_data);
                                            }
                                        }

                                        if (isset($send_docket_ids[$akey]) && is_array($send_docket_ids[$akey]) && count($send_docket_ids[$akey]) > 0) {
                                            $updateq = "UPDATE docket SET customer_mis_send=1 WHERE id IN(" . implode(",", $send_docket_ids[$akey]) . ")";
                                            $this->db->query($updateq);
                                        }
                                    }
                                }

                                if ($customer_id > 0) {
                                    $response = array(
                                        'status' => 'success',
                                        'message' => 'EMAIL SENT SUCCESSFULLY'
                                    );
                                }
                            } else {
                                if ($customer_id > 0) {
                                    $response = array(
                                        'status' => 'failed',
                                        'message' => 'NO DATA FOUND TO SEND EMAIL'
                                    );
                                }
                            }
                        } else {
                            if ($customer_id > 0) {
                                $response = array(
                                    'status' => 'failed',
                                    'message' => 'RECEIPIENT EMAIL ID NOT FOUND'
                                );
                            }
                        }
                    } else {
                        if ($customer_id > 0) {
                            $response = array(
                                'status' => 'failed',
                                'message' => 'EMAIL CANNOT SENT TODAY AS PER SETTING'
                            );
                        }
                    }
                }
            } else {
                if ($customer_id > 0) {
                    $response = array(
                        'status' => 'failed',
                        'message' => 'NO EMAIL BODY FOUND'
                    );
                }
            }
        } else {
            if ($customer_id > 0) {
                $response = array(
                    'status' => 'failed',
                    'message' => 'SETUP PROPER MIS EMAIL SETTING'
                );
            }
        }
        if ($customer_id > 0) {
            echo json_encode($response);
        }
    }
    public function send_report_email()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->helper('email');
        $this->load->model('Global_model', 'gm');


        $query = "SELECT * FROM email_queue WHERE status IN(1,2) AND send_status=1 
        AND receiver_email!='' AND email_send_datetime <='" . date('Y-m-d H:i:s') . "' LIMIT 100";
        $query_exec = $this->db->query($query);
        $result = $query_exec->result_array();
        if (isset($result) && is_array($result) && count($result) > 0) {
            foreach ($result as $key => $value) {
                $attachment = array();

                $qry = "SELECT co.* FROM email_configuration co 
                 WHERE co.status IN(1) AND co.id='" . $value['email_configuration_id'] . "'";
                $query_exec = $this->db->query($query);
                $email_credential = $query_exec->result_array();
                if (isset($email_credential) && is_array($email_credential) && count($email_credential) > 0) {
                    $attachment[] = $value['email_attachment'];
                    send_email_msg($value['email_configuration_id'], $value['email_subject'], $value['email_body'], $value['receiver_email'], '', $value['cc_email'], $attachment);
                    $update_data = array(
                        'send_status' => 2,
                        'send_datetime' => date('Y-m-d H:i:s')
                    );
                    $this->gm->update('email_queue', $update_data, '', array('id' => $value['id']));
                } else {
                    $update_data = array(
                        'failed_status' => 'Email Credential not found',
                        'send_status' => 3
                    );
                    $this->gm->update('email_queue', $update_data, '', array('id' => $value['id']));
                }
            }
        }
    }
}
