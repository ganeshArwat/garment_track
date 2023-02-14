<?php
class Column_script extends MX_Controller
{
    public function check_table_structure()
    {
        $this->load->helper('database_manage');
        $table_list = create_all_table('get_table');

        //GET COMPANY TABLE STRUCTURE
        foreach ($table_list as $key => $table_name) {
            $company_schema = array();
            $actual_schema = array();

            $qry = "SHOW COLUMNS FROM `" . $table_name . "`";

            $qry_exe = $this->db->query($qry);
            $company_db_table = $qry_exe->result_array();
            if (isset($company_db_table) && is_array($company_db_table) && count($company_db_table) > 0) {
                foreach ($company_db_table as $ct_key => $ct_value) {
                    $company_schema[$ct_value['Field']] = $ct_value;
                }
            }

            //CHECK TABLE COLUMN QUERY
            $table_controller = $table_name . "_table";
            $this->load->module('database_migration/' . $table_controller);
            $migration_table = $this->$table_controller->column_list();
            // file_put_contents('log1/table.txt', $table_controller . "\n", FILE_APPEND);


            if (isset($migration_table) && is_array($migration_table) && count($migration_table) > 0) {
                foreach ($migration_table as $mkey => $mvalue) {
                    $migration_array = explode(", ", $mvalue);
                    if (isset($migration_array) && is_array($migration_array) && count($migration_array) > 0) {
                        foreach ($migration_array as $mi_key => $mi_value) {
                            $mi_value_array = explode(":", $mi_value);
                            $actual_table_structure[$mi_value_array[0]] = $mi_value_array[1];
                        }

                        $actual_schema[$actual_table_structure['Field']] = $actual_table_structure;
                    }
                }


                if (isset($actual_schema) && is_array($actual_schema) && count($actual_schema) > 0) {
                    foreach ($actual_schema as $tkey => $tvalue) {

                        if (!isset($company_schema[$tkey])) {
                            $alter_query = "ALTER TABLE `" . $table_name . "` ADD `" . $tkey . "` " . $tvalue['Type'];
                            if ($tvalue['Null'] == 'NO') {
                                $alter_query .= " NOT NULL";
                            } else {
                                $alter_query .= " NULL";
                            }

                            if ($tvalue['Default'] != '') {
                                $alter_query .= " DEFAULT '" . $tvalue['Default'] . "'";
                            }

                            $this->db->query($alter_query);
                        }
                    }
                }
            } else {
                // echo "<br>COM=".$table_name;
                // echo "<br>CONTROLLER=".$table_controller;
                // echo '<pre>';print_r( $migration_table);
            }
        }
    }
}
