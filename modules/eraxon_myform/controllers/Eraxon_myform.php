<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 
 */
class Eraxon_myform extends AdminController
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('eraxon_myform_model');
	}

	// public function index()
	// {
	// 	$this->load->view('manage');
	// }

    //Show All Leads
    public function others()
    {
        $data['other_requests'] = $this->eraxon_myform_model->get_other_requests();
        $data['title']   = 'Other Forms';
        $this->load->view('eraxon_myform/others_manage',$data);
    }

    //add and update
    public function other_form_ad()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            if (!$this->input->post('id')) {
                $data['requested_datetime'] = date('Y-m-d H:i:s'); 
                $data['status'] = 0; 
                $request_type = $this->input->post('request_type');
                $id = $this->eraxon_myform_model->add_other_request($data);

                
                    if ($id) {
                        set_alert('success', _l('added_successfully', "Request"));
                         $followers_id = get_option('selected_hr_for_notification');
                        $staffid = get_current_user() ;
                        $subject = "Requested for ".$request_type;
                        $link = 'eraxon_myform/others';

                        if ($followers_id != '') {
                            if ($staffid != $followers_id) {
                                $notification_data = [
                                    'description' => $subject,
                                    'touserid' => $followers_id,
                                    'link' => $link,
                                ];

                                $notification_data['additional_data'] = serialize([
                                    $subject,
                                ]);

                                if (add_notification($notification_data)) {
                                    pusher_trigger_notification([$followers_id]);
                                }

                            }
                        }
                    }
                
            } else {
                $id = $data['id'];
                unset($data['id']);
                $success = $this->eraxon_myform_model->update_other_reuqest($data, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully',"Request"));
                }
            }
        }
    }

    public function delete_or($id)
    {
        if (!is_admin()) {
            access_denied('Delete Request');
        }
        if (!$id) {
            redirect(admin_url('eraxon_myform/others'));
        }
        $response = $this->eraxon_myform_model->delete_or($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('lead_source_lowercase')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', "Request deleted Successfully"));
        } else {
            set_alert('warning', _l('problem_deleting', _l('lead_source_lowercase')));
        }
        redirect(admin_url('eraxon_myform/others'));
    }

	//Show All Leads
	public function advance_salary()
	{
		$data['advance_salary'] = $this->eraxon_myform_model->get_advance_salary();
        $data['title']   = 'Advance Salary';
		$this->load->view('eraxon_myform/advance_salary_manage',$data);
	}

	//add and update
	public function advance_salary_ad()
	{
		if ($this->input->post()) {
            $data = $this->input->post();
            if (!$this->input->post('id')) {
                $data['requested_datetime'] = date('Y-m-d H:i:s'); 
                $data['status'] = 0; 
                $id = $this->eraxon_myform_model->add_advance_salary($data);

              
                    if ($id) {
                        set_alert('success', _l('added_successfully', "Advance Salary"));

                        $followers_id = get_option('selected_hr_for_notification');
                        $staffid = get_current_user() ;
                        $subject = "Requested an advance salary";
                        $link = 'eraxon_myform/advance_salary';

                        if ($followers_id != '') {
                            if ($staffid != $followers_id) {
                                $notification_data = [
                                    'description' => $subject,
                                    'touserid' => $followers_id,
                                    'link' => $link,
                                ];

                                $notification_data['additional_data'] = serialize([
                                    $subject,
                                ]);

                                if (add_notification($notification_data)) {
                                    pusher_trigger_notification([$followers_id]);
                                }

                            }
                        }
                    }
               
            } else {
                $id = $data['id'];
                unset($data['id']);
                $success = $this->eraxon_myform_model->update_advance_salary($data, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully',"Advance Salary"));
                }
            }
        }
	}

	public function delete_as($id)
    {
        if (!is_admin()) {
            access_denied('Delete Advance Salary Request');
        }
        if (!$id) {
            redirect(admin_url('eraxon_myform/advance_salary'));
        }
        $response = $this->eraxon_myform_model->delete_as($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('lead_source_lowercase')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', "Request deleted Successfully"));
        } else {
            set_alert('warning', _l('problem_deleting', _l('lead_source_lowercase')));
        }
        redirect(admin_url('eraxon_myform/advance_salary'));
    }

    public function report_by_leads_staffs($id)
    {
        echo json_encode($this->eraxon_myform_model->report_by_leads_staffs($id));
    }

    public function report_by_day_leads_staffs($id)
    {
        echo json_encode($this->eraxon_myform_model->report_by_day_leads_staffs($id));
    }
}