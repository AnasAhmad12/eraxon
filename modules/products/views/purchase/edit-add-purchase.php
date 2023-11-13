<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<style>
    #item-code {
        padding: 1.5rem;
    }
</style>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="panel_s section-heading section-products">
            <div class="panel-body">
                <h4 class="no-margin section-text">
                    <?php echo "Purchase" ?>
                </h4>
            </div>
        </div>

        <div class="panel_s section-heading section-products">
            <div class="panel-body align-content-center">

                <div class="col-md-12" style="margin-top: 10px;">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="amount">Select Status</label>
                            <select id="select-status" class="form-control" name="payment_status">

                                <option selected value="Pending">Pending</option>
                                <option value="Approved">Approved</option>

                            </select>
                            <span id="status-validation"></span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <?php echo render_input('payment_date', "Purchase Date", "", "date"); ?>
                        <span id="date-validation"></span>
                    </div>
                </div>


                <div class="col-md-12 input-group">
                    <span class="input-group-addon">
                        <i class="fas fa-barcode"></i></span>
                    <?php echo render_input('item-code', "", "", "", ["Placeholder" => "Enter Product Name ",], ); ?>
                </div>


            </div>

        </div>
        <?php if (!empty($message)) { ?>
            <!-- Removed HTML entities because in message we are sending HTML format -->
            <div class="alert alert-danger">
                <?php echo $message; ?>
            </div>
        <?php } ?>
        <?php echo form_open(uri_string()); ?>

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

                                                <th align="">
                                                    <?php echo _l('qty') ?>
                                                </th>
                                                <th align="">
                                                    <?php echo "Product Variant" ?>
                                                </th>

                                                <th align="right">
                                                    <?php echo _l('rate') ?>
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
                        <?php if (0 == get_option('coupons_disabled')) { ?>
                            <div class="row">
                                <div class="col-md-6 col-md-offset-6">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <span class="bold">
                                                        <?php echo _l('coupon_code_label'); ?>
                                                    </span>
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input type="text" id="coupon_code" class="form-control" value="" />
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-success pull-right apply_coupon"><i
                                                            class="fa fa-check"></i>
                                                        <?php echo _l('apply_coupon'); ?>
                                                    </button>
                                                    <button type="button"
                                                        class="btn btn-danger pull-right remove_coupon hide"><i
                                                            class="fa fa-check"></i>
                                                        <?php echo _l('remove_coupon'); ?>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php } ?>
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
                        <input type="text" id="selectedDate" style="display: none;">
                        <button class="btn btn-success pull-right" id="purchase-button"><i class="fa fa-check"></i>
                            <?php echo "Update Purchase"; ?>
                        </button>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>

    </div>
</div>
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

