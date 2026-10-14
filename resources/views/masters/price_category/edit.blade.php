<div style="display: none" class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Price Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('price-categories.update','id')}}" method="post">
                @csrf()
                @method("PUT")

                <div class="modal-body">
                    <div class="form-group row">
                        <label for="code" class="col-md-4 col-form-label text-md-right">{{ __('Category Name') }} </label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="name" name="name" maxlength="45">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="code" class="col-md-4 col-form-label text-md-right">{{ __('Description') }} <font
                                color="red">*</font></label>
                        <div class="col-md-8">
                                <input type="text" class="form-control" id="description" name="description" maxlength="45">

                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="category" class="col-md-4 col-form-label text-md-right">Available for Sale<font color="red">*</font></label>

                        <div class="col-md-8">
                            <select class="form-control select2"  class="form-control" id="active" name="active"  required data-width="100%">
                                    <option value="Yes" name="category">Yes</option>
                                    <option value="No" name="category">No</option>
                            </select>
                        </div>
                </div>
                    <input type="hidden" name="price_category_id" id="price_category_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
