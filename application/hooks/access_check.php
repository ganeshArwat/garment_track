<?php
class Access_check
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
            "0" => 'garment_track_/adminx',
            "1" => 'garment_track_/pannest-logistics'
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

        // Prevent DOS Attack.only 10 request can be made in one second by single user
        //if user does not change IP, then ban the IP when more than 10 requests per second are detected in 1 second
        $limitps = 5;

        if (!isset($_SESSION['first_request'])) {
            $_SESSION['requests'] = 0;
            $_SESSION['first_request'] = $_SERVER['REQUEST_TIME'];
        }


        $_SESSION['requests']++;
        if ($_SESSION['requests'] >= $limitps && ($_SERVER['REQUEST_TIME']) - ($_SESSION['first_request']) <= 1) {
            //write the IP to a banned_ips.log file and configure your server to retrieve the banned ips from there - now you will be handling this IP outside of PHP
            $_SESSION['banip'] = 1;
            file_put_contents('ban_ips.txt', $_SERVER['REMOTE_ADDR'], FILE_APPEND);
        } elseif (($_SERVER['REQUEST_TIME']) - ($_SESSION['first_request']) > 2) {
            $_SESSION['requests'] = 0;
            $_SESSION['first_request'] = $_SERVER['REQUEST_TIME'];
            $_SESSION['banip'] = '';
            // $log_data = array(
            //      'current' => $_SERVER['REQUEST_TIME'],
            //      'first' => $_SESSION['first_request'],
            //      'request_cnt' => $_SESSION['requests']
            //  );
            // file_put_contents('test_'.date('Y-m-d-H-i-s'), serialize($log_data), FILE_APPEND);
        }
        file_put_contents(FCPATH . '/log1/' . date('Y-m-d-H-i-s'), serialize($_SESSION));

        if (isset($_SESSION['banip']) && $_SESSION['banip'] == 1) {
            header('HTTP/1.1 429 Too many request');
            header('Retry-After: 60');
            die;
        }


        $CI = &get_instance();
        $CI->load->helper('url');
        $current_url =  $CI->uri->uri_string();
        $segment1 =  $CI->uri->segment(1);
        $segment2 =  $CI->uri->segment(2);

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
                            $permission_key = $value['config_key'];
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
        } else if ($is_itd_login != 1 && count($permission_key) > 0  && $user_role != $super_admin_role && isset($_SESSION['admin_user']['user_permission']) && !in_array($permission_key, $_SESSION['admin_user']['user_permission'])) {
            redirect(site_url());
        }
    }
}
