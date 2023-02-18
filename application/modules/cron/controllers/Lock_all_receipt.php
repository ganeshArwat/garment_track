<?php
class Lock_all_receipt extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    function lock_all_receipt()
    {
        file_put_contents(FCPATH . 'log1/Lock_all_invoice.txt', date('Y-m-d-H-i-s-a') . "\n", FILE_APPEND);
        //GET BANK 
        $this->load->helper('url');
        $this->load->model('Global_model', 'gm');
        $this->load->helper('frontend_common');

        $setting = get_all_app_setting(" AND module_name IN('account')");

        if (isset($setting['lock_all_receipt']) && $setting['lock_all_receipt'] == 1) {
            if (isset($setting['no_of_days_for_receipt_lock']) && $setting['no_of_days_for_receipt_lock'] == 0) {
                $query = "UPDATE payment_receipt SET receipt_status = '1'";
                $this->db->query($query);
            } else if ($setting['no_of_days_for_receipt_lock'] && $setting['no_of_days_for_receipt_lock'] != "" && $setting['no_of_days_for_receipt_lock'] != 0) {
                $today = date('Y-m-d');
                $min_date = date('Y-m-d', strtotime('-' . $setting["no_of_days_for_receipt_lock"] . ' day', strtotime($today)));
                $query = "UPDATE payment_receipt SET receipt_status = '1'  WHERE receipt_date>='" . $min_date . "'";
                $this->db->query($query);
            }
        }
    }
}
