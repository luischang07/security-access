/**
 * Patient Profile JavaScript
 * 
 * Handles profile edit mode, form validation, and interactions
 */

document.addEventListener('DOMContentLoaded', function () {
  initializeProfile();
});

/**
 * Initialize profile functionality
 */
function initializeProfile() {
  const editButton = document.getElementById('edit-profile-btn');
  const cancelButton = document.getElementById('cancel-edit-btn');
  const profileForm = document.getElementById('profile-form');

  if (editButton) {
    editButton.addEventListener('click', function () {
      toggleEditMode(true);
    });
  }

  if (cancelButton) {
    cancelButton.addEventListener('click', function (e) {
      e.preventDefault();
      toggleEditMode(false);
    });
  }

  if (profileForm) {
    profileForm.addEventListener('submit', function (e) {
      if (!validateProfileForm()) {
        e.preventDefault();
        alert('Por favor completa todos los campos requeridos correctamente.');
      }
    });
  }
}

/**
 * Toggle between display and edit mode
 * @param {boolean} editMode 
 */
function toggleEditMode(editMode) {
  const displayMode = document.getElementById('profile-display-mode');
  const editModeEl = document.getElementById('profile-edit-mode');

  if (editMode) {
    displayMode.classList.add('hidden');
    editModeEl.classList.remove('hidden');
  } else {
    displayMode.classList.remove('hidden');
    editModeEl.classList.add('hidden');

    // Reset form to original values
    const form = document.getElementById('profile-form');
    if (form) {
      form.reset();
    }
  }
}

/**
 * Validate profile form
 * @returns {boolean}
 */
function validateProfileForm() {
  const nombre = document.getElementById('nombre').value.trim();
  const apellido = document.getElementById('apellido').value.trim();
  const correo = document.getElementById('correo').value.trim();

  // Check required fields
  if (!nombre || !apellido || !correo) {
    return false;
  }

  // Validate email format
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(correo)) {
    return false;
  }

  return true;
}

/**
 * Format date for display
 * @param {string} dateString 
 * @returns {string}
 */
function formatDate(dateString) {
  if (!dateString) return 'N/A';

  const date = new Date(dateString);
  return new Intl.DateTimeFormat('es-MX', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  }).format(date);
}

/**
 * Format datetime for display
 * @param {string} dateString 
 * @returns {string}
 */
function formatDateTime(dateString) {
  if (!dateString) return 'N/A';

  const date = new Date(dateString);
  return new Intl.DateTimeFormat('es-MX', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  }).format(date);
}

// Auto-hide success/error messages after 5 seconds
document.addEventListener('DOMContentLoaded', function () {
  const alerts = document.querySelectorAll('.alert-message');
  alerts.forEach(alert => {
    setTimeout(() => {
      alert.style.transition = 'opacity 0.5s';
      alert.style.opacity = '0';
      setTimeout(() => alert.remove(), 500);
    }, 5000);
  });
});
