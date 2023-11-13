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

    public function add_campaign_columns($post_data)
    {
        $this->db->insert(db_prefix() . 'qa_campaign_column', $post_data);
        return $this->db->error();
    }

    public function add_orignal_lead_data($data, $id)
    {
        $this->db->where('camp_type_id', $data['lead_type']);
        $col_data = $this->db->get(db_prefix() . 'qa_campaign_column')->result_array();

        $columns = json_decode($col_data[0]['column'], true);

        $str = array();
        foreach ($columns as $col) {
            if ($col['data'] == 'date') {
                $str[] = array($col['data'] => date('Y-m-d'));

            } else if ($col['data'] == 'agent_name') {

                $str[] = array($col['data'] => $data['name']);

            } else if ($col['data'] == 'phone_number') {

                $str[] = array($col['data'] => $data['phonenumber']);
            } else {

                $str[] = array($col['data'] => '');

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

    public function get_leads($id, $date, $flag,$staff_id)
    {

        $campaign_sheet_lead = [];

        $this->db->where('lead_type', $id);
        $this->db->where('DATE(lead_date)', $date);
        $this->db->where('assigned_staff',$staff_id);
        
        
        if ($flag != 'all') {
            $this->db->where('added_sheet!=', 0);
        }

        $lead = $this->db->get(db_prefix() . 'qa_lead')->result();
        $this->db->where('lead_type', $id);
        $this->db->where('DATE(lead_date)', $date);
        $this->db->where('assigned_staff',$staff_id);
        
        $this->db->update(db_prefix().'qa_lead',['added_sheet'=>0]);
        foreach ($lead as $l) {

            if (has_permission('qa_person', '', 'view') && !is_admin()) {
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
                    'rejection_comments' => $l->rejection_comments
                ];
            }
            $col = json_decode($l->complete_lead, true);



            $combinedObject = [];

            $col[] = $newObjects;

            foreach ($col as $object) {
                $combinedObject = array_merge($combinedObject, $object);
            }
            array_splice($combinedObject, 0, 0, ['id' => $l->id]);
            array_push($campaign_sheet_lead, $combinedObject);
        }





        return $campaign_sheet_lead;

    }

    public function get_leads_new($id, $date)
    {

        $campaign_sheet_lead = [];


        $this->db->where('lead_type', $id);
        $this->db->where('added_sheet', 1);
        $this->db->where('DATE(lead_date)', $date);

        $lead = $this->db->get(db_prefix() . 'qa_lead')->result();
        foreach ($lead as $l) {

            if (has_permission('qa_person', '', 'view') && !is_admin()) {
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
                    'rejection_comments' => $l->rejection_comments
                ];
            }
            $col = json_decode($l->complete_lead, true);



            $combinedObject = [];

            $col[] = $newObjects;

            foreach ($col as $object) {
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

        foreach ($review as $a) {
            array_push($qa_review_status, $a->name);
        }
        foreach ($qa as $a) {
            array_push($qa_status, $a->name);
        }
        $data['qa_review_status'] = $qa_review_status;
        $data['qa_status'] = $qa_status;
        return $data;

    }

    public function update_complete_lead($col, $id)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'qa_lead', ["complete_lead" => json_encode($col)]);

        return $this->db->error();
    }
}