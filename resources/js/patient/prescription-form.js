window.prescriptionForm = function () {
  return {
    // Form Data
    medicationsItems: [],

    // New Medication Input State
    medicationId: '',
    medicationName: '',
    professionalLicense: '',
    medicationQuantity: '',

    // Branch Selection State
    cadenaId: '',
    sucursalId: '',
    sucursales: [],
    loadingSucursales: false,
    pendingSucursalId: null,

    init() {
      // Watch for cadena changes to load sucursales
      this.$watch('cadenaId', (value) => {
        if (value) {
          this.loadSucursales(value);
        } else {
          this.sucursales = [];
          this.sucursalId = '';
        }
      });

      // Check for query parameters to pre-fill form
      const urlParams = new URLSearchParams(window.location.search);
      const preCadenaId = urlParams.get('cadena_id');
      const preSucursalId = urlParams.get('sucursal_id');
      if (preCadenaId) {
        this.cadenaId = preCadenaId;
        if (preSucursalId) {
          this.pendingSucursalId = preSucursalId;
        }
      }

      // Prefill from server-side pedido (cuando se regresa desde step2)
      if (window.initialPrescription) {
        const data = window.initialPrescription;
        this.professionalLicense = data.cedula_profesional || '';
        if (Array.isArray(data.medications)) {
          this.medicationsItems = data.medications.map(m => ({
            id: m.id ?? '',
            name: m.name ?? '',
            quantity: parseInt(m.quantity) || 1
          }));
        }
        if (data.cadena_id) {
          this.cadenaId = data.cadena_id;
          this.pendingSucursalId = data.sucursal_id ?? null;
          // Cuando cadenaId se asigna después de init, el watcher dispara loadSucursales
        }
      }
    },

    // --- Medication List Logic ---

    addMedication() {
      // Validate inputs
      if (!this.medicationName) {
        alert('Please select a medication');
        return;
      }
      // Ensure quantity is a positive integer
      const qty = parseInt(this.medicationQuantity);
      if (!this.medicationQuantity || isNaN(qty) || qty < 1) {
        alert('Please enter a valid quantity (minimum 1)');
        return;
      }

      // Check for duplicates
      const exists = this.medicationsItems.some(item => item.name === this.medicationName);
      if (exists) {
        alert('This medication is already in the list');
        return;
      }

      // Add to list
      this.medicationsItems.push({
        id: this.medicationId, // Can be empty string if manual entry
        name: this.medicationName,
        quantity: parseInt(this.medicationQuantity)
      });

      // Reset inputs
      this.medicationId = '';
      this.medicationName = '';
      this.medicationQuantity = '';

      // Clear autocomplete component
      window.dispatchEvent(new CustomEvent('clear-autocomplete'));

      // Return focus to medication name input
      this.$nextTick(() => {
        const medicationInput = document.getElementById('medication-name-input');
        if (medicationInput) {
          medicationInput.focus();
        }
      });
    },

    removeMedication(index) {
      this.medicationsItems.splice(index, 1);
    },

    incrementQuantity(index) {
      if (index >= 0 && index < this.medicationsItems.length) {
        this.medicationsItems[index].quantity += 1;
      }
    },

    decrementQuantity(index) {
      if (index >= 0 && index < this.medicationsItems.length && this.medicationsItems[index].quantity > 1) {
        this.medicationsItems[index].quantity -= 1;
      }
    },

    updateQuantity(index, newQuantity) {
      const quantity = parseInt(newQuantity);
      if (index >= 0 && index < this.medicationsItems.length) {
        if (quantity > 0) {
          this.medicationsItems[index].quantity = quantity;
        } else {
          this.medicationsItems[index].quantity = 1;
        }
      }
    },

    // --- Branch Selection Logic ---

    async loadSucursales(cadenaId) {
      this.loadingSucursales = true;
      this.sucursales = [];
      this.sucursalId = ''; // Reset selection

      try {
        const response = await fetch(`/prescription/sucursales/${cadenaId}`);
        if (!response.ok) throw new Error('Failed to load branches');

        const data = await response.json();
        this.sucursales = data;

        // If we have a pending selection from URL, apply it
        if (this.pendingSucursalId) {
          // Verify the pending ID exists in the loaded branches
          // Note: API returns 'sucursal_id' not 'id', and we use loose equality for string/int match
          const exists = this.sucursales.find(s => s.sucursal_id == this.pendingSucursalId);
          if (exists) {
            // Use the exact value from the data to ensure strict equality match in select
            this.sucursalId = exists.sucursal_id;
          }
          this.pendingSucursalId = null; // Clear pending state
        }
      } catch (error) {
        console.error('Error loading sucursales:', error);
        alert('Error loading branches. Please try again.');
      } finally {
        this.loadingSucursales = false;
      }
    },


  };
};
