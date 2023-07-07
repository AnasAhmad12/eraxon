<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 
 */
class eraxon_myform extends AdminController
{
	
	function __construct()
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
       // $data['advance_salary'] = $this->eraxon_myform_model->get_advance_salary();
        $data['title']   = 'Other Forms';
        $this->load->view('eraxon_myform/others_manage',$data);
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

                if (!$inline) {
                    if ($id) {
                        set_alert('success', _l('added_successfully', "Advance Salary"));
                    }
                } else {
                    echo json_encode(['success' => $id ? true : false, 'id' => $id]);
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
}