@extends("layouts.master")
@section('content-title')

Point of Sales
@endsection



@section("content")

<div class="col-sm-5">
    <div class="card">
            <div class="card-header">
                <h3 class="card-title">Items &amp; Services</h3>
            </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-12">
                    <input type="text" class="form-control" id="search_products" placeholder="Search item or service">
                </div>
                
            </div>
            <div id="product-table" class="table-responsive">
                <table id="products" class="display table nowrap table-striped table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>Item / Service</th>
                            <!-- <th>Stock</th>
                            <th>Price</th> -->
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($current_stock as $stock)
                        @php($isService = $stock->type === 'Service')
                        <tr>
                            <td>{{ $stock->name }}</td>
                            <!-- <td>{!! $isService ? '<span class="badge text-bg-info">Service</span>' : number_format($stock->quantity) !!}</td>
                            <td>{{ $isService ? '—' : ($stock->sale_price_1 ? number_format($stock->sale_price_1, 2) : 'Not set') }}</td> -->
                            <td>
                                <button type="button"
                                    class="btn btn-sm {{ $isService ? 'btn-info' : 'btn-success' }}"
                                    data-id="{{ $stock->id }}"
                                    data-name="{{ $stock->name }}"
                                    data-type="{{ $isService ? 'Service' : 'Item' }}"
                                    data-price="{{ $isService ? 0 : ($stock->sale_price_1 ?? 0) }}"
                                    data-qoh="{{ $isService ? 0 : $stock->quantity }}"
                                    data-unit_cost="{{ $stock->unit_cost ?? 0 }}"
                                    data-bs-toggle="modal" data-bs-target="#addItemsModal" title="Add">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
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
            <div class="card-header">
                <div class="row g-2 w-100">
                    @if (getSettings('enable_sale_date_select')=='YES')
                        
                        <div class="col-md-3">
                            <div id="date" style="border-radius: 6px;">
                                <input type="text" name="sale_date" class="form-control" id="sale_date" value="{{ old('sale_date') }}" required >
                            </div>
                        </div>
                    @else
                        <input type="hidden" name="sale_date" value="NA">
                    @endif
                    {{-- <div class="col-md-3">
                        <select class="form-select select2" name="created_by" required data-width="100%">
                            <option value="">Select User</option>

                            @foreach($users as $u)
                                <option value="{{$u->id}}" {{ (old('created_by')==$u->id ? "selected":"") }}>{{$u->name}}</option>
                            @endforeach
                        </select>

                    </div> --}}
                    <div class="col-md-4">
                        <select class="form-select select2" name="customer_id"  data-width="100%">
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
        order: [], // keep server order: services first, then items

    });

    $('#search_products').keyup(function(){
        prod_table
            .columns(0)
            .search(this.value)
            .draw();
    });

    // Order line: [id, qoh, name, qty, price, subtotal, unit_cost, type, total]
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
            { title: "Item / Service" },
            { title: "Qty" },
            { title: "Unit Price" },
            { title: "Sub Total" },
            { title: "unit_cost" },
            { title: "type" },
            { title: "total" },
            { title: "Action", defaultContent: '<button type="button" class="btn btn-sm btn-danger delete_btn" title="Remove"><i class="bi bi-trash"></i></button>' },
        ]
    });

    order_table.columns([0, 1, 6, 7, 8]).visible(false);

    var order_list = [];

    function refreshOrder() {
        order_table.clear();
        order_table.rows.add(order_list).draw();
        $('#total').text(formatMoney(totalOrder(order_list)));
        $('#sale_order').val(order_list.length ? JSON.stringify(order_list) : '');
    }

    function showAddError(msg) {
        $('#add_error').text(msg).toggleClass('d-none', !msg);
    }

    function recalcAdd() {
        var qty = parseInt($('#add_qty').val()) || 0;
        if ($('#add_type').val() === 'Service') {
            var total = parseFloat($('#add_total').val()) || 0;
            $('#add_calc_label').text('Unit Price');
            $('#add_calc').val(qty > 0 && total > 0 ? formatMoney(total / qty) : '');
        } else {
            var price = parseFloat($('#add_price_text').data('price')) || 0;
            $('#add_calc_label').text('Total');
            $('#add_calc').val(qty > 0 ? formatMoney(price * qty) : '');
        }
    }

    // Open the add modal for an item or a service
    $('#addItemsModal').on('show.bs.modal', function (event) {
        var b = $(event.relatedTarget);
        var type = b.data('type');
        var price = parseFloat(b.data('price')) || 0;

        $('#add_id').val(b.data('id'));
        $('#add_type').val(type);
        $('#add_qoh').val(b.data('qoh'));
        $('#add_unit_cost').val(b.data('unit_cost'));
        $('#add_name').text(b.data('name'));
        $('#add_badge').text(type).attr('class', 'badge ms-1 ' + (type === 'Service' ? 'badge-service' : 'badge-item'));
        $('#add_qty').val(1).attr('max', type === 'Item' ? b.data('qoh') : null);
        $('#add_total').val('');
        $('#add_qoh_text').val(b.data('qoh'));
        $('#add_price_text').val(formatMoney(price)).data('price', price);

        $('.item-only').toggle(type === 'Item');
        $('.service-only').toggle(type === 'Service');
        $('#add_title').text(type === 'Service' ? 'Add Service' : 'Add Item');

        showAddError('');
        $('#add_submit').prop('disabled', false);
        if (type === 'Item' && price <= 0) {
            showAddError('This item has no selling price. Set it in Masters > Products first.');
            $('#add_submit').prop('disabled', true);
        }
        recalcAdd();
    });

    $('#addItemsModal').on('shown.bs.modal', function () {
        $('#add_qty').trigger('focus').select();
    });

    $('#add_qty, #add_total').on('input', recalcAdd);

    // Add the line to the order
    $('#addItemForm').on('submit', function (event) {
        event.preventDefault();

        var id = parseInt($('#add_id').val());
        var type = $('#add_type').val();
        var name = $('#add_name').text();
        var qty = parseInt($('#add_qty').val()) || 0;
        var unitCost = parseFloat($('#add_unit_cost').val()) || 0;

        if (qty < 1) { showAddError('Enter a quantity of at least 1.'); return; }

        if (type === 'Service') {
            var total = parseFloat($('#add_total').val()) || 0;
            if (total <= 0) { showAddError('Enter the total cost for this service.'); return; }

            // each service job is its own line
            order_list.push([id, 0, name, qty, formatMoney(total / qty), formatMoney(total), 0, 'Service', total]);
        } else {
            var qoh = parseInt($('#add_qoh').val()) || 0;
            var price = parseFloat($('#add_price_text').data('price')) || 0;

            // same item already in the order: add to its quantity
            var existing = order_list.findIndex(function (r) { return r[0] === id && r[7] === 'Item'; });
            var newQty = qty + (existing >= 0 ? parseInt(order_list[existing][3]) : 0);
            if (newQty > qoh) { showAddError('Only ' + qoh + ' in stock.'); return; }

            var line = [id, qoh, name, newQty, formatMoney(price), formatMoney(price * newQty), unitCost, 'Item', price * newQty];
            if (existing >= 0) { order_list[existing] = line; } else { order_list.push(line); }
        }

        refreshOrder();
        bootstrap.Modal.getOrCreateInstance(document.getElementById('addItemsModal')).hide();
    });

    // Remove a line
    $('#order tbody').on('click', '.delete_btn', function () {
        var index = order_table.row($(this).parents('tr')).index();
        order_list.splice(index, 1);
        refreshOrder();
    });

    // Do not submit an empty order
    $('form[action="{{ route('sales.store') }}"]').on('submit', function (e) {
        if (order_list.length === 0) {
            e.preventDefault();
            alert('Add at least one item or service to the order.');
        }
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
                    sum += parseFloat(array2d[i][8]) || 0;
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