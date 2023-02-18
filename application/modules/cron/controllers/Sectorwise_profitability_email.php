<?php
class Sectorwise_profitability_email extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function send_sectorwise_profitability_email()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->helper('email');
        $this->load->model('Global_model', 'gm');

        //CHECK SETTING ENABLE OR NOT
        $setting = get_all_app_setting(" AND module_name IN('general')");
        file_put_contents(FCPATH . 'log1/sectorwise_email_cron.txt', date('Y-m-d-H-i-s-a') . "\n", FILE_APPEND);

        if (isset($setting['enable_sectorwise_report']) && $setting['enable_sectorwise_report'] == 1) {

            if (isset($setting['sectorwise_report_email']) && $setting['sectorwise_report_email'] != '') {
                //CHECK EMAIL SETUP
                $qry = "SELECT te.* FROM trigger_email_setting te 
                JOIN email_configuration co ON(co.id=te.email_configuration_id)
                WHERE te.status IN(1,2) AND co.status IN(1,2) AND te.email_trigger_key='sectorwise_profitability_report'";
                $qry_exe = $this->db->query($qry);
                $msg_data = $qry_exe->row_array();

                if (isset($msg_data) && is_array($msg_data) && count($msg_data) > 0) {
                    $email_subject = $msg_data['email_subject'];
                    $data['msg_body'] = $msg_data['email_body'];
                    $html_body = $this->load->view('email_body/general_email', $data, TRUE);

                    $receiver_email = $setting['sectorwise_report_email'];

                    $this->load->module('report/sector_report');

                    $_GET['send_email'] = 1;
                    $data = $this->sector_report->show_list();

                    $filename2 = create_year_dir('sectorwise_profitability_email');

                    $filename = 'sectorwise-profitability-report-' . date('d-M-Y') . '.csv';
                    $file_save_path =  $filename2 . '/' . $filename;
                    $handle = fopen($file_save_path, 'w');

                    $line = array('CUSTOMER CODE', 'TOTAL OUTSTANDING', 'ORIGIN HUB');
                    $all_sector =  isset($data['all_sector']) ? $data['all_sector'] :  array();
                    $all_hub =  isset($data['all_hub']) ? $data['all_hub'] :  array();
                    if (isset($all_sector) && is_array($all_sector) && count($all_sector) > 0) {
                        foreach ($all_sector as $skey => $svalue) {
                            $line[] = strtoupper($svalue['code']) . ' BILLED';
                            $line[] = strtoupper($svalue['code']) . ' UN-BILLED';
                            $line[] = strtoupper($svalue['code']) . ' PURCHASE';
                            $line[] = strtoupper($svalue['code']) . ' YESTERDAY BOOKING';
                            $line[] = strtoupper($svalue['code']) . ' DEBIT';
                            $line[] = strtoupper($svalue['code']) . ' CREDIT';
                            $line[] = strtoupper($svalue['code']) . ' OUTSTANDING';
                        }
                    }
                    fputcsv($handle, $line);
                    unset($line);

                    $customer_data = isset($data['customer_data']) ? $data['customer_data'] :  array();
                    $unbilled_amt_data = isset($data['unbilled_amt_data']) ? $data['unbilled_amt_data'] :  array();
                    $amount_data = isset($data['amount_data']) ? $data['amount_data'] :  array();
                    $sector_billed = isset($data['sector_billed']) ? $data['sector_billed'] :  array();
                    $sector_unbilled = isset($data['sector_unbilled']) ? $data['sector_unbilled'] :  array();
                    $sector_purchase = isset($data['sector_purchase']) ? $data['sector_purchase'] :  array();
                    $yesterday_booking = isset($data['yesterday_booking']) ? $data['yesterday_booking'] :  array();
                    $sector_data = isset($data['sector_data']) ? $data['sector_data'] :  array();
                    if (isset($customer_data) && is_array($customer_data) && count($customer_data) > 0) {
                        foreach ($customer_data as $key => $value) {
                            $unbilled_amt = isset($unbilled_amt_data[$value['id']]) && $unbilled_amt_data[$value['id']] > 0 ? $unbilled_amt_data[$value['id']] : 0;
                            $credit_amt = isset($amount_data[1][$value['id']]) && $amount_data[1][$value['id']] > 0 ? $amount_data[1][$value['id']] : 0;
                            $debit_amt = isset($amount_data[2][$value['id']]) && $amount_data[2][$value['id']] > 0 ? $amount_data[2][$value['id']] : 0;
                            $debit_amt = $debit_amt + $unbilled_amt;
                            $row_data = array(
                                'customer_code' => $value['code'],
                                'total_outstanding' => round($debit_amt - $credit_amt),
                                'origin_hub' =>  isset($all_hub[$value['origin_hub_id']]) ? ucwords($all_hub[$value['origin_hub_id']]['name']) : ''
                            );
                            if (isset($all_sector) && is_array($all_sector) && count($all_sector) > 0) {
                                foreach ($all_sector as $skey => $svalue) {
                                    $row_data[] = isset($sector_billed[$svalue['id']][$value['id']]) ? $sector_billed[$svalue['id']][$value['id']] : '';
                                    $row_data[] = isset($sector_unbilled[$svalue['id']][$value['id']]) ? $sector_unbilled[$svalue['id']][$value['id']] : '';;
                                    $row_data[] = isset($sector_purchase[$svalue['id']][$value['id']]) ? $sector_purchase[$svalue['id']][$value['id']] : '';
                                    $row_data[] = isset($yesterday_booking[$svalue['id']][$value['id']]) ? $yesterday_booking[$svalue['id']][$value['id']] : '';
                                    $row_data[] = isset($sector_data[$svalue['id']][$value['id']][2]) ? $sector_data[$svalue['id']][$value['id']][2] : '';
                                    $row_data[] = isset($sector_data[$svalue['id']][$value['id']][1]) ? $sector_data[$svalue['id']][$value['id']][1] : '';
                                    $sec_debit_amt = isset($sector_data[$svalue['id']][$value['id']][2]) ? $sector_data[$svalue['id']][$value['id']][2] : 0;
                                    $sec_credit_amt = isset($sector_data[$svalue['id']][$value['id']][1]) ? $sector_data[$svalue['id']][$value['id']][1] : 0;

                                    $row_data[] = $sec_credit_amt - $sec_debit_amt;
                                }
                            }
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
                $message = "SECTORWISE PROFITABILITY EMAILS NOT PRESENT";
            }
        } else {
            $message = "SEND SECTORWISE PROFITABILITY REPORT - SETTING IS DISABLE";
        }
    }
}
