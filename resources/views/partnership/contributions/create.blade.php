{{-- Add contribution modal. Expects $partners and optional $selectedPartnerId, $modalId, $suffix. --}}
@php
    $modalId = $modalId ?? 'createContribution';
@endphp
<div class="modal fade" id="{{ $modalId }}" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-md modal-fullscreen-sm-down" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add contribution</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('partnership.contributions.store') }}" method="post">
                @csrf
                <div class="modal-body">
                    @include('partnership.contributions._fields', ['partners' => $partners, 'selectedPartnerId' => $selectedPartnerId ?? null, 'suffix' => $suffix ?? ''])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save contribution</button>
                </div>
            </form>
        </div>
    </div>
</div>
