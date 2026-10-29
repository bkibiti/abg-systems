<div class="modal fade" id="delete" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-fullscreen-sm-down" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete this sale?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="card mb-3">
                    <div class="card-body py-2">
                        <dl class="pos-saved-details mb-0">
                            <div><dt>Date</dt><dd id="delete_date"></dd></div>
                            <div><dt>Product</dt><dd id="delete_product"></dd></div>
                            <div><dt>Quantity</dt><dd id="delete_quantity"></dd></div>
                            <div><dt>Amount</dt><dd id="delete_amount"></dd></div>
                            <div><dt>Receipt</dt><dd id="delete_receipt"></dd></div>
                        </dl>
                    </div>
                </div>

                <form id="form_sale_delete" method="POST" action="{{ route('sales.destroy') }}">
                    @csrf
                    @method('DELETE')

                    <input type="hidden" name="id" id="delete_id">

                    <div class="mb-3">
                        <label class="form-label pos-label" for="delete_reason">Reason<span style="color: red;">*</span></label>
                        <textarea class="form-control" id="delete_reason" name="reason" rows="3" minlength="3" maxlength="255" required></textarea>
                    </div>

                    <div class="modal-footer px-0 pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger" id="delete_submit">Delete sale</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
