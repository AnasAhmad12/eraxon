<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
      <div class="panel_s ">
          <div class="panel-body">
            <h2>Wallet Reporting</h2>
          </div>
      </div>
      <div class="row">
      <div class="col-md-12">
        <div class="panel-body">
                <div class="row">
                  <div class="content">
                    <div class="col-md-5">
                      <div class="form-group">
                        <label for="staffid" class="control-label">Select Staff</label>
                        <select name="staff_id" data-live-search="true" id="staffid" class="form-control selectpicker">
                          <?php foreach ($all_staff as $staff) { ?>
                            <option value="<?php echo $staff['staffid']; ?>">
                              <?php echo $staff['firstname'].' '.$staff['lastname'] . ' (' . get_custom_field_value($staff['staffid'], 'staff_pseudo', 'staff', true) . ')'; ?>
                            </option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <?php echo render_date_input('transaction_from', 'Start Date '); ?>
                    </div>
                    <div class="col-md-3">
                      <?php echo render_date_input('transaction_to', 'End Date '); ?>
                    </div>
                    <div class="col-md-1">
                      <div class="form-group">
                        <label class="control-label">&nbsp;</label>
                        <br>
                        <button class="btn btn-success" id="generate_transactions_report">Filter </button>
                      </div>
                    </div>
                  </div>
                </div>


                <div class="col-md-12">
                  <div class="table-responsive">
                    <table id="#wallet_staff_table" class="table items items-preview invoice-items-preview"
                      data-type="invoice">
                      <thead>
                        <tr>                 
                          <th align="center">Amount Type</th>
                          <th align="center">Amount</th>
                          <th align="center">Created Date/Time</th>

                        </tr>
                      </thead>
                      <tbody id="wallet_staff_table_body">

                      </tbody>
                    </table>
                    <h4 class="bold" style="float:right;"> Current Balance : <span id="total-amount"> </span> </h4>
                  </div>
                </div>
              </div>
      </div>
      </div>
  </div>
</div>
<?php init_tail(); ?>
<script>
  function add_data_to_table(data) 
  {
      document.getElementById("wallet_staff_table_body").innerHTML="";
      total=0;
      var table_data="";
      data.forEach(item => {
      const table_data=`<tr> <td  align="center"> ${item.amount_type} </td> <td  align="center"> ${item.amount} </td> <td  align="center">${item.created_datetime}</td></tr>`;
      document.getElementById("wallet_staff_table_body").innerHTML +=table_data;
      });
  }
  $("#generate_transactions_report").on('click', function () 
  {
      var client_id = $('select[name="staff_id"]').val();
      var from = $("#transaction_from").val();
      var to = $("#transaction_to").val();

      if (from == "") {
        alert_float("danger", "Please Select From Date");
        return false;
      }
      if (to == "") {
        alert_float("danger", "Please Select To Date");
        return false;
      }
      $.ajax({
        url: admin_url + 'eraxon_wallet/wallet_reporting',
        type: 'POST',
        data: { staff_id: client_id, from: from, to: to },
      })
        .done(function (data) {
          data = $.parseJSON(data);
          if (data.status == "error") {
            alert_float("danger", "End Date Must Be Larger Than Start Date");
            return false;
          }
          console.log(data.data);
          $("#total-amount").text('-'+data.total_transaction_amount);
          add_data_to_table(data.data);
        });
  });

</script>