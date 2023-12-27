<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 
 */
class Eraxon_quality extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('eraxon_quality_model', );
        $this->load->model('staff_model');
        $this->load->model('misc_model');
    }
  
  
 	

    public function qa_status()
    {
        if (!has_permission('qa_department', '', 'qa_manager')) {
            access_denied('QA Status');
        }
        if ($this->input->post()) {
            if (!$this->input->post('id')) {
                $data = array(
                    'name' => $this->input->post('qaname'),
                    'isactive' => $this->input->post('isactive'),
                );
                $id = $this->eraxon_quality_model->add_qa_status($data);
                if ($id) {
                    set_alert('success', _l('added_successfully', "QA Status"));

                }
            } else {
                $id = $this->input->post('id');
                $data = array(
                    'name' => $this->input->post('qaname'),
                    'isactive' => $this->input->post('isactive'),
                );
                $success = $this->eraxon_quality_model->update_qa_status($data, $id);
                set_alert('success', _l('updated_successfully', "QA Status"));
            }

        } else {

            $data['qa_status'] = $this->eraxon_quality_model->get_qa_status();
            $this->load->view('eraxon_quality/qa_status', $data);
        }
    }

    public function delete_qa_status($id)
    {
        if (!has_permission('qa_department', '', 'qa_manager')) {
            access_denied('QA Status');
        }

        $response = $this->eraxon_quality_model->delete_qa_status($id);
        if ($response == true) {
            set_alert('success', _l('deleted', "QA status deleted"));
        } else {
            set_alert('warning', _l('problem_deleting', 'Problem in deleting'));
        }
        redirect(admin_url('eraxon_quality/qa_status'));
    }


    public function qa_reviewer_status()
    {
        if (!has_permission('qa_department', '', 'qa_manager')) {
            access_denied('QA Reviewer Status');
        }

        if ($this->input->post()) {
            if (!$this->input->post('id')) {
                $data = array(
                    'name' => $this->input->post('qaname'),
                    'isactive' => $this->input->post('isactive'),
                );
                $id = $this->eraxon_quality_model->add_qar_status($data);
                if ($id) {
                    set_alert('success', _l('added_successfully', "QA Reviewer Status"));

                }
            } else {
                $id = $this->input->post('id');
                $data = array(
                    'name' => $this->input->post('qaname'),
                    'isactive' => $this->input->post('isactive'),
                );
                $success = $this->eraxon_quality_model->update_qar_status($data, $id);
                set_alert('success', _l('updated_successfully', "QA Reviewer Status"));
            }

        } else {

            $data['qa_status'] = $this->eraxon_quality_model->get_qar_status();
            $this->load->view('eraxon_quality/qa_reviewer_status', $data);
        }

    }

    public function delete_qar_status($id)
    {
        if (!has_permission('qa_department', '', 'qa_manager')) {
            access_denied('QA Status');
        }

        $response = $this->eraxon_quality_model->delete_qar_status($id);

        if ($response == true) {
            set_alert('success', _l('deleted', "QA Reviewer status deleted"));
        } else {
            set_alert('warning', _l('problem_deleting', 'Problem in deleting'));
        }
        redirect(admin_url('eraxon_quality/qa_reviewer_status'));
    }

    public function manage_column($param = "")
    {
        if (!has_permission('qa_department', '', 'qa_manager')) {
            access_denied('Campaign Columns');
        }

        if ($param == "add") {
            $data['campaign_types'] = $this->eraxon_quality_model->get_campaigns();
            $this->load->view('eraxon_quality/add_column', $data);

        } else if ($param == "save") {
            if ($this->input->post()) {
                if ($this->input->post('camp_type_id')) {
                    $camp_type_id = $this->input->post('camp_type_id');
                    $columns = $this->input->post('columns');
                    $slugs = $this->input->post('slugs');
                  	$default=$this->input->post('default');

                    $data = array();
                    $counter = 0;
                    foreach ($columns as $key => $col) {
                        if ($counter == 0) {
                            $column = array(
                                'title' => $col,
                                'data' => $slugs[$key],
                                'type' => 'date',
                                'dateFormat' => 'YYYY-MM-DD',
                                'correctFormat' => 'true',
                            );
                            $counter++;
                        } else {
                            $column = array(
                                'title' => $col,
                                'data' => $slugs[$key],
                              	'default'=>$default[$key],
                                'type' => 'text',
                            );
                        }

                        $data[] = $column;
                    }

                    $post_data = array(
                        "camp_type_id" => $camp_type_id,
                        "column" => json_encode($data)
                    );


                    $response = $this->eraxon_quality_model->add_campaign_columns($post_data);
                    $response = [
                        'url' => admin_url('eraxon_quality/manage_column'),
                        "status" => 1
                    ];
                    echo json_encode($response);


                    var_dump($this->eraxon_quality_model->get_campaigns());

                }
            }
        } else {
            $data['columns'] = $this->eraxon_quality_model->get_columns();
            $this->load->view('eraxon_quality/manage_column', $data);
        }
    }


    public function edit_column($id)
    {
        if (!has_permission('qa_department', '', 'qa_manager')) {
            access_denied('QA Campaign Columns');
        }
        $data = $this->eraxon_quality_model->get_campaign_columns_by_id($id);

        $this->load->view('eraxon_quality/edit_column', $data);
    }

    public function delete_column($id)
    {
        $this->eraxon_quality_model->delete_campaign($id);
        redirect(admin_url('eraxon_quality/manage_column'));
    }

    public function qa_set_column()
    {
        if (!has_permission('qa_department', '', 'qa_manager')) {
            access_denied('QA Status');
        }
        $data['lead_types'] = $this->eraxon_quality_model->get_lead_type();
        $this->load->view('eraxon_quality/manage_column', $data);
    }

    public function get_campaign_sheet($id)
    {
        if (!has_permission('qa_department', '', 'qa_manager') && !has_permission('qa_department', '', 'qa_person') && !has_permission('qa_department', '', 'qa_reviewer')) {
            access_denied('QA Sheet');
        }

        if ($this->input->get()) {
            $flag = $this->input->get('flag');
            $date = $this->input->get('date');
            $start_date=$this->input->get('start_date');
            $end_date=$this->input->get('end_date');
            $keyword=$this->input->get('searchKey');
            $staff_id = get_staff_user_id();

            if ($flag == "all") {
                $camp_col = $this->eraxon_quality_model->get_campaign_columns($id);
                $status_col = $this->eraxon_quality_model->get_status_col();
                $data['camp_col'] = $camp_col;
                $data['status_col'] = $status_col;
            }
            $leads = $this->eraxon_quality_model->get_leads($id, $start_date,$end_date, $flag, $staff_id,$keyword);

            $data['new_leads'] = $this->eraxon_quality_model->get_new_leads();

            $data['leads'] = $leads;
            $data['id'] = $id;
            $data['flag'] = $flag;
            echo json_encode($data);
            return 0;
        }

        $data['id'] = $id;
        $this->load->view('eraxon_quality/campaign_sheet', $data);
    }

    public function get_leads_unassigned()
    {
         if (!has_permission('qa_department', '', 'qa_manager')) {
            access_denied('QA Sheet');
        }
        $flag = $this->input->get('flag');
        $type= $this->input->get('type');
        $start_date=$this->input->get('start_date');
        $end_date=$this->input->get('end_date');
        $data['u_leads'] = $this->eraxon_quality_model->get_unassinged_leads_sheet($flag,$type,$start_date,$end_date);
        echo json_encode($data);
        return 0;

    }

    public function all_campaign_sheet()
    {
        if (!has_permission('qa_department', '', 'qa_manager') && !has_permission('qa_department', '', 'qa_person') && !has_permission('qa_department', '', 'qa_reviewer')) {
            access_denied('QA Sheet');
        }

        $campaigns['campaigns'] = $this->eraxon_quality_model->get_campaigns_with_col();
        $campaigns['new_leads'] = $this->eraxon_quality_model->get_new_leads();

        return $this->load->view('eraxon_quality/sheet', $campaigns);
    }

    public function manager_sheet()
    {
        if (!has_permission('qa_department', '', 'qa_manager')) {
            access_denied('QA Manager Sheet');
        }
      
        if($this->input->get()){

            $flag = $this->input->get('flag');
            $id=$this->input->get('id');
    
            if ($flag == 'all') {
                $columns= $this->eraxon_quality_model->get_campaign_columns($id);

                $data['columns']=$columns;

                echo json_encode($data);
                // if ($this->input->is_ajax_request()) {
                //     $this->app->get_table_data(module_views_path('eraxon_quality', 'tables/manager_sheet_table'));
                // }
            }
        }
        else {
            $campaigns['campaigns'] = $this->eraxon_quality_model->get_campaigns_with_col();
            return $this->load->view('eraxon_quality/manager_sheet_table', $campaigns);
        }


    }
  
   public function update_columns(){

        if($this->input->post()){
            if ($this->input->post('camp_type_id')) {
                $camp_type_id = $this->input->post('camp_type_id');
                $columns = $this->input->post('columns');
                $slugs = $this->input->post('slugs');
                $default = $this->input->post('default');

              
                $data = array();
                $counter = 0;
                foreach ($columns as $key => $col) {
                    if ($counter == 0) {
                        $column = array(
                            'title' => $col,
                            'data' => $slugs[$key],
                            'type' => 'date',
                            'dateFormat' => 'YYYY-MM-DD',
                            'correctFormat' => 'true',
                        );
                        $counter++;
                    } else {
                        $column = array(
                            'title' => $col,
                            'data' => $slugs[$key],
                          	'default'=>$default[$key],
                            'type' => 'text',
                        );
                    }
    
                    $data[] = $column;
                }
    
                $post_data = array(
                    "column" => json_encode($data)
                );
              
               $response=$this->eraxon_quality_model->edit_column($camp_type_id,$post_data);
               $response = [
                    'url' => admin_url('eraxon_quality/manage_column'),
                    "status" => 1
                ];
                echo json_encode($response);
    
            }
           }
    }


     public function update_qa_lead()
    {
        $complete_lead = $this->input->post('data');
        $status = $this->input->post('status');
      	$qa_lead_rating=$this->input->post('qa_rating');
        $lead_uploaded=$this->input->post('lead_uploaded');
      	
        if (has_permission('qa_department', '', 'qa_person') && !is_admin()) {
            $status = array('qa_status' => $status['qa_status'], 'forwardable_comments' => $status['forward_comments'], 'qa_comments' => $status['qa_comments']);
        } else {
            if ($this->input->post('status')['qa'] == 'pending') {
                $status = array('qa_status' => $status['qa'], 'review_status' => $status['reviewer_status'], 'forwardable_comments' => $status['forward_comments'], 'qa_comments' => $status['qa_comments'], 'rejection_comments' => $status['rejection_comments'], 'added_sheet' => 0, 'added_sheet_reviewer' => 0);
            } else {
                $status = array('qa_status' => $status['qa'], 'review_status' => $status['reviewer_status'], 'forwardable_comments' => $status['forward_comments'], 'qa_comments' => $status['qa_comments'], 'rejection_comments' => $status['rejection_comments'],'qa_lead_rating'=>$qa_lead_rating,'lead_uploaded'=>$lead_uploaded);
            }
        }

        $id = $this->input->post('id');
        $response = $this->eraxon_quality_model->update_complete_lead($complete_lead, $id, $status);
        echo json_encode($status);
    }


    public function save_settings()
    {
        if (!has_permission('qa_department', '', 'qa_manager')) {
            access_denied('QA Sheet');
        }
        $distribution = $this->input->post('distribution');
        update_option('auto_distribution_manual_automatic_check', $distribution);
        redirect(admin_url('eraxon_quality/settings'));
    }

    public function distribute_leads()
    {
        if (!has_permission('qa_department', '', 'qa_manager')) {
            access_denied('QA Sheet');
        }
        $data['staffs'] = $this->staff_model->get('', ['role' => 11, 'active' => 1]);
        return $this->load->view('lead_distribution', $data);
    }

    public function assign_distribution()
    {
        $id = $this->input->post('id');
        $staff_id = $this->input->post('assigned_staff');
        $response = $this->eraxon_quality_model->assign_distribution($id, $staff_id);
        echo json_encode($response);
    }

   

    public function lead_approve()
    {
        $date = $this->input->post('date');
      // Assuming $date is in a format like 'Y-m-d'
$startOfDay = date('Y-m-d 00:00:00', strtotime($date));

$this->db->where('lead_date >=', $startOfDay);


        $this->db->where('qa_done!=', 1);
        $leads = $this->db->get(db_prefix() . 'qa_lead')->result();

        foreach ($leads as $l) {
            if ($l->review_status == 'approved') {
                $this->db->where('id', $l->lead_id);
                $this->db->update(db_prefix() . 'leads', ['status' => 3]);

                $this->db->where('id', $l->lead_id);
                $c_lead = $this->db->get(db_prefix() . 'leads')->result()[0];

                add_notification([
                    'description' => "Lead Approved",
                    'touserid' => $c_lead->addedfrom,
                    'link' => admin_url('leads'),
                ]);

            } elseif ($l->review_status == 'reject') {
                $this->db->where('id', $l->lead_id);
                $this->db->update(db_prefix() . 'leads', ['status' => 4, 'description' => $l->rejection_comments]);
                $this->misc_model->add_note(["description" => $l->rejection_comments], 'lead', $l->lead_id);
                $this->db->where('id', $l->lead_id);
                $c_lead = $this->db->get(db_prefix() . 'leads')->result()[0];
                add_notification([
                    'description' => "Lead Rejected",
                    'touserid' => $c_lead->addedfrom,
                    'link' => admin_url('leads'),
                ]);

            }

            if ($l->review_status == 'approved' || $l->review_status == 'reject') {
                $this->db->where('id', $l->id);
                $this->db->update(db_prefix() . 'qa_lead', ["qa_done" => 1]);
            }
        }
        set_alert('success', "Leads Statuses Updated");

        redirect(admin_url('eraxon_quality/settings'));
    }

    public function settings()
    {
        $data['staffs'] = $this->staff_model->get('', ['role' => 11, 'active' => 1]);
        $this->load->view('eraxon_quality/settings', $data);
    }
  
   public function report_by_day_leads_staffs()
    {
        echo json_encode($this->eraxon_quality_model->report_by_day_leads_staffs());
    }
  
      public function qa_reports(){
        $reports=$this->eraxon_quality_model->get_reports();   
        // var_dump( $reports);
        // return 0;
        return $this->load->view('qa_reports',$reports);

    }

    public function report_by_leads_qa()
    {
        echo json_encode($this->eraxon_quality_model->report_by_leads_staffs());
    }
  
    public function make_distribution_group()
    {
       // $current_hr = date('G'); //6
       // $run_hour = get_option('auto_quality_distribution_group');
      
       //if($current_hr==$run_hour)
       // {
          
            $job_group_id =  hr_get_position_group_id_by_name('QA');
            $position_ids =  hr_get_list_job_position_by_jobgroupid($job_group_id);
            $staffid_with_daily_targets = hr_get_staff_ids_by_position_ids($position_ids);
            $staffid_with_daily_targets  = json_encode($staffid_with_daily_targets);
            update_option('auto_distribution_staffid_with_daily_targets',$staffid_with_daily_targets);
            update_option('auto_distribution_pending_factor',0); // As per max pending leads value 
            //update_option('auto_quality_distribution_group','qwdsdqwqw');
      // }
       
    }
  
    public function qa_daily_target()
    {
        if($this->input->post())
        {
            $target = $this->input->post('target');
            $sid = $this->input->post('sid');
            $fix = $this->input->post('fix');
            $qa_staff  = get_option('auto_distribution_staffid_with_daily_targets');
            $qa_staff = json_decode($qa_staff,1);

            foreach($qa_staff as $key => $staff)
            {
                if($staff['staff_id'] == $sid[$key])
                {
                    $qa_staff[$key]['fix'] = (int)$fix[$key];
                    $qa_staff[$key]['daily_target'] = (int)$target[$key];
                }
            }

            update_option('auto_distribution_staffid_with_daily_targets',json_encode($qa_staff));
            redirect(admin_url('eraxon_quality/settings'));
        }
    }
  
  
  public function update_qa_table(){ 
        $qa_leads=$this->db->get(db_prefix().'qa_lead')->result();
        foreach($qa_leads as $qa){
            $this->db->where('id',$qa->lead_id);
            $lead=$this->db->get(db_prefix()."leads")->result();
            var_dump($lead);
            if($lead){
                $this->db->where('lead_id',$lead[0]->id);
                $this->db->update(db_prefix().'qa_lead',["phonenumber"=>$lead[0]->phonenumber]);
            }

        }
        // var_dump($qa_leads);
    }

}