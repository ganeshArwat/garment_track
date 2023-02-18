<?php
class Weight_change_email extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function send_weight_data()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->helper('email');
        $this->load->model('Global_model', 'gm');

        //get all docket of yesterday
        $today = date('Y-m-d');
        $booking_data = date("Y-m-d", strtotime("-1 days", strtotime($today)));

        $qry = "SELECT d.id,d.wt_change_datetime,d.old_chargeable_wt,d.booking_date,cust.operation_email_id,
        d.awb_no,d.forwarding_no,d.destination_id,d.total_pcs,d.actual_wt,d.chargeable_wt,
        d.vendor_id,d.customer_id,d.product_id,dcon.name as con_name,dshi.name as shi_name FROM docket d
        JOIN customer cust ON(cust.id=d.customer_id)
        LEFT OUTER JOIN docket_consignee dcon ON(d.id=dcon.docket_id AND dcon.status IN(1,2))
        LEFT OUTER JOIN docket_shipper dshi ON(d.id=dshi.docket_id AND dshi.status IN(1,2)) 
        WHERE d.status IN(1,2) AND cust.send_wt_change_daily=1 AND cust.operation_email_id!='' 
         AND DATE_FORMAT(d.wt_change_datetime, '%Y-%m-%d')='" . $booking_data . "'";
        $qry_exe = $this->db->query($qry);
        $docket_data = $qry_exe->result_array();

        $module_setting = $this->gm->get_data_list('module_setting', array('config_key' => "weight_change_selection_daily", 'module_type' => 1, 'status' => 1), array(), array(), 'id,module_id,config_key,config_value');
        if (isset($module_setting) && is_array($module_setting) && count($module_setting) > 0) {
            foreach ($module_setting as $mkey => $mvalue) {
                $customer_setting[$mvalue['module_id']][$mvalue['config_key']] = $mvalue['config_value'];
            }
        }

        if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
            foreach ($docket_data as $dkey => $dvalue) {
                //NEW WEIGHT HIGHER
                if (
                    isset($customer_setting[$dvalue['customer_id']]) && $customer_setting[$dvalue['customer_id']]['weight_change_selection_daily'] == 2
                    && ($dvalue['chargeable_wt'] > $dvalue['old_chargeable_wt'])
                ) {
                    $customer_docket[$dvalue['customer_id']][$dvalue['id']] = $dvalue;
                    $customer_email[$dvalue['customer_id']] = $dvalue['operation_email_id'];
                    $customer_id_arr[$dvalue['customer_id']] = $dvalue['customer_id'];
                    $location_id_arr[$dvalue['destination_id']] = $dvalue['destination_id'];
                } else if (
                    isset($customer_setting[$dvalue['customer_id']]) && $customer_setting[$dvalue['customer_id']]['weight_change_selection_daily'] == 3
                    && ($dvalue['chargeable_wt'] < $dvalue['old_chargeable_wt'])
                ) {
                    $customer_docket[$dvalue['customer_id']][$dvalue['id']] = $dvalue;
                    $customer_email[$dvalue['customer_id']] = $dvalue['operation_email_id'];
                    $customer_id_arr[$dvalue['customer_id']] = $dvalue['customer_id'];
                    $location_id_arr[$dvalue['destination_id']] = $dvalue['destination_id'];
                } else if (
                    isset($customer_setting[$dvalue['customer_id']]) && $customer_setting[$dvalue['customer_id']]['weight_change_selection_daily'] == 1
                ) {
                    $customer_docket[$dvalue['customer_id']][$dvalue['id']] = $dvalue;
                    $customer_email[$dvalue['customer_id']] = $dvalue['operation_email_id'];
                    $customer_id_arr[$dvalue['customer_id']] = $dvalue['customer_id'];
                    $location_id_arr[$dvalue['destination_id']] = $dvalue['destination_id'];
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
             WHERE te.status IN(1,2) AND co.status IN(1,2) AND te.email_trigger_key='weight_change_daily'
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
                    $html_body = $this->load->view('email_body/weight_change_daily', $data, TRUE);

                    //$customer_email_id = isset($all_customer[$ckey]) ? $all_customer[$ckey]['email_id'] : '';

                    if (isset($customer_email[$ckey]) && $customer_email[$ckey] != '') {
                        $receipient_email = $customer_email[$ckey];

                        send_email_msg($msg_data['email_configuration_id'], $email_subject, $html_body, $receipient_email, '', $msg_data['cc_email']);
                    }
                }
            }
        }
    }
}
