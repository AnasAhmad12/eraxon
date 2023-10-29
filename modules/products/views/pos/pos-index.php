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
		        <h4 class="no-margin section-text"><?php echo "POS - Point of Sale" ?></h4>
		    </div>
		</div>

        <div class="panel_s section-heading section-products">
		    <div class="panel-body align-content-center">
            
                    <div class="col-md-12 input-group">
                            <span class="input-group-addon" >  
                            <i class="fas fa-barcode"></i></span>
                            <?php echo render_input('item-code',"","","",["Placeholder"=> "Enter Product Name "],); ?>
                    </div>
		    </div>

		</div>

		
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

    <div class="col-md-12">
        <div class="form-group">
            <label for="staffid" class="control-label">Select Staff</label>
            <select name="staff_id" data-live-search="true" id="staffid" class="form-control selectpicker">
            <option value="0" selected>Customer</option>
                <?php foreach ($staff_members as $staff) {?>
                <option value="<?php echo $staff['staffid']; ?>" >
                    <?php echo $staff['full_name'].' ('.get_custom_field_value($staff['staffid'],'staff_pseudo','staff',true).')'; ?></option>
                <?php } ?>
            </select>
      </div>
    </div>
    <div class="radio-buttons col-md-12">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="Cash" name="order-type" id="flexRadioDefault1" checked >
            <label class="form-check-label" for="flexRadioDefault1">
                Cash
            </label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="Wallet" name="order-type" id="flexRadioDefault2">
            <label class="form-check-label" for="flexRadioDefault2">
                Wallet
            </label>
        </div>
    </div>

    <div class="col-md-12">

   
      <?php if (has_permission('POS', '', 'create')) {?>
    <button class="btn btn-success pull-right" id="purchase-button" ><i class="fa fa-check"></i> <?php echo "Place Order"; ?></button>
      <?php }?>

</div>
    </div>
</div>

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


 <?php require('modules/products/assets/js/purchase-pos.php'); ?>
