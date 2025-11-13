<?php

return [
  // Prescription Upload Step 1
  'upload_step1' => [
    'title' => 'Upload Prescription - Step 1',
    'heading' => 'Upload Your Medical Prescription',
    'subtitle' => 'Upload a clear photo or PDF of your prescription to get started.',
    'drag_drop' => 'Drag & drop your file here or click to upload',
    'accepted_formats' => 'Accepted formats: JPG, PNG, PDF. Max size: 10MB.',
    'browse_files' => 'Browse Files',
    'or_manual' => 'Or enter details manually',
    'prescription_details' => 'Prescription Details',
    'patient_name' => 'Patient Name',
    'patient_name_placeholder' => 'e.g., Jane Doe',
    'doctor_name' => 'Doctor\'s Name',
    'doctor_name_placeholder' => 'e.g., Dr. John Smith',
    'medications' => 'Medications',
    'medication_name' => 'Medication Name',
    'medication_name_placeholder' => 'e.g., Amoxicillin',
    'dosage' => 'Dosage',
    'dosage_placeholder' => 'e.g., 500mg',
    'quantity' => 'Quantity',
    'quantity_placeholder' => 'e.g., 30',
    'add_medication' => 'Add Another Medication',
    'special_instructions' => 'Special Instructions',
    'special_instructions_placeholder' => 'Add any special instructions for the pharmacy here...',
    'submit' => 'Submit Prescription',
    'dashboard' => 'Dashboard',
  ],  // Prescription Upload Step 2
  'upload_step2' => [
    'title' => 'Upload Prescription - Step 2',
    'heading' => 'Confirm Prescription Details',
    'subtitle' => 'Please review the information we extracted from your prescription',
    'medication' => 'Medication',
    'dosage' => 'Dosage',
    'quantity' => 'Quantity',
    'refills' => 'Refills',
    'doctor' => 'Prescribing Doctor',
    'edit' => 'Edit',
    'confirm' => 'Confirm and Continue',
    'back' => 'Back',
  ],

  // Pharmacy Selection Map
  'pharmacy_map' => [
    'title' => 'Select a Pharmacy',
    'heading' => 'Find a Pharmacy Near You',
    'subtitle' => 'Select a pharmacy that has your medication in stock',
    'search_placeholder' => 'Search by location or pharmacy name...',
    'filters' => [
      'title' => 'Filters',
      'open_now' => 'Open Now',
      'in_stock' => 'In Stock',
      'delivery' => 'Offers Delivery',
      '24_hours' => '24 Hours',
    ],
    'pharmacy_card' => [
      'distance' => ':distance km away',
      'in_stock' => 'In Stock',
      'out_of_stock' => 'Out of Stock',
      'open' => 'Open',
      'closed' => 'Closed',
      'select' => 'Select Pharmacy',
      'view_details' => 'View Details',
    ],
  ],

  // Common
  'status' => [
    'uploading' => 'Uploading...',
    'processing' => 'Processing prescription...',
    'success' => 'Prescription uploaded successfully!',
    'error' => 'Error uploading prescription. Please try again.',
  ],
];
