<?php

class Admin_login extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $response['status'] = 'logout';
            echo json_encode($response);
        } else {
            $this->show_form();
        }
    }

    public function _display($view, $data)
    {
        $this->load->view('backend_header_login');
        $this->load->view($view, $data);
        $this->load->view('backend_footer_login');
    }

    public function show_form()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $user = $this->session->userdata('admin_user');

        if (isset($user['id']) && $user['id'] > 0) {
            redirect('adminx');
        } else {
            if ($this->session->userdata('RememberURL')) {
                redirect($this->session->userdata('RememberURL'));
            } else {
         
                $main_db = $this->load->database('main_db', true);
                $company_sef = $this->uri->segment(1);
                $company_sef2 = $this->uri->segment(2);
                
                if ($company_sef == 'login' && $company_sef2 == 'admin_login') {
                    $qry = "SELECT id,company_name,sef_url,logo FROM company WHERE status IN(1,2) AND id='1'";
                    $qry_exe = $main_db->query($qry);
                    $row = $qry_exe->row_array();
                } else {
                    $company_sef = strtolower($company_sef);
                    $localhost_ip = array(
                        '127.0.0.1',
                        '::1'
                    );
                    if (in_array($_SERVER['REMOTE_ADDR'], $localhost_ip)) {
                        // CHECKING COMAPNY SAFE URL
                        if ($company_sef != '') {
                            $qry = "SELECT id,company_name,sef_url,logo,status FROM company WHERE status IN(1,2) AND sef_url='" . $company_sef . "'";
                            $qry_exe = $main_db->query($qry);
                            $row = $qry_exe->row_array();
                        }
                    } else {

                        $host_name = $_SERVER['HTTP_HOST'];
                        // LOGIN USING PORTAL IP ADDRESS
                        $qry = "SELECT id,company_name,sef_url,logo,status FROM company WHERE status IN(1,2) AND portal_domain='" . $host_name . "'";
                        $qry_exe = $main_db->query($qry);
                        $row = $qry_exe->row_array();

                        if (isset($row) && is_array($row) && count($row) > 0) {
                            unset($row);
                            $portal_domain_login = 1;
                            $this->show_user_login();
                        }else {
                            //LOGIN USING COMPANY IP ADDRESS
                            $qry = "SELECT id,company_name,sef_url,logo,status FROM company WHERE status IN(1,2) AND company_domain='" . $host_name . "'";
                            $qry_exe = $main_db->query($qry);
                            $row = $qry_exe->row_array();

                            $all_company = get_all_company();
                            if (isset($all_company) && is_array($all_company) && count($all_company) > 0) {
                                foreach ($all_company as $ckey => $cvalue) {
                                    $all_company_sef[$cvalue['sef_url']] = $cvalue['id'];
                                }
                            }


                            //LOGIN USING COMPANY SEF
                            if ($company_sef != 'login' && $company_sef != '') {
                                $company_id = isset($all_company_sef[$company_sef]) ? $all_company_sef[$company_sef] : 0;

                                if (isset($row['id']) && $row['id'] != $company_id) {
                                    unset($row);
                                    $this->_display('show_404_page', array());
                                } else if ($company_id > 0) {
                                    $qry = "SELECT id,company_name,sef_url,logo,status FROM company WHERE status IN(1,2) AND id='" . $company_id . "'";
                                    $qry_exe = $main_db->query($qry);
                                    $row = $qry_exe->row_array();
                                }
                            }
                        }
                    }
                }

                if (isset($row['status']) && $row['status'] != 1) {
                    // IF COMPANY IS INACTIVE THEN SHOW MAINTENANCE MESSAGE
                    echo "<h3 style='color: red;font-size: 27px;font-weight: 700;'>Hello Team,<br>
                    Sorry, we're down for scheduled maintenance. </h3>";
                    
                    exit;
                }

                if (!isset($portal_domain_login)) {
                    //SHOW ADMIN LOGIN PAGE
                    if (isset($row) && is_array($row) && count($row) > 0) {
                        $data['company_name'] = isset($row['company_name']) ? $row['company_name'] : 'ITD Admin';

                        $data['sef_url'] = isset($row['sef_url']) ? $row['sef_url'] : 'admin_login';

                        $company_id = $row['id'];
                        if ($company_id > 1) {
                            $conn = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, 'garment_track_company_' . $company_id);
                            if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                            }
                            $sql =  "SELECT id,config_key,config_value FROM app_settings WHERE status IN(1,2) AND config_key='display_logo_of_which_company'";
                            $logo_com_result = $conn->query($sql);
                            if ($logo_com_result->num_rows > 0) {
                                $logo_row = $logo_com_result->fetch_assoc();
                                if (isset($logo_row['config_value']) && $logo_row['config_value'] > 0) {
                                    $sql =  "SELECT id,logo_file FROM company_master WHERE status IN(1,2) AND id='" . $logo_row['config_value'] . "'";
                                    $logo_path_result = $conn->query($sql);
                                    if ($logo_path_result->num_rows > 0) {
                                        $logo_path = $logo_path_result->fetch_assoc();
                                    }
                                }
                            }
                            $conn->close();
                        }
                        $data['company_logo'] = isset($logo_path['logo_file']) ? $logo_path['logo_file'] : $row['logo'];

                        $this->_display('admin_login_form', $data);
                    } else {
                        $this->_display('admin_login_form', array());
                    }
                }
            }
        }
    }

    public function show_user_login()
    {
        $host_name = $_SERVER['HTTP_HOST'];
        $main_db = $this->load->database('main_db', true);
        $qry = "SELECT id,company_name,sef_url,logo FROM company WHERE status IN(1,2) AND portal_domain='" . $host_name . "'";
        $qry_exe = $main_db->query($qry);
        $row = $qry_exe->row_array();
        if (isset($row) && is_array($row) && count($row) > 0) {
            $data['company_name'] = isset($row['company_name']) ? $row['company_name'] : 'ITD Admin';
            $data['company_id'] = isset($row['id']) ? $row['id'] : '';
            $data['company_sef'] = isset($row['sef_url']) ? $row['sef_url'] : '';
            $company_id =  $data['company_id'];
            $conn = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, 'garment_track_company_' . $company_id);
            if ($conn->connect_error) {
                die("CAN NOT LOGIN");
            } else {
                $sql =  "SELECT id,config_key,config_value FROM app_settings WHERE status IN(1,2) AND config_key='display_logo_of_which_company'";
                $logo_com_result = $conn->query($sql);
                if ($logo_com_result->num_rows > 0) {
                    $logo_row = $logo_com_result->fetch_assoc();
                    if (isset($logo_row['config_value']) && $logo_row['config_value'] > 0) {
                        $sql =  "SELECT id,logo_file FROM company_master WHERE status IN(1,2) AND id='" . $logo_row['config_value'] . "'";
                        $logo_path_result = $conn->query($sql);
                        if ($logo_path_result->num_rows > 0) {
                            $logo_path = $logo_path_result->fetch_assoc();
                        }
                    }
                }
                $conn->close();
                $data['company_logo'] = isset($logo_path['logo_file']) ? $logo_path['logo_file'] : $row['logo'];
            }

            $_SESSION['admin_user'] =  array(
                'type' => 'customer',
                'com_id' => $data['company_id'],
                'logo' => $data['company_logo'],
                'is_restrict' => 2,
                'logged_in' => true,
                'user_permission' => array()
            );

            $this->_display('user_login_form', $data);
        } else {
            $this->_display('show_404_page', array());
        }
    }

    public function _is_logged_in()
    {
        $user = $this->session->userdata('admin_user');
        $headers = get_header_data();
        $this->load->helper('frontend_common');
        $this->load->helper('get_authtoken');

        if (isset($_GET['auth'])) {
            return true;
        } else if (isset($headers["authorization"])) {
            $user_id = $this->input->post("user_id");
            $customer_id = $this->input->post("customer_id");
            $company_id = $this->input->post("company_id");
            $auth_token = str_replace("Bearer ", "", $headers["authorization"]);
            $is_customer_portal = $this->input->post("is_customer_portal");
            if (isset($company_id) && isset($customer_id) && isset($is_customer_portal) && $is_customer_portal == 1) {
                if (!get_authtoken_customer_user($auth_token, $user_id, $customer_id, $company_id)) {
                    $responce_array = array(
                        "success" => false,
                        "error" => array("Token is updated.Please logout and login.")
                    );
                    http_response_code(401);
                    echo json_encode($responce_array, JSON_UNESCAPED_SLASHES);
                    exit;
                } else {
                    return true;
                }
            } else if (!get_authtoken($auth_token, $user_id)) {
                $responce_array = array(
                    "success" => false,
                    "error" => array("Error !!")
                );
                http_response_code(401);
                echo json_encode($responce_array, JSON_UNESCAPED_SLASHES);
                exit;
            } else {
                return true;
            }
        } else if (isset($_GET['cron_company']) && $_GET['cron_company'] > 0) {
            return true;
        } else {

            if (isset($user['id']) && $user['id'] > 0) {
                $qry = "set global sql_mode='ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';";
                $this->db->query($qry);
                return true;
            } else {
                $last_url = $this->session->userdata('LastUrl');
                $this->session->set_userdata('RememberURL', $last_url);

                $this->load->helper('cookie');
                $this->load->helper('url');

                $sef =  get_cookie('cookie_name');
                $type =  get_cookie('cookie_type');

                $this->session->unset_userdata('admin_user');
                if ($sef != '') {
                    if ($type == 'customer') {
                        redirect(site_url($sef . '/user_login'));
                    } else {
                        redirect(site_url($sef));
                    }
                } else {
                    redirect(site_url());
                }

                //  return false;
            }
        }
    }

    public function uvf_login()
    {
        $this->load->model('Global_model', 'gm');
        $this->load->helper('url');
        $multiple_login_id = $this->config->item('multiple_login_id');

        $is_mobile_app = $this->input->post('is_mobile_app');
        if ($is_mobile_app == 1) {
            $email = $this->input->post('email');
        } else {
            $email = $this->input->post('username');
        }
        $password = $this->input->post('password');
        $company_code = $this->input->post('company_code');


        if (isset($email) && !empty($email) && isset($password) && !empty($password)) {
            if ($this->_check_login($email, $password, $company_code, $is_mobile_app)) {
                $current_session = session_id();
                $session_data = $this->session->userdata('admin_user');

                //logout on other system for same account
                $main_db = $this->load->database('main_db', true);
                if ($is_mobile_app != 1) {
                    $removetoken_query = "update admin_user set auth_token='' where id=" . $session_data['id'];
                    $main_db->query($removetoken_query);
                } else {
                }

                if (in_array($email, $multiple_login_id)) {
                } else {
                    $deleteq = "DELETE FROM ci_sessions WHERE type=1 AND user_id=" . $session_data['id'] . " AND id!='" . $current_session . "'";
                    $main_db->query($deleteq);
                }

                if ($is_mobile_app == 1) {
                    return True;
                } else {
                    http_response_code(200);
                }
            } else {
                if ($is_mobile_app == 1) {
                    return False;
                } else {
                    http_response_code(401);
                }
            }
        } else {
            if ($is_mobile_app == 1) {
                return false;
            } else {
                redirect(site_url('adminx'));
            }
        }
    }

    function check_validity()
    {
        $session_data = $this->session->userdata('admin_user');
        if ($session_data['is_restrict'] == 1) {
            http_response_code(200);
        } else {
            $user_id = $session_data['id'];
            if ($user_id > 0) {
                $main_db = $this->load->database('main_db', true);

                $qry = "SELECT a.id FROM admin_user a WHERE a.status IN(1,2) AND a.id=" . $user_id . " AND a.valid_till >='" . date('Y-m-d') . "'";
                $qry_exe = $main_db->query($qry);
                $row = $qry_exe->row_array();
                if (isset($row) && is_array($row) && count($row) > 0) {
                    http_response_code(200);
                } else {
                    // $this->session->unset_userdata('admin_user');
                    // http_response_code(401);

                    http_response_code(200);
                }
            } else {
                //  http_response_code(401);

                http_response_code(200);
            }
        }
    }

    public function _check_login($email, $password, $company_code, $ismobile = 0)
    {
        $this->load->model('Global_model', 'gm');
        if (!empty($email) && !empty($password)) {

            $company_sef = $this->input->post('company_sef');

            $username = trim(strip_tags($email));
            $company_code = trim(strip_tags($company_code));
            $company_sef = trim(strip_tags($company_sef));
            $password = trim(strip_tags($password));
            $company_host = trim($_SERVER['REQUEST_URI'], '/');

            $this->load->helper('security');
            $saltpass = md5(trim(strip_tags($password)));
            /*
             * get id,email,type from user table
             */
            $append = "";

            if ($company_sef != '' && $company_sef != 'login' && $company_sef != 'admin_login') {
                $company_sef = strtolower($company_sef);
                $append .= " AND c.sef_url='" . $company_sef . "'";
            } else {
                $append .= " AND c.is_restrict='1'"; // ADMIN LOGIN - ITD ADMIN
            }
            // $append .= " AND a.valid_till >='" . date('Y-m-d') . "'";
            $main_db = $this->load->database('main_db', true);
            $qry = "SELECT a.id,a.email,a.name as fullname,c.logo,c.sef_url,a.role,c.is_restrict,c.id as com_id,r.name,r.code as role_code
            FROM admin_user a 
            JOIN company c ON(c.id=a.company_id)
            LEFT OUTER JOIN role r ON(r.id=a.role)
             WHERE a.status IN(1) AND c.status IN(1) AND a.email='" . addslashes($username) . "' 
              AND a.password='" . $saltpass . "'" . $append;

            $qry_exe = $main_db->query($qry);
            $row = $qry_exe->row_array();


            if (isset($row['id']) && is_array($row) && count($row) > 0) {
                //GET LOGO USING SETTING
                $company_id = $row['com_id'];
                if ($company_id > 1) {
                    $conn = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, 'garment_track_company_' . $company_id);
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }
                    $sql =  "SELECT id,config_key,config_value FROM app_settings WHERE status IN(1,2) AND config_key='display_logo_of_which_company'";
                    $logo_com_result = $conn->query($sql);
                    if ($logo_com_result->num_rows > 0) {
                        $logo_row = $logo_com_result->fetch_assoc();
                        if (isset($logo_row['config_value']) && $logo_row['config_value'] > 0) {
                            $sql =  "SELECT id,logo_file FROM company_master WHERE status IN(1,2) AND id='" . $logo_row['config_value'] . "'";
                            $logo_path_result = $conn->query($sql);
                            if ($logo_path_result->num_rows > 0) {
                                $logo_path = $logo_path_result->fetch_assoc();
                            }
                        }
                    }

                 
                    $company_logo = isset($logo_path['logo_file'])  && $logo_path['logo_file'] != "" ? $logo_path['logo_file'] : $row['logo'];

                    if ($ismobile == 1) {
                        $authtoken = base64_encode(rand(10000000, 99999999) . "welcome to itds" . "_" . $company_id);
                        $addtoken_query = "update admin_user set auth_token='" . $authtoken . "' where id=" . $row['id'];
                        $main_db->query($addtoken_query);
                    }


                    $per_query = "SELECT per.config_key,per.url_key FROM user_permission_map upm LEFT OUTER JOIN permission per ON (upm.permission_id = per.id AND per.status = 1)  WHERE upm.status = 1 AND upm.user_id =" . $row['id'];
                    $per_query_exe = $main_db->query($per_query);
                    $user_permission = $per_query_exe->result_array();

                    foreach ($user_permission as $key => $value) {
                        $data['user_permission'][$key] = $value['config_key'];
                        $data['url_permission'][$key] = $value['url_key'];
                    }
                    $data['url'] = array();

                    if (isset($data['url_permission']) && is_array($data['url_permission']) && count($data['url_permission']) > 0) {
                        foreach ($data['url_permission'] as $key => $value) {
                            $exploded_url_key = explode(",", $value);
                            $data['url'] = array_filter(array_merge($data['url'], $exploded_url_key));
                        }
                    }
                    $conn->close();
                }
                $user_data = array(
                    'admin_user' => array(
                        'type' => 'software_user',
                        'id' => $row['id'],
                        'email' => $row['email'],
                        'name' => $row['fullname'],
                        'role' => $row['role'],
                        'role_name' => $row['role_code'],
                        'com_id' => $row['com_id'],
                        'sef_url' => $row['sef_url'],
                        'company_domain' => isset($row['company_domain']),
                        'is_restrict' => $row['is_restrict'],
                        'logo' =>  $company_logo,
                        'auth_token' => isset($authtoken) ? $authtoken : "",
                        'logged_in' => true,
                        "user_permission" => isset($data['user_permission']) ? $data['user_permission'] : array(),
                        "url_permission" => isset($data['url']) ? $data['url'] : array(),
                    )
                );
                $this->session->set_userdata($user_data);



                $this->load->helper('cookie');

                $this->input->set_cookie(array("name" => 'cookie_name', 'value' => $row['sef_url'], 'expire' => 2592000));
                $this->input->set_cookie(array("name" => 'cookie_type', 'value' => 'software_user', 'expire' => 2592000));

                return true;
            }
        } else {
            return false;
        }
    }

    public function logout()
    {
        $this->load->helper('cookie');
        $this->load->helper('url');
        $session_data = $this->session->userdata('admin_user');

        $com_id = $session_data['com_id'];

        $sef = $session_data['sef_url'];
        // echo "<pre>";print_r($sef);exit;
        if ($sef == '') {
            $sef =  get_cookie('cookie_name');
        }

        $type = $session_data['type'];
        if ($type == '') {
            $type =  get_cookie('cookie_type');
            $sef_portal =  get_cookie('cookie_name');
        } else {
            $sef_portal =  get_cookie('cookie_name');
        }

        $this->session->unset_userdata('admin_user');

        $main_db = $this->load->database('main_db', true);
        $host_name = $_SERVER['HTTP_HOST'];
        //LOGOUT USING COMPANY IP ADDRESS
        $qry = "SELECT id,company_name,sef_url,logo FROM company WHERE status IN(1,2) AND company_domain='" . $host_name . "'";
        $qry_exe = $main_db->query($qry);
        $row = $qry_exe->row_array();

        if (isset($row) && is_array($row) && count($row) > 0) {
            redirect(site_url());
        } else {
            if ($sef != '') {
                if ($type == 'customer' || $type == 'vendor') {
                    redirect($sef_portal);
                } else {
                    if ($sef == '' && $com_id == 1) {
                        redirect(site_url('login/admin_login'));
                    } else {
                        redirect(site_url('login/admin_login'));
                    }
                }
            } else {
                redirect(site_url('login/admin_login'));
            }
        }
    }


    function show_forgot_form()
    {
        $data = array();
        $this->load->helper('url');
        $this->load->helper('frontend_common_helper');
        $company_url = $this->input->get("company_url");
        $main_db = $this->load->database('main_db', true);
        $qry = "SELECT id FROM company WHERE status IN(1,2) AND sef_url='" . $company_url . "'";
        $qry_exe = $main_db->query($qry);
        $company_id = $qry_exe->row_array();
        $data["company_id"] = $company_id["id"];
        $this->_display('admin_forgot_password', $data);
    }
    function send_password()
    {
        $this->load->helper('url');
        $this->load->helper('email');
        $this->load->model('Global_model', 'gm');
        $main_db = $this->load->database('main_db', true);

        $qry = "SELECT * FROM admin_user WHERE status IN(1,2) AND email='" . addslashes($this->input->post('username')) . "'";
        $qry_exe = $main_db->query($qry);
        $row = $qry_exe->row_array();

        if (isset($row) && is_array($row) && count($row) > 0) {
            $recipient_email = $row["email"];
            $emailconfdata_query = "select id from email_configuration where status IN (1) AND name='OPERATIONS'";
            $emailconfdata_query_exe = $this->db->query($emailconfdata_query);
            $get_conf = $emailconfdata_query_exe->row_array();

            $otp = rand(100000, 999999);
            $query = "update admin_user set forgot_password_key='" . $otp . "',fp_key_expiry_time='" . date("Y-m-d H:i:s", strtotime('+2 hours')) . "' where id=" . $row["id"];
            $query_exec =  $main_db->query($query);
            $data["forgot_link"] = site_url() . "login/admin_login/change_password_view?cron_company=" . $this->input->get("cron_company") . "&&key=" . md5($otp);
            $subject = "Reset Password Link";
            $body = $this->load->view("forget_password_email", $data, true);
            if (isset($get_conf) && is_array($get_conf) && count($get_conf) > 0) {

                if (isset($get_conf["id"]) && !empty($get_conf["id"]) && $get_conf["id"] != "") {
                    send_email_msg($get_conf["id"], $subject, $body,  $recipient_email, $reply_email = '', $cc_email = '', $attachment = array());
                    echo "<b>Password reset email sent on your Registered email.</b>";
                    exit;
                }
            }
        } else {
            redirect(site_url());
        }
    }
    function change_password_view()
    {
        $this->load->helper('url');
        $this->load->model('Global_model', 'gm');
        $data["key"] = $this->input->get("key");
        $data["company_id"] = $this->input->get("cron_company");
        $main_db = $this->load->database('main_db', true);
        $qry = "SELECT * FROM admin_user WHERE status IN(1,2) AND MD5(forgot_password_key)='" . $data["key"] . "'";
        $qry_exe = $main_db->query($qry);
        $admin_user_data = $qry_exe->row_array();

        if (isset($admin_user_data["fp_key_expiry_time"]) && (date("Y-m-d", strtotime($admin_user_data["fp_key_expiry_time"])) != "1970-01-01")) {
            if (date("Y-m-d H:i:s", strtotime($admin_user_data["fp_key_expiry_time"])) > date("Y-m-d H:i:s")) {
                $data["username"] = $admin_user_data["user_name"];
                $this->_display('admin_password_reset', $data);
            } else {
                echo "<b style='cplor:red;'>Link Expired</b>";
                exit;
            }
        } else {
            echo "<b style='cplor:red;'>Link Expired</b>";
            exit;
        }
    }
    function update_password_admin()
    {
        $this->load->helper('url');
        $this->load->model('Global_model', 'gm');
        $data["company_id"] = $this->input->get("cron_company");
        $main_db = $this->load->database('main_db', true);
        $password = $this->input->post("password");
        $confirm_password = $this->input->post("conf_password");
        $username = $this->input->post("username");
        $key = $this->input->post("key");
        $qry = "SELECT * FROM admin_user WHERE status IN(1,2) AND MD5(forgot_password_key)='" . $key . "'";
        $qry_exe = $main_db->query($qry);
        $admin_user_data = $qry_exe->row_array();
        if (isset($admin_user_data["id"]) && !empty($admin_user_data["id"]) && $admin_user_data["id"] != NULL && isset($admin_user_data["fp_key_expiry_time"]) && (date("Y-m-d", strtotime($admin_user_data["fp_key_expiry_time"])) != "1970-01-01")) {
            if (date("Y-m-d H:i:s", strtotime($admin_user_data["fp_key_expiry_time"])) > date("Y-m-d H:i:s")) {
                if ($password == $confirm_password) {
                    $update_qry = "update admin_user set password='" . md5($password) . "' where  status IN(1,2) AND id=" . $admin_user_data["id"];
                    $main_db->query($update_qry);
                    $qry = "SELECT sef_url FROM company WHERE status IN(1,2) AND id='" . $data["company_id"] . "'";
                    $qry_exe = $main_db->query($qry);
                    $company_data = $qry_exe->row_array();
                    if (isset($company_data["sef_url"]) && !empty($company_data["sef_url"])) {
                        redirect(site_url() . $company_data["sef_url"]);
                    }
                } else {
                    echo "<b style='cplor:red;'>Password AND confirm password not matched</b>";
                    exit;
                }
            } else {
                echo "<b style='cplor:red;'>Link Expired</b>";
                exit;
            }
        } else {
            echo "<b style='cplor:red;'>Link Expired</b>";
            exit;
        }
    }
}
