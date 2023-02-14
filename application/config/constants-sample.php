<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
  |--------------------------------------------------------------------------
  | Display Debug backtrace
  |--------------------------------------------------------------------------
  |
  | If set to TRUE, a backtrace will be displayed along with php errors. If
  | error_reporting is disabled, the backtrace will not display, regardless
  | of this setting
  |
 */
defined('SHOW_DEBUG_BACKTRACE') or define('SHOW_DEBUG_BACKTRACE', true);

/*
  |--------------------------------------------------------------------------
  | File and Directory Modes
  |--------------------------------------------------------------------------
  |
  | These prefs are used when checking and setting modes when working
  | with the file system.  The defaults are fine on servers with proper
  | security, but you may wish (or even need) to change the values in
  | certain environments (Apache running a separate process for each
  | user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
  | always be used to set the mode correctly.
  |
 */
defined('FILE_READ_MODE') or define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') or define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE') or define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE') or define('DIR_WRITE_MODE', 0755);

/*
  |--------------------------------------------------------------------------
  | File Stream Modes
  |--------------------------------------------------------------------------
  |
  | These modes are used when working with fopen()/popen()
  |
 */
defined('FOPEN_READ') or define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE') or define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE') or define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESCTRUCTIVE') or define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE') or define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE') or define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT') or define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT') or define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
  |--------------------------------------------------------------------------
  | Exit Status Codes
  |--------------------------------------------------------------------------
  |
  | Used to indicate the conditions under which the script is exit()ing.
  | While there is no universal standard for error codes, there are some
  | broad conventions.  Three such conventions are mentioned below, for
  | those who wish to make use of them.  The CodeIgniter defaults were
  | chosen for the least overlap with these conventions, while still
  | leaving room for others to be defined in future versions and user
  | applications.
  |
  | The three main conventions used for determining exit status codes
  | are as follows:
  |
  |    Standard C/C++ Library (stdlibc):
  |       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
  |       (This link also contains other GNU-specific conventions)
  |    BSD sysexits.h:
  |       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
  |    Bash scripting:
  |       http://tldp.org/LDP/abs/html/exitcodes.html
  |
 */
defined('EXIT_SUCCESS') or define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR') or define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG') or define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE') or define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS') or define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') or define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT') or define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE') or define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN') or define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX') or define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code


/*
  |--------------------------------------------------------------------------
  | Define css, js image and media path
  |--------------------------------------------------------------------------
  | for frontend , backend
  |
  |
 */
//define('BASE_URL', 'http://101.53.135.30/');
$localhost_ip = array(
  '127.0.0.1',
  '::1'
);
if (in_array($_SERVER['REMOTE_ADDR'], $localhost_ip)) {
  define('BASE_URL', 'http://localhost:8015/courier_new/');
} else {
  define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/');
}
define('CSS_PATH_FRONTEND', BASE_URL . 'css/frontend/'); // css path
define('JS_PATH_FRONTEND', BASE_URL . 'js/frontend/'); // js path
define('IMAGE_PATH_FRONTEND', BASE_URL . 'images/'); // image path
define('MEDIA_PATH_FRONTEND', BASE_URL . 'media/'); // media path

define('CSS_PATH_BACKEND', BASE_URL . 'css/backend/'); // css path
define('JS_PATH_BACKEND', BASE_URL . 'js/backend/'); // js path
define('IMAGE_PATH_BACKEND', BASE_URL . 'images/backend/'); // image path
define('MEDIA_PATH_BACKEND', BASE_URL . 'media/backend/'); // media path

define('WEBSITE_NAME', 'ITD Services');
define('PER_PAGE', '20');
//define('DATETIME_FORMAT', 'd M,Y h:i a');
define('DATETIME_FORMAT', 'd/m/Y h:i a');
define('DATE_FORMAT', 'd/m/Y');
define('LOGIN_VALIDITY_DAYS', '30');
define('PDF_LIB_PATH', 'C:\Program Files\wkhtmltopdf\bin\wkhtmltopdf');
define('DATE_INPUT_FORMAT', 'Y-m-d');
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'TrackmateLite#fb5421');
define('INFINITE_DATE', '2999-12-31');
define('PDF_LIB_LOCATION', '/var/www/html/garment_track_theme/wkhtmltopdf');
define('DEFAULT_EXPIRY_DATE', '3000-12-31');
define('LONG_VALID_TILL_DATE', '3000-12-31');
