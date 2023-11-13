<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class eraxon_assets_loss_management extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('staff_model');
        $this->load->model('roles_model');
        $this->load->model('Eraxon_assets_allocation_model');
        $this->load->model('Eraxon_assets_loss_model');

    }

    public function index()
    {
        if (!has_permission('asset-loss', '', 'view')&&!has_permission('asset-loss', '', 'view_own')) {
            access_denied('Assets Loss Management');
        }



        $staff_id=get_staff_user_id();
        $staffAllocatedItems = $this->Eraxon_assets_allocation_model->get_staff_items($staff_id);
        $data["staff_items"]=$staffAllocatedItems;
        $data['staff_id']=$staff_id;
        $data['title'] = "Assets Allocation";
        $data['loss']= $this->Eraxon_assets_loss_model->get_loss();
        $this->load->view('eraxon_assets/asset_loss_management/index', $data);
    }

    public function add_loss()
    {
        if ($this->input->post()) {

            $data=$this->input->post('item');
            $item_id=explode("-",$data)[0];
            $serial_number=explode("-",$data)[1];

            $post_loss_data=array(
                "report_date" => date("Y:m:d"),
                "staff_id"      => $this->input->post('staff_id'),
                "item_id" => $item_id,
                "serial_number" => $serial_number,
                "reason" => $this->input->post('loss_reason'),
                "request_status" => 0,
                "item_status" => 0
            );

            $this->Eraxon_assets_loss_model->post_loss_db($post_loss_data);

            $followers_id = get_option('stock_loss_approval');
            $subject = "Loss Reported Item ID : ".$item_id." Serial Number".$serial_number;
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

                

            set_alert('success', "Loss Reported");

            
        }

    }

    public function edit_loss(){
        $id=$this->input->post('id_loss');
        $request_status= $this->input->post('request_status');
        $item_status= $this->input->post('item_status');

        
        echo $id;
        $this->Eraxon_assets_loss_model->edit_loss($id,$request_status,$item_status);
        set_alert('success', "Status Updated");
    }



}
