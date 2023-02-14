<?php
class Customer_contract extends MX_Controller
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
                $all_cust = get_all_customer(" AND status IN(1,2) ", "code");
                $all_vendor = get_all_vendor(" AND status IN(1,2) ", "code");
                $all_pdt = get_all_product(" AND status IN(1,2) ", "code");
                $all_co_vendor = get_all_co_vendor(" AND status IN(1,2) ", "code");
                $all_location = get_all_location(" AND status IN(1,2) ", "code");
                $all_zone = get_all_zone(" AND status IN(1,2) ", "code");
                $all_city = get_all_city(" AND status IN(1,2) ", "code");

                foreach ($csvData as $u_key => $cvalue) {
                    $customer_id = 0;
                    $vendor_id = 0;
                    $product_id = 0;
                    $co_vendor_id = 0;
                    $dest_zone_id = 0;
                    $ori_zone_id = 0;
                    $dest_city_id = 0;
                    $ori_city_id = 0;
                    $dest_location_id = 0;
                    $ori_location_id = 0;
                    $effective_min = '';
                    $effective_max = '';
                    $onadd_arr = array();
                    $lower_weight_arr = array();
                    $upper_weight_arr = array();
                    $rate_arr = array();

                    $count = $u_key + 1;
                    $data['count'] = $count;
                    if (!isset($cvalue['customer_code']) || $cvalue['customer_code'] == '') {
                        $data['error'] = 'CUSTOMER NOT FOUND on row ' . $count . '';
                        $non_inserted_data[] = $data;
                        continue;
                    } else if (!isset($cvalue['product_code']) || $cvalue['product_code'] == '') {
                        $data['error'] = 'PRODUCT NOT FOUND on row ' . $count . '';
                        $non_inserted_data[] = $data;
                        continue;
                    } else {
                        $customer_id = isset($cvalue['customer_code']) && isset($all_cust[strtolower(trim($cvalue['customer_code']))]) ? $all_cust[strtolower(trim($cvalue['customer_code']))]['id'] : 0;
                        // if ($customer_id == 0) {
                        //     $customer_id = check_record_exist(array('name' => $cvalue['customer_code']), 1, 'customer', 'code');
                        //     $all_cust[strtolower(trim($cvalue['customer_code']))]['id'] = $customer_id;
                        // }

                        if ($cvalue['vendor_code'] != '') {
                            $vendor_id = isset($cvalue['vendor_code']) && isset($all_vendor[strtolower(trim($cvalue['vendor_code']))]) ? $all_vendor[strtolower(trim($cvalue['vendor_code']))]['id'] : 0;
                            // if ($vendor_id == 0) {
                            //     $vendor_id = check_record_exist(array('name' => $cvalue['vendor_code']), 1, 'vendor', 'code');
                            //     $all_vendor[strtolower(trim($cvalue['vendor_code']))]['id'] = $vendor_id;
                            // }
                        }
                        $product_id = isset($cvalue['product_code']) && isset($all_pdt[strtolower(trim($cvalue['product_code']))]) ? $all_pdt[strtolower(trim($cvalue['product_code']))]['id'] : 0;
                        // if ($product_id == 0) {
                        //     $product_id = check_record_exist(array('name' => $cvalue['product_code']), 1, 'product', 'code');
                        //     $all_pdt[strtolower(trim($cvalue['product_code']))]['id'] = $product_id;
                        // }
                        if (isset($cvalue['co_vendor_code']) && $cvalue['co_vendor_code'] != '') {
                            $co_vendor_id = isset($cvalue['co_vendor_code']) && isset($all_co_vendor[strtolower(trim($cvalue['co_vendor_code']))]) ? $all_co_vendor[strtolower(trim($cvalue['co_vendor_code']))]['id'] : 0;
                            if ($co_vendor_id == 0) {
                                // $co_vendor_id = check_record_exist(array('name' => $cvalue['co_vendor_code']), 1, 'co_vendor', 'code');
                                // $all_co_vendor[strtolower(trim($cvalue['vendor_code']))]['id'] = $co_vendor_id;
                            }
                        }

                        if (isset($cvalue['zone_code']) && $cvalue['zone_code'] != '') {
                            $dest_zone_id = isset($cvalue['zone_code']) && isset($all_zone[strtolower(trim($cvalue['zone_code']))]) ? $all_zone[strtolower(trim($cvalue['zone_code']))]['id'] : 0;
                            // if ($dest_zone_id == 0) {
                            //     $dest_zone_id = check_record_exist(array('name' => $cvalue['zone_code']), 1, 'zone', 'code');
                            //     $all_zone[strtolower(trim($cvalue['zone_code']))]['id'] = $dest_zone_id;
                            // }
                        }

                        if (isset($cvalue['origin_zone_code']) && $cvalue['origin_zone_code'] != '') {
                            $ori_zone_id = isset($cvalue['origin_zone_code']) && isset($all_zone[strtolower(trim($cvalue['origin_zone_code']))]) ? $all_zone[strtolower(trim($cvalue['origin_zone_code']))]['id'] : 0;
                            // if ($ori_zone_id == 0) {
                            //     $ori_zone_id = check_record_exist(array('name' => $cvalue['origin_zone_code']), 1, 'zone', 'code');
                            //     $all_zone[strtolower(trim($cvalue['origin_zone_code']))]['id'] = $ori_zone_id;
                            // }
                        }

                        if (isset($cvalue['destination_city']) && $cvalue['destination_city'] != '') {
                            $dest_city_id = isset($cvalue['destination_city']) && isset($all_city[strtolower(trim($cvalue['destination_city']))]) ? $all_city[strtolower(trim($cvalue['destination_city']))]['id'] : 0;
                            // if ($dest_city_id == 0) {
                            //     $dest_city_id = check_record_exist(array('name' => $cvalue['destination_city']), 1, 'city', 'code');
                            //     $all_city[strtolower(trim($cvalue['destination_city']))]['id'] = $dest_city_id;
                            // }
                        }

                        if (isset($cvalue['origin_city']) && $cvalue['origin_city'] != '') {
                            $ori_city_id = isset($cvalue['origin_city']) && isset($all_city[strtolower(trim($cvalue['origin_city']))]) ? $all_city[strtolower(trim($cvalue['origin_city']))]['id'] : 0;
                            // if ($ori_city_id == 0) {
                            //     $ori_city_id = check_record_exist(array('name' => $cvalue['origin_city']), 1, 'city', 'code');
                            //     $all_city[strtolower(trim($cvalue['origin_city']))]['id'] = $ori_city_id;
                            // }
                        }
                        if (isset($cvalue['destination_code']) && $cvalue['destination_code'] != '') {
                            $dest_location_id = isset($cvalue['destination_code']) && isset($all_location[strtolower(trim($cvalue['destination_code']))]) ? $all_location[strtolower(trim($cvalue['destination_code']))]['id'] : 0;
                            // if ($dest_location_id == 0) {
                            //     $dest_location_id = check_record_exist(array('name' => $cvalue['destination_code']), 1, 'location', 'code');
                            //     $all_location[strtolower(trim($cvalue['destination_code']))]['id'] = $dest_location_id;
                            // }
                        }

                        if (isset($cvalue['origin_code']) && $cvalue['origin_code'] != '') {
                            $ori_location_id = isset($cvalue['origin_code']) && isset($all_location[strtolower(trim($cvalue['origin_code']))]) ? $all_location[strtolower(trim($cvalue['origin_code']))]['id'] : 0;
                            // if ($ori_location_id == 0) {
                            //     $ori_location_id = check_record_exist(array('name' => $cvalue['origin_code']), 1, 'location', 'code');
                            //     $all_location[strtolower(trim($cvalue['origin_code']))]['id'] = $ori_location_id;
                            // }
                        }

                        if (isset($cvalue['effective_from']) && $cvalue['effective_from'] != '') {
                            $effective_min = $cvalue['effective_from'] != '' ? format_date($cvalue['effective_from']) : '';
                        }
                        if (isset($cvalue['effective_till']) && $cvalue['effective_till'] != '') {
                            $effective_max = $cvalue['effective_till'] != '' ? format_date($cvalue['effective_till']) : '';
                        }
                        $insert_data = array(
                            'customer_id' => $customer_id,
                            'effective_min' => $effective_min,
                            'effective_max' => $effective_max,
                            'vendor_id' => $vendor_id,
                            'co_vendor_id' => $co_vendor_id,
                            'ori_location_id' => $ori_location_id,
                            'ori_zone_id' => $ori_zone_id,
                            'ori_city_id' => $ori_city_id,
                            'tat' => isset($cvalue['tat']) ? $cvalue['tat'] : '', //NOT COMING IN EXPORT
                            'remark' => isset($cvalue['remarks']) ? $cvalue['remarks'] : '',
                            'dest_zone_id' => $dest_zone_id,
                            'dest_city_id' => $dest_city_id,
                            'dest_location_id' => $dest_location_id,
                            'product_id' => $product_id,
                            'method_id' => isset($cvalue['calculation_method']) && $cvalue['calculation_method'] == 'slabwise' ? 2 : 1,
                            'created_date' => isset($cvalue['created_at']) ? $cvalue['created_at'] : date('Y-m-d H:i:s'),
                            'created_by' => $this->user_id,
                            'migration_id' => isset($cvalue['id']) ? $cvalue['id'] : 0
                        );
                        $recordExist = array();
                        if ((int)$cvalue['id'] > 0) {
                            $recordExist = $this->gm->get_selected_record('customer_contract', 'id', array('migration_id' => $cvalue['id']), array());
                        }
                        if (isset($recordExist) && is_array($recordExist) && count($recordExist) > 0) {
                            $this->gm->update('customer_contract', $insert_data, '', array('id' => $recordExist['id']));
                            $data['error'] = 'CONTRACT UPDATED SUCCESSFULLY on row ' . $count . '';
                            $non_inserted_data[] = $data;
                            $contract_id = $recordExist['id'];
                        } else {
                            $contract_id = $this->gm->insert('customer_contract', $insert_data);
                        }

                        if (isset($cvalue['on_addition']) && $cvalue['on_addition'] != '') {
                            $onadd_arr = explode(",", $cvalue['on_addition']);
                        }
                        if (isset($cvalue['lower_weight']) && $cvalue['lower_weight'] != '') {
                            $lower_weight_arr = explode(",", $cvalue['lower_weight']);
                        }
                        if (isset($cvalue['upper_weight']) && $cvalue['upper_weight'] != '') {
                            $upper_weight_arr = explode(",", $cvalue['upper_weight']);
                        }
                        if (isset($cvalue['rate']) && $cvalue['rate'] != '') {
                            $rate_arr = explode(",", $cvalue['rate']);
                        }
                        if (isset($onadd_arr) && is_array($onadd_arr) && count($onadd_arr) > 0) {
                            $deleteq = "DELETE FROM customer_contract_rate WHERE customer_contract_id='" . $contract_id . "'";
                            $this->db->query($deleteq);

                            foreach ($onadd_arr as $rate_key => $rate_value) {
                                $rate_insert = array(
                                    'customer_contract_id' => $contract_id,
                                    'on_add' => $rate_value,
                                    'lower_wt' => isset($lower_weight_arr[$rate_key]) ? $lower_weight_arr[$rate_key] : 0,
                                    'upper_wt' => isset($upper_weight_arr[$rate_key]) ? $upper_weight_arr[$rate_key] : 0,
                                    'rate' => isset($rate_arr[$rate_key]) ? $rate_arr[$rate_key] : 0,
                                    'created_by' => $this->user_id,
                                    'created_date' => date('Y-m-d H:i:s')
                                );
                                $this->gm->insert('customer_contract_rate', $rate_insert);
                            }
                        }
                        $insert_count++;
                    }
                }

                if (isset($non_inserted_data) && is_array($non_inserted_data) && count($non_inserted_data) > 0) {
                    $data['insert_count'] = $insert_count;
                    $data['non_inserted_data'] = $non_inserted_data;
                    $data['total_csv_rec_count'] = isset($csvData) && is_array($csvData) ? count($csvData) : 0;

                    $this->_display('upload_success_list', $data);
                } else {
                    $this->session->set_flashdata('add_feedback', 'Customer Contract imported successfully.');
                    redirect(site_url('customer_contracts/show_list'));
                }
            } else {
                $this->session->set_flashdata('add_feedback', 'No Data present in file');
                redirect(site_url('mirgation/import_data/show_form/customer_contract'));
            }
        } else {
            $this->session->set_flashdata('add_feedback', 'Failed to upload file');
            redirect(site_url('mirgation/import_data/show_form/customer_contract'));
        }
    }
}
