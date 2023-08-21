<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<div class="panel_s">
					<div class="panel-body">
						<?php echo form_open(admin_url('eraxon_payroll/add_payroll_adjustment')); ?>
						<div class="modal-body">
							<div class="tab-content">
								<div role="tabpanel" class="tab-pane active" id="tab_staff_profile">
									<h4>Add Adjustments into the Payslip</h4>
									<hr/>
									<div class="clearfix"></div>
									<div class="row">
										<div class="col-md-12">
											<?php if(!empty($salary_details)) { 
								                foreach($salary_details as $salary_detail) { 
												$pdfName = $salary_detail->name.'('.date('F Y', strtotime($salary_detail->date)).')';
								                    ?>
								                    	<table class="table table-bordered">
								                        <tr>
								                            <td>Full Name</td>
								                            <td>Job Group</td>
								                            <td>Grade</td>
								                            <td>Salary Month</td>
								                            
								                        </tr>
								                        <tr>
								                            <td><?php echo $salary_detail->name.' ( '.get_custom_field_value($salary_detail->staffid,'staff_pseudo','staff',true).' )'; ?></td>
								                            <td><?php echo $salary_detail->rolename; ?></td>
								                            <td><?php echo hr_profile_get_job_position_name($salary_detail->job_position) ?></td>
								                             <td><?php echo date('F Y', strtotime($salary_detail->date)).' <span class="">('.$salary_detail->status.')</span>'; ?></td>
								                        </tr>
								                         
								                    </table>

								            <?php }} ?>        			
										</div>
									</div>
									<div class="clearfix"></div>
									<input type="hidden" id="id" name="salary_details_id" value="<?php echo isset($salary_slip_id) ? $salary_slip_id : ''; ?>">
									<div class="row">
										<div class="col-md-12">
											<div class="form-group" app-field-wrapper="staff_identifi">
												<label for="name" class="control-label">Adjustment Name</label>
												<input type="text" id="name" name="name" required class="form-control" value="<?php echo set_value('name',isset($adj_detail) ? $adj_detail->name : ''); ?>" aria-invalid="false" >
											</div>
										</div> 
									</div>
									<div class="row">
										<div class="col-md-6">												
											
											<div class="dropdown bootstrap-select bs3" style="width: 100%;"> 
												
												 <label for="type" class="control-label">Type</label> 
												<div class="dropdown bootstrap-select bs3" style="width: 100%;">  
												<select id="type" name="type" class="selectpicker" data-width="100%" data-none-selected-text="None selected" data-live-search="true" tabindex="-98">
													<option value="">Select Type</option>
													<option value="add">Addition</option>
													<option value="deduct">Deduction</option>
												</select>
											</div> 
											
										</div>
									</div>
										<div class="col-md-6">
											<div class="form-group" app-field-wrapper="email">
												<label for="amount" class="control-label">Amount</label>

																									
												<input type="number" id="amount" name="amount" required class="form-control" value="<?php echo set_value('amount',isset($adj_detail) ? $adj_detail->name : ''); ?>" aria-invalid="false" >

												 
												
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

