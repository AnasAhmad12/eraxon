<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
        <?php echo form_open(admin_url('eraxon_payroll/save_bonuses_settings')); ?>
            <div class="col-md-6">
                <h4>Bonuses</h4>
                <?php
                if(!empty($bonuses)):
                    foreach($bonuses as $bonus): ?>
                    <div class="">
                        <label>
                            <input type="checkbox" name="bonuses[]" value="<?php echo $bonus->id; ?>" 
                            <?php echo in_array($bonus->id, $general_bonuses) ? 'checked' : ''; ?>>
                            <?php echo $bonus->name; ?>
                        </label>
                    </div>
                <?php endforeach;
                    endif;
                ?>
            </div>
            <div class="col-md-6">
            <?php echo render_select('employee',$employees,array('staffid', 'name'),'Employee'); ?>
            <h4>Bonuses</h4>
            <?php
                if(!empty($bonuses)):
                    foreach($bonuses as $bonus): ?>
                    <div class="">
                        <label>
                            <input type="checkbox" name="employee_bonuses[]" value="<?php echo $bonus->id; ?>">
                            <?php echo $bonus->name; ?>
                        </label>
                    </div>
                <?php endforeach;
                    endif;
                ?>
            </div>
            <button type="submit" class="btn btn-primary" style="margin-bottom: 10px;">Save</button>
            <div class="col-md-12 mtop-30">
            <table class="table dt-table" data-order-col="0" data-order-type="asc">
            <thead>
                <th>Employee Name</th>
                <th>Bonuses</th>
                <th><?php echo _l('options'); ?></th>
            </thead>
            <tbody>
                <?php 
                $grouped_bonuses = [];
                foreach($employee_bonuses as $bonus) {
                    $grouped_bonuses[$bonus['employee_id']]['employee_name'] = $bonus['employee_name'];
                    $grouped_bonuses[$bonus['employee_id']]['bonuses'][] = $bonus['name'];
                }
                
                foreach($grouped_bonuses as $employee_id => $bonus): ?>
                    <tr>
                        <td><?php echo $bonus['employee_name']; ?></td>
                        <td><?php echo implode(',', $bonus['bonuses']); ?></td>
                        <td>
                        <?php
                            if(has_permission('deductions','','delete') || is_admin()){
                        ?>
                            <a href="<?php echo admin_url('eraxon_payroll/delete_employee_bonuses/' . $employee_id); ?>" class="tw-mt-px tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 _delete">
                                <i class="fa-regular fa-trash-can fa-lg"></i>
                            </a>
                        <?php } ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                
            </tbody>
            </table>
            </div>
        <?php echo form_close(); ?>
        </div>
    </div>
</div>
<?php init_tail(); ?>
