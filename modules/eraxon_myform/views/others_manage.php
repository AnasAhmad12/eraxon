<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="tw-mb-2 sm:tw-mb-4">
                    <a href="#" onclick="new_advance_salary(); return false;" class="btn btn-primary">
                        <i class="fa-regular fa-plus tw-mr-1"></i>
                        <?php echo "Add Request"; ?>
                    </a>
                </div>
                 <div class="panel_s">
                    <div class="panel-body panel-table-full">
                    	<table class="table dt-table" data-order-col="1" data-order-type="asc">
                            <thead>
                            	<?php if(is_admin()){ ?>
                                		<th>Staff Name</th>
                                	<?php } ?>
                                <th>Type</th>
                                <th>Description</th>
                                <th>Requested Date/Time</th>
                                <th>Status</th>
                                <th><?php echo _l('options'); ?></th>
                            </thead>
                            <tbody>
                            <!-- 	<?php foreach ($advance_salary as $as) { ?>
                                <tr>
                                	<?php if(is_admin()){ ?>
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
                                            <a href="#"
                                                onclick="edit_as_request(this,<?php echo $as['id']; ?>); return false"
                                                data-reason="<?php echo $as['reason']; ?>" 
                                                data-amount="<?php echo $as['amount']; ?>"
                                                data-amount_needed_date="<?php echo $as['amount_needed_date']; ?>"

                                                class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700" data-hide-from-client="0" >
                                                <i class="fa-regular fa-pen-to-square fa-lg"></i>
                                            </a>
                                            <a href="<?php echo admin_url('eraxon_myform/delete_as/' . $as['id']); ?>" class="tw-mt-px tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 _delete">
                                                <i class="fa-regular fa-trash-can fa-lg"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?> -->
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
        <?php echo form_open(admin_url('eraxon_myform/other_form_ad')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span class="edit-title">Edit Request</span>
                    <span class="add-title">Add Request</span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="additional"></div>
                        <div class="form-group">
		                    <label for="amount">Select Request</label>
		                   
		                        <select class="form-control" name="request_type">
                                    <option>Resignation</option>  
                                    <option>Harassment</option>
                                    <option>Complaint</option>      
                                </select>
		                   
		                </div>
                        <?php echo render_textarea('description', 'Add Details'); ?>
                        <input type="hidden" name="id_staff" value="<?php echo $current_user->staffid; ?>">
                    </div>
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
        request_type: 'required',
        description:'required',
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
    
    $('#advance_salary').modal('show');
    $('.add-title').addClass('hide');
}


</script>