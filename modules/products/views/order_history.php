<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4><?php echo htmlspecialchars($title); ?></h4>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />
                        <div class="clearfix"></div>
                     <?php render_datatable([
                         'Order ID',
                         'Staff',
                        _l('Order Date'),
                        _l('Total in').' '.$base_currency->name,
                        "Order Type",
                        _l('Status'),'Action',
                        ], 'order-history'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
   $(function(){
        initDataTable('.table-order-history', window.location.href, [1], [1]);
   });

    function delivered(e, id) 
    {
        if (confirm('Are you sure ?')) 
        {
            $.get(admin_url + 'products/kiosk/p_status/'+id+'/deliver',function(response) {
                if(response.success)
                {
                    $(e).closest("tr").find("td:nth-child(6)").html('<span class="label label-success  s-status invoice-status-2">Delivered</span>');
                    $(e).closest("tr").find("td:nth-child(7)").html('');
                }
                
            },'json');
     }
       
    }

    function order_delete(e, id) 
    {
        if (confirm('Are you sure ?')) {
        
        $.get(admin_url + 'products/kiosk/p_status/'+id+'/delete',function(response) {
            if(response.success)
            {
                $(e).closest("tr").remove();
            }
            
        },'json');
    }
       
    }
     function pos_delete(e, id) 
    {

       console.log( $(e).closest("tr"))

        if (confirm('Are you sure ?')) {
        
        $.get(admin_url + 'products/pos/delete_pos/'+id,function(response) {
            console.log(response);
            if(response.success)
            {   
               
                $(e).closest("tr").remove();
            }
            
        },'json');
    }
       
    }
</script>


</body>
</html>
