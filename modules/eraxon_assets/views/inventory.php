<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s " id="TableData">
          <div class="panel-body">
            <h4> <?php echo $title ?> </h4>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12" id="panel">
            <div class="panel_s">
              <div class="panel-body">
                <?php
                $table_data = [
                  "Item Id",
                  "Image",
                  "Item Name",
                  "Serial Number",
                  "Qty",
                  "Price"
                ];
                render_datatable($table_data, ($class ?? 'assets_inventory')); ?>
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
  $(function () {
    initDataTable('.table-assets_inventory', window.location.href, 'undefined', 'undefined', '');
  });
</script>