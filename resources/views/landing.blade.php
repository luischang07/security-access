@extends('layouts.app')

@section('title', 'Acceso Seguro')

@push('styles')
    @vite('resources/css/landing.css')
@endpush

@section('content')
    @include('components.navbar')

    <!-- Hero Section -->
    <section id="inicio" class="hero">
        <div class="card fade-in">
            @if (session('status'))
                <div class="alert">{{ session('status') }}</div>
            @endif
            <div class="hero-content">
                <div class="hero-header">
                    <div class="hero-heading">
                        <h1>Seguridad de Acceso</h1>
                    </div>
                </div>
                {{-- <div class="hero-text">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum vel sapien auctor, commodo nunc
                        at, volutpat magna. Mauris vel lectus eget justo ultrices tincidunt vel vel nisi.</p>
                </div> --}}
                <div class="cta">
                    @auth
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-secondary">Cerrar sesión</button>
                        </form>
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">Ir al panel</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary">Comience ahora</a>
                        <a href="#contact" class="btn btn-secondary">Comunicarse con ventas</a>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    @include('components.footer')

    <!-- Los scripts para las animaciones y navegación están ahora en navbar.js -->
@endsection
