<?php
class Fsc_migrate_api extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_api_data($company_name = '')
    {
        $time_start = microtime(true);
        $this->load->helper('url');
        $this->load->helper('frontend_common');

        // $company_data = $this->config->item('company_base_url');
        // $base_url = isset($company_data[$company_name]) ? $company_data[$company_name] : '';

        $sessiondata = $this->session->userdata('admin_user');
        $company_id = isset($sessiondata['com_id']) ? $sessiondata['com_id'] : '';
        $main_db = $this->load->database('main_db', true);
        $qry = "SELECT  id,old_domain FROM company WHERE status IN(1,2) AND id='" . $company_id . "'";
        $qry_exe = $main_db->query($qry);
        $com_res = $qry_exe->row_array();
        $base_url = isset($com_res['old_domain']) ? $com_res['old_domain'] : '';

        if ($base_url != '') {
            $api_url = $base_url . "api/v5/fsc_masters";
            $json_data = @file_get_contents($api_url);

            $json_data =  str_replace(array("'"), "", $json_data);
            $result = json_decode($json_data, TRUE);
        } else {
            echo "SET OLD SOFTWARE DOMAIN IN COMPANY";
            exit;
        }


        if (isset($result) && is_array($result) && count($result) > 0) {
            $all_customer = get_all_customer(" AND status IN(1,2) ", "code");
            $all_vendor = get_all_vendor(" AND status IN(1,2) ", "code");
            $all_co_vendor = get_all_co_vendor(" AND status IN(1,2) ", "code");
            $all_user = get_all_user(" AND company_id='" . $company_id . "'", "", "migration_id");

            $this->load->module('fsc_masters');

            if (isset($result['data']) && is_array($result['data']) && count($result['data']) > 0) {
                foreach ($result['data'] as $key => $value) {

                    $customer_insert = array();
                    $customer_id = isset($value['customer_code']) && isset($all_customer[strtolower(trim($value['customer_code']))]) ? $all_customer[strtolower(trim($value['customer_code']))]['id'] : 0;
                    $vendor_id = isset($value['vendor_code']) && isset($all_vendor[strtolower(trim($value['vendor_code']))]) ? $all_vendor[strtolower(trim($value['vendor_code']))]['id'] : 0;
                    $co_vendor_id = isset($value['co_vendor_code']) && isset($all_co_vendor[strtolower(trim($value['co_vendor_code']))]) ? $all_co_vendor[strtolower(trim($value['co_vendor_code']))]['id'] : 0;

                    $billing_type = 0;
                    if ($value['billing_type'] == 'sale') {
                        $billing_type = 3;
                    } else if ($value['billing_type'] == 'purchase') {
                        $billing_type = 2;
                    }
                    $customer_insert['fsc'] = array(
                        'id' => $value['id'],
                        'migration_id' => $value['id'],
                        'billing_type' => $billing_type,
                        'customer_id' => $customer_id,
                        'vendor_id' => $vendor_id,
                        'co_vendor_id' => $co_vendor_id,
                        'effective_min' =>  $value['effective_from'],
                        'effective_max' =>  $value['effective_till'],
                        'fsc_percentage' =>  $value['percentage'],
                        'created_date' =>  $value['created_at'],
                        'modified_date' =>  $value['updated_at'],
                        'status' =>  1,
                        'created_by' => isset($all_user[$value['created_by_id']]) ? $all_user[$value['created_by_id']]['id'] : 0,
                        'modified_by' => isset($all_user[$value['updated_by_id']]) ? $all_user[$value['updated_by_id']]['id'] : 0,
                    );


                    $qry = "SELECT id FROM fsc_masters WHERE status IN(1,2) AND migration_id='" . $value['id'] . "'";
                    $qry_exe = $this->db->query($qry);
                    $existData = $qry_exe->row_array();

                    if (isset($existData) && is_array($existData) && count($existData) > 0) {
                        $customer_insert['fsc_id'] = $existData['id'];
                        $this->fsc_masters->update($customer_insert);
                    } else {
                        $this->fsc_masters->insert($customer_insert);
                    }
                }
            }
        }
        $time_end = microtime(true);
        $execution_time = ($time_end - $time_start) / 60;

        //execution time of the script
        echo '<b>Total Execution Time:</b> ' . $execution_time . ' Mins';
    }
}
