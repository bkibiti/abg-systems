@extends("layouts.master")

@section('page_css')
<style>

</style>

@endsection

@section('content-title')
Sales History
@endsection

@section('content-sub-title')
<li class="breadcrumb-item"><a href="{{route('home')}}"><i class="bi bi-house"></i></a></li>
<li class="breadcrumb-item"><a href="#">Sales / Sales History </a></li>
@endsection

@section("content")


<div class="col-sm-12">
    <div class="card">
            <div class="card-header">
                <h3 class="card-title">Sales History</h3>
            </div>
        <div class="card-body">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

                @can('Delete Sales')
                    <div class="mb-3">
                        <a href="{{ route('sales.deletions') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-clock-history" aria-hidden="true"></i> Deleted sales
                        </a>
                    </div>
                @endcan

                <form action="{{ route('sales.history-search') }}" method="GET">
                    <div class="row g-2 g-md-3 mb-3">
                        <div class="col-12 col-md">
                            <label for="filter_product" class="form-label history-filter-label">Product</label>
                            <select class="form-select select2" name="product" id="filter_product" data-width="100%">
                                <option value="">All Products</option>
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}" @selected(request('product') == $p->id)>{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        @include('partials.date_filter', ['resetRoute' => 'sales.history'])
                    </div>
                </form>

                <hr>
                <div id="product-table" class="table-responsive">
                    <table id="fixed-header1" class="display table nowrap table-striped table-hover" style="width:100%">
                        <thead>
                            <tr>
                       
                                <th>Date</th>
                                <th>Item</th>
                                <th>Qty</th>
                                <th>Unit Cost</th>
                                <th>Total Cost</th>
                                <th>Customer</th>
                                <th>Payment</th>
                                <th>Added By</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sales as $s)
                            <tr>
                          
                                <td data-order="{{ $s->created_at->format('Y-m-d H:i:s') }}">{{date_format($s->created_at,'d M Y')}}</td>
                                <td>{{ $s->product->name ?? '(deleted product #'.$s->stock_id.')' }}</td>
                                <td>{{number_format($s->quantity,0)}}</td>
                                <td>{{number_format($s->selling_price,2)}}</td>
                                <td>{{number_format(($s->selling_price * $s->quantity), 2)}}</td>
                                <td>{{ $s->customer->name ?? 'Walk-in' }}</td>
                                <td>{{ $s->paymentMethod->name ?? '—' }}</td>
                                <td>{{ $s->user->name ?? '—' }}</td>
                                @php($canDeleteThis = $s->isDeletableBy(auth()->id(), $canDeleteAny))
                                <td class="text-nowrap">
                                    @can('Edit Sales')
                                        <button class="btn btn-sm btn-info"
                                                data-id="{{$s->id}}"
                                                data-receipt="{{$s->receipt_number}}"
                                                data-name="{{ $s->product->name ?? '(deleted product)' }}"
                                                data-type="{{ $s->product && $s->product->isService() ? 'Service' : 'Item' }}"
                                                data-created_at="{{$s->created_at}}"
                                                data-quantity="{{$s->quantity}}"
                                                data-selling_price="{{$s->selling_price}}"
                                                data-customer_id="{{$s->customer_id}}"
                                                data-payment_method_id="{{$s->payment_method_id}}"
                                                data-can_delete="{{ $canDeleteThis ? 1 : 0 }}"
                                                type="button"
                                                data-bs-toggle="modal" data-bs-target="#edit">Edit
                                        </button>
                                        @if ($s->remarks <> '')
                                            <button class="btn btn-sm btn-success"
                                                    data-updated_by="{{ $s->updater ? 'Updated by: '.$s->updater->name.'  at: '.$s->updated_at : '' }}"
                                                    data-updated_at="{{$s->updated_at}}"
                                                    data-remarks="{{$s->remarks}}"
                                                    type="button"
                                                    data-bs-toggle="modal" data-bs-target="#view">View
                                            </button>
                                        @endif
                                    @endcan
                                    @if ($canDeleteThis)
                                        <button class="btn btn-sm btn-outline-danger"
                                                data-id="{{$s->id}}"
                                                data-receipt="{{$s->receipt_number}}"
                                                data-name="{{ $s->product->name ?? '(deleted product)' }}"
                                                data-date_display="{{ date_format($s->created_at,'d M Y') }}"
                                                data-quantity="{{$s->quantity}}"
                                                data-amount="{{ $s->quantity * $s->selling_price }}"
                                                type="button"
                                                data-bs-toggle="modal" data-bs-target="#delete">
                                            <i class="bi bi-trash" aria-hidden="true"></i> Delete
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>

                </div>
