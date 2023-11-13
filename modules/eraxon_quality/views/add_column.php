<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<style type="text/css">
	@media only screen and (max-width: 600px) {
		.delete_col{
		margin-top: 0px !important;
	}

	}
	
     .delete_col{
		margin-top: 22px;
	}
</style>
<div id="wrapper" >
	<div class="content">
		<div class="row">

			<div class="col-md-10 col-md-offset-1">
				<div class="panel_s">
					<div class="panel-body">
						
						<?php echo form_open(admin_url('eraxon_quality/manage_column/save')); ?>
						<div class="modal-body">

							<div class="tab-content">
								<div role="tabpanel" class="tab-pane active" id="tab_staff_profile">
										
                                    <h4>Set New Columns </h4>
                                    <hr/>

									<div class="clearfix"></div>
									<br>
									<div class="clearfix"></div>
									<?php
										$selected = '';
										/*foreach($campaign_types as $camp){
											if(isset($member)){
												if($member->camp_type_id == $camp->'camp_type_id'){
													$selected = $camp->'roleid';
												}
											} 
										}*/
										?>
									<div class="row">
										<div class="col-md-12">
											<?php echo render_select('camp_type_id',$campaign_types,array('id','name'),'Select Campaign Type',$selected); ?>	
											
										</div> 
									</div>

									<div class="row">
										<div class="col-md-12">
										<span id="add_column" class="btn btn-info">Add Column</span>
									    </div>
									</div>
									<div class="clearfix"></div>
									<br>
									<div class="clearfix"></div>
									<div id="columns_space">
									<div class="row" >
										 <div class="col-md-5">
										 	<div class="form-group">
										 		<label>Column Name</label>
										 		<input type="text" name="columns[]" class="form-control" value="Date" readonly />
										 	</div>
										 </div>
										 <div class="col-md-5">
										 	<div class="form-group">
										 		<label>Column Slug</label>
										 		<input type="text" name="slugs[]" class="form-control" value="date" readonly/>
										 	</div>
										 </div>
										 <div class="col-md-2">
										 	&nbsp;
										 </div>
									</div>
									<div class="row" >
										 <div class="col-md-5">
										 	<div class="form-group">
										 		<label>Column Name</label>
										 		<input type="text" name="columns[]" class="form-control" value="Agent Name" readonly />
										 	</div>
										 </div>
										 <div class="col-md-5">
										 	<div class="form-group">
										 		<label>Column Slug</label>
										 		<input type="text" name="slugs[]" class="form-control" value="agent_name" readonly/>
										 	</div>
										 </div>
										 <div class="col-md-2">
										 	&nbsp;
										 </div>
									</div>
									<div class="row" >
										 <div class="col-md-5">
										 	<div class="form-group">
										 		<label>Column Name</label>
										 		<input type="text" name="columns[]" class="form-control" value="Phone Number" readonly />
										 	</div>
										 </div>
										 <div class="col-md-5">
										 	<div class="form-group">
										 		<label>Column Slug</label>
										 		<input type="text" name="slugs[]" class="form-control" value="phone_number" readonly/>
										 	</div>
										 </div>
										 <div class="col-md-2">
										 	&nbsp;
										 </div>
									</div>
									</div>
								</div>
							</div>

							<div class="modal-footer">
								<a href="<?php echo admin_url('eraxon_quality/manage_column'); ?>"  class="btn btn-default mr-2 "><?php echo _l('hr_close'); ?></a>
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
<script>
	$(function() {
	    appValidateForm($('form'), {
	        camp_type_id: 'required',
	        columns:'required',
	        slugs:'required',
	    }, manage_column_form);	   
	});

	function manage_column_form(form) {
	    var data = $(form).serialize();
	    var url = form.action;
	    
	   $.post(url, data).done(function(response) {
	        console.log(response);
	    });
	    return false;
	}
	$(document).on('click','#add_column',function(){
		html = '';
		html +=`<div class="row" > <div class="col-md-5">
					<div class="form-group">
						<label>Column Name</label>
							<input type="text" name="columns[]" class="form-control" />
					</div>
					</div>
					<div class="col-md-5">
					<div class="form-group">
					<label>Column Slug</label>
					<input type="text" name="slugs[]" class="form-control" />
					</div>
					</div>
					<div class="col-md-2">
					<span class="btn btn-primary delete_col" ><i class="fa-regular fa-trash-can fa-lg"></i></span>
				</div></div>`;
		$("#columns_space").append(html);		
	});

	$(document).on('click','.delete_col',function(){

		$(this).closest('.row').remove();
	});
</script>

</body>
</html>