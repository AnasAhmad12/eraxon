<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Eraxon_assets_inventory extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('eraxon_assets/eraxon_assets_category_model');
    }

    public function index()
    {

        if (!has_permission('asset_inventory', '', 'view')) {
            access_denied('Assets Inventory');
        }
        
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('eraxon_assets', 'tables/assets_inventory_table'));
        }
        $data['title'] = "Inventory";
        $this->load->view('eraxon_assets/inventory', $data);
    }


}
