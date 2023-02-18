<?php
if (!function_exists('create_company_database')) {
    function create_company_database($company_id = '')
    {
        $ci = &get_instance();
        $response = false;
        if ($company_id > 0) {
            $ci->load->database();
            $ci->load->dbutil();
            $company_db_name = "garment_track_company_" . $company_id;
            if (!$ci->dbutil->database_exists($company_db_name)) {
                $ci->load->dbforge();
                $ci->dbforge->create_database($company_db_name);
            }
            $response = true;
        }
        return $response;
    }
}

if (!function_exists('create_all_table')) {
    function create_all_table($return_type = '')
    {
        $CI = &get_instance();

        $session_data = isset($_SESSION['admin_user']) ? $_SESSION['admin_user'] : array();

        if (isset($_GET['cron_company']) && $_GET['cron_company'] > 0) {
            $database_name = "garment_track_company_" . $_GET['cron_company'];
        } else  if (isset($session_data['is_restrict']) && $session_data['is_restrict'] == 2) {
            $database_name = "garment_track_company_" . $session_data['com_id'];
        } else {
            $database_name = "garment_track_theme";
        }

        //CHECK FUNCTION EXIST
        $qry = "show function status where name='REGEXP_REPLACE' AND db = '" . $database_name . "'";
        $qry_exe = $CI->db->query($qry);
        $functionExist = $qry_exe->row_array();

        if (isset($functionExist) && is_array($functionExist) && count($functionExist) > 0) {
        } else {
            $regex_function = "CREATE FUNCTION `REGEXP_REPLACE`(original VARCHAR(1000),pattern VARCHAR(1000),replacement VARCHAR(1000))
        RETURNS VARCHAR(1000)
        DETERMINISTIC
        BEGIN 
         DECLARE temp VARCHAR(1000); 
         DECLARE ch VARCHAR(1); 
         DECLARE i INT;
         SET i = 1;
         SET temp = '';
         IF original REGEXP pattern THEN 
          loop_label: LOOP 
           IF i>CHAR_LENGTH(original) THEN
            LEAVE loop_label;  
           END IF;
           SET ch = SUBSTRING(original,i,1);
           IF NOT ch REGEXP pattern THEN
            SET temp = CONCAT(temp,ch);
           ELSE
            SET temp = CONCAT(temp,replacement);
           END IF;
           SET i=i+1;
          END LOOP;
         ELSE
          SET temp = original;
         END IF;
         RETURN temp;
        END";
            $CI->db->query($regex_function);
        }


        $table_list = array(
            'app_settings', 'company_bank', 'company_master', 'custom_validation_field', 'module_setting', 'setting_data'
        );

        if ($return_type == 'get_table') {
            return $table_list;
        }


        foreach ($table_list as $key => $value) {
            $table_name = $value . "_table_qry";
            // check table creation function exist or not
            if (function_exists($table_name)) {
                $qry =  $table_name();

                $CI->db->query($qry);

            }
        }
    }
}
