<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Purchase_model extends App_Model{


    public function add_purchase_item_db($purchase_items){
        $table_name =db_prefix() . 'product_purchase_items' ; 
        $products=[];
        $this->db->select('*');
        $this->db->where('session_id',$purchase_items['session_id']);
        $this->db->where('product_id',$purchase_items['product_id']);
        $this->db->where('product_variant_id',$purchase_items['product_variant_id']);
        $this->db->where('product_variation_value_id',$purchase_items['product_variation_value_id']);
        
        $products=$this->db->get($table_name)->result();

        if(empty($products)){
            $this->db->insert(db_prefix() . 'product_purchase_items', $purchase_items);
        }
        else{
            
            $this->db->where('product_id',$purchase_items['product_id']);
            $this->db->update(db_prefix() . 'product_purchase_items', array('quantity' => $products[0]->quantity+1,'subtotal'=> ($products[0]->quantity+1)*$products[0]->net_unit_cost));
        }


      
    }

    public function get_purchase_item_db($session_id){
        
        $table_name =db_prefix() . 'product_purchase_items' ; 
        $this->db->select('*');
        $this->db->where('session_id',$session_id);
      return  $this->db->get($table_name)->result();
    }

    public function update_quantity($id,$quantity){
        $table_name =db_prefix() . 'product_purchase_items' ; 
        
        $this->db->select('*');
        $this->db->where('id',$id);
       $products= $this->db->get($table_name)->result();

       $this->db->select('*');
       $this->db->where('id',$id);
        $this->db->update($table_name,['quantity'=>$quantity,'subtotal'=> $quantity*$products[0]->net_unit_cost]);
    }
    public function delete_item($id){
        $table_name =db_prefix() . 'product_purchase_items' ; 
        $this->db->select('*');
        $this->db->where('id',$id);
        $this->db->delete($table_name);
    }

    public function make_purchase_to_db($id,$purchase_detail){

        $table_name_item =db_prefix() . 'product_purchase_items' ; 
        $table_name =db_prefix() . 'product_purchases' ; 

        $this->db->insert(db_prefix() . 'product_purchases', $purchase_detail);
        $st= $this->db->insert_id();

        $this->db->where('session_id',$id);
        $this->db->update($table_name_item,['purchase_id'=>$st]);

        log_activity('Purchase Created [  Purchase ID : '.$st.' Staff id '.get_staff_user_id().']');


        if($purchase_detail['payment_status']=="Approved"){
           
            $this->db->where('session_id',$id);
            // $pr=  $this->db->get($table_name_item)->result();
            $products=$this->db->get($table_name_item)->result();
            $quantity_count=0;
            foreach($products as $pr){
               
                 if($pr->product_variant!=null){
                    
                    $this->db->where('product_id',$pr->product_id);
                    $this->db->where('variation_value_id',$pr->product_variation_value_id);
                    $current_quantity=$this->db->get(db_prefix() . 'product_variations')->result()[0]->quantity_number; //10
                    $quantity_count=$quantity_count+$current_quantity+$pr->quantity; //12
                    
                    $this->db->where('product_id',$pr->product_id);
                    $this->db->where('variation_value_id',$pr->product_variation_value_id);
                    $this->db->update(db_prefix() . 'product_variations',['quantity_number'=>$current_quantity+$pr->quantity]);

                    $this->db->where('id',$pr->product_id);
                    $current_quantity_master=$this->db->get(db_prefix() . 'product_master')->result()[0]->quantity_number;
                   
                   
                    $this->db->where('id',$pr->product_id);
                    $this->db->update(db_prefix() . 'product_master',['quantity_number'=>$current_quantity_master+$pr->quantity]);
                
                }
                else if($pr->product_variant==null){

                    $this->db->where('id',$pr->product_id);
                    $current_quantity=$this->db->get(db_prefix() . 'product_master')->result()[0]->quantity_number;
                    
                    $this->db->where('id',$pr->product_id);
                    $this->db->update(db_prefix() . 'product_master',['quantity_number'=>$current_quantity+$pr->quantity]);



                }


            }

        }
        else if($purchase_detail['payment_status']=="Initial"){
            $this->db->where('session_id',$id);
            
            $products=$this->db->get($table_name_item)->result();
            
            foreach($products as $pr){
               
                 if($pr->product_variant!=null){
                    
                    $this->db->where('product_id',$pr->product_id);
                    $this->db->where('variation_value_id',$pr->product_variation_value_id);
                    $this->db->where('product_id',$pr->product_id);
                    $this->db->where('variation_value_id',$pr->product_variation_value_id);
                    $this->db->update(db_prefix() . 'product_variations',['quantity_number'=>$pr->quantity]);
                    $this->db->where('id',$pr->product_id);
                    $this->db->update(db_prefix() . 'product_master',['quantity_number'=>$pr->quantity]);
                
                }
                else if($pr->product_variant==null){

                    $this->db->where('id',$pr->product_id);
                    $this->db->update(db_prefix() . 'product_master',['quantity_number'=>$pr->quantity]);

                }


            }

            $this->db->where('id',$st);
            $this->db->update($table_name,['payment_status'=>"Approved"]);
        }

        return  $quantity_count;

    }

    public function update_purchase_to_db($id,$purchase_detail,$p_id){

        $table_name_item =db_prefix() . 'product_purchase_items' ; 
        $table_name =db_prefix() . 'product_purchases' ; 
        
        $this->db->where('id',$p_id);
        $this->db->update(db_prefix() . 'product_purchases', $purchase_detail);
        $st= $p_id;


        $this->db->where('session_id',$id);
        $this->db->update($table_name_item,['purchase_id'=>$st]);


        if($purchase_detail['payment_status']=="Approved"){
           
            $this->db->where('session_id',$id);
            // $pr=  $this->db->get($table_name_item)->result();
            $products=$this->db->get($table_name_item)->result();
            $quantity_count=0;
            foreach($products as $pr){
               
                 if($pr->product_variant!=null){
                    
                    $this->db->where('product_id',$pr->product_id);
                    $this->db->where('variation_value_id',$pr->product_variation_value_id);
                    $current_quantity=$this->db->get(db_prefix() . 'product_variations')->result()[0]->quantity_number; //10
                    $quantity_count=$quantity_count+$current_quantity+$pr->quantity; //12
                    
                    $this->db->where('product_id',$pr->product_id);
                    $this->db->where('variation_value_id',$pr->product_variation_value_id);
                    $this->db->update(db_prefix() . 'product_variations',['quantity_number'=>$current_quantity+$pr->quantity]);

                    $this->db->where('id',$pr->product_id);
                    $current_quantity_master=$this->db->get(db_prefix() . 'product_master')->result()[0]->quantity_number;
                   
                   
                    $this->db->where('id',$pr->product_id);
                    $this->db->update(db_prefix() . 'product_master',['quantity_number'=>$current_quantity_master+$pr->quantity]);
                
                }
                else if($pr->product_variant==null){

                    $this->db->where('id',$pr->product_id);
                    $current_quantity=$this->db->get(db_prefix() . 'product_master')->result()[0]->quantity_number;
                    
                    $this->db->where('id',$pr->product_id);
                    $this->db->update(db_prefix() . 'product_master',['quantity_number'=>$current_quantity+$pr->quantity]);



                }


            }

        }


    }


    public function delete_purchase($id){
        $this->db->where('id',$id);
        $this->db->delete(db_prefix() . 'product_purchases');
    }




    public function get_purchase_list(){
        $this->db->order_by('id', 'desc');
        $data =$this->db->get(db_prefix() . 'product_purchases')->result();
        
        return $data;
    }

    public function edit_purchase($id){ 
        $this->db->select('*');
        $this->db->where('id',$id);

        $data['purchase_detail']= $this->db->get(db_prefix() . 'product_purchases')->result();
        

        $this->db->select('*');
        $this->db->where('purchase_id',$data['purchase_detail'][0]->id);
        $data['session_id']= $this->db->get(db_prefix() . 'product_purchase_items')->result()[0]->session_id;
       return $data;

    }
}