<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Eraxon_assets_request_inventory_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }


    public function request_inventory($post_data){ 
        
        $this->db->insert(db_prefix().'assets_request_inventory',$post_data);

        return $this->db->error();
    }

    public function get_request_inventory(){ 

        $this->db->select('assets_request_inventory.*, item_name');
        $this->db->from(db_prefix() . 'assets_request_inventory');
        if(has_permission('asset-request',"","view_own")  && !is_admin() ){
            $this->db->where( 'staff_id = '.get_staff_user_id());
        }else{
            
        }
        $this->db->join(db_prefix() . 'assets_items_master', db_prefix() . 'assets_items_master.id =' . db_prefix() . 'assets_request_inventory.item_id', 'left');
        $data = $this->db->get()->result();

        return $data;
    }



    public function edit_status($id,$status){
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'assets_request_inventory', ["status" => $status]);
        $this->db->where('id', $id);
        $data = $this->db->get(db_prefix() . 'assets_request_inventory')->result();

        if ($status == 1) {
            $master=array(
                "allocation_date"=>date('Y-m-d'),
                "staff_id" => $data[0]->staff_id,
            );

            $this->db->insert(db_prefix().'assets_allocation_master',$master);
            $insert_id=$this->db->insert_id();
            $allocation_item=array(
                "allocation_master_id"=>$insert_id,
                "item_id"=>$data[0]->item_id,
                "serial_number"=>$data[0]->serial_number,
                "qty"=>1
            );
            $this->db->insert(db_prefix().'assets_allocation',$allocation_item);
      
            if ($data[0]->serial_number == "0") {
                $this->db->where('item_id', $data[0]->item_id);
                $inventory = $this->db->get(db_prefix() . 'assets_available_inventory')->result();
                $this->db->where('item_id', $data[0]->item_id);
                $this->db->update(db_prefix() . 'assets_available_inventory', ["qty" => $inventory[0]->qty-1]);
            } else {

                $this->db->where('serial_number', $data[0]->serial_number);
                $inventory = $this->db->get(db_prefix() . 'assets_available_inventory')->result();
                $this->db->where('item_id', $data[0]->item_id);
                $this->db->update(db_prefix() . 'assets_available_inventory', ["qty" => $inventory[0]->qty - 1]);

            }
      
      
        }

    }
    

    
    

    
    
}
