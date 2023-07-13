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
		//$data['team'] = $this->eraxon_team_model->get_other_requests();
        //$data['title']   = 'Team';
        $this->load->view('eraxon_team/manage_team',$data);
	}

	public function add_edit_team($sid = '')
	{
		
	}

}