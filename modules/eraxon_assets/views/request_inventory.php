<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<style>
    ul.ui-front {
        z-index: 30000;
    }

    ul.ui-autocomplete {
        display: block;
    }
</style>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="tw-mb-2 sm:tw-mb-4">
                    <?php if (has_permission('asset-request', '', 'create') || is_admin()) { ?>
                        <a href="#" onclick="new_advance_salary(); return false;" class="btn btn-primary">
                            <i class="fa-regular fa-plus tw-mr-1"></i>
                            <?php echo "Request Inventory"; ?>
                        </a>
                    <?php } ?>
                </div>

                <div class="panel_s">

                    <div class="panel-body panel-table-full">
                        <table class="table dt-table" data-order-col="1" data-order-type="asc">

                            <thead>

                                <th>Staff Name</th>

                                <th>Item Name</th>
                                <th> Serial Number </th>
                                <th>Request Status</th>
                                <?php if (has_permission('asset-request','','edit')) { ?>
                                    <th>
                                        <?php echo _l('options'); ?>
                                    </th>
                                <?php } ?>

                            </thead>
                            <tbody>
                                <?php foreach ($request_inventory as $l) { ?>

                                    <tr data-item="<?php echo $l->status ?>" data-id="<?php echo $l->id ?> ">

                                        <td>
                                            <?php echo get_staff_full_name($l->staff_id) ?>
                                        </td>
                                        <td>
                                            <?php echo $l->item_name ?>
                                        </td>
                                        <td>
                                            <?php echo $l->serial_number ?>
                                        </td>

                                        <td>
                                            <?php if ($l->status == 0) {
                                                echo "Pending";
                                            } elseif ($l->status == 1) {
                                                echo "Approved";
                                            } else {
                                                echo 'Rejected';
                                            }

                                            ?>
                                        </td>


                                        <td>
                                            <div class="tw-flex tw-items-center tw-space-x-3">

                                            <?php if (get_staff_user_id()==get_option('stock_in_purchase_approval')) { ?>
                                                    <a href="#"
                                                        class="edit_request tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700"
                                                        data-hide-from-client="0">
                                                        <i class="fa-regular fa-pen-to-square fa-lg"></i>
                                                    </a>
                                                <?php }
                                                if (has_permission('asset-request', '', 'delete') || is_admin()) { ?>
                                                    <a href="<?php echo admin_url('eraxon_asset/delete_or/' . $l->id); ?>"
                                                        class="tw-mt-px tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 _delete">
                                                        <i class="fa-regular fa-trash-can fa-lg"></i>
                                                    </a>
                                                <?php } ?>
                                            </div>
                                        </td>




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
<div class="modal fade xl" id="other_requests" tabindex="" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('eraxon_assets/eraxon_assets_request_inventory/request_inventory')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span class="add-title">Select Item</span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="additional_loss"></div>
                        <input type="hidden" name="staff_id" value="<?php echo $current_user->staffid; ?>">
                    </div>
                    <div class="col-md-12 input-group">
                        <span class="input-group-addon">
                            <i class="fas fa-barcode"></i></span>
                        <?php echo render_input('item-code', "", "", "", ["Placeholder" => "Enter Item Name ",], ); ?>
                    </div>
                </div>

                <div id="item"> </div>



                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <?php echo _l('close'); ?>
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <?php echo _l('submit'); ?>
                    </button>
                </div>
            </div>
            <!-- /.modal-content -->
            <?php echo form_close(); ?>
        </div>
        <!-- /.modal-dialog -->
    </div>
</div>


<div class="modal fade" id="status_edit" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('eraxon_assets/Eraxon_assets_request_inventory/edit_status')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span class="add-title">Edit Status</span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="request_id"></div>
                        <input type="hidden" name="staff_id" value="<?php echo $current_user->staffid; ?>">
                    </div>

                    <div class="col-md-12" id="status">
                        <label for="status" class="control-label">Request Status</label>
                        <select name="status" class="selectpicker" id="status" data-width="100%"
                            data-none-selected-text="<?php echo _l('none_type'); ?>">
                            <option value="0">Pending</option>
                            <option value="1">Accepted</option>
                            <option value="2">Rejected</option>
                        </select>
                    </div>


                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo _l('close'); ?>
                </button>
                <button type="submit" class="btn btn-primary">
                    <?php echo _l('submit'); ?>
                </button>
            </div>
        </div>
        <!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div>
    <!-- /.modal-dialog -->
</div>
<?php init_tail(); ?>
<script>
    $(function () {
        appValidateForm($('form'), {
            item: 'required',
        }, manage_advance_salary);
        $('#other_requests').on('hidden.bs.modal', function (event) {
            $('#additional').html('');
            $('.add-title').removeClass('hide');
            $('.edit-title').removeClass('hide');
        });
    });

    function manage_advance_salary(form) {
        var data = $(form).serialize();
        var url = form.action;
        $.post(url, data).done(function (response) {

            window.location.reload();

        });
        return false;
    }

    function new_advance_salary() {
        $('#other_requests select[name="request_type"]').val('');
        $('#other_requests').modal('show');
        $('.edit-title').addClass('hide');
    }


    $(document).on('click', '.edit_request', function (e) {
        e.preventDefault();

        var request_status = $(this).parents("tr").attr("data-request");
        var item_status = $(this).parents("tr").attr("data-item");



        var id = $(this).parents("tr").attr("data-id");

        console.log("SSAS ID ", id);

        // setDefaultOptionByStatus(request_status, item_status);



        $('#request_id').append(hidden_input('id_request', id));
        $('#status_edit').modal('show');
        $('.add-title').addClass('hide');

    });


    function edit_as_request(invoker, id) {

        console.log("ID", id);

    }

    function setDefaultOptionByStatus(status, item_status) {
        var selectElement = $('select[name="request_status"]');
        selectElement.find('option').each(function () {
            if ($(this).val() === status) {
                $(this).prop('selected', true);
            } else {
                $(this).prop('selected', false);
            }
        });
        selectElement.selectpicker('refresh');

        var item = $('select[name="item_status"]');
        item.find('option').each(function () {
            if ($(this).val() === item_status) {
                $(this).prop('selected', true);
            } else {
                $(this).prop('selected', false);
            }
        });
        item.selectpicker('refresh');
    }

    $("#item-code").autocomplete({
        source: site_url + 'eraxon_assets/Eraxon_assets_allocation/get_item_master',
        autoFocus: true,
        select: function (event, ui) {

            console.log("Available Item", ui.item)

            $("#item").html(`<br> <br><h5> Selected Item : ${ui.item.item_name + " " + ui.item.serial_number}</h5>
            <input type="hidden" name="item_id" value="${ui.item.item_id}">
            <input type="hidden" name="serial_number" value="${ui.item.serial_number}">
            <input type="hidden" name="qty" value="1">

            
            
            `)
            event.preventDefault();
            ui.term = "";
            $(this).val("");
        },
    });



</script>