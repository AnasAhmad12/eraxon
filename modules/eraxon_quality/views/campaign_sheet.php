<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    .handsontable-container {
        width: 100%;
        height: 400px;
        overflow: auto;
    }
</style>
<div id="wrapper">
    <div class="content">

        <div class="panel_s">
            <div class="panel-body">
                <div class="clearfix">
                    <div class="row filter_by">
                        <div class="col-md-2 leads-filter-column">
                            <?php echo render_input('filter_date', 'Date', date('Y-m-d'), 'date'); ?>
                        </div>
                        <button type="button" id="submit_btn" style="margin-top:22px;" class="btn btn-primary"> Get
                            Leads </button>

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
<?php init_tail(); ?>

<script>
    var hot = "";
    var id = <?php echo json_encode($id); ?>;
    date = ""
    $(document).ready(function ($) {

        var date = $("#filter_date").val();

        $("#submit_btn").click(function () {
            date = $("#filter_date").val();
            get_leads_and_col();
        });




        function get_leads_and_col() {

            $.get(admin_url + "eraxon_quality/get_campaign_sheet/" + id, { id: id, date: date, flag: "all" }, function (data, status) {
                var data = JSON.parse(data);
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

                <?php if (has_permission('qa_person', '', 'view') && !is_admin()) { ?>

                    console.log("QA Person")
                    data.camp_col.push(
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
                        }, {
                        title: 'QA Status',
                        data: 'qa_status',
                        type: 'dropdown',
                        source: data.status_col.qa_status,
                        validator: qa_status,
                        allowInvalid: false
                    },

                    );

                <?php } else { ?>
                    console.log("QA Review Person")

                    data.camp_col.push(
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
                        {
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
                        source: data.status_col.qa_status,
                        validator: qa_status,
                        allowInvalid: false
                    }, {
                        title: 'Reviewer Status',
                        data: 'reviewer_status',
                        type: 'dropdown',
                        validator: rev_status,
                        allowInvalid: false,
                        source: data.status_col.qa_review_status
                    },

                    );

                <?php } ?>

                var col_width = new Array(data.camp_col.length).fill(120);
                var container = document.getElementById('campaign_sheet');
                container.innerHTML = "";
                hot = new Handsontable(container, {
                    data: data.leads,

                    columns: data.camp_col,
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
                            value = hot.getDataAtRow(row);// row
                            var columnHeaders = []; // columns
                            for (var columnIndex = 0; columnIndex < hot.countCols(); columnIndex++) {
                                var columnSlug = hot.getColHeader(columnIndex);
                                columnHeaders.push(columnSlug);
                            }

                            var complete_lead = value.map((value, index) => {
                                if (index == 0) {
                                } else {
                                    return { [columnHeaders[index].toString().toLowerCase().replace(/ /g, '_')]: value };
                                }

                            });
                            complete_lead = complete_lead.slice(1, -3);

                            $.ajax({
                                url: site_url + 'eraxon_quality/update_qa_lead',
                                type: "POST",
                                data: {
                                    id: value[0],
                                    data: complete_lead,
                                },
                                success: function (response) {
                                }

                            });
                        }

                    },
                });

            });

            get_orders_data();
        }

        get_leads_and_col();

    });

    function get_orders_data() {
        setTimeout(function () {
            var date = $("#filter_date").val();
            $.get(admin_url + "eraxon_quality/get_campaign_sheet/" + id, { id: id, date: date, flag: "none" }, function (data, status) {
                var data = JSON.parse(data);
                var col = hot.countRows();
                if (data.leads.length != 0) {
                    let resultArray = Object.entries(data.leads[0]).map(([key, value]) => [col, key, value]);
                    hot.alter('insert_row', col, 1);
                    hot.setDataAtRowProp(resultArray);
                }
                get_orders_data();
            });

        }, 10000);
    }
    function campaign_sheet(data) {


    }




</script>