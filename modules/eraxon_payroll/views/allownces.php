<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper" >
	<div class="content">
		<div class="row">

			<div class="col-md-10 col-md-offset-1">
				<div class="panel_s">
					<div class="panel-body">
						
						<?php echo form_open(admin_url('eraxon_payroll/save_allownces')); ?>
						<div class="modal-body">

							<div class="tab-content">
								<div role="tabpanel" class="tab-pane active" id="tab_staff_profile">
										
                                    <h4>Allownces</h4>
                                    <hr/>

									<div class="clearfix"></div>
									<br>
									<div class="clearfix"></div>
									<input type="hidden" id="id" name="id" value="<?php echo isset($allowance->id) ? $allowance->id : ''; ?>">
									<div class="row">

										<div class="col-md-12">
											<div class="form-group" app-field-wrapper="staff_identifi">
												<label for="name" class="control-label"><span class="text-danger">* </span>Allownce Name</label>
												<input type="text" id="name" name="name" required class="form-control" value="<?php echo set_value('name',isset($allowance) ? $allowance->name : ''); ?>" aria-invalid="false" >
											</div>
										</div> 
										
									</div>

									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="type" class="control-label"><span class="text-danger">*</span> Allownce Type</label>
												<select name="type" required class="selectpicker" id="type" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>"> 
													<option value="Fixed" <?php echo set_value('type', isset($allownce) && $allownce->type == 'Fixed' ? 'selected' : ''); ?> >Fixed</option>
													<option value="Percentage" <?php echo set_value('type', isset($allowance) && $allowance->type == 'Percentage' ? 'selected' : ''); ?>>Percentage %</option>
												</select>
											</div>
										</div>
                                        <div class="col-md-6">
											<div class="form-group" app-field-wrapper="email">
												<label for="email" class="control-label"><span class="text-danger">*</span> Fixed amount or %</label>
												<input type="amount" id="email" required name="amount" class="form-control" autocomplete="off" value="<?php echo set_value('amount', isset($allowance) ? $allowance->amount : ''); ?>" >
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="modal-footer">
								<a href="<?php echo admin_url('eraxon_payroll/allownces'); ?>"  class="btn btn-default mr-2 "><?php echo _l('hr_close'); ?></a>
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