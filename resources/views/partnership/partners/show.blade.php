@extends("layouts.master")

@section('content-title')
{{ $partner->name }}
@endsection

@section('content-sub-title')
<li class="breadcrumb-item"><a href="{{route('home')}}"><i class="bi bi-house"></i></a></li>
<li class="breadcrumb-item"><a href="{{ route('partnership.index') }}">Partnership / Partners &amp; shares</a></li>
<li class="breadcrumb-item"><a href="#">{{ $partner->name }}</a></li>
@endsection

@section("content")

@php
    $investmentTotal = (float) ($partner->investment_total ?? 0);
    $operationsTotal = (float) ($partner->operations_total ?? 0);
    $totalContributedByPartner = $investmentTotal + $operationsTotal;
@endphp

<div class="col-12 mb-4">
    <div class="card">
        <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <span class="partner-avatar partner-avatar-lg" style="background-color: {{ partnerColor($partner->id) }}">{{ initials($partner->name) }}</span>
                <div>
                    <h4 class="mb-1">{{ $partner->name }}</h4>
                    <div class="staff-card-subtitle">
                        {{ collect([$partner->phone, $partner->email, $partner->joined_on ? 'Partner since '.$partner->joined_on->format('j M Y') : null])->filter()->implode(' · ') }}
                    </div>
                </div>
            </div>
            @can('Manage Partnership')
                <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#editPartner"
                    data-id="{{ $partner->id }}" data-name="{{ $partner->name }}" data-phone="{{ $partner->phone }}"
                    data-email="{{ $partner->email }}" data-joined_on="{{ optional($partner->joined_on)->format('Y-m-d') }}" data-notes="{{ $partner->notes }}">
                    <i class="bi bi-pencil"></i> Edit partner
                </button>
            @endcan
        </div>
    </div>
</div>

<div class="col-md-3 mb-4">
    <div class="card staff-figure-card h-100">
        <div class="card-body">
            <div class="staff-figure-label"><span class="staff-dot" style="background-color: #5B6FB5"></span>Investment</div>
            <div class="staff-figure-amount">TZS {{ formatAmount($investmentTotal) }}</div>
        </div>
    </div>
</div>

<div class="col-md-3 mb-4">
    <div class="card staff-figure-card h-100">
        <div class="card-body">
            <div class="staff-figure-label"><span class="staff-dot" style="background-color: #E0874E"></span>Operations</div>
            <div class="staff-figure-amount">TZS {{ formatAmount($operationsTotal) }}</div>
        </div>
    </div>
</div>

<div class="col-md-3 mb-4">
    <div class="card staff-figure-card h-100">
        <div class="card-body">
            <div class="staff-figure-label">Total contributed</div>
            <div class="staff-figure-amount">TZS {{ formatAmount($totalContributedByPartner) }}</div>
        </div>
    </div>
</div>

<div class="col-md-3 mb-4">
    <div class="card partnership-share-card h-100">
        <div class="card-body">
            <div class="partnership-share-sub">Share</div>
            @if ($share === null)
                <div class="partnership-share-value">&mdash;</div>
            @else
                <div class="partnership-share-value">{{ number_format($share, 1) }}%</div>
                <div class="partnership-share-sub">of TZS {{ formatAmount($totalInvestment) }} investment</div>
            @endif
        </div>
    </div>
</div>

<div class="col-lg-8 mb-4">
    <div class="card h-100">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h5 class="card-title mb-0">Contributions</h5>
            <div class="btn-group" role="group" aria-label="Filter by type">
                <button type="button" class="btn btn-sm btn-outline-secondary active" data-contribution-filter="All">All</button>
                <button type="button" class="btn btn-sm btn-outline-secondary" data-contribution-filter="Investment">Investment</button>
                <button type="button" class="btn btn-sm btn-outline-secondary" data-contribution-filter="Operations">Operations</button>
            </div>
        </div>
        <div class="card-body">
            @if ($contributions->isEmpty())
                <p class="text-body-secondary mb-0">No contributions recorded yet.</p>
            @else
                <div class="table-responsive">
                    <table class="table nowrap table-striped table-hover" id="partnerContributionsTable" style="width:100%">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Description</th>
                                <th class="text-end">Amount</th>
                                @can('Manage Partnership')
                                    <th>Action</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($contributions as $contribution)
                                <tr data-type="{{ $contribution->type }}" data-amount="{{ $contribution->amount }}">
                                    <td>{{ $contribution->contribution_date->format('j M Y') }}</td>
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
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3">Total</th>
                                <th class="text-end" id="partnerContributionsTotal">{{ formatAmount($totalContributedByPartner) }}</th>
                                @can('Manage Partnership')
                                    <th></th>
                                @endcan
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

@can('Manage Partnership')
    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Add contribution</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('partnership.contributions.store') }}" method="post">
                    @csrf
                    @include('partnership.contributions._fields', ['partners' => $partners, 'selectedPartnerId' => $partner->id, 'suffix' => '_partner'])
                    <button type="submit" class="btn btn-primary w-100">Save contribution</button>
                </form>
            </div>
        </div>
    </div>

    @include('partnership.partners.edit')
    @include('partnership.contributions.edit', ['partners' => $partners])
    @include('partnership.contributions.delete')
@endcan

@endsection

@push("page_scripts")
@include('partials.notification')
<script>
    document.title = document.title.concat(" | ").concat(@json($partner->name));

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

    // All / Investment / Operations filter for the contributions table (client-side only)
    (function () {
        var buttons = document.querySelectorAll('[data-contribution-filter]');
        var rows = document.querySelectorAll('#partnerContributionsTable tbody tr');
        var totalCell = document.getElementById('partnerContributionsTotal');

        buttons.forEach(function (button) {
            button.addEventListener('click', function () {
                buttons.forEach(function (b) { b.classList.remove('active'); });
                button.classList.add('active');

                var filter = button.getAttribute('data-contribution-filter');
                var total = 0;

                rows.forEach(function (row) {
                    var matches = filter === 'All' || row.getAttribute('data-type') === filter;
                    row.style.display = matches ? '' : 'none';
                    if (matches) {
                        total += parseFloat(row.getAttribute('data-amount')) || 0;
                    }
                });

                if (totalCell) {
                    totalCell.textContent = Math.round(total).toLocaleString();
                }
            });
        });
    })();
</script>
@endpush
