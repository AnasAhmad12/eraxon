<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="tw-mb-2 sm:tw-mb-4">
                     <?php if(has_permission('eraxon_qa','','create') || is_admin()){ ?>
                    <a href="#" onclick="new_qa_status(); return false;" class="btn btn-primary">
                        <i class="fa-regular fa-plus tw-mr-1"></i>
                        <?php echo "Add QA Reviewer Status"; ?>
                    </a>
                <?php } ?>
                </div>
                 <div class="panel_s">
                    <div class="panel-body panel-table-full">
                    	<table class="table dt-table" data-order-col="1" data-order-type="asc">
                            <thead>
                            	<th>Name</th>
                                <th>is Action</th>
                                <th><?php echo _l('options'); ?></th>
                            </thead>
                            <tbody>
                          	<?php foreach ($qa_status as $as) { ?>
                                <tr>
                                	<td><?php echo $as['name']; ?></td>
                                	<td><?php if($as['isactive']==0){
                                		echo "Active";
                                	}elseif($as['isactive']==1){
                                		echo "Deactive";
                                	}

                                	?></td>
                                	<td>
                                        <?php if($as['qadefault'] != 1){ ?>
                                        <div class="tw-flex tw-items-center tw-space-x-3">
                                            <?php if(has_permission('eraxon_qa','','edit')){ ?>
                                            <a href="#"
                                                onclick="edit_as_request(this,<?php echo $as['id']; ?>); return false"
                                                data-name="<?php echo $as['name']; ?>" 
                                                data-isactive="<?php echo $as['isactive']; ?>"
                                                

                                                class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700" data-hide-from-client="0" >
                                                <i class="fa-regular fa-pen-to-square fa-lg"></i>
                                            </a>
                                        <?php }
                                        if(has_permission('eraxon_qa','','delete')  || is_admin()){ ?>
                                            <a href="<?php echo admin_url('eraxon_quality/delete_qar_status/' . $as['id']); ?>" class="tw-mt-px tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 _delete">
                                                <i class="fa-regular fa-trash-can fa-lg"></i>
                                            </a>
                                        <?php } ?>
                                        </div>
                                    <?php } ?>
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
<div class="modal fade" id="qa_status_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('eraxon_quality/qa_reviewer_status')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span class="edit-title">Edit QA Reviewer Status</span>
                    <span class="add-title">Add QA Reviewer Status</span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="additional"></div>
                        <div class="form-group">
                            <label for="qaname">Name</label>
                           
                                <input type="text" class="form-control" name="qaname" id="qaname">
                           
                        </div>                       
                    </div>

                    <?php  if(is_admin() || has_permission('eraxon_qa', '', 'edit')){ ?>
                    <div class="col-md-12" id="isactive">
                          <label for="isactive" class="control-label">Status</label>
                          <select name="isactive" class="selectpicker" id="isactive_status" data-width="100%" data-none-selected-text="<?php echo _l('none_type'); ?>"> 
                           <option value="1">Active</option>  
                           <option value="0">Deactive</option>                                        
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
        qaname: 'required',
        isactive:'required',
    }, manage_qa_status);
    $('#qa_status_modal').on('hidden.bs.modal', function(event) {
        $('#additional').html('');
        $('.add-title').removeClass('hide');
        $('.edit-title').removeClass('hide');
    });
});

	function manage_qa_status(form) {
    var data = $(form).serialize();
    var url = form.action;
    $.post(url, data).done(function(response) {
        window.location.reload();
    });
    return false;
	}

	function new_qa_status() {
    $('#qa_status_modal input[name="qaname"]').val('');
    //tinyMCE.activeEditor.setContent('');
    $('#qa_status_modal').modal('show');
    $('.edit-title').addClass('hide');
	}



function edit_as_request(invoker, id) {
    
 	$('#additional').append(hidden_input('id', id));
    $('#qa_status_modal input[name="qaname"]').val($(invoker).data('name'));
    //$('#qa_status_modal textarea[name="description"]').val($(invoker).data('description'));
     //tinyMCE.activeEditor.setContent($(invoker).data('description'));
     $('#qa_status_modal select[name="isactive"]').val($(invoker).data('isactive')).change();
    
    $('#qa_status_modal').modal('show');
    $('.add-title').addClass('hide');
}


</script>