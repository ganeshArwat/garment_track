<?php

class Login extends MX_Controller
{

    function index()
    {
        $this->load->helper('frontend_common');
        $this->show_form();
    }

    function _display($view, $data)
    {
        $this->load->view('frontend_header');
        $this->load->view($view, $data);
        $this->load->view('frontend_footer');
    }

    function login_display()
    {
        $this->load->helper('url');
        $this->load->view('frontend_header');
        $this->load->view('login');
        $this->load->view('frontend_footer');
    }

    function _is_logged_in()
    {
        $user = $this->session->userdata('user');
        $school_admin = $this->session->userdata('school_admin');
        $admin_user = $this->session->userdata('admin_user');
        if ($user['id'] > 0) {
            return TRUE;
        } else if ($school_admin['id'] > 0) {
            return TRUE;
        } else if ($admin_user['id'] > 0) {
            return TRUE;
        } else {
            $last_url = $this->session->userdata('LastUrl');
            $this->session->set_userdata('RememberURL', $last_url);
            return FALSE;
        }
    }

    function show_form()
    {

        $this->load->helper('url');
        $data = array();
        //require '/facebook-sdk-v5/autoload.php';
        //print_r($data);
        //exit;
        $this->_display('loginform', $data);
        /* if ($this->session->userdata('RememberURL')) {
          redirect($this->session->userdata('RememberURL'));
          } else {
          $data = array();
          $this->_display('loginform', $data);
          } */
    }

    function uvf_login()
    {

        $this->load->helper('url');
        $this->load->model('Global_model', 'gm');
        $setid = $this->input->post('setid');
        $username = $this->input->post('contact');
        $password = $this->input->post('password');

        if (isset($username) && !empty($username) && isset($password) && !empty($password)) {

            if ($this->_check_login($username, $password, $setid)) {

                //if($_SESSION['user']['exam_status']==2)
                //{
                //	redirect(site_url('dashboard/login').'?username='.$_SESSION['user']['usercode'].'&password='.$_SESSION['user']['password']);
                //}
                //else
                //{
                http_response_code(200);
                //}
            } else {
                http_response_code(401);
            }
        } else {
            http_response_code(401);
        }
    }
    function through_website_login()
    {

        $this->load->helper('url');
        // $setid = $this->uri->segment(4);
        $this->load->model('Global_model', 'gm');
        $username = $this->input->post('contact');
        $password = $this->input->post('password');
        $setid = $this->input->post('setid');
        if (isset($username) && !empty($username) && isset($password) && !empty($password)) {

            if ($this->_check_login($username, $password, $setid)) {

                redirect('reg/reg_details/details/');
                //http_response_code(200);
            } else {
                http_response_code(401);
            }
        } else {
            http_response_code(401);
        }
    }

    function _check_login($username, $password, $setid = '')
    {

        $this->load->helper('url');
        //$setid = $this->uri->segment(4);
        //$setid = $setid;
        if (!empty($username) && !empty($password)) {

            $username = addslashes(trim(strip_tags($username)));
            $password = trim(strip_tags($password));
            $saltpass = md5(trim(strip_tags($password)));
            $query = "select * from `user` where `encrypt_password` ='" . $saltpass . "' AND (`usercode` = '" . $username . "' OR `contact_no1` = '" . $username . "')";

            $query_exec = $this->db->query($query);
            $row = $query_exec->row_array();


            if (isset($row) && is_array($row) && count($row) > 0) {

                $user = array(
                    'id' => $row['id'],
                    'username' => $row['name'],
                    'usercode' => $row['usercode'],
                    'password' => $row['password'],
                    'email_id1' => $row['email_id1'],
                    'contact_no1' => $row['contact_no1'],
                    'status' => $row['status'],
                    'role_id' => $row['setid'],
                    'exam_status' => $row['exam_status'],
                );

                if ($row['role_id'] == 1) {
                    $user_information = $this->gm->get_selected_record('school', '*', array('id' => $row['entity_id']));
                }
                if ($row['role_id'] == 2) {
                    $user_information = $this->gm->get_selected_record('student', '*', array('id' => $row['entity_id']));
                }
                //echo '<pre>';
                //print_r($user_information);
                //exit;

                $session_data = array(
                    'setid' => isset($setid) && $setid != '' ? $setid : '',
                    'user' => $user,
                    'user_information' => isset($user_information) ? $user_information : ''
                );

                $this->session->set_userdata($session_data);

                return TRUE;
            } else {
                return FALSE;
            }
        } else {

            return FALSE;
        }
    }

    function logout()
    {
        $this->load->helper('url');
        $this->session->unset_userdata('user');
        redirect(site_url());
    }

    function show_forgot_form()
    {
        $data = array();
        $this->load->helper('url');
        $this->load->helper('frontend_common_helper');
        $this->_display('register_user_show_forgot_form', $data);
    }

