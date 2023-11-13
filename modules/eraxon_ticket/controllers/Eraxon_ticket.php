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
	}

	public function index($status = '', $userid = '')
    {
        close_setup_menu();

        if (!is_numeric($status)) {
            $status = '';
        }

        if ($this->input->is_ajax_request()) {
            if (!$this->input->post('filters_ticket_id')) {
                $tableParams = [
                    'status' => $status,
                    'userid' => $userid,
                ];
            } else {
                // request for othes tickets when single ticket is opened
                $tableParams = [
                'userid'              => $this->input->post('filters_userid'),
                'where_not_ticket_id' => $this->input->post('filters_ticket_id'),
            ];
                if ($tableParams['userid'] == 0) {
                    unset($tableParams['userid']);
                    $tableParams['by_email'] = $this->input->post('filters_email');
                }
            }

            $this->app->get_table_data('tickets', $tableParams);
        }

        $data['chosen_ticket_status']              = $status;
        $data['weekly_tickets_opening_statistics'] = json_encode($this->tickets_model->get_weekly_tickets_opening_statistics());
        $data['title']                             = _l('support_tickets');
        $this->load->model('departments_model');
        $data['statuses']             = $this->tickets_model->get_ticket_status();
        $data['staff_deparments_ids'] = $this->departments_model->get_staff_departments(get_staff_user_id(), true);
        $data['departments']          = $this->departments_model->get();
        $data['priorities']           = $this->tickets_model->get_priority();
        $data['services']             = $this->tickets_model->get_service();
        $data['ticket_assignees']     = $this->tickets_model->get_tickets_assignes_disctinct();
        $data['bodyclass']            = 'tickets-page';
        add_admin_tickets_js_assets();
        $data['default_tickets_list_statuses'] = hooks()->apply_filters('default_tickets_list_statuses', [1, 2, 4]);
        $this->load->view('admin/tickets/list', $data);
    }

}