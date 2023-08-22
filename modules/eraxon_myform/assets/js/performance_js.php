<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<script>
	$(function()
	{
		var  sid= $("#report_by_staffs").data('staffid');

        if($('#report_by_staffs').length)
        {
            report_by_staffs('report_by_staffs', '', '',sid);
        }
		if($('#report_by_day_leads_staffs').length)
        {
		    report_by_day_leads_staffs('report_by_day_leads_staffs', '', '',sid);
        }
	});

function report_by_staffs(id, value, title_c,sid){
    'use strict';
    
    requestGetJSON('eraxon_myform/report_by_leads_staffs/'+sid).done(function (response) {

       //get data for hightchart
       
      Highcharts.setOptions({
        chart: {
            style: {
                fontFamily: 'inherit !important',
                fill: 'black'
            }
        },
        colors: [ '#119EFA','#15f34f','#ef370dc7','#791db2d1', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B']
       });
      Highcharts.chart(id, {
        chart: {
            type: 'column'
        },
        title: {
            text: '<?php echo _l('employee_lead_chart_by_month'); ?>'
        },
        credits: {
            enabled: false
        },
        xAxis: {
            categories: response.categories,
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: ''
            }
        },
        tooltip: {
            headerFormat: '<span class="font-size-10">{point.key}</span><table>',
            pointFormat: '<tr><td class="padding-0" style="color:{series.color}">{series.name}: </td>' +
            '<td class="padding-0"><b>{point.y:.1f}</b></td></tr>',
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
        series: [{
            name: '<?php echo 'Pending'; ?>',
            data: response.pending_leads 

        }, {
            name: '<?php echo 'Approved'; ?>',
            data: response.approved_leads

        }, {
            name: '<?php echo 'Rejected'; ?>',
            data: response.rejected_leads

        }]
       });
       

   });
}	

function report_by_day_leads_staffs(id, value, title_c,sid){
    'use strict';
    
    requestGetJSON('eraxon_myform/report_by_day_leads_staffs/'+sid).done(function (response) {

       //get data for hightchart
       
      Highcharts.setOptions({
        chart: {
            style: {
                fontFamily: 'inherit !important',
                fill: 'black'
            }
        },
        colors: [ '#119EFA','#15f34f','#ef370dc7','#791db2d1', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B']
       });
      Highcharts.chart(id, {
        /*chart: {
            type: 'column'
        },*/
        title: {
            text: '<?php echo 'Daily Leads'; ?>'
        },
        credits: {
            enabled: false
        },
        xAxis: {
            categories: response.categories,
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: ''
            }
        },
        tooltip: {
            headerFormat: '<span class="font-size-10">{point.key}</span><table>',
            pointFormat: '<tr><td class="padding-0" style="color:{series.color}">{series.name}: </td>' +
            '<td class="padding-0"><b>{point.y:.1f}</b></td></tr>',
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
        series: [{
            name: '<?php echo 'Leads'; ?>',
            data: response.daily_leads 

        }]
       });
       

   });
}
</script>