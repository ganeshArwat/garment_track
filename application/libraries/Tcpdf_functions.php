<?php
$path = FCPATH . 'application/libraries/TCPDF/tcpdf.php';
require_once("$path");
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tcpdf_functions extends TCPDF
{
    protected $last_page = false;
    public $base_newpage = 272.629456;
    public $add_newpage = 11.43001755556;


    //Page header
    public function Header()
    {

        //FREEFORM
        if (isset($_GET['pdf_name']) && $_GET['pdf_name'] != '' && $_GET['pdf_name'] == "freeform") {
            $this->SetFont('helvetica', '', 20);
            $invoice_type = strtoupper($_GET['freeform']['invoice_type']);
            $invoice_no = $_GET['freeform']['invoice_no'];
            $forwarding_no = $_GET['freeform']['forwarding_no'];
            $invoice_date = $_GET['freeform']['invoice_date'];
            $total_pieces = $_GET['freeform']['total_pieces'];
            $chargeable_wt = $_GET['freeform']['chargeable_wt'];

            $aadhaar_no = $_GET['freeform']['aadhaar_no'];

            $shipper_name = strtoupper($_GET['freeform']['shipper_name']);
            $shipper_company_name = strtoupper($_GET['freeform']['shipper_company_name']);
            $shipper_address1 = strtoupper($_GET['freeform']['shipper_address1']);
            $shipper_address2 = strtoupper($_GET['freeform']['shipper_address2']);
            $shipper_address3 = strtoupper($_GET['freeform']['shipper_address3']);
            $shipper_email = $_GET['freeform']['shipper_email'];
            $shipper_phone = $_GET['freeform']['shipper_phone'];

            $consignee_name = strtoupper($_GET['freeform']['consignee_name']);
            $consignee_company_name = strtoupper($_GET['freeform']['consignee_company_name']);
            $consignee_address1 = strtoupper($_GET['freeform']['consignee_address1']);
            $consignee_address2 = strtoupper($_GET['freeform']['consignee_address2']);
            $consignee_address3 = strtoupper($_GET['freeform']['consignee_address3']);
            $consignee_email = $_GET['freeform']['consignee_email'];
            $consignee_phone = $_GET['freeform']['consignee_phone'];
            $consignee_phone2 = $_GET['freeform']['consignee_phone2'];
            $sendor_city_state = trim(strtoupper($_GET['freeform']['sendor_city_state']), " ");
            $sendor_country_pincode = trim(strtoupper($_GET['freeform']['sendor_country_pincode']), " ");
            $receiver_city_state = trim(strtoupper($_GET['freeform']['receiver_city_state']), " ");
            $receiver_country_pincode = trim(strtoupper($_GET['freeform']['receiver_country_pincode']), " ");

            $awb_no = $_GET['freeform']['awb_no'];
            $reference_no = $_GET['freeform']['reference_no'];

            $gstin_no = strtoupper(isset($_GET['freeform']['gstin_no']) ? $_GET['freeform']['gstin_no'] : "");
            $gstin_type = strtoupper(isset($_GET['freeform']['gstin_type']) ? $_GET['freeform']['gstin_type'] . ' :' : "");
            $incoterm = strtoupper($_GET['freeform']['incoterm']);
            $setting = $_GET['freeform']['setting'];

            if (isset($setting['free_text_in_free_form']) && $setting['free_text_in_free_form'] == 1) {
                $free_value1 = strtoupper($_GET['freeform']['free_value1']);
                $free_value2 = strtoupper($_GET['freeform']['free_value2']);
                $free_value3 = strtoupper($_GET['freeform']['free_value3']);
                $free_value4 = strtoupper($_GET['freeform']['free_value4']);
                $free_value5 = strtoupper($_GET['freeform']['free_value5']);
                $free_value6 = strtoupper($_GET['freeform']['free_value6']);
                $free_value7 = strtoupper($_GET['freeform']['free_value7']);

                $free_text1 = strtoupper($_GET['freeform']['free_text1']);
                $free_text2 = strtoupper($_GET['freeform']['free_text2']);
                $free_text3 = strtoupper($_GET['freeform']['free_text3']);
                $free_text4 = strtoupper($_GET['freeform']['free_text4']);
                $free_text5 = strtoupper($_GET['freeform']['free_text5']);
                $free_text6 = strtoupper($_GET['freeform']['free_text6']);
                $free_text7 = strtoupper($_GET['freeform']['free_text7']);
            }

            if (isset($setting["hide_incoterm_from_free_form"]) && $setting["hide_incoterm_from_free_form"] != 1) {
                $incoterm = "(" . strtoupper($_GET['freeform']['incoterm']) . ")";
            } else {
                $incoterm = "";
            }

            
            if (isset($setting['rename_company_name_to_name_free_form']) && $setting['rename_company_name_to_name_free_form'] == 1) {
                $company_title='NAME';
            }else{
                $company_title='COMPANY NAME';
            } 

            if (isset($setting["hide_chargeable_wt_from_free_form"]) && $setting["hide_chargeable_wt_from_free_form"] != 1) {
                $chargeable_print = "<span><b>CHARGEABLE WEIGHT :</b>  " . $chargeable_wt . "</span>";
            } else {
                $chargeable_print = "";
            }

            if (isset($setting['add_forwarding_in_freeform_invoice_pdf']) && $setting['add_forwarding_in_freeform_invoice_pdf'] == 1) {
                $forwarding_no_print = "<span><b>FORWARDING NO :</b> " . $forwarding_no . "</span><br>";
            } else {
                $forwarding_no_print = "<br>";
            }

            if (isset($setting['hide_pcs_from_free_form']) && $setting['hide_pcs_from_free_form'] != 1) {
                $total_pcs_print = "<span><b>TOTAL PIECES :</b> " . $total_pieces . "</span><br>";
            } else {
                $total_pcs_print = "<br>";
            }

            if (isset($setting['show_awb_no_from_free_form']) && $setting['show_awb_no_from_free_form'] == 1) {
                $awb_no_print = "<span><b>AWB NO. :</b> " . $awb_no . "</span><br>";
            } else {
                $awb_no_print = "<br>";
            }
            if (isset($setting['show_reference_no_in_free_form_pdf']) && $setting['show_reference_no_in_free_form_pdf'] == 1) {
                $ref_no_print = "<br><span><b>REFERENCE NO. : </b> ".$reference_no."</span>";
            } else {
                $ref_no_print = "<br>";
            }
            $td = "";
            if (isset($setting['free_text_in_free_form']) && $setting['free_text_in_free_form'] == 1) {
                $span = "";
                if (isset($free_text1) && $free_text1 != "") {
                    $span .= '<span><b>' . $free_text1 . '</b>: ' . $free_value1 . '</span><br>';
                }
                if (isset($free_text2) && $free_text2 != "") {
                    $span .= '<span><b>' . $free_text2 . '</b>: ' . $free_value2 . '</span><br>';
                }
                if (isset($free_text3) && $free_text3 != "") {
                    $span .= '<span><b>' . $free_text3 . '</b>: ' . $free_value3 . '</span><br>';
                }
                if (isset($free_text4) && $free_text4 != "") {
                    $span .= '<span><b>' . $free_text4 . '</b>: ' . $free_value4 . '</span><br>';
                }
                if (isset($free_text5) && $free_text5 != "") {
                    $span .= '<span><b>' . $free_text5 . '</b>: ' . $free_value5 . '</span><br>';
                }
                if (isset($free_text6) && $free_text6 != "") {
                    $span .= '<span><b>' . $free_text6 . '</b>: ' . $free_value6 . '</span><br>';
                }
                if (isset($free_text7) && $free_text7 != "") {
                    $span .= '<span><b>' . $free_text7 . '</b>: ' . $free_value7 . '</span>';
                }
                $td = '<td colspan="4">' . $span . '</td>
                <td colspan="4"><span><b>INVOICE NO. :</b>' . $invoice_no . ' </span><br><span><b>INVOICE DATE. :</b>' . $invoice_date . '</span><br>' . $total_pcs_print . $chargeable_print. "<br>" . $forwarding_no_print . $awb_no_print . '<span><b>OTHER REFERENCE</b></span><br><span><b>' . $gstin_type . '</b>' . $gstin_no . '</span>'.$ref_no_print.'</td>';
            } else {
                $td = '<td colspan="4"><span><b>INVOICE NO. :</b>' . $invoice_no . ' </span><br><span><b>INVOICE DATE. :</b>' . $invoice_date . '</span><br>' . $total_pcs_print . $chargeable_print . $ref_no_print.'</td>
                <td colspan="4">' . $forwarding_no_print . $awb_no_print . '<span><b>OTHER REFERENCE</b></span><br><span><b>' . $gstin_type . '</b>' . $gstin_no . '</span></td>';
            }

            $tbl = '<style>
                            table {
                                border-collapse: collapse;
                                font-weight: 300;
                                font-family: sans-serif;
                            }
                            table td {
                                border: 1px solid #000;
                                font-size:12px;
                                line-height: 16px;
                                color: #000;
                            }
                    </style>
                    <Table cellpadding="4" cellspacing="0">
                    <tr style="text-align:center;">
                        <td colspan="8"><p style="font-size:18px;">' . $invoice_type . '</p></td>
                    </tr>
                    <tr>
                        ' . $td . '
                    </tr>
                    <tr>
                        <td colspan="4"><span style="font-size:12px;"><b>SHIPPER</b></span></td>
                        <td colspan="4"><span style="font-size:12px;"><b>CONSIGNEE</b></span></td>
                    </tr>
                    <tr>
                        <td colspan="4" style="border-bottom:none;border-top:none;"><span><b>' . $shipper_name . '</b></span><br><span><b>'. $company_title.'</b> :' . $shipper_company_name . '</span><br><span><b>ADDRESS</b> :' . $shipper_address1 . '</span><br><span>' . $shipper_address2 . '</span><br><span>' . $shipper_address3 . '</span><br><span>' . $sendor_city_state . '</span><br><span>' . $sendor_country_pincode . '</span><br><span><b>EMAIL</b> ' . $shipper_email . '</span><br><span><b>PHONE NUMBER</b> : ' . $shipper_phone . '</span></td>  
                        <td colspan="4" style="border-bottom:none;border-top:none;"><span><b>' . $consignee_name . '</b></span><br><span><b>'. $company_title.' :</b>' . $consignee_company_name . '</span><br><span><b>ADDRESS :</b> ' . $consignee_address1 . '</span><br><span>' . $consignee_address2 . '</span><br><span>' . $consignee_address3 . '</span><br><span>' . $receiver_city_state . '</span><br><span>' . $receiver_country_pincode . '</span><br><span><b>EMAIL </b>' . $consignee_email . '</span><br><span><b>PHONE NUMBER :</b> ' . $consignee_phone . $consignee_phone2 . '</span></td>
                    </tr>
                     <tr>
                     <td style="width:6%"><b>SR.<br>NO.</b></td>
                     <td colspan="2" style="width:44%;text-align:center;"><b>DESCRIPTION</b></td>
                     <td style="width:10%; text-align:center;"><b>HS<br>CODE</b></td>
                     <td style="width:10%; text-align:center;"><b>UNIT<br>TYPE</b></td>
                     <td style="width:10%; text-align:center;"><b>QUANTITY</b></td>
                     <td style="width:10%; text-align:center;"><b>UNIT<br>RATES</b></td>
                     <td style="width:10%; text-align:center;"><b>AMOUNT<br> ' . $incoterm . ' </b></td>
                </tr>
                    </table>
                  ';
            $this->writeHTML($tbl, true, false, false, false, '');
        }

        //FREEFORM supreme
        if (isset($_GET['pdf_name']) && $_GET['pdf_name'] != '' && $_GET['pdf_name'] == "freeform_supreme") {
            $this->SetFont('helvetica', '', 20);
            $invoice_type = strtoupper($_GET['freeform']['invoice_type']);
            $invoice_no = $_GET['freeform']['invoice_no'];
            $forwarding_no = $_GET['freeform']['forwarding_no'];
            $invoice_date = $_GET['freeform']['invoice_date'];
            $total_pieces = $_GET['freeform']['total_pieces'];
            $chargeable_wt = $_GET['freeform']['chargeable_wt'];

            $aadhaar_no = $_GET['freeform']['aadhaar_no'];

            $shipper_name = strtoupper($_GET['freeform']['shipper_name']);
            $shipper_company_name = strtoupper($_GET['freeform']['shipper_company_name']);
            $shipper_address1 = strtoupper($_GET['freeform']['shipper_address1']);
            $shipper_address2 = strtoupper($_GET['freeform']['shipper_address2']);
            $shipper_address3 = strtoupper($_GET['freeform']['shipper_address3']);
            $shipper_email = $_GET['freeform']['shipper_email'];
            $shipper_phone = $_GET['freeform']['shipper_phone'];

            $consignee_name = strtoupper($_GET['freeform']['consignee_name']);
            $consignee_company_name = strtoupper($_GET['freeform']['consignee_company_name']);
            $consignee_address1 = strtoupper($_GET['freeform']['consignee_address1']);
            $consignee_address2 = strtoupper($_GET['freeform']['consignee_address2']);
            $consignee_address3 = strtoupper($_GET['freeform']['consignee_address3']);
            $consignee_email = $_GET['freeform']['consignee_email'];
            $consignee_phone = $_GET['freeform']['consignee_phone'];
            $consignee_phone2 = $_GET['freeform']['consignee_phone2'];
            $sendor_city_state = trim(strtoupper($_GET['freeform']['sendor_city_state']), " ");
            $sendor_country_pincode = trim(strtoupper($_GET['freeform']['sendor_country_pincode']), " ");
            $receiver_city_state = trim(strtoupper($_GET['freeform']['receiver_city_state']), " ");
            $receiver_country_pincode = trim(strtoupper($_GET['freeform']['receiver_country_pincode']), " ");

            $awb_no = $_GET['freeform']['awb_no'];

            $gstin_no = strtoupper(isset($_GET['freeform']['gstin_no']) ? $_GET['freeform']['gstin_no'] : "");
            $gstin_type = strtoupper(isset($_GET['freeform']['gstin_type']) ? $_GET['freeform']['gstin_type'] . ' :' : "");
            $incoterm = strtoupper($_GET['freeform']['incoterm']);
            $setting = $_GET['freeform']['setting'];

            if (isset($setting['free_text_in_free_form']) && $setting['free_text_in_free_form'] == 1) {
                $free_value1 = strtoupper($_GET['freeform']['free_value1']);
                $free_value2 = strtoupper($_GET['freeform']['free_value2']);
                $free_value3 = strtoupper($_GET['freeform']['free_value3']);
                $free_value4 = strtoupper($_GET['freeform']['free_value4']);
                $free_value5 = strtoupper($_GET['freeform']['free_value5']);
                $free_value6 = strtoupper($_GET['freeform']['free_value6']);
                $free_value7 = strtoupper($_GET['freeform']['free_value7']);

                $free_text1 = strtoupper($_GET['freeform']['free_text1']);
                $free_text2 = strtoupper($_GET['freeform']['free_text2']);
                $free_text3 = strtoupper($_GET['freeform']['free_text3']);
                $free_text4 = strtoupper($_GET['freeform']['free_text4']);
                $free_text5 = strtoupper($_GET['freeform']['free_text5']);
                $free_text6 = strtoupper($_GET['freeform']['free_text6']);
                $free_text7 = strtoupper($_GET['freeform']['free_text7']);
            }

            if (isset($setting["hide_incoterm_from_free_form"]) && $setting["hide_incoterm_from_free_form"] != 1) {
                $incoterm = "(" . strtoupper($_GET['freeform']['incoterm']) . ")";
            } else {
                $incoterm = "";
            }

            
            if (isset($setting['rename_company_name_to_name_free_form']) && $setting['rename_company_name_to_name_free_form'] == 1) {
                $company_title='NAME';
            }else{
                $company_title='COMPANY NAME';
            } 

            if (isset($setting["hide_chargeable_wt_from_free_form"]) && $setting["hide_chargeable_wt_from_free_form"] != 1) {
                $chargeable_print = "<span><b>CHARGEABLE WEIGHT :</b>  " . $chargeable_wt . "</span>";
            } else {
                $chargeable_print = "";
            }

            if (isset($setting['add_forwarding_in_freeform_invoice_pdf']) && $setting['add_forwarding_in_freeform_invoice_pdf'] == 1) {
                $forwarding_no_print = "<span><b>FORWARDING NO :</b> " . $forwarding_no . "</span><br>";
            } else {
                $forwarding_no_print = "<br>";
            }

            if (isset($setting['hide_pcs_from_free_form']) && $setting['hide_pcs_from_free_form'] != 1) {
                $total_pcs_print = "<span><b>TOTAL PIECES :</b> " . $total_pieces . "</span><br>";
            } else {
                $total_pcs_print = "<br>";
            }

            if (isset($setting['show_awb_no_from_free_form']) && $setting['show_awb_no_from_free_form'] == 1) {
                $awb_no_print = "<span><b>AWB NO. :</b> " . $awb_no . "</span><br>";
            } else {
                $awb_no_print = "<br>";
            }
            $td = "";
            if (isset($setting['free_text_in_free_form']) && $setting['free_text_in_free_form'] == 1) {
                $span = "";
                if (isset($free_text1) && $free_text1 != "") {
                    $span .= '<span><b>' . $free_text1 . '</b>: ' . $free_value1 . '</span><br>';
                }
                if (isset($free_text2) && $free_text2 != "") {
                    $span .= '<span><b>' . $free_text2 . '</b>: ' . $free_value2 . '</span><br>';
                }
                if (isset($free_text3) && $free_text3 != "") {
                    $span .= '<span><b>' . $free_text3 . '</b>: ' . $free_value3 . '</span><br>';
                }
                if (isset($free_text4) && $free_text4 != "") {
                    $span .= '<span><b>' . $free_text4 . '</b>: ' . $free_value4 . '</span><br>';
                }
                if (isset($free_text5) && $free_text5 != "") {
                    $span .= '<span><b>' . $free_text5 . '</b>: ' . $free_value5 . '</span><br>';
                }
                if (isset($free_text6) && $free_text6 != "") {
                    $span .= '<span><b>' . $free_text6 . '</b>: ' . $free_value6 . '</span><br>';
                }
                if (isset($free_text7) && $free_text7 != "") {
                    $span .= '<span><b>' . $free_text7 . '</b>: ' . $free_value7 . '</span>';
                }
                $td = '<td colspan="4">' . $span . '</td>
                <td colspan="4"><span><b>INVOICE NO. :</b>' . $invoice_no . ' </span><br><span><b>INVOICE DATE. :</b>' . $invoice_date . '</span><br>' . $total_pcs_print . $chargeable_print. "<br>" . $forwarding_no_print . $awb_no_print . '<span><b>OTHER REFERENCE</b></span><br><span><b>' . $gstin_type . '</b>' . $gstin_no . '</span></td>';
            } else {
                $td = '<td colspan="4"><span><b>INVOICE NO. :</b>' . $invoice_no . ' </span><br><span><b>INVOICE DATE. :</b>' . $invoice_date . '</span><br>' . $total_pcs_print . $chargeable_print . '</td>
                <td colspan="4">' . $forwarding_no_print . $awb_no_print . '<span><b>OTHER REFERENCE</b></span><br><span><b>' . $gstin_type . '</b>' . $gstin_no . '</span></td>';
            }

            $tbl = '<style>
                            table {
                                border-collapse: collapse;
                                font-weight: 300;
                                font-family: sans-serif;
                            }
                            table td {
                                border: 1px solid #000;
                                font-size:12px;
                                line-height: 16px;
                                color: #000;
                            }
                    </style>
                    <Table cellpadding="4" cellspacing="0">
                    <tr style="text-align:center;">
                        <td colspan="8"><p style="font-size:18px;">' . $invoice_type . '</p></td>
                    </tr>
                    <tr>
                        ' . $td . '
                    </tr>
                    <tr>
                        <td colspan="4"><span style="font-size:12px;"><b>SHIPPER</b></span></td>
                        <td colspan="4"><span style="font-size:12px;"><b>CONSIGNEE</b></span></td>
                    </tr>
                    <tr>
                        <td colspan="4" style="border-bottom:none;border-top:none;"><span><b>' . $shipper_name . '</b></span><br><span><b>'. $company_title.'</b> :' . $shipper_company_name . '</span><br><span><b>ADDRESS</b> :' . $shipper_address1 . '</span><br><span>' . $shipper_address2 . '</span><br><span>' . $shipper_address3 . '</span><br><span>' . $sendor_city_state . '</span><br><span>' . $sendor_country_pincode . '</span><br><span><b>EMAIL</b> ' . $shipper_email . '</span><br><span><b>PHONE NUMBER</b> : ' . $shipper_phone . '</span></td>  
                        <td colspan="4" style="border-bottom:none;border-top:none;"><span><b>' . $consignee_name . '</b></span><br><span><b>'. $company_title.' :</b>' . $consignee_company_name . '</span><br><span><b>ADDRESS :</b> ' . $consignee_address1 . '</span><br><span>' . $consignee_address2 . '</span><br><span>' . $consignee_address3 . '</span><br><span>' . $receiver_city_state . '</span><br><span>' . $receiver_country_pincode . '</span><br><span><b>EMAIL </b>' . $consignee_email . '</span><br><span><b>PHONE NUMBER :</b> ' . $consignee_phone . $consignee_phone2 . '</span></td>
                    </tr>
                     <tr>
                     <td style="width:6%"><b>SR.<br>NO.</b></td>
                     <td colspan="2" style="width:44%;text-align:center;"><b>DESCRIPTION</b></td>
                     <td style="width:10%; text-align:center;"><b>HS<br>CODE</b></td>
                     <td style="width:10%; text-align:center;"><b>QUANTITY</b></td>
                     <td style="width:10%; text-align:center;"><b>UNIT<br>TYPE</b></td>
                     <td style="width:10%; text-align:center;"><b>UNIT<br>RATES</b></td>
                     <td style="width:10%; text-align:center;"><b>AMOUNT<br> ' . $incoterm . ' </b></td>
                </tr>
                    </table>
                  ';
            $this->writeHTML($tbl, true, false, false, false, '');
        }
    }


    public function Footer()
    {
        //FREE FROM PDF
        if (isset($_GET['pdf_name']) && $_GET['pdf_name'] != '' && $_GET['pdf_name'] == "freeform") {
            // Position at 65 mm from bottom
            $y = (-31);
            $this->SetY($y);

            // footer only in last page
            if ($this->last_page) {

                // $y = (-43);
                // $y = $_GET['freeform']['fbox_y'];
                // $this->SetY($y);

                $notes = $_GET['freeform']['notes'];
                $total_amount = $_GET['freeform']['total_amount'];
                $amt_in_word = strtolower($_GET['freeform']['amt_in_word']);
                $amt_in_word = ucwords($amt_in_word);
                $cur_name = $_GET['freeform']['cur_name'];

                $fbox = '';
                $fbox = $_GET['freeform']['fbox'];

                $tbl = '
                     <style>
                     table {
                        border-collapse: collapse;
                        font-weight: 300;
                        font-family: sans-serif;
                    }
                    table td {
                        border: 1px solid #000;
                        font-size:12px;
                        line-height: 16px;
                        color: #000;
                    }
                     </style>
                    
                    <Table cellpadding="4" cellspacing="0">
                    <tr>
                        <td colspan="2">AMOUNT CHARGEABLE </td>
                        <td colspan="4"><span style="font-size:13.5px;">' . $amt_in_word . '</span> </td>
                        <td colspan="2"><b>TOTAL:</b> ' . $total_amount . ' ' . $cur_name . '</td>
                    </tr>
                    
                    <tr>
                        <td colspan="4"><b>NOTES</b></td>
                        <td colspan="4"><b>SIGNATURE / STAMP</b></td>
                    </tr>
                    <tr>
                        <td colspan="4">' . $notes . '</td>
                        <td colspan="4"><br><br></td>
                    </tr>
                    </table>
                    ';

                $this->writeHTML($tbl, true, false, false, false, '');
            }
        }

         //FREE FROM PDF supreme
         if (isset($_GET['pdf_name']) && $_GET['pdf_name'] != '' && $_GET['pdf_name'] == "freeform_supreme") {
            // Position at 65 mm from bottom
            $y = (-32);
            $this->SetY($y);

            // footer only in last page
            if ($this->last_page) {

                // $y = (-43);
                // $y = $_GET['freeform']['fbox_y'];
                // $this->SetY($y);

                $notes = $_GET['freeform']['notes'];
                $total_amount = $_GET['freeform']['total_amount'];
                $amt_in_word = strtolower($_GET['freeform']['amt_in_word']);
                $amt_in_word = ucwords($amt_in_word);
                $cur_name = $_GET['freeform']['cur_name'];

                $fbox = '';
                $fbox = $_GET['freeform']['fbox'];

                $tbl = '
                     <style>
                     table {
                        border-collapse: collapse;
                        font-weight: 300;
                        font-family: sans-serif;
                    }
                    table td {
                        border: 1px solid #000;
                        font-size:12px;
                        line-height: 16px;
                        color: #000;
                    }
                     </style>
                    
                    <Table cellpadding="4" cellspacing="0">
                    <tr>
                        <td colspan="2">AMOUNT CHARGEABLE </td>
                        <td colspan="4"><span style="font-size:13.5px;">' . $amt_in_word . '</span> </td>
                        <td colspan="2"><b>TOTAL:</b> ' . $total_amount . ' ' . $cur_name . '</td>
                    </tr>
                    
                    <tr>
                        <td colspan="4"><b>NOTES</b></td>
                        <td colspan="4"><b>SIGNATURE / STAMP</b></td>
                    </tr>
                    <tr>
                        <td colspan="4">' . $notes . '</td>
                        <td colspan="4"><br><br></td>
                    </tr>
                    </table>
                    ';

                $this->writeHTML($tbl, true, false, false, false, '');
            }
        }
    }

    public function lastPage($resetmargins = false)
    {
        $this->setPage($this->getNumPages(), $resetmargins);
        $this->last_page = true;
    }
}
