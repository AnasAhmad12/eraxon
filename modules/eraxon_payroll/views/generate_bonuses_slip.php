<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
            <?php echo form_open(admin_url('eraxon_payroll/generate_bonus_slips')); ?>
                <div class="row filter_by">
                    <div class="col-md-2 leads-filter-column" style="margin-bottom:10px;">
                    <?php echo render_input('month_timesheets','month',date('Y-m'), 'month'); ?>
                    </div>
                    <div class="col-md-3 leads-filter-column" style="margin-bottom:10px;">
                    <?php echo render_select('role_id',$roles,array('roleid', 'name'),'role'); ?>
                    </div>
                 <div>
                <div class="tw-mb-2 sm:tw-mb-4 mtop25 text-right">
                    <?php if(has_permission('generate_bonus_slip','','create') || is_admin()){ ?>
                    <!-- <a href="<?php echo admin_url('eraxon_payroll/generate_bonus_slips'); ?>" class="btn btn-success"> -->
                        <!-- <i class="fa-regular fa-plus tw-mr-1"></i> -->
                        <!-- <?php echo "Generate Salary Slip"; ?>
                    </a> -->
                    <button type="submit" class="btn btn-success"><?php echo "Generate Bonus Slip"; ?></button>
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
                                <th>Performance Bonus</th>
                                <th>Bonus</th>
                                <th>Total Bonus</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                            <?php
                                if(has_permission('generate_salary_slip','','view') || is_admin()){  
                                    if(!empty($bonus_details)){
                                        foreach ($bonus_details as $bonus_detail):
                            ?>
                                        <tr>
                                            <td><?php echo date('Y-m', strtotime($bonus_detail->month_year)); ?></td>
                                            <td><?php echo $bonus_detail->name; ?></td>
                                            <td><?php echo $bonus_detail->performance_bonus; ?></td>
                                            <td><?php echo $bonus_detail->bonus; ?></td>
                                            <td><?php echo $bonus_detail->total_bonus; ?></td>
                                            <td>
                                            <div class="tw-flex tw-items-center tw-space-x-3">
                                                <?php
                                                    if(has_permission('generate_salary_slip','','view') || is_admin()){
                                                ?>
                                                        <a href="<?php echo admin_url('eraxon_payroll/salary_slip_detail/' . $bonus_detail->id); ?>" class="tw-mt-px tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 ">
                                                            <!-- <i class="fa-regular fa-trash-can fa-lg"></i> -->
                                                            View
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
<?php init_tail(); ?>
