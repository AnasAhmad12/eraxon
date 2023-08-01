<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper" >
	<div class="content">
		<div class="row">

			<div class="col-md-10 col-md-offset-1">
				<div class="panel_s">
					<div class="panel-body">
						
						<?php echo form_open_multipart(admin_url('eraxon_payroll/save_deductions')); ?>
						<div class="modal-body">

							<div class="tab-content">
								<div role="tabpanel" class="tab-pane active" id="tab_staff_profile">

									<?php if(total_rows(db_prefix().'emailtemplates',array('slug'=>'two-factor-authentication','active'=>0)) == 0){ ?>
										<!-- <div class="checkbox checkbox-primary">
											<input type="checkbox" value="1" name="two_factor_auth_enabled" id="two_factor_auth_enabled"<?php if(isset($member) && $member->two_factor_auth_enabled == 1){echo ' checked';} ?>>
											<label for="two_factor_auth_enabled"><i class="fa fa-question-circle" data-toggle="tooltip" data-title="<?php echo _l('two_factor_authentication_info'); ?>"></i>
												<?php echo _l('enable_two_factor_authentication'); ?></label>
											</div> -->
										<?php } ?>
										
                                    <h4>Deductions</h4>
                                    <hr/>

									<div class="clearfix"></div>
									<br>
									<div class="clearfix"></div>
									<input type="hidden" id="id" name="id" value="<?php echo isset($deduction->id) ? $deduction->id : ''; ?>">
									<div class="row">

										<div class="col-md-12">
											<div class="form-group" app-field-wrapper="staff_identifi">
												<label for="deductions_name" class="control-label"><span class="text-danger">* </span>Deduction Name</label>
												<input type="text" id="deductions_name" required name="name" class="form-control" value="<?php echo set_value('name',isset($deduction) ? $deduction->name : ''); ?>" aria-invalid="false" >
											</div>
										</div> 
										
									</div>

									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="type" class="control-label"><span class="text-danger">*</span> Deduction Type</label>
												<select name="type" class="selectpicker" required id="type" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>"> 
													<option value="Fixed" <?php echo set_value('type', isset($deduction) && $deduction->type == 'Fixed' ? 'selected' : ''); ?>>Fixed</option>
													<option value="Percentage" <?php echo set_value('type', isset($deduction) && $deduction->type == 'Percentage' ? 'selected' : ''); ?>>Percentage %</option>
												</select>
											</div>
										</div>
                                        <div class="col-md-6">
											<div class="form-group" app-field-wrapper="email">
												<label for="amount" class="control-label"><span class="text-danger">*</span> Fixed amount or %</label>
												<input type="amount" id="amount" name="amount" required class="form-control" autocomplete="off" value="<?php echo set_value('amount', isset($deduction) ? $deduction->amount : ''); ?>" >
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="modal-footer">
								<a href="<?php echo admin_url('eraxon_payroll/deductions'); ?>"  class="btn btn-default mr-2 "><?php echo _l('hr_close'); ?></a>
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
	<?php 
	// require('modules/hr_profile/assets/js/hr_record/add_update_staff_js.php');
	// require('modules/hr_profile/assets/js/hr_record/add_staff_js.php');
	?>
</body>
</html>