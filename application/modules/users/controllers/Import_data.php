<?php
class Import_data extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->module('login/admin_login');

        $login = new Admin_login();
        $check_login = $login->_is_logged_in();

        if (!($check_login)) {
            $this->session->set_userdata('login_page', 'backend');
            $this->load->helper('url');
            redirect(site_url());
        }
        $sessiondata = $this->session->userdata('admin_user');
        $this->user_id = $sessiondata['id'];
    }
    public function _display($view, $data)
    {
        $data['heading'] = 'User Import';
        $data['parent_nav'] = 'free_form_items';
        $this->load->view('admin_header', $data);
        $this->load->view('sidebar', $data);
        $this->load->view($view, $data);
        $this->load->view('admin_footer');
    }
    public function show_form()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $data = isset($_SESSION['import_data']) ? $_SESSION['import_data'] : array();
        if (isset($_SESSION['import_data'])) {
            unset($_SESSION['import_data']);
            $data['import_response'] = 1;
        }

        $data['sample_file_path'] = 'media/sample_csv/user_import.csv';
        $data['file_upload_action'] = 'users/import_data/insert_data';
        $this->_display('show_import_form', $data);
    }
    public function insert_data()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');
        $this->load->helper('upload');
        $this->load->library('CSVReader');

        $post_data = $this->input->post();
        if (isset($post_data['submit_check']) && $post_data['submit_check'] == 1) {
            $upload_response = upload_file('user_import', 'import_file');
            $non_inserted_data = array();
            $insert_count = 0;
            if (isset($upload_response['status']) && $upload_response['status'] == 'success') {
                $csv_file =  $upload_response['file_path'];
                $params = array(
                    'separator' => ',',
                    'enclosure' => '"',
                );
                $this->csvreader->initialize($params);
                $csvData = $this->csvreader->parse_file_new($csv_file);


                if (isset($csvData) && is_array($csvData) && count($csvData) > 0) {
                    $all_role = get_all_role(" AND status IN(1,2)", "name");
                    $all_hub = get_all_hub("", "code");
                    $main_db = $this->load->database('main_db', true);
                    foreach ($csvData as $u_key => $cvalue) {
                        $insert_data = array();
                        if ($cvalue['name'] == '') {
                            $count = $u_key + 1;
                            $data['count'] = $count;
                            $data['error'] =  'NAME REQUIRED at row ' . $count . '';
                            $non_inserted_data[] = $data;
                            continue;
                        } else if ($cvalue['email'] == '') {
                            $count = $u_key + 1;
                            $data['count'] = $count;
                            $data['error'] =  'EMAIL ID REQUIRED at row ' . $count . '';
                            $non_inserted_data[] = $data;
                            continue;
                        } else if ($cvalue['contact_no'] == '') {
                            $count = $u_key + 1;
                            $data['count'] = $count;
                            $data['error'] =  'CONTACT NO. REQUIRED at row ' . $count . '';
                            $non_inserted_data[] = $data;
                            continue;
                        } else if ($cvalue['role'] == '') {
                            $count = $u_key + 1;
                            $data['count'] = $count;
                            $data['error'] =  'ROLE REQUIRED at row ' . $count . '';
                            $non_inserted_data[] = $data;
                            continue;
                        } else if ($cvalue['password'] == '') {
                            $count = $u_key + 1;
                            $data['count'] = $count;
                            $data['error'] =  'Password REQUIRED at row ' . $count . '';
                            $non_inserted_data[] = $data;
                            continue;
                        } else {
                            $sessiondata = $this->session->userdata('admin_user');

                            if (isset($cvalue['migration_id']) && $cvalue['migration_id'] > 0 && $sessiondata['com_id'] > 0) {
                                $qry = "SELECT id FROM admin_user 
                                WHERE migration_id='" . $cvalue['migration_id'] . "' AND company_id='" . $sessiondata['com_id'] . "'";
                                $qry_exe = $main_db->query($qry);
                                $existData = $qry_exe->row_array();
                            }
                            $user_id = isset($existData['id']) ? $existData['id'] : 0;

                            $role_id = isset($cvalue['role']) && isset($all_role[strtolower(trim($cvalue['role']))]) ? $all_role[strtolower(trim($cvalue['role']))]['id'] : 0;
                            $insert_data = array(
                                'name' => $cvalue['name'],
                                'user_name' => $cvalue['email'],
                                'password' => md5($cvalue['password']),
                                'email' => $cvalue['email'],
                                'contactno' => $cvalue['contact_no'],
                                'role' => $role_id,
                                'company_id' => $sessiondata['com_id'],
                                'valid_till' => '2030-01-01',
                                'migration_id' => $cvalue['migration_id']
                            );

                            // CHECK EMAIL ALREADY EXIST
                            $qry = "SELECT id FROM admin_user WHERE status IN(1,2) AND user_name='" . $cvalue['email'] . "'
                            AND company_id='" . $sessiondata['com_id'] . "'";
                            $qry_exe = $main_db->query($qry);
                            $emailExist = $qry_exe->row_array();

                            if (isset($emailExist) && is_array($emailExist) && count($emailExist) > 0) {
                                $count = $u_key + 1;
                                $data['count'] = $count;
                                $data['error'] =  'EMAIL ID ' . $cvalue['email'] . ' already present at row ' . $count . '';
                                $non_inserted_data[] = $data;
                                continue;
                            } else {
                                if ($user_id > 0) {
                                    $insert_data['modified_date'] = date('Y-m-d H:i:s');
                                    $insert_data['modified_by'] = $this->user_id;
                                    $insert_data['status'] = 1;

                                    $main_db->where(array('id' => $user_id));
                                    $main_db->update('admin_user', $insert_data);

                                    $count = $u_key + 1;
                                    $data['count'] = $count;
                                    $data['error'] = 'user updated successfully on row ' . $count . '';
                                    $non_inserted_data[] = $data;
                                    $insert_count++;
                                } else {
                                    $insert_data['created_date'] = date('Y-m-d H:i:s');
                                    $insert_data['created_by'] = $this->user_id;
                                    $main_db->insert('admin_user', $insert_data);
                                    $user_id = $main_db->insert_id();


                                    //ADD DOCKET PERMISSION
                                    $docket_perq = "INSERT INTO `user_permission_map` (`status`, `user_id`, `permission_id`, `created_date`, `created_by`, `modified_date`, `modified_by`, `permission_type`) VALUES
                                    (1, $user_id, 8, '2022-05-10 10:25:35', 3, '0000-00-00 00:00:00', 0, 'Fixed'),
                                    (1, $user_id, 9, '2022-05-10 10:25:35', 3, '0000-00-00 00:00:00', 0, 'Fixed'),
                                    (1, $user_id, 10, '2022-05-10 10:25:35', 3, '0000-00-00 00:00:00', 0, 'Fixed'),
                                    (1, $user_id, 11, '2022-05-10 10:25:35', 3, '0000-00-00 00:00:00', 0, 'Fixed'),
                                    (1, $user_id, 12, '2022-05-10 10:25:35', 3, '0000-00-00 00:00:00', 0, 'Fixed'),
                                    (1, $user_id, 13, '2022-05-10 10:25:35', 3, '0000-00-00 00:00:00', 0, 'Fixed'),
                                    (1, $user_id, 14, '2022-05-10 10:25:35', 3, '0000-00-00 00:00:00', 0, 'Fixed'),
                                    (1, $user_id, 316, '2022-05-10 10:25:35', 3, '0000-00-00 00:00:00', 0, 'Special'),
                                    (1, $user_id, 317, '2022-05-10 10:25:35', 3, '0000-00-00 00:00:00', 0, 'Special'),
                                    (1, $user_id, 318, '2022-05-10 10:25:35', 3, '0000-00-00 00:00:00', 0, 'Special'),
                                    (1, $user_id, 319, '2022-05-10 10:25:35', 3, '0000-00-00 00:00:00', 0, 'Special'),
                                    (1, $user_id, 320, '2022-05-10 10:25:36', 3, '0000-00-00 00:00:00', 0, 'Special'),
                                    (1, $user_id, 321, '2022-05-10 10:25:36', 3, '0000-00-00 00:00:00', 0, 'Special'),
                                    (1, $user_id, 322, '2022-05-10 10:25:36', 3, '0000-00-00 00:00:00', 0, 'Special'),
                                    (1, $user_id, 323, '2022-05-10 10:25:36', 3, '0000-00-00 00:00:00', 0, 'Special'),
                                    (1, $user_id, 324, '2022-05-10 10:25:36', 3, '0000-00-00 00:00:00', 0, 'Special'),
                                    (1, $user_id, 325, '2022-05-10 10:25:36', 3, '0000-00-00 00:00:00', 0, 'Special'),
                                    (1, $user_id, 326, '2022-05-10 10:25:36', 3, '0000-00-00 00:00:00', 0, 'Special'),
                                    (1, $user_id, 327, '2022-05-10 10:25:36', 3, '0000-00-00 00:00:00', 0, 'Special'),
                                    (1, $user_id, 328, '2022-05-10 10:25:36', 3, '0000-00-00 00:00:00', 0, 'Special'),
                                    (1, $user_id, 329, '2022-05-10 10:25:36', 3, '0000-00-00 00:00:00', 0, 'Special'),
                                    (1, $user_id, 330, '2022-05-10 10:25:36', 3, '0000-00-00 00:00:00', 0, 'Special');";
                                    $main_db->query($docket_perq);

                                    $count = $u_key + 1;
                                    $data['count'] = $count;
                                    $data['error'] = 'user added successfully on row ' . $count . '';
                                    $non_inserted_data[] = $data;
                                    $insert_count++;
                                }


                                //ADD HUB
                                $hub_insert = array();
                                if (isset($cvalue['hub_code']) && $cvalue['hub_code'] != '') {
                                    $hub_arr = explode(",", $cvalue['hub_code']);
                                    if (isset($hub_arr) && is_array($hub_arr) && count($hub_arr) > 0) {
                                        $updateq = "DELETE FROM hub_mapping WHERE module_id='" . $user_id . "' AND module_type=2";
                                        $this->db->query($updateq);

                                        foreach ($hub_arr as $hkey => $hvalue) {
                                            $cust_hub_id = $hvalue != '' && isset($all_hub[strtolower(trim($hvalue))]) ? $all_hub[strtolower(trim($hvalue))]['id'] : 0;
                                            if ($cust_hub_id > 0) {
                                                $hub_insert[] = array(
                                                    'module_id' => $user_id,
                                                    'module_type' => 2,
                                                    'hub_id' => $cust_hub_id,
                                                    'created_date' => date('Y-m-d H:i:s'),
                                                    'created_by' => $this->user_id
                                                );
                                            }
                                        }

                                        if (isset($hub_insert) && is_array($hub_insert) && count($hub_insert) > 0) {
                                            $this->db->insert_batch('hub_mapping', $hub_insert);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                $this->session->set_flashdata('add_feedback', 'Failed to upload file');
                redirect(site_url('users/show_list'));
            }

            $data['insert_count'] = $insert_count;
            $data['non_inserted_data'] = $non_inserted_data;
            $data['total_csv_rec_count'] = isset($csvData) && is_array($csvData) ? count($csvData) : 0;

            $data['sample_file_path'] = 'media/sample_csv/user_import.csv';
            $data['file_upload_action'] = 'users/import_data/insert_data';
            $this->_display('upload_success_list', $data);
        } else {
            redirect('users/import_data/show_form');
        }
    }
}
