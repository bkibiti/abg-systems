<div class="modal fade" id="edit" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <div>
                        <form id="form_product_edit" method="POST" action="{{route('products.update','id')}}" >
                            @csrf
                            @method("PUT")

                            <input type="hidden" name="id" id="id">

                            <div class="row mb-3">
                                <label for="product_name" class="col-md-4 col-form-label text-md-end">Product Name <font color="red">*</font></label>

                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="name_edit" name="name" maxlength="100" minlength="2"
                                        placeholder="" required value="{{ old('name') }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="category_id_edit" class="col-md-4 col-form-label text-md-end">Category <font color="red">*</font></label>
                                <div class="col-md-8">
                                    <select class="form-select" id="category_id_edit" name="category_id" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" >{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                    @if($categories->isEmpty())
                                        <small class="text-danger">No categories yet. Add them in Masters &gt; Product Categories.</small>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                    <label for="category" class="col-md-4 col-form-label text-md-end">Minimum Quantity</label>
                                    <div class="col-md-8">
                                            <input type="number" class="form-control" id="min_quantinty_edit" name="min_quantinty" min="0"
                                                placeholder=""  value="{{ old('min_quantinty') }}">
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
