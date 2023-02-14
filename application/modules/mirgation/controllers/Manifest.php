<?php
class Manifest extends MX_Controller
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
                $all_forwarder = get_all_forwarder(" AND status IN(1,2) ", "code");
                $all_vendor = get_all_vendor(" AND status IN(1,2) ", "code");
                $all_hub = get_all_hub(" AND status IN(1,2) ", "code");
                foreach ($csvData as $u_key => $cvalue) {
                    $count = $u_key + 1;
                    $data['count'] = $count;

                    $manifest_date = '';
                    $booking_max = '';
                    $forwarder_id = 0;
                    $vendor_id = 0;
                    $co_vendor_id = 0;
                    $arrival_date = '';
                    $dest_hub_id = 0;
                    $ori_hub_id = 0;
                    if (isset($cvalue['manifest_date']) && $cvalue['manifest_date'] != '') {
                        $manifest_date = format_date($cvalue['manifest_date']);
                    }
                    if (isset($cvalue['arrival_date']) && $cvalue['arrival_date'] != '') {
                        $arrival_date = format_date($cvalue['arrival_date']);
                    }
                    if (isset($cvalue['forwarder_code']) && $cvalue['forwarder_code'] != '') {
                        $forwarder_id = isset($cvalue['forwarder_code']) && isset($all_forwarder[strtolower(trim($cvalue['forwarder_code']))]) ? $all_forwarder[strtolower(trim($cvalue['forwarder_code']))]['id'] : 0;
                        if ($forwarder_id == 0) {
                            $count = $u_key + 1;
                            $data['count'] = $count;
                            $data['error'] =  'forwarder CODE ' . $cvalue['forwarder_code'] . ' NOT FOUND at row ' . $count . '';
                            $non_inserted_data[] = $data;
                            continue;
                        }
                        // if ($forwarder_id == 0) {
                        //     $forwarder_id = check_record_exist(array('name' => $cvalue['forwarder_code']), 1, 'forwarder', 'code');
                        //     $all_forwarder[strtolower(trim($cvalue['forwarder_code']))]['id'] = $forwarder_id;
                        // }
                    }
                    if (isset($cvalue['vendor_code']) && $cvalue['vendor_code'] != '') {
                        $vendor_id = isset($cvalue['vendor_code']) && isset($all_vendor[strtolower(trim($cvalue['vendor_code']))]) ? $all_vendor[strtolower(trim($cvalue['vendor_code']))]['id'] : 0;
                        if ($vendor_id == 0) {
                            $count = $u_key + 1;
                            $data['count'] = $count;
                            $data['error'] =  'service CODE ' . $cvalue['vendor_code'] . ' NOT FOUND at row ' . $count . '';
                            $non_inserted_data[] = $data;
                            continue;
                        }
                        // if ($vendor_id == 0) {
                        //     $vendor_id = check_record_exist(array('name' => $cvalue['vendor_code']), 1, 'vendor', 'code');
                        //     $all_vendor[strtolower(trim($cvalue['vendor_code']))]['id'] = $vendor_id;
                        // }
                    }

                    if (isset($cvalue['co_vendor_code']) && $cvalue['co_vendor_code'] != '') {
                        $co_vendor_id = isset($cvalue['co_vendor_code']) && isset($all_co_vendor[strtolower(trim($cvalue['co_vendor_code']))]) ? $all_co_vendor[strtolower(trim($cvalue['co_vendor_code']))]['id'] : 0;
                        if ($co_vendor_id == 0) {
                            $count = $u_key + 1;
                            $data['count'] = $count;
                            $data['error'] =  'vendor CODE ' . $cvalue['co_vendor_code'] . ' NOT FOUND at row ' . $count . '';
                            $non_inserted_data[] = $data;
                            continue;
                        }
                        // if ($co_vendor_id == 0) {
                        //     $co_vendor_id = check_record_exist(array('name' => $cvalue['co_vendor_code']), 1, 'co_vendor', 'code');
                        //     $all_co_vendor[strtolower(trim($cvalue['co_vendor_code']))]['id'] = $co_vendor_id;
                        // }
                    }

                    if (isset($cvalue['destination_hub_code']) && $cvalue['destination_hub_code'] != '') {
                        $dest_hub_id = isset($cvalue['destination_hub_code']) && isset($all_hub[strtolower(trim($cvalue['destination_hub_code']))]) ? $all_hub[strtolower(trim($cvalue['destination_hub_code']))]['id'] : 0;
                        if ($dest_hub_id == 0) {
                            $count = $u_key + 1;
                            $data['count'] = $count;
                            $data['error'] =  'DESTINATION HUB CODE ' . $cvalue['destination_hub_code'] . ' NOT FOUND at row ' . $count . '';
                            $non_inserted_data[] = $data;
                            continue;
                        }
                        // if ($dest_hub_id == 0) {
                        //     $dest_hub_id = check_record_exist(array('name' => $cvalue['destination_hub_code']), 1, 'hub', 'code');
                        //     $all_hub[strtolower(trim($cvalue['destination_hub_code']))]['id'] = $dest_hub_id;
                        // }
                    }

                    if (isset($cvalue['origin_hub_code']) && $cvalue['origin_hub_code'] != '') {
                        $ori_hub_id = isset($cvalue['origin_hub_code']) && isset($all_hub[strtolower(trim($cvalue['origin_hub_code']))]) ? $all_hub[strtolower(trim($cvalue['origin_hub_code']))]['id'] : 0;
                        if ($ori_hub_id == 0) {
                            $count = $u_key + 1;
                            $data['count'] = $count;
                            $data['error'] =  'ORIGIN HUB CODE ' . $cvalue['origin_hub_code'] . ' NOT FOUND at row ' . $count . '';
                            $non_inserted_data[] = $data;
                            continue;
                        }
                        // if ($ori_hub_id == 0) {
                        //     $ori_hub_id = check_record_exist(array('name' => $cvalue['origin_hub_code']), 1, 'hub', 'code');
                        //     $all_hub[strtolower(trim($cvalue['origin_hub_code']))]['id'] = $ori_hub_id;
                        // }
                    }

                    $insert_data = array(
                        'booking_min' => '',
                        'booking_max' => $booking_max,
                        'manifest_no' => isset($cvalue['manifest_no']) ? $cvalue['manifest_no'] : '',
                        'forwarder_id' => $forwarder_id,
                        'vendor_id' => $vendor_id,
                        'co_vendor_id' => $co_vendor_id,
                        'vendor_delivery' => isset($cvalue['vendor_delivery_code']) ? $cvalue['vendor_delivery_code'] : '',
                        'master_no' => $cvalue['master_awb_part_1'] . ' ' . $cvalue['master_awb_part_2'],
                        'master_edi_no' => isset($cvalue['master_edi_bag_no']) ? $cvalue['master_edi_bag_no'] : '',
                        'manifest_date' => $manifest_date,
                        'run_number' => isset($cvalue['run_number']) ? $cvalue['run_number'] : '',
                        'flight_no' => isset($cvalue['flight_no']) ? $cvalue['flight_no']  : '',
                        'vendor_cd_no' => isset($cvalue['vendor_cd_no']) ? $cvalue['vendor_cd_no'] : '',
                        'bags_count' => isset($cvalue['no_of_bags']) ? $cvalue['no_of_bags'] : '',
                        'arrival_date' => $arrival_date,
                        'vendor_wt' => isset($cvalue['vendor_weight']) ? $cvalue['vendor_weight'] : '',
                        'ori_hub_id' => $ori_hub_id,
                        'dest_hub_id' => $dest_hub_id,
                        'vehicle_no' => isset($cvalue['vehical_no']) ? $cvalue['vehical_no']  : '',
                        'line_haul_vendor' => isset($cvalue['line_haul_vendor']) ? $cvalue['line_haul_vendor'] : '',
                        'created_date' => isset($cvalue['created_at']) ? $cvalue['created_at'] : date('Y-m-d H:i:s'),
                        'created_by' => $this->user_id,
                        'migration_id' => isset($cvalue['id']) ? $cvalue['id'] : 0
                    );
                    $recordExist = array();
                    if ((int)$cvalue['id'] > 0) {
                        $recordExist = $this->gm->get_selected_record('manifest', 'id', array('migration_id' => $cvalue['id']), array());
                    }
                    if (isset($recordExist) && is_array($recordExist) && count($recordExist) > 0) {
                        $this->gm->update('manifest', $insert_data, '', array('id' => $recordExist['id']));
                        $data['error'] = 'MANIFEST UPDATED SUCCESSFULLY on row ' . $count . '';
                        $non_inserted_data[] = $data;
                    } else {
                        $this->gm->insert('manifest', $insert_data);
                    }
                }
                if (isset($non_inserted_data) && is_array($non_inserted_data) && count($non_inserted_data) > 0) {
                    $data['insert_count'] = $insert_count;
                    $data['non_inserted_data'] = $non_inserted_data;
                    $data['total_csv_rec_count'] = isset($csvData) && is_array($csvData) ? count($csvData) : 0;
                    $this->_display('upload_success_list', $data);
                } else {
                    $this->session->set_flashdata('add_feedback', 'manifest imported successfully.');
                    redirect(site_url('manifest/show_list'));
                }
            } else {
                $this->session->set_flashdata('add_feedback', 'No Data present in file');
                redirect(site_url('mirgation/import_data/show_form/manifest'));
            }
        } else {
            $this->session->set_flashdata('add_feedback', 'Failed to upload file');
            redirect(site_url('mirgation/import_data/show_form/manifest'));
        }
    }
}
