<div class="modal fade" id="deletePartner" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete partner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('partnership.partners.destroy', 'id') }}" method="post">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <div id="message"></div>
                    <input type="hidden" id="id" name="id" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-danger btn-sm">Yes, delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
