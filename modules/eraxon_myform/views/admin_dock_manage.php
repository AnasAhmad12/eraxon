<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="tw-mb-2 sm:tw-mb-4">
                   
                    <a href="#" onclick="add_dock(); return false;" class="btn btn-primary">
                        <i class="fa-regular fa-plus tw-mr-1"></i>
                        <?php echo "Create Dock"; ?>
                    </a>
                
                </div>
                 <div class="panel_s">
                    <div class="panel-body panel-table-full">
                    	<table class="table dt-table" data-order-col="1" data-order-type="asc">
                            <thead>
                                <tr>
                                	<!-- <th>ID</th> -->
                                    <th>Name</th>
                                    <th>Amount</th>
                                    <th><?php echo _l('options'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            	<?php 
                                if(isset($all_docks)){
                                foreach ($all_docks as $as) { ?>
                                <tr>
                                	<!-- <td><?php echo $as['id']; ?></td> -->
                                	<td><?php echo $as['dock_name']; ?></td>
                                	<td><?php echo number_format($as['amount'],0,".",","); ?></td>                                	
                                	<td>
                                        <div class="tw-flex tw-items-center tw-space-x-3">
                                            <a href="#"
                                                onclick="edit_as_request(this,<?php echo $as['id']; ?>); return false"
                                                data-name="<?php echo $as['dock_name']; ?>" 
                                                data-amount="<?php echo $as['amount']; ?>"
                                                class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700" data-hide-from-client="0" >
                                                <i class="fa-regular fa-pen-to-square fa-lg"></i>
                                            </a>
                                             <a href="<?php echo admin_url('eraxon_myform/delete_dock/' . $as['id']); ?>" class="tw-mt-px tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 _delete">
                                                <i class="fa-regular fa-trash-can fa-lg"></i>
                                            </a> 
                                           

                                        </div>
                                    </td>
                                </tr>
                            <?php }}?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="admin_dock" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('eraxon_myform/add_dock')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span class="edit-title">Edit Dock</span>
                    <span class="add-title">Add New Dock</span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="additional"></div>
                        <div class="form-group">
                            <label for="dock_name">Dock Name</label>
                           
                                <input type="text" class="form-control" name="dock_name">
                           
                        </div>
                        <div class="form-group">
		                    <label for="amount">Dock Amount</label>
		                   
		                        <input type="number" class="form-control" name="amount">
		                   
		                </div>
                        
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
        dock_name: 'required',
        amount:'required',
    }, manage_advance_salary);
    $('#admin_dock').on('hidden.bs.modal', function(event) {
        $('#additional').html('');
        $('#admin_dock input[name="dock_name"]').val('');
        $('#admin_dock input[name="amount"]').val('');
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

	function add_dock() {
    $('#admin_dock').modal('show');
    $('.edit-title').addClass('hide');
	}



function edit_as_request(invoker, id) {
    
 	$('#additional').append(hidden_input('id', id));
    $('#admin_dock input[name="dock_name"]').val($(invoker).data('name'));
    $('#admin_dock input[name="amount"]').val($(invoker).data('amount'));

    $('#admin_dock').modal('show');
    $('.add-title').addClass('hide');
}


</script>