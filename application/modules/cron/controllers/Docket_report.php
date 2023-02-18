<?php
class Docket_report extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function send_docket_email()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->helper('email');
        $this->load->model('Global_model', 'gm');

        //CHECK SETTING ENABLE OR NOT
        $setting = get_all_app_setting(" AND module_name IN('general')");
        file_put_contents(FCPATH . 'log1/docket_email_cron.txt', date('Y-m-d-H-i-s-a') . "\n", FILE_APPEND);

        if (isset($setting['send_deleted_awb_report']) && $setting['send_deleted_awb_report'] == 1) {

            if (isset($setting['deleted_awb_report_email']) && $setting['deleted_awb_report_email'] != '') {
                //CHECK EMAIL SETUP
                $qry = "SELECT te.* FROM trigger_email_setting te 
                JOIN email_configuration co ON(co.id=te.email_configuration_id)
                WHERE te.status IN(1,2) AND co.status IN(1,2) AND te.email_trigger_key='deleted_awb_report'";
                $qry_exe = $this->db->query($qry);
                $msg_data = $qry_exe->row_array();

                if (isset($msg_data) && is_array($msg_data) && count($msg_data) > 0) {
                    $email_subject = $msg_data['email_subject'];
                    $data['msg_body'] = $msg_data['email_body'];
                    $html_body = $this->load->view('email_body/general_email', $data, TRUE);

                    $receiver_email = $setting['deleted_awb_report_email'];

                    $filename2 = create_year_dir('deleted_awb_report_email');

                    $filename = 'docket-report-' . date('d-M-Y-h-i-s') . '.csv';
                    $file_save_path =  $filename2 . '/' . $filename;
                    $handle = fopen($file_save_path, 'w');

                    $line = array("AWB NO.", "IS DELETED", "DELETED EMAIL ID", "IS RE-PRINT", "REPRINT EMAIL ID", "IS WT CHANGE", "WT CHANGE EMAIL ID");
                    fputcsv($handle, $line);
                    unset($line);

                    //get all docket of yesterday
                    $today = date('Y-m-d');
                    $booking_data = date("Y-m-d", strtotime("-1 days", strtotime($today)));

                    $qry = "SELECT d.id,d.awb_no,d.wt_change_by,d.wt_change_by_type,d.wt_change_datetime
                            FROM docket d WHERE d.status=1  
                            AND DATE_FORMAT(d.wt_change_datetime, '%Y-%m-%d')='" . $booking_data . "'";
                    $qry_exe = $this->db->query($qry);
                    $wt_data = $qry_exe->result_array();

                    $qry = "SELECT d.id,d.awb_no,d.deleted_by,d.deleted_by_type,d.deleted_datetime
                            FROM docket d WHERE d.status=3
                            AND DATE_FORMAT(d.deleted_datetime, '%Y-%m-%d')='" . $booking_data . "'";
                    $qry_exe = $this->db->query($qry);
                    $deleted_data = $qry_exe->result_array();

                    $qry = "SELECT d.id,d.awb_no,d.reprint_by,d.reprint_by_type,d.reprint_datetime
                            FROM docket d WHERE d.status=1  
                            AND DATE_FORMAT(d.reprint_datetime, '%Y-%m-%d')='" . $booking_data . "'";
                    $qry_exe = $this->db->query($qry);
                    $reprint_data = $qry_exe->result_array();

                    $all_cust = get_all_customer();
                    $all_user = get_all_user();
                    if (isset($wt_data) && is_array($wt_data) && count($wt_data) > 0) {
                        foreach ($wt_data as $key => $value) {
                            $row_data[$value['id']]['awb_no'] = $value['awb_no'];
                            $row_data[$value['id']]['is_wt_change'] = "TRUE";
                            if ($value['wt_change_by_type'] == 2) {
                                $row_data[$value['id']]['wt_change_email'] = "PORTAL- " . $all_cust[$value['wt_change_by']]['code'];
                            } else {
                                $row_data[$value['id']]['wt_change_email'] = $all_user[$value['wt_change_by']]['email'];
                            }
                        }
                    }
                    if (isset($deleted_data) && is_array($deleted_data) && count($deleted_data) > 0) {
                        foreach ($deleted_data as $key => $value) {
                            $row_data[$value['id']]['awb_no'] = $value['awb_no'];
                            $row_data[$value['id']]['is_deleted'] = "TRUE";
                            if ($value['deleted_by_type'] == 2) {
                                $row_data[$value['id']]['deleted_email'] = "PORTAL- " . $all_cust[$value['deleted_by']]['code'];
                            } else {
                                $row_data[$value['id']]['deleted_email'] = $all_user[$value['deleted_by']]['email'];
                            }
                        }
                    }

                    if (isset($reprint_data) && is_array($reprint_data) && count($reprint_data) > 0) {
                        foreach ($reprint_data as $key => $value) {
                            $row_data[$value['id']]['awb_no'] = $value['awb_no'];
                            $row_data[$value['id']]['is_reprint'] = "TRUE";
                            if ($value['reprint_by_type'] == 2) {
                                $row_data[$value['id']]['reprint_email'] = "PORTAL- " . $all_cust[$value['reprint_by']]['code'];
                            } else {
                                $row_data[$value['id']]['reprint_email'] = $all_user[$value['reprint_by']]['email'];
                            }
                        }
                    }


                    if (isset($row_data) && is_array($row_data) && count($row_data) > 0) {
                        foreach ($row_data as $key => $value) {
                            $csv_data = array(
                                'awb_no' => $value['awb_no'],
                                'is_deleted' => isset($value['is_deleted']) ?  $value['is_deleted'] : '',
                                'deleted_email' => isset($value['deleted_email']) ?  $value['deleted_email'] : '',
                                'is_reprint' => isset($value['is_reprint']) ?  $value['is_reprint'] : '',
                                'reprint_email' => isset($value['reprint_email']) ?  $value['reprint_email'] : '',
                                'is_wt_change' => isset($value['is_wt_change']) ?  $value['is_wt_change'] : '',
                                'wt_change_email' => isset($value['wt_change_email']) ?  $value['wt_change_email'] : '',
                            );

                            fputcsv($handle, $csv_data);
                            unset($row_data);
                        }
                    }

                    fclose($handle);


                    $attachment[] = $file_save_path;

                    send_email_msg($msg_data['email_configuration_id'], $email_subject, $html_body, $receiver_email, '', $msg_data['cc_email'], $attachment);

                    unlink($file_save_path);
                } else {
                    $message = "EMAIL BODY NOT FOUND";
                }
            } else {
                $message = "DOCKET REPORT EMAILS NOT PRESENT";
            }
        } else {
            $message = "SEND DELETED,REPRINT AND WT CHANGE REPORT - SETTING IS DISABLE";
        }
    }


    public function send_invoice_email()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->helper('email');
        $this->load->model('Global_model', 'gm');

        //CHECK SETTING ENABLE OR NOT
        $setting = get_all_app_setting(" AND module_name IN('general')");
        file_put_contents(FCPATH . 'log1/invoice_email_cron.txt', date('Y-m-d-H-i-s-a') . "\n", FILE_APPEND);

        if (isset($setting['send_invoice_report']) && $setting['send_invoice_report'] == 1) {

            if (isset($setting['invoice_report_email']) && $setting['invoice_report_email'] != '') {
                //CHECK EMAIL SETUP
                $qry = "SELECT te.* FROM trigger_email_setting te 
                JOIN email_configuration co ON(co.id=te.email_configuration_id)
                WHERE te.status IN(1,2) AND co.status IN(1,2) AND te.email_trigger_key='invoice_report_email'";
                $qry_exe = $this->db->query($qry);
                $msg_data = $qry_exe->row_array();

                if (isset($msg_data) && is_array($msg_data) && count($msg_data) > 0) {
                    $email_subject = $msg_data['email_subject'];
                    $data['msg_body'] = $msg_data['email_body'];
                    $html_body = $this->load->view('email_body/general_email', $data, TRUE);

                    $receiver_email = $setting['invoice_report_email'];

                    $this->load->module('report/outstanding_report');

                    $_GET['send_email'] = 1;
                    $report_data = $this->outstanding_report->show_list();

                    $filename2 = create_year_dir('outstanding_email');

                    $filename = 'invoice-report-' . date('d-M-Y-h-i-s') . '.csv';
                    $file_save_path =  $filename2 . '/' . $filename;
                    $handle = fopen($file_save_path, 'w');

                    $line = array(
                        "Hub Code",  "Customer name", "customer code", "Company name", "no. of invoices",
                        "no. of awbs.", "grand total", "outstanding", "total unbilled", "total outstanding"
                    );
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

                            $customer_out[$value['id']] = array(
                                'billed' => $debit_amt - $credit_amt - $unbilled_amt,
                                'unbilled' => $unbilled_amt,
                                'total' => $debit_amt - $credit_amt,
                            );
                        }
                    }

                    $all_cust = get_all_customer();
                    $all_company = get_all_billing_company();
                    $all_hub = get_all_hub();
                    //get all invoice of yesterday
                    $today = date('Y-m-d');
                    $booking_data = date("Y-m-d", strtotime("-1 days", strtotime($today)));
                    $qry = "SELECT i.id,i.customer_id,i.company_master_id,im.docket_id,i.grand_total 
                    FROM docket_invoice i
                    JOIN docket_invoice_map im ON(i.id=im.docket_invoice_id)
                    JOIN docket d ON(d.id=im.docket_id)
                    WHERE i.status=1 AND im.status=1 AND d.status=1
                    AND DATE_FORMAT(i.created_date, '%Y-%m-%d')='" . $booking_data . "'";

                    $qry_exe = $this->db->query($qry);
                    $invoice_data = $qry_exe->result_array();

                    if (isset($invoice_data) && is_array($invoice_data) && count($invoice_data) > 0) {
                        foreach ($invoice_data as $key => $value) {
                            $hub_id = isset($all_cust[$value['customer_id']]) ? $all_cust[$value['customer_id']]['origin_hub_id'] : 0;
                            $hub_invoice[$hub_id][$value['customer_id']][$value['company_master_id']][$value['id']] = $value['id'];
                            $hub_invoice_total[$hub_id][$value['customer_id']][$value['company_master_id']][$value['id']] = $value['grand_total'];
                            $hub_invoice_docket[$hub_id][$value['customer_id']][$value['company_master_id']][$value['docket_id']] = $value['docket_id'];
                        }
                    }


                    if (isset($hub_invoice) && is_array($hub_invoice) && count($hub_invoice) > 0) {
                        foreach ($hub_invoice as $hub_key => $hub_val) {
                            foreach ($hub_val as $cust_key => $cust_val) {
                                foreach ($cust_val as $company_key => $company_val) {
                                    $customer_id = $cust_key;

                                    $row_data = array(
                                        'hub_code' => isset($all_hub[$hub_key]) ? $all_hub[$hub_key]['code'] : '',
                                        'name' => isset($all_cust[$customer_id]) ? $all_cust[$customer_id]['name'] : '',
                                        'code' => isset($all_cust[$customer_id]) ? $all_cust[$customer_id]['code'] : '',
                                        'company' => isset($all_company[$company_key]) ? $all_company[$company_key]['code'] : '',
                                        'invoice_cnt' => is_array($company_val) ? count($company_val)  : 0,
                                        'docket_cnt' => isset($hub_invoice_docket[$hub_key][$cust_key][$company_key]) && is_array($hub_invoice_docket[$hub_key][$cust_key][$company_key]) ? count($hub_invoice_docket[$hub_key][$cust_key][$company_key])  : 0,
                                        'grand_total' => isset($hub_invoice_total[$hub_key][$cust_key][$company_key]) && is_array($hub_invoice_total[$hub_key][$cust_key][$company_key]) ? array_sum($hub_invoice_total[$hub_key][$cust_key][$company_key])  : 0,
                                        'billed' => isset($customer_out[$customer_id]) ? $customer_out[$customer_id]['billed'] : 0,
                                        'unbilled' => isset($customer_out[$customer_id]) ? $customer_out[$customer_id]['unbilled'] : 0,
                                        'total' => isset($customer_out[$customer_id]) ? $customer_out[$customer_id]['total'] : 0,
                                    );

                                    fputcsv($handle, $row_data);
                                    unset($row_data);
                                }
                            }
                        }
                    }

                    fclose($handle);

                    $attachment[] = $file_save_path;

                    send_email_msg($msg_data['email_configuration_id'], $email_subject, $html_body, $receiver_email, '', $msg_data['cc_email'], $attachment);

                    unlink($file_save_path);
                } else {
                    $message = "EMAIL BODY NOT FOUND";
                }
            } else {
                $message = "INVOICE REPORT EMAILS NOT PRESENT";
            }
        } else {
            $message = "SEND TOTAL INVOICE REPORT - SETTING IS DISABLE";
        }
    }

    function calculate_docket_edd()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->helper('email');
        $this->load->model('Global_model', 'gm');
        $setting = get_all_app_setting(" AND module_name IN('docket')");

        $config_key = "edd_cron_" . date('Y-m-d');
        $qry = "SELECT  id,config_value FROM migration_log WHERE status IN(1,2) AND config_key='" . $config_key . "' ";
        $qry_exe = $this->db->query($qry);
        $offset_res = $qry_exe->row_array();

        $limit = 5000;
        if (isset($offset_res['config_value']) && $offset_res['config_value'] != '') {
            $offset = $offset_res['config_value'];
        } else {
            $offset = 0;
        }

        //GET UNDELIVERED DOCCKET
        $qry = "SELECT d.id,d.customer_contract_tat,d.booking_date,d.dispatch_date,d.courier_dispatch_date,
        pbill.vendor_contract_tat FROM docket d
        LEFT OUTER JOIN docket_purchase_billing pbill ON(d.id=pbill.docket_id AND pbill.status=1)
       WHERE d.status=1 AND d.state_id!=10 LIMIT " . $limit . " OFFSET " . $offset;
        $qry_exe = $this->db->query($qry);
        $docket_data = $qry_exe->result_array();

        if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
            $customer_edd_column = "booking_date";
            if (isset($setting['fetch_edd_customer']) && $setting['fetch_edd_customer'] == 2) {
                $customer_edd_column = "dispatch_date";
            } else if (isset($setting['fetch_edd_customer']) && $setting['fetch_edd_customer'] == 3) {
                $customer_edd_column = "courier_dispatch_date";
            }

            $vendor_edd_column = "booking_date";
            if (isset($setting['fetch_edd_vendor']) && $setting['fetch_edd_vendor'] == 2) {
                $vendor_edd_column = "dispatch_date";
            } else if (isset($setting['fetch_edd_vendor']) && $setting['fetch_edd_vendor'] == 3) {
                $vendor_edd_column = "courier_dispatch_date";
            }

            foreach ($docket_data as $key => $value) {
                $customer_tat = (int)$value['customer_contract_tat'];
                $docket_date = $value[$customer_edd_column];
                $today = date('Y-m-d');
                $datediff = strtotime($today) - strtotime($docket_date);
                $day_elasp =  round($datediff / (60 * 60 * 24));
                $cross_edd_cust = (int)$day_elasp - $customer_tat;

                $vendor_tat = (int)$value['vendor_contract_tat'];
                $docket_date = $value[$vendor_edd_column];
                $today = date('Y-m-d');
                $datediff = strtotime($today) - strtotime($docket_date);
                $day_elasp =  round($datediff / (60 * 60 * 24));
                $cross_edd_vendor = (int)$day_elasp - $vendor_tat;

                $updateq = "UPDATE docket SET customer_edd_cross='" . $cross_edd_cust . "',
                vendor_edd_cross='" . $cross_edd_vendor . "' WHERE id='" . $value['id'] . "'";
                $this->db->query($updateq);
            }
            $new_offset = $offset + $limit;
            $qry = "SELECT  id,config_value FROM migration_log WHERE status IN(1,2) AND config_key='" . $config_key . "' ";
            $qry_exe = $this->db->query($qry);
            $configExist = $qry_exe->row_array();
            if (isset($configExist) && is_array($configExist) && count($configExist) > 0) {
                $updateq = "UPDATE migration_log SET config_value='" . $new_offset . "' WHERE status IN(1,2) AND config_key='" . $config_key . "'";
                $this->db->query($updateq);
            } else {
                $mig_insert_data = array(
                    'config_key' => $config_key,
                    'config_value' => $new_offset
                );
                $this->gm->insert('migration_log', $mig_insert_data);
            }
        }
    }
}
