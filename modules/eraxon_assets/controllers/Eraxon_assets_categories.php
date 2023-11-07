<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Eraxon_assets_categories extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('eraxon_assets/eraxon_assets_category_model');
    }

    public function index()
    {

        
        if (!has_permission('asset-category', '', 'view')) {

            access_denied('Assets Category');
        }
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('eraxon_assets', 'tables/assets_category_table'));
        }
        $data['title'] = "Assets Categories";
        $this->load->view('eraxon_assets/category/eraxon_assets_categories', $data);
    }

    public function category()
    {
       
        $this->load->library('form_validation');
        if ($this->input->is_ajax_request()) {
            $data              = $this->input->post();
            $original_category = (object) [];
            if (!empty($data['assets_category_id'])) {
                $original_category = $this->eraxon_assets_category_model->get($data['assets_category_id']);
                if ($original_category->assets_category_name != $data['assets_category_name']) {
                    $this->form_validation->set_rules('assets_category_name', 'Category name', 'required|is_unique[assets_categories.assets_category_name]');
                }
            } else {
                $this->form_validation->set_rules('assets_category_name', 'Category name', 'required|is_unique[assets_categories.assets_category_name]');
            }
            $this->form_validation->set_rules('assets_category_description', 'Description', 'required');
            if (false == $this->form_validation->run()) {
                echo json_encode([
                    'success' => false,
                    'message' => validation_errors(),
                ]);

                return;
            }
            if ('' == $data['assets_category_id']) {
                $id      = $this->eraxon_assets_category_model->add($data);
                $message = $id ? _l('added_successfully',"Asset Categories") : '';
                echo json_encode([
                    'success' => $id ? true : false,
                    'message' => $message,
                    'id'      => $id,
                    'name'    => $data['assets_category_name'],
                ]);
            } else {
                $success = $this->eraxon_assets_category_model->edit($data);
                $message = '';
                if (true == $success) {
                    $message = _l('updated_successfully',"Asset Categories");
                }
                echo json_encode([
                    'success' => $success,
                    'message' => $message,
                ]);
            }
        }
    }

    public function delete_category($id)
    {
        if (!is_admin()) {
            access_denied('Delete Asset Category');
        }
        if (!$id) {
            redirect(admin_url('eraxon_assets/eraxon_assets_categories'));
        }
        $response = $this->eraxon_assets_category_model->delete($id);
        if (true == $response) {
            set_alert('success', _l('deleted',"Asset Categories"));
        } else {
            set_alert('warning', _l('problem_deleting',"Asset Categories"));
        }
        redirect(admin_url('eraxon_assets/eraxon_assets_categories'));
    }
}
