@extends('layouts.app')

@section('title', 'Panel de Control')

@push('styles')
    @vite('resources/css/dashboard.css')
@endpush

@section('content')
    <div class="wrapper">
        <header>
            <div>
                <h1>Panel de Seguridad</h1>
                <p>Hola {{ auth()->user()->name ?? 'usuario' }}, aquí puedes monitorear tus accesos recientes.</p>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit">Cerrar sesión</button>
            </form>
        </header>

        <div class="card-grid">
            <div class="card">
                <h2>Último inicio de sesión</h2>
                <p>{{ optional(auth()->user()->last_login_at)->locale('es')->diffForHumans() ?? 'Sin registros.' }}</p>
            </div>
            <div class="card">
                <h2>Sesión activa</h2>
                <p>{{ auth()->user()->session_token ? __('Sesión validada y segura.') : __('No hay sesión activa registrada.') }}
                </p>
            </div>
            <div class="card">
                <h2>Correo registrado</h2>
                <p>{{ auth()->user()->email }}</p>
            </div>
        </div>

        <p class="meta">Tu sesión se cierra automáticamente si iniciamos otra sesión con las mismas credenciales en un
            dispositivo distinto.</p>
    </div>
@endsection
