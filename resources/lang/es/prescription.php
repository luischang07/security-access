<?php

return [
  // Prescription Upload Step 1
  'upload_step1' => [
    'title' => 'Subir Receta - Paso 1',
    'heading' => 'Sube tu Receta Médica',
    'subtitle' => 'Sube una foto clara o PDF de tu receta para comenzar.',
    'drag_drop' => 'Arrastra y suelta tu archivo aquí o haz clic para subir',
    'accepted_formats' => 'Formatos aceptados: JPG, PNG, PDF. Tamaño máximo: 10MB.',
    'browse_files' => 'Buscar Archivos',
    'or_manual' => 'O ingresa los detalles manualmente',
    'prescription_details' => 'Detalles de la Receta',
    'patient_name' => 'Nombre del Paciente',
    'patient_name_placeholder' => 'ej., Jane Doe',
    'doctor_name' => 'Nombre del Médico',
    'doctor_name_placeholder' => 'ej., Dr. John Smith',
    'medications' => 'Medicamentos',
    'medication_name' => 'Nombre del Medicamento',
    'medication_name_placeholder' => 'ej., Amoxicilina',
    'dosage' => 'Dosificación',
    'dosage_placeholder' => 'ej., 500mg',
    'quantity' => 'Cantidad',
    'quantity_placeholder' => 'ej., 30',
    'add_medication' => 'Agregar Otro Medicamento',
    'special_instructions' => 'Instrucciones Especiales',
    'special_instructions_placeholder' => 'Agrega cualquier instrucción especial para la farmacia aquí...',
    'submit' => 'Enviar Receta',
    'dashboard' => 'Panel',
  ],

  // Prescription Upload Step 2
  'upload_step2' => [
    'title' => 'Resumen del Pedido',
    'heading' => 'Revisa tu Pedido',
    'subtitle' => 'Por favor confirma los detalles a continuación antes de realizar tu pedido.',
    'selected_pharmacy' => 'Farmacia Seleccionada',
    'estimated_pickup' => 'Hora Estimada de Recolección',
    'pickup_notification' => 'Recibirás una notificación cuando esté listo.',
    'prescribed_medications' => 'Medicamentos Recetados',
    'medication' => 'Medicamento',
    'quantity' => 'Cantidad',
    'price' => 'Precio',
    'subtotal' => 'Subtotal',
    'service_fee' => 'Cargo por Servicio',
    'estimated_total' => 'Total Estimado',
    'price_disclaimer' => 'El precio final puede variar. Pagarás en la farmacia.',
    'edit_prescription' => 'Editar Receta',
    'confirm_order' => 'Confirmar Pedido',
    'capsules' => 'Cápsulas',
    'tablets' => 'Tabletas',
  ],

  // Pharmacy Selection Map
  'pharmacy_map' => [
    'title' => 'Seleccionar una Farmacia',
    'heading' => 'Encuentra una Farmacia Cerca de Ti',
    'subtitle' => 'Selecciona una farmacia que tenga tu medicamento en stock',
    'search_placeholder' => 'Buscar por ubicación o nombre de farmacia...',
    'filters' => [
      'title' => 'Filtros',
      'open_now' => 'Abierto Ahora',
      'in_stock' => 'En Stock',
      'delivery' => 'Ofrece Entrega',
      '24_hours' => '24 Horas',
    ],
    'pharmacy_card' => [
      'distance' => 'A :distance km',
      'in_stock' => 'En Stock',
      'out_of_stock' => 'Agotado',
      'open' => 'Abierto',
      'closed' => 'Cerrado',
      'select' => 'Seleccionar Farmacia',
      'view_details' => 'Ver Detalles',
    ],
  ],

  // Common
  'status' => [
    'uploading' => 'Subiendo...',
    'processing' => 'Procesando receta...',
    'success' => '¡Receta subida exitosamente!',
    'error' => 'Error al subir la receta. Por favor intenta de nuevo.',
  ],
];
