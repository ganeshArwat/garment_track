<?php
class Run_sheets extends MX_Controller
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
        $data['heading'] = 'OLD DATA Import';
        $data['parent_nav'] = 'master';
        $this->load->view('admin_header', $data);
        $this->load->view('sidebar', $data);
        $this->load->view($view, $data);
        $this->load->view('admin_footer');
    }
    public function insert_data()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');
        $this->load->helper('upload');
        $this->load->library('CSVReader');

        $this->load->module('generic_detail');
        $this->load->module('manifest');
        $upload_response = upload_file('migration_data', 'import_file');

        $insert_count = 0;
        if (isset($upload_response['status']) && $upload_response['status'] == 'success') {
            $csv_file =  $upload_response['file_path'];
            $params = array(
                'separator' => ',',
                'enclosure' => '"',
            );
            $this->csvreader->initialize($params);
            $csvData = $this->csvreader->parse_file_new($csv_file);

            $insert_count = 0;

            if (isset($csvData) && is_array($csvData) && count($csvData) > 0) {
                $all_hub = get_all_hub(" AND status IN(1,2) ", "code");
                $all_route = get_all_route(" AND status IN(1,2) ", "code");
                foreach ($csvData as $u_key => $cvalue) {
                    $count = $u_key + 1;
                    $data['count'] = $count;
                    $drs_date = '';
                    $hub_id = 0;
                    $route_id = 0;
                    if (isset($cvalue['drs_date']) && $cvalue['drs_date'] != '') {
                        $drs_date = format_date($cvalue['drs_date']);
                    }
                    if (isset($cvalue['hub_code']) && $cvalue['hub_code'] != '') {
                        $hub_id = isset($cvalue['hub_code']) && isset($all_hub[strtolower(trim($cvalue['hub_code']))]) ? $all_hub[strtolower(trim($cvalue['hub_code']))]['id'] : 0;
                        // if ($hub_id == 0) {
                        //     $hub_id = check_record_exist(array('name' => $cvalue['hub_code']), 1, 'hub', 'code');
                        //     $all_hub[strtolower(trim($cvalue['hub_code']))]['id'] = $hub_id;
                        // }
                    }
                    if (isset($cvalue['route_master_code']) && $cvalue['route_master_code'] != '') {
                        $route_name = isset($cvalue['route_master_code']) && isset(strtolower(trim($cvalue['route_master_code']))) ? strtolower(trim($cvalue['route_master_code'])) : 0;
                        // if ($route_id == 0) {
                        //     $route_id = check_record_exist(array('name' => $cvalue['route_master_code']), 1, 'route', 'code');
                        //     $all_route[strtolower(trim($cvalue['route_master_code']))]['id'] = $route_id;
                        // }
                    }
                    $insert_data = array(
                        'run_sheet_no' => isset($cvalue['number']) ? $cvalue['number']  : '',
                        'drs_date' => $drs_date,
                        'hub_id' => $hub_id,
                        'user_id' => 0, //DELIVERY BOY
                        'route_name' => $route_name,
                        'vehicle_no' => isset($cvalue['vehicle_number']) ? $cvalue['vehicle_number'] : '',
                        'vehicle_type' => isset($cvalue['vehicle_type']) ? $cvalue['vehicle_type'] : '',
                        'driver_name' => isset($cvalue['driver_name']) ? $cvalue['driver_name'] : '',
                        'created_date' => isset($cvalue['created_at']) ? $cvalue['created_at'] : date('Y-m-d H:i:s'),
                        'created_by' => $this->user_id,
                        'migration_id' => isset($cvalue['id']) ? $cvalue['id'] : 0
                    );
                    $recordExist = array();
                    if ((int)$cvalue['id'] > 0) {
                        $recordExist = $this->gm->get_selected_record('run_sheet', 'id', array('migration_id' => $cvalue['id']), array());
                    }
                    if (isset($recordExist) && is_array($recordExist) && count($recordExist) > 0) {
                        $this->gm->update('run_sheet', $insert_data, '', array('id' => $recordExist['id']));
                        $data['error'] = 'RUNSHEET UPDATED SUCCESSFULLY on row ' . $count . '';
                        $non_inserted_data[] = $data;
                    } else {
                        $this->gm->insert('run_sheet', $insert_data);
                    }
                }
                if (isset($non_inserted_data) && is_array($non_inserted_data) && count($non_inserted_data) > 0) {
                    $data['insert_count'] = $insert_count;
                    $data['non_inserted_data'] = $non_inserted_data;
                    $data['total_csv_rec_count'] = isset($csvData) && is_array($csvData) ? count($csvData) : 0;
                    $this->_display('upload_success_list', $data);
                } else {
                    $this->session->set_flashdata('add_feedback', 'RUNSHEET imported successfully.');
                    redirect(site_url('run_sheets/show_list'));
                }
            } else {
                $this->session->set_flashdata('add_feedback', 'No Data present in file');
                redirect(site_url('mirgation/import_data/show_form/run_sheets'));
            }
        } else {
            $this->session->set_flashdata('add_feedback', 'Failed to upload file');
            redirect(site_url('mirgation/import_data/show_form/run_sheets'));
        }
    }
}
