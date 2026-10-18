{{-- Edit contribution modal. Expects $partners. Populated via JS on show.bs.modal. --}}
<div class="modal fade" id="editContribution" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-md modal-fullscreen-sm-down" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit contribution</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('partnership.contributions.update', 'id') }}" method="post">
                @csrf
                @method('PUT')
                <input type="hidden" id="id" name="id" value="">
                <div class="modal-body">
                    @include('partnership.contributions._fields', ['partners' => $partners, 'suffix' => '_edit'])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save contribution</button>
                </div>
            </form>
        </div>
    </div>
</div>
