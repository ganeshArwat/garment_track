<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * CSVReader Class
 *
 * $Id: csvreader.php 147 2007-07-09 23:12:45Z Pierre-Jean $
 *
 * Allows to retrieve a CSV file content as a two dimensional array.
 * The first text line shall contains the column names.
 *
 * Let's consider the following CSV formatted data:
 *
 *        col1;col2;col3
 *         11;12;13
 *         21;22;23
 *
 * It's returned as follow by the parsing operations:
 *
 *         Array(
 *             [0] => Array(
 *                     [col1] => 11,
 *                     [col2] => 12,
 *                     [col3] => 13
 *             )
 *             [1] => Array(
 *                     [col1] => 21,
 *                     [col2] => 22,
 *                     [col3] => 23
 *             )
 *        )
 *
 * @author        Pierre-Jean Turpeau
 * @link        http://www.codeigniter.com/wiki/CSVReader
 */
class CSVReader
{

    var $fields;
    /** columns names retrieved after parsing */
    var $separator = ';';
    /** separator used to explode each line */
    var $path = 'd:/';
    var $extn = '.csv';
    var $feild_name = array();
    var $enclosure = '"';
    var $max_row_size;

    /**
     * Constructor
     *
     * @access    public
     * @param    array    initialization parameters
     */
    function CSVReader($params = array())
    {
        //$CI =& get_instance();

        if (count($params) > 0) {
            $this->initialize($params);
        }

        log_message('debug', "CSVReader Class Initialized");
    }

    // --------------------------------------------------------------------

    /**
     * Initialize Preferences
     *
     * @access    public
     * @param     array    initialization parameters
     * @return    void
     */
    function initialize($params = array())
    {
        if (count($params) > 0) {
            foreach ($params as $key => $val) {
                if (isset($this->$key)) {
                    $this->$key = $val;
                }
            }
        }
    }

    // ------------------------------------------------------------------------

    /**
     * Parse a text containing CSV formatted data.
     *
     * @access    public
     * @param    string
     * @return    array
     */
    function parse_text($p_Text)
    {
        $lines = explode("\n", $p_Text);
        return $this->parse_lines($lines);
    }

    // ------------------------------------------------------------------------

    /**
     * Parse a file containing CSV formatted data.
     *
     * @access    public
     * @param    string
     * @return    array
     */
    function parse_file($p_Filepath)
    {
        $lines = file($p_Filepath);

        return $this->parse_lines($lines);
    }

    // ------------------------------------------------------------------------

    /**
     * Parse an array of text lines containing CSV formatted data.
     *
     * @access    public
     * @param    array
     * @return    array
     */
    function parse_lines($p_CSVLines)
    {
        $content = TRUE;
        $column_name = array();
        foreach ($p_CSVLines as $line_num => $line) {
            if ($line != '') { // skip empty lines
                $elements = explode($this->separator, $line);

                if (!is_array($content)) { // the first line contains fields names
                    foreach ($elements as $feild_names) {
                        if (in_array($feild_names, $this->feild_name))
                            $column_name[] = $feild_names;
                        else
                            $column_name = $this->feild_name;
                    }

                    //$this->fields = $elements;
                    $this->fields = $column_name;
                    $content = array();
                } else {
                    $item = array();
                    foreach ($this->fields as $id => $field) {
                        if (isset($elements[$id])) {
                            $item[trim($field)] = $elements[$id];
                        }
                    }
                    $content[] = $item;
                }
            }
        }
        return $content;
    }

    /**
     * Parse a file containing CSV formatted data.
     *
     * @access    public
     * @param    string
     * @param    boolean
     * @return    array
     */
    function parse_file_new($p_Filepath, $p_NamedFields = true)
    {
        $content = false;
        $file = fopen($p_Filepath, 'r');

        /* To remove empty space from cell of csv array_filter is used */
        if ($p_NamedFields) {
            $this->fields = array_filter(fgetcsv($file, $this->max_row_size, $this->separator, $this->enclosure));
        }

        while (($row = fgetcsv($file, $this->max_row_size, $this->separator, $this->enclosure)) != false) {

            //if ($row[0] != null) { // skip empty lines
            if (!$content) {
                $content = array();
            }

            if ($p_NamedFields) {
                $items = array();

                // I prefer to fill the array with values of defined fields

                foreach ($this->fields as $id => $field) {
                    if (isset($row[$id])) {
                        $label_key = strtolower(trim($field));
                        $label_key = str_replace(" ", "_", $label_key);
                        $row[$id] = trim($row[$id]);

                        $row[$id] = utf8_encode($row[$id]);

                        $items[$label_key] = $row[$id];
                    }
                }
                $content[] = $items;
            } else {
                $content[] = $row;
            }
            //}
        }
        fclose($file);
        return $content;
    }

