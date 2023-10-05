<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="panel_s section-heading section-products">
		    <div class="panel-body">
		        <h4 class="no-margin section-text"><?php echo 'Order Detail'; ?></h4>
		    </div>
		</div>
		<div class="row">
		<div class="col-md-12">
			<div class="panel_s">
				<div class="panel-body">
       				 <div class="row">
            			<div class="col-md-6">
							
            				<table class="table table-bordered">
            					
		                        <tr>
		                            <td>Full Name</td>
		                            <td><?php if($staff!==null) {
										echo staff_profile_image($staff->staffid,'staff-profile-image', 'small').' '.$staff->full_name.' ( '.get_custom_field_value($staff->staffid,'staff_pseudo','staff',true).' )';
									}
									else{
										echo "Customer";
									}
									
									
									 ?></td>
		                        </tr>
		                         <tr>
		                            <td>Order Date</td>
		                            <td><?php echo date('Y-M-d', strtotime($master_order->order_date)); ?></td>
		                        </tr>
		                        <tr>
		                            <td>Status</td>
		                            <td><?php echo ($master_order->status == 1)? 'pending':'paid'; ?></td>
		                        </tr>
		                    </table> 
            			</div>
            			<div class="col-md-6">
            				<?php //var_dump($master_order); ?>
            				<?php //var_dump($invoice); ?>
            			</div>
            		</div>
					
            		<div class="row">
            			<div class="col-md-12">

            				<table class="table items items-preview invoice-items-preview" data-type="invoice">
            					<thead>
            						<tr>
            							<th align="center">#</th>
            							<th class="description" width="38%" align="left">Item</th>
            							<th align="right">Qty</th>
            							<th align="right">Rate</th>
            							<th align="right">Amount</th>
            						</tr>
            					</thead>

            					<tbody id="table-body-invoice">
            						
                                   
            						

            					</tbody>
                                

            					</table>
            			</div>
            			<div class="col-md-6 col-md-offset-6">
                    <table class="table text-right tw-text-normal">
                        <tbody>
                            <tr id="subtotal">
                                <td>
                                    <span class="bold tw-text-neutral-700">Sub Total</span>
                                </td>
                                <td id="subtotal2">

                                 </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="bold tw-text-neutral-700">Total</span>
                                </td>
                                <td id="total2">
                                 
                                </td>
                            </tr>
                            </tbody>
                    </table>
                </div >
                <div class="col-md-12" style="    display: inline-flex;justify-content: end;"> 
                <button class="btn btn-success" id="update-button"> Update </button>
                </div>
            		</div>


            	</div>
			</div>
		</div>
	</div>
	</div>
</div>
<?php init_tail(); ?>
<?php require('modules/products/assets/js/purchase-pos-edit.php'); ?>
