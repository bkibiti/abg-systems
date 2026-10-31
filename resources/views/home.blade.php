@extends("layouts.master")

@section('page_css')
<style>
    .dashboard-card {
        background-color: #fff;
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 12px;
        box-shadow: none;
    }

    .dashboard-card > .card-header {
        background-color: transparent;
        border-bottom: 1px solid rgba(0, 0, 0, 0.06);
        border-radius: 12px 12px 0 0;
    }

    .dashboard-label {
        color: var(--bs-secondary-color);
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 0.03em;
        text-transform: uppercase;
    }

    .dashboard-figure {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1.2;
    }

    .dashboard-chart {
        position: relative;
        height: 340px;
    }

    .dashboard-bar-track {
        background-color: rgba(0, 0, 0, 0.05);
        border-radius: 4px;
        height: 6px;
        overflow: hidden;
    }

    .dashboard-bar {
        border-radius: 4px;
        height: 100%;
    }

    .dashboard-dot {
        border-radius: 50%;
        display: inline-block;
        height: 10px;
        margin-right: 4px;
        width: 10px;
    }

    .dashboard-period-switch .btn {
        background-color: transparent;
        border-color: var(--abg-input-border, #D8D4CB);
        color: var(--abg-text, #141414);
    }

    .dashboard-period-switch .btn.active {
        background-color: var(--abg-black, #0B0B0B);
        border-color: var(--abg-black, #0B0B0B);
        color: #FFFFFF;
    }
</style>
@endsection

@section("content")

@can('View Advanced Dashboard')

@php
    $periodLabel = $periods[$period];
@endphp

<div class="col-12 d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div class="text-body-secondary">{{ $today->format('l, j F Y') }}</div>
    <div class="btn-group btn-group-sm dashboard-period-switch" role="group" aria-label="Dashboard period">
        @foreach ($periods as $periodKey => $label)
            <a href="{{ route('home', ['period' => $periodKey]) }}"
                class="btn {{ $periodKey === $period ? 'active' : '' }}"
                @if ($periodKey === $period) aria-current="page" @endif>{{ $label }}</a>
        @endforeach
    </div>
</div>

{{-- Row 1: cash available and key figures --}}
<div class="col-md-4 mb-4">
    <div class="card dashboard-card h-100">
        <div class="card-body">
            <div class="dashboard-label">
                <i class="bi bi-wallet2 me-1"></i>
                Cash available
            </div>
            <div class="dashboard-figure {{ $cashAvailable['available'] < 0 ? 'text-danger' : '' }}">
                {{ $cashAvailable['available'] < 0 ? '-' : '' }}{{ formatAmount(abs($cashAvailable['available'])) }}
            </div>
            <div class="small text-body-secondary">All time, up to today</div>
        </div>
    </div>
</div>


@foreach ($keyFigures as $figure)
    @php
        if (is_null($figure['change']) || abs($figure['change']) < 0.05) {
            $changeClass = 'text-body-secondary';
        } elseif (! $figure['upIsGood']) {
            $changeClass = 'text-body-secondary';
        } else {
            $changeClass = $figure['change'] > 0 ? 'text-success' : 'text-danger';
        }
    @endphp
    <div class="col-md-4 mb-4">
        <div class="card dashboard-card h-100">
            <div class="card-body">
                <div class="dashboard-label">
                    <span class="dashboard-dot" style="background-color: {{ $figure['title'] === 'Sales' ? 'var(--abg-sales)' : 'var(--abg-expense)' }}"></span>
                    {{ $figure['title'] }} · {{ $periodLabel }}
                </div>
                <div class="dashboard-figure">{{ formatAmount($figure['amount']) }}</div>
                <div class="small {{ $changeClass }}">
                    @if (is_null($figure['change']))
                        No {{ strtolower($figure['title']) }} for {{ $comparisonLabel }} to compare
                    @elseif (abs($figure['change']) < 0.05)
                        No change vs {{ $comparisonLabel }} ({{ formatAmount($figure['previous']) }})
                    @else
                        {{ $figure['change'] > 0 ? '▲' : '▼' }} {{ number_format(abs($figure['change']), 1) }}%
                        vs {{ $comparisonLabel }} ({{ formatAmount($figure['previous']) }})
                    @endif
                </div>
            </div>
        </div>
    </div>
@endforeach

{{-- Row 2: monthly chart --}}
<div class="col-12 mb-4">
    <div class="card dashboard-card">
        <div class="card-header">
            <h5 class="card-title mb-0">Sales and expenses per month</h5>
            <div class="card-tools text-body-secondary small">Last 12 months</div>
        </div>
        <div class="card-body">
            <div class="dashboard-chart">
                <canvas id="sales-expenses-chart" aria-label="Line chart of monthly sales and expenses" role="img"></canvas>
            </div>
            <script type="application/json" id="sales-expenses-data">@json($monthlyTrend)</script>

          
        </div>
    </div>
</div>

{{-- Row 3: sales by service and expenses by category --}}
<div class="col-lg-6 mb-4">
    <div class="card dashboard-card h-100">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-start gap-2">
            <div>
                <h5 class="card-title mb-0">Sales by service</h5>
                <div class="card-tools text-body-secondary small">{{ $periodLabel }}</div>
            </div>
            <div class="text-end">
                <div class="sbs-total">TZS {{ formatAmount($salesByService['total']) }}</div>
                <div class="small text-body-secondary">
                    {{ $salesByService['salesCount'] }} {{ Str::plural('sale', $salesByService['salesCount']) }}
                    &middot; avg TZS {{ formatAmount($salesByService['average']) }} per sale
                </div>
            </div>
        </div>
        <div class="card-body">
            @forelse ($salesByService['rows'] as $row)
                <div class="sbs-row">
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="sbs-dot" style="background-color: {{ $row['color'] }}"></span>
                            <span class="fw-bold">{{ $row['name'] }}</span>
                        </div>
                        <div class="text-end text-nowrap">
                            <span class="fw-bold">{{ formatAmount($row['amount']) }}</span>
                            <span class="text-body-secondary">&middot; {{ number_format($row['share'], 1) }}%</span>
                        </div>
                    </div>

                    @if (! $row['hasSales'])
                        <p class="small text-body-secondary mb-0 mt-1">No sales yet in this period.</p>
                    @else
                        <div class="sbs-bar-track mt-2" title="{{ $row['name'] }} &middot; {{ formatAmount($row['amount']) }}">
                            <div class="sbs-bar" style="width: {{ $salesByService['maxAmount'] > 0 ? round($row['amount'] / $salesByService['maxAmount'] * 100, 2) : 0 }}%; background-color: {{ $row['color'] }}"></div>
                        </div>
                        <div class="d-flex justify-content-between align-items-start small mt-1 gap-2">
                            <span class="text-body-secondary">
                                {{ $row['salesCount'] }} {{ Str::plural('sale', $row['salesCount']) }}
                                &middot; {{ number_format($row['qty']) }} pcs
                                &middot; avg {{ formatAmount($row['average']) }} per sale
                            </span>
                            @if ($row['change'] !== null)
                                <span class="sbs-change text-nowrap">vs previous period {{ $row['change'] >= 0 ? '▲' : '▼' }} {{ number_format(abs($row['change']), 0) }}%</span>
                            @endif
                        </div>
                    @endif
                </div>
            @empty
                <p class="text-body-secondary mb-0">No sales yet.</p>
            @endforelse

            @if (count($salesByService['monthly']) > 0)
                @php
                    $mixAriaLabel = collect($salesByService['monthly'])->map(fn ($m) => $m['label'].' total TZS '.formatAmount($m['total']))->implode(', ');
                @endphp
                <div class="sbs-mix-label mt-4 mb-2">Mix by month</div>
                <div class="sbs-mix" role="img" aria-label="Monthly sales mix by service: {{ $mixAriaLabel }}">
                    @foreach ($salesByService['monthly'] as $month)
                        <div class="sbs-mix-col">
                            <div class="sbs-mix-bar" title="{{ $month['label'] }} &middot; TZS {{ formatAmount($month['total']) }}">
                                @foreach ($month['segments'] as $segment)
                                    @if ($segment['amount'] > 0)
                                        <div class="sbs-mix-seg" style="height: {{ round($segment['share'], 2) }}%; background-color: {{ $segment['color'] }}"
                                            title="{{ $segment['name'] }} &middot; {{ number_format($segment['share'], 1) }}%"></div>
                                    @endif
                                @endforeach
                            </div>
                            <div class="sbs-mix-month small text-body-secondary mt-1">{{ $month['label'] }}</div>
                            <div class="sbs-mix-total small">{{ formatCompactAmount($month['total']) }}</div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<div class="col-lg-6 mb-4">
    <div class="card dashboard-card h-100">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-start gap-2">
            <div>
                <h5 class="card-title mb-0">Expenses by category</h5>
                <div class="card-tools text-body-secondary small">{{ $periodLabel }} </div>
            </div>
            <div class="text-end">
                <div class="sbs-total">TZS {{ formatAmount($expensesByCategory['total']) }}</div>
                <div class="small text-body-secondary">avg TZS {{ formatAmount($expensesByCategory['averagePerMonth']) }} per month</div>
            </div>
        </div>
        <div class="card-body" id="expensesByCategoryBody">
            <div class="btn-group btn-group-sm mb-3" role="group" aria-label="Expenses by category view">
                <button type="button" class="btn ebc-toggle-btn active" data-ebc-view="amount">Amount</button>
                <button type="button" class="btn ebc-toggle-btn" data-ebc-view="change">vs previous period</button>
            </div>

            @forelse ($expensesByCategory['rows'] as $row)
                @php
                    $barWidth = $expensesByCategory['maxAmount'] > 0 ? max(3, round($row['amount'] / $expensesByCategory['maxAmount'] * 100, 2)) : 0;
                    $isLink = ! $row['grouped'] && $row['id'] !== null;
                @endphp
                <div class="ebc-row">
                    <div class="ebc-name">
                        @if ($isLink)
                            <a href="{{ route('expense.index', ['expense_category' => $row['id'], 'from_date' => $range['start']->toDateString(), 'to_date' => $range['end']->subDay()->toDateString()]) }}">{{ $row['name'] }}</a>
                        @elseif ($row['grouped'])
                            <span title="{{ $row['names'] }}">{{ $row['name'] }}</span>
                        @else
                            <span>{{ $row['name'] }}</span>
                        @endif
                    </div>
                    <div class="ebc-bar-wrap">
                        <div class="ebc-bar {{ $row['grouped'] ? 'ebc-bar-grouped' : '' }}" style="width: {{ $barWidth }}%"
                            title="{{ $row['name'] }} &middot; {{ formatAmount($row['amount']) }}"></div>
                    </div>
                    <div class="ebc-value">
                        <span class="ebc-amount">{{ formatAmount($row['amount']) }}</span>
                        <span class="ebc-percent">&middot; {{ number_format($row['share'], 1) }}%</span>
                        <span class="ebc-change">
                            @if ($row['isNew'])
                                &middot; new
                            @elseif ($row['change'] === null)
                                &middot; &mdash;
                            @else
                                &middot; {{ $row['change'] >= 0 ? '▲' : '▼' }} {{ number_format(abs($row['change']), 0) }}%
                            @endif
                        </span>
                    </div>
                </div>
            @empty
                <p class="text-body-secondary mb-0">No expenses yet.</p>
            @endforelse

            @if ($expensesByCategory['insight'])
                <div class="ebc-insight mt-3">
                    <i class="bi bi-info-circle"></i>
                    <span>{{ $expensesByCategory['insight'] }}</span>
                </div>
            @endif
        </div>
    </div>
</div>

@else
<div class="col-sm-12">
    <div class="card">
        <div class="card-body">
            <h4>Welcome, {{ Auth::user()->name }}</h4>
            <p class="text-muted mb-0">Use the menu on the left to get started.</p>
        </div>
    </div>
</div>
@endcan

@endsection

@push("page_scripts")
<script>
    document.title = document.title.concat(" | Dashboard");
</script>

@can('View Advanced Dashboard')
    @vite('resources/js/dashboard.js')
    <script>
        (function () {
            var buttons = document.querySelectorAll('.ebc-toggle-btn');
            var body = document.getElementById('expensesByCategoryBody');

            buttons.forEach(function (button) {
                button.addEventListener('click', function () {
                    buttons.forEach(function (b) { b.classList.remove('active'); });
                    button.classList.add('active');
                    body.classList.toggle('ebc-view-change', button.getAttribute('data-ebc-view') === 'change');
                });
            });
        })();
    </script>
@endcan
@endpush
