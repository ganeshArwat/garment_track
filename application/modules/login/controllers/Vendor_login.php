<?php

class Vendor_login extends MX_Controller
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
        $user = $this->session->userdata('admin_user');

        if (isset($user['id']) && $user['id'] > 0) {
            redirect('adminx');
        } else {
            if ($this->session->userdata('RememberURL')) {
                redirect($this->session->userdata('RememberURL'));
            } else {
                $this->session->unset_userdata('admin_user');
                //CHECK COMPANY BY SEF URL
                $company_sef = $this->uri->segment(1);
                $main_db = $this->load->database('main_db', true);
                if ($company_sef == 'vendor_login') {
                    $company_ip = $_SERVER['HTTP_HOST'];
                    $qry = "SELECT id,company_name,sef_url,logo,status FROM company WHERE status IN(1,2) AND vendor_portal_domain LIKE '%" . $company_ip . "%'";

                    $qry_exe = $main_db->query($qry);
                    $row = $qry_exe->row_array();
                } else {

                    $qry = "SELECT id,company_name,sef_url,logo,status FROM company WHERE status IN(1,2) AND sef_url='" . $company_sef . "'";
                    $qry_exe = $main_db->query($qry);
                    $row = $qry_exe->row_array();
                }

                if (isset($row['status']) && $row['status'] != 1) {
                    echo "<h3 style='color: red;font-size: 27px;font-weight: 700;'>Hello Team,<br>
                    Site is Down for maintenance. Thank you.</h3>";
                    exit;
                }

                $data['company_name'] = isset($row['company_name']) ? $row['company_name'] : 'ITD Admin';
                $data['company_id'] = isset($row['id']) ? $row['id'] : '';


                $company_id =  $data['company_id'];
                $conn = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, 'garment_track_theme_company_' . $company_id);
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
                    'type' => 'vendor',
                    'com_id' => $data['company_id'],
                    'logo' => $data['company_logo'],
                    'is_restrict' => 2,
                    'logged_in' => true,
                    'user_permission' => array()
                );

                $this->_display('vendor_login_form', $data);
            }
        }
    }

    public function _is_logged_in()
    {
        $user = $this->session->userdata('admin_user');

        if (isset($user['id']) && $user['id'] > 0) {
            return true;
        } else {
            $last_url = $this->session->userdata('LastUrl');
            $this->session->set_userdata('RememberURL', $last_url);
            return false;
        }
    }

    public function uvf_login()
    {
        $this->load->model('Global_model', 'gm');
        $this->load->helper('url');
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

                $current_session =  session_id();
                $session_data = $this->session->userdata('admin_user');

                //logout on other system for same account
                $deleteq = "DELETE FROM ci_sessions WHERE user_id=" . $session_data['id'] . " AND id!='" . $current_session . "'";
                $this->db->query($deleteq);

                if ($is_mobile_app == 1) {
                    return true;
                } else {
                    http_response_code(200);
                }
            } else {
                if ($is_mobile_app == 1) {
                    return false;
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

    public function _check_login($email, $password, $company_code, $ismobile = 0)
    {
        $this->load->model('Global_model', 'gm');
        if (!empty($email) && !empty($password)) {

            $post_data = $this->input->post();
            $main_db = $this->load->database('main_db', true);

            $company_sef = $this->input->post('company_sef');
            $company_url = $this->input->post('company_url');

            $username = trim(strip_tags($email));
            $company_code = trim(strip_tags($company_code));
            $company_sef = trim(strip_tags($company_sef));
            $password = trim(strip_tags($password));
            $company_host = trim($_SERVER['REQUEST_URI'], '/');


            $this->load->helper('security');
            $saltpass = md5(trim(strip_tags($password)));

            if (isset($post_data['company_id']) && $post_data['company_id'] > 0) {
                $company_id = $post_data['company_id'];
                $conn = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, 'garment_track_theme_company_' . $company_id);
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                //INACTIVE USER CAN ALSO BE LOGIN ONLY THEY CAN NOT ADD ANY DATA
                $sql =  "SELECT cu.*,c.status as customer_status FROM vendor_users cu 
                LEFT OUTER JOIN co_vendor c ON(cu.co_vendor_id=c.id)
                WHERE cu.status=1 AND cu.email_id='" . addslashes($username) . "' 
              AND cu.password='" . $saltpass . "' AND c.status=1";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // output data of each row

                    while ($row = $result->fetch_assoc()) {
                        $main_db = $this->load->database('main_db', true);
                        $qry = "SELECT id,company_name,sef_url,logo FROM company WHERE status IN(1,2) AND id='" . $company_id . "'";
                        $qry_exe = $main_db->query($qry);
                        $company_row = $qry_exe->row_array();

                        //GET LOGO USING SETTING
                        $conn = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, 'garment_track_theme_company_' . $company_id);
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

                        $company_logo = isset($logo_path['logo_file']) ? $logo_path['logo_file'] : $company_row['logo'];

                        $user_data = array(
                            'admin_user' => array(
                                'type' => 'vendor',
                                'id' => $row['id'],
                                'email_id' => $row['email_id'],
                                'name' => $row['name'],
                                'co_vendor_id' => $row['co_vendor_id'],
                                'vendor_status' => $row['customer_status'],
                                'logo' => $company_logo,
                                'com_id' => $company_id,
                                'is_restrict' => 2,
                                'sef_url' => $company_row["sef_url"],
                                'logged_in' => true,

                            )
                        );

                        $conn->close();
                        $this->session->set_userdata($user_data);

                        $this->load->helper('cookie');

                        $this->input->set_cookie(array("name" => 'cookie_name', 'value' => $company_url, 'expire' => 2592000));
                        $this->input->set_cookie(array("name" => 'cookie_type', 'value' => 'vendor', 'expire' => 2592000));
                        return true;
                    }
                } else {
                    $conn->close();
                    return false;
                }


                //     $qry = "SELECT * FROM vendor_users c WHERE c.status IN(1,2) AND c.email_id='" . addslashes($username) . "' 
                //   AND c.password='" . $saltpass . "'";
                //     $qry_exe = $this->db->query($qry);
                //     $row = $qry_exe->row_array();


            }
        } else {
            return false;
        }
    }

    public function logout()
    {
        $this->load->helper('url');
        $session_data = $this->session->userdata('admin_user');
        $sef = $session_data['sef_url'];
        $this->session->unset_userdata('admin_user');
        redirect(site_url() . "/user_login");
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

        $this->_display('user_forgot_password', $data);
    }
    function send_password()
    {
        $this->load->helper('url');
        $this->load->helper('email');
        $this->load->model('Global_model', 'gm');

        $qry = "SELECT * FROM vendor_users WHERE status IN(1,2) AND email_id='" . addslashes($this->input->post('username')) . "'";
        $qry_exe = $this->db->query($qry);
        $row = $qry_exe->row_array();


        if (isset($row) && is_array($row) && count($row) > 0) {
            $recipient_email = $row["email_id"];
            $emailconfdata_query = "select id from email_configuration where status IN (1) AND name='OPERATIONS'";
            $emailconfdata_query_exe = $this->db->query($emailconfdata_query);
            $get_conf = $emailconfdata_query_exe->row_array();

            $otp = rand(100000, 999999);
            $query = "update vendor_users set forgot_password_key='" . $otp . "',fp_key_expiry_time='" . date("Y-m-d H:i:s", strtotime('+2 hours')) . "' where id=" . $row["id"];
            $query_exec =   $this->db->query($query);
            $data["forgot_link"] = site_url() . "login/user_login/change_password_view?cron_company=" . $this->input->get("cron_company") . "&&key=" . md5($otp);
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

        $qry = "SELECT * FROM vendor_users WHERE status IN(1,2) AND MD5(forgot_password_key)='" . $data["key"] . "'";
        $qry_exe = $this->db->query($qry);
        $admin_user_data = $qry_exe->row_array();

        if (isset($admin_user_data["fp_key_expiry_time"]) && (date("Y-m-d", strtotime($admin_user_data["fp_key_expiry_time"])) != "1970-01-01")) {
            if (date("Y-m-d H:i:s", strtotime($admin_user_data["fp_key_expiry_time"])) > date("Y-m-d H:i:s")) {
                $data["username"] = $admin_user_data["user_name"];
                $this->_display('user_password_reset', $data);
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
        $qry = "SELECT * FROM vendor_users WHERE status IN(1,2) AND MD5(forgot_password_key)='" . $key . "'";
        $qry_exe = $this->db->query($qry);
        $customer_user_data = $qry_exe->row_array();
        if (isset($customer_user_data["id"]) && !empty($customer_user_data["id"]) && $customer_user_data["id"] != NULL && isset($customer_user_data["fp_key_expiry_time"]) && (date("Y-m-d", strtotime($customer_user_data["fp_key_expiry_time"])) != "1970-01-01")) {
            if (date("Y-m-d H:i:s", strtotime($customer_user_data["fp_key_expiry_time"])) > date("Y-m-d H:i:s")) {
                if ($password == $confirm_password) {
                    $update_qry = "update vendor_users set password='" . md5($password) . "' where  status IN(1,2) AND id=" . $customer_user_data["id"];
                    $this->db->query($update_qry);
                    $qry = "SELECT sef_url FROM company WHERE status IN(1,2) AND id='" . $data["company_id"] . "'";
                    $qry_exe = $main_db->query($qry);
                    $company_data = $qry_exe->row_array();
                    if (isset($company_data["sef_url"]) && !empty($company_data["sef_url"])) {
                        redirect(site_url() . $company_data["sef_url"] . "/user_login");
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
