<?php
class Delivery_email extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function send_delivery_data()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->helper('email');
        $this->load->helper('message');
        $this->load->model('Global_model', 'gm');
        //get all docket delivered yesterday
        $today = date('Y-m-d');
        $booking_data = date("Y-m-d", strtotime("-1 days", strtotime($today)));


        $qry = "SELECT d.id,d.booking_date,d.awb_no,d.forwarding_no,d.destination_id,cust.operation_email_id,
        d.vendor_id,d.customer_id,dcon.name as con_name,del.delivery_date,del.delivery_time,del.receiver_name FROM docket d
        JOIN docket_delivery del ON(d.id=del.docket_id)
        JOIN customer cust ON(cust.id=d.customer_id)
        LEFT OUTER JOIN docket_consignee dcon ON(d.id=dcon.docket_id AND dcon.status IN(1,2))
        WHERE d.status IN(1,2) AND cust.status IN(1,2) AND cust.send_pod_email=1 AND cust.operation_email_id!='' 
        AND del.status IN(1,2) AND DATE_FORMAT(`delivered_mark_date`,'%Y-%m-%d')='" . $booking_data . "'
        AND del.delivery_date!='0000-00-00' AND del.delivery_date!='1970-01-01'";
        $qry_exe = $this->db->query($qry);
        $docket_data = $qry_exe->result_array();


        if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
            foreach ($docket_data as $dkey => $dvalue) {
                $customer_docket[$dvalue['customer_id']][$dvalue['id']] = $dvalue;
                $customer_email[$dvalue['customer_id']] = $dvalue['operation_email_id'];
                $customer_id_arr[$dvalue['customer_id']] = $dvalue['customer_id'];
                $location_id_arr[$dvalue['destination_id']] = $dvalue['destination_id'];

                $docket_ids[$dvalue['id']] = $dvalue['id'];
            }
        }

        if (isset($docket_ids) && is_array($docket_ids) && count($docket_ids) > 0) {
            //GET POD FILE
            $chargeq = "SELECT id,module_id,module_type,media_key,media_path FROM media_attachment 
                WHERE status IN(1,2) AND module_id IN(" . implode(",", $docket_ids) . ") 
                AND module_type=1 AND media_key='pod_image'";

            $chargeq_exe = $this->db->query($chargeq);
            $pod_awb_result = $chargeq_exe->result_array();


            if (isset($pod_awb_result) && is_array($pod_awb_result) && count($pod_awb_result) > 0) {
                foreach ($pod_awb_result as $pkey => $pvalue) {
                    if (file_exists($pvalue['media_path'])) {
                        $pod_image_data[$pvalue['module_id']] = base_url($pvalue['media_path']);
                    }
                }
            }
        }

        if (isset($customer_id_arr) && is_array($customer_id_arr) && count($customer_id_arr) > 0) {
            $all_customer = get_all_customer(" AND id IN(" . implode(",", $customer_id_arr) . ")");
        }
        if (isset($location_id_arr) && is_array($location_id_arr) && count($location_id_arr) > 0) {
            $all_location = get_all_location(" AND id IN(" . implode(",", $location_id_arr) . ")");
        }

        $all_company = get_all_billing_company(" AND id=1");

        //SEND CUSTOMER WISE DOCKET EMAIL
        if (isset($customer_docket) && is_array($customer_docket) && count($customer_docket) > 0) {

            //get mail data
            $qry = "SELECT te.* FROM trigger_email_setting te 
             JOIN email_configuration co ON(co.id=te.email_configuration_id)
             WHERE te.status IN(1,2) AND co.status IN(1,2) AND te.email_trigger_key='pod_email'
             AND te.send_email=1";
            $qry_exe = $this->db->query($qry);
            $msg_data = $qry_exe->row_array();


            if (isset($msg_data) && is_array($msg_data) && count($msg_data) > 0) {
                foreach ($customer_docket as $ckey => $cvalue) {
                    $data = array();
                    $company_name = isset($all_company[1]) ? $all_company[1]['name'] : '';

                    $email_subject = $msg_data['email_subject'];
                    $email_subject = str_ireplace("[[company]]", $company_name, $email_subject);

                    $email_body = $msg_data['email_body'];
                    $email_body = str_ireplace("[[company]]", $company_name, $email_body);
                    $email_body = str_ireplace("[[content]]", '', $email_body);

                    $data['msg_body'] = $email_body;
                    $data['docket_data'] = $cvalue;
                    $data['all_location'] = $all_location;
                    $data['pod_image'] = isset($pod_image_data) ? $pod_image_data : array();
                    $html_body = $this->load->view('email_body/delivery_daily_docket', $data, TRUE);


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



    public function send_old_delivery_data()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->helper('email');
        $this->load->helper('message');
        $this->load->model('Global_model', 'gm');
        //get all docket delivered yesterday
        $today = date('Y-m-d');
        $start_date = $_GET['start_date'];
        $end_date = $_GET['end_date'];


        $qry = "SELECT d.id,d.booking_date,d.awb_no,d.forwarding_no,d.destination_id,cust.operation_email_id,
        d.vendor_id,d.customer_id,dcon.name as con_name,del.delivery_date,del.delivery_time,del.receiver_name FROM docket d
        JOIN docket_delivery del ON(d.id=del.docket_id)
        JOIN customer cust ON(cust.id=d.customer_id)
        LEFT OUTER JOIN docket_consignee dcon ON(d.id=dcon.docket_id AND dcon.status IN(1,2))
        WHERE d.status IN(1,2) AND cust.status IN(1,2) AND cust.send_pod_email=1 AND cust.operation_email_id!='' 
        AND del.status IN(1,2) AND del.delivery_date>='" . $start_date . "' 
        AND del.delivery_date<='" . $end_date . "'";

        $qry_exe = $this->db->query($qry);
        $docket_data = $qry_exe->result_array();

        if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
            foreach ($docket_data as $dkey => $dvalue) {
                $customer_docket[$dvalue['customer_id']][$dvalue['id']] = $dvalue;
                $customer_email[$dvalue['customer_id']] = $dvalue['operation_email_id'];
                $customer_id_arr[$dvalue['customer_id']] = $dvalue['customer_id'];
                $location_id_arr[$dvalue['destination_id']] = $dvalue['destination_id'];
            }
        }


        if (isset($customer_id_arr) && is_array($customer_id_arr) && count($customer_id_arr) > 0) {
            $all_customer = get_all_customer(" AND id IN(" . implode(",", $customer_id_arr) . ")");
        }
        if (isset($location_id_arr) && is_array($location_id_arr) && count($location_id_arr) > 0) {
            $all_location = get_all_location(" AND id IN(" . implode(",", $location_id_arr) . ")");
        }

        $all_company = get_all_billing_company(" AND id=1");

        //SEND CUSTOMER WISE DOCKET EMAIL
        if (isset($customer_docket) && is_array($customer_docket) && count($customer_docket) > 0) {

            //get mail data
            $qry = "SELECT te.* FROM trigger_email_setting te 
             JOIN email_configuration co ON(co.id=te.email_configuration_id)
             WHERE te.status IN(1,2) AND co.status IN(1,2) AND te.email_trigger_key='pod_email'
             AND te.send_email=1";
            $qry_exe = $this->db->query($qry);
            $msg_data = $qry_exe->row_array();


            if (isset($msg_data) && is_array($msg_data) && count($msg_data) > 0) {
                foreach ($customer_docket as $ckey => $cvalue) {
                    $data = array();
                    $company_name = isset($all_company[1]) ? $all_company[1]['name'] : '';

                    $email_subject = $msg_data['email_subject'];
                    $email_subject = str_ireplace("[[company]]", $company_name, $email_subject);

                    $email_body = $msg_data['email_body'];
                    $email_body = str_ireplace("[[company]]", $company_name, $email_body);
                    $email_body = str_ireplace("[[content]]", '', $email_body);

                    $data['msg_body'] = $email_body;
                    $data['docket_data'] = $cvalue;
                    $data['all_location'] = $all_location;
                    $html_body = $this->load->view('email_body/delivery_daily_docket', $data, TRUE);

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
}
