<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Eraxon_assets_items extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model(['Eraxon_assets_category_model','Eraxon_assets_custom_fields_model','Eraxon_assets_items_model']);
    }

    public function index()
    {
        if (!has_permission('asset_items', '', 'view')) {
            access_denied('Assets Items View');
        }
        close_setup_menu();
        // $data['title'] = _l('products');
        if (has_permission('asset_items', '', 'view')) {
            if ($this->input->is_ajax_request()) {
                $this->app->get_table_data(module_views_path('eraxon_assets', 'tables/assets_items_table'));
            }
        //     $this->load->model(['currencies_model']);
        //     $data['base_currency'] = $this->currencies_model->get_base_currency();
            $data['title']         = "Items";
            $this->load->view('items/items-index', $data);
        } else {
            access_denied('Assets Items');
        }
    }

    public function add_item()
    {
        if (!has_permission('asset_items', '', 'create')) {
            access_denied('Item View');
        }
        close_setup_menu();
        if (has_permission('asset_items', '', 'create')) {
            $post = $this->input->post();
            if (!empty($post)) {

                $this->form_validation->set_rules('item_name', 'item name', 'required|is_unique[assets_items_master.item_name]');
                $this->form_validation->set_rules('item_description', 'product description', 'required');
                $this->form_validation->set_rules('assets_category_id', 'product category', 'required');
               
                if (false == $this->form_validation->run()) {
                    set_alert('danger', preg_replace("/\r|\n/", '', validation_errors()));
                } 
                
                else {
                    $item_post_data= array(   
                    'assets_category_id'=> $post['assets_category_id'],
                    'item_name' => $post['item_name'],
                    'item_description' => $post['item_description'],
                    'item_sr_no' => $post['is_unique']
                );

               $inserted_id= $this->Eraxon_assets_items_model->add_item($item_post_data);
                    if ($inserted_id) {
                        $extra_field=[];
                        $count=0;
                     
                        foreach($post['cf_id'] as $cfid){

                            $temp=array(
                                'custom_field_id'=>$post['cf_field_id'],
                                'custom_field_value_id'=>$cfid,
                                'item_id' => $inserted_id,
                                'custom_field_name'=> $post['cf_field_name'][$count],
                                'value'=>$post['cf_value'][$count],
                            );

                            array_push($extra_field,$temp);
                            $count=$count+1;

                        }

                        $this->Eraxon_assets_items_model->add_item_custom_fields($extra_field); 
                      
                        handle_item_upload($inserted_id);
                        set_alert('success', 'Item Added successfully');
                        redirect(admin_url('eraxon_assets/eraxon_assets_items'), 'refresh');
                    } else {
                        set_alert('warning', _l('Error Found - Item not inserted'));
                    }
                }
            }
            
            $data['title']              = "Add New Item";
            $data['action']             = "Items";
            $data['item_categories'] = $this->Eraxon_assets_category_model->get();
            $this->load->view('items/add-item', $data); 
        } else {
            access_denied('products');
        }
    }

    public function get_custom_fields(){
        $id= $this->input->post('category_id');
        $custom_fields=$this->Eraxon_assets_custom_fields_model->get_custom_field_by_category($id);
        echo json_encode($custom_fields);
    }
    public function edit($id)
    {
        if (!has_permission('asset_items', '', 'edit')) {
            access_denied('Items View');
        }

        close_setup_menu();
		
        if (has_permission('asset_items', '', 'edit')) {
            $original_product = $data['item'] = $this->Eraxon_assets_items_model->get_by_id_product($id)[0];
            if (empty($original_product)) {
                set_alert('danger', _l('not_found_products'));
                redirect(admin_url('eraxon_assets/eraxon_assets_items'), 'refresh');
            }
            $post = $this->input->post();

            if (!empty($post)) {
                $this->form_validation->set_rules('item_name', 'item name', 'required');
                if ($original_product->item_name != $post['item_name']) {
                    $this->form_validation->set_rules('item_name', 'item name', 'required|is_unique[assets_items_master.item_name]');
                }
                $this->form_validation->set_rules('item_description', 'product description', 'required');
                $this->form_validation->set_rules('assets_category_id', 'item category', 'required');
        
                if (false == $this->form_validation->run()) {
                    set_alert('danger', preg_replace("/\r|\n/", '', validation_errors()));
                } else {
                   
                    $item_post_data= array(   
                        'assets_category_id'=> $post['assets_category_id'],
                        'item_name' => $post['item_name'],
                        'item_description' => $post['item_description'],
                        'item_sr_no' => $post['is_unique']);
                    $inserted_id= $this->Eraxon_assets_items_model->edit_item($item_post_data,$id);

                   
                    if ($inserted_id) {
                
                        $count=0;
                        foreach($post['cf_id'] as $cfid){
                            $temp=array(
                                'custom_field_id'=>$post['cf_field_id'],
                                'custom_field_value_id'=>$cfid,
                                'custom_field_name'=> $post['cf_field_name'][$count],
                                'value'=>$post['cf_value'][$count],
                            );
                            $this->Eraxon_assets_items_model->edit_item_custom_fields($temp,$id,$cfid); 
                            $count=$count+1;

                        }
                 
                    handle_item_upload($inserted_id);
                        set_alert('success', 'Item Updated successfully');
                        redirect(admin_url('eraxon_assets/eraxon_assets_items'), 'refresh');
                        
                    } else {
                        set_alert('warning', _l('Error Found Or You Have not made any changes'));
                    }
                }
            }
            $data['title']              = "Edit Item";
            $data['item_categories'] =  $this->Eraxon_assets_category_model->get();
            // var_dump($original_product);
          
            $this->load->view('items/add-item', $data);
        } else {
            access_denied('products');
        }
    }

    public function delete($id)
    {
     
        if (!is_admin()) {
            access_denied('Delete Product');
        }
        if (!$id) {
            redirect(admin_url('eraxon_assets/eraxon_assets_items'));
        }
     
        $response = $this->Eraxon_assets_items_model->delete_by_id_item($id);
        
        if (true == $response) {
            set_alert('success', _l('deleted', _l('products')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('products')));
        }
        redirect(admin_url('eraxon_assets/eraxon_assets_items'));
    }


}
