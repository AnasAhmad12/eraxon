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

    $("input").autocomplete({
        source: site_url + 'eraxon_assets/Eraxon_assets_allocation/get_item_master',
        autoFocus: true,

        select: function (event, ui) {
            console.log("Available Item",ui.item)
            count_id = count_id + 1;
            ui.item.sr_id = count_id;
            ui.item.serial_no = ui.item.serial_number;
            ui.item.quantity = 1;

    

            if (stock_in.find(item => item.id == ui.item.id && ui.item.item_sr_no != 1)) {
                query=stock_in.find(item => item.id == ui.item.id && ui.item.item_sr_no != 1);
                if(query.quantity<=query.qty){
                stock_in.find(item => item.id == ui.item.id).quantity=parseInt(stock_in.find(item => item.id == ui.item.id).quantity)+1 ;
                }
                else{
                    alert_float("danger","Quantity Exceded")
                }
            } else {
                stock_in.push(ui.item);
            }


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

            const table_data = `
            <tr data-id="${item.id+""+item.serial_no}"  >
            <input type="hidden" name="item_id[]" value="${item.id}"> 
                <td> ${count} </td>
                <td> ${Math.floor(item.serial_no)==0?item.item_name:item.item_name + " (" +Math.floor(item.serial_no)+ ") "} </td>
                <td>
                    <input
                        type="number"
                        class="quantity"
                        name="quantity[]"
                        value="${item.quantity}"
                        style="padding:0.4rem;"
                        min="1"
                        max="100"
                        ${Math.floor(item.serial_no)==0 ?"":"readonly"}
                    
                    >
                </td>
                <td >
                    <input
                        type="text"
                        class="serial_no"
                        name="serial-number[]"
                        value="${item.serial_no}"
                        style="padding:0.4rem;"
                        readonly
                    >
                </td>
                <td align="right">
                    <button type="button" id="delete_button" style="padding:0.4rem" class="btn btn-danger btn-xs">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            </tr>`;
            document.getElementById("table-body").innerHTML += table_data;
           


        });

    

    }




    

    $(document).on('click', '.remove', function (e) {
        e.preventDefault();
        var ele = $(this);
        var id = ele.parents("tr").attr("data-id");
        if (confirm("Are you sure want to remove?")) {
            count_id = count_id + 1;
            console.log(count_id)
            stock_in = stock_in.filter(item => item.id+""+item.serial_no != id);
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

    $(document).on('change', '.quantity', function (e) {

        qty_row_id = $(this).parents("tr").attr("data-id");
        query = stock_in.find(item => (item.id+""+item.serial_no) == qty_row_id);
        qty = $(this).val();
        console.log("Max quantity",query.qty);

        if(qty>query.qty){
            query.quantity=query.qty;
            alert_float("danger", "Qunatity Limit Exceded");


        }   
        else{
            query.quantity=$(this).val();
        }
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
        
        $("#allocation_form").submit(function (e) {
            e.preventDefault();

            var inputArray = $("input[name='serial-number[]']");
            console.log("staff",$("selet[name='staff_id']").val())
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

        form = $('#allocation_form');
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

        $("#payment_date").val("<?php echo date('Y-m-d'); ?>");
    });
</script>

