<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    .handsontable-container {
       
    }

    @import "https://unpkg.com/open-props";
 .custom-table thead th {
        font-weight: bold;
        /*font-size: 16px;*/
    }

    .custom-table thead th:nth-child(even),
    .custom-table tbody tr:nth-child(odd) th {
        background-color: #d7f1e1;
    }

  .handsontable .blue {
        background: #1aee40;
    }

    .handsontable .status-reject {
        background: #f11322;
    }

    .handsontable .status-approve {
        background: #f9aeae;
    }

    .handsontable .status-l1 {
        background: #349dcf;
    }
    .handsontable .status-warning{
        background: #ff6700;
    }
    .handsontable .status-pending {
        background: #ffdd12;
    }

   .handsontable th, .handsontable td{
        white-space:nowrap; 
        text-overflow: ellipsis;
    }

    button:hover {
        --y: -10;
        --scale: 1.1;
        --border-scale: 1;
    }

    button:active {
        --y: 5%;
        --scale: 0.9;
        --border-scale: 0.9, 0.8;
    }
  
   #loader-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(255, 255, 255, 0.8);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 9999;
    }

    /* Style for the loading spinner */
    #loader {
      border: 8px solid #f3f3f3;
      border-top: 8px solid #3498db;
      border-radius: 50%;
      width: 50px;
      height: 50px;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
  
</style>
<div id="wrapper">
    <div class="content">

        <div class="panel_s">
            <div class="panel-body">
                <div class="clearfix">

                    <div class="ag-format-container">
                        <div class="ag-courses_box">

                            <?php foreach ($campaigns as $c) { ?>
                                <button type="button" data-id="<?php echo $c->id ?>" class="btn btn-primary campaign_name"
                                    href="">
                                    <?php echo $c->name ?> <span class="badge badge-light noti-span"></span>
                                </button>
                            <?php } ?>




                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="panel_s">
            <div class="panel-body">
                <div class="clearfix">
                   <div id="filter-date" class="row filter_by" style=" display:none;">
                        <div class="col-md-2 leads-filter-column">
                            <?php  if( has_permission('qa_department', '', 'qa_person') && !is_admin()){
                            echo render_date_input('from', 'contract_start_date', date('Y-m-d', strtotime('-30 days'))); 
                            }
                            else{
                            echo render_date_input('from', 'contract_start_date', date('Y-m-d')); 
                            } ?>
                        </div>

                        <div class="col-md-2 leads-filter-column">
                            <?php echo render_date_input('to', 'contract_end_date', date('Y-m-d')); ?>

                        </div>
                        <div class="col-md-2 leads-filter-column">
                        <button type="button" id="submit_btn" style="margin-top:22px;" class="btn btn-primary"> Get
                            Leads </button>
                        </div>
                     
                            <div class="col-md-4">
                            <?php echo render_input('search_keyword',"Search","","",["id"=>"search_key"]); ?>
                            </div>
                              
                        <?php if (has_permission('qa_department', '', 'qa_manager') || has_permission('qa_department', '', 'qa_reviewer')) { ?>
                            <button type="button" id="csv_btn" style="margin-top:22px;float:right;" class="btn btn-info">
                                Download CSV </button>
                        <?php } ?>

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

<?php init_tail(); ?>

