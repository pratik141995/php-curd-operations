<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Admin extends MY_Controller {
    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     *	- or -
     * 		http://example.com/index.php/welcome/index
     *	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('Admin_model');
    }
    //********** Showing Admin dashboard ********
    public function index() {
        $data = array();
        $this->load->view('Admin/header');
        $this->load->view('Admin/dashboard', $data);
        $this->load->view('Admin/footer');
    }
    //******** logged in user reset there password
    public function changePass() {
        $session = $this->session->userdata();
        $data['session'] = $session;
        $this->load->view('Admin/header');
        $this->load->view('Admin/changePassAdmin', $data);
        $this->load->view('Admin/footer');
    }
    //*******  Reset Password ******
    public function resetPassUser() {
        $data = array();
        $data['userPassword'] = md5($_POST['userPassword']);
        $data['userEmail'] = $_POST['userEmail'];
        $res = $this->Admin_model->resetPass($data);
        echo $res;
    }
    //********* logout *****************
    public function logout() {
        $url = base_url();
        $this->session->sess_destroy();
        redirect('Auth/index');
    }
    public function Manage_products() {
        $data = array();
        $table_name = 'tbl_products';
        $where = array();
        $data['products'] = $this->Admin_model->getRecords($table_name, $where);
        $this->load->view('Admin/header');
        $this->load->view('Admin/products', $data);
        $this->load->view('Admin/footer');
    }
    public function Manage_membership() {
        $data = array();
        $table_name = 'tbl_members';
        $where = array();
        $data['members'] = $this->Admin_model->getRecords($table_name, $where);
        $this->load->view('Admin/header');
        $this->load->view('Admin/membership', $data);
        $this->load->view('Admin/footer');
    }
    public function Manage_orders() {
        $data = array();
        $where = array();
        $data['orders'] = $this->Admin_model->getAllOrdersRecords($where);
        $this->load->view('Admin/header');
        $this->load->view('Admin/orders', $data);
        $this->load->view('Admin/footer');
    }

    public function Manage_testimonials()
    {
      $data = array();
      $table_name = 'tbl_testimonials';
      $where = array();
      $data['testimonials'] = $this->Admin_model->getRecords($table_name, $where);
      $this->load->view('Admin/header');
      $this->load->view('Admin/testimonials', $data);
      $this->load->view('Admin/footer');
    }

    public function Manage_events()
    {
      $data = array();
      $table_name = 'tbl_events';
      $where = array();
      $data['events'] = $this->Admin_model->getRecords($table_name, $where);
      $this->load->view('Admin/header');
      $this->load->view('Admin/events', $data);
      $this->load->view('Admin/footer');
    }

    public function Manage_home_page()
    {
      $data = array();
      $table_name = 'tbl_home_page_settings';
      $where = array();
      $data['home_page_data'] = $this->Admin_model->getRecords($table_name, $where);
      $this->load->view('Admin/header');
      $this->load->view('Admin/home_page_setting', $data);
      $this->load->view('Admin/footer');
    }

    public function Manage_about()
    {
      $data = array();
      $table_name = 'tbl_aboutus';
      $where = array();
      $data['aboutus'] = $this->Admin_model->getRecords($table_name, $where);
      $this->load->view('Admin/header');
      $this->load->view('Admin/aboutus', $data);
      $this->load->view('Admin/footer');
    }

    public function Manage_enquiry()
    {
      $data = array();
      $table_name = 'tbl_contact';
      $where = array();
      $data['contacts'] = $this->Admin_model->getRecords($table_name, $where);
      $this->load->view('Admin/header');
      $this->load->view('Admin/enquiry', $data);
      $this->load->view('Admin/footer');
    }

    public function view_order($id=null) {
        $data = array();
        if(!empty($id))
        {
          $where = array('tbl_orders.order_id' => $id);
          $data['orders'] = $this->Admin_model->getAllOrdersRecords($where);
        }

        $this->load->view('Admin/header');
        $this->load->view('Admin/order_view', $data);
        $this->load->view('Admin/footer');
    }

    /***************************************************************************
    / ====================  Admin Add functions ==================/
    /**************************************************************************/
    public function addRecord() {
        $data = array();
        $now = date('Y-m-d H:i:s');
        if (isset($_POST['product_name'])) {
            $table_name = 'tbl_products';
            $file = '';
            $target_file = '';
            $filename = "";
            if (!empty($_FILES["product_image"]["name"])) {
                $target_dir = "uploads/";
                $filename = time() . basename($_FILES["product_image"]["name"]);
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $valid_ext = array("png", "jpg", "jpeg");
                if (in_array($ext, $valid_ext)) {
                    $target_file = $target_dir . $filename;
                    if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
                        $data = array('product_name' => $_POST['product_name'], 'product_price' => $_POST['product_price'], 'product_image' => $filename, 'created_at' => $now);
                        $data = $this->Admin_model->addRecord($table_name, $data);
                        echo json_encode($data);
                    } else {
                        echo json_encode("error");
                    }
                } else {
                    echo json_encode("invalid_format");
                }
            }
        }
        if (isset($_POST['testimonial'])) {
            $table_name = 'tbl_testimonials';
            $file = '';
            $target_file = '';
            $filename = "";
            if (!empty($_FILES["image"]["name"])) {
                $target_dir = "uploads/";
                $filename = time() . basename($_FILES["image"]["name"]);
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $valid_ext = array("png", "jpg", "jpeg");
                if (in_array($ext, $valid_ext)) {
                    $target_file = $target_dir . $filename;
                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                        $data = array('name' => $_POST['name'], 'position' => $_POST['position'], 'heading' => $_POST['heading'], 'message' => $_POST['testimonial'], 'image' => $filename, 'created_at' => $now);
                        $data = $this->Admin_model->addRecord($table_name, $data);
                        echo json_encode($data);
                    } else {
                        echo json_encode("error");
                    }
                } else {
                    echo json_encode("invalid_format");
                }
            }
        }
        if (isset($_POST['event_title'])) {
            $table_name = 'tbl_events';
            $file = '';
            $target_file = '';
            $filename = "";
            if (!empty($_FILES["image"]["name"])) {
                $target_dir = "uploads/";
                $filename = time() . basename($_FILES["image"]["name"]);
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $valid_ext = array("png", "jpg", "jpeg");
                if (in_array($ext, $valid_ext)) {
                    $target_file = $target_dir . $filename;
                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                        $data = array('event_title' => $_POST['event_title'], 'event_description' => $_POST['event_description'], 'event_brief' => $_POST['event_brief'], 'event_image' => $filename, 'created_at' => $now);

                        $data = $this->Admin_model->addRecord($table_name, $data);
                        echo json_encode($data);
                    } else {
                        echo json_encode("error");
                    }
                } else {
                    echo json_encode("invalid_format");
                }
            }
        }
        if (isset($_POST['member_name'])) {
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
                        $data = $this->Admin_model->addRecord($table_name, $data);
                        echo json_encode($data);
                    } else {
                        echo json_encode("error");
                    }
                } else {
                    echo json_encode("invalid_format");
                }
            }
        }
        if (isset($_POST['promo_code'])) {
            $table_name = 'tbl_promocode';
            $data = array('promo_code' => $_POST['promo_code'], 'promo_offer' => $_POST['discount'], 'status' => '1');
            $res = $this->Admin_model->addrecord($table_name, $data);
            echo $res;
        }
    }
    /***************************************************************************
    / ====================  Admin Delete functions ==================/
    /**************************************************************************/
    public function deleteRecord() {
        echo $_POST['del_id'];
        if ($_POST['type'] == 'product') {
            $table = "tbl_products";
            $where = array('product_id' => $_POST['del_id']);
        }

        if ($_POST['type'] == 'testimonial') {
            $table = "tbl_testimonials";
            $where = array('testimonial_id' => $_POST['del_id']);
        }

        if ($_POST['type'] == 'member') {
            $table = "tbl_members";
            $where = array('member_id' => $_POST['del_id']);
        }
        if ($_POST['status'] == '1') {
            $data = array('status' => '0');
        } else {
            $data = array('status' => '1');
        }
        $res = $this->Admin_model->updateRecord($table, $data, $where);
    }
    /***************************************************************************
    / ====================  Admin Edit functions ==================/
    /**************************************************************************/
    public function editRecord() {
        if ($_POST['type'] == 'product') {
            $table_name = 'tbl_products';
            $where = array('product_id' => $_POST['editId']);
            $data = $this->Admin_model->getRecords($table_name, $where);
        }
        if ($_POST['type'] == 'home_setting') {
            $table_name = 'tbl_home_page_settings';
            $where = array('home_page_settings_id' => $_POST['edit_id']);
            $data = $this->Admin_model->getRecords($table_name, $where);
        }

        if ($_POST['type'] == 'aboutus') {
            $table_name = 'tbl_aboutus';
            $where = array('aboutus_id' => $_POST['edit_id']);
            $data = $this->Admin_model->getRecords($table_name, $where);
        }

        if ($_POST['type'] == 'testimonial') {
            $table_name = 'tbl_testimonials';
            $where = array('testimonial_id' => $_POST['edit_id']);
            $data = $this->Admin_model->getRecords($table_name, $where);
        }

        if ($_POST['type'] == 'member') {
            $table_name = 'tbl_members';
            $where = array('member_id' => $_POST['editId']);
            $data = $this->Admin_model->getRecords($table_name, $where);
        }
        if ($_POST['type'] == 'event') {
            $table_name = 'tbl_events';
            $where = array('event_id' => $_POST['edit_id']);
            $data = $this->Admin_model->getRecords($table_name, $where);
        }

        echo json_encode($data);
    }
    /***************************************************************************
    / ====================  Admin UpdateRecord functions ==================/
    /**************************************************************************/
    public function updateRecord() {
        //print_r($_POST);
        $now = date('Y-m-d H:i:s');
        if (isset($_POST['product_id'])) {
            $table = "tbl_products";
            $file = '';
            $target_file = '';
            $filename = "";
            $where = array('product_id' => $_POST['product_id']);
            if (!empty($_FILES["product_image"]["name"])) {
                $target_dir = "uploads/";
                $filename = time() . basename($_FILES["product_image"]["name"]);
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $valid_ext = array("png", "jpg", "jpeg");
                if (in_array($ext, $valid_ext)) {
                    $target_file = $target_dir . $filename;
                    if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
                        $data = array('product_name' => $_POST['product_name'], 'product_price' => $_POST['product_price'], 'product_image' => $filename, 'created_at' => $now);
                        $data = $this->Admin_model->updateRecord($table, $data, $where);
                        echo json_encode($data);
                    } else {
                        echo json_encode("error");
                    }
                } else {
                    echo json_encode("invalid_format");
                }
            } else {
                $data = array('product_name' => $_POST['product_name'], 'product_price' => $_POST['product_price'], 'created_at' => $now);
                $data = $this->Admin_model->updateRecord($table, $data, $where);
                echo json_encode($data);
            }
        }

        if (isset($_POST['event_id'])) {
            $table = "tbl_events";
            $file = '';
            $target_file = '';
            $filename = "";
            $where = array('event_id' => $_POST['event_id']);
            if (!empty($_FILES["image"]["name"])) {
                $target_dir = "uploads/";
                $filename = time() . basename($_FILES["image"]["name"]);
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $valid_ext = array("png", "jpg", "jpeg");
                if (in_array($ext, $valid_ext)) {
                    $target_file = $target_dir . $filename;
                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {

                        $data = array('event_title' => $_POST['event_title'], 'event_description' => $_POST['event_description'], 'event_brief' => $_POST['event_brief'], 'event_image' => $filename, 'created_at' => $now);

                        $data = $this->Admin_model->updateRecord($table, $data, $where);
                        echo json_encode($data);
                    } else {
                        echo json_encode("error");
                    }
                } else {
                    echo json_encode("invalid_format");
                }
            } else {
              $data = array('event_title' => $_POST['event_title'], 'event_description' => $_POST['event_description'], 'event_brief' => $_POST['event_brief'], 'created_at' => $now);
                $data = $this->Admin_model->updateRecord($table, $data, $where);
                echo json_encode($data);
            }
        }


        if (isset($_POST['home_page_settings_id'])) {
            $table = "tbl_home_page_settings";
            $file = '';
            $target_file = '';
            $filename = "";
            $where = array('home_page_settings_id' => $_POST['home_page_settings_id']);
            if (!empty($_FILES["banner_image"]["name"])) {
                $target_dir = "asstes/images/";
                $filename = time() . basename($_FILES["banner_image"]["name"]);
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $valid_ext = array("png", "jpg", "jpeg");
                if (in_array($ext, $valid_ext)) {
                    $target_file = $target_dir . $filename;
                    if (move_uploaded_file($_FILES["banner_image"]["tmp_name"], $target_file)) {
                        $data = array('banner_heading' => $_POST['banner_heading'], 'banner_button_link' => $_POST['banner_button_link'], 'banner_image' => $filename, 'donations' => $_POST['donations'], 'members' => $_POST['members'], 'projects' => $_POST['projects'], 'missions' => $_POST['missions'], 'creat_at' => $now);
                        $data = $this->Admin_model->updateRecord($table, $data, $where);
                        echo json_encode($data);
                    } else {
                        echo json_encode("error");
                    }
                } else {
                    echo json_encode("invalid_format");
                }
            } else {
              $data = array('banner_heading' => $_POST['banner_heading'], 'banner_button_link' => $_POST['banner_button_link'], 'donations' => $_POST['donations'], 'members' => $_POST['members'], 'projects' => $_POST['projects'], 'missions' => $_POST['missions'], 'creat_at' => $now);

                $data = $this->Admin_model->updateRecord($table, $data, $where);
                echo json_encode($data);
            }
        }

        if (isset($_POST['aboutus_id'])) {
            $table = "tbl_aboutus";
            $file = '';
            $target_file = '';
            $filename = "";
            $where = array('aboutus_id' => $_POST['aboutus_id']);
            if (!empty($_FILES["image"]["name"])) {
                $target_dir = "asstes/images/";
                $filename = time() . basename($_FILES["image"]["name"]);
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $valid_ext = array("png", "jpg", "jpeg");
                if (in_array($ext, $valid_ext)) {
                    $target_file = $target_dir . $filename;
                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                        $data = array('who_we_are' => $_POST['who_we_are'], 'our_mission' => $_POST['our_mission'], 'image' => $filename, 'our_policy' => $_POST['our_policy'], 'created_at' => $now);
                        $data = $this->Admin_model->updateRecord($table, $data, $where);
                        echo json_encode($data);
                    } else {
                        echo json_encode("error");
                    }
                } else {
                    echo json_encode("invalid_format");
                }
            } else {
              $data = array('who_we_are' => $_POST['who_we_are'], 'our_mission' => $_POST['our_mission'], 'our_policy' => $_POST['our_policy'], 'created_at' => $now);

                $data = $this->Admin_model->updateRecord($table, $data, $where);
                echo json_encode($data);
            }
        }

        if (isset($_POST['testimonial_id'])) {
            $table = "tbl_testimonials";
            $file = '';
            $target_file = '';
            $filename = "";
            $where = array('testimonial_id' => $_POST['testimonial_id']);
            if (!empty($_FILES["image"]["name"])) {
                $target_dir = "uploads/";
                $filename = time() . basename($_FILES["image"]["name"]);
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $valid_ext = array("png", "jpg", "jpeg");
                if (in_array($ext, $valid_ext)) {
                    $target_file = $target_dir . $filename;
                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                        $data = array('name' => $_POST['name'], 'position' => $_POST['position'], 'image' => $filename, 'heading' => $_POST['heading'], 'testimonial' => $_POST['message'], 'creat_at' => $now);
                        $data = $this->Admin_model->updateRecord($table, $data, $where);
                        echo json_encode($data);
                    } else {
                        echo json_encode("error");
                    }
                } else {
                    echo json_encode("invalid_format");
                }
            } else {
              $data = array('name' => $_POST['name'], 'position' => $_POST['position'], 'heading' => $_POST['heading'], 'message' => $_POST['testimonial'], 'created_at' => $now);
              $data = $this->Admin_model->updateRecord($table, $data, $where);
                echo json_encode($data);
            }
        }
        if (isset($_POST['member_id'])) {
            $table = "tbl_members";
            $file = '';
            $target_file = '';
            $filename = "";
            $where = array('member_id' => $_POST['member_id']);
            if (!empty($_FILES["member_image"]["name"])) {
                $target_dir = "uploads/";
                $filename = time() . basename($_FILES["member_image"]["name"]);
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $valid_ext = array("png", "jpg", "jpeg");
                if (in_array($ext, $valid_ext)) {
                    $target_file = $target_dir . $filename;
                    if (move_uploaded_file($_FILES["member_image"]["tmp_name"], $target_file)) {
                        $data = array('member_name' => $_POST['member_name'], 'website_link' => $_POST['website_link'], 'facebook_link' => $_POST['facebook_link'], 'member_image' => $filename, 'created_at' => $now);
                        $data = $this->Admin_model->updateRecord($table, $data, $where);
                        echo json_encode($data);
                    } else {
                        echo json_encode("error");
                    }
                } else {
                    echo json_encode("invalid_format");
                }
            } else {
                $data = array('member_name' => $_POST['member_name'], 'website_link' => $_POST['website_link'], 'facebook_link' => $_POST['facebook_link'], 'created_at' => $now);
                $data = $this->Admin_model->updateRecord($table, $data, $where);
                echo json_encode($data);
            }
        }
    }
    /***************************************************************************
    / ====================  Admin Update Service Record functions ==================/
    /**************************************************************************/
    public function updateServiceRecord() {
        $table = "tbl_service_category";
        $where = array('serviceCatId' => $_POST['catServiceId']);
        $data = array('serviceCat_name' => $_POST['servicecat']);
        $this->Admin_model->updateRecord($table, $data, $where);
        $data = array('id' => $_POST['catServiceId'], 'serviceCat_name' => $_POST['servicecat']);
        echo json_encode($data);
    }
    public function changeOrderStatus()
    {
      if (isset($_POST['contact_id'])) {
          $table = "tbl_contact";
          $where = array('contact_id' => $_POST['contact_id']);
          $data = array('status' => $_POST['order_status']);
          $res = $this->Admin_model->updateRecord($table, $data, $where);
          echo json_encode("success");
      }else  if (isset($_POST['order_id'])) {
            $table = "tbl_orders";
            $where = array('order_id' => $_POST['order_id']);
            $data = array('status' => $_POST['order_status']);
            $res = $this->Admin_model->updateRecord($table, $data, $where);
            echo json_encode("success");
        }else
      {
        echo json_encode("error");
      }


    }

    public function deleteOrder()
    {
        echo $_POST['del_id'];

        $table = "tbl_orders";
        $where = array('order_id' => $_POST['del_id']);

        $res = $this->Admin_model->deleteRecord($table, $where);
    }
}
?>
