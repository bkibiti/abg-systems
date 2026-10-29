<div class="modal fade" id="edit" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-fullscreen-sm-down" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit sale</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="form_product_edit" method="POST" action="{{ route('sales.update') }}">
                    @csrf

                    <input type="hidden" name="id" id="edit_id">

                    <div class="mb-4">
                        <div class="pos-muted small">Receipt <span id="edit_receipt"></span></div>
                        <div class="pos-field-name" id="edit_product_name"></div>
                        <span class="pos-field-sub" id="edit_product_type"></span>
                    </div>

                    <div class="mb-4">
                        <label class="form-label pos-label" for="edit_saledate">Sale date</label>
                        <input type="date" class="form-control pos-date-input" id="edit_saledate" name="date"
                            max="{{ now('Africa/Dar_es_Salaam')->toDateString() }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label pos-label" for="edit_customer_id">Customer (optional)</label>
                        <select class="form-select select2" id="edit_customer_id" name="customer_id" data-width="100%">
                            <option value="">Walk-in</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label pos-label" for="edit_quantity">Quantity</label>
                        <div class="d-flex gap-2 align-items-stretch">
                            <button type="button" class="btn btn-secondary pos-qty-btn" id="edit_qty_minus" aria-label="Decrease quantity">
                                <i class="bi bi-dash-lg" aria-hidden="true"></i>
                            </button>
                            <input type="text" class="form-control pos-qty-input" id="edit_quantity" name="quantity"
                                inputmode="numeric" pattern="[0-9]*" autocomplete="off" required>
                            <button type="button" class="btn btn-secondary pos-qty-btn" id="edit_qty_plus" aria-label="Increase quantity">
                                <i class="bi bi-plus-lg" aria-hidden="true"></i>
                            </button>
                        </div>
                        <div class="form-text d-none" id="edit_qty_hint"></div>
                    </div>

                    {{-- Service: total is typed --}}
                    <div class="mb-4 d-none" id="edit_service_total_group">
                        <label class="form-label pos-label" for="edit_total">Total amount (TZS)</label>
                        <input type="text" class="form-control pos-amount-input" id="edit_total" name="total"
                            inputmode="numeric" autocomplete="off">
                    </div>

                    {{-- Item: price is fixed (the price it was sold at), total is calculated --}}
                    <div class="mb-4 d-none" id="edit_item_total_group">
                        <div class="pos-total-box" aria-live="polite">
                            <div class="pos-total-label">TOTAL (CALCULATED)</div>
                            <div class="pos-total-amount" id="edit_item_total_amount">TZS 0</div>
                            <div class="pos-total-sub" id="edit_item_total_sub"></div>
                        </div>
                    </div>

                    <fieldset class="mb-4">
                        <legend class="form-label pos-label">Payment method</legend>
                        <div class="pos-pay-group" id="edit_payment_group">
                            @foreach ($paymentMethods as $method)
                                <input type="radio" class="btn-check" name="payment_method_id" id="edit_payment_{{ $method->id }}"
                                    value="{{ $method->id }}" autocomplete="off">
                                <label class="btn pos-pay-btn" for="edit_payment_{{ $method->id }}">{{ $method->name }}</label>
                            @endforeach
                        </div>
                    </fieldset>

                    <div class="modal-footer px-0 pb-0 d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-danger d-none" id="edit_delete_btn">
                            <i class="bi bi-trash" aria-hidden="true"></i> Delete
                        </button>
                        <div class="ms-auto">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
