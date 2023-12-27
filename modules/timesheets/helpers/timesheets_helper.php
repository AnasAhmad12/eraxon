<?php
defined('BASEPATH') or exit('No direct script access allowed');
hooks()->add_action('after_email_templates', 'add_timesheets_email_templates');

/**
 * Check whether column exists in a table
 * Custom function because Codeigniter is caching the tables and this is causing issues in migrations
 * @param  string $column column name to check
 * @param  string $table table name to check
 * @return boolean
 */

function get_timesheets_option($name) {
	$CI = &get_instance();
	$options = [];
	$val = '';
	$name = trim($name);

	if (!isset($options[$name])) {
		// is not auto loaded
		$CI->db->select('option_val');
		$CI->db->where('option_name', $name);
		$row = $CI->db->get(db_prefix() . 'timesheets_option')->row();
		if ($row) {
			$val = $row->option_val;
		}
	} else {
		$val = $options[$name];
	}

	return hooks()->apply_filters('get_timesheets_option', $val, $name);
}

/**
 * row timesheets options exist
 * @param  string $name
 * @return
 */
function row_timesheets_options_exist($name) {
	$CI = &get_instance();
	$i = count($CI->db->query('Select * from ' . db_prefix() . 'timesheets_option where option_name = ' . $name)->result_array());
	if ($i == 0) {
		return 0;
	}
	if ($i > 0) {
		return 1;
	}
}

/**
 * handle timesheets attachments array
 * @param  int $staffid
 * @param  string $index_name
 * @return
 */
function handle_timesheets_attachments_array($staffid, $index_name = 'attachments') {
	$uploaded_files = [];
	$path = TIMESHEETS_MODULE_UPLOAD_FOLDER . '/' . $staffid . '/';
	$CI = &get_instance();
	if (isset($_FILES[$index_name]['name'])
		&& ($_FILES[$index_name]['name'] != '' || is_array($_FILES[$index_name]['name']) && count($_FILES[$index_name]['name']) > 0)) {
		if (!is_array($_FILES[$index_name]['name'])) {
			$_FILES[$index_name]['name'] = [$_FILES[$index_name]['name']];
			$_FILES[$index_name]['type'] = [$_FILES[$index_name]['type']];
			$_FILES[$index_name]['tmp_name'] = [$_FILES[$index_name]['tmp_name']];
			$_FILES[$index_name]['error'] = [$_FILES[$index_name]['error']];
			$_FILES[$index_name]['size'] = [$_FILES[$index_name]['size']];
		}

		_file_attachments_index_fix($index_name);
		for ($i = 0; $i < count($_FILES[$index_name]['name']); $i++) {
			// Get the temp file path
			$tmpFilePath = $_FILES[$index_name]['tmp_name'][$i];

			// Make sure we have a filepath
			if (!empty($tmpFilePath) && $tmpFilePath != '') {
				if (_perfex_upload_error($_FILES[$index_name]['error'][$i])
					|| !_upload_extension_allowed($_FILES[$index_name]['name'][$i])) {
					continue;
				}

				_maybe_create_upload_path($path);
				$filename = unique_filename($path, $_FILES[$index_name]['name'][$i]);
				$newFilePath = $path . $filename;

				// Upload the file into the temp dir
				if (move_uploaded_file($tmpFilePath, $newFilePath)) {
					array_push($uploaded_files, [
						'file_name' => $filename,
						'filetype' => $_FILES[$index_name]['type'][$i],
					]);
					if (is_image($newFilePath)) {
						create_img_thumb($path, $filename);
					}
				}
			}
		}
	}

	if (count($uploaded_files) > 0) {
		return $uploaded_files;
	}

	return false;
}

/**
 * render timesheets yes/no option
 * @param  int $option_value
 * @param  string $label
 * @param  string $tooltip
 * @param  string $replace_yes_text
 * @param  string $replace_no_text
 * @param  string $replace_1
 * @param  string $replace_0
 * @return
 */
