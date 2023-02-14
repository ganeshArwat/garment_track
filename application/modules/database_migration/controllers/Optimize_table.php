<?php
class Optimize_table extends MX_Controller
{
    public function run_qry()
    {
        $this->load->helper('database_manage');
        $table_list = create_all_table('get_table');

        foreach ($table_list as $key => $table_name) {

            $qry = "SHOW COLUMNS FROM `" . $table_name . "`";
            $qry_exe = $this->db->query($qry);
            $company_db_table = $qry_exe->result_array();

            $session_data = $this->session->userdata('admin_user');
            $company_id = isset($session_data['com_id']) ? $session_data['com_id'] : '';
            $db_name = "garment_track_theme_company_" . $company_id;
            $index_qry = "SELECT DISTINCT TABLE_NAME, INDEX_NAME FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_SCHEMA = '" . $db_name . "'";
            $index_qry_exe = $this->db->query($index_qry);
            $index_data = $index_qry_exe->result_array();

            $indexExist = array();
            if (isset($index_data) && is_array($index_data) && count($index_data) > 0) {
                foreach ($index_data as $ekey => $evalue) {
                    $indexExist[$evalue['TABLE_NAME']][] = $evalue['INDEX_NAME'];
                }
            }

            if (isset($company_db_table) && is_array($company_db_table) && count($company_db_table) > 0) {
                foreach ($company_db_table as $ct_key => $ct_value) {

                    $column_name_split_arr = explode("_", $ct_value['Field']);

                    if (isset($column_name_split_arr) && is_array($column_name_split_arr) && count($column_name_split_arr) > 0) {
                        if (in_array('id', $column_name_split_arr)) {

                            //CHECK INDEX ALREADY EXIST
                            if (isset($indexExist[$table_name]) && in_array($ct_value['Field'], $indexExist[$table_name])) {
                                //DONT ADD INDEX
                            } else {
                                $indexq = "ALTER TABLE `" . $table_name . "` ADD INDEX(`" . $ct_value['Field'] . "`);";

                                $this->db->query($indexq);
                            }
                        }
                    }
                }
            }
        }
    }


    public function get_tinyint_col()
    {
        $this->load->helper('database_manage');
        $table_list = create_all_table('get_table');

        foreach ($table_list as $key => $table_name) {

            $qry = "SHOW COLUMNS FROM `" . $table_name . "`";
            $qry_exe = $this->db->query($qry);
            $company_db_table = $qry_exe->result_array();


            if (isset($company_db_table) && is_array($company_db_table) && count($company_db_table) > 0) {
                foreach ($company_db_table as $ct_key => $ct_value) {

                    if ($ct_value['Type'] == 'tinyint(1)' && $ct_value['Field'] != 'status') {
                        $int_table[$table_name][] = $ct_value['Field'];
                    }
                }
            }
        }


        echo '<pre>';
        print_r($int_table);
        exit;
    }
}
