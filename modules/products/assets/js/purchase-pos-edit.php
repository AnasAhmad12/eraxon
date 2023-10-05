<script>




$(document).ready(function() {



    const invoice = <?php echo  json_encode($invoice); ?>;
    const master_order=<?php echo  json_encode($master_order); ?>;


    $(document).on('click','#update-button',function(e){
        $.ajax({
                        url: site_url+'products/pos/update_order',
                        type: "POST",
                        data: {
                    
                            order_item:invoice,
                            subtotal:subTotalInvoice
                          
                        },
                        success: function (response) {
                            window.location.replace(admin_url+'products/pos/index/');
                        alert_float('success', "Order Updated"); 
                            console.log("Sa",response)
                        
                        }
                    });

        });

    function invoice_table() {
         
        console.log();
         document.getElementById("table-body-invoice").innerHTML="";
         subTotalInvoice=0;
         
         product_data_table=$("#table-body-invoice");
         var table_data="";
         count=0;
         invoice.forEach(item => {
             console.log(count)
         const table_data=`
         <tr data-id="${item.product_id}">
            							
                                        <td align="center" width="5%">${++count}</td>
            							<td class="description" align="left;" width="33%">
                                        <span style="font-size:px;">
                                        <strong>${item.product_name+" "+ item.value}</strong>
                                        </span>
            							</td>
            							<td align="right" width="9%">
                                        <input type="number" class="invquantity" name="quantity" value="${item.qty}" style="padding:0.4rem;"min="1" max="100">
                                        </td>
            							<td align="right" width="9%">${item.rate}</td>
            							<td class="amount" align="right" width="9%">${item.qty*item.rate}</td>
							
            						</tr>
         `
         document.getElementById("table-body-invoice").innerHTML +=table_data;
         subTotalInvoice=subTotalInvoice+parseFloat(item.rate)*item.qty;

         console.log("TOTS", subTotalInvoice)
        
         
     });

     document.getElementById("subtotal2").innerHTML=`<?=$base_currency->name?> ${subTotalInvoice}`;
     document.getElementById("total2").innerHTML=`<?=$base_currency->name?> ${subTotalInvoice}`;


    }
    
    invoice_table();

    console.log(invoice)


   $(document).on('change','.invquantity',function(e){
    e.preventDefault();
    var id=$(this).parents("tr").attr("data-id");
    console.log("ID is",id);

    query=invoice.find(item => item.product_id==id); 

    if(query.variant_qty!=null){
       
        console.log( "Max Quantity",parseInt( query.variant_qty)+parseInt( query.qty))
        if(parseInt($(this).val())> (parseInt( query.variant_qty))){
        alert_float('danger','Max Quantity Available '+( parseInt(query.variant_qty)))
        $(this).val(parseInt(query.variant_qty));
        query.qty=parseInt(query.variant_qty);
        query.subtotal=0;

    }
    else{
        alert_float('success','Quantity Updated');
        query.qty=$(this).val();
        query.subtotal=0;
   
    }
}
    else{
        if(parseInt($(this).val())> (parseInt( query.quantity_number))){
        alert_float('danger','Max Quantity Available '+( parseInt(query.quantity_number)))
        $(this).val(parseInt(query.variant_qty));
        query.qty=parseInt(query.quantity_number);
        query.subtotal=0;
        
    }
    else{
        alert_float('success','Quantity Updated');
        query.qty=$(this).val();
        query.subtotal=0;
   
    }
    
    }

    
    invoice_table();
});

        });

</script>