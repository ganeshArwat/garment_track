<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if (!function_exists('active')) {
    function active($uri, $level = 1, $class_suffix = 'active')
    {
        $ci = &get_instance();

        if ($ci->uri->segment($level) == $uri) {
            return $class_suffix;
        } else {
            return '';
        }
    }
}


if (!function_exists('get_company_user_count')) {
    function get_company_user_count()
    {
        $ci = &get_instance();
        $sessiondata = $ci->session->userdata('admin_user');
        if ($sessiondata['is_restrict'] == 2) {
            $ci->main_db = $ci->load->database('main_db', true);

            $qry = "SELECT * FROM company WHERE status IN(1,2) AND id = " . $sessiondata['com_id'];
            $qry_exe = $ci->main_db->query($qry);
            $company_data = $qry_exe->row_array();

            if (isset($company_data) && is_array($company_data) && count($company_data) > 0) {
                $cquery = "SELECT id FROM `admin_user` WHERE status='1' AND role!=8 AND company_id=" . $sessiondata['com_id'];
                $cquery_exec = $ci->main_db->query($cquery);
                $company_users = $cquery_exec->result_array();
                $login_count = $company_data['login_count'];
                $active_user = count($company_users);
            }
        }

        $return_data = array(
            'login_count' => $login_count,
            'active_user' => $active_user,
        );
        return json_encode($return_data);
    }
}
