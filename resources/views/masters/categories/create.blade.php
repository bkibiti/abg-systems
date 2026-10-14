<div class="modal fade" id="create" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Product Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('categories.store') }}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">
                        <label for="name_create" class="col-md-4 col-form-label text-md-end">Category Name <font color="red">*</font></label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="name_create" name="name" maxlength="100" required value="{{ old('name') }}">
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
