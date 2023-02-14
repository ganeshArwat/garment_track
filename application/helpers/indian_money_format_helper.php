
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function indian_money_format($num) {
    $num= round($num);
    $len = strlen($num);
    $m = '';
    $num = strrev($num);
    for ($i = 0; $i < $len; $i++) {
        if (( $i == 3 || ($i > 3 && ($i - 1) % 2 == 0) ) && $i != $len) {
            $m .= ',';
        }
        $m .= $num[$i];
    }
    return strrev($m);
}

?>