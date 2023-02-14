<?php
class Invoice extends MX_Controller
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
                $all_customer = get_all_customer(" AND status IN(1,2) ", "code");
                $all_billing_company = get_all_billing_company(" AND status IN(1,2) ", "code");
                foreach ($csvData as $u_key => $cvalue) {
                    $count = $u_key + 1;
                    $data['count'] = $count;

                    $from_date = '';
                    $invoice_date = '';
                    $customer_id = 0;
                    $billing_company_id = 0;
                    $till_date = '';
                    $due_date = '';
                    if (isset($cvalue['from_date']) && $cvalue['from_date'] != '') {
                        $from_date = format_date($cvalue['from_date']);
                    }
                    if (isset($cvalue['till_date']) && $cvalue['till_date'] != '') {
                        $till_date = format_date($cvalue['till_date']);
                    }
                    if (isset($cvalue['invoice_date']) && $cvalue['invoice_date'] != '') {
                        $invoice_date = format_date($cvalue['invoice_date']);
                    }
                    if (isset($cvalue['due_date']) && $cvalue['due_date'] != '') {
                        $due_date = format_date($cvalue['due_date']);
                    }
                    if (isset($cvalue['customer_code']) && $cvalue['customer_code'] != '') {
                        $customer_id = isset($cvalue['customer_code']) && isset($all_customer[strtolower(trim($cvalue['customer_code']))]) ? $all_customer[strtolower(trim($cvalue['customer_code']))]['id'] : 0;
                        // if ($customer_id == 0) {
                        //     $customer_id = check_record_exist(array('name' => $cvalue['customer_code']), 1, 'customer', 'code');
                        //     $all_customer[strtolower(trim($cvalue['customer_code']))]['id'] = $customer_id;
                        // }
                    }
                    if (isset($cvalue['billing_company']) && $cvalue['billing_company'] != '') {
                        $billing_company_id = isset($cvalue['billing_company']) && isset($all_billing_company[strtolower(trim($cvalue['billing_company']))]) ? $all_billing_company[strtolower(trim($cvalue['billing_company']))]['id'] : 0;
                        // if ($billing_company_id == 0) {
                        //     $billing_company_id = check_record_exist(array('name' => $cvalue['billing_company']), 1, 'company_master', 'name');
                        //     $all_billing_company[strtolower(trim($cvalue['billing_company']))]['id'] = $billing_company_id;
                        // }
                    }


                    $insert_data = array(
                        'customer_id' => $customer_id,
                        'company_master_id' => $billing_company_id,
                        'invoice_range_id' => '',
                        'from_date' => $from_date,
                        'to_date' => $till_date,
                        'invoice_no' => isset($cvalue['invoice_number']) ? $cvalue['invoice_number'] : '',
                        'invoice_date' => $invoice_date,
                        'due_date' => $due_date,
                        'customer_note' => isset($cvalue['note']) ? $cvalue['note'] : '',
                        'po_number' => isset($cvalue['po_number']) ? $cvalue['po_number'] : '',
                        'created_date' => isset($cvalue['created_at']) ? $cvalue['created_at'] : date('Y-m-d H:i:s'),
                        'created_by' => $this->user_id,
                        'migration_id' => isset($cvalue['id']) ? $cvalue['id'] : 0,
                        'invoice_type' => 1, //CUSTOMER WISE
                        'grand_total' => isset($cvalue['grand_total']) ? $cvalue['grand_total'] : '',
                        'payment_received' => $cvalue['status'] == 'unpaid' ? 2 : 1
                    );


                    $recordExist = array();
                    if ((int)$cvalue['id'] > 0) {
                        $recordExist = $this->gm->get_selected_record('docket_invoice', 'id', array('migration_id' => $cvalue['id']), array());
                    }
                    if (isset($recordExist) && is_array($recordExist) && count($recordExist) > 0) {
                        $this->gm->update('docket_invoice', $insert_data, '', array('id' => $recordExist['id']));
                        $data['error'] = 'INVOICE UPDATED SUCCESSFULLY on row ' . $count . '';
                        $non_inserted_data[] = $data;
                    } else {
                        $this->gm->insert('docket_invoice', $insert_data);
                    }
                }

                if (isset($non_inserted_data) && is_array($non_inserted_data) && count($non_inserted_data) > 0) {
                    $data['insert_count'] = $insert_count;
                    $data['non_inserted_data'] = $non_inserted_data;
                    $data['total_csv_rec_count'] = isset($csvData) && is_array($csvData) ? count($csvData) : 0;
                    $this->_display('upload_success_list', $data);
                } else {
                    $this->session->set_flashdata('add_feedback', 'INVOICE imported successfully.');
                    redirect(site_url('invoice/show_list'));
                }
            } else {
                $this->session->set_flashdata('add_feedback', 'No Data present in file');
                redirect(site_url('mirgation/import_data/show_form/invoice'));
            }
        } else {
            $this->session->set_flashdata('add_feedback', 'Failed to upload file');
            redirect(site_url('mirgation/import_data/show_form/invoice'));
        }
    }
}
