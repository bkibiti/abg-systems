@extends("layouts.master")

@section("content")

@php
    $hour = (int) \Carbon\CarbonImmutable::now('Africa/Dar_es_Salaam')->format('G');
    $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
    $firstName = trim(strtok(Auth::user()->name, ' ')) ?: Auth::user()->name;
    $todayLabel = \Carbon\CarbonImmutable::now('Africa/Dar_es_Salaam')->format('l, j F Y');
@endphp

<div class="col-12 mb-4">
    <h4 class="staff-greeting-title mb-1">{{ $greeting }}, {{ $firstName }}</h4>
    <div class="staff-greeting-date">{{ $todayLabel }}</div>
</div>

{{-- Phone: overlapping "my totals" cards, big action buttons, mixed recent-entries feed --}}
<div class="col-12 d-lg-none phone-home-block mb-4">
    <div class="phone-staff-tiles mb-3">
        <div class="card phone-staff-tile">
            <div class="card-body">
                <div class="staff-figure-label">
                    <span class="staff-dot" style="background-color: var(--abg-sales)"></span>MY SALES TODAY
                </div>
                <div class="staff-figure-amount">TZS {{ formatAmount($mySalesToday) }}</div>
                <div class="staff-card-subtitle">{{ $mySalesTodayCount }} {{ Str::plural('sale', $mySalesTodayCount) }}</div>
            </div>
        </div>
        <div class="card phone-staff-tile">
            <div class="card-body">
                <div class="staff-figure-label">
                    <span class="staff-dot" style="background-color: var(--abg-expense)"></span>MY EXPENSES TODAY
                </div>
                <div class="staff-figure-amount">TZS {{ formatAmount($myExpensesToday) }}</div>
                <div class="staff-card-subtitle">{{ $myExpensesTodayCount }} {{ Str::plural('expense', $myExpensesTodayCount) }}</div>
            </div>
        </div>
    </div>

    @can('Point of Sales')
        <a href="{{ route('sales.index') }}" class="btn btn-primary w-100 phone-staff-btn mb-2">New sale</a>
    @endcan
    @can('Add Expenses')
        <a href="{{ route('expense.create') }}" class="btn phone-staff-btn phone-staff-btn-dark w-100 mb-4">Expense</a>
    @endcan

    <div class="d-flex justify-content-between align-items-center mb-2">
        <div class="phone-section-title">My recent entries</div>
        @can('View Sales History')
            <a href="{{ route('history.index') }}" class="staff-view-all">See all</a>
        @endcan
    </div>
    <div class="card mb-3">
        <div class="card-body py-2">
            @forelse ($recentEntries as $row)
                @php
                    $entryDate = $row['date'];
                    $entryLabel = $entryDate->isToday() ? 'Today' : ($entryDate->isYesterday() ? 'Yesterday' : $entryDate->format('j M'));
                @endphp
                <div class="phone-feed-row">
                    <span class="phone-feed-icon {{ $row['kind'] === 'sale' ? 'tint-sales' : 'tint-expense' }}">
                        <i class="bi {{ $row['kind'] === 'sale' ? 'bi-cash-coin' : 'bi-wallet2' }}"></i>
                    </span>
                    <div class="phone-feed-main">
                        <div class="phone-feed-title">{{ $row['title'] }}</div>
                        <div class="phone-feed-sub">{{ $entryLabel }} &middot; {{ $row['sub'] }}</div>
                    </div>
                    <div class="phone-feed-amount {{ $row['kind'] === 'sale' ? 'phone-feed-amount-sale' : 'phone-feed-amount-expense' }}">
                        {{ $row['kind'] === 'sale' ? '+' : '-' }}{{ formatAmount($row['amount']) }}
                    </div>
                </div>
            @empty
                <p class="pos-muted mb-0">No sales or expenses in the last 7 days.</p>
            @endforelse
        </div>
    </div>
</div>

