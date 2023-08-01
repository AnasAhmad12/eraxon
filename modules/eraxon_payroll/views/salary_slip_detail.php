<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <?php if(!empty($salary_details))
            { 
                foreach($salary_details as $salary_detail){
                ?>
                <div class="col-md-3">
                    <h4>Name</h4>
                    <p><?php echo $salary_detail->name; ?></p>
                    <hr/>
                    <h4>Role</h4>
                    <p><?php echo $salary_detail->rolename; ?></p>
                </div>
                <div class="col-md-3">
                    <h4>Allowances</h4>
                    <?php 
                        if(!empty($allowances))
                        {
                            foreach($allowances as $allowance){
                    ?>
                        <p><?php echo $allowance->name; ?> - <?php echo $allowance->allowance_amount; ?></p>
                    <?php
                            }
                        }
                    ?>
                    <hr/>
                    <h4>Deductions</h4>
                    
                    <?php 
                        if(!empty($deductions))
                        {
                            foreach($deductions as $deduction){
                    ?>
                        <p><?php echo $deduction->name; ?> - <?php echo $deduction->deduction_amount; ?></p>
                    <?php
                            }
                        }
                    ?>
                </div>
                <div class="col-md-3">
                    <h4>Attendance</h4>
                    <p><?php echo "Half Leaves"." - ".$salary_detail->half_leaves; ?></p>
                    <p><?php echo "Leaves"." - ".$salary_detail->leaves; ?></p>
                    <p><?php echo "Sandwitch Leaves"." - ".$salary_detail->sandwitch; ?></p>
                    <p><?php echo "Total Leaves"." - ".$salary_detail->total_leaves; ?></p>
                </div>
                <div class="col-md-3">
                    <h4>Basic Salary</h4>
                    <p><?php echo $salary_detail->basic_salary; ?></p>
                    <hr/>
                    <h4>Gross Salary</h4>
                    <p><?php echo $salary_detail->gross_salary; ?></p>
                    <hr/>
                    <h4>Net Salary</h4>
                    <p><?php echo $salary_detail->net_salary; ?></p>
                </div>
            <?php 
            }} ?>
        </div>
    </div>
</div>
<?php init_tail(); ?>