function render_timesheets_yes_no_option($option_value, $label, $tooltip = '', $replace_yes_text = '', $replace_no_text = '', $replace_1 = '', $replace_0 = '') {
	ob_start();?>
    <div class="form-group">
        <label for="<?php echo html_entity_decode($option_value); ?>" class="control-label clearfix">
            <?php echo ($tooltip != '' ? '<i class="fa fa-question-circle" data-toggle="tooltip" data-title="' . _l($tooltip, '', false) . '"></i> ' : '') . _l($label, '', false); ?>
        </label>
        <div class="radio radio-primary radio-inline">
            <input type="radio" id="y_opt_1_<?php echo html_entity_decode($label); ?>" name="timesheets_setting[<?php echo html_entity_decode($option_value); ?>]" value="<?php echo html_entity_decode($replace_1) == '' ? 1 : $replace_1; ?>" <?php if (get_timesheets_option($option_value) == ($replace_1 == '' ? '1' : $replace_1)) {
		echo 'checked';
	}?>>
            <label for="y_opt_1_<?php echo html_entity_decode($label); ?>">
                <?php echo html_entity_decode($replace_yes_text) == '' ? _l('settings_yes') : $replace_yes_text; ?>
            </label>
        </div>
        <div class="radio radio-primary radio-inline">
            <input type="radio" id="y_opt_2_<?php echo html_entity_decode($label); ?>" name="timesheets_setting[<?php echo html_entity_decode($option_value); ?>]" value="<?php echo html_entity_decode($replace_0) == '' ? 0 : $replace_0; ?>" <?php if (get_timesheets_option($option_value) == ($replace_0 == '' ? '0' : $replace_0)) {
		echo 'checked';
	}?>>
            <label for="y_opt_2_<?php echo html_entity_decode($label); ?>">
                <?php echo html_entity_decode($replace_no_text) == '' ? _l('settings_no') : $replace_no_text; ?>
            </label>
        </div>
    </div>
    <?php
$settings = ob_get_contents();
	ob_end_clean();
	echo html_entity_decode($settings);
}

/**
 * timesheets reformat currency asset
 * @param  int $value
 * @return
 */
function timesheets_reformat_currency_asset($value) {
	return str_replace(',', '', $value);
}

/**
 * get type of leave name
 * @param  int $id
 * @return
 */
function get_type_of_leave_name($id) {
	$name = '';
	switch ($id) {
	case 1:
		$name = _l('sick_leave');
		break;
	case 2:
		$name = _l('maternity_leave');
		break;
	case 3:
		$name = _l('private_work_with_pay');
		break;
	case 4:
		$name = _l('private_work_without_pay');
		break;
	case 5:
		$name = _l('child_sick');
		break;
	case 6:
		$name = _l('power_outage');
		break;
	case 7:
		$name = _l('meeting_or_studying');
		break;
	case 8:
		$name = _l('annual_leave');
		break;
	}
	return $name;
}

/**
 * handle requisition attachments
 * @param  int $id
 * @return
 */
function handle_requisition_attachments($id) {
	if (isset($_FILES['file']) && _perfex_upload_error($_FILES['file']['error'])) {
		header('HTTP/1.0 400 Bad error');
		echo _perfex_upload_error($_FILES['file']['error']);
		die;
	}
	$path = TIMESHEETS_MODULE_UPLOAD_FOLDER . '/requisition_leave/' . $id . '/';
	$CI = &get_instance();

	if (isset($_FILES['file']['name'])) {
		hooks()->do_action('before_upload_expense_attachment', $id);
		// Get the temp file path
		$tmpFilePath = $_FILES['file']['tmp_name'];
		// Make sure we have a filepath
		if (!empty($tmpFilePath) && $tmpFilePath != '') {
			_maybe_create_upload_path($path);
			$filename = $_FILES['file']['name'];
			$newFilePath = $path . $filename;
			// Upload the file into the temp dir
			if (move_uploaded_file($tmpFilePath, $newFilePath)) {
				$attachment = [];
				$attachment[] = [
					'file_name' => $filename,
					'filetype' => $_FILES['file']['type'],
				];

				$rs = $CI->misc_model->add_attachment_to_database($id, 'requisition', $attachment);
				return $rs;
			}
		}
	}
}
if (!function_exists('add_timesheets_email_templates')) {
	/**
	 * Init appointly email templates and assign languages
	 * @return void
	 */
	function add_timesheets_email_templates() {
		$CI = &get_instance();

		$data['timesheets_attendance_mgt_templates'] = $CI->emails_model->get(['type' => 'timesheets_attendance_mgt', 'language' => 'english']);

		$CI->load->view('timesheets/email_templates', $data);
	}
}
/**
 * crawl get
 * @param  string &$curl
 * @param  string $link
 * @param  string $header
 * @return string
 */
