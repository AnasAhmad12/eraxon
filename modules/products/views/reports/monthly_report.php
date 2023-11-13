<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<style type="text/css">
    
    .salary_label_unpaid{color:#f21f1f;border:1px solid #faa5a5;background: #fff6f6; cursor: pointer;padding: 2px;border-radius: 5px;}
    .salary_label_paid{color:#1bef15;border:1px solid #a4f9a1;background: #f6fff6;cursor: pointer;padding: 2px;border-radius: 5px;}
    .salary_label_hold{color:#28b8da;border:1px solid #a9e3f0;background: #f7fcfe;cursor: pointer;padding: 2px;border-radius: 5px;}

    .overlay{
    display: none;
    position: fixed;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    z-index: 999;
    background: rgba(255,255,255,0.8) url("<?php echo base_url('uploads/company/loading.gif'); ?>") center no-repeat;
}

</style>
 <div class="overlay"></div>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
          
                <div class="row filter_by">
                    <div class="col-md-3">
                      <?php echo render_date_input('kiosk_from', 'Start Date '); ?>
                    </div>
                    <div class="col-md-3">
                      <?php echo render_date_input('kiosk_to', 'End Date '); ?>
                    </div>
                 <div>
                <div class="tw-mb-2 sm:tw-mb-4 mtop25 text-right">
                    
                    <button id="generate_kiosk_report" class="btn btn-success"><?php echo "Generate KIOSK Report"; ?></button>
               
                </div>
            
            </div>
            <div class="col-md-12">
                 <div class="panel_s">
                    <div class="panel-body panel-table-full">
                    	<table class="table" id="kiosk_datatable" data-order-col="1" data-order-type="asc">
                            <thead>
                                <th>Staff Name</th>
                                <th>Total Payable</th>
                               
                            </thead>
                            <tbody id="kiosk_staff_table_body">
                                       
                            	
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

<script>

function add_data_to_table(data) {
    
    document.getElementById("kiosk_staff_table_body").innerHTML="";
   
    var table_data="";
    data.forEach(item => {
    const table_data=`<tr> <td  align="center"> ${item.name} </td> <td  align="center"> ${item.total} </td></tr>`;
    document.getElementById("kiosk_staff_table_body").innerHTML +=table_data;
   
});

 initDataTableInline($("#kiosk_datatable"));

}

$(function(){
      $("#generate_kiosk_report").on('click', function () {
      var client_id = $('select[name="staff_id"]').val();
      var from = $("#kiosk_from").val();
      var to = $("#kiosk_to").val();

      if (from == "") {
        alert_float("danger", "Please Select From Date");
        return false;
      }
      if (to == "") {
        alert_float("danger", "Please Select To Date");
        return false;
      }
      $.ajax({
        url: admin_url + 'products/monthly_report',
        type: 'POST',
        data: { role_id: 6, from: from, to: to },
      })
        .done(function (data) {
          data = $.parseJSON(data);
          if (data.status == "error") {
            alert_float("danger", "End Date Must Be Larger Than Start Date");
            return false;
          }
          console.log(data.data)
          add_data_to_table(data.data);
        });
    });

});






</script>