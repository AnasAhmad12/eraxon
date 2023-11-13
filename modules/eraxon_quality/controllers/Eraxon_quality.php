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
        $this->load->model('eraxon_quality_model');
    }

    public function qa_status()
    {
        if ($this->input->post()) 
        {
            if (!$this->input->post('id')) 
            {
                $data = array(
                    'name' => $this->input->post('qaname'),
                    'isactive' => $this->input->post('isactive'), 
                );
                $id = $this->eraxon_quality_model->add_qa_status($data);
                if ($id) {
                        set_alert('success', _l('added_successfully', "QA Status"));

                    }
            }else
            {
                $id = $this->input->post('id');
                $data = array(
                    'name' => $this->input->post('qaname'),
                    'isactive' => $this->input->post('isactive'), 
                );
                $success = $this->eraxon_quality_model->update_qa_status($data, $id);
                set_alert('success', _l('updated_successfully',"QA Status"));
            }

        }else{

            $data['qa_status'] = $this->eraxon_quality_model->get_qa_status();
            $this->load->view('eraxon_quality/qa_status', $data);
        }
    }

    public function delete_qa_status($id)
    {
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
        if ($this->input->post()) 
        {
            if (!$this->input->post('id')) 
            {
                $data = array(
                    'name' => $this->input->post('qaname'),
                    'isactive' => $this->input->post('isactive'), 
                );
                $id = $this->eraxon_quality_model->add_qar_status($data);
                if ($id) {
                        set_alert('success', _l('added_successfully', "QA Reviewer Status"));

                    }
            }else
            {
                $id = $this->input->post('id');
                $data = array(
                    'name' => $this->input->post('qaname'),
                    'isactive' => $this->input->post('isactive'), 
                );
                $success = $this->eraxon_quality_model->update_qar_status($data, $id);
                set_alert('success', _l('updated_successfully',"QA Reviewer Status"));
            }

        }else{

            $data['qa_status'] = $this->eraxon_quality_model->get_qar_status();
            $this->load->view('eraxon_quality/qa_reviewer_status', $data);
        }
       
    }

    public function delete_qar_status($id)
    {
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
        if($param == "add")
        {
            $data['campaign_types'] = $this->eraxon_quality_model->get_campaigns();
            $this->load->view('eraxon_quality/add_column',$data);

        }else if($param == "save")
        {
            if ($this->input->post()) 
            {
                if ($this->input->post('camp_type_id')) 
                {
                    $camp_type_id = $this->input->post('camp_type_id');
                    $columns = $this->input->post('columns');
                    $slugs = $this->input->post('slugs');

                    $data = array();
                    $counter = 0;
                    foreach($columns as $key => $col)
                    {
                        if($counter == 0)
                        {
                            $column = array(
                            'title'=> $col,
                            'data'=> $slugs[$key],
                            'type'=> 'date',
                            'dateFormat'=> 'YYYY-MM-DD',
                            'correctFormat'=> 'true',
                            );
                            $counter++;
                        }else
                        {
                             $column = array(
                            'title'=> $col,
                            'data'=> $slugs[$key],
                            'type'=> 'text',
                            );
                        }
                       
                    $data[] = $column;    
                    }
                    $this->eraxon_quality_model->add_columns($camp_type_id,$data);
                    echo json_encode($data);
                }
            }
            //redirect(admin_url('eraxon_quality/manage_column'));
        }else
        {
            $data['columns'] = $this->eraxon_quality_model->get_columns();
            $this->load->view('eraxon_quality/manage_column', $data);
        }
    }

    public function qa_set_column()
    {
        $data['lead_types'] = $this->eraxon_quality_model->get_lead_type();
        $this->load->view('eraxon_quality/manage_column', $data);
    }

    public function make_distribution_group()
    {
       $job_group_id =  hr_get_position_group_id_by_name('QA');
       $position_ids =  hr_get_list_job_position_by_jobgroupid($job_group_id);
       $staffid_with_daily_targets = hr_get_staff_ids_by_position_ids($position_ids);
       var_dump($staffid_with_daily_targets);
       $staffid_with_daily_targets  = json_encode($staffid_with_daily_targets);
       update_option('auto_distribution_staffid_with_daily_targets',$staffid_with_daily_targets);
       update_option('auto_distribution_changing_factor',1); // 60
       update_option('auto_distribution_pending_factor',0); // As per max pending leads value 
    }

    public function move_pointer(&$data,$current)
    {
        
        foreach($data as $key => &$d)
        {
            //var_dump($data['staff_id']);
            if($d['staff_id'] != $current && $d['check-in'] == 1)
            {
                $d['pointer'] = 'p';
                $data[$key] = $d;
                break;
            }
        }
        
    }

    public function check_distribution($data)
    {
        $changing_factor = get_option('auto_distribution_changing_factor');

        foreach($data as $cell)
        {
            if($cell['assigned'] <= $changing_factor)
            {
                return true;
            }
        }
        return false;
    }

    public function auto_distribution_leads()
    {
        //$CI = &get_instance();
        //$CI->load->model('eraxon_quality/eraxon_quality_model');
        $current_date = date('Y-m-d');
        $qc_staff = get_option('auto_distribution_staffid_with_daily_targets');
        $qc_staff = json_decode($qc_staff,1);
         var_dump($qc_staff);

        //check if staff is present or absent
        $qc_present_staff = $this->eraxon_quality_model->get_qa_present_staff($qc_staff,$current_date);
        var_dump($qc_present_staff);

        $unassigned_leads = $this->eraxon_quality_model->get_unassigned_leads($current_date);
        $changing_factor = get_option('auto_distribution_changing_factor'); // 60

        
       // $pending_factor = get_option('auto_distribution_pending_factor'); // As per max pending leads value
        var_dump($unassigned_leads);
        if(count($unassigned_leads) > 0)
        {

            foreach($unassigned_leads as $leads)
            {
              foreach($qc_present_staff as $key => $qc_available_staff)
                {
                    if($qc_available_staff['check-in'] == 1 && $qc_available_staff['pointer'] == 'p')
                    {
                        if($qc_available_staff['assigned'] <= $changing_factor)
                        {
                            $this->eraxon_quality_model->assigned_lead_to($qc_available_staff['staff_id'],$leads['id']);
                            $this->move_pointer($qc_present_staff,$qc_available_staff['staff_id']);
                            $qc_present_staff[$key]['pointer'] = '';
                            $qc_present_staff[$key]['assigned'] += 1;
                            //var_dump($qc_available_staff);
                            break;  
                        }else
                        {
                            
                            if($this->check_distribution($qc_present_staff))
                            {
                                $this->move_pointer($qc_present_staff,$qc_available_staff['staff_id']);
                                $qc_present_staff[$key]['pointer'] = '';

                            }else
                            {
                                $pending_factor = $this->eraxon_quality_model->get_number_of_pending_leads($qc_present_staff);
                                var_dump($pending_factor);

                                foreach($qc_present_staff as $key2 => $qc_available_staff2)
                                {
                                    //update_option('auto_distribution_pending_factor',$pending_factor);
                                    //check pending factor
                                   
                                    if($qc_available_staff2['pending'] < $pending_factor)
                                    {
                                        $this->eraxon_quality_model->assigned_lead_to($qc_available_staff2['staff_id'],$leads['id']);
                                        $this->move_pointer($qc_present_staff,$qc_available_staff2['staff_id']);
                                        $qc_present_staff[$key2]['pointer'] = '';
                                        $qc_present_staff[$key2]['assigned'] += 1;
                                        break;
                                    }else{
                                        $this->move_pointer($qc_present_staff,$qc_available_staff2['staff_id']);
                                        $qc_present_staff[$key2]['pointer'] = '';
                                            
                                    } 

                                }
                                break;
                            }
                        }                      
                    }
                }      
            }


            update_option('auto_distribution_staffid_with_daily_targets',json_encode($qc_present_staff)); 
        }
        
    }

}