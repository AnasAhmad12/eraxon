<?php 
    $this->load->model('eraxon_wallet/eraxon_wallet_model');

    $month_year = date('Y-m');
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

    $walletid = $this->eraxon_wallet_model->get_walletid_by_staff_id($staffid);
    $my_transactions = $this->eraxon_wallet_model->get_transactions_by_walletid_daterange($walletid,$startDate,$endDate);
?>

<div class="row">
     <style type="text/css">
         .table-loading table thead tr{
            height: unset;
         }
     </style>   
       <input type="hidden" id="wallet_walletid" value="<?=$walletid ?>">
       <input type="hidden" id="wallet_staffid" value="<?=$staffid ?>">
       <div class="row filter_by">
        
        <div class="col-md-2 leads-filter-column">
          <?php echo render_input('month_timesheets','month',date('Y-m'), 'month'); ?>
        </div>
       
        <div class="col-md-1 mtop25">
          <button type="button" class="btn btn-info wallet_filter"><?php echo _l('filter'); ?></button>
        </div>                         
      </div>
      <?php 
            $pos_total = 0.0;
            $kiosk_total = 0.0;
            $dock_total = 0.0;
            $advance_total = 0.0;

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
                    $dock_total += $trans['amount'];
                }
            } 
      ?>
      <div class="row">
          <div class="col-md-12">
              <table class="table-bordered" width="100%">
                <tr>    
                  <th>Total POS Amount</th>
                  <th>Total KIOSK Amount</th>
                  <th>Total DOCK Amount</th>
                  <th>Total Advance Amount</th>
                </tr>
                <tr>
                    <td id="wallet-pos"><?=$pos_total ?></td>
                    <td id="wallet-kiosk"><?=$kiosk_total ?></td>
                    <td id="wallet-dock"><?=$dock_total ?></td>
                    <td id="wallet-advance"><?=$advance_total ?></td>
                </tr>
                
              </table>
          </div>
      </div>
      
     <div class="clearfix"></div>
     <br>
     <div id="wallet_table_div">
        <table class="table table-wallet dt-table" data-order-type="asc">
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Amount Type</th>
                    <th>Amount</th>
                    <th>Created Date / Time</th>
                </tr>     
            </thead>
            <tbody id="wallet_body">
                    <?php foreach($my_transactions as $trans){ 
                       $staffid = $this->eraxon_wallet_model->get_staffid_by_walletid($trans['wallet_id']);
                    ?>
                <tr>
                    <td><?=get_staff_full_name($staffid).' ('.get_custom_field_value($staffid,'staff_pseudo','staff',true).')' ?></td>    
                    <td><?=$trans['amount_type'] ?></td>
                <?php if($trans['in_out'] == 'in'){ ?>
                     <td style="color:#1bef15;"><?php echo '+'.number_format($trans['amount'],0,".",","); ?></td>
                     <?php }else{  ?>
                      <td style="color:#f21f1f;"><?php echo '-'.number_format($trans['amount'],0,".",","); ?></td>
                     <?php } ?>
                      <td><?=$trans['created_datetime'] ?></td>
                </tr>
                <?php } ?>
            </tbody>

        </table>
     </div>  
</div>

<script>
    

</script>