<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Frontend extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('Frontend_model');
        $this->load->library("pagination");
    }
    public function index() {
        $data = array();
        $table_name = 'tbl_home_page_settings';
        $where = array();
        $data['home_page_data'] = $this->Frontend_model->getRecords($table_name, $where);
        $table_name = 'tbl_aboutus';
        $data['aboutus'] = $this->Frontend_model->getRecords($table_name, $where);
        $table_name = 'tbl_testimonials';
        $where = array('status' => '1');
        $data['testimonials'] = $this->Frontend_model->getRecords($table_name, $where);
        $table_name = 'tbl_events';
        $where = array('status' => '1');
        $data['events'] = $this->Frontend_model->getRecords($table_name, $where);
        $this->load->view('Frontend/header');
        $this->load->view('Frontend/index', $data);
        $this->load->view('Frontend/footer');
    }
    public function events() {
        $data = array();
        $table_name = 'tbl_events';

        $where = array('status' => '1');
        $config["base_url"] = base_url() . "events";
        $config["total_rows"] = $this->Frontend_model->get_count($table_name, $where);
        $config["per_page"] = 10;
        $config["uri_segment"] = 2;
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
        $data["links"] = $this->pagination->create_links();
        $data['events'] = $this->Frontend_model->getPagedRecords($table_name, $where, $config["per_page"], $page);
        $this->load->view('Frontend/header');
        $this->load->view('Frontend/blog', $data);
        $this->load->view('Frontend/footer');
    }
    public function event_details($id=null)
    {
        if(!empty($id))
        {

          $data = array();
          $table_name = 'tbl_events';
          $where = array('event_id'=>$id ,'status' => '1');
          $data['events'] = $this->Frontend_model->getSingleRecord($table_name, $where);

          $table_name = 'tbl_comments';
            $where = array('event_id'=>$id);
          $data['comments'] = $this->Frontend_model->getRecords($table_name, $where);
            // print_r($data);die();
          $this->load->view('Frontend/header');
          $this->load->view('Frontend/blog-single', $data);
          $this->load->view('Frontend/footer');

        }else
        {
          $url = base_url();

          redirect($url.'events');
        }
    }
    public function about() {
        $data = array();
        $where = array();
        $table_name = 'tbl_aboutus';
        $data['aboutus'] = $this->Frontend_model->getRecords($table_name, $where);
        $this->load->view('Frontend/header');
        $this->load->view('Frontend/about', $data);
        $this->load->view('Frontend/footer');
    }

    public function profile() {
        $data = array();
        $usersessiondata = array();
        $usersessiondata = $this->session->userdata('logged_in_user');
        if (!empty($usersessiondata)) {
            $table_name = 'tbl_customers';
            $where = array('customer_id' => $usersessiondata->customer_id);
            $data['customer_detail'] = $this->Frontend_model->getSingleRecord($table_name, $where);
        }
        $this->load->view('Frontend/header');
        $this->load->view('Frontend/myprofile', $data);
        $this->load->view('Frontend/footer');
    }
    public function products() {
        $data = array();
        $config = array();
        $table_name = 'tbl_products';
        $where = array('status' => '1');
        $config["base_url"] = base_url() . "products";
        $config["total_rows"] = $this->Frontend_model->get_count($table_name, $where);
        $config["per_page"] = 10;
        $config["uri_segment"] = 2;
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
        $data["links"] = $this->pagination->create_links();
        $data['products'] = $this->Frontend_model->getPagedRecords($table_name, $where, $config["per_page"], $page);
        $this->load->view('Frontend/header');
        $this->load->view('Frontend/products', $data);
        $this->load->view('Frontend/footer');
    }
    public function membership() {
        $data = array();
        $table_name = 'tbl_members';
        $where = array('status' => '1');
        $data['members'] = $this->Frontend_model->getRecords($table_name, $where);
        $this->load->view('Frontend/header');
        $this->load->view('Frontend/membership', $data);
        $this->load->view('Frontend/footer');
    }
    public function contact() {
        $this->load->view('Frontend/header');
        $this->load->view('Frontend/contact');
        $this->load->view('Frontend/footer');
    }
    public function login() {
        $this->load->view('Frontend/header');
        $this->load->view('Frontend/login');
        $this->load->view('Frontend/footer');
    }
    public function signup() {
        $this->load->view('Frontend/header');
        $this->load->view('Frontend/signup');
        $this->load->view('Frontend/footer');
    }
    public function checkout() {
        $this->load->view('Frontend/header');
        $this->load->view('Frontend/checkout');
        $this->load->view('Frontend/footer');
    }
    public function thankyou() {
        $this->load->view('Frontend/header');
        $this->load->view('Frontend/thankyou');
        $this->load->view('Frontend/footer');
    }
    public function become_member() {
        $this->load->view('Frontend/header');
        $this->load->view('Frontend/become_member');
        $this->load->view('Frontend/footer');
    }
    public function add_members()
    {
      $now = date('Y-m-d H:i:s');
      if (!empty($_FILES['member_image']['name'])) {
        $table_name = 'tbl_members';
        $file = '';
        $target_file = '';
        $filename = "";
        if (!empty($_FILES["member_image"]["name"])) {
            $target_dir = "uploads/";
            $filename = time() . basename($_FILES["member_image"]["name"]);
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            $valid_ext = array("png", "jpg", "jpeg");
            if (in_array($ext, $valid_ext)) {
                $target_file = $target_dir . $filename;
                if (move_uploaded_file($_FILES["member_image"]["tmp_name"], $target_file)) {
                    $data = array('member_name' => $_POST['member_name'], 'website_link' => $_POST['website_link'], 'facebook_link' => $_POST['facebook_link'], 'member_image' => $filename, 'created_at' => $now);
                    $data = $this->Frontend_model->addRecord($table_name, $data);
                    echo json_encode($data);
                } else {
                    echo json_encode("error");
                }
            } else {
                echo json_encode("invalid_format");
            }
        }
      }
    }
    public function add_comment() {
        if (!empty($_POST)) {
            $now = date('Y-m-d H:i:s');
            $table_name = 'tbl_comments';
            $data = array('event_id' => $_POST['event_id'], 'commentor_name' => $_POST['commentor_name'], 'commentor_email' => $_POST['commentor_email'], 'commentor_message' => $_POST['commentor_message'], 'created_at' => $now);
            $data = $this->Frontend_model->addRecord($table_name, $data);
            if ($data) {
                echo json_encode($data);
            } else {
                echo json_encode("error");
            }
        }
    }

    public function contactus() {
        if (!empty($_POST)) {
            $now = date('Y-m-d H:i:s');
            $table_name = 'tbl_contact';
            $data = array('name' => $_POST['name'], 'email' => $_POST['email'], 'message' => $_POST['message'], 'status' => '1', 'created_at' => $now);
            $data = $this->Frontend_model->addRecord($table_name, $data);
            if ($data) {
                echo json_encode($data);
            } else {
                echo json_encode("error");
            }
        }
    }

    public function cart() {
        $session_cart = array();
        $cartitem = array();
        $table_name = 'tbl_products';
        $session_cart = $this->session->userdata('cart');
        if (!empty($session_cart)) {
            foreach ($session_cart as $key => $value) {
                $where = array('product_id' => $value);
                $cartitem[] = $this->Frontend_model->getSingleRecord($table_name, $where);
            }
        }
        $data['cartitem'] = $cartitem;
        $this->load->view('Frontend/header');
        $this->load->view('Frontend/cart', $data);
        $this->load->view('Frontend/footer');
    }
    public function add_to_cart() {
        if (!empty($_POST)) {
            $session_cart = array();
            $cartitem = array();
            $session_cart = $this->session->userdata('cart');
            if (!empty($session_cart)) {
                $count = count($this->session->userdata('cart'));
                foreach ($session_cart as $key => $value) {
                    if ($value != $_POST['product_id']) {
                        $cartitem[] = $value;
                    }
                }
                $cartitem[] = $_POST['product_id'];
            } else {
                $cartitem[] = $_POST['product_id'];
            }
            $this->session->set_userdata('cart', $cartitem);
            echo "success";
        } else {
            echo "fail";
        }
    }
    public function remove_from_cart() {
        if (!empty($_POST)) {
            $session_cart = array();
            $cartitem = array();
            $session_cart = $this->session->userdata('cart');
            if (!empty($session_cart)) {
                $count = count($this->session->userdata('cart'));
                foreach ($session_cart as $key => $value) {
                    if ($value != $_POST['product_id']) {
                        $cartitem[] = $value;
                    }
                }
            }
            $this->session->set_userdata('cart', $cartitem);
            echo "success";
        } else {
            echo "fail";
        }
    }
    public function addRecord() {
        if (!empty($_POST)) {
            $now = date('Y-m-d H:i:s');
            $table_name = 'tbl_customers';
            $where = array('customer_email' => $_POST['email']);
            $res = $this->Frontend_model->getSingleRecord($table_name, $where);
            if (empty($res)) {
                $data = array('customer_name' => $_POST['name'], 'customer_email' => $_POST['email'], 'password' => md5($_POST['password']), 'customer_mobile' => $_POST['mobile_number'], 'status' => '1', 'created_at' => $now);
                $data = $this->Frontend_model->addRecord($table_name, $data);
                if ($data) {
                    echo json_encode($data);
                } else {
                    echo json_encode("error");
                }
            } else {
                echo json_encode("email_exist");
            }
        }
    }
    public function login_user() {
        if (!empty($_POST)) {
            $now = date('Y-m-d H:i:s');
            $table_name = 'tbl_customers';
            $where = array('customer_email' => $_POST['username'], 'password' => md5($_POST['password']));
            $res = $this->Frontend_model->getSingleRecord($table_name, $where);
            if (!empty($res)) {
                $this->session->userdata();
                $this->session->set_userdata('logged_in_user', $res);
                echo json_encode($res);
            } else {
                echo json_encode("error");
            }
        }
    }
    public function logout() {
        $this->session->unset_userdata('logged_in_user');
        $url = base_url() . 'login';
        redirect($url);
    }
    public function total_checkout_amount() {
        if (!empty($_POST)) {
            $this->session->set_userdata('total_checkout_amount', $_POST['total_checkout_amount']);
            $this->session->set_userdata('quantity', $_POST['quantity']);
            echo json_encode("success");
        } else {
            echo json_encode("fail");
        }
    }
    public function place_order() {
        if (!empty($_POST)) {
            $now = date('Y-m-d H:i:s');
            $session_cart = array();
            $quantity = array();
            $session_cart = $this->session->userdata('cart');
            $table_name = 'tbl_billing_info';
            $data = array('name' => $_POST['first_name'] . ' ' . $_POST['last_name'], 'email' => $_POST['email'], 'address' => $_POST['streetaddress'], 'city' => $_POST['city'], 'zipcode' => $_POST['zipcode'], 'status' => '1', 'created_at' => $now);
            $res = $this->Frontend_model->addRecord($table_name, $data);
            $total_checkout_amount = 0;
            if ($res) {
                if (!empty($session_cart)) {
                    $quantity = $this->session->userdata('quantity');
                    $count = count($this->session->userdata('cart'));
                    foreach ($session_cart as $key => $value) {
                        $table_name = 'tbl_products';
                        $where = array('product_id' => $value);
                        $product_details = $this->Frontend_model->getSingleRecord($table_name, $where);
                        $total_checkout_amount = $quantity[$key] * $product_details->product_price;
                        $table_name = 'tbl_orders';
                        $data = array('product_id' => $value, 'billing_id' => $res, 'status' => '1', 'quantity' => $quantity[$key], 'total_checkout_amount' => $total_checkout_amount, 'created_at' => $now);
                        $this->Frontend_model->addRecord($table_name, $data);
                    }
                }
                $this->session->unset_userdata('cart');
                $this->session->unset_userdata('total_checkout_amount');
                echo json_encode("success");
            } else {
                echo json_encode("error");
            }
        } else {
            echo json_encode("fail");
        }
    }
    public function update_customer() {
        if (!empty($_POST)) {
            $table_name = 'tbl_customers';
            $where = array('customer_id' => $_POST['customer_id']);
            if (!empty($_POST['password'])) {
                $data = array('customer_name' => $_POST['name'], 'customer_mobile' => $_POST['mobile_number'], 'password' => md5($_POST['password']));
            } else {
                $data = array('customer_name' => $_POST['name'], 'customer_mobile' => $_POST['mobile_number']);
            }
            $this->Frontend_model->updateRecord($table_name, $data, $where);
            echo json_encode("success");
        } else {
            echo json_encode("error");
        }
    }
    public function upload_profile_image() {
        if (!empty($_FILES['files']['name'])) {
            $table_name = 'tbl_customers';
            $where = array('customer_id' => $_POST['customer_id']);
            $file = '';
            $target_file = '';
            $filename = "";
            if (!empty($_FILES["files"]["name"])) {
                $target_dir = "uploads/";
                $filename = time() . basename($_FILES["files"]["name"]);
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $valid_ext = array("png", "jpg", "jpeg");
                if (in_array($ext, $valid_ext)) {
                    $target_file = $target_dir . $filename;
                    if (move_uploaded_file($_FILES["files"]["tmp_name"], $target_file)) {
                        $data = array('image' => $filename);
                        $data = $this->Frontend_model->updateRecord($table_name, $data, $where);
                        echo json_encode($data);
                        $res = $this->Frontend_model->getSingleRecord($table_name, $where);
                        $this->session->userdata();
                        $this->session->unset_userdata('logged_in_user');
                        $this->session->set_userdata('logged_in_user', $res);
                    } else {
                        echo json_encode("error");
                    }
                } else {
                    echo json_encode("invalid_format");
                }
            }
        }
    }
}
