<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Purchase extends AdminController{
    

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model(['products_model','purchase_model','currencies_model','reports_products_model']);
        
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
        $product['base_currency']      = $this->currencies_model->get_base_currency();
        $this->load->view('products/purchase/add-purchase',$product);
    }

    public function add_purchase_new(){
           
        $purchase_items=$this->input->post('purchase_items');
        $payment_status=$this->input->post('payment_status');
        $payment_date=$this->input->post('payment_date');
        $total=$this->input->post('total');

        $staff_id=get_staff_user_id();

        $master_purchase=array(
            "date"=>$payment_date,
            "grand_total"=>$total,
            "created_by"=>get_staff_full_name($staff_id),
            "payment_status"=>$payment_status
        );
        $purchase_items_post=[];
        foreach($purchase_items as $oi){ 
                    $temp_pro=array(
                        'product_id'  => $oi['product_id'],
                        'product_name' => $oi['product_name'],
                        'net_unit_cost'=>$oi['rate'],
                        'quantity'=>$oi['qty'],
                        'subtotal' =>$oi['subtotal'],
                    );
                
                    array_push($purchase_items_post,$temp_pro);
                }
        $response=$this->purchase_model->add_purchase_to_db($master_purchase,$purchase_items_post,$payment_status);
        $url=admin_url('products/purchase');
        echo json_encode($url);
        return 0;
    

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
    

    public function purchase_report(){
        $this->load->view('products/reports/purchase_report');
    }

    public function generate_purchase_report(){
      $from_date= $this->input->post('from_date');
      $to_date= $this->input->post('to_date');
    
      $response=$this->reports_products_model->purchase_report($from_date,$to_date);


      echo json_encode($response);
    }

    public function update_purchase(){
        $purchase_items=$this->input->post('purchase_items');
        $payment_status=$this->input->post('payment_status');
        $payment_date=$this->input->post('payment_date');
        $total=$this->input->post('total');
        $id=$this->input->post('id');



        $master_update= array(
            "date"=>$payment_date,
            "grand_total"=>$total,
            "payment_status"=>$payment_status
        );

        $purchase_items_post=[];
        foreach($purchase_items as $oi){ 
                    $temp_pro=array(
                        'product_id'  => $oi['product_id'],
                        'product_name' => $oi['product_name'],
                        'net_unit_cost'=>$oi['rate'],
                        'quantity'=>$oi['qty'],
                        'subtotal' =>$oi['subtotal'],
                    );
                    array_push($purchase_items_post,$temp_pro);
                }
$response=$this->purchase_model->update_purchase($master_update,$id,$purchase_items_post,$payment_status);
        
            
        echo json_encode($purchase_items);


        
    }


    public function update_error(){

         $purchases=   $this->db->get(db_prefix().'product_purchases')->result();
            foreach($purchases as $p){
                $this->db->select_sum('subtotal');
                $this->db->where('purchase_id',$p->id);
                $subtotal=$this->db->get(db_prefix().'product_purchase_items')->result()[0]->subtotal;
                $this->db->where('id',$p->id);
                $this->db->update(db_prefix().'product_purchases',['grand_total'=>$subtotal]);

            }

           echo json_encode($subtotal);
   
        }  



    

}