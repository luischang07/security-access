@extends('layouts.app')

@section('title', 'Registro de usuario')

@push('styles')
    @vite('resources/css/register.css')
@endpush

@section('content')
    <div class="container">
        <h1>Crear cuenta</h1>
        <p>Complete el formulario para registrarse en el sistema.</p>

        @if (session('status'))
            <div class="status">{{ session('status') }}</div>
        @endif

        @if ($errors->has('general'))
            <div class="alert">
                {{ $errors->first('general') }}
            </div>
        @endif

        <form method="POST" action="{{ route('register.attempt') }}">
            @csrf
            <div class="form-group">
                <label for="name">Nombre completo</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name"
                    autofocus class="{{ $errors->has('name') ? 'error' : '' }}">
                @if ($errors->has('name'))
                    <div class="error-message">{{ $errors->first('name') }}</div>
                @endif
            </div>

            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                    autocomplete="email" class="{{ $errors->has('email') ? 'error' : '' }}">
                @if ($errors->has('email'))
                    <div class="error-message">{{ $errors->first('email') }}</div>
                @endif
            </div>

            <div class="form-group">
                <label for="nip">NIP</label>
                <input id="nip" type="password" name="nip" required autocomplete="new-password"
                    class="{{ $errors->has('nip') ? 'error' : '' }}">
                <p>Debe contener al menos 8 caracteres, incluyendo una mayúscula, un número y un símbolo.</p>
                @if ($errors->has('nip'))
                    <div class="error-message">{{ $errors->first('nip') }}</div>
                @endif
            </div>

            <div class="form-group">
                <label for="nip_confirmation">Confirmar NIP</label>
                <input id="nip_confirmation" type="password" name="nip_confirmation" required autocomplete="new-password">
            </div>

            <button type="submit">Registrarse</button>
        </form>

        <div class="links">
            <a class="back-link" href="{{ route('landing') }}">&#8592; Volver al inicio</a>
            <a class="login-link" href="{{ route('login') }}">¿Ya tienes cuenta? Inicia sesión</a>
        </div>
    </div>
@endsection
