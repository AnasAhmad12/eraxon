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
                        <h4> Sales Report </h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" id="panel">
                        <div class="panel_s">
                            <div class="panel-body">
                                <?php echo form_open(admin_url('products/kiosk/profitloss')) ?>
                                <div class="col-md-3">
                                    <?php echo render_date_input('from_date', 'from_date', $from_date); ?>
                                </div>
                                <div class="col-md-3">
                                    <?php echo render_date_input('to_date', 'to_date', $to_date); ?>
                                </div>
                                <button style="float:right; margin-top:20px" class="btn btn-primary"
                                    id="purchase_report_btn">Generate Report </button>
                                <?php echo form_close() ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">

                        <div class="panel_s">
                            <div class="panel-body panel-table-full">

                                <table class="table dt-table" data-order-col="1" data-order-type="asc">
                                    <thead>
                                        <th align="left">Total Purchase</th>
                                        <th align="center">Total Sale</th>

                                    </thead>
                                    <tbody id="t-body">
                                    <tr> <td align="left" id="total_purchase"> </td> <td align="center" id="total_sale"> </td> 
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
            re=JSON.parse(response)
            console.log(re.purchase_total[0].grand_total)
            $("#total_purchase").text(re.purchase_total[0].grand_total +" PKR ");
            $("#total_sale").text(re.sale_total[0].total+ " PKR");

        });
        return false;
    }





</script>