<script>


    $(function () {

        const purchase_items = <?php echo json_encode($purchase_item); ?>;
        const purchase_details = <?php echo json_encode($purchase_detail); ?>;

        console.log("Purchase Detail", purchase_details[0]);
        console.log("Purchase Items", purchase_items);
        add_data_to_table();

        function add_data_to_table() {
            document.getElementById("table-body").innerHTML = "";
            subTotal = 0;
            console.log("Table Intialized");
            product_data_table = $("#table-body");
            var table_data = "";
            count = 0;
            purchase_items.forEach(item => {
                count = count + 1;
                const table_data = `<tr data-id="${item.id}"> <td> ${count} </td>   <td> ${item.product_name}  </td> <td> <input type="number" class="quantity" name="quantity" value="${item.qty}" style="padding:0.4rem;"min="1" max="100"> </td> <td>${item.variation == null ? "N/A" : item.variation} </td> <td align="right"> <input type="number" class="rate" style="padding:0.4rem;" value="${item.rate}" > </td> <td align="right">${item.subtotal} </td> <td align="right"> <button type="button" id="delete_button"  style="padding:0.4rem" class="btn btn-danger btn-xs" > <i class="fas fa-trash-alt"></i> </button> </td></tr>`;
                document.getElementById("table-body").innerHTML += table_data;
                subTotal = subTotal + parseFloat(item.subtotal);
            });

            document.getElementById('items-subtotal').innerHTML = `<?= $base_currency->name ?> ${subTotal}`;
            document.getElementById('payable-amount').innerHTML = `<?= $base_currency->name ?> ${subTotal}`;
        }



        $(document).on('change', '.quantity', function (e) {
            e.preventDefault();

            var id = $(this).parents("tr").attr("data-id");
            query = purchase_items.find(item => item.id == id);
            $qty = $(this).val();




            query.qty = $qty;
            query.subtotal = $qty * query.rate;
            add_data_to_table();
            alert_float('success', "Quantity Updated");


        });

        $(document).on('change', '.rate', function (e) {
            e.preventDefault();

            var id = $(this).parents("tr").attr("data-id");
            query = purchase_items.find(item => item.id == id);
            $rate = $(this).val();

            console.log("Rate is ", $rate)

            query.rate = $rate;
            query.subtotal = parseInt(query.qty) * parseInt($rate);
            add_data_to_table();
            alert_float('success', "Qunatity Updated");


        });

        $(document).on('click', '#delete_button', function (e) {
            e.preventDefault();
            var ele = $(this);
            var id = ele.parents("tr").attr("data-id");
            console.log("ID is", ele.parents("tr").attr("data-id"));
            if (confirm("Are you sure want to remove?")) {
                purchase_items = purchase_items.filter(item => item.id != id);
                add_data_to_table();
            }
        });


        $(document).on('click', '#purchase-button', function (e) {
            e.preventDefault();
            payment_status = $("select[name='payment_status']").val();
            var date = $('input[name="payment_date"]').val();

            console.log("deta", purchase_details[0].id);

            $.ajax({
                url: site_url + 'products/purchase/update_purchase',
                type: "POST",
                data: {
                    purchase_items: purchase_items,
                    payment_status: payment_status,
                    payment_date: date,
                    total: subTotal,
                    id:purchase_details[0].id
                },
                success: function (response) {
                    window.location.href = site_url + 'products/purchase';
                    alert_float('success', "Purchase Updated");
                }

            });

        });



        $('select[name="payment_status"]').val(purchase_details[0].payment_status)
        $('input[name="payment_date"]').val(purchase_details[0].date.slice(0, 10))


        $("input").autocomplete({
            source: site_url + 'products/purchase/get_product_master',
            autoFocus: true,

            select: function (event, ui) {
                post_data = ui.item.product_detail;

                console.log('varrr', ui.item.selected_variation)

                console.log('qtyyy', ui.item.var_qty);

                if (ui.item.selected_variation === undefined) {
                    false;
                }
                else {
                    let selectedvariation = { 'selectedVariation': ui.item.selected_variation['variation_value'] };
                    post_data = $.extend({}, post_data, selectedvariation);

                }
                console.log("Post", post_data)

                if (post_data.variations) {
                    variation = post_data.variations.find(item => item.variation_value == post_data.selectedVariation);
                    query = purchase_items.find(item => item.product_id == post_data.id && item.variation_value_id == variation.variation_value_id);
                    console.log("Query", query)
                    if (query) {

                        query.qty += 1;
                        query.subtotal = parseInt(query.qty) * variation.rate;
                    }
                    else {
                        var order_data = {
                            id: purchase_items.length + 1,
                            product_id: post_data.id,
                            product_variation_id: variation.id,
                            variation_value_id: variation.variation_value_id,
                            display_name: post_data.product_name + " - " + variation.variation_value + " (" + variation.product_code + ")",
                            product_name: post_data.product_name,
                            variation: post_data.selectedVariation,
                            rate: variation.rate,
                            max_qty: ui.item.var_qty,
                            qty: 1,
                            subtotal: variation.rate * 1


                        }

                        purchase_items.push(order_data);

                    }

                }
                else {
                    query = purchase_items.find(item => item.product_id == post_data.id)
                    if (query) {
                        query.qty += 1;
                        query.subtotal = parseInt(query.qty) * query.rate;
                    }
                    else {
                        var order_data = {
                            id: purchase_items.length + 1,
                            product_id: post_data.id,
                            product_variation_id: null,
                            display_name: post_data.product_name + " (" + post_data.product_code + ")",
                            product_name: post_data.product_name,
                            variation: null,
                            rate: post_data.rate,
                            max_qty: ui.item.var_qty,

                            qty: 1,
                            subtotal: post_data.rate * 1
                        }
                        purchase_items.push(order_data);

                    }


                }
                add_data_to_table();


                event.preventDefault();

            },

            response: function (event, ui) {
                console.log("log", $(this).val().length == 13);
                if (ui) {
                    ui.item = ui.content[0];
                    if ($(this).val().length === 13) {
                        // Automatically select the first option if it has a length of 13 characters
                        $(this).data('ui-autocomplete')._trigger('select', 'autocompleteselect', ui);
                        $(this).autocomplete('close');
                        $(this).removeClass('ui-autocomplete-loading');
                        $(this).val("");
                    }
                }
            }
        });
    });









</script>