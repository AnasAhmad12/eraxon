<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div id="result-container" class="row">
      <div class="col-md-12">
        <div class="panel_s " id="TableData">
          <div class="panel-body">
            <?php if (has_permission('purchase', '', 'create')) { ?>
              <a href="<?php echo admin_url('products/purchase/add_purchase'); ?>"
                class="btn btn-info pull-left display-block">
                <?php echo "Add Purchase" ?>
              </a>
            <?php } ?>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">

            <div class="panel_s">
              <div class="panel-body panel-table-full">
                <table class="table dt-table" data-order-col="1" data-order-type="asc">
                  <thead>

                    <th>Date</th>
                    <th>Grand Total</th>
                    <th>Status</th>
                    <th>Created By</th>
                    <th>Updated By</th>
                    <th>
                      <?php echo _l('options'); ?>
                    </th>
                  </thead>
                  <tbody>
                    <?php foreach ($purchases as $row): ?>
                      <tr data-id=<?php echo $row->id ?>>
                        <td>
                          <?php echo date(substr($row->date,0,10)); ?>
                        </td>
                        <td><b>
                            <?php echo $row->grand_total; ?> PKR
                          </b></td>
                        <td><b>
                            <?php if ($row->payment_status == "Pending") {
                              echo '<span class="label label-warning">' . $row->payment_status . '</span>';

                            } else {
                              echo '<span class="label label-success">' . $row->payment_status . '</span>';
                            } ?>
                          </b>
                        </td>
                        <td>
                          <?php echo $row->created_by; ?>
                        </td>
                        <td>
                          <?php echo $row->updated_by; ?>
                        </td>
                        <td>

                          <?php if (has_permission('purchase', '', 'delete')) { ?>
                            <button class="btn btn-danger btn-sm remove-from-cart" <?php if ($row->payment_status == "Approved")
                              echo "disabled" ?> id="purchase_delete"><i
                                  class="fas fa-trash-alt"></i></button>
                          <?php } ?>

                          <?php if (has_permission('purchase', '', 'edit')) { ?>
                            <?php echo form_open(admin_url('products/purchase/edit_purchase'), ['class' => 'edit-form', 'method' => 'GET', "style" => "display:inline"]); ?>
                            <input type="text" name="id" value="<?php echo $row->id; ?>" style="display:none;">
                            <button class="btn btn-primary btn-sm remove-from-cart" <?php if ($row->payment_status == "Approved")
                              echo "disabled" ?> id="purchase_edit"><i
                                  class="fas fa-edit"></i></button>
                            <?php echo form_close(); ?>
                          <?php } ?>

                          <?php if (has_permission('purchase', '', 'view')) { ?>
                            <?php echo form_open(admin_url('products/purchase/view_purchase_detail'), ['class' => 'view-purchase', 'method' => 'GET', "style" => "display:inline"]); ?>
                            <input type="text" name="id" value="<?php echo $row->id; ?>" style="display:none;">

                            <button class="btn btn-primary btn-sm remove-from-cart" id="purchase_view">
                              <i class="fas fa-eye"></i>
                            </button>
                            <?php echo form_close(); ?>
                          <?php } ?>


                        </td>

                      </tr>
                    <?php endforeach; ?>
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

<script type="text/javascript">
  $(function () {
    // initDataTable('.table-products', window.location.href,'undefined','undefined','');
    // // $('.edit-form').css("display","inline");
    // $('.delete-form').css("display","inline");

    $(document).on('click', '#purchase_edit', function (e) {

      var ele = $(this);
      var id = ele.parents("tr").attr("data-id");
      console.log("ID is", ele.parents("tr").attr("data-id"));

      $('.edit-form').attr('action', site_url + 'products/purchase/edit_purchase')
      $('.edit-form').submit();

    });

    $(document).on('click', '#purchase_delete', function (e) {

      var ele = $(this);
      var id = ele.parents("tr").attr("data-id");
      console.log("ID is", ele.parents("tr").attr("data-id"));

      if (confirm("Are you sure want to remove?")) {
        $.ajax({
          url: site_url + 'products/purchase/delete_purchase',
          type: "POST",
          data: {
            id: id
          },
          success: function (response) {
            alert_float('success', "Item Deleted");
            console.log(response);
            location.reload();
            // get_purchase_item(sessionData);
          }

        });

      }


    });



    $(document).on('click', '#purchase_view', function (e) {

      var ele = $(this);
      var id = ele.parents("tr").attr("data-id");
      console.log("ID is", ele.parents("tr").attr("data-id"));

      $('.edit-form').attr('action', site_url + 'products/purchase/view_purchase_detail')
      $('.edit-form').submit();

    });





  });
</script>