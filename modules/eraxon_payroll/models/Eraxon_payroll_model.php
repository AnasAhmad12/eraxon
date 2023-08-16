<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * payroll model
 */
class Eraxon_payroll_model extends App_Model 
{

	public function __construct() 
    {
		parent::__construct();
        $this->load->model('departments_model');
	}

	public function insert_allownce($allowance_name, $type, $amount) {
        $data = array(
            'name' => $allowance_name,
            'type' => $type,
            'amount' => $amount
        );

        return $this->db->insert(db_prefix() .'allowance', $data);
    }

	public function insert_deduction($deduction_name, $type, $amount) {
        $data = array(
            'name' => $deduction_name,
            'type' => $type,
            'amount' => $amount
        );

        return $this->db->insert(db_prefix() .'deductions', $data);
    }

	public function get_allownces() 
	{
        $query = $this->db->get(db_prefix() .'allowance');
        return $query->result();
    }

	public function get_allownce_by_id($id) {
        $query = $this->db->get_where(db_prefix() .'allowance', array('id' => $id));
        return $query->row();
    }

	public function update_allownce($id, $allowance_name, $type, $amount) {
        $data = array(
            'name' => $allowance_name,
            'type' => $type,
            'amount' => $amount
        );

        $this->db->where('id', $id);
        return $this->db->update(db_prefix() .'allowance', $data);
    }

	public function get_deductions() 
	{
        $query = $this->db->get(db_prefix() .'deductions');
        return $query->result();
    }

	public function get_deduction_by_id($id) {
        $query = $this->db->get_where(db_prefix() .'deductions', array('id' => $id));
        return $query->row();
    }

	public function update_deduction($id, $deduction_name, $type, $amount) {
        $data = array(
            'name' => $deduction_name,
            'type' => $type,
            'amount' => $amount
        );

        $this->db->where('id', $id);
        return $this->db->update(db_prefix() .'deductions', $data);
    }
	
	public function delete_allownce($id) {
        $this->db->where('id', $id);
        return $this->db->delete(db_prefix() .'allowance');
    }

	public function delete_deduction($id) {
        $this->db->where('id', $id);
        return $this->db->delete(db_prefix() .'deductions');
    }

    public function delete_deductions_allowances($job_id)
	{
		$this->db->where('jobid', $job_id);
        return $this->db->delete(db_prefix() .'job_allowance_deduction');
	}

    public function get_targets() 
	{
        $query = $this->db->get(db_prefix() .'targets');
        return $query->result();
    }

    public function insert_target($target_name, $bonus, $target, $accumulative_bonus, $status)
    {
        $data = array(
            'name' => $target_name,
            'bonus' => $bonus,
            'target' => $target,
            'accumulative_bonus' => $accumulative_bonus,
            'status' => $status,
        );

        return $this->db->insert(db_prefix() .'targets', $data);
    }

    public function update_target($id,$target_name, $bonus, $target, $accumulative_bonus, $status) {
        $data = array(
            'name' => $target_name,
            'bonus' => $bonus,
            'target' => $target,
            'accumulative_bonus' => $accumulative_bonus,
            'status' => $status,
        );

        $this->db->where('id', $id);
        return $this->db->update(db_prefix() .'targets', $data);
    }

    public function get_target_by_id($id) 
    {
        $query = $this->db->get_where(db_prefix() .'targets', array('id' => $id));
        return $query->row();
    }

    public function delete_target($id)
	{
		$this->db->where('id', $id);
        return $this->db->delete(db_prefix() .'targets');
	}
    public function get_bonuses() 
    {
        $query = $this->db->get(db_prefix() .'bonuses');
        return $query->result();
    }

    public function insert_bonus($bonus_name, $amount)
    {
        $data = array(
            'name' => $bonus_name,
            'amount' => $amount,
        );

        return $this->db->insert(db_prefix() .'bonuses', $data);
    }

    public function update_bonus($id, $bonus_name, $amount) {
        $data = array(
            'name' => $bonus_name,
            'amount' => $amount,
        );

        $this->db->where('id', $id);
        return $this->db->update(db_prefix() .'bonuses', $data);
    }

    public function get_bonus_by_id($id) 
    {
        $query = $this->db->get_where(db_prefix() .'bonuses', array('id' => $id));
        return $query->row();
    }

    public function delete_bonus($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete(db_prefix() .'bonuses');
    }

