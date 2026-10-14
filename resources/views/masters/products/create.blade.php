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
                                <label for="category_id" class="col-md-4 col-form-label text-md-end">Category <font color="red">*</font></label>
                                <div class="col-md-8">
                                    <select class="form-select" id="category_id" name="category_id" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                    @if($categories->isEmpty())
                                        <small class="text-danger">No categories yet. Add them in Masters &gt; Product Categories.</small>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="type" class="col-md-4 col-form-label text-md-end">Type <font color="red">*</font></label>
                                <div class="col-md-8">
                                    <select class="form-select product-type" id="type" name="type" required>
                                        <option value="Item" {{ old('type','Item')=='Item' ? 'selected' : '' }}>Item (kept in stock)</option>
                                        <option value="Service" {{ old('type','Item')=='Service' ? 'selected' : '' }}>Service (no stock)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3 price-row">
                                <label for="sale_price_1" class="col-md-4 col-form-label text-md-end">Selling Price</label>
                                <div class="col-md-8">
                                    <input type="number" step="0.01" min="0" class="form-control" id="sale_price_1" name="sale_price_1" value="{{ old('sale_price_1') }}">
                                    <small class="text-muted">Items: the price used at the POS. Services: leave empty, the total is entered at the POS.</small>
                                </div>
                            </div>
                            <div class="row mb-3 min-qty-row">
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
