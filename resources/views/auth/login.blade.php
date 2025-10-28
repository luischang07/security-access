@extends('layouts.app')

@section('title', 'Iniciar sesión')

@push('styles')
    @vite('resources/css/login.css')
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
                @elseif($errors->has('nip') && session('lockout_seconds'))
                    <span id="lockout-message">
                        Cuenta bloqueada temporalmente. Intenta nuevamente en <span id="countdown-timer"></span>.
                    </span>
                @else
                    {{ $errors->first() }}
                @endif
            </div>
        @endif

        <form method="POST" action="{{ route('login.attempt') }}">
            @csrf
            <div>
                <label for="correo">Correo electrónico</label>
                <input id="correo" type="email" name="correo" value="{{ old('correo') }}" required autocomplete="email"
                    autofocus>
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
                let remainingSeconds = {{ session('lockout_seconds') }};
                const countdownElement = document.getElementById('countdown-timer');
                const lockoutMessage = document.getElementById('lockout-message');
                const submitButton = document.querySelector('button[type="submit"]');
                const emailInput = document.getElementById('correo');
                const lockedEmail = '{{ old('correo') }}'; // El email que está bloqueado

                if (remainingSeconds > 0 && countdownElement) {
                    function checkIfShouldDisable() {
                        const currentEmail = emailInput ? emailInput.value : '';
                        const shouldDisable = currentEmail === lockedEmail && remainingSeconds > 0;

                        if (submitButton) {
                            submitButton.disabled = shouldDisable;
                            submitButton.textContent = shouldDisable ? 'Cuenta bloqueada' : 'Ingresar';
                        }
                    }

                    function updateCountdown() {
                        if (remainingSeconds <= 0) {
                            if (submitButton) {
                                submitButton.disabled = false;
                                submitButton.textContent = 'Ingresar';
                            }
                            if (lockoutMessage) {
                                lockoutMessage.innerHTML =
                                    'Ya puedes intentar iniciar sesión nuevamente con este correo.';
                                lockoutMessage.style.color = '#28a745';
                            }
                            return;
                        }

                        const minutes = Math.floor(remainingSeconds / 60);
                        const seconds = remainingSeconds % 60;

                        let timeText;
                        if (minutes > 0) {
                            timeText = minutes + ' minuto(s)' + (seconds > 0 ? ' y ' + seconds + ' segundo(s)' : '');
                        } else {
                            timeText = seconds + ' segundo(s)';
                        }

                        countdownElement.textContent = timeText;
                        remainingSeconds--;

                        checkIfShouldDisable();

                        setTimeout(updateCountdown, 1000);
                    }

                    if (emailInput) {
                        emailInput.addEventListener('input', checkIfShouldDisable);
                        emailInput.addEventListener('keyup', checkIfShouldDisable);
                    }

                    checkIfShouldDisable();

                    updateCountdown();
                }
            });
        </script>
    @endif
@endsection
