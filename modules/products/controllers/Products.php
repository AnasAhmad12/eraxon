<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Products extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model(['products_model','currencies_model', 'variations_model', 'Reports_model', 'taxes_model','order_model','reports_products_model']);
    }

    public function index()
    {
        if (!has_permission('products', '', 'view')) {
            access_denied('products View');
        }
        close_setup_menu();
		//\modules\products\core\Apiinit::ease_of_mind('products');
		//\modules\products\core\Apiinit::the_da_vinci_code('products');
        $data['title'] = _l('products');
        if (has_permission('products', '', 'view')) {
            if ($this->input->is_ajax_request()) {
                $this->app->get_table_data(module_views_path('products', 'products'));
            }
            $this->load->model(['currencies_model']);
            $data['base_currency'] = $this->currencies_model->get_base_currency();
            $data['title']         = _l('products');
            $this->load->view('list_products', $data);
        } else {
            access_denied('products');
        }
    }

    public function add_product()
    {
        if (!has_permission('products', '', 'view')) {
            access_denied('products View');
        }
        close_setup_menu();
		//\modules\products\core\Apiinit::ease_of_mind('products');
		//\modules\products\core\Apiinit::the_da_vinci_code('products');
        if (has_permission('products', '', 'view')) {
            $data['taxes'] = $this->taxes_model->get();
            $post          = $this->input->post();
            if (!empty($post)) {
                $this->form_validation->set_rules('product_name', 'product name', 'required|is_unique[product_master.product_name]');
                $this->form_validation->set_rules('product_description', 'product description', 'required');
                $this->form_validation->set_rules('product_category_id', 'product category', 'required');
                $this->form_validation->set_rules('rate', 'product rate', 'required');
                $this->form_validation->set_rules('quantity_number', 'product quantity', 'required');
                if (false == $this->form_validation->run()) {
                    set_alert('danger', preg_replace("/\r|\n/", '', validation_errors()));
                } else {
                    $data = [
                        'product_name'        => $post['product_name'],
                        'product_code'        => $post['product_code'],
                        'product_description' => $post['product_description'],
                        'product_category_id' => $post['product_category_id'],
                        'rate'                => $post['rate'],
                        'quantity_number'     => $post['quantity_number'],
                        'is_digital'          => (!empty($post['is_digital'])) ? $post['is_digital'] : 0,
                        'taxes'               => (!empty($post['taxes'])) ? serialize($post['taxes']) : 0,
                        'is_variation'        => (isset($post['is_variation'])) ? $post['is_variation'] : 0,
                        'variations'          => [],
                        'cycles'              => $post['cycles'] ?? 0,
                    ];
                    if (isset($post['recurring'])) {
                        if ('custom' == $post['recurring']) {
                            $data['recurring_type']   = $post['repeat_type_custom'];
                            $data['custom_recurring'] = 1;
                            $data['recurring']        = $post['repeat_every_custom'];
                        } else {
                            $data['recurring']        = $post['recurring'];
                        }
                    } else {
                        $data['custom_recurring'] = 0;
                        $data['recurring']        = 0;
                    }
                    if (isset($post['is_variation']) && $post['is_variation']) {
                        $data['variations']           = (isset($post['variations'])) ? $post['variations'] : [];
                    } else {
                        $data['variations']           = [];
                    }
                    $data['taxes']  = (!empty($post['taxes'])) ? serialize($post['taxes']) : '';
                    $inserted_id    = $this->products_model->add_product($data);
                    if ($inserted_id) {
                        handle_product_upload($inserted_id);
                        set_alert('success', 'Product Added successfully');
                        redirect(admin_url('products'), 'refresh');
                    } else {
                        set_alert('warning', _l('Error Found - Product not inserted'));
                    }
                }
            }
            $this->load->model(['currencies_model', 'product_category_model']);
            $data['title']              = _l('add_new', 'product');
            $data['action']             = _l('products');
            $data['product_categories'] = $this->product_category_model->get();
            $data['variations']         = $this->variations_model->get();
            $data['currencies']         = $this->currencies_model->get();
            $data['base_currency']      = $this->currencies_model->get_base_currency();

            $this->load->view('products/add_product', $data);
        } else {
            access_denied('products');
        }
    }

    public function edit($id)
    {
        if (!has_permission('products', '', 'view')) {
            access_denied('products View');
        }
        close_setup_menu();
		//\modules\products\core\Apiinit::ease_of_mind('products');
		//\modules\products\core\Apiinit::the_da_vinci_code('products');
        if (has_permission('products', '', 'view')) {
            $original_product = $data['product'] = $this->products_model->get_by_id_product($id);
            if (empty($original_product)) {
                set_alert('danger', _l('not_found_products'));
                redirect(admin_url('products'), 'refresh');
            }
            $post = $this->input->post();

            if (!empty($post)) {
                $this->form_validation->set_rules('product_name', 'product name', 'required');
                if ($original_product->product_name != $post['product_name']) {
                    $this->form_validation->set_rules('product_name', 'product name', 'required|is_unique[product_master.product_name]');
                }
                $this->form_validation->set_rules('product_description', 'product description', 'required');
                $this->form_validation->set_rules('product_category_id', 'product category', 'required');
                $this->form_validation->set_rules('rate', 'product rate', 'required');
                $this->form_validation->set_rules('quantity_number', 'product quantity', 'required');
                if (false == $this->form_validation->run()) {
                    set_alert('danger', preg_replace("/\r|\n/", '', validation_errors()));
                } else {
                    $data = [
                        'product_name'        => $post['product_name'],
                        'product_code'        => $post['product_code'],
                        'product_description' => $post['product_description'],
                        'product_category_id' => $post['product_category_id'],
                        'rate'                => $post['rate'],
                        'quantity_number'     => $post['quantity_number'],
                        'is_digital'          => (isset($post['is_digital'])) ? $post['is_digital'] : 0,
                        'is_variation'        => (isset($post['is_variation'])) ? $post['is_variation'] : 0,
                        'variations'          => [],
                        'cycles'              => $post['cycles'] ?? 0,
                    ];
                    if (0 != $original_product->recurring && 0 == $post['recurring']) {
                        $data['cycles']              = 0;
                    }
                    if (isset($post['recurring'])) {
                        if ('custom' == $post['recurring']) {
                            $data['recurring_type']   = $post['repeat_type_custom'];
                            $data['custom_recurring'] = 1;
                            $data['recurring']        = $post['repeat_every_custom'];
                        } else {
                            $data['recurring']        = $post['recurring'];
                            $data['recurring_type']   = null;
                            $data['custom_recurring'] = 0;
                        }
                    } else {
                        $data['custom_recurring'] = 0;
                        $data['recurring']        = 0;
                        $data['recurring_type']   = null;
                    }
                    if (isset($post['is_variation']) && $post['is_variation']) {
                        $data['variations']           = (isset($post['variations'])) ? $post['variations'] : [];
                    } else {
                        $data['variations']           = [];
                    }
                    $data['taxes']  = (!empty($post['taxes'])) ? serialize($post['taxes']) : '';
                    $result = $this->products_model->edit_product($data, $id);
                    handle_product_upload($id);
                    if ($result) {
                        set_alert('success', 'Product Updated successfully');
                        redirect(admin_url('products'), 'refresh');
                    } else {
                        set_alert('warning', _l('Error Found Or You Have not made any changes'));
                    }
                }
            }
            $this->load->model(['currencies_model', 'product_category_model']);
            $data['title']              = _l('edit', 'product');
            $data['product_categories'] = $this->product_category_model->get();
            $data['variations']         = $this->variations_model->get();
            $data['currencies']         = $this->currencies_model->get();
            $data['base_currency']      = $this->currencies_model->get_base_currency();
            $data['taxes']              = $data['product']->taxes;
            $this->load->view('products/add_product', $data);
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
            redirect(admin_url('products'));
        }
        $response = $this->products_model->delete_by_id_product($id);
        if (true == $response) {
            set_alert('success', _l('deleted', _l('products')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('products')));
        }
        redirect(admin_url('products'));
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
        $base_currency = $this->currencies_model->get_base_currency();
        $this->load->vars('base_currency',$base_currency);


        $role_id = $this->roles_model->get_roleid_by_name('CSR');
        $staff_members = $this->staff_model->get('', ['active' => 1,'role' => $role_id]);

        $this->load->vars('staff_members',$staff_members);

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

    public function staff_report_kiosk(){
        $posted_data       = $this->input->post();
        $staff_id          = $posted_data['staff_id'];
        $from_date         = $posted_data['from'];
        $to_date           = $posted_data['to'];
        $from_date_st      = strtotime($from_date);
        $to_date_st        = strtotime($to_date);

        if ($from_date_st > $to_date_st) {
            $return_data['status'] = 'error';
        } else {
            $return_data['data'] = $this->reports_products_model->staff_kiosk_report($staff_id, $from_date, $to_date);
        }


        echo json_encode($return_data);

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
