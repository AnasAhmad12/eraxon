<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 
 */
class Eraxon_assets extends AdminController
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('staff_model');
        $this->load->model('roles_model');
	}

	public function index()
	{
		$this->load->view('index');
	}

	public function settings(){
		if(!has_permission('asset-setting','','view')){
			access_denied('Setting');
		}
		
		$role_id = $this->roles_model->get_roleid_by_name('CSR');
        $data['staff_members'] = $this->staff_model->get('', ['active' => 1]);
		$this->load->view('setting',$data);
	}

	public function set_settings(){

		$purchase=$this->input->post('purchase_approval');
		$loss=$this->input->post('loss_approval');

		update_option("stock_in_purchase_approval",$purchase);
		update_option("stock_loss_approval",$loss);

		set_alert('success', 'Settings Updated');
		redirect(admin_url('eraxon_assets/settings'));

	}
}