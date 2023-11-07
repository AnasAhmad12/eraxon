<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Eraxon_assets_loss_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }



    public function post_loss_db($data)
    {
        $this->db->insert(db_prefix() . 'assets_lost_inventory', $data);
    }

    public function get_loss()
    {
        $data = $this->db->get(db_prefix() . 'assets_lost_inventory')->result();
    if(has_permission('asset-loss','','view_own') && !is_admin()){
        $this->db->where('staff_id',get_staff_user_id());
    }
        $this->db->select('tblassets_lost_inventory.*, item_name');
        $this->db->from(db_prefix() . 'assets_lost_inventory');
        $this->db->join(db_prefix() . 'assets_items_master', db_prefix() . 'assets_items_master.id =' . db_prefix() . 'assets_lost_inventory.item_id', 'left');
        $data = $this->db->get()->result();

        return $data;
    }

    public function edit_loss($id, $status,$item_status)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'assets_lost_inventory', ["request_status" => $status,"item_status"=>$item_status]);
        $this->db->where('id', $id);
        $data = $this->db->get(db_prefix() . 'assets_lost_inventory')->result();

        if ($status == 1) {
            if ($data[0]->serial_number == "0") {

                $this->db->where('item_id', $data[0]->item_id);
                $inventory = $this->db->get(db_prefix() . 'assets_allocation')->result();
                $this->db->where('item_id', $data[0]->item_id);
                $this->db->update(db_prefix() . 'assets_allocation', ["qty" => $inventory[0]->qty - 1]);
            } else {

                $this->db->where('serial_number', $data[0]->serial_number);
                $inventory = $this->db->get(db_prefix() . 'assets_allocation')->result();
                $this->db->where('item_id', $data[0]->item_id);
                $this->db->update(db_prefix() . 'assets_allocation', ["qty" => $inventory[0]->qty - 1]);

            }
        }

        if ($item_status == 1) {
            if ($data[0]->serial_number == "0") {
                $this->db->where('item_id', $data[0]->item_id);
                $inventory = $this->db->get(db_prefix() . 'assets_available_inventory')->result();
                $this->db->where('item_id', $data[0]->item_id);
                $this->db->update(db_prefix() . 'assets_available_inventory', ["qty" => $inventory[0]->qty+1]);
            } else {

                $this->db->where('serial_number', $data[0]->serial_number);
                $inventory = $this->db->get(db_prefix() . 'assets_available_inventory')->result();
                $this->db->where('item_id', $data[0]->item_id);
                $this->db->update(db_prefix() . 'assets_available_inventory', ["qty" => $inventory[0]->qty + 1]);

            }
        }

    }









}
