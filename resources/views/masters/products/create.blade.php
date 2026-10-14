<div class="modal fade" id="create" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <div>
                        <form method="POST" action="{{ route('products.store') }}" >
                            @csrf

                            <div class="row mb-3">
                                <label for="product_name" class="col-md-4 col-form-label text-md-end">Product Name <font color="red">*</font></label>

                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="name_edit" name="name" maxlength="100" minlength="2"
                                        placeholder="" required value="{{ old('name') }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                    <label for="category" class="col-md-4 col-form-label text-md-end">Minimum Quantity</label>
                                    <div class="col-md-8">
                                            <input type="number" class="form-control" id="min_quantinty" name="min_quantinty" min="0"
                                                placeholder=""  value="{{ old('min_quantinty') }}">
                                    </div>
                            </div>


                        <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>


        </div>
    </div>
</div>
