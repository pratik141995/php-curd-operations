<?php
class Admin_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }
    public function resetPass($data) {
        $this->db->where('userEmail', $data['userEmail']);
        $this->db->update('tbl_users', $data);
    }
    public function getUserDetails($userEmail) {
        $this->db->select('*');
        $this->db->from('tbl_users');
        $this->db->where('userEmail', $userEmail);
        $this->db->where('userStatus', '0');
        $qry_res = $this->db->get();
        $resArray = $qry_res->result_array();
        return $resArray;
    }
    public function addRecord($table, $data) {
        $this->db->insert($table, $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }
    public function getRecords($table_name, $where) {
        $this->db->select('*');
        $this->db->from($table_name);
        $this->db->where($where);
        $query = $this->db->get();
        $data = $query->result_array();
        return $data;
    }
    public function getRecords1($table_name, $where) {
        $this->db->select('*');
        $this->db->from($table_name);
        $this->db->where($where);
        $query = $this->db->get();
        // $data=$query->result_array();
        return $query->num_rows();
    }
    public function updateRecord($table, $data, $where) {
        $this->db->where($where);
        $this->db->update($table, $data);
        $row = $this->db->affected_rows();
        //print_r($this->db->last_query());
        return $row;
    }
    public function getAllRecords() {
        $this->db->select('tbl_service.*,tbl_service_category.*,tbl_booking.*,tbl_paymentdetails.*');
        $this->db->from('tbl_booking');
        $this->db->join('tbl_service', 'tbl_booking.service_id = tbl_service.serviceId');
        $this->db->join('tbl_service_category', 'tbl_service.serviceCatId = tbl_service_category.serviceCatId');
        $this->db->join('tbl_paymentdetails', 'tbl_booking.booking_id = tbl_paymentdetails.booking_id');
        $this->db->order_by('tbl_booking.booking_id', "desc");
        $query = $this->db->get();
        $data = $query->result_array();
        return $data;
    }
		public function getAllOrdersRecords($where) {
        $this->db->select('tbl_orders.*,tbl_billing_info.name,tbl_billing_info.email,tbl_billing_info.address,tbl_billing_info.city,tbl_billing_info.zipcode,tbl_products.product_name,tbl_products.product_image');
        $this->db->from('tbl_orders');
        $this->db->join('tbl_billing_info', 'tbl_orders.billing_id = tbl_billing_info.billing_id');
        $this->db->join('tbl_products', 'tbl_orders.product_id = tbl_products.product_id');
        $this->db->order_by('tbl_orders.order_id', "desc");
				if(!empty($where))
				{
						$this->db->where($where);
				}

        $query = $this->db->get();
        $data = $query->result_array();
        return $data;
    }

    public function getAllRecords1() {
        $this->db->select('tbl_service.*,tbl_service_category.*,tbl_booking.*,tbl_paymentdetails.*');
        $this->db->from('tbl_booking');
        $this->db->join('tbl_service', 'tbl_booking.service_id = tbl_service.serviceId');
        $this->db->join('tbl_service_category', 'tbl_service.serviceCatId = tbl_service_category.serviceCatId');
        $this->db->join('tbl_paymentdetails', 'tbl_booking.booking_id = tbl_paymentdetails.booking_id');
        $this->db->order_by('tbl_booking.booking_id', "desc");
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function deleteRecord($table,$where)
  	{
  		$this->db->where($where);
  		$this->db->delete($table);
  	}
}
?>
