<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <?php echo form_open(admin_url('utilities/selecthr')); ?>
                            
                                 <label for="position">Select HR for Notification</label>
                                        <select name="staffid" id="staffid" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo 'Select HR '; ?>" data-hide-disabled="true" data-live-search="true" >

                                         <?php foreach($staffs as $dpm){
                                          $selected = '';
                                          if($dpm['staffid'] ==  $staff)
                                          {
                                            $selected = 'selected';
                                          }
                                          ?>
                                          <option <?php echo html_entity_decode($selected); ?>  value="<?php echo html_entity_decode($dpm['staffid']); ?>"><?php echo html_entity_decode($dpm['firstname']) . ' '.$dpm['lastname']; ?></option>
                                        <?php } ?>   
                                      </select>
                                      <hr>
                            <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
</body>

</html>