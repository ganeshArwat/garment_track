<?php
class Charge_migrate_api extends MX_Controller
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
            $api_url = $base_url . "api/v5/charge_masters";
            $json_data = @file_get_contents($api_url);
            $json_data =  str_replace(array("'"), "", $json_data);
            $result = json_decode($json_data, TRUE);
        } else {
            echo "SET OLD SOFTWARE DOMAIN IN COMPANY";
            exit;
        }

        if (isset($result) && is_array($result) && count($result) > 0) {

            $this->load->module('charge_masters');
            $all_user = get_all_user(" AND company_id='" . $company_id . "'", "", "migration_id");

            if (isset($result['data']) && is_array($result['data']) && count($result['data']) > 0) {
                foreach ($result['data'] as $key => $value) {
                    $customer_insert = array();
                    $is_default = 2;
                    if ($value['is_manual'] != 1 && $value['is_optional'] != 1) {
                        $is_default = 1;
                    }
                    $customer_insert['charge'] = array(
                        'id' => $value['id'],
                        'migration_id' => $value['id'],
                        'name' =>  $value['name'],
                        'is_gst_apply' =>  $value['is_gst_applicable'] == 1 ? 1 : 2,
                        'is_fsc_apply' =>  $value['is_fsc_applicable'] == 1 ? 1 : 2,
                        'is_manual' =>  $value['is_manual'] == 1 ? 1 : 2,
                        'is_cust_optional' =>  $value['is_optional'] == 1 ? 1 : 2,
                        //'is_default' =>  $value['is_system_default'] == 1 ? 1 : 2,
                        'is_default' => $is_default,
                        'is_vendor_optional' =>  $value['is_vendor_optional'] == 1 ? 1 : 2,
                        'created_date' =>  $value['created_at'],
                        'modified_date' =>  $value['updated_at'],
                        'status' =>  $value['is_active'] == 1 ? 1 : 2,
                        'created_by' => isset($all_user[$value['created_by_id']]) ? $all_user[$value['created_by_id']]['id'] : 0,
                        'modified_by' => isset($all_user[$value['updated_by_id']]) ? $all_user[$value['updated_by_id']]['id'] : 0,
                    );

                    $qry = "SELECT id FROM charge_master WHERE status IN(1,2) AND migration_id='" . $value['id'] . "'";
                    $qry_exe = $this->db->query($qry);
                    $existData = $qry_exe->row_array();

                    if (isset($existData) && is_array($existData) && count($existData) > 0) {
                        $customer_insert['charge_id'] = $existData['id'];
                        $this->charge_masters->update($customer_insert);
                    } else {
                        $this->charge_masters->insert($customer_insert);
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
