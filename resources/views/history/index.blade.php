@extends("layouts.master")

@section('content-title')
History
@endsection

@section('content-sub-title')
<li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bi bi-house"></i></a></li>
<li class="breadcrumb-item"><a href="#">History</a></li>
@endsection

@section("content")

<div class="col-12 d-lg-none history-phone-header mb-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0 history-phone-title">History</h4>
        <button type="button" class="history-search-btn" id="history_search_toggle" aria-label="Search history">
            <i class="bi bi-search"></i>
        </button>
    </div>
    <div class="mb-2 d-none" id="history_search_row">
        <input type="search" class="form-control pos-search-input" id="history_search_input"
            placeholder="Search history" aria-label="Search history" autocomplete="off">
    </div>
    <div class="history-chip-row">
        <a href="{{ route('history.index', ['type' => 'all', 'from_date' => $fromDate, 'to_date' => $toDate]) }}"
            class="history-chip {{ $type === 'all' ? 'history-chip-active' : '' }}">All</a>
        <a href="{{ route('history.index', ['type' => 'sales', 'from_date' => $fromDate, 'to_date' => $toDate]) }}"
            class="history-chip {{ $type === 'sales' ? 'history-chip-active' : '' }}">Sales</a>
        <a href="{{ route('history.index', ['type' => 'expenses', 'from_date' => $fromDate, 'to_date' => $toDate]) }}"
            class="history-chip {{ $type === 'expenses' ? 'history-chip-active' : '' }}">Expenses</a>
    </div>
</div>

<div class="col-12 d-none d-lg-block mb-3">
    <h3 class="card-title">History</h3>
    <p class="pos-muted">{{ \Carbon\CarbonImmutable::parse($fromDate)->format('j M Y') }} &ndash; {{ \Carbon\CarbonImmutable::parse($toDate)->format('j M Y') }} &middot; Total {{ formatAmount($total) }}</p>
</div>

<div class="col-12" id="history_list">
    @forelse ($groups as $group)
        <div class="history-day-group mb-3">
            <div class="d-flex justify-content-between align-items-baseline mb-2">
                <span class="phone-section-title">{{ $group['label'] }}</span>
                <span class="pos-muted small">{{ $group['rows']->count() }} {{ Str::plural('entry', $group['rows']->count()) }}</span>
            </div>

            @foreach ($group['rows'] as $row)
                @php
                    $initials = mb_strtoupper(mb_substr(trim(preg_replace('/[^A-Za-z0-9 ]/', '', $row['title'])) ?: $row['title'], 0, 2));
                    $isEditableSale = $row['kind'] === 'sale' && Auth::user()->can('Edit Sales');
                    $isEditableExpense = $row['kind'] === 'expense' && Auth::user()->can('Edit Expenses');
                @endphp
                <div class="history-card {{ ($isEditableSale || $isEditableExpense) ? 'history-card-clickable' : '' }} js-history-search-row"
                    data-search="{{ Str::lower($row['title'].' '.$row['sub']) }}"
                    @if ($isEditableSale)
                        role="button" tabindex="0" data-bs-toggle="modal" data-bs-target="#edit"
                        data-id="{{ $row['sale']->id }}"
                        data-receipt="{{ $row['sale']->receipt_number }}"
                        data-name="{{ $row['sale']->product->name ?? '(deleted product)' }}"
                        data-type="{{ $row['sale']->product && $row['sale']->product->isService() ? 'Service' : 'Item' }}"
                        data-created_at="{{ $row['sale']->created_at }}"
                        data-quantity="{{ $row['sale']->quantity }}"
                        data-selling_price="{{ $row['sale']->selling_price }}"
                        data-customer_id="{{ $row['sale']->customer_id }}"
                        data-payment_method_id="{{ $row['sale']->payment_method_id }}"
                        data-can_delete="{{ $row['canDeleteThis'] ? 1 : 0 }}"
                    @elseif ($isEditableExpense)
                        role="button" tabindex="0" data-bs-toggle="modal" data-bs-target="#historyExpenseEdit"
                        data-id="{{ $row['expense']->id }}"
                        data-date="{{ $row['expense']->created_at }}"
                        data-amount="{{ $row['expense']->amount }}"
                        data-expense_category="{{ $row['expense']->expense_category_id }}"
                        data-expense_description="{{ $row['expense']->expense_description }}"
                    @endif>
                    <span class="history-card-badge {{ $row['kind'] === 'sale' ? 'tint-sales' : 'tint-expense' }}">{{ $initials }}</span>
                    <div class="history-card-main">
                        <div class="history-card-title">{{ $row['title'] }}</div>
                        <div class="history-card-sub">{{ $row['sub'] }}</div>
                    </div>
                    <div class="history-card-end">
                        <div class="history-card-amount {{ $row['kind'] === 'sale' ? 'phone-feed-amount-sale' : 'phone-feed-amount-expense' }}">
                            {{ $row['kind'] === 'sale' ? '+' : '-' }}{{ formatAmount($row['amount']) }}
                        </div>
                        <div class="history-card-time">{{ $row['date']->format('H:i') }}</div>
                    </div>
                    @if ($row['kind'] === 'sale' && $row['canDeleteThis'])
                        <button type="button" class="history-card-delete js-history-delete-sale" aria-label="Delete sale"
                            data-bs-toggle="modal" data-bs-target="#delete"
                            data-id="{{ $row['sale']->id }}"
                            data-receipt="{{ $row['sale']->receipt_number }}"
                            data-name="{{ $row['sale']->product->name ?? '(deleted product)' }}"
                            data-date_display="{{ $row['date']->format('j M Y') }}"
                            data-quantity="{{ $row['sale']->quantity }}"
                            data-amount="{{ $row['amount'] }}">
                            <i class="bi bi-trash" aria-hidden="true"></i>
                        </button>
                    @endif
                </div>
            @endforeach
        </div>
    @empty
        <p class="pos-muted text-center py-5">No sales or expenses in this period.</p>
    @endforelse

    @php
        $earlierFrom = \Carbon\CarbonImmutable::parse($fromDate)->subMonth()->toDateString();
    @endphp
    <div class="text-center mb-4">
        <a href="{{ route('history.index', ['type' => $type, 'from_date' => $earlierFrom, 'to_date' => $toDate]) }}"
            class="btn btn-outline-secondary history-filter-btn">Load more</a>
    </div>
