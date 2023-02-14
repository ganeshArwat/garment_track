<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

function barcode_print($str) {//barcode_print($str, $type)     
    $CI = &get_instance();
    $CI->load->library('zend');
    $CI->zend->load('Zend/Barcode');

    //$file = Zend_Barcode::draw('code39', 'image', array('text' => $str, 'drawText' => false), array());// to save as image
    //$file = Zend_Barcode::render('code39', 'image', array('text' => $str, 'drawText' => false), array());
    $file = Zend_Barcode::factory('code39', 'image', array('text' => $str, 'drawText' => false), array())->render();
    echo $file;

//    if (!file_exists('./media/barcode')) {
//        mkdir('./media/barcode', 0777, true);
//    }
//    if ($type == 1) {// for cd
//        if (!file_exists('./media/barcode/cd')) {
//            mkdir('./media/barcode/cd', 0777, true);
//        }
//        imagepng($file, "./media/barcode/cd/{$str}.png");
//        return "/media/barcode/cd/" . $str . '.png';
//    } elseif ($type == 2) {// for ref no
//        if (!file_exists('./media/barcode/ref')) {
//            mkdir('./media/barcode/ref', 0777, true);
//        }
//        imagepng($file, "./media/barcode/ref/{$str}.png");
//        return "/media/barcode/ref/" . $str . '.png';
//    } else {
//        return false;
//    }
}
