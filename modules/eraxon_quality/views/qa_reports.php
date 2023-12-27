<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    .status-dot {
    width: 16px;
    height: 16px;
    border-radius: 50%;
    margin-bottom: 10px;
}

.online-qa {
    background-color: #4CAF50; /* Green dot for online */
}

.offline-qa {
    background-color: #A9A9A9; /* Grey dot for offline */
}
  

    .lead-summary {
        margin: 20px;
    }

    .lead-header {
        background-color: #3498db;
        color: #fff;
        padding: 10px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
    }

    #toggleIcon {
        width: 20px;
        height: 20px;
    }

    .lead-content {
        display: none;
        padding: 10px;
    }

</style>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4> QA Reports </h4>
                    </div>
                </div>
                <div class="panel_s">
                    <div class="panel-body">
                      <div class="lead-summary">
                            <button class="btn btn-default btn-with-tooltip" id="slideDown"><i
                                    class="fa fa-align-left"></i></button>
                            <div class="lead-content" id="leadContent">
                                <div class="leads-overview tw-mt-2 sm:tw-mt-4 tw-mb-4 sm:tw-mb-0"
                                    style="display: block;">
                                    <h4 class="tw-mt-0 tw-font-semibold tw-text-lg">
                                        QA Summary </h4>
                                    <div
                                        class="tw-flex tw-flex-wrap tw-flex-col lg:tw-flex-row tw-w-full tw-gap-3 lg:tw-gap-6">
                                        <div
                                            class="lg:tw-border-r lg:tw-border-solid lg:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center last:tw-border-r-0">
                                            <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                                                <?php echo $total_summary->total_leads ?>
                                            </span>
                                            <span style="color:#28b8da" class="">
                                                Total </span>
                                        </div>
                                        <div
                                            class="lg:tw-border-r lg:tw-border-solid lg:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center last:tw-border-r-0">
                                            <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                                                <?php echo $total_summary->pending_count ?>
                                            </span>
                                            <span style="color:#1bef15" class="">
                                                Pending </span>
                                        </div>
                                        <div
                                            class="lg:tw-border-r lg:tw-border-solid lg:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center last:tw-border-r-0">
                                            <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                                                <?php echo $total_summary->total_assigned_leads ?>
                                            </span>
                                            <span style="color:#f21f1f" class="">
                                                Assigned  </span>
                                        </div>

                                        <div
                                            class="lg:tw-border-r lg:tw-border-solid lg:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center last:tw-border-r-0">
                                            <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                                                <?php echo $total_summary->rejected_count ?>
                                            </span>
                                            <span style="color:#f21f1f" class="">
                                                Rejected </span>
                                        </div>

                                        <div
                                            class="lg:tw-border-r lg:tw-border-solid lg:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center last:tw-border-r-0">
                                            <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                                                <span data-toggle="tooltip" data-title="0">
                                                    <?php echo $total_summary->approved_count ?>
                                                </span> </span>
                                            <span style="color:#fc2d42" class="text-danger">
                                                Approved </span>
                                        </div>
                                    </div>

                                </div>

                            </div>

                        </div>
                        <div class="col-md-6 " style="border:2px solid #f1f5f9;border-width:1px; ">                      
                      
                        <table class="table">
                            <thead class="thead-dark">
                            <tr>
                                <th>Staff Name</th>
                                <th>Pending</th>
                                <th>Total</th>
                                <th> Active </td>
                            </tr>
                            </thead>
                            <?php foreach ($reports as $re) { ?>
                                <tr >
                                    <td><?php echo get_staff_full_name($re->assigned_staff)?></td>
                                    <td><?php echo $re->pending_count?></td>
                                    <td><?php echo $re->total_leads?></td>
                                    <td><?php if($re->active==1){
                                        echo '<div class="status-dot online-qa"></div>';
                                    }
                                    else{
                                        echo  '<div class="status-dot offline-qa"></div>';
                                    }
                                    
                                    
                                    
                                    
                                    ?></td>


                                </tr>
                            <?php } ?>
                        </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>

<script>

    $(document).ready(function () {
        $('#slideDown').click(function () {
            $('#leadContent').slideToggle();
        });

        // Update lead counts (you can replace these values with your actual counts)
        var pendingLeadsCount = 5;
        var totalLeadsCount = 20;

        // Display lead counts
        $('#pendingLeads').text(pendingLeadsCount);
        $('#totalLeads').text(totalLeadsCount);
    });

</script>