<?php
class Garbage_clean extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function deleteDatabaseBackup($path)
    {
        $files = glob($path . '/*');
        foreach ($files as $file) {
            if (is_dir($file)) {
                $this->deleteDatabaseBackup($file);
            } else {
                $file_created_date = date('Y-m-d', filemtime($file));
                echo "<br>DB=" . $file;
                echo "<br>file_created_date=" . $file_created_date;
                //DONT DELETE TODAY FILE
                if ($file_created_date != date('Y-m-d')) {
                    unlink($file);
                }
            }
        }
        @rmdir($path);
    }
    public function delete_file_temp()
    {

        file_put_contents(FCPATH . 'log1/garbage_cron.txt', date('Y-m-d-H-i-s-a') . "\n", FILE_APPEND);
        $path = FCPATH . '/db_backup_daily/daily';
        $this->deleteDatabaseBackup($path);

        $path = FCPATH . '/db_backup_daily/monthly';
        $this->deleteDatabaseBackup($path);

        $path = FCPATH . '/db_backup_daily/weekly';
        $this->deleteDatabaseBackup($path);

        //DELETE LOG FILE

        $files = glob(FCPATH . '/application/logs/*'); // get all file names
        foreach ($files as $file) { // iterate files
            if (is_file($file)) {
                unlink($file); // delete file
            }
        }


        $dir = FCPATH . '/client_media';
        $files1 = scandir($dir);

        if (isset($files1) && is_array($files1) && count($files1) > 0) {
            foreach ($files1 as $fkey => $fvalue) {

                $dir_arr = explode("_", $fvalue);
                if (in_array('zip', $dir_arr)) {
                    $dirPath = FCPATH . '/client_media/' . $fvalue;
                    echo "<br>" . $dirPath;
                    $this->deleteDir($dirPath);
                } else if ($fvalue == 'temp') {
                    $dirPath = FCPATH . '/client_media/' . $fvalue;
                    echo "<br>" . $dirPath;
                    $this->deleteDir($dirPath);
                } else {
                    if ($dir_arr[0] == 'company' && is_numeric($dir_arr[1])) {

                        $company_dir  = FCPATH . '/client_media/' . $fvalue . '/';
                        $company_files = scandir($company_dir);

                        if (isset($company_files) && is_array($company_files) && count($company_files) > 0) {
                            foreach ($company_files as $cfkey => $cfvalue) {

                                $dir_arr = explode("_", $cfvalue);
                                if (in_array('zip', $dir_arr)) {
                                    $dirPath = $company_dir . $cfvalue;
                                    echo "<br>" . $dirPath;
                                    $this->deleteDir($dirPath);
                                } else if ($cfvalue == 'temp') {
                                    $dirPath = $company_dir . $cfvalue;
                                    echo "<br>" . $dirPath;
                                    $this->deleteDir($dirPath);
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    public function delete_file()
    {
        //DELETE LOG FILE

        $files = glob(FCPATH . '/application/logs/*'); // get all file names
        foreach ($files as $file) { // iterate files
            if (is_file($file)) {
                unlink($file); // delete file
            }
        }

        $dir    = FCPATH . '/client_media';
        $files1 = scandir($dir);

        if (isset($files1) && is_array($files1) && count($files1) > 0) {
            foreach ($files1 as $fkey => $fvalue) {

                $dir_arr = explode("_", $fvalue);
                if (in_array('zip', $dir_arr)) {
                    $dirPath = FCPATH . '/client_media/' . $fvalue;

                    echo "<br>" . $dirPath;
                    $this->deleteDir($dirPath);
                } else if ($fvalue == 'temp') {
                    $dirPath = FCPATH . '/client_media/' . $fvalue;
                    echo "<br>" . $dirPath;
                    $this->deleteDir($dirPath);
                } else {
                    if ($dir_arr[0] == 'company' && is_numeric($dir_arr[1])) {

                        $company_dir  = FCPATH . '/client_media/' . $fvalue . '/';
                        $company_files = scandir($company_dir);

                        if (isset($company_files) && is_array($company_files) && count($company_files) > 0) {
                            foreach ($company_files as $cfkey => $cfvalue) {

                                $dir_arr = explode("_", $cfvalue);
                                if (in_array('zip', $dir_arr)) {
                                    $dirPath = $company_dir . $cfvalue;
                                    echo "<br>" . $dirPath;
                                    $this->deleteDir($dirPath);
                                } else if ($cfvalue == 'temp') {
                                    $dirPath = $company_dir . $cfvalue;
                                    echo "<br>" . $dirPath;
                                    $this->deleteDir($dirPath);
                                } else if (in_array('import', $dir_arr)) {
                                    $dirPath = $company_dir . $cfvalue;
                                    echo "<br>" . $dirPath;
                                    $this->deleteDir($dirPath);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public static function deleteDir($dirPath)
    {
        if (!is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }

        $files = glob($dirPath . '*', GLOB_MARK);
        $dont_delete_dir = 2;
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                $file_created_date = date('Y-m-d', filemtime($file));

                $lastThreeDay = date("Y-m-d", strtotime("-3 days"));

                if ($file_created_date < $lastThreeDay) {
                    echo  "<br>FILE=" . $file;
                    unlink($file);
                } else {
                    $dont_delete_dir = 1;
                }
            }
        }

        if ($dont_delete_dir == 2) {
            @rmdir($dirPath);
        }
    }

    function test()
    {

        $this->load->helper('database_manage');
        $table_list = create_all_table('get_table');
        sort($table_list);

        foreach ($table_list as $key => $table_name) {

            $qry = "ALTER TABLE `" . $table_name . "` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT";
            echo  "<br>" . $qry;
            $this->db->query($qry);
        }
    }
}
