<?php defined('BASEPATH') or exit('No direct script access allowed'); 

$this->load->model('products/order_model');
$uid = get_staff_user_id();
$data_dash = $this->order_model->get_kiosk_last_three_order_by_user_id($uid);
//var_dump($data_dash);
?>
<div class="widget" id="widget-<?php echo create_widget_id(); ?>" data-name="<?php echo 'KIOSK'; ?>">
    <div class="panel_s user-data">
        <div class="panel-body home-activity">
            <div class="widget-dragger"></div>

            <div id="container">
               
                    <div class="clearfix"></div>
                    <div class="row">
                        <div class="col-md-12">
                            
                            <h4><p class="padding-5 bold"><i class="fa fa-cart-plus menu-icon"></i>  <?php echo "  KIOSK"; ?></p></h4>
                            <hr class="hr-panel-heading-dashboard">
                            
                            <table  id="#myTable" class="table items items-preview invoice-items-preview" data-type="invoice">
                     <thead>
                        <tr>
                           <th align="">Order Date</th>
                           <th align="center"><?php echo "Subtotal" ?></th>
                 
                        </tr>
                     </thead>
                     <tbody id="table-body">
                        <?php foreach($data_dash as $order){?>
                            <!-- <a href="'.admin_url('products/kiosk/staff_invoice/'.$aRow['id']).'" target="_blank">'.$aRow['id'].'</a> -->
                            <tr> 
                                <td align="">
                                 <a href="<?php echo admin_url('products/kiosk/staff_invoice/' . $order->id); ?>" ><?php echo $order->order_date?>  </a> 
                                </td> 
                                <td align="center">
                                    <?php echo $order->total?>
                                </td>
                            </tr>
                        <?php }?>


                     </tbody>
                  </table>

                        </div>
                    </div>
                
            </div>

        </div>
    </div>
</div>