@can('Point of Sales')
    <div class="col-md-6 mb-4 d-none d-lg-block">
        <a href="{{ route('sales.index') }}" class="card staff-shortcut staff-shortcut-gold text-decoration-none">
            <div class="card-body d-flex align-items-center gap-3">
                <span class="staff-shortcut-icon staff-shortcut-icon-dark"><i class="bi bi-plus-lg"></i></span>
                <div>
                    <div class="staff-shortcut-title">New sale</div>
                    <div class="staff-shortcut-subtitle">Open the POS</div>
                </div>
            </div>
        </a>
    </div>
@endcan

@can('Add Expenses')
    <div class="col-md-6 mb-4 d-none d-lg-block">
        <a href="{{ route('expense.create') }}" class="card staff-shortcut text-decoration-none">
            <div class="card-body d-flex align-items-center gap-3">
                <span class="staff-shortcut-icon staff-shortcut-icon-copper"><i class="bi bi-wallet2"></i></span>
                <div>
                    <div class="staff-shortcut-title">Record expense</div>
                    <div class="staff-shortcut-subtitle">Discs, transport</div>
                </div>
            </div>
        </a>
    </div>
@endcan

<div class="col-md-6 mb-4 d-none d-lg-block">
    <div class="card staff-figure-card h-100">
        <div class="card-body">
            <div class="staff-figure-label">
                <span class="staff-dot" style="background-color: var(--abg-sales)"></span>MY SALES TODAY
            </div>
            <div class="staff-figure-amount">TZS {{ formatAmount($mySalesToday) }}</div>
        </div>
    </div>
</div>

<div class="col-md-6 mb-4 d-none d-lg-block">
    <div class="card staff-figure-card h-100">
        <div class="card-body">
            <div class="staff-figure-label">
                <span class="staff-dot" style="background-color: var(--abg-expense)"></span>MY EXPENSES TODAY
            </div>
            <div class="staff-figure-amount">TZS {{ formatAmount($myExpensesToday) }}</div>
        </div>
    </div>
</div>

<div class="col-lg-6 mb-4">
    <div class="card h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-title mb-0">My recent sales</h5>
                <div class="staff-card-subtitle">Last 7 days</div>
            </div>
            @can('View Sales History')
                <a href="{{ route('sales.history') }}" class="staff-view-all">View all</a>
            @endcan
        </div>
        <div class="card-body">
            @if ($recentSales->isEmpty())
                <p class="text-body-secondary mb-0">No sales in the last 7 days.</p>
            @else
                <div class="staff-table-scroll">
                    <table class="staff-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Product</th>
                                <th class="amount-col">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentSales as $sale)
                                <tr>
                                    <td>{{ $sale['date']->format('j M Y') }}</td>
                                    <td>{{ $sale['product'] }}</td>
                                    <td class="amount-col">{{ formatAmount($sale['amount']) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2">Total (last 7 days)</th>
                                <th class="amount-col">{{ formatAmount($recentSalesTotal) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="col-lg-6 mb-4">
    <div class="card h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-title mb-0">My recent expenses</h5>
                <div class="staff-card-subtitle">Last 7 days</div>
            </div>
            @canany(['View Expense Management', 'View Expenses', 'Add Expenses', 'Edit Expenses'])
                <a href="{{ route('expense.index') }}" class="staff-view-all">View all</a>
            @endcanany
        </div>
        <div class="card-body">
            @if ($recentExpenses->isEmpty())
                <p class="text-body-secondary mb-0">No expenses in the last 7 days.</p>
            @else
                <div class="staff-table-scroll">
                    <table class="staff-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                {{-- <th>Category</th> --}}
                                <th>Description</th>
                                <th class="amount-col">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentExpenses as $expense)
                                <tr>
                                    <td>{{ $expense['date']->format('j M Y') }}</td>
                                    {{-- <td>{{ $expense['category'] }}</td> --}}
                                    <td>{{ $expense['description'] }}</td>
                                    <td class="amount-col">{{ formatAmount($expense['amount']) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3">Total (last 7 days)</th>
                                <th class="amount-col">{{ formatAmount($recentExpensesTotal) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

@push("page_scripts")
<script>
    document.title = document.title.concat(" | Dashboard");
</script>
@endpush