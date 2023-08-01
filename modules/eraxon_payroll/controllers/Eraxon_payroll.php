<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 
 */
class Eraxon_payroll extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('eraxon_payroll_model');
        $this->load->model('timesheets/timesheets_model');
    }

    public function allownces()
    {
        $data['allownces'] = $this->eraxon_payroll_model->get_allownces();
        $this->load->view('eraxon_payroll/allownces_list', $data);
    }

    // public function add_allownces()

    public function deductions()
    {
        $data['deductions'] = $this->eraxon_payroll_model->get_deductions();
        $this->load->view('eraxon_payroll/deductions_list',$data);
    }

    public function add_allownces($id='')
    {
        $data = array();
        if ($id != '') {
            // Get the record from the database
            $data['allowance'] = $this->eraxon_payroll_model->get_allownce_by_id($id);
        }
        $this->load->view('eraxon_payroll/allownces',$data);
    }

    public function add_deductions($id='')
    {
        $data = array();
        if ($id != '') {
            $data['deduction'] = $this->eraxon_payroll_model->get_deduction_by_id($id);
        }
        $this->load->view('eraxon_payroll/deduction',$data);
    }

    public function save_allownces()
    {
        $id = $this->input->post('id');
        $allowance_name = $this->input->post('name');
        $type = $this->input->post('type');
        $amount = $this->input->post('amount');
        // check if id exists
        if($id) {
            // update the record
            $res = $this->eraxon_payroll_model->update_allownce($id, $allowance_name, $type, $amount);
        } else {
            // insert a new record
            $res = $this->eraxon_payroll_model->insert_allownce($allowance_name, $type, $amount);
        }
        // $res = $this->eraxon_payroll_model->insert_allownce($allowance_name, $type, $amount);
        if($res) {
            $message = $id ? "Allownce updated successfully" : "Allownce added successfully";
            set_alert('success', $message);
        } else {
            $message = $id ? "Failed to update allownce" : "Failed to add allownce";
            set_alert('error', $message);
        }
        redirect(admin_url("eraxon_payroll/add_allownces/". $id));
    }

    public function save_deductions()
    {
        $id = $this->input->post('id');
        $deduction_name = $this->input->post('name');
        $type = $this->input->post('type');
        $amount = $this->input->post('amount');
        if($id) {
            // update the record
            $res = $this->eraxon_payroll_model->update_deduction($id, $deduction_name, $type, $amount);
        } else {
            $res = $this->eraxon_payroll_model->insert_deduction($deduction_name, $type, $amount);
        }
        if($res)
        {
            $message = $id ? "Deduction updated successfully":"Deduction added successfully";
		    set_alert('success', $message);
        }else{
            $message = $id ?"Failed to update deduction" :"Failed to add deduction";
		    set_alert('error', $message);
        }
        redirect(admin_url("eraxon_payroll/add_deductions/" . $id));
    }

    public function delete_allownce($id) {
        $res = $this->eraxon_payroll_model->delete_allownce($id);
    
        if($res) {
            $message = "Allownce deleted successfully";
            set_alert('success', $message);
        } else {
            $message = "Failed to delete allownce";
            set_alert('error', $message);
        }
    
        redirect(admin_url("eraxon_payroll/allownces"));
    }

    public function delete_deduction($id) {
        $res = $this->eraxon_payroll_model->delete_deduction($id);
    
        if($res) {
            $message = "Deduction deleted successfully";
            set_alert('success', $message);
        } else {
            $message = "Deduction to delete allownce";
            set_alert('error', $message);
        }
    
        redirect(admin_url("eraxon_payroll/deductions"));
    }
    
    public function targets()
    {
        $data['targets'] = $this->eraxon_payroll_model->get_targets();
        $this->load->view('eraxon_payroll/targets_list', $data);
    }

    public function add_targets($id='')
    {
        $data = array();
        if ($id != '') {
            // Get the record from the database
            $data['target'] = $this->eraxon_payroll_model->get_target_by_id($id);
        }
        $this->load->view('eraxon_payroll/targets', $data);
    }

    public function save_targets()
    {
        $id = $this->input->post('id');
        $target_name = $this->input->post('name');
        $bonus = $this->input->post('bonus');
        $target = $this->input->post('target');
        $accumulative_bonus = $this->input->post('accumulative_bonus');
        $status = $this->input->post('status');
        if (isset($status)) {
            $status = "Active";
        } else {
            $status = "Inactive";
        }

        
        // check if id exists
        if($id) {
            // update the record
            $res = $this->eraxon_payroll_model->update_target($id, $target_name, $bonus, $target, $accumulative_bonus,$status );
        } else {
            // insert a new record
            $res = $this->eraxon_payroll_model->insert_target($target_name, $bonus, $target, $accumulative_bonus,$status);
        }

        if($res) {
            $message = $id ? "Target updated successfully" : "Target added successfully";
            set_alert('success', $message);
        } else {
            $message = $id ? "Failed to update target" : "Failed to add target";
            set_alert('error', $message);
        }

        redirect(admin_url("eraxon_payroll/add_targets/". $id));
    }

    public function delete_target($id) 
    {
        $res = $this->eraxon_payroll_model->delete_target($id);
    
        if($res) {
            $message = "Target deleted successfully";
            set_alert('success', $message);
        } else {
            $message = "Failed to delete target";
            set_alert('error', $message);
        }
    
        redirect(admin_url("eraxon_payroll/targets"));
    }

    public function bonuses()
    {
        $data['bonuses'] = $this->eraxon_payroll_model->get_bonuses();
        $this->load->view('eraxon_payroll/bonuses_list', $data);
    }
    
    public function add_bonuses($id='')
    {
        $data = array();
        if ($id != '') {
            $data['bonus'] = $this->eraxon_payroll_model->get_bonus_by_id($id);
        }
        $this->load->view('eraxon_payroll/bonuses', $data);
    }

    public function save_bonuses()
    {
        $id = $this->input->post('id');
        $bonus_name = $this->input->post('name');
        $amount = $this->input->post('amount');
        // check if id exists
        if($id) {
            // update the record
            $res = $this->eraxon_payroll_model->update_bonus($id, $bonus_name, $amount);
        } else {
            // insert a new record
            $res = $this->eraxon_payroll_model->insert_bonus($bonus_name, $amount);
        }

        if($res) {
            $message = $id ? "Bonus updated successfully" : "Bonus added successfully";
            set_alert('success', $message);
        } else {
            $message = $id ? "Failed to update bonus" : "Failed to add bonus";
            set_alert('error', $message);
        }

        redirect(admin_url("eraxon_payroll/add_bonuses/". $id));
    }

    public function delete_bonus($id) 
    {
        $res = $this->eraxon_payroll_model->delete_bonus($id);

        if($res) {
            $message = "Bonus deleted successfully";
            set_alert('success', $message);
        } else {
            $message = "Failed to delete bonus";
            set_alert('error', $message);
        }

        redirect(admin_url("eraxon_payroll/bonuses"));
    }

    public function generate_salary_slip()
    {
        $data['salar_details'] = $this->eraxon_payroll_model->get_salary_details();
        
        $this->load->model('roles_model');
        $data['roles'] = $this->roles_model->get();
        // var_dump($data);exit;
        $this->load->view('eraxon_payroll/generate_salary_slip', $data);
    }

    public function generate_salary_slips()
    {
        $month_year =  $this->input->post('month_timesheets');
        $role_id =  $this->input->post('role_id');
        if(empty( $month_year) || empty($role_id))
        {
            $message = "Please select a valid date and role.";
            set_alert('error', $message);
            redirect(admin_url("eraxon_payroll/generate_salary_slip"));
            exit;
        }
        
        
        
        // Create DateTime object for the first day of the selected month
        $selected_month = DateTime::createFromFormat('Y-m', $month_year);
        // Create start and end dates
        $startDate = clone $selected_month;
        $endDate = clone $selected_month;
        // Modify start date: go to previous month and set day to 21
        $startDate->modify('-1 month');
        $startDate->setDate($startDate->format('Y'), $startDate->format('m'), 21);

        // Modify end date: set day to 20
        $endDate->setDate($endDate->format('Y'), $endDate->format('m'), 20);
        // $data_hs = $this->set_col_tk(21, 30, 06, 2023, true,[3],'');
        
        $startDate = $startDate->format('Y-m-d');
        $endDate = $endDate->format('Y-m-d');
        
        $staff = array();
        // $data['staff_members'] = $this->eraxon_payroll_model->get_staff();
        $staff = $this->eraxon_payroll_model->get_staff($role_id);
        // var_dump($staff);exit;
        foreach ($staff as $member) 
        {
            $employee_id = $member->staffid;
            $basic_salary = $member->basic_salary;
            $date_string = $month_year . '-01';
            $date = new DateTime($date_string);
            $date = $date->format('Y-m-d');
            $allowances_amount =0;
            $deductions_amount =0;

            $data = array(
                'employee_id' => $employee_id,
                'basic_salary' => $basic_salary,
                'total_allowances' => 0,
                'total_deductions' => 0,
                'total_attendance' => 0,
                'total_halfdays' => 0,
                'tax' => 0,
                'gross_salary' => 0,
                'net_salary' => 0,
                'status' => "unpaid",
                'ack_status' => "",
                'date' => $date,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'created_by' => '',
                'updated_by' => '',
            );
            $id = $this->eraxon_payroll_model->add_salary_detail($data);
            $job_position_id = $member->job_position;
            $allowances = $this->eraxon_payroll_model->get_allowances_details($job_position_id);
            $deductions = $this->eraxon_payroll_model->get_deductions_details($job_position_id);
            // var_dump($allowances);
            if(!empty($allowances))
            {
                $allowances_amount = $this->calculate_allowances($id,$basic_salary,$allowances);
            }
            if(!empty($deductions))
            {
                $deductions_amount = $this->calculate_deductions($id,$basic_salary,$deductions);
            }

            $short_hours = $this->calculate_short_hours($employee_id,$startDate,$endDate);
            if($short_hours)
            {
                $per_day_salary = $basic_salary/30;
                $total_leaves_amount = $per_day_salary*$short_hours['total_leaves'];
                $half_leaves_amount = $per_day_salary*$short_hours['half_leaves'];
                $gross_salary = $basic_salary + $allowances_amount;
                $net_salary = ($gross_salary - $deductions_amount) - $total_leaves_amount;
                $data = array(
                    'salary_details_id'=>$id,
                    'paid'=>$short_hours['paid'],
                    'leaves'=>$short_hours['leaves'],
                    'basic_salary'=>$basic_salary,
                    'total_leaves'=>$short_hours['total_leaves'],
                    'sandwitch'=>$short_hours['sandwitch'],
                    'half_leaves'=>$short_hours['half_leaves'],
                    'total_amount'=>$total_leaves_amount,
                    'total_half_leaves_amount'=>$half_leaves_amount,
                    'created_at'=>date('Y-m-d H:i:s'),
                );
                $this->eraxon_payroll_model->add_salary_details_to_attendance($data);
            }

            $data = array(
                'total_allowances' => $allowances_amount,
                'total_deductions' => $deductions_amount,
                'total_attendance' => $short_hours['total_leaves'],
                'total_halfdays' => $short_hours['half_leaves'],
                'gross_salary' => $gross_salary,
                'net_salary' => $net_salary,
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $this->eraxon_payroll_model->update_salary_details($id,$data);
        }
        $message = "Salary generated successfully";
        set_alert('success', $message);
        redirect(admin_url("eraxon_payroll/generate_salary_slip"));
    }

    public function salary_slip_detail($id='')
    {
        if(!empty($id))
        {
            $data['salary_details'] = $this->eraxon_payroll_model->salary_slip_details($id);
            $data['allowances'] = $this->eraxon_payroll_model->salary_details_to_allowances($id);
            $data['deductions'] = $this->eraxon_payroll_model->salary_details_to_deductions($id);
        }
        $this->load->view('eraxon_payroll/salary_slip_detail', $data);

    }

    public function calculate_allowances($id,$basic_salary,$allowances)
    {
        $total_amount = 0;
        foreach ($allowances as $allowance) 
        {
            $calculated_amount = 0;
            if ($allowance->allowance_type == 'Fixed') 
            {
                $calculated_amount = $allowance->allowance_amount;
            } 
            else if ($allowance->allowance_type == 'Percentage') 
            {
                $calculated_amount = ($basic_salary * ($allowance->allowance_amount / 100));
            }
            $total_amount +=$calculated_amount;
            if(!empty($allowance->allowance_type))
            {
                $data = array(
                    'salary_details_id' => $id,
                    'allowance_id' => $allowance->allowance_id,
                    'allowance_amount' => $allowance->allowance_amount,
                    'total_amount' => $calculated_amount,
                    'created_at' => date('Y-m-d H:i:s'),
                );
                $this->eraxon_payroll_model->add_salary_details_to_allowances($data);
            }
            
        }

        return $total_amount;
    }

    public function calculate_deductions($id,$basic_salary, $deductions) {
        $total_deductions = 0;
        foreach ($deductions as $deduction) {
            $calculated_deduction = 0;
            if ($deduction->deduction_type == 'Fixed') {
                $calculated_deduction = $deduction->deduction_amount;
            } else if ($deduction->deduction_type == 'Percentage') {
                $calculated_deduction = ($basic_salary * ($deduction->deduction_amount / 100));
            }
            $total_deductions += $calculated_deduction;
            if(!empty($deduction->deduction_type)) {
                $data = array(
                    'salary_details_id' => $id,
                    'deduction_id' => $deduction->deduction_id,
                    'deduction_amount' => $deduction->deduction_amount,
                    'total_amount' => $calculated_deduction,
                    'created_at' => date('Y-m-d H:i:s'),
                );
                $this->eraxon_payroll_model->add_salary_details_to_deductions($data);
            }
        }
        return $total_deductions;
    }

    public function calculate_short_hours($employee_id,$startDate,$endDate)
    {
        $timeSheet = array();
        $SandwitchDates = array();
        $leave_counter =0;
        $leaves = 0;
        $sandwitch = 0;
        $half_leaves = 0;
        $list_date = $this->timesheets_model->get_list_date($startDate, $endDate);
        
        foreach ($list_date as $kdbm => $day) 
        {
            $today = "";$hours = '';
            //get shift by employee and day i.e 05 monday is shift 2
            $list_shift = $this->timesheets_model->get_shift_work_staff_by_date($employee_id, $day);
            $ss = !empty($list_shift) ? $list_shift[0] : 0;
        
            $dateObj = new DateTime($day);
            $dayOfWeek = $dateObj->format('w');
            if ($dayOfWeek == 0) {
                $today = "Sunday";
            }
            $data_shift_type = $this->timesheets_model->get_shift_type($ss);
            if ($data_shift_type) 
            {
                $start_date = $data_shift_type->time_start_work;
                $end_date = $data_shift_type->time_end_work;
                $start = new DateTime($start_date);
                $end = new DateTime($end_date);
                $interval = $start->diff($end);
                $hours = $interval->h;
                $minutes = $interval->i;
                $total_minutes = $hours * 60 + $minutes;
                $sixty_percent_time = $total_minutes * 0.6;
                //if less than this hours there will be short hours
                $hours = $sixty_percent_time / 60;
                
            }
            if(empty($timeSheet))
            {
                $timeSheet = $this->eraxon_payroll_model->get_timesheet_by_employee_id($employee_id,$startDate,$endDate);
            }
            if($today!="Sunday")
            {
                $result = array_filter($timeSheet, function ($item) use ($day) {
                    return $item->date_work == $day;
                });

                if(!empty($result))
                {
                    foreach($result as $res)
                    {
                        $type = $res->type;
                        if($type == "W")
                        {
                            //working hours is less than 60 % of working hours
                            if($res->value < $hours)
                            {
                                $half_leaves+=0.5;
                                // $leaves +=0.5;
                                break;
                            } 
                        }
                        if($type == "U" || $type == "AL" || $type == "SI" || $type == "M")
                        {
                            if($leave_counter==0)
                            {
                                $leave_counter = 1;
                            }
                            else
                            {
                                $leaves += 1; 
                                $backDate = new DateTime($day);
                                $forwardDate = new DateTime($day);

                                while (true) {
                                    // Go one day back
                                    $backDate->modify('-1 day');
                                    $backDateString = $backDate->format('Y-m-d');
                                    if($backDate->format('Y-m-d') < $startDate)
                                    {
                                        break;
                                    }
                                    //todo: have to get dayoff from tblday_off
                                    $check_holiday = $this->timesheets_model->check_holiday($employee_id, $backDateString);

                                    // If the day is not a public holiday or a Sunday, break the loop
                                    if ($backDate->format('w') != 0 && (empty($check_holiday))) {
                                        break;
                                    } else {
                                        // If the day is a public holiday or a Sunday, increment the leaves
                                        if (!in_array($backDate->format('Y-m-d'), $SandwitchDates)) {
                                            $SandwitchDates[] = $backDate->format('Y-m-d');
                                            $leaves += 1; 
                                            $sandwitch +=1;
                                        }
                                    }
                                }

                                while (true) {
                                    // Go one day forward
                                    $forwardDate->modify('+1 day');
                                    $forwardDateString = $forwardDate->format('Y-m-d');
                                    if($forwardDate->format('Y-m-d') > $endDate)
                                    {
                                        break;
                                    }
                                    $check_holiday = $this->timesheets_model->check_holiday($employee_id, $forwardDateString);
                                    var_dump($check_holiday);

                                    // If the day is not a public holiday or a Sunday, break the loop
                                    if ($forwardDate->format('w') != 0 && (empty($check_holiday))) {
                                        break;
                                    } else {
                                        // If the day is a public holiday or a Sunday, increment the leaves
                                        if (!in_array($forwardDate->format('Y-m-d'), $SandwitchDates)) {
                                            $SandwitchDates[] = $forwardDate->format('Y-m-d');
                                            $leaves += 1; 
                                            $sandwitch +=1;
                                        }
                                    }
                                }
                            }
                            
                        }
                    }
                }
                else{
                    if($leave_counter==0)
                    {
                        $leave_counter = 1;
                    }
                    else
                    {
                        $check_holiday = $this->timesheets_model->check_holiday($employee_id, $day);
                        if(empty($check_holiday))
                        {
                            $leaves += 1; 
                            $backDate = new DateTime(date('Y-m-d', strtotime($day)));
                            $forwardDate = new DateTime(date('Y-m-d', strtotime($day)));

                            while (true) {
                                // Go one day back
                                $backDate->modify('-1 day');
                                $backDateString = $backDate->format('Y-m-d');
                                if($backDate->format('Y-m-d') < $startDate)
                                {
                                    break;
                                }
                                //todo: have to get dayoff from tblday_off
                                $check_holiday = $this->timesheets_model->check_holiday($employee_id, $backDateString);
                                
                                // If the day is not a public holiday or a Sunday, break the loop
                                if ($backDate->format('w') != 0 && (empty($check_holiday))) {
                                    break;
                                } else {
                                    // If the day is a public holiday or a Sunday, increment the leaves
                                    if (!in_array($backDate->format('Y-m-d'), $SandwitchDates)) {
                                        $SandwitchDates[] = $backDate->format('Y-m-d');
                                        $leaves += 1; 
                                        $sandwitch +=1;
                                    }
                                    
                                }
                            }

                            while (true) {
                                // Go one day forward
                                $forwardDate->modify('+1 day');
                                $forwardDateString = $forwardDate->format('Y-m-d');
                                
                                if($forwardDate->format('Y-m-d') > $endDate)
                                {
                                    break;
                                }
                                
                                $check_holiday = $this->timesheets_model->check_holiday($employee_id, $forwardDateString);

                                // If the day is not a public holiday or a Sunday, break the loop
                                if ($forwardDate->format('w') != 0 && (empty($check_holiday))) {
                                    break;
                                } 
                                else 
                                {
                                    // If the day is a public holiday or a Sunday, increment the leaves
                                    if (!in_array($forwardDate->format('Y-m-d'), $SandwitchDates)) {
                                        $SandwitchDates[] = $forwardDate->format('Y-m-d');
                                        $leaves += 1; 
                                        $sandwitch +=1;
                                    }
                                }
                            }
                        }
                            
                    }
                }

            } 
        }
        $data = array(
            'paid'=>$leave_counter,
            'leaves'=>$leaves,
            'total_leaves'=>($leaves+$half_leaves),
            'sandwitch'=>$sandwitch,
            'half_leaves'=>$half_leaves,
            'paid'=>$leave_counter,
        );
        return $data;
    }
    public function set_col_tk($from_day, $to_day, $month, $month_year, $absolute_type = true, $stafflist = '', $work_shift_id = '') {
		$list_data = [];
		$data_day_by_month = [];
		$data_time = [];
		$data_day_by_month_tk = [];
		$data_set_col = [];
		$data_set_col_tk = [];
		$data_object = [];
		$data_shift_type = $this->timesheets_model->get_shift_type();
		$new_list_shift = [];

		if ($absolute_type == true) {
			if ($stafflist) {
				array_push($data_day_by_month, 'staffid');
				array_push($data_day_by_month, _l('staff'));
				array_push($list_data, [
					'data' => 'staffid', 'type' => 'text', 'readOnly' => true,
				]);
				array_push($list_data, [
					'data' => 'staff', 'type' => 'text', 'readOnly' => true,
				]);
			}
			for ($d = $from_day; $d <= $to_day; $d++) {
				$time = mktime(12, 0, 0, $month, $d, $month_year);

				if (date('m', $time) == $month) {
					array_push($data_time, $time);
					array_push($data_day_by_month_tk, date('D d', $time));
					array_push($data_day_by_month, date('D d', $time));
					array_push($data_set_col, ['data' => date('D d', $time), 'type' => 'text']);
					array_push($data_set_col_tk, ['data' => date('D d', $time), 'type' => 'text']);

					array_push($data_set_col_tk, ['data' => date('D d', $time), 'type' => 'text']);
					array_push($list_data, [
						'data' => date('D d', $time),
						'editor' => "chosen",
						'chosenOptions' => [
							'data' => $new_list_shift,
						],
					]);
				}
			}
			if ($stafflist) {
				$this->load->model('staff_model');
				foreach ($stafflist as $key => $value) {
					$data_staff = $this->staff_model->get($value);
					$staff_id = $data_staff->staffid;
					$staff_name = $data_staff->firstname . ' ' . $data_staff->lastname;
					$data_shift_staff = [];

					$row_data_staff = new stdClass();
					$row_data_staff->staffid = $staff_id;
					$row_data_staff->staff = $staff_name;
					foreach ($data_time as $k => $time) {
						$times = date('D d', $time);
						$date_s = date('Y-m-d', $time);
						$row_data_staff->$times = $this->timesheets_model->get_id_shift_type_by_date_and_master_id($staff_id, $date_s, $work_shift_id);
					}
					$data_object[] = $row_data_staff;
				}
			} else {
				$row_data_staff = new stdClass();
				foreach ($data_time as $k => $time) {
					$times = date('D d', $time);
					$date_s = date('Y-m-d', $time);
					$id_shift_type = '';
					$staff_id = '';
					$first_staff = $this->timesheets_model->get_first_staff_work_shift($work_shift_id);
					if ($first_staff) {
						$staff_id = $first_staff->staff_id;
					}
					$data_s = $this->timesheets_model->get_id_shift_type_by_date_and_master_id($staff_id, $date_s, $work_shift_id);
					$row_data_staff->$times = $data_s;
				}
				$data_object[] = $row_data_staff;
			}
		} else {
			$day_list = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];
			if ($stafflist) {
				array_push($data_day_by_month, 'staffid');
				array_push($data_day_by_month, _l('staff'));
				array_push($list_data, [
					'data' => 'staffid', 'type' => 'text', 'readOnly' => true,
				]);
				array_push($list_data, [
					'data' => 'staff', 'type' => 'text', 'readOnly' => true,
				]);
			}
			foreach ($day_list as $key => $value) {
				array_push($data_day_by_month_tk, $value);
				array_push($data_day_by_month, $value);
				array_push($data_set_col, ['data' => $value, 'type' => 'text']);
				array_push($data_set_col_tk, ['data' => $value, 'type' => 'text']);
				array_push($list_data, [
					'data' => $value,
					'editor' => "chosen",
					'chosenOptions' => [
						'data' => $new_list_shift,
					],
				]);
			}
			if ($stafflist) {
				$this->load->model('staff_model');
				foreach ($stafflist as $key => $value) {
					$data_staff = $this->staff_model->get($value);
					$staff_id = $data_staff->staffid;
					$staff_name = $data_staff->firstname . ' ' . $data_staff->lastname;

					$data_shift_staff = [];
					$row_data_staff = new stdClass();
					$row_data_staff->staffid = $staff_id;
					$row_data_staff->staff = $staff_name;
					for ($i = 1; $i <= 7; $i++) {
						$shift_type_id = '';
						$data_shift_type = $this->timesheets_model->get_shift_type_id_by_number_day($work_shift_id, $i, $staff_id);
						if ($data_shift_type) {
							$shift_type_id = $data_shift_type->shift_id;
						}
						$day_name = $day_list[$i - 1];
						$row_data_staff->$day_name = $shift_type_id;
					}
					$data_object[] = $row_data_staff;
				}
			} else {
				$row_data_staff = new stdClass();
				for ($i = 1; $i <= 7; $i++) {
					$shift_type_id = '';
					$data_shift_type = $this->timesheets_model->get_shift_type_id_by_number_day($work_shift_id, $i);

					if ($data_shift_type) {
						$shift_type_id = $data_shift_type->shift_id;
					}
					$day_name = $day_list[$i - 1];
					$row_data_staff->$day_name = $shift_type_id;
				}
				$data_object[] = $row_data_staff;
			}
		}
		$obj = new stdClass();
		$obj->day_by_month = $data_day_by_month;
		$obj->day_by_month_tk = $data_day_by_month_tk;
		$obj->set_col = $data_set_col;
		$obj->set_col_tk = $data_set_col_tk;
		$obj->list_data = $list_data;
		$obj->data_object = $data_object;
		return $obj;
	}



}