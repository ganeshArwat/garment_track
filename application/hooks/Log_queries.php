<?php

class Log_queries
{

    function query_display()
    {

        $CI = &get_instance();
        if (isset($_GET['mode']) && $_GET['mode'] == 'test') {
            $CI = &get_instance();
            $times = $CI->db->query_times;

            $data = array();
            foreach ($CI->db->queries as $key => $query) {
                $data[$key][0] = $query;
                $data[$key][1] = $times[$key];
            }
            echo '<pre>';
            print_r($data);
        }
    }
}
