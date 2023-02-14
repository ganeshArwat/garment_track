
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('convert_number_to_words')) {

    function convert_number_to_words($number)
    {

        $decimal = round($number - ($no = floor($number)), 2) * 100;
        $hundred = null;
        $digits_length = strlen($no);
        $i = 0;
        $str = array();
        $words = array(
            0 => '',
            1 => 'one',
            2 => 'two',
            3 => 'three',
            4 => 'four',
            5 => 'five',
            6 => 'six',
            7 => 'seven',
            8 => 'eight',
            9 => 'nine',
            10 => 'ten',
            11 => 'eleven',
            12 => 'twelve',
            13 => 'thirteen',
            14 => 'fourteen',
            15 => 'fifteen',
            16 => 'sixteen',
            17 => 'seventeen',
            18 => 'eighteen',
            19 => 'nineteen',
            20 => 'twenty',
            30 => 'thirty',
            40 => 'forty',
            50 => 'fifty',
            60 => 'sixty',
            70 => 'seventy',
            80 => 'eighty',
            90 => 'ninety'
        );
        $paisewords = array(
            0 => 'zero',
            1 => 'one',
            2 => 'two',
            3 => 'three',
            4 => 'four',
            5 => 'five',
            6 => 'six',
            7 => 'seven',
            8 => 'eight',
            9 => 'nine',
            10 => 'ten',
            11 => 'eleven',
            12 => 'twelve',
            13 => 'thirteen',
            14 => 'fourteen',
            15 => 'fifteen',
            16 => 'sixteen',
            17 => 'seventeen',
            18 => 'eighteen',
            19 => 'nineteen',
            20 => 'twenty',
            30 => 'thirty',
            40 => 'forty',
            50 => 'fifty',
            60 => 'sixty',
            70 => 'seventy',
            80 => 'eighty',
            90 => 'ninety'
        );
        $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
        while ($i < $digits_length) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += $divider == 10 ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str[] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural . ' ' . $hundred : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural . ' ' . $hundred;
            } else
                $str[] = null;
        }
        $Rupees = implode('', array_reverse($str));
        $paise = ($decimal) ? ". " . ($paisewords[$decimal / 10] . " " . $paisewords[$decimal % 10]) . ' Paise' : '';

        $Rupees = ucwords($Rupees);
        $paise = ucwords($paise);

        return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise;

        /* $hyphen = ' ';
      $conjunction = ' and ';
      $separator = ', ';
      $negative = 'negative ';
      $decimal = ' point ';
      $dictionary = array(
      0 => 'zero',
      1 => 'one',
      2 => 'two',
      3 => 'three',
      4 => 'four',
      5 => 'five',
      6 => 'six',
      7 => 'seven',
      8 => 'eight',
      9 => 'nine',
      10 => 'ten',
      11 => 'eleven',
      12 => 'twelve',
      13 => 'thirteen',
      14 => 'fourteen',
      15 => 'fifteen',
      16 => 'sixteen',
      17 => 'seventeen',
      18 => 'eighteen',
      19 => 'nineteen',
      20 => 'twenty',
      30 => 'thirty',
      40 => 'fourty',
      50 => 'fifty',
      60 => 'sixty',
      70 => 'seventy',
      80 => 'eighty',
      90 => 'ninety',
      100 => 'hundred',
      1000 => 'thousand',
      1000000 => 'million',
      1000000000 => 'billion',
      1000000000000 => 'trillion',
      1000000000000000 => 'quadrillion',
      1000000000000000000 => 'quintillion'
      );

      if (!is_numeric($number)) {
      return false;
      }

      if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
      // overflow
      trigger_error(
      'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX, E_USER_WARNING
      );
      return false;
      }

      if ($number < 0) {
      return $negative . convert_number_to_words(abs($number));
      }

      $string = $fraction = null;

      if (strpos($number, '.') !== false) {
      list($number, $fraction) = explode('.', $number);
      }

      switch (true) {
      case $number < 21:
      $string = $dictionary[$number];
      break;
      case $number < 100:
      $tens = ((int) ($number / 10)) * 10;
      $units = $number % 10;
      $string = $dictionary[$tens];
      if ($units) {
      $string .= $hyphen . $dictionary[$units];
      }
      break;
      case $number < 1000:
      $hundreds = $number / 100;
      $remainder = $number % 100;
      $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
      if ($remainder) {
      $string .= $conjunction . convert_number_to_words($remainder);
      }
      break;
      default:
      $baseUnit = pow(1000, floor(log($number, 1000)));
      $numBaseUnits = (int) ($number / $baseUnit);
      $remainder = $number % $baseUnit;
      $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
      if ($remainder) {
      $string .= $remainder < 100 ? $conjunction : $separator;
      $string .= convert_number_to_words($remainder);
      }
      break;
      }

      if (null !== $fraction && is_numeric($fraction)) {
      $string .= $decimal;
      $words = array();
      foreach (str_split((string) $fraction) as $number) {
      $words[] = $dictionary[$number];
      }
      $string .= implode(' ', $words);
      }

      return $string; */
    }
}