    // ------------------------------------------------------------------------

    /**
     * Array to CSV
     *
     * @access    public
     * @param    array
     * @param    string
     * @return    void
     * download == "" -> return CSV string
     * download == "toto.csv" -> download file toto.csv
     * 
     * Example: array_to_csv($array); or array_to_csv($array, 'toto.csv');
     */
    function array_to_csv($array, $download = "")
    {

        // store the csv file as a report in the system
        $f = fopen($this->path . $download . $this->extn, 'w') or show_error("Can't open php://output");
        $n = 0;
        foreach ($array as $line) {
            $n++;
            if (!fputcsv($f, $line)) {
                show_error("Can't write line $n: $line");
            }
        }
        fclose($f) or show_error("Can't close php://output");

        // get all the contents from the file and put it into a variable
        ob_start();
        include($this->path . $download . $this->extn);
        $str = ob_get_contents();
        ob_end_clean();

        if ($download == "") {
            return $str;
        } else {
            header('Content-Type: application/csv');
            header('Content-Disposition: attachement; filename="' . $download . $this->extn . '"');
            echo $str;
            exit;
        }
    }

    function multi_array_to_csv($array, $keyvalue = '', $fields = '', $download = "")
    {
        // store the csv file as a report in the system
        $f = fopen($this->path . $download . $this->extn, 'w') or show_error("Can't open php://output");
        $n = 0;
        $a = array();
        $b = array();

        if (!fputcsv($f, $fields)) {
            show_error("Can't write line $n: $line");
        }
        #print "<pre>";		
        #print_r($array);
        foreach ($array as $line => $key) {

            #print_r($key);

            $a = $key['asset_details'];
            unset($key['asset_details']);
            $b = $key;
            #print_r($a);
            #print_r($b);
            #exit;
            foreach ($b as $k1 => $v1) {

                if (!fputcsv($f, $v1)) {
                    show_error("Can't write line $n: $line");
                }
            }


            if (!fputcsv($f, $a)) {
                show_error("Can't write line $n: $line");
            }
        }



        fclose($f) or show_error("Can't close php://output");

        // get all the contents from the file and put it into a variable
        ob_start();
        include($this->path . $download . $this->extn);
        $str = ob_get_contents();
        ob_end_clean();

        if ($download == "") {
            return $str;
        } else {
            header('Content-Type: application/csv');
            header('Content-Disposition: attachement; filename="' . $download . $this->extn . '"');
            echo $str;
        }
    }

    // ------------------------------------------------------------------------

    /**
     * Query to CSV
     *
     * @access  public
     * @param   query object
     * @param   boolean
     * @param   string
     * @return  void
     * download == "" -> return CSV string
     * download == "toto.csv" -> download file toto.csv
     * 
     * Example: query_to_csv($query, TRUE); or query_to_csv($query, TRUE, 'toto.csv');
     */
    function query_to_csv($query, $headers = TRUE, $download = "")
    {

        if (!is_object($query) or !method_exists($query, 'list_fields')) {
            show_error('invalid query');
        }

        $array = array();

        if ($headers) {
            $line = array();
            foreach ($query->list_fields() as $name) {
                $line[] = $name;
            }
            $array[] = $line;
        }

        foreach ($query->result_array() as $row) {
            $line = array();
            foreach ($row as $item) {
                $line[] = $item;
            }
            $array[] = $line;
        }

        $this->array_to_csv($array, $download);
    }

    /**
     * Convert a comma separated file into an associated array.
     * The first row should contain the array keys.
     * 
     * Example:
     * 
     * @param string $filename Path to the CSV file
     * @param string $delimiter The separator used in the file
     * @return array
     */
    function csv_to_array($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return FALSE;

        $header = NULL;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== FALSE) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
                if (!$header)
                    $header = $row;
                else
                    $data[] = $row;
            }
            fclose($handle);
        }
        return $data;
    }
}

/* End of file CSVReader.php */
/* Location: ./system/application/libraries/CSVReader.php */
