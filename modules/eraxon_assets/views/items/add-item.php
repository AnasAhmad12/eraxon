<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <h4 class="no-margin">
              <?php echo htmlspecialchars($title); ?>
            </h4>
            <hr class="hr-panel-heading" />
            <?php echo form_open_multipart($this->uri->uri_string()); ?>
            <div class="row">
              <div class="col-md-5">

                <?php echo render_select('assets_category_id', $item_categories, ['assets_category_id', 'assets_category_name'], 'Item Category', !empty(set_value('assets_category_id')) ? set_value('assets_category_id') : $item->assets_category_id ?? ''); ?>
              </div>
              <div class="col-md-7">
                <?php echo render_input('item_name', 'Item Name', $item->item_name ?? ''); ?>
              </div>

            </div>

            <div class="row" id="custom-fields">

            </div>

            <div class="row">
              <div class="col-md-12">
                <?php echo render_textarea('item_description', 'Item Description', $item->item_description ?? ''); ?>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label for="is_unique"> Is unique serial No?</label>
                <div class="checkbox checkbox-danger">
                  <input type="checkbox" name="is_unique" id="is_unique"
                    value="<?php echo isset($item) ? $item->item_sr_no : "" ?>" <?php echo isset($item) ? ($item->item_sr_no == '1') ? "checked" : "" : "" ?>>
                  <label></label>
                </div>
              </div>
            </div>


            <?php
            $existing_image_class = 'col-md-4';
            $input_file_class = 'col-md-8';
            if (empty($item->item_image)) {
              $existing_image_class = 'col-md-12';
              $input_file_class = 'col-md-12';
            }
            ?>

            <div class="row">
              <?php if (!empty($item->item_image)) { ?>
                <div class="<?php echo htmlspecialchars($existing_image_class); ?>">
                  <div class="existing_image">
                    <label class="control-label">Existing Image</label>
                    <img src="<?php echo base_url('modules/' . Eraxon_assets . '/uploads/' . $item->item_image); ?>"
                      class="img img-responsive img-thumbnail zoom" />
                  </div>
                </div>
              <?php } ?>
              <div class="<?php echo htmlspecialchars($input_file_class); ?>">
                <div class="attachment">
                  <div class="form-group">
                    <label for="attachment" class="control-label"><small class="req text-danger">* </small>Item
                      Image</label>
                    <input type="file" extension="png,jpg,jpeg,gif" filesize="<?php echo file_upload_max_size(); ?>"
                      class="form-control" name="item" id="item" required>
                  </div>
                </div>
              </div>
            </div>

            <button type="submit" class="btn btn-info pull-right">
              <?php echo _l('submit'); ?>
            </button>
            <?php echo form_close(); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
