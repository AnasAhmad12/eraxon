<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6 mt-5">
                                <h4>
                                    <?php echo '<i class=" fa fa-clipboard"></i> Approval Settings ' ?>
                                </h4>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <?php echo form_open(admin_url('eraxon_assets/set_settings')) ?>

                        <div class="row">
                            <div class="col-md-2">
                                <h5> Stock In Purchase Approval : </h5>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <select name="purchase_approval" data-live-search="true" id="staffid"
                                        class="form-control selectpicker">
                                        <?php foreach ($staff_members as $staff) { ?>
                                            <option value="<?php echo $staff['staffid']; ?>">
                                                <?php echo $staff['full_name'] . ' (' . get_custom_field_value($staff['staffid'], 'staff_pseudo', 'staff', true) . ')'; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-2">
                                <h5> Loss Stock Approval : </h5>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <select name="loss_approval" data-live-search="true" id="staffid"
                                        class="form-control selectpicker">
                                        <?php foreach ($staff_members as $staff) { ?>
                                            <option value="<?php echo $staff['staffid']; ?>">
                                                <?php echo $staff['full_name'] . ' (' . get_custom_field_value($staff['staffid'], 'staff_pseudo', 'staff', true) . ')'; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                                <button type="submit" id="setting_button" class="btn btn-primary">
                                    <?php echo _l('submit'); ?>
                                </button>
                            </div>
                        <?php echo form_close() ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>

<script>


 </script>