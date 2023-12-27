<?php 
	
$this->load->model('eraxon_payroll/eraxon_payroll_model');
$salar_details = $this->eraxon_payroll_model->salary_slip_details_by_employeeid($staffid);

?>
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
<div class="row">
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
                                <th>Action</th>
                            </thead>
                            <tbody>
                            <?php
                                //if(has_permission('generate_salary_slip','','view') || is_admin()){  
                                    if(!empty($salar_details)){
                                        foreach ($salar_details as $salar_detail):
                            ?>
                                        <tr>
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
                                            <td><span class="<?=$sclass ?>"  
                                                data-status="<?php echo $salar_detail->status; ?>"


                                                ><?php echo $salar_detail->status; ?></span></td>
                                            <td>
                                            <div class="tw-flex tw-items-center tw-space-x-3">
                                                <?php
                                                   // if(has_permission('generate_salary_slip','','view') || is_admin()){
                                                ?>
                                                        <a href="<?php echo admin_url('eraxon_payroll/salary_slip_detail/' . $salar_detail->id); ?>" class="tw-mt-px tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 ">
                                                            <!-- <i class="fa-regular fa-trash-can fa-lg"></i> -->
                                                            View
                                                        </a>
                                            </div>
                                            </td>
                                        </tr>
                            <?php
                                    endforeach;
                                }
                           // }
                            ?>
                            	
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

</div>