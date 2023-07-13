<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Leadboard extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('staff_model');
	}

    public function index()
    {
        $data['terms'] = get_option('terms_and_conditions');
        $data['title'] = _l('terms_and_conditions') . ' - ' . get_option('companyname');
        $this->data($data);
        $this->view('terms_and_conditions');
        $this->layout();
    }


    public function allstaffleads()
    {
    	$data['staffs'] = $data['staffs'] = $this->staff_model->get('','active = 1');
        $data['title'] = 'All Staf Lead Board' . ' - ' . get_option('companyname');
        $this->data($data);
        $this->view('leadboard');
        $this->layout();
    }
}
