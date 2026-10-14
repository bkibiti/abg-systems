<div class="modal fade" id="addItemsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sold Quantity</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="panel-body">
                        <form id="addItemForm" >

                            <input type="hidden" name="id" id="id">
                            <input type="hidden" name="name" id="name">
                            <input type="hidden" name="quantity" id="quantity">
                            <input type="hidden" name="unit_cost" id="unit_cost">
                            <input type="hidden" name="sale_price_1" id="sale_price_1">

                            <div class="form-group row">
                                    <label for="category" class="col-md-4 col-form-label text-md-right">Sold Quantity</label>
                                    <div class="col-md-8">
                                            <input type="number" class="form-control" id="sold_quantity" name="sold_quantity" required autofocus>
                                    </div>
                                  
                            </div>
                            <div class="form-group row">
                         
                                <label for="category" class="col-md-4 col-form-label text-md-right">Price</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="sale_price" name="sale_price" required>
                                </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" >Add</button>
                        </div>
                    </form>
                </div>
            </div>


        </div>
    </div>
</div>
