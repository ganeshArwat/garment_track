<?php
class Auto_invoice extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function create_invoice()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');

        $current_time = date('H:00:00');

        //CHECK AUTO INVOICE SETTING ON 
        $qry = "SELECT c.id,m.config_key,m.config_value,c.billing_cycle,c.billing_time,c.billing_day,
         c.company_id FROM customer c 
                JOIN module_setting m ON(c.id=m.module_id AND m.module_type=1) 
                WHERE c.status IN(1,2) AND m.status IN(1,2) AND m.config_key='create_auto_invoice' 
                AND m.config_value=1 AND c.billing_cycle!=1 AND c.billing_time='" . $current_time . "'
                AND c.company_id>0";
        $qry_exe = $this->db->query($qry);
        $customer_data = $qry_exe->result_array();

        $all_company = get_all_billing_company();

        if (isset($customer_data) && is_array($customer_data) && count($customer_data) > 0) {
            $this->load->module('invoice');
            $all_week_days = all_week_days();
            foreach ($customer_data as $key => $value) {

                //GET CUSTOMER BILLING SETTING

                $filter_data = array();
                if (isset($all_company) && is_array($all_company) && count($all_company) > 0) {

                    if ($value['billing_cycle'] == 2) {
                        //DAILY
                        $booking_date =  date("Y-m-d", strtotime("yesterday"));
                        $filter_data['booking_min'] = $booking_date;
                        $filter_data['booking_max'] = $booking_date;
                    } else if ($value['billing_cycle'] == 3) {
                        //WEEKLY
                        $day_no = date('N', strtotime(date("Y-m-d")));
                        if ($day_no == $value['billing_day']) {
                            $end_week = $day_no - 1;
                            if ($end_week == 0) {
                                $end_week = 7;
                            }
                            $filter_data['booking_min'] = date("Y-m-d", strtotime("previous " . $all_week_days[$day_no]['name'], strtotime(date("Y-m-d"))));
                            $filter_data['booking_max'] = date("Y-m-d", strtotime("previous " . $all_week_days[$end_week]['name'], strtotime(date("Y-m-d"))));
                        }
                    } else if ($value['billing_cycle'] == 4) {
                        //MONTHLY
                        $today_date =  date("d");
                        if ($today_date == $value['billing_day']) {
                            $filter_data['booking_min'] = date('Y-m-d', strtotime('first day of last month'));
                            $filter_data['booking_max'] = date('Y-m-d', strtotime('last day of last month'));
                        }
                    }


                    if (isset($filter_data) && is_array($filter_data) && count($filter_data) > 0) {
                        foreach ($all_company as $ckey => $cvalue) {
                            $all_invoice_range = array();
                            $id_arr = $this->get_company_invoice_range($cvalue['id']);

                            if (isset($id_arr) && is_array($id_arr) && count($id_arr) > 0) {
                                $qry = "SELECT id,name,code FROM invoice_range WHERE status IN(1,2) 
                    AND id IN(" . implode(",", $id_arr) . ")  ORDER BY name";

                                $qry_exe = $this->db->query($qry);
                                $all_invoice_range = $qry_exe->result_array();
                            }


                            if (isset($all_invoice_range) && is_array($all_invoice_range) && count($all_invoice_range) > 0) {
                                foreach ($all_invoice_range as $rkey => $rvalue) {
                                    $filter_data['invoice_range_id'] = $rvalue['id'];
                                    $filter_data['customer_id'] = $value['id'];
                                    $filter_data['auto_invoice'] = 1;
                                    $filter_data['company_master_id'] = $cvalue['id'];


                                    $docket_data = $this->invoice->customer_single($filter_data);

                                    //CREATE INVOICE
                                    if (isset($docket_data['list']) && is_array($docket_data['list']) && count($docket_data['list']) > 0) {
                                        $save_data = array();
                                        $save_data['invoice'] = array(
                                            'customer_id' => $value['id'],
                                            'invoice_range_id' =>  $rvalue['id'],
                                            'company_master_id' => $cvalue['id'],
                                            'from_date' => $filter_data['booking_min'],
                                            'to_date' => $filter_data['booking_max'],
                                            'invoice_date' => date('Y-m-d'),
                                            'due_date' => date('Y-m-d'),
                                        );

                                        foreach ($docket_data['list'] as $dkey => $dvalue) {
                                            $save_data['docket_id'][] = $dvalue['id'];
                                            $save_data['grand_total'][] = $dvalue['grand_total'];
                                        }

                                        $save_response = $this->invoice->customer_single_invoice_save($save_data);

                                        echo "<br>CUSTOMER_ID=" . $value['id'] . "====INVOICE RESPONSE " . $save_response['message'];
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function get_company_invoice_range($company_id = 0)
    {
        $this->load->model('Global_model', 'gm');
        $post = $this->input->post();
        $result = array();
        $id_arr = array();
        if (isset($post['company_id']) && $post['company_id'] > 0) {
            $company_id = $post['company_id'];
        }
        if ($company_id > 0) {

            //get company rangeid 
            $range_id_res = $this->gm->get_data_list('company_invoice_range', array('company_master_id' => $company_id, 'status' => 1), array(), array(), 'id,invoice_range_id');
            if (isset($range_id_res) && is_array($range_id_res) && count($range_id_res) > 0) {
                foreach ($range_id_res as $rkey => $rvalue) {
                    $id_arr[$rvalue['invoice_range_id']] = $rvalue['invoice_range_id'];
                }
            }

            //GET NON_GST INVOICE RANGE
            $non_gst_range = $this->gm->get_data_list('invoice_range', array('company_master_id' => $company_id, 'status' => 1, 'is_non_gst' => 1), array(), array(), 'id');
            if (isset($non_gst_range) && is_array($non_gst_range) && count($non_gst_range) > 0) {
                foreach ($non_gst_range as $rkey => $rvalue) {
                    $id_arr[$rvalue['id']] = $rvalue['id'];
                }
            }

            if (isset($id_arr) && is_array($id_arr) && count($id_arr) > 0) {
                $qry = "SELECT id,name,code FROM invoice_range WHERE status IN(1,2) 
                AND id IN(" . implode(",", $id_arr) . ") ORDER BY name";

                $qry_exe = $this->db->query($qry);
                $result = $qry_exe->result_array();
            }
        }
        if (isset($post['company_id']) && $post['company_id'] > 0) {
            echo json_encode($result);
        } else {
            return $id_arr;
        }
    }
}
