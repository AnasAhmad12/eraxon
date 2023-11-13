<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                  <div class="panel-body">
                    <div class="row">
                      <div class="col-md-6 mt-5">
                        <h4><?php echo '<i class=" fa fa-clipboard"></i> DNC Requests' ?></h4>
                      </div> 
                    </div>
                    <div class="clearfix"></div>
                    <div class="row">

                        <?php render_datatable([
                         'Name',
                         'Phone Number',
                         'Result',
                        ], 'dnc-requests'); ?>

                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>
<script>
 $(function(){
        initDataTable('.table-dnc-requests', window.location.href, [1], [1]);
   });
</script>