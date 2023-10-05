<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<style>
#item-code{
    padding: 1.5rem;
}

</style>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="panel_s section-heading section-products">
		    <div class="panel-body">
		        <h4 class="no-margin section-text"><?php echo "Purchase" ?></h4>
		    </div>
		</div>

        <div class="panel_s section-heading section-products">
		    <div class="panel-body align-content-center">
            
            <div class="col-md-12" style="margin-top: 10px;">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="amount">Select Status</label>
                                    <select id="select-status" class="form-control" name="payment_status">
                                    
                                        <option  selected value="Pending">Pending</option>  
                                        <option value="Approved">Approved</option>
                                        
                                    </select>
                                    <span id="status-validation"></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <?php echo render_input('payment_date',"Purchase Date","","date"); ?>
                            <span id="date-validation"></span>
                        </div>
            </div>
                    

                    <div class="col-md-12 input-group">
                            <span class="input-group-addon" >  
                            <i class="fas fa-barcode"></i></span>
                            <?php echo render_input('item-code',"","","",["Placeholder"=> "Enter Product Name ",],); ?>
                    </div>
         
                   
		    </div>

		</div>
		<?php if (!empty($message)) { ?>
		    <!-- Removed HTML entities because in message we are sending HTML format -->
		    <div class="alert alert-danger"><?php echo $message; ?></div>
		<?php } ?>
		<?php echo form_open(uri_string()); ?>
	
		<?php $total = 0; ?>
	<div class="row">
		<div class="col-md-12">
			<div class="panel_s">
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
               <div class="table-responsive">
                  <table  id="#myTable" class="table items items-preview invoice-items-preview" data-type="invoice">
                     <thead>
                        <tr>
                           <th align="center">#</th>
                           <th class="description" width="50%" align="left"><?php echo _l('item') ?></th>

                           <th align=""><?php echo _l('qty') ?></th>
                           <th align=""><?php echo "Product Variant" ?></th>
                         
                           <th align="right"><?php echo _l('rate') ?></th>
                
                          
                           <th align="right"><?php echo "Subtotal" ?></th>
                           <th align="right"><?php echo "Action" ?></th>
                        </tr>
                     </thead>
                     <tbody id="table-body">
                        
                     </tbody>
                  </table>
               </div>
            </div>
        </div>
        <?php if (0 == get_option('coupons_disabled')) { ?>
            <div class="row">
                <div class="col-md-6 col-md-offset-6">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>
                                    <span class="bold"><?php echo _l('coupon_code_label');?></span>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" id="coupon_code" class="form-control" value="" />
                                </td>
                                <td>
                                    <button type="button" class="btn btn-success pull-right apply_coupon"><i class="fa fa-check"></i> <?php echo _l('apply_coupon'); ?></button>
                                    <button type="button" class="btn btn-danger pull-right remove_coupon hide"><i class="fa fa-check"></i> <?php echo _l('remove_coupon'); ?></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php } ?>
        <div class="row">
            <div class="col-md-6 col-md-offset-6">
                <table class="table text-right">
                    <tbody>
                        <tr id="subtotal">
                            <td>
                                <span class="bold"><?php echo _l('subtotal');?></span>
                            </td>
                            <td class="subtotal" id="items-subtotal">
                                
                            </td>
                        </tr>
                        <tr id="coupon_discount" class="hide">
                            <td>
                                <span class="bold"><?php echo _l('coupon_discount');?></span>
                                <?php echo form_hidden('coupon_id', ''); ?>
                            </td>
                            <td class="coupon_discount">
                            </td>
                        </tr>
                        <?php foreach ($all_taxes as $tax) { ?>
                            <tr class="tax-area">
                                <td>
                                    <span class="bold"><?php echo htmlspecialchars($tax['taxname'] . '(' . $tax['taxrate'].'%)'); ?></span>
                                </td>
                                <td>
                                    <?php $total += array_sum($init_tax[$tax['name']]); echo app_format_money(array_sum($init_tax[$tax['name']]), $base_currency->name); ?>    
                                </td>
                            </tr>
                        <?php } ?>
                        <?php if (!empty($shipping_cost)) { ?>
                            <tr id="shipping_costs">
                                <td>
                                    <span class="bold">
                                        <?php 
                                            echo _l('shipping_costs');
                                            echo "(" . app_format_money($shipping_base, $base_currency->name) . "+" . $shipping_tax . "%)";
                                        ?>
                                    </span>
                                </td>
                                <td class="shipping_costs">
                                    <?php echo form_hidden('shipping_cost', $shipping_cost); ?>
                                    <?php echo app_format_money($shipping_cost, $base_currency->name); ?>
                                </td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td><span class="text-danger bold"><?php echo _l('payable_amount');?></span></td>
                            <td>
                                <span class="text-danger total" id="payable-amount"></span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="panel_s section-heading section-products">
    <div class="panel-body">
    <input type="text" id="selectedDate" style="display: none;">
        <button class="btn btn-success pull-right" id="purchase-button" ><i class="fa fa-check"></i> <?php echo "Update Purchase"; ?></button>
    </div>
</div>
<?php echo form_close(); ?>
		</div>
	</div>	

	</div>
</div>
<?php init_tail(); ?>

<script type="text/javascript" src="<?php echo base_url('assets/plugins/accounting.js/accounting.js'); ?>"></script>
<script type="text/javascript">
    "use strict";
    // var base_currency = <?php echo htmlspecialchars($base_currency->id); ?>
</script>
<?php if (!empty($message)) { ?>
    <script type="text/javascript">
        $(function() {
            // alert_float('warning','<?php echo $message; ?>',6000);
        });
    </script>
<?php } ?>
<script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/css/autoComplete.min.css">
<script>
        

        $(function() {

        const session_id = <?php echo  json_encode($session_id); ?>;
        localStorage.setItem('sessionId_edit', session_id)
        let sessionData={'sessionId':localStorage.getItem('sessionId_edit')};


        const purchase_detail = <?php echo  json_encode($purchase_detail); ?>;
        console.log(purchase_detail);

        let subTotal;





        var purchase_items=[];
        console.log('Purchase Items');

        get_purchase_item(sessionData)
        .then(function(purchase_items) {
        // Handle the data here
        console.log("Data received:", purchase_items);
        add_data_to_table();
    })
    .catch(function(error) {
        // Handle errors here
        console.error("Error:", error);
    });

       
        
        

        function post_product_data(post_data = {}) {
                    $.ajax({
                        url: site_url+'products/purchase/add_purchase_item',
                        type: 'POST',
                        dataType: 'json',
                        data : post_data,
                        success : function (data) {
                            alert_float('success', "Item Added"); 
                            get_purchase_item(sessionData)
                        }
                    })
        }

        function get_purchase_item(post_data = {}) {
        return new Promise(function(resolve, reject) {
        $.ajax({
            url: site_url + 'products/purchase/get_purchase_item',
            type: 'POST',
            dataType: 'json',
            data: post_data,
            success: function(data) {
                purchase_items = data;
                console.log("Purchase Updated", purchase_items);
                add_data_to_table();
                resolve(purchase_items);

            },
            error: function(xhr, status, error) {
                reject(error);
            }
        });
    });
    }

    
        function add_data_to_table() {
         
            document.getElementById("table-body").innerHTML="";
            subTotal=0;
            console.log("Table Intialized");
            product_data_table=$("#table-body");
            var table_data="";
            count=0;
            purchase_items.forEach(item => {
                count=count+1;
            const table_data=`<tr data-id="${item.id}"> <td> ${count} </td>   <td> ${item.product_name} </td> <td> <input type="number" class="quantity" name="quantity" value="${item.quantity}" style="padding:0.4rem;"min="1" max="100"> </td> <td>${item.product_variant==null?"N/A":item.product_variant} </td> <td align="right">${item.net_unit_cost} </td> <td align="right">${item.subtotal} </td> <td align="right"> <button type="button" id="openModalBtn"  style="padding:0.4rem" class="btn btn-danger btn-xs" > <i class="fas fa-trash-alt"></i> </button> </td></tr>`;
            document.getElementById("table-body").innerHTML +=table_data;
            subTotal=subTotal+parseFloat(item.subtotal);
           
        });
       
            document.getElementById('items-subtotal').innerHTML=`<?=$base_currency->name?> ${subTotal}`;
            document.getElementById('payable-amount').innerHTML=`<?=$base_currency->name?> ${subTotal}`;
   
        }

        $(document).on('click','#openModalBtn',function(e){
            e.preventDefault();
            var ele = $(this);
            var id =ele.parents("tr").attr("data-id");
            console.log( "ID is",ele.parents("tr").attr("data-id"));
            if(confirm("Are you sure want to remove?")) {
                $.ajax({
                    url: site_url+'products/purchase/delete_purchase_item',
                    type: "POST",
                    data: {
                        id: id
                    },
                    success: function (response) {
                        alert_float('success', "Item Deleted"); 
                        console.log(response);
                        get_purchase_item(sessionData);
                    }

                });

            }
        });

        $(document).on('change','.quantity',function(e){
            e.preventDefault();
            var ele = $(this);
            var id=ele.parents("tr").attr("data-id");
            console.log(id);
           
            $.ajax({
                        url: site_url+'products/purchase/update_purchase_item_quantity',
                        type: 'POST',
                        dataType: 'json',
                        data : {id:id,val:ele.parents("tr").find(".quantity").val()},
                        success : function (data) {
                            console.log('Updated');
                            console.log(data);
                            alert_float('success', "Quantity Updated"); 
                            get_purchase_item(sessionData);
                            
                        }
                    })
           
        });


        const [year, month, day] = purchase_detail[0].date.split(" ")[0].split("-");
        const dateObject = new Date(year, month - 1, day); 
        const formattedDate = dateObject.toISOString().split("T")[0];
        var a= $("input[name='payment_date']").val(formattedDate);
        $("#select-status").val(purchase_detail[0].payment_status);


        




        $(document).on('click','#purchase-button',function(e){
            e.preventDefault();

        var selectedStatus = $("select[name='payment_status']").val();
        var purchaseDate = $("input[name='payment_date']").val();
        
        
        var statusValidationMessage = $("#status-validation");
        var dateValidationMessage = $("#date-validation");
        

        statusValidationMessage.text("");
        dateValidationMessage.text("");
        
        
        if (selectedStatus === "") {
            statusValidationMessage.text("Please select a status").css("color", "red");
        }
        
        
        if (purchaseDate === "") {
            dateValidationMessage.text("Please enter a purchase date").css("color", "red");
        }
        
        // Check if either field has an error
        if (selectedStatus === "" || purchaseDate === "") {
            return false; // Prevent form submission
        }
        else{
            if(purchase_items.length>0){
             console.log(selectedDate);
                $.ajax({
                    url: site_url+'products/purchase/update_purchase',
                    type: "POST",
                    data: {
                        id:purchase_detail[0].id,
                        session_id:sessionData['sessionId'],
                        grand_total:subTotal,
                        created_by:"<?php echo get_staff_full_name();?>",
                        date:purchaseDate,
                        payment_status:selectedStatus
                    },
                    success: function (response) {
                        alert_float('success', "Purchased"); 
                        console.log("Date from ",response);
                        localStorage.removeItem('sessionId');
                        window.history.back();
                       
                    }

                });
            }
            else{
                alert("Please Select Items");
            }
        }       
        });

 
    $("input").autocomplete({
    source: site_url+'products/purchase/get_product_master',
    autoFocus:true,
    select: function(event, ui) {
        post_data=ui.item.product_detail;
        post_data= $.extend({}, post_data, sessionData);
        if(ui.item.selected_variation===undefined){
          console.log("Ad");
        }
        else{
            let selectedvariation={'selectedVariation':ui.item.selected_variation['variation_value']};
            post_data= $.extend({}, post_data, selectedvariation);
        }
      post_product_data(post_data);
    //   get_purchase_item(sessionData);
    //   add_data_to_table();
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
        });








 </script>
