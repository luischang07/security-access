<!-- Footer Component -->
<footer class="{{ isset($dashboard) ? 'footer-dashboard' : '' }}">
    <div class="container">
        <h3>SecuAccess</h3>
        <p>Seguridad de acceso centrada en el usuario</p>
        <div class="footer-links">
            <a href="{{ isset($dashboard) ? route('landing') . '#inicio' : '#inicio' }}" class="footer-link">Inicio</a>
            <a href="{{ isset($dashboard) ? route('landing') . '#features' : '#features' }}"
                class="footer-link">Características</a>
            <a href="{{ isset($dashboard) ? route('landing') . '#about' : '#about' }}" class="footer-link">Acerca de</a>
            <a href="{{ isset($dashboard) ? route('landing') . '#services' : '#services' }}"
                class="footer-link">Servicios</a>
            <a href="{{ isset($dashboard) ? route('landing') . '#contact' : '#contact' }}"
                class="footer-link">Contacto</a>
        </div>
        <p>&copy; {{ date('Y') }} SecuAccess. Todos los derechos reservados.</p>
    </div>
</footer>
