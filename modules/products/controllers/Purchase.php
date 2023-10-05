<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Purchase extends AdminController{
    

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model(['products_model','purchase_model','currencies_model']);
        
    }


    
    public function index(){

        $purchase_data['purchases']=$this->purchase_model->get_purchase_list();
        $this->load->view('products/purchase/purchase-view',$purchase_data);
    }


    public function get_product_master(){
        $product_name= array();
        $word=$this->input->get('term');
        $products=$this->products_model->get_by_name_product($word);

        foreach($products as $item){

            if($item->variations){
                foreach($item->variations as $var){
                    
                    // $test->variations=$var;
                    $name['value']="$item->product_name - $var->variation_value ($var->product_code) ";
                    $name['var_qty']=$var->quantity_number;
                    $name['selected_variation']=$var;
                    $name['product_detail']=$item;
                   
                    array_push($product_name,$name);
                  
                
                }
               
            }
            else{
                $name['value']=$item->product_name;
                $name['product_detail']=$item;    
                $name['var_qty']=$item->quantity_number;

                array_push($product_name,$name);
                }

        }



        echo json_encode($product_name);




    }


    public function add_purchase(){
        $product['products']=$this->products_model->get_by_id_product();
        $product['session_id'] = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $product['base_currency']      = $this->currencies_model->get_base_currency();
        $this->load->view('products/purchase/add-purchase',$product);
    }

    public function add_purchase_item(){
    
        $purchase_items= array(
            'product_id'  => $this->input->post("id"),
            'product_name' => $this->input->post("product_name"),
            'net_unit_cost'=>$this->input->post("rate"),
            'quantity'=>1.0,
            'subtotal' =>$this->input->post("rate"),
            'session_id'=>$this->input->post("sessionId"),
        );


       
        $variations=$this->input->post("variations");
        
        foreach($variations as $variation){
            if($variation['variation_value']==$this->input->post("selectedVariation")){
                $variation_id=$variation['variation_id'];
                $variation_value_id=$variation['variation_value_id'];
                $product_variant=$variation['variation_value'];
                $purchase_items['net_unit_cost']=$variation['rate'];
                $purchase_items['subtotal']=$purchase_items['net_unit_cost']*$purchase_items['quantity'];
         }
        }
        
        $purchase_items['product_variant']=$product_variant;
        $purchase_items['product_variation_value_id']= $variation_value_id;
        $purchase_items['product_variant_id']= $variation_id;
    
        $this->purchase_model->add_purchase_item_db($purchase_items);
        echo json_encode($purchase_items);
    }


    public function update_purchase_item_quantity(){

        $this->purchase_model->update_quantity($this->input->post("id"),$this->input->post("val"));
        echo json_encode("Updated");
    }

    public function delete_purchase_item(){
        $this->purchase_model->delete_item($this->input->post("id"));
        echo json_encode($this->input->post("id"));
    }

    public function make_purchase(){

        $purchase_detail= array(
            'grand_total'  => $this->input->post("grand_total"),
            'created_by' => $this->input->post("created_by"),
            'payment_status'=>$this->input->post("payment_status")
        );

        $date=$this->input->post("date");
        $timestamp = strtotime(str_replace('/', '-', $date));
        $new_date= date("Y-m-d H:i:s", $timestamp);
        $purchase_detail['date']=$new_date;

        $session_id=$this->input->post("session_id");
      $test= $this->purchase_model->make_purchase_to_db($session_id,$purchase_detail);
        echo json_encode($test);
    }

    public function update_purchase(){

        $purchase_detail= array(
            'grand_total'  => $this->input->post("grand_total"),
            'updated_by' => $this->input->post("created_by"),
            'payment_status'=>$this->input->post("payment_status")
        );
        $purchase_id=$this->input->post("id");

        $date=$this->input->post("date");
        $timestamp = strtotime(str_replace('/', '-', $date));
        $new_date= date("Y-m-d H:i:s", $timestamp);
        $purchase_detail['date']=$new_date;

        $session_id=$this->input->post("session_id");

         $this->purchase_model->update_purchase_to_db($session_id,$purchase_detail,$purchase_id);
        echo json_encode($purchase_detail);
    }

    public function delete_purchase(){
        $purchase_id=$this->input->post("id");
        $this->purchase_model->delete_purchase($purchase_id);

    }

    public function view_purchase_detail(){

        $id = $this->input->get('id');
        $data= $this->purchase_model->edit_purchase($id);
        $this->load->view('products/purchase/view-add-purchase',$data);


    }




    public function get_purchase_item(){
        $purchase_items= $this->purchase_model->get_purchase_item_db($this->input->post("sessionId"));
        echo json_encode($purchase_items);
    }

    public function edit_purchase(){

      
        $id = $this->input->get('id');
        $data= $this->purchase_model->edit_purchase($id);
       


        $this->load->view('products/purchase/edit-add-purchase',$data);


    }

}