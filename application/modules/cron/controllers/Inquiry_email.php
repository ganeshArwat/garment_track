<?php
class inquiry_email extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function send_user_inquiry()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->helper('email');
        $this->load->model('Global_model', 'gm');

        $all_user = get_all_user();
        $setting = get_all_app_setting(" AND module_name IN('general')");
        $today = date('Y-m-d');
        //GET TODAY INQUIRY
        $qry = "SELECT i.*,inf.* FROM inquiries i JOIN inquiry_follow_up inf
        ON(i.id=inf.inquiry_id) WHERE i.status IN(1,2) AND inf.status IN(1,2) AND follow_up_date='" . $today . "'
         AND follow_up_status=2";
        $query_exec = $this->db->query($qry);
        $result = $query_exec->result_array();
        if (isset($result) && is_array($result) && count($result) > 0) {
            foreach ($result as $key => $value) {
                $user_inquiry[$value['created_by']][$value['id']] = $value;
                $cust_id[$value['customer_id']] = $value['customer_id'];
                $vendor_id[$value['vendor_id']] = $value['vendor_id'];
            }
        }

        if (isset($cust_id) && is_array($cust_id) && count($cust_id) > 0) {
            $all_cust = get_all_customer(" AND id IN(" . implode(",", $cust_id) . ")");
        }
        if (isset($vendor_id) && is_array($vendor_id) && count($vendor_id) > 0) {
            $all_vendor = get_all_vendor(" AND id IN(" . implode(",", $vendor_id) . ")");
        }

        if (isset($user_inquiry) && is_array($user_inquiry) && count($user_inquiry) > 0) {
            $qry = "SELECT te.* FROM trigger_email_setting te 
             JOIN email_configuration co ON(co.id=te.email_configuration_id)
             WHERE te.status IN(1,2) AND co.status IN(1,2) AND te.email_trigger_key='inquiry_user_email'
             AND te.send_email=1";
            $qry_exe = $this->db->query($qry);
            $msg_data = $qry_exe->row_array();

            if (isset($msg_data) && is_array($msg_data) && count($msg_data) > 0) {
                $filename2 = create_year_dir('inquiry_email');

                $all_type = gat_all_inquiry_type();
                $all_status = gat_all_inquiry_status();
                foreach ($user_inquiry as $ukey => $uvalue) {
                    if (isset($all_user[$ukey]) && $all_user[$ukey]['email'] != '') {
                        $filename = 'inquiry_' . $ukey . '_' . date('d-M-Y-h-i-A') . '.csv';
                        $file_save_path =  $filename2 . '/' . $filename;
                        $handle = fopen($file_save_path, 'w');
                        $line = array(
                            'USER', 'NAME', 'FOLLOW-UP DATE', 'FOLLOW-UP TIME',
                            'DESCRIPTION', 'PHONE NO.', 'EMAIL ID', 'CUSTOMER', 'SERVICE', 'MODE OF INQUIRY', 'STATUS OF THE ENQUIRY'
                        );
                        fputcsv($handle, $line);
                        unset($line);

                        if (isset($uvalue) && is_array($uvalue) && count($uvalue) > 0) {
                            foreach ($uvalue as $ikey => $ivalue) {
                                $row_data = array(
                                    'user' => isset($all_user[$ukey]) ? $all_user[$ukey]['name'] : '',
                                    'name' => $ivalue['name'],
                                    'follow_up_date' => date('d/m/Y', strtotime($ivalue['follow_up_date'])),
                                    'follow_up_time' => date('h:i A', strtotime($ivalue['follow_up_time'])),
                                    'follow_up_desc' => $ivalue['follow_up_desc'],
                                    'contact_no' => $ivalue['contact_no'],
                                    'email_id' => $ivalue['email_id'],
                                    'customer_id' => isset($all_cust[$ivalue['customer_id']]) ? $all_cust[$ivalue['customer_id']]['name'] : '',
                                    'vendor_id' => isset($all_vendor[$ivalue['vendor_id']]) ? $all_vendor[$ivalue['vendor_id']]['name'] : '',
                                    'type' => isset($all_type[$ivalue['type']]) ? $all_type[$ivalue['type']]['name'] : '',
                                    'status_id' => isset($all_status[$ivalue['status_id']]) ? $all_status[$ivalue['status_id']]['name'] : '',
                                );
                                fputcsv($handle, $row_data);
                                unset($row_data);
                            }


                            $attachment = array($file_save_path);
                            $receipient_email = $all_user[$ukey]['email'];
                            $email_subject = $msg_data['email_subject'];
                            $html_body = $msg_data['email_body'];
                            $cc_email = isset($setting['inquiry_cc_email']) ? $setting['inquiry_cc_email'] : '';
                            send_email_msg($msg_data['email_configuration_id'], $email_subject, $html_body, $receipient_email, '', $cc_email, $attachment);
                        }
                    }
                }
            }
        }
    }


    public function send_postdate_inquiry()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->helper('email');
        $this->load->model('Global_model', 'gm');

        $all_user = get_all_user();
        $setting = get_all_app_setting(" AND module_name IN('general')");
        $today = date('Y-m-d');
        //GET TODAY INQUIRY
        $qry = "SELECT i.*,inf.* FROM inquiries i JOIN inquiry_follow_up inf
        ON(i.id=inf.inquiry_id) WHERE i.status IN(1,2) AND inf.status IN(1,2) AND follow_up_date < '" . $today . "'
         AND follow_up_status!=1";
        $query_exec = $this->db->query($qry);
        $result = $query_exec->result_array();

        if (isset($result) && is_array($result) && count($result) > 0) {
            foreach ($result as $key => $value) {
                $cust_id[$value['customer_id']] = $value['customer_id'];
                $vendor_id[$value['vendor_id']] = $value['vendor_id'];
            }
        }

        if (isset($cust_id) && is_array($cust_id) && count($cust_id) > 0) {
            $all_cust = get_all_customer(" AND id IN(" . implode(",", $cust_id) . ")");
        }
        if (isset($vendor_id) && is_array($vendor_id) && count($vendor_id) > 0) {
            $all_vendor = get_all_vendor(" AND id IN(" . implode(",", $vendor_id) . ")");
        }

        if (isset($result) && is_array($result) && count($result) > 0) {
            $qry = "SELECT te.* FROM trigger_email_setting te 
             JOIN email_configuration co ON(co.id=te.email_configuration_id)
             WHERE te.status IN(1,2) AND co.status IN(1,2) AND te.email_trigger_key='inquiry_postdate_email'
             AND te.send_email=1";
            $qry_exe = $this->db->query($qry);
            $msg_data = $qry_exe->row_array();

            if (isset($msg_data) && is_array($msg_data) && count($msg_data) > 0) {

                $filename2 = create_year_dir('inquiry_postdate_email');
                $all_type = gat_all_inquiry_type();
                $all_status = gat_all_inquiry_status();
                $cc_email = isset($setting['inquiry_cc_email']) ? $setting['inquiry_cc_email'] : '';
                if ($cc_email != '') {
                    $filename = 'postdate_inquiry_' . date('d-M-Y-h-i-A') . '.csv';
                    $file_save_path =  $filename2 . '/' . $filename;
                    $handle = fopen($file_save_path, 'w');
                    $line = array(
                        'USER', 'NAME', 'FOLLOW-UP DATE', 'FOLLOW-UP TIME', 'FOLLOW-UP STATUS', 'FOLLOW-UP RESPONSE',
                        'DESCRIPTION', 'PHONE NO.', 'EMAIL ID', 'CUSTOMER', 'SERVICE', 'MODE OF INQUIRY', 'STATUS OF THE ENQUIRY'
                    );
                    fputcsv($handle, $line);
                    unset($line);

                    foreach ($result as $ikey => $ivalue) {
                        $row_data = array(
                            'user' => isset($all_user[$ivalue['created_by']]) ? $ivalue['created_by'] : '',
                            'name' => $ivalue['name'],
                            'follow_up_date' => date('d/m/Y', strtotime($ivalue['follow_up_date'])),
                            'follow_up_time' => date('h:i A', strtotime($ivalue['follow_up_time'])),
                            'follow_up_status' => 'NOT DONE',
                            'follow_up_response' => $ivalue['follow_up_response'],
                            'follow_up_desc' => $ivalue['follow_up_desc'],
                            'contact_no' => $ivalue['contact_no'],
                            'email_id' => $ivalue['email_id'],
                            'customer_id' => isset($all_cust[$ivalue['customer_id']]) ? $all_cust[$ivalue['customer_id']]['name'] : '',
                            'vendor_id' => isset($all_vendor[$ivalue['vendor_id']]) ? $all_vendor[$ivalue['vendor_id']]['name'] : '',
                            'type' => isset($all_type[$ivalue['type']]) ? $all_type[$ivalue['type']]['name'] : '',
                            'status_id' => isset($all_status[$ivalue['status_id']]) ? $all_status[$ivalue['status_id']]['name'] : '',
                        );
                        fputcsv($handle, $row_data);
                        unset($row_data);
                    }
                    $receipient_email = $all_user[$ivalue]['email'];
                    $attachment = array($file_save_path);
                    $cc_email = isset($setting['inquiry_cc_email']) ? $setting['inquiry_cc_email'] : '';
                    $email_subject = $msg_data['email_subject'];
                    $html_body = $msg_data['email_body'];
                    send_email_msg($msg_data['email_configuration_id'], $email_subject, $html_body, $receipient_email, '', $cc_email, $attachment);
                }
            }
        }
    }
}
