// Sincronización del email del formulario con el campo oculto del session reset
document.addEventListener('DOMContentLoaded', function () {
  const emailInput = document.getElementById('correo');
  const hiddenEmailInput = document.getElementById('hidden-email');
  const emailDisplay = document.getElementById('email-display');

  if (emailInput && hiddenEmailInput) {
    // Función para actualizar el email oculto y el display
    function updateHiddenEmail() {
      const emailValue = emailInput.value;
      hiddenEmailInput.value = emailValue;
      if (emailDisplay) {
        emailDisplay.textContent = emailValue || 'tu correo';
      }
    }

    // Actualizar en tiempo real mientras el usuario escribe
    emailInput.addEventListener('input', updateHiddenEmail);
    emailInput.addEventListener('keyup', updateHiddenEmail);
    emailInput.addEventListener('change', updateHiddenEmail);

    // Actualizar al cargar la página si ya hay un valor
    updateHiddenEmail();
  }
});