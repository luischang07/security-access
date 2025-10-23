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
                    <div class="hero-logo">
                        <img src="{{ asset('images/logo/Logo-sa.png') }}" alt="Logo SecuAccess" width="180" height="auto"
                            loading="eager">
                    </div>
                    <div class="hero-heading">
                        <h1>Obtenga SecuAccess para su empresa</h1>
                    </div>
                </div>
                <div class="hero-text">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum vel sapien auctor, commodo nunc
                        at, volutpat magna. Mauris vel lectus eget justo ultrices tincidunt vel vel nisi.</p>
                </div>
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
            <div id="features" class="feature-grid">
                <div class="feature fade-in">
                    <h3>Característica Uno</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla consectetur elit vel metus finibus,
                        non tincidunt nisi tempus.</p>
                </div>
                <div class="feature fade-in">
                    <h3>Característica Dos</h3>
                    <p>Maecenas pharetra sem nec fermentum euismod. Etiam tincidunt consectetur risus, et dignissim magna
                        auctor quis.</p>
                </div>
                <div class="feature fade-in">
                    <h3>Característica Tres</h3>
                    <p>Suspendisse potenti. Fusce vel sapien sed justo vestibulum lacinia. Phasellus vel lectus sit amet
                        nunc varius.</p>
                </div>
                <div class="feature fade-in">
                    <h3>Característica Cuatro</h3>
                    <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Duis
                        egestas.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about-section">
        <div class="container">
            <h2 class="section-title fade-in">Acerca de Nuestra Solución</h2>
            <div class="about-content">
                <div class="about-text fade-in">
                    <h3>Sistema de Seguridad de Última Generación</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam in dui mauris. Vivamus hendrerit arcu
                        sed erat molestie vehicula. Sed auctor neque eu tellus rhoncus ut eleifend nibh porttitor. Ut in
                        nulla enim. Phasellus molestie magna non est bibendum non venenatis nisl tempor.</p>
                    <p>Suspendisse in orci enim. Donec sed ligula ipsum. Pellentesque consequat metus vitae metus tincidunt,
                        eget congue justo tempus. Duis porta orci sed tortor pharetra dignissim. Vivamus hendrerit arcu sed
                        erat molestie vehicula.</p>
                    <p>Sed auctor neque eu tellus rhoncus ut eleifend nibh porttitor. Ut in nulla enim. Phasellus molestie
                        magna non est bibendum non venenatis nisl tempor. Suspendisse in orci enim.</p>
                </div>
                <div class="about-image fade-in">
                    <img src="{{ asset('images/logo/secuaccess-logo-full.png') }}" alt="SecuAccess Logo" class="about-logo">
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="services-section">
        <div class="container">
            <h2 class="section-title fade-in">Nuestros Servicios</h2>
            <div class="services-grid">
                <div class="service-card fade-in">
                    <h3>Autenticación Avanzada</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas at erat id sapien efficitur
                        ultricies. Nulla facilisi. Proin ac magna enim. Curabitur quis dui vel quam aliquam faucibus ut at
                        mauris.</p>
                </div>
                <div class="service-card fade-in">
                    <h3>Control de Acceso</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas at erat id sapien efficitur
                        ultricies. Nulla facilisi. Proin ac magna enim. Curabitur quis dui vel quam aliquam faucibus ut at
                        mauris.</p>
                </div>
                <div class="service-card fade-in">
                    <h3>Gestión de Sesiones</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas at erat id sapien efficitur
                        ultricies. Nulla facilisi. Proin ac magna enim. Curabitur quis dui vel quam aliquam faucibus ut at
                        mauris.</p>
                </div>
                <div class="service-card fade-in">
                    <h3>Reportes de Seguridad</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas at erat id sapien efficitur
                        ultricies. Nulla facilisi. Proin ac magna enim. Curabitur quis dui vel quam aliquam faucibus ut at
                        mauris.</p>
                </div>
                <div class="service-card fade-in">
                    <h3>Auditorías</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas at erat id sapien efficitur
                        ultricies. Nulla facilisi. Proin ac magna enim. Curabitur quis dui vel quam aliquam faucibus ut at
                        mauris.</p>
                </div>
                <div class="service-card fade-in">
                    <h3>Consultoría de Seguridad</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas at erat id sapien efficitur
                        ultricies. Nulla facilisi. Proin ac magna enim. Curabitur quis dui vel quam aliquam faucibus ut at
                        mauris.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact-section">
        <div class="container">
            <h2 class="section-title fade-in" style="color: #0f172a;">Contacta con Nosotros</h2>
            <div class="contact-grid">
                <div class="contact-info fade-in">
                    <h3>Información de Contacto</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas at erat id sapien efficitur
                        ultricies.</p>
                    <p><strong>Correo:</strong> info@secuaccess.com</p>
                    <p><strong>Teléfono:</strong> +52 123 456 7890</p>
                    <p><strong>Dirección:</strong> Calle Lorem Ipsum #123, Col. Dolor Sit, Ciudad Amet, CP 12345</p>
                </div>
                <div class="contact-form fade-in">
                    <h3>Envía un Mensaje</h3>
                    <form>
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Nombre completo">
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control" placeholder="Correo electrónico">
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" rows="4" placeholder="Mensaje"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Enviar mensaje</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    @include('components.footer')

    <!-- Los scripts para las animaciones y navegación están ahora en navbar.js -->
@endsection
