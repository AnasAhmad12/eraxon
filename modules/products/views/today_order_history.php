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

                        <div class="col-md-12">
               <div class="table-responsive">
                  <table  id="#myTable" class="table items items-preview invoice-items-preview" data-type="invoice">
                     <thead>
                        <tr>
                        
                           <th class="description" width="20%" align="left"><?php echo "Staff Name" ?></th>
                           <th align="right"><?php echo "Order Date" ?></th>
                           <th align="right"><?php echo "Total" ?></th>

                           <th align="right"><?php echo "Action" ?></th>
                        </tr>
                     </thead>
                     <tbody id="table-body">
                        
                     </tbody>
                  </table>
               </div>
            </div>

                     
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
   

   today_orders=[];
    function add_data_to_table() {
         document.getElementById("table-body").innerHTML="";
         var table_data="";
        
         today_orders.forEach(item => {
         const table_data = `<tr data-id="${item.id}">
   


          <td><a href="<?php echo admin_url('products/kiosk/staff_invoice/')?>${item.id}"> ${item.firstname + " " + item.lastname} (${item.pseudo})</a></td>
      
            <td align="right">${item.order_date}</td>
            <td align="right">${item.total}</td>
            <td align="right">
            <button class="btn btn-success" onclick="delivered(this, '${item.id}')" ${item.status !== 2 ? '' : 'disabled'}>Deliver</button>
            &nbsp;
            <button class="btn btn-danger" onclick="order_delete(this,'${item.id}')">Delete</button>
            </button>
            </td>
            </tr>`;
            document.getElementById("table-body").innerHTML += table_data;
         });
     
     
     }

     function delivered(e, id) 
    {
        if (confirm('Are you sure ?')) 
        {
            $.get(admin_url + 'products/kiosk/p_status/'+id+'/deliver',function(response) {
                if(response.success)
                {
                    $(e).closest("tr").find("td:nth-child(4)").html('<span class="label label-success  s-status invoice-status-2">Delivered</span>');
                    $(e).closest("tr").find("td:nth-child(5)").html('');
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

  
     


(function(){

    
     $.get(admin_url+"products/ajax_get_todays_orders", function(data, status){
        today_orders=JSON.parse(data);
    add_data_to_table();    
    });

    get_orders_data();

  })(jQuery);

function get_orders_data()
{
    setTimeout( function() {
        console.log("Table Updated")
         $.get(admin_url+"products/ajax_get_todays_orders", function(data, status){
        today_orders=JSON.parse(data);
    console.log("DAta",data);
    add_data_to_table();
    get_orders_data();
    });
    },10000);
}


</script>


</body>
</html>
