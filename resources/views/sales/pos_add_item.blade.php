<div class="modal fade" id="addItemsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="add_title">Add to Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="addItemForm" novalidate>
                <div class="modal-body">
                    <input type="hidden" id="add_id">
                    <input type="hidden" id="add_type">
                    <input type="hidden" id="add_qoh">
                    <input type="hidden" id="add_unit_cost">

                    <div class="mb-2">
                        <strong id="add_name"></strong>
                        <span class="badge ms-1" id="add_badge"></span>
                    </div>

                    {{-- ITEM: price comes from stock --}}
                    <div class="item-only">
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label text-md-end">In Stock</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control-plaintext" id="add_qoh_text" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label text-md-end">Unit Price</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="add_price_text" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="add_qty" class="col-md-4 col-form-label text-md-end">Quantity <font color="red">*</font></label>
                        <div class="col-md-8">
                            <input type="number" class="form-control" id="add_qty" min="1" step="1" required>
                        </div>
                    </div>

                    {{-- SERVICE: total cost is entered --}}
                    <div class="row mb-3 service-only">
                        <label for="add_total" class="col-md-4 col-form-label text-md-end">Total Cost <font color="red">*</font></label>
                        <div class="col-md-8">
                            <input type="number" class="form-control" id="add_total" min="0.01" step="0.01">
                        </div>
                    </div>

                    <div class="row mb-1">
                        <label class="col-md-4 col-form-label text-md-end" id="add_calc_label">Total</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control-plaintext fw-bold" id="add_calc" readonly>
                        </div>
                    </div>

                    <div class="alert alert-danger py-2 mb-0 d-none" id="add_error"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="add_submit">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
