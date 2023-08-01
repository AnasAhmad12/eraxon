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
        $this->db->select(db_prefix().'staff.*, jp.position_name, jp.position_id, jp.basic_salary');
        $this->db->from(db_prefix().'staff');
        $this->db->join(db_prefix().'hr_job_position as jp', db_prefix().'staff.job_position = jp.position_id', 'left');
        $this->db->where(db_prefix().'staff.active', 1);
        $this->db->where(db_prefix().'staff.admin', 0);
        $this->db->where(db_prefix().'staff.role', $role_id);
        $query = $this->db->get();

        return $query->result();


        return $query->result();

        // $this->db->select("tblstaff.*, tblhr_job_position.*, tbljob_allowance_deduction.*");
        // $this->db->from('tblstaff');
        // $this->db->join('tblhr_job_position', 'tblstaff.job_position = tblhr_job_position.position_id', 'left');
        // $this->db->join('tbljob_allowance_deduction', 'tblhr_job_position.position_id = tbljob_allowance_deduction.jobid', 'left');
        // $this->db->where('tblstaff.active', 1);
        // $query = $this->db->get();

        // return $query->result();

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

    public function get_salary_details()
    {
        $this->db->select('sd.*, CONCAT(s.firstname, " ", s.lastname) as name,r.name as rolename');
        $this->db->from(db_prefix() . 'salary_details as sd');
        $this->db->join(db_prefix() . 'staff as s', 's.staffid = sd.employee_id', 'left');
        $this->db->join(db_prefix() . 'roles as r', 'r.roleid = s.role', 'left');
        $this->db->order_by('sd.id', 'DESC');
        $query = $this->db->get();

        return $query->result();
    }

    
    public function salary_slip_details($id)
    {
        $this->db->select('sd.*, CONCAT(s.firstname, " ", s.lastname) as name, r.name as rolename, at.*');
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


}