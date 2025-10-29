// Countdown timer para bloqueo de cuenta
function initLockoutCountdown(remainingSeconds, lockedEmail) {
  const countdownElement = document.getElementById('countdown-timer');
  const lockoutMessage = document.getElementById('lockout-message');
  const submitButton = document.querySelector('button[type="submit"]');
  const emailInput = document.getElementById('correo');

  if (remainingSeconds > 0 && countdownElement) {
    function checkIfShouldDisable() {
      const currentEmail = emailInput ? emailInput.value : '';
      const shouldDisable = currentEmail === lockedEmail && remainingSeconds > 0;

      if (submitButton) {
        submitButton.disabled = shouldDisable;
        submitButton.textContent = shouldDisable ? 'Cuenta bloqueada' : 'Ingresar';
      }
    }

    function updateCountdown() {
      if (remainingSeconds <= 0) {
        if (submitButton) {
          submitButton.disabled = false;
          submitButton.textContent = 'Ingresar';
        }
        if (lockoutMessage) {
          lockoutMessage.innerHTML =
            'Ya puedes intentar iniciar sesión nuevamente con este correo.';
          lockoutMessage.style.color = '#28a745';
        }
        return;
      }

      const minutes = Math.floor(remainingSeconds / 60);
      const seconds = remainingSeconds % 60;

      let timeText;
      if (minutes > 0) {
        timeText = minutes + ' minuto(s)' + (seconds > 0 ? ' y ' + seconds + ' segundo(s)' : '');
      } else {
        timeText = seconds + ' segundo(s)';
      }

      countdownElement.textContent = timeText;
      remainingSeconds--;

      checkIfShouldDisable();

      setTimeout(updateCountdown, 1000);
    }

    if (emailInput) {
      emailInput.addEventListener('input', checkIfShouldDisable);
      emailInput.addEventListener('keyup', checkIfShouldDisable);
    }

    checkIfShouldDisable();
    updateCountdown();
  }
}