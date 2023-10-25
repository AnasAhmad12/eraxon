<script>
order_item=[];


$("input").autocomplete({
    source: site_url+'products/purchase/get_product_master',
    autoFocus:true,
    
    select: function(event, ui) {
        post_data=ui.item.product_detail;

        console.log('varrr',ui.item.selected_variation)

        console.log('qtyyy',ui.item.var_qty);

        if(ui.item.selected_variation===undefined){
         false;
        }
        else{
            let selectedvariation={'selectedVariation':ui.item.selected_variation['variation_value']};
            post_data= $.extend({}, post_data, selectedvariation);

        }
        console.log("Post",post_data)

        console.log("Order Items", order_item)
    
    if(post_data.variations){
        variation=post_data.variations.find(item=> item.variation_value==post_data.selectedVariation); 
        query=order_item.find(item=>item.product_id==post_data.id && item.variation_value_id==variation.variation_value_id);
        console.log("Query",query)
        if(query){
            
            query.qty+=1;
            query.subtotal=parseInt(query.qty)*variation.rate;
        }
        else{
            var order_data={
                id:order_item.length+1,
                product_id:post_data.id,
                product_variation_id:variation.id,
                variation_value_id:variation.variation_value_id,
                display_name:post_data.product_name+" - "+ variation.variation_value +" ("+variation.product_code+")",
                product_name:post_data.product_name,
                variation:post_data.selectedVariation,
                rate:variation.rate,
                max_qty:ui.item.var_qty,
                qty:1,
                subtotal:variation.rate*1

               
            } 

            order_item.push(order_data);

        }
       
    }
    else{
        query=order_item.find(item=> item.product_id==post_data.id)
        if(query){
            query.qty+=1;
            query.subtotal=parseInt(query.qty)*query.rate;
        }
        else{
            var order_data={
                id:order_item.length+1,
                product_id:post_data.id,
                product_variation_id:null,
                display_name:post_data.product_name+" ("+post_data.product_code+")",
                product_name:post_data.product_name,
                variation:null,
                rate:post_data.rate,
                max_qty:ui.item.var_qty,
                
                qty:1,
                subtotal:post_data.rate*1
            }
            order_item.push(order_data);

        }

       
    }
    add_data_to_table();


    event.preventDefault();

    },

    response: function(event, ui) {
        console.log("log",$(this).val().length==13);
        if (ui) {
            ui.item = ui.content[0];
            if ($(this).val().length === 13) {
                // Automatically select the first option if it has a length of 13 characters
                $(this).data('ui-autocomplete')._trigger('select', 'autocompleteselect', ui);
                $(this).autocomplete('close');
                $(this).removeClass('ui-autocomplete-loading');
                $(this).val("");
            }
        }
    }        
  });

// let subTotal=order_item.reduce((i,c)=>{
//     return i.
// })

  function add_data_to_table() {
         
    document.getElementById("table-body").innerHTML="";
    subTotal=0;
    console.log("Table Intialized");
    product_data_table=$("#table-body");
    var table_data="";
    count=0;
    order_item.forEach(item => {
        count=count+1;
    const table_data=`<tr data-id="${item.id}"> <td> ${count} </td>   <td> ${item.display_name}  </td> <td> <input type="number" class="quantity" name="quantity" value="${item.qty}" style="padding:0.4rem;"min="1" max="100"> </td> <td>${item.variation==null?"N/A":item.variation} </td> <td align="right">${item.rate} </td> <td align="right">${item.qty*item.rate} </td> <td align="right"> <button type="button" id="delete_button"  style="padding:0.4rem" class="btn btn-danger btn-xs" > <i class="fas fa-trash-alt"></i> </button> </td></tr>`;
    document.getElementById("table-body").innerHTML +=table_data;
    subTotal=subTotal+parseFloat(item.subtotal);
   
    
});

document.getElementById('items-subtotal').innerHTML=`<?=$base_currency->name?> ${subTotal}`;
document.getElementById('payable-amount').innerHTML=`<?=$base_currency->name?> ${subTotal}`;


}




$(document).on('change','.quantity',function(e){
    e.preventDefault();

    var id=$(this).parents("tr").attr("data-id");
    query=order_item.find(item => item.id == id);
    $qty=$(this).val();

    console.log('Input',typeof $qty)
    console.log('Max ',typeof query.max_qty )


    if(parseInt($qty)>parseInt(query.max_qty)){
                            alert_float('danger', "Max Quantity Available "+query.max_qty); 
                            $('.quantity').val(query.max_qty);
                            query.qty=query.max_qty;
                            query.subtotal=query.max_qty*query.rate;
                            add_data_to_table();


                        }
                        else{
                            query.qty=$qty;
                            query.subtotal=$qty*query.rate;
                            add_data_to_table();
                            alert_float('success', "Qunatity Updated");
                        }
   
});


$(document).on('click','#delete_button',function(e){
    e.preventDefault();
    var ele = $(this);
    var id =ele.parents("tr").attr("data-id");
    console.log( "ID is",ele.parents("tr").attr("data-id"));
    if(confirm("Are you sure want to remove?")) {
       order_item=order_item.filter(item=>item.id!=id);
       add_data_to_table();
    }
});

$(document).on('click','#purchase-button',function(e){

    e.preventDefault();
    order_typ=$("input[name='order-type']:checked").val();
    var staff_id = $('select[name="staff_id"]').val();
    console.log("Order",order_typ);
    

    if(order_item.every(item => parseInt(item.qty) <= parseInt(item.max_qty))){
        $.ajax({
                    url: site_url+'products/pos/add_order',
                    type: "POST",
                    data: {
                        order_items:order_item,
                        order_type:order_typ,
                        staff_id:staff_id
                    },
                    success: function (response) {
                        location.reload();//location.replace(admin_url+'products/kiosk/staff_invoice/'+response);
                        alert_float('success', "Order Placed"); 
                        console.log("Sa",response)
                       
                    }

                });
    }else{
        alert_float('Danger', "Invalid Quantity");
    }
   
});

$(document).on('change','#staffid',function(e){
    if($(this).val()==0){
        $("#flexRadioDefault2").prop("disabled", true);
    }
    else{
        $("#flexRadioDefault2").prop("disabled", false);
    }
});


$(document).ready(function() {


    const invoice = <?php echo  json_encode($invoice); ?>;
    console.log(invoice);
    $("#item-code").focus();
    
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
         <tr data-id="${item.id}">
            							
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
         subTotalInvoice=subTotalInvoice+parseFloat(item.subtotal);
        
         
     });

     document.getElementById("subtotal2").innerHTML=`<?=$base_currency->name?> ${subTotalInvoice}`;
     document.getElementById("total2").innerHTML=`<?=$base_currency->name?> ${subTotalInvoice}`;


    }



  

        


          staffId= $('select[name="staff_id"]').val()
          console.log('sta',staffId);
          if(staffId==0){
            console.log("SSSSS");
            $("#flexRadioDefault2").prop("disabled", true);
          }
        });



   

</script>