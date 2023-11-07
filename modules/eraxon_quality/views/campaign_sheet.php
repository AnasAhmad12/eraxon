<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style> 

</style>
<div id="wrapper">
    <div class="content">
     
            <div class="panel_s">
                <div class="panel-body">
                <div class="clearfix">
      
       <div class="row filter_by">
        <div class="col-md-2 leads-filter-column">
          <?php echo render_input('month_timesheets','Date',date('Y-m'), 'date'); ?>
        </div>
      </div>

                    <div id="campaign_sheet" class="hot handsontable htRowHeaders htColumnHeaders ht__manualColumnMove ht__manualRowMove"></div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script>
qa_status = function (value, callback) {
    setTimeout(function () {
        if (value == 'Clear' || value == 'Invalid') {
            callback(true);
        }
        else {
            callback(false);
        }
    }, 200);
};

lead_status = function (value, callback) {
    setTimeout(function () {
        if (value == 'Pending' || value == 'Approved' || value == 'Reject') {
            callback(true);
        }
        else {
            callback(false);
        }
    }, 200);
};

rev_status = function (value, callback) {
    setTimeout(function () {
        if (value == 'Hold' || value == 'Reject' || value == 'Approved' || value == 'Invalid') {
            callback(true);
        }
        else {
            callback(false);
        }
    }, 200);
};




var camp_col=<?php echo json_encode($camp_col);?>;
var lead=<?php echo json_encode($leads);?>;

console.log("data",camp_col);
newa=[ ];
camp_col.push({
            title: 'Lead Status',
            data: 'lead_status',
            type: 'dropdown',
            validator: lead_status,
            allowInvalid: false,
            source: ['Pending', 'Approved', 'Reject']
        }, {
            title: 'QA Status',
            data: 'qa_status',
            type: 'dropdown',
            source: ['Clear', 'Invalid'],
            validator: qa_status,
            allowInvalid: false
        }, {
            title: 'Reviewer Status',
            data: 'reviewer_status',
            type: 'dropdown',
            validator: rev_status,
            allowInvalid: false,
            source: ['Hold', 'Reject', 'Approved', 'Invalid']
        });



console.log(data);
var save = document.getElementById("save");
var load = document.getElementById("load");



var container = document.getElementById('campaign_sheet');
var hot = new Handsontable(container, {
    data: lead,

    columns: camp_col,
    allowEmpty: true,
    contextMenu: false,
    manualRowMove: false,
    manualColumnMove: false,
    autoWrapRow: true,
    className: 'custom-table',
    licenseKey: 'non-commercial-and-evaluation',
    fillHandle: false,
    startRows: 0,
    startCols: 0,
    minSpareRows: 0,
    width: '100%',
    stretchH: 'all',
    autoColumnSize: {
        samplingRatio: 23
    },
    manualColumnResize: true,
    colWidths: [120, 120, 120, 120, 120, 120, 120, 120, 120, 120, 120, 120, 120, 120, 120, 120, 120, 120, 120, 120, 120, 120, 120, 120, 120, 120, 120, 120, 120, 120, 120],
    rowHeaders: true,
    filters: false,
    dropdownMenu: false,
    afterChange: (changes, source) => {
        console.log(source);
        if (source === 'loadData') {
            return; //don't save this change
        }
        if (changes) {
            var row = changes[0][0];
            console.log(hot.getDataAtRow(row));

            // aajx call
        }
       
    },
});

Handsontable.dom.addEvent(save, 'click', function () {
    console.log(JSON.stringify({ data: hot.getData() }));
});

document.querySelector('.btn').addEventListener('click', function () {
    var col = hot.countRows();
    hot.alter('insert_row', col, 1);
    /*hot.setDataAtCell(col, 0, '2023-10-25');
    hot.setDataAtCell(col, 1, 'Agent name');
    hot.setDataAtCell(col, 5, '+92134653232')*/
    hot.setDataAtRowProp([[col, 'date', '2023-10-25'], [col, 'agent_name', 'Agent name'], [col, 'phone_number', '+92134653232']]);
});

Handsontable.dom.addEvent(load, 'click', function () {
    $.ajax({
        url: 'json/load.json',
        dataType: 'json',
        type: 'GET',
        success: function (res) {
            var db = JSON.stringify(res.data);

            var data = JSON.parse(db);
            console.log(data);
            hot.loadData(data);
            //hot.render();
            setTimeout(function () {
                hot.render();
            }, 100);
        }
    });
});

</script>
<?php init_tail(); ?>