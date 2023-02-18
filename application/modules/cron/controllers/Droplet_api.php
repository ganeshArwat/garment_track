<?php
class Droplet_api extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    function reboot_api($node_id = '')
    {
        $api_key = "8cda4c9b-5962-4332-b5a3-636a0dbcc8da";
        $access_token = "eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJGSjg2R2NGM2pUYk5MT2NvNE52WmtVQ0lVbWZZQ3FvcXRPUWVNZmJoTmxFIn0.eyJleHAiOjE2ODk3NTgxMTcsImlhdCI6MTY1ODIyMjExNywianRpIjoiOWI3Yjg1YTUtZGU4My00MDUxLWE5ZmItZDM4ODZlNTliMjgxIiwiaXNzIjoiaHR0cDovL2dhdGV3YXkuZTJlbmV0d29ya3MuY29tL2F1dGgvcmVhbG1zL2FwaW1hbiIsImF1ZCI6ImFjY291bnQiLCJzdWIiOiI5YTBhOWNkOS02ODEyLTRlYWItYmM1My0zMTc5YjhlMWIxZjQiLCJ0eXAiOiJCZWFyZXIiLCJhenAiOiJhcGltYW51aSIsInNlc3Npb25fc3RhdGUiOiJhZDM0YTNhNi0yM2MxLTQ3ZmItYWE4NC0wOWUwYzZjMzNlMzMiLCJhY3IiOiIxIiwiYWxsb3dlZC1vcmlnaW5zIjpbIiJdLCJyZWFsbV9hY2Nlc3MiOnsicm9sZXMiOlsib2ZmbGluZV9hY2Nlc3MiLCJ1bWFfYXV0aG9yaXphdGlvbiIsImFwaXVzZXIiLCJkZWZhdWx0LXJvbGVzLWFwaW1hbiJdfSwicmVzb3VyY2VfYWNjZXNzIjp7ImFjY291bnQiOnsicm9sZXMiOlsibWFuYWdlLWFjY291bnQiLCJtYW5hZ2UtYWNjb3VudC1saW5rcyIsInZpZXctcHJvZmlsZSJdfX0sInNjb3BlIjoicHJvZmlsZSBlbWFpbCIsInNpZCI6ImFkMzRhM2E2LTIzYzEtNDdmYi1hYTg0LTA5ZTBjNmMzM2UzMyIsImVtYWlsX3ZlcmlmaWVkIjpmYWxzZSwibmFtZSI6IlZpcmFnIERvc2hpIiwicHJlZmVycmVkX3VzZXJuYW1lIjoidmlyYWdAaXRkc2VydmljZXMuaW4iLCJnaXZlbl9uYW1lIjoiVmlyYWciLCJmYW1pbHlfbmFtZSI6IkRvc2hpIiwiZW1haWwiOiJ2aXJhZ0BpdGRzZXJ2aWNlcy5pbiJ9.GdLy5ukexAN-7UYmC3naEcSyJWx4BsXn38DgyCZ1utzNhDH3BVNewWeBOrxRydWPB1tXY_s_z4j6l1iMqeXDUifOYTLCUMaOuFQ37OoyfDFuZAoyKUOAdqIKB0rRPWZrYlav6GN8ETs9mqTELYXzPSyDIjX6ghL7e6J7iLY7Ukc";
        if ($node_id != '') {
            $url = 'https://api.e2enetworks.com/myaccount/api/v1/nodes/' . $node_id . '/actions/?apikey=' . $api_key;

            $request_data['type'] = "reboot";
            $request_json = json_encode($request_data);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,  $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request_json);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Accept: application/json',
                'Authorization: Bearer ' . $access_token
            ));
            $response_json = curl_exec($ch);

            //echo $response_json;

            $file_data = date('Y-m-d H:i:s') . "\n";
            $file_data .= $url . "\n";
            $file_data .= $response_json . "\n\n\n";
            file_put_contents('log1/reboot_log.txt', $file_data, FILE_APPEND);
            curl_close($ch);
        }
    }

    function download_backup()
    {
        // $DBUSER = "root";
        // $DBPASSWD = "";
        // $DATABASE = "trackmate_lite";

        // $filename = "backup-" . $DATABASE . date("d-m-Y") . ".sql";
        // $mime = "application/x-gzip";

        // header("Content-Type: " . $mime);
        // header('Content-Disposition: attachment; filename="' . $filename . '"');

        // //$cmd = "mysqldump -u $DBUSER -p $DBPASSWD $DATABASE | zip --best";

        // //$cmd = "mysqldump -u $DBUSER -p '$DBPASSWD' $DATABASE < " . $filename;
        // $cmd = "mysqldump -u $DBUSER -p --all-databases > all_db_backup.sql";
        // exec($cmd);
        // exit(0);


        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        $database = 'trackmate_lite';
        $user = 'root';
        $pass = '';
        $host = 'localhost:3310';
        $dir = dirname(__FILE__) . '/dump.sql';

        $filename = "backup-" . $database . date("d-m-Y") . ".sql";
        $mime = "application/text";
        header("Content-Type: " . $mime);
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        //$command = "mysqldump --user={$user} --password={$pass} --host={$host} {$database} --result-file={$dir} 2>&1";
        $command = "mysqldump -u $user -p {$database} > {filename}";
        exec($command);



        exit(0);

        // exec($command, $output);

        // var_dump($output);
    }

    function test()
    {
        $dbhost = DB_HOSTNAME;
        $dbuser = DB_USERNAME;
        $dbpass = DB_PASSWORD;
        $dbname = 'trackmate_lite';
        $backup_file = $dbname . date("Y-m-d-H-i-s") . '.gz';
        $command = "mysqldump --opt -h $dbhost -u $dbuser -p $dbpass " . $dbname . " | gzip > $backup_file";

        exec($command, $output);
        var_dump($output);
    }


    function db_backup_old($company_id = 0)
    {
        $this->load->dbutil();

        $database_name = "trackmate_company_" . $company_id;

        if ($this->dbutil->database_exists($database_name)) {
            $prefs = array(
                'format' => 'zip',
                'filename' => 'my_db_backup.sql'
            );

            $backup = $this->dbutil->backup($prefs);
            $db_name = $database_name . '-' . date("Y-m-d-h-i-sa") . '.zip';
            //$save = 'db_backup/' . $db_name;

            // $this->load->helper('file');
            // write_file($save, $backup);

            $this->load->helper('download');
            force_download($db_name, $backup);
        } else {
            echo "Company Database not exist";
        }
    }


    function db_backup($company_id = 0)
    {
        $this->load->dbutil();

        $database_name = "trackmate_company_" . $company_id;

        if ($this->dbutil->database_exists($database_name)) {
            $prefs = array(
                'format' => 'zip',
                'filename' => 'my_db_backup.sql'
            );

            //$backup = $this->dbutil->backup($prefs);


            $tables = $this->db->list_tables();
            $ignore = array();
            $newline = "\n";
            $add_drop = FALSE;
            $foreign_key_checks = TRUE;
            $format = 'gzip';
            $add_insert = TRUE;
            $file = $database_name . '-' . date("Y-m-d-h-i-sa") . '.zip';
            $txt = fopen('php://memory', 'w');
            //$txt = fopen($file, "w") or die("Unable to open file!");


            foreach ((array) $tables as $table) {
                // Is the table in the "ignore" list?
                if (in_array($table, (array) $ignore, TRUE)) {
                    continue;
                }

                // Get the table schema
                $query = $this->db->query('SHOW CREATE TABLE ' . $this->db->escape_identifiers($this->db->database . '.' . $table));

                // No result means the table name was invalid
                if ($query === FALSE) {
                    continue;
                }

                // Write out the table schema
                $file_line = '#' . $newline . '# TABLE STRUCTURE FOR: ' . $table . $newline . '#' . $newline . $newline;
                fwrite($txt, gzencode($file_line));

                if ($add_drop === TRUE) {
                    $file_line = 'DROP TABLE IF EXISTS ' . $this->db->protect_identifiers($table) . ';' . $newline . $newline;
                    fwrite($txt, gzencode($file_line));
                }

                $i = 0;
                $result = $query->result_array();
                foreach ($result[0] as $val) {
                    if ($i++ % 2) {
                        $file_line = $val . ';' . $newline . $newline;
                        fwrite($txt, gzencode($file_line));
                    }
                }

                // If inserts are not needed we're done...
                if ($add_insert === FALSE) {
                    continue;
                }


                $limit = 25000;
                $offset = 0;

                // Grab all the data from the current table
                while ($offset >= 0) {
                    $select_qry = 'SELECT * FROM ' . $this->db->protect_identifiers($table) . ' LIMIT ' . $limit . ' OFFSET ' . $offset;
                    $query = $this->db->query($select_qry);
                    if ($query->num_rows() === 0) {
                        $offset = -1;
                        continue;
                    } else {
                        $offset = $offset + $limit;
                    }

                    // Fetch the field names and determine if the field is an
                    // integer type. We use this info to decide whether to
                    // surround the data with quotes or not

                    $i = 0;
                    $field_str = '';
                    $is_int = array();
                    while ($field = $query->result_id->fetch_field()) {
                        // Most versions of MySQL store timestamp as a string
                        $is_int[$i] = in_array($field->type, array(MYSQLI_TYPE_TINY, MYSQLI_TYPE_SHORT, MYSQLI_TYPE_INT24, MYSQLI_TYPE_LONG), TRUE);

                        // Create a string of field names
                        $field_str .= $this->db->escape_identifiers($field->name) . ', ';
                        $i++;
                    }

                    // Trim off the end comma
                    $field_str = preg_replace('/, $/', '', $field_str);

                    // Build the insert string
                    foreach ($query->result_array() as $row) {
                        $val_str = '';

                        $i = 0;
                        foreach ($row as $v) {
                            // Is the value NULL?
                            if ($v === NULL) {
                                $val_str .= 'NULL';
                            } else {
                                // Escape the data if it's not an integer
                                $val_str .= ($is_int[$i] === FALSE) ? $this->db->escape($v) : $v;
                            }

                            // Append a comma
                            $val_str .= ', ';
                            $i++;
                        }

                        // Remove the comma at the end of the string
                        $val_str = preg_replace('/, $/', '', $val_str);

                        // Build the INSERT string
                        $file_line = 'INSERT INTO ' . $this->db->protect_identifiers($table) . ' (' . $field_str . ') VALUES (' . $val_str . ');' . $newline;
                        fwrite($txt, gzencode($file_line));
                    }
                }

                $file_line = $newline . $newline;
                fwrite($txt, gzencode($file_line));
            }
            //fclose($txt);

            fseek($txt, 0);
            header("Content-Type: application/x-gzip");
            header('Content-Disposition: attachment; filename="' . $file . '"');
            // make php send the generated csv lines to the browser
            fpassthru($txt);
            fclose($txt);

            // header('Content-Description: File Transfer');
            // header('Content-Disposition: attachment; filename=' . basename($file));
            // header('Expires: 0');
            // header('Cache-Control: must-revalidate');
            // header('Pragma: public');
            // header('Content-Length: ' . filesize($file));
            // header("Content-Type: application/x-gzip");
            // readfile($file);

            //$db_name = $database_name . '-' . date("Y-m-d-h-i-sa") . '.zip';
            //$save = 'db_backup/' . $db_name;

            // $this->load->helper('file');
            // write_file($save, $backup);

            // $this->load->helper('download');
            // force_download($db_name, $backup);
        } else {
            echo "Company Database not exist";
        }
    }
}
