// Prescription Upload Step 1 JavaScript
// New medication search + selection flow with backend sync

document.addEventListener('DOMContentLoaded', () => {
  initializePrescriptionForm();
  initializeBranchSelection();
});

const medicationState = {
  selected: null,
  list: [],
  suggestionsTimer: null,
};

/** Initialize prescription form functionality */
function initializePrescriptionForm() {
  const form = document.getElementById('prescription-form');
  if (!form) return;

  form.addEventListener('submit', (e) => {
    if (!validateForm()) {
      e.preventDefault();
      alert('Por favor completa los campos requeridos antes de continuar.');
    }
  });

  setupMedicationSearch();
  hydrateInitialMedications(window.initialMedications || []);
  validateForm();
}

function setupMedicationSearch() {
  const searchInput = document.getElementById('medication-search');
  const suggestionsBox = document.getElementById('medication-suggestions');
  const selectedPanel = document.getElementById('selected-medication-panel');
  const selectedName = document.getElementById('selected-medication-name');
  const selectedMeta = document.getElementById('selected-medication-meta');
  const clearSelectionButton = document.getElementById('clear-selected-medication');
  const addButton = document.getElementById('add-selected-medication');
  const quantityInput = document.getElementById('selected-quantity');

  if (!searchInput || !suggestionsBox || !selectedPanel || !selectedName || !selectedMeta || !addButton || !quantityInput) {
    return;
  }

  searchInput.addEventListener('input', (e) => {
    const term = e.target.value.trim();
    if (medicationState.suggestionsTimer) {
      clearTimeout(medicationState.suggestionsTimer);
    }
    medicationState.suggestionsTimer = setTimeout(() => {
      if (term.length < 2) {
        hideSuggestions(suggestionsBox);
        return;
      }
      searchMedications(term, suggestionsBox);
    }, 250);
  });

  document.addEventListener('click', (event) => {
    if (!suggestionsBox.contains(event.target) && event.target !== searchInput) {
      hideSuggestions(suggestionsBox);
    }
  });

  clearSelectionButton.addEventListener('click', () => {
    medicationState.selected = null;
    selectedPanel.classList.add('hidden');
    searchInput.value = '';
  });

  addButton.addEventListener('click', () => {
    const quantity = parseInt(quantityInput.value, 10);
    if (!medicationState.selected) {
      alert('Selecciona un medicamento de la lista.');
      return;
    }
    if (!quantity || quantity < 1) {
      alert('Ingresa una cantidad válida.');
      return;
    }
    addMedicationToBackend(medicationState.selected.id, quantity);
  });
}

function searchMedications(term, container) {
  const url = (window.routes && window.routes.medicationsSearch) || '/prescription/medications/search';
  fetch(`${url}?q=${encodeURIComponent(term)}`, { headers: { Accept: 'application/json' } })
    .then((res) => res.ok ? res.json() : Promise.reject(res.statusText))
    .then((results) => renderSuggestions(results || [], container))
    .catch((err) => {
      console.error('Error buscando medicamentos', err);
      container.innerHTML = `<div class="px-4 py-3 text-sm text-red-500">No se pudo cargar la búsqueda</div>`;
      container.classList.remove('hidden');
    });
}

function renderSuggestions(results, container) {
  container.innerHTML = '';
  if (!results.length) {
    container.innerHTML = `<div class="px-4 py-3 text-sm text-neutral-text dark:text-neutral-text-dark">Sin resultados</div>`;
    container.classList.remove('hidden');
    return;
  }

  results.forEach((item) => {
    const option = document.createElement('button');
    option.type = 'button';
    option.className = 'w-full text-left px-4 py-3 hover:bg-background-light dark:hover:bg-background-dark transition';
    option.innerHTML = `
      <div class="font-semibold text-body-text dark:text-body-text-dark">${item.nombre}</div>
      <div class="text-xs text-neutral-text dark:text-neutral-text-dark">${item.unidades ? item.unidades : ''} ${item.unidad_medida ?? ''}</div>
    `;
    option.addEventListener('click', () => handleMedicationSelection(item));
    container.appendChild(option);
  });

  container.classList.remove('hidden');
}

