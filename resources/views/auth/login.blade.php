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
            <div class="alert">
                {{ $errors->first() }}
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
            <button type="submit">Ingresar</button>
        </form>

        <a class="back-link" href="{{ route('landing') }}">&#8592; Volver al inicio</a>
    </div>
@endsection
