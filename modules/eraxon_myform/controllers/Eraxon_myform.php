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
        $this->load->model('staff_model');
        $this->load->model('roles_model');
        $this->load->model('eraxon_wallet/eraxon_wallet_model');
       
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

                    if($data['status'] == 1)
                    {
                        $wallet_id = $this->eraxon_wallet_model->get_walletid_by_staff_id($data['id_staff']);

                        $transaction = array(
                        'wallet_id' => $wallet_id,
                        'amount_type' => 'Advance Salary',
                        'amount' => $data['amount'],
                        'in_out' => 'out',
                        'created_datetime' => date('Y-m-d H:i:s')

                        );

                        $tans_id = $this->eraxon_wallet_model->add_transaction($transaction);
                        $trans = array('trans_id'=> $tans_id);
                        $success = $this->eraxon_myform_model->update_advance_salary($trans, $id);

                        $followers_id = $data['id_staff'];
                        $subject = "Advance Salary is Approved. Please collect your amount.";
                        $link = 'eraxon_myform/advance_salary';

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

                          
                       

                    }else
                    {
                        $adv_data = $this->eraxon_myform_model->get_advance_salary($id);

                            if($adv_data->trans_id > 0 )
                            {
                                $condition = $this->eraxon_wallet_model->delete_transaction($adv_data->trans_id);
                                if($condition)
                                {
                                    $trans = array('trans_id'=> 0);
                                    $s = $this->eraxon_myform_model->update_advance_salary($trans, $id);
                                }
                            }
                            $status = '';

                            if($data['status'] == 0){ $status = 'pending'; }else{ $status = 'rejected'; }
                            $followers_id = $data['id_staff'];
                            $subject = "Advance Salary is ".$status;
                            $link = 'eraxon_myform/advance_salary';

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


    //Show All Docks
    public function manage_docks()
    {
        $data['all_docks'] = $this->eraxon_myform_model->get_all_docks();
        $data['title']   = 'Manage Docks';
        $this->load->view('eraxon_myform/admin_dock_manage',$data);
    }

    //add or update Dock
    public function add_dock()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            if (!$this->input->post('id')) {
               
                $id = $this->eraxon_myform_model->add_dock($data);

                if ($id) {
                    set_alert('success', _l('added_successfully', "Dock Data"));

                }
               
            } else {
                $id = $data['id'];
                unset($data['id']);
                $success = $this->eraxon_myform_model->update_dock($data, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully',"Dock Data"));
                }
            }
        }
    }

    public function delete_dock($id)
    {
        if (!$id) {
            redirect(admin_url('eraxon_myform/manage_docks'));
        }
        $response = $this->eraxon_myform_model->delete_dock($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('lead_source_lowercase')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', "Request deleted Successfully"));
        } else {
            set_alert('warning', _l('problem_deleting', _l('lead_source_lowercase')));
        }
        redirect(admin_url('eraxon_myform/manage_docks'));
    }

    public function team_lead_manage_dock()
    {
        $role_id = $this->roles_model->get_roleid_by_name('CSR');
        $data['staff_members'] = $this->staff_model->get('', ['active' => 1,'role' => $role_id]);
        $data['docks'] = $this->eraxon_myform_model->get_all_docks();

        $this->load->view('eraxon_myform/teamlead_dock_manage',$data);

    }

    public function team_lead_add_dock()
    {
        
        if ($this->input->post()) {
            $data = $this->input->post();
            if (!$this->input->post('id')) {
               $data['added_datetime'] = date('Y-m-d H:i:s');
                $id = $this->eraxon_myform_model->teamlead_add_dock($data);

                if ($id) {
                    set_alert('success', _l('added_successfully', "Dock Added"));

                    $wallet_id = $this->eraxon_wallet_model->get_walletid_by_staff_id($data['staff_id']);
                    $transaction = array(
                    'wallet_id' => $wallet_id,
                    'amount_type' => 'dock ('.$data['dock_name'].')',
                    'amount' => $data['dock_amount'],
                    'in_out' => 'out',
                    'created_datetime' => date('Y-m-d H:i:s'),
                    );
                   $tid =  $this->eraxon_wallet_model->add_transaction($transaction);
                    redirect(admin_url('eraxon_myform/team_lead_manage_dock'));
                }
               
                } else {
                $id = $data['id'];
                unset($data['id']);
                $success = $this->eraxon_myform_model->teamlead_update_dock($data, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully',"Dock Data"));
                }
            }

        }
    }

    public function team_lead_manage_all_dock()
    {
        
    }

    public function report_by_leads_staffs($id)
    {
        echo json_encode($this->eraxon_myform_model->report_by_leads_staffs($id));
    }

    public function report_by_day_leads_staffs($id)
    {
        echo json_encode($this->eraxon_myform_model->report_by_day_leads_staffs($id));
    }
  
    function cronjob_date_added() {
	
	    $this->load->model('timesheets/timesheets_model');
		$data = array('testcronjobdate'=> date('Y-m-d H:i:s'));
		$this->timesheets_model->testinsertcron($data);
		
	
	return;
	}
  
    public function update_leaves_type()
    {
        $result = $this->eraxon_myform_model->update_leave_type();

        echo $result;

    }

    
}