</div>

@can('Point of Sales')
    <a href="{{ route('sales.index') }}" class="history-fab d-lg-none" aria-label="New sale">
        <i class="bi bi-plus-lg"></i>
    </a>
@endcan

@if ($canViewSalesHistory)
    @include('sales.edit_sale_modal')
    @include('sales.delete_sale_modal')
@endif

{{-- Not expense.edit: that partial also uses id="edit", which would collide with sales.edit_sale_modal above. --}}
@can('Edit Expenses')
    <div class="modal fade" id="historyExpenseEdit" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-md modal-fullscreen-sm-down" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update expense</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="historyExpenseEditForm" action="{{ route('expense.update') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="he_id" name="id">

                        <div class="mb-4">
                            <label class="form-label pos-label" for="he_date">Date</label>
                            <input type="date" id="he_date" name="expense_date" class="form-control pos-date-input"
                                max="{{ now('Africa/Dar_es_Salaam')->toDateString() }}" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label pos-label" for="he_amount">Amount (TZS)</label>
                            <input type="text" id="he_amount" class="form-control pos-amount-input" inputmode="numeric" name="expense_amount" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label pos-label" for="he_category">Category</label>
                            <select id="he_category" name="expense_category" class="form-select">
                                @foreach ($expense_category as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label pos-label" for="he_description">Description</label>
                            <textarea class="form-control" id="he_description" name="expense_description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endcan

@endsection

@push("page_scripts")
@include('partials.notification')
<script>
    document.title = document.title.concat(" | History");

    // Same output as the PHP formatAmount() helper: rounded, with thousands separators
    function formatAmount(value) {
        return Math.round(Number(value) || 0).toLocaleString('en-US');
    }

    // Search box: filters the already-rendered cards by title/sub text
    (function () {
        const toggle = document.getElementById('history_search_toggle');
        const row = document.getElementById('history_search_row');
        const input = document.getElementById('history_search_input');
        if (! toggle) { return; }

        toggle.addEventListener('click', function () {
            row.classList.toggle('d-none');
            if (! row.classList.contains('d-none')) { input.focus(); }
        });

        input.addEventListener('input', function () {
            const term = input.value.trim().toLowerCase();
            document.querySelectorAll('.js-history-search-row').forEach(function (card) {
                card.classList.toggle('d-none', term !== '' && ! card.dataset.search.includes(term));
            });
            document.querySelectorAll('.history-day-group').forEach(function (group) {
                const visible = group.querySelectorAll('.js-history-search-row:not(.d-none)').length;
                group.classList.toggle('d-none', visible === 0);
            });
        });
    })();

    @if ($canViewSalesHistory)
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
        if (! button || ! button.length || ! button.hasClass('js-history-delete-sale')) {
            return;
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
    @endif

    @can('Edit Expenses')
    $('#historyExpenseEdit').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var modal = $(this);

        modal.find('#he_date').val(String(button.data('date')).substring(0, 10));
        modal.find('#he_amount').val(button.data('amount'));
        modal.find('#he_category').val(button.data('expense_category'));
        modal.find('#he_description').val(button.data('expense_description'));
        modal.find('#he_id').val(button.data('id'));
    });
    @endcan

    // History cards are div[role=button]; Enter/Space open the same modal a click would.
    document.querySelectorAll('.history-card-clickable[role="button"]').forEach(function (card) {
        card.addEventListener('keydown', function (event) {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                card.click();
            }
        });
    });
</script>
@endpush
