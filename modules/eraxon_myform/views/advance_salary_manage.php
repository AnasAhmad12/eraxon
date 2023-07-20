<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="tw-mb-2 sm:tw-mb-4">
                    <?php if(has_permission('advance_salary','','create') || is_admin()){ ?>
                    <a href="#" onclick="new_advance_salary(); return false;" class="btn btn-primary">
                        <i class="fa-regular fa-plus tw-mr-1"></i>
                        <?php echo "Apply for Advance Salary"; ?>
                    </a>
                <?php } ?>
                </div>
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
                            	<?php foreach ($advance_salary as $as) { ?>
                                <tr>
                                	<?php if(has_permission('advance_salary','','view') || is_admin()){ ?>
                                		<td><?php echo $as['firstname'].' '.$as['lastname']; ?></td>
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
                                            <?php if(has_permission('advance_salary','','edit')  || is_admin()){ ?>
                                            <a href="#"
                                                onclick="edit_as_request(this,<?php echo $as['id']; ?>); return false"
                                                data-reason="<?php echo $as['reason']; ?>" 
                                                data-amount="<?php echo $as['amount']; ?>"
                                                data-amount_needed_date="<?php echo $as['amount_needed_date']; ?>"
                                                data-status="<?php echo $as['status']; ?>"
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
</div>
<div class="modal fade" id="advance_salary" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('eraxon_myform/advance_salary_ad')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span class="edit-title">Edit Advance Salary Request</span>
                    <span class="add-title">Apply for the Advance Salary</span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="additional"></div>
                        <div class="form-group">
		                    <label for="amount">Amount of Cash Advance</label>
		                   
		                        <input type="number" class="form-control" name="amount">
		                   
		                </div>
                        <?php echo render_date_input('amount_needed_date', 'Date Cash Advance is Needed'); ?>
                        <?php echo render_textarea('reason', 'Purpose of Advance Cash'); ?>
                        <input type="hidden" name="id_staff" value="<?php echo $current_user->staffid; ?>">
                    </div>

            <?php  if(is_admin() || has_permission('advance_salary', '', 'edit')){ ?>
                    <div class="col-md-12" id="status">
                          <label for="status" class="control-label">Status</label>
                          <select name="status" class="selectpicker" id="status" data-width="100%" data-none-selected-text="<?php echo _l('none_type'); ?>"> 
                           <option value="0">Pending</option>                  
                           <option value="1">Approved</option>                  
                           <!--<option value="6"><?php echo _l('early') ?></option>                  
                           <option value="3"><?php echo _l('Go_out') ?></option>                  
                           <option value="4"><?php echo _l('Go_on_bussiness') ?></option>    -->               
                         </select>
                    </div>
                <?php } ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
            </div>
        </div>
        <!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div>
    <!-- /.modal-dialog -->
</div>
<?php init_tail(); ?>
<script>
	$(function() {
    appValidateForm($('form'), {
        amount: 'required',
        amount_needed_date:'required',
        reason:'required',
    }, manage_advance_salary);
    $('#advance_salary').on('hidden.bs.modal', function(event) {
        $('#additional').html('');
        $('#advance_salary input[name="amount"]').val('');
        $('.add-title').removeClass('hide');
        $('.edit-title').removeClass('hide');
    });
});

	function manage_advance_salary(form) {
    var data = $(form).serialize();
    var url = form.action;
    $.post(url, data).done(function(response) {
        window.location.reload();
    });
    return false;
	}

	function new_advance_salary() {
    $('#advance_salary').modal('show');
    $('.edit-title').addClass('hide');
	}



function edit_as_request(invoker, id) {
    
 	$('#additional').append(hidden_input('id', id));
    $('#advance_salary input[name="amount"]').val($(invoker).data('amount'));
    $('#advance_salary input[name="amount_needed_date"]').val($(invoker).data('amount_needed_date'));
    $('#advance_salary textarea[name="reason"]').val($(invoker).data('reason'));
    $('#advance_salary select[name="status"]').val($(invoker).data('status')).change();

    $('#advance_salary').modal('show');
    $('.add-title').addClass('hide');
}


</script>