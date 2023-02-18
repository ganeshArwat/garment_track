<?php
class Aging_limit_email extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function insert_aging_limit_email($customer_id = 0)
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
            $append = " AND c.id='" . $customer_id . "'";
        }
        $query = "SELECT c.id,c.billing_email_id,c.aging_due_email_day,c.aging_due_email_cycle,m.module_id FROM customer c 
        JOIN module_setting m ON(c.id=m.module_id AND m.module_type=1 AND m.status IN(1,2))
        WHERE c.status IN(1,2) AND  m.status IN(1,2) AND  m.config_key='customer_aging_due_email'
        AND  m.config_value='1' AND c.contact_no!=''" . $append;
        $query_exec = $this->db->query($query);
        $result = $query_exec->result_array();

        $this->load->module('generic_detail');
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
                    'message' => 'AGING DUE EMAIL DISABLE FOR THIS CUSTOMER'
                );
            }
        }

        $qry = "SELECT te.* FROM trigger_email_setting te 
            JOIN email_configuration co ON(co.id=te.email_configuration_id)
            WHERE te.status IN(1,2) AND co.status IN(1,2) AND te.email_trigger_key='customer_aging_due_email'
            AND te.send_email=1";

        $qry_exe = $this->db->query($qry);
        $msg_data = $qry_exe->row_array();

        if (isset($msg_data) && is_array($msg_data) && count($msg_data) > 0) {

            foreach ($result as $ekey => $evalue) {
                //CHECK WHETHER TO SEND EMAIL TODAY OR NOT
                $send_report = 2;
                if ($evalue['aging_due_email_cycle'] == 2) {
                    //DAILY
                    $send_report = 1;
                } elseif ($evalue['aging_due_email_cycle'] == 3) {
                    //WEEKLY
                    $todayDate = date('Y-m-d');
                    $todayDay = date('N', strtotime($todayDate));
                    if ($todayDay == $evalue['aging_due_email_day']) {
                        $send_report = 1;
                    }
                } elseif ($evalue['aging_due_email_cycle'] == 4) {
                    //MONTHLY
                    $todayDate = date('Y-m-d');
                    $firstDate = date('Y-m-d', strtotime('first day of this month'));
                    if ($todayDate == $firstDate) {
                        $send_report = 1;
                    }
                } elseif ($evalue['aging_due_email_cycle'] == 5) {
                    //TWICE A DAY
                    $send_report = 1;
                } elseif ($evalue['aging_due_email_cycle'] == 1) {
                    //MANUALLY
                    if ($customer_id > 0) {
                        $send_report = 1;
                    } else {
                        $send_report = 2;
                    }
                }


                if ($send_report == 1) {
                    $time1 = "11:00:00";

                    $receiver_email = isset($cust_receiver_email[$evalue['module_id']]) ? $cust_receiver_email[$evalue['module_id']] : '';

                    if ($receiver_email != '') {
                        $attachment_file = array();

                        $note_param['customer_id'] = $evalue['module_id'];

                        $aging_data = $this->generic_detail->get_aging_due_data($note_param);

                        if (isset($aging_data) && is_array($aging_data) && count($aging_data) > 0) {
                            if (isset($_GET['cron_company'])) {
                                $filename = 'client_media/company_' . $_GET['cron_company'] . '/temp/' . 'aging_limit_' . $note_param['customer_id'] . '_' . date('d-M-Y-h-i-A') . '.csv';
                            } else {
                                $filename = 'client_media/temp/' . 'aging_limit_' . $note_param['customer_id'] . '_' . date('d-M-Y-h-i-A') . '_' . rand(1000, 9999) . '.csv';
                            }


                            $handle = fopen(FCPATH . $filename, 'a');

                            $line = array(
                                'Invoice NO', 'Invoice Date', 'Amount', 'Paid Amount', 'Due Date'
                            );
                            fputcsv($handle, $line);
                            unset($line);
                            if (isset($aging_data) && is_array($aging_data) && count($aging_data) > 0) {
                                foreach ($aging_data as $akey => $avalue) {
                                    $line = array(
                                        'invoice_no' => $avalue['type'] == 'Opening Balance' ? 'Opening Balance' : $avalue['invoice_no'],
                                        'ledger_date' => $avalue['ledger_date'],
                                        'total_amt' => $avalue['total_amt'],
                                        'paid_amt' => $avalue['paid_amt'],
                                        'due_date' => $avalue['due_date'],
                                    );
                                    fputcsv($handle, $line);
                                }
                            }
                            fclose($handle);

                            $attachment_file[] =  base_url($filename);
                        }


                        if (isset($attachment_file) && is_array($attachment_file) && count($attachment_file) > 0) {
                            foreach ($attachment_file as $akey => $avalue) {
                                $email_subject = $msg_data['email_subject'];
                                $module_data = array(
                                    'email_configuration_id' => $msg_data['email_configuration_id'],
                                    'module_id' => $evalue['module_id'],
                                    'master_var_id' => $msg_data['id'],
                                    'module_type' => 2, //CUSTOMER OUTSTANDING
                                    'sms_type' => 'email',
                                    'email_subject' => $email_subject,
                                    'email_body' =>  $msg_data['email_body']
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
                                        'description' => 'Customer Aging Limit Email',
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


    public function send_aging_limit_whatsapp($customer_id = 0)
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
            $append = " AND c.id='" . $customer_id . "'";
        }
        $query = "SELECT c.id,c.contact_no,c.aging_due_whatsapp_cycle,c.aging_due_whatsapp_day,m.module_id FROM customer c 
        JOIN module_setting m ON(c.id=m.module_id AND m.module_type=1 AND m.status IN(1,2))
        WHERE c.status IN(1,2) AND  m.status IN(1,2) AND  m.config_key='customer_aging_due_whatsapp'
        AND  m.config_value='1' AND c.contact_no!=''" . $append;
        $query_exec = $this->db->query($query);
        $result = $query_exec->result_array();

        $this->load->module('generic_detail');
        if (isset($result) && is_array($result) && count($result) > 0) {
            foreach ($result as $key => $value) {
                $cust_id_arr[$value['id']] = $value['id'];
                $cust_receiver_contact[$value['id']] = $value['contact_no'];
                $customer_res[$value['id']] = $value;
            }
        } else {
            if ($customer_id > 0) {
                $response = array(
                    'status' => 'failed',
                    'message' => 'AGING DUE WHATSAPP DISABLE FOR THIS CUSTOMER'
                );
            }
        }



        $all_identifier = all_whatsapp_identifier('', 'config_key');

        $sms_identifier = "customer_aging_due_whatsapp";
        $identifier_id = isset($all_identifier[$sms_identifier]) ? $all_identifier[$sms_identifier]['id'] : 0;
        if ($identifier_id > 0) {
            $qry = "SELECT id,sms_text FROM whatsapp_master WHERE status IN(1,2) AND identifier_id='" . $identifier_id . "'";
            $qry_exe = $this->db->query($qry);
            $msg_data = $qry_exe->row_array();
        }


        if (isset($msg_data) && is_array($msg_data) && count($msg_data) > 0) {

            foreach ($result as $ekey => $evalue) {
                //CHECK WHETHER TO SEND EMAIL TODAY OR NOT
                $send_report = 2;
                if ($evalue['aging_due_whatsapp_cycle'] == 2) {
                    //DAILY
                    $send_report = 1;
                } elseif ($evalue['aging_due_whatsapp_cycle'] == 3) {
                    //WEEKLY
                    $todayDate = date('Y-m-d');
                    $todayDay = date('N', strtotime($todayDate));
                    if ($todayDay == $evalue['aging_due_whatsapp_day']) {
                        $send_report = 1;
                    }
                } elseif ($evalue['aging_due_whatsapp_cycle'] == 4) {
                    //MONTHLY
                    $todayDate = date('Y-m-d');
                    $firstDate = date('Y-m-d', strtotime('first day of this month'));
                    if ($todayDate == $firstDate) {
                        $send_report = 1;
                    }
                } elseif ($evalue['aging_due_whatsapp_cycle'] == 5) {
                    //TWICE A DAY
                    $send_report = 1;
                } elseif ($evalue['aging_due_whatsapp_cycle'] == 1) {
                    //MANUALLY
                    if ($customer_id > 0) {
                        $send_report = 1;
                    } else {
                        $send_report = 2;
                    }
                }


                if ($send_report == 1) {
                    $contact_no = isset($cust_receiver_contact[$evalue['module_id']]) ? $cust_receiver_contact[$evalue['module_id']] : '';

                    if ($contact_no != '') {
                        $resposne =  send_aging_limit_whatsapp($evalue['module_id']);
                        if (isset($resposne['error'])) {
                            $response = array(
                                'status' => 'failed',
                                'message' => $resposne['error']
                            );
                        }
                    } else {
                        if ($customer_id > 0) {
                            $response = array(
                                'status' => 'failed',
                                'message' => 'RECEIPIENT CONTACT NO NOT FOUND'
                            );
                        }
                    }
                } else {
                    if ($customer_id > 0) {
                        $response = array(
                            'status' => 'failed',
                            'message' => 'WHATSAPP CANNOT SENT TODAY AS PER SETTING'
                        );
                    }
                }
            }
        } else {
            if ($customer_id > 0) {
                $response = array(
                    'status' => 'failed',
                    'message' => 'NO WHATSAPP BODY FOUND'
                );
            }
        }

        if ($customer_id > 0) {
            echo json_encode($response);
        }
    }
}
