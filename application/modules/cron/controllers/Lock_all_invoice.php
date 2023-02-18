<?php
class Lock_all_invoice extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    function lock_all_invoice()
    {
        file_put_contents(FCPATH . 'log1/Lock_all_invoice.txt', date('Y-m-d-H-i-s-a') . "\n", FILE_APPEND);
        //GET BANK 
        $this->load->helper('url');
        $this->load->model('Global_model', 'gm');
        $query = "update docket_invoice SET invoice_status = '1',modified_date='" . date('Y-m-d H:i:s') . "'";
        $this->db->query($query);
    }
}
