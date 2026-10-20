@extends("layouts.master")

@section('content-title')
Overview
@endsection

@section('content-sub-title')
<li class="breadcrumb-item"><a href="{{route('home')}}"><i class="bi bi-house"></i></a></li>
<li class="breadcrumb-item"><a href="#">Partnership / Overview</a></li>
@endsection

@section("content")

<div class="col-12 mb-4 d-flex flex-wrap justify-content-between align-items-start gap-2">
    <div class="staff-card-subtitle">All contributions to date</div>
    @can('Manage Partnership')
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#createPartner"><i class="bi bi-plus-lg"></i> Add partner</button>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createContribution"><i class="bi bi-plus-lg"></i> Add contribution</button>
        </div>
    @endcan
</div>

<div class="col-md-4 mb-4">
    <div class="card staff-figure-card h-100">
        <div class="card-body">
            <div class="staff-figure-label">Total contributed</div>
            <div class="staff-figure-amount">TZS {{ formatAmount($totalContributed) }}</div>
            <div class="staff-card-subtitle mt-1">{{ $partners->count() }} partners &middot; {{ $totalContributions }} contributions</div>
        </div>
    </div>
</div>

<div class="col-md-4 mb-4">
    <div class="card staff-figure-card h-100">
        <div class="card-body">
            <div class="staff-figure-label"><span class="staff-dot" style="background-color: #5B6FB5"></span>Investment</div>
            <div class="staff-figure-amount">TZS {{ formatAmount($totalInvestment) }}</div>
            <div class="staff-card-subtitle mt-1">Equipments, Workshop, etc</div>
        </div>
    </div>
</div>

<div class="col-md-4 mb-4">
    <div class="card staff-figure-card h-100">
        <div class="card-body">
            <div class="staff-figure-label"><span class="staff-dot" style="background-color: #E0874E"></span>Operations</div>
            <div class="staff-figure-amount">TZS {{ formatAmount($totalOperations) }}</div>
            <div class="staff-card-subtitle mt-1">Not in investments</div>
        </div>
    </div>
</div>

