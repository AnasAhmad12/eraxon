<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s " id="TableData">
          <div class="panel-body">
          
            <?php if (has_permission('asset-custom_fields', '', 'create')) { ?>
              <a href="<?php echo admin_url('eraxon_assets/eraxon_assets_custom_fields/add'); ?>" class="btn btn-info pull-left display-block">
                <?php echo "New Custom Field"?>
              </a>
            <?php } ?>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12" id="panel">
           <div class="panel_s">
              <div class="panel-body">
                <?php
                $table_data = [
                  "Custom Field Name",
                  "Category Name",
                  "Custom Field Values",
                  ];
                  render_datatable($table_data, 'custom-fields'); ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
  <?php init_tail(); ?>
<script type="text/javascript">
  $(function(){
    initDataTable('.table-custom-fields', window.location.href, 'undefined', 'undefined', '');
  });
</script>
