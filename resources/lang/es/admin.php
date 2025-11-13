<?php

return [
  // Administrator Dashboard
  'dashboard' => [
    'title' => 'Panel de Administración',
    'welcome' => 'Bienvenido, Administrador',
    'overview' => 'Vista General del Sistema',
    'total_patients' => 'Total de Pacientes Registrados',
    'active_pharmacies' => 'Total de Farmacias Activas',
    'prescriptions_today' => 'Recetas Procesadas Hoy',
    'avg_fulfillment' => 'Tiempo Promedio de Entrega',
    'new_registrations' => 'Nuevos Registros de Usuarios',
    'last_30_days' => 'Últimos 30 días',
    'quick_actions' => 'Acciones Rápidas',
    'manage_users' => 'Administrar Usuarios',
    'manage_pharmacies' => 'Administrar Farmacias',
    'view_reports' => 'Ver Reportes',
    'manage_penalties' => 'Administrar Penalizaciones',
    'recent_activity' => 'Actividad Reciente',
    'activity' => [
      'new_user' => 'Nuevo usuario registrado',
      'pharmacy_registered' => 'Nueva farmacia registrada',
      'order_completed' => 'Pedido completado',
      'penalty_issued' => 'Penalización emitida',
      'system_update' => 'Sistema actualizado',
      'time_ago' => 'hace :time minutos',
    ],
    'stats' => [
      'total_users' => 'Total de Usuarios',
      'total_pharmacies' => 'Total de Farmacias',
      'active_orders' => 'Pedidos Activos',
      'total_revenue' => 'Ingresos Totales',
    ],
    'sidebar' => [
      'dashboard' => 'Panel',
      'users' => 'Gestión de Usuarios',
      'pharmacies' => 'Gestión de Farmacias',
      'orders' => 'Vista General de Pedidos',
      'penalties' => 'Penalizaciones',
      'reports' => 'Reportes y Análisis',
      'settings' => 'Configuración del Sistema',
      'logout' => 'Cerrar Sesión',
    ],
  ],

  // User Management
  'users' => [
    'title' => 'Gestión de Usuarios',
    'patients' => 'Pacientes',
    'pharmacy_employees' => 'Empleados de Farmacia',
    'administrators' => 'Administradores',
    'search' => 'Buscar usuarios...',
    'add_new' => 'Agregar Nuevo Usuario',
    'user_details' => 'Detalles del Usuario',
    'actions' => [
      'view' => 'Ver',
      'edit' => 'Editar',
      'deactivate' => 'Desactivar',
      'delete' => 'Eliminar',
    ],
  ],

  // Pharmacy Management
  'pharmacies' => [
    'title' => 'Gestión de Farmacias',
    'search' => 'Buscar farmacias...',
    'add_new' => 'Agregar Nueva Farmacia',
    'pharmacy_details' => 'Detalles de la Farmacia',
    'active' => 'Activa',
    'inactive' => 'Inactiva',
    'actions' => [
      'view' => 'Ver',
      'edit' => 'Editar',
      'deactivate' => 'Desactivar',
      'delete' => 'Eliminar',
    ],
  ],

  // Penalties
  'penalties' => [
    'title' => 'Gestión de Penalizaciones',
    'active' => 'Penalizaciones Activas',
    'resolved' => 'Penalizaciones Resueltas',
    'add_penalty' => 'Agregar Penalización',
    'penalty_details' => 'Detalles de la Penalización',
    'user' => 'Usuario',
    'reason' => 'Razón',
    'date' => 'Fecha',
    'status' => 'Estado',
    'actions' => [
      'view' => 'Ver',
      'resolve' => 'Resolver',
      'delete' => 'Eliminar',
    ],
  ],
];
