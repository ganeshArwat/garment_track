<?php
class Bank_Balance extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    function update_closing_balance()
    {
        file_put_contents(FCPATH . 'log1/bank_closing_cron.txt', date('Y-m-d-H-i-s-a') . "\n", FILE_APPEND);
        //GET BANK 
        $qry = "SELECT c.id,cb.id,cb.account_name,cb.account_no,cb.available_balance,c.country,cb.closing_balance 
                FROM company_master c
JOIN company_bank cb ON(c.id=cb.company_master_id)
WHERE c.status IN(1,2) AND cb.status IN(1,2)";
        $qry_exe = $this->db->query($qry);
        $bank_data = $qry_exe->result_array();

        $this->load->module('account/bank_ledger');
        if (isset($bank_data) && is_array($bank_data) && count($bank_data) > 0) {
            foreach ($bank_data as $bkey => $bvalue) {
                $filter_data = array(
                    'account_no_id' => $bvalue['id'],
                    'closing_date' => date('Y-m-d', (strtotime('-1 day', strtotime(date('Y-m-d'))))),
                    'mode' => 'script',
                );

                $closing_balance =  $this->bank_ledger->export_data($filter_data);

                $updateq = "UPDATE company_bank SET closing_balance='" . $closing_balance . "' WHERE id='" . $bvalue['id'] . "'";

                //   echo $updateq;
                $this->db->query($updateq);
            }
        }
    }
}
