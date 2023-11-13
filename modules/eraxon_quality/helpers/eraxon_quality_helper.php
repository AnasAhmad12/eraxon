<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
	* get position group id by name
	* @return [job_id]
	*/

	function hr_get_position_group_id_by_name($name)
	{
		$CI = & get_instance();

		$CI->db->where('job_name', $name);
		$CI->db->select('job_id');
		$job_group_id = $CI->db->get(db_prefix().'hr_job_p')->row();
		return $job_group_id->job_id;
	}

	/**
	 * hr get list job position id
	 * @param  $id 
	 * @return position_ids      
	 */
	function hr_get_list_job_position_by_jobgroupid($id)
	{
		$position_ids='';
		$CI = & get_instance();

		$CI->db->where('job_p_id', $id);
		$CI->db->select('position_id');
		$job_position_ids = $CI->db->get(db_prefix().'hr_job_position')->result_array();

		if(count($job_position_ids) > 0)
		{
			foreach ($job_position_ids  as $value) 
			{
				if(strlen($position_ids) > 0)
				{
					$position_ids .=','.$value['position_id'];

				}else
				{
					$position_ids .=$value['position_id'];
				}
			}
		}
		
		return $position_ids ;		
	}

	/**
	 * hr get position target by job position id
	 * @param  $position_ids 
	 * @return $staff_ids      
	 */
	 function hr_get_position_target_by_jobpid($jobpid)
	 {
	 	$CI = & get_instance();
	 	$CI->db->select('basic_target');
		$CI->db->where('position_id',$jobpid);
		$position_basic_target = $CI->db->get(db_prefix().'hr_job_position')->row()->basic_target;

		return $position_basic_target;
	 }

	/**
	 * hr get list staff ids by passing position ids
	 * @param  $position_ids 
	 * @return $staff_ids      
	 */
	 function hr_get_staff_ids_by_position_ids($ids)
	 {
	 	$staff_data = array();

		$CI = & get_instance();
		$CI->db->select('staffid,job_position');
		$CI->db->where('job_position IN ('. $ids.') ');
		$CI->db->where('active',1);
		$hr_all_staff = $CI->db->get(db_prefix().'staff')->result_array();
		foreach ($hr_all_staff  as $value) 
		{
			$daily_target = hr_get_position_target_by_jobpid($value['job_position'])/26;
			$data = array(

				'staff_id' => $value['staffid'],
				'job_position_id' => $value['job_position'],
				'daily_target' => $daily_target,
				'assigned' => 0,
				'pending' => 0,
				'check-in' => "",
				'pointer' => ""
			);
			$staff_data[] = $data;
		}
		

		return $staff_data; 
	 }