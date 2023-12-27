<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
   .custom-table thead th {
        font-weight: bold;
        /*font-size: 16px;*/
    }

    .custom-table thead th:nth-child(even),
    .custom-table tbody tr:nth-child(odd) th {
        background-color: #d7f1e1;
    }
</style>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4> Leads Distribution </h4>
                    </div>
                </div>

    

                <div class="panel_s">
                    <div class="panel-body">
                        <div class="clearfix">
                            <div class="ag-format-container">
                                <div class="ag-courses_box">
                                    <button type="button" data-id="assigned" class="btn btn-primary campaign_name"
                                        href="">
                                        Get All Leads
                                    </button>
                                    <button type="button" data-id="unassigned" class="btn btn-primary campaign_name"
                                        href="">
                                        Get Unassigned Leads
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                

                <div class="panel_s">

                    <div class="panel-body">
                    <div id="filter-date" class="row filter_by" style="
                    display:none;">
                        <div class="col-md-2 leads-filter-column">
                        <?php echo render_date_input('from', 'contract_start_date',date('Y-m-d')); ?>

                        </div>

                        <div class="col-md-2 leads-filter-column">
                        <?php echo render_date_input('to', 'contract_end_date',date('Y-m-d')); ?>

                        </div>
                        <button type="button" id="submit_btn" style="margin-top:22px;" class="btn btn-primary"> Get
                            Leads </button>
                       

                    </div>
                        <div class="handsontable-container">
                            <div id="example"
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
    var staff = <?php $firstNames = array_column($staffs, 'firstname');
    echo json_encode($firstNames);
    ?>;
    var all_staff = <?php echo (json_encode($staffs)); ?>;
    var start_date = "";
    var end_date="";
    var typeR="";
   
    $(document).ready(function ($) {
        start_date = $("[name='from']").val();
        end_date = $("[name='to']").val();
    });

    $("#submit_btn").click(function () {
            start_date = $("[name='from']").val();
            end_date = $("[name='to']").val();
            get_leads_and_col(typeR);
        });


    $(document).on('click', '.campaign_name', function (e) {
        $("#filter-date").show();
            e.preventDefault();
            typeR = $(this).attr("data-id");
            console.log("Click ", start_date)
            get_leads_and_col(typeR);
        });

    function get_leads_and_col(type) {
            console.log("Type First Time", type);

            $.get(admin_url + "eraxon_quality/get_leads_unassigned", {
                flag: "all",
                start_date:start_date,
                end_date:end_date,
                type: type
            }, function (data, status) {
                if (data.length != 0) {
                    var data = JSON.parse(data);
                }

                var container = document.getElementById('example');
                container.innerHTML = "";
                hot = new Handsontable(container, {
                    data: data.u_leads,
                    columns: [
                        {
                            title: 'id',
                            data: 'id',
                            type: 'text',
                        },
                       { title: "Date",
                       data: "date", 
                       type: "date", 
                       dateForma: "MM-DD-YYYY",
                        correctForm:true
                     },
                        {
                            title: 'Agent Name',
                            data: 'agent_name',
                            type: 'text',
                        }, {
                            title: 'Phone Number',
                            data: 'phone_no',
                            type: 'text',
                        }, {
                            title: 'Lead Type',
                            data: 'lead_type',
                            type: 'text',
                        }, {
                            title: 'Assigned To',
                            data: 'assigned_to',
                            type: 'dropdown',
                            source: all_staff.map(staff => staff.firstname + " " + staff.lastname),
                            allowInvalid: false
                        },
                       {
                        title: 'Lead Status',
                        data: 'lead_status',
                        type: 'text',   
                    }
                      
                    
                    ],
                    allowEmpty: true,
                    hiddenColumns: {
                        columns: [0],
                    },
                    rowHeaders:true,
                    colHeaders: true,
                    contextMenu: false,
                    manualRowMove: false,
                    manualColumnMove: false,
                    autoWrapRow: true,
                    className: 'custom-table',
                    licenseKey: 'non-commercial-and-evaluation',
                    fillHandle: false,
                    startRows: 0,
                    startCols: 0,
                  	columnSorting: true,
                  	filters: true,             
                  	minSpareRows: 0,
                    width: '100%',
                    autoColumnSize: {
                        samplingRatio: 23
                    },
                    manualColumnResize: true,
                    rowHeaders: true,
                    dropdownMenu: true,                  	
                    afterChange: (changes, source) => {



                        if (source === 'loadData') {
                            return; //don't save this change
                        }
                        if (changes) {
                            var row = changes[0][0];
                            var prop = changes[0][1];
                            var value = changes[0][3];

                            if (prop === 'qa_status') {
                                for (var i = 0; i < hot.countCols(); i++) {

                                    var meta = hot.getCellMeta(row, i);

                                    if (value != 'pending') {
                                        meta.readOnly = true;
                                    }

                                }
                                hot.render();
                            }
                        }

                        if (changes) {
                            var row = changes[0][0];
                            var prop = changes[0][1];
                            var value = changes[0][3];
                            if (prop === 'assigned_to') {
                                for (var i = 0; i < hot.countCols(); i++) {

                                    var meta = hot.getCellMeta(row, i);

                                    if (value !== '') {
                                        meta.readOnly = true;
                                    }

                                }
                                hot.render();
                            }
                        }

                        if (changes) {
                            var row = changes[0][0];
                            var prop = changes[0][1];
                            var value = changes[0][3];
                            if (prop === 'qa_status') {
                                for (var i = 0; i < hot.countCols(); i++) {

                                    var meta = hot.getCellMeta(row, i);

                                    if (value == 'pending') {
                                        meta.readOnly = true;
                                    }

                                }
                                hot.render();
                            }

                        }
                        if (changes) {

                            var row = changes[0][0];
                            value = hot.getDataAtRow(row); // row
                            var columnHeaders = []; // columns
                            for (var columnIndex = 0; columnIndex < hot
                                .countCols(); columnIndex++) {
                                var columnSlug = hot.getColHeader(columnIndex);
                                columnHeaders.push(columnSlug);
                            }


                            var complete_lead = value.map((value, index) => {
                                if (index == 0) { } else {
                                    return {
                                        [columnHeaders[index].toString().toLowerCase()
                                            .replace(/ /g, '_')
                                        ]: value
                                    };
                                }

                            });


                            complete_lead_data = complete_lead;

                            assigned = complete_lead_data.find(obj => obj && obj.hasOwnProperty('assigned_to')).assigned_to;

                            staff_id = all_staff.find(staff => (
                                staff.firstname === assigned.split(' ')[0] && staff.lastname === assigned.split(' ')[1]
                            )).staffid;

                            $.ajax({
                                url: site_url + 'eraxon_quality/assign_distribution',
                                type: "POST",
                                data: {
                                    id: value[0],
                                    assigned_staff: staff_id,

                                },
                                success: function (response) {
                                }

                            });
                        }

                    },
                });
                get_orders_data(type);

            });

        }


        function get_orders_data(type) {
            console.log("Type After recursive call", type);

            $.get(admin_url + "eraxon_quality/get_leads_unassigned/", {
                flag: "none",
                start_date:start_date,
                end_date:end_date,
                type: type
            }, function (data, status) {
                var data = JSON.parse(data);
                // console.log("Updated Rows", data.u_leads)
                const allrows = hot.getData();
                if (data.u_leads.length !== 0) {
                    data.u_leads.forEach(element => {
                        const idExists = allrows.some(row => row[0] === element.id);
                        if (!idExists) {
                            var col = hot.countRows();
                            let new_lead = Object.entries(element).map(([key, value]) => [col, key, value]);
                            hot.alter('insert_row', col, element.length);
                            hot.setDataAtRowProp(new_lead);
                        } else {
                            console.log("Row already exists. Do something if needed.");
                        }
                    });
                }
                // Recursive call with the same type
                setTimeout(function () {
                    console.log("Type before recursive call", type);
                    get_orders_data(type);
                }, 50000);
            });
        }


        function findRowIndexByData(data) {
            // Function to find the index of a row based on its data
            for (var i = 0; i < hot.countRows(); i++) {
                var row_data = hot.getDataAtRow(i);
                if (compareArrays(row_data, data)) {
                    return i; // Row found, return its index
                }
            }
            return -1; // Row not found
        }

        function compareArrays(arr1, arr2) {
            // Function to compare two arrays for equality
            return JSON.stringify(arr1) === JSON.stringify(arr2);
        }






</script>