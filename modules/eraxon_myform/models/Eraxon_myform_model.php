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
     * Get All Docks
     */
    public function get_all_docks()
    {
        $this->db->order_by('id', 'asc');

        return $this->db->get(db_prefix() . 'docks')->result_array();
    }

	  /**
     * Get All Advance Salary
     */
    public function get_advance_salary($id = false)
    {

    	if(has_permission('advance_salary','','view_own') && !is_admin())
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

        if(has_permission('other_form','','view_own') && !is_admin())
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

    public function report_by_leads_staffs($id)
    {
        
        $custom_date_select = '';

        $current_year = date('Y');
        for($_month = 1 ; $_month <= 12; $_month++){
            $month_t = date('m',mktime(0, 0, 0, $_month, 04, 2016));

            if($_month == 5){
                $chart['categories'][] = _l('month_05');
            }else{
                $chart['categories'][] = _l('month_'.$_month);
            }

            $month = $current_year.'-'.$month_t;

            $chart['pending_leads'][] = $this->leads_by_month($month, 'Pending',$id);
            $chart['approved_leads'][] = $this->leads_by_month($month, 'Approved',$id);
            $chart['rejected_leads'][] = $this->leads_by_month($month, 'Rejected',$id);
        }

        return $chart;
    }

    public function leads_by_month($month,$status,$id)
    {
        $this->db->select('count(id) as total_leads, status');

        if($status == 'Pending')
        { 
            $this->db->where('status',2); 

        }elseif($status == 'Approved')
        {
            $this->db->where('status',3); 

        }elseif($status == 'Rejected'){

            $this->db->where('status',4); 
        }
        
            
        $this->db->where('assigned',$id);
     
        //$sql_where = "date_format(".db_prefix()."leads.dateadded, '%Y-%m') = '".$month."'";
        $sql_where = "date_format(dateadded, '%Y-%m') = '".$month."'";
        $this->db->where($sql_where);
        //$result = $this->db->get(db_prefix().'leads')->row();
        $result = $this->db->get(db_prefix().'leads')->row();

        if($result){
            return (int)$result->total_leads;
        }
        return 0;
    }

    public function report_by_day_leads_staffs($id)
    {
        //$months_report = $this->input->post('months_report');
        $custom_date_select = '';

        $maxDays=date('t');
        $current_year = date('Y');
        $current_month = date('m');
        $current_month_short = date('M');
        for($_day = 1 ; $_day <= $maxDays; $_day++)
        {

            $chart['categories'][] = $_day.' / '.$current_month_short;

            $dd = $current_year.'-'.$current_month.'-'.str_pad($_day, 2, '0', STR_PAD_LEFT);

            $chart['daily_leads'][] = $this->leads_by_day($dd,$id);
            //$chart['approved_leads'][] = $this->leads_by_day($dd, 'Approved');
            //$chart['rejected_leads'][] = $this->leads_by_day($dd, 'Rejected');

        }

        return $chart;
    }

    public function leads_by_day($day,$id)
    {
        $this->db->select('count(id) as total_leads');

        $this->db->where('assigned',$id);
        
        $sql_where = "date_format(dateadded, '%Y-%m-%d') = '".$day."'";
        $this->db->where($sql_where);
        //$result = $this->db->get(db_prefix().'leads')->row();
        $result = $this->db->get(db_prefix().'leads')->row();

        if($result){
            return (int)$result->total_leads;
        }
        return 0;
    }

    public function add_dock($data)
    {
        $this->db->insert(db_prefix() . 'docks', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Dock Added [TypeID: ' . $insert_id . ', ID: ' . $data['dock_name'] . ']');
        }

        return $insert_id;
    }

    public function update_dock($data, $id)
    {

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'docks', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Dock Data Updated [TypeID: ' . $id . ', Name: ' . $data['dock_name'] . ']');

            return true;
        }

        return false;
    
    }

    public function delete_dock($id)
    {        
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'docks');
        if ($this->db->affected_rows() > 0) {
            
            log_activity('Dock Data Deleted [SourceID: ' . $id . ']');

            return true;
        }

        return false;
    }


    public function teamlead_add_dock($data)
    {
        $this->db->insert(db_prefix() . 'teamlead_docks', $data);

        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('Dock added to staff [TypeID: ' . $insert_id . ', Staff: ' . $data['staff_id'] . ', Dock Name: ' . $data['dock_name'] . ', Dock Amount: ' . $data['dock_amount'] . ']');
        }

        return $insert_id;
    }


   
}