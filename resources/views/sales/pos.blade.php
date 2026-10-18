@extends("layouts.master")

@section('content-title')
New sale
@endsection

@section("content")

@php
    $defaultPaymentMethodId = optional($paymentMethods->firstWhere('name', 'Cash') ?? $paymentMethods->first())->id;
    $selectedPaymentMethodId = (int) old('payment_method_id', $defaultPaymentMethodId);
    $toLocalTime = fn ($date) => \Carbon\CarbonImmutable::parse($date)->setTimezone('Africa/Dar_es_Salaam');
@endphp

<div class="col-12">
    <div class="row pos {{ $lastSale ? 'pos-just-saved' : '' }}" id="pos">

        @if ($lastSale)
            {{-- Desktop: success bar above an empty form --}}
            <div class="col-12 mb-3 pos-success-bar-col">
                <div class="pos-success-bar" role="status">
                    <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
                    <span>
                        <strong>Sale saved.</strong>
                        Receipt {{ $lastSale['receipt_number'] }} · {{ $lastSale['product'] }} · TZS {{ formatAmount($lastSale['amount']) }}
                    </span>
                </div>
            </div>

            {{-- Phone: "Sale saved" panel shown instead of the form --}}
            <div class="col-12 mb-4 pos-saved-panel" role="status">
                <div class="text-center mb-3">
                    <span class="pos-saved-check"><i class="bi bi-check-lg" aria-hidden="true"></i></span>
                    <h4 class="pos-saved-title mt-3 mb-1">Sale saved</h4>
                    <div class="pos-muted">Receipt {{ $lastSale['receipt_number'] }} · {{ $lastSale['time'] }}</div>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <dl class="pos-saved-details mb-0">
                            <div><dt>Product</dt><dd>{{ $lastSale['product'] }}</dd></div>
                            <div><dt>Quantity</dt><dd>{{ formatAmount($lastSale['quantity']) }}</dd></div>
                            <div><dt>Customer</dt><dd>{{ $lastSale['customer'] }}</dd></div>
                            <div><dt>Payment method</dt><dd>{{ $lastSale['payment_method'] }}</dd></div>
                            <div class="pos-saved-total"><dt>Total</dt><dd>TZS {{ formatAmount($lastSale['amount']) }}</dd></div>
                        </dl>
                    </div>
                </div>

                @if ($todaySales->isNotEmpty())
                    <div class="pos-section-label mb-2">Recorded today by me</div>
                    <div class="card mb-3">
                        <div class="card-body py-2">
                            @foreach ($todaySales->take(3) as $sale)
                                <div class="pos-today-row">
                                    <div class="pos-today-main">
                                        <div class="pos-today-name">{{ $sale->product->name ?? '—' }}</div>
                                        <div class="pos-muted small">{{ $toLocalTime($sale->created_at)->format('H:i') }} · {{ $sale->customer->name ?? 'Walk-in' }}</div>
                                    </div>
                                    <div class="pos-today-amount">{{ formatAmount((float) $sale->quantity * (float) $sale->selling_price) }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <button type="button" class="btn btn-primary w-100 pos-btn-xl mb-2" id="record_another">Record another sale</button>
                <a href="{{ route('home') }}" class="btn btn-secondary w-100 pos-btn-lg">Back to dashboard</a>
            </div>
        @endif

        <div class="col-lg-8 mb-4 pos-form-col">
            <form method="POST" action="{{ route('sales.store') }}" id="sale_form" class="card pos-form-card" novalidate>
                @csrf
                <div class="card-body">

                    {{-- 1. Product or service --}}
                    <div class="mb-4">
                        <label class="form-label pos-label" id="product_label">Product or service</label>
                        <input type="hidden" name="product_id" id="product_id" value="{{ old('product_id') }}">
                        <button type="button" class="pos-field @error('product_id') is-invalid @enderror" id="product_field"
                            data-bs-toggle="modal" data-bs-target="#productModal" aria-labelledby="product_label product_field_name">
                            <span class="pos-field-text">
                                <span class="pos-field-name pos-placeholder" id="product_field_name">Select product or service</span>
                                <span class="pos-field-sub d-none" id="product_field_sub"></span>
                            </span>
                            <i class="bi bi-search" aria-hidden="true"></i>
                        </button>
                        @error('product_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- 2. Customer --}}
                    <div class="mb-4">
                        <label class="form-label pos-label" id="customer_label">Customer (optional)</label>
                        <input type="hidden" name="customer_id" id="customer_id" value="{{ old('customer_id') }}">
                        <div class="d-flex gap-2">
                            <button type="button" class="pos-field flex-grow-1 @error('customer_id') is-invalid @enderror" id="customer_field"
                                data-bs-toggle="modal" data-bs-target="#customerModal" aria-labelledby="customer_label customer_field_name">
                                <span class="pos-field-text">
                                    <span class="pos-field-name pos-placeholder" id="customer_field_name">Select customer</span>
                                </span>
                                <i class="bi bi-person" aria-hidden="true"></i>
                            </button>
                            <button type="button" class="btn btn-secondary pos-clear-customer d-none" id="customer_clear" aria-label="Clear customer">
                                <i class="bi bi-x-lg" aria-hidden="true"></i>
                            </button>
                        </div>
                        @error('customer_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- 3. Quantity --}}
                    <div class="mb-4">
                        <label class="form-label pos-label" for="quantity">Quantity</label>
                        <div class="d-flex gap-2 align-items-stretch">
                            <button type="button" class="btn btn-secondary pos-qty-btn" id="qty_minus" aria-label="Decrease quantity">
                                <i class="bi bi-dash-lg" aria-hidden="true"></i>
                            </button>
                            <input type="text" class="form-control pos-qty-input @error('quantity') is-invalid @enderror" name="quantity" id="quantity"
                                inputmode="numeric" pattern="[0-9]*" autocomplete="off" value="{{ old('quantity', 1) }}">
                            <button type="button" class="btn btn-secondary pos-qty-btn" id="qty_plus" aria-label="Increase quantity">
                                <i class="bi bi-plus-lg" aria-hidden="true"></i>
                            </button>
                        </div>
                        <div class="form-text d-none" id="qty_hint"></div>
                        @error('quantity')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- 4a. Service: total is typed --}}
                    <div class="mb-4 d-none" id="service_total_group">
                        <label class="form-label pos-label" for="total">Total amount (TZS)</label>
                        <input type="text" class="form-control pos-amount-input @error('total') is-invalid @enderror" name="total" id="total"
                            inputmode="numeric" autocomplete="off" value="{{ old('total') }}">
                        <div class="form-text">Enter the price agreed with the customer for the whole job.</div>
                        @error('total')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- 4b. Item: total is calculated --}}
                    <div class="mb-4 d-none" id="item_total_group">
                        <div class="pos-total-box" aria-live="polite">
                            <div class="pos-total-label">TOTAL (CALCULATED)</div>
                            <div class="pos-total-amount" id="item_total_amount">TZS 0</div>
                            <div class="pos-total-sub" id="item_total_sub"></div>
                        </div>
                    </div>

                    {{-- 5. Payment method --}}
                    <fieldset class="mb-4">
                        <legend class="form-label pos-label">Payment method</legend>
                        <div class="pos-pay-group">
                            @foreach ($paymentMethods as $method)
                                <input type="radio" class="btn-check" name="payment_method_id" id="payment_{{ $method->id }}"
                                    value="{{ $method->id }}" autocomplete="off" @checked($selectedPaymentMethodId === $method->id)>
                                <label class="btn pos-pay-btn" for="payment_{{ $method->id }}">{{ $method->name }}</label>
                            @endforeach
                        </div>
                        @error('payment_method_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </fieldset>

                    @if ($canSelectSaleDate)
                        <div class="mb-4">
                            <label class="form-label pos-label" for="sale_date">Sale date</label>
                            <input type="date" class="form-control pos-date-input @error('sale_date') is-invalid @enderror" name="sale_date" id="sale_date"
                                max="{{ $todayDate }}" value="{{ old('sale_date', $todayDate) }}">
                            <div class="form-text">Leave as today unless you are recording an earlier sale.</div>
                            @error('sale_date')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif

                    {{-- 6. Desktop buttons --}}
                    <div class="d-none d-lg-flex justify-content-end gap-2 pt-2">
                        <button type="button" class="btn btn-secondary pos-btn-lg px-4 js-clear">Clear</button>
                        <button type="submit" class="btn btn-primary pos-btn-lg px-4 js-save" disabled>Save sale</button>
                    </div>
                </div>

                {{-- 6. Phone buttons --}}
                <div class="pos-phone-footer d-lg-none">
                    <div class="pos-phone-summary">My sales today: {{ $todayCount }} · TZS {{ formatAmount($todayTotal) }}</div>
                    <button type="submit" class="btn btn-primary w-100 pos-btn-xl js-save" disabled>Save sale</button>
                </div>
            </form>
        </div>

        <div class="col-lg-4 mb-4 pos-today-col">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">My sales today</h5>
                </div>
                <div class="card-body">
                    <div class="pos-today-summary mb-2">{{ $todayCount }} {{ Str::plural('sale', $todayCount) }} · TZS {{ formatAmount($todayTotal) }}</div>
                    @forelse ($todaySales as $sale)
                        <div class="pos-today-row">
                            <div class="pos-today-main">
                                <div class="pos-today-name">{{ $sale->product->name ?? '—' }}</div>
                                <div class="pos-muted small">{{ $toLocalTime($sale->created_at)->format('H:i') }} · {{ $sale->customer->name ?? 'Walk-in' }}</div>
                            </div>
                            <div class="pos-today-amount">{{ formatAmount((float) $sale->quantity * (float) $sale->selling_price) }}</div>
                        </div>
                    @empty
                        <p class="pos-muted mb-0">No sales yet today.</p>
                    @endforelse

                    @can('View Sales History')
                        <div class="pt-3">
                            <a href="{{ route('sales.history') }}" class="staff-view-all">Sales history</a>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Product search --}}
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-fullscreen-lg-down">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalTitle">Select product or service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="px-3 pt-3">
                <input type="search" class="form-control pos-search-input js-search" placeholder="Search products and services"
                    aria-label="Search products and services" autocomplete="off" data-list="#product_list">
            </div>
            <div class="modal-body">
                <div class="pos-pick-list" id="product_list">
                    @foreach ($products as $product)
                        @php($isService = $product->isService())
                        <button type="button" class="pos-pick-row js-product"
                            data-id="{{ $product->id }}"
                            data-name="{{ $product->name }}"
                            data-type="{{ $isService ? 'Service' : 'Item' }}"
                            data-price="{{ $isService ? 0 : (float) $product->sale_price_1 }}"
                            data-stock="{{ $isService ? 0 : (float) $product->quantity }}"
                            data-search="{{ Str::lower($product->name) }}">
                            <span class="pos-pick-name">{{ $product->name }}</span>
                            @if ($isService)
                                <span class="pos-pick-sub pos-text-service">Service</span>
                            @else
                                <span class="pos-pick-sub pos-text-item">
                                    Item · TZS {{ formatAmount($product->sale_price_1) }} each · {{ formatAmount($product->quantity) }} in stock
                                </span>
                            @endif
                        </button>
                    @endforeach
                    <p class="pos-muted text-center py-4 mb-0 d-none js-no-match">No matching products or services.</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Customer search --}}
