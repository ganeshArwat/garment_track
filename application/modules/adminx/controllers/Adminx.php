<?php

class Adminx extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->module('login/admin_login');

        $login = new Admin_login();
        $check_login = $login->_is_logged_in();
        $check_login = true;

        if (!($check_login)) {
            $this->session->set_userdata('url_page', $url_page);
            $this->session->set_userdata('login_page', 'backend');
            $this->load->helper('url');
            redirect(site_url('login/admin_login'));
        }
    }

    function login_redirect()
    {
        $this->load->helper('url');
        $this->load->helper('database_manage');
        $this->load->model('Global_model', 'gm');
        $this->load->helper('create_table');
        $session_data = $this->session->userdata('admin_user');
        //check database and all table for login user company present or not
        $company_id = isset($session_data['com_id']) ? $session_data['com_id'] : '';

        if ($company_id > 1 && isset($session_data['is_restrict']) && $session_data['is_restrict'] == 2) {
            if (create_company_database($company_id)) {
                create_all_table();

                //RUN TABLE COLUMN MIGRATION SCRIPT
                $this->load->module('database_migration/column_script');
                $this->column_script->check_table_structure();

                $this->load->module('database_migration/optimize_table');
                $this->optimize_table->run_qry();
            }
        }


        $insert_log = array(
            'login_date' => date('Y-m-d H:i:s'),
            'user_id' => $session_data['id'],
            'user_type' => $session_data['type'] == 'software_user' ? 1 : 2,
            'ip_address' => $_SERVER['REMOTE_ADDR']
        );
        $this->gm->insert('login_log', $insert_log);


        redirect('adminx');
    }
    public function index()
    {
        $this->load->helper('url');
        $this->load->helper('database_manage');
        $this->load->helper('create_table');
        $session_data = $this->session->userdata('admin_user');
        //check database and all table for login user company present or not
        $company_id = isset($session_data['com_id']) ? $session_data['com_id'] : '';

        $data = array();

        $session_data = $this->session->userdata('admin_user');
        if ($session_data['type'] == 'customer') {
            $this->show_customer_dashboard();
        } else if ($session_data['type'] == 'vendor') {
            redirect('incoming_manifests/show_list');
        } else {
            if ($session_data['is_restrict'] == 2) {
                $data = $this->show_company_dashboard();
            } else {
                $data = array();
            }

            $this->_display('dashboard', $data);
        }

        //redirect('page_management/dashboard');
    }

    public function show_form()
    {
        $sessiondata = $this->session->userdata('admin_user');
        $customer_id = $sessiondata['customer_id'];

        if ($sessiondata['type'] == 'customer') {
            //CHEKC TO SHOW KYC PAGE
            $query = "SELECT c.id FROM customer c 
        JOIN module_setting m ON(c.id=m.module_id AND m.module_type=1 AND m.status IN(1,2))
        WHERE  m.status IN(1,2) AND  m.config_key='show_kyc_after_login'
        AND  m.config_value='1' AND c.id='" . $customer_id . "'";

            $qry_exe = $this->db->query($query);
            $show_kyc = $qry_exe->result_array();
            if (isset($show_kyc) && is_array($show_kyc) && count($show_kyc) > 0) {
                redirect('customer_masters/show_kyc');
            } else {
                $this->show_customer_dashboard();
            }
        }
    }

    public function show_company_dashboard()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');
        $this->load->helper('pagination');

        $sessiondata = $this->session->userdata('admin_user');

        $query = "SELECT COUNT(r.id) as status_total FROM refresh_log r 
        JOIN docket d ON(d.id=r.module_id AND r.module_type=1)
         WHERE d.status IN(1,2) AND r.status IN(1,2) AND r.refresh_type='refresh_awb' AND r.refresh_status=1";
        $query_exe = $this->db->query($query);
        $status_res = $query_exe->row_array();
        $data['refresh_awb_cnt'] = isset($status_res['status_total']) ? $status_res['status_total'] : 0;

        $query = "SELECT COUNT(r.id) as status_total FROM refresh_log r 
            JOIN docket d ON(d.id=r.module_id AND r.module_type=1)
             WHERE d.status IN(1,2) AND r.status IN(1,2) AND r.refresh_type='refresh_sales_billing' AND r.refresh_status=1";
        $query_exe = $this->db->query($query);
        $status_res = $query_exe->row_array();
        $data['refresh_sales_cnt'] = isset($status_res['status_total']) ? $status_res['status_total'] : 0;

        $query = "SELECT COUNT(r.id) as status_total FROM refresh_log r 
                JOIN docket d ON(d.id=r.module_id AND r.module_type=1)
                 WHERE d.status IN(1,2) AND r.status IN(1,2) AND r.refresh_type='refresh_purchase_billing' AND r.refresh_status=1";
        $query_exe = $this->db->query($query);
        $status_res = $query_exe->row_array();
        $data['refresh_purchase_cnt'] = isset($status_res['status_total']) ? $status_res['status_total'] : 0;

        $query = "SELECT COUNT(r.id) as status_total FROM refresh_log r 
                    JOIN docket d ON(d.id=r.module_id AND r.module_type=1)
                     WHERE d.status IN(1,2) AND r.status IN(1,2) AND r.refresh_type='refresh_delivery' AND r.refresh_status=1";
        $query_exe = $this->db->query($query);
        $status_res = $query_exe->row_array();
        $data['refresh_delivery_cnt'] = isset($status_res['status_total']) ? $status_res['status_total'] : 0;

        $query = "SELECT COUNT(r.id) as status_total FROM email_queue r 
         WHERE r.status IN(1,2) AND r.send_status=1";
        $query_exe = $this->db->query($query);
        $status_res = $query_exe->row_array();
        $data['email_cnt'] = isset($status_res['status_total']) ? $status_res['status_total'] : 0;

        $setting = get_all_app_setting(" AND module_name IN('general','dashboard','account')");
        $data['setting'] = $setting;

        //  NOTIFICATION TRACKING
        if ($_GET['days'] == 1 || !isset($_GET['days'])) {
            $triggger_date =  date('Y-m-d');
            $qry = "SELECT COUNT(id) as count ,mail_type FROM `mail_tracking` WHERE `trigger_date` LIKE '%" . $triggger_date . "%' GROUP BY mail_type;";
        } else if ($_GET['days'] == 2) {
            $y_date = date('d.m.Y', strtotime("-1 days"));
            $triggger_date = get_format_date("Y-m-d", $y_date);
            $qry = "SELECT COUNT(id) as count ,mail_type FROM `mail_tracking` WHERE `trigger_date` LIKE '%" . $triggger_date . "%' GROUP BY mail_type;";
        } else if ($_GET['days'] == 7) {
            $start_date = date('Y-m-d', strtotime("-7 days")) . " 00:00:00";
            $end_date = date('Y-m-d') . " 23:59:59";
            $qry = "SELECT COUNT(id) as count ,mail_type FROM `mail_tracking` WHERE `trigger_date` >= '" . $start_date . "'  AND `trigger_date` <= '" . $end_date . "' GROUP BY mail_type;";
        } else if ($_GET['days'] == "month") {
            $start_date = date('Y-m-d', strtotime("-1 months")) . " 00:00:00";
            $end_date = date('Y-m-d') . " 23:59:59";
            $qry = "SELECT COUNT(id) as count ,mail_type FROM `mail_tracking` WHERE `trigger_date` >= '" . $start_date . "'  AND `trigger_date` <= '" . $end_date . "' GROUP BY mail_type;";
        } else if ($_GET['days'] == "last_3_month") {
            $start_date = date('Y-m-d', strtotime("-3 months")) . " 00:00:00";
            $end_date = date('Y-m-d') . " 23:59:59";
            $qry = "SELECT COUNT(id) as count ,mail_type FROM `mail_tracking` WHERE `trigger_date` >= '" . $start_date . "'  AND `trigger_date` <= '" . $end_date . "' GROUP BY mail_type;";
        } else if ($_GET['days'] == "till_date") {
            $triggger_date =  get_format_date("Y-m-d", date('Y-m-d')) . " 23:59:59";
            $qry = "SELECT COUNT(id) as count ,mail_type FROM `mail_tracking` WHERE `trigger_date` <= '" . $triggger_date . "' GROUP BY mail_type;";
        } else {
            $triggger_date =  date('Y-m-d');
            $qry = "SELECT COUNT(id) as count ,mail_type FROM `mail_tracking` WHERE `trigger_date` LIKE '%" . $triggger_date . "%' GROUP BY mail_type;";
        }

        $qry_exe = $this->db->query($qry);
        $daily_mail_track = $qry_exe->result_array();

        $daily_mail_count  = array();
        foreach ($daily_mail_track as $key => $value) {
            $daily_mail_count[$value['mail_type']] =  $value['count'];
        }

        $data['daily_mail_count'] = $daily_mail_count;

        //shipments which are inscaned but not manifested
        if (isset($setting['show_unmanifest_on_dashboard']) && $setting['show_unmanifest_on_dashboard'] == 1) {

            $page = $this->uri->segment(2);
            $offset = page_offset($page);
            $limitQ = " LIMIT " . PER_PAGE . " OFFSET " . $offset;

            $hub_id_arr = get_user_all_hub($sessiondata['id']);
            $query = "SELECT d.id,d.customer_id,d.vendor_id,p.inscan_date,p.parcel_no
            FROM docket d JOIN docket_items p ON(d.id=p.docket_id)
            WHERE d.status IN(1,2) AND p.status IN(1,2) AND p.is_inscan=1 AND d.id NOT IN(
                SELECT md.docket_id FROM manifest m JOIN manifest_docket md ON(m.id=md.manifest_id)
                WHERE m.status IN(1,2) AND md.status IN(1,2)
            )";

            if (isset($hub_id_arr) && is_array($hub_id_arr) && count($hub_id_arr) > 0) {
                $query .= " AND d.ori_hub_id IN(" . implode(",", $hub_id_arr) . ")";
            }
            $query .=  " ORDER BY d.booking_date" . $limitQ;

            $query_exec = $this->db->query($query);
            $data['result'] = $query_exec->result_array();
            if (isset($data['result']) && is_array($data['result']) && count($data['result']) > 0) {
                foreach ($data['result'] as $rkey => $rvalue) {
                    $all_cust[$rvalue['customer_id']] = $rvalue['customer_id'];
                    $all_vendor[$rvalue['vendor_id']] = $rvalue['vendor_id'];
                }
            }

            if (isset($all_cust) && is_array($all_cust) && count($all_cust) > 0) {
                $data['all_customer'] = get_all_customer(" AND id IN(" . implode(",", $all_cust) . ")");
            }

            if (isset($all_vendor) && is_array($all_vendor) && count($all_vendor) > 0) {
                $data['all_vendor'] = get_all_vendor(" AND id IN(" . implode(",", $all_vendor) . ")");
            }

            $cquery = "SELECT COUNT(d.id) as id
            FROM docket d JOIN docket_items p ON(d.id=p.docket_id)
            WHERE d.status IN(1,2) AND p.status IN(1,2) AND p.is_inscan=1 AND d.id NOT IN(
                SELECT md.docket_id FROM manifest m JOIN manifest_docket md ON(m.id=md.manifest_id)
                WHERE m.status IN(1,2) AND md.status IN(1,2)
            )";

            if (isset($hub_id_arr) && is_array($hub_id_arr) && count($hub_id_arr) > 0) {
                $cquery .= " AND d.ori_hub_id IN(" . implode(",", $hub_id_arr) . ")";
            }
            $cquery_exec = $this->db->query($cquery);
            $count = $cquery_exec->row_array();
            $data['total'] = isset($count['id']) ? $count['id'] : 0;

            if (isset($data['setting']['show_bank_balance_report']) && $data['setting']['show_bank_balance_report'] == 1) {
                $this->load->module('account/bank_ledger');
                //GET BANK BALANCE
                $qry = "SELECT c.id,cb.id,cb.account_name,cb.account_no,cb.available_balance,c.country,cb.closing_balance 
                FROM company_master c
                JOIN company_bank cb ON(c.id=cb.company_master_id)
                WHERE c.status IN(1,2) AND cb.status IN(1,2)";
                $qry_exe = $this->db->query($qry);
                $data['bank_balance'] = $qry_exe->result_array();
                $all_currency = get_all_currency();
                if (isset($data['bank_balance']) && is_array($data['bank_balance']) && count($data['bank_balance']) > 0) {
                    foreach ($data['bank_balance'] as $key => $value) {
                        if ($value['country'] != '') {
                            $qry = "SELECT id,currency_code_id FROM country WHERE status=1 AND name='" . $value['country'] . "'";
                            $query_exe = $this->db->query($qry);
                            $country_currency = $query_exe->row_array();

                            $from_currency_code = isset($all_currency[$country_currency['currency_code_id']]['code']) && $all_currency[$country_currency['currency_code_id']]['code'] != '' ? strtoupper($all_currency[$country_currency['currency_code_id']]['code']) : 'INR';
                            $data['bank_balance'][$key]['currency_code'] = $from_currency_code;

                            if (isset($data['currency_balance'][$from_currency_code])) {
                                $data['currency_balance'][$from_currency_code] += $value['available_balance'];
                            } else {
                                $data['currency_balance'][$from_currency_code] = $value['available_balance'];
                            }
                        }

                        $filter_data = array(
                            'account_no_id' => $value['id'],
                            'closing_date' => date('Y-m-d'),
                            'mode' => 'script',
                        );

                        $data['bank_balance'][$key]['bank_balance_amt'] =  $this->bank_ledger->export_data($filter_data);
                    }
                }
            }

            if (isset($data['setting']['show_run_performance_report']) && $data['setting']['show_run_performance_report'] == 1) {
                $manifest_append = "";
                $till_date = 2;

                $manifest_start = date('Y-m-d');
                $manifest_end = date('Y-m-d');
                $week_date = date('Y-m-d');
                if (isset($_GET['days']) && $_GET['days'] == 2) {
                    //YESTERDAY
                    $manifest_start = date('Y-m-d', (strtotime('-1 day', strtotime(date('Y-m-d')))));
                    $manifest_end = date('Y-m-d', (strtotime('-1 day', strtotime(date('Y-m-d')))));
                } else if (isset($_GET['days']) && $_GET['days'] == 7) {
                    //This Week

                    //IF TODAY IS MONDAY GET NEXT SUNDAY DATE ELSE GET PREVIOS SUNDAY DATE
                    $week_date = date('Y-m-d');
                    $week_day = date('N', strtotime($week_date));
                    if ($week_day == 1) {
                        $manifest_start = $week_date;
                        $manifest_end = date("Y-m-d", strtotime("next Sunday", strtotime($manifest_start)));
                    } else {
                        $manifest_end = $week_date;
                        $manifest_start = date("Y-m-d", strtotime("previous Monday", strtotime($manifest_end)));
                    }
                } else if (isset($_GET['days']) && $_GET['days'] == 'month') {
                    //This Month
                    $manifest_start = date('Y-m-1');
                    $manifest_end = date('Y-m-t');
                } else if (isset($_GET['days']) && $_GET['days'] == 'last_3_month') {
                    //Last 3 Month
                    $manifest_start = date('Y-m-01', (strtotime('-2 month', strtotime(date('Y-m-d')))));
                    $manifest_end = date('Y-m-t');
                } else if (isset($_GET['from_date']) && isset($_GET['to_date'])) {
                    //FILTER
                    $manifest_start = $_GET['from_date'];
                    $manifest_end = $_GET['to_date'];
                } else if (isset($_GET['days']) && $_GET['days'] == 'till_date') {
                    //till_date
                    $till_date = 1;
                }

                if ($till_date == 2) {
                    $manifest_append = " AND m.manifest_date >='" . $manifest_start . "' AND 
                    manifest_date <='" . $manifest_end . "'";
                }
                //GET MANIFEST DATA
                $qry = "SELECT m.* FROM manifest m 
            WHERE m.status IN(1,2) " . $manifest_append;
                $qry_exe = $this->db->query($qry);
                $data['manifest_data'] = $qry_exe->result_array();
                if (isset($data['manifest_data']) && is_array($data['manifest_data']) && count($data['manifest_data']) > 0) {
                    foreach ($data['manifest_data'] as $key => $value) {
                        if (isset($data['manifest_sales_total'])) {
                            $data['manifest_sales_total'] += $value['sales_total'];
                        } else {
                            $data['manifest_sales_total'] = $value['sales_total'];
                        }

                        if (isset($data['manifest_purchase_total'])) {
                            $data['manifest_purchase_total'] += $value['purchase_total'];
                        } else {
                            $data['manifest_purchase_total'] = $value['purchase_total'];
                        }
                    }
                }
                $data['all_hub'] = get_all_hub();
            }

            if ((isset($setting['show_company_outstanding_report']) && $setting['show_company_outstanding_report'] == 1) ||
                isset($setting['show_customer_outstanding_report']) && $setting['show_customer_outstanding_report'] == 1
            ) {
                //GET OUTSTANDING DATA
                $qry = "SELECT id,company_master_id FROM payment_receipt WHERE status IN(1,2)";
                $qry_exe = $this->db->query($qry);
                $receipt_res = $qry_exe->result_array();
                if (isset($receipt_res) && is_array($receipt_res) && count($receipt_res) > 0) {
                    foreach ($receipt_res as $rkey => $rvalue) {
                        $all_receipt[$rvalue['id']] = $rvalue['company_master_id'];
                    }
                }
                $data['all_receipt'] = isset($all_receipt) ? $all_receipt : array();

                $qry = "SELECT id,company_master_id FROM credit_debit_note WHERE status IN(1,2)";
                $qry_exe = $this->db->query($qry);
                $note_res = $qry_exe->result_array();
                if (isset($note_res) && is_array($note_res) && count($note_res) > 0) {
                    foreach ($note_res as $rkey => $rvalue) {
                        $all_note[$rvalue['id']] = $rvalue['company_master_id'];
                    }
                }
                $data['all_note'] = isset($all_note) ? $all_note : array();

                $qry = "SELECT id,company_master_id FROM opening_balance WHERE status IN(1,2)";
                $qry_exe = $this->db->query($qry);
                $opening_res = $qry_exe->result_array();
                if (isset($opening_res) && is_array($opening_res) && count($opening_res) > 0) {
                    foreach ($opening_res as $rkey => $rvalue) {
                        $all_opening[$rvalue['id']] = $rvalue['company_master_id'];
                    }
                }
                $data['all_opening'] = isset($all_opening) ? $all_opening : array();


                $ledger_date_appendquery = '';
                if (
                    isset($data['setting']['customised_unbilled_dockets_in_leder_and_outstanding_report']) && $data['setting']['customised_unbilled_dockets_in_leder_and_outstanding_report'] == 1
                    && isset($data['setting']['account_ledger_start_date']) && $data['setting']['account_ledger_start_date'] != '' && $data['setting']['account_ledger_start_date'] != '1970-01-01' && $data['setting']['account_ledger_start_date'] != '0000-00-00'
                ) {
                    $ledger_date_appendquery .= " AND l.ledger_date >='" . $data['setting']['account_ledger_start_date'] . "'";
                }

                if (isset($get['customer_id']) && $get['customer_id'] != '') {
                    $ledger_date_appendquery .= " AND l.customer_id IN(" . $get['customer_id'] . ")";
                }
                $data['all_customer'] = get_all_customer(" AND status IN(1,2)");

                $qry = "SELECT id,sector_id FROM payment_receipt WHERE status IN(1,2)";
                $qry_exe = $this->db->query($qry);
                $receipt_res = $qry_exe->result_array();
                if (isset($receipt_res) && is_array($receipt_res) && count($receipt_res) > 0) {
                    foreach ($receipt_res as $rkey => $rvalue) {
                        $all_receipt[$rvalue['id']] = $rvalue['sector_id'];
                    }
                }
                $data['all_receipt'] = isset($all_receipt) ? $all_receipt : array();

                $qry = "SELECT id,sector_id FROM credit_debit_note WHERE status IN(1,2)";
                $qry_exe = $this->db->query($qry);
                $note_res = $qry_exe->result_array();
                if (isset($note_res) && is_array($note_res) && count($note_res) > 0) {
                    foreach ($note_res as $rkey => $rvalue) {
                        $all_note[$rvalue['id']] = $rvalue['sector_id'];
                    }
                }
                $data['all_note'] = isset($all_note) ? $all_note : array();

                $qry = "SELECT id,sector_id FROM opening_balance WHERE status IN(1,2)";
                $qry_exe = $this->db->query($qry);
                $opening_res = $qry_exe->result_array();
                if (isset($opening_res) && is_array($opening_res) && count($opening_res) > 0) {
                    foreach ($opening_res as $rkey => $rvalue) {
                        $all_opening[$rvalue['id']] = $rvalue['sector_id'];
                    }
                }
                $data['all_opening'] = isset($all_opening) ? $all_opening : array();


                //GET TOTAL CREDIT AND DEBIT AMOUNT
                $amountq = "SELECT l.id,l.amount,l.customer_id,l.ledger_type,di.id as invoice_id,l.payment_type,l.payment_id,
            di.company_master_id as invoice_company 
                FROM ledger_outstanding_item l
                LEFT OUTER JOIN docket_invoice di ON(di.id=l.payment_id AND l.payment_type=5 AND di.status IN(1,2)  AND di.is_cancelled!=1)
                WHERE l.status IN(1,2) 
                AND l.particular!='round_off' 
                AND l.payment_type!=6"
                    //."AND l.customer_id IN(" . implode(",", $cust_id_arr) . ") " 
                    . $ledger_date_appendquery;
                $amountq_exe = $this->db->query($amountq);
                $amount_data = $amountq_exe->result_array();

                if (isset($amount_data) && is_array($amount_data) && count($amount_data) > 0) {
                    foreach ($amount_data as $akey => $avalue) {


                        if ($avalue['payment_type'] == 5 && $avalue['invoice_id'] == '') {
                            continue;
                        } else if ($avalue['payment_type'] == 1 && (!isset($all_opening[$avalue['payment_id']]))) {
                            continue;
                        } else if ($avalue['payment_type'] == 2 && (!isset($all_receipt[$avalue['payment_id']]))) {
                            continue;
                        } else if (($avalue['payment_type'] == 3 || $avalue['payment_type'] == 4) && (!isset($all_note[$avalue['payment_id']]))) {
                            continue;
                        } else if (isset($show_comp_outstanding) && $show_comp_outstanding > 0) {
                            if ($avalue['payment_type'] == 1 && !isset($all_opening[$avalue['payment_id']])) {
                                continue;
                            } else if ($avalue['payment_type'] == 2 && !isset($all_receipt[$avalue['payment_id']])) {
                                continue;
                            } else if (($avalue['payment_type'] == 3 || $avalue['payment_type'] == 4) && !isset($all_note[$avalue['payment_id']])) {
                                continue;
                            }
                        }

                        $company_id = 0;
                        if ($avalue['payment_type'] == 1) {
                            $company_id = $all_opening[$avalue['payment_id']];
                        } else if ($avalue['payment_type'] == 2) {
                            $company_id = $all_receipt[$avalue['payment_id']];
                        } else  if (($avalue['payment_type'] == 3 || $avalue['payment_type'] == 4)) {
                            $company_id = $all_note[$avalue['payment_id']];
                        } else if ($avalue['payment_type'] == 5) {
                            $company_id = $avalue['invoice_company'];
                        }


                        if (isset($data['customer_amount'][$avalue['ledger_type']][$avalue['customer_id']])) {
                            $data['customer_amount'][$avalue['ledger_type']][$avalue['customer_id']] += $avalue['amount'];
                        } else {
                            $data['customer_amount'][$avalue['ledger_type']][$avalue['customer_id']] = $avalue['amount'];
                        }

                        if (isset($data['all_customer'][$avalue['customer_id']])) {
                            if (isset($data['company_amount'][$avalue['ledger_type']][$company_id])) {
                                $data['company_amount'][$avalue['ledger_type']][$company_id] += $avalue['amount'];
                            } else {
                                $data['company_amount'][$avalue['ledger_type']][$company_id] = $avalue['amount'];
                            }

                            if (isset($data['company_cust_amount'][$avalue['ledger_type']][$company_id][$avalue['customer_id']])) {
                                $data['company_cust_amount'][$avalue['ledger_type']][$company_id][$avalue['customer_id']] += $avalue['amount'];
                            } else {
                                $data['company_cust_amount'][$avalue['ledger_type']][$company_id][$avalue['customer_id']] = $avalue['amount'];
                            }
                        }

                        $ledger_cust[$avalue['customer_id']] = $avalue['customer_id'];
                    }
                }

                $unbill_date_appendquery = '';
                if (
                    isset($data['setting']['customised_unbilled_dockets_in_leder_and_outstanding_report']) && $data['setting']['customised_unbilled_dockets_in_leder_and_outstanding_report'] == 1
                    && isset($data['setting']['account_ledger_start_date']) && $data['setting']['account_ledger_start_date'] != '' && $data['setting']['account_ledger_start_date'] != '1970-01-01' && $data['setting']['account_ledger_start_date'] != '0000-00-00'
                ) {
                    $unbill_date_appendquery .= " AND d.booking_date >='" . $data['setting']['account_ledger_start_date'] . "'";
                }
                $product_id = "";
                $product_void = get_all_product(" AND code = 'void'");
                if (isset($product_void) && is_array($product_void) && count($product_void) > 0) {
                    foreach ($product_void as $key => $value) {
                        $product_id = $value['id'];
                    }
                }
                $unbilledq = "SELECT d.id,d.awb_no,d.booking_date,
            d.destination_id,d.chargeable_wt,ds.grand_total,d.customer_id,d.company_id,de.sector_id FROM `docket` d 
            JOIN customer c ON(c.id=d.customer_id)
                LEFT OUTER JOIN docket_sales_billing ds ON(d.id=ds.docket_id AND ds.status IN(1,2))
                LEFT OUTER JOIN docket_extra_field de ON(d.id=de.docket_id AND de.status=1)
                WHERE d.status IN(1,2) AND d.status_id!=3  AND d.product_id != '" . $product_id . "' " . $unbill_date_appendquery . " AND c.status IN(1,2)"
                    . " AND d.id NOT IN(SELECT docket_id FROM docket_invoice_map WHERE status IN(1,2))";
                $unbilledq_exe = $this->db->query($unbilledq);
                $unbilled_data = $unbilledq_exe->result_array();

                if (isset($unbilled_data) && is_array($unbilled_data) && count($unbilled_data) > 0) {
                    foreach ($unbilled_data as $rkey => $rvalue) {
                        if (isset($ledger_cust[$rvalue['customer_id']])) {
                            if (isset($data['unbilled_customer'][$rvalue['customer_id']])) {
                                $data['unbilled_customer'][$rvalue['customer_id']] += $rvalue['grand_total'];
                            } else {
                                $data['unbilled_customer'][$rvalue['customer_id']] = $rvalue['grand_total'];
                            }
                            if (isset($data['all_customer'][$rvalue['customer_id']])) {
                                if (isset($data['unbilled_company'][$rvalue['company_id']])) {
                                    $data['unbilled_company'][$rvalue['company_id']] += $rvalue['grand_total'];
                                } else {
                                    $data['unbilled_company'][$rvalue['company_id']] = $rvalue['grand_total'];
                                }

                                if (isset($data['unbilled_company_cust'][$rvalue['company_id']][$rvalue['customer_id']])) {
                                    $data['unbilled_company_cust'][$rvalue['company_id']][$rvalue['customer_id']] += $rvalue['grand_total'];
                                } else {
                                    $data['unbilled_company_cust'][$rvalue['company_id']][$rvalue['customer_id']] = $rvalue['grand_total'];
                                }
                            }
                        }
                    }
                }
            }
            $data['all_company'] = get_all_billing_company(" AND status IN(1,2)");

            if (isset($setting['show_service_weight_report']) && $setting['show_service_weight_report'] == 1) {
                $data['all_vendor'] = get_all_vendor(" AND status IN(1,2)");
                $data['service_wt'] = array();
                //Show weight wise service list
                $qry = "SELECT sum(chargeable_wt) as chargeable_wt_sum,vendor_id FROM docket WHERE status=1 GROUP by vendor_id";
                $qry_exe = $this->db->query($qry);
                $service_wt_data = $qry_exe->result_array();
                if (isset($service_wt_data) && is_array($service_wt_data) && count($service_wt_data) > 0) {
                    foreach ($service_wt_data as $key => $value) {
                        $data['service_wt'][$value['vendor_id']] = $value['chargeable_wt_sum'];
                    }
                }

                rsort($data['service_wt']);
            }

            //Show Last 3 months booking Details
            if (isset($setting['show_last_3_month_booking_report']) && $setting['show_last_3_month_booking_report'] == 1) {
                $month_start =  date('Y-m', strtotime('-2 month')) . "-01";
                $month_end = date('Y-m-t');

                $month_arr[] = date('Y-m', strtotime('-2 month'));
                $month_arr[] = date('Y-m', strtotime('-1 month'));
                $month_arr[] = date('Y-m');
                $qry = "SELECT id,state_id,booking_date FROM docket WHERE status=1 AND booking_date>='$month_start'"
                    . "  AND booking_date<='$month_end'";

                $data['month_booking_range'] = $month_arr;
                $qry_exe = $this->db->query($qry);
                $booking_data = $qry_exe->result_array();
                if (isset($booking_data) && is_array($booking_data) && count($booking_data) > 0) {
                    foreach ($booking_data as $key => $value) {
                        $booking_mon = date('Y-m', strtotime($value['booking_date']));
                        if (isset($data['booking_data'][$booking_mon][$value['state_id']])) {
                            $data['booking_data'][$booking_mon][$value['state_id']] += 1;
                        } else {
                            $data['booking_data'][$booking_mon][$value['state_id']] = 1;
                        }

                        if (isset($data['total_booking'][$booking_mon])) {
                            $data['total_booking'][$booking_mon] += 1;
                        } else {
                            $data['total_booking'][$booking_mon] = 1;
                        }
                    }
                }
            }


            //GET TICKET DATA
            $qry = "SELECT id,sw_status,status_mark_date FROM ticket WHERE status=1";
            $qry_exe = $this->db->query($qry);
            $ticket_data = $qry_exe->result_array();
            if (isset($ticket_data) && is_array($ticket_data) && count($ticket_data) > 0) {
                foreach ($ticket_data as $key => $value) {
                    if (isset($data['ticket_data'][$value['sw_status']])) {
                        $data['ticket_data'][$value['sw_status']] += 1;
                    } else {
                        $data['ticket_data'][$value['sw_status']] = 1;
                    }


                    if ($value['status_mark_date'] == date('Y-m-d')) {
                        if (isset($data['today_ticket_data'][$value['sw_status']])) {
                            $data['today_ticket_data'][$value['sw_status']] += 1;
                        } else {
                            $data['today_ticket_data'][$value['sw_status']] = 1;
                        }
                    }
                }
            }

            $data['all_ticket_status'] = all_ticket_status();


            $pagination_data = array(
                'url' => site_url('adminx'),
                'total_rows' => isset($data['total']) ? $data['total'] : 0
            );
            pagination_config($pagination_data);
        }

        return  $data;
    }
    public function show_customer_dashboard()
    {
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');
        $sessiondata = $this->session->userdata('admin_user');
        $customer_id = $sessiondata['customer_id'];


        //GET OFFER DATA
        $today = date("Y-m-d");
        $query = "SELECT title,subject,id,from_date,till_date FROM offer WHERE status IN(1,2) AND 
        '$today' BETWEEN from_date AND till_date";
        $query_exe = $this->db->query($query);
        $data['offer_data'] = $query_exe->result_array();

        $query = "SELECT title,media_path,id,from_date,till_date FROM slider WHERE status IN(1,2) AND 
        '$today' BETWEEN from_date AND till_date";
        $query_exe = $this->db->query($query);
        $data['slider_data'] = $query_exe->result_array();

        //TOTAL DOCKET BOOKED
        $query = "SELECT SUM(report_value) as total_docket FROM dashboard_data WHERE status IN(1,2) AND 
        report_key='total_docket' AND customer_id='" . $customer_id . "'";
        $query_exe = $this->db->query($query);
        $report_data = $query_exe->row_array();
        $data['total_docket'] = isset($report_data['total_docket']) ? $report_data['total_docket'] : 0;

        //TOTAL DOCKET DELIVERED
        $query = "SELECT SUM(report_value) as total_delivered FROM dashboard_data WHERE status IN(1,2) AND 
        report_key='total_delivered' AND customer_id='" . $customer_id . "'";
        $query_exe = $this->db->query($query);
        $report_data = $query_exe->row_array();
        $data['total_delivered'] = isset($report_data['total_delivered']) ? $report_data['total_delivered'] : 0;

        //TOTAL DOCKET DELIVERED
        $query = "SELECT SUM(report_value) as total_intransit FROM dashboard_data WHERE status IN(1,2) AND 
        report_key='total_intransit' AND customer_id='" . $customer_id . "'";
        $query_exe = $this->db->query($query);
        $report_data = $query_exe->row_array();
        $data['total_intransit'] = isset($report_data['total_intransit']) ? $report_data['total_intransit'] : 0;


        //TOTAL DOCKET DELIVERED
        $query = "SELECT SUM(report_value) as total_sale FROM dashboard_data WHERE status IN(1,2) AND 
        report_key='total_sale' AND customer_id='" . $customer_id . "'";
        $query_exe = $this->db->query($query);
        $report_data = $query_exe->row_array();
        $data['total_sale'] = isset($report_data['total_sale']) ? $report_data['total_sale'] : 0;

        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime("-1 days"));
        $last7 = date('Y-m-d', strtotime("-7 days"));
        $last15 = date('Y-m-d', strtotime("-15 days"));

        //TODAY LOAD
        $query = "SELECT report_value,vendor_id FROM dashboard_data WHERE status IN(1,2) AND 
        report_key='service_load' AND report_date = '" . $today . "' AND customer_id='" . $customer_id . "'";
        $query_exe = $this->db->query($query);
        $load_data = $query_exe->result_array();
        if (isset($load_data) && is_array($load_data) && count($load_data) > 0) {
            foreach ($load_data as $lkey => $lvalue) {
                if (isset($data['today_load'][$lvalue['vendor_id']])) {
                    $data['today_load'][$lvalue['vendor_id']] += $lvalue['report_value'];
                } else {
                    $data['today_load'][$lvalue['vendor_id']] = $lvalue['report_value'];
                }
            }
        }


        //YESTERDAY LOAD
        $query = "SELECT report_value,vendor_id FROM dashboard_data WHERE status IN(1,2) AND 
        report_key='service_load' AND report_date ='" . $yesterday . "' AND customer_id='" . $customer_id . "'";
        $query_exe = $this->db->query($query);
        $load_data = $query_exe->result_array();
        if (isset($load_data) && is_array($load_data) && count($load_data) > 0) {
            foreach ($load_data as $lkey => $lvalue) {
                if (isset($data['yes_load'][$lvalue['vendor_id']])) {
                    $data['yes_load'][$lvalue['vendor_id']] += $lvalue['report_value'];
                } else {
                    $data['yes_load'][$lvalue['vendor_id']] = $lvalue['report_value'];
                }
            }
        }

        //7 DAY LOAD
        $query = "SELECT report_value,vendor_id FROM dashboard_data WHERE status IN(1,2) AND 
         report_key='service_load' AND report_date BETWEEN '" . $last7 . "' AND '" . $today . "' AND customer_id='" . $customer_id . "'";;
        $query_exe = $this->db->query($query);
        $load_data = $query_exe->result_array();
        if (isset($load_data) && is_array($load_data) && count($load_data) > 0) {
            foreach ($load_data as $lkey => $lvalue) {
                if (isset($data['day7_load'][$lvalue['vendor_id']])) {
                    $data['day7_load'][$lvalue['vendor_id']] += $lvalue['report_value'];
                } else {
                    $data['day7_load'][$lvalue['vendor_id']] = $lvalue['report_value'];
                }
            }
        }

        //15 DAY LOAD
        $query = "SELECT report_value,vendor_id FROM dashboard_data WHERE status IN(1,2) AND 
         report_key='service_load' AND report_date BETWEEN '" . $last15 . "' AND '" . $today . "' AND customer_id='" . $customer_id . "'";
        $query_exe = $this->db->query($query);
        $load_data = $query_exe->result_array();
        if (isset($load_data) && is_array($load_data) && count($load_data) > 0) {
            foreach ($load_data as $lkey => $lvalue) {
                if (isset($data['day15_load'][$lvalue['vendor_id']])) {
                    $data['day15_load'][$lvalue['vendor_id']] += $lvalue['report_value'];
                } else {
                    $data['day15_load'][$lvalue['vendor_id']] = $lvalue['report_value'];
                }
            }
        }

        // echo '<pre>';
        // print_r($data);
        // exit;

        //DELIVERED SERVICE DATA
        $query = "SELECT report_value,vendor_id FROM dashboard_data WHERE status IN(1,2) AND 
         report_key='service_delivered' AND customer_id='" . $customer_id . "'";
        $query_exe = $this->db->query($query);
        $load_data = $query_exe->result_array();
        if (isset($load_data) && is_array($load_data) && count($load_data) > 0) {
            foreach ($load_data as $lkey => $lvalue) {
                if (isset($data['service_delivered'][$lvalue['vendor_id']])) {
                    $data['service_delivered'][$lvalue['vendor_id']] += $lvalue['report_value'];
                } else {
                    $data['service_delivered'][$lvalue['vendor_id']] = $lvalue['report_value'];
                }
            }
        }

        //IN-TRANSIT SERVICE DATA
        $query = "SELECT report_value,vendor_id FROM dashboard_data WHERE status IN(1,2) AND 
         report_key='service_intransit' AND customer_id='" . $customer_id . "'";
        $query_exe = $this->db->query($query);
        $load_data = $query_exe->result_array();
        if (isset($load_data) && is_array($load_data) && count($load_data) > 0) {
            foreach ($load_data as $lkey => $lvalue) {
                if (isset($data['service_intransit'][$lvalue['vendor_id']])) {
                    $data['service_intransit'][$lvalue['vendor_id']] += $lvalue['report_value'];
                } else {
                    $data['service_intransit'][$lvalue['vendor_id']] = $lvalue['report_value'];
                }
            }
        }

        $query = "SELECT report_value,vendor_id FROM dashboard_data WHERE status IN(1,2) AND 
         report_key='service_total' AND customer_id='" . $customer_id . "'";
        $query_exe = $this->db->query($query);
        $load_data = $query_exe->result_array();
        if (isset($load_data) && is_array($load_data) && count($load_data) > 0) {
            foreach ($load_data as $lkey => $lvalue) {
                if (isset($data['service_total'][$lvalue['vendor_id']])) {
                    $data['service_total'][$lvalue['vendor_id']] += $lvalue['report_value'];
                } else {
                    $data['service_total'][$lvalue['vendor_id']] = $lvalue['report_value'];
                }
            }
        }

        $data['all_vendor'] = get_all_vendor();

        // //BALANCE CREDIT
        // $qry = "SELECT SUM(amount) as credit_amt FROM ledger_outstanding_item WHERE status IN(1,2) AND ledger_type=1 AND customer_id='" . $customer_id . "'";
        // $qry_exe = $this->db->query($qry);
        // $credit_data = $qry_exe->row_array();
        // $credit_amount = isset($credit_data['credit_amt']) && $credit_data['credit_amt'] != '' ? $credit_data['credit_amt'] : 0;

        // //GET DEBIT AMOUNT
        // $qry = "SELECT SUM(amount) as debit_amt FROM ledger_outstanding_item WHERE status IN(1,2) AND ledger_type=2 AND customer_id='" . $customer_id . "'";
        // $qry_exe = $this->db->query($qry);
        // $debit_data = $qry_exe->row_array();
        // $debit_amount = isset($debit_data['debit_amt']) && $debit_data['debit_amt'] != '' ? $debit_data['debit_amt'] : 0;
        // $unbill_append = '';

        // $unbilledq = "SELECT SUM(ds.grand_total) as unbilled_amt FROM `docket` d
        //     JOIN customer c ON(c.id=d.customer_id)
        //   LEFT OUTER JOIN docket_consignee dcon ON(d.id=dcon.docket_id AND dcon.status IN(1,2))
        //   LEFT OUTER JOIN docket_shipper dshi ON(d.id=dshi.docket_id AND dshi.status IN(1,2))
        //   LEFT OUTER JOIN docket_sales_billing ds ON(d.id=ds.docket_id AND ds.status IN(1,2))
        //   WHERE d.status IN(1,2) " . $unbill_append . " AND c.status IN(1,2) AND d.customer_id ='" . $customer_id . "' AND d.id NOT IN(SELECT id FROM docket_invoice_map WHERE status IN(1,2)) ";
        // $unbilledq_exe = $this->db->query($unbilledq);
        // $unbilled_data = $unbilledq_exe->row_array();

        // $unbilled_amt = isset($unbilled_data['unbilled_amt']) && $unbilled_data['unbilled_amt'] != '' ? $unbilled_data['unbilled_amt'] : 0;
        // $current_docket_total = isset($post_data['sales_grand_total']) && $post_data['sales_grand_total'] != '' ? $post_data['sales_grand_total'] : 0;
        // $total_unbilled = $unbilled_amt + $current_docket_total;
        // $balance = ($debit_amount - $credit_amount) + $total_unbilled;

        // $customer_data = $this->gm->get_selected_record('customer', 'id,credit_limit,aging_limit', $where = array('id' => $customer_id, 'status' => 1), array());

        // if ($customer_data['credit_limit'] > 0) {
        //     $data['balance_credit'] = $customer_data['credit_limit'] - $balance;
        // }

        // if (!isset($data['balance_credit']) || $data['balance_credit'] < 0) {
        //     $data['balance_credit'] = 0;
        // }
        // $ledger_data = $this->get_customer_ledger($customer_id);
        // $data['balance_credit'] = isset($ledger_data['customer_balance']) ?  $ledger_data['customer_balance'] : 0;
        $this->_display('customer_dashboard/dashboard1', $data);
    }

    public function _display($view, $data)
    {
        $data['heading'] = 'DASHBOARD';
        $this->load->view('admin_header', $data);
        $this->load->view('sidebar', $data);
        $this->load->view($view, $data);
        $this->load->view('admin_footer');
    }
}
