<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Eraxon_assets_allocation extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('staff_model');
        $this->load->model('roles_model');
        $this->load->model('Eraxon_assets_allocation_model');
    }

    public function index()
    {
        if (!has_permission('asset-allocation', '', 'view_own') && !has_permission('asset-allocation', '', 'view')) {
            access_denied('Assets allocation');
        }


        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('eraxon_assets', 'tables/assets_allocation_table'));
        }
        $data['title'] = "Assets Allocation";
        $this->load->view('eraxon_assets/allocation/index', $data);
    }

    public function add_allocation()
    {
        if (!has_permission('asset-allocation', '', 'create')) {
            access_denied('Allocation View');
        }
        close_setup_menu();
        if (has_permission('asset-allocation', '', 'create')) {
            $post = $this->input->post();
            if (!empty($post)) {

                $post_data = [];
                $item = $post['item_id'];
                $serial_numbers = $post['serial-number'];
                $staff_id = $post['allocated_staff_id'];
                $qty = $post['quantity'];
                $date = $post['payment_date'];

                $allocation_master = array(
                    'staff_id' => $staff_id,
                    'allocation_date' => date('Y-m-d', strtotime($date))
                );



                foreach ($item as $index => $i) {
                    $temp = array(
                        "item_id" => $i,
                        "serial_number" => $serial_numbers[$index],
                        "qty" => $qty[$index],

                    );
                    array_push($post_data, $temp);
                }

                $focus = $this->Eraxon_assets_allocation_model->allocate_item($post_data, $allocation_master);
                $response = [
                    'url' => admin_url('eraxon_assets/eraxon_assets_allocation'),
                    'status' => "success",
                    "message" => "Item Allocated Successfully"
                ];

                echo json_encode($response);
                return 0;

            }
            $role_id = $this->roles_model->get_roleid_by_name('CSR');
            $data['staff_members'] = $this->staff_model->get('', ['active' => 1, 'role' => $role_id]);
            $data['title'] = "Add Allocation";
            $this->load->view('allocation/add_allocation', $data);
        } else {
            access_denied('Allocation');
        }
    }

    public function get_item_master()
    {
        $word = $this->input->get('term');
        $items = $this->Eraxon_assets_allocation_model->get_item($word);
        echo (json_encode($items));
    }

    public function staff_allocated_items($id)
    {
        //  $this->load->model('staff_model');

        $data = $this->Eraxon_assets_allocation_model->get_by_id_allocation($id);

        $this->load->view('allocation/user_allocateditems', $data);
    }

    public function delete($id)
    {
        $this->Eraxon_assets_allocation_model->delete_allocation($id);
        set_alert('success', "Allocation Deleted");
        redirect(admin_url('eraxon_assets/eraxon_assets_allocation'));

    }


}
