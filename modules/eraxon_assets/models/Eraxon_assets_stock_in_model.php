<?php

defined('BASEPATH') or exit('No direct script access allowed');


class Eraxon_assets_stock_in_model extends App_Model
{

    public function get_item_with_category($word)
    {

        $this->db->like('item_name', $word);
        // $this->db->join('assets_items_master  AS ac', 'ac.assets_category_id=' . db_prefix() . 'assets_categories.assets_category_id', 'LEFT');   
        $item = $this->db->get(db_prefix() . 'assets_items_master');

        if (!$item) {
            return $this->db->error();
        } else {
            $item = $item->result();
            foreach ($item as $i) {
                $this->load->model('Eraxon_assets_category_model');
                $i->category_name = $this->Eraxon_assets_category_model->get($i->assets_category_id)->assets_category_name;
                $i->value = $i->category_name . ' ' . '> ' . $i->item_name;
            }
            return $item;


        }

    }

    public function add_stock($stock_master, $stock_inventory)
    {

        $this->db->insert(db_prefix() . 'assets_stock_table_master', $stock_master);
        $insert_id = $this->db->insert_id();
        foreach ($stock_inventory as &$s) {
            $s['stock_in_master_id'] = $insert_id;
        }
        $this->db->insert_batch(db_prefix() . 'assets_stock_table', $stock_inventory);
        return $stock_inventory;
        // $this->db->insert_batch(db_prefix().'assets_stock_table',$data);
    }

    public function edit_stock($stock_master, $stock_inventory,$id)
    {
        $this->db->where('id',$id);
        $this->db->update(db_prefix() . 'assets_stock_table_master', $stock_master);
        $this->db->insert_batch(db_prefix() . 'assets_stock_table', $stock_inventory);
        return $stock_inventory;
    }

    public function is_serial_number_unique($serial_number) {
      return false;
    }

    public function delete_by_id($id,$flag=false)
    {
        if($flag!=true){
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'assets_stock_table_master');
        }
        $this->db->where('stock_in_master_id', $id);
        $result = $this->db->delete(db_prefix() . 'assets_stock_table');
        return $result;

    }

    public function get_by_id_stock_purchase($id)
    {
        $this->db->where('id', $id);
        $stock_purchase_master = $this->db->get(db_prefix() . 'assets_stock_table_master')->result();
        $this->db->select('assets_stock_table.*,assets_stock_table.item_id as id,assets_stock_table.serial_number as serial_no,assets_stock_table.quantity_added as quantity,assets_stock_table.purchase_price as rate, assets_items_master.item_name as item_name, assets_items_master.item_sr_no');
        $this->db->from(db_prefix() . 'assets_stock_table');
        $this->db->where('stock_in_master_id', $id);
        $this->db->join('assets_items_master', 'assets_stock_table.item_id = assets_items_master.id', 'left');
        $stock_purchase_items = $this->db->get()->result();
        $data['item'] = $stock_purchase_items;
        $data['purchase_date'] = $stock_purchase_master[0]->purchase_date;
        $data['invoice_number'] = $stock_purchase_master[0]->invoice_number;
        $data['invoice_image'] = $stock_purchase_master[0]->invoice_image;
        return $data;
    }

    public function get_master_id_item($id="",$serial_number="")
    {
        $stock_in_id=[];
        $this->db->where('serial_number', $serial_number);
        $stock_in_id = $this->db->get(db_prefix() . 'assets_stock_table')->result();
        if (!$stock_in_id) {
            $this->db->where('item_id', $id);
            $stock_in_id = $this->db->get(db_prefix() . 'assets_stock_table')->result();
        }

        return $stock_in_id[0]->stock_in_master_id;


    }

    public function add_stock_to_inventory($stock_inventory){
            foreach($stock_inventory as $s ){

                $this->db->where('item_id',$s['item_id']);
                $inventory=$this->db->get(db_prefix().'assets_available_inventory')->result();
                if($inventory){
                    if($s['serial_number']==""){
                        $this->db->where('item_id',$s['item_id']);
                        $this->db->update(db_prefix().'assets_available_inventory',["qty"=>$inventory[0]->qty+$s['quantity_added']]);
                    }
                    else{
                        $insert_data=array(
                            "item_id"=> $s['item_id'],
                            "serial_number"=>$s['serial_number'],
                            "qty" =>$s['quantity_added'],
                            "price"=> $s['purchase_price'],
                            "last_updated"=>  date("Y-m-d H:i:s")
                        );
                        $this->db->insert(db_prefix().'assets_available_inventory',$insert_data);
                    }
                   
                   }  
                 else{
                    $insert_data=array(
                        "item_id"=> $s['item_id'],
                        "serial_number"=>$s['serial_number'],
                        "qty" =>$s['quantity_added'],
                        "price"=> $s['purchase_price'],
                        "last_updated"=>  date("Y-m-d H:i:s")
                    );
                    $this->db->insert(db_prefix().'assets_available_inventory',$insert_data);
                 }
            }
            return $this->db->error();
    }
}

