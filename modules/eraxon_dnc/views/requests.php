<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                  <div class="panel-body">
                    <div class="row">
                      <div class="col-md-6 mt-5">
                        <h4><?php echo '<i class=" fa fa-clipboard"></i> DNC Requests' ?></h4>
                      </div> 
                    </div>
                    <div class="clearfix"></div>
                    <div class="row">

                        <?php 
                        if(has_permission('dnc_check','','view') || is_admin())
                        {
                             render_datatable([
                             'Name',
                             'Phone Number',
                             'Result',
                             'Action', 
                            ], 'dnc-requests'); 
                        }else
                        {
                           render_datatable([
                             'Name',
                             'Phone Number',
                             'Result', 
                            ], 'dnc-requests');  
                        }


                        ?>

                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="other_requests" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('eraxon_dnc/update_status')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span class="edit-title">Edit DNC Status</span>
                    <span class="add-title">Add DNC Status</span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="additional"></div>
                        <div class="form-group">
                            <label for="amount">Select DNC Status</label>
                           
                                <select class="form-control" name="result">
                                    <option value="">Select</option>  
                                    <option value="Good">Good</option>  
                                    <option value="Bad">Bad</option>     
                                </select>
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
 $(function(){
        initDataTable('.table-dnc-requests', window.location.href, [1], [1]);

        appValidateForm($('form'), {
            result: 'required',
        }, manage_advance_salary);
        $('#other_requests').on('hidden.bs.modal', function(event) {
            $('#additional').html('');
            $('.add-title').removeClass('hide');
            $('.edit-title').removeClass('hide');
        });

        function manage_advance_salary(form) {
            var data = $(form).serialize();
            var url = form.action;
            $.post(url, data).done(function(response) {
                window.location.reload();
            });
            return false;
        }

   });


 function edit_as_request(invoker, id) {
    $('#additional').append(hidden_input('id', id));
    $('#other_requests select[name="result"]').val($(invoker).data('result')).change();
    //$('#other_requests select[name="status"]').val($(invoker).data('status')).change();
    
    $('#other_requests').modal('show');
    $('.add-title').addClass('hide');
}
</script>