<script type="text/javascript">
  var mode = '<?php echo $this->uri->segment(3, 0); ?>';
  (mode == 'add_product') ? $('input[type="file"]').prop('required', true) : $('input[type="file"]').prop('required', false);
  $(function () {

    appValidateForm($('form'), {
      assets_category_id: "required",
      item_name: "required",
      item_description: "required",
      item:"required"
    });

    var selectedOption = $("#assets_category_id").val();


    if(selectedOption){
      $("#assets_category_id").prop("disabled",true)
      $('.dropdown-toggle').removeAttr('data-toggle');
      $.ajax({
      url: site_url+'eraxon_assets/eraxon_assets_items/get_custom_fields',
      type: 'POST',
      dataType: 'json',
      data : {'category_id': $("#assets_category_id").val()},
      success : function (data) {
        console.log("data",data);

    document.getElementById("custom-fields").innerHTML=` <div class="col-md-12"> <h4> ${data.name}  </h2> </div>`;
    data.values.forEach(item => {
    const field_data=`       
    <div class="col-md-6">
     <div class="form-group">
      <label for="${item.value}" class="control-label"> ${item.value}</label> 
      <input type="text" id="${item.value}" name="cf_value[]" class="form-control" value="">
      <input type="hidden" id="${item.value}" name="cf_id[]" class="form-control" value="${item.id}">
      <input type="hidden" id="${item.value}" name="cf_field_id" class="form-control" value="${item.assets_custom_field_id}">
      <input type="hidden" id="${item.value}" name="cf_field_name[]" class="form-control" value="${item.value}">

      </div> 
    `;
    document.getElementById("custom-fields").innerHTML +=field_data;


      custom_fields.forEach(function(value, index) {
        $(`input[name="cf_value[]"][id="${value.custom_field_name}"]`).val(value.value);

    });Ï

        });

      }
    });
    }

    $('#assets_category_id').change(function () {

      $.ajax({
        url: site_url + 'eraxon_assets/eraxon_assets_items/get_custom_fields',
        type: 'POST',
        dataType: 'json',
        data: { 'category_id': $(this).val() },
        success: function (data) {
          console.log("DAta",data);
          if(data.length!=0){
            document.getElementById("custom-fields").innerHTML = ` <div class="col-md-12"> <h4> ${data.name}  </h2> </div>`;
          data.values.forEach(item => {
            const field_data = `       
            <div class="col-md-6">
            <div class="form-group">
              <label for="${item.value}" class="control-label"> ${item.value}</label> 
              <input type="text" id="${item.value}" name="cf_value[]" class="form-control" value="">
              <input type="hidden" id="${item.value}" name="cf_id[]" class="form-control" value="${item.id}">
              <input type="hidden" id="${item.value}" name="cf_field_id" class="form-control" value="${item.assets_custom_field_id}">
              <input type="hidden" id="${item.value}" name="cf_field_name[]" class="form-control" value="${item.value}">
              
              </div> 
            `;
            document.getElementById("custom-fields").innerHTML += field_data;

          });
          }else{
            document.getElementById("custom-fields").innerHTML = ``;
          }
         


        }
      });


    });




    $('#is_unique').click(function (event) {
      if ($('#is_unique').is(':checked')) {
        $(this).attr({ value: 1 });
      } else {
        $(this).attr({ value: 0 });
      }
    });




    change_variation_values();
    // change_variation_quantity_event();
  });
  function get_variation_value_preview_values() {
    var response = {};
    response.variation_id = parseInt($('.selectpicker.variation').val());
    response.variation_name = '';
    for (var variation_index = 0; variation_index < $('.selectpicker.variation option').length; variation_index++) {
      var variation_item = $($('.selectpicker.variation option')[variation_index]);
      if (variation_item.val() == response.variation_id) {
        response.variation_name = variation_item.text();
      }
    }
    response.variation_value_id = parseInt($('.selectpicker.variation_value').val());
    response.variation_value_value = '';
    response.variation_value_values = [];
    for (var variation_index = 0; variation_index < $('select.variation_value option').length; variation_index++) {
      var variation_value_item = $($('.selectpicker.variation_value option')[variation_index]);
      if (variation_value_item.val() == response.variation_value_id) {
        response.variation_value_value = variation_value_item.text();
      }
      response.variation_value_values.push({ id: parseInt(variation_value_item.val()), value: variation_value_item.text() });
    }
    return response;
  }
  $("body").on(
    "change",
    '[name="recurring"]',
    function () {
      var val = $(this).val();
      val == "custom" ? $(".recurring_custom").removeClass("hide") : $(".recurring_custom").addClass("hide");
    }
  );
  $("body").on(
    "change",
    '[name="is_variation"]',
    function () {
      var val = $(this).val();
      if (val !== "" && val != 0) {
        $("body").find("#variations_wrapper").removeClass("hide");
      } else {
        $("body").find("#variations_wrapper").addClass("hide");
      }
    }
  );
  function change_variation_values() {
    $.ajax({
      url: site_url + 'products/variations/values',
      type: 'POST',
      dataType: 'json',
      data: { 'variation_id': $('.selectpicker.variation').val() },
      success: function (data) {
        var variation_values_html = '<option value="">' + $('.selectpicker.variation_value').data('none-selected-text') + '</option>';
        for (var variation_index = 0; variation_index < data.length; variation_index++) {
          variation_values_html += '<option value="' + data[variation_index]['id'] + '">' + data[variation_index]['value'] + '</option>';
        }
        $('.selectpicker.variation_value').html(variation_values_html);
        $('.selectpicker.variation_value').selectpicker("refresh");
      }
    });
  }
  function change_variation_quantity_event() {
    change_variation_quantity();
    $("body").on(
      "change",
      'input.quantity_number',
      function () {
        change_variation_quantity();
      }
    );
  }
  function change_variation_quantity() {
    var total_quantities = 0;
    var quantity_numbers = $('input.quantity_number');
    for (var quantiry_index = 0; quantiry_index < quantity_numbers.length; quantiry_index++) {
      total_quantities += parseInt($(quantity_numbers[quantiry_index]).val());
    }
    $('#quantity_number').val(total_quantities);
  }
  function add_variation_value_to_table() {
    var data = get_variation_value_preview_values();

    if (data.variation_id === "") {
      return;
    }

    var variation_row = null;
    var row_variation_id = '';
    var row_variation_value_id = '';
    var rows = $(".table.product-variations-table tbody tr:not(.main)");
    for (var row_index = 0; row_index < rows.length; row_index++) {
      if ($(rows[row_index]).hasClass('variation')) {
        row_variation_id = $(rows[row_index]).find("input").data('id');
        if (row_variation_id == data.variation_id) {
          variation_row = $(rows[row_index]);
        }
      } else {
        row_variation_id = $(rows[row_index]).find("input.variation").data('id');
        row_variation_value_id = $(rows[row_index]).find("input.variation_value").data('id');
        if (row_variation_id == data.variation_id) {
          variation_row = $(rows[row_index]);
        }
        if (!data.variation_value_id) {
          if (row_variation_id == data.variation_id) {
            return;
          }
        } else {
          if (row_variation_value_id == data.variation_value_id) {
            return;
          }
        }
      }
    }

    var table_row = "";
    if (!data.variation_value_id) {
      table_row += '<tr class="variation">';
      table_row += '<td><input class="form-control" value="' + data.variation_name + '" data-id="' + data.variation_id + '" readonly /></td>';
      table_row += '<td></td>';
      table_row += '<td></td>';
      table_row += '<td></td>';
      table_row += '<td><a href="#" class="btn btn-danger pull-right" onclick="delete_variation(this); return false;"><i class="fa fa-times"></i></a></td>';
      table_row += '</tr>';
      for (var variation_value_index = 0; variation_value_index < data.variation_value_values.length; variation_value_index++) {
        if (data.variation_value_values[variation_value_index].id) {
          table_row += '<tr class="variation_value">';
          table_row += '<td><input name="variations[variation][]" class="form-control variation" value="' + data.variation_name + '" data-id="' + data.variation_id + '" readonly /></td>';
          table_row += '<td><input name="variations[variation_value][]" class="form-control variation_value" value="' + data.variation_value_values[variation_value_index].value + '" data-id="' + data.variation_value_values[variation_value_index].id + '" readonly /></td>';
          table_row += '<td><input name="variations[rate][]" class="form-control rate" value="' + $('input[name="rate"]').val() + '" /></td>';
          table_row += '<td><input name="variations[quantity_number][]" class="form-control quantity_number" value="1" /></td>';
          table_row += '<td><a href="#" class="btn btn-danger pull-right" onclick="delete_variation_value(this); return false;"><i class="fa fa-times"></i></a></td>';
          table_row += '</tr>';
        }
      }
      $("table.product-variations-table tbody").append(table_row);
    } else {
      if (!variation_row) {
        table_row += '<tr class="variation">';
        table_row += '<td><input class="form-control" value="' + data.variation_name + '" data-id="' + data.variation_id + '" readonly /></td>';
        table_row += '<td></td>';
        table_row += '<td></td>';
        table_row += '<td></td>';
        table_row += '<td><a href="#" class="btn btn-danger pull-right" onclick="delete_variation(this); return false;"><i class="fa fa-times"></i></a></td>';
        table_row += '</tr>';
      }
      table_row += '<tr class="variation_value">';
      table_row += '<td><input name="variations[variation][]" class="form-control variation" value="' + data.variation_name + '" data-id="' + data.variation_id + '" readonly /></td>';
      table_row += '<td><input name="variations[variation_value][]" class="form-control variation_value" value="' + data.variation_value_value + '" data-id="' + data.variation_value_id + '" readonly /></td>';
      table_row += '<td><input name="variations[product_code][]" class="form-control product_code" value="" /></td>';
      table_row += '<td><input name="variations[rate][]" class="form-control rate" value="' + $('input[name="rate"]').val() + '" /></td>';
      table_row += '<td><input name="variations[quantity_number][]" class="form-control quantity_number" value="1" /></td>';
      table_row += '<td><a href="#" class="btn btn-danger pull-right" onclick="delete_variation_value(this); return false;"><i class="fa fa-times"></i></a></td>';
      table_row += '</tr>';
      if (!variation_row) {
        $("table.product-variations-table tbody").append(table_row);
      } else {
        variation_row.after(table_row);
      }
    }

    change_variation_quantity_event();
  }
  $("body").on(
    "change",
    '.selectpicker.variation',
    function () {
      change_variation_values();
    }
  );
  function delete_variation_values(row) {
    if (row.hasClass('variation_value')) {
      delete_variation_values(row.next());
      row.remove();
    }
  }
  function delete_variation(row) {
    $(row)
      .parents("tr")
      .addClass("animated fadeOut", function () {
        setTimeout(function () {
          delete_variation_values($(row).parents("tr").next());
          $(row).parents("tr").remove();
        }, 50);
      });
  }
  function delete_variation_value(row) {
    $(row)
      .parents("tr")
      .addClass("animated fadeOut", function () {
        setTimeout(function () {
          $(row).parents("tr").remove();
        }, 50);
      });
  }
</script>