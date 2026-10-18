<div class="modal fade" id="edit" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-fullscreen-sm-down" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Selling Price</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <div>
                        <form id="form_product_edit" method="POST" action="{{route('current-stock.update','id')}}" >
                            @csrf
                            @method("PUT")

                            <input type="hidden" name="id" id="id">

                            <div class="row mb-3">
                                    <label for="category" class="col-md-4 col-form-label text-md-end">Product Name</label>
                                    <div class="col-md-8" >
                                            <input type="text" class="form-control" id="name" readonly>
                                    </div>
                            </div>
                            <div class="row mb-3">
                                <label for="category" class="col-md-4 col-form-label text-md-end">Unit Cost</label>
                                <div class="col-md-8">
                                        <input type="text" class="form-control" id="unit_cost" name="unit_cost" maxlength="50" minlength="3"
                                            value="{{ old('unit_cost') }}" required>
                                </div>
                            </div>

                                <div class="row mb-3">
                                        <label for="category" class="col-md-4 col-form-label text-md-end">Selling Price</label>
                                        <div class="col-md-8">
                                                <input type="text" class="form-control" id="sale_price_1" name="sale_price_1" maxlength="50" minlength="3"
                                                    value="{{ old('sale_price_1') }}" required>
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
    </div>
</div>
