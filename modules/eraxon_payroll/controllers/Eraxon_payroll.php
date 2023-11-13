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
        $this->load->model('eraxon_wallet/eraxon_wallet_model');
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
        if($this->input->get('m'))
        {
            $month_year = $this->input->get('m');
            $month_year = date('Y-m',strtotime($month_year));
        }else{

            $month_year = Date('Y-m');
        }
        
        $data['salar_details'] = $this->eraxon_payroll_model->get_salary_details($month_year);
         
        $this->load->model('roles_model');
        $data['roles'] = $this->roles_model->get();
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
        $endDateWallet = clone $selected_month;
        // Modify start date: go to previous month and set day to 21
        $startDate->modify('-1 month');
        $startDate->setDate($startDate->format('Y'), $startDate->format('m'), 21);

        // Modify end date: set day to 20
        $endDate->setDate($endDate->format('Y'), $endDate->format('m'), 20);
        // $data_hs = $this->set_col_tk(21, 30, 06, 2023, true,[3],'');

        $endDateWallet->setDate($endDate->format('Y'), $endDate->format('m'), 20);
        
        $startDate = $startDate->format('Y-m-d');
        $endDate = $endDate->format('Y-m-d');

        $endDateWallet = $endDateWallet->format('Y-m-d H:i:s');
        
        $staff = array();
        // $data['staff_members'] = $this->eraxon_payroll_model->get_staff();
        $staff = $this->eraxon_payroll_model->get_staff($role_id);
        // var_dump($staff);exit;
        foreach ($staff as $member) 
        {
            $joining_check = 0;
            $joining_days = 0;
            $employee_id = $member->staffid;
            $basic_salary = $member->basic_salary;
            

           /* $staff_wallet = $this->eraxon_wallet_model->get_wallet_row_by_staff_id($member->staffid);
            $payable_basic_salary = $staff_wallet->total_balance;
            $transaction = array(
                    'wallet_id' => $staff_wallet->id,
                    'amount_type' => 'Salary ('.$month_year.')',
                    'amount' => $staff_wallet->total_balance,
                    'in_out' => 'out',
                    'created_datetime' => date('Y-m-d H:i:s'),
                    );
            $transaction_id = $this->eraxon_wallet_model->add_transaction($transaction);*/
            
            $date_string = $month_year . '-01';
            $date = new DateTime($date_string);
            $date = $date->format('Y-m-d');
            $this->eraxon_payroll_model->delete_salary_details($employee_id, $date);
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
                'ack_status' => "unseen",
                'date' => $date,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'employee_timesheet' => NULL,
                'sandwitch_timesheet' => NULL,
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
            //calculating joining date if mid of month calculate attendance accordingly
            $joining_date = get_custom_field_value($employee_id,'staff_date_of_appointment','staff',true);
            if (!empty($joining_date)) 
            {
                $joiningDate = new DateTime($joining_date);
                $start = new DateTime($startDate);
                $s = new DateTime($startDate);
                $end = new DateTime($endDate);
                
                if ($joiningDate > $start && $joiningDate < $end) 
                {
                    $start = $joining_date; 
                    $joining_check=1;
                    $interval = $s->diff($joiningDate);
                    $joining_days = $interval->days;
                }else
                {
                    $start = $startDate;
                }
                // echo $joining_days;exit;
            }

            $staff_wallet_total_transactions_amount = $this->eraxon_wallet_model->get_total_transactions_amount($member->staffid,$start,$endDate);
            $payable_basic_salary = $basic_salary; //- $staff_wallet_total_transactions_amount;
            $wallet_id = $this->eraxon_wallet_model->get_walletid_by_staff_id($member->staffid);
            //wallet transaction 
            $transaction = array(
                    'wallet_id' => $wallet_id,
                    'amount_type' => 'Wallet Amount Adjustment-Salary ('.$month_year.')',
                    'amount' => $staff_wallet_total_transactions_amount,
                    'in_out' => 'in',
                    'created_datetime' => $endDateWallet,
                    );
            if($staff_wallet_total_transactions_amount > 0.0)
            {
                $transaction_id = $this->eraxon_wallet_model->add_transaction($transaction);
            }

            //add deduction transaction id into salary details
            $this->eraxon_wallet_model->add_deduct_transaction($id,$transaction_id);

            $short_hours = $this->calculate_short_hours_v2($employee_id,$start,$endDate);

            if($short_hours)
            {
                $per_day_salary = $basic_salary/30;
                if(!empty($joining_check))
                {
                    $payable_basic_salary = $payable_basic_salary-($joining_days * $per_day_salary);
                }

                $total_absent_amount = $per_day_salary * ($short_hours['absents']);
                $half_days_amount = $per_day_salary * ($short_hours['half_days']*0.5);
                $total_leaves_amount = $total_absent_amount + $half_days_amount;



                $gross_salary = $payable_basic_salary + $allowances_amount;
                $net_salary = ($gross_salary - $deductions_amount)- $staff_wallet_total_transactions_amount - $total_leaves_amount - $short_hours['late_amount'];
                $data = array(
                    'salary_details_id'=>$id,
                    'presents' => $short_hours['presents'],
                    'absents' => $short_hours['absents'],
                    'paid_leaves' => $short_hours['paid_leave'], 
                    'half_days' => $short_hours['half_days'],
                    'sandwitch' => $short_hours['sandwitch'],
                    'late' => $short_hours['late'],
                    'late_amount' => $short_hours['late_amount'],
                    'basic_salary' => $payable_basic_salary,
                    'absent_amount' => $total_absent_amount,
                    'half_days_amount' => $half_days_amount,
                    'total_amount' => $total_leaves_amount,
                    'created_at'=>date('Y-m-d H:i:s'),
                );
                $this->eraxon_payroll_model->add_salary_details_to_attendance($data);
            }

            $data = array(
                'total_allowances' => $allowances_amount,
                'total_deductions' => $deductions_amount,
                'total_attendance' => $total_leaves_amount,
                'total_halfdays' => $half_days_amount,
                'total_late' => $short_hours['late_amount'],
                'total_wallet_deduct_amount' => $staff_wallet_total_transactions_amount,
                'gross_salary' => $gross_salary,
                'net_salary' => $net_salary,
                'updated_at' => date('Y-m-d H:i:s'),
                'employee_timesheet' => json_encode($short_hours['employee_timesheet']),
                'sandwitch_timesheet' =>json_encode($short_hours['sandwitch_timesheet']),
            );
            $this->eraxon_payroll_model->update_salary_details($id,$data);
        }
        $message = "Salary generated successfully";
        set_alert('success', $message);
       // redirect(admin_url("eraxon_payroll/generate_salary_slip"));
    }

    public function salary_slip_detail($id='')
    {
        if(!empty($id))
        {
            $data['salary_details'] = $this->eraxon_payroll_model->salary_slip_details($id);
            $data['allowances'] = $this->eraxon_payroll_model->salary_details_to_allowances($id);
            $data['deductions'] = $this->eraxon_payroll_model->salary_details_to_deductions($id);
            $data['adjustments'] = $this->eraxon_payroll_model->salary_details_to_adjustments($id);
            $data['slip_id'] = $id;
            $this->app_scripts->add('jspdf-debug-js','modules/eraxon_payroll/assets/js/jspdf.debug.js');
            $this->app_scripts->add('html2canvas-js','modules/eraxon_payroll/assets/js/html2canvas.js');
            $this->app_scripts->add('html2pdf-js','modules/eraxon_payroll/assets/js/html2pdf.bundle.min.js');
            $this->app_scripts->add('es6-promise-js','modules/eraxon_payroll/assets/js/es6-promise.auto.min.js');            
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

    public function calculate_short_hours_v2($employee_id,$startDate,$endDate)
    {

        //Present = P
        //Absent = A
        //Half Day = HD
        //Holiday = H
        //Sunday = S
        //Applied Leave = AL
        //Paid Leave = PL
        //Exception = E

        $timeSheet = array();
        $employee_timeSheet = array();
        $SandwitchDates = array();
        $paid_leave =0;
        $absents = 0;
        $sandwitch = 0;
        $half_days = 0;
        $presents = 0;
        $late = 0;
        $late_amount = 0;

        $half_day_check = 0;
        $counter = 1;
        $exception = 0;
        //total months dates
        $list_date = $this->timesheets_model->get_list_date($startDate, $endDate);

        $data['list_date'] = $list_date;
        

        foreach ($list_date as $kdbm => $day) 
        {
            $employee_timeSheet[$counter]['staff_id'] = $employee_id; 
            $employee_timeSheet[$counter]['date'] = $day; 
            $dateObj = new DateTime($day);
            $dayOfWeek = $dateObj->format('w');
            if($dayOfWeek == 0)
            {
                $exception_array = $this->eraxon_payroll_model->check_exception_for_salary($day);
                $data['overall'] = $exception_array;

                if(count($exception_array) > 0)
                {
                    $exception = 1;

                }else
                {
                     $exception = 0;
                }

                $data['exception'] = $exception;
            }
            if ($dayOfWeek == 0 && $exception == 0) 
            {

                //check exception here
                $employee_timeSheet[$counter]['hours'] = 0;
                $employee_timeSheet[$counter]['attendance'] = 'S';
            
            }else
            {
                //employee working days and hours
                $hours = '';


                if($exception == 1)
                {
                    $start_date = $exception_array[0]['time_start_work'];
                    $end_date = $exception_array[0]['time_end_work'];
                    $start = new DateTime($start_date);
                    $end = new DateTime($end_date);
                    $interval = $start->diff($end);
                    $hours = $interval->h;
                    $minutes = $interval->i;
                    $total_minutes = $hours * 60 + $minutes;
                    $sixty_percent_time = $total_minutes * 0.6;
                    //if less than this hours there will be short hours
                    $hours = $sixty_percent_time / 60;

                        $data['start_date'] = $start_date;
                        $data['end_date'] = $end_date;
                        $data['total_minutes'] = $total_minutes;
                        $data['sixty_percent_time'] = $sixty_percent_time;
                        $data['hours'] = $hours;
                        $data['day'] = $exception_array[0]['exceptions'];

                    $data['addintotimesheet'] = $this->eraxon_payroll_model->add_check_in_out_value_to_timesheet($employee_id,$exception_array[0]['exceptions']);

                    $exception = 0;
                    
                }else
                {
                    //get shift by employee and day i.e 05 monday is shift 2
                    $list_shift = $this->timesheets_model->get_shift_work_staff_by_date($employee_id, $day);
                    $ss = !empty($list_shift) ? $list_shift[0] : 0;
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


                        $data['start_date'] = $start_date;
                        $data['end_date'] = $end_date;
                        $data['total_minutes'] = $total_minutes;
                        $data['sixty_percent_time'] = $sixty_percent_time;
                        $data['hours'] = $hours;
                        
                    }
                }

                    //Check and add Holiday
                    $check_holiday = $this->timesheets_model->check_holiday($employee_id, $day);
                    if(!empty($check_holiday))
                    {
                        $employee_timeSheet[$counter]['hours'] = 0;
                        $employee_timeSheet[$counter]['attendance'] = 'H';

                    }else
                    {

                        $timeSheet = $this->eraxon_payroll_model->get_timesheet_by_employee_id($employee_id,$startDate,$endDate);
        
                        $data['timesheet'] = $timeSheet;
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
                                        $employee_timeSheet[$counter]['hours'] = $res->value;
                                        $employee_timeSheet[$counter]['attendance'] = 'HD';
                                        $half_day_check = 1;
                                        
                                    }else
                                    {
                                        $employee_timeSheet[$counter]['hours'] = $res->value;
                                        $employee_timeSheet[$counter]['attendance'] = 'P';
                                    } 
                                }else if($type == "U" || $type == "AL" || $type == "SI" || $type == "M" || $type == "CL")
                                {
                                    $employee_timeSheet[$counter]['hours'] = 0;
                                    $employee_timeSheet[$counter]['attendance'] = 'AL';

                                }else if($type == "L")
                                {
                                    if($res->value > 0.33 && $half_day_check == 0)
                                    {
                                        $employee_timeSheet[$counter]['Late'] = $res->value;
                                        $late++;
                                        $late_amount += 200;
                                    }else{
                                        $half_day_check = 0;
                                    }

                                }else if($type != "L" && $type != 'E'){

                                    $employee_timeSheet[$counter]['hours'] = 0;
                                    $employee_timeSheet[$counter]['attendance'] = 'A';
                                }
                            }
                        }else
                        {
                            $employee_timeSheet[$counter]['hours'] = 0;
                            $employee_timeSheet[$counter]['attendance'] = 'A';
                        }
                    }

                
            }

            $counter++;
        }

        //check Sandwitch
        $SandwitchDates = $employee_timeSheet;

         $holiday = array();
            $holiday_counter = 0;
            for ($i=1; $i <=count($SandwitchDates) ; $i++) 
            { 
                if($SandwitchDates[$i]['attendance'] == 'H')
                {
                    $holiday_counter++;
                    if($i == 1)
                    {
                        $nextDay = $i + 1;

                        if($SandwitchDates[$nextDay]['attendance'] == 'A')
                            {
                                $SandwitchDates[$i]['attendance'] = 'A';
                                $sandwitch++;

                            }else if($SandwitchDates[$nextDay]['attendance'] == 'HD')
                            {
                                $SandwitchDates[$i]['attendance'] = 'HD';
                                $sandwitch++;
                            }  


                    }else if($i == count($SandwitchDates))
                    {
                        $previousDay = $i - 1;

                        if($SandwitchDates[$previousDay]['attendance'] == 'A')
                        {
                            $SandwitchDates[$i]['attendance'] = 'A';
                            $sandwitch++;

                        }else if($SandwitchDates[$previousDay]['attendance'] == 'HD')
                        {
                            $SandwitchDates[$i]['attendance'] = 'HD';
                            $sandwitch++;
                        } 

                    }else
                    {
                        $previousDay = $i - 1;
                        $nextDay = $i + 1;
                        $hnextDay = $nextDay;
                        $hcheck = 0;
                        
                        if($SandwitchDates[$previousDay]['attendance'] == 'A')
                        {
                            $hcheck = 1;

                        }else if($SandwitchDates[$previousDay]['attendance'] == 'HD')
                        {
                            $hcheck = 2;
                        } 

                        while(true)
                        {
                            if($SandwitchDates[$hnextDay]['attendance'] == 'H')
                            {
                               $hnextDay++; 

                            }else{

                               break; 
                            }
                        }

                        if($SandwitchDates[$hnextDay]['attendance'] == 'A')
                        {
                            $hcheck = 1;

                        }else if($SandwitchDates[$hnextDay]['attendance'] == 'HD')
                        {
                            if($hcheck == 2)
                            {
                                $hcheck = 2;
                            }
                            
                        }
                        $hnextDay--;
                        for ($h=$hnextDay; $h>= $i ; $h--) 
                        { 
                            if($hcheck == 1)
                            {
                                $SandwitchDates[$h]['attendance'] = 'A';
                                $holiday[] = $SandwitchDates[$h]['date'];
                                $sandwitch++;

                            }else if($hcheck == 2)
                            {
                                $SandwitchDates[$h]['attendance'] = 'HD';
                                $holiday[] = $SandwitchDates[$h]['date'];
                                $sandwitch++;
                            }
                        }


                        
                        $data['holiday'] = $holiday;
                    }
                }
                    
            }

            for ($i=1; $i <=count($SandwitchDates) ; $i++) 
            { 
                $attendanceDate = $SandwitchDates[$i]['date'];
                $attendanceStatus = $SandwitchDates[$i]['attendance'];

                //Check Sandwitch for sundays
                if($attendanceStatus == 'S')
                {
                    //check Exception
                    $exception_array = $this->eraxon_payroll_model->check_exception_for_salary($attendanceDate);
                    if(count($exception_array) > 0)
                    {
                        continue;
                    }else
                    {   
                        if($i == 1)
                        {
                            $nextDay = $i + 1;

                            if($SandwitchDates[$nextDay]['attendance'] == 'A')
                            {
                                $SandwitchDates[$i]['attendance'] = 'A';
                                $sandwitch++;

                            }else if($SandwitchDates[$nextDay]['attendance'] == 'HD')
                            {
                                $SandwitchDates[$i]['attendance'] = 'HD';
                                $sandwitch++;
                            }                         

                        }else if($i == count($SandwitchDates))
                        {
                            $previousDay = $i - 1;
                            if($SandwitchDates[$previousDay]['attendance'] == 'A')
                            {
                                $SandwitchDates[$i]['attendance'] = 'A';
                                $sandwitch++;

                            }else if($SandwitchDates[$previousDay]['attendance'] == 'HD')
                            {
                                $SandwitchDates[$i]['attendance'] = 'HD';
                                $sandwitch++;
                            } 

                        }else
                        {
                            $previousDay = $i - 1;
                            $nextDay = $i + 1;
                            $scheck1 = 0;

                            //check Previous
                            if($SandwitchDates[$previousDay]['attendance'] == 'A')
                            {
                                $SandwitchDates[$i]['attendance'] = 'A';
                                $scheck1 = 1;
                                $sandwitch++;

                            }else if($SandwitchDates[$previousDay]['attendance'] == 'HD')
                            {
                                $SandwitchDates[$i]['attendance'] = 'HD';
                                $sandwitch++;
                            }

                            if($scheck1 == 0)
                            {
                                 //check next
                                if($SandwitchDates[$nextDay]['attendance'] == 'A')
                                {
                                    $SandwitchDates[$i]['attendance'] = 'A';
                                    $sandwitch++;

                                }else if($SandwitchDates[$nextDay]['attendance'] == 'HD')
                                {
                                    $SandwitchDates[$i]['attendance'] = 'HD';
                                    $sandwitch++;
                                } 
                            }
                                         

                        }
                    }

                   
                }

            }
            

        $data['SandwitchDates'] = $SandwitchDates;
        $data['counter'] = $holiday_counter;
        $data['attendance'] = $employee_timeSheet;

       

        for ($i=1; $i <=count($SandwitchDates) ; $i++) 
        {
            if($SandwitchDates[$i]['attendance'] == 'P')
            {
                $presents++;

            }else if($SandwitchDates[$i]['attendance'] == 'A' )
            {
                $absents ++;

            }else if($SandwitchDates[$i]['attendance'] == 'AL' )
            {
                $paid_leave++;

            }else if($SandwitchDates[$i]['attendance'] == 'HD' )
            {
                $half_days++;
            }
        } 
        $data = array(
            'paid_leave'=>$paid_leave,
            'presents'=>$presents,
            'half_days'=>$half_days,
            'absents'=>$absents,
            'sandwitch' => $sandwitch,
            'late' => $late,
            'late_amount' => $late_amount, 
            'employee_timesheet' => $employee_timeSheet,
            'sandwitch_timesheet' => $SandwitchDates

        );

        return $data;
        //$this->load->view('eraxon_payroll/dump_data', $data);
        
    }

    public function bonuses_settings()
    {
        $data['bonuses'] = $this->eraxon_payroll_model->get_bonuses();
        $data['employees'] = $this->eraxon_payroll_model->get_employees();
        $general_bonuses = array_map(function($item) {
            return $item['bonus_id'];
        }, $this->eraxon_payroll_model->get_general_bonuses());
        $data['general_bonuses'] = $general_bonuses;
        $data['employee_bonuses'] = $this->eraxon_payroll_model->get_employee_bonuses();
        $this->load->view('eraxon_payroll/bonuses_settings', $data);
    }

    public function save_bonuses_settings()
    {
        $bonuses = $this->input->post('bonuses');
        $employee_bonuses = $this->input->post('employee_bonuses');
        $employee = $this->input->post('employee');

        // Call the model methods to handle the database operations
        $this->eraxon_payroll_model->update_general_bonuses($bonuses);
        $this->eraxon_payroll_model->update_employee_bonuses($employee, $employee_bonuses);
        $message = "Bonuses updated successfully";
        set_alert('success', $message);
        redirect(admin_url("eraxon_payroll/bonuses_settings"));
    }

    public function delete_employee_bonuses($employee_id)
    {
        $this->eraxon_payroll_model->delete_employee_bonuses($employee_id);
        $message = "Bonus deleted successfully";
        set_alert('success', $message);
        redirect(admin_url('eraxon_payroll/bonuses_settings'));
    }

    public function generate_bonus_slip()
    {
        $data['bonus_details'] = $this->eraxon_payroll_model->get_bonus_details();
        
        $this->load->model('roles_model');
        $data['roles'] = $this->roles_model->get();
        $this->load->view('eraxon_payroll/generate_bonuses_slip',$data);
    }

    // public function generate_bonus_slips()
    // {
    //     $month_year =  $this->input->post('month_timesheets');
    //     $role_id =  $this->input->post('role_id');
    //     if(empty( $month_year) || empty($role_id))
    //     {
    //         $message = "Please select a valid date and role.";
    //         set_alert('error', $message);
    //         redirect(admin_url("eraxon_payroll/generate_bonus_slip"));
    //         exit;
    //     }

    //     $staff = $this->eraxon_payroll_model->get_staff($role_id);

    // }

    public function generate_bonus_slips()
    {
        $month_year =  $this->input->post('month_timesheets');
        $role_id =  $this->input->post('role_id');
        if(empty( $month_year) || empty($role_id))
        {
            $message = "Please select a valid date and role.";
            set_alert('error', $message);
            redirect(admin_url("eraxon_payroll/generate_bonus_slip"));
            exit;
        }

        $staff = $this->eraxon_payroll_model->get_staff($role_id);
        
        if (!empty($staff)) {
            foreach($staff as $staff_member){
                $staff_id = $staff_member->staffid; // assuming staffid is the id of the staff member
                $date_string = $month_year . '-01';
                $date = new DateTime($date_string);
                $date = $date->format('Y-m-d');
                $this->eraxon_payroll_model->delete_bonus_slip($staff_id, $date);

                $leads = $this->eraxon_payroll_model->get_leads_for_staff($staff_id, $month_year);
                $leads_count = $leads;
                $targets = $this->eraxon_payroll_model->get_active_targets();
                
                $data = array(
                    'performance_bonus'=>0,
                    'bonus'=>0,
                    'employee_id'=>$staff_id,
                    'total_bonus'=>0,
                    'month_year'=>$date,
                    'leads_achieved'=>$leads_count,
                    'accumulative_bonus'=>0,
                    'created_at'=>date('Y-m-d H:i:s'),
                );
                $insert_id = $this->eraxon_payroll_model->insert_bonus_slip($data);
                // Initialize bonus to 0
                $leadsbonus = $accumulative_bonus = 0;
                if (!empty($targets)) {
                    foreach($targets as $target){
                        
                        if($leads_count > $target->target){
                            $leadsbonus = $target->bonus;
                            // Assuming accumulative_bonus is a percentage
                            $accumulative_bonus = $leadsbonus * ($target->accumulative_bonus / 100);
                            $leadsbonus = $leadsbonus - $accumulative_bonus;
                            $data = array(
                                'leads_achieved'=>$leads_count,
                                'target_name'=>$target->name,
                                'target_leads'=>$target->target,
                                'target_bonus'=>$target->bonus,
                                'accumulative_bonus'=>$target->accumulative_bonus,
                                'bonus_slip_id'=>$insert_id,
                            );
                            $this->eraxon_payroll_model->insert_bonus_slip_target_bonus($data);
                            break;
                        }
                    }
                }
                /** caculating general and specific emplyee bonuses */
                $general_bonuses = $this->eraxon_payroll_model->g_bonuses();                
                $employee_bonuses = $this->eraxon_payroll_model->e_bonuses($staff_id);  
                $merged_bonuses = [];
                if (!empty($general_bonuses)) {
                    foreach ($general_bonuses as $bonus) {
                        $key = $bonus['bonus_id'];
                        $merged_bonuses[$key] = $bonus;
                    }
                }
                
                if (!empty($employee_bonuses)) {
                    foreach ($employee_bonuses as $bonus) {
                        $key = $bonus['bonus_id'];
                        $merged_bonuses[$key] = $bonus;
                    }
                }     
                $total_bonus_amount = 0;

                if (!empty($merged_bonuses)) {
                    foreach ($merged_bonuses as $bonus) {
                        $total_bonus_amount += $bonus['amount'];
                        $data = array(
                            'bonus_id'=>$bonus["bonus_id"],
                            'bonus_name'=>$bonus['name'],
                            'bonus_amount'=>$bonus["amount"],
                            'bonus_slip_id'=>$insert_id,
                        );
                        $this->eraxon_payroll_model->insert_bonus_slip_bonus($data);
                    }
                }
                $total_bonus = $leadsbonus + $total_bonus_amount;
                $data = array(
                    'performance_bonus'=>$leadsbonus,
                    'bonus'=>$total_bonus_amount,
                    'total_bonus'=>$total_bonus,
                    'accumulative_bonus'=>$accumulative_bonus,
                );
                $this->eraxon_payroll_model->update_bonus_slip($data, $insert_id);
                
            }
        }
        redirect(admin_url('eraxon_payroll/generate_bonus_slip'));
    }

    public function salary_settings()
    {
        if ($this->input->post()) 
        {
            $data = $this->input->post();
            $ins = $this->eraxon_payroll_model->add_exception($data);
            if($ins)
            {
                set_alert('success', _l('added_successfully', "Request"));
            }
            

        }

            $data['exceptions'] = $this->eraxon_payroll_model->get_exception();
  
        $this->load->view('eraxon_payroll/salary_settings', $data);
    }

    public function delete_exception($id)
    {
        if (!$id) {
            redirect(admin_url('eraxon_payroll/salary_settings'));
        }
        $response = $this->eraxon_payroll_model->delete_exception($id);

        if($response)
        {
            set_alert('success', _l('deleted', "Request deleted Successfully"));

        }else
        {
             set_alert('warning', _l('problem_deleting', _l('lead_source_lowercase')));
        }
        redirect(admin_url('eraxon_payroll/salary_settings'));
    }

    public function salary_pdf_view($id='')
    {
         if(!empty($id))
        {
            //$this->load->library('Pdf');
            $data['salary_details'] = $this->eraxon_payroll_model->salary_slip_details($id);
            $data['allowances'] = $this->eraxon_payroll_model->salary_details_to_allowances($id);
            $data['deductions'] = $this->eraxon_payroll_model->salary_details_to_deductions($id);

        }
        $this->load->view('eraxon_payroll/salary_slip_detail_pdf', $data);
    }

    public function salary_slip_status()
    {
        if ($this->input->post()) 
        {
            $data = $this->input->post();
            $slip_id = $data['id'];
            $data2 = array('status' => $data['status']);
            
            $success = $this->eraxon_payroll_model->update_salary_slip_status($slip_id,$data2);
            if ($success) {

                    /*if($data['status'] == 'paid')
                    {
                        $this->eraxon_wallet_model->add_salary_deposit_into_wallet($slip_id); 
                    }*/
                   
                    echo 1;
                   // set_alert('success', _l('updated_successfully',"Stutus updated!"));
                }
        }
    }

    public function salary_slip_detail_delete($id)
    {
        $success =$this->eraxon_payroll_model->delete_salary_details_by_id($id);
        if ($success) 
        {
            set_alert('success', _l('updated_successfully',"Slip Deleted!"));
        }
        redirect(admin_url('eraxon_payroll/generate_salary_slip'));

    }

    public function my_salary()
    {
        $data['salar_details'] = $this->eraxon_payroll_model->get_salary_details();
         
        $this->load->model('roles_model');
        $data['roles'] = $this->roles_model->get();
        $this->load->view('eraxon_payroll/my_salary', $data);
    }

    public function my_salary_slip_status($sid)
    {
            $slip_id = $sid;
            $data = array('ack_status' => 'acknowledged');
            
            $success = $this->eraxon_payroll_model->update_my_salary_slip_status($slip_id,$data);
            if ($success) {
                    set_alert('success', _l('updated_successfully',"Stutus updated!"));
                    redirect(admin_url('eraxon_payroll/my_salary'));
                }
        
    }

    public function payroll_adjustment($salary_slip_id)
    {
        $data['salary_details'] = $this->eraxon_payroll_model->salary_slip_details($salary_slip_id);
        $data['salary_slip_id'] = $salary_slip_id;
        $this->load->view('eraxon_payroll/payroll_adjustment', $data);
    }

    public function add_payroll_adjustment()
    {
        if ($this->input->post()) 
        {
            $data = $this->input->post();
            $data['created_at'] = date('Y-m-d H:i:s');
            $slip_id = $data['salary_details_id']; 

            $id = $this->eraxon_payroll_model->add_payroll_adjustment($slip_id,$data);
             if ($id) 
             {
                set_alert('success', _l('added_successfully', "Request"));
                redirect(admin_url("eraxon_payroll/generate_salary_slip"));
             } 
        }
        redirect(admin_url("eraxon_payroll/generate_salary_slip"));
    }
   
}