<script>
    stock_in = [];


    function ButtonOptions(text, className) {
        this.text = text;
        this.className = className;
    }

    const new_btn = new ButtonOptions(` <span class="addBtn">
            <i class="fa fa-plus"></i>
            </span>`, "Add btn");

    const existing_btn = new ButtonOptions(` <span class="addBtn">
            <i class="fa fa-minus"></i>
            </span>`, "remove btn");
    const empty_btn = new ButtonOptions("", "");
    count_id = 0;

    $("#item-code").autocomplete({
        source: site_url + 'eraxon_assets/Eraxon_assets_stock_in/get_item_master',
        autoFocus: true,

        select: function (event, ui) {

            count_id = count_id + 1;
            ui.item.item_sr_no == 1 ? ui.item.button = new_btn : ui.item.button = empty_btn;
            ui.item.sr_id = count_id;
            ui.item.serial_no = [];
            ui.item.quantity = 1;
            ui.item.rate = "";

            stock_in.forEach(element => {
                if (ui.item.item_sr_no == 1) {
                    if (ui.item.id == element.id) {
                        element.button = existing_btn;
                    }
                }
            });

            if (stock_in.find(item => item.id == ui.item.id && ui.item.item_sr_no != 1)) {

                stock_in.find(item => item.id == ui.item.id).quantity=parseInt(stock_in.find(item => item.id == ui.item.id).quantity)+1 ;
            } else {
                stock_in.push(ui.item);
            }

            console.log("Stock In ", stock_in);

            add_data_to_table(stock_in);

            event.preventDefault();
            ui.term = "";
            $(this).val("");


        },

    });







    function add_data_to_table(stock_in) {

        document.getElementById("table-body").innerHTML = "";
        product_data_table = $("#table-body");
        var table_data = "";
        count = 0;
        subTotal = 0;

        stock_in.forEach((item) => {
            count = count + 1;

            const quantityInputDisabled = shouldDisableQuantity(item); // Define your condition here
            const serialNumberDisabled = !quantityInputDisabled; // Disable serial number if quantity is enabled
            const showButton = quantityInputDisabled; // Show button when quantity is disabled

            const table_data = `
            <tr data-id="${item.sr_id}"  >
            <input type="hidden" name="item_id[]" value="${item.id}"> 
                <td> ${count} </td>
                <td> ${item.item_name} </td>
                <td>
                    <input
                        type="number"
                        class="quantity"
                        name="quantity[]"
                        value="${item.quantity}"
                        style="padding:0.4rem;"
                        min="1"
                        max="100"
                        ${quantityInputDisabled ? 'readonly' : ''}
                    >
                </td>
                <td >
                    <input
                        type="text"
                        class="serial_no"
                        name="serial-number[]"
                        data-flag="${item.item_sr_no}"
                        value="${item.serial_no}"
                        style="padding:0.4rem;"
                        ${serialNumberDisabled ? 'readonly' : ''}
                        ${serialNumberDisabled ? '' : ''}
                    >
                    ${showButton ? `<button type="button"  class="${item.button.className}">${item.button.text} </button>` : ''}
                </td>
                <td><input
                        
                        type="number"
                        class="rate"
                        name="rate[]"
                        value="${item.rate}"
                        style="padding:0.4rem;width:40%;"
                    ></td>
                <input type="hidden" name="purchase_price[]"value="${item.quantity * item.rate}">
                <td align="right">${item.quantity * item.rate} </td>
                <td align="right">
                    <button type="button" id="delete_button" style="padding:0.4rem" class="btn btn-danger btn-xs">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            </tr>`;
            document.getElementById("table-body").innerHTML += table_data;
            subTotal = subTotal + parseFloat(item.rate * item.quantity);
            document.getElementById("table-body").innerHTML +=
                `<input type="hidden" name="subtotal" value="${subTotal}">`;
            document.getElementById("table-body").innerHTML +=
                `<input type="hidden" name="has_serial[]" value="${item.item_sr_no}">`;


        });

        document.getElementById('items-subtotal').innerHTML = `<?= $base_currency->name ?> ${subTotal}`;
        document.getElementById('payable-amount').innerHTML = `<?= $base_currency->name ?> ${subTotal}`;

    }




    $(document).on('click', '.Add', function (e) {
        e.preventDefault();

        

        var id = $(this).parents("tr").attr("data-id");
        query = stock_in.find(item => item.sr_id == id);
        count_id = count_id + 1;

        const copy = {
            ...query
        };
        copy.sr_id = count_id;
        copy.serial_no = "";

        query.button = existing_btn;
        stock_in.push(copy);
        console.log(stock_in)
        add_data_to_table(stock_in);

    });

    $(document).on('click', '.remove', function (e) {
        e.preventDefault();
        var ele = $(this);
        var id = ele.parents("tr").attr("data-id");
        if (confirm("Are you sure want to remove?")) {
            count_id = count_id + 1;
            console.log(count_id)
            stock_in = stock_in.filter(item => item.sr_id != id);
            console.log(stock_in)
            add_data_to_table(stock_in);
        }

    });

    $(document).on('input', '.serial_no', function (e) {

        $(this).css("border", "1px solid grey");
        $(this).parent().find("p").remove();
        rowid = $(this).closest("tr").attr("data-id")
        console.log("Id is", rowid)
        query = stock_in.find(item => item.sr_id == rowid);
        query.serial_no = $(this).val();
    });

    $(document).on('input', '.quantity', function (e) {

        qty_row_id = $(this).parents("tr").attr("data-id");
        query = stock_in.find(item => item.sr_id == qty_row_id);
        qty = $(this).val();
        query.quantity = qty;
        console.log(stock_in)
        add_data_to_table(stock_in);
    });

    $(document).on('change', '.rate', function (e) {
        e.preventDefault();
        qty_row_id = $(this).parents("tr").attr("data-id");
        query = stock_in.find(item => item.sr_id == qty_row_id);
        rate = $(this).val();
        query.rate = rate;
        console.log(stock_in)
        add_data_to_table(stock_in);
    });

    $(document).on('click', '#delete_button', function (e) {
        e.preventDefault();
        var ele = $(this);
        var id = ele.parents("tr").attr("data-id");
        if (confirm("Are you sure want to remove?")) {
            count_id = count_id + 1;
            console.log(count_id)

            console.log("Row ID", id)
            temp_sr_id = [];

            var item_id = stock_in.find(item => item.sr_id == id && item.button.className == "Add btn");
            console.log("Item ID ", item_id)
            if (item_id) {
                item_id = item_id.id;
                stock_in.forEach(element => {
                    if (element.id == item_id) {
                        console.log("Sghoaib")
                        temp_sr_id.push(element.sr_id);
                    }
                });
            }


            stock_in = stock_in.filter(item => item.sr_id != id);
            if (temp_sr_id.length > 1) {
                stock_in.find(item => item.sr_id == temp_sr_id[temp_sr_id.length - 2]).button = new_btn;
            }

            add_data_to_table(stock_in);
        }
    });



    $(document).ready(function () {
        $("#stockin_form").submit(function (e) {
            e.preventDefault();

            var inputArray = $("input[name='serial-number[]']");
            console.log(inputArray)
            var isValid = true;
            inputArray.each(function () {
                if ($(this).attr('data-flag') == 1) {
                    if ($(this).val().trim() === "") {
                        isValid = false;
                        $(this).css("border", "2px solid red");
                        $(this).parent().append(`<p id="serial-number[]-error" class="text-danger">This field is required.</p>`)
                    }
                }
            });

            if (isValid) {
                submit_form();
            }
        });
    });


    // $(document).on('click', '#purchase-button', function (e) {
    //     e.preventDefault();
    //     submit_form();
    // });

    function submit_form() {

        form = $('#stockin_form');
        var data = $(form).serialize();
        var url = form.action;
        $.post(url, data).done(function (response) {
            response = JSON.parse(response);
            console.log("Response", response)
            if (response.status == "success") {
                alert_float('success', response.message);
                window.location.href = response.url;
            }
            else {
                alert_float("danger", response.message)
            }
        });
        return false;
    }

    function shouldDisableQuantity(item) {
        return item.item_sr_no == 1;
    }




    $(function () {
        data = <?php echo json_encode($item); ?>;
        if (data) {
            console.log("")

            console.log("Data", data.item)
            stock_in.push(...data.item);
            stock_in.forEach((item)=>{
               item.sr_id=count_id;
                item.button=existing_btn;
               count_id+=1;
            })
            console.log('Stock In', stock_in);
            add_data_to_table(stock_in);
        }
       
       
        $("#payment_date").val("<?php echo date('Y-m-d'); ?>");
    });
</script>