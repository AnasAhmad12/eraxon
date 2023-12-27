<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
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
                <div class="clearfix"></div>
                <hr class="hr-panel-heading" />
                <div class="clearfix"></div>
                <?php render_datatable([
                    "Category Name",
                    "Category Description",
                    "Options",
                ], 'manager-sheet'); ?>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
<?php init_tail(); ?>

<script>
    $(function () {
        initDataTable('.table-manager-sheet', window.location.href, [1], [1]);
    });

    $(document).ready(function ($) {
        $(document).on('click', '.campaign_name', function (e) {
            e.preventDefault();
            id = $(this).attr("data-id");
            console.log("Id is ",id);
            $.get(admin_url + "eraxon_quality/manager_sheet/" + id, {
                flag: "all"
            }, function (data, status) {
                // var data = JSON.parse(data);
                console.log("Data is ",data);
            }
        );

        });

        

    });
</script>