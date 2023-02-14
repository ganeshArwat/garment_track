<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Global_model extends CI_Model
{
    public function _construct()
    {
        parent::_construct();
    }

    public function insert($table_name, $data)
    {
        $id = 0;
        $insert_data = array();
        if (isset($data) && is_array($data) && count($data) > 0) {
            foreach ($data as $key => $value) {
                $insert_data[$key] = trim($value);
                $insert_data[$key] =  str_replace(array("'", "\'"), "", $insert_data[$key]);
            }
        }
        $add_record = 1;

        if ($add_record == 1) {
           
            $this->db->insert($table_name, $insert_data);
            $id = $this->db->insert_id();
        }

        if (isset($insertError) && $insertError != '') {
            return $insertError;
        } else {
            return $id;
        }
    }

    public function get_data_list($table_name, $where = array(), $like = array(), $order_by = array(), $select = '*', $limit = 0, $like_or = array(), $where_in = array())
    {
        $this->db->select($select);
        foreach ($like as $key => $value) {
            $this->db->like($key, $value);
        }

        foreach ($like_or as $key => $value) {
            $this->db->or_like($key, $value);
        }

        if (isset($where) && is_array($where)) {
            foreach ($where as $key => $value) {
                $this->db->where($key, $value);
            }
        }

        if (isset($where_in) && is_array($where_in) && count($where_in) > 0) {
            foreach ($where_in as $in_key => $in_value) {
                $this->db->where_in($in_key, $in_value);
            }
        }

        if (isset($order_by) && is_array($order_by)) {
            foreach ($order_by as $key => $value) {
                $this->db->order_by($key, $value);
            }
        }

        if ($limit != 0) {
            $this->db->limit($limit);
        }

        $query = $this->db->get($table_name);

        return $query->result_array();
    }

    public function get_selected_record($table_name, $select = "*", $where = array(), $order_by = '', $where_in = array())
    {


        $this->db->select($select);

        if (count($where) > 0) {
            $this->db->where($where);
        }


        if (isset($where_in) && is_array($where_in) && count($where_in) > 0) {
            foreach ($where_in as $in_key => $in_value) {
                $this->db->where_in($in_key, $in_value);
            }
        }
        if ($order_by != '') {
            $this->db->order_by($order_by);
        }

        $query = $this->db->get($table_name);

        return $query->row_array();
    }

    public function has_empty($array)
    {
        foreach ($array as $value) {
            if ($value == "" || $value == null) {
                return true;
            }
        }
        return false;
    }

    public function update($table_name, $data, $id = 0, $where = array())
    {

        $this->load->helper('frontend_common');
        if (isset($id) && $id > 0) {
            $where['id'] = $id;
        }

        $update_data = array();
        if (isset($data) && is_array($data) && count($data) > 0) {
            foreach ($data as $key => $value) {
                $update_data[$key] = trim($value);
                //$update_data[$key] =  str_replace(array("'", "\""), "", $update_data[$key]);
                $update_data[$key] =  str_replace(array("'", "\'"), "", $update_data[$key]);
            }
        }

        if ($this->has_empty($where)) {
            return false;
        } else {
           
            if ($table_name == 'docket' &&  $where['id'] > 0) {

            $this->db->where($where);

            $update_status = $this->db->update($table_name, $update_data);

            return $update_status;
        }
    }
}
}