function crawl_get(&$curl, $link, $header = null) {
	$cookie_file = dirname(__FILE__) . '/' . 'cookie.txt';
	curl_setopt($curl, CURLOPT_URL, $link);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_AUTOREFERER, true);
	curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_file);
	curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_file);
	curl_setopt($curl, CURLOPT_COOKIESESSION, true);
	curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.110 Safari/537.36');
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($curl, CURLOPT_TIMEOUT, 120);
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 120);
	curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
	if (isset($header)) {
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
	}
	return curl_exec($curl);
}
/**
 * address2geo
 * @param  string
 * @return json
 */
function address2geo($address) {
	$googlemap_api_key = '';
	$api_key = get_timesheets_option('googlemap_api_key');
	if ($api_key) {
		$googlemap_api_key = $api_key;
	}
	$url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . rawurlencode($address) . "&key=" . $googlemap_api_key;
	$curl = curl_init();
	$curlData = crawl_get($curl, $url);
	$geo = json_decode($curlData);
	if (isset($geo) && isset($geo->results[0])) {
		return json_encode($geo->results[0]->geometry->location);
	}
	return '';
}
/**
 * get workplace name
 * @param  $workplace_id
 * @return string $name
 */
function get_workplace_name($workplace_id) {
	$CI = &get_instance();
	$data = $CI->timesheets_model->get_workplace($workplace_id);
	$name = '';
	if ($data) {
		$name = $data->name;
	}
	return $name;
}

/**
 * list timesheet permisstion
 * @return [type]
 */
function list_timesheet_permisstion() {
	$timesheet_permission = [];
	// Attendance
	$timesheet_permission[] = 'attendance_management';
	// Leave
	$timesheet_permission[] = 'leave_management';
	// Route
	$timesheet_permission[] = 'route_management';
	// Additional timesheets
	$timesheet_permission[] = 'additional_timesheets_management';
	// Work Shift Table
	$timesheet_permission[] = 'table_shiftwork_management';
	// Report
	$timesheet_permission[] = 'report_management';
	// Workplace
	$timesheet_permission[] = 'table_workplace_management';
	return $timesheet_permission;
}

/**
 * timesheet get staff id permissions
 * @return array
 */
function timesheet_get_staff_id_permissions() {
	$CI = &get_instance();
	$array_staff_id = [];
	$index = 0;

	$str_permissions = '';
	foreach (list_timesheet_permisstion() as $per_key => $per_value) {
		if (strlen($str_permissions) > 0) {
			$str_permissions .= ",'" . $per_value . "'";
		} else {
			$str_permissions .= "'" . $per_value . "'";
		}

	}

	$sql_where = "SELECT distinct staff_id FROM " . db_prefix() . "staff_permissions
        where feature IN (" . $str_permissions . ")
        ";

	$staffs = $CI->db->query($sql_where)->result_array();

	if (count($staffs) > 0) {
		foreach ($staffs as $key => $value) {
			$array_staff_id[$index] = $value['staff_id'];
			$index++;
		}
	}
	return $array_staff_id;
}

/**
 * get staff id not permissions
 * @return array
 */
function timesheet_get_staff_id_not_permissions() {
	$CI = &get_instance();
	$CI->db->where('admin != ', 1);
	if (count(timesheet_get_staff_id_permissions()) > 0) {
		$CI->db->where_not_in('staffid', timesheet_get_staff_id_permissions());
	}
	return $CI->db->get(db_prefix() . 'staff')->result_array();

}

/**
 * get status modules
 * @param  string $module_name
 * @return boolean
 */
function timesheet_get_status_modules($module_name) {
	$CI = &get_instance();

	$sql = 'select * from ' . db_prefix() . 'modules where module_name = "' . $module_name . '" AND active =1 ';
	$module = $CI->db->query($sql)->row();
	if ($module) {
		return true;
	} else {
		return false;
	}
}
/**
 *[timesheet staff manager query
 * @param  string $permission
 * @param  string $column
 * @param  string $and
 * @return string
 */
