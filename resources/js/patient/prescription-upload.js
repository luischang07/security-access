// Prescription Upload Step 1 JavaScript
// Handles form validation, dynamic medication rows, and form submission

document.addEventListener('DOMContentLoaded', function () {
  initializePrescriptionForm();
  initializeBranchSelection();
  initializeExistingDeleteButtons();
});

/** Initialize prescription form functionality */
function initializePrescriptionForm() {
  const form = document.getElementById('prescription-form');
  const submitButton = document.getElementById('submit-button');
  const addMedicationButton = document.getElementById('add-medication');

  if (!form) return;

  // Enable/disable submit button based on form validity
  form.addEventListener('input', validateForm);

  // Add medication row
  if (addMedicationButton) {
    addMedicationButton.addEventListener('click', function (e) {
      e.preventDefault();
      addMedicationRow();
    });
  }

  // Handle form submission
  form.addEventListener('submit', function (e) {
    if (!validateForm()) {
      e.preventDefault();
      alert('Por favor completa todos los campos requeridos.');
    }
  });
}

/** Validate form fields */
function validateForm() {
  const sucursalId = document.getElementById('sucursal_id').value;
  const patientName = document.getElementById('patient-name').value.trim();
  const doctorName = document.getElementById('doctor-name').value.trim();
  const submitButton = document.getElementById('submit-button');

  // Check at least one medication is filled
  const medicationRows = document.querySelectorAll('.medication-row');
  let hasValidMedication = false;
  medicationRows.forEach(row => {
    const name = row.querySelector('[name$="[name]"]').value.trim();
    const dosage = row.querySelector('[name$="[dosage]"]').value.trim();
    const quantity = row.querySelector('[name$="[quantity]"]').value.trim();
    if (name && dosage && quantity) {
      hasValidMedication = true;
    }
  });

  const isValid = sucursalId && patientName && doctorName && hasValidMedication;
  if (submitButton) submitButton.disabled = !isValid;
  return isValid;
}

/** Add a new medication row */
function addMedicationRow() {
  const container = document.getElementById('medications-container');
  const rows = container.querySelectorAll('.medication-row');
  const newIndex = rows.length;

  const newRow = document.createElement('div');
  newRow.className = 'medication-row grid grid-cols-1 lg:grid-cols-12 gap-4 items-end p-4 border border-border-light dark:border-border-dark rounded-lg bg-card-light dark:bg-card-dark';
  newRow.innerHTML = `
    <div class="lg:col-span-5">
      <label class="block text-sm font-medium text-body-text dark:text-body-text-dark mb-1.5" for="medication-${newIndex}">Nombre del Medicamento</label>
      <input class="w-full rounded-lg border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark focus:border-primary focus:ring-primary/50"
             id="medication-${newIndex}" name="medications[${newIndex}][name]"
             placeholder="ej., Amoxicilina" type="text" required />
    </div>
    <div class="lg:col-span-3">
      <label class="block text-sm font-medium text-body-text dark:text-body-text-dark mb-1.5" for="dosage-${newIndex}">Dosificación</label>
      <input class="w-full rounded-lg border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark focus:border-primary focus:ring-primary/50"
             id="dosage-${newIndex}" name="medications[${newIndex}][dosage]"
             placeholder="ej., 500" type="number" min="1" required />
    </div>
    <div class="lg:col-span-3">
      <label class="block text-sm font-medium text-body-text dark:text-body-text-dark mb-1.5" for="quantity-${newIndex}">Cantidad</label>
      <input class="w-full rounded-lg border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark focus:border-primary focus:ring-primary/50"
             id="quantity-${newIndex}" name="medications[${newIndex}][quantity]"
             placeholder="ej., 30" type="number" min="1" required />
    </div>
    <div class="lg:col-span-1">
      <button type="button" class="delete-medication w-full flex items-center justify-center h-10 rounded-lg bg-transparent text-neutral-text dark:text-neutral-text-dark hover:bg-red-500/10 hover:text-red-500 transition">
        <span class="material-symbols-outlined text-xl">delete</span>
      </button>
    </div>
  `;

  container.appendChild(newRow);

  // Delete functionality for the new row
  const deleteButton = newRow.querySelector('.delete-medication');
  deleteButton.addEventListener('click', function () {
    newRow.remove();
    validateForm();
  });

  // Add input listeners for validation on the new fields
  newRow.querySelectorAll('input').forEach(input => {
    input.addEventListener('input', validateForm);
  });
}

/** Initialize branch selection handling and sync hidden cadena_id */
function initializeBranchSelection() {
  const cadenaSelect = document.getElementById('cadena_id');
  const sucursalSelect = document.getElementById('sucursal_id');

  // All sucursales data passed from Blade via a global variable
  const allSucursales = window.sucursalesData || [];

  // Populate sucursal options based on selected cadena
  function populateSucursales(cadenaId) {
    // Clear existing options
    sucursalSelect.innerHTML = '';
    const placeholder = document.createElement('option');
    placeholder.value = '';
    placeholder.disabled = true;
    placeholder.selected = true;
    const defaultSelectOption = (window.prescriptionTranslations && window.prescriptionTranslations.select_option) || 'Select an option';
    placeholder.textContent = defaultSelectOption;
    sucursalSelect.appendChild(placeholder);

    const filtered = allSucursales.filter(s => s.cadena_id == cadenaId);
    filtered.forEach(s => {
      const opt = document.createElement('option');
      opt.value = s.sucursal_id;
      opt.textContent = s.nombre;
      opt.dataset.sucursal = JSON.stringify(s);
      sucursalSelect.appendChild(opt);
    });
    sucursalSelect.disabled = false;
  }

  // When cadena changes, repopulate sucursales
  if (cadenaSelect) {
    cadenaSelect.addEventListener('change', function () {
      const cadenaId = this.value;
      if (cadenaId) {
        populateSucursales(cadenaId);
      } else {
        sucursalSelect.innerHTML = '';
        sucursalSelect.disabled = true;
      }
      validateForm();
    });
  }

  // When sucursal changes, optional server sync
  if (sucursalSelect) {
    sucursalSelect.addEventListener('change', function () {
      const selectedOption = this.options[this.selectedIndex];
      const sucursalJson = selectedOption.dataset.sucursal;
      if (sucursalJson) {
        try {
          const sucursalObjeto = JSON.parse(sucursalJson);
          // Optional server call
          fetch('/prescription/sucursal/procesar', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
              sucursal_id: sucursalObjeto.sucursal_id,
              cadena_id: sucursalObjeto.cadena_id
            })
          })
            .then(res => res.json())
            .then(data => console.log('Sucursal seleccionada:', data))
            .catch(err => console.error('Error al seleccionar sucursal:', err));
        } catch (e) {
          console.error('Error al procesar datos de la sucursal', e);
        }
      }
      validateForm();
    });
  }
}


/** Initialize delete buttons for existing medication rows */
function initializeExistingDeleteButtons() {
  const deleteButtons = document.querySelectorAll('.delete-medication');
  deleteButtons.forEach(button => {
    button.addEventListener('click', function () {
      const row = this.closest('.medication-row');
      const container = document.getElementById('medications-container');
      if (container.querySelectorAll('.medication-row').length > 1) {
        row.remove();
        validateForm();
      } else {
        alert('Debe haber al menos un medicamento.');
      }
    });
  });
}
