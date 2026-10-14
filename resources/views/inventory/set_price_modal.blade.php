<div class="modal fade" id="edit" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Selling Price</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="panel-body">
                        <form id="form_product_edit" method="POST" action="{{route('current-stock.update','id')}}" >
                            @csrf
                            @method("PUT")

                            <input type="hidden" name="id" id="id">

                            <div class="form-group row">
                                    <label for="category" class="col-md-4 col-form-label text-md-right">Product Name</label>
                                    <div class="col-md-8" >
                                            <input type="text" class="form-control" id="name" readonly>
                                    </div>
                            </div>
                            <div class="form-group row">
                                <label for="category" class="col-md-4 col-form-label text-md-right">Unit Cost</label>
                                <div class="col-md-8">
                                        <input type="text" class="form-control" id="unit_cost" name="unit_cost" maxlength="50" minlength="3"
                                            value="{{ old('unit_cost') }}" required>
                                </div>
                            </div>

                            @if ($PriceCategory[0]->active =='Yes')
                                <div class="form-group row">
                                        <label for="category" class="col-md-4 col-form-label text-md-right">{{$PriceCategory[0]->description}}</label>
                                        <div class="col-md-8">
                                                <input type="text" class="form-control" id="sale_price_1" name="sale_price_1" maxlength="50" minlength="3"
                                                    value="{{ old('sale_price_1') }}" required>
                                        </div>
                                </div>
                            @endif
                            @if ($PriceCategory[1]->active =='Yes')
                                <div class="form-group row">
                                        <label for="category" class="col-md-4 col-form-label text-md-right">{{$PriceCategory[1]->description}} Price</label>
                                        <div class="col-md-8">
                                                <input type="text" class="form-control" id="sale_price_2" name="sale_price_2" maxlength="50" minlength="3"
                                                    value="{{ old('sale_price_2') }}" required>
                                        </div>
                                </div>
                            @endif
                            @if ($PriceCategory[2]->active =='Yes')
                                <div class="form-group row">
                                        <label for="category" class="col-md-4 col-form-label text-md-right">{{$PriceCategory[2]->description}} Price</label>
                                        <div class="col-md-8">
                                                <input type="text" class="form-control" id="sale_price_3" name="sale_price_3" maxlength="50" minlength="3"
                                                    value="{{ old('sale_price_3') }}" required>
                                        </div>
                                </div>
                            @endif

                        


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
