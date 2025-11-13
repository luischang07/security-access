// Función para manejar las animaciones al hacer scroll
function handleScrollAnimations() {
  const elements = document.querySelectorAll('.fade-in');
  elements.forEach(element => {
    const elementTop = element.getBoundingClientRect().top;
    const windowHeight = window.innerHeight;
    if (elementTop < windowHeight - 100) {
      element.classList.add('visible');
    }
  });
}

// Variables para controlar el comportamiento del navbar
let lastScrollTop = 0;
let scrollThreshold = 10; // Umbral mínimo para detectar scroll

// Función para manejar el show/hide del navbar basado en dirección de scroll
function handleNavbarScroll() {
  const navbar = document.querySelector('header[aria-label*="landing"], header[aria-label*="Dashboard"]');

  if (!navbar) return;

  const currentScroll = window.pageYOffset || document.documentElement.scrollTop;

  // Evitar valores negativos en algunos navegadores
  if (currentScroll < 0) return;

  // Si estamos en la parte superior de la página, siempre mostrar el navbar
  if (currentScroll <= 100) {
    navbar.style.transform = 'translateY(0)';
    navbar.style.transition = 'transform 0.3s ease-in-out';
    lastScrollTop = currentScroll;
    return;
  }

  // Calcular la diferencia de scroll
  const scrollDiff = currentScroll - lastScrollTop;

  // Solo actuar si el scroll supera el umbral
  if (Math.abs(scrollDiff) < scrollThreshold) {
    return;
  }

  // Detectar dirección del scroll
  if (scrollDiff > 0) {
    // Scroll hacia abajo - ocultar navbar
    navbar.style.transform = 'translateY(-100%)';
    navbar.style.transition = 'transform 0.3s ease-in-out';
  } else {
    // Scroll hacia arriba - mostrar navbar inmediatamente
    navbar.style.transform = 'translateY(0)';
    navbar.style.transition = 'transform 0.3s ease-in-out';
  }

  lastScrollTop = currentScroll;
}

// Manejo del menú de navegación móvil y animaciones
document.addEventListener('DOMContentLoaded', function () {
  const navbarToggle = document.getElementById('navbar-toggle');
  const navbarMenu = document.getElementById('navbar-menu');

  if (navbarToggle && navbarMenu) {
    navbarToggle.addEventListener('click', function () {
      navbarMenu.classList.toggle('active');
    });
  }

  // Hacer visible los elementos que ya están en la pantalla al cargar
  handleScrollAnimations();

  // Añadir listener para el scroll (animaciones y navbar)
  window.addEventListener('scroll', function () {
    handleScrollAnimations();
    handleNavbarScroll();
  });

  // Manejar el scroll suave para los enlaces internos
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      const href = this.getAttribute('href');

      // Si el enlace es un ancla (comienza con #)
      if (href.startsWith('#')) {
        e.preventDefault();
        const targetId = href.substring(1);
        const target = document.getElementById(targetId);

        if (target) {
          window.scrollTo({
            top: target.offsetTop - 80,
            behavior: 'smooth'
          });

          // Cerrar el menú móvil después de hacer clic
          if (navbarMenu && navbarMenu.classList.contains('active')) {
            navbarMenu.classList.remove('active');
          }
        }
      }
    });
  });
});