<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<div class="panel_s">
					<div class="panel-body">
						<?php echo form_open(admin_url('eraxon_payroll/save_targets')); ?>
						<div class="modal-body">
							<div class="tab-content">
								<div role="tabpanel" class="tab-pane active" id="tab_staff_profile">
									<h4>Targets</h4>
									<hr/>
									<div class="clearfix"></div>
									<br>
									<div class="clearfix"></div>
									<input type="hidden" id="id" name="id" value="<?php echo isset($target->id) ? $target->id : ''; ?>">
									<div class="row">
										<div class="col-md-12">
											<div class="form-group" app-field-wrapper="staff_identifi">
												<label for="name" class="control-label"><span class="text-danger">* </span>Target Name</label>
												<input type="text" id="name" name="name" required class="form-control" value="<?php echo set_value('name',isset($target) ? $target->name : ''); ?>" aria-invalid="false" >
											</div>
										</div> 
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="target" class="control-label"><span class="text-danger">*</span>Target Leads</label>
												<input type="number" id="target" name="target" required class="form-control" value="<?php echo set_value('name',isset($target) ? $target->target : ''); ?>" aria-invalid="false" >
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group" app-field-wrapper="email">
												<label for="bonus" class="control-label"><span class="text-danger">*</span> Bonus</label>
												<input type="number" id="bonus" required name="bonus" class="form-control" autocomplete="off" value="<?php echo set_value('amount', isset($target) ? $target->bonus : ''); ?>" >
											</div>
										</div>
									</div>
                                    <div class="row">
                                    <div class="col-md-6">
											<div class="form-group" app-field-wrapper="email">
												<label for="accumulative_bonus" class="control-label"><span class="text-danger">*</span> Accumulative Bonus</label>
												<input type="number" id="accumulative_bonus" name="accumulative_bonus" class="form-control" autocomplete="off" value="<?php echo set_value('amount', isset($target) ? $target->accumulative_bonus : ''); ?>" >
											</div>
										</div>
										<div class="col-md-6">
                                            <div class="form-group">
												<input type="checkbox" id="status" name="status" value="" <?php echo (isset($target) && $target->status=="Active") ? "checked" : ''; ?>>
												<label for="status" class="control-label">Active</label>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<a href="<?php echo admin_url('eraxon_payroll/targets'); ?>"  class="btn btn-default mr-2 "><?php echo _l('hr_close'); ?></a>
								<button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
							</div>
							<?php echo form_close(); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="btn-bottom-pusher"></div>
	</div>
	<?php init_tail(); ?>
</body>
</html>
