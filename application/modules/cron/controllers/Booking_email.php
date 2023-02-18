<?php
class Booking_email extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function send_booking_data()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->helper('email');
        $this->load->helper('message');
        $this->load->model('Global_model', 'gm');
        file_put_contents(FCPATH . 'log1/booking_email_cron.txt', date('Y-m-d-H-i-s-a') . "\n", FILE_APPEND);

        //get all docket of yesterday
        $today = date('Y-m-d');
        $booking_data = date("Y-m-d", strtotime("-1 days", strtotime($today)));

        $qry = "SELECT d.id,d.booking_date,d.awb_no,d.forwarding_no,d.destination_id,d.total_pcs,d.actual_wt,d.chargeable_wt,d.reference_no,d.company_id,
        d.vendor_id,d.customer_id,d.product_id,dcon.name as con_name,dshi.name as shi_name,cust.operation_email_id FROM docket d
        JOIN customer cust ON(cust.id=d.customer_id)
        LEFT OUTER JOIN docket_consignee dcon ON(d.id=dcon.docket_id AND dcon.status IN(1,2))
        LEFT OUTER JOIN docket_shipper dshi ON(d.id=dshi.docket_id AND dshi.status IN(1,2)) 
        WHERE d.status IN(1,2) AND cust.status IN(1,2) AND cust.send_booking_daily=1 AND cust.operation_email_id!='' 
        AND d.booking_date='" . $booking_data . "'";
        $qry_exe = $this->db->query($qry);
        $docket_data = $qry_exe->result_array();

        if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
            foreach ($docket_data as $dkey => $dvalue) {
                $customer_docket[$dvalue['customer_id']][$dvalue['id']] = $dvalue;
                $customer_docket_ids[$dvalue['customer_id']][$dvalue['id']] = $dvalue['id'];
                $customer_email[$dvalue['customer_id']] = $dvalue['operation_email_id'];
                $customer_id_arr[$dvalue['customer_id']] = $dvalue['customer_id'];
                $location_id_arr[$dvalue['destination_id']] = $dvalue['destination_id'];
                $company_id_arr[$dvalue['company_id']] = $dvalue['company_id'];
            }
        }



        if (isset($customer_id_arr) && is_array($customer_id_arr) && count($customer_id_arr) > 0) {
            $all_customer = get_all_customer(" AND id IN(" . implode(",", $customer_id_arr) . ")");
        }
        if (isset($location_id_arr) && is_array($location_id_arr) && count($location_id_arr) > 0) {
            $all_location = get_all_location(" AND id IN(" . implode(",", $location_id_arr) . ")");
        }

        $all_vendor = get_all_vendor();
        $all_company = get_all_billing_company(" AND id=1");
        if (isset($customer_id_arr) && is_array($customer_id_arr) && count($customer_id_arr) > 0) {
            $all_customer = get_all_customer(" AND id IN(" . implode(",", $customer_id_arr) . ")");
        }
        $get_all_company = get_all_billing_company();

        //SEND CUSTOMER WISE DOCKET EMAIL
        if (isset($customer_docket) && is_array($customer_docket) && count($customer_docket) > 0) {
            //get mail data
            $qry = "SELECT te.* FROM trigger_email_setting te 
             JOIN email_configuration co ON(co.id=te.email_configuration_id)
             WHERE te.status IN(1,2) AND co.status IN(1,2) AND te.email_trigger_key='booking_email_daily'
             AND te.send_email=1";
            $qry_exe = $this->db->query($qry);
            $msg_data = $qry_exe->row_array();

            if (isset($msg_data) && is_array($msg_data) && count($msg_data) > 0) {
                foreach ($customer_docket as $ckey => $cvalue) {
                    $data = array();
                    $email_subject = $msg_data['email_subject'];

                    $email_body = $msg_data['email_body'];

                    $data['msg_body'] = $email_body;
                    $data['docket_data'] = $cvalue;
                    $data['all_location'] = $all_location;
                    $data['all_vendor'] = $all_vendor;
                    $data['get_all_company'] = $get_all_company;

                    $html_body = $this->load->view('email_body/booking_daily_docket', $data, TRUE);


                    //USE THIS TO SEND DYNAMIC TABLE IN EMAIL 
                    // $master_ids = isset($customer_docket_ids[$ckey]) ? $customer_docket_ids[$ckey] : array();
                    // if (isset($master_ids) && is_array($master_ids) && count($master_ids) > 0) {
                    //     $html_body = get_email_table_data($master_ids, $msg_data['id']);
                    // }
                    // echo  $html_body;
                    // exit;

                    // $customer_email_id = isset($all_customer[$ckey]) ? $all_customer[$ckey]['email_id'] : '';
                    $docket_cust_id = 0;
                    if (isset($cvalue) && is_array($cvalue) && count($cvalue) > 0) {
                        foreach ($cvalue as $cust_key => $cust_val) {
                            $docket_cust_id = $cust_val['id'];
                        }
                    }


                    if (isset($customer_email[$ckey]) && $customer_email[$ckey] != '') {
                        $receipient_email = $customer_email[$ckey];
                        $module_data = array(
                            'email_configuration_id' => $msg_data['email_configuration_id'],
                            'master_var_id' => $msg_data['id'],
                            'module_id' => $docket_cust_id,
                            'module_type' => 1, //DOCKET
                            'message' => $html_body,
                            'email_subject' => $email_subject,
                            'sms_type' => 'email'
                        );
                        $dynamic_res = get_dynamic_email_body($module_data);

                        $html_body = $dynamic_res['message'];
                        $email_subject = $dynamic_res['email_subject'];
                        send_email_msg($msg_data['email_configuration_id'], $email_subject, $html_body, $receipient_email, '', $msg_data['cc_email'], array());
                    }
                }
            }
        }
    }


    public function send_extra_charge_data()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->helper('email');
        $this->load->helper('message');
        $this->load->model('Global_model', 'gm');

        //get all docket of yesterday
        $today = date('Y-m-d');
        $booking_data = date("Y-m-d", strtotime("-1 days", strtotime($today)));

        $chargeq = "SELECT c.id,c.docket_id,c.charge_id,c.rate_mod_id,c.charge_amount,
        d.awb_no,d.booking_date,cust.operation_email_id,d.customer_id
         FROM docket d
         JOIN customer cust ON(cust.id=d.customer_id)
         JOIN docket_charges c ON(d.id=c.docket_id)
         JOIN module_setting m ON(cust.id=m.module_id AND m.module_type=1 AND m.status IN(1,2)) 
         WHERE d.status IN(1,2) AND cust.status IN(1,2) AND c.status IN(1,2) 
         AND cust.operation_email_id!=''  AND c.billing_type=1 AND c.charge_amount>0
        AND d.booking_date='" . $booking_data . "' AND m.config_key='extra_charge_email' AND m.config_value=1";

        $chargeq_Exe = $this->db->query($chargeq);
        $docket_data = $chargeq_Exe->result_array();

        if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
            foreach ($docket_data as $dkey => $dvalue) {
                $customer_docket[$dvalue['customer_id']][$dvalue['id']] = $dvalue;
                $customer_email[$dvalue['customer_id']] = $dvalue['operation_email_id'];
                $customer_id_arr[$dvalue['customer_id']] = $dvalue['customer_id'];
            }
        }

        $all_company = get_all_billing_company(" AND id=1");

        //SEND CUSTOMER WISE DOCKET EMAIL
        if (isset($customer_docket) && is_array($customer_docket) && count($customer_docket) > 0) {
            //get mail data
            $qry = "SELECT te.* FROM trigger_email_setting te 
             JOIN email_configuration co ON(co.id=te.email_configuration_id)
             WHERE te.status IN(1,2) AND co.status IN(1,2) AND te.email_trigger_key='extra_charge_email'
             AND te.send_email=1";
            $qry_exe = $this->db->query($qry);
            $msg_data = $qry_exe->row_array();
            if (isset($msg_data) && is_array($msg_data) && count($msg_data) > 0) {
                foreach ($customer_docket as $ckey => $cvalue) {
                    $data = array();
                    $company_name = isset($all_company[1]) ? $all_company[1]['name'] : '';

                    $email_subject = $msg_data['email_subject'];
                    $email_body = $msg_data['email_body'];

                    $module_data = array(
                        'master_var_id' => $msg_data['id'],
                        'module_id' => $ckey,
                        'module_type' => 2, //CUSTOMER
                        'message' => $email_body,
                        'sms_type' => 'email',
                        'email_subject' => $email_subject
                    );
                    $dynamic_res = get_dynamic_email_body($module_data);

                    $html_body = $dynamic_res['message'];
                    $email_subject = $dynamic_res['email_subject'];
                    $data['docket_charge'] = $cvalue;

                    $data['all_charge'] = get_all_charge('', 'id,name');
                    $data['msg_body'] = $html_body;
                    $html_body = $this->load->view('email_body/extra_charge_per_docket', $data, true);

                    if (isset($customer_email[$ckey]) && $customer_email[$ckey] != '') {
                        $receipient_email = $customer_email[$ckey];
                        send_email_msg($msg_data['email_configuration_id'], $email_subject, $html_body, $receipient_email, '', $msg_data['cc_email'], array());
                    }
                }
            }
        }
    }


    public function send_company_booking_email()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->helper('email');
        $this->load->model('Global_model', 'gm');

        //CHECK SETTING ENABLE OR NOT
        $setting = get_all_app_setting(" AND module_name IN('general')");

        if (isset($setting['send_total_booking_report']) && $setting['send_total_oustanding_report'] == 1) {

            if (isset($setting['total_outstanding_report_email']) && $setting['total_outstanding_report_email'] != '') {
                //CHECK EMAIL SETUP
                $qry = "SELECT te.* FROM trigger_email_setting te 
                JOIN email_configuration co ON(co.id=te.email_configuration_id)
                WHERE te.status IN(1,2) AND co.status IN(1,2) AND te.email_trigger_key='company_outstanding_email'";
                $qry_exe = $this->db->query($qry);
                $msg_data = $qry_exe->row_array();

                if (isset($msg_data) && is_array($msg_data) && count($msg_data) > 0) {
                    $email_subject = $msg_data['email_subject'];
                    $data['msg_body'] = $msg_data['email_body'];
                    $html_body = $this->load->view('email_body/general_email', $data, TRUE);

                    $receiver_email = $setting['total_outstanding_report_email'];

                    $this->load->module('report/outstanding_report');

                    $_GET['send_email'] = 1;
                    $report_data = $this->outstanding_report->show_list();

                    $filename2 = create_year_dir('outstanding_email');

                    $filename = 'outstanding-report-' . date('d-M-Y') . '.csv';
                    $file_save_path =  $filename2 . '/' . $filename;
                    $handle = fopen($file_save_path, 'w');

                    $line = array("Customer Code", "Customer Name", "Billed Outstanding", "Unbilled Outstanding", "Total Outstanding");
                    fputcsv($handle, $line);
                    unset($line);

                    $customer_data = isset($report_data['customer_data']) ? $report_data['customer_data'] : array();
                    $unbilled_amt_data = isset($report_data['unbilled_amt_data']) ? $report_data['unbilled_amt_data'] : array();
                    $amount_data = isset($report_data['amount_data']) ? $report_data['amount_data'] : array();

                    if (isset($customer_data) && is_array($customer_data) && count($customer_data) > 0) {
                        foreach ($customer_data as $key => $value) {
                            $unbilled_amt = isset($unbilled_amt_data[$value['id']]) && $unbilled_amt_data[$value['id']] > 0 ? $unbilled_amt_data[$value['id']] : 0;
                            $credit_amt = isset($amount_data[1][$value['id']]) && $amount_data[1][$value['id']] > 0 ? $amount_data[1][$value['id']] : 0;
                            $debit_amt = isset($amount_data[2][$value['id']]) && $amount_data[2][$value['id']] > 0 ? $amount_data[2][$value['id']] : 0;
                            $debit_amt = $debit_amt + $unbilled_amt;

                            $row_data = array(
                                'code' => $value['code'],
                                'name' => $value['name'],
                                'billed' => $debit_amt - $credit_amt - $unbilled_amt,
                                'unbilled' => $unbilled_amt,
                                'total' => $debit_amt - $credit_amt,
                            );
                            fputcsv($handle, $row_data);
                            unset($row_data);
                        }
                    }

                    fclose($handle);

                    $attachment[] = $file_save_path;
                    send_email_msg($msg_data['email_configuration_id'], $email_subject, $html_body, $receiver_email, '', $msg_data['cc_email'], $attachment);
                } else {
                    $message = "EMAIL BODY NOT FOUND";
                }
            } else {
                $message = "OUTSTANDING REPORT EMAILS NOT PRESENT";
            }
        } else {
            $message = "SEND TOTAL OUTSTANDING REPORT - SETTING IS DISABLE";
        }
    }
}
