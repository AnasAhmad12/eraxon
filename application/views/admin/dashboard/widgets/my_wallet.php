<?php defined('BASEPATH') or exit('No direct script access allowed'); 

$this->load->model('eraxon_wallet/eraxon_wallet_model');
$current_balance = $this->eraxon_wallet_model->get_wallettotal_balance_by_staff_id(get_staff_user_id());

$wallet_id = $this->eraxon_wallet_model->get_walletid_by_staff_id(get_staff_user_id());
$last_three_transactions = $this->eraxon_wallet_model->wallet_last_three_transactions($wallet_id);

?>
<div class="widget" id="widget-<?php echo create_widget_id(); ?>" data-name="<?php echo 'Wallet'; ?>">
    <div class="panel_s user-data">
        <div class="panel-body home-activity">
            <div class="widget-dragger"></div>

            <div id="container">
               
                    <div class="clearfix"></div>
                    <div class="row">
                        <div class="col-md-12">
                            
                            <h4><p class="padding-5 bold"><i class="fa fa-wallet menu-icon"></i> <?php echo "Wallet"; ?></p></h4>
                            <hr class="hr-panel-heading-dashboard">
                            
                            <h4>Current Balance: Rs.<?php echo number_format($current_balance,0,".",","); ?></h4>

                            <table class="table table-bordered">
                                <tr>
                                    <th colspan="2" class="text-center">Transactions</th>
                                </tr>
                                <tr>
                                    <td>Type</td>
                                    <td>Amount</td>
                                </tr>

                                <?php foreach($last_three_transactions as $trans){ ?>
                                    <tr>
                                    <td><?= $trans['amount_type'] ?></td>
                                   <?php if($trans['in_out'] == 'in'){ ?>
                                        <td style="color:#1bef15;"><?php echo '+'.number_format($trans['amount'],0,".",","); ?></td>
                                    <?php }else{  ?>
                                        <td style="color:#f21f1f;"><?php echo '-'.number_format($trans['amount'],0,".",","); ?></td>
                                    <?php } ?>
                                    
                                    </tr>

                                <?php } ?>

                            </table>



                        </div>
                    </div>
                
            </div>

        </div>
    </div>
</div>
