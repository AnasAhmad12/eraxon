<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="panel_s ">
      <div class="panel-body">
        <a href="#" class="btn btn-info openall">Open all</a>
        <a href="#" class="btn btn-warning closeall">Close all</a>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="panel-group" id="accordion">
          <!-- Orders this Week -->
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="panel-title">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                  Orders this Week
                </a>
              </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse in">
              <div class="panel-body">
                <div id="staff_chart_by_age" class="hrm-marginauto hrm-minwidth310">
                  <figure class="highcharts-figure">
                    <div id="container_order_of_the_week"></div>
                    <p class="highcharts-description"></p>
                  </figure>
                </div>
              </div>
            </div>
          </div>
          <!-- Orders this Week over -->
          <!-- Orders Per Month -->
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="panel-title">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                  Orders Per Month
                </a>
              </h4>
            </div>
            <div id="collapseTwo" class="panel-collapse collapse">
              <div class="panel-body">
                <div id="staff_chart_by_age" class="hrm-marginauto hrm-minwidth310">
                  <figure class="highcharts-figure">
                    <div id="container_order_of_the_month"></div>
                    <p class="highcharts-description"></p>
                  </figure>
                </div>
              </div>
            </div>
          </div>
          <!-- Orders Per Month over -->
          <!-- Orders Per Year -->
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="panel-title">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                  Orders Per Year
                </a>
              </h4>
            </div>
            <div id="collapseThree" class="panel-collapse collapse">
              <div class="panel-body">
                <figure class="highcharts-figure">
                  <div id="container_order_of_the_year"></div>
                  <p class="highcharts-description"></p>
                </figure>
              </div>
            </div>
          </div>

          <div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="panel-title">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseFive">
                  Total Receiveables
                </a>

              </h4>

            </div>
            <div id="collapseFive" class="panel-collapse collapse">
              <div class="panel-body">
                <div class="col-md-6">
                  <div class="table-responsive">
                    <table id="#myTable" class="table items items-preview invoice-items-preview" data-type="invoice">
                      <thead>
                        <tr>

                          <th align="center">
                            <?php echo "Month" ?>
                          </th>
                          <th align="center">
                            <?php echo "Total Receiveables" ?>
                          </th>
                        </tr>
                      </thead>
                      <tbody id="recieveable-table">

                      </tbody>
                    </table>
                  </div>
                </div>

              </div>
            </div>
          </div>


          <div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="panel-title">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseSix">
                  Staff Kiosk Reporting
                </a>
              </h4>
            </div>
            <div id="collapseSix" class="panel-collapse collapse">
              <div class="panel-body">
                <div class="row">
                  <div class="content">
                    <div class="col-md-5">
                      <div class="form-group">
                        <label for="staffid" class="control-label">Select Staff</label>
                        <select name="staff_id" data-live-search="true" id="staffid" class="form-control selectpicker">
                          <?php foreach ($staff_members as $staff) { ?>
                            <option value="<?php echo $staff['staffid']; ?>">
                              <?php echo $staff['full_name'] . ' (' . get_custom_field_value($staff['staffid'], 'staff_pseudo', 'staff', true) . ')'; ?>
                            </option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <?php echo render_date_input('kiosk_from', 'Start Date '); ?>
                    </div>
                    <div class="col-md-3">
                      <?php echo render_date_input('kiosk_to', 'End Date '); ?>
                    </div>
                    <div class="col-md-1">
                      <div class="form-group">
                        <label class="control-label">&nbsp;</label>
                        <br>
                        <button class="btn btn-success" id="generate_kiosk_report">Filter </button>
                      </div>
                    </div>
                  </div>
                </div>


                <div class="col-md-12">
                  <div class="table-responsive">
                    <table id="#kiosk_staff_table" class="table items items-preview invoice-items-preview"
                      data-type="invoice">
                      <thead>
                        <tr>

                          <th align="center">
                            <?php echo "Order No" ?>
                          </th>
                  
                          <th align="center">
                            <?php echo "Order Date" ?>
                          </th>
                          <th align="center">
                            <?php echo "Subtotal" ?>
                          </th>

                        </tr>
                      </thead>
                      <tbody id="kiosk_staff_table_body">

                      </tbody>
                    </table>
                    <h4 class="bold" style="float:right;"> Total Payable : <span id="total-amount"> </span> </h4>
                  </div>
                </div>






              </div>
            </div>
          </div>





            <!-- Orders Per Year over-->
            <!-- Custom Charts -->
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
                    Custom Charts
                  </a>
                </h4>
              </div>
              <div id="collapseFour" class="panel-collapse collapse">
                <div class="panel-body">
                  <div class="row">
                    <div class="content">
                      <div class="col-md-5">
                        <?php echo render_select('products_name', $products, ['product_name', 'product_name'], 'products', '', ['multiple' => true], [], '', '', false); ?>
                      </div>
                      <div class="col-md-3">
                        <?php echo render_date_input('from', 'contract_start_date'); ?>
                      </div>
                      <div class="col-md-3">
                        <?php echo render_date_input('to', 'contract_end_date'); ?>
                      </div>
                      <div class="col-md-1">
                        <div class="form-group">
                          <label class="control-label">&nbsp;</label>
                          <br>
                          <button class="btn btn-success create_report">Filter </button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <figure class="highcharts-figure">
                    <div id="container_order_custom"></div>
                    <p class="highcharts-description"></p>
                  </figure>
                </div>
              </div>
            </div>
            <!-- Custom Charts over -->
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php init_tail(); ?>
  <script type="text/javascript">
    $('.closeall').on('click', function () {
      $('.panel-collapse.in').collapse('hide');
    });
    $('.openall').on('click', function () {
      $('.panel-collapse:not(".in")').collapse('show');
    });


function add_data_to_table(data) {
    
    document.getElementById("kiosk_staff_table_body").innerHTML="";
    total=0;
    var table_data="";
    data.forEach(item => {
    const table_data=`<tr> <td  align="center"> ${item.id} </td> <td  align="center"> ${item.order_date} </td> <td  align="center"><?=$base_currency->name?> ${item.total}</td></tr>`;
    document.getElementById("kiosk_staff_table_body").innerHTML +=table_data;
    total=total+parseFloat(item.total);
});
document.getElementById('total-amount').innerHTML=` <?=$base_currency->name?>  ${total} `;

}
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
        url: admin_url + 'products/staff_report_kiosk',
        type: 'POST',
        data: { staff_id: client_id, from: from, to: to },
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











    Highcharts.chart('container_order_of_the_week', {
      chart: {
        type: 'areaspline'
      },
      title: {
        text: 'Sales this week'
      },
      legend: {
        layout: 'vertical',
        align: 'left',
        verticalAlign: 'top',
        x: 60,
        y: 50,
        floating: true,
        borderWidth: 1,
        backgroundColor:
          Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF'
      },
      xAxis: {
        categories: <?php echo ($categories) ? $categories : null; ?>,
        plotBands: [{
          from: 1,
          to: 1,
          color: 'rgba(68, 170, 213, .2)'
        }]
      },
      yAxis: {
        title: {
          text: 'Sales'
        }
      },
      tooltip: {
        shared: false,
        valueSuffix: ' PKR'
      },
      credits: {
        enabled: false
      },
      plotOptions: {
        areaspline: {
          fillOpacity: 0.5
        }
      },
      series: <?php echo ($week_series) ? $week_series : null; ?>
    });


    $("#recieveable-table").html("<?php foreach ($total_recieveable as $v => $n) { ?>
        < tr > <td align='center' style='font-weight:bold;'> <?php echo $v ?> </td> <td align='center' style='font-weight:bold;'> <?php echo $n . " PKR" ?> </td> <?php } ?>
  ")

  Highcharts.chart('container_order_of_the_month', {
        chart: {
          type: 'column'
        },
        credits: {
          enabled: false
        },
        title: {
          text: 'Orders Per Month'
        },
        xAxis: {
          categories: <?php echo ($month_categories) ? $month_categories : null; ?>,
          crosshair: true
        },
        yAxis: {
          min: 0,
          title: {
            text: 'Number Of Orders'
          }
        },
        tooltip: {
          headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
          pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y:.0f} PKR</b></td></tr>',
          footerFormat: '</table>',
          shared: true,
          useHTML: true
        },
        plotOptions: {
          column: {
            pointPadding: 0.2,
            borderWidth: 0
          }
        },
        series: <?php echo ($month_series) ? $month_series : null; ?>
      });

    Highcharts.chart('container_order_of_the_year', {
      chart: {
        type: 'column'
      },
      credits: {
        enabled: false
      },
      title: {
        text: 'Orders Per Year'
      },
      xAxis: {
        categories: <?php echo ($year_categories) ? $year_categories : null; ?>,
        crosshair: true
      },
      yAxis: {
        min: 0,
        title: {
          text: 'Number Of Orders'
        }
      },
      tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
          '<td style="padding:0"><b>{point.y:.0f} PKR</b></td></tr>',
        footerFormat: '</table>',
        shared: false,
        useHTML: true
      },
      plotOptions: {
        column: {
          pointPadding: 0.2,
          borderWidth: 0
        }
      },
      series: <?php echo ($year_series) ? $year_series : null; ?>
    });











    $(".create_report").on('click', function () {
      var products_name = $("#products_name").val();
      var from = $("#from").val();
      var to = $("#to").val();
      if (products_name.length < 1 || products_name[0] == "") {
        alert_float("danger", "Please Select at Least One Product");
        return false;
      }
      if (from == "") {
        alert_float("danger", "Please Select From Date");
        return false;
      }
      if (to == "") {
        alert_float("danger", "Please Select To Date");
        return false;
      }
      $.ajax({
        url: admin_url + 'products/custom_report_products',
        type: 'POST',
        data: { products_name: products_name, from: from, to: to },
      })
        .done(function (data) {
          data = $.parseJSON(data);
          if (data.status == "error") {
            alert_float("danger", "End Date Must Be Larger Than Start Date");
            return false;
          }
          Highcharts.chart('container_order_custom', {
            chart: {
              type: 'column'
            },
            credits: {
              enabled: false
            },
            title: {
              text: "Chart between " + from + " to  " + to
            },
            xAxis: {
              categories: data.date_categories,
              crosshair: true
            },
            yAxis: {
              min: 0,
              title: {
                text: 'Number Of Orders'
              }
            },
            tooltip: {
              headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
              pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.0f} orders</b></td></tr>',
              footerFormat: '</table>',
              shared: false,
              useHTML: true
            },
            plotOptions: {
              column: {
                pointPadding: 0.2,
                borderWidth: 0
              }
            },
            series: data.date_series,
          });
        });
    });
  </script>