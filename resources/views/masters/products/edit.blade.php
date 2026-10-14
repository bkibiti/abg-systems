<div class="modal fade" id="edit" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="panel-body">
                        <form id="form_product_edit" method="POST" action="{{route('products.update','id')}}" >
                            @csrf
                            @method("PUT")

                            <input type="hidden" name="id" id="id">

                            <div class="form-group row">
                                <label for="product_name" class="col-md-4 col-form-label text-md-right">Product Name <font color="red">*</font></label>

                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="name_edit" name="name" maxlength="50" minlength="2"
                                        placeholder="" required value="{{ old('name') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                    <label for="category" class="col-md-4 col-form-label text-md-right">Category<font color="red">*</font></label>

                                    <div class="col-md-8">
                                        <select class="form-control select2"  class="form-control" id="category_id_edit" name="category_id"  data-placeholder="Select Category" required data-width="100%">
                                                @foreach($categories as $category)
                                                <option value="{{ $category->id }}" name="category">{{ $category->name }}</option>
                                                @endforeach
                                        </select>
                                    </div>
                            </div>
                            <div class="form-group row">
                                    <label for="category" class="col-md-4 col-form-label text-md-right">Sold By<font color="red">*</font></label>

                                    <div class="col-md-8">
                                        <select class="form-control select2"  class="form-control" id="sold_by_edit" name="sold_by"  data-placeholder="Select One" required data-width="100%">
                                            <option value="Bottles" name="category">Bottles</option>
                                            <option value="Shots" name="category">Shots</option>
                                            <option value="Weight" name="category">Weight</option>
                                            <option value="Pieces" name="category">Pieces</option>

                                        </select>
                                    </div>
                            </div>
                            <div class="form-group row">
                                    <label for="category" class="col-md-4 col-form-label text-md-right">Available for Sale<font color="red">*</font></label>

                                    <div class="col-md-8">
                                        <select class="form-control select2"  class="form-control" id="for_sale_edit" name="for_sale"  required data-width="100%">
                                                <option value="Yes" name="category">Yes</option>
                                                <option value="No" name="category">No</option>
                                        </select>
                                    </div>
                            </div>
                            <div class="form-group row">
                                    <label for="category" class="col-md-4 col-form-label text-md-right">Purchase Unit</label>
                                    <div class="col-md-8">
                                            <input type="text" class="form-control" id="purchase_uom_edit" name="purchase_uom" maxlength="50" minlength="2"
                                                placeholder=""  value="{{ old('purchase_uom') }}">
                                    </div>
                            </div>
                            <div class="form-group row">
                                    <label for="category" class="col-md-4 col-form-label text-md-right">Quantity per Unit</label>
                                    <div class="col-md-8">
                                            <input type="number" class="form-control" id="quantity_per_unit_edit" name="quantity_per_unit" min="1"
                                                placeholder=""  value="{{ old('quantity_per_unit') }}">
                                    </div>
                            </div>
                            <div class="form-group row">
                                    <label for="category" class="col-md-4 col-form-label text-md-right">Minimum Quantity</label>
                                    <div class="col-md-8">
                                            <input type="number" class="form-control" id="min_quantinty_edit" name="min_quantinty" min="1"
                                                placeholder=""  value="{{ old('min_quantinty') }}">
                                    </div>
                            </div>
                         


                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>


        </div>
    </div>
</div>
