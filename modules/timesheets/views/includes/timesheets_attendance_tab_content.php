
 <input type="hidden" name="staffid" value="<?php echo html_entity_decode($staffid); ?>">
<div class="row">
     <input type="hidden" name="current_month" value="<?php echo date('Y-m'); ?>">
      
       <div class="row filter_by">
        <div class="col-md-2 leads-filter-column">
          <?php echo render_input('month_timesheets','month',date('Y-m'), 'month'); ?>
        </div>
       
        <div class="col-md-1 mtop25">
          <button type="button" class="btn btn-info timesheets_filter2"><?php echo _l('filter'); ?></button>
        </div>                         
      </div>  
     <div class="clearfix"></div>
     <br>
       <div class="col-md-12" id="summary_table">
         
       </div>
     <div class="clearfix"></div>
     <br>
        <div class="col-md-12 table-responsive" id="attendance_sheet_table">
         
       </div>
     <div class="clearfix"></div>
     <br>
      <div class="col-md-12">
        <h3>Working Hours</h3>
        <div class="form">    
          <div class="hot handsontable htColumnHeaders" id="example">
          </div>
          <?php echo form_hidden('time_sheet'); ?>
          <?php echo form_hidden('month', date('m-Y')); ?>
          <?php echo form_hidden('latch'); ?>
          <?php echo form_hidden('unlatch'); ?>
          <?php echo form_hidden('is_edit', 0); ?>
        </div>
      </div>


</div>

  <div class="modal" id="timesheets_detail_modal" tabindex="-1" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content width-100">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 id='title_detail'>
              <?php echo _l('detail'); ?>
            </h4>
          </div>
          <div class="modal-body">
            <ul class="list-group" id="ul_timesheets_detail_modal">
            </ul>
          </div>
          <div class="modal-footer">
            <button type="" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->  

<?php 



?>