    Public function get_staff($role_id)
    {
        $this->db->select(db_prefix().'staff.*, jp.position_name, jp.position_id, jp.basic_salary, jp.basic_target');
        $this->db->from(db_prefix().'staff');
        $this->db->join(db_prefix().'hr_job_position as jp', db_prefix().'staff.job_position = jp.position_id', 'left');
        $this->db->where(db_prefix().'staff.active', 1);
        $this->db->where(db_prefix().'staff.admin', 0);
        $this->db->where(db_prefix().'staff.role', $role_id);
        $query = $this->db->get();

        return $query->result();
    }
    public function get_allowances_details($job_position_id)
    {
        $this->db->select("jad.*,a.id,a.name as allowance_name,a.amount as allowance_amount,a.type as allowance_type");
        $this->db->from(db_prefix() .'job_allowance_deduction as jad');
        $this->db->join(db_prefix() .'allowance as a', 'a.id = jad.allowance_id', 'left');
        // $this->db->join(db_prefix() .'hr_job_position as p', 'p.position_id = jad.jobid', 'left');
        $this->db->where('jad.jobid', $job_position_id);
        $this->db->where('jad.allowance_id !=', 0);
        $query = $this->db->get();

        return $query->result();
    }

    public function get_deductions_details($job_position_id)
    {
        $this->db->select("jad.*,d.name as deduction_name,d.amount as deduction_amount,d.type as deduction_type, p.position_name, p.basic_salary");
        $this->db->from(db_prefix() .'job_allowance_deduction as jad');
        $this->db->join(db_prefix() .'deductions as d', 'd.id = jad.deduction_id', 'left');
        $this->db->join(db_prefix() .'hr_job_position as p', 'p.position_id = jad.jobid', 'left');
        $this->db->where('jad.jobid', $job_position_id);
        $this->db->where('jad.deduction_id !=', 0);
        $query = $this->db->get();
        return $query->result();
    }

    public function add_salary_detail($data)
    {
        $this->db->insert(db_prefix() .'salary_details', $data);
        return $this->db->insert_id();
    }
    
    public function add_salary_details_to_allowances($data)
    {
        return $this->db->insert(db_prefix() .'salary_details_to_allowances', $data);
    }

    public function add_salary_details_to_deductions($data)
    {
        return $this->db->insert(db_prefix() .'salary_details_to_deductions', $data);
    }

    public function update_salary_details($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update(db_prefix() .'salary_details', $data);
    }

    public function get_timesheet_by_employee_id($employee_id,$startDate,$endDate)
    {
        $this->db->select('*');
        $this->db->from(db_prefix() .'timesheets_timesheet');
        $this->db->where('date_work >=', $startDate);
        $this->db->where('date_work <=', $endDate);
        $this->db->where('staff_id', $employee_id);
        $query = $this->db->get();

        return $query->result();
    }

    public function add_salary_details_to_attendance($data)
    {
        return $this->db->insert(db_prefix() .'salary_details_to_attendance', $data);
    }

    public function get_salary_details($month_year = '')
    {
        if($month_year != '')
        {
           $this->db->where('YEAR(date)', date('Y', strtotime($month_year)));
           $this->db->where('MONTH(date)', date('m', strtotime($month_year))); 
        }
        if(has_permission('my_salary_slip','','view_own') && !is_admin())
        {
            $this->db->where("employee_id", get_staff_user_id());
        }
        $this->db->select('sd.*, CONCAT(s.firstname, " ", s.lastname) as name,s.*,r.name as rolename');
        $this->db->from(db_prefix() . 'salary_details as sd');
        $this->db->join(db_prefix() . 'staff as s', 's.staffid = sd.employee_id', 'left');
        $this->db->join(db_prefix() . 'roles as r', 'r.roleid = s.role', 'left');
        $this->db->order_by('sd.id', 'DESC');
        $query = $this->db->get();

        return $query->result();
    }

    
    public function salary_slip_details($id)
    {
        $this->db->select('sd.*, CONCAT(s.firstname, " ", s.lastname) as name,s.*, r.name as rolename, at.*');
        $this->db->from(db_prefix() . 'salary_details as sd');
        $this->db->join(db_prefix() . 'staff as s', 's.staffid = sd.employee_id', 'left');
        $this->db->join(db_prefix() . 'roles as r', 'r.roleid = s.role', 'left');
        $this->db->join(db_prefix() . 'salary_details_to_attendance as at', 'at.salary_details_id = sd.id', 'left');
        $this->db->where('sd.id', $id);
        $this->db->order_by('sd.id', 'DESC');
        $query = $this->db->get();

        return $query->result();
    }

