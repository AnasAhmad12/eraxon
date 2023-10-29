<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<style>
#item-code{
    padding: 1.5rem;
}

</style>
<?php init_head(); ?>
<div id="wrapper">
	<div id="pdf_file" class="content">
		
        <div class="panel_s section-heading section-products">
		    <div class="panel-body align-content-center">
                <div style="justify-content: center; display: inline-flex; margin-bottom:10px" class="col-md-12  text-center">
                    <div  style="width:25%;">
                    <?php get_dark_company_logo(); ?>
                    </div>
                </div>
                <div class="">
                <table style="justify-content: center;"  class="table  table-bordered table-sm">
                    <tr>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                    <tr>
                        <td id="status"></td>
                        <td id="date_se"></td>
                    </tr>
                </table>
                </div>
                

                      
		    </div>

		</div>
		
		
	
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
                                            
                                            </tr>
                                        </thead>
                                        <tbody id="table-body">
                                            
                                        </tbody>
                                    </table>
                                </div>
                                </div>
                            </div>
                        
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
                        <button class="btn btn-success pull-right" id="create_pdf" data-html2canvas-ignore="true"><i class="fa fa-check"></i> <?php echo "Download PDF"; ?></button>
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



        $('#create_pdf').on('click', function (){ 
              add_data_to_table();
            var element = document.getElementById('pdf_file');         
            var opt = {
            margin:       0,
            filename:     'Purchse-'+purchase_detail[0].id+'-'+purchase_detail[0].date+'.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2, ignoreElements:'true' },
            jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
            };
            html2pdf().set(opt).from(element).save();

            });

    

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
            const table_data=`<tr data-id="${item.id}"> <td> ${count} </td>   <td> ${item.product_name} </td> <td> ${item.quantity} </td> <td>${item.product_variant==null?"N/A":item.product_variant} </td> <td align="right">${item.net_unit_cost} </td> <td align="right">${item.subtotal} </tr>`;
            document.getElementById("table-body").innerHTML +=table_data;
            subTotal=subTotal+parseFloat(item.subtotal);
           
        });
       
            document.getElementById('items-subtotal').innerHTML=`<?=$base_currency->name?> ${subTotal}`;
            document.getElementById('payable-amount').innerHTML=`<?=$base_currency->name?> ${subTotal}`;
   
        }

      
        const [year, month, day] = purchase_detail[0].date.split(" ")[0].split("-");
        const dateObject = new Date(year, month - 1, day); 
        const formattedDate = dateObject.toISOString().split("T")[0];
        $("#date_se").html(formattedDate);
        $("#status").html(purchase_detail[0].payment_status);


        




        $(document).on('click','#purchase-print-button',function(e){
            e.preventDefault();
             alert("printed")
        });

 
     
        


    });





 </script>
<script type="text/javascript" src="<?php echo module_dir_url('products', 'assets/js/html2canvas.js'); ?>"></script>
<script type="text/javascript" src="<?php echo module_dir_url('products', 'assets/js/es6-promise.auto.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo module_dir_url('products', 'assets/js/jspdf.debug.js'); ?>"></script>
<script type="text/javascript" src="<?php echo module_dir_url('products', 'assets/js/html2pdf.bundle.min.js'); ?>"></script>