if (!function_exists('datediff')) {

    function datediff($date1, $date2)
    {
        $diff = abs(strtotime($date1) - strtotime($date2));

        return sprintf(
            "%d Days, %d Hours, %d Mins, %d Seconds",
            intval($diff / 86400),
            intval(($diff % 86400) / 3600),
            intval(($diff / 60) % 60),
            intval($diff % 60)
        );
    }
}



if (!function_exists('convert_number_to_words_decimal')) {
    function convert_number_to_words_decimal($number)
    {
        if (isset($number)) {
            $no = floor($number);
            $point = round($number - $no, 2) * 100;
            $hundred = null;
            $digits_1 = strlen($no);
            $i = 0;
            $j = 0;
            $str = array();
            $words = array(
                '0' => '', '1' => 'ONE', '2' => 'TWO',
                '3' => 'THREE', '4' => 'FOUR', '5' => 'FIVE', '6' => 'SIX',
                '7' => 'SEVEN', '8' => 'EIGHT', '9' => 'NINE',
                '10' => 'TEN', '11' => 'ELEVEN', '12' => 'TWELVE',
                '13' => 'THIRTEEN', '14' => 'FOURTEEN',
                '15' => 'FIFTEEN', '16' => 'SIXTEEN', '17' => 'SEVENTEEN',
                '18' => 'EIGHTEEN', '19' => 'NINETEEN', '20' => 'TWENTY',
                '30' => 'THIRTY', '40' => 'FORTY', '50' => 'FIFTY',
                '60' => 'SIXTY', '70' => 'SEVENTY',
                '80' => 'EIGHTY', '90' => 'NINETY'
            );
            $digits = array('', 'HUNDRED', 'THOUSAND', 'LAKH', 'CRORE', 'ARAB', 'KHARAB');
            while ($i < $digits_1) {
                $divider = ($i == 2) ? 10 : 100;
                $number = floor($no % $divider);
                $no = floor($no / $divider);
                $i += ($divider == 10) ? 1 : 2;
                if ($number) {
                    $plural = (($counter = count($str)) && $number > 9) ? '' : null;
                    $hundred = ($counter == 1 && $str[0]) ? ' AND ' : null;
                    $str[] = ($number < 21) ? $words[$number] .
                        " " . $digits[$counter] . $plural . " " . $hundred :
                        $words[floor($number / 10) * 10]
                        . " " . $words[$number % 10] . " "
                        . $digits[$counter] . $plural . " " . $hundred;
                } else
                    $str[] = null;
            }
            $str = array_reverse($str);
            $result = implode('', $str);
            //decimal words
            $digits_2 = strlen($point);

            $decimalwords = array(
                '0' => '', '1' => 'ONE', '2' => 'TWO',
                '3' => 'THREE', '4' => 'FOUR', '5' => 'FIVE', '6' => 'SIX',
                '7' => 'SEVEN', '8' => 'EIGHT', '9' => 'NINE',
                '10' => 'TEN', '11' => 'ELEVEN', '12' => 'TWELVE',
                '13' => 'THIRTEEN', '14' => 'FOURTEEN',
                '15' => 'FIFTEEN', '16' => 'SIXTEEN', '17' => 'SEVENTEEN',
                '18' => 'EIGHTEEN', '19' => 'NINETEEN', '20' => 'TWENTY',
                '30' => 'THIRTY', '40' => 'FORTY', '50' => 'FIFTY',
                '60' => 'SIXTY', '70' => 'SEVENTY',
                '80' => 'EIGHTY', '90' => 'NINETY'
            );
            $decimaldigits = array('', 'HUNDRED', 'THOUSAND', 'LAKH', 'CRORE', 'ARAB', 'KHARAB');
            $pointsstr = array();
            while ($j < $digits_2) {
                $pointdivider = ($j == 2) ? 10 : 100;
                $point_number = floor($point % $pointdivider);
                $point = floor($point / $pointdivider);
                $j += ($pointdivider == 10) ? 1 : 2;
                if ($point_number) {
                    $point_plural = (($point_counter = count($pointsstr)) && $point_number > 9) ? '' : null;
                    $point_hundred = ($point_counter == 1 && $pointsstr[0]) ? ' AND ' : null;
                    $pointsstr[] = ($point_number < 21) ? $words[$point_number] .
                        " " . $decimaldigits[$point_counter] . $point_plural . " " . $point_hundred :
                        $words[floor($point_number / 10) * 10]
                        . " " . $words[$point_number % 10] . " "
                        . $decimaldigits[$point_counter] . $point_plural . " " . $point_hundred;
                } else
                    $pointsstr[] = null;
            }
            $pointsstr = array_reverse($pointsstr);
            $decimalresult = implode('', $pointsstr);
            /*$points = ($point) ?
                $decimalwords[$point / 10] . " " .
                $decimalwords[$point = $point % 10] : '';*/

            $part2 = isset($decimalresult) && $decimalresult != '' ? "AND " . $decimalresult . " PAISE" : "";

            return $result . "RUPEES  " . $part2;
        } else {
            return;
        }
    }
}

?>