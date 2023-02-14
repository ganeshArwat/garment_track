<?php

// require_once FCPATH . '/phpwkhtmltopdf-master/src/BaseCommand.php';
// require_once FCPATH . '/phpwkhtmltopdf-master/src/File.php';
// $path2 = FCPATH . '/phpwkhtmltopdf-master/src/Command.php';
// //require_once($path);
// require_once($path2);
// require_once FCPATH . '/phpwkhtmltopdf-master/src/Image.php';
// require_once FCPATH . '/phpwkhtmltopdf-master/src/Pdf.php';
// use mikehaertl\wkhtmlto\Pdf;
$path = FCPATH . '/dompdf-master/autoload.inc.php';
require_once($path);

use Dompdf\Dompdf;
use Dompdf\Options;

class Download_data extends MX_Controller
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
        $this->user_type = $sessiondata['type'] == 'customer' ? 2 : 1;
        $this->customer_id = $sessiondata['customer_id'];
        $this->user_id = $sessiondata['id'];
    }

    public function _display($view, $data)
    {
        $data['heading'] = 'Docket';
        $this->load->view('admin_header', $data);
        $this->load->view('sidebar', $data);
        $this->load->view($view, $data);
        $this->load->view('admin_footer');
    }

    public function download_data_list()
    {
        $this->load->helper('pagination');
        $this->load->helper('url');
        $this->load->helper('frontend_common');
        $this->load->model('Global_model', 'gm');
        $data = array();
        $this->_display("download_data_list", $data);
    }

    public function start_download()
    {
        $this->load->helper('url');
        $co_vendor_id = $this->input->post("co_vendor_id");
        $customer_id = $this->input->post("customer_id");
        $vendor_id = $this->input->post("vendor_id"); //service
        $booking_min = $this->input->post("booking_min");
        $booking_max = $this->input->post("booking_max");
        $this->load->model('Global_model', 'gm');
        $this->load->module("docket");

        $searcharray = array();
        if (isset($vendor_id) && !empty($vendor_id)) {
            $searcharray["vendor_id"] = $vendor_id;
        }
        if (isset($co_vendor_id) && !empty($co_vendor_id)) {
            $searcharray["co_vendor_id"] = $vendor_id;
        }
        if (isset($customer_id) && !empty($customers_id)) {
            $searcharray["customer_id"] = $customer_id;
        }
        if (isset($booking_min) && $booking_min != '') {
            $searcharray["DATE_FORMAT(booking_date,'%Y-%m-%d') >="] = $booking_min;
        } else {
            echo "MinDate not selected";
            exit;
        }
        if (isset($booking_max) && $booking_max != '') {
            $searcharray["booking_date <="] = $booking_max;
        } else {
            echo "MaxDate not selected";
            exit;
        }
        $searcharray["status"] = 1;

        $docket_data = $this->gm->get_data_list('docket', $searcharray, array(), array(), '*');
        print_r($this->db->last_query());
        exit;
        foreach ($docket_data as $key => $value) {
            $_GET["type"] = 1;
            $_GET["docket"] = $value["id"];
            $_GET["to_save"] = 1;
            $this->docket->print_docket();
            unset($_GET["type"]);
            $_GET["type"] = 2;
            $_GET["docket"] = $value["id"];
            $this->docket->print_docket();
            unset($_GET["type"]);
            unset($_GET["docket"]);
            $this->docket->print_free_form_invoice($value["id"]);
            $this->docket->print_label($value["id"]);
            //copy uploaded media
            $media_data = $this->gm->get_data_list('media_attachment', array('module_id' => $value["id"], 'module_type' => 1, 'status' => 1), array(), array(), '*');


            if (isset($media_data) && is_array($media_data) && count($media_data) > 0) {
                foreach ($media_data as $mk => $mv) {
                    if (file_exists($mv["media_path"])) {
                        $ext = pathinfo($mv["media_path"], PATHINFO_EXTENSION);

                        $filename2 = create_year_dir('temp');

                        copy($mv["media_path"], $filename2 . '/' . $value["awb_no"] . '/' . $value["awb_no"] . " _" . $mk . "." . $ext);
                    }
                }
            }
        }
    }
}
