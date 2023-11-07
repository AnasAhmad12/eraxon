<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Eraxon_assets_request_inventory extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('staff_model');
        $this->load->model('roles_model');
        $this->load->model('Eraxon_assets_allocation_model');
        $this->load->model('Eraxon_assets_request_inventory_model');
        $this->load->model('Eraxon_assets_loss_model');

    }

    public function index()
    {
        if (!has_permission('asset-request', '', 'view') && !has_permission('asset-request', '', 'view_own')) {
            access_denied('Assets Inventory');
        }
        $data['request_inventory']= $this->Eraxon_assets_request_inventory_model->get_request_inventory();
        $this->load->view('eraxon_assets/request_inventory',$data);
    }

    public function request_inventory(){ 
        $post=$this->input->post();

        $post_data=array(
            "item_id"=>$post['item_id'],
            "serial_number" =>$post['serial_number'],
            "qty" =>1,
            "staff_id" => $post['staff_id'],
            "status" => 0,
        );

        $response=$this->Eraxon_assets_request_inventory_model->request_inventory($post_data);
       
        $followers_id = get_option('stock_in_purchase_approval');
        $subject = "User Requested Item ID : ".$post['item_id']." Serial Number".$post['serial_number'];
        $link = '';

        
                $notification_data = [
                    'description' => $subject,
                    'touserid' => $followers_id,
                    'link' => $link,
                ];

                $notification_data['additional_data'] = serialize([
                    $subject,
                ]);

                if (add_notification($notification_data)) {
                    pusher_trigger_notification([$followers_id]);
                }
       
        echo json_encode($response);

    }

    public function edit_status(){
        $id=$this->input->post('id_request');
        $status= $this->input->post('status');

        $this->Eraxon_assets_request_inventory_model->edit_status($id,$status);
        set_alert('success', "Status Updated");        
    }

    



}