<hr>
                <div class="row">

                    <div class="col-md-12">
                        <h4>Total Sales (Tshs): {{number_format($total,2)}}</h4>
                    </div>
                </div>

            </div>
        </div>
    </div>

    </div>
    @endsection

    @push("page_scripts")

    @include('sales.edit_sale_modal')
    @include('sales.view_sale_modal')
    @include('sales.delete_sale_modal')
    @include('partials.notification')

    <script>
        var title = document.title;
        document.title = title.concat(" | Sales | History");
    </script>
    <script>
        $('#fixed-header1').DataTable({
          bAutoWidth: true,
          order: [[0, "desc"]]
      });

    // Same output as the PHP formatAmount() helper: rounded, with thousands separators
    function formatAmount(value) {
        return Math.round(Number(value) || 0).toLocaleString('en-US');
    }

    function refreshEditTotals() {
        var modal = $('#edit');
        var isService = modal.data('sale-type') === 'Service';
        var price = modal.data('selling-price') || 0;
        var quantity = parseInt(modal.find('#edit_quantity').val(), 10) || 0;

        if (isService) {
            return;
        }

        modal.find('#edit_item_total_amount').text('TZS ' + formatAmount(quantity * price));
        modal.find('#edit_item_total_sub').text(quantity + ' × TZS ' + formatAmount(price) + ' (price it was sold at)');
    }

    $('#edit').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var modal = $(this);
        var type = button.data('type') === 'Service' ? 'Service' : 'Item';
        var quantity = parseInt(button.data('quantity'), 10) || 0;
        var sellingPrice = parseFloat(button.data('selling_price')) || 0;

        modal.data('sale-type', type);
        modal.data('selling-price', sellingPrice);

        modal.find('#edit_id').val(button.data('id'));
        modal.find('#edit_receipt').text(button.data('receipt'));
        modal.find('#edit_product_name').text(button.data('name'));
        modal.find('#edit_product_type').text(type).attr('class', 'pos-field-sub ' + (type === 'Service' ? 'pos-text-service' : 'pos-text-item'));
        modal.find('#edit_saledate').val(String(button.data('created_at')).substring(0, 10));
        modal.find('#edit_quantity').val(quantity);

        var customerId = button.data('customer_id');
        modal.find('#edit_customer_id').val(customerId ? String(customerId) : '').trigger('change');

        modal.find('input[name="payment_method_id"]').prop('checked', false);
        var paymentMethodId = button.data('payment_method_id');
        if (paymentMethodId) {
            modal.find('#edit_payment_' + paymentMethodId).prop('checked', true);
        }

        modal.find('#edit_service_total_group').toggleClass('d-none', type !== 'Service');
        modal.find('#edit_item_total_group').toggleClass('d-none', type !== 'Item');
        modal.find('#edit_delete_btn').toggleClass('d-none', ! button.data('can_delete'));

        if (type === 'Service') {
            modal.find('#edit_total').val(Math.round(quantity * sellingPrice));
        } else {
            refreshEditTotals();
        }
    });

    $('#edit_quantity').on('input', function () {
        this.value = this.value.replace(/[^0-9]/g, '');
        refreshEditTotals();
    });

    $('#edit_qty_minus').on('click', function () {
        var input = $('#edit_quantity');
        input.val(Math.max(1, (parseInt(input.val(), 10) || 0) - 1));
        refreshEditTotals();
    });

    $('#edit_qty_plus').on('click', function () {
        var input = $('#edit_quantity');
        input.val(Math.max(1, (parseInt(input.val(), 10) || 0) + 1));
        refreshEditTotals();
    });

    $('#view').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var modal = $(this);

        modal.find('.modal-body #remarks').text(button.data('remarks'));
        modal.find('.modal-body #by').text(button.data('updated_by'));
        modal.find('.modal-body #at').text(button.data('updated_at'));


    });

    function fillDeleteModal(data) {
        $('#delete_id').val(data.id);
        $('#delete_date').text(data.date);
        $('#delete_product').text(data.product);
        $('#delete_quantity').text(data.quantity);
        $('#delete_amount').text('TZS ' + formatAmount(data.amount));
        $('#delete_receipt').text(data.receipt);
        $('#delete_reason').val('');
        $('#delete_submit').prop('disabled', false).text('Delete sale');
    }

    $('#delete').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        if (! button || ! button.length) {
            return; // Opened programmatically from the edit form; fields are already filled.
        }
        fillDeleteModal({
            id: button.data('id'),
            date: button.data('date_display'),
            product: button.data('name'),
            quantity: button.data('quantity'),
            amount: button.data('amount'),
            receipt: button.data('receipt'),
        });
    });

    $('#form_sale_delete').on('submit', function () {
        $('#delete_submit').prop('disabled', true).text('Deleting…');
    });

    // The edit form's own Delete button: close the edit modal, then open the
    // delete confirmation modal with the same sale's current details.
    $('#edit_delete_btn').on('click', function () {
        var editModal = $('#edit');
        var isService = editModal.data('sale-type') === 'Service';
        var sellingPrice = editModal.data('selling-price') || 0;
        var quantity = parseInt($('#edit_quantity').val(), 10) || 0;

        var data = {
            id: $('#edit_id').val(),
            date: $('#edit_saledate').val(),
            product: $('#edit_product_name').text(),
            quantity: quantity,
            amount: isService ? (parseFloat($('#edit_total').val()) || 0) : quantity * sellingPrice,
            receipt: $('#edit_receipt').text(),
        };

        var editModalEl = document.getElementById('edit');
        editModalEl.addEventListener('hidden.bs.modal', function handler() {
            editModalEl.removeEventListener('hidden.bs.modal', handler);
            fillDeleteModal(data);
            bootstrap.Modal.getOrCreateInstance(document.getElementById('delete')).show();
        });
        bootstrap.Modal.getOrCreateInstance(editModalEl).hide();
    });

    </script>
    @endpush