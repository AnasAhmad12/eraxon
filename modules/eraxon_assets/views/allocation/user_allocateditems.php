<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="panel_s section-heading section-products">
            <div class="panel-body">
                <h4 class="no-margin section-text">
                    <?php echo 'Allocation Details'; ?>
                </h4>
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
                                        <td>

                                           <?php echo  get_staff_full_name($allocation_master[0]->staff_id) ?> 
                                        </td>
                                    </tr>
                                    <tr>
                                    <td>Allocation Date</td>
                                    <td><?php echo date('Y-M-d', strtotime($allocation_master[0]->allocation_date)); ?></td>
                                </tr>

                                </table>
                            </div>
                            <div class="col-md-6">

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">

                                <table class="table items items-preview invoice-items-preview" data-type="invoice">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th class="description" width="38%">Item</th>
                                            <th>Serial Number</th>
                                            <th >Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php $count=0; foreach($allocation_item as $ai){ ?>
                                            
                                            <tr>
                                            <td> <?php echo $count=$count+1; ?> </td>
                                            <td > <?php echo $ai->item_name; ?> </td>
                                            <td> <?php echo   $ai->serial_number; ?> </td>
                                            <td> <?php echo   $ai->qty; ?> </td>

                                            </tr>
                                        
                                        <?php } ?>

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