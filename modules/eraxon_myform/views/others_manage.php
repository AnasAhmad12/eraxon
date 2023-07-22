<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="tw-mb-2 sm:tw-mb-4">
                     <?php if(has_permission('other_form','','create') || is_admin()){ ?>
                    <a href="#" onclick="new_advance_salary(); return false;" class="btn btn-primary">
                        <i class="fa-regular fa-plus tw-mr-1"></i>
                        <?php echo "Add Request"; ?>
                    </a>
                <?php } ?>
                </div>
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
                          	<?php foreach ($other_requests as $as) { ?>
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
</div>
<div class="modal fade" id="other_requests" tabindex="-1" role="dialog">
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
                                    <option value="">Select Request Type</option>  
                                    <option value="resignation">Resignation</option>  
                                    <option value="harassment">Harassment</option>
                                    <option value="complaint">Complaint</option>      
                                </select>
		                   
		                </div>
                        <?php 
                            $contents = '';
                        echo render_textarea('description', 'Add Details',$contents,array(),array(),'','tinymce' ); 
                          
                        ?>
                        <input type="hidden" name="id_staff" value="<?php echo $current_user->staffid; ?>">
                    </div>

                    <?php  if(is_admin() || has_permission('other_form', '', 'edit')){ ?>
                    <div class="col-md-12" id="status">
                          <label for="status" class="control-label">Status</label>
                          <select name="status" class="selectpicker" id="status" data-width="100%" data-none-selected-text="<?php echo _l('none_type'); ?>"> 
                           <option value="0">Pending</option>  
                           <option value="1">Accepted</option>  
                           <option value="2">Rejected</option>                 
                           <option value="3">Action Taken</option>
                           <option value="4">In-Progress</option>                  
                                        
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
        request_type: 'required',
        description:'required',
    }, manage_advance_salary);
    $('#other_requests').on('hidden.bs.modal', function(event) {
        $('#additional').html('');
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
    $('#other_requests select[name="request_type"]').val('');
    tinyMCE.activeEditor.setContent('');
    $('#other_requests').modal('show');
    $('.edit-title').addClass('hide');
	}



function edit_as_request(invoker, id) {
    
 	$('#additional').append(hidden_input('id', id));
    $('#other_requests select[name="request_type"]').val($(invoker).data('request-type'));
    //$('#other_requests textarea[name="description"]').val($(invoker).data('description'));
     tinyMCE.activeEditor.setContent($(invoker).data('description'));
     $('#other_requests select[name="status"]').val($(invoker).data('status')).change();
    
    $('#other_requests').modal('show');
    $('.add-title').addClass('hide');
}


</script>