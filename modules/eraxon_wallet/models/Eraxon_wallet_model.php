<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Wallet model
 */
class Eraxon_wallet_model extends App_Model 
{

	public function __construct() {
      parent::__construct();
   }


   public function get_staff($role_id = '')
   {
        $this->db->select(db_prefix().'staff.*, jp.position_name, jp.position_id, jp.basic_salary, jp.basic_target');
        $this->db->from(db_prefix().'staff');
        $this->db->join(db_prefix().'hr_job_position as jp', db_prefix().'staff.job_position = jp.position_id', 'left');
        $this->db->where(db_prefix().'staff.active', 1);
        $this->db->where(db_prefix().'staff.admin', 0);
        if(!empty($role_id))
        {
         $this->db->where(db_prefix().'staff.role', $role_id);
        }
        //$this->db->where(db_prefix().'staff.role', $role_id);
        $query = $this->db->get();

        return $query->result_array();
   }

   public function create_wallet($data)
   {
       $this->db->insert(db_prefix() .'wallet', $data);
       return $this->db->insert_id();
   }

   public function update_wallet($id = '', $sid = '')
   {
      if (is_numeric($id))
      {
         $this->db->where('id',$id);
      }

      if(is_numeric($sid))
      {
         $this->db->where('staff_id',$sid);
      }
      $wallets = $this->db->get(db_prefix() .'wallet')->result_array();

      foreach($wallets as $wallet)
      {
         $this->db->where('wallet_id', $wallet['id']);
         $transactions = $this->db->get(db_prefix() .'wallet_transaction')->result_array();
         $total_amount = 0;
         foreach($transactions as $transaction)
         {  
            if($transaction['in_out'] == 'in')
            {
                  $total_amount += $transaction['amount'];

            }else if($transaction['in_out'] == 'out'){

                  $total_amount -= $transaction['amount'];
            }
         }
         $data = array(
                    'total_balance' => $total_amount,
                    'last_balance' => $wallet['total_balance'],
                    'status' => 1,
                    'updated_datetime' => date('Y-m-d H:i:s')
                );

        $this->db->where('id', $wallet['id']);
        $this->db->update(db_prefix() .'wallet', $data);
        
      }

      return true;
   }

   public function add_transaction($data)
   {
       $this->db->insert(db_prefix() .'wallet_transaction', $data);
       $trans = $this->db->insert_id();
       $this->update_wallet($data['wallet_id'],'');
       return $trans;
   }

   public function get_walletid_by_staff_id($staffid)
   {
      $this->db->where('staff_id', $staffid);
      return $this->db->get(db_prefix() .'wallet')->row()->id;
   }

   public function get_wallettotal_balance_by_staff_id($staffid)
   {
      $this->db->where('staff_id', $staffid);
      return $this->db->get(db_prefix() .'wallet')->row()->total_balance;
   }

   public function get_wallet_row_by_staff_id($staffid)
   {
      $this->db->where('staff_id', $staffid);
      return $this->db->get(db_prefix() .'wallet')->row();
   }

   public function get_staffid_by_walletid($wid)
   {
      $this->db->where('id', $wid);
      return $this->db->get(db_prefix() .'wallet')->row()->staff_id;
   }

   public function get_wallets()
   {
      if(has_permission('my_wallet','','view_own') && !is_admin())
      {
         $this->db->where('staff_id',get_staff_user_id());
      }

      return $this->db->get(db_prefix() .'wallet')->result_array();
   }

   public function get_transactions()
   {
      if(has_permission('wallet_transactions','','view_own') && !is_admin())
      {
         $wallet_id = $this->get_walletid_by_staff_id(get_staff_user_id());
         $this->db->where('wallet_id',$wallet_id);
         return $this->db->get(db_prefix() .'wallet_transaction')->result_array();
      }else if(is_admin()){

          return $this->db->get(db_prefix() .'wallet_transaction')->result_array();
      }
     
   }

   public function update_transaction($tid, $amount)
   {
      $this->db->where('id', $tid);
      $this->db->update(db_prefix() .'wallet_transaction',['amount'=>$amount]);

      $wallet_id = $this->db->where('id',$tid)->get(db_prefix() .'wallet_transaction')->row()->wallet_id;
      $this->update_wallet($wallet_id ,'');

   }

