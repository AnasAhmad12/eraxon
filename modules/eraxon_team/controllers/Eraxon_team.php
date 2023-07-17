<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 
 */
class Eraxon_team extends AdminController
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('eraxon_team_model');
		$this->load->model('staff_model');
	}



	public function teams()
	{
		$data['staffs'] = $this->staff_model->get('','active = 1');
		$data['teams'] = $this->eraxon_team_model->get_teams();
		//$data['team'] = $this->eraxon_team_model->get_other_requests();
        //$data['title']   = 'Team';
        $this->load->view('eraxon_team/manage_team',$data);
	}

	public function add_edit_team($sid = '')
	{

		if ($this->input->post()) 
		{
			$teamname = $this->input->post('teamname');
			$staff = $this->input->post('staff');

			$staff = implode(',', $staff);

			 $data = array(
                'teamname' => $teamname,
                'team' => $staff,
                'created_datetime' => date('Y-m-d H:i:s') 
            );

			 $result = $this->eraxon_team_model->add_team($data);

			 if($result)
			 {
			 		set_alert('success', _l('added_successfully', "Request"));
			 }else{
			 		 echo json_encode(['success' => $result ? true : false, 'id' => $result]);
			 }

			 redirect(admin_url('eraxon_team/teams'));
		}
	
	}

}