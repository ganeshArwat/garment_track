<?php
function compressImage($source, $destination, $quality)
{

    $info = getimagesize($source);

    if ($info['mime'] == 'image/jpeg')
        $image = imagecreatefromjpeg($source);

    elseif ($info['mime'] == 'image/gif')
        $image = imagecreatefromgif($source);

    elseif ($info['mime'] == 'image/png')
        $image = imagecreatefrompng($source);

    imagejpeg($image, $destination, $quality);
}
function upload_file($file_directory, $file_key)
{
    $return_data = array();
    $ci = &get_instance();
    $fileName = $_FILES[$file_key]["name"];
    $filetmppath = $_FILES[$file_key]['tmp_name'];

    $filename2 = create_year_dir($file_directory);

    // Valid extension
    $valid_ext = array('png', 'jpeg', 'jpg','tiff');
    // Location
    $newfilename = date("Y-m-d-H-i-s") . $_FILES[$file_key]["name"];
    $newfilename = preg_replace('/\s+/', '_', $newfilename);
    $location =  $filename2 . '/' . $newfilename;

    // file extension

    $file_extension = pathinfo($location, PATHINFO_EXTENSION);
    $file_extension = strtolower($file_extension);
    // Check extension
    if (in_array($file_extension, $valid_ext)) {

        // Compress Image
        compressImage($filetmppath, $location, 60);
        $return_data['status'] =  'success';
        $return_data['file_name'] =  $fileName;
        $return_data['file_path'] = $location;
    } else {
        $ci->load->library('upload');
        $filetmppath = $_FILES[$file_key]['tmp_name'];

        $fileName = $newfilename;
        $config['upload_path'] = FCPATH . $filename2;
        $config['allowed_types'] = 'tiff|jpg|jpeg|png|doc|docx|pdf|csv|xlsx|xls|tif';
        $config['file_name'] = $fileName;

        $ci->upload->initialize($config);

        if (!$ci->upload->do_upload($file_key)) {
            $error = array('error' => $ci->upload->display_errors());

            $return_data['status'] =  'failed';
            $return_data['file_name'] =  $error['error'];
        } else {
            $image_data = $ci->upload->data();
            // $image_data['file_name'] = preg_replace('/\s+/', '_', $image_data['file_name']) . date('-Y-m-d-h-i-s');
            $return_data['status'] =  'success';
            $return_data['file_name'] =  $image_data['file_name'];
            $return_data['file_path'] = $filename2 . '/' . $image_data['file_name'];
        }
    }
    return $return_data;
}
function upload_file_old($file_directory, $file_key)
{
    $return_data = array();
    $ci = &get_instance();
    $fileName = $_FILES[$file_key]["name"];
    $filetmppath = $_FILES[$file_key]['tmp_name'];
    if ($fileName != '' && $filetmppath != '') {
        $root_direc = 'client_media/' . $file_directory;

        if (file_exists('client_media') == false) {
            mkdir('client_media', 0777);
        }

        if (file_exists($root_direc) == false) {
            mkdir($root_direc, 0777);
        }

        /**
         * create year month wise directory
         */
        $year = date("Y");
        $month = date("m");
        $filename = $root_direc . '/' . $year;
        $filename2 = $root_direc . '/' . $year . '/' . $month;

        if (file_exists($filename)) {
            if (file_exists($filename2) == false) {
                mkdir($filename2, 0777);
            }
        } else {
            mkdir($filename, 0777);
        }
        if (file_exists($filename)) {
            if (file_exists($filename2) == false) {
                mkdir($filename2, 0777);
            }
        } else {
            mkdir($filename, 0777);
        }

        $ci->load->library('upload');
        $filetmppath = $_FILES[$file_key]['tmp_name'];
        $newfilename = date("Y-m-d-H-i-s") . $_FILES[$file_key]["name"];
        $fileName = $newfilename;
        $config['upload_path'] = FCPATH . $filename2;
        $config['allowed_types'] = 'tiff|jpg|jpeg|png|doc|docx|pdf|csv|xlsx|xls|tif';
        $config['file_name'] = $fileName;

        $ci->upload->initialize($config);

        if (!$ci->upload->do_upload($file_key)) {
            $error = array('error' => $ci->upload->display_errors());
            $return_data['status'] =  'failed';
            $return_data['file_name'] =  $error['error'];
        } else {
            $image_data = $ci->upload->data();
            $return_data['status'] =  'success';
            $return_data['file_name'] =  $image_data['file_name'];
            $return_data['file_path'] = $filename2 . '/' . $image_data['file_name'];
        }
    }
    return $return_data;
}
