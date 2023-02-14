<?php $session_data = $this->session->userdata('admin_user');
$admin_role_name = array('software_user');
$this->load->helper('frontend_common');
?>
<style>
  .sidebar li {
    line-height: 1.2 !important;
  }

  .sidebar li a {
    font-size: 12px;
  }
</style>
<?php

if ($session_data['type'] == 'customer') {
  $this->load->view('customer_sidebar');
} else if ($session_data['type'] == 'vendor') {
  $this->load->view('vendor_sidebar');
} else {
  $this->load->view('company_sidebar');
}