    public function salary_details_to_allowances($id)
    {
        $this->db->select('*');
        $this->db->from(db_prefix() . 'salary_details_to_allowances as sa');
        $this->db->join(db_prefix() . 'allowance as a', 'a.id = sa.allowance_id', 'left');
        $this->db->where('sa.salary_details_id', $id);
        $query = $this->db->get();

        return $query->result();
    }

    public function salary_details_to_deductions($id)
    {
        $this->db->select('*');
        $this->db->from(db_prefix() . 'salary_details_to_deductions as sd');
        $this->db->join(db_prefix() . 'deductions as a', 'a.id = sd.deduction_id', 'left');
        $this->db->where('sd.salary_details_id', $id);
        $query = $this->db->get();

        return $query->result();
    }

    public function delete_salary_details($employee_id, $date)
    {
        // check if record exists
        $this->db->where('employee_id', $employee_id);
        $this->db->where('date', $date);
        $query = $this->db->get(db_prefix() . 'salary_details');

        if ($query->num_rows() > 0) {
            // record exists, get the salary_details_id
            $salary_details_id = $query->row()->id;

            // delete from salary_details
            $this->db->where('id', $salary_details_id);
            $this->db->delete(db_prefix() . 'salary_details');

            // delete related records from salary_details_to_allowances
            $this->db->where('salary_details_id', $salary_details_id);
            $this->db->delete(db_prefix() . 'salary_details_to_allowances');

            // delete related records from salary_details_to_deductions
            $this->db->where('salary_details_id', $salary_details_id);
            $this->db->delete(db_prefix() . 'salary_details_to_deductions');

            // delete related records from salary_details_to_attendance
            $this->db->where('salary_details_id', $salary_details_id);
            $this->db->delete(db_prefix() . 'salary_details_to_attendance');
        }
    }

    public function delete_salary_details_by_id($sid)
    {
        // check if record exists
        $this->db->where('id', $sid);
        $query = $this->db->get(db_prefix() . 'salary_details');

        if ($query->num_rows() > 0) {
            // record exists, get the salary_details_id
            
            

            // delete related records from salary_details_to_allowances
            $this->db->where('salary_details_id', $sid);
            $this->db->delete(db_prefix() . 'salary_details_to_allowances');

            // delete related records from salary_details_to_deductions
            $this->db->where('salary_details_id', $sid);
            $this->db->delete(db_prefix() . 'salary_details_to_deductions');

            // delete related records from salary_details_to_attendance
            $this->db->where('salary_details_id', $sid);
            $this->db->delete(db_prefix() . 'salary_details_to_attendance');

            //wallet transactions delettion process
            $this->db->where('id', $sid);
            $salary_details_row =  $this->db->get(db_prefix() . 'salary_details')->row();

            if($salary_details_row->deduct_transaction_id != NULL)
            {
                $this->eraxon_wallet_model->delete_transaction($salary_details_row->deduct_transaction_id);
            }
            if($salary_details_row->deposit_transaction_id != NULL)
            {
                $this->eraxon_wallet_model->delete_transaction($salary_details_row->deposit_transaction_id);
            }

            // delete from salary_details
            $this->db->where('id', $sid);
            $this->db->delete(db_prefix() . 'salary_details');

            log_activity('Salary Slip Deleted [TypeID: ' . $sid .']');
            return true;
        }
        return false;
    }

    Public function get_employees()
    {
        $this->db->select('s.staffid,CONCAT(s.firstname, " ", s.lastname , " (" , s.email, " )" ) as name');
        $this->db->from(db_prefix().'staff as s');
        $this->db->where('s.active', 1);
        $this->db->where('s.admin', 0);
        $query = $this->db->get();

        return $query->result_array();

    }

    public function update_general_bonuses($bonuses)
    {
        // Delete existing records in tblgeneral_bonuses
        $this->db->empty_table(db_prefix().'general_bonuses');

        // Insert new records into tblgeneral_bonuses
        foreach($bonuses as $bonus) {
            $this->db->insert(db_prefix().'general_bonuses', ['bonus_id' => $bonus, 'created_at'=>date('Y-m-d H:i:s')]);
        }
    }

    public function update_employee_bonuses($employee, $employee_bonuses)
    {
        // Delete existing records for the selected employee in tblemployee_bonuses
        $this->db->where('employee_id', $employee);
        $this->db->delete(db_prefix().'employee_bonuses');

        // Insert new records into tblemployee_bonuses
        foreach($employee_bonuses as $bonus) {
            $this->db->insert(db_prefix().'employee_bonuses', ['employee_id' => $employee, 'bonus_id' => $bonus]);
        }
    }

