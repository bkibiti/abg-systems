<div class="modal fade" id="adjust" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Stock Adjustment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <div>
                        <form id="form_product_edit" method="POST" action="{{route('stock-adjustment.store')}}" >
                            @csrf

                            <input type="hidden" name="id" id="id">

                            <div class="row mb-3">
                                    <label for="category" class="col-md-4 col-form-label text-md-end">Product Name</label>
                                    <div class="col-md-8" >
                                            <input type="text" class="form-control" id="name" readonly>
                                    </div>
                            </div>
                            <div class="row mb-3">
                                <label for="category" class="col-md-4 col-form-label text-md-end">Quantity on Hand</label>
                                <div class="col-md-8">
                                        <input type="text" class="form-control" id="quantity" name="quantity" readonly>
                                </div>
                            </div>
                                <div class="row mb-3">
                                        <label for="category" class="col-md-4 col-form-label text-md-end" >Adjustment Type <font color="red">*</font></label>
                                        <div class="col-md-8">
                                            <select class="form-select select2"  class="form-select" id="type" name="type"  required data-width="100%">
                                                <option value="" name="category">Select</option>
                                                <option value="Positive" name="category">Positive (+)</option>
                                                <option value="Negative" name="category">Negative (-)</option>
                                        </select>
                                        </div>
                                </div>
                  
                                <div class="row mb-3">
                                        <label for="category" class="col-md-4 col-form-label text-md-end" > <span id='qntyTxt'>Quantity</span>  <font color="red">*</font></label>
                                        <div class="col-md-4">
                                                <input type="number" min="1" class="form-control" id="qnty" name="qnty" required>
                                        </div>
                                        
                                        <label class="col-md-4 col-form-label text-md-start" id = 'newqty' style="color:blue"></label>
                                    
                                </div>
                                <div class="row mb-3">
                                    <label for="category" class="col-md-4 col-form-label text-md-end">Reason<font color="red">*</font></label>
                                    <div class="col-md-8">
                                            <textarea class="form-control" id="reason" name="reason" rows="2" required></textarea>
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
