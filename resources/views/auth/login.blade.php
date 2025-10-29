@extends('layouts.app')

@section('title', 'Iniciar sesión')

@push('styles')
    @vite('resources/css/login.css')
@endpush

@push('scripts')
    @vite('resources/js/lockout-countdown.js')
    @vite('resources/js/email-sync.js')
@endpush

@section('content')
    <div class="container">
        <h1>Bienvenido de nuevo</h1>
        <p>Inicia sesión para acceder al panel seguro.</p>

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
                        Cuenta bloqueada temporalmente. Intenta nuevamente en <span id="countdown-timer"></span>.
                    </span>
                @else
                    {{ $errors->first() }}
                @endif
            </div>
        @endif

        @if (session('show_session_reset'))
            <div class="session-reset-section">
                <h3>🔐 Sesión Activa Detectada</h3>
                <p>Si quieres eliminar tu sesión activa para poder iniciar sesión desde este dispositivo, haz clic en el
                    botón de abajo:</p>

                <form method="POST" action="{{ route('session.reset.send') }}" style="margin: 15px 0;"
                    id="session-reset-form">
                    @csrf
                    <input type="hidden" name="email" id="hidden-email" value="{{ old('correo') }}">
                    <button type="submit" class="reset-session-btn">
                        📧 Enviar email para eliminar sesión
                    </button>
                </form>

                <p><small>Se enviará un correo a <strong><span id="email-display">{{ old('correo') }}</span></strong> con un
                        enlace para eliminar tu sesión
                        activa.</small></p>
            </div>
        @endif

        <form method="POST" action="{{ route('login.attempt') }}">
            @csrf
            <div>
                <label for="correo">Correo electrónico</label>
                <input id="correo" type="email" name="correo" value="{{ old('correo') }}" required
                    autocomplete="email" autofocus>
            </div>
            <div>
                <label for="nip">NIP</label>
                <input id="nip" type="password" name="nip" required autocomplete="current-password">
            </div>
            <div class="remember-me">
                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember">Recordarme</label>
            </div>
            <button type="submit">Ingresar</button>
        </form>

        <a class="back-link" href="{{ route('landing') }}">&#8592; Volver al inicio</a>
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

@endsection
