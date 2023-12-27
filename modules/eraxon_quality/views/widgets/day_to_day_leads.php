<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="widget<?php if (!is_staff_member()) {
    echo ' hide';
} ?>" id="widget-<?php echo create_widget_id(); ?>" data-name="<?php echo _l('s_chart', _l('leads')); ?>">
    <?php if (has_permission('qa_department','','qa_person')) { ?>
    <div class="row">
        <div class="col-md-12">
            <div class="panel_s">
                <div class="panel-body padding-10">
                    <div class="widget-dragger"></div>

                    <p
                        class="tw-font-medium tw-flex tw-items-center tw-mb-0 tw-space-x-1.5 rtl:tw-space-x-reverse tw-p-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="tw-w-6 tw-h-6 tw-text-neutral-500">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                        </svg>

                        <span class="tw-text-neutral-700">
                            <?php echo "My Daily QA Lead Performance"; ?>
                        </span>
                    </p>

                    <hr class="-tw-mx-3 tw-mt-3 tw-mb-6">

                   <div id="report_by_day_leads_qa">
                   </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
</div>

<?php hooks()->add_action('qa_js', 'qa_function');

function qa_function(){
    echo "
    console.log(\"SDASD\");
    report_by_day_leads_qa('report_by_day_leads_qa', '', '');
    report_by_qa('report_by_qa', '', '');

    
    function report_by_day_leads_qa(id, value, title_c){
        'use strict';
        
        requestGetJSON('eraxon_quality/report_by_day_leads_staffs').done(function (response) {
    
           //get data for highchart
           
          Highcharts.setOptions({
            chart: {
                style: {
                    fontFamily: 'inherit',
                    fill: 'black'
                }
            },
            colors: ['#119EFA', '#15f34f', '#ef370dc7', '#791db2d1', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4', '#50B432', '#0d91efc7', '#ED561B']
           });
          Highcharts.chart(id, {
            title: {
                text: 'Daily Leads'
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
                headerFormat: '<span class=\"font-size-10\">{point.key}</span><table>',
                pointFormat: '<tr><td class=\"padding-0\" style=\"color:{series.color}\">{series.name}: </td>' +
                '<td class=\"padding-0\"><b>{point.y:.1f}</b></td></tr>',
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
                name: 'Leads',
                data: response.daily_leads 
            }]
           });
       });
    }


    function report_by_qa(id, value, title_c){
        'use strict';
        
        requestGetJSON('eraxon_quality/report_by_day_leads_staffs').done(function (response) {
    
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
                text: '<?php echo \"Monthly Leads\"; ?>'
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
                headerFormat: '<span class=\"font-size-10\">{point.key}</span><table>',
                pointFormat: '<tr><td class=\"padding-0\" style=\"color:{series.color}\">{series.name}: </td>' +
                '<td class=\"padding-0\"><b>{point.y:.1f}</b></td></tr>',
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
                name: '<?php echo \'Pending\'; ?>',
                data: response.pending_leads 
    
            }, {
                name: '<?php echo \'Approved\'; ?>',
                data: response.approved_leads
    
            }, {
                name: '<?php echo \'Rejected\'; ?>',
                data: response.rejected_leads
    
            }]
           });
           
    
       });
    }
    
    

";

}
?>