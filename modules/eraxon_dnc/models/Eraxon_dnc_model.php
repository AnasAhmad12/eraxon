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

}