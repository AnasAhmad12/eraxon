<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<?php $from_date = _d(date('Y-m') . '-01');
$to_date = _d(date('Y-m') . '-31'); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s " id="TableData">
                    <div class="panel-body">
                        <h4> Purchase Report </h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" id="panel">
                        <div class="panel_s">
                            <div class="panel-body">
                                <?php echo form_open(admin_url('products/purchase/generate_purchase_report')) ?>
                                <div class="col-md-3">
                                    <?php echo render_date_input('from_date', 'from_date'); ?>
                                </div>
                                <div class="col-md-3">
                                    <?php echo render_date_input('to_date', 'to_date'); ?>
                                </div>
                                <button  style="float:right; margin-top:20px" class="btn btn-primary" id="purchase_report_btn">Generate Report </button>
                                <?php echo form_close() ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">

                        <div class="panel_s">
                            <div class="panel-body panel-table-full">

                                <table class="table" id="kiosk-purchase" data-order-col="1" data-order-type="asc">
                                <h4> Grand Total : <span id="payable"> </span></h4>
                                <thead>
                                        <th align="left">Date</th>
                                        <th align="center">Total</th>

                                    </thead>
                                    <tbody id="t-body">

                                    </tbody>
                                </table>

                           

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script type="text/javascript">
    $(function () {
        appValidateForm($('form'), {
            from_date: 'required',
            to_date: 'required'

        }, manage_column_form);
    });

    function manage_column_form(form) {
        var data = $(form).serialize();
        var url = form.action;

        $.post(url, data).done(function (response) {
            add_data_to_table(response);
        });
        return false;
    }

    function add_data_to_table(data) {
        var purchase_report=JSON.parse(data);
        console.log(purchase_report.length);
        document.getElementById("t-body").innerHTML = "";
        subTotal = 0;
        var table_data = "";
        count = 0;
        purchase_report.forEach(item => {
            count = count + 1;
            const table_data = `<tr><td align="left"><a href="<?php echo admin_url('products/purchase/view_purchase_detail?id=') ?> ${item.id}" > ${item.date.slice(0, 10)} </a> </td> <td align="center"> ${item.grand_total + " PKR "} </td></tr>`;
            document.getElementById("t-body").innerHTML += table_data;
            subTotal = subTotal + parseFloat(item.grand_total);
        });

        console.log("Total",subTotal);

         $("#payable").text(subTotal + " PKR");
        // document.getElementById('payable-amount').innerHTML = ` ${subTotal}`;

         initDataTableInline($("#kiosk-purchase"));
    }



</script>