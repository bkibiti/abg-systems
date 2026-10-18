{{-- Shared contribution fields. Expects $partners, optional $selectedPartnerId, $suffix (for unique ids when the form appears more than once on a page). --}}
@php
    $suffix = $suffix ?? '';
    $selectedPartnerId = $selectedPartnerId ?? null;
@endphp

<div class="row mb-3">
    <label for="partner_id{{ $suffix }}" class="col-md-4 col-form-label text-md-end">Partner<span class="text-danger">*</span></label>
    <div class="col-md-8">
        <select name="partner_id" id="partner_id{{ $suffix }}" class="form-select" required>
            <option value="" disabled @selected(! $selectedPartnerId)>Select partner</option>
            @foreach ($partners as $p)
                <option value="{{ $p->id }}" @selected((string) $selectedPartnerId === (string) $p->id)>{{ $p->name }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="row mb-3">
    <label class="col-md-4 col-form-label text-md-end">Contribution for<span class="text-danger">*</span></label>
    <div class="col-md-8">
        <div class="pos-pay-group">
            <input type="radio" class="btn-check" name="type" id="type_investment{{ $suffix }}" value="Investment" autocomplete="off" checked>
            <label class="btn pos-pay-btn" for="type_investment{{ $suffix }}">Investment</label>
            <input type="radio" class="btn-check" name="type" id="type_operations{{ $suffix }}" value="Operations" autocomplete="off">
            <label class="btn pos-pay-btn" for="type_operations{{ $suffix }}">Operations</label>
        </div>
    </div>
</div>

<div class="row mb-3">
    <label for="contribution_date{{ $suffix }}" class="col-md-4 col-form-label text-md-end">Date<span class="text-danger">*</span></label>
    <div class="col-md-8">
        <input type="date" name="contribution_date" id="contribution_date{{ $suffix }}" class="form-control"
            value="{{ now('Africa/Dar_es_Salaam')->toDateString() }}" max="{{ now('Africa/Dar_es_Salaam')->toDateString() }}" required>
    </div>
</div>

<div class="row mb-3">
    <label for="amount{{ $suffix }}" class="col-md-4 col-form-label text-md-end">Amount (TZS)<span class="text-danger">*</span></label>
    <div class="col-md-8">
        <input type="number" step="0.01" min="1" name="amount" id="amount{{ $suffix }}" class="form-control" required>
    </div>
</div>

<div class="row mb-3">
    <label for="description{{ $suffix }}" class="col-md-4 col-form-label text-md-end">Description</label>
    <div class="col-md-8">
        <textarea name="description" id="description{{ $suffix }}" class="form-control" rows="2" maxlength="255"></textarea>
    </div>
</div>
