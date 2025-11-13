@extends('layouts.app')

@section('title', __('auth.login.title'))

@push('styles')
    @vite('resources/css/login.css')
@endpush

@push('scripts')
    @vite('resources/js/lockout-countdown.js')
    @vite('resources/js/email-sync.js')
@endpush

@section('content')
    <div class="auth-page">
        <div class="container">
            <h1>{{ __('auth.login.welcome_back') }}</h1>
            <p>{{ __('auth.login.subtitle') }}</p>

            @if (session('status'))
                <div class="status">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert" id="error-message">
                    @if ($errors->has('session'))
                        {{ $errors->first('session') }}
                    @elseif($errors->has('session_reset'))
                        {{ $errors->first('session_reset') }}
                    @elseif($errors->has('nip') && session('lockout_seconds'))
                        <span id="lockout-message">
                            {{ __('messages.login.lockout', ['time' => '']) }} <span id="countdown-timer"></span>.
                        </span>
                    @else
                        {{ $errors->first() }}
                    @endif
                </div>
            @endif

            @if (session('show_session_reset'))
                <div class="session-reset-section">
                    <h3>Active Session Detected</h3>
                    <p>You already have an active session on another device. Click below to delete it and log in from this
                        device.</p>

                    <form method="POST" action="{{ route('session.reset.send') }}" style="margin: 15px 0;"
                        id="session-reset-form">
                        @csrf
                        <input type="hidden" name="email" id="hidden-email" value="{{ old('correo') }}">
                        <button type="submit" class="reset-session-btn">
                            Delete Active Session
                        </button>
                    </form>

                    <p><small>We'll send a confirmation link to <span id="email-display">{{ old('correo') }}</span></small>
                    </p>
                </div>
            @endif

            <form method="POST" action="{{ route('login.attempt') }}">
                @csrf
                <div>
                    <label for="correo">{{ __('auth.login.email') }}</label>
                    <input id="correo" type="email" name="correo" value="{{ old('correo') }}" required
                        autocomplete="email" autofocus>
                </div>
                <div>
                    <label for="nip">{{ __('auth.login.password') }}</label>
                    <input id="nip" type="password" name="nip" required autocomplete="current-password">
                </div>
                <div class="remember-me">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember">{{ __('auth.login.remember_me') }}</label>
                </div>
                <button type="submit">{{ __('auth.login.submit') }}</button>
            </form>

            <p style="text-align: center; margin-top: 20px;">
                {{ __('auth.login.no_account') }}
                <a href="{{ route('register') }}"
                    style="color: #1d4ed8; text-decoration: none;">{{ __('auth.login.register_link') }}</a>
            </p>

            <a class="back-link" href="{{ route('landing') }}">{{ __('auth.session_reset_success.back_to_home') }}</a>
        </div>

        @if (session('lockout_seconds'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const remainingSeconds = {{ session('lockout_seconds') }};
                    const lockedEmail = '{{ old('correo') }}';
                    initLockoutCountdown(remainingSeconds, lockedEmail);
                });
            </script>
        @endif
    </div>
    </div>
@endsection
