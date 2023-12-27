<script>


 (function(){
    "use strict";

     $('.wallet_filter').on('click', function() {
          var data = {};
          data.month = $("#month_timesheets").val();
          data.walletid = $("#wallet_walletid").val();
          data.staffid = $("#wallet_staffid").val();
          
          $.post(admin_url + 'eraxon_wallet/reload_wallet_byfilter', data).done(function(response) {
          
          response = JSON.parse(response);
           
            $('#wallet_table_div').empty();

            $('#wallet_table_div').html(response.html);
            var table = $('.table').DataTable();

            $('#wallet-pos').html(response.pos_total);
            $('#wallet-kiosk').html(response.kiosk_total);
            $('#wallet-dock').html(response.dock_total);
            $('#wallet-advance').html(response.advance_total);
          });
        });


})(jQuery);
  </script>