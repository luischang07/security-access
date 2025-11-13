<?php

return [
  // Administrator Dashboard
  'dashboard' => [
    'title' => 'Admin Dashboard',
    'welcome' => 'Welcome, Administrator',
    'overview' => 'System Overview',
    'total_patients' => 'Total Registered Patients',
    'active_pharmacies' => 'Total Active Pharmacies',
    'prescriptions_today' => 'Prescriptions Processed Today',
    'avg_fulfillment' => 'Average Fulfillment Time',
    'new_registrations' => 'New User Registrations',
    'last_30_days' => 'Last 30 days',
    'quick_actions' => 'Quick Actions',
    'manage_users' => 'Manage Users',
    'manage_pharmacies' => 'Manage Pharmacies',
    'view_reports' => 'View Reports',
    'manage_penalties' => 'Manage Penalties',
    'recent_activity' => 'Recent Activity',
    'activity' => [
      'new_user' => 'New user registered',
      'pharmacy_registered' => 'New pharmacy registered',
      'order_completed' => 'Order completed',
      'penalty_issued' => 'Penalty issued',
      'system_update' => 'System updated',
      'time_ago' => ':time minutes ago',
    ],
    'stats' => [
      'total_users' => 'Total Users',
      'total_pharmacies' => 'Total Pharmacies',
      'active_orders' => 'Active Orders',
      'total_revenue' => 'Total Revenue',
    ],
    'sidebar' => [
      'dashboard' => 'Dashboard',
      'users' => 'User Management',
      'pharmacies' => 'Pharmacy Management',
      'orders' => 'Order Overview',
      'penalties' => 'Penalties',
      'reports' => 'Reports & Analytics',
      'settings' => 'System Settings',
      'logout' => 'Logout',
    ],
  ],

  // User Management
  'users' => [
    'title' => 'User Management',
    'patients' => 'Patients',
    'pharmacy_employees' => 'Pharmacy Employees',
    'administrators' => 'Administrators',
    'search' => 'Search users...',
    'add_new' => 'Add New User',
    'user_details' => 'User Details',
    'actions' => [
      'view' => 'View',
      'edit' => 'Edit',
      'deactivate' => 'Deactivate',
      'delete' => 'Delete',
    ],
  ],

  // Pharmacy Management
  'pharmacies' => [
    'title' => 'Pharmacy Management',
    'search' => 'Search pharmacies...',
    'add_new' => 'Add New Pharmacy',
    'pharmacy_details' => 'Pharmacy Details',
    'active' => 'Active',
    'inactive' => 'Inactive',
    'actions' => [
      'view' => 'View',
      'edit' => 'Edit',
      'deactivate' => 'Deactivate',
      'delete' => 'Delete',
    ],
  ],

  // Penalties
  'penalties' => [
    'title' => 'Penalty Management',
    'active' => 'Active Penalties',
    'resolved' => 'Resolved Penalties',
    'add_penalty' => 'Add Penalty',
    'penalty_details' => 'Penalty Details',
    'user' => 'User',
    'reason' => 'Reason',
    'date' => 'Date',
    'status' => 'Status',
    'actions' => [
      'view' => 'View',
      'resolve' => 'Resolve',
      'delete' => 'Delete',
    ],
  ],
];
