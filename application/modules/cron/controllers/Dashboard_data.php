<?php
class Dashboard_data extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function save_data()
    {
        $tracking_event_id = $this->config->item('tracking_event_id');
        //GET YESTERDAT DOCKET 
        $yesterday_date =  date('Y-m-d', strtotime("-1 days"));
        $yesterday_date = '2022-01-27';
        $query = "SELECT d.id,d.booking_date,d.state_id,d.customer_id,ds.grand_total,d.vendor_id,d.chargeable_wt FROM docket d 
        LEFT OUTER JOIN docket_sales_billing ds ON(d.id=ds.docket_id AND ds.status IN(1,2))
         WHERE d.status IN(1,2) AND d.booking_date ='" . $yesterday_date . "'";

        $query_exe = $this->db->query($query);
        $result = $query_exe->result_array();

        $delivered_id = $tracking_event_id['delivered'];
        $in_transit_id = $tracking_event_id['in_transit'];
        if (isset($result) && is_array($result) && count($result) > 0) {
            foreach ($result as $key => $value) {
                if ($value['customer_id'] > 0) {
                    if ($value['state_id'] == $delivered_id) {
                        if (isset($docket_data[$value['booking_date']][$value['customer_id']])) {
                            $docket_data[$value['booking_date']][$value['customer_id']]['total_delivered'] += 1;
                        } else {
                            $docket_data[$value['booking_date']][$value['customer_id']]['total_delivered'] = 1;
                        }
                    }

                    if ($value['state_id'] == $in_transit_id) {
                        if (isset($docket_data[$value['booking_date']][$value['customer_id']])) {
                            $docket_data[$value['booking_date']][$value['customer_id']]['total_intransit'] += 1;
                        } else {
                            $docket_data[$value['booking_date']][$value['customer_id']]['total_intransit'] = 1;
                        }
                    }

                    if (isset($docket_data[$value['booking_date']][$value['customer_id']])) {
                        $docket_data[$value['booking_date']][$value['customer_id']]['total_docket'] += 1;
                        $docket_data[$value['booking_date']][$value['customer_id']]['total_sale'] += $value['grand_total'];
                    } else {
                        $docket_data[$value['booking_date']][$value['customer_id']]['total_docket'] = 1;
                        $docket_data[$value['booking_date']][$value['customer_id']]['total_sale'] += $value['grand_total'];
                    }

                    //COURIER WISE LOAD (IN KG)
                    if ($value['vendor_id'] > 0) {
                        if (isset($docket_data[$value['booking_date']][$value['customer_id']]['service_load'][$value['vendor_id']])) {
                            $docket_data[$value['booking_date']][$value['customer_id']]['service_load'][$value['vendor_id']] += $value['chargeable_wt'];
                        } else {
                            $docket_data[$value['booking_date']][$value['customer_id']]['service_load'][$value['vendor_id']] = $value['chargeable_wt'];
                        }
                    }

                    //COURIER WISE STATUS
                    if ($value['vendor_id'] > 0) {
                        if (isset($docket_data[$value['booking_date']][$value['customer_id']]['service_total'][$value['vendor_id']])) {
                            $docket_data[$value['booking_date']][$value['customer_id']]['service_total'][$value['vendor_id']] += 1;
                            if ($value['state_id'] == $delivered_id) {
                                $docket_data[$value['booking_date']][$value['customer_id']]['service_delivered'][$value['vendor_id']] += 1;
                            } else {
                                $docket_data[$value['booking_date']][$value['customer_id']]['service_delivered'][$value['vendor_id']] += 0;
                            }

                            if ($value['state_id'] == $in_transit_id) {
                                $docket_data[$value['booking_date']][$value['customer_id']]['service_intransit'][$value['vendor_id']] += 1;
                            } else {
                                $docket_data[$value['booking_date']][$value['customer_id']]['service_intransit'][$value['vendor_id']] += 0;
                            }
                        } else {
                            $docket_data[$value['booking_date']][$value['customer_id']]['service_total'][$value['vendor_id']] = 1;

                            if ($value['state_id'] == $delivered_id) {
                                $docket_data[$value['booking_date']][$value['customer_id']]['service_delivered'][$value['vendor_id']] = 1;
                            } else {
                                $docket_data[$value['booking_date']][$value['customer_id']]['service_delivered'][$value['vendor_id']] = 0;
                            }

                            if ($value['state_id'] == $in_transit_id) {
                                $docket_data[$value['booking_date']][$value['customer_id']]['service_intransit'][$value['vendor_id']] = 1;
                            } else {
                                $docket_data[$value['booking_date']][$value['customer_id']]['service_intransit'][$value['vendor_id']] = 0;
                            }
                        }
                    }
                }
            }
        }

        $report_key = array('total_docket', 'total_sale', 'total_delivered', 'total_intransit');
        $service_report_key = array('service_load', 'service_total', 'service_delivered', 'service_intransit');
        if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
            foreach ($docket_data as $book_key => $book_value) {
                foreach ($book_value as $cust_key => $cust_value) {

                    foreach ($report_key as $rkey => $rvalue) {
                        if (isset($cust_value[$rvalue])) {
                            $insert_data[] = array(
                                'report_date' => $book_key,
                                'customer_id' => $cust_key,
                                'vendor_id' => 0,
                                'report_key' => $rvalue,
                                'report_value' => $cust_value[$rvalue],
                                'created_date' => date('Y-m-d H:i:s')
                            );
                        }
                    }

                    foreach ($service_report_key as $skey => $svalue) {
                        if (isset($cust_value[$svalue]) && is_array($cust_value[$svalue]) && count($cust_value[$svalue]) > 0) {
                            foreach ($cust_value[$svalue] as $ser_key => $ser_value) {
                                $insert_data[] = array(
                                    'report_date' => $book_key,
                                    'customer_id' => $cust_key,
                                    'vendor_id' => $ser_key,
                                    'report_key' => $svalue,
                                    'report_value' => $ser_value,
                                    'created_date' => date('Y-m-d H:i:s')
                                );
                            }
                        }
                    }
                }
            }
        }

        if (isset($insert_data) && is_array($insert_data) && count($insert_data) > 0) {
            $this->db->insert_batch('dashboard_data', $insert_data);
        }
    }
}
