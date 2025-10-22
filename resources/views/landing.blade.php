@extends('layouts.app')

@section('title', 'Acceso Seguro')

@push('styles')
    @vite('resources/css/landing.css')
@endpush

@section('content')
    <section class="hero">
        <div class="card">
            @if (session('status'))
                <div class="alert">{{ session('status') }}</div>
            @endif
            <h1>Seguridad de Acceso Centrada en el Usuario</h1>
            <p>
                Protege la información crítica de tu organización con políticas de acceso robustas, seguimiento en
                tiempo real y control total sobre sesiones activas. Nuestra plataforma aplica las mejores prácticas de
                seguridad de Laravel para asegurar credenciales y bloquear accesos no autorizados.
            </p>
            <div class="cta">
                @auth
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-secondary">Cerrar sesión</button>
                    </form>
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">Ir al panel</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary">Iniciar sesión</a>
                    <a href="#features" class="btn btn-secondary">Conoce las políticas</a>
                @endauth
            </div>
            <div id="features" class="feature-grid">
                <div class="feature">
                    <h3>Hash Seguro de NIP</h3>
                    <p>Las credenciales se almacenan usando algoritmos de hashing modernos, imposibles de revertir.</p>
                </div>
                <div class="feature">
                    <h3>Validaciones Rigurosas</h3>
                    <p>Patrones personalizados garantizan que los NIP y correos cumplan con los lineamientos
                        corporativos.</p>
                </div>
                <div class="feature">
                    <h3>Sesión Única</h3>
                    <p>Cada usuario mantiene una sola sesión activa, eliminando accesos simultáneos riesgosos.</p>
                </div>
                <div class="feature">
                    <h3>Bloqueos Temporales</h3>
                    <p>Los intentos fallidos se limitan para mitigar ataques de fuerza bruta y proteger la cuenta.</p>
                </div>
            </div>
        </div>
    </section>
@endsection
