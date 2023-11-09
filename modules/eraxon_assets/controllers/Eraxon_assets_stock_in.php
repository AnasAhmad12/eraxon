<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Eraxon_assets_stock_in extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Eraxon_assets_stock_in_model');
        $this->load->model(['Eraxon_assets_custom_fields_model', 'Eraxon_assets_items_model']);

    }

    public function index()
    {

        if (!has_permission('asset-purchase', '', 'view')) {
            access_denied('Assets Purchase');
        }
        close_setup_menu();
        if (has_permission('asset-purchase', '', 'view')) {
            if ($this->input->is_ajax_request()) {
                $this->app->get_table_data(module_views_path('eraxon_assets', 'tables/assets_stock_in_table'));
            }
            $data['title'] = "Assets Categories";
            $this->load->view('eraxon_assets/purchase/stock-index', $data);
        }
    }

    public function add_stock_in()
    {
        if (!has_permission('asset-purchase', '', 'create')) {
            access_denied('Stock In View');
        }
        close_setup_menu();
        if (has_permission('asset-purchase', '', 'create')) {
            $post = $this->input->post();
            if (!empty($post)) {
                $is_serial_no = $this->input->post('has_serial');
                
                // $this->form_validation->set_rules('serial_number[]', 'Serial Number', 'callback_check_serial_number');
                $this->form_validation->set_rules('rate[]', 'Rate', 'required');

                if (false == $this->form_validation->run()) {
                    $response = [
                        'status' => 'error',
                        'message' => validation_errors()
                    ];
                } else {

                    $master_stock = array(
                        "purchase_date" => $post['payment_date'],
                        "total" => $post['subtotal'],
                        "status" => $post['payment_status'],
                        "invoice_image" => "",
                        "invoice_number" => $post["invoice_number"],
                        "datecreated" => date("Y-m-d"),
                    );

                    $item_ids = $post['item_id'];
                    $quantity = $post['quantity'];
                    $serial_number = $post['serial-number'];
                    $rate = $post['rate'];
                    $stock_inventory = [];
                    foreach ($item_ids as $index => $i) {
                        $item = $this->Eraxon_assets_items_model->get_by_id_product($i);
                        $temp = array(
                            "item_id" => $i,
                            "serial_number" => $serial_number[$index],
                            "purchase_price" => $rate[$index],
                            "quantity_added" => $quantity[$index]
                        );
                        array_push($stock_inventory, $temp);
                    }
                    if (!empty($_FILES['invoice_image']['name'])) {
                        $upload_path = module_dir_path('eraxon_assets', 'uploads/invoice/');
                        $config['upload_path'] = $upload_path;
                        $config['allowed_types'] = 'gif|jpg|jpeg|png';
                        $this->load->library('upload', $config);
                        if ($this->upload->do_upload('invoice_image')) {
                            $upload_data = $this->upload->data();
                            $file_name = $upload_data['file_name'];
                            $master_stock['invoice_image'] = $file_name;
                        } else {
                            $error = $this->upload->display_errors();
                        }
                    }
                    $response=  $this->Eraxon_assets_stock_in_model->add_stock($master_stock,$stock_inventory);  
                    // set_alert('success', 'Stock In Purchase Created successfully');
                    // redirect(admin_url('eraxon_assets/eraxon_assets_stock_in'), 'refresh');
                    $response = [
                        'url' => admin_url('eraxon_assets/eraxon_assets_stock_in'),
                        'status' => "success",
                        "message" => "Data Successfully Sent"
                    ];


                }
                echo json_encode($response);
                return 0;
            }
            $data['title'] = "Add Stock Purchase";
            $data['approval']="";

            $this->load->view('purchase/stock-in', $data);
        } else {
            access_denied('Stock In');
        }
    }

    public function check_serial_number() {
     $serial_numbers=$this->input->post('serial-number');
    if (is_array($serial_numbers)) {
        foreach ($serial_numbers as $serial_number) {
        
            return $this->Eraxon_assets_stock_in_model->is_serial_number_unique($serial_number);
           
        }
    } else {
        // Handle the case where $serial_numbers is not an array
        $this->form_validation->set_message('check_serial_number', 'Invalid input for Serial Number.');
        return FALSE;
    }

    return TRUE;
    
    }

    public function edit($id)
    {
        if (!has_permission('asset-purchase', '', 'edit')) {
            access_denied('Stock In Edit');
        }
        close_setup_menu();

        if (has_permission('asset-purchase', '', 'edit')) {
            $original_product = $data['item'] = $this->Eraxon_assets_stock_in_model->get_by_id_stock_purchase($id);
            if (empty($original_product)) {
                set_alert('danger', _l('not_found_products'));
                redirect(admin_url('eraxon_assets/eraxon_assets_stock_in'), 'refresh');
            }
            $post = $this->input->post();

            if (!empty($post)) {



               
                $is_serial_no = $this->input->post('has_serial');
                $serial_numbers = $this->input->post('serial_number');
                $rule = ($is_serial_no == 1) ? 'required' : 'trim';
                $this->form_validation->set_rules('rate[]', 'Rate', 'required');
                foreach ($serial_numbers as $index => $sno) {
                    $field_name = 'serial_number[' . $index . ']';
                    $this->form_validation->set_rules($field_name, 'Serial Number', $rule);
                }
                if (false == $this->form_validation->run()) {
                    // set_alert('danger', preg_replace("/\r|\n/", '', validation_errors()));
                    $response = [
                        'status' => 'error',
                        'message' => validation_errors()
                    ];
                } else {



                    $master_stock = array(
                        "purchase_date" => $post['payment_date'],
                        "total" => $post['subtotal'],
                        "status" => $post['payment_status'],
                        "datecreated" => date("Y-m-d"),
                    );

                  
                    $item_ids = $post['item_id'];
                    $quantity = $post['quantity'];
                    $serial_number = $post['serial-number'];
                    $rate = $post['rate'];
                    $stock_inventory = [];
                   
                    $master_id=$this->Eraxon_assets_stock_in_model->get_master_id_item($item_ids[0],$serial_number[0]);
                    $this->Eraxon_assets_stock_in_model->delete_by_id($master_id,true);
                   
                    foreach ($item_ids as $index => $i) {
                        $item = $this->Eraxon_assets_items_model->get_by_id_product($i);
                        $temp = array(
                            "item_id" => $i,
                            "stock_in_master_id"=>$master_id,
                            "serial_number" => $serial_number[$index],
                            "purchase_price" => $rate[$index],
                            "quantity_added" => $quantity[$index]
                        );
                        array_push($stock_inventory, $temp);
                        
                    }
                    if (!empty($_FILES['invoice_image']['name'])) {
                        $upload_path = module_dir_path('eraxon_assets', 'uploads/invoice/');
                        $config['upload_path'] = $upload_path;
                        $config['allowed_types'] = 'gif|jpg|jpeg|png';
                        $this->load->library('upload', $config);
                        if ($this->upload->do_upload('invoice_image')) {
                            $upload_data = $this->upload->data();
                            $file_name = $upload_data['file_name'];
                            $master_stock['invoice_image'] = $file_name;
                        } else {
                            $error = $this->upload->display_errors();
                        }
                       
                    }
                    $response=  $this->Eraxon_assets_stock_in_model->edit_stock($master_stock,$stock_inventory,$master_id);  
                    if($post['payment_status']==1){
                      $focus=  $this->Eraxon_assets_stock_in_model->add_stock_to_inventory($stock_inventory);
                    }
                    // set_alert('success', 'Stock In Purchase Created successfully');
                    // redirect(admin_url('eraxon_assets/eraxon_assets_stock_in'), 'refresh');
                    $response = [
                        'url' => admin_url('eraxon_assets/eraxon_assets_stock_in'),
                        'status' => "success",
                        "message" => "Data Successfully Updated"
                    ];
                }

                echo json_encode($response);
                return 0;
            }

            $data['title'] = "Edit Stock Purchase";
            $data['approval']=get_option('stock_in_purchase_approval');
            $this->load->view('purchase/stock-in', $data);
        } else {
            access_denied('Edit Stock In');
        }
    }




    public function delete($id)
    {
        if (!is_admin()) {
            access_denied('Delete Stock In');
        }
        if (!$id) {
            redirect(admin_url('eraxon_assets/eraxon_assets_stock_in'));
        }
        $response = $this->Eraxon_assets_stock_in_model->delete_by_id($id);

        if (true == $response) {
            set_alert('success', "Stock Deleted");
        } else {
            set_alert('warning', "Error in Stock Deleting");
        }
        redirect(admin_url('eraxon_assets/eraxon_assets_stock_in'));
    }

    public function get_item_master()
    {
        $word = $this->input->get('term');
        $items = $this->Eraxon_assets_stock_in_model->get_item_with_category($word);
        echo (json_encode($items));
    }

}
