<?php
require_once FCPATH . '/phpwkhtmltopdf-master/src/BaseCommand.php';
require_once FCPATH . '/phpwkhtmltopdf-master/src/File.php';
$path2 = FCPATH . '/phpwkhtmltopdf-master/src/Command.php';

require_once($path2);
require_once FCPATH . '/phpwkhtmltopdf-master/src/Image.php';
require_once FCPATH . '/phpwkhtmltopdf-master/src/Pdf.php';

use mikehaertl\wkhtmlto\Pdf;

function html_to_pdf($path, $view_name, $data, $file_name, $options = array())
{
    $CI = &get_instance();
    $pdfdata['pdf_print']['filename'] = $file_name . ".pdf";
    $pdfdata['pdf_print']['filepath'] = $path . "/" . $file_name . ".pdf";
    $pdfdata['pdf_print']['pdfdata'] = $CI->load->view($view_name, $data, true);

    foreach ($pdfdata as $key => $value) {
        unset($pdf);
        $pdf = new Pdf($pdfdata[$key]['pdfdata']);
        //$pdf->binary = '/var/www/html/garment_track_/wkhtmltopdf';
        //$pdf->binary = 'C:\Program Files\wkhtmltopdf\bin\wkhtmltopdf';
        $pdf->binary = PDF_LIB_LOCATION;
        $pdf->setOptions($options);
        /*$pdf->setOptions(array(
            'margin-top' => 0,
            'margin-right' => 0,
            'margin-bottom' => 0,
            'margin-left' => 0,
            //'zoom' => 0.9,
            //"page-width" => 211,
            'page-size' => 'A4'
            //'disable-smart-shrinking'=>TRUE
            //'scale' => 1.95
            //"orientation" => "landscape"
        ));*/

        if (!$pdf->saveAs($pdfdata[$key]['filepath'])) {
            $pdfdata[$key]['error'] = $pdf->getError();
        }
    }
    $pdf = reset($pdfdata);
    if ($pdf['error']) {
        echo $pdf['error'];
        die();
    }

    return   $path . $file_name . ".pdf";
    exit;
}
