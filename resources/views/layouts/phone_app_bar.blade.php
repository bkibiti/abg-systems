{{-- Phone-only top app bar (hidden ≥769px via CSS). Main tab pages (home/history/more) show
     the logo and a title; sub-pages (forms like POS/expense, and the rest) show a back arrow. --}}
@php
    $phoneMainTabRoutes = ['home', 'history.index', 'more.index'];
    $isPhoneMainTab = request()->routeIs($phoneMainTabRoutes);
    $phoneTitle = trim((string) $__env->yieldContent('content-title'));

    if (request()->routeIs('home')) {
        $hour = (int) \Carbon\CarbonImmutable::now('Africa/Dar_es_Salaam')->format('G');
        $phoneTitle = ($hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening')).', '.(trim(strtok(Auth::user()->name, ' ')) ?: Auth::user()->name);
    } elseif ($phoneTitle === '') {
        $phoneTitle = $businessName;
    }
@endphp
<header class="phone-app-bar d-lg-none">
    @if ($isPhoneMainTab)
        <span class="phone-app-bar-brand" aria-hidden="true">
            @if ($logoUrl)
                <img src="{{ $logoUrl }}" alt="" class="phone-app-bar-logo">
            @else
                <i class="bi bi-tools"></i>
            @endif
        </span>
        <span class="phone-app-bar-title">{{ $phoneTitle }}</span>
    @else
        <a href="{{ route('home') }}" class="phone-app-bar-back" data-phone-back aria-label="Back">
            <i class="bi bi-arrow-left" aria-hidden="true"></i>
        </a>
        <span class="phone-app-bar-title phone-app-bar-title-center">{{ $phoneTitle }}</span>
        <span class="phone-app-bar-brand phone-app-bar-brand-sm" aria-hidden="true">
            @if ($logoUrl)
                <img src="{{ $logoUrl }}" alt="" class="phone-app-bar-logo-sm">
            @else
                <i class="bi bi-tools"></i>
            @endif
        </span>
    @endif
</header>
