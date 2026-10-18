<div class="modal fade" id="createPartner" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-md modal-fullscreen-sm-down" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add partner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('partnership.partners.store') }}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">
                        <label for="name" class="col-md-4 col-form-label text-md-end">Name<span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <input type="text" id="name" name="name" class="form-control" maxlength="100" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="phone" class="col-md-4 col-form-label text-md-end">Phone</label>
                        <div class="col-md-8">
                            <input type="text" id="phone" name="phone" class="form-control" maxlength="45">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="email" class="col-md-4 col-form-label text-md-end">Email</label>
                        <div class="col-md-8">
                            <input type="email" id="email" name="email" class="form-control" maxlength="100">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="joined_on" class="col-md-4 col-form-label text-md-end">Joined on</label>
                        <div class="col-md-8">
                            <input type="date" id="joined_on" name="joined_on" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="notes" class="col-md-4 col-form-label text-md-end">Notes</label>
                        <div class="col-md-8">
                            <textarea id="notes" name="notes" class="form-control" rows="2"></textarea>
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
