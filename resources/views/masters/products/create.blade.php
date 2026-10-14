<div class="modal fade" id="create" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="panel-body">
                        <form method="POST" action="{{ route('products.store') }}" >
                            @csrf

                            <div class="form-group row">
                                <label for="product_name" class="col-md-4 col-form-label text-md-right">Product Name <font color="red">*</font></label>

                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="name_edit" name="name" maxlength="100" minlength="2"
                                        placeholder="" required value="{{ old('name') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                    <label for="category" class="col-md-4 col-form-label text-md-right">Minimum Quantity</label>
                                    <div class="col-md-8">
                                            <input type="number" class="form-control" id="min_quantinty" name="min_quantinty" min="0"
                                                placeholder=""  value="{{ old('min_quantinty') }}">
                                    </div>
                            </div>


                        <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>


        </div>
    </div>
</div>
