<?php

use app\services\utilities\Arr;

defined('BASEPATH') or exit('No direct script access allowed');

class pos extends AdminController{
    

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model(['products_model','purchase_model','currencies_model','order_model','eraxon_wallet/eraxon_wallet_model']);
        
    }


    
    public function index(){
        $role_id = $this->roles_model->get_roleid_by_name('CSR');
        $data['staff_members'] = $this->staff_model->get('', ['active' => 1,'role' => $role_id]);
        $data['base_currency']  = $this->currencies_model->get_base_currency();
        $this->load->view('products/pos/pos-index',$data);
    }

public function add_order(){
           
            $o=[];
            $order_items=$this->input->post('order_items');
            $subtotal=0;
            foreach($order_items as $oi){ 

                $temp_pro=array(
                   'product_id'=>$oi['product_id'],
                   'product_variation_id'=>$oi['product_variation_id'],
                    'qty'=>$oi['qty'],
                    'rate'=> $oi['rate']
                );
            
                array_push($o,$temp_pro);
                $subtotal += $oi['subtotal'];
            }
          
            if($this->input->post('order_type')=="Wallet"){
                $order_type="POS-Wallet";
        }
        else{
            $order_type="POS-Cash";
        }

            $order_master = array(
                'invoice_id' => 0,
                'clientid'=>strval($this->input->post('staff_id')),
                'datecreated'=>date('Y-m-d H:i:s'),
                'order_date'=>date('Y-m-d'),
                'subtotal' =>$subtotal,
                'total'=>$subtotal,
                'status' =>2,
                'order_type'=>$order_type,
                'trans_id' =>0
            );

          
           

            
           
        
            $order_id=$this->order_model->add_pos_order($order_master,$o);

            if($this->input->post('order_type')=="Wallet"){
                $wallet_id = $this->eraxon_wallet_model->get_walletid_by_staff_id($this->input->post('staff_id'));
                $transaction = array(
                        'wallet_id' => $wallet_id,
                        'amount_type' => 'POS Order-ID-'.$order_id,
                        'amount' => $subtotal,
                        'in_out' => 'out',
                        'created_datetime' => date('Y-m-d H:i:s'),
                        );
                    
                $tid =  $this->eraxon_wallet_model->add_transaction($transaction);
                $this->order_model->update_order_transaction($order_id,$tid);
            }
            echo json_encode($order_id);
        //    redirect(site_url('products/kiosk/staff_invoice/'.$order_id));
         
            

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
                    $name['selected_variation']="ACS";
                    $name['product_detail']=$item;
                   
                    array_push($product_name,$name);
                  
                
                }
               
            }
            else{
                $name['value']=$item->product_name;
                $name['product_detail']=$item;     
                array_push($product_name,$name);
                }

        }



        echo json_encode($product_name);




    }

    public function edit_pos($id){
        $this->load->model('staff_model');
        $order_master = $this->order_model->get_by_id_order($id);
        $data['master_order'] = $this->order_model->get_by_id_order($id);
        $data['invoice'] = $this->order_model->get_order_with_items($id);
        $data['staff'] = $this->staff_model->get($order_master->clientid, ['active' => 1]);
        $data['base_currency']    = $this->currencies_model->get_base_currency();

        $this->load->view('products/pos/pos-edit',$data);

    }

   public function update_order(){
            $item=$this->input->post('order_item');
            $update_item=[];

            foreach($item as $i){
                $temp=array(
                    'product_id'=>$i['product_id'],
                    'qty'=>$i['qty'],
                    'order_id'=>$i['id'],
                    'product_variation_id'=>$i['variation_id']
                );
                
                if($i['variation_id']!=null){
                    $this->db->where('order_id',$i['id']);
                    $this->db->where('product_id',$i['product_id']);
                    $this->db->where('product_variation_id',$i['variation_id']);
                    $current_qty_item= $this->db->get(db_prefix().'order_items')->result()[0]->qty;

                 
                  $this->db->where('id',$i['variation_id']);
                  $current_qty_master= $this->db->get(db_prefix().'product_variations')->result()[0]->quantity_number;

                  $this->db->where('id',$i['variation_id']);
                  $this->db->update(db_prefix().'product_variations',["quantity_number"=>($current_qty_master+$current_qty_item)-$i['qty']]);


                  $this->db->where('id',$i['product_id']);
                  $current_qty_master= $this->db->get(db_prefix().'product_master')->result()[0]->quantity_number;

                  $this->db->where('id',$i['product_id']);

                  $this->db->update(db_prefix().'product_master',["quantity_number"=>($current_qty_master+$current_qty_item)-$i['qty']]);


                }
                else{

                    $this->db->where('order_id',$i['id']);
                    $this->db->where('product_id',$i['product_id']);
                    // $this->db->where('product_variation_id',$i['variation_id']);
                    $current_qty_item= $this->db->get(db_prefix().'order_items')->result()[0]->qty;


                    $this->db->where('id',$i['product_id']);
                    $current_qty_master= $this->db->get(db_prefix().'product_master')->result()[0]->quantity_number;
                    $this->db->where('id',$i['product_id']);
                    $this->db->update(db_prefix().'product_master',["quantity_number"=>($current_qty_master+$current_qty_item)-$i['qty']]);
                }
                
                array_push($update_item,$temp);
            }

            $subtotal=$this->input->post('subtotal');
            
            $trans_id=$item[0]['trans_id'];

            if($trans_id!=0){
                $this->eraxon_wallet_model->delete_transaction($trans_id);
                $wallet_id = $this->eraxon_wallet_model->get_walletid_by_staff_id($item[0]['clientid']);
                $transaction = array(
                        'wallet_id' => $wallet_id,
                        'amount_type' => 'POS Order-ID-'.$item[0]['id'],
                        'amount' => $subtotal,
                        'in_out' => 'out',
                        'created_datetime' => date('Y-m-d H:i:s'),
                        );

                
                    
                $tid =  $this->eraxon_wallet_model->add_transaction($transaction);
                $this->order_model->update_order_transaction($item[0]['id'],$tid);

            }

            $order_master = $this->order_model->update_order($item[0]['id'],$update_item,$subtotal,$trans_id);

            echo json_encode($i);

    }

   
    public function today_order(){
       
        $this->load->view('products/today_order_history');
        
    }

    public function delete_pos($id){
       $this->db->where('id',$id);
       $order_master= $this->db->get(db_prefix().'order_master')->result();
       $this->db->where('order_id',$order_master[0]->id);
       $this->db->delete(db_prefix().'order_items');
       if($order_master[0]->trans_id!=null){
        $this->eraxon_wallet_model->delete_transaction($order_master[0]->trans_id);
       }

       $this->db->where('id',$id);
       $order_master= $this->db->delete(db_prefix().'order_master');
       echo json_encode(["success"=>true]);
    }



}