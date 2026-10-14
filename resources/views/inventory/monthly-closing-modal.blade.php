<div class="modal fade" id="create" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Month End Closing Stock</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="panel-body">
                        <form id="form_product_edit" method="POST" action="{{route('monthly-closing-stock.store')}}" >
                            @csrf

                                <div class="form-group row">
                                        <label for="category" class="col-md-4 col-form-label text-md-right">Month <font color="red">*</font></label>
                                        <div class="col-md-8">
                                            <select class="form-control select2"  class="form-control" id="month" name="month"  required data-width="100%">
                                                <option value="">Select Month</option>
                                                <option value="1" {{ (old('month')==1 ? "selected":"") }} >1</option>
                                                <option value="2" {{ (old('month')==2 ? "selected":"") }} >2</option>
                                                <option value="3" {{ (old('month')==3 ? "selected":"") }} >3</option>
                                                <option value="4" {{ (old('month')==4 ? "selected":"") }} >4</option>
                                                <option value="5" {{ (old('month')==5 ? "selected":"") }} >5</option>
                                                <option value="6" {{ (old('month')==6 ? "selected":"") }} >6</option>
                                                <option value="7" {{ (old('month')==7 ? "selected":"") }} >7</option>
                                                <option value="8" {{ (old('month')==8 ? "selected":"") }} >8</option>
                                                <option value="9" {{ (old('month')==9 ? "selected":"") }} >9</option>
                                                <option value="10" {{ (old('month')==10 ? "selected":"") }}>10</option>
                                                <option value="11" {{ (old('month')==11 ? "selected":"") }}>11</option>
                                                <option value="12" {{ (old('month')==12 ? "selected":"") }}>12</option>
                                        </select>
                                        </div>
                                </div>
                                <div class="form-group row">
                                    <label for="category" class="col-md-4 col-form-label text-md-right">Year <font color="red">*</font></label>
                                    <div class="col-md-8">
                                        <select class="form-control select2"  class="form-control" id="year" name="year"  required data-width="100%">
                                            <option value="">Select Year</option>
                                            <option value="2020" {{ (old('year')==2020 ? "selected":"") }} >2020</option>
                                            <option value="2021" {{ (old('year')==2021 ? "selected":"") }} >2021</option>
                                    </select>
                                    </div>
                            </div>
          
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Generate & Save Stock</button>
                        </div>
                    </form>
                </div>
            </div>


        </div>
    </div>
</div>
