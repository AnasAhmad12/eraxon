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
        if (!has_permission('assets-items', '', 'view')) {
            access_denied('Assets Items View');
        }
        close_setup_menu();
        // $data['title'] = _l('products');
        if (has_permission('assets-items', '', 'view')) {
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
        if (!has_permission('assets-item', '', 'view')) {
            access_denied('Item View');
        }
        close_setup_menu();
        if (has_permission('assets-item', '', 'view')) {
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
        if (!has_permission('assets-item', '', 'view')) {
            access_denied('Items View');
        }
        close_setup_menu();
		
        if (has_permission('assets-item', '', 'view')) {
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

    public function order_history()
    {
        /*if (!is_admin()) {
            access_denied('Order History');
        }*/
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('products', 'tables/staff_order_history'));
        }
        $this->load->model(['currencies_model']);
        $data['base_currency'] = $this->currencies_model->get_base_currency();
        $data['title']         = _l('order_history');
        $this->load->view('order_history', $data);
    }

    public function order_report()
    {
        $chart_week_data = $this->Reports_model->chart_orders_of_the_week();
        $this->load->vars('categories', toPlainArray($chart_week_data['days']));
        if (!empty($chart_week_data['series'])) {
            $this->load->vars('week_series', preg_replace('/"([^"]+)"\s*:\s*/', '$1:', json_encode($chart_week_data['series'])));
        } else {
            $this->load->vars('week_series', '[]');
        }
        $chart_month_data = $this->Reports_model->chart_orders_per_month();
        $this->load->vars('month_categories', toPlainArray($chart_month_data['months']));
        if (!empty($chart_month_data['months'])) {
            $this->load->vars('month_series', preg_replace('/"([^"]+)"\s*:\s*/', '$1:', json_encode($chart_month_data['series'])));
        } else {
            $this->load->vars('month_series', '[]');
        }
        $chart_year_data = $this->Reports_model->chart_orders_per_year();
        $this->load->vars('year_categories', toPlainArray($chart_year_data['years']));
        if (!empty($chart_year_data['years'])) {
            $this->load->vars('year_series', preg_replace('/"([^"]+)"\s*:\s*/', '$1:', json_encode($chart_year_data['series'])));
        } else {
            $this->load->vars('year_series', '[]');
        }
        $this->load->vars('products', $this->products_model->get_by_id_product());
        $this->load->view('reports/order_report');
    }

    public function order_report_products()
    {


        $chart_week_data = $this->reports_products_model->chart_orders_of_the_week();
        $this->load->vars('categories', toPlainArray($chart_week_data['days']));
        if (!empty($chart_week_data['series'])) {
            $this->load->vars('week_series', preg_replace('/"([^"]+)"\s*:\s*/', '$1:', json_encode($chart_week_data['series'])));
        } else {
            $this->load->vars('week_series', '[]');
        }

        $chart_month_data = $this->reports_products_model->chart_orders_per_month();
        $this->load->vars('month_categories', toPlainArray($chart_month_data['months']));
        if (!empty($chart_month_data['months'])) {
            $this->load->vars('month_series', preg_replace('/"([^"]+)"\s*:\s*/', '$1:', json_encode($chart_month_data['series'])));
        } else {
            $this->load->vars('month_series', '[]');
        }
        $chart_year_data = $this->reports_products_model->chart_orders_per_year();
        $this->load->vars('year_categories', toPlainArray($chart_year_data['years']));
        if (!empty($chart_year_data['years'])) {
            $this->load->vars('year_series', preg_replace('/"([^"]+)"\s*:\s*/', '$1:', json_encode($chart_year_data['series'])));
        } else {
            $this->load->vars('year_series', '[]');
        }

        $total_recieveables=$this->reports_products_model->year_recieveables();
        if (!empty($total_recieveables)) {
            $this->load->vars('total_recieveable',$total_recieveables);

        } else {
            $this->load->vars('total_recieveable', '[]');
        }
        $this->load->vars('products', $this->products_model->get_by_id_product());
        $this->load->view('reports/order_report');
    }

    public function custom_report_products()
    {
        $posted_data       = $this->input->post();
        $selected_products = implode('", "', $posted_data['products_name']);
        $from_date         = $posted_data['from'];
        $to_date           = $posted_data['to'];
        $from_date_st      = strtotime($from_date);
        $to_date_st        = strtotime($to_date);
        if ($from_date_st > $to_date_st) {
            $return_data['status'] = 'error';
        } else {
            $custom_chart_data              = $this->Reports_model->chart_custom_date_range($selected_products, $from_date, $to_date);
            $return_data['date_series']     = null;
            $return_data['date_categories'] = null;
            if (count($custom_chart_data['date_range']) > 0) {
                $return_data['date_series']     = preg_replace('/"([^"]+)"\s*:\s*/', '$1:', json_encode($custom_chart_data['series']));
                $return_data['date_categories'] = toPlainArray($custom_chart_data['date_range']);
                $return_data['date_series']     = $custom_chart_data['series'];
                $return_data['date_categories'] = $custom_chart_data['date_range'];
            }
        }
        echo json_encode($return_data);
    }

    public function quantities_report_products()
    {
        /*if (!has_permission('quantities', '', 'view')) {
            access_denied('quantities View');
        }*/
        close_setup_menu();
        $data['title'] = _l('quantities_report');
        if (has_permission('products', '', 'view')) {
            if ($this->input->is_ajax_request()) {
                $this->app->get_table_data(module_views_path('products', 'quantities'));
            }
            $data['title'] = _l('quantities');
            $this->load->model(['currencies_model']);
            $data['base_currency'] = $this->currencies_model->get_base_currency();
            $this->load->view('reports/quantities_report', $data);
        } else {
            access_denied('quantities');
        }
    }

    public function test()
    {
        $this->load->model('order_model');
        $this->order_model->update_quantity_on_invoice(45);
    }

    public function today_order_history()
    {
      
       /* if (!is_admin() || has_permission('kiosk', '', 'today_order')) {
            access_denied('Order History');
        }*/
       /* if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('products', 'tables/staff_order_history'));
        }*/
        
        $this->load->model(['currencies_model']);
        $data['base_currency'] = $this->currencies_model->get_base_currency();
        $data['title']         = _l('order_history');
        $this->load->view('today_order_history', $data);
    }

    function get_order_status_label($status)
    {
        $html = '';
        if ($status == 1) {
            $html .= '<span class="label label-danger  s-status invoice-status-2">Undelivered</span>';
        }elseif ($status == 2) {
            $html .= '<span class="label label-success  s-status invoice-status-2">Delivered</span>';
        }elseif ($status == 3) {
            $html .= '<span class="label label-warning  s-status invoice-status-2">Cancel</span>';
        }

        return $html;
    }

    public function ajax_get_todays_orders()
    {
        $todays_orders = $this->order_model->today_order();
        
        foreach($todays_orders as $order)
        {
            $order->pseudo = get_custom_field_value($order->clientid,'staff_pseudo','staff',true);
        }
        echo json_encode($todays_orders);
        // $html = '';
        // foreach($todays_orders as $order)
        // {
        //     if (has_permission('kiosk', '', 'delivery_status') && $order->status != 2) 
        //     {
        //         $options .= '<button class="btn btn-success" onclick="delivered(this,'.$order->id.')">Delivered</button> &nbsp;';
        //     }
        //     $html .='<tr><td><a href="'.admin_url('products/kiosk/staff_invoice/'.$aRow['id']).'">'.get_staff_full_name($aRow['clientid']).' ('.get_custom_field_value($aRow['clientid'],'staff_pseudo','staff',true).')</a><td>';
        //     $html .='<td>'.$order->order_date.'</td>';
        //     $html .='<td>'.$base_currency->name.' '.$order->total.'</td>';
        //     $html .='<td>'.$this->get_order_status_label($order->status).'</td>';
        //     $html .='<td>'.$options.'</td><tr>';
        // }

        // echo $html;
    }


    

    


}
