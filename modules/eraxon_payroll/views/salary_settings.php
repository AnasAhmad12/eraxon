<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                    	
                        <?php echo form_open(admin_url('eraxon_payroll/salary_settings')); ?>
                         
                        <div class="row" id="extra">
                        	<div class="col-md-4">
                            <label for="exceptions" class="control-label">Add Date Exception to "Turn ON" Sundays</label>
                            <div class="input-group date">	
                                <input type="text" class="form-control datepicker" name="exceptions" autocomplete="off"> 
                                <div class="input-group-addon">
							        <i class="fa-regular fa-calendar calendar-icon"></i>
							    </div>
                            </div>
                               <!--  <a class="btn-primary" onClick ="add_date()" id="add_btn">ADD</a>  --> 
                               

                            </div>       

                                
                        <div class="col-md-4">   
                         <div class="form-group" app-field-wrapper="time_start_work">
                         	<label for="time_start_work" class="control-label">Shift Start time</label>
                         	<input type="time" id="time_start_work" name="time_start_work" class="form-control" value="">
                         </div>

                        </div>
                        <div class="col-md-4">   
                          <div class="form-group" app-field-wrapper="time_end_work">
                         	<label for="time_end_work" class="control-label">Shift end time</label>
                         	<input type="time" id="time_end_work" name="time_end_work" class="form-control" value="">
                         </div> 

                         </div>	
                        </div>

                        <div class="extra2">
                        	
                        </div>
                        <div class="row">
                        	<button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                    	</div>
                        <?php echo form_close(); ?>

                    </div>
                </div>


                <div class="row">
                	<div class="col-md-12">
                		<table class="table table-striped">
                			<thead>
                				<tr>
                					<th>Excetion Date</th>
                					<th>Shift Start Time</th>
                					<th>Sift End Time</th>
                					<th>Action</th>
                				</tr>
                			</thead>
                			<tbody>
                				<?php if(isset($exceptions)){ 

                					foreach ($exceptions as $key ) {
                					
                					?>
                				<tr>
                					<th><?php echo $key['exceptions'] ?></th>
                					<th><?php echo $key['time_start_work'] ?></th>
                					<th><?php echo $key['time_end_work'] ?></th>
                					<th><a href="<?php echo admin_url('eraxon_payroll/delete_exception/').$key['id']; ?>">Delete</a></th>
                				</tr>

                			<?php }
                				} ?>
                			</tbody>
                		</table>
                	</div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
</body>

<script>
	function add_date(){

		//var html = '<div class="extra_add input-group date"><div class="form-group"><input type="text" class="form-control datepicker1" name="exceptions[]"></div><div class="input-group-addon"><i class="fa-regular fa-calendar calendar-icon"></i></div> <a class="btn-danger delete_btn"><i class="fa fa-trash"></i></a></div>';

		//$("#extra").append(html);
		//$(".datepicker1" ).datepicker({ dateFormat: 'yy-mm-dd' });
		
		$( "#extra" ).clone().appendTo( ".extra2" );
	}

	$(document).on('click', 'a.delete_btn', function()
	{
		$(this).closest('.extra_add').remove();

	});
</script>
</html>