// SPA Navigation - Carga dinámica de contenido sin recargar la página
// Usando eventos nativos en lugar de Alpine.js para mayor compatibilidad

let isLoading = false;
let currentRoute = window.location.pathname;

async function navigateSPA(url, event) {
  console.log('Navigate SPA called:', url);

  if (event) {
    event.preventDefault();
  }

  if (currentRoute === url || isLoading) {
    console.log('Same route or already loading, ignoring');
    return;
  }

  isLoading = true;
  showLoading();
  console.log('Loading started');

  try {
    const response = await fetch(url, {
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
      }
    });

    console.log('Response received:', response.status);

    if (!response.ok) throw new Error('Network response was not ok');

    const data = await response.json();
    console.log('Data received:', data);

    // Actualizar el contenido principal
    const mainContent = document.querySelector('#main-content');
    console.log('Content container found:', !!mainContent);

    if (mainContent && data.html) {
      mainContent.innerHTML = `
        <div id="spa-loader" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.3); z-index: 9999; align-items: center; justify-content: center;">
          <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-xl">
            <div class="flex items-center gap-3">
              <svg class="animate-spin h-8 w-8 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <span class="text-gray-700 dark:text-gray-300 font-medium">Loading...</span>
            </div>
          </div>
        </div>
        ${data.html}
      `;
      console.log('Content updated');
    }

    // Actualizar la URL sin recargar
    window.history.pushState({ path: url }, '', url);
    currentRoute = url;

    // Scroll al inicio del contenido
    document.querySelector('#main-content')?.scrollTo(0, 0);

    // Actualizar clases activas en el sidebar
    updateActiveLinks(url);

  } catch (error) {
    console.error('Error loading content:', error);
    // Fallback: navegación normal
    window.location.href = url;
  } finally {
    isLoading = false;
    hideLoading();
    console.log('Loading finished');
  }
}

function showLoading() {
  const loader = document.getElementById('spa-loader');
  if (loader) {
    loader.style.display = 'flex';
  }
}

function hideLoading() {
  const loader = document.getElementById('spa-loader');
  if (loader) {
    loader.style.display = 'none';
  }
}

function updateActiveLinks(url) {
  // Remover todas las clases activas
  document.querySelectorAll('aside nav a').forEach(link => {
    link.classList.remove('bg-primary/10', 'text-primary');
    link.classList.add('hover:bg-gray-100', 'dark:hover:bg-gray-800', 'text-gray-900', 'dark:text-white');
    const text = link.querySelector('p');
    if (text) {
      text.classList.remove('font-bold');
      text.classList.add('font-medium');
    }
  });

  // Agregar clase activa al link actual
  document.querySelectorAll('aside nav a').forEach(link => {
    if (link.getAttribute('href') === url) {
      link.classList.remove('hover:bg-gray-100', 'dark:hover:bg-gray-800', 'text-gray-900', 'dark:text-white');
      link.classList.add('bg-primary/10', 'text-primary');
      const text = link.querySelector('p');
      if (text) {
        text.classList.remove('font-medium');
        text.classList.add('font-bold');
      }
    }
  });
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
  console.log('SPA Navigation initialized');

  // Agregar event listeners a los links con data-spa
  document.addEventListener('click', (e) => {
    const link = e.target.closest('a[data-spa]');
    if (link) {
      const url = link.getAttribute('href');
      navigateSPA(url, e);
    }
  });

  // Manejar botón atrás/adelante del navegador
  window.addEventListener('popstate', (event) => {
    if (event.state?.path) {
      navigateSPA(event.state.path);
    }
  });
});

// Exportar para uso global
window.navigateSPA = navigateSPA;
