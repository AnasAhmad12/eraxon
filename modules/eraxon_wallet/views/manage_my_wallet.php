<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                  <div class="panel-body">
                    <div class="row">
                      <div class="col-md-4 mt-5">
                        <h4><i class=" fa fa-wallet"></i> My Wallet </h4>
                      </div> 
                      <?php if(has_permission('my_wallet','','view_own') && !is_admin())
                        { ?>
                      <div class="col-md-4 mt-5">
                        <h4>Current Balance: Rs.<?php echo number_format($my_wallet[0]['total_balance'],0,".",","); ?>/- </h4>
                      </div>
                      <?php } ?>

                       <?php if(has_permission('hr_permission','','view') || is_admin())
                        { ?>
                      <div class="col-md-4 mt-5">
                        <a href="<?php echo admin_url('eraxon_wallet/update_all_wallet'); ?>"><button class="btn btn-primary">Update All Wallets</button></a>
                      </div>
                      <?php } ?>
                    </div>
                    <div class="clearfix"></div>
                    <table class="table dt-table" data-order-type="asc">
                        <thead>
                        <tr>
                            <th>Full Name</th>
                            <th>Amount Type</th>
                            <th>Amount</th>
                            <th>Created Date / Time</th>
                            <?php  if(has_permission('hr_permission','','view') || is_admin()){ ?>   
                            <th>Action</th>
                        <?php } ?>
                        </tr>     
                        </thead>
                        <tbody>
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
                            <?php  if(has_permission('hr_permission','','view') || is_admin()){ ?>
                             
                            <td>
                                <a href="<?php echo admin_url('eraxon_wallet/delete_transaction/').$trans['id'] ?>">Delete</a>
                            </td>
                            <?php } ?>
                            </tr>
                            <?php } ?>
                        </tbody>

                    </table>
                   </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>