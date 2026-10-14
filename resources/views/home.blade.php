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
        border-radius: 3px;
        display: inline-block;
        height: 10px;
        margin-right: 4px;
        width: 10px;
    }
</style>
@endsection

@section("content")

@can('View Advanced Dashboard')

@php
    $periodLabel = $periods[$period];
@endphp

<div class="col-12 d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div class="text-body-secondary">{{ $today->format('l, j F Y') }} · figures in TZS</div>
    <div class="btn-group btn-group-sm" role="group" aria-label="Dashboard period">
        @foreach ($periods as $periodKey => $label)
            <a href="{{ route('home', ['period' => $periodKey]) }}"
                class="btn {{ $periodKey === $period ? 'btn-primary' : 'btn-outline-secondary' }}"
                @if ($periodKey === $period) aria-current="page" @endif>{{ $label }}</a>
        @endforeach
    </div>
</div>

{{-- Row 1: key figures --}}
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
    <div class="col-md-6 mb-4">
        <div class="card dashboard-card h-100">
            <div class="card-body">
                <div class="dashboard-label">{{ $figure['title'] }} · {{ $periodLabel }}</div>
                <div class="dashboard-figure">{{ formatCompactAmount($figure['amount']) }}</div>
                <div class="small {{ $changeClass }}">
                    @if (is_null($figure['change']))
                        No {{ strtolower($figure['title']) }} for {{ $comparisonLabel }} to compare
                    @elseif (abs($figure['change']) < 0.05)
                        No change vs {{ $comparisonLabel }} ({{ formatCompactAmount($figure['previous']) }})
                    @else
                        {{ $figure['change'] > 0 ? '▲' : '▼' }} {{ number_format(abs($figure['change']), 1) }}%
                        vs {{ $comparisonLabel }} ({{ formatCompactAmount($figure['previous']) }})
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
            <div class="card-tools text-body-secondary small">Last {{ count($monthlyTrend) }} months</div>
        </div>
        <div class="card-body">
            <div class="dashboard-chart">
                <canvas id="sales-expenses-chart" aria-label="Monthly sales by items and services, and expenses" role="img"></canvas>
            </div>
            <script type="application/json" id="sales-expenses-data">@json($monthlyTrend)</script>

          
        </div>
    </div>
</div>

{{-- Row 3: sales mix and expenses by category --}}
<div class="col-lg-6 mb-4">
    <div class="card dashboard-card h-100">
        <div class="card-header">
            <h5 class="card-title mb-0">Sales mix</h5>
            <div class="card-tools text-body-secondary small">{{ $periodLabel }}</div>
        </div>
        <div class="card-body">
            @if ($salesMix['total'] <= 0)
                <p class="text-body-secondary mb-0">No sales yet.</p>
            @else
                <div class="progress-stacked mb-2" style="height: 28px">
                    <div class="progress" role="progressbar" aria-label="Items" style="height: 28px; width: {{ round($salesMix['itemsPercent'], 2) }}%"
                        aria-valuenow="{{ round($salesMix['itemsPercent']) }}" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar" style="background-color: #2B6CB0">{{ round($salesMix['itemsPercent']) >= 8 ? round($salesMix['itemsPercent']).'%' : '' }}</div>
                    </div>
                    <div class="progress" role="progressbar" aria-label="Services" style="height: 28px; width: {{ round($salesMix['servicesPercent'], 2) }}%"
                        aria-valuenow="{{ round($salesMix['servicesPercent']) }}" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar" style="background-color: #D9480F">{{ round($salesMix['servicesPercent']) >= 8 ? round($salesMix['servicesPercent']).'%' : '' }}</div>
                    </div>
                </div>
                <div class="d-flex justify-content-between small mb-4">
                    <div>
                        <span class="dashboard-dot" style="background-color: #2B6CB0"></span>Items
                        <strong>{{ formatCompactAmount($salesMix['items']) }}</strong>
                        <span class="text-body-secondary">({{ number_format($salesMix['itemsPercent'], 1) }}%)</span>
                    </div>
                    <div class="text-end">
                        <span class="dashboard-dot" style="background-color: #D9480F"></span>Services
                        <strong>{{ formatCompactAmount($salesMix['services']) }}</strong>
                        <span class="text-body-secondary">({{ number_format($salesMix['servicesPercent'], 1) }}%)</span>
                    </div>
                </div>

                <div class="dashboard-label mb-2">Services by revenue</div>
                @forelse ($salesMix['serviceRows'] as $service)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between small mb-1">
                            <span>{{ $service['name'] }}</span>
                            <span>
                                <strong>{{ formatCompactAmount($service['revenue']) }}</strong>
                                <span class="text-body-secondary">· {{ $service['jobs'] }} {{ Str::plural('job', $service['jobs']) }}</span>
                            </span>
                        </div>
                        <div class="dashboard-bar-track">
                            <div class="dashboard-bar" style="width: {{ round($service['share'], 2) }}%; background-color: #D9480F"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-body-secondary small mb-0">No service sales in this period.</p>
                @endforelse
            @endif
        </div>
    </div>
</div>

<div class="col-lg-6 mb-4">
    <div class="card dashboard-card h-100">
        <div class="card-header">
            <h5 class="card-title mb-0">Expenses by category</h5>
            <div class="card-tools small">
                <span class="text-body-secondary">{{ $periodLabel }} ·</span>
                <strong>{{ formatCompactAmount($expensesByCategory['total']) }}</strong>
            </div>
        </div>
        <div class="card-body">
            @forelse ($expensesByCategory['rows'] as $category)
                <div class="mb-3">
                    <div class="d-flex justify-content-between small mb-1">
                        <span>{{ $category['name'] }}</span>
                        <strong>{{ formatCompactAmount($category['amount']) }}</strong>
                    </div>
                    <div class="dashboard-bar-track">
                        <div class="dashboard-bar" style="width: {{ round($category['share'], 2) }}%; background-color: #2B9E8F"></div>
                    </div>
                </div>
            @empty
                <p class="text-body-secondary mb-0">No expenses yet.</p>
            @endforelse
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
@endcan
@endpush
