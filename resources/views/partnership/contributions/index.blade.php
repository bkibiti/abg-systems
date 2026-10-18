@extends("layouts.master")

@section('content-title')
Contributions
@endsection

@section('content-sub-title')
<li class="breadcrumb-item"><a href="{{route('home')}}"><i class="bi bi-house"></i></a></li>
<li class="breadcrumb-item"><a href="{{ route('partnership.index') }}">Partnership</a></li>
<li class="breadcrumb-item"><a href="#">Contributions</a></li>
@endsection

@section("content")

<div class="col-md-6 mb-4">
    <div class="card staff-figure-card h-100">
        <div class="card-body">
            <div class="staff-figure-label"><span class="staff-dot" style="background-color: #5B6FB5"></span>Investment</div>
            <div class="staff-figure-amount">TZS {{ formatAmount($investmentTotal) }}</div>
            <div class="staff-card-subtitle mt-1">Decides ownership shares</div>
        </div>
    </div>
</div>

<div class="col-md-6 mb-4">
    <div class="card staff-figure-card h-100">
        <div class="card-body">
            <div class="staff-figure-label"><span class="staff-dot" style="background-color: #E0874E"></span>Operations</div>
            <div class="staff-figure-amount">TZS {{ formatAmount($operationsTotal) }}</div>
            <div class="staff-card-subtitle mt-1">Shown separately, not in shares</div>
        </div>
    </div>
</div>

<div class="col-sm-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Contributions</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('partnership.contributions.index') }}" method="GET">
                <div class="row g-2 g-md-3 mb-3">
                    <div class="col-12 col-md-3">
                        <label for="filter_partner" class="form-label history-filter-label">Partner</label>
                        <select name="partner_id" id="filter_partner" class="form-select">
                            <option value="">All partners</option>
                            @foreach ($partners as $p)
                                <option value="{{ $p->id }}" @selected(request('partner_id') == $p->id)>{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <label for="filter_type" class="form-label history-filter-label">Type</label>
                        <select name="type" id="filter_type" class="form-select">
                            <option value="">All types</option>
                            <option value="Investment" @selected(request('type') === 'Investment')>Investment</option>
                            <option value="Operations" @selected(request('type') === 'Operations')>Operations</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        <label for="from_date" class="form-label history-filter-label">From</label>
                        <input type="date" name="from_date" id="from_date" class="form-control history-filter-input" value="{{ request('from_date') }}">
                    </div>
                    <div class="col-6 col-md-2">
                        <label for="to_date" class="form-label history-filter-label">To</label>
                        <input type="date" name="to_date" id="to_date" class="form-control history-filter-input" value="{{ request('to_date') }}">
                    </div>
                    <div class="col-12 col-md-auto d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary history-filter-btn flex-grow-1 flex-md-grow-0">Filter</button>
                        <a href="{{ route('partnership.contributions.index') }}" class="btn btn-secondary history-filter-btn flex-grow-1 flex-md-grow-0">Reset</a>
                    </div>
                </div>
            </form>
            <hr>
            <div class="table-responsive">
                <table class="table nowrap table-striped table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Partner</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th class="text-end">Amount</th>
                            @can('Manage Partnership')
                                <th>Action</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($contributions as $contribution)
                            <tr>
                                <td>{{ $contribution->contribution_date->format('j M Y') }}</td>
                                <td>
                                    <a href="{{ route('partnership.partners.show', $contribution->partner_id) }}">{{ $contribution->partner->name }}</a>
                                </td>
                                <td>
                                    @if ($contribution->type === \App\Models\PartnerContribution::TYPE_INVESTMENT)
                                        <span class="badge-investment">Investment</span>
                                    @else
                                        <span class="badge-operations">Operations</span>
                                    @endif
                                </td>
                                <td>{{ $contribution->description }}</td>
                                <td class="text-end">{{ formatAmount($contribution->amount) }}</td>
                                @can('Manage Partnership')
                                    <td class="text-nowrap">
                                        <button type="button" class="btn btn-sm btn-outline-secondary" title="Edit" data-bs-toggle="modal" data-bs-target="#editContribution"
                                            data-id="{{ $contribution->id }}" data-partner_id="{{ $contribution->partner_id }}" data-type="{{ $contribution->type }}"
                                            data-contribution_date="{{ $contribution->contribution_date->format('Y-m-d') }}" data-amount="{{ $contribution->amount }}"
                                            data-description="{{ $contribution->description }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" title="Delete" data-bs-toggle="modal" data-bs-target="#deleteContribution"
                                            data-id="{{ $contribution->id }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                @endcan
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-body-secondary">No contributions match these filters.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4">Total</th>
                            <th class="text-end">{{ formatAmount($investmentTotal + $operationsTotal) }}</th>
                            @can('Manage Partnership')
                                <th></th>
                            @endcan
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

@can('Manage Partnership')
    @include('partnership.contributions.edit', ['partners' => $partners])
    @include('partnership.contributions.delete')
@endcan

@endsection

@push("page_scripts")
@include('partials.notification')
<script>
    document.title = document.title.concat(" | Contributions");

    $('#editContribution').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var modal = $(this);
        modal.find('#id').val(button.data('id'));
        modal.find('#partner_id_edit').val(button.data('partner_id'));
        modal.find('#type_investment_edit').prop('checked', button.data('type') === 'Investment');
        modal.find('#type_operations_edit').prop('checked', button.data('type') === 'Operations');
        modal.find('#contribution_date_edit').val(button.data('contribution_date'));
        modal.find('#amount_edit').val(button.data('amount'));
        modal.find('#description_edit').val(button.data('description'));
    });

    $('#deleteContribution').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var modal = $(this);
        modal.find('#message').text('Are you sure you want to delete this contribution?');
        modal.find('#id').val(button.data('id'));
    });
</script>
@endpush
