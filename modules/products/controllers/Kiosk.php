<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Kiosk extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['products_model', 'variations_model', 'Reports_model', 'taxes_model','currencies_model','order_model','eraxon_wallet/eraxon_wallet_model']);
    }

    public function index()
    {
       /* if (0 != get_option('product_menu_disabled')) {
            set_alert('warning', _l('access_denied'));
            redirect(site_url());
        }*/
        $this->load->model('product_category_model');
        $data['title']              = _l('products');
        $data['products']           = $this->products_model->get_by_id_product();
        $data['product_categories'] = $this->product_category_model->get();
        //$this->data($data);
        $this->load->view('kiosk',$data);
        //$this->layout();
    }

    public function add_cart()
    {
        $product_id           = $this->input->post('product_id');
        $product_variation_id = $this->input->post('product_variation_id');
        $quantity             = $this->input->post('quantity');
        $newdata['cart_data'] = $this->session->cart_data;
        if (empty($newdata['cart_data'])) {
            $newdata['cart_data'] = [
                ['product_id' => $product_id, 'product_variation_id' => $product_variation_id, 'quantity' => $quantity]
            ];
            $this->session->set_userdata($newdata);
        } else {
            $cart_item_exist = false;
            foreach ($newdata['cart_data'] as $cart_item_index => $cart_item) {
                if ($cart_item['product_id'] == $product_id && $cart_item['product_variation_id'] == $product_variation_id) {
                    $newdata['cart_data'][$cart_item_index]['quantity'] = $quantity;
                    $cart_item_exist = true;
                }
            }
            if (!$cart_item_exist) {
                $newdata['cart_data'][] = ['product_id' => $product_id, 'product_variation_id' => $product_variation_id, 'quantity' => $quantity];
            }
            $newdata['cart_data'] = $this->sort_cart($newdata['cart_data']);
            $this->session->set_userdata($newdata);
        }
        
        echo json_encode($this->session->cart_data);
    }

    public function filter()
    {
        $p_category_id = $this->input->post('p_category_id');
        $cart_data     = $this->session->cart_data;
        $products      = $this->products_model->get_category_filter($p_category_id);
        $base_currency = $this->currencies_model->get_base_currency();
        foreach ($products as $key => $value) {
            $products[$key]['cart_data']          = $this->get_cart_product($value['id']);
            $products[$key]['product_image_url']  = module_dir_url('products', 'uploads') . '/' . $value['product_image'];
            $products[$key]['no_image_url']       = module_dir_url('products', 'uploads') . '/image-not-available.png';
            $products[$key]['base_currency_name'] = $base_currency->name;
            $taxes                                = unserialize($value['taxes']);
            $total_tax                            = 0;
            if (!empty($taxes)) {
                foreach ($taxes as $tax) {
                    if (!is_array($tax)) {
                        $tmp_taxname = $tax;
                        $tax_array   = explode('|', $tax);
                    } else {
                        $tax_array   = explode('|', $tax['taxname']);
                        $tmp_taxname = $tax['taxname'];
                        if ('' == $tmp_taxname) {
                            continue;
                        }
                    }
                    $total_tax += $tax_array[1];
                }
            }
            $products[$key]['total_tax'] = $total_tax;
            $products[$key]['qty'] = _l('qty');
            $products[$key]['add_to_cart'] = _l('add_to_cart');
            $products[$key]['update_cart'] = _l('update_cart');
            $products[$key]['out_of_stock'] = _l('out_of_stock');
        }
        echo json_encode($products);
    }

    private function get_cart_product($product_id)
    {
        $cart_data     = $this->session->cart_data;
        if (!empty($cart_data)) {
            foreach ($cart_data as $cart_item) {
                if ($cart_item['product_id'] == $product_id) {
                    return $cart_item;
                }
            }
        }

        return [];
    }

    private function get_cart_product_ids()
    {
        $cart_data     = $this->session->cart_data;
        $cart_product_ids = [];
        if (!empty($cart_data)) {
            foreach ($cart_data as $cart_item) {
                $cart_product_ids[] = $cart_item['product_id'];
            }
        }

        return $cart_product_ids;
    }


    public function place_order($product_id = false)
    {
       /* if (0 != get_option('product_menu_disabled')) {
            $this->session->unset_userdata('cart_data');
            set_alert('warning', _l('access_denied'));
            redirect(site_url());
        }*/
        $this->load->model('products/order_model');
        /*if (!is_client_logged_in()) {
            set_alert('warning', _l('clients_login_heading_no_register'));
            redirect(site_url(''));
        }*/
        $message          = '';
        $post = $this->input->post();
        unset($post['taxes']);
        unset($post['shipping_cost']);
        if (!empty($post)) {
            $post['product_items'] = $this->sort_cart($post['product_items']);
            $return_data = $this->order_model->add_invoice_staff_order($post);
            if ($return_data['status']) {
                $this->session->unset_userdata('cart_data');
                set_alert('success', _l('order_success'));

                redirect(site_url('products/kiosk/staff_invoice/' . $return_data['invoice_id'] . '/' . $return_data['invoice_hash']), 'refresh');
                /*if ($return_data['single_invoice']) {
                    redirect(site_url('invoice/' . $return_data['invoice_id'] . '/' . $return_data['invoice_hash']), 'refresh');
                }*/
               // redirect(site_url('clients/invoices'), 'refresh');
            }
            if (!$return_data['status']) {
                set_alert('error', _l('order_fail'));
                $message .= $return_data['message'];
            }
        }
        $cart_data = $this->sort_cart($this->session->cart_data);
        if (empty($cart_data)) {
            set_alert('danger', _l('Cart is empty'));
            redirect(site_url('products/kiosk/'));
        }
        $data['products'] = $product = $this->products_model->get_by_cart_product($cart_data);
        if (empty($product)) {
            set_alert('danger', _l('Products in Cart not found'));
            redirect(site_url('products/kiosk/'));
        }
        $all_taxes        = [];
        $init_tax         = [];
        $apply_shipping   = false;
        foreach ($product as $value) {
            if (!$value->is_digital) {
                if ((int) $value->quantity_number < 1) {
                    $this->remove_cart($value->id, $value->product_variation_id ?? '', true);
                    $message .= $value->product_name . ' is out of stock so removed from cart <br>';
                    continue;
                }
                if ((int) $value->quantity > (int) $value->quantity_number) {
                    $value->quantity = $value->quantity_number;
                    $message         .= $value->product_name . ' is only ' . $value->quantity_number . ' in stock so quantity reduced to that quantity <br>';
                }
            }
            $value->apply_shipping = false;
            if (!$value->recurring && !$value->is_digital) {
                $value->apply_shipping = true;
                $apply_shipping = true;
            }
            $taxes_arr       = [];
            $value->taxname  = $taxes  = unserialize($value->taxes);
            if ($taxes) {
                foreach ($taxes as $tax) {
                    if (!is_array($tax)) {
                        $tmp_taxname = $tax;
                        $tax_array   = explode('|', $tax);
                    } else {
                        $tax_array   = explode('|', $tax['taxname']);
                        $tmp_taxname = $tax['taxname'];
                        if ('' == $tmp_taxname) {
                            continue;
                        }
                    }
                    $init_tax[$tmp_taxname][]  = ($value->rate * $value->quantity) / 100 * $tax_array[1];
                    $all_taxes[$tmp_taxname]   = $taxes_arr[]   = ['name' => $tmp_taxname, 'taxrate' => $tax_array[1], 'taxname' => $tax_array[0]];
                }
            }
            $value->taxes = $taxes_arr;
        }
        $shipping_cost = 0;
        $base_shipping_cost = 0;
        $shipping_tax = 0;
        if ($apply_shipping) {
            $taxname = (!empty((get_option('product_tax_for_shipping_cost')))) ? unserialize(get_option('product_tax_for_shipping_cost')) : '';
            $shipping_cost = $base_shipping_cost = get_option('product_flat_rate_shipping');
            $shipping_tax = 0;
            if ($taxname) {
                foreach ($taxname as $tax) {
                    if (!is_array($tax)) {
                        $tmp_taxname = $tax;
                        $tax_array   = explode('|', $tax);
                    } else {
                        $tax_array   = explode('|', $tax['taxname']);
                        $tmp_taxname = $tax['taxname'];
                        if ('' == $tmp_taxname) {
                            continue;
                        }
                    }
                    $shipping_tax  += $tax_array[1];
                    $shipping_cost += ($base_shipping_cost) / 100 * $tax_array[1];
                }
            }
        }
        $data['shipping_cost']    = $shipping_cost;
        $data['shipping_base']    = $base_shipping_cost;
        $data['shipping_tax']     = $shipping_tax;
        $data['all_taxes']        = $all_taxes;
        $data['init_tax']         = $init_tax;
        $data['message']          = $message;
        $data['title']            = _l('confirm') . ' ' . _l('place_order');
        $data['base_currency']    = $this->currencies_model->get_base_currency();
        //$this->data($data);
        $this->load->view('kiosk/place_order',$data);
        //$this->layout();
    }

    private function sort_cart($cart_data)
    {
        $cart_data_keys = array_keys($cart_data);
        $first_index = 0;
        while ($first_index < count($cart_data_keys) - 1) {
            $sorted_count = 0;
            for ($second_index = $first_index + 2; $second_index < count($cart_data_keys); $second_index++) {
                if ($cart_data[$cart_data_keys[$first_index]]['product_id'] == $cart_data[$cart_data_keys[$second_index]]['product_id']) {
                    $replace_cart_item = $cart_data[$cart_data_keys[$second_index]];
                    for ($third_index = $second_index; $third_index > $first_index + $sorted_count + 1; $third_index--) {
                        $cart_data[$cart_data_keys[$third_index]] = $cart_data[$cart_data_keys[$third_index - 1]];
                    }
                    $cart_data[$cart_data_keys[$first_index + $sorted_count + 1]] = $replace_cart_item;
                    $sorted_count = $sorted_count + 1;
                }
            }
            $first_index = $first_index + $sorted_count + 1;
        }
        return $cart_data;
    }
    public function remove_cart($product_id = null, $product_variation_id = null, $return = false)
    {
        if (empty($product_id)) {
            $product_id = $this->input->post('product_id');
        }
        if (empty($product_variation_id)) {
            $product_variation_id = $this->input->post('product_variation_id');
        }
        $newdata['cart_data'] = $this->session->cart_data;
        foreach ($newdata['cart_data'] as $key => $value) {
            if ($product_id == $value['product_id'] && $product_variation_id == $value['product_variation_id']) {
                unset($newdata['cart_data'][$key]);
            }
        }
        $cart_data = [];
        foreach ($newdata['cart_data'] as $value) {
            $cart_data[] = $value;
        }
        $newdata['cart_data'] = $cart_data;
        $this->session->set_userdata($newdata);
        if (empty($newdata['cart_data'])) {
            set_alert('danger', _l('Cart is empty'));
            $res['status'] = false;
            if ($return) {
                return json_encode($res);
            }
            echo json_encode($res);

            return;
        }
        $res['status'] = true;
        $res['cart_data'] = $newdata['cart_data'];
        if ($return) {
            return json_encode($res);
        }
        echo json_encode($res);
    }

    public function get_currency($id)
    {
        echo json_encode(get_currency($id));
    }

    public function staff_invoice($id)
    {   
         $this->load->model('staff_model');
         $order_master = $this->order_model->get_by_id_order($id);
         $data['master_order'] = $this->order_model->get_by_id_order($id);
         $data['invoice'] = $this->order_model->get_order_with_items($id);
         $data['staff'] = $this->staff_model->get($order_master->clientid, ['active' => 1]);
         $data['base_currency']    = $this->currencies_model->get_base_currency();

         $this->load->view('kiosk/staff_invoice',$data);
    }

    public function p_status($id, $param)
    {
        if($param == 'deliver')
        {
            //$this->post->get('csrf_token_name');
            $this->order_model->order_status($id,2);
            $this->db->where('order_id',$id);
            $order_items= $this->db->get(db_prefix().'order_items')->result();


            foreach($order_items as $i){
                
                if($i->product_variation_id!==null){
                    $this->db->where('id',$i->product_variation_id);
                    $pr=$this->db->get(db_prefix() . 'product_variations')->result();
                    $current_qty=$pr[0]->quantity_number;
                    $this->db->where('id',$i->product_variation_id);
                    $this->db->update(db_prefix() . 'product_variations',['quantity_number'=>$current_qty-$i->qty]);

                    $this->db->where('id',$i->product_id);
                    $pr=$this->db->get(db_prefix() . 'product_master')->result();
                    $current_qty=$pr[0]->quantity_number;
                    $this->db->where('id',$i->product_id);
                    $this->db->update(db_prefix() . 'product_master',['quantity_number'=>$current_qty-$i->qty]);

                }else{
                    $this->db->where('id',$i->product_id);
                    $pr=$this->db->get(db_prefix() . 'product_master')->result();
                    $current_qty=$pr[0]->quantity_number;
                    $this->db->where('id',$i->product_id);
                    $this->db->update(db_prefix() . 'product_master',['quantity_number'=>$current_qty-$i->qty]);

                }
            }
            $order = $this->order_model->get_by_id_order($id);
            $wallet_id = $this->eraxon_wallet_model->get_walletid_by_staff_id($order->clientid);

            $transaction = array(
                    'wallet_id' => $wallet_id,
                    'amount_type' => 'KIOSK Order-ID-'.$id,
                    'amount' => $order->total,
                    'in_out' => 'out',
                    'created_datetime' => date('Y-m-d H:i:s'),
                    );

            $tid =  $this->eraxon_wallet_model->add_transaction($transaction);
            $this->order_model->update_order_transaction($id,$tid);

            $data['success'] = 1;
            echo json_encode($data);
        }elseif($param == 'delete')
        {
            $res = $this->order_model->delete_order($id);
            $order = $this->order_model->get_by_id_order($id);
            if($order->trans_id > 0 )
            {
                $this->eraxon_wallet_model->delete_transaction($order->trans_id);
            }
            if($res)
            {
               $data['success'] = 1;
               echo json_encode($data); 
            }
            
        }
    }



}
