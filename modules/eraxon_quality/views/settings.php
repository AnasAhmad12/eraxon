<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); 
    $qa_staff  = get_option('auto_distribution_staffid_with_daily_targets');
    $qa_staff = json_decode($qa_staff,1);
?> 
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <?php echo form_open(admin_url('eraxon_quality/save_settings')); 
                           $auto_distribution = get_option('auto_distribution_manual_automatic_check');

                        ?>
                                <div class="form-group">
                                    <label for="distribution">Select Leads Distribution</label>
                                    <input type="radio" name="distribution" value="1" <?= $auto_distribution == 1 ? 'checked': '' ?>> Automatic
                                    <input type="radio" name="distribution" value="0" <?= $auto_distribution == 0 ? 'checked': '' ?> > Manual
                                </div>

                                       
                                      <hr>
                            <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                        <?php echo form_close(); ?>
                    </div>
                </div>

                <div class="panel_s">
                    <div class="panel-body">
                        <h4>Set Daily Targets</h4>
                        <?php echo form_open(admin_url('eraxon_quality/qa_daily_target'),['id'=>'qa_form']);  ?>

                            <table class="table">
                                <tr>
                                    <th>QA Staff Name</th>
                                    <th>Daily Target</th>
                                    <th>Fix</th>
                                </tr>
                             <?php foreach($qa_staff as $qa)
                            { ?>
                                <tr>
                                    <td><?=get_staff_full_name($qa['staff_id']) ?></td>
                                    <td>
                                        <input type="number" name="target[]" value="<?=$qa['daily_target']?>" class="form-control">   
                                        <input type="hidden" name="sid[]" value="<?=$qa['staff_id']?>" class="form-control">
                                    </td>
                                    <td>
                                        <input type="checkbox" class="form-control fix" <?php if($qa['fix'] == 1){ echo 'checked';}?>>
                                        <?php if($qa['fix'] == 0)
                                        {
                                             echo '<input type="hidden" name="fix[]" value="0">'; 

                                        }else{
                                             echo '<input type="hidden" name="fix[]" value="1">'; 
                                        } ?>
                                    </td>
                                </tr>
                                    
                        
                        <?php  } ?>
                        </table>
                        <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>

                        <?php echo form_close(); ?>
                    </div>
                </div>

                <div class="panel_s">
                    <div class="panel-body">
                        <?php echo form_open(admin_url('eraxon_quality/lead_approve'));  ?>
                               

                        <div id="filter-date" class="row filter_by">
                        <div class="col-md-2 leads-filter-column">
                            <?php echo render_input('filter_date', 'Date', date('Y-m-d'), 'date'); ?>
                        </div>
                        <button type="submit" id="submit_btn" style="margin-top:22px;" class="btn btn-primary">
                        Update CSR Leads Status</button>

                    </div>          
                        
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>

<script>
    $(document).on('change','[name=distribution]',function(){
        if($(this).val() == 0)
        {
            $("#distributor").show();
        }else{
            $("#distributor").hide();
        }
    });

    $(document).on('change','.fix',function(){

        if($(this).is(':checked'))
        {
            $(this).next("[name^='fix']").val('1');

        }else{

            $(this).next("[name^='fix']").val('0');
        }
    });

    /*function qaFormSubmit(){
        var data = $('#qa_form').serialize();
        console.log(data);
    }*/
    <?php if($auto_distribution==0) {?>
        
    <?php } ?>
</script>
</body>

</html>