{{-- Populated via JS on show.bs.modal. --}}
<div class="modal fade" id="editPartner" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-md modal-fullscreen-sm-down" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit partner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('partnership.partners.update', 'id') }}" method="post">
                @csrf
                @method('PUT')
                <input type="hidden" id="partner_id_edit" name="id" value="">
                <div class="modal-body">
                    <div class="row mb-3">
                        <label for="name_edit" class="col-md-4 col-form-label text-md-end">Name<span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <input type="text" id="name_edit" name="name" class="form-control" maxlength="100" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="phone_edit" class="col-md-4 col-form-label text-md-end">Phone</label>
                        <div class="col-md-8">
                            <input type="text" id="phone_edit" name="phone" class="form-control" maxlength="45">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="email_edit" class="col-md-4 col-form-label text-md-end">Email</label>
                        <div class="col-md-8">
                            <input type="email" id="email_edit" name="email" class="form-control" maxlength="100">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="joined_on_edit" class="col-md-4 col-form-label text-md-end">Joined on</label>
                        <div class="col-md-8">
                            <input type="date" id="joined_on_edit" name="joined_on" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="notes_edit" class="col-md-4 col-form-label text-md-end">Notes</label>
                        <div class="col-md-8">
                            <textarea id="notes_edit" name="notes" class="form-control" rows="2"></textarea>
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
