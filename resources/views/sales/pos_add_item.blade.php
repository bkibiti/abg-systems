<div class="modal fade" id="addItemsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sold Quantity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <div>
                        <form id="addItemForm" >

                            <input type="hidden" name="id" id="id">
                            <input type="hidden" name="name" id="name">
                            <input type="hidden" name="quantity" id="quantity">
                            <input type="hidden" name="unit_cost" id="unit_cost">
                            <input type="hidden" name="sale_price_1" id="sale_price_1">

                            <div class="row mb-3">
                                    <label for="category" class="col-md-4 col-form-label text-md-end">Sold Quantity</label>
                                    <div class="col-md-8">
                                            <input type="number" class="form-control" id="sold_quantity" name="sold_quantity" required autofocus>
                                    </div>
                                  
                            </div>
                            <div class="row mb-3">
                         
                                <label for="category" class="col-md-4 col-form-label text-md-end">Price</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="sale_price" name="sale_price" required>
                                </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" >Add</button>
                        </div>
                    </form>
                </div>
            </div>


        </div>
    </div>
</div>