function hideSuggestions(container) {
  container.classList.add('hidden');
  container.innerHTML = '';
}

function handleMedicationSelection(medication) {
  const selectedPanel = document.getElementById('selected-medication-panel');
  const selectedName = document.getElementById('selected-medication-name');
  const selectedMeta = document.getElementById('selected-medication-meta');
  const searchInput = document.getElementById('medication-search');
  const suggestionsBox = document.getElementById('medication-suggestions');

  medicationState.selected = {
    id: medication.id,
    name: medication.nombre,
    meta: `${medication.unidades ?? ''} ${medication.unidad_medida ?? ''}`.trim()
  };

  selectedName.textContent = medicationState.selected.name;
  selectedMeta.textContent = medicationState.selected.meta;
  selectedPanel.classList.remove('hidden');
  hideSuggestions(suggestionsBox);
  searchInput.value = medicationState.selected.name;
}

function addMedicationToBackend(medId, quantity) {
  const addUrl = (window.routes && window.routes.medicationsAdd) || '/prescription/medications/add';
  fetch(addUrl, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': getCsrfToken(),
      Accept: 'application/json'
    },
    body: JSON.stringify({ medId, cantidad: quantity })
  })
    .then((res) => res.ok ? res.json() : Promise.reject(res.statusText))
    .then((data) => {
      medicationState.list = Array.isArray(data.medications) ? data.medications : [];
      renderMedicationTable();
      syncHiddenInputs();
      const quantityField = document.getElementById('selected-quantity');
      if (quantityField) quantityField.value = 1;
      validateForm();
    })
    .catch((err) => {
      console.error('Error al agregar medicamento', err);
      alert('No se pudo agregar el medicamento. Intenta de nuevo.');
    });
}

function removeMedication(medId) {
  const removeUrl = (window.routes && window.routes.medicationsRemove) || '/prescription/medications/remove';
  fetch(removeUrl, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': getCsrfToken(),
      Accept: 'application/json'
    },
    body: JSON.stringify({ medId })
  })
    .then((res) => res.ok ? res.json() : Promise.reject(res.statusText))
    .then((data) => {
      medicationState.list = Array.isArray(data.medications) ? data.medications : [];
      renderMedicationTable();
      syncHiddenInputs();
      validateForm();
    })
    .catch((err) => {
      console.error('Error al eliminar medicamento', err);
      alert('No se pudo eliminar el medicamento. Intenta de nuevo.');
    });
}

function renderMedicationTable() {
  const tableBody = document.getElementById('medications-table-body');
  const emptyRow = document.getElementById('medications-empty-state');
  const countBadge = document.getElementById('medications-count');

  if (!tableBody) return;

  tableBody.innerHTML = '';

  if (!medicationState.list.length) {
    if (emptyRow) {
      tableBody.appendChild(emptyRow);
      emptyRow.classList.remove('hidden');
    }
    if (countBadge) countBadge.textContent = '0 seleccionados';
    return;
  }

  medicationState.list.forEach((med) => {
    const row = document.createElement('tr');
    row.innerHTML = `
      <td class="px-4 py-3">
        <div class="font-semibold text-body-text dark:text-body-text-dark">${med.name}</div>
        <div class="text-xs text-neutral-text dark:text-neutral-text-dark">ID: ${med.id}</div>
      </td>
      <td class="px-4 py-3 text-body-text dark:text-body-text-dark">${med.quantity}</td>
      <td class="px-4 py-3 text-right">
        <button type="button" class="text-red-500 hover:text-red-600" data-med-id="${med.id}">
          <span class="material-symbols-outlined text-xl">delete</span>
        </button>
      </td>
    `;
    const deleteButton = row.querySelector('button');
    deleteButton.addEventListener('click', () => removeMedication(med.id));
    tableBody.appendChild(row);
  });

  if (countBadge) {
    countBadge.textContent = `${medicationState.list.length} seleccionado${medicationState.list.length === 1 ? '' : 's'}`;
  }
}

