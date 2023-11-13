<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                     <div class="_buttons">
                     <?php if (has_permission('asset-category', '', 'create')) { ?>
                        <a href="#" class="btn btn-info pull-left" data-toggle="modal" data-target="#asset_category_modal"><?php echo "New Category"; ?></a>
                    <?php } ?>
                   
                    </div>
                    <div class="clearfix"></div>
                    <hr class="hr-panel-heading" />
                    <div class="clearfix"></div>
                    <?php render_datatable([
                        "Category Name",
                        "Category Description",
                        "Options",
                        ], 'asset-category'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('eraxon_assets/category/asset_category_modal'); ?>
<?php init_tail(); ?>
<script>
   $(function(){
        initDataTable('.table-asset-category', window.location.href, [1], [1]);
   });
</script>
</body>
</html>
