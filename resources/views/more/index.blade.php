@extends("layouts.master")

@section('content-title')
More
@endsection

@section("content")

@php
    $moreUserInitials = collect(explode(' ', Auth::user()->name))
        ->filter()
        ->map(fn ($part) => mb_substr($part, 0, 1))
        ->take(2)
        ->implode('');

    // layouts.master defines $businessName for its own render only; @section('content') runs
    // in this view's own scope, so it needs the same lookup again (mirrors layouts/master.blade.php).
    $moreBusinessName = getSettings('business_name');
    $moreBusinessName = $moreBusinessName === 'NA' || blank($moreBusinessName) ? config('app.name') : $moreBusinessName;

    $canPartnership = Auth::user()->can('View Partnership');
    $canReports = Auth::user()->can('View Reports');
    $canProducts = Auth::user()->can('View Products');
    $canCustomers = Auth::user()->canAny(['View Customers', 'Manage Customers']);
    $businessGroupVisible = $canPartnership || $canReports || $canProducts || $canCustomers;

    $canUsers = Auth::user()->can('View Users');
    $canRoles = Auth::user()->can('View Roles');
    $canSettings = Auth::user()->can('View Settings');
    $adminGroupVisible = $canUsers || $canRoles || $canSettings;
@endphp

<div class="col-12 d-lg-none">
    <div class="more-profile mb-4">
        <span class="more-profile-avatar">{{ mb_strtoupper($moreUserInitials) }}</span>
        <div>
            <div class="more-profile-name">{{ Auth::user()->name }}</div>
            <div class="more-profile-sub">{{ Auth::user()->position }} &middot; {{ $moreBusinessName }}</div>
        </div>
    </div>

    @if ($businessGroupVisible)
        <div class="more-group-label">Business</div>
        <div class="card more-group-card mb-4">
            @if ($canPartnership)
                <a href="{{ route('partnership.index') }}" class="more-row">
                    <span class="more-row-icon tint-indigo"><i class="bi bi-people-fill"></i></span>
                    <span class="more-row-label">Partnership &amp; payouts</span>
                    <i class="bi bi-chevron-right more-row-chevron"></i>
                </a>
            @endif
            @if ($canReports)
                <a href="{{ route('reports.index') }}" class="more-row">
                    <span class="more-row-icon tint-sales"><i class="bi bi-file-earmark-bar-graph"></i></span>
                    <span class="more-row-label">Reports</span>
                    <i class="bi bi-chevron-right more-row-chevron"></i>
                </a>
            @endif
            @if ($canProducts)
                <a href="{{ route('products.index') }}" class="more-row">
                    <span class="more-row-icon tint-gold"><i class="bi bi-box-seam"></i></span>
                    <span class="more-row-label">Products &amp; services</span>
                    <i class="bi bi-chevron-right more-row-chevron"></i>
                </a>
            @endif
            @if ($canCustomers)
                <a href="{{ route('customers.index') }}" class="more-row">
                    <span class="more-row-icon tint-gold"><i class="bi bi-people"></i></span>
                    <span class="more-row-label">Customers</span>
                    <i class="bi bi-chevron-right more-row-chevron"></i>
                </a>
            @endif
        </div>
    @endif

    @if ($adminGroupVisible)
        <div class="more-group-label">Admin</div>
        <div class="card more-group-card mb-4">
            @if ($canUsers || $canRoles)
                <a href="{{ $canUsers ? route('users.index') : route('roles.index') }}" class="more-row">
                    <span class="more-row-icon tint-black"><i class="bi bi-person-gear"></i></span>
                    <span class="more-row-label">Users &amp; roles</span>
                    <i class="bi bi-chevron-right more-row-chevron"></i>
                </a>
            @endif
            @if ($canSettings)
                <a href="{{ route('configurations.index') }}" class="more-row">
                    <span class="more-row-icon tint-black"><i class="bi bi-gear"></i></span>
                    <span class="more-row-label">Settings</span>
                    <i class="bi bi-chevron-right more-row-chevron"></i>
                </a>
            @endif
        </div>
    @endif

    <div class="more-group-label">App</div>
    <div class="card more-group-card mb-4">
        <button type="button" class="more-row more-row-button d-none" id="more_install_row">
            <span class="more-row-icon tint-gold"><i class="bi bi-download"></i></span>
            <span class="more-row-label">Install app on this phone</span>
            <i class="bi bi-chevron-right more-row-chevron"></i>
        </button>
        <a href="{{ route('logout') }}" class="more-row more-row-danger"
            onclick="event.preventDefault(); document.getElementById('more-logout-form').submit();">
            <span class="more-row-icon tint-danger"><i class="bi bi-box-arrow-right"></i></span>
            <span class="more-row-label">Log out</span>
        </a>
        <form id="more-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
</div>

@endsection

@push("page_scripts")
<script>
    document.title = document.title.concat(" | More");
</script>
<script>
    // Mirrors the install prompt already wired up in layouts.master for the sidebar user menu.
    (function () {
        const row = document.getElementById('more_install_row');
        const isStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;
        let installPrompt = null;

        if (isStandalone) {
            return;
        }

        window.addEventListener('beforeinstallprompt', function (event) {
            event.preventDefault();
            installPrompt = event;
            row.classList.remove('d-none');
        });

        row.addEventListener('click', async function () {
            if (installPrompt) {
                installPrompt.prompt();
                await installPrompt.userChoice;
                installPrompt = null;
                row.classList.add('d-none');

                return;
            }

            // iPhone (Safari) does not support beforeinstallprompt: show the manual steps instead.
            if (/iphone|ipad|ipod/i.test(window.navigator.userAgent)) {
                alert('Tap the Share icon, then "Add to Home Screen".');
            }
        });

        if (/iphone|ipad|ipod/i.test(window.navigator.userAgent)) {
            row.classList.remove('d-none');
        }

        window.addEventListener('appinstalled', function () {
            installPrompt = null;
            row.classList.add('d-none');
        });
    })();
</script>
@endpush
