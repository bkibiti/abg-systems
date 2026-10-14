
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Adjustment Reason</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('adjustment-reasons.store') }}" method="post">
                @csrf()
                <div class="modal-body">
                    <div class="row mb-3">
                        <label for="code" class="col-md-4 col-form-label text-md-end">Reason <font
                                color="red">*</font></label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="reason" name="reason"
                                   aria-describedby="emailHelp" required maxlength="30">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>


        </div>
    </div>
</div>
