<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Eraxon DNC Model
 */
class Eraxon_dnc_model extends App_Model 
{

    public function __construct() 
    {
        parent::__construct();   
    }

    public function add_dnc_request($data)
    {
        $this->db->insert(db_prefix() . 'dnc_request', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New DNC Request Added [ID: ' . $insert_id . ', BY: '.$data['id_staff'].']');
        }

        return $insert_id;
    }

    public function get_dnc_request()
    {
        $this->db->order_by('id', 'asc');
       if(has_permission('dnc_check','','view_own'))
       {
           $this->db->where('id_staff', get_staff_user_id());
       }
        return $this->db->get(db_prefix() . 'dnc_request')->result_array();
    }

    public function verifyfromdb($phone)
    {
        $this->db->where('phonenumber',$phone);
        $this->db->get(db_prefix().'dnc_request');

        if($this->db->affected_rows() > 0)
        {
            return 1;
        }
        return 0;
    }

    public function get_data_by_number($phone)
    {
        $this->db->where('phonenumber',$phone);
        $this->db->limit(1);
        $query = $this->db->get(db_prefix().'dnc_request')->row();
        return $query;
    }

    public function update_dnc_request($id, $data)
    {

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'dnc_request', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('New DNC Request Updated [ID: ' . $id . ', BY: '.$data['id_staff'].']');

            return true;
        }

        return false;
    
    }

    public function update_status($id,$data)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'dnc_request', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('New DNC Result Updated [ID: ' . $id .']');

            return true;
        }

        return false;
    }

}