function hydrateInitialMedications(initialMedications = []) {
  if (!Array.isArray(initialMedications) || !initialMedications.length) return;

  medicationState.list = initialMedications.map((med) => ({
    id: med.medication_id || med.id,
    name: med.name || '',
    quantity: parseInt(med.quantity, 10) || 1
  })).filter((med) => med.id);

  if (medicationState.list.length) {
    renderMedicationTable();
    syncHiddenInputs();
    validateForm();
  }
}

function syncHiddenInputs() {
  const hiddenContainer = document.getElementById('medications-hidden-inputs');
  if (!hiddenContainer) return;

  hiddenContainer.innerHTML = '';
  medicationState.list.forEach((med, index) => {
    hiddenContainer.insertAdjacentHTML('beforeend', `
      <input type="hidden" name="medications[${index}][medication_id]" value="${med.id}">
      <input type="hidden" name="medications[${index}][name]" value="${med.name}">
      <input type="hidden" name="medications[${index}][quantity]" value="${med.quantity}">
    `);
  });
}

/** Validate form fields */
function validateForm() {
  const sucursalSelect = document.getElementById('sucursal_id');
  const submitButton = document.getElementById('submit-button');

  const hasSucursal = sucursalSelect && sucursalSelect.value;
  const hasMedications = medicationState.list.length > 0;
  const isValid = !!(hasSucursal && hasMedications);

  if (submitButton) {
    submitButton.disabled = !isValid;
  }

  return isValid;
}

/** Initialize branch selection handling and sync hidden cadena_id */
function initializeBranchSelection() {
  const cadenaSelect = document.getElementById('cadena_id');
  const sucursalSelect = document.getElementById('sucursal_id');

  function populateSucursales(cadenaId) {
    sucursalSelect.innerHTML = '';
    const placeholder = document.createElement('option');
    placeholder.value = '';
    placeholder.disabled = true;
    placeholder.selected = true;
    const defaultSelectOption = (window.prescriptionTranslations && window.prescriptionTranslations.select_option) || 'Select an option';
    placeholder.textContent = defaultSelectOption;
    sucursalSelect.appendChild(placeholder);

    const template = window.routes && window.routes.sucursalesByCadena;
    const url = template ? template.replace('%%CADENA%%', encodeURIComponent(cadenaId)) : ('/prescription/sucursales/' + encodeURIComponent(cadenaId));

    fetch(url, { headers: { 'Accept': 'application/json' } })
      .then(res => {
        if (!res.ok) throw new Error('Network response was not ok');
        return res.json();
      })
      .then(data => {
        if (!Array.isArray(data)) return;
        data.forEach(s => {
          const opt = document.createElement('option');
          opt.value = s.sucursal_id;
          opt.textContent = s.nombre || s.sucursal_id;
          opt.dataset.sucursal = JSON.stringify(s);
          sucursalSelect.appendChild(opt);
        });
        sucursalSelect.disabled = false;
        validateForm();
      })
      .catch(err => {
        console.error('Error loading sucursales for cadena', err);
        sucursalSelect.disabled = true;
      });
  }

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

  if (sucursalSelect) {
    sucursalSelect.addEventListener('change', function () {
      const selectedOption = this.options[this.selectedIndex];
      const sucursalJson = selectedOption.dataset.sucursal;
      if (sucursalJson) {
        try {
          const sucursalObjeto = JSON.parse(sucursalJson);
          fetch('/prescription/sucursal/procesar', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': getCsrfToken()
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

function getCsrfToken() {
  const el = document.querySelector('meta[name="csrf-token"]');
  return el ? el.content : '';
}