<script>
    var id = -1;

    var hot = "";
    var start_date = "";
    var end_date = "";




    $(document).ready(function ($) {
        var new_leads = <?php echo json_encode($new_leads); ?>;
        update_new_lead();

        document.addEventListener('keydown', function (event) {
            if ((event.ctrlKey || event.metaKey) && event.key === '+') {
                fullscreen('campaign_sheet');
            }
        });

        $("input[name='search_keyword']").change(function () {
        // This function will be called when the value changes
        var inputValue = $(this).val();
        get_leads_and_col(id,inputValue);
      });

        function fullscreen(id) {
            var element = document.getElementById(id);
            element.parentNode.requestFullscreen();
            element.style.width = '100%';
            element.style.height = '100%';
            element.style.overflow = 'auto';
        }

        start_date = $("[name='from']").val();
        end_date = $("[name='to']").val();



        $("#submit_btn").click(function () {
            start_date = $("[name='from']").val();
            end_date = $("[name='to']").val();
            showLoader();

            get_leads_and_col(id);
        });

        $(document).on('click', '.campaign_name', function (e) {
            e.preventDefault();
            id = $(this).attr("data-id");
            $(this).find('.noti-span').text("");
            showLoader();
            get_leads_and_col(id);
        });


        function update_new_lead() {
            $('.campaign_name').each(function () {
                var dataId = $(this).data('id');
                var $button = $('[data-id="' + dataId + '"]');



                new_leads.forEach(function (lead) {
                    if (lead.lead_type == dataId) {
                        $button.find('.noti-span').text(lead.lead_count);
                    }
                });

            });
        }

        function get_leads_and_col(id,keyword="") {

            $("#filter-date").show();
            date = $("#filter_date").val();
            $.get(admin_url + "eraxon_quality/get_campaign_sheet/" + id, {
                id: id,
                start_date: start_date,
                end_date: end_date,
                flag: "all",
                searchKey:keyword
            }, function (data, status) {
                console.log("SSSSSSS",data);
                var data = JSON.parse(data);
                new_leads = data.new_leads;
                update_new_lead();
                $('#loader-overlay').remove();

                qa_status = function (value, callback) {
                    setTimeout(function () {
                        if (data.status_col.qa_status.includes(value)) {
                            callback(true);
                        } else {
                            callback(false);
                        }
                    }, 200);
                };

                lead_status = function (value, callback) {
                    setTimeout(function () {
                        if (value == 'pending' || value == 'approved' || value == 'reject') {
                            callback(true);
                        } else {
                            callback(false);
                        }
                    }, 200);
                };

                rev_status = function (value, callback) {
                    setTimeout(function () {
                        if (data.status_col.qa_review_status.includes(value)) {
                            callback(true);
                        } else {
                            callback(false);
                        }
                    }, 200);
                };

                <?php if (has_permission('qa_department', '', 'qa_person') && !is_admin()) { ?>
                    data.camp_col.push({
                        title: 'Forwardable Comments',
                        data: 'forwardable_comments',
                        type: 'text',
                    }, {
                        title: 'QA Comments',
                        data: 'qa_comments',
                        type: 'text',
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
                    data.camp_col.push({
                        title: 'Forwardable Comments',
                        data: 'forwardable_comments',
                        type: 'text',

                    }, {
                        title: 'QA Comments',
                        data: 'qa_comments',
                        type: 'text',
                    }, {
                        title: 'Rejection Comments',
                        data: 'rejection_comments',
                        type: 'text',
                    }, {
                        title: 'QA Status',
                        data: 'qa_status',
                        type: 'dropdown',
                        source: data.status_col.qa_status,
                        validator: qa_status,
                        allowInvalid: false
                    },
                        {
                            title: 'Reviewer Status',
                            data: 'reviewer_status',
                            type: 'dropdown',
                            validator: rev_status,
                            allowInvalid: false,
                            source: data.status_col.qa_review_status
                        },
                        {
                            title: 'QA Person',
                            data: 'qa_person',
                            type: 'text',

                        },
                        {
                            title: 'QA Rating',
                            data: 'qa_rating',
                            type: 'dropdown',
                            source: [1, 2, 3],
                            renderer: 'starRating'
                        },
                         
                        {
                            title: 'Lead Uploaded',
                            data: 'lead_uploaded',
                            type: 'checkbox',
                            checkedTemplate: 'yes',
                            uncheckedTemplate: 'no'
                        }

                    );

                <?php } ?>


                Handsontable.renderers.registerRenderer('starRating', function (instance, td, row, col, prop, value, cellProperties) {
                    var rating = parseInt(value, 10);

                    var stars = '';
                    for (var i = 1; i <= 3; i++) {
                        if (i <= rating) {
                            stars += '★ ';
                        } else {
                            stars += '☆ ';
                        }
                    }

                    td.innerHTML = stars;
                    td.style.color = (rating >= 3) ? '#ffc700' : '#c59b08';
                });


                var customLinkRenderer = function (instance, td, row, col, prop, value, cellProperties) {
                    Handsontable.renderers.TextRenderer.apply(this, arguments);

                    if (prop === 'recording_link_1' || prop === 'recording_link_2') {
                        if (value != "") {
                            td.innerHTML = '<a href="' + value + '" target="_blank">' + value + '</a>';
                        }
                    }
                };

                data.camp_col.forEach(element => {

                    if (element.data == "recording_link_1" || element.data == "recording_link_2") {
                        element.renderer = customLinkRenderer;
                    }

                });


                console.log("Column", data.camp_col);
                var container = document.getElementById('campaign_sheet');
                container.innerHTML = "";
                <?php if (has_permission('qa_department', '', 'qa_person') && !is_admin()) { ?>
                    Handsontable.hooks.add('afterInit', updateRows);
                <?php } ?>
               <?php if (has_permission('qa_department', '', 'qa_reviewer') && !is_admin()) { ?>
                    Handsontable.hooks.add('afterInit', updateColors);
                <?php } ?>
                Handsontable.hooks.add('afterCopy', aftercopycolor);
                Handsontable.hooks.add('afterCopy', function (data, coords) {
                    const copiedRowIndex = coords[0].startRow;
                    console.log("AS", copiedRowIndex)
                    // Change the background color of the copied row
                    hot.setCellMeta(copiedRowIndex, 0, 'className', 'copied-row');

                    // Re-render the table to update the colors
                    hot.render();
                });


                hot = new Handsontable(container, {
                    data: data.leads,

                    columns: data.camp_col,
                    allowEmpty: true,
                    hiddenColumns: {
                        columns: [0],

                    },
                    contextMenu: false,
                    manualRowMove: true,
                    manualColumnMove: true,
                    autoWrapRow: true,
                  	width: '100%',
                  	height: 'auto',
                    className: 'custom-table',
                    persistentState: true,
                    licenseKey: 'non-commercial-and-evaluation',
                    fillHandle: false,
                    startRows: 0,
                    startCols: 0,
                    minSpareRows: 0,
                    width: '100%',
                    columnSorting: true,
                    columnSorting: {
                        sortEmptyCells: true,
                        initialConfig: {
                            column: 'local_agent_name', // use the property name you want to sort by
                            sortOrder: 'asc'
                        }
                    },
                    manualColumnResize: true,
                    rowHeaders: true,
                    filters: true,
                    dropdownMenu: true,
                  	minSpareRows: 3,
                    beforeKeyDown: function (event) {
                        var selected = hot.getSelected();
                        var totalColumns = hot.countCols();

                        if (event.key === 'ArrowRight' && selected && selected[0][1] === totalColumns - 1) {
                            // If the right arrow key is pressed at the last column, prevent default behavior
                            event.stopImmediatePropagation();
                            event.preventDefault();
                        }
                    },


                    afterChange: (changes, source) => {
                        if (source === 'loadData') {
                            return;
                        }



                        <?php if (has_permission('qa_department', '', 'qa_person') && !is_admin()) { ?>
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
                                      
                                       if (value == 'pending') {
                                            meta.className = 'status-pending';
                                        }
                                        if (value == 'approved') {
                                            meta.className = 'status-approve';
                                        }

                                        if (value == 'reject') {
                                            meta.className = 'status-reject';
                                        }

                                    }
                                    hot.render();
                                }
                            }
                        <?php } ?>

                        <?php if (has_permission('qa_department', '', 'qa_reviewer')) { ?>
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
                              if (prop === 'reviewer_status') {
                                    for (var i = 0; i < hot.countCols(); i++) {
                                        var meta = hot.getCellMeta(row, i);
                                        if (value == 'reject') {
                                            meta.className = 'status-reject';
                                        }
                                        if (value == 'approved') {
                                            meta.className = 'status-approve';
                                        }
                                      	if(value =='L1'){
                                        	meta.className='status-l1';
                                        
                                        }
                                      if(value =='warning'){
                                        	meta.className='status-warning';
                                        
                                        }

                                    }
                                    hot.render();
                                }

                            }
                        <?php } ?>

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
                            const status = {};

                            <?php if (has_permission('qa_department', '', 'qa_person') && !is_admin()) { ?>
                                status.qa_status = complete_lead_data.find(obj => obj && obj
                                    .hasOwnProperty('qa_status')).qa_status;
                                status.forward_comments = complete_lead_data.find(obj => obj && obj
                                    .hasOwnProperty('forwardable_comments'))
                                    .forwardable_comments;
                                status.qa_comments = complete_lead_data.find(obj => obj && obj
                                    .hasOwnProperty('qa_comments')).qa_comments;
                                qa_rating = "";
                          		lead_uploaded="";
                            <?php } else { ?>
                                status.reviewer_status = complete_lead_data.find(obj => obj && obj
                                    .hasOwnProperty('reviewer_status')).reviewer_status;
                                status.qa = complete_lead_data.find(obj => obj && obj
                                    .hasOwnProperty('qa_status')).qa_status;
                                status.forward_comments = complete_lead_data.find(obj => obj && obj
                                    .hasOwnProperty('forwardable_comments'))
                                    .forwardable_comments;
                                status.qa_comments = complete_lead_data.find(obj => obj && obj
                                    .hasOwnProperty('qa_comments')).qa_comments;
                                status.rejection_comments = complete_lead_data.find(obj => obj &&
                                    obj.hasOwnProperty('rejection_comments')).rejection_comments;

                                qa_rating = complete_lead_data.find(obj => obj &&
                                    obj.hasOwnProperty('qa_rating')).qa_rating;
                          		lead_uploaded = complete_lead_data.find(obj => obj &&
                                    obj.hasOwnProperty('lead_uploaded')).lead_uploaded;

                            <?php } ?>

                            complete_lead = complete_lead.slice(1, -3);

                            $.ajax({
                                url: site_url + 'eraxon_quality/update_qa_lead',
                                type: "POST",
                                data: {
                                    id: value[0],
                                    status: status,
                                    data: complete_lead,
                                    qa_rating: qa_rating,
                                  	lead_uploaded:lead_uploaded
                                },
                                success: function (response) {
                                    console.log(response)
                                }

                            });
                        }

                    },
                });

            });


            get_orders_data();
        }

        <?php if (has_permission('qa_department', '', 'qa_manager') || has_permission('qa_department', '', 'qa_reviewer')) { ?>

            const csvButton = document.getElementById('csv_btn');
            csvButton.addEventListener('click', function () {
                if (hot.getPlugin('exportFile')) {
                    const hiddenColumns = hot.getPlugin('hiddenColumns').hiddenColumns;
                    const excludedColumns = [1, ...hiddenColumns];

                    console.log("Jio", excludedColumns);

                    hot.getPlugin('exportFile').downloadFile('csv', {
                        bom: false,
                        columnDelimiter: ',',
                        columnHeaders: true,
                        exportHiddenColumns: false,
                        exportHiddenRows: false,
                        fileExtension: 'csv',
                        filename: 'LeadsSheet-CSV-file_[YYYY]-[MM]-[DD]',
                        mimeType: 'text/csv',
                        rowDelimiter: '\r\n',
                        rowHeaders: true
                    });
                } else {
                    console.error('exportFile plugin is not available. Make sure you have the correct version of Handsontable.');
                }
            });
        <?php } ?>

        function get_orders_data() {
            setTimeout(function () {
                var date = $("#filter_date").val();
                $.get(admin_url + "eraxon_quality/get_campaign_sheet/" + id, {
                    id: id, start_date: start_date,
                    end_date: end_date, flag: "none"
                }, function (data, status) {
                    var data = JSON.parse(data);
                    new_leads = data.new_leads;
                    update_new_lead();
                    if (data.leads.length != 0) {
                        data.leads.forEach(element => {
                            var col = hot.countRows();
                            let new_lead = Object.entries(element).map(([key, value]) => [col, key, value]);
                            hot.alter('insert_row', col, element.length);
                            hot.setDataAtRowProp(new_lead);
                        });
                    }
                    get_orders_data();
                });

            }, 20000);
        }
    });


  function updateColors() {
        for (var row = 0; row < this.countRows(); row++) {
            for (var column = 0; column < this.countCols(); column++) {
                var meta = this.getCellMeta(row, column);
                var value = this.getDataAtRowProp(row, 'reviewer_status');
                var value1 = this.getDataAtRowProp(row, 'lead_uploaded');

                if (value == 'reject') {
                    meta.className= 'status-reject';
                }
                if (value == 'approved') {
                    meta.className = 'status-approve';
                }
                if (value == 'L1') {
                    meta.className = 'status-l1';
                }
                if(value=='warning'){
                    meta.className='status-warning';
                }
                if (value1 == 'yes') {
                    meta.className = 'blue';
                }
                
                
            }
        }
        this.render();
    }
  
    function updateRows() {
        for (var row = 0; row < this.countRows(); row++) {
            for (var column = 0; column < this.countCols(); column++) {
                var meta = this.getCellMeta(row, column);
                var value = this.getDataAtRowProp(row, 'qa_status');
                if (value != 'pending') {
                    meta.readOnly = true;
                }
              
              if (value == 'pending') {
                                         meta.className = 'status-pending';
                                        }
                                        if (value == 'approved') {
                                            meta.className = 'status-approve';
                                        }

                                        if (value == 'reject') {
                                            meta.className = 'status-reject';
                                        }
              
              	

            }
        }
        this.render();
    }
   
  function aftercopycolor(data, coords) {

        if (coords.length > 0) {
            var selectedColumns = hot.getSelected();
            var totalColumns = hot.countCols();
            totalColumns = totalColumns - 1;
            var lead_upload = this.propToCol('lead_uploaded');

            if (selectedColumns.length == 1) {

                for (var row = selectedColumns[0][0]; row <= selectedColumns[0][2]; row++) {
                    if (selectedColumns[0][3] == totalColumns) {
                        this.setDataAtCell(row, lead_upload, 'yes');
                        for (var col = 0; col <= totalColumns; col++) {
                            var cell = this.getCell(row, col);
                            this.setCellMeta(row, col, 'className', 'blue');
                        }
                    }
                }
            } else {
                // this can be improved
                for (var row = 0; row < selectedColumns.length; row++) {
                    if (selectedColumns[0][3] == totalColumns) {
                        this.setDataAtCell(selectedColumns[row][0], lead_upload, 'yes');
                        for (var col = 0; col <= totalColumns; col++) {
                            var cell = this.getCell(selectedColumns[row][0], col);
                            this.setCellMeta(selectedColumns[row][0], col, 'className', 'blue');
                        }
                    }
                }
            }
            console.log("selec", selectedColumns);



            this.render();

        }
    }




    function showLoader() {
        // Create the loader overlay
        var loaderOverlay = $('<div id="loader-overlay"></div>');
        var loader = $('<div id="loader"></div>');

        // Append the loader to the overlay
        loaderOverlay.append(loader);

        // Append the overlay to the body
        $('body').append(loaderOverlay);
    }

    function campaign_sheet(data) {


    }
</script>
