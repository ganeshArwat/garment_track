<?php
class Vendor_mis extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function insert_custom_report_email($co_vendor_id = 0)
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->helper('email');
        $this->load->model('Global_model', 'gm');
        $this->load->module('report/custom_report');

        //CHECK CUSTOMER WHOSE MIS REPORT ENABLE
        $append = '';
        if ($co_vendor_id > 0) {
            $append = " AND id='" . $co_vendor_id . "'";
        }
        $query = "SELECT id,receiver_auto_mis_report,calculate_tat,check_tat FROM co_vendor 
        WHERE status IN(1,2) AND vendor_send_auto_mis_email=1 AND receiver_auto_mis_report!=''" . $append;
        $query_exec = $this->db->query($query);
        $result = $query_exec->result_array();

        if (isset($result) && is_array($result) && count($result) > 0) {
            foreach ($result as $key => $value) {
                $vendor_id_arr[$value['id']] = $value['id'];
                $vendor_res[$value['id']] = $value;
            }
        } else {
            if ($co_vendor_id > 0) {
                $response = array(
                    'status' => 'failed',
                    'message' => 'MIS EMAIL DISABLE FOR THIS VENDOR'
                );
            }
        }

        if (isset($vendor_id_arr) && is_array($vendor_id_arr) && count($vendor_id_arr) > 0) {
            $query = "SELECT * FROM email_cron_setting WHERE status IN(1,2) AND module_type=2 AND email_type='vendor_mis'
             AND module_id IN(" . implode(",", $vendor_id_arr) . ") GROUP BY module_type,module_id";
            $query_exec = $this->db->query($query);
            $email_result = $query_exec->result_array();
        }
        $tracking_event_id = $this->config->item('tracking_event_id');
        $delivery_state_id = $tracking_event_id['delivered'];
        if (isset($email_result) && is_array($email_result) && count($email_result) > 0) {

            $qry = "SELECT te.* FROM trigger_email_setting te 
            JOIN email_configuration co ON(co.id=te.email_configuration_id)
            WHERE te.status IN(1,2) AND co.status IN(1,2) AND te.email_trigger_key='vendor_mis_email'
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
                    } elseif ($evalue['email_cycle'] == 1) {
                        //MANUALLY
                        if ($co_vendor_id > 0) {
                            $send_report = 1;
                        } else {
                            $send_report = 2;
                        }
                    }


                    if ($send_report == 1) {
                        $company_name = '';
                        $email_subject = $msg_data['email_subject'];
                        $email_subject = str_ireplace("[[company]]", $company_name, $email_subject);

                        $email_body = $msg_data['email_body'];
                        $email_body = str_ireplace("[[company]]", $company_name, $email_body);
                        $email_body = str_ireplace("[[content]]", '', $email_body);

                        $data['msg_body'] = $email_body;
                        $html_body = $this->load->view('email_body/vendor_mis_email', $data, TRUE);

                        $time1 = '09:00:00';

                        $receiver_email = isset($vendor_res[$evalue['module_id']]) ? $vendor_res[$evalue['module_id']]['receiver_auto_mis_report'] : '';

                        if ($receiver_email != '') {
                            //create attachment file
                            $report_filter = array(
                                'co_vendor_id' =>  $evalue['module_id'],
                                'not_state_id' => $delivery_state_id,
                            );

                            $report_filter['email_type'] = 'vendor_mis';

                            $report_filter['status_id'][] = 1;
                            $report_filter['status_id'][] = 2;
                            $report_filter['check_tat'] = isset($vendor_res[$evalue['module_id']]) ? $vendor_res[$evalue['module_id']]['check_tat'] : 1;
                            $report_filter['calculate_tat']  = isset($vendor_res[$evalue['module_id']]) ? $vendor_res[$evalue['module_id']]['calculate_tat'] : 1;

                            $attachment_file = array();
                            $attachment_file_name = $this->custom_report->export_data($evalue['custom_report_id'], $report_filter);
                            if ($attachment_file_name != '') {
                                $attachment_file[] =  $attachment_file_name;
                            }
                            if (isset($attachment_file) && is_array($attachment_file) && count($attachment_file) > 0) {
                                foreach ($attachment_file as $akey => $avalue) {

                                    if ($co_vendor_id > 0) {
                                        send_email_msg($msg_data['email_configuration_id'], $email_subject, $html_body, $receiver_email, '', $msg_data['cc_email'], $avalue);
                                    } else {
                                        $insert_data = array(
                                            'email_configuration_id' => $msg_data['email_configuration_id'],
                                            'receiver_email' => $receiver_email,
                                            'email_subject' => $email_subject,
                                            'email_body' => $html_body,
                                            'email_attachment' => $avalue,
                                            'description' => 'Vendor mis report',
                                            'send_status' => 1,
                                            'module_id' => $evalue['module_id'],
                                            'module_type' => $evalue['module_type'],
                                            'created_date' => date('Y-m-d H:i:s')
                                        );


                                        if ($time1 != '') {
                                            $insert_data['email_send_datetime'] =  date('Y-m-d') . " " . $time1;
                                            $this->gm->insert('email_queue', $insert_data);
                                        }
                                    }
                                }
                                if ($co_vendor_id > 0) {
                                    $response = array(
                                        'status' => 'success',
                                        'message' => 'EMAIL SENT SUCCESSFULLY'
                                    );
                                }
                            } else {
                                if ($co_vendor_id > 0) {
                                    $response = array(
                                        'status' => 'failed',
                                        'message' => 'NO DATA FOUND TO SEND EMAIL'
                                    );
                                }
                            }
                        } else {
                            if ($co_vendor_id > 0) {
                                $response = array(
                                    'status' => 'failed',
                                    'message' => 'RECEIPIENT EMAIL ID NOT FOUND'
                                );
                            }
                        }
                    } else {
                        if ($co_vendor_id > 0) {
                            $response = array(
                                'status' => 'failed',
                                'message' => 'EMAIL CANNOT SENT TODAY AS PER SETTING'
                            );
                        }
                    }
                }
            } else {
                if ($co_vendor_id > 0) {
                    $response = array(
                        'status' => 'failed',
                        'message' => 'NO EMAIL BODY FOUND'
                    );
                }
            }
        } else {
            if ($co_vendor_id > 0) {
                $response = array(
                    'status' => 'failed',
                    'message' => 'SETUP PROPER MIS EMAIL SETTING'
                );
            }
        }

        if ($co_vendor_id > 0) {
            echo json_encode($response);
        }
    }
}
