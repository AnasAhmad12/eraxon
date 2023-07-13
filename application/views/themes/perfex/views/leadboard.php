<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="mtop40">
    <div class="col-md-6 col-md-offset-2 text-center forgot-password-heading">
        <h1 class="tw-font-semibold mbot20">All Staff Live Leads (<?php echo date('d-M-Y'); ?>)</h1>
    </div>
    <div class="col-md-6 col-md-offset-2">
        <div class="panel_s">
            <div class="panel-body">

                <?php //var_dump($staffs) ?>
                <table class="table customizable-table dataTable no-footer">
                  <thead>
                    <tr>
                      <th>Image</th>
                      <th>Full Name</th>
                      <th>Total Leads</th>
                    </tr>
                  </thead>
                  <tbody id="leads-body">
                      
                  </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
(function(){
  $.get(site_url+"authentication/ajaxLeadBoard", function(data, status){
       $('#leads-body').html(data);
       
    });
  get_lead_data();

  })(jQuery);

  var counter = 1
  function get_lead_data()
  {
    setTimeout( function() {
    $.get(site_url+"authentication/ajaxLeadBoard", function(data, status){
       $('#leads-body').html(data);
       get_lead_data();
        //counter++;
      // console.log(counter);
    });
  },10000);
  }
</script>