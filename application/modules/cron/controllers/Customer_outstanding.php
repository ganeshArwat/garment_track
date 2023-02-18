<?php
class Customer_outstanding extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function insert_custom_report_email($customer_id = 0)
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->helper('message');
        $this->load->helper('email');
        $this->load->model('Global_model', 'gm');
        $this->load->module('account/ledger');

        //CHECK CUSTOMER WHOSE OUTSTANDING ENABLE
        $append = '';
        if ($customer_id > 0) {
            $append = " AND id='" . $customer_id . "'";
        }
        $query = "SELECT id,billing_email_id FROM customer 
        WHERE status IN(1,2) AND send_outstanding_email=1 AND billing_email_id!=''" . $append;
        $query_exec = $this->db->query($query);
        $result = $query_exec->result_array();


        if (isset($result) && is_array($result) && count($result) > 0) {
            foreach ($result as $key => $value) {
                $cust_id_arr[$value['id']] = $value['id'];
                $cust_receiver_email[$value['id']] = $value['billing_email_id'];
                $customer_res[$value['id']] = $value;
            }
        } else {
            if ($customer_id > 0) {
                $response = array(
                    'status' => 'failed',
                    'message' => 'OUTSTANDING EMAIL DISABLE FOR THIS CUSTOMER'
                );
            }
        }

        if (isset($cust_id_arr) && is_array($cust_id_arr) && count($cust_id_arr) > 0) {
            $query = "SELECT * FROM email_cron_setting WHERE status IN(1,2) AND module_type=1 AND email_type='customer_outstanding'
            AND module_id IN(" . implode(",", $cust_id_arr) . ") GROUP BY module_type,module_id";
            $query_exec = $this->db->query($query);
            $email_result = $query_exec->result_array();
        }


        if (isset($email_result) && is_array($email_result) && count($email_result) > 0) {

            $qry = "SELECT te.* FROM trigger_email_setting te 
            JOIN email_configuration co ON(co.id=te.email_configuration_id)
            WHERE te.status IN(1,2) AND co.status IN(1,2) AND te.email_trigger_key='customer_outstanding_email'
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


                    if ($send_report == 1) {


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

                        $data['msg_body'] = $email_body;
                        $html_body = $this->load->view('email_body/customer_outstanding_email', $data, TRUE);

                        if ($evalue['email_cycle'] == 5) {
                            $time1 = $evalue['email_time1'];
                            $time2 = $evalue['email_time1'];
                        } else {
                            $time1 = $evalue['email_time1'];
                            $time2 = '';
                        }

                        $receiver_email = isset($cust_receiver_email[$evalue['module_id']]) ? $cust_receiver_email[$evalue['module_id']] : '';

                        if ($receiver_email != '') {
                            //create attachment file
                            $report_filter = array(
                                'customer_id' =>  $evalue['module_id']
                            );
                            if ($evalue['shipment_range'] == 1) {
                                $report_filter['booking_min'] = date('Y-m-01', strtotime('-1 MONTH'));
                                $report_filter['booking_max'] = date('Y-m-t');
                            } else {
                                $report_filter['booking_min'] = date('Y-m-01', strtotime('-1 MONTH'));
                                $report_filter['booking_max'] = date('Y-m-t', strtotime('-1 MONTH'));
                            }

                            $report_filter['email_type'] = 'customer_outstanding';
                            $attachment_file = array();
                            $attachment_file_name = $this->ledger->export_data($report_filter);


                            if ($attachment_file_name != '') {
                                $attachment_file[] =  $attachment_file_name;
                            }


                            if (isset($attachment_file) && is_array($attachment_file) && count($attachment_file) > 0) {
                                foreach ($attachment_file as $akey => $avalue) {

                                    $module_data = array(
                                        'email_configuration_id' => $msg_data['email_configuration_id'],
                                        'module_id' => $evalue['module_id'],
                                        'master_var_id' => $msg_data['id'],
                                        'module_type' => 2, //CUSTOMER OUTSTANDING
                                        'message' => $html_body,
                                        'sms_type' => 'email',
                                        'email_subject' => $email_subject
                                    );
                                    $dynamic_res = get_dynamic_email_body($module_data);
                                    $html_body = $dynamic_res['message'];
                                    $email_subject = $dynamic_res['email_subject'];
                                    if ($customer_id > 0) {
                                        $email_attach[0] = $avalue;
                                        send_email_msg($msg_data['email_configuration_id'], $email_subject, $html_body, $receiver_email, '', $msg_data['cc_email'], $email_attach);
                                    } else {
                                        $insert_data = array(
                                            'email_configuration_id' => $msg_data['email_configuration_id'],
                                            'receiver_email' => $receiver_email,
                                            'email_subject' => $email_subject,
                                            'email_body' => $html_body,
                                            'email_attachment' => $avalue,
                                            'description' => 'Customer OUTSTANDING report',
                                            'send_status' => 1,
                                            'module_id' => $evalue['module_id'],
                                            'module_type' => $evalue['module_type'],
                                            'created_date' => date('Y-m-d H:i:s')
                                        );


                                        if ($time1 != '') {
                                            $insert_data['email_send_datetime'] =  date('Y-m-d') . " " . $time1;
                                            $this->gm->insert('email_queue', $insert_data);
                                        }
                                        if ($time2 != '') {
                                            $insert_data['email_send_datetime'] =  date('Y-m-d') . " " . $time2;
                                            $this->gm->insert('email_queue', $insert_data);
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
                    'message' => 'SETUP PROPER OUTSTANDING EMAIL SETTING'
                );
            }
        }
        if ($customer_id > 0) {
            echo json_encode($response);
        }
    }



    public function insert_outstanding_sms($customer_id = 0)
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');

        $this->load->model('Global_model', 'gm');

        $this->load->helper('email');
        $this->load->helper('sms');
        $this->load->helper('whatsapp');
        $this->load->helper('message');

        $this->load->module('account/ledger');

        //CHECK CUSTOMER WHOSE OUTSTANDING ENABLE
        $append = '';
        if ($customer_id > 0) {
            $append = " AND id='" . $customer_id . "'";
        }
        $query = "SELECT c.id,c.billing_email_id,c.outstand_sms_cycle,c.outstand_sms_day FROM customer c 
        JOIN module_setting m ON(c.id=m.module_id AND m.module_type=1 AND m.status IN(1,2))
        WHERE c.status IN(1,2) AND  m.status IN(1,2) AND  m.config_key='customer_outstanding_sms'
        AND  m.config_value='1' AND c.contact_no!=''" . $append;
        $query_exec = $this->db->query($query);
        $result = $query_exec->result_array();

        if (isset($result) && is_array($result) && count($result) > 0) {
            foreach ($result as $key => $value) {
                $cust_id_arr[$value['id']] = $value['id'];
                $cust_contact_no[$value['id']] = $value['contact_no'];
                $customer_res[$value['id']] = $value;
            }
        } else {
            if ($customer_id > 0) {
                $response = array(
                    'status' => 'failed',
                    'message' => 'OUTSTANDING SMS DISABLE FOR THIS CUSTOMER'
                );
            }
        }
        $all_identifier = all_sms_identifier('', 'config_key');
        $sms_identifier = 'customer_outstanding_sms';
        $identifier_id = isset($all_identifier[$sms_identifier]) ? $all_identifier[$sms_identifier]['id'] : 0;

        $qry = "SELECT id,sms_text,template_name FROM sms_master WHERE status IN(1,2) AND identifier_id='" . $identifier_id . "'";
        $qry_exe = $this->db->query($qry);
        $msg_data = $qry_exe->row_array();

        if (isset($msg_data) && is_array($msg_data) && count($msg_data) > 0) {

            foreach ($result as $ekey => $evalue) {
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
                    if ($customer_id > 0) {
                        $send_report = 1;
                    } else {
                        $send_report = 2;
                    }
                }


                if ($send_report == 1) {


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

                    $data['msg_body'] = $email_body;
                    $html_body = $this->load->view('email_body/customer_outstanding_email', $data, TRUE);

                    if ($evalue['email_cycle'] == 5) {
                        $time1 = $evalue['email_time1'];
                        $time2 = $evalue['email_time1'];
                    } else {
                        $time1 = $evalue['email_time1'];
                        $time2 = '';
                    }

                    $receiver_email = isset($cust_receiver_email[$evalue['module_id']]) ? $cust_receiver_email[$evalue['module_id']] : '';

                    if ($receiver_email != '') {
                        //create attachment file
                        $report_filter = array(
                            'customer_id' =>  $evalue['module_id']
                        );
                        if ($evalue['shipment_range'] == 2) {
                            $report_filter['booking_min'] = date('Y-m-01', strtotime('-1 MONTH'));
                            $report_filter['booking_max'] = date('Y-m-t');
                        } else {
                            $report_filter['booking_min'] = date('Y-m-01', strtotime('-1 MONTH'));
                            $report_filter['booking_max'] = date('Y-m-t', strtotime('-1 MONTH'));
                        }
                        $report_filter['email_type'] = 'customer_outstanding';
                        $attachment_file = array();
                        $attachment_file_name = $this->ledger->export_data($report_filter);
                        $pdf_filter_data = $report_filter;
                        $pdf_filter_data['mode_download'] = 'donwload_pdf';
                        $attachment_pdf_file_name = $this->ledger->print_ledger($pdf_filter_data);

                        if ($attachment_file_name != '') {
                            $attachment_file[] =  $attachment_file_name;
                        }

                        if ($attachment_pdf_file_name != '') {
                            $attachment_file[] =  $attachment_pdf_file_name;
                        }


                        if (isset($attachment_file) && is_array($attachment_file) && count($attachment_file) > 0) {
                            foreach ($attachment_file as $akey => $avalue) {

                                $module_data = array(
                                    'email_configuration_id' => $msg_data['email_configuration_id'],
                                    'module_id' => $evalue['module_id'],
                                    'module_type' => 2, //CUSTOMER OUTSTANDING
                                    'message' => $html_body,
                                    'email_subject' => $email_subject
                                );
                                $dynamic_res = get_dynamic_email_body($module_data);
                                $html_body = $dynamic_res['message'];
                                $email_subject = $dynamic_res['email_subject'];
                                if ($customer_id > 0) {
                                    $email_attach[0] = $avalue;
                                    send_email_msg($msg_data['email_configuration_id'], $email_subject, $html_body, $receiver_email, '', $msg_data['cc_email'], $email_attach);
                                } else {
                                    $insert_data = array(
                                        'email_configuration_id' => $msg_data['email_configuration_id'],
                                        'receiver_email' => $receiver_email,
                                        'email_subject' => $email_subject,
                                        'email_body' => $html_body,
                                        'email_attachment' => $avalue,
                                        'description' => 'Customer OUTSTANDING report',
                                        'send_status' => 1,
                                        'module_id' => $evalue['module_id'],
                                        'module_type' => $evalue['module_type'],
                                        'created_date' => date('Y-m-d H:i:s')
                                    );


                                    if ($time1 != '') {
                                        $insert_data['email_send_datetime'] =  date('Y-m-d') . " " . $time1;
                                        $this->gm->insert('email_queue', $insert_data);
                                    }
                                    if ($time2 != '') {
                                        $insert_data['email_send_datetime'] =  date('Y-m-d') . " " . $time2;
                                        $this->gm->insert('email_queue', $insert_data);
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

        if ($customer_id > 0) {
            echo json_encode($response);
        }
    }

    function test($customer_id)
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->helper('message');
        $this->load->helper('email');
        $this->load->model('Global_model', 'gm');
        $this->load->module('account/ledger');

        $qry = "SELECT te.* FROM trigger_email_setting te 
        JOIN email_configuration co ON(co.id=te.email_configuration_id)
        WHERE te.status IN(1,2) AND co.status IN(1,2) AND te.email_trigger_key='customer_outstanding_email'
        AND te.send_email=1";
        $qry_exe = $this->db->query($qry);
        $msg_data = $qry_exe->row_array();

        $email_subject = $msg_data['email_subject'];

        $email_body = $msg_data['email_body'];
        $data['msg_body'] = $email_body;
        $html_body = $this->load->view('email_body/customer_outstanding_email', $data, TRUE);


        $module_data = array(
            'email_configuration_id' => $msg_data['email_configuration_id'],
            'module_id' => $customer_id,
            'master_var_id' => $msg_data['id'],
            'module_type' => 2, //CUSTOMER OUTSTANDING
            'message' => $html_body,
            'sms_type' => 'email',
            'email_subject' => $email_subject
        );
        $dynamic_res = get_dynamic_email_body($module_data);
        echo '<pre>';
        print_r($dynamic_res);
        exit;
        //create attachment file
        $report_filter = array(
            'customer_id' =>  $customer_id
        );
        $report_filter['booking_min'] = date('Y-m-01', strtotime('-1 MONTH'));
        $report_filter['booking_max'] = date('Y-m-t', strtotime('-1 MONTH'));

        $report_filter['email_type'] = 'customer_outstanding';
        $attachment_file = array();
        $attachment_file_name = $this->ledger->export_data($report_filter);
        $pdf_filter_data = $report_filter;
        $pdf_filter_data['mode_download'] = 'donwload_pdf';
        $attachment_pdf_file_name = $this->ledger->print_ledger($pdf_filter_data);
        echo '<pre>';
        print_r($attachment_pdf_file_name);
        exit;
    }
}
