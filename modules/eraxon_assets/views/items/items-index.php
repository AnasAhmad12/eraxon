<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s " id="TableData">
          <div class="panel-body">
            <?php if (has_permission('asset_items', '', 'create')) { ?>
            <a href="<?php echo admin_url('eraxon_assets/eraxon_assets_items/add_item'); ?>" class="btn btn-info pull-left display-block">
             Add New Item
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
                    "Item Name",
                     "Item Image",
                     "Item Description",
                      "Item Category",
                 
                  ];
                  render_datatable($table_data, ($class ?? 'items')); ?>
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
    initDataTable('.table-items', window.location.href,'undefined','undefined','');
  });
</script>