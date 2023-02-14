<?php

class Page_management extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
    }

    public function _display($view, $data)
    {
        $this->load->view('frontend_header', $data);
        $this->load->view($view, $data);
        $this->load->view('frontend_footer');
    }

    public function index()
    {
    }
    public function get_portal_master()
    {
        $this->load->helper('url');
        $this->load->model('Global_model', 'gm');
        $portal_id = $this->input->post('portal_id');
        $qry = "SELECT id,name FROM project_module WHERE status IN(1,2) AND software_portal_id=" . $portal_id;
        $qry_exe = $this->db->query($qry);
        $master_data = $qry_exe->result_array();
        echo json_encode($master_data);
    }
}
