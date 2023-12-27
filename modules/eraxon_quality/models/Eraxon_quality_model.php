<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * quality model
 */
class Eraxon_quality_model extends App_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get_qa_status()
    {
        $this->db->order_by('id', 'asc');

        return $this->db->get(db_prefix() . 'qa_status')->result_array();
    }

    public function add_qa_status($data)
    {
        $this->db->insert(db_prefix() . 'qa_status', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New QA Status Added [ID: ' . $insert_id . ']');
        }

        return $insert_id;
    }

    public function update_qa_status($data, $id)
    {

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'qa_status', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('QA status updated [TypeID: ' . $id . ', ID: ' . $data['name'] . ']');

            return true;
        }

        return false;
    }

    public function delete_qa_status($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'qa_status');
        if ($this->db->affected_rows() > 0) {

            log_activity('QA Status Deleted [SourceID: ' . $id . ']');

            return true;
        }

        return false;
    }

    public function get_qar_status()
    {
        $this->db->order_by('id', 'asc');

        return $this->db->get(db_prefix() . 'qa_review_status')->result_array();
    }

    public function add_qar_status($data)
    {
        $this->db->insert(db_prefix() . 'qa_review_status', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New QA reviewer status added [ID: ' . $insert_id . ']');
        }

        return $insert_id;
    }

    public function update_qar_status($data, $id)
    {

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'qa_review_status', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('QA reviewer status updated [TypeID: ' . $id . ', ID: ' . $data['name'] . ']');

            return true;
        }

        return false;
    }

    public function delete_qar_status($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'qa_review_status');
        if ($this->db->affected_rows() > 0) {

            log_activity('QA reviewer status deleted [SourceID: ' . $id . ']');

            return true;
        }

        return false;
    }

    public function get_columns()
    {
        $this->db->order_by('id', 'asc');
        $this->db->select(db_prefix() . 'qa_campaign_column.*,name');
        $this->db->from(db_prefix() . 'qa_campaign_column');
        $this->db->join(db_prefix() . 'leads_type', db_prefix() . 'leads_type.id=' . db_prefix() . 'qa_campaign_column.camp_type_id', 'left');
        $column = $this->db->get()->result();
        return $column;
    }

    public function get_campaigns($flag = true)
    {
        if ($flag) {
            $this->db->select("camp_type_id");
            $camp_done = $this->db->get(db_prefix() . 'qa_campaign_column')->result();

            $excluded_ids = [];

            foreach ($camp_done as $c) {
                array_push($excluded_ids, $c->camp_type_id);
            }
            if ($camp_done) {
                $this->db->where_not_in('id', $excluded_ids);
            }
            $camp_type = $this->db->get(db_prefix() . 'leads_type')->result_array();
            return $camp_type;
        } else {
            $camp_type = $this->db->get(db_prefix() . 'leads_type')->result();
            return $camp_type;
        }

    }

    public function get_campaigns_with_col()
    {
        $this->db->select(db_prefix() . 'leads_type.*');
        $this->db->from(db_prefix() . 'leads_type');
        $this->db->join(db_prefix() . 'qa_campaign_column', db_prefix() . 'leads_type.id =' . db_prefix() . 'qa_campaign_column.camp_type_id');
        $query = $this->db->get();

        $result = $query->result();

        return $result;
    }

    public function add_campaign_columns($post_data)
    {
        $this->db->insert(db_prefix() . 'qa_campaign_column', $post_data);
        return $this->db->error();
    }

    public function add_orignal_lead_data($data, $id,$lead_exist_id=0)
    {
        
        $this->db->where('camp_type_id', $data['lead_type']);
        $col_data = $this->db->get(db_prefix() . 'qa_campaign_column')->result_array();

        $columns = json_decode($col_data[0]['column'], true);

        $str = array();
        if($lead_exist_id!=0 && $lead_exist_id!=null){
            $this->db->where('lead_id',$lead_exist_id);
            $lead=$this->db->get(db_prefix().'qa_lead')->result();
            if($lead){
                 $temp=json_decode($lead[0]->complete_lead,true);
                foreach ($temp as &$item) {
                    if (isset($item['date'])) {
                        $item['date'] = date('m-d-Y');
                    }
                    if (isset($item['local_agent_name'])) {
                        $item['local_agent_name'] = $data['name'];
                    }
                }
                $str=$temp;
            }
            else{
                foreach ($columns as $col) {
                    if ($col['data'] == 'date') {
                        $str[] = array($col['data'] => date('m-d-Y'));
        
                    } else if ($col['data'] == 'local_agent_name') {
        
                        $str[] = array($col['data'] => $data['name']);
        
                    } else if ($col['data'] == 'phone_number') {
                        $str[] = array($col['data'] => $data['phonenumber']);
                    } else {
                        $str[] = array($col['data'] => $col['default']);
                    }
                }
            }
        }else{
            foreach ($columns as $col) {
                if ($col['data'] == 'date') {
                    $str[] = array($col['data'] => date('m-d-Y'));
    
                } else if ($col['data'] == 'local_agent_name') {
    
                    $str[] = array($col['data'] => $data['name']);
    
                } else if ($col['data'] == 'phone_number') {
    
                    $str[] = array($col['data'] => $data['phonenumber']);
                } else {
                    $str[] = array($col['data'] => $col['default']);
                }
            }
        }

        $datam = array(
            'complete_lead' => json_encode($str),
          	'phonenumber'=> $data['phonenumber'],
            'lead_status' => 'pending',
            'qa_status' => 'pending',
            'review_status' => 'pending',
            'lead_type' => $data['lead_type'],
            'assigned_staff' => 0,
            'lead_id' => $id,
            'lead_date' => date('Y-m-d H:i:s'),
        );

        $this->db->insert(db_prefix() . 'qa_lead', $datam);

    }

    public function get_campaign_columns($id)
    {

        $this->db->where('camp_type_id', $id);
        $col = $this->db->get(db_prefix() . 'qa_campaign_column')->result();
        $col = json_decode($col[0]->column);
        array_splice($col, 0, 0, ['id' => 'id']);

        return $col;
    }



    public function get_unassinged_leads_sheet($flag,$type,$start_date,$end_date)
    {

      
        if($type=="unassigned"){
            $this->db->where("assigned_staff", 0);
        }

        $this->db->where('DATE(lead_date) >=', $start_date);
        $this->db->where('DATE(lead_date) <=', $end_date);
        if ($flag != "all") {
            $this->db->where('added_sheet_distributor!=', 1);
        }

        $u_leads = $this->db->get(db_prefix() . 'qa_lead')->result();

        $u_leads_array = [];

        foreach ($u_leads as $l) {

            $col = json_decode($l->complete_lead, true);
            $newArray = array();

            foreach ($col as $item) {
                foreach ($item as $key => $value) {
                    $newArray[$key] = $value;
                }
            }
            $temp_lead = [
                'id' => $l->id,
              	 'date'=>$newArray['date'],
                'agent_name' => $newArray['local_agent_name'],
                'lead_type' => get_campaigns_by_id($l->lead_type),
                'phone_no' => $newArray['phone_number'],
                'assigned_to' => get_staff_full_name($l->assigned_staff),
              	'lead_status' =>$l->qa_status 
            ];
            array_push($u_leads_array, $temp_lead);

        }
        $this->db->where("assigned_staff", 0);
        $this->db->where('DATE(lead_date) >=', $start_date);
        $this->db->where('DATE(lead_date) <=', $end_date);
        $u_leads = $this->db->update(db_prefix() . 'qa_lead', ['added_sheet_distributor' => 0]);
        return $u_leads_array;



    }

    public function assign_distribution($id, $staff_id)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'qa_lead', ["assigned_staff" => $staff_id]);
       
        $this->db->where('assigned_staff',$staff_id);
        $this->db->where('DATE(lead_date)', date('Y-m-d'));
        $total_leads_assigned=count($this->db->get(db_prefix().'qa_lead')->result());
        $option=json_decode(get_option('auto_distribution_staffid_with_daily_targets',1));
       foreach($option as &$op){
        if($op->staff_id==$staff_id){
            $op->assigned=$total_leads_assigned;
        }
       }
       
        update_option('auto_distribution_staffid_with_daily_targets',json_encode($option));
      
      
        return $this->db->error();
    }



    public function get_leads($id, $start_date, $end_date, $flag, $staff_id,$keyword)
    {

         $campaign_sheet_lead = [];

        $this->db->where('lead_type', $id);
        if(has_permission('qa_department', '', 'qa_reviewer') && !is_admin()) {
            if($keyword!=""){
                $this->db->like('phonenumber',$keyword);
            }else{
                $this->db->where('DATE(qc_date) >=', $start_date);
                $this->db->where('DATE(qc_date) <=', $end_date);
                $this->db->where('DATE(lead_date) >=', $start_date);
                $this->db->where('DATE(lead_date) <=', $end_date);
            }
           
        } 
        else {
            if($keyword!=""){
                $this->db->like('phonenumber',$keyword);
            }else{
            $this->db->where('DATE(lead_date) >=', $start_date);
            $this->db->where('DATE(lead_date) <=', $end_date);
            }
        }

        if(has_permission('qa_department', '', 'qa_person') && !is_admin()) {
            $this->db->where('assigned_staff', $staff_id);
            $this->db->where('qa_status', 'pending');



        } elseif(has_permission('qa_department', '', 'qa_reviewer') && !is_admin()) { // change it to reviewer
            $this->db->where('qa_status !=', 'pending');
        }

        if($flag != 'all' && has_permission('qa_department', '', 'qa_person') && !is_admin()) {
            $this->db->where('added_sheet!=', 1);
        }

        if($flag != 'all' && has_permission('qa_department', '', 'qa_reviewer') && !is_admin()) {
            $this->db->where('added_sheet_reviewer!=', 1);
        }

        if($flag != 'all' && has_permission('qa_department', '', 'qa_manager') && !is_admin()) {
            $this->db->where('added_sheet_manager!=', 1);
        }

        if($flag != 'all' && is_admin()) {
            $this->db->where('added_sheet_admin!=', 1);
        }



        $lead = $this->db->get(db_prefix().'qa_lead')->result();

        $this->db->where('lead_type', $id);
        $this->db->where('DATE(lead_date) >=', $start_date);
        $this->db->where('DATE(lead_date) <=', $end_date);

        if(has_permission('qa_department', '', 'qa_person') && !is_admin()) {
            $this->db->where('assigned_staff', $staff_id);
            $this->db->update(db_prefix().'qa_lead', ['added_sheet' => 1]);
        }

        if(has_permission('qa_department', '', 'qa_reviewer') && !is_admin()) {
            $this->db->where('DATE(qc_date) >=', $start_date);
            $this->db->where('DATE(qc_date) <=', $end_date);

            $this->db->where('qa_status!=', 'pending');
            $this->db->update(db_prefix().'qa_lead', ['added_sheet_reviewer' => 1]);
        }

        if(has_permission('qa_department', '', 'qa_manager') && !is_admin()) {
            $this->db->update(db_prefix().'qa_lead', ['added_sheet_manager' => 1]);
        }


        if(is_admin()) {
            $this->db->update(db_prefix().'qa_lead', ['added_sheet_admin' => 1]);
        }

        foreach($lead as $l) {

            if(has_permission('qa_person', '', 'view') && !is_admin()) {
                $newObjects = [
                    'qa_status' => $l->qa_status,
                    'forwardable_comments' => $l->forwardable_comments,
                    'qa_comments' => $l->qa_comments
                ];
            } else {
                $newObjects = [
                    'lead_status' => $l->lead_status,
                    'qa_status' => $l->qa_status,
                    'reviewer_status' => $l->review_status,
                    'forwardable_comments' => $l->forwardable_comments,
                    'qa_comments' => $l->qa_comments,
                    'rejection_comments' => $l->rejection_comments,
                    'qa_person' => get_staff_full_name($l->assigned_staff),
                    'qa_rating' => $l->qa_lead_rating,
                  	'lead_uploaded' => $l->lead_uploaded

                ];
            }
            $col = json_decode($l->complete_lead, true);
            $combinedObject = [];

            $col[] = $newObjects;

            foreach($col as $object) {
                $combinedObject = array_merge($combinedObject, $object);
            }
            array_splice($combinedObject, 0, 0, ['id' => $l->id]);
            array_push($campaign_sheet_lead, $combinedObject);
        }





        return $campaign_sheet_lead;

    }



    public function get_status_col()
    {
        $qa_status = [];
        $qa_review_status = [];
        $qa = $this->db->get(db_prefix() . 'qa_review_status')->result();
        $review = $this->db->get(db_prefix() . 'qa_status')->result();

        foreach ($qa as $a) {
            array_push($qa_review_status, $a->name);
        }
        foreach ($review as $a) {
            array_push($qa_status, $a->name);
        }
        $data['qa_review_status'] = $qa_review_status;
        $data['qa_status'] = $qa_status;
        return $data;

    }

    public function update_complete_lead($col, $id, $status)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'qa_lead', ["complete_lead" => json_encode($col),"qc_date"=>date('Y-m-d')]);
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'qa_lead', $status);


        return $this->db->error();
    }
    public function calculateTimeDifferenceInMinutes($startTime, $endTime)
    {
        // Convert the start and end times to DateTime objects
        $startDateTime = new DateTime($startTime);
        $endDateTime = new DateTime($endTime);

        // Calculate the difference in minutes
        $interval = $startDateTime->diff($endDateTime);
        $minutes = $interval->format('%i');

        return $minutes;
    }

   /* public function get_qa_present_staff($qa_staffs, $today)
    {
        $available_staff = array();
        $counter = 1;
        foreach ($qa_staffs as $key => $qa_staff) {
            $this->db->where('staff_id', $qa_staff['staff_id']);
            $this->db->where('date_format(date, "%Y-%m-%d") = "' . $today . '"');
            $this->db->get(db_prefix() . 'check_in_out');
            if ($this->db->affected_rows() > 0) {
				if($this->db->affected_rows() % 2 == 0)
                {
                    $qa_staffs[$key]['check-in'] = 0;
                    $available_staff[] = $qa_staffs[$key];
                }else
                {
                    $qa_staffs[$key]['check-in'] = 1;
                    if ($counter == 1) {
                        $qa_staffs[$key]['pointer'] = "p";
                        $qa_staffs[$key]['myturn'] = 1;
                        $counter++;
                    }
                    $available_staff[] = $qa_staffs[$key];
                } 
            }else{

                $qa_staffs[$key]['check-in'] = 0;
            } 
        }

        return $qa_staffs;
    }*/
  
    public function get_qa_present_staff($qa_staffs, $today)
    {
        $counter = 1;
        foreach ($qa_staffs as $key => $qa_staff) {
            $this->db->where('staff_id', $qa_staff['staff_id']);
            $this->db->where('date_format(date, "%Y-%m-%d") = "' . $today . '"');
            $this->db->get(db_prefix() . 'check_in_out');
            if ($this->db->affected_rows() > 0) {

                if($this->db->affected_rows() % 2 == 0)
                {
                    $qa_staffs[$key]['check-in'] = 0;
                   
                }else
                {
                    $qa_staffs[$key]['check-in'] = 1;                   
                } 
            }else{

                $qa_staffs[$key]['check-in'] = 0;
            } 
        }

        return $qa_staffs;
    }

    public function get_unassigned_leads($today,$limit_count)
    {
        $result = $this->db->where("assigned_staff", 0)
            ->where('DATE(lead_date)', $today)
            ->limit($limit_count)
            ->get(db_prefix() . 'qa_lead')->result_array();
        return $result;
    }

    public function get_new_leads()
    {
        $qa_staff_id = get_staff_user_id();
        $this->db->select('lead_type, COUNT(*) as lead_count');
        $this->db->from(db_prefix().'qa_lead');
        $this->db->where('assigned_staff', $qa_staff_id);
        $this->db->where('added_sheet!=', 1);

        $this->db->group_by('lead_type');
        $query = $this->db->get()->result();
        return $query;

    }
    public function assigned_lead_to($staffid, $qaleadid)
    {
        $this->db->where("id", $qaleadid);
        $this->db->update(db_prefix() . 'qa_lead', array('assigned_staff' => $staffid));
    }

   public function get_number_of_pending_leads(&$qa_staff)
    {
        $max_pending = array();
        $current_date = date('Y-m-d');
        $factor = 0;
        foreach ($qa_staff as $key => &$staff) {
            $this->db->where('assigned_staff', $staff['staff_id']);
            $this->db->where('qa_status', 'pending');
            $this->db->where('DATE(lead_date)', $current_date);
            $this->db->get(db_prefix() . 'qa_lead');
            $pendings = $this->db->affected_rows();
            $max_pending[] = $pendings;

            $staff['pending'] = $pendings;
            $qa_staff[$key] = $staff;
        }
     
        if(max($max_pending) <= 3)
        {
            $factor = 4;

        }else{
        
          	$factor =  max($max_pending);
        
        }
     
        update_option('auto_distribution_pending_factor', $factor );
        return $factor;
    }
  
    public function findMaxDifference($numbers) 
    {
        if (count($numbers) < 2) {
            return 10;
        }

        $maxDifference = $numbers[1] - $numbers[0];

        for ($i = 0; $i < count($numbers); $i++) {
            for ($j = $i + 1; $j < count($numbers); $j++) {
                $difference = $numbers[$j] - $numbers[$i];
                if ($difference > $maxDifference) {
                    $maxDifference = $difference;
                }
            }
        }

        return $maxDifference;
    }
  
   /* public function get_number_of_pending_leads(&$qa_staff)
    {
        $max_pending = array();
        $current_date = date('Y-m-d');
        foreach ($qa_staff as $key => &$staff) {
            $this->db->where('assigned_staff', $staff['staff_id']);
            $this->db->where('qa_status', 'pending');
            $this->db->where('DATE(lead_date)', $current_date);
            $this->db->get(db_prefix() . 'qa_lead');
            $pendings = $this->db->affected_rows();
            $max_pending[] = $pendings;

            $staff['pending'] = $pendings;
            $qa_staff[$key] = $staff;
        }

        $max_difference = $this->findMaxDifference($max_pending);
        if($max_difference <= 5)
        {
            $max_difference = 10;

        }else{
            $max_difference = max($max_pending);

        }
        update_option('auto_distribution_pending_factor', $max_difference);
        return $max_difference;
    }*/

    public function get_campaign_columns_by_id($id)
    {
        $this->db->where('id', $id);
        $data = $this->db->get(db_prefix() . 'qa_campaign_column')->result();
      	$resp['id']= $id;
        $resp['camp_name'] = get_campaigns_by_id($data[0]->camp_type_id);
        $resp['column'] = json_decode($data[0]->column);
        return $resp;
    }

    public function delete_campaign($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'qa_campaign_column');
    }
  
  public function report_by_day_leads_staffs()
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

			$chart['daily_leads'][] = $this->leads_by_day($dd);
			//$chart['approved_leads'][] = $this->leads_by_day($dd, 'Approved');
			//$chart['rejected_leads'][] = $this->leads_by_day($dd, 'Rejected');

		}

		return $chart;
	}

    public function leads_by_day($day)
	{
		$this->db->select('count(id) as total_leads');

		if(is_staff_member() && !is_admin())
		{
			//$this->db->where(db_prefix().'leads.assigned',get_staff_user_id());
			$this->db->where('assigned_staff',get_staff_user_id());
            $this->db->where('qa_status!=','pending');

		}
		//$sql_where = "date_format(".db_prefix()."leads.dateadded, '%Y-%m') = '".$month."'";
		$sql_where = "date_format(lead_date, '%Y-%m-%d') = '".$day."'";
		$this->db->where($sql_where);
		//$result = $this->db->get(db_prefix().'leads')->row();
		$result = $this->db->get(db_prefix().'qa_lead')->row();

		if($result){
			return (int)$result->total_leads;
		}
		return 0;
	}

    public function report_by_leads_staffs()
	{
		//$months_report = $this->input->post('months_report');
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

			$chart['leads'][] = $this->leads_by_month($month);
		}

		return $chart;
	}
  
    public function edit_column($id,$post_data){
        $this->db->where('id',$id);
        $this->db->update(db_prefix().'qa_campaign_column',$post_data);
        return $this->db->error();
    }

    public function leads_by_month($month)
	{
		$this->db->select('count(id) as total_leads');
		$this->db->where('qa_status!=','pending');		

		if(is_staff_member() && !is_admin())
		{
			$this->db->where('assigned_staff',get_staff_user_id());
		}
		//$sql_where = "date_format(".db_prefix()."leads.dateadded, '%Y-%m') = '".$month."'";
		$sql_where = "date_format(lead_date, '%Y-%m') = '".$month."'";
		$this->db->where($sql_where);
		//$result = $this->db->get(db_prefix().'leads')->row();
		$result = $this->db->get(db_prefix().'qa_lead')->row();

		if($result){
			return (int)$result->total_leads;
		}
		return 0;
	}
  
  public function get_reports()
    {
        $this->db->select('assigned_staff, COUNT(CASE WHEN qa_status = "pending" THEN 1 END) AS pending_count, COUNT(*) AS total_leads');
        $this->db->from(db_prefix() . 'qa_lead');
        $this->db->where('assigned_staff!=', 0);
        $this->db->where('DATE(lead_date)', date('Y-m-d'));
        $this->db->group_by('assigned_staff');
        $query = $this->db->get();
        $result = $query->result();

        foreach ($result as &$re) {
            $re->active = $this->staff_active($re->assigned_staff);
        }

        $this->db->select('COUNT(CASE WHEN qa_status = "pending" THEN 1 END) AS pending_count, 
                      COUNT(CASE WHEN qa_status = "approved" THEN 1 END) AS approved_count,
                      COUNT(CASE WHEN qa_status  = "reject" THEN 1 END) AS rejected_count,
                      COUNT(CASE WHEN assigned_staff != 0 THEN 1 END) AS total_assigned_leads,
                      COUNT(*) AS total_leads');
                      
        $this->db->from(db_prefix().'qa_lead');
        $this->db->where('DATE(lead_date)', date('Y-m-d'));
        // $this->db->where('assigned_staff!=', 0);
        $query = $this->db->get();
        $total_summary=$query->result();

        $response['reports']=$result;
        $response['total_summary']=$total_summary[0];
        return $response;
    }
    public function staff_active($staff_id){ 
            $today=date('Y-m-d');
            $this->db->where('staff_id', $staff_id);
            $this->db->where('date_format(date, "%Y-%m-%d") = "' . $today . '"');
            $this->db->get(db_prefix() . 'check_in_out');
            if ($this->db->affected_rows() > 0) {
                if($this->db->affected_rows()%2==0){
                    return 0;
                }
                else{
                    return 1;
                }                    
                }
            else{
                return 0;
            }
            
        }  

}