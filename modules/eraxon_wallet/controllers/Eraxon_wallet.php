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

     public function new_create_wallet()
    {
        $all_staff = $this->eraxon_wallet_model->get_staff();

        foreach($all_staff as $staff)
        {
            $wallet_exist = $this->eraxon_wallet_model->wallet_exist($staff['staffid']);

            if(!$wallet_exist)
            {
                $data = array(

                    'staff_id' => $staff['staffid'],
                    'total_balance' => 0.0,
                    'last_balance' => 0.0,
                    'status' => 1,
                    'updated_datetime' => date('Y-m-d H:i:s')
                );
               $wallet_id = $this->eraxon_wallet_model->create_wallet($data);
            }
            
            /*$transaction = array(
                    'wallet_id' => $wallet_id,
                    'amount_type' => 'salary',
                    'amount' => $staff['basic_salary'],
                    'in_out' => 'in',
                    'created_datetime' => date('Y-m-d H:i:s')

            );

            $tans_id = $this->eraxon_wallet_model->add_transaction($transaction);*/
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

    public function wallet_reporting()
    {
        if ($this->input->post()) 
        {
            $posted_data       = $this->input->post();
            $staff_id          = $posted_data['staff_id'];
            $from_date         = $posted_data['from'];
            $to_date           = $posted_data['to'];
            $from_date_st      = strtotime($from_date);
            $to_date_st        = strtotime($to_date);

            if ($from_date_st > $to_date_st) {
            $return_data['status'] = 'error';
            } else {
                $return_data['data'] = $this->eraxon_wallet_model->wallet_report($staff_id, $from_date, $to_date);
                $return_data['total_transaction_amount'] = $this->eraxon_wallet_model->get_total_transactions_amount($staff_id, $from_date, $to_date);
            }
            echo json_encode($return_data);
        }else{
        $data['all_staff'] = $this->eraxon_wallet_model->get_staff(6);
        $this->load->view('eraxon_wallet/reporting_my_wallet',$data);
        }
    }
    
    public function reload_wallet_byfilter()
    {
        $data = $this->input->post();

        $month_year = $data['month'];
        $walletid = $data['walletid'];
        $staffid = $data['staffid'];

        // Create DateTime object for the first day of the selected month
        $selected_month = DateTime::createFromFormat('Y-m', $month_year);
        // Create start and end dates
        $startDate = clone $selected_month;
        $endDate = clone $selected_month;
        $endDateWallet = clone $selected_month;
        // Modify start date: go to previous month and set day to 21
        $startDate->modify('-1 month');
        $startDate->setDate($startDate->format('Y'), $startDate->format('m'), 21);

        // Modify end date: set day to 20
        $endDate->setDate($endDate->format('Y'), $endDate->format('m'), 20);
        // $data_hs = $this->set_col_tk(21, 30, 06, 2023, true,[3],'');
        $endDateWallet->setDate($endDate->format('Y'), $endDate->format('m'), 20);
            
        $startDate = $startDate->format('Y-m-d');
        $endDate = $endDate->format('Y-m-d');

        $my_transactions = $this->eraxon_wallet_model->get_transactions_by_walletid_daterange($walletid,$startDate,$endDate);

        $pos_total = 0.0;
        $kiosk_total = 0.0;
        $dock_total = 0.0;
        $advance_total = 0.0;

        $html = '<table class="table table-wallet dt-table"><thead>
                <tr>
                    <th>Full Name</th>
                    <th>Amount Type</th>
                    <th>Amount</th>
                    <th>Created Date / Time</th>
                </tr>     
            </thead><tbody>';
        foreach($my_transactions as $trans)
        { 
            if(str_contains($trans['amount_type'],'POS'))
                {
                    $pos_total += $trans['amount'];

                }else if(str_contains($trans['amount_type'],'KIOSK'))
                {
                    $kiosk_total += $trans['amount'];

                }else if(str_contains($trans['amount_type'],'dock'))
                {
                    $dock_total += $trans['amount'];

                }else if(str_contains($trans['amount_type'],'Advance'))
                {
                    $advance_total += $trans['amount'];
                }

            $html .='<tr><td>'.get_staff_full_name($staffid).' ('.get_custom_field_value($staffid,'staff_pseudo','staff',true).')</td><td>'.$trans['amount_type'].'</td>';

            if($trans['in_out'] == 'in')
            {
                 $html .='<td style="color:#1bef15;">+'.number_format($trans['amount'],0,".",",").'</td>';
            }else
            {
                $html .='<td style="color:#f21f1f;">-'.number_format($trans['amount'],0,".",",").'</td>';
            }

            $html .='<td>'.$trans['created_datetime'].'</td></tr>';

        }
        $html .='</tbody></table>';
        
        $data['pos_total'] = $pos_total;
        $data['kiosk_total'] = $kiosk_total;
        $data['dock_total'] = $dock_total;
        $data['advance_total'] = $advance_total;

        $data['html'] = $html;

        echo json_encode($data);
    }


}