<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    .handsontable-container {
        width: 100%;
        height: 400px;
        /* Set the desired height */
        overflow: auto;
    }
</style>
<div id="wrapper">
    <div class="content">

        <div class="panel_s">
            <div class="panel-body">
                <div class="clearfix">
                    <div class="row filter_by">
                        <?php echo form_open(admin_url('eraxon_quality/get_campaign_sheet/'.$id),["id"=>"myform"])?>
                        <div class="col-md-2 leads-filter-column">
                            <?php echo render_input('filter_date', 'Date', date('Y-m-d'), 'date'); ?>
                        </div>
                        <button type="submit" id="submit_btn" style="margin-top:22px;" class="btn btn-primary"> Get Leads </button>
                        <?php echo form_close()?>

                    </div>

                    <div class="handsontable-container">
                        <div id="campaign_sheet"
                            class="hot handsontable htRowHeaders htColumnHeaders ht__manualColumnMove ht__manualRowMove">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script>
   

   
  
    qa_status = function (value, callback) {
        setTimeout(function () {
            if (value == 'pending' || value == 'approved' || value == 'reject') {
                callback(true);
            }
            else {
                callback(false);
            }
        }, 200);
    };

    lead_status = function (value, callback) {
        setTimeout(function () {
            if (value == 'pending' || value == 'approved' || value == 'reject') {
                callback(true);
            }
            else {
                callback(false);
            }
        }, 200);
    };

    rev_status = function (value, callback) {
        setTimeout(function () {
            if (value == 'reject' || value == 'approved' || value == 'reject') {
                callback(true);
            }
            else {
                callback(false);
            }
        }, 200);
    };




    var camp_col = <?php echo json_encode($camp_col); ?>;
    var lead = <?php echo json_encode($leads); ?>;
    var qa_status_col = <?php echo json_encode($status_col); ?>

    newa = [];
    <?php if (has_permission('qa_person', '', 'view') && !is_admin()) { ?>

        camp_col.push({
            title: 'QA Status',
            data: 'qa_status',
            type: 'dropdown',
            source: qa_status_col.qa_status,
            validator: qa_status,
            allowInvalid: false
        },
        {
            title: 'Forwardable Comments',
            data: 'forwardable_comments',
            type: 'text',
            // source: qa_status_col.qa_status,
            // validator: qa_status,
            // allowInvalid: false
        },
        {
            title: 'QA Comments',
            data: 'qa_comments',
            type: 'text',
            // source: qa_status_col.qa_status,
            // validator: qa_status,
            // allowInvalid: false
        },

        );

    <?php } else { ?>

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
            source: qa_status_col.qa_status,
            validator: qa_status,
            allowInvalid: false
        }, {
            title: 'Reviewer Status',
            data: 'reviewer_status',
            type: 'dropdown',
            validator: rev_status,
            allowInvalid: false,
            source: qa_status_col.qa_review_status
        },
        {
            title: 'Forwardable Comments',
            data: 'forwardable_comments',
            type: 'text',
            // source: qa_status_col.qa_status,
            // validator: qa_status,
            // allowInvalid: false
        },
        {
            title: 'QA Comments',
            data: 'qa_comments',
            type: 'text',
            // source: qa_status_col.qa_status,
            // validator: qa_status,
            // allowInvalid: false
        },
        {
            title: 'Rejection Comments',
            data: 'rejection_comments',
            type: 'text',
            // source: qa_status_col.qa_status,
            // validator: qa_status,
            // allowInvalid: false
        },
        
        );

    <?php } ?>


    var col_width= new Array(camp_col.length).fill(120);

    var container = document.getElementById('campaign_sheet');
    var hot = new Handsontable(container, {
        data: lead,

        columns: camp_col,
        allowEmpty: true,
        hiddenColumns: {
            columns: [0],

        },
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
        autoColumnSize: {
            samplingRatio: 23
        },
        manualColumnResize: true,
        colWidths: col_width,
        rowHeaders: true,
        filters: false,
        dropdownMenu: false,
        afterChange: (changes, source) => {

            if (source === 'loadData') {
                return; //don't save this change
            }
            if (changes) {
                var row = changes[0][0];
                value=hot.getDataAtRow(row);// row
                var columnHeaders = []; // columns
                for (var columnIndex = 0; columnIndex < hot.countCols(); columnIndex++) {
                    var columnSlug = hot.getColHeader(columnIndex);
                    columnHeaders.push(columnSlug);
                }

                var complete_lead = value.map((value, index) => {
                    if(index==0){
                    }else{
                    return { [columnHeaders[index].toString().toLowerCase().replace(/ /g, '_')]: value };
                    }
                  
                });
                complete_lead=complete_lead.slice(1,-3);

                $.ajax({
                    url: site_url + 'eraxon_quality/update_qa_lead',
                    type: "POST",
                    data: {
                        id:value[0],
                        data: complete_lead,
                    },
                    success: function (response) {
                        console.log("Sa", response)
                    }

                });
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