    public function get_general_bonuses()
    {
        $this->db->select('bonus_id');
        $this->db->from(db_prefix().'general_bonuses');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_employee_bonuses()
    {
        $this->db->select(db_prefix().'employee_bonuses.employee_id, '.db_prefix().'employee_bonuses.bonus_id, '.db_prefix().'bonuses.name, CONCAT('.db_prefix().'staff.firstname, " ", '.db_prefix().'staff.lastname) as employee_name');
        $this->db->from(db_prefix().'employee_bonuses');
        $this->db->join(db_prefix().'bonuses', db_prefix().'bonuses.id = '.db_prefix().'employee_bonuses.bonus_id', 'left');
        $this->db->join(db_prefix().'staff', db_prefix().'staff.staffid = '.db_prefix().'employee_bonuses.employee_id', 'left');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function delete_employee_bonuses($employee_id)
    {
        $this->db->where('employee_id', $employee_id);
        $this->db->delete(db_prefix() . 'employee_bonuses');
    }

    public function get_leads_for_staff($staff_id, $month_year)
    {
        $this->db->select('*');
        $this->db->from(db_prefix().'leads');
        $this->db->where('assigned', $staff_id);
        $this->db->where('status', 3);
        $this->db->where('YEAR(dateadded)', date('Y', strtotime($month_year)));
        $this->db->where('MONTH(dateadded)', date('m', strtotime($month_year)));
        $count = $this->db->count_all_results();

        return $count;
    }

    public function get_active_targets() 
    {
        $this->db->where('status', 'Active');
        $this->db->order_by('target', 'DESC');
        $query = $this->db->get(db_prefix() .'targets');
        return $query->result();
    }

    public function e_bonuses($id)
    {
        $this->db->select(db_prefix().'employee_bonuses.bonus_id, '.db_prefix().'bonuses.name, '.db_prefix().'bonuses.amount');
        $this->db->from(db_prefix().'employee_bonuses');
        $this->db->join(db_prefix().'bonuses', db_prefix().'bonuses.id = '.db_prefix().'employee_bonuses.bonus_id', 'left');
        $this->db->where(db_prefix().'employee_bonuses.employee_id', $id); // add this line
        $query = $this->db->get();
        return $query->result_array();
    }

    public function g_bonuses()
    {
        $this->db->select(db_prefix().'general_bonuses.bonus_id, '.db_prefix().'bonuses.name, '.db_prefix().'bonuses.amount');
        $this->db->from(db_prefix().'general_bonuses');
        $this->db->join(db_prefix().'bonuses', db_prefix().'bonuses.id = '.db_prefix().'general_bonuses.bonus_id', 'left');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function insert_bonus_slip($data)
    {
        $this->db->insert(db_prefix().'bonus_slip', $data);

        // Return the ID of the inserted row, or false if the insert failed
        return $this->db->insert_id();
    }

    public function insert_bonus_slip_target_bonus($data)
    {
        $this->db->insert(db_prefix().'bonus_slip_target_bonus', $data);

        // Return the ID of the inserted row, or false if the insert failed
        return $this->db->insert_id();
    }

    public function insert_bonus_slip_bonus($data)
    {
        $this->db->insert(db_prefix().'bonus_slip_bonus', $data);

        // Return the ID of the inserted row, or false if the insert failed
        return $this->db->insert_id();
    }

    public function update_bonus_slip($data, $id)
    {
        $this->db->where('id', $id);
        return $this->db->update(db_prefix().'bonus_slip', $data);
    }

    public function delete_bonus_slip($staff_id, $date)
    {
        $this->db->where('employee_id', $staff_id);
        $this->db->where('month_year', $date);
        $query = $this->db->get(db_prefix().'bonus_slip');
        
        if($query->num_rows() > 0) {
            $bonus_slip_id = $query->row()->id;
            
            $this->db->where('id', $bonus_slip_id);
            $this->db->delete(db_prefix().'bonus_slip');

            $this->db->where('bonus_slip_id', $bonus_slip_id);
            $this->db->delete(db_prefix().'bonus_slip_bonus');

            $this->db->where('bonus_slip_id', $bonus_slip_id);
            $this->db->delete(db_prefix().'bonus_slip_target_bonus');

            return true;
        } else {
            return false;
        }
    }

    public function get_bonus_details() 
    {
        $this->db->select(db_prefix() . 'bonus_slip.*, CONCAT('.db_prefix().'staff.firstname, " ", '.db_prefix().'staff.lastname) as name');
        $this->db->from(db_prefix() . 'bonus_slip');
        $this->db->join(db_prefix().'staff', db_prefix().'staff.staffid = '.db_prefix().'bonus_slip.employee_id', 'left');
        $query = $this->db->get();
        return $query->result();
    }

    public function check_format_date_ymd($date) {
        if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $date)) {
            return true;
        } else {
            return false;
        }
    }

    public function get_hour($date1, $date2) {
        $result = 0;
        if ($date1 != '' && $date2 != '') {
            $timestamp1 = strtotime($date1);
            $timestamp2 = strtotime($date2);
            $result = number_format(abs($timestamp2 - $timestamp1) / (60 * 60), 2);
        }
        return $result;
    }

    /**
     * add check in out value to timesheet
     * @param integer $staff_id 
     */
    public function add_check_in_out_value_to_timesheet($staff_id, $date) {
        $data_check_in_out = $this->timesheets_model->get_list_check_in_out($date, $staff_id);
        $check_in_date = '';
        $check_out_date = '';
        $total_work_hours = 0;
        $next_key = '';
        foreach ($data_check_in_out as $key => $value) {
            if ($value['type_check'] == 2) {
                $check_out_date = $value['date'];
                if ($next_key == $key) {
                    if ($check_out_date != '' && $check_in_date != '') {
                        $data_hour = $this->get_hour($check_in_date, $check_out_date);
                        $total_work_hours += $data_hour;
                    }
                }
            }
            if ($value['type_check'] == 1) {
                $check_in_date = $value['date'];
                $next_key = $key + 1;
            }
        }
        $data_ts = $this->timesheets_model->get_ts_staff($staff_id, $date, 'W');
        if ($total_work_hours > 0) {
            if ($data_ts) {
                $this->db->where('id', $data_ts->id);
                $this->db->update(db_prefix() . 'timesheets_timesheet', [
                    'value' => $total_work_hours,
                    'type' => 'W',
                ]);
                if ($this->db->affected_rows() > 0) {
                    return true;
                }
            } else {
                $data_insert['staff_id'] = $staff_id;
                $data_insert['date_work'] = $date;
                $data_insert['type'] = 'W';
                $data_insert['add_from'] = ((get_staff_user_id() && get_staff_user_id() != 0 && get_staff_user_id() != '') ? get_staff_user_id() : $staff_id);
                $data_insert['value'] = $total_work_hours;
                $this->db->insert(db_prefix() . 'timesheets_timesheet', $data_insert);
                $insert_id = $this->db->insert_id();
                if ($insert_id) {
                    return true;
                }
            }
        } else {
            if ($data_ts) {
                $this->db->where('id', $data_ts->id);
                $this->db->delete(db_prefix() . 'timesheets_timesheet');
                if ($this->db->affected_rows() > 0) {
                    return true;
                }
            }
        }
        return false;
    }

    public function check_exception_for_salary($day)
    {
        $this->db->from(db_prefix() .'eraxon_payroll_exception');
        $this->db->where('date(exceptions) = "' . $day . '"');
        $query = $this->db->get();

        return $query->result_array();
    }

    public function add_exception($data) {
        /*if (isset($data['time_start_work'])) {
            if (!$this->check_format_date_ymd($data['time_start_work'])) {
                $data['time_start_work'] = to_sql_date($data['time_start_work']);
            }
        }
        if (isset($data['time_end_work'])) {
            if (!$this->check_format_date_ymd($data['time_end_work'])) {
                $data['time_end_work'] = to_sql_date($data['time_end_work']);
            }
        }*/
        $this->db->insert(db_prefix() . 'eraxon_payroll_exception', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            return $insert_id;
        }
        return 0;
    }

    public function get_exception()
    {
        return $this->db->get(db_prefix() . 'eraxon_payroll_exception')->result_array();

    }

    public function delete_exception($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'eraxon_payroll_exception');
        if ($this->db->affected_rows() > 0) {
            
            log_activity('Payroll Exception Deleted [SourceID: ' . $id . ']');

            return true;
        }

        return false;
    }


    public function update_salary_slip_status($id, $data)
    {

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'salary_details', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Salary Status Updated [TypeID: ' . $id .']');

            return true;
        }

        return false;
    
    }

    public function update_my_salary_slip_status($id, $data)
    {

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'salary_details', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('My Salary Status Updated [TypeID: ' . $id .']');

            return true;
        }

        return false;
    
    }
    

}