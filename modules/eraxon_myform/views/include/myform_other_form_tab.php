<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>


    <div class="content">
        <div class="row">
            <div class="col-md-12">
                 <div class="panel_s">
                    <div class="panel-body panel-table-full">
                    	<table class="table dt-table" data-order-col="1" data-order-type="asc">
                            <thead>
                            	<?php if(has_permission('other_form','','view')){ ?>
                                <th>Staff Name</th>
                                <?php } ?>
                                <th>Type</th>
                                <th>Description</th>
                                <th>Requested Date/Time</th>
                                <th>Status</th>
                                <th><?php echo _l('options'); ?></th>
                            </thead>
                            <tbody>
                          	<?php 
                             $CI = &get_instance();

                             $staffid = $CI->uri->segment(4);
                            $other_requests=others_form_helper($staffid)['other_requests'];
                            foreach ($other_requests as $as) { ?>
                                <tr>
                                	<?php if(has_permission('other_form','','view')){ ?>
                                		<td><?php echo $as['firstname'].' '.$as['lastname']; ?></td>
                                	<?php } ?>
                                	<td><?php echo $as['request_type']; ?></td>
                                	<td><?php echo $as['description']; ?></td>
                                	<td><?php echo $as['requested_datetime']; ?></td>
                                	<td><?php if($as['status']==0){
                                		echo "Pending";
                                	}elseif($as['status']==1){
                                		echo "Accepted";
                                	}elseif($as['status']==2){
                                        echo "Rejected";
                                    }elseif($as['status']==3){
                                        echo "Action Taken";
                                    }elseif($as['status']==4){
                                        echo "In-Progress";
                                    }

                                	?></td>
                                	<td>
                                        <div class="tw-flex tw-items-center tw-space-x-3">
                                            <?php if(has_permission('other_form','','edit')){ ?>
                                            <a href="#"
                                                onclick="edit_as_request(this,<?php echo $as['id']; ?>); return false"
                                                data-request-type="<?php echo $as['request_type']; ?>" 
                                                data-description="<?php echo $as['description']; ?>"
                                                data-status="<?php echo $as['status']; ?>"

                                                class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700" data-hide-from-client="0" >
                                                <i class="fa-regular fa-pen-to-square fa-lg"></i>
                                            </a>
                                        <?php }
                                        if(has_permission('other_form','','delete')  || is_admin()){ ?>
                                            <a href="<?php echo admin_url('eraxon_myform/delete_or/' . $as['id']); ?>" class="tw-mt-px tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 _delete">
                                                <i class="fa-regular fa-trash-can fa-lg"></i>
                                            </a>
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
