<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Eraxon_assets_custom_fields extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model(['Eraxon_assets_custom_fields_model','Eraxon_assets_category_model']);
    }

    public function index()
    {
        //fix permissions
        if (!has_permission('asset-custom_fields', '', 'view')) {
            access_denied('Custom Fields');
        }
        close_setup_menu();
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('eraxon_assets', 'tables/assets_custom_fields_table'));
        }
        $data['title']         = "Custom Fields";
        $this->load->view('customfields/asset_custom_fields', $data);
    }

    public function add()
    {
       
        close_setup_menu();
       
            $post          = $this->input->post();
            if (!empty($post)) {
                $this->form_validation->set_rules('cf_name', 'variation name', 'required|is_unique[assets_custom_field.name]');
                $this->form_validation->set_rules('custom_field_category', 'Custom Field Category', 'required');
                
                if (false == $this->form_validation->run()) {
                    set_alert('danger', preg_replace("/\r|\n/", '', validation_errors()));
                } else {
                    $data = [
                        'name'        => $post['cf_name'],
                        'description' => $post['cf_description'],
                        'assets_category_id'=>$post['custom_field_category']
                    ];
                    $inserted_id    = $this->Eraxon_assets_custom_fields_model->add_custom_field($data);
                    
                    if ($inserted_id) {
                        if (isset($post['values'])) {
                            $this->Eraxon_assets_custom_fields_model->add_variation_values($inserted_id, $post['values']);
                        }
                        set_alert('success', 'Variation Added successfully');
                        redirect(admin_url('eraxon_assets/eraxon_assets_custom_fields'), 'refresh');
                    } else {
                        set_alert('warning', _l('Error Found - Variation not inserted'));
                    }
                }
            }
            $data['title']              ="Add New Custom Fields";
            $data['action']             = "Custom Fields";
            $data['custom_field_categories']=$this->Eraxon_assets_category_model->get_category_for_custom_fields();
            // var_dump($data['custom_field_categories']);
            // return 0;
            $this->load->view('customfields/add', $data);
        
    }

    public function edit($id)
    {
        if (!has_permission('asset-custom_fields', '', 'edit')) {
            access_denied('Custom Field View');
        }
        close_setup_menu();
        if (has_permission('asset-custom_fields', '', 'edit')) {
            $original_custom_fields = $data['variation'] = $this->Eraxon_assets_custom_fields_model->get($id, true);
            if (empty($original_custom_fields)) {
                set_alert('danger', "Not Found");
                redirect(admin_url('eraxon_assets/eraxon_assets_custom_fields'), 'refresh');
            }
            $post = $this->input->post();
           
            if (!empty($post)) {
                $this->form_validation->set_rules('cf_name', 'variation name', 'required');
                if ($original_custom_fields->name != $post['cf_name']) {
                    $this->form_validation->set_rules('cf_name', 'custom field name', 'required|is_unique[variations.name]');
                }
                if (false == $this->form_validation->run()) {
                    set_alert('danger', preg_replace("/\r|\n/", '', validation_errors()));
                } else {
                    $data = [
                        'name'        => $post['cf_name'],
                        'description' => $post['cf_description'],
                    ];
                    $result = $this->Eraxon_assets_custom_fields_model->edit($data, $id);
                    if (isset($post['values'])) {
                        $this->Eraxon_assets_custom_fields_model->edit_variation_values($id, $post['values']);
                    }
                    if ($result) {
                        set_alert('success', 'Custom Field Updated successfully');
                        redirect(admin_url('eraxon_assets/eraxon_assets_custom_fields'));
                    } else {
                        set_alert('warning', _l('Error Found Or You Have not made any changes'));
                    }
                }
            }
            $data['title']              = _l('edit', 'variation');
            $data['custom_field_categories']=$this->Eraxon_assets_category_model->get_category_for_custom_fields();
            // var_dump($data);
            // return 0;
            $this->load->view('customfields/add', $data);
        } else {
            access_denied('products');
        }
    }

    public function values()
    {
        $variation_id     = $this->input->post('variation_id');
        $variation_values = $this->eraxon_assets_custom_fields_model->get_values($variation_id);
        echo json_encode($variation_values);
    }

    public function delete($id)
    {
        if (!is_admin()) {
            access_denied('Delete Variation');
        }
        if (!$id) {
            redirect(admin_url('eraxon_assets/eraxon_assets_custom_fields'));
        }
        $response = $this->Eraxon_assets_custom_fields_model->delete($id);
        if (true == $response) {
            set_alert('success', _l('deleted', _l('variations')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('variations')));
        }
        redirect(admin_url('eraxon_assets/eraxon_assets_custom_fields'));
    }
}