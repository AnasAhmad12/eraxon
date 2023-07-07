<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * hr payroll model
 */
class Eraxon_myform_model extends App_Model {
	public function __construct() {
		parent::__construct();
	}

	  /**
     * Get All Advance Salary
     */
    public function get_advance_salary($id = false)
    {

    	if(is_staff_member() && !is_admin())
    	{
    		 $this->db->where('id_staff', get_staff_user_id());
    	}

        if (is_numeric($id)) {
            $this->db->where('id', $id);

            return $this->db->get(db_prefix() . 'advance-salary')->row();
        }

        $this->db->select(db_prefix().'advance-salary.*,'.db_prefix().'staff.*');
         $this->db->join(db_prefix() . 'staff', db_prefix() . 'staff.staffid = ' . db_prefix() . 'advance-salary.id_staff', 'left');
    
        $this->db->order_by('id', 'asc');

        return $this->db->get(db_prefix() . 'advance-salary')->result_array();
    }

     /**
     * Get All Other Requests
     */
    public function get_other_requests($id = false)
    {

        if(is_staff_member() && !is_admin())
        {
             $this->db->where('id_staff', get_staff_user_id());
        }

        if (is_numeric($id)) {
            $this->db->where('id', $id);

            return $this->db->get(db_prefix() . 'other-requests')->row();
        }

        $this->db->select(db_prefix().'other-requests.*,'.db_prefix().'staff.*');
         $this->db->join(db_prefix() . 'staff', db_prefix() . 'staff.staffid = ' . db_prefix() . 'other-requests.id_staff', 'left');
    
        $this->db->order_by('id', 'asc');

        return $this->db->get(db_prefix() . 'other-requests')->result_array();
    }

     public function add_other_request($data)
     {
        $this->db->insert(db_prefix() . 'other-requests', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Request Added [ID: ' . $insert_id . ']');
        }

        return $insert_id;
    }
     public function delete_or($id)
    {
        $current = $this->get_other_requests($id);
        // Check if is already using in table
        /*if (is_reference_in_table('type', db_prefix() . 'leads', $id) || is_reference_in_table('lead_type', db_prefix() . 'leads_email_integration', $id)) {
            return [
                'referenced' => true,
            ];
        }*/
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'other-requests');
        if ($this->db->affected_rows() > 0) {
            
            log_activity('Request Deleted [SourceID: ' . $id . ']');

            return true;
        }

        return false;
    }
    public function update_other_reuqest($data, $id)
    {

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'other-requests', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Request Updated [TypeID: ' . $id . ', ID: ' . $data['id'] . ']');

            return true;
        }

        return false;
    
    }

    public function add_advance_salary($data)
     {
        $this->db->insert(db_prefix() . 'advance-salary', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Advance Salary Request Added [TypeID: ' . $insert_id . ', ID: ' . $data['id'] . ']');
        }

        return $insert_id;
    }

    public function update_advance_salary($data, $id)
    {

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'advance-salary', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Advance Salary Request Updated [TypeID: ' . $id . ', Name: ' . $data['id'] . ']');

            return true;
        }

        return false;
    
    }

     public function delete_as($id)
    {
        $current = $this->get_advance_salary($id);
        // Check if is already using in table
        /*if (is_reference_in_table('type', db_prefix() . 'leads', $id) || is_reference_in_table('lead_type', db_prefix() . 'leads_email_integration', $id)) {
            return [
                'referenced' => true,
            ];
        }*/
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'advance-salary');
        if ($this->db->affected_rows() > 0) {
            
            log_activity('Advance Salary Deleted [SourceID: ' . $id . ']');

            return true;
        }

        return false;
    }
}