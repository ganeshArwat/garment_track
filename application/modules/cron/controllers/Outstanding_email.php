<?php
class Outstanding_email extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function send_outstanding_email()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->helper('email');
        $this->load->model('Global_model', 'gm');

        //CHECK SETTING ENABLE OR NOT
        $setting = get_all_app_setting(" AND module_name IN('general')");
        file_put_contents(FCPATH . 'log1/outstanding_email_cron.txt', date('Y-m-d-H-i-s-a') . "\n", FILE_APPEND);

        if (isset($setting['send_total_oustanding_report']) && $setting['send_total_oustanding_report'] == 1) {

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

                    $filename = 'outstanding-report-' . date('d-M-Y-h-i-s') . '.csv';
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

                    if (isset($_GET['test'])) {
                        echo '<pre>';
                        print_r($attachment);
                        exit;
                    }

                    send_email_msg($msg_data['email_configuration_id'], $email_subject, $html_body, $receiver_email, '', $msg_data['cc_email'], $attachment);

                    unlink($file_save_path);
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
