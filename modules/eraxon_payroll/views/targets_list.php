<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="tw-mb-2 sm:tw-mb-4">
                    <?php if(has_permission('targets','','create') || is_admin()){ ?>
                    <a href="<?php echo admin_url('eraxon_payroll/add_targets'); ?>" class="btn btn-primary">
                        <i class="fa-regular fa-plus tw-mr-1"></i>
                        <?php echo "Add Target"; ?>
                    </a>
                <?php } ?>
                </div>
                 <div class="panel_s">
                    <div class="panel-body panel-table-full">
                    	<table class="table dt-table" data-order-col="1" data-order-type="asc">
                            <thead>
                                <th>Target Name</th>
                                <th>Target Leads</th>
                                <th>Bonus</th>
                                <th>Accumulative Bonus</th>
                                <th>Status</th>
                                <th><?php echo _l('options'); ?></th>
                            </thead>
                            <tbody>
                            <?php
                                if(has_permission('targets','','view') || is_admin()){  
                                    if(!empty($targets)){
                                        foreach ($targets as $target):
                            ?>
                                        <tr>
                                            <td><?php echo $target->name; ?></td>
                                            <td><?php echo $target->target; ?></td>
                                            <td><?php echo $target->bonus; ?></td>
                                            <td><?php echo $target->accumulative_bonus; ?></td>
                                            <td><?php echo $target->status; ?></td>
                                            <td>
                                            <div class="tw-flex tw-items-center tw-space-x-3">
                                                <?php
                                                    if(has_permission('targets','','edit') || is_admin()){
                                                ?>
                                                        <a href="<?php echo admin_url('eraxon_payroll/add_targets/'. $target->id);  ?>"
                                                            onclick="edit_as_request(this,<?php echo $target->id;  ?>); return false"
                                                            data-request-type="<?php ?>" 
                                                            data-description="<?php  ?>"

                                                            class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700" data-hide-from-client="0" >
                                                            <i class="fa-regular fa-pen-to-square fa-lg"></i>
                                                        </a>
                                                <?php } 
                                                    if(has_permission('targets','','delete') || is_admin()){
                                                ?>
                                                        <a href="<?php echo admin_url('eraxon_payroll/delete_target/' . $target->id); ?>" class="tw-mt-px tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 _delete">
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
