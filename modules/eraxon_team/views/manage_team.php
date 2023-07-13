<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="panel_s">
					 <div class="panel-body">
						<?php echo form_open_multipart(admin_url('eraxon_team/add_edit_team'), array('id' => 'add_edit_team')); ?>
							<div class="modal-body">
								<div class="row">

									<?php $value = (isset($team) ? $team->teamname : '');  ?>
									<div class="col-md-12">
											<?php echo render_input('teamname','Team Name',$value,'text',$attrs); ?>
									</div>
									<div class="col-md-12">
							            <label for="position"><?php echo _l('staff'); ?></label>
							            <select name="staff[]" id="staff" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('all'); ?>" data-hide-disabled="true" data-live-search="true" multiple="true">

							             <?php foreach($staffs as $dpm){
							              $selected = '';
							              if(in_array($dpm['staffid'], $staff)){
							                $selected = 'selected';
							              }
							              ?>
							              <option <?php echo html_entity_decode($selected); ?>  value="<?php echo html_entity_decode($dpm['staffid']); ?>"><?php echo html_entity_decode($dpm['firstname']) . ' '.$dpm['lastname']; ?></option>
							            <?php } ?>   
							          </select>

							        </div>
								</div>
							

							


							<div class="modal-footer">
								<a href="<?php echo admin_url('eraxon_team/teams'); ?>"  class="btn btn-default mr-2 "><?php echo _l('hr_close'); ?></a>
								<button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
							</div>	
						<?php echo form_close(); ?>
						</div>
					</div> 
				</div>	
			</div>
		</div>
	</div>
</div>


<?php init_tail(); ?>
<?php require 'modules/eraxon_team/assets/js/manage_team_js.php'; ?>
</body>
</html>