<?php
class Booking_report extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function send_booking_email()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->helper('email');
        $this->load->model('Global_model', 'gm');

        //CHECK SETTING ENABLE OR NOT
        $setting = get_all_app_setting(" AND module_name IN('general')");
        file_put_contents(FCPATH . 'log1/send_booking_email.txt', date('Y-m-d-H-i-s-a') . "\n", FILE_APPEND);

        if (isset($setting['send_total_booking_report']) && $setting['send_total_booking_report'] == 1) {

            if (isset($setting['total_booking_report_email']) && $setting['total_booking_report_email'] != '') {
                //CHECK EMAIL SETUP
                $qry = "SELECT te.* FROM trigger_email_setting te 
                JOIN email_configuration co ON(co.id=te.email_configuration_id)
                WHERE te.status IN(1,2) AND co.status IN(1,2) AND te.email_trigger_key='booking_report_daily'";
                $qry_exe = $this->db->query($qry);
                $msg_data = $qry_exe->row_array();


                if (isset($msg_data) && is_array($msg_data) && count($msg_data) > 0) {
                    $email_subject = $msg_data['email_subject'];
                    $data['msg_body'] = $msg_data['email_body'];
                    $html_body = $this->load->view('email_body/general_email', $data, TRUE);

                    $receiver_email = $setting['total_booking_report_email'];

                    $today = date('Y-m-d');
                    $booking_data = date("Y-m-d", strtotime("-1 days", strtotime($today)));
                    $qry = "SELECT d.id,d.booking_date,d.customer_id,d.awb_no,d.vendor_id,d.destination_id,d.total_pcs,d.actual_wt,
                    d.chargeable_wt,bill.grand_total,sh.name as shi_name,co.name as con_name,d.ori_hub_id FROM docket d 
                    LEFT OUTER JOIN docket_shipper sh ON(d.id=sh.docket_id AND sh.status=1)
                    LEFT OUTER JOIN docket_consignee co ON(d.id=co.docket_id AND co.status=1)
                    LEFT OUTER JOIN docket_sales_billing bill ON(d.id=bill.docket_id AND bill.status=1)
                    WHERE d.status=1 AND DATE_FORMAT(d.created_date,'%Y-%m-%d')='" . $booking_data . "' ORDER BY d.destination_id";
                    $qry_exe = $this->db->query($qry);
                    $docket_data = $qry_exe->result_array();

                    if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
                        foreach ($docket_data as $dkey => $dvalue) {
                            $hub_docket[$dvalue['ori_hub_id']][$dvalue['id']] = $dvalue;
                            if (isset($hub_data[$dvalue['ori_hub_id']]['grand_total'])) {
                                $hub_data[$dvalue['ori_hub_id']]['grand_total'] += $dvalue['grand_total'];
                            } else {
                                $hub_data[$dvalue['ori_hub_id']]['grand_total'] = $dvalue['grand_total'];
                            }

                            if (isset($hub_data[$dvalue['ori_hub_id']]['chargeable_wt'])) {
                                $hub_data[$dvalue['ori_hub_id']]['chargeable_wt'] += $dvalue['chargeable_wt'];
                            } else {
                                $hub_data[$dvalue['ori_hub_id']]['chargeable_wt'] = $dvalue['chargeable_wt'];
                            }


                            if (isset($hub_data[$dvalue['ori_hub_id']][$dvalue['destination_id']]['chargeable_wt'])) {
                                $hub_data[$dvalue['ori_hub_id']][$dvalue['destination_id']]['chargeable_wt'] += $dvalue['chargeable_wt'];
                            } else {
                                $hub_data[$dvalue['ori_hub_id']][$dvalue['destination_id']]['chargeable_wt'] = $dvalue['chargeable_wt'];
                            }
                            if (isset($hub_data[$dvalue['ori_hub_id']][$dvalue['destination_id']]['grand_total'])) {
                                $hub_data[$dvalue['ori_hub_id']][$dvalue['destination_id']]['grand_total'] += $dvalue['grand_total'];
                            } else {
                                $hub_data[$dvalue['ori_hub_id']][$dvalue['destination_id']]['grand_total'] = $dvalue['grand_total'];
                            }
                        }
                    }

                    if (isset($_GET['cron_company'])) {
                        $filename2 = create_year_dir('temp', $_GET['cron_company']);
                    } else {
                        $filename2 = create_year_dir('temp');
                    }


                    $filename = 'booking-report-' . date('d-M-Y') . '-' . rand(000000, 999999) . '.csv';
                    $file_save_path =  $filename2 . '/' . $filename;
                    $handle = fopen($file_save_path, 'w');

                    $line = array(
                        "Hub", "Customer Name","Customer Code", "Awb Number", "Booking Data", "Shipper Name", "Consignee Name", "Service", "Destination", "Pcs",
                        "Actual Weight", "Chargeable Weight", "Docket Amount", "Hub Wise Total Amount", "Hub Wise Total Weight", "Total Amount", "Total Weight"
                    );
                    fputcsv($handle, $line);
                    unset($line);


                    $all_hub = get_all_hub();
                    $all_location = get_all_location();
                    $all_vendor = get_all_vendor();
                    $all_customer = get_all_customer();
                    if (isset($hub_docket) && is_array($hub_docket) && count($hub_docket) > 0) {
                        foreach ($hub_docket as $hkey => $hvalue) {
                            $row_data = array(
                                'ori_hub_id' => isset($all_hub[$hkey]) ? $all_hub[$hkey]['code'] : '',
                                'customer_id' => '',
                                'awb_no' => '',
                                'booking_date' => '',
                                'shi_name' => '',
                                'con_name' => '',
                                'vendor_id' => '',
                                'destination_id' => '',
                                'total_pcs' => '',
                                'actual_wt' => '',
                                'chargeable_wt' => '',
                                'grand_total' => '',
                                'hub_total_amount' => isset($hub_data[$hkey]) ? $hub_data[$hkey]['grand_total'] : '',
                                'hub_total_weight' => isset($hub_data[$hkey]) ? $hub_data[$hkey]['chargeable_wt'] : '',
                                'total_amount' => '',
                                'total_weight' => '',
                            );
                            fputcsv($handle, $row_data);
                            unset($row_data);


                            $old_destination = '';
                            if (isset($hvalue) && is_array($hvalue) && count($hvalue) > 0) {
                                foreach ($hvalue as $dkey => $dvalue) {
                                    $row_data = array(
                                        'ori_hub_id' => isset($all_hub[$dvalue['ori_hub_id']]) ? $all_hub[$dvalue['ori_hub_id']]['code'] : '',
                                        'customer_id' => isset($all_customer[$dvalue['customer_id']]) ? $all_customer[$dvalue['customer_id']]['name'] : '',
                                        'customer_code' => isset($all_customer[$dvalue['customer_id']]) ? $all_customer[$dvalue['customer_id']]['code'] : '',
                                        'awb_no' => $dvalue['awb_no'],
                                        'booking_date' => get_format_date('d/m/Y', ($dvalue['booking_date'])),
                                        'shi_name' => $dvalue['shi_name'],
                                        'con_name' => $dvalue['con_name'],
                                        'vendor_id' => isset($all_vendor[$dvalue['vendor_id']]) ? $all_vendor[$dvalue['vendor_id']]['code'] : '',
                                        'destination_id' =>  isset($all_location[$dvalue['destination_id']]) ? $all_location[$dvalue['destination_id']]['code'] : '',
                                        'total_pcs' =>  $dvalue['total_pcs'],
                                        'actual_wt' =>  $dvalue['actual_wt'],
                                        'chargeable_wt' =>  $dvalue['chargeable_wt'],
                                        'grand_total' =>  $dvalue['grand_total'],
                                        'hub_total_amount' => '',
                                        'hub_total_weight' => '',
                                        'total_amount' => '',
                                        'total_weight' => '',
                                    );

                                    if ($old_destination != $dvalue['destination_id']) {
                                        $row_data['total_amount'] = isset($hub_data[$hkey][$dvalue['destination_id']]) ? $hub_data[$hkey][$dvalue['destination_id']]['grand_total'] : '';
                                        $row_data['total_weight'] = isset($hub_data[$hkey][$dvalue['destination_id']]) ? $hub_data[$hkey][$dvalue['destination_id']]['chargeable_wt'] : '';

                                        $old_destination = $dvalue['destination_id'];
                                    }
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
                $message = "BOOLKING REPORT EMAILS NOT PRESENT";
            }
        } else {
            $message = "SEND TOTAL BOOKING REPORT - SETTING IS DISABLE";
        }

        echo $message;
    }
}
