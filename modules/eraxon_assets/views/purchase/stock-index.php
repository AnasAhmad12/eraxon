<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s " id="TableData">
          <div class="panel-body">
            <?php if (has_permission('asset-purchase', '', 'create')) { ?>
              <a href="<?php echo admin_url('eraxon_assets/eraxon_assets_stock_in/add_stock_in'); ?>"
                class="btn btn-info pull-left display-block">
                Stock In
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
                  "Id",
                  "Date",
                  "Status",
                  "Total",
                  "Action"
                ];
                render_datatable($table_data, ($class ?? 'stock_in_items')); ?>
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
    initDataTable('.table-stock_in_items', window.location.href, 'undefined', 'undefined', '');
  });
</script>