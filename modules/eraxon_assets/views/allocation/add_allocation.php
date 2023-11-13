<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<style>
    #item-code {
        padding: 1.5rem;
    }
</style>
<?php init_head(); ?>
<?php echo form_open( $this->uri->uri_string(),["id"=>"allocation_form"]); ?>

<div id="wrapper">

    <div class="content">

        <div class="panel_s section-heading section-products">
            <div class="panel-body">
                <h4 class="no-margin section-text">
                    <?php echo "$title" ?>
                </h4>
            </div>
        </div>

        <div class="panel_s section-heading section-products">
            <div class="panel-body align-content-center">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Select Staff </label>
                        <select name="allocated_staff_id" data-live-search="true" id="staffid"
                            class="form-control selectpicker">
                            <?php foreach ($staff_members as $staff) { ?>
                                <option value="<?php echo $staff['staffid']; ?>">
                                    <?php echo $staff['full_name'] . ' (' . get_custom_field_value($staff['staffid'], 'staff_pseudo', 'staff', true) . ')'; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <?php echo render_input('payment_date', "Purchase Date", "", "date"); ?>
                    <span id="date-validation"></span>
                </div>


                <div class="col-md-12 input-group">
                    <span class="input-group-addon">
                        <i class="fas fa-barcode"></i></span>
                    <?php echo render_input('item-code', "", "", "", ["Placeholder" => "Enter Item Name ",], ); ?>
                </div>

            </div>

        </div>
        <?php if (!empty($message)) { ?>
            <div class="alert alert-danger">
                <?php echo $message; ?>
            </div>
        <?php } ?>


        <?php $total = 0; ?>
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="#myTable" class="table items items-preview invoice-items-preview"
                                        data-type="invoice">
                                        <thead>
                                            <tr>
                                                <th align="center">#</th>
                                                <th class="description" width="50%" align="left">
                                                    <?php echo _l('item') ?>
                                                </th>

                                                <th>
                                                    <?php echo "Quantity" ?>
                                                </th>

                                                <th>
                                                    <?php echo "Serial No" ?>
                                                </th>

                                                <th align="right">
                                                    <?php echo "Action" ?>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="table-body">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>

                <div class="panel_s section-heading section-products">
                    <div class="panel-body">
                        <div class="row">
                            <button type="submit" class="btn btn-success pull-right" id="purchase-button"><i class="fa fa-check"></i>
                                <?php echo "Allocate"; ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <?php echo form_close(); ?>
    <?php init_tail(); ?>

    <?php if (!empty($message)) { ?>
        <script type="text/javascript">
            $(function () {
                // alert_float('warning','<?php echo $message; ?>',6000);
            });
        </script>
    <?php } ?>


    <?php require('modules/Eraxon_assets/assets/js/allocation_js.php'); ?>