<div class="col-12 mb-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="card-title mb-0">Contributions percentage</h5>
            @if ($totalInvestment > 0)
                <div class="staff-card-subtitle">Based on investment &middot; TZS {{ formatAmount($totalInvestment) }}</div>
            @endif
        </div>
        <div class="card-body">
            @if ($totalInvestment <= 0)
                <p class="text-body-secondary mb-0">No investment contributions yet, so shares can't be calculated.</p>
            @else
                @php
                    // $partners is already sorted by share, highest first; filtering keeps that order.
                    $barPartners = $partners->filter(fn ($p) => (float) $p->investment_total > 0);
                    $ariaLabel = $barPartners->map(fn ($p) => $p->name.' '.number_format($p->share, 1).'%')->implode(', ');
                @endphp
                <div class="share-bar" role="img" aria-label="Ownership shares: {{ $ariaLabel }}">
                    @foreach ($barPartners as $partner)
                        <div class="share-seg" style="width: {{ $partner->share }}%; background-color: {{ partnerColor($partner->id) }}"
                            title="{{ $partner->name }} &middot; {{ number_format($partner->share, 1) }}%"></div>
                    @endforeach
                </div>
                <div class="share-legend">
                    @foreach ($barPartners as $partner)
                        <div class="share-legend-item">
                            <span class="share-legend-swatch" style="background-color: {{ partnerColor($partner->id) }}"></span>
                            <span class="share-legend-name">{{ $partner->name }}</span>
                            <span class="share-legend-percent">{{ number_format($partner->share, 1) }}%</span>
                            <span class="share-legend-sub">TZS {{ formatAmount($partner->investment_total) }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>


<div class="col-12 mb-4">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Partners</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table nowrap table-striped table-hover partners-table">
                    <thead>
                        <tr>
                            <th>Partner</th>
                            <th>Investment</th>
                            <th>Operations</th>
                            <th>Total contributed</th>
                            <th>Equal share</th>
                            <th>Balance</th>
                            <th>View</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($partners as $partner)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="partner-avatar" style="background-color: {{ partnerColor($partner->id) }}">{{ initials($partner->name) }}</span>
                                        <div>
                                            <div class="fw-semibold">{{ $partner->name }}</div>
                                            @if ($partner->phone)
                                                <div class="staff-card-subtitle">{{ $partner->phone }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ formatAmount($partner->investment_total ?? 0) }}</td>
                                <td>{{ formatAmount($partner->operations_total ?? 0) }}</td>
                                <td class="fw-bold">{{ formatAmount(($partner->investment_total ?? 0) + ($partner->operations_total ?? 0)) }}</td>
                             
                                <td>
                                    @if ($equalShareCheck['equalShare'] === null)
                                        &mdash;
                                    @else
                                        {{ formatAmount($equalShareCheck['equalShare']) }}
                                    @endif
                                </td>
                                <td>
                                    @if ($partner->balance === null)
                                        &mdash;
                                    @elseif ($partner->balance < 0)
                                        <span class="badge-balance-add">Add {{ formatAmount(abs($partner->balance)) }}</span>
                                    @elseif ($partner->balance > 0)
                                        <span class="badge-balance-over">Over {{ formatAmount($partner->balance) }}</span>
                                    @else
                                        <span class="text-body-secondary">Equal</span>
                                    @endif
                                </td>
                                <td class="text-nowrap">
                                    <a href="{{ route('partnership.partners.show', $partner) }}" class="btn btn-sm btn-info">View</a>
                                    @can('Manage Partnership')
                                        <button type="button" class="btn btn-sm btn-outline-secondary" title="Edit partner" data-bs-toggle="modal" data-bs-target="#editPartner"
                                            data-id="{{ $partner->id }}" data-name="{{ $partner->name }}" data-phone="{{ $partner->phone }}"
                                            data-email="{{ $partner->email }}" data-joined_on="{{ optional($partner->joined_on)->format('Y-m-d') }}" data-notes="{{ $partner->notes }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" title="Delete partner" data-bs-toggle="modal" data-bs-target="#deletePartner"
                                            data-id="{{ $partner->id }}" data-name="{{ $partner->name }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Total</th>
                            <th>{{ formatAmount($totalInvestment) }}</th>
                            <th>{{ formatAmount($totalOperations) }}</th>
                            <th>{{ formatAmount($totalContributed) }}</th>
                            <th>{{ $totalInvestment > 0 ? '100.0%' : '—' }}</th>
                            <th>{{ $equalShareCheck['equalShare'] === null ? '—' : formatAmount($equalShareCheck['equalShare']) }}</th>
                            <th>{{ $equalShareCheck['equalShare'] === null ? '—' : formatAmount(0) }}</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

@can('Manage Partnership')
    @include('partnership.partners.create')
    @include('partnership.partners.edit')
    @include('partnership.partners.delete')
    @include('partnership.contributions.create', ['partners' => $partners, 'modalId' => 'createContribution', 'suffix' => '_ov'])
@endcan

@endsection

@push("page_scripts")
@include('partials.notification')
<script>
    document.title = document.title.concat(" | Partnership");

    $('#editPartner').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var modal = $(this);
        modal.find('#partner_id_edit').val(button.data('id'));
        modal.find('#name_edit').val(button.data('name'));
        modal.find('#phone_edit').val(button.data('phone'));
        modal.find('#email_edit').val(button.data('email'));
        modal.find('#joined_on_edit').val(button.data('joined_on'));
        modal.find('#notes_edit').val(button.data('notes'));
    });

    $('#deletePartner').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var modal = $(this);
        modal.find('#message').text("Are you sure you want to delete '".concat(button.data('name'), "'? This only works if they have no contributions."));
        modal.find('#id').val(button.data('id'));
    });
</script>
@endpush
