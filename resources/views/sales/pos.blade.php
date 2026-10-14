@extends("layouts.master")
@section('content-title')

Point of Sales
@endsection



@section("content")

<div class="col-sm-5">
    <div class="card">
        <div class="card-body">
            <div class="form-group row">
                <div class="col-md-6">
                    <select class="form-control select2" id="product_category" data-width="100%">
                            <option value="">Select Category</option>

                            @foreach($categories as $c)
                                <option value="{{ $c->name }}" }}>{{ $c->name }}</option>
                            @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <input type="text" class="form-control " id="search_products" placeholder="Search Item">
                </div>
                
            </div>
            <div id="product-table" class="table-responsive">
                <table id="products" class="display table nowrap table-striped table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Item</th>
                            <th>QOH</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($current_stock as $stock)
                        <tr>
                            <td>{{ $stock->category->name }}</td>
                            <td>{{ $stock->name }}</td>
                            <td>{{$stock->quantity}}</td>
                            <td>
                                <a href="#">
                                    <button type="button" 
                                        class="btn btn-sm btn-rounded btn-icon btn-success selectproduct"
                                        data-id="{{$stock->id}}" data-name="{{$stock->name}}"
                                        data-sale_price_1="{{$stock->sale_price_1}}"
                                        data-unit_cost="{{$stock->unit_cost}}" data-quantity="{{$stock->quantity}}"
                                        data-toggle="button"><i class="feather icon-chevron-right"></i>
                                    </button>
                                    <button type="button"
                                        class="btn btn-sm btn-rounded btn-icon btn-secondary selectProducts"
                                        data-id="{{$stock->id}}" data-name="{{$stock->name}}"
                                        data-sale_price_1="{{$stock->sale_price_1}}"
                                        data-unit_cost="{{$stock->unit_cost}}" data-quantity="{{$stock->quantity}}"
                                        data-toggle="modal" data-target="#addItemsModal"><i class="feather icon-chevrons-right"></i>
                                    </button>
                            
                                
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<div class="col-sm-7">
    
    <form method="POST" action="{{ route('sales.store') }}">
        @csrf
        <input type="hidden" name="sale_order" id="sale_order" required>

        <div class="card">
            <div class="alert alert-success" role="alert">
                <div class="form-group row">
                    @if (getSettings('enable_sale_date_select')=='YES')
                        
                        <div class="col-md-3">
                            <div id="date" style="border-radius: 6px;">
                                <input type="text" name="sale_date" class="form-control " id="sale_date" value="{{ old('sale_date') }}" required >
                            </div>
                        </div>
                    @else
                        <input type="hidden" name="sale_date" value="NA">
                    @endif
                    {{-- <div class="col-md-3">
                        <select class="form-control  select2" name="created_by" required data-width="100%">
                            <option value="">Select User</option>

                            @foreach($users as $u)
                                <option value="{{$u->id}}" {{ (old('created_by')==$u->id ? "selected":"") }}>{{$u->name}}</option>
                            @endforeach
                        </select>

                    </div> --}}
                    <div class="col-md-2">
                        <input type="text" class="form-control " name="table_number" placeholder="Table No.">
                    </div>
                    <div class="col-md-4">
                        <select class="form-control  select2" name="customer_id"  data-width="100%">
                            <option value="">Select Customer</option>

                            @foreach($customers as $c)
                                <option value="{{$c->id}}" }}>{{$c->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body">

                <div id="order-table" class="table-responsive">
                    <table id="order" class="display table nowrap table-striped table-hover" style="width:100%">

                    </table>

                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-6">
                        <h6> Total Order Amount </h6>
                    </div>
                    <div class="col-sm-4">
                        <h6 id="total"> </h6>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>

            </div>
        </div>
    </form>
</div>


@endsection

@push("page_scripts")
@include('partials.notification')
@include('sales.pos_add_item')

<script src="{{asset("assets/plugins/bootstrap-datetimepicker/js/bootstrap-datepicker.min.js")}}"></script>
<script src="{{asset("assets/js/pages/ac-datepicker.js")}}"></script>
<script>
    var title = document.title;
    document.title = title.concat(" | Sales | POS");
</script>

<script>

    $(function () {
        var start = moment();
        var end = moment();

        $('#sale_date').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            maxDate: end,
            autoUpdateInput: true,
            locale: {
                format: 'DD-M-YYYY'
            }
        });
    });


    var prod_table = $('#products').DataTable({
        bAutoWidth: true,
        lengthChange: false,
        scrollY:  "500px",
        scrollCollapse: true,
        paging: false,
        bInfo: false,
        dom: 'lrtip',
        order: [[ 1, "asc" ]],
        columnDefs: [{
            targets: 0,
            searchable: true,
            visible: false
        }]
        
    });

    //on dropdown change, filter items
    $('#product_category').change(function(){
        prod_table
            .columns(0)
            .search( this.value )
            .draw();
    }); 


    $('#search_products').keyup(function(){
        prod_table
            .columns(1)
            .search(this.value)
            .draw();
    });

    var order_table = $('#order').DataTable({
        searching: false,
        bPaginate: false,
        ordering: false,
        bInfo: false,
        scrollY:  "300px",
        scrollCollapse: true,
        columns: [
            { title: "Id" },
            { title: "QOH" },
            { title: "Item" },
            { title: "Quantity" },
            { title: "Price" },
            { title: "Sub Total" },
            { title: "unit_cost"},
            { title: "Action", defaultContent:  '<button type="button" id="edit_btn" class="btn btn-icon btn-rounded btn-sm btn-primary"><i class="feather icon-edit"></i></button><button type="button" id="delete_btn" class="btn btn-icon btn-rounded btn-sm btn-danger"><i class="feather icon-trash-2"></i></button>'},
            
        ]
    });

    order_table.columns([0,1,6]).visible(false); //hide id,qoh,buying price

    var order_list = []; //hold data displayed in datatable


        $('.selectproduct').click(function() {
            var data = [];

            //if row exist increment quantity of existing record
            if( rowExist($(this).data('id'),order_list)){
                for (var i = 0; i < order_list.length; i++) {
                    if(order_list[i][0] == $(this).data('id')){
                        order_list[i][3] = parseInt(order_list[i][3]) + 1;
                        //check if quantity is > QOH
                        if (parseInt(order_list[i][3]) > parseInt(order_list[i][1])) { //OrderQty > QOH
                            order_list[i][3] = order_list[i][1]
                        }
                        order_list[i][5] = formatMoney(parseFloat(order_list[i][4].replace(/\,/g, '')) * parseInt(order_list[i][3]));

                        order_table.clear();
                        order_table.rows.add(order_list).draw();
                        $('#total').text(formatMoney(totalOrder(order_list)));
                        $('#sale_order').val(JSON.stringify(order_list));
                        break;
                    }
                }
                 
            } else{ //add item
                data.push($(this).data('id'));
                data.push($(this).data('quantity'));
                data.push($(this).data('name'));
                data.push(1);
                data.push(formatMoney($(this).data('sale_price_1')));
                data.push(formatMoney($(this).data('sale_price_1')));
                data.push($(this).data('unit_cost'));

                order_list.push(data);
                
                order_table.clear();
                order_table.rows.add(order_list).draw();
                $('#total').text(formatMoney(totalOrder(order_list)));
                $('#sale_order').val(JSON.stringify(order_list));

            }
          
            
        });
     
        $('#order tbody').on('click', '#edit_btn', function () {
            var row_data = order_table.row($(this).parents('tr')).data();
            var index = order_table.row($(this).parents('tr')).index();
            quantity = row_data[3];
            row_data[3] = "<input type='number' min='1' class='form-control' id='edit_quantity' required  onkeypress='return (event.charCode == 8 || event.charCode == 0) ? null : event.charCode >= 48 && event.charCode <= 57'>";
            order_list[index] = row_data;
            order_table.clear();
            order_table.rows.add(order_list);
            order_table.draw();
            document.getElementById("edit_quantity").value = quantity;
        });

        $('#order tbody').on('change', '#edit_quantity', function () {
            var row_data = order_table.row($(this).parents('tr')).data();
            var index = order_table.row($(this).parents('tr')).index();
            row_data[3] = Number((document.getElementById("edit_quantity").value));

            if (row_data[3] < 1) { row_data[3] = 1 }
    
            if (row_data[3] > row_data[1]) { //OrderQty > QOH
                row_data[3] = row_data[1];
                row_data[5] = formatMoney(row_data[3] * parseFloat(row_data[4].replace(/\,/g, '')));
            }
            else {
                row_data[5] = formatMoney(row_data[3] * parseFloat(row_data[4].replace(/\,/g, '')));
            }

            order_list[index] = row_data;
            order_table.clear();
            order_table.rows.add(order_list);
            order_table.draw();
            $('#total').text(formatMoney(totalOrder(order_list)));
            $('#sale_order').val(JSON.stringify(order_list));
            
        });
        
        $('#order tbody').on('click', '#delete_btn', function () {
            var index = order_table.row($(this).parents('tr')).index();
            order_list.splice(index, 1);
            order_table.clear();
            order_table.rows.add(order_list);
            order_table.draw();
            $('#total').text(formatMoney(totalOrder(order_list)));
            $('#sale_order').val(JSON.stringify(order_list));

        });

        //add items to order
        $('#addItemsModal').on('show.bs.modal', function (event) {
            setTimeout(function (){
                $('#sold_quantity').focus();
            }, 500);

            var button = $(event.relatedTarget);
            var modal = $(this);

            modal.find('.modal-body #id').val(button.data('id'));
            modal.find('.modal-body #name').val(button.data('name'));
            modal.find('.modal-body #quantity').val(button.data('quantity'));
            modal.find('.modal-body #sale_price_1').val(button.data('sale_price_1'));
            modal.find('.modal-body #sale_price').val(button.data('sale_price_1'));
            modal.find('.modal-body #unit_cost').val(button.data('unit_cost'));
            modal.find('.modal-body #sold_quantity').val("");

        });

        //on click of modal form add data to data table order list
        $( '#addItemForm' ).submit(function( event ) {
            event.preventDefault();

            var values = {};
            $.each($(this).serializeArray(), function(i, field) {
                values[field.name] = field.value;
            });

            //add item to order table
            var data = [];
            if(! rowExist(values.id,order_list)){
                var qty;
                if (parseInt(values.sold_quantity) > parseInt(values.quantity)) { //OrderQty > QOH
                    qty = values.quantity;
                }else{
                    qty = values.sold_quantity;
                }
                data.push(parseInt(values.id));
                data.push(parseInt(values.quantity));
                data.push(values.name);
                data.push(parseInt(qty));
                data.push(formatMoney(values.sale_price));
                data.push(formatMoney(values.sale_price * qty));
                data.push(parseFloat(values.unit_cost));

                order_list.push(data);
                
                order_table.clear();
                order_table.rows.add(order_list).draw();
                $('#total').text(formatMoney(totalOrder(order_list)));
                $('#sale_order').val(JSON.stringify(order_list));
            }

            $('#addItemsModal').modal('hide');
                        
        });
  

            //check if items is already added on the table
            function rowExist(value2Check,Array2D) {
                var count = 0;
                for (var i = 0; i <Array2D.length; i++) {
                    if(Array2D[i][0] == value2Check){
                        count++;
                    }
                }
                if (count > 0 ) {
                    return true;
                }else{
                    return false;
                }
            };
            //sum sub total to get total amount of the order
            function totalOrder(array2d){
                var sum = 0;
                for(var i=0;i<array2d.length;i++){
                    sum +=  parseFloat(array2d[i][5].replace(/\,/g, ''));
                }
                return sum;
            }

            function formatMoney(amount, decimalCount = 2, decimal = ".", thousands = ",") {
                try {
                    decimalCount = Math.abs(decimalCount);
                    decimalCount = isNaN(decimalCount) ? 2 : decimalCount;
                    const negativeSign = amount < 0 ? "-" : "";
                    let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
                    let j = (i.length > 3) ? i.length % 3 : 0;
                    return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
                } catch (e) {
                }
            }

         
    
</script>
@endpush