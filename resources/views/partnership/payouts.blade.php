@extends("layouts.master")

@section('content-title')
Payouts
@endsection

@section('content-sub-title')
<li class="breadcrumb-item"><a href="{{route('home')}}"><i class="bi bi-house"></i></a></li>
<li class="breadcrumb-item"><a href="{{ route('partnership.index') }}">Partnership</a></li>
<li class="breadcrumb-item"><a href="#">Payouts</a></li>
@endsection

@section("content")


{{-- Summary cards --}}
<div class="col-md-4 mb-4">
    <div class="card payout-cash-card h-100">
        <div class="card-body">
            <div class="payout-cash-label">Cash available now</div>
            <div class="payout-cash-amount {{ $available < 0 ? 'payout-cash-amount-negative' : '' }}">
                {{ $available < 0 ? '-' : '' }}TZS {{ formatAmount(abs($available)) }}
            </div>
        
        </div>
    </div>
</div>

<div class="col-md-4 mb-4">
    <div class="card staff-figure-card h-100">
        <div class="card-body">
            <div class="staff-figure-label">Paid out, all time</div>
            <div class="staff-figure-amount">TZS {{ formatAmount($paidOutAllTime) }}</div>
            <div class="staff-card-subtitle mt-1">{{ $payoutsCount }} {{ Str::plural('payout', $payoutsCount) }} &middot; {{ $reversedCount }} reversed</div>
        </div>
    </div>
</div>

<div class="col-md-4 mb-4">
    <div class="card staff-figure-card h-100">
        <div class="card-body">
            <div class="staff-figure-label">Paid out, this year</div>
            <div class="staff-figure-amount">TZS {{ formatAmount($paidOutThisYear) }}</div>
        </div>
    </div>
</div>