<div class="modal fade" id="customerModal" tabindex="-1" aria-labelledby="customerModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-fullscreen-lg-down">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="customerModalTitle">Select customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="px-3 pt-3">
                <input type="search" class="form-control pos-search-input js-search" placeholder="Search customers"
                    aria-label="Search customers" autocomplete="off" data-list="#customer_list">
            </div>
            <div class="modal-body">
                <div class="pos-pick-list" id="customer_list">
                    @foreach ($customers as $customer)
                        <button type="button" class="pos-pick-row js-customer"
                            data-id="{{ $customer->id }}"
                            data-name="{{ $customer->name }}"
                            data-search="{{ Str::lower($customer->name) }}">
                            <span class="pos-pick-name">{{ $customer->name }}</span>
                        </button>
                    @endforeach
                    <p class="pos-muted text-center py-4 mb-0 {{ $customers->isEmpty() ? '' : 'd-none' }} js-no-match">No matching customers.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push("page_scripts")
<script>
    document.title = document.title.concat(" | Sales | New sale");

    (function () {
        const form = document.getElementById('sale_form');
        const productInput = document.getElementById('product_id');
        const productName = document.getElementById('product_field_name');
        const productSub = document.getElementById('product_field_sub');
        const customerInput = document.getElementById('customer_id');
        const customerName = document.getElementById('customer_field_name');
        const customerClear = document.getElementById('customer_clear');
        const quantityInput = document.getElementById('quantity');
        const quantityHint = document.getElementById('qty_hint');
        const totalInput = document.getElementById('total');
        const serviceGroup = document.getElementById('service_total_group');
        const itemGroup = document.getElementById('item_total_group');
        const itemTotalAmount = document.getElementById('item_total_amount');
        const itemTotalSub = document.getElementById('item_total_sub');
        const saveButtons = form.querySelectorAll('.js-save');

        let selectedProduct = null;

        // Same output as the PHP formatAmount() helper: rounded, with thousands separators
        function formatAmount(value) {
            return Math.round(Number(value) || 0).toLocaleString('en-US');
        }

        function currentQuantity() {
            const quantity = parseInt(quantityInput.value, 10);

            return Number.isNaN(quantity) ? 0 : quantity;
        }

        function refresh() {
            const quantity = currentQuantity();
            let canSave = selectedProduct !== null && quantity >= 1;

            serviceGroup.classList.toggle('d-none', ! selectedProduct || selectedProduct.type !== 'Service');
            itemGroup.classList.toggle('d-none', ! selectedProduct || selectedProduct.type !== 'Item');
            quantityHint.classList.add('d-none');

            if (selectedProduct && selectedProduct.type === 'Item') {
                itemTotalAmount.textContent = 'TZS ' + formatAmount(selectedProduct.price * quantity);
                itemTotalSub.textContent = quantity + ' × TZS ' + formatAmount(selectedProduct.price);

                if (selectedProduct.price <= 0) {
                    quantityHint.textContent = 'This item has no selling price. Set it in Masters > Products first.';
                    quantityHint.classList.remove('d-none');
                    canSave = false;
                } else if (quantity > selectedProduct.stock) {
                    quantityHint.textContent = 'Only ' + formatAmount(selectedProduct.stock) + ' in stock.';
                    quantityHint.classList.remove('d-none');
                    canSave = false;
                }
            }

            saveButtons.forEach(function (button) { button.disabled = ! canSave; });
        }

        function selectProduct(row) {
            selectedProduct = row ? {
                id: row.dataset.id,
                name: row.dataset.name,
                type: row.dataset.type,
                price: parseFloat(row.dataset.price) || 0,
                stock: parseFloat(row.dataset.stock) || 0,
            } : null;

            productInput.value = selectedProduct ? selectedProduct.id : '';
            productName.textContent = selectedProduct ? selectedProduct.name : 'Select product or service';
            productName.classList.toggle('pos-placeholder', ! selectedProduct);
            productSub.classList.toggle('d-none', ! selectedProduct);

            if (selectedProduct) {
                const isService = selectedProduct.type === 'Service';
                productSub.textContent = isService
                    ? 'Service'
                    : 'Item · TZS ' + formatAmount(selectedProduct.price) + ' each · ' + formatAmount(selectedProduct.stock) + ' in stock';
                productSub.className = 'pos-field-sub ' + (isService ? 'pos-text-service' : 'pos-text-item');
            }

            refresh();
        }

        function selectCustomer(row) {
            customerInput.value = row ? row.dataset.id : '';
            customerName.textContent = row ? row.dataset.name : 'Select customer';
            customerName.classList.toggle('pos-placeholder', ! row);
            customerClear.classList.toggle('d-none', ! row);
        }

        function clearForm() {
            selectProduct(null);
            selectCustomer(null);
            quantityInput.value = 1;
            if (totalInput) { totalInput.value = ''; }
            form.querySelectorAll('.invalid-feedback').forEach(function (el) { el.remove(); });
            form.querySelectorAll('.is-invalid').forEach(function (el) { el.classList.remove('is-invalid'); });
            refresh();
        }

        // Pick lists
        document.querySelectorAll('.js-product').forEach(function (row) {
            row.addEventListener('click', function () {
                selectProduct(row);
                bootstrap.Modal.getOrCreateInstance(document.getElementById('productModal')).hide();
            });
        });

        document.querySelectorAll('.js-customer').forEach(function (row) {
            row.addEventListener('click', function () {
                selectCustomer(row);
                bootstrap.Modal.getOrCreateInstance(document.getElementById('customerModal')).hide();
            });
        });

        customerClear.addEventListener('click', function () { selectCustomer(null); });

        // Search as you type
        document.querySelectorAll('.js-search').forEach(function (search) {
            const list = document.querySelector(search.dataset.list);
            const rows = list.querySelectorAll('.pos-pick-row');
            const noMatch = list.querySelector('.js-no-match');

            search.addEventListener('input', function () {
                const term = search.value.trim().toLowerCase();
                let shown = 0;
                rows.forEach(function (row) {
                    const match = row.dataset.search.includes(term);
                    row.classList.toggle('d-none', ! match);
                    if (match) { shown++; }
                });
                noMatch.classList.toggle('d-none', shown > 0);
            });

            const modal = search.closest('.modal');
            modal.addEventListener('shown.bs.modal', function () { search.focus(); });
            modal.addEventListener('hidden.bs.modal', function () {
                search.value = '';
                search.dispatchEvent(new Event('input'));
            });
        });

        // Quantity
        document.getElementById('qty_minus').addEventListener('click', function () {
            quantityInput.value = Math.max(1, currentQuantity() - 1);
            refresh();
        });
        document.getElementById('qty_plus').addEventListener('click', function () {
            quantityInput.value = Math.max(1, currentQuantity() + 1);
            refresh();
        });
        quantityInput.addEventListener('input', function () {
            quantityInput.value = quantityInput.value.replace(/[^0-9]/g, '');
            refresh();
        });

        form.querySelectorAll('.js-clear').forEach(function (button) {
            button.addEventListener('click', clearForm);
        });

        // Save once: block a second click while the first request is on its way
        form.addEventListener('submit', function (event) {
            if (form.dataset.submitting === '1' || ! selectedProduct) {
                event.preventDefault();

                return;
            }
            form.dataset.submitting = '1';
            saveButtons.forEach(function (button) {
                button.disabled = true;
                button.textContent = 'Saving…';
            });
        });

        // Coming back with the browser's Back button must not leave the buttons stuck on "Saving…"
        window.addEventListener('pageshow', function (event) {
            if (event.persisted) {
                form.dataset.submitting = '';
                saveButtons.forEach(function (button) { button.textContent = 'Save sale'; });
                refresh();
            }
        });

        // Phone: leave the "Sale saved" panel and show the empty form
        const recordAnother = document.getElementById('record_another');
        if (recordAnother) {
            recordAnother.addEventListener('click', function () {
                document.getElementById('pos').classList.remove('pos-just-saved');
                window.scrollTo(0, 0);
            });
        }

        // Restore old() values after a validation error
        if (productInput.value) {
            selectProduct(document.querySelector('.js-product[data-id="' + CSS.escape(productInput.value) + '"]'));
        }
        if (customerInput.value) {
            selectCustomer(document.querySelector('.js-customer[data-id="' + CSS.escape(customerInput.value) + '"]'));
        }
        refresh();
    })();
</script>
@endpush
