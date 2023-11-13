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
        $this->load->model('leads_model');
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

        return $this->db->get(db_prefix() . 'qa_campaign_column')->result();
    }

    public function get_campaigns()
    {
         $this->db->select("camp_type_id");
         $camp_done = $this->db->get(db_prefix().'qa_campaign_column')->result_array();

         if(count($camp_done) > 0 )
         {
            //$camp_done = array_values($camp_done);
            $camp_done = implode(",",$camp_done);
              $this->db->where_not_in('id', $camp_done);   
         }
         $camp_type = $this->db->get(db_prefix() . 'leads_type')->result_array();
         
         return $camp_type;
    }

    public function add_columns($type,$cols)
    {
        $data = array(
            'camp_type_id' => $type,
            'column' => json_encode($cols)
        );
        $this->db->insert(db_prefix() . 'qa_campaign_column', $data);
    }

    //event
    public function add_orignal_lead_data($data,$id)
    {        
        $this->db->where('camp_type_id',$data['lead_type']);
        $col_data = $this->db->get(db_prefix() . 'qa_campaign_column')->result_array();

        $columns = json_decode($col_data[0]['column'],true);

        $str = array();
        foreach($columns as $col)
        {
            if($col['data'] == 'date')
            {
                $str[] = array( $col['data']=>date('Y-m-d H:i:s') );

            }else if($col['data'] == 'agent_name'){

                $str[] = array( $col['data']=>$data['name']);

            }else if($col['data'] == 'phone_number'){

                $str[] = array( $col['data']=>$data['phonenumber'] );
            }else{

                $str[] = array( $col['data']=>'');

            }
            
        }

        $datam = array(
            'complete_lead' => json_encode($str),
            'lead_status' => 'pending',
            'qa_status' => 'pending',
            'review_status' => 'pending',
            'lead_type' => $data['lead_type'],
            'assigned_staff' => 0,
            'lead_id' => $id,
            'lead_date' =>date('Y-m-d H:i:s'),   
        );

        $this->db->insert(db_prefix() . 'qa_lead', $datam);

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

    public function get_qa_present_staff($qa_staffs,$today)
    {
        $available_staff = array();
        $counter = 1;
        foreach($qa_staffs as $key => $qa_staff)
        {
            $this->db->where('staff_id',$qa_staff['staff_id']);
            $this->db->where('date_format(date, "%Y-%m-%d") = "'.$today.'"');
            $this->db->get(db_prefix().'check_in_out');    
            if($this->db->affected_rows() > 0)
            {
                $qa_staffs[$key]['check-in'] = 1;
                if($counter == 1)
                {
                    $qa_staffs[$key]['pointer'] = "p";
                    $counter++; 
                }
                $available_staff [] = $qa_staffs[$key];
            }else
            {
                $qa_staffs[$key]['check-in'] = 0;
                $available_staff [] = $qa_staffs[$key];               
            }
        }

        return $available_staff;
    }

    public function get_unassigned_leads($today)
    {
        $result = $this->db->where("assigned_staff", 0)
                 //->where('DATE(lead_date)',$today)
                 ->get(db_prefix().'qa_lead')->result_array();
        return $result;         
    }

    public function assigned_lead_to($staffid, $qaleadid)
    {
        $this->db->where("id", $qaleadid);
        $this->db->update(db_prefix().'qa_lead',array('assigned_staff' => $staffid));
    }

    public function get_number_of_pending_leads(&$qa_staff)
    { 
        $max_pending = array();
        
        foreach($qa_staff as $key => &$staff)
        {
            $this->db->where('assigned_staff', $staff['staff_id']);
            $this->db->where('qa_status','pending');
            $this->db->get(db_prefix().'qa_lead');
            $pendings = $this->db->affected_rows();
            $max_pending[] = $pendings;

            $staff['pending'] = $pendings;
            $qa_staff[$key] = $staff;
        }
        update_option('auto_distribution_pending_factor',max($max_pending));
        return max($max_pending);
    }

}