    function send_password()
    {
        $this->load->helper('url');

        $this->load->model('Global_model', 'gm');


        $row = $this->gm->get_selected_record('user', '*', array('email_id' => addslashes($this->input->post('email'))));
        //echo '<pre>';
        //print_r($row);
        //exit;
        if (isset($row) && is_array($row) && count($row) > 0) {
            $data['email'] = $email;
            $otp = rand(100000, 999999);
            $body = "Your verification code=" . $otp;
            $this->load->library('email');

            $config['protocol'] = "smtp";
            $config['mailtype'] = "html";
            $config['charset'] = "utf-8";
            $config['priority'] = "1";
            $config['smtp_host'] = "24mehta.com";
            $config['smtp_user'] = "noreply@24mehta.com";
            $config['smtp_pass'] = "fn$=+S#PA!RM";
            $config['smtp_port'] = "587";

            $this->email->initialize($config);

            $this->email->from('noreply@24mehta.com', '24mehta.com');
            $this->email->to($email, $email);
            $this->email->subject('Verification code ' . date('D-M-Y h:i:sa'));
            print_r($body);
            $this->email->message($body);
            $this->email->send();

            $forgot_password = array(
                'user_id' => $row['id'],
                'status' => 1,
                'otp' => $otp,
                'created_date' => date('y-m-d H:i:s')
            );

            $data = $this->gm->insert('forgot_password', $forgot_password);

            $result = array(
                'email' => $email
            );
            redirect(site_url('login/reset_password'));
        } else {
            redirect(site_url());
        }
    }

    function reset_password()
    {
        $data = array();
        $this->load->helper('url');
        $this->load->helper('frontend_common_helper');
        $this->_display('login_reset_password_form', $data);
    }

    function update_new_password()
    {
        $data = array();
        $this->load->model('Global_model', 'gm');
        $this->load->helper('url');
        $this->load->helper('frontend_common_helper');
        $user = $this->input->post('user_information');
        $row = $this->gm->get_selected_record('forgot_password', '*', array('status' => 1, 'otp' => $user['otp'], 'user_id' => $user['id']));
        if (isset($row) && is_array($row) && count($row) > 0) {
            $data = array(
                'password' => md5($user['password'])
            );
            $this->gm->update('user', $data, $id = 0, $where = array('id' => $row['user_id']));
        }
        //redirect(site_url());
        //echo '<pre>';
        //print_r($row);
        //exit;
    }

    function set_geo_data()
    {
        if ($this->input->is_ajax_request()) {
            $_SESSION['locality']['lat'] = $this->input->post('lat');
            $_SESSION['locality']['long'] = $this->input->post('long');
            $_SESSION['locality']['location'] = $this->input->post('location');
            http_response_code(200);
        }
    }

    function g_success()
    {
        //http://localhost/sixpackabsindia/login/g_success?code=4/ZDLzI85wtmrCcA8JiE3_qcJSJxlmtFzszbli4HYnIvw#
        //echo '<pre>';
        //print_r($_GET);
        //print_r($_POST);
        //print_r($_REQUEST);
        //print_r($_SESSION);
        if ($_GET['error']) {
            $this->load->helper('url');
            redirect('login?error=g');
        }
        $this->load->helper('google_login_helper');
        get_data();
    }

    function dmit_dashboard()
    {
        $this->load->helper('url');
        $post = array(
            'username' => 'BW02713',
            'password' => '61371'
        );

        $this->dashboard_login($post);
        redirect(base_url('dashboard/dmit_dashboard1'));
    }

    function dashboard_login($post = array())
    {

        $this->load->model('Global_model', 'gm');

        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = $this->input->post('username');
            $password = $this->input->post('password');
        } else {
            //$post = $post;
            $username = $post['username'];
            $password = $post['password'];
        }


        if (isset($username) && !empty($username) && isset($password) && !empty($password)) {
            if ($this->_check_login($username, $password)) {
                if ($this->input->is_ajax_request()) {
                    http_response_code(200);
                } else {
                    redirect(site_url('dashboard/student_interest'));
                }
            } else {
                http_response_code(401);
            }
        } else {
            http_response_code(401);
        }
    }
    function web_forgotpassword()
    {
        $data = array();
        $this->load->helper('url');
        $this->load->helper('frontend_common_helper');
        $this->load->view('backend_header_login');
        $this->load->view('web_forgotpassword');
        $this->load->view('backend_footer_login');
    }

    function update_webforgotpassword()
    {
        $this->load->helper('url');
        $this->load->model('Global_model', 'gm');
        $this->load->helper('email');

        $email = $this->input->post('reg_email');

        $query = "select id, username, email_id, usercode, password from user where email_id ='" . trim($email) . "' order by id desc";
        $result = $this->db->query($query)->row_array();

        if (isset($result) && is_array($result) && count($result) > 0) {
            $subject = "Your Brainwonders Usercode and password";
            $body = 'Dear ' . $result['username'] . ',<br><br>';
            $body .= 'Here is your Usercode and Password for the test.<br>
                      Usercode: ' . $result['usercode'] . '<br>
                      Password: ' . $result['password'] . '<br><br>Please connect with us <a href="https://www.brainwonders.in/contact.php">here</a> or call on +91 9987422220/ +91 9987766531 For any other query.<br>';
            $body .= '<br><br>Thanks & Regards<br>Wishing you the very best,<br>Brainwonders!';
            $emailsent = send_email12($email, $subject, $body, 'result@brainwonders.in', $reply_email = "", $cc_email = "", $bcc_email = "", $attachment = array(), $entity_data = array());
            redirect('https://brainwonders.in/aptitudetest/index.php/login/web_forgotpassword?message=Email with usercode and password sent.');
        } else {
            redirect('https://brainwonders.in/aptitudetest/index.php/login/web_forgotpassword?message=The Entered Email is not registered with us.');
        }
    }
}