function timesheet_staff_manager_query($permission, $column = 'staffid', $and = 'AND') {
	$query = '';
	$CI = &get_instance();
	if (!is_admin() && !has_permission($permission, '', 'view')) {
		$space = '';
		if ($and != '') {
			$space = ' ';
		}
		if (timesheet_get_status_modules('hr_profile') == true) {
			$CI->load->model('hr_profile/hr_profile_model');
			$list_staff = $CI->hr_profile_model->get_staff_by_manager();
			$list_staff = implode(',', $list_staff);
			$query = $space . $and . $space . $column . ' IN (' . $list_staff . ')';
		} else {
			$query = $space . $and . $space . $column . ' = ' . get_staff_user_id() . '';
		}
	}
	return $query;
}
if (!function_exists('cal_days_in_month')) {
	define('CAL_GREGORIAN', 0);
	function cal_days_in_month($calendar, $month, $year) {
		return date('t', mktime(0, 0, 0, $month, 1, $year));
	}
}
/**
 * get client IP
 * @return string
 */
function get_client_ip() {
	//whether ip is from the share internet
	$ip = '';
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}

/**
	 * timekeeping
	 * @return view
	 */
	function timekeeping_widget($param = []) {

		$CI = &get_instance();
		$CI->load->model('staff_model');
		$CI->load->model('roles_model');
		$CI->load->model('timesheets/timesheets_model');
		$data['title'] = _l('timesheets');
		$month = date('m');
		$month_year = date('Y');
		$days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $month_year);

		$data['departments'] = $CI->departments_model->get();
		$data['roles'] = $CI->roles_model->get();
		$data['day_by_month_tk'] = [];
		$data['day_by_month_tk'][] = _l('staff_id');
		$data['day_by_month_tk'][] = _l('staff');
		$data['set_col_tk'] = [];
		$data['set_col_tk'][] = ['data' => _l('staff_id'), 'type' => 'text'];
		$data['set_col_tk'][] = ['data' => _l('staff'), 'type' => 'text', 'readOnly' => true, 'width' => 200];


     	/* Modified by: Anus*/
		$start_day = 21;
		$end_day = 20;
		$previous_month = $month - 1;
		$prev_month_year = $month_year;


		if ($month == 1) {
		    $previous_month = 12;
		    $prev_month_year--;
		}
		$days_in_prev_month = cal_days_in_month(CAL_GREGORIAN, $previous_month, $prev_month_year);

		$staff_from = $prev_month_year.'-'.$previous_month.'-'.$start_day; 
		$staff_to = $month_year.'-'.$month.'-'.$end_day; 

		for ($d = $start_day; $d <= $days_in_prev_month; $d++) {
			$time = mktime(12, 0, 0, $previous_month, $d, $prev_month_year);
			if (date('m', $time) == $previous_month) {
				array_push($data['day_by_month_tk'], date('D d', $time));
				array_push($data['set_col_tk'], ['data' => date('D d', $time), 'type' => 'text']);
			}
		}

		for ($d1 = 1; $d1 <= $end_day; $d1++) {
			$time = mktime(12, 0, 0, $month, $d1, $month_year);
			if (date('m', $time) == $month) {
				array_push($data['day_by_month_tk'], date('D d', $time));
				array_push($data['set_col_tk'], ['data' => date('D d', $time), 'type' => 'text']);
			}
		}
		/* Modified by: Anus*/

		$data['day_by_month_tk'] = json_encode($data['day_by_month_tk']);
		$data_map = [];
		$data_timekeeping_form = get_timesheets_option('timekeeping_form');
		$data_timekeeping_manually_role = get_timesheets_option('timekeeping_manually_role');
		$data['data_timekeeping_form'] = $data_timekeeping_form;
		$data['staff_row_tk'] = [];
		
		if(isset($param['staffid']))
		{
			$CI->db->where('active',1);
			$CI->db->where('staffid',$param['staffid']);
			$CI->db->order_by('firstname', 'ASC');
			$result = $CI->db->get(db_prefix() . 'staff')->result_array();
			$staffs = $result;
		}else
		{
			$staffs = $CI->timesheets_model->get_staff_timekeeping_applicable_object();
		}
		 
		$data['staffs'] = $staffs;

				//$result = $this->timesheets_model->get_attendance_manual($staffs, $month, $month_year,$staff_from,$staff_to);
		$result = $CI->timesheets_model->get_attendance_manual($staffs,'', '',$staff_from,$staff_to);
		$data['staff_row_tk'] = $result['staff_row_tk'];
		
		$data_lack = [];
		$data['data_lack'] = $data_lack;
		$data['set_col_tk'] = json_encode($data['set_col_tk']);
		
	    //$this->load->view('timekeeping/manage_timekeeping', $data);
		return $data;
		
	}

    function calculate_attendance_timesheet($employee_id,$startDate,$endDate)
    {
    	$CI = &get_instance();
    	$CI->load->model('timesheets/timesheets_model');
    	$CI->load->model('eraxon_payroll/eraxon_payroll_model');
    	
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
        $list_date = $CI->timesheets_model->get_list_date($startDate, $endDate);

        $data['list_date'] = $list_date;
        

        foreach ($list_date as $kdbm => $day) 
        {
            $employee_timeSheet[$counter]['staff_id'] = $employee_id; 
            $employee_timeSheet[$counter]['date'] = $day; 
            $dateObj = new DateTime($day);
            $dayOfWeek = $dateObj->format('w');
            if($dayOfWeek == 0)
            {
                $exception_array = $CI->eraxon_payroll_model->check_exception_for_salary($day);
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

                    $data['addintotimesheet'] = $CI->eraxon_payroll_model->add_check_in_out_value_to_timesheet($employee_id,$exception_array[0]['exceptions']);

                    $exception = 0;
                    
                }else
                {
                    //get shift by employee and day i.e 05 monday is shift 2
                    $list_shift = $CI->timesheets_model->get_shift_work_staff_by_date($employee_id, $day);
                    $ss = !empty($list_shift) ? $list_shift[0] : 0;
                    $data_shift_type = $CI->timesheets_model->get_shift_type($ss);

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
                    $check_holiday = $CI->timesheets_model->check_holiday($employee_id, $day);
                    if(!empty($check_holiday))
                    {
                        $employee_timeSheet[$counter]['hours'] = 0;
                        $employee_timeSheet[$counter]['attendance'] = 'H';

                    }else
                    {

                        $timeSheet = $CI->eraxon_payroll_model->get_timesheet_by_employee_id($employee_id,$startDate,$endDate);
        
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
                    $exception_array = $CI->eraxon_payroll_model->check_exception_for_salary($attendanceDate);
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

function requisition_manage_helper($staffid)
{
	$CI = &get_instance();

	if (!(has_permission('leave_management', '', 'view_own') || has_permission('leave_management', '', 'view') || is_admin())) {
		access_denied('approval_process');
	}
	$send_mail_approve = $CI->session->userdata("send_mail_approve");
	if ((isset($send_mail_approve)) && $send_mail_approve != '') {
		$data['send_mail_approve'] = $send_mail_approve;
		$CI->session->unset_userdata("send_mail_approve");
	}
	$data['userid'] = $staffid;
	$status_leave = $CI->timesheets_model->get_number_of_days_off();
	$day_off = $CI->timesheets_model->get_current_date_off($data['userid']);
	$data['days_off'] = $day_off->days_off;
	$data['number_day_off'] = $day_off->number_day_off;

	$data['data_timekeeping_form'] = get_timesheets_option('timekeeping_form');
	$CI->load->model('departments_model');
	$data['departments'] = $CI->departments_model->get();
	$data['current_date'] = date('Y-m-d H:i:s');
	$status_leave = $CI->timesheets_model->get_option_val();
	$CI->load->model('staff_model');
	$data['pro'] = $CI->staff_model->get('', 'active = 1');
	$data['tab'] = $CI->input->get('tab');
	$data['title'] = _l('leave');
	$data['additional_timesheets_id'] = $CI->input->get('additional_timesheets_id');
	$data['additional_timesheets'] = $CI->timesheets_model->get_additional_timesheets();
	$data['type_of_leave'] = $CI->timesheets_model->get_type_of_leave();
	return $data;
}

function get_staff_id_from_uri(){
	$CI = &get_instance();
	$staffid = $CI->uri->uri_string();
	return $staffid;
}

