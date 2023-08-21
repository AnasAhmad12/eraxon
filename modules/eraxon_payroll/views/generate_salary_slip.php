<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<style type="text/css">
    
    .salary_label_unpaid{color:#f21f1f;border:1px solid #faa5a5;background: #fff6f6; cursor: pointer;padding: 2px;border-radius: 5px;}
    .salary_label_paid{color:#1bef15;border:1px solid #a4f9a1;background: #f6fff6;cursor: pointer;padding: 2px;border-radius: 5px;}
    .salary_label_hold{color:#28b8da;border:1px solid #a9e3f0;background: #f7fcfe;cursor: pointer;padding: 2px;border-radius: 5px;}

    .overlay{
    display: none;
    position: fixed;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    z-index: 999;
    background: rgba(255,255,255,0.8) url("<?php echo base_url('uploads/company/loading.gif'); ?>") center no-repeat;
}

</style>
 <div class="overlay"></div>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
            <?php echo form_open(admin_url('eraxon_payroll/generate_salary_slips')); ?>
                <div class="row filter_by">
                    <div class="col-md-2 leads-filter-column" style="margin-bottom:10px;">
                    <?php echo render_input('month_timesheets','month',date('Y-m'), 'month'); ?>
                    </div>
                    <div class="col-md-3 leads-filter-column" style="margin-bottom:10px;">
                    <?php echo render_select('role_id',$roles,array('roleid', 'name'),'role'); ?>
                    </div>
                 <div>
                <div class="tw-mb-2 sm:tw-mb-4 mtop25 text-right">
                    <?php if(has_permission('generate_salary_slip','','create') || is_admin()){ ?>
                    <!-- <a href="<?php echo admin_url('eraxon_payroll/generate_salary_slips'); ?>" class="btn btn-success"> -->
                        <!-- <i class="fa-regular fa-plus tw-mr-1"></i> -->
                        <!-- <?php echo "Generate Salary Slip"; ?>
                    </a> -->
                    <button type="submit" class="btn btn-success"><?php echo "Generate Salary Slip"; ?></button>
                <?php } ?>
                </div>
            <?php echo form_close(); ?>
            </div>
            <div class="col-md-12">
                 <div class="panel_s">
                    <div class="panel-body panel-table-full">
                    	<table class="table dt-table" data-order-col="1" data-order-type="asc">
                            <thead>
                                <th>Month</th>
                                <th>Employee Name</th>
                                <th>Pesudo</th>
                                <th>Role</th>
                                <!-- <th>Pay Grade</th> -->
                                <th>Basic Salary</th>
                                <th>Gross Salary</th>
                                <th>Net Salary</th>
                                <th>Status</th> 
                                <th>Staff Status</th> 
                                <th>Action</th>
                            </thead>
                            <tbody>
                            <?php
                                if(has_permission('generate_salary_slip','','view') || is_admin()){  
                                    if(!empty($salar_details)){
                                        foreach ($salar_details as $salar_detail):
                            ?>
                                        <tr id="<?php echo $salar_detail->id; ?>">
                                            <td><?php echo date('Y-m', strtotime($salar_detail->date)); ?></td>
                                            <td><?php echo $salar_detail->name; ?></td>
                                            <td><?php echo get_custom_field_value($salar_detail->staffid,'staff_pseudo','staff',true) ?></td>
                                            <td><?php echo $salar_detail->rolename; ?></td>

                                            <td><?php echo number_format($salar_detail->basic_salary,0,".",","); ?></td>
                                            <td><?php echo number_format($salar_detail->gross_salary,0,".",","); ?></td>
                                            <td><?php echo number_format($salar_detail->net_salary,0,".",","); ?></td>
                                            <?php
                                            $sclass = ''; 
                                            if($salar_detail->status == 'unpaid')
                                            { $sclass = 'salary_label_unpaid';}else if($salar_detail->status == 'paid')
                                            {  $sclass = 'salary_label_paid';}else if($salar_detail->status == 'hold')
                                            { $sclass = 'salary_label_hold';} ?>
                                            <td><span class="<?=$sclass ?>" onclick="edit_status(this,<?php echo $salar_detail->id; ?>); return false" 
                                                data-status="<?php echo $salar_detail->status; ?>"><?php echo $salar_detail->status; ?></span></td>
                                            <td><?php echo $salar_detail->ack_status; ?></td>    

                                            <td>
                                            <div class="tw-flex tw-items-center tw-space-x-3">
                                                <?php
                                                    if(has_permission('generate_salary_slip','','view') || is_admin()){
                                                ?>
                                                       <a href="<?php echo admin_url('eraxon_payroll/payroll_adjustment/' . $salar_detail->id); ?>" class="tw-mt-px tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 ">
                                                            <!-- <i class="fa-regular fa-trash-can fa-lg"></i> -->
                                                            Add Adjustment
                                                        </a> 
                                                        <a href="<?php echo admin_url('eraxon_payroll/salary_slip_detail/' . $salar_detail->id); ?>" class="tw-mt-px tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 ">
                                                            <!-- <i class="fa-regular fa-trash-can fa-lg"></i> -->
                                                            View
                                                        </a>
                                                        <a href="<?php echo admin_url('eraxon_payroll/salary_slip_detail_delete/' . $salar_detail->id); ?>" class="tw-mt-px tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 ">
                                                            <!-- <i class="fa-regular fa-trash-can fa-lg"></i> -->
                                                            Delete
                                                        </a>
                                                <?php } ?>
                                                </div>
                                            </td>
                                        </tr>
                            <?php
                                    endforeach;
                                }
                            }
                            ?>
                            	
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
</div>
<div class="modal fade" id="salary_slip_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('eraxon_payroll/salary_slip_status')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span class="edit-title">Change Salary Slip Status</span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div id="additional"></div>
                    <div class="col-md-12" id="status">
                          <label for="status" class="control-label">Status</label>
                          <select name="status" class="selectpicker" id="status" data-width="100%" data-none-selected-text="<?php echo _l('none_type'); ?>"> 
                           <option value="paid">Paid</option>                  
                           <option value="unpaid">Unpaid</option>
                           <option value="hold">Hold</option>                  
                                         
                         </select>
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

var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
    return false;
};

var month_year = getUrlParameter('m');

//console.log(month_year);
 //console.log(month_year.substr(0,4) + '/' + month_year.substr(5,7));
if(month_year.length > 0)
{
    $('#month_timesheets').val(month_year);//month_year.substr(0,4) + '/' + month_year.substr(5,7));
}

var $loading = $('.overlay').hide();
$(document)
  .ajaxStart(function () {
    $loading.show();
  })
  .ajaxStop(function () {
    $loading.hide();
  });


  $("#month_timesheets").on('change',function(){
   /* var url = '<?php echo admin_url('eraxon_payroll/generate_salary_slip'); ?>';
    var v = $('#month_timesheets').val();
    var data = {'month_timesheets':v};
    $.post(url, data).done(function(response) {
    });*/
    var v = $('#month_timesheets').val();
    window.open("<?php echo admin_url('eraxon_payroll/generate_salary_slip') ?>?m="+v,"_self");
  });

    appValidateForm($('form'), {
        status: 'required',
    }, manage_salary_slip_status);
    
});

  function manage_salary_slip_status(form) {
    var data = $(form).serialize();
    var url = form.action;
    $.post(url, data).done(function(response) {

        //console.log(response);
         
        //window.location.reload();
         if(response == 1)
         {
            $('#salary_slip_modal').modal('hide');

         }
    });
    return false;
    }


function edit_status(invoker, id) {
    
    $('#salary_slip_modal #additional').html(hidden_input('id', id));
    $('#salary_slip_modal select[name="status"]').val($(invoker).data('status')).change();

    $('#salary_slip_modal').modal('show');
   
}


</script>