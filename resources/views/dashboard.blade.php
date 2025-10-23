@extends('layouts.app')

@section('title', 'Panel de Control')

@push('styles')
    @vite('resources/css/landing.css')
    @vite('resources/css/dashboard.css')
@endpush

@section('content')
    @include('components.navbar', ['dashboard' => true])

    <div class="dashboard-background">
        <div class="wrapper dashboard-content dashboard-container">
            <header class="dashboard-header">
                <div>
                    <h1>Panel de Control</h1>
                    <p>Bienvenido, <strong>{{ auth()->user()->name ?? 'usuario' }}</strong>. Aquí puedes monitorear la
                        seguridad
                        de tu cuenta.</p>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dashboard-btn">Cerrar sesión</button>
                </form>
            </header>

            <div class="card-grid dashboard-cards">
                <div class="card dashboard-card">
                    <div class="card-icon"><i class="fas fa-clock"></i></div>
                    <h2>Último inicio de sesión</h2>
                    <p>{{ optional(auth()->user()->last_login_at)->locale('es')->diffForHumans() ?? 'Sin registros.' }}</p>
                </div>
                <div class="card dashboard-card">
                    <div class="card-icon"><i class="fas fa-shield-alt"></i></div>
                    <h2>Sesión activa</h2>
                    <p>{{ auth()->user()->session_token ? __('Sesión validada y segura.') : __('No hay sesión activa registrada.') }}
                    </p>
                </div>
                <div class="card dashboard-card">
                    <div class="card-icon"><i class="fas fa-envelope"></i></div>
                    <h2>Correo registrado</h2>
                    <p>{{ auth()->user()->email }}</p>
                </div>
            </div>

            <p class="meta dashboard-meta">
                <i class="fas fa-info-circle"></i> Tu sesión se cierra automáticamente si iniciamos otra sesión con las
                mismas
                credenciales en un dispositivo distinto.
            </p>
        </div>
    </div>

    @include('components.footer', ['dashboard' => true])
@endsection
