<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function var_validate($data, $type)
{
    $CI = &get_instance();
    if ($type == 2) { //array
        if (isset($data) && is_array($data) && count($data) > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    } else if ($type == 1) { // for variable string
        if (isset($data) && !empty($data) && $data != NULL && $data != '') {
            return TRUE;
        } else {
            return FALSE;
        }
    } else if ($type == 3) { //for date
        $data = str_replace('/', '-', $data);
        $date_to_s = 0;
        if (isset($data)) {
            $date_to_s = strtotime($data);
        }

        return (isset($date_to_s) && !!$date_to_s && $date_to_s > 0);
    } else {
        return FALSE;
    }
}
