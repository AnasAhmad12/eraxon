<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<style>
    #item-code {
        padding: 1.5rem;
    }
</style>
<?php init_head(); ?>
<?php echo form_open_multipart($this->uri->uri_string(),["id"=>"stockin_form"]); ?>

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
                        <label for="payment_status">Select Status</label>
                        <select class="form-control" name="payment_status">
                            <option selected value="0">Pending</option>
                            <?php if( is_admin() || get_option('asset_stock_in_purchase_approval')==get_staff_user_id()){ ?>
                            <option value="1">Approved</option>
                            <?php }?>
                        </select>
                        <span id="status-validation"></span>
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
                                                    <?php "Quantity" ?>
                                                </th>

                                                <th>
                                                    <?php echo "Serial No" ?>
                                                </th>
                                                <th>
                                                    <?php echo "Rate" ?>
                                                </th>
                                                <th align="right">
                                                    <?php echo "Subtotal" ?>
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
                       
                        <div class="row">
                            <div class="col-md-6 col-md-offset-6">
                                <table class="table text-right">
                                    <tbody>
                                        <tr id="subtotal">
                                            <td>
                                                <span class="bold">
                                                    <?php echo _l('subtotal'); ?>
                                                </span>
                                            </td>
                                            <td class="subtotal" id="items-subtotal">

                                            </td>
                                        </tr>
                                        <tr id="coupon_discount" class="hide">
                                            <td>
                                                <span class="bold">
                                                    <?php echo _l('coupon_discount'); ?>
                                                </span>
                                                <?php echo form_hidden('coupon_id', ''); ?>
                                            </td>
                                            <td class="coupon_discount">
                                            </td>
                                        </tr>
                                        <?php foreach ($all_taxes as $tax) { ?>
                                            <tr class="tax-area">
                                                <td>
                                                    <span class="bold">
                                                        <?php echo htmlspecialchars($tax['taxname'] . '(' . $tax['taxrate'] . '%)'); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php $total += array_sum($init_tax[$tax['name']]);
                                                    echo app_format_money(array_sum($init_tax[$tax['name']]), $base_currency->name); ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <?php if (!empty($shipping_cost)) { ?>
                                            <tr id="shipping_costs">
                                                <td>
                                                    <span class="bold">
                                                        <?php
                                                        echo _l('shipping_costs');
                                                        echo "(" . app_format_money($shipping_base, $base_currency->name) . "+" . $shipping_tax . "%)";
                                                        ?>
                                                    </span>
                                                </td>
                                                <td class="shipping_costs">
                                                    <?php echo form_hidden('shipping_cost', $shipping_cost); ?>
                                                    <?php echo app_format_money($shipping_cost, $base_currency->name); ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <tr>
                                            <td><span class="text-danger bold">
                                                    <?php echo _l('payable_amount'); ?>
                                                </span></td>
                                            <td>
                                                <span class="text-danger total" id="payable-amount"></span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel_s section-heading section-products">
                    <div class="panel-body">
                        <div class="row">
                            <!--  -->
                            <div class="col-md-3 <?php echo htmlspecialchars($input_file_class); ?>">
                                <div class="attachment">
                                    <div class="form-group">
                                        <label for="attachment" class="control-label"><small class="req text-danger">*
                                            </small>
                                            <?php echo "Invoice Image" ?>
                                        </label>
                                        <input type="file" extension="png,jpg,jpeg,gif"
                                            filesize="<?php echo file_upload_max_size(); ?>" class="form-control"
                                            name="invoice_image" id="product">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <?php echo render_input('invoice_number', 'Invoice Number', $product->quantity_number ?? '', 'text', ["id"=> "invoice-image"]); ?>
                            </div>
                        </div>
                        <button class="btn btn-success pull-right" id="purchase-button"><i class="fa fa-check"></i>
                            <?php echo "Purchase"; ?>
                        </button>
                    </div>
                </div>

             
              

            </div>
        </div>

    </div>
</div>
<?php echo form_close(); ?>
<?php init_tail(); ?>

<script type="text/javascript" src="<?php echo base_url('assets/plugins/accounting.js/accounting.js'); ?>"></script>
<script type="text/javascript">
    "use strict";
    // var base_currency = <?php echo htmlspecialchars($base_currency->id); ?>
</script>
<?php if (!empty($message)) { ?>
    <script type="text/javascript">
        $(function () {
            // alert_float('warning','<?php echo $message; ?>',6000);
        });
    </script>
<?php } ?>



<?php require('modules/Eraxon_assets/assets/js/stock_in_js.php'); ?>