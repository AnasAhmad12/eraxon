<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 
 */
class Eraxon_wallet extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        //$this->load->model('eraxon_payroll_model');
        $this->load->model('eraxon_wallet_model');
       // $this->load->model('staff_model');
       // $this->load->model('timesheets/timesheets_model');
    }

    //wallet module
    public function create_wallet()
    {
        $all_staff = $this->eraxon_wallet_model->get_staff();

        foreach($all_staff as $staff)
        {
            $data = array(

                    'staff_id' => $staff['staffid'],
                    'total_balance' => $staff['basic_salary'],
                    'last_balance' => 0.0,
                    'status' => 1,
                    'updated_datetime' => date('Y-m-d H:i:s')
                );
            $wallet_id = $this->eraxon_wallet_model->create_wallet($data);
            $transaction = array(
                    'wallet_id' => $wallet_id,
                    'amount_type' => 'salary',
                    'amount' => $staff['basic_salary'],
                    'in_out' => 'in',
                    'created_datetime' => date('Y-m-d H:i:s')

            );

            $tans_id = $this->eraxon_wallet_model->add_transaction($transaction);
        }
    }


    public function my_wallet()
    {
        $data['my_wallet'] = $this->eraxon_wallet_model->get_wallets();
        $data['my_transactions'] = $this->eraxon_wallet_model->get_transactions();
        $this->load->view('eraxon_wallet/manage_my_wallet',$data);
    }

    public function delete_transaction($tid)
    {
        $res = $this->eraxon_wallet_model->delete_transaction($tid);

        if($res) {
            $message = "transaction deleted successfully";
            set_alert('success', $message);
        } else {
            $message = "Failed to delete transaction";
            set_alert('error', $message);
        }

        redirect(admin_url("eraxon_wallet/my_wallet"));
    }

    public function update_all_wallet()
    {
        $update = $this->eraxon_wallet_model->update_wallet('','');
        redirect(admin_url("eraxon_wallet/my_wallet"));
    }


}