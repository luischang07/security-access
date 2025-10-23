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

  // Añadir listener para el scroll
  window.addEventListener('scroll', handleScrollAnimations);

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