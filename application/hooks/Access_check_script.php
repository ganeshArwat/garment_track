<?php
class Access_check_script
{
    public function check_permission_old()
    {
        $CI = &get_instance();
        $CI->load->helper('url');
        $current_url =  $CI->uri->uri_string();

        $segment1 = $this->uri->segment(1);
        $segment2 = $this->uri->segment(2);

        $url = $segment1 . "/" . $segment2;

        $sessiondata = $_SESSION['admin_user'];

        $current_url = site_url() . $current_url . '/';

        $skip_url_permission = array(
            "0" => 'garment_track_theme/adminx',
            "1" => 'garment_track_theme/sohem-testing'
        );

        if (!in_array($url, $skip_url_permission)) {

            if (isset($sessiondata['url_permission']) && is_array($sessiondata['url_permission']) && count($sessiondata['url_permission']) > 0) {
                $is_found = 0;
                foreach ($sessiondata['url_permission'] as $ukey => $uvalue) {
                    $db_url = site_url() . $uvalue;
                    if ($uvalue != '' && strpos($current_url, $db_url) !== false) {
                        $is_found = 1;
                        break;
                    }
                }
                if ($is_found != 1) {
                    http_response_code(403);
                    die('Forbidden');
                }
            } else {
                http_response_code(403);
                die('Forbidden');
            }
        }
    }




    public function check_permission()
    {

        if (session_id() == '') {
            session_start();
        }

        $CI = &get_instance();
        $CI->load->helper('url');
        $current_url =  $CI->uri->uri_string();
        $segment1 =  $CI->uri->segment(1);
        $segment2 =  $CI->uri->segment(2);

        $connected_db =  $CI->db->database;
        if ($connected_db != 'garment_track_theme') {
            $connected_db_arrr = explode("_", $connected_db);
            $company_id = isset($connected_db_arrr[2]) ? $connected_db_arrr[2] : 0;
            if ($company_id > 0) {
                $main_db = $CI->load->database('main_db', true);
                $qry = "SELECT id,status FROM company WHERE id='" . $company_id . "'";
                $qry_exe = $main_db->query($qry);
                $company_data = $qry_exe->row_array();

                if ($segment1 != 'company_dashboard') {
                    if (isset($company_data['status']) && $company_data['status'] != 1) {
                        if (isset($_SESSION['admin_user']['type']) && $_SESSION['admin_user']['type'] == 'software_user') {
                            echo "<h3 style='color: red;font-size: 27px;font-weight: 700;'>Hello Team,<br>
                    Your account has been suspended due to non payment of dues. Please clear the dues to resume the service. Thank you.</h3>";
                            exit;
                        } else {
                            echo "<h3 style='color: red;font-size: 27px;font-weight: 700;'>Hello Team,<br>
                        Site is Down for maintenance. Thank you.</h3>";
                            exit;
                        }
                    }
                }
            }
        }

        //get all permission
        $main_db = $CI->load->database('main_db', true);
        $qry = "SELECT id,config_key,url_key FROM permission WHERE status IN(1,2)";
        $qry_exe = $main_db->query($qry);
        $result = $qry_exe->result_array();

        $permission_key = array();
        $current_url = site_url() . $current_url . '/';

        if (isset($result) && is_array($result) && count($result) > 0) {
            foreach ($result as $key => $value) {
                $url_arr = explode(",", $value['url_key']);
                if (isset($url_arr) && is_array($url_arr) && count($url_arr) > 0) {
                    foreach ($url_arr as $ukey => $uvalue) {
                        $db_url = site_url() . $uvalue;
                        if ($uvalue != '' && strpos($current_url, $db_url) !== false) {
                            $permission_key[] = $value['config_key'];
                        }
                    }
                }
            }
        }

        $super_admin_role = $CI->config->item('super_admin');

        $user_role = isset($_SESSION['admin_user']['role']) ? $_SESSION['admin_user']['role'] : 0;
        $is_itd_login = isset($_SESSION['admin_user']['is_restrict']) ? $_SESSION['admin_user']['is_restrict'] : 2;




        if (($segment1 != 'adminx' && $segment1 != 'customer_masters' && $segment1 != 'login' && $segment2 != 'show_kyc') && isset($_SESSION['admin_user']['customer_status']) && $_SESSION['admin_user']['customer_status'] != 1) {
            echo "<h3>YOUR ACCOUNT IS DISABLED!</h3>";
            exit;
        } else if ($is_itd_login != 1 && count($permission_key) > 0  && $user_role != $super_admin_role && isset($_SESSION['admin_user']['user_permission']) && empty(array_intersect($permission_key, $_SESSION['admin_user']['user_permission']))) {
            redirect(site_url());
        }
    }
}
