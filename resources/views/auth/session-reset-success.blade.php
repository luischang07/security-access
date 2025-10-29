@extends('layouts.app')

@section('title', 'Sesión Eliminada')

@push('styles')
    @vite('resources/css/login.css')
@endpush

@section('content')
    <div class="container">
        <div style="text-align: center; margin-bottom: 2rem;">
            <div style="font-size: 3rem; margin-bottom: 1rem;">✅</div>
            <h1>Sesión Eliminada Exitosamente</h1>
        </div>

        @if (session('status'))
            <div class="status">{{ session('status') }}</div>
        @endif

        @if (session('reset_email'))
            <div
                style="background-color: #f0f9ff; border: 1px solid #0ea5e9; border-radius: 0.75rem; padding: 1.5rem; margin: 1.5rem 0;">
                <p><strong>Cuenta:</strong> {{ session('reset_email') }}</p>
                <p>Tu sesión activa ha sido eliminada. Ahora puedes iniciar sesión desde este dispositivo.</p>
            </div>
        @endif

        <div style="text-align: center; margin-top: 2rem;">
            <a href="{{ route('login') }}"
                style="display: inline-block; background-color: #1d4ed8; color: white; padding: 1rem 2rem; border-radius: 0.75rem; text-decoration: none; font-weight: 600;">
                Iniciar Sesión
            </a>
        </div>

        <a class="back-link" href="{{ route('landing') }}">&#8592; Volver al inicio</a>
    </div>
@endsection