{{-- Paid to each partner --}}
<div class="col-lg-5 mb-4">
    <div class="card h-100">
        <div class="card-header">
            <h5 class="card-title mb-0">Paid to each partner</h5>
        </div>
        <div class="card-body">
            @php $partnersByPaidOut = $partners->sortByDesc('paid_out_total')->values(); @endphp
            <table class="staff-table">
                <tbody>
                    @foreach ($partnersByPaidOut as $partner)
                        <tr>
                            <td>
                                <span class="partner-avatar" style="background-color: {{ partnerColor($partner->id) }}">{{ initials($partner->name) }}</span>
                                {{ $partner->name }}
                            </td>
                            <td class="amount-col">{{ formatAmount($partner->paid_out_total ?? 0) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>Total</th>
                        <th class="amount-col">{{ formatAmount($partners->sum('paid_out_total')) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

{{-- Payout history --}}
<div class="col-lg-7 mb-4">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Payout history</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('partnership.payouts.index') }}" method="GET">
                <div class="row g-2 g-md-3 mb-3">
                    <div class="col-12 col-md-4">
                        <label for="filter_partner" class="form-label history-filter-label">Partner</label>
                        <select name="partner_id" id="filter_partner" class="form-select">
                            <option value="">All partners</option>
                            @foreach ($partners as $p)
                                <option value="{{ $p->id }}" @selected(request('partner_id') == $p->id)>{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-3">
                        <label for="from_date" class="form-label history-filter-label">From</label>
                        <input type="date" name="from_date" id="from_date" class="form-control history-filter-input" value="{{ request('from_date') }}">
                    </div>
                    <div class="col-6 col-md-3">
                        <label for="to_date" class="form-label history-filter-label">To</label>
                        <input type="date" name="to_date" id="to_date" class="form-control history-filter-input" value="{{ request('to_date') }}">
                    </div>
                    <div class="col-12 col-md-auto d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary history-filter-btn flex-grow-1 flex-md-grow-0">Filter</button>
                        <a href="{{ route('partnership.payouts.index') }}" class="btn btn-secondary history-filter-btn flex-grow-1 flex-md-grow-0">Reset</a>
                    </div>
                </div>
            </form>
            <hr>
            <div class="table-responsive">
                <table class="table nowrap table-striped table-hover payout-history-table" style="width:100%">
                    <thead>
                        <tr>
                            <th>Payout no.</th>
                            <th>Date</th>
                            <th>Partner</th>
                            <th>Method · ref</th>
               
                            <th>Status</th>
                            <th class="text-end">Amount</th>
                            @can('Reverse payouts')
                                <th>Action</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($history as $payout)
                            <tr>
                                <td>{{ $payout->payout_no }}</td>
                                <td>{{ $payout->payout_date->format('j M Y') }}</td>
                                <td>
                                    <a href="{{ route('partnership.partners.show', $payout->partner_id) }}">{{ $payout->partner->name ?? '—' }}</a>
                                </td>
                                <td>{{ collect([$payout->paymentMethod->name ?? null, $payout->reference])->filter()->implode(' · ') ?: '—' }}</td>
                                <td>
                                    @if ($payout->status === \App\Models\PartnerPayout::STATUS_PAID)
                                        <span class="badge-paid">Paid</span>
                                    @else
                                        <span class="badge-reversed" title="{{ $payout->reversal_reason }}">Reversed</span>
                                        <div class="payout-reversal-reason">{{ $payout->reversal_reason }}</div>
                                    @endif
                                </td>
                                <td class="text-end {{ $payout->status === \App\Models\PartnerPayout::STATUS_REVERSED ? 'payout-amount-reversed' : '' }}">
                                    {{ formatAmount($payout->amount) }}
                                </td>
                                @can('Reverse payouts')
                                    <td class="text-nowrap">
                                        @if ($payout->status === \App\Models\PartnerPayout::STATUS_PAID)
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#reversePayout"
                                                data-id="{{ $payout->id }}" data-payout_no="{{ $payout->payout_no }}">Reverse</button>
                                        @endif
                                    </td>
                                @endcan
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-body-secondary">No payouts match these filters.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $history->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>


@can('Manage Partnership')
    {{-- New payout --}}
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">New payout</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('partnership.payouts.store') }}" method="post" id="payoutForm">
                    @csrf

                    <div class="row mb-3">
                        <label class="col-md-3 col-form-label text-md-end">Payout type</label>
                        <div class="col-md-9">
                            <div class="pos-pay-group">
                                <input type="radio" class="btn-check" name="mode" id="mode_split" value="split" autocomplete="off" @checked(old('mode', 'split') === 'split')>
                                <label class="btn pos-pay-btn" for="mode_split">Split by shares</label>
                                <input type="radio" class="btn-check" name="mode" id="mode_single" value="single" autocomplete="off" @checked(old('mode') === 'single')>
                                <label class="btn pos-pay-btn" for="mode_single">One partner</label>
                            </div>
                        </div>
                    </div>

                    <div id="payoutSplitSection">
                        <div class="row mb-3">
                            <label for="total_amount" class="col-md-3 col-form-label text-md-end">Total to pay out (TZS)<span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="number" step="1" min="1" name="total_amount" id="total_amount" class="form-control"
                                    value="{{ old('total_amount') }}">
                                <div class="payout-available-hint mt-1">Up to TZS {{ formatAmount(max($available, 0)) }} available</div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-9 offset-md-3">
                                <div class="table-responsive">
                                    <table class="table table-sm nowrap payout-split-table">
                                        <thead>
                                            <tr>
                                                <th>Partner</th>
                                                <th>Share</th>
                                                <th class="text-end">Amount (TZS)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($partners as $partner)
                                                <tr>
                                                    <td>
                                                        <span class="partner-avatar" style="width:28px;height:28px;min-width:28px;font-size:11px;background-color: {{ partnerColor($partner->id) }}">{{ initials($partner->name) }}</span>
                                                        {{ $partner->name }}
                                                    </td>
                                                    <td>{{ number_format($partner->share, 1) }}%</td>
                                                    <td class="text-end">
                                                        <input type="number" step="1" min="0" class="form-control form-control-sm split-amount-input"
                                                            name="amounts[{{ $partner->id }}]" data-partner-id="{{ $partner->id }}" data-share="{{ $partner->share }}"
                                                            value="{{ old('amounts.'.$partner->id, 0) }}">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="2">Total</th>
                                                <th class="text-end" id="splitSumCell">0</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="payout-available-hint">Amounts are split by equipment share and can be adjusted. The total must not exceed cash available.</div>
                            </div>
                        </div>
                    </div>

                    <div id="payoutSingleSection">
                        <div class="row mb-3">
                            <label for="single_partner_id" class="col-md-3 col-form-label text-md-end">Partner<span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select name="partner_id" id="single_partner_id" class="form-select">
                                    <option value="" disabled @selected(! old('partner_id'))>Select partner</option>
                                    @foreach ($partners as $partner)
                                        <option value="{{ $partner->id }}" @selected((string) old('partner_id') === (string) $partner->id)>{{ $partner->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="single_amount" class="col-md-3 col-form-label text-md-end">Amount (TZS)<span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="number" step="1" min="1" name="amount" id="single_amount" class="form-control" value="{{ old('amount') }}">
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="payout_date" class="col-md-3 col-form-label text-md-end">Payment date<span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <input type="date" name="payout_date" id="payout_date" class="form-control"
                                value="{{ old('payout_date', now('Africa/Dar_es_Salaam')->toDateString()) }}" max="{{ now('Africa/Dar_es_Salaam')->toDateString() }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="payment_method_id" class="col-md-3 col-form-label text-md-end">Payment method</label>
                        <div class="col-md-9">
                            <select name="payment_method_id" id="payment_method_id" class="form-select">
                                <option value="">Select method</option>
                                @foreach ($paymentMethods as $method)
                                    <option value="{{ $method->id }}" @selected((string) old('payment_method_id') === (string) $method->id)>{{ $method->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="reference" class="col-md-3 col-form-label text-md-end">Reference</label>
                        <div class="col-md-9">
                            <input type="text" name="reference" id="reference" class="form-control" maxlength="100"
                                placeholder="e.g. M-Pesa or bank ref" value="{{ old('reference') }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="note" class="col-md-3 col-form-label text-md-end">Note</label>
                        <div class="col-md-9">
                            <textarea name="note" id="note" class="form-control" rows="2" maxlength="255"
                                placeholder="e.g. October profit share">{{ old('note') }}</textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-9 offset-md-3">
                            <div id="payoutBlockedMessage" class="payout-blocked-message mb-2" style="display:none"></div>
                            <button type="submit" class="btn btn-primary pos-btn-lg" id="recordPayoutBtn">Record payout</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endcan

@can('Reverse payouts')
    <div class="modal fade" id="reversePayout" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reverse payout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('partnership.payouts.reverse', 'id') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="reverse_id" name="id" value="">
                        <p id="reverse_message"></p>
                        <div class="mb-3">
                            <label for="reversal_reason" class="form-label">Reason<span class="text-danger">*</span></label>
                            <textarea name="reversal_reason" id="reversal_reason" class="form-control" rows="2" maxlength="255" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger btn-sm">Yes, reverse</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endcan

@endsection

@push("page_scripts")
@include('partials.notification')
<script>
    document.title = document.title.concat(" | Payouts");

    $('#reversePayout').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var modal = $(this);
        modal.find('#reverse_id').val(button.data('id'));
        modal.find('#reverse_message').text("Reverse payout ".concat(button.data('payout_no'), "? This cannot be undone, but the cash it paid out will become available again."));
        modal.find('#reversal_reason').val('');
    });

@can('Manage Partnership')
    (function () {
        var availableCash = {{ (float) $available }};
        var modeSplit = document.getElementById('mode_split');
        var modeSingle = document.getElementById('mode_single');
        var splitSection = document.getElementById('payoutSplitSection');
        var singleSection = document.getElementById('payoutSingleSection');
        var totalInput = document.getElementById('total_amount');
        var amountInputs = document.querySelectorAll('.split-amount-input');
        var splitSumCell = document.getElementById('splitSumCell');
        var singlePartnerSelect = document.getElementById('single_partner_id');
        var singleAmountInput = document.getElementById('single_amount');
        var submitBtn = document.getElementById('recordPayoutBtn');
        var blockedMessage = document.getElementById('payoutBlockedMessage');
        var payoutForm = document.getElementById('payoutForm');

        function toggleMode() {
            var isSplit = modeSplit.checked;
            splitSection.style.display = isSplit ? '' : 'none';
            singleSection.style.display = isSplit ? 'none' : '';

            totalInput.disabled = !isSplit;
            amountInputs.forEach(function (input) { input.disabled = !isSplit; });
            singlePartnerSelect.disabled = isSplit;
            singleAmountInput.disabled = isSplit;

            checkAvailable();
        }

        function splitByShares(total) {
            var rows = Array.prototype.map.call(amountInputs, function (input) {
                var share = parseFloat(input.getAttribute('data-share')) || 0;
                var raw = total * share / 100;

                return { input: input, floor: Math.floor(raw), rem: raw - Math.floor(raw) };
            });

            var flooredSum = rows.reduce(function (sum, row) { return sum + row.floor; }, 0);
            var remainder = Math.max(0, Math.round(total - flooredSum));

            rows.slice().sort(function (a, b) { return b.rem - a.rem; })
                .forEach(function (row, index) { row.extra = index < remainder ? 1 : 0; });

            rows.forEach(function (row) { row.input.value = row.floor + row.extra; });
        }

        function updateSplitSum() {
            var sum = Array.prototype.reduce.call(amountInputs, function (acc, input) {
                return acc + (parseFloat(input.value) || 0);
            }, 0);
            splitSumCell.textContent = Math.round(sum).toLocaleString();
        }

        function currentTotal() {
            return modeSplit.checked ? (parseFloat(totalInput.value) || 0) : (parseFloat(singleAmountInput.value) || 0);
        }

        function checkAvailable() {
            var total = currentTotal();

            if (total > availableCash) {
                blockedMessage.textContent = 'Only TZS ' + Math.round(availableCash).toLocaleString() + ' is available. Reduce the payout.';
                blockedMessage.style.display = '';
                submitBtn.disabled = true;
            } else {
                blockedMessage.style.display = 'none';
                submitBtn.disabled = false;
            }
        }

        modeSplit.addEventListener('change', toggleMode);
        modeSingle.addEventListener('change', toggleMode);

        totalInput.addEventListener('input', function () {
            splitByShares(parseFloat(totalInput.value) || 0);
            updateSplitSum();
            checkAvailable();
        });

        amountInputs.forEach(function (input) {
            input.addEventListener('input', function () {
                updateSplitSum();
                checkAvailable();
            });
        });

        singleAmountInput.addEventListener('input', checkAvailable);

        payoutForm.addEventListener('submit', function () {
            if (!submitBtn.disabled) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Recording…';
            }
        });

        updateSplitSum();
        toggleMode();
    })();
@endcan
</script>
@endpush
