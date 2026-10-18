<div class="modal fade" id="edit" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-fullscreen-sm-down" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Sales</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <div>
                        <form id="form_product_edit" method="POST" action="{{route('sales.update')}}" >
                            @csrf
                        

                            <input type="hidden" name="id" id="id">

                            <div class="row mb-3">
                                <label for="saledate" class="col-md-4 col-form-label text-md-end">Sale Date<span style="color: red;">*</span></label>
                                <div class="col-md-8">
                                    <input type="date" id="saledate" name="created_at" class="form-control"
                                        max="{{ now('Africa/Dar_es_Salaam')->toDateString() }}" required>
                                </div>
                            </div>
                   
                            <div class="row mb-3">
                                    <label for="category" class="col-md-4 col-form-label text-md-end">Product Name</label>
                                    <div class="col-md-8" >
                                            <input type="text" class="form-control" id="name" readonly>
                                    </div>
                            </div>
                            <div class="row mb-3">
                                <label for="category" class="col-md-4 col-form-label text-md-end">Quantity<span style="color: red;">*</span></label>
                                <div class="col-md-8">
                                        <input type="text" class="form-control" id="quantity" name="quantity" maxlength="50" minlength="1"
                                            value="{{ old('quantity') }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="category" class="col-md-4 col-form-label text-md-end">Unit Cost<span style="color: red;">*</span></label>
                                <div class="col-md-8">
                                        <input type="text" class="form-control" id="selling_price" name="selling_price" maxlength="50" minlength="3"
                                            value="{{ old('selling_price') }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                    <label for="category" class="col-md-4 col-form-label text-md-end">Buying Price<span style="color: red;">*</span></label>
                                    <div class="col-md-8">
                                            <input type="text" class="form-control" id="buying_price" name="buying_price" maxlength="50" minlength="1"
                                                value="{{ old('buying_price') }}" required>
                                    </div>
                            </div>
                
                            <div class="row mb-3">
                                <label for="expense_description"
                                    class="col-md-4 col-form-label text-md-end">Remarks<span style="color: red;">*</span></label>
                                <div class="col-md-8">
                                    <textarea class="form-control" name="remarks" rows="3" minlength="5"  maxlength="200" required></textarea>
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
