<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="tw-mb-2 sm:tw-mb-4">
                    <?php if(has_permission('bonuses','','create') || is_admin()){ ?>
                    <a href="<?php echo admin_url('eraxon_payroll/add_bonuses'); ?>" class="btn btn-primary">
                        <i class="fa-regular fa-plus tw-mr-1"></i>
                        <?php echo "Add Bonus"; ?>
                    </a>
                <?php } ?>
                </div>
                 <div class="panel_s">
                    <div class="panel-body panel-table-full">
                    	<table class="table dt-table" data-order-col="1" data-order-type="asc">
                            <thead>
                                <th>Bonus Name</th>
                                <th>Amount</th>
                                <th><?php echo _l('options'); ?></th>
                            </thead>
                            <tbody>
                            <?php
                                if(has_permission('bonuses','','view') || is_admin()){  
                                    if(!empty($bonuses)){
                                        foreach ($bonuses as $bonus):
                            ?>
                                        <tr>
                                            <td><?php echo $bonus->name; ?></td>
                                            <td><?php echo $bonus->amount; ?></td>
                                            <td>
                                            <div class="tw-flex tw-items-center tw-space-x-3">
                                                <?php
                                                    if(has_permission('bonuses','','edit') || is_admin()){
                                                ?>
                                                        <a href="<?php echo admin_url('eraxon_payroll/add_bonuses/'. $bonus->id);  ?>"
                                                            onclick="edit_as_request(this,<?php echo $bonus->id;  ?>); return false"
                                                            data-request-type="<?php ?>" 
                                                            data-description="<?php  ?>"

                                                            class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700" data-hide-from-client="0" >
                                                            <i class="fa-regular fa-pen-to-square fa-lg"></i>
                                                        </a>
                                                <?php } 
                                                    if(has_permission('bonuses','','delete') || is_admin()){
                                                ?>
                                                        <a href="<?php echo admin_url('eraxon_payroll/delete_bonus/' . $bonus->id); ?>" class="tw-mt-px tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 _delete">
                                                            <i class="fa-regular fa-trash-can fa-lg"></i>
                                                        </a>
                                                <?php } ?>
                                                    </div>
                                            </td>
                                        </tr>
                            <?php
                                    endforeach;
                                }
                            }
                            ?>
                            	
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
