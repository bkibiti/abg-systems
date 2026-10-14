<div class="modal fade" id="edit" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Purchase</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="panel-body">
                        <form id="form_product_edit" method="POST" action="{{route('goods-receiving.update')}}" >
                            @csrf
                        

                            <input type="hidden" name="id" id="id">
                       
                            <div class="form-group row">
                                <label  class="col-md-4 col-form-label text-md-right">Supplier<span style="color: red;">*</span></label>
                                <div class="col-md-8">
                                    <select class="form-control select2"  class="form-control" id="supplier_id" name="supplier_id" data-width="100%">
                                        @foreach($suppliers as $supp)
                                            <option value="{{ $supp->id }}">{{ $supp->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>

                            <div class="form-group row">
                                <label  class="col-md-4 col-form-label text-md-right">Purchase Date<span style="color: red;">*</span></label>
                                <div class="col-md-8">
                                    <div style="border: 2px solid white; border-radius: 6px;">
                                        <input type="text" id="created_at" name="created_at" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                   
                            <div class="form-group row">
                                    <label for="category" class="col-md-4 col-form-label text-md-right">Product Name</label>
                                    <div class="col-md-8" >
                                            <input type="text" class="form-control" id="name" readonly>
                                    </div>
                            </div>
                            <div class="form-group row">
                                <label for="category" class="col-md-4 col-form-label text-md-right">Quantity<span style="color: red;">*</span></label>
                                <div class="col-md-8">
                                        <input type="text" class="form-control" id="quantity" name="quantity" maxlength="50" minlength="1"
                                            value="{{ old('quantity') }}" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="category" class="col-md-4 col-form-label text-md-right">Unit Cost<span style="color: red;">*</span></label>
                                <div class="col-md-8">
                                        <input type="text" class="form-control" id="unit_cost" name="unit_cost" maxlength="50" minlength="3"
                                            value="{{ old('unit_cost') }}" required>
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
