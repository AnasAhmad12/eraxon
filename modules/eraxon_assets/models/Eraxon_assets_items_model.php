<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Eraxon_assets_items_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function add_item($data)
    {
        
        $this->db->insert(db_prefix() . 'assets_items_master', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Assets Added [ ID:' . $insert_id . ', '. $data['item_name'].', Staff id ' . get_staff_user_id() . ' ]');
            return $insert_id;
        }

        return false;
    }

    public function add_item_custom_fields($data){
        $this->db->insert_batch(db_prefix() . 'assets_items_custom_fields', $data);
    }

    public function edit_item_custom_fields($data,$id,$cfid){
        $this->db->where('item_id',$id);
        $this->db->where('custom_field_value_id',$cfid);
        unset($data['custom_field_value_id']);
        $this->db->update(db_prefix() . 'assets_items_custom_fields', $data);
    }

    




    public function get_by_id_product($id = false)
    {   
        $this->db->where('id',$id);
        $this->db->from(db_prefix().'assets_items_master');
        $query = $this->db->get(); 

        $this->db->where('item_id',$id);
        $this->db->from(db_prefix().'assets_items_custom_fields');
        $custom_fields = $this->db->get()->result(); 
        
        $db_error = $this->db->error();
        if ($db_error['code'] !== 0) {
             return $db_error['message'];
        } else {
            $products=$query->result();
            $custom_field=[];
            foreach($custom_fields as $p ){ 
                $temp=array("custom_field_name"=>$p->custom_field_name,"value"=>$p->value);
                array_push($custom_field,$temp);
            }
            $products[0]->custom_fields=$custom_field;
            return $products; 
        }
       
    }

    

    

    

    

   

    public function edit_item($data, $id)
    {
       
        // $product = $this->get_by_id_product($id);
       
        $this->db->where('id', $id);
        $res = $this->db->update(db_prefix() . 'assets_items_master', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Item Details updated[ ID: '.$id.', '.$product->product_name.', Staff id '.get_staff_user_id().' ]');
        }

        if ($res) {
            return true;
        }

        return false;
    }

    public function delete_by_id_item($id)
    {
        // $product  = $this->get_by_id_product($id);
        $this->db->where('id',$id);
        $items= $this->db->get(db_prefix().'assets_items_master')->result();
        $relPath  = get_upload_path_by_type('items').'/';
        $fullPath = $relPath.$items[0]->item_image;
        unlink($fullPath);
        if (!empty($id)) {
            $this->db->where('id', $id);
        }
        $result = $this->db->delete(db_prefix() . 'assets_items_master');
        log_activity('Item Deleted[ ID: '.$id.', '.$items[0]->item_name.', Staff id '.get_staff_user_id().' ]');

        // $this->db->where('product_id', $id);
        // $product_variations = $this->db->get(db_prefix() . 'product_variations')->result_array();
        // foreach ($product_variations as $product_variation) {
        //     $this->db->where('id', $product_variation['id']);
        //     $this->db->delete(db_prefix() . 'product_variations');
        //     log_activity('Product Variation Details Deleted [ ID: ' . $product_variation['id'] . ' ]');
        // }

        return $result;
    }

   
}
