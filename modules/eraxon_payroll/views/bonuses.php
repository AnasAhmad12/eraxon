<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper" >
	<div class="content">
		<div class="row">

			<div class="col-md-10 col-md-offset-1">
				<div class="panel_s">
					<div class="panel-body">
						
						<?php echo form_open(admin_url('eraxon_payroll/save_bonuses')); ?>
						<div class="modal-body">

							<div class="tab-content">
								<div role="tabpanel" class="tab-pane active" id="tab_staff_profile">
										
                                    <h4>Bonuses</h4>
                                    <hr/>

									<div class="clearfix"></div>
									<br>
									<div class="clearfix"></div>
									<input type="hidden" id="id" name="id" value="<?php echo isset($bonus->id) ? $bonus->id : ''; ?>">
									<div class="row">

										<div class="col-md-12">
											<div class="form-group" app-field-wrapper="staff_identifi">
												<label for="name" class="control-label"><span class="text-danger">* </span>Bonus Name</label>
												<input type="text" id="name" name="name" required class="form-control" value="<?php echo set_value('name',isset($bonus) ? $bonus->name : ''); ?>" aria-invalid="false" >
											</div>
										</div> 
										
									</div>

									<div class="row">
                                        <div class="col-md-12">
											<div class="form-group" app-field-wrapper="email">
												<label for="amount" class="control-label"><span class="text-danger">*</span> Amount</label>
												<input type="amount" id="amount" required name="amount" class="form-control" autocomplete="off" value="<?php echo set_value('amount', isset($bonus) ? $bonus->amount : ''); ?>" >
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="modal-footer">
								<a href="<?php echo admin_url('eraxon_payroll/bonuses'); ?>"  class="btn btn-default mr-2 "><?php echo _l('hr_close'); ?></a>
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