   public function delete_transaction($id)
   {
      $this->db->where('id', $id);
      $wallet_id = $this->db->get(db_prefix() . 'wallet_transaction')->row()->wallet_id;

      $this->db->where('id', $id);
      $this->db->delete(db_prefix() . 'wallet_transaction');
      if ($this->db->affected_rows() > 0) {
         $this->update_wallet($wallet_id,'');
         return true;
      }
      return false;
   }

   public function add_salary_deposit_into_wallet($slip_id)
   {
      $this->db->where('id', $slip_id);
      $salary_details_row= $this->db->get(db_prefix() .'salary_details')->row();

      $staff_id = $salary_details_row->employee_id;

      $this->db->where('staff_id',$staff_id);
      $wallet_id =  $this->db->get(db_prefix() .'wallet')->row()->id;

      $this->db->where('staffid', $staff_id);
      $job_position_id = $this->db->get(db_prefix().'staff')->row()->job_position;

      $this->db->where('position_id', $job_position_id);
      $basic_salary = $this->db->get(db_prefix().'hr_job_position')->row()->basic_salary;

      $transaction = array(
                    'wallet_id' => $wallet_id,
                    'amount_type' => 'Salary (Deposit into Wallet)',
                    'amount' => $basic_salary,
                    'in_out' => 'in',
                    'created_datetime' => date('Y-m-d H:i:s'),
                    );
      $transaction_id = $this->add_transaction($transaction);

      if($transaction_id)
      {
         $data = array('deposit_transaction_id' => $transaction_id);

         $this->db->where('id', $slip_id);
         $this->db->update(db_prefix() .'salary_details', $data);

         return $transaction_id;
      }else{
         return 0;
      }
      

   }

   public function add_deduct_transaction($slip_id,$transaction_id)
   {
      if($transaction_id)
      {
         $data = array('deduct_transaction_id' => $transaction_id);
         $this->db->where('id', $slip_id);
         $this->db->update(db_prefix() .'salary_details', $data);
      }
   }

   public function wallet_last_three_transactions($wid)
   {
      $this->db->where('wallet_id', $wid);
      $this->db->order_by('id', 'desc'); 
      $this->db->limit(3);
      return $this->db->get(db_prefix().'wallet_transaction')->result_array();
   }

    public function get_total_transactions_amount($staff_id,$from,$to)
    {
         $wallet_row =  $this->get_wallet_row_by_staff_id($staff_id);
         $wallet_id = $wallet_row->id;

         $sql = 'SELECT *
                 FROM ' . db_prefix() . 'wallet_transaction
                 WHERE DATE(created_datetime) BETWEEN "' . $from . '" AND "' . $to . '"
                 AND `wallet_id` = ' . $wallet_id ;

         $query  = $this->db->query($sql);   
         
         $transactions  = $query->result_array();

         $total_amount = 0;
         foreach($transactions as $transaction)
         {  
            /*if($transaction['in_out'] == 'in')
            {
                  $total_amount += $transaction['amount'];

            }else*/ if($transaction['in_out'] == 'out'){

                  $total_amount += $transaction['amount'];
            }
         }

         return $total_amount;

    }

   public function wallet_report($staff_id,$from,$to){

        $wallet_row =  $this->get_wallet_row_by_staff_id($staff_id);
        $wallet_id = $wallet_row->id;

        $qry = 'SELECT *
        FROM ' . db_prefix() . 'wallet_transaction
        WHERE DATE(created_datetime) BETWEEN "' . $from . '" AND "' . $to . '"
        AND `wallet_id` = ' . $wallet_id ;

        $query           = $this->db->query($qry);
        
        if(!$query){
            return  $this->db->error();
        }
        else{
            $array           = $query->result_array();

            return $array;
        }
       
    }

    public function wallet_exist($staff_id)
    {
       $this->db->where('staff_id', $staff_id);
       $query = $this->db->get(db_prefix().'wallet');
       if($query->num_rows() > 0 )
       {
         return true;
       }

       return false;

    }

}
