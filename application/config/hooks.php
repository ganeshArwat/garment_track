<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/

// $hook['pre_controller'][0] = array(
//     'class' => 'Database_connect',
//     'function' => 'get_company_db',
//     'filename' => 'Database_connect.php',
//     'filepath' => 'hooks',
// );

$hook['pre_controller'] = array(
    'class'    => 'Access_check_script',
    'function' => 'check_permission',
    'filename' => 'Access_check_script.php',
    'filepath' => 'hooks'
);


$hook['post_system'] = array(
    'class' => 'Log_queries',
    'function' => 'query_display',
    'filename' => 'Log_queries.php',
    'filepath' => 'hooks',
);
