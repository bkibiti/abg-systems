@extends('layouts.app')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-body login-card-body">
        <p class="login-box-msg">Sign in to start your session</p>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="input-group mb-3 has-validation">
                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror"
                    name="username" value="{{ old('username') }}" required placeholder="Username" autofocus>
                <div class="input-group-text"><span class="bi bi-person"></span></div>
                @error('username')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="input-group mb-3 has-validation">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                    name="password" required autocomplete="current-password" placeholder="Password">
                <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="row align-items-center">
                <div class="col-7">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                            {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">Remember Me</label>
                    </div>
                </div>
                <div class="col-5">
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Sign In</button>
                    </div>
                </div>
            </div>
        </form>

        @if (Route::has('password.request'))
            <p class="mb-0 mt-3">
                <a href="{{ route('password.request') }}">I forgot my password</a>
            </p>
        @endif
    </div>
</div>
@endsection
