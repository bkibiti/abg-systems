{{-- Phone-only bottom tab bar (hidden ≥769px via CSS). Each tab only renders if the user holds
     the permission for its route, mirroring the same @can checks used in layouts/menu.blade.php. --}}
@php
    $phoneCanExpenses = Auth::user()->can('Add Expenses');
    $phoneCanSell = Auth::user()->can('Point of Sales');
    $phoneCanHistory = Auth::user()->canAny(['View Sales History', 'View Expense Management', 'View Expenses', 'Add Expenses', 'Edit Expenses']);
@endphp
<nav class="phone-bottom-nav d-lg-none" aria-label="Primary">
    <a href="{{ route('home') }}" class="phone-tab {{ request()->routeIs('home') ? 'phone-tab-active' : '' }}"
        @if (request()->routeIs('home')) aria-current="page" @endif>
        <i class="bi bi-house-door" aria-hidden="true"></i>
        <span>Home</span>
    </a>

    @if ($phoneCanExpenses)
        <a href="{{ route('expense.create') }}" class="phone-tab {{ request()->routeIs('expense.create') ? 'phone-tab-active' : '' }}"
            @if (request()->routeIs('expense.create')) aria-current="page" @endif>
            <i class="bi bi-wallet2" aria-hidden="true"></i>
            <span>Expenses</span>
        </a>
    @endif

    @if ($phoneCanSell)
        <a href="{{ route('sales.index') }}" class="phone-tab phone-tab-sell {{ request()->routeIs('sales.index') ? 'phone-tab-active' : '' }}"
            @if (request()->routeIs('sales.index')) aria-current="page" @endif>
            <span class="phone-tab-sell-btn" aria-hidden="true"><i class="bi bi-plus-lg"></i></span>
            <span>Sell</span>
        </a>
    @endif

    @if ($phoneCanHistory)
        <a href="{{ route('history.index') }}" class="phone-tab {{ request()->routeIs('history.index') ? 'phone-tab-active' : '' }}"
            @if (request()->routeIs('history.index')) aria-current="page" @endif>
            <i class="bi bi-clock-history" aria-hidden="true"></i>
            <span>History</span>
        </a>
    @endif

    <a href="{{ route('more.index') }}" class="phone-tab {{ request()->routeIs('more.index') ? 'phone-tab-active' : '' }}"
        @if (request()->routeIs('more.index')) aria-current="page" @endif>
        <i class="bi bi-grid-3x3-gap" aria-hidden="true"></i>
        <span>More</span>
    </a>
</nav>
