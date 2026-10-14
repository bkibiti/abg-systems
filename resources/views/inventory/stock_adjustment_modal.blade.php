<div class="modal fade" id="adjust" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Stock Adjustment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="panel-body">
                        <form id="form_product_edit" method="POST" action="{{route('stock-adjustment.store')}}" >
                            @csrf

                            <input type="hidden" name="id" id="id">

                            <div class="form-group row">
                                    <label for="category" class="col-md-4 col-form-label text-md-right">Product Name</label>
                                    <div class="col-md-8" >
                                            <input type="text" class="form-control" id="name" readonly>
                                    </div>
                            </div>
                            <div class="form-group row">
                                <label for="category" class="col-md-4 col-form-label text-md-right">Quantity on Hand</label>
                                <div class="col-md-8">
                                        <input type="text" class="form-control" id="quantity" name="quantity" readonly>
                                </div>
                            </div>
                                <div class="form-group row">
                                        <label for="category" class="col-md-4 col-form-label text-md-right" >Adjustment Type <font color="red">*</font></label>
                                        <div class="col-md-8">
                                            <select class="form-control select2"  class="form-control" id="type" name="type"  required data-width="100%">
                                                <option value="" name="category">Select</option>
                                                <option value="Positive" name="category">Positive (+)</option>
                                                <option value="Negative" name="category">Negative (-)</option>
                                        </select>
                                        </div>
                                </div>
                  
                                <div class="form-group row">
                                        <label for="category" class="col-md-4 col-form-label text-md-right" > <span id='qntyTxt'>Quantity</span>  <font color="red">*</font></label>
                                        <div class="col-md-4">
                                                <input type="number" min="1" class="form-control" id="qnty" name="qnty" required>
                                        </div>
                                        
                                        <label class="col-md-4 col-form-label text-md-left" id = 'newqty' style="color:blue"></label>
                                    
                                </div>
                                <div class="form-group row">
                                    <label for="category" class="col-md-4 col-form-label text-md-right">Reason<font color="red">*</font></label>
                                    <div class="col-md-8">
                                            <textarea class="form-control" id="reason" name="reason" rows="2" required></textarea>
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
