<?php
class Frontend_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }
     
    public function check_service_category($where) {
        $this->db->select('*');
        $this->db->from('tbl_service_category');
        //  $this->db->join('tbl_service_category', 'tbl_service_category.serviceCatId = tbl_service.serviceCatId','right');
        $this->db->where($where);
        $query = $this->db->get();
        //$this->db->group_by('tbl_service.u_id');
        //	echo $this->db->last_query();
        return $query->result();
    }
    public function checkExist($table, $where) {
        $this->db->select('*');
        $this->db->from($table);
        $this->db->where($where);
        $query = $this->db->get();
        return $query->row();
    }
    public function check_service($where) {
        $this->db->select('*');
        $this->db->from('tbl_service');
        $this->db->where($where);
        $query = $this->db->get();
        $data = $query->result_array();
        return $data;
    }
    public function getSingleRecord($table_name, $where) {
        $this->db->select('*');
        $this->db->from($table_name);
        $this->db->where($where);
        $query = $this->db->get();
        $data = $query->row();
        return $data;
    }
    public function addRecord($table_name, $data) {
        $this->db->insert($table_name, $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }
    public function getAllRecords($table_name, $where) {
        $this->db->select('*');
        $this->db->from($table_name);
        //$this->db->join('tbl_service','tbl_booking.service_id = tbl_service.serviceId');
        //$this->db->join('tbl_service_category','tbl_service.serviceCatId = tbl_service_category.serviceCatId');
        $this->db->where($where);
        $query = $this->db->get();
        $data = $query->result_array();
        return $data;
    }
    public function getRecords($table_name, $where) {
        $this->db->select('*');
        $this->db->from($table_name);
        if (!empty($where)) {
            $this->db->where($where);
        }
        $query = $this->db->get();
        $data = $query->result_array();
        return $data;
    }
    public function getPagedRecords($table_name, $where, $limit, $start) {
        $this->db->select('*');
        $this->db->from($table_name);
        if (!empty($where)) {
            $this->db->where($where);
        }
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        $data = $query->result_array();
        return $data;
    }
    public function updateRecord($table, $data, $where) {
        $this->db->where($where);
        $this->db->update($table, $data);
        $row = $this->db->affected_rows();
        //print_r($this->db->last_query());
        return $row;
    }
    public function get_count($table, $where) {
        $this->db->where($where);
        return $this->db->count_all($table);
    }
}
?>