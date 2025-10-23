<!-- Navbar Component -->
<nav class="navbar {{ isset($dashboard) ? 'navbar-dashboard' : '' }}">
    <a href="{{ route('landing') }}" class="navbar-brand">
        <i class="fas fa-shield-alt"></i> SecuAccess
    </a>
    <button class="navbar-toggle" id="navbar-toggle">
        <i class="fas fa-bars"></i>
    </button>
    <div class="navbar-menu" id="navbar-menu">
        <a href="{{ isset($dashboard) ? route('landing') . '#inicio' : '#inicio' }}" class="nav-link">Inicio</a>
        <a href="{{ isset($dashboard) ? route('landing') . '#features' : '#features' }}"
            class="nav-link">Características</a>
        <a href="{{ isset($dashboard) ? route('landing') . '#about' : '#about' }}" class="nav-link">Acerca de</a>
        <a href="{{ isset($dashboard) ? route('landing') . '#services' : '#services' }}" class="nav-link">Servicios</a>
        <a href="{{ isset($dashboard) ? route('landing') . '#contact' : '#contact' }}" class="nav-link">Contacto</a>
        @auth
            <a href="{{ route('dashboard') }}"
                class="btn btn-primary {{ isset($dashboard) ? 'dashboard-btn-primary' : '' }}">Panel</a>
        @else
            <a href="{{ route('login') }}"
                class="btn btn-primary {{ isset($dashboard) ? 'dashboard-btn-primary' : '' }}">Iniciar sesión</a>
        @endauth
    </div>
</nav>
