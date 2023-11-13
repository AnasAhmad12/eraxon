<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Eraxon_assets_allocation_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_item($word)
    {

        $this->db->like('item_name', $word);
        $this->db->where('qty >', 0);

        $this->db->join(db_prefix() . 'assets_items_master', db_prefix() . 'assets_items_master.id=' . db_prefix() . 'assets_available_inventory.item_id', 'LEFT');
        $item = $this->db->get(db_prefix() . 'assets_available_inventory');

        if (!$item) {
            return $this->db->error();
        } else {
            $item = $item->result();
            foreach ($item as $i) {
                $this->load->model('Eraxon_assets_category_model');
                $i->category_name = $this->Eraxon_assets_category_model->get($i->assets_category_id)->assets_category_name;
                $i->value = $i->category_name . ' ' . '> ' . $i->item_name . '  --------- ' . intval($i->serial_number);
            }

            return $item;


        }

    }

    public function allocate_item($post_data, $allocation_master)
    {
        $this->db->insert(db_prefix() . 'assets_allocation_master', $allocation_master);
        $insert_id = $this->db->insert_id();
        foreach ($post_data as &$p) {
            $p['allocation_master_id'] = $insert_id;
            $this->db->insert(db_prefix() . 'assets_allocation', $p);
            $this->db->where('item_id', $p['item_id']);
            $this->db->where('serial_number', $p['serial_number']);
            $item = $this->db->get(db_prefix() . 'assets_available_inventory')->result();
            $this->db->where('item_id', $p['item_id']);
            $this->db->where('serial_number', $p['serial_number']);
            $this->db->update(db_prefix() . 'assets_available_inventory', ["qty" => $item[0]->qty - intval($p['qty'])]);
        }

        return $this->db->error();
    }


    public function get_by_id_allocation($id = false)
    {
        $this->db->where('id',$id);
        $allocation['allocation_master']=$this->db->get(db_prefix().'assets_allocation_master')->result();
       
        $this->db->select('tblassets_allocation.*, item_name');
        $this->db->from(db_prefix().'assets_allocation');
        $this->db->where('assets_allocation.allocation_master_id', $id);
        $this->db->join(db_prefix().'assets_items_master', db_prefix().'assets_items_master.id ='.db_prefix().'assets_allocation.item_id', 'left');
        $allocation['allocation_item'] = $this->db->get()->result();
       
       
        return $allocation;
    }

    public function delete_allocation($id){
        $this->db->where('allocation_master_id',$id);
        $allocation=$this->db->get(db_prefix().'assets_allocation')->result();
        foreach($allocation as $ai){
            if($ai->serial_number==0){
                $this->db->where('item_id',$ai->item_id);
                $qty=$this->db->get(db_prefix().'assets_available_inventory')->result()[0]->qty;
                $this->db->where('item_id',$ai->item_id);
                $this->db->update(db_prefix().'assets_available_inventory',["qty"=>$qty+$ai->qty]);
            }
            else{

                $this->db->where('serial_number',$ai->serial_number);
                $qty=$this->db->get(db_prefix().'assets_available_inventory')->result()[0]->qty;
                $this->db->where('serial_number',$ai->serial_number);
                $this->db->update(db_prefix().'assets_available_inventory',["qty"=>$qty+$ai->qty]);

            }
        }
        $this->db->where('id',$id);
        $this->db->delete(db_prefix().'assets_allocation_master');
        $this->db->where('allocation_master_id',$id);
        $this->db->delete(db_prefix().'assets_allocation');

        
    }

    public function get_staff_items($staffid){

        $this->db->where('staff_id',$staffid);
        $allocation_master= $this->db->get('assets_allocation_master')->result();
       if($allocation_master){
        $this->db->select('tblassets_allocation.*, item_name');
        $this->db->from(db_prefix().'assets_allocation');
        $this->db->where('assets_allocation.allocation_master_id', $allocation_master[0]->id);
        $this->db->join(db_prefix().'assets_items_master', db_prefix().'assets_items_master.id ='.db_prefix().'assets_allocation.item_id', 'left');
        $staff_items= $this->db->get()->result();
       }
       else{
        $staff_items=[];
       }

        return $staff_items;



    }










}
