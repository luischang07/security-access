/**
 * Patient Dashboard JavaScript
 * 
 * Handles dashboard-specific frontend functionality
 */

document.addEventListener('DOMContentLoaded', function () {
  initializeDashboard();
});

/**
 * Initialize dashboard components
 */
function initializeDashboard() {
  // Add smooth animations to progress bars
  animateProgressBars();

  // Add hover effects to order cards
  enhanceOrderCards();
}

/**
 * Animate progress bars on load
 */
function animateProgressBars() {
  const progressBars = document.querySelectorAll('.progress-bar');

  progressBars.forEach(bar => {
    const targetWidth = bar.style.width;
    bar.style.width = '0%';

    // Animate to target width
    setTimeout(() => {
      bar.style.transition = 'width 0.8s ease-out';
      bar.style.width = targetWidth;
    }, 100);
  });
}

/**
 * Enhance order cards with interactive effects
 */
function enhanceOrderCards() {
  const orderCards = document.querySelectorAll('[data-order-card]');

  orderCards.forEach(card => {
    card.addEventListener('mouseenter', function () {
      this.style.transform = 'translateX(4px)';
      this.style.transition = 'transform 0.2s ease';
    });

    card.addEventListener('mouseleave', function () {
      this.style.transform = 'translateX(0)';
    });
  });
}

/**
 * Format currency values
 * @param {number} amount 
 * @returns {string}
 */
function formatCurrency(amount) {
  return new Intl.NumberFormat('es-MX', {
    style: 'currency',
    currency: 'MXN'
  }).format(amount);
}

/**
 * Format date values
 * @param {string} dateString 
 * @returns {string}
 */
function formatDate(dateString) {
  const date = new Date(dateString);
  return new Intl.DateTimeFormat('es-MX', {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit'
  }).format(date);
}

// Export functions for use in other modules if needed
export { formatCurrency, formatDate };
