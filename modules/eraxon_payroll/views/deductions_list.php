<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="tw-mb-2 sm:tw-mb-4">
                    <?php if(has_permission('deductions','','create') || is_admin()){ ?>
                    <a href="<?php echo admin_url('eraxon_payroll/add_deductions'); ?>" class="btn btn-primary">
                        <i class="fa-regular fa-plus tw-mr-1"></i>
                        <?php echo "Add Deduction"; ?>
                    </a>
                <?php } ?>
                </div>
                 <div class="panel_s">
                    <div class="panel-body panel-table-full">
                    	<table class="table dt-table" data-order-col="1" data-order-type="asc">
                            <thead>
                                <th>Deduction Name</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th><?php echo _l('options'); ?></th>
                            </thead>
                            <tbody>
                            <?php 
                                if(has_permission('deductions','','view') || is_admin()){
                                    if(!empty($deductions)){
                                        foreach ($deductions as $deduction):
                            ?>
                                        <tr>
                                            <td><?php echo $deduction->name; ?></td>
                                            <td><?php echo $deduction->type; ?></td>
                                            <td><?php echo $deduction->amount; ?></td>
                                            <td>
                                            <div class="tw-flex tw-items-center tw-space-x-3">
                                                <?php
                                                    if(has_permission('deductions','','edit') || is_admin()){
                                                ?>
                                                        <a href="<?php echo admin_url('eraxon_payroll/add_deductions/'. $deduction->id);  ?>"
                                                            onclick="edit_as_request(this,<?php echo $deduction->id;  ?>); return false"
                                                            data-request-type="<?php ?>" 
                                                            data-description="<?php  ?>"

                                                            class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700" data-hide-from-client="0" >
                                                            <i class="fa-regular fa-pen-to-square fa-lg"></i>
                                                        </a>
                                                <?php } 
                                                    if(has_permission('deductions','','delete') || is_admin()){
                                                ?>
                                                        <a href="<?php echo admin_url('eraxon_payroll/delete_deduction/' . $deduction->id); ?>" class="tw-mt-px tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 _delete">
                                                            <i class="fa-regular fa-trash-can fa-lg"></i>
                                                        </a>
                                                <?php } ?>
                                                    </div>
                                            </td>
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
