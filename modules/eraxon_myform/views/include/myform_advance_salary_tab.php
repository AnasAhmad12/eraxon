<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

    <div class="content">
        <div class="row">
            <div class="col-md-12">
                 <div class="panel_s">
                    <div class="panel-body panel-table-full">
                    	<table class="table dt-table" data-order-col="1" data-order-type="asc">
                            <thead>
                            	<?php if(has_permission('advance_salary','','view')){ ?>
                                		<th>Staff Name</th>
                                	<?php } ?>
                                <th>Reason</th>
                                <th>Amount</th>
                                <th>Requested Date/Time</th>
                                <th>Needed Date</th>
                                <th>Status</th>
                                <th><?php echo _l('options'); ?></th>
                            </thead>
                            <tbody>
                            	<?php 
                                 $CI = &get_instance();

                                 $staffid = $CI->uri->segment(4);
                                $advance_salary=advance_salary_helper($staffid)['advance_salary'];
                                foreach ($advance_salary as $as) { ?>
                                <tr>
                                	<?php if(has_permission('advance_salary','','view') || is_admin()){ ?>
                                		<td><?php echo $as['firstname'].' '.$as['lastname'].' ('.get_custom_field_value($as['staffid'],'staff_pseudo','staff',true).')'; ?></td>
                                	<?php } ?>
                                	<td><?php echo $as['reason']; ?></td>
                                	<td><?php echo $as['amount']; ?></td>
                                	<td><?php echo $as['requested_datetime']; ?></td>
                                	<td><?php echo $as['amount_needed_date']; ?></td>
                                	<td><?php if($as['status']==0){
                                		echo "Pending";
                                	}elseif($as['status']==1){
                                		echo "Approved";
                                	}else{
                                		echo 'Rejected';
                                	}

                                	?></td>
                                	<td>
                                        <div class="tw-flex tw-items-center tw-space-x-3">
                                            <?php if(has_permission('advance_salary','','edit')  || is_admin()){ 

                                                ?>
                                            <a href="#"
                                                onclick="edit_as_request(this,<?php echo $as['id']; ?>); return false"
                                                data-reason="<?php echo $as['reason']; ?>" 
                                                data-amount="<?php echo $as['amount']; ?>"
                                                data-amount_needed_date="<?php echo $as['amount_needed_date']; ?>"
                                                data-status="<?php echo $as['status']; ?>"
                                                data-staffid="<?php echo $as['id_staff']; ?>" 
                                                class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700" data-hide-from-client="0" >
                                                <i class="fa-regular fa-pen-to-square fa-lg"></i>
                                            </a>
                                            <?php }
                                            if(has_permission('advance_salary','','delete')  || is_admin()){ ?>
                                            <a href="<?php echo admin_url('eraxon_myform/delete_as/' . $as['id']); ?>" class="tw-mt-px tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 _delete">
                                                <i class="fa-regular fa-trash-can fa-lg"></i>
                                            </a>
                                        <?php } ?>

                                        <?php 
                                            if(has_permission('advance_salary','','edit')  || is_admin()){ ?>
                                            <!-- <a href="<?php echo admin_url('eraxon_myform/advance_release/' . $as['id']); ?>" class="tw-mt-px tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 _delete">
                                                <i class="fa fa-refresh fa-lg"></i>
                                            </a> -->
                                        <?php } ?>

                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

