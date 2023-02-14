<?php
class Cft_contract_api extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_api_data($company_name = '')
    {
        $time_start = microtime(true);
        $this->load->helper('url');
        $this->load->model('Global_model', 'gm');
        $this->load->helper('frontend_common');

        $sessiondata = $this->session->userdata('admin_user');
        $company_id = isset($sessiondata['com_id']) ? $sessiondata['com_id'] : '';
        $main_db = $this->load->database('main_db', true);
        $qry = "SELECT  id,old_domain FROM company WHERE status IN(1,2) AND id='" . $company_id . "'";
        $qry_exe = $main_db->query($qry);
        $com_res = $qry_exe->row_array();
        $base_url = isset($com_res['old_domain']) ? $com_res['old_domain'] : '';

        // $company_data = $this->config->item('company_base_url');
        // $base_url = isset($company_data[$company_name]) ? $company_data[$company_name] : '';
        if ($base_url != '') {
            $api_url = $base_url . "api/v5/cft_contracts";
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
            $all_product = get_all_product(" AND status IN(1,2) ", "code");
            $all_user = get_all_user(" AND company_id='" . $company_id . "'", "", "migration_id");

            $all_user = get_all_user(" AND company_id='" . $company_id . "'", "", "migration_id");

            if (isset($result['data']) && is_array($result['data']) && count($result['data']) > 0) {
                foreach ($result['data'] as $key => $value) {

                    $customer_id =  isset($value['customer_code']) && $value['customer_code'] != '' && isset($all_customer[strtolower(trim($value['customer_code']))]) ? $all_customer[strtolower(trim($value['customer_code']))]['id'] : 0;
                    $product_id =  isset($value['product_code']) && $value['product_code'] != '' && isset($all_product[strtolower(trim($value['product_code']))]) ? $all_product[strtolower(trim($value['product_code']))]['id'] : 0;
                    $vendor_id =  isset($value['vendor_code']) && $value['vendor_code'] != '' && isset($all_vendor[strtolower(trim($value['vendor_code']))]) ? $all_vendor[strtolower(trim($value['vendor_code']))]['id'] : 0;
                    $co_vendor_id =  isset($value['co_vendor_code']) && $value['co_vendor_code'] != '' && isset($all_co_vendor[strtolower(trim($value['co_vendor_code']))]) ? $all_co_vendor[strtolower(trim($value['co_vendor_code']))]['id'] : 0;

                    if (strtolower($value['billing_type']) == 'sale') {
                        $billing_type = 3;
                    } else if (strtolower($value['billing_type']) == 'sale') {
                        $billing_type = 2;
                    } else {
                        $billing_type = 1;
                    }
                    $customer_insert = array(
                        'id' => $value['id'],
                        'migration_id' => $value['id'],
                        'billing_type' => $billing_type,
                        'customer_id' =>  $customer_id,
                        'vendor_id' =>  $vendor_id,
                        'co_vendor_id' =>  $co_vendor_id,
                        'product_id' =>  $product_id,
                        'effective_min' =>  $value['effective_from'],
                        'effective_max' =>  $value['effective_till'],
                        'cft_value' =>  $value['cft_value'],
                        'cft_multiplier' =>  $value['cft_multiplier'],
                        'created_date' =>  $value['created_at'],
                        'modified_date' =>  $value['updated_at'],
                        'created_by' => isset($all_user[$value['created_by_id']]) ? $all_user[$value['created_by_id']]['id'] : 0,
                        'modified_by' => isset($all_user[$value['updated_by_id']]) ? $all_user[$value['updated_by_id']]['id'] : 0,
                        'status' => 1,
                    );

                    $qry = "SELECT id FROM cft_contracts WHERE status IN(1,2) AND migration_id='" . $value['id'] . "'";
                    $qry_exe = $this->db->query($qry);
                    $existData = $qry_exe->row_array();

                    if (isset($existData) && is_array($existData) && count($existData) > 0) {
                        $this->gm->update('cft_contracts', $customer_insert, '', array('migration_id' => $value['id']));
                    } else {
                        $this->gm->insert('cft_contracts', $customer_insert);
                    }

                    echo "<br>CONTRACT ID=" . $value['id'] . " ADDED";
                }
            }
        }
        $time_end = microtime(true);
        $execution_time = ($time_end - $time_start) / 60;

        //execution time of the script
        echo '<b>Total Execution Time:</b> ' . $execution_time . ' Mins';
    }
}
