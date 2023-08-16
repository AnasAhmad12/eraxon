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
                        <h4><?php echo '<i class=" fa fa-clipboard"></i> Add Dock' ?></h4>
                      </div> 
                    </div>
                    <div class="clearfix"></div>
                    <div class="row">
                        <?php echo form_open(admin_url('eraxon_myform/team_lead_add_dock')); ?>

                        <input type="hidden" name="dock_name" value="" id="dock_name">
                        <input type="hidden" name="dock_amount" value="" id="dock_amount">
                        <div class="col-md-6">
                             <div class="form-group">
                                <label for="staffid" class="control-label">Select Staff</label>
                                    <select name="staff_id" data-live-search="true" id="staffid" class="form-control selectpicker">
                                        <?php foreach ($staff_members as $staff) {?>
                                        <option value="<?php echo $staff['staffid']; ?>" >
                                            <?php echo $staff['full_name'].' ('.get_custom_field_value($staff['staffid'],'staff_pseudo','staff',true).')'; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                        </div>
                        <div class="col-md-6">
                             <div class="form-group">
                                <label for="dock_id" class="control-label">Select Dock</label>
                                    <select name="dock_id" data-live-search="true" id="dock_id" class="form-control selectpicker">
                                        <option value=''>Select Dock</option>
                                        <?php foreach ($docks as $dock) {?>
                                        <option value="<?php echo $dock['id']; ?>" >
                                            <?php echo $dock['dock_name'].' | '.number_format($dock['amount'],0,".",","); ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
                        </div>
                        <?php echo form_close(); ?>
                    </div>

                </div>
            </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>

<script>
    $(function(){
        $('#dock_id').on('change', function(event){
            var arr = [];
            let dock_text = $('#dock_id option:selected').text();
            arr =  dock_text.split("|");

            $('#dock_name').val($.trim(arr[0]));
            $('#dock_amount').val($.trim(arr[1]));

            //console.log('Dock Name: ' + $.trim(arr[0]));
            //console.log('Dock Amount: ' + $.trim(arr[1]));
        });
    });
</script>